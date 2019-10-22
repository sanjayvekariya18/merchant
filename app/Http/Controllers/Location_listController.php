<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\App;
use Illuminate\Http\Request;
use App\Http\Controllers\PermissionsController;
use App\Helpers\ConnectionManager;
use App\Http\Traits\PermissionTrait;
use App\Location_list;
use App\Country;
use App\State;
use App\County;
use App\City;
use App\Postal;
use App\Identity_postal;
use App\Working_hour;
use App\Working_hours_stage;
use App\Working_holiday;
use App\Premises_operation;
use App\Reservations_booking;
use App\Table;
use URL;
use Session;
use DB;
use Redirect;
use Auth;
use Input;
use DateTime;
use Carbon\Carbon;
use File;

CONST GOOGLE_API_KEY = "AIzaSyB0rfVC02005ehz3RV7cwQfKm4qwBvzghE";
CONST CUSTOMER_IDENTITY_TYPE	= 4;
CONST MERCHANT_IDENTITY_TYPE	= 8;
CONST PEOPLE_IDENTITY_TYPE 		= 15;
CONST PAYEE_IDENTITY_TYPE 		= 21;
CONST INIT_VALUE				= 0;
CONST FIRST_VALUE 				= 1;

class Location_listController extends PermissionsController
{
	use PermissionTrait;

	public function __construct()
	{
		parent::__construct();

        $connectionStatus = ConnectionManager::setDbConfig('Hase_merchant');
        if ($connectionStatus['type'] === "error") {
            Session::flash('type', $connectionStatus['type']);
            Session::flash('msg', $connectionStatus['message']);
            return Redirect::back()->send();
        }
	}

	public function index(){
		
		$reservations_seatings = Table::all();
		return view('location_list.index',compact("reservations_seatings"));
	}

	public function store(Request $request)
	{
		$cityArray = array();
		$dataArray = array();

		if(isset($request->region_id)){			

			foreach ($request->region_id as $key => $city) {
				if(strpos($city, 'city') !== false){
					$cityArray[] = explode('_', $city)[0];
				}
			}
			
			Location_list::
					where("identity_id",$request->identity_id)
					->where("identity_table_id",$request->identity_table_id)
					->whereNotIn('location_city_id',$cityArray)
					->delete();

			foreach ($cityArray as $key => $cityId) {
				
				$cityInfo = Location_list::
					where("identity_id",$request->identity_id)
					->where("identity_table_id",$request->identity_table_id)
					->where('location_city_id',$cityId)
					->get()->first();

				if(!$cityInfo){
					$dataArray[$key] = array(
						'identity_id' => $request->identity_id,
						'identity_table_id' => $request->identity_table_id,
						'location_city_id' => $cityId
					);
				}
			}
			Location_list::insert($dataArray);
			
		}else{
			Location_list::
					where("identity_id",$request->identity_id)
					->where("identity_table_id",$request->identity_table_id)
					->delete();
		}
		return 1;
	}

	public function getIdentityCityList(Request $request)
	{	
		$originTable = PermissionTrait::getTableType($request->identity_table_id);
		$originTableInfo = PermissionTrait::getIdentityTableType($originTable->table_code,$request->identity_id);
		$identityTable = PermissionTrait::getTableType($originTableInfo->identity_table_id);

		$originTableName = $originTable->table_code;
		$identityTableName = $identityTable->table_code;

		$identity_city_lists = Location_list::
			select(
				'location_list.*', 
				'location_city.city_id',
				'location_city.state_id',
				'location_city.country_id',
				'postal.postal_premise',
				'postal.postal_subpremise',
				'postal.postal_street_number',
				'postal.postal_route',
				'postal.postal_neighborhood',
				'postal.postal_postcode',
				'postal.postal_lat',
				'postal.postal_lng',
					
				$identityTableName.'.*',

				$identityTableName.'.identity_name',
				$identityTableName.'.identity_code',
			
				'location_city.city_name',
				'location_county.county_name',
				'location_state.state_name',
				'location_country.country_name'
			)

			->join($originTableName,$originTableName.'.identity_id','location_list.identity_id')
			->join($identityTableName,'location_list.identity_id',$identityTableName.'.identity_id')

			->leftjoin('postal','postal.postal_id','location_list.postal_id')
			->leftjoin('identity_postal','identity_postal.identity_id','postal.identity_id')

			->join('location_city','location_city.city_id','location_list.location_city_id')

			->join('location_state','location_city.state_id','location_state.state_id')
			->join('location_county','location_city.county_id','location_county.county_id')
			->join('location_country','location_city.country_id','location_country.country_id')
			->groupBy('location_list.location_city_id')
			->where('location_list.identity_id',$request->identity_id)
			->where('location_list.identity_table_id',$request->identity_table_id)
			->get();		

		return json_encode($identity_city_lists);
	}

	public function getIdentityCityListData(Request $request)
	{	
		$originTable = PermissionTrait::getTableType($request->identity_table_id);
		$originTableInfo = PermissionTrait::getIdentityTableType($originTable->table_code,$request->identity_id);
		$identityTable = PermissionTrait::getTableType($originTableInfo->identity_table_id);

		$originTableName = $originTable->table_code;
		$identityTableName = $identityTable->table_code;

		$identity_city_list = Location_list::
			select(
				'location_list.*', 
				'location_city.city_id',
				'location_city.state_id',
				'location_city.country_id',
				'postal.postal_premise',
				'postal.postal_subpremise',
				'postal.postal_street_number',
				'postal.postal_route',
				'postal.postal_neighborhood',
				'postal.postal_postcode',
				'postal.postal_lat',
				'postal.postal_lng',
					
				$identityTableName.'.*',

				$identityTableName.'.identity_name',
				$identityTableName.'.identity_code',
			
				'location_city.city_name',
				'location_county.county_name',
				'location_state.state_name',
				'location_country.country_name'
			)

			->join($originTableName,$originTableName.'.identity_id','location_list.identity_id')
			->join($identityTableName,'location_list.identity_id',$identityTableName.'.identity_id')

			->leftjoin('postal','postal.postal_id','location_list.postal_id')
			->leftjoin('identity_postal','identity_postal.identity_id','postal.identity_id')

			->join('location_city','location_city.city_id','location_list.location_city_id')

			->join('location_state','location_city.state_id','location_state.state_id')
			->join('location_county','location_city.county_id','location_county.county_id')
			->join('location_country','location_city.country_id','location_country.country_id')
			->groupBy('location_list.location_city_id')
			->where('location_list.identity_id',$request->identity_id)
			->where('location_list.identity_table_id',$request->identity_table_id);

		$identity_city_data["total"] = $identity_city_list->get()->count();
		$identity_city_data["identity_city_lists"] = $identity_city_list->offset($request->skip)->limit($request->take)->get();
		
		return json_encode($identity_city_data);
	}

	public function updateList(Request $request)
	{
		$key = $request->key;
		$value = $request->value;
		$order_list = Location_list::findOrFail($request->list_id);
		$order_list->$key = $value;
		$order_list->save();
	}

	public function getLocationData(Request $request)
	{
		$workingHours = array();
		$holidayHours = array();
		$reservationTables = array();

		$createdAt = Carbon::now();
		$currentDate = $createdAt->format('Ymd');		

		$postal_list = Location_list::
			select(
				'location_list.*',
				
				'postal.postal_premise',
				'postal.postal_subpremise',
				'postal.postal_street_number',
				'postal.postal_route',
				'postal.postal_neighborhood',
				'postal.postal_postcode',
				'postal.postal_lat',
				'postal.postal_lng',

				'location_city.city_id',
				'location_city.city_name',
				'location_county.county_id',
				'location_county.county_name',
				'location_state.state_id',
				'location_state.state_name',
				'location_country.country_id',
				'location_country.country_name',
				'location_country.postal_code_max',

				'premises_operation.*'
			)

			->leftjoin('postal','location_list.postal_id','postal.postal_id')
			->leftjoin('identity_postal','identity_postal.identity_id','postal.identity_id')

			->join('location_city','location_city.city_id','location_list.location_city_id')

			->join('location_state','location_city.state_id','location_state.state_id')
			->join('location_county','location_city.county_id','location_county.county_id')
			->join('location_country','location_city.country_id','location_country.country_id')
			->leftjoin('premises_operation','postal.operations_id','premises_operation.operation_id')
			->where('location_list.identity_id',$request->identity_id)
			->where('location_list.identity_table_id',$request->identity_table_id)
			->where('location_list.location_city_id',$request->location_city_id)
			->get();

		$countryId = $postal_list[0]["country_id"];
		$hase_holidays = Working_holiday::
							where('holiday_date','>=',$currentDate)
							->where('country_id',$countryId)
							->get();

		foreach ($hase_holidays as $key => $holiday) {
			$hase_holidays[$key]["holiday_date"] = substr_replace(substr_replace($holiday->holiday_date, '-', 4, 0), '-', 7, 0);
		}								

		foreach ($postal_list as $postal) {
			
			$workingHour = 	Working_hour::where("list_id",$postal->list_id)->where("holiday_id","0")->get()->toArray();
			$holidayHour = 	Working_hour::where("list_id",$postal->list_id)->where("holiday_id","!=","0")->where("shift",0)->get()->toArray();
			$reservationTable = Reservations_booking::join("reservations_seating","reservations_seating.seating_id","reservations_booking.seating_id")->where("list_id",$postal->list_id)->get()->toArray();

			
			foreach ($workingHour as $key => $value) {
				
				$workingHour[$key]["opening_time"] = PermissionTrait::convertIntoTime($value["opening_time"]);
				$workingHour[$key]["closing_time"] = PermissionTrait::convertIntoTime($value["closing_time"]);
			}

			foreach ($holidayHour as $holidayKey => $holidayValue) {
				
				$holidayHour[$holidayKey]["opening_time"] = PermissionTrait::convertIntoTime($holidayValue["opening_time"]);
				$holidayHour[$holidayKey]["closing_time"] = PermissionTrait::convertIntoTime($holidayValue["closing_time"]);

				$holidayShift = Working_hour::where("list_id",$postal->list_id)->where("holiday_id",$holidayValue["holiday_id"])->where("shift","!=",0)->get()->toArray();
				foreach ($holidayShift as $shiftKey => $shiftValue) {
					$holidayShift[$shiftKey]["opening_time"] = PermissionTrait::convertIntoTime($shiftValue["opening_time"]);
					$holidayShift[$shiftKey]["closing_time"] = PermissionTrait::convertIntoTime($shiftValue["closing_time"]);
				}
				
				$holidayHour[$holidayKey]["shiftData"] = $holidayShift;
			}
			
			$workingHours[] = $workingHour;
			$holidayHours[] = $holidayHour;
			$reservationTables[] = $reservationTable;
		}

		return json_encode(array("locationData" => $postal_list,"workingHours" => $workingHours,"holidayHours" => $holidayHours,"reservationTables" => $reservationTables,"holidays" => $hase_holidays));
	}

	public function getPostalAddress(Request $request)
	{
		$postalData = $this->getCoordinates($request);
		
		$jsonArray = array(
			"street_number" => array(),
			"route"			=> array(),
			"neighborhood"	=> array(),
			"postal_code"	=> array()
		);

		if($postalData && count($postalData->results)){

			if(isset($postalData->results[FIRST_VALUE])){
				$addresses = $postalData->results[FIRST_VALUE]->address_components;
				$geometry = $postalData->results[FIRST_VALUE]->geometry;
			}else{
				$addresses = $postalData->results[INIT_VALUE]->address_components;
				$geometry = $postalData->results[INIT_VALUE]->geometry;
			}			

			if(count($addresses)){
				foreach ($addresses as $key => $address) {

					if(isset($address->types)){

						(in_array("street_number", $address->types))?
							$jsonArray['street_number'][] = $address->long_name : '';

						(in_array("route", $address->types))?
							$jsonArray['route'][] = $address->long_name : '';
						
						in_array("neighborhood", $address->types)?
							$jsonArray['neighborhood'][] = $address->long_name : '';

						in_array("postal_code", $address->types)?
							$jsonArray['postal_code'][] = $address->long_name : '';
					}
				}
			}
			$jsonArray['lat'] = isset($geometry->location->lat)?
					$geometry->location->lat : INIT_VALUE;

			$jsonArray['lng'] = isset($geometry->location->lng)?
					$geometry->location->lng : INIT_VALUE;

			return json_encode($jsonArray);

		}else{
			return json_encode($jsonArray);
		}
	}

	public function updateLocation(Request $request)
	{

		$allLocationPostal = array();
		$locationsData = $postal_list = Location_list::
			select(
				'location_list.list_id',				
				'postal.identity_id',
				'postal.postal_id',
				'postal.operations_id'
			)
			->leftjoin('postal','location_list.postal_id','postal.postal_id')
			->where('location_list.identity_id',$request->identity_id)
			->where('location_list.identity_table_id',$request->identity_table_id)
			->where('location_list.location_city_id',$request->location_city_id)
			->get();

		foreach ($locationsData as $locationData) {
				$allLocationPostal[$locationData->list_id] = $locationData->toArray();
			}
		
		foreach($request->postals as $key => $postal) {

			$location_list_id = "";

			if($postal['list_id']){
				$listInfo = Location_list::findOrfail($postal['list_id']);

				// UNSET LIST ID FROM ALL POSTAL LIST
				unset($allLocationPostal[$postal['list_id']]);
			}			
			
			if($postal['postal_id']){
				$postalObj = Postal::findOrfail($postal['postal_id']);
			}else{
				$identity_postal = new Identity_postal;
				$identity_postal->identity_type_id = 14;
				$identity_postal->save();

				$postalObj = new Postal;
				$postalObj->identity_id = $identity_postal->identity_id;
			}

			$postalObj->postal_premise = $postal['premise'];
			$postalObj->postal_subpremise = $postal['subpremise'];
			$postalObj->postal_street_number =	($postal['street_number'] != "None")?
							$postal['street_number'] : 0 ;
			$postalObj->postal_route =	($postal['route'] != "None")?
							$postal['route'] : '' ;
			$postalObj->postal_neighborhood =	($postal['neighborhood'] != "None")?
							$postal['neighborhood'] : '' ;
			$postalObj->postal_postcode =	($postal['neighborhood'] != "None")?
							$postal['neighborhood'] : 0 ;
			$postalObj->postal_lat =	$postal['lat'];
			$postalObj->postal_lng =	$postal['lng'];

			$postalObj->save();

			if($postal['list_id']){
				$listInfo->postal_id = $postalObj->postal_id;
				$listInfo->save();
				$location_list_id = $listInfo->list_id;

			}else{
				$cityList = new Location_list;
				$cityList->identity_id = $listInfo->identity_id;
				$cityList->identity_table_id = $listInfo->identity_table_id;
				$cityList->location_city_id = $listInfo->location_city_id;
				$cityList->postal_id = $postalObj->postal_id;
				$cityList->save();
				$location_list_id = $cityList->list_id;
			}

			// ORDER AND RESERVATION SETTING FOR PREMISES OPERATIONS
			$postalObjData = Postal::findOrfail($postalObj->postal_id);
			if($postalObjData->operations_id > 0){
				$premisesOperation = Premises_operation::findOrfail($postalObjData->operations_id);
			}else{
				$premisesOperation = new Premises_operation();
			}

			$premisesOperation->offer_delivery = isset($postal['offer_delivery'])?1:0;
			$premisesOperation->offer_collection = isset($postal['offer_delivery'])?1:0;
			$premisesOperation->delivery_time = $postal['delivery_time'];
			$premisesOperation->collection_time = $postal['collection_time'];
			$premisesOperation->last_order_time = $postal['last_order_time'];
			$premisesOperation->future_orders = isset($postal['future_orders'])?1:0;
			$premisesOperation->future_order_delivery_days = $postal['future_order_delivery_days'];
			$premisesOperation->future_order_collection_days = $postal['future_order_collection_days'];

			$premisesOperation->reservation_time_interval = $postal['reservation_time_interval'];
			$premisesOperation->reservation_stay_time = $postal['reservation_stay_time'];
			$premisesOperation->save();

			if(!$postalObjData->operations_id){
				$postalObjData->operations_id = $premisesOperation->operation_id;
				$postalObjData->save();
			}

			// WORKING HOUR CODE 

			Working_hour::where("list_id",$location_list_id)->delete();

			if(count($postal['flexible_hours'])) {
                foreach ($postal['flexible_hours'] as $day => $dayData) {
                    foreach ($dayData as $shift => $shiftData) {
                        $hase_working_hours = new Working_hour();
                        $hase_working_hours->list_id = $location_list_id;
                        $hase_working_hours->weekday = $day;
                        $hase_working_hours->shift = $shift;
                        $hase_working_hours->holiday_id = 0;
                        $hase_working_hours->working_hours_type_id = 1;
                        
                        $openTimeData = explode(":", $shiftData['open']);
                        $workOpenTime = $openTimeData[0]*3600+$openTimeData[1]*60;
                        $closeTimeData = explode(":", $shiftData['close']);
                        $workCloseTime = $closeTimeData[0]*3600+$closeTimeData[1]*60;

                        $hase_working_hours->opening_time = $workOpenTime;
                        $hase_working_hours->closing_time = $workCloseTime;
                        if (!array_key_exists('status', $shiftData)) {
                            $shiftData['status'] = 0;
                        }else{
                        	$shiftData['status'] = 1;
                        }
                        $hase_working_hours->status = $shiftData['status'];
                        $hase_working_hours->save();

                    }
                }
            }

            if(count($postal['holiday_hours'])) {
                foreach ($postal['holiday_hours'] as $holiDay => $holiDayData) {
                	$holidayId = $holiDayData[0]["holiday_id"];
                    foreach ($holiDayData as $holidayShift => $holidayShiftData) {
                    	if($holidayId != ""){
	                        $hase_holiday_working_hours = new Working_hour();
	                        $hase_holiday_working_hours->list_id = $location_list_id;
	                        $hase_holiday_working_hours->weekday = 0;
	                        $hase_holiday_working_hours->shift = $holidayShift;
	                        $hase_holiday_working_hours->holiday_id = $holidayId;
	                        $hase_holiday_working_hours->working_hours_type_id = 1;
	                        
	                        $openTimeData = explode(":", $holidayShiftData['open']);
	                        $workOpenTime = $openTimeData[0]*3600+$openTimeData[1]*60;
	                        $closeTimeData = explode(":", $holidayShiftData['close']);
	                        $workCloseTime = $closeTimeData[0]*3600+$closeTimeData[1]*60;

	                        $hase_holiday_working_hours->opening_time = $workOpenTime;
	                        $hase_holiday_working_hours->closing_time = $workCloseTime;
	                        if (!array_key_exists('status', $holidayShiftData)) {
	                            $holidayShiftData['status'] = 0;
	                        }else{
	                        	$holidayShiftData['status'] = 1;
	                        }
	                        $hase_holiday_working_hours->status = $holidayShiftData['status'];
	                        $hase_holiday_working_hours->save();
	                    }    
                    }
                }
            }

            Reservations_booking::where("list_id",$location_list_id)->delete();
            if(isset($postal['tables'])){
            	foreach ($postal['tables'] as $seatingId) {
            		$reservation_booking = new Reservations_booking();
            		$reservation_booking->list_id = $location_list_id;
            		$reservation_booking->seating_id = $seatingId;
            		$reservation_booking->save();
            	}
            }
		}

		if(count($allLocationPostal)>0){
        	foreach ($allLocationPostal as $locationData) {
        		Reservations_booking::where("list_id",$locationData["list_id"])->delete();
        		Working_hour::where("list_id",$locationData["list_id"])->delete();
        		Premises_operation::where("operation_id",$locationData["operations_id"])->delete();
        		Identity_postal::where("identity_id",$locationData["identity_id"])->delete();
        		Postal::where("postal_id",$locationData["postal_id"])->delete();
        		Location_list::where("list_id",$locationData["list_id"])->delete();
        	}
        }
		return 1;
	}

	public function getCoordinates($request) {
		
        $addressInfo = urldecode($request->postal_subpremise).",".
        			   urldecode($request->postal_premise).",".
        			   urldecode($request->city_name);

        $locationData = urlencode($addressInfo);

        $coordinateUrl = 'https://maps.googleapis.com/maps/api/geocode/json?region=hk&language=en&address=' . $locationData . '&key=' . GOOGLE_API_KEY;

		$curl = curl_init();

		curl_setopt_array($curl, array(
			CURLOPT_URL => $coordinateUrl,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_TIMEOUT => 30000,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "GET",
			CURLOPT_HTTPHEADER => array(
				'Content-Type: application/json',
			),
		));
		$response = curl_exec($curl);
		$err = curl_error($curl);
		curl_close($curl);

		return (!$err)? json_decode($response) : false;
    }

	public function getIdentities(Request $request)
	{
		switch ($request->identity_table_id) {
			case 4:
				$customers = PermissionTrait::getCustomers();
				return json_encode($customers);
				break;
			case 8:
				$merchants = PermissionTrait::getMerchants();
				return json_encode($merchants);
				break;
			case 15:
				$peoples = PermissionTrait::getPeoples();
				return json_encode($peoples);
				break;
			case 21:
				$payees = PermissionTrait::getPayees();
				return json_encode($payees);
				break;
			default:
				return false;
				break;
		}

		/*$identityTable = PermissionTrait::getIdentityTable($request->identity_table_id);

		$identities = DB::table($identityTable->table_code)
						->where($identityTable->table_key,0)
						->get();*/
	}

	public function getLocationCountries()
	{
		$countries = PermissionTrait::getCountries();
		return json_encode($countries);
	}

	public function getLocationStates(Request $request)
	{
		if($request->query('id') != ""){
			$states = PermissionTrait::getStates($request->query('id'));
		}else{
			$states = PermissionTrait::getStates();
		}
		return json_encode($states);
	}

	public function getLocationCounties(Request $request)
	{
		if($request->query('id') != ""){
			$counties = PermissionTrait::getCounties($request->query('id'));
		}else{
			$counties = PermissionTrait::getCounties();
		}
		return json_encode($counties);
	}

	public function getLocationCities(Request $request)
	{
		if($request->query('id') != ""){
			$cities = PermissionTrait::getCities($request->query('id'));
		}else{
			$cities = PermissionTrait::getCities();
		}
		return json_encode($cities);
	}

	public function getRegions(){
		$portalRegionAutoCompletePath = storage_path()."/treeview/PortalRegionAutoComplete.json"; 
		return file_get_contents($portalRegionAutoCompletePath);
	}

	public function getLocationTree()
	{
		$portalRegionTreePath = storage_path()."/treeview/PortalRegionTree.json";
		return file_get_contents($portalRegionTreePath);
	}

	public function createLocationTreeJson()
	{
		$topologyJsonArray = array();

		$countries = Country::where('country_id','>',0)->get();

		foreach ($countries as $keyCountry => $country) {
			$topologyJsonArray[$keyCountry] = array(
				'text' 		=> $country->country_name,
				'id'		=> $country->country_id."_country",
				'parent_id' => 0
			);

			$states = State::
				join('location_city', 'location_state.state_id', '=', 'location_city.state_id')
	            ->where('location_city.country_id','=',$country->country_id)
	            ->select('location_state.*')
	            ->orderBy('location_state.state_name', 'ASC')
	            ->groupBy('state_name')
	            ->get();

	        if(!count($states)){
				unset($topologyJsonArray[$keyCountry]);
				continue;
			}
			foreach ($states as $keyState => $state) {
				$topologyJsonArray[$keyCountry]['items'][$keyState] = array(
					'text' 		=> $state->state_name,
					'id'		=> $state->state_id."_state",
					'parent_id' => $country->country_id
				);

				$cities = City::where('state_id',$state->state_id)->get();
				if(!count($cities)){
					unset($topologyJsonArray[$keyCountry]['items'][$keyState]);
					continue;
				}
				foreach ($cities as $keyCity => $city) {
					$topologyJsonArray[$keyCountry]['items'][$keyState]['items'][$keyCity] = array(
						'text' 		=> $city->city_name,
						'id'		=> $city->city_id."_city",
						'parent_id' => $state->state_id
					);
				}
			}
		}
		$portalRegionTreePath = storage_path()."/treeview/PortalRegionTree.json"; 
		file_put_contents($portalRegionTreePath, json_encode(array_values($topologyJsonArray), JSON_PRETTY_PRINT));
	}

	public function createLocationAutoCompleteJson()
	{
		$regionArray = array();
	   	$cities=PermissionTrait::getCities()->where('city_id','>',0);
	   	foreach ($cities as $key => $city) {
	   		$regionArray[] = array(
	   			'region_id' => $city->city_id."_city",
	   			'region_name' => $city->city_name,
	   			'path' => $city->country_id."_country/".$city->state_id."_state/".$city->city_id."_city",
	   		); 
	   	}
	   	$portalRegionAutoCompletePath = storage_path()."/treeview/PortalRegionAutoComplete.json"; 
		file_put_contents($portalRegionAutoCompletePath, json_encode($regionArray, JSON_PRETTY_PRINT));
	}
	public function importMagentoLocation()
	{
		$magentoTreePath = storage_path()."/treeview/MagentoRegionTree.json";
		$regionJson = file_get_contents($magentoTreePath);
		$regionJsonArray = json_decode($regionJson, true);
		foreach($regionJsonArray as $regionJsonRegionKey => $regionJsonRegionValue)
		{
			if(isset($regionJsonRegionValue['items']))
			foreach($regionJsonRegionValue['items'] as $continentKey => $continentValue)
			{
				if($continentValue['text'] == 'Asia' || $continentValue['text'] == 'Europe')
				{
					if(isset($continentValue['items']))
					foreach($continentValue['items'] as $subContinentKey => $subContinentValue)
					{
						if(isset($subContinentValue['items']))
						foreach($subContinentValue['items'] as $countryKey => $countryValue)
						{
							$countryValue['text'] = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $countryValue['text']);

							$countryExist = Country::
								where("country_name",$countryValue['text'])
								->get()->first();
							if($countryExist)
							{
								$lastInsertedCountryId = $countryExist->country_id;
							} else {
								$countryObject = new Country();
								$countryObject->country_name = $countryValue['text'];
								$countryObject->save();
								$lastInsertedCountryId = $countryObject->country_id;
							}
							if(isset($countryValue['items']))
							{
								foreach($countryValue['items'] as $stateKey => $stateValue)
								{
									$stateValue['text'] = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $stateValue['text']);
									$stateExist = State::
										where("state_name",$stateValue['text'])
										->where("country_id",$lastInsertedCountryId)
										->get()->first();
									if($stateExist)
									{
										$lastInsertedStateId = $stateExist->state_id;
									} else {
										$stateObject = new State();
										$stateObject->state_name = $stateValue['text'];
										$stateObject->country_id = $lastInsertedCountryId;
										$stateObject->save();
										$lastInsertedStateId = $stateObject->state_id;
									}
									if(isset($stateValue['items']))
									{
										foreach($stateValue['items'] as $cityKey => $cityValue)
										{
											$cityValue['text'] = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $cityValue['text']);

											$cityExist = City::
												where("city_name",$cityValue['text'])
												->where("state_id",$lastInsertedStateId)
												->where("country_id",$lastInsertedCountryId)
												->get()->first();
											if($cityExist)
											{
												$lastInsertedCityId = $cityExist->city_id;
											} else {
												$cityObject = new City();
												$cityObject->city_name = $cityValue['text'];
												$cityObject->state_id = $lastInsertedStateId;
												$cityObject->county_id = 0;
												$cityObject->country_id = $lastInsertedCountryId;
												$cityObject->save();
												$lastInsertedCityId = $cityObject->city_id;
											}
										}
									} else {
										$cityExist = City::
											where("city_name",$stateValue['text'])
											->where("state_id",$lastInsertedStateId)
											->where("country_id",$lastInsertedCountryId)
											->get()->first();
										if(!$cityExist)
										{
											$cityObject = new City();
											$cityObject->city_name = $stateValue['text'];
											$cityObject->state_id = $lastInsertedStateId;
											$cityObject->county_id = 0;
											$cityObject->country_id = $lastInsertedCountryId;
											$cityObject->save();
											$lastInsertedCityId = $cityObject->city_id;
										}
									}
								}
							} else {
								$stateExist = State::
									where("state_name",$countryValue['text'])
									->where("country_id",$lastInsertedCountryId)
									->get()->first();

								if($stateExist)
								{
									$lastInsertedStateId = $stateExist->state_id;
								} else {
									$stateObject = new State();
									$stateObject->state_name = $countryValue['text'];
									$stateObject->country_id = $lastInsertedCountryId;
									$stateObject->save();
									$lastInsertedStateId = $stateObject->state_id;
								}
								$cityExist = City::
									where("city_name",$countryValue['text'])
									->where("state_id",$lastInsertedStateId)
									->where("country_id",$lastInsertedCountryId)
									->get()->first();
								if(!$cityExist)
								{
									$cityObject = new City();
									$cityObject->city_name = $countryValue['text'];
									$cityObject->state_id = $lastInsertedStateId;
									$cityObject->county_id = 0;
									$cityObject->country_id = $lastInsertedCountryId;
									$cityObject->save();
									$lastInsertedCityId = $cityObject->city_id;
								}
							}
						}
					}
				} else {
					if(isset($continentValue['items']))
					foreach($continentValue['items'] as $countryKey => $countryValue)
					{
						$countryValue['text'] = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $countryValue['text']);

						$countryExist = Country::
								where("country_name",$countryValue['text'])
								->get()->first();
						if($countryExist)
						{
							$lastInsertedCountryId = $countryExist->country_id;
						} else {
							$countryObject = new Country();
							$countryObject->country_name = $countryValue['text'];
							$countryObject->save();
							$lastInsertedCountryId = $countryObject->country_id;
						}
						var_dump($lastInsertedCountryId);
						if(isset($countryValue['items']))
						{
							foreach($countryValue['items'] as $stateKey => $stateValue)
							{
								$stateValue['text'] = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $stateValue['text']);
								$stateExist = State::
									where("state_name",$stateValue['text'])
									->where("country_id",$lastInsertedCountryId)
									->get()->first();
								if($stateExist)
								{
									$lastInsertedStateId = $stateExist->state_id;
								} else {
									$stateObject = new State();
									$stateObject->state_name = $stateValue['text'];
									$stateObject->country_id = $lastInsertedCountryId;
									$stateObject->save();
									$lastInsertedStateId = $stateObject->state_id;
								}
								if(isset($stateValue['items']))
								{
									foreach($stateValue['items'] as $cityKey => $cityValue)
									{
										$cityValue['text'] = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $cityValue['text']);

										$cityExist = City::
											where("city_name",$cityValue['text'])
											->where("state_id",$lastInsertedStateId)
											->where("country_id",$lastInsertedCountryId)
											->get()->first();
										if($cityExist)
										{
											$lastInsertedCityId = $cityExist->city_id;
										} else {
											$cityObject = new City();
											$cityObject->city_name = $cityValue['text'];
											$cityObject->state_id = $lastInsertedStateId;
											$cityObject->county_id = 0;
											$cityObject->country_id = $lastInsertedCountryId;
											$cityObject->save();
											$lastInsertedCityId = $cityObject->city_id;
										}
									}
								} else {
									$cityExist = City::
										where("city_name",$stateValue['text'])
										->where("state_id",$lastInsertedStateId)
										->where("country_id",$lastInsertedCountryId)
										->get()->first();
									if(!$cityExist)
									{
										$cityObject = new City();
										$cityObject->city_name = $stateValue['text'];
										$cityObject->state_id = $lastInsertedStateId;
										$cityObject->county_id = 0;
										$cityObject->country_id = $lastInsertedCountryId;
										$cityObject->save();
										$lastInsertedCityId = $cityObject->city_id;
									}
								}
							}
						} else {
							$stateExist = State::
								where("state_name",$countryValue['text'])
								->where("country_id",$lastInsertedCountryId)
								->get()->first();

							if($stateExist)
							{
								$lastInsertedStateId = $stateExist->state_id;
							} else {
								$stateObject = new State();
								$stateObject->state_name = $countryValue['text'];
								$stateObject->country_id = $lastInsertedCountryId;
								$stateObject->save();
								$lastInsertedStateId = $stateObject->state_id;
							}
							$cityExist = City::
								where("city_name",$countryValue['text'])
								->where("state_id",$lastInsertedStateId)
								->where("country_id",$lastInsertedCountryId)
								->get()->first();
							if(!$cityExist)
							{
								$cityObject = new City();
								$cityObject->city_name = $countryValue['text'];
								$cityObject->state_id = $lastInsertedStateId;
								$cityObject->county_id = 0;
								$cityObject->country_id = $lastInsertedCountryId;
								$cityObject->save();
								$lastInsertedCityId = $cityObject->city_id;
							}
						}
					}
				}
			}
		}
	}
}
