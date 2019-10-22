<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use App\Http\Controllers\PermissionsController;
use Amranidev\Ajaxis\Ajaxis;
use App\Http\Traits\PermissionTrait;
use App\Helpers\ConnectionManager;
use App\Limits_apikey;
use URL;
use Session;
use DB;
use Redirect;
use Hase;


/**
 * Class SocialController.
 *
 * @author  The scaffold-interface created at 2018-03-04 06:06:54pm
 * @link  https://github.com/amranidev/scaffold-interface
 */
class LimitsApikeyController extends PermissionsController
{
    use PermissionTrait;

    public function __construct()
    {
        parent::__construct();

        $connectionStatus = ConnectionManager::setDbConfig('Limits_apikey');
        if ($connectionStatus['type'] === "error") {
            Session::flash('type', $connectionStatus['type']);
            Session::flash('msg', $connectionStatus['message']);
            return Redirect::back()->send();
        }
    }
    
    public function index()
    {
        if($this->permissionDetails('Limits_apikey','access')){

            $result = PermissionTrait::checkIpstackApi();           
            $permissions = $this->getPermission("Limits_apikey");

            if ($result['type'] === "error") {
                Session::flash('type', $result['type']);
                Session::flash('msg', $result['message']); 
            }
            return view('limits_apikey.index',compact('permissions'));
        }else{
            return Redirect::back()->with('message', 'You are not authorized to use this functionality!');
        }
    }
    
    public function getLimitsApikey(Request $request)
    {
        try{

            if(isset($request->skip)){
                $limitsApikey=Limits_apikey::              
                    offset($request->skip)
                    ->limit($request->take)
                    ->orderby("id","desc")
                    ->get()->toArray();
            }else{
                $limitsApikey=Limits_apikey::all()->toArray();
            } 

            if($limitsApikey){
                
                foreach ($limitsApikey as $key => $value) {

                    $limitsApikey[$key]['api_key'] = PermissionTrait::decrypt($value['api_key']);
                }

                $total_records=Limits_apikey::count();        

                $limitsApikey_data['limitsApikey'] = $limitsApikey;
                $limitsApikey_data['total'] = $total_records;                
                return json_encode($limitsApikey_data);
            }

        }catch( \Exception $e){

            $exceptionMessage = ConnectionManager::renderPdoException($e->getCode());
            return (array("error" => $exceptionMessage));
        }    
    }   

    public function saveLimitsApikey(Request $request)
    {
        try{
            if(empty($request->id)){
                $limitsApikey = new Limits_apikey();    
            }else{
                $limitsApikey = Limits_apikey::findOrfail($request->id);
            }
            
            $key = $request->key;
            $value = $request->value;

            if($key === "api_key"){
                $value = PermissionTrait::encrypt($value);
            }

            $limitsApikey->$key = $value;
            $limitsApikey->save();

            return array("type" => "success");

        }catch (Exception $e) {

            $exceptionMessage = ConnectionManager::renderPdoException($e->getCode());
            return array("type" => "error" , "message" => $exceptionMessage);
        } 
    }
}
