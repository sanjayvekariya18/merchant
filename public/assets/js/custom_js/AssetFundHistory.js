$(document).ready(function() {

	var token = $('input[name="_token"]').val();
	var requestUrl = $("#requestUrl").val();

	$("#tabstrip").kendoTabStrip().data("kendoTabStrip");

	var assetFundHistoryGrid = jQuery("#assetFundHistoryGrid").kendoGrid({
		dataSource: {
			serverPaging: true,
			pageSize: 20,
			transport : {
				read : {
					data:{_token:token},
					url: requestUrl+"/getAssetFundHistoryList",
					dataType: "json",
					type: "POST"
				}
			},
			schema: {
				total:'total',
	            data:'asset_fund',
				model: {
					fund_id:'fund_id',
					fields: {
						priority: {
							type: "number",
							validation: { required: true,min: 0,max:99 }
						},
						enable: {
							defaultValue: { value: 1, text: "Accept"}
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
		editable: false,
		detailTemplate: kendo.template($("#templateDetail").html()),
		detailInit: detailInitHistory,
		dataBound : function(e) {
			var data = this.dataSource.data();
            $.each(data, function(i, row) {
                var commentDetails = row.comment;
                if (commentDetails != '') {
                	$('tr[data-uid="' + row.uid + '"] td:nth-child(1)').css("background-color", "lightblue");
                }
            });
            var dataSource = this.dataSource;
            this.element.find('tr.k-master-row').each(function() {
                var row = $(this);
                var data = dataSource.getByUid(row.data('uid'));
                if (data.comment == '') {
                    row.find('.k-hierarchy-cell a').remove();
                }
            });
            $.each(data, function(i, row) {
                var statusFontColor = row.statusFontColor;
                var colorCode = row.statusColor;
                var element = $('tr[data-uid="' + row.uid + '"] ');
                element.css("background-color", colorCode);
                element.css("color", statusFontColor);
            });
		},
		columns: [
		{
			field: "fund_id",
			title: "Fund Id",
			hidden:true,
		},{
			field: "status",
			title: "Status",
			template: "#=data.status_name#"
		},{
			field: "merchant_name",
			title: "Merchant",
		}, {
			field: "merchant_account_name",
			title: "Merchant Account",
		}, {
			field: "customer_account_name",
			title: "Customer Account",
		},{
			field: "asset_code",
			title: "Asset Code",
		}, {
			field: "asset_quantity",
			title: "Asset Quantity",
		}, {
			field: "asset_price",
			title: "Asset Price",
		},{
			field: "fund_type_name",
			title: "Fund Type",
		},{
			field: "fund_date",
			title: "Fund Date",
		},{
			field: "fund_time",
			title: "Fund Time",
		},{
			field: "image_href",
			title: "Show Image",
			width: "100px",
			template: '#if(data.image_href != "") {#<a target="_blank" imageUrl="#=data.image_href#" class="k-button k-button-icontext k-grid-ShowImage displayImage">Show Image</a>#}#',
		}
		],
	});
	function detailInitHistory(e) {
        var detailRow = e.detailRow;
        detailRow.find(".commentDetails").kendoGrid({
            dataSource: {
                transport: {
                    read: {
                        url: requestUrl+"/fundCommentList",
                        data: {
			                commentId: e.data.comment_id,
			                _token:token
			            },
                        dataType: "json",
                        type: "POST",
                    }
                },
                pageSize: 5,
                serverFiltering: true,
            },
            pageable: {
                refresh: true,
                pageSizes: true
            },
            scrollable: false,
            sortable: true,
            pageable: true,
            columns: [
                { field: "comment", title: "Comments", width: "70px" },
                { field: "commentedBy", title: "Commented By", width: "30px" },
                { field: "commentDate", title: "Commented Date", width: "30px" },
                { field: "commentTime", title: "Commented Time", width: "30px" }
            ]
        });
    }
});