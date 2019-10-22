<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\App;
use Illuminate\Http\Request;
use App\Http\Controllers\PermissionsController;
use App\Helpers\ConnectionManager;
use Amranidev\Ajaxis\Ajaxis;
use App\Http\Traits\PermissionTrait;
use App\TradeSideType;
use Carbon\Carbon;
use URL;
use Session;
use DB;
use Redirect;

/**
 * Class Trade_side_typeController.
 *
 * @author  The scaffold-interface created at 2018-02-08 07:46:49pm
 * @link  https://github.com/amranidev/scaffold-interface
 */
class Trade_side_typeController extends PermissionsController
{
    use PermissionTrait;

    public function __construct()
    {
        parent::__construct();

        $connectionStatus = ConnectionManager::setDbConfig('Trade_side_type');

        if ($connectionStatus['type'] === "error") {
            Session::flash('type', $connectionStatus['type']);
            Session::flash('msg', $connectionStatus['message']);
            return Redirect::back()->send();
        }
    }

    public function index()
    {
         if($this->permissionDetails('Trade_side_type','access')){
                       
             $permissions = $this->getPermission("Trade_side_type");
            
             if($this->merchantId == 0){
                $trade_side_types = TradeSideType::all();
                 return view('trade_side_type.index',compact('trade_side_types','permissions'));
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
         if($this->permissionDetails('Trade_side_type','add')){
            return view('trade_side_type.create');
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
        $trade_side_type = new TradeSideType();

        
        $trade_side_type->side_type_code = $request->side_type_code;

        
        $trade_side_type->side_type_name = $request->side_type_name;
        
        
        $trade_side_type->save();

        $tradeSideID = $trade_side_type->side_type_id;

        Session::flash('type', 'success'); 
        Session::flash('msg', 'Trade Side Type Successfully Created');
        
        if ($request->submitBtn === "Save") {
            return redirect('trade_side_type/'. $tradeSideID . '/edit');
        }else{
            return redirect('trade_side_type');
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
         if($this->permissionDetails('Trade_side_type','manage')){
            
            $trade_side_type = TradeSideType::findOrfail($id);
            return view('trade_side_type.edit',compact('trade_side_type'));

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
        $trade_side_type = TradeSideType::findOrfail($id);
        
        $trade_side_type->side_type_code = $request->side_type_code;

        
        $trade_side_type->side_type_name = $request->side_type_name;
        
        
        $trade_side_type->save();

        $tradeSideID = $trade_side_type->side_type_id;

        Session::flash('type', 'success'); 
        Session::flash('msg', 'Trade Side Type Successfully Created');
        
        if ($request->submitBtn === "Save") {
            return redirect('trade_side_type/'. $tradeSideID . '/edit');
        }else{
            return redirect('trade_side_type');
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
         if($this->permissionDetails('Trade_side_type','delete')){
            $trade_side_type = TradeSideType::findOrfail($id);
            $trade_side_type->delete();
            Session::flash('type', 'success'); 
            Session::flash('msg', 'Trade Side Type Successfully Deleted');
            return redirect('trade_side_type');
         }else{
             return Redirect::back()->with('message', 'You are not authorized to use this functionality!');
         }
    }
}
