<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\App;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Traits\PermissionTrait;
use URL;
use Session;
use DB;
use Redirect;


class Event_url_listController extends Controller
{
    use PermissionTrait;

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
        $this->staffId = session()->has('staffId') ? session()->get('staffId') :"";
        $this->staffName = session()->has('staffName') ? session()->get('staffName') :"";
        $this->merchantId = session()->has('merchantId') ? session()->get('merchantId') :"";
        $this->roleId = session()->has('role') ? session()->get('role') :"";
        $this->locationId = session()->has('locationId') ? session()->get('locationId') :"";
        return $next($request);
        });
    }

    public function eventUrlList($status_id=null){
        if($this->permissionDetails('Event_url_list','access')){
            if(isset($status_id)){
                $status_id=$status_id;
            }else{
                $status_id=0;
            }
            $event_url_list = DB::table('event_url_list')->
            join('portal_password as portal_password','portal_password.user_id','=','event_url_list.user_id')
            ->leftjoin('website_domain as website_domain','website_domain.website_domain_id','=','event_url_list.website_id')
            ->leftjoin('regex_field as regex_field','regex_field.field_id','=','event_url_list.keyword_list_id')
            ->leftjoin('statuses as block_status','block_status.status_id','=','event_url_list.action')
            ->select('event_url_list.*','portal_password.username as username','website_domain.website_url as website_url','regex_field.field_name as keyword_label','block_status.status_name as status')
            ->where('result_scrape_id','=',0)
            ->where('action','=',$status_id)->get();
            return view('search_engine.event_url_list',compact('title','event_url_list'));
        }else{
            return Redirect::back()->with('message', 'You are not authorized to use this functionality!');
        }
    }

    public function eventUrlDetailsView($website_id){
        $eventUrlLists=DB::table('event_url_list')->leftjoin('regex_field as regex_field','regex_field.field_id','=','event_url_list.keyword_list_id')->where('website_id','=',$website_id)->select('event_url_list.*','regex_field.field_name as field_name')->get();
         $block_status=DB::table('block_status')->get();
         return view('search_engine.event_url_details',compact('eventUrlLists','website_id','title','block_status'));

    }

}
