<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\App;
use Illuminate\Http\Request;
use App\Http\Controllers\PermissionsController;
use App\Http\Traits\PermissionTrait;
use App\Helpers\ConnectionManager;
use App\Asset;
use App\Account;
use App\Wallet;
use App\Identity_account;
use App\Merchant;
use App\Customer;
use App\Merchant_account_list;
use App\Customer_account_list;
use App\Merchant_customer_list;
use App\Account_wallet;
use App\Wallet_confirm_list;

use URL;
use Session;
use DB;
use Redirect;
use Auth;
use DateTime;
use Config;
use Carbon\Carbon;

class AccountController extends PermissionsController
{
	use PermissionTrait;

	public function __construct()
	{
		parent::__construct();

        $connectionStatus = ConnectionManager::setDbConfig('Account');
        if (strcmp($connectionStatus['type'],"error") == 0) {
            Session::flash('type', $connectionStatus['type']);
            Session::flash('msg', $connectionStatus['message']);
            return Redirect::back()->send();
        }
	}

	public function accounts(){

		return view('account.index');
	}

	public function getAccountMerchants(Request $request)
	{
		$where = array();

		if($this->merchantId == 0){
			$where[] = array(
				'key' => "merchant.merchant_id",
				'operator' => '>',
				'val' => $this->merchantId
			);
				//$where['merchant_type.merchant_root_id'] = $merchantType;
		}else{
			if($this->roleId == 4){
				$where[] = array(
					'key' => "merchant.merchant_id",
					'operator' => '=',
					'val' => $this->merchantId
				);
			}else{
				$where[] = array(
					'key' => "merchant.merchant_id",
					'operator' => '=',
					'val' => $this->merchantId
				);
				/*$where['staff.location_id'] = $this->locationId; */
			}
		}

		$merchants = Merchant::
		select(
			'merchant.*',
			'identity_merchant.identity_name as merchant_name',
			'identity_merchant.identity_code as merchant_code'
		)
		->join('identity_merchant','identity_merchant.identity_id','merchant.identity_id')
		->where('merchant_type_id',$request->merchant_type_id)
		->where(function($q) use ($where){
			foreach($where as $key => $value){
				$q->where($value['key'], $value['operator'], $value['val']);
			}
		})->get();
		return json_encode($merchants);
	}

	public function getAccountAssets($flag)
	{
		return PermissionTrait::getAssets($flag);
	}

	public function getReferrerAccounts($merchantId,$customerId)
	{
		$accounts = Account::select(
							'account.*',
							'identity_account.identity_name as account_name',
							'identity_account.identity_code as account_code'
							)
					->join('identity_account','identity_account.identity_id','account.identity_id')
					->join('customer_account_list','customer_account_list.account_id','account.account_id')
					->where('customer_account_list.merchant_id',$merchantId)
					->where('customer_account_list.customer_id','!=',$customerId)
					->orWhere('customer_account_list.merchant_id',0)
					->orderby('customer_account_list.merchant_id')
					->get();
		return $accounts;	
	}

	public function merchant_account_list(Request $request)
	{
		$merchant_account_list=Account::
			distinct()
			->select(
					'account.*',
					'merchant_account_list.*',
					'identity_merchant.identity_name as merchant_name',
					'identity_merchant.identity_code as merchant_code',
					
					'identity_account.identity_name as account_name',
					'identity_account.identity_name as account_code',
					'identity_account.identity_description as account_description',

					'account.account_code_long','account.referrer_id',
					'account.asset_quantity','account.asset_price',

					'identity_asset.identity_code as asset_code',
					'identity_asset.identity_name as asset_name',

					'settlement_identity.identity_name as account_settlement_name',
					'settlement_identity.identity_code as account_settlement_code'

				)
			->join('identity_account','identity_account.identity_id','account.identity_id')
			->join('merchant_account_list','merchant_account_list.staff_account_id','account.account_id')

			->join('merchant','merchant.merchant_id','merchant_account_list.merchant_id')
			->join('identity_merchant','identity_merchant.identity_id','merchant.identity_id')

			->join('asset','asset.asset_id','merchant_account_list.asset_id')
			->join('identity_asset','identity_asset.identity_id','asset.identity_id')

			->join('asset as settlement','settlement.asset_id','account.account_settlement')
			->join('identity_asset as settlement_identity','settlement_identity.identity_id','settlement.identity_id')

			->where('merchant_account_list.merchant_id',$request->merchant_id)
			->offset($request->skip)
            ->limit($request->take)
			->get();

		$total_records = Account::
			distinct()
			->join('identity_account','identity_account.identity_id','account.identity_id')
			->join('merchant_account_list','merchant_account_list.staff_account_id','account.account_id')

			->join('merchant','merchant.merchant_id','merchant_account_list.merchant_id')
			->join('identity_merchant','identity_merchant.identity_id','merchant.identity_id')

			->join('asset','asset.asset_id','merchant_account_list.asset_id')
			->join('identity_asset','identity_asset.identity_id','asset.identity_id')

			->join('asset as settlement','settlement.asset_id','account.account_settlement')
			->join('identity_asset as settlement_identity','settlement_identity.identity_id','settlement.identity_id')
			->where('merchant_account_list.merchant_id',$request->merchant_id)
			->count();	

		foreach ($merchant_account_list as $key => $account) {
			$totalWallet = Account_wallet::where('account_id',$account->account_id)->count();
			$merchant_account_list[$key]->totalWallet = $totalWallet;
		}

		$merchant_account_list_data['merchant_account_list'] = $merchant_account_list;
		$merchant_account_list_data['total'] = $total_records;

		return json_encode($merchant_account_list_data);
	}

	public function customer_account_list(Request $request)
	{
		$where = array();
		if(isset($request->customer_id)){
				$where['customer_account_list.customer_id'] = $request->customer_id;
		}else if(isset($request->merchant_id)){
			$where['customer_account_list.merchant_id'] = $request->merchant_id;
		}
		
		$customer_account_list=Account::
			distinct()
			->select(
					'account.*',
					'customer_account_list.*',

					'identity_merchant.identity_name as merchant_name',
					'identity_merchant.identity_code as merchant_code',
					
					'identity_customer.identity_name as customer_name',
					'identity_customer.identity_code as customer_code',

					'identity_account.identity_name as account_name',
					'identity_account.identity_name as account_code',
					'identity_account.identity_description as account_description',

					'account.account_code_long','account.referrer_id',
					'account.asset_quantity','account.asset_price',

					'identity_asset.identity_code as asset_code',
					'identity_asset.identity_name as asset_name',

					'settlement_identity.identity_name as account_settlement_name',
					'settlement_identity.identity_code as account_settlement_code'

				)
			->join('identity_account','identity_account.identity_id','account.identity_id')

			->join('customer_account_list','customer_account_list.account_id','account.account_id')

			->join('merchant','merchant.merchant_id','customer_account_list.merchant_id')
			->join('identity_merchant','identity_merchant.identity_id','merchant.identity_id')

			->join('customers','customers.customer_id','customer_account_list.customer_id')
			->join('identity_customer','identity_customer.identity_id','customers.identity_id')

			->join('asset','asset.asset_id','customer_account_list.asset_id')
			->join('identity_asset','identity_asset.identity_id','asset.identity_id')

			->join('asset as settlement','settlement.asset_id','account.account_settlement')
			->join('identity_asset as settlement_identity','settlement_identity.identity_id','settlement.identity_id')

			->where(function($q) use ($where){
				foreach($where as $key => $value){
					$q->where($key, '=', $value);
				}
			})
			->offset($request->skip)
            ->limit($request->take)
			->get();

			foreach ($customer_account_list as $key => $account) {
				$referAccount = $this->getAccountInfo($account->referrer_id);
				$customer_account_list[$key]->referrer_account_code_long = $referAccount->account_code_long;
				$customer_account_list[$key]->referrer_account_name = $referAccount->account_name;

				$totalWallet = Account_wallet::where('account_id',$account->account_id)->count();
				$customer_account_list[$key]->totalWallet = $totalWallet;
			}

			$total_records = Account::
			distinct()
			->join('identity_account','identity_account.identity_id','account.identity_id')

			->join('customer_account_list','customer_account_list.account_id','account.account_id')

			->join('merchant','merchant.merchant_id','customer_account_list.merchant_id')
			->join('identity_merchant','identity_merchant.identity_id','merchant.identity_id')

			->join('customers','customers.customer_id','customer_account_list.customer_id')
			->join('identity_customer','identity_customer.identity_id','customers.identity_id')

			->join('asset','asset.asset_id','customer_account_list.asset_id')
			->join('identity_asset','identity_asset.identity_id','asset.identity_id')

			->join('asset as settlement','settlement.asset_id','account.account_settlement')
			->join('identity_asset as settlement_identity','settlement_identity.identity_id','settlement.identity_id')

			->where(function($q) use ($where){
				foreach($where as $key => $value){
					$q->where($key, '=', $value);
				}
			})
			->count();

			$customer_account_list_data['customer_account_list'] = $customer_account_list;
			$customer_account_list_data['total'] = $total_records;

		return json_encode($customer_account_list_data);
	}

	public function updateList(Request $request)
	{
		$identityUpdateColumn = array("identity_name","identity_description");
		$accountUpdateColumn = array("account_code_long","fee_percentage","account_settlement","credit");
		$listUpdateColumn = array("asset_id",'priority','enable');

		$key = $request->key;
		$value = $request->value;

		if(in_array($key, $accountUpdateColumn)){
			$accountObj = Account::findOrFail($request->account_id);
		}

		if(in_array($key, $listUpdateColumn)){
			$account_list = Merchant_account_list::where('staff_account_id',$request->account_id)->get()->first();
			$accountObj = Merchant_account_list::findOrFail($account_list->list_id);
		}

		if(in_array($key, $identityUpdateColumn)){
			$account = Account::findOrFail($request->account_id);
			$accountObj = Identity_account::findOrFail($account->identity_id);	
		}

		$accountObj->$key = $value;
		$accountObj->save();
	}

	public function updateCustomerList(Request $request)
	{
		$identityUpdateColumn = array("identity_name","identity_description");
		$accountUpdateColumn = array("account_code_long","referrer_id","referrer_fee","account_settlement","fee_percentage","credit");
		$listUpdateColumn = array('asset_id','priority','status');

		$key = $request->key;
		$value = $request->value;

		if(in_array($key, $accountUpdateColumn)){
			$accountObj = Account::findOrFail($request->account_id);
		}

		if(in_array($key, $listUpdateColumn)){
			$account_list = Customer_account_list::where('account_id',$request->account_id)->get()->first();
			$accountObj = Customer_account_list::findOrFail($account_list->list_id);
		}

		if(in_array($key, $identityUpdateColumn)){
			$account = Account::findOrFail($request->account_id);
			$accountObj = Identity_account::findOrFail($account->identity_id);	
		}

		$accountObj->$key = $value;
		$accountObj->save();
	}

	public function createMerchantAccount(Request $request)
	{

		if(isset($request->asset_id) && count($request->asset_id)){

			foreach ($request->asset_id as $key => $assetId) {
				
				$merchantAccountLists = $this->getMerchantAccountLists($request->merchant_id,$assetId);
				$countIndex = count($merchantAccountLists);
				
				DB::beginTransaction();

				try {
					$assetInfo = $this->getAssetInfo($assetId);
					$merchantInfo = $this->getMerchantInfo($request->merchant_id);
					$identity_account = new Identity_account;
					$identity_account->identity_table_id = 2;
					
					$identity_account->identity_code = $merchantInfo->merchant_code."-".strtolower($assetInfo->asset_code);
					$identity_account->identity_code = ($countIndex != 0) ?
										$identity_account->identity_code.$countIndex:
										$identity_account->identity_code;

					$identity_account->identity_name = $merchantInfo->merchant_code." ".$assetInfo->asset_code;
					$identity_account->identity_name = ($countIndex != 0) ?
										$identity_account->identity_name.$countIndex:
										$identity_account->identity_name;

					$identity_account->identity_description = $merchantInfo->merchant_code." ".$assetInfo->asset_code;
					$identity_account->identity_description = ($countIndex != 0) ?
										$identity_account->identity_description.$countIndex." Account":
										$identity_account->identity_description." Account";

					$identity_account->identity_type_id = 11;
					$identity_account->save();
					
					$identityId = $identity_account->identity_id;

					$account = new Account;
					$account->identity_id = $identityId;
					$account->account_type_id = 4;
					$account->account_settlement = $request->account_settlement;
					$account->credit = $request->credit;

					$account->account_code_long = $merchantInfo->merchant_code."-".$assetInfo->asset_code;
					$account->account_code_long = ($countIndex != 0) ?
							strtolower($account->account_code_long.$countIndex):
							strtolower($account->account_code_long);

					$account->referrer_id = 0;
					$account->referrer_fee = 0;
					$account->asset_quantity = 0;
					$account->asset_price = 0;
					$account->save();
					
					$accountId = $account->account_id;

					$merchant_account_list = new Merchant_account_list;
					$merchant_account_list->merchant_id=$request->merchant_id;
					$merchant_account_list->staff_account_id=$accountId;
					$merchant_account_list->asset_id=$assetId;
					$merchant_account_list->save();

					// Commit the queries!
					DB::commit();	
				} catch (Exception $e) {
					DB::rollback();
				}
			}
		}
	}

	public function createCustomerAccount(Request $request)
	{
		// echo "<pre>";
		// print_r($request->toarray());
		// die;

		if(isset($request->asset_id) && count($request->asset_id)){

			foreach ($request->asset_id as $key => $assetId) {

				$customerAccountLists = $this->getCustomerAccountLists($request->customer_id,$assetId);
				$countIndex = count($customerAccountLists);
				
				DB::beginTransaction();

				try {
					$assetInfo = $this->getAssetInfo($assetId);
					$merchantInfo = $this->getMerchantInfo($request->merchant_id);
					$customerInfo = $this->getCustomerInfo($request->customer_id);
					
					$identity_account = new Identity_account;
					$identity_account->identity_table_id = 2;

					$identity_account->identity_code = $customerInfo->customer_code."-".strtolower($assetInfo->asset_code);
					$identity_account->identity_code = ($countIndex != 0) ?
										$identity_account->identity_code.$countIndex:
										$identity_account->identity_code;
					
					$identity_account->identity_name = $customerInfo->customer_code." ".$assetInfo->asset_code;
					$identity_account->identity_name = ($countIndex != 0) ?
										$identity_account->identity_name.$countIndex:
										$identity_account->identity_name;

					$identity_account->identity_description = $customerInfo->customer_code." ".$assetInfo->asset_code;
					$identity_account->identity_description = ($countIndex != 0) ?
										$identity_account->identity_description.$countIndex." Account":
										$identity_account->identity_description." Account";

					$identity_account->identity_type_id = 11;

					$identity_account->save();
					
					$identityId = $identity_account->identity_id;

					$account = new Account;
					$account->identity_id = $identityId;
					$account->account_type_id = 4;
					$account->account_settlement = $request->account_settlement;
					$account->fee_percentage = $request->fee_percentage;

					$account->account_code_long = $customerInfo->customer_code."-".$assetInfo->asset_code;
					$account->account_code_long = ($countIndex != 0) ?
							strtolower($account->account_code_long.$countIndex):
							strtolower($account->account_code_long);

					$account->referrer_id = $request->referrer_id;
					$account->referrer_fee = isset($request->referrer_fee)?
												$request->referrer_fee : 0;

					$account->credit = $request->credit;
					$account->asset_quantity = 0;
					$account->asset_price = 0;
					$account->save();
					
					$accountId = $account->account_id;

					$customer_account_list = new Customer_account_list;
					$customer_account_list->merchant_id=$request->merchant_id;
					$customer_account_list->customer_id=$request->customer_id;
					$customer_account_list->account_id=$accountId;
					$customer_account_list->asset_id=$assetId;
					$customer_account_list->save();	
					$countIndex++;
					// Commit the queries!
					DB::commit();	
				} catch (Exception $e) {
					DB::rollback();
				}
			}
		}
	}

	public function filterAssets(Request $request)
	{
		$merchantId = $request->merchant_id;
		$customerId = $request->customer_id;
		$filterType = $request->filterType;

		switch ($filterType) {
			case 0:
			case 2:
				$filterAssets=Merchant_account_list::
				distinct()
				->select(
						'merchant_account_list.*',
						'identity_asset.identity_code as asset_code',
						'identity_asset.identity_name as asset_name'
					)
				->join('asset','asset.asset_id','merchant_account_list.asset_id')
				->join('identity_asset','identity_asset.identity_id','asset.identity_id')
				->groupBy('merchant_account_list.asset_id')
				->where('merchant_account_list.merchant_id',$merchantId)
				->get();
				break;
			case 1:
				$filterAssets=Merchant_account_list::
				distinct()
				->select(
						'merchant_account_list.*',
						'identity_asset.identity_code as asset_code',
						'identity_asset.identity_name as asset_name'
					)
				->join('asset','asset.asset_id','merchant_account_list.asset_id')
				->join('identity_asset','identity_asset.identity_id','asset.identity_id')
				->whereNotIn('merchant_account_list.asset_id', function($query) use ($merchantId,$customerId) {
							$query->select('customer_account_list.asset_id')
							->from('customer_account_list')
							->where('customer_account_list.merchant_id',$merchantId)
							->where('customer_account_list.customer_id',$customerId);
				})
				->groupBy('merchant_account_list.asset_id')
				->where('merchant_account_list.merchant_id',$merchantId)
				->get();
				break;
		}
		return json_encode($filterAssets);
	}

	public function getMerchantAssets(Request $request,$merchantId)
	{
		$filterAssets=Merchant_account_list::
			distinct()
			->select(
					'merchant_account_list.*',
					'identity_asset.identity_code as asset_code',
					'identity_asset.identity_name as asset_name'
				)
			->join('asset','asset.asset_id','merchant_account_list.asset_id')
			->join('identity_asset','identity_asset.identity_id','asset.identity_id')
			->groupBy('merchant_account_list.asset_id')
			->where('merchant_account_list.merchant_id',$merchantId)			
			->get();		
			
		return json_encode($filterAssets);
	}

	public function getAssetInfo($assetId)
	{
		$asset = Asset::select(
						'asset.*',
						'identity_asset.identity_name as asset_name',
						'identity_asset.identity_code as asset_code'
						)
				->join('identity_asset','identity_asset.identity_id','asset.identity_id')
				->where('asset_id',$assetId)
				->get()->first();
		return $asset;
	}

	public function getAccountInfo($accountId)
	{
		$asset = Account::select(
						'account.*',
						'identity_account.identity_name as account_name',
						'identity_account.identity_code as account_code'
						)
				->join('identity_account','identity_account.identity_id','account.identity_id')
				->where('account_id',$accountId)
				->get()->first();
		return $asset;
	}

	public function getMerchantInfo($merchantId)
	{
		$asset = Merchant::select(
						'merchant.*',
						'identity_merchant.identity_name as merchant_name',
						'identity_merchant.identity_code as merchant_code'
						)
				->join('identity_merchant','identity_merchant.identity_id','merchant.identity_id')
				->where('merchant_id',$merchantId)
				->get()->first();
		return $asset;
	}

	public function getCustomerInfo($customerId)
	{
		$asset = Customer::select(
						'customers.*',
						'identity_customer.identity_name as customer_name',
						'identity_customer.identity_code as customer_code'
						)
				->join('identity_customer','identity_customer.identity_id','customers.identity_id')
				->where('customer_id',$customerId)
				->get()->first();
		return $asset;
	}

	public function getAccountCustomers($merchantId)
	{
		$customers = Merchant_customer_list::
					select(
							'customers.customer_id',
							'identity_customer.identity_name as customer_name',
							'identity_customer.identity_code as customer_code'
						)

					->join('customers','customers.customer_id','merchant_customer_list.customer_id')

					->join('identity_customer','identity_customer.identity_id','customers.identity_id')

					->where('merchant_customer_list.merchant_id',$merchantId)
					->get();

		return json_encode($customers);
	}

	public function getMerchantAccountLists($merchantId,$assetId)
	{
		return Merchant_account_list::
					where('merchant_id',$merchantId)
					->where('asset_id',$assetId)
					->get();
	}

	public function getCustomerAccountLists($customerId,$assetId)
	{
		return Customer_account_list::
					where('customer_id',$customerId)
					->where('asset_id',$assetId)
					->get();
	}


	//----------------------------Wallet Logic-----------------------------------------------//
	public function getAllWallets()
	{
		return PermissionTrait::getWallets();
	}

	public function getAccountWallets(Request $request)
	{
		$accountWallets =  Account_wallet::
				select(
					'account_wallet.*',
					'wallet.*',
					'identity_account.identity_code as account_code',
					'identity_account.identity_name as account_name',
					'account.account_code_long',

					'timezone.timezone_name',

					'identity_asset.identity_code as asset_code',
					'identity_asset.identity_name as asset_name'
				)
				->join('wallet','account_wallet.wallet_id','wallet.wallet_id')
				->join('account','account.account_id','account_wallet.account_id')
				->join('identity_account','identity_account.identity_id','account.identity_id')

				->join('timezone','timezone.timezone_id','account_wallet.create_timezone')

				->join('asset','asset.asset_id','wallet.asset_id')
				->join('identity_asset','identity_asset.identity_id','asset.identity_id')
				->where('account_wallet.account_id',$request->account_id)
				->offset($request->skip)
            	->limit($request->take)
				->get();

		$total_records =  Account_wallet::
				join('wallet','account_wallet.wallet_id','wallet.wallet_id')
				->join('account','account.account_id','account_wallet.account_id')
				->join('identity_account','identity_account.identity_id','account.identity_id')

				->join('timezone','timezone.timezone_id','account_wallet.create_timezone')

				->join('asset','asset.asset_id','wallet.asset_id')
				->join('identity_asset','identity_asset.identity_id','asset.identity_id')
				->where('account_wallet.account_id',$request->account_id)
				->count();		

		foreach ($accountWallets as $key => $wallet) {
			$datetime = json_decode(PermissionTrait::covertToLocalTz($wallet->create_time));
			$accountWallets[$key]->create_date = $datetime->date;
			$accountWallets[$key]->create_time = $datetime->time;
		}

		$accountWalletsData['account_wallet'] = $accountWallets;
		$accountWalletsData['total'] = $total_records;

		return json_encode($accountWalletsData);
	}

	public function updateWalletList(Request $request)
	{
		$listUpdateColumn = array('priority','status');
		$walletUpdateColumn = array('wallet_name');

		$key = $request->key;
		$value = $request->value;

		if(in_array($key, $listUpdateColumn)){
			$accountObj = Account_wallet::findOrFail($request->list_id);
		}

		if(in_array($key, $walletUpdateColumn)){
			$accountInfo = Account_wallet::findOrFail($request->list_id);
			$accountObj = Wallet::findOrFail($accountInfo->wallet_id);
		}

		$accountObj->$key = $value;
		$accountObj->save();
	}

	public function getAssetId($account_id){

		$merchantAccountInfo = Merchant_account_list::
								where("staff_account_id",$account_id)
								->first();

		if($merchantAccountInfo){
			return $merchantAccountInfo->asset_id;
		}else{
			
			$customerAccountInfo = Customer_account_list::
								where("account_id",$account_id)
								->first();

			return $customerAccountInfo->asset_id;
		}
	}
	
	public function createAccountWallet(Request $request)
	{
		
		$asset_id = $this->getAssetId($request->account_id);

		$dataArray = array();

		$walletInfo = Wallet::
				where("wallet_address",$request->wallet_address)
				->get()->first();
		
		if(!$walletInfo){

			$wallet = new Wallet;
			$wallet->asset_id = $asset_id;
			$wallet->wallet_name = $request->wallet_name;
			$wallet->wallet_address = $request->wallet_address;
			$wallet->save();
			$walletID = $wallet->wallet_id;

			
			$account_wallet =  array(
				'account_id' => $request->account_id,
				'create_date' => date('Ymd'),
				'create_timezone' => PermissionTrait::getTimezoneId(),
				'create_time' => time(),
				'status' => 1,
				'wallet_id' => $walletID
			);
			Account_wallet::insert($account_wallet);
			return json_encode(array('error' => ""));
		}else{
			return json_encode(array('error' => "Duplicate Wallet Address Found!"));
		}
		return 1;
	}
}