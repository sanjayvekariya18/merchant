<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\App;
use Illuminate\Http\Request;
use App\Http\Controllers\PermissionsController;
use App\Helpers\ConnectionManager;
use App\Http\Controllers\ProductApprovalController;
use App\Http\Traits\PermissionTrait;
use App\Promotion;
use App\Promotion_stage;
use App\Merchant;
use App\City;
use App\Postal;
use App\Approval;
use App\Location_list;
use App\Merchant_type;
use Carbon\Carbon;
use URL;
use DB;
use Auth;
use DateTime;
use Session;
use Redirect;

/**
 * Class Hase_promotionController.
 *
 * @author  The scaffold-interface created at 2017-03-18 08:36:46am
 * @link  https://github.com/amranidev/scaffold-interface
 */
class Hase_promotionController extends PermissionsController
{
    use PermissionTrait;
    const MERCHANT_TABLE_IDENTITY_TYPE = 8;

    public function __construct()
    {
        parent::__construct();

        $connectionStatus = ConnectionManager::setDbConfig('Hase_promotion');

        if ($connectionStatus['type'] === "error") {
            Session::flash('type', $connectionStatus['type']);
            Session::flash('msg', $connectionStatus['message']);
            return Redirect::back()->send();
        }

        $this->request_table_live = 36;
        $this->request_table_stage = 37;
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

        if($this->permissionDetails('Hase_promotion','access')) {
            $title = 'Index - hase_promotion';
            $searchPromotion = '';
            $permissions = $this->getPermission('Hase_promotion');
            $merchant_parent_types = Merchant_type::all()->where('merchant_parent_id',0);
            $where = array();
            if($this->merchantId === 0){
                    $where[] = array(
                        'key' => "merchant_type.merchant_root_id",
                        'operator' => '=',
                        'val' => $merchantType
                    );
            }else{
                if($this->roleId === 4){
                    $where[] = array(
                        'key' => "promotions.merchant_id",
                        'operator' => '=',
                        'val' => $this->merchantId
                    );
                    $where[] = array(
                        'key' => "promotions.status",
                        'operator' => '=',
                        'val' => 1
                    );
                }else{
                    $where[] = array(
                        'key' => "promotions.merchant_id",
                        'operator' => '=',
                        'val' => $this->merchantId
                    );
                    $where[] = array(
                        'key' => "location_list.postal_id",
                        'operator' => '=',
                        'val' => $this->locationId
                    );
                    $where[] = array(
                        'key' => "promotions.status",
                        'operator' => '=',
                        'val' => 1
                    );
                }
            }
            $where[] = array(
                'key' => "location_list.identity_table_id",
                'operator' => '=',
                'val' => self::MERCHANT_TABLE_IDENTITY_TYPE
            );
            $hase_promotions = Promotion::distinct()
                ->select('promotions.*','identity_merchant.identity_name as merchant_name',DB::raw('(CASE WHEN (postal.postal_premise != "" and postal.postal_subpremise != "") THEN CONCAT(postal.postal_premise,", ",SUBSTR(postal.postal_subpremise,1,10))
                              WHEN (postal.postal_premise != "") THEN postal.postal_premise                              
                              WHEN (postal.postal_subpremise !="") THEN SUBSTR(postal.postal_subpremise,1,10)
                              ELSE CONCAT(postal.postal_street_number," ",postal.postal_route)   
                        END) as location_name'))
                ->join('location_list','promotions.location_id','=','location_list.list_id') 
                ->join('location_city','location_list.location_city_id','=','location_city.city_id') 
                ->join('merchant','merchant.merchant_id','=','promotions.merchant_id')
                ->join('merchant_type_list','merchant_type_list.merchant_id','=','merchant.merchant_id')
                ->join('merchant_type','merchant_type.merchant_type_id','=','merchant_type_list.merchant_type_id')
                ->join('identity_merchant','identity_merchant.identity_id','=','merchant.identity_id')
                ->join('postal','postal.postal_id','=','location_list.postal_id')
                ->groupBy('promotions.promotion_id')
                ->where(function($q) use ($where){
                    foreach($where as $key => $value){
                        $q->where($value['key'], $value['operator'], $value['val']);
                    }
                })
                ->paginate(25);
            
            return view('hase_promotion.index',compact('hase_promotions','title','permissions','merchant_parent_types','merchantType','searchPromotion'));
        } else {
            return Redirect::back()->with('message', 'You are not authorized to use this functionality!');
        }
    }

    /**
     * search resource in storage.
     *
     * @param    \Illuminate\Http\Request  $request
     * @return  \Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        $searchPromotion = trim($request->search_promotion);
        $merchantId = session()->has('merchantId') ? session()->get('merchantId') : '';
        $roleId = session()->has('role') ? session()->get('role') : '';
        $locationId = session()->has('locationId') ? session()->get('locationId') :"";
        $merchantType = session()->has('merchantType') ? session()->get('merchantType') :"";
        $merchant_parent_types = Merchant_type::all()->where('merchant_parent_id',0);
        if(!empty($searchPromotion)) {
            if($merchantId === 0) {

                $hase_promotions = Promotion::distinct()
                  ->select('promotions.*','identity_merchant.identity_name as merchant_name',DB::raw('(CASE WHEN (postal.postal_premise != "" and postal.postal_subpremise != "") THEN CONCAT(postal.postal_premise,", ",SUBSTR(postal.postal_subpremise,1,10))
                              WHEN (postal.postal_premise != "") THEN postal.postal_premise                              
                              WHEN (postal.postal_subpremise !="") THEN SUBSTR(postal.postal_subpremise,1,10)
                              ELSE CONCAT(postal.postal_street_number," ",postal.postal_route)   
                        END) as location_name'))
                  ->join('location_list','promotions.location_id','=','location_list.postal_id') 
                  ->join('location_city','location_list.location_city_id','=','location_city.city_id') 
                  ->join('merchant','merchant.merchant_id','=','promotions.merchant_id')
                  ->join('merchant_type_list','merchant_type_list.merchant_id','=','merchant.merchant_id')
                  ->join('merchant_type','merchant_type.merchant_type_id','=','merchant_type_list.merchant_type_id')
                  ->join('identity_merchant','identity_merchant.identity_id','=','merchant.identity_id')
                  ->join('postal','postal.postal_id','=','location_list.postal_id')                
                  ->where('postal.postal_premise', 'LIKE', '%' . $searchPromotion . '%')
                  ->orWhere('identity_merchant.identity_name', 'LIKE', '%' . $searchPromotion . '%')
                  ->where('merchant.merchant_type_id','=',$merchantType)
                  ->groupBy('promotions.promotion_id')                
                  ->paginate(25)->setPath('');

            } else {
                if($roleId === 4)
                {

                    $hase_promotions = Promotion::distinct()
                        ->select('promotions.*','identity_merchant.identity_name as merchant_name',DB::raw('(CASE WHEN (postal.postal_premise != "" and postal.postal_subpremise != "") THEN CONCAT(postal.postal_premise,", ",SUBSTR(postal.postal_subpremise,1,10))
                              WHEN (postal.postal_premise != "") THEN postal.postal_premise                              
                              WHEN (postal.postal_subpremise !="") THEN SUBSTR(postal.postal_subpremise,1,10)
                              ELSE CONCAT(postal.postal_street_number," ",postal.postal_route)   
                        END) as location_name'))
                        ->join('location_list','promotions.location_id','=','location_list.postal_id') 
                        ->join('location_city','location_list.location_city_id','=','location_city.city_id') 
                        ->join('merchant','merchant.merchant_id','=','promotions.merchant_id')
                        ->join('merchant_type_list','merchant_type_list.merchant_id','=','merchant.merchant_id')
                        ->join('merchant_type','merchant_type.merchant_type_id','=','merchant_type_list.merchant_type_id')
                        ->join('identity_merchant','identity_merchant.identity_id','=','merchant.identity_id')
                        ->join('postal','postal.postal_id','=','location_list.postal_id')
                        ->where('postal.postal_premise', 'LIKE', '%' . $searchPromotion . '%')
                        ->orWhere('identity_merchant.identity_name', 'LIKE', '%' . $searchPromotion . '%')
                        ->where('merchant.merchant_id','=',$merchantId)                      
                        ->where('promotions.status','=',1)
                        ->where('merchant.merchant_type_id','=',$merchantType)
                        ->paginate(25)->setPath('');

                } else {

                    $hase_promotions = Promotion::distinct()
                        ->select('promotions.*','identity_merchant.identity_name as merchant_name',DB::raw('(CASE WHEN (postal.postal_premise != "" and postal.postal_subpremise != "") THEN CONCAT(postal.postal_premise,", ",SUBSTR(postal.postal_subpremise,1,10))
                              WHEN (postal.postal_premise != "") THEN postal.postal_premise                              
                              WHEN (postal.postal_subpremise !="") THEN SUBSTR(postal.postal_subpremise,1,10)
                              ELSE CONCAT(postal.postal_street_number," ",postal.postal_route)   
                        END) as location_name'))
                        ->join('location_list','promotions.location_id','=','location_list.postal_id') 
                        ->join('location_city','location_list.location_city_id','=','location_city.city_id') 
                        ->join('merchant','merchant.merchant_id','=','promotions.merchant_id')
                        ->join('merchant_type_list','merchant_type_list.merchant_id','=','merchant.merchant_id')
                        ->join('merchant_type','merchant_type.merchant_type_id','=','merchant_type_list.merchant_type_id')
                        ->join('identity_merchant','identity_merchant.identity_id','=','merchant.identity_id')
                        ->join('postal','postal.postal_id','=','location_list.postal_id')
                        ->where('postal.postal_premise', 'LIKE', '%' . $searchPromotion . '%')
                        ->orWhere('identity_merchant.identity_name', 'LIKE', '%' . $searchPromotion . '%')
                        ->where('merchant.merchant_id','=',$merchantId)
                        ->where('location_list.list_id','=',$locationId)                      
                        ->where('promotions.status','=',1)
                        ->where('merchant.merchant_type_id','=',$merchantType)
                        ->paginate(25)->setPath('');
                }
            }
            $pagination = $hase_promotions->appends(array(
                'search_promotion' => $searchPromotion 
            ));
            $permissions = PermissionTrait::getPermission('Hase_promotion');
            
            if (count($hase_promotions) > 0) {
                return view('hase_promotion.index', compact('hase_promotions','title','permissions','merchant_parent_types','merchantType','searchPromotion'))->withDetails($hase_promotions)->withQuery($searchPromotion);
            }
            return view('hase_promotion.index', compact('hase_promotions','permissions','merchant_parent_types','merchantType','searchPromotion'))->withMessage('No Details found. Try to search again !');
        } else {
            return redirect('hase_promotion');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return  \Illuminate\Http\Response
     */
    public function create()
    {
        if($this->permissionDetails('Hase_promotion','add')){
            $roleId = $this->roleId;
            $merchantId = $this->merchantId;
            $title = 'Create - hase_promotion';
            $merchant_parent_types = Merchant_type::all()->where('merchant_parent_id',0);
            $merchantType = session()->has('merchantType') ? session()->get('merchantType') :"";

            if($this->merchantId === 0 ) {
                $labels = array("Featured Offer");
                $hase_merchants = Merchant::distinct()
                    ->select('merchant.merchant_id','identity_merchant.identity_name as merchant_name')
                    ->join('identity_merchant','identity_merchant.identity_id','=','merchant.identity_id')
                    ->where('merchant.merchant_id','!=',0)
                    ->where('merchant.merchant_type_id',$merchantType)
                    ->get();

                $merchant_cities = array();
                $merchant_city_postals = array();

            } else {

                $hase_merchants = Merchant::
                            select('merchant.merchant_id','identity_merchant.identity_name as merchant_name')
                            ->join('identity_merchant','identity_merchant.identity_id','=','merchant.identity_id')
                            ->where('merchant.merchant_id','=',$this->merchantId)
                            ->where('merchant.merchant_type_id',$merchantType)
                            ->get()->first();


                if($hase_merchants->merchant_type === 8)
                {
                    $labels = array("Dining Experience");
                } else {
                    $labels = array("Online Shopping");
                }

                if($this->roleId === 4)
                {
                    $merchant_cities = City::distinct()
                        ->select('location_city.city_id','location_city.city_name as city_name')
                        ->leftjoin('location_list','location_city.city_id','=','location_list.location_city_id')
                        ->leftjoin('merchant','merchant.identity_id','=','location_list.identity_id')
                        ->where('location_list.identity_table_id','=',self::MERCHANT_TABLE_IDENTITY_TYPE)
                        ->where('merchant.merchant_id','=',$this->merchantId)
                        ->get();
                    $merchant_city_postals = array();

                } else {
                    $merchant_cities = City::distinct()
                        ->select('location_city.city_id','location_city.city_name as city_name')
                        ->leftjoin('location_list','location_city.city_id','=','location_list.location_city_id')
                        ->leftjoin('merchant','merchant.identity_id','=','location_list.identity_id')
                        ->where('location_list.identity_table_id','=',self::MERCHANT_TABLE_IDENTITY_TYPE)
                        ->where('merchant.merchant_id','=',$this->merchantId)
                        ->where('location_list.postal_id','=',$this->locationId)
                        ->get();
                    $merchant_city_postals = Postal::distinct()
                        ->select('location_list.postal_id as location_id',DB::raw('(CASE WHEN (postal.postal_premise != "" and postal.postal_subpremise != "") THEN CONCAT(postal.postal_premise,", ",SUBSTR(postal.postal_subpremise,1,10))
                              WHEN (postal.postal_premise != "") THEN postal.postal_premise                              
                              WHEN (postal.postal_subpremise !="") THEN SUBSTR(postal.postal_subpremise,1,10)
                              ELSE CONCAT(postal.postal_street_number," ",postal.postal_route)   
                        END) as location_name'))
                        ->join('location_list','location_list.postal_id','=','postal.postal_id')
                        ->join('merchant','merchant.identity_id','=','location_list.identity_id')
                        ->where('merchant.merchant_id','=',$this->merchantId)
                        ->where('location_list.postal_id','=',$this->locationId)
                        ->where('location_list.identity_table_id','=',self::MERCHANT_TABLE_IDENTITY_TYPE)
                        ->get();
                }
            }
            return view('hase_promotion.create',compact('title','merchant_cities','hase_merchants','merchantId','labels','roleId','merchant_city_postals','merchant_parent_types','merchantType'));
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
        /*echo "<pre>";
        print_r($request->toArray());
        die;*/
        if($this->roleId === 1)
        {
            $hase_promotion = new Promotion();
        } else {
            $hase_promotion = new Promotion_stage();
        }

        //$hase_promotion->getKeyName();

        $request->status = (isset($request->status)) ? 1 : 0;

        if($this->roleId !== 1) {
            $hase_promotion->staff_id = $this->staffId;
        } 
        if($this->merchantId !== 0) {
            $merchantID = $this->merchantId;
        } else {
            $merchantID = $request->merchant_id;
        }
        $locationID = $request->location_id;

        $hase_promotion->merchant_id = $merchantID;
        $hase_promotion->location_id = $locationID;
        $hase_promotion->offer_url = $request->offer_url;
        $hase_promotion->offer_details = $request->offer_details;
        $hase_promotion->status = $request->status;        
        $hase_promotion->offer_terms = $request->offer_terms;
        $hase_promotion->offer_featured = (isset($request->featured_status)) ?
                                            $request->offer_featured : 0;        
        $hase_promotion->offer_hottest = (isset($request->hottest_status)) ?
                                            $request->offer_hottest : 0;

        if(isset($request->year_round))
        {   
            $hase_promotion->offer_begin = str_replace("-","",date('Y-m-d', strtotime('12/31')));
            $hase_promotion->offer_expire = str_replace("-","",date('Y-m-d', strtotime('12/31')));
        }else{

            $hase_promotion->offer_begin = (!empty($request->offer_begin))?str_replace("-","",date('Y-m-d',strtotime($request->offer_begin))) : 0;

            $hase_promotion->offer_expire = (!empty($request->offer_expire))?str_replace("-","",date('Y-m-d',strtotime($request->offer_expire))) : 0;
        }

        $hase_merchant = Merchant::
                            select('merchant.merchant_id','identity_merchant.identity_name as merchant_name')
                        ->leftjoin('identity_merchant','identity_merchant.identity_id','=','merchant.identity_id') 
                        ->where('merchant.merchant_id',$merchantID)
                        ->get()->first();

        $hase_location = postal::
                        select('postal_id','postal_premise as location_name')
                        ->where('postal_id',$locationID)
                        ->get()->first();

                        
        $merchantDirName = md5($hase_merchant->merchant_name);
        $locationDirName = md5($hase_merchant->merchant_name.$hase_location->location_name);

        if($request->live_image_url)
        {
            $hase_promotion->image_url = $request->live_image_url;
        } else {
            if($request->file('image_url')){

                $publicDirPath = public_path(env('image_dir_path'));
                
                if($this->roleId === 1){
                    $imageDirPath = "merchant/$merchantDirName/location/$locationDirName/";
                }else{
                    $imageDirPath = "merchant/$merchantDirName/location/$locationDirName/";
                }

                $absoluteImageDirPath = $publicDirPath.$imageDirPath;

                if(!file_exists($absoluteImageDirPath)){
                    mkdir($absoluteImageDirPath,0777,true);
                }

                $imageName = $request->file('image_url')->getClientOriginalName();
                $imageArray = explode('.', $imageName);
                $hashImageName = md5($hase_merchant->merchant_name.$hase_location->location_name.$hase_promotion->offer_details.$imageName).".".$imageArray[1];
                $request->file('image_url')->move($absoluteImageDirPath,$hashImageName);
                $hase_promotion->image_url = "$imageDirPath$hashImageName";
            }
        }

        if($request->live_image_compact_url)
        {
            $hase_promotion->image_url_compact = $request->live_image_compact_url;
        } else {
            if($request->file('image_url_compact')){

                $publicDirPath = public_path(env('image_dir_path'));
                
                if($this->roleId === 1){
                    $imageDirPath = "merchant/$merchantDirName/location/$locationDirName/";
                }else{
                    $imageDirPath = "merchant/$merchantDirName/location/$locationDirName/";
                }

                $absoluteImageDirPath = $publicDirPath.$imageDirPath;

                if(!file_exists($absoluteImageDirPath)){
                    mkdir($absoluteImageDirPath,0777,true);
                }

                $imageName = $request->file('image_url_compact')->getClientOriginalName();
                $imageArray = explode('.', $imageName);
                $hashImageName = md5($hase_merchant->merchant_name.$hase_location->location_name.$hase_promotion->offer_details.$imageName).".".$imageArray[1];
                $request->file('image_url_compact')->move($absoluteImageDirPath,$hashImageName);
                $hase_promotion->image_url_compact = "$imageDirPath$hashImageName";
            }
        }

        $hase_promotion->save();
        
        $staffUrl = "/hase_staff/".session('staffId')."/edit";
        $action = "added";
        if($this->roleId === 1)
        {   

            $approvalStatus = $this->productApproval->ApprovalActionStatusTransitionBySelf("change");

            $approvalCrudStatus = $this->productApproval->getApprovalCrudStatusId("insert");

            $updatedPromotionColumns = array('merchant_id','location_id','image_url','image_url_compact','offer_details','offer_terms','offer_featured','offer_hottest','offer_begin','offer_expire','offer_url','status');

            $this->addAdminForApprove($hase_promotion->promotion_id,$updatedPromotionColumns,$this->request_table_live,$approvalStatus,$approvalCrudStatus,$merchantID,$request->location_id);

            $promotionUrl = "/hase_promotion/".$hase_promotion->promotion_id."/edit";
            $message = "<a href='".URL::to($staffUrl)."'>".$this->staffName."</a> <strong>".$action."</strong> new promotion <a href='".URL::to($promotionUrl)."'> <strong>".$hase_promotion->offer_details."</strong></a>";
            PermissionTrait::addActivityLog($action,$message);
        } else {
            
            $approvalStatus = $this->productApproval->ApprovalActionStatusTransitionBySelf("change");

            $approvalCrudStatus = $this->productApproval->getApprovalCrudStatusId("insert");

            $updatedPromotionColumns = array('merchant_id','location_id','image_url','image_url_compact','offer_details','offer_terms','offer_featured','offer_hottest','offer_begin','offer_expire','offer_url','status');

            $this->addForApprove($hase_promotion->promotion_id,$updatedPromotionColumns,$this->request_table_live,$this->request_table_stage,$approvalStatus,$approvalCrudStatus,$merchantID,$request->location_id);


            $message = "<a href='".URL::to($staffUrl)."'>".$this->staffName."</a> <strong> ".$action." </strong> new promotion <strong> $hase_promotion->offer_details </strong>";
            PermissionTrait::addActivityLog($action,$message);
        }
        

        Session::flash('type', 'success'); 
        Session::flash('msg', 'Promotion Successfully Inserted');

        if($this->roleId === 1){
            if ($request->submitBtn === "Save") {
                return redirect('hase_promotion/'. $hase_promotion->promotion_id . '/edit');
            }else{
                return redirect('hase_promotion');
            }
        }else{
            return redirect('hase_promotion');
        }

        /*if ($request->submitBtn === "Save") {
           return redirect('hase_promotion/'. $hase_promotion->promotion_id . '/edit');
        }else{
           return redirect('hase_promotion');
        }*/
    }

    /**
     * Show the form for editing the specified resource.
     * @param    \Illuminate\Http\Request  $request
     * @param    int  $id
     * @return  \Illuminate\Http\Response
     */
    public function edit($id,Request $request)
    {
        if($this->permissionDetails('Hase_promotion','manage')){
            $title = 'Edit - hase_promotion';
            $roleId = $this->roleId;
            $merchantId =$this->merchantId;
            
            if($this->merchantId === 0){
                $labels = array("Featured Offer");

                $hase_promotion = Promotion::
                    distinct()                
                    ->select('promotions.*','identity_merchant.identity_name as merchant_name','location_city.city_name as location_name','location_city.city_id')
                    ->leftjoin('location_list','location_list.postal_id','=','promotions.location_id') 
                    ->leftjoin('location_city','location_list.location_city_id','=','location_city.city_id')
                    ->leftjoin('merchant','merchant.merchant_id','=', 'promotions.merchant_id')
                    ->leftjoin('identity_merchant','identity_merchant.identity_id','=','merchant.identity_id')
                    ->where('promotions.promotion_id',$id)
                    ->where('location_list.identity_table_id',self::MERCHANT_TABLE_IDENTITY_TYPE)
                    ->get()->first();
            }else{
                $hase_promotion = Promotion::
                    distinct()                
                    ->select('promotions.*','identity_merchant.identity_name as merchant_name','location_city.city_name as location_name','location_city.city_id')
                    ->leftjoin('location_list','location_list.postal_id','=','promotions.location_id') 
                    ->leftjoin('location_city','location_list.location_city_id','=','location_city.city_id')
                    ->leftjoin('merchant','merchant.merchant_id','=', 'promotions.merchant_id')
                    ->leftjoin('identity_merchant','identity_merchant.identity_id','=','merchant.identity_id')
                    ->where('promotions.promotion_id',$id)
                    ->where('location_list.identity_table_id',self::MERCHANT_TABLE_IDENTITY_TYPE)
                    ->get()->first();


                $merchant_data = Merchant::
                            select('merchant.merchant_id','identity_merchant.identity_name as merchant_name')
                        ->leftjoin('identity_merchant','identity_merchant.identity_id','=','merchant.identity_id') 
                        ->where('merchant.merchant_id',$this->merchantId)
                        ->get()->first();

                if($merchant_data->merchant_type === 8)
                {
                    $labels = array("Dining Experience");
                } else {
                    $labels = array("Online Shopping");
                }
            }
            if($this->roleId < 5)
            {
                $merchant_cities = City::distinct()
                        ->select('location_city.city_id','location_city.city_name as city_name')
                        ->leftjoin('location_list','location_city.city_id','=','location_list.location_city_id')
                        ->leftjoin('merchant','merchant.identity_id','=','location_list.identity_id')
                        ->where('location_list.identity_table_id','=',self::MERCHANT_TABLE_IDENTITY_TYPE)
                        ->where('merchant.merchant_id','=',$hase_promotion->merchant_id)
                        ->get();
                $merchant_city_postals = Postal::distinct()
                        ->select('location_list.postal_id as location_id',DB::raw('(CASE WHEN (postal.postal_premise != "" and postal.postal_subpremise != "") THEN CONCAT(postal.postal_premise,", ",SUBSTR(postal.postal_subpremise,1,10))
                              WHEN (postal.postal_premise != "") THEN postal.postal_premise                              
                              WHEN (postal.postal_subpremise !="") THEN SUBSTR(postal.postal_subpremise,1,10)
                              ELSE CONCAT(postal.postal_street_number," ",postal.postal_route)   
                        END) as location_name'))
                        ->join('location_list','location_list.postal_id','=','postal.postal_id')
                        ->join('merchant','merchant.identity_id','=','location_list.identity_id')
                        ->where('merchant.merchant_id','=',$hase_promotion->merchant_id)
                        ->where('location_list.location_city_id','=',$hase_promotion->city_id)
                        ->where('location_list.identity_table_id','=',self::MERCHANT_TABLE_IDENTITY_TYPE)->get();
                
            } else {
                $merchant_cities = City::distinct()
                        ->select('location_city.city_id','location_city.city_name as city_name')
                        ->leftjoin('location_list','location_city.city_id','=','location_list.location_city_id')
                        ->leftjoin('merchant','merchant.identity_id','=','location_list.identity_id')
                        ->where('location_list.identity_table_id','=',self::MERCHANT_TABLE_IDENTITY_TYPE)
                        ->where('merchant.merchant_id','=',$this->merchantId)
                        ->where('location_list.postal_id','=',$this->locationId)
                        ->get();

                $merchant_city_postals = Postal::distinct()
                        ->select('location_list.postal_id as location_id',DB::raw('(CASE WHEN (postal.postal_premise != "" and postal.postal_subpremise != "") THEN CONCAT(postal.postal_premise,", ",SUBSTR(postal.postal_subpremise,1,10))
                              WHEN (postal.postal_premise != "") THEN postal.postal_premise                              
                              WHEN (postal.postal_subpremise !="") THEN SUBSTR(postal.postal_subpremise,1,10)
                              ELSE CONCAT(postal.postal_street_number," ",postal.postal_route)   
                        END) as location_name'))
                        ->join('location_list','location_list.postal_id','=','postal.postal_id')
                        ->join('merchant','merchant.identity_id','=','location_list.identity_id')
                        ->where('merchant.merchant_id','=',$hase_promotion->merchant_id)
                        ->where('location_list.postal_id','=',$hase_promotion->location_id)
                        ->where('location_list.identity_table_id','=',self::MERCHANT_TABLE_IDENTITY_TYPE)->get();
            }
            if(!$hase_promotion) {

                return Redirect('hase_promotion')->with('message', 'You are not authorized to use this functionality!');
            }
            $hase_promotion->offer_begin =  date('m/d/Y',strtotime($hase_promotion->offer_begin));
            $hase_promotion->offer_expire =  date('m/d/Y',strtotime($hase_promotion->offer_expire));
            return view('hase_promotion.edit',compact('title','hase_promotion','merchantId','merchant_cities','labels','merchant_city_postals','roleId'));
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
        $hase_promotion = Promotion::findOrfail($id);
        
        if (isset($request->status)) {

            $request->status = 1;
        } else {
            $request->status = 0;
        }

        if($this->merchantId !== 0){
            $merchantID = $this->merchantId;
        }else{
            $merchantID = $request->merchant_id;
        }
        $locationID = $request->location_id;
        
        $hase_promotion->location_id = $locationID;
        
        $hase_promotion->offer_details = $request->offer_details;

        $hase_promotion->offer_url = $request->offer_url;

        
        $hase_promotion->offer_terms = $request->offer_terms;

        $hase_promotion->status = $request->status;

        $hase_promotion->offer_featured = (isset($request->featured_status)) ?
                                            $request->offer_featured : 0;

        
        $hase_promotion->offer_hottest = (isset($request->hottest_status)) ?
                                            $request->offer_hottest : 0;

        if(isset($request->year_round))
        {   
            $hase_promotion->offer_begin = str_replace("-","",date('Y-m-d', strtotime('12/31')));
            $hase_promotion->offer_expire = str_replace("-","",date('Y-m-d', strtotime('12/31')));
        }else{

            $hase_promotion->offer_begin = (!empty($request->offer_begin))?str_replace("-","",date('Y-m-d',strtotime($request->offer_begin))) : 0;

            $hase_promotion->offer_expire = (!empty($request->offer_expire))?str_replace("-","",date('Y-m-d',strtotime($request->offer_expire))) : 0;
        }

        $hase_merchant = Merchant::
                            select('merchant.merchant_id','identity_merchant.identity_name as merchant_name')
                        ->leftjoin('identity_merchant','identity_merchant.identity_id','=','merchant.identity_id')
                        ->where('merchant.merchant_id',$merchantID)
                        ->get()->first();

        $hase_location = City::
                        select('city_id','city_name as location_name')
                        ->where('city_id',$locationID)
                        ->get()->first();

        $merchantDirName = md5($hase_merchant->merchant_name);
        $locationDirName = md5($hase_merchant->merchant_name.$hase_location->location_name);

        if($request->live_image_url)
        {
            $hase_promotion->image_url = $request->live_image_url;
        } else {
            if($request->file('image_url')){

                $publicDirPath = public_path(env('image_dir_path'));
                
                if($this->roleId === 1){
                    $imageDirPath = "merchant/$merchantDirName/location/$locationDirName/";
                }else{
                    $imageDirPath = "merchant/$merchantDirName/location/$locationDirName/";
                }

                $absoluteImageDirPath = $publicDirPath.$imageDirPath;

                if(!file_exists($absoluteImageDirPath)){
                    mkdir($absoluteImageDirPath,0777,true);
                }

                if($this->roleId === 1)
                {
                    $imagePath = $publicDirPath.$hase_promotion->image_url;
                    if (is_file($imagePath)) {
                        unlink($imagePath);
                    }
                }

                $imageName = $request->file('image_url')->getClientOriginalName();
                $imageArray = explode('.', $imageName);
                $hashImageName = md5($hase_merchant->merchant_name.$hase_location->location_name.$hase_promotion->offer_details.$imageName).".".$imageArray[1];
                $request->file('image_url')->move($absoluteImageDirPath,$hashImageName);
                $hase_promotion->image_url = "$imageDirPath$hashImageName";
            }
        }

        if($request->live_image_compact_url)
        {
            $hase_promotion->image_url_compact = $request->live_image_compact_url;
        } else {
            if($request->file('image_url_compact')){

                $publicDirPath = public_path(env('image_dir_path'));
                
                if($this->roleId === 1){
                    $imageDirPath = "merchant/$merchantDirName/location/$locationDirName/";
                }else{
                    $imageDirPath = "merchant/$merchantDirName/location/$locationDirName/";
                }

                $absoluteImageDirPath = $publicDirPath.$imageDirPath;

                if(!file_exists($absoluteImageDirPath)){
                    mkdir($absoluteImageDirPath,0777,true);
                }

                if($this->roleId === 1)
                {
                    $imagePath = $publicDirPath.$hase_promotion->image_url_compact;
                    if (is_file($imagePath)) {
                        unlink($imagePath);
                    }
                }

                $imageName = $request->file('image_url_compact')->getClientOriginalName();
                $imageArray = explode('.', $imageName);
                $hashImageName = md5($hase_merchant->merchant_name.$hase_location->location_name.$hase_promotion->offer_details.$imageName).".".$imageArray[1];
                $request->file('image_url_compact')->move($absoluteImageDirPath,$hashImageName);
                $hase_promotion->image_url_compact = "$imageDirPath$hashImageName";
            }
        }


        $staffUrl = "/hase_staff/".session('staffId')."/edit";
        $action = "updated";
        if($this->roleId === 1)
        {
            $dirty = $hase_promotion->getDirty();
            if($dirty)
            {
                $updatedPromotionColumns = array();
                foreach ($dirty as $field => $newdata)
                {
                    $olddata = $hase_promotion->getOriginal($field);
                    if ($olddata !== $newdata)
                    {
                        $updatedPromotionColumns[] =  $field;
                    }
                }
                if(count($updatedPromotionColumns));
                {
                    $approvalStatus = $this->productApproval->ApprovalActionStatusTransitionBySelf("change");

                    $approvalCrudStatus = $this->productApproval->getApprovalCrudStatusId("modify");

                    $this->updateAdminForApprove($id,$updatedPromotionColumns,$this->request_table_live,$approvalStatus,$approvalCrudStatus,$merchantID,$request->location_id);
                }

                $hase_promotion->save();
                $promotionUrl = "/hase_promotion/".$hase_promotion->promotion_id."/edit";
                $message = "<a href='".URL::to($staffUrl)."'>".$this->staffName."</a> <strong>".$action."</strong> promotion <a href='".URL::to($promotionUrl)."'> <strong>".$hase_promotion->offer_details."</strong></a>";
                PermissionTrait::addActivityLog($action,$message);
            }
        } else {
            $hase_promotion_stage = new Promotion_stage();
            $dirty = $hase_promotion->getDirty();
            if($dirty)
            {
                $updatedPromotionColumns = array();
                foreach ($dirty as $field => $newdata)
                {
                    $updatedFieldsExist = DB::table('approval_key')->where('field_show', 'LIKE', '%'.$field.'%')->where('key_table','=','promotions')->get();
                    $olddata = $hase_promotion->getOriginal($field);
                    if ($olddata !== $newdata)
                    {
                        if (!$updatedFieldsExist->isEmpty()) 
                        {
                            $hase_promotion_stage->$field = $newdata;
                            $updatedPromotionColumns[] =  $field;
                        } else {
                            $noneExistColumns = Promotion::findOrFail($id);
                            $noneExistColumns->$field = $newdata;
                            $noneExistColumns->save();
                        }
                    }
                }
                if(count($updatedPromotionColumns));
                {
                    $hase_promotion_stage->location_id = $request->location_id;
                    $hase_promotion_stage->staff_id = $this->staffId;
                    $hase_promotion_stage->merchant_id = $merchantID;
                    $hase_promotion_stage->save();
                    
                    $approvalStatus = $this->productApproval->ApprovalActionStatusTransitionBySelf("change");

                    $approvalCrudStatus = $this->productApproval->getApprovalCrudStatusId("modify");

                    $this->updateForApprove($id,$hase_promotion_stage->promotion_id,$updatedPromotionColumns,$this->request_table_live,$this->request_table_stage,$approvalStatus,$approvalCrudStatus,$merchantID,$request->location_id);
                }
                $message = "<a href='".URL::to($staffUrl)."'>".$this->staffName."</a> <strong> ".$action." </strong> promotion <strong> $hase_promotion->offer_details </strong>";
                
                PermissionTrait::addActivityLog($action,$message);
            }
        }
        Session::flash('type', 'success'); 
        Session::flash('msg', 'Promotion Successfully Updated');

        if($this->roleId === 1){
            if ($request->submitBtn === "Save") {
                return redirect('hase_promotion/'. $hase_promotion->promotion_id . '/edit');
            }else{
                return redirect('hase_promotion');
            }
        }else{
            return redirect('hase_promotion');
        }


        /*if ($request->submitBtn === "Save") {
           return redirect('hase_promotion/'. $hase_promotion->promotion_id . '/edit');
        } else {
           return redirect('hase_promotion');
        }*/
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param    int $id
     * @return  \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if($this->permissionDetails('Hase_promotion','delete')){
            $hase_promotion = Promotion::findOrfail($id);
            if ($this->roleId === 1) {                
                $hase_promotion->delete();
            }else{
               $hase_promotion_stage = new Promotion_stage();

               $hase_promotion_stage->staff_id = $this->staffId;
               $hase_promotion_stage->id = $id;
               $hase_promotion_stage->location_id = $hase_promotion->location_id;
               $hase_promotion_stage->save();

               $updatePromotionColumn = array('location_id','id');

               $approvalStatus = $this->productApproval->ApprovalActionStatusTransitionBySelf("change");

               $approvalCrudStatus = $this->productApproval->getApprovalCrudStatusId("delete");
               
               $this->updateForApprove($id,$hase_promotion_stage->id,$updatePromotionColumn,$this->request_table_live,$this->request_table_stage,$approvalStatus,$approvalCrudStatus,$hase_promotion->merchant_id,$hase_promotion->location_id);
            }
            
            Session::flash('type', 'success'); 
            Session::flash('msg', 'Promotion Successfully Deleted');
            return redirect('hase_promotion');
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
