<?php
namespace App\Helpers;

use Schema;
use Collective\Html\FormFacade as Form;
use App\Module;
use App\ModuleFields;
use App\ModuleFieldTypes;
use App\Helpers\LAFormMaker;
use App\Helpers\LAHelper;

class KendoColumnMaker
{
	const INIT_VALUE      = 0;
	const FIRST_VALUE     = 1;
	/**
	* Print input field enclosed within form-group
	**/
	final public static function kendo_input($module,$moduleGroupFields, $field_name, $default_val = null, $required2 = null, $class = 'form-control', $params = [])
	{
		$row = null;
		if(isset($module->row)) {
			$row = $module->row;
		}
		/*echo "<pre>";
		print_r($module);*/
		//print_r($module->fields);
		$fieldId = $module->fields[$field_name]['id'];
		$label = $module->fields[$field_name]['label'];
		$field_type = $module->fields[$field_name]['field_type'];
		$unique = $module->fields[$field_name]['unique'];
		$field_visibility = $module->fields[$field_name]['visibility'];
		$defaultvalue = $module->fields[$field_name]['defaultvalue'];
		$minlength = $module->fields[$field_name]['minlength'];
		$maxlength = $module->fields[$field_name]['maxlength'];
		$popup_vals = $module->fields[$field_name]['popup_vals'];
		$popup_field_id = $module->fields[$field_name]['popup_field_id'];
		$popup_field_name = $module->fields[$field_name]['popup_field_name'];
		$field_type = ModuleFieldTypes::find($field_type);
		
		$out = '';
		
		switch ($field_type->name) {
			case 'Address':
			case 'Email':
			case 'File':
			case 'Files':
			case 'HTML':
			case 'Mobile':
			case 'Name':
			case 'String':
			case 'Taginput':
			case 'TextField':
				$out.= '{
					field:"'.$field_name.'",
					title:"'.$label.'"';
				break;
			case 'Checkbox':
			case 'Decimal':
			case 'Float':
			case 'Integer':
			case 'Currency':
				$editor = ''.LAHelper::convertStringToFunctionName($field_name,[]).'Editor';
				$out.= '{
					field:"'.$field_name.'",
					title:"'.$label.'",
					editor:'.$editor.'';
				break;
			case 'Textarea':
				$template = '#if('.$field_name.' && '.$field_name.'.length>100){# # var myContent ='.$field_name.'; #  # var dcontent = myContent.substring(0,100); # <span title=\'${'.$field_name.'}\'>#=kendo.toString(dcontent)#...</span> #}#';
				$editor = ''.LAHelper::convertStringToFunctionName($field_name,[]).'Editor';
				$out.= '{
					field:"'.$field_name.'",
					title:"'.$label.'",
					template:"'.$template.'",
					width:"200px",
					editor:'.$editor.'';
				break;
			case 'Password':
				$template = '********';
				$editor = ''.LAHelper::convertStringToFunctionName($field_name,[]).'Editor';
				$out.= '{
					field:"'.$field_name.'",
					title:"'.$label.'",
					template:"'.$template.'",
					editor:'.$editor.'';
				break;
			case 'Date':
				$format = '{0:dd MMM yyyy}';
				$template = '#if(data.'.$field_name.'){# #= kendo.toString(new Date('.$field_name.'), \'dd MMM yyyy\')# #}#';
				$editor = ''.LAHelper::convertStringToFunctionName($field_name,[]).'Editor';
				$out.= '{
					field:"'.$field_name.'",
					title:"'.$label.'",
					template:"'.$template.'",
					format:"'.$format.'",
					editor:'.$editor.'';
				break;
			case 'Datetime':
				$format = '{0:yyyy-MM-dd HH:mm:ss}';
				$template = '#if(data.'.$field_name.'){# #= kendo.toString(new Date('.$field_name.'), \'dd MMM yyyy hh:mm tt\')# #}#';
				$editor = ''.LAHelper::convertStringToFunctionName($field_name,[]).'Editor';
				$out.= '{
					field:"'.$field_name.'",
					title:"'.$label.'",
					template:"'.$template.'",
					format:"'.$format.'",
					editor:'.$editor.'';
				break;
			case 'Dropdown':
			case 'AutoComplete':
				$editor = ''.LAHelper::convertStringToFunctionName($field_name,[]).'Editor';
			if(starts_with($popup_vals, "@")) {
				$template = '#:'.$popup_field_name.'#';
				$out.= '{
					field:"'.$field_name.'",
					title:"'.$label.'",
					template:"'.$template.'",
					editor:'.$editor.'';
			} else {
				$out.= '{
					field:"'.$field_name.'",
					title:"'.$label.'",
					editor:'.$editor.'';
			}
				break;
			case 'Image':
				$template = '<div>#if(data.'.$field_name.'){# <img src=\'#= '.$field_name.' #\' alt=\'image\' width=\'100\' height=\'50\' /># }#</div>';
				$out.= '{
					field:"'.$field_name.'",
					title:"'.$label.'",
					template:"'.$template.'"';
				break;
			case 'Multiselect':
				$editor = ''.LAHelper::convertStringToFunctionName($field_name,[]).'Editor';
			if(starts_with($popup_vals, "@")) {
				$template = ''.$field_name.'Display';
				$out.= '{
					field:"'.$field_name.'",
					title:"'.$label.'",
					template:'.$template.',
					editor:'.$editor.'';
			} else {
				$template = '#if('.$field_name.') {# #= '.$field_name.'.join(\', \')# #} #';
				$out.= '{
					field:"'.$field_name.'",
					title:"'.$label.'",
					template:"'.$template.'",
					editor:'.$editor.'';
			}
				break;
			case 'Radio':
				$template = ''.$field_name.'Display';
				$editor = 'function() {return false}';
				$out.= '{
					field:"'.$field_name.'",
					title:"'.$label.'",
					template:'.$template.',
					editable:'.$editor.'';
				break;
			case 'URL':
				$template = '#if(data.'.$field_name.'){# <a target=\'_blank\' href=\'#='.$field_name.'#\'>#='.$field_name.'#</a>#}#';
				$out.= '{
					field:"'.$field_name.'",
					title:"'.$label.'",
					template:"'.$template.'"';
				break;
		}
				if(in_array($fieldId, array_column($moduleGroupFields, 'field_id')) && starts_with($popup_vals, "@") && $field_visibility != self::INIT_VALUE)
				{
					$out.= ',
					groupHeaderTemplate: "'.$label.': #=get'.LAHelper::convertStringToFunctionName($label,true).'(value)#"';
				}
				if($field_visibility == self::INIT_VALUE)
				{
					$out.= ',
					hidden:true
				}';
				} else {
					$out.= '
				}';
				}
		return $out;
	}
	final public static function kendo_input_command($commandName)
	{
		if($commandName == "Popup")
		{
			$actionOutput= '{
					command:[{text:"popup",
						click:popupAction
					}],
					title:"Action"
				}';
		} else {
			$actionOutput= '{
					command:[{
						name: "destroy",
						text:" ",
					}],
					title: "&nbsp;",
					width: "80px"
				},';
		}
		
		return $actionOutput;
	}

	final public static function kendo_action_function()
	{
		$actionFunction = 'function popupAction(gridRowObject) {
			var dataItem = this.dataItem($(gridRowObject.currentTarget).closest("tr"));
			var nodeId = ""; //dataItem.nodeId;
		    var identityTableId = ""; //dataItem.identityTableId;
		    var targetId = ""; //dataItem.ref_id;
		    var targetTable = ""; //dataItem.ref_table;
		    var targetColumn = ""; //dataItem.ref_column;
		    var identityId = ""; // $("#identity_id").val();
			var targetTableDataSource = new kendo.data.DataSource({
		        pageSize: 5,
		        transport: {
		            read: {
		                url: requestUrl + "/getTargetNodeDetails?node_id=" + nodeId + "&identity_table=" + identityTableId + "&identity_id=" + identityId + "&target_id="+targetId+"&target_table="+targetTable+"&target_column="+targetColumn,
		                dataType: "json",
		                type: "GET"
		            }
		        },
		        schema: {
		            model: {
		                id: "id",
		                fields: {
		                    id: {
		                        type: "number"
		                    },
		                    target_id: {
		                        type: "number",
		                        editable: false
		                    },
		                }
		            },
		        }
		    });
		    $("#targetTableData").data("kendoGrid").setDataSource(targetTableDataSource);
			$("#top_modal").modal("show");
		}

		function targetTableEditor(container, options) {
			$("<input data-text-field=\'table_name\' data-value-field=\'table_name\' data-bind=\'value:" + options.field + "\'/>").appendTo(container).kendoDropDownList({
		        dataSource: {
		            transport: {
		                read: {
		                    dataType: "json",
		                    url: requestUrl + "/getTargetTables",
		                    type: "GET"
		                }
		            }
		        },
		        dataTextField: "table_name",
		        dataValueField: "table_name"
		    });
		}

		function tableColumnsEditor(container, options) {
		    var targetTable = options.model.target_table;
		    $("<input data-text-field=\'column_name\' data-value-field=\'column_name\' data-bind=\'value:" + options.field + "\'/>").appendTo(container).kendoDropDownList({
		        dataSource: {
		            transport: {
		                read: {
		                    data: {
		                        target_table: targetTable,
		                    },
		                    dataType: "json",
		                    url: requestUrl + "/getTargetTableColumns",
		                    type: "GET"
		                }
		            }
		        },
		        dataTextField: "column_name",
		        dataValueField: "column_name"
		    });
		}';
		return $actionFunction;
	}

	final public static function target_action_grid()
	{
		$targetActionGrid = 'var targetTableGrid = jQuery("#targetTableData").kendoGrid({
	        pageable: {
	            refresh: true,
	            pageSizes: true
	        },
	        scrollable: true,
	        autoSync: false,
	        sortable: true,
	        reorderable: true,
	        groupable: true,
	        resizable: true,
	        editable: true,
	        columns: [{
	            field: "id",
	            title: "Id#",
	            hidden: true,
	        }, {
	            field: "table_id",
	            title: "table_id#",
	            hidden: true,
	        }, {
	            field: "target_table",
	            title: "Target Table",
	            editor: targetTableEditor,
	            width: "150px",
	        }, {
	            field: "target_column",
	            title: "Target Column",
	            editor: tableColumnsEditor,
	            width: "100px"
	        }, {
	            field: "target_id",
	            title: "Target Id",
	            template: "#=(target_id > 0)?target_id:\' \'#",
	            width: "100px"
	        }],
	    });';
	    return $targetActionGrid;
	}


	final public static function kendoSelectAllColumn($primaryKey)
	{

		$checkBoxColumnOutput= '{
					headerTemplate:"<input type=\'checkbox\' class=\'allSelectRow\' />",
					template: "<input type=\'checkbox\' class=\'selectRow\' data-bind=\'checked: checked\' primaryKey=\'#='.$primaryKey.'#\' />",
					width: "20px",
					filterable: false
				},';
		return $checkBoxColumnOutput;
	}

	final public static function kendoSnippetFile($fileName,$moduleName)
	{
		$snippetFilePath= '<script type="text/javascript" src="{{asset(\'assets/js/custom_js/'.$moduleName.'/'.$fileName.'\')}}"></script>';
		return $snippetFilePath;
	}
	final public static function kendoSelectFunctionColumn($moduleName,$primaryKey)
	{

		$selectCheckboxFunction= '
		$("#'.LAHelper::convertStringToFunctionName($moduleName,[]).'Grid").on("click", ".allSelectRow", function(eventData) {
        	var checkedData = eventData.target.checked;
        	$(".selectRow").each(function (idx, item) {
	            if (checkedData) {
	                if(!$(this).prop("checked") == true){
	                    $(this).click();
	                }
	            } else {
	                if($(this).prop("checked") == true){
	                    $(this).click();
	                }
	            }
	        });
	    });

	    $("#'.LAHelper::convertStringToFunctionName($moduleName,[]).'Grid").on("click", ".selectRow", function(eventData) {
	    	var checkedStatus = this.checked,
	        rowData = $(this).closest("tr"),
	        gridObject = $("#'.LAHelper::convertStringToFunctionName($moduleName,[]).'Grid").data("kendoGrid"),
	        dataItem = gridObject.dataItem(rowData);
	        checkedIds[dataItem.'.$primaryKey.'] = checkedStatus;
	        var numChkBoxes = $(".selectRow").length;
	        var numChkBoxesChecked = $(".selectRow:checkbox:checked").length;
	        if (numChkBoxes == numChkBoxesChecked && numChkBoxes > 0) {
	            $(".allSelectRow").prop("checked", true);
	        }
	        else {
	            $(".allSelectRow").prop("checked", false);
	        }
	    });';
	    return $selectCheckboxFunction;
	}
	
	final public static function kendoBatchActionFunction($moduleName,$primaryKey)
	{

		$batchActionFunction= '
		function submitBatchData(eventType) 
		{
			$(\'.content .preloader\').show();
		    $(\'.content .preloader img\').show();
		    var checkedData = [];
		    if(eventType == \'selected\')
	        {
	            for(var i in checkedIds){
	                if(checkedIds[i]){
	                    checkedData.push(i);
	                }
	            }
	        } else {
	        	$(".selectRow").each(function (idx, item) {
	        		checkedData.push($(this).attr("primaryKey"));
		        });
	        }
		    if(typeof checkedData !== \'undefined\' && checkedData.length > 0)
		    {
		        console.log(checkedData);
	        } else {
		    	toastr.options = {
				   "closeButton": true,
				   "positionClass": "toast-top-right",
				   "showDuration": "1000",
				   "hideDuration": "1000",
				   "timeOut": "5000",
				   "extendedTimeOut": "1000",
				   "showEasing": "swing",
				   "hideEasing": "swing",
				   "showMethod": "show"
				};
				var $toast = toastr["error"]("","Please select at least one row");
		    }
		    $(\'.content .preloader\').hide();
	        $(\'.content .preloader img\').hide();
		}

		function onDataBound(eventObject) {
	        var items = this.dataSource.data();
	        var gridDataBound = $("#'.LAHelper::convertStringToFunctionName($moduleName,[]).'Grid").data(\'kendoGrid\');
			var count = gridDataBound.dataSource.total();
	        for (i = 0; i < count; i++) {

	        	var rowData = gridDataBound.tbody.find("tr[data-uid=\'" + items[i].uid+ "\']");
	        	var checkbox = rowData.find(\'.selectRow\');
	        	var checked = checkbox.checked,
		        dataItem = gridDataBound.dataItem(rowData);
		        checkedIds[dataItem.'.$primaryKey.'] = checked;
	        }
	    }';
	    return $batchActionFunction;
	}
	
	final public static function kendoGroupByAction($fieldName,$sortOrder)
	{
		$groupByAction= '{
                field: "'.$fieldName.'",
                dir: "'.$sortOrder.'"
            }';
        return $groupByAction;
	}
	final public static function kendoGroupByFunction($fieldRow,$moduleName)
	{
		$groupByFuncation= '
			function get'.LAHelper::convertStringToFunctionName($fieldRow->label,true).'('.$fieldRow->colname.')
			{
				var gridObject = jQuery("#'.LAHelper::convertStringToFunctionName($moduleName,[]).'Grid").data(\'kendoGrid\').dataSource.data();
				var gridRowObject = $.grep(
			        gridObject,
			        function(item,indx){
			            return (item.'.$fieldRow->colname.' == '.$fieldRow->colname.');
			        }
			    )[0];//get the first record
				return gridRowObject.'.$fieldRow->popup_field_name.';
			}
			';
		return $groupByFuncation;
	}
	
	
	
	final public static function kendo_template($module, $field_name, $default_val = null, $required2 = null, $class = 'form-control', $params = [])
	{
		$row = null;
		if(isset($module->row)) {
			$row = $module->row;
		}
		$label = $module->fields[$field_name]['label'];
		$field_type = $module->fields[$field_name]['field_type'];
		$unique = $module->fields[$field_name]['unique'];
		$requiredField = $module->fields[$field_name]['required'];
		$defaultvalue = $module->fields[$field_name]['defaultvalue'];
		$minlength = $module->fields[$field_name]['minlength'];
		$maxlength = $module->fields[$field_name]['maxlength'];
		$popup_vals = $module->fields[$field_name]['popup_vals'];
		$popup_field_id = $module->fields[$field_name]['popup_field_id'];
		$popup_field_name = $module->fields[$field_name]['popup_field_name'];
		$field_type = ModuleFieldTypes::find($field_type);
		
		$templateOutput = '';
		switch ($field_type->name) {
			case 'Date':
				$templateOutput.= '
				function '.LAHelper::convertStringToFunctionName($field_name,[]).'Editor(container, options) {
			        $("<input type=\'text\' data-text-field=\'" + options.field + "\' data-value-field=\'" + options.field + "\' data-bind=\'value:" + options.field + "\' data-format=\'" + options.format + "\'/>")
			        .appendTo(container)
			        .kendoDatePicker({});
			    }
			    ';
				break;
			case 'Datetime':
				$templateOutput.= '
				function '.LAHelper::convertStringToFunctionName($field_name,[]).'Editor(container, options) {
			        $("<input type=\'text\' data-text-field=\'" + options.field + "\' data-value-field=\'" + options.field + "\' data-bind=\'value:" + options.field + "\' data-format=\'" + options.format + "\'/>")
			        .appendTo(container)
			        .kendoDateTimePicker({});
			    }
			    ';
				break;
			case 'Password':
				$templateOutput.= '
				function '.LAHelper::convertStringToFunctionName($field_name,[]).'Editor(container, options) {
			        $("<input data-text-field=\'" + options.field + "\' class=\'k-input k-textbox\' type=\'password\' data-value-field=\'" + options.field + "\' data-bind=\'value:" + options.field + "\' />").appendTo(container);
			    }
			    ';
				break;
			case 'Dropdown':
				if(starts_with($popup_vals, "@")) {
					$templateOutput.= '
					function '.LAHelper::convertStringToFunctionName($field_name,[]).'Editor(container, options) {
				        $("<input name=\'" + options.field + "\' required type=\'text\' data-text-field=\''.$popup_field_name.'\' data-value-field=\''.$popup_field_id.'\' data-bind=\'value:" + options.field + "\' />")
				        .appendTo(container)
				        .kendoDropDownList({
							autoBind: true,
							dataTextField: "'.$popup_field_name.'",
				            dataValueField: "'.$popup_field_id.'",
				            dataSource: {
				                transport : {
									read : {
				                        dataType : \'json\',
				                        url : requestUrl+"/get'.LAHelper::convertStringToFunctionName($field_name,true).'List",
				                        type : "GET"
				                    }
				                }
				            }
				        });
				    }
				    ';
				} else {
					if(isset($popup_vals) && !!$popup_vals) {
						$popup_vals = KendoColumnMaker::kendo_process_values($popup_vals,$popup_field_id,$popup_field_name);
					} else {
						$popup_vals = array();
					}
					/*To get the javascript array from php array.. we can not directely give array as a concatenate string so need to add impode with inverted comma */
					$popup_vals = "['".implode ("','",$popup_vals )."']";
					$templateOutput.= '
					var '.$field_name.'Data = '.$popup_vals.';
					function '.LAHelper::convertStringToFunctionName($field_name,[]).'Editor(container, options) {
				        $("<input name=\'" + options.field + "\'/>")
				        .appendTo(container)
				        .kendoDropDownList({
							autoBind: true,
				            dataSource: '.$field_name.'Data
				        });
				    }
				    ';
				}
				break;
			case 'Multiselect':
				if(starts_with($popup_vals, "@")) {
					$templateOutput.= '
					function '.LAHelper::convertStringToFunctionName($field_name,[]).'Editor(container, options) {
				        $("<select data-text-field=\''.$popup_field_name.'\' data-value-field=\''.$popup_field_id.'\' data-bind=\'value:'.$popup_field_id.'\' />")
				        .appendTo(container)
				        .kendoMultiSelect({
							autoBind: true,
							dataSource: {
				                transport : {
									read : {
				                        dataType : \'json\',
				                        url : requestUrl+"/get'.LAHelper::convertStringToFunctionName($field_name,true).'List",
				                        type : "GET"
				                    }
				                }
				            }
				        });
				    }

				    function '.$field_name.'Display('.$field_name.'Data) {
						if('.$field_name.'Data.hasOwnProperty(\''.$popup_field_id.'\'))
	                    {
					        var res = [];
		                    $.each('.$field_name.'Data.'.$popup_field_id.', function (idx, elem) {
		                        res.push(elem.'.$popup_field_name.');
		                    });
		                    return res.join(", ");
		                } else {
							return "";
		                }
				    }
				    ';
				} else {
					if(isset($popup_vals) && !!$popup_vals) {
						$popup_vals = KendoColumnMaker::kendo_process_values($popup_vals,$popup_field_id,$popup_field_name);
					} else {
						$popup_vals = array();
					}
					/*To get the javascript array from php array.. we can not directely give array as a concatenate string so need to add impode with inverted comma */
					$popup_vals = "['".implode ("','",$popup_vals )."']";
					$templateOutput.= '
					var '.$field_name.'Data = '.$popup_vals.';
					function '.LAHelper::convertStringToFunctionName($field_name,[]).'Editor(container, options) {

				        $("<select name=\'" + options.field + "\' data-bind=\'value:'.$field_name.'\'/>")
				        .appendTo(container)
				        .kendoMultiSelect({
				            dataSource: '.$field_name.'Data
				        });
				    }
				    ';
				    }
				break;
			case 'Textarea':
				$templateOutput.= '
				function '.LAHelper::convertStringToFunctionName($field_name,[]).'Editor(container, options) {
			        $("<textarea data-bind=\'value:" + options.field + "\' ></textarea>")
			        .appendTo(container);
			    }
			    ';
			    break;
		    case 'Checkbox':
				$templateOutput.= '
				function '.LAHelper::convertStringToFunctionName($field_name,[]).'Editor(container, options) {
					var guid = kendo.guid();
					$("<input class=\'k-checkbox\' id=\'" + guid + "\' type=\'checkbox\' name=\'" + options.field + "\' data-type=\'boolean\' data-bind=\'checked:" + options.field + "\'>").appendTo(container);
                    $("<label class=\'k-checkbox-label\' for=\'" + guid + "\'>&#8203;</label>").appendTo(container);
			    }
			    ';
				break;
			case 'Decimal':
			case 'Float':
			case 'Integer':
			case 'Currency':
				$templateOutput.= '
				function '.LAHelper::convertStringToFunctionName($field_name,[]).'Editor(container, options) {
					$("<input name=\'" + options.field + "\'';
				if(isset($requiredField) && $requiredField==self::FIRST_VALUE) 
				{
					$templateOutput.= ' required=\'required\'';
				}
				$templateOutput.='/>")
					.appendTo(container)
                    .kendoNumericTextBox({';
	                if($field_type->name=='Integer')
	                {
	                	$templateOutput.= '
	                	min: 0,
                        step: 1,
                        decimals:0';
	                } 
	                if($field_type->name=='Currency' || $field_type->name=='Decimal')
	                {
	                	$templateOutput.= '
	                	min: 0,
                        step: 0.00000001,
                        decimals: 8';
	                } 
                $templateOutput.= '
            		});
            		';
        		$templateOutput.= 'var tooltipElement = $("<span class=\'k-invalid-msg\' data-for=\'" + options.field + "\'></span>");
                    tooltipElement.appendTo(container);
            	}';
				break;
			case 'Radio':
			if(starts_with($popup_vals, "@")) {
				
			} else {
				if(isset($popup_vals) && !!$popup_vals) {
					$popup_vals = KendoColumnMaker::kendo_process_values($popup_vals,$popup_field_id,$popup_field_name);
				} else {
					$popup_vals = array();
				}
				/*To get the javascript array from php array.. we can not directely give array as a concatenate string so need to add impode with inverted comma */
				$popup_vals = "['".implode ("','",$popup_vals )."']";
				$templateOutput.= '
				var '.$field_name.'Data = '.$popup_vals.';
				function '.$field_name.'Display(dataItem)
				{
				    var cell = "";
				    var category = dataItem.radio - 1;

				    for (var i = 0; i < '.$field_name.'Data.length; i++) {
				        var item = "";

				        item += "<label>"
				        if (category === i) {
				            item += "<input type=\'radio\' name=\'" + dataItem.uid + "\' onclick=\'set'.$field_name.'Item(this);\' checked=checked />";
				        } else {
				            item += "<input type=\'radio\' name=\'" + dataItem.uid + "\' onclick=\'set'.$field_name.'Item(this);\'/>";
				        }
				        item += '.$field_name.'Data[i];
				        item += "</label>"
				        item += "</br>";

				        cell += item;
				    }
				    return cell;
				}
				';
				$templateOutput.= '
				function set'.$field_name.'Item(item) {
       				 var '.$module->name.'Grid = $("#'.$module->name.'Grid").data("kendoGrid");
		            var rowObject = $(item).closest("tr");
		            var dataItem = '.$module->name.'Grid.dataItem(rowObject);
		            var '.$field_name.'Text = $(item)[0].labels[0].innerText;
		            var ID;

		            for (var i = 0; i < '.$field_name.'Data.length; i++) {
		                if ('.$field_name.'Data[i] === '.$field_name.'Text) {
		                    ID = i;
		                    break;
		                }
		            };

		            dataItem.set("'.$field_name.'", ID + 1);
		        };
	        	';
			}
			break;
			case 'AutoComplete':
				if(starts_with($popup_vals, "@")) {
					$templateOutput.= '
					function '.LAHelper::convertStringToFunctionName($field_name,[]).'Editor(container, options) {
				        $("<input name=\'" + options.field + "\' required type=\'text\' data-text-field=\''.$popup_field_name.'\' data-value-field=\''.$popup_field_id.'\' data-bind=\'value:" + options.field + "\' />")
				        .appendTo(container)
				        .kendoComboBox({
							autoBind: true,
							dataTextField: "'.$popup_field_name.'",
				            dataValueField: "'.$popup_field_id.'",
				            dataSource: {
				                transport : {
									read : {
				                        dataType : \'json\',
				                        url : requestUrl+"/get'.LAHelper::convertStringToFunctionName($field_name,true).'List",
				                        type : "GET"
				                    }
				                }
				            }
				        });
				    }
				    ';
				} else {
					if(isset($popup_vals) && !!$popup_vals) {
						$popup_vals = KendoColumnMaker::kendo_process_values($popup_vals,$popup_field_id,$popup_field_name);
					} else {
						$popup_vals = array();
					}
					/*To get the javascript array from php array.. we can not directely give array as a concatenate string so need to add impode with inverted comma */
					$popup_vals = "['".implode ("','",$popup_vals )."']";
					$templateOutput.= '
					var '.$field_name.'Data = '.$popup_vals.';
					function '.LAHelper::convertStringToFunctionName($field_name,[]).'Editor(container, options) {
				        $("<input name=\'" + options.field + "\'/>")
				        .appendTo(container)
				        .kendoComboBox({
							autoBind: true,
				            dataSource: '.$field_name.'Data
				        });
				    }
				    ';
				}
				break;
		}
		return $templateOutput;
	}

	final public static function kendo_parameter_map($module, $field_name, $default_val = null, $required2 = null, $class = 'form-control', $params = [])
	{
		$row = null;
		if(isset($module->row)) {
			$row = $module->row;
		}
		$label = $module->fields[$field_name]['label'];
		$field_type = $module->fields[$field_name]['field_type'];
		$unique = $module->fields[$field_name]['unique'];
		$defaultvalue = $module->fields[$field_name]['defaultvalue'];
		$minlength = $module->fields[$field_name]['minlength'];
		$maxlength = $module->fields[$field_name]['maxlength'];
		$popup_vals = $module->fields[$field_name]['popup_vals'];
		$popup_field_id = $module->fields[$field_name]['popup_field_id'];
		$popup_field_name = $module->fields[$field_name]['popup_field_name'];
		$field_type = ModuleFieldTypes::find($field_type);
		
		$templateOutput = '';
		switch ($field_type->name) {
			case 'Date':
				$templateOutput.= '
						options.'.$field_name.' = kendo.toString(new Date(options.'.$field_name.'), "MM/dd/yyyy");';
				break;

			case 'Datetime':
				$templateOutput.= '
						options.'.$field_name.' = kendo.toString(new Date(options.'.$field_name.'), "MM/dd/yyyy HH:mm:ss");';
				break;
		}
		return $templateOutput;
	}
	
	final public static function kendo_search_param($field_name)
	{
		$templateOutput= '{ 
				field   : "'.$field_name.'",
        		operator: "contains",
        		value   : eventSearchValue
      		},';
		return $templateOutput;
	}


	final public static function kendo_schema_fields($module, $field_name, $default_val = null, $required2 = null, $class = 'form-control', $params = [])
	{
		$templateOutput = '';
		$field_type = $module->fields[$field_name]['field_type'];
		$field_type = ModuleFieldTypes::find($field_type);
		$requiredField = $module->fields[$field_name]['required'];
		$minlength = $module->fields[$field_name]['minlength'];
		$maxlength = $module->fields[$field_name]['maxlength'];
		switch ($field_type->name) {
			case 'String':
			case 'Name':
			case 'Password':
			case 'TextField':
			case 'URL':
				$templateOutput.= ''.$field_name.' : { 
							validation: { ';
				if($requiredField)
				{
					$templateOutput.= 'required: true,
							';
				} 
				$templateOutput.= '
								maxlength: function (input) {
									if (input.val().length != 0) {
										if (input.val().length > '.$maxlength.') {
											input.attr("data-maxlength-msg", "Max length is '.$maxlength.'");
				                               return false;
								        }
								        if (input.val().length < '.$minlength.') {
											input.attr("data-maxlength-msg", "Min length is '.$minlength.'");
				                               return false;
								        }
								    }
							        return true;
							    }
							} 
						},
					 ';
				break;
			case 'Address':
			case 'Textarea':
				if($maxlength != self::INIT_VALUE) 
				{
					$templateOutput.= ''.$field_name.' : { 
							validation: { ';
					if($requiredField)
					{
						$templateOutput.= 'required: true,
								';
					} 
					$templateOutput.= '
									maxlength: function (input) {
										if (input.val().length != 0) {
											if (input.val().length > '.$maxlength.') {
												input.attr("data-maxlength-msg", "Max length is '.$maxlength.'");
					                               return false;
									        }
									    }
								        return true;
								    }
								} 
							},
						 ';
				}
				break;
			case 'Checkbox':
				$templateOutput.= ''.$field_name.' : { type: "boolean"';
				if($requiredField)
				{
					$templateOutput.= ', validation: { required: true } },
								';
				} else {
					$templateOutput.= '},
								';
				}
				break;
			case 'Float':
			case 'Integer':
				$templateOutput.= ''.$field_name.' : { type: "number"';
				if($requiredField)
				{
					$templateOutput.= ', validation: { required: true } },
								';
				} else {
					$templateOutput.= '},
								';
				}
				break;
			case 'Email':
				$templateOutput.= ''.$field_name.' : { validation: { email: true';
				if($requiredField)
				{
					$templateOutput.= ', required: true } },
					';
				} else {
					$templateOutput.= '} },
					   ';
				}
				break;
			case 'Mobile':
				$templateOutput.= ''.$field_name.' : { 
							validation: { ';
				if($requiredField)
				{
					$templateOutput.= 'required: true,
							';
				} 
				$templateOutput.= '
								Mobilevalidation: function (input) {
									if (input.is(\'[name="'.$field_name.'"]\')) {
							            if ((input.val()) == \'\') {
							                input.attr("data-Mobilevalidation-msg", "Mobile number required");
							                return /^[A-Z]/.test(input.val());
							            }
							            else {
							                if (/^\d+$/.test(input.val())) {
							                    if (input.val().length == 10) {
							                        return true;
							                    } else {
							                        input.attr("data-Mobilevalidation-msg", "Mobile number must be 10 digit");
							                        return /^[A-Z]/.test(input.val());
							                    }

							                }
							                else {
							                    input.attr("data-Mobilevalidation-msg", "Mobile number is invalid");
							                    return /^[A-Z]/.test(input.val());
							                }
							                return true;
							            }
							        }
							        return true;
							    }
							} 
						},
					 ';
				break;
			default:
        		if($requiredField)
				{
					$templateOutput.= ''.$field_name.' : { validation: { required: true } },
								';
				}
		}
		return $templateOutput;
	}

	final public static function kendo_controller_Create($module, $field_name, $default_val = null, $required2 = null, $class = 'form-control', $params = [])
	{
		$row = null;
		if(isset($module->row)) {
			$row = $module->row;
		}
		$field_type = $module->fields[$field_name]['field_type'];
		$popup_vals = $module->fields[$field_name]['popup_vals'];
		$popup_field_id = $module->fields[$field_name]['popup_field_id'];
		$popup_field_name = $module->fields[$field_name]['popup_field_name'];
		$field_type = ModuleFieldTypes::find($field_type);
		$popup_vals = str_ireplace("@", "", $popup_vals);
		$templateOutput = '';
		switch ($field_type->name) {
			case 'Dropdown':
			case 'AutoComplete':
				if(starts_with($module->fields[$field_name]['popup_vals'], "@")) {
					$templateOutput.= '
					function get'.LAHelper::convertStringToFunctionName($field_name,true).'List(Request $request) {
						$'.$field_name.'Details =  DB::connection("mysqlDynamicConnector")->table("'.$popup_vals.'")->select("'.$popup_field_id.'","'.$popup_field_name.'")->get()->toArray();
						return json_encode($'.$field_name.'Details);
					}
					';
				}
				break;
			case 'Multiselect':
				if(starts_with($module->fields[$field_name]['popup_vals'], "@")) {
					$templateOutput.= '
					function get'.LAHelper::convertStringToFunctionName($field_name,true).'List(Request $request) {
						$'.$field_name.'Details =  DB::connection("mysqlDynamicConnector")->table("'.$popup_vals.'")->select("'.$popup_field_id.'","'.$popup_field_name.'")->get()->toArray();
						return json_encode($'.$field_name.'Details);
					}
					';
				}
				break;
		}
		return $templateOutput;
	}

	final public static function target_action_node_function()
	{
		$targetTableNodeFunction = 'public function getTargetNodeDetails(Request $request)
	    {
	        $targetInfo["target_table"]  = "";
	        $targetInfo["target_column"] = "";
	        $targetInfo["target_id"]     = "";
	        return json_encode($targetInfo);
	    }';

	    return $targetTableNodeFunction;
	}


	/**
	* Processes the populated values for Multiselect / Taginput / Dropdown
	* get data from module / table whichever is found if starts with '@'
	**/
	// $values = LAFormMaker::process_values($data);
	final public static function kendo_process_values($json,$field_value="id",$field_text="name") {
		$out = array();
		// Check if populated values are from Module or Database Table
		if(is_string($json) && starts_with($json, "@")) {
			
			// Get Module / Table Name
			$json = str_ireplace("@", "", $json);
			$table_name = strtolower($json);

			// Search Module
			$module = Module::getByTable($table_name);
			if(isset($module->id)) {
				$out = Module::getDDArray($module->name);
			} else {
				// Search Table if no module found
				if (Schema::hasTable($table_name)) {
					$out = \DB::table($table_name)->select($field_value,$field_text)->get()->toArray();
				} else if(Schema::hasTable($json)) {
					// $array = \DB::table($table_name)->get();
				}
			}
		} else if(is_string($json)) {
			$array = json_decode($json);
			if(is_array($array)) {
				foreach ($array as $value) {
					$out[] = $value;
				}
			} else {
				// TODO: Check posibility of comma based pop values.
			}
		} else if(is_array($json)) {
			foreach ($json as $value) {
				$out[$value] = $value;
			}
		}
		return $out;
	}
	
	/**
	* Check Whether User has Module Access
	**/
	final public static function la_access($module_id, $access_type = "access", $user_id = self::INIT_VALUE)
	{
		return Module::hasAccess($module_id, $access_type, $user_id);
	}

	final public static function kendoGroupHeaderFunction($fieldRow,$moduleName)
	{
		$groupHeaderFunction= '
			function get'.LAHelper::convertStringToFunctionName($fieldRow['label'],true).'('.$fieldRow["colname"].')
			{
				var gridObject = jQuery("#'.LAHelper::convertStringToFunctionName($moduleName,[]).'Grid").data(\'kendoGrid\').dataSource.data();
				var gridRowObject = $.grep(
			        gridObject,
			        function(item,indx){
			            return (item.'.$fieldRow["colname"].' == '.$fieldRow["colname"].');
			        }
			    )[0];//get the first record
				return gridRowObject.'.$fieldRow["popup_field_name"].';
			}
			';
		return $groupHeaderFunction;
	}
}



