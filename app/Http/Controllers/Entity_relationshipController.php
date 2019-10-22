<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\App;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Traits\PermissionTrait;
use App\Database_manager;
use URL;
use Session;
use DB;
use Redirect;


class Entity_relationshipController extends Controller
{
    use PermissionTrait;
    protected $tableTreeObject = array();
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
        $this->staffId = session()->has('staffId') ? session()->get('staffId') :"";
        $this->staffName = session()->has('staffName') ? session()->get('staffName') :"";
        $this->merchantId = session()->has('merchantId') ? session()->get('merchantId') :"";
        $this->roleId = session()->has('role') ? session()->get('role') :"";
        $this->locationId = session()->has('locationId') ? session()->get('locationId') :"";
        return $next($request);
        });
    }

    public function index()
    {
        if($this->permissionDetails('Entity_relationship','access')) {
            $databaseProvider = Database_manager::getDBProvider();
            return view('entity_relationship.index');
        }
        else {
            return Redirect::back()->with('message', 'You are not authorized to use this functionality!');
        }
    }

    public function getAllTableList()
    {
        $allTableListArray = DB::select('SHOW TABLES');
        $allTableListArray = array_map('current',$allTableListArray);
        $tableList = array();
        foreach ($allTableListArray as $key=>$value) {
            $tableList[$key]['table_id'] = $key;
            $tableList[$key]['table_name'] = $value;
        }
        return json_encode(array_values($tableList));
    }

    public function getTableForeignObject(Request $request)
    {
        if(isset($request->selectedTable) && $request->selectedTable != '')
        {
            $rootTable = $request->selectedTable;
            $treeTableId = 0;
            self::getTableForeignTables($rootTable,$treeTableId);
            echo "<pre>";
            print_r($this->tableTreeObject);
        }
    }

    public function getTableForeignTables($rootTable,$treeTableId)
    {
        $primaryFieldDetails = array();
        $columnList = DB::select('DESCRIBE '.$rootTable);
        foreach ($columnList as $columnListKey => $columnListValue)
        {
            
            if('MUL' === $columnListValue->Key)
            {
                $primaryKeyDetails = DB::select("SELECT REFERENCED_TABLE_NAME FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE WHERE TABLE_NAME = '".$rootTable."' AND COLUMN_NAME = '".$columnListValue->Field."'");
                if(isset($primaryKeyDetails[0]->REFERENCED_TABLE_NAME) && !empty($primaryKeyDetails[0]->REFERENCED_TABLE_NAME))
                {
                    $treeTableId++;
                    $this->tableTreeObject[$treeTableId]['tableId'] = $treeTableId;
                    $this->tableTreeObject[$treeTableId]['tableName'] = $rootTable;
                    $this->tableTreeObject[$treeTableId]['childTable'] = $primaryKeyDetails[0]->REFERENCED_TABLE_NAME;
                    self::getTableForeignTables($primaryKeyDetails[0]->REFERENCED_TABLE_NAME,$treeTableId);

                }
            }

        }
    }

}
