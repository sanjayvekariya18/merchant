<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Traits\PermissionTrait;
use DB;
use Schema;
use Session;
use App\Role;
use App\Module;
use App\ModuleFields;
use App\ModuleFieldTypes;
use App\Helpers\LAHelper;

class FieldController extends Controller
{
	const INIT_VALUE      = 0;
	const FIRST_VALUE     = 1;
	
	use PermissionTrait;

	public function __construct() {
		$this->middleware('auth');
        $this->middleware(function ($request, $next) {
        $this->staffId = session()->has('staffId') ? session()->get('staffId') :"";
        $this->identity_table_id = session()->has('identity_table_id') ? session()->get('identity_table_id') :"";
        $this->staffName = session()->has('staffName') ? session()->get('staffName') :"";
        $this->merchantId = session()->has('merchantId') ? session()->get('merchantId') :"";
        $this->roleId = session()->has('role') ? session()->get('role') :"";
        $this->locationId = session()->has('locationId') ? session()->get('locationId') :"";
        $this->staffUrl = session()->has('staffUrl') ? session()->get('staffUrl') :"";
        $this->userId = session()->has('userId') ? session()->get('userId') :"";
        return $next($request);
       });
	}
	
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request)
	{

		$module = Module::find($request->module_id);
		$module_id = $request->module_id;

		$module = Module::find($module_id);
		$moduleFieldExist = ModuleFields::where('module',$module_id)->get()->first();
		if((isset($module) && $module->view_col == '') || (!$moduleFieldExist))
		{
			$module->view_col=$request->colname;
			$module->save();
		}
		$fieldResponse = ModuleFields::createField($request);
		if (strcmp($fieldResponse['type'],"error") == self::INIT_VALUE) {
            Session::flash('type', $fieldResponse['type']);
            Session::flash('msg', $fieldResponse['message']);
        }
		return redirect()->route('modules.show', [$module_id]);
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show($id)
	{
		// $ftypes = ModuleFieldTypes::getFTypes2();
		// $module = Module::find($id);
		// $module = Module::get($module->name);
		// return view('modules.show', [
		//     'no_header' => true,
		//     'no_padding' => "no-padding",
		//     'ftypes' => $ftypes
		// ])->with('module', $module);
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function edit($id)
	{
		$field = ModuleFields::find($id);
		
		$module = Module::find($field->module);
		$ftypes = ModuleFieldTypes::getFieldTypeValue();
		
		$tables = LAHelper::getDBTables($module->provider_id,[]);
		
		return view('modules.field_edit', [
			'module' => $module,
			'ftypes' => $ftypes,
			'tables' => $tables
		])->with('field', $field);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, $id)
	{
		$module_id = $request->module_id;
		if($request->view_col) {
            $module = Module::find($module_id);
            $module->view_col = $request->colname;
            $module->save();
        } 
		ModuleFields::updateField($id, $request);
		
		return redirect()->route('modules.show', [$module_id]);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id)
	{
		$field = ModuleFields::find($id);
		$module = Module::find($field->module);
		$this->deleteFields($id);
		return redirect()->route('modules.show', [$module->id]);
	}
	
	/**
	 * Check unique values for perticular field
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function check_unique_val(Request $request, $field_id)
	{
		$valExists = false;
		
		// Get Field
		$field = ModuleFields::find($field_id);
		// Get Module
		$module = Module::find($field->module);
		
		// echo $module->name_db." ".$field->colname." ".$request->field_value;
		$rowCount = DB::table($module->name_db)->where($field->colname, $request->field_value)->where("id", "!=", $request->row_id)->whereNull('deleted_at')->count();
		
		if($rowCount > self::INIT_VALUE) {
			$valExists = true;
		}	
		
		return response()->json(['exists' => $valExists]);
	}

	/**
	 * Set Visibility values for perticular field
	 *
	 * @param  int  $id
	 * @param  int  $status
	 * @return \Illuminate\Http\Response
	 */
	public function set_visibility($id,$status)
	{
		$fieldVisibility = ($status =='show' ? self::FIRST_VALUE : self::INIT_VALUE);
		$field = ModuleFields::find($id);
		$field->visibility = $fieldVisibility;
		$field->save();
		$module = Module::find($field->module);
		return redirect()->route('modules.show', [$module->id]);
	}

	/**
	 * Set Visibility values for perticular field
	 *
	 * @param  int  $id
	 * @param  int  $status
	 * @return \Illuminate\Http\Response
	 */
	public function massFieldAction(Request $request)
	{
		if(isset($request->selectedFields) && !empty($request->selectedFields))
		{
			$fieldIds = explode(',', $request->selectedFields);
			foreach ($fieldIds as $fieldKey => $fieldValue) {
				if($request->fieldAction == 'hide')
				{
					$field = ModuleFields::find($fieldValue);
					$field->visibility = self::INIT_VALUE;
					$field->save();
				} else if($request->fieldAction == 'delete') {
					$this->deleteFields($fieldValue);
					
				}
			}
		}
		return redirect()->route('modules.show', [$request->massActionModuleId]);
	}

	public function deleteFields($fieldId)
	{
		// Get Context
		$field = ModuleFields::find($fieldId);
		$module = Module::find($field->module);
		
		// Delete from Table module_field
		Schema::table($module->name_db, function ($table) use ($field) {
			$table->dropColumn($field->colname);
		});
		
		// Delete Context
		$field->delete();
	}

	
}
