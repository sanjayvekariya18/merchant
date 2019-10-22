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

use App\Identity_dom_setup;

class Identity_dom_setupController extends Controller
{
	use PermissionTrait;
	
	public $show_action = true;
	public $view_col = 'type_name';
	public $listing_cols = ['type_id', 'type_name', 'table_source', 'table_identity_id', 'url_id'];
	
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

        $connectionStatus = ConnectionManager::setDbConfig('Identity_dom_setup', 'mysqlDynamicConnector');

        if (strcmp($connectionStatus['type'], "error") == 0) {

            Session::flash('type', $connectionStatus['type']);
            Session::flash('msg', $connectionStatus['message']);
        }
        return $next($request);
       });

	}
	
	/**
	 * Display a listing of the Identity_dom_setup.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		$module = Module::get('Identity_dom_setup');
		
		if(Module::hasAccess($module->id,"access")) {
			return View('identity_dom_setup.index', [
				'show_actions' => $this->show_action,
				'listing_cols' => $this->listing_cols,
				'module' => $module
			]);
		} else {
            return Redirect::back()->with('message', 'You are not authorized to use this functionality!');
        }
	}

	public function getIdentityDomSetup(Request $request)
    {
        $total_records = 0;

        $identityDomSetupDetails = Identity_dom_setup::
            select('identity_dom_setup.*');

        if (isset($request->filter)) {
            $searchFiler = $request->filter['filters'][0]['value'];
            if($searchFiler)
            {
                $identityDomSetupDetails->where(function ($query) use ($searchFiler,$request) {
                    foreach ($request->filter['filters'] as $filterKey => $filterValue) {
                        $query->orWhere('identity_dom_setup.'.$filterValue['field'].'', 'LIKE', '%' . $searchFiler . '%');
                    }
                });
            }
        }

        $total_records = $total_records + $identityDomSetupDetails->get()->count();
        if(isset($request->take))
        {
            $identityDomSetupDetails->offset($request->skip)->limit($request->take);
        }
        $identityDomSetupValues = $identityDomSetupDetails->get()->toArray();
        $templateDefineArray = array("Multiselect");
        $fields_popup = ModuleFields::getModuleFields('Identity_dom_setup');
        $module = Module::where('name', 'Identity_dom_setup')->orderBy('id', 'desc')->first();
        foreach ($identityDomSetupValues as $key => $value) {
            for ($j=0; $j < count($this->listing_cols); $j++) { 
                $col = $this->listing_cols[$j];
                if(isset($value[$col]) && $fields_popup[$col] != null && starts_with($fields_popup[$col]->popup_vals, "@")) {
                    $field_type = ModuleFieldTypes::find($fields_popup[$col]->field_type);
                    if (in_array($field_type->name, $templateDefineArray))
                    {
                        $identityDomSetupValues[$key][$fields_popup[$col]->popup_field_id] = ModuleFields::getKendoFieldValue($fields_popup[$col], $value[$col],$module->provider_id);
                    } else {
                        $identityDomSetupValues[$key][$fields_popup[$col]->popup_field_name] = ModuleFields::getFieldValue($fields_popup[$col], $value[$col],$module->provider_id);
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
        $IdentityDomSetupdata['identityDomSetup'] = array_values($identityDomSetupValues);
        $newIdentityDomSetupdata[0] = array("type_id" => 0,"type_name" => "", "table_source" => "", "table_identity_id" => 0, "url_id" => 0,"table_code" => "None", "identity_website" => "None");
        $IdentityDomSetupdata['identityDomSetup'] = array_merge($newIdentityDomSetupdata, $IdentityDomSetupdata['identityDomSetup']);

        $IdentityDomSetupdata['total'] = $total_records;
        return json_encode($IdentityDomSetupdata); 
    }
    
	function getTableIdentityIdList(Request $request) {
		$table_identity_idDetails =  DB::connection("mysqlDynamicConnector")->table("identity_table_type")->select("type_id","table_code")->get()->toArray();
		return json_encode($table_identity_idDetails);
	}
	
	function getUrlIdList(Request $request) {
		$url_idDetails =  DB::connection("mysqlDynamicConnector")->table("identity_website")->select("identity_id","identity_website")->get()->toArray();
		return json_encode($url_idDetails);
	}
					
    public function createIdentityDomSetup(Request $request)
    {
        if (Module::hasAccess("Identity_dom_setup", "manage")) {
            $identity_dom_setup = new Identity_dom_setup();
            if ($request->type_id == 0) {
                try {
                    $identity_dom_setup->type_name = $request->type_name;
                    $identity_dom_setup->save();
                    return array("type" => "success", "message" => 'Identity Dom Type Inserted');
                } catch (\Exception $e) {
                    $exceptionMessage = ConnectionManager::renderPdoException($e->getCode());
                    return array("type" => "error", "message" => $exceptionMessage);
                }

            } else {
                try {
                    Module::updateRow("Identity_dom_setup", $request, $request->type_id);
                    return array("type" => "success", "message" => 'Identity Dom Type Updated');
                } catch (\Exception $e) {
                    $exceptionMessage = ConnectionManager::renderPdoException($e->getCode());

                }
            }
        }
    }
}
