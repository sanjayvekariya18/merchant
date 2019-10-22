<?php

namespace App\Http\Controllers;

use App\Crosswalk_production;
use App\Helpers\ConnectionManager;
use App\Http\Traits\PermissionTrait;
use App\Module;
use App\ModuleFields;
use App\ModuleFieldTypes;
use App\Production;
use DB;
use Illuminate\Http\Request;
use Validator;

class Crosswalk_productionController extends PermissionsController
{
    use PermissionTrait;

    public $show_action  = true;
    public $view_col     = 'production_td';
    public $listing_cols = ['crosswalk_id', 'production_td', 'production_sf', 'production_sh'];

    public function __construct()
    {
        parent::__construct();

        $connectionStatus = ConnectionManager::setDbConfig('ticket_event', 'mysqlDynamicConnector');
        if (strcmp($connectionStatus['type'], "error") == 0) {
            Session::flash('type', $connectionStatus['type']);
            Session::flash('msg', $connectionStatus['message']);
        }
    }

    /**
     * Display a listing of the Crosswalk_production.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $module = Module::get('Crosswalk_production');

        if (Module::hasAccess($module->id, "access")) {
            return View('crosswalk_production.index', [
                'show_actions' => $this->show_action,
                'listing_cols' => $this->listing_cols,
                'module'       => $module,
            ]);
        } else {
            return Redirect::back()->with('message', 'You are not authorized to use this functionality!');
        }
    }

    public function getCrosswalkProduction(Request $request)
    {
        $total_records = 0;

        $crosswalkProductionDetails = Crosswalk_production::
            select('crosswalk_production.*');

        if (isset($request->filter)) {
            $searchFilter = $request->filter['filters'][0]['value'];
            if ($searchFilter) {
                $crosswalkProductionDetails->where(function ($query) use ($searchFilter, $request) {
                    foreach ($request->filter['filters'] as $filterKey => $filterValue) {
                        $query->orWhere('crosswalk_production.' . $filterValue['field'] . '', 'LIKE', '%' . $searchFilter . '%');
                    }
                });
            }
        }

        $total_records = $total_records + $crosswalkProductionDetails->get()->count();
        if (isset($request->take)) {
            $crosswalkProductionDetails->offset($request->skip)->limit($request->take);
        }
        $crosswalkProductionValues = $crosswalkProductionDetails->get();
        $templateDefineArray       = array("Multiselect");
        $fields_popup              = ModuleFields::getModuleFields('Crosswalk_production');
        $module                    = Module::where('name', 'Crosswalk_production')->orderBy('id', 'desc')->first();
        foreach ($crosswalkProductionValues as $key => $value) {
            for ($j = 0; $j < count($this->listing_cols); $j++) {
                $col = $this->listing_cols[$j];
                if (isset($value[$col]) && $fields_popup[$col] != null && starts_with($fields_popup[$col]->popup_vals, "@")) {
                    $field_type = ModuleFieldTypes::find($fields_popup[$col]->field_type);
                    if (in_array($field_type->name, $templateDefineArray)) {
                        $crosswalkProductionValues[$key][$fields_popup[$col]->popup_field_id] = ModuleFields::getKendoFieldValue($fields_popup[$col], $value[$col], $module->provider_id);
                    } else {
                        $crosswalkProductionValues[$key][$fields_popup[$col]->popup_field_name] = ModuleFields::getFieldValue($fields_popup[$col], $value[$col], $module->provider_id);
                    }

                } else if (isset($value[$col]) && !!$value[$col] && isset($fields_popup[$col]) && !!$fields_popup[$col] && starts_with($fields_popup[$col]->popup_vals, "[")) {
                    $field_type = ModuleFieldTypes::find($fields_popup[$col]->field_type);
                    if (in_array($field_type->name, $templateDefineArray)) {
                        $TestingEventValues[$key][$fields_popup[$col]->colname] = json_decode($TestingEventValues[$key][$fields_popup[$col]->colname], true);
                    }

                }
            }
        }
        foreach ($crosswalkProductionValues as $key => $value) {
            $crosswalkProductionValues[$key]->production_td_id = $value->production_td;
            $production_tdDetails                              = Production::select("production_name")->where("production_id", '=', $value->production_td)->first();
            $crosswalkProductionValues[$key]->production_td    = $production_tdDetails->production_name;
            $crosswalkProductionValues[$key]->production_sf_id = $value->production_sf;
            $production_sfDetails                              = Production::select("production_name")->where("production_id", '=', $value->production_sf)->first();
            $crosswalkProductionValues[$key]->production_sf    = $production_sfDetails->production_name;
            $crosswalkProductionValues[$key]->production_sh_id = $value->production_sh;
            $production_shDetails                              = Production::select("production_name")->where("production_id", '=', $value->production_sh)->first();
            $crosswalkProductionValues[$key]->production_sh    = $production_shDetails->production_name;
        }
        $CrosswalkProductiondata['crosswalkProduction'] = $crosswalkProductionValues->toArray();
        $CrosswalkProductiondata['total']               = $total_records;
        return json_encode($CrosswalkProductiondata);
    }

    public function getProductionTDList(Request $request)
    {
        $serviceIdDetail      = DB::connection("mysqlDynamicConnector")->table('service')->where('service_name', '=', 'eventinventory')->first();
        $production_tdDetails = Production::select("production_name", "production_id")->where("service_id", '=', $serviceIdDetail->service_id);
        if (isset($request->take)) {
            $production_tdDetails->offset($request->skip)->limit($request->take);
        }
        if (isset($request->filter['filters'])) {
            $searchFilter = $request->filter['filters'][0]['value'];
            if ($searchFilter) {
                $production_tdDetails->where(function ($query) use ($searchFilter, $request) {
                    foreach ($request->filter['filters'] as $filterKey => $filterValue) {
                        $query->orWhere('production.' . $filterValue['field'] . '', 'LIKE', '%' . $searchFilter . '%');
                    }
                });
            }
        }
        $production_tdDetailsValue = $production_tdDetails->get();
        $production_tdDetails      = $production_tdDetailsValue->toArray();
        return json_encode($production_tdDetails);
    }
    public function getProductionSHList(Request $request)
    {
        $serviceIdDetail      = DB::connection("mysqlDynamicConnector")->table('service')->where('service_name', '=', 'stubhub')->first();
        $production_shDetails = Production::select("production_name", "production_id")->where("service_id", '=', $serviceIdDetail->service_id);
        if (isset($request->take)) {
            $production_shDetails->offset($request->skip)->limit($request->take);
        }
        if (isset($request->filter['filters'])) {
            $searchFilter = $request->filter['filters'][0]['value'];
            if ($searchFilter) {
                $production_shDetails->where(function ($query) use ($searchFilter, $request) {
                    foreach ($request->filter['filters'] as $filterKey => $filterValue) {
                        $query->orWhere('production.' . $filterValue['field'] . '', 'LIKE', '%' . $searchFilter . '%');
                    }
                });
            }
        }
        $production_shDetailsValue = $production_shDetails->get();
        $production_shDetails      = $production_shDetailsValue->toArray();
        return json_encode($production_shDetails);
    }
    public function getProductionSFList(Request $request)
    {
        $serviceIdDetail      = DB::connection("mysqlDynamicConnector")->table('service')->where('service_name', '=', 'stagefront')->first();
        $production_sfDetails = Production::select("production_name", "production_id")->where("service_id", '=', $serviceIdDetail->service_id);
        if (isset($request->take)) {
            $production_sfDetails->offset($request->skip)->limit($request->take);
        }
        if (isset($request->filter['filters'])) {
            $searchFilter = $request->filter['filters'][0]['value'];
            if ($searchFilter) {
                $production_sfDetails->where(function ($query) use ($searchFilter, $request) {
                    foreach ($request->filter['filters'] as $filterKey => $filterValue) {
                        $query->orWhere('production.' . $filterValue['field'] . '', 'LIKE', '%' . $searchFilter . '%');
                    }
                });
            }
        }
        $production_sfDetailsValue = $production_sfDetails->get();
        $production_sfDetails      = $production_sfDetailsValue->toArray();
        return json_encode($production_sfDetails);
    }

    /**
     * Update the specified crosswalk_production in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateCrosswalkProduction(Request $request)
    {
        $callback = $request->callback;
        if (Module::hasAccess("Crosswalk_production", "manage")) {

            $rules = Module::validateRules("Crosswalk_production", $request, true);

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            Module::updateRow("Crosswalk_production", $request, $request->crosswalk_id);
            return $callback . "(" . json_encode($request) . ")";

        } else {
            return $callback . "(" . json_encode($request) . ")";
        }
    }

    /**
     * Remove the specified crosswalk_production from storage.
     *
     * @param  object  $request
     * @return \Illuminate\Http\Response
     */
    public function deleteCrosswalkProduction(Request $request)
    {
        $callback = $request->callback;
        if (Module::hasAccess("Crosswalk_production", "delete")) {
            Crosswalk_production::find($request->crosswalk_id)->delete();
            return $callback . "(" . json_encode($request) . ")";
        } else {
            return $callback . "(" . json_encode($request) . ")";
        }
    }

}
