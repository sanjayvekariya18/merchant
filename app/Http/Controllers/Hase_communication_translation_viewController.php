<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\App;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use URL;
use Session;
use DB;
use Auth;
use DateTime;
use Carbon\Carbon;
use App\Http\Traits\PermissionTrait;
use Redirect;
use PDO;

class Hase_communication_translation_viewController extends Controller
{   
    
   public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            $this->merchantId = session()->has('merchantId') ? session()->get('merchantId') :"";
            $this->locationId = session()->has( 'locationId' ) ? session()->get( 'locationId' ) : '';
            $this->roleId = session()->has('role') ? session()->get('role') :"";
            $this->staffId = session()->has('staffId') ? session()->get('staffId') :"";
            $this->staffName = session()->has('staffName') ? session()->get('staffName') :"";
            $this->userId = session()->has('userId') ? session()->get('userId') :"";
        return $next($request);
        });
    }
    use PermissionTrait;
    public function chatbotCommunicationView(){
        if($this->permissionDetails('Hase_chatbot_communication_view','access')){
        $hase_communication_translaton_status_filter=array();
        $communicationTranslatedList=array();
        $userStatusListArray=$this->userCommunicationsStatusViewList();
        $communicationTranslatedList=DB::table('communication_stage')/*->whereIn('translation_status',$userStatusListArray)*/->get();
        $statusList=null;
        $commaSeparators="";
        if(isset($communicationTranslatedList)){
        foreach ($communicationTranslatedList as $communicationTranslatedListValue) {
                $statusId=$communicationTranslatedListValue->translation_status;
                $statusList.=$commaSeparators.$statusId;
                $commaSeparators =',';
        }
            $statusValue=explode(",", $statusList);
            $statusListArray =$statusValue;
            $hase_communication_translaton_status_filter=DB::table('approval_status')
                ->distinct('approval_status_code')
                ->groupBy('approval_status_id')
                ->whereIn('approval_status_id',$statusListArray)    
                ->where('approval_status_display',">",'0')
                ->where('approval_status_code',"!=",'noop')
                ->get();
                } 
        return view('hase_translation.chatbot_communication_translation_list',compact('title','hase_communication_translaton_status_filter'));
        }else{
            return Redirect::back()->with('message', 'You are not authorized to use this functionality!');
        }
    }
    public function communicationApprovalView(){
        if($this->permissionDetails('Hase_communication_approval_list','access')){
         $haseCommunicationApprovalList = DB::table('communication') ->join('portal_password as portal_password ','communication.user_id','=','portal_password.user_id')->select('communication.*','portal_password.username as user_id')->get();
            return view('hase_translation.communication_approval_list_view',compact('title','haseCommunicationApprovalList'));
        }else{
            return Redirect::back()->with('message', 'You are not authorized to use this functionality!');
        }
    }
    public function getCommunicationApprovalLists(Request $request){
        $haseCommunicationApprovalList = DB::table('communication') ->join('portal_password as portal_password ','communication.user_id','=','portal_password.user_id')->select('communication.*','portal_password.username as user_id')->get();
                foreach ($haseCommunicationApprovalList as $key=>$communicationApprovalList){        
                    $datetime = json_decode(PermissionTrait::covertToLocalTz($communicationApprovalList->communications_time));
                    $haseCommunicationApprovalList[$key]->approved_date=$datetime->date;
                    $haseCommunicationApprovalList[$key]->approved_time=$datetime->time;
                }
                $communicationApprovalList = $haseCommunicationApprovalList->toArray();
        return $communicationApprovalList;
    }
    public function translationLanguageDifference() {
         return view('hase_translation.chatbot_communication_translation_differences',compact('title')); 
    }
    public function chatbotCommunicationLanguageView(){
        $hase_communication_translaton_status_filter=array();
        $communicationTranslatedList=array();
        if(!isset($_COOKIE["languageCode"])){
            $languageCode="en_us";
        }else{
            $languageCode=$_COOKIE["languageCode"];
        }
        $languageDetails=DB::table('languages')->where('language_code','=',$languageCode)->first();

        $userStatusListArray=$this->userCommunicationsStatusViewList();

        $communicationTranslatedList=DB::table('communication_stage')/*->whereIn('translation_status',$userStatusListArray)*/->where('language_id','=',$languageDetails->language_id)->orderBy('communication_date','DESC')->get();

        $statusList=null;
        $commaSeparators="";
        if(isset($communicationTranslatedList)){
        foreach ($communicationTranslatedList as $communicationTranslatedListValue) {
                $statusId=$communicationTranslatedListValue->translation_status;
                $statusList.=$commaSeparators.$statusId;
                $commaSeparators =',';
        }
            $statusValue=explode(",", $statusList);
            $statusListArray =$statusValue;
            $hase_communication_translaton_status_filter=DB::table('approval_status')
                ->distinct('approval_status_code')
                ->groupBy('approval_status_id')
                ->whereIn('approval_status_id',$statusListArray)    
                ->where('approval_status_display',">",'0')
                ->where('approval_status_code',"!=",'noop')
                ->get();
                } 
        return view('hase_translation.chatbot_communication_translation_language_list',compact('title','hase_communication_translaton_status_filter'));
    }

    public function communicationDetailsList(Request $request){
        $communicationDescription=$communicationDetailList=array();
        $statusListArray=$this->userCommunicationsStatusViewList();

            if($request->filterValue == 'all' || $request->filterValue == 'null'){
                $communicationDescription=DB::table('communication_stage')->join('communication_topic','communication_stage.communication_topic_id','=','communication_topic.topic_id')/*->whereIn('translation_status',$statusListArray)*/->orderBy('communication_date','DESC')->get();
            }
            else{
                $communicationDescription=DB::table('communication_stage')->join('communication_topic','communication_stage.communication_topic_id','=','communication_topic.topic_id')->where('translation_status','=',$request->filterValue)/*->whereIn('translation_status',$statusListArray)*/->orderBy('communication_date','DESC')->get();
            }     

        foreach ($communicationDescription as $communicationDetails) {
            $communicationTranslationDetails=DB::table('translation_approval')->where('approval_grouphash','=',$communicationDetails->communication_id)->where('request_table_stage','=','communication_stage')->first();
            if(isset($communicationTranslationDetails->approval_grouphash)){
                $translationHistoryId=1;
            }else{
                $translationHistoryId=0;
            }
            $statusList=DB::table('approval_status')->where('approval_status_id','=',$communicationDetails->translation_status)->get()->first();
            if(isset($statusList)){
                $statusId=$statusList->approval_status_id;
                $statusName=$statusList->approval_status_name;
                $statusDisplay=$statusList->approval_status_display;
                $statusColor=$statusList->approval_status_color;
                $statusFontColor=$statusList->approval_status_font_color;
            }
            if($statusName != 'Accepted'){
                    $editableStatus=false;
                }elseif ($statusName == 'Accepted' && $this->userId=='1') {
                    $editableStatus=false;
                }else{
                 $editableStatus=true;          
                }
            $communicationDetailList[]=array('editableStatus'=>$editableStatus,"translationHistoryId"=>$translationHistoryId,"statusFontColor"=>$statusFontColor,"colorCode"=>$statusColor,'communicationId'=>$communicationDetails->communication_id,'userId'=>$this->roleId,'communicationText'=>$communicationDetails->communication_text,'communicationsTopic'=>$communicationDetails->topic_name,'currentStatus'=>$statusName);
        }
     
        return $communicationDetailList;    
    }
    public function communicationLanguageDetailsList(Request $request){
        $communicationDescription=$communicationDetailList=array();
        if(!isset($_COOKIE["languageCode"])){
            $languageCode="en_us";
        }else{
            $languageCode=$_COOKIE["languageCode"];
        }
        $languageDetails=DB::table('languages')->where('language_code','=',$languageCode)->first();
        $statusListArray=$this->userCommunicationsStatusViewList();

            if($request->filterValue == 'all' || $request->filterValue == 'null'){
                $communicationDescription=DB::table('communication_stage')->join('communication_topic','communication_stage.communication_topic_id','=','communication_topic.topic_id')->where('language_id','=',$languageDetails->language_id)/*->whereIn('translation_status',$statusListArray)*/->orderBy('communication_date','DESC')->get();
            }
            else{
                $communicationDescription=DB::table('communication_stage')->join('communication_topic','communication_stage.communication_topic_id','=','communication_topic.topic_id')->where('language_id','=',$languageDetails->language_id)->where('translation_status','=',$request->filterValue)/*->whereIn('translation_status',$statusListArray)*/->orderBy('communication_date','DESC')->get();
            }     
        foreach ($communicationDescription as $communicationDetails) {
            $communicationTranslationDetails=DB::table('translation_approval')->where('approval_grouphash','=',$communicationDetails->communication_id)->where('request_table_stage','=','communication')->first();
            if(isset($communicationTranslationDetails->approval_grouphash)){
                $translationHistoryId=1;
            }else{
                $translationHistoryId=0;
            }

            $statusList=DB::table('approval_status')->where('approval_status_id','=',$communicationDetails->translation_status)->get()->first();

            if(isset($statusList)){
                $statusId=$statusList->approval_status_id;
                $statusName=$statusList->approval_status_name;
                $statusDisplay=$statusList->approval_status_display;
                $statusColor=$statusList->approval_status_color;
                $statusFontColor=$statusList->approval_status_font_color;
            }
            $communicationDetailList[]=array("translationHistoryId"=>$translationHistoryId,"statusFontColor"=>$statusFontColor,"colorCode"=>$statusColor,'communicationId'=>$communicationDetails->communication_id,'userId'=>$this->roleId,'communicationText'=>$communicationDetails->communication_text,'communicationsTopic'=>$communicationDetails->topic_name,'currentStatus'=>$statusName);
    
      } 
        return $communicationDetailList;    
    }
    public function userCommunicationsStatusViewList(){
                $userStatusViewList=DB::table('translation_status_view_manage')->where('status_target',"=",$this->staffId)->where('manage_table',"=",'communication')->get();
                $userStatusList=null;
                $commaSeparators="";
                if(isset($userStatusViewList)){
                    foreach ($userStatusViewList as $userStatusViewListValue) {
                            $statusId=$userStatusViewListValue->user_view_status;
                            $userStatusList.=$commaSeparators.$statusId;
                            $commaSeparators =',';
                    }
                }
                $statusValue=explode(",", $userStatusList);
                return $statusListArray =$statusValue;
    }
    public function communicationDiffrenceDetailList(Request $request){
             $oldVersionNumber=$request->oldVersionNumber;
             $newVersionNumber=$request->newVersionNumber;
             $originalId=$request->originalId; 
             $secondOriginalId=$request->secondOriginalId;
             $firstOriginalId=$request->firstOriginalId;
             if(isset($firstOriginalId)) {
                 $translationlanguageOldDifference=DB::table('translation_approval')->where('translation_version','=',$oldVersionNumber)->where('approval_id','=',$firstOriginalId)->first();
                  $translationlanguageNewDifference=DB::table('translation_approval')->where('translation_version','=',$newVersionNumber)->where('approval_id','=',$secondOriginalId)->first();
             } else{
                $translationlanguageOldDifference=DB::table('translation_approval')->where('translation_version','=',$oldVersionNumber)->where('approval_grouphash','=',$originalId)->first();
                  $translationlanguageNewDifference=DB::table('translation_approval')->where('translation_version','=',$newVersionNumber)->where('approval_grouphash','=',$originalId)->first();
             }         
          
            $translationHistoryOldDifference=$translationlanguageOldDifference->translation_text;
            $translationHistoryNewDifference=$translationlanguageNewDifference->translation_text;
            $fromStart = strspn($translationHistoryOldDifference ^ $translationHistoryNewDifference, "\0");        
            $fromEnd = strspn(strrev($translationHistoryOldDifference) ^ strrev($translationHistoryNewDifference), "\0");
            $oldEnd = strlen($translationHistoryOldDifference) - $fromEnd;
            $newEnd = strlen($translationHistoryNewDifference) - $fromEnd;
            $sentenceStart = substr($translationHistoryNewDifference, 0, $fromStart);
            $sentencesEnd = substr($translationHistoryNewDifference, $newEnd);
            $newDifferences = substr($translationHistoryNewDifference, $fromStart, $newEnd - $fromStart);  
            $oldDifferences = substr($translationHistoryOldDifference, $fromStart, $oldEnd - $fromStart);

            $translationHistoryNewDifference = "$sentenceStart<span style='color:red'>$newDifferences</span>$sentencesEnd";
            $translationHistoryOldDifference = "$sentenceStart<span style='color:blue'>$oldDifferences</span>$sentencesEnd";
            $translationHistoryDifferenceValues=array('oldDataRevision'=>$translationHistoryNewDifference,'newDataRevision'=>$translationHistoryOldDifference);
            return json_encode($translationHistoryDifferenceValues);

    }
    public function communicationTranslationHistory(Request $request){
        $communicationTranslationHistoryList=array();
        $communicationId=$request->communicationId;
        $communicationTranslationDetails=DB::table('translation_approval')->where('approval_grouphash','=',$request->communicationId)->where('request_table_stage','=','communication_stage')->get();
        foreach ($communicationTranslationDetails as $communicationTranslationDetailsValue) {
            $communicationStatus=$communicationTranslationDetailsValue->approval_status_id;
            if($communicationTranslationDetailsValue->language_code != ''){
                    $languageDetails=DB::table('languages')->where('language_code','=',$communicationTranslationDetailsValue->language_code)->first();
                    $languageName=$languageDetails->name;
                }else{
                    $languageName='';
                }
            $statusList=DB::table('approval_status')->where('approval_status_id','=',$communicationStatus)->get()->first();
                        if(isset($statusList)){
                            $statusId=$statusList->approval_status_id;
                            $statusName=$statusList->approval_status_name;
                            $statusDisplay=$statusList->approval_status_display;
                            $statusColor=$statusList->approval_status_color;
                            $statusFontColor=$statusList->approval_status_font_color;
                        }
            $userDetailsList=DB::table('portal_password')->where('user_id','=',$communicationTranslationDetailsValue->request_user_id)->get()->first();
            $datetime = json_decode(PermissionTrait::covertToLocalTz($communicationTranslationDetailsValue->request_time));
            $communicationTranslationHistoryList[]=array('translationVersion'=>$communicationTranslationDetailsValue->translation_version,'languageName'=>$languageName,'translationId'=>$communicationTranslationDetailsValue->approval_id,"statusFontColor"=>$statusFontColor,"colorCode"=>$statusColor,'communicationStatusName'=>$statusName,'communicationTranslationDate'=>$datetime->date." ".$datetime->time,'userName'=>$userDetailsList->username,'communicationTranslationHistory'=>$communicationTranslationDetailsValue->translation_text);
        }
        return $communicationTranslationHistoryList;

    }
    public function communicationTranslationLanguageHistory(Request $request){
        if(!isset($_COOKIE["languageCode"])){
            $languageCode="en_us";
        }else{
            $languageCode=$_COOKIE["languageCode"];
        }
        $communicationTranslationLanguageHistoryList=array();
        $communicationId=$request->communicationId;
        $communicationTranslationDetails=DB::table('translation_approval')->where('approval_grouphash','=',$request->communicationId)->where('request_table_stage','=','communication')->get();
        foreach ($communicationTranslationDetails as $communicationTranslationDetailsValue) {
            $communicationStatus=$communicationTranslationDetailsValue->approval_status_id;
            if($communicationTranslationDetailsValue->language_code != ''){
                    $languageDetails=DB::table('languages')->where('language_code','=',$communicationTranslationDetailsValue->language_code)->first();
                    $languageName=$languageDetails->language_name;
                }else{
                    $languageName='';
                }
            $statusList=DB::table('approval_status')->where('approval_status_id','=',$communicationStatus)->get()->first();
                        if(isset($statusList)){
                            $statusId=$statusList->approval_status_id;
                            $statusName=$statusList->approval_status_name;
                            $statusDisplay=$statusList->approval_status_display;
                            $statusColor=$statusList->approval_status_color;
                            $statusFontColor=$statusList->approval_status_font_color;
                        }
            $userDetailsList=DB::table('portal_password')->where('user_id','=',$communicationTranslationDetailsValue->request_user_id)->get()->first();
            $datetime = json_decode(PermissionTrait::covertToLocalTz($communicationTranslationDetailsValue->request_time));
            $communicationTranslationLanguageHistoryList[]=array('languageName'=>$languageName,'translationId'=>$communicationTranslationDetailsValue->approval_id,"statusFontColor"=>$statusFontColor,"colorCode"=>$statusColor,'communicationStatusName'=>$statusName,'communicationTranslationDate'=>$datetime->date." ".$datetime->time,'userName'=>$userDetailsList->username,'communicationTranslationHistory'=>$communicationTranslationDetailsValue->translation_text);
        }
        return $communicationTranslationLanguageHistoryList;
    }
    public function updateCommunicationDetails(Request $request){
        $userId=$this->roleId;
        $updateWordText=$request->original_word;
        $updateTranslatedWord=$request->communicationText;
        $communicationId=$request->communicationId;
        $imageDate = date('Ymd');
        $imageTime = time();
        $translationHistoryList=DB::table('translation_approval')->where('approval_grouphash','=',$communicationId)->get();
        $translationHistoryCount=count($translationHistoryList);
        $translationVersion=$translationHistoryCount+1;
        $approvalId=DB::table('translation_approval')->insertGetId(array(
                            'request_time'=>$imageTime,
                            'request_date'=>$imageDate,
                            'request_staff_id'=>$this->userId,
                            'request_user_id'=>$this->roleId,
                            'location_id'=>$this->locationId,
                            'request_table_live'=>'communication',
                            'request_table_stage'=>'communication_stage',
                            'request_fields'=>'communication_text',
                            'approval_grouphash'=>$communicationId,
                            'translation_text'=>$updateTranslatedWord,'approval_status_id'=>1,
                            'language_code'=>'',
                            'translation_version'=>$translationVersion));

        $tableDetails=DB::table('identity_table_type')->where('table_code','=','communication_stage')->first();
        DB::table('translation_reference')->insert(array(
                            'approval_id'=>$approvalId,
                            'identity_table_id'=>$tableDetails->type_id,
                            'identity_id'=>$approvalId,
                            'previous_time'=>$imageTime,
                            'previous_date'=>$imageDate));
    }
}