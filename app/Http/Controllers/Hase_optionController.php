<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\App;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Request as Requests;
use App\Http\Controllers\PermissionsController;
use App\Helpers\ConnectionManager;
use App\Http\Traits\PermissionTrait;
use App\Hase_option;
use App\Hase_location;
use App\Hase_option_values;
use URL;
use DB;
use Session;
use Redirect;


/**
 * Class Hase_optionController.
 *
 * @author  The scaffold-interface created at 2017-03-08 05:07:13am
 * @link  https://github.com/amranidev/scaffold-interface
 */
class Hase_optionController extends PermissionsController
{
    use PermissionTrait;

    public function __construct()
    {
        parent::__construct();

        $connectionStatus = ConnectionManager::setDbConfig('Hase_option');

        if ($connectionStatus['type'] === "error") {
            Session::flash('type', $connectionStatus['type']);
            Session::flash('msg', $connectionStatus['message']);
            return Redirect::back()->send();
        }

        $this->request_table_live = 'promotions';
        $this->request_table_stage = 'promotions_stage'; 
    }

    /**
     * Display a listing of the resource.
     *
     * @return  \Illuminate\Http\Response
     */
    public function index()
    {
        $haseOptionAccess = $this->permissionDetails('Hase_option','access');
        if($haseOptionAccess) {
            $title = 'Index - hase_option';
            $permissions = $this->getPermission('Hase_option');
            if(Requests::segment(1) === "hase_product_option"){
                $merchantType = 2;
                $labels = array("Products","Shop","Product");    
            }else{
                $merchantType = 8;
                $labels = array("Menus","Restaurants","Menu");
            }

            if($this->merchantId === 0 ) {
                $hase_options = Hase_option::
                        join('merchant_type_list','hase_merchant_type_list.location_id','=','hase_options.location_id')
                        ->join('hase_merchant_type','hase_merchant_type.type_id','=','hase_merchant_type_list.merchant_type')
                        ->where('hase_merchant_type.root_id','=',$merchantType)
                        ->get();
            } else {
                if($this->roleId === 4)
                {
                    $hase_options = Hase_option::all()->
                            where('merchant_id','=',$this->merchantId);
                } else {
                    $hase_options = Hase_option::all()->
                            where('merchant_id','=',$this->merchantId)
                            ->where('location_id','=',$this->locationId);
                }
            }
            
            return view('hase_option.index',compact('hase_options','title','labels','permissions'));
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
        $haseOptionAccess = $this->permissionDetails('Hase_option','add');
        if($haseOptionAccess) {
            $title = 'Create - hase_option';

            if(Requests::segment(1) === "hase_product_option"){
                $merchantType = 2;
                $labels = array("Products","Shop","Product");    
            }else{
                $merchantType = 8;
                $labels = array("Menus","Restaurants","Menu");
            }

            if($this->merchantId != 0 ) {
                $hase_locations = Hase_location::
                        where('merchant_id','=',$this->merchantId)->get();
            }

            return view('hase_option.create',compact('title','hase_locations','labels'));
        } else {
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
        $hase_option = new Hase_option();
        
        $hase_option->merchant_id = $this->merchantId;
        
        if($this->merchantId === 0){
            $hase_option->location_id = 
                    (Requests::segment(1) === "hase_product_option") ? 2 : 1;
        }else{
            $hase_option->location_id = $request->location_id;
        }

        $hase_option->option_name = $request->option_name;

        $hase_option->display_type = $request->display_type;

        $hase_option->priority = $request->priority;

        $hase_option->save();

        $lastInsertedOptionResponse = \Response::json(array('success' => true, 'last_insert_id' => $hase_option->option_id), 200);
        
        $lastInsertedOptionId = $lastInsertedOptionResponse->getData()->last_insert_id;

        if($request->option_values)
        {
            $optionIndex =1;
            foreach ($request->option_values as $optionValue) {
                $hase_option_values = new Hase_option_values;
                $hase_option_values->merchant_id = $this->merchantId;

                $hase_option_values->location_id = 
                                (Requests::segment(1) === "hase_product_option") ? 2 : 1;

                $hase_option_values->option_id = $lastInsertedOptionId;
                $hase_option_values->value = $optionValue['value'];
                $hase_option_values->price = $optionValue['price'];
                $hase_option_values->priority = $optionIndex;
                $hase_option_values->save();
                $optionIndex++;

            }
        }

        Session::flash('type', 'success'); 
        Session::flash('msg', 'Menu Options Successfully Inserted');
        if ($request->submitBtn === "Save") {
           return redirect(Requests::segment(1).'/'. $hase_option->option_id . '/edit');
        }else{
           return redirect(Requests::segment(1));
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

        $haseOptionAccess = $this->permissionDetails('Hase_option','manage');
        if($haseOptionAccess) {
            $title = 'Edit - hase_option';

            if(Requests::segment(1) === "hase_product_option"){
                $merchantType = 2;
                $labels = array("Products","Shop","Product");    
            }else{
                $merchantType = 8;
                $labels = array("Menus","Restaurants","Menu");
            }

            if($this->merchantId === 0 ) {
                $hase_option = Hase_option::findOrfail($id);
            } else {

                $hase_locations = Hase_location::
                    where('merchant_id','=',$this->merchantId)->get();

                $hase_option = Hase_option::
                            where('option_id',$id)
                            ->where('merchant_id','=',$this->merchantId)
                            ->get()->first();
            }
            if(!$hase_option) {
                return Redirect(Requests::segment(1))->with('message', 'You are not authorized to use this functionality!');    
            }
                
            $hase_option_values = DB::table('hase_option_values')->where('option_id',$id)->orderBy('priority', 'asc')->get();

            $option_count = count($hase_option_values);

            return view('hase_option.edit',compact('title','hase_option','hase_option_values','option_count','hase_locations','labels'));
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
        $hase_option = Hase_option::findOrfail($id);

        $hase_option->option_name = $request->option_name;

        if($this->merchantId != 0){
            $hase_category->location_id = $request->location_id;
        }

        $hase_option->display_type = $request->display_type;

        $hase_option->priority = $request->priority;

        $hase_option->save();

        $lastInsertedOptionResponse = \Response::json(array('success' => true, 'last_insert_id' => $hase_option->option_id), 200);

        $lastInsertedOptionId = $lastInsertedOptionResponse->getData()->last_insert_id;
        
        if($request->option_values)
        {
            $optionValueExist = array();
            foreach ($request->option_values as $optionValueData) {
                if (array_key_exists('option_value_id', $optionValueData)) {
                    $optionValueExist[] = $optionValueData['option_value_id'];
                }
            }
            Hase_option_values::whereNotIn('option_value_id', $optionValueExist)
                            ->where('option_id',$lastInsertedOptionId)->delete();

            $optionIndex =1;

            foreach ($request->option_values as $optionValue) {
                if ($optionValue['option_value_id']) {
                    $hase_option_values = Hase_option_values::
                        firstOrCreate(['option_value_id' => $optionValue['option_value_id']]);
                } else {
                    $hase_option_values = new Hase_option_values;
                }
                $hase_option_values->option_id = $lastInsertedOptionId;
                $hase_option_values->value = $optionValue['value'];
                $hase_option_values->price = $optionValue['price'];
                $hase_option_values->priority = $optionIndex;
                $hase_option_values->save();
                $optionIndex++;

            }
        } else {
            Hase_option_values::where('option_id',$lastInsertedOptionId);
        }

        Session::flash('type', 'success'); 
        Session::flash('msg', 'Menu Options Successfully Updated');
        
        if ($request->submitBtn === "Save") {
           return redirect(Requests::segment(1).'/'. $hase_option->option_id . '/edit');
        }else{
           return redirect(Requests::segment(1));
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
        $haseOptionAccess = $this->permissionDetails('Hase_option','delete');
        if($haseOptionAccess) {
            $hase_option = Hase_option::findOrfail($id);
            $hase_option->delete();
            Session::flash('type', 'success'); 
            Session::flash('msg', 'options Successfully Deleted');
            return redirect(Requests::segment(1));
        } else {
            return Redirect::back()->with('message', 'You are not authorized to use this functionality!');
        }
    }
}
