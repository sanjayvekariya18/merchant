<?php

namespace App\Http\Controllers;

use App\Crosswalk_position;
use App\Helpers\ConnectionManager;
use App\Http\Controllers\PermissionsController;
use App\Http\Traits\PermissionTrait;
use App\Module;
use App\ModuleFields;
use App\ModuleFieldTypes;
use DB;
use Illuminate\Http\Request;
use Validator;

class CrosswalkController extends PermissionsController
{
    const FIRST_VALUE    = 1;
    const INIT_VALUE     = 0;
    use PermissionTrait;

    public $show_action  = true;
    public $view_col     = 'creator_id';
    public $listing_cols = ['id', 'source_id', 'creator_id', 'creator_timestamp', 'updater_id', 'updater_timestamp', 'name', 'lookup_id'];

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
     * Display a listing of the Crosswalk_position.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $module = Module::get('Crosswalk_position');

        if (Module::hasAccess($module->id, "access")) {
            return View('crosswalk_position.index', [
                'show_actions' => $this->show_action,
                'listing_cols' => $this->listing_cols,
                'module'       => $module,
            ]);
        } else {
            return Redirect::back()->with('message', 'You are not authorized to use this functionality!');
        }
    }

    public function getCrosswalkDetails(Request $request)
    {
        $total_records           = self::INIT_VALUE;
        $crosswalkPositionValues = array();
        $tableName               = $request->crosswalk_table;
        $tableDetails            = explode("_", $tableName);
        $tableName               = "crosswalk_" . $tableDetails[self::FIRST_VALUE];
        $tableName               = ucfirst($tableName);
        $table_name              = $tableName;

        $crosswalkPositionDetails = DB::table($table_name)->select($table_name . '.*');
        if (isset($request->filter)) {
            $searchFiler = $request->filter['filters'][self::INIT_VALUE]['value'];
            if ($searchFiler) {
                $crosswalkPositionDetails->where(function ($query) use ($searchFiler, $request) {
                    foreach ($request->filter['filters'] as $filterKey => $filterValue) {
                        $query->orWhere($table_name . '.' . $filterValue['field'] . '', 'LIKE', '%' . $searchFiler . '%');
                    }
                });
            }
        }

        $total_records = $total_records + $crosswalkPositionDetails->get()->count();
        if (isset($request->take)) {
            $crosswalkPositionDetails->offset($request->skip)->limit($request->take);
        }
        $crosswalkPositionValue = $crosswalkPositionDetails->get()->toArray();
        $templateDefineArray    = array("Multiselect");
        $fields_popup           = ModuleFields::getModuleFields($tableName);
        $module                 = Module::where('name', $tableName)->orderBy('id', 'desc')->first();
        $crosswalkPositionValue = (array) $crosswalkPositionValue;
        foreach ($crosswalkPositionValue as $key => $value) {
            if($value->updater_timestamp != null && $value->updater_timestamp !=self::INIT_VALUE) {
                $crosswalk_datetime                              = json_decode(PermissionTrait::covertToLocalTz($value->updater_timestamp));
                $crosswalkPositionValue[$key]->updater_timestamp = $crosswalk_datetime->date . " " . $crosswalk_datetime->time;
                $crosswalkPositionValue[$key]->creator_timestamp = $crosswalk_datetime->date . " " . $crosswalk_datetime->time;
            } else {
                $crosswalkPositionValue[$key]->updater_timestamp = '';

            }
            $crosswalkPositionValues[]                       = (array) $value;
        }
        foreach ($crosswalkPositionValues as $key => $value) {
            for ($j = self::INIT_VALUE; $j < count($this->listing_cols); $j++) {
                $col = $this->listing_cols[$j];
                if (isset($value[$col]) && $fields_popup[$col] != null && starts_with($fields_popup[$col]->popup_vals, "@")) {
                    $field_type = ModuleFieldTypes::find($fields_popup[$col]->field_type);
                    if (in_array($field_type->name, $templateDefineArray)) {
                        $crosswalkPositionValues[$key][$fields_popup[$col]->popup_field_id] = ModuleFields::getKendoFieldValue($fields_popup[$col], $value[$col], $module->provider_id);
                    } else {
                        $crosswalkPositionValues[$key][$fields_popup[$col]->popup_field_name] = ModuleFields::getFieldValue($fields_popup[$col], $value[$col], $module->provider_id);
                    }

                } else if (isset($value[$col]) && !!$value[$col] && isset($fields_popup[$col]) && !!$fields_popup[$col] && starts_with($fields_popup[$col]->popup_vals, "[")) {
                    $field_type = ModuleFieldTypes::find($fields_popup[$col]->field_type);
                    if (in_array($field_type->name, $templateDefineArray)) {
                        $TestingEventValues[$key][$fields_popup[$col]->colname] = json_decode($TestingEventValues[$key][$fields_popup[$col]->colname], true);
                    }

                }
            }
        }
        $CrosswalkPositiondata['crosswalkDetails'] = array_values($crosswalkPositionValues);
        $CrosswalkPositiondata['total']            = $total_records;
        return json_encode($CrosswalkPositiondata);
    }

    public function getCreatorIdList(Request $request)
    {
        $creator_idDetails = DB::table("portal_password")->select("username", "user_id")->get()->toArray();
        return json_encode($creator_idDetails);
    }

    /**
     * Update the specified crosswalk_position in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateCrosswalkDetails(Request $request)
    {
        $callback = $request->callback;
        $tableDetails = explode("_", $request->crosswalk_table);
        $tableName    = ucfirst("crosswalk_" . $tableDetails[self::FIRST_VALUE]);
        if (Module::hasAccess($tableName, "manage")) {
            $rules = Module::validateRules($tableName, $request, true);


            $validator = Validator::make($request->all(), $rules);

            if (is_array($request->updater_id)) {
                foreach ($request->updater_id as $key => $value) {
                    $request->updater_id = $value;
                }
            } else {
                $request->updater_id = $request->updater_id;
            }
            $request->updater_timestamp = time();
            Module::updateRow($tableName, $request, $request->id);
            $updateMessage = array("type" => "update","action" => "success", "message" => $tableName.' Updated');
            return $callback . "(" . json_encode($updateMessage) . ")";

        } else {
            return $callback . "(" . json_encode($request) . ")";
        }
    }

    /**
     * Remove the specified crosswalk_position from storage.
     *
     * @param  object  $request
     * @return \Illuminate\Http\Response
     */
    public function deleteCrosswalkDetails(Request $request)
    {
        $callback = $request->callback;
        $tableDetails = explode("_", $request->crosswalk_table);
        $tableName    = ucfirst("crosswalk_" . $tableDetails[self::FIRST_VALUE]);
        if (Module::hasAccess($tableName, "delete")) {
            Crosswalk_position::find($request->id)->delete();
            return $callback . "(" . json_encode($request) . ")";
        } else {
            return $callback . "(" . json_encode($request) . ")";
        }
    }
}
