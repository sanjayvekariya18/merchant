<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\App;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Approval_crud_status;
use App\Http\Traits\PermissionTrait;
use URL;
use Session;
use DB;
use Redirect;
use Auth;

/**
 * Class Hase_approval_crud_statusController.
 *
 * @author  The scaffold-interface created at 2017-04-06 02:12:00pm
 * @link  https://github.com/amranidev/scaffold-interface
 */
class Hase_approval_crud_statusController extends Controller
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
        
        $title = 'Index - hase_approval_crud_status';
        $hase_approval_statuses = Approval_crud_status::all();

        return view('hase_approval_crud_status.index',compact('hase_approval_statuses','title'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return  \Illuminate\Http\Response
     */
    public function create()
    {
        $title = 'Create - hase_approval_crud_status';
        return view('hase_approval_crud_status.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param    \Illuminate\Http\Request  $request
     * @return  \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $hase_approval_status = new Approval_crud_status();
        $hase_approval_status->crud_status_name = $request->crud_status_name;
        $hase_approval_status->crud_status_color = $request->crud_status_color;
        $hase_approval_status->crud_status_code = $request->crud_status_code;
        $hase_approval_status->save();
        Session::flash('type', 'success'); 
        Session::flash('msg', 'Approve Status Successfully Inserted');
        if ($request->submitBtn == "Save") {
           return redirect('hase_approval_crud_status/'. $hase_approval_status->crud_status_id . '/edit');
        }else{
           return redirect('hase_approval_crud_status');
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
        $title = 'Edit - hase_approval_crud_status';
        $hase_approval_status = Approval_crud_status::findOrfail($id);
        return view('hase_approval_crud_status.edit',compact('title','hase_approval_status'  ));
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
        $hase_approval_status = Approval_crud_status::findOrfail($id);
        $hase_approval_status->crud_status_name = $request->crud_status_name;
        $hase_approval_status->crud_status_color = $request->crud_status_color;
        $hase_approval_status->save();
        Session::flash('type', 'success'); 
        Session::flash('msg', 'Approve Status Successfully Updated');
        if ($request->submitBtn == "Save") {
           return redirect('hase_approval_crud_status/'. $hase_approval_status->crud_status_id . '/edit');
        }else{
           return redirect('hase_approval_crud_status');
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
        if($this->permissionDetails('Hase_approval_crud_status','delete')) {
            $hase_approval_status = Approval_crud_status::findOrfail($id);
            $hase_approval_status->delete();
            Session::flash('type', 'success'); 
            Session::flash('msg', 'approval Status Successfully Deleted');
            return redirect('hase_approval_crud_status');
        } else {
            return Redirect::back()->with('message', 'You are not authorized to use this functionality!');
        }
    }
}
