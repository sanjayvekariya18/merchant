var token = $('input[name="_token"]').val();
var requestUrl = $("#requestUrl").val();
var crosswalkProductionRequestUrl = "crosswalk_production";

function productionTdEditor(container, options) {
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
                    url: crosswalkProductionRequestUrl + "/getProductionTDList",
                    type: "GET"
                }
            },
            pageSize: 5,
            serverPaging: true,
            serverFiltering: true
        },
        dataTextField: "production_name",
        dataValueField: "production_name",
        placeholder: "Select Production TD"
    });
}

function productionSfEditor(container, options) {
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
                    url: crosswalkProductionRequestUrl + "/getProductionSFList",
                    type: "GET"
                }
            },
            pageSize: 5,
            serverPaging: true,
            serverFiltering: true
        },
        dataTextField: "production_name",
        dataValueField: "production_name",
        placeholder: "Select Production SF"
    });
}

function productionShEditor(container, options) {
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
                    url: crosswalkProductionRequestUrl + "/getProductionSHList",
                    type: "GET"
                }
            },
            pageSize: 5,
            serverPaging: true,
            serverFiltering: true
        },
        dataTextField: "production_name",
        dataValueField: "production_name",
        placeholder: "Select Production SH"
    });
}
$(document).ready(function() {
    $(function() {
        var grid = $("#crosswalkProductionGrid").data("kendoGrid");
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
    var crosswalkProductionGrid = jQuery("#crosswalkProductionGrid").kendoGrid({
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
                    url: crosswalkProductionRequestUrl + "/getCrosswalkProduction",
                    dataType: "json",
                    type: "POST"
                },
                update: {
                    data: {
                        _token: token
                    },
                    url: crosswalkProductionRequestUrl + "/updateCrosswalkProduction",
                    dataType: "jsonp",
                    type: "POST"
                },
                destroy: {
                    data: {
                        _token: token
                    },
                    url: crosswalkProductionRequestUrl + "/deleteCrosswalkProduction",
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
                    var crosswalkProductionGrid = $("#crosswalkProductionGrid").data("kendoGrid");
                    crosswalkProductionGrid.dataSource.read();
                }
            },
            schema: {
                total: 'total',
                data: 'crosswalkProduction',
                model: {
                    id: 'crosswalk_id',
                    fields: {
                        production_td: {
                            editable: true,
                            required: false
                        },
                        production_sf: {
                            editable: true,
                            required: false
                        },
                        production_sh: {
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
            template: kendo.template($("#crosswalkProductionHeaderAction").html())
        }],
        scrollable: true,
        sortable: false,
        reorderable: true,
        groupable: true,
        resizable: true,
        editable: true,
        columnMenu: true,
        columns: [{
            field: "production_td",
            title: "Production TradeDesk",
            editor: productionTdEditor
        }, {
            field: "production_sf",
            title: "Production StageFront",
            editor: productionSfEditor
        }, {
            field: "production_sh",
            title: "Production StubHub",
            editor: productionShEditor
        }, ],
    });
    /* event Search */
    $("#crosswalkProductionBtnSearch").click(function() {
        var eventSearchValue = $('#crosswalkProductionSearchBox').val();
        $("#crosswalkProductionGrid").data("kendoGrid").dataSource.filter({
            logic: "or",
            filters: [{
                field: "production_td",
                operator: "contains",
                value: eventSearchValue
            }, {
                field: "production_sf",
                operator: "contains",
                value: eventSearchValue
            }, {
                field: "production_td",
                operator: "contains",
                value: eventSearchValue
            }, ]
        });
    });
    $('#crosswalkProductionSearchBox').keypress(function(e) {
        if (e.which == 13) { //Enter key pressed
            $('#crosswalkProductionBtnSearch').click(); //Trigger search button click event
        }
    });
    //Clearing the filter
    $("#crosswalkProductionBtnReset").click(function() {
        $('#crosswalkProductionSearchBox').val("");
        $("#crosswalkProductionGrid").data("kendoGrid").dataSource.filter({});
    });
    /* calendar Search end*/
});