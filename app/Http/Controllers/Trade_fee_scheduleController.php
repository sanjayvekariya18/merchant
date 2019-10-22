<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\App;
use Illuminate\Http\Request;
use App\Http\Controllers\PermissionsController;
use App\Helpers\ConnectionManager;
use App\Asset;
use App\Exchange;
use App\Trade_fee_schedule;
use Amranidev\Ajaxis\Ajaxis;
use App\Http\Traits\PermissionTrait;

use URL;
use Session;
use DB;
use Redirect;

/**
 * Class Trade_fee_scheduleController.
 *
 * @author  The scaffold-interface created at 2018-02-14 05:38:42pm
 * @link  https://github.com/amranidev/scaffold-interface
 */
class Trade_fee_scheduleController extends PermissionsController
{
    use PermissionTrait;

    public function __construct()
    {
        parent::__construct();

        $connectionStatus = ConnectionManager::setDbConfig('Trade_fee_schedule');

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
        $permissions = $this->getPermission("Trade_fee_schedule");
        
        $trade_fee_schedules = Trade_fee_schedule::distinct()
                            ->select('identity_exchange.identity_name as exchange_name','identity_asset_base.identity_name as base_currency_name','identity_asset_quote.identity_name as quote_currency_name','identity_asset_volume.identity_name as volume_currency_name','trade_fee_schedule.*')
                            ->join('exchange','trade_fee_schedule.exchange_id','=','exchange.exchange_id')
                            ->join('identity_exchange','exchange.identity_id','=','identity_exchange.identity_id')
                            ->join('asset as asset_base','trade_fee_schedule.base_currency_id','=','asset_base.asset_id')
                            ->join('asset as asset_quote','trade_fee_schedule.quote_currency_id','=','asset_quote.asset_id')
                            ->join('asset as asset_volume','trade_fee_schedule.volume_currency_id','=','asset_volume.asset_id')
                            ->join('identity_asset as identity_asset_base','asset_base.identity_id','=','identity_asset_base.identity_id')
                            ->join('identity_asset as identity_asset_quote','asset_quote.identity_id','=','identity_asset_quote.identity_id')
                            ->join('identity_asset as identity_asset_volume','asset_volume.identity_id','=','identity_asset_volume.identity_id')
                            ->get();

        return view('trade_fee_schedule.index',compact('trade_fee_schedules','permissions'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return  \Illuminate\Http\Response
     */
    public function create()
    {
        if($this->permissionDetails('Trade_fee_schedule','add')){
            $exchanges = Exchange::
                        select('identity_exchange.identity_code as exchange_code','identity_exchange.identity_name as exchange_name','identity_exchange.identity_website as exchange_website','exchange.*')
                        ->join('identity_exchange','identity_exchange.identity_id','=','exchange.identity_id')
                        ->get();
            $assets = Asset::select('identity_asset.identity_name as asset_name','asset.asset_id')
                ->join('identity_asset','identity_asset.identity_id','=','asset.identity_id')
                ->where('identity_asset.identity_id','!=',0)
                ->get();

            return view('trade_fee_schedule.create',compact('assets','exchanges'));
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
        $trade_fee_schedule = new Trade_fee_schedule();
        
        $trade_fee_schedule->exchange_id = $request->exchange_id;
        $trade_fee_schedule->base_currency_id = $request->base_currency_id;
        $trade_fee_schedule->quote_currency_id = $request->quote_currency_id;
        $trade_fee_schedule->volume_currency_id = $request->volume_currency_id;
        $trade_fee_schedule->trading_volume_lower = $request->trading_volume_lower;
        $trade_fee_schedule->trading_volume_upper = $request->trading_volume_upper;
        $trade_fee_schedule->trading_fees_taker = $request->trading_fees_taker;
        $trade_fee_schedule->trading_fees_maker = $request->trading_fees_maker;
        
        $trade_fee_schedule->save();

        Session::flash('type', 'success'); 
        Session::flash('msg', 'Trading Schedule Successfully Created');
        if ($request->submitBtn === "Save") {
           return redirect('trade_fee_schedule/'. $trade_fee_schedule->trading_schedule_id . '/edit');
        }else{
           return redirect('trade_fee_schedule');
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
        if($this->permissionDetails('Trade_fee_schedule','manage')){
            
            $trade_fee_schedule = Trade_fee_schedule::findOrfail($id);
            $exchanges = Exchange::
                        select('identity_exchange.identity_code as exchange_code','identity_exchange.identity_name as exchange_name','identity_exchange.identity_website as exchange_website','exchange.*')
                        ->join('identity_exchange','identity_exchange.identity_id','=','exchange.identity_id')
                        ->get();
            $assets = Asset::select('identity_asset.identity_name as asset_name','asset.asset_id')
                ->join('identity_asset','identity_asset.identity_id','=','asset.identity_id')
                ->where('identity_asset.identity_id','!=',0)
                ->get();
        
            return view('trade_fee_schedule.edit',compact('trade_fee_schedule','assets','exchanges'));

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
        $trade_fee_schedule = Trade_fee_schedule::findOrfail($id);
    	
        $trade_fee_schedule->exchange_id = $request->exchange_id;
        $trade_fee_schedule->base_currency_id = $request->base_currency_id;
        $trade_fee_schedule->quote_currency_id = $request->quote_currency_id;
        $trade_fee_schedule->volume_currency_id = $request->volume_currency_id;
        $trade_fee_schedule->trading_volume_lower = $request->trading_volume_lower;
        $trade_fee_schedule->trading_volume_upper = $request->trading_volume_upper;
        $trade_fee_schedule->trading_fees_taker = $request->trading_fees_taker;
        $trade_fee_schedule->trading_fees_maker = $request->trading_fees_maker;
        
        $trade_fee_schedule->save();

        Session::flash('type', 'success'); 
        Session::flash('msg', 'Trading Schedule Successfully Updated');
        if ($request->submitBtn === "Save") {
           return redirect('trade_fee_schedule/'. $trade_fee_schedule->trading_schedule_id . '/edit');
        }else{
           return redirect('trade_fee_schedule');
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
     	
        if($this->permissionDetails('Trade_fee_schedule','delete')){
            $trade_fee_schedule = Trade_fee_schedule::findOrfail($id);
        $trade_fee_schedule->delete();
            Session::flash('type', 'success'); 
            Session::flash('msg', 'Trading Schedule Successfully Deleted');
            return redirect('trade_fee_schedule');
        }else{
            return Redirect::back()->with('message', 'You are not authorized to use this functionality!');
        }
    }
}
