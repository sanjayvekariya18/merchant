<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\App;
use Illuminate\Http\Request;
use App\Http\Controllers\PermissionsController;
use App\Helpers\ConnectionManager;
use App\Exchange;
use App\Group;
use App\Identity_exchange;
use Amranidev\Ajaxis\Ajaxis;
use App\Http\Traits\PermissionTrait;

use URL;
use Session;
use DB;
use Redirect;

const EXCHANGE_IDENTITY_TYPE_ID=9;
const EXCHANGE_TABLE_ID=6;
const EXCHANGE_IDENTITY_TABLE_ID=5;

/**
 * Class ExchangeController.
 *
 * @author  The scaffold-interface created at 2018-02-06 01:54:39pm
 * @link  https://github.com/amranidev/scaffold-interface
 */
class ExchangeController extends PermissionsController
{
    use PermissionTrait;

    public function __construct()
    {
        parent::__construct();

        $connectionStatus = ConnectionManager::setDbConfig('Exchange');

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
        
        if($this->permissionDetails('Exchange','access')){
                       
            $permissions = $this->getPermission("Exchange");
            
            if($this->merchantId == 0){

                $exchanges = Exchange::
                    select('identity_exchange.identity_code as exchange_code','identity_exchange.identity_name as exchange_name','identity_exchange.identity_website as exchange_website','exchange.*')
                    ->join('identity_exchange','identity_exchange.identity_id','=','exchange.identity_id')
                    ->get();

                return view('exchange.index',compact('exchanges','permissions'));
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
        if($this->permissionDetails('Exchange','add')){
            return view('exchange.create');
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

        $exchange = new Exchange();
        $identity_exchange = new Identity_exchange();
        
        $identity_exchange->identity_code = $request->exchange_code;
        $identity_exchange->identity_name = $request->exchange_name;
        $identity_exchange->identity_website = $request->exchange_website;
        $identity_exchange->identity_table_id = EXCHANGE_TABLE_ID;
        $identity_exchange->identity_type_id = EXCHANGE_IDENTITY_TYPE_ID;

        $identity_exchange->save();
        $identityID = $identity_exchange->identity_id;
        
        $exchange->identity_id = $identityID;
        $exchange->identity_table_id = EXCHANGE_IDENTITY_TABLE_ID;
        $exchange->trading_fees_url = $request->trading_fees_url;
        $exchange->trading_api_url = $request->trading_api_url;
        $exchange->save();

        Session::flash('type', 'success'); 
        Session::flash('msg', 'Exchange Successfully Created');
        if ($request->submitBtn === "Save") {
           return redirect('exchange/'. $exchange->exchange_id . '/edit');
        }else{
           return redirect('exchange');
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
        
        if($this->permissionDetails('Exchange','manage')){
            
            $exchange = Exchange::
                    select('identity_exchange.identity_code as exchange_code','identity_exchange.identity_name as exchange_name','identity_exchange.identity_website as exchange_website','exchange.*')
                    ->join('identity_exchange','identity_exchange.identity_id','=','exchange.identity_id')
                    ->where('exchange_id',$id)
                    ->get()->first();
        
            return view('exchange.edit',compact('exchange'));

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
        
        $exchange = Exchange::findOrfail($id);
        $identity_exchange = Identity_exchange::findOrfail($exchange->identity_id);
        
        $identity_exchange->identity_code = $request->exchange_code;
        $identity_exchange->identity_name = $request->exchange_name;
        $identity_exchange->identity_website = $request->exchange_website;
        $identity_exchange->identity_table_id = EXCHANGE_TABLE_ID;
        $identity_exchange->identity_type_id = EXCHANGE_IDENTITY_TYPE_ID;

        $identity_exchange->save();
        $identityID = $identity_exchange->identity_id;
        
        $exchange->identity_id = $identityID;
        $exchange->identity_table_id = EXCHANGE_IDENTITY_TABLE_ID;
        $exchange->trading_fees_url = $request->trading_fees_url;
        $exchange->trading_api_url = $request->trading_api_url;
        $exchange->save();

        Session::flash('type', 'success'); 
        Session::flash('msg', 'Exchange Successfully Updated');
        if ($request->submitBtn === "Save") {
           return redirect('exchange/'. $exchange->exchange_id . '/edit');
        }else{
           return redirect('exchange');
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
     	
        if($this->permissionDetails('Exchange','delete')){
            $exchange = Exchange::findOrfail($id);
            $identity_exchange = Identity_exchange::findOrfail($exchange->identity_id);

            $exchange->delete();
            $identity_exchange->delete();
            Session::flash('type', 'success'); 
            Session::flash('msg', 'Exchange Successfully Deleted');
            return redirect('exchange');
        }else{
            return Redirect::back()->with('message', 'You are not authorized to use this functionality!');
        }
    }
}
