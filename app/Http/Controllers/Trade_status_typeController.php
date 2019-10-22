<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\App;
use Illuminate\Http\Request;
use App\Http\Controllers\PermissionsController;
use App\Helpers\ConnectionManager;
use Amranidev\Ajaxis\Ajaxis;
use App\Http\Traits\PermissionTrait;
use App\TradeStatusType;
use Carbon\Carbon;
use URL;
use Session;
use DB;
use Redirect;

/**
 * Class Trade_status_typeController.
 *
 * @author  The scaffold-interface created at 2018-02-08 07:46:49pm
 * @link  https://github.com/amranidev/scaffold-interface
 */
class Trade_status_typeController extends PermissionsController
{
	use PermissionTrait;

	public function __construct()
	{
		parent::__construct();

        $connectionStatus = ConnectionManager::setDbConfig('Trade_status_type');

        if ($connectionStatus['type'] === "error") {
            Session::flash('type', $connectionStatus['type']);
            Session::flash('msg', $connectionStatus['message']);
            return Redirect::back()->send();
        }
	}

	public function index()
	{
		 if($this->permissionDetails('Trade_status_type','access')){
					   
			 $permissions = $this->getPermission("Trade_status_type");
			
			if($this->merchantId == 0){
				$trade_status_types = TradeStatusType::paginate(25);
				 return view('trade_status_type.index',compact('trade_status_types','permissions'));
			}
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
		if($this->permissionDetails('Trade_status_type','add')){
			return view('trade_status_type.create');
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
		$trade_status_type = new TradeStatusType();

		
		$trade_status_type->trade_status_code = $request->trade_status_code;
		$trade_status_type->trade_status_name = $request->trade_status_name;
		$trade_status_type->trade_status_color = $request->trade_status_color;
		$trade_status_type->trade_status_font_color = $request->trade_status_font_color;
		
		$trade_status_type->save();

		$tradeStatusID = $trade_status_type->trade_status_id;

		Session::flash('type', 'success'); 
		Session::flash('msg', 'Trade Status Type Successfully Created');
		
		if ($request->submitBtn === "Save") {
			return redirect('trade_status_type/'. $tradeStatusID . '/edit');
		}else{
			return redirect('trade_status_type');
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
		 if($this->permissionDetails('Trade_status_type','manage')){
			
			$trade_status_type = TradeStatusType::findOrfail($id);
			return view('trade_status_type.edit',compact('trade_status_type'));

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
		$trade_status_type = TradeStatusType::findOrfail($id);
		
		$trade_status_type->trade_status_code = $request->trade_status_code;
		$trade_status_type->trade_status_name = $request->trade_status_name;
		$trade_status_type->trade_status_color = $request->trade_status_color;
		$trade_status_type->trade_status_font_color = $request->trade_status_font_color;
		
		
		$trade_status_type->save();

		$tradeStatusID = $trade_status_type->trade_status_id;

		Session::flash('type', 'success'); 
		Session::flash('msg', 'Trade Status Type Successfully Created');
		
		if ($request->submitBtn === "Save") {
			return redirect('trade_status_type/'. $tradeStatusID . '/edit');
		}else{
			return redirect('trade_status_type');
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
		 if($this->permissionDetails('Trade_status_type','delete')){
			$trade_status_type = TradeStatusType::findOrfail($id);
			$trade_status_type->delete();
			Session::flash('type', 'success'); 
			Session::flash('msg', 'Trade Status Type Successfully Deleted');
			return redirect('trade_status_type');
		 }else{
		 	return Redirect::back()->with('message', 'You are not authorized to use this functionality!');
		 }
	}
}
