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
use App\Image_translation_category;
use Carbon\Carbon;
use App\Http\Traits\PermissionTrait;
define('CURL_IMAGE_PATH', 'cURL/CurlWrapper.php');
use curl\ CurlWrapper;
require_once(CURL_IMAGE_PATH);
use PDO;
define('CATEGORY_TREE_NODE_DETAIL_FILE_NAME', '..\\public\\assets\\kendoui-treeview-categories\\js\\utree\\js\\TreeNodeDetails.json');
 
class Hase_image_translation_viewController extends Controller
{   
    private static $captchaKey = '3ab92b43668c5da746cf69eca8863323';
    const IMAGE_ACTIVITY_REGION_TREE_VIEW='albums.ImageActivityRegionTree';
    const REGION_ID_VALUE='regionId';
    const ACTIVITY_ID_VALUE='activityId';
    const REGION_NODE_ID='2';
    const ACTIVITY_NODE_ID='1';
    const CATEGORY_LIST_FILE_NAME = 'TreeNodeDetails.json';
    const NODE_NAME_FIELD = 'text';
    const NODE_LEVEL_FIELD = 'level';
    const NODE_PARENT_ID_FIELD = 'parent_id';
    const NODE_CHILDREN_COUNT_FIELD = 'children_count';
    const NODE_PATH_FIELD = 'path';
    const TREE_NODE_NUMBER='9';
    const ERROR_MESSAGE='ERROR: ';
    const SELECT_REGION_ACTIVITY_NAME_QUERY="SELECT cev.value as text, ce.parent_id, ce.level, ce.entity_id as id, ce.path, ce.children_count FROM magento_catalog_category_entity_varchar cev, magento_eav_attribute ea, magento_catalog_category_entity ce, magento_eav_entity_type eet WHERE eet.entity_type_code = 'catalog_category' AND eet.entity_type_id = ea.entity_type_id AND ea.attribute_code = 'name' AND ea.attribute_id = cev.attribute_id AND ce.entity_id = cev.entity_id";
    const IMAGE_ACTIVITY_VALUE='imageActivityValue';
    const IMAGE_REGION_VALUE='imageRegionValue';
    const ACTIVITY_REGION_WINDOW_VIEW="albums.ImageActivityRegionwindow";
    const IMAGE_ATTEMPT_VALUE='imageAttempt';
    const IMAGE_CAPTCHA_VALUE='imageCaptchaValue';
    const PHOTO_ALBUM_ID='photoAlbumId';
    const IMAGE_NAME_VALUE='imageName';
    const IMAGE_OLD_VALUE='imageOldValue';
    const IMAGE_NEW_VALUE='imageNewValue';
    const IMAGE_CAPTCHA_STATUS='imageCaptchaStatus';

    public function __construct()
    {       
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
        $this->staffId = session()->has('staffId') ? session()->get('staffId') :"";
        $this->staffName = session()->has('staffName') ? session()->get('staffName') :"";
        $this->userId = session()->has('userId') ? session()->get('userId') :"";
        $this->roleId = session()->has('role') ? session()->get('role') :"";
        $this->locationId = session()->has('locationId') ? session()->get('locationId') :"";
        $this->userId = session()->has('userId') ? session()->get('userId') :"";
        return $next($request);
        });
    }
    use PermissionTrait;
    public function translationCurlUrlAction($translationCurlUrl,$translationCurlOption){
        $curlWrapper=new CurlWrapper();
        return $curlWrapper->curlPost($translationCurlUrl, $translationCurlOption);
    }
    public function jsonQueryView(request $request){
        if($this->permissionDetails('Hase_json_query_view','access')){
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
            $hase_translaton_status=DB::table('approval_status')
                ->distinct('approval_status_code')
                ->groupBy('approval_status_id')
                ->whereIn('approval_status_id',$statusListArray)    
                ->where('approval_status_display',">",'0')
                ->where('approval_status_code',"!=",'noop')
                ->get(); 
                $userName=$this->staffName;  
            return view('hase_translation.translation_json_query_list',compact('title','userName','hase_translaton_status'));
        }else{
            return Redirect::back()->with('message', 'You are not authorized to use this functionality!');
        }
    }
    public function imageApprovalView(request $request){
        if($this->permissionDetails('Hase_image_approval_list','access')){
            $haseImageApprovalList = DB::table('image_translation') ->join('portal_password as portal_password ','image_translation.user_id','=','portal_password.user_id')->select('image_translation.*','portal_password.username as user_id')->get();
            return view('hase_translation.image_approval_list_view',compact('title','haseImageApprovalList'));
        }else{
            return Redirect::back()->with('message', 'You are not authorized to use this functionality!');
        }
    }
    public function getImageApprovalLists(){
        $haseImageApprovalList = DB::table('image_translation') ->join('portal_password as portal_password ','image_translation.user_id','=','portal_password.user_id')->select('image_translation.*','portal_password.username as user_id')->get();

         foreach ($haseImageApprovalList as $key=>$imageApprovalList){
            $datetime = json_decode(PermissionTrait::covertToLocalTz($imageApprovalList->approved_time));
            $haseImageApprovalList[$key]->approved_date=$datetime->date;
            $haseImageApprovalList[$key]->approved_time=$datetime->time; 
            
        }
        $imageApprovalList = $haseImageApprovalList->toArray();
        return $imageApprovalList;
    }
    public function translationJsonQueryList(request $request){
        $statusName=$request->status_name;
        $userId=$this->userId;
        $imageQueryDetail=DB::table('translation_approval')->where('request_user_id',$userId)->where('approval_status_id',$statusName)->get();
            return $imageQueryDetail;
    }
    public function imageJsonQueryListView(request $request){
       $uploadedJsonImageDetailList=$imageJsonDetailList=array();
        $albumsNameList=DB::table('photo_album')->get();

        foreach ($albumsNameList as $albumsNameListValue){
            $albumName=str_replace(' ', '', $albumsNameListValue->photo_album_name);
            $imageJsonFile = dirname(URL::to('/'))."/jsonUploads/".$albumName.".json";
            $uploadedJsonImageDetailList[]= json_decode(file_get_contents($imageJsonFile),1);
        }

        foreach ($uploadedJsonImageDetailList as $imageJsonDatails){
            if (!empty($imageJsonDatails)) {
                foreach ($imageJsonDatails as $imageJsonDetailListValue){
                    $imageJsonDetailList[]=$imageJsonDetailListValue;
                } 
            }
        }
       return $imageJsonDetailList;
    }  
    public function imageTranslationView(request $request){
            if($this->permissionDetails('Image_translation','access')){
            $userStatusListArray=$this->userImageStatusViewList();
            $username=$this->staffName;
            if($this->staffName == 'admin'){
                $translatedList=DB::table('image_translation_stage')/*->whereIn('translation_status',$userStatusListArray)*/->get();
            }else{
                $translatedList=DB::table('image_translation_stage')->where('image_status','=','0')->get();
            }
            $commaSeparators='';
            $statusList=0;    

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
            $statusCountList=count($hase_translaton_status_filter);
            return view('hase_translation.image_translation_list',compact('title','statusCountList','hase_translaton_status_filter','username'));
            }else{
            return Redirect::back()->with('message', 'You are not authorized to use this functionality!');
        }
    } 
        public function imageDetailList(Request $request) {
        $treeNodeItem = array();
        $prepareStatement = DB::connection('mysql2')->select(self::SELECT_REGION_ACTIVITY_NAME_QUERY);
        foreach ($prepareStatement as $nodeIndex => $nodeValue) {
            $treeNodeItem[$nodeValue->id] = array(
                    self::NODE_NAME_FIELD           => $nodeValue->text,
                    self::NODE_LEVEL_FIELD          => $nodeValue->level,
                    self::NODE_PARENT_ID_FIELD      => $nodeValue->parent_id,
                    self::NODE_CHILDREN_COUNT_FIELD => $nodeValue->children_count,
                    self::NODE_PATH_FIELD           => $nodeValue->path
            );
        }
        file_put_contents(CATEGORY_TREE_NODE_DETAIL_FILE_NAME, json_encode($treeNodeItem));
        $imageDetailList=$imageListData=array();
        $imageFilterId=$acceptRejectfilterValue=$fieldFilter=$imageCaptchaStatusValue=null;
        $statusListArray=$this->userImageStatusViewList();
        if($this->userId == 1){
            if($request->filterValue == 'all' || $request->filterValue == 'null'){
                $imageDescription=DB::table('image_translation_stage')->join('photo_album','image_translation_stage.photo_album_id','=','photo_album.photo_album_id')/*->whereIn('translation_status',$statusListArray)*/->orderBy('image_text')->orderBy('date_time','DESC')->get();
            }
            else{
                $imageDescription=DB::table('image_translation_stage')->join('photo_album','image_translation_stage.photo_album_id','=','photo_album.photo_album_id')->where('translation_status','=',$request->filterValue)/*->whereIn('translation_status',$statusListArray)*/->orderBy('image_text')->orderBy('date_time','DESC')->get();
            } 
            }else{
                if($request->filterValue == 'all' || $request->filterValue == 'null'){
                $imageDescription=DB::table('image_translation_stage')->join('photo_album','image_translation_stage.photo_album_id','=','photo_album.photo_album_id')/*->whereIn('translation_status',$statusListArray)*/->orderBy('image_text')->orderBy('date_time','DESC')->where('image_status','=','0')->get();
            }
            else{
                $imageDescription=DB::table('image_translation_stage')->join('photo_album','image_translation_stage.photo_album_id','=','photo_album.photo_album_id')->where('image_status','=','0')->where('translation_status','=',$request->filterValue)/*->whereIn('translation_status',$statusListArray)*/->orderBy('image_text')->orderBy('date_time','DESC')->get();
            }

            }   
        $imageDetails=$imageDescription;
            $albumsNameList=DB::table('photo_album')
            ->join('image_translation_stage', 'image_translation_stage.photo_album_id', '=', 'photo_album.photo_album_id')
            ->groupBy('photo_album.photo_album_id')
            ->get();
        foreach ($albumsNameList as $albumsNameListValue){
            $albumName=str_replace(' ','', $albumsNameListValue->photo_album_name);
            $imageJsonFile = dirname(URL::to('/'))."/jsonUploads/".$albumName.".json";
            $uploadedImageDetailList[]= json_decode(file_get_contents($imageJsonFile),1);
        }
        foreach ($imageDetails as $imageDetailsKey => $imageDetailsValue) {
            $imageListData[$imageDetailsValue->id]=(array)$imageDetailsValue;
        }
        if(isset($uploadedImageDetailList)){
            foreach ($uploadedImageDetailList as $imageDetailsList){
                foreach ($imageDetailsList as $imageDataKey => $imageDataValue) {
                    if(isset($imageListData[$imageDataValue['imageId']])){
                        $imageListData[$imageDataValue['imageId']]['imageData']=$imageDataValue['imageData'];
                    }
                }
            }
        }
        $imageData=array_values($imageListData);
        foreach ($imageData as $imageDetails) {
            $imageEventCategoryId=Image_translation_category::queryDBImageActivityRegionCategoryId($imageDetails['id']);
            $activityRegionCategoryName=Image_translation_category::queryDBImageActivityRegionCategoryName($imageEventCategoryId['activity'], $imageEventCategoryId['region']);
            if($imageDetails['image_text'] == ''){
                $imageAcceptRejectStatus='disableAcceptReject';
            }else {
                $imageAcceptRejectStatus=$imageDetails['translation_status'];
            }
            $base64DecodeImage=base64_decode($imageDetails['imageData']);
            $imageUrlDecrypted=openssl_decrypt($base64DecodeImage,'AES-128-CBC',0,OPENSSL_RAW_DATA,'');
            if(stripos($imageUrlDecrypted, $_SERVER['SERVER_NAME']) !== FALSE){
                $imageUrlDecrypt=$imageUrlDecrypted;
            }else {
                $imageUrlDecrypt=$imageDetails['imageData'];
            }

            $statusList=DB::table('approval_status')->where('approval_status_id','=',$imageDetails['translation_status'])->get()->first();
                        if(isset($statusList)){
                            $statusId=$statusList->approval_status_id;
                            $statusName=$statusList->approval_status_name;
                            $statusDisplay=$statusList->approval_status_display;
                            $statusColor=$statusList->approval_status_color;
                            $statusFontColor=$statusList->approval_status_font_color;
                        }
            if($this->permissionDetails('Image_translation','add')){
                $imageEditPermission='add';
            }else{
                $imageEditPermission='';
            }
            $updatedTime=json_decode(PermissionTrait::covertToLocalTz($imageDetails['date_time']));
            $updatedDateTime=$updatedTime->date." ".$updatedTime->time;
            $imageDetailList[]=array('imageStatus'=>$imageDetails['image_status'],'id'=>$imageDetails['id'],'imageEditPermission'=>$imageEditPermission,'statusName'=>$statusName,"statusFontColor"=>$statusFontColor,"colorCode"=>$statusColor,self::IMAGE_ACTIVITY_VALUE=>$activityRegionCategoryName['activity'],self::IMAGE_REGION_VALUE=>$activityRegionCategoryName['region'],'imageOldValue'=>$imageDetails['image_old_value'],'imageAttempt'=>$imageDetails['image_attempt'],'imageId'=>$imageDetails['id'],'imageCaptchaStatus'=>$imageDetails['captcha_status'],'photoAlbumName'=>$imageDetails['photo_album_name'],'modifiedDate'=>$updatedDateTime,'imageId'=>$imageDetails['id'], 
                'userId'=>$this->userId,'viewStatus'=>$imageDetails['view_status'],'imageData'=>$imageUrlDecrypt,'imageText'=>$imageDetails['image_text'],'imageAcceptRejectStatus'=>$statusName);
    
      } 
        return $imageDetailList;
    
    }
    public function imageDelete($id){
         $haseImageAceess = $this->permissionDetails('Image_translation','delete');
        if($haseImageAceess) {
            DB::table('image_translation_stage')->where('id','=',$id)->update(array('image_status'=>1));
            Session::flash('type', 'success'); 
            Session::flash('msg', 'Image Successfully Deleted');
            return redirect('image_translation');
        } else {
            return Redirect::back()->with('message', 'You are not authorized to use this functionality!'); 
        }

    }
    public function updateImageText(Request $request) {
        $imageId=$request->imageId;
        $imageText=$request->imageText;
        $albumName=str_replace(' ','',$request->albumName);
        $imageJsonFile = dirname(dirname(dirname(__DIR__)))."/jsonUploads/".$albumName.".json";
        $updatedImageDetailList[]= json_decode(file_get_contents($imageJsonFile),1);
        if(isset($updatedImageDetailList)){
            foreach ($updatedImageDetailList as $imageDetailsList){
                foreach ($imageDetailsList as $imageDataValue) {
                    if($imageDataValue['imageId'] == $imageId){
                        $imageAttemptValue=$imageDataValue['imageAttempt'];
                        $imageDataValue['imageAttempt'] = $imageAttemptValue + 1;
                        $imageDataValue[self::IMAGE_OLD_VALUE]=$imageDataValue[self::IMAGE_NEW_VALUE];
                        $imageDataValue[self::IMAGE_NEW_VALUE]=$imageText;
                        DB::table('image_translation_stage')->where('id',"=",$imageId)->update(array('image_attempt'=>$imageDataValue['imageAttempt'],'image_old_value'=>$imageDataValue[self::IMAGE_OLD_VALUE]));
                    }
                    $imageData[]=array(self::IMAGE_REGION_VALUE=>$imageDataValue[self::IMAGE_REGION_VALUE],self::IMAGE_ACTIVITY_VALUE=>$imageDataValue[self::IMAGE_ACTIVITY_VALUE],self::IMAGE_NEW_VALUE=>$imageDataValue[self::IMAGE_NEW_VALUE],self::IMAGE_OLD_VALUE=>$imageDataValue[self::IMAGE_OLD_VALUE],'imageAttempt'=>$imageDataValue['imageAttempt'],self::IMAGE_CAPTCHA_STATUS=>$imageDataValue[self::IMAGE_CAPTCHA_STATUS],self::IMAGE_CAPTCHA_VALUE=>$imageDataValue[self::IMAGE_CAPTCHA_VALUE],self::PHOTO_ALBUM_ID=>$imageDataValue[self::PHOTO_ALBUM_ID],'imageId'=>$imageDataValue['imageId'],'userId'=>$imageDataValue['userId'],'imageElevation'=>$imageDataValue['imageElevation'],'imageLongitude'=>$imageDataValue['imageLongitude'],'imageLatitude'=>$imageDataValue['imageLatitude'],'imageData'=>$imageDataValue['imageData'],'imageName'=>$imageDataValue['imageName']);
                }
            }
        }
                    $imageDate = date('Ymd');
                    $imageTime = time();
                    $translationHistoryList=DB::table('translation_approval')->where('approval_grouphash','=',$imageId)->get();
                        $translationHistoryCount=count($translationHistoryList);
                        $translationVersion=$translationHistoryCount+1;
                    $approvalId=DB::table('translation_approval')->insertGetId(array(
                            'request_time'=>$imageTime,
                            'request_date'=>$imageDate,
                            'request_staff_id'=>$this->userId,
                            'request_user_id'=>$this->userId,
                            'location_id'=>$this->locationId,
                            'request_table_live'=>'image_translation',
                            'request_table_stage'=>'image_translation_stage',
                            'request_fields'=>'image_url',
                            'approval_grouphash'=>$imageId,
                            'translation_text'=>$imageText,'approval_status_id'=>1,
                            'translation_version'=>$translationVersion));
                    $tableDetails=DB::table('identity_table_type')->where('table_code','=','image_translation_stage')->first();
                    DB::table('translation_reference')->insert(array(
                            'approval_id'=>$approvalId,
                            'identity_table_id'=>$tableDetails->type_id,
                            'identity_id'=>$approvalId,
                            'previous_time'=>$imageTime,
                            'previous_date'=>$imageDate));
        $imageAttemptJsonData = json_encode($imageData);
        file_put_contents(dirname(dirname(dirname(__DIR__)))."/jsonUploads/".$albumName.".json", $imageAttemptJsonData);
        DB::table('image_translation_stage')->where('id','=',$imageId)->update(array('image_text'=>$imageText,'captcha_status'=>1));
    }
    public function imageTranslationHistoryList(request $request){
        $imageTranslationHistoryList=array();
        $imageId=$request->imageId;
        $imageTranslationDetails=DB::table('translation_approval')->where('approval_grouphash','=',$request->imageId)->where('request_table_stage','=','image_translation_stage')->get();
        foreach ($imageTranslationDetails as $translationHistoryListValue) {
            $imageStatus=$translationHistoryListValue->approval_status_id;
            $statusList=DB::table('approval_status')->where('approval_status_id','=',$imageStatus)->get()->first();
                        if(isset($statusList)){
                            $statusId=$statusList->approval_status_id;
                            $statusName=$statusList->approval_status_name;
                            $statusDisplay=$statusList->approval_status_display;
                            $statusColor=$statusList->approval_status_color;
                            $statusFontColor=$statusList->approval_status_font_color;
                        }
            $userDetailsList=DB::table('portal_password')->where('user_id','=',$translationHistoryListValue->request_user_id)->get()->first();
            $datetime = json_decode(PermissionTrait::covertToLocalTz($translationHistoryListValue->request_time));
            $imageTranslationHistoryList[]=array('translationVersion'=>$translationHistoryListValue->translation_version,"statusFontColor"=>$statusFontColor,"colorCode"=>$statusColor,'imageStatusName'=>$statusName,'modifiedDate'=>$datetime->date.' '.$datetime->time,'userName'=>$userDetailsList->username,'imageTranslationHistory'=>$translationHistoryListValue->translation_text);
        }
        return $imageTranslationHistoryList;
    }
    public function imageViewStatus(request $request) {
        $imageId=$request->imageId;
        $statusValue=$request->viewStatus;
        if(isset($imageId)){
            DB::table('image_translation_stage')->where('id','=',$imageId)->update(array('view_status'=>date('Y-m-d H:i:s').' '.$this->staffName,'date_time'=>date('Ymd').time()));
        }
    }
    public function activityRegionWindow() {
         return view('hase_translation.image_activity_region_window'); 
    }
    public function imageUploadList(){
        if($this->permissionDetails('Hase_image_upload','access')){
        $albumsDetail=DB::table('photo_album')->where('user_id','=',$this->roleId)->get();
        $albumsNameList=array();
        foreach ($albumsDetail as $albumsName) {
               $albumsNameList[]=$albumsName->photo_album_name;
           }
        $albumNameList=json_encode($albumsNameList);
         $userName=$this->staffName;  
        return view('hase_translation.image_upload_view',compact('title','albumNameList','userName'));
        }else{
            return Redirect::back()->with('message', 'You are not authorized to use this functionality!');
        }
    }
    public function imageUpload(request $request) {
        $albumName=$request->albumName;
        $publicDirPath = public_path(config('app.image_dir_path'));  
        $translationImageDirPath = "/images/translation/";
        $imageDirPath = $publicDirPath.$translationImageDirPath;
        $imageLocation=$request->imageLocation;
        $userId=$this->userId;
        $imageDate = date('Ymd');
        $imageTime = time();
        $baseUrl=URL::to('/');

        if($request->imageData != null){
            $urlImages=$request->imageData;
            if (@getimagesize($urlImages)) {
                $imageUploadCurlUrl=dirname($baseUrl).'/app/Translation/Hase_image_upload.php?callFunction=storeImageDetails';
                
                $imageDetails=$this->translationCurlUrlAction($imageUploadCurlUrl,array('albumName'=>$albumName,'imageLocation'=>$imageLocation,'userId'=>$userId,'imageData'=>$urlImages));
                  Hase_image_translation_viewController::captchaImageDetails($urlImages,$userId,$this->locationId);
                }
        } 
        if($request->file('translation_image') != null){
            $imageDetails =$request->file('translation_image');
            $fileCount = count($imageDetails);
            $uploadCount = 0;
            foreach($imageDetails as $imageFile) {
                if(!file_exists($imageDirPath)){
                    mkdir($imageDirPath,0777,true);
                    }
                    $file_name = $imageFile->getClientOriginalName();
                    $fileName = str_replace(" ", "", $file_name);
                    $imageFile->move($imageDirPath,$fileName); 
                    $urlImages=asset(config('app.image_dir_path'))."images/translation/".$fileName;
                    $imageUploadCurlUrl=dirname($baseUrl).'/app/Translation/Hase_image_upload.php?callFunction=storeImageDetails';
                    $imageDetails=$this->translationCurlUrlAction($imageUploadCurlUrl,array('albumName'=>$albumName,'imageLocation'=> $imageLocation,'userId'=>$userId,'imageData'=>$urlImages));
                    Hase_image_translation_viewController::captchaImageDetails($urlImages,$userId,$this->locationId);

                   
                }
        }
        Session::flash('type', 'success'); 
        Session::flash('msg', 'Image Successfully Uploaded');
        return redirect('hase_image_upload');
    }
    static function captchaImageDetails($urlImages,$userId,$locationId){
        $imageDate = date('Ymd');
        $imageTime = time();
        $imageTranslationDetails=DB::table('image_translation_stage')->where('image_url','=',$urlImages)->where('image_text','!=','')->first();
        
                    if(isset($imageTranslationDetails)){
                        $approvalId=DB::table('translation_approval')->insertGetId(array(
                            'request_time'=>$imageTime,
                            'request_date'=>$imageDate,
                            'request_staff_id'=>$userId,
                            'request_user_id'=>$userId,
                            'location_id'=>$locationId,
                            'request_table_live'=>'image_translation',
                            'request_table_stage'=>'image_translation_stage',
                            'request_fields'=>'image_url',
                            'translation_text'=>$imageTranslationDetails->image_text,
                            'approval_grouphash'=>$imageTranslationDetails->id,
                            'approval_status_id'=>1,
                            'translation_version'=>1));
                    $tableDetails=DB::table('identity_table_type')->where('table_code','=','image_translation_stage')->first();
                    DB::table('translation_reference')->insert(array(
                            'approval_id'=>$approvalId,
                            'identity_table_id'=>$tableDetails->type_id,
                            'identity_id'=>$approvalId,
                            'previous_time'=>$imageTime,
                            'previous_date'=>$imageDate));
                    }
    }    
    public function userImageStatusViewList(){
        $userStatusViewList=DB::table('translation_status_view_manage')->where('status_target',"=",$this->staffId)->where('manage_table',"=",'image_translation_stage')->get();
        
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
    public function insertActivityRegionValue(request $request){
        $activityId=$request->activityId;
        $regionId=$request->regionId;
        $imageId=$request->imageId;
        $albumName=$request->albumName;
          if ($regionId != '') {
                $categoryId=explode(',',$regionId);
                $deleteIdList=DB::table('image_translation_category')->where('image_id','=',$imageId)->where('tree_id','=',2)->whereNotIn('node_id',$categoryId)->get();
                $regionActivityId=0;
                $commaSeparators='';
                foreach ($deleteIdList as $deleteIdListValue) {
                    $categoriesId=$deleteIdListValue->id;
                    $regionActivityId.=$commaSeparators.$categoriesId;
                    $commaSeparators =',';
                }
                if(isset($regionActivityId)){
                    $deleteCategoryId=explode(',',$regionActivityId);
                    DB::table('image_translation_category')->whereIn('id',$deleteCategoryId)->delete();
                }
                $regionIdValue=explode(',', $regionId);
                foreach ($regionIdValue as $regionKey=>$regionValue) {
                if($regionValue != ''){
                $imageCategoryIdList=DB::table('image_translation_category')->where('image_id','=',$imageId)->where('tree_id','=',2)->where('node_id','=',$regionValue)->first();
                    if(!isset($imageCategoryIdList)){
                        DB::table('image_translation_category')->insert(array('image_id'=>$imageId,'tree_id'=>2,'node_id'=>$regionValue));
                    }
                }
            }
         }
         if ($activityId != '') {
                $categoryId=explode(',',$activityId);
                $deleteIdList=DB::table('image_translation_category')->where('image_id','=',$imageId)->where('tree_id','=',1)->whereNotIn('node_id',$categoryId)->get();
                $regionActivityId=0;
                $commaSeparators='';
                foreach ($deleteIdList as $deleteIdListValue) {
                    $categoriesId=$deleteIdListValue->id;
                    $regionActivityId.=$commaSeparators.$categoriesId;
                    $commaSeparators =',';
                }
                if(isset($regionActivityId)){
                    $deleteCategoryId=explode(',',$regionActivityId);
                    DB::table('image_translation_category')->whereIn('id',$deleteCategoryId)->delete();
                }
                $activityIdValue=explode(',', $activityId);
                foreach ($activityIdValue as $activityKey=>$activityNode){
                    if($activityNode != ''){
                    $imageCategoryIdList=DB::table('image_translation_category')->where('image_id','=',$imageId)->where('tree_id','=',1)->where('node_id','=',$activityNode)->first();
                        if(!isset($imageCategoryIdList)){
                            DB::table('image_translation_category')->insert(array('image_id'=>$imageId,'tree_id'=>1,'node_id'=>$activityNode));
                        }
                    }
                }
         }
         $imageJsonFile = dirname(URL::to('/'))."/jsonUploads/".$albumName.".json";
         $updatedImageDetailList[]= json_decode(file_get_contents($imageJsonFile),1);
         if(isset($updatedImageDetailList)){
             foreach ($updatedImageDetailList as $imageDetailsList){
                 foreach ($imageDetailsList as $imageDataValue) {
                     if($imageDataValue['imageId'] == $imageId){
                         $imageEventCategoryId=Image_translation_category::queryDBImageActivityRegionCategoryId($imageId);
                         $activityRegionCategoryName=Image_translation_category::queryDBImageActivityRegionCategoryName($imageEventCategoryId['activity'], $imageEventCategoryId['region']);
                         $imageDataValue[self::IMAGE_ACTIVITY_VALUE] = $activityRegionCategoryName['activity'];
                         $imageDataValue[self::IMAGE_REGION_VALUE]=$activityRegionCategoryName['region'];
                     }
                     $imageCategoryData[]=array(self::IMAGE_REGION_VALUE=>$imageDataValue[self::IMAGE_REGION_VALUE],self::IMAGE_ACTIVITY_VALUE=>$imageDataValue[self::IMAGE_ACTIVITY_VALUE],'imageNewValue'=>$imageDataValue['imageNewValue'],'imageOldValue'=>$imageDataValue['imageOldValue'],'imageAttempt'=>$imageDataValue['imageAttempt'],'imageCaptchaStatus'=>$imageDataValue['imageCaptchaStatus'],self::IMAGE_CAPTCHA_VALUE=>$imageDataValue[self::IMAGE_CAPTCHA_VALUE],self::PHOTO_ALBUM_ID=>$imageDataValue[self::PHOTO_ALBUM_ID],'imageId'=>$imageDataValue['imageId'],'userId'=>$imageDataValue['userId'],'imageElevation'=>$imageDataValue['imageElevation'],'imageLongitude'=>$imageDataValue['imageLongitude'],'imageLatitude'=>$imageDataValue['imageLatitude'],'imageData'=>$imageDataValue['imageData'],'imageName'=>$imageDataValue['imageName']);
                 }
             }
         }
         $imageCategoryJsonData = json_encode($imageCategoryData);
         file_put_contents(dirname(dirname(dirname(__DIR__)))."/jsonUploads/".$albumName.".json", $imageCategoryJsonData);
    }   
}