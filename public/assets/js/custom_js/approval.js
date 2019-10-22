function submitApproveRejectValue(checkApprovalValue) {
    if (document.adminform.boxchecked.value == 0) {
        alert('Please select value for action');
        return;
    } else if (confirm('Are you sure you want to ' + checkApprovalValue + " " + 'selected list?')) {
        submitApprovalActionList(checkApprovalValue);
    } else
        return;
}

function submitApprovalActionList(checkApprovalValue) {
    document.adminform.task.value = checkApprovalValue;
    try {
        document.adminform.onsubmit();
    } catch (submitException) {}
    document.adminform.submit();
}

function approveRejectValueCheck(checkApprovalValue) {
    allApproveRejectListCheck(checkApprovalValue.checked);
}

function allApproveRejectListCheck(checkApprovalValue) {
    var formData = document.adminform;
    var actionLength = formData.elements.length;
    approvalCounter = 0;
    for (var approveCount = 0; approveCount < actionLength; approveCount++) {
        var approveRejectName = formData.elements[approveCount];
        if (approveRejectName.name == 'cbQ[]') {
            approveRejectName.checked = checkApprovalValue;
            if (checkApprovalValue == true)
                approvalCounter++;
        }
    }
    if (checkApprovalValue == false) {
        document.adminform.boxchecked.value = 0;
    } else {
        document.adminform.boxchecked.value = approvalCounter;
    }
    return true;
}

function approvalRejectCheck(valueChecked) {
    if (valueChecked == true) {
        document.adminform.boxchecked.value++;
    } else {
        document.adminform.boxchecked.value--;
    }
}
jQuery('.pull-right').click(function() {
    localStorage.clear();
});

function statusFilterValue(statusValue) {
    localStorage.setItem('filtedDefaultId', statusValue);
    location.reload();
}

function saveRejectComment(approvalId) {
    var approvalId = localStorage.getItem('approvalId');
    var approveComments = $('textarea').val();
    if (approveComments == '') {
        alert('Please add Comment');
    } else {
        jQuery.ajax({
            type: "GET",
            url: "update_approval_comments",
            data: {
                approvalId: approvalId,
                approveComments: approveComments
            },
            cache: false,
            success: function(updateComments) {
                 approvalDetailsElement.data("kendoGrid").dataSource.read();
            }
        });
    }
}

function updateApprovalStatus(staffGroupId, approvalId, approvalActionStatus, statusId) {
    localStorage.setItem('approvalId', approvalId);
    if (approvalActionStatus != 'Actions') {
        if (confirm('Are you sure you want to ' + approvalActionStatus + " " + 'selected list?')) {
            if (approvalActionStatus == 'Reject') {
                jQuery.ajax({
                    type: "GET",
                    url: "update_approval_status",
                    data: {
                        approvalId: approvalId,
                        approvalStatus: approvalActionStatus,
                        staffGroupId: staffGroupId,
                        statusId: statusId,
                    },
                    cache: false,
                    success: function(updatestatus) {}
                });
                commentShowDetails({}, function no() {

                });
            } else if (approvalActionStatus == 'Comment') {
                showDetails(approvalId);
            } else {
                jQuery.ajax({
                    type: "GET",
                    url: "update_approval_status",
                    data: {
                        approvalId: approvalId,
                        approvalStatus: approvalActionStatus,
                        staffGroupId: staffGroupId,
                        statusId: statusId,
                    },
                    cache: false,
                    success: function(updatestatus) {
                         approvalDetailsElement.data("kendoGrid").dataSource.read();
                    }
                });
            }
        } else
            return;
    }
}
function closeCommentEvent() {
    location.reload();
}
wnd = jQuery("#comments")
    .kendoWindow({
        title: "Comments",
        modal: true,
        visible: false,
        resizable: false,
        width: "auto",
        height: "auto",
        close: closeCommentEvent

    }).data("kendoWindow");
var detailsTemplate;
detailsTemplate = kendo.template(jQuery("#template").html());

function showDetails(approval_id) {
    localStorage.setItem('approvalId', approval_id);
    wnd.content(detailsTemplate(kendo.template(jQuery("#template").html())));
    wnd.center().open();
}

function commentShowDetails() {
    wnd.content(detailsTemplate(kendo.template(jQuery("#template").html())));
    wnd.center().open();
}

    var wnd, detailsTemplate;
    var approvalDetailsElement = jQuery("#grid").kendoGrid({
        dataSource: {
            serverPaging: true,
            pageSize: 20,
            group: {
                field: "Group",
                dir: "asc"
            },
            transport: {
                read: {
                    url: "category_view" + "/" +
                        localStorage.getItem('filtedDefaultId'),
                    dataType: "json",
                    type: "GET",
                },
            },
            schema: {
                total:'total',
                data:'approval',
                model: {
                    fields: {
                        loacatinName: {
                            editable: false
                        },
                        request_date: {
                            editable: false
                        },
                        category_value: {
                            editable: false
                        },
                        staff_name: {
                            editable: false
                        },
                        request_fields: {
                            editable: false
                        },
                        stage_value: {
                            editable: false
                        },
                        live_value: {
                            editable: false
                        },
                        actionBy: {
                            editable: false
                        },
                        approval_id: {
                            editable: false
                        },
                        selectValue: {
                            editable: false
                        },
                        statusName: {
                            editable: false
                        },
                        approval_status: {
                            editable: false
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
        autoSync: true,
        sortable: true,
        reorderable: true,
        serverFiltering: true,
        groupable: true,
        resizable: false,
        toolbar: kendo.template(jQuery("#templates").html()),
        detailTemplate: kendo.template($("#templateDetail").html()),
        detailInit: detailInit,
        editable: true,
        scrollable: {
            horizontal: true
        },
        dataBound: function(e) {
            var data = this.dataSource.data();
            $.each(data, function(i, row) {
                var commentDetails = row.get("comment");
                if (commentDetails != '') {
                    $('tr[data-uid="' + row.uid + '"] td:nth-child(2)').css("background-color", "blue");
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
            var data = this.dataSource.data();
            $.each(data, function(i, row) {
                var statusFontColor = row.get("statusFontColor");
                var colorCode = row.get("colorCode");
                var element = $('tr[data-uid="' + row.uid + '"] ');
                element.css("background-color", colorCode);
                element.css("color", statusFontColor);
            });
        },
        columns: [{
            field: "approval_id",
            title: "<input name='toggleAll' title='Select or deselect all' onclick='approveRejectValueCheck(this);' type='checkbox'>",
            width: 30,
            sortable: false,
            template: kendo.template('#if(statusName == "Pending"){# <input id="cbQ#=approval_id#" name="cbQ[]" type="checkbox" value="#=approval_id#" onClick="approvalRejectCheck(this.checked);" /> #}else{# #}#'),
        }, {
            field: "approval_status",
            title: "Action",
            template: kendo.template(jQuery('#actiontemplate').html()),
            width: 120
        }, {
            field: "request_date",
            title: "Date & Time",
            width: 101
        }, {
            field: "loacatinName",
            title: "Outlet name",
            width: 101
        }, {
            field: "staff_name",
            title: "Updated by",
            width: 101
        }, {
            field: "category_value",
            title: "Section",
            width: 120
        }, {
            field: "request_fields",
            title: "Field",
            width: 120
        }, {
            field: "stage_value",
            title: "Pending",
            template: kendo.template("#if(request_fields == 'image_url' && stage_value != ''){# <img src='#=stage_value#'  width=90 height=70 /> #}else{# #=stage_value# #}#"),
            width: 120
        }, {
            field: "live_value",
            title: "Published",
            template: kendo.template("#if(request_fields == 'image_url' && live_value != ''){# <img src='#=live_value#'  width=90 height=70 /> #}else{# #=live_value# #}#"),
            width: 120
        }, {
            field: "statusName",
            title: "Status",
            width: 120
        }, {
            field: "actionBy",
            title: "Action by",
            width: 110
        }, {
            field: "updatedAt",
            title: "Updated at",
            width: 110
        }],
    });

    function detailInit(e) {
        var detailRow = e.detailRow;
        detailRow.find(".commentDetails").kendoGrid({
            dataSource: {
                transport: {
                    read: {
                        url: "rejects_comment_list" + "/" +
                            e.data.commentId,
                        dataType: "json",
                        type: "GET",
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
