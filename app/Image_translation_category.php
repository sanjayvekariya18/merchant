<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Hase_category.
 *
 * @author  The scaffold-interface created at 2017-03-06 08:51:43am
 * @link  https://github.com/amranidev/scaffold-interface
 */
class Image_translation_category extends Model
{
	
	
    public $timestamps = false;
    protected $primaryKey = 'id';
    protected $table = 'image_translation_category';


    const ACTIVITY_VALUE='activity';
    const REGION_VALUE='region';
    const TREE_ID='tree_id';
    const NODE_ID_FIELD='node_id';
    const EQUAL_SEPARATOR='=';
    const IMAGE_ID_FIELD_VALUE='image_id';
    const SINGLE_SEPARATORS='';
    const REGION_NODE_VALUE='2';

    private static $treeNodeDetail;

	public static function queryDBImageActivityRegionCategoryId($imageId){
        $eventCategoryId=array(self::ACTIVITY_VALUE=>self::SINGLE_SEPARATORS, self::REGION_VALUE=>self::SINGLE_SEPARATORS);
        $imageEventCategoryId=Image_translation_category::where(self::TREE_ID,self::EQUAL_SEPARATOR,1)->where(self::IMAGE_ID_FIELD_VALUE,self::EQUAL_SEPARATOR,$imageId)->get();
        if (isset($imageEventCategoryId)) {
            $imageEventCategoryData=array();
            foreach ($imageEventCategoryId as $imageEventCategoryIdValue)
                $imageEventCategoryData[]=$imageEventCategoryIdValue->node_id;
            $eventCategoryId[self::ACTIVITY_VALUE]=implode(',',$imageEventCategoryData);
            unset($imageEventCategoryData);
        }
        $imageEventCategoryId=Image_translation_category::where(self::TREE_ID,self::EQUAL_SEPARATOR,self::REGION_NODE_VALUE)->where(self::IMAGE_ID_FIELD_VALUE,self::EQUAL_SEPARATOR,$imageId)->get();
        if (isset($imageEventCategoryId)) {
        	$imageEventCategoryData=array();
            foreach ($imageEventCategoryId as $imageEventCategoryIdValue)
                $imageEventCategoryData[]=$imageEventCategoryIdValue->node_id;
            $eventCategoryId[self::REGION_VALUE]=implode(',',$imageEventCategoryData);
            unset($imageEventCategoryData);
        }
        return $eventCategoryId;
    }
    public static function queryDBImageActivityRegionCategoryName($activityId, $regionId, $imageCategoryNodeId = false){
        Image_translation_category::$treeNodeDetail = empty(Image_translation_category::$treeNodeDetail) ? json_decode(file_get_contents(CATEGORY_TREE_NODE_DETAIL_FILE_NAME), true) : Image_translation_category::$treeNodeDetail;
        $eventActivity = Image_translation_category::queryDBImagecategoryList($activityId);
        $eventRegion = Image_translation_category::queryDBImagecategoryList($regionId);
        if (true == $imageCategoryNodeId) {
            return array(self::ACTIVITY_VALUE => $eventActivity, self::REGION_VALUE => $eventRegion);
        }
        return array(
                self::ACTIVITY_VALUE => implode(',', $eventActivity),
                self::REGION_VALUE   => implode(',', $eventRegion)
        );
    }
    private static function queryDBImagecategoryList($imageCategoryList) {
        $imageTreeNodeList = array();
        if (!is_array($imageCategoryList)) {
            $imageCategoryList = (strpos($imageCategoryList,',') !== false) ? explode(',', $imageCategoryList) : (array)$imageCategoryList;
        }
        foreach ($imageCategoryList as $categoryNodeId) {
            if (isset(Image_translation_category::$treeNodeDetail[$categoryNodeId])) {
                $imageTreeNodeList[$categoryNodeId] = Image_translation_category::$treeNodeDetail[$categoryNodeId]['text'];
            }
        }
        return $imageTreeNodeList;
    }
}
