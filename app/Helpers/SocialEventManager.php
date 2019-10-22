<?php

namespace App\Helpers;

use App\GraphCalendar;
use App\GraphCalendarEvents;
use App\IdentityGraphCalendarEvents;
use App\GraphCalendarEventUsers;
use App\GraphCalendarUsers;
use App\GraphCollective;
use App\GraphGroupUserHideEvent;
use App\GraphRelation;
use App\GraphShareGroupEvent;
use App\Location_list;
use App\City;
use App\Http\Traits\PermissionTrait;
use DB;
use Illuminate\Support\Facades\App;
use Session;

class SocialEventManager
{
    private $userId;
    private $connectorName;
    private $connectorId;
    private $eventTableId;

    public function __construct($connectorName)
    {
        $this->connectorName = $connectorName;
        $this->connectorId   = PermissionTrait::getConnectorId($connectorName);
        $this->userId        = session()->has('userId') ? session()->get('userId') : "";
        $this->roleId        = session()->has('role') ? session()->get('role') : "";
        $this->eventTableId  = 64;
    }

    public function insertRelation($relationName)
    {
        $defaultGraphRelation = GraphRelation::select('graph_relation.relation_id')->where('relation_name', $relationName)->get()->first();
        if (!isset($defaultGraphRelation->relation_id)) {
            $graphRelationObject                = new GraphRelation();
            $graphRelationObject->relation_name = $relationName;
            $graphRelationObject->save();
        }
    }

    public function insertCollective($collectiveName)
    {
        $collectiveSummary = GraphCollective::select('graph_collective.collective_id')->where('collective_name', $collectiveName)->get()->first();
        if (!isset($collectiveSummary->collective_id)) {
            $collectiveSummary                = new GraphCollective();
            $collectiveSummary->collective_name = $collectiveName;
            $collectiveSummary->save();
        }
        return $collectiveSummary;
    }

    public function insertCalendar($calendarIdentity, $calendarName,$collectiveId, $calendarStatus, $calendarDescription='', $calendarlink='',$calendarPhoto='')
    {
        $defaultCalendarType = 'Holidays';
        $calendarSummary     = GraphCalendar::where('graph_calendar.cal_name', $calendarName)->get()->first();
        if (isset($calendarSummary->cal_id) && $calendarSummary->cal_id != '') {

            $calendarSummary               = GraphCalendar::findOrfail($calendarSummary->graph_cal_id);
            $calendarSummary->cal_name      = $calendarName;
            $calendarSummary->description      = $calendarDescription;
            $calendarSummary->cal_link      = $calendarlink;
            $calendarSummary->cal_photo      = $calendarPhoto;
            $calendarSummary->save();

            $calendarUserSummary = GraphCalendarUsers::
                where('graph_cal_id', $calendarSummary->graph_cal_id)
                ->where('portal_user_id', $this->userId)
                ->where('connector_id', $this->connectorId)
                ->get()->first();
            if (!isset($calendarUserSummary->calendar_user_id)) {
                /* insert into calendar users*/

                $calendarUserObject                 = new GraphCalendarUsers();
                $calendarUserObject->graph_cal_id   = $calendarSummary->graph_cal_id;
                $calendarUserObject->portal_user_id = $this->userId;
                $calendarUserObject->connector_id   = $this->connectorId;
                $calendarUserObject->status         = $calendarStatus;
                $calendarUserObject->save();

                /* end insert into calendar user */
            }
        } else {

            $graphRelation   = GraphRelation::select('graph_relation.relation_id')->where('relation_name', $defaultCalendarType)->get()->first();
            /* insert into calendar */

            $calendarSummary                = new GraphCalendar();
            $calendarSummary->cal_id        = $calendarIdentity;
            $calendarSummary->cal_name      = $calendarName;
            $calendarSummary->description      = $calendarDescription;
            $calendarSummary->cal_link      = $calendarlink;
            $calendarSummary->cal_photo      = $calendarPhoto;
            $calendarSummary->relation_id = $graphRelation->relation_id;
            $calendarSummary->collective_id   = $collectiveId;
            $calendarSummary->save();

            /* end insert into calendar */

            /* insert into calendar users*/

            $calendarUserObject                 = new GraphCalendarUsers();
            $calendarUserObject->graph_cal_id   = $calendarSummary->graph_cal_id;
            $calendarUserObject->portal_user_id = $this->userId;
            $calendarUserObject->connector_id   = $this->connectorId;
            $calendarUserObject->status         = $calendarStatus;
            $calendarUserObject->save();

            /* end insert into calendar user */
        }
        return $calendarSummary;
    }

    public function insertEvents($calendarSummary, $eventIdentity, $eventSummary, $eventLink, $eventAvatar, $eventStartDate, $eventStartTime, $eventEndDate, $eventEndTime, $allDayEvent, $eventTypeValue, $eventDescription = '',$eventCity = '')
    {

        $collectiveName      = 'Events';
        $defaultCalendarType = 'Holidays';
        $graphCalendarEvents = GraphCalendarEvents::
            select('graph_calendar_events.calendar_event_id')
            ->join('identity_graph_calendar_events','identity_graph_calendar_events.identity_id','graph_calendar_events.identity_id')
            ->where('graph_calendar_events.graph_cal_id', $calendarSummary->graph_cal_id)
            ->where('identity_graph_calendar_events.identity_code', $eventIdentity)
            ->get()->first();
        $socialEventSummary = addslashes(str_replace("&", "and", $eventSummary));
        $socialEventSummary = str_replace("\'", "", $socialEventSummary);
        if (!isset($graphCalendarEvents->calendar_event_id)) {
            $graphRelation      = GraphRelation::select('graph_relation.relation_id')->where('relation_name', $defaultCalendarType)->get()->first();
            $graphCollective    = GraphCollective::select('graph_collective.collective_id')->where('collective_name', $collectiveName)->get()->first();

            /* insert into graph calendar events */
            $identityGraphCalendarEventObject                 = new IdentityGraphCalendarEvents();
            $identityGraphCalendarEventObject->identity_code  = $eventIdentity;
            $identityGraphCalendarEventObject->identity_name  = $socialEventSummary;
            $identityGraphCalendarEventObject->identity_description = $eventDescription;
            $identityGraphCalendarEventObject->identity_logo = $eventAvatar;
            $identityGraphCalendarEventObject->identity_website = $eventLink;
            $identityGraphCalendarEventObject->save();

            
            $graphCalendarEventObject                 = new GraphCalendarEvents();
            $graphCalendarEventObject->identity_id    = $identityGraphCalendarEventObject->identity_id;
            $graphCalendarEventObject->graph_cal_id   = $calendarSummary->graph_cal_id;
            $graphCalendarEventObject->location   = $eventCity;
            $graphCalendarEventObject->relation_id    = isset($graphRelation->relation_id) ? $graphRelation->relation_id : 0;
            $graphCalendarEventObject->collective_id  = isset($graphCollective->collective_id) ? $graphCollective->collective_id : 0;
            $graphCalendarEventObject->start_date     = $eventStartDate;
            $graphCalendarEventObject->start_time     = $eventStartTime;
            $graphCalendarEventObject->end_date       = $eventEndDate;
            $graphCalendarEventObject->end_time       = $eventEndTime;
            $graphCalendarEventObject->all_day        = $allDayEvent;
            $graphCalendarEventObject->private_event  = $eventTypeValue;
            $graphCalendarEventObject->save();
            /* end insert into graph events */
            $locationCityObject = City::select('location_city.city_id')->where('city_name', $eventCity)->get()->first();
            if (isset($locationCityObject->city_id)) {
                $eventCityId = $locationCityObject->city_id;
            } else {
                $eventCityId = 0;
            }

            $location_list = new Location_list();
            $location_list->identity_id = $identityGraphCalendarEventObject->identity_id;
            $location_list->identity_table_id = $this->eventTableId;
            $location_list->location_city_id = $eventCityId;
            $location_list->postal_id = 0;
            $location_list->priority = 0;
            $location_list->status = 1;
            $location_list->save();

            /* insert into graph event users*/
            $graphCalendarEventUsers                    = new GraphCalendarEventUsers();
            $graphCalendarEventUsers->calendar_event_id = $graphCalendarEventObject->calendar_event_id;
            $graphCalendarEventUsers->portal_user_id    = $this->userId;
            $graphCalendarEventUsers->connector_id      = $this->connectorId;
            $graphCalendarEventUsers->status            = 1;
            $graphCalendarEventUsers->save();

            /* end insert into graph event users */

        } else {

            /* update graph identity  */

            $graphCalendarEventObject               = GraphCalendarEvents::findOrfail($graphCalendarEvents->calendar_event_id);

            $identityGraphCalendarEventObject = IdentityGraphCalendarEvents::findOrfail($graphCalendarEventObject->identity_id);
            $identityGraphCalendarEventObject->identity_name  = $socialEventSummary;
            $identityGraphCalendarEventObject->identity_website = $eventLink;
            $identityGraphCalendarEventObject->save();

            $graphCalendarEventObject               = GraphCalendarEvents::findOrfail($graphCalendarEvents->calendar_event_id);
            $graphCalendarEventObject->start_date   = $eventStartDate;
            $graphCalendarEventObject->start_time   = $eventStartTime;
            $graphCalendarEventObject->end_date     = $eventEndDate;
            $graphCalendarEventObject->end_time     = $eventEndTime;
            $graphCalendarEventObject->save();

            /* end update graph identity  */

            $graphEventUsers = GraphCalendarEventUsers::select('event_user_id')
                ->where('calendar_event_id', $graphCalendarEvents->calendar_event_id)
                ->where('portal_user_id', $this->userId)
                ->get()->first();

            if (!isset($graphEventUsers->event_user_id)) {
                /* insert into graph event users*/
                $graphCalendarEventUsers                    = new GraphCalendarEventUsers();
                $graphCalendarEventUsers->calendar_event_id = $graphCalendarEvents->calendar_event_id;
                $graphCalendarEventUsers->portal_user_id    = $this->userId;
                $graphCalendarEventUsers->connector_id      = $this->connectorId;
                $graphCalendarEventUsers->status            = 1;
                $graphCalendarEventUsers->save();

                /* end insert into graph event users */
            }
        }
        return $graphCalendarEventObject;
    }

    public function enableCalendarStatus($calendarIdentity)
    {
        $googleCalendarSummary = GraphCalendar::where('graph_calendar.cal_id', $calendarIdentity)->get()->first();

        $googleCalendarUserSummary = GraphCalendarUsers::
            where('graph_cal_id', $googleCalendarSummary->graph_cal_id)
            ->where('portal_user_id', $this->userId)
            ->where('connector_id', $this->connectorId)
            ->update(['status' => 1]);
        return $googleCalendarSummary;
    }

    public function getSocialConnectorEvents()
    {
        $graphCalendarEvents = GraphCalendarEvents::
            select('identity_graph_calendar_events.identity_code as graph_event_id')
            ->join('identity_graph_calendar_events', 'identity_graph_calendar_events.identity_id', 'graph_calendar_events.identity_id')
            ->join('graph_calendar_event_users', 'graph_calendar_event_users.calendar_event_id', 'graph_calendar_events.calendar_event_id')
            ->where('graph_calendar_event_users.portal_user_id', $this->userId)
            ->where('graph_calendar_event_users.connector_id', $this->connectorId)
            ->get();
        return $graphCalendarEvents;
    }

    public function getUnSyncCalendar($recordSkip,$recondTake)
    {
        $unsyncCalendarlist = GraphCalendar::join('graph_calendar_users', 'graph_calendar_users.graph_cal_id', 'graph_calendar.graph_cal_id')
            ->where('graph_calendar_users.portal_user_id', $this->userId)
            ->where('graph_calendar_users.connector_id', $this->connectorId)
            ->select('graph_calendar.*','graph_calendar_users.status')
            ->orderBy('graph_calendar.graph_cal_id', 'desc');

        $total_records =  $unsyncCalendarlist->get()->count();

        $unSyncCalendarValues = $unsyncCalendarlist->offset($recordSkip)
            ->limit($recondTake)
            ->get()->toArray();
        $calendarListData['googleGroups'] = $unSyncCalendarValues;
        $calendarListData['total'] = $total_records;
        return json_encode($calendarListData);
    }
    public function getCalendarList($recondTake, $recordSkip, $searchFiler)
    {
        $total_records         = 0;
        $googleCalendarSummary = GraphCalendar::
            select('graph_calendar.*', 'graph_calendar.cal_id', 'graph_calendar.cal_name', 'graph_collective.collective_name')
            ->join('graph_collective', 'graph_collective.collective_id', 'graph_calendar.collective_id')
            ->join('graph_calendar_users', 'graph_calendar_users.graph_cal_id', 'graph_calendar.graph_cal_id')
            ->where('graph_calendar_users.status', 1)
            ->where('graph_calendar_users.portal_user_id', $this->userId)
            ->where('graph_calendar_users.connector_id', $this->connectorId);
        if (isset($searchFiler) && $searchFiler != '') {
            $googleCalendarSummary->where('graph_calendar.cal_name', 'LIKE', '%' . $searchFiler . '%');
        }
        $total_records        = $total_records + $googleCalendarSummary->get()->count();
        $googleCalendarValues = $googleCalendarSummary->offset($recordSkip)
            ->limit($recondTake)->get()->toArray();
        $googleCalendarShareDetails = GraphCalendar::
            select('graph_calendar.graph_cal_id', 'graph_calendar.cal_id', 'graph_calendar.cal_name', 'graph_collective.collective_name')
            ->join('graph_calendar_events', 'graph_calendar_events.graph_cal_id', 'graph_calendar.graph_cal_id')
            ->join('graph_share_group_event', 'graph_share_group_event.calendar_event_id', 'graph_calendar_events.calendar_event_id')
            ->join('graph_calendar_event_users', 'graph_calendar_event_users.calendar_event_id', 'graph_calendar_events.calendar_event_id')
            ->join('graph_collective', 'graph_collective.collective_id', 'graph_calendar.collective_id')
            ->where('graph_calendar_event_users.connector_id', $this->connectorId)
            ->where('graph_calendar_event_users.portal_user_id', '!=', $this->userId)
            ->where('graph_share_group_event.group_id', $this->roleId);
        if (isset($searchFiler) && $searchFiler != '') {
            $googleCalendarShareDetails->where('graph_calendar.cal_name', 'LIKE', '%' . $searchFiler . '%');
        }
        $total_records              = $total_records + $googleCalendarShareDetails->groupBy('graph_calendar.graph_cal_id')->get()->count();
        $googleCalendarShareDetails = $googleCalendarShareDetails->offset($recordSkip)
            ->limit($recondTake)->get()->toArray();

        $googleCalendarTotalSummary = array_merge($googleCalendarValues, $googleCalendarShareDetails);
        $googleCalendarTotalSummary = self::unique_multidim_array($googleCalendarTotalSummary, 'cal_id');

        foreach ($googleCalendarTotalSummary as $key => $value) {
            $eventCount = GraphCalendarEvents::select(DB::raw('count("calendar_event_id") as eventCount'))
                ->join('graph_calendar_event_users', 'graph_calendar_event_users.calendar_event_id', 'graph_calendar_events.calendar_event_id')
                ->where('graph_calendar_events.graph_cal_id', $value['graph_cal_id'])
                ->where('graph_calendar_event_users.portal_user_id', $this->userId)
                ->where('graph_calendar_event_users.connector_id', $this->connectorId)
                ->where('graph_calendar_event_users.status', 1)
                ->get()->first();
            if ($eventCount->eventCount == 0) {
                $userIds = GraphGroupUserHideEvent::select('graph_group_user_hide_event.portal_user_id')
                    ->join('graph_share_group_event', 'graph_share_group_event.group_event_id', '=', 'graph_group_user_hide_event.group_event_id')
                    ->join('graph_calendar_events', 'graph_share_group_event.calendar_event_id', 'graph_calendar_events.calendar_event_id')
                    ->where('graph_calendar_events.graph_cal_id', $value['graph_cal_id'])->get();
                $ownHideEvent = GraphCalendarEvents::select('graph_share_group_event.group_event_id')
                    ->where('graph_calendar_events.graph_cal_id', $value['graph_cal_id'])
                    ->join('graph_share_group_event', 'graph_share_group_event.calendar_event_id', 'graph_calendar_events.calendar_event_id')
                    ->join('graph_group_user_hide_event', function ($join) {
                        $join->on('graph_share_group_event.group_event_id', 'graph_group_user_hide_event.group_event_id')
                            ->on('graph_group_user_hide_event.calendar_event_id', 'graph_share_group_event.calendar_event_id')
                            ->where('graph_group_user_hide_event.portal_user_id', $this->userId);
                    })
                    ->where('graph_share_group_event.status', 0)
                    ->where('graph_share_group_event.group_id', $this->roleId)
                    ->groupBy('graph_share_group_event.group_event_id', 'graph_share_group_event.calendar_event_id')
                    ->distinct()
                    ->get()->toArray();
                $eventCountMatch = GraphCalendarEvents::select('graph_share_group_event.group_event_id', 'graph_share_group_event.calendar_event_id')->where('graph_calendar_events.graph_cal_id', $value['graph_cal_id'])
                    ->join('graph_share_group_event', 'graph_share_group_event.calendar_event_id', 'graph_calendar_events.calendar_event_id')
                    ->whereNotIn('graph_share_group_event.group_event_id', $ownHideEvent)
                    ->where('graph_share_group_event.group_id', $this->roleId)
                    ->groupBy('graph_share_group_event.group_event_id', 'graph_share_group_event.calendar_event_id')
                    ->distinct()
                    ->get()->count();
                if ($eventCountMatch == 0) {
                    unset($googleCalendarTotalSummary[$key]);
                    $total_records = $total_records - 1;
                } else {
                    $googleCalendarTotalSummary[$key]['event_count'] = $eventCountMatch;
                }
            } else {
                $googleCalendarTotalSummary[$key]['event_count'] = $eventCount->eventCount;
            }

        }
        $calendar_list_data['graph_calendar'] = array_values($googleCalendarTotalSummary);
        $calendar_list_data['total']          = $total_records;
        return $calendar_list_data;
    }
    public function getEventList($calendarIdentity,$eventTake=0,$eventSkip=0,$searchFiler='')
    {
        $eventTableId = $this->eventTableId;
        $eventDetails = GraphCalendarEvents::
            select('graph_calendar_events.*','identity_graph_calendar_events.identity_code as graph_event_id','identity_graph_calendar_events.identity_name as event_name',
                'identity_graph_calendar_events.identity_description as description',
                'identity_graph_calendar_events.identity_logo as avatar_link',
                'identity_graph_calendar_events.identity_website as website_link')
            ->join('identity_graph_calendar_events', 'identity_graph_calendar_events.identity_id', 'graph_calendar_events.identity_id')
            ->join('graph_calendar_event_users', 'graph_calendar_event_users.calendar_event_id', 'graph_calendar_events.calendar_event_id')
            ->join('graph_calendar', 'graph_calendar.graph_cal_id', 'graph_calendar_events.graph_cal_id')
            ->leftjoin('location_list', function($eventDetails) use($eventTableId)
            {
              $eventDetails->on('graph_calendar_events.identity_id','=','location_list.identity_id')
                  ->where('location_list.identity_table_id', $eventTableId);
            })
            ->leftjoin('location_city','location_city.city_id','=','location_list.location_city_id');
        if (isset($searchFiler) && $searchFiler !== '') {
            $eventDetails->where(function ($query) use ($searchFiler) {
                $query->orWhere('identity_graph_calendar_events.identity_name', 'LIKE', '%' . $searchFiler . '%');
                $query->orWhere('graph_calendar_events.start_date', 'LIKE', '%' . $searchFiler . '%');
                $query->orWhere('graph_calendar_events.end_date', 'LIKE', '%' . $searchFiler . '%');
                $query->orWhere('graph_calendar_events.location', 'LIKE', '%' . $searchFiler . '%');
            });
        }
        $eventDetails = $eventDetails->where('graph_calendar_events.graph_cal_id', $calendarIdentity)
            ->where('graph_calendar_event_users.portal_user_id', $this->userId)
            ->where('graph_calendar_event_users.connector_id', $this->connectorId)
            ->where('graph_calendar_event_users.status', 1)->groupBy('graph_calendar_events.calendar_event_id')->get()->toArray();
        foreach ($eventDetails as $key => $value) {
            $eventDetails[$key]['owner_event'] = true;
            $eventDetails[$key]['status'] = 'new';
        }

        $ownHideEvent = GraphCalendarEvents::select('graph_share_group_event.group_event_id')
            ->join('identity_graph_calendar_events', 'identity_graph_calendar_events.identity_id', 'graph_calendar_events.identity_id')
            ->where('graph_calendar_events.graph_cal_id', $calendarIdentity)
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

        $eventShareDetails = GraphCalendarEvents::select('graph_calendar_events.*','identity_graph_calendar_events.identity_code as graph_event_id','identity_graph_calendar_events.identity_name as event_name','identity_graph_calendar_events.identity_description as description','identity_graph_calendar_events.identity_logo as avatar_link','identity_graph_calendar_events.identity_website as website_link', 'graph_share_group_event.comment', 'graph_share_group_event.group_id')
            ->join('identity_graph_calendar_events', 'identity_graph_calendar_events.identity_id', 'graph_calendar_events.identity_id')
            ->join('graph_share_group_event', 'graph_share_group_event.calendar_event_id', 'graph_calendar_events.calendar_event_id')
            ->join('graph_calendar', 'graph_calendar.graph_cal_id', 'graph_calendar_events.graph_cal_id')
            ->leftjoin('location_list', function($eventShareDetails) use($eventTableId)
            {
              $eventShareDetails->on('graph_calendar_events.identity_id','=','location_list.identity_id')
                  ->where('location_list.identity_table_id', $eventTableId);
            })
            ->leftjoin('location_city','location_city.city_id','=','location_list.location_city_id');
            if (isset($searchFiler) && $searchFiler !== '') {
                $eventShareDetails->where(function ($query) use ($searchFiler) {
                    $query->orWhere('identity_graph_calendar_events.identity_name', 'LIKE', '%' . $searchFiler . '%');
                    $query->orWhere('graph_calendar_events.start_date', 'LIKE', '%' . $searchFiler . '%');
                    $query->orWhere('graph_calendar_events.end_date', 'LIKE', '%' . $searchFiler . '%');
                    $query->orWhere('graph_calendar_events.location', 'LIKE', '%' . $searchFiler . '%');
                });
            }
            $eventShareDetails = $eventShareDetails->whereNotIn('graph_share_group_event.group_event_id', $ownHideEvent)
            ->where('graph_calendar_events.graph_cal_id', $calendarIdentity)
            ->where('graph_share_group_event.group_id', $this->roleId)
            ->groupBy('graph_share_group_event.group_event_id', 'graph_share_group_event.calendar_event_id')
            ->distinct()->get()->toArray();
        foreach ($eventShareDetails as $key => $value) {
            $eventShareDetails[$key]['status'] = 'Shared';
        }
        $eventSummary = array_merge($eventDetails, $eventShareDetails);
        $eventSummary = self::unique_multidim_array($eventSummary, 'calendar_event_id');
        if(!empty($eventTake))
        {
            $paginatedEventSummary = array_slice($eventSummary, $eventSkip, $eventTake, true); 
        } else {
            $paginatedEventSummary = $eventSummary; 
        }
        
        $totalCount = count($eventSummary);
        $eventObjectReturn['eventDetails'] = $paginatedEventSummary;
        $eventObjectReturn['totalCount'] = $totalCount;
        return $eventObjectReturn;

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
                $temporaryArray[$searchedKey]['status'] = 'Shared';
                if(isset($temporaryArray[$searchedKey]['owner_event']) && $temporaryArray[$searchedKey]['owner_event'] === true && isset($calendarArrayValue['group_id']) && $calendarArrayValue['group_id'] == $this->roleId)
                {
                    $temporaryArray[$searchedKey]['own_share'] = true;
                }
            }
            $arrayIndex++;
        }
        return array_values($temporaryArray);
    }

    public function getUserCalendarIdentity()
    {
        $calendarValues = GraphCalendar::join('graph_calendar_users', 'graph_calendar_users.graph_cal_id', 'graph_calendar.graph_cal_id')->where('graph_calendar_users.portal_user_id', $this->userId)
            ->where('graph_calendar_users.connector_id', $this->connectorId)
            ->where('graph_calendar_users.status', 1)
            ->select('graph_calendar.cal_id')
            ->get();
        return $calendarValues;
    }

    public function deleteCalendar($calendarIdentity)
    {
        GraphCalendarUsers::join('graph_calendar', 'graph_calendar.graph_cal_id', 'graph_calendar_users.graph_cal_id')
            ->where('graph_calendar_users.portal_user_id', $this->userId)
            ->where('graph_calendar_users.connector_id', $this->connectorId)
            ->where('graph_calendar.graph_cal_id', $calendarIdentity)
            ->update(['graph_calendar_users.status' => 0]);

        GraphCalendarEventUsers::join('graph_calendar_events', 'graph_calendar_events.calendar_event_id', 'graph_calendar_event_users.calendar_event_id')
            ->where('graph_calendar_events.graph_cal_id', $calendarIdentity)
            ->where('graph_calendar_event_users.portal_user_id', $this->userId)
            ->where('graph_calendar_event_users.connector_id', $this->connectorId)
            ->update(['graph_calendar_event_users.status' => 0]);
    }

    public function deleteEvent($eventIdentity)
    {
        GraphCalendarEventUsers::join('graph_calendar_events', 'graph_calendar_events.calendar_event_id', 'graph_calendar_event_users.calendar_event_id')
            ->where('graph_calendar_event_users.portal_user_id', $this->userId)
            ->where('graph_calendar_event_users.connector_id', $this->connectorId)
            ->where('graph_calendar_events.calendar_event_id', $eventIdentity)
            ->update(['graph_calendar_event_users.status' => 0]);

        $graphGroupListExist = GraphShareGroupEvent::where('calendar_event_id', $eventIdentity)->get()->first();
        if ($graphGroupListExist) {
            $graphGroupUserHideEvent                    = new GraphGroupUserHideEvent();
            $graphGroupUserHideEvent->calendar_event_id = $graphGroupListExist->calendar_event_id;
            $graphGroupUserHideEvent->group_event_id    = $graphGroupListExist->group_event_id;
            $graphGroupUserHideEvent->portal_user_id    = $this->userId;
            $graphGroupUserHideEvent->save();
        }
    }
}
