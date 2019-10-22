var constant = {
	KENDO_GRID_ID   : "#transactionsCodeGrid",
	KENDO_GRID_METHOD : "/getAllTransactionCode",
	UPDATE_LIST_METHOD : "/updateTransactionCode"
}

$(document).ready(function () {
	var requestUrl = $('#requestUrl').val();
	var token = $('input[name="_token"]').val();
	function onSave(data) {
		var transactionsCodeGridObject = $(constant.KENDO_GRID_ID).data("kendoGrid");
		var selectedItem = transactionsCodeGridObject.dataItem(transactionsCodeGridObject.select());
		
		var code_id = selectedItem.code_id;	
		var key="";
		var value=0;
						
		if(data.values.code_random) {
			key = "code_random";
			value = data.values.code_random;
		}
		else {
			return false;
		}

		$.ajax({
			type: 'POST',
			data:{
				code_id:code_id,
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

	var transactionsCodeGrid = jQuery("#transactionsCodeGrid").kendoGrid({
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
					code_id:'code_id',
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
			var columnNotEditableIndex = [0];
			if($.inArray(parseInt(e.container.index()),columnNotEditableIndex) != -1){
				this.closeCell();
			}
		},
		columns: [
		{
			field: "code_id",
			title: "Code Id",
			width:100
		},{
			field: "code_random",
			title: "Random Code",
			width:100
		}
		],
	});

	$("#transactionsCodeSearchGrid").keyup(function (e) {
        var searchValue = $('#transactionsCodeSearchGrid').val();
        $("#transactionsCodeGrid").data("kendoGrid").dataSource.filter({
	        logic  : "or",
	        filters: [{
                field   : "code_random",
                operator: "contains",
                value   : searchValue
            }]
        });
	});
});