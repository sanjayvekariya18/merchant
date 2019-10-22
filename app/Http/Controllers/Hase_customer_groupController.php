<?php

namespace App\Http\Controllers;

use App\Customer_group;
use App\Helpers\ConnectionManager;
use App\Http\Controllers\PermissionsController;
use App\Http\Traits\PermissionTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Redirect;
use Session;

/**
 * Class Hase_customer_groupController.
 *
 * @author  The scaffold-interface created at 2017-02-27 06:11:13am
 * @link  https://github.com/amranidev/scaffold-interface
 */
class Hase_customer_groupController extends PermissionsController
{
    use PermissionTrait;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $connectionStatus = ConnectionManager::setDbConfig('Hase_customer_group');
        if (strcmp($connectionStatus['type'], "error") == 0) {
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
        $haseCustomergroupAceess = $this->permissionDetails('Hase_customer_group', 'access');
        if ($haseCustomergroupAceess) {
            $title                = 'Index - hase_customer_group';
            $hase_customer_groups = Customer_group::all();

            return view('hase_customer_group.index', compact('hase_customer_groups', 'title'));
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
        $haseCustomergroupAceess = $this->permissionDetails('Hase_customer_group', 'add');
        if ($haseCustomergroupAceess) {
            $title = 'Create - hase_customer_group';
            /*$merchants = Merchant::
            distinct()
            ->select('merchant.*','identity_merchant.identity_name as merchant_name')
            ->leftjoin('identity_merchant','identity_merchant.identity_id','merchant.identity_id')
            ->where('merchant.merchant_id','!=',0)
            ->get(); */
            return view('hase_customer_group.create');
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
        if (!$request->approval) {
            $request->approval = 0;
        }
        $hase_customer_group = new Customer_group();
        /*$hase_customer_group->merchant_id = $request->merchant_id;*/
        $hase_customer_group->group_name  = $request->group_name;
        $hase_customer_group->description = $request->description;
        $hase_customer_group->approval    = $request->approval;

        $hase_customer_group->save();

        Session::flash('type', 'success');
        Session::flash('msg', 'Customer group Successfully Inserted');
        if ($request->submitBtn == "Save") {
            return redirect('hase_customer_group/' . $hase_customer_group->customer_group_id . '/edit');
        } else {
            return redirect('hase_customer_group');
        }
    }

    /**
     * Show the form for editing the specified resource.
     * @param    \Illuminate\Http\Request  $request
     * @param    int  $id
     * @return  \Illuminate\Http\Response
     */
    public function edit($id, Request $request)
    {
        $haseCustomergroupAceess = $this->permissionDetails('Hase_customer_group', 'manage');
        if ($haseCustomergroupAceess) {
            /*$merchants = Merchant::
            distinct()
            ->select('merchant.*','identity_merchant.identity_name as merchant_name')
            ->leftjoin('identity_merchant','identity_merchant.identity_id','merchant.identity_id')
            ->where('merchant.merchant_id','!=',0)
            ->get(); */
            $hase_customer_group = Customer_group::findOrfail($id);
            return view('hase_customer_group.edit', compact('title', 'hase_customer_group'));
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
    public function update($id, Request $request)
    {
        if (!$request->approval) {
            $request->approval = 0;
        }
        $hase_customer_group = Customer_group::findOrfail($id);
        /*$hase_customer_group->merchant_id = $request->merchant_id;*/
        $hase_customer_group->group_name  = $request->group_name;
        $hase_customer_group->description = $request->description;
        $hase_customer_group->approval    = $request->approval;
        $hase_customer_group->save();

        Session::flash('type', 'success');
        Session::flash('msg', 'Customer group Successfully Inserted');
        if ($request->submitBtn == "Save") {
            return redirect('hase_customer_group/' . $hase_customer_group->customer_group_id . '/edit');
        } else {
            return redirect('hase_customer_group');
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
        $haseCustomergroupAceess = $this->permissionDetails('Hase_customer_group', 'delete');
        if ($haseCustomergroupAceess) {
            $hase_customer_group = Customer_group::findOrfail($id);
            $hase_customer_group->delete();
            Session::flash('type', 'success');
            Session::flash('msg', 'Customer Group Successfully Deleted');
            return redirect('hase_customer_group');
        } else {
            return Redirect::back()->with('message', 'You are not authorized to use this functionality!');
        }
    }
}
