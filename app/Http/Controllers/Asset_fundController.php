<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\App;
use Illuminate\Http\Request;
use App\Http\Controllers\PermissionsController;
use App\Helpers\ConnectionManager;
use App\Asset_fund;
use App\Staff_group;
use App\Staff;
use App\Account;
use App\Asset;
use App\Timezone;
use App\Merchant;
use App\Fund_type;
use App\Merchant_account_list;
use App\Trade_limits;
use App\Asset_move;
use App\Trade_risk;
use App\Asset_fund_image;
use App\Identity_table_type;

use Amranidev\Ajaxis\Ajaxis;
use App\Http\Controllers\ProductApprovalController;
use App\Http\Traits\PermissionTrait;

use URL;
use Auth;
use Session;
use DB;
use Redirect;

/**
 * Class Asset_fundController.
 *
 * @author  The scaffold-interface created at 2018-03-10 08:47:45pm
 * @link  https://github.com/amranidev/scaffold-interface
 */
class Asset_fundController extends PermissionsController
{
    use PermissionTrait;

    public function __construct()
    {
        parent::__construct();

        $connectionStatus = ConnectionManager::setDbConfig('Asset_fund');

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
        if($this->permissionDetails('Asset_fund','access')) {
            $where = array();
            $permissions = $this->getPermission("Asset_fund");
            return view('asset_fund.index',compact('permissions'));
        } else {
         return Redirect::back()->with('message', 'You are not authorized to use this functionality!');
        }
    }

    public function getAssetFundList(Request $request)
    {
        $where = array();
        if($this->merchantId == 0){
            $where[] = array(
                'key' => "asset_fund.status",
                'operator' => '!=',
                'val' => 9
            );
        }else{
            $where[] = array(
                'key' => "asset_fund.merchant_id",
                'operator' => '=',
                'val' => $this->merchantId
            );
            $where[] = array(
                'key' => "asset_fund.status",
                'operator' => '!=',
                'val' => 9
            );
        }

        $asset_fund_active_list=Asset_fund::
            distinct()
            ->select(
                    'asset_fund.*',
                    'merchant_identity_account.identity_name as merchant_account_name',
                    'customer_identity_account.identity_name as customer_account_name',
                    'identity_merchant.identity_name as merchant_name',
                    'identity_merchant.identity_code as merchant_code',
                    'identity_asset.identity_code as asset_code',
                    'identity_asset.identity_name as asset_name',
                    'fund_type.type_id as fund_type_id',
                    'fund_type.type_name as fund_type_name',
                    'approval_status.approval_status_name as status_name',
                    'asset_fund_image.image_href'
                )
            ->leftjoin('account as merchant_account','merchant_account.account_id','asset_fund.merchant_account_id')
            ->leftjoin('identity_account as merchant_identity_account','merchant_identity_account.identity_id','merchant_account.identity_id')
            ->leftjoin('account as customer_account','customer_account.account_id','asset_fund.customer_account_id')
            ->leftjoin('identity_account as customer_identity_account','customer_identity_account.identity_id','customer_account.identity_id')
            ->leftjoin('asset_fund_image','asset_fund.fund_id','asset_fund_image.fund_id')
            ->join('merchant','merchant.merchant_id','asset_fund.merchant_id')
            ->join('identity_merchant','identity_merchant.identity_id','merchant.identity_id')
            ->join('asset','asset.asset_id','asset_fund.asset_id')
            ->join('identity_asset','identity_asset.identity_id','asset.identity_id')
            ->join('fund_type','fund_type.type_id','asset_fund.fund_type')
            ->join('approval_status','approval_status.approval_status_id','asset_fund.status')
            ->where(function($q) use ($where){
                foreach($where as $key => $value){
                    $q->where($value['key'], $value['operator'], $value['val']);
                }
            })
            ->offset($request->skip)
            ->limit($request->take)
            ->get()->toArray();

        $total_records=Asset_fund::
            distinct()
            ->leftjoin('account as merchant_account','merchant_account.account_id','asset_fund.merchant_account_id')
            ->leftjoin('identity_account as merchant_identity_account','merchant_identity_account.identity_id','merchant_account.identity_id')
            ->leftjoin('account as customer_account','customer_account.account_id','asset_fund.customer_account_id')
            ->leftjoin('identity_account as customer_identity_account','customer_identity_account.identity_id','customer_account.identity_id')
            ->leftjoin('asset_fund_image','asset_fund.fund_id','asset_fund_image.fund_id')
            ->join('merchant','merchant.merchant_id','asset_fund.merchant_id')
            ->join('identity_merchant','identity_merchant.identity_id','merchant.identity_id')
            ->join('asset','asset.asset_id','asset_fund.asset_id')
            ->join('identity_asset','identity_asset.identity_id','asset.identity_id')
            ->join('fund_type','fund_type.type_id','asset_fund.fund_type')
            ->join('approval_status','approval_status.approval_status_id','asset_fund.status')
            ->where(function($q) use ($where){
                foreach($where as $key => $value){
                    $q->where($value['key'], $value['operator'], $value['val']);
                }
            })->count();    

        foreach($asset_fund_active_list as $key => $value)
        {
            
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
                foreach ($rowstatusList as $rowstatusListKey => $rowstatusListValue) {
                    $rowStatusTargetList[$rowstatusListKey]['id'] = $rowstatusListValue->approval_status_id;
                    $rowStatusTargetList[$rowstatusListKey]['name'] = $rowstatusListValue->approval_status_name;


                    $datetimeData = json_decode($this->covertToLocalTz($value['fund_time']));
                    $asset_fund_active_list[$key]['fund_date'] = $datetimeData->date;
                    $asset_fund_active_list[$key]['fund_time'] = $datetimeData->time;
                }
            }
            $asset_fund_active_list[$key]['status'] = $rowStatusTargetList;
            $asset_fund_active_list[$key]['statusCount'] = count($rowStatusTargetList);

            $statusList=DB::table('approval_status')
                ->where('approval_status_id','=',$currentApprovalStatus)->get()->first();

            if(isset($statusList)){
                $asset_fund_active_list[$key]['statusId']=$statusList->approval_status_id;
                $asset_fund_active_list[$key]['statusName']=$statusList->approval_status_name;
                $asset_fund_active_list[$key]['statusDisplay']=$statusList->approval_status_display;
                $asset_fund_active_list[$key]['statusColor']=$statusList->approval_status_color;
                $asset_fund_active_list[$key]['statusFontColor']=$statusList->approval_status_font_color;
            }

            if(isset($asset_fund_active_list[$key]['comment_id'])){
                $asset_fund_active_list[$key]['comment']=$asset_fund_active_list[$key]['comment_id'];
            }else{
                $asset_fund_active_list[$key]['comment']='';
            }
        }

        $asset_fund_active_list_data['asset_fund'] = $asset_fund_active_list;
        $asset_fund_active_list_data['total'] = $total_records;

        return json_encode($asset_fund_active_list_data);
    }

    public function getAssetFundHistoryList(Request $request)
    {
        $where = array();
        if($this->merchantId == 0){
        
        }else{
            $where['asset_fund.merchant_id'] = $this->merchantId;
        }

        $asset_fund_history_list=Asset_fund::
            distinct()
            ->select(
                    'asset_fund.*',
                    'merchant_identity_account.identity_name as merchant_account_name',
                    'customer_identity_account.identity_name as customer_account_name',
                    'identity_merchant.identity_name as merchant_name',
                    'identity_merchant.identity_code as merchant_code',
                    'identity_asset.identity_code as asset_code',
                    'identity_asset.identity_name as asset_name',
                    'fund_type.type_id as fund_type_id',
                    'fund_type.type_name as fund_type_name',
                    'approval_status.approval_status_name as status_name',
                    'asset_fund_image.image_href'
                )
            ->leftjoin('account as merchant_account','merchant_account.account_id','asset_fund.merchant_account_id')
            ->leftjoin('identity_account as merchant_identity_account','merchant_identity_account.identity_id','merchant_account.identity_id')
            ->leftjoin('account as customer_account','customer_account.account_id','asset_fund.customer_account_id')
            ->leftjoin('identity_account as customer_identity_account','customer_identity_account.identity_id','customer_account.identity_id')
            ->leftjoin('asset_fund_image','asset_fund.fund_id','asset_fund_image.fund_id')
            ->join('merchant','merchant.merchant_id','asset_fund.merchant_id')
            ->join('identity_merchant','identity_merchant.identity_id','merchant.identity_id')
            ->join('asset','asset.asset_id','asset_fund.asset_id')
            ->join('identity_asset','identity_asset.identity_id','asset.identity_id')
            ->join('fund_type','fund_type.type_id','asset_fund.fund_type')
            ->join('approval_status','approval_status.approval_status_id','asset_fund.status')
            ->where('asset_fund.status','=',9)
            ->where(function($q) use ($where){
                foreach($where as $key => $value){
                    $q->where($value['key'], $value['operator'], $value['val']);
                }
            })
            ->offset($request->skip)
            ->limit($request->take)
            ->get()->toArray();

        $total_records=Asset_fund::
            distinct()
            ->leftjoin('account as merchant_account','merchant_account.account_id','asset_fund.merchant_account_id')
            ->leftjoin('identity_account as merchant_identity_account','merchant_identity_account.identity_id','merchant_account.identity_id')
            ->leftjoin('account as customer_account','customer_account.account_id','asset_fund.customer_account_id')
            ->leftjoin('identity_account as customer_identity_account','customer_identity_account.identity_id','customer_account.identity_id')
            ->leftjoin('asset_fund_image','asset_fund.fund_id','asset_fund_image.fund_id')
            ->join('merchant','merchant.merchant_id','asset_fund.merchant_id')
            ->join('identity_merchant','identity_merchant.identity_id','merchant.identity_id')
            ->join('asset','asset.asset_id','asset_fund.asset_id')
            ->join('identity_asset','identity_asset.identity_id','asset.identity_id')
            ->join('fund_type','fund_type.type_id','asset_fund.fund_type')
            ->join('approval_status','approval_status.approval_status_id','asset_fund.status')
            ->where('asset_fund.status','=',9)
            ->where(function($q) use ($where){
                foreach($where as $key => $value){
                    $q->where($value['key'], $value['operator'], $value['val']);
                }
            })
            ->count();    

        foreach($asset_fund_history_list as $key => $value)
        {
            $currentApprovalStatus =  $value['status'];
            $statusList=DB::table('approval_status')
                ->where('approval_status_id','=',$currentApprovalStatus)->get()->first();

            if(isset($statusList)){
                $asset_fund_history_list[$key]['statusId']=$statusList->approval_status_id;
                $asset_fund_history_list[$key]['statusName']=$statusList->approval_status_name;
                $asset_fund_history_list[$key]['statusDisplay']=$statusList->approval_status_display;
                $asset_fund_history_list[$key]['statusColor']=$statusList->approval_status_color;
                $asset_fund_history_list[$key]['statusFontColor']=$statusList->approval_status_font_color;
            }
            if(isset($asset_fund_history_list[$key]['comment_id'])){
                $asset_fund_history_list[$key]['comment']=$asset_fund_history_list[$key]['comment_id'];
            }else{
                $asset_fund_history_list[$key]['comment']='';
            }

            $datetimeData = json_decode($this->covertToLocalTz($value['fund_time']));
            $asset_fund_history_list[$key]['fund_date'] = $datetimeData->date;
            $asset_fund_history_list[$key]['fund_time'] = $datetimeData->time;
        }

        $asset_fund_history_list_data['asset_fund'] = $asset_fund_history_list;
        $asset_fund_history_list_data['total'] = $total_records;

        return json_encode($asset_fund_history_list_data);

    }

    public function updateAssetFundEntry(Request $request)
    {

        $statusList=DB::table('approval_group_list')
                ->distinct('approval_group_list.target_approval_status_id')
                ->join('approval_status','approval_status.approval_status_id','=','approval_group_list.source_approval_status_id')
                ->where('approval_group_list.source_staff_group_id','=',$this->roleId)
                ->where('approval_status.approval_status_name','=',$request->status_code)
                ->get()->first();
        if(isset($statusList) && $statusList->approval_status_code != 'comment' && $statusList->approval_status_code != 'noop') {
            $statusId=$statusList->target_approval_status_id;
            $asset_fund = Asset_fund::findOrfail($request->fund_id);
            $statusList=DB::table('approval_status')
                ->where('approval_status_id',$statusId)->get()->first();
            if($statusList->approval_status_name === 'Accepted')
            {
                $timeZoneId = PermissionTrait::getTimeZoneId();
                $tradeStartDate = date('Ymd');
                $tradeStartTime = time();

                $merchantAccountId = $asset_fund->merchant_account_id;
                $customerAccountId = $asset_fund->customer_account_id;
                $assetId = $asset_fund->asset_id;
                $merchantId = $asset_fund->merchant_id;
                $assetQuantity = $asset_fund->asset_quantity;
                $assetPrice = $asset_fund->asset_price;
                $trade_limits = Trade_limits::select('trade_limits.quantity_maximum',
                'trade_limits.breach')
                ->where('merchant_id', $merchantId)
                ->where('merchant_account_id', $merchantAccountId)
                ->where('asset_id', $assetId)
                ->get()->first();

                if($trade_limits && $assetQuantity >= $trade_limits->quantity_maximum) {
                    $sourceTable = Identity_table_type::select('type_id')
                        ->where('table_code', 'asset_fund')
                        ->get()->first();
                    $asset_move = new Asset_move();
                    $asset_move->source_table = $sourceTable->type_id;
                    $asset_move->source_id = $request->fund_id;
                    $asset_move->merchant_id = $merchantId;
                    $asset_move->staff_group_id = $this->roleId;
                    $asset_move->staff_id = $this->staffId;
                    $asset_move->account_from_id = $merchantAccountId;
                    $asset_move->account_into_id = $customerAccountId;
                    $asset_move->asset_id = $assetId;
                    $asset_move->asset_price = $assetPrice;
                    $asset_move->asset_quantity = $assetQuantity;
                    $asset_move->move_timezone = $timeZoneId;
                    $asset_move->move_date = $tradeStartDate;
                    $asset_move->move_time = $tradeStartTime;
                    $asset_move->status = 1;
                    $asset_move->approval_staff_id = $this->staffId;
                    $asset_move->save();

                    if($trade_limits->breach == 1) {
                        $trade_breach = new Trade_risk();
                        $trade_breach->merchant_id = $merchantId;
                        $trade_breach->location_id = 0;
                        $trade_breach->group_id = $this->roleId;
                        $trade_breach->staff_id = $this->staffId;
                        $trade_breach->merchant_account_id = $merchantAccountId;
                        $trade_breach->customer_account_id = $customerAccountId;
                        $trade_breach->exchange_id = 1;
                        $trade_breach->asset_id = $assetId;
                        $trade_breach->entry_timezone = $timeZoneId;
                        $trade_breach->entry_date = $tradeStartDate;
                        $trade_breach->entry_time = $tradeStartTime;
                        $trade_breach->price_average = 0;
                        $trade_breach->trade_exposure = 0;
                        $trade_breach->settlement_limit = 0;
                        $trade_breach->settlement_limit_status = 1;
                        $trade_breach->trading_limit = 0;
                        $trade_breach->trading_limit_status = 1;
                        $trade_breach->save();
                    }
                }
                else {
                    $asset_account = Account::findOrfail($merchantAccountId);
                    $currentAccountQuantity = $asset_account->asset_quantity;
                    $currentAccountPrice = $asset_account->asset_price;
                    
                    $newQuantity = $currentAccountQuantity + $assetQuantity;
                    $newPrice = $currentAccountPrice + $assetPrice;
                    $asset_account->asset_quantity = $newQuantity;
                    $asset_account->asset_price = $newPrice;
                    $asset_account->save();
                }
                $asset_fund->status = $statusId;
                $asset_fund->approval_staff_id = $this->staffId;
            } else {
                $asset_fund->status = $statusId;
                $asset_fund->approval_staff_id = $this->staffId;
            }
            $asset_fund->save();
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return  \Illuminate\Http\Response
     */
    public function create()
    {
        if($this->permissionDetails('Asset_fund','add')){
            $staffs = Staff::All();
            $staff_groups = Staff_group::All();
            $accounts = Account::select('identity_account.identity_name as account_name','account.account_id','merchant_account_list.asset_id')
                ->join('identity_account','identity_account.identity_id','=','account.identity_id')
                ->leftjoin('merchant_account_list','merchant_account_list.staff_account_id','=','account.account_id')
                ->where('account.account_id','!=',0)
                ->get();
            $timezones = Timezone::All();
            $assets = Asset::select('identity_asset.identity_name as asset_name','asset.asset_id','identity_asset.identity_code as asset_code')
                ->join('identity_asset','identity_asset.identity_id','=','asset.identity_id')
                ->where('asset.asset_id','!=',0)
                ->get();

            $merchants = Merchant::distinct()
                ->select('merchant.*','identity_merchant.identity_name as merchant_name')
                ->leftjoin('identity_merchant','identity_merchant.identity_id','=','merchant.identity_id')
                ->where('merchant.merchant_id','!=',0)
                ->get();
            $fundTypes = Fund_type::All();

            return view('asset_fund.create',compact('staffs','staff_groups','accounts','timezones','assets','merchants','fundTypes'));
        }else{
            return Redirect::back()->with('message', 'You are not authorized to use this functionality!');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param    \Illuminate\Http\Request  $request
     * @return  \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $asset_fund = new Asset_fund();
        $timeZoneId = PermissionTrait::getTimeZoneId();

        if($this->merchantId != 0) {
            $merchantID = $this->merchantId;
        } else {
            $merchantID = $request->merchant_id;
        }
        $asset_fund->merchant_id = $merchantID;
    
        $asset_fund->merchant_account_id = $request->merchant_account_id;
        $asset_fund->customer_account_id = $request->customer_account_id;
    
        $asset_fund->asset_id = $request->asset_id_hidden;
     
        $asset_fund->asset_price = $request->asset_price;
     
        $assetAction = $request->action;

        if($assetAction === 'withdrawal') {
            $asset_fund->asset_quantity = -($request->asset_quantity);
        }
        else {
            $asset_fund->asset_quantity = $request->asset_quantity;
        }

        $asset_fund->fund_title = $request->fund_title;

        $asset_fund->fund_description = $request->fund_description;

        $asset_fund->fund_type = $request->fund_type;

        $asset_fund->fund_timezone = $timeZoneId;

        /*if(isset($request->fund_date)) {
            $timestamp = json_decode($this->covertToUtcTz(strtotime($request->fund_date." ".$request->fund_time.":00")));
            $asset_fund->fund_date = $timestamp->date;
        } else {
            $asset_fund->fund_date = date('Ymd');
        }

        if(isset($request->fund_time)) {
            $timestamp = json_decode($this->covertToUtcTz(strtotime($request->fund_date." ".$request->fund_time.":00")));
            $asset_fund->fund_time = $timestamp->time;
        } else {
            $asset_fund->fund_time = time();
        }*/
        
        $asset_fund->fund_date = date('Ymd');
        $asset_fund->fund_time = time();
        
        $asset_fund->status = 1;
        $asset_fund->approval_staff_id = $this->staffId;

        $asset_fund->save();

        $assetFundId = $asset_fund->fund_id;

        $asset_fund_image = new Asset_fund_image();

        $asset_fund_image->fund_id = $assetFundId;
        $asset_fund_image->image_title = $request->image_title;
        $asset_fund_image->image_description = $request->image_description;

        $hase_merchant = Merchant::
                select('merchant.merchant_id','identity_merchant.identity_name as merchant_name')
            ->leftjoin('identity_merchant','identity_merchant.identity_id','=','merchant.identity_id') 
            ->where('merchant.merchant_id',$merchantID)
            ->get()->first();
        $merchantDirName = md5($hase_merchant->merchant_name);
        if($request->file('image_href')) {
            $publicDirPath = public_path(env('image_dir_path'));
            $imageDirPath = "merchant/$merchantDirName/";
            $absoluteImageDirPath = $publicDirPath.$imageDirPath;

            if(!file_exists($absoluteImageDirPath)){
                mkdir($absoluteImageDirPath,0777,true);
            }
            $imageName = $request->file('image_href')->getClientOriginalName();
            $imageArray = explode('.', $imageName);
            $hashImageName = md5($hase_merchant->merchant_name.$imageName).".".$imageArray[1];
            $request->file('image_href')->move($absoluteImageDirPath,$hashImageName);
            $asset_fund_image->image_href = "$imageDirPath$hashImageName";
        }
        $asset_fund_image->save();

        Session::flash('type', 'success');
        Session::flash('msg', 'Asset Fund Successfully Inserted');

        return redirect('asset_fund');
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
        $title = 'Show - asset_fund';

        if($request->ajax())
        {
            return URL::to('asset_fund/'.$id);
        }

        $asset_fund = Asset_fund::findOrfail($id);
        return view('asset_fund.show',compact('title','asset_fund'));
    }

    /**
     * Show the form for editing the specified resource.
     * @param    \Illuminate\Http\Request  $request
     * @param    int  $id
     * @return  \Illuminate\Http\Response
     */
    public function edit($id,Request $request)
    {
        if($this->permissionDetails('Asset_fund','manage')){
            $title = 'Edit - asset_fund';
                
            $asset_fund = Asset_fund::findOrfail($id);
            return view('asset_fund.edit',compact('title','asset_fund'));
        }else{
            return Redirect::back()->with('message', 'You are not authorized to use this functionality!');
        }
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
        $asset_fund = Asset_fund::findOrfail($id);
        $timeZoneId = PermissionTrait::getTimeZoneId();
        
        $asset_fund->merchant_id = $request->merchant_id;
        
        $asset_fund->staff_group_id = 0;
        
        $asset_fund->staff_id = $request->staff_id;
        
        $asset_fund->account_id = $request->account_id;
        
        $asset_fund->asset_id = $request->asset_id;
        
        $asset_fund->asset_price = $request->asset_price;
        
        $asset_fund->asset_quantity = $request->asset_quantity;

        $asset_fund->fund_type = $request->fund_type;
        
        $asset_fund->fund_timezone = $timeZoneId;
        
        /*$asset_fund->fund_date = $request->fund_date;
        
        $asset_fund->fund_time = $request->fund_time;*/
        
        $asset_fund->status = (!$request->status) ? 0 : $request->status;
        
        $asset_fund->save();

        Session::flash('type', 'success');
        Session::flash('msg', 'Asset Fund Successfully Updated');

        if ($request->submitBtn == "Save") {
           return redirect('asset_fund/'. $asset_fund->fund_id . '/edit');
        }else{
           return redirect('asset_fund');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param    int $id
     * @return  \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if($this->permissionDetails('Asset_fund','delete')){
            $asset_fund = Asset_fund::findOrfail($id);
            $asset_fund->delete();
            Session::flash('type', 'success'); 
            Session::flash('msg', 'Asset Fund Successfully Deleted');
            return redirect('asset_fund');
        }else{
            return Redirect::back()->with('message', 'You are not authorized to use this functionality!');
        }
    }

    public function updateFundComments(Request $request)
    {
        $comment_Date = date('Ymd');
        $comment_time = time();
        DB::table('asset_fund')->where('fund_id','=',$request->fundId)->update(array('approval_staff_id'=>$this->roleId));

        $comment_fund_id= DB::table('asset_fund')->where('fund_id','=',$request->fundId)->get()->first();

        if(!isset($comment_fund_id->comment_id)){
                    $comment_id = DB::table('approval_comment')->insertGetId(
                    ['comment'=>$request->approveComments,'comment_date'=>$comment_Date,'comment_time'=>$comment_time,'comment_parent_id'=>'','comment_source_id'=>$this->roleId]);
                    DB::table('asset_fund')->where('fund_id','=',$request->fundId)->update(array('comment_id'=>$comment_id));
                    DB::table('approval_comment')->where('comment_id','=',$comment_id)->update(array('comment_root_id'=>$comment_id));

        }else{
                $comment_id = DB::table('approval_comment')->insertGetId(
               ['comment'=>$request->approveComments,'comment_date'=>$comment_Date,'comment_time'=>$comment_time,'comment_parent_id'=>$comment_fund_id->comment_id,'comment_source_id'=>$this->roleId,'comment_root_id'=>$comment_fund_id->comment_id]);  
        }
    }
    public function fundCommentList(Request $request)
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
}
