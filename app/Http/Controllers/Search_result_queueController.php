<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\App;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Search_result_queue;
use Amranidev\Ajaxis\Ajaxis;
use URL;
use DateTime;
use Carbon\Carbon;
use App\Http\Traits\PermissionTrait;
use DB;
use Auth;
use Session;
use Redirect;

/**
 * Class Search_result_queueController.
 *
 * @author  The scaffold-interface created at 2018-03-07 01:10:35pm
 * @link  https://github.com/amranidev/scaffold-interface
 */
class Search_result_queueController extends Controller
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
    public function eventQueueStatusUpdate(Request $request){
        $queue_status=$request->queue_status;
        $queueStatusUpdate=$request->queueStatusUpdate;
        if($queue_status == 'active'){
            if($queueStatusUpdate[0] != ''){
                foreach ($queueStatusUpdate as $queueStatusUpdateKey=>$queueStatusUpdateValue) {
                        $eventQueueList=DB::table('search_result_queue')->where('id','=',$queueStatusUpdateValue)->first();
                        $createdAt =Carbon::now();
                        $start_Date = str_replace('-', '', $createdAt->toDateString());
                        $requestTimeFormat = $createdAt->toTimeString();
                        $openTimeData = explode(":", $requestTimeFormat);
                        $start_time = $openTimeData[0]*3600+$openTimeData[1]*60;
                            if(isset($eventQueueList)){
                                 $eventScrapeList=DB::table('search_result_scrape')->where('event_url','=',$eventQueueList->event_url)->first();
                                if(!isset($eventScrapeList)){
                                    DB::table('search_result_scrape')->insert(array('event_url'=>$eventQueueList->event_url,'user_id'=>$this->userId,'website_id'=>$eventQueueList->website_id,'modify_date'=>$start_Date,'modify_time'=>$start_time));
                                }
                                DB::table('search_result_queue')->where('id','=',$eventQueueList->id)->delete();
                            }
                    }
            }

        }
    }
    public function queueEventDetailsView($website_id){
        $eventQueueLists=Search_result_queue::leftjoin('regex_field as regex_field','regex_field.field_id','=','search_result_queue.keyword_list_id')->where('website_id','=',$website_id)->select('search_result_queue.*','regex_field.field_name as keyword_label')->get();
         return view('search_result_queue.event_queue_details',compact('eventQueueLists','website_id','title'));

    }
    public function eventUrlUpdate(Request $request){
                $event_title=$request->event_url_title;
                $website_id=$request->website_id;
                $event_url=$request->event_url;
                $start_Date = date('Ymd');
                $start_time = time();
                $keyword_label_list=DB::table('regex_field')->where('field_name','=',$event_title)->first();
                    if(!isset($keyword_label_list)){
                    $keyword_id = DB::table('regex_field')->insertGetId(
                        ['field_name'=>$event_title]);
                    }else{
                        $keyword_id=$keyword_label_list->keyword_id;
                    }
                DB::table('search_result_queue')->insert(['keyword_list_id'=>$keyword_id,'user_id'=>$this->userId,'event_url'=>$event_url,'entry_date'=>$start_Date,'entry_time'=>$start_time,'website_id'=>$website_id
                        ]);
    }
    public function eventDetailsUpdate(Request $request){
        $queue_status=$request->queue_status;
        $website_id=$request->website_id;
        $pagination_template=$request->pagination_template;
        if($pagination_template != ''){
         DB::table('search_result_queue')->where('website_id','=',$website_id)->update(array('pagination_template'=>$pagination_template));
        }
        if($queue_status == 'active'){
            $eventQueueList=DB::table('search_result_queue')->where('website_id','=',$website_id)->get();
            if($eventQueueList[0] != ''){
                foreach ($eventQueueList as $eventQueueListValue) {
                                $start_Date = date('Ymd');
                                $start_time = time();
                                DB::table('search_result_scrape')->insert(array('event_url'=>$eventQueueListValue->event_url,'user_id'=>$this->userId,'website_id'=>$eventQueueListValue->website_id,'modify_date'=>$start_Date,'modify_time'=>$start_time,'pagination_template'=>$eventQueueListValue->pagination_template));
                                DB::table('search_result_queue')->where('id','=',$eventQueueListValue->id)->delete();
                    }
            }

        }

    }
    /**
     * Display a listing of the resource.
     *
     * @return  \Illuminate\Http\Response
     */
    public function index()
    {
        if($this->permissionDetails('Search_result_queue','access')){
            $title = 'Index - search_result_queue';
            $search_result_queues = Search_result_queue::join('portal_password as portal_password','portal_password.user_id','=','search_result_queue.user_id')->join('website_domain as website_domain','website_domain.website_domain_id','=','search_result_queue.website_id')->select('search_result_queue.*','portal_password.username as username','website_domain.website_url as website_url')->groupBy('website_id')->get();
            return view('search_result_queue.index',compact('search_result_queues','title'));
        }else{
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
        $title = 'Create - search_result_queue';
        
        return view('search_result_queue.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param    \Illuminate\Http\Request  $request
     * @return  \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $search_result_queue = new Search_result_queue();

        
        //$search_result_queue->id = $request->id;

        
        //$search_result_queue->event_title = $request->event_title;

        
        //$search_result_queue->keyword_list_id = $request->keyword_list_id;

        
        //$search_result_queue->status_id = $request->status_id;

        
        $search_result_queue->user_id = $this->userId;

        
        $search_result_queue->event_url = $request->event_url;

        $start_Date = date('Ymd');
        $start_time = time();
        
        $search_result_queue->entry_date = $start_Date;        
        $search_result_queue->entry_time = $start_time;
        $website_url=preg_replace('#http://|https://|www.#', '', $request->event_url);

        $website_id_list=DB::table('website_domain')->where('website_url','=',$website_url)->first();
        if(!isset($website_id_list)){
        $website_id = DB::table('website_domain')->insertGetId(
            ['website_url'=>$website_url]);
        }else{
            $website_id=$website_id_list->website_domain_id;
        } 
        $search_result_queue->website_id = $website_id;

        $search_result_queue->save();

        $pusher = App::make('pusher');

        //default pusher notification.
        //by default channel=test-channel,event=test-event
        //Here is a pusher notification example when you create a new resource in storage.
        //you can modify anything you want or use it wherever.
        $pusher->trigger('test-channel',
                         'test-event',
                        ['message' => 'A new search_result_queue has been created !!']);

        return redirect('search_result_queue');
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
        $title = 'Show - search_result_queue';

        if($request->ajax())
        {
            return URL::to('search_result_queue/'.$id);
        }

        $search_result_queue = Search_result_queue::findOrfail($id);
        return view('search_result_queue.show',compact('title','search_result_queue'));
    }

    /**
     * Show the form for editing the specified resource.
     * @param    \Illuminate\Http\Request  $request
     * @param    int  $id
     * @return  \Illuminate\Http\Response
     */
    public function edit($id,Request $request)
    {
        $title = 'Edit - search_result_queue';
        if($request->ajax())
        {
            return URL::to('search_result_queue/'. $id . '/edit');
        }

        
        $search_result_queue = Search_result_queue::findOrfail($id);
        return view('search_result_queue.edit',compact('title','search_result_queue'  ));
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
        $search_result_queue = Search_result_queue::findOrfail($id);
    	
        //$search_result_queue->id = $request->id;
        
        //$search_result_queue->event_title = $request->event_title;
        
        //$search_result_queue->keyword_list_id = $request->keyword_list_id;
        
        //$search_result_queue->status_id = $request->status_id;
        
        $search_result_queue->user_id = $this->userId;
        
        $search_result_queue->event_url = $request->event_url;
        $start_Date = date('Ymd');
        $start_time = time();
        
        $search_result_queue->entry_date = $start_Date;
        
        $search_result_queue->entry_time = $start_time;
        
        //$search_result_queue->website_id = $request->website_id;
        
        
        $search_result_queue->save();

        return redirect('search_result_queue');
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
        $msg = Ajaxis::MtDeleting('Warning!!','Would you like to remove This?','/search_result_queue/'. $id . '/delete');

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
     	$search_result_queue = Search_result_queue::findOrfail($id);
     	$search_result_queue->delete();
        return redirect('search_result_queue');
    }
}
