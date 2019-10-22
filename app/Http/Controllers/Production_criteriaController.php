<?php

namespace App\Http\Controllers;

use App\Helpers\ConnectionManager;
use App\Http\Controllers\PermissionsController;
use App\Http\Traits\PermissionTrait;
use App\Module;
use App\ModuleFields;
use App\ModuleFieldTypes;
use App\Production_criteria;
use App\Venue_criteria;
use DB;
use Illuminate\Http\Request;
use Validator;

class Production_criteriaController extends PermissionsController
{
    use PermissionTrait;
    const INIT_VALUE = 0;
    public $show_action  = true;
    public $view_col     = 'production_id';
    public $listing_cols = ['criteria_id', 'production_id', 'criteria_range', 'venue_group_id', 'wave_id', 'criteria_reference_id', 'criteria_reference_percent', 'min_quantity', 'max_quantity', 'delivery_id', 'external_reference_id', 'external_reference_percent', 'min_price', 'max_price', 'broker_ids', 'purchase_wait'];

    public function __construct()
    {
        parent::__construct();
        $connectionStatus = ConnectionManager::setDbConfig('ticket_event', 'mysqlDynamicConnector');
        if (strcmp($connectionStatus['type'], "error") == self::INIT_VALUE) {
            Session::flash('type', $connectionStatus['type']);
            Session::flash('msg', $connectionStatus['message']);
        }
    }

    /**
     * Display a listing of the Production_criteria.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $module = Module::get('Production_criteria');

        if (Module::hasAccess($module->id, "access")) {
            return View('production_criteria.index', [
                'show_actions' => $this->show_action,
                'listing_cols' => $this->listing_cols,
                'module'       => $module,
            ]);
        } else {
            return Redirect::back()->with('message', 'You are not authorized to use this functionality!');
        }
    }

    public function getProductionCriteria(Request $request)
    {
        $total_records = self::INIT_VALUE;

        $productionCriteriaDetails = Production_criteria::
            select('production_criteria.*','production.event_date','opponent.opponent_name')
            ->leftjoin('production', 'production.production_id', 'production_criteria.production_id')
            ->leftjoin('opponent', 'opponent.opponent_id', 'production.opponent_id');

        if (isset($request->filter)) {
            $searchFilter = $request->filter['filters'][self::INIT_VALUE]['value'];
            if ($searchFilter) {
                $productionCriteriaDetails->where(function ($query) use ($searchFilter, $request) {
                    foreach ($request->filter['filters'] as $filterKey => $filterValue) {
                        if ($filterValue['field'] == 'event_date' || $filterValue['field'] == 'production_name' || $filterValue['field'] == 'production_id') {
                            $query->orWhere('production.' . $filterValue['field'] . '', 'LIKE', '%' . $searchFilter . '%');
                        } else if ($filterValue['field'] == 'opponent_name')  {
                            $query->orWhere('opponent.' . $filterValue['field'] . '', 'LIKE', '%' . $searchFilter . '%');

                        } else {
                            $query->orWhere('production_criteria.' . $filterValue['field'] . '', 'LIKE', '%' . $searchFilter . '%');
                        }
                    }
                });
            }
        }

        $total_records = $total_records + $productionCriteriaDetails->get()->count();
        if (isset($request->take)) {
            $productionCriteriaDetails->offset($request->skip)->limit($request->take);
        }
        $productionCriteriaValues = $productionCriteriaDetails->get()->toArray();
        $templateDefineArray      = array("Multiselect");
        $fields_popup             = ModuleFields::getModuleFields('Production_criteria');
        $module                   = Module::where('name', 'Production_criteria')->orderBy('id', 'desc')->first();
        foreach ($productionCriteriaValues as $key => $value) {
            for ($j = self::INIT_VALUE; $j < count($this->listing_cols); $j++) {
                $col = $this->listing_cols[$j];
                if (isset($value[$col]) && $fields_popup[$col] != null && starts_with($fields_popup[$col]->popup_vals, "@")) {
                    $field_type = ModuleFieldTypes::find($fields_popup[$col]->field_type);
                    if (in_array($field_type->name, $templateDefineArray)) {
                        $productionCriteriaValues[$key][$fields_popup[$col]->popup_field_id] = ModuleFields::getKendoFieldValue($fields_popup[$col], $value[$col], $module->provider_id);
                    } else {
                        $productionCriteriaValues[$key][$fields_popup[$col]->popup_field_name] = ModuleFields::getFieldValue($fields_popup[$col], $value[$col], $module->provider_id);
                    }

                } else if (isset($value[$col]) && !!$value[$col] && isset($fields_popup[$col]) && !!$fields_popup[$col] && starts_with($fields_popup[$col]->popup_vals, "[")) {
                    $field_type = ModuleFieldTypes::find($fields_popup[$col]->field_type);
                    if (in_array($field_type->name, $templateDefineArray)) {
                        $TestingEventValues[$key][$fields_popup[$col]->colname] = json_decode($TestingEventValues[$key][$fields_popup[$col]->colname], true);
                    }

                }
            }
        }
        $ProductionCriteriadata['productionCriteria'] = array_values($productionCriteriaValues);
        $ProductionCriteriadata['total']              = $total_records;
        return json_encode($ProductionCriteriadata);
    }
    public function venueCriteriaDetailsList(Request $request)
    {
        $total_records        = self::INIT_VALUE;
        $venueCriteriaDetails = Venue_criteria::
            select('venue_criteria.*','venue.venue_name' , 'venue_criteria.row')
            ->leftjoin('venue', 'venue.venue_id', 'venue_criteria.venue_id')
            ->where('group_id', '=', $request->venue_group_id);
        
        $total_records = $total_records + $venueCriteriaDetails->get()->count();
        if (isset($request->take)) {
            $venueCriteriaDetails->offset($request->skip)->limit($request->take);
        }
        $venueCriteriaDetailsValue = $venueCriteriaDetails->get();
        $venue_criteria_detail_data['venue_details'] = $venueCriteriaDetailsValue->toArray();
        $venue_criteria_detail_data['total']                  = $total_records;
        return json_encode($venue_criteria_detail_data);
    }

    public function getProductionIdList(Request $request)
    {
        $production_idDetails = DB::connection("mysqlDynamicConnector")->table("production")->select("production_id", "production_name")->get()->toArray();
        return json_encode($production_idDetails);
    }

    public function getBrokerIdsList(Request $request)
    {
        $broker_idsDetails = DB::connection("mysqlDynamicConnector")->table("broker")->select("broker_id", "broker_name")->get()->toArray();
        return json_encode($broker_idsDetails);
    }

    /**
     * Update the specified production_criteria in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateProductionCriteria(Request $request)
    {
        $callback = $request->callback;
        if (Module::hasAccess("Production_criteria", "manage")) {

            $rules = Module::validateRules("Production_criteria", $request, true);

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            Module::updateRow("Production_criteria", $request, $request->criteria_id);
            return $callback . "(" . json_encode($request) . ")";

        } else {
            return $callback . "(" . json_encode($request) . ")";
        }
    }

    /**
     * Remove the specified production_criteria from storage.
     *
     * @param  object  $request
     * @return \Illuminate\Http\Response
     */
    public function deleteProductionCriteria(Request $request)
    {
        $callback = $request->callback;
        if (Module::hasAccess("Production_criteria", "delete")) {
            Production_criteria::find($request->criteria_id)->delete();
            return $callback . "(" . json_encode($request) . ")";
        } else {
            return $callback . "(" . json_encode($request) . ")";
        }
    }
}
