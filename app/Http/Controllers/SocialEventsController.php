<?php

namespace App\Http\Controllers;

use App\Http\Controllers\PermissionsController;
use Illuminate\Support\Facades\App;
use Illuminate\Http\Request;
use App\GraphShareGroupEvent;
use App\GraphEventCategories;

/**
 * Class Account_typeController.
 *
 * @author  The scaffold-interface created at 2018-02-18 01:01:05pm
 * @link  https://github.com/amranidev/scaffold-interface
 */
class SocialEventsController extends PermissionsController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function sharedEventGroup(Request $request)
    {
        $sharedGroupIds    = array();
        $sharedGroupValues = GraphShareGroupEvent::join('graph_calendar_events', 'graph_calendar_events.calendar_event_id', 'graph_share_group_event.calendar_event_id')
            ->where('graph_calendar_events.calendar_event_id', $request->calendar_event_id)
            ->where('graph_share_group_event.status', 1)
            ->select('graph_share_group_event.group_id')
            ->get();
        foreach ($sharedGroupValues as $key => $value) {
            $sharedGroupIds[] = $value->group_id;
        }
        return json_encode(array_unique($sharedGroupIds));
    }

    public function shareEventToGroup(Request $request)
    {
        $unshareComment   = $request->unshareComment;
        $eventIdentity       = $request->sharedEventId;
        $existGroupId     = array();
        $shareEventArray  = explode(',', $request->sharedEventGroupList);
        $deletedGroupList = GraphShareGroupEvent::
            join('graph_calendar_events', 'graph_calendar_events.calendar_event_id', 'graph_share_group_event.calendar_event_id')
            ->where('graph_calendar_events.calendar_event_id', $eventIdentity)
            ->where('graph_share_group_event.status',1)
            ->whereNOTIn('graph_share_group_event.group_id', $shareEventArray)->update(array('status' => 0, 'comment' => $unshareComment));

        $existGroup = GraphShareGroupEvent::
            join('graph_calendar_events', 'graph_calendar_events.calendar_event_id', 'graph_share_group_event.calendar_event_id')
            ->where('graph_calendar_events.calendar_event_id', $eventIdentity)
            ->whereIn('graph_share_group_event.group_id', $shareEventArray)
            ->select('graph_share_group_event.group_id','graph_share_group_event.group_event_id')->get();
        foreach ($existGroup as $key => $value) {
            $existGroupId[] = $value->group_id;
            $graphSharedGroupEvent = graphShareGroupEvent::findOrfail($value->group_event_id);
            $graphSharedGroupEvent->status=1;
            $graphSharedGroupEvent->comment='';
            $graphSharedGroupEvent->save();

        }
        $shareEventArray = array_diff($shareEventArray, $existGroupId);
        foreach ($shareEventArray as $shareEventArrayKey => $shareEventArrayValue) {
            if ($shareEventArrayValue) {
                $graphSharedGroupEvent                    = new graphShareGroupEvent();
                $graphSharedGroupEvent->group_id          = $shareEventArrayValue;
                $graphSharedGroupEvent->calendar_event_id = $eventIdentity;
                $graphSharedGroupEvent->save();
            }
        }
    }

    public function saveEventCategories(Request $request)
    {
        $selectedCategories = explode(',', $request->selectedCategory);

        $deletedEventCategories = GraphEventCategories::select('category_id')
            ->where('calendar_event_id',$request->categoryEventId)
            ->whereNOTIn('category_id', $selectedCategories)->delete();

        $existEventCategories = GraphEventCategories::whereIn('category_id', $selectedCategories)
            ->where('calendar_event_id',$request->categoryEventId)
            ->select('category_id')->get();
        foreach ($existEventCategories as $key => $value) {
            $key = array_search($value->category_id, $selectedCategories);
            unset($selectedCategories[$key]);
        }
        foreach ($selectedCategories as $categoryKey => $categoryvalue) {
            $graphEventCategories = new GraphEventCategories();
            $graphEventCategories->calendar_event_id = $request->categoryEventId;
            $graphEventCategories->category_id = $categoryvalue;
            $graphEventCategories->save();
        }
    }

    public function graphEventCategories(Request $request)
    {
        $categoryIds    = array();
        $graphEventCategories = GraphEventCategories::join('merchant_retail_category_type','merchant_retail_category_type.category_type_id','graph_event_categories.category_id')
            ->join('merchant_type', 'merchant_type.merchant_type_id', 'merchant_retail_category_type.merchant_type_id')
            ->where('graph_event_categories.calendar_event_id', $request->calendar_event_id)
            ->select('graph_event_categories.category_id','merchant_retail_category_type.merchant_type_id','merchant_type.merchant_type_name')
            ->get();
        foreach ($graphEventCategories as $key => $value) {
            $categoryIds[] = $value->category_id;
        }
        $eventCategories['categories'] = array_unique($categoryIds);
        if(isset($graphEventCategories[0]))
        {
            $merchantTypeId = $graphEventCategories[0]->merchant_type_id;
            $merchantTypeName = $graphEventCategories[0]->merchant_type_name;  
        } else {
            $merchantTypeId = '';
            $merchantTypeName = '';
        }
        $eventCategories['merchant_type_id'] = $merchantTypeId;
        $eventCategories['merchant_type_name'] = $merchantTypeName;
        return json_encode($eventCategories);
    }
}
