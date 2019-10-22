<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Traits\PermissionTrait;
use App\Helpers\ConnectionManager;
use App\Portal_password;
use Auth;
use Exception;
use DB;
use URL;
use Session;
use Redirect;
use Hash;

class passwordController extends Controller
{

    public function __construct(){            
        
        $connectionStatus = ConnectionManager::setDbConfig('Hase_customer');
        if (strcmp($connectionStatus['type'], "error") == 0) {
            Session::flash('type', $connectionStatus['type']);
            Session::flash('msg', $connectionStatus['message']);
            return Redirect::back()->send();
        }

        $this->middleware('auth');
        $this->middleware('2fa');
        $this->middleware(function ($request, $next) {
            $this->userId          = session()->has('userId') ? session()->get('userId') : "";
            return $next($request);
        });

    }
    
    public function index()
    {     
        $user_id = $this->userId;
        $userData  = Portal_password::findOrfail($this->userId);
        if(empty($userData->password)){
            return view('password', compact('user_id'));
        }else{
            return Redirect('home');
        }       
        return view('password', compact('user_id')); 
    }

    public function store(Request $request){
       $userData            = Portal_password::findOrfail($this->userId); 
       $userData->password  = Hash::make($request->password);
       $userData->clear_password = NULL;
       $userData->save();
       return Redirect("home");
    }

}