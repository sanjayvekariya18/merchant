var constant = {
	TOKEN						: 	$("input[name=_token]").val(),
	ACTION_URL					: 	$('#identityPayeeListForm').attr('action'),
	FORM_ID 					: 	"#identityPayeeListForm",
	CREATE_PAYEE_LIST			: 	"/createPayeeList",

    DROPDOWN_ID 				: 	"#identity_table_id",
    DROPDOWN_PRIMARY_ID 		: 	"identity_table_id",
    DROPDOWN_PRIMARY_VAL 		: 	"identity_table_name",

    COMBOBOX_ID 				: 	"#identity_id",
    GET_IDENTITY_METHOD_NAME	: 	"/getIdentities",
    COMBOBOX_PRIMARY_ID			: 	"identity_id",
    COMBOBOX_PRIMARY_VAL		: 	"identity_name",
    COMBO_TYPE_ERROR_MESSAGE	:	"Identity Required",

    MULTI_SELECT_DROPDOWN_ID 	: 	"#payee_id",
    MULTI_METHOD_NAME		 	: 	"/getPayeeList",
    MULTI_DROPDOWN_PRIMARY_ID	: 	"payee_id",
    MULTI_DROPDOWN_PRIMARY_VAL	: 	"payee_name",
    MULTI_TYPE_ERROR_MESSAGE	:	"Please select at least one Payee",

    GET_LIST_METHOD_NAME		: 	"/getIdentityPayeeList",
    
    KENDO_GRID_ID				: 	"#identityPayeeListGrid",
    KENDO_GRID_PRIMARY_ID		: 	"list_id",

    UPDATE_LIST_METHOD			: 	"/updateList"
}

// function editLocation() {
// 	alert();
// }

$(document).ready(function (){ 


	$(constant.DROPDOWN_ID).kendoDropDownList({
		dataTextField: constant.DROPDOWN_PRIMARY_VAL,
		dataValueField: constant.DROPDOWN_PRIMARY_ID,
		value: 8,
		dataSource:[
			{ "identity_table_name": "Merchants", "identity_table_id": "8" },
			{ "identity_table_name": "Customers", "identity_table_id": "4" },
			{ "identity_table_name": "Peoples",   "identity_table_id": "15" }
		],
		change: function(e){
			var identity_table_id = parseInt(this.value());

			var identityDataSource = new kendo.data.DataSource({
				transport : {   
					read : {
							data:{
								identity_table_id:identity_table_id,
								_token:constant.TOKEN
							},
							url: constant.ACTION_URL+constant.GET_IDENTITY_METHOD_NAME,
							dataType: "json",
							type: "POST"
						}
					}
				});

			kendoIdentity.setDataSource(identityDataSource);
			kendoIdentity.value("");
			kendoPayee.value([]);
		}
	});

	$(constant.COMBOBOX_ID).kendoComboBox({
		placeholder: "Select Identity...",
		dataTextField: constant.COMBOBOX_PRIMARY_VAL,
		dataValueField: constant.COMBOBOX_PRIMARY_ID,
		filter: "contains",
		height: 400,
		change: function (e) {

			if(!this.value()){
				kendoPayee.value([]);
				return false;
			}

			$('.content .preloader').show();
			$('.content .preloader img').show();
			
			kendoGrid.dataSource.read();

			var identity_table_id = kendoIdentityTable.value();
			var identity_id = this.value();

			var payeeListDataSource = new kendo.data.DataSource({
				pageSize: 20,
				transport : {   
					read : {
							data:{
								_token:constant.TOKEN,
								identity_id:identity_id,
								identity_table_id:identity_table_id
							},
							url: constant.ACTION_URL+constant.GET_LIST_METHOD_NAME,
							dataType: "json",
							type: "POST"
						}
					}
				});
			kendoGrid.setDataSource(payeeListDataSource);

			$.ajax({

				type:'POST',
				data:{
					_token:constant.TOKEN,
					identity_id:identity_id,
					identity_table_id:identity_table_id
				},
				url: constant.ACTION_URL+constant.GET_LIST_METHOD_NAME,
				dataType:'json',
				
				success:function(json){
					console.log(json);
					var payees =[];
					$.each(json, function(key, value) {
						payees.push(value.payee_id);
					});
					kendoPayee.value(payees);
				}
			});

			$('.content .preloader').hide();
			$('.content .preloader img').hide();
		}
	}).data("kendoComboBox");

	$(constant.MULTI_SELECT_DROPDOWN_ID).kendoMultiSelect({
		placeholder: "Select Payee...",
		dataTextField: constant.MULTI_DROPDOWN_PRIMARY_VAL,
		dataValueField: constant.MULTI_DROPDOWN_PRIMARY_ID,
		filter: "contains",
		height: 400,
		dataSource: {
			transport : {   
				read : {
					dataType : "json",
					url : constant.ACTION_URL+constant.MULTI_METHOD_NAME,
					type : "GET"
				}
			}
		},
		change: function(e) {
			
		},
	}).data("kendoMultiSelect");

	
	var validator = $(constant.FORM_ID).kendoValidator({
		rules: {
			hasIdentity: function (input) {
				if(input.is("[id="+constant.COMBOBOX_PRIMARY_ID+"]")){
					var ms = input.data("kendoComboBox");       
					if(ms.value().length === 0){
						return false;
					}
				} 
				return true;
			},
			hasPayee: function (input) {
				if(input.is("[id="+constant.MULTI_DROPDOWN_PRIMARY_ID+"]")){
					var ms = input.data("kendoMultiSelect");       
					if(ms.value().length === 0){
						return false;
					}
				} 
				return true;
			},
		},
		messages: { 
			hasIdentity: constant.COMBO_TYPE_ERROR_MESSAGE,
			hasPayee: constant.MULTI_TYPE_ERROR_MESSAGE
		}
	}).data("kendoValidator");

	var kendoIdentityTable 	= $(constant.DROPDOWN_ID).data("kendoDropDownList");
	var kendoIdentity 		= $(constant.COMBOBOX_ID).data("kendoComboBox");
	var kendoPayee 		= $(constant.MULTI_SELECT_DROPDOWN_ID).data("kendoMultiSelect");

	kendoIdentityTable.trigger('change');

	function onSave(data) {
		var list_id = data.model.list_id;
		var key="";
		var value=0;
		
		if(typeof data.values.status !== 'undefined'){
			key = "status";
			value = data.values.status;
		}else if(typeof data.values.priority !== 'undefined'){
			key = "priority";
			value = data.values.priority;
		}else{
			return false;
		}

		$.ajax({
			type: 'POST',
			data:{list_id:list_id,key:key,value:value,_token:constant.TOKEN},
			url: constant.ACTION_URL+constant.UPDATE_LIST_METHOD,
			success: function (eventData) {
				$(constant.KENDO_GRID_ID).data("kendoGrid").dataSource.read();
			}
		});
	}

	var orderTypeList = jQuery(constant.KENDO_GRID_ID).kendoGrid({
		dataSource: {
			serverPaging: true,
			pageSize: 20,
			schema: {
				model: {
					list_id:constant.KENDO_GRID_PRIMARY_ID,
					fields: {
						priority: {
							editable: true,
							type: "number",
							validation: { required: true,min: 0,max:99 }
						},
						status: {
							editable: true,
							defaultValue: { value: 1, text: "Enable"}
						}
					}
				},
			},
			serverFiltering: true,
		},
		pageable: {
			refresh: true,
			pageSizes: true
		},
		scrollable: true,
		autoSync: true,
		sortable: true,
		reorderable: true,
		serverFiltering: true,
		groupable: true,
		resizable: true,
		editable: true,
		selectable:'row',
		save:onSave,
		edit: function(e){
			var columnNotEditableIndex = [0,1,2,3,4,5,6];
			if($.inArray(parseInt(e.container.index()),columnNotEditableIndex) != -1){
				this.closeCell(); 
			}
		},
		columns: [
		{
			field: constant.KENDO_GRID_PRIMARY_ID,
			title: "List Id",
			hidden:true,
		}, {
			field: "identity_code",
			title: "Identity Code",
		}, {
			field: "payee_name",
			title: "Payee",
		}, {
			field: "priority",
			title: "Priority",
		}, {
			field: "status",
			title: "Status",
			editor: statusDropDownEditor,
			template: "#=(data.status)?'Enable':'Disable'#"
		}
		],
	});
	var kendoGrid 			= $(constant.KENDO_GRID_ID).data("kendoGrid");

	function statusDropDownEditor(container, options) {
		var data = [
					{ Description: "Enable", ID: "1" },
					{ Description: "Disable", ID: "0" }
				];
			$('<input data-text-field="Description" data-value-field="ID" data-bind="value:' + options.field + '"/>')
				.appendTo(container)
				.kendoDropDownList ({
					dataSource: data,
					dataTextField: "Description",
					dataValueField:"ID"
			});
	};

	// Submit Form
	
	$("#submitBtn").click(function(){
		if (validator.validate()) {
			$('.content .preloader').show();
			$('.content .preloader img').show();

			$.ajax({
				type: 'POST',
				data:$(constant.FORM_ID).serialize(),
				url: constant.ACTION_URL+constant.CREATE_PAYEE_LIST,
				success: function (data) {
					kendoIdentity.trigger('change');
					$('.content .preloader').hide();
					$('.content .preloader img').hide();
				}
			});
		}
	});
	
});