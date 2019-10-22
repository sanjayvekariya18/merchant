<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\App;
use Illuminate\Http\Request;
use App\Http\Controllers\PermissionsController;
use App\Helpers\ConnectionManager;
use App\Http\Traits\PermissionTrait;

use App\Trade_order_type_list;
use App\TradeOrderType;
use App\Merchant;

use URL;
use Session;
use DB;
use Redirect;
use Auth;
use DateTime;
use Carbon\Carbon;
use File;

class Trade_order_type_listController extends PermissionsController
{
	use PermissionTrait;

	public function __construct()
	{
		parent::__construct();

        $connectionStatus = ConnectionManager::setDbConfig('Trade_order_type_list');

        if ($connectionStatus['type'] === "error") {
            Session::flash('type', $connectionStatus['type']);
            Session::flash('msg', $connectionStatus['message']);
            return Redirect::back()->send();
        }
	}

	
	public function index(){
		return view('trade_order_type_list.index');
	}

	public function store(Request $request)
	{
		$dataArray = array();
		$treeResult = explode('_', $request->topologyTreeResultId);
		switch ($treeResult[1]) {
			case 'merchant': $keyCol='merchant_id';
				break;
			case 'city': $keyCol='location_city_id';
				break;
			case 'group'   : $keyCol='group_id';
				break;
			case 'staff'   : $keyCol='staff_id';
				break;
		}
		
		Trade_order_type_list::
				where("merchant_id",$request->merchant_id)
				->whereNotIn('type_id',$request->type_id)
				->delete();

		foreach ($request->type_id as $key => $typeId) {
			
			$orderList = Trade_order_type_list::
				where("merchant_id",$request->merchant_id)
				->where('type_id',$typeId)
				->get()->first();

			if(!$orderList){
				$dataArray[$key] = array(
					'merchant_id' => $request->merchant_id,
					'customer_id' => $request->customer_id,
					'customer_account_id' => $request->customer_account_id,
					'staff_account_id' => $request->staff_account_id,
					'asset_id' => $request->asset_id,
					'type_id' => $typeId
				);
			}
		}
		Trade_order_type_list::insert($dataArray);
		return redirect('trade_order_type_list');
	}

	public function getOrderTypeList($merchantId)
	{
		$merchantTypeInfo = PermissionTrait::getMerchantType();
		$merchantType = $merchantTypeInfo->merchant_type_id;

		if($merchantId != 0){
            $where['trade_order_type_list.merchant_id'] = $merchantId;
        }else{
            $where = array();
        }

		$order_type_lists = Trade_order_type_list::
				distinct()
				->select(
					'trade_order_type_list.*',
					'identity_merchant.identity_name as merchant_name',
					'identity_merchant.identity_code as merchant_code',
					'location_city.city_name',
					'location_county.county_name',
					'location_state.state_name',
					'location_country.country_name',
					'staff_groups.staff_group_name',
				 	'staffs.staff_name',
					'trade_order_type.type_name'
					)
					->join('trade_order_type','trade_order_type.type_id','trade_order_type_list.type_id')

					->join('merchant','merchant.merchant_id','trade_order_type_list.merchant_id')
					->join('identity_merchant','identity_merchant.identity_id','merchant.identity_id')
					->join('location_city','location_city.city_id','trade_order_type_list.location_city_id')
					->join('location_county','location_city.county_id','location_county.county_id')
					->join('location_state','location_city.state_id','location_state.state_id')
					->join('location_country','location_city.country_id','location_country.country_id')
					->join('staffs','staffs.staff_id','trade_order_type_list.staff_id')
					->join('staff_groups','staff_groups.staff_group_id','trade_order_type_list.group_id')
					->where(function($q) use ($where){
	                    foreach($where as $key => $value){
	                        $q->where($key, '=', $value);
	                    }
	                })->get();

		return json_encode($order_type_lists);
	}


	public function getOrderTypeList($id)
	{	
		$nodeId = explode('_', $id);

		switch ($nodeId[1]) {
			case 'merchant': $where['merchant_id'] = $nodeId[0];
				break;
			case 'city': $where['location_city_id'] = $nodeId[0];
				break;
			case 'group'   : $where['group_id'] = $nodeId[0];
				break;
			case 'staff'   : $where['staff_id'] = $nodeId[0];
				break;
		}
		$trade_order_type_list = Trade_order_type_list::
								where(function($q) use ($where){
									foreach($where as $key => $value){
										$q->where($key, '=', $value);
									}
								})->get();


		return json_encode($trade_order_type_list);
	}

	public function tradeOrderTypes(){
	   $orderTypes=TradeOrderType::all();
		return json_encode($orderTypes);
	}

	public function getOrderTypeListByListID($listId)
	{
		$order_list = Trade_order_type_list::findOrFail($listId);
		return json_encode($order_list);
	}

	public function updateList(Request $request)
	{
		$key = $request->key;
		$value = $request->value;
		$order_list = Trade_order_type_list::findOrFail($request->list_id);
		
		$order_list->$key = $value;
		$order_list->save();
	}

	public function getMerchants()
    {
        $merchants = Merchant::
        select(
            'merchant.*',
            'identity_merchant.identity_name as merchant_name',
            'identity_merchant.identity_code as merchant_code'
        )
        ->join('identity_merchant','identity_merchant.identity_id','merchant.identity_id')
        ->where('merchant.merchant_id','>',0)
        ->get();
        return json_encode($merchants);
    }

	public function getAccounts(){
	   $accounts=PermissionTrait::getAccounts();
		return json_encode($accounts);
	}

	public function getAssets(){
	   $assets=PermissionTrait::getAssets();
		return json_encode($assets);
	}

	public function getOrderTypeListTree()
	{
		$topologyJsonArray = array();

		$where = array();

		if($this->merchantId == 0){
			$where[] = array(
				'key' => "merchant.merchant_id",
				'operator' => '>',
				'val' => $this->merchantId
			);
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
				$where['staff.location_id'] = $this->locationId; 
			}
		}

		$where = array();
        if($this->merchantId != 0 && $this->roleId == 4){
            $where['merchant.merchant_id'] = $this->merchantId; 
        }

		$merchants = Merchant::
            select(
                    'merchant.*',
                    'identity_merchant.identity_name as merchant_name',
                    'identity_merchant.identity_code as merchant_code'
                )
            ->join('identity_merchant','identity_merchant.identity_id','merchant.identity_id')
            ->where(function($q) use ($where){
                foreach($where as $key => $value){
                    $q->where($value['key'], $value['operator'], $value['val']);
                }
            })
            ->get();

		foreach ($merchants as $keyMerchant => $merchant) {
			$topologyJsonArray[$keyMerchant] = array(
                'text'      => $merchant->merchant_name,
                'id'        => $merchant->merchant_id."_merchant",
                'parent_id' => 0
            );

			$cities = PermissionTrait::getMerchantCities($merchant->merchant_id);

			foreach ($cities as $keyCity => $city) {
                
                $topologyJsonArray[$keyMerchant]['items'][$keyCity] = array(
                    'text'      => $city->city_name,
                    'id'        => $city->city_id."_city",
                    'parent_id' => $merchant->merchant_id
                );

				$staffGroups = PermissionTrait::getStaffGroups($merchant->merchant_id,$city->city_id);

				foreach ($staffGroups as $keyGroup => $staffGroup) {
					
					$topologyJsonArray[$keyMerchant]['items'][$keyCity]['items'][$keyGroup] = array(
                            'text'      => $staffGroup->staff_group_name,
                            'id'        => $staffGroup->staff_group_id."_group",
                            'parent_id' => $city->city_id
                        );

					$staffs = PermissionTrait::getStaffs($merchant->merchant_id,$staffGroup->staff_group_id);

					foreach ($staffs as $keyStaff => $staff) {
						
						$topologyJsonArray[$keyMerchant]['items'][$keyLocation]['items'][$keyStaff] = array(
							'text' 		=> $staff->staff_name,
							'id'		=> $staff->staff_id."_staff",
							'parent_id' => $staffGroup->staff_group_id
						);
					}
				}
			}
		}
		return json_encode($topologyJsonArray);
	}
}