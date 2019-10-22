<?php

namespace App\Http\Controllers;

use App\Helpers\SocialEventManager;
use App\Helpers\SocialAuth;
use App\Helpers\ConnectionManager;
use App\Http\Controllers\Controller;
use App\Http\Traits\PermissionTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Redirect;
use Session;
use Socialite;
use URL;

/**
 * Class Account_typeController.
 *
 * @author  The scaffold-interface created at 2018-02-18 01:01:05pm
 * @link  https://github.com/amranidev/scaffold-interface
 */
class Google_connectorController extends Controller
{
    use PermissionTrait;
    protected $connectorName;

    public function __construct(){            
        
        $connectionStatus = ConnectionManager::setDbConfig('social_connectors', 'mysqlDynamicConnector');
        
        if($connectionStatus['type'] === "error"){
            
            Session::flash('type', $connectionStatus['type']);
            Session::flash('msg', $connectionStatus['message']);
            return Redirect::back()->send();
        } 

        $this->connectorName = "google";
        $this->loginTypeId = '1';
        $this->connectorTypeId = '2';        
        
        $this->social = new SocialAuth();
    }

    /**
     * Display a listing of the resource.
     *
     * @return  \Illuminate\Http\Response
     */
    public function index($type)
    {     

        $socialTypeId = PermissionTrait::getSocialConnectionTypeId($type);
        $result = PermissionTrait::setSocialConnectorService($this->connectorName,$socialTypeId);

        if($result['type']==="error"){
            Session::flash('type', $result['type']);
            Session::flash('msg', $result['message']); 
            if($socialTypeId == $this->loginTypeId){
                return redirect('/login');    
            }else{
                return redirect('/social_connectors');    
            }
        }
        $scopes = [
            'https://www.googleapis.com/auth/userinfo.profile',
            'https://www.googleapis.com/auth/userinfo.email',
            'https://www.googleapis.com/auth/calendar',
            'https://picasaweb.google.com/data'
        ];
        return Socialite::driver($this->connectorName)->scopes($scopes)->with(["access_type" => "offline", "prompt" => "consent select_account"])->redirect();
    }

    public function socialConnect()
    {
        try{
            $result = PermissionTrait::setSocialConnectorService($this->connectorName,$this->connectorTypeId);  

            if($result['type']==="error"){
                Session::flash('type', $result['type']);
                Session::flash('msg', $result['message']);                
            }else{
                $user= Socialite::driver($this->connectorName)->user();
                if(!isset($user->user['type'])){
                    return $this->social->socialConnect($user,$this->connectorName);    
                }else{                    
                    Session::flash('type','error');
                    Session::flash('msg', $user->user['message']);  
                }
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

            if($result['type']==="error"){
                Session::flash('type', $result['type']);
                Session::flash('msg', $result['message']); 
                return redirect('/login');
            }else{
                $user= Socialite::driver($this->connectorName)->user();
                if(!isset($user->user['type'])){
                    return $this->social->loginConnect($user,$this->connectorName);
                }else{
                    Session::flash("type","error");
                    Session::flash("msg",$user->user['message']);
                    return redirect('/login');        
                }    
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
