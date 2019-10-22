<?php

namespace App\Http\Controllers;

use App\Group_permission;
use App\Helpers\ConnectionManager;
use App\Http\Controllers\PermissionsController;
use App\Http\Traits\PermissionTrait;
use App\Identity_group_list;
use App\Merchant;
use App\Permission;
use App\Staff;
use Auth;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Redirect;
use Session;

const GROUP_STATUS_ENABLE = 1;
const INIT_VALUE = 0;
const GROUP_ADMIN_PERMISSION = 4;
const MAX_PORTAL_USER_GROUP_ID = 3;
/**
 * Class Hase_staff_groupController.
 *
 * @author  The scaffold-interface created at 2017-03-07 09:16:06am
 * @link  https://github.com/amranidev/scaffold-interface
 */
class Hase_staff_groupController extends PermissionsController
{
    use PermissionTrait;

    public function __construct()
    {
        parent::__construct();

        $connectionStatus = ConnectionManager::setDbConfig('Hase_staff_group');
        if (strcmp($connectionStatus['type'], "error") == 0) {
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
        if ($this->permissionDetails('Hase_staff_group', 'access')) {

            $title = 'Index - hase_staff_group';

            $permissions = $this->getPermission("Hase_staff_group");
            $adminReoles = array("access", "manage", "add", "delete");
            $accessibility  = count(array_intersect($permissions, $adminReoles)); 

            return view('hase_staff_group.roles', compact('permissions','accessibility','title'));
        } else {
            return Redirect::back()->with('message', 'You are not authorized to use this functionality!');
        }
    }
    public function getRolesList(Request $request)
    {

        $permissions = $this->getPermission("Hase_staff_group");
        $adminReoles     = array("access", "manage", "add", "delete");
        $matchRoles      = count(array_intersect($permissions, $adminReoles));

        if ($this->merchantId == INIT_VALUE) {
            $hase_staff_groups = Group_permission::
                select('group_permissions.*', DB::raw('count(identity_group_list.identity_id) as total'))
                ->leftjoin('identity_group_list', function ($join) {
                    $join->on('identity_group_list.group_id', '=', 'group_permissions.group_id');
                })
                ->where('group_permissions.group_id', '>', INIT_VALUE)
                ->groupBy('group_permissions.group_id')
                ->offset($request->skip)->limit($request->take)
                ->get();

            $total_record = Group_permission::
                leftjoin('identity_group_list', function ($join) {
                $join->on('identity_group_list.group_id', '=', 'group_permissions.group_id');
            })
                ->where('group_permissions.group_id', '>', INIT_VALUE)
                ->groupBy('group_permissions.group_id')
                ->get()->count();

        } else {
            if ($matchRoles == GROUP_ADMIN_PERMISSION) {
                $hase_staff_groups = Group_permission::
                select('group_permissions.*', DB::raw('count(identity_group_list.identity_id) as total'))
                ->leftjoin('identity_group_list', function ($join) {
                    $join->on('identity_group_list.group_id', '=', 'group_permissions.group_id');
                })
                ->where('group_permissions.group_id', '>', MAX_PORTAL_USER_GROUP_ID)
                ->where('group_permissions.merchant_id',$this->merchantId)
                ->groupBy('group_permissions.group_id')
                ->offset($request->skip)->limit($request->take)
                ->get();

            $total_record = Group_permission::
                leftjoin('identity_group_list', function ($join) {
                $join->on('identity_group_list.group_id', '=', 'group_permissions.group_id');
            })
                ->where('group_permissions.group_id', '>', MAX_PORTAL_USER_GROUP_ID)
                ->where('group_permissions.merchant_id',$this->merchantId)
                ->groupBy('group_permissions.group_id')
                ->get()->count();

            } else {
                $hase_staff_groups = Group_permission::
                select('group_permissions.*', DB::raw('count(identity_group_list.identity_id) as total'))
                ->leftjoin('identity_group_list', function ($join) {
                    $join->on('identity_group_list.group_id', '=', 'group_permissions.group_id')->where('identity_group_list.identity_table_id', $this->identityTableId);
                })
                ->where('identity_group_list.identity_id', '=', $this->staffId)
                ->where('group_permissions.group_id', '>', INIT_VALUE)
                ->groupBy('group_permissions.group_id')
                ->offset($request->skip)->limit($request->take)
                ->get();

                $total_record = Group_permission::
                    leftjoin('identity_group_list', function ($join) {
                        $join->on('identity_group_list.group_id', '=', 'group_permissions.group_id')->where('identity_group_list.identity_table_id', $this->identityTableId);
                    })
                    ->where('identity_group_list.identity_id', '=', $this->staffId)
                    ->where('group_permissions.group_id', '>', INIT_VALUE)
                    ->groupBy('group_permissions.group_id')
                    ->get()->count();
            }
        }

        foreach ($hase_staff_groups as $key => $hase_staff_group) {
            if (in_array("manage", $permissions)) {
                $hase_staff_groups[$key]->edit = "edit";
            } else {
                $hase_staff_groups[$key]->edit = "";
            }if (in_array("delete", $permissions) && $hase_staff_group->group_id > 6) {
                $hase_staff_groups[$key]->delete = "delete";
            } else {
                $hase_staff_groups[$key]->delete = "";
            }
        }

        $hase_staff_groups_data['staff_groups'] = $hase_staff_groups;
        $hase_staff_groups_data['total']        = $total_record;

        return json_encode($hase_staff_groups_data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return  \Illuminate\Http\Response
     */
    public function create()
    {
        if ($this->permissionDetails('Hase_staff_group', 'add')) {

            $title            = 'Create - hase_staff_group';
            $hase_permissions = Permission::all();
            if ($this->merchantId == 0) {
                $groupPermission = Group_permission::
                    leftjoin('identity_group_list', function ($join) {
                    $join->on('identity_group_list.group_id', '=', 'group_permissions.group_id')->where('identity_group_list.identity_table_id', 35);
                })
                    ->leftjoin('staff', function ($join) {
                        $join->on('staff.identity_id', '=', 'identity_group_list.identity_id')->where('staff.staff_id', $this->staffId);
                    })
                    ->where('group_permissions.merchant_id', '=', $this->merchantId)
                    ->where('group_permissions.group_id', '>', 0)
                    ->select('permissions')
                    ->get()->first();

                $groupPermission = unserialize($groupPermission->permissions);
                foreach ($hase_permissions as $key => $value) {
                    if (isset($groupPermission[$value->name]['description'])) {
                        $groupPermission[$value->name]['description'] = $value->description;
                    } else {
                        $namePermission                               = unserialize($value->action);
                        $groupPermission[$value->name]                = $namePermission;
                        $groupPermission[$value->name]['description'] = $value->description;
                    }
                }
            } else {
                if ($this->roleId == 4) {
                    $groupPermission = Group_permission::
                        leftjoin('identity_group_list', function ($join) {
                        $join->on('identity_group_list.group_id', '=', 'group_permissions.group_id')->where('identity_group_list.identity_table_id', 35);
                    })
                        ->leftjoin('staff', function ($join) {
                            $join->on('staff.identity_id', '=', 'identity_group_list.identity_id')->where('staff.staff_id', $this->staffId);
                        })
                        ->where('staff.merchant_id', '=', $this->merchantId)
                        ->where('group_permissions.group_id', '>', 0)
                        ->select('permissions')
                        ->get()->first();
                    $groupPermission = unserialize($groupPermission->permissions);
                    foreach ($hase_permissions as $key => $value) {
                        $groupPermission[$value->name]['description'] = $value->description;
                    }
                }
            }

            $merchants = Merchant::
                distinct()
                ->select('merchant.*', 'identity_merchant.identity_name as merchant_name')
                ->leftjoin('identity_merchant', 'identity_merchant.identity_id', 'merchant.identity_id')
                ->where('merchant.merchant_id', '!=', 0)
                ->get();

            return view('hase_staff_group.create', compact('title', 'groupPermission', 'merchants'));
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
        $hase_staff_group              = new Group_permission();
        $hase_staff_group->merchant_id = ($request->merchant_id) ? $request->merchant_id : $this->merchantId;
        $hase_staff_group->group_name  = $request->staff_group_name;
        $hase_staff_group->status      = isset($request->staff_group_status) ? 1 : 0;

        $hase_staff_group->permissions = ($request->permissions != "") ?
        serialize($request->permissions) :
        "";
        if ($hase_staff_group->permissions == '') {
            Session::flash('type', 'error');
            Session::flash('msg', 'please assign atleast one permission');
            return redirect('hase_staff_group/create');
        } else {
            $hase_staff_group->save();
            Session::flash('type', 'success');
            Session::flash('msg', 'Staff Group Successfully Inserted');

            if ($request->submitBtn == "Save") {
                return redirect('hase_staff_group/' . $hase_staff_group->group_id . '/edit');
            } else {
                return redirect('hase_staff_group');
            }
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
        $title            = 'Show - hase_staff_group';
        $hase_staff_group = Group_permission::findOrfail($id);
        return view('hase_staff_group.show', compact('title', 'hase_staff_group'));
    }

    /**
     * Show the form for editing the specified resource.
     * @param    \Illuminate\Http\Request  $request
     * @param    int  $id
     * @return  \Illuminate\Http\Response
     */
    public function edit($id, Request $request)
    {
        if ($this->permissionDetails('Hase_staff_group', 'manage')) {
            $title               = 'Edit - hase_staff_group';
            $merchant_permission = array();
            if ($this->merchantId == 0) {

                $hase_staff_group = Group_permission::findOrfail($id);

                $merchant_permission = Identity_group_list::                    
                    leftjoin('group_permissions', 'group_permissions.group_id', 'identity_group_list.group_id')
                    ->where('identity_group_list.identity_id', '=', $this->staffId)
                    ->where('identity_group_list.identity_table_id', '=', $this->identityTableId)
                    ->get()->first();
                if ($id > 4) {
                    $merchant_permission = Identity_group_list::
                        leftjoin('group_permissions', 'group_permissions.group_id', 'identity_group_list.group_id')
                        ->where('identity_group_list.identity_id', '=', $this->staffId)
                        ->where('identity_group_list.identity_table_id', '=', $this->identityTableId)
                        ->get()->first();
                }
            } else {
                $hase_staff_group = Group_permission::
                    select('group_permissions.*')
                    ->leftjoin('identity_group_list', function ($join) {
                    $join->on('identity_group_list.group_id', '=', 'group_permissions.group_id')->where('identity_group_list.identity_id', '=', $this->staffId)->where('identity_group_list.identity_table_id', '=', $this->identityTableId);
                })
                    ->where('group_permissions.group_id', '=', $id)
                    ->where('group_permissions.merchant_id', '=', $this->merchantId)
                    ->get()->first();

                $merchant_permission = Identity_group_list::
                    leftjoin('group_permissions', 'group_permissions.group_id', 'identity_group_list.group_id')
                    ->where('identity_group_list.identity_id', '=', $this->staffId)
                    ->where('identity_group_list.identity_table_id', '=', $this->identityTableId)
                    ->where('group_permissions.merchant_id', '=', $this->merchantId)
                    ->get()->first();
            }
            $hase_permissions = Permission::all();
            $merchants        = Merchant::
                distinct()
                ->select('merchant.*', 'identity_merchant.identity_name as merchant_name')
                ->leftjoin('identity_merchant', 'identity_merchant.identity_id', 'merchant.identity_id')
                ->where('merchant.merchant_id', '!=', 0)
                ->get();

            if (count($merchant_permission)) {
                if ($id > 4) {
                    $merchant_group_permission = ($merchant_permission->permissions != "") ? unserialize($merchant_permission->permissions) : array();
                    foreach ($hase_permissions as $hase_permissionskey => $hase_permissionsvalue) {
                        if (!array_key_exists($hase_permissionsvalue['name'], $merchant_group_permission)) {
                            unset($hase_permissions[$hase_permissionskey]);
                        } else {
                            $hase_permissions[$hase_permissionskey]->action = serialize($merchant_group_permission[$hase_permissionsvalue['name']]);
                        }
                    }
                }
                $hase_staff_group->permissions = ($hase_staff_group->permissions != "") ?
                unserialize($hase_staff_group->permissions) :
                array();

                return view('hase_staff_group.edit', compact('title', 'hase_staff_group', 'hase_permissions', 'merchants'));
            } else {
                return redirect('hase_staff_group')->with("message", "You are not authorized to use this functionality!");
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
        $hase_staff_group              = Group_permission::findOrfail($id);
        $hase_staff_group->group_name  = $request->staff_group_name;
        $hase_staff_group->merchant_id = ($request->merchant_id) ? $request->merchant_id : $this->merchantId;

        $hase_staff_group->status = isset($request->staff_group_status) ? 1 : 0;

        $hase_staff_group->permissions =
        ($request->permissions != "") ? serialize($request->permissions) : "";

        $hase_staff_group->save();

        Session::flash('type', 'success');
        Session::flash('msg', 'Staff Group Successfully Updated');

        if ($request->submitBtn == "Save") {
            return redirect('hase_staff_group/' . $hase_staff_group->group_id . '/edit');
        } else {
            return redirect('hase_staff_group');
        }
    }

    public function updateRoles(Request $request){

        $requestField          = $request->key;
        $staffGroupObj         = Group_permission::findOrFail($request->group_id);
        $staffGroupObj->$requestField = $request->value;
        $staffGroupObj->save();
        return array("type" => "success", "message" => 'Record Updated');
    }

    public function cloneRole(Request $request)
    {
        try{
            $clone_staff_group = Group_permission::findOrfail($request->group_id);        
            $hase_staff_group  = new Group_permission(); 
            $hase_staff_group->group_name  = $request->staff_group_name;
            $hase_staff_group->merchant_id = $clone_staff_group->merchant_id;

            $hase_staff_group->status = GROUP_STATUS_ENABLE;

            $hase_staff_group->permissions =
            ($clone_staff_group->permissions != "") ? $clone_staff_group->permissions : "";
            $hase_staff_group->save();
            return array("type" => "success", "message" => 'Group Successfully Clone');
        }catch (Exception $e){
            return array("type" => "error", "message" => $e->getMessage());
        }        
    }

}
