<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\App;
use Illuminate\Http\Request;
use App\Http\Controllers\PermissionsController;
use App\Account_type;
use Amranidev\Ajaxis\Ajaxis;
use App\Http\Controllers\ProductApprovalController;
use App\Http\Traits\PermissionTrait;
use App\Helpers\ConnectionManager;
use URL;
use Session;
use DB;
use Redirect;

/**
 * Class Account_typeController.
 *
 * @author  The scaffold-interface created at 2018-02-18 01:01:05pm
 * @link  https://github.com/amranidev/scaffold-interface
 */
class Account_typeController extends PermissionsController
{
    use PermissionTrait;

    public function __construct()
    {
        parent::__construct();

        $connectionStatus = ConnectionManager::setDbConfig('Account_type');
        if (strcmp($connectionStatus['type'],"error") == 0) {
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
        if($this->permissionDetails('Account_type','access')){
                       
            $permissions = $this->getPermission("Account_type");
            if($this->merchantId == 0){

                $account_types = Account_type::paginate(25);
                return view('account_type.index',compact('account_types','permissions'));
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
        if($this->permissionDetails('Account_type','add')){
            return view('account_type.create');
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
        $account_type = new Account_type();

        
        $account_type->type_name = $request->type_name;
        
        
        $account_type->save();

        $typeId = $account_type->type_id;

        Session::flash('type', 'success'); 
        Session::flash('msg', 'Account Type Successfully Created');
        
        if ($request->submitBtn == "Save") {
            return redirect('account_type/'. $typeId . '/edit');
        }else{
            return redirect('account_type');
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
        if($this->permissionDetails('Account_type','manage')){
            
            $account_type = Account_type::findOrfail($id);
            return view('account_type.edit',compact('account_type'));

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
        $account_type = Account_type::findOrfail($id);
        
        $account_type->type_name = $request->type_name;
        
        
        $account_type->save();

        $typeId = $account_type->type_id;

        Session::flash('type', 'success'); 
        Session::flash('msg', 'Account Type Successfully Updated');
        
        if ($request->submitBtn == "Save") {
            return redirect('account_type/'. $typeId . '/edit');
        }else{
            return redirect('account_type');
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
        if($this->permissionDetails('Account_type','delete')){
            $account_type = Account_type::findOrfail($id);
            $account_type->delete();
            Session::flash('type', 'success'); 
            Session::flash('msg', 'Account Type Successfully Deleted');
            return redirect('account_type');
        }else{
            return Redirect::back()->with('message', 'You are not authorized to use this functionality!');
        }
    }
}
