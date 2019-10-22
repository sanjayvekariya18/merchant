<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\App;
use Illuminate\Http\Request;
use App\Http\Controllers\PermissionsController;
use App\Helpers\ConnectionManager;
use App\Asset_social_list;
use App\Asset;
use App\Social_apikeys;
use App\Social;
use Amranidev\Ajaxis\Ajaxis;
use App\Http\Controllers\ProductApprovalController;
use App\Http\Traits\PermissionTrait;
use Session;
use DB;
use Redirect;
use URL;

/**
 * Class Asset_social_listController.
 *
 * @author  The scaffold-interface created at 2018-02-28 06:08:46pm
 * @link  https://github.com/amranidev/scaffold-interface
 */
class Asset_social_listController extends PermissionsController
{
    use PermissionTrait;

    public function __construct()
    {
        parent::__construct();

        $connectionStatus = ConnectionManager::setDbConfig('Asset_social_list');

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
        if($this->permissionDetails('Asset_social_list','access')) {
            $permissions = $this->getPermission("Asset_social_list");
            $asset_social_lists = Asset_social_list::distinct()->select('asset_social_list.*', 'identity_asset.identity_name as asset_name','identity_social.identity_name as social_name')
                ->join('asset','asset.asset_id','asset_social_list.asset_id')
                ->join('identity_asset','identity_asset.identity_id','asset.identity_id')
                ->join('social','social.social_id','asset_social_list.social_id')
                ->join('identity_social','identity_social.identity_id','social.identity_id')
                ->get();
            return view('asset_social_list.index',compact('asset_social_lists','permissions'));
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
        if($this->permissionDetails('Asset_social_list','add')) {
            $assets = Asset::select('identity_asset.identity_name as asset_name','asset.asset_id',
                'identity_asset.identity_code as asset_code')
                ->join('identity_asset','identity_asset.identity_id','=','asset.identity_id')
                ->where('identity_asset.identity_id','!=',0)
                ->get();

            $socials = Social::select('social_id',
                'identity_social.identity_code as social_code','identity_social.identity_name as social_name')
                ->join('identity_social','identity_social.identity_id','=','social.identity_id')
                ->get();    

            return view('asset_social_list.create',compact('assets','socials'));
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

        if(isset($request->social_id) && count($request->social_id) != 0){

            Asset_social_list::
                where('asset_id',$request->asset_id)
                ->whereNotIn('social_id',$request->social_id)
                ->delete();


            foreach ($request->socials as $value) {

                $social_exist = Asset_social_list::
                        where('asset_id',$request->asset_id)
                        ->where('social_id',$value['social_id'])
                        ->get()->first();

                if(count($social_exist) == 0){
                    $asset_social_list = new Asset_social_list();
                }else{
                    $asset_social_list = Asset_social_list::findOrfail($social_exist->list_id);
                }

                
                $asset_social_list->asset_id = $request->asset_id;
                $asset_social_list->social_id = $value['social_id'];
                $asset_social_list->social_url = $value['social_url'];
                $asset_social_list->priority = $value['priority'];
                $asset_social_list->status = isset($value['status'])?1:0;

                
                                
                $asset_social_list->save();

            }
        }else{
            Asset_social_list::
                where('asset_id',$request->asset_id)
                ->delete();
        } 

        Session::flash('type', 'success');
        Session::flash('msg', 'Asset Social List Successfully Inserted');

        if ($request->submitBtn === "Save") {
           return redirect('asset_social_list/'. $asset_social_list->list_id . '/edit');
        } else {
           return redirect('asset_social_list');
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
        $title = 'Show - asset_social_list';

        if($request->ajax())
        {
            return URL::to('asset_social_list/'.$id);
        }

        $asset_social_list = Asset_social_list::findOrfail($id);
        return view('asset_social_list.show',compact('title','asset_social_list'));
    }

    /**
     * Show the form for editing the specified resource.
     * @param    \Illuminate\Http\Request  $request
     * @param    int  $id
     * @return  \Illuminate\Http\Response
     */
    public function edit($id,Request $request)
    {
        if($this->permissionDetails('Asset_social_list','manage')) {
            $asset_social_list = Asset_social_list::findOrfail($id);
            $assets = Asset::select('identity_asset.identity_name as asset_name','asset.asset_id',
                'identity_asset.identity_code as asset_code')
                ->join('identity_asset','identity_asset.identity_id','=','asset.identity_id')
                ->where('identity_asset.identity_id','!=',0)
                ->get();
            return view('asset_social_list.edit',compact('assets','asset_social_list'  ));
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
        $asset_social_list = Asset_social_list::findOrfail($id);
    	
        $asset_social_list->asset_id = $request->asset_id;
        
        $asset_social_list->social_id = $request->social_id;
        
        $asset_social_list->social_url = $request->social_url;
        
        $asset_social_list->priority = $request->priority;
        
        $asset_social_list->save();

        Session::flash('type', 'success');
        Session::flash('msg', 'Asset Social List Successfully Updated');

        if ($request->submitBtn === "Save") {
           return redirect('asset_social_list/'. $asset_social_list->list_id . '/edit');
        } else {
           return redirect('asset_social_list');
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
        if($this->permissionDetails('Asset_social_list','delete')) {
         	$asset_social_list = Asset_social_list::findOrfail($id);
         	$asset_social_list->delete();
            Session::flash('type', 'success');
            Session::flash('msg', 'Asset Social List Successfully Deleted');
            return redirect('asset_social_list');
        } else {
            return Redirect::back()->with('message', 'You are not authorized to use this functionality!');
        }
    }

    public function getSocials(Request $request)
    {
            $socials = Asset_social_list::distinct()
                ->select('asset_social_list.*', 'identity_social.identity_name as social_name')
                ->join('social','social.social_id','asset_social_list.social_id')
                ->join('identity_social','identity_social.identity_id','social.identity_id')
                ->where("asset_social_list.asset_id",$request->asset_id)
                ->get();

            echo json_encode($socials);    
    }
}
