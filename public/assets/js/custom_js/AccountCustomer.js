$(document).ready(function() {

	var token = $('input[name="_token"]').val();
	var requestUrl = $("#requestUrl").val();

	var selectedRowCustId = 0;
	var selectedPullCustId = 0;

	$("#c_merchant_type_id").kendoComboBox({
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
				cKendoMerchant.value("");	cKendoMerchant.enable(false);
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
			cKendoMerchant.setDataSource(merchantDataSource);
			cKendoMerchant.enable(true);
		}
	}).data("kendoComboBox");

	$("#c_merchant_id").kendoComboBox({
		placeholder: "Select Merchant...",
		dataTextField: "merchant_name",
		dataValueField: "merchant_id",
		filter: "contains",
		height: 400,
		change: function (e) {
			if(!this.value()){

				selectedPullCustId=0;
				cKendoReferrer.value("");	cKendoReferrer.enable(false);
				cKendoCustomer.value("");	cKendoCustomer.enable(false);
				cKendoAsset.value([]);		cKendoAsset.enable(false);
				cKendoFilter.value(0);		cKendoFilter.enable(false);
				return false;
			}

			var merchant_id = this.value();

			var customerAccountListDataSource = new kendo.data.DataSource({
				serverPaging: true,
				pageSize: 20,
				transport : {   
					read : {
						data:{_token:token,merchant_id:merchant_id},
							url: requestUrl+"/customer_account_list",
							dataType: "json",
							type: "POST"
						}
				},
				schema: {
					total:'total',
                	data:'customer_account_list',
					model: {
						account_id:'account_id',
						fields: {
							priority: {
								type: "number",
								validation: { required: true,min: 0,max:99 }
							},
							fee_percentage: {
								type: "number",
								format: "{0:#.##}"
							},
							status: {
								defaultValue: { value: 1, text: "Enable"}
							},
							credit: {
								defaultValue: { value: 0, text: "FALSE"}
							}
						}
					},
				},

				});
			cKendoGrid.setDataSource(customerAccountListDataSource);

			var customerDataSource = new kendo.data.DataSource({
				transport : {   
					read : {
							url: requestUrl+"/getCustomers/"+merchant_id,
							dataType: "json",
							type: "GET"
						}
					}
				});
			cKendoCustomer.setDataSource(customerDataSource);

			var merchantAssetDataSource = new kendo.data.DataSource({
				transport : {   
					read : {
							url: requestUrl+"/getMerchantAssets/"+merchant_id,
							dataType: "json",
							type: "GET"
						}
					}
				});
			cKendoAsset.setDataSource(merchantAssetDataSource);

			if(selectedPullCustId){
				cKendoCustomer.value(selectedPullCustId);	
			}else{
				cKendoCustomer.value("");
			}
			cKendoReferrer.value("");
			cKendoCustomer.enable(true);
			cKendoAsset.enable(true);
			cKendoFilter.enable(true);
		}
	}).data("kendoComboBox");

	$("#c_customer_id").kendoComboBox({
		placeholder: "Select Customer...",
		dataTextField: "customer_code",
		dataValueField: "customer_id",
		filter: "contains",
		height: 400,
		change: function (e) {

			if(!this.value()){

				cKendoReferrer.value("");	cKendoReferrer.enable(false);
				cKendoAsset.value([]);		cKendoAsset.enable(false);
				cKendoFilter.value(0);		cKendoFilter.enable(false);
				return false;
			}

			var customer_id = this.value();
			var merchant_id = cKendoMerchant.value();
			var asset_id 	= cKendoAsset.value();
			
			var globalFilter 	= { logic: "and", filters: []};
			var customerFilter 	= { filters: [] };
			var assetFilter 	= { logic: "or",  filters: [] };

			var assetDataSource = new kendo.data.DataSource({
			transport : {   
				read : {
						data:{
							merchant_id:merchant_id,
							customer_id:customer_id,
							filterType:1,
							_token:token
						},
						url: requestUrl+"/filterAssets",
						dataType: "json",
						type: "POST"
					}
				}
			});
			cKendoAsset.setDataSource(assetDataSource);
				
			var referrerDataSource = new kendo.data.DataSource({
			transport : {   
				read : {
						url: requestUrl+"/getReferrerAccounts/"+merchant_id+"/"+customer_id,
						dataType: "json",
						type: "GET"
					}
				}
			});
			cKendoReferrer.setDataSource(referrerDataSource);
			cKendoReferrer.enable(true);
			setTimeout(function(){
				cKendoReferrer.select(0);	
			},1000);

			customerFilter.filters.push(
				{field: "customer_id", operator: "eq", value: parseInt(customer_id) }
			);

			globalFilter.filters.push(customerFilter);

			if (asset_id != null && asset_id.length != 0) {
				$.each(asset_id, function(i, v) {
					assetFilter.filters.push(
						{field: "asset_id", operator: "eq", value: v }
					);
				});
				globalFilter.filters.push(assetFilter);
			}
			cKendoGrid.dataSource.filter(globalFilter);

			cKendoReferrer.enable(true);
			cKendoAsset.enable(true);
			cKendoFilter.enable(true);
		}
	}).data("kendoComboBox");

	$("#c_filter").kendoDropDownList({
		dataTextField: "text",
		dataValueField: "value",
		value: 0,
		dataSource:[
			{ text: "Custom", value: "0" },
			{ text: "Remain", value: "1" },
			{ text: "Clear", value: "2" }
		],
		change: function(e){
			var filterType = parseInt(this.value());
			var merchant_id = cKendoMerchant.value();
			var customer_id = cKendoCustomer.value();

			var assetDataSource=
						$.ajax({
							data:{
								merchant_id:merchant_id,
								customer_id:customer_id,
								filterType:filterType,
								_token:token
							},
							url: requestUrl+"/filterAssets",
							async: false,
							dataType: "json",
							type: "POST"
						}).responseJSON;

			cKendoAsset.setDataSource(assetDataSource);

			if(filterType == 1 && assetDataSource.length){
				var values = [];
				$.each(assetDataSource,function(key,val){
					values.push(val.asset_id);
				});
				cKendoAsset.value(values);

			}else if(filterType == 2){
				cKendoAsset.value([]);
			}
		}
	});

	$("#c_asset_id").kendoMultiSelect({
		placeholder: "Enter Assets...",
		dataTextField: "asset_code",
		dataValueField: "asset_id",
		filter: "contains",
		height: 400,
		change: function(e){
			var customerId = cKendoCustomer.value();
			var values = this.value();

			var globalFilter 	= { logic: "and", filters: []};
			var customerFilter 	= { filters: [] };
			var assetFilter 	= { logic: "or",  filters: [] };

			if(customerId != ""){
				customerFilter.filters.push(
					{field: "customer_id", operator: "eq", value: parseInt(customerId) }
				);
				globalFilter.filters.push(customerFilter);
			}

			if (values.length != 0) {
				$.each(values, function(i, v) {
					assetFilter.filters.push(
						{field: "asset_id", operator: "eq", value: v }
					);
				});
				globalFilter.filters.push(assetFilter);
			}
			cKendoGrid.dataSource.filter(globalFilter);
		}
	}).data("kendoMultiSelect");

	$("#c_settlement_id").kendoComboBox({
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

	$("#c_referrer_id").kendoComboBox({
		placeholder: "Select Account...",
		dataTextField: "account_code",
		dataValueField: "account_id",
		filter: "contains",
		height: 400,
		change: function(e){
			var referrer_id = parseInt(this.value());
			if(referrer_id != 0){
				cKendoReferrerFee.enable(true);
			}else{
				cKendoReferrerFee.enable(false);
			}
		}
	}).data("kendoComboBox");

	$("#c_referrer_fee").kendoNumericTextBox({min: 0});
	$("#c_fee_percentage").kendoNumericTextBox({decimals: 2,min: 0});

	$("#c_credit").kendoDropDownList({
		dataTextField: "text",
		dataValueField: "value",
		value: 0,
		dataSource:[
			{ text: "True", value: "1" },
			{ text: "False", value: "0" }
		]	
	});


	var cKendoMerchant = $("#c_merchant_id").data("kendoComboBox");
	// cKendoMerchant.setDataSource(merchantDataSource);

	var cKendoCustomer = $("#c_customer_id").data("kendoComboBox");
	var cKendoFilter = $("#c_filter").data("kendoDropDownList");
	var cKendoAsset = $("#c_asset_id").data("kendoMultiSelect");
	var cKendoSettlement = $("#c_settlement_id").data("kendoComboBox");
	var cKendoReferrer = $("#c_referrer_id").data("kendoComboBox");
	var cKendoReferrerFee = $("#c_referrer_fee").data("kendoNumericTextBox");
	var cKendoFeePercentage = $("#c_fee_percentage").data("kendoNumericTextBox");
	var cKendoCredit = $("#c_credit").data("kendoDropDownList");

	function onSave(data) {

		var account_id = data.model.account_id;
		var key="";var value=0;var table="";

		if(typeof data.values.status !== 'undefined'){
			key = "status";
			value = data.values.status;
		}else if(typeof data.values.priority !== 'undefined'){
			key = "priority";
			value = data.values.priority;
		}else if(typeof data.values.referrer_id !== 'undefined'){
			key = "referrer_id";
			value = data.values.referrer_id;
		}else if(typeof data.values.referrer_fee !== 'undefined'){
			key = "referrer_fee";
			value = data.values.referrer_fee;
		}else if(typeof data.values.account_code_long !== 'undefined'){
			key = "account_code_long";
			value = data.values.account_code_long;

			var data = cKendoGrid.dataSource._data;
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
					cKendoGrid.dataSource.read();
					return false;
				}
			}
		}else if(typeof data.values.account_description !== 'undefined'){
			key = "identity_description";
			value = data.values.account_description;
		}else if(typeof data.values.account_name !== 'undefined'){
			key = "identity_name";
			value = data.values.account_name;
		}else if(typeof data.values.fee_percentage !== 'undefined'){
			key = "fee_percentage";
			value = data.values.fee_percentage;
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
			url: requestUrl+"/updateCustomerList",
			success: function (eventData) {
				cKendoGrid.dataSource.read();
			}
		});
	}

	var customerAccountListGrid = jQuery("#customerAccountListGrid").kendoGrid({
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
						fee_percentage: {
							type: "number",
							format: "{0:#.##}"
						},
						status: {
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
			var columnNotEditableIndex = [0,1,5,9,10];
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
		change: function(e) {
			var selectedRows = this.select();
			var dataItem = this.dataItem(selectedRows[0]);
			selectedRowCustId = dataItem.customer_id;
		},
		columns: [
		{ 
			template: "<button class='wallet k-button'>#= 'Wallet ('+data.totalWallet+')' #</button>",
			title: " ", 
			width: "100px"
		},{
			field: "account_id",
			title: "Account Id",
			hidden:true,
		}, {
			field: "customer_code",
			title: "Customer Code",
			width: 100
		}, {
			field: "account_name",
			title: "Account Name",
			width: 100
		}, {
			field: "account_description",
			title: "Account Description",
			width: 100
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
			field: "referrer_id",
			title: "Referrer ID",
			editor: referrerDropDownEditor,
			template: "#=data.referrer_account_code_long#",
			width: 100
		}, {
			field: "referrer_fee",
			title: "Referrer Rate",
			width: 100
		}, {
			field: "asset_quantity",
			title: "Asset Quantity",
			width: 100
		}, {
			field: "asset_price",
			title: "Asset Price",
			width: 100
		}, {
			field: "fee_percentage",
			title: "Trading Fee(%)",
			width: 100
		}, {
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
			field: "status",
			title: "Status",
			editor: statusDropDownEditor,
			template: "#=(data.status)?'Enable':'Disable'#",
			width: 100
		}
		],
	});

	var cKendoGrid = $("#customerAccountListGrid").data("kendoGrid");

	$('#customerAccountListGrid').on('click', '.wallet', function(e){
		e.preventDefault();
		
		$('.content .preloader').show();
		$('.content .preloader img').show();

		var dataItem = cKendoGrid.dataItem($(this).closest('tr'));

		var cKendoGridWallet = $("#AccountWalletListGrid").data("kendoGrid");
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

		cKendoGridWallet.setDataSource(accountWalletListDataSource);

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

	function referrerDropDownEditor(container, options) {
		var merchant_id = cKendoMerchant.value();	
		var customer_id = selectedRowCustId;

		$('<input data-text-field="account_code_long" data-value-field="account_id" data-bind="value:' + options.field + '"/>')
			.appendTo(container)
			.kendoDropDownList({
				dataSource: {
					transport : {   
						read : {
							dataType : "json",
							url : requestUrl+"/getReferrerAccounts/"+merchant_id+"/"+customer_id,
							type : "GET"
						}
					}
				},
				dataTextField: "account_code_long",
				dataValueField: "account_id"
			});
	}

	var validator = $("#customerAccountListForm").kendoValidator({
		validateOnBlur:false,
		rules: {
			hasMerchant: function (input) {
				if(input.is("[id=c_merchant_id]")){
					var ms = input.data("kendoComboBox");       
					if(ms.value().length === 0){
						return false;
					}
				} 
				return true;
			},
			hasCustomer: function (input) {
				if(input.is("[id=c_customer_id]")){
					var ms = input.data("kendoComboBox");       
					if(ms.value().length === 0){
						return false;
					}
				} 
				return true;
			},
			hasAsset: function (input) {
				if(input.is("[id=c_asset_id]")){
					var ms = input.data("kendoMultiSelect");       
					if(ms.value().length === 0){
						return false;
					}
				} 
				return true;
			},
			hasReferrer: function (input) {
				if(input.is("[id=c_referrer_id]")){
					var ms = input.data("kendoComboBox");       
					if(ms.value().length === 0){
						return false;
					}
				} 
				return true;
			},
		},
		messages: { 
			hasMerchant: "Merchant Required",
			hasCustomer: "Customer Required",
			hasAsset: "Asset Required",
			hasReferrer: "Referrer Account Required"
		}
	}).data("kendoValidator");

	$('#c_submitBtn').click(function(){
		if (validator.validate()) {
			$.ajax({
				type: 'POST',
				data:$('#customerAccountListForm').serialize(),
				url: requestUrl+"/createCustomerAccount",
				success: function (eventData) {
					selectedPullCustId = $("#c_customer_id").val();
					cKendoAsset.value([]);
					cKendoReferrer.value(0);
					cKendoReferrerFee.value("");
					cKendoSettlement.value(0);
					cKendoFeePercentage.value("");
					cKendoCredit.value(0);
					cKendoMerchant.trigger('change');
				}
			});
		}
	});
});