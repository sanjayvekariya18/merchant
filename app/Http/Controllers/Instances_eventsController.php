<?php

namespace App\Http\Controllers;

use App\Helpers\ConnectionManager;
use App\Http\Traits\PermissionTrait;
use App\Instances_events;
use App\Module;
use App\ModuleFields;
use App\Production;
use Illuminate\Http\Request;
use Redirect;

class Instances_eventsController extends PermissionsController
{
    use PermissionTrait;
    const INIT_VALUE = 0;
    const INDEX_ONE  = 1;
    public $show_action  = true;
    public $view_col     = 'production_id';
    public $listing_cols = ['id', 'produnction_id'];

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
     * Display a listing of the Instances_events.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $module = Module::get('Instances_events');

        if (Module::hasAccess($module->id, "access")) {
            return View('instances_events.index', [
                'show_actions' => $this->show_action,
                'listing_cols' => $this->listing_cols,
                'module'       => $module,
            ]);
        } else {
            return Redirect::back()->with('message', 'You are not authorized to use this functionality!');
        }
    }

    public function getInstances_events(Request $request)
    {
        $total_records = self::INIT_VALUE;

        $Instances_eventDetails = Instances_events::
            select('instances_events.*', 'production.*')
            ->leftjoin('production', 'production.production_id', 'instances_events.production_id')
            ->groupBy('instances_events.production_id');
        $total_records = $total_records + $Instances_eventDetails->get()->count();

        if (isset($request->take)) {
            $Instances_eventDetails->offset($request->skip)->limit($request->take);
        }
        $Instances_eventValues = $Instances_eventDetails->get();
        $templateDefineArray = array("Multiselect");
        $fields_popup        = ModuleFields::getModuleFields('Instances_events');
        $module              = Module::where('name', 'Instances_events')->first();
        foreach ($Instances_eventValues as $key => $value) {
            for ($j = self::INIT_VALUE; $j < count($this->listing_cols); $j++) {
                $col = $this->listing_cols[$j];
                if (isset($value[$col]) && !!$value[$col] && $fields_popup[$col] != null && starts_with($fields_popup[$col]->popup_vals, "@")) {
                    $field_type = ModuleFieldTypes::find($fields_popup[$col]->field_type);
                    if (in_array($field_type->name, $templateDefineArray)) {
                        $Instances_eventValues[$key][$fields_popup[$col]->popup_field_id] = ModuleFields::getKendoFieldValue($fields_popup[$col], $value[$col], $module->provider_id);
                    } else {
                        $Instances_eventValues[$key][$fields_popup[$col]->popup_field_name] = ModuleFields::getFieldValue($fields_popup[$col], $value[$col], $module->provider_id);
                    }

                } else if (isset($value[$col]) && !!$value[$col] && isset($fields_popup[$col]) && !!$fields_popup[$col] && starts_with($fields_popup[$col]->popup_vals, "[")) {
                    $field_type = ModuleFieldTypes::find($fields_popup[$col]->field_type);
                    if (in_array($field_type->name, $templateDefineArray)) {
                        $TestingEventValues[$key][$fields_popup[$col]->colname] = json_decode($TestingEventValues[$key][$fields_popup[$col]->colname], true);
                    }

                }
            }
        }
        foreach ($Instances_eventValues as $key => $value) {
            $instance_datetime                          = json_decode(PermissionTrait::covertToLocalTz($value->instance_time));
            $Instances_eventValues[$key]->instance_date = date("Y m d", strtotime($value->instance_date));
            $Instances_eventValues[$key]->instance_time = $instance_datetime->time;
        }
        if (isset($request->filter)) {
            $searchFilter = $request->filter['filters'][self::INIT_VALUE]['value'];
            if ($searchFilter) {
                foreach ($Instances_eventValues as $Instances_eventKey => $Instances_eventValue) {
                    $flagValue = false;
                    foreach ($request->filter['filters'] as $filterKey => $filterValue) {
                         if ($Instances_eventValue[$filterValue['field']] == 'instance_date') {
                            $searchFilter = str_replace(' ', '', $searchFilter);
                        }
                        if (stripos($Instances_eventValue[$filterValue['field']], $searchFilter) !== false) {
                            $flagValue = true;
                        }
                    }
                    if ($flagValue == false) {
                        unset($Instances_eventValues[$Instances_eventKey]);
                        $total_records = $total_records - self::INDEX_ONE;
                    }
                }
            }
        }
        $Instances_eventValues                     = $Instances_eventValues->toArray();
        $instances_eventValuesAddValue[self::INIT_VALUE]          = array("id" => self::INIT_VALUE,"production_id" => '',"stubhub_id" => '', "instance_date" => '', "instance_time" => "", "account" => "", "start_avg_min" => null, "start_avg_max" => "", "list" => '', "threads" => 'None', "wait" => "", "low_price" => '', "event_id" => '', "service_id" => "", "event_name" => "None","production_name" => "None", "venue_id" => "");
        $Instances_events_data['Instances_events'] = array_merge($instances_eventValuesAddValue, $Instances_eventValues);
        $Instances_events_data['total']            = $total_records;
        return json_encode($Instances_events_data);
    }
    public function productionIdList(Request $request)
    {
        $productionIdDetails = Production::groupBy('production_id')->get()->toArray();
        return json_encode($productionIdDetails);
    }

    /**
     * Update the specified instances_event in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateInstances_events(Request $request)
    {
        $callback = $request->callback;
        if (Module::hasAccess("Instances_events", "manage")) {

            Module::updateRow("Instances_events", $request, $request->id);
            return $callback . "(" . json_encode($request) . ")";

        } else {
            return $callback . "(" . json_encode($request) . ")";
        }
    }

    public function createInstancesEvents(Request $request)
    {
        if (Module::hasAccess("Instances_events", "manage")) {
            $instances_event = new Instances_events();
            if ($request->id == self::INIT_VALUE) {
                try {
                    $instances_event->production_id = $request->production_id;
                    $instances_event->instance_time = time();
                    $instances_event->instance_date = date('Ymd');
                    $instances_event->save();
                    return array("type" => "success", "message" => 'Instance Events Inserted');
                } catch (\Exception $e) {
                    $exceptionMessage = ConnectionManager::renderPdoException($e->getCode());
                    return array("type" => "error", "message" => $exceptionMessage);
                }

            } else {
                try {
                    Module::updateRow("Instances_events", $request, $request->id);
                    return array("type" => "success", "message" => 'Instance Events Updated');
                } catch (\Exception $e) {
                    $exceptionMessage = ConnectionManager::renderPdoException($e->getCode());

                }
            }
        }
    }
}
