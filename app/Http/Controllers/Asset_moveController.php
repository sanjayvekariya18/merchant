<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\App;
use Illuminate\Http\Request;
use App\Http\Controllers\PermissionsController;
use App\Helpers\ConnectionManager;
use App\Http\Traits\PermissionTrait;
use App\Asset_move;
use App\Asset;
use App\Account;
use App\Identity_account;
use App\Merchant;
use App\Customer;
use App\Merchant_customer_list;
use App\Merchant_account_list;
use App\Identity_table_type;
use Amranidev\Ajaxis\Ajaxis;
use URL;
use Session;
use DB;
use Redirect;
use Auth;
use DateTime;
use Config;
use Carbon\Carbon;

/**
 * Class Asset_moveController.
 *
 * @author  The scaffold-interface created at 2018-03-08 12:32:05pm
 * @link  https://github.com/amranidev/scaffold-interface
 */
class Asset_moveController extends PermissionsController
{
    use PermissionTrait;

    public function __construct()
    {
        parent::__construct();

        $connectionStatus = ConnectionManager::setDbConfig('Asset_move');

        if ($connectionStatus['type'] === "error") {

            Session::flash('type', $connectionStatus['type']);
            Session::flash('msg', $connectionStatus['message']);
            return Redirect::back()->send();
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return  \Illuminate\Http\Response
     */
    public function index()
    {
        if($this->permissionDetails('Asset_move','access')){
            $asset_moves = Asset_move::paginate(6);
            return view('asset_move.index',compact('asset_moves'));
        }else{
            return Redirect::back()->with('message', 'You are not authorized to use this functionality!');
        }
    }

    public function getAssetMerchants()
    {
        $where = array();
        if($this->merchantId == 0) {
            
        } else {
            if($this->roleId == 4){
                $where['merchant.merchant_id'] = $this->merchantId;
            }else{
                $where['merchant.merchant_id'] = $this->merchantId;
            }
        }
        $merchants = Merchant::
        select(
            'merchant.*',
            'identity_merchant.identity_name as merchant_name',
            'identity_merchant.identity_code as merchant_code'
        )
        ->join('identity_merchant','identity_merchant.identity_id','merchant.identity_id')
        ->where('identity_merchant.identity_id','!=',0)
        ->where(function($q) use ($where) {
            foreach($where as $key => $value){
                $q->where($key, '=', $value);
            }
        })->get();
        return json_encode($merchants);
    }

    public function getFilterAssets($flag)
    {
        return PermissionTrait::getAssets($flag);
    }

    public function getAssetMoveList(Request $request)
    {
        $where = array();
        if(isset($request->merchant_id)) {
            $where['asset_move.merchant_id'] = $request->merchant_id;
        }
        
        $asset_move_active_list=Asset_move::distinct()
            ->select(
                'asset_move.*',
                'account.account_code_long',
                'identity_merchant.identity_name as merchant_name',
                'identity_merchant.identity_code as merchant_code',
                'identity_account.identity_name as account_name',
                'identity_account.identity_description as account_description',
                'identity_asset.identity_code as asset_code',
                'identity_asset.identity_name as asset_name',
                'trade_orders.*',
                'approval_status.approval_status_name as status_name'
            )
            ->join('account','account.account_id','asset_move.account_from_id')
            ->join('identity_account','identity_account.identity_id','account.identity_id')
            ->join('merchant','merchant.merchant_id','asset_move.merchant_id')
            ->join('identity_merchant','identity_merchant.identity_id','merchant.identity_id')
            ->join('asset','asset.asset_id','asset_move.asset_id')
            ->join('identity_asset','identity_asset.identity_id','asset.identity_id')
            ->leftjoin('trade_orders','trade_orders.order_id','asset_move.source_id')
            ->join('approval_status','approval_status.approval_status_id','asset_move.status')
            ->where('asset_move.status','!=',9)
            ->where(function($q) use ($where) {
                foreach($where as $key => $value) {
                    $q->where($key, '=', $value);
                }
            })
            ->offset($request->skip)
            ->limit($request->take)
            ->get()->toArray();

        $total_records = Asset_move::
            distinct()
            ->join('account','account.account_id','asset_move.account_from_id')
            ->join('identity_account','identity_account.identity_id','account.identity_id')
            ->join('merchant','merchant.merchant_id','asset_move.merchant_id')
            ->join('identity_merchant','identity_merchant.identity_id','merchant.identity_id')
            ->join('asset','asset.asset_id','asset_move.asset_id')
            ->join('identity_asset','identity_asset.identity_id','asset.identity_id')
            ->leftjoin('trade_orders','trade_orders.order_id','asset_move.source_id')
            ->join('approval_status','approval_status.approval_status_id','asset_move.status')
            ->where('asset_move.status','!=',9)
            ->where(function($q) use ($where) {
                foreach($where as $key => $value) {
                    $q->where($key, '=', $value);
                }
            })
            ->count();
            
                
        foreach($asset_move_active_list as $key => $value)
        {
            if($value['source_table'] == 32) {
                $asset_move_active_list[$key]['asset_into_id'] = '';
                $asset_move_active_list[$key]['asset_into_price'] = '';
                $asset_move_active_list[$key]['asset_into_quantity'] = '';
            }

            $currentApprovalStatus =  $value['status'];
            $rowstatusList=DB::table('approval_status')
            ->distinct('approval_status.approval_status_name')
            ->select('approval_status.approval_status_id','approval_status.approval_status_name')
            ->join('approval_group_list', function($join) use($currentApprovalStatus) {
                  $join->on('approval_status.approval_status_id', '=', 'approval_group_list.target_approval_status_id')
                        ->where('approval_group_list.source_staff_group_id', $this->roleId)
                        ->where('approval_group_list.source_approval_status_id', $currentApprovalStatus);
                })
            ->where('approval_status.approval_status_display',0)
            ->get();
            $rowStatusTargetList = array();
            if(isset($rowstatusList))
            {
                foreach ($rowstatusList as $rowstatusListey => $rowstatusListValue) {
                    $rowStatusTargetList[$rowstatusListey]['id'] = $rowstatusListValue->approval_status_id;
                    $rowStatusTargetList[$rowstatusListey]['name'] = $rowstatusListValue->approval_status_name;
                }
            }
            $asset_move_active_list[$key]['status'] = $rowStatusTargetList;
            $asset_move_active_list[$key]['statusCount'] = count($rowStatusTargetList);

            $statusList=DB::table('approval_status')
                ->where('approval_status_id','=',$currentApprovalStatus)->get()->first();

            if(isset($statusList)){
                $asset_move_active_list[$key]['statusId']=$statusList->approval_status_id;
                $asset_move_active_list[$key]['statusName']=$statusList->approval_status_name;
                $asset_move_active_list[$key]['statusDisplay']=$statusList->approval_status_display;
                $asset_move_active_list[$key]['statusColor']=$statusList->approval_status_color;
                $asset_move_active_list[$key]['statusFontColor']=$statusList->approval_status_font_color;
            }

            if(isset($asset_move_active_list[$key]['comment_id'])){
                $asset_move_active_list[$key]['comment']=$asset_move_active_list[$key]['comment_id'];
            }else{
                $asset_move_active_list[$key]['comment']='';
            }
        }

        $asset_move_active_list_data['active_list'] = $asset_move_active_list;
        $asset_move_active_list_data['total'] = $total_records;

        return json_encode($asset_move_active_list_data);
    }

    public function getAssetMoveHistoryList(Request $request) {        
        $asset_move_history_list=Asset_move::
            distinct()
            ->select(
                    'asset_move.*',
                    'account.account_code_long',
                    'identity_merchant.identity_name as merchant_name',
                    'identity_merchant.identity_code as merchant_code',
                    'identity_account.identity_name as account_name',
                    'identity_account.identity_description as account_description',
                    'identity_asset.identity_code as asset_code',
                    'identity_asset.identity_name as asset_name',
                    'trade_orders.*',
                    'approval_status.approval_status_name as status_name'
                )
            ->join('account','account.account_id','asset_move.account_from_id')
            ->join('identity_account','identity_account.identity_id','account.identity_id')
            ->join('merchant','merchant.merchant_id','asset_move.merchant_id')
            ->join('identity_merchant','identity_merchant.identity_id','merchant.identity_id')
            ->join('asset','asset.asset_id','asset_move.asset_id')
            ->join('identity_asset','identity_asset.identity_id','asset.identity_id')
            ->leftjoin('trade_orders','trade_orders.order_id','asset_move.source_id')
            ->join('approval_status','approval_status.approval_status_id','asset_move.status')
            ->where('asset_move.status','=',9)
            ->offset($request->skip)
            ->limit($request->take)
            ->get()->toArray();

        $total_records=Asset_move::
            distinct()
            ->join('account','account.account_id','asset_move.account_from_id')
            ->join('identity_account','identity_account.identity_id','account.identity_id')
            ->join('merchant','merchant.merchant_id','asset_move.merchant_id')
            ->join('identity_merchant','identity_merchant.identity_id','merchant.identity_id')
            ->join('asset','asset.asset_id','asset_move.asset_id')
            ->join('identity_asset','identity_asset.identity_id','asset.identity_id')
            ->leftjoin('trade_orders','trade_orders.order_id','asset_move.source_id')
            ->join('approval_status','approval_status.approval_status_id','asset_move.status')
            ->where('asset_move.status','=',9)
            ->count();
                
        foreach($asset_move_history_list as $key => $value)
        {
            if($value['source_table'] == 32) {
                $asset_move_history_list[$key]['asset_into_price'] = '';
                $asset_move_history_list[$key]['asset_into_quantity'] = '';
            }
            $currentApprovalStatus =  $value['status'];
            $statusList=DB::table('approval_status')
                ->where('approval_status_id','=',$currentApprovalStatus)->get()->first();

            if(isset($statusList)){
                $asset_move_history_list[$key]['statusId']=$statusList->approval_status_id;
                $asset_move_history_list[$key]['statusName']=$statusList->approval_status_name;
                $asset_move_history_list[$key]['statusDisplay']=$statusList->approval_status_display;
                $asset_move_history_list[$key]['statusColor']=$statusList->approval_status_color;
                $asset_move_history_list[$key]['statusFontColor']=$statusList->approval_status_font_color;
            }
            if(isset($asset_move_history_list[$key]['comment_id'])){
                $asset_move_history_list[$key]['comment']=$asset_move_history_list[$key]['comment_id'];
            }else{
                $asset_move_history_list[$key]['comment']='';
            }
        }

        $asset_move_history_list_data['history_list'] = $asset_move_history_list;
        $asset_move_history_list_data['total'] = $total_records;
        
        return json_encode($asset_move_history_list_data);
    }

    public function getAccountInfo($accountId)
    {
        $asset = Account::select(
            'account.*',
            'identity_account.identity_name as account_name',
            'identity_account.identity_code as account_code'
        )
        ->join('identity_account','identity_account.identity_id','account.identity_id')
        ->where('account_id',$accountId)
        ->get()->first();
        return $asset;
    }

    public function getAssetAccounts($merchantId)
    {
        $accounts = Merchant_account_list::
            select(
                'account.account_id',
                'identity_account.identity_name as account_name',
                'identity_account.identity_code as account_code'
            )
            ->join('account','account.account_id','merchant_account_list.staff_account_id')
            ->join('identity_account','identity_account.identity_id','account.identity_id')
            ->where('merchant_account_list.merchant_id',$merchantId)
            ->where('merchant_account_list.staff_account_id','!=',0)
            ->get();
        return json_encode($accounts);
    }

    public function updateAssetMoveEntry(Request $request)
    {
        $statusList=DB::table('approval_group_list')
                ->distinct('approval_group_list.target_approval_status_id')
                ->join('approval_status','approval_status.approval_status_id','=','approval_group_list.source_approval_status_id')
                ->where('approval_group_list.source_staff_group_id','=',$this->roleId)
                ->where('approval_status.approval_status_name','=',$request->status_code)
                ->get()->first();

        if(isset($statusList) && $statusList->approval_status_code != 'comment' && $statusList->approval_status_code != 'noop') {

            $statusId=$statusList->target_approval_status_id;
            $asset_move = Asset_move::findOrfail($request->move_id);

            $statusList=DB::table('approval_status')
                ->where('approval_status_id',$statusId)->get()->first();
            if($statusList->approval_status_name === 'Accepted')
            {

                $moveSourceTableName = Identity_table_type::where('type_id',$asset_move->source_table)->get()->first();
                if($asset_move->source_table = 17)
                {
                    $sourceTableData = DB::table($moveSourceTableName->table_code)->where('order_id',$asset_move->source_id)->get()->first();
                    $assetIntoId = $sourceTableData->asset_into_id;
                    $assetIntoQuantity = $sourceTableData->asset_into_quantity;
                    $assetIntoPrice = $sourceTableData->asset_into_price;

                } else {
                    $sourceTableData = DB::table($moveSourceTableName)->where('fund_id',$asset_move->source_id)->get()->first();
                    $assetIntoId = '';
                    $assetIntoQuantity = '';
                    $assetIntoPrice = '';
                }
                $assetFromId = $asset_move->asset_id;
                $accountFromId = $asset_move->account_from_id;
                $assetFromQuantity = $asset_move->asset_quantity;
                $assetFromPrice = $asset_move->asset_price;
                $accountIntoId = $asset_move->account_into_id;
                
                $tradeSideTypeList=DB::table('asset_type')
                    ->join('asset as ass','ass.asset_type_id','=','asset_type.asset_type_id')
                    ->where('ass.asset_id','=',$assetFromId)
                    ->select('asset_type.asset_type_code')
                    ->get()->first();
                if($tradeSideTypeList->asset_type_code === 'fiat') {
                    /* trade sell       customer buy*/

                    /* trader account update */
                    $traderAccountObject = Account::findOrfail($accountFromId);
                    $traderQuantityRemain = $traderAccountObject->asset_quantity- $assetFromQuantity;
                    if($traderQuantityRemain != 0)
                    {
                        if($traderAccountObject->asset_quantity == 0)
                        {
                            $currentTraderPosition = $traderAccountObject->asset_quantity * $traderAccountObject->asset_price;
                            $tradeTraderPosition =  $assetFromQuantity*$assetFromPrice;
                            $totalTraderQuantity =  $traderAccountObject->asset_quantity+$assetFromQuantity;
                            $traderAccountObject->asset_price = ($currentTraderPosition + $tradeTraderPosition)/($totalTraderQuantity);
                        }
                        $traderAccountObject->asset_quantity = $traderAccountObject->asset_quantity- $assetFromQuantity;
                    }
                    else {
                        $traderAccountObject->asset_price = 0;
                        $traderAccountObject->asset_quantity = 0;
                    }
                    $traderAccountObject->save();
                    /* end trader account update */

                    /* Customer account update */
                    $customerAccountObject = Account::findOrfail($accountIntoId);
                    $customerQuantityRemain = $customerAccountObject->asset_quantity+ $assetIntoQuantity;
                    if($customerQuantityRemain != 0)
                    {
                        $currentCustomerPosition = $customerAccountObject->asset_quantity * $customerAccountObject->asset_price;
                        $tradeCustomerPosition =  $assetIntoQuantity*$assetIntoPrice;
                        $totalCustomerQuantity =  $customerAccountObject->asset_quantity+$assetIntoQuantity;
                        $customerAccountObject->asset_price = ($currentCustomerPosition + $tradeCustomerPosition)/($totalCustomerQuantity);
                        $customerAccountObject->asset_quantity = $customerAccountObject->asset_quantity+ $assetIntoQuantity;
                    } else {
                        $customerAccountObject->asset_quantity = 0;
                        $customerAccountObject->asset_price = 0;
                    }
                    $customerAccountObject->save();
                    /* end account update */
                }
                else {
                    /* trade buy       customer sell*/

                    $traderAccountObject = Account::findOrfail($accountFromId);
                    $traderQuantityRemain =  $traderAccountObject->asset_quantity+ $assetFromQuantity;
                    /* trader account update */
                    if($traderQuantityRemain != 0)
                    {
                        $currentTraderPosition = $traderAccountObject->asset_quantity * $traderAccountObject->asset_price;
                        $tradeTraderPosition =  $assetFromQuantity*$assetFromPrice;
                        $totalTraderQuantity =  $traderAccountObject->asset_quantity+$assetFromQuantity;
                        $traderAccountObject->asset_price = ($currentTraderPosition + $tradeTraderPosition)/($totalTraderQuantity);
                        $traderAccountObject->asset_quantity = $traderAccountObject->asset_quantity+ $assetFromQuantity;
                    } else {
                        $traderAccountObject->asset_quantity = 0;
                        $traderAccountObject->asset_price = 0;
                    }
                    $traderAccountObject->save();
                    /* end trader account update */

                    /* Customer account update */
                    $customerAccountObject = Account::findOrfail($accountIntoId);
                    $customerQuantityRemain = $customerAccountObject->asset_quantity- $assetIntoQuantity;
                    if($customerQuantityRemain != 0)
                    {
                        if($customerAccountObject->asset_quantity == 0)
                        {
                            $currentCustomerPosition = $customerAccountObject->asset_quantity * $customerAccountObject->asset_price;
                            $tradeCustomerPosition =  $assetIntoQuantity*$assetIntoPrice;
                            $totalCustomerQuantity =  $customerAccountObject->asset_quantity+$assetIntoQuantity;
                            $customerAccountObject->asset_price = ($currentCustomerPosition + $tradeCustomerPosition)/($totalCustomerQuantity);
                        }
                        $customerAccountObject->asset_quantity = $customerAccountObject->asset_quantity- $assetIntoQuantity;
                    } else {
                        $customerAccountObject->asset_quantity =0;
                        $customerAccountObject->asset_price =0;
                    }
                    $customerAccountObject->save();
                    /*end Customer account update */
                }
                $asset_move->status = $statusId;
                $asset_move->approval_staff_id = $this->staffId;
            }
            else {
                $asset_move->status = $statusId;
                $asset_move->approval_staff_id = $this->staffId;
            }
            $asset_move->save();
        }
    }

    
    public function updateMoveComments(Request $request)
    {
        $comment_Date = date('Ymd');
        $comment_time = time();
        DB::table('asset_move')->where('move_id','=',$request->moveId)->update(array('approval_staff_id'=>$this->roleId));

        $comment_move_id= DB::table('asset_move')->where('move_id','=',$request->moveId)->get()->first();

        if(!isset($comment_move_id->comment_id)){
                    $comment_id = DB::table('approval_comment')->insertGetId(
                    ['comment'=>$request->approveComments,'comment_date'=>$comment_Date,'comment_time'=>$comment_time,'comment_parent_id'=>'','comment_source_id'=>$this->roleId]);
                    DB::table('asset_move')->where('move_id','=',$request->moveId)->update(array('comment_id'=>$comment_id));
                    DB::table('approval_comment')->where('comment_id','=',$comment_id)->update(array('comment_root_id'=>$comment_id));

        }else{
                $comment_id = DB::table('approval_comment')->insertGetId(
               ['comment'=>$request->approveComments,'comment_date'=>$comment_Date,'comment_time'=>$comment_time,'comment_parent_id'=>$comment_move_id->comment_id,'comment_source_id'=>$this->roleId,'comment_root_id'=>$comment_move_id->comment_id]);  
        }
    }
    public function moveCommentList(Request $request)
    {
        $rejectCommentList=array();
        $rejectApproveCommentList = DB::table('approval_comment')->where('comment_root_id','=',$request->commentId)->get();              
        foreach ($rejectApproveCommentList as $rejectApproveCommentListValue) {
            $actionByValue = DB::table('staff')->select('identity_name')->leftjoin('identity_staff','staff.identity_id','identity_staff.identity_id')->where('staff_id','=',$rejectApproveCommentListValue->comment_source_id)->get()->first();
            $startDateTime = json_decode($this->covertToLocalTz($rejectApproveCommentListValue->comment_time), true);
            $rejectCommentList[]=array('commentDate'=>$startDateTime['date'],'commentTime'=>$startDateTime['time'],'comment'=>$rejectApproveCommentListValue->comment,'commentedBy'=>$actionByValue->identity_name);
        }

       return $rejectCommentList;
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return  \Illuminate\Http\Response
     */
    public function create()
    {
        $title = 'Create - asset_move';
        
        return view('asset_move.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param    \Illuminate\Http\Request  $request
     * @return  \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $asset_move = new Asset_move();

        $asset_move->source_table = $request->source_table;
      
        $asset_move->source_id = $request->source_id;
     
        $asset_move->merchant_id = $request->merchant_id;
     
        $asset_move->staff_group_id = $request->staff_group_id;
    
        $asset_move->staff_id = $request->staff_id;
    
        $asset_move->account_from_id = $request->account_from_id;
    
        $asset_move->account_into_id = $request->account_into_id;
    
        $asset_move->asset_id = $request->asset_id;
      
        $asset_move->asset_price = $request->asset_price;
     
        $asset_move->asset_quantity = $request->asset_quantity;
     
        $asset_move->move_timezone = $request->move_timezone;
     
        $asset_move->move_date = $request->move_date;
     
        $asset_move->move_time = $request->move_time;
     
        $asset_move->status = $request->status;
 
        $asset_move->save();

        return redirect('asset_move');
    }

    /**
     * Display the specified resource.
     *
     * @param    \Illuminate\Http\Request  $request
     * @param    int  $id
     * @return  \Illuminate\Http\Response
     */
    public function show($id,Request $request)
    {
        $title = 'Show - asset_move';

        if($request->ajax())
        {
            return URL::to('asset_move/'.$id);
        }

        $asset_move = Asset_move::findOrfail($id);
        return view('asset_move.show',compact('title','asset_move'));
    }

    /**
     * Show the form for editing the specified resource.
     * @param    \Illuminate\Http\Request  $request
     * @param    int  $id
     * @return  \Illuminate\Http\Response
     */
    public function edit($id,Request $request)
    {                
        $asset_move = Asset_move::findOrfail($id);
        return view('asset_move.edit',compact('asset_move'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param    \Illuminate\Http\Request  $request
     * @param    int  $id
     * @return  \Illuminate\Http\Response
     */
    public function update($id,Request $request)
    {
        $asset_move = Asset_move::findOrfail($id);
    	        
        $asset_move->source_table = $request->source_table;
        
        $asset_move->source_id = $request->source_id;
        
        $asset_move->merchant_id = $request->merchant_id;
        
        $asset_move->staff_group_id = $request->staff_group_id;
        
        $asset_move->staff_id = $request->staff_id;
        
        $asset_move->account_from_id = $request->account_from_id;
        
        $asset_move->account_into_id = $request->account_into_id;
        
        $asset_move->asset_id = $request->asset_id;
        
        $asset_move->asset_price = $request->asset_price;
        
        $asset_move->asset_quantity = $request->asset_quantity;
        
        $asset_move->move_timezone = $request->move_timezone;
        
        $asset_move->move_date = $request->move_date;
        
        $asset_move->move_time = $request->move_time;
        
        $asset_move->status = $request->status;
        
        $asset_move->save();

        return redirect('asset_move');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param    int $id
     * @return  \Illuminate\Http\Response
     */
    public function destroy($id)
    {
     	$asset_move = Asset_move::findOrfail($id);
     	$asset_move->delete();
        return URL::to('asset_move');
    }
}
