<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\App;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Website_domain;
use Amranidev\Ajaxis\Ajaxis;
use URL;
use Carbon\Carbon;
use App\Http\Traits\PermissionTrait;
use DB;
use Auth;
use Session;
use Redirect;

/**
 * Class Website_domainController.
 *
 * @author  The scaffold-interface created at 2018-03-05 02:19:28pm
 * @link  https://github.com/amranidev/scaffold-interface
 */
class Website_domainController extends Controller
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
    /**
     * Display a listing of the resource.
     *
     * @return  \Illuminate\Http\Response
     */
    public function index()
    {
        if($this->permissionDetails('Website_domain_list','access')){
            $title = 'Index - website_domain';
            $website_domains = Website_domain::get();
            return view('website_domain.index',compact('website_domains','title'));
        }else{
            return Redirect::back()->with('message', 'You are not authorized to use this functionality!');
        }
    }
    public function websiteLists(){
         if($this->permissionDetails('Website_domain_list','access')){
            return view('website_domain.website_list',compact('title'));
        }else{
            return Redirect::back()->with('message', 'You are not authorized to use this functionality!');
        }
    }    
    public function websiteDomainLists(Request $request){
        $website_domains = Website_domain::get();
        return json_encode($website_domains);
    }
    public function updateWebsiteDomain(Request $request){
        $website_domain_id=$request->website_domain_id;
        $website_domain = Website_domain::findOrfail($website_domain_id);
        $website_domain->website_url = $request->website_url;
        $website_domain->save();
        return 1;    
    }
    public function deleteWebsiteDomain(Request $request){
        $website_domain_id=$request->website_domain_id;
        $haseWebsiteAceess = $this->permissionDetails('Website_domain_list','delete');
        if($haseWebsiteAceess) {
            DB::table('website_domain')->where('website_domain_id','=',$website_domain_id)->update(array('status'=>0));
            Session::flash('type', 'success'); 
            Session::flash('msg', 'website domain Successfully Deleted');
        }
        return 1;       
    }
    public function createWebsiteDomain(Request $request){
            $website_domain = new Website_domain();
            $website_url = $request->website_url;
            $website_domain->website_url=preg_replace('#http://|https://|www.#', '', $website_url);
            $website_domain->save();
            $eventDate = date('Ymd');
            $eventTime = time();
            DB::table('search_result_queue')->insert(array(
                                'user_id'=>$this->roleId,
                                'event_url'=>$website_url,
                                'website_id'=>$website_domain->website_domain_id,
                                'entry_time'=>$eventTime,
                                'entry_date'=>$eventDate));
            return 1;
        
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return  \Illuminate\Http\Response
     */
    public function create()
    {
        if($this->permissionDetails('Website_domain','add')){
            $title = 'Create - website_domain';
            return view('website_domain.create');
        }else{
            return Redirect::back()->with('message', 'You are not authorized to use this functionality!');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param    \Illuminate\Http\Request  $request
     * @return  \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $website_domain = new Website_domain();

        
       // $website_domain->website_domain_id = $request->website_domain_id;

        
        $website_url = $request->website_url;

        $website_domain->website_url=preg_replace('#http://|https://|www.#', '', $website_url);
        //$website_domain->status = $request->status;

        
        
        $website_domain->save();
        $createdAt =Carbon::now();
        $eventDate = str_replace('-', '', $createdAt->toDateString());
        $requestTimeFormat = $createdAt->toTimeString();
        $openTimeData = explode(":", $requestTimeFormat);
        $eventTime = $openTimeData[0]*3600+$openTimeData[1]*60;
        DB::table('search_result_queue')->insert(array(
                            'user_id'=>$this->roleId,
                            'event_url'=>$website_url,
                            'website_id'=>$website_domain->website_domain_id,
                            'entry_time'=>$eventTime,
                            'entry_date'=>$eventDate));
        $pusher = App::make('pusher');


        $pusher->trigger('test-channel',
                         'test-event',
                        ['message' => 'A new website_domain has been created !!']);

       return redirect('website_domain');
    }

    /**
     * Display the specified resource.
     *
     * @param    \Illuminate\Http\Request  $request
     * @param    int  $id
     * @return  \Illuminate\Http\Response
     */
    public function show($id,Request $request)
    {
        if($this->permissionDetails('Website_domain','manage')){
            $title = 'Show - website_domain';

            if($request->ajax())
            {
                return URL::to('website_domain/'.$id);
            }
            $website_domain = Website_domain::findOrfail($id);
            return view('website_domain.show',compact('title','website_domain'));
        }else{
            return Redirect::back()->with('message', 'You are not authorized to use this functionality!');
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
        $title = 'Edit - website_domain';
        if($request->ajax())
        {
            return URL::to('website_domain/'. $id . '/edit');
        }

        
        $website_domain = Website_domain::findOrfail($id);
        return view('website_domain.edit',compact('title','website_domain'  ));
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
        $website_domain = Website_domain::findOrfail($id);
    	
        //$website_domain->website_domain_id = $request->website_domain_id;
        
        $website_domain->website_url = $request->website_url;
        
        //$website_domain->status = $request->status; 
        
        $website_domain->save();

        return redirect('website_domain');
    }

    /**
     * Delete confirmation message by Ajaxis.
     *
     * @link      https://github.com/amranidev/ajaxis
     * @param    \Illuminate\Http\Request  $request
     * @return  String
     */
    public function DeleteMsg($id,Request $request)
    {
        $msg = Ajaxis::MtDeleting('Warning!!','Would you like to remove This?','/website_domain/'. $id . '/delete');

        if($request->ajax())
        {
            return $msg;
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
        if($this->permissionDetails('Website_domain','delete')){
         	$website_domain = Website_domain::findOrfail($id);
         	$website_domain->delete();
            return redirect('website_domain');
        }else{
            return Redirect::back()->with('message', 'You are not authorized to use this functionality!');
        }
    }
}
