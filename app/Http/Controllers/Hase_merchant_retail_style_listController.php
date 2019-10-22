<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\App;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Request as Requests;
use App\Http\Controllers\PermissionsController;
use App\Helpers\ConnectionManager;
use App\Http\Controllers\ProductApprovalController;
use App\Http\Traits\PermissionTrait;
use App\Merchant_retail_style_list;
use App\Merchant_retail_style_list_stage;
use App\Merchant_retail_style_type;
use App\Merchant_city_list;
use App\Merchant;
use App\Merchant_type;
use App\Location_list;
use URL;
use Session;
use DB;
use Redirect;
use Auth;

/**
 * Class Hase_merchant_retail_style_listController.
 *
 */
class Hase_merchant_retail_style_listController extends PermissionsController
{
    use PermissionTrait;

    public function __construct()
    {
      parent::__construct();

      $connectionStatus = ConnectionManager::setDbConfig('Hase_style_list');

      if ($connectionStatus['type'] === "error") {
          Session::flash('type', $connectionStatus['type']);
          Session::flash('msg', $connectionStatus['message']);
          return Redirect::back()->send();
      }
       
      $this->request_table_live = 49;
      $this->request_table_stage = 50; 
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

        if($this->permissionDetails('Hase_style_list','access')){
            $title = 'Index - hase style list';
            $searchStyle = '';
            $merchant_parent_types = Merchant_type::all()->where('merchant_parent_id',0);
            $permissions = $this->getPermission('Hase_style_list');

            if($this->merchantId === 0) {
                $labels = array("Tag Styles","Tag Style","Merchants");

                $hase_merchant_retail_style_lists = Merchant_retail_style_list::
                distinct()
                ->select('merchant_retail_style_list.style_list_id',DB::raw("group_concat(distinct identity_merchant_retail_style_type.identity_name SEPARATOR ', ') as style_name"),DB::raw('(CASE WHEN (postal.postal_premise != "" and postal.postal_subpremise != "") THEN CONCAT(postal.postal_premise,", ",SUBSTR(postal.postal_subpremise,1,10))
                              WHEN (postal.postal_premise != "") THEN postal.postal_premise                              
                              WHEN (postal.postal_subpremise !="") THEN SUBSTR(postal.postal_subpremise,1,10)
                              ELSE CONCAT(postal.postal_street_number," ",postal.postal_route)   
                        END) as location_name'),'identity_merchant.identity_name as merchant_name','merchant.merchant_type_id as merchant_type','merchant_retail_style_list.enable')
                        ->join('location_list','location_list.list_id','merchant_retail_style_list.location_id')
                        ->join('postal','location_list.postal_id','=','postal.postal_id')
                        ->join('merchant','merchant_retail_style_list.merchant_id','=','merchant.merchant_id')
                        ->join('identity_merchant','identity_merchant.identity_id','=','merchant.identity_id')
                        ->join('merchant_retail_style_type','merchant_retail_style_list.style_type_id','=','merchant_retail_style_type.style_type_id')
                        ->join('identity_merchant_retail_style_type','identity_merchant_retail_style_type.identity_id','=','merchant_retail_style_type.identity_id')
                        ->where('merchant_retail_style_list.enable','=',1)
                        ->where('merchant.merchant_type_id','=',$merchantType)
                        ->groupBy('merchant_retail_style_list.location_id','identity_merchant.identity_name')
                        ->orderBy('merchant_retail_style_list.style_list_id','desc')
                        ->paginate(25);
            } else {
                $merchant_data = Merchant::distinct()
                    ->select('merchant.merchant_id','identity_merchant.identity_name as merchant_name','merchant_type.merchant_root_id as merchant_type')
                    ->join('identity_merchant','identity_merchant.identity_id','=','merchant.identity_id')
                    ->join('merchant_type_list','merchant_type_list.merchant_id','=','merchant.merchant_id')
                    ->join('merchant_type','merchant_type.merchant_type_id','=','merchant_type_list.merchant_type_id')
                    ->where('merchant.merchant_id','=',$this->merchantId)
                    ->get()->first();

                if($merchant_data->merchant_type === 8)
                {
                    $labels = array("Cuisines","Cuisine","Merchants");
                } else {
                    $labels = array("Industries","Industry","Merchants");
                }
                if($this->roleId === 4)
                {
                    $hase_merchant_retail_style_lists = Merchant_retail_style_list::
                    distinct()
                    ->select('merchant_retail_style_list.style_list_id',DB::raw("group_concat(distinct identity_merchant_retail_style_type.identity_name SEPARATOR ', ') as style_name"),DB::raw('(CASE WHEN (postal.postal_premise != "" and postal.postal_subpremise != "") THEN CONCAT(postal.postal_premise,", ",SUBSTR(postal.postal_subpremise,1,10))
                              WHEN (postal.postal_premise != "") THEN postal.postal_premise                              
                              WHEN (postal.postal_subpremise !="") THEN SUBSTR(postal.postal_subpremise,1,10)
                              ELSE CONCAT(postal.postal_street_number," ",postal.postal_route)   
                        END) as location_name'),'identity_merchant.identity_name as merchant_name','merchant_retail_style_list.enable')
                        ->join('location_list','location_list.list_id','merchant_retail_style_list.location_id')                        
                        ->join('postal','location_list.postal_id','=','postal.postal_id')
                        ->join('merchant','merchant_retail_style_list.merchant_id','=','merchant.merchant_id')
                        ->join('identity_merchant','identity_merchant.identity_id','=','merchant.identity_id')

                        ->join('merchant_retail_style_type','merchant_retail_style_list.style_type_id','=','merchant_retail_style_type.style_type_id')
                        ->join('identity_merchant_retail_style_type','identity_merchant_retail_style_type.identity_id','=','merchant_retail_style_type.identity_id')
                        ->where('merchant_retail_style_list.enable','=',1)
                        ->where('merchant_retail_style_list.merchant_id',"=",$this->merchantId)
                        ->groupBy('merchant_retail_style_list.location_id','identity_merchant.identity_name')
                        ->orderBy('merchant_retail_style_list.style_list_id','desc')
                        ->paginate(25);
                    
                } else {
                    $hase_merchant_retail_style_lists = Merchant_retail_style_list::
                    distinct()
                    ->select('merchant_retail_style_list.style_list_id',DB::raw("group_concat(distinct identity_merchant_retail_style_type.identity_name SEPARATOR ', ') as style_name"),DB::raw('(CASE WHEN (postal.postal_premise != "" and postal.postal_subpremise != "") THEN CONCAT(postal.postal_premise,", ",SUBSTR(postal.postal_subpremise,1,10))
                              WHEN (postal.postal_premise != "") THEN postal.postal_premise                              
                              WHEN (postal.postal_subpremise !="") THEN SUBSTR(postal.postal_subpremise,1,10)
                              ELSE CONCAT(postal.postal_street_number," ",postal.postal_route)   
                        END) as location_name'),'identity_merchant.identity_name as merchant_name','merchant_retail_style_list.enable')
                        ->join('location_list','location_list.list_id','merchant_retail_style_list.location_id')
                        ->join('postal','location_list.postal_id','=','postal.postal_id')
                        ->join('merchant','merchant_retail_style_list.merchant_id','=','merchant.merchant_id')
                        ->join('identity_merchant','identity_merchant.identity_id','=','merchant.identity_id')
                        ->join('merchant_retail_style_type','merchant_retail_style_list.style_type_id','=','merchant_retail_style_type.style_type_id')
                        ->join('identity_merchant_retail_style_type','identity_merchant_retail_style_type.identity_id','=','merchant_retail_style_type.identity_id')
                        ->where('merchant_retail_style_list.enable','=',1)
                        ->where('merchant_retail_style_list.merchant_id',"=",$this->merchantId)
                        ->where('merchant_retail_style_list.location_id',"=",$this->locationId)
                        ->groupBy('merchant_retail_style_list.location_id','identity_merchant.identity_name')
                        ->orderBy('merchant_retail_style_list.style_list_id','desc')
                        ->paginate(25);
                }
            }
            return view('hase_merchant_retail_style_list.index',compact('hase_merchant_retail_style_lists','title','labels','permissions','merchant_parent_types','merchantType','searchStyle'));
        }else{
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
        $searchStyle = trim($request->search_style);
        $merchantId = session()->has('merchantId') ? session()->get('merchantId') : '';
        $roleId = session()->has('role') ? session()->get('role') : '';
        $locationId = session()->has('locationId') ? session()->get('locationId') :"";
        $merchantType = session()->has('merchantType') ? session()->get('merchantType') :"";
        $merchant_parent_types = Merchant_type::all()->where('merchant_parent_id',0);
        if(!empty($searchStyle)) {
            if($merchantId === 0) {
                $labels = array("Tag Styles","Tag Style","Merchants");
                $hase_merchant_retail_style_lists = Merchant_retail_style_list::select('merchant_retail_style_list.style_list_id',DB::raw("group_concat(distinct identity_merchant_retail_style_type.identity_name SEPARATOR ', ') as style_name"),DB::raw('(CASE WHEN (postal.postal_premise != "" and postal.postal_subpremise != "") THEN CONCAT(postal.postal_premise,", ",SUBSTR(postal.postal_subpremise,1,10))
                              WHEN (postal.postal_premise != "") THEN postal.postal_premise                              
                              WHEN (postal.postal_subpremise !="") THEN SUBSTR(postal.postal_subpremise,1,10)
                              ELSE CONCAT(postal.postal_street_number," ",postal.postal_route)   
                        END) as location_name'),'identity_merchant.identity_name as merchant_name','merchant.merchant_type_id','merchant_retail_style_list.enable')
                      ->join('location_list','location_list.list_id','merchant_retail_style_list.location_id')
                      ->join('postal','postal.postal_id','=','location_list.postal_id')               
                      ->join('merchant','merchant_retail_style_list.merchant_id','=','merchant.merchant_id')
                      ->join('identity_merchant','identity_merchant.identity_id','=','merchant.identity_id')
                      ->join('merchant_retail_style_type', 'merchant_retail_style_list.style_type_id', '=', 'merchant_retail_style_type.style_type_id')
                      ->join('identity_merchant_retail_style_type','identity_merchant_retail_style_type.identity_id','=','merchant_retail_style_type.identity_id')
                      ->distinct('merchant_retail_style_list.style_list_id')
                      ->groupBy('merchant_retail_style_list.location_id','identity_merchant.identity_name')
                      ->orderBy('merchant_retail_style_list.style_list_id','desc')                    
                      ->where('postal.postal_premise', 'LIKE', '%' . $searchStyle . '%')
                      ->orWhere('identity_merchant.identity_name', 'LIKE', '%' . $searchStyle . '%')
                      ->where('merchant.merchant_type_id','=',$merchantType)
                      ->where('merchant_retail_style_list.enable','=',1)
                      ->paginate(25)->setPath('');
                        
            } else {
                $merchant_data = Hase_merchant::
                        where('merchant_id','=',$merchantId)
                        ->get()->first();
                if($merchant_data->merchant_type === 8)
                {
                    $labels = array("Cuisines","Cuisine","Merchants");
                } else {
                    $labels = array("Industries","Industry","Merchants");
                }
                if($roleId === 4)
                {
                  $hase_merchant_retail_style_lists = Merchant_retail_style_list::select('merchant_retail_style_list.style_list_id',DB::raw("group_concat(distinct identity_merchant_retail_style_type.identity_name SEPARATOR ', ') as style_name"),DB::raw('(CASE WHEN (postal.postal_premise != "" and postal.postal_subpremise != "") THEN CONCAT(postal.postal_premise,", ",SUBSTR(postal.postal_subpremise,1,10))
                              WHEN (postal.postal_premise != "") THEN postal.postal_premise                              
                              WHEN (postal.postal_subpremise !="") THEN SUBSTR(postal.postal_subpremise,1,10)
                              ELSE CONCAT(postal.postal_street_number," ",postal.postal_route)   
                        END) as location_name'),'identity_merchant.identity_name as merchant_name','merchant.merchant_type_id','merchant_retail_style_list.enable')
                      ->join('location_list','location_list.list_id','merchant_retail_style_list.location_id')
                      ->join('postal','postal.postal_id','=','location_list.postal_id')               
                      ->join('merchant','merchant_retail_style_list.merchant_id','=','merchant.merchant_id')
                      ->join('identity_merchant','identity_merchant.identity_id','=','merchant.identity_id')
                      ->join('merchant_retail_style_type', 'merchant_retail_style_list.style_type_id', '=', 'merchant_retail_style_type.style_type_id')
                      ->join('identity_merchant_retail_style_type','identity_merchant_retail_style_type.identity_id','=','merchant_retail_style_type.identity_id')
                      ->distinct('merchant_retail_style_list.style_list_id')
                      ->where('merchant_retail_style_list.enable','=',1)
                      ->groupBy('merchant_retail_style_list.location_id','identity_merchant.identity_name')
                      ->orderBy('merchant_retail_style_list.style_list_id','desc')
                      ->where('postal.postal_premise', 'LIKE', '%' . $searchStyle . '%')
                      ->orWhere('identity_merchant.identity_name', 'LIKE', '%' . $searchStyle . '%')
                      ->where('merchant.merchant_type_id','=',$merchantType)
                      ->where('merchant.merchant_id','=',$merchantId)
                      ->paginate(25)->setPath('');

                } else {
                    $hase_merchant_retail_style_lists = Merchant_retail_style_list::select('merchant_retail_style_list.style_list_id',DB::raw("group_concat(distinct identity_merchant_retail_style_type.identity_name SEPARATOR ', ') as style_name"),DB::raw('(CASE WHEN (postal.postal_premise != "" and postal.postal_subpremise != "") THEN CONCAT(postal.postal_premise,", ",SUBSTR(postal.postal_subpremise,1,10))
                              WHEN (postal.postal_premise != "") THEN postal.postal_premise                              
                              WHEN (postal.postal_subpremise !="") THEN SUBSTR(postal.postal_subpremise,1,10)
                              ELSE CONCAT(postal.postal_street_number," ",postal.postal_route)   
                        END) as location_name'),'identity_merchant.identity_name as merchant_name','merchant.merchant_type_id','merchant_retail_style_list.enable')
                      ->join('location_list','location_list.list_id','merchant_retail_style_list.location_id')
                      ->join('postal','postal.postal_id','=','location_list.postal_id')               
                      ->join('merchant','merchant_retail_style_list.merchant_id','=','merchant.merchant_id')
                      ->join('identity_merchant','identity_merchant.identity_id','=','merchant.identity_id')
                      ->join('merchant_retail_style_type', 'merchant_retail_style_list.style_type_id', '=', 'merchant_retail_style_type.style_type_id')
                      ->join('identity_merchant_retail_style_type','identity_merchant_retail_style_type.identity_id','=','merchant_retail_style_type.identity_id')
                      ->distinct('merchant_retail_style_list.style_list_id')
                      ->where('merchant_retail_style_list.enable','=',1)
                      ->groupBy('merchant_retail_style_list.location_id','identity_merchant.identity_name')
                      ->orderBy('merchant_retail_style_list.style_list_id','desc')
                      ->where('postal.postal_premise', 'LIKE', '%' . $searchStyle . '%')
                      ->orWhere('identity_merchant.identity_name', 'LIKE', '%' . $searchStyle . '%')
                      ->where('merchant_retail_style_list.merchant_id','=',$merchantId)
                      ->where('merchant_retail_style_list.location_id','=',$locationId)
                      ->where('merchant.merchant_type_id','=',$merchantType)
                      ->paginate(25)->setPath('');
                }
            }
            $pagination = $hase_merchant_retail_style_lists->appends(array(
                  'search_style' => $searchStyle
            ));
            $permissions = PermissionTrait::getPermission('Hase_style_list');
            
            if (count($hase_merchant_retail_style_lists) > 0) {
                return view('hase_merchant_retail_style_list.index', compact('hase_merchant_retail_style_lists','title','permissions','labels','merchant_parent_types','merchantType','searchStyle'))->withDetails($hase_merchant_retail_style_lists)->withQuery($searchStyle);
            }
            return view('hase_merchant_retail_style_list.index', compact('hase_merchant_retail_style_lists','permissions','labels','merchant_parent_types','merchantType','searchStyle'))->withMessage('No Details found. Try to search again !');
        } else {
            return redirect('hase_style_list');
        }
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return  \Illuminate\Http\Response
     */
    public function create()
    {
        if($this->permissionDetails('Hase_style_list','add')){
            
            $merchant_data = Merchant::distinct()
                ->select('merchant.merchant_id','identity_merchant.identity_name as merchant_name','merchant_type.merchant_root_id as merchant_type')
                ->join('identity_merchant','identity_merchant.identity_id','=','merchant.identity_id')
                ->leftjoin('merchant_type_list','merchant_type_list.merchant_id','=','merchant.merchant_id')
                ->leftjoin('merchant_type','merchant_type.merchant_type_id','=','merchant_type_list.merchant_type_id')
                ->where('merchant.merchant_id','=',$this->merchantId)
                ->get()->first();

            if($this->merchantId === 0){
                $labels = array("Tag Styles","Tag Style","Merchants");

                $hase_merchants = Merchant::distinct()
                    ->select('merchant.merchant_id','identity_merchant.identity_name as merchant_name')
                    ->join('identity_merchant','identity_merchant.identity_id','=','merchant.identity_id')
                    ->where('merchant.merchant_id','!=',$this->merchantId)
                    ->get();
                $hase_locations = array();
            } else {
                
                if($merchant_data->merchant_type_id === 8)
                {
                    $labels = array("Cuisines","Cuisine","Merchants");
                } else {
                    $labels = array("Industries","Industry","Merchants");
                }

                $styleTypes = Merchant::
                    distinct('identity_merchant_retail_style_type.identity_name as style_name')
                    ->select('merchant_retail_style_type.style_type_id')
                    ->leftjoin('merchant_type_list','merchant_type_list.merchant_id','=','merchant.merchant_id')
                    ->leftjoin('merchant_type','merchant_type.merchant_type_id','=','merchant_type_list.merchant_type_id')
                    ->leftjoin('merchant_retail_style_type','merchant_retail_style_type.merchant_type_id','=','merchant_type.merchant_root_id')
                    ->join('identity_merchant_retail_style_type','identity_merchant_retail_style_type.identity_id','=','merchant_retail_style_type.identity_id')
                    ->where('merchant.merchant_id','=',$this->merchantId)
                    ->get();

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
            }
            return view('hase_merchant_retail_style_list.create',compact('title','hase_merchant_retail_style_lists','hase_locations','hase_merchants','styleTypes','labels','merchant_data'));
        }else{
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
            if(isset($request->style_type_id) && count($request->style_type_id)){

                $retail_style_list_deleted = Merchant_retail_style_list::whereNotIn('style_type_id', $request->style_type_id)
                    ->where('merchant_id',$merchantID)
                    ->where('location_id',$locationID)
                    ->get();

                 Merchant_retail_style_list::whereNotIn('style_type_id', $request->style_type_id)
                ->where('merchant_id',$merchantID)
                ->where('location_id',$locationID)
                ->delete();

                if(count($retail_style_list_deleted))
                {
                    foreach ($retail_style_list_deleted as $key => $value) {
                        $updateStyleColumn = array('style_type_id');

                        $approvalStatus = $this->productApproval->ApprovalActionStatusTransitionBySelf("change");

                        $approvalCrudStatus = $this->productApproval->getApprovalCrudStatusId("delete");

                        $this->updateAdminForApprove($value['style_list_id'],$updateStyleColumn,$this->request_table_live,$approvalStatus,$approvalCrudStatus,$merchantID,$locationID);
                    }
                }

                foreach ($request->style_type_id as $key => $value) {
                    $isExist = Merchant_retail_style_list::
                        where('merchant_id', '=', $merchantID)
                        ->where('location_id',$locationID)
                        ->where('style_type_id',$value)
                        ->first();

                    if(is_null($isExist)){
                        $styleListSet = array(
                            'merchant_id' => $merchantID,
                            'location_id' => $locationID,
                            'style_type_id'=>$value,
                            'priority'=>$request->styles[$value]['priority'],
                            'enable'=>isset($request->enable) ? 1 : 0 ,
                        );
                        
                        $admin_list_id = Merchant_retail_style_list::insertGetId($styleListSet);

                        $updateStyleColumn = array('location_id','style_type_id','priority','enable');
                        
                        $approvalStatus = $this->productApproval->ApprovalActionStatusTransitionBySelf("change");

                        $approvalCrudStatus = $this->productApproval->getApprovalCrudStatusId("insert");

                        $this->addAdminForApprove($admin_list_id,$updateStyleColumn,$this->request_table_live,$approvalStatus,$approvalCrudStatus,$merchantID,$locationID);

                    } else {
                        $isExist->priority = $request->styles[$value]['priority'];
                        $isExist->enable = isset($request->enable) ? 1 : 0;    
                        $isExist->update();
                    }
                }
            } else {

                $retail_style_list_deleted = Merchant_retail_style_list::
                    where('merchant_id',$merchantID)
                    ->where('location_id',$locationID)
                    ->get();

                Merchant_retail_style_list::
                    where('merchant_id',$merchantID)
                    ->where('location_id',$locationID)
                    ->delete();

                if(count($retail_style_list_deleted))
                {
                    foreach ($retail_style_list_deleted as $key => $value) {
                        $updateStyleColumn = array('style_type_id');

                        $approvalStatus = $this->productApproval->ApprovalActionStatusTransitionBySelf("change");

                        $approvalCrudStatus = $this->productApproval->getApprovalCrudStatusId("delete");

                        $this->updateAdminForApprove($value['style_list_id'],$updateStyleColumn,$this->request_table_live,$approvalStatus,$approvalCrudStatus,$merchantID,$locationID);
                    }
                }
            }
        } else {
            if(isset($request->style_type_id) && count($request->style_type_id)){
                $retail_style_list_deleted = Merchant_retail_style_list::whereNotIn('style_type_id', $request->style_type_id)
                    ->where('merchant_id',$merchantID)
                    ->where('location_id',$locationID)
                    ->get();
            } else {
                $retail_style_list_deleted = Merchant_retail_style_list::
                    where('merchant_id',$merchantID)
                    ->where('location_id',$locationID)
                    ->get();
            }
            if(count($retail_style_list_deleted))
            {
                foreach ($retail_style_list_deleted as $key => $value) {

                    $hase_merchant_retail_style_list_stage = new Merchant_retail_style_list_stage();

                    $hase_merchant_retail_style_list_stage->staff_id = $this->staffId;
                    $hase_merchant_retail_style_list_stage->merchant_id = $value['merchant_id'];
                    $hase_merchant_retail_style_list_stage->location_id = $value['location_id'];
                    $hase_merchant_retail_style_list_stage->style_type_id = $value['style_type_id'];
                    $hase_merchant_retail_style_list_stage->priority = $value['priority'];
                    $hase_merchant_retail_style_list_stage->enable = 0;
                    $hase_merchant_retail_style_list_stage->save();


                    $updateStyleColumn = array('style_type_id','priority','enable');
                    
                    $approvalStatus = $this->productApproval->ApprovalActionStatusTransitionBySelf("change");

                    $approvalCrudStatus = $this->productApproval->getApprovalCrudStatusId("delete");

                    $this->updateForApprove($value['style_list_id'],$hase_merchant_retail_style_list_stage->style_list_id,$updateStyleColumn,$this->request_table_live,$this->request_table_stage,$approvalStatus,$approvalCrudStatus,$merchantID,$locationID);
                }
            }
            if(isset($request->style_type_id) && count($request->style_type_id)) {
                foreach ($request->style_type_id as $key => $value) {
                    $isExist = Merchant_retail_style_list::
                        where('merchant_id', '=', $merchantID)
                        ->where('location_id',$locationID)
                        ->where('style_type_id',$value)
                        ->first();

                    if(is_null($isExist)){
                        $hase_merchant_retail_style_list = new Merchant_retail_style_list_stage();

                        $hase_merchant_retail_style_list->staff_id = $this->staffId;
                        $hase_merchant_retail_style_list->merchant_id = $merchantID;
                        $hase_merchant_retail_style_list->location_id = $locationID;
                        $hase_merchant_retail_style_list->style_type_id = $value;
                        $hase_merchant_retail_style_list->priority = $request->styles[$value]['priority'];
                        $hase_merchant_retail_style_list->enable = 
                                                isset($request->enable) ? 1 : 0 ;
                        $hase_merchant_retail_style_list->save();


                        $updateStyleColumn = array('style_type_id','priority','enable');
                        
                        $approvalStatus = $this->productApproval->ApprovalActionStatusTransitionBySelf("change");

                        $approvalCrudStatus = $this->productApproval->getApprovalCrudStatusId("insert");

                        $this->addForApprove($hase_merchant_retail_style_list->style_list_id,$updateStyleColumn,$this->request_table_live,$this->request_table_stage,$approvalStatus,$approvalCrudStatus,$merchantID,$locationID);
                    }
                }
            }
        }
        $staffUrl = "/hase_staff/".$this->staffId."/edit";
        $action="added";
        $message = "<a href='".URL::to($staffUrl)."'>".$this->staffName."</a> <strong>added</strong> new style";
        PermissionTrait::addActivityLog($action,$message);
        Session::flash('type', 'success'); 
        Session::flash('msg', 'Style Successfully Created'); 
        return redirect('hase_style_list');
    }

    /**
     * Show the form for editing the specified resource.
     * @param    \Illuminate\Http\Request  $request
     * @param    int  $id
     * @return  \Illuminate\Http\Response
     */
    public function edit($id,Request $request)
    {
        if($this->permissionDetails('Hase_style_list','manage')){
            
            $title = 'Edit - Hase Style List';
            $hase_style_exist=array();

            $hase_merchant_retail_style_list = Merchant_retail_style_list::findOrfail($id);

            $merchant_data = Merchant::
                        distinct()
                        ->select('merchant.merchant_id','identity_merchant.identity_name as merchant_name','merchant_type.merchant_root_id as merchant_type')
                        ->leftjoin('identity_merchant','identity_merchant.identity_id','=','merchant.identity_id')
                        ->leftjoin('merchant_type_list','merchant_type_list.merchant_id','=','merchant.merchant_id')
                        ->leftjoin('merchant_type','merchant_type.merchant_type_id','=','merchant_type_list.merchant_type_id')
                        ->where('merchant.merchant_id','=',$hase_merchant_retail_style_list->merchant_id)
                        ->get()->first();

            $location_data = Location_list::
                    select('list_id as location_id',DB::raw('(CASE WHEN (postal.postal_premise != "" and postal.postal_subpremise != "") THEN CONCAT(postal.postal_premise,", ",SUBSTR(postal.postal_subpremise,1,10))
                              WHEN (postal.postal_premise != "") THEN postal.postal_premise                              
                              WHEN (postal.postal_subpremise !="") THEN SUBSTR(postal.postal_subpremise,1,10)
                              ELSE CONCAT(postal.postal_street_number," ",postal.postal_route)   
                        END) as location_name'))
                    ->leftjoin('postal','postal.postal_id','location_list.postal_id')                    
                    ->where('location_list.list_id','=',$hase_merchant_retail_style_list->location_id)
                    ->get()->first();           

            $stylesExist = Merchant_retail_style_list::
                            where('location_id','=',$hase_merchant_retail_style_list->location_id)
                            ->where('enable',1)
                            ->get()->toArray();

            $styleTypes = Merchant_retail_style_type::
                            select('merchant_retail_style_type.style_type_id','identity_merchant_retail_style_type.identity_name as style_name')
                            ->leftjoin('identity_merchant_retail_style_type','identity_merchant_retail_style_type.identity_id','=','merchant_retail_style_type.identity_id')
                            ->where('merchant_retail_style_type.merchant_type_id','=',$merchant_data->merchant_type)
                            ->get();

            if($this->merchantId === 0){

                $labels = array("Tag Styles","Tag Style","Merchants");
                
            } else {

                $merchant_data = Merchant::
                        distinct()
                        ->select('merchant.merchant_id','identity_merchant.identity_name as merchant_name','merchant_type.merchant_root_id as merchant_type')
                        ->leftjoin('identity_merchant','identity_merchant.identity_id','=','merchant.identity_id')
                        ->leftjoin('merchant_type_list','merchant_type_list.merchant_id','=','merchant.merchant_id')
                        ->leftjoin('merchant_type','merchant_type.merchant_type_id','=','merchant_type_list.merchant_type_id')
                        ->where('merchant.merchant_id','=',$this->merchantId)
                        ->get()->first();

                if($merchant_data->merchant_type === 8)
                {
                    $labels = array("Cuisines","Cuisine","Merchants");
                } else {
                    $labels = array("Industries","Industry","Merchants");
                }
            }
            foreach ($stylesExist as $key => $value) {
                $hase_style_exist['style_type_id'][] = $value['style_type_id'];
                $hase_style_exist['priority'][$value['style_type_id']] = $value['priority'];
            }

            return view('hase_merchant_retail_style_list.edit',compact('title','hase_merchant_retail_style_lists','merchant_data','location_data','labels','styleTypes','hase_merchant_retail_style_list','hase_style_exist','labels'));
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
    public function update($id,Request $request)
    {
        $merchantID = $request->merchant_id;
        $locationID = $request->location_id;
        $retails_type_list = array();
        if($this->roleId === 1){
            if(isset($request->style_type_id) && count($request->style_type_id)){

                $retail_style_list_deleted = Merchant_retail_style_list::whereNotIn('style_type_id', $request->style_type_id)
                    ->where('merchant_id',$merchantID)
                    ->where('location_id',$locationID)
                    ->get();

                 Merchant_retail_style_list::whereNotIn('style_type_id', $request->style_type_id)
                ->where('merchant_id',$merchantID)
                ->where('location_id',$locationID)
                ->delete();

                if(count($retail_style_list_deleted))
                {
                    foreach ($retail_style_list_deleted as $key => $value) {
                        $updateStyleColumn = array('style_type_id');

                        $approvalStatus = $this->productApproval->ApprovalActionStatusTransitionBySelf("change");

                        $approvalCrudStatus = $this->productApproval->getApprovalCrudStatusId("delete");

                        $this->updateAdminForApprove($value['style_list_id'],$updateStyleColumn,$this->request_table_live,$approvalStatus,$approvalCrudStatus,$merchantID,$locationID);
                    }
                }
                foreach ($request->style_type_id as $key => $value) {
                    $isExist = Merchant_retail_style_list::
                        where('merchant_id', '=', $merchantID)
                        ->where('location_id',$locationID)
                        ->where('style_type_id',$value)
                        ->first();
                    if(is_null($isExist)){
                        $styleListSet = array(
                            'merchant_id' => $merchantID,
                            'location_id' => $locationID,
                            'style_type_id'=>$value,
                            'priority'=>$request->styles[$value]['priority'],
                            'enable'=>isset($request->enable) ? 1 : 0 ,
                        );

                        $admin_list_id = Merchant_retail_style_list::insertGetId($styleListSet);

                        $updateStyleColumn = array('location_id','style_type_id','priority','enable');
                        
                        $approvalStatus = $this->productApproval->ApprovalActionStatusTransitionBySelf("change");

                        $approvalCrudStatus = $this->productApproval->getApprovalCrudStatusId("insert");

                        $this->addAdminForApprove($admin_list_id,$updateStyleColumn,$this->request_table_live,$approvalStatus,$approvalCrudStatus,$merchantID,$locationID);

                    } else {
                        $isExist->priority = $request->styles[$value]['priority'];
                        $isExist->enable = isset($request->enable) ? 1 : 0;    
                        $isExist->update();
                    }
                }
            } else {

                $retail_style_list_deleted = Merchant_retail_style_list::where('merchant_id',$merchantID)
                    ->where('location_id',$locationID)
                    ->get();

                Merchant_retail_style_list::
                    where('merchant_id',$merchantID)
                    ->where('location_id',$locationID)
                    ->delete();

                if(count($retail_style_list_deleted))
                {
                    foreach ($retail_style_list_deleted as $key => $value) {
                        $updateStyleColumn = array('style_type_id');

                        $approvalStatus = $this->productApproval->ApprovalActionStatusTransitionBySelf("change");

                        $approvalCrudStatus = $this->productApproval->getApprovalCrudStatusId("delete");

                        $this->updateAdminForApprove($value['style_list_id'],$updateStyleColumn,$this->request_table_live,$approvalStatus,$approvalCrudStatus,$merchantID,$locationID);
                    }
                }
            }
        } else {
            if(isset($request->style_type_id) && count($request->style_type_id)){
                $retail_style_list_deleted = Merchant_retail_style_list::whereNotIn('style_type_id', $request->style_type_id)
                    ->where('merchant_id',$merchantID)
                    ->where('location_id',$locationID)
                    ->get();
            } else {
                $retail_style_list_deleted = Merchant_retail_style_list::
                    where('merchant_id',$merchantID)
                    ->where('location_id',$locationID)
                    ->get();
            }
            if(count($retail_style_list_deleted))
            {
                foreach ($retail_style_list_deleted as $key => $value) {

                    $hase_merchant_retail_style_list_stage = new Merchant_retail_style_list_stage();

                    $hase_merchant_retail_style_list_stage->staff_id = $this->staffId;
                    $hase_merchant_retail_style_list_stage->merchant_id = $value['merchant_id'];
                    $hase_merchant_retail_style_list_stage->location_id = $value['location_id'];
                    $hase_merchant_retail_style_list_stage->style_type_id = $value['style_type_id'];
                    $hase_merchant_retail_style_list_stage->priority = $value['priority'];
                    $hase_merchant_retail_style_list_stage->enable = 0;
                    $hase_merchant_retail_style_list_stage->save();


                    $updateStyleColumn = array('style_type_id','priority','enable');
                    
                    $approvalStatus = $this->productApproval->ApprovalActionStatusTransitionBySelf("change");

                    $approvalCrudStatus = $this->productApproval->getApprovalCrudStatusId("delete");

                    $this->updateForApprove($value['style_list_id'],$hase_merchant_retail_style_list_stage->style_list_id,$updateStyleColumn,$this->request_table_live,$this->request_table_stage,$approvalStatus,$approvalCrudStatus,$merchantID,$locationID);
                }
            }
            if(isset($request->style_type_id) && count($request->style_type_id)) {
                foreach ($request->style_type_id as $key => $value) {
                    $isExist = Merchant_retail_style_list::
                        where('merchant_id', '=', $merchantID)
                        ->where('location_id',$locationID)
                        ->where('style_type_id',$value)
                        ->first();

                    if(is_null($isExist)){
                        $hase_merchant_retail_style_list = new Merchant_retail_style_list_stage();

                        $hase_merchant_retail_style_list->staff_id = $this->staffId;
                        $hase_merchant_retail_style_list->merchant_id = $merchantID;
                        $hase_merchant_retail_style_list->location_id = $locationID;
                        $hase_merchant_retail_style_list->style_type_id = $value;
                        $hase_merchant_retail_style_list->priority = $request->styles[$value]['priority'];
                        $hase_merchant_retail_style_list->enable = 
                                                isset($request->enable) ? 1 : 0 ;
                        $hase_merchant_retail_style_list->save();


                        $updateStyleColumn = array('style_type_id','priority','enable');
                        
                        $approvalStatus = $this->productApproval->ApprovalActionStatusTransitionBySelf("change");

                        $approvalCrudStatus = $this->productApproval->getApprovalCrudStatusId("insert");

                        $this->addForApprove($hase_merchant_retail_style_list->style_list_id,$updateStyleColumn,$this->request_table_live,$this->request_table_stage,$approvalStatus,$approvalCrudStatus,$merchantID,$locationID);
                    }
                }
            }
        }
        $staffUrl = "/hase_staff/".$this->staffId."/edit";
        $action="updated";
        $message = "<a href='".URL::to($staffUrl)."'>".$this->staffName."</a> <strong>updated</strong> style";
        PermissionTrait::addActivityLog($action,$message);
        Session::flash('type', 'success'); 
        Session::flash('msg', 'Style Successfully Updated'); 
        return redirect('hase_style_list');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param    int $id
     * @return  \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        
        if($this->permissionDetails('Hase_style_list','delete')){
            $styleListData = Merchant_retail_style_list::findOrfail($id);
            if ($this->roleId === 1) {
               $hase_merchant_retail_style_list = Merchant_retail_style_list::findOrfail($id);
               $hase_merchant_retail_style_list->enable = 0;
               $hase_merchant_retail_style_list->save();

               $updateStyleColumn = array('enable');

               $approvalStatus = $this->productApproval->ApprovalActionStatusTransitionBySelf("change");

               $approvalCrudStatus = $this->productApproval->getApprovalCrudStatusId("delete");

               $this->updateForApprove($id,$updateStyleColumn,$this->request_table_live,$approvalStatus,$approvalCrudStatus,$styleListData->merchant_id,$styleListData->location_id);
            }else{
               $hase_merchant_retail_style_list_stage = new Merchant_retail_style_list_stage();

               $hase_merchant_retail_style_list_stage->staff_id = $this->staffId;
               $hase_merchant_retail_style_list_stage->enable = 0;
               $hase_merchant_retail_style_list_stage->save();

               $updateStyleColumn = array('enable');

               $approvalStatus = $this->productApproval->ApprovalActionStatusTransitionBySelf("change");

               $approvalCrudStatus = $this->productApproval->getApprovalCrudStatusId("delete");

               $this->updateForApprove($id,$hase_merchant_retail_style_list_stage->style_list_id,$updateStyleColumn,$this->request_table_live,$this->request_table_stage,$approvalStatus,$approvalCrudStatus,$styleListData->merchant_id,$styleListData->location_id);
            }

            $staffUrl = "/hase_staff/".$this->staffId."/edit";
            $action="deleted";
            $message = "<a href='".URL::to($staffUrl)."'>".$this->staffName."</a> <strong>deleted</strong> retail style list";
            PermissionTrait::addActivityLog($action,$message);

            Session::flash('type', 'success'); 
            Session::flash('msg', 'style Successfully Deleted');
            return redirect(Requests::segment(1));
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
