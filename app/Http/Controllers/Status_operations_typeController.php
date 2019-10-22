<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\App;
use Illuminate\Http\Request;
use App\Http\Controllers\PermissionsController;
use App\Helpers\ConnectionManager;
use App\Status_operations_type;
use Amranidev\Ajaxis\Ajaxis;
use App\Http\Controllers\ProductApprovalController;
use App\Http\Traits\PermissionTrait;

use URL;
use Session;
use DB;
use Redirect;


/**
 * Class Status_operations_typeController.
 *
 * @author  The scaffold-interface created at 2018-02-14 08:06:23pm
 * @link  https://github.com/amranidev/scaffold-interface
 */
class Status_operations_typeController extends PermissionsController
{
    use PermissionTrait;

    public function __construct()
    {
        parent::__construct();

        $connectionStatus = ConnectionManager::setDbConfig('Status_operations_type');

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
        
        if($this->permissionDetails('Status_operations_type','access')){
                       
            $permissions = $this->getPermission("Status_operations_type");
            
            if($this->merchantId == 0){

                $status_operations_types = Status_operations_type::All();
                return view('status_operations_type.index',compact('status_operations_types','permissions'));
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

        if($this->permissionDetails('Status_operations_type','add')){
            return view('status_operations_type.create');
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
        $status_operations_type = new Status_operations_type();

        $status_operations_type->type_code = $request->type_code;
        $status_operations_type->type_name = $request->type_name;
        $status_operations_type->color_background = $request->color_background;
        $status_operations_type->color_foreground = $request->color_foreground;
        
        $status_operations_type->save();

        $pusher = App::make('pusher');

        Session::flash('type', 'success'); 
        Session::flash('msg', 'Status Operation Type Successfully Inserted');

        if ($request->submitBtn === "Save") {
           return redirect('status_operations_type/'. $status_operations_type->type_id . '/edit');
        }else{
           return redirect('status_operations_type');
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
        if($this->permissionDetails('Status_operations_type','manage')){
            
            $status_operations_type = Status_operations_type::findOrfail($id);
        return view('status_operations_type.edit',compact('status_operations_type'));

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
        $status_operations_type = Status_operations_type::findOrfail($id);
    	
        $status_operations_type->type_code = $request->type_code;
        $status_operations_type->type_name = $request->type_name;
        $status_operations_type->color_background = $request->color_background;
        $status_operations_type->color_foreground = $request->color_foreground;
        
        $status_operations_type->save();

        Session::flash('type', 'success'); 
        Session::flash('msg', 'Status Operation Type Successfully Updated');

        if ($request->submitBtn === "Save") {
           return redirect('status_operations_type/'. $status_operations_type->type_id . '/edit');
        }else{
           return redirect('status_operations_type');
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
     	
        if($this->permissionDetails('Status_operations_type','delete')){
            $status_operations_type = Status_operations_type::findOrfail($id);
        $status_operations_type->delete();
            Session::flash('type', 'success'); 
            Session::flash('msg', 'Status Operation Type Successfully Deleted');
            return redirect('status_operations_type');
        }else{
            return Redirect::back()->with('message', 'You are not authorized to use this functionality!');
        }
    }
}
