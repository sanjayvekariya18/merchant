<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\App;
use Illuminate\Http\Request;
use App\Http\Controllers\PermissionsController;
use App\Helpers\ConnectionManager;
use App\Asset_pnl;
use App\Asset;
use App\Merchant;
use App\Account;
use Amranidev\Ajaxis\Ajaxis;
use App\Http\Controllers\ProductApprovalController;
use App\Http\Traits\PermissionTrait;

use URL;
use Session;
use DB;
use Redirect;

/**
 * Class Asset_pnlController.
 *
 */
class Asset_pnlController extends PermissionsController
{
    use PermissionTrait;

    public function __construct()
    {
        parent::__construct();

        $connectionStatus = ConnectionManager::setDbConfig('Asset_pnl');

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
        if($this->permissionDetails('Asset_pnl','access')){
                       
            $permissions = $this->getPermission("Asset_pnl");
            
            $asset_pnls = Asset_pnl::distinct()
                ->select('identity_asset.identity_name as asset_name','identity_account.identity_name as account_name','asset_pnl.*')
                ->join('asset','asset_pnl.asset_id','=','asset.asset_id')
                ->join('identity_asset','asset.identity_id','=','identity_asset.identity_id')
                ->join('account','asset_pnl.account_id','=','account.account_id')
                ->join('identity_account','identity_account.identity_id','=','account.identity_id')
                ->get();
            return view('asset_pnl.index',compact('asset_pnls','permissions'));
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
        if($this->permissionDetails('Asset_pnl','add')) {
            $assets = Asset::select('identity_asset.identity_name as asset_name','asset.asset_id')
                ->join('identity_asset','identity_asset.identity_id','=','asset.identity_id')
                ->get();
            $accounts = Account::select('account.*','identity_account.identity_code as account_code','identity_account.identity_name as account_name')
                ->join('identity_account','identity_account.identity_id','=','account.identity_id')
                ->get();
            return view('asset_pnl.create',compact('assets','accounts'));

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
        $asset_pnl = new Asset_pnl();
        $asset_pnl->asset_id = $request->asset_id;
        $asset_pnl->account_id = $request->account_id;
        if(isset($request->pnl_date)) {
            $reserveDate = str_replace('-', '', $request->pnl_date);
            $asset_pnl->pnl_date = $reserveDate;
        } else {
             $asset_pnl->pnl_date = 0;
        }
        $asset_pnl->total_amount = $request->total_amount;
        $asset_pnl->trade_fees = $request->trade_fees;
        $asset_pnl->quantity_closed = $request->quantity_closed;
        $asset_pnl->quantity_open = $request->quantity_open;
        $asset_pnl->position_unrealized = $request->position_unrealized;
        $asset_pnl->pnl_margin = $request->pnl_margin;
        $asset_pnl->pnl_balance_remaining = $request->pnl_balance_remaining;
        $asset_pnl->trade_long = $request->trade_long;
        $asset_pnl->trade_short = $request->trade_short;
        $asset_pnl->save();

        Session::flash('type', 'success'); 
        Session::flash('msg', 'Asset Pnl Successfully Created');
        if ($request->submitBtn === "Save") {
           return redirect('asset_pnl/'. $asset_pnl->pnl_id . '/edit');
        }else{
           return redirect('asset_pnl');
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
        if($this->permissionDetails('Asset_pnl','manage')) {
            $asset_pnl = Asset_pnl::findOrfail($id);
            $assets = Asset::select('identity_asset.identity_name as asset_name','asset.asset_id')
                ->join('identity_asset','identity_asset.identity_id','=','asset.identity_id')
                ->get();
                                    
            $accounts = Account::select('account.*','identity_account.identity_code as account_code','identity_account.identity_name as account_name')
                ->join('identity_account','identity_account.identity_id','=','account.identity_id')
                ->get();
            return view('asset_pnl.edit',compact('asset_pnl','assets','accounts'));
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
        $asset_pnl = Asset_pnl::findOrfail($id);
    	
        $asset_pnl->asset_id = $request->asset_id;
        $asset_pnl->account_id = $request->account_id;
        if(isset($request->pnl_date)) {
            $reserveDate = str_replace('-', '', $request->pnl_date);
            $asset_pnl->pnl_date = $reserveDate;
        } else {
             $asset_pnl->pnl_date = 0;
        }
        $asset_pnl->total_amount = $request->total_amount;
        $asset_pnl->trade_fees = $request->trade_fees;
        $asset_pnl->quantity_closed = $request->quantity_closed;
        $asset_pnl->quantity_open = $request->quantity_open;
        $asset_pnl->position_unrealized = $request->position_unrealized;
        $asset_pnl->pnl_margin = $request->pnl_margin;
        $asset_pnl->pnl_balance_remaining = $request->pnl_balance_remaining;
        $asset_pnl->trade_long = $request->trade_long;
        $asset_pnl->trade_short = $request->trade_short;
        $asset_pnl->save();

        Session::flash('type', 'success'); 
        Session::flash('msg', 'Asset Pnl Successfully Created');
        if ($request->submitBtn === "Save") {
           return redirect('asset_pnl/'. $asset_pnl->pnl_id . '/edit');
        }else{
           return redirect('asset_pnl');
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
        if($this->permissionDetails('Asset_pnl','delete')){
            $asset_pnl = Asset_pnl::findOrfail($id);
            $asset_pnl->delete();
            Session::flash('type', 'success'); 
            Session::flash('msg', 'Asset Pnl Successfully Deleted');
            return redirect('asset_pnl');
        }else{
            return Redirect::back()->with('message', 'You are not authorized to use this functionality!');
        }
    }
}
