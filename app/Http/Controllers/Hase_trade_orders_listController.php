<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\App;
use Illuminate\Http\Request;
use App\Http\Controllers\PermissionsController;
use App\Helpers\ConnectionManager;
use App\Http\Traits\PermissionTrait;

use App\TradeOrder;
use App\Staff;
use App\Account;
use App\Exchange;
use App\Timezone;
use App\TradeSideType;
use App\TradeOrderType;
use App\TradeStatusType;
use App\TradeReasonType;
use App\TradeTransactionType;
use App\Transaction_summary;
use App\Transactions_ledger;
use App\Status_operations_type;
use App\Asset;
use App\TradePosition;
use App\Trade_limits;
use App\Asset_move;
use App\Identity_table_type;

use URL;
use Session;
use DB;
use Redirect;
use Auth;

class Hase_trade_orders_listController extends PermissionsController
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
    public function tradeOrdersQueue(){
        if($this->permissionDetails('Hase_trade_orders_queue','access')){
        $baseUrl=dirname(URL::to('/'));
        $compareTime = time() - 180;
        $tradeOrdersQueueListData=TradeOrder::
                    distinct()
                    ->select(
                        'trade_orders.*',
                        'asset_flow.*','group_permissions.group_name as staff_group_name')
                        ->leftjoin('group_permissions','group_permissions.group_id','trade_orders.group_id')
                        ->leftjoin('asset_flow as asset_flow','asset_flow.merchant_id','trade_orders.merchant_id')
                        ->leftjoin('asset_flow as asset_flows','asset_flows.staff_id','trade_orders.staff_id')
                        ->where('trade_orders.status_operation','=',6)
                        ->orwhere('trade_orders.status_operation','=',10)
                        ->orwhere(function($tradeOrdersQueueListData) use ($compareTime) {
                             $tradeOrdersQueueListData->where('trade_orders.status_operation','=', 8)
                               ->Where('trade_orders.status_time','<', $compareTime);
                         })
                        ->take(1)->get();
            if(isset($tradeOrdersQueueListData[0])){
                return view('hase_otc_order_entry.trade_orders_queue',compact('title','baseUrl','tradeOrdersQueueListData'));
            }
            $retry='retry';
        return view('hase_otc_order_entry.trade_orders_queue',compact('title','retry','baseUrl'));
        }else{
            return Redirect::back()->with('message', 'You are not authorized to use this functionality!');
        }
    }

    public function tradeOrdersQueueMobile(){
        $baseUrl=dirname(URL::to('/'));
        $compareTime = time() - 180;
        $tradeOrdersQueueListData=TradeOrder::
                    distinct()
                    ->select(
                        'trade_orders.*',
                        'asset_flow.*','group_permissions.group_name as staff_group_name')
                        ->leftjoin('group_permissions','group_permissions.group_id','trade_orders.group_id')
                        ->leftjoin('asset_flow as asset_flow','asset_flow.merchant_id','trade_orders.merchant_id')
                        ->leftjoin('asset_flow as asset_flows','asset_flows.staff_id','trade_orders.staff_id')
                        ->where('trade_orders.status_operation','=',6)
                        ->orwhere('trade_orders.status_operation','=',10)
                        ->orwhere(function($tradeOrdersQueueListData) use ($compareTime) {
                             $tradeOrdersQueueListData->where('trade_orders.status_operation','=', 8)
                               ->Where('trade_orders.status_time','<', $compareTime);
                         })
                        ->take(1)->get();
            if(isset($tradeOrdersQueueListData[0])){
                return view('hase_otc_order_entry.trade_orders_queue_mobile',compact('title','baseUrl','tradeOrdersQueueListData'));
            }
            $retry='retry';
        return view('hase_otc_order_entry.trade_orders_queue_mobile',compact('title','retry','baseUrl'));
    }
    
    public function tradeOrderListView(){        
        return view('hase_trade_order_list.trade_order_details_list',compact('','title'));
    } 
    public function otcManagerEntryList(Request $request){
        $accountId=$request->accountId;
        $accountList=null;
        $commaSeparators="";
        $customer_account_list=DB::table('customer_account_list')->get();
        if(isset($customer_account_list)){
            foreach ($customer_account_list as $customer_account_list_value) {
                    $statusId=$customer_account_list_value->account_id;
                    $accountList.=$commaSeparators.$statusId;
                    $commaSeparators =',';
            }
            $accountValue=explode(",", $accountList);
            $accountListArray =$accountValue;
        }
        $tradeSideTypeList = array();
        $otcManagerEntryDetailList=array();
        $where = array();

        $merchantTypeInfo = PermissionTrait::getMerchantType();
        $merchantType = $merchantTypeInfo->merchant_type_id;

        if($this->merchantId == 0){
            //$where['merchant_type.merchant_root_id'] = $merchantType;
        }else{
            if($this->roleId == 4){
                $where['trade_orders.merchant_id'] = $this->merchantId;
            }else{
                $where['trade_orders.merchant_id'] = $this->merchantId; 
            }
        }
        if($accountId == 1){
            $statusValue = 'Pending';
        } else {
            $statusValue = 'Complete';
        }

        $trade_orders = TradeOrder::
                    distinct()
                    ->select(
                        'trade_orders.*',
                        
                        'identity_merchant.identity_name as merchant_name',                        
                        'identity_customer.identity_name as customer_name',
                        
                        'broker_account.account_code_long as broker_account_code_long',
                        'broker_identity_account.identity_name as broker_account_name',
                        
                        'customer_account.account_code_long as customer_account_code_long',
                        'customer_identity_account.identity_name as customer_account_name',

                        'trade_order_side_type.side_type_name',
                        'asset_from_identity.identity_code as asset_from_code',
                        'asset_into_identity.identity_code as asset_into_code',

                        'status_operations_type.type_name as status_operations_type_id',
                        'status_operations_type.type_name as status_operations_type_name',
                        'status_fiat_type.status_fiat_type_id',
                        'status_fiat_type.status_fiat_type_name',
                        'status_crypto_type.status_crypto_type_id',
                        'status_crypto_type.status_crypto_type_name',

                        'status_operations_type.color_foreground as status_operations_type_color',
                        'status_fiat_type.status_fiat_type_color',
                        'status_crypto_type.status_crypto_type_color',


                        'start_timezone.timezone_name as start_timezone',
                        'expire_timezone.timezone_name as expire_timezone',

                        'fee_asset_identity.identity_code as fee_asset_code'
                        )

                        ->leftjoin('merchant','merchant.merchant_id','trade_orders.merchant_id')
                        ->leftjoin('identity_merchant','identity_merchant.identity_id','merchant.identity_id')

                        ->leftjoin('staff','staff.staff_id','trade_orders.staff_id')
                        ->leftjoin('customer_account_list','customer_account_list.account_id','trade_orders.customer_account_id')
                        ->leftjoin('customers','customers.customer_id','customer_account_list.customer_id')
                        ->leftjoin('identity_customer','identity_customer.identity_id','customers.identity_id')

                        ->leftjoin('account as broker_account','broker_account.account_id','trade_orders.merchant_account_id')
                        ->leftjoin('identity_account as broker_identity_account','broker_identity_account.identity_id','broker_account.identity_id')

                        ->leftjoin('account as customer_account','customer_account.account_id','trade_orders.customer_account_id')
                        ->leftjoin('identity_account as customer_identity_account','customer_identity_account.identity_id','customer_account.identity_id')

                        ->leftjoin('trade_order_side_type','trade_order_side_type.side_type_id','trade_orders.side_type_id')

                        ->leftjoin('status_operations_type','status_operations_type.type_id','trade_orders.status_operation')

                        ->leftjoin('status_fiat_type','status_fiat_type.status_fiat_type_id','trade_orders.status_fiat')

                        ->leftjoin('status_crypto_type','status_crypto_type.status_crypto_type_id','trade_orders.status_crypto')

                        ->leftjoin('timezone as start_timezone','start_timezone.timezone_id','trade_orders.start_timezone')
                        ->leftjoin('timezone as expire_timezone','expire_timezone.timezone_id','trade_orders.expire_timezone')

                        ->leftjoin('asset as asset_from','asset_from.asset_id','trade_orders.asset_from_id')
                        ->leftjoin('identity_asset as asset_from_identity','asset_from_identity.identity_id','asset_from.identity_id')

                        ->leftjoin('asset as asset_into','asset_into.asset_id','trade_orders.asset_into_id')
                        ->leftjoin('identity_asset as asset_into_identity','asset_into_identity.identity_id','asset_into.identity_id')
                                
                        ->leftjoin('asset as fee_asset','fee_asset.asset_id','trade_orders.fee_asset')
                        ->leftjoin('identity_asset as fee_asset_identity','fee_asset_identity.identity_id','fee_asset.identity_id')
                        ->where(function($trade_orders) use ($statusValue)  {
                            if($statusValue === 'Pending') {
                                $trade_orders->where('status_fiat_type.status_fiat_type_name','=',$statusValue);
                                $trade_orders->orwhere('status_crypto_type.status_crypto_type_name','=',$statusValue);
                                $trade_orders->orwhere('status_operations_type.type_name','=',$statusValue);
                            } else {
                                $trade_orders->where('status_fiat_type.status_fiat_type_name','=',$statusValue);
                                $trade_orders->where('status_crypto_type.status_crypto_type_name','=',$statusValue);
                                    $trade_orders->where('status_operations_type.type_name','=',$statusValue);
                            }
                        })
                        ->where(function($q) use ($where){
                            foreach($where as $key => $value){
                                $q->where($key, '=', $value);
                            }
                        })->whereIn('trade_orders.customer_account_id',$accountListArray)
                        ->orderby('trade_orders.order_id','desc')
                        ->offset($request->skip)
                        ->limit($request->take)
                        ->get();

        $total_trade_orders = TradeOrder::
                    distinct()
                        ->leftjoin('merchant','merchant.merchant_id','trade_orders.merchant_id')
                        ->leftjoin('identity_merchant','identity_merchant.identity_id','merchant.identity_id')

                        ->leftjoin('staff','staff.staff_id','trade_orders.staff_id')
                        ->leftjoin('customer_account_list','customer_account_list.account_id','trade_orders.customer_account_id')
                        ->leftjoin('customers','customers.customer_id','customer_account_list.customer_id')
                        ->leftjoin('identity_customer','identity_customer.identity_id','customers.identity_id')

                        ->leftjoin('account as broker_account','broker_account.account_id','trade_orders.merchant_account_id')
                        ->leftjoin('identity_account as broker_identity_account','broker_identity_account.identity_id','broker_account.identity_id')

                        ->leftjoin('account as customer_account','customer_account.account_id','trade_orders.customer_account_id')
                        ->leftjoin('identity_account as customer_identity_account','customer_identity_account.identity_id','customer_account.identity_id')

                        ->leftjoin('trade_order_side_type','trade_order_side_type.side_type_id','trade_orders.side_type_id')

                        ->leftjoin('status_operations_type','status_operations_type.type_id','trade_orders.status_operation')

                        ->leftjoin('status_fiat_type','status_fiat_type.status_fiat_type_id','trade_orders.status_fiat')

                        ->leftjoin('status_crypto_type','status_crypto_type.status_crypto_type_id','trade_orders.status_crypto')

                        ->leftjoin('timezone as start_timezone','start_timezone.timezone_id','trade_orders.start_timezone')
                        ->leftjoin('timezone as expire_timezone','expire_timezone.timezone_id','trade_orders.expire_timezone')

                        ->leftjoin('asset as asset_from','asset_from.asset_id','trade_orders.asset_from_id')
                        ->leftjoin('identity_asset as asset_from_identity','asset_from_identity.identity_id','asset_from.identity_id')

                        ->leftjoin('asset as asset_into','asset_into.asset_id','trade_orders.asset_into_id')
                        ->leftjoin('identity_asset as asset_into_identity','asset_into_identity.identity_id','asset_into.identity_id')
                                
                        ->leftjoin('asset as fee_asset','fee_asset.asset_id','trade_orders.fee_asset')
                        ->leftjoin('identity_asset as fee_asset_identity','fee_asset_identity.identity_id','fee_asset.identity_id')
                        ->where(function($trade_orders) use ($statusValue)  {
                            if($statusValue === 'Pending') {
                                $trade_orders->where('status_fiat_type.status_fiat_type_name','=',$statusValue);
                                $trade_orders->orwhere('status_crypto_type.status_crypto_type_name','=',$statusValue);
                                $trade_orders->orwhere('status_operations_type.type_name','=',$statusValue);
                            } else {
                                $trade_orders->where('status_fiat_type.status_fiat_type_name','=',$statusValue);
                                $trade_orders->where('status_crypto_type.status_crypto_type_name','=',$statusValue);
                                    $trade_orders->where('status_operations_type.type_name','=',$statusValue);
                            }
                        })
                        ->where(function($q) use ($where){
                            foreach($where as $key => $value){
                                $q->where($key, '=', $value);
                            }
                        })->whereIn('trade_orders.customer_account_id',$accountListArray)
                        ->count();                

        $sideTypeList=DB::table('status_fiat_type')->get();
        if(isset($sideTypeList))
        {
            foreach ($sideTypeList as $sideTypeListy => $sideTypeListValue) {
                $tradeSideTypeList[$sideTypeListy]['id'] = $sideTypeListValue->status_fiat_type_id;
                $tradeSideTypeList[$sideTypeListy]['name'] = $sideTypeListValue->status_fiat_type_name;
            }
        } 
        $status_operations_type=DB::table('status_operations_type')->get();
        if(isset($status_operations_type))
        {
            foreach ($status_operations_type as $operationTypeListy => $soperationTypeValue) {
                $operationTypeList[$operationTypeListy]['id'] = $soperationTypeValue->type_id;
                $operationTypeList[$operationTypeListy]['name'] = $soperationTypeValue->type_name;
            }
        } 
        $status_crypto_type=DB::table('status_crypto_type')->get();
        if(isset($status_crypto_type))
        {
            foreach ($status_crypto_type as $cryptoTypeListy => $cryptoTypeValue) {
                $cryptoTypeList[$cryptoTypeListy]['id'] = $cryptoTypeValue->status_crypto_type_id;
                $cryptoTypeList[$cryptoTypeListy]['name'] = $cryptoTypeValue->status_crypto_type_name;
            }
        }
        foreach ($trade_orders as $key=>$trade_order){
            $startDateTime = json_decode($this->covertToLocalTz($trade_order->start_time), true);
            $endDateTime = json_decode($this->covertToLocalTz($trade_order->expire_time), true);
            $trade_orders[$key]->start_date=$startDateTime['date'];
            $trade_orders[$key]->start_time=$startDateTime['time'];
            $trade_orders[$key]->expire_date=$endDateTime['date'];
            $trade_orders[$key]->expire_time=$endDateTime['time'];
            $trade_orders[$key]->status_list = $tradeSideTypeList;
            $trade_orders[$key]->operation_status_List = $operationTypeList;
            $trade_orders[$key]->status_crypto_type=$cryptoTypeList;
        }
        $tradeOrderListData['trade_order'] = $trade_orders;
        $tradeOrderListData['total'] = $total_trade_orders;
        
        return json_encode($tradeOrderListData);
    }

    public function customerOrderDetailsList(Request $request)
    {
        $customerOrderList=DB::table('trade_orders')
            ->leftjoin('customer_account_list','customer_account_list.account_id','trade_orders.customer_account_id')
            ->where('order_id','=',$request->order_id)->get()->first();
        return json_encode($customerOrderList);
    }

    public function fxAllRatesList()
    {
        $fxAllRatesList=DB::table('asset')
        ->leftjoin('identity_asset','identity_asset.identity_id','asset.identity_id')
        ->select('asset.asset_id','identity_asset.identity_code')
        ->get();
        return json_encode($fxAllRatesList);
    }

    public function updateTradeOrderStatus(Request $request)
    {   

        $statusFieldName = $request->status_field;
        $timeZoneId = PermissionTrait::getTimeZoneId();
        $tradeOrderObject = TradeOrder::findOrfail($request->order_id);
        $tradeOrderObject->$statusFieldName = $request->statusId;
        $tradeOrderObject->save();
        $tradeStartDate = str_replace("-","",date('Y-m-d'));
        $tradeStartTime = time();
        /*summary logic */
        $exceptStatues = array('reviewing_trade','review_queue');
        $customerOperationStatus=Status_operations_type::select('type_id')
            ->whereIn('type_code',$exceptStatues)->get();
        $statusFound = 0;
        foreach ($customerOperationStatus as $customerOperationStatusKey => $customerOperationStatusValue) {

            if($customerOperationStatusValue['type_id'] ==$request->statusId)
            {
                $statusFound = 1;
            }

        }
        if($statusFound == 0)
        {
            
            $transactionSummaryObject = Transaction_summary::where('order_id',$request->order_id)
            ->get()->first();
            /*ledger insert */
            $transactions_ledger = new Transactions_ledger();
            $transactions_ledger->summary_id = $transactionSummaryObject->summary_id;
            $transactions_ledger->basket_id = $tradeOrderObject->basket_id;
            $transactions_ledger->order_id = $request->order_id;
            $transactions_ledger->group_id = $tradeOrderObject->group_id;
            $transactions_ledger->staff_id = $tradeOrderObject->staff_id;
            $transactions_ledger->merchant_account_id = $tradeOrderObject->merchant_account_id;
            $transactions_ledger->customer_account_id = $tradeOrderObject->customer_account_id;
            $transactions_ledger->exchange_id = $tradeOrderObject->exchange_id;
            $transactions_ledger->trade_timezone = $timeZoneId;
            $transactions_ledger->trade_date = $tradeStartDate;
            $transactions_ledger->trade_time = $tradeStartTime;
            $transactions_ledger->side_type_id = $tradeOrderObject->side_type_id;
            $transactions_ledger->asset_from_id = $tradeOrderObject->asset_from_id;
            $transactions_ledger->asset_from_quote = $transactionSummaryObject->asset_from_quote;
            $transactions_ledger->asset_from_price = $tradeOrderObject->asset_from_price;
            $transactions_ledger->asset_from_quantity = $tradeOrderObject->asset_from_quantity;
            $transactions_ledger->asset_into_id = $tradeOrderObject->asset_into_id;
            $transactions_ledger->asset_into_quote = $transactionSummaryObject->asset_into_quote;
            $transactions_ledger->asset_into_price = $tradeOrderObject->asset_into_price;
            $transactions_ledger->asset_into_quantity = $tradeOrderObject->asset_into_quantity;
            $transactions_ledger->order_type_id = $tradeOrderObject->order_type_id;

            $transactions_ledger->status_type_id = 1;
            $transactions_ledger->reason_type_id = 1;
            $transactions_ledger->fee_amount = $tradeOrderObject->fee_amount;
            $transactions_ledger->fee_asset_id = $tradeOrderObject->fee_asset;
            $transactions_ledger->fee_referrer_id = $tradeOrderObject->fee_referrer;
            $transactions_ledger->transaction_address = 'None';
            $transactions_ledger->transaction_address_url = 'None';

            $transactions_ledger->transaction_type_id = 1;
            $transactions_ledger->transaction_exchange_id = 1;
            $transactions_ledger->transaction_exchange_ref = $tradeOrderObject->transaction_internal_ref;
            $transactions_ledger->transaction_internal_ref = $tradeOrderObject->transaction_internal_ref;
            $transactions_ledger->transaction_root = $tradeOrderObject->transaction_internal_ref;
            $transactions_ledger->ledger_hash = 0;
            $transactions_ledger->save();

        }

        
        /*position insert */
        if($statusFieldName === 'status_operation')
        {
            if($request->statusId == 1)
            {
                $statusFound = 0;
                $transactions_ledger->asset_from_id = $tradeOrderObject->asset_from_id;
                $transactions_ledger->merchant_id = $tradeOrderObject->merchant_id;

                $trade_limits = Trade_limits::select('trade_limits.quantity_maximum')
                        ->where('merchant_id', $tradeOrderObject->merchant_id)
                        ->where('staff_id', $tradeOrderObject->staff_id)
                        ->where('merchant_account_id', $tradeOrderObject->merchant_account_id)
                        ->where('customer_account_id', $tradeOrderObject->customer_account_id)
                        ->where('asset_id', $tradeOrderObject->asset_from_id)
                        ->get()->first();

                if($trade_limits && $tradeOrderObject->asset_from_quantity >= $trade_limits->quantity_maximum) {
                    $sourceTable = Identity_table_type::select('type_id')
                        ->where('table_code', 'trade_orders')
                        ->get()->first();
                    $asset_move = new Asset_move();
                    $asset_move->source_table = $sourceTable->type_id;
                    $asset_move->source_id = $tradeOrderObject->order_id;
                    $asset_move->merchant_id = $tradeOrderObject->merchant_id;
                    $asset_move->staff_group_id = $tradeOrderObject->group_id;
                    $asset_move->staff_id = $tradeOrderObject->staff_id;
                    $asset_move->account_from_id = $tradeOrderObject->merchant_account_id;
                    $asset_move->account_into_id = $tradeOrderObject->customer_account_id;
                    $asset_move->asset_id = $tradeOrderObject->asset_from_id;
                    $asset_move->asset_price = $tradeOrderObject->asset_from_price;
                    $asset_move->asset_quantity = $tradeOrderObject->asset_from_quantity;
                    $asset_move->move_timezone = $timeZoneId;
                    $asset_move->move_date = $tradeStartDate;
                    $asset_move->move_time = $tradeStartTime;
                    $asset_move->status = 1;
                    $asset_move->approval_staff_id = $this->staffId;
                    $asset_move->save();
                }
                else {
                    $tradeSideTypeList=DB::table('asset_type')
                    ->join('asset as ass','ass.asset_type_id','=','asset_type.asset_type_id')
                    ->where('ass.asset_id','=',$tradeOrderObject->asset_from_id)
                    ->select('asset_type.asset_type_code')
                    ->get()->first();
                
                    if($tradeSideTypeList->asset_type_code === 'fiat') {
                        /* trade sell       customer buy*/

                        /* trader account update */
                        $traderAccountObject = Account::findOrfail($tradeOrderObject->merchant_account_id);
                        $traderQuantityRemain = $traderAccountObject->asset_quantity- $tradeOrderObject->asset_from_quantity;
                        if($traderQuantityRemain != 0)
                        {
                            if($traderAccountObject->asset_quantity == 0)
                            {
                                
                                $currentTraderPosition = $traderAccountObject->asset_quantity * $traderAccountObject->asset_price;
                                $tradeTraderPosition =  $tradeOrderObject->asset_from_quantity*$tradeOrderObject->asset_from_price;
                                $totalTraderQuantity =  $traderAccountObject->asset_quantity+$tradeOrderObject->asset_from_quantity;
                                $traderAccountObject->asset_price = ($currentTraderPosition + $tradeTraderPosition)/($totalTraderQuantity);

                            }

                            $traderAccountObject->asset_quantity = $traderAccountObject->asset_quantity- $tradeOrderObject->asset_from_quantity;
                        } else {
                            $traderAccountObject->asset_price = 0;
                            $traderAccountObject->asset_quantity = 0;
                        }
                        $traderAccountObject->save();
                        /* end trader account update */


                        /* Customer account update */
                        $customerAccountObject = Account::findOrfail($tradeOrderObject->customer_account_id);
                        $customerQuantityRemain = $customerAccountObject->asset_quantity+ $tradeOrderObject->asset_into_quantity;
                        if($customerQuantityRemain != 0)
                        {
                            $currentCustomerPosition = $customerAccountObject->asset_quantity * $customerAccountObject->asset_price;
                            $tradeCustomerPosition =  $tradeOrderObject->asset_into_quantity*$tradeOrderObject->asset_into_price;
                            $totalCustomerQuantity =  $customerAccountObject->asset_quantity+$tradeOrderObject->asset_from_quantity;
                            $customerAccountObject->asset_price = ($currentCustomerPosition + $tradeCustomerPosition)/($totalCustomerQuantity);
                            $customerAccountObject->asset_quantity = $customerAccountObject->asset_quantity+ $tradeOrderObject->asset_into_quantity;
                        } else {
                            $customerAccountObject->asset_quantity = 0;
                            $customerAccountObject->asset_price = 0;
                        }
                        $customerAccountObject->save();
                        /* end account update */
                    } else {
                        /* trade buy       customer sell*/

                        $traderAccountObject = Account::findOrfail($tradeOrderObject->merchant_account_id);
                        $traderQuantityRemain =  $traderAccountObject->asset_quantity+ $tradeOrderObject->asset_from_quantity;
                        /* trader account update */
                        if($traderQuantityRemain != 0)
                        {

                            $currentTraderPosition = $traderAccountObject->asset_quantity * $traderAccountObject->asset_price;
                            $tradeTraderPosition =  $tradeOrderObject->asset_from_quantity*$tradeOrderObject->asset_from_price;
                            $totalTraderQuantity =  $traderAccountObject->asset_quantity+$tradeOrderObject->asset_from_quantity;
                            $traderAccountObject->asset_price = ($currentTraderPosition + $tradeTraderPosition)/($totalTraderQuantity);
                            $traderAccountObject->asset_quantity = $traderAccountObject->asset_quantity+ $tradeOrderObject->asset_from_quantity;
                            
                        } else {
                            $traderAccountObject->asset_quantity = 0;
                            $traderAccountObject->asset_price = 0;

                        }
                        $traderAccountObject->save();
                        /* end trader account update */

                        /* Customer account update */
                        $customerAccountObject = Account::findOrfail($tradeOrderObject->customer_account_id);
                        $customerQuantityRemain = $customerAccountObject->asset_quantity- $tradeOrderObject->asset_into_quantity;
                        if($customerQuantityRemain != 0)
                        {

                            if($customerAccountObject->asset_quantity == 0)
                            {
                                echo $customerAccountObject->asset_quantity."<br>";
                                echo $tradeOrderObject->asset_into_quantity."<br>";
                                $currentCustomerPosition = $customerAccountObject->asset_quantity * $customerAccountObject->asset_price;
                                $tradeCustomerPosition =  $tradeOrderObject->asset_into_quantity*$tradeOrderObject->asset_into_price;
                                $totalCustomerQuantity =  $customerAccountObject->asset_quantity+$tradeOrderObject->asset_from_quantity;
                                $customerAccountObject->asset_price = ($currentCustomerPosition + $tradeCustomerPosition)/($totalCustomerQuantity);
                            }
                            $customerAccountObject->asset_quantity = $customerAccountObject->asset_quantity- $tradeOrderObject->asset_into_quantity;
                        } else {
                            $customerAccountObject->asset_quantity =0;
                            $customerAccountObject->asset_price =0;
                        }
                        $customerAccountObject->save();
                        /*end Customer account update */
                    }
                }
            }
        }
    }
    public function updateQueueStatus(Request $request)
    {
        $tradeOrderObject = TradeOrder::findOrfail($request->order_id);
        $currenteTime = time();
        if($tradeOrderObject->status_operation != 8)
        {
            $tradeOrderObject->status_time = $currenteTime;
        }
        $tradeOrderObject->status_operation = $request->status_id;
        $tradeOrderObject->staff_id = $this->staffId;
        $tradeOrderObject->save();
    }

    public function accountQuantityData(Request $request)
    {
        $tradeOrderObject = Account::findOrfail($request->account_id);
        return json_encode($tradeOrderObject);
    }

    
    
}