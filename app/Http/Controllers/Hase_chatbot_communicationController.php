<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\App;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Chatbot_communication;
use App\Http\Traits\PermissionTrait;
use URL;
use DB;
use Auth;
use Session;
use Carbon\Carbon;
use Redirect;

/**
 * Class Hase_chatbot_communicationController.
 *
 * @author  The scaffold-interface created at 2017-04-08 01:17:22pm
 * @link  https://github.com/amranidev/scaffold-interface
 */
class Hase_chatbot_communicationController extends Controller
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
        $title = 'Index - hase_chatbot_communication';
        $hase_chatbot_communications = Chatbot_communication::all();
        return view('hase_chatbot_communication.index',compact('hase_chatbot_communications','title'));
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return  \Illuminate\Http\Response
     */
    public function create()
    {
        $title = 'Create - hase_chatbot_communication';

        $Hase_chatbot_communication=DB::table('communication_topic')->get();
        
        return view('hase_chatbot_communication.create',compact('title','Hase_chatbot_communication'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param    \Illuminate\Http\Request  $request
     * @return  \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $hase_chatbot_communication = new Chatbot_communication();

        
        $hase_chatbot_communication->communication_id = $request->communication_id;

        
      // $hase_chatbot_communication->communications_vendor_id = $request->communications_vendor_id;

        
        $hase_chatbot_communication->communication_topic_id = $request->communication_topic_id;

        $hase_chatbot_communication->communication_opcode = $request->communication_opcode;
        
        $hase_chatbot_communication->communication_text = $request->communication_text;

        $hase_chatbot_communication->language_id = 1;
        
        $hase_chatbot_communication->save();

        $pusher = App::make('pusher');
        Session::flash('type', 'success'); 
        Session::flash('msg', 'Chatbot Opcode Successfully Inserted');
        //default pusher notification.
        //by default channel=test-channel,event=test-event
        //Here is a pusher notification example when you create a new resource in storage.
        //you can modify anything you want or use it wherever.
        $pusher->trigger('test-channel',
                         'test-event',
                        ['message' => 'A new Chatbot_communication has been created !!']);

        return redirect('hase_chatbot_communication');
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
        $title = 'Show - hase_chatbot_communication';

        if($request->ajax())
        {
            return URL::to('hase_chatbot_communication/'.$id);
        }

        $hase_chatbot_communication = Chatbot_communication::findOrfail($id);
        return view('hase_chatbot_communication.show',compact('title','hase_chatbot_communication'));
    }

    /**
     * Show the form for editing the specified resource.
     * @param    \Illuminate\Http\Request  $request
     * @param    int  $id
     * @return  \Illuminate\Http\Response
     */
    public function edit($id,Request $request)
    {
    	$title = 'Edit - hase_chatbot_communication';
    	if($request->ajax())
    	{
    		return URL::to('hase_chatbot_communication/'. $id . '/edit');
    	}
    	
    	
    	$hase_chatbot_communication = Chatbot_communication::findOrfail($id);
    	$Hase_chatbot_communications_topic=DB::table('communication_topic')->get();
    	
    	return view('hase_chatbot_communication.edit',compact('title','hase_chatbot_communication','Hase_chatbot_communications_topic'));
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
        $hase_chatbot_communication = Chatbot_communication::findOrfail($id);
        
        //$hase_chatbot_communication->communication_id = $request->communication_id;
        
        //$hase_chatbot_communication->communications_vendor_id = $request->communications_vendor_id;
        
        $hase_chatbot_communication->communication_topic_id = $request->communication_topic_id;
        
        $hase_chatbot_communication->communication_opcode = $request->communication_opcode;
        
        $hase_chatbot_communication->communication_text = $request->communication_text;
        
        
        $hase_chatbot_communication->save();

        return redirect('hase_chatbot_communication');
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
        $msg = Ajaxis::BtDeleting('Warning!!','Would you like to remove This?','/hase_chatbot_communication/'. $id . '/delete');

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
        $hase_chatbot_communication = Chatbot_communication::findOrfail($id);
        $hase_chatbot_communication->delete();
        return redirect('hase_chatbot_communication');
    
    }
}
