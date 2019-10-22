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
use App\Asset;
use App\Payment_summary;
use App\Payment_ledger;
use App\Merchant_city_list;
use App\Timezone;
use App\Payment_type;
use App\Payee;
use App\Identity_table_type;
use App\Location_list;
use App\Fee_summary;
use URL;
use Session;
use DB;
use Redirect;
use Auth;
use DateTime;
use DateTimeZone;
use Config;
use Carbon\Carbon;

class Payment_summaryController extends PermissionsController
{
    use PermissionTrait;

    public function __construct()
    {
        parent::__construct();

        $connectionStatus = ConnectionManager::setDbConfig('Payment_summary');

        if ($connectionStatus['type'] === "error") {
            Session::flash('type', $connectionStatus['type']);
            Session::flash('msg', $connectionStatus['message']);
            return Redirect::back()->send();
        }
    }
    public function index(){

       $merchantId = $this->merchantId;                  
       return view('payment_summary.index',compact('merchantId'));

    }

    
    public function getPersons()
    {
        $where = array();

        if($this->merchantId == 0){
            $where[] = array(
                'key' => "merchant.merchant_id",
                'operator' => '>',
                'val' => $this->merchantId
            );
            $where[] = array(
                'key' => "merchant_account_list.staff_account_id",
                'operator' => '!=',
                'val' => 0
            );
            //$where['merchant_type.merchant_root_id'] = $merchantType;

        }else{
            if($this->roleId == 4){
                $where[] = array(
                    'key' => "merchant.merchant_id",
                    'operator' => '=',
                    'val' => $this->merchantId
                );    
                $where[] = array(
                    'key' => "merchant_account_list.staff_account_id",
                    'operator' => '!=',
                    'val' => 0
                );            
            }else{
                $where[] = array(
                    'key' => "merchant.merchant_id",
                    'operator' => '=',
                    'val' => $this->merchantId
                );
                $where[] = array(
                    'key' => "merchant_account_list.staff_account_id",
                    'operator' => '!=',
                    'val' => 0
                );            
                /*$where['staff.location_id'] = $this->locationId; */
            }
        }        

        $merchants = Merchant::
        distinct('merchant.merchant_id')
        ->select(
            DB::raw("CONCAT('merchant_',merchant.merchant_id) AS person_id"),
            'identity_merchant.identity_name as person_name',
            'identity_merchant.identity_code as person_code'
        )
        ->join('identity_merchant','identity_merchant.identity_id','merchant.identity_id')
        ->join('merchant_account_list','merchant_account_list.merchant_id','=','merchant.merchant_id')
        ->where(function($q) use ($where){
            foreach($where as $key => $value){
                $q->where($value['key'], $value['operator'], $value['val']);
            }
        })->get()->toArray();

        $payees = Payee::distinct('payee.payee_id')
        ->select(
            DB::raw("CONCAT('payee_',payee.payee_id) AS person_id"),
            'identity_payee.identity_name as person_name',
            'identity_payee.identity_code as person_code'
        )
        ->join('identity_payee','identity_payee.identity_id','payee.identity_id')
        ->get()->toArray();        

        $people = array_merge($merchants,$payees);

        return json_encode($people);
    }

    public function getMerchantCityList(request $request)
    {
        $requestData = explode('_', $request->merchant_id);
        $requestTable = $requestData[0];
        $requestId = $requestData[1];

        switch ($requestTable) {
            case 'merchant':
                $table_id = "8";
                $table_primary_key = "merchant_id";
                $table_idnetity_key = "identity_id";
                break;

            case 'payee':
                $table_id = "21";
                $table_primary_key = "payee_id";
                $table_idnetity_key = "identity_id";
                break;

            default:
                # code...
                break;
        }

        $identity_table_data = Identity_table_type::select('table_code')
                            ->where('type_id','=',$table_id)
                            ->get()->first();

        $table_name = $identity_table_data->table_code;

        $identity_id = DB::table($table_name)->where($table_primary_key,$requestId)->value('identity_id');



        $merchant_cities = Location_list::
        distinct('location_city.city_id')
        ->select(
            'location_city.city_id','location_city.city_name'
        )
        ->join('location_city','location_city.city_id','location_list.location_city_id')        
        ->where('location_list.identity_table_id','=',$table_id)
        ->where('location_list.identity_id','=',$identity_id)
        ->get();

        return json_encode($merchant_cities);
    }

    public function getLocationPostalList(request $request)
    {
        $requestData = explode('_', $request->merchant_id);
        $requestTable = $requestData[0];
        $requestId = $requestData[1];

        switch ($requestTable) {
            case 'merchant':
                $table_id = "8";
                $table_primary_key = "merchant_id";
                $table_idnetity_key = "identity_id";
                break;

            case 'payee':
                $table_id = "21";
                $table_primary_key = "payee_id";
                $table_idnetity_key = "identity_id";
                break;

            default:
                # code...
                break;
        }

        $identity_table_data = Identity_table_type::select('table_code')
                            ->where('type_id','=',$table_id)
                            ->get()->first();

        $table_name = $identity_table_data->table_code;

        $identity_id = DB::table($table_name)->where($table_primary_key,$requestId)->value('identity_id');

        $location_city_id = $request->location_city_id;

        $location_postals = Location_list::
            distinct('location_list.postal_id')
            ->select(
                DB::raw('(CASE WHEN (postal.postal_premise != "" and postal.postal_subpremise != "") THEN CONCAT(postal.postal_premise,", ",SUBSTR(postal.postal_subpremise,1,10))
                              WHEN (postal.postal_premise != "") THEN postal.postal_premise                              
                              WHEN (postal.postal_subpremise !="") THEN SUBSTR(postal.postal_subpremise,1,10)
                              ELSE CONCAT(postal.postal_street_number," ",postal.postal_route)   
                        END) as location_name'),'location_list.list_id'
            )
            ->join('postal','postal.postal_id','location_list.postal_id')        
            ->where('location_list.identity_table_id','=',$table_id)
            ->where('location_list.identity_id','=',$identity_id)
            ->where('location_list.location_city_id','=',$location_city_id)
            ->get();
        
        return json_encode($location_postals);
    }
   
    public function getMerchantCustomers(Request $request){
        $customer_list=array(); 

        $requestData = explode('_', $request->merchant_id);
        $requestTable = $requestData[0];
        $requestId = $requestData[1];

        switch ($requestTable) {
            case 'merchant':
                $customer_list=Identity_customer::distinct('cust.customer_id')
                   ->select('identity_customer.identity_name as customer_name','cust.customer_id')
                   ->join('customers as cust ','cust.identity_id','=','identity_customer.identity_id')
                   ->join('merchant_customer_list as mcl ','mcl.customer_id','=','cust.customer_id')
                   ->where('mcl.merchant_id','=',$requestId)
                   ->get();
                break;

            default:
                # code...
                break;
        }
       
        return json_encode($customer_list);
    }

    public function merchantAccountList(Request $request){        
        $merchant_account_list=array();

        $requestData = explode('_', $request->merchant_id);
        $requestTable = $requestData[0];
        $requestId = $requestData[1];

        switch ($requestTable) {
            case 'merchant':
                $merchant_account_list=Account::distinct()
                    ->select('account.account_code_long','account.account_id')
                    ->join('merchant_account_list','merchant_account_list.staff_account_id','=','account.account_id')
                    ->where('merchant_account_list.merchant_id','=',$requestId)
                    ->get();
                break;

            default:
                # code...
                break;
        }
        
        return json_encode($merchant_account_list);
    }

    public function customerAccountList(Request $request){
        $customer_account=array();
        $customer_account_list=Account::distinct()
        ->select('account.account_code_long','account.account_id')
        ->join('customer_account_list','customer_account_list.account_id','=','account.account_id')
        ->where('customer_account_list.customer_id','=',$request->customer_id)
        ->get();
        return json_encode($customer_account_list);
    }
    
    public function getPaymentTypes(){
        $payment_types=array();
        $payment_types=Payment_type::All();
        return json_encode($payment_types);
    }

    public function getPaymentAssets(){
        $assets = array();
        $assets = Asset::select('asset.asset_id','identity_asset.identity_code as asset_code','identity_asset.identity_name as asset_name')
                    ->join('identity_asset','identity_asset.identity_id','=','asset.identity_id')
                    ->where('asset.asset_id','!=','0')
                    ->get();

        return json_encode($assets);
    }

    public function createPaymentSummary(request $request){
        $requestData = explode('_', $request->merchant_id);
        $requestTable = $requestData[0];
        $merchantId = $requestData[1];
        $timeZoneId = PermissionTrait::getTimeZoneId();
        $paymentDate = str_replace("-","",date('Y-m-d'));
        $paymentTime = time();
        
        $payment_summary = new Payment_summary;
        $payment_summary->merchant_id = $merchantId;
        $payment_summary->location_id = $request->location_id;
        $payment_summary->merchant_account_id = isset($request->merchant_account_id)?$request->merchant_account_id:0;
        $payment_summary->customer_account_id = isset($request->customer_account_id)?$request->customer_account_id:0;
        $payment_summary->payment_type = $request->payment_type_id;
        $payment_summary->payment_name = $request->payment_name;
        $payment_summary->payment_price = $request->payment_price;
        $payment_summary->payment_quantity = $request->payment_quantity;
        $payment_summary->payment_asset_id = $request->asset_id;
        $payment_summary->fee_amount = $request->fee_amount;
        $payment_summary->fee_asset_id = $request->fee_asset_id;
        $payment_summary->payment_timezone = $timeZoneId;
        $payment_summary->payment_date = $paymentDate;
        $payment_summary->payment_time = $paymentTime;

        $payment_summary->save();
    }

    public function getTableNameFromId($table_id){
        $identity_table_type = Identity_table_type::select('table_code')
                        ->where('type_id',$table_id)
                        ->get()->first();
        
        return $identity_table_type->table_code;
    }

    public function getPaymentSummaryList(Request $request)
    {
        $payment_list = array();
        $table_array = array(array(8,7),array(21,22));
        $total_records = 0;
        
        foreach ($table_array as $table_id) {
            $original_table_id = $table_id[0];
            $identity_table_id = $table_id[1];
            $orignal_table_name = $this->getTableNameFromId($original_table_id);
            $identity_table_name = $this->getTableNameFromId($identity_table_id);
            
            switch ($identity_table_id) {
                case '22':
                    $select_statement = "payment_summary.*,".
                    $identity_table_name.".identity_name as merchant_name,".
                    $identity_table_name.".identity_code as merchant_code,
                    CONCAT(postal.postal_premise,',',location_city.city_name) AS location_city,
                    payment_type.type_name as payment_type,
                    identity_payment_asset.identity_code as payment_asset_code,
                    identity_payment_asset.identity_name as payment_asset_name,
                    identity_fee_asset.identity_code as fee_asset_code,
                    identity_fee_asset.identity_name as fee_asset_name,'Payee' as group_name";
                    break;
                
                default:
                    $select_statement = "payment_summary.*,".
                    $identity_table_name.".identity_name as merchant_name,".
                    $identity_table_name.".identity_code as merchant_code,
                    CONCAT(postal.postal_premise,',',location_city.city_name) AS location_city,
                    payment_type.type_name as payment_type,
                    identity_payment_asset.identity_code as payment_asset_code,
                    identity_payment_asset.identity_name as payment_asset_name,
                    identity_fee_asset.identity_code as fee_asset_code,
                    identity_fee_asset.identity_name as fee_asset_name,
                    account_merchant.account_code_long as merchant_account,
                    account_customer.account_code_long as customer_account,'Merchant' as group_name";
                    break;
            }

            $payment_summary_active_list=DB::table('payment_summary')
            ->distinct()  
            ->select(DB::raw($select_statement))
            ->join('location_list','location_list.list_id','payment_summary.location_id')
            ->join($identity_table_name,$identity_table_name.'.identity_id','location_list.identity_id')
            ->join('location_city','location_city.city_id','location_list.location_city_id')
            ->join('postal','postal.postal_id','location_list.postal_id')            
            ->join('payment_type','payment_type.type_id','payment_summary.payment_type')
            ->join('asset as payment_asset','payment_asset.asset_id','payment_summary.payment_asset_id')
            ->join('identity_asset as identity_payment_asset','identity_payment_asset.identity_id','payment_asset.identity_id')
            ->join('asset as fee_asset','fee_asset.asset_id','payment_summary.fee_asset_id')
            ->join('identity_asset as identity_fee_asset','identity_fee_asset.identity_id','fee_asset.identity_id');

            if($original_table_id != 21){

                $payment_summary_active_list->join('account as account_merchant','account_merchant.account_id','=','payment_summary.merchant_account_id');
                $payment_summary_active_list->join('account as account_customer','account_customer.account_id','=','payment_summary.customer_account_id');
            }
            $payment_summary_active_list->where('location_list.identity_table_id',$original_table_id);
            $list_data = $payment_summary_active_list->offset($request->skip)
            ->limit($request->take)->get()->toArray();

            $total_records = $total_records + $payment_summary_active_list->get()->count();



            $payment_list = array_merge($payment_list,$list_data);
        } 

        foreach ($payment_list as $payment_list_key => $payment_list_value) {
            $payemnt_ledger = Payment_ledger::select(DB::Raw('count(ledger_id) as ledgerCount'))->where('summary_id',$payment_list_value->summary_id)->groupBy('summary_id')->get()->first();
            if($payemnt_ledger)
            {
                $payment_list[$payment_list_key]->ledger_count = $payemnt_ledger->ledgerCount;
            } else {
                $payment_list[$payment_list_key]->ledger_count = 0;
            }
        }

        $payment_list_data['payment_list'] = $payment_list;
        $payment_list_data['total'] = $total_records;
        
        return json_encode($payment_list_data);        
    }

    public function getAllPaymentLedger(Request $request) {
        $payment_ledger_list = Payment_ledger::distinct()
            ->select('payment_ledger.*',
                'identity_staff.identity_name as staff_name',
                'group_permissions.group_name',
                'location_city.city_name',
                'identity_merchant_account.identity_name as merchant_account_name',
                'identity_customer_account.identity_name as customer_account_name',
                'payment_type.type_name as payment_type_name',
                'identity_asset.identity_code as payment_asset_name',
                'transaction_type.type_name as transaction_type_name'
            )
            ->join('staff','staff.staff_id','payment_ledger.staff_id')
            ->join('identity_staff','identity_staff.identity_id','staff.identity_id')
            ->join('group_permissions','group_permissions.group_id','payment_ledger.group_id')
            ->join('location_list','location_list.list_id','payment_ledger.location_id')
            ->join('location_city','location_city.city_id','location_list.location_city_id')
            ->join('account as account_merchant','account_merchant.account_id','payment_ledger.merchant_account_id')
            ->join('identity_account as identity_merchant_account', 'identity_merchant_account.identity_id','account_merchant.identity_id')
            ->join('account as account_customer','account_customer.account_id','payment_ledger.customer_account_id')
            ->join('identity_account as identity_customer_account', 'identity_customer_account.identity_id','account_customer.identity_id')
            ->join('payment_type','payment_type.type_id','payment_ledger.payment_type')
            ->join('asset','asset.asset_id','payment_ledger.payment_asset_id')
            ->join('identity_asset','identity_asset.identity_id','asset.identity_id')
            ->join('payment_type as transaction_type','transaction_type.type_id','payment_ledger.transaction_type_id')
            ->join('payment_summary','payment_summary.summary_id','payment_ledger.summary_id')
            ->where('payment_ledger.summary_id',$request->summaryId)
            ->get();
        return json_encode($payment_ledger_list);
    }
    public function updatePaymentLedger(Request $request) {
        $ledgerId = $request->ledger_id;
        $key = $request->key;
        $value = $request->value;
        $paymentLedger = Payment_ledger::findOrfail($ledgerId);
        if($key === "vendor_name") {
            $paymentLedger->vendor_name = $value;
        }
        else if($key === "payment_description") {
            $paymentLedger->payment_description = $value;
        }
        else if($key === "payment_name") {
            $paymentLedger->payment_name = $value;
        }
        else if($key === "payment_notes") {
            $paymentLedger->payment_notes = $value;
        }
        else if($key === "transaction_address") {
            $paymentLedger->transaction_address = $value;
        }
        else if($key === "transaction_address_url") {
            $paymentLedger->transaction_address_url = $value;
        }
        else if($key === "transaction_internal_ref") {
            $paymentLedger->transaction_internal_ref = $value;
        }
        else if($key === "transaction_root") {
            $paymentLedger->transaction_root = $value;
        }
        else if($key === "ledger_hash") {
            $paymentLedger->ledger_hash = $value;
        }
        $paymentLedger->save();
    }
}

