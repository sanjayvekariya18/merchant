var constant = {
	TOKEN					: 	$("input[name=_token]").val(),
	ACTION_URL				: 	$('#tradeOrderTypeListForm').attr('action'),
	FORM_ID 				: 	"#tradeOrderTypeListForm",

	TREE_NAME 				: 	"topologyTree",
    TREE_ID 				: 	"#topologyTree",
    TREE_SELECTED_NODE_ID	: 	"#topologyTreeResultId",
    TREE_ERROR_ID			: 	"#treeError",
    TREE_METHOD_NAME		: 	"/getOrderTypeListTree",

    DROPDOWN_ID 			: 	"#merchant_id",
    DROPDOWN_METHOD_NAME 	: 	"/getMerchants",
    DROPDOWN_PRIMARY_ID		: 	"merchant_id",
    DROPDOWN_PRIMARY_VAL	: 	"merchant_name",
    DROPDOWN_ERROR_MESSAGE	:	"Merchant Required",

    MULTI_SELECT_ID 		: 	"#type_id",
    MULTI_SELECT_METHOD_NAME: 	"/tradeOrderTypes",
    MULTI_SELECT_PRIMARY_ID	: 	"type_id",
    MULTI_SELECT_PRIMARY_VAL: 	"type_name",
    MULTI_SELECT_ERROR_MESSAGE		:	"Please select at least one Order Type",

    // GET_LIST_METHOD_NAME	: 	"/getOrderTypeList/",
    // GET_LIST_BY_ID_METHOD	: 	"/getOrderTypeListByListID/",
    
    KENDO_GRID_ID			: 	"#orderTypeListGrid",
    KENDO_GRID_METHOD		: 	"/getOrderTypeList/",
    KENDO_GRID_PRIMARY_ID	: 	"list_id",

    UPDATE_LIST_METHOD		: 	"/updateList"
}

$(document).ready(function (){

	var kendoTreeUrl = constant.ACTION_URL+constant.TREE_METHOD_NAME;

	$(constant.DROPDOWN_ID).kendoComboBox({
		placeholder: "Select Merchant...",
		dataTextField: constant.DROPDOWN_PRIMARY_VAL,
		dataValueField: constant.DROPDOWN_PRIMARY_ID,
		filter: "contains",
		height: 400,
		dataSource: {
			transport : {   
				read : {
					dataType : "json",
					url : constant.ACTION_URL+constant.DROPDOWN_METHOD_NAME,
					type : "GET",
				}
			}
		},
		change: function (e) {
			var merchant_id = $(constant.DROPDOWN_ID).val();
			console.log(merchant_id);
			if(merchant_id != ""){
				var requestUrl = constant.ACTION_URL+constant.KENDO_GRID_METHOD+merchant_id;
			}else{
				var requestUrl = constant.ACTION_URL+constant.KENDO_GRID_METHOD+"0";
			}
			var orderListDataSource = new kendo.data.DataSource({
				pageSize: 20,
				transport : {   
					read : {
							url:requestUrl,
							dataType: "json",
							type: "GET"
						}
					}
				});
			$(constant.KENDO_GRID_ID)
			.data("kendoGrid")
			.setDataSource(orderListDataSource);

			var order_type = $(constant.MULTI_SELECT_ID).data("kendoMultiSelect");
			$.ajax({
				type:'GET',
				url:requestUrl,
				dataType:'json',
				success:function(json){
					var types = new Array();
					if (json.length > 0){
						$.each(json, function(index, obj) {
							types[index] = obj.type_id;
						});
					}
					order_type.value(types);
				}
			});
		}
	}).data("kendoComboBox");

	$(constant.MULTI_SELECT_ID).kendoMultiSelect({
		placeholder: "Select Order Types...",
		dataTextField: constant.MULTI_SELECT_PRIMARY_VAL,
		dataValueField: constant.MULTI_SELECT_PRIMARY_ID,
		filter: "contains",
		height: 400,
		dataSource: {
			transport : {   
				read : {
					dataType : "json",
					url : constant.ACTION_URL+constant.MULTI_SELECT_METHOD_NAME,
					type : "GET",
					async:false
				}
			}
		},
		change: function(e) {
			// var grid = $(constant.KENDO_GRID_ID).data("kendoGrid");
			// var filter = { logic: "or", filters: [] };
			// var values = this.value();
			// console.log(values);
			// $.each(values, function(i, v) {
			// 	filter.filters.push({field: "type_id", operator: "eq", value: v });
			// });
			// grid.dataSource.filter(filter);
		}
	}).data("kendoMultiSelect");

	var validator = $(constant.FORM_ID).kendoValidator({
		rules: {
			hasMerchants: function (input) {
				if(input.is("[id="+constant.DROPDOWN_PRIMARY_ID+"]")){
					var ms = input.data("kendoComboBox");       
					if(ms.value().length === 0){
						return false;
					}
				} 
				return true;
			},
			hasOrderSide: function (input) {
				if(input.is("[id="+constant.MULTI_SELECT_PRIMARY_ID+"]")){
					var ms = input.data("kendoMultiSelect");       
					if(ms.value().length === 0){
						return false;
					}
				} 
				return true;
			},
		},
		messages: { 
			hasMerchants: constant.DROPDOWN_ERROR_MESSAGE,
			hasOrderSide: constant.MULTI_SELECT_ERROR_MESSAGE
		}
	}).data("kendoValidator");

	/*function onSelect(e) {
		var treeView = $(constant.TREE_ID).data('kendoTreeView');
		var side_id = $(constant.MULTI_SELECT_ID).data("kendoMultiSelect");
		nodeId = treeView.dataItem(e.node).id;
		$(constant.TREE_SELECTED_NODE_ID).val(nodeId);
		$(constant.TREE_ERROR_ID).hide();
		$.ajax({
			type:'GET',
			url:constant.ACTION_URL+constant.KENDO_GRID_METHOD+nodeId,
			dataType:'json',
			success:function(json){
				var types = new Array();
				if (json.length > 0){
					$.each(json, function(index, obj) {
						types[index] = obj.type_id;
					});
				}
				side_id.value(types);
			}
		});
	}*/

	/*jQuery.getJSON(kendoTreeUrl, function (JsonReturnData) {
		$(constant.TREE_ID).kendoTreeView({
			dataSource: { data: JsonReturnData },
			loadOnDemand: false,
			dataTextField: "text",
			dataValueField: "id",
			select: onSelect
		});
	});*/

	/*function onChange() {
		var orderListGridObject = $(constant.KENDO_GRID_ID).data("kendoGrid");
		var selectedItem = orderListGridObject.dataItem(orderListGridObject.select());
		var treeView = $(constant.TREE_ID).data('kendoTreeView');
		var dropdown = $(constant.MULTI_SELECT_ID).data("kendoMultiSelect");
		$.ajax({
			type:'GET',
			url:constant.ACTION_URL+constant.GET_LIST_BY_ID_METHOD+selectedItem.list_id,
			dataType:'json',
			success:function(json){
				dropdown.value(json.type_id);
				if(json.merchant_id != 0){
					nodeID = json.merchant_id+"_merchant";						
				}else if(json.location_id != 0){
					nodeID = json.location_id+"_city";
				}else if(json.group_id != 0){
					nodeID = json.group_id+"_group";
				}else{
					nodeID = json.staff_id+"_staff";
				}
				treeView.expandTo(nodeID);
				$(constant.TREE_SELECTED_NODE_ID).val(nodeID);
				var getitem = treeView.dataSource.get(nodeID);
				var selectitem = treeView.findByUid(getitem.uid);
				treeView.select(selectitem);
			}
		});
	}*/

	function onSave(data) {
		var orderListGridObject = $(constant.KENDO_GRID_ID).data("kendoGrid");
		var selectedItem = orderListGridObject.dataItem(orderListGridObject.select());

		var list_id = selectedItem.list_id;
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
		// change: onChange,
		save:onSave,
		edit: function(e){
			var columnNotEditableIndex = [0,1,2,3];
			if($.inArray(parseInt(e.container.index()),columnNotEditableIndex) != -1){
				this.closeCell(); 
			}
		},
		columns: [
		{
			field: "list_id",
			title: "List Id",
			width: 100,
			hidden:true,
		},{
			field: "merchant_code",
			title: "Merchant Name",
			width: 100
		}, /*{
			field: "country_name",
			title: "Country",
			width: 100
		},  {
			field: "state_name",
			title: "State",
			width: 100
		}, {
			field: "county_name",
			title: "County",
			width: 100
		}, {
			field: "city_name",
			title: "City Name",
			width: 100
		},   {
			field: "staff_group_name",
			title: "Group Name",
			width: 100
		}, {
			field: "staff_name",
			title: "Staff Name",
			width: 80
		},*/ {
			field: "type_name",
			title: "Order Type",
			width: 70
		},{
			field: "priority",
			title: "Priority",
			width: 100
		},{
			field: "status",
			title: "Status",
			width: 130,
			editor: statusDropDownEditor,
			template: "#=(data.status)?'Enable':'Disable'#"
		}
		],
	});

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

	$('#submitBtn').click(function(){
		if (validator.validate()) {
			$.ajax({
				type: 'POST',
				data:$(constant.FORM_ID).serialize(),
				url: constant.ACTION_URL,
				success: function (eventData) {
					// $(constant.MULTI_SELECT_ID).data("kendoMultiSelect").value([]);
					$("#merchant_id").data("kendoComboBox").trigger('change');
				}
			});
		}
	});
});