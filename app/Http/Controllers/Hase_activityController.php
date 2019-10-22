<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\App;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Activity;
use App\Staff;
use App\Http\Traits\PermissionTrait;
use URL;
use Redirect;

/**
 * Class Hase_activityController.
 *
 * @author  The scaffold-interface created at 2017-03-19 06:03:27am
 * @link  https://github.com/amranidev/scaffold-interface
 */
class Hase_activityController extends Controller
{
    use PermissionTrait;

    private $merchantId;

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            $this->merchantId = session()->has('merchantId') ? session()->get('merchantId') :"";
            $this->roleId = session()->has('role') ? session()->get('role') :"";
            $this->locationId = session()->has('locationId') ? session()->get('locationId') :"";
            $this->staffId = session()->has('staffId') ? session()->get('staffId') :"";

            if(!$this->issetHashPassword()){
                Redirect::to('hase_staff/'. $this->staffId . '/edit')->with('message', 'You must have to change password !')->send();
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
        $title = 'Index - hase_activity';
        
        switch ($this->roleId) {
            case 1:
                $hase_activities = Activity::orderBy('date_added','desc')->get();  
                break;
            case 3:
                $hase_activities = Activity::
                            orderBy('date_added','desc')
                            ->where('user_id','!=',1)
                            ->get();
                break;
            case 4:
                $hase_activities = Activity::
                            orderBy('date_added','desc')
                            ->where('merchant_id',$this->merchantId)
                            ->get();
                break;
            case 5:
                $hase_staffs = Staff::
                        where('staff_location_id',$this->locationId)
                        ->where('merchant_id',$this->merchantId)
                        ->where('staff_group_id','!=',4)
                        ->select('staff_id')
                        ->get();

                $staffIds = array();
                foreach ($hase_staffs as $key => $value) {
                    $staffIds[] = $value->staff_id;
                }
                $hase_activities = Activity::
                            orderBy('date_added','desc')
                            ->whereIn('user_id',$staffIds)
                            ->get();
                break;
            case 2:
            case 6:
                $hase_activities = Activity::
                            orderBy('date_added','desc')
                            ->where('user_id',$this->staffId)
                            ->get();
                break;
        }

        return view('hase_activity.index',compact('hase_activities','title'));
    }

    public function updateActivityStatus(){
        
        $hase_activities = Activity::
                where('status','=',1)
                ->update(array('status' => 0));
    }
}
