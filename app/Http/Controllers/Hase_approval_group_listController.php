<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\App;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Approval_group_list;
use App\Http\Traits\PermissionTrait;
use App\Group_permission;
use App\Approval_status;
use App\Approval_category;
use App\Merchant;
use URL;
use Session;
use DB;
use Redirect;
use Auth;

/**
 * Class Hase_approval_group_listController.
 *
 * @author  The scaffold-interface created at 2017-04-05 04:16:06pm
 * @link  https://github.com/amranidev/scaffold-interface
 */
class Hase_approval_group_listController extends Controller
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
            $this->request_table_live = 'merchant';
            $this->request_table_stage = 'merchant_stage';
            $this->staffUrl = session()->has('staffUrl') ? session()->get('staffUrl') :"";

            if(!$this->issetHashPassword()){
                Redirect::to($this->staffUrl.'/'. $this->staffId . '/edit')->with('message', 'You must have to change password !')->send();
            }

            return $next($request);
        });
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
        $hase_staff_groups = Group_permission::select('group_id as '.$request->group.'_staff_group_id','group_name as '.$request->group.'_group_name')->where('group_id','>',0)
            ->where(function($q) use ($where){
                foreach($where as $key => $value){
                    $q->where($value['key'], $value['operator'], $value['val']);
                }
            })->get();
        return json_encode($hase_staff_groups);
    }

    public function getApprovalStatus(Request $request)
    {
        $hase_approval_statuses = Approval_status::select('approval_status_id as '.$request->status.'_approval_status_id','approval_status_name as '.$request->status.'_status_name')->get();
        return json_encode($hase_approval_statuses);
    }

    public function getMerchantList(Request $request)
    {
        $where = array();
        if($this->merchantId != 0){
            $where[] = array(
                'key' => "merchant.merchant_id",
                'operator' => '=',
                'val' => $this->merchantId
            );
        }
        $merchantList = Merchant::select('merchant.merchant_id','identity_merchant.identity_name as merchant_name')->join('identity_merchant','identity_merchant.identity_id','merchant.identity_id')
        ->where(function($q) use ($where){
            foreach($where as $key => $value){
                $q->where($value['key'], $value['operator'], $value['val']);
            }
        })->get();
        return json_encode($merchantList);
    }

    public function getCategoryList(Request $request)
    {
        
        $categoryList = Approval_category::all();
        return json_encode($categoryList);
    }


    public function getApprovalGropLists(Request $request)
    {
        if($this->permissionDetails('Hase_approval_group_list','access')) {
            $where = array();
            if($this->merchantId != 0){
                $where[] = array(
                    'key' => "approval_group_list.merchant_id",
                    'operator' => '=',
                    'val' => $this->merchantId
                );
            }
            $hase_approval_group_lists = Approval_group_list::
                select('approval_group_list.*','sourceGroup.group_name as source_group_name','targetGroup.group_name as target_group_name','sourceStatus.approval_status_name as source_status_name','targetStatus.approval_status_name as target_status_name','identity_merchant.identity_name as merchant_name','approval_category.category_name')
                ->leftjoin('group_permissions as sourceGroup','approval_group_list.source_staff_group_id','=','sourceGroup.group_id')
                ->leftjoin('group_permissions as targetGroup','approval_group_list.target_staff_group_id','=','targetGroup.group_id')
                ->leftjoin('approval_status as sourceStatus','approval_group_list.source_approval_status_id','=','sourceStatus.approval_status_id')
                ->leftjoin('approval_status as targetStatus','approval_group_list.target_approval_status_id','=','targetStatus.approval_status_id')
                ->join('merchant','merchant.merchant_id','=','approval_group_list.merchant_id')
                ->join('identity_merchant','identity_merchant.identity_id','=','merchant.identity_id')
                ->join('approval_category','approval_category.category_id','=','approval_group_list.category_id')
                ->where(function($q) use ($where){
                    foreach($where as $key => $value){
                        $q->where($value['key'], $value['operator'], $value['val']);
                    }
                });
            $approval_routing_results['total'] = Approval_group_list::where(function($q) use ($where){
                    foreach($where as $key => $value){
                        $q->where($value['key'], $value['operator'], $value['val']);
                    }
                })->count();
            $approval_routing_results['approval_group_list'] = $hase_approval_group_lists->offset($request->skip)->limit($request->take)->orderBy('staff_group_list_id','DESC')->get();
            return json_encode($approval_routing_results);
        } else {
            return '{}';
        }
    }

    public function createApprovalGropLists(Request $request)
    {
        $hase_approval_group_list = new Approval_group_list();
        $hase_approval_group_list->merchant_id = $request->merchant_id;
        $hase_approval_group_list->category_id = $request->category_id;
        $hase_approval_group_list->source_staff_group_id = $request->source_staff_group_id;
        $hase_approval_group_list->target_staff_group_id = $request->target_staff_group_id;
        $hase_approval_group_list->source_approval_status_id = $request->source_approval_status_id;
        $hase_approval_group_list->target_approval_status_id = $request->target_approval_status_id;
        $hase_approval_group_list->save();
        return 1;
    }

    public function updateApprovalGropLists(Request $request)
    {
        $hase_approval_group_list = Approval_group_list::findOrfail($request->staff_group_list_id);
        $hase_approval_group_list->merchant_id = $request->merchant_id;
        $hase_approval_group_list->category_id = $request->category_id;
        $hase_approval_group_list->source_staff_group_id = $request->source_staff_group_id;
        $hase_approval_group_list->target_staff_group_id = $request->target_staff_group_id;
        $hase_approval_group_list->source_approval_status_id = $request->source_approval_status_id;
        $hase_approval_group_list->target_approval_status_id = $request->target_approval_status_id;
        $hase_approval_group_list->save();
        return 1;
    }

    public function deleteApprovalGropLists(Request $request)
    {
        $hase_approval_group_list = Approval_group_list::findOrfail($request->staff_group_list_id);
        $hase_approval_group_list->delete();
        return 1;
    }

    /**
     * Display a listing of the resource.
     *
     * @return  \Illuminate\Http\Response
     */
    public function index()
    {
        if($this->permissionDetails('Hase_approval_group_list','access')) {
            if($this->merchantId == 0)
            {
                $merchantEditable = "enable";
            } else {
                $merchantEditable = "disable";
            }
            return view('hase_approval_group_list.index',compact('merchantEditable'));
        } else {
            return Redirect::back()->with('message', 'You are not authorized to use this functionality!');
        }
    }
}
