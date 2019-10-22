<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\App;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use URL;
use Session;
use DB;
use Redirect;
use Auth;
use DateTime;
use Carbon\Carbon;
use App\Http\Traits\PermissionTrait;
use App\Search_result_scrape;
use App\Identity_table_type;
define('CURL_PATH', 'cURL/CurlWrapper.php');
use curl\ CurlWrapper;
use PDO;
require_once(CURL_PATH);

class Hase_translation_viewController extends Controller
{   
   public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
        $this->staffId = session()->has('staffId') ? session()->get('staffId') :"";
        $this->identity_table_id = session()->has('identity_table_id') ? session()->get('identity_table_id') :"";
        $this->staffName = session()->has('staffName') ? session()->get('staffName') :"";
        $this->roleId = session()->has('role') ? session()->get('role') :"";
        $this->locationId = session()->has('locationId') ? session()->get('locationId') :"";
        $this->userId = session()->has('userId') ? session()->get('userId') :"";
        return $next($request);
        });
    }
    use PermissionTrait;
    public function UserKnownLanguageList(Request $request){
        $userLanguageListData=array();
        $userLanguageList=DB::table('identity_language_list')
                      ->leftjoin('languages','languages.language_id','=','identity_language_list.language_id')
                      ->where('identity_id','=',$this->staffId)
                      ->where('identity_table_id','=',$this->identity_table_id)->get(); 
            foreach ($userLanguageList as $userLanguagevalue) {
                $userLanguageListData[]=array('language_code'=>$userLanguagevalue->language_code,'language_name'=>$userLanguagevalue->language_name);
            }
        return json_encode($userLanguageListData);
    }
    public function translationCurlUrlAction($translationCurlUrl,$translationCurlOption){
        $curlWrapper=new CurlWrapper();
        return $curlWrapper->curlPost($translationCurlUrl, $translationCurlOption);
    }
    public function TranslationActionStatusTransitionToAnotherDB($targetGroupId, $statusSourceId) {
        $roleId = session()->has('role') ? session()->get('role') :"";
        $statusList = DB::table('approval_group_list')
            ->join('approval_status','approval_status.approval_status_id','=','approval_group_list.target_approval_status_id')
            ->select('approval_group_list.target_approval_status_id','approval_status.approval_status_code','approval_status.approval_status_name')
            ->where('approval_group_list.source_staff_group_id','=',$roleId)
            ->where('approval_group_list.target_staff_group_id','=',$targetGroupId)
            ->where('approval_group_list.source_approval_status_id','=',$statusSourceId)
            ->get()->first();

        return $statusList;
    }
    public function translationApprovalView(request $request){
        if($this->permissionDetails('Hase_translation_approval','access')){
            $translatedList=DB::table('translation_approval')->get();
            $commaSeparators='';
            $statusList=0;    
            foreach ($translatedList as $translatedListValue) {
                    $statusId=$translatedListValue->approval_status_id;
                    $statusList.=$commaSeparators.$statusId;
                    $commaSeparators =',';
            }
            $statusValue=explode(",", $statusList);
            $statusListArray =$statusValue;
            $hase_translaton_status_filter=DB::table('approval_status')
                ->distinct('approval_status_code')
                ->groupBy('approval_status_id')
                ->whereIn('approval_status_id',$statusListArray)    
                ->where('approval_status_display',">",'0')
                ->where('approval_status_code',"!=",'noop')
                ->get();  
            $statusCountList=count($hase_translaton_status_filter);
            return view('hase_translation.translation_approval_list',compact('title','statusCountList','hase_translaton_status_filter'));
        }else{
            return Redirect::back()->with('message', 'You are not authorized to use this functionality!');
        }
    }
    public function translationApprovalCurrentDetailList(Request $request){
        $translationApprovalCurrentDetailList=array();
            if($request->filterValue == 'all' || $request->filterValue == 'null'){
                $translationApprovalList=DB::table('translation_approval')->orderBy('request_date','DESC')->orderBy('approval_date','DESC')->groupBy('approval_grouphash','request_fields')->get();
            }
            else{
                $translationApprovalList=DB::table('translation_approval')->where('approval_status_id','=',$request->filterValue)->orderBy('request_date','DESC')->groupBy('approval_grouphash','request_fields')->orderBy('approval_date','DESC')->get();
            }    
        foreach ($translationApprovalList as $translationApprovalDetails) {
             $translationHistoryList=DB::table('translation_approval')->where('approval_grouphash','=',$translationApprovalDetails->approval_grouphash)->get();
             $translationHistoryCount=count($translationHistoryList);
             $translationHistoryLatest=DB::table('translation_approval')->select('translation_text')->where('approval_grouphash','=',$translationApprovalDetails->approval_grouphash)->where('translation_version','=',$translationHistoryCount)->first();
            $translationTableDetails=DB::table('translation_key')->where('key_table','=',$translationApprovalDetails->request_table_stage)->get()->first();
            $imageDetialsList=DB::table($translationApprovalDetails->request_table_stage)->select($translationApprovalDetails->request_fields)->where($translationTableDetails->key_primary,$translationApprovalDetails->approval_grouphash)->first();
                $translationApprovalDetails->request_fields;
                $originalContent=$imageDetialsList->{$translationApprovalDetails->request_fields};
                $translationListValue=$translationTableDetails->key_id;
            $statusList=DB::table('approval_status')->where('approval_status_id','=',$translationApprovalDetails->approval_status_id)->get()->first();
                        if(isset($statusList)){
                            $statusId=$statusList->approval_status_id;
                            $statusName=$statusList->approval_status_name;
                            $statusDisplay=$statusList->approval_status_display;
                            $statusColor=$statusList->approval_status_color;
                            $statusFontColor=$statusList->approval_status_font_color;
                        }
            $imageStatusValue=$translationApprovalDetails->approval_status_id;
            $rowStatusTargetList = array();
            $rowstatusList=DB::table('approval_status')
                ->distinct('approval_status.approval_status_name')
                ->select('approval_status.approval_status_id','approval_status.approval_status_name')
                ->join('approval_group_list', function($join) use($imageStatusValue) {
                      $join->on('approval_status.approval_status_id', '=', 'approval_group_list.target_approval_status_id')
                            ->where('approval_group_list.source_staff_group_id', $this->roleId)
                            ->where('approval_group_list.source_approval_status_id', $imageStatusValue);
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
                                ->where('user_id',$translationApprovalDetails->request_staff_id)
                                ->first();
            if(!empty($identity_details->identity_table_id)){                    
            $identity_table_name = DB::table('identity_table_type')
                                ->select('table_code')
                                ->where('type_id',$identity_details->identity_table_id)
                                ->first()->table_code;
                            
            $staffName = DB::table($identity_table_name)->select('identity_name')->leftjoin('identity_'.$identity_table_name,$identity_table_name.'.identity_id','identity_'.$identity_table_name.'.identity_id')->where($identity_table_name.'_id','=',$translationApprovalDetails->request_staff_id)->first(); 
                $staffName=$staffName->identity_name;
            }else{
                $staffName='Guest';
            }
            $identity_details=DB::table('portal_password')
                                ->where('user_id',$translationApprovalDetails->approval_staff_id)
                                ->first();
            if(!empty($identity_details->identity_table_id)){                    
            $identity_table_name = DB::table('identity_table_type')
                                ->select('table_code')
                                ->where('type_id',$identity_details->identity_table_id)
                                ->first()->table_code;
            $actionByValue = DB::table($identity_table_name)->select('identity_name')->leftjoin('identity_'.$identity_table_name,$identity_table_name.'.identity_id','identity_'.$identity_table_name.'.identity_id')->where($identity_table_name.'_id','=',$translationApprovalDetails->approval_staff_id)->first();
                $actionByValue=$actionByValue->identity_name;
            }else{
                $actionByValue='';
            }
            if($translationApprovalDetails->approval_time != 0){
                $updatedTime=json_decode(PermissionTrait::covertToLocalTz($translationApprovalDetails->approval_time));
                $updatedDateTime=$updatedTime->date." ".$updatedTime->time;
            } else {
                $updatedDateTime='';
            }
            $datetime = json_decode(PermissionTrait::covertToLocalTz($translationApprovalDetails->request_time));
    
            $translationApprovalCurrentDetailList[]=array('translationListValue'=>$translationListValue,
                'Fields'=>$translationApprovalDetails->request_fields,
                'updatedAt'=>$updatedDateTime,
                'request_date'=>$datetime->date." ".$datetime->time,
                'translationGroupId'=>$translationApprovalDetails->approval_grouphash,
                'actionByValue'=>$actionByValue,
                'updatedBy'=>$staffName,
                'commentId'=>$translationApprovalDetails->comment_id,
                'translationStatus'=>$translationApprovalDetails->approval_status_id,
                "rowStatusAction"=>$rowStatusTargetList,
                "rowStatusTargetListCount"=>count($rowStatusTargetList),
                "statusFontColor"=>$statusFontColor,
                "colorCode"=>$statusColor,
                'translation_text'=>$translationHistoryLatest->translation_text,
                "originalContent"=>$originalContent, 
                'userId'=>$this->userId,
                'approval_grouphash'=>$translationApprovalDetails->approval_grouphash,
                'History'=>$translationApprovalDetails->approval_grouphash,
                'translation_version'=>$translationApprovalDetails->translation_version,
                'approval_id'=>$translationApprovalDetails->approval_id);
      } 
        return $translationApprovalCurrentDetailList;
    }
    public function translationApprovalDetailList(Request $request) {
        $translationApprovalDetailList=array();
        $imageFilterId=$acceptRejectfilterValue=$fieldFilter=$imageCaptchaStatusValue=null;
            if($request->filterValue == 'all' || $request->filterValue == 'null'){
                $translationApprovalList=DB::table('translation_approval')->orderBy('request_date','DESC')->orderBy('approval_date','DESC')->get();
            }
            else{
                $translationApprovalList=DB::table('translation_approval')->where('approval_status_id','=',$request->filterValue)->orderBy('request_date','DESC')->orderBy('approval_date','DESC')->get();
            }    
        foreach ($translationApprovalList as $translationApprovalDetails) {
            $translationTableDetails=DB::table('translation_key')->where('key_table','=',$translationApprovalDetails->request_table_stage)->get()->first();
            $imageDetialsList=DB::table($translationApprovalDetails->request_table_stage)->select($translationApprovalDetails->request_fields)->where($translationTableDetails->key_primary,$translationApprovalDetails->approval_grouphash)->first();
                $translationApprovalDetails->request_fields;
                $originalContent=$imageDetialsList->{$translationApprovalDetails->request_fields};
                $translationListValue=$translationTableDetails->key_id;
            $statusList=DB::table('approval_status')->where('approval_status_id','=',$translationApprovalDetails->approval_status_id)->get()->first();
                        if(isset($statusList)){
                            $statusId=$statusList->approval_status_id;
                            $statusName=$statusList->approval_status_name;
                            $statusDisplay=$statusList->approval_status_display;
                            $statusColor=$statusList->approval_status_color;
                            $statusFontColor=$statusList->approval_status_font_color;
                        }
            $imageStatusValue=$translationApprovalDetails->approval_status_id;
            $rowStatusTargetList = array();
            $rowstatusList=DB::table('approval_status')
                ->distinct('approval_status.approval_status_name')
                ->select('approval_status.approval_status_id','approval_status.approval_status_name')
                ->join('approval_group_list', function($join) use($imageStatusValue) {
                      $join->on('approval_status.approval_status_id', '=', 'approval_group_list.target_approval_status_id')
                            ->where('approval_group_list.source_staff_group_id', $this->roleId)
                            ->where('approval_group_list.source_approval_status_id', $imageStatusValue);
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
                                ->where('user_id',$this->userId)
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
            }else{
                $staffGroupId=0;
            }
            if(($translationApprovalDetails->request_table_live == 'location' || $translationApprovalDetails->request_table_live == 'merchant_type_list' || $translationApprovalDetails->request_table_live == 'working_hours'))
            {
                $locationName = DB::table('location_list')
                                ->leftjoin('location_city as location_identity','location_identity.city_id','=','location_list.location_city_id')
                                ->select('list_id','location_identity.city_name as location_name')
                                ->where('list_id','=',$translationApprovalDetails->location_id)->get()->first();

                if(!empty($locationName->location_name)){
                    $locationName=$locationName->location_name;
                }else{
                    $locationName='Guest';
                }
            } else {
                $locationName = DB::table('location_list')
                                ->leftjoin('location_city as location_identity','location_identity.city_id','=','location_list.location_city_id')
                                ->select('list_id','location_identity.city_name as location_name')
                                ->where('list_id','=',$translationApprovalDetails->location_id)->get()->first();

                if(!empty($locationName->location_name)){
                    $locationName=$locationName->location_name;
                }else{
                    $locationName='Guest';
                }
            }
            $identity_details=DB::table('portal_password')
                                ->where('user_id',$translationApprovalDetails->request_staff_id)
                                ->first();
            if(!empty($identity_details->identity_table_id)){                    
            $identity_table_name = DB::table('identity_table_type')
                                ->select('table_code')
                                ->where('type_id',$identity_details->identity_table_id)
                                ->first()->table_code;
                            
            $staffName = DB::table($identity_table_name)->select('identity_name')->leftjoin('identity_'.$identity_table_name,$identity_table_name.'.identity_id','identity_'.$identity_table_name.'.identity_id')->where($identity_table_name.'_id','=',$translationApprovalDetails->request_staff_id)->first(); 
                $staffName=$staffName->identity_name;
            }else{
                $staffName='Guest';
            }
            $identity_details=DB::table('portal_password')
                                ->where('user_id',$translationApprovalDetails->approval_staff_id)
                                ->first();
            if(!empty($identity_details->identity_table_id)){                    
            $identity_table_name = DB::table('identity_table_type')
                                ->select('table_code')
                                ->where('type_id',$identity_details->identity_table_id)
                                ->first()->table_code;
            $actionByValue = DB::table($identity_table_name)->select('identity_name')->leftjoin('identity_'.$identity_table_name,$identity_table_name.'.identity_id','identity_'.$identity_table_name.'.identity_id')->where($identity_table_name.'_id','=',$translationApprovalDetails->approval_staff_id)->first();
                $actionByValue=$actionByValue->identity_name;
            }else{
                $actionByValue='';
            }
            if($translationApprovalDetails->approval_time != 0){
                $updatedTime=json_decode(PermissionTrait::covertToLocalTz($translationApprovalDetails->approval_time));
                $updatedDateTime=$updatedTime->date." ".$updatedTime->time;
            } else {
                $updatedDateTime='';
            }
           $datetime = json_decode(PermissionTrait::covertToLocalTz($translationApprovalDetails->request_time));
            if($translationApprovalDetails->language_code != ''){
                $languageCode=$translationApprovalDetails->language_code;
            }else{
                $languageCode='en_us';
            }      
            $translationApprovalDetailList[]=array('translationListValue'=>$translationListValue,
                'Fields'=>$translationApprovalDetails->request_fields,
                'updatedAt'=>$updatedDateTime,
                'request_date'=>$datetime->date." ".$datetime->time,
                'translationGroupId'=>$translationApprovalDetails->approval_grouphash,
                'actionByValue'=>$actionByValue,
                'section'=>str_replace('hase_', '', $translationApprovalDetails->request_table_live),
                'statusName'=>$statusName,
                'updatedBy'=>$staffName,
                'locationName'=>$locationName,
                'commentId'=>$translationApprovalDetails->comment_id,
                "staffGroupId"=>$staffGroupId,
                'historyId'=>$translationApprovalDetails->approval_id,
                'translationStatus'=>$translationApprovalDetails->approval_status_id,
                "rowStatusAction"=>$rowStatusTargetList,
                "rowStatusTargetListCount"=>count($rowStatusTargetList),
                "statusFontColor"=>$statusFontColor,
                "colorCode"=>$statusColor,
                'translation_text'=>$translationApprovalDetails->translation_text,
                "originalContent"=>$originalContent, 
                'userId'=>$this->userId,
                'languageCode'=>$languageCode,
                'approval_grouphash'=>$translationApprovalDetails->approval_grouphash,
                'History'=>$translationApprovalDetails->approval_grouphash,
                'translation_version'=>$translationApprovalDetails->translation_version,
                'approval_id'=>$translationApprovalDetails->approval_id);
      } 
        return $translationApprovalDetailList;
    }
 public function imageWordQueue(request $request) {
       $translationQueueRandomValue=$request->queueRandomValue;
       $randomValueDynamic=DB::table('translation_manage')->where('translator_user_id','=',$this->userId)->count();
        $randomValueDynamicList=DB::table('translation_manage')
        ->leftjoin('translation_key','translation_key.key_id','=','translation_manage.manage_table_id')
        ->where('manage_id','=',$translationQueueRandomValue)->first();
         
        if (isset($randomValueDynamicList)){
            $randomValueDynamicName=$randomValueDynamicList->key_table;
        }else{
        
            $randomValueDynamicList=DB::table('translation_manage')
            ->leftjoin('translation_key','translation_key.key_id','=','translation_manage.manage_table_id')
            ->where('translator_user_id','=',$this->userId)->first();
        
            if (isset($randomValueDynamicList)){
                $randomValueDynamicName=$randomValueDynamicList->key_table;
            }else{
                $randomValueDynamicName='';
            }
        }

           if($randomValueDynamicName != ''){
           $translationTableDetails=DB::table('translation_key')->where('key_table','=',$randomValueDynamicName)->get()->first();
           $baseUrl=dirname(URL::to('/'));
           $tabIndexValue='';
           $translationQueueRandomValue=$request->queueRandomValue;
           $userId=$this->userId;
           $userLanguageListData=array();
           $userLanguageList=DB::table('identity_language_list')
                      ->leftjoin('languages','languages.language_id','=','identity_language_list.language_id')
                      ->where('identity_id','=',$this->staffId)
                      ->where('identity_table_id','=',$this->identity_table_id)->get(); 
            foreach ($userLanguageList as $userLanguagevalue) {
                $userLanguageListData[]=array('language_code'=>$userLanguagevalue->language_code,'language_name'=>$userLanguagevalue->language_name);
            }
            if(isset($userLanguageList[0])){
                $languageValue=1;
                foreach ($userLanguageList as $userLanguagevalue) {
                    $userLanguageListData[]=array('language_code'=>$userLanguagevalue->language_code,'language_name'=>$userLanguagevalue->language_name);
                }
            }else {
                $languageValue=0;
            }  
            $imageWordQueueListData=$imageQueueListData=$wordQueueListData=$imageData=array();
            $translationTableDetails=DB::table('translation_key')->where('key_table','=',$randomValueDynamicName)->first();
            $approval_status=DB::table('approval_status')->where('approval_status_name','=','Accepted')->first();
            if($randomValueDynamicName=='regex_result_pattern')
                {
                $translationQueueListData=DB::table('regex_result_pattern')
             ->leftjoin('identity_table_type as identity_table_type','identity_table_type.type_id','=','regex_result_pattern.identity_table_id')
             ->leftjoin('regex_pattern as regex_pattern','regex_pattern.pattern_id','=','regex_result_pattern.pattern_id')->select('regex_result_pattern.*','identity_table_type.table_code as table_code','regex_pattern.pattern as tupleregex')/*->where('translation_status','!=', $approval_status->approval_status_id)*/->limit('9')->get();
            }else{
                $translationQueueListData=DB::table($randomValueDynamicName)->where('translation_queue_status','=',0)/*->where('translation_status','!=', $approval_status->approval_status_id)*/->inRandomOrder()->limit(9)->get();
            }
            if(isset($translationQueueListData[0])){
                $tabIndexValue=1;
                    for ($wordStart=0;$wordStart<count($translationQueueListData);$wordStart++)
                    {
                        $sessionWordList[]=$translationQueueListData[$wordStart]->{$translationTableDetails->key_primary};
                            DB::table($randomValueDynamicName)->where($translationTableDetails->key_primary,'=',$translationQueueListData[$wordStart]->{$translationTableDetails->key_primary})->update(array('translation_queue_status'=>1));
                    }
                    $translationDynamicId=$translationTableDetails->key_id;
                    if (isset($sessionWordList)){
                        Session::put('randomlyQueueList',$sessionWordList);
                        Session::put('randomlyQueueListValue',$translationDynamicId);
                    }
                    $queueRandomValue=1;
                    $newArray = array();
                    foreach ($translationQueueListData as $value) { 
                        $newArray[$value->{$translationTableDetails->field_show}] = $value; 
                    }
                   $keyPrimary=$translationTableDetails->key_primary;
                   $fieldShow=$translationTableDetails->field_show;
                   $imageWordTranslationQueue=$newArray;               
                   if($keyPrimary=='result_id'){
                        foreach ($translationQueueListData as $value) {
                        $eventUrl=$value->result_text; 
                            $newArray[$eventUrl]= $value; 
                        }
                       $keyPrimary=$translationTableDetails->key_primary;
                       $fieldShow=$translationTableDetails->field_show;
                       $imageWordTranslationQueue=$newArray;
                       }
                   return view('hase_translation.image_word_translation_queue',compact('title','baseUrl','languageValue','userLanguageListData','queueRandomValue','imageWordTranslationQueue','tabIndexValue','randomValueDynamic','translationDynamicId','keyPrimary','fieldShow'));
                }    
                else{
                     return redirect('hase_translation_queue');
                }
            }else {
                Session::flash('type', 'success'); 
                Session::flash('msg', 'You are not authorized to use Translation Queue functionality!');
                return redirect('hase_translation_list');
            }
    }
    public function imageWordStatusUpdate(){
        $randomlyQueueList=Session::get('randomlyQueueList');
        $randomlyQueueListValue=Session::get('randomlyQueueListValue');
        $translationTableDetails=DB::table('translation_key')->where('key_id','=',$randomlyQueueListValue)->first();
        if(isset($randomlyQueueList)){
            foreach ($randomlyQueueList as $randomlyQueueListValue) {
                DB::table($translationTableDetails->key_table)->where($translationTableDetails->key_primary,'=',$randomlyQueueListValue)->update(array('translation_queue_status'=>0));
            }
        }
        
    }
    public function updateImageWordText(request $request){
        $translationQueueValue=$request->translationQueueValue;
        $languageName=$request->userKnownLanguage;
        if ($languageName == 'Select Language'){
                    $translateLanguage=DB::table('identity_language_list')
                      ->leftjoin('languages','languages.language_id','=','identity_language_list.language_id')
                      ->where('identity_id','=',$this->staffId)
                      ->where('identity_table_id','=',$this->identity_table_id)->first(); 
                    if($translateLanguage->language_code != ''){
                        $translatedLanguageCode=$translateLanguage->language_code;
                    }else{
                        $translatedLanguageCode="en_us";
                    }
        }elseif ($languageName == '') {
            $translatedLanguageCode="en_us";
        }
        else{
            $translatedLanguageCode=$languageName;
        }    
        $imageWordQueueTranslationData=$_POST;
        $imageDate = date('Ymd');
        $imageTime = time();
        $translationTableDetails=DB::table('translation_key')->where('key_id','=',$translationQueueValue)->get()->first();
        foreach ($imageWordQueueTranslationData as $queueImagesId=>$queueImageValue) {
                if ($queueImageValue!='' && $queueImagesId !='submitBtn' && $queueImagesId != '_token' && $queueImagesId != 'userKnownLanguage' && $queueImagesId != 'translationQueueValue' ) {
                        $translationHistoryList=DB::table('translation_approval')->where('approval_grouphash','=',$queueImagesId)->get();
                        $translationHistoryCount=count($translationHistoryList);
                        $translationVersion=$translationHistoryCount+1;
                        $timeZoneId = PermissionTrait::getTimeZoneId();
                        $timeZoneDetails=DB::table('timezone')->where('timezone_id','=',$timeZoneId)->first();
                        $approvalId=DB::table('translation_approval')->insertGetId(array(
                            'request_time'=>$imageTime,
                            'request_date'=>$imageDate,
                            'request_staff_id'=>$this->userId,
                            'request_user_id'=>$this->userId,
                            'location_id'=>$this->locationId,
                            'request_table_live'=>str_replace('_stage', '', $translationTableDetails->key_table),
                            'request_table_stage'=>$translationTableDetails->key_table,
                            'request_fields'=>$translationTableDetails->field_show,
                            'approval_grouphash'=>$queueImagesId,
                            'translation_text'=>$queueImageValue,
                            'approval_status_id'=>1,
                            'language_code'=>$translatedLanguageCode,
                            'translation_version'=>$translationVersion,
                            'time_zone'=>$timeZoneDetails->timezone_name));

                    $tableDetails=DB::table('identity_table_type')->where('table_code','=',$translationTableDetails->key_table)->first();
                    DB::table('translation_reference')->insert(array(
                            'approval_id'=>$approvalId,
                            'identity_table_id'=>$tableDetails->type_id,
                            'identity_id'=>$approvalId,
                            'previous_time'=>$imageTime,
                            'previous_date'=>$imageDate));
                   if($translationTableDetails->key_table == 'image_translation_stage'){
                    DB::table('image_translation_stage')->where('id',"=",$queueImagesId)->update(array('image_text'=>$queueImageValue,'captcha_status'=>1,'date_time'=>date('Y-m-d H:i:s'))); 
                   } 
                }
            }
        return redirect('hase_translation_queue');
    }
    public function updateImageTranslationComments(Request $request){ 
        $comment_Date = date('Ymd');
        $comment_time = time();
        $comment_translation_id= DB::table('translation_approval')->where('approval_id','=',$request->historyId)->get()->first();

        if(!isset($comment_translation_id->comment_id)){
            $comment_id = DB::table('approval_comment')->insertGetId(
            ['comment'=>$request->approveRejectComments,'comment_date'=>$comment_Date,'comment_time'=>$comment_time,'comment_parent_id'=>'','comment_source_id'=>$this->roleId]);
            DB::table('translation_approval')->where('approval_id','=',$request->historyId)->update(array('comment_id'=>$comment_id));
            DB::table('approval_comment')->where('comment_id','=',$comment_id)->update(array('comment_root_id'=>$comment_id));
        }else{
            $comment_id = DB::table('approval_comment')->insertGetId(
            ['comment'=>$request->approveRejectComments,'comment_date'=>$comment_Date,'comment_time'=>$comment_time,'comment_parent_id'=>$comment_translation_id->comment_id,'comment_source_id'=>$this->roleId,'comment_root_id'=>$comment_translation_id->comment_id]);  
        }
    }
    public function updateImageTranslationStatus(request $request){
        if(isset($request)){
            $request = (object)$_GET;
        }
        $statusList = $this->TranslationActionStatusTransitionToAnotherDB($request->staffGroupId, $request->statusId);
        $languageDetailsList=DB::table('translation_approval')->where('approval_id','=',$request->imageId)->first();

        $languageCode=$languageDetailsList->language_code;
        
        $statusError = DB::table('approval_status')
            ->select('approval_status_id')
            ->where('approval_status.approval_status_code','=','error')
            ->get()->first();

        if(isset($statusList)) {
            $statusCode=$statusList->approval_status_code;
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
                $approvalDate =date('Ymd');
                $approvalTime = time();
                $translationListValue=$request->translationListValue;
                 $translationTableDetails=DB::table('translation_key')->where('key_id','=',$translationListValue)->first();
                DB::table($translationTableDetails->key_table)->where($translationTableDetails->key_primary,'=',$request->translationGroupId)->update(array('translation_status'=>$statusId));
                $approvalSectionList=DB::table('translation_approval')->where('approval_id','=',$request->imageId)->where('request_fields','=',$translationTableDetails->field_show)->first();
                 DB::table('translation_approval')->where('approval_id','=',$request->imageId)->update(array('approval_status_id'=>$statusId,'approval_staff_id'=>$this->userId,'approval_date'=>$approvalDate,'approval_time'=>$approvalTime));

                if($statusCode == 'accepted'){
                    $this->updateTranslationStageToLive($request->translationGroupId,$translationListValue,$request->imageId);
                }
            }
        }

    }
    public function updateTranslationStageToLive($approvalId,$translationListValue,$translationId){
        $approvalDate =date('Ymd');
        $approvalTime = time();
        $statusList=DB::table('approval_status')->where('approval_status_name','=','Rejected')->first();
        $statusId=$statusList->approval_status_id;
        $translationTableDetails=DB::table('translation_key')->where('key_id','=',$translationListValue)->first();   
        if($translationTableDetails->key_table == 'image_translation_stage'){
        $imageDetails=DB::table('image_translation_stage')->where('id','=',$approvalId)->first();
        $imageDetailsList=DB::table('image_translation')->where('approval_translation_id','=',$approvalId)->first();
        $traslationDetails=DB::table('translation_approval')->where('approval_id','=',$translationId)->first();
        if(isset($imageDetailsList->approval_translation_id)){
            DB::table('translation_approval')->where('translation_text','=',$imageDetailsList->translation_text)->where('request_table_stage','=','image_translation_stage')->update(array('approval_status_id'=>$statusId));
            DB::table('image_translation')->where('approval_translation_id','=',$approvalId)->update(array('translation_text'=>$traslationDetails->translation_text,'approved_date'=>$approvalDate,'approved_time'=>$approvalTime));
        }else{
            DB::table('image_translation')->insert(array(
                            'approval_translation_id'=>$approvalId,
                            'image_url'=>$imageDetails->image_url,
                            'approved_date'=>$approvalDate,
                            'approved_time'=>$approvalTime,
                            'user_id'=>$this->userId,
                            'translation_text'=>$traslationDetails->translation_text));
        }
    }else if($translationTableDetails->key_table == 'communication_stage'){
        $communicationDetails=DB::table('communication_stage')->where('communication_id','=',$approvalId)->get()->first();
        $communicationDetailsList=DB::table('communication')->where('approval_translation_id','=',$approvalId)->first();
        $communicationTranslationDetailsList=DB::table('translation_approval')->where('approval_id','=',$translationId)->first();
        if(isset($communicationDetailsList->approval_translation_id)){
            DB::table('translation_approval')->where('translation_text','=',$communicationDetailsList->translation_text)->where('request_table_stage','=','communication_stage')->update(array('approval_status_id'=>$statusId));
            DB::table('communication')->where('approval_translation_id','=',$approvalId)->update(array('communications_translation_text'=>$communicationTranslationDetailsList->translation_text,'communications_date'=>$approvalDate,'communications_time'=>$approvalTime));
        }else{
            DB::table('communication')->insert(array(
                            'approval_translation_id'=>$approvalId,
                            'communications_date'=>$approvalDate,
                            'communications_time'=>$approvalTime,
                            'user_id'=>$this->userId,
                            'communications_text'=>$communicationDetails->communication_text,
                            'communications_translation_text'=>$communicationTranslationDetailsList->translation_text));
        }
    }else if($translationTableDetails->key_table == 'word_translation_stage'){
        $wordDetails=DB::table('word_translation_stage')->where('or_id','=',$approvalId)->get()->first();
        $wordDetailsList=DB::table('word_translation_stage')->where('translation_status','=',$approvalId)->first();
        $translationDetailsList=DB::table('word_translation')->where('approval_translation_id','=',$approvalId)->first();
        $wordTranslationDetailsList=DB::table('translation_approval')->where('approval_id','=',$translationId)->first();
        if(isset($translationDetailsList->approval_translation_id)){
            DB::table('translation_approval')->where('translation_text','=',$translationDetailsList->translation_text)->where('request_table_stage','=','word_translation_stage')->update(array('approval_status_id'=>$statusId));
            DB::table('word_translation')->where('approval_translation_id','=',$approvalId)->update(array('translation_text'=>$wordTranslationDetailsList->translation_text,'approved_date'=>$approvalDate,'approved_time'=>$approvalTime));
        }else{
            DB::table('word_translation')->insert(array(
                            'approval_translation_id'=>$approvalId,
                            'approved_date'=>$approvalDate,
                            'approved_time'=>$approvalTime,
                            'user_id'=>$this->userId,
                            'original_word'=>$wordDetails->original_word,
                            'translation_text'=>$wordTranslationDetailsList->translation_text));
        }
        $wordDetailsList=DB::table('word_translation_stage')->where('or_id','=',$approvalId)->get()->first();
        $wordTranslationDetailsList=DB::table('translation_approval')->where('approval_id','=',$translationId)->get()->first();
        $updateAcceptSentenceList=array('languageCode'=>$wordDetailsList->language_code,'originalContent'=>$wordDetailsList->original_word,'newTranslationText'=>$wordTranslationDetailsList->translation_text);
                $this->translationCurlUrlAction(dirname(URL::to('/')).'/tiki-language-translate/AddUpdateTranslation.php?callFunction=updateRejectRevertOldWord',$updateAcceptSentenceList);  
    }else{
        $regex_result_pattern=DB::table('regex_result_pattern')->where('result_id','=',$approvalId)->get()->first();
        $regex_result_pattern_list=DB::table('event_url_detail_result')->where('approval_translation_id','=',$approvalId)->get()->first();
        $regex_result_pattern_list_detail=DB::table('translation_approval')->where('approval_id','=',$translationId)->get()->first();
        if(isset($regex_result_pattern_list->approval_translation_id)){
            DB::table('translation_approval')->where('translation_text','=',$regex_result_pattern_list->translation_text)->where('request_table_stage','=','regex_result_pattern')->update(array('approval_status_id'=>$statusId));
            DB::table('event_url_detail_result')->where('approval_translation_id','=',$approvalId)->update(array('events_translation_text'=>$regex_result_pattern_list_detail->translation_text,'approved_date'=>$approvalDate,'approved_time'=>$approvalTime));
        }else{
            DB::table('event_url_detail_result')->insert(array(
                            'approval_translation_id'=>$approvalId,
                            'approved_date'=>$approvalDate,
                            'approved_time'=>$approvalTime,
                            'user_id'=>$this->userId,
                            'events_value'=>$regex_result_pattern->result_text,
                            'events_translation_text'=>$regex_result_pattern_list_detail->translation_text));
        }

    }
    }
}