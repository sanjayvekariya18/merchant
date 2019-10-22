<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\App;
use Illuminate\Http\Request;
use App\Http\Controllers\PermissionsController;
use App\Helpers\ConnectionManager;
use Amranidev\Ajaxis\Ajaxis;
use App\Http\Controllers\ProductApprovalController;
use App\Http\Traits\PermissionTrait;
use App\TradeOrderType;
use Carbon\Carbon;
use URL;
use Session;
use DB;
use Redirect;

/**
 * Class Trade_order_typeController.
 *
 * @author  The scaffold-interface created at 2018-02-08 07:46:49pm
 * @link  https://github.com/amranidev/scaffold-interface
 */
class Trade_order_typeController extends PermissionsController
{
    use PermissionTrait;

    public function __construct()
    {
        parent::__construct();

        $connectionStatus = ConnectionManager::setDbConfig('Trade_order_type');

        if ($connectionStatus['type'] === "error") {
            Session::flash('type', $connectionStatus['type']);
            Session::flash('msg', $connectionStatus['message']);
            return Redirect::back()->send();
        }
    }

    public function index()
    {
         if($this->permissionDetails('Trade_order_type','access')){
                       
             $permissions = $this->getPermission("Trade_order_type");
            
             if($this->merchantId == 0){
                $trade_order_types = TradeOrderType::all();
                 return view('trade_order_type.index',compact('trade_order_types','permissions'));
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
         if($this->permissionDetails('Trade_order_type','add')){
            return view('trade_order_type.create');
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
        $trade_order_type = new TradeOrderType();

        
        $trade_order_type->type_code = $request->type_code;

        
        $trade_order_type->type_name = $request->type_name;
        
        
        $trade_order_type->save();

        $tradeOrderID = $trade_order_type->type_id;

        Session::flash('type', 'success'); 
        Session::flash('msg', 'Trade Order Type Successfully Created');
        
        if ($request->submitBtn === "Save") {
            return redirect('trade_order_type/'. $tradeOrderID . '/edit');
        }else{
            return redirect('trade_order_type');
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
         if($this->permissionDetails('Trade_order_type','manage')){
            
            $trade_order_type = TradeOrderType::findOrfail($id);
            return view('trade_order_type.edit',compact('trade_order_type'));

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
        $trade_order_type = TradeOrderType::findOrfail($id);
        
        $trade_order_type->type_code = $request->type_code;

        
        $trade_order_type->type_name = $request->type_name;
        
        
        $trade_order_type->save();

        $tradeOrderID = $trade_order_type->type_id;

        Session::flash('type', 'success'); 
        Session::flash('msg', 'Trade Order Type Successfully Created');
        
        if ($request->submitBtn === "Save") {
            return redirect('trade_order_type/'. $tradeOrderID . '/edit');
        }else{
            return redirect('trade_order_type');
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
         if($this->permissionDetails('Trade_order_type','delete')){
            $trade_order_type = TradeOrderType::findOrfail($id);
            $trade_order_type->delete();
            Session::flash('type', 'success'); 
            Session::flash('msg', 'Trade Order Type Successfully Deleted');
            return redirect('trade_order_type');
         }else{
             return Redirect::back()->with('message', 'You are not authorized to use this functionality!');
         }
    }
}
