var token = $('input[name="_token"]').val();
var requestUrl = $("#requestUrl").val();

function merchantIdEditor(container, options) {
    $("<input name='" + options.field + "' required type='text' data-text-field='identity_name' data-value-field='identity_id' data-bind='value:" + options.field + "' />").appendTo(container).kendoComboBox({
        autoBind: true,
        dataTextField: "identity_name",
        dataValueField: "identity_id",
        dataSource: {
            transport: {
                read: {
                    dataType: 'json',
                    url: requestUrl + "/getMerchantIdList",
                    type: "GET"
                }
            }
        }
    });
}

function userIdEditor(container, options) {
    $("<input name='" + options.field + "' required type='text' data-text-field='user_id' data-value-field='user_id' data-bind='value:" + options.field + "' />").appendTo(container).kendoComboBox({
        autoBind: true,
        dataTextField: "user_id",
        dataValueField: "user_id",
        dataSource: {
            transport: {
                read: {
                    dataType: 'json',
                    url: requestUrl + "/getUserIdList",
                    type: "GET"
                }
            }
        }
    });
}

function statusEditor(container, options) {
    $("<input name='" + options.field + "'/>").appendTo(container).kendoNumericTextBox({
        min: 0,
        step: 1,
        decimals: 0
    });
    var tooltipElement = $("<span class='k-invalid-msg' data-for='" + options.field + "'></span>");
    tooltipElement.appendTo(container);
}

function dateAddedEditor(container, options) {
    $("<input type='text' data-text-field='" + options.field + "' data-value-field='" + options.field + "' data-bind='value:" + options.field + "' data-format='" + options.format + "'/>").appendTo(container).kendoDateTimePicker({});
}
$(document).ready(function() {
    $(function() {
        var grid = $("#activitiesGrid").data("kendoGrid");
        grid.table.on("focusout", ".k-invalid", function() {
            var content = grid.content;
            var height = content.height();
            var cell = $(this).closest("td");
            var message = cell.find(".k-invalid-msg");
            var callout = message.find(".k-callout");
            var position = message.position();
            var top = position.top + callout.outerHeight() + message.outerHeight();
            if (top > height) {
                content.scrollTop(content.scrollTop() + top - height);
            }
        });
    });
    var activitiesGrid = jQuery("#activitiesGrid").kendoGrid({
        dataSource: {
            serverPaging: true,
            serverFiltering: true,
            serverSorting: false,
            pageSize: 50,
            autoSync: true,
            group: [],
            transport: {
                read: {
                    data: {
                        _token: token
                    },
                    url: requestUrl + "/getActivities",
                    dataType: "json",
                    type: "POST"
                },
                update: {
                    data: {
                        _token: token
                    },
                    url: requestUrl + "/updateActivities",
                    dataType: "jsonp",
                    type: "POST"
                },
                destroy: {
                    data: {
                        _token: token
                    },
                    url: requestUrl + "/deleteActivities",
                    dataType: "jsonp",
                    type: "POST"
                },
                parameterMap: function(options, operation) {
                    if (operation == "update") {
                        options.date_added = kendo.toString(new Date(options.date_added), "MM/dd/yyyy HH:mm:ss");
                        return options;
                    } else {
                        return options;
                    }
                }
            },
            requestEnd: function(requestResponse) {
                if (requestResponse.type === "update" && requestResponse.response) {
                    var activitiesGrid = $("#activitiesGrid").data("kendoGrid");
                    activitiesGrid.dataSource.read();
                }
            },
            schema: {
                total: 'total',
                data: 'activities',
                model: {
                    id: 'activity_id',
                    fields: {
                        username: { editable: false },
                        action: {
                            validation: {
                                maxlength: function(input) {
                                    if (input.val().length != 0) {
                                        if (input.val().length > 32) {
                                            input.attr("data-maxlength-msg", "Max length is 32");
                                            return false;
                                        }
                                        if (input.val().length < 0) {
                                            input.attr("data-maxlength-msg", "Min length is 0");
                                            return false;
                                        }
                                    }
                                    return true;
                                }
                            }
                        },
                        message: { editable: false },
                        status: {
                            type: "number"
                        },
                        ip_address: { editable: false },
                        user_timezone: { editable: false },
                        user_city: {
                            validation: {
                                maxlength: function(input) {
                                    if (input.val().length != 0) {
                                        if (input.val().length > 50) {
                                            input.attr("data-maxlength-msg", "Max length is 50");
                                            return false;
                                        }
                                        if (input.val().length < 0) {
                                            input.attr("data-maxlength-msg", "Min length is 0");
                                            return false;
                                        }
                                    }
                                    return true;
                                }
                            }
                        },
                    },
                },
            },
        },
        pageable: {
            refresh: true,
            pageSizes: [50, 100, 200]
        },
        noRecords: true,
        messages: {
            noRecords: "No event Found"
        },
        toolbar: [{
            template: kendo.template($("#activitiesHeaderAction").html())
        }],
        scrollable: true,
        sortable: false,
        reorderable: true,
        groupable: true,
        resizable: true,
        editable: true,
        columnMenu: true,
        columns: [{
            field: "merchant_id",
            title: "Merchant",
            template: "#:identity_name#",
            editor: merchantIdEditor,
            width: "100px"
        }, {
            field: "username",
            title: "User",
            width: "100px"
        }, {
            field: "action",
            title: "Action",
            width: "100px",
            hidden: true
        }, {
            field: "message",
            title: "Message",
            template: '<h6 class="timeline-title">#=message#  from #=timeDetails# #=humanTiming#</h4>',
            width: "200px"
        }, {
            field: "ip_address",
            title: "Ip Address",
            width: "100px"
        }, {
            field: "status",
            title: "Status",
            editor: statusEditor,
            width: "100px",
            hidden: true
        }, {
            field: "user_timezone",
            title: "User Timezone",
            width: "130px",
            hidden: false
        }, {
            field: "user_time",
            title: "User Time",
            width: "100px",
            hidden: false
        }, {
            field: "date_added",
            title: "Date Added",
            template: "#if(data.date_added){# #= kendo.toString(new Date(date_added), 'dd MMM yyyy hh:mm tt')# #}#",
            format: "{0:yyyy-MM-dd HH:mm:ss}",
            editor: dateAddedEditor,
            width: "100px"
        }, {
            field: "user_city",
            title: "User City",
            width: "100px",
            hidden: false
        }, ],
    });
    /* event Search */
    $("#activitiesBtnSearch").click(function() {
        var eventSearchValue = $('#activitiesSearchBox').val();
        $("#activitiesGrid").data("kendoGrid").dataSource.filter({
            logic: "or",
            filters: [{
                field: "merchant_id",
                operator: "contains",
                value: eventSearchValue
            }, {
                field: "domain",
                operator: "contains",
                value: eventSearchValue
            }, {
                field: "context",
                operator: "contains",
                value: eventSearchValue
            }, {
                field: "user",
                operator: "contains",
                value: eventSearchValue
            }, {
                field: "user_id",
                operator: "contains",
                value: eventSearchValue
            }, {
                field: "action",
                operator: "contains",
                value: eventSearchValue
            }, {
                field: "message",
                operator: "contains",
                value: eventSearchValue
            }, {
                field: "ip_address",
                operator: "contains",
                value: eventSearchValue
            }, {
                field: "user_timezone",
                operator: "contains",
                value: eventSearchValue
            }, {
                field: "user_time",
                operator: "contains",
                value: eventSearchValue
            }, {
                field: "user_city",
                operator: "contains",
                value: eventSearchValue
            }, ]
        });
    });
    $('#activitiesSearchBox').keypress(function(e) {
        if (e.which == 13) { //Enter key pressed
            $('#activitiesBtnSearch').click(); //Trigger search button click event
        }
    });
    //Clearing the filter
    $("#activitiesBtnReset").click(function() {
        $('#activitiesSearchBox').val("");
        $("#activitiesGrid").data("kendoGrid").dataSource.filter({});
    });
    /* calendar Search end*/
});