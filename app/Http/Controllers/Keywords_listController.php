<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\App;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Traits\PermissionTrait;
use URL;
use Session;
use DB;
use Redirect;
use App\Keyword_category;
use App\Keyword;
use App\Keyword_list;
define('CATEGORY_TREE_NODE_DETAIL_FILE_NAME', '..\\public\\assets\\kendoui-treeview-categories\\js\\utree\\js\\TreeNodeDetails.json');


class Keywords_listController extends Controller
{
    const NODE_NAME_FIELD = 'text';
    const NODE_LEVEL_FIELD = 'level';
    const NODE_PARENT_ID_FIELD = 'parent_id';
    const NODE_CHILDREN_COUNT_FIELD = 'children_count';
    const NODE_PATH_FIELD = 'path';
    const TREE_NODE_NUMBER='9';
    const ERROR_MESSAGE='ERROR: ';
    const SELECT_REGION_ACTIVITY_NAME_QUERY="SELECT cev.value as text, ce.parent_id, ce.level, ce.entity_id as id, ce.path, ce.children_count FROM magento_catalog_category_entity_varchar cev, magento_eav_attribute ea, magento_catalog_category_entity ce, magento_eav_entity_type eet WHERE eet.entity_type_code = 'catalog_category' AND eet.entity_type_id = ea.entity_type_id AND ea.attribute_code = 'name' AND ea.attribute_id = cev.attribute_id AND ce.entity_id = cev.entity_id";
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

    public function keywordsList(request $request){
        if($this->permissionDetails('Search_keywords','access')){
            Keywords_listController::getCreateJson();
            return view('search_engine.keywords_list',compact('title'));
        }else{
            return Redirect::back()->with('message', 'You are not authorized to use this functionality!');
        }
    }
     public function getCreateJson() {
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
        }
    public function getKeywordsLists(request $request){
            if($this->userId != 1){
                $keywords_list = DB::table('keyword')->get();
            }else{
                $keywords_list = DB::table('keyword')->where('keyword_status','!=',0)->get();
            }
          foreach ($keywords_list as $key=>$keywords_list_value){
            $imageEventCategoryId=Keyword_category::queryDBKeywordActivityRegionCategoryId($keywords_list_value->keyword_id);
            $activityRegionCategoryName=Keyword_category::queryDBKeywordActivityRegionCategoryName($imageEventCategoryId['activity'], $imageEventCategoryId['region']);
            if($keywords_list_value->last_search != 0){
                $datetime = json_decode(PermissionTrait::covertToLocalTz($keywords_list_value->last_search));
                $keywords_list[$key]->dateTime=$datetime->date." ".$datetime->time;
            }else{
                $keywords_list[$key]->dateTime='';
            }
                $keywords_list[$key]->activity=$activityRegionCategoryName['activity'];
                $keywords_list[$key]->region=$activityRegionCategoryName['region'];           
        }
        $keywords_list_value = $keywords_list->toArray();
        return $keywords_list_value;
    }
     public function createKeyword(request $request) {
        if($this->permissionDetails('Search_keywords','add')){            
            return view('search_engine.add_new_keyword',compact('title'));
        }else{
            return Redirect::back()->with('message', 'You are not authorized to use this functionality!');
        }
    }
    public function addKeyword(request $request){
        $keywordName=$request->keyword;
        $keywordIdentity=Keyword::where('keyword','=',$keywordName)->first();
        if(!isset($keywordIdentity->keyword)) {
            $addKeyword=new Keyword();
            $addKeyword->keyword=$keywordName;
            $addKeyword->save();
            $keywordId=$addKeyword->keyword_id;
            $keywordIdentity=Keyword_list::where('keyword_id','=',$keywordId)->first();
            if(!isset($keywordIdentity->keyword_id)) {
                $keyowrdList=new Keyword_list();
                $keyowrdList->keyword_id=$keywordId;
                $keyowrdList->search_id=1;
                $keyowrdList->save();
            }
            $activityId=$request->activityId;
            $regionId=$request->regionId;
            if ($keywordId!='') {
                $regionId=explode(',', $regionId);
                $regionValue=$regionId[0];
                Keyword_category::insert(array('keyword_id'=>$keywordId,"tree_id"=>2,'node_id'=>$regionValue));
                $activityId=explode(',', $activityId);
                $activityValue=$activityId[0];
                Keyword_category::insert(array('keyword_id'=>$keywordId,"tree_id"=>1,'node_id'=>$activityValue));
            }
        }
    }
    public function searchKeywordDetailsList($keywordId) {
        $keywordData        =Keyword::where('keyword_id','=',$keywordId)->first();
        $keywordCategoryList=Keyword_category::queryDBSearchUrlDetail($keywordData->keyword_id);
        $keywordList=array();
        foreach ($keywordCategoryList as $keywordCategory) {
            $keywordList[]=array('links'=>$keywordCategory." ".$keywordData->keyword);
        }
        return $keywordList;
    }
    public function editKeyword($keyword_id) {
        if($this->permissionDetails('Search_keywords','add')){
            $keywordData=Keyword::where('keyword_id','=',$keyword_id)->first();
            $treeData=Keyword_category::queryDBKeywordActivityRegionWithName($keyword_id);
            return View('search_engine.edit_keyword',compact('title','keywordData','treeData'));
        }
        else{
            return Redirect::back()->with('message', 'You are not authorized to use this functionality!');
        }
    }
    public function keywordListDelete($id){
         $haseKeywordAceess = $this->permissionDetails('Search_keywords','delete');
        if($haseKeywordAceess) {
            DB::table('keyword')->where('keyword_id','=',$id)->update(array('keyword_status'=>0));
            Session::flash('type', 'success'); 
            Session::flash('msg', 'keyword Successfully Deleted');
            return redirect('search_keywords');
        } else {
            return Redirect::back()->with('message', 'You are not authorized to use this functionality!'); 
        }
    } 
    public function updateActiveValue(request $request){
             $keywordId=$request->keyword_id;
             $status=$request->status;
             DB::table('keyword')->where('keyword_id','=',$keywordId)->update(array('active'=>$status));
    }
     public function updateKeyword(request $request) {
        $keywordId=$request->keywordId;
        $activityId=$request->activityId;
        $regionId=$request->regionId;
        $regionId=explode(",", $regionId);
        $regionValue=$regionId[0];
        $activityId=explode(",", $activityId);
        $updateKeyword    =$request->keyword;
        Keyword::where('keyword_id','=',$keywordId)->update(array('keyword'=>$updateKeyword));
        $keywordsId=Keyword_category::where('keyword_id','=',$keywordId)->first();
        $activityValue=$activityId[0];
        if (strpos($regionValue," ")===false) {
            if(!isset($keywordsId->keyword_id)) {
                Keyword_category::insert(array('keyword_id'=>$keywordId,"tree_id"=>2,'node_id'=>$regionValue));
            }
            else {
                Keyword_category::where('keyword_id','=',$keywordId)->where('tree_id','=','2')->update(array('node_id'=>$regionValue));
            }
        }
        if (strpos($activityValue," ")===false) {
            if(!isset($keywordsId->keyword_id)) {
                Keyword_category::insert(array('keyword_id'=>$keywordId,"tree_id"=>1,'node_id'=>$activityValue));
            }
            else {
               Keyword_category::where('keyword_id','=',$keywordId)->where('tree_id','=','1')->update(array('node_id'=>$activityValue));
            }
        }
     }
}
