<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Traits\PermissionTrait;

use App\Portal_social_api;
use Socialite;
use Auth;
use Exception;
use DB;
use URL;
use Session;
use Redirect;
use DateTime;
use DateTimeZone;
use Config;

class Weibo_connectorController extends Controller
{

    public function __construct(){

        $this->middleware('auth');
        $this->middleware(function ($request, $next) {

            $this->connectorName = "weibo";
            $this->userId = session()->has('userId') ? session()->get('userId') :"";

            $connector = Portal_social_api::where('connectorname',$this->connectorName)->first();

            $connector_name = strtolower($connector->connectorname);
            $redirect_url = url('/'.strtolower($connector->connectorname).'/connect');
            $redirect_url = str_replace("https://","http://",$redirect_url);
            
            config()->set('services.'.$connector_name.'.client_id',$connector->api_key);
            config()->set('services.'.$connector_name.'.client_secret',$connector->api_secret_key);  
            config()->set('services.'.$connector_name.'.redirect',$redirect_url); 

            return $next($request);
        });
    }
    /**
     * Redirect the user to the Foursquare authentication page.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Socialite::driver($this->connectorName)->redirect();
    }

    /**
     * Obtain the user information from Foursquare.
     *
     * @return \Illuminate\Http\Response
     */
    public function connect()
    {
        $user = Socialite::driver($this->connectorName)->user();

        // NEED TO UPDATE CODE AS PER GET RESPONSE FROM WEIBO 
        
        $oauthToken = $user->token;
        $secretOauthToken = $user->refreshToken;
        $userApiID = $user->id;   
        $displayName = $user->name;  
        $avtar = $user->avatar;
        $email= $user->email;          
        $userFirstName = isset($user->user['firstname'])?$user->user['firstname']:"";
        $userLastName = isset($user->user['lastname'])?$user->user['lastname']:"";       
        $gender = isset($user->user['sex'])?($user->user['sex'] == 'M')?'Male':'Female':NULL;
        $city = isset($user->user['city'])?$user->user['city']:"";

        if($userFirstName !=""){
            if(!PermissionTrait::checkUserApiId($userApiID)){
                    PermissionTrait::storeConnectorUserDetails($this->connectorName,$userApiID,$displayName,$oauthToken,$secretOauthToken,$avtar,$gender,$city);
            }else{
                    Session::flash('type', 'error'); 
                    Session::flash('msg', 'User API Already Register With Other User');
            }
            $connectorUrl = url('/')."/social_connectors";
            $connectorUrl = str_replace("https://","http://",$connectorUrl);
            echo "<script>   
            window.close();
            window.opener.location = '".$connectorUrl."';
            window.opener.location.reload();
            </script>";
        }

        
    }

    public function disconnect(){
        
        PermissionTrait::deactivateConnector($this->userId, $this->connectorName);
        return Redirect::to(URL::to('/').'/social_connectors');
    }
}