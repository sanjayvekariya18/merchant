<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\App;
use Illuminate\Http\Request;
use App\Http\Controllers\PermissionsController;
use App\Helpers\ConnectionManager;
use App\Http\Traits\PermissionTrait;
use App\Working_holiday;
use App\Countries;
use App\State;
use Redirect;
use URL;
use DB;
use Session;

/**
 * Class Hase_working_holidayController.
 *
 */
class Hase_working_holidayController extends PermissionsController
{
    use PermissionTrait;

    public function __construct()
    {
        parent::__construct();

        $connectionStatus = ConnectionManager::setDbConfig('Hase_merchant');
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
        $title = 'Index - hase_working_holiday';
        $hase_working_holidays = DB::table('working_holidays')
            ->select('working_holidays.*','location_country.country_name','location_state.state_name')
            ->leftjoin('location_country','working_holidays.country_id','=','location_country.country_id')
            ->leftjoin('location_state','working_holidays.state_id','=','location_state.state_id')
            ->get();
        return view('hase_working_holiday.index',compact('hase_working_holidays','title'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return  \Illuminate\Http\Response
     */
    public function create()
    {
        $title = 'Create - hase_working_holiday';
        $hase_countries = Countries::orderBy('country_name', 'ASC')->get()->toArray();
        return view('hase_working_holiday.create',compact('hase_countries'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return  \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $hase_working_holiday = new Working_holiday();
        $hase_working_holiday->country_id = $request->holiday_country_id;
        $hase_working_holiday->state_id = $request->holiday_state_id;
        $hase_working_holiday->holiday_name = $request->holiday_name;
        $holidayDate = str_replace('-', '', $request->holiday_date);
        $hase_working_holiday->holiday_date = $holidayDate;
        $hase_working_holiday->save();
        Session::flash('type', 'success');
        Session::flash('msg', 'Holiday Successfully Inserted');
        if ($request->submitbutton === "Save") {
           return redirect('hase_working_holiday/'. $hase_working_holiday->holiday_id . '/edit');
        } else {
           return redirect('hase_working_holiday');
        }
    }

    /**
     * Show the form for editing the specified resource.
     * @param \Illuminate\Http\Request  $request
     * @param int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id,Request $request)
    {
        $title = 'Edit - hase_working_holiday';
        $hase_working_holiday = Working_holiday::findOrfail($id);
        $hase_states = State::
            select('location_state.*')
            ->leftjoin('location_city', 'location_state.state_id', 'location_city.state_id')
            ->where('location_city.country_id',$hase_working_holiday->country_id)
            ->orWhere('location_state.state_id',0)
            ->groupBy('state_id')->get();
        $hase_countries = Countries::orderBy('country_name', 'ASC')->get()->toArray();

        return view('hase_working_holiday.edit',compact('title','hase_working_holiday','hase_countries','hase_states'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request  $request
     * @param int  $id
     * @return  \Illuminate\Http\Response
     */
    public function update($id,Request $request)
    {
        $hase_working_holiday = Working_holiday::findOrfail($id);
        
        $hase_working_holiday->country_id = $request->holiday_country_id;
        $hase_working_holiday->state_id = $request->holiday_state_id;
        $holidayDate = str_replace('-', '', $request->holiday_date);
        $hase_working_holiday->holiday_date = $holidayDate;
        $hase_working_holiday->holiday_name = $request->holiday_name;
        $hase_working_holiday->save();
        Session::flash('type', 'success');
        Session::flash('msg', 'Holiday Successfully Updated');

        if ($request->submitbutton === "Save") {
           return redirect('hase_working_holiday/'. $hase_working_holiday->holiday_id . '/edit');
        } else {
           return redirect('hase_working_holiday');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return  \Illuminate\Http\Response
     */
    public function destroy($id)
    {
     	$hase_working_holiday = Working_holiday::findOrfail($id);
     	$hase_working_holiday->delete();
        Session::flash('type', 'success');
        Session::flash('msg', 'Holiday Successfully Deleted');
        return redirect('hase_working_holiday');
    }
    /**
     * Delete confirmation message by Ajaxis.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return  String
     */
    public function getCountryState(Request $request)
    {
        $states = State::
            select('location_state.*')
            ->leftjoin('location_city', 'location_state.state_id', 'location_city.state_id')
            ->where('location_city.country_id',$request->country_id)
            ->orWhere('location_state.state_id',0)
            ->groupBy('state_id')->get();
        return $states;
    }

}

