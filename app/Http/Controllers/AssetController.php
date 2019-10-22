<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\App;
use Illuminate\Http\Request;
use App\Http\Controllers\PermissionsController;
use App\Helpers\ConnectionManager;
use App\Asset;
use App\Asset_type;
use App\Identity_asset;
use Amranidev\Ajaxis\Ajaxis;
use App\Http\Controllers\ProductApprovalController;
use App\Http\Traits\PermissionTrait;

use URL;
use Session;
use DB;
use Redirect;

const ASSET_IDENTITY_TYPE_ID=10;
const ASSET_IDENTITY_TABLE_ID=11;
const ASSET_TABLE_ID=12;


/**
 * Class AssetController.
 *
 * @author  The scaffold-interface created at 2018-02-06 01:54:39pm
 * @link  https://github.com/amranidev/scaffold-interface
 */
class AssetController extends PermissionsController
{
    use PermissionTrait;

    public function __construct()
    {
        parent::__construct();

        $connectionStatus = ConnectionManager::setDbConfig('Asset');

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
        
        if($this->permissionDetails('Asset','access')){
                       
            $permissions = $this->getPermission("Asset");
            
            if($this->merchantId == 0){

                $assets = Asset::
                    select('identity_asset.identity_code as asset_symbol','identity_asset.identity_name as asset_name','asset_type.asset_type_code','asset_type.asset_type_name','asset.*')
                    ->join('identity_asset','identity_asset.identity_id','=','asset.identity_id')
                    ->join('asset_type','asset_type.asset_type_id','=','asset.asset_type_id')
                    ->get();

                return view('asset.index',compact('assets','permissions'));
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
        if($this->permissionDetails('Asset','add')){
            $assetTypes = Asset_type::all();
            return view('asset.create',compact('assetTypes'));
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

        $asset_exist = Identity_asset::select('*')
                         ->where('identity_code',$request->asset_symbol)
                         ->get()->first();

        if(count($asset_exist) == 0){
            $asset = new Asset();
            $identity_asset = new Identity_asset();
            $message = "Asset Successfully Created";
        }else{

            $asset = Asset::select('*')
                        ->where('identity_id',$asset_exist->identity_id)
                        ->get()->first();
            $identity_asset = Identity_asset::findOrfail($asset->identity_id);
            $message = "Asset Successfully Updated";
        }
        
        $identity_asset->identity_code = $request->asset_symbol;
        $identity_asset->identity_name = $request->asset_name;
        $identity_asset->identity_table_id = ASSET_TABLE_ID;
        $identity_asset->identity_type_id = ASSET_IDENTITY_TYPE_ID;

        $identity_asset->save();
        $identityID = $identity_asset->identity_id;
        
        $asset->identity_id = $identityID;
        $asset->identity_table_id = ASSET_IDENTITY_TABLE_ID;
        $asset->asset_type_id = $request->asset_type_id;
        $asset->precision_enter = $request->precision_enter;
        $asset->precision_display = $request->precision_display;
        $asset->save();

        Session::flash('type', 'success'); 
        Session::flash('msg',$message);
        if ($request->submitBtn === "Save") {
           return redirect('asset/'. $asset->asset_id . '/edit');
        }else{
           return redirect('asset');
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
        
        if($this->permissionDetails('Asset','manage')){
            
            $assetTypes = Asset_type::all();
            $asset = Asset::
                    select('identity_asset.identity_code as asset_symbol','identity_asset.identity_name as asset_name','asset_type.asset_type_code','asset_type.asset_type_name','asset.*')
                    ->join('identity_asset','identity_asset.identity_id','=','asset.identity_id')
                    ->join('asset_type','asset_type.asset_type_id','=','asset.asset_type_id')
                    ->where('asset_id',$id)
                    ->get()->first();
        
            return view('asset.edit',compact('assetTypes','asset'));

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
        
        $asset_exist = Identity_asset::select('*')
                         ->where('identity_code',$request->asset_symbol)
                         ->get()->first();

        if(count($asset_exist) == 0){
            $asset = new Asset();
            $identity_asset = new Identity_asset();
            $message = "Asset Successfully Created";
        }else{

            $asset = Asset::select('*')
                        ->where('identity_id',$asset_exist->identity_id)
                        ->get()->first();
            $identity_asset = Identity_asset::findOrfail($asset->identity_id);
            $message = "Asset Successfully Updated";
        }
        
        $identity_asset->identity_code = $request->asset_symbol;
        $identity_asset->identity_name = $request->asset_name;
        $identity_asset->identity_table_id = ASSET_TABLE_ID;
        $identity_asset->identity_type_id = ASSET_IDENTITY_TYPE_ID;

        $identity_asset->save();
        $identityID = $identity_asset->identity_id;
        
        $asset->identity_id = $identityID;
        $asset->identity_table_id = ASSET_IDENTITY_TABLE_ID;
        $asset->asset_type_id = $request->asset_type_id;
        $asset->precision_enter = $request->precision_enter;
        $asset->precision_display = $request->precision_display;
        $asset->save();

        Session::flash('type', 'success');
        Session::flash('msg', $message);
        if ($request->submitBtn === "Save") {
           return redirect('asset/'. $asset->asset_id . '/edit');
        }else{
           return redirect('asset');
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
     	
        if($this->permissionDetails('Asset','delete')){
            $asset = Asset::findOrfail($id);
            $identity_asset = Identity_asset::findOrfail($asset->identity_id);

            $asset->delete();
            $identity_asset->delete();
            Session::flash('type', 'success');
            Session::flash('msg', 'Asset Successfully Deleted');
            return redirect('asset');
        }else{
            return Redirect::back()->with('message', 'You are not authorized to use this functionality!');
        }
    }

    public function getAllAssets(Request $request) {

        $assets = Asset::
            select('identity_asset.identity_code as asset_code','identity_asset.identity_name as asset_name','asset_type.asset_type_code','asset_type.asset_type_name','asset.*')
            ->join('identity_asset','identity_asset.identity_id','=','asset.identity_id')
            ->join('asset_type','asset_type.asset_type_id','=','asset.asset_type_id')
            ->where('asset.asset_id','!=',0)
            ->orderBy('asset.asset_id')
            ->offset($request->skip)->limit($request->take);

        if (isset($request->searchtext) && trim($request->searchtext) != "") {
            $assets->where(function($q) use ($request) {
             $q->where('identity_asset.identity_code', 'LIKE', '%' . $request->searchtext . '%')
               ->orWhere('identity_asset.identity_name', 'LIKE', '%' . $request->searchtext . '%');
             });

            $assets_data['assets'] = $assets->get();
            $assets_data['total'] = $assets->get()->count();   

        }else{

            $assets_data['assets'] = $assets->get();
            $assets_data['total'] = Asset::
            select('identity_asset.identity_code as asset_code','identity_asset.identity_name as asset_name','asset_type.asset_type_code','asset_type.asset_type_name','asset.*')
            ->join('identity_asset','identity_asset.identity_id','=','asset.identity_id')
            ->join('asset_type','asset_type.asset_type_id','=','asset.asset_type_id')
            ->where('asset.asset_id','!=',0)->count();
        }   

        return json_encode($assets_data);
    }

    public function updateAsset(Request $request)
    {
        $assetId = $request->asset_id;
        $identityId = $request->identity_id;
        $key = $request->key;
        $value = $request->value;
        if($key === "identity_code") {
            $identityAsset = Identity_asset::findOrfail($identityId);
            $identityAsset->identity_code = $value;
            $identityAsset->save();
        }
        else if($key === "identity_name") {
            $identityAsset = Identity_asset::findOrfail($identityId);
            $identityAsset->identity_name = $value;
            $identityAsset->save();
        }
        else if($key === "asset_type_id") {
            $assetData = Asset::findOrfail($assetId);
            $assetData->asset_type_id = $value;
            $assetData->save();
        }
        else if($key === "precision_enter") {
            $assetData = Asset::findOrfail($assetId);
            $assetData->precision_enter = $value;
            $assetData->save();
        }
        else if($key === "precision_display") {
            $assetData = Asset::findOrfail($assetId);
            $assetData->precision_display = $value;
            $assetData->save();
        }
    }
}
