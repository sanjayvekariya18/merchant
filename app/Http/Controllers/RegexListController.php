<?php

namespace App\Http\Controllers;

use App\Crosswalk_terrain;
use App\Helpers\ConnectionManager;
use App\Http\Controllers\PermissionsController;
use App\Http\Traits\PermissionTrait;
use App\Module;
use App\ModuleFields;
use App\ModuleFieldTypes;
use DB;
use Illuminate\Http\Request;
use Validator;

class RegexListController extends PermissionsController
{
    use PermissionTrait;

    public $show_action  = true;
    public $view_col     = 'id';
    public $listing_cols = ['id', 'parent_id', 'root_id', 'name', 'priority', 'image'];

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

    /**
     * Display a listing of the regex list.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $module = Module::get('Regex_list');

        if (Module::hasAccess($module->id, "access")) {
            return View('crosswalk_terrain.index', [
                'show_actions' => $this->show_action,
                'listing_cols' => $this->listing_cols,
                'module'       => $module,
            ]);
        } else {
            return Redirect::back()->with('message', 'You are not authorized to use this functionality!');
        }
    }

    public function getRegexList(Request $request)
    {
        $total_records    = 0;
        $regexList        = array();
        $tableName        = ucfirst($request->ref_table);
        $table_name       = $request->ref_table;
        $regexListDetails = DB::table($table_name)->select($table_name . '.*', $table_name . '.name as listName', $table_name . '.name as rootName');

        if (isset($request->filter)) {
            $searchFiler = $request->filter['filters'][0]['value'];
            if ($searchFiler) {
                $regexListDetails->where(function ($query) use ($searchFiler, $request) {
                    foreach ($request->filter['filters'] as $filterKey => $filterValue) {
                        if ($filterValue['field'] == 'listName' || $filterValue['field'] == 'rootName') {
                            $filterValue['field'] = 'name';
                        }
                        $query->orWhere($request->ref_table . '.' . $filterValue['field'] . '', 'LIKE', '%' . $searchFiler . '%');
                    }
                });
            }
        }

        $total_records = $total_records + $regexListDetails->get()->count();
        if (isset($request->take)) {
            $regexListDetails->offset($request->skip)->limit($request->take);
        }
        $regexListValues     = $regexListDetails->get()->toArray();
        $templateDefineArray = array("Multiselect");
        $fields_popup        = ModuleFields::getModuleFields(ucfirst($request->ref_table));
        $module              = Module::where('name', ucfirst($request->ref_table))->orderBy('id', 'desc')->first();
        $regexListValues     = (array) $regexListValues;
        foreach ($regexListValues as $key => $regexListValue) {
            $rootNameDetails                 = DB::table($table_name)->select('name')->where('id', $regexListValue->root_id)->first();
            if ($regexListValue->root_id == $regexListValue->id && $regexListValue->parent_id == $regexListValue->id) {
                $regexListValue->parent_id = 0;
            }
            if(isset($rootNameDetails->name)){
                $regexListValues[$key]->rootName = $rootNameDetails->name;
            }
            $userNameDetails                 = DB::table('portal_password')->select('username')->where('user_id', $regexListValue->updater_id)->first();
            if(isset($userNameDetails->username)){
                $regexListValues[$key]->username = $userNameDetails->username;
            }
            if($regexListValue->updater_timestamp != NULL || $regexListValue->updater_timestamp != 0){
                $lookup_datetime                              = json_decode(PermissionTrait::covertToLocalTz($regexListValue->updater_timestamp));
                $regexListValues[$key]->updater_timestamp = $lookup_datetime->date . " " . $lookup_datetime->time;
            } else {
                $regexListValues[$key]->updater_timestamp = '';
            }

            $regexList[]                     = (array) $regexListValue;
        }
        foreach ($regexList as $key => $value) {
            for ($j = 0; $j < count($this->listing_cols); $j++) {
                $col = $this->listing_cols[$j];
                if (isset($value[$col]) && $fields_popup[$col] != null && starts_with($fields_popup[$col]->popup_vals, "@")) {
                    $field_type = ModuleFieldTypes::find($fields_popup[$col]->field_type);
                    if (in_array($field_type->name, $templateDefineArray)) {
                        $regexList[$key][$fields_popup[$col]->popup_field_id] = ModuleFields::getKendoFieldValue($fields_popup[$col], $value[$col], $module->provider_id);
                    } else {
                        $regexList[$key][$fields_popup[$col]->popup_field_name] = ModuleFields::getFieldValue($fields_popup[$col], $value[$col], $module->provider_id);
                    }

                } else if (isset($value[$col]) && !!$value[$col] && isset($fields_popup[$col]) && !!$fields_popup[$col] && starts_with($fields_popup[$col]->popup_vals, "[")) {
                    $field_type = ModuleFieldTypes::find($fields_popup[$col]->field_type);
                    if (in_array($field_type->name, $templateDefineArray)) {
                        $TestingEventValues[$key][$fields_popup[$col]->colname] = json_decode($TestingEventValues[$key][$fields_popup[$col]->colname], true);
                    }

                }
            }
        }
        $regexListdata['regexList'] = array_values($regexList);
        $regexListdataNewDetails[0] = array("id" => 0, "name" => '', "root_id" => 0, "parent_id" => 0, "priority" =>'', "image" => '', "rootName" => '', "listName" => '');
        $regexListdata['regexList'] = array_merge($regexListdataNewDetails, $regexListdata['regexList']);
        $regexListdata['total']     = $total_records;
        return json_encode($regexListdata);
    }

    public function getParentIdList(Request $request)
    {
        $parent_idDetails = DB::table($request->ref_table)->select("id", "name")->get()->toArray();
        return json_encode($parent_idDetails);
    }
    public function getRootIdList(Request $request)
    {
        $root_idDetails = DB::table($request->ref_table)->select("id as root_id", "name as rootName")->get()->toArray();
        return json_encode($root_idDetails);
    }

    /**
     * Update the specified regex list in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateRegexList(Request $request)
    {
        $callback = $request->callback;
        if (Module::hasAccess(ucfirst($request->ref_table), "manage")) {
                if ($request->id == 0) {
                   if (isset($request->listName) && !empty($request->listName)) {
                        DB::table($request->ref_table)->insert(array('name' => $request->listName, 'root_id' => 1, 'parent_id' => 0));
                        $insertMessage = array("type" => "update", "action" => "success", "message" => $request->ref_table . ' Details Added');
                    } else {
                        $insertMessage = array("type" => "update", "action" => "success", "message" => 'Name is required');
                    }
                return $callback . "(" . json_encode($insertMessage) . ")";
            }  else {
                    $rules = Module::validateRules(ucfirst($request->ref_table), $request, true);

                    $validator = Validator::make($request->all(), $rules);
                    
                    if ($request->root_id == $request->id && $request->parent_id == $request->id) {
                        $request->parent_id = 0;
                    }
                    if (is_array($request->updater_id)) {
                        foreach ($request->updater_id as $key => $value) {
                            $request->updater_id = $value;
                        }
                    } else {
                            $request->updater_id = $request->updater_id;
                    }
                    $request->name = $request->listName;
                    $request->updater_timestamp = time();
                    Module::updateRow(ucfirst($request->ref_table), $request, $request->id);
                    $updateMessage = array("type" => "update","action" => "success", "message" => ucfirst($request->ref_table).' Updated');
                    return $callback . "(" . json_encode($updateMessage) . ")";
            }
        } else {
            return $callback . "(" . json_encode($request) . ")";
        }
    }

    /**
     * Remove the specified regex list from storage.
     *
     * @param  object  $request
     * @return \Illuminate\Http\Response
     */
    public function deleteRegexList(Request $request)
    {
        $callback = $request->callback;
        if (Module::hasAccess(ucfirst($request->ref_table), "delete")) {
            Crosswalk_terrain::find($request->terrain_id)->delete();
            return $callback . "(" . json_encode($request) . ")";
        } else {
            return $callback . "(" . json_encode($request) . ")";
        }
    }
}
