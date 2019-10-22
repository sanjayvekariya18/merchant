<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\App;
use Illuminate\Http\Request;
use App\Http\Controllers\PermissionsController;
use App\Helpers\ConnectionManager;
use App\Http\Traits\PermissionTrait;
use App\Bank;
use App\Identity_bank;
use App\Country;
use App\State;
use App\City;
use URL;
use Session;
use DB;
use Redirect;
use Auth;
/**
 * Class Hase_approval_group_listController.
 *
 * @author  The scaffold-interface created at 2017-04-05 04:16:06pm
 * @link  https://github.com/amranidev/scaffold-interface
 */
class BankController extends PermissionsController
{
    const BANK_TABLE_ID=27;
    const IDENTITY_BANK_TABLE_ID=28;

    use PermissionTrait;
    
    public function __construct()
    {
        parent::__construct();

        $connectionStatus = ConnectionManager::setDbConfig('Bank');

        if ($connectionStatus['type'] === "error") {
            Session::flash('type', $connectionStatus['type']);
            Session::flash('msg', $connectionStatus['message']);
            return Redirect::back()->send();
        }
        
        $this->request_table_live = 'merchant';
        $this->request_table_stage = 'merchant_stage';
    }

    /**
     * Display a listing of the resource.
     *
     * @return  \Illuminate\Http\Response
     */
    public function index()
    {
        if($this->permissionDetails('Bank','access')) {
            
            $defaultCountry = Country::where('country_name','Hong Kong')->first();
            return view('bank.index',compact('defaultCountry'));
        } else {
            return Redirect::back()->with('message', 'You are not authorized to use this functionality!');
        }
    }

    public function store(Request $request)
    {
        $identity_bank = new Identity_bank();
        $identity_bank->identity_table_id = self::BANK_TABLE_ID;
        $identity_bank->identity_name = $request->bank_name;
        $identity_bank->identity_code = $request->bank_name;
        $identity_bank->save();

        $bankIdentity = $identity_bank->identity_id;
        $bank = new Bank();
        $bank->identity_id = $bankIdentity;
        $bank->identity_table_id = self::IDENTITY_BANK_TABLE_ID;
        $bank->clearing_code = $request->clearing_code;
        $bank->local_name = $request->local_name;
        $bank->country_origin = $request->country_origin;
        $bank->swift_bic = $request->swift_bic;
        $bank->save();
    }

    public function getBankList(Request $bankDetails) {
        $bankListDetails = Bank::select('bank.*','identity_bank.identity_name as bank_name','postal.postal_premise as branch_name','location_country.country_id','location_country.country_name')
            ->join('identity_bank','identity_bank.identity_id','bank.identity_id')
            ->leftjoin('location_list','location_list.list_id','bank.location_id')
            ->leftjoin('postal','postal.postal_id','location_list.postal_id')
            ->leftjoin('location_country','bank.country_origin','location_country.country_id');

        $bankList['total'] = Bank::count();
        $bankList['bank_list'] = $bankListDetails->offset($bankDetails->skip)->limit($bankDetails->take)->orderBy('bank_id','DESC')->get();
        return json_encode($bankList);
    }

    public function updateBankLists(Request $request)
    {
        $bank = Bank::findOrfail($request->bank_id);
        $bank->swift_bic = $request->swift_bic;
        $bank->country_origin = $request->country_origin;
        $bank->clearing_code = $request->clearing_code;
        $bank->local_name = $request->local_name;
        $bank->save();

        $identity_bank = Identity_bank::findOrfail($request->identity_id);
        $identity_bank->identity_name = $request->bank_name;
        $identity_bank->identity_code = $request->bank_name;
        $identity_bank->save();
        return 1;
    }

    public function deleteBankLists(Request $request)
    {
        $identity_bank = Identity_bank::findOrfail($request->identity_id);
        $identity_bank->delete();
        $Bank = Bank::findOrfail($request->bank_id);
        $Bank->delete();
        return 1;
    }

    public function getBankCountries()
    {
        $countryListDetails = PermissionTrait::getCountries()->where('country_id','>',0);
        foreach ($countryListDetails as $key => $country) {
            $countryList[] = array(
                'country_id' => $country->country_id,
                'country_name' => $country->country_name
            ); 
        }
        return json_encode($countryList);
    }

    public function getLocationTree()
    {
        $topologyJsonArray = array();

        $countries = Country::where('country_id','>',0)->get();

        foreach ($countries as $keyCountry => $country) {
            $topologyJsonArray[$keyCountry] = array(
                'text'      => $country->country_name,
                'id'        => $country->country_id."_country",
                'parent_id' => 0
            );

            $states = State::
                join('location_city', 'location_state.state_id', '=', 'location_city.state_id')
                ->where('location_city.country_id','=',$country->country_id)
                ->select('location_state.*')
                ->orderBy('location_state.state_name', 'ASC')
                ->groupBy('state_name')
                ->get();

            foreach ($states as $keyState => $state) {
                $topologyJsonArray[$keyCountry]['items'][$keyState] = array(
                    'text'      => $state->state_name,
                    'id'        => $state->state_id."_state",
                    'parent_id' => $country->country_id
                );

                $counties = County::
                    join('location_city', 'location_county.county_id', '=', 'location_city.county_id')
                    ->where('location_city.state_id',$state->state_id)
                    ->select('location_county.*')
                    ->orderBy('location_county.county_name', 'ASC')
                    ->groupBy('county_name')
                    ->get();
                foreach ($counties as $keyCounty => $county) {
                    $topologyJsonArray[$keyCountry]['items'][$keyState]['items'][$keyCounty] = array(
                            'text'      => $county->county_name,
                            'id'        => $county->county_id."_county",
                            'parent_id' => $state->state_id
                        );

                    $cities = City::where('county_id',$county->county_id)->get();
                    foreach ($cities as $keyCity => $city) {
                        $topologyJsonArray[$keyCountry]['items'][$keyState]['items'][$keyCounty]['items'][$keyCity] = array(
                            'text'      => $city->city_name,
                            'id'        => $city->city_id."_city",
                            'parent_id' => $county->county_id
                        );
                    }
                }
            }
        }
        return json_encode(array_values($topologyJsonArray));
    }

    public function getRegions(){
        $regionArray = array();
        $cities=PermissionTrait::getCities()->where('city_id','>',0);
        foreach ($cities as $key => $city) {
            $regionArray[] = array(
                'region_id' => $city->city_id."_city",
                'region_name' => $city->city_name
            ); 
        }
        return json_encode($regionArray);
    }
}
