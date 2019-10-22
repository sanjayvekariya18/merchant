<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Traits\PermissionTrait;
use App\Portal_social_api;
use App\Portal_social_environment;
use App\Social_connection_type;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Redirect;
use Session;

/**
 * Class Identity_typeController.
 *
 * @author  The scaffold-interface created at 2018-02-18 02:18:07pm
 * @link  https://github.com/amranidev/scaffold-interface
 */
class Portal_social_apikeysController extends Controller
{
    use PermissionTrait;

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            $this->staffId    = session()->has('staffId') ? session()->get('staffId') : "";
            $this->staffName  = session()->has('staffName') ? session()->get('staffName') : "";
            $this->merchantId = session()->has('merchantId') ? session()->get('merchantId') : "";
            $this->roleId     = session()->has('role') ? session()->get('role') : "";
            $this->locationId = session()->has('locationId') ? session()->get('locationId') : "";
            $this->staffUrl   = session()->has('staffUrl') ? session()->get('staffUrl') : "";

            if (!$this->issetHashPassword()) {
                Redirect::to($this->staffUrl . '/' . $this->staffId . '/edit')->with('message', 'You must have to change password !')->send();
            }
                                                                             
            return $next($request);
        });
    }

    public function index()
    {
        try{
            if ($this->permissionDetails('Portal_social_api', 'access')) {

                $permissions = $this->getPermission("Portal_social_api");
                return view('portal_social_api.index', compact('permissions'));
            } else {
                return Redirect::back()->with('message', 'You are not authorized to use this functionality!');
            }
        }catch (Exception $e) {

            Session::flash("type","error");
            Session::flash("msg",$e->getMessage());
            return view('portal_social_api.index');
        }    
    }

    public function getPortalSocialConnector()
    {
        $portal_social_connector = Portal_social_api::all();
        $total_records           = Portal_social_api::count();

        $portal_social_connectors['portal_social_connector'] = $portal_social_connector;
        $portal_social_connectors['total']                   = $total_records;

        return json_encode($portal_social_connectors);
    }

    public function getPortalSocialApi($connectorId)
    {
        $portal_social_api = Portal_social_environment::
            join("environment","environment.environment_id","portal_social_environment.environment_id")
            ->where("connectorid", $connectorId)
            ->get()->toArray();

        $total_records = Portal_social_environment::
            where("connectorid", $connectorId)
            ->count();

        foreach ($portal_social_api as $key => $value) {

            $portal_social_api[$key]['api_key'] = PermissionTrait::decrypt($value['api_key']);
        }

        $portal_social_api_detail['portal_social_api'] = $portal_social_api;
        $portal_social_api_detail['total']                   = $total_records;

        return json_encode($portal_social_api_detail);
    }  

    public function updatePortalSocialApi(Request $request)
    {
        try{
            $portal_social_environment = Portal_social_environment::findOrfail($request->id);
            
            $key = $request->key;
            $value = $request->value;

            if($key === "api_key"){
                $value = PermissionTrait::encrypt($value);
            }

            $portal_social_environment->$key = $value;
            $portal_social_environment->save();

            return array("type" => "success");

        }catch (Exception $e) {

            return array("type" => "error" , "message" => $e->getMessage());
        }    
    } 

    public function updatePortalSocialConnector(Request $request)
    {
        try{
            $portal_social_api = Portal_social_api::findOrfail($request->id);
            
            $key = $request->key;
            $value = $request->value;
            
            $portal_social_api->$key = $value;
            $portal_social_api->save();

            return array("type" => "success");

        }catch (Exception $e) {

            return array("type" => "error" , "message" => $e->getMessage());
        }    
    }  
}
