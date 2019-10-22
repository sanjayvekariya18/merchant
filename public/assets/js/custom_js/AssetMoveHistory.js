$(document).ready(function() {

	var token = $('input[name="_token"]').val();
	var requestUrl = $("#requestUrl").val();

	$("#tabstrip").kendoTabStrip().data("kendoTabStrip");

	var assetMoveHistoryGrid = jQuery("#assetMoveHistoryGrid").kendoGrid({
		dataSource: {
			serverPaging: true,
			pageSize: 20,
			transport : {
				read : {
					data:{_token:token},
					url: requestUrl+"/getAssetMoveHistoryList",
					dataType: "json",
					type: "POST"
				}
			},
			schema: {
				total:'total',
	            data:'history_list',
				model: {
					move_id:'move_id',
					fields: {
						priority: {
							type: "number",
							validation: { required: true,min: 0,max:99 }
						},
						enable: {
							defaultValue: { value: 11, text: "Accept"}
						}
					}
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
		serverFiltering: true,
		groupable: true,
		resizable: true,
		editable: false,
		selectable:'row',
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
			field: "move_id",
			title: "Move Id",
			hidden:true,
		},{
			field: "status",
			title: "Status",
			//template: "#=(data.status == 1)?'Accept':'Reject'#"
			template: "#=data.status_name#"
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
			field: "asset_quantity",
			title: "Asset From Quantity",
		}, {
			field: "asset_price",
			title: "Asset From Price",
		},{
			field: "asset_into_quantity",
			title: "Asset Into Quantity",
		},{
			field: "asset_into_price",
			title: "Asset Into Price",
		}
		],
	});
	function detailInitHistory(e) {
        var detailRow = e.detailRow;
        detailRow.find(".commentDetails").kendoGrid({
            dataSource: {
                transport: {
                    read: {
                        url: requestUrl+"/moveCommentList",
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