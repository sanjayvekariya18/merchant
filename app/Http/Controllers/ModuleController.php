<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests;
use DB;
use App\Helpers\LAHelper;
use App\Helpers\ConnectionManager;
use App\Helpers\CodeGenerator;
use App\Menus;
use App\Menus_database_manager;
use App\Module;
use App\ModulesSnippet;
use App\ModuleFields;
use App\ModulesGrouping;
use App\ModuleFieldTypes;
use App\Group_permission;
use App\Permission;
use App\Database_manager;
use App\Identity_table_type;
use App\Role;
use App\Http\Traits\PermissionTrait;
use Schema;
use Session;
use Redirect;
use stdClass;

class ModuleController extends Controller
{
	const INIT_VALUE      = 0;
	const FIRST_VALUE     = 1;
	const NEGATIVE_FIRST_VALUE = -1;

	public $listing_cols = ['id', 'module_id', 'placeholder', 'snippet'];

	use PermissionTrait;
	protected $dbconnector;
	public function __construct() {
		// for authentication (optional)
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
        $this->dbconnector = 'mysqlDynamicConnector';
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
		if($this->permissionDetails('Modules','access')) {
            $permissions = $this->getPermission("Modules");
            if($this->roleId == self::FIRST_VALUE)
            {
            	$moduleDetails = Module::join('portal_password','portal_password.user_id','modules.owner_id')
            		->where('custom',self::FIRST_VALUE)
            		->select('modules.*','portal_password.username','portal_password.identity_id','portal_password.identity_table_id')
            		->get();
            } else {
            	$moduleDetails = Module::join('portal_password','portal_password.user_id','modules.owner_id')
            		->where('custom',self::FIRST_VALUE)
            		->where('owner_id',$this->userId)
            		->select('modules.*','portal_password.username','portal_password.identity_id','portal_password.identity_table_id')
            		->get();
            }
            foreach ($moduleDetails as $moduleKey => $moduleValue) {

				$originalTable = Identity_table_type::where("type_id",$moduleValue->identity_table_id)->get()->first()->table_code; 

				$originalTableData  = DB::table($originalTable)
									   ->join('merchant','merchant.merchant_id',$originalTable.".merchant_id")	
				                       ->where($originalTable.".identity_id",$moduleValue->identity_id)
				                       ->get()
				                       ->first(); 

                $identityTable = Identity_table_type::where("type_id",$originalTableData->identity_table_id)->get()->first()->table_code;

                $identityTableData  = DB::table($identityTable)
                                   ->where("identity_id",$originalTableData->identity_id)
                                   ->get()
                                   ->first(); 
				$moduleDetails[$moduleKey]->identity_name = $identityTableData->identity_name;
			}
			$databaseProvider = Database_manager::getDBProvider();
            return View('modules.index',compact('moduleDetails','permissions','databaseProvider','tableList'));

        }else{
        	return Redirect::back()->with('message', 'You are not authorized to use this functionality!');
        }          
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
		$popupCommand = (isset($request->newTablePopupCommand)) ? self::FIRST_VALUE : self::INIT_VALUE;
		$allowDelete = (isset($request->newTableAllowDeleteCommand)) ? self::FIRST_VALUE : self::INIT_VALUE;
		$allowEdit = (isset($request->newTableAllowEditCommand)) ? self::FIRST_VALUE : self::INIT_VALUE;
		$selectAllCheckbox = (isset($request->newTableSelectAllCommand)) ? self::FIRST_VALUE : self::INIT_VALUE;
		$module_id = Module::generateBase($request->name, $request->icon,$request->newTableProvider,$popupCommand,$allowDelete,$allowEdit,$selectAllCheckbox);
		
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
		$module = Module::find($id);
		$module = Module::get($module->name);
		if($this->permissionDetails('Modules','manage') && ($this->roleId==self::FIRST_VALUE || $module->owner_id == $this->userId)) {
			$permissions = $this->getPermission("Modules");
			$ftypes = ModuleFieldTypes::getFieldTypeValue();
			$tables = LAHelper::getDBTables($module->provider_id,[]);
			$modules = LAHelper::getModuleNames([]);		
			$moduleFields = ModuleFields::getModuleFieldsList($id);
			$moduleGroupFields = ModuleFields::getGroupByColumns($id);
			
			// Get Modules Access for all roles
			$roles = Module::getRoleAccess($id);
			$crudType = array("1"=>"Kendo UI","2"=>"Bootstrap");
			$sortOrder = array("asc"=>"Asc","desc"=>"Desc");
			
			return view('modules.show', [
				'no_header' => true,
				'no_padding' => "no-padding",
				'ftypes' => $ftypes,
				'tables' => $tables,
				'modules' => $modules,
				'roles' => $roles,
				'crudType' => $crudType,
				'sortOrder' => $sortOrder,
				'permissions' => $permissions,
				'moduleFieldList' => $moduleFields,
				'moduleGroupFieldList' => $moduleGroupFields,
			])->with('module', $module);
		} else {
        	return Redirect::back()->with('message', 'You are not authorized to use this functionality!');
        } 
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function edit($id)
	{
		//
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
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id)
	{
		if($this->permissionDetails('Modules','delete')) {
			$this->moduleDelete($id);
			return redirect()->route('modules.index');
		}else{
        	return Redirect::back()->with('message', 'You are not authorized to use this functionality!');
        }				
	}
	

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function massModuleAction(Request $request)
	{
		if($this->permissionDetails('Modules','delete')) {

			if(isset($request->selectedModules) && !empty($request->selectedModules))
			{
				$modulesIds = explode(',', $request->selectedModules);
				foreach ($modulesIds as $moduleKey => $moduleValue) {
					$this->moduleDelete($moduleValue);
				}
			}
			return redirect()->route('modules.index');
		}else{
        	return Redirect::back()->with('message', 'You are not authorized to use this functionality!');
        }				
	}

	public function moduleDelete($moduleId)
	{
		$module = Module::find($moduleId);
			
		if(isset($module->id)){
			$connectionStatus = LAHelper::moduleConnection($module->provider_id);
			if($connectionStatus['type']==='error')
			{
				Session::flash('type', $connectionStatus['type']);
	            Session::flash('msg', $connectionStatus['message']);
                return redirect()->route('modules.index');
			}

			//Delete Menu
			$menuItems = Menus::where('name', $module->name)->first();
			if(isset($menuItems)) {
				$menuItems->delete();
				Menus_database_manager::where('menu_id',$menuItems->id)->delete();
			}	
			
			// Delete Module Fields
			$module_fields = ModuleFields::where('module',$module->id)->delete();
			
			$sourcePath = resource_path('/views/' . $module->name_db);
			$destinationPath = storage_path('DynamicModules/'.$module->name_db.'_'.date("Ymd_his"));
			LAHelper::copyDynamicDirectory($sourcePath, $destinationPath);// Copy Resource Views directory
			\File::deleteDirectory(resource_path('/views/' . $module->name_db));
			
			copy(app_path('/Http/Controllers/'.$module->name.'Controller.php'), $destinationPath.'/'.$module->name.'Controller.php');
			// Delete Controller
			\File::delete(app_path('/Http/Controllers/'.$module->name.'Controller.php'));
			
			copy(app_path($module->model.'.php'), $destinationPath.'/'.$module->model.'.php');
			
			// Delete Model
			\File::delete(app_path($module->model.'.php'));
			// Modify Migration for Deletion
			// Find existing migration file
			$mfiles = scandir(base_path('database/migrations/'));
			$fileExistName = "";
			foreach ($mfiles as $mfile) {
				if(str_contains($mfile, "create_".$module->name_db."_table")) {
					$migrationClassName = ucfirst(camel_case("create_".$module->name_db."_table"));
					
					$templateDirectory = __DIR__.'/../../Helpers/stubs';

					$migrationData = file_get_contents($templateDirectory."/migration_removal.stub");
					$migrationData = str_replace("__migration_class_name__", $migrationClassName, $migrationData);
					$migrationData = str_replace("__db_table_name__", $module->name_db, $migrationData);
					file_put_contents(base_path('database/migrations/'.$mfile), $migrationData);
				}
			}
			
			// Delete Admin Routes
			if(LAHelper::laravel_ver() == 5.3) {
				$file_admin_routes = base_path("routes\web.php");
			} else {
				$file_admin_routes = base_path("app\Http\web.php");
			}

			while(LAHelper::getLineWithString($file_admin_routes, $module->name."Controller") != self::NEGATIVE_FIRST_VALUE) {
				$line = LAHelper::getLineWithString($file_admin_routes, $module->name.'Controller');
				$fileData = file_get_contents($file_admin_routes);
				$fileData = str_replace($line, "", $fileData);
				file_put_contents($file_admin_routes, $fileData);
			}
			if(LAHelper::getLineWithString($file_admin_routes, "=== ".$module->name." ===") != self::NEGATIVE_FIRST_VALUE) {
				$line = LAHelper::getLineWithString($file_admin_routes, "=== ".$module->name." ===");
				$fileData = file_get_contents($file_admin_routes);
				$fileData = str_replace($line, "", $fileData);
				file_put_contents($file_admin_routes, $fileData);
			}

			$fileData = file_get_contents($file_admin_routes);
			$fileData = preg_replace("/([A-Z a-z])*::([A-Z a-z])*\(.*{.\n}\);/","",$fileData);
			file_put_contents($file_admin_routes, $fileData);

			// delete module access permission from groups
			Module::removeGroupPermission($moduleId);

			// delete permission record from permission schema.
			Permission::where('name',$module->name)->delete();
			
			// Delete Module
			$module->delete();

			//
		}
	}
	
	/**
	 * Generate Modules Migrations
	 *
	 * @param  int  $module_id
	 * @return \Illuminate\Http\Response
	 */
	public function generate_migr($module_id)
	{
		$module = Module::find($module_id);
		$module = Module::get($module->name);
		CodeGenerator::generateMigration($module->name_db, true);
	}

	/**
	 * Generate Modules Migrations and CRUD Model
	 *
	 * @param  int  $module_id
	 * @return \Illuminate\Http\Response
	 */
	public function generate_migr_crud(Request $request)
	{
		$module = Module::find($request->module_id);
		$module = Module::get($module->name);
		// Generate Migration
		CodeGenerator::generateMigration($module->name_db, true);
		
		// Create Config for Code Generation
		$config = CodeGenerator::generateConfig($module->name,$module->fa_icon);

		// Generate CRUD
		if($request->crud_id == self::FIRST_VALUE)
		{
			/*kendo ui CRUD generation */
			CodeGenerator::createKendoController($config);
			CodeGenerator::createKendoModel($config);
			CodeGenerator::createKendoJs($config);
			CodeGenerator::createKendoViews($config);
			CodeGenerator::appendKendoRoutes($config);
		} else {
			/*lara admin CRUD generation */
			CodeGenerator::createController($config);
			CodeGenerator::createModel($config);
			CodeGenerator::createViews($config);
			CodeGenerator::appendRoutes($config);	
		}
		CodeGenerator::addMenu($config);
		CodeGenerator::addPermission($config);
		CodeGenerator::assignPermission($config,$this->roleId);
		
		// Set Module Generated = True
		$module = Module::find($request->module_id);
		$module->is_gen='1';
		$module->crud_type_id=$request->crud_id;
		$module->save();
		return back();

	}
/**
	 * Generate Modules Update(migrations and crud) not routes
	 *
	 * @param  int  $module_id
	 * @return \Illuminate\Http\Response
	 */
	public function generate_update($module_id,$crud_type_id)
	{
		
		$module = Module::find($module_id);
		$module = Module::get($module->name);
		
		// Generate Migration
		CodeGenerator::generateMigration($module->name_db, true);
		
		// Create Config for Code Generation
		$config = CodeGenerator::generateConfig($module->name,$module->fa_icon);
		// Generate CRUD
		if($crud_type_id == self::FIRST_VALUE)
		{
			/*kendo ui CRUD generation */
			CodeGenerator::createKendoController($config);
			CodeGenerator::createKendoModel($config);
			CodeGenerator::createKendoJs($config);
			CodeGenerator::createKendoViews($config);
			
			// Delete Admin Routes
			if(LAHelper::laravel_ver() == 5.3) {
				$file_admin_routes = base_path("routes\web.php");
			} else {
				$file_admin_routes = base_path("app\Http\web.php");
			}

			while(LAHelper::getLineWithString($file_admin_routes, $module->name."Controller") != self::NEGATIVE_FIRST_VALUE) {
				$line = LAHelper::getLineWithString($file_admin_routes, $module->name.'Controller');
				$fileData = file_get_contents($file_admin_routes);
				$fileData = str_replace($line, "", $fileData);
				file_put_contents($file_admin_routes, $fileData);
			}
			if(LAHelper::getLineWithString($file_admin_routes, "=== ".$module->name." ===") != self::NEGATIVE_FIRST_VALUE) {
				$line = LAHelper::getLineWithString($file_admin_routes, "=== ".$module->name." ===");
				$fileData = file_get_contents($file_admin_routes);
				$fileData = str_replace($line, "", $fileData);
				file_put_contents($file_admin_routes, $fileData);
			}

			$fileData = file_get_contents($file_admin_routes);
			$fileData = preg_replace("/([A-Z a-z])*::([A-Z a-z])*\(.*{.\n}\);/","",$fileData);
			file_put_contents($file_admin_routes, $fileData);
			CodeGenerator::appendKendoRoutes($config);
		} else {
			/*lara admin CRUD generation */
			CodeGenerator::createController($config);
			CodeGenerator::createModel($config);
			CodeGenerator::createViews($config);	
		}
		
		// Set Module Generated = True
		$module = Module::find($module_id);
		$module->is_gen='1';
		$module->save();
	}
	
	/**
	 * Set the model view_column
	 *
	 * @param  int  $module_id
	 * @param string $column_name
	 * @return \Illuminate\Http\Response
	 */
	public function set_view_col($module_id, $column_name){
		$module = Module::find($module_id);
		$module->view_col=$column_name;
		$module->save();

		return redirect()->route('modules.show', [$module_id]);
	}
	
	public function save_role_module_permissions(Request $request, $id)
	{
		$module = Module::find($id);
		
		foreach ($request->module as $key => $value) {
			$group_id = $key;
			$accessibility = array();	

			$hase_permissions = Group_permission::where('group_id', $group_id)
		   	->first();

		   	$permissions_data = unserialize($hase_permissions->permissions);

		   	if(is_array($value)){
				foreach ($value as $access_modifier => $access_value) {
					$accessibility[] = $access_modifier;
				}

				$permissions_data[$module->name] = $accessibility;
			}else{
				unset($permissions_data[$module->name]);
			}

			$group_permissions = Group_permission::find($group_id);
			$group_permissions->permissions = serialize($permissions_data);
			$group_permissions->save();

		}

        return redirect('modules/'.$id."#access");
	}
	
	public function save_module_field_sort(Request $request, $id)
	{
		$sort_array = $request->sort_array;
		try {
			foreach ($sort_array as $index => $field_id) {
				DB::table('module_fields')->where('id', $field_id)->update(['sort' => ($index + self::FIRST_VALUE)]);
			}
		}
		catch (\Exception $e) {
            $exceptionMessage = ConnectionManager::renderPdoException($e->getCode());
            return response()->json([
				'type' => 'error',
				"message" => $exceptionMessage
			]);
        }
		return response()->json([
			'type' => 'success',
			"message" => 'Fields Successfully Updated'
		]);
	}

	public function get_module_files(Request $request, $module_id)
	{
		$module = Module::find($module_id);
		
		$arr = array();
		$arr[] = "app/Http/Controllers/".$module->controller.".php";
		$arr[] = "app/".$module->model.".php";

		$viewdir=resource_path('views\\'.$module->name_db);
		
		if(is_dir($viewdir)){
			$views = scandir(resource_path('views/'.$module->name_db));
			foreach ($views as $view) {
				if($view != "." && $view != "..") {
					$arr[] = "resources/views/".$view;
				}
			}
		}

		// Find existing migration file
		$mfiles = scandir(base_path('database/migrations/'));
		$fileExistName = "";
		foreach ($mfiles as $mfile) {
			if(str_contains($mfile, "create_".$module->name_db."_table")) {
				$arr[] = 'database/migrations/' . $mfile;
			}
		}
		return response()->json([
			'files' => $arr
		]);
	}

	public function get_schema_fields(Request $request){
		$fields = array();
		$connectionStatus = LAHelper::moduleConnection($request->provider_id);
		if($connectionStatus['type']==='error')
		{
			Session::flash('type', $connectionStatus['type']);
            Session::flash('msg', $connectionStatus['message']);
            return redirect()->route('modules.index');
		}

		$schema_fields = DB::connection('mysqlDynamicConnector')->select("SELECT DISTINCT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME='".$request->schema_name."'");

		foreach ($schema_fields as $field) {
				$fields[] = $field->COLUMN_NAME;
		}

		return json_encode($fields);
	}

	public function get_schema_tables($providerId){
		$connectionStatus = LAHelper::moduleConnection($providerId);
		if($connectionStatus['type']==='error')
		{
			return json_encode($connectionStatus);
		}
		$tableList = DB::connection($this->dbconnector)->select('SHOW TABLES');
		$tableList = array_map('current',$tableList);
		$moduleTableList = Module::select('name_db')->get()->toArray();
		$moduleTableList = array_map('current',$moduleTableList);
		$tableList = array_diff($tableList, $moduleTableList);
		return json_encode(array_values($tableList));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function newModulestore(Request $request)
	{
		$tableName = $request->table_name;
		$moduleName = ucfirst($tableName);
		$popupCommand = (isset($request->existTablePopupCommand)) ? self::FIRST_VALUE : self::INIT_VALUE;
		$allowDelete = (isset($request->existTableAllowDeleteCommand)) ? self::FIRST_VALUE : self::INIT_VALUE;
		$allowEdit = (isset($request->existTableAllowEditCommand)) ? self::FIRST_VALUE : self::INIT_VALUE;
		$selectAllCheckbox = (isset($request->existTableSelectAllCommand)) ? self::FIRST_VALUE : self::INIT_VALUE;
		$module_id = Module::generateBase($moduleName, $request->icon,$request->existTableProvider,$popupCommand,$allowDelete,$allowEdit,$selectAllCheckbox);
		$connectionStatus = LAHelper::moduleConnection($request->existTableProvider);
		if($connectionStatus['type']==='error')
		{
			Session::flash('type', $connectionStatus['type']);
            Session::flash('msg', $connectionStatus['message']);
            return redirect()->route('modules.index');
		}
		$columnList = DB::connection($this->dbconnector)->select('DESCRIBE '.$tableName);
		if(isset($columnList) && isset($columnList[self::INIT_VALUE]))
		{
			$module = Module::find($module_id);
			$module->view_col=$columnList[self::FIRST_VALUE]->Field;
			$module->save();
		}
		foreach ($columnList as $columnListKey => $columnListValue)
		{
			if($columnListValue->Key === 'MUL')
			{
				$columnTitle = LAHelper::converColumnToTitle($columnListValue->Field,true);
			} else {
				$columnTitle = LAHelper::converColumnToTitle($columnListValue->Field);
			}
			$fieldObject = new stdClass();
			$moduleName = ucfirst($tableName);
			$isTableExist = self::FIRST_VALUE;
			$fieldObject->module_id = $module_id;
			$fieldObject->providerId = $request->existTableProvider;
			$fieldObject->label = $columnTitle;
			$fieldObject->colname = $columnListValue->Field;
			$fieldObject->defaultvalue = isset($columnListValue->Default)?$columnListValue->Default:'';
			$fieldObject->unique = false;
			$fieldObject->required = false;
			$fieldObject->minlength = self::INIT_VALUE;
			$fieldObject->visibility = self::FIRST_VALUE;
			$columnTypeLengthData = ModuleFieldTypes::getFieldTypeId($columnListValue->Type,$columnListValue->Key,$columnListValue->Field);
			$fieldObject->maxlength = $columnTypeLengthData['columnMaxLength'];
			$fieldObject->field_type = $columnTypeLengthData['columnTypeId'];
			$fieldObject->precision_value = $columnTypeLengthData['columnPrecision'];
			if($columnListValue->Key === 'MUL')
			{
				$primaryKeyDetails = DB::connection($this->dbconnector)->select("SELECT TABLE_NAME, COLUMN_NAME, CONSTRAINT_NAME, REFERENCED_TABLE_NAME, REFERENCED_COLUMN_NAME FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE WHERE TABLE_NAME = '".$tableName."' AND COLUMN_NAME = '".$columnListValue->Field."'");
				if(!empty($primaryKeyDetails))
				{
					$fieldObject->popup_value_type = 'table';
					$fieldObject->popup_vals_table = $primaryKeyDetails[self::INIT_VALUE]->REFERENCED_TABLE_NAME;
					$fieldObject->popup_field_id =$primaryKeyDetails[self::INIT_VALUE]->REFERENCED_COLUMN_NAME;
					$fieldObject->popup_field_name = $request->{$columnListValue->Field};
				} else {
					$fieldObject->popup_value_type = '';
					$fieldObject->popup_vals_table = '';
					$fieldObject->popup_field_id ='';
					$fieldObject->popup_field_name = '';
				}
				
			} else {
				$fieldObject->popup_value_type = '';
				$fieldObject->popup_vals_table = '';
				$fieldObject->popup_field_id ='';
				$fieldObject->popup_field_name = '';
			}
			if($columnListValue->Key !=='PRI')
				$fieldId = ModuleFields::createField($fieldObject,$isTableExist);
		}
		return redirect()->route('modules.show', [$module_id]);
	}

	public function moduleGroupStore(Request $request)
	{
		$moduleId = $request->groupModuleId;
		ModulesGrouping::where('module_id',$moduleId)->delete();
		if(isset($request->fieldObject))
		{
			
			foreach ($request->fieldObject as $fieldId => $fieldGroupArray) {
				$modulesGroupingObject = new ModulesGrouping();
				$modulesGroupingObject->module_id = $moduleId;
				$modulesGroupingObject->field_id = $fieldId;
				$modulesGroupingObject->sort = $fieldGroupArray['field_sort'];
				$modulesGroupingObject->save();
			}
		}
		return redirect('modules/'.$moduleId."#gridGroupBy");
	}

	public function getSchemaTablePopups(Request $request)
	{
		$foreignTableName = $request->table_name;
		$connectionStatus = LAHelper::moduleConnection($request->provider_id);
		if($connectionStatus['type']==='error')
		{
			return json_encode($connectionStatus);
		}
		$popupFieldDetails = array();
		$columnList = DB::connection($this->dbconnector)->select('DESCRIBE '.$foreignTableName);
		foreach ($columnList as $columnListKey => $columnListValue)
		{
			
			if('MUL' === $columnListValue->Key)
			{
				$primaryKeyDetails = DB::connection($this->dbconnector)->select("SELECT TABLE_NAME, COLUMN_NAME, CONSTRAINT_NAME, REFERENCED_TABLE_NAME, REFERENCED_COLUMN_NAME FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE WHERE TABLE_NAME = '".$foreignTableName."' AND COLUMN_NAME = '".$columnListValue->Field."'");
				if(isset($primaryKeyDetails[self::INIT_VALUE]->REFERENCED_TABLE_NAME) && !empty($primaryKeyDetails[self::INIT_VALUE]->REFERENCED_TABLE_NAME))
				{
					foreach ($primaryKeyDetails as $primaryKey => $primaryValue) {
						$primaryColumnList = DB::connection($this->dbconnector)->select('DESCRIBE '.$primaryValue->REFERENCED_TABLE_NAME);
						$primaryTableColumn = array();
						foreach ($primaryColumnList as $primaryColumnListKey => $primaryColumnListValue) {
							$primaryTableColumn[] = $primaryColumnListValue->Field;
						}
					}
					$tempPopupData['fieldName'] = $columnListValue->Field;
					$tempPopupData['popupTable'] = $primaryKeyDetails[self::INIT_VALUE]->REFERENCED_TABLE_NAME;
					$tempPopupData['popupTableId'] = $primaryKeyDetails[self::INIT_VALUE]->REFERENCED_COLUMN_NAME;
					$tempPopupData['popupTableFields'] = $primaryTableColumn;
					$popupFieldDetails[] = $tempPopupData;
				}
			}
		}
		return json_encode($popupFieldDetails);
		
	}

	public function moduleActionStore(Request $request, $id)
	{
		try {
			if(isset($request->actionName))
			{
				$module = Module::where('id', $id)->orderBy('id', 'desc')->first();
				switch ($request->actionName) {
					case 'moduleActionAllowDelete':
						if($request->state == "true")
						{
							echo "if";
							$module->allow_delete = self::FIRST_VALUE;
						} else {
							echo "else";
							$module->allow_delete = self::INIT_VALUE;
						}
						break;
					case 'moduleActionPopup':
						if($request->state == "true")
						{
							$module->popup = self::FIRST_VALUE;
						} else {
							$module->popup = self::INIT_VALUE;
						}
						break;

					case 'moduleActionSelectAll':
						if($request->state == "true")
						{
							$module->select_all = self::FIRST_VALUE;
						} else {
							$module->select_all = self::INIT_VALUE;
						}
						break;
					case 'moduleActionAllowEdit':
						if($request->state == "true")
						{
							$module->allow_edit = self::FIRST_VALUE;
						} else {
							$module->allow_edit = self::INIT_VALUE;
						}
						break;
					
					default:
						# code...
						break;
				}
				$module->save();
			}
		}
		catch (\Exception $e) {
            $exceptionMessage = ConnectionManager::renderPdoException($e->getCode());
            return response()->json([
				'type' => 'error',
				"message" => $exceptionMessage
			]);
        }
		return response()->json([
			'type' => 'success',
			"message" => 'Action Successfully Updated'
		]);
	}

	public function getModulesSnippet($moduleId)
    {
        $modulesSnippetValues = ModulesSnippet::select('modules_snippet.*')->where('module_id',$moduleId)->get()->toArray();
        $modulesSnippetAddValues[0]          = array("id" => 0,"placeholder" => '__SNIPPET__JS__FILE__',"snippet" => '',"module_id"=>$moduleId);
        $modulesSnippetDetails = array_merge($modulesSnippetAddValues, $modulesSnippetValues);
        return json_encode($modulesSnippetDetails);
    }
					

    /**
     * Update the specified modules_snippet in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateModulesSnippet(Request $request)
    {
        if(Module::hasAccess("Modules_snippet", "manage")) {
            if ($request->id == 0) {
                try {
                	$moduleSnippetDetails = new ModulesSnippet();
                    $moduleSnippetDetails->placeholder = $request->place_holder;
                    $moduleSnippetDetails->module_id = $request->module_id;
		            $moduleSnippetDetails->snippet = $request->snippet_file;
		            $moduleSnippetDetails->save();
                    return array("type" => "success", "message" => 'Module Snippet Inserted');
                } catch (\Exception $e) {
                    $exceptionMessage = ConnectionManager::renderPdoException($e->getCode());
                    return array("type" => "error", "message" => $exceptionMessage);
                }

            } else {
                try {
                    $moduleSnippetDetails = ModulesSnippet::find($request->id);
		            $moduleSnippetDetails->placeholder = $request->place_holder;
		            $moduleSnippetDetails->snippet = $request->snippet_file;
		            $moduleSnippetDetails->save();
                    return array("type" => "success", "message" => 'Module Snippet Updated');
                } catch (\Exception $e) {
                    $exceptionMessage = ConnectionManager::renderPdoException($e->getCode());
                    return array("type" => "error", "message" => $exceptionMessage);

                }
            }
        }
    }

    /**
     * Remove the specified modules_snippet from storage.
     *
     * @param  object  $request
     * @return \Illuminate\Http\Response
     */
    public function deleteModulesSnippet(Request $request)
    {
        $callback = $request->callback;
        if(Module::hasAccess("Modules_snippet", "delete")) {
            ModulesSnippet::find($request->id)->delete();
            return $callback."(".json_encode($request).")";
        } else {
            return $callback."(".json_encode($request).")";
        }
    }

}
