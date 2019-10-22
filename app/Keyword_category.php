<?php

namespace App;
use DB;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class keyword_category extends Model
{
    
    public $timestamps = false;
    protected $primaryKey = 'keyword_id';
    protected $table = 'keyword_category';

    const REGION_NAME_VALUE='regionName';
    const ACTIVITY_NAME_VALUE='activityName';
    const ACTIVITY_VALUE='activity';
    const REGION_VALUE='region';
    const TREE_ID='tree_id';
    const NODE_ID_FIELD='node_id';
    const EQUAL_SEPARATOR='=';
    const KEYWORD_ID_FIELD_VALUE='keyword_id';
    const SINGLE_SEPARATORS='';
    const REGION_NODE_VALUE='2';
    const REGION_ID=1;
    const ACTIVITY_ID=2;
    const TOTAL_SIZE=5;
    const MINIMUM_SIZE=0;
    const REGION_ACTIVITY_INDEX_ZERO=0;
    const REGION_ACTIVITY_INDEX_ONE=1;
    const EQUALS_TO="=";
    const SEMI_COLON=";";
    const OR_SYMBOL="||";
    const BLANK_SPACE="";
    const ACTIVITIES_VALUE='activities';
    const REGIONS_VALUE='regions';  
    const BRACKET_OVER=")";
    const SELECT_NODE_ID_QUERY="select node_id from keyword_category WHERE keyword_id =";
    const TREE_ID_COMPARE_QUERY=" AND tree_id =";
    const NODE_NAME_FIELD = 'text';
    const TREE_NODE_DEEP_LEVEL=5;
    const RECORD_INDEX_ONE=1;
    const NODE_LEVEL_FIELD='level';
    const NODE_PARENT_ID_FIELD='parent_id';
    const NODE_CHILDREN_COUNT_FIELD='children_count';
    const UPDATE_KEYWORD_QUERY="UPDATE keyword_category SET node_id=";
    const UPDTAE_KEYWORD_ID_CONDTITOIN="WHERE keyword_id=";
    const UPDTAE_TREE_ID_CONDTITOIN="AND tree_id=";

    private static $keywordName;

    private static $activityValue;

    private static $parentDetail;

    private static $keywordCategoryList;

    private static $keywordCategoryQuery;

    private static $treeId;

    private static $allActivities;

    private static $allRegions;

    private static $regionActivityKey="";

    private static $regionKey;

    private static $activityKey;

    private static $activityRegionsData="";

    private static $keywordsData;

    private static $keywordRegion;

    private static $keywordActivity;

    private static $databaseConnectionObject;

    private static $saveRegionData;

    private static $saveActivityData;

    private static $saveRegionActivity;

    private static $treeNodeDetail;

    public static function queryDBKeywordActivityRegionCategoryId($keyword_id){
        $eventCategoryId=array(self::ACTIVITY_VALUE=>self::SINGLE_SEPARATORS, self::REGION_VALUE=>self::SINGLE_SEPARATORS);
        $keywordEventCategoryId=keyword_category::where(self::TREE_ID,self::EQUAL_SEPARATOR,1)->where(self::KEYWORD_ID_FIELD_VALUE,self::EQUAL_SEPARATOR,$keyword_id)->get();
        if (isset($keywordEventCategoryId)) {
            $keywordEventCategoryData=array();
            foreach ($keywordEventCategoryId as $keywordEventCategoryIdValue)
                $keywordEventCategoryData[]=$keywordEventCategoryIdValue->node_id;
            $eventCategoryId[self::ACTIVITY_VALUE]=implode(',',$keywordEventCategoryData);
            unset($keywordEventCategoryData);
        }
        $keywordEventCategoryId=keyword_category::where(self::TREE_ID,self::EQUAL_SEPARATOR,self::REGION_NODE_VALUE)->where(self::KEYWORD_ID_FIELD_VALUE,self::EQUAL_SEPARATOR,$keyword_id)->get();
        if (isset($keywordEventCategoryId)) {
            $keywordEventCategoryData=array();
            foreach ($keywordEventCategoryId as $keywordEventCategoryIdValue)
                $keywordEventCategoryData[]=$keywordEventCategoryIdValue->node_id;
            $eventCategoryId[self::REGION_VALUE]=implode(',',$keywordEventCategoryData);
            unset($keywordEventCategoryData);
        }
        return $eventCategoryId;
    }
    public static function queryDBKeywordActivityRegionCategoryName($activityId, $regionId, $keywordCategoryNodeId = false){
        keyword_category::$treeNodeDetail = empty(keyword_category::$treeNodeDetail) ? json_decode(file_get_contents(CATEGORY_TREE_NODE_DETAIL_FILE_NAME), true) : keyword_category::$treeNodeDetail;
        $eventActivity = keyword_category::queryDBKeywordcategoryList($activityId);
        $eventRegion = keyword_category::queryDBKeywordcategoryList($regionId);
        if (true == $keywordCategoryNodeId) {
            return array(self::ACTIVITY_VALUE => $eventActivity, self::REGION_VALUE => $eventRegion);
        }
        return array(
                self::ACTIVITY_VALUE => implode(',', $eventActivity),
                self::REGION_VALUE   => implode(',', $eventRegion)
        );
    }
    private static function queryDBKeywordcategoryList($keywordCategoryList) {
        $keywordTreeNodeList = array();
        if (!is_array($keywordCategoryList)) {
            $keywordCategoryList = (strpos($keywordCategoryList,',') !== false) ? explode(',', $keywordCategoryList) : (array)$keywordCategoryList;
        }
        foreach ($keywordCategoryList as $categoryNodeId) {
            if (isset(keyword_category::$treeNodeDetail[$categoryNodeId])) {
                $keywordTreeNodeList[$categoryNodeId] = keyword_category::$treeNodeDetail[$categoryNodeId]['text'];
            }
        }
        return $keywordTreeNodeList;
    }

    public static function queryDBSearchUrlDetail($keywordId) {
        global $getGoogleQuery, $getParentNodeKey;
        keyword_category::queryDBSetUpRegionActivity();
        $treeCategoriesDetail[self::ACTIVITY_NAME_VALUE] =keyword_category::queryDBGetTreeSetup($keywordId, self::ACTIVITY_ID);
        $treeCategoriesDetail[self::REGION_NAME_VALUE] = keyword_category::queryDBGetTreeSetup($keywordId, self::REGION_ID);
        keyword_category::queryDBKeywordCombinations($treeCategoriesDetail[self::ACTIVITY_NAME_VALUE]);
        $activityQueryItems = $getGoogleQuery;
        $getGoogleQuery = array();
        keyword_category::queryDBKeywordCombinations($treeCategoriesDetail[self::REGION_NAME_VALUE]);
        $regionQueryItems = $getGoogleQuery;
        $getGoogleQuery = $searchQuery = array();
        for ($regionCountIndex = 0; $regionCountIndex < count($regionQueryItems); $regionCountIndex++) {
            $regionKey = explode(self::OR_SYMBOL, $regionQueryItems[$regionCountIndex]);
            $googleQueryKey = self::BLANK_SPACE;
            for ($activityCountIndex = 0; $activityCountIndex < count($activityQueryItems); $activityCountIndex++) {
                $activityKey = explode(self::OR_SYMBOL, $activityQueryItems[$activityCountIndex]);
                $googleQueryKey = $regionKey[self::RECORD_INDEX_ONE] . self::OR_SYMBOL . $activityKey[self::RECORD_INDEX_ONE];
                $result_text = implode(' ', array_unique(explode(' ', $activityKey[0])));  
                $searchQuery[$googleQueryKey] = trim($regionKey[0]) . " ". trim($result_text);
            }
        }
        return $searchQuery;
    }

    private static function queryDBGetTreeSetup($keywordId, $treeId) {
        global $getGoogleQuery, $getParentNodeKey;
        $keywordDetail =keyword_category::where(self::KEYWORD_ID_FIELD_VALUE,"=",$keywordId)->where(self::TREE_ID,self::EQUAL_SEPARATOR,$treeId)->get();
        $searchQueryDetail = array();
        if ($keywordDetail) {
            foreach ($keywordDetail as $keywordRow) {
                $parentSearchQuery=keyword_category::$treeNodeDetail[$keywordRow->node_id][self::NODE_NAME_FIELD] . self::OR_SYMBOL . $keywordRow->node_id;
                if (keyword_category::$treeNodeDetail[$keywordRow->node_id][self::NODE_LEVEL_FIELD] > self::TREE_NODE_DEEP_LEVEL) {
                    keyword_category::$queryDBKeywordParentId(keyword_category::$treeNodeDetail[$keywordRow->node_id][self::NODE_PARENT_ID_FIELD]);
                    $parentSearchQuery=implode(self::BLANK_SPACE, array_reverse($getParentNodeKey)) . self::BLANK_SPACE . $parentSearchQuery;
                    $getParentNodeKey=array();
                }
                $childCategoryNodes = keyword_category::queryDBKeywordChildCategory($keywordRow->node_id);
                $parentSearchQuery = explode(self::OR_SYMBOL, $parentSearchQuery);
                if (empty($childCategoryNodes)) {
                    $searchQueryDetail[$parentSearchQuery[0]] = $parentSearchQuery[0] . self::OR_SYMBOL . $parentSearchQuery[self::RECORD_INDEX_ONE];
                } else {
                    $searchQueryDetail[$parentSearchQuery[0]] = $childCategoryNodes;
                }
            }
        }
        return $searchQueryDetail;
    }

    public static function queryDBKeywordChildCategory($selectedId) {
        $childrenList = keyword_category::queryDBKeywordParentId($selectedId);
        foreach ($childrenList as $nodeId => $childrenDetail) {
            if ($childrenDetail[self::NODE_CHILDREN_COUNT_FIELD] > 0) {
                $childrenItems[$childrenDetail[self::NODE_NAME_FIELD]] = keyword_category::queryDBKeywordChildCategory($nodeId);
            } else {
                $childrenItems[$childrenDetail[self::NODE_NAME_FIELD]] = $childrenDetail[self::NODE_NAME_FIELD] . self::OR_SYMBOL . $nodeId;
            }
        }
        return !empty($childrenItems) ? $childrenItems : array();
    }

    public static function queryDBKeywordCombinations($activityRegion) {
        global $getGoogleQuery, $parentNodeKey;
        foreach ($activityRegion as $nodeItemIndex=>$nodeElement) {
            if (is_array($nodeElement)) {
                $parentNodeKey[$nodeItemIndex]=$nodeItemIndex;
                keyword_category::queryDBKeywordCombinations($nodeElement);
            } else {
                $keyDetails=(!empty($parentNodeKey)) ? implode(' ',$parentNodeKey) : ' ';
                $nodeElement=$keyDetails.' '.$nodeElement;
                
                $getGoogleQuery[]=$nodeElement;
            }
            unset($parentNodeKey[$nodeItemIndex]);
        }
    }
    public static function queryDBKeywordParentId($parentId) {
       $childrenList = array();
        foreach (keyword_category::$treeNodeDetail as $nodeId => $nodeDetail) {
            if ($nodeDetail[self::NODE_PARENT_ID_FIELD] == $parentId) {
                $childrenList[$nodeId] = $nodeDetail;
            }
        }
        return $childrenList;
    }

    public static function queryDBCheckKeywordById($keywordId) {
        $keywordId=keyword_category::where('keyword_id', $keywordId)->first();
        if (!empty($keywordId)) {
            return $keywordId->keyword_id;
        }
    }

    public static function updateDBRegionActivityCategory($keywordId,$treeId,$regionActivityId) {
        $updateRegionActivity=DB::connection()->update("UPDATE keyword_category SET node_id= $regionActivityId WHERE keyword_id= $keywordId AND tree_id= $treeId");
        return true;
    }

    public static function queryDBKeywordActivityRegionWithName($keywordId) {
        $activityRegionData=self::SINGLE_SEPARATORS;
        keyword_category::$keywordsData=keyword_category::where(self::KEYWORD_ID_FIELD_VALUE, keyword_category::EQUALS_TO, $keywordId)->where(self::TREE_ID, keyword_category::EQUALS_TO, keyword_category::REGION_ID)->get();
        $activityId=DB::connection()->select(self::SELECT_NODE_ID_QUERY.$keywordId.self::TREE_ID_COMPARE_QUERY.self::REGION_ID);
        $regionId=DB::connection()->select(self::SELECT_NODE_ID_QUERY.$keywordId.self::TREE_ID_COMPARE_QUERY.self::ACTIVITY_ID);
        $selectedActivityId=self::SINGLE_SEPARATORS;
        $selectedRegionId=self::MINIMUM_SIZE;
        $commaSeparators=self::BLANK_SPACE;
        foreach ($activityId as $activityValue) {
            $activityData=$activityValue->node_id;
            $selectedActivityId.=$commaSeparators.$activityData;
            $commaSeparators=",";
        }
        foreach ($regionId as $regionValue) {
            $regionData=$regionValue->node_id;
            $selectedRegionId.=$commaSeparators.$regionData;
            $commaSeparators=",";
        }
        $activityRegionData=keyword_category::insertDBActivityRegionNameDisplay($selectedActivityId, $selectedRegionId);
        return array(self::ACTIVITY_NAME_VALUE=>$activityRegionData[self::ACTIVITY_NAME_VALUE], self::REGION_NAME_VALUE=>$activityRegionData[self::REGION_NAME_VALUE]);
    }

    public static function insertDBActivityRegionNameDisplay($activityId, $regionId) {
        keyword_category::queryDBSetUpRegionActivity();
        $keywordActivities = keyword_category::queryDBCategoryListCorrection($activityId);
        $keywordRegions = keyword_category::queryDBCategoryListCorrection($regionId);
        return array(
            self::ACTIVITY_NAME_VALUE => implode(",", $keywordActivities),
            self::REGION_NAME_VALUE   => implode(",", $keywordRegions)
        );
    }
    public static function queryDBKeywordRegionActivityName($keywordData) {
        $activityRegionData=array();
        $activityRegion=array();
        foreach (keyword_category::$keywordsData as $keywordKey=>$keywordDetail) {
            $activityRegionData[]=$keywordDetail->node_id;
            $activityRegion[]=implode(keyword_category::COMMA_SIGN, $activityRegionData);
            return $activityRegion[$keywordKey];
        }
    }
    private static function queryDBCategoryListCorrection($categoryList) {
        $treeNodeList = array();
        if (!is_array($categoryList)) {
            $categoryList = (strpos($categoryList, ",") !== false) ? explode(",", $categoryList) : (array)$categoryList;
        }
        foreach ($categoryList as $nodeId) {
            if (isset(keyword_category::$treeNodeDetail[$nodeId])) {
                $treeNodeList[$nodeId] = keyword_category::$treeNodeDetail[$nodeId][self::NODE_NAME_FIELD];
            }
        }
        return $treeNodeList;
    }
    private static function queryDBSetUpRegionActivity() {
        keyword_category::$treeNodeDetail = empty(keyword_category::$treeNodeDetail) ? json_decode(file_get_contents(CATEGORY_TREE_NODE_DETAIL_FILE_NAME), true) : keyword_category::$treeNodeDetail;
    }
}
