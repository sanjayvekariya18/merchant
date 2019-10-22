<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\App;
use Illuminate\Http\Request;
use App\Http\Controllers\PermissionsController;
use App\Helpers\ConnectionManager;
use App\Asset_team;
use Amranidev\Ajaxis\Ajaxis;
use App\Http\Controllers\ProductApprovalController;
use App\Http\Traits\PermissionTrait;

use URL;
use Session;
use DB;
use Redirect;

/**
 * Class Asset_teamController.
 *
 * @author  The scaffold-interface created at 2018-02-22 05:50:45pm
 * @link  https://github.com/amranidev/scaffold-interface
 */
class Asset_teamController extends PermissionsController
{
    use PermissionTrait;

    public function __construct()
    {
        parent::__construct();

        $connectionStatus = ConnectionManager::setDbConfig('Asset_team');

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

        if($this->permissionDetails('Asset_team','access')){
                       
            $permissions = $this->getPermission("Asset_team");
            
            if($this->merchantId == 0){

                $asset_teams = Asset_team::All();
                return view('asset_team.index',compact('asset_teams','permissions'));
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
        
        if($this->permissionDetails('Asset_team','add')){
            return view('asset_team.create');
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
        $asset_team = new Asset_team();

        $asset_team->team_name = $request->team_name;
        $asset_team->team_project = $request->team_project;
        
        
        $asset_team->save();

        Session::flash('type', 'success'); 
        Session::flash('msg', 'Asset Team Successfully Inserted');

        if ($request->submitBtn === "Save") {
           return redirect('asset_team/'. $asset_team->team_id . '/edit');
        }else{
           return redirect('asset_team');
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
                
        if($this->permissionDetails('Asset_team','manage')){
            
            $asset_team = Asset_team::findOrfail($id);
        return view('asset_team.edit',compact('asset_team'));

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
        $asset_team = Asset_team::findOrfail($id);
    	
        $asset_team->team_name = $request->team_name;
        $asset_team->team_project = $request->team_project;
        
        $asset_team->save();

        Session::flash('type', 'success'); 
        Session::flash('msg', 'Asset Team Successfully Updated');

        if ($request->submitBtn === "Save") {
           return redirect('asset_team/'. $asset_team->team_id . '/edit');
        }else{
           return redirect('asset_team');
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

        if($this->permissionDetails('Asset_team','delete')){
            $asset_team = Asset_team::findOrfail($id);
            $asset_team->delete();
            Session::flash('type', 'success'); 
            Session::flash('msg', 'Asset Team Successfully Deleted');
            return redirect('asset_team');
        }else{
            return Redirect::back()->with('message', 'You are not authorized to use this functionality!');
        }
    }
}
