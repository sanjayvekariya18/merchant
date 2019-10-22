var token = $('input[name="_token"]').val();
var requestUrl = $("#requestUrl").val();
var baseUrl = $("#baseUrl").val();

function syncGoogleGroups(syncGridObject) {
    $('.content .preloader').show();
    $('.content .preloader img').show();
    var dataItem = this.dataItem($(syncGridObject.currentTarget).closest("tr"));
    var groupListId = [dataItem.cal_id];
    $.ajax({
        type: "POST",
        url: requestUrl + '/syncGoogleEvents',
        data: {
            _token: token,
            googleGroups: JSON.stringify(groupListId),
        },
        success: function(syncEventResponse, status, xhr) {
            $('.content .preloader').hide();
            $('.content .preloader img').hide();
            $("#unSyncGroupPanel").data("kendoGrid").dataSource.remove(dataItem);
            $('#googleCalendarDetails').data('kendoGrid').dataSource.read();
        }
    });
    syncGridObject.preventDefault();
}

function syncBatchGroup(syncType)
{
    $('.content .preloader').show();
    $('.content .preloader img').show();
    var googleUnSyncGroupGrid = jQuery("#unSyncGroupPanel").data('kendoGrid');
    var unSyncGroupGridData = googleUnSyncGroupGrid.dataSource.view();
    var checkedGroupData = [];
    if(syncType == 'checkedGroup')
    {
        for (var initData = 0; initData < unSyncGroupGridData.length; initData++)
        {
            dataItem = googleUnSyncGroupGrid.table.find("tr[data-uid='" + unSyncGroupGridData[initData].uid + "']");
            var checkbox = dataItem.find(".selectRow");
            if (checkbox.is(":checked")) {
                checkedGroupData.push(unSyncGroupGridData[initData].cal_id);
            }
        }
    } else {
        var checkedGroupData = googleUnSyncGroupGrid.dataSource.data().map(function(x){
            return x.cal_id;
        });
    }
    if(typeof checkedGroupData !== 'undefined' && checkedGroupData.length > 0)
    {
        $.ajax({
            type: "POST",
            url: requestUrl + '/syncGoogleEvents',
            data: {
                _token: token,
                googleGroups: JSON.stringify(checkedGroupData),
            },
            success: function(syncEventResponse, status, xhr) {
                $('.content .preloader').hide();
                $('.content .preloader img').hide();
                if(syncType == 'checkedGroup')
                {
                    $("#unSyncGroupPanel").find("input:checked").each(function(){
                        if (!$(this).parents('th').length) {
                            googleUnSyncGroupGrid.removeRow($(this).closest('tr'));
                        }
                    });
                } else {
                    $("#unSyncGroupPanel").data('kendoGrid').dataSource.data([]);
                }
                $('#googleCalendarDetails').data('kendoGrid').dataSource.read();
            }
        });
    } else {
        $('.content .preloader').hide();
        $('.content .preloader img').hide();
    }
}
$(document).ready(function() {
    $("#getCalendarList").click(function() {
        $('.content .preloader').show();
        $('.content .preloader img').show();
        $.ajax({
            type: 'POST',
            dataType: "json",
            data: {
                _token: token
            },
            url: requestUrl + "/fetchCalendarList",
            error: function(xhr, status, error) {
                console.log(error);
            },
            success: function(fetchedCalendarList, status, xhr) {
                var googleUnSyncGroupGrid = $("#unSyncGroupPanel").data("kendoGrid");
                googleUnSyncGroupGrid.dataSource.data(fetchedCalendarList);
                $('.content .preloader').hide();
                $('.content .preloader img').hide();
            }
        });
    });


    var googleUnSyncGroupGrid = jQuery("#unSyncGroupPanel").kendoGrid({
        dataSource: {
            transport: {
                read: {
                    data: {
                        _token: token,
                    },
                    url: requestUrl + "/getUnsyncedCalendar",
                    type: 'GET',
                    dataType: "json"
                },
            },
            serverPaging: true,
            serverSorting: true,
            pageSize: 10,
            batch: true,
            schema: {
                data: "googleGroups",
                total: "total",
                model: {
                    id: 'cal_id',
                },
            },
        },
        noRecords: true,
        messages: {
            noRecords: "No Calendar Found"
        },
        pageable: {
            refresh: true,
            pageSizes: true
        },
        autoSync: true,
        sortable: true,
        reorderable: true,
        groupable: true,
        resizable: true,
        toolbar: kendo.template($("#syncGroupTemplate").html()),
        dataBound: function(eventObject) {
            var data = this.dataSource.data();
            $.each(data, function(i, row) {
                if(row.status)
                {
                    $('tr[data-uid="' + row.uid + '"] td:nth-child(5)').find(".k-grid-sync").text("Re-Sync");
                }
            });
        },
        columns: [{
           headerTemplate: "<input type='checkbox' class='allSelectRow' />",
           template: "<input type='checkbox' class='selectRow' data-bind='checked: checked' />",
           width: "20px",
           filterable: false
        },{
            field: "graph_cal_id",
            title: "Id",
            hidden: true,
        },{
            field: "cal_id",
            title: "calendar Id",
        }, {
            field: "cal_name",
            title: "Calendar Name",
        }, {
            command: [{
                text: "sync",
                click:syncGoogleGroups
            }],
            title: "Action",
        }],
    });

    $("#unSyncGroupPanel").on('click', '.allSelectRow', function(groupData) {
        var checkedData = groupData.target.checked;
        $('.selectRow').each(function (idx, item) {
            if (checkedData) {
                if(!$(this).prop('checked') == true){
                    $(this).click();
                }
            } else {
                if($(this).prop('checked') == true){
                    $(this).click();
                }
            }
        });
    });
    var googleCalendarGrid = jQuery("#googleCalendarDetails").kendoGrid({
        dataSource: {
            serverPaging: true,
            serverFiltering: true,
            serverSorting: false,
            pageSize: 10,
            transport: {
                read: {
                    data: {
                        _token: token
                    },
                    url: requestUrl + "/googleCalendarList",
                    dataType: "json",
                    type: "POST"
                },
                destroy: {
                    data: {
                        _token: token
                    },
                    url: requestUrl + "/deleteGoogleCalendar",
                    dataType: "jsonp",
                    type: "POST",
                }
            },
            schema: {
                total: 'total',
                data: 'graph_calendar',
                model: {
                    id: 'cal_id',
                },
            },
        },
        pageable: {
            refresh: true,
            pageSizes: true
        },
        toolbar: [{
            template: kendo.template($("#calendarGridSearch").html())
        }],
        scrollable: true,
        autoSync: true,
        sortable: false,
        reorderable: true,
        groupable: true,
        resizable: true,
        editable: "inline",
        detailTemplate: kendo.template($("#templateDetail").html()),
        detailInit: detailInit,
        columns: [{
                field: "graph_cal_id",
                title: "calendar Id",
                hidden: true,
            }, {
                field: "cal_name",
                title: "Calendar Name",
                template: "#if(data.cal_name){##=data.cal_name##}# [#=data.event_count#]",
            }
        ],
    });

    function detailInit(eventGridObject) {
        var detailRow = eventGridObject.detailRow;
        var Eventgrid = detailRow.find(".googleEventDetails").kendoGrid({
            dataSource: {
                pageSize: 10,
                serverPaging: false,
                transport: {
                    read: {
                        url: requestUrl + "/googleEventList",
                        data: {
                            graphCalId: eventGridObject.data.graph_cal_id,
                            _token: token
                        },
                        dataType: "json",
                        type: "POST",
                    },
                    destroy: {
                        data: {
                            _token: token
                        },
                        url: requestUrl + "/deleteGoogleEvent",
                        dataType: "jsonp",
                        type: "POST",
                    }
                },
                schema: {
                    model: {
                        id: 'calendar_event_id',
                    },
                },
            },
            pageable: {
                refresh: true,
                pageSizes: true
            },
            filterable: {
                mode: "row"
            },
            batch: true,
            scrollable: false,
            sortable: true,
            editable: "inline",
            editable: {
                mode: "inline",
                confirmation: "Are you sure you want to hide this record??"
            },
            columnMenu: true,
            dataBound: function(eventObject) {
                var data = this.dataSource.data();
                $.each(data, function(i, row) {
                    if ((typeof row.owner_event !== 'undefined' && row.owner_event == true) && row.matchRoles == 4) {
                        $('tr[data-uid="' + row.uid + '"] td:nth-child(9)').find(".k-grid-delete").remove();
                        if (row.shared_event) $('tr[data-uid="' + row.uid + '"] td:nth-child(7)').find(".k-grid-Share").text('unshare');
                    } else {
                        if (row.comment) {
                            $('tr[data-uid="' + row.uid + '"] td:nth-child(9)').find(".k-grid-Share").remove();
                            $('tr[data-uid="' + row.uid + '"]').css("background-color", "darkgrey")
                        } else {
                            $('tr[data-uid="' + row.uid + '"] td:nth-child(9)').find(".k-grid-delete,.k-grid-Share").remove();
                        }
                    }
                });
            },
            columns: [{
                field: "calendar_event_id",
                title: "Event Id",
                hidden: true,
            }, {
                field: "event_name",
                title: "Event",
                filterable: {
                    cell: {
                        operator: "contains",
                    }
                },
                template: "#if(data.website_link){#<a class='eventLink' href='#=data.website_link#' target='_blank'>#=data.event_name#</a>#}else{# #=data.event_name# #} #",
            }, {
                field: "start_date",
                title: "Start Date",
                template: '#= Unix_timestamp(data.start_date,data.start_time,data.all_day) #'
            }, {
                field: "end_date",
                title: "End Date",
                template: '#= Unix_timestamp(data.end_date,data.end_time,data.all_day) #'
            }, {
                field: "location",
                title: "City"
            },{
                field: "comment",
                title: "Comment",
                hidden: true,
            },{
                field: "status",
                title: "Status",
                width: "10%",
            },{
                command: [{
                    text: "Categories",
                    click: showCategories
                }],
                title: "Category",
                width: "10%"
            },{
                command: [{
                    text: "Share",
                    click: shareToGroup
                }, {
                    name: "destroy",
                    text: "Hide"
                }],
                title: "&nbsp;",
            }]
        }).data("kendoGrid");
    }
    /* calendar Search */
    $("#calendarBtnSearch").click(function() {
        var calendarSearchValue = $('#calendarSearchBox').val();
        $("#googleCalendarDetails").data("kendoGrid").dataSource.filter({
            logic: "or",
            filters: [{
                field: "cal_name",
                operator: "contains",
                value: calendarSearchValue
            }]
        });
    });
    //Clearing the filter
    $("#calendarBtnReset").click(function() {
        $("#googleCalendarDetails").data("kendoGrid").dataSource.filter({});
    });
    /* calendar Search end*/
    
    $("#googleLoginRedirect").click(function(eventObject) {
        eventObject.preventDefault();
        localStorage.setItem('highlighterLogin', 'Google');
        window.location = baseUrl+"/social_connectors";
    });
});
