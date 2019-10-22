<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\App;
use Illuminate\Http\Request;
use App\Http\Controllers\PermissionsController;
use App\Helpers\ConnectionManager;
use App\Http\Traits\PermissionTrait;

use App\Venue;
use App\City;
use App\Postal;
use App\Location_list;

use App\Identity_venue;
use App\Identity_postal;

use URL;
use Session;
use DB;
use Redirect;
use Auth;

CONST GOOGLE_API_KEY = "AIzaSyB0rfVC02005ehz3RV7cwQfKm4qwBvzghE";

CONST IDENTITY_VENUE_TABLE_TYPE	= 57;
CONST VENUE_TABLE_TYPE			= 58;
CONST VENUE_IDENTITY_TYPE 		= 21;

CONST IDENTITY_POSTAL_TABLE_TYPE= 30;
CONST POSTAL_TABLE_TYPE 		= 29;
CONST POSTAL_IDENTITY_TYPE 		= 14;
/**
 * Class VenueController.
 *
 * @author  The scaffold-interface created at 2017-05-24 04:20:12pm
 * @link  https://github.com/amranidev/scaffold-interface
 */
class VenueController extends PermissionsController
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
		return view('hase_venue.index');
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

		if(!empty($hase_venue->postal_id)){

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

	public function getVenues(Request $request)
	{
		$hase_venues = Venue::
			select(
				'venue.venue_id',
				
				'identity_venue.identity_name as venue_name',
				'identity_venue.identity_code as venue_code',

				'postal.postal_premise',
				'postal.postal_subpremise',
				'postal.postal_street_number',
				'postal.postal_route',
				'postal.postal_neighborhood',
				'postal.postal_postcode',
				'postal.postal_lat',
				'postal.postal_lng',
			
				'location_city.city_name',
				'location_county.county_name',
				'location_state.state_name',
				'location_country.country_name'
			)
			->join('identity_venue','identity_venue.identity_id','venue.identity_id')

			->join('postal','venue.postal_id','postal.postal_id')
			->join('identity_postal','identity_postal.identity_id','postal.identity_id')

			->join('location_city','postal.postal_city','location_city.city_id')
			->join('location_state','postal.postal_state','location_state.state_id')
			->join('location_county','postal.postal_county','location_county.county_id')
			->join('location_country','postal.postal_country','location_country.country_id')
			->offset($request->skip)
            ->limit($request->take)
			->get();

		$total_records = Venue::
			join('identity_venue','identity_venue.identity_id','venue.identity_id')

			->join('postal','venue.postal_id','postal.postal_id')
			->join('identity_postal','identity_postal.identity_id','postal.identity_id')

			->join('location_city','postal.postal_city','location_city.city_id')
			->join('location_state','postal.postal_state','location_state.state_id')
			->join('location_county','postal.postal_county','location_county.county_id')
			->join('location_country','postal.postal_country','location_country.country_id')
			->count();	

		$hase_venues_data['hase_venues'] = $hase_venues;
		$hase_venues_data['total'] = $total_records;
		
		return json_encode($hase_venues_data);
	}

	public function getVenue(Request $request)
	{
		$hase_venue = Venue::
			select(
				'venue.venue_id',
				'venue.postal_id',
				'venue.identity_id',
				
				'identity_venue.identity_name as venue_name',
				'identity_venue.identity_code as venue_code',

				'postal.postal_premise',
				'postal.postal_subpremise',
				'postal.postal_street_number',
				'postal.postal_route',
				'postal.postal_neighborhood',
				'postal.postal_postcode',
				'postal.postal_lat',
				'postal.postal_lng',
				'location_city.city_id',
				'location_state.state_id',
				'location_county.county_id',
				'location_country.country_id',
			
				'location_city.city_name',
				'location_county.county_name',
				'location_state.state_name',
				'location_country.country_name'
			)
			->join('identity_venue','identity_venue.identity_id','venue.identity_id')

			->join('postal','venue.postal_id','postal.postal_id')
			->join('identity_postal','identity_postal.identity_id','postal.identity_id')

			->join('location_city','postal.postal_city','location_city.city_id')
			->join('location_state','postal.postal_state','location_state.state_id')
			->join('location_county','postal.postal_county','location_county.county_id')
			->join('location_country','postal.postal_country','location_country.country_id')
			->where('venue.venue_id',$request->venue_id)
			->first();

		return json_encode($hase_venue);
	}

	public function getPostalAddress(Request $request)
	{
		
		$postalData = $this->getCoordinates($request);
		
		$jsonArray = array(
			"street_number" => array(),
			"route"			=> array(),
			"neighborhood"	=> array(),
			"postal_code"	=> array()
		);

		if($postalData && count($postalData->results)){

			$addresses = $postalData->results[0]->address_components;
			$geometry = $postalData->results[0]->geometry;

			if(count($addresses)){
				foreach ($addresses as $key => $address) {

					if(isset($address->types)){

						(in_array("street_number", $address->types))?
							$jsonArray['street_number'][] = $address->long_name : '';

						(in_array("route", $address->types))?
							$jsonArray['route'][] = $address->long_name : '';
						
						if(in_array("neighborhood", $address->types)){
							$jsonArray['neighborhood'][] = $address->long_name;
							$cityName = $address->long_name;
						}
						
						in_array("postal_code", $address->types)?
							$jsonArray['postal_code'][] = $address->long_name : '';
					}
				}
			}
			$jsonArray['lat'] = isset($geometry->location->lat)?
					$geometry->location->lat : 0;

			$jsonArray['lng'] = isset($geometry->location->lng)?
					$geometry->location->lng : 0;

			if(isset($cityName)){
				if(!is_null($this->getCityByName($cityName))){
					$jsonArray['cityInfo'] = $this->getCityByName($cityName)->toArray();
				}else{
					$jsonArray['cityInfo'] = Array();
				}
			}

			return json_encode($jsonArray);
		}else{
			
			return json_encode($jsonArray);
		}
	}

	public function updateLocation(Request $request)
	{
		/*echo "<pre>";
		print_r($request->postals);
		die;*/

		foreach($request->postals as $key => $postal) {

			DB::beginTransaction();

			try {

				if(isset($postal['postal_id'])){
					
					$postalObj = Postal::findOrfail($postal['postal_id']);

				}else{

					$identity_postal = new Identity_postal;
					$identity_postal->identity_type_id = POSTAL_IDENTITY_TYPE;
					$identity_postal->save();

					$postalObj = new Postal;
					$postalObj->identity_id = $identity_postal->identity_id;
				}

				$postalObj->postal_premise = $postal['premise'];
				$postalObj->postal_subpremise = $postal['subpremise'];

				$postalObj->postal_street_number =	($postal['street_number'] !== "None")?
								$postal['street_number'] : 0 ;

				$postalObj->postal_route =	($postal['route'] !== "None")?
								$postal['route'] : '' ;

				$postalObj->postal_neighborhood =	($postal['neighborhood'] !== "None")?
								$postal['neighborhood'] : '' ;

				$postalObj->postal_postcode =	($postal['neighborhood'] !== "None")?
								$postal['neighborhood'] : 0 ;

				$postalObj->postal_country 	= 	$postal['country'];
				$postalObj->postal_state 	=	$postal['state'];
				$postalObj->postal_county 	=	$postal['county'];
				$postalObj->postal_city 	=	$postal['city'];

				$postalObj->postal_lat 		=	$postal['lat'];
				$postalObj->postal_lng 		=	$postal['lng'];

				$postalObj->save();

				if(isset($postal['identity_id'])){

					/*Identity Venue*/
					$identity_venue = Identity_venue::findOrfail($postal['identity_id']);
					$identity_venue->identity_name = $postal['venue_name'];
					$identity_venue->identity_type_id = VENUE_TABLE_TYPE;
					$identity_venue->save();

				}else{

					/*Identity Venue*/
					$identity_venue = new Identity_venue;
					$identity_venue->identity_name = $postal['venue_name'];
					$identity_venue->identity_type_id = VENUE_TABLE_TYPE;
					$identity_venue->save();

					/*Venue*/
					$venue = new Venue;
					$venue->identity_id = $identity_venue->identity_id;
					$venue->identity_table_id = IDENTITY_VENUE_TABLE_TYPE;
					$venue->postal_id = $postalObj->postal_id;
					$venue->save();
				}
				
				
				DB::commit();	
			} catch (Exception $e) {
				DB::rollback();
			}
		}
		return 1;
	}

	public function updateVenue(Request $request)
	{
		$key = $request->key;
		$value = $request->value;
		$venue = Venue::findOrfail($request->venue_id);
		if($venue){
			$identity_venue = Identity_venue::findOrFail($venue->identity_id);
			$identity_venue->$key = $value;
			$identity_venue->save();
		}
	}

	public function getCoordinates($request) {
		
        $addressInfo = urldecode($request->postal_subpremise).",".
        			   urldecode($request->postal_premise).",".
        			   urldecode($request->city_name);

        $locationData = urlencode($addressInfo);

        $coordinateUrl = 'https://maps.googleapis.com/maps/api/geocode/json?region=hk&address=' . $locationData . '&key=' . GOOGLE_API_KEY;

		$curl = curl_init();

		curl_setopt_array($curl, array(
			CURLOPT_URL => $coordinateUrl,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_TIMEOUT => 30000,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "GET",
			CURLOPT_HTTPHEADER => array(
				'Content-Type: application/json',
			),
		));
		$response = curl_exec($curl);
		$err = curl_error($curl);
		curl_close($curl);

		return (!$err)? json_decode($response) : false;
    }

    public static function getCityByName($cityName)
	{
		return City::
			select('location_city.*')
			->where('city_name',$cityName)
            ->get()->first();
	}

	public function getCountries()
	{
		$countries = PermissionTrait::getCountries();
		return json_encode($countries);
	}

	public function getStates(Request $request)
	{
		if(!empty($request->query('id'))){
			$states = PermissionTrait::getStates($request->query('id'));
		}else{
			$states = PermissionTrait::getStates();
		}
		return json_encode($states);
	}

	public function getCounties(Request $request)
	{
		if(!empty($request->query('id'))){
			$counties = PermissionTrait::getCounties($request->query('id'));
		}else{
			$counties = PermissionTrait::getCounties();
		}
		return json_encode($counties);
	}

	public function getCities(Request $request)
	{
		if(!empty($request->query('id'))){
			$cities = PermissionTrait::getCities($request->query('id'));
		}else{
			$cities = PermissionTrait::getCities();
		}
		return json_encode($cities);
	}
}
