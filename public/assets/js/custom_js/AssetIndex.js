var constant = {
	KENDO_GRID_ID   : "#assetGrid",
	KENDO_GRID_METHOD : "/getAllAssets",
	UPDATE_LIST_METHOD : "/updateAsset"
}

$(document).ready(function () {
	var requestUrl = $('#requestUrl').val();
	var token = $('input[name="_token"]').val();
	function onSave(data) {
		var assetGridObject = $(constant.KENDO_GRID_ID).data("kendoGrid");
		var selectedItem = assetGridObject.dataItem(assetGridObject.select());
		
		var asset_id = selectedItem.asset_id;
		var identityId = selectedItem.identity_id;
		
		var key="";
		var value=0;
						
		if(data.values.asset_code) {
			key = "identity_code";
			value = data.values.asset_code;
		}
		else if(data.values.asset_name) {
			key = "identity_name";
			value = data.values.asset_name;
		}
		else if(data.values.asset_type_id) {
			key = "asset_type_id";
			value = data.values.asset_type_id;
		}
		else if(data.values.precision_enter) {
			key = "precision_enter";
			value = data.values.precision_enter;
		}
		else if(data.values.precision_display) {
			key = "precision_display";
			value = data.values.precision_display;
		}
		else {
			return false;
		}

		$.ajax({
			type: 'POST',
			data:{
				asset_id:asset_id,
				identity_id:identityId,
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

	var assetGrid = jQuery("#assetGrid").kendoGrid({
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
				data:'assets',
				model: {
					asset_id:'asset_id',
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
			var columnNotEditableIndex = [];
			if($.inArray(parseInt(e.container.index()),columnNotEditableIndex) != -1){
				this.closeCell(); 
			}
		},
		columns: [
		{
			field: "asset_id",
			title: "Asset#",
			hidden:true,
		},{
			field: "asset_code",
			title: "Asset Code",
			width:100
		},{
			field: "asset_name",
			title: "Asset Name",
			width:100
		},{
			field: "asset_type_id",
			title: "Asset Type",
			editor: assetTypeDropDownEditor,
			template: "#=data.asset_type_name#",
			width:100
		},{
			field: "precision_enter",
			title: "Precision Enter",
			width:100
		},{
			field: "precision_display",
			title: "Precision Display",
			width:100
		}
		],
	});

	function assetTypeDropDownEditor(container, options) {
		var data = [
			{ Description: "Fiat Currency", ID: "1" },
			{ Description: "Cryptography Currency", ID: "2" },
			{ Description: "Initial Coin Offering", ID: "3" }
		];
		$('<input data-text-field="Description" data-value-field="ID" data-bind="value:' + options.field + '"/>')
			.appendTo(container)
			.kendoDropDownList ({
				dataSource: data,
				dataTextField: "Description",
				dataValueField:"ID"
		});
	};

	$("#assetSearchGrid").keyup(function (e) {
        var searchValue = $('#assetSearchGrid').val();
        var assetGrid = new kendo.data.DataSource({
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
				data:'assets',
				model: {
					asset_id:'asset_id',
				},
			},
			serverFiltering: true				
		});
		$("#assetGrid").data("kendoGrid").setDataSource(assetGrid);
	});
});