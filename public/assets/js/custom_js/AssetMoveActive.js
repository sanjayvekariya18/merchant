var token = $('input[name="_token"]').val();
var requestUrl = $("#requestUrl").val();
var closeEvent = function() {
    location.reload();
}


wnd = jQuery("#comments")
	.kendoWindow({
	    title: "Comments",
	    modal: true,
	    visible: false,
	    resizable: false,
	    width: 207,
	    height: 135,

	}).data("kendoWindow");

function showDetails(moveId) {
	localStorage.setItem('moveId', moveId);
    wnd.center().open();
}
function closeCommentEvent() {
    wnd.close();
}
function saveRejectComment(approvalId) {
    var moveId = localStorage.getItem('moveId');
    var approveComments = $('textarea').val();
    if (approveComments == '') {
        alert('Please add Comment');
    } else {
        jQuery.ajax({
            type: "POST",
            url: requestUrl+"/updateMoveComments",
            data: {
                moveId: moveId,
                approveComments: approveComments,
                _token:token
            },
            cache: false,
            success: function(updateComments) {
                $("#assetMoveActiveGrid").data("kendoGrid").dataSource.read();
				$("#assetMoveHistoryGrid").data("kendoGrid").dataSource.read();
				wnd.close();
            }
        });
    }
}
function updateApprovalStatus(moveId,changedStatusText,changedStatusId) {
	if (confirm('Are you sure you want to ' + changedStatusText + " " + 'selected list?')) {

		if (changedStatusText == 'Reject') {
			$.ajax({
				type: 'POST',
				data:{move_id:moveId,status:changedStatusId,status_code:changedStatusText,_token:token},
				url: requestUrl+"/updateAssetMoveEntry",
				success: function (eventData) {
					localStorage.setItem('moveId', moveId);
					wnd.center().open();
				}
			});

		} else if (changedStatusText == 'Comment') {
			showDetails(moveId);
        } else {
            $.ajax({
				type: 'POST',
				data:{move_id:moveId,status:changedStatusId,status_code:changedStatusText,_token:token},
				url: requestUrl+"/updateAssetMoveEntry",
				success: function (eventData) {
					$("#assetMoveActiveGrid").data("kendoGrid").dataSource.read();
					$("#assetMoveHistoryGrid").data("kendoGrid").dataSource.read();
				}
			});
        }
	} else
		return;
}
$(document).ready(function() {
	var detailsTemplate;
	detailsTemplate = kendo.template(jQuery("#template").html());
	wnd.content(detailsTemplate(kendo.template(jQuery("#template").html())));
	$("#c_account_id").kendoComboBox({
		placeholder: "Select Account...",
		dataTextField: "account_code",
		dataValueField: "account_id",
		filter: "contains",
		height: 400,
		change: function (e) {
			
			var accountFromId = $("#c_account_id").val();
			var asset_id = $('#c_asset_id').data('kendoMultiSelect').value();
			var grid = $("#assetMoveActiveGrid").data("kendoGrid");
			var filter =  { logic: "and", filters: []};
			var filter1 = { filters: [] };
			var filter2 = { logic: "or",  filters: [] };

			if(accountFromId != "") {
				filter1.filters.push(
					{field: "account_from_id", operator: "eq", value: parseInt(accountFromId) }
				);
				filter.filters.push(filter1);
			}
			if (asset_id != null && asset_id.length != 0) {
				console.log(asset_id);
				$.each(asset_id, function(i, v) {
					filter2.filters.push(
						{field: "asset_from_id", operator: "eq", value: v }
					);
				});
				filter.filters.push(filter2);
			}
			grid.dataSource.filter(filter);
		}
	}).data("kendoComboBox");

	$("#c_asset_id").kendoMultiSelect({
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
			var grid = $("#assetMoveActiveGrid").data("kendoGrid");
			var accountFromId = $('#c_account_id').val();
			var values = this.value();
			var filter =  { logic: "and", filters: []};
			var filter1 = { filters: [] };
			var filter2 = { logic: "or",  filters: [] };

			if(accountFromId != ""){
				filter1.filters.push(
					{field: "account_from_id", operator: "eq", value: parseInt(accountFromId) }
				);
				filter.filters.push(filter1);
			}
			console.log(values);
			if (values.length != 0) {
				$.each(values, function(i, v) {
					filter2.filters.push(
						{field: "asset_from_id", operator: "eq", value: v }
					);
				});
				filter.filters.push(filter2);
			}
			console.log(filter);
			grid.dataSource.filter(filter);
		}
	}).data("kendoMultiSelect");

	$("#c_merchant_id").kendoComboBox({
		placeholder: "Select Merchant...",
		dataTextField: "merchant_name",
		dataValueField: "merchant_id",
		filter: "contains",
		height: 400,
		dataSource: {
			transport : {
				read : {
					dataType : "json",
					url : requestUrl+"/getMerchants",
					type : "GET",
				}
			}
		},
		change: function (e) {
			if($('#c_merchant_id').val() != ""){
				var account_id = $("#c_account_id").data("kendoComboBox");
				var merchant_id = $('#c_merchant_id').val();
				var asset_id = $("#c_asset_id").data("kendoMultiSelect");
				
				var assetMoveListDataSource = new kendo.data.DataSource({
					serverPaging: true,
					pageSize: 20,
					transport : {
						read : {
							data:{_token:token,merchant_id:merchant_id},
								url: requestUrl+"/getAssetMoveList",
								dataType: "json",
								type: "POST"
							}
					},
					schema: {
						total:'total',
			            data:'active_list',
						model: {
							move_id:'move_id',
							fields: {
								priority: {
									type: "number",
									validation: { required: true,min: 0,max:99 }
								},
								status: {
									defaultValue: { value: 1, text: "Accept"}
								}
							}
						},
					}
					});
				$("#assetMoveActiveGrid")
				.data("kendoGrid")
				.setDataSource(assetMoveListDataSource);
				var accountDataSource = new kendo.data.DataSource({
					transport : {
						read : {
								url: requestUrl+"/getAccounts/"+merchant_id,
								dataType: "json",
								type: "GET"
							}
						}
					});
				account_id.setDataSource(accountDataSource);
				account_id.value("");
				account_id.enable(true);
				asset_id.enable(true);
			}
		}
	}).data("kendoComboBox");

	var assetMoveActiveGrid = jQuery("#assetMoveActiveGrid").kendoGrid({
		dataSource: {
			serverPaging: false,
			pageSize: 20,
			schema: {
				model: {
					move_id:'move_id',
					fields: {
						priority: {
							type: "number",
							validation: { required: true,min: 0,max:99 }
						},
						status: {
							defaultValue: { value: 1, text: "Accept"}
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
		detailTemplate: kendo.template($("#templateDetail").html()),
		detailInit: detailInit,
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
			field: "account_from_id",
			title: "Account From",
			hidden:true,
		},{
			field: "asset_from_id",
			title: "Asset From",
			hidden:true,
		},{
            field: "approval_status",
            title: "Action",
            template: kendo.template(jQuery('#actiontemplate').html()),
            width: 120
        },{
			field: "status",
			title: "Status",
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
	function detailInit(e) {
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
	var validator = $("#assetMoveForm").kendoValidator({
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
			hasAccount: function (input) {
				if(input.is("[id=c_account_id]")){
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
		},
		messages: { 
			hasMerchant: "Merchant Required",
			hasAccount: "Account Required",
			hasAsset: "Asset Required"
		}
	}).data("kendoValidator");
});