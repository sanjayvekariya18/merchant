<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Helpers\ConnectionManager;
use Illuminate\Http\Request;
use App\Http\Requests;
use Auth;
use DB;
use Validator;
use Datatables;
use App\ModuleFieldTypes;
use Collective\Html\FormFacade as Form;
use App\Http\Traits\PermissionTrait;
use App\Module;
use App\ModuleFields;
use App\Helpers\LAHelper;

use App\Portal_exception;
use Config;

class Portal_exceptionController extends Controller
{
	use PermissionTrait;
	
	public $show_action = true;
	public $view_col = 'exception';
	public $listing_cols = ['id', 'user_id', 'identity_id', 'exception', 'datetime', 'identity_table_id'];
	
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

        $connectionStatus = ConnectionManager::setDbConfig('Portal_exception', 'mysqlDynamicConnector');

        if (strcmp($connectionStatus['type'], "error") == 0) {

            Session::flash('type', $connectionStatus['type']);
            Session::flash('msg', $connectionStatus['message']);
        }
        return $next($request);
       });

	}
	
	/**
	 * Display a listing of the Portal_exception.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		$module = Module::get('Portal_exception');
		
		if(Module::hasAccess($module->id,"access")) {
			return View('portal_exception.index', [
				'show_actions' => $this->show_action,
				'listing_cols' => $this->listing_cols,
				'module' => $module
			]);
		} else {
            return Redirect::back()->with('message', 'You are not authorized to use this functionality!');
        }
	}

	public function getPortal_exception(Request $request)
    {
        $total_records = 0;

        $Portal_exceptionDetails = Portal_exception::
            select('portal_exception.*');
        $total_records = $total_records + $Portal_exceptionDetails->get()->count();
        
        if(isset($request->take))
        {
            $Portal_exceptionDetails->offset($request->skip)->limit($request->take);
        }
        $Portal_exceptionValues = $Portal_exceptionDetails->get()->toArray();
        $templateDefineArray = array("Multiselect");
        $fields_popup = ModuleFields::getModuleFields('Portal_exception');
        $module = Module::where('name', 'Portal_exception')->first();
        foreach ($Portal_exceptionValues as $key => $value) {
            for ($j=0; $j < count($this->listing_cols); $j++) { 
                $col = $this->listing_cols[$j];
                if(isset($value[$col]) && $fields_popup[$col] != null && starts_with($fields_popup[$col]->popup_vals, "@")) {
                    $field_type = ModuleFieldTypes::find($fields_popup[$col]->field_type);
                    if (in_array($field_type->name, $templateDefineArray))
                    {
                        $Portal_exceptionValues[$key][$fields_popup[$col]->popup_field_id] = ModuleFields::getKendoFieldValue($fields_popup[$col], $value[$col],$module->provider_id);
                    } else {
                        $Portal_exceptionValues[$key][$fields_popup[$col]->popup_field_name] = ModuleFields::getFieldValue($fields_popup[$col], $value[$col],$module->provider_id);
                    }

                } else if(isset($value[$col]) && !!$value[$col] && isset($fields_popup[$col]) && !!$fields_popup[$col] && starts_with($fields_popup[$col]->popup_vals, "[") ){
                    $field_type = ModuleFieldTypes::find($fields_popup[$col]->field_type);
                    if (in_array($field_type->name, $templateDefineArray))
                    {
                        $TestingEventValues[$key][$fields_popup[$col]->colname] = json_decode($TestingEventValues[$key][$fields_popup[$col]->colname],true);
                    }

                }
            }
        }
        if(isset($request->filter))
        {
            $searchFiler =  $request->filter['filters'][0]['value'];
            if($searchFiler)
            {
                foreach ($Portal_exceptionValues as $Portal_exceptionKey => $Portal_exceptionValue) {
                    $flagValue = false;
                    foreach ($request->filter['filters'] as $filterKey => $filterValue) {
                        if(is_array($Portal_exceptionValue[$filterValue['field']]))
                        {
                            if (in_array($searchFiler, $Portal_exceptionValue[$filterValue['field']])) {
                                $flagValue = true;
                            }
                        } else {
                            if (stripos($Portal_exceptionValue[$filterValue['field']], $searchFiler) !== false) {
                                $flagValue = true;
                            }
                        }
                    }
                    if($flagValue == false)
                    {
                        unset($Portal_exceptionValues[$Portal_exceptionKey]);
                        $total_records = $total_records-1;
                    }
                }
            }
        }
        foreach ($Portal_exceptionValues as $key => $value) {
            
            $human_time = PermissionTrait::humanTiming($value['datetime']);
            $timezone_name = PermissionTrait::getTimezoneName($value['user_timezone']);
            $timezone_offset = PermissionTrait::getOffsetFromTzName($timezone_name);
            $user_datetime = PermissionTrait::covertToUserTz($value['datetime'],$timezone_name);

            $date_format = date('m/d/Y h:i A',strtotime($value['datetime']))." ".Config::get('app.timezone').($value['user_timezone'] !="" ? " [".date("H:i",strtotime($user_datetime)). " ".$timezone_offset.']' : "");
            $display_date = $date_format." ".$human_time; 
            $Portal_exceptionValues[$key]['datetime'] = $display_date;
            
        }
        $Portal_exception_data['Portal_exception'] = array_values($Portal_exceptionValues);
        $Portal_exception_data['total'] = $total_records;
        return json_encode($Portal_exception_data); 
    }
    
					function getuser_idList(Request $request) {
						$user_idDetails =  DB::connection("mysqlDynamicConnector")->table("portal_password")->select("user_id","username")->get()->toArray();
						return json_encode($user_idDetails);
					}
					
					function getidentity_idList(Request $request) {
						$identity_idDetails =  DB::connection("mysqlDynamicConnector")->table("database_manager")->select("id","provider_name")->get()->toArray();
						return json_encode($identity_idDetails);
					}
					
					function getidentity_table_idList(Request $request) {
						$identity_table_idDetails =  DB::connection("mysqlDynamicConnector")->table("identity_table_type")->select("type_id","table_name")->get()->toArray();
						return json_encode($identity_table_idDetails);
					}
					

    /**
     * Update the specified portal_exception in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updatePortal_exception(Request $request)
    {
        $callback = $request->callback;
        if(Module::hasAccess("Portal_exception", "manage")) {
            
            $rules = Module::validateRules("Portal_exception", $request, true);
            
            $validator = Validator::make($request->all(), $rules);
            
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();;
            }
            
            Module::updateRow("Portal_exception", $request, $request->id);
            return $callback."(".json_encode($request).")";
            
        } else {
            return $callback."(".json_encode($request).")";
        }
    }
}
