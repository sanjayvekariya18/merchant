<?php

namespace App\Http\Controllers;

use App\Helpers\ConnectionManager;
use App\Group_permission;
use App\Http\Controllers\PermissionsController;
use App\Identity_table_type;
use App\RegexTableAccess;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Session;
use Redirect;

class Regex_table_accessController extends PermissionsController
{

    public function __construct()
    {
        parent::__construct();
        $connectionStatus = ConnectionManager::setDbConfig('Regex');
        if ($connectionStatus['type'] === "error") {
            Session::flash('type', $connectionStatus['type']);
            Session::flash('msg', $connectionStatus['message']);
            return Redirect::back()->send();
        }
    }

    public function index()
    {
        return view('regex_table_access.index');
    }

    public function getAccessTableList(Request $request)
    {
        $total_records        = 0;
        $regex_table_accesses = RegexTableAccess::
            leftjoin('group_permissions', 'regex_table_access.group_id', 'group_permissions.group_id')
            ->select('regex_table_access.*', 'group_permissions.group_name');
        if (isset($request->take)) {
            $regex_table_accesses->offset($request->skip)->limit($request->take);
        }
        $total_records                       = $total_records + $regex_table_accesses->count();
        $regex_table_accesses_list           = $regex_table_accesses->get();
        $regex_table['total']                = $total_records;
        $regex_table['regex_table_accesses'] = $regex_table_accesses_list->toArray();
        $regexAccessAddNewDetails[0]         = array("access_id" => 0, "table_name" => 'identity_social', "group_name" => 'Portal Admin', "column_name" => 'identity_code');
        $regex_table['regex_table_accesses'] = array_merge($regexAccessAddNewDetails, $regex_table['regex_table_accesses']);
        return json_encode($regex_table);
    }
    public function getReferenceTableGroupList(Request $request)
    {
        $hase_staff_groups = Group_permission::
            select('group_permissions.*', DB::raw('count(identity_group_list.identity_id) as total'))
            ->leftjoin('identity_group_list', function ($join) {
                $join->on('identity_group_list.group_id', '=', 'group_permissions.group_id');
            })
            ->where('group_permissions.group_id', '>', 0)
            ->groupBy('group_permissions.group_id')
            ->get();
        return json_encode($hase_staff_groups);
    }
    public function updateAccessTable(Request $request)
    {
        if ($request->access_id == 0) {
            try {
                $regex_table_access              = new RegexTableAccess();
                $table_id                        = RegexTableAccess::count();
                $group_details                   = Group_permission::where('group_name', '=', $request->group_name)->first();
                $regex_table_access->group_id    = $group_details->group_id;
                $regex_table_access->table_id    = $table_id + 1;
                $regex_table_access->table_name  = $request->table_name;
                $regex_table_access->column_name = $request->column_name;
                $regex_table_access->save();
                return array("type" => "success", "message" => 'Reference Table Inserted');
            } catch (\Exception $e) {

                $exceptionMessage = ConnectionManager::renderPdoException($e->getCode());
                return array("type" => "error", "message" => $exceptionMessage);
            }
        } else {
            try {
                $regex_table_access              = RegexTableAccess::findOrfail($request->access_id);
                $group_details                   = Group_permission::where('group_name', '=', $request->group_name)->first();
                $regex_table_access->group_id    = $group_details->group_id;
                $regex_table_access->table_name  = $request->table_name;
                $regex_table_access->column_name = $request->column_name;
                $regex_table_access->save();
                return array("type" => "success", "message" => 'Reference Table Updated');
            } catch (\Exception $e) {

                $exceptionMessage = ConnectionManager::renderPdoException($e->getCode());
                return array("type" => "error", "message" => $exceptionMessage);
            }
        }
    }
    public function deleteAccessTable(Request $request)
    {
        try {
            $regex_table_access = RegexTableAccess::findOrfail($request->access_id);
            $regex_table_access->delete();
            return array("type" => "success");
        } catch (\Exception $e) {

            $exceptionMessage = ConnectionManager::renderPdoException($e->getCode());
            return array("type" => "error" , "message" => $exceptionMessage);
        }
    }
    public function createAccessTable(Request $request)
    {
        try {
            $regex_table_access              = new RegexTableAccess();
            $table_id                        = RegexTableAccess::count();
            $group_details                   = Group_permission::where('group_name', '=', $request->group_name)->first();
            $regex_table_access->group_id    = $group_details->group_id;
            $regex_table_access->table_id    = $table_id + 1;
            $regex_table_access->table_name  = $request['table_name']['table_code'];
            $regex_table_access->column_name = $request->column_name;
            $regex_table_access->save();
            return $regex_table_access;
        } catch (\Exception $e) {

            $exceptionMessage = ConnectionManager::renderPdoException($e->getCode());
            return (array("error" => $exceptionMessage));
        }
    }
    public function getReferenceTable()
    {
        $referenceTableList = Identity_table_type::get();
        return json_encode($referenceTableList);
    }

}
