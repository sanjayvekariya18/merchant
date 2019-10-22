<?php
namespace App\Http\Traits;

use Illuminate\Http\Request;

use App\Permission;
use App\Approval;
use App\Location;

use App\City;
use App\County;
use App\State;
use App\Countries;

use App\Merchant;
use App\Staff;
use App\Customer;
use App\People;
use App\Payee;

use App\Account;
use App\Wallet;
use App\Asset;
use App\Timezone;
use App\Limits_apikey;

use Auth;
use Config;
use DB;

use App\Merchant_type;
use App\Merchant_city_list;
use App\Identity_table_type;
use App\Identity_type;
use App\Location_list;

use App\Menus;
use App\Database_manager;
use App\Menus_database_manager;

use Carbon\Carbon;
use URL;
use DateTime;
use DateTimeZone;
use App\Portal_social_profile;
use App\Portal_social_api;
use App\Portal_social_environment;
use App\Social_connection_type;

use App\Helpers\ConnectionManager;
use App\Environment;
const MERCHANT_TABLE_IDENTITY_TYPE = 8;
trait PermissionTrait {
	
	static $secret_key     = "SoHyper2018!";
	static $encrypt_method = "AES-256-CBC";
	static $hase_generator = "sha256";

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->middleware('auth');
		$this->middleware(function ($request, $next) {
			$this->merchantId = session()->has('merchantId') ? session()->get('merchantId') :"";
			$this->locationId = session()->has( 'locationId' ) ? session()->get( 'locationId' ) : '';
			$this->roleId = session()->has('role') ? session()->get('role') :"";
			$this->staffId = session()->has('staffId') ? session()->get('staffId') :"";
			$this->staffName = session()->has('staffName') ? session()->get('staffName') :"";			

			return $next($request);
		});
	}

	public function issetHashPassword(){
		$user = Auth::user();
		return ($user->password != null || !empty($user->password)) ? true : false;
	}
	
	public function permissionDetails($controllerName, $contollerAction = null) {
		
		$hase_permissions = DB::table('group_permissions')
			->join('identity_group_list', 'identity_group_list.group_id', '=', 'group_permissions.group_id')
		   	->where('identity_group_list.identity_id', session('staffId'))
  			->where('identity_group_list.identity_table_id', session('identity_table_id'))
  			->where("group_permissions.group_id",'!=',0)
  			->where("group_permissions.status",1)
		   	->select('group_permissions.group_id as staff_group_id','group_permissions.group_name as staff_group_name', 'group_permissions.permissions')
		   	->get();

		$permissions = array();	    

		foreach ($hase_permissions as $group) {

			$permissions = self::array_merge_recursive_distinct($permissions,unserialize($group->permissions));

		}

		if (array_key_exists($controllerName,$permissions)){
			return (in_array($contollerAction, $permissions[$controllerName])) ? true : false;
		}else{
			return false;
		}
	}

	public static function getPermission($controllerName) {
		$hase_permissions = DB::table('group_permissions')
			->join('identity_group_list', 'identity_group_list.group_id', '=', 'group_permissions.group_id')
		   	->where('identity_group_list.identity_id', session('staffId'))
  			->where('identity_group_list.identity_table_id', session('identity_table_id'))
  			->where("group_permissions.group_id",'!=',0)
  			->where("group_permissions.status",1)
		   	->select('group_permissions.group_id as staff_group_id','group_permissions.group_name as staff_group_name', 'group_permissions.permissions')
		   	->get();

		$permissions = array();	    

		foreach ($hase_permissions as $group) {

			$gpermission = unserialize($group->permissions);
			$permissions = self::array_merge_recursive_distinct($permissions,$gpermission);

		}

		if (array_key_exists($controllerName,$permissions)){
			return $permissions[$controllerName];
		}else{
			return array();
		}
	}
	
	public static function array_merge_recursive_distinct(array $array1, array $array2)
	{
	    $merged = $array1;
	    foreach ($array2 as $key => $value)
	    {
	        if (is_array($value) && isset($merged[$key]) && is_array($merged[$key]))
	        {
	            $merged[$key] = self::array_merge_recursive_distinct($merged[$key], $value);
	        }
	        else
	        {
	            $merged[$key] = $value;
	        }
	    }
	    return $merged;
	}


	public static function getUserPermissions(){

		$hase_permissions = DB::table('group_permissions')
				->join('identity_group_list', 'identity_group_list.group_id', '=', 'group_permissions.group_id')
			    ->where('identity_group_list.identity_id', session('staffId'))
  				->where('identity_group_list.identity_table_id', session('identity_table_id'))
  				->where("group_permissions.group_id",'!=',0)  
  				->where("group_permissions.status",1)				
			    ->select('group_permissions.group_id as staff_group_id','group_permissions.group_name as staff_group_name', 'group_permissions.permissions')
			    ->get();

		$permissions = array();	    

		foreach ($hase_permissions as $group) {

			$permissions = self::array_merge_recursive_distinct($permissions,unserialize($group->permissions));

		}

		return $permissions;        
	}	
	
	public static function getLocations($merchantID)
	{
		$locationDetails = Location_list::
            select('list_id as location_id',DB::raw('(CASE WHEN (postal.postal_premise != "" and postal.postal_subpremise != "") THEN CONCAT(postal.postal_premise,", ",SUBSTR(postal.postal_subpremise,1,10))
                      WHEN (postal.postal_premise != "") THEN postal.postal_premise                              
                      WHEN (postal.postal_subpremise !="") THEN SUBSTR(postal.postal_subpremise,1,10)
                      ELSE CONCAT(postal.postal_street_number," ",postal.postal_route)   
                END) as location_name'))
            ->leftjoin('postal','postal.postal_id','location_list.postal_id')
            ->where('location_list.identity_table_id','=',8)
            ->where('location_list.identity_id','=',$merchantID)
            ->get();
		return $locationDetails;
	}

	public static function getMerchantCities($merchantID)
	{
		$merchantCities = City::distinct()
            ->select('location_city.city_id','location_city.city_name as city_name')
            ->leftjoin('location_list','location_city.city_id','=','location_list.location_city_id')
            ->leftjoin('merchant','merchant.identity_id','=','location_list.identity_id')
            ->where('location_list.identity_table_id','=',MERCHANT_TABLE_IDENTITY_TYPE)
            ->where('merchant.merchant_id','=',$merchantID)
            ->get();
		return $merchantCities;
	}

	public static function getStaffGroups($merchantID,$cityID)
	{
		$staffGroups = Staff::
					distinct()
					->select('staff_groups.staff_group_id','staff_group_name')
					->join('location_city','location_city.city_id','staffs.location_city_id')
					->join('staff_groups','staff_groups.staff_group_id','staffs.staff_group_id')
					->where('staffs.merchant_id',$merchantID)
					->where('staffs.location_city_id',$cityID)->get();
		return $staffGroups;
	}

	public static function getStaffs($merchantID,$cityID)
	{
		$staffs = Staff::
					select('staff_id','staff_name')
					->where('staffs.merchant_id',$merchantID)
					->where('staffs.location_city_id',$cityID)
					->get();
		return $staffs;
	}

	public static function getMerchants()
	{
		$merchants = Merchant::select(
							'merchant.*',
							'identity_merchant.identity_name',
							'identity_merchant.identity_code'
							)
					->join('identity_merchant','identity_merchant.identity_id','merchant.identity_id')
					->where('merchant_id','>',0)
					->get();
		return $merchants;
	}
	public static function getStaff()
	{
					$staffs = Staff::select(
							'staff.*',
							'identity_staff.identity_name',
							'identity_staff.identity_code'
							)
					->join('identity_staff','identity_staff.identity_id','staff.identity_id')
					->where('staff_id','>',0)
					->get();
		return $staffs;
	}
	public static function getCustomers()
	{
		$customers = Customer::select(
							'customers.*',
							'identity_customer.identity_name',
							'identity_customer.identity_code'
							)
					->join('identity_customer','identity_customer.identity_id','customers.identity_id')
					->where('customer_id','>',0)
					->get();
		return $customers;
	}

	public static function getPeoples()
	{
		$peoples = People::select(
							'people.*',
							'identity_people.identity_name',
							'identity_people.identity_code'
							)
					->join('identity_people','identity_people.identity_id','people.identity_id')
					->where('people_id','>',0)
					->get();
		return $peoples;
	}

	public static function getPayees()
	{
		$payees = Payee::select(
							'payee.*',
							'identity_payee.identity_name',
							'identity_payee.identity_code'
							)
					->join('identity_payee','identity_payee.identity_id','payee.identity_id')
					->where('payee_id','>',0)
					->get();
		return $payees;
	}

	public static function getAccounts($flag=1)
	{
		$accounts = Account::select(
							'account.*',
							'identity_account.identity_name',
							'identity_account.identity_code'
							)
					->join('identity_account','identity_account.identity_id','account.identity_id')
					->where('account_id','>=',$flag)
					->get();
		return $accounts;
	}

	public static function getAssets($flag=1)
	{
		$assets = Asset::select(
							'asset.*',
							'identity_asset.identity_name as asset_name',
							'identity_asset.identity_code as asset_code'
							)
					->join('identity_asset','identity_asset.identity_id','asset.identity_id')
					->where('asset_id','>=',$flag)
					->get();
		return $assets;
	}

	public static function getCountries()
	{
		return Countries::all();
	}
	

	public static function getStates($countryId = NULL)
	{
		if($countryId != NULL){
			$where['location_city.country_id'] = $countryId;
		}else{
			$where = array();
		}

		$states = State::
		select('location_state.*')
		->leftjoin('location_city', 'location_state.state_id', 'location_city.state_id')
		           ->orderBy('location_state.state_name', 'ASC')->groupBy('state_name')
		           ->where(function($q) use ($where){
		foreach($where as $key => $value){
		$q->where($key, $value);
		}
		})->get();

		return $states;
	}

	public static function getCounties($stateId = NULL)
	{
		if($stateId != NULL){
			$where['location_city.state_id'] = $stateId;
		}else{
			$where = array();
		}
		$counties = County::
		select('location_county.*')
		->leftjoin('location_city', 'location_county.county_id', 'location_city.county_id')
		           ->orderBy('location_county.county_name', 'ASC')->groupBy('county_name')
		           ->where(function($q) use ($where){
		foreach($where as $key => $value){
			$q->where($key, $value);
		}
		})->get();
		return $counties;
	}

	public static function getCities($countyId = NULL)
	{

		if($countyId != NULL){
			$where['location_city.county_id'] = $countyId;
		}else{
			$where = array();
		}
		$cities = City::
		select('location_city.*')
		           ->where(function($q) use ($where){
		foreach($where as $key => $value){
			$q->where($key, $value);
		}
		})->get();
		return $cities;
	}

	public static function getWallets()
	{
		return Wallet::where('wallet_id','>',0)->get();
	}

	public static function user_timezone_offset_string()
	{
		$timezone_name = timezone_name_from_abbr('', $_COOKIE['timeZoneOffset'] * 3600, false);
		$offset = timezone_offset_get( new DateTimeZone( $timezone_name ), new DateTime());
	    return sprintf( "%s%02d:%02d", ( $offset >= 0 ) ? '+' : '-', abs( $offset / 3600 ), abs( $offset % 3600 )/60 );
	}


	public static function covertToLocalTz($time)
	{
		$timezone_name = timezone_name_from_abbr('', $_COOKIE['timeZoneOffset'] * 3600, false);

		$localTimeZoneFomat = new DateTime(date('Y-m-d H:i:s', $time));
        $localTimeZoneFomat->setTimezone(new DateTimeZone($timezone_name));
        $localDateTimeDetails['date']= $localTimeZoneFomat->format('d M Y');
        $localDateTimeDetails['time']= $localTimeZoneFomat->format('H:i:s');
        return json_encode($localDateTimeDetails);
	}

	public static function covertToUtcTz($time)
	{
		$utcTimeZoneFomat = new DateTime(date('Y-m-d H:i:s', $time));
        $utcTimeZoneFomat->setTimezone(new DateTimeZone('UTC'));
        $utcDateTimeDetails['date']= $utcTimeZoneFomat->format('Ymd');
        $utcDateTimeDetails['time']= $utcTimeZoneFomat->getTimestamp();
        return json_encode($utcDateTimeDetails);
	}
	public static function getTimeZoneId()
	{
		$timeZone = Config::get('app.timezone', 'UTC');
        $timeZoneDetails = Timezone::where('timezone_name','=',$timeZone)->first();
        if(!$timeZoneDetails)
        {
            $timeZoneDetails = Timezone::where('timezone_name','=','UTC')->first();
            
        }
        if(!$timeZoneDetails)
        {
            $timeZoneDetails->timezone_id = 1;
        }
        return $timeZoneDetails->timezone_id;
	}
	public static function convertIntoTime($timeString){
		$totalMinute = $timeString / 60;
		$totalHours = floor($totalMinute / 60);
		$minuteFormat = $totalMinute % 60;
		if(strlen($minuteFormat) == 1)
		{
			$minuteFormat = '0'.$minuteFormat;
		}
		return $totalHours.":".$minuteFormat;

	}

	public static function convertIntoDate($dateString){
		if(strlen($dateString) == 8){
			$dateObj = substr_replace(substr_replace($dateString, '-', 4, 0), '-', 7, 0);
			return date('d M Y',strtotime($dateObj));
		}else{
			return 0;
		}
	}

	public static function getMerchantType(){

		return Merchant::
					select('merchant.merchant_type_id','merchant_type_name')
					->join('merchant_type','merchant_type.merchant_type_id','merchant.merchant_type_id')
					->where('merchant.merchant_id',session('merchantId'))
					->get()->first();
	}

	public static function getTableType($tableTypeId){

		return  Identity_table_type::findOrfail($tableTypeId);
	}

	public static function getIdentityTableType($originTableName,$identityId){

		return  DB::table($originTableName)
					->where('identity_id',$identityId)
					->get()->first();
	}

	public static function getApprovalStatusId($statusCode){

		$approvalStatus = DB::table('approval_status_id')
						->where('status_code',$statusCode)
						->get()->first();
		return $approvalStatus->status_id;
	}

	public static function humanTiming ($datetime)
	{
		$currentDate =Carbon::now();

		$time = strtotime($currentDate) - strtotime($datetime);

		$time = ($time<1)? 1 : $time;
		$tokens = array (
			31536000 => 'year',
			2592000 => 'month',
			604800 => 'week',
			86400 => 'day',
			3600 => 'hour',
			60 => 'minute',
			1 => 'second'
		);

		foreach ($tokens as $unit => $text) {
			if ($time < $unit) continue;
			$numberOfUnits = floor($time / $unit);
			return $numberOfUnits.' '.$text.(($numberOfUnits>1)?'s':'');
		}

	}

	public static function getTimezoneName($timeZoneOffset){

		return timezone_name_from_abbr('', $timeZoneOffset * 3600, false);
	}

	public static function covertToUserTz($datetime,$timezone)
	{
		$utcTimeZoneFomat = new DateTime($datetime);
		$utcTimeZoneFomat->format('Y-m-d H:i:s');		
        $userTimeZone = new DateTimeZone($timezone);        
        $utcTimeZoneFomat->setTimezone($userTimeZone); 
        $userDateTime = $utcTimeZoneFomat->format('Y-m-d H:i:s');     
        return $userDateTime;
	}

	public static function getOffsetFromTzName($timezone_name){
		$offset = timezone_offset_get( new DateTimeZone( $timezone_name ), new DateTime());
	    return sprintf( "%s%02d:%02d", ( $offset >= 0 ) ? '+' : '-', abs( $offset / 3600 ), abs( $offset % 3600 )/60 );
	}

	public static function encrypt($string)
	{	
		$key    = hash(self::$hase_generator, self::$secret_key);
		$iv     = substr(hash(self::$hase_generator, self::$secret_key), 0, 16);  
	    $output = base64_encode(openssl_encrypt($string, self::$encrypt_method, $key, 0, $iv));
	    return $output;
	}

    public static function decrypt($string)
	{	
		$key    = hash(self::$hase_generator, self::$secret_key);
		$iv     = substr(hash(self::$hase_generator, self::$secret_key), 0, 16);   
	    $output = openssl_decrypt(base64_decode($string), self::$encrypt_method, $key, 0, $iv);
	    return $output;
	}

    public static function checkIpstackApi(){
    	try{
			$ipstackApikeys = Limits_apikey::
							select('id')
	                        ->where('provider_name','IPSTACK')
	                        ->where('status',0)
	                        ->whereRaw('datediff(curdate(),disable_date) >= 31')
	                        ->get()
	                        ->toArray();

	        $apiID = array();
	        if(count($ipstackApikeys) > 0){
	        	foreach ($ipstackApikeys as $value) {
	        		
	        		$apiID[] = $value['id'];
	        	}

	        	$updates = array(
	        		"total_limit" => 10000,
	        		"status" => 1,
	        		"disable_date" => null
	        	);

	        	Limits_apikey::
	        			whereIn('id', $apiID)
	        			->update($updates);
	        }
	        return array("type" => "success","message" => "Apikey recyle process complete");
	    }catch( \Exception $e){

            $exceptionMessage = ConnectionManager::renderPdoException($e->getCode());
            return array("type" => "error","message" => $exceptionMessage);
        }     
	}

	public static function getIpstackApiByKey($apikey){
		try{
			$apikey = self::encrypt($apikey);
			$connector = DB::table('limits_apikey')
                        ->where('provider_name','IPSTACK')
                        ->where('api_key',$apikey)
                        ->first();
            $connector->api_key = self::decrypt($connector->api_key);            
        	return $connector;                
        }catch( \Exception $e){

            $exceptionMessage = ConnectionManager::renderPdoException($e->getCode());
            return array("type" => "error","message" => $exceptionMessage);
        }
	}

	public static function getIpstackApi(){
		try{
			$connector = DB::table('limits_apikey')
	                        ->where('provider_name','IPSTACK')
	                        ->where('status',1)
	                        ->first();

	        $connector->api_key = self::decrypt($connector->api_key);

        	return $connector;                
       	}catch( \Exception $e){

            $exceptionMessage = ConnectionManager::renderPdoException($e->getCode());
            return array("type" => "error","message" => $exceptionMessage);
        } 	
	}

	public static function updateIpstackApiCounter($id){
		try{
			$connector = DB::table('limits_apikey')
						->where('id', $id)
						->decrement('total_limit',1);

        	return $connector;                
        }catch( \Exception $e){

            $exceptionMessage = ConnectionManager::renderPdoException($e->getCode());
            return array("type" => "error","message" => $exceptionMessage);
        } 	
	}

	public static function disableIpstackApiCounter($id){
		try{
			$connector = DB::table('limits_apikey')
            			->where('id', $id)
            			->update(['total_limit' => 0,'status' => 0,'disable_date' => date('Y-m-d')]);

        	return $connector;
        }catch( \Exception $e){

            $exceptionMessage = ConnectionManager::renderPdoException($e->getCode());
            return array("type" => "error","message" => $exceptionMessage);
        } 	                
	}

	public static function getUserIpAddress($ip){

		$api_result = array();
		$connector = self::getIpstackApi();

        if(isset($connector->api_key)){    

			// Initialize CURL:
			$ch = curl_init('http://api.ipstack.com/'.$ip.'?access_key='.$connector->api_key);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

			// Store the data:
			$json = curl_exec($ch);
			curl_close($ch);

			// Decode JSON response:
			$api_result = json_decode($json, true);	

			if(isset($api_result['ip'])){
				self::updateIpstackApiCounter($connector->id);					
			}else if(isset($api_result['error'])){
				self::disableIpstackApiCounter($connector->id);
				self::getUserIpAddress($ip);
			}	

		}
		return $api_result;	

	}

	public static function addActivityLog($action,$message){

		$ip = isset($_SERVER['REMOTE_ADDR'])?$_SERVER['REMOTE_ADDR'] ?: ($_SERVER['HTTP_X_FORWARDED_FOR'] ?: $_SERVER['HTTP_CLIENT_IP']):'127.0.0.1';

		$ipInformation = self::getUserIpAddress($ip);
		$timezone= self::user_timezone_offset_string();

		$userDatetime = json_decode(self::covertToLocalTz(time()));
		$usertime = date('h:i',strtotime($userDatetime->time));

		$activityLog = array(
			"merchant_id" => session('merchantId'),
			"user_id" => session('userId'),
			"action" => $action,
			"message" => $message,
			"ip_address" => isset($ipInformation['ip'])?$ipInformation['ip']:$ip,
			"user_timezone" => $timezone,
			"user_time" => $usertime,
			"user_city" => isset($ipInformation['city'])?$ipInformation['city']:"",
			"date_added" => Carbon::now()
		);

		DB::table('activities')->insert($activityLog);
	}

	public function addAdminForApprove($primaryId,$updatedColumns,$liveTable,$approvalStatus,$approvalUpdateStatus,$merchantId,$locationId,$approvalParent=null,$approvalGrouHash=null) {

		$createdAt =Carbon::now();
		$request_Date = str_replace('-', '', $createdAt->toDateString());
		$requestTimeFormat = $createdAt->toTimeString();
		$openTimeData = explode(":", $requestTimeFormat);
		$request_time = $openTimeData[0]*3600+$openTimeData[1]*60;
		if(!$approvalGrouHash)
		{
			$randomNumber = rand(11,11111111);
		} else {
			$randomNumber = $approvalGrouHash;
		}
		$approvalParentId = 0;
		$ignoreField = 1;
		foreach($updatedColumns as $updatedColumn)
		{
		   $Hase_approval = new Approval();
		   $Hase_approval->approval_parent = (isset($approvalParent))?$approvalParent:0;
		   $Hase_approval->request_Date = $request_Date;
		   $Hase_approval->request_time = $request_time;
		   $Hase_approval->request_staff_id = session('userId');
		   $Hase_approval->merchant_id = $merchantId;
		   $Hase_approval->merchant_location_id = $locationId;
		   $Hase_approval->request_table_live = $liveTable;
		   $Hase_approval->request_table_live_primary_id = $primaryId;
		   $Hase_approval->request_fields = $updatedColumn;
		   $Hase_approval->approval_grouphash = $randomNumber;
		   $Hase_approval->approval_status_id = $approvalStatus;
		   $Hase_approval->crud_id = $approvalUpdateStatus;
		   $Hase_approval->ignore = $ignoreField;
		   $Hase_approval->save();

		   $approvalParentId = $Hase_approval->approval_id;
		}
		$responseArray = array(
			'approvalParentId' => $approvalParentId,
			'groupHash' => $randomNumber
		);
		return $responseArray;
	}

	public static function random_num($size) {
        $alpha_key = '';
        $keys = range('A', 'Z');

        for ($i = 0; $i < 2; $i++) {
            $alpha_key .= $keys[array_rand($keys)];
        }

        $length = $size - 2;

        $key = '';
        $keys = range(0, 9);

        for ($i = 0; $i < $length; $i++) {
            $key .= $keys[array_rand($keys)];
        }

        return $alpha_key . $key;
    }

	public function updateAdminForApprove($livePrimaryId,$updatedColumns,$liveTable,$approvalStatus,$approvalUpdateStatus,$merchantId,$locationId) {
	   $createdAt =Carbon::now();
	   $request_Date = str_replace('-', '', $createdAt->toDateString());
	   $requestTimeFormat = $createdAt->toTimeString();
	   $openTimeData = explode(":", $requestTimeFormat);
	   $request_time = $openTimeData[0]*3600+$openTimeData[1]*60;
	   $randomNumber = rand(11,11111111);
	   $ignoreField = 1;
	   foreach($updatedColumns as $updatedColumn)
	   {
		   $Hase_approval = new Approval();
		   $Hase_approval->request_Date = $request_Date;
		   $Hase_approval->request_time = $request_time;
		   $Hase_approval->request_staff_id = session('userId');
		   $Hase_approval->merchant_id = $merchantId;
		   $Hase_approval->merchant_location_id = $locationId;
		   $Hase_approval->request_table_live = $liveTable;
		   $Hase_approval->request_table_live_primary_id = $livePrimaryId;
		   $Hase_approval->request_fields = $updatedColumn;
		   $Hase_approval->approval_grouphash = $randomNumber;
		   $Hase_approval->approval_status_id = $approvalStatus;
		   $Hase_approval->crud_id = $approvalUpdateStatus;
		   $Hase_approval->ignore = $ignoreField;
		   $Hase_approval->save();
	   }
	}

	public function addForApprove($primaryId,$updatedColumns,$liveTable,$stageTable,$approvalStatus,$approvalUpdateStatus,$merchantId,$locationId,$approvalParent=null,$approvalGrouHash=null) {

		$createdAt =Carbon::now();
		$request_Date = str_replace('-', '', $createdAt->toDateString());
		$requestTimeFormat = $createdAt->toTimeString();
		$openTimeData = explode(":", $requestTimeFormat);
		$request_time = $openTimeData[0]*3600+$openTimeData[1]*60;
		if(!$approvalGrouHash)
		{
			$randomNumber = rand(11,11111111);
		} else {
			$randomNumber = $approvalGrouHash;
		}
		$approvalParentId = 0;
		foreach($updatedColumns as $updatedColumn)
		{
		   $Hase_approval = new Approval();
		   $Hase_approval->approval_parent = (isset($approvalParent))?$approvalParent:0;
		   $Hase_approval->request_Date = $request_Date;
		   $Hase_approval->request_time = $request_time;
		   $Hase_approval->request_staff_id = session('userId');
		   $Hase_approval->merchant_id = $merchantId;
		   $Hase_approval->merchant_location_id = $locationId;
		   $Hase_approval->request_table_live = $liveTable;
		   $Hase_approval->request_table_stage = $stageTable;
		   $Hase_approval->request_table_stage_primary_id = $primaryId;
		   $Hase_approval->request_fields = $updatedColumn;
		   $Hase_approval->approval_grouphash = $randomNumber;
		   $Hase_approval->approval_status_id = $approvalStatus;
		   $Hase_approval->crud_id = $approvalUpdateStatus;

		   $Hase_approval->save();
		   $approvalParentId = $Hase_approval->approval_id;
		}
		$responseArray = array(
			'approvalParentId' => $approvalParentId,
			'groupHash' => $randomNumber
		);
		return $responseArray;
	}

	public function updateForApprove($livePrimaryId,$stagePrimaryId,$updatedColumns,$liveTable,$stageTable,$approvalStatus,$approvalUpdateStatus,$merchantId,$locationId) {
	   $createdAt =Carbon::now();
	   $request_Date = str_replace('-', '', $createdAt->toDateString());
	   $requestTimeFormat = $createdAt->toTimeString();
	   $openTimeData = explode(":", $requestTimeFormat);
	   $request_time = $openTimeData[0]*3600+$openTimeData[1]*60;
	   $randomNumber = rand(11,11111111);
	   foreach($updatedColumns as $updatedColumn)
	   {
		   $Hase_approval = new Approval();
		   $Hase_approval->request_Date = $request_Date;
		   $Hase_approval->request_time = $request_time;
		   $Hase_approval->request_staff_id = session('userId');
		   $Hase_approval->merchant_id = $merchantId;
		   $Hase_approval->merchant_location_id = $locationId;
		   $Hase_approval->request_table_live = $liveTable;
		   $Hase_approval->request_table_live_primary_id = $livePrimaryId;
		   $Hase_approval->request_table_stage = $stageTable;
		   $Hase_approval->request_table_stage_primary_id = $stagePrimaryId;
		   $Hase_approval->request_fields = $updatedColumn;
		   $Hase_approval->approval_grouphash = $randomNumber;
		   $Hase_approval->approval_status_id = $approvalStatus;
		   $Hase_approval->crud_id = $approvalUpdateStatus;
		   $Hase_approval->save();
	   }
	}

	public function getRootParentId($approvalId){

		$parentId = DB::table('approval')->where('approval_id',$approvalId)->first()->approval_parent;

		if($parentId == 0){
			return $approvalId;
		}else{
			return $this->getRootParentId($parentId);
		}

	}

	public function updateStageToLive($approvalStatus,$approval_id)
	{
		
		$statusList=DB::table('approval_status')
				->where('approval_status_id',$approvalStatus)->get()->first();
		$createdAt =Carbon::now();
		$approve_Date = str_replace('-', '', $createdAt->toDateString());
		$requestTimeFormat = $createdAt->toTimeString();
		$openTimeData = explode(":", $requestTimeFormat);
		$approve_time = $openTimeData[0]*3600+$openTimeData[1]*60;
		
		$hase_approval = Approval::findOrfail($approval_id);
		$approvalGrouHash = DB::table('approval')->where('approval_grouphash','=',$hase_approval->approval_grouphash)->count();
		$approvalIdList = DB::table('approval')->where('approval_grouphash','=',$hase_approval->approval_grouphash)->where('approval_status_id','=',5)->count();
		
		$staffUrl = "/hase_staff/".session('staffId')."/edit";		
		if($statusList->approval_status_name == 'Accepted' && $approvalGrouHash == $approvalIdList)
		{
			$action = 'approved';
			$hase_approval_parent = $this->getRootParentId($approval_id);
			$hase_approval = Approval::
							where('approval_id',$hase_approval_parent)
							->get()->first();

			/*$hase_approval_parent = $hase_approval->approval_parent;*/

			/*if($hase_approval_parent != 0){
				$hase_approval = Approval::
							where('approval_id',$hase_approval_parent)
							->get()->first();
			}*/

			$hase_approval_live_primary_id = $hase_approval->request_table_live_primary_id;
			$hase_approval_stage_primary_id = $hase_approval->request_table_stage_primary_id;
			$hase_approval_live_table = DB::table('identity_table_type')
                                ->select('table_code')
                                ->where('type_id',$hase_approval->request_table_live)
                                ->first();

            $hase_approval_stage_table = DB::table('identity_table_type')
                                ->select('table_code')
                                ->where('type_id',$hase_approval->request_table_stage)
                                ->first();

			$hase_approval_live_primary_name_object = DB::table('approval_key')
													->select('key_primary')
													->where('key_table','=',$hase_approval_live_table->table_code)
													->get()->first();

			$hase_approval_live_primary_name = $hase_approval_live_primary_name_object->key_primary;

			$stageTableObject = DB::table($hase_approval_stage_table->table_code)
				->where($hase_approval_live_primary_name,'=',$hase_approval_stage_primary_id)
				->get()->first();

			$stageTable = (array)$stageTableObject;
			unset($stageTable['staff_id'],$stageTable[$hase_approval_live_primary_name]);
			unset($stageTable['identity_type_id']);

			$approvalCrudStatus = DB::table("approval_crud_status")
									->where('crud_status_id',$hase_approval->crud_id)
									->get()->first();

			switch ($approvalCrudStatus->crud_status_code) {

				case "insert":

					$liveTablePrimaryId = DB::table($hase_approval_live_table->table_code)
					->insertGetId($stageTable, $hase_approval_live_primary_name);
					
					$approvalUpdateArray = array(
							'request_table_live_primary_id' => $liveTablePrimaryId,
							'approval_date'=>$approve_Date,
							'approval_time'=>$approve_time,
							'approval_staff_id'=>session('staffId'),
							'approval_status_id'=>$approvalStatus
					);

					Approval::
							where('approval_grouphash', '=', $hase_approval->approval_grouphash)
							->where('approval_parent','=',0)
							->update($approvalUpdateArray);

					$childRecords = Approval::
							where('approval_grouphash',$hase_approval->approval_grouphash)
							->where('approval_parent','!=',0)
							->get();

					if(count($childRecords)){

						$childRecordArrays = array();

						foreach ($childRecords as $childRecord) {
							$request_table_stage = DB::table('identity_table_type')
					                                ->select('table_code')
					                                ->where('type_id',$childRecord->request_table_stage)
					                                ->first()->table_code;
							$childRecordArrays[$request_table_stage][$childRecord->request_table_stage_primary_id] = $childRecord->toArray();
						}
						foreach ($childRecordArrays as $childRecordArray){
							foreach ($childRecordArray as $childRecordKey=>$childRecordValue){

								$hase_approval_child_live_primary_name_object = 
										DB::table('approval_key')
										->select('key_primary')
										->join('identity_table_type','identity_table_type.table_code','=','approval_key.key_table')
										->where('identity_table_type.type_id','=',$childRecordValue['request_table_live'])
										->first();

								$hase_approval_child_live_primary_name = $hase_approval_child_live_primary_name_object->key_primary;

								$request_table_stage = DB::table('identity_table_type')
					                                ->select('table_code')
					                                ->where('type_id',$childRecordValue['request_table_stage'])
					                                ->first()->table_code;

					            $childRecordValue['request_table_stage'] = $request_table_stage;

								$stageTableObject = 
										DB::table($childRecordValue['request_table_stage'])
										->where($hase_approval_child_live_primary_name,'=',$childRecordKey)
										->get()->first();

								$stageTable = (array)$stageTableObject;

								unset($stageTable['staff_id'],$stageTable[$hase_approval_child_live_primary_name]);

								$request_table_live = DB::table('identity_table_type')
					                                ->select('table_code')
					                                ->where('type_id',$childRecordValue['request_table_live'])
					                                ->first()->table_code;

					            $childRecordValue['request_table_live'] = $request_table_live;                    

    							$foreignConstraintsDetail = 
									DB::select("SELECT * FROM information_schema.KEY_COLUMN_USAGE WHERE REFERENCED_TABLE_NAME IS NOT NULL AND TABLE_NAME = '$request_table_live' AND TABLE_SCHEMA = 'dev_v400'");

								/*$foreignConstraintsDetail = 
									DB::select("SELECT * FROM information_schema.KEY_COLUMN_USAGE WHERE REFERENCED_TABLE_NAME = '$request_table_live' AND REFERENCED_COLUMN_NAME = '$hase_approval_live_primary_name' AND TABLE_SCHEMA = 'dev_v400'");*/
								/*echo "<pre>";*/
								
								$middlechildRecords = Approval::
									where('approval_id',$childRecordValue['approval_parent'])
									->get()->first();

								if($middlechildRecords->approval_parent != 0)
								{
									$hase_approval_live_table = DB::table('identity_table_type')
					                                ->select('table_code')
					                                ->where('type_id',$middlechildRecords['request_table_live'])
					                                ->first();
								}
								foreach ($foreignConstraintsDetail as $key => $value) {
									if($hase_approval_live_table->table_code == $value->REFERENCED_TABLE_NAME ||  $value->REFERENCED_COLUMN_NAME == 'special_id'){										
										$lastRecordTableName = DB::table($value->REFERENCED_TABLE_NAME)->orderBy($value->REFERENCED_COLUMN_NAME, 'desc')->first();
										$referencedColumnName = $value->REFERENCED_COLUMN_NAME;
										$stageTable[$value->COLUMN_NAME] = $lastRecordTableName->$referencedColumnName;
									}
								}

								$childLiveTablePrimaryId = 
									DB::table($childRecordValue['request_table_live'])
									->insertGetId($stageTable, $hase_approval_child_live_primary_name);

								$approvalUpdateArray = array(
									'request_table_live_primary_id'=>$childLiveTablePrimaryId,
									'approval_date'=>$approve_Date,
									'approval_time'=>$approve_time,
									'approval_staff_id'=>session('staffId'),
									'approval_status_id'=>$approvalStatus
								);

								Approval::
										where('approval_grouphash', '=', $hase_approval->approval_grouphash)
										->where('approval_parent','!=',0)
										->where('request_table_stage_primary_id','=',$childRecordKey)
										->update($approvalUpdateArray);
							}
						}
					}
					
					$message = "<a href='".URL::to($staffUrl)."'>".$this->staffName."</a> <strong>".$action."</strong><strong> Record</strong>";
					$this->addActivityLog($action,$message);

					break;

				case "modify":
					foreach ($stageTable as $stageTableKey => $stageTableValue) {

						if((string)$stageTableValue != ''){
							$updatedStageArray[$stageTableKey] = $stageTableValue;
						}
					}

					DB::table($hase_approval_live_table->table_code)
						->where($hase_approval_live_primary_name, $hase_approval_live_primary_id)
						->update($updatedStageArray);
					
					$liveTablePrimaryId = $hase_approval_live_primary_id;

					$approvalUpdateArray = array(
								'approval_date'=>$approve_Date,
								'approval_time'=>$approve_time,
								'approval_staff_id'=>session('staffId'),
								'approval_status_id'=>$approvalStatus
							);

					Approval::
							where('approval_grouphash', '=', $hase_approval->approval_grouphash)
							->update($approvalUpdateArray);

					$message = "<a href='".URL::to($staffUrl)."'>".$this->staffName."</a> <strong>".$action."</strong><strong> Record</strong>";
					$this->addActivityLog($action,$message);
					break;

				case "delete":

					$liveTablePrimaryId = DB::table($hase_approval_live_table->table_code)
						->where($hase_approval_live_primary_name,$hase_approval_live_primary_id)
						->delete();

					$approvalUpdateArray = array(
						'approval_date'=>$approve_Date,
						'approval_time'=>$approve_time,
						'approval_staff_id'=>session('staffId'),
						'approval_status_id'=>$approvalStatus
					);

					Approval::
						where('approval_grouphash', $hase_approval->approval_grouphash)
						->update($approvalUpdateArray);
					break;
			}			

		} else {
			$approvalUpdateArray = array(
					'approval_date'=>$approve_Date,
					'approval_time'=>$approve_time,
					'approval_staff_id'=>session('staffId'),
					'approval_status_id'=>$approvalStatus
				);

			Approval::
						where('approval_id', '=', $hase_approval->approval_id)
						->update($approvalUpdateArray);
			$action = 'Rejected';
			$message = "<a href='".URL::to($staffUrl)."'>".$this->staffName."</a> <strong>".$action."</strong><strong> Record</strong>";
			$this->addActivityLog($action,$message);
		}
	}

	public static function getActiveUser($connectorName){
		return $activiteUserStatus = Portal_social_profile::
			select('portal_social_profile.user_active','portal_social_profile.user_screen_name','portal_social_profile.profile_image')
			->join('portal_social_api','portal_social_api.connectorid','portal_social_profile.connector_id')
			->where('portal_social_profile.user_id',session('userId'))
			->where('portal_social_profile.user_active','yes')
			->where('portal_social_api.connectorname',$connectorName)
			->get()->first();
	}

	public static function getConnectorUrl($connectorName){
		return URL::to('/').'/'.$connectorName;
	}

	/**
     * This function gets api key of users
     * @param string $connectorName
     * @return string
     */
    public static function getApiKey($connectorName) {
    	$hostname = \Request::getHttpHost();
    	$connectorApiKey = Portal_social_api::
			select('portal_social_environment.api_key')
			->join('portal_social_environment','portal_social_environment.connectorid','portal_social_api.connectorid')
			->join('environment','environment.environment_id','portal_social_environment.environment_id')
			->where('environment.hostname',$hostname)
			->where('portal_social_api.connectorname',$connectorName)
			->get()->first();
		return self::decrypt($connectorApiKey->api_key);
    }

    public static function getSocialConnectorStatus($connectorName) {
    	try{
    		$connectorStatus = Portal_social_api::
			select('portal_social_api.api_active')
			->where('portal_social_api.connectorname',$connectorName)
			->get()->first();

			if(strcmp($connectorStatus->api_active,"yes")==0){
				return true;
			}else{
				return false;
			}	
    	}catch( \Exception $e){
    		return $e->getMessage();
    	}
    	
    }
    
    /**
     * This function gets active api secret key
     * @param string $connectorName
     * @return MIxed
     */
    public static function getApiSecretKey($connectorName) {
    	$hostname = \Request::getHttpHost();
        $connectorApiKey = Portal_social_api::
			select('portal_social_environment.api_secret_key')
			->join('portal_social_environment','portal_social_environment.connectorid','portal_social_api.connectorid')
			->join('environment','environment.environment_id','portal_social_environment.environment_id')
			->where('environment.hostname',$hostname)
			->where('portal_social_api.connectorname',$connectorName)
			->get()->first();
		return $connectorApiKey->api_secret_key;
    }

    // CHECK USER API ID EXIST RATHER THEN CURRENT LOGIN USER.

    public static function checkUserApiId($userApiId){

    	$userExist = Portal_social_profile::
			select('social_user_id')
			->where('user_id','!=',session('userId'))
			->where('user_api_id',$userApiId)
			->get()->first();

		return	isset($userExist->social_user_id) ? true : false;
    }


    /**
     * This function gets active api secret key
     * @param string $connectorName
     * @return MIxed
     */
    public static function storeConnectorUserDetails($connectorName=null, $userApiId=null, $userName=null, $oauthToken=null, $secretOauthToken=null, $profileImage=null, $getGender=null, $userCity=null) {
    	try{
	    	$connectorsIdentity=self::getConnectorId($connectorName);
	    	$userExist = Portal_social_profile::
				select('portal_social_profile.social_user_id')
				->where('portal_social_profile.user_id',session('userId'))
				->where('portal_social_profile.connector_id',$connectorsIdentity)
				->get()->first();
			if($userExist)
			{
				$portalSocialProfile = Portal_social_profile::findOrfail($userExist->social_user_id);
				$portalSocialProfile->connector_id = $connectorsIdentity;
				$portalSocialProfile->user_api_id = $userApiId;
				$portalSocialProfile->user_active = 'yes';
				$portalSocialProfile->user_screen_name = $userName;
				$portalSocialProfile->oauth_token = $oauthToken;
				$portalSocialProfile->oauth_token_secret = $secretOauthToken;
				$portalSocialProfile->profile_image = $profileImage;
				$portalSocialProfile->gender = $getGender;
				$portalSocialProfile->city = $userCity;
				$portalSocialProfile->save();

			} else {
				$portalSocialProfile = new Portal_social_profile();
				$portalSocialProfile->user_id = session('userId');
				$portalSocialProfile->connector_id = $connectorsIdentity;
				$portalSocialProfile->user_api_id = $userApiId;
				$portalSocialProfile->user_active = 'yes';
				$portalSocialProfile->user_screen_name = $userName;
				$portalSocialProfile->oauth_token = $oauthToken;
				$portalSocialProfile->oauth_token_secret = $secretOauthToken;
				$portalSocialProfile->profile_image = $profileImage;
				$portalSocialProfile->gender = $getGender;
				$portalSocialProfile->city = $userCity;
				$portalSocialProfile->save();
			}

			return array("type" => "success","message" => $connectorName." connected");

	    }catch( \Exception $e){

            $exceptionMessage = ConnectionManager::renderPdoException($e->getCode());
            return array("type" => "error","message" => $exceptionMessage);
        }
    }

    /**
     * This function includes query which fetches connector identity
     * @param string $connectorName
     * @return mixed
     */
    public static function getConnectorId($connectorName) {
    	$connectorDetails = Portal_social_api::
			select('portal_social_api.connectorid')
			->where('portal_social_api.connectorname',$connectorName)
			->get()->first();
		return $connectorDetails->connectorid;
    }

    /**
     * This function selects user details of active user
     * @param string $connectorName
     * @return MIxed
     */
    public static function getActiveUserDetails($connectorName) {
    	$activeUserDetails = Portal_social_profile::
			select(
				'portal_social_profile.user_screen_name',
				'portal_social_profile.profile_image',
				'portal_social_profile.connector_id',
				'portal_social_profile.social_user_id',
				'portal_social_profile.oauth_token'
			)
			->join('portal_social_api','portal_social_profile.connector_id','portal_social_api.connectorid')
			->where('portal_social_profile.user_id',session('userId'))
			->where('portal_social_api.connectorname',$connectorName)
			->where('portal_social_profile.user_active','yes')
			->get()->first();
		return $activeUserDetails;
    }

    /**
     * This function deactivates specified social-connector from sohyper_social_profile
     * @param string $userId
     * @param string $connectorName
     * @return boolean
     */
    public static function deactivateConnector($userId, $connectorName) {
    	try{
	    	$connectorsIdentity=self::getConnectorId($connectorName);
	    	$userExist = Portal_social_profile::
				select('portal_social_profile.social_user_id')
				->where('portal_social_profile.user_id',$userId)
				->where('portal_social_profile.connector_id',$connectorsIdentity)
				->get()->first();
	        $portalSocialProfile = Portal_social_profile::findOrfail($userExist->social_user_id);
			$portalSocialProfile->user_active = 'no';
			$portalSocialProfile->save();

			return array("type" => "success","message" => $connectorName." Disconnected");

	    }catch( \Exception $e){

            $exceptionMessage = ConnectionManager::renderPdoException($e->getCode());
            return array("type" => "error","message" => $exceptionMessage);
        }
    }

    /**
     * This function is used to get current user access token 
     * @param string $userEmail
     * @param int $identityId
     * @return string $tokenData
     */
    public static function getCurrentUserAccessToken($userId,$connectorName) {
    	$connectorsIdentity=self::getConnectorId($connectorName);
    	$selectOauthToken = Portal_social_profile::
			select('portal_social_profile.*')
			->where('portal_social_profile.user_id',$userId)
			->where('portal_social_profile.connector_id',$connectorsIdentity)
			->where('portal_social_profile.user_active','yes')
			->get()->first();
        return $selectOauthToken;
    }

    public static function getCurrentEnvironment(){
    	try{
    		$hostname = \Request::getHttpHost();
    		$environmentData = Environment::where("hostname",$hostname)->first();
    		return $environmentData;

    	}catch( \Exception $e){
            
            return $e->getMessage();
        }
    }

    public static function getSocialConnectionTypeId($type){
    	try{

    		$social_connection_type = Social_connection_type::where("type_name",$type)->first();
    		return $social_connection_type->type_id;

    	}catch( \Exception $e){
            
            return $e->getMessage();
        }
    }
    
    public static function setSocialConnectorService($connectorName,$connectorType)
    {
    	try{
    		$loginTypeId = 1;
    		$hostname = \Request::getHttpHost();
	    	$connector = Portal_social_environment::
	    					select("connectorname","api_key","api_secret_key")
	    					->join("portal_social_api",'portal_social_api.connectorid','portal_social_environment.connectorid')
	    					->join("environment","environment.environment_id","portal_social_environment.environment_id")
	    					->where('hostname',$hostname)
	    					->where('portal_social_environment.type_id',$connectorType)
	    					->where('portal_social_api.connectorname',$connectorName)
	    					->first();

	        $serviceName = strtolower($connector->connectorname);

	        if($connectorType == $loginTypeId){
	        	$serviceRedirectUrl = url('/'.strtolower($connector->connectorname).'_login/connect');
	    	}else{	    		
	    		$serviceRedirectUrl = url('/'.strtolower($connector->connectorname).'_social/connect');
	    	}

	        if($serviceName == "facebook"){
	            $serviceRedirectUrl = str_replace("http://","https://",$serviceRedirectUrl);
	        }else{
	            $serviceRedirectUrl = str_replace("https://","http://",$serviceRedirectUrl);
	        }

	        config()->set(['services.'.$serviceName => [
	            'client_id' => self::decrypt($connector->api_key),
	            'client_secret' => $connector->api_secret_key,
	            'redirect' => $serviceRedirectUrl
			]]);

	    	return array("type" => "success","message" => "Social api configured");

	    }catch( \Exception $e){

            $exceptionMessage = ConnectionManager::renderPdoException($e->getCode());
            return array("type" => "error","message" => $exceptionMessage);
        }    
    }     

    public static function print_menu_provider_editor($menu,$activeConnector) {
		
		$hostname = \Request::getHttpHost();

		$str = '<li class="dd-item dd3-item" data-id="'.$menu->id.'">
			<button data-action="collapse" type="button" style="display: block;">Collapse</button>
			<button data-action="expand" type="button" style="display: none;">Expand</button>			
			<div class="dd3-content" style="padding-left:10px;"><i class="fa fa-cube"></i> '.$menu->name.'</div>';
		
		$childrens = Menus_database_manager::              
                select('menus_database_manager.*','database_manager.provider_name')
                ->join('database_manager','database_manager.id','menus_database_manager.provider_id')
                ->join('environment','environment.environment_id','database_manager.environment_id')
                ->where('menus_database_manager.menu_id',$menu->id)
                ->where('environment.hostname',$hostname)
                ->orderBy('menus_database_manager.priority','asc')
                ->get();
		
		if(count($childrens) > 0) {
			$str .= '<ol class="dd-list">';

			$isActiveFlag = 0;			
			foreach($childrens as $children) {				
				$isActive = in_array($children->provider_id, $activeConnector);
				if($isActive && !$isActiveFlag){
					$isActiveFlag = 1;
					$str .= '<li class="dd-item dd3-item" data-id="'.$children->id.'">
					<div class="dd-handle dd3-handle" style="background:mediumseagreen"></div>
					<div class="dd3-content" style="background:mediumseagreen" >'.$children->provider_name.'</div></li>';
				}else{
					$str .= '<li class="dd-item dd3-item" data-id="'.$children->id.'">
					<div class="dd-handle dd3-handle"></div>
					<div class="dd3-content">'.$children->provider_name.'</div></li>';
				}

				
			}
			$str .= '</ol>';
		}
		$str .= '</li>';
		return $str;
	}

	public static function getIdentityTableId($table_code){
		try{
			$identityTable = Identity_table_type::where("table_code",$table_code)->get()->first();	
			if(isset($identityTable->type_id)){
				return $identityTable->type_id;
			}else{
				return array("type" => "error","message" => "Identity table not found");
			}	
		}catch( \Exception $e){
            return array("type" => "error","message" => $e->getMessage());
        } 
	}

	public static function getIdentityTypeId($type_code){
		try{
			$identityType = Identity_type::where("type_code",$type_code)->get()->first();	
			if(isset($identityType->identity_type_id)){
				return $identityType->identity_type_id;
			}else{
				return array("type" => "error","message" => "Identity type not found");
			}	
		}catch( \Exception $e){
            return array("type" => "error","message" => $e->getMessage());
        }
	}
}