<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\App;
use Illuminate\Http\Request;
use App\Http\Controllers\PermissionsController;
use App\Helpers\ConnectionManager;
use Amranidev\Ajaxis\Ajaxis;
use App\Http\Traits\PermissionTrait;

use App\Transaction_summary;
use App\Transaction_ledger;
use App\Staff;
use App\Account;
use App\Exchange;
use App\Timezone;
use App\TradeSideType;
use App\TradeOrderType;
use App\TradeStatusType;
use App\TradeReasonType;
use App\TradeTransactionType;
use App\Asset;
use Carbon\Carbon;
use URL;
use Session;
use DB;
use Redirect;

/**
 * Class Transaction_summaryController.
 *
 * @author  The scaffold-interface created at 2018-02-06 01:57:57pm
 * @link  https://github.com/amranidev/scaffold-interface
 */
class Transaction_summaryController extends PermissionsController
{
	use PermissionTrait;

	public function __construct()
	{
		parent::__construct();

        $connectionStatus = ConnectionManager::setDbConfig('Transaction_summary');

        if ($connectionStatus['type'] === "error") {
            Session::flash('type', $connectionStatus['type']);
            Session::flash('msg', $connectionStatus['message']);
            return Redirect::back()->send();
        }
	}

	public function index()
	{
		$merchantTypeInfo = PermissionTrait::getMerchantType();
		$merchantType = $merchantTypeInfo->merchant_type_id;

	   	 if($this->permissionDetails('Transaction_summary','access')){
			$where = array();
			$permissions = $this->getPermission("Transaction_summary");
			
			if($this->merchantId == 0){
				//$where['merchant_type.merchant_root_id'] = $merchantType;
			}else{
				if($this->roleId == 4){
					$where['transactions_summary.merchant_id'] = $this->merchantId;
				}else{
					$where['transactions_summary.merchant_id'] = $this->merchantId;
				}
			}

            $transaction_summaries = Transaction_summary::distinct()
				->select(
					'transactions_summary.*',
					'identity_merchant.identity_name as merchant_name',
										
					'group_permissions.group_name',
				 	'identity_staff.identity_name as staff_name',
				 	'account_staff_identity.identity_name as staff_account_code_long',
				 	'account_staff_identity.identity_code as staff_account_code_short',

				 	'account_customer_identity.identity_name as customer_account_code_long',
				 	'account_customer_identity.identity_code as customer_account_code_short',

				 	'identity_exchange.identity_name as exchange_name',
				 	'timezone.timezone_name',

				 	'trade_order_side_type.side_type_name',
				 	'asset_from_identity.identity_name as asset_from_name',
					'asset_from_identity.identity_code as asset_from_code',
					'asset_into_identity.identity_name as asset_into_name',
					'asset_into_identity.identity_code as asset_into_code',

					'trade_order_type.type_name',
					'trade_status_type.trade_status_name',
					'trade_reason_type.trade_reason_type_name',
					'trade_transaction_type.trade_transaction_type_name',

					'fee_asset_identity.identity_name as fee_asset_name',
					'fee_asset_identity.identity_code as fee_asset_code'
					)

					->leftjoin('merchant','merchant.merchant_id','transactions_summary.merchant_id')
					->leftjoin('identity_merchant','identity_merchant.identity_id','merchant.identity_id')

					->leftjoin('staff','staff.staff_id','transactions_summary.staff_id')
					->leftjoin('identity_staff','identity_staff.identity_id','staff.identity_id')

					->leftjoin('account as account_staff','account_staff.account_id','transactions_summary.merchant_account_id')
					->leftjoin('identity_account as account_staff_identity','account_staff_identity.identity_id','account_staff.identity_id')

					->leftjoin('group_permissions','group_permissions.group_id','transactions_summary.group_id')

					->leftjoin('account as account_customer','account_customer.account_id','transactions_summary.customer_account_id')
					->leftjoin('identity_account as account_customer_identity','account_customer_identity.identity_id','account_customer.identity_id')

					->leftjoin('exchange','exchange.exchange_id','transactions_summary.exchange_id')
					->leftjoin('identity_exchange','exchange.identity_id','identity_exchange.identity_id')

					->leftjoin('timezone','timezone.timezone_id','transactions_summary.trade_timezone')

					->leftjoin('trade_order_side_type','trade_order_side_type.side_type_id','transactions_summary.side_type_id')

					->leftjoin('asset as asset_from','asset_from.asset_id','transactions_summary.asset_from_id')
					->leftjoin('identity_asset as asset_from_identity','asset_from_identity.identity_id','asset_from.identity_id')

					->leftjoin('asset as asset_into','asset_into.asset_id','transactions_summary.asset_into_id')
					->leftjoin('identity_asset as asset_into_identity','asset_into_identity.identity_id','asset_into.identity_id')
							
					->leftjoin('trade_order_type','trade_order_type.type_id','transactions_summary.order_type_id')

					->leftjoin('trade_status_type','trade_status_type.trade_status_id','transactions_summary.status_type_id')

					->leftjoin('trade_reason_type','trade_reason_type.trade_reason_type_id','transactions_summary.reason_type_id')

					->leftjoin('trade_transaction_type','trade_transaction_type.trade_transaction_type_id','transactions_summary.transaction_type_id')

					->leftjoin('asset as fee_asset','fee_asset.asset_id','transactions_summary.fee_asset_id')
					->leftjoin('identity_asset as fee_asset_identity','fee_asset_identity.identity_id','fee_asset.identity_id')

					->leftjoin('merchant_type_list','merchant_type_list.merchant_id','merchant.merchant_id')
					->leftjoin('merchant_type','merchant_type.merchant_type_id','merchant_type_list.merchant_type_id')

					->where(function($q) use ($where){
						foreach($where as $key => $value){
							$q->where($key, '=', $value);
						}
					})->get();

            return view('transaction_summary.index',compact('transaction_summaries','permissions'));
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
		 if($this->permissionDetails('Transaction_summary','add')){

			$hase_staffs = Staff::all();
			$timezones = Timezone::all();
			$sideTypes = TradeSideType::all();
			$tradeOrderTypes = TradeOrderType::all();
			$tradeStatusTypes = TradeStatusType::all();
			$tradeReasonTypes = TradeReasonType::all();
			$tradeTransactionTypes = TradeTransactionType::all();

			$accounts = Account::
				select('account.account_id','identity.identity_name') 
				->leftjoin('identity','account.identity_id','identity.identity_id')
			   ->get();

		   $exchanges = Exchange::
				select('exchange.exchange_id','identity.identity_name') 
				->leftjoin('identity','exchange.identity_id','identity.identity_id')
			   ->get();

			$assets = Asset::
				select('asset.asset_id','identity.identity_name') 
				->distinct()
				->leftjoin('identity','asset.identity_id','identity.identity_id')
			   ->get();
			
			return view('transaction_summary.create',compact('hase_staffs','accounts','exchanges','timezones','sideTypes','assets','tradeOrderTypes','tradeStatusTypes','tradeReasonTypes','tradeTransactionTypes'));
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
		$transaction_summary = new Transaction_summary();
		
		$transaction_summary->staff_id = $request->staff_id;

		$transaction_summary->account_id = $request->account_id;

		$transaction_summary->exchange_id = $request->exchange_id;
	
		$transaction_summary->trade_timezone = $request->trade_timezone;

		$transaction_summary->trade_date = (!empty($request->trade_date))?
					str_replace("-","",date('Y-m-d',strtotime($request->trade_date))) : 0;

		$transaction_summary->trade_time = (!empty($request->trade_time))?
					Carbon::createFromFormat('H:i', $request->trade_time)->timestamp:0;

		$transaction_summary->side_type_id = $request->side_type_id;

		$transaction_summary->asset_trade_id = $request->asset_trade_id;

		$transaction_summary->asset_base_id = $request->asset_base_id;

		$transaction_summary->quantity_executed = $request->quantity_executed;

		$transaction_summary->quantity_asset_id = $request->quantity_asset_id;

		$transaction_summary->type_id = $request->type_id;

		$transaction_summary->price = $request->price;

		$transaction_summary->trade_status_type_id = $request->trade_status_type_id;

		$transaction_summary->trade_reason_type_id = $request->trade_reason_type_id;

		$transaction_summary->trade_transaction_type_id = $request->trade_transaction_type_id;

		$transaction_summary->transaction_fee = $request->transaction_fee;

		$transaction_summary->transaction_fee_asset = $request->transaction_fee_asset;
	
		$transaction_summary->transaction_exchange = $request->transaction_exchange;
	
		$transaction_summary->transaction_internal = $request->transaction_internal;

		$transaction_summary->save();
		$transactionSummaryID = $transaction_summary->transaction_summary_id;

		Session::flash('type', 'success');
		Session::flash('msg', 'Merchant Successfully Created');
		
		if ($request->submitBtn === "Save") {
			return redirect('transaction_summary/'. $transactionSummaryID . '/edit');
		}else{
			return redirect('transaction_summary');
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
		 if($this->permissionDetails('Transaction_summary','manage')){
			
			$transaction_summary = Transaction_summary::findOrfail($id);

			$transaction_summary->trade_date = ($transaction_summary->trade_date)?
				substr_replace(substr_replace($transaction_summary->trade_date,'/', 4, 0),'/', 7, 0):0;
			
			$transaction_summary->trade_time = ($transaction_summary->trade_time)?
						date('H:i',$transaction_summary->trade_time):0;

			$hase_staffs = Staff::all();
			$timezones = Timezone::all();
			$sideTypes = TradeSideType::all();
			$tradeOrderTypes = TradeOrderType::all();
			$tradeStatusTypes = TradeStatusType::all();
			$tradeReasonTypes = TradeReasonType::all();
			$tradeTransactionTypes = TradeTransactionType::all();

			$accounts = Account::
				select('account.account_id','identity.identity_name') 
				->leftjoin('identity','account.identity_id','identity.identity_id')
			   ->get();

		    $exchanges = Exchange::
				select('exchange.exchange_id','identity.identity_name') 
				->leftjoin('identity','exchange.identity_id','identity.identity_id')
			   ->get();

			$assets = Asset::
				select('asset.asset_id','identity.identity_name') 
				->distinct()
				->leftjoin('identity','asset.identity_id','identity.identity_id')
			   ->get();
	 
			return view('transaction_summary.edit',compact('transaction_summary','hase_staffs','accounts','exchanges','timezones','sideTypes','assets','tradeOrderTypes','tradeStatusTypes','tradeReasonTypes','tradeTransactionTypes'));

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
		$transaction_summary = Transaction_summary::findOrfail($id);

		$transaction_summary->transaction_summary_id = $request->transaction_summary_id;
		
		$transaction_summary->staff_id = $request->staff_id;
		
		$transaction_summary->account_id = $request->account_id;
		
		$transaction_summary->exchange_id = $request->exchange_id;
		
		$transaction_summary->trade_timezone = $request->trade_timezone;
		
		$transaction_summary->trade_date = (!empty($request->trade_date))?
					str_replace("-","",date('Y-m-d',strtotime($request->trade_date))) : 0;

		$transaction_summary->trade_time = (!empty($request->trade_time))?
					Carbon::createFromFormat('H:i', $request->trade_time)->timestamp:0;
		
		$transaction_summary->side_type_id = $request->side_type_id;
		
		$transaction_summary->asset_trade_id = $request->asset_trade_id;
		
		$transaction_summary->asset_base_id = $request->asset_base_id;
		
		$transaction_summary->quantity_executed = $request->quantity_executed;
		
		$transaction_summary->quantity_asset_id = $request->quantity_asset_id;
		
		$transaction_summary->type_id = $request->type_id;
		
		$transaction_summary->price = $request->price;
		
		$transaction_summary->trade_status_type_id = $request->trade_status_type_id;
		
		$transaction_summary->trade_reason_type_id = $request->trade_reason_type_id;
		
		$transaction_summary->trade_transaction_type_id = $request->trade_transaction_type_id;
		
		$transaction_summary->transaction_fee = $request->transaction_fee;
		
		$transaction_summary->transaction_fee_asset = $request->transaction_fee_asset;
		
		$transaction_summary->transaction_exchange = $request->transaction_exchange;
		
		$transaction_summary->transaction_internal = $request->transaction_internal;
		
		$transaction_summary->save();
		$transactionSummaryID = $transaction_summary->transaction_summary_id;
		
		Session::flash('type', 'success'); 
		Session::flash('msg', 'Transaction Summary Successfully Created');
		
		if ($request->submitBtn === "Save") {
			return redirect('transaction_summary/'. $transactionSummaryID . '/edit');
		}else{
			return redirect('transaction_summary');
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
		 if($this->permissionDetails('Transaction_summary','delete')){
			$transaction_summary = Transaction_summary::findOrfail($id);
			$transaction_summary->delete();
			Session::flash('type', 'error'); 
			Session::flash('msg', 'Transaction Summary Successfully Deleted');
			return redirect('Transaction_summary');
		 }else{
		 	return Redirect::back()->with('message', 'You are not authorized to use this functionality!');
		 }
	}

	public function summaryIndex()
    {
    	if($this->permissionDetails('Transaction_summary','access')){
        	return view('transaction_summary.summary-index');
        }else{
            return Redirect::back()->with('message', 'You are not authorized to use this functionality!');
        }
    }

    public function RetrieveTransactionSummaryList(Request $request) {
        $merchantTypeInfo = PermissionTrait::getMerchantType();
		$merchantType = $merchantTypeInfo->merchant_type_id;

	   	 if($this->permissionDetails('Transaction_summary','access')){
			$where = array();
			$permissions = $this->getPermission("Transaction_summary");
			
			if($this->merchantId == 0){
				//$where['merchant_type.merchant_root_id'] = $merchantType;
			}else{
				if($this->roleId == 4){
					$where['transactions_summary.merchant_id'] = $this->merchantId;
				}else{
					$where['transactions_summary.merchant_id'] = $this->merchantId;
				}
			}

            $transaction_summaries = Transaction_summary::distinct()
				->select(
					'transactions_summary.*',
					'identity_merchant.identity_name as merchant_name',
											
					'group_permissions.group_name',
				 	'identity_staff.identity_name as staff_name',
				 	'account_staff_identity.identity_name as staff_account_code_long',
				 	'account_staff_identity.identity_code as staff_account_code_short',

				 	'account_customer_identity.identity_name as customer_account_code_long',
				 	'account_customer_identity.identity_code as customer_account_code_short',

				 	'identity_exchange.identity_name as exchange_name',
				 	'timezone.timezone_name',

				 	'trade_order_side_type.side_type_name',
				 	'asset_from_identity.identity_name as asset_from_name',
					'asset_from_identity.identity_code as asset_from_code',
					'asset_into_identity.identity_name as asset_into_name',
					'asset_into_identity.identity_code as asset_into_code',

					'trade_order_type.type_name',
					'trade_status_type.trade_status_name',
					'trade_reason_type.trade_reason_type_name',
					'trade_transaction_type.trade_transaction_type_name',

					'fee_asset_identity.identity_name as fee_asset_name',
					'fee_asset_identity.identity_code as fee_asset_code'
					)
					->leftjoin('merchant','merchant.merchant_id','transactions_summary.merchant_id')
					->leftjoin('identity_merchant','identity_merchant.identity_id','merchant.identity_id')

					->leftjoin('staff','staff.staff_id','transactions_summary.staff_id')
					->leftjoin('identity_staff','identity_staff.identity_id','staff.identity_id')

					->leftjoin('account as account_staff','account_staff.account_id','transactions_summary.merchant_account_id')
					->leftjoin('identity_account as account_staff_identity','account_staff_identity.identity_id','account_staff.identity_id')

					->leftjoin('group_permissions','group_permissions.group_id','transactions_summary.group_id')

					->leftjoin('account as account_customer','account_customer.account_id','transactions_summary.customer_account_id')
					->leftjoin('identity_account as account_customer_identity','account_customer_identity.identity_id','account_customer.identity_id')

					->leftjoin('exchange','exchange.exchange_id','transactions_summary.exchange_id')
					->leftjoin('identity_exchange','exchange.identity_id','identity_exchange.identity_id')

					->leftjoin('timezone','timezone.timezone_id','transactions_summary.trade_timezone')

					->leftjoin('trade_order_side_type','trade_order_side_type.side_type_id','transactions_summary.side_type_id')

					->leftjoin('asset as asset_from','asset_from.asset_id','transactions_summary.asset_from_id')
					->leftjoin('identity_asset as asset_from_identity','asset_from_identity.identity_id','asset_from.identity_id')

					->leftjoin('asset as asset_into','asset_into.asset_id','transactions_summary.asset_into_id')
					->leftjoin('identity_asset as asset_into_identity','asset_into_identity.identity_id','asset_into.identity_id')
							
					->leftjoin('trade_order_type','trade_order_type.type_id','transactions_summary.order_type_id')

					->leftjoin('trade_status_type','trade_status_type.trade_status_id','transactions_summary.status_type_id')

					->leftjoin('trade_reason_type','trade_reason_type.trade_reason_type_id','transactions_summary.reason_type_id')

					->leftjoin('trade_transaction_type','trade_transaction_type.trade_transaction_type_id','transactions_summary.transaction_type_id')

					->leftjoin('asset as fee_asset','fee_asset.asset_id','transactions_summary.fee_asset_id')
					->leftjoin('identity_asset as fee_asset_identity','fee_asset_identity.identity_id','fee_asset.identity_id')

					->leftjoin('merchant_type_list','merchant_type_list.merchant_id','merchant.merchant_id')
					->leftjoin('merchant_type','merchant_type.merchant_type_id','merchant_type_list.merchant_type_id')

					->where(function($q) use ($where){
						foreach($where as $key => $value){
							$q->where($key, '=', $value);
						}
					})
					->offset($request->skip)
            		->limit($request->take)
            		->get();

            $total_records = Transaction_summary::
            		distinct()
            		->select(
					'transactions_summary.*',
					'identity_merchant.identity_name as merchant_name',
											
					'group_permissions.group_name',
				 	'identity_staff.identity_name as staff_name',
				 	'account_staff_identity.identity_name as staff_account_code_long',
				 	'account_staff_identity.identity_code as staff_account_code_short',

				 	'account_customer_identity.identity_name as customer_account_code_long',
				 	'account_customer_identity.identity_code as customer_account_code_short',

				 	'identity_exchange.identity_name as exchange_name',
				 	'timezone.timezone_name',

				 	'trade_order_side_type.side_type_name',
				 	'asset_from_identity.identity_name as asset_from_name',
					'asset_from_identity.identity_code as asset_from_code',
					'asset_into_identity.identity_name as asset_into_name',
					'asset_into_identity.identity_code as asset_into_code',

					'trade_order_type.type_name',
					'trade_status_type.trade_status_name',
					'trade_reason_type.trade_reason_type_name',
					'trade_transaction_type.trade_transaction_type_name',

					'fee_asset_identity.identity_name as fee_asset_name',
					'fee_asset_identity.identity_code as fee_asset_code'
					)
					->leftjoin('merchant','merchant.merchant_id','transactions_summary.merchant_id')
					->leftjoin('identity_merchant','identity_merchant.identity_id','merchant.identity_id')

					->leftjoin('staff','staff.staff_id','transactions_summary.staff_id')
					->leftjoin('identity_staff','identity_staff.identity_id','staff.identity_id')

					->leftjoin('account as account_staff','account_staff.account_id','transactions_summary.merchant_account_id')
					->leftjoin('identity_account as account_staff_identity','account_staff_identity.identity_id','account_staff.identity_id')

					->leftjoin('group_permissions','group_permissions.group_id','transactions_summary.group_id')

					->leftjoin('account as account_customer','account_customer.account_id','transactions_summary.customer_account_id')
					->leftjoin('identity_account as account_customer_identity','account_customer_identity.identity_id','account_customer.identity_id')

					->leftjoin('exchange','exchange.exchange_id','transactions_summary.exchange_id')
					->leftjoin('identity_exchange','exchange.identity_id','identity_exchange.identity_id')

					->leftjoin('timezone','timezone.timezone_id','transactions_summary.trade_timezone')

					->leftjoin('trade_order_side_type','trade_order_side_type.side_type_id','transactions_summary.side_type_id')

					->leftjoin('asset as asset_from','asset_from.asset_id','transactions_summary.asset_from_id')
					->leftjoin('identity_asset as asset_from_identity','asset_from_identity.identity_id','asset_from.identity_id')

					->leftjoin('asset as asset_into','asset_into.asset_id','transactions_summary.asset_into_id')
					->leftjoin('identity_asset as asset_into_identity','asset_into_identity.identity_id','asset_into.identity_id')
							
					->leftjoin('trade_order_type','trade_order_type.type_id','transactions_summary.order_type_id')

					->leftjoin('trade_status_type','trade_status_type.trade_status_id','transactions_summary.status_type_id')

					->leftjoin('trade_reason_type','trade_reason_type.trade_reason_type_id','transactions_summary.reason_type_id')

					->leftjoin('trade_transaction_type','trade_transaction_type.trade_transaction_type_id','transactions_summary.transaction_type_id')

					->leftjoin('asset as fee_asset','fee_asset.asset_id','transactions_summary.fee_asset_id')
					->leftjoin('identity_asset as fee_asset_identity','fee_asset_identity.identity_id','fee_asset.identity_id')

					->leftjoin('merchant_type_list','merchant_type_list.merchant_id','merchant.merchant_id')
					->leftjoin('merchant_type','merchant_type.merchant_type_id','merchant_type_list.merchant_type_id')

					->where(function($q) use ($where){
						foreach($where as $key => $value){
							$q->where($key, '=', $value);
						}
					})
            		->get()->count();		

            foreach ($transaction_summaries as $key=>$transaction_summary){
            	$transaction_summaries[$key]->trade_date=$this->convertIntoDate($transaction_summary->trade_date);
                $transaction_summaries[$key]->trade_time=date('H:i',$transaction_summary->trade_time); 

                $transaction_ledger = Transaction_ledger::select(DB::Raw('count(ledger_id) as ledgerCount'))->where('summary_id',$transaction_summary->summary_id)->groupBy('summary_id')->get()->first();
	            if($transaction_ledger)
	            {
	                $transaction_summaries[$key]->ledger_count = $transaction_ledger->ledgerCount;
	            } else {
	                $transaction_summaries[$key]->ledger_count = 0;
	            }
            }

            $transaction_summaries_data['transaction_summaries']=$transaction_summaries;
            $transaction_summaries_data['total']=$total_records;

            return json_encode($transaction_summaries_data);

		 }else{
		 	return Redirect::back()->with('message', 'You are not authorized to use this functionality!');
		 }
    }

    public function RetrieveTransactionLedgerList($summaryId) { 
        $merchantTypeInfo = PermissionTrait::getMerchantType();
		$merchantType = $merchantTypeInfo->merchant_type_id;

	   	 if($this->permissionDetails('Transaction_summary','access')){
			$where = array();
			$where['transactions_summary.summary_id'] = $summaryId;

            $transaction_ledgers = Transaction_ledger::distinct()
				->select(
					'transactions_ledger.*',
										
					'group_permissions.group_name',
				 	'identity_staff.identity_name as staff_name',
				 	'account_staff_identity.identity_name as staff_account_code_long',
				 	'account_staff_identity.identity_code as staff_account_code_short',

				 	'account_customer_identity.identity_name as customer_account_code_long',
				 	'account_customer_identity.identity_code as customer_account_code_short',

				 	'identity_exchange.identity_name as exchange_name',
				 	'timezone.timezone_name',

				 	'trade_order_side_type.side_type_name',
				 	'asset_from_identity.identity_name as asset_from_name',
					'asset_from_identity.identity_code as asset_from_code',
					'asset_into_identity.identity_name as asset_into_name',
					'asset_into_identity.identity_code as asset_into_code',

					'trade_order_type.type_name',
					'trade_status_type.trade_status_name',
					'trade_reason_type.trade_reason_type_name',
					'trade_transaction_type.trade_transaction_type_name',

					'fee_asset_identity.identity_name as fee_asset_name',
					'fee_asset_identity.identity_code as fee_asset_code'
					)
					->leftjoin('transactions_summary','transactions_summary.summary_id','transactions_ledger.summary_id')

					->leftjoin('staff','staff.staff_id','transactions_ledger.staff_id')
					->leftjoin('identity_staff','identity_staff.identity_id','staff.identity_id')

					->leftjoin('account as account_staff','account_staff.account_id','transactions_ledger.merchant_account_id')
					->leftjoin('identity_account as account_staff_identity','account_staff_identity.identity_id','account_staff.identity_id')

					->leftjoin('group_permissions','group_permissions.group_id','transactions_ledger.group_id')

					->leftjoin('account as account_customer','account_customer.account_id','transactions_ledger.customer_account_id')
					->leftjoin('identity_account as account_customer_identity','account_customer_identity.identity_id','account_customer.identity_id')

					->leftjoin('exchange','exchange.exchange_id','transactions_ledger.exchange_id')
					->leftjoin('identity_exchange','exchange.identity_id','identity_exchange.identity_id')

					->leftjoin('timezone','timezone.timezone_id','transactions_ledger.trade_timezone')

					->leftjoin('trade_order_side_type','trade_order_side_type.side_type_id','transactions_ledger.side_type_id')

					->leftjoin('asset as asset_from','asset_from.asset_id','transactions_ledger.asset_from_id')
					->leftjoin('identity_asset as asset_from_identity','asset_from_identity.identity_id','asset_from.identity_id')

					->leftjoin('asset as asset_into','asset_into.asset_id','transactions_ledger.asset_into_id')
					->leftjoin('identity_asset as asset_into_identity','asset_into_identity.identity_id','asset_into.identity_id')
							
					->leftjoin('trade_order_type','trade_order_type.type_id','transactions_ledger.order_type_id')

					->leftjoin('trade_status_type','trade_status_type.trade_status_id','transactions_ledger.status_type_id')

					->leftjoin('trade_reason_type','trade_reason_type.trade_reason_type_id','transactions_ledger.reason_type_id')

					->leftjoin('trade_transaction_type','trade_transaction_type.trade_transaction_type_id','transactions_ledger.transaction_type_id')

					->leftjoin('asset as fee_asset','fee_asset.asset_id','transactions_ledger.fee_asset_id')
					->leftjoin('identity_asset as fee_asset_identity','fee_asset_identity.identity_id','fee_asset.identity_id')

					->where(function($q) use ($where){
						foreach($where as $key => $value){
							$q->where($key, '=', $value);
						}
					})->get();

            foreach ($transaction_ledgers as $key=>$transaction_ledger){
            	$transaction_ledgers[$key]->trade_date=$this->convertIntoDate($transaction_ledger->trade_date);
                $transaction_ledgers[$key]->trade_time=date('H:i',$transaction_ledger->trade_time); 
            }
            return $transaction_ledgers->toArray();

		 }else{
		 	return Redirect::back()->with('message', 'You are not authorized to use this functionality!');
		 }
    }
}
