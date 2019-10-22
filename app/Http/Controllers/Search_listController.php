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
use App\Keyword_category;
use App\Keyword;
use App\Keyword_list;
use App\Search;


class Search_listController extends Controller
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
        $this->userId = session()->has('userId') ? session()->get('userId') :"";
        return $next($request);
        });
    }

    public function searchUrl(Request $request){
        if($this->permissionDetails('Search_list','access')){
            return view('search_engine.search_url_list',compact('title'));
        }else{
            return Redirect::back()->with('message', 'You are not authorized to use this functionality!');
        }
    }

    public function searchUrlList(Request $request) {
        $searchUrlList=Search::queryDBSearchUrlList();
        return json_encode($searchUrlList);
    }

    public function createSearchUrl(Request $request) {
        $searchUrl=$request->search_url;
        $searchPriority=$request->search_priority;
        Search::insertDBSearchUrl($searchUrl,$searchPriority);
        return 1;
    }

    public function updateSearchUrl(Request $request) {
        $searchId=$request->serarch_id;
        $searchUrl=$request->search_url;
        $searchPriority=$request->search_priority;
        Search::updateDBSearchUrl($searchId, $searchUrl, $searchPriority);
        return 1;
    }

    public function deleteSearchUrl(Request $request) {
        $searchId=$request->search_id;
        Search::deleteDBSearchUrl($searchId);
        return 1;
    }
}
