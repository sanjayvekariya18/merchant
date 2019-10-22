<?php

namespace App\Http\Controllers;

use App\Helpers\SocialEventManager;
use App\Http\Controllers\Controller;
use Google_Client;
use Google_Service_Calendar;
use Google_Service_Calendar_Event;
use Google_Service_Calendar_EventDateTime;
use Google_Service_Oauth2;
use App\Http\Traits\PermissionTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Redirect;
use Session;
use URL;
use DateTime;
use DateTimeZone;
/**
 * Class Account_typeController.
 *
 * @author  The scaffold-interface created at 2018-02-18 01:01:05pm
 * @link  https://github.com/amranidev/scaffold-interface
 */
class Google_eventsController extends Controller
{
    use PermissionTrait;
    protected $connectorName;

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            $this->merchantId    = session()->has('merchantId') ? session()->get('merchantId') : "";
            $this->roleId        = session()->has('role') ? session()->get('role') : "";
            $this->locationId    = session()->has('locationId') ? session()->get('locationId') : "";
            $this->staffId       = session()->has('staffId') ? session()->get('staffId') : "";
            $this->userId        = session()->has('userId') ? session()->get('userId') : "";
            $this->staffUrl      = session()->has('staffUrl') ? session()->get('staffUrl') : "";
            $this->connectorName = 'Google';

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
        if ($this->permissionDetails('Google_events', 'access')) {
            $permissions = $this->getPermission("Google_events");
            $tokenData   = PermissionTrait::getCurrentUserAccessToken($this->userId, $this->connectorName);
            $adminReoles = array("access", "manage", "add", "delete");
            $matchRoles  = count(array_intersect($permissions, $adminReoles));
            return view('google_events.index', compact('permissions', 'tokenData', 'matchRoles'));
        } else {
            return Redirect::back()->with('message', 'You are not authorized to use this functionality!');
        }
    }

    public function googleCalendarList(Request $request)
    {
        $searchFiler = '';
        if (isset($request->filter)) {
            $searchFiler = $request->filter['filters'][0]['value'];
        }
        $socialManagerObject = new SocialEventManager($this->connectorName);
        $calendarListData    = $socialManagerObject->getCalendarList($request->take, $request->skip, $searchFiler);
        return json_encode($calendarListData);

    }

    public function googleEventList(Request $request)
    {
        $socialManagerObject = new SocialEventManager($this->connectorName);
        $googleEventSummary  = $socialManagerObject->getEventList($request->graphCalId);
        $permissions         = $this->getPermission("Google_events");
        $adminReoles         = array("access", "manage", "add", "delete");
        $matchRoles          = count(array_intersect($permissions, $adminReoles));
        foreach ($googleEventSummary['eventDetails'] as $key => $value) {
            $googleEventSummary['eventDetails'][$key]['matchRoles'] = $matchRoles;
        }
        return json_encode($googleEventSummary['eventDetails']);
    }

    public function deleteGoogleEvent(Request $request)
    {
        $eventIdentity       = $request->calendar_event_id;
        $socialManagerObject = new SocialEventManager($this->connectorName);
        $socialManagerObject->deleteEvent($eventIdentity);
        return $request->callback;
    }

    public function deleteGoogleCalendar(Request $request)
    {
        $graphCalId          = $request->graph_cal_id;
        $socialManagerObject = new SocialEventManager($this->connectorName);
        $socialManagerObject->deleteCalendar($graphCalId);
    }

    public function getUnsyncedCalendar(Request $request)
    {
        $socialManagerObject = new SocialEventManager($this->connectorName);
        return $socialManagerObject->getUnSyncCalendar($request->skip,$request->take);
    }

    /**
     * This function is used to get google client object
     * @return object $googleClient
     */
    public function getGoogleClientOject()
    {
        $googleClient = new Google_Client();
        $googleClient->setApplicationName("Google Calendar PHP Starter Application");
        $googleClient->setClientId(PermissionTrait::getApiKey('Google'));
        $googleClient->setClientSecret(PermissionTrait::getApiSecretKey('Google'));
        $googleClient->setRedirectUri(self::getConnectUrl());
        $googleClient->setScopes("https://www.googleapis.com/auth/calendar https://www.googleapis.com/auth/calendar.readonly https://www.googleapis.com/auth/userinfo.profile https://www.googleapis.com/auth/userinfo.email https://picasaweb.google.com/data");
        $googleClient->setDeveloperKey('470470417904@developer.gserviceaccount.com');
        $googleClient->setAccessType('offline');
        $googleClient->setApprovalPrompt('force');
        return $googleClient;
    }

    public function getConnectUrl()
    {
        return URL::to('/') . '/google';
    }
    public function fetchCalendarList(Request $request)
    {
        $googleClient = self::refreshToken();
        $fetchCalendar = array();
        if(!empty($googleClient))
        {
            $googleCalendar      = new Google_Service_Calendar($googleClient);
            $socialManagerObject = new SocialEventManager($this->connectorName);
            $collectiveType = 'Calendar';
            $collectiveSummary = $socialManagerObject->insertCollective($collectiveType);
            $defaultCalendarType = 'Holidays';
            $socialManagerObject->insertRelation($defaultCalendarType);
            $listCalendar       = $googleCalendar->calendarList->listCalendarList();
            $totalCalendarCount = count($listCalendar->getItems());
            
            $flagCount     = 0;
            //get list of calendar
            while (true) {
                foreach ($listCalendar->getItems() as $calendarListEntry) {
                    $calendarIdentity     = $calendarListEntry->getId();
                    $calendarEntrySummary = $calendarListEntry->getSummary();
                    $calendarEntryDescription = $calendarListEntry->getDescription();
                    $calendarStatus = 0;
                    $socialManagerObject->insertCalendar($calendarIdentity, $calendarEntrySummary,$collectiveSummary->collective_id,$calendarStatus, $calendarEntryDescription);

                    $fetchCalendar[$flagCount]['cal_id']        = $calendarIdentity;
                    $fetchCalendar[$flagCount]['cal_name'] = $calendarEntrySummary;
                    $flagCount++;
                }

                //end foreach calendar list
                $listCalendar = $googleCalendar->calendarList->listCalendarList();
                $pageToken    = $listCalendar->getNextPageToken();
                if ($pageToken) {
                    $optionParams = array('pageToken' => $pageToken);
                    $listCalendar = $googleCalendar->calendarList->listCalendarList($optionParams);
                } else {
                    break;
                }
            }
            $googleCalendarIds = $socialManagerObject->getUserCalendarIdentity();
            $calendarExist     = array();
            if (!$googleCalendarIds->isEmpty()) {
                foreach ($googleCalendarIds as $googleCalendarIdsKey => $googleCalendarIdsValue) {
                    $calendarExist[] = $googleCalendarIdsValue->cal_id;
                }
            }
            foreach ($fetchCalendar as $fetchCalendarKey => $fetchCalendarValue) {
                if (in_array($fetchCalendarValue['cal_id'], $calendarExist)) {
                    $fetchCalendar[$fetchCalendarKey]['status'] = true;
                }

            }
        }
        return json_encode(array_reverse((array_values($fetchCalendar))));
    }

    public function syncGoogleEvents(Request $request)
    {
        $listCalendar = json_decode($request->googleGroups);
        $googleClient = self::refreshToken();
        if(!empty($googleClient))
        {
            $socialManagerObject = new SocialEventManager($this->connectorName);
            $googleCalendar      = new Google_Service_Calendar($googleClient);
            foreach ($listCalendar as $listCalendarKey => $listCalendarValue) {

                $calendarListEntry     = $googleCalendar->calendarList->get($listCalendarValue);
                $calendarIdentity      = $listCalendarValue;
                $calendarEntrySummary  = $calendarListEntry->getSummary();
                $collectiveName        = 'calendar';
                $defaultCalendarType   = 'Holidays';
                $googleCalendarSummary = $socialManagerObject->enableCalendarStatus($calendarIdentity);

                $listSocialEvent = $googleCalendar->events->listEvents($calendarIdentity);
                //get list of calendar events
                while (true) {
                    foreach ($listSocialEvent->getItems() as $calendarEvent) {
                        if ($calendarEvent->getSummary()) {
                            // getting the each event details.
                            $startTime = $calendarEvent->getStart();
                            if ($startTime->{'dateTime'}) {
                                $startDate = strtotime(date("Y-m-d", strtotime($startTime->{'dateTime'})));
                            } else {
                                $startDate = strtotime($startTime->{"date"});
                            }
                            $currentTime    = strtotime(date("Y-m-d"));
                            $dateDifference = $startDate - $currentTime;
                            $eventDays      = floor($dateDifference / (60 * 60 * 24));
                            if (0 <= $eventDays && 365 >= $eventDays) {
                                $eventSummary  = $calendarEvent->getSummary();
                                $eventIdentity = $calendarEvent->getId();
                                $attendesData  = $calendarEvent->getAttendees();
                                $creatorEmail  = $calendarEvent->getCreator();
                                $endTime       = $calendarEvent->getend();
                                $eventLink     = $calendarEvent->getHtmlLink();
                                $eventCity     = $calendarEvent->getLocation();
                                $eventDescription = '';
                                $eventAvatar = '';
                                
                                $eventType     = $calendarEvent->getVisibility();
                                if($calendarEvent->getCreator()->getSelf() == 1)
                                {
                                    $eventTypeValue = 1;
                                } else {
                                    $eventTypeValue = 0;
                                }
                                if ($startTime->{'dateTime'}) {
                                    $socialStartDate = date("Y-m-d", strtotime($startTime->{'dateTime'}));
                                    $socialStartTime = date("H:i:s", strtotime($startTime->{'dateTime'}));
                                    $socialEndDate   = date("Y-m-d", strtotime($endTime->{'dateTime'}));
                                    $socialEndTime   = date("H:i:s", strtotime($endTime->{'dateTime'}));
                                    $allDataEvent    = false;
                                } else {
                                    $socialStartDate = date("Y-m-d", strtotime($startTime->{'date'}));
                                    $socialStartTime = '00:00:00';
                                    $socialEndDate   = date("Y-m-d", strtotime($endTime->{'date'}));
                                    $socialEndTime   = '00:00:00';
                                    $allDataEvent    = true;

                                }
                                $socialEventSummaryDetails = str_replace("&", "and", $eventSummary);
                                $socialManagerObject->insertEvents($googleCalendarSummary, $eventIdentity, $socialEventSummaryDetails, $eventLink,$eventAvatar, $socialStartDate, $socialStartTime, $socialEndDate, $socialEndTime, $allDataEvent, $eventTypeValue,$eventDescription,$eventCity);
                            }
                        }
                    }
                    $pageToken = $listSocialEvent->getNextPageToken();
                    if ($pageToken) {
                        $optionParams    = array('pageToken' => $pageToken);
                        $listSocialEvent = $googleCalendar->events->listEvents('primary', $optionParams);
                    } else {
                        break;
                    }
                }
            }
        }
    }

    public function deleteGoogleCalendarEvent($calendarIdentity,$eventIdentity)
    {
        $googleClient = self::refreshToken();
        if(!empty($googleClient))
        {
            $googleCalendar = new Google_Service_Calendar($googleClient);
            try {
                $googleCalendar->events->delete($calendarIdentity, $eventIdentity);
            } catch (Google_Service_Exception $exception ) {
                return $exception->getMessage();
            }
        } else {
            return false;
        }
    }

    public function createGoogleCalendarEvent($createEventData)
    {
        $googleClient = self::refreshToken();
        if(!empty($googleClient))
        {
            $calendarId = 'primary';
            $googleCalendar = new Google_Service_Calendar($googleClient);
            $eventParameter = array('summary' => $createEventData->event_name);
            try {
                if($createEventData->all_day == 1)
                {
                    $timezone_name = timezone_name_from_abbr('', $_COOKIE['timeZoneOffset'] * 3600, false);
                    $startDateObject = new DateTime($createEventData->start_date);
                    $startDateObject->setTimezone(new DateTimeZone($timezone_name));
                    $endDateObject = new DateTime($createEventData->end_date);
                    $endDateObject->setTimezone(new DateTimeZone($timezone_name));
                    $eventParameter['start'] = array('date' => $startDateObject->format('Y-m-d'));
                    $eventParameter['end'] = array('date' => $endDateObject->format('Y-m-d'));
                } else {
                    $eventParameter['start'] = array('dateTime' => $createEventData->start_date,'timeZone' => date_default_timezone_get());
                    $eventParameter['end'] = array('dateTime' => $createEventData->end_date,'timeZone' => date_default_timezone_get());

                }
                $eventDetails = new Google_Service_Calendar_Event($eventParameter);
                $createEventResponse = $googleCalendar->events->insert($calendarId, $eventDetails);
                return $createEventResponse;
            } catch (Google_Service_Exception $exception ) {
                return $exception->getMessage();
            }
        } else {
            return false;
        }
    }

    public function updateGoogleCalendarEvent($updateEventData)
    {
        $googleClient = self::refreshToken();
        if(!empty($googleClient))
        {
            $googleCalendar = new Google_Service_Calendar($googleClient);
            $eventObject = $googleCalendar->events->get('primary', $updateEventData->graph_event_id);
            $eventObject->setSummary($updateEventData->event_name);

            if($updateEventData->all_day == 1)
                {
                    $timezone_name = timezone_name_from_abbr('', $_COOKIE['timeZoneOffset'] * 3600, false);
                    $startDateObject = new DateTime($updateEventData->start_date);
                    $startDateObject->setTimezone(new DateTimeZone($timezone_name));
                    $endDateObject = new DateTime($updateEventData->end_date);
                    $endDateObject->setTimezone(new DateTimeZone($timezone_name));

                     $startDate = new Google_Service_Calendar_EventDateTime();
                     $startDate->setDate($startDateObject->format('Y-m-d'));
                     $eventObject->setStart($startDate);

                     $endDate = new Google_Service_Calendar_EventDateTime();
                     $endDate->setDate($endDateObject->format('Y-m-d'));
                     $eventObject->setEnd($endDate);
                } else {
                    $startDate = new Google_Service_Calendar_EventDateTime();
                    $startDate->setDateTime($updateEventData->start_date);
                    $eventObject->setStart($startDate);
                    $endDate = new Google_Service_Calendar_EventDateTime();
                    $endDate->setDateTime($updateEventData->end_date);
                    $eventObject->setEnd($endDate);
                }

            $updatedEvent = $googleCalendar->events->update('primary', $eventObject->getId(), $eventObject);
            return $updatedEvent;
        } else {
            return false;
        }
    }

    public function getCalendarDetails($calendarIdentity)
    {
        $googleClient = self::refreshToken();
        if(!empty($googleClient))
        {
            $googleCalendar = new Google_Service_Calendar($googleClient);
            $calendarListEntry = $googleCalendar->calendarList->get($calendarIdentity);
            return $calendarListEntry;
        } else {
            return false;
        }
    }

    public function refreshToken()
    {
        include_once storage_path() . "/Google/vendor/autoload.php";
        $googleClient = self::getGoogleClientOject();
        $tokenData = PermissionTrait::getCurrentUserAccessToken($this->userId, $this->connectorName);
        if (isset($tokenData->oauth_token) && !empty($tokenData->oauth_token)) {
            $googleClient->setAccessToken($tokenData->oauth_token);
            $isTokenExpire = $googleClient->isAccessTokenExpired();
            if ($isTokenExpire && isset($tokenData->oauth_token_secret) && $tokenData->oauth_token_secret != '') {
                $refreshToken      = $tokenData->oauth_token_secret;
                $socialGoogleToken = $googleClient->refreshToken($refreshToken);

                $accessToken  = isset($socialGoogleToken['access_token']) ? $socialGoogleToken['access_token'] : null;
                $refreshToken = isset($socialGoogleToken['refresh_token']) ? $socialGoogleToken['refresh_token'] : null;

                PermissionTrait::storeConnectorUserDetails($this->connectorName, $tokenData->user_api_id, $tokenData->user_screen_name, json_encode($socialGoogleToken), $refreshToken, $tokenData->profile_image, $tokenData->gender, $tokenData->city);
            }
            return $googleClient;
        } else {
            return false;
        }
        
    }
}