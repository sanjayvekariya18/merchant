<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\App;
use Illuminate\Http\Request;
use App\Http\Controllers\PermissionsController;
use App\Helpers\ConnectionManager;
use App\Permission;
use App\Group_permission;
use App\Http\Traits\PermissionTrait;
use URL;
use Session;
use DB;
use Redirect;

/**
 * Class Hase_permissionController.
 *
 * @author  The scaffold-interface created at 2017-03-10 04:10:26am
 * @link  https://github.com/amranidev/scaffold-interface
 */
class Hase_permissionController extends PermissionsController
{
	use PermissionTrait;

    public function __construct()
    { 
        parent::__construct();

        $connectionStatus = ConnectionManager::setDbConfig('Hase_permission');

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
		if($this->permissionDetails('Hase_permission','access')){
			$title = 'Index - hase_permission';
			$permissions = $this->getPermission("Hase_permission");
			$hase_permissions = Permission::all();
			return view('hase_permission.index',compact('hase_permissions','permissions','title'));
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
		if($this->permissionDetails('Hase_permission','add')){
			$title = 'Create - hase_permission';
		
			return view('hase_permission.create');
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
		$permission = DB::table('permissions')
					->where('permissions.name',$request->name)->get()->first();

		if($permission){
			$hase_permission = Permission::firstOrCreate(['name' => $request->name]);

			Session::flash('type', 'success'); 
			Session::flash('title', 'Permission Already Exist!'); 
			Session::flash('msg', 'Permission Update Successfully.'); 
		}else{
			$hase_permission = new Permission();
			Session::flash('type', 'success'); 
			Session::flash('msg', 'Permission Successfully Created.'); 
		}

		$hase_permission->name = $request->name;
		
		$hase_permission->description = $request->description;
		
		$hase_permission->action = serialize($request->action);
		
		$hase_permission->status = isset($request->status) ? 1 : 0;

		$hase_permission->save();

		if ($request->submitBtn === "Save") {
			return redirect('hase_permission/'. $hase_permission->permission_id . '/edit');
		}else{
			return redirect('hase_permission');
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
		$title = 'Show - hase_permission';

		if($request->ajax())
		{
			return URL::to('hase_permission/'.$id);
		}

		$hase_permission = Permission::findOrfail($id);
		return view('hase_permission.show',compact('title','hase_permission'));
	}

	/**
	 * Show the form for editing the specified resource.
	 * @param    \Illuminate\Http\Request  $request
	 * @param    int  $id
	 * @return  \Illuminate\Http\Response
	 */
	public function edit($id,Request $request)
	{
		if($this->permissionDetails('Hase_permission','manage')){
			$title = 'Edit - hase_permission';
		
			$hase_permission = Permission::findOrfail($id);
			if(count($hase_permission)){
				$hase_permission->action = unserialize($hase_permission->action);	
				return view('hase_permission.edit',compact('title','hase_permission'));
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
		$hase_permission = Permission::findOrfail($id);
		$oldPermissionName = $hase_permission->name;
		$hase_permission->name = $request->name;
		$dirtyNameField = $hase_permission->getDirty();
		if(isset($dirtyNameField['name']))
		{
			$allGroupPermission = Group_permission::select('permissions','group_id')->where('group_id','>',0)->get();
			foreach ($allGroupPermission as $allGroupPermissionKey => $allGroupPermissionvalue) {

				$groupPermission = unserialize($allGroupPermissionvalue->permissions);
				if(array_key_exists($oldPermissionName, $groupPermission))
				{
					$groupPermission[$request->name] = $groupPermission[$oldPermissionName];
					unset($groupPermission[$oldPermissionName]);

				}
				$saveUpdatedGroup = Group_permission::findOrfail($allGroupPermissionvalue->group_id);
				$saveUpdatedGroup->permissions = serialize($groupPermission);
				$saveUpdatedGroup->save();
				
			}
		}
		$hase_permission->description = $request->description;
		
		$hase_permission->action = serialize($request->action);
		
		$hase_permission->status = isset($request->status) ? 1 : 0;
		
		
		$hase_permission->save();

		Session::flash('type', 'success'); 
        Session::flash('msg', 'Permission Successfully Updated'); 

        if ($request->submitBtn === "Save") {
			return redirect('hase_permission/'. $hase_permission->permission_id . '/edit');
		}else{
			return redirect('hase_permission');
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
		$msg = Ajaxis::BtDeleting('Warning!!','Would you like to remove This?','/hase_permission/'. $id . '/delete');

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
		if($this->permissionDetails('Hase_permission','delete')){
			$hase_permission = Permission::findOrfail($id);
			$hase_permission->status = 0;
            $hase_permission->save();

			Session::flash('type', 'error'); 
	        Session::flash('msg', 'Permission Successfully Deleted');

			return redirect('hase_permission');
		}else{
			return Redirect::back()->with('message', 'You are not authorized to use this functionality!');
		}
	}
}
