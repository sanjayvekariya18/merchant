<?php

namespace App\Http\Controllers;

use App\Helpers\SocialEventManager;
use App\Http\Controllers\Controller;
use App\Http\Traits\PermissionTrait;
use Exception;
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
class Meetup_eventsController extends Controller
{
	const MAX_GROUP_ALLOWED = 10000;
    use PermissionTrait;
    protected $connectorName;
    protected $maxGroupAllow;

    protected $_parameters = array(
        'sign' => 'true',
    );
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            $this->merchantId = session()->has('merchantId') ? session()->get('merchantId') : "";
            $this->roleId     = session()->has('role') ? session()->get('role') : "";
            $this->locationId = session()->has('locationId') ? session()->get('locationId') : "";
            $this->staffId    = session()->has('staffId') ? session()->get('staffId') : "";
            $this->userId     = session()->has('userId') ? session()->get('userId') : "";
            $this->staffUrl   = session()->has('staffUrl') ? session()->get('staffUrl') : "";

            $this->connectorName = 'Meetup';
            $this->maxGroupAllow = self::MAX_GROUP_ALLOWED;
            if (!$this->issetHashPassword()) {
                Redirect::to($this->staffUrl . '/' . $this->staffId . '/edit')->with('message', 'You must have to change password !')->send();
            }
            return $next($request);
        });
    }
    /**
     * Display a listing of the resource.
     *
     * @return  \Illuminate\Http\Response
     */
    public function index()
    {
        if ($this->permissionDetails('Meetup_events', 'access')) {
            $permissions   = $this->getPermission("Meetup_events");
            $tokenData   = PermissionTrait::getCurrentUserAccessToken($this->userId, $this->connectorName);
            $adminReoles = array("access", "manage", "add", "delete");
            $matchRoles  = count(array_intersect($permissions, $adminReoles));
            return view('meetup_events.index', compact('permissions', 'tokenData','matchRoles'));
        } else {
            return Redirect::back()->with('message', 'You are not authorized to use this functionality!');
        }
    }

    /**
     * This function sets connector name
     * @return string
     */
    public function setConnectorName()
    {
        $connectorName = 'Meetup';
        return $connectorName;
    }

    public function fetchGroupList(Request $request)
    {
        $socialManagerObject = new SocialEventManager($this->connectorName);
        $tokenData     = PermissionTrait::getCurrentUserAccessToken($this->userId, $this->connectorName);
        $parameters    = array(
            'access_token' => $tokenData->oauth_token,
            'page'         => $request->take,
            'offset'       =>$request->page-1,

        );
        $meeupListResponse = $this->get('/self/groups', $parameters);
        $groupListArray = json_decode($meeupListResponse);
        $meetupGroupList = array();
        $tempArray = array();
        foreach ($groupListArray as $groupListKey => $groupListValue) {
            $tempArray['group_id']        = $groupListValue->id;
            $tempArray['group_name'] = $groupListValue->name;
            $tempArray['group_link'] = $groupListValue->link;
            $tempArray['group_city'] = $groupListValue->localized_location;
            $tempArray['group_timezone'] = $groupListValue->timezone;
            $tempArray['group_photo'] = isset($groupListValue->group_photo)?$groupListValue->group_photo->thumb_link:'';
            $meetupGroupList[] = $tempArray;
        }
        /* for joel meetup group */

        /*$parameters    = array(
            'access_token' => $tokenData->oauth_token,
            'member_id'    => 42780492, 
            'page'         => 100,
            'offset'       =>0,
 
        );
        $memberListResponse = $this->get('/2/groups', $parameters);
        return $memberListResponse;*/
        

        /* end of joel meetup group */
        $meetupGroupIds = $socialManagerObject->getUserCalendarIdentity();
        $groupExist     = array();
        if (!$meetupGroupIds->isEmpty()) {
            foreach ($meetupGroupIds as $meetupGroupIdsKey => $meetupGroupIdsValue) {
                $groupExist[] = $meetupGroupIdsValue->cal_id;
            }
        }

        foreach ($meetupGroupList as $meetupGroupListKey => $meetupGroupListValue) {
            if (in_array($meetupGroupListValue['group_id'], $groupExist)) {
                $meetupGroupList[$meetupGroupListKey]['disable_sync'] = true;
            }

        }
        $meetupGroupResponse['meetupGroups'] = array_reverse(array_values($meetupGroupList));
        if(count($meetupGroupList) == $request->take)
        {
            /*maximum group list in grid */
            $total_count = $this->maxGroupAllow;
        } if(count($meetupGroupList) < $request->take) {
            $total_count = $request->take*($request->page-1)+count($meetupGroupList);
        } else {
            
        }
        $meetupGroupResponse['total'] = $total_count;
        return json_encode($meetupGroupResponse);
    }

    public function syncMeetupEvents(Request $request)
    {
        if (isset($request->meetupGroups)) {
            $listGroup = json_decode($request->meetupGroups);
            $socialManagerObject = new SocialEventManager($this->connectorName);
            $tokenData     = PermissionTrait::getCurrentUserAccessToken($this->userId, $this->connectorName);
            $collectiveType = 'Meetups';
            $collectiveSummary = $socialManagerObject->insertCollective($collectiveType);
            $defaultCalendarType = 'Holidays';
            $socialManagerObject->insertRelation($defaultCalendarType);
            foreach ($listGroup as $listGroupKey => $listGroupValue) {
                $groupParameters    = array(
                    'access_token' => $tokenData->oauth_token,
                    'id'           => $listGroupValue
                );
                $meeupListResponse = $this->get('/groups', $groupParameters);
                $groupListArray = json_decode($meeupListResponse);
                $groupIdentity=$groupListArray->results[0]->id;
                $groupName=$groupListArray->results[0]->name;
                $groupDescription=$groupListArray->results[0]->description;
                $groupLink=$groupListArray->results[0]->link;
                if(isset($groupListArray->results[0]->photo_url))
                {
                    $groupPhoto = $groupListArray->results[0]->photo_url;
                }
                else
                {
                    $groupPhoto='';
                }

                if(isset($groupListArray->results[0]->city))
                {
                    $groupCity = $groupListArray->results[0]->city;
                }
                else
                {
                    $groupCity='';
                }
                $groupStatus=1;
                $calendarSummary = $socialManagerObject->insertCalendar($groupIdentity, $groupName,$collectiveSummary->collective_id, $groupStatus, $groupDescription, $groupLink, $groupPhoto);
                $eventParameters    = array(
                    'access_token' => $tokenData->oauth_token,
                    'group_id'     => $groupIdentity,
                    'fields'       =>'venue'
                );
                $eventListResponse = $this->get('/2/events', $eventParameters);
                $eventListArray    = json_decode($eventListResponse);
                if (!empty($eventListArray)) {
                    for ($e = 0; $e < count($eventListArray->results); $e++) {
                        $duplicate = null;
                        for ($ee = $e + 1; $ee < count($eventListArray->results); $ee++) {
                            if ($eventListArray->results[$ee]->name=== $eventListArray->results[$e]->name) {
                                $duplicate = $ee;
                                break;
                            }
                        }
                        if (!is_null($duplicate)) {
                            array_splice($eventListArray->results, $duplicate);
                        }
                    }
                }
                if(isset($eventListArray->results))
                {
                    foreach ($eventListArray->results as $eventListKey =>$eventListValue) {
                        $eventSummary = $eventListValue->name;
                        $eventIdentity = $eventListValue->id;
                        $eventDescription = $eventListValue->description;
                        $eventLink = $eventListValue->event_url;
                        $eventAvatar = isset($eventListValue->photo_url)?$eventListValue->photo_url:'';
                        $eventTime = $eventListValue->time;
                        $eventduration = $eventListValue->duration;
                        $eventLocation = isset($eventListValue->venue)?$eventListValue->venue->city:'';
                        if(empty($eventLocation))
                        {
                            $eventLocation = $groupCity;    
                        }
                        $eventStartUtcTimestamp = $eventTime;
                        $eventStartDate = date("Y-m-d", $eventStartUtcTimestamp/1000);
                        $eventStartTime = date("H:i:s", $eventStartUtcTimestamp/1000);
                        $eventEndUtcTimestamp = $eventTime+$eventduration;
                        $eventEndDate = date("Y-m-d", $eventEndUtcTimestamp/1000);
                        $eventEndTime = date("H:i:s", $eventEndUtcTimestamp/1000);
                        $eventType = $eventListValue->visibility;
                        if ($eventType === 'private') {
                            $eventTypeValue = 1;
                        } else {
                            $eventTypeValue = 0;
                        }
                        $allDayEvent    = false;
                        $socialManagerObject->insertEvents($calendarSummary, $eventIdentity, $eventSummary, $eventLink, $eventAvatar, $eventStartDate, $eventStartTime, $eventEndDate, $eventEndTime, $allDayEvent, $eventTypeValue, $eventDescription,$eventLocation);
                    }
                }
            }
        }
    }

    public function meetupCalendarList(Request $request)
    {
        $searchFiler = '';
        if (isset($request->filter)) {
            $searchFiler = $request->filter['filters'][0]['value'];
        }
        $socialManagerObject = new SocialEventManager($this->connectorName);
        $calendarListData    = $socialManagerObject->getCalendarList($request->take, $request->skip, $searchFiler);
        return json_encode($calendarListData);

    }

    public function meetupEventList(Request $request)
    {
        $socialManagerObject = new SocialEventManager($this->connectorName);
        $meetupEventSummary  = $socialManagerObject->getEventList($request->graphCalId);
        $permissions         = $this->getPermission("Meetup_events");
        $adminReoles         = array("access", "manage", "add", "delete");
        $matchRoles          = count(array_intersect($permissions, $adminReoles));
        foreach ($meetupEventSummary['eventDetails'] as $key => $value) {
            $meetupEventSummary['eventDetails'][$key]['matchRoles'] = $matchRoles;
        }
        return json_encode($meetupEventSummary['eventDetails']);
    }

    public function deleteMeetupEvent(Request $request)
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
        $url = 'https://api.meetup.com' . $path . '?' . http_build_query($parameters);
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
