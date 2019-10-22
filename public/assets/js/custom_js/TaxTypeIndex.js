var constant = {
	KENDO_GRID_ID   : "#taxTypeGrid",
	KENDO_GRID_METHOD : "/getAllTaxType",
	KENDO_CATEGORY_GRID_METHOD : "/getAllTaxCategory",
	UPDATE_LIST_METHOD : "/updateTaxType"
}

$(document).ready(function () {
	var requestUrl = $('#requestUrl').val();
	var token = $('input[name="_token"]').val();

	$("#merchant_id").kendoComboBox({
        placeholder: "Select Person",
        dataTextField: "person_name",
        dataValueField: "person_id",
        filter: "contains",
        height: 400,
        dataSource: {
            transport : {
                read : {
                	data:{_token:token},
                    dataType : "json",
                    url : requestUrl+"/getPersons",
                    type : "POST",
                }
            }
        }
    }).data("kendoComboBox");

	function onSave(data) {
		var taxTypeGridObject = $(constant.KENDO_GRID_ID).data("kendoGrid");
		var selectedItem = taxTypeGridObject.dataItem(taxTypeGridObject.select());
		
		var type_id = selectedItem.type_id;
		var category_id = selectedItem.category_id;
		var key="";
		var value=0;
						
		if(data.values.type_name) {
			key = "type_name";
			value = data.values.type_name;
		}
		else if(data.values.category_name) {
			key = "category_name";
			value = data.values.category_name;
		}
		else if(data.values.percentage) {
			key = "percentage";
			value = data.values.percentage;
		}
		else {
			return false;
		}

		$.ajax({
			type: 'POST',
			data:{
				type_id:type_id,
				category_id: category_id,
				key:key,
				value:value,
				_token:token
			},
			url: requestUrl+constant.UPDATE_LIST_METHOD,
			success: function (eventData) {
				$(constant.KENDO_GRID_ID).data("kendoGrid").dataSource.read();
			}
		});
	}

	var taxTypeGrid = jQuery("#taxTypeGrid").kendoGrid({
		dataSource: {
			serverPaging: true,
			pageSize: 20,
			transport : {
				read : {
					data:{_token:token},
					url: requestUrl+constant.KENDO_GRID_METHOD,
					dataType: "json",
					type: "POST"
				}
			},
			schema: {
				total:'total',
				data:'taxTypes',
				model: {
					type_id:'type_id',
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
		serverFiltering: false,
		groupable: true,
		resizable: true,
		editable: true,
		selectable:'row',
		save:onSave,
		edit: function(e) {
			var columnNotEditableIndex = [4,5,6];
			if($.inArray(parseInt(e.container.index()),columnNotEditableIndex) != -1){
				this.closeCell();
			}
		},
		columns: [
			{
				field: "type_id",
				title: "Tax Type#",
				hidden:true,
			},{
				field: "type_name",
				title: "Description",
				width:100
			},{
				field: "category_name",
				title: "Category",
				width:100
			},{
				field: "percentage",
				title: "Percent",
				width:100
			},{
				field: "merchant_name",
				title: "Merchant",
				width:100
			},{
				field: "payee_name",
				title: "Payee",
				width:100
			},{
				field: "asset_code",
				title: "Asset",
				width:100
			}
		],
	});

	$("#taxTypeSearchGrid").keyup(function (e) {
        var searchValue = $('#taxTypeSearchGrid').val();
        var taxTypeGrid = new kendo.data.DataSource({
			serverPaging: true,
			pageSize: 20,
			transport : {
				read : {
					data:{_token:token,searchtext:searchValue},
					url: requestUrl+constant.KENDO_GRID_METHOD,
					dataType: "json",
					type: "POST"
				}
			},
			schema: {
				total:'total',
				data:'taxTypes',
				model: {
					type_id:'type_id',
				},
			},
			serverFiltering: true				
		});
		$("#taxTypeGrid").data("kendoGrid").setDataSource(taxTypeGrid);
	});

	$("#category_id").kendoComboBox({
		placeholder: "Select Category...",
		dataTextField: "category_name",
		dataValueField: "category_id",
		filter: "contains",
		height: 400,
		dataSource: {
			transport : {
				read : {
					data:{_token:token},
					dataType : "json",
					url : requestUrl+"/getTaxCategory",
					type : "POST"
				}
			}
		},
	}).data("kendoComboBox");

	$("#asset_id").kendoComboBox({
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

	var mKendoCategory = $("#category_id").data("kendoComboBox");
	var mKendoAsset = $("#asset_id").data("kendoComboBox");
	//var mKendoSettlement = $("#settlement_id").data("kendoComboBox");

	$('#category_id').on('change', function() {
		var categoryId = $('#category_id').val();
		$('#percentage').val('');
        $.ajax({
            type:'POST',
            data:{_token:token,category_id:categoryId},
            dataType:"json",
            url : requestUrl+"/getTaxPercent",
            success:function(percentage,status,xhr) {
                $.each(percentage, function(i,value) {
                	$('#percentage').val(value['percentage']);
                	if(value['merchant_id'] != 0) {
                		$('#merchant_id').data('kendoComboBox').value("merchant_"+value['merchant_id']);
                	}
                	if(value['payee_id'] != 0) {
                		$('#merchant_id').data('kendoComboBox').value("payee_"+value['payee_id']);
                	}
                });
            }
        });
	});

	$('#addCategory').click(function() {
		var mKendoGridCategory = $("#taxCategoryListGrid").data("kendoGrid");
		var taxCategoryListDataSource = new kendo.data.DataSource({
			serverPaging: true,
			pageSize: 20,
			transport : {
				read : {
						data:{_token:token},
						url: requestUrl+constant.KENDO_CATEGORY_GRID_METHOD,
						dataType: "json",
						type: "POST"
					}
			},
			schema: {
				total:'total',
				data:'taxCategoryTypes',
				model: {
					category_id:'category_id',
				},
			},
			serverFiltering: true,

			});
		mKendoGridCategory.setDataSource(taxCategoryListDataSource);
		$('#taxCategoryForm')[0].reset();
		mKendoCategory.value('');
		$('#top_modal').modal("show");

		$("#category_merchant_id").kendoComboBox({
	        placeholder: "Select Person",
	        dataTextField: "person_name",
	        dataValueField: "person_id",
	        filter: "contains",
	        height: 400,
	        dataSource: {
	            transport : {
	                read : {
	                	data:{_token:token},
	                    dataType : "json",
	                    url : requestUrl+"/getPersons",
	                    type : "POST",
	                }
	            }
	        }
	    }).data("kendoComboBox");
	});

	/*$('.taxAmountPercent').focusout(function() {
		var totalAmount = $('#totalamount').val();
		var taxPercentage = $('#percentage').val();
		var taxAmount = totalAmount * taxPercentage/100;
		$('#amount').val(taxAmount.toFixed(4));
	});

	$('#settlement_id').on('change', function() {
		var settlementId = $(this).val();
		var assetId = $('#asset_id').val();
		var totalAmount = $('#totalamount').val();
		if(assetId != 0 && settlementId != 0) {
			$.ajax({
	            type:'POST',
	            data:{_token:token,asset_id:assetId,settlement_id:settlementId},
	            dataType:"text",
	            url : requestUrl+"/getAssetSettlementPrice",
	            success:function(assetSettlement,status,xhr) {
	            	var settleTotalAmount = totalAmount * assetSettlement;
	                $('#totalamount').val(settleTotalAmount);
	                $('.taxAmountPercent').trigger('focusout');
	            }
	        });
		}
	});*/

	var validator = $("#taxTypeForm").kendoValidator({
		rules: {
			hasCategory: function (input) {
				if(input.is("[id=category_id]")) {
					var ms = input.data("kendoComboBox");       
					if(ms.value().length === 0){
						return false;
					}
				}
				return true;
			},
			hasAsset: function (input) {
				if(input.is("[id=asset_id]")){
					var ms = input.data("kendoComboBox");       
					if(ms.value().length === 0){
						return false;
					}
				}
				return true;
			}
		},
		messages: {
			hasCategory: "Category Required",
			hasAsset: "Asset Required"
		}
	}).data("kendoValidator");

	$('#submitBtn').click(function() {
		if (validator.validate()) {
			$.ajax({
				type: 'POST',
				data: $('#taxTypeForm').serialize(),
				url: requestUrl+"/createTaxType",
				success: function (eventData) {
					$('#taxTypeForm')[0].reset();
					$("#taxTypeGrid").data("kendoGrid").dataSource.read();
				}
			});
		}
	});

	function onSaveCategory(data) {
		var taxCategoryGridObject = $("#taxCategoryListGrid").data("kendoGrid");
		var selectedItem = taxCategoryGridObject.dataItem(taxCategoryGridObject.select());
		var category_id = selectedItem.category_id;
				
		var key="";
		var value=0;
						
		if(data.values.category_name) {
			key = "category_name";
			value = data.values.category_name;
		}
		else if(data.values.percentage) {
			key = "percentage";
			value = data.values.percentage;
		}
		else {
			return false;
		}

		$.ajax({
			type: 'POST',
			data:{
				category_id: category_id,
				key:key,
				value:value,
				_token:token
			},
			url: requestUrl+constant.UPDATE_LIST_METHOD,
			success: function (eventData) {
				$("#taxCategoryListGrid").data("kendoGrid").dataSource.read();
			}
		});
	}

	var taxCategoryListGrid = jQuery("#taxCategoryListGrid").kendoGrid({
		dataSource: {
			serverPaging: true,
			pageSize: 20,
			schema: {
				total:'total',
				data:'taxCategoryTypes',
				model: {
					category_id:'category_id',
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
		groupable: true,
		resizable: true,
		editable: true,
		selectable:'row',
		save:onSaveCategory,
		edit: function(e){
			var columnNotEditableIndex = [2,4];
			if($.inArray(parseInt(e.container.index()),columnNotEditableIndex) != -1){
				this.closeCell();
			}
		},
		columns: [
			{
				field: "category_id",
				title: "Category#",
				hidden:true,
			},{
				field: "category_name",
				title: "Category Name",
				width:100
			},{
				field: "merchant_name",
				title: "Merchant",
				width:100
			},{
				field: "percentage",
				title: "Percent",
				width:100
			},{
				field: "payee_name",
				title: "Payee",
				width:100
			}
		],
	});

	$("#taxCategorySearchGrid").keyup(function (e) {
        var searchValue = $('#taxCategorySearchGrid').val();
        var taxCategoryListGrid = new kendo.data.DataSource({
			serverPaging: true,
			pageSize: 20,
			transport : {
				read : {
					data:{_token:token,searchtext:searchValue},
					url: requestUrl+constant.KENDO_CATEGORY_GRID_METHOD,
					dataType: "json",
					type: "POST"
				}
			},
			schema: {
				total:'total',
				data:'taxCategoryTypes',
				model: {
					category_id:'category_id',
				},
			},
			serverFiltering: true				
		});
		$("#taxCategoryListGrid").data("kendoGrid").setDataSource(taxCategoryListGrid);
        
	});

	var categoryValidator = $("#taxCategoryForm").kendoValidator({
		rules: {
			hasMerchant: function (input) {
				if(input.is("[id=category_merchant_id]")) {
					var ms = input.data("kendoComboBox");       
					if(ms.value().length === 0){
						return false;
					}
				}
				return true;
			}
		},
		messages: {
			hasMerchant: "Merchant Required"
		}
	}).data("kendoValidator");

	$('#taxCategoryForm').on('submit',function() {
		if (categoryValidator.validate()) {
			$.ajax({
				type: 'POST',
				data:$('#taxCategoryForm').serialize(),
				url: requestUrl+"/createTaxTypeCategory",
				success: function (eventData) {
					$('#taxCategoryForm')[0].reset();
					$("#taxCategoryListGrid").data("kendoGrid").dataSource.read();
					var taxCategoryDataSource = new kendo.data.DataSource({
						transport : {
							read : {
								data:{_token:token},
								dataType : "json",
								url : requestUrl+"/getTaxCategory",
								type : "POST"
							}
						}
					});
					mKendoCategory.setDataSource(taxCategoryDataSource);
				}
			});
		}
		return false;
	});
});