<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Traits\PermissionTrait;
use App\Helpers\SocialAuth;
use App\Helpers\ConnectionManager;
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

class Facebook_connectorController extends Controller
{
    protected $scopes = array();
    public function __construct(){            
        
        $connectionStatus = ConnectionManager::setDbConfig('social_connectors', 'mysqlDynamicConnector');
        
        if(strcmp($connectionStatus['type'],"error")==0){
            
            Session::flash('type', $connectionStatus['type']);
            Session::flash('msg', $connectionStatus['message']);
            return Redirect::back()->send();
        } 

        $this->scopes = ['user_likes','user_events','user_photos','user_videos','user_friends','email','manage_pages','pages_show_list'];

        $this->connectorName = "facebook";
        $this->loginTypeId = '1';
        $this->connectorTypeId = '2';        
        
        $this->social = new SocialAuth();
    }
    /**
     * Redirect the user to the GitHub authentication page.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($type)
    {     

        $socialTypeId = PermissionTrait::getSocialConnectionTypeId($type);
        $result = PermissionTrait::setSocialConnectorService($this->connectorName,$socialTypeId);

        if(strcmp($result['type'],"error")==0){
            Session::flash('type', $result['type']);
            Session::flash('msg', $result['message']); 
            if($socialTypeId == $this->loginTypeId){
                return redirect('/login');    
            }else{
                return redirect('/social_connectors');    
            }
        }

        return Socialite::driver($this->connectorName)->scopes($this->scopes)->redirect();
    }

    /**
     * Obtain the user information from GitHub.
     *
     * @return \Illuminate\Http\Response
     */
    public function socialConnect()
    {
        try{
            $result = PermissionTrait::setSocialConnectorService($this->connectorName,$this->connectorTypeId);  

            if(strcmp($result['type'],"error")==0){
                Session::flash('type', $result['type']);
                Session::flash('msg', $result['message']);                
            }else{
                $user= Socialite::driver($this->connectorName)->user();
                return $this->social->socialConnect($user,$this->connectorName);
            }
        }catch (Exception $e) {
            Session::flash("type","error");
            Session::flash("msg",$e->getMessage());
        }finally{
            echo "<script>
            localStorage.setItem('type','".Session::pull('type')."');
            localStorage.setItem('msg','".Session::pull('msg')."');   
            window.close();
            </script>";
        }      
    }   

    public function loginConnect()
    {
        try {
            $result = PermissionTrait::setSocialConnectorService($this->connectorName,$this->loginTypeId);

            if(strcmp($result['type'],"error")==0){
                Session::flash('type', $result['type']);
                Session::flash('msg', $result['message']); 
                return redirect('/login');
            }else{
                $user= Socialite::driver($this->connectorName)->user();
                return $this->social->loginConnect($user,$this->connectorName);
            }
        } 
        catch (Exception $e) {

            Session::flash("type","error");
            Session::flash("msg",$e->getMessage());
            return redirect('/login');
        }
    }

    public function socialDisconnect(){
        try{
            
            $result = $this->social->socialDisconnect($this->connectorName);
            return json_encode($result);

        }catch (Exception $e) {

            Session::flash("type","error");
            Session::flash("msg",$e->getMessage());
            return redirect('/logout');
        }    
    }
}