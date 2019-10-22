$(document).ready(function() {

	var token = $('input[name="_token"]').val();
	var requestUrl = $("#requestUrl").val();

	$("#tabstrip").kendoTabStrip().data("kendoTabStrip");

	/*var merchantDataSource = new kendo.data.DataSource({
       transport : {   
           read : {
               dataType : "json",
               url : requestUrl+"/getMerchants",
               type : "GET",
           }
       }
   	});*/

   	$("#merchant_type_id").kendoComboBox({
		placeholder: "Merchant Type ...",
		dataTextField: "merchant_type_name",
		dataValueField: "merchant_type_id",
		filter: "contains",
		height: 400,
		value: 0,
		dataSource: {
			transport : {   
				read : {
					dataType : "json",
					url : requestUrl+"/getMerchantTypes",
					type : "GET"
				}
			}
		},
		change: function (e) {
			console.log(this.value());
			if(!this.value()){
				mKendoMerchant.value("");	mKendoMerchant.enable(false);
				return false;
			}

			var merchant_type_id = this.value();

			var merchantDataSource = new kendo.data.DataSource({
				transport : {   
					read : {
						type 		: "POST",
						dataType 	: "json",
						data 		: {_token:token,merchant_type_id:merchant_type_id},	
						url 		: requestUrl+"/getMerchants",
					}
				}
			});
			mKendoMerchant.setDataSource(merchantDataSource);
			mKendoMerchant.enable(true);
		}
	}).data("kendoComboBox");

   	$("#merchant_id").kendoComboBox({
		placeholder: "Select Merchant...",
		dataTextField: "merchant_name",
		dataValueField: "merchant_id",
		filter: "contains",
		height: 400,
		change: function (e) {
			if(!this.value()){
				mKendoAsset.enable(true);
				return false;
			}
			var merchant_id = this.value();

			var merchantAccountListDataSource = new kendo.data.DataSource({
					serverPaging: true,
					pageSize: 20,
					transport : {   
						read : {
								data:{_token:token,merchant_id:merchant_id},
								url: requestUrl+"/merchant_account_list",
								dataType: "json",
								type: "POST"
							}
					},
					schema: {
						total:'total',
	                	data:'merchant_account_list',
						model: {
							account_id:'account_id',
							fields: {
								priority: {
									type: "number",
									validation: { required: true,min: 0,max:99 }
								},
								enable: {
									defaultValue: { value: 1, text: "Enable"}
								},
								credit: {
									defaultValue: { value: 0, text: "FALSE"}
								}
							}
						},
					},
					});
			mKendoGrid.setDataSource(merchantAccountListDataSource);
			mKendoAsset.enable(true);
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
		change: function(e) {
			var assetFilter = { logic: "or", filters: [] };

			$.each(this.value(), function(i, v) {
				assetFilter.filters.push({field: "asset_id", operator: "eq", value: v });
			});
			
			mKendoGrid.dataSource.filter(assetFilter);
			mKendoGrid.dataSource.fetch(function() {
		        console.log(mKendoGrid.dataSource.total());
		    });
		}
	}).data("kendoMultiSelect");

	$("#settlement_id").kendoComboBox({
		placeholder: "Select Asset...",
		dataTextField: "asset_code",
		dataValueField: "asset_id",
		filter: "contains",
		height: 400,
		value: 0,
		dataSource: {
			transport : {   
				read : {
					dataType : "json",
					url : requestUrl+"/getAssets/0",
					type : "GET"
				}
			}
		},
	}).data("kendoComboBox");
	
	$("#credit").kendoDropDownList({
		dataTextField: "text",
		dataValueField: "value",
		value: 0,
		dataSource:[
			{ text: "True", value: "1" },
			{ text: "False", value: "0" }
		]
	});

	var mKendoMerchant = $("#merchant_id").data("kendoComboBox");
	// mKendoMerchant.setDataSource(merchantDataSource);

	var mKendoAsset 	= $("#asset_id").data("kendoMultiSelect");
	var mKendoSettlement= $("#settlement_id").data("kendoComboBox");
	var mKendoCredit 	= $("#credit").data("kendoDropDownList");

	function onSave(data) {

		var account_id = data.model.account_id;
		var key="";var value=0;var table="";
		
		if(typeof data.values.enable !== 'undefined'){
			key = "enable";
			value = data.values.enable;
		}else if(typeof data.values.priority !== 'undefined'){
			key = "priority";
			value = data.values.priority;
		}else if(typeof data.values.account_code_long !== 'undefined'){
			key = "account_code_long";
			value = data.values.account_code_long;

			var data = mKendoGrid.dataSource._data;
			for(i=0; i < data.length; i++){
				if(data[i].account_code_long == value){
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
					var $toast = toastr["error"]("", "Duplicate Account Code Found");
					mKendoGrid.dataSource.read();
					return false;
				}
			}
		}else if(typeof data.values.account_description !== 'undefined'){
			key = "identity_description";
			value = data.values.account_description;
		}else if(typeof data.values.account_name !== 'undefined'){
			key = "identity_name";
			value = data.values.account_name;
		}else if(typeof data.values.account_settlement !== 'undefined'){
			key = "account_settlement";
			value = data.values.account_settlement;
		}else if(typeof data.values.asset_id !== 'undefined'){
			key = "asset_id";
			value = data.values.asset_id;
		}else if(typeof data.values.credit !== 'undefined'){
			key = "credit";
			value = data.values.credit;
		}else{
			return false;
		}

		$.ajax({
			type: 'POST',
			data:{account_id:account_id,key:key,value:value,_token:token},
			url: requestUrl+"/updateList",
			success: function (eventData) {
				mKendoGrid.dataSource.read();
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
						},
						credit: {
							defaultValue: { value: 0, text: "FALSE"}
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
			var columnNotEditableIndex = [0,1,2,8,9];
			var selectedRows = this.select();
			var wallet = this.dataItem(selectedRows[0]).totalWallet;
			
			if($.inArray(parseInt(e.container.index()),columnNotEditableIndex) != -1){
				this.closeCell(); 
			}else if (parseInt(e.container.index()) == 6 && wallet != 0){
				console.log(wallet);
				console.log("close");
				this.closeCell();
			}
		},
		columns: [
		{ 
			template: "<button class='wallet k-button'>#= 'Wallet ('+data.totalWallet+')' #</button>",
			title: " ", 
			width: "100px"
		}, {
			field: "account_id",
			title: "Account Id",
			hidden:true,
		}, {
			field: "merchant_code",
			title: "Merchant Code",
			width: 100
		}, {
			field: "account_name",
			title: "Account Name",
			width: 100
		}, {
			field: "account_description",
			title: "Account Description",
			width: 130
		}, {
			field: "account_code_long",
			title: "Account Code",
			width: 100
		}, {
			field: "asset_id",
			title: "Asset Code",
			editor: assetDropDownEditor,
			template: "#=data.asset_code#",
			width: 100
		}, {
			field: "account_settlement",
			title: "Settlement",
			editor: settlementDropDownEditor,
			template: "#=data.account_settlement_code#",
			width: 100
		}, {
			field: "asset_quantity",
			title: "Asset Quantity",
			width: 100
		}, {
			field: "asset_price",
			title: "Asset Price",
			width: 100
		},{
			field: "credit",
			title: "Credit",
			editor: creditDropDownEditor,
			template: "#=(data.credit)?'TRUE':'FALSE'#",
			width: 100
		}, {
			field: "priority",
			title: "Priority",
			width: 100
		}, {
			field: "enable",
			title: "Status",
			editor: statusDropDownEditor,
			template: "#=(data.enable)?'Enable':'Disable'#",
			width: 100
		}
		],
	});

	var mKendoGrid = $("#merchantAccountListGrid").data("kendoGrid");

	$('#merchantAccountListGrid').on('click', '.wallet', function(e){
		e.preventDefault();
		
		$('.content .preloader').show();
		$('.content .preloader img').show();

		var dataItem = mKendoGrid.dataItem($(this).closest('tr'));
		console.log(dataItem);
		var mKendoGridWallet = $("#AccountWalletListGrid").data("kendoGrid");
		var accountWalletListDataSource = new kendo.data.DataSource({
				serverPaging: true,
				pageSize: 20,
				transport : {   
					read : {
							data:{_token:token,account_id:dataItem.account_id},
							url: requestUrl+"/getAccountWallets",
							dataType: "json",
							type: "POST"
						}
				},
				schema: {
						total:'total',
	                	data:'account_wallet',
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
				});

		mKendoGridWallet.setDataSource(accountWalletListDataSource);

		$('#walletForm').trigger('reset');
		$('#account_id').val(dataItem.account_id);
		$('#wallet_asset_id').val(dataItem.asset_id);
		$('.modal-title').text("Account : "+dataItem.account_code_long);
		$('.content .preloader').hide();
		$('.content .preloader img').hide();
		$('#top_modal').modal("show");
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

	function creditDropDownEditor(container, options) {
		var data = [
					{ Description: "TRUE", ID: "1" },
					{ Description: "FALSE", ID: "0" }
				];
			$('<input data-text-field="Description" data-value-field="ID" data-bind="value:' + options.field + '"/>')
				.appendTo(container)
				.kendoDropDownList ({
					dataSource: data,
					dataTextField: "Description",
					dataValueField:"ID"
			 });
	};

	function settlementDropDownEditor(container, options) {
		$('<input data-text-field="asset_code" data-value-field="asset_id" data-bind="value:' + options.field + '"/>')
			.appendTo(container)
			.kendoDropDownList({
				dataSource: {
					transport : {   
						read : {
							dataType : "json",
							url : requestUrl+"/getAssets/0",
							type : "GET"
						}
					}
				},
				dataTextField: "asset_code",
				dataValueField: "asset_id"
			});
	}

	function assetDropDownEditor(container, options) {
		$('<input data-text-field="asset_code" data-value-field="asset_id" data-bind="value:' + options.field + '"/>')
			.appendTo(container)
			.kendoDropDownList({
				dataSource: {
					transport : {   
						read : {
							dataType : "json",
							url : requestUrl+"/getAssets/0",
							type : "GET"
						}
					}
				},
				dataTextField: "asset_code",
				dataValueField: "asset_id"
			});
	}

	var validator = $("#merchantAccountListForm").kendoValidator({
		validateOnBlur:false,
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
			hasAsset: function (input) {
				if(input.is("[id=asset_id]")){
					var ms = input.data("kendoMultiSelect");       
					if(ms.value().length === 0){
						return false;
					}
				} 
				return true;
			}
		},
		messages: { 
			hasMerchant: "Merchant Required",
			hasAsset: "Asset Required"
		}
	}).data("kendoValidator");

	$('#submitBtn').click(function(){
		if (validator.validate()) {
			$.ajax({
				type: 'POST',
				data:$('#merchantAccountListForm').serialize(),
				url: requestUrl+"/createMerchantAccount",
				success: function (eventData) {
					mKendoAsset.value([]);
					mKendoSettlement.value(0);
					mKendoCredit.value(0);
					mKendoMerchant.trigger('change');
				}
			});
		}
	});
});