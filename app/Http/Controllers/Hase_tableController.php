<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\App;
use Illuminate\Http\Request;
use App\Http\Controllers\PermissionsController;
use App\Helpers\ConnectionManager;
use App\Http\Traits\PermissionTrait;
use App\Reservations_seating;
use URL;
use Session;
use Redirect;

/**
 * Class Hase_tableController.
 *
 * @author  The scaffold-interface created at 2017-03-06 05:42:53am
 * @link  https://github.com/amranidev/scaffold-interface
 */
class Hase_tableController extends PermissionsController
{
    use PermissionTrait;
    
    public function __construct()
    {
        parent::__construct();

        $connectionStatus = ConnectionManager::setDbConfig('Hase_table');
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
        $haseTableAccess = $this->permissionDetails('Hase_table','access');
        if($haseTableAccess) {
            $title = 'Index - Table';
            if($this->merchantId == 0 ) {
                $hase_tables = Reservations_seating::all();
            } else {
                $hase_tables = Reservations_seating::
                                join('location_list','location_list.identity_id','reservations_seating.location_id')
                                ->where('location_list.identity_id','=',$this->merchantId)
                                ->get();
            }
            
            return view('hase_table.index',compact('hase_tables','title'));
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
        $haseTableAccess = $this->permissionDetails('Hase_table','add');
        if($haseTableAccess) {
            return view('hase_table.create');
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
        $hase_table = new Reservations_seating();

        $hase_table->location_id = $this->locationId;
        
        $hase_table->seating_name = $request->seating_name;

        $hase_table->min_capacity = $request->min_capacity;

        $hase_table->max_capacity = $request->max_capacity;
        
        $hase_table->autobook = $request->autobook;
        
        if(!$request->status)
        {
            $request->status = 0;
        }
        $hase_table->status = $request->status;

        $hase_table->save();

        Session::flash('type', 'success');
        Session::flash('msg', 'Table Successfully Saved');
        if ($request->submitbutton === "Save") {
          return redirect('hase_table/'. $hase_table->seating_id . '/edit');
        } else {
          return redirect('hase_table');
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
        $title = 'Show - Table';

        if($request->ajax())
        {
            return URL::to('hase_table/'.$id);
        }

        $hase_table = Reservations_seating::findOrfail($id);
        return view('hase_table.show',compact('title','hase_table'));
    }

    /**
     * Show the form for editing the specified resource.
     * @param    \Illuminate\Http\Request  $request
     * @param    int  $id
     * @return  \Illuminate\Http\Response
     */
    public function edit($id,Request $request)
    {
        $haseTableAccess = $this->permissionDetails('Hase_table','manage');
        if($haseTableAccess) {
            $title = 'Edit - Table';
            if($this->merchantId == 0 ) {
                $hase_table = Reservations_seating::findOrfail($id);
            } else {
                $hase_table = Reservations_seating::where('table_id',$id)->where('merchant_id','=',$this->merchantId)->get()->first();
            }
            if(!$hase_table) {
                return Redirect('hase_table')->with('message', 'You are not authorized to use this functionality!');
            }
            return view('hase_table.edit',compact('title','hase_table'  ));
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
        $hase_table = Reservations_seating::findOrfail($id);
                
        $hase_table->location_id = $this->locationId;
        
        $hase_table->seating_name = $request->seating_name;

        $hase_table->min_capacity = $request->min_capacity;
        
        $hase_table->max_capacity = $request->max_capacity;
        
        $hase_table->autobook = $request->autobook;
        
        if(!$request->status)
        {
            $request->status = 0;
        }
        $hase_table->status = $request->status;
        
        $hase_table->save();

        Session::flash('type', 'success');
        Session::flash('msg', 'Table Successfully Saved');

        if ($request->submitbutton === "Save") {
          return redirect('hase_table/'. $hase_table->seating_id . '/edit');
        } else {
          return redirect('hase_table');
        }
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
        $msg = Ajaxis::BtDeleting('Warning!!','Would you like to remove This?','/hase_table/'. $id . '/delete');

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
        $haseTableAccess = $this->permissionDetails('Hase_table','delete');
        if($haseTableAccess) {
            $hase_table = Reservations_seating::findOrfail($id);
            $hase_table->delete();
            Session::flash('type', 'success');
            Session::flash('msg', 'Table Successfully Deleted');
            return redirect('hase_table');
        } else {
            return Redirect::back()->with('message', 'You are not authorized to use this functionality!');
        }
    }
}
