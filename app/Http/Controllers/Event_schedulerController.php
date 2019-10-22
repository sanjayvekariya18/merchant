<?php

namespace App\Http\Controllers;

use App\Helpers\SocialEventManager;
use App\GraphCalendarEvents;
use App\GraphCalendarEventUsers;
use App\GraphShareGroupEvent;
use App\GraphGroupUserHideEvent;
use App\GraphCalendar;
use App\IdentityGraphCalendarEvents;
use App\Http\Controllers\Google_eventsController;
use App\Http\Traits\PermissionTrait;
use DateTime;
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
class Event_schedulerController extends Google_eventsController
{
    use PermissionTrait;
    private $eventTableId;

    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            $this->merchantId = session()->has('merchantId') ? session()->get('merchantId') : "";
            $this->roleId     = session()->has('role') ? session()->get('role') : "";
            $this->locationId = session()->has('locationId') ? session()->get('locationId') : "";
            $this->staffId    = session()->has('staffId') ? session()->get('staffId') : "";
            $this->userId     = session()->has('userId') ? session()->get('userId') : "";
            $this->staffUrl   = session()->has('staffUrl') ? session()->get('staffUrl') : "";
            $this->eventTableId  = 64;
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
        /* check user has a permission for event scheduler */
        if ($this->permissionDetails('Event_scheduler', 'access')) {
            $permissions = $this->getPermission("Event_scheduler");
            return view('event_scheduler.index', compact('permissions'));
        } else {
            return Redirect::back()->with('message', 'You are not authorized to use this functionality!');
        }
    }

    public function getGraphEvents(Request $request)
    {
        $callback           = $request->callback;
        $eventTableId = $this->eventTableId;
        $graphEventDetails = GraphCalendarEvents::
            select('graph_calendar_events.*', 'graph_calendar.cal_name','graph_calendar.cal_id','portal_social_api.connectorname','identity_graph_calendar_events.identity_name as event_name','identity_graph_calendar_events.identity_logo as avatar_link',
                'identity_graph_calendar_events.identity_website as website_link')
            ->join('identity_graph_calendar_events', 'identity_graph_calendar_events.identity_id', 'graph_calendar_events.identity_id')
            ->join('graph_calendar_event_users', 'graph_calendar_event_users.calendar_event_id', 'graph_calendar_events.calendar_event_id')
            ->join('portal_social_api', 'portal_social_api.connectorid', 'graph_calendar_event_users.connector_id')
            ->join('graph_calendar', 'graph_calendar.graph_cal_id', 'graph_calendar_events.graph_cal_id')
            ->leftjoin('location_list', function($graphEventDetails) use($eventTableId)
            {
              $graphEventDetails->on('graph_calendar_events.identity_id','=','location_list.identity_id')
                  ->where('location_list.identity_table_id', $eventTableId);
            })
            ->leftjoin('location_city','location_city.city_id','=','location_list.location_city_id')
            ->where('graph_calendar_event_users.status', 1)
            ->where('graph_calendar_event_users.portal_user_id', $this->userId)
            ->groupBy('graph_calendar_events.calendar_event_id')
            ->get()->toArray();
        foreach ($graphEventDetails as $key => $value) {
            $graphEventDetails[$key]['owner_event'] = true;
        }
        $ownHideEvent = GraphCalendarEvents::select('graph_share_group_event.group_event_id')
            ->join('graph_share_group_event', 'graph_share_group_event.calendar_event_id', 'graph_calendar_events.calendar_event_id')
            ->join('graph_group_user_hide_event', function ($join) {
                $join->on('graph_share_group_event.group_event_id', 'graph_group_user_hide_event.group_event_id')
                    ->on('graph_group_user_hide_event.calendar_event_id', 'graph_share_group_event.calendar_event_id')
                    ->where('graph_group_user_hide_event.portal_user_id', $this->userId);
            })
            ->where('graph_share_group_event.group_id', $this->roleId)
            ->groupBy('graph_share_group_event.group_event_id', 'graph_share_group_event.calendar_event_id')
            ->distinct()
            ->get()->toArray();
        $graphEventShareDetails = GraphCalendarEvents::select('graph_calendar_events.*', 'graph_calendar.cal_name', 'graph_calendar.cal_id','graph_share_group_event.comment', 'graph_share_group_event.group_id','identity_graph_calendar_events.identity_name as event_name','identity_graph_calendar_events.identity_logo as avatar_link','identity_graph_calendar_events.identity_website as website_link')
            ->join('identity_graph_calendar_events', 'identity_graph_calendar_events.identity_id', 'graph_calendar_events.identity_id')
            ->join('graph_calendar', 'graph_calendar.graph_cal_id', 'graph_calendar_events.graph_cal_id')
            ->join('graph_share_group_event', 'graph_share_group_event.calendar_event_id', 'graph_calendar_events.calendar_event_id')
            ->leftjoin('location_list', function($graphEventShareDetails) use($eventTableId)
            {
              $graphEventShareDetails->on('graph_calendar_events.identity_id','=','location_list.identity_id')
                  ->where('location_list.identity_table_id', $eventTableId);
            })
            ->leftjoin('location_city','location_city.city_id','=','location_list.location_city_id')
            ->whereNotIn('graph_share_group_event.group_event_id', $ownHideEvent)
            ->where('graph_share_group_event.group_id', $this->roleId)
            ->groupBy('graph_share_group_event.group_event_id', 'graph_share_group_event.calendar_event_id')
            ->distinct()
            ->get()->toArray();

        $graphEventSummary = array_merge($graphEventDetails, $graphEventShareDetails);
        $graphEventSummary = self::unique_multidim_array($graphEventSummary, 'calendar_event_id');


        $permissions        = $this->getPermission("Google_events");
        $adminReoles        = array("access", "manage", "add", "delete");
        $matchRoles         = count(array_intersect($permissions, $adminReoles));
        foreach ($graphEventSummary as $key => $value) {
            $graphEventSummary[$key]['matchRoles'] = $matchRoles;
        }
        $graphEventSummaryData = array();
        foreach ($graphEventSummary as $graphEventSummaryKey => $graphEventSummaryValue) {
            $graphEventSummaryValue['start_date'] = date('Y-m-d\TH:i:s\Z', strtotime($graphEventSummaryValue['start_date'] . " " . $graphEventSummaryValue['start_time']));
            $graphEventSummaryValue['end_date']   = date('Y-m-d\TH:i:s\Z', strtotime($graphEventSummaryValue['end_date'] . " " . $graphEventSummaryValue['end_time']));
            $graphEventSummaryValue['all_day']    = ($graphEventSummaryValue['all_day']) ? true : false;
            $graphEventSummaryData[]              = $graphEventSummaryValue;
            # code...
        }
        return $callback . "(" . json_encode($graphEventSummaryData) . ")";

    }

    public function createGraphEvents(Request $request)
    {
        $callback      = $request->callback;
        $createEventData = json_decode($request->models);
        $request->connectorName = 'Google';
        $socialManagerObject = new SocialEventManager($request->connectorName);
        foreach ($createEventData as $createEventDataKey => $createEventDataValue) {
            switch ($request->connectorName) {
                case 'Google':
                    $createEventResponse = $this->createGoogleCalendarEvent($createEventDataValue);
                    break;
                default:
            }
            if(isset($createEventResponse) && $createEventResponse !== '')
            {
                $calendarSummary = self::checkCalendarExist($createEventResponse->creator->email);
                if(empty($calendarSummary))
                {
                    $collectiveType = 'Calendar';
                    $collectiveSummary = $socialManagerObject->insertCollective($collectiveType);
                    $calendarListEntry = $this->getCalendarDetails($createEventResponse->creator->email);
                    $calendarIdentity     = $calendarListEntry->getId();
                    $calendarEntrySummary = $calendarListEntry->getSummary();
                    $calendarEntryDescription = $calendarListEntry->getDescription();
                    $calendarStatus = 1;
                    $calendarSummary = $socialManagerObject->insertCalendar($calendarIdentity, $calendarEntrySummary,$collectiveSummary->collective_id,$calendarStatus, $calendarEntryDescription);
                }
                $eventSummary  = $createEventResponse->getSummary();
                $eventIdentity = $createEventResponse->getId();
                $attendesData  = $createEventResponse->getAttendees();
                $creatorEmail  = $createEventResponse->getCreator();
                $startTime       = $createEventResponse->getstart();
                $endTime       = $createEventResponse->getend();
                $eventLink     = $createEventResponse->getHtmlLink();
                $eventCity     = $calendarEvent->getLocation();
                $eventAvatar = '';
                $eventDescription = '';
                $eventType     = $createEventResponse->getVisibility();
                if($createEventResponse->getCreator()->getSelf() == 1)
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
                $graphCalendarEventObject = $socialManagerObject->insertEvents($calendarSummary, $eventIdentity, $socialEventSummaryDetails, $eventLink,$eventAvatar, $socialStartDate, $socialStartTime, $socialEndDate, $socialEndTime, $allDataEvent, $eventTypeValue, $eventDescription, $eventCity);
                $graphCalendarEventArray = $graphCalendarEventObject->toArray();
                $graphCalendarEventArray['start_date'] = $createEventDataValue->start_date;
                $graphCalendarEventArray['end_date'] = $createEventDataValue->end_date;
            } else {
                return $callback;
            }
        }
        $calendarObject = GraphCalendar::join('graph_calendar_events','graph_calendar_events.graph_cal_id','graph_calendar.graph_cal_id')
            ->join('graph_calendar_event_users', 'graph_calendar_event_users.calendar_event_id', 'graph_calendar_events.calendar_event_id')
            ->join('portal_social_api', 'portal_social_api.connectorid', 'graph_calendar_event_users.connector_id')
            ->where('graph_calendar_events.graph_cal_id',$graphCalendarEventObject->graph_cal_id)
            ->select('graph_calendar.cal_id','graph_calendar.cal_name','portal_social_api.connectorname')
            ->get()->first();
        $eventDetailsArray = array_merge($graphCalendarEventArray,$calendarObject->toArray());
        $permissions        = $this->getPermission("Google_events");
        $adminReoles        = array("access", "manage", "add", "delete");
        $matchRoles         = count(array_intersect($permissions, $adminReoles));
        $eventDetailsArray['matchRoles'] = $matchRoles;
        $eventDetailsArray['owner_event'] = 1;
        $resultArray[] = $eventDetailsArray;
        return $callback . "(" . json_encode($resultArray) . ")";
    }

    public function updateGraphEvents(Request $request)
    {
        $callback      = $request->callback;
        $editEventData = json_decode($request->models);
        $socialManagerObject = new SocialEventManager('Google');
        foreach ($editEventData as $editEventDataKey => $editEventDataValue) {
            $privateEventObject = GraphCalendarEvents::
                join('graph_calendar_event_users', 'graph_calendar_event_users.calendar_event_id', 'graph_calendar_events.calendar_event_id')
                ->join('portal_social_api', 'portal_social_api.connectorid', 'graph_calendar_event_users.connector_id')
                ->where('graph_calendar_events.calendar_event_id',$editEventDataValue->calendar_event_id)
                ->where('graph_calendar_event_users.portal_user_id',$this->userId)
                ->select('graph_calendar_events.private_event','portal_social_api.connectorname')
                ->get()->first();
            if(isset($privateEventObject->private_event) && $privateEventObject->private_event == 1 && isset($privateEventObject->connectorname))
            {
                switch ($privateEventObject->connectorname) {
                    case 'Google':
                        $editEventResponse = $this->updateGoogleCalendarEvent($editEventDataValue);
                        break;
                    default:
                }
                
            }
            $startDateObject                       = new DateTime($editEventDataValue->start_date);
            $endDateObject                         = new DateTime($editEventDataValue->end_date);
            $startDate                             = $startDateObject->format('Y-m-d');
            $startTime                             = $startDateObject->format('H:i:s');
            $endDate                               = $endDateObject->format('Y-m-d');
            $endTime                               = $endDateObject->format('H:i:s');
            $graphCalendarEventsObject             = GraphCalendarEvents::findOrfail($editEventDataValue->calendar_event_id);

            $identityGraphCalendarEventObject = IdentityGraphCalendarEvents::findOrfail($graphCalendarEventsObject->identity_id);
            $identityGraphCalendarEventObject->event_name;
            $identityGraphCalendarEventObject->save();

            $graphCalendarEventsObject->start_date = $startDate;
            $graphCalendarEventsObject->end_date   = $endDate;
            $graphCalendarEventsObject->start_time = $startTime;
            $graphCalendarEventsObject->end_time   = $endTime;
            $graphCalendarEventsObject->all_day    = ($editEventDataValue->all_day) ? 1 : 0;
            $graphCalendarEventsObject->save();
        }
        return $callback . "(" . $request->models . ")";
    }

    public function destroyGraphEvents(Request $request)
    {
        $callback         = $request->callback;
        $destroyEventData = json_decode($request->models);
        foreach ($destroyEventData as $destroyEventDataKey => $destroyEventDataValue) {
            if(isset($destroyEventDataValue->owner_event) && $destroyEventDataValue->owner_event == 1)
            {
                if(isset($destroyEventDataValue->private_event) && $destroyEventDataValue->private_event == 1)
                {
                    switch ($destroyEventDataValue->connectorname) {
                        case 'Google':
                            $deleteEventResponse = $this->deleteGoogleCalendarEvent($destroyEventDataValue->cal_id,$destroyEventDataValue->graph_event_id);
                            break;
                        default:
                    }
                    self::deleteEvent($destroyEventDataValue->calendar_event_id);
                } else {
                    self::distableOwnerEventStatus($destroyEventDataValue->calendar_event_id);
                }
                
            } else {
                self::distableSharedEventStatus($destroyEventDataValue->calendar_event_id);  
            }
            
        }
        return $callback . "(" . $request->models . ")";
    }
    public function unique_multidim_array($calendarArray, $calendarArrayKey)
    {
        $temporaryArray       = array();
        $arrayIndex           = 0;
        $intermediateKeyArray = array();
        foreach ($calendarArray as $calendarArrayValue) {
            if (!in_array($calendarArrayValue[$calendarArrayKey], $intermediateKeyArray)) {
                $intermediateKeyArray[$arrayIndex] = $calendarArrayValue[$calendarArrayKey];
                $temporaryArray[$arrayIndex]       = $calendarArrayValue;
            } else {
                $searchedKey                                  = array_search($calendarArrayValue[$calendarArrayKey], $intermediateKeyArray);
                $temporaryArray[$searchedKey]['shared_event'] = true;
            }
            $arrayIndex++;
        }
        return array_values($temporaryArray);
    }

    public function distableOwnerEventStatus($eventIdentity)
    {
        GraphCalendarEventUsers::join('graph_calendar_events', 'graph_calendar_events.calendar_event_id', 'graph_calendar_event_users.calendar_event_id')
        ->where('graph_calendar_event_users.portal_user_id', $this->userId)
        ->where('graph_calendar_events.calendar_event_id', $eventIdentity)
        ->update(['graph_calendar_event_users.status' => 0]);
    }

    public function distableSharedEventStatus($eventIdentity)
    {
        $graphGroupListExist = GraphShareGroupEvent::where('calendar_event_id', $eventIdentity)
            ->where('group_id',$this->roleId)->get()->first();
        if ($graphGroupListExist) {
            $graphGroupUserHideEvent                    = new GraphGroupUserHideEvent();
            $graphGroupUserHideEvent->calendar_event_id = $graphGroupListExist->calendar_event_id;
            $graphGroupUserHideEvent->group_event_id    = $graphGroupListExist->group_event_id;
            $graphGroupUserHideEvent->portal_user_id    = $this->userId;
            $graphGroupUserHideEvent->save();
        }
    }

    public function deleteEvent($eventIdentity)
    {
        GraphGroupUserHideEvent::where('calendar_event_id',$eventIdentity)->delete();
        GraphShareGroupEvent::where('calendar_event_id',$eventIdentity)->delete();
        GraphCalendarEventUsers::where('calendar_event_id',$eventIdentity)->delete();
        GraphCalendarEvents::where('calendar_event_id',$eventIdentity)->delete();
        IdentityGraphCalendarEvents::join('graph_calendar_events','graph_calendar_events.identity_id','identity_graph_calendar_events.identity_id')->where('graph_calendar_events.calendar_event_id',$eventIdentity)->delete();
    }

    public function checkCalendarExist($calendarIdentity)
    {
        $calendarSummary     = GraphCalendar::where('graph_calendar.cal_id', $calendarIdentity)->get()->first();
        if (isset($calendarSummary->cal_id) && $calendarSummary->cal_id != '') {
            return $calendarSummary;
        }
        return false;
    }
    
}
