var constant = {
	KENDO_GRID_ID   : "#paymentLedgerGrid",
	KENDO_GRID_METHOD : "/getAllPaymentLedger",
	UPDATE_LIST_METHOD : "/updatePaymentLedger"
}

$(document).ready(function () {
	var requestUrl = $('#requestUrl').val();
	var token = $('input[name="_token"]').val();
	function onSave(data) {
		var paymentLedgerGridObject = $(constant.KENDO_GRID_ID).data("kendoGrid");
		var selectedItem = paymentLedgerGridObject.dataItem(paymentLedgerGridObject.select());
		
		var ledger_id = selectedItem.ledger_id;
		var key="";
		var value=0;
						
		if(data.values.vendor_name) {
			key = "vendor_name";
			value = data.values.vendor_name;
		}
		else if(data.values.payment_description) {
			key = "payment_description";
			value = data.values.payment_description;
		}
		else if(data.values.payment_name) {
			key = "payment_name";
			value = data.values.payment_name;
		}
		else if(data.values.payment_notes) {
			key = "payment_notes";
			value = data.values.payment_notes;
		}
		else if(data.values.transaction_address) {
			key = "transaction_address";
			value = data.values.transaction_address;
		}
		else if(data.values.transaction_address_url) {
			key = "transaction_address_url";
			value = data.values.transaction_address_url;
		}
		else if(data.values.transaction_internal_ref) {
			key = "transaction_internal_ref";
			value = data.values.transaction_internal_ref;
		}
		else if(data.values.transaction_root) {
			key = "transaction_root";
			value = data.values.transaction_root;
		}
		else if(data.values.ledger_hash) {
			key = "ledger_hash";
			value = data.values.ledger_hash;
		}
		else {
			return false;
		}

		$.ajax({
			type: 'POST',
			data:{
				ledger_id:ledger_id,
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

	var paymentLedgerGrid = jQuery("#paymentLedgerGrid").kendoGrid({
		dataSource: {
			serverPaging: false,
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
				model: {
					ledger_id:'ledger_id',
				},
			},
			serverFiltering: false,
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
		edit: function(e){
			var columnNotEditableIndex = [1,2,3,4,5,6,8,12,13,14,15,16,17];
			if($.inArray(parseInt(e.container.index()),columnNotEditableIndex) != -1){
				this.closeCell();
			}
		},
		columns: [
		{
			field: "ledger_id",
			title: "Ledger#",
			hidden:true,
		},{
			field: "summary_id",
			title: "Summary Id",
			width:100
		},{
			field: "city_name",
			title: "Location Name",
			width:100
		},{
			field: "group_name",
			title: "Group Name",
			width:100
		},{
			field: "staff_name",
			title: "Staff Name",
			width:100
		},{
			field: "merchant_account_name",
			title: "Merchant Account Name",
			width:100
		},{
			field: "customer_account_name",
			title: "Customer Account Name",
			width:100
		},{
			field: "vendor_name",
			title: "Vendor Name",
			width:100
		},{
			field: "payment_type_name",
			title: "Payment Type",
			width:100
		},{
			field: "payment_description",
			title: "Payment Description",
			width:100
		},{
			field: "payment_name",
			title: "Payment Name",
			width:100
		},{
			field: "payment_notes",
			title: "Payment Notes",
			width:100
		},{
			field: "payment_quantity",
			title: "Quantity",
			width:100
		},{
			field: "payment_unit_price",
			title: "Unit Price",
			width:100
		},{
			field: "payment_amount",
			title: "Amount",
			width:100
		},{
			field: "payment_asset_name",
			title: "Asset Name",
			width:100
		},{
			field: "payment_fee_id",
			title: "Payment Fee Id",
			width:100
		},{
			field: "transaction_type_name",
			title: "Transaction Type",
			width:100
		},{
			field: "transaction_address",
            title: "Transaction Address",
            width: 100
        }, {
            field: "transaction_address_url",
            title: "Transaction Address Url",
            width: 100
        }, {
			field: "transaction_internal_ref",
			title: "Transaction Internal",
			width: 100
		}, {
            field: "transaction_root",
            title: "Transaction Root",
            width: 100
        }, {
            field: "ledger_hash",
            title: "Ledger Hash",
            width: 100
		}
		],
	});

	$("#paymentLedgerSearchGrid").keyup(function (e) {
        var searchValue = $('#paymentLedgerSearchGrid').val();
        $("#paymentLedgerGrid").data("kendoGrid").dataSource.filter({
	        logic  : "or",
	        filters: [{
                field   : "merchant_account_name",
                operator: "contains",
                value   : searchValue
            },
            {
                field   : "customer_account_name",
                operator: "contains",
                value   : searchValue
            },
            {
                field   : "vendor_name",
                operator: "contains",
                value   : searchValue
            },
            {
                field   : "payment_type_name",
                operator: "contains",
                value   : searchValue
            },
            {
                field   : "payment_description",
                operator: "contains",
                value   : searchValue
            },
            {
                field   : "payment_name",
                operator: "contains",
                value   : searchValue
            },
            {
                field   : "payment_notes",
                operator: "contains",
                value   : searchValue
            },
            {
                field   : "payment_quantity",
                operator: "contains",
                value   : searchValue
            },
            {
                field   : "payment_unit_price",
                operator: "contains",
                value   : searchValue
            },
            {
                field   : "payment_amount",
                operator: "contains",
                value   : searchValue
            },
            {
                field   : "payment_asset_name",
                operator: "contains",
                value   : searchValue
            },
            {
                field   : "transaction_type_name",
                operator: "contains",
                value   : searchValue
            },
            {
                field   : "transaction_address",
                operator: "contains",
                value   : searchValue
            },
            {
                field   : "transaction_address_url",
                operator: "contains",
                value   : searchValue
            },
            {
                field   : "ledger_hash",
                operator: "contains",
                value   : searchValue
            }]
        });
	});
});