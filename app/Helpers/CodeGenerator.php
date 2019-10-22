<?php
namespace App\Helpers;

use Exception;
use Illuminate\Filesystem\Filesystem;
use App\Helpers\LAHelper;
use App\Helpers\KendoColumnMaker;
use App\Menus_database_manager;
use App\Module;
use App\ModulesGrouping;
use App\ModulesSnippet;
use App\ModuleFields;
use App\ModuleFieldTypes;
use App\Menus;
use App\Permission;
use App\Group_permission;
use DB;

class CodeGenerator
{
	const INIT_VALUE      = 0;
	const FIRST_VALUE     = 1;
    /**
	* Generate Controller file
    * if $generate is true then create file from module info from DB
    * $comm is command Object from Migration command
	* CodeGenerator::generateMigration($table, $generateFromTable);
	**/
    /*kendo controller */
    public static function createKendoController($config, $comm = null) {

        $templateDirectory = __DIR__.'/stubs/kendoui';

        LAHelper::log("info", "Creating controller...", $comm);
        $md = file_get_contents($templateDirectory."/controller.stub");
        
        $md = str_replace("__controller_class_name__", $config->controllerName, $md);
        $md = str_replace("__model_name__", $config->modelName, $md);
        $md = str_replace("__module_name_singular__", LAHelper::convertStringToFunctionName($config->modelName,[]), $md);
        $md = str_replace("__module_name_camel_case__", LAHelper::convertStringToFunctionName($config->modelName,true), $md);
        $md = str_replace("__module_name__", $config->moduleName, $md);
        $md = str_replace("__view_column__", $config->module->view_col, $md);

        //primary key
        $md = str_replace("__primary_key__", $config->primary_key, $md);

        // Listing columns
        $listing_cols = "";
        foreach ($config->module->fields as $field) {
            $listing_cols .= "'".$field['colname']."', ";
        }
        $listing_cols = trim($listing_cols, ", ");

        $md = str_replace("__listing_cols__", $listing_cols, $md);
        $md = str_replace("__view_folder__", $config->dbTableName, $md);
        $md = str_replace("__route_resource__", $config->dbTableName, $md);
        $md = str_replace("__db_table_name__", $config->dbTableName, $md);
        $md = str_replace("__singular_var__", $config->singularVar, $md);

        $kendoControllerFunction = '';
        $templateDefineArray = array("Dropdown", "Multiselect","AutoComplete");
        $moduleDetails = Module::get($config->moduleName);
        foreach ($config->module->fields as $field) {
            $field_type = ModuleFieldTypes::find($moduleDetails->fields[$field['colname']]['field_type']);
            if (in_array($field_type->name, $templateDefineArray))
            {  
               $kendoControllerFunction .= KendoColumnMaker::kendo_controller_Create($moduleDetails, $field['colname']);
               
            }
        }
        $md = str_replace("/*__dropdown_function_var__*/", $kendoControllerFunction, $md);

        if(isset($config->module) && $config->module->popup==self::FIRST_VALUE )
        {
            $targetActionNodeFunction = KendoColumnMaker::target_action_node_function();
            $targetActionNodeFunction = trim($targetActionNodeFunction);
            $md = str_replace("/*__target__table__node__*/", $targetActionNodeFunction, $md);
        } else {
            $md = str_replace("/*__target__table__node__*/", "", $md);
        }

        file_put_contents(base_path('app/Http/Controllers/'.$config->controllerName.".php"), $md);
    }
    
	/*lara controller */
    public static function createController($config, $comm = null) {

        $templateDirectory = __DIR__.'/stubs';

        LAHelper::log("info", "Creating controller...", $comm);
        $md = file_get_contents($templateDirectory."/controller.stub");
        
        $md = str_replace("__controller_class_name__", $config->controllerName, $md);
        $md = str_replace("__model_name__", $config->modelName, $md);
        $md = str_replace("__module_name__", $config->moduleName, $md);
        $md = str_replace("__view_column__", $config->module->view_col, $md);
        $md = str_replace("__primary_key__", $config->primary_key, $md);
        // Listing columns
        $listing_cols = "";
        foreach ($config->module->fields as $field) {
            $listing_cols .= "'".$field['colname']."', ";
        }
        $listing_cols = trim($listing_cols, ", ");

        $md = str_replace("__listing_cols__", $listing_cols, $md);
        $md = str_replace("__view_folder__", $config->dbTableName, $md);
        $md = str_replace("__route_resource__", $config->dbTableName, $md);
        $md = str_replace("__db_table_name__", $config->dbTableName, $md);
        $md = str_replace("__singular_var__", $config->singularVar, $md);

        file_put_contents(base_path('app/Http/Controllers/'.$config->controllerName.".php"), $md);
    }

    /*kendo model */
    public static function createKendoModel($config, $comm = null) {

        $templateDirectory = __DIR__.'/stubs/kendoui';

        LAHelper::log("info", "Creating model...", $comm);
        $md = file_get_contents($templateDirectory."/model.stub");

        $md = str_replace("__model_class_name__", $config->modelName, $md);
        $md = str_replace("__db_table_name__", $config->dbTableName, $md);
        $md = str_replace("__primary_key__", $config->primary_key, $md);

        file_put_contents(base_path('app/'.$config->modelName.".php"), $md);
    }


    /*lara model */
    public static function createModel($config, $comm = null) {

        $templateDirectory = __DIR__.'/stubs/kendoui';

        LAHelper::log("info", "Creating model...", $comm);
        $md = file_get_contents($templateDirectory."/model.stub");

        $md = str_replace("__model_class_name__", $config->modelName, $md);
        $md = str_replace("__db_table_name__", $config->dbTableName, $md);
        $md = str_replace("__primary_key__", $config->primary_key, $md);
        file_put_contents(base_path('app/'.$config->modelName.".php"), $md);
    }

    /*kendo view */
    public static function createKendoViews($config, $comm = null) {

        $templateDirectory = __DIR__.'/stubs/kendoui';

        LAHelper::log("info", "Creating views...", $comm);
        // Create Folder
        @mkdir(base_path("resources/views/".$config->dbTableName), 0777, true);

        // ============================ Listing / Index ============================
        $md = file_get_contents($templateDirectory."/views/index.blade.stub");

        $md = str_replace("__module_name__", $config->moduleName, $md);
        $md = str_replace("__module_name_singular__", LAHelper::convertStringToFunctionName($config->modelName,[]), $md);
        $md = str_replace("__module_name_camel_case__", LAHelper::convertStringToFunctionName($config->modelName,true), $md);
        $md = str_replace("__db_table_name__", $config->dbTableName, $md);
        $md = str_replace("__controller_class_name__", $config->controllerName, $md);
        $md = str_replace("__singular_var__", $config->singularVar, $md);
        $md = str_replace("__singular_cap_var__", $config->singularCapitalVar, $md);
        $md = str_replace("__module_name_2__", $config->moduleName2, $md);
        $md = str_replace("__primary_key__", $config->primary_key, $md);

        $moduleDetails = Module::get($config->moduleName);
        $moduleSnippetFile = ModulesSnippet::getModuleSnippet($moduleDetails->id);
        $kendoSnippetFile = '';
        foreach ($moduleSnippetFile as $fileKey => $fileValue) {
            if($fileValue['placeholder'] == '__SNIPPET__JS__FILE__')
            {
                $kendoSnippetFile = KendoColumnMaker::kendoSnippetFile($fileValue['snippet'],$config->moduleName);
                $md = str_replace('{{-- '.$fileValue['placeholder'].' --}}', $kendoSnippetFile, $md);
            }
        }
        $md = str_replace('{{-- __SNIPPET__JS__FILE__ --}}', $kendoSnippetFile, $md);

        if(isset($config->module) && $config->module->select_all==self::FIRST_VALUE )
        {
            $md = str_replace("display:none;", "display:block;", $md);
        }

        file_put_contents(base_path('resources/views/'.$config->dbTableName.'/index.blade.php'), $md);
    }

    /*lara view creation*/
    public static function createViews($config, $comm = null) {

        $templateDirectory = __DIR__.'/stubs';

        LAHelper::log("info", "Creating views...", $comm);
        // Create Folder
        @mkdir(base_path("resources/views/".$config->dbTableName), 0777, true);

        // ============================ Listing / Index ============================
        $md = file_get_contents($templateDirectory."/views/index.blade.stub");

        $md = str_replace("__module_name__", $config->moduleName, $md);
        $md = str_replace("__db_table_name__", $config->dbTableName, $md);
        $md = str_replace("__controller_class_name__", $config->controllerName, $md);
        $md = str_replace("__singular_var__", $config->singularVar, $md);
		$md = str_replace("__singular_cap_var__", $config->singularCapitalVar, $md);
        $md = str_replace("__module_name_2__", $config->moduleName2, $md);
        $md = str_replace("__primary_key__", $config->primary_key, $md);
        // Listing columns
        $inputFields = "";
        foreach ($config->module->fields as $field) {
            $inputFields .= "\t\t\t\t\t@la_input($"."module, '".$field['colname']."')\n";
        }
        $inputFields = trim($inputFields);
        $md = str_replace("__input_fields__", $inputFields, $md);

        file_put_contents(base_path('resources/views/'.$config->dbTableName.'/index.blade.php'), $md);

        // ============================ Edit ============================
        $md = file_get_contents($templateDirectory."/views/edit.blade.stub");

        $md = str_replace("__module_name__", $config->moduleName, $md);
        $md = str_replace("__db_table_name__", $config->dbTableName, $md);
        $md = str_replace("__controller_class_name__", $config->controllerName, $md);
        $md = str_replace("__singular_var__", $config->singularVar, $md);
		$md = str_replace("__singular_cap_var__", $config->singularCapitalVar, $md);
        $md = str_replace("__module_name_2__", $config->moduleName2, $md);
        $md = str_replace("__primary_key__", $config->primary_key, $md);
        
        // Listing columns
        $inputFields = "";
        foreach ($config->module->fields as $field) {
            $inputFields .= "\t\t\t\t\t@la_input($"."module, '".$field['colname']."')\n";
        }
        $inputFields = trim($inputFields);
        $md = str_replace("__input_fields__", $inputFields, $md);

        file_put_contents(base_path('resources/views/'.$config->dbTableName.'/edit.blade.php'), $md);

        // ============================ Show ============================
        $md = file_get_contents($templateDirectory."/views/show.blade.stub");

        $md = str_replace("__module_name__", $config->moduleName, $md);
        $md = str_replace("__db_table_name__", $config->dbTableName, $md);
        $md = str_replace("__singular_var__", $config->singularVar, $md);
        $md = str_replace("__singular_cap_var__", $config->singularCapitalVar, $md);
		$md = str_replace("__module_name_2__", $config->moduleName2, $md);
        $md = str_replace("__primary_key__", $config->primary_key, $md);

        // Listing columns
        $displayFields = "";
        foreach ($config->module->fields as $field) {
            $displayFields .= "\t\t\t\t\t\t@la_display($"."module, '".$field['colname']."')\n";
        }
        $displayFields = trim($displayFields);
        $md = str_replace("__display_fields__", $displayFields, $md);

        file_put_contents(base_path('resources/views/'.$config->dbTableName.'/show.blade.php'), $md);
    }

    /* kendo route*/
    public static function appendKendoRoutes($config, $comm = null) {

        $templateDirectory = __DIR__.'/stubs/kendoui';

        LAHelper::log("info", "Appending routes...", $comm);
        if(LAHelper::laravel_ver() == 5.3) {
			$routesFile = base_path('routes/web.php');
		} else {
			$routesFile = app_path('Http/web.php');
		}

		/*$contents = file_get_contents($routesFile);
		$contents = str_replace('});', '', $contents);
		file_put_contents($routesFile, $contents);*/
		
        $md = file_get_contents($templateDirectory."/routes.stub");

        $md = str_replace("__module_name__", $config->moduleName, $md);
        $md = str_replace("__module_name_singular__", LAHelper::convertStringToFunctionName($config->modelName,[]), $md);
        $md = str_replace("__module_name_camel_case__", LAHelper::convertStringToFunctionName($config->modelName,true), $md);
        $md = str_replace("__controller_class_name__", $config->controllerName, $md);
        $md = str_replace("__db_table_name__", $config->dbTableName, $md);
        $md = str_replace("__singular_var__", $config->singularVar, $md);
        $md = str_replace("__singular_cap_var__", $config->singularCapitalVar, $md);

        $kendoRoutePath = '';
        $templateDefineArray = array("Dropdown", "Multiselect","AutoComplete");
        $moduleDetails = Module::get($config->moduleName);

        foreach ($config->module->fields as $field) {
            $field_type = ModuleFieldTypes::find($moduleDetails->fields[$field['colname']]['field_type']);
            if (in_array($field_type->name, $templateDefineArray))
            {  
                $kendoRoutePath .= 'Route::get("'.$config->dbTableName.'/get'.LAHelper::convertStringToFunctionName($field['colname'],true).'List", "'.$config->controllerName.'@get'.LAHelper::convertStringToFunctionName($field['colname'],true).'List");
                ';
               
            }
        }
        if(isset($config->module) && $config->module->popup==self::FIRST_VALUE )
        {
            $kendoRoutePath .= 'Route::get("'.$config->dbTableName.'/getTargetNodeDetails", "'.$config->controllerName.'@getTargetNodeDetails");
            ';
            $kendoRoutePath .= 'Route::get("'.$config->dbTableName.'/getTargetTables", "HtmldomController@getTargetTables");
            ';
            $kendoRoutePath .= 'Route::get("'.$config->dbTableName.'/getTargetTableColumns", "HtmldomController@getTargetTableColumns");
            ';
        }
        
        $md = str_replace("Route::resource", $kendoRoutePath." Route::resource", $md);
        file_put_contents($routesFile, $md, FILE_APPEND);
    }

    /*lara view route*/
    public static function appendRoutes($config, $comm = null) {

        $templateDirectory = __DIR__.'/stubs';

        LAHelper::log("info", "Appending routes...", $comm);
        if(LAHelper::laravel_ver() == 5.3) {
            $routesFile = base_path('routes/web.php');
        } else {
            $routesFile = app_path('Http/web.php');
        }

        /*$contents = file_get_contents($routesFile);
        $contents = str_replace('});', '', $contents);
        file_put_contents($routesFile, $contents);*/
        
        $md = file_get_contents($templateDirectory."/routes.stub");

        $md = str_replace("__module_name__", $config->moduleName, $md);
        $md = str_replace("__controller_class_name__", $config->controllerName, $md);
        $md = str_replace("__db_table_name__", $config->dbTableName, $md);
        $md = str_replace("__singular_var__", $config->singularVar, $md);
        $md = str_replace("__singular_cap_var__", $config->singularCapitalVar, $md);

        file_put_contents($routesFile, $md, FILE_APPEND);
    }

    /* kendo js*/
    public static function createKendoJs($config, $comm = null) {

        $templateDirectory = __DIR__.'/stubs/kendoui';

        LAHelper::log("info", "Creating js...", $comm);
        // Create Folder
        @mkdir(base_path("public/assets/js/custom_js/".$config->moduleName), 0777, true);

        // ============================ Listing / Index ============================
        $md = file_get_contents($templateDirectory."/js.stub");

        $md = str_replace("__module_name__", $config->moduleName, $md);
        $md = str_replace("__module_name_singular__", LAHelper::convertStringToFunctionName($config->modelName,[]), $md);
        $md = str_replace("__module_name_camel_case__", LAHelper::convertStringToFunctionName($config->modelName,true), $md);
        $md = str_replace("__db_table_name__", $config->dbTableName, $md);
        $md = str_replace("__controller_class_name__", $config->controllerName, $md);
        $md = str_replace("__singular_var__", $config->singularVar, $md);
        $md = str_replace("__singular_cap_var__", $config->singularCapitalVar, $md);
        $md = str_replace("__module_name_2__", $config->moduleName2, $md);
        $md = str_replace("__primary_key__", $config->primary_key, $md);
        // Listing columns
        $searchParam = "";
        $inputFields = "";
        $templateGrid = "";
        $parameterMap = "";
        $schemaGridTemplate= "";
        $inputGroupFields = "";
        $groupHeaderFuncation = "";
        $templateDefineArray = array("Date", "Datetime", "Dropdown","Multiselect","Textarea","Checkbox","Decimal","Float","Integer","Radio","Password","Currency","AutoComplete");
        $parameterMapDefineArray = array("Date", "Datetime");
        $searchDefineArray = array("String", "Dropdown","Multiselect","Address","Email","Name","TextField","Textarea","URL","AutoComplete");
        $moduleDetails = Module::get($config->moduleName);
        $moduleGroupFields = ModulesGrouping::
            select('modules_grouping.*','module_fields.colname','module_fields.popup_field_name','module_fields.label','module_fields.popup_vals')
            ->join('module_fields','module_fields.id','modules_grouping.field_id')
            ->where('module_id',$moduleDetails->id)
            ->get()->toArray();

        foreach ($moduleGroupFields as $fieldKey => $fieldValue) {
            $inputGroupFields .= KendoColumnMaker::kendoGroupByAction($fieldValue['colname'],$fieldValue['sort']).',';
            if(starts_with($fieldValue['popup_vals'], "@"))
            {
                $groupHeaderFuncation .= KendoColumnMaker::kendoGroupHeaderFunction($fieldValue,$config->moduleName);
            }
        }
        $inputGroupFields = trim($inputGroupFields);
        $md = str_replace("/*__module_group_by_action__*/", $inputGroupFields, $md);

        $groupHeaderFuncation = trim($groupHeaderFuncation);
        $md = str_replace("/*__kendo_group_by_function__*/", $groupHeaderFuncation, $md);

        if(isset($config->module) && $config->module->allow_delete==self::FIRST_VALUE )
        {
            $inputFields .= KendoColumnMaker::kendo_input_command("Delete");
        }
        foreach ($config->module->fields as $field) {
            $inputFields .= KendoColumnMaker::kendo_input($moduleDetails,$moduleGroupFields, $field['colname']).',';

            $field_type = ModuleFieldTypes::find($moduleDetails->fields[$field['colname']]['field_type']);
            if (in_array($field_type->name, $templateDefineArray))
            {  
               $templateGrid .= KendoColumnMaker::kendo_template($moduleDetails, $field['colname']).' ';
               
            }
            if (in_array($field_type->name, $parameterMapDefineArray))
            {  
               $parameterMap .= KendoColumnMaker::kendo_parameter_map($moduleDetails, $field['colname']).' ';
               
            }
            if (in_array($field_type->name, $searchDefineArray))
            {  
                if($field['visibility'] ==self::FIRST_VALUE) 
                {
                    $searchParam .= KendoColumnMaker::kendo_search_param($field['colname']).' ';
                }
               
            }

            $schemaGridTemplate .= KendoColumnMaker::kendo_schema_fields($moduleDetails, $field['colname']).' ';
        }
        if(isset($config->module) && $config->module->popup==self::FIRST_VALUE )
        {
            $inputFields .= KendoColumnMaker::kendo_input_command("Popup");
            $kendoInputAction = KendoColumnMaker::kendo_action_function();
            $kendoInputAction = trim($kendoInputAction);
            $md = str_replace("/*__input_action_function__*/", $kendoInputAction, $md);
            $targetActionGrid = KendoColumnMaker::target_action_grid();
            $targetActionGrid = trim($targetActionGrid);
            $md = str_replace("/*__target_action_grid__*/", $targetActionGrid, $md);
        } else {
            $md = str_replace("/*__input_action_function__*/", "", $md);
            $md = str_replace("/*__target_action_grid__*/", "", $md);
        }

        if(isset($config->module) && $config->module->allow_edit==self::INIT_VALUE )
        {
            $md = str_replace("editable:true", "editable:false", $md);
        }

        if(isset($config->module) && $config->module->select_all==self::FIRST_VALUE )
        {
            $kendoSelectAllColumnAction = KendoColumnMaker::kendoSelectAllColumn($config->primary_key);
            $kendoSelectAllColumnAction = trim($kendoSelectAllColumnAction);
            $md = str_replace("/*__module_select_checkbox__*/", $kendoSelectAllColumnAction, $md);
            $kendoSelectFunctionColumnAction = KendoColumnMaker::kendoSelectFunctionColumn($config->moduleName,$config->primary_key);
            $kendoSelectFunctionColumnAction = trim($kendoSelectFunctionColumnAction);
            $md = str_replace("/*__module_select_checkbox_function__*/", $kendoSelectFunctionColumnAction, $md);

            $kendoBatchActionFunction = KendoColumnMaker::kendoBatchActionFunction($config->moduleName,$config->primary_key);
            $kendoBatchActionFunction = trim($kendoBatchActionFunction);

            $md = str_replace("/*__kendo_batch_function__*/", $kendoBatchActionFunction, $md);

            $md = str_replace("/*dataBound: onDataBound,*/", "dataBound: onDataBound,", $md);
        } else {
            $replceString = array("/*__module_select_checkbox__*/","/*__module_select_checkbox_function__*/","/*__kendo_batch_function__*/");
            $md = str_replace($replceString, '', $md);
            $md = str_replace("var checkedIds = {};", '', $md);
            $md = str_replace("/*dataBound: onDataBound,*/", '', $md);
        }

        $inputFields = trim($inputFields);
        $md = str_replace("__input_fields__", $inputFields, $md);

        $templateGrid = trim($templateGrid);
        $md = str_replace("/*__template_editor__*/", $templateGrid, $md);

        $schemaGridTemplate = trim($schemaGridTemplate);
        $md = str_replace("/*schema_modle_fields*/", $schemaGridTemplate, $md);

        $parameterMap = trim($parameterMap);
        $md = str_replace("/*__parameter_map__*/", $parameterMap, $md);

        $searchParam = trim($searchParam);
        $md = str_replace("/*__search_Param__*/", $searchParam, $md);
        
        file_put_contents(base_path('public/assets/js/custom_js/'.$config->moduleName.'/'.$config->moduleName.'.js'), $md);

        $moduleDetails = Module::get($config->moduleName);
        $moduleSnippetFile = ModulesSnippet::getModuleSnippet($moduleDetails->id);
        foreach ($moduleSnippetFile as $fileKey => $fileValue) {
            if($fileValue['placeholder'] == '__SNIPPET__JS__FILE__')
            {
                $snippetFilePath = base_path('public/assets/js/custom_js/'.$config->moduleName.'/'.$fileValue['snippet'].'');
                if (!file_exists($snippetFilePath)) {
                    copy($templateDirectory."/snippet.stub", base_path('public/assets/js/custom_js/'.$config->moduleName.'/'.$fileValue['snippet'].''));
                }
            }
        }
    }
    public static function addMenu($config, $comm = null) {

        // $templateDirectory = __DIR__.'/stubs';

        LAHelper::log("info", "Appending Menu...", $comm);
        if(Menus::where("url", $config->dbTableName)->count() == self::INIT_VALUE) {
            try {
                $menuObject = new Menus();
                $menuObject->name = $config->moduleName;
                $menuObject->url = $config->moduleName;
                $menuObject->icon = "fa ".$config->fa_icon;
                $menuObject->type = 'module';
                $menuObject->parent = self::INIT_VALUE;
                $menuObject->save();

                $menuId = $menuObject->id;
                $providerId = $config->module->provider_id;
                $MenusDatabaseManager = new Menus_database_manager();
                $MenusDatabaseManager->menu_id = $menuId;
                $MenusDatabaseManager->provider_id = $providerId;
                $MenusDatabaseManager->priority = self::FIRST_VALUE;
                $MenusDatabaseManager->save();
            } catch (\Exception $e) {
                $exceptionMessage = ConnectionManager::renderPdoException($e->getCode());
                return array("type" => "error", "message" => $exceptionMessage);
            }
        } 
    }

    public static function addPermission($config, $comm = null){

        if(Permission::where("name", $config->moduleName)->count() == self::INIT_VALUE) {
            $permission = new Permission();
            $permission->name = $config->moduleName;
            $permission->action = 'a:4:{i:0;s:6:"access";i:1;s:6:"manage";i:2;s:3:"add";i:3;s:6:"delete";}';
            $permission->description = "Ability to access, manage, add and delete $config->moduleName";
            $permission->status = self::FIRST_VALUE;
            $permission->save();
        }  
    }

    public static function assignPermission($config, $group_id = self::FIRST_VALUE, $comm = null){

        $adminGroupID = self::FIRST_VALUE;
        $groups = array($adminGroupID);

        if ($group_id != $adminGroupID){
            $groups[] = $group_id;  
        }

        $module_access[$config->moduleName] = array("access","add","manage","delete");

        foreach ($groups as $groupID) {
            
            $hase_permissions = Group_permission::where('group_id', $groupID)->first();

            $permissions_data = unserialize($hase_permissions->permissions);
            $permissions_data = array_merge($permissions_data,$module_access);

            $group_permissions = Group_permission::find($groupID);
            $group_permissions->permissions = serialize($permissions_data);
            $group_permissions->save();
        }   

        return true;    

    }

	/**
	* Generate migration file
    * if $generate is true then create file from module info from DB
    * $comm is command Object from Migration command
	* CodeGenerator::generateMigration($table, $generateFromTable);
	**/
	public static function generateMigration($table, $generate = false, $comm = null)
	{
		$filesystem = new Filesystem();

        if(starts_with($table, "create_")) {
            $tname = str_replace("create_", "",$table);
            $table = str_replace("_table", "",$tname);
        }

        $modelName = ucfirst($table);
        $tableP = strtolower($table);
        $tableS = strtolower($table);
        $migrationName = 'create_'.$tableP.'_table';
        $migrationFileName = date("Y_m_d_His_").$migrationName.".php";
        $migrationClassName = ucfirst(camel_case($migrationName));
        $dbTableName = $tableP;
        $moduleName = ucfirst($table);

		LAHelper::log("info", "Model:\t   ".$modelName, $comm);
		LAHelper::log("info", "Module:\t   ".$moduleName, $comm);
		LAHelper::log("info", "Table:\t   ".$dbTableName, $comm);
		LAHelper::log("info", "Migration: ".$migrationName."\n", $comm);

        // Reverse migration generation from table
        $generateData = "";
        $viewColumnName = "view_column_name e.g. name";

		// fa_icon
		$faIcon = "fa-cube";

        if($generate) {
            // check if table, module and module fields exists
            $module = Module::get($moduleName);
            if(isset($module)) {
				LAHelper::log("info", "Module exists :\t   ".$moduleName, $comm);

                $viewColumnName = $module->view_col;
				$faIcon = $module->fa_icon;

                $ftypes = ModuleFieldTypes::getFieldTypeValue();
                foreach ($module->fields as $field) {
                    $ftype = $ftypes[$field['field_type']];
                    $unique = "false";
                    if($field['unique']) {
                        $unique = "true";
                    }
                    $dvalue = "";
                    if($field['defaultvalue'] != "") {
                        if(starts_with($field['defaultvalue'], "[")) {
                            $dvalue = $field['defaultvalue'];
                        } else {
                            $dvalue = '"'.$field['defaultvalue'].'"';
                        }
                    } else {
                        $dvalue = '""';
                    }
                    $minlength = $field['minlength'];
                    $maxlength = $field['maxlength'];
                    $required = "false";
                    if($field['required']) {
                        $required = "true";
                    }
                    $values = "";
                    if($field['popup_vals'] != "") {
                        if(starts_with($field['popup_vals'], "[")) {
                            $values = ', '.$field['popup_vals'];
                        } else {
                            $values = ', "'.$field['popup_vals'].'"';
                        }
                    }
                    $generateData .= '["'.$field['colname'].'", "'.$field['label'].'", "'.$ftype.'", '.$unique.', '.$dvalue.', '.$minlength.', '.$maxlength.', '.$required.''.$values.'],'."\n            ";
                }
                $generateData = trim($generateData);

                // Find existing migration file
                $mfiles = scandir(base_path('database/migrations/'));
                // print_r($mfiles);
                $fileExists = false;
                $fileExistName = "";
                foreach ($mfiles as $mfile) {
                    if(str_contains($mfile, $migrationName)) {
                        $fileExists = true;
                        $fileExistName = $mfile;
                    }
                }
                if($fileExists) {
					LAHelper::log("info", "Replacing old migration file: ".$fileExistName, $comm);
                    $migrationFileName = $fileExistName;
                }
            } else {
				LAHelper::log("error", "Module ".$moduleName." doesn't exists; Cannot generate !!!", $comm);
            }
        }

        $templateDirectory = __DIR__.'/stubs';

        try {
            LAHelper::log("line", "Creating migration...", $comm);
            $migrationData = file_get_contents($templateDirectory."/migration.stub");

            $migrationData = str_replace("__migration_class_name__", $migrationClassName, $migrationData);
            $migrationData = str_replace("__db_table_name__", $dbTableName, $migrationData);
            $migrationData = str_replace("__module_name__", $moduleName, $migrationData);
            $migrationData = str_replace("__model_name__", $modelName, $migrationData);
            $migrationData = str_replace("__view_column__", $viewColumnName, $migrationData);
			$migrationData = str_replace("__fa_icon__", $faIcon, $migrationData);
            $migrationData = str_replace("__generated__", $generateData, $migrationData);

            file_put_contents(base_path('database/migrations/'.$migrationFileName), $migrationData);

        } catch (Exception $e) {
            throw new Exception("Unable to generate migration for ".$table." : ".$e->getMessage(), self::FIRST_VALUE);
        }
        LAHelper::log("info", "Migration done: ".$migrationFileName."\n", $comm);
	}

    // $config = CodeGenerator::generateConfig($module_name);
    public static function generateConfig($module, $icon)
    {
        $config = array();
        $config = (object) $config;

        if(starts_with($module, "create_")) {
            $tname = str_replace("create_", "",$module);
            $module = str_replace("_table", "",$tname);
        }

        $config->modelName = ucfirst($module);
        $tableP = strtolower($module);
        $tableS = strtolower($module);
        $config->dbTableName = $tableP;
        $config->fa_icon = $icon;
        $config->moduleName = ucfirst($module);
		$config->moduleName2 = str_replace('_', ' ', ucfirst($module));
        $config->controllerName = ucfirst($module)."Controller";
        $config->singularVar = strtolower($module);
        $config->singularCapitalVar = str_replace('_', ' ', ucfirst($module));
        $module = Module::get($config->moduleName);
        if(!isset($module->id)) {
            throw new Exception("Please run 'php artisan migrate' for 'create_".$config->dbTableName."_table' in order to create CRUD.\nOr check if any problem in Module Name '".$config->moduleName."'.", self::FIRST_VALUE);
            return;
        }
        $connectionStatus = LAHelper::moduleConnection($module->provider_id);
        if($connectionStatus['type']==='error')
        {
            return json_encode($connectionStatus);
        }
        $dbconnector = 'mysqlDynamicConnector';
        $primaryKeyObject = DB::connection($dbconnector)->select("SHOW KEYS FROM ".$module->name_db." WHERE Key_name = 'PRIMARY'");
        $primaryKey = $primaryKeyObject[self::INIT_VALUE]->Column_name;
        $config->primary_key = $primaryKey;
        $config->module = $module;
        return $config;
    }
}
