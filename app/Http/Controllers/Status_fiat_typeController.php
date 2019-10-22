<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\App;
use Illuminate\Http\Request;
use App\Http\Controllers\PermissionsController;
use App\Helpers\ConnectionManager;
use App\Status_fiat_type;
use Amranidev\Ajaxis\Ajaxis;
use App\Http\Traits\PermissionTrait;

use URL;
use Session;
use DB;
use Redirect;


/**
 * Class Status_fiat_typeController.
 *
 * @author  The scaffold-interface created at 2018-02-14 08:06:23pm
 * @link  https://github.com/amranidev/scaffold-interface
 */
class Status_fiat_typeController extends PermissionsController
{
    use PermissionTrait;

    public function __construct()
    {
        parent::__construct();

        $connectionStatus = ConnectionManager::setDbConfig('Status_fiat_type');

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
        
        if($this->permissionDetails('Status_fiat_type','access')){
                       
            $permissions = $this->getPermission("Status_fiat_type");
            
            if($this->merchantId == 0){

                $status_fiat_types = Status_fiat_type::All();
                return view('status_fiat_type.index',compact('status_fiat_types','permissions'));
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

        if($this->permissionDetails('Status_fiat_type','add')){
            return view('status_fiat_type.create');
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
        $status_fiat_type = new Status_fiat_type();

        $status_fiat_type->status_fiat_type_code = $request->status_fiat_type_code;
        $status_fiat_type->status_fiat_type_name = $request->status_fiat_type_name;
        $status_fiat_type->status_fiat_type_color = $request->status_fiat_type_color;
        $status_fiat_type->status_fiat_type_font_color = $request->status_fiat_type_font_color;
        
        $status_fiat_type->save();

        $pusher = App::make('pusher');

        Session::flash('type', 'success'); 
        Session::flash('msg', 'Status Operation Type Successfully Inserted');

        if ($request->submitBtn === "Save") {
           return redirect('status_fiat_type/'. $status_fiat_type->status_fiat_type_id . '/edit');
        }else{
           return redirect('status_fiat_type');
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
        if($this->permissionDetails('Status_fiat_type','manage')){
            
            $status_fiat_type = Status_fiat_type::findOrfail($id);
        return view('status_fiat_type.edit',compact('status_fiat_type'));

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
        $status_fiat_type = Status_fiat_type::findOrfail($id);
    	
        $status_fiat_type->status_fiat_type_code = $request->status_fiat_type_code;
        $status_fiat_type->status_fiat_type_name = $request->status_fiat_type_name;
        $status_fiat_type->status_fiat_type_color = $request->status_fiat_type_color;
        $status_fiat_type->status_fiat_type_font_color = $request->status_fiat_type_font_color;
        
        $status_fiat_type->save();

        Session::flash('type', 'success'); 
        Session::flash('msg', 'Status Operation Type Successfully Updated');

        if ($request->submitBtn === "Save") {
           return redirect('status_fiat_type/'. $status_fiat_type->status_fiat_type_id . '/edit');
        }else{
           return redirect('status_fiat_type');
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
     	
        if($this->permissionDetails('Status_fiat_type','delete')){
            $status_fiat_type = Status_fiat_type::findOrfail($id);
        $status_fiat_type->delete();
            Session::flash('type', 'success'); 
            Session::flash('msg', 'Status Operation Type Successfully Deleted');
            return redirect('status_fiat_type');
        }else{
            return Redirect::back()->with('message', 'You are not authorized to use this functionality!');
        }
    }
}
