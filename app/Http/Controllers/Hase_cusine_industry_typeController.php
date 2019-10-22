<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\App;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Request as Requests;
use App\Http\Controllers\PermissionsController;
use App\Helpers\ConnectionManager;
use App\Merchant_retail_style_type;
use App\Merchant_retail_category_type;
use App\Merchant_retail_category_option;
use Amranidev\Ajaxis\Ajaxis;
use App\Merchant_type;
use App\Http\Traits\PermissionTrait;
use URL;
use DB;
use Session;
use Redirect;
use Auth;

/**
 * Class Hase_merchant_retail_style_typeController.
 *
 */
class Hase_cusine_industry_typeController extends PermissionsController
{
    use PermissionTrait;

    public function __construct()
    {
        parent::__construct();

        $connectionStatus = ConnectionManager::setDbConfig('Hase_cuisine_types');

        if ($connectionStatus['type'] === "error") {
            Session::flash('type', $connectionStatus['type']);
            Session::flash('msg', $connectionStatus['message']);
            return Redirect::back()->send();
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return  \Illuminate\Http\Response
     */
    public function index()
    {
        if($this->permissionDetails('Hase_retail_style_type','access') || $this->permissionDetails('Hase_retail_category_type','access') ){
            $stylepermissions = $this->getPermission('Hase_retail_style_type');
            $categorypermissions = $this->getPermission('Hase_retail_category_type');
            $categoryoptionpermissions = $this->getPermission('Hase_retail_category_option');
            if(Requests::segment(1) === "hase_industry_types") {
                $merchantType = 2;
                $labels = array("Industries","Industry","Shop");
            } else {
                $merchantType = 8;
                $labels = array("Cuisines","Cuisine","Restaurants");
            }
            
            $hase_merchant_retail_style_types = Merchant_retail_style_type::
                select('merchant_retail_style_type.*','identity_merchant_retail_style_type.identity_name as style_name','identity_merchant_retail_style_type.identity_logo as style_image')
                ->join('identity_merchant_retail_style_type','identity_merchant_retail_style_type.identity_id','=','merchant_retail_style_type.identity_id')
                ->where('merchant_type_id','=',$merchantType)
                ->orderBy('style_name','asc')
                ->get();

            $retailCategoryCount = Merchant_retail_category_type::
                select('merchant_retail_category_type.*')
                ->where('merchant_retail_category_type.merchant_type_id','=',$merchantType)->count();

            $hase_merchant_retail_category_types = Merchant_retail_category_type::
                select('merchant_retail_category_type.*','identity_merchant_retail_category_type.identity_name as category_name','identity_merchant_retail_category_type.identity_logo as category_image')
                ->join('identity_merchant_retail_category_type','identity_merchant_retail_category_type.identity_id','=','merchant_retail_category_type.identity_id')
                ->where('merchant_retail_category_type.merchant_type_id','=',$merchantType)
                ->orderBy('category_name','asc')
                ->get();

            $hase_merchant_retail_category_options = Merchant_retail_category_option::
                select('merchant_retail_category_option.*','identity_merchant_retail_category_option.identity_name as option_name','identity_merchant_retail_category_option.identity_logo as option_image','identity_merchant_retail_category_option.identity_logo_compact as option_image_compact')
                ->join('identity_merchant_retail_category_option','identity_merchant_retail_category_option.identity_id','=','merchant_retail_category_option.identity_id')
                ->where('merchant_retail_category_option.merchant_type_id','=',$merchantType)->get();

            return view('hase_cusine_industry_type.index',compact('hase_merchant_retail_style_types','hase_merchant_retail_category_types','hase_merchant_retail_category_options','labels','stylepermissions','categorypermissions','categoryoptionpermissions','merchantType','retailCategoryCount'));
        } else{
            return Redirect::back()->with('message', 'You are not authorized to use this functionality!');
        }
    }

    public function getCategoryType(Request $request) {
        $currentPageId = $request->page_id;
        if(Requests::segment(1) === "hase_industry_types") {
            $merchantType = 2;
            $labels = array("Industries","Industry","Shop");
        } else {
            $merchantType = 8;
            $labels = array("Cuisines","Cuisine","Restaurants");
        }
        $categoryPermissions = $this->getPermission('Hase_retail_category_type');
        $categoryTypeOffset = ($currentPageId-1) * 25;
        $hase_merchant_retail_category_types = Merchant_retail_category_type::
            select('merchant_retail_category_type.*','identity_merchant_retail_category_type.identity_name as category_name','identity_merchant_retail_category_type.identity_logo as category_image')
            ->join('identity_merchant_retail_category_type','identity_merchant_retail_category_type.identity_id','=','merchant_retail_category_type.identity_id')
            ->where('merchant_retail_category_type.merchant_type_id','=',$merchantType)
            ->orderBy('merchant_retail_category_type.category_type_id','desc')
            ->offset($categoryTypeOffset)->limit(25)->get();

        $categoryTypeHtml = '';
        foreach($hase_merchant_retail_category_types as $hase_merchant_retail_category_type) {
            $categoryTypeHtml.= '<tr id="'.$hase_merchant_retail_category_type->category_type_id.'">
                <td>';
            if(in_array('manage', $categoryPermissions)) {
                $categoryTypeHtml.= '<a href="#" data-url="'.url("hase_retail_category_type").'/'.$hase_merchant_retail_category_type->category_type_id.'/update" type-id="'.$hase_merchant_retail_category_type->category_type_id.'" class="editCategory">
                        <i class="fa fa-fw fa-pencil text-primary actions_icon" title="Edit Category"></i>
                    </a>';
                $categoryTypeHtml.= '<a href="#" data-url="'.url("hase_retail_category_type").'/'.$hase_merchant_retail_category_type->category_type_id.'/delete" type-id="'.$hase_merchant_retail_category_type->category_type_id.'" class="deleteCategory">
                        <i class="fa fa-fw fa-times text-danger actions_icon" title="Delete Category"></i>
                    </a>';    
            }
            $categoryTypeHtml.= '</td><td class="type_id">'.$hase_merchant_retail_category_type->category_type_id.'</td>
                <td class="category_name">'.$hase_merchant_retail_category_type->category_name.'</td><td class="parent_id">';
            foreach($hase_merchant_retail_category_types as $hase_merchant_retail_category_type1) {
                if($hase_merchant_retail_category_type->parent_id === $hase_merchant_retail_category_type1->category_type_id) {
                    echo $hase_merchant_retail_category_type1->category_name;
                }
            }
            $categoryTypeHtml.= '</td>
                <td class="category_priority">'.$hase_merchant_retail_category_type->category_priority.'</td>
                <td class="category_image">';

            $categoryTypeImageUrl = parse_url($hase_merchant_retail_category_type->category_image);
            
            if(isset($categoryTypeImageUrl['scheme'])) {
                if($categoryTypeImageUrl['scheme'] === 'https' || $categoryTypeImageUrl['scheme'] === 'http') {
                   $categoryTypeHtml.= '<img src="'.$hase_merchant_retail_category_type->category_image.'" style="width: 80px; height: 40px;"/>';
                }
            }
            else {
                if(!empty($hase_merchant_retail_category_type->category_image) && is_file(env('image_dir_path').$hase_merchant_retail_category_type->category_image)) {   
                    $categoryTypeHtml.= '<img src="'.asset(env('image_dir_path').$hase_merchant_retail_category_type->category_image).'" style="width: 80px; height: 40px;"/>';
                }
                else {
                    $categoryTypeHtml.= '<img src="'.asset(env('image_dir_path').'no_photo.png').'" style="width: 80px; height: 40px;"/>';
                }
            }
            $categoryTypeHtml.= '</td></tr>';
        }
        return $categoryTypeHtml;
    }

    
}
