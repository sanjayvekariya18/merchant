<?php

namespace App\Http\Controllers;

use App\Helpers\ConnectionManager;
use App\Http\Controllers\Controller;
use App\Http\Traits\PermissionTrait;
use App\Proxy_details;
use App\Purchase;
use App\Sales;
use App\Purchasing;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Redirect;
use Session;

/**
 * Class TicketListController.
 *
 */
class TicketListController extends PermissionsController
{
    use PermissionTrait;
    const INIT_VALUE = 0;
    const TIME_INTERNAL_SUBMIT_VALUE = 1000;
    const EVENT_DAY_START_STRING = 7;
    const EVENT_DAY_END_STRING = 2;
    const EVENT_MONTH_END_STRING = 3;
    const EVENT_DATE_YEAR_STRING = 4;
    const EVENT_DATE_FIELD = 5;
    public function __construct()
    {
        parent::__construct();
        $connectionStatus = ConnectionManager::setDbConfig('ticket_event', 'mysqlDynamicConnector');
        if (strcmp($connectionStatus['type'], "error") == self::INIT_VALUE) {
            Session::flash('type', $connectionStatus['type']);
            Session::flash('msg', $connectionStatus['message']);
        }
    }

    public function ticketList()
    {
        if ($this->permissionDetails('Ticket_list', 'access')) {
            $permissions = $this->getPermission("Ticket_list");
            return view('ticket_list.index', compact('permissions'));
        } else {
            return Redirect::back()->with('message', 'You are not authorized to use this functionality!');
        }

    }
    public function gatherTicketPurchasedWithoutMissed(Request $request)
    {
        $total_records              = self::INIT_VALUE;
        $tickePurchasedEventDetails = Purchase::distinct('purchase.production_id')->
            select('purchase.*', 'production.production_name' , 'production.event_date', 'venue.venue_name', DB::raw('max(purchase.purchase_date) as purchaseDate'))
            ->leftjoin('production', 'production.production_id', 'purchase.production_id')
            ->leftjoin('venue', 'venue.venue_id', 'production.venue_id')
            ->where('purchase.order_id', '>', self::INIT_VALUE)
            ->groupBy('purchase.production_id')
            ->orderBy('purchaseDate', 'DESC');
        if (isset($request->filter['filters'])) {
            $searchFilter = $request->filter['filters'][self::INIT_VALUE]['value'];
            if ($searchFilter) {
                $tickePurchasedEventDetails->where(function ($query) use ($searchFilter, $request) {
                    foreach ($request->filter['filters'] as $filterKey => $filterValue) {
                        if ($filterValue['field'] == 'event_date') {
                            $searchFilter = str_replace(' ', '', $searchFilter);
                        }
                        if ($filterValue['field'] == 'production_id') {
                            $query->orWhere('production.production_id', 'LIKE', '%' . $searchFilter . '%');
                        } else {
                            $query->orWhere($filterValue['field'], 'LIKE', '%' . $searchFilter . '%');
                        }
                    }
                });
            }
        }
        $total_records = $total_records + $tickePurchasedEventDetails->get()->count();
        if (isset($request->take)) {
            $tickePurchasedEventDetails->offset($request->skip)->limit($request->take);
        }
        $ticketPurchasedEventValues = $tickePurchasedEventDetails->get();
        foreach ($ticketPurchasedEventValues as $key => $tickePurchasedEventDetailsValue) {
            $event_datetime                                  = json_decode(PermissionTrait::covertToLocalTz($tickePurchasedEventDetailsValue->purchase_time));
            $ticketPurchasedEventValues[$key]->purchase_date = $event_datetime->date;
            $ticketPurchasedEventValues[$key]->purchase_time = $event_datetime->time;
            $eventDate                                       = $tickePurchasedEventDetailsValue->event_date;
            $eventyear                                       = substr($eventDate, self::INIT_VALUE, self::EVENT_DATE_YEAR_STRING);
            $eventMonth                                      = substr($eventDate, self::EVENT_DATE_YEAR_STRING, self::EVENT_MONTH_END_STRING);
            $eventDay                                        = substr($eventDate, self::EVENT_DAY_START_STRING, self::EVENT_DAY_END_STRING);
            $eventDate                                       = $eventMonth . ' ' . $eventDay . ' ' . $eventyear;
            $ticketPurchasedEventValues[$key]->event_date    = date('Y M d', strtotime($eventDate));
        }
        $ticket_purchased_list_data['ticket_purchased_events'] = $ticketPurchasedEventValues->toArray();
        $ticket_purchased_list_data['total']                   = $total_records;
        return json_encode($ticket_purchased_list_data);
    }
    public function getTicketPurchasedListingDetails(Request $request)
    {
        $total_records                 = self::INIT_VALUE;
        $ticketPurchasedListingDetails = Purchase::
            select('purchase.*')
            ->where('purchase.order_id', '>', self::INIT_VALUE)
            ->where('listing_id', '=', $request->listing_id);
        if (isset($request->filter['filters'])) {
            $filterValue = $request->filter['filters'][self::INIT_VALUE]['value'];
            $filterField = $request->filter['filters'][self::INIT_VALUE]['field'];
            if ($request->filter['filters'][self::INIT_VALUE]['operator'] === 'eq') {
                $ticketPurchasedListingDetails->where($filterField, '=', $filterValue);
            } elseif ($request->filter['filters'][self::INIT_VALUE]['operator'] === 'neq') {
                $ticketPurchasedListingDetails->where($filterField, '!=', $filterValue);
            } else {
                $ticketPurchasedListingDetails->where($filterField, 'LIKE', '%' . $filterValue . '%');
            }
        }
        $total_records = $total_records + $ticketPurchasedListingDetails->get()->count();
        if (isset($request->take)) {
            $ticketPurchasedListingDetails->offset($request->skip)->limit($request->take);
        }
        $ticketPurchasedListingValues = $ticketPurchasedListingDetails->get();
        foreach ($ticketPurchasedListingValues as $key => $ticketPurchasedListingValue) {
            $ticketPurchasedListingValues[$key]->time_connect          = date("H:i:s", $ticketPurchasedListingValue->time_connect / self::TIME_INTERNAL_SUBMIT_VALUE);
            $ticketPurchasedListingValues[$key]->time_internal_submit  = date("H:i:s", $ticketPurchasedListingValue->time_internal_submit / self::TIME_INTERNAL_SUBMIT_VALUE);
            $ticketPurchasedListingValues[$key]->time_internal_receive = date("H:i:s", $ticketPurchasedListingValue->time_internal_receive / self::TIME_INTERNAL_SUBMIT_VALUE);
            $ticketPurchasedListingValues[$key]->time_submit           = date("H:i:s", $ticketPurchasedListingValue->time_submit / self::TIME_INTERNAL_SUBMIT_VALUE);
            $ticketPurchasedListingValues[$key]->time_response         = date("H:i:s", $ticketPurchasedListingValue->time_response / self::TIME_INTERNAL_SUBMIT_VALUE);
            $ticketPurchasedListingValues[$key]->time_data             = date("d-m-Y H:i:s", $ticketPurchasedListingValue->time_data / self::TIME_INTERNAL_SUBMIT_VALUE);

        }
        $ticket_purchased_listing_data['ticket_purchased_listing'] = $ticketPurchasedListingValues->toArray();
        $ticket_purchased_listing_data['total']                    = $total_records;
        return json_encode($ticket_purchased_listing_data);
    }
    public function getTicketMissedListingDetails(Request $request)
    {
        $total_records              = self::INIT_VALUE;
        $ticketMissedListingDetails = Purchase::
            select('purchase.*')
            ->where('purchase.order_id', '=', self::INIT_VALUE)
            ->where('listing_id', '=', $request->listing_id);
        if (isset($request->filter['filters'])) {
            $filterValue = $request->filter['filters'][self::INIT_VALUE]['value'];
            $filterField = $request->filter['filters'][self::INIT_VALUE]['field'];
            if ($request->filter['filters'][self::INIT_VALUE]['operator'] === 'eq') {
                $ticketMissedListingDetails->where($filterField, '=', $filterValue);
            } elseif ($request->filter['filters'][self::INIT_VALUE]['operator'] === 'neq') {
                $ticketMissedListingDetails->where($filterField, '!=', $filterValue);
            } else {
                $ticketMissedListingDetails->where($filterField, 'LIKE', '%' . $filterValue . '%');
            }
        }
        $total_records = $total_records + $ticketMissedListingDetails->get()->count();
        if (isset($request->take)) {
            $ticketMissedListingDetails->offset($request->skip)->limit($request->take);
        }
        $ticketMissedListingValues = $ticketMissedListingDetails->get();
        foreach ($ticketMissedListingValues as $key => $ticketMissedListingValue) {
            $ticketMissedListingValues[$key]->time_connect          = date("H:i:s", $ticketMissedListingValue->time_connect / self::TIME_INTERNAL_SUBMIT_VALUE);
            $ticketMissedListingValues[$key]->time_internal_submit  = date("H:i:s", $ticketMissedListingValue->time_internal_submit / self::TIME_INTERNAL_SUBMIT_VALUE);
            $ticketMissedListingValues[$key]->time_internal_receive = date("H:i:s", $ticketMissedListingValue->time_internal_receive / self::TIME_INTERNAL_SUBMIT_VALUE);
            $ticketMissedListingValues[$key]->time_submit           = date("H:i:s", $ticketMissedListingValue->time_submit / self::TIME_INTERNAL_SUBMIT_VALUE);
            $ticketMissedListingValues[$key]->time_response         = date("H:i:s", $ticketMissedListingValue->time_response / self::TIME_INTERNAL_SUBMIT_VALUE);
            $ticketMissedListingValues[$key]->time_data             = date("d-m-Y H:i:s", $ticketMissedListingValue->time_data / self::TIME_INTERNAL_SUBMIT_VALUE);

        }
        $ticket_missed_listing_data['ticket_missed_listing'] = $ticketMissedListingValues->toArray();
        $ticket_missed_listing_data['total']                 = $total_records;
        return json_encode($ticket_missed_listing_data);
    }
    public function gatherTicketPurchasedWithMissed(Request $request)
    {
        $total_records                    = self::INIT_VALUE;
        $ticketPurchasedWithMissedDetails = Purchase::
            select('purchase.*')
            ->orderBy('purchase.purchase_date', 'DESC')
            ->orderBy('purchase.purchase_time', 'DESC')
            ->where('purchase.order_id', '>', self::INIT_VALUE)
            ->where('purchase.production_id', '=', $request->production_id);
        if (isset($request->filter['filters'])) {
            $filterValue = $request->filter['filters'][self::INIT_VALUE]['value'];
            $filterField = $request->filter['filters'][self::INIT_VALUE]['field'];
            if ($request->filter['filters'][self::INIT_VALUE]['operator'] == 'eq') {
                $ticketPurchasedWithMissedDetails->where($filterField, '=', $filterValue);
            } elseif ($request->filter['filters'][self::INIT_VALUE]['operator'] == 'neq') {
                $ticketPurchasedWithMissedDetails->where($filterField, '!=', $filterValue);
            } else {
                $ticketPurchasedWithMissedDetails->where($filterField, 'LIKE', '%' . $filterValue . '%');
            }
        }
        $total_records = $total_records + $ticketPurchasedWithMissedDetails->get()->count();
        if (isset($request->take)) {
            $ticketPurchasedWithMissedDetails->offset($request->skip)->limit($request->take);
        }
        $ticketPurchasedWithMissedValues = $ticketPurchasedWithMissedDetails->get();
        foreach ($ticketPurchasedWithMissedValues as $key => $ticketPurchasedWithMissedValue) {
            $purchasingExits = Purchasing::where('listing_id', $ticketPurchasedWithMissedValue->listing_id)->first();
            if (isset($purchasingExits->listing_id)) {
                $ticketPurchasedWithMissedValues[$key]->listingId = $purchasingExits->listing_id;
            }
            $event_datetime                                               = json_decode(PermissionTrait::covertToLocalTz($ticketPurchasedWithMissedValue->purchase_time));
            $ticketPurchasedWithMissedValues[$key]->purchase_date         = $event_datetime->date;
            $ticketPurchasedWithMissedValues[$key]->purchase_time         = $event_datetime->time;
            $ticketPurchasedWithMissedValues[$key]->time_connect          = date("H:i:s", $ticketPurchasedWithMissedValue->time_connect / self::TIME_INTERNAL_SUBMIT_VALUE);
            $ticketPurchasedWithMissedValues[$key]->time_internal_submit  = date("H:i:s", $ticketPurchasedWithMissedValue->time_internal_submit / self::TIME_INTERNAL_SUBMIT_VALUE);
            $ticketPurchasedWithMissedValues[$key]->time_internal_receive = date("H:i:s", $ticketPurchasedWithMissedValue->time_internal_receive / self::TIME_INTERNAL_SUBMIT_VALUE);
            $ticketPurchasedWithMissedValues[$key]->time_submit           = date("H:i:s", $ticketPurchasedWithMissedValue->time_submit / self::TIME_INTERNAL_SUBMIT_VALUE);
            $ticketPurchasedWithMissedValues[$key]->time_response         = date("H:i:s", $ticketPurchasedWithMissedValue->time_response / self::TIME_INTERNAL_SUBMIT_VALUE);
            $ticketPurchasedWithMissedValues[$key]->time_data             = date("d-m-Y H:i:s", $ticketPurchasedWithMissedValue->time_data / self::TIME_INTERNAL_SUBMIT_VALUE);

        }
        $ticket_purchased_with_missed_data['ticket_purchased_with_missed'] = $ticketPurchasedWithMissedValues->toArray();
        $ticket_purchased_with_missed_data['total']                        = $total_records;
        return json_encode($ticket_purchased_with_missed_data);

    }
    public function ticketListingEventList(Request $request)
    {
        $total_records            = self::INIT_VALUE;
        $tickeListingEventDetails = Purchase::select(DB::raw('sum(order_id > 0) bought_count,sum(order_id = 0) tickets, max(purchase.purchase_date) as purchaseDate,purchase.*, production.production_name, production.event_date , venue.venue_name'))
            ->leftjoin('production', 'production.production_id', 'purchase.production_id')->leftjoin('venue', 'venue.venue_id', 'production.venue_id')->groupBy('purchase.production_id')->orderBy('purchaseDate', 'DESC');
        if (isset($request->filter['filters'])) {
            $searchFilter = $request->filter['filters'][self::INIT_VALUE]['value'];
            if ($searchFilter) {
                $tickeListingEventDetails->where(function ($query) use ($searchFilter, $request) {
                    foreach ($request->filter['filters'] as $filterKey => $filterValue) {
                        if ($filterValue['field'] == 'event_date') {
                            $searchFilter = str_replace(' ', '', $searchFilter);
                        }
                        if ($filterValue['field'] == 'production_id') {
                            $query->orWhere('purchase.production_id', 'LIKE', '%' . $searchFilter . '%');
                        } else if ($filterValue['field'] == 'venue_name') {
                            $query->orWhere('venue.venue_name', 'LIKE', '%' . $searchFilter . '%');
                        } else if ($filterValue['field'] == 'bought_count') {
                            $query->havingRaw('COUNT(order_id > 0) = ' . $searchFilter);
                        } else {
                            $query->orWhere($filterValue['field'], 'LIKE', '%' . $searchFilter . '%');
                        }

                    }
                });
            }
        }
        if (isset($request->sort)) {
            $sortDirection = $request->sort[self::INIT_VALUE]['dir'];
            $sortFields    = $request->sort[self::INIT_VALUE]['field'];
            if ($sortFields === 'production_name' || $sortFields === 'event_date') {
                $sortFields = 'production.' . $request->sort[self::INIT_VALUE]['field'];
            } elseif ($sortFields === 'bought_count') {
                $sortFields = $request->sort[self::INIT_VALUE]['field'];
            } else {
                $sortFields = 'purchase.' . $request->sort[self::INIT_VALUE]['field'];
            }
            $tickeListingEventDetails->orderBy($sortFields, $sortDirection);
        }
        $total_records = $total_records + $tickeListingEventDetails->get()->count();
        if (isset($request->take)) {
            $tickeListingEventDetails->offset($request->skip)->limit($request->take);
        }
        $tickeListingEventValue = $tickeListingEventDetails->get();
        foreach ($tickeListingEventValue as $key => $tickeListingEventDetailsValue) {
            $seatsDetails = Purchase::where('production_id', $tickeListingEventDetailsValue->production_id)->where('order_id', '>','0')->get();
            $seatsCount = self::INIT_VALUE;
            $seatsValue = self::INIT_VALUE;
            foreach ($seatsDetails as $seatsDetailsValue) {
                $seatsCount++;
                $seatsValue += $seatsDetailsValue['quantity'];
            }
            $event_datetime                              = json_decode(PermissionTrait::covertToLocalTz($tickeListingEventDetailsValue->purchase_time));
            $tickeListingEventValue[$key]->purchase_date = $event_datetime->date;
            $tickeListingEventValue[$key]->purchase_time = $event_datetime->time;
            $tickeListingEventValue[$key]->quantity      = $seatsValue;
            $eventDate                                   = $tickeListingEventDetailsValue->event_date;
            $eventyear                                   = substr($eventDate, self::INIT_VALUE, self::EVENT_DATE_YEAR_STRING);
            $eventMonth                                  = substr($eventDate, self::EVENT_DATE_YEAR_STRING, self::EVENT_MONTH_END_STRING);
            $eventDay                                    = substr($eventDate, self::EVENT_DAY_START_STRING, self::EVENT_DAY_END_STRING);
            $eventDate                                   = $eventMonth . ' ' . $eventDay . ' ' . $eventyear;
            $tickeListingEventValue[$key]->event_date    = date('Y M d', strtotime($eventDate));
        }
            $ticket_listing_list_data['ticket_listing_events'] = $tickeListingEventValue->toArray();
            $ticket_listing_list_data['total']                 = $total_records;
        return json_encode($ticket_listing_list_data);
    }
    public function gatherTicketMissedWithPurchase(Request $request)
    {
        $total_records                   = self::INIT_VALUE;
        $ticketMissedWithPurchaseDetails = Purchase::
            select('purchase.*')
            ->orderBy('purchase.purchase_date', 'DESC')
            ->orderBy('purchase.purchase_time', 'DESC')
            ->where('purchase.order_id', '=', self::INIT_VALUE)
            ->where('purchase.production_id', '=', $request->production_id);
        if (isset($request->filter['filters'])) {
            $filterValue = $request->filter['filters'][self::INIT_VALUE]['value'];
            $filterField = $request->filter['filters'][self::INIT_VALUE]['field'];
            if ($request->filter['filters'][self::INIT_VALUE]['operator'] == 'eq') {
                $ticketMissedWithPurchaseDetails->where($filterField, '=', $filterValue);
            } elseif ($request->filter['filters'][self::INIT_VALUE]['operator'] == 'neq') {
                $ticketMissedWithPurchaseDetails->where($filterField, '!=', $filterValue);
            } else {
                $ticketMissedWithPurchaseDetails->where($filterField, 'LIKE', '%' . $filterValue . '%');
            }
        }
        $total_records = $total_records + $ticketMissedWithPurchaseDetails->get()->count();
        if (isset($request->take)) {
            $ticketMissedWithPurchaseDetails->offset($request->skip)->limit($request->take);
        }
        $ticketMissedWithPurchaseValues = $ticketMissedWithPurchaseDetails->get();
        foreach ($ticketMissedWithPurchaseValues as $key => $ticketMissedWithPurchaseValue) {
            $purchasingExits = Purchasing::where('listing_id', $ticketMissedWithPurchaseValue->listing_id)->first();
            if (isset($purchasingExits->listing_id)) {
                $ticketMissedWithPurchaseValues[$key]->listingId = $purchasingExits->listing_id;
            }
            $event_datetime                                              = json_decode(PermissionTrait::covertToLocalTz($ticketMissedWithPurchaseValue->purchase_time));
            $ticketMissedWithPurchaseValues[$key]->purchase_date         = $event_datetime->date;
            $ticketMissedWithPurchaseValues[$key]->purchase_time         = $event_datetime->time;
            $ticketMissedWithPurchaseValues[$key]->time_connect          = date("H:i:s", $ticketMissedWithPurchaseValue->time_connect / self::TIME_INTERNAL_SUBMIT_VALUE);
            $ticketMissedWithPurchaseValues[$key]->time_internal_submit  = date("H:i:s", $ticketMissedWithPurchaseValue->time_internal_submit / self::TIME_INTERNAL_SUBMIT_VALUE);
            $ticketMissedWithPurchaseValues[$key]->time_internal_receive = date("H:i:s", $ticketMissedWithPurchaseValue->time_internal_receive / self::TIME_INTERNAL_SUBMIT_VALUE);
            $ticketMissedWithPurchaseValues[$key]->time_submit           = date("H:i:s", $ticketMissedWithPurchaseValue->time_submit / self::TIME_INTERNAL_SUBMIT_VALUE);
            $ticketMissedWithPurchaseValues[$key]->time_response         = date("H:i:s", $ticketMissedWithPurchaseValue->time_response / self::TIME_INTERNAL_SUBMIT_VALUE);
            $ticketMissedWithPurchaseValues[$key]->time_data             = date("d-m-Y H:i:s", $ticketMissedWithPurchaseValue->time_data / self::TIME_INTERNAL_SUBMIT_VALUE);
        }
        $ticket_missed_with_purchase_list_data['ticket_missed_with_purchase'] = $ticketMissedWithPurchaseValues->toArray();
        $ticket_missed_with_purchase_list_data['total']                       = $total_records;
        return json_encode($ticket_missed_with_purchase_list_data);
    }
    public function gatherTicketMissedWithoutPurchase(Request $request)
    {
        $total_records           = self::INIT_VALUE;
        $tickeMissedEventDetails = Purchase::distinct('purchase.production_id')->
            select('purchase.*', 'purchasing.proxy_node_target_id', 'production.production_name', 'production.event_date', 'venue.venue_name', DB::raw('max(purchase.purchase_date) as purchaseDate'))
            ->leftjoin('purchasing', 'purchasing.listing_id', 'purchase.listing_id')
            ->leftjoin('production', 'production.production_id', 'purchase.production_id')
            ->leftjoin('venue', 'venue.venue_id', 'production.venue_id')
            ->where('purchase.order_id', '=', self::INIT_VALUE)
            ->groupBy('purchase.production_id')
            ->orderBy('purchaseDate', 'DESC');
        if (isset($request->filter['filters'])) {
            $searchFilter = $request->filter['filters'][self::INIT_VALUE]['value'];
            if ($searchFilter) {
                $tickeMissedEventDetails->where(function ($query) use ($searchFilter, $request) {
                    foreach ($request->filter['filters'] as $filterKey => $filterValue) {
                        if ($filterValue['field'] == 'event_date') {
                            $searchFilter = str_replace(' ', '', $searchFilter);
                        }
                        if ($filterValue['field'] == 'production_id') {
                            $query->orWhere('production.production_id', 'LIKE', '%' . $searchFilter . '%');
                        } else {
                            $query->orWhere($filterValue['field'], 'LIKE', '%' . $searchFilter . '%');
                        }
                    }
                });
            }
        }
        $total_records = $total_records + $tickeMissedEventDetails->get()->count();
        if (isset($request->take)) {
            $tickeMissedEventDetails->offset($request->skip)->limit($request->take);
        }
        $ticketMissedEventValues = $tickeMissedEventDetails->get();
        foreach ($ticketMissedEventValues as $key => $value) {
            $eventDate                                 = $value->event_date;
            $eventyear                                 = substr($eventDate, self::INIT_VALUE, self::EVENT_DATE_YEAR_STRING);
            $eventMonth                                = substr($eventDate, self::EVENT_DATE_YEAR_STRING, self::EVENT_MONTH_END_STRING);
            $eventDay                                  = substr($eventDate, self::EVENT_DAY_START_STRING, self::EVENT_DAY_END_STRING);
            $eventDate                                 = $eventMonth . ' ' . $eventDay . ' ' . $eventyear;
            $ticketMissedEventValues[$key]->event_date = date('Y M d', strtotime($eventDate));
        }
        $ticketMissedEventValues                         = $ticketMissedEventValues->toArray();
        $ticket_missed_list_data['ticket_missed_events'] = $ticketMissedEventValues;
        $ticket_missed_list_data['total']                = $total_records;
        return json_encode($ticket_missed_list_data);
    }
    public function proxyPurchasedEventDetails(Request $request)
    {
        $connectionStatus               = ConnectionManager::setDbConfig('proxy_location', 'mysqlDynamicConnector');
        $proxy_purchased_source_details = Proxy_details::
            where("proxy_details.target_ip", $request->purchase_proxy_ip);
        if (isset($request->filter['filters'])) {
            $filterValue = $request->filter['filters'][self::INIT_VALUE]['value'];
            $filterField = $request->filter['filters'][self::INIT_VALUE]['field'];
            if ($request->filter['filters'][self::INIT_VALUE]['operator'] == 'eq') {
                $proxy_purchased_source_details->where($filterField, '=', $filterValue);
            } elseif ($request->filter['filters'][self::INIT_VALUE]['operator'] == 'neq') {
                $proxy_purchased_source_details->where($filterField, '!=', $filterValue);
            } else {
                $proxy_purchased_source_details->where($filterField, 'LIKE', '%' . $filterValue . '%');
            }
        }
        $proxy_purchased_source_details = $proxy_purchased_source_details->get();
        foreach ($proxy_purchased_source_details as $key => $proxy_purchased_source_details_value) {
            $event_datetime                                     = json_decode(PermissionTrait::covertToLocalTz($proxy_purchased_source_details_value->request_time));
            $proxy_purchased_source_details[$key]->request_date = $event_datetime->date;
            $proxy_purchased_source_details[$key]->request_time = $event_datetime->time;
        }
        $proxy_purchased_source_details = $proxy_purchased_source_details->toArray();
        return $proxy_purchased_source_details;
    }
    public function purchasingDetailsList(Request $request)
    {
        $purchased_details_list = Purchasing::
            where("listing_id", $request->listingId)->orderBy('purchase_date', 'DESC')->orderBy('purchase_time', 'DESC');
        $purchased_details = $purchased_details_list->get();
        foreach ($purchased_details as $key => $purchased_details_value) {
            $purchased_details[$key]->purchase_time = date("H:i:s", $purchased_details_value->purchase_time / self::TIME_INTERNAL_SUBMIT_VALUE);
            $purchase_dates                         = strtotime($purchased_details_value->purchase_date);
            $purchased_details[$key]->purchase_date = date('Y-m-d', $purchase_dates);
        }
        $purchased_details = $purchased_details->toArray();
        return $purchased_details;
    }

    public function ticketSalesEvent(Request $request)
    {
        $total_records = self::INIT_VALUE;
        $tickeSalesEventDetails = Sales::select('sales.invoice_id','sales.production_id','sales.production_name','sales.venue_id','sales.venue_name','sales.event_date','sales.event_time','sales.section','sales.row','sales.seat_quantity','sales.seat_start','sales.seat_end','sales.purchase_total','production.production_name as base_production_name','venue.venue_name as base_venue_name')
            ->leftjoin('production','production.production_id','sales.production_id')
            ->leftjoin('venue','venue.venue_id','sales.venue_id')
            ->groupBy('sales.invoice_id')
            ->orderBy('ship_date', 'DESC');
        if (isset($request->filter['filters'])) {
           $searchFilter = $request->filter['filters'][self::INIT_VALUE]['value'];
            if ($searchFilter) {
                $tickeSalesEventDetails->where(function ($query) use ($searchFilter, $request) {
                    foreach ($request->filter['filters'] as $filterKey => $filterValue) {
                        if ($filterValue['field'] == 'event_date') {
                            $searchFilter = str_replace(' ', '', $searchFilter);
                        }
                        if ($filterValue['field'] == 'base_production_name'){
                            $query->orWhere('sales.production_name', 'LIKE', '%' . $searchFilter . '%');
                        } else if ($filterValue['field'] == 'base_venue_name'){
                            $query->orWhere('venue.venue_name', 'LIKE', '%' . $searchFilter . '%');
                        } else {
                            $query->orWhere('sales.' . $filterValue['field'] . '', 'LIKE', '%' . $searchFilter . '%');
                        }
                    }
                });
            }
        }

        $total_records = $total_records + $tickeSalesEventDetails->get()->count();
        if (isset($request->take)) {
            $tickeSalesEventDetails->offset($request->skip)->limit($request->take);
        }
        $ticketSalesEventValues = $tickeSalesEventDetails->get();
        foreach ($ticketSalesEventValues as $key => $ticketSalesEventDetailsValue) {
            $ticketSalesEventValues[$key]->event_time = PermissionTrait::convertIntoTime($ticketSalesEventDetailsValue->event_time);
            $ticketSalesEventValues[$key]->event_date = date("Y m d", strtotime($ticketSalesEventDetailsValue->event_date)); 

        }
        $ticket_sales_list_data['ticket_sales_events'] = $ticketSalesEventValues->toArray();
        $ticket_sales_list_data['total']                   = $total_records;
        return json_encode($ticket_sales_list_data);
    }
}
