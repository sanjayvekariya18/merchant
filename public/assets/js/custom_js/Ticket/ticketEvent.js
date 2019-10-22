var token = $('input[name="_token"]').val();
var requestUrl = $("#requestUrl").val();
function productionJsonCopy() {
  var copyText = document.getElementById("productionJsonDetail");
  copyText.select();
  document.execCommand("copy");
}
$(document).ready(function() {
    $("#tabstrip").kendoTabStrip().data("kendoTabStrip");

    function venueNameEditor(container, options) {
        $('<input name="' + options.field + '" required data-text-field="venue_name" data-value-field="venue_id" data-bind="value:' + options.field + '"/>').appendTo(container).kendoDropDownList({
            autoBind: true,
            dataTextField: "venue_name",
            dataValueField: "venue_id",
            dataSource: {
                transport: {
                    read: {
                        dataType: "json",
                        url: requestUrl + "/getVenueList",
                        type: "GET"
                    }
                }
            },
        });
    }

    function opponentNameEditor(container, options) {
        $('<input name="' + options.field + '" required data-text-field="opponent_name" data-value-field="opponent_id" data-bind="value:' + options.field + '"/>').appendTo(container).kendoDropDownList({
            autoBind: true,
            dataTextField: "opponent_name",
            dataValueField: "opponent_id",
            dataSource: {
                transport: {
                    read: {
                        dataType: "json",
                        url: requestUrl + "/getOpponentList",
                        type: "GET"
                    }
                }
            },
        });
    }

    function dateEditor(container, options) {
        $('<input type="text" data-text-field="' + options.field + '" data-value-field="' + options.field + '" data-bind="value:' + options.field + '" data-format="' + options.format + '"/>').appendTo(container).kendoDatePicker({});
    }
    var ticketEventGrid = jQuery("#ticketProducationDetails").kendoGrid({
        dataSource: {
            serverPaging: true,
            serverFiltering: true,
            serverSorting: false,
            pageSize: 10,
            autoSync: true,
            transport: {
                read: {
                    data: {
                        _token: token
                    },
                    url: requestUrl + "/ticketEventList",
                    dataType: "json",
                    type: "POST"
                },
                update: {
                    data: {
                        _token: token
                    },
                    url: requestUrl + "/updateEventList",
                    dataType: "jsonp",
                    type: "POST"
                },
                parameterMap: function(options, operation) {
                    if (operation == "update") {
                        var eventDateValue = new Date(options.event_date);
                        options.event_date = kendo.toString(new Date(eventDateValue), "yyyyMMMdd");
                        return options;
                    } else {
                        return options;
                    }
                }
            },
            requestEnd: function(requestResponse) {
                if (requestResponse.type === "update" && requestResponse.response) {
                    var ticketEventDetailsGrid = $("#ticketProducationDetails").data("kendoGrid");
                    ticketEventDetailsGrid.dataSource.read();
                }
            },
            schema: {
                total: 'total',
                data: 'ticket_events',
                model: {
                    id: 'production_id',
                    fields: {
                        production_name: {
                            validation: {
                                required: true
                            }
                        },
                        url_seating: {
                            editable: false
                        },
                    }
                },
            },
        },
        pageable: {
            refresh: true,
            pageSizes: true
        },
        toolbar: [{
            template: kendo.template($("#productionGridSearch").html())
        }],
        scrollable: true,
        sortable: false,
        reorderable: true,
        groupable: true,
        resizable: true,
        editable: false,
        dataBound: function(productionDetails) {
            var productionJsonDetails = this;
            var dataSource = this.dataSource;  
            productionJsonDetails.tbody.find("tr").click(function(productionDetails) {
                var row = $(this);
                var data = dataSource.getByUid(row.data('uid')); 
                var production_id=data.production_id;
                $('#production_json_top_modal').modal('show');
                $('#productionJsonDetail').val("");
                document.getElementById("productionJsonDetail").rows = "1";
                $.ajax({
                    dataType: 'json',
                    type: 'POST',
                    data: {
                        production_id: production_id,
                        _token: token
                    },
                    url: requestUrl + "/productionJsonDetail",
                    success: function(json) {
                        if (json) {
                            document.getElementById("productionJsonDetail").rows = "21";
                        }
                        document.getElementById("productionJsonDetail").value = json;
                    }
                });
            });
        },
        columns: [{
            field: "production_id",
            title: "ID",
            hidden: false,
        }, {
            field: "production_name",
            title: "Event Name",
        }, {
            field: "service_name",
            title: "Service",
        }, {
            field: "venue_id",
            template: "#:venue_name#",
            title: "venue",
            editor: venueNameEditor
        }, {
            field: "opponent_id",
            template: "#:opponent_name#",
            title: "Opponent",
            editor: opponentNameEditor
        }, {
            field: "event_date",
            title: "Event Date",
        }, {
            field: "url_seating",
            title: "Url",
            template: '<a target="_blank" href="#=url_seating#">#=url_seating#</a>'
        }],
    });
    /* event Search */
    $("#productionBtnSearch").click(function() {
        var eventSearchValue = $('#productionSearchBox').val();
        $("#ticketProducationDetails").data("kendoGrid").dataSource.filter({
            logic: "or",
            filters: [{
                field: "production_id",
                operator: "contains",
                value: eventSearchValue
            }, {
                field: "production_name",
                operator: "contains",
                value: eventSearchValue
            }, {
                field: "venue_name",
                operator: "contains",
                value: eventSearchValue
            }, {
                field: "opponent_name",
                operator: "contains",
                value: eventSearchValue
            }, {
                field: "event_date",
                operator: "contains",
                value: eventSearchValue
            }]
        });
    });
    $('#productionSearchBox').keypress(function(e) {
        if (e.which == 13) { //Enter key pressed
            $('#productionBtnSearch').click(); //Trigger search button click event
        }
    });
    //Clearing the filter
    $("#productionBtnReset").click(function() {
        $('#productionSearchBox').val("");
        $("#ticketProducationDetails").data("kendoGrid").dataSource.filter({});
    });
    /* calendar Search end*/
    /*venuw grid */
    var ticketVenueGrid = jQuery("#ticketVenueDetails").kendoGrid({
        dataSource: {
            serverPaging: true,
            serverFiltering: true,
            serverSorting: false,
            pageSize: 20,
            transport: {
                read: {
                    data: {
                        _token: token
                    },
                    url: requestUrl + "/ticketVenueList",
                    dataType: "json",
                    type: "POST"
                }
            },
            schema: {
                total: 'total',
                data: 'ticket_venue',
                model: {
                    id: 'venue_id'
                },
            },
        },
        pageable: {
            refresh: true,
            pageSizes: true
        },
        toolbar: [{
            template: kendo.template($("#venueGridSearch").html())
        }],
        scrollable: true,
        sortable: false,
        reorderable: true,
        groupable: true,
        resizable: true,
        editable: false,
        columns: [{
            field: "venue_id",
            title: "ID",
            hidden: false,
        }, {
            field: "venue_name",
            title: "Name",
        }, {
            field: "service_name",
            title: "Service",
        }, {
            field: "category_name",
            title: "Category",
        }],
    });
    /* venue Search */
    $("#venueBtnSearch").click(function() {
        var eventSearchValue = $('#venueSearchBox').val();
        $("#ticketVenueDetails").data("kendoGrid").dataSource.filter({
            logic: "or",
            filters: [{
                field: "venue_id",
                operator: "contains",
                value: eventSearchValue
            }, {
                field: "venue_name",
                operator: "contains",
                value: eventSearchValue
            }, {
                field: "service_name",
                operator: "contains",
                value: eventSearchValue
            }, {
                field: "category_name",
                operator: "contains",
                value: eventSearchValue
            }]
        });
    });
    $('#venueSearchBox').keypress(function(e) {
        if (e.which == 13) { //Enter key pressed
            $('#venueBtnSearch').click(); //Trigger search button click event
        }
    });
    //Clearing the filter
    $("#venueBtnReset").click(function() {
        $('#venueSearchBox').val("");
        $("#ticketVenueDetails").data("kendoGrid").dataSource.filter({});
    });
    /* venue Search end*/
    /*opponent grid */
    var ticketOpponentGrid = jQuery("#ticketOpponentDetails").kendoGrid({
        dataSource: {
            serverPaging: true,
            serverFiltering: true,
            serverSorting: false,
            pageSize: 20,
            transport: {
                read: {
                    data: {
                        _token: token
                    },
                    url: requestUrl + "/ticketOpponentList",
                    dataType: "json",
                    type: "POST"
                }
            },
            schema: {
                total: 'total',
                data: 'ticket_opponent',
                model: {
                    id: 'opponent_id'
                },
            },
        },
        pageable: {
            refresh: true,
            pageSizes: true
        },
        toolbar: [{
            template: kendo.template($("#opponentGridSearch").html())
        }],
        scrollable: true,
        sortable: false,
        reorderable: true,
        groupable: true,
        resizable: true,
        editable: false,
        columns: [{
            field: "opponent_id",
            title: "ID",
            hidden: false,
        }, {
            field: "opponent_name",
            title: "Name",
        }, {
            field: "service_name",
            title: "Service",
        }],
    });
    /* opponent Search */
    $("#opponentBtnSearch").click(function() {
        var eventSearchValue = $('#opponentSearchBox').val();
        $("#ticketOpponentDetails").data("kendoGrid").dataSource.filter({
            logic: "or",
            filters: [{
                field: "opponent_id",
                operator: "contains",
                value: eventSearchValue
            }, {
                field: "opponent_name",
                operator: "contains",
                value: eventSearchValue
            }, {
                field: "service_name",
                operator: "contains",
                value: eventSearchValue
            }]
        });
    });
    $('#opponentSearchBox').keypress(function(e) {
        if (e.which == 13) { //Enter key pressed
            $('#opponentBtnSearch').click(); //Trigger search button click event
        }
    });
    //Clearing the filter
    $("#opponentBtnReset").click(function() {
        $('#opponentSearchBox').val("");
        $("#ticketOpponentDetails").data("kendoGrid").dataSource.filter({});
    });
    /* opponent Search end*/
    /*service grid */
    var ticketServiceGrid = jQuery("#ticketServiceDetails").kendoGrid({
        dataSource: {
            serverPaging: true,
            serverFiltering: true,
            serverSorting: false,
            pageSize: 20,
            transport: {
                read: {
                    data: {
                        _token: token
                    },
                    url: requestUrl + "/ticketServiceList",
                    dataType: "json",
                    type: "POST"
                }
            },
            schema: {
                total: 'total',
                data: 'ticket_service',
                model: {
                    id: 'service_id'
                },
            },
        },
        pageable: {
            refresh: true,
            pageSizes: true
        },
        toolbar: [{
            template: kendo.template($("#serviceGridSearch").html())
        }],
        scrollable: true,
        sortable: false,
        reorderable: true,
        groupable: true,
        resizable: true,
        editable: false,
        columns: [{
            field: "service_id",
            title: "ID",
            hidden: false,
        }, {
            field: "service_name",
            title: "Name",
        }],
    });
    /* service Search */
    $("#serviceBtnSearch").click(function() {
        var eventSearchValue = $('#serviceSearchBox').val();
        $("#ticketServiceDetails").data("kendoGrid").dataSource.filter({
            logic: "or",
            filters: [{
                field: "service_id",
                operator: "contains",
                value: eventSearchValue
            }, {
                field: "service_name",
                operator: "contains",
                value: eventSearchValue
            }]
        });
    });
    $('#serviceSearchBox').keypress(function(e) {
        if (e.which == 13) { //Enter key pressed
            $('#serviceBtnSearch').click(); //Trigger search button click event
        }
    });
    //Clearing the filter
    $("#serviceBtnReset").click(function() {
        $('#serviceSearchBox').val("");
        $("#ticketServiceDetails").data("kendoGrid").dataSource.filter({});
    });
    /* service Search end*/
});