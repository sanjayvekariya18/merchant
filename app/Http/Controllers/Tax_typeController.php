<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\App;
use Illuminate\Http\Request;
use App\Http\Controllers\PermissionsController;
use App\Helpers\ConnectionManager;
use App\Tax_type;
use Amranidev\Ajaxis\Ajaxis;
use App\Http\Controllers\ProductApprovalController;
use App\Http\Traits\PermissionTrait;
use App\Tax_type_category;
use App\Asset_rate;
use App\Merchant;
use App\Payee;

use URL;
use Auth;
use Session;
use DB;
use Redirect;

/**
 * Class Tax_typeController.
 *
 * @author  The scaffold-interface created at 2018-03-24 11:58:26am
 * @link  https://github.com/amranidev/scaffold-interface
 */
class Tax_typeController extends PermissionsController
{
    use PermissionTrait;
    
    public function __construct()
    {
        parent::__construct();

        $connectionStatus = ConnectionManager::setDbConfig('Tax_type');

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
        if($this->permissionDetails('Tax_type','access')) {
            $permissions = $this->getPermission("Tax_type");
            $tax_types = Tax_type::all();
            return view('tax_type.index',compact('tax_types','permissions'));
        } else {
            return Redirect::back()->with('message', 'You are not authorized to use this functionality!');
        }
    }

    public function getPersons()
    {
        $where = array();
        if($this->merchantId == 0){
            $where[] = array(
                'key' => "merchant.merchant_id",
                'operator' => '>',
                'val' => $this->merchantId
            );
            $where[] = array(
                'key' => "merchant_account_list.staff_account_id",
                'operator' => '!=',
                'val' => 0
            );
        } else {
            if($this->roleId == 4){
                $where[] = array(
                    'key' => "merchant.merchant_id",
                    'operator' => '=',
                    'val' => $this->merchantId
                );    
                $where[] = array(
                    'key' => "merchant_account_list.staff_account_id",
                    'operator' => '!=',
                    'val' => 0
                );
            } else {
                $where[] = array(
                    'key' => "merchant.merchant_id",
                    'operator' => '=',
                    'val' => $this->merchantId
                );
                $where[] = array(
                    'key' => "merchant_account_list.staff_account_id",
                    'operator' => '!=',
                    'val' => 0
                );
            }
        }

        $merchants = Merchant::
            distinct('merchant.merchant_id')
            ->select(
                DB::raw("CONCAT('merchant_',merchant.merchant_id) AS person_id"),
                'identity_merchant.identity_name as person_name',
                'identity_merchant.identity_code as person_code'
            )
            ->join('identity_merchant','identity_merchant.identity_id','merchant.identity_id')
            ->join('merchant_account_list','merchant_account_list.merchant_id','=','merchant.merchant_id')
            ->where(function($q) use ($where){
                foreach($where as $key => $value){
                    $q->where($value['key'], $value['operator'], $value['val']);
                }
            })->get()->toArray();

        $payees = Payee::distinct('payee.payee_id')
            ->select(
                DB::raw("CONCAT('payee_',payee.payee_id) AS person_id"),
                'identity_payee.identity_name as person_name',
                'identity_payee.identity_code as person_code'
            )
            ->join('identity_payee','identity_payee.identity_id','payee.identity_id')
            ->get()->toArray();
        $people = array_merge($merchants,$payees);
        return json_encode($people);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return  \Illuminate\Http\Response
     */
    public function create()
    {
        if($this->permissionDetails('Tax_type','add')) {
            $title = 'Create - tax_type';
            return view('tax_type.create');
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
        $tax_type = new Tax_type();
            
        $tax_type->type_code = $request->type_code;
    
        $tax_type->type_name = $request->type_name;
  
        $tax_type->save();

        Session::flash('type', 'success');
        Session::flash('msg', 'Tax Type Successfully Created');
        if ($request->submitBtn == "Save") {
           return redirect('tax_type/'. $tax_type->type_id . '/edit');
        } else {
           return redirect('tax_type');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param    \Illuminate\Http\Request  $request
     * @param    int  $id
     * @return  \Illuminate\Http\Response
     */
    public function show($id,Request $request)
    {
        $title = 'Show - tax_type';

        if($request->ajax())
        {
            return URL::to('tax_type/'.$id);
        }

        $tax_type = Tax_type::findOrfail($id);
        return view('tax_type.show',compact('title','tax_type'));
    }

    /**
     * Show the form for editing the specified resource.
     * @param    \Illuminate\Http\Request  $request
     * @param    int  $id
     * @return  \Illuminate\Http\Response
     */
    public function edit($id,Request $request)
    {      
        if($this->permissionDetails('Tax_type','manage')) {
            $tax_type = Tax_type::findOrfail($id);
            return view('tax_type.edit',compact('tax_type'));
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
        $tax_type = Tax_type::findOrfail($id);
    	        
        $tax_type->type_code = $request->type_code;
        
        $tax_type->type_name = $request->type_name;
        
        $tax_type->save();

        Session::flash('type', 'success');
        Session::flash('msg', 'Tax Type Successfully Updated');

        if ($request->submitBtn == "Save") {
           return redirect('tax_type/'. $tax_type->type_id . '/edit');
        } else {
           return redirect('tax_type');
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
        if($this->permissionDetails('Tax_type','delete')) {
         	$tax_type = Tax_type::findOrfail($id);
         	$tax_type->delete();
            Session::flash('type', 'success');
            Session::flash('msg', 'Tax Type Successfully Deleted');
            return redirect('payee');
        } else {
            return Redirect::back()->with('message', 'You are not authorized to use this functionality!');
        }
    }

    public function getAllTaxType(Request $request) {
        $taxTypes = Tax_type::select('tax_type.*',
            'tax_type_category.category_name',
            'tax_type_category.percentage',
            'identity_asset.identity_code as asset_code',
            'identity_merchant.identity_name as merchant_name',
            'identity_payee.identity_name as payee_name'
            )
            ->join('tax_type_category','tax_type_category.category_id','tax_type.category_id')
            ->join('asset','asset.asset_id','tax_type.asset_id')
            ->join('identity_asset','identity_asset.identity_id','asset.identity_id')
            ->leftjoin('merchant','merchant.merchant_id','tax_type_category.merchant_id')
            ->leftjoin('identity_merchant','identity_merchant.identity_id','merchant.identity_id')
            ->leftjoin('payee','payee.payee_id','tax_type_category.payee_id')
            ->leftjoin('identity_payee','identity_payee.identity_id','payee.identity_id')
            ->offset($request->skip)
            ->limit($request->take);

        if (isset($request->searchtext) && trim($request->searchtext) != "") {
            $taxTypes->where(function($q) use ($request) {
             $q->where('tax_type_category.category_name', 'LIKE', '%' . $request->searchtext . '%')
               ->orWhere('tax_type.type_name', 'LIKE', '%' . $request->searchtext . '%');
             });

            $taxTypes_data['taxTypes'] = $taxTypes->get();
            $taxTypes_data['total'] = $taxTypes->get()->count();   

        }else{

            $taxTypes_data['taxTypes'] = $taxTypes->get();
            $taxTypes_data['total'] = Tax_type::select('tax_type.*',
            'tax_type_category.category_name',
            'tax_type_category.percentage',
            'identity_asset.identity_code as asset_code',
            'identity_merchant.identity_name as merchant_name',
            'identity_payee.identity_name as payee_name'
            )
            ->join('tax_type_category','tax_type_category.category_id','tax_type.category_id')
            ->join('asset','asset.asset_id','tax_type.asset_id')
            ->join('identity_asset','identity_asset.identity_id','asset.identity_id')
            ->leftjoin('merchant','merchant.merchant_id','tax_type_category.merchant_id')
            ->leftjoin('identity_merchant','identity_merchant.identity_id','merchant.identity_id')
            ->leftjoin('payee','payee.payee_id','tax_type_category.payee_id')
            ->leftjoin('identity_payee','identity_payee.identity_id','payee.identity_id')
            ->count();
        }     

        return json_encode($taxTypes_data);
    }

    public function getAllTaxCategory(Request $request) {
        $taxCategoryTypes = Tax_type_category::select('tax_type_category.*',
            'identity_merchant.identity_name as merchant_name',
            'identity_payee.identity_name as payee_name')
            ->leftjoin('merchant','merchant.merchant_id','tax_type_category.merchant_id')
            ->leftjoin('identity_merchant','identity_merchant.identity_id','merchant.identity_id')
            ->leftjoin('payee','payee.payee_id','tax_type_category.payee_id')
            ->leftjoin('identity_payee','identity_payee.identity_id','payee.identity_id')
            ->offset($request->skip)
            ->limit($request->take);

        if (isset($request->searchtext) && trim($request->searchtext) != "") {
            $taxCategoryTypes->where('tax_type_category.category_name', 'LIKE', '%' . $request->searchtext . '%');

            $taxCategoryTypesData['taxCategoryTypes'] = $taxCategoryTypes->get();
            $taxCategoryTypesData['total'] = $taxCategoryTypes->get()->count();   

        }else{

            $taxCategoryTypesData['taxCategoryTypes'] = $taxCategoryTypes->get();
            $taxCategoryTypesData['total'] = Tax_type_category::select('tax_type_category.*',
            'identity_merchant.identity_name as merchant_name',
            'identity_payee.identity_name as payee_name')
            ->leftjoin('merchant','merchant.merchant_id','tax_type_category.merchant_id')
            ->leftjoin('identity_merchant','identity_merchant.identity_id','merchant.identity_id')
            ->leftjoin('payee','payee.payee_id','tax_type_category.payee_id')
            ->leftjoin('identity_payee','identity_payee.identity_id','payee.identity_id')
            ->get()
            ->count();
        } 

        return json_encode($taxCategoryTypesData);
    }

    public function getTaxPercent(Request $request) {
        $categoryId = $request->category_id;
        $percentage = Tax_type_category::select('percentage','merchant_id','payee_id')
            ->where('tax_type_category.category_id',$categoryId)->get();
        echo json_encode($percentage);
    }

    public function getAssetSettlementPrice(Request $request) {
        $assetRates = Asset_rate::select('asset_last_price')
            ->where('asset_from_id',$request->asset_id)
            ->where('asset_into_id',$request->settlement_id)
            ->get()->first();
        return $assetRates->asset_last_price;
    }

    public function createTaxType(Request $request) {
        $taxTypes = new Tax_type();
        $taxTypes->type_code = str_replace(" ","-",strtolower($request->type_name));
        $taxTypes->type_name = $request->type_name;
        $taxTypes->category_id = $request->category_id;
        $taxTypes->asset_id = ($request->asset_id)?$request->asset_id:0;
        $taxTypes->save();

        $taxTypeCategory = Tax_type_category::findOrfail($request->category_id);
        $merchantPayee = explode("_", $request->merchant_id);
        if($merchantPayee[0] == 'merchant') {
            $taxTypeCategory->merchant_id = $merchantPayee[1];
        } else {
            $taxTypeCategory->merchant_id = 0;
        }
        if($merchantPayee[0] == 'payee') {
            $taxTypeCategory->payee_id = $merchantPayee[1];
        } else {
            $taxTypeCategory->payee_id = 0;
        }
        $taxTypeCategory->save();
    }

    public function updateTaxType(Request $request) {
        $taxTypeId = $request->type_id;
        $categoryId = $request->category_id;
        $key = $request->key;
        $value = $request->value;
        if($key == "type_name") {
            $taxType = Tax_type::findOrfail($taxTypeId);
            $taxType->type_name = $value;
            $taxType->type_code = str_replace(" ","-",strtolower($value));
            $taxType->save();
        }
        else if($key == "category_name") {
            $taxTypeCategory = Tax_type_category::findOrfail($categoryId);
            $taxTypeCategory->category_name = $value;
            $taxTypeCategory->save();
        }
        else if($key == "percentage") {
            $taxTypeCategory = Tax_type_category::findOrfail($categoryId);
            $taxTypeCategory->percentage = $value;
            $taxTypeCategory->save();
        }
    }

    public function getTaxTypeAssets($flag)
    {
        return PermissionTrait::getAssets($flag);
    }

    public function getTaxCategory()
    {
        $taxTypeCategory = Tax_type_category::all();
        return json_encode($taxTypeCategory);
    }

    public function createTaxTypeCategory(Request $request) {
        $taxTypeCategory = new Tax_type_category();
        $taxTypeCategory->category_code = str_replace(" ","-",strtolower($request->category_name));
        $taxTypeCategory->category_name = $request->category_name;

        $categoryMerchantPayee = explode("_", $request->category_merchant_id);
        if($categoryMerchantPayee[0] == 'merchant') {
            $taxTypeCategory->merchant_id = $categoryMerchantPayee[1];
        }
        if($categoryMerchantPayee[0] == 'payee') {
            $taxTypeCategory->payee_id = $categoryMerchantPayee[1];
        }
        $taxTypeCategory->percentage = $request->category_percentage;
        $taxTypeCategory->save();
    }
}
