var token = $('input[name="_token"]').val();
var requestUrl = $("#requestUrl").val();
var crosswalkVenueRequestUrl = "crosswalk_venue";

function venueTdEditor(container, options) {
    $('<input name="' + options.field + '" data-bind="value:' + options.field + '"/>').appendTo(container).kendoAutoComplete({
        autoBind: true,
        minLength: 1,
        filter: "contains",
        dataSource: {
            transport: {
                read: {
                    data: {
                        _token: token
                    },
                    dataType: "json",
                    url: crosswalkVenueRequestUrl + "/getVenueTDList",
                    type: "GET"
                }
            },
            pageSize: 5,
            serverPaging: true,
            serverFiltering: true
        },
        dataTextField: "venue_name",
        dataValueField: "venue_name",
        placeholder: "Select Venue TD"
    });
}

function venueSfEditor(container, options) {
    $('<input name="' + options.field + '" data-bind="value:' + options.field + '"/>').appendTo(container).kendoAutoComplete({
        autoBind: true,
        minLength: 1,
        filter: "contains",
        dataSource: {
            transport: {
                read: {
                    data: {
                        _token: token
                    },
                    dataType: "json",
                    url: crosswalkVenueRequestUrl + "/getVenueSFList",
                    type: "GET"
                }
            },
            pageSize: 5,
            serverPaging: true,
            serverFiltering: true
        },
        dataTextField: "venue_name",
        dataValueField: "venue_name",
        placeholder: "Select Venue SF"
    });
}

function venueShEditor(container, options) {
    $('<input name="' + options.field + '" data-bind="value:' + options.field + '"/>').appendTo(container).kendoAutoComplete({
        autoBind: true,
        minLength: 1,
        filter: "contains",
        dataSource: {
            transport: {
                read: {
                    data: {
                        _token: token
                    },
                    dataType: "json",
                    url: crosswalkVenueRequestUrl + "/getVenueSHList",
                    type: "GET"
                }
            },
            pageSize: 5,
            serverPaging: true,
            serverFiltering: true
        },
        dataTextField: "venue_name",
        dataValueField: "venue_name",
        placeholder: "Select Venue SH"
    });
}
$(document).ready(function() {
    $(function() {
        var grid = $("#crosswalkVenueGrid").data("kendoGrid");
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
    var crosswalkVenueGrid = jQuery("#crosswalkVenueGrid").kendoGrid({
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
                    url: crosswalkVenueRequestUrl + "/getCrosswalkVenue",
                    dataType: "json",
                    type: "POST"
                },
                update: {
                    data: {
                        _token: token
                    },
                    url: crosswalkVenueRequestUrl + "/updateCrosswalkVenue",
                    dataType: "jsonp",
                    type: "POST"
                },
                destroy: {
                    data: {
                        _token: token
                    },
                    url: crosswalkVenueRequestUrl + "/deleteCrosswalkVenue",
                    dataType: "jsonp",
                    type: "POST"
                },
                parameterMap: function(options, operation) {
                    if (operation == "update") {
                        return options;
                    } else {
                        return options;
                    }
                }
            },
            requestEnd: function(requestResponse) {
                if (requestResponse.type === "update" && requestResponse.response) {
                    var crosswalkVenueGrid = $("#crosswalkVenueGrid").data("kendoGrid");
                    crosswalkVenueGrid.dataSource.read();
                }
            },
            schema: {
                total: 'total',
                data: 'crosswalkVenue',
                model: {
                    id: 'crosswalk_id',
                    fields: {
                        venue_td: {
                            editable: true,
                            required: false
                        },
                        venue_sf: {
                            editable: true,
                            required: false
                        },
                        venue_sh: {
                            editable: true,
                            required: false
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
            template: kendo.template($("#crosswalkVenueHeaderAction").html())
        }],
        scrollable: true,
        sortable: false,
        reorderable: true,
        groupable: true,
        resizable: true,
        editable: true,
        columnMenu: true,
        columns: [{
            field: "venue_td",
            title: "TradeDesk",
            editor: venueTdEditor
        }, {
            field: "venue_sf",
            title: "StageFront",
            editor: venueSfEditor
        }, {
            field: "venue_sh",
            title: "StubHub",
            editor: venueShEditor
        }, ],
    });
    /* event Search */
    $("#crosswalkVenueBtnSearch").click(function() {
        var eventSearchValue = $('#crosswalkVenueSearchBox').val();
        $("#crosswalkVenueGrid").data("kendoGrid").dataSource.filter({
            logic: "or",
            filters: [{
                field: "venue_td",
                operator: "contains",
                value: eventSearchValue
            }, {
                field: "venue_sf",
                operator: "contains",
                value: eventSearchValue
            }, {
                field: "venue_sh",
                operator: "contains",
                value: eventSearchValue
            }, ]
        });
    });
    $('#crosswalkVenueSearchBox').keypress(function(e) {
        if (e.which == 13) { //Enter key pressed
            $('#crosswalkVenueBtnSearch').click(); //Trigger search button click event
        }
    });
    //Clearing the filter
    $("#crosswalkVenueBtnReset").click(function() {
        $('#crosswalkVenueSearchBox').val("");
        $("#crosswalkVenueGrid").data("kendoGrid").dataSource.filter({});
    });
    /* calendar Search end*/
});