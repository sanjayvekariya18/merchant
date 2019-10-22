<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Controllers\PermissionsController;
use App\Merchant;
use App\Location;
use App\Merchant_retail_style_list;
use App\Merchant_retail_category_list;
use App\Merchant_retail_style_type;
use App\Merchant_retail_category_type;
use App\Merchant_retail_category_option;
use App\Merchant_retail_category_option_list;
use App\Merchant_type;
use App\Http\Traits\PermissionTrait;
use App\Activity;
use App\Account;
use App\Customer;
use App\Group_permission;
use App\Staff;
use App\Location_list;
use App\Country;
use App\State;
use App\County;
use App\City;
use App\Postal;
use App\Identity_postal;
use View;
use Auth;
use DB;
use Redirect;
use DOMDocument;

class HomeController extends PermissionsController
{
    const MERCHANT_TABLE_IDENTITY_TYPE = 8;
    use PermissionTrait;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        
        parent::__construct();
    }
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(View::exists('index'))
        {
            switch ($this->roleId) {
                case 1:
                    $hase_activities = Activity::orderBy('date_added','desc')->paginate(30);  
                    break;
                case 3:
                    $hase_activities = Activity::
                                orderBy('date_added','desc')
                                ->where('user_id','!=',1)
                                ->paginate(30);
                    break;
                case 4:
                    $hase_activities = Activity::
                                orderBy('date_added','desc')
                                ->where('merchant_id',$this->merchantId)
                                ->paginate(30);
                    break;
                default:
                   $hase_activities = Activity::
                                orderBy('date_added','desc')
                                ->where('user_id',$this->userId)
                                ->paginate(30);
                /*case 5:
                    $hase_staffs = Staff::
                            where('location_city_id',$this->locationId)
                            ->where('merchant_id',$this->merchantId)
                            ->where('staff_group_id','!=',4)
                            ->select('staff_id')
                            ->get();

                    $staffIds = array();
                    foreach ($hase_staffs as $key => $value) {
                        $staffIds[] = $value->staff_id;
                    }

                    $hase_activities = Activity::
                                orderBy('date_added','desc')
                                ->whereIn('user_id',$staffIds)
                                ->paginate(30);
                    break;
                case 2:
                case 6:
                    $hase_activities = Activity::
                                orderBy('date_added','desc')
                                ->where('user_id',$this->staffId)
                                ->paginate(30);
                    break;*/
            }
            /*$tempratureUrl = "http://api.openweathermap.org/data/2.5/weather?id=1818209&lang=en&units=metric&APPID=78b7be1ff41818adabe02876d2cc13c1";
            $tempratureContents = file_get_contents($tempratureUrl); 
            $tempratureData=json_decode($tempratureContents);
            $currentTemp=$tempratureData->main->temp;
            $total_income = intval(DB::table('hase_orders')->sum('order_total'));
            $hase_customer_count = count(DB::table('hase_customers')->get());
            $currentData = date('Ymd');
            $today_hase_reservations = count(DB::table('hase_reservations')->where('reserve_date',$currentData)->get());*/

            $today_date = date('Ymd');
            $two_date_back = date('Ymd', strtotime('-2 days'));
            
            $changedQueue = array(1,4);
            if($this->roleId < 4) {
                $hase_reservations_count = DB::table('reservations')->get()->count();
                $hase_merchant_count = DB::table('merchant')->get()->count();
                $hase_location_count = DB::table('location_list')->get()->count();
                $hase_approve_accept = DB::table('approval')->where('approval_status_id',3)->whereBetween('request_date', [$two_date_back, $today_date])->get()->count();
                $hase_approve_reject = DB::table('approval')->where('approval_status_id',2)->whereBetween('request_date', [$two_date_back, $today_date])->get()->count();
                $hase_approve_pending = DB::table('approval')->wherein('approval_status_id',$changedQueue)->whereBetween('request_date', [$two_date_back, $today_date])->get()->count();
            } else {
                $hase_merchant_count = count(DB::table('merchant')->where('merchant_id',$this->merchantId)->get());
                if($this->roleId == 4)
                {
                    $hase_reservations_count = count(DB::table('reservations')
                                ->where('reservations.merchant_id',$this->merchantId)
                                ->get());

                    $hase_location_count = count(DB::table('location_list')->where('    identity_table_id',self::MERCHANT_TABLE_IDENTITY_TYPE)->get());
                    $hase_approve_accept = DB::table('approval')->where('approval_status_id',3)->whereBetween('request_date', [$two_date_back, $today_date])->where('merchant_id',$this->merchantId)->get()->count();
                    $hase_approve_reject = DB::table('approval')->where('approval_status_id',2)->whereBetween('request_date', [$two_date_back, $today_date])->where('merchant_id',$this->merchantId)->get()->count();
                    $hase_approve_pending = DB::table('approval')->wherein('approval_status_id',$changedQueue)->whereBetween('request_date', [$two_date_back, $today_date])->where('merchant_id',$this->merchantId)->get()->count();
                } else {
                    $hase_reservations_count = count(DB::table('reservations')->where('location_id',$this->locationId)->get());
                    $hase_location_count = count(DB::table('location_list')->where('    identity_table_id',self::MERCHANT_TABLE_IDENTITY_TYPE)->where('postal_id',$this->locationId)->get());
                    $hase_approve_accept = DB::table('approval')->where('approval_status_id',3)->whereBetween('request_date', [$two_date_back, $today_date])->where('merchant_id',$this->merchantId)->where('location_id',$this->locationId)->get()->count();
                    $hase_approve_reject = DB::table('approval')->where('approval_status_id',2)->whereBetween('request_date', [$two_date_back, $today_date])->where('merchant_id',$this->merchantId)->where('location_id',$this->locationId)->get()->count();
                    $hase_approve_pending = DB::table('approval')->wherein('approval_status_id',$changedQueue)->whereBetween('request_date', [$two_date_back, $today_date])->where('merchant_id',$this->merchantId)->where('location_id',$this->locationId)->get()->count();

                }
            }
            return view('index',compact('hase_merchant_count','hase_location_count','hase_reservations_count','today_hase_reservations','hase_activities','hase_approve_accept','hase_approve_reject','hase_approve_pending'));
        }
        else
        {
            return view('404');
        }
    }

    public function activity(Request $request){
        if(View::exists('activity'))
        {
            switch ($this->roleId) {
                case 1:
                    $hase_activities = Activity::orderBy('date_added','desc')->paginate(30);
                    break;
                case 3:
                    $hase_activities = Activity::
                                orderBy('date_added','desc')
                                ->where('user_id','!=',1)
                                ->paginate(30);
                    break;
                case 4:
                    $hase_activities = Activity::
                                orderBy('date_added','desc')
                                ->where('merchant_id',$this->merchantId)
                                ->paginate(30);
                    break;
                default:
                    /*$hase_staffs = Staff::
                            join('location_list','location_list.identity_id','=','staff.identity_id')
                            ->join('identity_group_list','identity_group_list.identity_id','=','staff.identity_id')
                            ->where('identity_group_list.identity_table_id',35)
                            ->where('location_list.postal_id',$this->locationId)
                            ->where('staff.merchant_id',$this->merchantId)
                            ->where('identity_group_list.group_id','!=',4)
                            ->select('staff_id')
                            ->get();

                    $staffIds = array();
                    foreach ($hase_staffs as $key => $value) {
                        $staffIds[] = $value->staff_id;
                    }*/
                    $hase_activities = Activity::
                                orderBy('date_added','desc')
                                ->where('user_id',$this->userId)
                                ->paginate(30);
                /*case 2:
                case 6:
                    $hase_activities = Activity::
                                orderBy('date_added','desc')
                                ->where('user_id',$this->staffId)
                                ->paginate(30);
                    break;*/
            }
            $today_date = date('Ymd');
            $two_date_back = date('Ymd', strtotime('-2 days'));
            
            $changedQueue = array(1,4);
            if($this->roleId < 4) {
                $hase_reservations_count = DB::table('reservations')->get()->count();
                $hase_merchant_count = DB::table('merchant')->get()->count();
                $hase_location_count = DB::table('location_list')->get()->count();
                $hase_approve_accept = DB::table('approval')->where('approval_status_id',3)->whereBetween('request_date', [$two_date_back, $today_date])->get()->count();
                $hase_approve_reject = DB::table('approval')->where('approval_status_id',2)->whereBetween('request_date', [$two_date_back, $today_date])->get()->count();
                $hase_approve_pending = DB::table('approval')->wherein('approval_status_id',$changedQueue)->whereBetween('request_date', [$two_date_back, $today_date])->get()->count();
            } else {
                $hase_merchant_count = count(DB::table('merchant')->where('merchant_id',$this->merchantId)->get());
                if($this->roleId == 4)
                {
                    $hase_reservations_count = count(DB::table('reservations')
                                ->where('reservations.merchant_id',$this->merchantId)->get());
                    $hase_location_count = count(DB::table('location_list')
                        ->where('identity_table_id',self::MERCHANT_TABLE_IDENTITY_TYPE)
                        ->get());
                    $hase_approve_accept = DB::table('approval')->where('approval_status_id',3)->whereBetween('request_date', [$two_date_back, $today_date])->where('merchant_id',$this->merchantId)->get()->count();
                    $hase_approve_reject = DB::table('approval')->where('approval_status_id',2)->whereBetween('request_date', [$two_date_back, $today_date])->where('merchant_id',$this->merchantId)->get()->count();
                    $hase_approve_pending = DB::table('approval')->wherein('approval_status_id',$changedQueue)->whereBetween('request_date', [$two_date_back, $today_date])->where('merchant_id',$this->merchantId)->get()->count();
                } else {
                    $hase_reservations_count = count(DB::table('reservations')->where('location_id',$this->locationId)->get());
                    $hase_location_count = count(DB::table('location_list')->where('identity_table_id',self::MERCHANT_TABLE_IDENTITY_TYPE)->where('postal_id',$this->locationId)->get());
                    $hase_approve_accept = DB::table('approval')->where('approval_status_id',3)->whereBetween('request_date', [$two_date_back, $today_date])->where('merchant_id',$this->merchantId)->where('merchant_location_id',$this->locationId)->get()->count();
                    $hase_approve_reject = DB::table('approval')->where('approval_status_id',2)->whereBetween('request_date', [$two_date_back, $today_date])->where('merchant_id',$this->merchantId)->where('merchant_location_id',$this->locationId)->get()->count();
                    $hase_approve_pending = DB::table('approval')->wherein('approval_status_id',$changedQueue)->whereBetween('request_date', [$two_date_back, $today_date])->where('merchant_id',$this->merchantId)->where('merchant_location_id',$this->locationId)->get()->count();

                }
            }
            return view('activity',compact('hase_merchant_count','hase_location_count','hase_reservations_count','today_hase_reservations','hase_activities','hase_approve_accept','hase_approve_reject','hase_approve_pending','hase_activities'));

        }else{
            return view('404');
        }
    }

    public function maps(Request $request){
       if(View::exists('maps'))
        {
            $today_date = date('Ymd');
            $two_date_back = date('Ymd', strtotime('-2 days'));
            
            $changedQueue = array(1,4);
            if($this->roleId < 4) {
                $hase_reservations_count = DB::table('reservations')->get()->count();
                $hase_merchant_count = DB::table('merchant')->get()->count();
                $hase_location_count = DB::table('location_list')->get()->count();
                $hase_approve_accept = DB::table('approval')->where('approval_status_id',3)->whereBetween('request_date', [$two_date_back, $today_date])->get()->count();
                $hase_approve_reject = DB::table('approval')->where('approval_status_id',2)->whereBetween('request_date', [$two_date_back, $today_date])->get()->count();
                $hase_approve_pending = DB::table('approval')->wherein('approval_status_id',$changedQueue)->whereBetween('request_date', [$two_date_back, $today_date])->get()->count();
            } else {
                $hase_merchant_count = count(DB::table('merchant')->where('merchant_id',$this->merchantId)->get());
                if($this->roleId == 4)
                {
                    $hase_reservations_count = count(DB::table('reservations')
                                ->where('reservations.merchant_id',$this->merchantId)->get());
                    $hase_location_count = count(DB::table('location_list')->where('identity_table_id',self::MERCHANT_TABLE_IDENTITY_TYPE)->get());
                    $hase_approve_accept = DB::table('approval')->where('approval_status_id',3)->whereBetween('request_date', [$two_date_back, $today_date])->where('merchant_id',$this->merchantId)->get()->count();
                    $hase_approve_reject = DB::table('approval')->where('approval_status_id',2)->whereBetween('request_date', [$two_date_back, $today_date])->where('merchant_id',$this->merchantId)->get()->count();
                    $hase_approve_pending = DB::table('approval')->wherein('approval_status_id',$changedQueue)->whereBetween('request_date', [$two_date_back, $today_date])->where('merchant_id',$this->merchantId)->get()->count();
                } else {
                    $hase_reservations_count = count(DB::table('reservations')->where('location_id',$this->locationId)->get());
                    $hase_location_count = count(DB::table('location_list')
                        ->where('identity_table_id',self::MERCHANT_TABLE_IDENTITY_TYPE)
                        ->where('postal_id',$this->locationId)->get());
                    $hase_approve_accept = DB::table('approval')->where('approval_status_id',3)->whereBetween('request_date', [$two_date_back, $today_date])->where('merchant_id',$this->merchantId)->where('merchant_location_id',$this->locationId)->get()->count();
                    $hase_approve_reject = DB::table('approval')->where('approval_status_id',2)->whereBetween('request_date', [$two_date_back, $today_date])->where('merchant_id',$this->merchantId)->where('merchant_location_id',$this->locationId)->get()->count();
                    $hase_approve_pending = DB::table('approval')->wherein('approval_status_id',$changedQueue)->whereBetween('request_date', [$two_date_back, $today_date])->where('merchant_id',$this->merchantId)->where('merchant_location_id',$this->locationId)->get()->count();
                }
            }
            return view('maps',compact('hase_merchant_count','hase_location_count','hase_reservations_count','today_hase_reservations','hase_activities','hase_approve_accept','hase_approve_reject','hase_approve_pending','hase_activities'));
        }else{
            return view('404');
        } 
    }

    public function getMerchantDetails(Request $request){

        $merchants = Merchant::distinct()
                    ->select('merchant.merchant_id','identity_merchant.identity_name as merchant_name')
                    ->join('identity_merchant','identity_merchant.identity_id','=','merchant.identity_id')
                    ->where('merchant.merchant_id','!=',0)
                    ->where('merchant.merchant_type_id',$request->merchant_type_id)
                    ->get();

        echo json_encode($merchants);
    }

    public function getLocationDetails(Request $request){

        $locationDetails = PermissionTrait::getLocations($request->merchant_id);
        echo json_encode($locationDetails);
    }

    public function getStyles(Request $request){
        $stylesList = Merchant_retail_style_list::
                    select('merchant_retail_style_list.product_id','merchant_retail_style_list.style_type_id','merchant_retail_style_list.priority','identity_merchant_retail_style_type.identity_name as style_name')
                    ->join('merchant_retail_style_type','merchant_retail_style_type.style_type_id','=','merchant_retail_style_list.style_type_id')
                    ->join('identity_merchant_retail_style_type','identity_merchant_retail_style_type.identity_id','=','merchant_retail_style_type.identity_id')
                    ->where('merchant_retail_style_list.location_id',$request->location_id)
                    ->get();

        if(isset($request->product_id)){
            $stylesList = $stylesList->where('product_id',$request->product_id);
        }else{
            $stylesList = $stylesList->where('product_id',NULL);
        }
        echo json_encode($stylesList);
    }

    public function getCategories(Request $request)
    {
        $categoriesList = Merchant_retail_category_list::
                    select('merchant_retail_category_list.product_id','merchant_retail_category_list.category_type_id','merchant_retail_category_list.priority','identity_merchant_retail_category_type.identity_name as category_name')
                    ->join('merchant_retail_category_type','merchant_retail_category_type.category_type_id','=','merchant_retail_category_list.category_type_id')
                    ->join('identity_merchant_retail_category_type','identity_merchant_retail_category_type.identity_id','=','merchant_retail_category_type.identity_id')
                    ->where('merchant_retail_category_list.location_id',$request->location_id)
                    ->get();

        if(isset($request->product_id)){
            $categoriesList = $categoriesList->where('product_id',$request->product_id);
        }else{
            $categoriesList = $categoriesList->where('product_id',NULL);
        }
        echo json_encode($categoriesList);
    }

    public function getCategoryOptions(Request $request)
    {

        if(isset($request->product_id)){
            $productId = $request->product_id;
        }else{
            $productId = NULL;
        }

        $categoriesList = Merchant_retail_category_option_list::
                    select('category_option_type_id','priority','enable','product_id')
                    ->where('location_id',$request->location_id)
                    ->where('category_type_id',$request->category_type_id)
                    ->where('product_id',$productId)
                    ->get();    

        echo json_encode($categoriesList);
    }

    public function getAllStyles(Request $request)
    {
        $styleTypes = Merchant::
                distinct()
                ->select('style_type_id','identity_merchant_retail_style_type.identity_name as style_name')
                ->join('merchant_retail_style_type','merchant_retail_style_type.merchant_type_id','=','merchant.merchant_type_id')
                ->join('identity_merchant_retail_style_type','identity_merchant_retail_style_type.identity_id','=','merchant_retail_style_type.identity_id')
                ->where('merchant.merchant_id','=',$request->merchant_id)
                ->get();
        echo json_encode($styleTypes);
    }

    public function getAllCategories(Request $request)
    {
        $categoryTypes = Merchant::
                select('category_type_id','identity_merchant_retail_category_type.identity_name as category_name')
                ->join('merchant_retail_category_type','merchant_retail_category_type.merchant_type_id','=','merchant.merchant_type_id')
                ->join('identity_merchant_retail_category_type','identity_merchant_retail_category_type.identity_id','=','merchant_retail_category_type.identity_id')
                ->where('merchant.merchant_id','=',$request->merchant_id)
                ->get();
                
        echo json_encode($categoryTypes);
    }

    public function getLocationJSON(Request $request){
        if($this->merchantId == 0){
            $locations = Location_list::
                    distinct()
                    ->select(
                        'postal_subpremise',
                        'postal_premise',
                        'postal_street_number',
                        'postal_route',
                        'postal_lat as location_lat',
                        'postal_lng as location_lng',
                        'location_county.county_name',
                        'location_city.city_name',
                        'identity_merchant.identity_name as merchant_name'
                    )
                    ->join('postal','postal.postal_id','location_list.postal_id')
                    ->join('location_city','postal.postal_city','location_city.city_id')
                    ->join('location_county','location_county.county_id','location_city.county_id')
                    ->join('identity_merchant','identity_merchant.identity_id','location_list.identity_id')
                    ->where('location_list.identity_table_id',8)
                    ->whereNotNull('postal.postal_lat')
                    ->whereNotNull('postal.postal_lng')
                    ->where('postal.postal_lat' ,'!=',0)
                    ->where('postal.postal_lng','!=',0)
                    ->get();    
        }else{
            if($this->roleId == 4){
                
                $locations = Location_list::
                    distinct()
                    ->select(
                        DB::raw('CONCAT(postal_subpremise, ",",postal_premise,",",postal_street_number,",",postal_route) AS location_name'),
                        'postal_lat as location_lat',
                        'postal_lng as location_lng',
                        'identity_merchant.identity_name as merchant_name'
                    )
                    ->join('postal','postal.postal_id','location_list.postal_id')
                    ->join('identity_merchant','identity_merchant.identity_id','location_list.identity_id')
                    ->whereNotNull('postal.postal_lat')
                    ->whereNotNull('postal.postal_lng')
                    ->where('postal.postal_lat' ,'!=',0)
                    ->where('postal.postal_lng','!=',0)
                    ->where('identity_table_id',8)
                    ->where('identity_id',$this->merchantId)
                    ->get(); 

            }else{

                $locations = Location_list::
                    distinct()
                    ->select(
                        DB::raw('CONCAT(postal_subpremise, ",",postal_premise,",",postal_street_number,",",postal_route) AS location_name'),
                        'postal_lat as location_lat',
                        'postal_lng as location_lng',
                        'identity_merchant.identity_name as merchant_name'
                    )
                    ->join('postal','postal.postal_id','location_list.postal_id')
                    ->join('identity_merchant','identity_merchant.identity_id','location_list.identity_id')
                    ->whereNotNull('postal.postal_lat')
                    ->whereNotNull('postal.postal_lng')
                    ->where('postal.postal_lat' ,'!=',0)
                    ->where('postal.postal_lng','!=',0)
                    ->where('identity_id',$this->merchantId)
                    ->get();
                
            }
        }
        echo json_encode($locations);
    }

    /* latest city concept */
    public function getMerchantCityDetails(Request $request){
        $merchantCities = PermissionTrait::getMerchantCities($request->merchant_id);
        echo json_encode($merchantCities);
    }

    public function getMerchantCityPostals(Request $request){
        $merchantCityPostals = Location_list::distinct()
            ->select('location_list.list_id as location_id',
                DB::raw('(CASE WHEN (postal.postal_premise != "" and postal.postal_subpremise != "") THEN CONCAT(postal.postal_premise,", ",SUBSTR(postal.postal_subpremise,1,20))
                              WHEN (postal.postal_premise != "") THEN postal.postal_premise                              
                              WHEN (postal.postal_subpremise !="") THEN SUBSTR(postal.postal_subpremise,1,20)
                              ELSE CONCAT(postal.postal_street_number," ",postal.postal_route)   
                        END) as location_name')
                ,'location_list.postal_id')
            ->join('postal','postal.postal_id','=','location_list.postal_id')
            ->join('merchant','merchant.identity_id','=','location_list.identity_id')
            ->where('merchant.merchant_id','=',$request->merchant_id)
            ->where('location_list.location_city_id','=',$request->merchant_city_id)
            ->where('location_list.identity_table_id','=',self::MERCHANT_TABLE_IDENTITY_TYPE)->get();
        echo json_encode($merchantCityPostals);
    }

    public function getMerchantCustomers(Request $request){
        $customers = Customer::
                select('customers.customer_id','identity_customer.identity_name as customer_name') 
                ->join('identity_customer','identity_customer.identity_id','customers.identity_id')
                ->join('merchant_customer_list','merchant_customer_list.customer_id','customers.customer_id')
                ->where('merchant_customer_list.merchant_id',$request->merchant_id)
                ->get();
        echo json_encode($customers);
    }

    public function getMerchantAccounts(Request $request){
        $accounts = Account::
                select('account.account_id','identity_account.identity_name as account_name','merchant_account_list.asset_id') 
                ->join('identity_account','identity_account.identity_id','account.identity_id')
                ->join('merchant_account_list','merchant_account_list.staff_account_id','account.account_id')
                ->where('merchant_account_list.merchant_id',$request->merchant_id)
                ->get();
        echo json_encode($accounts);
    }

    public function getCustomerAccounts(Request $request){
        $accounts = Account::
                select('account.account_id','identity_account.identity_name as account_name','customer_account_list.asset_id') 
                ->join('identity_account','identity_account.identity_id','account.identity_id')
                ->join('customer_account_list','customer_account_list.account_id','account.account_id')
                ->where('customer_account_list.merchant_id',$request->merchant_id)
                ->where('customer_account_list.customer_id',$request->customer_id)
                ->get();
        return json_encode($accounts);
    }

    public function getMerchantgroups(Request $request){
        $groups = Group_permission::
            where('merchant_id',$request->merchant_id)
            ->get();
        return json_encode($groups);
    }

    public function getMerchantStaffs(Request $request){
        $staffs = Staff::
            select('staff.staff_id','identity_staff.identity_name as staff_name')
            ->join('identity_staff','identity_staff.identity_id','staff.identity_id')
            ->where('staff.merchant_id',$request->merchant_id)
            ->get();
        return json_encode($staffs);
    }

    public function getMerchantGroupStaffs(Request $request){
        $staffs = Staff::
            select('staff.staff_id','identity_staff.identity_name as staff_name')
            ->join('identity_staff','identity_staff.identity_id','staff.identity_id')
            ->join('identity_group_list','identity_group_list.identity_id','staff.identity_id')
            ->where('identity_group_list.identity_table_id',35)
            ->where('identity_group_list.group_id',$request->group_id)
            ->where('staff.merchant_id',$request->merchant_id)
            ->get();
        return json_encode($staffs);
    }

    public function getMerchantTypes()
    {
        $merchant_types = Merchant_type::where('merchant_parent_id' ,0)->get();
        return json_encode($merchant_types);
    }

    public static function getStateDetails(Request $request)
    {
        $stateDetails = PermissionTrait::getStates($request->country_id);
        echo json_encode($stateDetails);
    }

    public static function getCountyDetails(Request $request)
    {
        $countyDetails = PermissionTrait::getCounties($request->state_id);
        echo json_encode($countyDetails);
    }

    public static function getCityDetails(Request $request)
    {
        $cityDetails = PermissionTrait::getCities($request->county_id);
        echo json_encode($cityDetails);
    }

    public function getCoordinates(Request $request) {
        $addressInfo = urldecode($request->location_address_1).",".urldecode($request->location_city_name).",".urldecode($request->location_county_name);
        $googleMapApiKey = "AIzaSyAoCq44EFUfSf7PUrNpZk5KdrHRn4Fk1y4";
        $locationData = urlencode($addressInfo);
        $coordinateUrl = 'https://maps.googleapis.com/maps/api/geocode/json?region=hk&address=' . $locationData . '&key=' . $googleMapApiKey;
        $responceData = @file_get_contents($coordinateUrl);
        $contentData = json_decode($responceData, true);
        
        if (isset($contentData['results'][0]['geometry']['location']['lat'])) {
            $location_lat = $contentData['results'][0]['geometry']['location']['lat'];
            $location_lng = $contentData['results'][0]['geometry']['location']['lng'];
        }
        else {
            $location_lat = 0;
            $location_lng = 0;
        }
        echo json_encode($location_lat.','.$location_lng); 
    }

    public function getRegions(){
        $regionArray = array();        
        $cities=PermissionTrait::getCities()->where('city_id','>',0);
        foreach ($cities as $key => $city) {
            $regionArray[] = array(
                'region_id' => $city->city_id."_city",
                'region_name' => $city->city_name
            ); 
        }
        return json_encode($regionArray);
    }

    public function getLocationTree()
    {
        $topologyJsonArray = array();

        $countries = Country::where('country_id','>',0)->get();

        foreach ($countries as $keyCountry => $country) {

            $topologyJsonArray[$keyCountry] = array(
                'text'      => $country->country_name,
                'id'        => $country->country_id."_country",
                'parent_id' => 0
            );

            $states = State::
                join('location_city', 'location_state.state_id', '=', 'location_city.state_id')
                ->where('location_city.country_id','=',$country->country_id)
                ->select('location_state.*')
                ->orderBy('location_state.state_name', 'ASC')
                ->groupBy('state_name')
                ->get();

            foreach ($states as $keyState => $state) {
                // echo "----State : ".$state->state_id." | ".$state->state_name."<br>";
                $topologyJsonArray[$keyCountry]['items'][$keyState] = array(
                    'text'      => $state->state_name,
                    'id'        => $state->state_id."_state",
                    'parent_id' => $country->country_id
                );

                $counties = County::
                    join('location_city', 'location_county.county_id', '=', 'location_city.county_id')
                    ->where('location_city.state_id',$state->state_id)
                    ->select('location_county.*')
                    ->orderBy('location_county.county_name', 'ASC')
                    ->groupBy('county_name')
                    ->get();

                foreach ($counties as $keyCounty => $county) {

                    $topologyJsonArray[$keyCountry]['items'][$keyState]['items'][$keyCounty] = array(
                            'text'      => $county->county_name,
                            'id'        => $county->county_id."_county",
                            'parent_id' => $state->state_id
                        );

                    $cities = City::where('county_id',$county->county_id)->get();
                    
                    foreach ($cities as $keyCity => $city) {

                        $topologyJsonArray[$keyCountry]['items'][$keyState]['items'][$keyCounty]['items'][$keyCity] = array(
                            'text'      => $city->city_name,
                            'id'        => $city->city_id."_city",
                            'parent_id' => $county->county_id
                        );
                    }
                }
            }
        }
        return json_encode(array_values($topologyJsonArray));
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

        echo $identity_city_lists;

        //return json_encode($identity_city_lists);
    }

    public function insertIdentityCityList(Request $request)
    {
        $cityArray = array();
        $dataArray = array();

        
        foreach ($request->region_id as $key => $city) {
            if(strpos($city, 'city')){
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
        return 1;
    }

    public function getLocationData(Request $request)
    {

        $identity_city_list = Location_list::
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
                'location_country.postal_code_max'
            )

            ->leftjoin('postal','location_list.postal_id','postal.postal_id')
            ->leftjoin('identity_postal','identity_postal.identity_id','postal.identity_id')

            ->join('location_city','location_city.city_id','location_list.location_city_id')

            ->join('location_state','location_city.state_id','location_state.state_id')
            ->join('location_county','location_city.county_id','location_county.county_id')
            ->join('location_country','location_city.country_id','location_country.country_id')
            ->where('location_list.identity_id',$request->identity_id)
            ->where('location_list.identity_table_id',$request->identity_table_id)
            ->where('location_list.location_city_id',$request->location_city_id)
            ->get();

        return json_encode($identity_city_list);
    }

    public function getPostalCoordinates($request) {
        
        $addressInfo = urldecode($request->postal_subpremise).",".
                       urldecode($request->postal_premise).",".
                       urldecode($request->city_name);

        $locationData = urlencode($addressInfo);
        $googleMapApiKey = "AIzaSyAoCq44EFUfSf7PUrNpZk5KdrHRn4Fk1y4";

        $coordinateUrl = 'https://maps.googleapis.com/maps/api/geocode/json?region=hk&address=' . $locationData . '&key=' . $googleMapApiKey;

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
            CURLOPT_SSL_VERIFYPEER => false,
        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        return (!$err)? json_decode($response) : false;
    }

    public function getPostalAddress(Request $request)
    {
        $postalData = $this->getPostalCoordinates($request);
        
        $jsonArray = array(
            "street_number" => array(),
            "route"         => array(),
            "neighborhood"  => array(),
            "postal_code"   => array()
        );

        if($postalData && count($postalData->results)){

            $addresses = $postalData->results[0]->address_components;
            $geometry = $postalData->results[0]->geometry;

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
                    $geometry->location->lat : 0;

            $jsonArray['lng'] = isset($geometry->location->lng)?
                    $geometry->location->lng : 0;

            return json_encode($jsonArray);

        }else{
            return json_encode($jsonArray);
        }
    }

    public function updateLocation(Request $request)
    {
        // echo "<pre>";
        // print_r($request->postals);
        // die;

        foreach($request->postals as $key => $postal) {

            if($postal['list_id']){
                $listInfo = Location_list::findOrfail($postal['list_id']);
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

            $postalObj->postal_street_number =  ($postal['street_number'] != "None")?
                            $postal['street_number'] : 0 ;

            $postalObj->postal_route =  ($postal['route'] != "None")?
                            $postal['route'] : '' ;

            $postalObj->postal_neighborhood =   ($postal['neighborhood'] != "None")?
                            $postal['neighborhood'] : '' ;

            $postalObj->postal_postcode =   ($postal['neighborhood'] != "None")?
                            $postal['neighborhood'] : 0 ;

            $postalObj->postal_lat =    $postal['lat'];
            $postalObj->postal_lng =    $postal['lng'];

            $postalObj->save();

            if($postal['list_id']){
                $listInfo->postal_id = $postalObj->postal_id;
                $listInfo->save();
            }else{
                $cityList = new Location_list;
                $cityList->identity_id = $listInfo->identity_id;
                $cityList->identity_table_id = $listInfo->identity_table_id;
                $cityList->location_city_id = $listInfo->location_city_id;
                $cityList->postal_id = $postalObj->postal_id;
                $cityList->save();
            }
        }
        return 1;
    }

    public function getUserGroupList()
    {
        if($this->merchantId == 0)
        {
            $hase_group_list = Group_permission::where('group_permissions.group_name','!=','None')
            ->select('group_permissions.group_id','group_permissions.group_name as group_name')
            ->get()->toArray();
        } else {
            $hase_group_list = Group_permission::join('identity_group_list', 'identity_group_list.group_id', '=', 'group_permissions.group_id')
            ->where('identity_group_list.identity_id', session('staffId'))
            ->where('identity_group_list.identity_table_id', session('identity_table_id'))
            ->select('group_permissions.group_id','group_permissions.group_name as group_name')
            ->get()->toArray();    
        }
        return json_encode($hase_group_list);
    }

    public function getAllMerchantTypes()
    {
        $merchant_types = Merchant_type::all();
        return json_encode($merchant_types);
    }
}
