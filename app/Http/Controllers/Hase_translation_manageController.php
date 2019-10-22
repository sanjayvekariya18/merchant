<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\App;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Translation_manage;
use Amranidev\Ajaxis\Ajaxis;
use App\Approval_group_list;
use App\Http\Traits\PermissionTrait;
use App\Group_permission;
use App\Approval_status;
use App\Hase_user;
use App\Staff;
use URL;
use DB;
use Auth;
use Session;
use Redirect;
use App\Portal_password;

/**
 * Class Hase_translation_manageController.
 *
 * @author  The scaffold-interface created at 2017-08-11 11:36:44am
 * @link  https://github.com/amranidev/scaffold-interface
 */
class Hase_translation_manageController extends Controller
{
    use PermissionTrait;
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
        $this->staffId = session()->has('staffId') ? session()->get('staffId') :"";
        $this->staffName = session()->has('staffName') ? session()->get('staffName') :"";
        $this->merchantId = session()->has('merchantId') ? session()->get('merchantId') :"";
        $this->roleId = session()->has('role') ? session()->get('role') :"";
        $this->locationId = session()->has('locationId') ? session()->get('locationId') :"";
        return $next($request);
        });
    }
    /**
     * Display a listing of the resource.
     *
     * @return  \Illuminate\Http\Response
     */
    public function index()
    {
        $title = 'Index - hase_translation_manage';
        if($this->permissionDetails('Hase_translation_manage','access')) {
            if($this->merchantId == 0)
            {
                $merchantEditable = "enable";
            } else {
                $merchantEditable = "disable";
            }
            return view('hase_translation_manage.index',compact('merchantEditable'));
        } else {
            return Redirect::back()->with('message', 'You are not authorized to use this functionality!');
        }
    }
    public function getManageTableList(Request $request)
    {
        $hase_translation_keys=DB::table('translation_key')->get();
        return json_encode($hase_translation_keys);
    }
    
    public function usersList(Request $request)
    {
        $hase_user_list=Portal_password::where('identity_id','!=',0)->get();
        return json_encode($hase_user_list);
    }
    public function getApprovalStatus(Request $request)
    {
        $hase_approval_statuses = Approval_status::all();
        return json_encode($hase_approval_statuses);
    }
    public function getStaffGroup(Request $request)
    {
       $where = array();
        if($this->merchantId != 0){
            $where[] = array(
                'key' => "group_permissions.merchant_id",
                'operator' => '=',
                'val' => $this->merchantId
            );
        }
        $hase_staff_groups = Group_permission::select('group_id as '.$request->group.'person_id','group_name as '.$request->group.'person_name')->where('group_id','>',0)
            ->where(function($q) use ($where){
                foreach($where as $key => $value){
                    $q->where($value['key'], $value['operator'], $value['val']);
                }
            })->get();
        return json_encode($hase_staff_groups);
    }    
    public function getTranslationManageLists(Request $request)
    {
        if($this->permissionDetails('Hase_translation_manage','access')) {
            $where = array();
            if($this->merchantId != 0){
                $where[] = array(
                    'key' => "hase_translation_manages.merchant_id",
                    'operator' => '=',
                    'val' => $this->merchantId
                );
            }
           $hase_translation_manages = Translation_manage::
                join('group_permissions as sourceGroup ','translation_manage.language_source','=','sourceGroup.group_id')
                ->join('group_permissions as targetGroup ','translation_manage.language_target','=','targetGroup.group_id')
                ->join('approval_status as sourceStatus ','translation_manage.language_status','=','sourceStatus.approval_status_id')
                ->join('approval_status as targetStatus ','translation_manage.language_status','=','targetStatus.approval_status_id')
                ->join('translation_key as translationKey ','translation_manage.manage_table_id','=','translationKey.key_id')
                ->join('portal_password as portal_password ','translation_manage.translator_user_id','=','portal_password.user_id')
                ->join('portal_password as portal_passwords ','translation_manage.approval_user_id','=','portal_passwords.user_id')
                ->select('translation_manage.*', 'sourceGroup.group_name as source_group_name','targetGroup.group_name as target_group_name', 'sourceStatus.approval_status_name as source_status_name', 'targetStatus.approval_status_name as target_status_name','translationKey.key_table as manage_table','translationKey.key_primary as manage_table_id','portal_password.username as translator_user_id','portal_passwords.username as approval_user_id')->get();
            return json_encode($hase_translation_manages);
        } else {
            return '{}';
        }
    }
    public function createTranslationManageLists(Request $request)
    {
       $hase_translation_manage = new Translation_manage();
       $hase_translation_manage->manage_table_id = $request->manage_table;
       $hase_translation_manage->language_source = $request->source_group_name;
       $hase_translation_manage->language_target = $request->source_status_name;
       $hase_translation_manage->language_status = $request->target_group_name;
       $hase_translation_manage->translator_user_id = $request->translator_user_id;
       $hase_translation_manage->approval_user_id = $request->approval_user_id;
       $hase_translation_manage->save();
       return 1;
    }
    public function updateTranslationManageLists(Request $request)
    {
        $hase_translation_manage = Translation_manage::findOrfail($request->manage_id);
        $hase_translation_manage->manage_table_id = $request->manage_table;
        $hase_translation_manage->language_source = $request->language_source;
        $hase_translation_manage->language_target = $request->language_target;
        $hase_translation_manage->language_status = $request->language_status;
        $hase_translation_manage->translator_user_id = $request->translator_user_id;
        $hase_translation_manage->approval_user_id = $request->approval_user_id;
        $hase_translation_manage->save();
        return 1;
    }
    public function deleteTranslationManageLists(Request $request)
    {
        $hase_translation_manage = Translation_manage::findOrfail($request->manage_id);
        $hase_translation_manage->delete();
        return 1;
    }
    
}
