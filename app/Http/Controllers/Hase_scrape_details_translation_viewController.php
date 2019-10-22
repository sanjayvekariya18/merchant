<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\App;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Redirect;
use URL;
use Session;
use DB;
use Auth;
use App\Search_result_scrape;
use DateTime;
use Carbon\Carbon;
use App\Http\Traits\PermissionTrait;
use PDO;

class Hase_scrape_details_translation_viewController extends Controller
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
            $this->userId = session()->has('userId') ? session()->get('userId') :"";
            return $next($request);
        });
    }
    use PermissionTrait;
    public function scrapeEventDetailsView(){
        if($this->permissionDetails('Hase_scrape_details_list','access')){
        $hase_scrape_event_translaton_status_filter=array();
        $scrapeEventTranslatedList=array();
        $userStatusListArray=$this->userScrapeEventStatusViewList();
        $scrapeEventTranslatedList=DB::table('regex_result_pattern')
         ->leftjoin('identity_table_type as identity_table_type','identity_table_type.type_id','=','regex_result_pattern.identity_table_id')
         ->leftjoin('regex_pattern as regex_pattern','regex_pattern.pattern_id','=','regex_result_pattern.pattern_id')->select('regex_result_pattern.*','identity_table_type.table_code as table_code','regex_pattern.pattern as tupleregex')->get();
        $statusList=null;
        $commaSeparators="";
        if(isset($scrapeEventTranslatedList)){
        foreach ($scrapeEventTranslatedList as $scrapeEventTranslatedListValue) {
                $statusId=$scrapeEventTranslatedListValue->translation_status;
                $statusList.=$commaSeparators.$statusId;
                $commaSeparators =',';
        }
            $statusValue=explode(",", $statusList);
            $statusListArray =$statusValue;
            $hase_scrape_event_translaton_status_filter=DB::table('approval_status')
                ->distinct('approval_status_code')
                ->groupBy('approval_status_id')
                ->whereIn('approval_status_id',$statusListArray)    
                ->where('approval_status_display',">",'0')
                ->where('approval_status_code',"!=",'noop')
                ->get();
                }
        $username=$this->staffName;        
        return view('hase_translation.scrape_event_details_translation_list',compact('title','hase_scrape_event_translaton_status_filter','username'));
        }else{
            return Redirect::back()->with('message', 'You are not authorized to use this functionality!');
        }

    }
    public function scrapeEventDetailsList(Request $request){
        $scrapeEventDetailList=$scrapeEventDetails=array();
        $statusListArray=$this->userScrapeEventStatusViewList();
        if($request->filterValue == 'all' || $request->filterValue == 'null'){
         $scrapeEventDetails=DB::table('regex_result_pattern')
         ->leftjoin('identity_table_type as identity_table_type','identity_table_type.type_id','=','regex_result_pattern.identity_table_id')
         ->leftjoin('regex_pattern as regex_pattern','regex_pattern.pattern_id','=','regex_result_pattern.pattern_id')->select('regex_result_pattern.*','identity_table_type.table_code as table_code','regex_pattern.pattern as tupleregex')->get();
        }else{
             $scrapeEventDetails=DB::table('regex_result_pattern')
         ->leftjoin('identity_table_type as identity_table_type','identity_table_type.type_id','=','regex_result_pattern.identity_table_id')
         ->leftjoin('regex_pattern as regex_pattern','regex_pattern.pattern_id','=','regex_result_pattern.pattern_id')
         ->where('regex_result_pattern.translation_status','=',$request->filterValue)
         ->select('regex_result_pattern.*','identity_table_type.table_code as table_code','regex_pattern.pattern as tupleregex')->get();           
        }
        foreach ($scrapeEventDetails as $key=>$scrapeEventDetail){
                $scrapeEventsTranslationDetails=DB::table('translation_approval')->where('approval_grouphash','=',$scrapeEventDetail->result_id)->where('request_table_stage','=','regex_result_pattern')->first();
            if(isset($scrapeEventsTranslationDetails->approval_grouphash)){
                $translationHistoryId=1;
            }else{
                $translationHistoryId=0;
            }
            if($this->permissionDetails('Hase_scrape_details_list','add')){
                    $websiteEditPermission='add';
                }else{
                    $websiteEditPermission=' ';
                }
            if($this->permissionDetails('Hase_scrape_details_list','delete()')){
                    $websiteDeletePermission='delete';
                }else{
                    $websiteDeletePermission=' ';
                }    
            $statusList=DB::table('approval_status')->where('approval_status_id','=',$scrapeEventDetail->translation_status)->get()->first();
                        if(isset($statusList)){
                            $statusId=$statusList->approval_status_id;
                            $statusName=$statusList->approval_status_name;
                            $statusDisplay=$statusList->approval_status_display;
                            $statusColor=$statusList->approval_status_color;
                            $statusFontColor=$statusList->approval_status_font_color;
                        }
               $scrapeEventDetails[$key]->translationHistoryId = $translationHistoryId;
               $scrapeEventDetails[$key]->currentStatus = $statusName;
               $scrapeEventDetails[$key]->statusFontColor = $statusFontColor;
               $scrapeEventDetails[$key]->colorCode = $statusColor;
               $scrapeEventDetails[$key]->websiteEditPermission=$websiteEditPermission;
               $scrapeEventDetails[$key]->websiteDeletePermission=$websiteDeletePermission;
        }
        $scrapeEventDetails = $scrapeEventDetails->toArray();
        return $scrapeEventDetails;
    }
     public function websiteUrlDelete($id){
         $haseImageAceess = $this->permissionDetails('Hase_scrape_details_list','delete');
        if($haseImageAceess) {
            DB::table('regex_result_pattern')->where('result_id','=',$id)->update(array('website_url_status'=>0));
            Session::flash('type', 'success'); 
            Session::flash('msg', 'Website Url Successfully Deleted');
            return redirect('hase_scrape_details_list');
        } else {
            return Redirect::back()->with('message', 'You are not authorized to use this functionality!'); 
        }

    }
    public function updateWebsiteUrl(Request $request)
    {
        
    }
    public function scrapeEventTranslationHistory(Request $request){

        $scrapeEventsTranslationHistoryList=array();
        $scrapeEventsTranslationDetails=DB::table('translation_approval')->where('approval_grouphash','=',$request->scrapeDetailsId)->where('request_table_stage','=','regex_result_pattern')->get();
        foreach ($scrapeEventsTranslationDetails as $scrapeEventsTranslationDetailsValue) {
            $scrapeEventsStatus=$scrapeEventsTranslationDetailsValue->approval_status_id;
            if($scrapeEventsTranslationDetailsValue->language_code != ''){
                    $languageDetails=DB::table('languages')->where('language_code','=',$scrapeEventsTranslationDetailsValue->language_code)->first();
                    $languageName=$languageDetails->language_code;
                }else{
                    $languageName='';
                }
            $statusList=DB::table('approval_status')->where('approval_status_id','=',$scrapeEventsStatus)->get()->first();
                        if(isset($statusList)){
                            $statusId=$statusList->approval_status_id;
                            $statusName=$statusList->approval_status_name;
                            $statusDisplay=$statusList->approval_status_display;
                            $statusColor=$statusList->approval_status_color;
                            $statusFontColor=$statusList->approval_status_font_color;
                        }
            $userDetailsList=DB::table('portal_password')->where('user_id','=',$scrapeEventsTranslationDetailsValue->request_user_id)->get()->first();
            $dateTime=json_decode(PermissionTrait::covertToLocalTz($scrapeEventsTranslationDetailsValue->request_time));
            $scrapeEventsTranslationHistoryList[]=array('translationVersion'=>$scrapeEventsTranslationDetailsValue->translation_version,'translationId'=>$scrapeEventsTranslationDetailsValue->approval_id,"statusFontColor"=>$statusFontColor,"colorCode"=>$statusColor,'scrapeEventStatusName'=>$statusName,'scrapeEventTranslationDate'=>$dateTime->date." ".$dateTime->time,'userName'=>$userDetailsList->username,'scrapeEventTranslationHistory'=>$scrapeEventsTranslationDetailsValue->translation_text);
        }
        return $scrapeEventsTranslationHistoryList;
    }
        public function scrapeEventApprovalView(request $request){
            if($this->permissionDetails('Hase_scrape_event_approval_list','access')){
                $scrapeEventApprovalList = DB::table('event_url_detail_result') ->join('portal_password as portal_password ','event_url_detail_result.user_id','=','portal_password.user_id')->select('event_url_detail_result.*','portal_password.username as user_id')->get();
                return view('hase_translation.scrape_event_approval_list_view',compact('title','scrapeEventApprovalList'));
                }else{
                return Redirect::back()->with('message', 'You are not authorized to use this functionality!');
            }
        }
        public function getScrapeApprovalLists(request $request){
             $scrapeEventApprovalList = DB::table('event_url_detail_result') ->join('portal_password as portal_password ','event_url_detail_result.user_id','=','portal_password.user_id')->select('event_url_detail_result.*','portal_password.username as user_id')->get();

            foreach ($scrapeEventApprovalList as $key=>$scrapeEventApprovalListValue){
                    $supportedImage =array('gif','jpg','jpeg','png'
                                    );
                                    $translationFieldName=$scrapeEventApprovalListValue->events_value;
                                    $translationDetails = strtolower(pathinfo($translationFieldName, PATHINFO_EXTENSION)); 
                                    if (in_array($translationDetails, $supportedImage)) {
                                        $event_url=1;
                                    
                                    } else {
                                        $event_url=0;
            }
            $datetime = json_decode(PermissionTrait::covertToLocalTz($scrapeEventApprovalListValue->approved_time));
            $scrapeEventApprovalList[$key]->approved_date=$datetime->date;
            $scrapeEventApprovalList[$key]->approved_time=$datetime->time;
            $scrapeEventApprovalList[$key]->event_url=$event_url; 
        }
        $scrapeEventApprovalListValue = $scrapeEventApprovalList->toArray();
        return $scrapeEventApprovalListValue;
                                    
        }
        public function userScrapeEventStatusViewList(){
                $userStatusViewList=DB::table('translation_status_view_manage')->where('status_target',"=",$this->userId)->where('manage_table',"=",'event_url_detail_result')->get();
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
}