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
use DateTime;
use DateInterval;

/**
 * Class Facebook_eventsController.
 *
 * @author  The scaffold-interface created at 2018-02-18 01:01:05pm
 * @link  https://github.com/amranidev/scaffold-interface
 */
class Facebook_eventsController extends PermissionsController
{
    protected $_parameters = array();
    protected $connectorName;
    public function __construct()
    {
        parent::__construct();
        $this->connectorName = 'Facebook';
    }
    /**
     * Display a listing of the resource.
     *
     * @return  \Illuminate\Http\Response
     */
    public function index()
    {
        if ($this->permissionDetails('Facebook_events', 'access')) {
            $permissions   = $this->getPermission("Facebook_events");
            $connectorName = $this->connectorName;
            $tokenData     = PermissionTrait::getCurrentUserAccessToken($this->userId, $connectorName);
            return view('facebook_events.index', compact('permissions', 'tokenData'));
        } else {
            return Redirect::back()->with('message', 'You are not authorized to use this functionality!');
        }
    }

    public function fetchMyEventList(Request $request)
    {
        $eventSearchUrl = $request->searchUrl;
        $tokenData                 = PermissionTrait::getCurrentUserAccessToken($this->userId, $this->connectorName);
        $venueExpand = 'venue';
        $parameters = array(
            'access_token' => $tokenData->oauth_token,
            'debug'=>'all',
            'format'=>'json',
            'method'=>'get',
            'pretty'=>0,
            'suppress_http_code'=>1,
            'fields'=>'id,name,cover,description,event_times,end_time,place,start_time,ticket_uri,timezone',
        );
        $facebookEventString = $this->get($eventSearchUrl, $parameters);
        $facebookEventObject = json_decode($facebookEventString);
        $facebookUserEvents = array();
        while (true) {
            $facebookEventsArray = (array) $facebookEventObject->data;
            $facebookUserEvents = self::createEventArray($facebookUserEvents,$facebookEventsArray);
            //end foreach calendar list
            $nextFacebookPageEvents = isset($facebookEventObject->paging->next)?$facebookEventObject->paging->next:'';
            if ($nextFacebookPageEvents) {
                $facebookEventString = $this->get_url($nextFacebookPageEvents);
                $facebookEventObject = json_decode($facebookEventString);
            } else {
                break;
            }
        }
        $socialManagerObject        = new SocialEventManager($this->connectorName);
        $currentUserFacebookEvent = $socialManagerObject->getSocialConnectorEvents();
        if ($currentUserFacebookEvent) {
            foreach ($currentUserFacebookEvent as $eventKey => $eventValue) {
                if (array_search($eventValue['graph_event_id'], array_column($facebookUserEvents, 'event_id')) !== false) {
                    $facebookUserEvents = self::addDisableSync($facebookUserEvents, 'event_id', $eventValue['graph_event_id']);
                }

            }
        }
        $facebookUserEvents = array_values($facebookUserEvents);
        $eventListValues['events']= $facebookUserEvents;
        return $eventListValues;
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

    public function syncFacebookEvents(Request $request)
    {

        if (isset($request->eventList)) {
            $tokenData       = PermissionTrait::getCurrentUserAccessToken($this->userId, $this->connectorName);
            $calendarSummary = self::facebookCalendarData();
            $eventList = json_decode($request->eventList);
            foreach ($eventList as $key => $value) {
                $eventIdentity = $value;
                $parameters = array(
                    'access_token' => 'EAAFkLUuRzQwBAObZAvnZALcAjttHQ8kHZAC6ZBueRh2gW4gFvrHAy7YNd0TFTCPszoZAz3OTWmVpnMCZCrG1OIAvyOfH4HSPaWP4nrJJa13xQ4y3FH6cS9Lalu8Pwtb6IVI743Vk0mnH9QiZBDbkfN0PcqgJWaqZCnxZBayc3uRKh7wSWNCFSXZCDAsbtVIrZCZCkV2HLqqQ3SqeZCAHG7iZA9GsHpRRPhatmSOQHd5FxDPlHrZCAZDZD',
                    'fields'=>'id,name,cover,description,event_times,end_time,place,start_time,ticket_uri,timezone',
                );
                $facebookEventString = $this->get('/' . $eventIdentity . '/', $parameters);
                $facebookEventsArray = json_decode($facebookEventString);
                self::facebookEventInsert($facebookEventsArray, $calendarSummary);
            }
        }
    }

    public function facebookEventInsert($facebookEventData, $calendarSummary)
    {
        $socialManagerObject = new SocialEventManager($this->connectorName);
        if(isset($facebookEventData->end_time) && !empty($facebookEventData->end_time))
        {
            $eventEndDate = date("Y-m-d", strtotime($facebookEventData->end_time));
            $eventEndTime = date("h:i:s", strtotime($facebookEventData->end_time));
        } else {
            $dateTimeObject = new DateTime($facebookEventData->start_time);
            $dateTimeObject->add(new DateInterval('PT3H'));
            $eventEndDate = $dateTimeObject->format('Y-m-d');
            $eventEndTime = $dateTimeObject->format('h:i:s');
        }
        $eventIdentity       = $facebookEventData->id;
        $eventSummary        = $facebookEventData->name;
        $eventDescription    = $facebookEventData->description;
        $eventLink           = isset($facebookEventData->url)?$facebookEventData->url:'';
        $eventAvatar         = isset($facebookEventData->cover)?$facebookEventData->cover->source:'';
        $eventCity           = isset($facebookEventData->place)?$facebookEventData->place->name:'';
        $eventStartDate      = date("Y-m-d", strtotime($facebookEventData->start_time));
        $eventStartTime      = date("h:i:s", strtotime($facebookEventData->start_time));
        $allDayEvent         = false;
        $eventTypeValue      = 0;
        $socialManagerObject->insertEvents($calendarSummary, $eventIdentity, $eventSummary, $eventLink,$eventAvatar, $eventStartDate, $eventStartTime, $eventEndDate, $eventEndTime, $allDayEvent, $eventTypeValue, $eventDescription,$eventCity);
    }

    public function facebookCalendarData()
    {
        $collectiveType = 'Events';
        $calendarName           = 'Facebook Events';
        $calendarIdentity       = 'facebook-cal';
        $calendarStatus         = 1;
        $socialManagerObject    = new SocialEventManager($this->connectorName);
        $defaultCalendarType = 'Holidays';
        $socialManagerObject->insertRelation($defaultCalendarType);
        $collectiveSummary = $socialManagerObject->insertCollective($collectiveType);
        return $calendarSummary = $socialManagerObject->insertCalendar($calendarIdentity, $calendarName, $collectiveSummary->collective_id, $calendarStatus);
    }
    
    public function createEventArray($previousArray,$eventArray)
    {
        $eventResponseArray = array();
        foreach ($eventArray as $key => $value) {
            if(isset($value->end_time) && !empty($value->end_time))
            {
                $endDate = date("Y-m-d", strtotime($value->end_time));
                $endTime = date("h:i:s", strtotime($value->end_time));
            } else {
                $dateTimeObject = new DateTime($value->start_time);
                $dateTimeObject->add(new DateInterval('PT3H'));
                $endDate = $dateTimeObject->format('Y-m-d');
                $endTime = $dateTimeObject->format('h:i:s');
            }
            $eventArrayValue['event_name'] = $value->name;
            $eventArrayValue['city'] = isset($value->place)?$value->place->name:"";
            $eventArrayValue['event_id'] = $value->id;
            $eventArrayValue['url'] = isset($value->url)?$value->url:'';
            $eventArrayValue['logo'] = isset($value->cover)?$value->cover->source:'';
            $eventArrayValue['start_date'] = date("Y-m-d", strtotime($value->start_time));
            $eventArrayValue['start_time'] = date("h:i:s", strtotime($value->start_time));
            $eventArrayValue['end_date'] = $endDate;
            $eventArrayValue['end_time'] = $endTime;
            $eventArrayValue['timezone'] = $value->timezone;
            $eventResponseArray[] = $eventArrayValue;# code...
        }
        return array_merge($previousArray,$eventResponseArray);
    }
    
    public function getFacebookCalendar()
    {
        $facebookDetails = GraphCalendar::where('cal_id', 'facebook-cal')->get()->first();
        return $facebookDetails;
    }

    public function facebookEventList(Request $request)
    {
        $facebookCalendarId = self::getFacebookCalendar();
        if(isset($request->filter))
        {
            $searchValue =  $request->filter['filters'][0]['value'];
        } else {
            $searchValue =  '';
        }
        
        if (isset($facebookCalendarId->graph_cal_id)) {
            $socialManagerObject    = new SocialEventManager($this->connectorName);
            $facebookEventSummary = $socialManagerObject->getEventList($facebookCalendarId->graph_cal_id,$request->take,$request->skip,$request->filter['filters'][0]['value'],$searchValue);
            $permissions            = $this->getPermission("Facebook_events");
            $adminroles             = array("access", "manage", "add", "delete");
            $matchRoles             = count(array_intersect($permissions, $adminroles));
            foreach ($facebookEventSummary['eventDetails'] as $key => $value) {
                $facebookEventSummary['eventDetails'][$key]['matchRoles'] = $matchRoles;
            }
            $facebookListData['graph_calendar'] = array_values($facebookEventSummary['eventDetails']);
            $facebookListData['total']          = $facebookEventSummary['totalCount'];
            return json_encode($facebookListData);
        }

    }

    public function deleteFacebookEvent(Request $request)
    {
        $eventIdentity       = $request->calendar_event_id;
        $socialManagerObject = new SocialEventManager($this->connectorName);
        $socialManagerObject->deleteEvent($eventIdentity);
        return $request->callback;
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
        $url = 'https://graph.facebook.com/v3.2' . $path . '?' . http_build_query($parameters);
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
}
