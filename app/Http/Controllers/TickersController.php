<?php

namespace App\Http\Controllers;

use App\Helpers\ConnectionManager;
use App\Http\Traits\PermissionTrait;
use App\Module;
use App\ModuleFields;
use App\ModuleFieldTypes;
use App\Ticker;
use App\TickerHistory;
use Illuminate\Http\Request;
use Redirect;
use Validator;
use DB;

class TickersController extends PermissionsController
{
    use PermissionTrait;
    const INIT_VALUE = 0;
    public $show_action  = true;
    public $view_col     = 'service_id';
    public $listing_cols = ['id', 'service_id', 'production_id', 'listing_id', 'list_date', 'list_time', 'price', 'price_previous', 'price_min', 'price_max', 'change_amount', 'quantity', 'section', 'row', 'seat_start', 'seat_end', 'broker_id', 'prev_broker_id', 'description', 'ticket_action_id', 'ticket_type_id', 'ticket_source_id', 'ticket_source_date_add', 'proxy_target_id'];

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
     * Display a listing of the Ticker.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $module = Module::get('Ticker');

        if (Module::hasAccess($module->id, "access")) {
            return View('tickers.index', [
                'show_actions' => $this->show_action,
                'listing_cols' => $this->listing_cols,
                'module'       => $module,
            ]);
        } else {
            return Redirect::back()->with('message', 'You are not authorized to use this functionality!');
        }
    }

    public function getTickers(Request $request)
    {
        $total_records = self::INIT_VALUE;

        $TickerDetails = Ticker::distinct('ticker.production_id')
            ->select('ticker.*', 'production.production_name' , 'production.event_date', 'venue.venue_name', DB::raw('max(ticker.list_date) as listDate'))
            ->leftjoin('production', 'production.production_id', 'ticker.production_id')
            ->leftjoin('venue', 'venue.venue_id', 'production.venue_id')
            ->groupBy('ticker.production_id')
            ->orderBy('listDate', 'DESC');
        $total_records = $total_records + $TickerDetails->get()->count();

        if (isset($request->take)) {
            $TickerDetails->offset($request->skip)->limit($request->take);
        }
        $TickerValues        = $TickerDetails->get();
        $templateDefineArray = array("Multiselect");
        $fields_popup        = ModuleFields::getModuleFields('Ticker');
        $module              = Module::where('name', 'Ticker')->first();
        foreach ($TickerValues as $key => $value) {
            for ($j = self::INIT_VALUE; $j < count($this->listing_cols); $j++) {
                $col = $this->listing_cols[$j];
                if (isset($value[$col]) && !!$value[$col] && $fields_popup[$col] != null && starts_with($fields_popup[$col]->popup_vals, "@")) {
                    $field_type = ModuleFieldTypes::find($fields_popup[$col]->field_type);
                    if (in_array($field_type->name, $templateDefineArray)) {
                        $TickerValues[$key][$fields_popup[$col]->popup_field_id] = ModuleFields::getKendoFieldValue($fields_popup[$col], $value[$col], $module->provider_id);
                    } else {
                        $TickerValues[$key][$fields_popup[$col]->popup_field_name] = ModuleFields::getFieldValue($fields_popup[$col], $value[$col], $module->provider_id);
                    }

                } else if (isset($value[$col]) && !!$value[$col] && isset($fields_popup[$col]) && !!$fields_popup[$col] && starts_with($fields_popup[$col]->popup_vals, "[")) {
                    $field_type = ModuleFieldTypes::find($fields_popup[$col]->field_type);
                    if (in_array($field_type->name, $templateDefineArray)) {
                        $TestingEventValues[$key][$fields_popup[$col]->colname] = json_decode($TestingEventValues[$key][$fields_popup[$col]->colname], true);
                    }

                }
            }
        }
        if (isset($request->filter)) {
            $searchFilter = $request->filter['filters'][self::INIT_VALUE]['value'];
            if ($searchFilter) {
                foreach ($TickerValues as $TickerKey => $TickerValue) {
                    $flagValue = false;
                    foreach ($request->filter['filters'] as $filterKey => $filterValue) {
                        if (is_array($TickerValue[$filterValue['field']])) {
                            if (in_array($searchFilter, $TickerValue[$filterValue['field']])) {
                                $flagValue = true;
                            }
                        } else {
                            if (stripos($TickerValue[$filterValue['field']], $searchFilter) !== false) {
                                $flagValue = true;
                            }
                        }
                    }
                    if ($flagValue == false) {
                        unset($TickerValues[$TickerKey]);
                        $total_records = $total_records - 1;
                    }
                }
            }
        }
        $TickerValues            = $TickerValues->toArray();
        $Tickers_data['Tickers'] = array_values($TickerValues);
        $Tickers_data['total']   = $total_records;
        return json_encode($Tickers_data);
    }
    public function getTickerDetailsList(Request $request)
    {
        $total_records = self::INIT_VALUE;
        $tickerDetails = Ticker::
            select('ticker.*', 'production.event_date','broker.broker_name','crosswalk_section.*','service.service_name')
            ->join('production', 'production.production_id', 'ticker.production_id')
            ->join('service', 'service.service_id', 'ticker.service_id')
            ->join('crosswalk_section', 'crosswalk_section.data_td', 'ticker.section')
            ->join('broker', 'broker.broker_id', 'ticker.broker_id')
            ->orderBy('ticker.list_time', 'DESC')
            ->groupBy('ticker.listing_id')
            ->where('ticker.production_id', '=', $request->production_id);

        $total_records = $total_records + $tickerDetails->get()->count();
        if (isset($request->take)) {
            $tickerDetails->offset($request->skip)->limit($request->take);
        }
        if (isset($request->filter['filters'])) {
            $filterField = $request->filter['filters'][self::INIT_VALUE]['field'];
            $searchFilter = $request->filter['filters'][self::INIT_VALUE]['value'];
            if ($searchFilter) {
                $tickerDetails->where(function ($query) use ($searchFilter, $request) {
                    foreach ($request->filter['filters'] as $filterKey => $filterValue) {
                        if ($request->filter['filters'][self::INIT_VALUE]['operator'] == 'eq') {
                            $query->orWhere($filterValue['field'], '=', $searchFilter);
                        } elseif ($request->filter['filters'][self::INIT_VALUE]['operator'] == 'neq') {
                            $query->orWhere($filterValue['field'], '!=', $searchFilter);
                        } else {
                            $query->orWhere($filterValue['field'], 'LIKE', '%' . $searchFilter . '%');
                        }
                    }
                });
            }
        }
        $tickerDetailsValue = $tickerDetails->get();
        foreach ($tickerDetailsValue as $key => $ticketValue) {
            $ticker_datetime                                  = json_decode(PermissionTrait::covertToLocalTz($ticketValue->list_time));
            $tickerDetailsValue[$key]->list_date              = $ticker_datetime->date;
            $tickerDetailsValue[$key]->list_time              = $ticker_datetime->time;
            $tickerDetailsValue[$key]->list_date_time         = $ticker_datetime->date." ".$ticker_datetime->time;
            $tickerDetailsValue[$key]->seatDetails            = $ticketValue->seat_start . " - " . $ticketValue->seat_end;
            $tickerDetailsValue[$key]->data_td                = $ticketValue->section;
            if($ticketValue->service_name == 'eventinventory' ){
               $tickerDetailsValue[$key]->data_sh = $ticketValue->data_sh;
            } else if ($ticketValue->service_name == 'stubhub' ) {
                $tickerDetailsValue[$key]->data_sh = $ticketValue->data_td;
            }
        }
        $ticket_ticker_data['total']          = $total_records;
        $ticket_ticker_data['ticker_details'] = $tickerDetailsValue->toArray();
        return json_encode($ticket_ticker_data);
    }
    public function getTickersHistory(Request $request)
    {
        $total_records        = self::INIT_VALUE;
        $TickerHistoryDetails = TickerHistory::distinct('ticker_history.production_id')
            ->select('ticker_history.*', 'production.production_name' , 'production.event_date', 'venue.venue_name', DB::raw('max(ticker_history.list_date) as listDate'))
            ->leftjoin('production', 'production.production_id', 'ticker_history.production_id')
            ->leftjoin('venue', 'venue.venue_id', 'production.venue_id')
            ->groupBy('ticker_history.production_id')
            ->orderBy('listDate', 'DESC');
        $total_records = $total_records + $TickerHistoryDetails->get()->count();

        if (isset($request->take)) {
            $TickerHistoryDetails->offset($request->skip)->limit($request->take);
        }
        $TickerHistoryValues = $TickerHistoryDetails->get();
        $templateDefineArray = array("Multiselect");
        $fields_popup        = ModuleFields::getModuleFields('Ticker');
        $module              = Module::where('name', 'Ticker')->first();
        foreach ($TickerHistoryValues as $key => $value) {
            for ($j = self::INIT_VALUE; $j < count($this->listing_cols); $j++) {
                $col = $this->listing_cols[$j];
                if (isset($value[$col]) && !!$value[$col] && $fields_popup[$col] != null && starts_with($fields_popup[$col]->popup_vals, "@")) {
                    $field_type = ModuleFieldTypes::find($fields_popup[$col]->field_type);
                    if (in_array($field_type->name, $templateDefineArray)) {
                        $TickerHistoryValues[$key][$fields_popup[$col]->popup_field_id] = ModuleFields::getKendoFieldValue($fields_popup[$col], $value[$col], $module->provider_id);
                    } else {
                        $TickerHistoryValues[$key][$fields_popup[$col]->popup_field_name] = ModuleFields::getFieldValue($fields_popup[$col], $value[$col], $module->provider_id);
                    }

                } else if (isset($value[$col]) && !!$value[$col] && isset($fields_popup[$col]) && !!$fields_popup[$col] && starts_with($fields_popup[$col]->popup_vals, "[")) {
                    $field_type = ModuleFieldTypes::find($fields_popup[$col]->field_type);
                    if (in_array($field_type->name, $templateDefineArray)) {
                        $TestingEventValues[$key][$fields_popup[$col]->colname] = json_decode($TestingEventValues[$key][$fields_popup[$col]->colname], true);
                    }

                }
            }
        }
        if (isset($request->filter)) {
            $searchFilter = $request->filter['filters'][self::INIT_VALUE]['value'];
            if ($searchFilter) {
                foreach ($TickerHistoryValues as $TickerHistoryKey => $TickerHistoryValue) {
                    $flagValue = false;
                    foreach ($request->filter['filters'] as $filterKey => $filterValue) {
                        if (is_array($TickerHistoryValue[$filterValue['field']])) {
                            if (in_array($searchFilter, $TickerHistoryValue[$filterValue['field']])) {
                                $flagValue = true;
                            }
                        } else {
                            if (stripos($TickerHistoryValue[$filterValue['field']], $searchFilter) !== false) {
                                $flagValue = true;
                            }
                        }
                    }
                    if ($flagValue == false) {
                        unset($TickerHistoryValues[$TickerHistoryKey]);
                        $total_records = $total_records - 1;
                    }
                }
            }
        }
        $TickerHistoryValues                     = $TickerHistoryValues->toArray();
        $Tickers_history_data['Tickers_History'] = array_values($TickerHistoryValues);
        $Tickers_history_data['total']           = $total_records;
        return json_encode($Tickers_history_data);
    }

    public function getTickerHistoryDetailsList(Request $request)
    {
        $total_records        = self::INIT_VALUE;
        $tickerHistoryDetails = TickerHistory::
            select('ticker_history.*','broker.broker_name')
            ->leftjoin('broker', 'broker.broker_id', 'ticker_history.broker_id')
            ->orderBy('ticker_history.list_time', 'DESC')
            ->where('ticker_history.production_id', '=', $request->production_id);
        if (isset($request->filter['filters'])) {
            $filterField = $request->filter['filters'][self::INIT_VALUE]['field'];
            $searchFilter = $request->filter['filters'][self::INIT_VALUE]['value'];
            if ($searchFilter) {
                $tickerHistoryDetails->where(function ($query) use ($searchFilter, $request) {
                    foreach ($request->filter['filters'] as $filterKey => $filterValue) {
                        if ($request->filter['filters'][self::INIT_VALUE]['operator'] == 'eq') {
                            $query->orWhere('ticker_history.' . $filterValue['field'], '=', $searchFilter);
                        } elseif ($request->filter['filters'][self::INIT_VALUE]['operator'] == 'neq') {
                            $query->orWhere('ticker_history.' . $filterValue['field'], '!=', $searchFilter);
                        } else {
                            $query->orWhere('ticker_history.' . $filterValue['field'], 'LIKE', '%' . $searchFilter . '%');
                        }
                    }
                });
            }
        }
        $total_records = $total_records + $tickerHistoryDetails->get()->count();
        if (isset($request->take)) {
            $tickerHistoryHistoryDetails->offset($request->skip)->limit($request->take);
        }
        $tickerHistoryDetailsValue = $tickerHistoryDetails->get();
        foreach ($tickerHistoryDetailsValue as $key => $tickerHistoryValue) {
            $ticker_details_history_datetime                         = json_decode(PermissionTrait::covertToLocalTz($tickerHistoryValue->list_time));
            $tickerHistoryDetailsValue[$key]->list_date              = $ticker_details_history_datetime->date;
            $tickerHistoryDetailsValue[$key]->list_time              = $ticker_details_history_datetime->time;
            $tickerHistoryDetailsValue[$key]->list_date_time         = $ticker_details_history_datetime->date." ".$ticker_details_history_datetime->time;
            $tickerHistoryDetailsValue[$key]->seatDetails            = $tickerHistoryValue->seat_start . " - " . $tickerHistoryValue->seat_end;
        }
        $ticket_history_detail_data['ticker_history_details'] = $tickerHistoryDetailsValue->toArray();
        $ticket_history_detail_data['total']                  = $total_records;
        return json_encode($ticket_history_detail_data);
    }
    /**
     * Update the specified ticker in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateTickers(Request $request)
    {
        $callback = $request->callback;
        if (Module::hasAccess("Ticker", "manage")) {

            $rules = Module::validateRules("Ticker", $request, true);

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            Module::updateRow("Ticker", $request, $request->id);
            return $callback . "(" . json_encode($request) . ")";

        } else {
            return $callback . "(" . json_encode($request) . ")";
        }
    }
}
