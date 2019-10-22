<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\App;
use Illuminate\Http\Request;
use App\Http\Controllers\PermissionsController;
use App\Helpers\ConnectionManager;
use App\Exhibition;
use App\Venue;
use App\Exhibition_working_hour;
use App\Http\Traits\PermissionTrait;
use URL;
use Session;
use DB;
use Redirect;
use Auth;


/**
 * Class Hase_exhibitionController.
 *
 * @author  The scaffold-interface created at 2017-05-24 04:20:12pm
 * @link  https://github.com/amranidev/scaffold-interface
 */
class Hase_exhibitionController extends PermissionsController
{
    use PermissionTrait;

    public function __construct()
    {
        parent::__construct();

        $connectionStatus = ConnectionManager::setDbConfig('Hase_exhibition');
        if ($connectionStatus['type'] === "error") {
            Session::flash('type', $connectionStatus['type']);
            Session::flash('msg', $connectionStatus['message']);
            return Redirect::back()->send();
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return  \Illuminate\Http\Response
     */
    public function index()
    {
        if($this->permissionDetails('Hase_exhibition','access')) {
            $permissions = $this->getPermission('Hase_exhibition');
            $title = 'Index - hase_exhibition';
            $hase_exhibitions = Exhibition::
                                select('exhibition.*','identity_venue.identity_name as venue_name')
                                ->join('venue','exhibition.venue_id','=','venue.venue_id')
                                ->join('identity_venue','venue.identity_id','identity_venue.identity_id')
                                ->get();
                                
            return view('hase_exhibition.index',compact('hase_exhibitions','title','permissions'));
        } else {
            return Redirect::back()->with('message', 'You are not authorized to use this functionality!');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return  \Illuminate\Http\Response
     */
    public function create()
    {
        $title = 'Create - hase_exhibition';
        
        $venues = Venue::select('venue.venue_id','identity_venue.identity_code as venue_code','identity_venue.identity_name as venue_name')
                    ->join('identity_venue','venue.identity_id','identity_venue.identity_id')
                    ->get();

        return view('hase_exhibition.create',compact('venues'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param    \Illuminate\Http\Request  $request
     * @return  \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(count($request->holiday_hours)) {
            foreach ($request->holiday_hours as $key => $value) {
                $dateArray[] = $value['date'];
                $startTimeArray[] = $value['open'];
                $endTimeArray[] = $value['close'];
            }
        }
        usort($dateArray, function($a, $b) {
            $dateTimestamp1 = strtotime($a);
            $dateTimestamp2 = strtotime($b);

            return $dateTimestamp1 < $dateTimestamp2 ? -1: 1;
        });

        $request->exhibition_date_start = $dateArray[0];
        $request->exhibition_date_end = $dateArray[count($dateArray) - 1];
        $request->exhibition_time_start = $startTimeArray[0];
        $request->exhibition_time_end = $endTimeArray[count($endTimeArray) - 1];


        $startTimeData = explode(":", $request->exhibition_time_start);
        $startTime = $startTimeData[0]*3600+$startTimeData[1]*60;

        $endTimeData = explode(":", $request->exhibition_time_end);
        $endTime = $endTimeData[0]*3600+$endTimeData[1]*60;

        $hase_exhibition = new Exhibition();

        $hase_exhibition->exhibition_name = $request->exhibition_name;
        $hase_exhibition->exhibition_date_start = (!empty($request->exhibition_date_start))?str_replace("-","",date('Y-m-d',strtotime($request->exhibition_date_start))) : 0;

        $hase_exhibition->exhibition_date_end = (!empty($request->exhibition_date_end))?str_replace("-","",date('Y-m-d',strtotime($request->exhibition_date_end))) : 0;

        $hase_exhibition->exhibition_time_start = $startTime;
        $hase_exhibition->exhibition_time_end = $endTime;

        $hase_exhibition->venue_id = $request->venue_id;
        $hase_exhibition->save();

        if(count($request->holiday_hours)) {
            foreach ($request->holiday_hours as $haseHolidayKey => $haseHolidayValue) {
                $hase_exhibition_working_hour = new Exhibition_working_hour();

                $hase_exhibition_working_hour->exhibition_date = (!empty($haseHolidayValue['date']))?str_replace("-","",date('Y-m-d',strtotime($haseHolidayValue['date']))) : 0;

                $openTimeData = explode(":", $haseHolidayValue['open']);
                $exhibitionStartTime = $openTimeData[0]*3600+$openTimeData[1]*60;

                $endTimeData = explode(":", $haseHolidayValue['close']);
                $exhibitionEndTime = $endTimeData[0]*3600+$endTimeData[1]*60;

                $hase_exhibition_working_hour->exhibition_time_start = $exhibitionStartTime;
                $hase_exhibition_working_hour->exhibition_time_end = $exhibitionEndTime;
                $hase_exhibition_working_hour->exhibition_id = $hase_exhibition->exhibition_id;
                $hase_exhibition_working_hour->save();
            }
        }
        Session::flash('type', 'success');
        Session::flash('msg', 'Exhibition Successfully Inserted');

        if ($request->submitBtn === "Save") {
           return redirect('hase_exhibition/'. $hase_exhibition->exhibition_id . '/edit');
        }else{
           return redirect('hase_exhibition');
        }
    }

    /**
     * Show the form for editing the specified resource.
     * @param    \Illuminate\Http\Request  $request
     * @param    int  $id
     * @return  \Illuminate\Http\Response
     */
    public function edit($id,Request $request)
    {

        if($this->permissionDetails('Hase_exhibition','manage')) {
            $title = 'Edit - hase_exhibition';

            $venues = Venue::select('venue.venue_id','identity_venue.identity_code as venue_code','identity_venue.identity_name as venue_name')
                    ->join('identity_venue','venue.identity_id','identity_venue.identity_id')
                    ->get();

            $hase_exhibition = Exhibition::
                                join('venue','exhibition.venue_id','=','venue.venue_id')
                                ->where('exhibition.exhibition_id',$id)
                                ->get()->first();

            $hase_exhibition->exhibition_date_start =  date('m/d/Y',strtotime($hase_exhibition->exhibition_date_start));
            $hase_exhibition->exhibition_date_end =  date('m/d/Y',strtotime($hase_exhibition->exhibition_date_end));

            $hase_exhibition_working_hours = Exhibition_working_hour::where('exhibition_id','=',$id)->get();

            $total_exhibition_day = count($hase_exhibition_working_hours);

            return view('hase_exhibition.edit',compact('title','hase_exhibition','hase_exhibition_working_hours','total_exhibition_day','venues'));
        } else {
            return Redirect::back()->with('message', 'You are not authorized to use this functionality!');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param    \Illuminate\Http\Request  $request
     * @param    int  $id
     * @return  \Illuminate\Http\Response
     */
    public function update($id,Request $request)
    {
        $hase_exhibition = Exhibition::
                                join('venue','exhibition.venue_id','=','venue.venue_id')
                                ->where('exhibition.exhibition_id',$id)
                                ->get()->first();

        if(count($request->holiday_hours)) {
            foreach ($request->holiday_hours as $key => $value) {
                $dateArray[] = $value['date'];
                $startTimeArray[] = $value['open'];
                $endTimeArray[] = $value['close'];
            }
        }
        usort($dateArray, function($a, $b) {
            $dateTimestamp1 = strtotime($a);
            $dateTimestamp2 = strtotime($b);

            return $dateTimestamp1 < $dateTimestamp2 ? -1: 1;
        });

        $request->exhibition_date_start = $dateArray[0];
        $request->exhibition_date_end = $dateArray[count($dateArray) - 1];
        $request->exhibition_time_start = $startTimeArray[0];
        $request->exhibition_time_end = $endTimeArray[count($endTimeArray) - 1];

        $startTimeData = explode(":", $request->exhibition_time_start);
        $startTime = $startTimeData[0]*3600+$startTimeData[1]*60;

        $endTimeData = explode(":", $request->exhibition_time_end);
        $endTime = $endTimeData[0]*3600+$endTimeData[1]*60;
        
        $hase_exhibition->exhibition_name = $request->exhibition_name;        
        $hase_exhibition->venue_id = $request->venue_id; 

        $hase_exhibition->exhibition_date_start = (!empty($request->exhibition_date_start))?str_replace("-","",date('Y-m-d',strtotime($request->exhibition_date_start))) : 0;

        $hase_exhibition->exhibition_date_end = (!empty($request->exhibition_date_end))?str_replace("-","",date('Y-m-d',strtotime($request->exhibition_date_end))) : 0;

        $hase_exhibition->exhibition_time_start = $startTime;
        $hase_exhibition->exhibition_time_end = $endTime;   

        $hase_exhibition->save();

        Exhibition_working_hour::where('exhibition_id', $id)->delete();
        if(count($request->holiday_hours)) {
            foreach ($request->holiday_hours as $haseHolidayKey => $haseHolidayValue) {
                $hase_exhibition_working_hour = new Exhibition_working_hour();

                $hase_exhibition_working_hour->exhibition_date = (!empty($haseHolidayValue['date']))?str_replace("-","",date('Y-m-d',strtotime($haseHolidayValue['date']))) : 0;

                $openTimeData = explode(":", $haseHolidayValue['open']);
                $exhibitionStartTime = $openTimeData[0]*3600+$openTimeData[1]*60;

                $endTimeData = explode(":", $haseHolidayValue['close']);
                $exhibitionEndTime = $endTimeData[0]*3600+$endTimeData[1]*60;

                $hase_exhibition_working_hour->exhibition_time_start = $exhibitionStartTime;
                $hase_exhibition_working_hour->exhibition_time_end = $exhibitionEndTime;
                $hase_exhibition_working_hour->exhibition_id = $hase_exhibition->exhibition_id;
                $hase_exhibition_working_hour->save();
            }
        }

        Session::flash('type', 'success');
        Session::flash('msg', 'Exhibition Successfully Updated');

        if ($request->submitBtn === "Save") {
           return redirect('hase_exhibition/'. $hase_exhibition->exhibition_id . '/edit');
        }else{
           return redirect('hase_exhibition');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param    int $id
     * @return  \Illuminate\Http\Response
     */
    public function destroy($id)
    {
     	
        if($this->permissionDetails('Hase_exhibition','delete')) {
            $hase_exhibition = Exhibition::findOrfail($id);
            $hase_exhibition->delete();

            Exhibition_working_hour::where('exhibition_id', $id)->delete();

            Session::flash('type', 'success'); 
            Session::flash('msg', 'Exhibition Successfully Deleted');
            return redirect('hase_exhibition');
        } else {
            return Redirect::back()->with('message', 'You are not authorized to use this functionality!');
        }
    }
}
