<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\App;
use Illuminate\Http\Request;
use App\Http\Controllers\PermissionsController;
use App\Helpers\ConnectionManager;
use App\Payment_ledger;
use App\Payment_summary;
use App\Staff;
use App\Account;
use App\Asset;
use App\Payment_type;
use App\Fee_summary;

use Amranidev\Ajaxis\Ajaxis;
use App\Http\Controllers\ProductApprovalController;
use App\Http\Traits\PermissionTrait;

use URL;
use Session;
use DB;
use Redirect;

/**
 * Class Payment_ledgerController.
 *
 * @author  The scaffold-interface created at 2018-03-23 11:02:52am
 * @link  https://github.com/amranidev/scaffold-interface
 */
class Payment_ledgerController extends PermissionsController
{
    use PermissionTrait;

    public function __construct()
    {
        parent::__construct();

        $connectionStatus = ConnectionManager::setDbConfig('Payment_ledger');

        if ($connectionStatus['type'] === "error") {
            Session::flash('type', $connectionStatus['type']);
            Session::flash('msg', $connectionStatus['message']);
            return Redirect::back()->send();
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return  \Illuminate\Http\Response
     */
    public function create()
    {
        if($this->permissionDetails('Payment_ledger','add')) {
            $accounts = Account::all();
            $paymentSummaries = Payment_summary::all();
            $allStaffs = Staff::all();
            $accounts = Account::select('account.account_id','identity_account.identity_name as account_name')
                ->join('identity_account','identity_account.identity_id','account.identity_id')
                ->where('account.account_id','!=',0)->get();
            $paymentTypes = Payment_type::all();
            $assets = Asset::select('asset.asset_id','identity_asset.identity_name as asset_name','identity_asset.identity_code as asset_code')
                ->join('identity_asset','identity_asset.identity_id','asset.identity_id')
                ->where('asset.asset_id','!=',0)->get();
            $feeSummary = Fee_summary::all();

            return view('payment_ledger.create',compact('paymentSummaries','allStaffs','paymentTypes','accounts','assets','feeSummary'));
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
        $timeZoneId = PermissionTrait::getTimeZoneId();
        $paymentDate = str_replace("-","",date('Y-m-d'));
        $paymentTime = time();
        $payment_ledger = new Payment_ledger();
        
        $paymentSummary = Payment_summary::findOrfail($request->summary_id);

        $payment_ledger->summary_id = $request->summary_id;

        $payment_ledger->location_id = $paymentSummary->location_id;
     
        $payment_ledger->group_id = $paymentSummary->group_id;
   
        $payment_ledger->staff_id = $paymentSummary->staff_id;
    
        $payment_ledger->merchant_account_id = $paymentSummary->merchant_account_id;
    
        $payment_ledger->customer_account_id = $paymentSummary->customer_account_id;
    
        $payment_ledger->vendor_name = $request->vendor_name;
     
        $payment_ledger->payment_type = $request->payment_type;
     
        $payment_ledger->payment_description = $request->payment_description;
     
        $payment_ledger->payment_name = $request->payment_name;
     
        $payment_ledger->payment_timezone = $timeZoneId;
     
        $payment_ledger->payment_date = $paymentDate;
    
        $payment_ledger->payment_time = $paymentTime;
     
        $payment_ledger->payment_notes = $request->payment_notes;
     
        $payment_ledger->payment_quantity = $request->payment_quantity;
     
        $payment_ledger->payment_unit_price = $request->payment_unit_price;
   
        $payment_ledger->payment_amount = $request->payment_amount;
  
        $payment_ledger->payment_asset_id = $request->payment_asset_id;
  
        $payment_ledger->payment_fee_id = 0;
 
        $payment_ledger->payment_status = 1;
  
        $payment_ledger->fee_summary_id = 0;
 
        $payment_ledger->transaction_type_id = $request->transaction_type_id;
 
        $payment_ledger->transaction_address = $request->transaction_address;
 
        $payment_ledger->transaction_address_url = $request->transaction_address_url;

        $payment_ledger->transaction_photo_url = $request->transaction_photo_url;

        $payment_ledger->transaction_internal_ref = $request->transaction_internal_ref;

        $payment_ledger->transaction_root = $request->transaction_root;
 
        $payment_ledger->ledger_hash = $request->ledger_hash;

        $payment_ledger->save();

        $paymentLedgerID = $payment_ledger->ledger_id;

        Session::flash('type', 'success'); 
        Session::flash('msg', 'Payment Ledger Successfully Created');
        
        if ($request->submitBtn === "Save") {
            return redirect('payment_ledger/'. $paymentLedgerID . '/edit');
        }else{
            return redirect('payment_summary');
        }
    }
}
