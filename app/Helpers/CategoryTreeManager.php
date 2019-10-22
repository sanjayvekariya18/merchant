<?php

namespace App\Helpers;

use App\Merchant_retail_category_type;
use App\Identity_merchant_retail_category_type;
use App\Http\Traits\PermissionTrait;
use DB;
use Illuminate\Support\Facades\App;
use Session;

class CategoryTreeManager
{
    private $userId;
    const PATH_SLASH_VALUE ='/';
    const TREE_ID='id';
    const PARENT_ID ='parent_id';
    const TREE_ITEMS="items";
    const NODE_PATH_TEXT='path';
    const CATEGORY_PARENT_OBJECT = "categoryParentObject";
    const CATEGORY_DATA_OBJECT ="categoryDataObject";

    public function __construct()
    {
        $this->userId        = session()->has('userId') ? session()->get('userId') : "";
        $this->roleId        = session()->has('role') ? session()->get('role') : "";
    }

    public function getCategoryTypes($merchantType)
    {
        $categoryIdList = Merchant_retail_category_type::
            select('merchant_retail_category_type.category_type_id as id',
                'merchant_retail_category_type.category_parent_id as parent_id',
            'identity_merchant_retail_category_type.identity_name as text')
            ->join('identity_merchant_retail_category_type', 'identity_merchant_retail_category_type.identity_id', 'merchant_retail_category_type.identity_id')
            ->join('merchant_type', 'merchant_type.merchant_type_id', 'merchant_retail_category_type.merchant_type_id')
            ->where('merchant_type.merchant_type_name', $merchantType)
            ->get()->toArray();
        $categoryArrangeData=array();
        foreach ($categoryIdList as $categoryIdDetail) {
            $categoryArrangeData[]=$categoryIdDetail;
        }
        return $categoryArrangeData;
    }

    public function getCategoryDataObject($merchantType)
    {
        $categoryArrangeData = $this->getCategoryTypes($merchantType);
        $categoryArrangedDataWithParentId=array();
        foreach ($categoryArrangeData as $categoryObject) {
            $categoryArrangedDataWithParentId[$categoryObject[self::PARENT_ID]][]=$categoryObject;
        }
        $categoryData=array(self::CATEGORY_PARENT_OBJECT=>$categoryArrangedDataWithParentId, self::CATEGORY_DATA_OBJECT=>$categoryArrangeData);
        return $categoryData;
    }

    public function getAutoCompleteCategoryDataObject($merchantType)
    {
        $categoryArrangeData = $this->getCategoryTypes($merchantType);
        $autoCompleteArray = $this->createAutoCompletePath($categoryArrangeData);
        return $autoCompleteArray;
    }

    public function createAutoCompletePath($parentObject)
    {
        $treeStructure=array();
        foreach ($parentObject as $keyObject=>$lengthObject) {
            $lengthObject[self::NODE_PATH_TEXT]=$this->getRootPath($parentObject,$lengthObject);
            $treeStructure[]=$lengthObject;
        }
        return $treeStructure;
    }

    public function getRootPath($parentObject,$nodeObject)
    {
        if($nodeObject[self::PARENT_ID] != 0)
        {
            $position = $this->searchForId($nodeObject[self::PARENT_ID],$parentObject);
            $nodeObject[self::NODE_PATH_TEXT] = $this->getRootPath($parentObject,$parentObject[$position]).self::PATH_SLASH_VALUE.$nodeObject[self::TREE_ID];
        } else {
            $nodeObject[self::NODE_PATH_TEXT] = $nodeObject[self::PARENT_ID].self::PATH_SLASH_VALUE.$nodeObject[self::TREE_ID];
        }
        return $nodeObject[self::NODE_PATH_TEXT];
    }

    public function searchForId($id, $array) {
       foreach ($array as $key => $val) {
           if ($val[self::TREE_ID] === $id) {
               return $key;
           }
       }
       return null;
    }

    public function createTree(&$listData, $parentObject)
    {
        $treeStructure=array();
        foreach ($parentObject as $keyObject=>$lengthObject) {
            if (isset($listData[$lengthObject[self::TREE_ID]])) {
                $lengthObject[self::TREE_ITEMS]=$this->createTree($listData, $listData[$lengthObject[self::TREE_ID]]);
            }
            $treeStructure[]=$lengthObject;
        }
        return $treeStructure;
    }

    public function removeElementWithValue($array, $key, $value){
        foreach($array as $subKey => $subArray){
          if($subArray[$key] != $value){
               unset($array[$subKey]);
          }
        }
        return $array;
    }
}
