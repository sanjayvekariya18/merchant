<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use App\Http\Controllers\PermissionsController;
use Amranidev\Ajaxis\Ajaxis;
use App\Helpers\ConnectionManager;
use App\Merchant_type;
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
class MerchantTypeController extends PermissionsController
{
    public function __construct()
    {
        parent::__construct();

        $connectionStatus = ConnectionManager::setDbConfig('Merchant_type');
        if ($connectionStatus['type'] === "error") {
            Session::flash('type', $connectionStatus['type']);
            Session::flash('msg', $connectionStatus['message']);
            return Redirect::back()->send();
        }
    }
    
    public function index()
    {
        if($this->permissionDetails('Merchant_type','access')){

            $permissions = $this->getPermission("Merchant_type");
            return view('merchant_type.index',compact('permissions'));

        }else{
            return Redirect::back()->with('message', 'You are not authorized to use this functionality!');
        }
    }
    
    public function getMerchantTypes(Request $request)
    {
        try{

            $merchantTypes=Merchant_type::  
                    select('merchant_type.*','root.merchant_type_name as root_type_name','parent.merchant_type_name as parent_type_name')
                    ->leftjoin("merchant_type as root","root.merchant_type_id","merchant_type.merchant_root_id")
                    ->leftjoin("merchant_type as parent","parent.merchant_type_id","merchant_type.merchant_parent_id")
                    ->where('merchant_type.merchant_type_id','!=',0)
                    ->offset($request->skip)
                    ->limit($request->take)
                    ->get();

            $total_records=Merchant_type::count();                
            
            $merchantType_data['merchantType'] = $merchantTypes;
            $merchantType_data['total'] = $total_records;                
            return json_encode($merchantType_data);

        }catch( \Exception $e){

            $exceptionMessage = ConnectionManager::renderPdoException($e->getCode());
            return (array("error" => $exceptionMessage));
        }    
    }  

    public function getAllMerchantTypes(){
        $merchantTypes=Merchant_type::orderby('merchant_type_name')->get()->toArray(); 
        return json_encode($merchantTypes);
    } 

    public function getAllParentMerchantTypes()
    {
        $merchantParentTypes = Merchant_type::where('merchant_parent_id', '=', 0)->orderby('merchant_type_name')->get()->toArray();
        return json_encode($merchantParentTypes);
    }
    public function saveMerchantTypes(Request $request)
    {
        try{
            if(empty($request->id)){
                $merchantTypes = new Merchant_type();    
            }else{
                $merchantTypes = Merchant_type::findOrfail($request->id);
            }
            
            $key = $request->key;
            $value = $request->value;

            $merchantTypes->$key = $value;
            $merchantTypes->save();

            Merchant_type::where('merchant_type_name', $value)->update(['merchant_root_id' =>$merchantTypes->merchant_type_id]);
            return array("type" => "success");

        }catch (Exception $e) {

            $exceptionMessage = ConnectionManager::renderPdoException($e->getCode());
            return array("type" => "error" , "message" => $exceptionMessage);
        } 
    }
}
