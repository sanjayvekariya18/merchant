<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\App;
use Illuminate\Http\Request;
use App\Http\Controllers\PermissionsController;
use Amranidev\Ajaxis\Ajaxis;
use App\Http\Controllers\ProductApprovalController;
use App\Http\Traits\PermissionTrait;
use App\Helpers\ConnectionManager;
use App\Identity_type;
use Carbon\Carbon;
use URL;
use Session;
use DB;
use Redirect;

/**
 * Class Identity_typeController.
 *
 * @author  The scaffold-interface created at 2018-02-18 02:18:07pm
 * @link  https://github.com/amranidev/scaffold-interface
 */
class Identity_typeController extends PermissionsController
{
    use PermissionTrait;

    public function __construct()
    {

        parent::__construct();

        $connectionStatus = ConnectionManager::setDbConfig('Identity_type');
        if (strcmp($connectionStatus['type'],"error") == 0) {
            Session::flash('type', $connectionStatus['type']);
            Session::flash('msg', $connectionStatus['message']);
            return Redirect::back()->send();
        }

    }
    
    public function index()
    {
        if($this->permissionDetails('Identity_type','access')){
                       
            $permissions = $this->getPermission("Identity_type");
            
            if($this->merchantId == 0){
                $identity_types = Identity_type::paginate(25);
                return view('identity_type.index',compact('identity_types','permissions'));
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
        if($this->permissionDetails('Identity_type','add')){
            return view('identity_type.create');
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
        $identity_type = new Identity_type();

        
        $identity_type->type_code = $request->type_code;        
        $identity_type->type_name = $request->type_name;        
        
        $identity_type->save();

        $identityTypeId = $identity_type->identity_type_id;

        Session::flash('type', 'success'); 
        Session::flash('msg', 'Identity Type Successfully Created');
        
        if ($request->submitBtn == "Save") {
            return redirect('identity_type/'. $identityTypeId . '/edit');
        }else{
            return redirect('identity_type');
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
        if($this->permissionDetails('Identity_type','manage')){
            
            $identity_type = Identity_type::findOrfail($id);
            return view('identity_type.edit',compact('identity_type'));

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

        $identity_type = Identity_type::findOrfail($id);
        
        $identity_type->type_code = $request->type_code;        
        $identity_type->type_name = $request->type_name;       
        
        $identity_type->save();

        $identityTypeId = $identity_type->identity_type_id;

        Session::flash('type', 'success'); 
        Session::flash('msg', 'Identity Type Successfully Created');
        
        if ($request->submitBtn == "Save") {
            return redirect('identity_type/'. $identityTypeId . '/edit');
        }else{
            return redirect('identity_type');
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
        if($this->permissionDetails('Identity_type','delete')){
            $identity_type = Identity_type::findOrfail($id);
            $identity_type->delete();
            Session::flash('type', 'success'); 
            Session::flash('msg', 'Identity Type Successfully Deleted');
            return redirect('identity_type');
        }else{
            return Redirect::back()->with('message', 'You are not authorized to use this functionality!');
        }
    }
}
