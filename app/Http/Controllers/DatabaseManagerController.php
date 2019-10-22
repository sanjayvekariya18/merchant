<?php

namespace App\Http\Controllers;

use App\Database_manager;
use App\Helpers\ConnectionManager;
use App\Http\Controllers\PermissionsController;
use App\Http\Traits\PermissionTrait;
use App\Menus;
use App\Menus_database_manager;
use App\Environment;
use App\Portal_exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Input;
use Redirect;
use Session;
use DB;
use Carbon\Carbon;
const INDEX_ZERO = 0;
const ACTIVE_STATUS = 1;
const DEACTIVE_STATUS = 0;
/**
 * Class SocialController.
 *
 * @author  The scaffold-interface created at 2018-03-04 06:06:54pm
 * @link  https://github.com/amranidev/scaffold-interface
 */
class DatabaseManagerController extends PermissionsController
{
    use PermissionTrait;

    public function __construct()
    {
        parent::__construct();

        $connectionStatus = ConnectionManager::setDbConfig('Database_manager');
        if ($connectionStatus['type'] === "error") {
            Session::flash('type', $connectionStatus['type']);
            Session::flash('msg', $connectionStatus['message']);
            return Redirect::back()->send();
        }        
        $response = PermissionTrait::getIdentityTableId("database_manager");
        $this->table_id = (is_numeric($response))?$response:INDEX_ZERO;
    }

    public function index()
    {
        try{
            if ($this->permissionDetails('Database_manager', 'access')) {

                $permissions = $this->getPermission("Database_manager");
                
                return view('database_manager.index', compact('permissions'));
            } else {
                return Redirect::back()->with('message', 'You are not authorized to use this functionality!');
            }
        } catch (\Exception $e) {

            $exceptionMessage = ConnectionManager::renderPdoException($e->getCode());
            return (array("error" => $exceptionMessage));
        }    
    }

    public function getDatabaseManager(Request $request)
    {

        try {
            if (isset($request->skip)) {
                $databaseManager = Database_manager::
                    leftjoin("environment","environment.environment_id","database_manager.environment_id")
                    ->offset($request->skip)
                    ->limit($request->take)
                    ->orderby("id","desc")
                    ->get()->toArray();
            } else {
                $databaseManager = Database_manager::all()->toArray();
            }

            foreach ($databaseManager as $key => $value) {
                $databaseManager[$key]['password'] = PermissionTrait::decrypt($value['password']);
            }

            $total_records = Database_manager::count();

            $databaseManager_data['databaseManager'] = $databaseManager;
            $databaseManager_data['total']           = $total_records;
            return json_encode($databaseManager_data);
        } catch (\Exception $e) {

            $exceptionMessage = ConnectionManager::renderPdoException($e->getCode());
            return (array("error" => $exceptionMessage));
        }
    }

    public function saveDatabaseManager(Request $request)
    {
        try {
            $status = INDEX_ZERO;
            $key = $request->key;
            $value = $request->value;
            $response = array();

            if($key === "password"){
                $value = PermissionTrait::encrypt($value);
            }else if($key === "db_port" && empty($value)){
                $value = "3306";
            }

            if(empty($request->id)){

                if($key === "provider_name"){
                    $databaseManagerExist = Database_manager::where($key,$value)->get()->count();

                    if($databaseManagerExist === INDEX_ZERO){
                        $databaseManager = new Database_manager();

                        $databaseManager->$key = $value;
                        $databaseManager->environment_id = 1;
                        $databaseManager->save();

                        $providerId = $databaseManager->id;

                        // GET TOP LEVEL MENUS AND ASSIGN NEWLY DATABASE PROVIDER.
                        $menu_list = Menus::where('parent', INDEX_ZERO)->get();

                        // GET MAX PRIORITY FROM DATABASE MENU
                        $menusPriority = Menus_database_manager::
                                        select(DB::raw("max(priority) as priority"))
                                        ->join("database_manager","database_manager.id","menus_database_manager.provider_id") 
                                        ->where("environment_id","1")
                                        ->first();
                        
                        $priority = $menusPriority->priority + 1;

                        foreach ($menu_list as $menu) {
                            
                            $menus_database_manager              = new Menus_database_manager();
                            $menus_database_manager->menu_id     = $menu->id;
                            $menus_database_manager->provider_id = $providerId;
                            $menus_database_manager->priority = $priority;
                            $menus_database_manager->save();
                        }
                        $response = array("type" => "success");
                    }else{
                        $response = array("type" => "error" , "message" => "Provider Name Already Exist");
                    }
                }

            }else{

                if($key === "provider_name"){
                    $databaseManagerExist = Database_manager::where($key,$value)->where("id","!=",$request->id)->get()->count();

                    if($databaseManagerExist === INDEX_ZERO){
                        $databaseManager = Database_manager::findOrfail($request->id);
                        $databaseManager->$key = $value;
                        $databaseManager->save();
                        $response = array("type" => "success");
                    }else{
                        $response = array("type" => "error" , "message" => "Provider Name Already Exist");
                    }
                }else if($key === "environment_id"){
                                        
                    // GET MAX PRIORITY FROM DATABASE MENU
                    $menusPriority = Menus_database_manager::
                                    select(DB::raw("max(priority) as priority"))
                                    ->join("database_manager","database_manager.id","menus_database_manager.provider_id") 
                                    ->where("environment_id",$value)
                                    ->first();
                    
                    $priority = $menusPriority->priority + 1;

                    // UPDATE DATABASE MANAGER ENVIRONMENT
                    $databaseManager = Database_manager::findOrfail($request->id);
                    $databaseManager->$key = $value;
                    $databaseManager->save();
                    
                    // UPDATE DATABASE MENUS PRIORITY BASED ON ENVIRONMENT
                    $menus_database_manager = Menus_database_manager::
                                            where('provider_id',$request->id)
                                            ->update(['priority' => $priority]);
                    
                    $response = array("type" => "success");

                }else{
                    $databaseManager = Database_manager::findOrfail($request->id);
                    $databaseManager->$key = $value;
                    $databaseManager->save();
                    $response = array("type" => "success");
                }
            }
            
            // check latest database configurations

            $status = $this->checkDBStatus($databaseManager);
            if($status){                
                $databaseManager->status = ACTIVE_STATUS;                    
            }else{
                $databaseManager->status = DEACTIVE_STATUS;
            }
            $databaseManager->save();

            return $response;

        } catch (\Exception $e) {

            $exceptionMessage = ConnectionManager::renderPdoException($e->getCode());
            return array("type" => "error" , "message" => $exceptionMessage);
        }
    }

    public function deleteDatabaseManager(Request $request)
    {
        try {
            $databaseManager = Database_manager::findOrfail($request->id);
            $databaseManager->delete();

            Menus_database_manager::where('provider_id',$request->id)->delete();

            return $databaseManager;
        } catch (\Exception $e) {

            $exceptionMessage = ConnectionManager::renderPdoException($e->getCode());
            return (array("error" => $exceptionMessage));
        }
    }   

    public function getMenusHierarchy(){
        $menusHtml   = "";
        $hostname    = \Request::getHttpHost();
        $activeConnector = array();

        $menus = Menus::select('id', 'name')->where('parent', INDEX_ZERO)->get();
        $databaseConnectors = Database_manager::
                                join("environment","environment.environment_id","database_manager.environment_id")
                                ->where("hostname",$hostname)
                                ->get();
        foreach ($databaseConnectors as $connector) {
            $status = $this->checkDBStatus($connector);
            if($status){
                $activeConnector[] = $connector->id;
            }
        } 
        foreach ($menus as $menu) {
            $menusHtml .= PermissionTrait::print_menu_provider_editor($menu,$activeConnector);
        }
        return $menusHtml; 
    }
    
    public function update_hierarchy()
    {
        try {
            $parents = Input::get('jsonData');

            for ($i = INDEX_ZERO; $i < count($parents); $i++) {
                
                if(array_key_exists("children", $parents[$i])){
                    $totalChild = count($parents[$i]['children']);
                    $menuId = $parents[$i]['id'];
                    
                    $providerData = $this->getTotalProvider($menuId);

                    if($providerData["status"] === "success" && $providerData["total"] === $totalChild){
                       $this->apply_hierarchy($parents[$i]); 
                    }else{
                        return (array("status" => "error","message" => "Invalid database target position"));
                    }
                }
            }
            return (array("status" => "success","message" => "Connection Priority Updated"));

        } catch (\Exception $e) {

            $exceptionMessage = ConnectionManager::renderPdoException($e->getCode());
            return (array("error" => $exceptionMessage));
        }
    }

    public function apply_hierarchy($menuItem)
    {
        try {
            if (isset($menuItem['children'])) {
                foreach ($menuItem['children'] as $key => $value) {
                    $priority                         = $key + 1;
                    $providerId                       = $value['id'];
                    $menus_database_manager           = Menus_database_manager::findOrfail($providerId);
                    $menus_database_manager->priority = $priority;
                    $menus_database_manager->save();
                }
            }
        } catch (\Exception $e) {

            $exceptionMessage = ConnectionManager::renderPdoException($e->getCode());
            return (array("error" => $exceptionMessage));
        }
    }

    public function getEnvironment(){
        try{
            $environments = Environment::all();
            return json_encode($environments);
        } catch (\Exception $e) {
            return (array("error" => $e->getMessage()));
        }
    }

    public function getTotalProvider($menuId){
        try{
            $hostname = \Request::getHttpHost();
            $totalChild = Menus_database_manager::              
                join('database_manager','database_manager.id','menus_database_manager.provider_id')
                ->join('environment','environment.environment_id','database_manager.environment_id')
                ->where('menus_database_manager.menu_id',$menuId)
                ->where('environment.hostname',$hostname)
                ->count();

            return (array("status" => "success","total" => $totalChild));

        } catch (\Exception $e){

            return (array("status" => "error","message" => $e->getMessage()));
        }
    }

    public function checkDBStatus($connector){
        try{
            $dbconnector = "mysqlDynamicConnector";
            config()->set(['database.connections.' . $dbconnector => [
                            'driver'    => 'mysql',
                            'host'      => $connector->db_ip,
                            'port'      => $connector->db_port,
                            'database'  => $connector->db_name,
                            'username'  => $connector->username,
                            'password'  => PermissionTrait::decrypt($connector->password),
                            'charset'   => 'utf8',
                            'collation' => 'utf8_unicode_ci',
                            'prefix'    => '',
                            'strict'    => false,
                            'engine'    => null,
                        ]]);
                            
            DB::purge($dbconnector);
            DB::reconnect($dbconnector);
            DB::connection($dbconnector)->getPdo();
            
            return true;

        }catch (\Exception $e) {
            
            $portal_exception = new Portal_exception();
            $portal_exception->identity_table_id = $this->table_id;
            $portal_exception->identity_id = $connector->id;
            $portal_exception->exception = $e->getMessage();
            $portal_exception->datetime = Carbon::now();
            $portal_exception->user_id = session('userId');
            $portal_exception->user_timezone = $_COOKIE['timeZoneOffset'];
            $portal_exception->save();
            return false;
        }    
    }
}
