<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\App;
use Illuminate\Http\Request;
use App\Http\Controllers\PermissionsController;
use App\Helpers\ConnectionManager;
use App\Merchant_retail_category_type;
use App\Asset_category_list;
use App\Asset;
use Amranidev\Ajaxis\Ajaxis;
use App\Http\Controllers\ProductApprovalController;
use App\Http\Traits\PermissionTrait;

use URL;
use Session;
use DB;
use Redirect;

/**
 * Class Asset_category_listController.
 *
 * @author  The scaffold-interface created at 2018-02-25 05:27:58pm
 * @link  https://github.com/amranidev/scaffold-interface
 */
class Asset_category_listController extends PermissionsController
{
    use PermissionTrait;

    public function __construct()
    {
        parent::__construct();

        $connectionStatus = ConnectionManager::setDbConfig('Asset_category_list');
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
        if($this->permissionDetails('Asset_category_list','access')){
                       
            $permissions = $this->getPermission("Asset_category_list");
            
            if($this->merchantId == 0){

                $asset_category_lists = Asset_category_list::
                    select('identity_asset.identity_code as asset_symbol','identity_asset.identity_name as asset_name','identity_category_type.identity_name as category_type_name','asset_category_list.*')
                    ->join('asset','asset.asset_id','=','asset_category_list.asset_id')
                    ->join('identity_asset','identity_asset.identity_id','=','asset.identity_id')
                    ->join('merchant_retail_category_type','merchant_retail_category_type.category_type_id','=','asset_category_list.category_type_id')
                    ->join('identity as identity_category_type','identity_category_type.identity_id','=','merchant_retail_category_type.identity_id')
                    ->get();

                return view('asset_category_list.index',compact('asset_category_lists','permissions'));
            }
        }else{
            return Redirect::back()->with('message', 'You are not authorized to use this functionality!');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return  \Illuminate\Http\Response
     */
    public function create()
    {

        if($this->permissionDetails('Asset_category_list','add')){

            $assets = Asset::select('identity_asset.identity_name as asset_name','asset.asset_id')
                                ->join('identity_asset','identity_asset.identity_id','=','asset.identity_id')
                                ->get();

            $category_types = Merchant_retail_category_type::
                    select('category_type_id','category_type_identity.identity_name as category_name')
                    ->join('identity as category_type_identity','category_type_identity.identity_id','=','merchant_retail_category_type.identity_id')
                    ->get();   

            return view('asset_category_list.create',compact('assets','category_types'));
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
        
        if(isset($request->category_type_id) && count($request->category_type_id) != 0){

            Asset_category_list::
                where('asset_id',$request->asset_id)
                ->whereNotIn('category_type_id',$request->category_type_id)
                ->delete();


            foreach ($request->category_type as $value) {

                $category_exist = Asset_category_list::
                          where('asset_id',$request->asset_id)
                        ->where('category_type_id',$value['category_type_id'])
                        ->get()->first();

                if(count($category_exist) == 0){
                    $asset_category_list = new Asset_category_list();
                }else{
                    $asset_category_list = Asset_category_list::findOrfail($category_exist->list_id);
                }

                $asset_category_list->asset_id = $request->asset_id;
                $asset_category_list->category_type_id = $value['category_type_id'];
                $asset_category_list->category_list_priority = $value['category_list_priority'];
                $asset_category_list->category_list_status = isset($value['category_list_status'])?1:0;
                
                $asset_category_list->save();

            }
        }else{
            Asset_category_list::
                where('asset_id',$request->asset_id)
                ->delete();
        }

        Session::flash('type', 'success'); 
        Session::flash('msg', 'Asset Category List Successfully Created');
        if ($request->submitBtn === "Save") {
           return redirect('asset_category_list/'. $asset_category_list->list_id . '/edit');
        }else{
           return redirect('asset_category_list');
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
                
        if($this->permissionDetails('Asset_category_list','manage')){

            $asset_category_list = Asset_category_list::findOrfail($id);

            $assets = Asset::select('identity_asset.identity_name as asset_name','asset.asset_id')
                                ->join('identity_asset','identity_asset.identity_id','=','asset.identity_id')
                                ->get();

            $category_types = Merchant_retail_category_type::
                    select('category_type_id','category_type_identity.identity_name as category_name')
                    ->join('identity as category_type_identity','category_type_identity.identity_id','=','merchant_retail_category_type.identity_id')
                    ->get(); 
            return view('asset_category_list.edit',compact('asset_category_list','assets','category_types'));

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
        $asset_category_list = Asset_category_list::findOrfail($id);
    	
        
        $asset_category_list->asset_id = $request->asset_id;
        $asset_category_list->category_type_id = $request->category_type_id;
        $asset_category_list->category_list_priority = $request->category_list_priority;
        $asset_category_list->category_list_status = $request->category_list_status;
        
        $asset_category_list->save();

        Session::flash('type', 'success'); 
        Session::flash('msg', 'Asset Category List Successfully Updated');
        if ($request->submitBtn === "Save") {
           return redirect('asset_category_list/'. $asset_category_list->list_id . '/edit');
        }else{
           return redirect('asset_category_list');
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
     	
        if($this->permissionDetails('Asset_category_list','delete')){
            $asset_category_list = Asset_category_list::findOrfail($id);
            $asset_category_list->delete();
            Session::flash('type', 'success'); 
            Session::flash('msg', 'Asset Category List Successfully Deleted');
            return redirect('asset_category_list');
        }else{
            return Redirect::back()->with('message', 'You are not authorized to use this functionality!');
        }
    }

    public function getCategoryTypes(Request $request) {
        
        $categoryTypes = Asset_category_list::
                        distinct('asset_category_list.asset_id','asset_category_list.category_type_id')
                        ->select('asset_category_list.*','identity_category_type.identity_name as category_type_name')
                        ->join('merchant_retail_category_type','merchant_retail_category_type.category_type_id','=','asset_category_list.category_type_id')
                        ->join('identity as identity_category_type','identity_category_type.identity_id','=','merchant_retail_category_type.identity_id')
                        ->where('asset_category_list.asset_id',$request->asset_id)
                        ->get();
                        
        echo json_encode($categoryTypes);
    }
}
