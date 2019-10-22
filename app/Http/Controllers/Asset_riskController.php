<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\App;
use Illuminate\Http\Request;
use App\Http\Controllers\PermissionsController;
use App\Helpers\ConnectionManager;
use App\Asset_risk;
use App\Asset;
use App\Account;
use Amranidev\Ajaxis\Ajaxis;
use App\Http\Controllers\ProductApprovalController;
use App\Http\Traits\PermissionTrait;

use URL;
use Session;
use DB;
use Redirect;


/**
 * Class Asset_riskController.
 *
 * @author  The scaffold-interface created at 2018-02-17 04:20:55pm
 * @link  https://github.com/amranidev/scaffold-interface
 */
class Asset_riskController extends PermissionsController
{
    use PermissionTrait;

    public function __construct()
    {
        parent::__construct();

        $connectionStatus = ConnectionManager::setDbConfig('Asset_risk');

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
        if($this->permissionDetails('Asset_risk','access')){

            $where = array();
            $permissions = $this->getPermission("Asset_risk");
            
            if($this->merchantId == 0){
                //$where['merchant_type.merchant_root_id'] = $merchantType;
            }else{
                if($this->roleId == 4){
                    $where['asset_risk.merchant_id'] = $this->merchantId;
                }else{
                    $where['asset_risk.merchant_id'] = $this->merchantId;
                    $where['asset_risk.location_id'] = $this->locationId;  
                }
            }
            
            $asset_risks = Asset_risk::
                            distinct()
                            ->select('identity_asset.identity_name as asset_name','identity_account.identity_name as account_name','asset_risk.*')
                            ->join('asset','asset_risk.asset_id','=','asset.asset_id')
                            ->join('account','asset_risk.account_id','=','account.account_id')
                            ->join('identity_asset','asset.identity_id','=','identity_asset.identity_id')
                            ->join('identity_account','identity_account.identity_id','=','account.identity_id')
                            ->where(function($q) use ($where){
                                foreach($where as $key => $value){
                                    $q->where($key, '=', $value);
                                }
                            })->get();

            return view('asset_risk.index',compact('asset_risks','permissions'));
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
        if($this->permissionDetails('Asset_risk','add')){

            $assets = Asset::select('identity_asset.identity_name as asset_name','asset.asset_id')
                                ->join('identity_asset','identity_asset.identity_id','=','asset.identity_id')
                                ->get();

            $accounts = Account::select('account.*','identity_account.identity_code as account_code','identity_account.identity_name as account_name')
                        ->join('identity_account','identity_account.identity_id','=','account.identity_id')
                        ->get();
        
            return view('asset_risk.create',compact('assets','accounts'));

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

        $asset_risk = new Asset_risk();

        $asset_risk->asset_id = $request->asset_id;
        $asset_risk->account_id = $request->account_id;
        if(isset($request->risk_date)) {
            $reserveDate = str_replace('-', '', $request->risk_date);
            $asset_risk->risk_date = $reserveDate;
        } else {
             $asset_risk->risk_date = 0;
        }
        $asset_risk->asset_quantity = $request->asset_quantity;
        $asset_risk->asset_price_average = $request->asset_price_average;
        
        $asset_risk->save();

        Session::flash('type', 'success'); 
        Session::flash('msg', 'Asset Risk Successfully Created');
        if ($request->submitBtn === "Save") {
           return redirect('asset_risk/'. $asset_risk->risk_id . '/edit');
        }else{
           return redirect('asset_risk');
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
        
        if($this->permissionDetails('Asset_risk','manage')){    
            $asset_risk = Asset_risk::findOrfail($id);
            $assets = Asset::select('identity_asset.identity_name as asset_name','asset.asset_id')
                                    ->join('identity_asset','identity_asset.identity_id','=','asset.identity_id')
                                    ->get();
            $accounts = Account::select('account.*','identity_account.identity_code as account_code','identity_account.identity_name as account_name')
                        ->join('identity_account','identity_account.identity_id','=','account.identity_id')
                        ->get();

            return view('asset_risk.edit',compact('asset_risk','assets','accounts'));
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
        $asset_risk = Asset_risk::findOrfail($id);
    	
        $asset_risk->asset_id = $request->asset_id;
        $asset_risk->account_id = $request->account_id;
        if(isset($request->risk_date)) {
            $reserveDate = str_replace('-', '', $request->risk_date);
            $asset_risk->risk_date = $reserveDate;
        } else {
             $asset_risk->risk_date = 0;
        }
        $asset_risk->asset_quantity = $request->asset_quantity;
        $asset_risk->asset_price_average = $request->asset_price_average;
        
        $asset_risk->save();

        Session::flash('type', 'success'); 
        Session::flash('msg', 'Asset Risk Successfully Created');
        if ($request->submitBtn === "Save") {
           return redirect('asset_risk/'. $asset_risk->risk_id . '/edit');
        }else{
           return redirect('asset_risk');
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
     	
        if($this->permissionDetails('Asset_risk','delete')){
            $asset_risk = Asset_risk::findOrfail($id);
            $asset_risk->delete();
            Session::flash('type', 'success'); 
            Session::flash('msg', 'Asset Risk Successfully Deleted');
            return redirect('asset_risk');
        }else{
            return Redirect::back()->with('message', 'You are not authorized to use this functionality!');
        }
    }
}
