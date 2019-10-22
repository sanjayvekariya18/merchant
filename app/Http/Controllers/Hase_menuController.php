<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Request as Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\PermissionsController;
use App\Helpers\ConnectionManager;
use App\Http\Controllers\ProductApprovalController;

use Amranidev\Ajaxis\Ajaxis;
use URL;

use App\Menu;
use App\Menu_stage;
use App\Identity_menu;
use App\Identity_menu_stage;
use App\Mealtime;

use App\Merchant_retail_style_type;
use App\Merchant_retail_style_list;
use App\Merchant_retail_style_list_stage;

use App\Merchant_retail_category_list;
use App\Merchant_retail_category_option;
use App\Merchant_retail_category_option_list;
use App\Merchant_retail_category_list_stage;
use App\Merchant_retail_category_option_list_stage; 
use App\Merchant_retail_category_type;

use App\Menus_special;
use App\Menus_special_stage;
use App\location_list;
use App\City;
use App\Merchant;
use App\Postal;
use App\Hase_identity;
use App\Hase_identity_stage;
use App\Special;
use App\Special_stage;

use DB;
use Session;
use Illuminate\Support\Facades\Redirect;
use App\Http\Traits\PermissionTrait;

const MERCHANT_TABLE_IDENTITY_TYPE = 8;
const IDENTITY_PRODUCT_STAGE_TABLE_ID = 44;

/**
 * Class Hase_menuController.
 *
 * @author  The scaffold-interface created at 2017-03-03 09:44:44am
 * @link  https://github.com/amranidev/scaffold-interface
 */
class Hase_menuController extends PermissionsController
{   

    use PermissionTrait;
   
    public function __construct()
    {
        parent::__construct();

        $connectionStatus = ConnectionManager::setDbConfig('Hase_menu');

        if ($connectionStatus['type'] === "error") {
            Session::flash('type', $connectionStatus['type']);
            Session::flash('msg', $connectionStatus['message']);
            return Redirect::back()->send();
        }

        $this->request_table_live = 23;
        $this->request_table_stage = 43;

        $this->identity_request_table_live = 24;
        $this->identity_request_table_stage = 44;

        $this->special_request_table_live = 45;
        $this->special_request_table_stage = 46;

        $this->request_specials_child_table_live = 47;
        $this->request_specials_child_table_stage = 48;

        $this->request_style_list_live = 49;
        $this->request_style_list_stage = 50;

        $this->request_category_list_live = 51;
        $this->request_category_list_stage = 52;

        $this->option_request_table_live = 53;
        $this->option_request_table_stage = 54;

        $this->codeCounter = 0;
        $this->productApproval = new ProductApprovalController();
    }
    /**
     * Display a listing of the resource.
     *
     * @return  \Illuminate\Http\Response
     */
    public function index()
    {

        $haseMenuAceess = $this->permissionDetails('Hase_menu','access');

        if($haseMenuAceess) {
            $permissions = $this->getPermission('Hase_menu');
            $title = 'Index - hase_menu';
            $searchData = '';
            if(Requests::segment(1) === "hase_product"){
                $merchantType = 2;
                $location_id = 1;
                $labels = array("Products","Shop","Product");    
            }else{
                $merchantType = 8;
                $location_id = 2;
                $labels = array("Feature Dish/Menus","Restaurants","Feature Dish/Menu");
            }

            if($this->merchantId === 0 ) {
                
                $hase_menus = Menu::
                    distinct('product.product_id')
                    ->join('location_list','location_list.list_id','=','product.location_id')
                    ->join('merchant','merchant.merchant_id','=','location_list.identity_id')
                    ->leftjoin('product_specials','product_specials.product_id','=','product.product_id')
                    ->leftjoin('special', function($join)
                    {
                      $join->on('special.special_id','=','product_specials.special_id')->where('special.special_status', 1);
                    })                    
                    ->leftjoin('merchant_retail_category_list','merchant_retail_category_list.product_id','=','product.product_id')
                    ->leftjoin('merchant_retail_category_type','merchant_retail_category_type.category_type_id','=','merchant_retail_category_list.category_type_id')
                    ->join('identity_product','identity_product.identity_id','=','product.identity_id')
                    ->join('identity_merchant','identity_merchant.identity_id','=','merchant.identity_id')
                    ->join('postal','postal.postal_id','=','location_list.postal_id')
                    ->join('identity_postal','identity_postal.identity_id','=','postal.identity_id')
                    ->leftjoin('identity_merchant_retail_category_type','identity_merchant_retail_category_type.identity_id','=','merchant_retail_category_type.identity_id')                  
                    ->where('merchant.merchant_type_id','=',$merchantType)
                    ->where('location_list.identity_table_id','=',MERCHANT_TABLE_IDENTITY_TYPE)
                    ->groupBy('product.product_id')
                    ->select('product.product_id','identity_product.identity_name as product_name','product.base_price','identity_product.identity_logo as product_image','identity_product.identity_logo_compact as product_image_compact','product.product_status','special.special_begin_date','special.special_expire_date','special.special_price','special.special_image','special.special_image_compact','identity_merchant.identity_name as merchant_name',DB::raw('(CASE WHEN (postal.postal_premise != "" and postal.postal_subpremise != "") THEN CONCAT(postal.postal_premise,", ",SUBSTR(postal.postal_subpremise,1,10))
                              WHEN (postal.postal_premise != "") THEN postal.postal_premise                              
                              WHEN (postal.postal_subpremise !="") THEN SUBSTR(postal.postal_subpremise,1,10)
                              ELSE CONCAT(postal.postal_street_number," ",postal.postal_route)   
                        END) as location_name'),'identity_merchant_retail_category_type.identity_name as category_name')
                    ->paginate(25);
                        
                        
            } else {
                if($this->roleId === 4)
                {
                    $hase_menus = Menu::
                    distinct('product.product_id')
                    ->join('location_list','location_list.list_id','=','product.location_id')
                    ->join('merchant','merchant.merchant_id','=','location_list.identity_id')
                    ->leftjoin('product_specials','product_specials.product_id','=','product.product_id')
                    ->leftjoin('special', function($join)
                    {
                      $join->on('special.special_id','=','product_specials.special_id')->where('special.special_status', 1);
                    })                    
                    ->leftjoin('merchant_retail_category_list','merchant_retail_category_list.product_id','=','product.product_id')
                    ->leftjoin('merchant_retail_category_type','merchant_retail_category_type.category_type_id','=','merchant_retail_category_list.category_type_id')
                    ->join('identity_product','identity_product.identity_id','=','product.identity_id')
                    ->join('identity_merchant','identity_merchant.identity_id','=','merchant.identity_id')
                    ->join('postal','postal.postal_id','=','location_list.postal_id')
                    ->join('identity_postal','identity_postal.identity_id','=','postal.identity_id')
                    ->leftjoin('identity_merchant_retail_category_type','identity_merchant_retail_category_type.identity_id','=','merchant_retail_category_type.identity_id')                  
                    ->where('merchant.merchant_id','=',$this->merchantId)
                    ->where('location_list.identity_table_id','=',MERCHANT_TABLE_IDENTITY_TYPE)
                    ->groupBy('product.product_id')
                    ->select('product.product_id','identity_product.identity_name as product_name','product.base_price','identity_product.identity_logo as product_image','identity_product.identity_logo_compact as product_image_compact','product.product_status','special.special_begin_date','special.special_expire_date','special.special_price','special.special_image','special.special_image_compact','identity_merchant.identity_name as merchant_name',DB::raw('(CASE WHEN (postal.postal_premise != "" and postal.postal_subpremise != "") THEN CONCAT(postal.postal_premise,", ",SUBSTR(postal.postal_subpremise,1,10))
                              WHEN (postal.postal_premise != "") THEN postal.postal_premise                              
                              WHEN (postal.postal_subpremise !="") THEN SUBSTR(postal.postal_subpremise,1,10)
                              ELSE CONCAT(postal.postal_street_number," ",postal.postal_route)   
                        END) as location_name'),'identity_merchant_retail_category_type.identity_name as category_name')
                    ->paginate(25);
                    
                } else {

                    $hase_menus = Menu::
                    distinct('product.product_id')
                    ->join('location_list','location_list.list_id','=','product.location_id')
                    ->join('merchant','merchant.merchant_id','=','location_list.identity_id')
                    ->leftjoin('product_specials','product_specials.product_id','=','product.product_id')
                    ->leftjoin('special', function($join)
                    {
                      $join->on('special.special_id','=','product_specials.special_id')->where('special.special_status', 1);
                    })                    
                    ->leftjoin('merchant_retail_category_list','merchant_retail_category_list.product_id','=','product.product_id')
                    ->leftjoin('merchant_retail_category_type','merchant_retail_category_type.category_type_id','=','merchant_retail_category_list.category_type_id')
                    ->join('identity_product','identity_product.identity_id','=','product.identity_id')
                    ->join('identity_merchant','identity_merchant.identity_id','=','merchant.identity_id')
                    ->join('postal','postal.postal_id','=','location_list.postal_id')
                    ->join('identity_postal','identity_postal.identity_id','=','postal.identity_id')
                    ->leftjoin('identity_merchant_retail_category_type','identity_merchant_retail_category_type.identity_id','=','merchant_retail_category_type.identity_id')                  
                    ->where('merchant.merchant_id','=',$this->merchantId)
                    ->where('location_list.list_id','=',$this->locationId)
                    ->where('location_list.identity_table_id','=',MERCHANT_TABLE_IDENTITY_TYPE)
                    ->groupBy('product.product_id')
                    ->select('product.product_id','identity_product.identity_name as product_name','product.base_price','identity_product.identity_logo as product_image','identity_product.identity_logo_compact as product_image_compact','product.product_status','special.special_begin_date','special.special_expire_date','special.special_price','special.special_image','special.special_image_compact','identity_merchant.identity_name as merchant_name',DB::raw('(CASE WHEN (postal.postal_premise != "" and postal.postal_subpremise != "") THEN CONCAT(postal.postal_premise,", ",SUBSTR(postal.postal_subpremise,1,10))
                              WHEN (postal.postal_premise != "") THEN postal.postal_premise                              
                              WHEN (postal.postal_subpremise !="") THEN SUBSTR(postal.postal_subpremise,1,10)
                              ELSE CONCAT(postal.postal_street_number," ",postal.postal_route)   
                        END) as location_name'),'identity_merchant_retail_category_type.identity_name as category_name')
                    ->paginate(25);
                }
            }

            return view('hase_menu.index',compact('hase_menus','title','labels','permissions','searchData'));
        } else {
            return Redirect::back()->with('message', 'You are not authorized to use this functionality!');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return  \Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        $searchData = trim($request->search_data);
        $merchantId = session()->has('merchantId') ? session()->get('merchantId') : '';
        $roleId = session()->has('role') ? session()->get('role') : '';
        $locationId = session()->has('locationId') ? session()->get('locationId') :"";
        $merchant_table_identity_type = 8;
        if(!empty($searchData)) {
            if(Requests::segment(1) === "hase_product") {
                $merchantType = 2;
                $location_id = 1;
                $labels = array("Products","Shop","Product");
            } else {
                $merchantType = 8;
                $location_id = 2;
                $labels = array("Feature Dish/Menus","Restaurants","Feature Dish/Menu");
            }

            if($merchantId === 0 ) {

                  $hase_menus = Menu::
                      distinct('product.product_id')
                      ->join('location_list','location_list.list_id','=','product.location_id')
                      ->join('merchant','merchant.merchant_id','=','location_list.identity_id')
                      ->leftjoin('product_specials','product_specials.product_id','=','product.product_id')
                      ->leftjoin('special', function($join)
                      {
                        $join->on('special.special_id','=','product_specials.special_id')->where('special.special_status', 1);
                      })                    
                      ->leftjoin('merchant_retail_category_list','merchant_retail_category_list.product_id','=','product.product_id')
                      ->leftjoin('merchant_retail_category_type','merchant_retail_category_type.category_type_id','=','merchant_retail_category_list.category_type_id')
                      ->join('identity_product','identity_product.identity_id','=','product.identity_id')
                      ->join('identity_merchant','identity_merchant.identity_id','=','merchant.identity_id')
                      ->join('postal','postal.postal_id','=','location_list.postal_id')
                      ->join('identity_postal','identity_postal.identity_id','=','postal.identity_id')
                      ->leftjoin('identity_merchant_retail_category_type','identity_merchant_retail_category_type.identity_id','=','merchant_retail_category_type.identity_id')                  
                      ->where(function ($queryData) use($searchData) {
                        $queryData->where('identity_postal.identity_name', 'LIKE', '%' . $searchData . '%')
                            ->OrWhere('identity_merchant.identity_name', 'LIKE', '%' . $searchData . '%');
                        })
                      ->where('merchant.merchant_type_id','=',$merchantType)
                      ->where('location_list.identity_table_id','=',$merchant_table_identity_type)
                      ->groupBy('product.product_id')
                      ->select('product.product_id','identity_product.identity_name as product_name','product.base_price','identity_product.identity_logo as product_image','identity_product.identity_logo_compact as product_image_compact','product.product_status','special.special_begin_date','special.special_expire_date','special.special_price','special.special_image','special.special_image_compact','identity_merchant.identity_name as merchant_name',DB::raw('(CASE WHEN (postal.postal_premise != "" and postal.postal_subpremise != "") THEN CONCAT(postal.postal_premise,", ",SUBSTR(postal.postal_subpremise,1,10))
                              WHEN (postal.postal_premise != "") THEN postal.postal_premise                              
                              WHEN (postal.postal_subpremise !="") THEN SUBSTR(postal.postal_subpremise,1,10)
                              ELSE CONCAT(postal.postal_street_number," ",postal.postal_route)   
                        END) as location_name'),'identity_merchant_retail_category_type.identity_name as category_name')
                      ->paginate(25);   

            } else {
                if($roleId === 4)
                {

                    $hase_menus = Menu::
                      distinct('product.product_id')
                      ->join('location_list','location_list.list_id','=','product.location_id')
                      ->join('merchant','merchant.merchant_id','=','location_list.identity_id')
                      ->leftjoin('product_specials','product_specials.product_id','=','product.product_id')
                      ->leftjoin('special', function($join)
                      {
                        $join->on('special.special_id','=','product_specials.special_id')->where('special.special_status', 1);
                      })                    
                      ->leftjoin('merchant_retail_category_list','merchant_retail_category_list.product_id','=','product.product_id')
                      ->leftjoin('merchant_retail_category_type','merchant_retail_category_type.category_type_id','=','merchant_retail_category_list.category_type_id')
                      ->join('identity_product','identity_product.identity_id','=','product.identity_id')
                      ->join('identity_merchant','identity_merchant.identity_id','=','merchant.identity_id')
                      ->join('postal','postal.postal_id','=','location_list.postal_id')
                      ->join('identity_postal','identity_postal.identity_id','=','postal.identity_id')
                      ->leftjoin('identity_merchant_retail_category_type','identity_merchant_retail_category_type.identity_id','=','merchant_retail_category_type.identity_id')                  
                      ->where(function ($queryData) use($searchData) {
                        $queryData->where('identity_postal.identity_name', 'LIKE', '%' . $searchData . '%')
                            ->OrWhere('identity_merchant.identity_name', 'LIKE', '%' . $searchData . '%');
                        })
                      ->where('merchant.merchant_id','=',$merchantId)
                      ->where('location_list.identity_table_id','=',$merchant_table_identity_type)
                      ->groupBy('product.product_id')
                      ->select('product.product_id','identity_product.identity_name as product_name','product.base_price','identity_product.identity_logo as product_image','identity_product.identity_logo_compact as product_image_compact','product.product_status','special.special_begin_date','special.special_expire_date','special.special_price','special.special_image','special.special_image_compact','identity_merchant.identity_name as merchant_name',DB::raw('(CASE WHEN (postal.postal_premise != "" and postal.postal_subpremise != "") THEN CONCAT(postal.postal_premise,", ",SUBSTR(postal.postal_subpremise,1,10))
                              WHEN (postal.postal_premise != "") THEN postal.postal_premise                              
                              WHEN (postal.postal_subpremise !="") THEN SUBSTR(postal.postal_subpremise,1,10)
                              ELSE CONCAT(postal.postal_street_number," ",postal.postal_route)   
                        END) as location_name'),'identity_merchant_retail_category_type.identity_name as category_name')
                      ->paginate(25);                  

                } else {
                    
                    $hase_menus = Menu::
                      distinct('product.product_id')
                      ->join('location_list','location_list.list_id','=','product.location_id')
                      ->join('merchant','merchant.merchant_id','=','location_list.identity_id')
                      ->leftjoin('product_specials','product_specials.product_id','=','product.product_id')
                      ->leftjoin('special', function($join)
                      {
                        $join->on('special.special_id','=','product_specials.special_id')->where('special.special_status', 1);
                      })                    
                      ->leftjoin('merchant_retail_category_list','merchant_retail_category_list.product_id','=','product.product_id')
                      ->leftjoin('merchant_retail_category_type','merchant_retail_category_type.category_type_id','=','merchant_retail_category_list.category_type_id')
                      ->join('identity_product','identity_product.identity_id','=','product.identity_id')
                      ->join('identity_merchant','identity_merchant.identity_id','=','merchant.identity_id')
                      ->join('postal','postal.postal_id','=','location_list.postal_id')
                      ->join('identity_postal','identity_postal.identity_id','=','postal.identity_id')
                      ->leftjoin('identity_merchant_retail_category_type','identity_merchant_retail_category_type.identity_id','=','merchant_retail_category_type.identity_id')                  
                      ->where(function ($queryData) use($searchData) {
                        $queryData->where('identity_postal.identity_name', 'LIKE', '%' . $searchData . '%')
                            ->OrWhere('identity_merchant.identity_name', 'LIKE', '%' . $searchData . '%');
                        })
                      ->where('merchant.merchant_id','=',$merchantId)
                      ->where('location_list.list_id','=',$locationId)
                      ->where('location_list.identity_table_id','=',$merchant_table_identity_type)
                      ->groupBy('product.product_id')
                      ->select('product.product_id','identity_product.identity_name as product_name','product.base_price','identity_product.identity_logo as product_image','identity_product.identity_logo_compact as product_image_compact','product.product_status','special.special_begin_date','special.special_expire_date','special.special_price','special.special_image','special.special_image_compact','identity_merchant.identity_name as merchant_name',DB::raw('(CASE WHEN (postal.postal_premise != "" and postal.postal_subpremise != "") THEN CONCAT(postal.postal_premise,", ",SUBSTR(postal.postal_subpremise,1,10))
                              WHEN (postal.postal_premise != "") THEN postal.postal_premise                              
                              WHEN (postal.postal_subpremise !="") THEN SUBSTR(postal.postal_subpremise,1,10)
                              ELSE CONCAT(postal.postal_street_number," ",postal.postal_route)   
                        END) as location_name'),'identity_merchant_retail_category_type.identity_name as category_name')
                      ->paginate(25);
                }
            }
            
            $pagination = $hase_menus->appends(array(
                'search_data' => $searchData 
            ));
            $permissions = PermissionTrait::getPermission('Hase_menu');
            if (count($hase_menus) > 0) {
                return view('hase_menu.index', compact('hase_menus','title','labels','permissions','searchData'))->withDetails($hase_menus)->withQuery($searchData);
            }
            return view('hase_menu.index', compact('hase_menus','permissions','labels','searchData'))->withMessage('No Details found. Try to search again !');
        } else {
            return redirect(Requests::segment(1));
        }
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return  \Illuminate\Http\Response
     */
    public function create()
    {        
        $haseMenuAceess = $this->permissionDetails('Hase_menu','add');
        if($haseMenuAceess) {
            
            $title = 'Create - hase_menu';
            $merchantId =$this->merchantId;

            if(Requests::segment(1) === "hase_product"){
                $merchantType = 2;
                $labels = array("Products","Shop","Product");
                $styleLabels = array("Industries","Industry","Merchants"); 
            }else{
                $merchantType = 8;
                $labels = array("Feature Dish/Menus","Restaurants","Feature Dish/Menu");
                $styleLabels = array("Cuisines","Cuisine","Merchants");
            }

            if($this->merchantId === 0 ) {


                /* ------------------------ Main Menu ---------------------------*/
                $hase_merchants = Merchant::
                    distinct()
                    ->select('merchant_id','merchant_status','identity_merchant.identity_name as merchant_name','identity_merchant.identity_email as merchant_email','identity_merchant.identity_telephone as merchant_telephone','identity_merchant.identity_website as merchant_website','identity_merchant.identity_logo as merchant_logo','identity_merchant.identity_logo_compact as merchant_logo_compact')
                    ->join('identity_merchant','identity_merchant.identity_id','=','merchant.identity_id')
                    ->where('merchant_type_id',$merchantType)
                    ->get();

                $hase_locations = array();
                
                $hase_categories =  Merchant_retail_category_type::
                        select('category_type_id','identity_merchant_retail_category_type.identity_name as category_name')
                        ->join('identity_merchant_retail_category_type','identity_merchant_retail_category_type.identity_id','=','merchant_retail_category_type.identity_id')
                        ->where('merchant_retail_category_type.merchant_type_id',$merchantType)
                        ->get();

            } else {

                $hase_locations = Postal::distinct()
                    ->select('location_list.postal_id as location_id',DB::raw('(CASE WHEN (postal.postal_premise != "" and postal.postal_subpremise != "") THEN CONCAT(postal.postal_premise,", ",SUBSTR(postal.postal_subpremise,1,10))
                              WHEN (postal.postal_premise != "") THEN postal.postal_premise                              
                              WHEN (postal.postal_subpremise !="") THEN SUBSTR(postal.postal_subpremise,1,10)
                              ELSE CONCAT(postal.postal_street_number," ",postal.postal_route)   
                        END) as location_name'))
                    ->join('location_list','location_list.postal_id','=','postal.postal_id')
                    ->join('merchant','merchant.identity_id','=','location_list.identity_id')
                    ->where('location_list.identity_table_id','=',MERCHANT_TABLE_IDENTITY_TYPE)
                    ->where('merchant.merchant_id','=',$this->merchantId)
                    ->get();  

                $hase_categories =  Merchant_retail_category_type::
                        select('category_type_id','identity_merchant_retail_category_type.identity_name as category_name')
                        ->join('identity_merchant_retail_category_type','identity_merchant_retail_category_type.identity_id','=','merchant_retail_category_type.identity_id')
                        ->where('merchant_retail_category_type.merchant_type_id',$merchantType)
                        ->get();

                $styleTypes = Merchant::
                        distinct('style_type_id','identity_merchant_retail_style_type.identity_name as style_name')
                        ->join('merchant_type_list','merchant_type_list.merchant_id','=','merchant.merchant_id')
                        ->join('merchant_type','merchant_type.merchant_type_id','=','merchant_type_list.merchant_type_id')
                        ->join('merchant_retail_style_type','merchant_retail_style_type.merchant_type_id','=','merchant_type.merchant_root_id')
                        ->join('identity_merchant_retail_style_type','identity_merchant_retail_style_type.identity_id','=','merchant_retail_style_type.identity_id')
                        ->where('merchant.merchant_id',$this->merchantId)
                        ->get();

                $categoryTypes = Merchant_retail_category_type::
                        select('category_type_id','identity_merchant_retail_category_type.identity_name as category_name')
                        ->join('identity_merchant_retail_category_type','identity_merchant_retail_category_type.identity_id','=','merchant_retail_category_type.identity_id')
                        ->where('merchant_retail_category_type.merchant_type_id',$merchantType)
                        ->get();

            }
            
            $hase_options = Merchant_retail_category_option::
                        select('merchant_retail_category_option.*','identity_merchant_retail_category_option.identity_name as option_name')
                        ->join('identity_merchant_retail_category_option','identity_merchant_retail_category_option.identity_id','=','merchant_retail_category_option.identity_id')
                        ->get();

            return view('hase_menu.create',compact('title','hase_categories','hase_locations','merchantId','labels','styleLabels','hase_merchants','merchantType','styleTypes','categoryTypes','hase_options'));
        } else {
            return Redirect::back()->with('message', 'You are not authorized to use this functionality!');
        }
    }

    public function generateCode($orignalCodeName)
    {
        
        if($this->codeCounter === 0){
            $codeName = $orignalCodeName;
        }else{
            $codeName = $orignalCodeName.$this->codeCounter;
        }

        $code_exist_live = Identity_menu::select('identity_code')
                                        ->where('identity_code',$codeName);

        $code_exist_stage = Identity_menu_stage::select('identity_code')
                                        ->join('approval','approval.request_table_stage_primary_id','identity_product_stage.identity_id')
                                        ->where('approval.request_table_stage',IDENTITY_PRODUCT_STAGE_TABLE_ID)
                                        ->where('identity_product_stage.identity_code',$codeName);                 

        $results = $code_exist_live->union($code_exist_stage)->get();  

        if(!count($results)){
            
            return $codeName;

        }else{
            $this->codeCounter = $this->codeCounter + 1;
            return $this->generateCode($orignalCodeName);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param    \Illuminate\Http\Request  $request
     * @return  \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        if($this->roleId === 1)
        {
            $hase_menu = new Menu();
            $hase_identity = new Identity_menu();

        } else {
            $hase_menu = new Menu_stage();
            $hase_identity = new Identity_menu_stage();

            $hase_menu->staff_id = $this->staffId;
            $hase_identity->staff_id = $this->staffId;
        }

        $merchantId = ($this->merchantId != 0) ? $this->merchantId : $request->merchant_id;
        $locationId = $request->location_id;

        if(Requests::segment(1) === "hase_product"){
            $updatedName = 'Product';
        } else {
            $updatedName = 'Menu';
        }

        //--------------------- Menu Insertion ---------------------//

        $hase_menu->location_id = $locationId;

        // identity schema data

        $productCode = strtolower(substr(preg_replace('/\s+/', '', $request->product_name),0,16));
        $codeName = $this->generateCode($productCode);

        $hase_identity->identity_code = $codeName;
        $hase_identity->identity_name = $request->product_name;
        $hase_identity->identity_description = $request->product_description;

        // product schema data

        $hase_menu->base_price = $request->base_price;
        $hase_menu->category_type_id = $request->product_category_id;
        
        $hase_merchant = Merchant::
                            select('merchant.merchant_id','identity_merchant.identity_name as merchant_name')
                            ->join('identity_merchant','identity_merchant.identity_id','=','merchant.identity_id')
                            ->where('merchant.merchant_id',$merchantId)
                            ->get()->first();

        $hase_location = Location_list::
                            select('location_list.list_id as location_id',DB::raw('(CASE WHEN (postal.postal_premise != "" and postal.postal_subpremise != "") THEN CONCAT(postal.postal_premise,", ",SUBSTR(postal.postal_subpremise,1,10))
                              WHEN (postal.postal_premise != "") THEN postal.postal_premise                              
                              WHEN (postal.postal_subpremise !="") THEN SUBSTR(postal.postal_subpremise,1,10)
                              ELSE CONCAT(postal.postal_street_number," ",postal.postal_route)   
                        END) as location_name'))
                            ->join('postal','postal.postal_id','=','location_list.postal_id')
                            ->where('location_list.list_id','=',$locationId)
                            ->get()->first();

        $merchantDirName = md5($hase_merchant->merchant_name);
        $locationDirName = md5($hase_merchant->merchant_name.$hase_location->location_name);

        if($request->live_image_url)
        {
            $hase_identity->identity_logo = $request->live_image_url;
        } else {
            if($request->file('product_image')){

                $publicDirPath = public_path(env('image_dir_path'));
                
                if($this->roleId === 1){
                    $imageDirPath = "merchant/$merchantDirName/location/$locationDirName/";
                }else{
                    $imageDirPath = "merchant_stage/$merchantDirName/location_stage/$locationDirName/";
                }

                $absoluteImageDirPath = $publicDirPath.$imageDirPath;

                if(!file_exists($absoluteImageDirPath)){
                    mkdir($absoluteImageDirPath,0777,true);
                }

                $imageName = $request->file('product_image')->getClientOriginalName();
                $imageArray = explode('.', $imageName);
                $hashImageName = md5($hase_merchant->merchant_name.$hase_location->location_name.$hase_menu->product_name.$imageName).".".$imageArray[1];
                $request->file('product_image')->move($absoluteImageDirPath,$hashImageName);
                $hase_identity->identity_logo = "$imageDirPath$hashImageName";
            }
        }

        if($request->live_image_compact_url)
        {
            $hase_identity->identity_logo_compact = $request->live_image_compact_url;
        } else {
            if($request->file('product_image_compact')){

                $publicDirPath = public_path(env('image_dir_path'));
                
                if($this->roleId === 1){
                    $imageDirPath = "merchant/$merchantDirName/location/$locationDirName/";
                }else{
                    $imageDirPath = "merchant_stage/$merchantDirName/location_stage/$locationDirName/";
                }

                $absoluteImageDirPath = $publicDirPath.$imageDirPath;

                if(!file_exists($absoluteImageDirPath)){
                    mkdir($absoluteImageDirPath,0777,true);
                }

                $imageName = $request->file('product_image_compact')->getClientOriginalName();
                $imageArray = explode('.', $imageName);
                $hashImageName = md5($hase_merchant->merchant_name.$hase_location->location_name.$hase_menu->product_name.$imageName).".".$imageArray[1];
                $request->file('product_image_compact')->move($absoluteImageDirPath,$hashImageName);
                $hase_identity->identity_logo_compact = "$imageDirPath$hashImageName";
                
            }
        }

        // Insert record in identity schema 
        $hase_identity->save();
        $identityID = $hase_identity->identity_id;

        $hase_menu->identity_id = $identityID;
        $hase_menu->save();

        $productId = $hase_menu->product_id;
        
        if(isset($request->special_status))
        {
            if($this->roleId === 1)
            {
                $hase_menus_specials = new Menus_special;
                $hase_special = new Special;

            } else {
                $hase_menus_specials = new Menus_special_stage;
                $hase_special = new Special_stage;

                $hase_menus_specials->staff_id = $this->staffId;
                $hase_special->staff_id = $this->staffId;
            }
            
            $hase_menus_specials->location_id = $locationId;
            $hase_menus_specials->product_id = $productId;

            // special schema data

            $hase_special->special_begin_date = (!is_null($request->start_date))?
                                            str_replace("-","",date('Y-m-d',strtotime($request->start_date))) : 0;
            $hase_special->special_expire_date = (!is_null($request->end_date))?
                                            str_replace("-","",date('Y-m-d',strtotime($request->end_date))) : 0;

            $hase_special->special_price = $request->special_price;
            $hase_special->special_details = $request->special_details;
            $hase_special->special_terms = $request->special_terms;
            $hase_special->special_status = $request->special_status;
            $hase_special->special_url = $request->special_url;
            

            if($request->live_special_image_url)
            {
                $hase_special->special_image = $request->live_special_image_url;
            } else {
                if($request->file('special_image')){

                    $publicDirPath = public_path(env('image_dir_path'));
                
                    if($this->roleId === 1){
                        $imageDirPath = "merchant/$merchantDirName/location/$locationDirName/";
                    }else{
                        $imageDirPath = "merchant_stage/$merchantDirName/location_stage/$locationDirName/";
                    }

                    $absoluteImageDirPath = $publicDirPath.$imageDirPath;

                    if(!file_exists($absoluteImageDirPath)){
                        mkdir($absoluteImageDirPath,0777,true);
                    }

                    $imageName = $request->file('special_image')->getClientOriginalName();
                    $imageArray = explode('.', $imageName);
                    $hashImageName = md5($hase_merchant->merchant_name.$hase_location->location_name.$hase_menu->product_name.$imageName).".".$imageArray[1];
                    $request->file('special_image')->move($absoluteImageDirPath,$hashImageName);
                    $hase_special->special_image = "$imageDirPath$hashImageName";
                }
            }

            if($request->live_special_image_compact_url)
            {
                $hase_special->special_image_compact = $request->live_special_image_compact_url;
            } else {
                if($request->file('special_image_compact')){

                    $publicDirPath = public_path(env('image_dir_path'));
                
                    if($this->roleId === 1){
                        $imageDirPath = "merchant/$merchantDirName/location/$locationDirName/";
                    }else{
                        $imageDirPath = "merchant_stage/$merchantDirName/location_stage/$locationDirName/";
                    }

                    $absoluteImageDirPath = $publicDirPath.$imageDirPath;

                    if(!file_exists($absoluteImageDirPath)){
                        mkdir($absoluteImageDirPath,0777,true);
                    }

                    $imageName = $request->file('special_image_compact')->getClientOriginalName();
                    $imageArray = explode('.', $imageName);
                    $hashImageName = md5($hase_merchant->merchant_name.$hase_location->location_name.$hase_menu->product_name.$imageName).".".$imageArray[1];
                    $request->file('special_image_compact')->move($absoluteImageDirPath,$hashImageName);
                    $hase_special->special_image_compact = "$imageDirPath$hashImageName";
                }
            }

            $hase_special->save();
            $specialId = $hase_special->special_id;

            $hase_menus_specials->special_id = $specialId;
            $hase_menus_specials->save();
        }

       
        $staffUrl = "/hase_staff/".$this->staffId."/edit";
        $action = "added";

        if($this->roleId === 1)
        {   
            $approvalStatus = $this->productApproval->ApprovalActionStatusTransitionBySelf("change");

            $approvalCrudStatus = $this->productApproval->getApprovalCrudStatusId("insert");

            $updatedIdentityColumns = array('identity_name','identity_description','identity_logo','identity_image_compact');

            $parentApprovalData = $this->addAdminForApprove($identityID,$updatedIdentityColumns,$this->identity_request_table_live,$approvalStatus,$approvalCrudStatus,$merchantId,$locationId);

            $updatedMenuColumns = array('base_price','product_url','product_quantity_stock','product_quantity_minimum','product_quantity_reduce','product_status');

            $parentApprovalData = $this->addAdminForApprove($hase_menu->product_id,$updatedMenuColumns,$this->request_table_live,$approvalStatus,$approvalCrudStatus,$merchantId,$locationId);

            if($request->special_status)
            {
                $updatedSpecialColumns = array('special_begin_date','special_begin_date','special_price','special_status','special_details','special_terms','special_image','special_image_compact','special_url');

                $this->addAdminForApprove($specialId,$updatedSpecialColumns,$this->special_request_table_live,$approvalStatus,$approvalCrudStatus,$merchantId,$locationId);

                $updatedMenuSpecialColumns = array('priority');

                $this->addAdminForApprove($hase_menus_specials->product_special_id,$updatedMenuSpecialColumns,$this->request_specials_child_table_live,$approvalStatus,$approvalCrudStatus,$merchantId,$locationId,$parentApprovalData['approvalParentId'],$parentApprovalData['groupHash']);
            }

            $menuUrl = "/hase_menu/".$hase_menu->product_id."/edit";
            $message = "<a href='".URL::to($staffUrl)."'>".$this->staffName."</a> <strong>".$action."</strong> new ".$updatedName." <a href='".URL::to($menuUrl)."'> <strong>".$hase_menu->product_name."</strong></a>";
            PermissionTrait::addActivityLog($action,$message);
        } else {

            $approvalStatus = $this->productApproval->ApprovalActionStatusTransitionBySelf("change");

            $approvalCrudStatus = $this->productApproval->getApprovalCrudStatusId("insert");

            $updatedIdentityColumns = array('identity_name','identity_description','identity_logo','identity_logo_compact');

            $parentApprovalData = $this->addForApprove($identityID,$updatedIdentityColumns,$this->identity_request_table_live,$this->identity_request_table_stage,$approvalStatus,$approvalCrudStatus,$merchantId,$locationId);

            $updatedMenuColumns = array('base_price','product_url','product_quantity_stock','product_quantity_minimum','product_quantity_reduce','product_status');
                        
            $parentApprovalData = $this->addForApprove($hase_menu->product_id,$updatedMenuColumns,$this->request_table_live,$this->request_table_stage,$approvalStatus,$approvalCrudStatus,$merchantId,$locationId,$parentApprovalData['approvalParentId'],$parentApprovalData['groupHash']);

            if($request->special_status)
            {

                $updatedSpecialColumns = array('special_begin_date','special_begin_date','special_price','special_status','special_details','special_terms','special_image','special_image_compact','special_url');

                $this->addForApprove($specialId,$updatedSpecialColumns,$this->special_request_table_live,$this->special_request_table_stage,$approvalStatus,$approvalCrudStatus,$merchantId,$locationId,$parentApprovalData['approvalParentId'],$parentApprovalData['groupHash']);

                $updatedMenuSpecialColumns = array('priority');

                $this->addForApprove($hase_menus_specials->product_special_id,$updatedMenuSpecialColumns,$this->request_specials_child_table_live,$this->request_specials_child_table_stage,$approvalStatus,$approvalCrudStatus,$merchantId,$locationId,$parentApprovalData['approvalParentId'],$parentApprovalData['groupHash']);

            }

            $message = "<a href='".URL::to($staffUrl)."'>".$this->staffName."</a> <strong> ".$action." </strong> new ".$updatedName." <strong> $hase_menu->product_name </strong>";
            PermissionTrait::addActivityLog($action,$message);
        }
        //--------------------- End Menu Insertion ---------------------//


        //------------------------- Style Tagging ----------------------//

        $retails_type_list = array();

        if($this->roleId === 1){
            if(isset($request->style_type_id) && count($request->style_type_id)){

                $retail_style_list_deleted = Merchant_retail_style_list::whereNotIn('style_type_id', $request->style_type_id)
                    ->where('merchant_id',$merchantId)
                    ->where('location_id',$locationId)
                    ->where('product_id',$productId)
                    ->get();

                 Merchant_retail_style_list::whereNotIn('style_type_id', $request->style_type_id)
                ->where('merchant_id',$merchantId)
                ->where('location_id',$locationId)
                ->where('product_id',$productId)
                ->delete();

                if(count($retail_style_list_deleted))
                {
                    foreach ($retail_style_list_deleted as $key => $value) {
                        $updateStyleColumn = array('product_id','style_type_id');

                        $approvalStatus = $this->productApproval->ApprovalActionStatusTransitionBySelf("change");

                        $approvalCrudStatus = $this->productApproval->getApprovalCrudStatusId("delete");

                        $this->updateAdminForApprove($value['style_list_id'],$updateStyleColumn,$this->request_style_list_live,$approvalStatus,$approvalCrudStatus,$merchantId,$locationId);
                    }
                }

                foreach ($request->style_type_id as $key => $value) {
                    $isExist = Merchant_retail_style_list::
                        where('merchant_id', $merchantId)
                        ->where('location_id',$locationId)
                        ->where('product_id',$productId)
                        ->where('style_type_id',$value)
                        ->first();

                    if(is_null($isExist)){
                        $styleListSet = array(
                            'merchant_id' => $merchantId,
                            'location_id' => $locationId,
                            'style_type_id'=>$value,
                            'product_id'=>$productId,
                            'priority'=>$request->styles[$value]['priority'],
                            //'enable'=>isset($request->enable) ? 1 : 0 ,
                        );
                        $admin_list_id = Merchant_retail_style_list::insertGetId($styleListSet);

                        $updateStyleColumn = array('location_id','style_type_id','priority','enable');
                        
                        $approvalStatus = $this->productApproval->ApprovalActionStatusTransitionBySelf("change");

                        $approvalCrudStatus = $this->productApproval->getApprovalCrudStatusId("insert");

                        $this->addAdminForApprove($admin_list_id,$updateStyleColumn,$this->request_style_list_live,$approvalStatus,$approvalCrudStatus,$merchantId,$locationId,$parentApprovalData['approvalParentId'],$parentApprovalData['groupHash']);
                    } else {
                        $isExist->priority = $request->styles[$value]['priority'];
                        $isExist->update();
                    }
                }
            } else {
                $retail_style_list_deleted = Merchant_retail_style_list::where('merchant_id',$merchantId)
                    ->where('location_id',$locationId)
                    ->where('product_id',$productId)
                    ->get();

                Merchant_retail_style_list::
                    where('merchant_id',$merchantId)
                    ->where('location_id',$locationId)
                    ->where('product_id',$productId)
                    ->delete();

                if(count($retail_style_list_deleted))
                {
                    foreach ($retail_style_list_deleted as $key => $value) {
                        $updateStyleColumn = array('product_id','style_type_id');

                        $approvalStatus = $this->productApproval->ApprovalActionStatusTransitionBySelf("change");

                        $approvalCrudStatus = $this->productApproval->getApprovalCrudStatusId("delete");

                        $this->updateAdminForApprove($value['style_list_id'],$updateStyleColumn,$this->request_style_list_live,$approvalStatus,$approvalCrudStatus,$merchantId,$locationId);
                    }
                }
            }
        } else {
            
            if(isset($request->style_type_id) && count($request->style_type_id)){
                $retail_style_list_deleted = Merchant_retail_style_list::whereNotIn('style_type_id', $request->style_type_id)
                    ->where('merchant_id',$merchantId)
                    ->where('location_id',$locationId)
                    ->where('product_id',$productId)
                    ->get();
            } else {
                $retail_style_list_deleted = Merchant_retail_style_list::
                    where('merchant_id',$merchantId)
                    ->where('location_id',$locationId)
                    ->where('product_id',$productId)
                    ->get();
            }

            if(count($retail_style_list_deleted))
            {
                foreach ($retail_style_list_deleted as $key => $value) {

                    $hase_merchant_retail_style_list_stage = new Merchant_retail_style_list_stage();

                    $hase_merchant_retail_style_list_stage->staff_id = $this->staffId;

                    /*$hase_merchant_retail_style_list_stage->merchant_id = $value['merchant_id'];

                    $hase_merchant_retail_style_list_stage->location_id = $value['location_id'];*/

                    $hase_merchant_retail_style_list_stage->style_type_id = $value['style_type_id'];

                    $hase_merchant_retail_style_list_stage->product_id = $productId;

                    /*$hase_merchant_retail_style_list_stage->priority = $value['priority'];

                    $hase_merchant_retail_style_list_stage->enable = 0;*/

                    $hase_merchant_retail_style_list_stage->save();


                    $updateStyleColumn = array('product_id','style_type_id');

                    $approvalStatus = $this->productApproval->ApprovalActionStatusTransitionBySelf("change");

                    $approvalCrudStatus = $this->productApproval->getApprovalCrudStatusId("delete");

                    $this->updateForApprove($value['style_list_id'],$hase_merchant_retail_style_list_stage->style_list_id,$updateStyleColumn,$this->request_table_live,$this->request_table_stage,$approvalStatus,$approvalCrudStatus,$merchantId,$locationId);
                }
            }

            if(isset($request->style_type_id) && count($request->style_type_id)) {
            
                foreach ($request->style_type_id as $key => $value) {
            
                    $isExist = Merchant_retail_style_list::
                        where('merchant_id', '=', $merchantId)
                        ->where('location_id',$locationId)
                        ->where('style_type_id',$value)
                        ->where('product_id',$productId)
                        ->first();

                    if(is_null($isExist)){
                        $hase_merchant_retail_style_list = new Merchant_retail_style_list_stage();

                        $hase_merchant_retail_style_list->staff_id = $this->staffId;
                        $hase_merchant_retail_style_list->merchant_id = $merchantId;
                        $hase_merchant_retail_style_list->location_id = $locationId;
                        $hase_merchant_retail_style_list->style_type_id = $value;
                        $hase_merchant_retail_style_list->product_id = $productId;
                        $hase_merchant_retail_style_list->priority = $request->styles[$value]['priority'];
                        /*$hase_merchant_retail_style_list->enable = 
                                                isset($request->enable) ? 1 : 0 ;*/
                        $hase_merchant_retail_style_list->save();


                        $updateStyleColumn = array('location_id','style_type_id','priority','enable');
                        
                        $approvalStatus = $this->productApproval->ApprovalActionStatusTransitionBySelf("change");

                        $approvalCrudStatus = $this->productApproval->getApprovalCrudStatusId("insert");

                        $this->addForApprove($hase_merchant_retail_style_list->style_list_id,$updateStyleColumn,$this->request_style_list_live,$this->request_style_list_stage,$approvalStatus,$approvalCrudStatus,$merchantId,$locationId,$parentApprovalData['approvalParentId'],$parentApprovalData['groupHash']);
                    } else {

                        $isExist->priority = $request->styles[$value]['priority'];
                        /*$isExist->enable = isset($request->enable) ? 1 : 0;    */
                        $isExist->update();
                    }
                }
            }
        }

        //--------------------- End Style Tagging ---------------------//

        //--------------------- Category Tagging ----------------------//

        $retails_type_list = array();
        if($this->roleId === 1){

            if(isset($request->options) && count($request->options)){
                
                $categoryList = array();
                foreach ($request->options as $key => $category) {
                    $categoryList[] = $category['category_type_id'];
                }
                $categoryListDeleted = Merchant_retail_category_list::
                    whereNotIn('category_type_id', $categoryList)
                    ->where('merchant_id','=',$merchantId)
                    ->where('product_id',$productId)
                    ->where('location_id',$locationId)
                    ->get();

                Merchant_retail_category_list::
                    whereNotIn('category_type_id', $categoryList)
                    ->where('merchant_id',$merchantId)
                    ->where('location_id',$locationId)
                    ->where('product_id',$productId)
                    ->delete();
                if(count($categoryListDeleted)){

                    foreach ($categoryListDeleted as $key => $value) {

                        $updateCategoryColumn = array('product_id','category_type_id');

                        $approvalStatus = $this->productApproval->ApprovalActionStatusTransitionBySelf("change");

                        $approvalCrudStatus = $this->productApproval->getApprovalCrudStatusId("delete");

                        $this->updateAdminForApprove($value['style_list_id'],$$updateCategoryColumn,$this->request_category_list_live,$approvalStatus,$approvalCrudStatus,$merchantId,$locationId);
                    }
                }

                $optionListDeleted = Merchant_retail_category_option_list::
                    whereNotIn('category_type_id', $categoryList)
                    ->where('merchant_id',$merchantId)
                    ->where('location_id',$locationId)
                    ->where('product_id',$productId)
                    ->get();

                Merchant_retail_category_option_list::
                    whereNotIn('category_type_id', $categoryList)
                    ->where('merchant_id','=',$merchantId)
                    ->where('location_id',$locationId)
                    ->where('product_id',$productId)
                    ->delete();

                if(count($optionListDeleted)){

                    foreach ($optionListDeleted as $key => $value) {

                        $updateCategoryColumn = array('product_id','category_type_id','category_option_type_id');

                        $approvalStatus = $this->productApproval->ApprovalActionStatusTransitionBySelf("change");

                        $approvalCrudStatus = $this->productApproval->getApprovalCrudStatusId("delete");

                        $this->updateAdminForApprove($value['category_option_list_id'],$updateCategoryColumn,$this->option_request_table_live,$approvalStatus,$approvalCrudStatus,$merchantId,$locationId);
                    }
                }
                foreach ($request->options as $key => $category) {
                    
                    $isExist = Merchant_retail_category_list::
                        where('merchant_id', $merchantId)
                        ->where('location_id',$locationId)
                        ->where('product_id',$productId)
                        ->where('category_type_id', $category['category_type_id'])
                        ->first();

                    if(is_null($isExist)){
                        $categoryListSet = array(
                            'merchant_id' => $merchantId,
                            'location_id' => $locationId,
                            'product_id' => $productId,
                            'category_type_id' => $category['category_type_id']
                        );

                        $admin_category_id = Merchant_retail_category_list::insertGetId($categoryListSet);

                        $updateCategoryColumn = array('category_type_id');
                        
                        $approvalStatus = $this->productApproval->ApprovalActionStatusTransitionBySelf("change");

                        $approvalCrudStatus = $this->productApproval->getApprovalCrudStatusId("insert");

                        $this->addAdminForApprove($admin_category_id,$updateCategoryColumn,$this->request_category_list_live,$approvalStatus,$approvalCrudStatus,$merchantId,$locationId,$parentApprovalData['approvalParentId'],$parentApprovalData['groupHash']);
                    }
                    
                    if(isset($category['category_option_type_id']) && count($category['category_option_type_id'])){

                        $optionListDeleted = Merchant_retail_category_option_list::
                            whereNotIn('category_option_type_id', $category['category_option_type_id'])
                            ->where('merchant_id',$merchantId)
                            ->where('location_id',$locationId)
                            ->where('product_id',$productId)
                            ->where('category_type_id',$category['category_type_id'])
                            ->get();
                            
                        Merchant_retail_category_option_list::
                            where('category_type_id', $category['category_type_id'])
                            ->where('merchant_id','=',$merchantId)
                            ->where('location_id',$locationId)
                            ->where('product_id',$productId)
                            ->whereNotIn('category_option_type_id',$category['category_option_type_id'])
                            ->delete();

                        if(count($optionListDeleted)){

                            foreach ($optionListDeleted as $key => $value) {

                                $updateCategoryColumn = array('product_id','category_type_id','category_option_type_id');

                                $approvalStatus = $this->productApproval->ApprovalActionStatusTransitionBySelf("change");

                                $approvalCrudStatus = $this->productApproval->getApprovalCrudStatusId("delete");

                                $this->addAdminForApprove($value['category_option_list_id'],$updateCategoryColumn,$this->option_request_table_live,$approvalStatus,$approvalCrudStatus,$merchantId,$locationId,$parentApprovalData['approvalParentId'],$parentApprovalData['groupHash']);
                            }
                        }


                        foreach ($category['category_option_type_id'] as $key => $optionID) {

                            $isExist = Merchant_retail_category_option_list::
                                    where('merchant_id',$merchantId)
                                    ->where('location_id',$locationId)
                                    ->where('category_type_id',$category['category_type_id'])
                                    ->where('product_id',$productId)
                                    ->where('category_option_type_id',$optionID)
                                    ->get()->first();

                            if(is_null($isExist)){

                                $optionListSet = array(
                                    'merchant_id' => $merchantId,
                                    'location_id' => $locationId,
                                    'product_id' => $productId,
                                    'category_type_id' => $category['category_type_id'],
                                    'category_option_type_id' => $optionID,
                                    'priority' => $category['priority'],
                                    'enable' => isset($category['enable']) ? 1 : 0
                                );

                                $admin_category_option_id = Merchant_retail_category_option_list::insertGetId($optionListSet);

                                $updateCategoryColumn = array('product_id','category_type_id','category_option_type_id');

                                $approvalStatus = $this->productApproval->ApprovalActionStatusTransitionBySelf("change");

                                $approvalCrudStatus = $this->productApproval->getApprovalCrudStatusId("delete");

                                $this->addAdminForApprove($admin_category_option_id,$updateCategoryColumn,$this->option_request_table_live,$approvalStatus,$approvalCrudStatus,$merchantId,$locationId,$parentApprovalData['approvalParentId'],$parentApprovalData['groupHash']);
                            }else{
                                $optionListSet = array(
                                        'priority' => $category['priority'],
                                        'enable' => isset($category['enable']) ? 1 : 0
                                );
                                Merchant_retail_category_option_list::
                                    where('merchant_id',$merchantId)
                                    ->where('location_id',$locationId)
                                    ->where('category_type_id',$category['category_type_id'])
                                    ->where('product_id',$productId)
                                    ->where('category_option_type_id',$optionID)
                                    ->update($optionListSet);
                            }
                        }
                    }else{

                        $optionListDeleted = Merchant_retail_category_option_list::
                            where('merchant_id',$merchantId)
                            ->where('location_id',$locationId)
                            ->where('product_id',$productId)
                            ->where('category_type_id',$category['category_type_id'])
                            ->get();

                        Merchant_retail_category_option_list::
                            where('merchant_id',$merchantId)
                            ->where('location_id',$locationId)
                            ->where('product_id',$productId)
                            ->where('category_type_id',$category['category_type_id'])
                            ->delete();

                        if(count($optionListDeleted)){

                            foreach ($optionListDeleted as $key => $value) {

                                $updateCategoryColumn = array('product_id','category_type_id','category_option_type_id');

                                $approvalStatus = $this->productApproval->ApprovalActionStatusTransitionBySelf("change");

                                $approvalCrudStatus = $this->productApproval->getApprovalCrudStatusId("delete");

                                $this->updateAdminForApprove($value['category_option_list_id'],$updateCategoryColumn,$this->option_request_table_live,$approvalStatus,$approvalCrudStatus,$merchantId,$locationId);
                            }
                        }
                    }
                }
            }else{
                
                $categoryListDeleted = Merchant_retail_category_list::
                    where('merchant_id','=',$merchantId)
                    ->where('product_id',$productId)
                    ->where('location_id',$locationId)
                    ->get();
                
                Merchant_retail_category_list::
                    where('merchant_id',$merchantId)
                    ->where('location_id',$locationId)
                    ->where('product_id',$productId)
                    ->delete();

                if(count($categoryListDeleted)){

                    foreach ($categoryListDeleted as $key => $value) {

                        $updateCategoryColumn = array('product_id','category_type_id');

                        $approvalStatus = $this->productApproval->ApprovalActionStatusTransitionBySelf("change");

                        $approvalCrudStatus = $this->productApproval->getApprovalCrudStatusId("delete");

                        $this->updateAdminForApprove($value['category_list_id'],$$updateCategoryColumn,$this->request_category_list_live,$approvalStatus,$approvalCrudStatus,$merchantId,$locationId);
                    }
                }

                $optionListDeleted = Merchant_retail_category_option_list::
                    where('merchant_id',$merchantId)
                    ->where('location_id',$locationId)
                    ->where('product_id',$productId)
                    ->get();

                Merchant_retail_category_option_list::
                    where('merchant_id',$merchantId)
                    ->where('location_id',$locationId)
                    ->where('product_id',$productId)
                    ->delete();

                if(count($optionListDeleted)){

                    foreach ($optionListDeleted as $key => $value) {

                        $updateCategoryColumn = array('product_id','category_type_id','category_option_type_id');

                        $approvalStatus = $this->productApproval->ApprovalActionStatusTransitionBySelf("change");

                        $approvalCrudStatus = $this->productApproval->getApprovalCrudStatusId("delete");

                        $this->updateAdminForApprove($value['category_option_list_id'],$updateCategoryColumn,$this->option_request_table_live,$approvalStatus,$approvalCrudStatus,$merchantId,$locationId);
                    }
                }
            }
        }else{
            
            if(isset($request->options) && count($request->options)){
                
                $categoryList = array();
                foreach ($request->options as $key => $category) {
                    $categoryList[] = $category['category_type_id'];
                }

                $categoryListDeleted = Merchant_retail_category_list::
                    whereNotIn('category_type_id', $categoryList)
                    ->where('merchant_id','=',$merchantId)
                    ->where('product_id',$productId)
                    ->where('location_id',$locationId)
                    ->get();
            }else{

                $categoryListDeleted =  Merchant_retail_category_list::
                    where('merchant_id','=',$merchantId)
                    ->where('location_id',$locationId)
                    ->where('product_id',$productId)
                    ->get();
            }

            if(count($categoryListDeleted)){

                foreach ($categoryListDeleted as $key => $value) {

                    $hase_merchant_retail_category_list = new Merchant_retail_category_list_stage();

                    $hase_merchant_retail_category_list->staff_id = $this->staffId;

                    /*$hase_merchant_retail_category_list->merchant_id = $value['merchant_id'];
                    $hase_merchant_retail_category_list->location_id = $value['location_id'];*/
                    $hase_merchant_retail_category_list->product_id = $productId;

                    $hase_merchant_retail_category_list->category_type_id = $value['category_type_id'];

                    $hase_merchant_retail_category_list->save();

                    $updateCategoryColumn = array('product_id','category_type_id');

                    $approvalStatus = $this->productApproval->ApprovalActionStatusTransitionBySelf("change");

                    $approvalCrudStatus = $this->productApproval->getApprovalCrudStatusId("delete");

                    $this->updateForApprove($value['category_list_id'],$hase_merchant_retail_category_list->category_list_id,$updateCategoryColumn,$this->request_category_list_live,$this->request_category_list_stage,$approvalStatus,$approvalCrudStatus,$merchantId,$locationId);
                }
            }

            if(isset($request->options) && count($request->options)){
                foreach ($request->options as $key => $category) {
                
                    if(isset($category['category_option_type_id']) && count($category['category_option_type_id'])){

                        $optionListDeleted = Merchant_retail_category_option_list::
                            whereNotIn('category_option_type_id', $category['category_option_type_id'])
                            ->where('merchant_id',$merchantId)
                            ->where('location_id',$locationId)
                            ->where('product_id',$productId)
                            ->where('category_type_id',$category['category_type_id'])
                            ->get();
                    }else{

                        $optionListDeleted =  Merchant_retail_category_option_list::
                            where('merchant_id',$merchantId)
                            ->where('location_id',$locationId)
                            ->where('product_id',$productId)
                            ->where('category_type_id',$category['category_type_id'])
                            ->get();
                    }

                    if(count($optionListDeleted)){

                        foreach ($optionListDeleted as $key => $value) {

                            $hase_merchant_retail_category_option_list = new Merchant_retail_category_option_list_stage();

                            $hase_merchant_retail_category_option_list->staff_id = $this->staffId;

                            /*$hase_merchant_retail_category_option_list->merchant_id = $value['merchant_id'];

                            $hase_merchant_retail_category_option_list->location_id = $value['location_id'];*/

                            $hase_merchant_retail_category_option_list->product_id = $productId;

                            $hase_merchant_retail_category_option_list->category_type_id = $value['category_type_id'];

                            $hase_merchant_retail_category_option_list->category_option_type_id = $value['category_option_type_id'];

                            /*$hase_merchant_retail_category_option_list->priority = $value['priority'];
                            $hase_merchant_retail_category_option_list->enable = 0;*/

                            $hase_merchant_retail_category_option_list->save();

                            $updateCategoryColumn = array('product_id','category_type_id','category_option_type_id');

                            $approvalStatus = $this->productApproval->ApprovalActionStatusTransitionBySelf("change");

                            $approvalCrudStatus = $this->productApproval->getApprovalCrudStatusId("delete");

                            $this->updateForApprove($value['category_option_type_id'],$hase_merchant_retail_category_option_list->category_option_list_id,$updateCategoryColumn,$this->option_request_table_live,$this->option_request_table_stage,$approvalStatus,$approvalCrudStatus,$merchantId,$locationId);
                        }
                    }
                

                    $isExist = Merchant_retail_category_list::
                    where('merchant_id', $merchantId)
                    ->where('location_id',$locationId)
                    ->where('product_id',$productId)
                    ->where('category_type_id', $category['category_type_id'])
                    ->first();

                    if(is_null($isExist)){
                        
                        $hase_merchant_retail_category_list = new Merchant_retail_category_list_stage();

                        $hase_merchant_retail_category_list->staff_id = $this->staffId;
                        $hase_merchant_retail_category_list->merchant_id = $merchantId;
                        $hase_merchant_retail_category_list->location_id = $locationId;
                        $hase_merchant_retail_category_list->product_id = $productId;
                        $hase_merchant_retail_category_list->category_type_id = $category['category_type_id'];

                        $hase_merchant_retail_category_list->save();

                        $updateCategoryColumn = array('category_type_id');

                        $approvalStatus = $this->productApproval->ApprovalActionStatusTransitionBySelf("change");

                        $approvalCrudStatus = $this->productApproval->getApprovalCrudStatusId("insert");

                        $this->addForApprove($hase_merchant_retail_category_list->category_list_id,$updateCategoryColumn,$this->request_category_list_live,$this->request_category_list_stage,$approvalStatus,$approvalCrudStatus,$merchantId,$locationId,$parentApprovalData['approvalParentId'],$parentApprovalData['groupHash']);
                    }

                    if(isset($category['category_option_type_id']) && count($category['category_option_type_id'])){
                        foreach ($category['category_option_type_id'] as $key => $optionID) {

                            $isExist = Merchant_retail_category_option_list::
                                    where('merchant_id',$merchantId)
                                    ->where('location_id',$locationId)
                                    ->where('category_type_id',$category['category_type_id'])
                                    ->where('category_option_type_id',$optionID)
                                    ->where('product_id',$productId)
                                    ->where('enable',1)
                                    ->first();

                            if(is_null($isExist)){

                                $hase_merchant_retail_category_option_list = new Merchant_retail_category_option_list_stage();

                                $hase_merchant_retail_category_option_list->staff_id = $this->staffId;

                                $hase_merchant_retail_category_option_list->merchant_id = $merchantId;

                                $hase_merchant_retail_category_option_list->location_id = $locationId;

                                $hase_merchant_retail_category_option_list->product_id = $productId;

                                $hase_merchant_retail_category_option_list->category_type_id = $category['category_type_id'];

                                $hase_merchant_retail_category_option_list->category_option_type_id = $optionID;

                                $hase_merchant_retail_category_option_list->priority = $category['priority'];

                                $hase_merchant_retail_category_option_list->enable = isset($category['enable']) ? 1 : 0 ;

                                $hase_merchant_retail_category_option_list->save();

                                $updateCategoryColumn = array('category_type_id','category_option_type_id','priority','enable');

                                $approvalStatus = $this->productApproval->ApprovalActionStatusTransitionBySelf("change");

                                $approvalCrudStatus = $this->productApproval->getApprovalCrudStatusId("insert");

                                $this->addForApprove($hase_merchant_retail_category_option_list->category_option_list_id,$updateCategoryColumn,$this->option_request_table_live,$this->option_request_table_stage,$approvalStatus,$approvalCrudStatus,$merchantId,$locationId,$parentApprovalData['approvalParentId'],$parentApprovalData['groupHash']);
                            }
                        }
                    }
                }  
            }     
        }

        //--------------------- End Category Tagging ---------------------//        

        Session::flash('type', 'success'); 
        if(Requests::segment(1) === "hase_product"){
            Session::flash('msg', 'Product Successfully Inserted');  
        }else{
            Session::flash('msg', 'Menu Successfully Inserted'); 
        }

        if($this->roleId === 1){
            if ($request->submitBtn === "Save") {
                return redirect(Requests::segment(1).'/'. $hase_menu->product_id . '/edit');
            }else{
               return redirect(Requests::segment(1));
            }
        }else{
            return redirect(Requests::segment(1));
        }
    }

    /**
     * Show the form for editing the specified resource.
     * @param    \Illuminate\Http\Request  $request
     * @param    int  $id
     * @return  \Illuminate\Http\Response
     */
    public function edit($id,Request $request)
    {
        $haseMenuAceess = $this->permissionDetails('Hase_menu','manage');

        if($haseMenuAceess) {
            
            $title = 'Edit - hase_menu';

            if(Requests::segment(1) === "hase_product"){
                $merchantType = 2;
                $labels = array("Products","Shop","Product");    
                $styleLabels = array("Industries","Industry","Merchants");
            }else{
                $merchantType = 8;
                $labels = array("Feature Dish/Menus","Restaurants","Feature Dish/Menu");
                $styleLabels = array("Cuisines","Cuisine","Merchants");
            }

            $hase_categories =  Merchant_retail_category_type::
                        select('category_type_id','identity_merchant_retail_category_type.identity_name as category_name')
                        ->join('identity_merchant_retail_category_type','identity_merchant_retail_category_type.identity_id','=','merchant_retail_category_type.identity_id')
                        ->where('merchant_retail_category_type.merchant_type_id',$merchantType)
                        ->get();

            //------------------------Start Menu Edit -----------------------------//

            if($this->merchantId === 0 ) {

                $hase_menu = Menu::
                            select('product.*','identity_product.identity_name as product_name','identity_product.identity_code as product_code','identity_product.identity_description as product_description','identity_product.identity_logo as product_image','identity_product.identity_logo_compact as product_image_compact','merchant.merchant_id','location_list.location_city_id')
                            ->join('location_list','product.location_id','=','location_list.list_id')
                            ->join('identity_product','product.identity_id','=','identity_product.identity_id')
                            ->join('merchant','merchant.identity_id','=','location_list.identity_id')
                            ->where('location_list.identity_table_id','=',MERCHANT_TABLE_IDENTITY_TYPE)
                            ->where('product_id',$id)
                            ->get()->first();
                
                $merchantInfo = Merchant::
                        select('merchant.merchant_id','identity_merchant.identity_name as merchant_name','merchant.merchant_type_id')
                        ->join('identity_merchant','identity_merchant.identity_id','=','merchant.identity_id')
                        ->where('merchant.merchant_id','=',$hase_menu->merchant_id)
                        ->get()->first();

                $hase_location_cities = City::distinct()
                    ->select('location_list.location_city_id','location_city.city_name')
                    ->join('location_list','location_list.location_city_id','=','location_city.city_id')
                    ->join('merchant','merchant.identity_id','=','location_list.identity_id')
                    ->where('location_list.identity_table_id','=',MERCHANT_TABLE_IDENTITY_TYPE)
                    ->where('merchant.merchant_id','=',$hase_menu->merchant_id)
                    ->get();         

                $hase_locations = Postal::distinct()
                    ->select('location_list.postal_id as location_id',DB::raw('(CASE WHEN (postal.postal_premise != "" and postal.postal_subpremise != "") THEN CONCAT(postal.postal_premise,", ",SUBSTR(postal.postal_subpremise,1,10))
                              WHEN (postal.postal_premise != "") THEN postal.postal_premise                              
                              WHEN (postal.postal_subpremise !="") THEN SUBSTR(postal.postal_subpremise,1,10)
                              ELSE CONCAT(postal.postal_street_number," ",postal.postal_route)   
                        END) as location_name'))
                    ->join('location_list','location_list.postal_id','=','postal.postal_id')
                    ->join('merchant','merchant.identity_id','=','location_list.identity_id')
                    ->where('location_list.identity_table_id','=',MERCHANT_TABLE_IDENTITY_TYPE)
                    ->where('merchant.merchant_id','=',$hase_menu->merchant_id)
                    ->get();        

            } else {

                $hase_menu = Menu::
                            select('product.*','identity_product.identity_name as product_name','identity_product.identity_code as product_code','merchant.merchant_id','identity_product.identity_description as product_description','identity_product.identity_logo as product_image','identity_product.identity_logo_compact as product_image_compact','location_list.location_city_id')
                            ->join('location_list','product.location_id','=','location_list.list_id')
                            ->join('identity_product','product.identity_id','=','identity_product.identity_id')
                            ->join('merchant','merchant.identity_id','=','location_list.identity_id')
                            ->where('location_list.identity_table_id','=',MERCHANT_TABLE_IDENTITY_TYPE)
                            ->where('merchant.merchant_id','=',$this->merchantId)
                            ->where('product_id',$id)                            
                            ->get()->first();                

                $merchantInfo = Merchant::
                            select('merchant.merchant_id','identity_merchant.identity_name as merchant_name','merchant.merchant_type_id')
                            ->leftjoin('identity_merchant','identity_merchant.identity_id','=','merchant.identity_id')
                            ->where('merchant.merchant_id','=',$hase_menu->merchant_id)
                            ->get()->first();

                $hase_location_cities = City::distinct()
                            ->select('location_list.location_city_id','location_city.city_name')
                            ->join('location_list','location_list.location_city_id','=','location_city.city_id')
                            ->join('merchant','merchant.identity_id','=','location_list.identity_id')
                            ->where('location_list.identity_table_id','=',MERCHANT_TABLE_IDENTITY_TYPE)
                            ->where('merchant.merchant_id','=',$hase_menu->merchant_id)
                            ->get();                 

                $hase_locations = Postal::distinct()
                                ->select('location_list.postal_id as location_id',DB::raw('(CASE WHEN (postal.postal_premise != "" and postal.postal_subpremise != "") THEN CONCAT(postal.postal_premise,", ",SUBSTR(postal.postal_subpremise,1,10))
                              WHEN (postal.postal_premise != "") THEN postal.postal_premise                              
                              WHEN (postal.postal_subpremise !="") THEN SUBSTR(postal.postal_subpremise,1,10)
                              ELSE CONCAT(postal.postal_street_number," ",postal.postal_route)   
                        END) as location_name'))
                                ->join('location_list','location_list.postal_id','=','postal.postal_id')
                                ->join('merchant','merchant.identity_id','=','location_list.identity_id')
                                ->where('location_list.identity_table_id','=',MERCHANT_TABLE_IDENTITY_TYPE)
                                ->where('merchant.merchant_id','=',$hase_menu->merchant_id)
                                ->get();  

            }

            $hase_menu_Specials = Menus_special::
                        join('special','product_specials.special_id','=','special.special_id')
                        ->where('product_id',$hase_menu->product_id)
                        ->get()->first();

            if(!$hase_menu) {
                return Redirect(Requests::segment(1))->with('message', 'You are not authorized to use this functionality!');
            }

            if($hase_menu_Specials){

                $hase_menu_Specials->special_begin_date =  substr_replace(
                substr_replace($hase_menu_Specials->special_begin_date, '-', 4, 0), '-', 7, 0);

                $hase_menu_Specials->special_expire_date =  substr_replace(
                    substr_replace($hase_menu_Specials->special_expire_date, '-', 4, 0), '-', 7, 0);
            }

            //------------------------End Menu Edit -----------------------------//

            //------------------------Start Style Tagging -----------------------------//


            $hase_style_exist=array();

            $stylesExist = Merchant_retail_style_list::                            
                            where('product_id',$hase_menu->product_id)
                            ->where('location_id',$hase_menu->location_id)
                            ->get()->toArray();

            $styleTypes = Merchant_retail_style_type::
                            select('merchant_retail_style_type.*','identity_merchant_retail_style_type.identity_name as style_name')
                            ->join('identity_merchant_retail_style_type','identity_merchant_retail_style_type.identity_id','=','merchant_retail_style_type.identity_id')
                            ->where('merchant_retail_style_type.merchant_type_id',$merchantInfo->merchant_type_id)
                            ->get();

            $hase_style_exist['style_type_id'] = array();
            $hase_style_exist['priority'] = array();

            foreach ($stylesExist as $key => $value) {
                $hase_style_exist['style_type_id'][] = $value['style_type_id'];
                $hase_style_exist['priority'][$value['style_type_id']] = $value['priority'];
            }
           
            //------------------------End Style Tagging -----------------------------//

            //-------------------- Start Category Tagging ---------------------------//

            $hase_category_exist=array();

            $hase_options = Merchant_retail_category_option::
                            select('merchant_retail_category_option.*','identity_merchant_retail_category_option.identity_name as option_name','identity_merchant_retail_category_option.identity_logo as option_image','identity_merchant_retail_category_option.identity_logo_compact as option_image_compact')
                            ->leftjoin('identity_merchant_retail_category_option','merchant_retail_category_option.identity_id','=','identity_merchant_retail_category_option.identity_id')
                            ->get();

            $categoryTypes = Merchant_retail_category_type::
                            select('merchant_retail_category_type.*','identity_merchant_retail_category_type.identity_name as category_name')
                            ->join('identity_merchant_retail_category_type','identity_merchant_retail_category_type.identity_id','=','merchant_retail_category_type.identity_id')
                            ->where('merchant_type_id',$merchantType)
                            ->get();

            //------------------------End Categpry Tagging --------------------------//
                        

            return view('hase_menu.edit',compact('title','hase_menu','hase_categories','hase_menu_Specials','hase_location_cities','hase_locations','labels','styleLabels','hase_merchants','merchantInfo','hase_style_exist','styleTypes','hase_options','categoryTypes'));

        } else {
            return Redirect::back()->with('message', 'You are not authorized to use this functionality!');
        }     
    }

    /**
     * Update the specified resource in storage.
     *
     * @param    \Illuminate\Http\Request  $request
     * @param    int  $id
     * @return  \Illuminate\Http\Response
     */
    public function update($id,Request $request)
    {

        $hase_menu = Menu::findOrfail($id);
        $hase_identity = Identity_menu::findOrfail($hase_menu->identity_id);

        if(Requests::segment(1) === "hase_product"){

            $updatedName = 'Product';
        }else{
            $updatedName = 'Menu';
        }

        $merchantId = $request->merchant_id;
        $locationId = $request->location_id;

        $hase_merchant = Merchant::
                            select('merchant.merchant_id','identity_merchant.identity_name as merchant_name')
                            ->join('identity_merchant','identity_merchant.identity_id','=','merchant.identity_id')
                            ->where('merchant.merchant_id',$merchantId)
                            ->get()->first();

        $hase_location = Location_list::
                            select('location_list.list_id as location_id',DB::raw('(CASE WHEN (postal.postal_premise != "" and postal.postal_subpremise != "") THEN CONCAT(postal.postal_premise,", ",SUBSTR(postal.postal_subpremise,1,10))
                              WHEN (postal.postal_premise != "") THEN postal.postal_premise                              
                              WHEN (postal.postal_subpremise !="") THEN SUBSTR(postal.postal_subpremise,1,10)
                              ELSE CONCAT(postal.postal_street_number," ",postal.postal_route)   
                        END) as location_name'))
                            ->join('postal','postal.postal_id','=','location_list.postal_id')
                            ->where('location_list.list_id','=',$locationId)
                            ->get()->first();

        $merchantDirName = md5($hase_merchant->merchant_name);
        $locationDirName = md5($hase_merchant->merchant_name.$hase_location->location_name);

        if($request->live_image_url)
        {
            $hase_identity->identity_logo = $request->live_image_url;
        } 
        else {
            if($request->file('product_image')){

                $publicDirPath = public_path(env('image_dir_path'));
                
                if($this->roleId === 1){
                    $imageDirPath = "merchant/$merchantDirName/location/$locationDirName/";
                }else{
                    $imageDirPath = "merchant_stage/$merchantDirName/location_stage/$locationDirName/";
                }

                $absoluteImageDirPath = $publicDirPath.$imageDirPath;

                if(!file_exists($absoluteImageDirPath)){
                    mkdir($absoluteImageDirPath,0777,true);
                }

                if($this->roleId === 1)
                {
                    $imagePath = $publicDirPath.$hase_menu->product_image;
                    if (is_file($imagePath)) {
                        unlink($imagePath);
                    }
                }

                $imageName = $request->file('product_image')->getClientOriginalName();
                $imageArray = explode('.', $imageName);
                $hashImageName = md5($hase_merchant->merchant_name.$hase_location->location_name.$hase_menu->product_name.$imageName).".".$imageArray[1];
                $request->file('product_image')->move($absoluteImageDirPath,$hashImageName);
                $hase_identity->identity_logo = "$imageDirPath$hashImageName";
            }
        }

        if($request->live_image_compact_url)
        {
            $hase_identity->identity_logo_compact = $request->live_image_compact_url;
        } else {
            if($request->file('product_image_compact')){

                $publicDirPath = public_path(env('image_dir_path'));
                
                if($this->roleId === 1){
                    $imageDirPath = "merchant/$merchantDirName/location/$locationDirName/";
                }else{
                    $imageDirPath = "merchant_stage/$merchantDirName/location_stage/$locationDirName/";
                }

                $absoluteImageDirPath = $publicDirPath.$imageDirPath;

                if(!file_exists($absoluteImageDirPath)){
                    mkdir($absoluteImageDirPath,0777,true);
                }

                if($this->roleId === 1)
                {
                    $imagePath = $publicDirPath.$hase_menu->product_image_compact;
                    if (is_file($imagePath)) {
                        unlink($imagePath);
                    }
                }

                $imageName = $request->file('product_image_compact')->getClientOriginalName();
                $imageArray = explode('.', $imageName);
                $hashImageName = md5($hase_merchant->merchant_name.$hase_location->location_name.$hase_menu->product_name.$imageName).".".$imageArray[1];
                $request->file('product_image_compact')->move($absoluteImageDirPath,$hashImageName);
                $hase_identity->identity_logo_compact = "$imageDirPath$hashImageName";
                
            }
        }

        // identity schema data


        if(!is_null($request->product_code)){
            $request->product_code = strtolower(preg_replace('/\s+/', '', $request->product_code));


            $code_exist_live = Identity_menu::select('identity_code')
                                        ->where('identity_id','!=',$request->identity_id)
                                        ->where('identity_code', $request->product_code);

            $code_exist_stage = Identity_menu_stage::select('identity_code')
                                        ->join('approval','approval.request_table_stage_primary_id','identity_product_stage.identity_id')
                                        ->where('approval.request_table_stage',IDENTITY_PRODUCT_STAGE_TABLE_ID)
                                        ->where('identity_product_stage.identity_code', $request->product_code);

            $results = $code_exist_live->union($code_exist_stage)->get(); 


            if(count($results) === 0){
                $hase_identity->identity_code = $request->product_code;
            }   

        }

        $hase_identity->identity_name = $request->product_name;
        $hase_identity->identity_description = $request->product_description;

        $hase_menu->location_id = $locationId;
        $hase_menu->base_price = $request->base_price;

        $staffUrl = "/hase_staff/".$this->staffId."/edit";
        $action = "updated";

        if($this->roleId === 1)
        {
            $dirtyMenu = $hase_menu->getDirty();
            $dirtyIdentity = $hase_identity->getDirty();


            $hase_menu->save();
            $hase_identity->save();

            if($dirtyIdentity)
            {
                $updatedIdentityColumns = array();
                foreach ($dirtyIdentity as $field => $newdata)
                {
                    $olddata = $hase_identity->getOriginal($field);
                    if ($olddata !== $newdata)
                    {
                        $updatedIdentityColumns[] =  $field;
                    }
                }
                if(count($updatedIdentityColumns));
                {
                    $approvalStatus = $this->productApproval->ApprovalActionStatusTransitionBySelf("change");

                    $approvalCrudStatus = $this->productApproval->getApprovalCrudStatusId("modify");

                    $this->updateAdminForApprove($id,$updatedIdentityColumns,$this->identity_request_table_live,$approvalStatus,$approvalCrudStatus,$merchantId,$locationId);
                }
            }

            if($dirtyMenu)
            {
                $updatedMenuColumns = array();
                foreach ($dirtyMenu as $field => $newdata)
                {
                    $olddata = $hase_menu->getOriginal($field);
                    if ($olddata !== $newdata)
                    {
                        $updatedMenuColumns[] =  $field;
                    }
                }
                if(count($updatedMenuColumns));
                {
                    $approvalStatus = $this->productApproval->ApprovalActionStatusTransitionBySelf("change");

                    $approvalCrudStatus = $this->productApproval->getApprovalCrudStatusId("modify");

                    $this->updateAdminForApprove($id,$updatedMenuColumns,$this->request_table_live,$approvalStatus,$approvalCrudStatus,$merchantId,$locationId);
                }
            }
            
        
            if($request->special_status) {

                if(isset($request->special_id)) {

                    $hase_menus_specials = Menus_special::firstOrCreate(['product_special_id' => $request->special_id]);

                    $hase_specials = Special::firstOrCreate(['special_id' => $hase_menus_specials->special_id]);

                } else {
                    $hase_menus_specials = new Menus_special;
                    $hase_specials = new Special;
                }

                if($request->live_special_image_url)
                {
                    $hase_specials->special_image = $request->live_special_image_url;
                } else {
                    if($request->file('special_image')){

                        $publicDirPath = public_path(env('image_dir_path'));
                    
                        if($this->roleId === 1){
                            $imageDirPath = "merchant/$merchantDirName/location/$locationDirName/";
                        }else{
                            $imageDirPath = "merchant_stage/$merchantDirName/location_stage/$locationDirName/";
                        }

                        $absoluteImageDirPath = $publicDirPath.$imageDirPath;

                        if(!file_exists($absoluteImageDirPath)){
                            mkdir($absoluteImageDirPath,0777,true);
                        }

                        if($this->roleId === 1)
                        {
                            $imagePath = $publicDirPath.$hase_menus_specials->special_image;
                            if (is_file($imagePath)) {
                                unlink($imagePath);
                            }
                        }

                        $imageName = $request->file('special_image')->getClientOriginalName();
                        $imageArray = explode('.', $imageName);
                        $hashImageName = md5($hase_merchant->merchant_name.$hase_location->location_name.$hase_menu->product_name.$imageName).".".$imageArray[1];
                        $request->file('special_image')->move($absoluteImageDirPath,$hashImageName);
                        $hase_specials->special_image = "$imageDirPath$hashImageName";
                    }
                }

                if($request->live_special_image_compact_url)
                {
                    $hase_specials->special_image_compact = $request->live_special_image_compact_url;
                } else {
                    if($request->file('special_image_compact')){

                        $publicDirPath = public_path(env('image_dir_path'));
                    
                        if($this->roleId === 1){
                            $imageDirPath = "merchant/$merchantDirName/location/$locationDirName/";
                        }else{
                            $imageDirPath = "merchant_stage/$merchantDirName/location_stage/$locationDirName/";
                        }

                        $absoluteImageDirPath = $publicDirPath.$imageDirPath;

                        if(!file_exists($absoluteImageDirPath)){
                            mkdir($absoluteImageDirPath,0777,true);
                        }

                        if($this->roleId === 1)
                        {
                            $imagePath = $publicDirPath.$hase_menus_specials->special_image_compact;
                            if (is_file($imagePath)) {
                                unlink($imagePath);
                            }
                        }

                        $imageName = $request->file('special_image_compact')->getClientOriginalName();
                        $imageArray = explode('.', $imageName);
                        $hashImageName = md5($hase_merchant->merchant_name.$hase_location->location_name.$hase_menu->product_name.$imageName).".".$imageArray[1];
                        $request->file('special_image_compact')->move($absoluteImageDirPath,$hashImageName);
                        $hase_specials->special_image_compact = "$imageDirPath$hashImageName";
                    }
                }

                $hase_menus_specials->location_id = $locationId;
                $hase_menus_specials->product_id = $id;
                
                $hase_specials->special_begin_date = ($request->start_date != "")?
                    str_replace("-","",date('Y-m-d',strtotime($request->start_date))) : 0;

                $hase_specials->special_expire_date = ($request->end_date != "")?
                        str_replace("-","",date('Y-m-d',strtotime($request->end_date))) : 0;

                $hase_specials->special_price = $request->special_price;
                $hase_specials->special_details = $request->special_details;
                $hase_specials->special_terms = $request->special_terms;
                $hase_specials->special_status = isset($request->special_status)?1:0;
                $hase_specials->special_url = $request->special_url;

                $menuSpecialDirty = $hase_menus_specials->getDirty();
                $specialDirty = $hase_specials->getDirty();

                if($specialDirty)
                {
                    $hase_specials->save();
                    $hase_menus_specials->special_id = $hase_specials->special_id;

                    $updatedSpecialColumns = array();
                    foreach ($specialDirty as $field => $newdata)
                    {
                        $olddata = $hase_specials->getOriginal($field);
                        if ($olddata !== $newdata)
                        {
                            $updatedSpecialColumns[] =  $field;
                        }
                    }
                    if(count($updatedSpecialColumns));
                    {
                        $approvalStatus = $this->productApproval->ApprovalActionStatusTransitionBySelf("change");

                        $approvalCrudStatus = $this->productApproval->getApprovalCrudStatusId("modify");

                        $this->updateAdminForApprove($hase_specials->special_id,$updatedSpecialColumns,$this->special_request_table_live,$approvalStatus,$approvalCrudStatus,$merchantId,$locationId);
                    }
                }

                if($menuSpecialDirty)
                {
                    $hase_menus_specials->save();
                    $updatedMenuSpecialColumns = array();
                    foreach ($menuSpecialDirty as $field => $newdata)
                    {
                        $olddata = $hase_menus_specials->getOriginal($field);
                        if ($olddata !== $newdata)
                        {
                            $updatedMenuSpecialColumns[] =  $field;
                        }
                    }
                    if(count($updatedMenuSpecialColumns));
                    {
                        $approvalStatus = $this->productApproval->ApprovalActionStatusTransitionBySelf("change");

                        $approvalCrudStatus = $this->productApproval->getApprovalCrudStatusId("modify");

                        $this->updateAdminForApprove($hase_menus_specials->product_special_id,$updatedMenuSpecialColumns,$this->request_specials_child_table_live,$approvalStatus,$approvalCrudStatus,$merchantId,$locationId);
                    }
                }

            } else {

                if(isset($request->special_id)){
                    $hase_menus_specials = Menus_special::
                            firstOrCreate(['product_special_id' => $request->special_id]);

                    $hase_menus_specials->special_status = 0;
                    $hase_menus_specials->special_id = $hase_specials->special_id;
                    $hase_menus_specials->save();


                    $updatedMenuSpecialColumns = array('special_status');
                    $approvalStatus = $this->productApproval->ApprovalActionStatusTransitionBySelf("change");

                    $approvalCrudStatus = $this->productApproval->getApprovalCrudStatusId("modify");

                    $this->updateAdminForApprove($hase_menus_specials->special_id,$updatedMenuSpecialColumns,$this->request_specials_child_table_live,$approvalStatus,$approvalCrudStatus,$merchantId,$locationId);
                }    
            }

            $menuUrl = "/hase_menu/".$id."/edit";
            $message = "<a href='".URL::to($staffUrl)."'>".$this->staffName."</a> <strong>".$action."</strong> ".$updatedName." <a href='".URL::to($menuUrl)."'> <strong>".$hase_menu->product_name."</strong></a>";
            PermissionTrait::addActivityLog($action,$message);
            
        } else {
            $hase_menu_stage = new Menu_stage();
            $hase_identity_stage = new Identity_menu_stage();

            $menuDirty = $hase_menu->getDirty();
            $identityDirty = $hase_identity->getDirty();

            if($identityDirty)
            {
                $updatedIdentityColumns = array();
                foreach ($identityDirty as $field => $newdata)
                {
                    $updatedFieldsExist = DB::table('approval_key')->where('field_show', 'LIKE', '%'.$field.'%')->where('key_table','=','identity_product')->get();

                    $olddata = $hase_identity->getOriginal($field);
                    if ($olddata !== $newdata)
                    {
                        if (!$updatedFieldsExist->isEmpty()) 
                        {
                            $hase_identity_stage->$field = $newdata;
                            $updatedIdentityColumns[] =  $field;
                        } else {
                            $noneExistColumns = Identity_menu::findOrFail($hase_identity->identity_id);
                            $noneExistColumns->$field = $newdata;
                            $noneExistColumns->save();
                        }
                    }
                }
                if(count($updatedIdentityColumns));
                {
                    $hase_identity_stage->staff_id = $this->staffId;
                    $hase_identity_stage->save();

                    $approvalStatus = $this->productApproval->ApprovalActionStatusTransitionBySelf("change");

                    $approvalCrudStatus = $this->productApproval->getApprovalCrudStatusId("modify");

                    $this->updateForApprove($hase_identity->identity_id,$hase_identity_stage->identity_id,$updatedIdentityColumns,$this->identity_request_table_live,$this->identity_request_table_stage,$approvalStatus,$approvalCrudStatus,$merchantId,$locationId);
                }
            }

            if($menuDirty)
            {
                $updatedMenuColumns = array();
                foreach ($menuDirty as $field => $newdata)
                {
                    $updatedFieldsExist = DB::table('approval_key')->where('field_show', 'LIKE', '%'.$field.'%')->where('key_table','=','product')->get();
                    $olddata = $hase_menu->getOriginal($field);
                    if ($olddata !== $newdata)
                    {
                        if (!$updatedFieldsExist->isEmpty()) 
                        {
                            $hase_menu_stage->$field = $newdata;
                            $updatedMenuColumns[] =  $field;
                        } else {
                            $noneExistColumns = Menu::findOrFail($id);
                            $noneExistColumns->$field = $newdata;
                            $noneExistColumns->save();
                        }
                    }
                }
                if(count($updatedMenuColumns));
                {
                    $hase_menu_stage->location_id = $locationId;
                    $hase_menu_stage->staff_id = $this->staffId;                    
                    $hase_menu_stage->save();

                    $approvalStatus = $this->productApproval->ApprovalActionStatusTransitionBySelf("change");

                    $approvalCrudStatus = $this->productApproval->getApprovalCrudStatusId("modify");

                    $this->updateForApprove($id,$hase_menu_stage->product_id,$updatedMenuColumns,$this->request_table_live,$this->request_table_stage,$approvalStatus,$approvalCrudStatus,$merchantId,$locationId);
                }
            }

            if($request->special_status) {

                if(isset($request->special_id)) {

                    $hase_menus_specials = Menus_special::
                        firstOrCreate(['product_special_id' => $request->special_id]);

                     $hase_specials = Special::firstOrCreate(['special_id' => $hase_menus_specials->special_id]);    

                } else {
                    $hase_menus_specials = new Menus_special;

                    $hase_specials = new Special;
                }

                if($request->file('special_image') || $request->file('special_image_compact')){

                    $publicDirPath = public_path(env('image_dir_path'));
                    
                    if($this->roleId === 1){
                        $menuImageDirPath = "merchant/$merchantId/menu/${id}/";
                    }else{
                        $menuImageDirPath = "merchant_stage/$merchantId/menu_stage/${id}/";
                    }

                    $imageDirPath = $publicDirPath.$menuImageDirPath;

                    if(!file_exists($imageDirPath)){
                        mkdir($imageDirPath,0777,true);
                    }

                    if($request->file('special_image')){
                        if($this->roleId === 1)
                        {
                            $imagePath = $publicDirPath.$hase_menus_specials->special_image;
                            if (is_file($imagePath)) {
                                unlink($imagePath);
                            }
                        }

                        $menuImage = $request->file('special_image')->getClientOriginalName();
                        $request->file('special_image')->move($imageDirPath,$menuImage);
                        $hase_specials->special_image = "$menuImageDirPath$menuImage";
                    }

                    if($request->file('special_image_compact')){
                        if($this->roleId === 1)
                        {
                            $imagePath = $publicDirPath.$hase_menus_specials->special_image_compact;
                            if (is_file($imagePath)) {
                                unlink($imagePath);
                            }
                        }

                        $menuImageCompact = $request->file('special_image_compact')->getClientOriginalName();
                        $request->file('special_image_compact')->move($imageDirPath,$menuImageCompact);
                        $hase_specials->special_image_compact = "$menuImageDirPath$menuImageCompact";
                    }
                }

                $hase_menus_specials->location_id = $locationId;
                $hase_menus_specials->product_id = $id;

                $hase_specials->special_begin_date = (!is_null($request->start_date))?
                    str_replace("-","",date('Y-m-d',strtotime($request->start_date))) : 0;

                $hase_specials->special_expire_date = (!is_null($request->end_date))?
                        str_replace("-","",date('Y-m-d',strtotime($request->end_date))) : 0;

                $hase_specials->special_price = $request->special_price;
                $hase_specials->special_details = $request->special_details;
                $hase_specials->special_terms = $request->special_terms;
                $hase_specials->special_status = isset($request->special_status)?1:0;
                $hase_specials->special_url = $request->special_url;

                $hase_menu_special_stage = new Menus_special_stage();
                $hase_special_stage = new Special_stage();

                $menuSpecialsdirty = $hase_menus_specials->getDirty();
                $specialsdirty = $hase_specials->getDirty();

                if($specialsdirty)
                {
                    $updatedSpecialColumns = array();
                    foreach ($specialsdirty as $field => $newdata)
                    {
                        $updatedFieldsExist = DB::table('approval_key')->where('field_show', 'LIKE', '%'.$field.'%')->where('key_table','=','special')->get();
                        $olddata = $hase_specials->getOriginal($field);

                        if ($olddata !== $newdata)
                        {
                            if (!$updatedFieldsExist->isEmpty()) 
                            {
                                $hase_special_stage->$field = $newdata;
                                $updatedSpecialColumns[] =  $field;
                            } else {
                                if(isset($request->special_id))
                                {
                                    $noneExistColumns = Special::findOrFail(['special_id' => $hase_menus_specials->special_id]);
                                    $noneExistColumns->$field = $newdata;
                                    $noneExistColumns->save();
                                }
                            }
                        }
                    }
                    if(count($updatedSpecialColumns));
                    {                        
                        $hase_special_stage->staff_id = $this->staffId;                         
                        $hase_special_stage->save();
                        
                        $approvalStatus = $this->productApproval->ApprovalActionStatusTransitionBySelf("change");

                        if(isset($request->special_id)) {

                            $approvalCrudStatus = $this->productApproval->getApprovalCrudStatusId("modify");

                            $this->updateForApprove($hase_specials->special_id,$hase_special_stage->special_id,$updatedSpecialColumns,$this->special_request_table_live,$this->special_request_table_stage,$approvalStatus,$approvalCrudStatus,$merchantId,$locationId);
                        } else {

                            $approvalCrudStatus = $this->productApproval->getApprovalCrudStatusId("insert");

                            $this->addForApprove($hase_special_stage->$hase_menus_specials->special_id,$updatedSpecialColumns,$this->special_request_table_live,$this->special_request_table_stage,$approvalStatus,$approvalCrudStatus,$merchantId,$locationId);
                        }
                    }
                }

                if($menuSpecialsdirty)
                {
                    $updatedMenuSpecialColumns = array();
                    foreach ($menuSpecialsdirty as $field => $newdata)
                    {
                        $updatedFieldsExist = DB::table('approval_key')->where('field_show', 'LIKE', '%'.$field.'%')->where('key_table','=','product_specials')->get();
                        $olddata = $hase_menus_specials->getOriginal($field);

                        if ($olddata !== $newdata)
                        {
                            if (!$updatedFieldsExist->isEmpty()) 
                            {
                                $hase_menu_special_stage->$field = $newdata;
                                $updatedMenuSpecialColumns[] =  $field;
                            } else {
                                if(isset($request->special_id))
                                {
                                    $noneExistColumns = Menus_special::findOrFail(['product_special_id' => $hase_menus_specials->product_special_id]);
                                    $noneExistColumns->$field = $newdata;

                                    $noneExistColumns->save();
                                }
                            }
                        }
                    }
                    if(count($updatedMenuSpecialColumns));
                    {
                        $hase_menu_special_stage->location_id = $locationId;
                        $hase_menu_special_stage->staff_id = $this->staffId;
                        $hase_menu_special_stage->product_id = $id;
                        $hase_menu_special_stage->save();
                        
                        $approvalStatus = $this->productApproval->ApprovalActionStatusTransitionBySelf("change");

                        if(isset($request->special_id)) {

                            $approvalCrudStatus = $this->productApproval->getApprovalCrudStatusId("modify");

                            $this->updateForApprove($hase_menus_specials->product_special_id,$hase_menu_special_stage->product_special_id,$updatedMenuSpecialColumns,$this->request_specials_child_table_live,$this->request_specials_child_table_stage,$approvalStatus,$approvalCrudStatus,$merchantId,$locationId);
                        } else {

                            $approvalCrudStatus = $this->productApproval->getApprovalCrudStatusId("insert");

                            $this->addForApprove($hase_menu_special_stage->special_id,$updatedMenuSpecialColumns,$this->request_specials_child_table_live,$this->request_specials_child_table_stage,$approvalStatus,$approvalCrudStatus,$merchantId,$locationId);
                        }
                    }
                }
                
            } else {
                if(isset($request->special_id)){
                    $hase_menus_special_stage = new Menus_special_stage();

                    $hase_menus_special_stage->staff_id = $this->staffId;
                    $hase_menus_special_stage->location_id = $locationId;
                    $hase_menus_special_stage->product_id = $id;
                    $hase_menus_special_stage->special_status = 0;
                    $hase_menus_special_stage->save();

                    $updatemenuSpecialColumn = array('special_status');
                    
                    $approvalStatus = $this->productApproval->ApprovalActionStatusTransitionBySelf("change");

                    $approvalCrudStatus = $this->productApproval->getApprovalCrudStatusId("modify");

                    $this->updateForApprove($request->special_id,$hase_menus_special_stage->special_id,$updatemenuSpecialColumn,$this->request_specials_child_table_live,$this->request_specials_child_table_stage,$approvalStatus,$approvalCrudStatus,$merchantId,$locationId);
                }    
            }

        }

        //----------------------------------------------------------//

        $retails_type_list = array();
        if($this->roleId === 1){
            if(isset($request->style_type_id) && count($request->style_type_id)){

                $retail_style_list_deleted = Merchant_retail_style_list::whereNotIn('style_type_id', $request->style_type_id)
                    ->where('merchant_id',$merchantId)
                    ->where('location_id',$locationId)
                    ->where('product_id',$id)
                    ->get();


                Merchant_retail_style_list::whereNotIn('style_type_id', $request->style_type_id)
                ->where('merchant_id',$merchantId)
                ->where('location_id',$locationId)
                ->where('product_id',$id)
                ->delete();

                if(count($retail_style_list_deleted))
                {
                    foreach ($retail_style_list_deleted as $key => $value) {
                        $updateStyleColumn = array('product_id','style_type_id');

                        $approvalStatus = $this->productApproval->ApprovalActionStatusTransitionBySelf("change");

                        $approvalCrudStatus = $this->productApproval->getApprovalCrudStatusId("delete");

                        $this->updateAdminForApprove($value['style_list_id'],$updateStyleColumn,$this->request_style_list_live,$approvalStatus,$approvalCrudStatus,$merchantId,$locationId);
                    }
                }
                foreach ($request->style_type_id as $key => $value) {
                    $isExist = Merchant_retail_style_list::
                        where('merchant_id', '=', $merchantId)
                        ->where('location_id',$locationId)
                        ->where('style_type_id',$value)
                        ->where('product_id',$id)
                        ->first();
                    if(is_null($isExist)){

                        $styleListSet = array(
                            'merchant_id' => $merchantId,
                            'location_id' => $locationId,
                            'style_type_id'=>$value,
                            'product_id' => $id,
                            'priority'=>$request->styles[$value]['priority'],
                            /*'enable'=>isset($request->enable) ? 1 : 0 ,*/
                        );

                        $admin_list_id = Merchant_retail_style_list::insertGetId($styleListSet);

                        $updateStyleColumn = array('location_id','style_type_id','priority','enable');
                        
                        $approvalStatus = $this->productApproval->ApprovalActionStatusTransitionBySelf("change");

                        $approvalCrudStatus = $this->productApproval->getApprovalCrudStatusId("insert");

                        $this->addAdminForApprove($admin_list_id,$updateStyleColumn,$this->request_style_list_live,$approvalStatus,$approvalCrudStatus,$merchantId,$locationId);


                    } else {
                        $isExist->priority = $request->styles[$value]['priority'];
                        /*$isExist->enable = isset($request->enable) ? 1 : 0;    */
                        $isExist->update();
                    }
                }
            } else {

                $retail_style_list_deleted = Merchant_retail_style_list::where('merchant_id',$merchantId)
                    ->where('location_id',$locationId)
                    ->where('product_id',$id)
                    ->get();

                Merchant_retail_style_list::
                    where('merchant_id',$merchantId)
                    ->where('location_id',$locationId)
                    ->where('product_id',$id)
                    ->delete();

                if(count($retail_style_list_deleted))
                {
                    foreach ($retail_style_list_deleted as $key => $value) {
                        $updateStyleColumn = array('product_id','style_type_id');

                        $approvalStatus = $this->productApproval->ApprovalActionStatusTransitionBySelf("change");

                        $approvalCrudStatus = $this->productApproval->getApprovalCrudStatusId("delete");

                        $this->updateAdminForApprove($value['style_list_id'],$updateStyleColumn,$this->request_style_list_live,$approvalStatus,$approvalCrudStatus,$merchantId,$locationId);
                    }
                }
            }
        } else {
            if(isset($request->style_type_id) && count($request->style_type_id)){
                $retail_style_list_deleted = Merchant_retail_style_list::whereNotIn('style_type_id', $request->style_type_id)
                    ->where('merchant_id',$merchantId)
                    ->where('location_id',$locationId)
                    ->where('product_id',$id)
                    ->get();
            } else {
                $retail_style_list_deleted = Merchant_retail_style_list::
                    where('merchant_id',$merchantId)
                    ->where('location_id',$locationId)
                    ->where('product_id',$id)
                    ->get();
            }

            if(count($retail_style_list_deleted))
            {
                foreach ($retail_style_list_deleted as $key => $value) {

                    $hase_merchant_retail_style_list_stage = new Merchant_retail_style_list_stage();

                    $hase_merchant_retail_style_list_stage->staff_id = $this->staffId;
                    $hase_merchant_retail_style_list_stage->merchant_id = $value['merchant_id'];
                    $hase_merchant_retail_style_list_stage->location_id = $value['location_id'];
                    $hase_merchant_retail_style_list_stage->product_id = $id;

                    $hase_merchant_retail_style_list_stage->style_type_id = $value['style_type_id'];
                    $hase_merchant_retail_style_list_stage->priority = $value['priority'];

                    /*$hase_merchant_retail_style_list_stage->enable = 0;*/

                    $hase_merchant_retail_style_list_stage->save();


                    $updateStyleColumn = array('product_id','style_type_id');
                    
                    $approvalStatus = $this->productApproval->ApprovalActionStatusTransitionBySelf("change");

                    $approvalCrudStatus = $this->productApproval->getApprovalCrudStatusId("delete");

                    $this->updateForApprove($value['style_list_id'],$hase_merchant_retail_style_list_stage->style_list_id,$updateStyleColumn,$this->request_style_list_live,$this->request_style_list_stage,$approvalStatus,$approvalCrudStatus,$merchantId,$locationId);

                }
            }

            if(isset($request->style_type_id) && count($request->style_type_id)) {
                foreach ($request->style_type_id as $key => $value) {
                    $isExist = Merchant_retail_style_list::
                        where('merchant_id', '=', $merchantId)
                        ->where('location_id',$locationId)
                        ->where('product_id',$id)
                        ->where('style_type_id',$value)
                        ->first();

                    if(is_null($isExist)){
                        $hase_merchant_retail_style_list = new Merchant_retail_style_list_stage();

                        $hase_merchant_retail_style_list->staff_id = $this->staffId;
                        $hase_merchant_retail_style_list->merchant_id = $merchantId;
                        $hase_merchant_retail_style_list->location_id = $locationId;
                        $hase_merchant_retail_style_list->product_id = $id;
                        $hase_merchant_retail_style_list->style_type_id = $value;
                        $hase_merchant_retail_style_list->priority = $request->styles[$value]['priority'];
                        /*$hase_merchant_retail_style_list->enable = 
                                                isset($request->enable) ? 1 : 0 ;*/
                        $hase_merchant_retail_style_list->save();


                        $updateStyleColumn = array('style_type_id','priority','enable');
                        
                        $approvalStatus = $this->productApproval->ApprovalActionStatusTransitionBySelf("change");

                        $approvalCrudStatus = $this->productApproval->getApprovalCrudStatusId("insert");

                        $this->addForApprove($hase_merchant_retail_style_list->style_list_id,$updateStyleColumn,$this->request_style_list_live,$this->request_style_list_stage,$approvalStatus,$approvalCrudStatus,$merchantId,$locationId);
                    }
                }
            }
        }

        $staffUrl = "/hase_staff/".$this->staffId."/edit";
        $action="updated";
        $message = "<a href='".URL::to($staffUrl)."'>".$this->staffName."</a> <strong>updated</strong> style";
        PermissionTrait::addActivityLog($action,$message);
        //----------------------------------------------------------//

        $retails_type_list = array();

        if($this->roleId === 1){

            if(isset($request->options) && count($request->options)){
                
                $categoryList = array();
                foreach ($request->options as $key => $category) {
                    $categoryList[] = $category['category_type_id'];
                }

                $categoryListDeleted = Merchant_retail_category_list::
                    whereNotIn('category_type_id', $categoryList)
                    ->where('merchant_id','=',$merchantId)
                    ->where('product_id',$id)
                    ->where('location_id',$locationId)
                    ->get();

                Merchant_retail_category_list::
                    whereNotIn('category_type_id', $categoryList)
                    ->where('merchant_id',$merchantId)
                    ->where('location_id',$locationId)
                    ->where('product_id',$id)
                    ->delete();

                if(count($categoryListDeleted)){

                    foreach ($categoryListDeleted as $key => $value) {

                        $updateCategoryColumn = array('product_id','category_type_id');

                        $approvalStatus = $this->productApproval->ApprovalActionStatusTransitionBySelf("change");

                        $approvalCrudStatus = $this->productApproval->getApprovalCrudStatusId("delete");

                        $this->updateAdminForApprove($value['category_list_id'],$updateCategoryColumn,$this->request_category_list_live,$approvalStatus,$approvalCrudStatus,$merchantId,$locationId);
                    }
                }

                $optionListDeleted = Merchant_retail_category_option_list::
                    whereNotIn('category_type_id', $categoryList)
                    ->where('merchant_id',$merchantId)
                    ->where('location_id',$locationId)
                    ->where('product_id',$id)
                    ->get();

                Merchant_retail_category_option_list::
                    whereNotIn('category_type_id', $categoryList)
                    ->where('merchant_id','=',$merchantId)
                    ->where('location_id',$locationId)
                    ->where('product_id',$id)
                    ->delete();

                if(count($optionListDeleted)){

                    foreach ($optionListDeleted as $key => $value) {

                        $updateCategoryColumn = array('product_id','category_type_id','category_option_type_id');

                        $approvalStatus = $this->productApproval->ApprovalActionStatusTransitionBySelf("change");

                        $approvalCrudStatus = $this->productApproval->getApprovalCrudStatusId("delete");

                        $this->updateAdminForApprove($value['category_option_list_id'],$updateCategoryColumn,$this->option_request_table_live,$approvalStatus,$approvalCrudStatus,$merchantId,$locationId);
                    }
                }

                foreach ($request->options as $key => $category) {
                    
                    $isExist = Merchant_retail_category_list::
                        where('merchant_id', '=', $merchantId)
                        ->where('location_id',$locationId)
                        ->where('product_id',$id)
                        ->where('category_type_id', $category['category_type_id'])
                        ->first();

                    if(is_null($isExist)){
                        $categoryListSet = array(
                            'merchant_id' => $merchantId,
                            'location_id' => $locationId,
                            'product_id' => $id,
                            'category_type_id' => $category['category_type_id']
                        );
                        $admin_category_id = Merchant_retail_category_list::insertGetId($categoryListSet);

                        $updateCategoryColumn = array('category_type_id');
                        
                        $approvalStatus = $this->productApproval->ApprovalActionStatusTransitionBySelf("change");

                        $approvalCrudStatus = $this->productApproval->getApprovalCrudStatusId("insert");

                        $this->addAdminForApprove($admin_category_id,$updateCategoryColumn,$this->request_category_list_live,$approvalStatus,$approvalCrudStatus,$merchantId,$locationId);
                    }

                    if(isset($category['category_option_type_id']) && count($category['category_option_type_id'])){

                        $optionListDeleted = Merchant_retail_category_option_list::
                            whereNotIn('category_option_type_id', $category['category_option_type_id'])
                            ->where('merchant_id',$merchantId)
                            ->where('location_id',$locationId)
                            ->where('product_id',$id)
                            ->where('category_type_id',$category['category_type_id'])
                            ->get();

                        Merchant_retail_category_option_list::
                            where('category_type_id', $category['category_type_id'])
                            ->where('merchant_id','=',$merchantId)
                            ->where('location_id',$locationId)
                            ->where('product_id',$id)
                            ->whereNotIn('category_option_type_id',$category['category_option_type_id'])
                            ->delete();

                        if(count($optionListDeleted)){

                            foreach ($optionListDeleted as $key => $value) {

                                $updateCategoryColumn = array('product_id','category_type_id','category_option_type_id');

                                $approvalStatus = $this->productApproval->ApprovalActionStatusTransitionBySelf("change");

                                $approvalCrudStatus = $this->productApproval->getApprovalCrudStatusId("delete");

                                $this->addAdminForApprove($value['category_option_list_id'],$updateCategoryColumn,$this->option_request_table_live,$approvalStatus,$approvalCrudStatus,$merchantId,$locationId);
                            }
                        }
                        foreach ($category['category_option_type_id'] as $key => $optionID) {
                            
                            $isExist = Merchant_retail_category_option_list::
                                    where('merchant_id',$merchantId)
                                    ->where('location_id',$locationId)
                                    ->where('product_id',$id)
                                    ->where('category_type_id',$category['category_type_id'])
                                    ->where('category_option_type_id',$optionID)
                                    ->first();

                            if(is_null($isExist)){

                                $optionListSet = array(
                                    'merchant_id' => $merchantId,
                                    'location_id' => $locationId,
                                    'product_id' => $id,
                                    'category_type_id' => $category['category_type_id'],
                                    'category_option_type_id' => $optionID,
                                    'priority' => $category['priority'],
                                    'enable' => isset($category['enable']) ? 1 : 0
                                );

                                $admin_category_option_id = Merchant_retail_category_option_list::insertGetId($optionListSet);

                                $updateCategoryColumn = array('product_id','category_type_id','category_option_type_id');

                                $approvalStatus = $this->productApproval->ApprovalActionStatusTransitionBySelf("change");

                                $approvalCrudStatus = $this->productApproval->getApprovalCrudStatusId("delete");

                                $this->addAdminForApprove($admin_category_option_id,$updateCategoryColumn,$this->option_request_table_live,$approvalStatus,$approvalCrudStatus,$merchantId,$locationId);
                            }else{
                                $optionListSet = array(
                                        'priority' => $category['priority'],
                                        'enable' => isset($category['enable']) ? 1 : 0
                                );
                                Merchant_retail_category_option_list::
                                    where('merchant_id',$merchantId)
                                    ->where('location_id',$locationId)
                                    ->where('product_id',$id)
                                    ->where('category_type_id',$category['category_type_id'])
                                    ->where('category_option_type_id',$optionID)
                                    ->update($optionListSet);
                            }
                        }
                    }else{
                        $optionListDeleted = Merchant_retail_category_option_list::
                            where('merchant_id',$merchantId)
                            ->where('location_id',$locationId)
                            ->where('product_id',$id)
                            ->where('category_type_id',$category['category_type_id'])
                            ->get();

                        Merchant_retail_category_option_list::
                            where('merchant_id',$merchantId)
                            ->where('location_id',$locationId)
                            ->where('product_id',$id)
                            ->where('category_type_id',$category['category_type_id'])
                            ->delete();

                        if(count($optionListDeleted)){

                            foreach ($optionListDeleted as $key => $value) {

                                $updateCategoryColumn = array('product_id','category_type_id','category_option_type_id');

                                $approvalStatus = $this->productApproval->ApprovalActionStatusTransitionBySelf("change");

                                $approvalCrudStatus = $this->productApproval->getApprovalCrudStatusId("delete");

                                $this->updateAdminForApprove($value['category_option_list_id'],$updateCategoryColumn,$this->option_request_table_live,$approvalStatus,$approvalCrudStatus,$merchantId,$locationId);
                            }
                        }
                    }
                }
            }else{

                $categoryListDeleted = Merchant_retail_category_list::
                    where('merchant_id','=',$merchantId)
                    ->where('product_id',$id)
                    ->where('location_id',$locationId)
                    ->get();
                
                Merchant_retail_category_list::
                    where('merchant_id',$merchantId)
                    ->where('location_id',$locationId)
                    ->where('product_id',$id)
                    ->delete();

                if(count($categoryListDeleted)){

                    foreach ($categoryListDeleted as $key => $value) {

                        $updateCategoryColumn = array('product_id','category_type_id');

                        $approvalStatus = $this->productApproval->ApprovalActionStatusTransitionBySelf("change");

                        $approvalCrudStatus = $this->productApproval->getApprovalCrudStatusId("delete");

                        $this->updateAdminForApprove($value['category_list_id'],$updateCategoryColumn,$this->request_category_list_live,$approvalStatus,$approvalCrudStatus,$merchantId,$locationId);
                    }
                }

                $optionListDeleted = Merchant_retail_category_option_list::
                    where('merchant_id',$merchantId)
                    ->where('location_id',$locationId)
                    ->where('product_id',$id)
                    ->get();

                Merchant_retail_category_option_list::
                    where('merchant_id',$merchantId)
                    ->where('location_id',$locationId)
                    ->where('product_id',$id)
                    ->delete();

                if(count($optionListDeleted)){

                    foreach ($optionListDeleted as $key => $value) {

                        $updateCategoryColumn = array('product_id','category_type_id','category_option_type_id');

                        $approvalStatus = $this->productApproval->ApprovalActionStatusTransitionBySelf("change");

                        $approvalCrudStatus = $this->productApproval->getApprovalCrudStatusId("delete");

                        $this->updateAdminForApprove($value['category_option_list_id'],$updateCategoryColumn,$this->option_request_table_live,$approvalStatus,$approvalCrudStatus,$merchantId,$locationId);
                    }
                }
            }

        }else{
            
            if(isset($request->options) && count($request->options)){
                
                $categoryList = array();
                foreach ($request->options as $key => $category) {
                    $categoryList[] = $category['category_type_id'];
                }

                $categoryListDeleted = Merchant_retail_category_list::
                    whereNotIn('category_type_id', $categoryList)
                    ->where('merchant_id',$merchantId)
                    ->where('location_id',$locationId)
                    ->where('product_id',$id)
                    ->get();
            }else{

                $categoryListDeleted =  Merchant_retail_category_list::
                    where('merchant_id',$merchantId)
                    ->where('location_id',$locationId)
                    ->where('product_id',$id)
                    ->get();
            }

            if(count($categoryListDeleted)){

                foreach ($categoryListDeleted as $key => $value) {

                    $hase_merchant_retail_category_list = new Merchant_retail_category_list_stage();

                    $hase_merchant_retail_category_list->staff_id = $this->staffId;
                    $hase_merchant_retail_category_list->merchant_id = $value['merchant_id'];
                    $hase_merchant_retail_category_list->location_id = $value['location_id'];
                    $hase_merchant_retail_category_list->product_id = $id;
                    $hase_merchant_retail_category_list->category_type_id = $value['category_type_id'];


                    $hase_merchant_retail_category_list->save();

                    $updateCategoryColumn = array('product_id','category_type_id');

                    $approvalStatus = $this->productApproval->ApprovalActionStatusTransitionBySelf("change");

                    $approvalCrudStatus = $this->productApproval->getApprovalCrudStatusId("delete");

                    $this->updateForApprove($value['category_list_id'],$hase_merchant_retail_category_list->category_list_id,$updateCategoryColumn,$this->request_category_list_live,$this->request_category_list_stage,$approvalStatus,$approvalCrudStatus,$merchantId,$locationId);
                }
            }
            if(isset($request->options)){
                foreach ($request->options as $key => $category) {
                
                    if(isset($category['category_option_type_id']) && count($category['category_option_type_id'])){
                        $optionListDeleted = Merchant_retail_category_option_list::
                            whereNotIn('category_option_type_id', $category['category_option_type_id'])
                            ->where('merchant_id',$merchantId)
                            ->where('location_id',$locationId)
                            ->where('product_id',$id)
                            ->where('category_type_id',$category['category_type_id'])
                            ->get();
                    }else{

                        $optionListDeleted =  Merchant_retail_category_option_list::
                            where('merchant_id','=',$merchantId)
                            ->where('location_id',$locationId)
                            ->where('product_id',$id)
                            ->where('category_type_id',$category['category_type_id'])
                            ->get();
                    }
                    
                    if(count($optionListDeleted)){

                        foreach ($optionListDeleted as $key => $value) {

                            $hase_merchant_retail_category_option_list = new Merchant_retail_category_option_list_stage();

                            $hase_merchant_retail_category_option_list->staff_id = $this->staffId;

                            $hase_merchant_retail_category_option_list->merchant_id = $value['merchant_id'];

                            $hase_merchant_retail_category_option_list->location_id = $value['location_id'];

                            $hase_merchant_retail_category_option_list->product_id = $id;

                            $hase_merchant_retail_category_option_list->category_type_id = $value['category_type_id'];

                            $hase_merchant_retail_category_option_list->category_option_type_id = $value['category_option_type_id'];

                            $hase_merchant_retail_category_option_list->priority = $value['priority'];

                            /*$hase_merchant_retail_category_option_list->enable = 0;*/

                            $hase_merchant_retail_category_option_list->save();

                            $updateCategoryColumn = array('product_id','category_type_id','category_option_type_id');

                            $approvalStatus = $this->productApproval->ApprovalActionStatusTransitionBySelf("change");

                            $approvalCrudStatus = $this->productApproval->getApprovalCrudStatusId("delete");

                            $this->updateForApprove($value['category_option_list_id'],$hase_merchant_retail_category_option_list->category_option_list_id,$updateCategoryColumn,$this->option_request_table_live,$this->option_request_table_stage,$approvalStatus,$approvalCrudStatus,$merchantId,$locationId);
                        }
                    }
                

                    $isExist = Merchant_retail_category_list::
                        where('merchant_id', $merchantId)
                        ->where('location_id',$locationId)
                        ->where('product_id',$id)
                        ->where('category_type_id',$category['category_type_id'])
                        ->first();

                    if(is_null($isExist)){
                        
                        $hase_merchant_retail_category_list = new Merchant_retail_category_list_stage();

                        $hase_merchant_retail_category_list->staff_id = $this->staffId;
                        $hase_merchant_retail_category_list->merchant_id = $merchantId;
                        $hase_merchant_retail_category_list->location_id = $locationId;
                        $hase_merchant_retail_category_list->product_id = $id;
                        $hase_merchant_retail_category_list->category_type_id = $category['category_type_id'];
                        

                        $hase_merchant_retail_category_list->save();

                        $updateCategoryColumn = array('category_type_id');
                        
                        $approvalStatus = $this->productApproval->ApprovalActionStatusTransitionBySelf("change");

                        $approvalCrudStatus = $this->productApproval->getApprovalCrudStatusId("insert");

                        $this->addForApprove($hase_merchant_retail_category_list->category_list_id,$updateCategoryColumn,$this->request_category_list_live,$this->request_category_list_stage,$approvalStatus,$approvalCrudStatus,$merchantId,$locationId);
                    }

                    if(isset($category['category_option_type_id']) && count($category['category_option_type_id'])){
                        foreach ($category['category_option_type_id'] as $key => $optionID) {

                            $isExist = Merchant_retail_category_option_list::
                                    where('merchant_id',$merchantId)
                                    ->where('location_id',$locationId)
                                    ->where('product_id',$id)
                                    ->where('category_type_id',$category['category_type_id'])
                                    ->where('category_option_type_id',$optionID)
                                    ->first();

                            if(is_null($isExist) || !$isExist->enable){

                                $hase_merchant_retail_category_option_list = new Merchant_retail_category_option_list_stage();

                                $hase_merchant_retail_category_option_list->staff_id = $this->staffId;

                                $hase_merchant_retail_category_option_list->merchant_id = $merchantId;

                                $hase_merchant_retail_category_option_list->location_id = $locationId;

                                $hase_merchant_retail_category_option_list->product_id = $id;

                                $hase_merchant_retail_category_option_list->category_type_id = $category['category_type_id'];

                                $hase_merchant_retail_category_option_list->category_option_type_id = $optionID;

                                $hase_merchant_retail_category_option_list->priority = $category['priority'];

                                if(!is_null($isExist) && !$isExist->enable){
                                    $hase_merchant_retail_category_option_list->enable = 1;
                                }else{
                                    $hase_merchant_retail_category_option_list->enable = isset($category['enable']) ? 1 : 0 ;
                                }
                                
                                $hase_merchant_retail_category_option_list->save();

                                $updateCategoryColumn = array('category_type_id','category_option_type_id','priority','enable');

                                $approvalStatus = $this->productApproval->ApprovalActionStatusTransitionBySelf("change");

                                $approvalCrudStatus = $this->productApproval->getApprovalCrudStatusId("insert");

                                $this->addForApprove($hase_merchant_retail_category_option_list->category_option_list_id,$updateCategoryColumn,$this->option_request_table_live,$this->option_request_table_stage,$approvalStatus,$approvalCrudStatus,$merchantId,$locationId);
                            }
                        }
                    }
                } 
            }  
        }
        
        $staffUrl = "/hase_staff/".$this->staffId."/edit";
        $action="updated";
        $message = "<a href='".URL::to($staffUrl)."'>".$this->staffName."</a> <strong>updated</strong> category";

        PermissionTrait::addActivityLog($action,$message);

        //----------------------------------------------------------//

        $message = "<a href='".URL::to($staffUrl)."'>".$this->staffName."</a> <strong> ".$action." </strong> ".$updatedName." <strong> $hase_menu->product_name </strong>";
        
        PermissionTrait::addActivityLog($action,$message);

        Session::flash('type', 'success');

        if(Requests::segment(1) === "hase_product") {
            Session::flash('msg', 'Product Successfully Updated');  
        } else {
            Session::flash('msg', 'Menu Successfully Updated'); 
        }

        if($this->roleId === 1){
            if ($request->submitBtn === "Save") {
                return redirect(Requests::segment(1).'/'. $hase_menu->product_id . '/edit');
            }else{
               return redirect(Requests::segment(1));
            }
        }else{
            return redirect(Requests::segment(1));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param    int $id
     * @return  \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $haseMenuAceess = $this->permissionDetails('Hase_menu','delete');
        if($haseMenuAceess) {
           if(Requests::segment(1) === "hase_product"){
               $updatedName = 'Product';
           }else{
               $updatedName = 'Menu';
           }
           $hase_menu = Menu::
                    distinct('product.product_id')
                    ->select('product.*','merchant.merchant_id')
                    ->leftjoin('location_list','location_list.list_id','=','product.location_id')
                    ->leftjoin('merchant','merchant.merchant_id','=','product.identity_id')
                    ->where('product_id',$id)
                    ->get()->first();

           if($this->roleId === 1) {
               $hase_menu->delete();
               $approvalStatus = $this->productApproval->ApprovalActionStatusTransitionBySelf("change");

               $approvalCrudStatus = $this->productApproval->getApprovalCrudStatusId("delete");

               $updatedMenuColumns = array('product_id','product_status');
               $this->updateAdminForApprove($id,$updatedMenuColumns,$this->request_table_live,$approvalStatus,$approvalCrudStatus,$hase_menu->merchant_id,$hase_menu->location_id);
           } else {
               $hase_menu_stage = new Menu_stage();
               $hase_menu_stage->staff_id = $this->staffId;
               $hase_menu_stage->product_id = $id;
               $hase_menu_stage->product_status = 0;
               $hase_menu_stage->save();
               
               $approvalStatus = $this->productApproval->ApprovalActionStatusTransitionBySelf("change");

               $approvalCrudStatus = $this->productApproval->getApprovalCrudStatusId("delete");

               $updatedMenuColumns = array('product_id','product_status');
               $this->updateForApprove($id,$hase_menu_stage->product_id,$updatedMenuColumns,$this->request_table_live,$this->request_table_stage,$approvalStatus,$approvalCrudStatus,$hase_menu->merchant_id,$hase_menu->location_id);
           }

           $staffUrl = "/hase_staff/".$this->staffId."/edit";
           $action="deleted";
           $menuUrl = "/hase_menu/".$hase_menu->product_id."/edit";
           $message = "<a href='".URL::to($staffUrl)."'>".$this->staffName."</a> <strong>".$action."</strong> ".$updatedName." <a href='".URL::to($menuUrl)."'> <strong>".$hase_menu->product_name."</strong></a>";
           PermissionTrait::addActivityLog($action,$message);


           Session::flash('type', 'success'); 
           Session::flash('msg', 'Menu Successfully Deleted');
           return redirect(Requests::segment(1));
        } else {
           return Redirect::back()->with('message', 'You are not authorized to use this functionality!');
        }
    }
}
