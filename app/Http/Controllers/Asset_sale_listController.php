<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\App;
use Illuminate\Http\Request;
use App\Http\Controllers\PermissionsController;
use App\Helpers\ConnectionManager;
use App\Event;
use App\Asset_sale_list;
use App\Asset;
use Amranidev\Ajaxis\Ajaxis;
use App\Http\Controllers\ProductApprovalController;
use App\Http\Traits\PermissionTrait;

use URL;
use Session;
use DB;
use Redirect;

/**
 * Class Asset_sale_listController.
 *
 * @author  The scaffold-interface created at 2018-02-25 05:27:58pm
 * @link  https://github.com/amranidev/scaffold-interface
 */
class Asset_sale_listController extends PermissionsController
{
    use PermissionTrait;

    public function __construct()
    {
        parent::__construct();

        $connectionStatus = ConnectionManager::setDbConfig('Asset_sale_list');

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
        if($this->permissionDetails('Asset_sale_list','access')){
                       
            $permissions = $this->getPermission("Asset_sale_list");
            
            if($this->merchantId == 0){

                $asset_sale_lists = Asset_sale_list::
                    select('identity_asset.identity_code as asset_symbol','identity_asset.identity_name as asset_name','identity_event.identity_name as event_name','asset_sale_list.*')
                    ->join('asset','asset.asset_id','=','asset_sale_list.asset_id')
                    ->join('identity_asset','identity_asset.identity_id','=','asset.identity_id')
                    ->join('event','event.event_id','=','asset_sale_list.event_id')
                    ->join('identity_event','identity_event.identity_id','=','event.identity_id')
                    ->get();

                return view('asset_sale_list.index',compact('asset_sale_lists','permissions'));
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

        if($this->permissionDetails('Asset_sale_list','add')){

            $assets = Asset::select('identity_asset.identity_name as asset_name','asset.asset_id')
                                ->join('identity_asset','identity_asset.identity_id','=','asset.identity_id')
                                ->get();

            $events = Event::
                    select('event_id','identity_event.identity_name as event_name')
                    ->join('identity_event','identity_event.identity_id','=','event.identity_id')
                    ->get();   

            return view('asset_sale_list.create',compact('assets','events'));
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
        
        if(isset($request->event_id) && count($request->event_id) != 0){

            Asset_sale_list::
                where('asset_id',$request->asset_id)
                ->whereNotIn('event_id',$request->event_id)
                ->delete();


            foreach ($request->events as $value) {

                $event_exist = Asset_sale_list::
                          where('asset_id',$request->asset_id)
                        ->where('event_id',$value['event_id'])
                        ->get()->first();

                if(count($event_exist) == 0){
                    $asset_sale_list = new Asset_sale_list();
                }else{
                    $asset_sale_list = Asset_sale_list::findOrfail($event_exist->list_id);
                }

                $asset_sale_list->asset_id = $request->asset_id;
                $asset_sale_list->event_id = $value['event_id'];
                $asset_sale_list->priority = $value['priority'];
                $asset_sale_list->status = isset($value['status'])?1:0;
                
                $asset_sale_list->save();

            }
        }else{
            Asset_sale_list::
                where('asset_id',$request->asset_id)
                ->delete();
        }

        Session::flash('type', 'success'); 
        Session::flash('msg', 'Asset Category List Successfully Created');
        if ($request->submitBtn === "Save") {
           return redirect('asset_sale_list/'. $asset_sale_list->list_id . '/edit');
        }else{
           return redirect('asset_sale_list');
        }
    }

    

    public function getEvents(Request $request) {
        
        $events = Asset_sale_list::
                        distinct('asset_sale_list.asset_id','asset_sale_list.event_id')
                        ->select('asset_sale_list.*','identity_event.identity_name as event_name')
                        ->join('event','event.event_id','=','asset_sale_list.event_id')
                        ->join('identity_event','identity_event.identity_id','=','event.identity_id')
                        ->where('asset_sale_list.asset_id',$request->asset_id)
                        ->get();
                        
        echo json_encode($events);
    }
}
