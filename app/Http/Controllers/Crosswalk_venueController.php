<?php

namespace App\Http\Controllers;

use App\Crosswalk_venue;
use App\Helpers\ConnectionManager;
use App\Http\Traits\PermissionTrait;
use App\Module;
use App\ModuleFields;
use App\ModuleFieldTypes;
use DB;
use Illuminate\Http\Request;
use Validator;
use App\Ticket_venue;

class Crosswalk_venueController extends PermissionsController
{
    use PermissionTrait;

    public $show_action  = true;
    public $view_col     = 'venue_td';
    public $listing_cols = ['crosswalk_id', 'venue_td', 'venue_sf', 'venue_sh'];

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
     * Display a listing of the Crosswalk_venue.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $module = Module::get('Crosswalk_venue');

        if (Module::hasAccess($module->id, "access")) {
            return View('crosswalk_venue.index', [
                'show_actions' => $this->show_action,
                'listing_cols' => $this->listing_cols,
                'module'       => $module,
            ]);
        } else {
            return Redirect::back()->with('message', 'You are not authorized to use this functionality!');
        }
    }

    public function getCrosswalkVenue(Request $request)
    {
        $total_records = 0;

        $crosswalkVenueDetails = Crosswalk_venue::
            select('crosswalk_venue.*');

        if (isset($request->filter)) {
            $searchFilter = $request->filter['filters'][0]['value'];
            if ($searchFilter) {
                $crosswalkVenueDetails->where(function ($query) use ($searchFilter, $request) {
                    foreach ($request->filter['filters'] as $filterKey => $filterValue) {
                        $query->orWhere('crosswalk_venue.' . $filterValue['field'] . '', 'LIKE', '%' . $searchFilter . '%');
                    }
                });
            }
        }

        $total_records = $total_records + $crosswalkVenueDetails->get()->count();
        if (isset($request->take)) {
            $crosswalkVenueDetails->offset($request->skip)->limit($request->take);
        }
        $crosswalkVenueValues = $crosswalkVenueDetails->get();
        $templateDefineArray  = array("Multiselect");
        $fields_popup         = ModuleFields::getModuleFields('Crosswalk_venue');
        $module               = Module::where('name', 'Crosswalk_venue')->orderBy('id', 'desc')->first();
        foreach ($crosswalkVenueValues as $key => $value) {
            for ($j = 0; $j < count($this->listing_cols); $j++) {
                $col = $this->listing_cols[$j];
                if (isset($value[$col]) && $fields_popup[$col] != null && starts_with($fields_popup[$col]->popup_vals, "@")) {
                    $field_type = ModuleFieldTypes::find($fields_popup[$col]->field_type);
                    if (in_array($field_type->name, $templateDefineArray)) {
                        $crosswalkVenueValues[$key][$fields_popup[$col]->popup_field_id] = ModuleFields::getKendoFieldValue($fields_popup[$col], $value[$col], $module->provider_id);
                    } else {
                        $crosswalkVenueValues[$key][$fields_popup[$col]->popup_field_name] = ModuleFields::getFieldValue($fields_popup[$col], $value[$col], $module->provider_id);
                    }

                } else if (isset($value[$col]) && !!$value[$col] && isset($fields_popup[$col]) && !!$fields_popup[$col] && starts_with($fields_popup[$col]->popup_vals, "[")) {
                    $field_type = ModuleFieldTypes::find($fields_popup[$col]->field_type);
                    if (in_array($field_type->name, $templateDefineArray)) {
                        $TestingEventValues[$key][$fields_popup[$col]->colname] = json_decode($TestingEventValues[$key][$fields_popup[$col]->colname], true);
                    }

                }
            }
        }
        foreach ($crosswalkVenueValues as $key => $value) {
             $crosswalkVenueValues[$key]->venue_td_id = $value->venue_td;
            $venue_tdDetails                         = Ticket_venue::select("venue_name")->where("venue_id", '=', $value->venue_td)->first();
            $crosswalkVenueValues[$key]->venue_td    = $venue_tdDetails->venue_name;
            $crosswalkVenueValues[$key]->venue_sf_id = $value->venue_sf;
            $venue_sfDetails                         = Ticket_venue::select("venue_name")->where("venue_id", '=', $value->venue_sf)->first();
            $crosswalkVenueValues[$key]->venue_sf    = $venue_sfDetails->venue_name;
            $crosswalkVenueValues[$key]->venue_sh_id = $value->venue_sh;
            $venue_shDetails                         = Ticket_venue::select("venue_name")->where("venue_id", '=', $value->venue_sh)->first();
            $crosswalkVenueValues[$key]->venue_sh    = $venue_shDetails->venue_name;
        }

        $CrosswalkVenuedata['crosswalkVenue'] = $crosswalkVenueValues->toArray();
        $CrosswalkVenuedata['total']          = $total_records;
        return json_encode($CrosswalkVenuedata);
    }
    public function getVenueTDList(Request $request)
    {
        $serviceIdDetail = DB::connection("mysqlDynamicConnector")->table('service')->where('service_name', '=', 'eventinventory')->first();
        $venue_tdDetails = Ticket_venue::select("venue_id", "venue_name")->where("service_id", '=', $serviceIdDetail->service_id);
        if (isset($request->take)) {
            $venue_tdDetails->offset($request->skip)->limit($request->take);
        }
        if (isset($request->filter['filters'])) {
            $searchFilter = $request->filter['filters'][0]['value'];
            if ($searchFilter) {
                $venue_tdDetails->where(function ($query) use ($searchFilter, $request) {
                    foreach ($request->filter['filters'] as $filterKey => $filterValue) {
                        $query->orWhere('venue.' . $filterValue['field'] . '', 'LIKE', '%' . $searchFilter . '%');
                    }
                });
            }
        }
        $venue_tdDetailsValue = $venue_tdDetails->get();
        $venue_tdDetails      = $venue_tdDetailsValue->toArray();
        return json_encode($venue_tdDetails);
    }
    public function getVenueSHList(Request $request)
    {
        $serviceIdDetail = DB::connection("mysqlDynamicConnector")->table('service')->where('service_name', '=', 'stubhub')->first();
        $venue_shDetails = Ticket_venue::select("venue_name","venue_id")->where("service_id", '=', $serviceIdDetail->service_id);
        if (isset($request->take)) {
            $venue_shDetails->offset($request->skip)->limit($request->take);
        }
        if (isset($request->filter['filters'])) {
            $searchFilter = $request->filter['filters'][0]['value'];
            if ($searchFilter) {
                $venue_shDetails->where(function ($query) use ($searchFilter, $request) {
                    foreach ($request->filter['filters'] as $filterKey => $filterValue) {
                        $query->orWhere('venue.' . $filterValue['field'] . '', 'LIKE', '%' . $searchFilter . '%');
                    }
                });
            }
        }
        $venue_shDetailsValue = $venue_shDetails->get();
        $venue_shDetails      = $venue_shDetailsValue->toArray();
        return json_encode($venue_shDetails);
    }
    public function getVenueSFList(Request $request)
    {
        $serviceIdDetail = DB::connection("mysqlDynamicConnector")->table('service')->where('service_name', '=', 'stagefront')->first();
        $venue_sfDetails = Ticket_venue::select("venue_name","venue_id")->where("service_id", '=', $serviceIdDetail->service_id);
        if (isset($request->take)) {
            $venue_sfDetails->offset($request->skip)->limit($request->take);
        }
        if (isset($request->filter['filters'])) {
            $searchFilter = $request->filter['filters'][0]['value'];
            if ($searchFilter) {
                $venue_sfDetails->where(function ($query) use ($searchFilter, $request) {
                    foreach ($request->filter['filters'] as $filterKey => $filterValue) {
                        $query->orWhere('venue.' . $filterValue['field'] . '', 'LIKE', '%' . $searchFilter . '%');
                    }
                });
            }
        }
        $venue_sfDetailsValue = $venue_sfDetails->get();
        $venue_sfDetails      = $venue_sfDetailsValue->toArray();
        return json_encode($venue_sfDetails);
    }

    /**
     * Update the specified crosswalk_venue in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateCrosswalkVenue(Request $request)
    {
        $callback = $request->callback;
        if (Module::hasAccess("Crosswalk_venue", "manage")) {

            $venue_td_details = Ticket_venue::where('venue_name', '=', $request->venue_td)->first();
            $venue_td_count   = count($venue_td_details);
            if ($venue_td_count != 0) {
                $request->venue_td = $venue_td_details->venue_id;
            } else {
                $request->venue_td = $request->venue_td_id;
            }
            $venue_sf_details = Ticket_venue::where('venue_name', '=', $request->venue_sf)->first();
            $venue_sf_count   = count($venue_sf_details);
            if ($venue_sf_count != 0) {
                $request->venue_sf = $venue_sf_details->venue_id;
            } else {
                $request->venue_sf = $request->venue_sf_id;
            }
            $venue_sh_details = Ticket_venue::where('venue_name', '=', $request->venue_sh)->first();
            $venue_sh_count   = count($venue_sh_details);
            if ($venue_sh_count != 0) {
                $request->venue_sh = $venue_sh_details->venue_id;
            } else {
                $request->venue_sh = $request->venue_sh_id;
            }
            $rules = Module::validateRules("Crosswalk_venue", $request, true);

            $validator = Validator::make($request->all(), $rules);

            Module::updateRow("Crosswalk_venue", $request, $request->crosswalk_id);
            return $callback . "(" . json_encode($request) . ")";

        } else {
            return $callback . "(" . json_encode($request) . ")";
        }
    }

    /**
     * Remove the specified crosswalk_venue from storage.
     *
     * @param  object  $request
     * @return \Illuminate\Http\Response
     */
    public function deleteCrosswalkVenue(Request $request)
    {
        $callback = $request->callback;
        if (Module::hasAccess("Crosswalk_venue", "delete")) {
            Crosswalk_venue::find($request->crosswalk_id)->delete();
            return $callback . "(" . json_encode($request) . ")";
        } else {
            return $callback . "(" . json_encode($request) . ")";
        }
    }

}
