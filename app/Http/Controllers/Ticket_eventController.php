<?php

namespace App\Http\Controllers;

use App\Helpers\ConnectionManager;
use App\Http\Controllers\PermissionsController;
use App\Opponent;
use App\Production;
use App\Service;
use App\Ticket_venue;
use App\ProductionJson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Redirect;
use Session;

/**
 * Class Account_typeController.
 *
 * @author  The scaffold-interface created at 2018-02-18 01:01:05pm
 * @link  https://github.com/amranidev/scaffold-interface
 */
class Ticket_eventController extends PermissionsController
{
    const EVENT_DATE_SEARCH_COUNT = 4;
    const INIT_VALUE = 0;
    const EVENT_DAY_START_STRING = 7;
    const EVENT_DAY_END_STRING = 2;
    CONST EVENT_MONTH_END_STRING = 3;
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
     * Display a listing of the resource.
     *
     * @return  \Illuminate\Http\Response
     */
    public function index()
    {
        if ($this->permissionDetails('Ticket_event', 'access')) {
            $permissions = $this->getPermission("Ticket_event");
            return view('ticket_event.index', compact('permissions'));
        } else {
            return Redirect::back()->with('message', 'You are not authorized to use this functionality!');
        }
    }
    public function getVenueList(Request $request)
    {
        $venueDetails = Ticket_venue::
            select('venue.venue_id', 'venue.venue_name')->get()->toArray();
        return json_encode($venueDetails);

    }
    public function getOpponentList(Request $request)
    {
        $opponentDetails = Opponent::
            select('opponent.opponent_id', 'opponent.opponent_name')->get()->toArray();
        return json_encode($opponentDetails);

    }
    public function productionJsonDetailList(Request $request){
        $productionJsonDetail = ProductionJson::select('json')->where('production_id','=',$request->production_id)->first();
        return json_encode($productionJsonDetail->json);
    }
    public function ticketEventList(Request $request)
    {
        if (isset($request->filter)) {
            $searchFilter = $request->filter['filters'][self::INIT_VALUE]['value'];
        }
        $total_records     = self::INIT_VALUE;
        $tickeEventDetails = Production::
            select('production.*', 'venue.venue_name', 'opponent.opponent_name', 'service.service_name')
            ->join('venue', function ($join) {
                $join->on('venue.venue_id', 'production.venue_id')
                    ->on('venue.service_id', 'production.service_id');
            })
            ->join('opponent', function ($join) {
                $join->on('opponent.opponent_id', 'production.opponent_id')
                    ->on('opponent.service_id', 'opponent.service_id');
            })
            ->join('service', 'service.service_id', 'production.service_id')
            ->orderBy('production.event_date', 'DESC');
        if (isset($searchFilter)) {
            if($request->filter['filters'][self::EVENT_DATE_SEARCH_COUNT]['field'] == 'event_date') {
                $searchFilter=str_replace(' ', '', $searchFilter);
            }
            $tickeEventDetails->where('production.production_name', 'LIKE', '%' . $searchFilter . '%')
                ->orwhere('venue.venue_name', 'LIKE', '%' . $searchFilter . '%')
                ->orwhere('opponent.opponent_name', 'LIKE', '%' . $searchFilter . '%')
                ->orwhere('production.production_id', 'LIKE', '%' . $searchFilter . '%')
                ->orwhere('production.event_date', 'LIKE', '%' . $searchFilter . '%');
        }
        $total_records = $total_records + $tickeEventDetails->get()->count();

        if (isset($request->take)) {
            $tickeEventDetails->offset($request->skip)->limit($request->take);
        }
       $ticketEventValues = $tickeEventDetails->get();
        foreach ($ticketEventValues as $key => $value) {
            $eventDate                           = $value->event_date;
            $eventyear                           = substr($eventDate, self::INIT_VALUE, self::EVENT_DATE_SEARCH_COUNT);
            $eventMonth                          = substr($eventDate, self::EVENT_DATE_SEARCH_COUNT, self::EVENT_MONTH_END_STRING);
            $eventDay                            = substr($eventDate, self::EVENT_DAY_START_STRING, self::EVENT_DAY_END_STRING);
            $eventDate                           = $eventMonth . ' ' . $eventDay . ' ' . $eventyear;
            $ticketEventValues[$key]->event_date = date('Y M d', strtotime($eventDate));
        }
        $ticket_list_data['ticket_events'] = $ticketEventValues->toArray();
        $ticket_list_data['total']         = $total_records;
        return json_encode($ticket_list_data);
    }

    public function updateEventList(Request $request)
    {
        $callback                        = $request->callback;
        $productionData                  = Production::findOrFail($request->production_id);
        $productionData->production_name = $request->production_name;
        $productionData->venue_id        = $request->venue_id;
        $productionData->opponent_id     = $request->opponent_id;
        $productionData->event_date      = $request->event_date;
        $productionData->url_seating     = $request->url_seating;
        $productionData->save();
        return $callback . "(" . json_encode($productionData) . ")";
    }

    public function ticketVenueList(Request $request)
    {
        if (isset($request->filter)) {
            $searchFilter = $request->filter['filters'][self::INIT_VALUE]['value'];
        }
        $total_records      = self::INIT_VALUE;
        $ticketVenueDetails = Ticket_venue::
            select('venue.*', 'service.service_name','category.category_name')
            ->join('service', 'service.service_id', 'venue.service_id')
            ->join('category', 'category.category_id', 'venue.event_type_id');
        if (isset($searchFilter)) {
            $ticketVenueDetails->where('venue.venue_name', 'LIKE', '%' . $searchFilter . '%')
                ->orwhere('service.service_name', 'LIKE', '%' . $searchFilter . '%')
                ->orwhere('category.category_name', 'LIKE', '%' . $searchFilter . '%')
                ->orwhere('venue.venue_id', 'LIKE', '%' . $searchFilter . '%');
        }
        $total_records = $total_records + $ticketVenueDetails->get()->count();

        if (isset($request->take)) {
            $ticketVenueDetails->offset($request->skip)->limit($request->take);
        }
        $ticketVenueValues                = $ticketVenueDetails->get()->toArray();
        $ticket_list_data['ticket_venue'] = $ticketVenueValues;
        $ticket_list_data['total']        = $total_records;
        return json_encode($ticket_list_data);
    }

    public function ticketOpponentList(Request $request)
    {
        if (isset($request->filter)) {
            $searchFilter = $request->filter['filters'][self::INIT_VALUE]['value'];
        }
        $total_records         = self::INIT_VALUE;
        $ticketOpponentDetails = Opponent::
            select('opponent.*', 'service.service_name')
            ->where('opponent.opponent_name', '!=', 'None')
            ->join('service', 'service.service_id', 'opponent.service_id');
        if (isset($searchFilter)) {
            $ticketOpponentDetails->where('opponent.opponent_name', 'LIKE', '%' . $searchFilter . '%')
                ->orwhere('service.service_name', 'LIKE', '%' . $searchFilter . '%')
                ->orwhere('opponent.opponent_id', 'LIKE', '%' . $searchFilter . '%');
        }
        $total_records = $total_records + $ticketOpponentDetails->get()->count();

        if (isset($request->take)) {
            $ticketOpponentDetails->offset($request->skip)->limit($request->take);
        }
        $ticketOpponentValues                = $ticketOpponentDetails->get()->toArray();
        $ticket_list_data['ticket_opponent'] = $ticketOpponentValues;
        $ticket_list_data['total']           = $total_records;
        return json_encode($ticket_list_data);
    }

    public function ticketServiceList(Request $request)
    {
        if (isset($request->filter)) {
            $searchFilter = $request->filter['filters'][self::INIT_VALUE]['value'];
        }
        $total_records        = self::INIT_VALUE;
        $ticketServiceDetails = Service::
            select('service.*');
        if (isset($searchFilter)) {
            $ticketServiceDetails->where('service.service_name', 'LIKE', '%' . $searchFilter . '%')
                ->orwhere('service.service_name', 'LIKE', '%' . $searchFilter . '%')
                ->orwhere('service.service_id', 'LIKE', '%' . $searchFilter . '%');
        }
        $total_records = $total_records + $ticketServiceDetails->get()->count();

        if (isset($request->take)) {
            $ticketServiceDetails->offset($request->skip)->limit($request->take);
        }
        $ticketServiceValues                = $ticketServiceDetails->get()->toArray();
        $ticket_list_data['ticket_service'] = $ticketServiceValues;
        $ticket_list_data['total']          = $total_records;
        return json_encode($ticket_list_data);
    }
}
