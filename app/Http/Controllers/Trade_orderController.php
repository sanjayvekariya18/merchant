<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\App;
use Illuminate\Http\Request;
use App\Http\Controllers\PermissionsController;
use App\Helpers\ConnectionManager;
use App\Trade_order;
use App\Group_permission;
use App\Customer;
use App\Exchange;
use App\Trade_order_type;
use App\Staff;
use App\Account;
use App\Trade_order_side_type;
use App\Asset;
use App\Timezone;
use App\Merchant;
use App\City;
use App\County;
use App\State;
use App\Countries;
use App\Status_operations_type;
use App\Status_fiat_type;
use App\Status_crypto_type;

use Amranidev\Ajaxis\Ajaxis;
use App\Http\Traits\PermissionTrait;

use URL;
use Session;
use DB;
use Redirect;

/**
 * Class Trade_orderController.
 *
 * @author  The scaffold-interface created at 2018-02-18 06:26:29pm
 * @link  https://github.com/amranidev/scaffold-interface
 */
class Trade_orderController extends PermissionsController
{
    use PermissionTrait;

    public function __construct()
    {
        parent::__construct();

        $connectionStatus = ConnectionManager::setDbConfig('Trade_order');

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
         if($this->permissionDetails('Trade_order','access')){
            $where = array();
            $permissions = $this->getPermission("Trade_order");
            
            if($this->merchantId == 0){
                //$where['merchant_type.merchant_root_id'] = $merchantType;
            }else{
                if($this->roleId == 4){
                    $where['trade_orders.merchant_id'] = $this->merchantId;
                }else{
                    $where['trade_orders.merchant_id'] = $this->merchantId;
                    $where['trade_orders.location_city_id'] = $this->locationId; 
                }
            }

            $trade_orders = Trade_order::
                    distinct()
                    ->select(
                        'trade_orders.*',
                        
                        'identity_merchant.identity_name as merchant_name',
                        'location_city.city_name',
                        
                        'staff_groups.staff_group_name',
                        
                        'staffs.staff_name',
                        'account_staff_identity.identity_name as staff_account_code_long',
                        'account_staff_identity.identity_code as staff_account_code_short',

                        
                        'identity_customer.identity_name as customer_name',
                        'identity_customer.identity_code as customer_code',
                        'account_customer_identity.identity_name as customer_account_code_long',
                        'account_customer_identity.identity_code as customer_account_code_short',

                        'account_fee_referrer_identity.identity_name as fee_referrer_account_code_long',
                        'account_fee_referrer_identity.identity_code as fee_referrer_account_code_short',

                        'identity_exchange.identity_name as exchange_name',
                        
                        'start_timezone.timezone_name as start_timezone_name',
                        'expire_timezone.timezone_name as expire_timezone_name',

                        'trade_order_side_type.side_type_name',
                        'asset_from_identity.identity_name as asset_from_name',
                        'asset_from_identity.identity_code as asset_from_code',
                        'asset_into_identity.identity_name as asset_into_name',
                        'asset_into_identity.identity_code as asset_into_code',

                        'trade_order_type.type_name as order_type_name',

                        'status_operations_type.type_code',
                        'status_operations_type.type_name',

                        'status_fiat_type.status_fiat_type_code',
                        'status_fiat_type.status_fiat_type_name',

                        'status_crypto_type.status_crypto_type_code',
                        'status_crypto_type.status_crypto_type_name',

                        'fee_asset_identity.identity_name as fee_asset_name',
                        'fee_asset_identity.identity_code as fee_asset_code'
                        )

                        ->leftjoin('merchant','merchant.merchant_id','trade_orders.merchant_id')
                        ->leftjoin('identity_merchant','identity_merchant.identity_id','merchant.identity_id')

                        ->leftjoin('location_city','location_city.city_id','trade_orders.location_city_id')

                        ->leftjoin('staffs','staffs.staff_id','trade_orders.staff_id')

                        ->leftjoin('account as account_staff','account_staff.account_id','trade_orders.staff_account_id')
                        ->leftjoin('identity_account as account_staff_identity','account_staff_identity.identity_id','account_staff.identity_id')


                        ->leftjoin('staff_groups','staff_groups.staff_group_id','trade_orders.group_id')

                        ->leftjoin('customers','customers.customer_id','trade_orders.customer_id')
                        ->leftjoin('identity_customer','identity_customer.identity_id','customers.identity_id')

                        ->leftjoin('account as account_customer','account_customer.account_id','trade_orders.customer_account_id')
                        ->leftjoin('identity_account as account_customer_identity','account_customer_identity.identity_id','account_customer.identity_id')

                        ->leftjoin('account as account_fee_referrer','account_fee_referrer.account_id','trade_orders.fee_referrer')
                        ->leftjoin('identity_account as account_fee_referrer_identity','account_fee_referrer_identity.identity_id','account_fee_referrer.identity_id')

                        ->leftjoin('exchange','exchange.exchange_id','trade_orders.exchange_id')
                        ->leftjoin('identity_exchange','exchange.identity_id','identity_exchange.identity_id')

                        ->leftjoin('timezone as start_timezone','start_timezone.timezone_id','trade_orders.start_timezone')
                        ->leftjoin('timezone as expire_timezone','expire_timezone.timezone_id','trade_orders.expire_timezone')


                        ->leftjoin('trade_order_side_type','trade_order_side_type.side_type_id','trade_orders.side_type_id')

                        ->leftjoin('asset as asset_from','asset_from.asset_id','trade_orders.asset_from_id')
                        ->leftjoin('identity_asset as asset_from_identity','asset_from_identity.identity_id','asset_from.identity_id')

                        ->leftjoin('asset as asset_into','asset_into.asset_id','trade_orders.asset_into_id')
                        ->leftjoin('identity_asset as asset_into_identity','asset_into_identity.identity_id','asset_into.identity_id')
                                
                        ->leftjoin('trade_order_type','trade_order_type.type_id','trade_orders.order_type_id')

                        ->leftjoin('status_operations_type','status_operations_type.type_id','trade_orders.status_operation')

                        ->leftjoin('status_fiat_type','status_fiat_type.status_fiat_type_id','trade_orders.status_fiat')

                        ->leftjoin('status_crypto_type','status_crypto_type.status_crypto_type_id','trade_orders.status_crypto')


                        ->leftjoin('asset as fee_asset','fee_asset.asset_id','trade_orders.fee_asset')
                        ->leftjoin('identity_asset as fee_asset_identity','fee_asset_identity.identity_id','fee_asset.identity_id')

                        ->leftjoin('merchant_type_list','merchant_type_list.merchant_id','merchant.merchant_id')
                        ->leftjoin('merchant_type','merchant_type.merchant_type_id','merchant_type_list.merchant_type_id')

                        ->where(function($q) use ($where){
                            foreach($where as $key => $value){
                                $q->where($key, '=', $value);
                            }
                        })->get();

            return view('trade_order.index',compact('trade_orders','permissions'));
        }else{
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
        if($this->permissionDetails('Trade_order','add')){

            $staffs = Staff::All();
            $staff_groups = Group_permission::All();
            $accounts = Account::All();
            $trade_order_side_types = Trade_order_side_type::All();
            $timezones = Timezone::All();                   
            $status_operations = Status_operations_type::All();
            $status_fiats = Status_fiat_type::All();
            $status_cryptos = Status_crypto_type::All();
            $trade_order_types = Trade_order_type::All();
            $location_countries = Countries::All();

            $assets = Asset::select('identity_asset.identity_name as asset_name','asset.asset_id')
                                ->join('identity_asset','identity_asset.identity_id','=','asset.identity_id')
                                ->get();
            $merchants = Merchant::
                                distinct()
                                ->select('merchant.*','merchant_identity.identity_name as merchant_name','merchant_identity.identity_logo as merchant_logo','merchant_identity.identity_logo_compact as merchant_logo_compact')
                                ->leftjoin('identity as merchant_identity','merchant_identity.identity_id','=','merchant.identity_id')
                                ->where('merchant.merchant_id','!=',0)
                                ->get(); 

            $customers = Customer::
                                distinct()
                                ->select('customers.customer_id','customer_identity.identity_name as customer_name')
                                ->leftjoin('identity as customer_identity','customer_identity.identity_id','=','customers.identity_id')
                                ->get();   

            $exchanges = Exchange::
                            select('identity_exchange.identity_code as exchange_code','identity_exchange.identity_name as exchange_name','identity_exchange.identity_website as exchange_website','exchange.*')
                            ->join('identity_exchange','identity_exchange.identity_id','=','exchange.identity_id')
                            ->get();
            

            return view('trade_order.create',compact('staffs','staff_groups','accounts','trade_order_side_types','timezones','status_operations','status_fiats','status_cryptos','trade_order_types','assets','merchants','customers','exchanges','location_countries'));
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
        $trade_order = new Trade_order();
        
        $trade_order->merchant_id = $request->merchant_id;
        $trade_order->location_city_id = $request->location_city_id;
        $trade_order->group_id = $request->group_id;
        $trade_order->staff_id = $request->staff_id;
        $trade_order->staff_account_id = $request->staff_account_id;
        $trade_order->customer_id = $request->customer_id;
        $trade_order->customer_account_id = $request->customer_account_id;
        $trade_order->exchange_id = $request->exchange_id;
        $trade_order->transaction_internal_ref = $request->transaction_internal;
        $trade_order->side_type_id = $request->side_type_id;
        $trade_order->asset_from_id = $request->asset_from_id;
        $trade_order->asset_from_price = $request->asset_from_price;
        $trade_order->asset_from_quantity = $request->asset_from_quantity;
        $trade_order->asset_into_id = $request->asset_into_id;
        $trade_order->asset_into_price = $request->asset_into_price;
        $trade_order->asset_into_quantity = $request->asset_into_quantity;
        $trade_order->order_type_id = $request->order_type_id;
        $trade_order->leverage = $request->leverage;
        $trade_order->start_timezone = $request->start_timezone;

        if(isset($request->start_time)) {
            $startTimeData = explode(":", $request->start_time);
            $startTime = $startTimeData[0]*3600+$startTimeData[1]*60;
            $trade_order->start_time = $startTime;
        } else {
            $trade_order->start_time = 0;
        }

        if(isset($request->start_date)) {
            $startDate = str_replace('-', '', $request->start_date);
            $trade_order->start_date = $startDate;
        } else {
             $trade_order->start_date = 0;
        }

        $trade_order->expire_timezone = $request->expire_timezone;

        if(isset($request->expire_time)) {
            $expireTimeData = explode(":", $request->expire_time);
            $expireTime = $expireTimeData[0]*3600+$expireTimeData[1]*60;
            $trade_order->expire_time = $expireTime;
        } else {
            $trade_order->expire_time = 0;
        }

        if(isset($request->expire_date)) {
            $expireDate = str_replace('-', '', $request->expire_date);
            $trade_order->expire_date = $expireDate;
        } else {
             $trade_order->expire_date = 0;
        }

        $trade_order->fee_amount = $request->fee_amount;
        $trade_order->fee_asset = $request->fee_asset;
        $trade_order->fee_referrer = $request->fee_referrer;
        $trade_order->status_operation = $request->status_operation;
        $trade_order->status_fiat = $request->status_fiat;
        $trade_order->status_crypto = $request->status_crypto;
        
        $trade_order->save();

        Session::flash('type', 'success'); 
        Session::flash('msg', 'Trade Order Successfully Inserted');

        if ($request->submitBtn === "Save") {
           return redirect('trade_order/'. $trade_order->order_id . '/edit');
        }else{
           return redirect('trade_order');
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
               
        if($this->permissionDetails('Status_operations_type','manage')){
            
            $trade_order = Trade_order::select('trade_orders.*','location_city.*')
                                ->leftjoin('location_city','location_city.city_id','trade_orders.location_city_id')
                                ->where('order_id',$id)
                                ->get()->first();

            $staffs = Staff::All();
            $staff_groups = Group_permission::All();
            $accounts = Account::All();
            $trade_order_side_types = Trade_order_side_type::All();
            $timezones = Timezone::All();                   
            $status_operations = Status_operations_type::All();
            $status_fiats = Status_fiat_type::All();
            $status_cryptos = Status_crypto_type::All();
            $trade_order_types = Trade_order_type::All();
            $location_countries = Countries::All();

            $assets = Asset::select('identity_asset.identity_name as asset_name','asset.asset_id')
                                ->join('identity_asset','identity_asset.identity_id','=','asset.identity_id')
                                ->get();
            $merchants = Merchant::
                                distinct()
                                ->select('merchant.*','merchant_identity.identity_name as merchant_name','merchant_identity.identity_logo as merchant_logo','merchant_identity.identity_logo_compact as merchant_logo_compact')
                                ->leftjoin('identity as merchant_identity','merchant_identity.identity_id','=','merchant.identity_id')
                                ->where('merchant.merchant_id','!=',0)
                                ->get(); 

            $customers = Customer::
                                distinct()
                                ->select('customers.customer_id','customer_identity.identity_name as customer_name')
                                ->leftjoin('identity as customer_identity','customer_identity.identity_id','=','customers.identity_id')
                                ->get();   

            $exchanges = Exchange::
                            select('identity_exchange.identity_code as exchange_code','identity_exchange.identity_name as exchange_name','identity_exchange.identity_website as exchange_website','exchange.*')
                            ->join('identity_exchange','identity_exchange.identity_id','=','exchange.identity_id')
                            ->get();
            

            return view('trade_order.edit',compact('staffs','staff_groups','accounts','trade_order_side_types','timezones','status_operations','status_fiats','status_cryptos','trade_order_types','assets','merchants','customers','exchanges','location_countries','trade_order'));

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
        $trade_order = Trade_order::findOrfail($id);
    	
        $trade_order->merchant_id = $request->merchant_id;
        $trade_order->location_city_id = $request->location_city_id;
        $trade_order->group_id = $request->group_id;
        $trade_order->staff_id = $request->staff_id;
        $trade_order->staff_account_id = $request->staff_account_id;
        $trade_order->customer_id = $request->customer_id;
        $trade_order->customer_account_id = $request->customer_account_id;
        $trade_order->exchange_id = $request->exchange_id;
        $trade_order->transaction_internal_ref = $request->transaction_internal;
        $trade_order->side_type_id = $request->side_type_id;
        $trade_order->asset_from_id = $request->asset_from_id;
        $trade_order->asset_from_price = $request->asset_from_price;
        $trade_order->asset_from_quantity = $request->asset_from_quantity;
        $trade_order->asset_into_id = $request->asset_into_id;
        $trade_order->asset_into_price = $request->asset_into_price;
        $trade_order->asset_into_quantity = $request->asset_into_quantity;
        $trade_order->order_type_id = $request->order_type_id;
        $trade_order->leverage = $request->leverage;
        $trade_order->start_timezone = $request->start_timezone;

        if(isset($request->start_time)) {
            $startTimeData = explode(":", $request->start_time);
            $startTime = $startTimeData[0]*3600+$startTimeData[1]*60;
            $trade_order->start_time = $startTime;
        } else {
            $trade_order->start_time = 0;
        }

        if(isset($request->start_date)) {
            $startDate = str_replace('-', '', $request->start_date);
            $trade_order->start_date = $startDate;
        } else {
             $trade_order->start_date = 0;
        }

        $trade_order->expire_timezone = $request->expire_timezone;

        if(isset($request->expire_time)) {
            $expireTimeData = explode(":", $request->expire_time);
            $expireTime = $expireTimeData[0]*3600+$expireTimeData[1]*60;
            $trade_order->expire_time = $expireTime;
        } else {
            $trade_order->expire_time = 0;
        }

        if(isset($request->expire_date)) {
            $expireDate = str_replace('-', '', $request->expire_date);
            $trade_order->expire_date = $expireDate;
        } else {
             $trade_order->expire_date = 0;
        }
        
        $trade_order->fee_amount = $request->fee_amount;
        $trade_order->fee_asset = $request->fee_asset;
        $trade_order->fee_referrer = $request->fee_referrer;
        $trade_order->status_operation = $request->status_operation;
        $trade_order->status_fiat = $request->status_fiat;
        $trade_order->status_crypto = $request->status_crypto;
        
        $trade_order->save();

        if ($request->submitBtn === "Save") {
           return redirect('trade_order/'. $trade_order->order_id . '/edit');
        }else{
           return redirect('trade_order');
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
     	if($this->permissionDetails('trade_order','delete')){
            $trade_order = Trade_order::findOrfail($id);
            $trade_order->delete();
            Session::flash('type', 'success'); 
            Session::flash('msg', 'Trade Order Successfully Deleted');
            return redirect('trade_order');
        }else{
            return Redirect::back()->with('message', 'You are not authorized to use this functionality!');
        }
    }

    public function getCity(Request $request) {
        if($request->county_id) {
            $allCities = City::where('county_id','=',$request->county_id)
                ->orderBy('city_name', 'ASC')->get();
        }
        echo json_encode($allCities);
    }

    public function getCounty(Request $request) {
        if($request->state_id) {
            $allCounties = County::join('location_city', 'location_county.county_id', '=', 'location_city.county_id')
                ->where('location_city.state_id','=',$request->state_id)
                ->select('location_county.*')
                ->orderBy('location_county.county_name', 'ASC')->groupBy('county_name')->get();
        }
        echo json_encode($allCounties);
    }

    public function getState(Request $request) {
        $allStates = State::join('location_city', 'location_state.state_id', '=', 'location_city.state_id')
            ->where('location_city.country_id','=',$request->country_id)
            ->select('location_state.*')
            ->orderBy('location_state.state_name', 'ASC')->groupBy('state_name')->get();
        echo json_encode($allStates);
    }
}
