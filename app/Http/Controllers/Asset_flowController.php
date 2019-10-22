<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\App;
use Illuminate\Http\Request;
use App\Http\Controllers\PermissionsController;
use App\Helpers\ConnectionManager;
use App\Asset_flow;
use App\Group_permission;
use App\Staff;
use App\Asset;
use App\Merchant;

use Amranidev\Ajaxis\Ajaxis;
use App\Http\Controllers\ProductApprovalController;
use App\Http\Traits\PermissionTrait;
use Session;
use DB;
use Redirect;
use URL;

/**
 * Class Asset_flowController.
 *
 */
class Asset_flowController extends PermissionsController
{
    use PermissionTrait;

    public function __construct()
    {
        parent::__construct();

        $connectionStatus = ConnectionManager::setDbConfig('Asset_flow');

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
        if($this->permissionDetails('Asset_flow','access')) {
            $where = array();
            $permissions = $this->getPermission("Asset_flow");
            
            if($this->merchantId == 0) {
                
            } else {
                if($this->roleId == 4) {
                    $where['asset_flow.merchant_id'] = $this->merchantId;
                } else {
                    $where['asset_flow.merchant_id'] = $this->merchantId;
                }
            }

            $asset_flows = Asset_flow::distinct()->select(
                'asset_flow.*',
                'identity_merchant.identity_name as merchant_name',
                'identity_staff.identity_name as staff_name',
                'identity_asset.identity_name as asset_name'
                )
                ->join('merchant','merchant.merchant_id','asset_flow.merchant_id')
                ->join('identity_merchant','identity_merchant.identity_id','merchant.identity_id')
                
                ->join('staff','staff.staff_id','asset_flow.staff_id')
                ->join('identity_staff','identity_staff.identity_id','staff.identity_id')

                ->join('asset','asset.asset_id','asset_flow.asset_id')
                ->join('identity_asset','identity_asset.identity_id','asset.identity_id')
                ->where(function($q) use ($where) {
                    foreach($where as $key => $value) {
                        $q->where($key, '=', $value);
                    }
                })->get();

            return view('asset_flow.index',compact('asset_flows','permissions'));
        }
        else {
            return Redirect::back()->with('message', 'You are not authorized to use this functionality!');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return  \Illuminate\Http\Response
     */
    public function create()
    {
        if($this->permissionDetails('Asset_flow','add')) {

            /*$staffs = Staff::All();
            $staff_groups = Group_permission::All();*/

            $assets = Asset::select('identity_asset.identity_name as asset_name','asset.asset_id')
                ->join('identity_asset','identity_asset.identity_id','=','asset.identity_id')
                ->where('identity_asset.identity_id','!=',0)
                ->get();

            $merchants = Merchant::distinct()
                ->select('merchant.*','merchant_identity.identity_name as merchant_name')
                ->leftjoin('identity_merchant as merchant_identity','merchant_identity.identity_id','=','merchant.identity_id')
                ->where('merchant.merchant_id','>',0)
                ->get();
            
            return view('asset_flow.create',compact('assets','merchants'));
        }
        else {
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
        $asset_flow = new Asset_flow();

        $asset_flow->merchant_id = ($this->merchantId != 0) ? $this->merchantId : $request->merchant_id;

        $asset_flow->staff_id = $request->staff_id;

        $asset_flow->asset_id = $request->asset_id;

        $asset_flow->asset_price_lower = $request->asset_price_lower;

        $asset_flow->asset_price_upper = $request->asset_price_upper;

        $asset_flow->asset_quantity_lower = $request->asset_quantity_lower;

        $asset_flow->asset_quantity_upper = $request->asset_quantity_upper;

        $asset_flow->asset_total_lower = $request->asset_total_lower;

        $asset_flow->asset_total_upper = $request->asset_total_upper;

        $asset_flow->save();

        Session::flash('type', 'success');
        Session::flash('msg', 'Asset Flow Successfully Inserted');

        if ($request->submitBtn === "Save") {
           return redirect('asset_flow/'. $asset_flow->flow_id . '/edit');
        } else {
           return redirect('asset_flow');
        }
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
        $title = 'Show - asset_flow';

        if($request->ajax())
        {
            return URL::to('asset_flow/'.$id);
        }

        $asset_flow = Asset_flow::findOrfail($id);
        return view('asset_flow.show',compact('title','asset_flow'));
    }

    /**
     * Show the form for editing the specified resource.
     * @param    \Illuminate\Http\Request  $request
     * @param    int  $id
     * @return  \Illuminate\Http\Response
     */
    public function edit($id,Request $request)
    {
        if($this->permissionDetails('Asset_flow','manage')) {
            $asset_flow = Asset_flow::findOrfail($id);
            /*$staffs = Staff::All();
            $staff_groups = Group_permission::All();*/
            $assets = Asset::select('identity_asset.identity_name as asset_name','asset.asset_id')
                ->join('identity_asset','identity_asset.identity_id','=','asset.identity_id')
                ->where('identity_asset.identity_id','!=',0)
                ->get();
            $merchants = Merchant::distinct()
                ->select('merchant.*','merchant_identity.identity_name as merchant_name')
                ->leftjoin('identity_merchant as merchant_identity','merchant_identity.identity_id','=','merchant.identity_id')
                ->where('merchant.merchant_id','!=',0)
                ->get();
            
            return view('asset_flow.edit',compact('asset_flow','assets','merchants'));
        } else {
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
        $asset_flow = Asset_flow::findOrfail($id);
    	
        $asset_flow->merchant_id = ($this->merchantId != 0) ? $this->merchantId : $request->merchant_id;
        
        $asset_flow->staff_id = $request->staff_id;
        
        $asset_flow->asset_id = $request->asset_id;
        
        $asset_flow->asset_price_lower = $request->asset_price_lower;
        
        $asset_flow->asset_price_upper = $request->asset_price_upper;
        
        $asset_flow->asset_quantity_lower = $request->asset_quantity_lower;
        
        $asset_flow->asset_quantity_upper = $request->asset_quantity_upper;
        
        $asset_flow->asset_total_lower = $request->asset_total_lower;
        
        $asset_flow->asset_total_upper = $request->asset_total_upper;
        
        $asset_flow->save();

        Session::flash('type', 'success');
        Session::flash('msg', 'Asset Flow Successfully Updated');

        if ($request->submitBtn === "Save") {
           return redirect('asset_flow/'. $asset_flow->flow_id . '/edit');
        } else {
           return redirect('asset_flow');
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
        if($this->permissionDetails('Asset_flow','delete')) {
         	$asset_flow = Asset_flow::findOrfail($id);
         	$asset_flow->delete();
            Session::flash('type', 'success');
            Session::flash('msg', 'Asset Flow Successfully Deleted');
            return redirect('asset_flow');
        } else {
            return Redirect::back()->with('message', 'You are not authorized to use this functionality!');
        }
    }
}
