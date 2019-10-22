<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\App;
use Illuminate\Http\Request;
use App\Http\Controllers\PermissionsController;
use App\Helpers\ConnectionManager;
use App\Exchange_asset_pair;
use App\Exchange;
use App\Asset;
use Amranidev\Ajaxis\Ajaxis;
use App\Http\Traits\PermissionTrait;
use URL;
use Session;
use DB;
use Redirect;

/**
 * Class Exchange_asset_pairController.
 *
 */
class Exchange_asset_pairController extends PermissionsController
{
    use PermissionTrait;

    public function __construct()
    {
        parent::__construct();

        $connectionStatus = ConnectionManager::setDbConfig('Exchange_asset_pair');

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
        if($this->permissionDetails('Exchange_asset_pair','access')) {
            $permissions = $this->getPermission("Exchange_asset_pair");
            $exchange_asset_pairs = Exchange_asset_pair::distinct()->select('exchange_asset_pairs.*', 
                'identity_exchange.identity_name as exchange_name',
                'asset_from_identity.identity_code as asset_from_code',
                'asset_into_identity.identity_code as asset_into_code')
                ->join('exchange','exchange.exchange_id','=','exchange_asset_pairs.exchange_id')
                ->join('identity_exchange','exchange.identity_id','=','identity_exchange.identity_id')
                ->leftjoin('asset as asset_from','asset_from.asset_id','exchange_asset_pairs.asset_from_id')
                ->leftjoin('identity_asset as asset_from_identity','asset_from_identity.identity_id','asset_from.identity_id')
                ->leftjoin('asset as asset_into','asset_into.asset_id','exchange_asset_pairs.asset_into_id')
                ->leftjoin('identity_asset as asset_into_identity','asset_into_identity.identity_id','asset_into.identity_id')
                ->get();
            return view('exchange_asset_pair.index',compact('exchange_asset_pairs','permissions'));
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
        if($this->permissionDetails('Exchange_asset_pair','add')) {
            $exchanges = Exchange::select('identity_exchange.identity_code as exchange_code','identity_exchange.identity_name as exchange_name','identity_exchange.identity_website as exchange_website','exchange.*')
                ->join('identity_exchange','identity_exchange.identity_id','=','exchange.identity_id')
                ->get();
            $assets = Asset::
                select('asset.asset_id','identity_asset.identity_name','identity_asset.identity_code') 
                ->distinct()
                ->leftjoin('identity_asset','asset.identity_id','identity_asset.identity_id')
                ->where('identity_asset.identity_id','!=',0)
                ->get();
            return view('exchange_asset_pair.create',compact('exchanges','assets'));
        } else {
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
        $exchange_asset_pairs = Exchange_asset_pair::select('exchange_asset_pairs.*')
            ->where('exchange_asset_pairs.exchange_id','=', $request->exchange_id)
            ->where('exchange_asset_pairs.asset_from_id','=', $request->asset_from_id)
            ->where('exchange_asset_pairs.asset_into_id','=', $request->asset_into_id)->get()->toArray();
        if(!isset($exchange_asset_pairs[0]['pairs_id'])) {
            $exchange_asset_pair = new Exchange_asset_pair();

            $exchange_asset_pair->exchange_id = $request->exchange_id;

            $exchange_asset_pair->asset_from_id = $request->asset_from_id;

            $exchange_asset_pair->asset_into_id = $request->asset_into_id;

            $exchange_asset_pair->priority = $request->priority;

            $exchange_asset_pair->enable = (!$request->enable) ? 0 : $request->enable;

            $exchange_asset_pair->save();

            $assetPairId = $exchange_asset_pair->pairs_id;
            $exchange_asset_pair_data = Exchange_asset_pair::findOrfail($assetPairId);
            $exchange_asset_pair_data->pairs_leg = $assetPairId;
            $exchange_asset_pair_data->save();

            if($assetPairId) {
                $exchange_asset_pairs_inverse = Exchange_asset_pair::select('exchange_asset_pairs.*')
                    ->where('exchange_asset_pairs.exchange_id','=', $request->exchange_id)
                    ->where('exchange_asset_pairs.asset_from_id','=', $request->asset_into_id)
                    ->where('exchange_asset_pairs.asset_into_id','=', $request->asset_from_id)->get()->toArray();
                if(!isset($exchange_asset_pairs_inverse[0]['pairs_id'])) {
                    $asset_pair_reverse = new Exchange_asset_pair();

                    $asset_pair_reverse->pairs_leg = $assetPairId;

                    $asset_pair_reverse->exchange_id = $request->exchange_id;

                    $asset_pair_reverse->asset_from_id = $request->asset_into_id;

                    $asset_pair_reverse->asset_into_id = $request->asset_from_id;

                    $asset_pair_reverse->priority = $request->priority;

                    $asset_pair_reverse->enable = (!$request->enable) ? 0 : $request->enable;

                    $asset_pair_reverse->save();
                }
                else {
                    $assetPairInverseId = $exchange_asset_pairs_inverse[0]['pairs_id'];
                    $exchange_asset_pair_data = Exchange_asset_pair::findOrfail($assetPairInverseId);
                    $exchange_asset_pair_data->pairs_leg = $assetPairInverseId;
                    $exchange_asset_pair_data->save();
                }
            }
        } else {
            $assetPairId = $exchange_asset_pairs[0]['pairs_id'];
            $exchange_asset_pairs_inverse = Exchange_asset_pair::select('exchange_asset_pairs.*')
            ->where('exchange_asset_pairs.exchange_id','=', $request->exchange_id)
            ->where('exchange_asset_pairs.asset_from_id','=', $request->asset_into_id)
            ->where('exchange_asset_pairs.asset_into_id','=', $request->asset_from_id)->get()->toArray();
            if(!isset($exchange_asset_pairs_inverse[0]['pairs_id'])) {
                $asset_pair_reverse = new Exchange_asset_pair();

                $asset_pair_reverse->pairs_leg = $assetPairId;

                $asset_pair_reverse->exchange_id = $request->exchange_id;

                $asset_pair_reverse->asset_from_id = $request->asset_into_id;

                $asset_pair_reverse->asset_into_id = $request->asset_from_id;

                $asset_pair_reverse->priority = $request->priority;

                $asset_pair_reverse->enable = (!$request->enable) ? 0 : $request->enable;

                $asset_pair_reverse->save();
            }
            else {
                $assetPairInverseId = $exchange_asset_pairs_inverse[0]['pairs_id'];
                $exchange_asset_pair_data = Exchange_asset_pair::findOrfail($assetPairInverseId);
                $exchange_asset_pair_data->pairs_leg = $assetPairInverseId;
                $exchange_asset_pair_data->save();
            }
        }

        Session::flash('type', 'success');
        Session::flash('msg', 'Exchange Asset Pair Successfully Inserted');

        if ($request->submitBtn === "Save") {
           return redirect('exchange_asset_pair/'. $exchange_asset_pair->pairs_id . '/edit');
        } else {
           return redirect('exchange_asset_pair');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param    \Illuminate\Http\Request  $request
     * @param    int  $id
     * @return  \Illuminate\Http\Response
     */
    public function show($id,Request $request)
    {
        $title = 'Show - exchange_asset_pair';

        if($request->ajax())
        {
            return URL::to('exchange_asset_pair/'.$id);
        }

        $exchange_asset_pair = Exchange_asset_pair::findOrfail($id);
        return view('exchange_asset_pair.show',compact('title','exchange_asset_pair'));
    }

    /**
     * Show the form for editing the specified resource.
     * @param    \Illuminate\Http\Request  $request
     * @param    int  $id
     * @return  \Illuminate\Http\Response
     */
    public function edit($id,Request $request)
    {
        if($this->permissionDetails('Exchange_asset_pair','manage')) {
            $exchange_asset_pair = Exchange_asset_pair::findOrfail($id);
            $exchanges = Exchange::select('identity_exchange.identity_code as exchange_code','identity_exchange.identity_name as exchange_name','identity_exchange.identity_website as exchange_website','exchange.*')
                ->join('identity_exchange','identity_exchange.identity_id','=','exchange.identity_id')
                ->get();
            $assets = Asset::
                select('asset.asset_id','identity_asset.identity_name','identity_asset.identity_code') 
                ->distinct()
                ->leftjoin('identity_asset','asset.identity_id','identity_asset.identity_id')
                ->where('identity_asset.identity_id','!=',0)
                ->get();
            return view('exchange_asset_pair.edit',compact('exchanges','exchange_asset_pair','assets'));
        } else {
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
        $exchange_asset_pair = Exchange_asset_pair::findOrfail($id);
    	
        $exchange_asset_pair->exchange_id = $request->exchange_id;
        
        $exchange_asset_pair->asset_from_id = $request->asset_from_id;
        
        $exchange_asset_pair->asset_into_id = $request->asset_into_id;
        
        $exchange_asset_pair->priority = $request->priority;
        
        $exchange_asset_pair->enable = (!$request->enable) ? 0 : $request->enable;
        
        $exchange_asset_pair->save();

        Session::flash('type', 'success');
        Session::flash('msg', 'Exchange Asset Pair Successfully Updated');

        if ($request->submitBtn === "Save") {
           return redirect('exchange_asset_pair/'. $exchange_asset_pair->pairs_id . '/edit');
        } else {
           return redirect('exchange_asset_pair');
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
        if($this->permissionDetails('Exchange_asset_pair','delete')) {
         	$exchange_asset_pair = Exchange_asset_pair::findOrfail($id);
         	$exchange_asset_pair->delete();
            Session::flash('type', 'success');
            Session::flash('msg', 'Exchange Asset Pair Successfully Deleted');
            return redirect('exchange_asset_pair');
        } else {
            return Redirect::back()->with('message', 'You are not authorized to use this functionality!');
        }
    }
}
