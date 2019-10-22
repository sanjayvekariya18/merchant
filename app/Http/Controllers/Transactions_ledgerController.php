<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\App;
use Illuminate\Http\Request;
use App\Http\Controllers\PermissionsController;
use App\Helpers\ConnectionManager;
use Amranidev\Ajaxis\Ajaxis;
use App\Http\Traits\PermissionTrait;
use App\Transactions_ledger;
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
 * Class Transactions_ledgerController.
 *
 * @author  The scaffold-interface created at 2018-02-08 04:40:12pm
 * @link  https://github.com/amranidev/scaffold-interface
 */
class Transactions_ledgerController extends PermissionsController
{
	use PermissionTrait;

	public function __construct()
	{
		parent::__construct();

        $connectionStatus = ConnectionManager::setDbConfig('Transactions_ledger');

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

	   	if($this->permissionDetails('Transactions_ledger','access')){
			$where = array();
			$permissions = $this->getPermission("Transactions_ledger");
			
			if($this->merchantId == 0){
				$where['merchant_type.merchant_root_id'] = $merchantType;
			}else{
				if($this->roleId == 4){
					$where['transactions_ledger.merchant_id'] = $this->merchantId;
				}else{
					$where['transactions_ledger.merchant_id'] = $this->merchantId;
					$where['transactions_ledger.location_id'] = $this->locationId;	
				}
			}

            $transactions_ledgers = Transactions_ledger::
					distinct()
					->select(
						'transactions_ledger.*',
						// 'transactions_ledger.ledger_id',
						
						'merchant_identity.identity_name as merchant_name',
						'location_identity.identity_name as location_name',
						
						'staff_groups.staff_group_name',
					 	'staffs.staff_name',
					 	
					 	'customer_identity.identity_name as customer_name',
					 	'customer_identity.identity_code as customer_code',
					 	
					 	// 'account_identity.identity_name as account_name',
					 	'account.account_code_long',
				 		'account.account_code_short',
					 	'identity_exchange.identity_name as exchange_name',
					 	
					 	'timezone.timezone_name',

					 	'trade_order_side_type.side_type_name',
					 	'asset_from_identity.identity_name as asset_from_name',
						'asset_from_identity.identity_code as asset_from_code',
						'asset_into_identity.identity_name as asset_into_name',
						'asset_into_identity.identity_code as asset_into_code',

						'trade_order_type.trade_order_type_name',
						'trade_status_type.trade_status_name',
						'trade_reason_type.trade_reason_type_name',
						'trade_transaction_type.trade_transaction_type_name',

						'fee_asset_identity.identity_name as fee_asset_name',
						'fee_asset_identity.identity_code as fee_asset_code'
						)
						// ->leftjoin('transactions_ledger','transactions_ledger.ledger_id','transactions_ledger.ledger_id')

						->leftjoin('merchant','merchant.merchant_id','transactions_ledger.merchant_id')
						->leftjoin('identity as merchant_identity','merchant_identity.identity_id','merchant.identity_id')

						->leftjoin('location','location.location_id','transactions_ledger.location_id')
						->leftjoin('identity as location_identity','location_identity.identity_id','location.identity_id')

						->leftjoin('staffs','staffs.staff_id','transactions_ledger.trader_id')

						->leftjoin('staff_groups','staff_groups.staff_group_id','transactions_ledger.group_id')

						->leftjoin('customers','customers.customer_id','transactions_ledger.customer_id')
						->leftjoin('identity as customer_identity','customer_identity.identity_id','customers.identity_id')

						->leftjoin('account','account.account_id','transactions_ledger.account_id')
								// ->leftjoin('identity as account_identity','account_identity.identity_id','account.identity_id')

						->leftjoin('exchange','exchange.exchange_id','transactions_ledger.exchange_id')
						->leftjoin('identity_exchange','exchange.identity_id','identity_exchange.identity_id')

						->leftjoin('timezone','timezone.timezone_id','transactions_ledger.trade_timezone')

						->leftjoin('trade_order_side_type','trade_order_side_type.side_type_id','transactions_ledger.side_type_id')

						->leftjoin('asset as asset_from','asset_from.asset_id','transactions_ledger.asset_from_id')
						->leftjoin('identity_asset as asset_from_identity','asset_from_identity.identity_id','asset_from.identity_id')

						->leftjoin('asset as asset_into','asset_into.asset_id','transactions_ledger.asset_into_id')
						->leftjoin('identity_asset as asset_into_identity','asset_into_identity.identity_id','asset_into.identity_id')
								
						->leftjoin('trade_order_type','trade_order_type.trade_order_type_id','transactions_ledger.order_type_id')

						->leftjoin('trade_status_type','trade_status_type.trade_status_id','transactions_ledger.status_type_id')

						->leftjoin('trade_reason_type','trade_reason_type.trade_reason_type_id','transactions_ledger.reason_type_id')

						->leftjoin('trade_transaction_type','trade_transaction_type.trade_transaction_type_id','transactions_ledger.transaction_type_id')


						->leftjoin('asset as fee_asset','fee_asset.asset_id','transactions_ledger.fee_asset_id')
						->leftjoin('identity_asset as fee_asset_identity','fee_asset_identity.identity_id','fee_asset.identity_id')

						->leftjoin('merchant_type_list','merchant_type_list.merchant_id','merchant.merchant_id')
						->leftjoin('merchant_type','merchant_type.merchant_type_id','merchant_type_list.merchant_type_id')

						->where(function($q) use ($where){
							foreach($where as $key => $value){
								$q->where($key, '=', $value);
							}
						})->get();

            return view('transactions_ledger.index',compact('transactions_ledgers','permissions'));
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
		if($this->permissionDetails('Transactions_ledger','add')){
			$hase_staffs = Staff::all();
			$timezones = Timezone::all();
			$sideTypes = TradeSideType::all();
			$tradeOrderTypes = TradeOrderType::all();
			$tradeStatusTypes = TradeStatusType::all();
			$tradeReasonTypes = TradeReasonType::all();
			$tradeTransactionTypes = TradeTransactionType::all();

			$accounts = Account::all();
			// $accounts = Account::
			// 	select('account.account_id','identity.identity_name') 
			// 	->leftjoin('identity','account.identity_id','identity.identity_id')
			//    ->get();

		   $exchanges = Exchange::
				select('exchange.exchange_id','identity_exchange.identity_name') 
				->leftjoin('identity_exchange','exchange.identity_id','identity_exchange.identity_id')
			   ->get();

			$assets = Asset::
				select('asset.asset_id','identity_asset.identity_name','identity_asset.identity_code')
				->leftjoin('identity_asset','asset.identity_id','identity_asset.identity_id')
			   ->get();

			return view('transactions_ledger.create',compact('hase_staffs','accounts','exchanges','timezones','sideTypes','assets','tradeOrderTypes','tradeStatusTypes','tradeReasonTypes','tradeTransactionTypes'));
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
		$transactions_ledger = new Transactions_ledger();

		$transactions_ledger->ledger_id = $request->ledger_id;

		$transactions_ledger->order_id = $request->order_id;

		$transactions_ledger->trader_id = $request->trader_id;

		$transactions_ledger->client_id = $request->client_id;

		$transactions_ledger->account_id = $request->account_id;

		$transactions_ledger->exchange_id = $request->exchange_id;
		
		$transactions_ledger->trade_timezone = $request->trade_timezone;
		
		$transactions_ledger->trade_date = (!empty($request->trade_date))?
					str_replace("-","",date('Y-m-d',strtotime($request->trade_date))) : 0;
		
		$transactions_ledger->trade_time = (!empty($request->trade_time))?
					Carbon::createFromFormat('H:i', $request->trade_time)->timestamp:0;
		
		$transactions_ledger->side_type_id = $request->side_type_id;

		$transactions_ledger->asset_from_id = $request->asset_from_id;

		$transactions_ledger->asset_from_price = $request->asset_from_price;
		
		$transactions_ledger->asset_from_quantity = $request->asset_from_quantity;
		
		$transactions_ledger->asset_into_id = $request->asset_into_id;

		$transactions_ledger->asset_into_price = $request->asset_into_price;
		
		$transactions_ledger->asset_into_quantity = $request->asset_into_quantity;
		
		$transactions_ledger->order_type_id = $request->order_type_id;
		
		$transactions_ledger->status_type_id = $request->status_type_id;

		$transactions_ledger->reason_type_id = $request->reason_type_id;

		$transactions_ledger->fee_asset_id = $request->fee_asset_id;

		$transactions_ledger->fee_amount = $request->fee_amount;

		$transactions_ledger->transaction_address = $request->transaction_address;

		$transactions_ledger->transaction_address_url = $request->transaction_address_url;

		$transactions_ledger->transaction_type_id = $request->transaction_type_id;

		$transactions_ledger->transaction_exchange = $request->transaction_exchange;

		$transactions_ledger->transaction_internal = $request->transaction_internal;

		$transactions_ledger->save();

		$transactionLedgerID = $transactions_ledger->ledger_id;

		Session::flash('type', 'success'); 
		Session::flash('msg', 'Transaction Ledger Successfully Created');
		
		if ($request->submitBtn === "Save") {
			return redirect('transactions_ledger/'. $transactionLedgerID . '/edit');
		}else{
			return redirect('transactions_ledger');
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
		if($this->permissionDetails('Transactions_ledger','manage')){
			$transactions_ledger = Transactions_ledger::findOrfail($id);

			$transactions_ledger->trade_date = ($transactions_ledger->trade_date)?
				substr_replace(substr_replace($transactions_ledger->trade_date,'/', 4, 0),'/', 7, 0):0;
			
			$transactions_ledger->trade_time = ($transactions_ledger->trade_time)?
						date('H:i',$transactions_ledger->trade_time):0;

			$hase_staffs = Staff::all();
			$timezones = Timezone::all();
			$sideTypes = TradeSideType::all();
			$tradeOrderTypes = TradeOrderType::all();
			$tradeStatusTypes = TradeStatusType::all();
			$tradeReasonTypes = TradeReasonType::all();
			$tradeTransactionTypes = TradeTransactionType::all();

			$accounts = Account::all();
			// $accounts = Account::
			// 	select('account.account_id','identity.identity_name') 
			// 	->leftjoin('identity','account.identity_id','identity.identity_id')
			//    ->get();

		   $exchanges = Exchange::
				select('exchange.exchange_id','identity_exchange.identity_name') 
				->leftjoin('identity_exchange','exchange.identity_id','identity_exchange.identity_id')
			   ->get();

			$assets = Asset::
				select('asset.asset_id','identity_asset.identity_name','identity_asset.identity_code')
				->leftjoin('identity_asset','asset.identity_id','identity_asset.identity_id')
			   ->get();
	 
			return view('transactions_ledger.edit',compact('transactions_ledger','hase_staffs','accounts','exchanges','timezones','sideTypes','assets','tradeOrderTypes','tradeStatusTypes','tradeReasonTypes','tradeTransactionTypes'));
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
		$transactions_ledger = Transactions_ledger::findOrfail($id);
		
		$transactions_ledger->ledger_id = $request->ledger_id;

		$transactions_ledger->order_id = $request->order_id;

		$transactions_ledger->trader_id = $request->trader_id;

		$transactions_ledger->client_id = $request->client_id;

		$transactions_ledger->account_id = $request->account_id;

		$transactions_ledger->exchange_id = $request->exchange_id;
		
		$transactions_ledger->trade_timezone = $request->trade_timezone;
		
		$transactions_ledger->trade_date = (!empty($request->trade_date))?
					str_replace("-","",date('Y-m-d',strtotime($request->trade_date))) : 0;
		
		$transactions_ledger->trade_time = (!empty($request->trade_time))?
					Carbon::createFromFormat('H:i', $request->trade_time)->timestamp:0;
		
		$transactions_ledger->side_type_id = $request->side_type_id;

		$transactions_ledger->asset_from_id = $request->asset_from_id;

		$transactions_ledger->asset_from_price = $request->asset_from_price;
		
		$transactions_ledger->asset_from_quantity = $request->asset_from_quantity;
		
		$transactions_ledger->asset_into_id = $request->asset_into_id;

		$transactions_ledger->asset_into_price = $request->asset_into_price;
		
		$transactions_ledger->asset_into_quantity = $request->asset_into_quantity;
		
		$transactions_ledger->order_type_id = $request->order_type_id;
		
		$transactions_ledger->status_type_id = $request->status_type_id;

		$transactions_ledger->reason_type_id = $request->reason_type_id;

		$transactions_ledger->fee_asset_id = $request->fee_asset_id;

		$transactions_ledger->fee_amount = $request->fee_amount;

		$transactions_ledger->transaction_address = $request->transaction_address;

		$transactions_ledger->transaction_address_url = $request->transaction_address_url;

		$transactions_ledger->transaction_type_id = $request->transaction_type_id;

		$transactions_ledger->transaction_exchange = $request->transaction_exchange;

		$transactions_ledger->transaction_internal = $request->transaction_internal;

		$transactions_ledger->save();

		$transactionLedgerID = $transactions_ledger->ledger_id;

		Session::flash('type', 'success'); 
		Session::flash('msg', 'Transaction Ledger Successfully Created');
		
		if ($request->submitBtn === "Save") {
			return redirect('transactions_ledger/'. $transactionLedgerID . '/edit');
		}else{
			return redirect('transactions_ledger');
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
		if($this->permissionDetails('Transactions_ledger','delete')){
			$transactions_ledger = Transactions_ledger::findOrfail($id);
			$transactions_ledger->delete();
			Session::flash('type', 'error'); 
			Session::flash('msg', 'Transaction Ledger Successfully Deleted');
			return redirect('transactions_ledger');
			
		}else{
			return Redirect::back()->with('message', 'You are not authorized to use this functionality!');
		}
	}
}
