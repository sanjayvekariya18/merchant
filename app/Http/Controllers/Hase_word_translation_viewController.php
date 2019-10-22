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
define('CURL_PATH', 'cURL/CurlWrapper.php');
use curl\ CurlWrapper;
use PDO;
require_once(CURL_PATH);
 
class Hase_word_translation_viewController extends Controller
{    
   public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
        $this->staffId = session()->has('staffId') ? session()->get('staffId') :"";
        $this->staffName = session()->has('staffName') ? session()->get('staffName') :"";
        $this->roleId = session()->has('role') ? session()->get('role') :"";
        $this->locationId = session()->has('locationId') ? session()->get('locationId') :"";
        $this->userId = session()->has('userId') ? session()->get('userId') :"";
        return $next($request);
        });
    }
    use PermissionTrait;
    public function index(Request $request){ 
        if($this->permissionDetails('Hase_translation_view','access')){
            $baseUrl=dirname(URL::to('/'));
            return view('hase_translation.word_translation',compact('title','baseUrl'));
        }else{
            return Redirect::back()->with('message', 'You are not authorized to use this functionality!');
        }
    }
    public function wordApprovalView(Request $request){
        if($this->permissionDetails('Hase_word_approval_list','access')){
        $haseWordApprovalList = DB::table('word_translation') ->join('portal_password as portal_password ','word_translation.user_id','=','portal_password.user_id')->select('word_translation.*','portal_password.username as user_id')->get();
        return view('hase_translation.word_approval_list_view',compact('title','haseWordApprovalList'));
        }else{
            return Redirect::back()->with('message', 'You are not authorized to use this functionality!');
        }
    }
    public function getWordApprovalLists(Request $request){
        $haseWordApprovalList = DB::table('word_translation') ->join('portal_password as portal_password ','word_translation.user_id','=','portal_password.user_id')->select('word_translation.*','portal_password.username as user_id')->get();
                foreach ($haseWordApprovalList as $key=>$wordApprovalList){        
                    $datetime = json_decode(PermissionTrait::covertToLocalTz($wordApprovalList->approved_time));
                    $haseWordApprovalList[$key]->approved_date=$datetime->date;
                    $haseWordApprovalList[$key]->approved_time=$datetime->time;
                }
                $wordApprovalList = $haseWordApprovalList->toArray();
        return $wordApprovalList;
    }
    public function wordsDelete($id){
         $haseImageAceess = $this->permissionDetails('Hase_translation_list','delete');
        if($haseImageAceess) {
            DB::table('word_translation_stage')->where('or_id','=',$id)->update(array('word_status'=>1));
            Session::flash('type', 'success'); 
            Session::flash('msg', 'Word Successfully Deleted');
            return redirect('hase_translation_list');
        } else {
            return Redirect::back()->with('message', 'You are not authorized to use this functionality!'); 
        }

    }   
    public function translation(Request $request){
        if($this->permissionDetails('Hase_translation_list','access')){
        $notInArray = array('NOOP');
        $hase_status = DB::table('approval_status')
            ->distinct('approval_group_list.status_target')
            ->groupBy('approval_status.approval_status_id')
            ->join('approval_group_list', 'approval_group_list.target_approval_status_id', '=', 'approval_status.approval_status_id')
            ->where('approval_status.approval_status_display',"=",'0')
            ->where('approval_group_list.source_staff_group_id','=',$this->roleId)
            ->whereNotIn('approval_status.approval_status_name',$notInArray)
            ->where('approval_status.approval_status_code','!=','comment')
            ->get();


        $statusCountList=count($hase_status);
        if(!isset($_COOKIE["languageCode"])){
            $languageCode="en_us";
        }else{
            $languageCodeDetails=$_COOKIE["languageCode"];
            $languageCodeList=DB::table('identity_language_list')
                            ->leftjoin('languages','languages.language_id','=','identity_language_list.language_id')
                            ->where('languages.language_code','=',$languageCodeDetails)
                            ->where('identity_id','=',$this->staffId)
                            ->first();

            if(!isset($languageCodeList->language_code)){
                $languageCode="en_us";
            }else{
                $languageCode=$languageCodeList->language_code;

            }
        } 
        $timeZone=date_default_timezone_get();
        $hase_translaton_status_filter=array();
        $translatedList=array();
        $userStatusListArray=$this->userWordStatusViewList();
        $translatedList=DB::table('word_translation_stage')
                        ->leftjoin('languages','languages.language_id','=','word_translation_stage.language_id')
                        /*->whereIn('translation_status',$userStatusListArray)*/
                        ->where('languages.language_code','=',$languageCode)->get();

        $statusList=null;
        $commaSeparators="";
        if(isset($translatedList)){
        foreach ($translatedList as $translatedListValue) {
                $statusId=$translatedListValue->translation_status;
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
        } 
        $username=$this->staffName;
        return view('hase_translation.word_translation_list',compact('title','statusCountList','hase_status','hase_translaton_status_filter','username'));
        }else{
            return Redirect::back()->with('message', 'You are not authorized to use this functionality!');
        }
    }
    public function translationLanguage(Request $request){ 
        $hase_translaton_status_filter=array();
        $translatedList=array();
        $userStatusListArray=$this->userWordStatusViewList();
        $translatedList=DB::table('word_translation_stage')/*->whereIn('translation_status',$userStatusListArray)*/->get();
        $statusList=null;
        $commaSeparators="";
        if(isset($translatedList)){
        foreach ($translatedList as $translatedListValue) {
                $statusId=$translatedListValue->translation_status;
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
                } 
    return view('hase_translation.word_translation_language',compact('title','hase_translaton_status_filter'));
    }   
    public function wordTranslationList(Request $request){ 
        Session::put('userTranslationPriority',0); 
        Session::put('languageCode',$request->languageCode); 
        $request->filterValue;       
        $userId=$this->roleId;
        $translatedList=$translationListData=array();
        $statusFilterId=null;
        if(!isset($_COOKIE["languageCode"])){
            $languageCode="en_us";
        }else{
            $languageCodeDetails=$_COOKIE["languageCode"];
            $languageCodeList=DB::table('identity_language_list')
                                ->join('languages','languages.language_id','=','identity_language_list.language_id')
                                ->where('languages.language_code','=',$languageCodeDetails)
                                ->where('identity_id','=',$this->staffId)
                                ->first();

            if(!isset($languageCodeList->language_code)){
                $languageCode="en_us";
            }else{
                $languageCode=$languageCodeList->language_code;

            }

        }
        $statusListArray=$this->userWordStatusViewList();
        if($this->userId == 1){
        if($request->filterValue == 'all' || $request->filterValue == 'null'){
                $translatedList=DB::table('word_translation_stage')
                ->leftjoin('languages','languages.language_id','=','word_translation_stage.language_id')
                ->where('word_translation_stage.language_code',"=",$languageCode)
                /*->whereIn('translation_status',$statusListArray)*/
                ->orderBy('date_time','DESC')->get();
            }
            else{
                $translatedList=DB::table('word_translation_stage')
                ->leftjoin('languages','languages.language_id','=','word_translation_stage.language_id')
                ->where('word_translation_stage.language_code',"=",$languageCode)
                ->where('translation_status',"=",$request->filterValue)
                /*->whereIn('translation_status',$statusListArray)*/
                ->orderBy('date_time','DESC')->get();
            }
        }
        else{
             if($request->filterValue == 'all' || $request->filterValue == 'null'){
                $translatedList=DB::table('word_translation_stage')
                ->leftjoin('languages','languages.language_id','=','word_translation_stage.language_id')
                ->where('word_translation_stage.language_code',"=",$languageCode)
                ->where('word_status','=',0)
                ->orderBy('date_time','DESC')->get();
            }
            else{
                $translatedList=DB::table('word_translation_stage')
                ->leftjoin('languages','languages.language_id','=','word_translation_stage.language_id')
                ->where('word_translation_stage.language_code',"=",$languageCode)
                ->where('translation_status',"=",$request->filterValue)
                ->where('word_status','=',0)
                ->orderBy('date_time','DESC')->get();
            }
        }
        foreach ($translatedList as $translatedListValue) {
            $statusList=DB::table('approval_status')->where('approval_status_id','=',$translatedListValue->translation_status)->get()->first();
                        if(isset($statusList)){
                            $statusId=$statusList->approval_status_id;
                            $statusName=$statusList->approval_status_name;
                            $statusDisplay=$statusList->approval_status_display;
                            $statusColor=$statusList->approval_status_color;
                            $statusFontColor=$statusList->approval_status_font_color;
                        }
            //  $globalUrlList=DB::table('translate_reference_url')->where('original_word_id','=',$translatedListValue->or_id)->get();

              /*if(isset($globalUrlList->global_url)){
                $translatedGlobalUrl=$globalUrlList->global_url;
                }
                else {*/
                $translatedGlobalUrl="<a target='false' href='".$translatedListValue->reference_url."'>".$translatedListValue->reference_url."</a>";
                //}
                if($statusName != 'Accepted'){
                    $editableStatus=false;
                }elseif ($statusName == 'Accepted' && $this->userId=='1') {
                    $editableStatus=false;
                }else{
                 $editableStatus=true;          
                }
               if($this->permissionDetails('Hase_translation_list','add')){
                    $wordEditPermission='add';
                }else{
                    $wordEditPermission=' ';
                } 
              $translationListData[]=array('wordStatus'=>$translatedListValue->word_status,'wordEditPermission'=>$wordEditPermission,'editableStatus'=>$editableStatus,"statusFontColor"=>$statusFontColor,"colorCode"=>$statusColor,"globalUrlList"=>$translatedGlobalUrl,"translationUrlReference"=>$translatedListValue->reference_url,"translatedWord"=>$translatedListValue->translation,"wordFlagValue"=>$statusName,"originalWordId"=>$translatedListValue->or_id,'languageCode'=>$request->languageCode,'original_word'=>$translatedListValue->original_word,'userReference'=>$translatedListValue->user_reference,'wordDate'=>$translatedListValue->date_time);
        }
        return $translationListData;   
        
        }
public function wordTranslationLanguageList(Request $request){  
        Session::put('languageCode',$request->languageCode); 
        $request->filterValue;       
        $userId=$this->roleId;
        $translatedList=$translationListData=array();
        $statusFilterId=null;
        $statusListArray=$this->userWordStatusViewList();
        if($request->filterValue == 'all' || $request->filterValue == 'null'){
                $translatedList=DB::table('word_translation_stage')->orderBy('date_time','DESC')/*->whereIn('translation_status',$statusListArray)*/->get();
            }
            else{
                $translatedList=DB::table('word_translation_stage')->where('translation_status',"=",$request->filterValue)/*->whereIn('translation_status',$statusListArray)*/->orderBy('date_time','DESC')->get();
            }
        foreach ($translatedList as $translatedListValue) {
            $statusList=DB::table('approval_status')->where('approval_status_id','=',$translatedListValue->translation_status)->get()->first();
                        if(isset($statusList)){
                            $statusId=$statusList->approval_status_id;
                            $statusName=$statusList->approval_status_name;
                            $statusDisplay=$statusList->approval_status_display;
                            $statusColor=$statusList->approval_status_color;
                            $statusFontColor=$statusList->approval_status_font_color;
                        }
              $translationListData[]=array("statusFontColor"=>$statusFontColor,"colorCode"=>$statusColor,"translatedWord"=>$translatedListValue->translation,"wordFlagValue"=>$statusName,"originalWordId"=>$translatedListValue->or_id,'languageCode'=>$request->languageCode,'original_word'=>$translatedListValue->original_word,'userReference'=>$translatedListValue->user_reference,'wordDate'=>$translatedListValue->date_time);
        }
        return $translationListData;    
        }

    public function translationCurlUrlAction($translationCurlUrl,$translationCurlOption){
        $curlWrapper=new CurlWrapper();
        return $curlWrapper->curlPost($translationCurlUrl, $translationCurlOption);
    }
    public function WordsTranslationMultipleEntries(Request $request){
        Session::put('userTranslationPriority',0);
        Session::put('originalWordId',$request->originalWordId); 
        $languageCode=$request->languageCode;
        $originalWordId=$request->originalWordId;
        $approvedStatus=$request->approvedStatus;
        $wordTranslationListData=array();
        if ($request['filter'] !='') {
            $filterStatusValue=$request['filter']['filters'][0]['value'];
            $wordTranslationEntriesList=DB::table('translation_approval')->where('approval_grouphash','=',$originalWordId)->where('language_code','=',$languageCode)->where('approval_status','=',$filterStatusValue)->where('request_table_stage','=','word_translation_stage')->get();
        }else{
            $wordTranslationEntriesList=DB::table('translation_approval')->where('language_code','=',$languageCode)->where('approval_grouphash','=',$originalWordId)->where('request_table_stage','=','word_translation_stage')->get();
        }
        foreach ($wordTranslationEntriesList as $wordTranslationEntriesListValue) {    $staffGroupList = DB::table('staff')->where('staff_id','=',$this->roleId)->get()->first();
                $originalWordList = DB::table('word_translation_stage')->where('or_id','=',$request->originalWordId)->get()->first();
                if(!empty($staffGroupList->staff_group_id)) {
                    $staffGroupId = $staffGroupList->staff_group_id;
                }else{
                    $staffGroupId=0;
                }
                $translationStatus=$wordTranslationEntriesListValue->approval_status_id;
                $statusList=DB::table('approval_status')->where('approval_status_id','=',$translationStatus)->get()->first();
                        if(isset($statusList)){
                            $statusId=$statusList->approval_status_id;
                            $statusName=$statusList->approval_status_name;
                            $statusDisplay=$statusList->approval_status_display;
                            $statusColor=$statusList->approval_status_color;
                            $statusFontColor=$statusList->approval_status_font_color;
                        }
                $userDetailsList=DB::table('portal_password')->where('user_id','=',$wordTranslationEntriesListValue->request_user_id)->get()->first();
                    if(isset($userDetailsList->username)){
                        $userName=$userDetailsList->username;
                    }else{
                        $userName='Guest';
                    }
                    $time=$this->convertIntoTime($wordTranslationEntriesListValue->request_time);     
                    $date=$this->convertIntoDate($wordTranslationEntriesListValue->request_date);
                $wordTranslationListData[]=array('original_word'=>$originalWordList->original_word,"staffGroupId"=>$staffGroupId,"statusFontColor"=>$statusFontColor,"colorCode"=>$statusColor,"wordDate"=>$date." ".$time,'userReference'=>$userName,'wordFlagValue'=>$wordTranslationEntriesListValue->approval_status_id,'historyId'=>$wordTranslationEntriesListValue->approval_id,'originalWordId'=>$originalWordId,'original_word'=>'','translatedWord'=>$wordTranslationEntriesListValue->translation_text,'wordTranslationStatus'=>$statusName);
        }
        return $wordTranslationListData;
}
    public function wordsTranslationLanguageDetailsList(Request $request){ 
        $originalWordId=$request->originalWordId;
        $wordTranslationLanguageListData=array();
        $wordTranslationLanguageList=DB::table('translation_approval')->where('approval_grouphash','=',$originalWordId)->where('request_table_stage','=','word_translation_stage')->get();
        foreach ($wordTranslationLanguageList as $wordTranslationLanguageListValue) {
            $translationStatus=$wordTranslationLanguageListValue->approval_status_id;
            $statusList=DB::table('approval_status')->where('approval_status_id','=',$translationStatus)->get()->first();
                        if(isset($statusList)){
                            $statusId=$statusList->approval_status_id;
                            $statusName=$statusList->approval_status_name;
                            $statusDisplay=$statusList->approval_status_display;
                            $statusColor=$statusList->approval_status_color;
                            $statusFontColor=$statusList->approval_status_font_color;
                        }
            $userDetailsList=DB::table('portal_password')->where('user_id','=',$wordTranslationLanguageListValue->request_user_id)->get()->first();
                    if(isset($userDetailsList->username)){
                        $userName=$userDetailsList->username;
                    }else{
                        $userName='Guest';
                    }
            $time=$this->convertIntoTime($wordTranslationLanguageListValue->request_time);     
            $date=$this->convertIntoDate($wordTranslationLanguageListValue->request_date);
            $wordTranslationLanguageListData[]=array('translationVersion'=>$wordTranslationLanguageListValue->translation_version,"statusFontColor"=>$statusFontColor,"colorCode"=>$statusColor,"wordDate"=>$date." ".$time,'userReference'=>$userName,'wordFlagValue'=>$wordTranslationLanguageListValue->approval_status_id,'historyId'=>$wordTranslationLanguageListValue->approval_id,'originalWordId'=>$originalWordId,'original_word'=>'','translatedWord'=>$wordTranslationLanguageListValue->translation_text,'wordTranslationStatus'=>$statusName);
        }
        return $wordTranslationLanguageListData;
}
        public function userWordStatusViewList(){
                $userStatusViewList=DB::table('translation_status_view_manage')->where('status_target',"=",$this->staffId)->where('manage_table',"=",'word_translation_stage')->get();
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
    public function UpdateTranslationSentenceDetails(request $request){
        Session::put('userTranslationPriority',0);
        $languageCode=$request->languageCode;
        $userId=$this->roleId;
        $userReference=$this->staffName;
        $updateWordText=$request->original_word;
        $updateTranslatedWord=$request->translatedWord;
        $originalWordId=$request->originalWordId;
        $imageDate = date('Ymd');
        $imageTime = time();
        $translationHistoryList=DB::table('translation_approval')->where('approval_grouphash','=',$originalWordId)->get();
                        $translationHistoryCount=count($translationHistoryList);
        $translationVersion=$translationHistoryCount+1;
        DB::table('word_translation_stage')->where('original_word',"=",$request->original_word)->where('language_code',"=",$languageCode)->update(array('translation'=>$request->translatedWord,'date_time'=>date('Y-m-d H:i:s'))); 
        $timeZoneId = PermissionTrait::getTimeZoneId();
        $timezoneDetails=DB::table('timezone')->where('timezone_id','=',$timeZoneId)->first();
        $approvalId=DB::table('translation_approval')->insertGetId(array(
                            'request_time'=>$imageTime,
                            'request_date'=>$imageDate,
                            'request_staff_id'=>$this->userId,
                            'request_user_id'=>$this->userId,
                            'location_id'=>$this->locationId,
                            'request_table_live'=>'word_translation',
                            'request_table_stage'=>'word_translation_stage',
                            'request_fields'=>'original_word',
                            'approval_grouphash'=>$originalWordId,
                            'translation_text'=>$updateTranslatedWord,'approval_status_id'=>1,
                            'language_code'=>$languageCode,
                            'time_zone'=>$timezoneDetails->timezone_name,
                            'translation_version'=>$translationVersion));

        $tableDetails=DB::table('identity_table_type')->where('table_code','=','word_translation_stage')->first();
        DB::table('translation_reference')->insert(array(
                            'approval_id'=>$approvalId,
                            'identity_table_id'=>$tableDetails->type_id,
                            'identity_id'=>$approvalId,
                            'previous_time'=>$imageTime,
                            'previous_date'=>$imageDate));
    }
      
}