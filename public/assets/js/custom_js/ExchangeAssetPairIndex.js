$(document).ready(function () {
	var exchangeAssetPair = jQuery("#exchangeAssetPairGrid").kendoGrid({
		dataSource: {
			serverPaging: true,
			pageSize: 20,
			transport: {
				read: {
					url: webUrl+"/order_type_list_view",
					dataType: "json",
					type: "GET",
				},
			},
			schema: {
				model: {
					fields: {
						asset_from_id: {
                            editable: false
                        },
                        asset_into_id: {
                            editable: false
                        },
                        asset_bid_price: {
                            editable: false
                        },
                        asset_ask_price: {
                            editable: false
                        },
                        asset_last_price: {
                            editable: false
                        },
                        priority: {
                            editable: false
                        },
					}
				},
			},
			serverFiltering: true,
		},
		pageable: {
            refresh: true,
            pageSizes: true
        },
        autoSync: true,
        sortable: true,
		reorderable: true,
		serverFiltering: true,
		groupable: true,
		resizable: true,
		editable: true,
		scrollable: {
            horizontal: true
        },
        columns: [
        {
            field: "asset_from_id",
            title: "Asset From",
            width: 80
        }, {
            field: "asset_into_id",
            title: "Asset Into",
            width: 50
        }, {
            field: "asset_bid_price",
            title: "Bid Price",
            width: 100
        }, {
            field: "asset_ask_price",
            title: "Ask Price",
            width: 80
        }, {
            field: "asset_last_price",
            title: "Last Price",
            width: 70
        }, {
            field: "priority",
            title: "Priority",
            width: 70
        }]
	});
});