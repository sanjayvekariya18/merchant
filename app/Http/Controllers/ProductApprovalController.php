<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\App;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Traits\PermissionTrait;
use App\Hase_category;
use App\Permalink;
use App\Approval_comment;
use App\Staff;
use URL;
use Session;
use DB;
use Redirect;
use Auth;
use DateTime;
use Carbon\Carbon;

class ProductApprovalController extends Controller
{
    const STAFF_TABLE_IDENTITY_TYPE = 35;
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
            $this->identity_request_table_live = 'identity';
            $this->identity_request_table_stage = 'identity_stage';  
            $this->staffUrl = session()->has('staffUrl') ? session()->get('staffUrl') :"";
            $this->userId = session()->has('userId') ? session()->get('userId') :"";

            if(!$this->issetHashPassword()){
                Redirect::to($this->staffUrl.'/'. $this->staffId . '/edit')->with('message', 'You must have to change password !')->send();
            }

            return $next($request);
        });
    }

    use PermissionTrait;

    public function index(Request $request){ 
        $defaultValue=$this->roleId;
        $notInArray = array('NOOP');

        $hase_status = DB::table('approval_status')
            ->distinct('approval_group_list.target_approval_status_id')
            ->groupBy('approval_status.approval_status_id')
            ->join('approval_group_list', 'approval_group_list.target_approval_status_id', '=', 'approval_status.approval_status_id')
            ->where('approval_status.approval_status_display',"=",'0')
            ->where('approval_group_list.source_staff_group_id','=',$this->roleId)
            ->whereNotIn('approval_status.approval_status_name',$notInArray)
            ->where('approval_status.approval_status_code','!=','comment')
            ->get();

        $statusCountList=count($hase_status);
        $hase_status_filter=DB::table('approval_status')
            ->distinct('approval_status.approval_status_code')
            ->groupBy('approval_status.approval_status_id')
            ->join('approval', 'approval.approval_status_id', '=', 'approval_status.approval_status_id')
            ->where('approval_status.approval_status_display',">",'0')
            ->where('approval_status.approval_status_code',"!=",'noop')
            ->get();

        return view('approval',compact('title','defaultValue','hase_status','statusCountList','hase_status_filter'));
    }   
    public function ApprovalProductsLists(Request $request,$filterValue) { 

        $categoriesDataJsonList=array();
        $approvalDetailsDataJsonList=array();
        if($filterValue == ' ' || $filterValue == 'null') {
            if($this->roleId == 1)
            {
                $approvalListData = DB::table('approval')->where('ignore','=',0)->orderBy('request_date', 'desc')->offset($request->skip)->limit($request->take)->get();

                $total_records = DB::table('approval')->where('ignore','=',0)->count();

            }
            elseif($this->roleId == 2){
                $approvalListData = DB::table('approval')->where('ignore','=',0)->where('approval_status_id','=','6')->orderBy('request_date', 'desc')->offset($request->skip)->limit($request->take)->get();

                $total_records = DB::table('approval')->where('ignore','=',0)->where('approval_status_id','=','6')->count();

            }elseif($this->roleId == 3){
                $approvalListData = DB::table('approval')->where('ignore','=',0)->where('approval_status_id','!=','2')->where('approval_status_id','!=','3')->orderBy('request_date', 'desc')->offset($request->skip)->limit($request->take)->get();

                $total_records = DB::table('approval')->where('ignore','=',0)->where('approval_status_id','!=','2')->where('approval_status_id','!=','3')->count();

            }else{
                if($this->roleId == 4) {

                    $hase_staffs = Staff::
                            where('merchant_id',$this->merchantId)
                           ->select('staff_id')
                           ->get();
                    $staffIds = array();
                    foreach ($hase_staffs as $key => $value) {
                       $staffIds[] = $value->staff_id;
                    }

                    $approvalListData = DB::table('approval')->where('ignore','=',0)->orderBy('request_date', 'desc')->whereIn('request_staff_id',$staffIds)->offset($request->skip)->limit($request->take)->get();

                    $total_records = DB::table('approval')->where('ignore','=',0)->orderBy('request_date', 'desc')->whereIn('request_staff_id',$staffIds)->count();

                } else {
                    $approvalListData = DB::table('approval')->where('ignore','=',0)->orderBy('request_date', 'desc')->where('request_staff_id',$this->staffId)->offset($request->skip)->limit($request->take)->get();

                    $total_records = DB::table('approval')->where('ignore','=',0)->where('request_staff_id',$this->staffId)->count();
                }
            }
        } else {
            if($filterValue !='all') {  
                    if($this->roleId == 4){
                        $hase_staffs = Staff::
                            where('merchant_id',$this->merchantId)
                           ->select('staff_id')
                           ->get();
                        $staffIds = array();
                        foreach ($hase_staffs as $key => $value) {
                           $staffIds[] = $value->staff_id;
                        }

                        $approvalListData = DB::table('approval')->where('approval_status_id','=',$filterValue)->orderBy('request_date', 'desc')->whereIn('request_staff_id',$staffIds)->where('ignore','=',0)->offset($request->skip)->limit($request->take)->get();

                        $total_records = DB::table('approval')->where('approval_status_id','=',$filterValue)->whereIn('request_staff_id',$staffIds)->where('ignore','=',0)->count();

                    }elseif($this->roleId == 5 || $this->roleId == 6){
                        $approvalListData = DB::table('approval')->where('approval_status_id','=',$filterValue)->orderBy('request_date', 'desc')->where('request_staff_id',$this->staffId)->where('ignore','=',0)->offset($request->skip)->limit($request->take)->get();

                        $total_records = DB::table('approval')->where('approval_status_id','=',$filterValue)->where('request_staff_id',$this->staffId)->where('ignore','=',0)->count();


                    } else {
                        $approvalListData = DB::table('approval')->where('approval_status_id','=',$filterValue)->orderBy('request_date', 'desc')->where('ignore','=',0)->offset($request->skip)->limit($request->take)->get();

                        $total_records = DB::table('approval')->where('approval_status_id','=',$filterValue)->where('ignore','=',0)->count();
                    }
                
            } else { 
                if($this->roleId == 4){
                    $hase_staffs = Staff::
                        where('merchant_id',$this->merchantId)
                       ->select('staff_id')
                       ->get();
                    $staffIds = array();
                    foreach ($hase_staffs as $key => $value) {
                       $staffIds[] = $value->staff_id;
                    }

                    $approvalListData = DB::table('approval')->whereIn('request_staff_id',$staffIds)->orderBy('request_date', 'desc')->where('ignore','=',0)->offset($request->skip)->limit($request->take)->get();

                    $total_records = DB::table('approval')->whereIn('request_staff_id',$staffIds)->where('ignore','=',0)->count();

                }elseif($this->roleId == 5 || $this->roleId == 6){
                    $approvalListData = DB::table('approval')->where('request_staff_id',$this->staffId)->orderBy('request_date', 'desc')->where('ignore','=',0)->offset($request->skip)->limit($request->take)->get();

                    $total_records = DB::table('approval')->where('request_staff_id',$this->staffId)->where('ignore','=',0)->count();

                }else {         
                    $approvalListData = DB::table('approval')->where('ignore','=',0)->orderBy('request_date', 'desc')->offset($request->skip)->limit($request->take)->get();

                    $total_records = DB::table('approval')->where('ignore','=',0)->count();
                }   
            }
        }

        foreach ($approvalListData as $approvalListDataValue) {
            $merchantName = DB::table('merchant')
                                ->select('merchant_id','identity_merchant.identity_name as merchant_name')
                                ->leftjoin('identity_merchant','identity_merchant.identity_id','=','merchant.identity_id')
                                ->where('merchant.merchant_id','=',$approvalListDataValue->merchant_id)
                                ->get()
                                ->first();

            if(!empty($merchantName->merchant_name)){
                $merchantName=$merchantName->merchant_name;
            }else{
                $merchantName='';
            }    
            $staffName = DB::table('staff')->select('identity_name')->leftjoin('identity_staff','staff.identity_id','identity_staff.identity_id')->where('staff_id','=',$approvalListDataValue->request_staff_id)->get()->first(); 
            if(!empty($staffName->identity_name)){
                $staffName=$staffName->identity_name;
            }else{
                $staffName='';
            }
            if(($approvalListDataValue->request_table_live == 'location' || $approvalListDataValue->request_table_live == 'merchant_type_list' || $approvalListDataValue->request_table_live == 'working_hours') && ($approvalListDataValue->request_table_live_primary_id == 0))
            {
                $locationName = DB::table('location_stage')
                                    ->select('location_id','identity_location.identity_name as location_name')
                                    ->leftjoin('identity_location','identity_location.identity_id','=','location_stage.identity_id')
                                    ->where('location_id','=',$approvalListDataValue->merchant_location_id)
                                    ->get()
                                    ->first();

                if(!empty($locationName->location_name)){
                    $locationName=$locationName->location_name;
                }else{
                    $locationName='';
                }
            } else {
                
                $locationName = DB::table('postal')
                                    ->select('postal_id as location_id','postal_premise as location_name')
                                    ->where('postal_id','=',$approvalListDataValue->merchant_location_id)
                                    ->get()
                                    ->first();

                if(!empty($locationName->location_name)){
                    $locationName=$locationName->location_name;
                }else{
                    $locationName='';
                }
            }
            $actionByValue = DB::table('staff')->select('identity_name')->leftjoin('identity_staff','staff.identity_id','identity_staff.identity_id')->where('staff_id','=',$approvalListDataValue->approval_staff_id)->get()->first();
            if(!empty($actionByValue->identity_name)){
                $actionByValue=$actionByValue->identity_name;
            }else{
                $actionByValue='';
            }

            $time=$this->convertIntoTime($approvalListDataValue->request_time);     
            $date=$this->convertIntoDate($approvalListDataValue->request_date);

            $request_table_live_name = DB::table('identity_table_type')
                                ->select('table_code')
                                ->where('type_id',$approvalListDataValue->request_table_live)
                                ->first();

            $keyTableValue=DB::table('approval_key')
                                ->select('key_primary')
                                ->where('key_table',$request_table_live_name->table_code)
                                ->first(); 

            if($approvalListDataValue->request_table_live_primary_id != 0) {       
                $requestFiledValue=DB::table($request_table_live_name->table_code)->select($approvalListDataValue->request_fields)->where($keyTableValue->key_primary,$approvalListDataValue->request_table_live_primary_id)->first();
                
                if($requestFiledValue)
                {
                    $liveUpdatedValue = $this->fetchLiveNameFromId($approvalListDataValue,$requestFiledValue);
                    if($liveUpdatedValue){
                        $requestFiledValue->{$approvalListDataValue->request_fields}=$liveUpdatedValue;    
                    }
                }
          
                if(isset(($requestFiledValue->{$approvalListDataValue->request_fields}))){
                    if($approvalListDataValue->request_fields == 'offer_begin' || $approvalListDataValue->request_fields=='offer_expire'){
                        $filedValueDate=$requestFiledValue->{$approvalListDataValue->request_fields};
                        $filedLiveValue=$this->convertIntoDate($filedValueDate);
                    }else{
                        $filedLiveValue=$requestFiledValue->{$approvalListDataValue->request_fields};
                    }

                    if($approvalListDataValue->request_fields == 'image_url'){        
                        $filedLiveValue=asset(env('image_dir_path'))."/".$requestFiledValue->{$approvalListDataValue->request_fields};
                    }else{
                        $filedLiveValue=$requestFiledValue->{$approvalListDataValue->request_fields};
                    }
                } else {
                    $filedLiveValue='';
                }
            }else{
                $filedLiveValue='';
            }
            $request_table_stage_name = DB::table('identity_table_type')
                                ->select('table_code')
                                ->where('type_id',$approvalListDataValue->request_table_stage)
                                ->first();
            
            $stageValue=DB::table($request_table_stage_name->table_code)
                            ->select($approvalListDataValue->request_fields)
                            ->where($keyTableValue->key_primary,$approvalListDataValue->request_table_stage_primary_id)
                            ->first();            

            if($stageValue) {
                $stageUpdatedValue = $this->fetchStageNameFromId($request_table_stage_name,$approvalListDataValue,$stageValue);
                if($stageUpdatedValue){
                    $stageValue->{$approvalListDataValue->request_fields}=$stageUpdatedValue;    
                }
            }

            if(isset($stageValue->{$approvalListDataValue->request_fields})){

                if($approvalListDataValue->request_fields == 'offer_begin' || $approvalListDataValue->request_fields=='offer_expire'){
                    $filedStageDate=$stageValue->{$approvalListDataValue->request_fields};
                    $fieldStageValue=$this->convertIntoDate($filedStageDate);
                    $this->convertIntoDate($filedStageDate);
                }else {
                    $fieldStageValue=$stageValue->{$approvalListDataValue->request_fields};
                }
                if($approvalListDataValue->request_fields == 'image_url') {
                    $fieldStageValue=asset(env('image_dir_path'))."/".$stageValue->{$approvalListDataValue->request_fields};
                } else {
                    $fieldStageValue=$stageValue->{$approvalListDataValue->request_fields};
                }
            } else {
                $fieldStageValue='';
            }
           
            $requestFiled=DB::table('approval_key')
                ->where('key_table','=',$request_table_live_name->table_code)->get()->first();     

            $requestfieldValue=explode(",", $requestFiled->field_show);
            $requestfieldValue = array_combine(range(1, count($requestfieldValue)), array_values($requestfieldValue));
            $stripped = array_map("trim", $requestfieldValue);        
            $requestFieldList=array_flip($stripped);
            if($approvalListDataValue->approval_time != 0){
                $updatedTime=$this->convertIntoTime($approvalListDataValue->approval_time);  
            } else {
                $updatedTime='';
            }
            if($approvalListDataValue->approval_date != 0) {
                $updatedDate=$this->convertIntoDate($approvalListDataValue->approval_date);  
            }else{
               $updatedDate='';
            }
            if(isset($approvalListDataValue->comment_id)){
                $reviewerComments=$approvalListDataValue->comment_id;
            }else{
                $reviewerComments='';
            }         


            $statusList=DB::table('approval_status')
                ->where('approval_status_id','=',$approvalListDataValue->approval_status_id)->get()->first();

            if(isset($statusList)){
                $statusId=$statusList->approval_status_id;
                $statusName=$statusList->approval_status_name;
                $statusDisplay=$statusList->approval_status_display;
                $statusColor=$statusList->approval_status_color;
                $statusFontColor=$statusList->approval_status_font_color;
            }
            $currentApprovalStatus =  $approvalListDataValue->approval_status_id;
            $rowStatusTargetList = array();
            $rowstatusList=DB::table('approval_status')
                ->distinct('approval_status.approval_status_name')
                ->select('approval_status.approval_status_id','approval_status.approval_status_name')
                ->join('approval_group_list', function($join) use($currentApprovalStatus) {
                      $join->on('approval_status.approval_status_id', '=', 'approval_group_list.target_approval_status_id')
                            ->where('approval_group_list.source_staff_group_id', $this->roleId)
                            ->where('approval_group_list.source_approval_status_id', $currentApprovalStatus);
                    })
                ->where('approval_status.approval_status_display',0)
                ->get();

            if(isset($rowstatusList))
            {
                foreach ($rowstatusList as $rowstatusListey => $rowstatusListValue) {
                    $rowStatusTargetList[$rowstatusListey]['id'] = $rowstatusListValue->approval_status_id;
                    $rowStatusTargetList[$rowstatusListey]['name'] = $rowstatusListValue->approval_status_name;
                }
            }
            $identity_details=DB::table('portal_password')
                                ->where('user_id',$approvalListDataValue->request_staff_id)
                                ->first();
            $identity_table_name = DB::table('identity_table_type')
                                ->select('table_code')
                                ->where('type_id',$identity_details->identity_table_id)
                                ->first()->table_code;
            $staffGroupList = DB::table($identity_table_name)
                ->select('identity_group_list.group_id')
                ->join('identity_group_list',$identity_table_name.'.identity_id','=','identity_group_list.identity_id')
                ->where($identity_table_name.'.identity_id','=',$identity_details->identity_id)
                ->where('identity_group_list.identity_table_id','=',$identity_details->identity_table_id)
                ->get()->first();
            if(!empty($staffGroupList->group_id)) {
               $staffGroupId = $staffGroupList->group_id;
            }
            if(isset($requestFieldList[$approvalListDataValue->request_fields])){
                $categoriesDataJsonList[]=array('staffGroupId'=>$this->userId,'commentCellColor'=>'','commentId'=>$approvalListDataValue->comment_id,'statusId'=>$statusId,'statusFontColor'=>$statusFontColor,'colorCode'=>$statusColor,'statusName'=>$statusName,'statusDisplay'=>$statusDisplay,'updatedAt'=>$updatedDate." ".$updatedTime,'category_value'=>$request_table_live_name->table_code,'live_value'=>$filedLiveValue,'stage_value'=>$fieldStageValue,'request_fields'=>$approvalListDataValue->request_fields,'approval_id'=>$approvalListDataValue->approval_id,'staff_name'=>$staffName,'approval_grouphash'=>$approvalListDataValue->approval_grouphash,'request_date'=>$date." ".$time,'loacatinName'=>$locationName,'merchant_id'=>$approvalListDataValue->merchant_id,'Group'=>$approvalListDataValue->approval_grouphash,'approval_status_id'=>$approvalListDataValue->approval_status_id,'comment'=>$reviewerComments,'actionBy'=>$actionByValue,'rowStatusAction'=>$rowStatusTargetList,'rowStatusTargetListCount'=>count($rowStatusTargetList));
            } 
        } 
        /*foreach ($categoriesDataJsonList as $categoriesDataJsonListValue) {
            $stageValue=trim($categoriesDataJsonListValue['stage_value']);
            if($stageValue != NULL){
               $approvalDetailsDataJsonList[]=$categoriesDataJsonListValue; 
            }         
        } */
        $approvalDetailsDataJsonListData['approval'] = $categoriesDataJsonList;
        $approvalDetailsDataJsonListData['total'] = $total_records; 

       return json_encode($approvalDetailsDataJsonListData);
    }

    public function UpdateApprovalStatus(Request $request){
        $statusList=DB::table('approval_group_list')
                ->distinct('approval_group_list.target_approval_status_id')
                ->join('approval_status','approval_status.approval_status_id','=','approval_group_list.source_approval_status_id')
                ->where('approval_group_list.source_staff_group_id','=',$this->roleId)
                ->where('approval_status.approval_status_name','=',$request->approvalStatus)
                ->get()->first();

        if(isset($statusList)){
                 $statusId=$statusList->target_approval_status_id;
        }
        if($statusList->approval_status_code != 'comment' && $statusList->approval_status_code != 'noop'){
            $this->updateStageToLive($statusId,$request->approvalId);
        }
   }
    public function updateApprovalStatusMultiple(Request $request){
        $approvalActionList=DB::table('approval')->whereIn('approval_id',$request->cbQ)->groupBy('approval_grouphash')->get();  

        $statusList=DB::table('approval_group_list')
                ->distinct('approval_group_list.target_approval_status_id')
                ->join('approval_status','approval_status.approval_status_id','=','approval_group_list.source_approval_status_id')
                ->where('approval_group_list.source_staff_group_id','=',$this->roleId)
                ->where('approval_status.approval_status_name','=',$request->task)
                ->get()->first();

        if(isset($statusList)){
                 $statusId=$statusList->target_approval_status_id;
        }
        foreach ($approvalActionList as $approvalActionListValue) {
            if(isset($statusId)){ if($statusList->approval_status_code != 'comment' && $statusList->approval_status_code != 'noop'){
                     $this->updateStageToLive($statusId,$approvalActionListValue->approval_id);
                }    
           }
        } 
       return redirect('hase_apparoval');
    }       

    
    public function UpdateApprovalComments(Request $request){
        $createdAt =Carbon::now();
        $comment_Date = str_replace('-', '', $createdAt->toDateString());
        $requestTimeFormat = $createdAt->toTimeString();
        $openTimeData = explode(":", $requestTimeFormat);
        $comment_time = $openTimeData[0]*3600+$openTimeData[1]*60;
        DB::table('approval')->where('approval_id','=',$request->approvalId)->update(array('approval_staff_id'=>$this->roleId,'approval_date'=>$comment_Date,'approval_time'=>$comment_time));

        $comment_approve_id= DB::table('approval')->where('approval_id','=',$request->approvalId)->get()->first();

            if(!isset($comment_approve_id->comment_id)){
                        $comment_id = DB::table('approval_comment')->insertGetId(
                        ['comment'=>$request->approveComments,'comment_date'=>$comment_Date,'comment_time'=>$comment_time,'comment_parent_id'=>'','comment_source_id'=>$this->roleId]);
                        DB::table('approval')->where('approval_id','=',$request->approvalId)->update(array('comment_id'=>$comment_id));
                        DB::table('approval_comment')->where('comment_id','=',$comment_id)->update(array('comment_root_id'=>$comment_id));

            }else{
                    $comment_id = DB::table('approval_comment')->insertGetId(
                   ['comment'=>$request->approveComments,'comment_date'=>$comment_Date,'comment_time'=>$comment_time,'comment_parent_id'=>$comment_approve_id->comment_id,'comment_source_id'=>$this->roleId,'comment_root_id'=>$comment_approve_id->comment_id]);  
            }
    }
    public function RejectsCommentList($approvalId){
            $rejectCommentList=array();
            $rejectApproveCommentList = DB::table('approval_comment')->where('comment_root_id','=',$approvalId)->get();              
            foreach ($rejectApproveCommentList as $rejectApproveCommentListValue) {
                $actionByValue = DB::table('staff')->select('identity_name')->leftjoin('identity_staff','staff.identity_id','identity_staff.identity_id')->where('staff_id','=',$rejectApproveCommentListValue->comment_source_id)->get()->first();
                $rejectCommentList[]=array('commentDate'=>$this->convertIntoDate($rejectApproveCommentListValue->comment_date),'commentTime'=>$this->convertIntoTime($rejectApproveCommentListValue->comment_time),'comment'=>$rejectApproveCommentListValue->comment,'commentedBy'=>$actionByValue->identity_name);
            }
       return $rejectCommentList;
    }
    public function ProductUpdate(Request $request){
    }
    
    public function fetchLiveNameFromId($approvalListDataValue,$requestFiledValue)
    {
        $flagValue = true;
        switch ($approvalListDataValue->request_table_live) {

            case 'merchant_retail_category_option_list':
                switch ($approvalListDataValue->request_fields) {
                    case 'category_type_id':

                        $styleSQLResult = "category_name";
                        $styleSQLTable = "merchant_retail_category_type";
                        $styleSQLWhere = "category_type_id";
                        break;

                    case 'option_type_id':

                        $styleSQLResult = "option_name";
                        $styleSQLTable = "merchant_retail_category_option";
                        $styleSQLWhere = "category_option_type_id";
                        break;

                    default:
                        $flagValue = false;
                        break;
                }
                break;

            case 'location':

                switch ($approvalListDataValue->request_fields) {
                    case 'merchant_id':

                        $styleSQLResult = "merchant_name";
                        $styleSQLTable = "merchant";
                        $styleSQLWhere = "merchant_id";
                        break;

                    default:
                        $flagValue = false;
                        break;
                }
                break;

            case 'merchant':

                switch ($approvalListDataValue->request_fields) {
                    case 'merchant_type':

                        $styleSQLResult = "merchant_type_name";
                        $styleSQLTable = "merchant_type";
                        $styleSQLWhere = "merchant_type_id";
                        break;

                    default:
                        $flagValue = false;
                        break;
                }
                break;

            case 'merchant_type_list':

                switch ($approvalListDataValue->request_fields) {
                    case 'merchant_type':

                        $styleSQLResult = "merchant_type_name";
                        $styleSQLTable = "merchant_type";
                        $styleSQLWhere = "merchant_type_id";
                        break;

                    default:
                        $flagValue = false;
                        break;
                }
                break;

            case 'working_hours':

                switch ($approvalListDataValue->request_fields) {
                    case 'location_id':

                        $styleSQLResult = "postal_premise";
                        $styleSQLTable = "postal";
                        $styleSQLWhere = "postal_id";
                        break;

                    default:
                        $flagValue = false;
                        break;
                }
                break;

            case 'merchant_retail_category_list':

                switch ($approvalListDataValue->request_fields) {
                    case 'category_type_id':

                        $styleSQLResult = "category_name";
                        $styleSQLTable = "merchant_retail_category_type";
                        $styleSQLWhere = "category_type_id";
                        break;

                    default:
                        $flagValue = false;
                        break;
                }
                break;

            case 'merchant_retail_style_list':

                switch ($approvalListDataValue->request_fields) {
                    case 'style_type_id':

                        $styleSQLResult = "style_name";
                        $styleSQLTable = "merchant_retail_style_type";
                        $styleSQLWhere = "style_type_id";
                        break;

                    default:
                        $flagValue = false;
                        break;
                }
                break;
            case 'promotions':

                switch ($approvalListDataValue->request_fields) {

                    case 'merchant_id':
                        $styleSQLResult = "merchant_name";
                        $styleSQLTable = "merchant";
                        $styleSQLWhere = "merchant_id";
                        break;

                    case 'location_id':
                        $styleSQLResult = "postal_premise";
                        $styleSQLTable = "postal";
                        $styleSQLWhere = "postal_id";
                        break;

                    default:
                        $flagValue = false;
                        break;
                }
                break;
            default:

                $flagValue = false;
                break;
        }
        if($flagValue) {
            $styleResultFields = DB::table($styleSQLTable)
                    ->select("identity_".$styleSQLTable.".identity_name as ".$styleSQLResult)
                    ->leftjoin("identity_".$styleSQLTable,'identity_'.$styleSQLTable.'.identity_id','=',$styleSQLTable.'.identity_id')
                    ->where($styleSQLWhere,$requestFiledValue->{$approvalListDataValue->request_fields})
                    ->first();
            return $styleResultFields->$styleSQLResult;
        }
    }

    public function fetchStageNameFromId($request_table_stage_name,$approvalListDataValue,$stageValue)
    {
        $flagValue = true;

        switch ($request_table_stage_name->table_code) {

            case 'locations_stage':

                switch ($approvalListDataValue->request_fields) {

                    case 'merchant_id':

                        $styleSQLResult = "merchant_name";
                        $styleSQLTable = "merchant";
                        $styleSQLWhere = "merchant_id";
                        break;

                    default:
                        $flagValue = false;
                        break;
                }
                break;

            case 'merchant_stage':

                switch ($approvalListDataValue->request_fields) {

                    case 'merchant_type':

                        $styleSQLResult = "merchant_type_name";
                        $styleSQLTable = "merchant_type";
                        $styleSQLWhere = "merchant_type_id";
                        break;

                    default:
                        $flagValue = false;
                        break;
                }
                break;

            case 'merchant_type_list_stage':

                switch ($approvalListDataValue->request_fields) {

                    case 'merchant_type':

                        $styleSQLResult = "merchant_type_name";
                        $styleSQLTable = "merchant_type";
                        $styleSQLWhere = "merchant_type_id";
                        break;

                    default:
                        $flagValue = false;
                        break;
                }
                break;

            case 'working_hours_stage':

                switch ($approvalListDataValue->request_fields) {

                    case 'location_id':

                        $styleSQLResult = "postal_premise";
                        $styleSQLTable = "postal";
                        $styleSQLWhere = "postal_id";
                        break;

                    default:
                        $flagValue = false;
                        break;
                }
                break;

            case 'merchant_retail_category_list_stage':
                switch ($approvalListDataValue->request_fields) {
                    case 'category_type_id':

                        $styleSQLResult = "category_name";
                        $styleSQLTable = "merchant_retail_category_type";
                        $styleSQLWhere = "category_type_id";
                        break;

                    case 'product_id':

                        $styleSQLResult = "product_name";
                        $styleSQLTable = "product";
                        $styleSQLWhere = "product_id";
                        break;

                    default:
                        $flagValue = false;
                        break;
                }
                break;

            case 'merchant_retail_category_option_list_stage':
                switch ($approvalListDataValue->request_fields) {

                    case 'product_id':

                        $styleSQLResult = "product_name";
                        $styleSQLTable = "product";
                        $styleSQLWhere = "product_id";
                        break;
                        
                    case 'category_type_id':

                        $styleSQLResult = "category_name";
                        $styleSQLTable = "merchant_retail_category_type";
                        $styleSQLWhere = "category_type_id";
                        break;

                    case 'category_option_type_id':

                        $styleSQLResult = "option_name";
                        $styleSQLTable = "merchant_retail_category_option";
                        $styleSQLWhere = "category_option_type_id";
                        break;

                    default:
                        $flagValue = false;
                        break;
                }
                break;

            case 'merchant_retail_style_list_stage':
                switch ($approvalListDataValue->request_fields) {
                    case 'style_type_id':

                        $styleSQLResult = "style_name";
                        $styleSQLTable = "merchant_retail_style_type";
                        $styleSQLWhere = "style_type_id";
                        break;

                    case 'product_id':

                        $styleSQLResult = "product_name";
                        $styleSQLTable = "product";
                        $styleSQLWhere = "product_id";
                        break;

                    default:
                        $flagValue = false;
                        break;
                }
                break;

            case 'promotions_stage':

                switch ($approvalListDataValue->request_fields) {

                    case 'merchant_id':

                        $styleSQLResult = "merchant_name";
                        $styleSQLTable = "merchant";
                        $styleSQLWhere = "merchant_id";
                        break;

                    case 'location_id':

                        $styleSQLResult = "postal_premise";
                        $styleSQLTable = "postal";
                        $styleSQLWhere = "postal_id";
                        break;

                    default:
                        $flagValue = false;
                        break;
                }
                break;        
          

            default:

                $flagValue = false;
                break;
        }

        if($flagValue) {
            
            $styleResultFields = DB::table($styleSQLTable)
                    ->select("identity_".$styleSQLTable.".identity_name as ".$styleSQLResult)
                    ->leftjoin("identity_".$styleSQLTable,'identity_'.$styleSQLTable.'.identity_id','=',$styleSQLTable.'.identity_id')
                    ->where($styleSQLWhere,$stageValue->{$approvalListDataValue->request_fields})
                    ->first();
            return $styleResultFields->$styleSQLResult;
        }
    }

    /* Transition workflow status from sourceId -> targetId */
    public function ApprovalActionStatusTransitionToAnotherDB($targetGroupId, $statusSourceId) {
        $roleId = session()->has('role') ? session()->get('role') :"";
        $statusList = DB::table('approval_group_list')
            ->join('approval_status','approval_status.approval_status_id','=','approval_group_list.target_approval_status_id')
            ->select('target_approval_status_id','approval_status_code','approval_status_name')
            ->where('approval_group_list.source_staff_group_id','=',$roleId)
            //->where('approval_group_list.target_staff_group_id','=',$targetGroupId)
            ->where('approval_group_list.source_approval_status_id','=',$statusSourceId)
            ->get()->first();
        return $statusList;
    }

    public function ApprovalActionStatusTransitionToAnother(Request $request) {
        $statusList = $this->ApprovalActionStatusTransitionToAnotherDB($request->staffGroupId, $request->statusId);
        $statusError = DB::table('approval_status')
            ->select('approval_status_id')
            ->where('approval_status.approval_status_code','=','error')
            ->get()->first();

        if(isset($statusList)) {
            if ($statusList->approval_status_name == 'noop') {
                $statusId = 0;
            } else {
                $statusId = $statusList->target_approval_status_id;
            }
        } else {
            $statusId = $statusError->approval_status_id;
        }

        if (isset ($statusList->approval_status_code)) {
            if($statusId > 0) {
                echo "<pre>";
                print_r($this->updateStageToLive($statusId,$request->approvalId));
            }
        }
    }    


    public function ApprovalActionStatusTransitionBySelfDB($statusSourceId) {
       
       $roleId = session()->get('role');



       $statusNoop = DB::table('approval_status')
           ->select('approval_status_id')
           ->where('approval_status.approval_status_code','noop')
           ->get()->first();

       $statusError = DB::table('approval_status')
           ->select('approval_status_id')
           ->where('approval_status.approval_status_code','error')
           ->get()->first();

       $statusList = DB::table('approval_group_list')        
           ->join('approval_status','approval_status.approval_status_id','approval_group_list.target_approval_status_id')
           ->select('target_approval_status_id','approval_status_code','approval_status_name')
           ->where('approval_group_list.source_staff_group_id',$roleId)
           ->where('approval_group_list.source_approval_status_id',$statusSourceId)
           ->get()->first();

        if(count($statusList)){
            if ($statusList->approval_status_code == 'noop') {
               $statusId = 0;
            } else {
               $statusId = $statusList->target_approval_status_id;
            }
        } else {
            $statusId = $statusError->approval_status_id;
        }

        return $statusId;
    }

    public function ApprovalActionStatusTransitionBySelf($statusSourceCode) {

        $statusSource = DB::table('approval_status')
           ->select('approval_status_id')
           ->where('approval_status.approval_status_code',$statusSourceCode)
           ->get()->first();

        return $this->ApprovalActionStatusTransitionBySelfDB($statusSource->approval_status_id);
    }

    public function getApprovalCrudStatusId($statusCode){

        $approvalStatus = DB::table('approval_crud_status')
                        ->where('crud_status_code',$statusCode)
                        ->get()->first();
        return $approvalStatus->crud_status_id;
    }
}
