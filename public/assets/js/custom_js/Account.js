$(document).ready(function() {

	var token = $('input[name="_token"]').val();
	var requestUrl = $("#requestUrl").val();

	$("#tabstrip").kendoTabStrip().data("kendoTabStrip");

	

	$("#referrer_id").kendoComboBox({
		placeholder: "Select Account...",
		dataTextField: "account_code",
		dataValueField: "account_id",
		filter: "contains",
		height: 400,
		dataSource: {
			transport : {   
				read : {
					dataType : "json",
					url : requestUrl+"/getAccounts",
					type : "GET",
				}
			}
		}
	}).data("kendoComboBox");


	$("#asset_id").kendoMultiSelect({
		placeholder: "Enter Assets...",
		dataTextField: "asset_code",
		dataValueField: "asset_id",
		filter: "contains",
		height: 400,
		dataSource: {
			transport : {   
				read : {
					dataType : "json",
					url : requestUrl+"/getAssets/1",
					type : "GET"
				}
			}
		},
		select: function (e) {
			var dataItem = e.dataItem;
			var assets = $("#asset_id").val();
			var asset_id = $("#asset_id").data("kendoMultiSelect");
			var grid = $("#merchantAccountListGrid").data().kendoGrid;
			var data = grid.dataSource.data();
			for(var i = 0; i < data.length; i++){
				if(data[i].asset_code == dataItem.asset_code){
					var select = grid.tbody.find('tr[data-uid="' +  data[i].uid + '"]');
					grid.select(select);
					setTimeout(function(){
						// asset_id.value(assets);
					},50);
					// asset_id.value([]);
					return false;
				}
			}
			// var selectedRow = grid.select();
			// var selectedDataItem = grid.dataItem(selectedRow);
			// console.log(selectedDataItem.asset_code);
		}
	}).data("kendoMultiSelect");

	$("#merchant_id").kendoComboBox({
		placeholder: "Select Merchant...",
		dataTextField: "merchant_name",
		dataValueField: "merchant_id",
		filter: "contains",
		height: 400,
		dataSource: {
			transport : {   
				read : {
					dataType : "json",
					url : requestUrl+"/getMerchants",
					type : "GET",
				}
			}
		},
		change: function (e) {
			if($('#merchant_id').val() != ""){
				var referrer_id = $("#referrer_id").data("kendoComboBox");
				var asset_id = $("#asset_id").data("kendoMultiSelect");


				var merchantAccountListDataSource = new kendo.data.DataSource({
					transport : {   
						read : {
							data:{_token:token,merchant_id:$('#merchant_id').val()},
								url: requestUrl+"/merchant_account_list",
								dataType: "json",
								type: "POST"
							}
						}
					});
				$("#merchantAccountListGrid")
				.data("kendoGrid")
				.setDataSource(merchantAccountListDataSource);

				referrer_id.enable(true);
				asset_id.enable(true);
			}
		}
	}).data("kendoComboBox");

	function onSave(data) {

		console.log(data);

		var account_id = data.model.account_id;
		var key="";var value=0;var table="";
		
		if(data.values.enable){
			key = "enable";
			value = data.values.enable;
		}else if(data.values.priority){
			key = "priority";
			value = data.values.priority;
		}else if(data.values.referrer_id){
			key = "referrer_id";
			value = data.values.referrer_id;
		}else if(data.values.account_code_long){
			key = "account_code_long";
			value = data.values.account_code_long;
		}else if(data.values.account_description){
			key = "identity_description";
			value = data.values.account_description;
		}else if(data.values.account_name){
			key = "identity_name";
			value = data.values.account_name;
		}else{
			return false;
		}

		$.ajax({
			type: 'POST',
			data:{account_id:account_id,key:key,value:value,_token:token},
			url: requestUrl+"/updateList",
			success: function (eventData) {
				$("#merchantAccountListGrid").data("kendoGrid").dataSource.read();
			}
		});
	}

	var merchantAccountListGrid = jQuery("#merchantAccountListGrid").kendoGrid({
		dataSource: {
			serverPaging: true,
			pageSize: 20,
			schema: {
				model: {
					account_id:'account_id',
					fields: {
						priority: {
							type: "number",
							validation: { required: true,min: 0,max:99 }
						},
						enable: {
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
			var columnIndex = [0,1,5,7,8];
			if($.inArray(parseInt(e.container.index()),columnIndex) != -1){
				this.closeCell(); 
			}
		},
		columns: [
		{
			field: "account_id",
			title: "Account Id",
			hidden:true,
		}, {
			field: "merchant_code",
			title: "Merchant Code",
		}, {
			field: "account_name",
			title: "Account Name",
		}, {
			field: "account_description",
			title: "Account Description",
		}, {
			field: "account_code_long",
			title: "Account Code",
		}, {
			field: "asset_code",
			title: "Asset Code",
		}, {
			field: "referrer_id",
			title: "Referrer ID",
			editor: referrerDropDownEditor,
			template: "#=data.referrer_account_code#"
		}, {
			field: "asset_quantity",
			title: "Asset Quantity",
		}, {
			field: "asset_price",
			title: "Asset Price",
		},{
			field: "priority",
			title: "Priority",
		},{
			field: "enable",
			title: "Status",
			editor: statusDropDownEditor,
			template: "#=(data.enable)?'Enable':'Disable'#"
		}
		],
	});

	function statusDropDownEditor(container, options) {
		console.log(options);
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

	function referrerDropDownEditor(container, options) {
		$('<input data-text-field="account_code" data-value-field="account_id" data-bind="value:' + options.field + '"/>')
			.appendTo(container)
			.kendoDropDownList({
				dataSource: {
					transport : {   
						read : {
							dataType : "json",
							url : requestUrl+"/getAccounts",
							type : "GET"
						}
					}
				},
				dataTextField: "account_code",
				dataValueField: "account_id"
			});
	}

	var validator = $("#merchantAccountListForm").kendoValidator({
		rules: {
			hasMerchant: function (input) {
				if(input.is("[id=merchant_id]")){
					var ms = input.data("kendoComboBox");       
					if(ms.value().length === 0){
						return false;
					}
				} 
				return true;
			},
		},
		messages: { 
			hasMerchant: "Merchant Required"
		}
	}).data("kendoValidator");

	$('#submitBtn').click(function(){
		validator.validate();
		$.ajax({
			type: 'POST',
			data:$('#merchantAccountListForm').serialize(),
			url: requestUrl+"/createMerchantAccount",
			success: function (eventData) {
				$("#merchant_id").data("kendoComboBox").trigger("change");
				$('#merchantAccountListForm').trigger("reset");				
			}
		});
	});
});