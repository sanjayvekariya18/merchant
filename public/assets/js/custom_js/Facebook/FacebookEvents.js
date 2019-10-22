var token = $('input[name="_token"]').val();
var requestUrl = $("#requestUrl").val();
var baseUrl = $("#baseUrl").val();
$(document).ready(function() {
    commentWindow = jQuery("#commentsWindow").kendoWindow({
        title: "Comments",
        modal: true,
        visible: false,
        resizable: false,
        width: "auto",
        height: "auto",
        close: function() {
            $('#top_modal').modal("show");
        },
    }).data("kendoWindow");
    var detailsTemplate;
    detailsTemplate = kendo.template(jQuery("#commentTemplate").html());
    var facebookEventsGrid = jQuery("#facebookEvents").kendoGrid({
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
                    url: requestUrl + "/facebookEventList",
                    dataType: "json",
                    type: "POST"
                },
                destroy: {
                    data: {
                        _token: token
                    },
                    url: requestUrl + "/deleteFacebookEvent",
                    dataType: "jsonp",
                    type: "POST",
                }
            },
            schema: {
                total: 'total',
                data: 'graph_calendar',
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
        toolbar: [{
            template: kendo.template($("#facebookGridSearch").html())
        }],
        editable: "inline",
        editable: {
            mode: "inline",
            confirmation: "Are you sure you want to hide this record??"
        },
        dataBound: function(eventObject) {
            var data = this.dataSource.data();
            $.each(data, function(i, row) {
                if ((typeof row.owner_event !== 'undefined' && row.owner_event == true) && row.matchRoles == 4) {
                    $('tr[data-uid="' + row.uid + '"] td:nth-child(7)').find(".k-grid-delete").remove();
                    if (row.shared_event) $('tr[data-uid="' + row.uid + '"] td:nth-child(6)').find(".k-grid-Share").text('unshare');
                } else {
                    if (row.comment) {
                        $('tr[data-uid="' + row.uid + '"] td:nth-child(6)').find(".k-grid-Share").remove();
                        $('tr[data-uid="' + row.uid + '"]').css("background-color", "darkgrey")
                    } else {
                        $('tr[data-uid="' + row.uid + '"] td:nth-child(6)').find(".k-grid-delete,.k-grid-Share").remove();
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
            width: "34%",
            template: "<div>#if(data.avatar_link){#<img class='facebookAvatarLink' src='#= data.avatar_link #' alt='image' /># }# #if(data.website_link){#<a class='facebookEventLink' href='#=data.website_link#' target='_blank'>#=data.event_name#</a>#}else{# <span class='facebookEventLink'>#=data.event_name#</span> #} #</div>",
        }, {
            field: "start_date",
            title: "Start Date",
            width: "16%",
            template: '#= Unix_timestamp(data.start_date,data.start_time) #'
        }, {
            field: "end_date",
            title: "End Date",
            width: "16%",
            template: '#= Unix_timestamp(data.end_date,data.end_time) #'
        },
        {
            field: "location",
            title: "city",
            width: "15%",
        }, {
            field: "comment",
            title: "Comment",
            width: "20%",

        },{
            command: [{
                text: "Share",
                click: shareToGroup
            }, {
                name: "destroy",
                text: "Hide"
            }],
            title: "&nbsp;",
            width: "10%"
        }]
    });
	var facebookSyncEventGrid = jQuery("#facebookSyncEvent").kendoGrid({
        noRecords: true,
        messages: {
            noRecords: "No event Found"
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
        toolbar: kendo.template($("#syncEventTemplate").html()),
        dataBound: function(eventObject) {
            var data = this.dataSource.data();
            $.each(data, function(i, row) {
                if(row.disable_sync)
                {
                    $('tr[data-uid="' + row.uid + '"] td:nth-child(9)').find(".k-grid-sync").remove(); 
                }
            });
        },
        columns: [{
           headerTemplate: "<input type='checkbox' class='allSelectRow' />",
           template: "<input type='checkbox' class='selectRow' data-bind='checked: checked' />",
           width: "20px",
           filterable: false
        },{
            field: "event_id",
            title: "Event Id",
            hidden: true,
        }, {
            field: "logo",
            title: "Logo",
            template: "#if(data.logo){#<img width='100' height='50' src='#= data.logo #' alt='image' />#}#",
            width: "110px",
            groupable: false
        }, {
            field: "event_name",
            title: "Event Name",
            template: '<a target="_blank" href="#=url#">#=event_name#</a>',
            width: "160px",
        }, {
            field: "start_date",
            title: "Start",
            width: "120px",
            template: '#= Unix_timestamp(data.start_date,data.start_time) #'
        }, {
            field: "end_date",
            title: "End",
            width: "120px",
            template: '#= Unix_timestamp(data.end_date,data.end_time) #'
        }, {
            field: "address.city",
            title: "City",
            width: "90",
            template: "#if(data.city){# #=data.city# #}#",
        },{
            command: [{
                text: "sync",
                click: syncFacebookEvent
            }],
            title: "Action",
            width: "80px",
        }],
    }).data("kendoGrid");

    function commentShowDetails() {
        commentWindow.content(detailsTemplate(kendo.template(jQuery("#commentTemplate").html())));
        commentWindow.center().open();
    }

    // share to group
    $('#shareEventToGroup').click(function() {
        var sharedGroupMultiselect = $("#sharedGroupMultiselect").data("kendoMultiSelect");
        var previouCountObject = $("#previousGroupCount").val();
        var previouCount = JSON.parse(previouCountObject);
        var currentcount = sharedGroupMultiselect.value();
        if (typeof previouCount !== 'undefined' && previouCount.length > 0) {
            var unsharedEventExist = arrayContainsArray(currentcount, previouCount);
            if (!unsharedEventExist) {
                $('#top_modal').modal("hide");
                commentShowDetails({}, function no() {});
            } else {
                shareStatusWithGroup({}, function no() {});
            }
        } else {
            shareStatusWithGroup({}, function no() {});
        }
    });

    $('#facebookSearchBox').keypress(function (eventObject) {
        var keyPress = eventObject.which;
        if(keyPress == 13)  // the enter key code
        {
            facebookGridSearch();
        }
    }); 
    function facebookGridSearch()
    {
        var facebookSearchValue = $('#facebookSearchBox').val();
        $("#facebookEvents").data("kendoGrid").dataSource.filter({
            logic: "or",
            filters: [{
                field: "event_name",
                operator: "contains",
                value: facebookSearchValue
            },
            {
                field   : "start_date",
                operator: "contains",
                value   : facebookSearchValue
            },
            {
                field   : "end_date",
                operator: "contains",
                value   : facebookSearchValue
            },
            {
                field   : "comment",
                operator: "contains",
                value   : facebookSearchValue
            },
            {
                field   : "location",
                operator: "contains",
                value   : facebookSearchValue
            }]
        });
    }
    $("#facebookBtnSearch").click(function() {
        facebookGridSearch();
    });
    //Clearing the filter
    $("#facebookBtnReset").click(function() {
        $("#facebookEvents").data("kendoGrid").dataSource.filter({});
    });
	// this is the id of the form
    $(".searchOwnEvents").click(function() {
        var eventSearchUrl = $(this).attr("data-url");
        var facebookSyncEventGrid = $("#facebookSyncEvent").data("kendoGrid");
        var facebookSyncEventDataSource = new kendo.data.DataSource({
            transport: {
                read: {
                    data: {
                        _token: token,
                        searchUrl : eventSearchUrl
                    },
                    url: requestUrl + "/fetchMyEventList",
                    type: 'POST',
                    dataType: "json"
                },
            },
            serverPaging: false,
            serverSorting: false,
            schema: {
                data: "events",
                model: {
                    id: 'event_id',
                },
            },
        });
        facebookSyncEventGrid.setDataSource(facebookSyncEventDataSource);
    });
	$("#facebookLoginRedirect").click(function(eventObject) {
        eventObject.preventDefault();
        localStorage.setItem('highlighterLogin', 'Facebook');
        window.location = baseUrl+"/social_connectors";
    });
    /*get user group list */
    var sharedGroupMultiselect = new kendo.data.DataSource({
        transport: {
            read: {
                dataType: "json",
                url: requestUrl + "/getUserGroupList",
                type: "GET",
            }
        }
    });
    var groupDropDownList = $("#sharedGroupMultiselect").kendoMultiSelect({
        dataTextField: "group_name",
        dataValueField: "group_id",
        filter: "contains",
        height: 400,
        placeholder: "Select Group",
        dataSource: sharedGroupMultiselect
    });
});

function shareToGroup(eventGridObject) {
    $('.content .preloader').show();
    $('.content .preloader img').show();
    var sharedGroupMultiselect = $("#sharedGroupMultiselect").data("kendoMultiSelect");
    eventGridObject.preventDefault();
    var dataItem = this.dataItem($(eventGridObject.currentTarget).closest("tr"));
    $.ajax({
        data: {
            _token: token,
            calendar_event_id: dataItem.calendar_event_id,
        },
        type: 'POST',
        dataType: 'json',
        url: requestUrl + "/facebooksharedEventGroup",
        success: function(sharedGroupIds) {
            var previousGroupCount = sharedGroupIds;
            $('#previousGroupCount').val(JSON.stringify(previousGroupCount));
            var groupIdValues = sharedGroupIds.map(function(groupId) {
                return groupId;
            }).join(',');
            sharedGroupMultiselect.value(sharedGroupIds);
            $('#sharedEventName').html(dataItem.event_name);
            $('#sharedEventId').val(dataItem.calendar_event_id);
            $('#sharedEventGroupList').val(groupIdValues);
            $('.content .preloader').hide();
            $('.content .preloader img').hide();
            $('#top_modal').modal("show");
        }
    });
}
function syncFacebookEvent(syncGridObject) {
    $('.content .preloader').show();
    $('.content .preloader img').show();
    var dataItem = this.dataItem($(syncGridObject.currentTarget).closest("tr"));
    var eventlist = [dataItem.event_id];
    $.ajax({
        type: "POST",
        url: requestUrl + '/syncFacebookEvents',
        data: {
            _token: token,
            eventList: JSON.stringify(eventlist),
        },
        success: function(syncEventResponse, status, xhr) {
            $('.content .preloader').hide();
            $('.content .preloader img').hide();
            $("#facebookSyncEvent").data("kendoGrid").dataSource.remove(dataItem);
            $('#facebookEvents').data('kendoGrid').dataSource.read();
        }
    });
    syncGridObject.preventDefault();
}

function syncBatchEvent(syncType)
{
    $('.content .preloader').show();
    $('.content .preloader img').show();
    var facebookSyncEventGrid = jQuery("#facebookSyncEvent").data('kendoGrid');
    var syncEventGridData = facebookSyncEventGrid.dataSource.view();
    var checkedEventData = [];
    if(syncType == 'checkedEvents')
    {
        for (var initData = 0; initData < syncEventGridData.length; initData++)
        {
            dataItem = facebookSyncEventGrid.table.find("tr[data-uid='" + syncEventGridData[initData].uid + "']");
            var checkbox = dataItem.find(".selectRow");
            if (checkbox.is(":checked")) {
                checkedEventData.push(syncEventGridData[initData].event_id);
            }
        }
    } else {
        var checkedEventData = facebookSyncEventGrid.dataSource.data().map(function(x){
            return x.event_id;
        });
    }

    if(typeof checkedEventData !== 'undefined' && checkedEventData.length > 0)
    {
        $.ajax({
            type: "POST",
            url: requestUrl + '/syncFacebookEvents',
            data: {
                _token: token,
                eventList: JSON.stringify(checkedEventData),
            },
            success: function(syncEventResponse, status, xhr) {
                $('.content .preloader').hide();
                $('.content .preloader img').hide();
                if(syncType == 'checkedEvents')
                {
                    $("#facebookSyncEvent").find("input:checked").each(function(){
                        if (!$(this).parents('th').length) {
                            facebookSyncEventGrid.removeRow($(this).closest('tr'));
                        }
                    })
                } else {
                    $("#facebookSyncEvent").data('kendoGrid').dataSource.data([]);
                }
                $('#facebookEvents').data('kendoGrid').dataSource.read();
            }
        });
    } else {
        $('.content .preloader').hide();
        $('.content .preloader img').hide();
    }
}

function shareStatusWithGroup() {
    var calendarFetchList = $("#sharedGroupMultiselect").data("kendoMultiSelect");
    $('#sharedEventGroupList').val(calendarFetchList.value().toString());
    var unshareComments = $('textarea').val();
    $('.modal .preloader').show();
    $('.modal img').show();
    $.ajax({
        type: 'POST',
        data: $('#shareToGroup').serialize() + "&unshareComment=" + unshareComments,
        url: requestUrl + '/saveSharedEventsToGroup',
        success: function(json) {
            $('.modal .preloader').hide();
            $('.modal img').hide();
            jQuery("#commentsWindow").data("kendoWindow").close();
            $('#top_modal').modal("hide");
        }
    });
}
function arrayContainsArray(superset, subset) {
    if (0 === subset.length) {
        return false;
    }
    return subset.every(function(value) {
        return (superset.indexOf(value) >= 0);
    });
}

function closeCommentEvent() {
    jQuery("#commentsWindow").data("kendoWindow").close();
    $('#top_modal').modal("show");
}

function textareHeightWidth() {
    var textarea = document.querySelector('textarea');
    textarea.addEventListener('keydown', autosize);

    function autosize() {
        var el = this;
        setTimeout(function() {
            el.style.cssText = 'height:auto; padding:0';
            el.style.cssText = 'height:' + el.scrollHeight + 'px';
        }, 0);
    }
}