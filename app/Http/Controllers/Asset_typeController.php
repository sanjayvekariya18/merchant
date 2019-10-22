<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\App;
use Illuminate\Http\Request;
use App\Http\Controllers\PermissionsController;
use App\Helpers\ConnectionManager;
use App\Asset_type;
use Amranidev\Ajaxis\Ajaxis;
use App\Http\Controllers\ProductApprovalController;
use App\Http\Traits\PermissionTrait;

use URL;
use Session;
use DB;
use Redirect;

/**
 * Class Asset_typeController.
 *
 * @author  The scaffold-interface created at 2018-02-08 08:05:25pm
 * @link  https://github.com/amranidev/scaffold-interface
 */
class Asset_typeController extends PermissionsController
{
    
    use PermissionTrait;

    public function __construct()
    {
        parent::__construct();

        $connectionStatus = ConnectionManager::setDbConfig('Asset_type');

        if ($connectionStatus['type'] === "error") {

            Session::flash('type', $connectionStatus['type']);
            Session::flash('msg', $connectionStatus['message']);
            return Redirect::back()->send();
        }
    }

    public function index()
    {
        if($this->permissionDetails('Asset_type','access')){
                       
            $permissions = $this->getPermission("Asset_type");
            
            if($this->merchantId == 0){

                $asset_types = Asset_type::All();
                return view('asset_type.index',compact('asset_types','permissions'));
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
        if($this->permissionDetails('Asset_type','add')){
            return view('asset_type.create');
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
        $asset_type = new Asset_type();

        $asset_type->asset_type_id = $request->asset_type_id;
        $asset_type->asset_type_code = $request->asset_type_code;
        $asset_type->asset_type_name = $request->asset_type_name;
        
        $asset_type->save();        

        Session::flash('type', 'success'); 
        Session::flash('msg', 'Asset Type Successfully Inserted');

        if ($request->submitBtn === "Save") {
           return redirect('asset_type/'. $asset_type->asset_type_id . '/edit');
        }else{
           return redirect('asset_type');
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
        
        if($this->permissionDetails('Asset_type','manage')){
            
            $asset_type = Asset_type::findOrfail($id);
        return view('asset_type.edit',compact('asset_type'));

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
        $asset_type = Asset_type::findOrfail($id);
    	
        $asset_type->asset_type_id = $request->asset_type_id;
        $asset_type->asset_type_code = $request->asset_type_code;
        $asset_type->asset_type_name = $request->asset_type_name;
        
        $asset_type->save();

        Session::flash('type', 'success'); 
        Session::flash('msg', 'Asset Type Successfully Updated');

        if ($request->submitBtn === "Save") {
           return redirect('asset_type/'. $asset_type->asset_type_id . '/edit');
        }else{
           return redirect('asset_type');
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
     	
        if($this->permissionDetails('Asset_type','delete')){
            $asset_type = Asset_type::findOrfail($id);
            $asset_type->delete();
            Session::flash('type', 'success'); 
            Session::flash('msg', 'Asset Type Successfully Deleted');
            return redirect('asset_type');
        }else{
            return Redirect::back()->with('message', 'You are not authorized to use this functionality!');
        }
    }
}
