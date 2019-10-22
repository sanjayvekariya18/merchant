<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Exception;
use Log;
use DB;
use Auth;
use App\Helpers\LAHelper;
use App\Http\Traits\PermissionTrait;
use App\User;
use App\Identity_group_list;
use App\Group_permission;
use App\Helpers\ConnectionManager;
use Session;

class Module extends Model
{
	const INIT_VALUE      = 0;

	protected $table = 'modules';
	
	protected $fillable = [
		"name", "name_db", "label", "view_col", "model", "controller", "is_gen","fa_icon"
	];
	
	protected $hidden = [
		
	];
	
	public static function generateBase($module_name, $icon, $providerId, $popupCommand, $allowDelete, $allowEdit, $selectAllCheckbox) {
		
		$names = LAHelper::generateModuleNames($module_name,$icon);
		
		// Check is Generated
		$is_gen = false;
		if(file_exists(base_path('app/Http/Controllers/'.($names->controller).".php"))) {
			if(($names->model == "User" || $names->model == "Role" || $names->model == "Permission") && file_exists(base_path('app/'.($names->model).".php"))) {
				$is_gen = true;
			} else if(file_exists(base_path('app/'.($names->model).".php"))) {
				$is_gen = true;
			}
		}
		$module = Module::where('name_db', $names->table)->orderBy('id', 'desc')->first();
		if(!isset($module->id)) {
			$userId = session()->has('userId') ? session()->get('userId') :"";
			$module = new Module();

			$module->name = $names->module;
			$module->owner_id = $userId;
			$module->label = $names->label;
			$module->name_db = $names->table;
			$module->view_col = "";
			$module->model = $names->model;
			$module->controller = $names->controller;
			$module->controller_url = $names->label;
			$module->fa_icon = $names->fa_icon;
			$module->is_gen = $is_gen;
			$module->provider_id = $providerId;
			$module->popup = $popupCommand;
			$module->allow_delete = $allowDelete;
			$module->allow_edit = $allowEdit;
			$module->select_all = $selectAllCheckbox;
			$module->custom = 1;
			$module->save();
		} else {
			$module->popup = $popupCommand;
			$module->allow_delete = $allowDelete;
			$module->allow_edit = $allowEdit;
			$module->select_all = $selectAllCheckbox;
			$module->save();
		}
		return $module->id;
	}
	
	public static function generate($module_name, $module_name_db, $view_col, $faIcon = "fa-cube", $fields) {
		
		$names = LAHelper::generateModuleNames($module_name, $faIcon);
		$fields = Module::format_fields($fields);
		
		if(substr_count($view_col, " ") || substr_count($view_col, ".")) {
			throw new Exception("Unable to generate migration for ".($names->module)." : Invalid view_column_name. 'This should be database friendly lowercase name.'", 1);
		} else if(!Module::validate_view_column($fields, $view_col)) {
			throw new Exception("Unable to generate migration for ".($names->module)." : view_column_name not found in field list.", 1);
		} else {
			// Check is Generated
			$is_gen = false;
			if(file_exists(base_path('app/Http/Controllers/'.($names->controller).".php"))) {
				if(($names->model == "User" || $model == "Role" || $model == "Permission") && file_exists(base_path('app/'.($names->model).".php"))) {
					$is_gen = true;
				} else if(file_exists(base_path('app/'.($names->model).".php"))) {
					$is_gen = true;
				}
			}
			
			$module = Module::where('name', $names->module)->orderBy('id', 'desc')->first();
			if(!isset($module->id)) {
				$module = Module::create([
					'name' => $names->module,
					'label' => $names->label,
					'name_db' => $names->table,
					'view_col' => $view_col,
					'model' => $names->model,
					'controller' => $names->controller,
					'is_gen' => $is_gen,
					'fa_icon' => $faIcon
				]);
			}
			
			$ftypes = ModuleFieldTypes::getFTypes();
			
			Schema::create($names->table, function (Blueprint $table) use ($fields, $module, $ftypes) {
				$table->increments('id');
				foreach ($fields as $field) {
					
					$mod = ModuleFields::where('module', $module->id)->where('colname', $field->colname)->first();
					if(!isset($mod->id)) {
						if($field->field_type == "Multiselect" || $field->field_type == "Taginput") {
							
							if(is_string($field->defaultvalue) && starts_with($field->defaultvalue, "[")) {
								$field->defaultvalue = json_decode($field->defaultvalue);
							}
							
							if(is_string($field->defaultvalue) || is_int($field->defaultvalue)) {
								$dvalue = json_encode([$field->defaultvalue]);
							} else {
								$dvalue = json_encode($field->defaultvalue);
							}
						} else {
							$dvalue = $field->defaultvalue;
							if(is_string($field->defaultvalue) || is_int($field->defaultvalue)) {
								$dvalue = $field->defaultvalue;
							} else if(is_array($field->defaultvalue) && is_object($field->defaultvalue)) {
								$dvalue = json_encode($field->defaultvalue);
							}
						}
						
						$pvalues = $field->popup_vals;
						if(is_array($field->popup_vals) || is_object($field->popup_vals)) {
							$pvalues = json_encode($field->popup_vals);
						}
						
						$field_obj = ModuleFields::create([
							'module' => $module->id,
							'colname' => $field->colname,
							'label' => $field->label,
							'field_type' => $ftypes[$field->field_type],
							'unique' => $field->unique,
							'defaultvalue' => $dvalue,
							'minlength' => $field->minlength,
							'maxlength' => $field->maxlength,
							'required' => $field->required,
							'popup_vals' => $pvalues
						]);
						$field->id = $field_obj->id;
						$field->module_obj = $module;
					}
					
					// Schema::dropIfExists($names->table);
					
					Module::create_field_schema($table, $field);
				}
				
				
				if($module->name_db == "users") {
					$table->rememberToken();
				}
				$table->dropSoftDeletes();
				$table->dropTimestamps();
			});
		}
	}
	
	public static function validate_view_column($fields, $view_col) {
		$found = false;
		foreach ($fields as $field) {
			if($field->colname == $view_col) {
				$found = true;
				break;
			}
		}
		return $found;
	}
	
	public static function create_field_schema($table, $field, $update = false, $isFieldTypeChange = false) {
		
		if(is_numeric($field->field_type)) {
			$ftypes = ModuleFieldTypes::getFTypes();
			$field->field_type = array_search($field->field_type, $ftypes);
		}
		if(!is_string($field->defaultvalue)) {
			$defval = json_encode($field->defaultvalue);
		} else {
			$defval = $field->defaultvalue;
		}
		Log::debug('Module:create_field_schema ('.$update.') - '.$field->colname." - ".$field->field_type
				." - ".$defval." - ".$field->maxlength);
		
		switch ($field->field_type) {
			case 'Address':
				$var = null;
				if($field->maxlength == 0) {
					if($update) {
						$var = $table->text($field->colname)->change();
					} else {
						$var = $table->text($field->colname);
					}
				} else {
					if($update) {
						$var = $table->string($field->colname, $field->maxlength)->change();
					} else {
						$var = $table->string($field->colname, $field->maxlength);
					}
				}
				if($field->defaultvalue != "") {
					$var->default($field->defaultvalue);
				} else if($field->required) {
					$var->default("");
				}
				break;
			case 'Checkbox':
				if($update) {
					$var = $table->boolean($field->colname)->change();
				} else {
					$var = $table->boolean($field->colname);
				}
				if($field->defaultvalue == "true" || $field->defaultvalue == "false" || $field->defaultvalue == true || $field->defaultvalue == false) {
					if(is_string($field->defaultvalue)) {
						if($field->defaultvalue == "true") {
							$field->defaultvalue = true;
						} else {
							$field->defaultvalue = false;
						}
					}
					$var->default($field->defaultvalue);
				} else if($field->required) {
					$field->defaultvalue = false;
				}
				break;
			case 'Currency':
			case 'Decimal':
				if($update) {
					$var = $table->decimal($field->colname, 18, $field->precision_value)->change();
				} else {
					$var = $table->decimal($field->colname, 18, $field->precision_value);
				}
				if($field->defaultvalue != "") {
					$var->default($field->defaultvalue);
				} else if($field->required) {
					$var->default("0.00000000");
				}
				break;
			case 'Date':
				if($update) {
					$var = $table->date($field->colname)->change();
				} else {
					$var = $table->date($field->colname);
				}
				if($field->defaultvalue != "" && !starts_with($field->defaultvalue, "date")) {
					$var->default($field->defaultvalue);
				} else if($field->required) {
					$var->default(date("Y-m-d"));
				}
				break;
			case 'Datetime':
				if($update) {
					// Timestamp Edit Not working - http://stackoverflow.com/questions/34774628/how-do-i-make-doctrine-support-timestamp-columns
					// Error Unknown column type "timestamp" requested. Any Doctrine type that you use has to be registered with \Doctrine\DBAL\Types\Type::addType()
					// $var = $table->timestamp($field->colname)->change();
				} else {
					$var = $table->timestamp($field->colname);
				}
				// $table->timestamp('created_at')->useCurrent();
				if(isset($var) && $field->defaultvalue != "" && !starts_with($field->defaultvalue, "date")) {
					$var->default($field->defaultvalue);
				} else if($field->required) {
					$var->default(date("Y-m-d H:i:s"));
				}
				break;
			case 'Dropdown':
			case 'AutoComplete':
				if($field->popup_vals == "") {
					if(is_int($field->defaultvalue)) {
						if($update) {
							$var = $table->integer($field->colname)->unsigned()->change();
						} else {
							$var = $table->integer($field->colname)->unsigned();
						}
						$var->default($field->defaultvalue);
						break;
					} else if(is_string($field->defaultvalue)) {
						if($update) {
							$var = $table->string($field->colname)->change();
						} else {
							$var = $table->string($field->colname);
						}
						$var->default($field->defaultvalue);
						break;
					}
				}
				$popup_vals = json_decode($field->popup_vals);
				if(starts_with($field->popup_vals, "@")) {
					$foreign_table_name = str_replace("@", "", $field->popup_vals);
					if($update) {
						$var = $table->integer($field->colname)->unsigned()->change();
						if($field->defaultvalue == "" || $field->defaultvalue == "0") {
							$var->default(1);
						} else {
							$var->default($field->defaultvalue);
						}
					} else {
						$var = $table->integer($field->colname)->unsigned();
						if($field->defaultvalue == "" || $field->defaultvalue == "0") {
							$var->default(1);
						} else {
							$var->default($field->defaultvalue);
						}
					}
				} else if(is_array($popup_vals)) {
					if($update) {
						$var = $table->string($field->colname)->change();
					} else {
						$var = $table->string($field->colname);
					}
					if($field->defaultvalue != "") {
						$var->default($field->defaultvalue);
					} else if($field->required) {
						$var->default("");
					}
				} else if(is_object($popup_vals)) {
					// ############### Remaining
					if($update) {
						$var = $table->integer($field->colname)->unsigned()->change();
					} else {
						$var = $table->integer($field->colname)->unsigned();
					}
					// if(is_int($field->defaultvalue)) {
					//     $var->default($field->defaultvalue);
					//     break;
					// }
				}
				break;
			case 'Email':
				$var = null;
				if($field->maxlength == 0) {
					if($update) {
						$var = $table->string($field->colname, 100)->change();
					} else {
						$var = $table->string($field->colname, 100);
					}
				} else {
					if($update) {
						$var = $table->string($field->colname, $field->maxlength)->change();
					} else {
						$var = $table->string($field->colname, $field->maxlength);
					}
				}
				if($field->defaultvalue != "") {
					$var->default($field->defaultvalue);
				} else if($field->required) {
					$var->default("");
				}
				break;
			case 'File':
				if($update) {
					$var = $table->integer($field->colname)->change();
				} else {
					$var = $table->integer($field->colname);
				}
				if($field->defaultvalue != "" && is_numeric($field->defaultvalue)) {
					$var->default($field->defaultvalue);
				} else if($field->required) {
					$var->default(0);
				}
				break;
			case 'Files':
				if($update) {
					$var = $table->string($field->colname, 256)->change();
				} else {
					$var = $table->string($field->colname, 256);
				}
				if(is_string($field->defaultvalue) && starts_with($field->defaultvalue, "[")) {
					$var->default($field->defaultvalue);
				} else if(is_array($field->defaultvalue)) {
					$var->default(json_encode($field->defaultvalue));
				} else {
					$var->default("[]");
				}
				break;
			case 'Float':
				if($update) {
					$var = $table->float($field->colname)->change();
				} else {
					$var = $table->float($field->colname);
				}
				if($field->defaultvalue != "") {
					$var->default($field->defaultvalue);
				} else if($field->required) {
					$var->default("0.0");
				}
				break;
			case 'HTML':
				if($update) {
					$var = $table->string($field->colname, 10000)->change();
				} else {
					$var = $table->string($field->colname, 10000);
				}
				if($field->defaultvalue != null) {
					$var->default($field->defaultvalue);
				} else if($field->required) {
					$var->default("");
				}
				break;
			case 'Image':
				if($update) {
					$var = $table->integer($field->colname)->change();
				} else {
					$var = $table->integer($field->colname);
				}
				if($field->defaultvalue != "" && is_numeric($field->defaultvalue)) {
					$var->default($field->defaultvalue);
				} else if($field->required) {
					$var->default(1);
				}
				break;
			case 'Integer':
				$var = null;
				if($update) {
					$var = $table->integer($field->colname, false)->unsigned()->change();
				} else {
					$var = $table->integer($field->colname, false)->unsigned();
				}
				if($field->defaultvalue != "") {
					$var->default($field->defaultvalue);
				} else if($field->required) {
					$var->default("0");
				}
				break;
			case 'Mobile':
				$var = null;
				if($field->maxlength == 0) {
					if($update) {
						$var = $table->string($field->colname)->change();
					} else {
						$var = $table->string($field->colname);
					}
				} else {
					if($update) {
						$var = $table->string($field->colname, $field->maxlength)->change();
					} else {
						$var = $table->string($field->colname, $field->maxlength);
					}
				}
				if($field->defaultvalue != "") {
					$var->default($field->defaultvalue);
				} else if($field->required) {
					$var->default("");
				}
				break;
			case 'Multiselect':
				if($update) {
					$var = $table->string($field->colname, 256)->change();
				} else {
					$var = $table->string($field->colname, 256);
				}
				if(is_array($field->defaultvalue)) {
					$field->defaultvalue = json_encode($field->defaultvalue);
					$var->default($field->defaultvalue);
				} else if(is_string($field->defaultvalue) && starts_with($field->defaultvalue, "[")) {
					$var->default($field->defaultvalue);
				} else if($field->defaultvalue == "" || $field->defaultvalue == null) {
					$var->default("[]");
				} else if(is_string($field->defaultvalue)) {
					$field->defaultvalue = json_encode([$field->defaultvalue]);
					$var->default($field->defaultvalue);
				} else if(is_int($field->defaultvalue)) {
					$field->defaultvalue = json_encode([$field->defaultvalue]);
					//echo "int: ".$field->defaultvalue;
					$var->default($field->defaultvalue);
				} else if($field->required) {
					$var->default("[]");
				}
				break;
			case 'Name':
				$var = null;
				if($field->maxlength == 0) {
					if($update) {
						$var = $table->string($field->colname)->change();
					} else {
						$var = $table->string($field->colname);
					}
				} else {
					if($update) {
						$var = $table->string($field->colname, $field->maxlength)->change();
					} else {
						$var = $table->string($field->colname, $field->maxlength);
					}
				}
				if($field->defaultvalue != "") {
					$var->default($field->defaultvalue);
				} else if($field->required) {
					$var->default("");
				}
				break;
			case 'Password':
				$var = null;
				if($field->maxlength == 0) {
					if($update) {
						$var = $table->string($field->colname)->change();
					} else {
						$var = $table->string($field->colname);
					}
				} else {
					if($update) {
						$var = $table->string($field->colname, $field->maxlength)->change();
					} else {
						$var = $table->string($field->colname, $field->maxlength);
					}
				}
				if($field->defaultvalue != "") {
					$var->default($field->defaultvalue);
				} else if($field->required) {
					$var->default("");
				}
				break;
			case 'Radio':
				$var = null;
				if($field->popup_vals == "") {
					if(is_int($field->defaultvalue)) {
						if($update) {
							$var = $table->integer($field->colname)->unsigned()->change();
						} else {
							$var = $table->integer($field->colname)->unsigned();
						}
						$var->default($field->defaultvalue);
						break;
					} else if(is_string($field->defaultvalue)) {
						if($update) {
							$var = $table->string($field->colname)->change();
						} else {
							$var = $table->string($field->colname);
						}
						$var->default($field->defaultvalue);
						break;
					}
				}
				if(is_string($field->popup_vals) && starts_with($field->popup_vals, "@")) {
					if($update) {
						$var = $table->integer($field->colname)->unsigned()->change();
					} else {
						$var = $table->integer($field->colname)->unsigned();
					}
					break;
				}
				$popup_vals = json_decode($field->popup_vals);
				if(is_array($popup_vals)) {
					if($update) {
						$var = $table->string($field->colname)->change();
					} else {
						$var = $table->string($field->colname);
					}
					if($field->defaultvalue != "") {
						$var->default($field->defaultvalue);
					} else if($field->required) {
						$var->default("");
					}
				} else if(is_object($popup_vals)) {
					// ############### Remaining
					if($update) {
						$var = $table->integer($field->colname)->unsigned()->change();
					} else {
						$var = $table->integer($field->colname)->unsigned();
					}
					// if(is_int($field->defaultvalue)) {
					//     $var->default($field->defaultvalue);
					//     break;
					// }
				}
				break;
			case 'String':
				$var = null;
				if($field->maxlength == 0) {
					if($update) {
						$var = $table->string($field->colname)->change();
					} else {
						$var = $table->string($field->colname);
					}
				} else {
					if($update) {
						$var = $table->string($field->colname, $field->maxlength)->change();
					} else {
						$var = $table->string($field->colname, $field->maxlength);
					}
				}
				if($field->defaultvalue != null) {
					$var->default($field->defaultvalue);
				} else if($field->required) {
					$var->default("");
				}
				break;
			case 'Taginput':
				$var = null;
				if($update) {
					$var = $table->string($field->colname, 1000)->change();
				} else {
					$var = $table->string($field->colname, 1000);
				}
				if(is_string($field->defaultvalue) && starts_with($field->defaultvalue, "[")) {
					$field->defaultvalue = json_decode($field->defaultvalue);
				}
				
				if(is_string($field->defaultvalue)) {
					$field->defaultvalue = json_encode([$field->defaultvalue]);
					//echo "string: ".$field->defaultvalue;
					$var->default($field->defaultvalue);
				} else if(is_array($field->defaultvalue)) {
					$field->defaultvalue = json_encode($field->defaultvalue);
					//echo "array: ".$field->defaultvalue;
					$var->default($field->defaultvalue);
				} else if($field->required) {
					$var->default("");
				}
				break;
			case 'Textarea':
				$var = null;
				if($field->maxlength == 0) {
					if($update) {
						$var = $table->text($field->colname)->change();
					} else {
						$var = $table->text($field->colname);
					}
				} else {
					if($update) {
						$var = $table->string($field->colname, $field->maxlength)->change();
					} else {
						$var = $table->string($field->colname, $field->maxlength);
					}
					if($field->defaultvalue != "") {
						$var->default($field->defaultvalue);
					} else if($field->required) {
						$var->default("");
					}
				}
				break;
			case 'TextField':
				$var = null;
				if($field->maxlength == 0) {
					if($update) {
						$var = $table->string($field->colname)->change();
					} else {
						$var = $table->string($field->colname);
					}
				} else {
					if($update) {
						$var = $table->string($field->colname, $field->maxlength)->change();
					} else {
						$var = $table->string($field->colname, $field->maxlength);
					}
				}
				if($field->defaultvalue != "") {
					$var->default($field->defaultvalue);
				} else if($field->required) {
					$var->default("");
				}
				break;
			case 'URL':
				$var = null;
				if($field->maxlength == 0) {
					if($update) {
						$var = $table->string($field->colname)->change();
					} else {
						$var = $table->string($field->colname);
					}
				} else {
					if($update) {
						$var = $table->string($field->colname, $field->maxlength)->change();
					} else {
						$var = $table->string($field->colname, $field->maxlength);
					}
				}
				if($field->defaultvalue != "") {
					$var->default($field->defaultvalue);
				} else if($field->required) {
					$var->default("");
				}
				break;
		}
		
		// set column unique
		if($update) {
			if($isFieldTypeChange) {
				if($field->unique && $var != null && $field->maxlength < 256) {
					$table->unique($field->colname);
				}
			}
		} else {
			if($field->unique && $var != null && $field->maxlength < 256) {
				$table->unique($field->colname);
			}
		}
	}
	
	public static function format_fields($fields) {
		$out = array();
		foreach ($fields as $field) {
			$obj = (Object)array();
			$obj->colname = $field[0];
			$obj->label = $field[1];
			$obj->field_type = $field[2];
			
			if(!isset($field[3])) {
				$obj->unique = 0;
			} else {
				$obj->unique = $field[3];
			}
			if(!isset($field[4])) {
				$obj->defaultvalue = '';
			} else {
				$obj->defaultvalue = $field[4];
			}
			if(!isset($field[5])) {
				$obj->minlength = 0;
			} else {
				$obj->minlength = $field[5];
			}
			if(!isset($field[6])) {
				$obj->maxlength = 0;
			} else {
				// Because maxlength above 256 will not be supported by Unique
				if($obj->unique) {
					$obj->maxlength = 250;
				} else {
					$obj->maxlength = $field[6];
				}
			}
			if(!isset($field[7])) {
				$obj->required = 0;
			} else {
				$obj->required = $field[7];
			}
			if(!isset($field[8])) {
				$obj->popup_vals = "";
			} else {
				if(is_array($field[8])) {
					$obj->popup_vals = json_encode($field[8]);
				} else {
					$obj->popup_vals = $field[8];
				}
			}
			$out[] = $obj;
		}
		return $out;
	}
	
	/**
	* Get Module by module name
	* $module = Module::get($module_name);
	**/
	public static function get($module_name) {
		$module = null;

		if(is_int($module_name)) {
			$module = Module::find($module_name);
		} else {
			$module = Module::where('name', $module_name)->orderBy('id', 'desc')->first();
		}
		
		if(isset($module)) {
			$module = $module->toArray();
			$fields = ModuleFields::where('module', $module['id'])->orderBy('sort', 'asc')->get()->toArray();
			$fields2 = array();
			foreach ($fields as $field) {
				$fields2[$field['colname']] = $field;
			}
			$module['fields'] = $fields2;

			return (object)$module;
		} else {
			return null;
		}
	}
	
	/**
	* Get Module by table name
	* $module = Module::getByTable($table_name);
	**/
	public static function getByTable($table_name) {
		$module = Module::where('name_db', $table_name)->orderBy('id', 'desc')->first();
		if(isset($module)) {
			$module = $module->toArray();
			return Module::get($module['name']);
		} else {
			return null;
		}
	}
	
	/**
	* Get Array for Dropdown, Multiselect, Taginput, Radio from Module getByTable
	* $array = Module::getDDArray($module_name);
	**/
	public static function getDDArray($module_name) {
		$module = Module::where('name', $module_name)->orderBy('id', 'desc')->first();
		if(isset($module)) {
			$model_name = ucfirst($module_name);
			if($model_name == "User" || $model_name == "Role" || $model_name == "Permission") {
				$model = "App\\".ucfirst($module_name);
			} else {
				$model = "App\\".ucfirst($module_name);
			}

			$result = $model::all();
			$out = array();
			foreach ($result as $row) {
				$view_col = $module->view_col;
				$out[$row->id] = $row->{$view_col};
			}
			return $out;
		} else {
			return array();
		}
	}
	
	public static function validateRules($module_name, $request, $isEdit = false) {
		$module = Module::get($module_name);
		$rules = [];
		if(isset($module)) {
			$ftypes = ModuleFieldTypes::getFieldTypeValue();
			foreach ($module->fields as $field) {
				if(isset($request->{$field['colname']})) {
					$col = "";
					if($field['required']) {
						$col .= "required|";
					}
					if(in_array($ftypes[$field['field_type']], array("Currency", "Decimal"))) {
						// No min + max length
					} else {
						if($field['minlength'] != 0) {
							$col .= "min:".$field['minlength']."|";
						}
						if($field['maxlength'] != 0) {
							$col .= "max:".$field['maxlength']."|";
						}
					}
					if($field['unique'] && !$isEdit) {
						$col .= "unique:".$module->name_db.",deleted_at,NULL";
					}
					// 'name' => 'required|unique|min:5|max:256',
					// 'author' => 'required|max:50',
					// 'price' => 'decimal',
					// 'pages' => 'integer|max:5',
					// 'genre' => 'max:500',
					// 'description' => 'max:1000'
					if($col != "") {
						$rules[$field['colname']] = trim($col, "|");
					}
				}
			}
			return $rules;
		} else {
			return $rules;
		}
	}
	
	public static function insert($module_name, $request) {
		$module = Module::get($module_name);
		if(isset($module)) {
			$model_name = ucfirst($module_name);
			if($model_name == "User" || $model_name == "Role" || $model_name == "Permission") {
				$model = "App\\".ucfirst($module_name);
			} else {
				$model = "App\\".ucfirst($module_name);
			}
			
			// Delete if unique rows available which are deleted
			$old_row = null;
			$uniqueFields = ModuleFields::where('module', $module->id)->where('unique', '1')->get()->toArray();
			foreach ($uniqueFields as $field) {
				Log::debug("insert: ".$module->name_db." - ".$field['colname']." - ".$request->{$field['colname']});
				$old_row = DB::table($module->name_db)->whereNotNull('deleted_at')->where($field['colname'], $request->{$field['colname']})->first();
				if(isset($old_row->id)) {
					Log::debug("deleting: ".$module->name_db." - ".$field['colname']." - ".$request->{$field['colname']});
					DB::table($module->name_db)->whereNotNull('deleted_at')->where($field['colname'], $request->{$field['colname']})->delete();
				}
			}
			
			$row = new $model;
			if(isset($old_row->id)) {
				// To keep old & new row id remain same
				$row->id = $old_row->id;
			}
			$row = Module::processDBRow($module, $request, $row);
			$row->save();
			return $row->id;
		} else {
			return null;
		}
	}
	
	public static function updateRow($module_name, $request, $id) {
		$module = Module::get($module_name);
		if(isset($module)) {
			$model_name = ucfirst($module_name);
			if($model_name == "User" || $model_name == "Role" || $model_name == "Permission") {
				$model = "App\\".ucfirst($module_name);
			} else {
				$model = "App\\".ucfirst($module_name);
			}
			//$row = new $module_path;
			$row = $model::find($id);
			$row = Module::processDBRow($module, $request, $row);
			$row->save();

			$dbconnector = 'mysqlDynamicConnector';
			$connectionStatus = LAHelper::moduleConnection($module->provider_id);
	        if($connectionStatus['type']==='error')
	        {
	            return json_encode($connectionStatus);
	        }
			$primaryKeyObject = DB::connection($dbconnector)->select("SHOW KEYS FROM ".$module->name_db." WHERE Key_name = 'PRIMARY'");
	        $primaryKey = $primaryKeyObject[self::INIT_VALUE]->Column_name;
	        return $row->{$primaryKey};
		} else {
			return null;
		}
	}
	
	public static function processDBRow($module, $request, $row) {
		$ftypes = ModuleFieldTypes::getFieldTypeValue();
		foreach ($module->fields as $field) {
			if(isset($request->{$field['colname']}) || isset($request->{$field['colname']."_hidden"})) {
				
				switch ($ftypes[$field['field_type']]) {
					case 'Checkbox':
						#TODO: Bug fix
						/* for kendo grid */
						if($module->crud_type_id == 1)
						{
							if($request->{$field['colname']} == "true") {
								$row->{$field['colname']} = true;
							} else {
								$row->{$field['colname']} = false;
							}
						} else {
							if(isset($request->{$field['colname']})) {
								$row->{$field['colname']} = true;
							} else if(isset($request->{$field['colname']."_hidden"})) {
								$row->{$field['colname']} = false;
							}
						}
						break;
					case 'Date':
						if($request->{$field['colname']} != "") {
							$date = $request->{$field['colname']};
							$d2 = date_parse_from_format("m/d/Y",$date);
							$request->{$field['colname']} = date("Y-m-d", strtotime($d2['year']."-".$d2['month']."-".$d2['day']));
						}
						$row->{$field['colname']} = $request->{$field['colname']};
						break;
					case 'Datetime':
						#TODO: Bug fix
						if($request->{$field['colname']} != "") {
							$date = $request->{$field['colname']};
							$d2 = date_parse_from_format("m/d/Y h:i:s A",$date);
							$request->{$field['colname']} = date("Y-m-d H:i:s", strtotime($d2['year']."-".$d2['month']."-".$d2['day']." ".substr($date, 11)));
						}
						$row->{$field['colname']} = $request->{$field['colname']};
						break;
					case 'Multiselect':
						/*for kendo grid */
						if($module->crud_type_id == 1)
						{
							if(starts_with($field['popup_vals'], "@"))
							{
								$tempArray = array();
								if(isset($request->{$field['popup_field_id']}[0][$field['popup_field_id']]))
								{
									foreach ($request->{$field['popup_field_id']} as $key => $value) {
										$tempArray[] = $value[$field['popup_field_id']];
									}
									$request->{$field['popup_field_id']} = $tempArray;
									$row->{$field['colname']} = json_encode($request->{$field['popup_field_id']}, JSON_NUMERIC_CHECK);
									break;
								}
							} else if(starts_with($field['popup_vals'], "[")) {
								$row->{$field['colname']} = json_encode($request->{$field['colname']});
								break;
							}
							$row->{$field['colname']} = $request->{$field['popup_field_id']};
						} else {
							#TODO: Bug fix
							//$row->{$field['colname']} = json_encode($request->{$field['colname']});
							$row->{$field['colname']} = json_encode($request->{$field['colname']}, JSON_NUMERIC_CHECK);
						}
						break;
						
					case 'Password':
						$row->{$field['colname']} = bcrypt($request->{$field['colname']});
						break;
					case 'Taginput':
						#TODO: Bug fix
						//$row->{$field['colname']} = json_encode($request->{$field['colname']});
						$row->{$field['colname']} = json_encode($request->{$field['colname']}, JSON_NUMERIC_CHECK);
						break;
					case 'Files':
						$files = json_decode($request->{$field['colname']});
						$files2 = array();
						foreach ($files as $file) {
							$files2[] = "".$file;
						}
						$row->{$field['colname']} = json_encode($files2);
						break;
					default:
						$row->{$field['colname']} = $request->{$field['colname']};
						break;
				}
			} else if(!isset($request->{$field['colname']}) && $ftypes[$field['field_type']] == "Multiselect" && $module->crud_type_id == 1 && starts_with($field['popup_vals'], "["))
			{
				$row->{$field['colname']} = '';
			}
		}
		return $row;
	}
	
	public static function itemCount($module_name) {
		$module = Module::get($module_name);
		if(isset($module)) {
			$model_name = ucfirst($module_name);
			if($model_name == "User" || $model_name == "Role" || $model_name == "Permission") {
				if(file_exists(base_path('app/'.$model_name.".php"))) {
					$model = "App\\".$model_name;
					return $model::count();
				} else {
					return "Model doesn't exists";
				}
			} else {
				if(file_exists(base_path('app/'.$module->model.".php"))) {
					try {
						$connectionStatus = LAHelper::moduleConnection($module->provider_id);
						if (strcmp($connectionStatus['type'], "error") == 0) {
							return $connectionStatus['message'];
				        }
						$model = "App\\".$module->model;
						return $model::count();
					} catch (\Exception $modelException) {
						$exceptionMessage = ConnectionManager::renderPdoException($modelException->getCode());
						return $exceptionMessage;
			        }
				} else {
					return "Model doesn't exists";
				}
			}
			
		} else {
			return 0;
		}
	}
	
	/**
	* Get Module Access for all roles
	* $roles = Module::getRoleAccess($id);
	**/
	public static function getRoleAccess($module_id, $specific_role = 0) {

		$module = Module::find($module_id);
		$module = Module::get($module->name);
		
		$identity_id = Session::get('staffId');
		$identity_table_id = Session::get('identity_table_id');

		$roleID = Session::get('role');

		if ($roleID == 1){
			$group_list = Group_permission::where("group_id","!=",0)->get();
		}else{
			$group_list = Identity_group_list::where('identity_id',$identity_id)->where('identity_table_id',$identity_table_id)->get();
		}
				
		$permissions = array();

		foreach ($group_list as $group) {
			
			$hase_permissions = DB::table('group_permissions')
		   	->where('group_id', $group->group_id)
		   	->first();

		   	$group_permissions = unserialize($hase_permissions->permissions);

		   	if(isset($group_permissions[$module->name])){
		   		$module_group_permission = $group_permissions[$module->name];
		   	}else{
		   		$module_group_permission = array();
		   	}

		   	$permissions[$group->group_id]['id'] = $hase_permissions->group_id;
		   	$permissions[$group->group_id]['name'] = $hase_permissions->group_name;
		   	$permissions[$group->group_id]['permissions'] = $module_group_permission;

		}
		
		return $permissions;
	}

	public static function removeGroupPermission($module_id){

		$module = Module::find($module_id);
		$group_list = group_permission::all();

		foreach ($group_list as $group) {
			
		   	$group_permissions = unserialize($group->permissions);
		   	unset($group_permissions[$module->name]);
		   	
		   	$group->permissions = serialize($group_permissions);
		   	$group->save(); 
		}

		return true;
	}
	
	/**
	* Get Module Access for role and access type
	* Module::hasAccess($module_id, $access_type, $user_id);
	**/
	public static function hasAccess($module_id, $access_type = "access", $user_id = 0) {
		$roles = array();
		$module_name ="";

		if(is_string($module_id)){
			$module = Module::get($module_id);
			$module_id = $module->id;
		}
		if($module_id) {
			$module = Module::get($module_id);
			$module_id = $module->id;
			$module_name = $module->name;
		}

		$permissions = PermissionTrait::getPermission($module_name);

		if(in_array($access_type,$permissions)){
			return true;
		}
			
	return false;
	}
		
}
