<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\App;
use Illuminate\Http\Request;
use App\Http\Controllers\PermissionsController;
use App\Helpers\ConnectionManager;
use App\Reservation;
use App\Http\Traits\PermissionTrait;
use App\Staff;
use App\Statuses;
use App\Reservations_seating;
use App\Status_history;
use App\Location_list;
use App\Customer;
use App\Identity_customer;
use App\Merchant;
use App\Reservations_alert;
use DateTime;
use URL;
use Auth;
use DB;
use Session;
use Redirect;
use Carbon\Carbon;

const MERCHANT_TABLE_IDENTITY_TYPE=8;
const STAFF_TABLE_IDENTITY_TYPE=35;
CONST MERCHANT_CODE_SUFFIX = "-merc";
CONST CUSTOMER_CODE_SUFFIX = "-cust-";
/**
 * Class Hase_reservationController.
 *
 * @author  The scaffold-interface created at 2017-03-02 02:20:43pm
 * @link  https://github.com/amranidev/scaffold-interface
 */
class Hase_reservationController extends PermissionsController
{
    use PermissionTrait;
    
    public function __construct()
    {
        parent::__construct();

        $connectionStatus = ConnectionManager::setDbConfig('Hase_reservation');

        if ($connectionStatus['type'] === "error") {
            Session::flash('type', $connectionStatus['type']);
            Session::flash('msg', $connectionStatus['message']);
            return Redirect::back()->send();
        }

        $this->codeCounter = 0;
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return  \Illuminate\Http\Response
     */
    public function index()
    {
        $haseReservation = $this->permissionDetails('Hase_reservation','access');
        if($haseReservation) {
            $title = 'Index - hase_reservation';
            $permissions = $this->getPermission("Hase_reservation");

            if($this->merchantId === 0 ) {
                $hase_reservations_data = Reservation::
                join('location_list','reservations.location_id','=','location_list.list_id')
                ->join('postal','postal.postal_id','=','location_list.postal_id')
                ->join('reservations_seating','reservations.seating_id','=','reservations_seating.seating_id')
                ->join('statuses','reservations.reservation_status','=','statuses.status_id')
                ->join('staff','reservations.assignee_id','=','staff.staff_id')
                ->join('identity_staff','identity_staff.identity_id','=','staff.identity_id')
                ->join('customers','customers.customer_id','=','reservations.customer_id')
                ->join('identity_customer','identity_customer.identity_id','=','customers.identity_id')
                ->select('reservations.*',DB::raw('(CASE WHEN (postal.postal_premise != "" and postal.postal_subpremise != "") THEN CONCAT(postal.postal_premise,", ",SUBSTR(postal.postal_subpremise,1,10))
                              WHEN (postal.postal_premise != "") THEN postal.postal_premise                              
                              WHEN (postal.postal_subpremise !="") THEN SUBSTR(postal.postal_subpremise,1,10)
                              ELSE CONCAT(postal.postal_street_number," ",postal.postal_route)   
                        END) as location_name'),'reservations_seating.seating_name','identity_staff.identity_name as staff_name','statuses.status_name','identity_customer.identity_name as customer_name','identity_customer.identity_telephone as telephone')
                ->orderBy('reserve_date', 'DESC')
                ->orderBy('reserve_time', 'ASC')
                ->get();

            } else {
                if($this->roleId === 4)
                {

                    $hase_reservations_data = Reservation::
                        join('location_list','reservations.location_id','=','location_list.list_id')
                        ->join('postal','postal.postal_id','=','location_list.postal_id')
                        ->join('reservations_seating','reservations.seating_id','=','reservations_seating.seating_id')
                        ->join('statuses','reservations.reservation_status','=','statuses.status_id')
                        ->join('staff','reservations.assignee_id','=','staff.staff_id')
                        ->join('identity_staff','identity_staff.identity_id','=','staff.identity_id')
                        ->join('customers','customers.customer_id','=','reservations.customer_id')
                        ->join('identity_customer','identity_customer.identity_id','=','customers.identity_id')
                        ->where('location_list.identity_id','=',$this->merchantId)
                        ->where('location_list.identity_table_id','=',8)
                        ->select('reservations.*',DB::raw('(CASE WHEN (postal.postal_premise != "" and postal.postal_subpremise != "") THEN CONCAT(postal.postal_premise,", ",SUBSTR(postal.postal_subpremise,1,10))
                              WHEN (postal.postal_premise != "") THEN postal.postal_premise                              
                              WHEN (postal.postal_subpremise !="") THEN SUBSTR(postal.postal_subpremise,1,10)
                              ELSE CONCAT(postal.postal_street_number," ",postal.postal_route)   
                        END) as location_name'),'reservations_seating.seating_name','identity_staff.identity_name as staff_name','statuses.status_name','identity_customer.identity_name as customer_name','identity_customer.identity_telephone as telephone')
                        ->orderBy('reserve_date', 'DESC')
                        ->orderBy('reserve_time', 'ASC')
                        ->get();

                } else {
                    
                    $hase_reservations_data = Reservation::
                        join('location_list','reservations.location_id','=','location_list.list_id')
                        ->join('postal','postal.postal_id','=','location_list.postal_id')
                        ->join('reservations_seating','reservations.seating_id','=','reservations_seating.seating_id')
                        ->join('statuses','reservations.reservation_status','=','statuses.status_id')
                        ->join('staff','reservations.assignee_id','=','staff.staff_id')
                        ->join('identity_staff','identity_staff.identity_id','=','staff.identity_id')
                        ->join('customers','customers.customer_id','=','reservations.customer_id')
                        ->join('identity_customer','identity_customer.identity_id','=','customers.identity_id')
                        ->where('location_list.identity_id','=',$this->merchantId)
                        ->where('location_list.list_id','=',$this->locationId)
                        ->where('location_list.identity_table_id','=',8)
                        ->select('reservations.*',DB::raw('(CASE WHEN (postal.postal_premise != "" and postal.postal_subpremise != "") THEN CONCAT(postal.postal_premise,", ",SUBSTR(postal.postal_subpremise,1,10))
                              WHEN (postal.postal_premise != "") THEN postal.postal_premise                              
                              WHEN (postal.postal_subpremise !="") THEN SUBSTR(postal.postal_subpremise,1,10)
                              ELSE CONCAT(postal.postal_street_number," ",postal.postal_route)   
                        END) as location_name'),'reservations_seating.seating_name','identity_staff.identity_name as staff_name','statuses.status_name','identity_customer.identity_name as customer_name','identity_customer.identity_telephone as telephone')
                        ->orderBy('reserve_date', 'DESC')
                        ->orderBy('reserve_time', 'ASC')
                        ->get();
                }
            }
            return view('hase_reservation.index',compact('title','hase_reservations_data','permissions'));
        } else {
            return Redirect::back()->with('message', 'You are not authorized to use this functionality!');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return  \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $haseReservation = $this->permissionDetails('Hase_reservation','add');
        if($haseReservation) {
            $title = 'Create - hase_reservation';
            $createdAt = Carbon::now('Asia/kolkata');
            $currentDate = $createdAt->format('Y-m-d');
            if($this->merchantId === 0 ) {

                $hase_merchants = Merchant::distinct()
                    ->select('merchant.merchant_id','identity_merchant.identity_name as merchant_name')
                    ->leftjoin('identity_merchant','identity_merchant.identity_id','=','merchant.identity_id')
                    ->where('merchant.merchant_id','!=',0)
                    ->get();

                $merchant_cities = array();
                $merchant_city_postals = array();          
                $seatings = array();          
                
                $hase_staffs = Staff::select('staff_id','identity_staff.identity_name as staff_name')
                                ->join('identity_staff','identity_staff.identity_id','staff.identity_id')
                                ->where('merchant_id','!=',0)
                                ->get();
            }
            else {

                $hase_merchants = Merchant::
                            select('merchant.merchant_id','identity_merchant.identity_name as merchant_name')
                            ->leftjoin('identity_merchant','identity_merchant.identity_id','=','merchant.identity_id')
                            ->where('merchant.merchant_id','=',$this->merchantId)
                            ->get()->first();


                if($this->roleId === 4) {

                    $merchant_cities = City::distinct()
                        ->select('location_city.city_id','location_city.city_name as city_name')
                        ->leftjoin('location_list','location_city.city_id','=','location_list.location_city_id')
                        ->leftjoin('merchant','merchant.identity_id','=','location_list.identity_id')
                        ->where('location_list.identity_table_id','=',MERCHANT_TABLE_IDENTITY_TYPE)
                        ->where('merchant.merchant_id','=',$this->merchantId)
                        ->get();

                    $merchant_city_postals = array(); 
                    $seatings = array();    
                    
                    $hase_staffs = Staff::select('staff_id','identity_staff.identity_name as staff_name')
                                ->join('identity_staff','identity_staff.identity_id','staff.identity_id')
                                ->where('merchant_id',$this->merchantId)
                                ->get();

                } else {

                    $merchant_cities = City::distinct()
                        ->select('location_city.city_id','location_city.city_name as city_name')
                        ->leftjoin('location_list','location_city.city_id','=','location_list.location_city_id')
                        ->leftjoin('merchant','merchant.identity_id','=','location_list.identity_id')
                        ->where('location_list.identity_table_id','=',MERCHANT_TABLE_IDENTITY_TYPE)
                        ->where('merchant.merchant_id','=',$this->merchantId)
                        ->where('location_list.postal_id','=',$this->locationId)
                        ->get();

                    $merchant_city_postals = Postal::distinct()
                        ->select('location_list.postal_id as location_id',DB::raw('(CASE WHEN (postal.postal_premise != "" and postal.postal_subpremise != "") THEN CONCAT(postal.postal_premise,", ",SUBSTR(postal.postal_subpremise,1,10))
                              WHEN (postal.postal_premise != "") THEN postal.postal_premise                              
                              WHEN (postal.postal_subpremise !="") THEN SUBSTR(postal.postal_subpremise,1,10)
                              ELSE CONCAT(postal.postal_street_number," ",postal.postal_route)   
                        END) as location_name'))
                        ->join('location_list','location_list.postal_id','=','postal.postal_id')
                        ->join('merchant','merchant.identity_id','=','location_list.identity_id')
                        ->where('merchant.merchant_id','=',$this->merchantId)
                        ->where('location_list.postal_id','=',$this->locationId)
                        ->where('location_list.identity_table_id','=',MERCHANT_TABLE_IDENTITY_TYPE)
                        ->get();  

                    $seatings = Reservations_seating::where('location_id',$this->locationId)->get();   
                    
                    $hase_staffs = Staff::select('staff_id','identity_staff.identity_name as staff_name')
                                ->join('identity_staff','identity_staff.identity_id','staff.identity_id')
                                ->join('location_list','location_list.identity_id','staff.identity_id')
                                ->where('location_list.identity_table_id',STAFF_TABLE_IDENTITY_TYPE)
                                ->where('staff.merchant_id',$this->merchantId)
                                ->where('location_list.list_id',$this->locationId)
                                ->get();

                }
            }

            return view('hase_reservation.create',compact('hase_locations','hase_staffs','hase_merchants','merchant_cities','merchant_city_postals','seatings','currentDate'));
        } else {
            return Redirect::back()->with('message', 'You are not authorized to use this functionality!');
        }

    }
    
    public function store(Request $request)
    {
        /*if (!$request->customer_status) {
            $request->customer_status = 0;
        }*/
        if (!$request->newsletter) {
            $request->newsletter = 0;
        }
        $hase_customer = new Customer();
        $hase_identity = new Identity_customer();

        $merchant = Merchant::
                    distinct()
                    ->select('merchant.*','identity_merchant.identity_code as merchant_code')
                    ->leftjoin('identity_merchant','identity_merchant.identity_id','=','merchant.identity_id')
                    ->where('merchant.merchant_id','=',$request->merchant_id)
                    ->get()->first();

        $merchantCode = $merchant->merchant_code;
        $merchantCodeNew = str_replace(MERCHANT_CODE_SUFFIX,CUSTOMER_CODE_SUFFIX,$merchantCode);                                 
        $customerCode = substr($request->first_name,0,1).substr($request->last_name,0,5);
        $customerCodeNew = $merchantCodeNew.$customerCode;

        $codeName = $this->generateCode($customerCodeNew);

        $hase_identity->identity_code = strtolower($codeName);
        $hase_identity->identity_name = $request->first_name." ".$request->last_name;
        
        $hase_identity->identity_email = $request->email;
        $hase_identity->identity_telephone = $request->telephone;

        $hase_customer->newsletter = $request->newsletter;
        $hase_customer->status = 1;

        $currentIp = $request->ip();
        $hase_customer->ip_address = $currentIp;

        $createdAt = Carbon::now('Asia/kolkata');

        $hase_customer->date_added = $createdAt;

        $hase_identity->save();
        $identityID = $hase_identity->identity_id;

        $hase_customer->identity_id = $identityID;

        $hase_customer->save();

        $customerId = $hase_customer->customer_id;

        $hase_reservation = new Reservation();

        //$hase_reservation->reservation_id = $request->reservation_id;
        
        $hase_reservation->location_id = $request->location_id;

        $hase_reservation->seating_id = $request->seating_id;
        
        if($this->roleId === 1) {
            $hase_reservation->merchant_id = $request->merchant_id;
        } else {
            $hase_reservation->merchant_id = $this->merchantId;
        }

        $hase_reservation->occasion_id = $request->occasion_id;

        $hase_reservation->customer_id = $customerId;

        if (!$request->guest_num) {
            $request->guest_num = 0;
        }
        $hase_reservation->guest_num = $request->guest_num;
        
        $hase_reservation->comment = $request->comment;

        if(isset($request->reserve_time)) {
            $reserveTimeData = explode(":", $request->reserve_time);
            $reserveTime = $reserveTimeData[0]*3600+$reserveTimeData[1]*60;
            $hase_reservation->reserve_time = $reserveTime;
        } else {
            $hase_reservation->reserve_time = 0;
        }

        if(isset($request->reserve_date)) {
            $reserveDate = str_replace('-', '', $request->reserve_date);
            $hase_reservation->reserve_date = $reserveDate;
        } else {
            $hase_reservation->reserve_date = 0;
        }

        $currentDate = $createdAt->format('Ymd');
        $hase_reservation->date_added = $currentDate;

        $hase_reservation->date_modified = $currentDate;
        
        $hase_reservation->assignee_id = $request->assignee_id;
        if (!$request->notify) {
            $request->notify = 0;
        }
        
        $hase_reservation->notify = $request->notify;

        $hase_reservation->ip_address = $currentIp;

        $userAgent = $request->header('User-Agent');
        
        $hase_reservation->user_agent = $userAgent;

        $hase_reservation->reservation_status = $request->status;

        $hase_reservation->save();
        
        Session::flash('type', 'success');
        Session::flash('msg', 'Reservation Successfully Created');
        return redirect('hase_reservation');
    }

    public function generateCode($orignalCodeName)
    {
        
        if($this->codeCounter === 0){
            $codeName = $orignalCodeName;
        }else{
            $codeName = $orignalCodeName.$this->codeCounter;
        }

        $code_exist = identity_customer::select('*')
                         ->where('identity_code',$codeName)
                         ->get()->first();

        if(!count($code_exist)){
            
            return $codeName;

        }else{
            $this->codeCounter = $this->codeCounter + 1;
            return $this->generateCode($orignalCodeName);
        }
    }

    public function getSeatings(Request $request){
        $seatings = Reservations_seating::where('location_id',$request->location_id)->get(); 
        echo json_encode($seatings);
    }

    
    public function show($id,Request $request)
    {
        $haseReservation = $this->permissionDetails('Hase_reservation','access');
        if($haseReservation) {
            $title = 'Show - hase_reservation';
            $hase_reservation = Reservation::findOrfail($id);

            if($this->merchantId === 0 ) {
                $hase_staffs = Staff::select('staff_id','identity_staff.identity_name as staff_name')
                                ->join('identity_staff','identity_staff.identity_id','staff.identity_id')
                                ->where('merchant_id','!=',0)
                                ->get();
            } else {
                $hase_staffs = Staff::select('staff_id','identity_staff.identity_name as staff_name')
                                ->join('identity_staff','identity_staff.identity_id','staff.identity_id')
                                ->where('merchant_id',$this->merchantId)
                                ->get();
            }
            
            $reservationSeatingId = $hase_reservation->seating_id;
            $reservationLocationId = $hase_reservation->location_id;
            $reservationCustomerId = $hase_reservation->customer_id;

            $hase_location = Location_list::
                            select('list_id','identity_merchant.identity_name as merchant_name','postal.*','location_city.city_name','location_state.state_name','location_county.county_name','location_country.country_name')
                            ->join('identity_merchant','identity_merchant.identity_id','=','location_list.identity_id')
                            ->join('location_city','location_city.city_id','=','location_list.location_city_id')
                            ->join('location_county','location_county.county_id','=','location_city.county_id')
                            ->join('location_state','location_state.state_id','=','location_city.state_id')                            
                            ->join('location_country','location_country.country_id','=','location_city.country_id')                            
                            ->join('postal','postal.postal_id','=','location_list.postal_id')
                            ->where('location_list.list_id','=',$reservationLocationId)
                            ->get()->first(); 

            $hase_customer = Customer::
                                select('customers.*','identity_customer.identity_name as customer_name','identity_customer.identity_code as customer_code','identity_customer.identity_email as email','identity_customer.identity_telephone as telephone')
                                ->join('identity_customer','customers.identity_id','=','identity_customer.identity_id')
                                ->where('customer_id',$reservationCustomerId)
                                ->get()->first();

            $seatings = Reservations_seating::where('location_id',$reservationLocationId)->get()->first();   
            $hase_statuses = DB::table('statuses')->where('status_for', '=', 'reserve')->get();

            $hase_status_history = DB::table('status_history')
                ->select('status_history.*','identity_staff.identity_name as staff_name','identity_assignee.identity_name as assignee_name','statuses.status_name')
                ->join('staff','status_history.staff_id','=','staff.staff_id') 
                ->join('identity_staff','identity_staff.identity_id','=','staff.identity_id') 
                ->join('statuses','status_history.status_id','=','statuses.status_id') 
                ->join('staff as assignee','status_history.assignee_id','=','assignee.staff_id') 
                ->join('identity_staff as identity_assignee','identity_assignee.identity_id','=','assignee.identity_id') 
                ->where('status_history.object_id', $id)
                ->orderBy('status_history_id', 'desc')
                ->get();

            return view('hase_reservation.show',compact('title','hase_reservation','hase_merchants','hase_location','hase_staffs','seatings','hase_customer','hase_statuses','hase_status_history'));
        } else {
            return Redirect::back()->with('message', 'You are not authorized to use this functionality!');
        }
    }

    
    public function edit($id,Request $request)
    {
        $haseReservation = $this->permissionDetails('Hase_reservation','manage');
        if($haseReservation) {
            $title = 'Edit - hase_reservation';
            $hase_reservation = Reservation::findOrfail($id);

            if($this->merchantId === 0 ) {
                $hase_staffs = Staff::select('staff_id','identity_staff.identity_name as staff_name')
                                ->join('identity_staff','identity_staff.identity_id','staff.identity_id')
                                ->where('merchant_id','!=',0)
                                ->get();
            } else {
                $hase_staffs = Staff::select('staff_id','identity_staff.identity_name as staff_name')
                                ->join('identity_staff','identity_staff.identity_id','staff.identity_id')
                                ->where('merchant_id',$this->merchantId)
                                ->get();
            }
            
            $reservationSeatingId = $hase_reservation->seating_id;
            $reservationLocationId = $hase_reservation->location_id;
            $reservationCustomerId = $hase_reservation->customer_id;

            $hase_location = Location_list::
                            select('list_id','identity_merchant.identity_name as merchant_name','postal.*','location_city.city_name','location_state.state_name','location_county.county_name','location_country.country_name')
                            ->join('identity_merchant','identity_merchant.identity_id','=','location_list.identity_id')
                            ->join('location_city','location_city.city_id','=','location_list.location_city_id')
                            ->join('location_county','location_county.county_id','=','location_city.county_id')
                            ->join('location_state','location_state.state_id','=','location_city.state_id')                            
                            ->join('location_country','location_country.country_id','=','location_city.country_id')                            
                            ->join('postal','postal.postal_id','=','location_list.postal_id')
                            ->where('location_list.list_id','=',$reservationLocationId)
                            ->get()->first(); 

            $hase_customer = Customer::
                                select('customers.*','identity_customer.identity_name as customer_name','identity_customer.identity_code as customer_code','identity_customer.identity_email as email','identity_customer.identity_telephone as telephone')
                                ->join('identity_customer','customers.identity_id','=','identity_customer.identity_id')
                                ->where('customer_id',$reservationCustomerId)
                                ->get()->first();

            $seatings = Reservations_seating::where('location_id',$reservationLocationId)->get()->first();   
            $hase_statuses = DB::table('statuses')->where('status_for', '=', 'reserve')->get();

            $hase_status_history = DB::table('status_history')
                ->select('status_history.*','identity_staff.identity_name as staff_name','identity_assignee.identity_name as assignee_name','statuses.status_name')
                ->join('staff','status_history.staff_id','=','staff.staff_id') 
                ->join('identity_staff','identity_staff.identity_id','=','staff.identity_id') 
                ->join('statuses','status_history.status_id','=','statuses.status_id') 
                ->join('staff as assignee','status_history.assignee_id','=','assignee.staff_id') 
                ->join('identity_staff as identity_assignee','identity_assignee.identity_id','=','assignee.identity_id') 
                ->where('status_history.object_id', $id)
                ->orderBy('status_history_id', 'desc')
                ->get();
            
            return view('hase_reservation.edit',compact('title','hase_reservation','hase_merchants','hase_location','hase_staffs','seatings','hase_customer','hase_statuses','hase_status_history'));
        } else {
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

        $hase_reservation = Reservation::findOrfail($id);

        if (isset($request->notify)) {
            $notify = 1;
        } else {
            $notify = 0;    
        }
        
        $hase_reservation->guest_num = $request->guest_num;

        if(isset($request->reserve_time)) {
            $reserveTimeData = explode(":", $request->reserve_time);
            $reserveTime = $reserveTimeData[0]*3600+$reserveTimeData[1]*60;
            $hase_reservation->reserve_time = $reserveTime;
        } else {
            $hase_reservation->reserve_time = 0;
        }

        if(isset($request->reserve_date)) {
            $reserveDate = str_replace('-', '', $request->reserve_date);
            $hase_reservation->reserve_date = $reserveDate;
        } else {
            $hase_reservation->reserve_date = 0;
        }
        
        $createdAt = Carbon::now('Asia/kolkata');
        $currentDate = $createdAt->format('Ymd');
        $hase_reservation->date_modified = $currentDate;

        $hase_reservation->comment = $request->comment;
        //$hase_reservation->status = $request->status;
        $hase_reservation->reservation_status = 8;
        $hase_reservation->notify = $notify;
        $hase_reservation->assignee_id = $request->assignee_id;
        $reservePeople = $request->guest_num;
        $reserveComment = $request->comment;
        $hase_reservation->save();

        $reserve_old_date = $request->reserve_date_old;
        $reserve_old_time = $request->reserve_time_old;
        $reserve_old_people = $request->reserve_people_old;
        $reserve_old_comment = $request->reserve_comment_old;

        if(($reserve_old_date !== $reserveDate) || ($reserve_old_time !== $reserveTime) || ($reserve_old_people !== $reservePeople) || ($reserve_old_comment !== $reserveComment)) {
            $url = env('CHATBOT_URL')."reserve_change?reservation_id=".$id;
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
            curl_setopt($ch, CURLOPT_URL,$url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            $output = curl_exec ($ch);
        }

        if(($reserve_old_date !== $reserveDate) || ($reserve_old_time !== $reserveTime))
        {
            Reservations_alert::where('reservation_id','=',$id)->delete();
        }
        
        if($request->status === 6 || $request->status === 7)
        {
            $url = env('CHATBOT_URL')."reserve_change?reservation_id=".$id;
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
            curl_setopt($ch, CURLOPT_URL,$url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            $output = curl_exec ($ch);
        }

        if(isset($request->status)){
            $hase_status_history = new Status_history;
            $hase_status_history->object_id = $request->reservation_id;
            $hase_status_history->staff_id = $this->staffId;
            $hase_status_history->assignee_id = $request->assignee_id;
            $hase_status_history->status_id = $request->status;
            $hase_status_history->notify = $notify;
            $hase_status_history->status_for = "reservation";
            $hase_status_history->comment = $request->status_comment;

            $currentDateTime = Carbon::now();
            $hase_status_history->date_added = $currentDateTime;
            $hase_status_history->save();
        }

        Session::flash('type', 'success');
        Session::flash('msg', 'Reservation Successfully Saved');

        if ($request->submitbutton === "Save") {
            return redirect('hase_reservation/'. $hase_reservation->reservation_id . '/edit');
        } else {
            return redirect('hase_reservation');
        }
    }

    /**
     * Delete confirmation message by Ajaxis.
     *
     * @link      https://github.com/amranidev/ajaxis
     * @param    \Illuminate\Http\Request  $request
     * @return  String
     */
    public function DeleteMsg($id,Request $request)
    {
        $msg = Ajaxis::BtDeleting('Warning!!','Would you like to remove This?','/hase_reservation/'. $id . '/delete');

        if($request->ajax())
        {
            return $msg;
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
        $haseReservation = $this->permissionDetails('Hase_reservation','delete');
        if($haseReservation) {
            $hase_reservation = Reservation::findOrfail($id);
            $hase_reservation->delete();
            return redirect('hase_reservation');
        } else {
            return Redirect::back()->with('message', 'You are not authorized to use this functionality!'); 
        }
    }

    public function acceptReject($id, $statusId)
    {
        $hase_reservation = Reservation::findOrfail($id);
        $hase_reservation->reservation_status = $statusId;
        $hase_reservation->save();
        
        $url = env('CHATBOT_URL')."reserve_change?reservation_id=".$id;
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        $output = curl_exec ($ch);
        
        /*$info = curl_getinfo($ch);
        $http_result = $info ['http_code'];
        curl_close ($ch);*/

        $statusDetails=DB::table('statuses')->where('status_id',$statusId)->get()->first();
        
        $hase_status_history = new Status_history;
        $hase_status_history->object_id = $id;
        $hase_status_history->staff_id = $this->staffId;
        $hase_status_history->assignee_id = $this->staffId;
        $hase_status_history->status_id = $statusId;
        $hase_status_history->notify = 1;
        $hase_status_history->status_for = "reservation";
        $hase_status_history->comment = $statusDetails->status_comment;

        $currentDateTime = Carbon::now();
        $hase_status_history->date_added = $currentDateTime;
        $hase_status_history->save();
        Session::flash('type', 'success');
        Session::flash('msg', 'Status Successfully Updated');
        return redirect('hase_reservation');
    }

    public function multiAccept(request $request)
    {
        $haseReservation = $this->permissionDetails('Hase_reservation','manage');
        if($haseReservation) {
            $statusId = 6;
            $statusDetails=DB::table('statuses')->where('status_id',$statusId)->get()->first();
            foreach ($request->checked_reservation as $key => $value) {
                $hase_reservation = Reservation::findOrfail($value);
                $hase_reservation->reservation_status = $statusId;
                $hase_reservation->save();
                $url = env('CHATBOT_URL')."reserve_change?reservation_id=".$value;
                $ch = curl_init($url);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
                curl_setopt($ch, CURLOPT_URL,$url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                $output = curl_exec ($ch);
                $hase_status_history = new Status_history;
                $hase_status_history->object_id = $value;
                $hase_status_history->staff_id = $this->staffId;
                $hase_status_history->assignee_id = $this->staffId;
                $hase_status_history->status_id = $statusId;
                $hase_status_history->notify = 1;
                $hase_status_history->status_for = "reservation";
                $hase_status_history->comment = $statusDetails->status_comment;

                $currentDateTime = Carbon::now();
                $hase_status_history->date_added = $currentDateTime;
                $hase_status_history->save();
            }
            Session::flash('type', 'success');
            Session::flash('msg', 'Reservation Successfully Accepted');
            return redirect('hase_reservation');
        } else {
            return Redirect::back()->with('message', 'You are not authorized to use this functionality!');
        }
    }

    public function multiReject(request $request)
    {
        $haseReservation = $this->permissionDetails('Hase_reservation','manage');
        if($haseReservation) {
            foreach ($request->checked_reservation as $key => $value) {
                $hase_reservation = Reservation::findOrfail($value);
                $hase_reservation->reservation_status = 7;
                $hase_reservation->save();
                $url = env('CHATBOT_URL')."reserve_change?reservation_id=".$value;
                $ch = curl_init($url);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
                curl_setopt($ch, CURLOPT_URL,$url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                $output = curl_exec ($ch);
            }
            Session::flash('type', 'success');
            Session::flash('msg', 'Reservation Successfully Rejected');
            return redirect('hase_reservation');
        } else {
            return Redirect::back()->with('message', 'You are not authorized to use this functionality!');
        }
    }
}
