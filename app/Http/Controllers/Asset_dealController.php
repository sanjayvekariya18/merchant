<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\App;
use Illuminate\Http\Request;
use App\Http\Controllers\PermissionsController;
use App\Helpers\ConnectionManager;
use App\Asset_deal;
use App\Staff;
use App\Account;
use App\Trade_order_side_type;
use App\Asset;
use App\Timezone;
use App\Merchant;
use App\Status_operations_type;
use App\Status_fiat_type;
use App\Status_crypto_type;
use Amranidev\Ajaxis\Ajaxis;
use App\Http\Controllers\ProductApprovalController;
use App\Http\Traits\PermissionTrait;

use URL;
use Session;
use DB;
use Redirect;

/**
 * Class Asset_dealController.
 *
 * @author  The scaffold-interface created at 2018-02-15 07:11:38pm
 * @link  https://github.com/amranidev/scaffold-interface
 */
class Asset_dealController extends PermissionsController
{
    use PermissionTrait;

    public function __construct()
    {
        parent::__construct();

        $connectionStatus = ConnectionManager::setDbConfig('Asset_deal');
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

        if($this->permissionDetails('Asset_deal','access')){
                       
            $permissions = $this->getPermission("Asset_deal");
            
            if($this->merchantId == 0){
                $asset_deals = Asset_deal::
                            distinct()
                            ->select('staff_name','account_code_long','side_type_name','identity_asset_quote.identity_name as asset_quote_name','identity_asset_base.identity_name as asset_base_name','timezone.timezone_name','identity_merchant.identity_name as merchant_name','type_name','status_fiat_type_name','status_crypto_type_name','asset_deal.*')
                            ->join('staffs','asset_deal.trader_id','=','staffs.staff_id')
                            ->join('account','asset_deal.account_id','=','account.account_id')
                            ->join('trade_order_side_type','trade_order_side_type.side_type_id','=','asset_deal.side_type_id')
                            ->join('timezone','asset_deal.entry_timezone','=','timezone.timezone_id')
                            ->join('asset as asset_base','asset_deal.asset_base_id','=','asset_base.asset_id')
                            ->join('asset as asset_quote','asset_deal.asset_quote_id','=','asset_quote.asset_id')
                            ->join('merchant','asset_deal.counterparty_id','=','merchant.merchant_id')
                            ->join('status_operations_type','status_operations_type.type_id','=','asset_deal.status_operations_type_id')
                            ->join('status_fiat_type','status_fiat_type.status_fiat_type_id','=','asset_deal.status_fiat_type_id')
                            ->join('status_crypto_type','status_crypto_type.status_crypto_type_id','=','asset_deal.status_crypto_type_id')
                            ->join('identity_asset as identity_asset_base','asset_base.identity_id','=','identity_asset_base.identity_id')
                            ->join('identity_asset as identity_asset_quote','asset_quote.identity_id','=','identity_asset_quote.identity_id')
                            ->join('identity_merchant','identity_merchant.identity_id','=','merchant.identity_id')
                            ->get();


            return view('asset_deal.index',compact('asset_deals','permissions'));
            }
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
        if($this->permissionDetails('Asset_deal','add')){
            $staffs = Staff::All();
            $accounts = Account::All();
            $side_types = Trade_order_side_type::All();
            $timezones = Timezone::All();                   
            $status_operations = Status_operations_type::All();
            $status_fiats = Status_fiat_type::All();
            $status_cryptos = Status_crypto_type::All();
            
            $assets = Asset::select('identity_asset.identity_name as asset_name','asset.asset_id')
                                ->join('identity_asset','identity_asset.identity_id','=','asset.identity_id')
                                ->get();
            $merchants = Merchant::
                                        distinct()
                                        ->select('merchant.*','merchant_identity.identity_name as merchant_name','merchant_identity.identity_logo as merchant_logo','merchant_identity.identity_logo_compact as merchant_logo_compact')
                                        ->leftjoin('identity as merchant_identity','merchant_identity.identity_id','=','merchant.identity_id')
                                        ->where('merchant.merchant_id','!=',0)
                                        ->get();                    

            return view('asset_deal.create',compact('staffs','accounts','side_types','assets','timezones','merchants','status_operations','status_fiats','status_cryptos'));

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
        $asset_deal = new Asset_deal();
        
        $asset_deal->trader_id = $request->trader_id;
        $asset_deal->account_id = $request->account_id;
        $asset_deal->transaction_id = $request->transaction_id;
        $asset_deal->quantity = $request->quantity;
        $asset_deal->side_type_id = $request->side_type_id;
        $asset_deal->asset_quote_id = $request->asset_quote_id;
        $asset_deal->entry_timezone = $request->entry_timezone;

        if(isset($request->entry_time)) {
            $reserveTimeData = explode(":", $request->entry_time);
            $reserveTime = $reserveTimeData[0]*3600+$reserveTimeData[1]*60;
            $asset_deal->entry_time = $reserveTime;
        } else {
            $asset_deal->entry_time = 0;
        }

        if(isset($request->entry_date)) {
            $reserveDate = str_replace('-', '', $request->entry_date);
            $asset_deal->entry_date = $reserveDate;
        } else {
             $asset_deal->entry_date = 0;
        }

        
        $asset_deal->price_index = $request->price_index;
        $asset_deal->price_quote = $request->price_quote;
        $asset_deal->price_fee = $request->price_fee;
        $asset_deal->price_fee_rate = $request->price_fee_rate;
        $asset_deal->counterparty_id = $request->counterparty_id;
        $asset_deal->asset_base_id = $request->asset_base_id;
        $asset_deal->asset_base_quote = $request->asset_base_quote;
        $asset_deal->asset_base_rate = $request->asset_base_rate;
        $asset_deal->account_uuid = $request->account_uuid;
        $asset_deal->type_id = $request->type_id;
        $asset_deal->status_fiat_type_id = $request->status_fiat_type_id;
        $asset_deal->status_crypto_type_id = $request->status_crypto_type_id;
        
        $asset_deal->save();

        Session::flash('type', 'success'); 
        Session::flash('msg', 'Asset Deal Successfully Created');
        if ($request->submitBtn === "Save") {
           return redirect('asset_deal/'. $asset_deal->deal_id . '/edit');
        }else{
           return redirect('asset_deal');
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
        if($this->permissionDetails('Asset_deal','manage')){

            $asset_deal = Asset_deal::findOrfail($id);
            $staffs = Staff::All();
            $accounts = Account::All();
            $side_types = Trade_order_side_type::All();
            $timezones = Timezone::All();                   
            $status_operations = Status_operations_type::All();
            $status_fiats = Status_fiat_type::All();
            $status_cryptos = Status_crypto_type::All();
            $assets = Asset::select('identity_asset.identity_name as asset_name','asset.asset_id')
                                ->join('identity_asset','identity_asset.identity_id','=','asset.identity_id')
                                ->get();
            $merchants = Merchant::
                                        distinct()
                                        ->select('merchant.*','merchant_identity.identity_name as merchant_name','merchant_identity.identity_logo as merchant_logo','merchant_identity.identity_logo_compact as merchant_logo_compact')
                                        ->leftjoin('identity as merchant_identity','merchant_identity.identity_id','=','merchant.identity_id')
                                        ->where('merchant.merchant_id','!=',0)
                                        ->get();                    

            return view('asset_deal.edit',compact('asset_deal','staffs','accounts','side_types','assets','timezones','merchants','status_operations','status_fiats','status_cryptos'));

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
        $asset_deal = Asset_deal::findOrfail($id);
    	
        
        $asset_deal->trader_id = $request->trader_id;
        $asset_deal->account_id = $request->account_id;
        $asset_deal->transaction_id = $request->transaction_id;
        $asset_deal->quantity = $request->quantity;
        $asset_deal->side_type_id = $request->side_type_id;
        $asset_deal->asset_quote_id = $request->asset_quote_id;
        $asset_deal->entry_timezone = $request->entry_timezone;
        if(isset($request->entry_time)) {
            $reserveTimeData = explode(":", $request->entry_time);
            $reserveTime = $reserveTimeData[0]*3600+$reserveTimeData[1]*60;
            $asset_deal->entry_time = $reserveTime;
        } else {
            $asset_deal->entry_time = 0;
        }

        if(isset($request->entry_date)) {
            $reserveDate = str_replace('-', '', $request->entry_date);
            $asset_deal->entry_date = $reserveDate;
        } else {
             $asset_deal->entry_date = 0;
        }
        $asset_deal->price_index = $request->price_index;
        $asset_deal->price_quote = $request->price_quote;
        $asset_deal->price_fee = $request->price_fee;
        $asset_deal->price_fee_rate = $request->price_fee_rate;
        $asset_deal->counterparty_id = $request->counterparty_id;
        $asset_deal->asset_base_id = $request->asset_base_id;
        $asset_deal->asset_base_quote = $request->asset_base_quote;
        $asset_deal->asset_base_rate = $request->asset_base_rate;
        $asset_deal->account_uuid = $request->account_uuid;
        $asset_deal->type_id = $request->type_id;
        $asset_deal->status_fiat_type_id = $request->status_fiat_type_id;
        $asset_deal->status_crypto_type_id = $request->status_crypto_type_id;
        
        $asset_deal->save();

        Session::flash('type', 'success'); 
        Session::flash('msg', 'Asset Deal Successfully Updated');
        if ($request->submitBtn === "Save") {
           return redirect('asset_deal/'. $asset_deal->deal_id . '/edit');
        }else{
           return redirect('asset_deal');
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
        if($this->permissionDetails('Asset_deal','delete')){
            $asset_deal = Asset_deal::findOrfail($id);
            $asset_deal->delete();
            Session::flash('type', 'success'); 
            Session::flash('msg', 'Asset Deal Successfully Deleted');
            return redirect('asset_deal');
        }else{
            return Redirect::back()->with('message', 'You are not authorized to use this functionality!');
        }
    }
}
