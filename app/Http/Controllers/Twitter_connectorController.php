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

class Twitter_connectorController extends Controller
{

    public function __construct(){            
        
        $connectionStatus = ConnectionManager::setDbConfig('social_connectors', 'mysqlDynamicConnector');
        
        if("error" === $connectionStatus['type']){
            
            Session::flash('type', $connectionStatus['type']);
            Session::flash('msg', $connectionStatus['message']);
            return Redirect::back()->send();
        }
        
        $this->connectorName = "twitter";
        $this->loginTypeId = '1';
        $this->connectorTypeId = '2';        
        $this->social = new SocialAuth();
    }
    
    public function index($type)
    {     
        try{
            $socialTypeId = PermissionTrait::getSocialConnectionTypeId($type);
            $result = PermissionTrait::setSocialConnectorService($this->connectorName,$socialTypeId);

            if("error" === $result['type']){
                Session::flash('type', $result['type']);
                Session::flash('msg', $result['message']); 
                if($socialTypeId == $this->loginTypeId){
                    return redirect('/login');  
                }else{
                    return redirect('/social_connectors');    
                }
            }

            return Socialite::driver($this->connectorName)->redirect();

        }catch (\Exception $exception){
            $message = $exception->getMessage();
            if($socialTypeId == $this->loginTypeId){
                Session::flash('type', "error");
                Session::flash('msg', $message); 
                return redirect('/login');    
            }else{
                return "<script>
                        localStorage.setItem('type','error');
                        localStorage.setItem('msg','".$message."');   
                        window.close();
                        </script>"; 
            }
        }
        
    }
    
    public function socialConnect()
    {
        try{
            $result = PermissionTrait::setSocialConnectorService($this->connectorName,$this->connectorTypeId);  

            if("error" === $result['type']){
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

            if("error" === $result['type']){
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