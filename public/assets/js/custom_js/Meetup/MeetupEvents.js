var token = $('input[name="_token"]').val();
var requestUrl = $("#requestUrl").val();
var baseUrl = $("#baseUrl").val();
$(document).ready(function() {

    var meetupUnSyncGroupGrid = jQuery("#unSyncGroupPanel").kendoGrid({
        noRecords: true,
        messages: {
            noRecords: "No event Found"
        },
        pageable: {
            numeric: false,
            previousNext: false
        },
        scrollable: {
            endless: true
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
                if(row.disable_sync)
                {
                    $('tr[data-uid="' + row.uid + '"] td:nth-child(7)').find(".k-grid-sync").text("Refresh");
                }
            });
            $('#unSyncGroupPanel .k-pager-info').remove();
        },
        columns: [{
           headerTemplate: "<input type='checkbox' class='allSelectRow' />",
           template: "<input type='checkbox' class='selectRow' data-bind='checked: checked' />",
           width: "20px",
           filterable: false
        },{
            field: "group_id",
            title: "Group Id",
            hidden: true,
        }, {
            field: "logo",
            title: "Logo",
            template: "#if(data.group_photo){#<img width='100' height='50' src='#= data.group_photo #' alt='image' />#}#",
            width: "110px",
        }, {
            field: "group_name",
            title: "Group Name",
            template: '<a target="_blank" href="#=data.group_link#">#=group_name#</a>',
            width: "160px",
        }, {
            field: "City",
            title: "City",
            width: "90",
            template: "#=group_city#",
        }, {
            field: "group_timezone",
            title: "Timezone",
            width: "80px",
        }, {
            command: [{
                text: "sync",
                click: syncMeetupGroups
            }],
            title: "Action",
            width: "80px",
        }],
    }).data("kendoGrid");

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

    $("#getGroupList").click(function() {
        var meetupUnSyncGroupGrid = $("#unSyncGroupPanel").data("kendoGrid");
        var meetupUnSyncGroupDataSource = new kendo.data.DataSource({
            transport: {
                read: {
                    data: {
                        _token: token,
                    },
                    url: requestUrl + "/fetchGroupList",
                    type: 'POST',
                    dataType: "json"
                },
            },
            serverPaging: true,
            serverSorting: true,
            pageSize: 15,
            batch: true,
            schema: {
                data: "meetupGroups",
                total: "total",
                model: {
                    id: 'group_id',
                },
            },
        });
        meetupUnSyncGroupGrid.setDataSource(meetupUnSyncGroupDataSource);
    });

    var meetupGroupGrid = jQuery("#meetupGroupDetails").kendoGrid({
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
                    url: requestUrl + "/meetupCalendarList",
                    dataType: "json",
                    type: "POST"
                },
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
        scrollable: true,
        autoSync: true,
        sortable: false,
        reorderable: true,
        groupable: true,
        resizable: true,
        editable: "inline",
        toolbar: [{
            template: kendo.template($("#meetupGridSearch").html())
        }],
        detailTemplate: kendo.template($("#templateDetail").html()),
        detailInit: detailInit,
        columns: [{
                field: "graph_cal_id",
                title: "calendar Id",
                hidden: true,
            }, {
                field: "cal_name",
                title: "Calendar Name",
                template: "#if(data.cal_photo){#<img width='20' height='20' src='#= data.cal_photo #' alt='image' />#}##if(data.cal_link){#<a style='font-size: larger;vertical-align: middle;margin-left: 1%;' href='#=data.cal_link#' target='_blank'>#=data.cal_name#</a>#}else{# #=data.cal_name# #} #",
            },
            {
                field: "event_count",
                title: "Event Count",
            }
        ],
    });
    
    function detailInit(eventGridObject) {
        var detailRow = eventGridObject.detailRow;
        var Eventgrid = detailRow.find(".meetupEventDetails").kendoGrid({
            dataSource: {
                pageSize: 10,
                serverPaging: false,
                transport: {
                    read: {
                        url: requestUrl + "/meetupEventList",
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
                        url: requestUrl + "/deleteMeetupEvent",
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
            filterable: false,
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
                        if (row.shared_event) $('tr[data-uid="' + row.uid + '"] td:nth-child(9)').find(".k-grid-Share").text('unshare');
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
                width: '35%',
                template: "<div>#if(data.avatar_link){#<img class='avatarLink' src='#= data.avatar_link #' alt='image' /># }# #if(data.website_link){#<a class='eventLink' href='#=data.website_link#' target='_blank'>#=data.event_name#</a>#}else{# #=data.event_name# #} #</div>",
            },{
                field: "start_date",
                title: "Start",
                template: '#= Unix_timestamp(data.start_date,data.start_time) #'
            }, {
                field: "end_date",
                title: "End",
                template: '#= Unix_timestamp(data.end_date,data.end_time) #'
            },{
                field: "location",
                title: "City",
            }, {
                field: "comment",
                title: "Comment",
                hidden: true
            },{
                field: "status",
                title: "Status",
                width: "10%",
            },
            {
                command: [{
                    text: "Categories",
                    click: showCategories
                }],
                title: "Category",
                width: "10%"
            },
            {
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

    function meetupGridSearch()
    {
        var meetupSearchValue = $('#meetupSearchBox').val();
        $("#meetupGroupDetails").data("kendoGrid").dataSource.filter({
            logic: "or",
            filters: [{
                field: "cal_name",
                operator: "contains",
                value: meetupSearchValue
            }]
        });
    }
    /* calendar Search */
    $("#meetupBtnSearch").click(function() {
        meetupGridSearch();
    });

    $('#meetupSearchBox').keypress(function (eventObject) {
        var keyPress = eventObject.which;
        if(keyPress == 13)  // the enter key code
        {
            meetupGridSearch();
        }
    }); 
    
    $("#meetupBtnSearch").click(function() {
        meetupGridSearch();
    });
    //Clearing the filter
    $("#meetupBtnReset").click(function() {
        $("#meetupGroupDetails").data("kendoGrid").dataSource.filter({});
    });
    /* calendar Search end*/

    $("#meetupLoginRedirect").click(function(eventObject) {
        eventObject.preventDefault();
        localStorage.setItem('highlighterLogin', 'Meetup');
        window.location = baseUrl+"/social_connectors";
    });
});


function syncMeetupGroups(syncGridObject) {
    $('.content .preloader').show();
    $('.content .preloader img').show();
    var dataItem = this.dataItem($(syncGridObject.currentTarget).closest("tr"));
    var groupListId = [dataItem.group_id];
    $.ajax({
        type: "POST",
        url: requestUrl + '/syncMeetupEvents',
        data: {
            _token: token,
            meetupGroups: JSON.stringify(groupListId),
        },
        success: function(syncEventResponse, status, xhr) {
            $('.content .preloader').hide();
            $('.content .preloader img').hide();
            $("#unSyncGroupPanel").data("kendoGrid").dataSource.remove(dataItem);
            $('#meetupGroupDetails').data('kendoGrid').dataSource.read();
        }
    });
    syncGridObject.preventDefault();
}

function syncBatchGroup(syncType)
{
    $('.content .preloader').show();
    $('.content .preloader img').show();
    var meetupUnSyncGroupGrid = jQuery("#unSyncGroupPanel").data('kendoGrid');
    var unSyncGroupGridData = meetupUnSyncGroupGrid.dataSource.view();
    var checkedGroupData = [];
    if(syncType == 'checkedGroup')
    {
        for (var initData = 0; initData < unSyncGroupGridData.length; initData++)
        {
            dataItem = meetupUnSyncGroupGrid.table.find("tr[data-uid='" + unSyncGroupGridData[initData].uid + "']");
            var checkbox = dataItem.find(".selectRow");
            if (checkbox.is(":checked")) {
                checkedGroupData.push(unSyncGroupGridData[initData].group_id);
            }
        }
    } else {
        var checkedGroupData = meetupUnSyncGroupGrid.dataSource.data().map(function(x){
            return x.group_id;
        });
    }
    if(typeof checkedGroupData !== 'undefined' && checkedGroupData.length > 0)
    {
        $.ajax({
            type: "POST",
            url: requestUrl + '/syncMeetupEvents',
            data: {
                _token: token,
                meetupGroups: JSON.stringify(checkedGroupData),
            },
            success: function(syncEventResponse, status, xhr) {
                $('.content .preloader').hide();
                $('.content .preloader img').hide();
                if(syncType == 'checkedGroup')
                {
                    $("#unSyncGroupPanel").find("input:checked").each(function(){
                        if (!$(this).parents('th').length) {
                            meetupUnSyncGroupGrid.removeRow($(this).closest('tr'));
                        }
                    });
                } else {
                    $("#unSyncGroupPanel").data('kendoGrid').dataSource.data([]);
                }
                $('#meetupGroupDetails').data('kendoGrid').dataSource.read();
            }
        });
    } else {
        $('.content .preloader').hide();
        $('.content .preloader img').hide();
    }
}
