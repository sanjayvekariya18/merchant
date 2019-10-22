<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\App;
use Illuminate\Http\Request;
use App\Http\Controllers\PermissionsController;
use App\Helpers\ConnectionManager;
use App\Trade_limits;
use App\Group_permission;
use App\Customer;
use App\Staff;
use App\Account;
use App\Asset;
use App\Timezone;
use App\Merchant;
use App\Country;

use Amranidev\Ajaxis\Ajaxis;
use App\Http\Traits\PermissionTrait;

use URL;
use Session;
use DB;
use Redirect;

/**
 * Class Trade_limitsController.
 *
 */
class Trade_limitsController extends PermissionsController
{
    use PermissionTrait;

    public function __construct()
    {
        parent::__construct();

        $connectionStatus = ConnectionManager::setDbConfig('Trade_limits');

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
         if($this->permissionDetails('Trade_limits','access')){
            $where = array();
            $permissions = $this->getPermission("Trade_limits");
            
            if($this->merchantId == 0){
                //$where['merchant_type.merchant_root_id'] = $merchantType;
            }else{
                if($this->roleId == 4){
                    $where['trade_limits.merchant_id'] = $this->merchantId;
                }else{
                    $where['trade_limits.merchant_id'] = $this->merchantId;
                    $where['trade_limits.location_city_id'] = $this->locationId; 
                }
            }

            $trade_limits = Trade_limits::distinct()
                ->select(
                    'trade_limits.*',
                    'identity_merchant.identity_name as merchant_name',
                    DB::raw('(CASE WHEN (postal.postal_premise != "" and postal.postal_subpremise != "") THEN CONCAT(postal.postal_premise,", ",SUBSTR(postal.postal_subpremise,1,10))
                              WHEN (postal.postal_premise != "") THEN postal.postal_premise                              
                              WHEN (postal.postal_subpremise !="") THEN SUBSTR(postal.postal_subpremise,1,10)
                              ELSE CONCAT(postal.postal_street_number," ",postal.postal_route)   
                        END) as location_name'),
                    'group_permissions.group_name',
                    'identity_staff.identity_name as staff_name',
                    'account_staff_identity.identity_name as staff_account_code_long',
                    'account_staff_identity.identity_code as staff_account_code_short',
                    'identity_customer.identity_name as customer_name',
                    'identity_customer.identity_code as customer_code',
                    'account_customer_identity.identity_name as customer_account_code_long',
                    'account_customer_identity.identity_code as customer_account_code_short',
                    'timezone.timezone_name as timezone_name',
                    'identity_asset.identity_name as asset_name',
                    'identity_asset.identity_code as asset_code'
                    )

                    ->leftjoin('merchant','merchant.merchant_id','trade_limits.merchant_id')
                    ->leftjoin('identity_merchant','identity_merchant.identity_id','merchant.identity_id')

                    ->leftjoin('location_list','location_list.list_id','trade_limits.location_id')
                    ->leftjoin('location_city','location_city.city_id','location_list.location_city_id')

                    ->leftjoin('postal','postal.postal_id','location_list.postal_id')
                    ->leftjoin('identity_postal','identity_postal.identity_id','postal.identity_id')

                    ->leftjoin('group_permissions','group_permissions.group_id','trade_limits.group_id')

                    ->leftjoin('staff','staff.staff_id','trade_limits.staff_id')
                    ->leftjoin('identity_staff','identity_staff.identity_id','staff.identity_id')

                    ->leftjoin('account as account_merchant','account_merchant.account_id','trade_limits.merchant_account_id')
                    ->leftjoin('identity_account as account_staff_identity','account_staff_identity.identity_id','account_merchant.identity_id')

                    ->leftjoin('customers','customers.customer_id','trade_limits.customer_id')
                    ->leftjoin('identity_customer','identity_customer.identity_id','customers.identity_id')

                    ->leftjoin('account as account_customer','account_customer.account_id','trade_limits.customer_account_id')
                    ->leftjoin('identity_account as account_customer_identity','account_customer_identity.identity_id','account_customer.identity_id')

                    ->leftjoin('timezone','timezone.timezone_id','trade_limits.timezone')

                    ->leftjoin('asset','asset.asset_id','trade_limits.asset_id')
                    ->leftjoin('identity_asset','identity_asset.identity_id','asset.identity_id')

                    ->where(function($q) use ($where){
                        foreach($where as $key => $value){
                            $q->where($key, '=', $value);
                        }
                    })->get();
            return view('trade_limits.index',compact('trade_limits','permissions'));
        } else {
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
        if($this->permissionDetails('Trade_limits','add')){

            $timezones = Timezone::All();
            $location_countries = Country::All();
            
            $assets = Asset::select('identity_asset.identity_name as asset_name','identity_asset.identity_code as asset_code','asset.asset_id')
                ->join('identity_asset','identity_asset.identity_id','=','asset.identity_id')
                ->get();
            
            $merchants = Merchant::distinct()
                ->select('merchant.*','identity_merchant.identity_name as merchant_name')
                ->join('merchant_account_list','merchant_account_list.merchant_id','=','merchant.merchant_id')
                ->join('identity_merchant','identity_merchant.identity_id','=','merchant.identity_id')
                ->get(); 

            return view('trade_limits.create',compact('timezones','assets','merchants','location_countries'));
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
        $trade_limits = new Trade_limits();
        $trade_limits->merchant_id = ($this->merchantId != 0) ? $this->merchantId : $request->merchant_id;
        $trade_limits->location_id = $request->location_id;
        $trade_limits->group_id = $request->group_id;
        $trade_limits->staff_id = $request->staff_id;
        $trade_limits->merchant_account_id = $request->merchant_account_id;
        $trade_limits->customer_id = $request->customer_id;
        $trade_limits->customer_account_id = $request->customer_account_id;
        $trade_limits->asset_id = $request->asset_id;
        $trade_limits->timezone = PermissionTrait::getTimezoneId();
        $trade_limits->day_start = $request->day_start;
        $trade_limits->day_end = $request->day_end;

        if(isset($request->time_start)) {
            $entryTimeData = explode(":", $request->time_start);
            $entryTime = $entryTimeData[0]*3600+$entryTimeData[1]*60;
            $trade_limits->time_start = $entryTime;
        } else {
            $trade_limits->time_start = 0;
        }

        if(isset($request->time_end)) {
            $entryTimeData = explode(":", $request->time_end);
            $entryTime = $entryTimeData[0]*3600+$entryTimeData[1]*60;
            $trade_limits->time_end = $entryTime;
        } else {
            $trade_limits->time_end = 0;
        }

        $trade_limits->price_pct_offset = $request->price_pct_offset;
        $trade_limits->quantity_maximum = $request->quantity_maximum;
        $trade_limits->priority = $request->priority;
        $trade_limits->status = isset($request->status)?1:0;
        $trade_limits->save();

        Session::flash('type', 'success'); 
        Session::flash('msg', 'Trade Limit Successfully Inserted');

        if ($request->submitBtn === "Save") {
           return redirect('trade_limits/'. $trade_limits->limits_id . '/edit');
        }else{
           return redirect('trade_limits');
        }
    }

    /**
     * Show the form for editing the specified resource.
     * @param    \Illuminate\Http\Request  $request
     * @param    int  $id
     * @return  \Illuminate\Http\Response
     */
    public function edit($id,Request $request)
    {
        if($this->permissionDetails('Trade_limits','manage')){
            
            $trade_limits = Trade_limits::
                select('trade_limits.*','location_list.location_city_id as city_id')
                ->join('location_list','location_list.list_id','trade_limits.location_id')
                ->where('limits_id',$id)
                ->get()->first();

            $assets = Asset::
                select(
                    'identity_asset.identity_name as asset_name',
                    'identity_asset.identity_code as asset_code',
                    'asset.asset_id'
                )
                ->join('identity_asset','identity_asset.identity_id','=','asset.identity_id')
                ->get();
            
            $merchants = Merchant::distinct()
                ->select('merchant.*','identity_merchant.identity_name as merchant_name')
                ->join('merchant_account_list','merchant_account_list.merchant_id','=','merchant.merchant_id')
                ->join('identity_merchant','identity_merchant.identity_id','=','merchant.identity_id')
                ->get(); 

            /*echo "<pre>";
            print_r($trade_limits->toArray());
            print_r($assets->toArray());
            print_r($merchants->toArray());
            die();*/

            return view('trade_limits.edit',compact('timezones','assets','merchants','location_countries','trade_limits'));

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
        $trade_limits = Trade_limits::findOrfail($id);
        $trade_limits->merchant_id = ($this->merchantId != 0) ? $this->merchantId : $request->merchant_id;
        $trade_limits->location_id = $request->location_id;
        $trade_limits->group_id = $request->group_id;
        $trade_limits->staff_id = $request->staff_id;
        $trade_limits->merchant_account_id = $request->merchant_account_id;
        $trade_limits->customer_id = $request->customer_id;
        $trade_limits->customer_account_id = $request->customer_account_id;
        $trade_limits->asset_id = $request->asset_id;
        $trade_limits->timezone = PermissionTrait::getTimezoneId();
        $trade_limits->day_start = $request->day_start;
        $trade_limits->day_end = $request->day_end;

        if(isset($request->time_start)) {
            $entryTimeData = explode(":", $request->time_start);
            $entryTime = $entryTimeData[0]*3600+$entryTimeData[1]*60;
            $trade_limits->time_start = $entryTime;
        } else {
            $trade_limits->time_start = 0;
        }

        if(isset($request->time_end)) {
            $entryTimeData = explode(":", $request->time_end);
            $entryTime = $entryTimeData[0]*3600+$entryTimeData[1]*60;
            $trade_limits->time_end = $entryTime;
        } else {
            $trade_limits->time_end = 0;
        }

        $trade_limits->price_pct_offset = $request->price_pct_offset;
        $trade_limits->quantity_maximum = $request->quantity_maximum;
        $trade_limits->priority = $request->priority;
        $trade_limits->status = isset($request->status)?1:0;
        $trade_limits->save();

        Session::flash('type', 'success'); 
        Session::flash('msg', 'Trade Limit Successfully Updated');

        if ($request->submitBtn === "Save") {
           return redirect('trade_limits/'. $trade_limits->limits_id . '/edit');
        }else{
           return redirect('trade_limits');
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
     	if($this->permissionDetails('Trade_limits','delete')){
            $trade_limits = Trade_limits::findOrfail($id);
            $trade_limits->delete();
            Session::flash('type', 'success'); 
            Session::flash('msg', 'Trade Limits Successfully Deleted');
            return redirect('trade_limits');
        }else{
            return Redirect::back()->with('message', 'You are not authorized to use this functionality!');
        }
    }
}
