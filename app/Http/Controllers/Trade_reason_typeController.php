<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\App;
use Illuminate\Http\Request;
use App\Http\Controllers\PermissionsController;
use App\Helpers\ConnectionManager;
use Amranidev\Ajaxis\Ajaxis;
use App\Http\Traits\PermissionTrait;
use App\TradeReasonType;
use Carbon\Carbon;
use URL;
use Session;
use DB;
use Redirect;

/**
 * Class Trade_reason_typeController.
 *
 * @author  The scaffold-interface created at 2018-02-08 07:46:49pm
 * @link  https://github.com/amranidev/scaffold-interface
 */
class Trade_reason_typeController extends PermissionsController
{
    use PermissionTrait;

    public function __construct()
    {
        parent::__construct();

        $connectionStatus = ConnectionManager::setDbConfig('Trade_reason_type');

        if ($connectionStatus['type'] === "error") {
            Session::flash('type', $connectionStatus['type']);
            Session::flash('msg', $connectionStatus['message']);
            return Redirect::back()->send();
        }
    }

    public function index()
    {
         if($this->permissionDetails('Trade_reason_type','access')){
                       
             $permissions = $this->getPermission("Trade_reason_type");
            
            if($this->merchantId == 0){
                $trade_reason_types = TradeReasonType::paginate(25);
                 return view('trade_reason_type.index',compact('trade_reason_types','permissions'));
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
         if($this->permissionDetails('Trade_reason_type','add')){
            return view('trade_reason_type.create');
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
        $trade_reason_type = new TradeReasonType();

        
        $trade_reason_type->trade_reason_type_code = $request->trade_reason_type_code;

        
        $trade_reason_type->trade_reason_type_name = $request->trade_reason_type_name;
        
        
        $trade_reason_type->save();

        $tradeReasonID = $trade_reason_type->trade_reason_type_id;

        Session::flash('type', 'success'); 
        Session::flash('msg', 'Trade Reason Type Successfully Created');
        
        if ($request->submitBtn === "Save") {
            return redirect('trade_reason_type/'. $tradeReasonID . '/edit');
        }else{
            return redirect('trade_reason_type');
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
         if($this->permissionDetails('Trade_reason_type','manage')){
            
            $trade_reason_type = TradeReasonType::findOrfail($id);
            return view('trade_reason_type.edit',compact('trade_reason_type'));

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
        $trade_reason_type = TradeReasonType::findOrfail($id);
        
        $trade_reason_type->trade_reason_type_code = $request->trade_reason_type_code;

        
        $trade_reason_type->trade_reason_type_name = $request->trade_reason_type_name;
        
        
        $trade_reason_type->save();

        $tradeReasonID = $trade_reason_type->trade_reason_type_id;

        Session::flash('type', 'success'); 
        Session::flash('msg', 'Trade Reason Type Successfully Created');
        
        if ($request->submitBtn === "Save") {
            return redirect('trade_reason_type/'. $tradeReasonID . '/edit');
        }else{
            return redirect('trade_reason_type');
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
         if($this->permissionDetails('Trade_reason_type','delete')){
            $trade_reason_type = TradeReasonType::findOrfail($id);
            $trade_reason_type->delete();
            Session::flash('type', 'success'); 
            Session::flash('msg', 'Trade Reason Type Successfully Deleted');
            return redirect('trade_reason_type');
         }else{
             return Redirect::back()->with('message', 'You are not authorized to use this functionality!');
         }
    }
}
