<?php

namespace App\Http\Controllers;

use App\Http\Controllers\PermissionsController;
use App\City;
use App\Group_permission;
use App\Helpers\ConnectionManager;
use App\Http\Traits\PermissionTrait;
use App\Identity_group_list;
use App\Identity_staff;
use App\Location_list;
use App\Merchant;
use App\Merchant_type;
use App\passwordSecurity;
use App\Portal_password;
use App\Postal;
use App\Staff;
use Carbon\Carbon;
use DB;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Redirect;
use Session;
use stdClass;
use URL;

/**
 * Class Hase_staffController.
 *
 * @author  The scaffold-interface created at 2017-03-08 07:43:27am
 * @link  https://github.com/amranidev/scaffold-interface
 */
class Hase_staffController extends PermissionsController
{
    use PermissionTrait;
    const STAFF_TABLE_IDENTITY_TYPE          = 35;
    const MERCHANT_TABLE_IDENTITY_TYPE       = 8;
    const IDENTITY_STAFF_TABLE_IDENTITY_TYPE = 9;
    const INIT_VALUE                         = 0;
    const FIRST_VALUE                        = 1;
    const FULL_ACCESS                        = 4;
    const PASSWORD_LENGTH                    = 8; 

    public function __construct()
    {
        parent::__construct();

        $connectionStatus = ConnectionManager::setDbConfig('Hase_staff');
        if (strcmp($connectionStatus['type'], "error") == self::INIT_VALUE) {
            Session::flash('type', $connectionStatus['type']);
            Session::flash('msg', $connectionStatus['message']);
            return Redirect::back()->send();
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return  \Illuminate\Http\Response
     */
    public function index()
    {
        if ($this->permissionDetails('Hase_staff', 'access')) {
            $title       = 'Index - hase_staff';
            $permissions = $this->getPermission("Hase_staff");
            $adminReoles     = array("access", "manage", "add", "delete");
            $accessibility   = count(array_intersect($permissions, $adminReoles));
            $locationId  = $this->locationId;
            $user_id     = $this->userId;
            return view('hase_staff.staff', compact('permissions', 'user_id', 'title',"accessibility"));
        } else {
            return Redirect::back()->with('message', 'You are not authorized to use this functionality!');
        }
    }
    public function getStaffList(Request $request)
    {
        $userGroupList = Identity_group_list::where('identity_group_list.identity_id', session('staffId'))
            ->where('identity_group_list.identity_table_id', session('identity_table_id'))
            ->select('identity_group_list.group_id')
            ->get();
        foreach ($userGroupList->toArray() as $userGroupList) {
            foreach ($userGroupList as $userGroupListValue) {
                if ($userGroupListValue == self::FIRST_VALUE) {
                    $adminGroupValue = $userGroupListValue;
                }

                break;
            }
        }
        $total_record      = self::INIT_VALUE;
        $commaSeparators   = '';
        $group_id_list     = self::INIT_VALUE;
        $multipleUsersList = Identity_group_list::where('identity_id', session('staffId'))->where('identity_table_id', session('identity_table_id'))->get();
        foreach ($multipleUsersList as $multipleUsersListValue) {
            $group_id = $multipleUsersListValue->group_id;
            $group_id_list .= $commaSeparators . $group_id;
            $commaSeparators = ',';

        }
        $groups_id       = explode(",", $group_id_list);
        $groups_id_Array = $groups_id;
        $permissions     = $this->getPermission("Hase_staff");
        $adminReoles     = array("access", "manage", "add", "delete");
        $matchRoles      = count(array_intersect($permissions, $adminReoles));
        $locationId      = $this->locationId;
        $user_id         = $this->userId;
        if (isset($adminGroupValue)) {
            $hase_staffs = Staff::
                distinct()
                ->select('staff.*', 'location_city.city_name as city_name', 'postal.postal_premise as location_name', 'group_permissions.group_name', 'portal_password.clear_password', 'portal_password.username',  'portal_password.user_id', 'identity_staff.identity_name', 'identity_staff.identity_email', 'identity_merchant.identity_name as merchant_name', 'password_securities.google2fa_enable as google2fa_enable')
                ->join('identity_staff', 'identity_staff.identity_id', '=', 'staff.identity_id')
                ->leftjoin('merchant', 'merchant.merchant_id', '=', 'staff.merchant_id')
                ->leftjoin('identity_merchant', 'identity_merchant.identity_id', '=', 'merchant.identity_id')
                ->join('identity_group_list', 'identity_group_list.identity_id', '=', 'staff.identity_id')
                ->join('group_permissions', 'group_permissions.group_id', '=', 'identity_group_list.group_id')
                ->join('portal_password', 'staff.identity_id', '=', 'portal_password.identity_id')
                ->join('location_list', function ($hase_staffs) {
                    $hase_staffs->on('staff.identity_id', '=', 'location_list.identity_id')->where('location_list.identity_table_id', self::STAFF_TABLE_IDENTITY_TYPE);
                })
                ->join('postal', 'postal.postal_id', '=', 'location_list.postal_id')
                ->join('location_city', 'location_list.location_city_id', '=', 'location_city.city_id')
                ->leftjoin('password_securities', 'password_securities.user_id', '=', 'portal_password.user_id')
                ->where('staff.staff_id', '>', self::INIT_VALUE)
                ->where('identity_group_list.identity_table_id', '=', self::STAFF_TABLE_IDENTITY_TYPE)
                ->where('portal_password.identity_table_id', self::STAFF_TABLE_IDENTITY_TYPE)
                ->groupBy('staff.staff_id');
        } else {
            if ($matchRoles == self::FULL_ACCESS) {
                $hase_staffs = Staff::
                    distinct()
                    ->select('staff.*', 'location_city.city_name as city_name', 'postal.postal_premise as location_name', 'group_permissions.group_name', 'portal_password.clear_password', 'portal_password.username',  'portal_password.user_id', 'identity_staff.identity_name', 'identity_staff.identity_email', 'identity_merchant.identity_name as merchant_name', 'password_securities.google2fa_enable as google2fa_enable')
                    ->join('identity_staff', 'identity_staff.identity_id', '=', 'staff.identity_id')
                    ->leftjoin('merchant', 'merchant.merchant_id', '=', 'staff.merchant_id')
                    ->leftjoin('identity_merchant', 'identity_merchant.identity_id', '=', 'merchant.identity_id')
                    ->join('identity_group_list', 'identity_group_list.identity_id', '=', 'staff.identity_id')
                    ->join('group_permissions', 'group_permissions.group_id', '=', 'identity_group_list.group_id')
                    ->join('portal_password', 'staff.identity_id', '=', 'portal_password.identity_id')
                    ->join('location_list', function ($hase_staffs) {
                        $hase_staffs->on('staff.identity_id', '=', 'location_list.identity_id')->where('location_list.identity_table_id', self::STAFF_TABLE_IDENTITY_TYPE);
                    })
                    ->join('postal', 'postal.postal_id', '=', 'location_list.postal_id')
                    ->join('location_city', 'location_list.location_city_id', '=', 'location_city.city_id')
                    ->leftjoin('password_securities', 'password_securities.user_id', '=', 'portal_password.user_id')
                    ->where('staff.staff_id', '>', self::INIT_VALUE)
                    ->where('staff.merchant_id',$this->merchantId)
                    ->where('identity_group_list.identity_table_id', '=', self::STAFF_TABLE_IDENTITY_TYPE)
                    ->where('portal_password.identity_table_id', self::STAFF_TABLE_IDENTITY_TYPE)
                    ->groupBy('staff.staff_id');
            } else {
                $hase_staffs = Staff::
                    distinct()
                    ->select('staff.*', 'location_city.city_name as city_name', 'postal.postal_premise as location_name', 'group_permissions.group_name', 'portal_password.clear_password', 'portal_password.username','portal_password.user_id', 'identity_staff.identity_name','identity_staff.identity_email', 'identity_merchant.identity_name as merchant_name')
                    ->join('identity_staff', 'identity_staff.identity_id', '=', 'staff.identity_id')
                    ->leftjoin('merchant', 'merchant.merchant_id', '=', 'staff.merchant_id')
                    ->leftjoin('identity_merchant', 'identity_merchant.identity_id', '=', 'merchant.identity_id')
                    ->join('identity_group_list', 'identity_group_list.identity_id', '=', 'staff.identity_id')
                    ->join('group_permissions', 'group_permissions.group_id', '=', 'identity_group_list.group_id')
                    ->join('portal_password', 'staff.identity_id', '=', 'portal_password.identity_id')
                    ->join('location_list', function ($hase_staffs) use ($locationId) {
                        $hase_staffs->on('staff.identity_id', '=', 'location_list.identity_id')->
                            where('location_list.identity_table_id', self::STAFF_TABLE_IDENTITY_TYPE)
                            ->where('location_list.postal_id', $locationId);
                    })
                    ->join('postal', 'postal.postal_id', '=', 'location_list.postal_id')
                    ->join('location_city', 'location_list.location_city_id', '=', 'location_city.city_id')
                    ->where('staff.merchant_id', '=', $this->merchantId)
                    ->where('staff.staff_id', $this->staffId)
                    ->where('staff.staff_id', '>', self::INIT_VALUE)
                    ->where('staff.staff_status', '=', self::FIRST_VALUE)
                    ->where('identity_group_list.identity_table_id', '=', self::STAFF_TABLE_IDENTITY_TYPE)
                    ->where('portal_password.identity_table_id', self::STAFF_TABLE_IDENTITY_TYPE)
                    ->groupBy('staff.staff_id');
            }
        }
        $total_record = $total_record + $hase_staffs->get()->count();
        if (isset($request->take)) {
            $hase_staffs->offset($request->skip)->limit($request->take);
        }
        $hase_staff_list          = $hase_staffs->get();
        foreach ($hase_staff_list as $key => $value) {
            if($hase_staff_list[$key]['google2fa_enable'] == null){
                $hase_staff_list[$key]['google2fa_enable']=self::INIT_VALUE;
            }
             $group_list          = array();
                $identity_group_list = Identity_group_list::
            select('identity_group_list.group_id', 'group_permissions.group_name')
            ->join('group_permissions', 'group_permissions.group_id', '=', 'identity_group_list.group_id')
            ->where('group_permissions.group_name', '!=', 'None')
            ->where('identity_id', $value['staff_id'])
            ->where('identity_table_id', self::STAFF_TABLE_IDENTITY_TYPE)
            ->get()->toArray();

                foreach ($identity_group_list as $identity_group_list_key => $identity_group_list_value) {
                    $group_list[$identity_group_list_key]['group_id'] = $identity_group_list_value['group_id'];
                    $group_list[$identity_group_list_key]['group_name'] = $identity_group_list_value['group_name'];
                }
            $hase_staff_list[$key]['group_details'] = $group_list;
        }
        $hase_staff['staff_list'] = $hase_staff_list->toArray();
        if(in_array("add", $permissions)){
            $staffUserAddNewDetails[self::INIT_VALUE] = array("user_id"=> $this->userId,"staff_id" => '', "identity_id" => '', "identity_table_id" => self::IDENTITY_STAFF_TABLE_IDENTITY_TYPE, "staff_fname" => "", "staff_lname" => "", "staff_timezone" => self::INIT_VALUE, "customer_group_id" => self::INIT_VALUE, "staff_status" => self::INIT_VALUE, "username" => "", "group_name" => '', "google2fa_enable" => null, "city_name" => "", "identity_name" => "" ,"location_id" => self::INIT_VALUE ,"group_details" => "");
            $hase_staff['staff_list'] = array_merge($staffUserAddNewDetails, $hase_staff['staff_list']);
        }
        $hase_staff['total']      = $total_record;        
        return json_encode($hase_staff);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return  \Illuminate\Http\Response
     */
    public function create()
    {
        if ($this->permissionDetails('Hase_staff', 'add')) {
            $title      = 'Create - hase_staff';
            $merchantId = $this->merchantId;

            $hase_merchant_types = Merchant_type::where('merchant_parent_id', '0')->get();

            $hase_staff_groups = ($this->merchantId == 0) ?
            Group_permission::all() :
            Group_permission::
                where('group_id', '>', $this->roleId)->get();

            if ($this->merchantId == 0) {

                $hase_merchants = array();
                $hase_locations = array();

            } else {

                $hase_merchants = Merchant::all()
                    ->where('merchant_id', '=', $this->merchantId);

                $hase_locations = Postal::distinct()
                    ->select('location_list.postal_id as location_id', 'Postal.postal_premise as location_name')
                    ->join('location_list', 'location_list.postal_id', '=', 'postal.postal_id')
                    ->join('staff', 'staff.identity_id', '=', 'location_list.identity_id')
                    ->where('location_list.identity_table_id', '=', self::MERCHANT_TABLE_IDENTITY_TYPE)
                    ->where('staff.merchant_id', '=', $this->merchantId)->get();
            }

            return view('hase_staff.create', compact('hase_staff_groups', 'hase_merchant_types', 'hase_merchants', 'hase_locations', 'merchantId'));
        } else {
            return Redirect::back()->with('message', 'You are not authorized to use this functionality!');
        }
    }

    public function getMerchantsForStaff(Request $request)
    {

        $hase_merchants = Merchant::
            distinct()
            ->select('merchant_id', 'merchant_status', 'identity_merchant.identity_name as merchant_name', 'identity_merchant.identity_email as merchant_email', 'identity_merchant.identity_telephone as merchant_telephone', 'identity_merchant.identity_website as merchant_website', 'identity_merchant.identity_logo as merchant_logo', 'identity_merchant.identity_logo_compact as merchant_logo_compact')
            ->join('identity_merchant', 'identity_merchant.identity_id', '=', 'merchant.identity_id')
            ->where('merchant_type_id', $request->merchant_type_id)
            ->get();

        return json_encode($hase_merchants);

    }
    /**
     * Store a newly created resource in storage.
     *
     * @param    \Illuminate\Http\Request  $request
     * @return  \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $timeZoneId = PermissionTrait::getTimeZoneId();

        $hase_staff     = new Staff();
        $hase_user      = new Portal_password();
        $identity_staff = new Identity_staff();
        $location_list  = new Location_list();

        $identity_staff->identity_name  = $request->first_name . " " . $request->last_name;
        $identity_staff->identity_email = $request->staff_email;
        $identity_staff->save();

        $location_list->identity_id       = $identity_staff->identity_id;
        $location_list->identity_table_id = self::STAFF_TABLE_IDENTITY_TYPE;
        $location_list->location_city_id  = $request->city_id;
        $location_list->postal_id         = $request->location_id;
        $location_list->priority          = 1;
        $location_list->status            = 1;
        $location_list->save();

        $hase_staff->merchant_id       = $request->merchant_id;
        $hase_staff->identity_id       = $identity_staff->identity_id;
        $hase_staff->identity_table_id = self::IDENTITY_STAFF_TABLE_IDENTITY_TYPE;
        $hase_staff->staff_fname       = $request->first_name;
        $hase_staff->staff_lname       = $request->last_name;
        $hase_staff->staff_timezone    = $timeZoneId;
        $hase_staff->staff_status      = isset($request->staff_status) ? 1 : 0;
        $hase_staff->save();

        foreach ($request->staff_group_id as $key => $value) {

            $identity_group_list                    = new Identity_group_list();
            $identity_group_list->group_id          = $value;
            $identity_group_list->identity_table_id = self::STAFF_TABLE_IDENTITY_TYPE;
            $identity_group_list->identity_id       = $identity_staff->identity_id;
            $identity_group_list->save();

        }

        $hase_user->identity_id       = $hase_staff->identity_id;
        $hase_user->identity_table_id = self::STAFF_TABLE_IDENTITY_TYPE;
        $hase_user->username          = $request->username;
        /*$hase_user->password = Hash::make($request->password);*/
        $hase_user->clear_password      = uniqid();
        /*$hase_user->salt = uniqid();*/

        $hase_user->save();

        Session::flash('type', 'success');
        Session::flash('msg', 'Staff Successfully Created');

        if ($request->submitBtn == "Save") {
            return redirect('hase_staff/' . $hase_staff->staff_id . '/edit');
        } else {
            return redirect('hase_staff');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param    \Illuminate\Http\Request  $request
     * @param    int  $id
     * @return  \Illuminate\Http\Response
     */
    public function show($id, Request $request)
    {
        $title = 'Show - hase_staff';

        $hase_staff = Staff::findOrfail($id);
        return view('hase_staff.show', compact('title', 'hase_staff'));
    }

    /**
     * Show the form for editing the specified resource.
     * @param    \Illuminate\Http\Request  $request
     * @param    int  $id
     * @return  \Illuminate\Http\Response
     */
    public function edit($id, Request $request)
    {
        if ($this->permissionDetails('Hase_staff', 'manage')) {
            $title = 'Edit - hase_staff';

            $group_list = array();
            $user_id    = $this->userId;
            $merchantId = $this->merchantId;

            $hase_merchant_types = Merchant_type::where('merchant_parent_id', '0')->get();

            if ($merchantId == 0) {
                $hase_staff = Staff::
                    select('portal_password.*', 'staff.*', 'identity_group_list.*', 'merchant.merchant_type_id', 'identity_staff.identity_email', 'password_securities.google2fa_enable')
                    ->join('portal_password', 'staff.identity_id', '=', 'portal_password.identity_id')
                    ->join('identity_staff', 'identity_staff.identity_id', '=', 'staff.identity_id')
                    ->join('identity_group_list', 'identity_group_list.identity_id', '=', 'staff.identity_id')
                    ->join('password_securities', 'password_securities.user_id', '=', 'portal_password.user_id')
                    ->join('merchant', 'merchant.merchant_id', '=', 'staff.merchant_id')
                    ->where('staff.staff_id', $id)
                    ->where('portal_password.identity_table_id', self::STAFF_TABLE_IDENTITY_TYPE)
                    ->where('identity_group_list.identity_table_id', '=', self::STAFF_TABLE_IDENTITY_TYPE)
                    ->get()
                    ->first();
            } else {
                $hase_staff = Staff::
                    select('portal_password.*', 'staff.*', 'identity_group_list.*', 'password_securities.google2fa_enable')
                    ->join('portal_password', 'staff.identity_id', '=', 'portal_password.identity_id')
                    ->join('identity_staff', 'identity_staff.identity_id', '=', 'staff.identity_id')
                    ->join('identity_group_list', 'identity_group_list.identity_id', '=', 'staff.identity_id')
                    ->join('password_securities', 'password_securities.user_id', '=', 'portal_password.user_id')
                    ->where('staff.staff_id', $id)
                    ->where('staff.merchant_id', $merchantId)
                    ->where('portal_password.identity_table_id', self::STAFF_TABLE_IDENTITY_TYPE)
                    ->where('identity_group_list.identity_table_id', '=', self::STAFF_TABLE_IDENTITY_TYPE)
                    ->get()
                    ->first();
            }

            if (count($hase_staff)) {
                $hase_staff_groups = ($this->merchantId == 0) ?
                Group_permission::where("group_id",'!=',0)->get() :
                Group_permission::
                    where('group_id', '>=', $this->roleId)->where("group_id",'!=',0)->get();

                $location_list = Location_list::
                    where('location_list.identity_id', '=', $hase_staff->identity_id)
                    ->where('location_list.identity_table_id', '=', self::STAFF_TABLE_IDENTITY_TYPE)
                    ->get()->first();

                $identity_group_list = Identity_group_list::
                    select('group_id')
                    ->where('identity_id', $hase_staff->identity_id)
                    ->where('identity_table_id', self::STAFF_TABLE_IDENTITY_TYPE)
                    ->get()->toArray();

                foreach ($identity_group_list as $key => $value) {

                    $group_list[] = $value['group_id'];
                }

                if (!$location_list) {
                    $location_list                   = new stdClass;
                    $location_list->location_city_id = 0;
                    $location_list->postal_id        = 0;
                }
                if ($merchantId == 0) {
                    $hase_merchants = Merchant::
                        distinct()
                        ->select('merchant_id', 'merchant_status', 'identity_merchant.identity_name as merchant_name', 'identity_merchant.identity_email as merchant_email', 'identity_merchant.identity_telephone as merchant_telephone', 'identity_merchant.identity_website as merchant_website', 'identity_merchant.identity_logo as merchant_logo', 'identity_merchant.identity_logo_compact as merchant_logo_compact')
                        ->join('identity_merchant', 'identity_merchant.identity_id', '=', 'merchant.identity_id')
                        ->get();

                    $merchant_cities = City::distinct()
                        ->select('location_city.city_id', 'location_city.city_name as city_name')
                        ->join('location_list', 'location_city.city_id', '=', 'location_list.location_city_id')
                        ->join('merchant', 'merchant.identity_id', '=', 'location_list.identity_id')
                        ->where('location_list.identity_table_id', '=', self::MERCHANT_TABLE_IDENTITY_TYPE)
                        ->where('merchant.merchant_id', '=', $hase_staff->merchant_id)
                        ->get();

                    $merchant_city_postals = Postal::distinct()
                        ->select('location_list.postal_id as location_id', 'postal.postal_premise as location_name')
                        ->join('location_list', 'postal.postal_id', '=', 'location_list.postal_id')
                        ->join('merchant', 'merchant.identity_id', '=', 'location_list.identity_id')
                        ->where('merchant.merchant_id', '=', $hase_staff->merchant_id)
                        ->where('location_list.location_city_id', '=', $location_list->location_city_id)
                        ->where('location_list.identity_table_id', '=', self::MERCHANT_TABLE_IDENTITY_TYPE)
                        ->get();

                } else {
                    $hase_merchants = Group_permission::all()
                        ->where('merchant_id', '=', $merchantId);

                    $merchant_cities = City::distinct()
                        ->select('location_city.city_id', 'location_city.city_name')
                        ->join('location_list', 'location_list.location_city_id', '=', 'location_city.city_id')
                        ->join('merchant', 'merchant.identity_id', '=', 'location_list.identity_id')
                        ->where('merchant.merchant_id', '=', $merchantId)
                        ->where('location_list.identity_table_id', '=', self::MERCHANT_TABLE_IDENTITY_TYPE)
                        ->get();

                    $merchant_city_postals = Postal::distinct()
                        ->select('location_list.postal_id as location_id', 'Postal.postal_premise as location_name')
                        ->join('location_list', 'location_list.postal_id', '=', 'postal.postal_id')
                        ->join('merchant', 'merchant.identity_id', '=', 'location_list.identity_id')
                        ->where('merchant.merchant_id', '=', $merchantId)
                        ->where('location_list.location_city_id', '=', $location_list->location_city_id)
                        ->where('location_list.identity_table_id', '=', self::MERCHANT_TABLE_IDENTITY_TYPE)
                        ->get();

                }

                return view('hase_staff.edit', compact('title', 'hase_staff', 'hase_staff_groups', 'merchant_cities', 'merchant_city_postals', 'hase_merchants', 'location_list', 'hase_merchant_types', 'merchantId', 'group_list', 'user_id'));
            } else {
                return redirect('hase_staff')->with("message", "You are not authorized to use this functionality!");
            }
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
        $hase_staff = Staff::findOrfail($id);

        $identity_staff                 = Identity_staff::findOrfail($hase_staff->identity_id);
        $identity_staff->identity_email = $request->staff_email;
        $identity_staff->identity_name  = $request->first_name . " " . $request->last_name;
        $identity_staff->save();

        // UPDATE STAFF ROLE INFORMATION

        Identity_group_list::whereNotIn('group_id', $request->staff_group_id)
            ->where('identity_id', $hase_staff->identity_id)
            ->where('identity_table_id', self::STAFF_TABLE_IDENTITY_TYPE)
            ->delete();

        foreach ($request->staff_group_id as $key => $value) {

            $isExist = Identity_group_list::
                where('group_id', '=', $value)
                ->where('identity_id', $identity_staff->identity_id)
                ->where('identity_table_id', self::STAFF_TABLE_IDENTITY_TYPE)
                ->first();

            if ($isExist == null) {

                $identity_group_list                    = new Identity_group_list();
                $identity_group_list->group_id          = $value;
                $identity_group_list->identity_table_id = self::STAFF_TABLE_IDENTITY_TYPE;
                $identity_group_list->identity_id       = $identity_staff->identity_id;
                $identity_group_list->save();
            }
        }
        $isExistuser = passwordSecurity::
            where('user_id', '=', $request->user_id)
            ->first();

        if ($isExistuser != null) {

            DB::table('password_securities')
                ->where('user_id', $request->user_id)->update(array('google2fa_enable' => (isset($request->google2fa_enable) ? 1 : 0)));
        }
        $location_list_row = Location_list::
            where('identity_id', $hase_staff->identity_id)
            ->where('location_list.identity_table_id', '=', self::STAFF_TABLE_IDENTITY_TYPE)
            ->get()
            ->first();

        if ($location_list_row) {
            $location_list = Location_list::findOrfail($location_list_row->list_id);
        } else {
            $location_list = new Location_list;
        }

        $location_list->location_city_id = $request->city_id;
        $location_list->postal_id        = $request->location_id;
        $location_list->save();

        $hase_staff->merchant_id  = $request->merchant_id;
        $hase_staff->staff_fname  = $request->first_name;
        $hase_staff->staff_lname  = $request->last_name;
        if($this->userId == 1){
            $hase_staff->staff_status = isset($request->staff_status) ? 1 : 0;    
        }        
        $hase_staff->save();

        $staffUser = Portal_password::
            where('identity_id', $hase_staff->identity_id)
            ->where('identity_table_id', self::STAFF_TABLE_IDENTITY_TYPE)
            ->get()
            ->first();
        $hase_user = Portal_password::findOrfail($staffUser->user_id);

        if ($request->password != "" && $hase_user->identity_id == $this->staffId) {
            $salt                           = uniqid();
            $hase_user->password            = Hash::make($request->password);
            $hase_user->clear_password      = null;
            $hase_user->salt                = $salt;
            $hase_user->save();
        }

        Session::flash('type', 'success');
        Session::flash('msg', 'Staff Successfully Updated');

        if ($request->submitBtn == "Save") {
            return redirect('hase_staff/' . $hase_staff->staff_id . '/edit');
        } else {
            return redirect('hase_staff');
        }

    }

    /**
     * Delete confirmation message by Ajaxis.
     *
     * @link      https://github.com/amranidev/ajaxis
     * @param    \Illuminate\Http\Request  $request
     * @return  String
     */
    public function DeleteMsg($id, Request $request)
    {
        $msg = Ajaxis::BtDeleting('Warning!!', 'Would you like to remove This?', '/hase_staff/' . $id . '/delete');

        if ($request->ajax()) {
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
        if ($this->permissionDetails('Hase_staff', 'delete')) {
            $hase_staff = Staff::findOrfail($id);
            /*$hase_staff->delete();*/
            $hase_staff->staff_status = 0;
            $hase_staff->save();

            Session::flash('type', 'success');
            Session::flash('msg', 'Staff Successfully Deleted');

            return redirect('hase_staff');
        } else {
            return Redirect::back()->with('message', 'You are not authorized to use this functionality!');
        }
    }
    public function updateStaffDetails(Request $request)
    {
        $staffId    = $request->staff_id;
        $merchantId = $request->merchant_id;
        $key        = $request->key;
        $value      = $request->value;
        $haseStaffAceess = $this->permissionDetails('Hase_staff', 'manage');
        if ($staffId == 0) {
            $haseStaffAddAceess = $this->permissionDetails('Hase_staff', 'add');
            if ($haseStaffAddAceess) {
                if ($key === "identity_email") {
                        $timeZoneId = PermissionTrait::getTimeZoneId();
                        $hase_staff     = new Staff();
                        $hase_user      = new Portal_password();
                        $identity_staff = new Identity_staff();
                        $location_list  = new Location_list();
                        $identity_staff->identity_email  = $value;
                        $identity_staff->identity_table_id  = self::STAFF_TABLE_IDENTITY_TYPE;
                        $identity_staff->save();

                        $location_list->identity_id       = $identity_staff->identity_id;
                        $location_list->identity_table_id = self::STAFF_TABLE_IDENTITY_TYPE;
                        $location_list->priority          = 1;
                        $location_list->status            = 1;
                        $location_list->save();

                        $hase_staff->merchant_id       = $this->merchantId;
                        $hase_staff->identity_id       = $identity_staff->identity_id;
                        $hase_staff->identity_table_id = self::IDENTITY_STAFF_TABLE_IDENTITY_TYPE;
                        $hase_staff->staff_timezone    = $timeZoneId;
                        $hase_staff->staff_status      = 1;
                        $hase_staff->save();
                        
                        $identity_group_list                    = new Identity_group_list();
                        $identity_group_list->identity_table_id = self::STAFF_TABLE_IDENTITY_TYPE;
                        $identity_group_list->identity_id       = $identity_staff->identity_id;
                        $identity_group_list->save();

                        $hase_user->identity_id       = $hase_staff->identity_id;
                        $hase_user->identity_table_id = self::STAFF_TABLE_IDENTITY_TYPE;
                        $hase_user->username          = $value;
                        $hase_user->clear_password      = uniqid();
                        $hase_user->clear_password_timestamp =time();
                        $hase_user->user_status         = 1;
                        $hase_user->save();

                        // Add the google2FA record in password security 
                        PasswordSecurity::create([
                            'user_id' => $hase_user->user_id,
                            'google2fa_enable' => 0,
                            'google2fa_secret' => NULL,
                        ]);

                        return array("type" => "success", "message" => 'New Staff Added');
                }
                else {
                    return array("type" => "error", "message" => 'Email is required.');
                }

            }
            else{
                return array("type" => "error", "message" => 'You are not authorized to use this functionality!');
            }
        }else{
        if ($haseStaffAceess) {
            if ($key === "username") {
                $user = Portal_password::where('username', $value)->first();
                if (isset($user->user_id)) {
                    return array("type" => "success", "message" => 'The username is not available');
                } else {
                    $group_details = Group_permission::where('group_id', $this->roleId)->first();
                    if ($group_details->group_name === 'Portal Admin') {
                        Portal_password::where('identity_id', $staffId)
                            ->where('identity_table_id', self::STAFF_TABLE_IDENTITY_TYPE)
                            ->update(['username' => $value, 'clear_password' => uniqid(), 'clear_password_timestamp' => time()]);
                        return array("type" => "success", "message" => 'Username update successful');
                    }else{
                        return array("type" => "error", "message" => 'You are not allowed to change username'); 
                    }
                }
            }
            if ($key === "merchant_name") {
                $merchant = DB::table('identity_merchant')->where('identity_name', '=', $value)->first();
                Staff::where('staff_id', $staffId)->update(['merchant_id' => $merchant->identity_id]);
                return array("type" => "success", "message" => 'Merchant Updated');
            }
            if ($key === "staff_lname") {
                $identity_staff= Staff::findOrfail($staffId);
                Identity_staff::where('identity_id', $staffId)->update(['identity_name' => $identity_staff->staff_fname." ".$value]);
                Staff::where('staff_id', $staffId)->update(['staff_lname' => $value]);
                return array("type" => "success", "message" => 'Staff Last Name Updated');
            }
            if ($key === "staff_fname") {
                Staff::where('staff_id', $staffId)->update(['staff_fname' => $value]);
                return array("type" => "success", "message" => 'Staff First Name Updated');
            }
            if ($key === "staff_status") {
                Staff::where('staff_id', $staffId)->update(['staff_status' => $value]);
                return array("type" => "success", "message" => 'Staff Status Updated');
            }
            if ($key === "google2fa_enable") {
                $users_details = Portal_password::where('identity_id', $staffId)->where('identity_table_id', self::STAFF_TABLE_IDENTITY_TYPE)->first();
                $isExistuser   = passwordSecurity::where('user_id', $users_details->user_id)->first();
                if ($isExistuser != null) {
                    DB::table('password_securities')->where('user_id', $users_details->user_id)->update(['google2fa_enable' => $value]);
                }else {
                   // Add the google2FA record in password security 
                   PasswordSecurity::create([
                       'user_id' => $users_details->user_id,
                       'google2fa_enable' => $value,
                       'google2fa_secret' => NULL,
                   ]);
                }
                if ($value == self::FIRST_VALUE) {
                        $google2faStatus = "On";
                } else {
                        $google2faStatus = "Off";
                }
                $actionUrl = "/" . session('staffUrl');
                if ($users_details->username != session('staffName')) {
                    $message = "<a href='" . URL::to($actionUrl) . "'>" . session('staffName') . "</a> <strong>Google2FA is ". $google2faStatus ." for " . $users_details->username . "</strong>";
                } else {
                    $message = "<a href='" . URL::to($actionUrl) . "'>" . session('staffName') . "</a> <strong>Google2FA is ". $google2faStatus ."</strong>";
                }
                $action    = "Google2fa Status Updated";
                PermissionTrait::addActivityLog($action, $message);
                return array("type" => "success", "message" => 'Google2fa Updated');
            }
            if ($key === "identity_name") {
                Identity_staff::where('identity_id', $staffId)->update(['identity_name' => $value]);
                return array("type" => "success", "message" => 'Staff Name Updated');
            }
            if ($key === "identity_email") {
                
                Identity_staff::where('identity_id', $staffId)->where('identity_table_id', self::STAFF_TABLE_IDENTITY_TYPE)->update(['identity_email' => $value]);
                return array("type" => "success", "message" => 'Staff Email Updated');
            }
            if ($key === "group_name") {
                Identity_group_list::
                        where('identity_id', $request->identity_id)
                        ->where('identity_table_id', self::STAFF_TABLE_IDENTITY_TYPE)
                        ->delete();
                
                foreach ($value as $groupid) {
                    $identity_group_list                    = new Identity_group_list();
                    $identity_group_list->group_id          = $groupid;
                    $identity_group_list->identity_table_id = self::STAFF_TABLE_IDENTITY_TYPE;
                    $identity_group_list->identity_id       = $staffId;
                    $identity_group_list->save();
                }

                return array("type" => "success", "message" => 'Staff Roles Updated');
            }
            if ($key === "city_name") {
                $merchant_cities   = City::where('city_name', '=', $value)->first();
                $location_list_row = Location_list::where('identity_id', $staffId)->where('location_list.identity_table_id', '=', self::STAFF_TABLE_IDENTITY_TYPE)->first();
                if ($location_list_row) {
                    $location_list = Location_list::findOrfail($location_list_row->list_id);
                } else {
                    $location_list = new Location_list;
                }
                $location_list->location_city_id = $merchant_cities->city_id;
                $location_list->save();
                return array("type" => "success", "message" => 'Staff City Updated');
            }
            if ($key === "location_name") {
                $merchant_postal   = Postal::where('postal_premise', '=', $value)->first();
                $location_list_row = Location_list::where('identity_id', $staffId)->where('location_list.identity_table_id', '=', self::STAFF_TABLE_IDENTITY_TYPE)->first();
                if ($location_list_row) {
                    $location_list = Location_list::findOrfail($location_list_row->list_id);
                } else {
                    $location_list = new Location_list;
                }
                $location_list->postal_id = $merchant_postal->postal_id;
                $location_list->save();
                return array("type" => "success", "message" => 'Staff Location Updated');
            }
            if ($key === "password") {
                    $password     = $value;
                    $uppercase    = preg_match('@[A-Z]@', $password);
                    $lowercase    = preg_match('@[a-z]@', $password);
                    $number       = preg_match('@[0-9]@', $password);
                    $specialChars = preg_match('@[^\w]@', $password);

                    if (!$uppercase || !$lowercase || !$number || !$specialChars || strlen($password) < self::PASSWORD_LENGTH) {
                        return array("type" => "error", "message" => 'Password should be at least 8 characters in length and should include at least one upper case letter, one number, and one special character.');
                    } else {
                        $users_details = Portal_password::where('identity_id', $staffId)->where('identity_table_id', self::STAFF_TABLE_IDENTITY_TYPE)->first();
                        if ($users_details->identity_id == $this->staffId) {
                            $hase_user                      = Portal_password::findOrfail($users_details->user_id);
                            $salt                           = uniqid();
                            $hase_user->password            = Hash::make($value);
                            $hase_user->clear_password      = null;
                            $hase_user->salt                = $salt;
                            $hase_user->save();
                            $actionUrl = "/" . session('staffUrl');
                            $message   = "<a href='" . URL::to($actionUrl) . "'>" . session('staffName') . "</a> <strong>Updated Password</strong>";
                            $action    = "Updated Password";
                            PermissionTrait::addActivityLog($action, $message);
                            return array("type" => "success", "message" => 'Password Updated');
                        } else {
                            return array("type" => "error", "message" => 'You are not allowed to change password for others');
                        }
                    }
                }
        } else {
            return array("type" => "error", "message" => 'You are not authorized to use this functionality!');
        }
        }
    }
    public function staffGroup(Request $request)
    {
        $staffGroups = ($this->merchantId == 0) ?
        Group_permission::all() : Group_permission::
            where('group_id', '>=', $this->roleId)->where('group_permissions.group_name', '!=', 'None')->get();
        return json_encode($staffGroups);
    }
    public function getCityDetails(Request $request)
    {
        $merchant_cities = City::distinct()
            ->select('location_city.city_id', 'location_city.city_name')
            ->join('location_list', 'location_list.location_city_id', '=', 'location_city.city_id')
            ->join('merchant', 'merchant.identity_id', '=', 'location_list.identity_id')
            ->where('merchant.merchant_id', '=', $request->merchant_id)
            ->where('location_list.identity_table_id', '=', self::MERCHANT_TABLE_IDENTITY_TYPE)
            ->get();
        return json_encode($merchant_cities);
    }
    public function getLocationDetails(Request $request)
    {
        $location_list = Location_list::
            where('location_list.identity_id', '=', $request->staff_id)
            ->where('location_list.identity_table_id', '=', self::STAFF_TABLE_IDENTITY_TYPE)
            ->get()->first();
        if (!$location_list) {
            $location_list                   = new stdClass;
            $location_list->location_city_id = 0;
            $location_list->postal_id        = 0;
        }
        $merchant_cities   = City::where('city_name', '=', $request->city_name)->first();
        $merchant_city_postals = Postal::distinct()
            ->select('location_list.postal_id as location_id', 'Postal.postal_premise as location_name')
            ->join('location_list', 'location_list.postal_id', '=', 'postal.postal_id')
            ->join('merchant', 'merchant.identity_id', '=', 'location_list.identity_id')
            ->where('merchant.merchant_id', '=', $request->merchant_id)
            ->where('location_list.location_city_id', '=', $location_list->location_city_id)
            ->where('location_list.identity_table_id', '=', self::MERCHANT_TABLE_IDENTITY_TYPE)
            ->get();
        return json_encode($merchant_city_postals);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param    str $username
     * @return  \Illuminate\Http\Response
     */
    public function checkUsername(Request $request)
    {
        $user = DB::table('portal_password')
            ->where('username', $request->username)
            ->get()
            ->first();

        $result['valid'] = (isset($user->user_id)) ? false : true;
        echo json_encode($result);
    }

    public function checkEmail(Request $request)
    {

        if ($request->type == "create") {

            $staff = DB::table('staff')
                ->join('identity_staff', 'staff.identity_id', '=', 'identity_staff.identity_id')
                ->where('identity_email', $request->email)
                ->get()->first();

            $result['valid'] = (isset($staff->staff_id)) ? false : true;

        } elseif ($request->type == "edit") {

            $staff = DB::table('staff')
                ->join('identity_staff', 'staff.identity_id', '=', 'identity_staff.identity_id')
                ->where('identity_email', $request->email)
                ->where('staff.staff_id', '!=', $request->staff_id)
                ->get()->first();

            if (!$staff) {
                $result['valid'] = true;
            } else {

                $result['valid'] = false;

            }
        } else {
            $result['valid'] = false;
        }

        echo json_encode($result);
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
        $actionUrl = "/" . session('staffUrl');
        if ($userName != session('staffName')) {
            $message = "<a href='" . URL::to($actionUrl) . "'>" . session('staffName') . "</a> <strong>Reset Password Of " . $userName->username . "</strong>";
        } else {
            $message = "<a href='" . URL::to($actionUrl) . "'>" . session('staffName') . "</a> <strong>Reset Password</strong>";
        }
        $action    = "Reset Password";
        PermissionTrait::addActivityLog($action, $message);
        return array("type" => "success", "message" => 'Reset Password Successfully');

    }
}
