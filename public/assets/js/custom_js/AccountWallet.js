$(document).ready(function() {
	
	var token = $('input[name="_token"]').val();
	var requestUrl = $("#requestUrl").val();
	
	// $('#top_modal').modal("show");

	$("#wallet_name").keyup(function(){
		console.log($(this).val());
	});

	$("#wallet_name").keyup(function (e) {
		var searchValue = $(this).val();
		console.log(searchValue);
		aKendoGridWallet.dataSource.filter({
			logic  : "or",
			filters: [{
				field   : "wallet_name",
				operator: "contains",
				value   : searchValue
			}]
		});
	});

	var aKendoWalletName 	= $("#wallet_name");
	var aKendoWalletAddress	= $("#wallet_name");
	var aKendoWalletUrl 	= $("#confirm_url");

	function onSave(data) {

		console.log(data);

		var list_id = data.model.list_id;
		var key="";var value=0;var table="";
		
		if(typeof data.values.wallet_name !== 'undefined'){
			key = "wallet_name";
			value = data.values.wallet_name;
		}else if(typeof data.values.status !== 'undefined'){
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
			data:{list_id:list_id,key:key,value:value,_token:token},
			url: requestUrl+"/updateWalletList",
			success: function (eventData) {
				aKendoGridWallet.dataSource.read();
			}
		});
	}

	var accountWalletListGrid = jQuery("#AccountWalletListGrid").kendoGrid({
		dataSource: {
			serverPaging: true,
			pageSize: 20,
			schema: {
				model: {
					account_id:'list_id',
					fields: {
						priority: {
							type: "number",
							validation: { required: true,min: 0,max:99 }
						},
						status: {
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
			var columnNotEditableIndex = [0,1,3,4,5,6];
			if($.inArray(parseInt(e.container.index()),columnNotEditableIndex) != -1){
				this.closeCell(); 
			}
		},
		columns: [
		{
			field: "list_id",
			title: "List Id",
			hidden:true,
		}, {
			field: "account_code_long",
			title: "Account Code",
			width: 100
		}, {
			field: "wallet_name",
			title: "Wallet Name",
			width: 100
		}, {
			field: "asset_code",
			title: "Asset Code",
			width: 100
		}, {
			field: "create_date",
			title: "Date",
			width: 100
		},{ 
			template: "<button class='address k-button'>#= wallet_address.substr(0, 10)+'..' #</button>",
			title: " ", 
			width: "100px"
		},/* {
			field: "wallet_address",
			title: "Wallet Address",
			width: 100
		},*/ {
			field: "timezone_name",
			title: "Timezone",
			width: 70
		},{
			field: "create_time",
			title: "Time",
			width: 50
		}, {
			field: "priority",
			title: "Priority",
			width: 40
		}, {
			field: "status",
			title: "Status",
			editor: statusDropDownEditor,
			template: "#=(data.status)?'Enable':'Disable'#",
			width: 50
		}
		],
	});

	var aKendoGridWallet = $("#AccountWalletListGrid").data("kendoGrid");
	var mKendoGrid = $("#merchantAccountListGrid").data("kendoGrid");
	var cKendoGrid = $("#customerAccountListGrid").data("kendoGrid");

	$('#AccountWalletListGrid').on('click', '.address', function(e){
		e.preventDefault();
		var dataItem = aKendoGridWallet.dataItem($(this).closest('tr'));

		kendo.alert(dataItem.wallet_address);
		setTimeout(function(){
			$('.k-dialog-title').text("Wallet Address");
			$('.k-alert').css('top','250px');
		},5);
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

	var validator = $("#walletForm").kendoValidator({
		/*rules: {
			hasWallet: function (input) {
				if(input.is("[id=wallet_id]")){
					var ms = input.data("kendoComboBox");       
					if(ms.value().length === 0){
						return false;
					}
				} 
				return true;
			}
		},
		messages: { 
			hasWallet: "Wallet Required"
		}*/
	}).data("kendoValidator");

	$('#walletForm').on('submit',function(){
		if (validator.validate()) {
			$.ajax({
				type: 'POST',
				dataType:'json',
				data:$('#walletForm').serialize(),
				url: requestUrl+"/createAccountWallet",
				success: function (json) {
					$('span.k-error').text(json.error);
					$('#walletForm').trigger('reset');
					mKendoGrid.dataSource.read();
					cKendoGrid.dataSource.read();
					aKendoGridWallet.dataSource.filter({});
					aKendoGridWallet.dataSource.read();
				}
			});
		}
		return false;
	});

});