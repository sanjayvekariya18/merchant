<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\App;
use Illuminate\Http\Request;
use App\Http\Controllers\PermissionsController;
use App\Helpers\ConnectionManager;
use App\Social_apikeys;
use App\Identity_social;
use App\Connector;
use Amranidev\Ajaxis\Ajaxis;
use App\Http\Traits\PermissionTrait;

use URL;
use Session;
use DB;
use Redirect;

const SOCIAL_IDENTITY_TYPE_ID=13;
const SOCIAL_IDENTITY_TABLE_ID=18;
const SOCIAL_TABLE_ID=17;


/**
 * Class Social_apikeysController.
 *
 * @author  The scaffold-interface created at 2018-03-04 06:06:54pm
 * @link  https://github.com/amranidev/scaffold-interface
 */
class Social_apikeysController extends PermissionsController
{
    use PermissionTrait;

    public function __construct()
    {
        parent::__construct();

        $connectionStatus = ConnectionManager::setDbConfig('Social_apikeys');

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
        if($this->permissionDetails('Social_apikeys','access')){
                       
            $permissions = $this->getPermission("Social_apikeys");
            
            if($this->merchantId == 0){

                $social_apikeys = Social_apikeys::select('social_apikeys.*','identity_social.identity_code as social_code','identity_social.identity_name as social_name','connector.connector_name')
                    ->join('identity_social','identity_social.identity_id','=','social_apikeys.identity_id')
                    ->join('connector','connector.connector_id','=','social_apikeys.connector_id')
                    ->get();

                return view('social_apikeys.index',compact('social_apikeys','permissions'));
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
        if($this->permissionDetails('Social_apikeys','add')){

            $connectors = Connector::All();

            return view('Social_apikeys.create',compact('connectors'));
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

        $social_exist = Identity_social::select('*')
                         ->where('identity_code',$request->social_code)
                         ->get()->first();

        if(count($social_exist) == 0){

            $social_apikeys = new Social_apikeys();
            $identity_social = new Identity_social();
            $message = "Social Successfully Created";

        }else{

            $social_apikeys = Social_apikeys::select('*')
                        ->where('identity_id',$social_exist->identity_id)
                        ->get()->first();

            $identity_social = Identity_social::findOrfail($social_apikeys->identity_id);
            $message = "Social Successfully Updated";
        }
        
        $identity_social->identity_code = $request->social_code;
        $identity_social->identity_name = $request->social_name;
        $identity_social->identity_table_id = SOCIAL_TABLE_ID;
        $identity_social->identity_type_id = SOCIAL_IDENTITY_TYPE_ID;

        $identity_social->save();
        $identityID = $identity_social->identity_id;
        
        $social_apikeys->identity_id = $identityID;
        $social_apikeys->identity_table_id = SOCIAL_IDENTITY_TABLE_ID;
        $social_apikeys->connector_id = $request->connector_id;
        $social_apikeys->connector_key = $request->connector_key;
        $social_apikeys->connector_passcode = $request->connector_passcode;
        $social_apikeys->save();

        Session::flash('type', 'success'); 
        Session::flash('msg',$message);
        if ($request->submitBtn === "Save") {
           return redirect('social_apikeys/'. $social_apikeys->social_id . '/edit');
        }else{
           return Redirect::back();
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
        if($this->permissionDetails('Social_apikeys','manage')){
            
            $connectors = Connector::All();

            $social_apikey = Social_apikeys::select('social_apikeys.*','identity_social.identity_code as social_code','identity_social.identity_name as social_name')
                    ->join('identity_social','identity_social.identity_id','=','social_apikeys.identity_id')
                    ->where('social_id',$id)
                    ->get()->first();
        
            return view('social_apikeys.edit',compact('social_apikey','connectors'));

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

        $social_identity_exist = Identity_social::select('*')
                         ->where('identity_code',$request->social_code)
                         ->where('identity_id','!=',$request->identity_id)
                         ->toSql();

        if(count($social_identity_exist) == 0){

        $social_apikeys = Social_apikeys::findOrfail($request->social_id);
        $identity_social = Identity_social::findOrfail($request->identity_id);
        $message = "Social Successfully Updated";
        $type = "success";
        
        
        $identity_social->identity_code = $request->social_code;
        $identity_social->identity_name = $request->social_name;

        $identity_social->save();
        
        $social_apikeys->connector_id = $request->connector_id;
        $social_apikeys->connector_key = $request->connector_key;
        $social_apikeys->connector_passcode = $request->connector_passcode;
        $social_apikeys->save();

    }else{

        $message = "Social Code Reserve By Another Social.";
        $type = "error";
    }

        Session::flash('type', $type); 
        Session::flash('msg',$message);
        if ($request->submitBtn === "Save") {
           return redirect('social_apikeys/'. $request->social_id . '/edit');
        }else{
           return redirect('social_apikeys');
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
     	if($this->permissionDetails('Social_apikeys','delete')){
            $social_apikeys = Social_apikeys::findOrfail($id);
            $identity_social = Identity_social::findOrfail($social_apikeys->identity_id);

            $social_apikeys->delete();
            $identity_social->delete();
            Session::flash('type', 'success'); 
            Session::flash('msg', 'Social Successfully Deleted');
            return redirect('social_apikeys');
        }else{
            return Redirect::back()->with('message', 'You are not authorized to use this functionality!');
        }
    }
}
