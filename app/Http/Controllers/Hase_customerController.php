<?php

namespace App\Http\Controllers;

use App\Address;
use App\City;
use App\Country;
use App\County;
use App\Customer;
use App\Group_permission;
use App\Helpers\ConnectionManager;
use App\Http\Controllers\PermissionsController;
use App\Http\Traits\PermissionTrait;
use App\Identity_customer;
use App\Identity_group_list;
use App\Location_list;
use App\Merchant;
use App\Merchant_customer_list;
use App\Merchant_type;
use App\PasswordSecurity;
use App\Identity_postal;
use App\Postal;
use App\Portal;
use App\Portal_password;
use App\Security_question;
use App\State;
use Carbon\Carbon;
use DB;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Redirect;
use Session;
use URL;

const CUSTOMER_IDENTITY_TYPE_ID  = "8";
const CUSTOMER_IDENTITY_TABLE_ID = "3";
const STAFF_TABLE_ID             = "35";
const CUSTOMER_TABLE_ID          = "4";
const POSTAL_IDENTITY_TYPE_ID	 = "14";
const MERCHANT_CODE_SUFFIX       = "-merc";
const CUSTOMER_CODE_SUFFIX       = "-cust-";
const INDEX_ZERO                 = 0;
const INDEX_ONE                  = 1;
const ADMIN_ROLE_ID              = 1;
const ADMIN_USER_ID              = 1;
const GROUP_ADMIN_ACCESS         = 4; 
const REGULAR_USER_GROUP_ID      = 9;  
const CUSTOMER_STATUS_ENABLE     = 1;
const PASSWORD_LENGTH            = 8;    

/**
 * Class Hase_customerController.
 *
 * @author  The scaffold-interface created at 2017-03-02 04:01:26am
 * @link  https://github.com/amranidev/scaffold-interface
 */
class Hase_customerController extends PermissionsController
{
    use PermissionTrait;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $connectionStatus = ConnectionManager::setDbConfig('Hase_customer');
        if (strcmp($connectionStatus['type'], "error") == 0) {
            Session::flash('type', $connectionStatus['type']);
            Session::flash('msg', $connectionStatus['message']);
            return Redirect::back()->send();
        }

        $this->codeCounter = INDEX_ZERO;
        $this->usernameCounter = INDEX_ZERO;
    }
    /**
     * Display a listing of the resource.
     *
     * @return  \Illuminate\Http\Response
     */
    public function index()
    {
        $haseCustomerAceess = $this->permissionDetails('Hase_customer', 'access');
        $haseCustomerEdit   = $this->permissionDetails('Hase_customer', 'manage');
        $permissions        = $this->getPermission("Hase_customer");
        $adminRoles        = array("access", "manage", "add", "delete");
        $accessibility      = count(array_intersect($permissions, $adminRoles));
        $hase_countries          = Country::orderBy('country_name', 'ASC')->get();
        $hase_states             = State::orderBy('state_name', 'ASC')->where('country_id',94)->get()->toArray();
        $editAccess         = 0;
        if ($haseCustomerEdit) {
            $editAccess = 1;
        }
        if ($haseCustomerAceess) {
            $user_id = $this->userId;
            return view('hase_customer.customer', compact('title', 'user_id', 'editAccess','accessibility','permissions','hase_countries','hase_states'));
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
        $haseCustomerAceess = $this->permissionDetails('Hase_customer', 'add');

        if ($haseCustomerAceess) {
            $title                = 'Create - hase_customer';
            
            if($this->merchantId == INDEX_ZERO){
                $hase_customer_groups = Group_permission::all();
            }else{
                $hase_customer_groups = Group_permission::
                                            join("identity_group_list","identity_group_list.group_id","group_permissions.group_id")
                                            ->where("identity_group_list.identity_table_id",$this->identityTableId)
                                            ->where("identity_group_list.identity_id",$this->staffId)
                                            ->get();
            }
            
            $hase_security_questions = Security_question::all()->toArray();
            $hase_cities             = City::orderBy('city_name', 'ASC')->get()->toArray();
            $hase_counties           = County::orderBy('county_name', 'ASC')->get()->toArray();
            $hase_states             = State::orderBy('state_name', 'ASC')->get()->toArray();
            $hase_countries          = Country::orderBy('country_name', 'ASC')->get()->toArray();
            $merchant_parent_types   = Merchant_type::all()->where('merchant_parent_id', 0);

            $merchants = Merchant::
                distinct()
                ->select('merchant.*', 'identity_merchant.identity_name as merchant_name')
                ->leftjoin('identity_merchant', 'identity_merchant.identity_id', '=', 'merchant.identity_id')
                ->where('merchant.merchant_id', '!=', 0)
                ->get();

            $createdAt = Carbon::now('Asia/kolkata');
            $currentIp = $request->ip();

            return view('hase_customer.create', compact('title', 'hase_customers', 'hase_customer_groups', 'hase_security_questions', 'createdAt', 'currentIp', 'hase_countries', 'hase_cities', 'hase_counties', 'hase_states', 'merchants', 'merchant_parent_types'));
        } else {
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
        if (!$request->status) {
            $request->status = 0;
        }
        if (!$request->newsletter) {
            $request->newsletter = 0;
        }

        $hase_identity          = new Identity_customer();
        $hase_customer          = new Customer();
        $hase_user              = new Portal_password();
        $merchant_customer_list = new Merchant_customer_list();
        $salt                   = uniqid();

        $merchant = Merchant::
            distinct()
            ->select('merchant.*', 'identity_merchant.identity_code as merchant_code')
            ->leftjoin('identity_merchant', 'identity_merchant.identity_id', '=', 'merchant.identity_id')
            ->where('merchant.merchant_id', '=', $request->merchant_id)
            ->get()->first();

        $merchantCode    = $merchant->merchant_code;
        $merchantCodeNew = str_replace(MERCHANT_CODE_SUFFIX, CUSTOMER_CODE_SUFFIX, $merchantCode);
        $customerCode    = substr($request->first_name, 0, 1) . substr($request->last_name, 0, 5);
        $customerCodeNew = $merchantCodeNew . $customerCode;

        $codeName = $this->generateCode($customerCodeNew);

        $hase_identity->identity_code     = strtolower($codeName);
        $hase_identity->identity_name     = $request->first_name . " " . $request->last_name;
        $hase_identity->identity_email    = $request->email;
        $hase_identity->identity_type_id  = CUSTOMER_IDENTITY_TYPE_ID;
        $hase_identity->identity_table_id = CUSTOMER_TABLE_ID;
        $hase_identity->save();

        $identityId = $hase_identity->identity_id;

        $hase_customer->identity_id       = $identityId;
        $hase_customer->identity_table_id = CUSTOMER_IDENTITY_TABLE_ID;

        $hase_customer->fname = $request->first_name;
        $hase_customer->lname = $request->last_name;
        /*$hase_customer->password = Hash::make($request->password);        */
        $hase_customer->salt = $salt;
        /*$hase_customer->security_question_id = $request->security_question_id;
        $hase_customer->security_answer = $request->security_answer;*/
        $hase_customer->newsletter = $request->newsletter;
        // $hase_customer->customer_group_id = $request->customer_group_id;
        $hase_customer->ip_address  = $request->ip_address;
        $hase_customer->date_added  = $request->date_added;
        $hase_customer->status      = $request->status;
        $hase_customer->merchant_id = $request->merchant_id;

        $hase_customer->save();
        $customerId = $hase_customer->customer_id;

        $merchant_customer_list->merchant_id = $request->merchant_id;
        $merchant_customer_list->customer_id = $customerId;

        $merchant_customer_list->save();

        foreach ($request->region_id as $key => $value) {

            $cityId                           = explode("_", $value);
            $location_list                    = new Location_list();
            $location_list->identity_id       = $identityId;
            $location_list->identity_table_id = CUSTOMER_TABLE_ID;
            $location_list->location_city_id  = $cityId[0];
            $location_list->postal_id         = 0;
            $location_list->priority          = 0;
            $location_list->status            = 1;
            $location_list->save();
        }

        $hase_user->identity_id       = $hase_customer->identity_id;
        $hase_user->identity_table_id = CUSTOMER_TABLE_ID;
        $hase_user->username          = $request->username;
        /*$hase_user->password = Hash::make($request->password);*/
        $hase_user->clear_password      = uniqid();
        $hase_user->clear_password_timestamp = time();
        /*$hase_user->salt = uniqid();*/

        $hase_user->save();

        foreach ($request->customer_group_id as $key => $value) {

            $identity_group_list                    = new Identity_group_list();
            $identity_group_list->group_id          = $value;
            $identity_group_list->identity_table_id = CUSTOMER_TABLE_ID;
            $identity_group_list->identity_id       = $hase_customer->identity_id;
            $identity_group_list->save();

        }

        // CREATE GOOGLE 2FA SECREAT KEY FOR CUSTOMER.

        $google2fa                           = app('pragmarx.google2fa');
        $password_security                   = new PasswordSecurity();
        $password_security->user_id          = $hase_user->user_id;
        $password_security->google2fa_enable = INDEX_ZERO;
        $password_security->google2fa_secret = $google2fa->generateSecretKey();
        $password_security->save();

        Session::flash('type', 'success');
        Session::flash('msg', 'Menu Options Successfully Updated');
        if ($request->submitBtn == "Save") {
            return redirect('hase_customer/' . $hase_customer->customer_id . '/edit');
        } else {
            return redirect('hase_customer');
        }
    }

    public function generateCode($orignalCodeName)
    {

        if ($this->codeCounter == 0) {
            $codeName = $orignalCodeName;
        } else {
            $codeName = $orignalCodeName . $this->codeCounter;
        }

        $code_exist = identity_customer::select('*')
            ->where('identity_code', $codeName)
            ->get()->first();

        if (!count($code_exist)) {

            return $codeName;

        } else {
            $this->codeCounter = $this->codeCounter + 1;
            return $this->generateCode($orignalCodeName);
        }
    }

    public function generateUsername($originalUsername)
    {

        if ($this->usernameCounter == INDEX_ZERO) {
            $userName = $originalUsername;
        } else {
            $userName = $originalUsername . $this->usernameCounter;
        }

        $username_exist = Portal_password::
            where('username', $userName)
            ->get()->first();

        if (!count($username_exist)) {

            return $userName;

        } else {
            $this->usernameCounter = $this->usernameCounter + INDEX_ONE;
            return $this->generateUsername($originalUsername);
        }
    }



    /**
     * Show the form for editing the specified resource.
     * @param    \Illuminate\Http\Request  $request
     * @param    int  $id
     * @return  \Illuminate\Http\Response
     */
    public function edit($id, Request $request)
    {
        $haseCustomerAceess = $this->permissionDetails('Hase_customer', 'manage');

        if ($haseCustomerAceess) {
            $title   = 'Edit - hase_customer';
            $user_id = $this->userId;
            if ($request->ajax()) {
                return URL::to('hase_customer/' . $id . '/edit');
            }
            $title                = 'Create - hase_customer';
            $hase_customer_groups = ($this->roleId == 1) ?
            Group_permission::all() :
            Identity_group_list::
                select('group_permissions.group_id', 'group_permissions.group_name')
                ->join('group_permissions', 'group_permissions.group_id', 'identity_group_list.group_id')
                ->where('identity_id', $this->staffId)
                ->where('identity_table_id', $this->identityTableId)
                ->get();

            $hase_security_questions = Security_question::all()->toArray();
            $hase_cities             = City::orderBy('city_name', 'ASC')->get()->toArray();
            $hase_counties           = County::orderBy('county_name', 'ASC')->get()->toArray();
            $hase_states             = State::orderBy('state_name', 'ASC')->get()->toArray();
            $hase_countries          = Country::orderBy('country_name', 'ASC')->get()->toArray();

            $hase_addresses = DB::table('customer_address')
                ->distinct('customer_address.address_id')
                ->select('customer_address.address_id', 'postal.postal_premise as address_1', 'postal.postal_route as address_2', 'postal.postal_city', 'postal.postal_county', 'postal.postal_state', 'postal.postal_country', 'customer_address.customer_id', 'postal.postal_postcode as postcode', 'postal.postal_telephone as telephone', 'location_country.postal_code_max', 'postal.postal_telephone_intl_code as telephone_intl_code', 'postal.postal_lat as location_lat', 'postal.postal_lng as location_lng')
                ->leftjoin('postal', 'customer_address.postal_id', '=', 'postal.postal_id')
                ->leftjoin('location_country', 'postal.postal_country', '=', 'location_country.country_id')
                ->where('customer_address.customer_id', $id)
                ->get();

            $totalAddressAdded = count($hase_addresses) + 1;
            $createdAt         = Carbon::now('Asia/kolkata');
            $currentIp         = $request->ip();
            $hase_customer     = Customer::
                select('customers.*', 'identity_group_list.*', 'identity_customer.identity_name as customer_name', 'identity_customer.identity_code as customer_code', 'identity_customer.identity_email as email', 'identity_customer.identity_telephone as telephone', 'portal_password.clear_password', 'portal_password.user_id', 'portal_password.username as username', 'password_securities.google2fa_enable',
                'customers.status as status')
                ->join('identity_customer', 'customers.identity_id', '=', 'identity_customer.identity_id')
                ->join('identity_group_list', 'identity_group_list.identity_id', '=', 'customers.identity_id')
                ->join('portal_password', 'customers.identity_id', '=', 'portal_password.identity_id')
                ->join('password_securities', 'password_securities.user_id', '=', 'portal_password.user_id')
                ->where('customer_id', $id)
                ->where('portal_password.identity_table_id', '=', CUSTOMER_TABLE_ID)
                ->get()->first();

            $identity_group_list = Identity_group_list::
                select('group_id')
                ->where('identity_id', $hase_customer->identity_id)
                ->where('identity_table_id', CUSTOMER_TABLE_ID)
                ->get();

            $group_list = array();

            foreach ($identity_group_list as $userGroup) {
                $group_list[] = $userGroup['group_id'];
            }

            return view('hase_customer.edit', compact('title', 'hase_customer', 'hase_customer_groups', 'hase_security_questions', 'currentIp', 'hase_countries', 'group_list', 'hase_counties', 'hase_states', 'hase_cities', 'hase_addresses', 'totalAddressAdded', 'user_id'));
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
    public function update($id, Request $request)
    {
        if (!$request->status) {
            $request->status = 0;
        }
        if (!$request->newsletter) {
            $request->newsletter = 0;
        }
        $hase_customer = Customer::findOrfail($id);
        $hase_identity = Identity_customer::findOrfail($hase_customer->identity_id);
        $salt          = uniqid();

        if ($request->customer_code != "") {

            $request->customer_code = strtolower($request->customer_code);

            $code_exist = Identity_customer::select('*')
                ->where('identity_id', '!=', $request->identity_id)
                ->where('identity_code', $request->customer_code)
                ->get()->first();

            if (count($code_exist) == 0) {

                $hase_identity->identity_code = $request->customer_code;
            }

        }

        $hase_identity->identity_name    = $request->first_name . " " . $request->last_name;
        $hase_identity->identity_email   = $request->email;
        $hase_identity->identity_type_id = '8';
        $hase_identity->save();
        $isExistuser = passwordSecurity::
            where('user_id', '=', $request->user_id)
            ->first();

        if ($isExistuser != null) {

            DB::table('password_securities')
                ->where('user_id', $request->user_id)->update(array('google2fa_enable' => (isset($request->google2fa_enable) ? 1 : 0)));
        }

        /*if($request->password != '')
        {
        $hase_customer->password = Hash::make($request->password);
        }*/

        $hase_customer->fname = $request->first_name;
        $hase_customer->lname = $request->last_name;
        $hase_customer->salt  = $salt;
        /*$hase_customer->security_question_id = $request->security_question_id;
        $hase_customer->security_answer = $request->security_answer;*/
        $hase_customer->newsletter = $request->newsletter;
        //$hase_customer->customer_group_id = $request->customer_group_id;
        if ($this->userId == ADMIN_USER_ID) {
            $hase_customer->status = $request->status;
        }
        $hase_customer->save();

        if ($this->roleId == ADMIN_ROLE_ID) {

            Identity_group_list::whereNotIn('group_id', $request->customer_group_id)
                ->where('identity_id', $hase_customer->identity_id)
                ->where('identity_table_id', CUSTOMER_TABLE_ID)
                ->delete();

            foreach ($request->customer_group_id as $key => $value) {

                $isExist = Identity_group_list::
                    where('group_id', '=', $value)
                    ->where('identity_id', $hase_customer->identity_id)
                    ->where('identity_table_id', CUSTOMER_TABLE_ID)
                    ->first();

                if ($isExist == null) {

                    $identity_group_list                    = new Identity_group_list();
                    $identity_group_list->group_id          = $value;
                    $identity_group_list->identity_table_id = CUSTOMER_TABLE_ID;
                    $identity_group_list->identity_id       = $hase_customer->identity_id;
                    $identity_group_list->save();
                }
            }
        }

        $customerUser = Portal_password::
            where('identity_id', $request->identity_id)
            ->where('identity_table_id', CUSTOMER_TABLE_ID)
            ->get()
            ->first();
        $hase_user = Portal_password::findOrfail($customerUser->user_id);

        if ($request->password != "" && $hase_user->identity_id == $this->staffId) {
            $salt = uniqid();

            $hase_user->username            = $request->username;
            $hase_user->password            = Hash::make($request->password);
            $hase_user->clear_password      = null;
            $hase_user->salt                = $salt;
            $hase_user->save();
        }

        $lastInsertedCustomerResponse = \Response::json(array('success' => true, 'last_insert_id' => $hase_customer->customer_id), 200);

        $lastInsertedCustomerId = $lastInsertedCustomerResponse->getData()->last_insert_id;
        if ($request->address) {
            $addressValueExist = array();
            foreach ($request->address as $addressValue) {
                if (array_key_exists('address_id', $addressValue)) {
                    $addressValueExist[] = $addressValue['address_id'];
                }
            }
            hase_address::whereNotIn('address_id', $addressValueExist)->where('customer_id', $lastInsertedCustomerId)->delete();
            foreach ($request->address as $address) {
                if (!array_key_exists('address_id', $address)) {
                    $hase_addresses = new Address();
                    $hase_postal    = new Portal();
                } else {
                    $hase_addresses = Address::firstOrCreate(['address_id' => $address['address_id']]);

                    $hase_postal = Portal::firstOrCreate(['postal_id' => $hase_addresses->postal_id]);
                }

                $hase_postal->postal_route               = $address['address_1'];
                $hase_postal->postal_premise             = $address['address_2'];
                $hase_postal->postal_city                = $address['city_id'];
                $hase_postal->postal_state               = $address['state_id'];
                $hase_postal->postal_county              = $address['county_id'];
                $hase_postal->postal_country             = $address['country_id'];
                $hase_postal->postal_postcode            = isset($address['postcode']) ? $address['postcode'] : '';
                $hase_postal->postal_telephone           = $address['telephone'];
                $hase_postal->postal_telephone_intl_code = $address['telephone_intl_code'];
                $hase_postal->postal_lat                 = $address['location_lat'];
                $hase_postal->postal_lng                 = $address['location_lng'];

                $hase_postal->save();
                $postalId = $hase_postal->postal_id;

                $hase_addresses->customer_id = $lastInsertedCustomerId;
                $hase_addresses->postal_id   = $postalId;
                $hase_addresses->save();

            }
        } else {
            Address::where('customer_id', $lastInsertedCustomerId)->delete();
        }

        Session::flash('type', 'success');
        Session::flash('msg', 'Customer Successfully Updated');
        if ($request->submitBtn == "Save") {
            return redirect('hase_customer/' . $hase_customer->customer_id . '/edit');
        } else {
            return redirect('hase_customer');
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
        $haseCustomerAceess = $this->permissionDetails('Hase_customer', 'delete');
        if ($haseCustomerAceess) {
            $hase_customer         = Customer::findOrfail($id);
            $hase_customer->status = 0;
            $hase_customer->save();
            Session::flash('type', 'success');
            Session::flash('msg', 'customer Successfully Deleted');
            return redirect('hase_customer');
        } else {
            return Redirect::back()->with('message', 'You are not authorized to use this functionality!');
        }
    }

    public function getCustomerMerchants(Request $request)
    {
        $merchants = Merchant::
            select(
            'merchant.*',
            'identity_merchant.identity_name as merchant_name',
            'identity_merchant.identity_code as merchant_code'
        )
            ->join('identity_merchant', 'identity_merchant.identity_id', 'merchant.identity_id')
            ->where('merchant.merchant_id', "!=", 0)
            ->where('merchant.merchant_type_id',$request->merchant_type_id)
            ->get();

        return json_encode($merchants);
    }

    public function getAllMerchants(Request $request)
    {
        $merchants = Merchant::
            select(
            'merchant.*',
            'identity_merchant.identity_name as merchant_name',
            'identity_merchant.identity_code as merchant_code'
        )
            ->join('identity_merchant', 'identity_merchant.identity_id', 'merchant.identity_id')
            ->where('merchant.merchant_id', "!=", 0)
            ->get();

        return json_encode($merchants);
    }

    public function getCustomerGroup(Request $request)
    {
        $customerGroups = ($this->merchantId == 0) ?
        Group_permission::all() :
        Group_permission::
            where('group_id', '>', $this->roleId)
            ->where('group_permission.group_name', '!=', 'None')->get();
        return json_encode($customerGroups);
    }
   
    public function getMerchantCustomers(Request $request, $merchantId)
    {
        $total_record       = INDEX_ZERO;
        $isdelete           = INDEX_ZERO;
        $isView             = INDEX_ZERO;
        
        $haseCustomerDelete = $this->permissionDetails('Hase_customer', 'delete');
        $haseCustomerAceess = $this->permissionDetails('Hase_customer', 'access');

        if ($haseCustomerDelete) {
            $isdelete = INDEX_ONE;
        }
        if ($haseCustomerAceess) {
            $isView = INDEX_ONE;
        }
        if ($merchantId != INDEX_ZERO) {
            $where['merchant_customer_list.merchant_id'] = $merchantId;
        } else {
            $where = array();
        }

        $groupIDList   = array();
        $userGroupList = Identity_group_list::
            where('identity_group_list.identity_id', $this->staffId)
            ->where('identity_group_list.identity_table_id', $this->identityTableId)
            ->select('identity_group_list.group_id')
            ->get();

        foreach ($userGroupList as $userGroup) {
            $groupIDList[] = $userGroup['group_id'];
        }
        
        $permissions = $this->getPermission("Hase_customer");
        $adminReoles = array("access", "manage", "add", "delete");
        $matchRoles  = count(array_intersect($permissions, $adminReoles));

        if (in_array(INDEX_ONE, $groupIDList)) {

            $merchant_customer_lists = Merchant_customer_list::
                distinct()
                ->select(
                    'customers.*',
                    'merchant.merchant_id',
                    'merchant_customer_list.priority',
                    'identity_customer.identity_name as customer_name',
                    'identity_customer.identity_code as customer_code',
                    'identity_customer.identity_email as email',
                    'portal_password.clear_password',
                    'portal_password.user_id',
                    'portal_password.username as username',
                    'identity_merchant.identity_name as merchant_name',
                    'group_permissions.group_name',
                    'postal.postal_premise as location_name',
                    'password_securities.google2fa_enable as google2fa_enable'
                )
                ->join('merchant', 'merchant.merchant_id', 'merchant_customer_list.merchant_id')
                ->leftjoin('identity_merchant', 'identity_merchant.identity_id', '=', 'merchant.identity_id')
                ->join('customers', 'customers.customer_id', 'merchant_customer_list.customer_id')
                ->join('identity_customer', 'identity_customer.identity_id', 'customers.identity_id')
                ->join('portal_password', 'customers.identity_id', '=', 'portal_password.identity_id')
                ->join('identity_group_list', 'identity_group_list.identity_id', '=', 'customers.identity_id')
                ->join('group_permissions', 'group_permissions.group_id', '=', 'identity_group_list.group_id')
                ->join('location_list', function ($merchant_customer_lists) {
                    $merchant_customer_lists->on('customers.identity_id', '=', 'location_list.identity_id')->where('location_list.identity_table_id', CUSTOMER_TABLE_ID);
                })
                ->join('postal', 'postal.postal_id', '=', 'location_list.postal_id')

                ->where(function ($q) use ($where) {
                    foreach ($where as $key => $value) {
                        $q->where($key, '=', $value);
                    }
                })
                ->where('merchant_customer_list.merchant_id', '>', INDEX_ZERO)
                ->where('identity_group_list.identity_table_id', '=', CUSTOMER_TABLE_ID)
                ->leftjoin('password_securities', 'password_securities.user_id', '=', 'portal_password.user_id')
                ->where('portal_password.identity_table_id', '=', CUSTOMER_TABLE_ID)
                ->groupBy('customers.customer_id');
        } else if ($this->identityTableId == STAFF_TABLE_ID) {

            $merchant_customer_lists = Merchant_customer_list::
                distinct()
                ->select(
                    'customers.*',
                    'merchant.merchant_id',
                    'merchant_customer_list.priority',
                    'identity_customer.identity_name as customer_name',
                    'identity_customer.identity_code as customer_code',
                    'identity_customer.identity_email as email',
                    'portal_password.clear_password',
                    'portal_password.user_id',
                    'portal_password.username as username',
                    'identity_merchant.identity_name as merchant_name',
                    'group_permissions.group_name',
                    'postal.postal_premise as location_name',
                    'password_securities.google2fa_enable as google2fa_enable'
                )
                ->join('merchant', 'merchant.merchant_id', 'merchant_customer_list.merchant_id')
                ->leftjoin('identity_merchant', 'identity_merchant.identity_id', '=', 'merchant.identity_id')
                ->join('customers', 'customers.customer_id', 'merchant_customer_list.customer_id')
                ->join('identity_customer', 'identity_customer.identity_id', 'customers.identity_id')
                ->join('portal_password', 'customers.identity_id', '=', 'portal_password.identity_id')
                ->join('identity_group_list', 'identity_group_list.identity_id', '=', 'customers.identity_id')
                ->join('group_permissions', 'group_permissions.group_id', '=', 'identity_group_list.group_id')
                ->join('location_list', function ($merchant_customer_lists) {
                    $merchant_customer_lists->on('customers.identity_id', '=', 'location_list.identity_id')->where('location_list.identity_table_id', CUSTOMER_TABLE_ID);
                })
                ->join('postal', 'postal.postal_id', '=', 'location_list.postal_id')

                ->where(function ($q) use ($where) {
                    foreach ($where as $key => $value) {
                        $q->where($key, '=', $value);
                    }
                })
                ->where('merchant_customer_list.merchant_id', $this->merchantId)
                ->where('identity_group_list.identity_table_id', '=', CUSTOMER_TABLE_ID)
                ->leftjoin('password_securities', 'password_securities.user_id', '=', 'portal_password.user_id')
                ->where('portal_password.identity_table_id', '=', CUSTOMER_TABLE_ID)
                ->groupBy('customers.customer_id');
        } else {
            $merchant_customer_lists = Merchant_customer_list::
                distinct()
                ->select(
                    'customers.*',
                    'merchant.merchant_id',
                    'merchant_customer_list.priority',
                    'identity_customer.identity_name as customer_name',
                    'identity_customer.identity_code as customer_code',
                    'identity_customer.identity_email as email',
                    'portal_password.clear_password',
                    'portal_password.user_id',
                    'portal_password.username as username',
                    'identity_merchant.identity_name as merchant_name',
                    'group_permissions.group_name',
                    'postal.postal_premise as location_name',
                    'password_securities.google2fa_enable as google2fa_enable'
                )
                ->join('merchant', 'merchant.merchant_id', 'merchant_customer_list.merchant_id')
                ->leftjoin('identity_merchant', 'identity_merchant.identity_id', '=', 'merchant.identity_id')
                ->join('customers', 'customers.customer_id', 'merchant_customer_list.customer_id')
                ->join('identity_customer', 'identity_customer.identity_id', 'customers.identity_id')
                ->join('portal_password', 'customers.identity_id', '=', 'portal_password.identity_id')
                ->join('identity_group_list', 'identity_group_list.identity_id', '=', 'customers.identity_id')
                ->join('group_permissions', 'group_permissions.group_id', '=', 'identity_group_list.group_id')
                ->join('location_list', function ($merchant_customer_lists) {
                    $merchant_customer_lists->on('customers.identity_id', '=', 'location_list.identity_id')->where('location_list.identity_table_id', CUSTOMER_TABLE_ID);
                })
                ->join('postal', 'postal.postal_id', '=', 'location_list.postal_id')

                ->where(function ($q) use ($where) {
                    foreach ($where as $key => $value) {
                        $q->where($key, '=', $value);
                    }
                })
                ->where('identity_customer.identity_id', $this->staffId)
                ->where('identity_group_list.identity_table_id', '=', CUSTOMER_TABLE_ID)
                ->leftjoin('password_securities', 'password_securities.user_id', '=', 'portal_password.user_id')
                ->where('portal_password.identity_table_id', '=', CUSTOMER_TABLE_ID)
                ->groupBy('customers.customer_id');
        }
        if (isset($request->filter['filters'])) {
            $filterValue = $request->filter['filters'][INDEX_ZERO]['value'];
            $filterField = $request->filter['filters'][INDEX_ZERO]['field'];
            $merchant_customer_lists->where('identity_merchant.identity_name', '=', $filterValue);
        }
        $total_record = $total_record + $merchant_customer_lists->get()->count();
        if (isset($request->take)) {
            $merchant_customer_lists->offset($request->skip)->limit($request->take);
        }
        $merchant_customer_lists = $merchant_customer_lists->get();

        foreach ($merchant_customer_lists as $key => $value) {
            $merchant_customer_lists[$key]['isdelete'] = $isdelete;
            $merchant_customer_lists[$key]['isView'] = $isView;

            // GET LIST OF ASSOCIATED GROUPS WITH CUSTOMER
            $group_list          = array();
            $identity_group_list = Identity_group_list::
            select('identity_group_list.group_id', 'group_permissions.group_name')
            ->join('group_permissions', 'group_permissions.group_id', '=', 'identity_group_list.group_id')
            ->where('group_permissions.group_name', '!=', 'None')
            ->where('identity_id', $value['identity_id'])
            ->where('identity_table_id',CUSTOMER_TABLE_ID)
            ->get()->toArray();

            foreach ($identity_group_list as $identity_group_list_key => $identity_group_list_value) {
                    $group_list[$identity_group_list_key]['group_id'] = $identity_group_list_value['group_id'];
                    $group_list[$identity_group_list_key]['group_name'] = $identity_group_list_value['group_name'];
                }
            $merchant_customer_lists[$key]['group_details'] = $group_list;

        }
        
        $merchant_customer_lists_data['customer_list'] =$merchant_customer_lists->toArray();
        if(in_array("add", $permissions)){

            $customerUserAddNewDetails[INDEX_ZERO] = array("user_id"=> $this->userId,"customer_id" => INDEX_ZERO, "identity_id" => '', "identity_table_id" => CUSTOMER_IDENTITY_TABLE_ID, "fname" => "", "lname" => "", "newsletter" => INDEX_ZERO, "customer_group_id" => INDEX_ZERO, "status" => INDEX_ZERO, "username" => "", "group_name" => '', "google2fa_enable" => null, "isdelete" => $isdelete, "isView" => $isView);
            $merchant_customer_lists_data['customer_list'] = array_merge($customerUserAddNewDetails, $merchant_customer_lists_data['customer_list']);

        }
        
        $merchant_customer_lists_data['total']         = $total_record;

        return json_encode($merchant_customer_lists_data);

    }

    public function updateCustomer(Request $request)
    {        
        $customerId = $request->customer_id;
        $merchantId = $request->merchant_id;
        $key        = $request->key;
        $value      = $request->value;
        $haseCustomerAceess = $this->permissionDetails('Hase_customer', 'manage');
        if ($customerId == INDEX_ZERO) {
            $haseCustomerAddAceess = $this->permissionDetails('Hase_customer', 'add');
            if ($haseCustomerAddAceess) {
                if (!$request->status) {
                    $request->status = INDEX_ZERO;
                }
                if (!$request->newsletter) {
                    $request->newsletter = INDEX_ZERO;
                }
                $createdAt = date('Ymd').time();
                $currentIp = $request->ip();
                if ($key === "fname") {
                    $hase_identity          = new Identity_customer();
                    $hase_customer          = new Customer();
                    $hase_user              = new Portal_password();
                    $merchant_customer_list = new Merchant_customer_list();
                    $salt                   = uniqid();
                    $this->usernameCounter  = INDEX_ZERO;

                    $merchant = Merchant::
                        distinct()
                        ->select('merchant.*', 'identity_merchant.identity_code as merchant_code')
                        ->leftjoin('identity_merchant', 'identity_merchant.identity_id', '=', 'merchant.identity_id')
                        ->where('merchant.merchant_id', '=', INDEX_ONE)
                        ->get()->first();

                    $merchantCode                     = $merchant->merchant_code;
                    $merchantCodeNew                  = str_replace(MERCHANT_CODE_SUFFIX, CUSTOMER_CODE_SUFFIX, $merchantCode);
                    $customerCode                     = substr($value, 0, 1) . substr($value, 0, 5);
                    $customerCodeNew                  = $merchantCodeNew . $customerCode;
                    $codeName                         = $this->generateCode($customerCodeNew);
                    $hase_identity->identity_code     = strtolower($codeName);
                    $hase_identity->identity_name     = $value;
                    $hase_identity->identity_type_id  = CUSTOMER_IDENTITY_TYPE_ID;
                    $hase_identity->identity_table_id = CUSTOMER_TABLE_ID;
                    $hase_identity->save();
                    $identityId                       = $hase_identity->identity_id;
                    $hase_customer->identity_id       = $identityId;
                    $hase_customer->identity_table_id = CUSTOMER_IDENTITY_TABLE_ID;

                    $hase_customer->fname       = $value;
                    $hase_customer->salt        = $salt;
                    $hase_customer->newsletter  = $request->newsletter;
                    $hase_customer->ip_address  = $currentIp;
                    $hase_customer->date_added  = $createdAt;
                    $hase_customer->status      = CUSTOMER_STATUS_ENABLE;
                    $hase_customer->merchant_id = $this->merchantId;
                    $hase_customer->save();
                    $customerId                          = $hase_customer->customer_id;
                    $merchant_customer_list->merchant_id = $this->merchantId;
                    $merchant_customer_list->customer_id = $customerId;
                    $location_list                       = new Location_list();
                    $location_list->identity_id          = $identityId;
                    $location_list->identity_table_id    = CUSTOMER_TABLE_ID;
                    $location_list->location_city_id     = INDEX_ONE;
                    $location_list->postal_id            = INDEX_ZERO;
                    $location_list->priority             = INDEX_ZERO;
                    $location_list->status               = INDEX_ONE;
                    $location_list->save();
                    $merchant_customer_list->save();
                    $hase_user->identity_id         = $hase_customer->identity_id;
                    $hase_user->identity_table_id   = CUSTOMER_TABLE_ID;
                    $hase_user->clear_password      = uniqid();
                    $userCode                       = substr($value, 0, 4);
                    $userCodeNew                    = $this->generateUsername($userCode);
                    $hase_user->username            =strtolower($userCodeNew);
                    $hase_user->clear_password_timestamp =time();
                    $hase_user->save();
                    $identity_group_list                    = new Identity_group_list();
                    $identity_group_list->identity_table_id = CUSTOMER_TABLE_ID;
                    $identity_group_list->identity_id       = $hase_customer->identity_id;
                    $identity_group_list->group_id          = REGULAR_USER_GROUP_ID;
                    $identity_group_list->save();

                    $password_security                   = new PasswordSecurity();
                    $password_security->user_id          = $hase_user->user_id;
                    $password_security->google2fa_enable = INDEX_ZERO;
                    $password_security->google2fa_secret = NULL;
                    $password_security->save();
                    return array("type" => "success", "message" => 'New Customer Added');
                }
                else{
                    return array("type" => "error", "message" => 'First name is required.');
                }
            } else {
                return array("type" => "error", "message" => 'You are not authorized to use this functionality!');
            }
        } else {
        if ($haseCustomerAceess) {
            if ($key === "password") {
                    $password     = $value;
                    $uppercase    = preg_match('@[A-Z]@', $password);
                    $lowercase    = preg_match('@[a-z]@', $password);
                    $number       = preg_match('@[0-9]@', $password);
                    $specialChars = preg_match('@[^\w]@', $password);

                    if (!$uppercase || !$lowercase || !$number || !$specialChars || strlen($password) < PASSWORD_LENGTH) {
                        return array("type" => "error", "message" => 'Password should be at least 8 characters in length and should include at least one upper case letter, one number, and one special character.');
                    } else {
                        $users_details = Portal_password::where('identity_id', $customerId)->where('identity_table_id', CUSTOMER_TABLE_ID)->first();
                        if ($users_details->identity_id == $this->staffId) {
                            $hase_user = Portal_password::findOrfail($users_details->user_id);
                            $salt                           = uniqid();
                            $hase_user->password            = Hash::make($value);
                            $hase_user->clear_password      = null;
                            $hase_user->salt                = $salt;
                            $hase_user->save();
                            $actionUrl = "/".session('staffUrl');
                            $message = "<a href='".URL::to($actionUrl)."'>".session('staffName')."</a> <strong>Updated Password</strong>";
                            $action = "Updated Password";
                            PermissionTrait::addActivityLog($action,$message); 
                            return array("type" => "success", "message" => 'Password Updated');
                        } else {
                            return array("type" => "error", "message" => 'You are not allowed to change password for others');
                        }
                    }
                }
            if ($key == "status") {
                $customerObj         = Customer::findOrFail($customerId);
                $customerObj->status = $value;
                $customerObj->save();
                return array("type" => "success", "message" => 'Status Updated');
            }
            if ($key === "newsletter") {
                $customer_newsletter             = Customer::findOrFail($customerId);
                $customer_newsletter->newsletter = $value;
                $customer_newsletter->save();
                return array("type" => "success", "message" => 'Newsletter Updated');
            }
            if ($key === "username") {
                $user = Portal_password::where('username', $value)->first();
                if (isset($user->user_id)) {
                    return array("type" => "success", "message" => 'The username is not available');
                } else {
                    $users_details = Portal_password::where('identity_id', $customerId)->where('identity_table_id', CUSTOMER_TABLE_ID)->first();
                    $group_details = Group_permission::where('group_id', $this->roleId)->first();
                    if ($group_details->group_name === 'Portal Admin') {
                        Portal_password::where('identity_id', $customerId)
                        ->where('identity_table_id', CUSTOMER_TABLE_ID)
                        ->update(['username' => $value]);
                        return array("type" => "success", "message" => 'Username update successful');
                    }else{
                        return array("type" => "error", "message" => 'You are not allowed to change username'); 
                    }
                }
            }
            if ($key === "fname") {
                Customer::where('customer_id', $customerId)->update(['fname' => $value]);
                return array("type" => "success", "message" => 'Customer First Name Updated');
            }
            if ($key === "lname") {
                
                $hase_identity  = Identity_customer::findOrFail($customerId);
                $hase_customer  = Customer::findOrFail($customerId);
                $this->usernameCounter  = INDEX_ZERO;

                $merchant = Merchant::
                        distinct()
                        ->select('merchant.*', 'identity_merchant.identity_code as merchant_code')
                        ->leftjoin('identity_merchant', 'identity_merchant.identity_id', '=', 'merchant.identity_id')
                        ->where('merchant.merchant_id', '=',$hase_customer->merchant_id)
                        ->get()->first();

                    $merchantCode                     = $merchant->merchant_code;
                    $merchantCodeNew                  = str_replace(MERCHANT_CODE_SUFFIX, CUSTOMER_CODE_SUFFIX, $merchantCode);
                    $customerCode                     = substr($hase_customer->fname, 0, 1) . substr($value, 0, 5);
                    $customerCodeNew                  = $merchantCodeNew . $customerCode;
                    $codeName                         = $this->generateCode($customerCodeNew);
                    $hase_identity->identity_code     = strtolower($codeName);
                    $hase_identity->identity_name     = $hase_customer->fname." ".$value;
                    $hase_identity->identity_type_id  = CUSTOMER_IDENTITY_TYPE_ID;
                    $hase_identity->identity_table_id = CUSTOMER_TABLE_ID;
                    $hase_identity->save();
                    $users_details       = Portal_password::where('identity_id', $customerId)->where('identity_table_id', CUSTOMER_TABLE_ID)->first();
                    $hase_user           = Portal_password::findOrfail($users_details->user_id);
                    $userCode            = substr($hase_customer->fname, 0, 4) . substr($value, 0, 4);
                    $userCodeNew         = $this->generateUsername($userCode);
                    $hase_user->username = strtolower($userCodeNew);
                    $hase_user->save();
                Customer::where('customer_id', $customerId)->update(['lname' => $value]);
                return array("type" => "success", "message" => 'Customer Last Name Updated');
            }
            if ($key === "email") {
                Identity_customer::where('identity_id', $customerId)->where('identity_table_id', CUSTOMER_TABLE_ID)->update(['identity_email' => $value]);
                return array("type" => "success", "message" => 'Customer Email Updated');
            }
            if ($key === "merchant_name") {
                $merchant = DB::table('identity_merchant')->where('identity_name', '=', $value)->first();
                Customer::where('customer_id', $customerId)->update(['merchant_id' => $merchant->identity_id]);
                Merchant_customer_list::where('customer_id', $customerId)->update(['merchant_id' => $merchant->identity_id]);
                return array("type" => "success", "message" => 'Merchant Updated');
            }
            if ($key === "group_name") {
                Identity_group_list::
                        where('identity_id', $request->identity_id)
                        ->where('identity_table_id', CUSTOMER_TABLE_ID)
                        ->delete();
                
                foreach ($value as $groupid) {
                    $identity_group_list                    = new Identity_group_list();
                    $identity_group_list->group_id          = $groupid;
                    $identity_group_list->identity_table_id = CUSTOMER_TABLE_ID;
                    $identity_group_list->identity_id       = $request->identity_id;
                    $identity_group_list->save();
                }

                return array("type" => "success", "message" => 'Staff Roles Updated');
            }
            if ($key === "google2fa_enable") {
                $users_details = Portal_password::where('identity_id', $customerId)->where('identity_table_id', CUSTOMER_TABLE_ID)->first();
                $isExistuser   = passwordSecurity::where('user_id', $users_details->user_id)->first();
                if ($isExistuser != null) {
                    if($value){
                        DB::table('password_securities')->where('user_id', $users_details->user_id)->update(['google2fa_enable' => $value]);
                    }else{
                        DB::table('password_securities')->where('user_id', $users_details->user_id)->update(['google2fa_enable' => $value,'google2fa_secret' => NULL]);
                    }
                }
                if ($value == INDEX_ONE) {
                        $google2faStatus = "On";
                } else {
                        $google2faStatus = "Off";
                }
                $actionUrl = "/" . session('staffUrl');
                if ($users_details->username != session('staffName')) {
                    $message = "<a href='" . URL::to($actionUrl) . "'>" . session('staffName') . "</a> <strong>Google2FA is " . $google2faStatus ." for " . $users_details->username . "</strong>";
                } else {
                    $message = "<a href='" . URL::to($actionUrl) . "'>" . session('staffName') . "</a> <strong>Google2FA is ". $google2faStatus . "</strong>";
                }
                $action    = "Google2fa Status Updated";
                PermissionTrait::addActivityLog($action, $message);
                return array("type" => "success", "message" => 'Google2fa Updated');
            }
        } else {
            return array("type" => "error", "message" => 'You are not authorized to use this functionality!');
        }
    }

    }
    public function updateLocation(Request $request){
        $key        = $request->key;
        $value      = $request->value;
        $list_id    = $request->list_id;
        if($key === 'status'){
            Location_list::where('list_id', $list_id)->update(['status' => $value]);
            return array("type" => "success", "message" => 'Status Updated');
        }
        if($key === 'priority'){
            Location_list::where('list_id', $list_id)->update(['priority' => $value]);
            return array("type" => "success", "message" => 'Priority Updated');
        }

    }
    public function reset(Request $request)
    {
        $userSet = array(
            'password'            => "",
            'clear_password'      => uniqid(),
            'clear_password_timestamp' => time(),
        );

        $user = DB::table('portal_password')
            ->where('user_id', $request->user_id)->update($userSet);
        $userName = DB::table('portal_password')->where('user_id', '=', $request->user_id)->first();
        $actionUrl = "/".session('staffUrl');
        if($userName != session('staffName')){
           $message = "<a href='".URL::to($actionUrl)."'>".session('staffName')."</a> <strong>Reset Password Of ".$userName->username."</strong>"; 
       } else {
            $message = "<a href='".URL::to($actionUrl)."'>".session('staffName')."</a> <strong>Reset Password</strong>";
       }
        $action = "Reset Password";
        PermissionTrait::addActivityLog($action,$message);
        return array("type" => "success", "message" => 'Reset Password Successfully');
    }

    public function checkUsername(Request $request)
    {
        $user = DB::table('portal_password')
            ->where('username', $request->username)
            ->get()
            ->first();

        $result['valid'] = (isset($user->user_id)) ? false : true;
        echo json_encode($result);
    }

    public function checkEditUsername(Request $request)
    {
        $user = DB::table('portal_password')
            ->where('username', $request->username)
            ->where('user_id', '!=', $request->user_id)
            ->get()
            ->first();

        $result['valid'] = (isset($user->user_id)) ? false : true;
        echo json_encode($result);
    }

    public function updateLocationPostal(Request $request){
    	try {
    		$identity_id = $request->identity_id;
	    	$identity_table_id = $request->identity_table_id;

	    	foreach ($request->postals as $postal) {
	    		            
	            if($postal['postal_id']){
	                $postalObj = Postal::findOrfail($postal['postal_id']);
	            }else{
	                $identity_postal = new Identity_postal();
	                $identity_postal->identity_type_id = POSTAL_IDENTITY_TYPE_ID;
	                $identity_postal->identity_name = $postal['premise'];
	                $identity_postal->save();

	                $postalObj = new Postal();
	                $postalObj->identity_id = $identity_postal->identity_id;
	            }

	            $postalObj->postal_premise 			= $postal['premise'];
	            $postalObj->postal_subpremise 		= $postal['subpremise'];
	            $postalObj->postal_street_number	=  ($postal['street_number'] != "None")?
	                            $postal['street_number'] : INDEX_ZERO ;
	            $postalObj->postal_route 			=  ($postal['route'] != "None")?
	                            $postal['route'] : '' ;
	            $postalObj->postal_neighborhood		=   ($postal['neighborhood'] != "None")?
	                            $postal['neighborhood'] : '' ;
	            $postalObj->postal_postcode			=   ($postal['neighborhood'] != "None")?
	                            $postal['neighborhood'] : INDEX_ZERO ;
	            $postalObj->postal_lat				=    $postal['lat'];
	            $postalObj->postal_lng				=    $postal['lng'];
	            $postalObj->postal_country			=    $postal['country_id'];
	            $postalObj->postal_state			=    $postal['state_id'];
	            $postalObj->postal_country 			=    $postal['county_id'];
	            $postalObj->postal_city				=    $postal['city_id'];
	            $postalObj->save();

	            if($postal['list_id']){
	            	$location_list = Location_list::findOrfail($postal['list_id']);
	                $location_list->postal_id = $postalObj->postal_id;
	                $location_list->save();
	            }else{   
	            	$location_list = new Location_list();             
	                $location_list->identity_id = $identity_id;
	                $location_list->identity_table_id = $identity_table_id;
	                $location_list->location_city_id = $postalObj->postal_city;
	                $location_list->postal_id = $postalObj->postal_id;
	                $location_list->priority = INDEX_ZERO;
	                $location_list->save();
	            }
	    	}
	    	return json_encode(array("type" => "success"));
    	} catch (Exception $e) {
    		return json_encode(array("type" => "error", "message" => $e->getMessage()));
    	}    	
    }

    public function getLocationsData(Request $request)
    {
        $location_list = Location_list::
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

            ->join('postal','location_list.postal_id','postal.postal_id')
            ->join('location_city','location_city.city_id','location_list.location_city_id')
            ->join('location_state','location_city.state_id','location_state.state_id')
            ->join('location_county','location_city.county_id','location_county.county_id')
            ->join('location_country','location_city.country_id','location_country.country_id')
            ->where('location_list.identity_id',$request->identity_id)
            ->where('location_list.identity_table_id',$request->identity_table_id)
            ->get();

        return json_encode($location_list);
    }

}
