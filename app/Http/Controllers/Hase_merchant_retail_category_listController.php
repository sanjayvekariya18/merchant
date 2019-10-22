<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\App;
use Illuminate\Http\Request;
use App\Http\Controllers\PermissionsController;
use App\Helpers\ConnectionManager;
use App\Http\Controllers\ProductApprovalController;
use App\Merchant_retail_category_list;
use App\Merchant_retail_category_option;
use App\Merchant_retail_category_option_list;
use App\Merchant_retail_category_list_stage;
use App\Merchant_retail_category_option_list_stage;
use App\Merchant_retail_category_type;
use App\Merchant_city_list;
use App\Merchant;
use App\Merchant_type;
use App\Location_list;
use App\Http\Traits\PermissionTrait;
use URL;
use Session;
use DB;
use Redirect;
use Auth;

/**
 * Class Hase_merchant_retail_category_listController.
 *
 */
class Hase_merchant_retail_category_listController extends PermissionsController
{
    use PermissionTrait;

    public function __construct()
    {
        parent::__construct();

        $connectionStatus = ConnectionManager::setDbConfig('Hase_category_list');

        if ($connectionStatus['type'] === "error") {
            Session::flash('type', $connectionStatus['type']);
            Session::flash('msg', $connectionStatus['message']);
            return Redirect::back()->send();
        }

        $this->request_table_live = 51;
        $this->option_request_table_live = 53;
        $this->request_table_stage = 52;
        $this->option_request_table_stage = 54;
        $this->productApproval = new ProductApprovalController();
    }
    /**
     * Display a listing of the resource.
     *
     * @return  \Illuminate\Http\Response
     */
    public function index()
    {
        $merchantType = session()->has('merchantType') ? session()->get('merchantType') :"";
        if($this->permissionDetails('Hase_category_list','access')) {
            $title = 'Index - hase category list';
            $searchCategory = '';
            $merchant_parent_types = Merchant_type::all()->where('merchant_parent_id',0);
            $permissions = $this->getPermission("Hase_category_list");

            if($this->merchantId === 0) {
                $hase_merchant_retail_category_lists = Merchant_retail_category_list::
                        select('merchant_retail_category_list.category_list_id',DB::raw("group_concat(distinct identity_merchant_retail_category_type.identity_name SEPARATOR ', ') as category_name"),DB::raw('(CASE WHEN (postal.postal_premise != "" and postal.postal_subpremise != "") THEN CONCAT(postal.postal_premise,", ",SUBSTR(postal.postal_subpremise,1,10))
                              WHEN (postal.postal_premise != "") THEN postal.postal_premise                              
                              WHEN (postal.postal_subpremise !="") THEN SUBSTR(postal.postal_subpremise,1,10)
                              ELSE CONCAT(postal.postal_street_number," ",postal.postal_route)   
                        END) as location_name'),'identity_merchant.identity_name as merchant_name','merchant.merchant_type_id as merchant_type')
                        ->join('merchant_retail_category_type','merchant_retail_category_list.category_type_id','=','merchant_retail_category_type.category_type_id')
                        ->join('identity_merchant_retail_category_type','identity_merchant_retail_category_type.identity_id','=','merchant_retail_category_type.identity_id')
                        ->join('location_list','location_list.list_id','merchant_retail_category_list.location_id')
                        ->join('postal','postal.postal_id','=','location_list.postal_id')
                        ->join('merchant','merchant_retail_category_list.merchant_id','=','merchant.merchant_id')
                        ->join('identity_merchant','identity_merchant.identity_id','=','merchant.identity_id')
                        ->groupBy('merchant_retail_category_list.location_id','identity_merchant.identity_name')
                        ->orderBy('merchant_retail_category_list.category_list_id','desc')
                        ->where('merchant.merchant_type_id','=',$merchantType)
                        ->paginate(25);
            } else {
                $merchant_data = Merchant::select('merchant.merchant_id','identity_merchant.identity_name as merchant_name')
                    ->leftjoin('identity_merchant','identity_merchant.identity_id','=','merchant.identity_id')
                    ->where('merchant.merchant_id',$this->merchantId)
                    ->get()->first();
                
                if($this->roleId === 4)
                {
                    $hase_merchant_retail_category_lists = Merchant_retail_category_list::select('merchant_retail_category_list.category_list_id',DB::raw("group_concat(distinct identity_merchant_retail_category_type.identity_name SEPARATOR ', ') as category_name"),DB::raw('(CASE WHEN (postal.postal_premise != "" and postal.postal_subpremise != "") THEN CONCAT(postal.postal_premise,", ",SUBSTR(postal.postal_subpremise,1,10))
                              WHEN (postal.postal_premise != "") THEN postal.postal_premise                              
                              WHEN (postal.postal_subpremise !="") THEN SUBSTR(postal.postal_subpremise,1,10)
                              ELSE CONCAT(postal.postal_street_number," ",postal.postal_route)   
                        END) as location_name'),'identity_merchant.identity_name as merchant_name','merchant.merchant_type_id as merchant_type')
                        ->join('merchant_retail_category_type','merchant_retail_category_list.category_type_id','=','merchant_retail_category_type.category_type_id')
                        ->join('identity_merchant_retail_category_type','identity_merchant_retail_category_type.identity_id','=','merchant_retail_category_type.identity_id')
                        ->join('location_list','location_list.list_id','merchant_retail_category_list.location_id')
                        ->join('postal','postal.postal_id','=','location_list.postal_id')
                        ->join('merchant','merchant_retail_category_list.merchant_id','=','merchant.merchant_id')
                        ->join('identity_merchant','identity_merchant.identity_id','=','merchant.identity_id')
                        ->groupBy('merchant_retail_category_list.location_id','identity_merchant.identity_name')
                        ->orderBy('merchant_retail_category_list.category_list_id','desc')
                        ->where('merchant.merchant_id','=',$this->merchantId)
                        ->paginate(25);
                }else{
                    $hase_merchant_retail_category_lists = Merchant_retail_category_list::select('merchant_retail_category_list.category_list_id',DB::raw("group_concat(distinct identity_merchant_retail_category_type.identity_name SEPARATOR ', ') as category_name"),DB::raw('(CASE WHEN (postal.postal_premise != "" and postal.postal_subpremise != "") THEN CONCAT(postal.postal_premise,", ",SUBSTR(postal.postal_subpremise,1,10))
                              WHEN (postal.postal_premise != "") THEN postal.postal_premise                              
                              WHEN (postal.postal_subpremise !="") THEN SUBSTR(postal.postal_subpremise,1,10)
                              ELSE CONCAT(postal.postal_street_number," ",postal.postal_route)   
                        END) as location_name'),'identity_merchant.identity_name as merchant_name','merchant.merchant_type_id as merchant_type')
                        ->join('merchant_retail_category_type','merchant_retail_category_list.category_type_id','=','merchant_retail_category_type.category_type_id')
                        ->join('identity_merchant_retail_category_type','identity_merchant_retail_category_type.identity_id','=','merchant_retail_category_type.identity_id')
                        ->join('location_list','location_list.list_id','merchant_retail_category_list.location_id')
                        ->join('postal','postal.postal_id','=','location_list.postal_id')
                        ->join('merchant','merchant_retail_category_list.merchant_id','=','merchant.merchant_id')
                        ->join('identity_merchant','identity_merchant.identity_id','=','merchant.identity_id')
                        ->groupBy('merchant_retail_category_list.location_id','identity_merchant.identity_name')
                        ->orderBy('merchant_retail_category_list.category_list_id','desc')
                        ->where('merchant_retail_category_list.merchant_id','=',$this->merchantId)
                        ->where('merchant_retail_category_list.location_id','=',$this->locationId)
                        ->paginate(25);
                }
            }
            return view('hase_merchant_retail_category_list.index',compact('hase_merchant_retail_category_lists','permissions','title','merchant_parent_types','merchantType','searchCategory'));
        } else {
            return Redirect::back()->with('message', 'You are not authorized to use this functionality!');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param    \Illuminate\Http\Request  $request
     * @return  \Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        $searchCategory = trim($request->search_category);
        $merchantId = session()->has('merchantId') ? session()->get('merchantId') : '';
        $roleId = session()->has('role') ? session()->get('role') : '';
        $locationId = session()->has('locationId') ? session()->get('locationId') :"";
        echo $merchantType = session()->has('merchantType') ? session()->get('merchantType') :"";
        $merchant_parent_types = Merchant_type::all()->where('merchant_parent_id',0);
        if(!empty($searchCategory)) {
            if($merchantId === 0) {
                $hase_merchant_retail_category_lists = Merchant_retail_category_list::select('merchant_retail_category_list.category_list_id',DB::raw("group_concat(distinct identity_merchant_retail_category_type.identity_name SEPARATOR ', ') as category_name"),DB::raw('(CASE WHEN (postal.postal_premise != "" and postal.postal_subpremise != "") THEN CONCAT(postal.postal_premise,", ",SUBSTR(postal.postal_subpremise,1,10))
                              WHEN (postal.postal_premise != "") THEN postal.postal_premise                              
                              WHEN (postal.postal_subpremise !="") THEN SUBSTR(postal.postal_subpremise,1,10)
                              ELSE CONCAT(postal.postal_street_number," ",postal.postal_route)   
                        END) as location_name'),'identity_merchant.identity_name as merchant_name','merchant.merchant_type_id as merchant_type')
                    ->join('merchant_retail_category_type','merchant_retail_category_list.category_type_id','=','merchant_retail_category_type.category_type_id')
                    ->join('identity_merchant_retail_category_type','identity_merchant_retail_category_type.identity_id','=','merchant_retail_category_type.identity_id')
                    ->join('location_list','location_list.list_id','merchant_retail_category_list.location_id')
                    ->join('postal','postal.postal_id','=','location_list.postal_id')               
                    ->join('merchant','merchant_retail_category_list.merchant_id','=','merchant.merchant_id')
                    ->join('identity_merchant','identity_merchant.identity_id','=','merchant.identity_id')
                    ->distinct('merchant_retail_category_list.category_list_id')
                    ->groupBy('merchant_retail_category_list.location_id','identity_merchant.identity_name')                  
                    ->orderBy('merchant_retail_category_list.category_list_id','desc')
                    ->where('postal.postal_premise', 'LIKE', '%' . $searchCategory . '%')
                    ->orWhere('identity_merchant.identity_name', 'LIKE', '%' . $searchCategory . '%')
                    ->where('merchant.merchant_type_id','=',$merchantType)->paginate(25)->setPath('');

            } else {
                $merchant_data = Hase_merchant::
                    where('merchant_id','=',$merchantId)
                    ->get()->first();
                
                if($roleId === 4)
                {
                    $hase_merchant_retail_category_lists = Merchant_retail_category_list::select('merchant_retail_category_list.category_list_id',DB::raw("group_concat(distinct identity_merchant_retail_category_type.identity_name SEPARATOR ', ') as category_name"),DB::raw('(CASE WHEN (postal.postal_premise != "" and postal.postal_subpremise != "") THEN CONCAT(postal.postal_premise,", ",SUBSTR(postal.postal_subpremise,1,10))
                              WHEN (postal.postal_premise != "") THEN postal.postal_premise                              
                              WHEN (postal.postal_subpremise !="") THEN SUBSTR(postal.postal_subpremise,1,10)
                              ELSE CONCAT(postal.postal_street_number," ",postal.postal_route)   
                        END) as location_name'),'identity_merchant.identity_name as merchant_name','merchant.merchant_type_id as merchant_type')
                        ->join('merchant_retail_category_type','merchant_retail_category_list.category_type_id','=','merchant_retail_category_type.category_type_id')
                        ->join('identity_merchant_retail_category_type','identity_merchant_retail_category_type.identity_id','=','merchant_retail_category_type.identity_id')

                        ->join('location_list','location_list.list_id','merchant_retail_category_list.location_id')
                        ->join('postal','postal.postal_id','=','location_list.postal_id')               
                        ->join('merchant','merchant_retail_category_list.merchant_id','=','merchant.merchant_id')
                        ->join('identity_merchant','identity_merchant.identity_id','=','merchant.identity_id')
                        ->distinct('merchant_retail_category_list.category_list_id')
                        ->groupBy('merchant_retail_category_list.location_id','identity_merchant.identity_name')
                        ->where('location_city.city_name', 'LIKE', '%' . $searchCategory . '%')
                        ->orWhere('identity_merchant.identity_name', 'LIKE', '%' . $searchCategory . '%')
                        ->where('merchant_retail_category_list.merchant_id',"=",$merchantId)
                        ->paginate(25)->setPath('');
                }else{
                    $hase_merchant_retail_category_lists = Merchant_retail_category_list::select('merchant_retail_category_list.category_list_id',DB::raw("group_concat(distinct identity_merchant_retail_category_type.identity_name SEPARATOR ', ') as category_name"),DB::raw('(CASE WHEN (postal.postal_premise != "" and postal.postal_subpremise != "") THEN CONCAT(postal.postal_premise,", ",SUBSTR(postal.postal_subpremise,1,10))
                              WHEN (postal.postal_premise != "") THEN postal.postal_premise                              
                              WHEN (postal.postal_subpremise !="") THEN SUBSTR(postal.postal_subpremise,1,10)
                              ELSE CONCAT(postal.postal_street_number," ",postal.postal_route)   
                        END) as location_name'),'identity_merchant.identity_name as merchant_name','merchant.merchant_type_id as merchant_type')
                        ->join('merchant_retail_category_type','merchant_retail_category_list.category_type_id','=','merchant_retail_category_type.category_type_id')
                        ->join('identity_merchant_retail_category_type','identity_merchant_retail_category_type.identity_id','=','merchant_retail_category_type.identity_id')
                        ->join('location_list','location_list.list_id','merchant_retail_category_list.location_id')
                        ->join('postal','postal.postal_id','=','location_list.postal_id')               
                        ->join('merchant','merchant_retail_category_list.merchant_id','=','merchant.merchant_id')
                        ->join('identity_merchant','identity_merchant.identity_id','=','merchant.identity_id')
                        ->distinct('merchant_retail_category_list.category_list_id')
                        ->groupBy('merchant_retail_category_list.location_id','identity_merchant.identity_name')
                        ->where('location_city.city_name', 'LIKE', '%' . $searchCategory . '%')
                        ->orWhere('identity_merchant.identity_name', 'LIKE', '%' . $searchCategory . '%')
                        ->where('merchant_retail_category_list.merchant_id',"=",$merchantId)
                        ->where('merchant_retail_category_list.location_id',"=",$locationId)->paginate(25)->setPath('');
                }
            }
            $pagination = $hase_merchant_retail_category_lists->appends(array(
                  'search_category' => $searchCategory 
            ));
            $permissions = PermissionTrait::getPermission('Hase_category_list');
            
            if (count($hase_merchant_retail_category_lists) > 0) {
                return view('hase_merchant_retail_category_list.index', compact('hase_merchant_retail_category_lists','title','permissions','merchant_parent_types','merchantType','searchCategory'))->withDetails($hase_merchant_retail_category_lists)->withQuery($searchCategory);
            }
            return view('hase_merchant_retail_category_list.index', compact('hase_merchant_retail_category_lists','permissions','merchant_parent_types','merchantType','searchCategory'))->withMessage('No Details found. Try to search again !');
        } else {
            return redirect('hase_category_list');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return  \Illuminate\Http\Response
     */
    public function create()
    {
        if($this->permissionDetails('Hase_category_list','add')) {
            $title = 'Create - Hase Category List';
            $hase_options = Merchant_retail_category_option::
                select('merchant_retail_category_option.*','identity_merchant_retail_category_option.identity_name as option_name','identity_merchant_retail_category_option.identity_logo as option_image','identity_merchant_retail_category_option.identity_logo_compact as option_image_compact')
                ->join('identity_merchant_retail_category_option','merchant_retail_category_option.identity_id','=','identity_merchant_retail_category_option.identity_id')
                ->get();

            if($this->merchantId === 0) {
                $hase_merchants = Merchant::
                    select('merchant.merchant_id','identity_merchant.identity_name as merchant_name')
                    ->join('identity_merchant','identity_merchant.identity_id','=','merchant.identity_id') 
                    ->where('merchant.merchant_id','!=',0)
                    ->get();
                $hase_locations = array();
            } else {
                $hase_merchants = Merchant::
                    select('merchant.merchant_id','identity_merchant.identity_name as merchant_name','merchant.merchant_type_id')
                    ->join('identity_merchant','identity_merchant.identity_id','=','merchant.identity_id')
                    ->where('merchant_id',$this->merchantId)
                    ->get()->first();

                $hase_locations = Location_list::
                    select('list_id as location_id',DB::raw('(CASE WHEN (postal.postal_premise != "" and postal.postal_subpremise != "") THEN CONCAT(postal.postal_premise,", ",SUBSTR(postal.postal_subpremise,1,10))
                              WHEN (postal.postal_premise != "") THEN postal.postal_premise                              
                              WHEN (postal.postal_subpremise !="") THEN SUBSTR(postal.postal_subpremise,1,10)
                              ELSE CONCAT(postal.postal_street_number," ",postal.postal_route)   
                        END) as location_name'))
                    ->leftjoin('postal','postal.postal_id','location_list.postal_id')
                    ->where('location_list.identity_table_id','=',8)
                    ->where('location_list.identity_id','=',$this->merchantId)
                    ->get();
                
                $categoryTypes = Merchant_retail_category_type::
                    select('category_type_id','identity_merchant_retail_category_type.identity_name as category_name')
                    ->join('identity_merchant_retail_category_type','identity_merchant_retail_category_type.identity_id','=','merchant_retail_category_type.identity_id')
                    ->where('merchant_retail_category_type.merchant_type_id',$hase_merchants->merchant_type_id)
                    ->get();
            }
            return view('hase_merchant_retail_category_list.create',compact('title','hase_locations','hase_merchants','categoryTypes','hase_options'));
        } else {
            return Redirect::back()->with('message', 'You are not authorized to use this functionality!');
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
        if($this->roleId < 4) {
            $merchantID = $request->merchant_id;
        } else {
            $merchantID = $this->merchantId;
        }

        $locationID = $request->location_id;
        $retails_type_list = array();

               
        if($this->roleId === 1){

            if(isset($request->options) && count($request->options)){
                
                $categoryList = array();
                foreach ($request->options as $key => $category) {
                    $categoryList[] = $category['category_type_id'];
                }

                $categoryListDeleted = Merchant_retail_category_list::
                    whereNotIn('category_type_id', $categoryList)
                    ->where('merchant_id','=',$merchantID)
                    ->where('location_id',$locationID)
                    ->get();

                Merchant_retail_category_list::
                    whereNotIn('category_type_id', $categoryList)
                    ->where('merchant_id','=',$merchantID)
                    ->where('location_id',$locationID)
                    ->delete();
                
                if(count($categoryListDeleted)){

                    foreach ($categoryListDeleted as $key => $value) {

                        $updateCategoryColumn = array('category_type_id');

                        $approvalStatus = $this->productApproval->ApprovalActionStatusTransitionBySelf("change");

                        $approvalCrudStatus = $this->productApproval->getApprovalCrudStatusId("delete");

                        $this->updateAdminForApprove($value['category_list_id'],$updateCategoryColumn,$this->request_table_live,$approvalStatus,$approvalCrudStatus,$merchantID,$locationID);
                    }
                }

                $optionListDeleted = Merchant_retail_category_option_list::
                    whereNotIn('category_type_id', $categoryList)
                    ->where('merchant_id',$merchantID)
                    ->where('location_id',$locationID)
                    ->get();


                Merchant_retail_category_option_list::
                    whereNotIn('category_type_id', $categoryList)
                    ->where('merchant_id','=',$merchantID)
                    ->where('location_id',$locationID)
                    ->delete();

                if(count($optionListDeleted)){

                    foreach ($optionListDeleted as $key => $value) {

                        $updateCategoryColumn = array('category_type_id','category_option_type_id');

                        $approvalStatus = $this->productApproval->ApprovalActionStatusTransitionBySelf("change");

                        $approvalCrudStatus = $this->productApproval->getApprovalCrudStatusId("delete");

                        $this->updateAdminForApprove($value['category_option_list_id'],$updateCategoryColumn,$this->option_request_table_live,$approvalStatus,$approvalCrudStatus,$merchantID,$locationID);
                    }
                }

                foreach ($request->options as $key => $category) {
                    
                    $isExist = Merchant_retail_category_list::
                        where('merchant_id', '=', $merchantID)
                        ->where('location_id',$locationID)
                        ->where('category_type_id', '=', $category['category_type_id'])
                        ->first();

                    if(is_null($isExist)){
                        $categoryListSet = array(
                            'merchant_id' => $merchantID,
                            'location_id' => $locationID,
                            'category_type_id' => $category['category_type_id']
                        );

                        $admin_category_id = Merchant_retail_category_list::insertGetId($categoryListSet);

                        $updateCategoryColumn = array('category_type_id');
                        
                        $approvalStatus = $this->productApproval->ApprovalActionStatusTransitionBySelf("change");

                        $approvalCrudStatus = $this->productApproval->getApprovalCrudStatusId("insert");

                        $this->addAdminForApprove($admin_category_id,$updateCategoryColumn,$this->request_table_live,$approvalStatus,$approvalCrudStatus,$merchantID,$locationID);
                    }
                    
                    if(isset($category['category_option_type_id']) && count($category['category_option_type_id'])){

                        $optionListDeleted = Merchant_retail_category_option_list::
                            whereNotIn('category_option_type_id', $category['category_option_type_id'])
                            ->where('merchant_id',$merchantID)
                            ->where('location_id',$locationID)
                            ->where('category_type_id',$category['category_type_id'])
                            ->get();

                        Merchant_retail_category_option_list::
                            where('category_type_id', $category['category_type_id'])
                            ->where('merchant_id','=',$merchantID)
                            ->where('location_id',$locationID)
                            ->whereNotIn('category_option_type_id',$category['category_option_type_id'])
                            ->delete();

                        if(count($optionListDeleted)){

                            foreach ($optionListDeleted as $key => $value) {

                                $updateCategoryColumn = array('category_type_id','category_option_type_id');

                                $approvalStatus = $this->productApproval->ApprovalActionStatusTransitionBySelf("change");

                                $approvalCrudStatus = $this->productApproval->getApprovalCrudStatusId("delete");

                                $this->addAdminForApprove($value['category_option_list_id'],$updateCategoryColumn,$this->option_request_table_live,$approvalStatus,$approvalCrudStatus,$merchantID,$locationID);
                            }
                        }

                        foreach ($category['category_option_type_id'] as $key => $optionID) {

                            $isExist = Merchant_retail_category_option_list::
                                    where('merchant_id',$merchantID)
                                    ->where('location_id',$locationID)
                                    ->where('category_type_id',$category['category_type_id'])
                                    ->where('category_option_type_id',$optionID)
                                    ->get()->first();

                            if(is_null($isExist)){

                                $optionListSet = array(
                                    'merchant_id' => $merchantID,
                                    'location_id' => $locationID,
                                    'category_type_id' => $category['category_type_id'],
                                    'category_option_type_id' => $optionID,
                                    'priority' => $category['priority'],
                                    'enable' => isset($category['enable']) ? 1 : 0
                                );

                                $admin_category_option_id = Merchant_retail_category_option_list::insertGetId($optionListSet);

                                $updateCategoryColumn = array('category_type_id','category_option_type_id');

                                $approvalStatus = $this->productApproval->ApprovalActionStatusTransitionBySelf("change");

                                $approvalCrudStatus = $this->productApproval->getApprovalCrudStatusId("delete");

                                $this->addAdminForApprove($admin_category_option_id,$updateCategoryColumn,$this->option_request_table_live,$approvalStatus,$approvalCrudStatus,$merchantID,$locationID);

                            }else{
                                $optionListSet = array(
                                        'priority' => $category['priority'],
                                        'enable' => isset($category['enable']) ? 1 : 0
                                );
                                Merchant_retail_category_option_list::
                                    where('merchant_id',$merchantID)
                                    ->where('location_id',$locationID)
                                    ->where('category_type_id',$category['category_type_id'])
                                    ->where('category_option_type_id',$optionID)
                                    ->update($optionListSet);
                            }
                        }
                    }else{

                        $optionListDeleted = Merchant_retail_category_option_list::
                            where('merchant_id',$merchantID)
                            ->where('location_id',$locationID)
                            ->where('category_type_id',$category['category_type_id'])
                            ->get();

                        Merchant_retail_category_option_list::
                            where('merchant_id',$merchantID)
                            ->where('location_id',$locationID)
                            ->where('category_type_id',$category['category_type_id'])
                            ->delete();

                        if(count($optionListDeleted)){

                            foreach ($optionListDeleted as $key => $value) {

                                $updateCategoryColumn = array('category_type_id','category_option_type_id');

                                $approvalStatus = $this->productApproval->ApprovalActionStatusTransitionBySelf("change");

                                $approvalCrudStatus = $this->productApproval->getApprovalCrudStatusId("delete");

                                $this->updateAdminForApprove($value['category_option_list_id'],$updateCategoryColumn,$this->option_request_table_live,$approvalStatus,$approvalCrudStatus,$merchantID,$locationID);
                            }
                        }
                    }
                }
            } else {
                
                $categoryListDeleted = Merchant_retail_category_list::
                    where('merchant_id','=',$merchantID)
                    ->where('location_id',$locationID)
                    ->get();

                Merchant_retail_category_list::
                    where('merchant_id',$merchantID)
                    ->where('location_id',$locationID)
                    ->delete();

                if(count($categoryListDeleted)){

                    foreach ($categoryListDeleted as $key => $value) {

                        $updateCategoryColumn = array('category_type_id');

                        $approvalStatus = $this->productApproval->ApprovalActionStatusTransitionBySelf("change");

                        $approvalCrudStatus = $this->productApproval->getApprovalCrudStatusId("delete");

                        $this->updateAdminForApprove($value['category_option_list_id'],$$updateCategoryColumn,$this->request_table_live,$approvalStatus,$approvalCrudStatus,$merchantID,$locationID);
                    }
                }

                $optionListDeleted = Merchant_retail_category_option_list::
                    where('merchant_id',$merchantID)
                    ->where('location_id',$locationID)
                    ->get();

                Merchant_retail_category_option_list::
                    where('merchant_id',$merchantID)
                    ->where('location_id',$locationID)
                    ->delete();

                if(count($optionListDeleted)){

                    foreach ($optionListDeleted as $key => $value) {

                        $updateCategoryColumn = array('category_type_id','category_option_type_id');

                        $approvalStatus = $this->productApproval->ApprovalActionStatusTransitionBySelf("change");

                        $approvalCrudStatus = $this->productApproval->getApprovalCrudStatusId("delete");

                        $this->updateAdminForApprove($value['category_option_list_id'],$updateCategoryColumn,$this->option_request_table_live,$approvalStatus,$approvalCrudStatus,$merchantID,$locationID);
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
                    ->where('merchant_id','=',$merchantID)
                    ->where('location_id',$locationID)
                    ->get();
            }else{

                $categoryListDeleted =  Merchant_retail_category_list::
                    where('merchant_id','=',$merchantID)
                    ->where('location_id',$locationID)
                    ->get();
            }
                

            if(count($categoryListDeleted)){

                foreach ($categoryListDeleted as $key => $value) {

                    $hase_merchant_retail_category_list = new Merchant_retail_category_list_stage();

                    $hase_merchant_retail_category_list->staff_id = $this->staffId;
                    $hase_merchant_retail_category_list->merchant_id = $value['merchant_id'];
                    $hase_merchant_retail_category_list->location_id = $value['location_id'];
                    $hase_merchant_retail_category_list->category_type_id = $value['category_type_id'];

                    $hase_merchant_retail_category_list->save();

                    $updateCategoryColumn = array('category_type_id');

                    $approvalStatus = $this->productApproval->ApprovalActionStatusTransitionBySelf("change");

                    $approvalCrudStatus = $this->productApproval->getApprovalCrudStatusId("delete");

                    $this->updateForApprove($value['category_list_id'],$hase_merchant_retail_category_list->category_list_id,$updateCategoryColumn,$this->request_table_live,$this->request_table_stage,$approvalStatus,$approvalCrudStatus,$merchantID,$locationID);
                }
            }

            foreach ($request->options as $key => $category) {
                
                if(isset($category['category_option_type_id']) && count($category['category_option_type_id'])){

                    $optionListDeleted = Merchant_retail_category_option_list::
                        whereNotIn('category_option_type_id', $category['category_option_type_id'])
                        ->where('merchant_id','=',$merchantID)
                        ->where('location_id',$locationID)
                        ->where('category_type_id',$category['category_type_id'])
                        ->get();
                }else{

                    $optionListDeleted =  Merchant_retail_category_option_list::
                        where('merchant_id','=',$merchantID)
                        ->where('location_id',$locationID)
                        ->where('category_type_id',$category['category_type_id'])
                        ->get();
                }

                if(count($optionListDeleted)){

                    foreach ($optionListDeleted as $key => $value) {

                        $hase_merchant_retail_category_option_list = new Merchant_retail_category_option_list_stage();

                        $hase_merchant_retail_category_option_list->staff_id = $this->staffId;

                        $hase_merchant_retail_category_option_list->merchant_id = $value['merchant_id'];

                        $hase_merchant_retail_category_option_list->location_id = $value['location_id'];

                        $hase_merchant_retail_category_option_list->category_type_id = $value['category_type_id'];

                        $hase_merchant_retail_category_option_list->category_option_type_id = $value['category_option_type_id'];

                        $hase_merchant_retail_category_option_list->priority = $value['priority'];
                        $hase_merchant_retail_category_option_list->enable = 0;

                        $hase_merchant_retail_category_option_list->save();

                        $updateCategoryColumn = array('category_type_id','category_option_type_id','priority','enable');

                        $approvalStatus = $this->productApproval->ApprovalActionStatusTransitionBySelf("change");

                        $approvalCrudStatus = $this->productApproval->getApprovalCrudStatusId("delete");

                        $this->updateForApprove($value['category_option_list_id'],$hase_merchant_retail_category_option_list->option_list_id,$updateCategoryColumn,$this->option_request_table_live,$this->option_request_table_stage,$approvalStatus,$approvalCrudStatus,$merchantID,$locationID);
                    }
                }
                

                $isExist = Merchant_retail_category_list::
                    where('merchant_id', '=', $merchantID)
                    ->where('location_id',$locationID)
                    ->where('category_type_id', '=', $category['category_type_id'])
                    ->first();

                if(is_null($isExist)){
                    
                    $hase_merchant_retail_category_list = new Merchant_retail_category_list_stage();

                    $hase_merchant_retail_category_list->staff_id = $this->staffId;
                    $hase_merchant_retail_category_list->merchant_id = $merchantID;
                    $hase_merchant_retail_category_list->location_id = $locationID;
                    $hase_merchant_retail_category_list->category_type_id = $category['category_type_id'];

                    $hase_merchant_retail_category_list->save();

                    $updateCategoryColumn = array('category_type_id');
                    
                    $approvalStatus = $this->productApproval->ApprovalActionStatusTransitionBySelf("change");

                    $approvalCrudStatus = $this->productApproval->getApprovalCrudStatusId("insert");

                    $this->addForApprove($hase_merchant_retail_category_list->category_list_id,$updateCategoryColumn,$this->request_table_live,$this->request_table_stage,$approvalStatus,$approvalCrudStatus,$merchantID,$locationID);
                }

                if(isset($category['category_option_type_id']) && count($category['category_option_type_id'])){
                    foreach ($category['category_option_type_id'] as $key => $optionID) {

                        $isExist = Merchant_retail_category_option_list::
                                where('merchant_id',$merchantID)
                                ->where('location_id',$locationID)
                                ->where('category_type_id',$category['category_type_id'])
                                ->where('category_option_type_id',$optionID)
                                ->where('enable',1)
                                ->first();

                        if(is_null($isExist)){

                            $hase_merchant_retail_category_option_list = new Merchant_retail_category_option_list_stage();

                            $hase_merchant_retail_category_option_list->staff_id = $this->staffId;

                            $hase_merchant_retail_category_option_list->merchant_id = $merchantID;

                            $hase_merchant_retail_category_option_list->location_id = $locationID;

                            $hase_merchant_retail_category_option_list->category_type_id = $category['category_type_id'];

                            $hase_merchant_retail_category_option_list->category_option_type_id = $optionID;

                            $hase_merchant_retail_category_option_list->priority = $category['priority'];

                            $hase_merchant_retail_category_option_list->enable = isset($category['enable']) ? 1 : 0 ;

                            $hase_merchant_retail_category_option_list->save();

                            $updateCategoryColumn = array('category_type_id','category_option_type_id','priority','enable');

                            $approvalStatus = $this->productApproval->ApprovalActionStatusTransitionBySelf("change");

                            $approvalCrudStatus = $this->productApproval->getApprovalCrudStatusId("insert");

                            $this->addForApprove($hase_merchant_retail_category_option_list->option_list_id,$updateCategoryColumn,$this->option_request_table_live,$this->option_request_table_stage,$approvalStatus,$approvalCrudStatus,$merchantID,$locationID);
                        }
                    }
                }
            }
                
        }
        
        $staffUrl = "/hase_staff/".$this->staffId."/edit";
        $action="added";
        $message = "<a href='".URL::to($staffUrl)."'>".$this->staffName."</a> <strong>added</strong> new category";
        PermissionTrait::addActivityLog($action,$message);
        Session::flash('type', 'success'); 
        Session::flash('msg', 'Category Successfully Created'); 
        return redirect('hase_category_list');
    }

    /**
     * Show the form for editing the specified resource.
     * @param    \Illuminate\Http\Request  $request
     * @param    int  $id
     * @return  \Illuminate\Http\Response
     */
    public function edit($id,Request $request)
    {
        if($this->permissionDetails('Hase_category_list','manage')){
            
            $title = 'Edit - Hase Category List';
            $hase_category_exist=array();
            
            $hase_merchant_retail_category_list = Merchant_retail_category_list::findOrfail($id);

            if(count($hase_merchant_retail_category_list)){
                
                $hase_options = Merchant_retail_category_option::
                            select('merchant_retail_category_option.*','identity_merchant_retail_category_option.identity_name as option_name')
                            ->join('identity_merchant_retail_category_option','merchant_retail_category_option.identity_id','=','identity_merchant_retail_category_option.identity_id')
                            ->get();

                $merchant_data = Merchant::
                            distinct()
                            ->select('merchant.merchant_id','identity_merchant.identity_name as merchant_name','merchant_type.merchant_root_id as merchant_type')
                            ->join('identity_merchant','identity_merchant.identity_id','=','merchant.identity_id')
                            ->join('merchant_type_list','merchant_type_list.merchant_id','=','merchant.merchant_id')
                            ->join('merchant_type','merchant_type.merchant_type_id','=','merchant_type_list.merchant_type_id')
                            ->where('merchant.merchant_id','=',$hase_merchant_retail_category_list->merchant_id)
                            ->get()->first();


                $location_data = Location_list::
                            select('list_id as location_id',DB::raw('(CASE WHEN (postal.postal_premise != "" and postal.postal_subpremise != "") THEN CONCAT(postal.postal_premise,", ",SUBSTR(postal.postal_subpremise,1,10))
                              WHEN (postal.postal_premise != "") THEN postal.postal_premise                              
                              WHEN (postal.postal_subpremise !="") THEN SUBSTR(postal.postal_subpremise,1,10)
                              ELSE CONCAT(postal.postal_street_number," ",postal.postal_route)   
                        END) as location_name'))
                            ->leftjoin('postal','postal.postal_id','location_list.postal_id')
                            ->where('location_list.list_id',$hase_merchant_retail_category_list->location_id)
                            ->get()->first();

                $categoryTypes = Merchant_retail_category_type::
                            select('category_type_id','identity_merchant_retail_category_type.identity_name as category_name')
                            ->leftjoin('identity_merchant_retail_category_type','identity_merchant_retail_category_type.identity_id','=','merchant_retail_category_type.identity_id')
                            ->where('merchant_retail_category_type.merchant_type_id','=',$merchant_data->merchant_type)
                            ->get();

                return view('hase_merchant_retail_category_list.edit',compact('title','location_data','categoryTypes','merchant_data','hase_merchant_retail_category_list','hase_options'));
            }else{
                return Redirect::back()->with('message', 'You are not authorized to use this functionality!');
            }
        }else{
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
    public function update($id,Request $request){
        
        $merchantID = $request->merchant_id;
        $locationID = $request->location_id;
        
        $retails_type_list = array();

        //echo "<pre>";
        /*print_r($request->toArray());
        die;*/

        if($this->roleId === 1){

            if(isset($request->options) && count($request->options)){
                
                $categoryList = array();
                foreach ($request->options as $key => $category) {
                    $categoryList[] = $category['category_type_id'];
                }

                Merchant_retail_category_list::
                    whereNotIn('category_type_id', $categoryList)
                    ->where('merchant_id','=',$merchantID)
                    ->where('location_id',$locationID)
                    ->delete();
                
                Merchant_retail_category_option_list::
                    whereNotIn('category_type_id', $categoryList)
                    ->where('merchant_id','=',$merchantID)
                    ->where('location_id',$locationID)
                    ->delete();

                foreach ($request->options as $key => $category) {
                    
                    $isExist = Merchant_retail_category_list::
                        where('merchant_id', '=', $merchantID)
                        ->where('location_id',$locationID)
                        ->where('category_type_id', '=', $category['category_type_id'])
                        ->first();

                    if(is_null($isExist)){
                        $categoryListSet = array(
                            'merchant_id' => $merchantID,
                            'location_id' => $locationID,
                            'category_type_id' => $category['category_type_id']
                        );

                        Merchant_retail_category_list::insert($categoryListSet);
                    }

                    if(isset($category['category_option_type_id']) && count($category['category_option_type_id'])){

                        Merchant_retail_category_option_list::
                            where('category_type_id', $category['category_type_id'])
                            ->where('merchant_id','=',$merchantID)
                            ->where('location_id',$locationID)
                            ->whereNotIn('category_option_type_id',$category['category_option_type_id'])
                            ->delete();

                        foreach ($category['category_option_type_id'] as $key => $optionID) {
                            
                            $isExist = Merchant_retail_category_option_list::
                                    where('merchant_id',$merchantID)
                                    ->where('location_id',$locationID)
                                    ->where('category_type_id',$category['category_type_id'])
                                    ->where('category_option_type_id',$optionID)
                                    ->first();

                            if(is_null($isExist)){

                                $optionListSet = array(
                                    'merchant_id' => $merchantID,
                                    'location_id' => $locationID,
                                    'category_type_id' => $category['category_type_id'],
                                    'category_option_type_id' => $optionID,
                                    'priority' => $category['priority'],
                                    'enable' => isset($category['enable']) ? 1 : 0
                                );

                                Merchant_retail_category_option_list::insert($optionListSet);
                            }else{
                                $optionListSet = array(
                                        'priority' => $category['priority'],
                                        'enable' => isset($category['enable']) ? 1 : 0
                                );
                                Merchant_retail_category_option_list::
                                    where('merchant_id',$merchantID)
                                    ->where('location_id',$locationID)
                                    ->where('category_type_id',$category['category_type_id'])
                                    ->where('category_option_type_id',$optionID)
                                    ->update($optionListSet);
                            }
                        }
                    }else{
                        Merchant_retail_category_option_list::
                            where('merchant_id',$merchantID)
                            ->where('location_id',$locationID)
                            ->where('category_type_id',$category['category_type_id'])
                            ->delete();
                    }
                }
            }else{
                Merchant_retail_category_list::
                    where('merchant_id',$merchantID)
                    ->where('location_id',$locationID)
                    ->delete();
                Merchant_retail_category_option_list::
                    where('merchant_id',$merchantID)
                    ->where('location_id',$locationID)
                    ->delete();
            }

        }else{
            
            if(isset($request->options) && count($request->options)){
                
                $categoryList = array();
                foreach ($request->options as $key => $category) {
                    $categoryList[] = $category['category_type_id'];
                }

                $categoryListDeleted = Merchant_retail_category_list::
                    whereNotIn('category_type_id', $categoryList)
                    ->where('merchant_id','=',$merchantID)
                    ->where('location_id',$locationID)
                    ->get();
            }else{

                $categoryListDeleted =  Merchant_retail_category_list::
                    where('merchant_id','=',$merchantID)
                    ->where('location_id',$locationID)
                    ->get();
            }

            if(count($categoryListDeleted)){

                foreach ($categoryListDeleted as $key => $value) {

                    $hase_merchant_retail_category_list = new Merchant_retail_category_list_stage();

                    $hase_merchant_retail_category_list->staff_id = $this->staffId;
                    $hase_merchant_retail_category_list->merchant_id = $value['merchant_id'];
                    $hase_merchant_retail_category_list->location_id = $value['location_id'];
                    $hase_merchant_retail_category_list->category_type_id = $value['category_type_id'];

                    $hase_merchant_retail_category_list->save();

                    $updateCategoryColumn = array('category_type_id');

                    $approvalStatus = $this->productApproval->ApprovalActionStatusTransitionBySelf("change");

                    $approvalCrudStatus = $this->productApproval->getApprovalCrudStatusId("delete");

                    $this->updateForApprove($value['category_list_id'],$hase_merchant_retail_category_list->list_id,$updateCategoryColumn,$this->request_table_live,$this->request_table_stage,$approvalStatus,$approvalCrudStatus,$merchantID,$locationID);
                }
            }
            if(isset($request->options)){
                foreach ($request->options as $key => $category) {
                
                    if(isset($category['category_option_type_id']) && count($category['category_option_type_id'])){
                        $optionListDeleted = Merchant_retail_category_option_list::
                            whereNotIn('category_option_type_id', $category['category_option_type_id'])
                            ->where('merchant_id','=',$merchantID)
                            ->where('location_id',$locationID)
                            ->where('category_type_id',$category['category_type_id'])
                            ->get();
                    }else{

                        $optionListDeleted =  Merchant_retail_category_option_list::
                            where('merchant_id','=',$merchantID)
                            ->where('location_id',$locationID)
                            ->where('category_type_id',$category['category_type_id'])
                            ->get();
                    }
                    
                    if(count($optionListDeleted)){

                        foreach ($optionListDeleted as $key => $value) {

                            $hase_merchant_retail_category_option_list = new Merchant_retail_category_option_list_stage();

                            $hase_merchant_retail_category_option_list->staff_id = $this->staffId;

                            $hase_merchant_retail_category_option_list->merchant_id = $value['merchant_id'];

                            $hase_merchant_retail_category_option_list->location_id = $value['location_id'];

                            $hase_merchant_retail_category_option_list->category_type_id = $value['category_type_id'];

                            $hase_merchant_retail_category_option_list->category_option_type_id = $value['category_option_type_id'];

                            $hase_merchant_retail_category_option_list->priority = $value['priority'];
                            $hase_merchant_retail_category_option_list->enable = 0;

                            $hase_merchant_retail_category_option_list->save();

                            $updateCategoryColumn = array('category_type_id','category_option_type_id','priority','enable');

                            $approvalStatus = $this->productApproval->ApprovalActionStatusTransitionBySelf("change");

                            $approvalCrudStatus = $this->productApproval->getApprovalCrudStatusId("delete");

                            $this->updateForApprove($value['category_option_list_id'],$hase_merchant_retail_category_option_list->option_list_id,$updateCategoryColumn,$this->option_request_table_live,$this->option_request_table_stage,$approvalStatus,$approvalCrudStatus,$merchantID,$locationID);
                        }
                    }
                

                    $isExist = Merchant_retail_category_list::
                        where('merchant_id', '=', $merchantID)
                        ->where('location_id',$locationID)
                        ->where('category_type_id', '=', $category['category_type_id'])
                        ->first();

                    if(is_null($isExist)){
                        
                        $hase_merchant_retail_category_list = new Merchant_retail_category_list_stage();

                        $hase_merchant_retail_category_list->staff_id = $this->staffId;
                        $hase_merchant_retail_category_list->merchant_id = $merchantID;
                        $hase_merchant_retail_category_list->location_id = $locationID;
                        $hase_merchant_retail_category_list->category_type_id = $category['category_type_id'];
                        

                        $hase_merchant_retail_category_list->save();

                        $updateCategoryColumn = array('category_type_id');
                        
                        $approvalStatus = $this->productApproval->ApprovalActionStatusTransitionBySelf("change");

                        $approvalCrudStatus = $this->productApproval->getApprovalCrudStatusId("insert");

                        $this->addForApprove($hase_merchant_retail_category_list->category_list_id,$updateCategoryColumn,$this->request_table_live,$this->request_table_stage,$approvalStatus,$approvalCrudStatus,$merchantID,$locationID);
                    }

                    if(isset($category['category_option_type_id']) && count($category['category_option_type_id'])){
                        foreach ($category['category_option_type_id'] as $key => $optionID) {

                            $isExist = Merchant_retail_category_option_list::
                                    where('merchant_id',$merchantID)
                                    ->where('location_id',$locationID)
                                    ->where('category_type_id',$category['category_type_id'])
                                    ->where('category_option_type_id',$optionID)
                                    ->first();

                            if(is_null($isExist) || !$isExist->option_enable){

                                $hase_merchant_retail_category_option_list = new Merchant_retail_category_option_list_stage();

                                $hase_merchant_retail_category_option_list->staff_id = $this->staffId;

                                $hase_merchant_retail_category_option_list->merchant_id = $merchantID;

                                $hase_merchant_retail_category_option_list->location_id = $locationID;

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

                                $this->addForApprove($hase_merchant_retail_category_option_list->category_option_list_id,$updateCategoryColumn,$this->option_request_table_live,$this->option_request_table_stage,$approvalStatus,$approvalCrudStatus,$merchantID,$locationID);
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
        Session::flash('type', 'success'); 
        Session::flash('msg', 'Category Successfully Updated'); 
        return redirect('hase_category_list');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param    int $id
     * @return  \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        
        if($this->permissionDetails('Hase_category_list','delete')){

            Session::flash('type', 'success'); 
            Session::flash('msg', 'category Successfully Deleted');
            return redirect('hase_category_list');
            
        } else {
            return Redirect::back()->with('message', 'You are not authorized to use this functionality!');
        }
    }

    public function getFilter(Request $request) {
        $merchantType = $request->merchant_type;
        session(['merchantType' => $merchantType]);
        return;
    }
}