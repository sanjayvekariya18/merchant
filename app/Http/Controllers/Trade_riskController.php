<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\App;
use Illuminate\Http\Request;
use App\Http\Controllers\PermissionsController;
use App\Helpers\ConnectionManager;
use App\Trade_risk;
use App\Exchange;
use App\TradeStatusType;
use App\Group_permission;
use App\Customer;
use App\Staff;
use App\Account;
use App\Asset;
use App\Timezone;
use App\Merchant;
use App\City;

use Amranidev\Ajaxis\Ajaxis;
use App\Http\Traits\PermissionTrait;

use URL;
use Session;
use DB;
use Redirect;

/**
 * Class Trade_riskController.
 *
 * @author  The scaffold-interface created at 2018-02-18 06:26:29pm
 * @link  https://github.com/amranidev/scaffold-interface
 */
class Trade_riskController extends PermissionsController
{
    use PermissionTrait;

    public function __construct()
    {
        parent::__construct();

        $connectionStatus = ConnectionManager::setDbConfig('Trade_risk');

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
         if($this->permissionDetails('Trade_risk','access')){
            $where = array();
            $permissions = $this->getPermission("Trade_risk");
            
            if($this->merchantId == 0){
                //$where['merchant_type.merchant_root_id'] = $merchantType;
            }else{
                if($this->roleId == 4){
                    $where['trade_breach.merchant_id'] = $this->merchantId;
                }else{
                    $where['trade_breach.merchant_id'] = $this->merchantId;
                    $where['trade_breach.location_city_id'] = $this->locationId; 
                }
            }

            $trade_breachs = Trade_risk::
                    distinct()
                    ->select(
                        'trade_breach.*',
                        
                        'identity_merchant.identity_name as merchant_name',
                        'location_city.city_name',
                        
                        'group_permissions.group_name',
                        
                        'identity_staff.identity_name as staff_name',
                        'account_staff_identity.identity_name as staff_account_code_long',
                        'account_staff_identity.identity_code as staff_account_code_short',

                        
                        'identity_customer.identity_name as customer_name',
                        'identity_customer.identity_code as customer_code',
                        'account_customer_identity.identity_name as customer_account_code_long',
                        'account_customer_identity.identity_code as customer_account_code_short',

                        'identity_exchange.identity_name as exchange_name',
                        
                        'entry_timezone.timezone_name as entry_timezone_name',

                        'settlement_trade_status_type.trade_status_name as settlement_limit_status_name',
                        'trading_trade_status_type.trade_status_name as trading_limit_status_name',
                        
                        'identity_asset.identity_name as asset_name',
                        'identity_asset.identity_code as asset_code'
                        )

                        ->leftjoin('merchant','merchant.merchant_id','trade_breach.merchant_id')
                        ->leftjoin('identity_merchant','identity_merchant.identity_id','merchant.identity_id')

                        ->leftjoin('location_list','location_list.list_id','trade_breach.location_id')
                        ->leftjoin('location_city','location_city.city_id','location_list.location_city_id')

                        ->leftjoin('staff','staff.staff_id','trade_breach.staff_id')
                        ->leftjoin('identity_staff','identity_staff.identity_id','staff.identity_id')

                        ->leftjoin('account as account_staff','account_staff.account_id','trade_breach.merchant_account_id')
                        ->leftjoin('identity_account as account_staff_identity','account_staff_identity.identity_id','account_staff.identity_id')


                        ->leftjoin('group_permissions','group_permissions.group_id','trade_breach.group_id')

                        ->leftjoin('customers','customers.customer_id','trade_breach.customer_id')
                        ->leftjoin('identity_customer','identity_customer.identity_id','customers.identity_id')

                        ->leftjoin('account as account_customer','account_customer.account_id','trade_breach.customer_account_id')
                        ->leftjoin('identity_account as account_customer_identity','account_customer_identity.identity_id','account_customer.identity_id')

                        ->leftjoin('timezone as entry_timezone','entry_timezone.timezone_id','trade_breach.entry_timezone')

                        ->join('exchange','exchange.exchange_id','=','trade_breach.exchange_id')
                        ->join('identity_exchange','exchange.identity_id','=','identity_exchange.identity_id')
                            
                        ->leftjoin('asset','asset.asset_id','trade_breach.asset_id')
                        ->leftjoin('identity_asset','identity_asset.identity_id','asset.identity_id')

                        ->leftjoin('trade_status_type as settlement_trade_status_type','settlement_trade_status_type.trade_status_id','trade_breach.settlement_limit_status')

                        ->leftjoin('trade_status_type as trading_trade_status_type','trading_trade_status_type.trade_status_id','trade_breach.trading_limit_status')

                        ->where(function($q) use ($where){
                            foreach($where as $key => $value){
                                $q->where($key, '=', $value);
                            }
                        })->get();

            foreach ($trade_breachs as $key => $trade_breach) {
                $datetime = json_decode(PermissionTrait::covertToLocalTz($trade_breach->entry_time));
                $trade_breachs[$key]->entry_date = $datetime->date;
                $trade_breachs[$key]->entry_time = $datetime->time;
            }

            return view('trade_breach.index',compact('trade_breachs','permissions'));
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
        if($this->permissionDetails('Trade_risk','add')){

            
            // $staff_groups = Group_permission::All();
            
            // $accounts = Account::All();
            
            // $timezones = Timezone::All();                   
            
            // $location_cities = City::All();
            
            $trade_status_types = TradeStatusType::All();

            $assets = Asset::select('identity_asset.identity_name as asset_name','asset.asset_id')
                                ->join('identity_asset','identity_asset.identity_id','=','asset.identity_id')
                                ->get();

            /*$staffs = Staff::
                            select('staff.*','identity_staff.identity_name as staff_name')
                            ->join('identity_staff','identity_staff.identity_id','=','staff.identity_id')
                            ->where('staff.staff_id','>',0)
                            ->get();*/ 

            $merchants = Merchant::
                                distinct()
                                ->select('merchant.*','identity_merchant.identity_name as merchant_name')
                                ->join('identity_merchant','identity_merchant.identity_id','=','merchant.identity_id')
                                ->where('merchant.merchant_id','!=',0)
                                ->get(); 

            /*$customers = Customer::
                                distinct()
                                ->select('customers.customer_id','identity_customer.identity_name as customer_name')
                                ->join('identity_customer','identity_customer.identity_id','=','customers.identity_id')
                                ->get();*/ 

            $exchanges = Exchange::
                        select('identity_exchange.identity_code as exchange_code','identity_exchange.identity_name as exchange_name','identity_exchange.identity_website as exchange_website','exchange.*')
                        ->join('identity_exchange','identity_exchange.identity_id','=','exchange.identity_id')
                        ->get();                      


            return view('trade_breach.create',compact('staffs','assets','merchants','trade_status_types','exchanges'));
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
        /*echo "<pre>";
        print_r($request->toArray());
        die;*/

        $trade_breach = new Trade_risk();
        
        $trade_breach->merchant_id = ($this->merchantId != 0) ? $this->merchantId : $request->merchant_id;
        $trade_breach->location_id = $request->location_id;
        $trade_breach->group_id = $this->roleId;
        $trade_breach->staff_id = $this->staffId;
        $trade_breach->customer_id = $request->customer_id;

        $trade_breach->merchant_account_id = $request->merchant_account_id;
        $trade_breach->customer_account_id = $request->customer_account_id;

        $trade_breach->exchange_id = $request->exchange_id;
        $trade_breach->asset_id = $request->asset_id;
        
        $trade_breach->entry_timezone = PermissionTrait::getTimezoneId();
        $trade_breach->entry_time = time();
        $trade_breach->entry_date = date('Ymd');

        $trade_breach->price_average = $request->price_average;
        $trade_breach->trade_exposure = $request->trade_exposure;
        $trade_breach->settlement_limit = $request->settlement_limit;
        $trade_breach->settlement_limit_status = $request->settlement_limit_status;
        $trade_breach->trading_limit_status = $request->trading_limit_status;
        $trade_breach->trading_limit = $request->trade_limit;
        
        $trade_breach->save();

        Session::flash('type', 'success'); 
        Session::flash('msg', 'Trade Risk Successfully Inserted');

        if ($request->submitBtn === "Save") {
           return redirect('trade_breach/'. $trade_breach->breach_id . '/edit');
        }else{
           return redirect('trade_breach');
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
               
        if($this->permissionDetails('Trade_risk','manage')){
            
            $trade_breach = Trade_risk::
                            select('trade_breach.*','location_list.location_city_id')
                            ->join('location_list','location_list.list_id','trade_breach.location_id')
                            ->where('breach_id',$id)
                            ->get()->first();
            
            /*$staff_groups = Group_permission::All();
            $accounts = Account::All();
            $timezones = Timezone::All();                   
            $location_cities = City::All();*/
            $trade_status_types = TradeStatusType::All();

            $assets = Asset::select('identity_asset.identity_name as asset_name','asset.asset_id')
                                ->join('identity_asset','identity_asset.identity_id','=','asset.identity_id')
                                ->get();
            
            /*$staffs = Staff::
                            select('staff.*','identity_staff.identity_name as staff_name')
                            ->join('identity_staff','identity_staff.identity_id','=','staff.identity_id')
                            ->where('staff.staff_id','>',0)
                            ->get();*/ 

            $merchants = Merchant::
                                distinct()
                                ->select('merchant.*','identity_merchant.identity_name as merchant_name')
                                ->leftjoin('identity_merchant','identity_merchant.identity_id','=','merchant.identity_id')
                                ->where('merchant.merchant_id','!=',0)
                                ->get(); 

            /*$customers = Customer::
                                distinct()
                                ->select('customers.customer_id','identity_customer.identity_name as customer_name')
                                ->leftjoin('identity_customer','identity_customer.identity_id','=','customers.identity_id')
                                ->get();*/ 

            $exchanges = Exchange::
                        select('identity_exchange.identity_code as exchange_code','identity_exchange.identity_name as exchange_name','identity_exchange.identity_website as exchange_website','exchange.*')
                        ->join('identity_exchange','identity_exchange.identity_id','=','exchange.identity_id')
                        ->get();   


            return view('trade_breach.edit',compact('assets','merchants','trade_breach','trade_status_types','exchanges'));

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
        /*echo "<pre>";
        print_r($request->toArray());
        die;*/

        $trade_breach = Trade_risk::findOrfail($id);
    	
        $trade_breach->merchant_id = ($this->merchantId != 0) ? $this->merchantId : $request->merchant_id;
        
        $trade_breach->location_id = $request->location_id;
        $trade_breach->group_id = $this->roleId;
        $trade_breach->staff_id = $this->staffId;
        $trade_breach->customer_id = $request->customer_id;

        $trade_breach->merchant_account_id = $request->merchant_account_id;
        $trade_breach->customer_account_id = $request->customer_account_id;

        $trade_breach->exchange_id = $request->exchange_id;
        $trade_breach->asset_id = $request->asset_id;

        $trade_breach->price_average = $request->price_average;
        $trade_breach->trade_exposure = $request->trade_exposure;
        $trade_breach->settlement_limit = $request->settlement_limit;
        $trade_breach->settlement_limit_status = $request->settlement_limit_status;
        $trade_breach->trading_limit_status = $request->trading_limit_status;
        $trade_breach->trading_limit = $request->trade_limit;
        
        $trade_breach->save();

        Session::flash('type', 'success'); 
        Session::flash('msg', 'Trade Risk Successfully Updated');

        if ($request->submitBtn === "Save") {
           return redirect('trade_breach/'. $trade_breach->breach_id . '/edit');
        }else{
           return redirect('trade_breach');
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
     	if($this->permissionDetails('Trade_risk','delete')){
            $trade_breach = Trade_risk::findOrfail($id);
            $trade_breach->delete();
            Session::flash('type', 'success'); 
            Session::flash('msg', 'Trade Risk Successfully Deleted');
            return redirect('trade_breach');
        }else{
            return Redirect::back()->with('message', 'You are not authorized to use this functionality!');
        }
    }
}
