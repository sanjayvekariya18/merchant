<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\App;
use Illuminate\Http\Request;
use App\Http\Controllers\PermissionsController;
use App\Helpers\ConnectionManager;
use App\Order;
use App\Statuses;
use App\Location;
use App\Address;
use App\Staff;
use App\Order_menu;
use App\Status_history;
use App\Http\Traits\PermissionTrait;
use URL;
use DB;
use Auth;
use Session;
use Redirect;
/**
 * Class Hase_orderController.
 *
 * @author  The scaffold-interface created at 2017-03-08 06:54:55am
 * @link  https://github.com/amranidev/scaffold-interface
 */
class Hase_orderController extends PermissionsController
{
    use PermissionTrait;

    public function __construct()
    {
        parent::__construct();

        $connectionStatus = ConnectionManager::setDbConfig('Hase_order');

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
        if($this->permissionDetails('Hase_order','access')){
            $title = 'Index - hase_order';

            if($this->merchantId === 0){
                $hase_orders = Order::
                                select('orders.*','location_identity.identity_name as location_name','statuses.status_name')
                                ->leftjoin('identity as location_identity','location.identity_id','=','location_identity.identity_id')
                                ->leftjoin('statuses','orders.status_id','=','statuses.status_id')
                                ->leftjoin('location','orders.location_id','=','location.location_id')
                                ->get();

            }else{

                $hase_orders = Order::
                                select('orders.*','location_identity.identity_name as location_name','statuses.status_name')
                                ->leftjoin('identity as location_identity','location.identity_id','=','location_identity.identity_id')
                                ->leftjoin('statuses','orders.status_id','=','statuses.status_id')
                                ->leftjoin('location','orders.location_id','=','location.location_id')
                                ->where('orders.merchant_id',"=",$this->merchantId)
                                ->get();
            }            

            return view('hase_order.index',compact('hase_orders','title'));
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
        if($this->permissionDetails('Hase_order','add')){
            $title = 'Create - hase_order';
        
            return view('hase_order.create');
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
        $hase_order = new Order();

        $hase_order->order_id = $request->order_id;

        
        $hase_order->merchant_id = $request->merchant_id;

        
        $hase_order->customer_id = $request->customer_id;

        
        $hase_order->first_name = $request->first_name;

        
        $hase_order->last_name = $request->last_name;

        
        $hase_order->email = $request->email;

        
        $hase_order->telephone = $request->telephone;

        
        $hase_order->location_id = $request->location_id;

        
        $hase_order->address_id = $request->address_id;

        
        $hase_order->cart = $request->cart;

        
        $hase_order->total_items = $request->total_items;

        
        $hase_order->comment = $request->comment;

        
        $hase_order->payment = $request->payment;

        
        $hase_order->order_type = $request->order_type;

        
        $hase_order->date_added = $request->date_added;

        
        $hase_order->date_modified = $request->date_modified;

        
        $hase_order->order_time = $request->order_time;

        
        $hase_order->order_date = $request->order_date;

        
        $hase_order->order_total = $request->order_total;

        
        $hase_order->status_id = $request->status_id;

        
        $hase_order->ip_address = $request->ip_address;

        
        $hase_order->user_agent = $request->user_agent;

        
        $hase_order->notify = $request->notify;

        
        $hase_order->assignee_id = $request->assignee_id;

        
        $hase_order->invoice_no = $request->invoice_no;

        
        $hase_order->invoice_prefix = $request->invoice_prefix;

        
        $hase_order->invoice_date = $request->invoice_date;

        
        
        $hase_order->save();

        Session::flash('type', 'success'); 
        Session::flash('msg', 'Order Successfully Inserted'); 

        if ($request->submitBtn === "Save") {
            return redirect('hase_order/'. $hase_order->order_id . '/edit');
        }else{
            return redirect('hase_order');
        }
        
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
        $title = 'Show - hase_order';

        if($request->ajax())
        {
            return URL::to('hase_order/'.$id);
        }

        $hase_order = Order::findOrfail($id);
        return view('hase_order.show',compact('title','hase_order'));
    }

    /**
     * Show the form for editing the specified resource.
     * @param    \Illuminate\Http\Request  $request
     * @param    int  $id
     * @return  \Illuminate\Http\Response
     */
    public function edit($id,Request $request)
    {
        if($this->permissionDetails('Hase_order','manage')){
            $title = 'Edit - hase_order';
            if($this->merchantId === 0){

                $hase_order = Order::findOrfail($id);

            }else{

                $hase_order = Order::
                    where('order_id',$id)
                    ->where('merchant_id',$this->merchantId)
                    ->get()->first();
            }
            
            if(count($hase_order)){

                $hase_order_menu = Order_menu::where('order_id', '=', $id)->get();

                $hase_locations = Location:: 
                        select('location.*','location_identity.identity_name as location_name','location_identity.identity_description as description','location_identity.identity_logo as location_image','location_identity.identity_email as location_email','location_identity.identity_telephone as location_telephone','location_identity.identity_website as location_website','postal.postal_premise','postal.postal_subpremise','postal.postal_street','postal.postal_street_number','postal.postal_route','postal.postal_neighborhood','postal.postal_postcode','postal.postal_lat','postal.postal_lng','location_city.city_name','location_state.state_name','location_county.county_name','location_country.country_name')
                        ->leftjoin('identity as location_identity','location.identity_id','=','location_identity.identity_id')
                        ->leftjoin('postal','location.postal_id','=','postal.postal_id')
                        ->leftjoin('location_city','postal.postal_city','=','location_city.city_id')
                        ->leftjoin('location_state','postal.postal_state','=','location_state.state_id')
                        ->leftjoin('location_county','postal.postal_county','=','location_county.county_id')
                        ->leftjoin('location_country','postal.postal_country','=','location_country.country_id')
                        ->where('location.location_id',$hase_order->location_id)
                        ->get()->first();
                
                $address_data = Address::
                        where('address_id', $hase_order->address_id)
                        ->where('customer_id', $hase_order->customer_id)->get()->first();

                $hase_statuses = Statuses::where('status_for', '=', 'order')->get(); 

                if($this->merchantId === 0){
                    $hase_staffs = Staff::all();
                }else{
                    $hase_staffs = Staff::where('merchant_id','=',$this->merchantId)
                                                ->get();
                }
                
                $hase_status_history = Status_history::
                       leftjoin('staffs','status_history.staff_id','=','staffs.staff_id') 
                        ->leftjoin('statuses','status_history.status_id','=','statuses.status_id')
                        ->leftjoin('staffs as assignee','status_history.assignee_id','=','assignee.staff_id')
                        ->select('status_history.*','staffs.staff_name','assignee.staff_name as assignee_name','statuses.status_name')
                        ->where('status_history.object_id', $id)
                        ->orderby('status_history_id','DESC')
                        ->get();


                return view('hase_order.edit',compact('title','hase_order','hase_locations','address_data','hase_statuses','hase_staffs','hase_status_history','hase_order_menu'));
            }else{
                return Redirect::back()->with('message', 'You are not authorized to use this functionality!');
            }
        }else{
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

        /*echo "<pre>";
        print_r($request->toArray());
        die;*/
        $hase_order = Order::findOrfail($id);
                    
        $hase_order->status_id = $request->status;
        $hase_order->notify = isset($request->notify) ? 1 : 0;
        $hase_order->assignee_id = $request->assignee_id;

        $hase_status_history = new Status_history;

        $hase_status_history->object_id = $request->order_id;
        $hase_status_history->assignee_id = $request->assignee_id;
        $hase_status_history->staff_id = Auth::user()->staff_id;
        $hase_status_history->status_id = $request->status;
        $hase_status_history->notify = isset($request->notify) ? 1 : 0;
        $hase_status_history->status_for = "order";
        $hase_status_history->comment = $request->status_comment;
        $hase_status_history->date_added = date('Y-m-d h:i:s');

        $hase_order->save();
        $hase_status_history->save();

        Session::flash('type', 'success'); 
        Session::flash('msg', 'Order Successfully Updated'); 

        if ($request->submitBtn === "Save") {
            return redirect('hase_order/'. $hase_order->order_id . '/edit');
        }else{
            return redirect('hase_order');
        }

        return redirect('hase_order');
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
        $msg = Ajaxis::BtDeleting('Warning!!','Would you like to remove This?','/hase_order/'. $id . '/delete');

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
        if($this->permissionDetails('Hase_order','delete')){
            $hase_order = Order::findOrfail($id);
            $hase_order->delete();
            return redirect('hase_order');
        }else{
            return Redirect::back()->with('message', 'You are not authorized to use this functionality!');
        }	
    }
}
