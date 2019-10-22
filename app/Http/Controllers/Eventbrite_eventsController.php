<?php

namespace App\Http\Controllers;

use App\GraphCalendar;
use App\Helpers\SocialEventManager;
use App\Http\Controllers\PermissionsController;
use App\Http\Traits\PermissionTrait;
use App\Postal;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Redirect;

/**
 * Class Account_typeController.
 *
 * @author  The scaffold-interface created at 2018-02-18 01:01:05pm
 * @link  https://github.com/amranidev/scaffold-interface
 */
class Eventbrite_eventsController extends PermissionsController
{
    protected $_parameters = array();
    protected $connectorName;
    public function __construct()
    {
        parent::__construct();
        $this->connectorName = 'Eventbrite';
    }
    /**
     * Display a listing of the resource.
     *
     * @return  \Illuminate\Http\Response
     */
    public function index()
    {
        if ($this->permissionDetails('Eventbrite_events', 'access')) {
            if ($this->locationId != 0) {
                $postalData = Postal::where('postal_id', $this->locationId)->get()->first();
            } else {
                $postalData = false;
            }
            $permissions   = $this->getPermission("Eventbrite_events");
            $connectorName = $this->connectorName;
            $tokenData     = PermissionTrait::getCurrentUserAccessToken($this->userId, $connectorName);
            return view('eventbrite_events.index', compact('permissions', 'tokenData', 'postalData'));
        } else {
            return Redirect::back()->with('message', 'You are not authorized to use this functionality!');
        }
    }
    public function createEventArray($eventArray)
    {
        $eventResponseArray = array();
        foreach ($eventArray as $key => $value) {
            $eventArrayValue['event_name'] = $value->name->text;
            $eventArrayValue['address'] = isset($value->venue->address)?$value->venue->address:"";
            $eventArrayValue['event_id'] = $value->id;
            $eventArrayValue['url'] = $value->url;
            $eventArrayValue['status'] = $value->status;
            $eventArrayValue['logo'] = $value->logo;
            $eventArrayValue['start_date'] = date("Y-m-d", strtotime($value->start->local));
            $eventArrayValue['start_time'] = date("h:i:s", strtotime($value->start->local));
            $eventArrayValue['end_date'] = date("Y-m-d", strtotime($value->end->local));
            $eventArrayValue['end_time'] = date("h:i:s", strtotime($value->end->local));
            $eventArrayValue['timezone'] = $value->start->timezone;
            $eventResponseArray[] = $eventArrayValue;
            # code...
        }
        return $eventResponseArray;
    }

    public function dynamicEventSearch($eventSearchUrl,$pageNumber)
    {
        $tokenData                 = PermissionTrait::getCurrentUserAccessToken($this->userId, $this->connectorName);
        $venueExpand = 'venue';
        $parameters = array(
            'token' => $tokenData->oauth_token,
            'page'  => $pageNumber,
            'expand'=> $venueExpand
        );
        $eventBriteEventString = $this->get($eventSearchUrl, $parameters);
        $eventBriteEventObject = json_decode($eventBriteEventString);
        $objectCount = $eventBriteEventObject->pagination->object_count;
        $eventBriteEventsArray = (array) $eventBriteEventObject->events;
        $eventBriteEvent = self::createEventArray($eventBriteEventsArray);
        $socialManagerObject = new SocialEventManager($this->connectorName);
        $currentUserEventBriteEvent = $socialManagerObject->getSocialConnectorEvents();
        if ($currentUserEventBriteEvent) {
            foreach ($currentUserEventBriteEvent as $eventKey => $eventValue) {
                if (array_search($eventValue['graph_event_id'], array_column($eventBriteEvent, 'event_id')) !== false) {
                    $objectCount = $objectCount-1;
                    $eventBriteEvent = self::addDisableSync($eventBriteEvent, 'event_id', $eventValue['graph_event_id']);
                }

            }
        }
        $eventBriteEventsResponse['events'] = $eventBriteEvent;
        $eventBriteEventsResponse['total']  = $objectCount;
        return $eventBriteEventsResponse;
    }
    public function fetchMyEventList(Request $request)
    {
        $eventSearchUrl = $request->searchUrl;
        $eventBriteOwnedUserEvents = self::dynamicEventSearch($eventSearchUrl,$request->page);
        return json_encode($eventBriteOwnedUserEvents);
    }

    public function fetchOtherEventList(Request $request)
    {
        $currentTime               = date("Y-m-d\TH:i:s", strtotime(Carbon::now("UTC")));
        $tokenData                 = PermissionTrait::getCurrentUserAccessToken($this->userId, $this->connectorName);
        $eventBriteOtherUserEvents = array();
        if (!$request->address && !$request->eventLatitude) {
            if ($this->locationId != 0) {
                $cityData     = Postal::select('city_name')->join('location_city', 'location_city.city_id', 'postal.postal_city')->where('postal_id', $this->locationId)->get()->first();
                $addressValue = $cityData->city_name;
            } else {
                $addressValue = 'New York';
            }
        } else {
            if(isset($request->address) && $request->address !== '')
            {
                $addressValue = $request->address;
            } else {
                $eventLatitude = $request->eventLatitude;
                $eventLongitude = $request->eventLongitude;
            }
                
        }
        $eventPrice    = ($request->eventPriceType) ? $request->eventPriceType : 'free';
        $sortByEvent   = ($request->sort_by) ? $request->sort_by : 'date';
        $eventDistance = ($request->distance) ? round($request->distance) . 'km' : '50km';
        $eventKeyword  = ($request->keyword) ? $request->keyword : 'marathon';
        $venueExpand = 'venue';
        $parameters = array(
            'token'            => $tokenData->oauth_token,
            'page'             => $request->page,
            'price'            => $eventPrice,
            'sort_by'          => $sortByEvent,
            'location.within'  => $eventDistance,
            'q'                => $eventKeyword,
            'expand'           => $venueExpand
        );
        if(isset($addressValue) && $addressValue !== '')
        {
            $parameters['location.address'] = $addressValue;
        } else {
            $parameters['location.latitude'] = $eventLatitude;
            $parameters['location.longitude'] = $eventLongitude;
        }
        $eventBriteEventString = $this->get('/events/search/', $parameters);
        $eventBriteEventObject = json_decode($eventBriteEventString);
        $eventBriteEventsArray = (array) $eventBriteEventObject->events;
        $eventBriteOtherUserEvents = self::createEventArray($eventBriteEventsArray);
        $objectCount = $eventBriteEventObject->pagination->object_count;
        if (!empty($eventBriteOtherUserEvents)) {
            for ($primaryCount = 0; $primaryCount < count($eventBriteOtherUserEvents); $primaryCount++) {
                $duplicate = null;
                for ($secondaryCount = $primaryCount + 1; $secondaryCount < count($eventBriteOtherUserEvents); $secondaryCount++) {
                    if ($eventBriteOtherUserEvents[$secondaryCount]['event_name'] === $eventBriteOtherUserEvents[$primaryCount]['event_name']) {
                        $objectCount = $objectCount-1;
                        unset($eventBriteOtherUserEvents[$secondaryCount]);
                    }
                }
                $eventBriteOtherUserEvents = array_values($eventBriteOtherUserEvents);
            }
        }
        $socialManagerObject        = new SocialEventManager($this->connectorName);
        $currentUserEventBriteEvent = $socialManagerObject->getSocialConnectorEvents();
        if ($currentUserEventBriteEvent) {
            foreach ($currentUserEventBriteEvent as $eventKey => $eventValue) {
                if (array_search($eventValue['graph_event_id'], array_column($eventBriteOtherUserEvents, 'event_id')) !== false) {
                    $objectCount = $objectCount-1;
                    $eventBriteOtherUserEvents = self::addDisableSync($eventBriteOtherUserEvents, 'event_id', $eventValue['graph_event_id']);
                }

            }
        }
        $eventBriteOtherUserEvents = array_values($eventBriteOtherUserEvents);
        $eventListValues['events']= $eventBriteOtherUserEvents;
        $eventListValues['total']= $objectCount;
        return json_encode($eventListValues);
    }

    public function syncEventbriteEvents(Request $request)
    {

        if (isset($request->otherEventList)) {
            $tokenData       = PermissionTrait::getCurrentUserAccessToken($this->userId, $this->connectorName);
            $calendarSummary = self::eventBriteCalendarData();
            $otherEventList = json_decode($request->otherEventList);
            foreach ($otherEventList as $key => $value) {
                $eventIdentity = $value;
                $venueExpand = 'venue';
                $parameters    = array(
                    'token' => $tokenData->oauth_token,
                    'expand'           => $venueExpand
                );
                $eventBriteEventString = $this->get('/events/' . $eventIdentity . '/', $parameters);
                $eventBriteEventsArray = json_decode($eventBriteEventString);
                self::eventBriteEventInsert($eventBriteEventsArray, $calendarSummary);
            }
        }
    }
    public function eventBriteCalendarData()
    {
        $collectiveType = 'EventBrite';
        $calendarName           = 'Eventbrite Events';
        $calendarIdentity       = 'eventbrite-cal';
        $calendarStatus         = 1;
        $socialManagerObject    = new SocialEventManager($this->connectorName);
        $defaultCalendarType = 'Holidays';
        $socialManagerObject->insertRelation($defaultCalendarType);
        $collectiveSummary = $socialManagerObject->insertCollective($collectiveType);
        return $calendarSummary = $socialManagerObject->insertCalendar($calendarIdentity, $calendarName, $collectiveSummary->collective_id, $calendarStatus);
    }
    public function eventBriteEventInsert($eventBriteEventData, $calendarSummary)
    {
        $socialManagerObject = new SocialEventManager($this->connectorName);
        $eventIdentity       = $eventBriteEventData->id;
        $eventSummary        = $eventBriteEventData->name->text;
        $eventDescription    = $eventBriteEventData->description->text;
        $eventLink           = $eventBriteEventData->url;
        $eventAvatar         = isset($eventBriteEventData->logo)?$eventBriteEventData->logo->url:'';
        $eventCity           = isset($eventBriteEventData->venue)?$eventBriteEventData->venue->address->city:'';
        $eventStartDate      = date("Y-m-d", strtotime($eventBriteEventData->start->utc));
        $eventStartTime      = date("h:i:s", strtotime($eventBriteEventData->start->utc));
        $eventEndDate        = date("Y-m-d", strtotime($eventBriteEventData->end->utc));
        $eventEndTime        = date("h:i:s", strtotime($eventBriteEventData->end->utc));
        $allDayEvent         = false;
        $eventTypeValue      = 0;
        $socialManagerObject->insertEvents($calendarSummary, $eventIdentity, $eventSummary, $eventLink,$eventAvatar, $eventStartDate, $eventStartTime, $eventEndDate, $eventEndTime, $allDayEvent, $eventTypeValue, $eventDescription,$eventCity);
    }

    public function addDisableSync($array, $key, $value)
    {
        foreach ($array as $subKey => $subArray) {
            if ($subArray[$key] === $value) {
                unset($array[$subKey]);
            }
        }
        return $array;
    }

    public function get($path, array $parameters = array())
    {
        $parameters = array_merge($this->_parameters, $parameters);

        if (preg_match_all('/:([a-z]+)/', $path, $matches)) {

            foreach ($matches[0] as $i => $match) {

                if (isset($parameters[$matches[1][$i]])) {
                    $path = str_replace($match, $parameters[$matches[1][$i]], $path);
                    unset($parameters[$matches[1][$i]]);
                } else {
                    throw new Exception("Missing parameter '" . $matches[1][$i] . "' for path '" . $path . "'.");
                }
            }
        }
        $url = 'https://www.eventbriteapi.com/v3' . $path . '?' . http_build_query($parameters);
        return $this->get_url($url);
    }

    protected function get_url($url)
    {

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Accept-Charset: utf-8"));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        $content = curl_exec($ch);

        if (curl_errno($ch)) {
            $error = curl_error($ch);
            curl_close($ch);

            throw new Exception("Failed retrieving  '" . $url . "' because of ' " . $error . "'.");
        }

        $response = json_decode($content);
        $status   = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        curl_close($ch);

        if ($status != 200) {

            if (isset($response->errors[0]->message)) {
                $error = $response->errors[0]->message;
            } else {
                $error = 'Status ' . $status;
            }

            throw new Exception("Failed retrieving  '" . $url . "' because of ' " . $error . "'.");
        }
        if (!isset($response)) {

            switch (json_last_error()) {
                case JSON_ERROR_NONE:
                    $error = 'No errors';
                    break;
                case JSON_ERROR_DEPTH:
                    $error = 'Maximum stack depth exceeded';
                    break;
                case JSON_ERROR_STATE_MISMATCH:
                    $error = ' Underflow or the modes mismatch';
                    break;
                case JSON_ERROR_CTRL_CHAR:
                    $error = 'Unexpected control character found';
                    break;
                case JSON_ERROR_SYNTAX:
                    $error = 'Syntax error, malformed JSON';
                    break;
                case JSON_ERROR_UTF8:
                    $error = 'Malformed UTF-8 characters, possibly incorrectly encoded';
                    break;
                default:
                    $error = 'Unknown error';
                    break;
            }

            throw new Exception("Cannot read response by  '" . $url . "' because of: '" . $error . "'.");
        }

        return $content;
    }

    public function getEventBriteCalendar()
    {
        $eventBriteDetails = GraphCalendar::where('cal_id', 'eventbrite-cal')->get()->first();
        return $eventBriteDetails;
    }

    public function eventBriteEventList(Request $request)
    {
        $eventBriteCalendarId = self::getEventBriteCalendar();
        if(isset($request->filter))
        {
            $searchValue =  $request->filter['filters'][0]['value'];
        } else {
            $searchValue =  '';
        }
        
        if (isset($eventBriteCalendarId->graph_cal_id)) {
            $socialManagerObject    = new SocialEventManager($this->connectorName);
            $eventBriteEventSummary = $socialManagerObject->getEventList($eventBriteCalendarId->graph_cal_id,$request->take,$request->skip,$request->filter['filters'][0]['value'],$searchValue);
            $permissions            = $this->getPermission("Eventbrite_events");
            $adminroles             = array("access", "manage", "add", "delete");
            $matchRoles             = count(array_intersect($permissions, $adminroles));
            foreach ($eventBriteEventSummary['eventDetails'] as $key => $value) {
                $eventBriteEventSummary['eventDetails'][$key]['matchRoles'] = $matchRoles;
            }
            $eventBriteListData['graph_calendar'] = array_values($eventBriteEventSummary['eventDetails']);
            $eventBriteListData['total']          = $eventBriteEventSummary['totalCount'];
            return json_encode($eventBriteListData);
        }

    }

    public function deleteEventBriteEvent(Request $request)
    {
        $eventIdentity       = $request->calendar_event_id;
        $socialManagerObject = new SocialEventManager($this->connectorName);
        $socialManagerObject->deleteEvent($eventIdentity);
        return $request->callback;
    }
}
