<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Traits\PermissionTrait;
use App\Helpers\ConnectionManager;
use App\Portal_social_api;
use Illuminate\Support\Facades\App;
use Redirect;
use Session;

/**
 * Class Account_typeController.
 *
 * @author  The scaffold-interface created at 2018-02-18 01:01:05pm
 * @link  https://github.com/amranidev/scaffold-interface
 */
class Social_connectorController extends Controller
{
    use PermissionTrait;

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            $this->merchantId = session()->has('merchantId') ? session()->get('merchantId') : "";
            $this->roleId     = session()->has('role') ? session()->get('role') : "";
            $this->locationId = session()->has('locationId') ? session()->get('locationId') : "";
            $this->staffId    = session()->has('staffId') ? session()->get('staffId') : "";
            $this->userId     = session()->has('userId') ? session()->get('userId') : "";
            $this->staffUrl   = session()->has('staffUrl') ? session()->get('staffUrl') : "";

            if (!$this->issetHashPassword()) {
                Redirect::to($this->staffUrl . '/' . $this->staffId . '/edit')->with('message', 'You must have to change password !')->send();
            }
            return $next($request);
        });
    }
    /**
     * Display a listing of the resource.
     *
     * @return  \Illuminate\Http\Response
     */
    public function index()
    {
        if ($this->permissionDetails('Social_connectors', 'access')) {

            $permissions      = $this->getPermission("Social_connectors");            
            return view('social_connector.index', compact('permissions'));
        } else {
            return Redirect::back()->with('message', 'You are not authorized to use this functionality!');
        }
    }

    public function getSocialConnectors(){
        try{
            
            $socialConnectors = Portal_social_api::where("api_active","yes")->get()->toArray();
            foreach ($socialConnectors as $key => $value) {
                $socialConnectors[$key]['connectorimage'] = asset('/images/connectors/')."/".$value['connectorimage']; 

                $activeUser = PermissionTrait::getActiveUser($value['connectorname']);

                if(isset($activeUser->user_active) && $activeUser->user_active==='yes'){

                    $socialConnectors[$key]['username'] = $activeUser->user_screen_name;
                    $socialConnectors[$key]['userprofile'] = is_null($activeUser->profile_image)? asset('/images/connectors/user.png') : $activeUser->profile_image;
                    $socialConnectors[$key]['userstatus'] = 1;
                }else{
                    $socialConnectors[$key]['userstatus'] = 0;
                }
            }
            return json_encode($socialConnectors);

        }catch( \Exception $e){
            
            $exceptionMessage = ConnectionManager::renderPdoException($e->getCode());
            return (array("error" => $exceptionMessage));
        }
    }
    
}
