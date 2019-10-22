<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\App;
use Illuminate\Http\Request;
use App\Http\Controllers\PermissionsController;
use App\Helpers\ConnectionManager;
use App\Http\Traits\PermissionTrait;
use App\Customer;
use App\Account;
use App\Merchant;
use App\Identity_merchant;
use App\Identity_asset;
use App\Identity_customer;
use App\Exchange;
use App\TradeOrder;
use App\Transaction_summary;
use App\Transactions_ledger;
use App\Transactions_code;
use App\Trade_basket;
use App\Timezone;
use App\Trade_limits;
use App\Identity_table_type;
use App\Asset_move;
use URL;
use Session;
use DB;
use Redirect;
use Auth;
use DateTime;
use Config;
use Carbon\Carbon;
use DateTimeZone;

class Hase_otc_order_entry_listController extends PermissionsController
{
    use PermissionTrait;

    public function __construct()
    {
        parent::__construct();

        $connectionStatus = ConnectionManager::setDbConfig('Hase_otc_order_entry');

        if ($connectionStatus['type'] === "error") {
            Session::flash('type', $connectionStatus['type']);
            Session::flash('msg', $connectionStatus['message']);
            return Redirect::back()->send();
        }
    }

    public function otcOrderEntryList(){
        $merchantId = $this->merchantId;           
        return view('hase_otc_order_entry.otc_order_entry_list',compact('merchantId'));
    }
    public function assetNameList(Request $request){
        $assetListDetails=DB::table('identity_asset')->where('identity_id','=',$request->identity_id)->first();
        $assetListType=DB::table('asset')->where('identity_id','=',$assetListDetails->identity_id)->first();
        $side_type_name=DB::table('asset_type')->where('asset_type_id','=',$assetListType->asset_type_id)->first();
        echo $assetListDetails->identity_code."_".$side_type_name->asset_type_code; 

    }
    public function customerAccountList(Request $request){
        $customer_account=array();
        $customer_account_list=Account::distinct()
        ->select('account.account_code_long','account.account_id','account.asset_quantity','account.fee_percentage')
        ->join('customer_account_list as cal ','cal.account_id','=','account.account_id')
        ->join('customers as cust ','cust.customer_id','=','cal.customer_id')
        ->join('identity_customer as ic ','ic.identity_id','=','cust.identity_id')
        ->where('cal.customer_id','=',$request->customer_id)
        ->where('cal.asset_id','=',$request->asset_id)
        ->get();
        return json_encode($customer_account_list);
    }
    public function merchantAccountList(Request $request){
        $broker_account_list=array();
        $broker_account_list=Account::distinct()
            ->select('account.account_code_long','account.account_id','account.account_settlement','account.asset_quantity','account.fee_percentage')
            ->join('merchant_account_list as mal ','mal.staff_account_id','=','account.account_id')
            ->join('merchant as merch ','merch.merchant_id','=','mal.merchant_id')
            ->join('identity_merchant as im ','im.identity_id','=','merch.identity_id')
            ->where('mal.asset_id','=',$request->asset_id)
            ->where('mal.merchant_id','=',$request->merchant_id)
            ->get();
        return json_encode($broker_account_list);
    }
    public function otcEntryDetailsUpdate(Request $request){
        $timeZoneId = PermissionTrait::getTimeZoneId();
        $tradeStartDate = str_replace("-","",date('Y-m-d'));
        $tradeStartTime = time();
        $tradeEndDate = str_replace("-","",date('Y-m-d'));
        $tradeEndTime = time();
        $order_type_id = 1;
        $transactionString = '23456789ABCDEFGHJKMNPQRSTUVYXZ';
        $transactionLength=20;
        $transactionCharactersLength = strlen($transactionString);
        $transactionRoot = '';
        for ($i = 0; $i < $transactionLength; $i++) {
            $transactionRoot .= $transactionString[rand(0, $transactionCharactersLength - 1)];
        }
        
        $transactions_code = new Transactions_code();
        $transactions_code->code_random = $transactionRoot;
        $transactions_code->save();

        $tradeSideTypeList=DB::table('asset_type')
        ->join('asset as ass','ass.asset_type_id','=','asset_type.asset_type_id')
        ->where('ass.asset_id','=',$request->assetListFrom[0])
        ->select('asset_type.asset_type_code')
        ->get()->first();       
        if($request->orderBasketConfirm == 0)
        {
            $basketCode = $request->customerAccountId.'-'.date('Ymd').'-'.PermissionTrait::random_num(4);
            
            $tradeBasketObject = new Trade_basket();
            $tradeBasketObject->merchant_id = $request->brokerName;
            $tradeBasketObject->staff_id = $this->staffId;
            $tradeBasketObject->customer_id = $request->customerName;
            $tradeBasketObject->basket_code = $basketCode;
            $tradeBasketObject->basket_timezone = $timeZoneId;
            $tradeBasketObject->basket_date = date('Ymd');
            $tradeBasketObject->basket_time = time();
            $tradeBasketObject->save();
            $basketID = $tradeBasketObject->basket_id;
        } else {
            $basketID = $request->orderBasketConfirm;
        }


        if($tradeSideTypeList->asset_type_code === 'crypto')
            $sideTypeId = 1;
        else
            $sideTypeId = 2;
        if($request->order_id)
        {   
            $tradeOrderObject = TradeOrder::findOrfail($request->order_id);
            $assetFromId = $request->assetListFrom[0];
            $assetIntoId = $request->assetListFrom[1];
            
        } else {
            $tradeOrderObject = new TradeOrder();
            $assetFromId = $request->assetListFrom[1];
            $assetIntoId = $request->assetListFrom[0];
        }
        $tradeOrderObject->asset_from_id = $assetFromId;
        $tradeOrderObject->asset_into_id = $assetIntoId;

        $tradeOrderObject->basket_id = $basketID;
        $tradeOrderObject->merchant_id = $request->brokerName;
        $tradeOrderObject->group_id = $this->roleId;

        $tradeOrderObject->staff_id = $this->staffId;
        $tradeOrderObject->merchant_account_id = $request->brokerAccountId;
        $tradeOrderObject->customer_account_id = $request->customerAccountId;
        
        $tradeOrderObject->strategy_type = 0;        
        $tradeOrderObject->strategy_id = 0;

        $tradeOrderObject->exchange_id = $request->exchangeList;
        $tradeOrderObject->transaction_internal_ref = $transactionRoot;
        $tradeOrderObject->side_type_id = $sideTypeId;
        
        $tradeOrderObject->asset_from_price = (!empty($request->priceTrader))?$request->priceTrader : 0;
        $tradeOrderObject->asset_from_quantity = $request->quantityTrader;
        $tradeOrderObject->asset_into_price = (!empty($request->price))?$request->price : 0;
        $tradeOrderObject->asset_into_quantity = $request->quantity;
        $tradeOrderObject->asset_settlement_id = (!empty($request->settlementAssetId))?$request->settlementAssetId : 1;


        $tradeOrderObject->order_type_id = $order_type_id;
        $tradeOrderObject->leverage = 1;
        $tradeOrderObject->start_timezone = $timeZoneId;

        $tradeOrderObject->start_date = $tradeStartDate;
        $tradeOrderObject->start_time = $tradeStartTime;

        $tradeOrderObject->expire_timezone = $timeZoneId;

        $tradeOrderObject->expire_date = $tradeEndDate;
        $tradeOrderObject->expire_time = $tradeEndTime;


        $tradeOrderObject->fee_amount = 99;
        $tradeOrderObject->fee_asset = 1;
        $tradeOrderObject->fee_referrer = 0;
        $tradeOrderObject->status_operation = 2;
        $tradeOrderObject->status_fiat = 1;
        $tradeOrderObject->status_crypto = 1;
        $tradeOrderObject->status_time = 0;
        $tradeOrderObject->save();
        
        $transaction_summary = Transaction_summary::firstOrNew(array('order_id' => $tradeOrderObject->order_id));
        $transaction_summary = new Transaction_summary();
        $transaction_summary->basket_id = $basketID;
        $transaction_summary->order_id = $tradeOrderObject->order_id;
        $transaction_summary->merchant_id = $request->brokerName;
        $transaction_summary->group_id = $this->roleId;
        $transaction_summary->staff_id = $this->staffId;
        $transaction_summary->merchant_account_id = $request->brokerAccountId;
        $transaction_summary->customer_account_id = $request->customerAccountId;
        $transaction_summary->exchange_id = $request->exchangeList;
        $transaction_summary->trade_timezone = $timeZoneId;
        $transaction_summary->trade_date = $tradeStartDate;
        $transaction_summary->trade_time = $tradeStartTime;
        $transaction_summary->side_type_id = $sideTypeId;
        $transaction_summary->asset_from_id = $assetFromId;
        $transaction_summary->asset_from_quote = $request->totalTraderTemplates;
        $transaction_summary->asset_from_price = (!empty($request->priceTrader))?$request->priceTrader : 0;
        $transaction_summary->asset_from_quantity = $request->quantityTrader;
        $transaction_summary->asset_into_id = $assetIntoId;
        $transaction_summary->asset_into_quote = $request->totalTemplates;
        $transaction_summary->asset_into_price = (!empty($request->price))?$request->price : 0;
        $transaction_summary->asset_into_quantity = $request->quantity;

        $transaction_summary->strategy_type = 0;
        $transaction_summary->order_type_id = $order_type_id;
        $transaction_summary->status_type_id = 1;
        $transaction_summary->reason_type_id = 1;
        $transaction_summary->fee_amount = 99;
        $transaction_summary->fee_asset_id = 1;
        $transaction_summary->fee_referrer_id = 0;
        $transaction_summary->transaction_address = 'None';
        $transaction_summary->transaction_address_url = 'None';

        $transaction_summary->transaction_type_id = 1;
        $transaction_summary->transaction_internal_ref = $transactionRoot;
        $transaction_summary->transaction_root = $transactionRoot;
        $transaction_summary->save();

        $transactions_ledger = new Transactions_ledger();
        $transactions_ledger->summary_id = $transaction_summary->summary_id;
        $transactions_ledger->basket_id = $basketID;
        $transactions_ledger->order_id = $tradeOrderObject->order_id;
        $transactions_ledger->group_id = $this->roleId;
        $transactions_ledger->staff_id = $this->staffId;
        $transactions_ledger->merchant_account_id = $request->brokerAccountId;
        $transactions_ledger->customer_account_id = $request->customerAccountId;
        $transactions_ledger->exchange_id = $request->exchangeList;
        $transactions_ledger->trade_timezone = $timeZoneId;
        $transactions_ledger->trade_date = $tradeStartDate;
        $transactions_ledger->trade_time = $tradeStartTime;
        $transactions_ledger->side_type_id = $sideTypeId;
        $transactions_ledger->asset_from_id = $assetFromId;
        $transactions_ledger->asset_from_quote = $request->totalTraderTemplates;
        $transactions_ledger->asset_from_price = (!empty($request->priceTrader))?$request->priceTrader : 0;
        $transactions_ledger->asset_from_quantity = $request->quantityTrader;
        $transactions_ledger->asset_into_id = $assetIntoId;
        $transactions_ledger->asset_into_quote = $request->totalTemplates;
        $transactions_ledger->asset_into_price = (!empty($request->price))?$request->price : 0;
        $transactions_ledger->asset_into_quantity = $request->quantity;

        $transactions_ledger->order_type_id = $order_type_id;
        $transactions_ledger->strategy_type = 0;
        $transactions_ledger->status_type_id = 1;
        $transactions_ledger->reason_type_id = 1;
        $transactions_ledger->fee_amount = 99;
        $transactions_ledger->fee_asset_id = 1;
        $transactions_ledger->fee_referrer_id = 0;
        $transactions_ledger->transaction_address = 'None';
        $transactions_ledger->transaction_address_url = 'None';

        $transactions_ledger->transaction_type_id = 1;
        $transactions_ledger->transaction_exchange_id = 1;
        $transactions_ledger->transaction_exchange_ref = $transactionRoot;
        $transactions_ledger->transaction_internal_ref = $transactionRoot;
        $transactions_ledger->transaction_root = $transactionRoot;
        $transactions_ledger->ledger_hash = 0;
        $transactions_ledger->save();

        /* asset move insert logic for condition */
        /*
        $statusFound = 0;
        $transactions_ledger->asset_from_id = $tradeOrderObject->asset_from_id;
        $transactions_ledger->merchant_id = $tradeOrderObject->merchant_id;
        $trade_limits = Trade_limits::select('trade_limits.quantity_maximum')
                ->where('merchant_id', $request->brokerName)
                ->where('staff_id', $this->staffId)
                ->where('staff_account_id', $request->brokerAccountId)
                ->where('customer_account_id', $request->customerAccountId)
                ->where('asset_id', $assetFromId)
                ->get()->first();
        if($trade_limits && $request->quantityTrader >= $trade_limits->quantity_maximum) {
            $sourceTable = Identity_table_type::select('type_id')
                ->where('table_code', 'trade_orders')
                ->get()->first();
            $asset_move = new Asset_move();
            $asset_move->source_table = $sourceTable->type_id;
            $asset_move->source_id = $tradeOrderObject->order_id;
            $asset_move->merchant_id = $request->brokerName;
            $asset_move->staff_group_id = $this->roleId;
            $asset_move->staff_id = $this->staffId;
            $asset_move->account_from_id = $request->brokerAccountId;
            $asset_move->account_into_id = $request->customerAccountId;
            $asset_move->asset_id = $assetFromId;
            $asset_move->asset_price = ($request->priceTrader != "")?$request->priceTrader : 0;
            $asset_move->asset_quantity = $request->quantityTrader;
            $asset_move->move_timezone = $timeZoneId;
            $asset_move->move_date = $tradeStartDate;
            $asset_move->move_time = $tradeStartTime;
            $asset_move->status = 1;
            $asset_move->approval_staff_id = $this->staffId;
            $asset_move->save();
        }*/
        return redirect('hase_otc_order_entry');
    }

    
    public function tradeBasketDetails(Request $request){
        $todayDate = str_replace("-","",date('Y-m-d'));
        $previousTime = time() - 86400;
        $tradeBasketDetails=array();
        $tradeBasketDetails=Trade_basket::distinct()
            ->select('trade_basket.basket_id','trade_basket.basket_code')
            ->where('trade_basket.merchant_id','=',$request->merchant_id)
            ->where('trade_basket.customer_id','=',$request->customer_id)
            ->where('trade_basket.basket_time','>',$previousTime)
            ->orwhere('trade_basket.basket_id','=',0)
            ->get();
        return json_encode($tradeBasketDetails);
    }

    public function insertBasketDetails(Request $request){
        $timeZoneId = PermissionTrait::getTimeZoneId();
        $tradeBasketObject = new Trade_basket();
        $tradeBasketObject->merchant_id = $request->merchant_id;
        $tradeBasketObject->staff_id = $this->staffId;
        $tradeBasketObject->customer_id = $request->customer_id;
        $tradeBasketObject->basket_code = $basketCode;
        $tradeBasketObject->basket_timezone = $timeZoneId;
        $tradeBasketObject->basket_date = date('Ymd');
        $tradeBasketObject->basket_time = time();
        $tradeBasketObject->save();
        return $tradeBasketObject->basket_id;
    }
    
    public function fxRatesList(Request $request){
       $fx_rates_details=array();
       $fx_rates_list=DB::table('identity_asset')
        ->join('asset as ass','ass.identity_id','=','identity_asset.identity_id')
        ->join('exchange_asset_pairs as eap','eap.asset_from_id','=','ass.asset_id')
        ->where('eap.exchange_id','=',$request->exchange_id)
        ->orderBy('eap.priority','desc')
        ->distinct()
        ->select('identity_asset.identity_code','ass.asset_id')
        ->get();
        return json_encode($fx_rates_list);
    }
    public function customerIdList(Request $request) {
        $customer_name=$request->customer_name;
        if(is_numeric($customer_name)){
            
             $customer_list=DB::table('customers')->where('identity_id','=',$customer_name)->first();
         }else{

             $customer_details_list=DB::table('identity')->where('identity_name','=',$customer_name)->first();
            $customer_list=DB::table('customers')->where('identity_id','=',$customer_details_list->identity_id)->first();
         }
        $customers_list=DB::table('customer_account_list')->where('customer_id','=',$customer_list->customer_id)->first(); 
        echo $customers_list->account_id; 
    }
    public function accountIdList(Request $request) {
       $account_code_short=$request->account_code_short;
        $customer_account_list=DB::table('account')->where('account_code_short','=',$account_code_short)->first();
        $customer_list=DB::table('customer_account_list')->where('account_id','=',$customer_account_list->account_id)->first();
        echo $customer_list->account_id; 
    }
    public function customerList(Request $request){
       $customer_list=Identity_customer::distinct()
       ->select('identity_customer.identity_name','cust.customer_id')
       ->join('customers as cust ','cust.identity_id','=','identity_customer.identity_id')
       ->join('merchant_customer_list as mcl ','mcl.customer_id','=','cust.customer_id')
       ->where('mcl.merchant_id','=',$request->merchant_id)
       ->get();
        return json_encode($customer_list);
    }
    public function tradeSideTypeList(Request $request){
        $tradeSideTypeList=array();
        $tradeSideTypeList=DB::table('asset_type')
            ->join('asset as ass','ass.asset_type_id','=','asset_type.asset_type_id')
            ->where('ass.asset_id','=',$request->asset_id)
            ->select('asset_type.asset_type_code')
            ->get()->first();
        return json_encode($tradeSideTypeList);
    }

    /*nirmal customer function */
    public function brokerNameDetails(){

        if($this->merchantId == 0){
                
        }else{
            if($this->roleId == 4){
                $where['merchant.merchant_id'] = $this->merchantId;
            }else{
                $where['merchant.merchant_id'] = $this->merchantId;
            }
        }
        if($this->merchantId == 0 )
        {
            $broker_lists = Merchant::
                distinct('merchant.merchant_id')
                ->select('merchant.merchant_id','im.identity_name')
                ->leftjoin('identity_merchant as im','im.identity_id','=','merchant.identity_id')
                ->join('merchant_account_list as mal','mal.merchant_id','=','merchant.merchant_id')
                ->where('merchant.merchant_id','!=',0)
                ->where('mal.staff_account_id','!=',0)
                ->get();
        } else {
            if($this->roleId == 4)
            {
                $broker_lists=Identity_merchant::distinct()
                ->select('identity_merchant.identity_name','merchant.merchant_id')
                ->join('merchant','merchant.identity_id','=','identity_merchant.identity_id')
                ->join('merchant_account_list as mal','mal.merchant_id','=','merchant.merchant_id')
                ->where('merchant.merchant_id','=',$this->merchantId)
                ->where('mal.staff_account_id','!=',0)
                ->where(function($q) use ($where){
                    foreach($where as $key => $value){
                        $q->where($key, '=', $value);
                    }
                })
                ->get();
            } 
            else {
                /* this is for list of staff and manager */
                $broker_lists=Identity_merchant::distinct()
                ->select('identity_merchant.identity_name','merchant.merchant_id')
                ->join('merchant','merchant.identity_id','=','identity_merchant.identity_id')
                ->join('merchant_account_list as mal','mal.merchant_id','=','merchant.merchant_id')
                ->where('merchant.merchant_id','=',$this->merchantId)
                ->where('mal.staff_account_id','!=',0)
                ->where(function($q) use ($where){
                    foreach($where as $key => $value){
                        $q->where($key, '=', $value);
                    }
                })
                ->get();
            }
        }
        return json_encode($broker_lists);





    }
    public function assetIntoValues(Request $request){
        $broker_list=Identity_Asset::distinct()
        ->select('identity_asset.identity_code','ass.asset_id')
        ->join('asset as ass','identity_asset.identity_id','=','ass.identity_id')
        ->join('exchange_asset_pairs as pairs','pairs.asset_into_id','=','ass.asset_id')
        ->where('pairs.exchange_id','=',$request->exchange_id)
        ->where('pairs.asset_from_id','=',$request->asset_id)
        ->get();
        return json_encode($broker_list);
    }

    public function exchangeNameDetails(){
        $exchange_list=Exchange::distinct()
        ->select('exchange.exchange_id','ie.identity_name','ie.identity_name')
        ->join('identity_exchange as ie','ie.identity_id','=','exchange.identity_id')
        ->get();
        return json_encode($exchange_list);
    }
    public function assetSellPrice(Request $request){
        $asserSellPrice=DB::table('asset_rates')->
        where('asset_rates.asset_from_id','=',$request->asset_settlement)
        ->where('asset_rates.asset_into_id','=',$request->asset_from_id)
        ->select('asset_rates.asset_bid_price','asset_rates.asset_ask_price')->get()->first();
        return json_encode($asserSellPrice);
    }
    public function assetBuyPrice(Request $request){
        $asserSellPrice=DB::table('asset_rates')->
        where('asset_rates.asset_from_id','=',$request->asset_settlement)
        ->where('asset_rates.asset_into_id','=',$request->asset_into_id)
        ->select('asset_rates.asset_bid_price','asset_rates.asset_ask_price')->get()->first();
        return json_encode($asserSellPrice);
    }

    public function assetSettlementDetails(Request $request){
        $exchangeAssetList=DB::table('exchange_asset_list')
        /*->join('account as acc','acc.account_settlement','=','exchange_asset_list.asset_id')*/
        ->where('exchange_asset_list.exchange_id','=',$request->exchange_id)
        ->orderBy('exchange_asset_list.priority','desc')
        ->distinct()
        ->select('exchange_asset_list.asset_code','exchange_asset_list.asset_id')
        ->get();
        return json_encode($exchangeAssetList);
    }
    public function accountSettlementSelected(Request $request){
        $exchangeSettlementList=DB::table('account')
        ->where('account.account_id','=',$request->account_id)
        ->select('account.account_settlement','account.asset_quantity')
        ->get()->first();
        return json_encode($exchangeSettlementList);
    }
}