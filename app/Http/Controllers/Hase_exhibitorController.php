<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\App;
use Illuminate\Http\Request;
use App\Http\Controllers\PermissionsController;
use App\Helpers\ConnectionManager;
use App\Exhibitor;
use App\Merchant;
use App\Http\Traits\PermissionTrait;
use URL;
use Session;
use DB;
use Redirect;
use Auth;

/**
 * Class Hase_exhibitorController.
 *
 * @author  The scaffold-interface created at 2017-05-24 04:20:12pm
 * @link  https://github.com/amranidev/scaffold-interface
 */
class Hase_exhibitorController extends PermissionsController
{
    use PermissionTrait;

    public function __construct()
    {
        parent::__construct();

        $connectionStatus = ConnectionManager::setDbConfig('Hase_exhibitor');
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
        if($this->permissionDetails('Hase_exhibitor','access')) {
            $permissions = $this->getPermission('Hase_exhibitor');
            $title = 'Index - hase_exhibitor';
            
            $hase_exhibitors = Exhibitor::
                        distinct()
                        ->select('exhibitor.*','merchant_identity.identity_name as merchant_name')
                        ->leftjoin('merchant','exhibitor.merchant_id','=','merchant.merchant_id')
                        ->leftjoin('identity_merchant as merchant_identity','merchant_identity.identity_id','=','merchant.identity_id')
                        ->groupBy('exhibitor.exhibitor_id')
                        ->get();

            return view('hase_exhibitor.index',compact('hase_exhibitors','title','permissions'));
        } else {
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
        $title = 'Create - hase_exhibitor';
        $hase_merchants = Merchant::
                    distinct()
                    ->select('merchant_id','merchant_identity.identity_name as merchant_name')
                    ->leftjoin('identity_merchant as merchant_identity','merchant_identity.identity_id','=','merchant.identity_id')
                    ->where('merchant.merchant_id','!=',0)
                    ->get();

        return view('hase_exhibitor.create',compact('title','hase_merchants'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param    \Illuminate\Http\Request  $request
     * @return  \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        $hase_exhibitor = new Exhibitor();

        $hase_exhibitor->merchant_id = $request->merchant_id;

        $hase_exhibitor->exhibitor_floor = $request->exhibitor_floor;

        $hase_exhibitor->exhibitor_hall = $request->exhibitor_hall;

        $hase_exhibitor->exhibitor_location = $request->exhibitor_location;

        $hase_exhibitor->exhibitor_description = $request->exhibitor_description;

        $hase_exhibitor->exhibitor_contact = $request->exhibitor_contact;

        $hase_exhibitor->exhibitor_namecard_url = $request->exhibitor_namecard_url;

        $hase_exhibitor->exhibitor_location_map_url = $request->exhibitor_location_map_url;

        $hase_exhibitor->exhibitor_location_directions = $request->exhibitor_location_directions;

        $hase_exhibitor->save();

        Session::flash('type', 'success');
        Session::flash('msg', 'Exhibitor Successfully Inserted');

        if ($request->submitBtn === "Save") {
           return redirect('hase_exhibitor/'. $hase_exhibitor->exhibitor_id . '/edit');
        }else{
           return redirect('hase_exhibitor');
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

        if($this->permissionDetails('Hase_exhibitor','manage')) {
            $title = 'Edit - hase_exhibitor';
            $hase_exhibitor = Exhibitor::findOrfail($id);
            $hase_merchants = Merchant::
                    distinct()
                    ->select('merchant_id','merchant_identity.identity_name as merchant_name')
                    ->leftjoin('identity_merchant as merchant_identity','merchant_identity.identity_id','=','merchant.identity_id')
                    ->where('merchant.merchant_id','!=',0)
                    ->get();

            return view('hase_exhibitor.edit',compact('title','hase_merchants','hase_exhibitor'));
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
        $hase_exhibitor = Exhibitor::findOrfail($id);

        $hase_exhibitor->merchant_id = $request->merchant_id;

        $hase_exhibitor->exhibitor_floor = $request->exhibitor_floor;

        $hase_exhibitor->exhibitor_hall = $request->exhibitor_hall;

        $hase_exhibitor->exhibitor_location = $request->exhibitor_location;

        $hase_exhibitor->exhibitor_description = $request->exhibitor_description;

        $hase_exhibitor->exhibitor_contact = $request->exhibitor_contact;

        $hase_exhibitor->exhibitor_namecard_url = $request->exhibitor_namecard_url;

        $hase_exhibitor->exhibitor_location_map_url = $request->exhibitor_location_map_url;

        $hase_exhibitor->exhibitor_location_directions = $request->exhibitor_location_directions;

        $hase_exhibitor->save();

        Session::flash('type', 'success');
        Session::flash('msg', 'Exhibitor Successfully Updated');

        if ($request->submitBtn === "Save") {
           return redirect('hase_exhibitor/'. $hase_exhibitor->exhibitor_id . '/edit');
        }else{
           return redirect('hase_exhibitor');
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
     	
        if($this->permissionDetails('Hase_exhibitor','delete')) {
            $hase_exhibitor = Exhibitor::findOrfail($id);
            $hase_exhibitor->delete();
            Session::flash('type', 'success'); 
            Session::flash('msg', 'Exhibitor Successfully Deleted');
            return redirect('hase_exhibitor');
        } else {
            return Redirect::back()->with('message', 'You are not authorized to use this functionality!');
        }
    }
}
