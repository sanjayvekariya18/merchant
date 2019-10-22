<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\App;
use Illuminate\Http\Request;
use App\Http\Controllers\PermissionsController;
use App\Helpers\ConnectionManager;
use Amranidev\Ajaxis\Ajaxis;
use App\Http\Controllers\ProductApprovalController;
use App\Http\Traits\PermissionTrait;

use App\Asset;
use App\Asset_rate;
use App\Timezone;
use App\Identity_source;
use Carbon\Carbon;
use URL;
use Session;
use DB;
use Redirect;

/**
 * Class Asset_rateController.
 *
 */
class Asset_rateController extends PermissionsController
{
	use PermissionTrait;

	public function __construct()
	{
		parent::__construct();

        $connectionStatus = ConnectionManager::setDbConfig('Asset_rate');

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

		 if($this->permissionDetails('Asset_rate','access')){
			$where = array();
			$permissions = $this->getPermission("Asset_rate");
			
			 if($this->merchantId == 0){
			     //$where['merchant_type.merchant_root_id'] = $merchantType;
			 }else{
			     if($this->roleId == 4){
			         $where['asset_rates.merchant_id'] = $this->merchantId;
			     }else{
			         $where['asset_rates.merchant_id'] = $this->merchantId;
			         $where['asset_rates.location_id'] = $this->locationId;
			     }
			 }

			$asset_rates = Asset_rate::
				select(
					'asset_rates.*',
					'asset_from_identity.identity_name as asset_from_name',
					'asset_from_identity.identity_code as asset_from_code',
					'asset_into_identity.identity_name as asset_into_name',
					'asset_into_identity.identity_code as asset_into_code',
					'timezone.timezone_name'
				)
				->leftjoin('timezone','timezone.timezone_id','asset_rates.asset_last_timezone')
				->leftjoin('asset as asset_from','asset_from.asset_id','asset_rates.asset_from_id')
				->leftjoin('identity_asset as asset_from_identity','asset_from_identity.identity_id','asset_from.identity_id')
				->leftjoin('asset as asset_into','asset_into.asset_id','asset_rates.asset_into_id')
				->leftjoin('identity_asset as asset_into_identity','asset_into_identity.identity_id','asset_into.identity_id')
				->get();
			return view('asset_rate.index',compact('asset_rates','permissions'));
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
		 if($this->permissionDetails('Asset_rate','add')){
			
			$timezones = Timezone::all();
			$identitySource = Identity_source::all();
			$assets = Asset::
				select('asset.asset_id','identity_asset.identity_name','identity_asset.identity_code') 
				->distinct()
				->leftjoin('identity_asset','asset.identity_id','identity_asset.identity_id')
				->where('identity_asset.identity_id','!=',0)
			   	->get();
			return view('asset_rate.create',compact('timezones','assets','identitySource'));
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
		$asset_rate = new Asset_rate();
		$timeZoneId = PermissionTrait::getTimeZoneId();
		$currentDate = str_replace("-","",date('Y-m-d'));
        $currentTime = time();

		$asset_rates = Asset_rate::select('asset_rates.*')
			->where('asset_rates.asset_from_id',$request->asset_from_id)
			->where('asset_rates.asset_into_id',$request->asset_into_id)
			->get()->first();
		if($asset_rates && $asset_rates->rate_id) {
			$asset_rate = Asset_rate::findOrfail($asset_rates->rate_id);
			$asset_rate->asset_bid_price = $request->asset_bid_price;
			$asset_rate->asset_ask_price = $request->asset_ask_price;
			$asset_rate->asset_last_price = $request->asset_last_price;
			$asset_rate->save();
		}
		else {
			$asset_rate->asset_from_id = $request->asset_from_id;
			
			$asset_rate->asset_into_id = $request->asset_into_id;
			
			$asset_rate->asset_bid_price = $request->asset_bid_price;
			
			$asset_rate->asset_ask_price = $request->asset_ask_price;
			
			$asset_rate->asset_last_price = $request->asset_last_price;
			
			$asset_rate->asset_last_date = $currentDate;

			$asset_rate->asset_last_time = $currentTime;
			
			$asset_rate->asset_last_timezone = $timeZoneId;
			$asset_rate->asset_source_id = $request->asset_source_id;
			$asset_rate->save();
			$assetRateID = $asset_rate->rate_id;

			if($assetRateID) {
				$get_asset_rates = Asset_rate::select('asset_rates.*')
					->where('asset_rates.asset_from_id',$request->asset_into_id)
					->where('asset_rates.asset_into_id',$request->asset_from_id)
					->get()->first();
				if(!$get_asset_rates) {
					$asset_rate_reverse = new Asset_rate();
					$asset_rate_reverse->asset_from_id = $request->asset_into_id;
					$asset_rate_reverse->asset_into_id = $request->asset_from_id;
					$asset_rate_reverse->asset_bid_price = 1/$request->asset_bid_price;
					$asset_rate_reverse->asset_ask_price = 1/$request->asset_ask_price;
					$asset_rate_reverse->asset_last_price = 1/$request->asset_last_price;

					$asset_rate_reverse->asset_last_date = $currentDate;

					$asset_rate_reverse->asset_last_time = $currentTime;
				
					$asset_rate_reverse->asset_last_timezone = $timeZoneId;
					$asset_rate_reverse->asset_source_id = $request->asset_source_id;
					$asset_rate_reverse->save();
				}
			}
		}

		Session::flash('type', 'success');
		Session::flash('msg', 'Asset Rate Successfully Created');
		
		if ($request->submitBtn === "Save") {
			return redirect('asset_rate/'. $assetRateID . '/edit');
		}else{
			return redirect('asset_rate');
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
		 if($this->permissionDetails('Asset_rate','manage')) {
			
			$asset_rate = Asset_rate::findOrfail($id);
			$timezones = Timezone::all();
			$identitySource = Identity_source::all();
			$assets = Asset::
				select('asset.asset_id','identity_asset.identity_name','identity_asset.identity_code') 
				->distinct()
				->leftjoin('identity_asset','asset.identity_id','identity_asset.identity_id')
				->where('identity_asset.identity_id','!=',0)
			   	->get();
			return view('asset_rate.edit',compact('asset_rate','timezones','assets','identitySource'));
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
		$asset_rate = Asset_rate::findOrfail($id);
		$timeZoneId = PermissionTrait::getTimeZoneId();
		$currentDate = str_replace("-","",date('Y-m-d'));
        $currentTime = time();
		
		$asset_rate->asset_from_id = $request->asset_from_id;
		
		$asset_rate->asset_into_id = $request->asset_into_id;
		
		$asset_rate->asset_bid_price = $request->asset_bid_price;
		
		$asset_rate->asset_ask_price = $request->asset_ask_price;
		
		$asset_rate->asset_last_price = $request->asset_last_price;
		
		$asset_rate->asset_last_date = $currentDate;

		$asset_rate->asset_last_time = $currentTime;
		
		$asset_rate->asset_last_timezone = $timeZoneId;
		$asset_rate->asset_source_id = $request->asset_source_id;
		$asset_rate->save();
		$assetRateID = $asset_rate->rate_id;

		Session::flash('type', 'success'); 
		Session::flash('msg', 'Asset Rate Successfully Updated');
		
		if ($request->submitBtn === "Save") {
			return redirect('asset_rate/'. $assetRateID . '/edit');
		} else {
			return redirect('asset_rate');
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
		 if($this->permissionDetails('Asset_rate','delete')){
			$asset_rate = Asset_rate::findOrfail($id);
			$asset_rate->delete();
			Session::flash('type', 'error');
			Session::flash('msg', 'Asset Rate Successfully Deleted');
			return redirect('asset_rate');
			
		 }else{
		 	return Redirect::back()->with('message', 'You are not authorized to use this functionality!');
		 }
	}
}
