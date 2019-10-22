<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\App;
use Illuminate\Http\Request;
use App\Http\Controllers\PermissionsController;
use App\Helpers\ConnectionManager;
use App\Asset;
use App\Exchange;
use App\Exchange_rate;
use Amranidev\Ajaxis\Ajaxis;
use App\Http\Controllers\ProductApprovalController;
use App\Http\Traits\PermissionTrait;

use URL;
use Session;
use DB;
use Redirect;

/**
 * Class Exchange_rateController.
 *
 * @author  The scaffold-interface created at 2018-02-13 07:20:28pm
 * @link  https://github.com/amranidev/scaffold-interface
 */
class Exchange_ratesController extends PermissionsController
{

    use PermissionTrait;

    public function __construct()
    {
        parent::__construct();

        $connectionStatus = ConnectionManager::setDbConfig('Exchange_rate');

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
        $permissions = $this->getPermission("Exchange_rate");
    if($this->permissionDetails('Exchange_rate','access')) {
        $exchange_rates = Exchange_rate::
                            distinct()
                            ->select('identity_exchange.identity_name as exchange_name','identity_asset_base.identity_name as base_currency_name','identity_asset_quote.identity_name as quote_currency_name','identity_asset_volume.identity_name as volume_currency_name','exchange_rates.*')
                            ->join('exchange','exchange_rates.exchange_id','=','exchange.exchange_id')
                            ->join('identity_exchange','exchange.identity_id','=','identity_exchange.identity_id')
                            ->join('asset as asset_base','exchange_rates.base_currency_id','=','asset_base.asset_id')
                            ->join('asset as asset_quote','exchange_rates.quote_currency_id','=','asset_quote.asset_id')
                            ->join('asset as asset_volume','exchange_rates.volume_currency_id','=','asset_volume.asset_id')
                            ->join('identity_asset as identity_asset_base','asset_base.identity_id','=','identity_asset_base.identity_id')
                            ->join('identity_asset as identity_asset_quote','asset_quote.identity_id','=','identity_asset_quote.identity_id')
                            ->join('identity_asset as identity_asset_volume','asset_volume.identity_id','=','identity_asset_volume.identity_id')
                            ->get();

        return view('exchange_rate.index',compact('exchange_rates','permissions'));
        }
            else {
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

        if($this->permissionDetails('Exchange_rate','add')){
            $exchanges = Exchange::
                        select('identity_exchange.identity_code as exchange_code','identity_exchange.identity_name as exchange_name','identity_exchange.identity_website as exchange_website','exchange.*')
                        ->join('identity_exchange','identity_exchange.identity_id','=','exchange.identity_id')
                        ->get();
            $assets = Asset::select('identity_asset.identity_name as asset_name','asset.asset_id')
                ->join('identity_asset','identity_asset.identity_id','=','asset.identity_id')
                ->where('identity_asset.identity_id','!=',0)
                ->get();

            return view('exchange_rate.create',compact('assets','exchanges'));
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
        $exchange_rate = new Exchange_rate();
        
        $exchange_rate->exchange_id = $request->exchange_id;
        $exchange_rate->base_currency_id = $request->base_currency_id;
        $exchange_rate->quote_currency_id = $request->quote_currency_id;
        $exchange_rate->volume_currency_id = $request->volume_currency_id;
        $exchange_rate->level_margin_call = $request->level_margin_call;
        $exchange_rate->level_margin_liquidation = $request->level_margin_liquidation;
        $exchange_rate->leverage_buy = $request->leverage_buy;
        $exchange_rate->leverage_sell = $request->leverage_sell;
        $exchange_rate->margin_percent = $request->margin_percent;
        
        if(isset($request->funding_start)) {
            $reserveDate = str_replace('-', '', $request->funding_start);
            $exchange_rate->funding_start = $reserveDate;
        } else {
            $exchange_rate->funding_start = 0;
        }

        $exchange_rate->funding_interval = $request->funding_interval;
        $exchange_rate->funding_rate = $request->funding_rate;
        
        $exchange_rate->save();

        Session::flash('type', 'success'); 
        Session::flash('msg', 'Exchange Rate Successfully Created');
        if ($request->submitBtn === "Save") {
           return redirect('exchange_rates/'. $exchange_rate->rate_id . '/edit');
        }else{
           return redirect('exchange_rates');
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

        if($this->permissionDetails('Exchange_rate','manage')){
            
            $exchange_rate = Exchange_rate::findOrfail($id);
            $exchanges = Exchange::
                        select('identity_exchange.identity_code as exchange_code','identity_exchange.identity_name as exchange_name','identity_exchange.identity_website as exchange_website','exchange.*')
                        ->join('identity_exchange','identity_exchange.identity_id','=','exchange.identity_id')
                        ->get();
            $assets = Asset::select('identity_asset.identity_name as asset_name','asset.asset_id')
                ->join('identity_asset','identity_asset.identity_id','=','asset.identity_id')
                ->where('identity_asset.identity_id','!=',0)
                ->get();
        
            return view('exchange_rate.edit',compact('exchange_rate','assets','exchanges'));

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
        $exchange_rate = Exchange_rate::findOrfail($id);
    	
        $exchange_rate->exchange_id = $request->exchange_id;
        $exchange_rate->base_currency_id = $request->base_currency_id;
        $exchange_rate->quote_currency_id = $request->quote_currency_id;
        $exchange_rate->volume_currency_id = $request->volume_currency_id;
        $exchange_rate->level_margin_call = $request->level_margin_call;
        $exchange_rate->level_margin_liquidation = $request->level_margin_liquidation;
        $exchange_rate->leverage_buy = $request->leverage_buy;
        $exchange_rate->leverage_sell = $request->leverage_sell;
        $exchange_rate->margin_percent = $request->margin_percent;

        if(isset($request->funding_start)) {
            $reserveDate = str_replace('-', '', $request->funding_start);
            $exchange_rate->funding_start = $reserveDate;
        } else {
            $exchange_rate->funding_start = 0;
        }

        $exchange_rate->funding_interval = $request->funding_interval;
        $exchange_rate->funding_rate = $request->funding_rate;
        
        $exchange_rate->save();

        Session::flash('type', 'success'); 
        Session::flash('msg', 'Exchange Rate Successfully Created');
        if ($request->submitBtn === "Save") {
           return redirect('exchange_rates/'. $exchange_rate->rate_id . '/edit');
        }else{
           return redirect('exchange_rates');
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
     	
        if($this->permissionDetails('Exchange_rate','delete')){
            $exchange_rate = Exchange_rate::findOrfail($id);
            $exchange_rate->delete();
            Session::flash('type', 'success'); 
            Session::flash('msg', 'Exchange Rate Successfully Deleted');
            return redirect('exchange_rates');
        }else{
            return Redirect::back()->with('message', 'You are not authorized to use this functionality!');
        }
    }
}
