<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\App;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Translation_status_view_manage;
use Amranidev\Ajaxis\Ajaxis;
use App\Approval_group_list;
use App\Http\Traits\PermissionTrait;
use App\Group_permission;
use App\Approval_status;
use App\Hase_user;
use App\Promotion;
use URL;
use DB;
use Auth;
use Session;
use Redirect;

/**
 * Class Hase_translation_status_view_manageController.
 *
 * @author  The scaffold-interface created at 2017-11-02 12:45:11pm
 * @link  https://github.com/amranidev/scaffold-interface
 */
class Hase_translation_status_view_manageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return  \Illuminate\Http\Response
     */
    use PermissionTrait;
    public function index()
    {
        $title = 'Index - hase_translation_status_view_manage';
        $hase_translation_status_view_manages = Translation_status_view_manage::
                join('group_permissions as targetGroup ','translation_status_view_manage.status_target','=','targetGroup.group_id')
                ->join('approval_status as sourceStatus ','translation_status_view_manage.user_view_status','=','sourceStatus.approval_status_id')
                ->select('translation_status_view_manage.*', 'targetGroup.group_name as status_view_group_name', 'sourceStatus.approval_status_name as status_view_name')->get();
        return view('hase_translation_status_view_manage.index',compact('hase_translation_status_view_manages','title'));
    }
    public function getUserStatusManageLists(){
        /*if($this->permissionDetails('Hase_translation_status_view_manages','add')) {
            $where = array();
            if($this->merchantId != 0){
                $where[] = array(
                    'key' => "hase_translation_status_view_manages.merchant_id",
                    'operator' => '=',
                    'val' => $this->merchantId
                );
            }*/
           $hase_translation_status_view_manages = Translation_status_view_manage::
                join('group_permissions as targetGroup ','translation_status_view_manage.status_target','=','targetGroup.group_id')
                ->join('approval_status as sourceStatus ','translation_status_view_manage.user_view_status','=','sourceStatus.approval_status_id')
                ->select('translation_status_view_manage.*', 'targetGroup.group_name as status_view_group_name', 'sourceStatus.approval_status_name as status_view_name')->get();
            return json_encode($hase_translation_status_view_manages);
       /* } else {
            return '{}';
        }*/
    }

    public function createUserStatusManageLists(Request $request){
        $hase_translation_status_view_manage = new Translation_status_view_manage();
        $hase_translation_status_view_manage->manage_id = $request->manage_id;
        $hase_translation_status_view_manage->status_target = $request->status_view_group_name;
        $hase_translation_status_view_manage->user_view_status = $request->status_view_name;
        $hase_translation_status_view_manage->manage_table = $request->manage_table;
        $hase_translation_status_view_manage->save();
        return 1;
    }
    public function updateUserStatusManageLists(Request $request){
         $hase_translation_status_view_manage = Translation_status_view_manage::findOrfail($request->manage_id); 
        $hase_translation_status_view_manage->status_target = $request->status_view_group_name;
        $hase_translation_status_view_manage->user_view_status = $request->status_view_name;
        $hase_translation_status_view_manage->manage_table = $request->manage_table;
        $hase_translation_status_view_manage->save();
    }
    public function deleteUserStatusManageLists(Request $request){
        $hase_translation_status_view_manage = Translation_status_view_manage::findOrfail($request->manage_id);
        $hase_translation_status_view_manage->delete();
    }
    /**
     * Display the specified resource.
     *
     * @param    \Illuminate\Http\Request  $request
     * @param    int  $id
     * @return  \Illuminate\Http\Response
     */
    public function show($id,Request $request)
    {
        $title = 'Show - hase_translation_status_view_manage';

        if($request->ajax())
        {
            return URL::to('hase_status_view_manage/'.$id);
        }

        $hase_translation_status_view_manage = Translation_status_view_manage::findOrfail($id);
        return view('hase_translation_status_view_manage.show',compact('title','hase_translation_status_view_manage'));
    }
    
}
