<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\App;
use Illuminate\Http\Request;
use App\Http\Controllers\PermissionsController;
use App\Helpers\ConnectionManager;
use App\Country;
use Carbon\Carbon;
use Amranidev\Ajaxis\Ajaxis;
use App\Http\Traits\PermissionTrait;
use URL;
use DB;
use Session;
use Redirect;

/**
 * Class Hase_countryController.
 *
 * @author  The scaffold-interface created at 2017-05-06 11:37:38am
 * @link  https://github.com/amranidev/scaffold-interface
 */
class Hase_countryController extends PermissionsController
{
    use PermissionTrait;

    public function __construct()
    {
        parent::__construct();

        $connectionStatus = ConnectionManager::setDbConfig('Hase_merchant');
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
        if($this->permissionDetails('Hase_merchant','access')){

            $title = 'Index - hase_country';
            $permissions = $this->getPermission("Hase_merchant");
            $hase_countries = Country::all();

            return view('hase_country.index',compact('hase_countries','permissions','title'));
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
        if($this->permissionDetails('Hase_merchant','add')){
            $title = 'Create - hase_country';
        
            return view('hase_country.create');
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
        $hase_country = new Country();

        $hase_country->country_name = $request->country_name;
        
        $hase_country->iso_code_2 = $request->iso_code_2;

        $hase_country->iso_code_3 = $request->iso_code_3;

        $hase_country->format = $request->format;

        $hase_country->status = (isset($request->status)) ? 1 : 0 ;
        
        $hase_country->country_phone_code = $request->country_phone_code;

        $hase_country->telephone_min = $request->telephone_min;

        $hase_country->telephone_max = $request->telephone_max;
        
        $hase_country->save();

        $countryID = $hase_country->country_id;

        if($request->file('flag')){

            $publicDirPath = public_path(env('image_dir_path'));
            $countryFlagDirPath = "flags/";

            $flagDirPath = $publicDirPath.$countryFlagDirPath;

            if(!file_exists($flagDirPath)){
                mkdir($flagDirPath,0777,true);
            }

            if($request->file('flag')){

                $countryFlag = $request->file('flag')->getClientOriginalName();
                $request->file('flag')->move($flagDirPath,$countryFlag);
                $hase_merchant->flag = "$countryFlagDirPath$countryFlag";
            }

            $hase_country->save();
        }

        $staffUrl = "/hase_staff/".$this->staffId."/edit";
        $countryUrl = "/hase_country/".$countryID."/edit";
        $action="added";

        $message = "<a href='".URL::to($staffUrl)."'>".$this->staffName."</a> <strong>added</strong> country <a href='".URL::to($countryUrl)."'> <strong>".$hase_country->country_name."</strong></a>";
        PermissionTrait::addActivityLog($action,$message);

        Session::flash('type', 'success'); 
        Session::flash('msg', 'Country Successfully Created'); 

        if($request->submitBtn === "Save") {
            return redirect('hase_country/'. $countryID . '/edit');
        }else{
            return redirect('hase_country');
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
        $title = 'Show - hase_country';

        if($request->ajax())
        {
            return URL::to('hase_country/'.$id);
        }

        $hase_country = Country::findOrfail($id);
        return view('hase_country.show',compact('title','hase_country'));
    }

    /**
     * Show the form for editing the specified resource.
     * @param    \Illuminate\Http\Request  $request
     * @param    int  $id
     * @return  \Illuminate\Http\Response
     */
    public function edit($id,Request $request)
    {
        if($this->permissionDetails('Hase_merchant','manage')){
            $title = 'Edit - hase_country';
            $hase_country = Country::findOrfail($id);
            return view('hase_country.edit',compact('title','hase_country'));
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
        $hase_country = Country::findOrfail($id);
    	
        $hase_country->country_name = $request->country_name;
        
        $hase_country->iso_code_2 = $request->iso_code_2;
        
        $hase_country->iso_code_3 = $request->iso_code_3;
        
        $hase_country->format = $request->format;
        
        $hase_country->status = isset($request->status) ? 1 : 0;
        
        $hase_country->country_phone_code = $request->country_phone_code;
        
        $hase_country->telephone_min = $request->telephone_min;
        
        $hase_country->telephone_max = $request->telephone_max;

        if($request->file('flag')){

            $publicDirPath = public_path(env('image_dir_path'));
            
            $countryFlagDirPath = "flags/";

            $flagDirPath = $publicDirPath.$countryFlagDirPath;

            if(!file_exists($flagDirPath)){
                mkdir($flagDirPath,0777,true);
            }

            if($request->file('flag')){

                $imagePath = $publicDirPath.$hase_country->flag;
                if (is_file($imagePath)) {
                    unlink($imagePath);
                }

                $countryFlag = $request->file('flag')->getClientOriginalName();
                $request->file('flag')->move($flagDirPath,$countryFlag);
                $hase_country->flag = "$countryFlagDirPath$countryFlag";
            }
        }
        $hase_country->save();

        $staffUrl = "/hase_staff/".$this->staffId."/edit";
        $countryUrl = "/hase_country/".$id."/edit";
        $action="updated";
        $message = "<a href='".URL::to($staffUrl)."'>".$this->staffName."</a> <strong>updated</strong> country <a href='".URL::to($countryUrl)."'> <strong>".$hase_country->country_name."</strong></a>";
        PermissionTrait::addActivityLog($action,$message);

        Session::flash('type', 'success'); 
        Session::flash('msg', 'Merchant Successfully Updated');
        
        if ($request->submitBtn === "Save") {
            return redirect('hase_country/'. $hase_country->country_id . '/edit');
        }else{
            return redirect('hase_country');
        }
    }

    /**
     * Delete confirmation message by Ajaxis.
     *
     * @link      https://github.com/amranidev/ajaxis
     * @param    \Illuminate\Http\Request  $request
     * @return  String
     */
    public function DeleteMsg($id,Request $request)
    {
        $msg = Ajaxis::BtDeleting('Warning!!','Would you like to remove This?','/hase_country/'. $id . '/delete');

        if($request->ajax())
        {
            return $msg;
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
        if($this->permissionDetails('Hase_merchant','delete')){
            $hase_country = Country::findOrfail($id);
            $hase_country->delete();

            Session::flash('type', 'error'); 
            Session::flash('msg', 'Country Successfully Deleted');

            return redirect('hase_country');
        }else{
            return Redirect::back()->with('message', 'You are not authorized to use this functionality!');     
        }
     	
    }
}
