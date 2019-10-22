<?php

namespace App\Helpers;

use App\Database_manager;
use App\Http\Traits\PermissionTrait;
use App\Menus;
use DB;

class ConnectionManager
{

    public static function setDbConfig($active_module, $dbconnector = "mysql")
    {
        try {

        	$hostname = \Request::getHttpHost();

            $activeModule = DB::table("menus")
                ->select('id')
                ->where('url', $active_module)
                ->first();

            if(isset($activeModule->id)){
                $parentMenuId = self::getRootParentId($activeModule->id);

                $connectors = DB::table("database_manager")
                        ->select('database_manager.*')
                        ->join('menus_database_manager', 'menus_database_manager.provider_id', 'database_manager.id')
                        ->join('menus', 'menus.id', 'menus_database_manager.menu_id')
                        ->join('environment','environment.environment_id','database_manager.environment_id')
                        ->where('menus.id', $parentMenuId)
                        ->where('environment.hostname',$hostname)
                        ->orderBy('menus_database_manager.priority', 'asc')
                        ->get();

                foreach ($connectors as $connector) {

                    try {

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
                        
                        // throw an exception when connection could not be established
                        DB::connection($dbconnector)->getPdo();
                        break;
                    } catch (\Exception $e) {
                        // continue with loop for check next connection
                    }

                }
                // throw an exception when connection could not be established
                DB::connection($dbconnector)->getPdo();                
                return array("type" => "success"); 
            } else{
                return array("type" => "error", "message" => "Module is not available in sidebar menus");    
            }
        }catch (\Exception $e) {
            $exceptionMessage = self::renderPdoException($e->getCode());
            return array("type" => "error", "message" => $exceptionMessage);
        }     
    }

    public static function getRootParentId($menuId){

		$parentId = DB::table("menus")
            ->select('parent')
            ->where('id', $menuId)
            ->first()->parent;

		if($parentId == 0){
			return $menuId;
		}else{
			return self::getRootParentId($parentId);
		}

	}

    public static function renderPdoException($code)
    {
        switch ($code) {
            case 2002:
            case 1045:
                $message = "Database connection error";
                break;
            case "42S02":
                $message = "Schema not found";
                break;
            case "42S22":
                $message = "Schema field not found";
                break;
            default:
                $message = "Untrapped Error";
        }
        return $message;
    }
}
