<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\App;
use Illuminate\Http\Request;
use App\Http\Controllers\PermissionsController;
use App\Helpers\ConnectionManager;
use App\Venue;
use App\City;
use App\Postal;
use App\Http\Traits\PermissionTrait;
use URL;
use Session;
use DB;
use Redirect;
use Auth;

/**
 * Class Hase_venueController.
 *
 * @author  The scaffold-interface created at 2017-05-24 04:20:12pm
 * @link  https://github.com/amranidev/scaffold-interface
 */
class Hase_venueController extends PermissionsController
{
    use PermissionTrait;

    public function __construct()
    {
        parent::__construct();

        $connectionStatus = ConnectionManager::setDbConfig('Hase_venue');
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
        if($this->permissionDetails('Hase_venue','access')) {
            $permissions = $this->getPermission('Hase_venue');
            $title = 'Index - hase_venue';
            $hase_venues = Venue::
                        select('venue.venue_id','venue.venue_name','postal.postal_premise','postal.postal_subpremise','postal.postal_street','postal.postal_street_number','postal.postal_route','postal.postal_neighborhood','postal.postal_postcode','postal.postal_lat','postal.postal_lng','location_city.city_name','location_state.state_name','location_county.county_name','location_country.country_name')
                        ->leftjoin('postal','venue.postal_id','=','postal.postal_id')
                        ->leftjoin('location_city','postal.postal_city','=','location_city.city_id')
                        ->leftjoin('location_state','postal.postal_state','=','location_state.state_id')
                        ->leftjoin('location_county','postal.postal_county','=','location_county.county_id')
                        ->leftjoin('location_country','postal.postal_country','=','location_country.country_id')
                        ->groupBy('venue.venue_id')
                        ->get();

            return view('hase_venue.index',compact('hase_venues','title','permissions'));
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
        $title = 'Create - hase_venue';
        $hase_cities = City::all();
        return view('hase_venue.create',compact('title','hase_cities'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param    \Illuminate\Http\Request  $request
     * @return  \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        $hase_venue = new Venue();
        $hase_postal = new Postal();

        $hase_venue->venue_name = $request->venue_name;

        $hase_postal->postal_city = $request->venue_city_id;

        if(isset($request->venue_city_id))
        {
            $hase_city = City::where('city_id','=',$request->venue_city_id)->get()->first();
            $hase_postal->postal_county = $hase_city->county_id;
            $hase_postal->postal_state = $hase_city->state_id;
            $hase_postal->postal_country = $hase_city->country_id;
        }

        $hase_postal->postal_route = $request->venue_address1;

        $hase_postal->postal_premise = $request->venue_address2;

        $hase_postal->postal_lat = $request->venue_gps_lat;

        $hase_postal->postal_lng = $request->venue_gps_lng;

        $hase_postal->save();

        $hase_venue->postal_id = $hase_postal->postal_id;
        $hase_venue->save();

        Session::flash('type', 'success');
        Session::flash('msg', 'Venue Successfully Inserted');

        if ($request->submitBtn === "Save") {
           return redirect('hase_venue/'. $hase_venue->venue_id . '/edit');
        }else{
           return redirect('hase_venue');
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

        if($this->permissionDetails('Hase_venue','manage')) {
            $title = 'Edit - hase_venue';
            $hase_venue = Venue::
                        select('venue.venue_id','venue.venue_name','postal.postal_premise','postal.postal_subpremise','postal.postal_street','postal.postal_street_number','postal.postal_route','postal.postal_neighborhood','postal.postal_postcode','postal.postal_lat','postal.postal_lng','location_city.city_name','location_state.state_name','location_county.county_name','location_country.country_name','postal.postal_city')
                        ->leftjoin('postal','venue.postal_id','=','postal.postal_id')
                        ->leftjoin('location_city','postal.postal_city','=','location_city.city_id')
                        ->leftjoin('location_state','postal.postal_state','=','location_state.state_id')
                        ->leftjoin('location_county','postal.postal_county','=','location_county.county_id')
                        ->leftjoin('location_country','postal.postal_country','=','location_country.country_id')
                        ->where('venue.venue_id',$id)
                        ->get()->first();

            $hase_cities = City::all();
            return view('hase_venue.edit',compact('title','hase_cities','hase_venue'));
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
        $hase_venue = Venue::findOrfail($id);

        if(isset($hase_venue->postal_id) && !empty($hase_venue->postal_id)){

            $hase_postal = Postal::findOrfail($hase_venue->postal_id);
        }else{
            
            $hase_postal = new Postal();
        }        

        $hase_venue->venue_name = $request->venue_name;

        $hase_postal->postal_city = $request->venue_city_id;

        if(isset($request->venue_city_id))
        {
            $hase_city = City::where('city_id','=',$request->venue_city_id)->get()->first();
            $hase_postal->postal_county = $hase_city->county_id;
            $hase_postal->postal_state = $hase_city->state_id;
            $hase_postal->postal_country = $hase_city->country_id;
        }

        $hase_postal->postal_route = $request->venue_address1;

        $hase_postal->postal_premise = $request->venue_address2;

        $hase_postal->postal_lat = $request->venue_gps_lat;

        $hase_postal->postal_lng = $request->venue_gps_lng;

        $hase_postal->save();

        $hase_venue->postal_id = $hase_postal->postal_id;
        $hase_venue->save();

        Session::flash('type', 'success');
        Session::flash('msg', 'Venue Successfully Updated');

        if ($request->submitBtn === "Save") {
           return redirect('hase_venue/'. $hase_venue->venue_id . '/edit');
        } else {
           return redirect('hase_venue');
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
     	
        if($this->permissionDetails('Hase_venue','delete')) {
            $hase_venue = Venue::findOrfail($id);
            $hase_venue->delete();
            Session::flash('type', 'success'); 
            Session::flash('msg', 'Venue Successfully Deleted');
            return redirect('hase_venue');
        } else {
            return Redirect::back()->with('message', 'You are not authorized to use this functionality!');
        }
    }
}
