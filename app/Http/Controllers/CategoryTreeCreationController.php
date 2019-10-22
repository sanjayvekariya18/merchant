<?php

namespace App\Http\Controllers;

use App\Helpers\CategoryTreeManager;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use App\Merchant_type;
use Redirect;
use Session;

/**
 * Class Account_typeController.
 *
 * @author  The scaffold-interface created at 2018-02-18 01:01:05pm
 * @link  https://github.com/amranidev/scaffold-interface
 */
class CategoryTreeCreationController extends Controller
{
    const BOLD_TEXT_TAG = "<b>";
    const CATEGORY_PARENT_ID = 'parent_id';
    const JSON_STORAGE_FOLDER = '/utree/';
    const CATEGORY_PARENT_OBJECT = 'categoryParentObject';
    const CATEGORY_DATA_OBJECT = 'categoryDataObject';
    const CATEGORY_NOT_EXIST = "No Category exist for <b>";
    const CATEGORY_TREE_VALUE = "</b> Tree<br/><br/>";
    const CATEGORY_AUTOCOMPLETE_VALUE = "</b> AutoComplete<br/><br/>";
    const TREE_SUCCESS_MESSAGE = "</b> Tree Created<br/><br/>";
    const AUTOCOMPLETE_SUCCESS_MESSAGE = "</b> AutoComplete Created<br/><br/>";
    const CATEGORY_APPEND_JSON = 'Category.json';
    const AUTOCOMPLETE_APPEND_JSON = 'AutoComplete.json';
    
    public function createCategoryTree($merchantType)
    {
        $categoryTreeManager = new CategoryTreeManager();
        $categoryParenetChildObject= $categoryTreeManager->getCategoryDataObject($merchantType);
        $categoryParentObject=$categoryParenetChildObject[self::CATEGORY_PARENT_OBJECT];
        $categoryChildObject=$categoryParenetChildObject[self::CATEGORY_DATA_OBJECT];
        $categoryTreeObject=$categoryTreeManager->createTree($categoryParentObject, $categoryChildObject);
        $categoryTreeObject = $categoryTreeManager->removeElementWithValue($categoryTreeObject,self::CATEGORY_PARENT_ID,0);
        $categoryTreePath=storage_path().self::JSON_STORAGE_FOLDER.$merchantType.self::CATEGORY_APPEND_JSON;
        file_put_contents($categoryTreePath, json_encode(array_values($categoryTreeObject)));
        if(empty($categoryTreeObject))
        {
            return self::CATEGORY_NOT_EXIST.$merchantType.self::CATEGORY_TREE_VALUE;
        } else {
            return self::BOLD_TEXT_TAG.$merchantType.self::TREE_SUCCESS_MESSAGE;
        }
        

    }

    public function createCategoryAutoComplete($merchantType)
    {
        $categoryTreeManager = new CategoryTreeManager();
        $categoryAutoCompleteObject= $categoryTreeManager->getAutoCompleteCategoryDataObject($merchantType);
        $categoryAutoCompletePath=storage_path().self::JSON_STORAGE_FOLDER.$merchantType.self::AUTOCOMPLETE_APPEND_JSON;
        file_put_contents($categoryAutoCompletePath, json_encode($categoryAutoCompleteObject));
        if(empty($categoryAutoCompleteObject))
        {
            return self::CATEGORY_NOT_EXIST.$merchantType.self::CATEGORY_AUTOCOMPLETE_VALUE;
        } else {
            return self::BOLD_TEXT_TAG.$merchantType.self::AUTOCOMPLETE_SUCCESS_MESSAGE;
        }
    }

    public function allTreeAutoCompleteCategories()
    {
        $mechantTypes = Merchant_type::select('merchant_type.merchant_type_name')->get();
        foreach ($mechantTypes as $mechantTypeKey => $mechantTypeValue) {
            if(isset($mechantTypeValue->merchant_type_name) && !empty($mechantTypeValue->merchant_type_name))
            {
                echo $this->createCategoryTree($mechantTypeValue->merchant_type_name);
                echo $this->createCategoryAutoComplete($mechantTypeValue->merchant_type_name);
            }
            
        }
    }

    public function selectedCategoryTreeCreate(Request $request)
    {
        $selectedMerchantType = $request->merchantTypeList;
        $mechantTypeName = Merchant_type::select('merchant_type.merchant_type_name')
                            ->whereIn('merchant_type.merchant_type_id', $selectedMerchantType)->get();
        foreach ($mechantTypeName as $mechantTypeKey => $mechantTypeValue) {
            $this->createCategoryTree($mechantTypeValue->merchant_type_name);
            $this->createCategoryAutoComplete($mechantTypeValue->merchant_type_name);
        }
        $treeSuccessMessage = array("type" => "success","message" => "Tree Created successfully");
        return json_encode($treeSuccessMessage);
    }
}
