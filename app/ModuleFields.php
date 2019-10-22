<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Schema;
use Log;
use DB;
use App\Http\Traits\PermissionTrait;
use App\Helpers\LAHelper;
use App\Module;
use App\ModulesGrouping;

class ModuleFields extends Model
{
    protected $table = 'module_fields';
    protected static $dbconnector = 'mysqlDynamicConnector';
    protected $fillable = [
        "colname", "label", "module", "field_type", "unique", "defaultvalue", "minlength", "maxlength", "required", "popup_vals"
    ];
    
    protected $hidden = [
        
    ];
    
    public static function createField($request,$isTableExist =0) {
        $module = Module::find($request->module_id);
        $module_id = $request->module_id;
        
        $field = ModuleFields::where('colname', $request->colname)->where('module', $module_id)->first();
        if(!isset($field->id)) {
            $connectionStatus = LAHelper::moduleConnection($module->provider_id);
            if($connectionStatus['type']==='error')
            {
                return $connectionStatus;
            }

            // Create Schema for Module Field
            if (!Schema::connection(self::$dbconnector)->hasTable($module->name_db)) {

                Schema::connection(self::$dbconnector)->create($module->name_db, function($table) {
                    $table->increments('id');
                });
            }
            
            $field = new ModuleFields;
            $field->colname = $request->colname;
            $field->label = $request->label;
            $field->module = $request->module_id;
            $field->field_type = $request->field_type;
            if($request->unique) {
                $field->unique = true;
            } else {
                $field->unique = false;
            }
            $field->defaultvalue = $request->defaultvalue;
            if($request->minlength == "") {
				$request->minlength = 0;
			}
			if($request->maxlength == "") {
				if(in_array($request->field_type, [1, 8, 16, 17, 19, 20, 22, 23 ])) {
					$request->maxlength = 256;
				} else if(in_array($request->field_type, [14])) {
					$request->maxlength = 20;
				} else if(in_array($request->field_type, [3, 6, 10, 13])) {
					$request->maxlength = 11;
				}
			}
            $field->minlength = $request->minlength;
            if($request->maxlength != null && $request->maxlength != "") {
				$field->maxlength = $request->maxlength;
			}
            if($request->required) {
                $field->required = true;
            } else {
                $field->required = false;
            }

            if($request->precision_value == "") {
                if($request->field_type == 3) {
                    $field->precision_value = 8;
                } else {
                    $field->precision_value = 0;
                }
            } else {
                $field->precision_value = $request->precision_value;
            }

            if($request->visibility) {
                $field->visibility = true;
            } else {
                $field->visibility = false;
            }
            if($request->field_type == 7 || $request->field_type == 15 || $request->field_type == 18 || $request->field_type == 20 || $request->field_type == 25) {
                if($request->popup_value_type == "table") {
                    $field->popup_vals = "@".$request->popup_vals_table;
                    $field->popup_field_id = $request->popup_field_id;
                    $field->popup_field_name = $request->popup_field_name;
                } else if($request->popup_value_type == "list") {
                    $request->popup_vals_list = json_encode($request->popup_vals_list);
                    $field->popup_vals = $request->popup_vals_list;
                }
            } else {
				$field->popup_vals = "";
			}
            $field->save();
            if(!$isTableExist)
            {
                Schema::connection(self::$dbconnector)->table($module->name_db, function($table) use ($field, $module) {

                    $field->module_obj = $module;                
                    Module::create_field_schema($table, $field, false);
                });
            }
        }
        return $field->id;
    }

    public static function updateField($id, $request) {
        $module_id = $request->module_id;
        
        $field = ModuleFields::find($id);

        // Update the Schema
        // Change Column Name if Different
        $module = Module::find($module_id);

        $connectionStatus = LAHelper::moduleConnection($module->provider_id);
        if($connectionStatus['type']==='error')
        {
            return $connectionStatus;
        }

        if($field->colname != $request->colname) {
            Schema::connection(self::$dbconnector)->table($module->name_db, function ($table) use ($field, $request) {
                $table->renameColumn($field->colname, $request->colname);
            });
        }
        
		$isFieldTypeChange = false;

        // Update Context in ModuleFields
        $field->colname = $request->colname;
        $field->label = $request->label;
        $field->module = $request->module_id;
        $field->field_type = $request->field_type;
		if($field->field_type != $request->field_type) {
			$isFieldTypeChange = true;
		}
        if($request->unique) {
            $field->unique = true;
        } else {
            $field->unique = false;
        }
        $field->defaultvalue = $request->defaultvalue;
        if($request->minlength == "") {
			$request->minlength = 0;
		}
		if($request->maxlength == "" || $request->maxlength == 0) {
			if(in_array($request->field_type, [1, 8, 16, 17, 19, 20, 22, 23 ])) {
				$request->maxlength = 256;
			} else if(in_array($request->field_type, [14])) {
				$request->maxlength = 20;
			} else if(in_array($request->field_type, [3, 6, 10, 13])) {
				$request->maxlength = 11;
			}
		}
		$field->minlength = $request->minlength;
		if($request->maxlength != null && $request->maxlength != "") {
			$field->maxlength = $request->maxlength;
		}
        if($request->required) {
            $field->required = true;
        } else {
            $field->required = false;
        }

        if($request->visibility) {
            $field->visibility = true;
        } else {
            $field->visibility = false;
        }
        
        if($request->precision_value == "") {
            if($request->field_type == 3) {
                $field->precision_value = 8;
            } else {
                $field->precision_value = 0;
            }
        } else {
            $field->precision_value = $request->precision_value;
        }
        if($request->field_type == 7 || $request->field_type == 15 || $request->field_type == 18 || $request->field_type == 20 || $request->field_type == 25) {
            if($request->popup_value_type == "table") {
                $field->popup_vals = "@".$request->popup_vals_table;
                $field->popup_field_id = $request->popup_field_id;
                $field->popup_field_name = $request->popup_field_name;
            } else if($request->popup_value_type == "list") {
                $request->popup_vals_list = json_encode($request->popup_vals_list);
                $field->popup_vals = $request->popup_vals_list;
            }
        } else {
			$field->popup_vals = "";
		}
        $field->save();

		$field->module_obj = $module;

        Schema::connection(self::$dbconnector)->table($module->name_db, function ($table) use ($field, $isFieldTypeChange) {
            Module::create_field_schema($table, $field, true, $isFieldTypeChange);
        });
    }

	public static function getModuleFields($moduleName) {
        $module = Module::where('name', $moduleName)->orderBy('id', 'desc')->first();
        $connectionStatus = LAHelper::moduleConnection($module->provider_id);
        if($connectionStatus['type']==='error')
        {
            return json_encode($connectionStatus);
        }
        $dbconnector = 'mysqlDynamicConnector';
        $primaryKeyObject = DB::connection($dbconnector)->select("SHOW KEYS FROM ".$module->name_db." WHERE Key_name = 'PRIMARY'");
        $primaryKey = $primaryKeyObject[0]->Column_name;

        $fields = DB::table('module_fields')->where('module', $module->id)->get();
        $ftypes = ModuleFieldTypes::getFTypes();
		
		$fields_popup = array();

        $fields_popup[$primaryKey] = null;
        
		foreach($fields as $f) {
			$f->field_type_str = array_search($f->field_type, $ftypes);
            $fields_popup [ $f->colname ] = $f;
        }
		return $fields_popup;
    }

	public static function getFieldValue($field, $value,$providerId=1) {
        if(!is_int($value)){
            if(LAHelper::isStringJSON($value))
            {
                $value = json_decode($value);
            } else {
                $value = (array)$value;
            }
        }else{
            $value = (array)$value;
        }

        $external_table_name = substr($field->popup_vals, 1);
        $field_name = $field->popup_field_name;
        $field_value = array();

        $connectionStatus = LAHelper::moduleConnection($providerId);
        if($connectionStatus['type']==='error')
        {
            return $connectionStatus;
        }

        if(Schema::connection(self::$dbconnector)->hasTable($external_table_name)) {
            $external_value = DB::connection(self::$dbconnector)->table($external_table_name)->whereIn($field->popup_field_id, $value)->get();
            
            if(isset($external_value)) {
                foreach ($external_value as $record) {
                    $field_value[] = $record->$field_name;
                }
                return implode(",", $field_value);
            } else {
                return $value;
            }
        } else {
            return $value;
        }
    }
	
    final public static function getKendoFieldValue($field, $value,$providerId=1) {

        if(!is_int($value)){
            $value = json_decode($value);
        }else{
            $value = (array)$value;
        }

        $external_table_name = substr($field->popup_vals, 1);
        $field_name = $field->popup_field_name;
        $field_id = $field->popup_field_id;
        $field_value = array();
        $connectionStatus = LAHelper::moduleConnection($providerId);
        if($connectionStatus['type']==='error')
        {
            return $connectionStatus;
        }
        if(Schema::connection(self::$dbconnector)->hasTable($external_table_name)) {
            $external_value = DB::connection(self::$dbconnector)->table($external_table_name)->whereIn($field->popup_field_id, $value)->get();
            
            if(isset($external_value)) {
                foreach ($external_value as $record) {
                    $field_value_temp[$field_id] = $record->$field_id;
                    $field_value_temp[$field_name] = $record->$field_name;
                    $field_value[] = $field_value_temp;
                }
                return $field_value;
            } else {
                return $value;
            }
        } else {
            return $value;
        }
    }

    public static function getModuleFieldsList($moduleId) {
        $moduleFields = ModuleFields::select('id','colname')->where('module',$moduleId)->get();
        return $moduleFields;
    }
    
	public static function listingColumnAccessScan($module_name, $listing_cols) {
        $module = Module::get($module_name);

		$listing_cols_temp = array();
		foreach ($listing_cols as $col) {
			if($col == 'id') {
				$listing_cols_temp[] = $col;
			} else {
				$listing_cols_temp[] = $col;
			}
		}
		return $listing_cols_temp;
    }

    public static function getGroupByColumns($moduleId)
    {
        $moduleGroupFields = ModulesGrouping::
            select('modules_grouping.*','module_fields.colname')
            ->join('module_fields','module_fields.id','modules_grouping.field_id')
            ->where('module_id',$moduleId)
            ->get();
        return $moduleGroupFields;
    }
    
}
