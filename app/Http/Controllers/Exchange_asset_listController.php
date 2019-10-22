<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\App;
use Illuminate\Http\Request;
use App\Http\Controllers\PermissionsController;
use App\Helpers\ConnectionManager;
use App\Exchange_asset_list;
use App\Exchange_language_list;
use App\Exchange;
use App\Asset;
use Amranidev\Ajaxis\Ajaxis;
use App\Http\Traits\PermissionTrait;
use Session;
use DB;
use Redirect;
use URL;

/**
 * Class Exchange_asset_listController.
 *
 */
class Exchange_asset_listController extends PermissionsController
{
    use PermissionTrait;

    public function __construct()
    {
        parent::__construct();

        $connectionStatus = ConnectionManager::setDbConfig('Exchange_asset_list');

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
        if($this->permissionDetails('Exchange_asset_list','access') || $this->permissionDetails('Exchange_language_list','access')) {

            $permissions_asset = $this->getPermission("Exchange_asset_list");
            $permissions_language = $this->getPermission("Exchange_language_list");

            $exchange_asset_lists = Exchange_asset_list::distinct()->select('exchange_asset_list.*',
                'identity_exchange.identity_name as exchange_name',
                'identity_asset.identity_name as asset_name'
                )
                ->join('exchange','exchange.exchange_id','=','exchange_asset_list.exchange_id')
                ->join('identity_exchange','exchange.identity_id','=','identity_exchange.identity_id')
                ->leftjoin('asset','asset.asset_id','exchange_asset_list.asset_id')
                ->leftjoin('identity_asset','identity_asset.identity_id','asset.identity_id')->get();

            $exchange_language_lists = Exchange_language_list::distinct()->select('exchange_language_list.*',
                'identity_exchange.identity_name as exchange_name',
                'languages.language_name'
                )
                ->join('exchange','exchange.exchange_id','=','exchange_language_list.exchange_id')
                ->join('identity_exchange','exchange.identity_id','=','identity_exchange.identity_id')
                ->leftjoin('languages','languages.language_id','exchange_language_list.language_id')->get();    

            return view('exchange_asset_list.index',compact('exchange_asset_lists','exchange_language_lists','permissions_asset','permissions_language'));
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
        if($this->permissionDetails('Exchange_asset_list','add')) {
            $exchanges = Exchange::select('identity_exchange.identity_code as exchange_code','identity_exchange.identity_name as exchange_name','identity_exchange.identity_website as exchange_website','exchange.*')
                ->join('identity_exchange','identity_exchange.identity_id','=','exchange.identity_id')
                ->get();
            $assets = Asset::select('identity_asset.identity_name as asset_name','asset.asset_id',
                'identity_asset.identity_code as asset_code')
                ->join('identity_asset','identity_asset.identity_id','=','asset.identity_id')
                ->where('identity_asset.identity_id','!=',0)
                ->get();
            
            return view('exchange_asset_list.create',compact('exchanges','assets'));
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

        if(isset($request->asset_id) && count($request->asset_id) !== 0){

            Exchange_asset_list::
                where('exchange_id',$request->exchange_id)
                ->whereNotIn('asset_id',$request->asset_id)
                ->delete();


            foreach ($request->assets as $value) {

                $asset_exist = Exchange_asset_list::
                          where('exchange_id',$request->exchange_id)
                        ->where('asset_id',$value['asset_id'])
                        ->get()->first();

                if(count($asset_exist) === 0){
                    $exchange_asset_list = new Exchange_asset_list();
                }else{
                    $exchange_asset_list = Exchange_asset_list::findOrfail($asset_exist->list_id);
                }

                $exchange_asset_list->exchange_id = $request->exchange_id;
                $exchange_asset_list->asset_id = $value['asset_id'];
                $exchange_asset_list->asset_code = $value['asset_code'];
                $exchange_asset_list->priority = $value['priority'];
                $exchange_asset_list->status = isset($value['status'])?1:0;
                
                $exchange_asset_list->save();

            }
        }else{
            Exchange_asset_list::
                where('exchange_id',$request->exchange_id)
                ->delete();
        }

        Session::flash('type', 'success');
        Session::flash('msg', 'Exchange Asset List Successfully Inserted');

        if ($request->submitBtn === "Save") {
           return redirect('exchange_asset_list/'. $exchange_asset_list->list_id . '/edit');
        } else {
           return redirect('exchange_asset_list');
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
        if($this->permissionDetails('Exchange_asset_list','delete')) {
         	$exchange_asset_list = Exchange_asset_list::findOrfail($id);
         	$exchange_asset_list->delete();
            Session::flash('type', 'success');
            Session::flash('msg', 'Exchange Asset List Successfully Deleted');
            return redirect('exchange_asset_list');
        } else {
            return Redirect::back()->with('message', 'You are not authorized to use this functionality!');
        }
    }

    public function getExchangeAsset(Request $request) {
        if($request->exchange_id) {
            
            $exchangeAssets = Exchange_asset_list::select('exchange_asset_list.*','identity_asset.identity_name as asset_name','identity_asset.identity_code as asset_code','exchange_asset_list.asset_code as new_asset_code')
                ->join('asset','exchange_asset_list.asset_id','=','asset.asset_id')
                ->join('identity_asset','identity_asset.identity_id','=','asset.identity_id')
                ->where('exchange_asset_list.exchange_id','=', $request->exchange_id)
                ->get();
        }

        echo json_encode($exchangeAssets);
    }
    
}
