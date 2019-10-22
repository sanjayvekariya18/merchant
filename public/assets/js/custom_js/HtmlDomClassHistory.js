$(document).ready(function() {
    var requestUrl = $("#requestUrl").val();
    var token = $('input[name="_token"]').val();
    var htmlDomHistoryGrid = jQuery("#htmlDomHistoryGrid").kendoGrid({
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
        columns: [{
            field: "class_change",
            title: "Class",
            width: 150
        }, {
            field: "domStatus",
            title: "Status",
            width: 100
        }, {
            field: "change_date",
            title: "Date",
            width: 100
        }, {
            field: "change_time",
            title: "Time",
            width: 100
        }, {
            field: "owner_name",
            title: "Owner",
            width: 100
        }],
    });
    var domTreeList = $('#htmlDomResultGrid').data('kendoTreeList');
    $('#htmlDomResultGrid').on('click', '.historyButton', function(e) {
        var dataItem = domTreeList.dataItem($(this).closest('tr'));
        var nodeId = dataItem.nodeId;
        var identityId = $('#identity_id').val();
        var identityTableId = dataItem.identityTableId;
        var htmlDomClassHisotryWindow = $("#htmlDomHistoryGrid").data("kendoGrid");
        var htmlDomClassHisotryListDataSource = new kendo.data.DataSource({
            pageSize: 5,
            transport: {
                read: {
                    data: {
                        _token: token,
                        node_id: nodeId,
                        identity_id: identityId,
                        identity_table_id: identityTableId
                    },
                    url: requestUrl + '/gethtmlDomHistoryListList',
                    dataType: "json",
                    type: "POST"
                }
            },
            schema: {
                model: {
                    id: 'override_id',
                }
            }
        });
        htmlDomClassHisotryWindow.setDataSource(htmlDomClassHisotryListDataSource);
        $('#html_dom_class_top_modal').modal("show");
    });
});