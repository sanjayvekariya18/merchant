<?php

namespace App\Http\Controllers;

use App\Competitors;
use App\CompetitorsHistory;
use App\Helpers\ConnectionManager;
use App\Http\Traits\PermissionTrait;
use App\Inventory;
use App\InventoryHistory;
use App\Module;
use App\ModuleFields;
use Illuminate\Http\Request;
use Redirect;
use Validator;

class InventoriesController extends PermissionsController
{
    const INIT_VALUE = 0;
    const INDEX_ONE  = 1;
    public $show_action  = true;
    public $view_col     = 'service_id';
    public $listing_cols = ['service_id', 'production_id', 'listing_id'];

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
     * Display a listing of the Inventory.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $module = Module::get('Inventory');
        if (Module::hasAccess($module->id, "access")) {
            return View('inventories.index', [
                'show_actions' => $this->show_action,
                'listing_cols' => $this->listing_cols,
                'module'       => $module,
            ]);
        } else {
            return Redirect::back()->with('message', 'You are not authorized to use this functionality!');
        }
    }

    public function getInventories(Request $request)
    {
        $total_records = self::INIT_VALUE;

        $InventoryDetails = Inventory::distinct('inventory.production_id')
            ->select('inventory.*', 'production.production_name', 'production.event_date')
            ->leftjoin('production', 'production.production_id', 'inventory.production_id');

        if (isset($request->filter)) {
            $searchFilter = $request->filter['filters'][self::INIT_VALUE]['value'];
            if($searchFilter)
            {
                $InventoryDetails->where(function ($query) use ($searchFilter,$request) {
                    foreach ($request->filter['filters'] as $filterKey => $filterValue) {
                        if(in_array($filterValue['field'],array('production_name','event_date')))
                        {
                            $query->orWhere('production.'.$filterValue['field'].'', 'LIKE', '%' . $searchFilter . '%');
                        } else {
                            $query->orWhere('inventory.'.$filterValue['field'].'', 'LIKE', '%' . $searchFilter . '%');
                        }
                    }
                });
            }
        }
        $InventoryDetails->groupBy('inventory.production_id', 'section', 'row', 'seat_start')
            ->orderBy('inventory.list_time', 'DESC');
        $total_records = $total_records + $InventoryDetails->get()->count();

        if (isset($request->take)) {
            $InventoryDetails->offset($request->skip)->limit($request->take);
        }
        $InventoryValues     = $InventoryDetails->get()->toArray();
        $Inventories_data['Inventories'] = $InventoryValues;
        $Inventories_data['total']       = $total_records;
        return json_encode($Inventories_data);
    }
    public function getInventoryDetailsList(Request $request)
    {
        $total_records          = self::INIT_VALUE;
        $ticketInventoryDetails = Inventory::
            select('inventory.*')
            ->orderBy('inventory.list_time', 'DESC')
            ->where('inventory.production_id', '=', $request->production_id)
            ->where('section', '=', $request->section)
            ->where('row', '=', $request->row)
            ->where('seat_start', '=', $request->seat_start);
        $total_records = $total_records + $ticketInventoryDetails->get()->count();
        if (isset($request->take)) {
            $ticketInventoryDetails->offset($request->skip)->limit($request->take);
        }
        $ticketInventoryDetailsValue = $ticketInventoryDetails->get();
        foreach ($ticketInventoryDetailsValue as $key => $ticketInventoryValue) {
            $inventory_datetime                                        = json_decode(PermissionTrait::covertToLocalTz($ticketInventoryValue->list_time));
            $ticketInventoryDetailsValue[$key]->list_date              = $inventory_datetime->date;
            $ticketInventoryDetailsValue[$key]->list_time              = $inventory_datetime->time;
            $ticketInventoryDetailsValue[$key]->seatDetails            = $ticketInventoryValue->seat_start . " - " . $ticketInventoryValue->seat_end;
        }
        $ticket_inventory_data['inventory_details'] = $ticketInventoryDetailsValue->toArray();
        $ticket_inventory_data['total']             = $total_records;
        return json_encode($ticket_inventory_data);
    }
    public function getInventoriesHistory(Request $request)
    {
        $total_records           = self::INIT_VALUE;
        $InventoryHistoryDetails = InventoryHistory::distinct('inventory_history.production_id')
            ->select('inventory_history.*', 'production.production_name', 'production.event_date')
            ->leftjoin('production', 'production.production_id', 'inventory_history.production_id')
            ->groupBy('inventory_history.production_id', 'section', 'row', 'seat_start')
            ->orderBy('inventory_history.list_time', 'DESC');
        $total_records = $total_records + $InventoryHistoryDetails->get()->count();

        if (isset($request->take)) {
            $InventoryHistoryDetails->offset($request->skip)->limit($request->take);
        }
        $InventoryHistoryValues = $InventoryHistoryDetails->get();
        $templateDefineArray    = array("Multiselect");
        $fields_popup           = ModuleFields::getModuleFields('Inventory');
        if (isset($request->filter)) {
            $searchFilter = $request->filter['filters'][self::INIT_VALUE]['value'];
            if ($searchFilter) {
                foreach ($InventoryValues as $InventoryKey => $InventoryValue) {
                    $flagValue = false;
                    foreach ($request->filter['filters'] as $filterKey => $filterValue) {
                        if (stripos($InventoryValue[$filterValue['field']], $searchFilter) !== false) {
                            $flagValue = true;
                        }
                    }
                    if ($flagValue == false) {
                        unset($InventoryValues[$InventoryKey]);
                        $total_records = $total_records - self::INDEX_ONE;
                    }
                }
            }
        }
        $InventoryHistoryValues                         = $InventoryHistoryValues->toArray();
        $Inventories_history_data['InventoriesHistory'] = array_values($InventoryHistoryValues);
        $Inventories_history_data['total']              = $total_records;
        return json_encode($Inventories_history_data);
    }
    public function getInventoriesHistoryDetailsList(Request $request)
    {
        $total_records                   = self::INIT_VALUE;
        $ticketInventoriesHistoryDetails = InventoryHistory::
            select('inventory_history.*')
            ->orderBy('inventory_history.list_time', 'DESC')
            ->where('inventory_history.production_id', '=', $request->production_id)
            ->where('section', '=', $request->section)
            ->where('row', '=', $request->row)
            ->where('seat_start', '=', $request->seat_start);
        $total_records = $total_records + $ticketInventoriesHistoryDetails->get()->count();
        if (isset($request->take)) {
            $ticketInventoriesHistoryDetails->offset($request->skip)->limit($request->take);
        }
        $ticketInventoriesHistoryDetailsValue = $ticketInventoriesHistoryDetails->get();
        foreach ($ticketInventoriesHistoryDetailsValue as $key => $ticketInventoriesHistoryValue) {
            $inventories_history_datetime                                       = json_decode(PermissionTrait::covertToLocalTz($ticketInventoriesHistoryValue->list_time));
            $ticketInventoriesHistoryDetailsValue[$key]->list_date              = $inventories_history_datetime->date;
            $ticketInventoriesHistoryDetailsValue[$key]->list_time              = $inventories_history_datetime->time;
            $ticketInventoriesHistoryDetailsValue[$key]->seatDetails            = $ticketInventoriesHistoryValue->seat_start . " - " . $ticketInventoriesHistoryValue->seat_end;
        }
        $ticket_inventories_history_data['inventories_history_details'] = $ticketInventoriesHistoryDetailsValue->toArray();
        $ticket_inventories_history_data['total']                       = $total_records;
        return json_encode($ticket_inventories_history_data);
    }
    public function getCompetitors(Request $request)
    {
        $total_records = self::INIT_VALUE;

        $CompetitorsDetails = Competitors::distinct('competitors.production_id')
            ->select('competitors.*', 'production.production_name', 'production.event_date')
            ->leftjoin('production', 'production.production_id', 'competitors.production_id');
        if (isset($request->filter)) {
            $searchFilter = $request->filter['filters'][self::INIT_VALUE]['value'];
            if($searchFilter)
            {
                $CompetitorsDetails->where(function ($query) use ($searchFilter,$request) {
                    foreach ($request->filter['filters'] as $filterKey => $filterValue) {
                        if(in_array($filterValue['field'],array('production_name','event_date')))
                        {
                            $query->orWhere('production.'.$filterValue['field'].'', 'LIKE', '%' . $searchFilter . '%');
                        } else {
                            $query->orWhere('competitors.'.$filterValue['field'].'', 'LIKE', '%' . $searchFilter . '%');
                        }
                    }
                });
            }
        }
        $CompetitorsDetails->groupBy('competitors.production_id', 'section', 'row', 'seat_start')
            ->orderBy('competitors.list_time', 'DESC');
        $total_records = $total_records + $CompetitorsDetails->get()->count();

        if (isset($request->take)) {
            $CompetitorsDetails->offset($request->skip)->limit($request->take);
        }
        $CompetitorsValues               = $CompetitorsDetails->get()->toArray();
        $Competitors_data['Competitors'] = $CompetitorsValues;
        $Competitors_data['total']       = $total_records;
        return json_encode($Competitors_data);
    }
    public function getCompetitorsDetailsList(Request $request)
    {
        $total_records            = self::INIT_VALUE;
        $ticketCompetitorsDetails = Competitors::
            select('competitors.*', 'production.event_date')
            ->leftjoin('production', 'production.production_id', 'competitors.production_id')
            ->orderBy('competitors.list_time', 'DESC')
            ->where('competitors.production_id', '=', $request->production_id)
            ->where('section', '=', $request->section)
            ->where('row', '=', $request->row)
            ->where('seat_start', '=', $request->seat_start);
        $ticketInventoryDetails = Inventory::
            select('inventory.*')
            ->orderBy('inventory.list_time', 'DESC')
            ->where('inventory.production_id', '=', $request->production_id)
            ->where('section', '=', $request->section)
            ->where('row', '=', $request->row)
            ->where('seat_start', '=', $request->seat_start);
        $ticketInventoryDetailsValue = $ticketInventoryDetails->get();
        foreach ($ticketInventoryDetailsValue as $key => $ticketInventoryValue) {
            $inventory_datetime                                        = json_decode(PermissionTrait::covertToLocalTz($ticketInventoryValue->list_time));
            $ticketInventoryDetailsValue[$key]->list_date              = $inventory_datetime->date;
            $ticketInventoryDetailsValue[$key]->list_time              = $inventory_datetime->time;
            $ticketInventoryDetailsValue[$key]->seatDetails            = $ticketInventoryValue->seat_start . " - " . $ticketInventoryValue->seat_end;
        }
        $total_records = $total_records + $ticketCompetitorsDetails->get()->count() + $ticketInventoryDetails->get()->count();
        if (isset($request->take)) {
            $ticketCompetitorsDetails->offset($request->skip)->limit($request->take);
        }
        $ticketCompetitorsDetailsValue = $ticketCompetitorsDetails->get();
        foreach ($ticketCompetitorsDetailsValue as $key => $ticketCompetitorsValue) {
            $competitors_datetime                                        = json_decode(PermissionTrait::covertToLocalTz($ticketCompetitorsValue->list_time));
            $ticketCompetitorsDetailsValue[$key]->list_date              = $competitors_datetime->date;
            $ticketCompetitorsDetailsValue[$key]->list_time              = $competitors_datetime->time;
            $ticketCompetitorsDetailsValue[$key]->seatDetails            = $ticketCompetitorsValue->seat_start . " - " . $ticketCompetitorsValue->seat_end;
        }
        $ticket_competitors_data['competitors_details'] = $ticketCompetitorsDetailsValue->toArray();
        $ticket_competitors_data['total']               = $total_records;
        $ticket_competitors_data['competitors_details'] = array_merge($ticket_competitors_data['competitors_details'], $ticketInventoryDetailsValue->toArray());
        usort($ticket_competitors_data['competitors_details'], array($this, 'timeCompare'));
        usort($ticket_competitors_data['competitors_details'], array($this, 'dateSort'));
        $ticket_competitors_data['competitors_details'] = array_reverse($ticket_competitors_data['competitors_details']);
        return json_encode($ticket_competitors_data);
    }
    public function timeCompare($ticket_competitors_data, $ticket_inventory_data)
    {
        $ticket_competitors_data_time = strtotime($ticket_competitors_data['list_time']);
        $ticket_inventory_data_time   = strtotime($ticket_inventory_data['list_time']);
        return ($ticket_competitors_data_time - $ticket_inventory_data_time);
    }
    public function dateSort($ticket_competitors_data, $ticket_inventory_data)
    {
        $ticket_competitors_data_date = strtotime($ticket_competitors_data['list_date']);
        $ticket_inventory_data_date   = strtotime($ticket_inventory_data['list_date']);
        return ($ticket_competitors_data_date - $ticket_inventory_data_date);
    }
    public function getCompetitorsHistory(Request $request)
    {
        $total_records             = self::INIT_VALUE;
        $CompetitorsHistoryDetails = CompetitorsHistory::distinct('competitors_history.production_id')
            ->select('competitors_history.*', 'production.production_name', 'production.event_date')
            ->leftjoin('production', 'production.production_id', 'competitors_history.production_id')
            ->groupBy('competitors_history.production_id');
        $total_records = $total_records + $CompetitorsHistoryDetails->get()->count();

        if (isset($request->take)) {
            $CompetitorsHistoryDetails->offset($request->skip)->limit($request->take);
        }
        $CompetitorsHistoryValues = $CompetitorsHistoryDetails->get();
        $templateDefineArray      = array("Multiselect");
        if (isset($request->filter)) {
            $searchFilter = $request->filter['filters'][self::INIT_VALUE]['value'];
            if ($searchFilter) {
                foreach ($CompetitorsHistoryValues as $CompetitorsHistoryKey => $CompetitorsHistoryValue) {
                    $flagValue = false;
                    foreach ($request->filter['filters'] as $filterKey => $filterValue) {
                        if (stripos($CompetitorsHistoryValue[$filterValue['field']], $searchFilter) !== false) {
                            $flagValue = true;
                        }
                    }
                    if ($flagValue == false) {
                        unset($CompetitorsHistoryValues[$CompetitorsHistoryKey]);
                        $total_records = $total_records - self::INDEX_ONE;
                    }
                }
            }
        }
        $CompetitorsHistoryValues                       = $CompetitorsHistoryValues->toArray();
        $Competitors_history_data['CompetitorsHistory'] = array_values($CompetitorsHistoryValues);
        $Competitors_history_data['total']              = $total_records;
        return json_encode($Competitors_history_data);
    }
    public function getCompetitorsHistoryDetailsList(Request $request)
    {
        $total_records                   = self::INIT_VALUE;
        $ticketCompetitorsHistoryDetails = competitorsHistory::
            select('competitors_history.*')
            ->orderBy('competitors_history.list_time', 'DESC')
            ->where('competitors_history.production_id', '=', $request->production_id)
            ->where('section', '=', $request->section)
            ->where('row', '=', $request->row)
            ->where('seat_start', '=', $request->seat_start);
        $total_records = $total_records + $ticketCompetitorsHistoryDetails->get()->count();
        if (isset($request->take)) {
            $ticketCompetitorsHistoryDetails->offset($request->skip)->limit($request->take);
        }
        $ticketCompetitorsHistoryDetailsValue = $ticketCompetitorsHistoryDetails->get();
        foreach ($ticketCompetitorsHistoryDetailsValue as $key => $ticketCompetitorsHistoryValue) {
            $competitors_history_datetime                                       = json_decode(PermissionTrait::covertToLocalTz($ticketCompetitorsHistoryValue->list_time));
            $ticketCompetitorsHistoryDetailsValue[$key]->list_date              = $competitors_history_datetime->date;
            $ticketCompetitorsHistoryDetailsValue[$key]->list_time              = $competitors_history_datetime->time;
            $ticketCompetitorsHistoryDetailsValue[$key]->seatDetails            = $ticketCompetitorsHistoryValue->seat_start . " - " . $ticketCompetitorsHistoryValue->seat_end;
        }
        $ticket_competitors_history_data['competitors_history_details'] = $ticketCompetitorsHistoryDetailsValue->toArray();
        $ticket_competitors_history_data['total']                       = $total_records;
        return json_encode($ticket_competitors_history_data);
    }

    /**
     * Update the specified inventory in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateInventories(Request $request)
    {
        $callback = $request->callback;
        if (Module::hasAccess("Inventory", "manage")) {

            $rules = Module::validateRules("Inventory", $request, true);

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            Module::updateRow("Inventory", $request, $request->id);
            return $callback . "(" . json_encode($request) . ")";

        } else {
            return $callback . "(" . json_encode($request) . ")";
        }
    }
}
