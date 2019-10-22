var token = $('input[name="_token"]').val();
var requestUrl = $("#requestUrl").val();
var crosswalkHistoryRequestUrl = "crosswalk_exchange_ledger";

function crosswalkIdEditor(container, options) {
    $("<input name='" + options.field + "'/>").appendTo(container).kendoNumericTextBox({
        min: 0,
        step: 1,
        decimals: 0
    });
    var tooltipElement = $("<span class='k-invalid-msg' data-for='" + options.field + "'></span>");
    tooltipElement.appendTo(container);
}

function submitDateEditor(container, options) {
    $("<input name='" + options.field + "'/>").appendTo(container).kendoNumericTextBox({
        min: 0,
        step: 1,
        decimals: 0
    });
    var tooltipElement = $("<span class='k-invalid-msg' data-for='" + options.field + "'></span>");
    tooltipElement.appendTo(container);
}

function submitTimeEditor(container, options) {
    $("<input name='" + options.field + "'/>").appendTo(container).kendoNumericTextBox({
        min: 0,
        step: 1,
        decimals: 0
    });
    var tooltipElement = $("<span class='k-invalid-msg' data-for='" + options.field + "'></span>");
    tooltipElement.appendTo(container);
}

function identityUserEditor(container, options) {
    $("<input name='" + options.field + "'/>").appendTo(container).kendoNumericTextBox({
        min: 0,
        step: 1,
        decimals: 0
    });
    var tooltipElement = $("<span class='k-invalid-msg' data-for='" + options.field + "'></span>");
    tooltipElement.appendTo(container);
}
$(document).ready(function() {
    $(function() {
        var grid = $("#crosswalkExchangeLedgerGrid").data("kendoGrid");
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
    var crosswalkExchangeLedgerGrid = jQuery("#crosswalkExchangeLedgerGrid").kendoGrid({
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
                    url: crosswalkHistoryRequestUrl + "/getCrosswalkExchangeLedger",
                    dataType: "json",
                    type: "POST"
                },
                update: {
                    data: {
                        _token: token
                    },
                    url: crosswalkHistoryRequestUrl + "/updateCrosswalkExchangeLedger",
                    dataType: "jsonp",
                    type: "POST"
                },
                destroy: {
                    data: {
                        _token: token
                    },
                    url: crosswalkHistoryRequestUrl + "/deleteCrosswalkExchangeLedger",
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
                    var crosswalkExchangeLedgerGrid = $("#crosswalkExchangeLedgerGrid").data("kendoGrid");
                    crosswalkExchangeLedgerGrid.dataSource.read();
                }
            },
            schema: {
                total: 'total',
                data: 'crosswalkExchangeLedger',
                model: {
                    id: 'ledger_id',
                    fields: {
                        crosswalk_id: {
                            type: "number"
                        },
                        data_old: {
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
                        data_new: {
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
            template: kendo.template($("#crosswalkExchangeLedgerHeaderAction").html())
        }],
        scrollable: true,
        sortable: false,
        reorderable: true,
        groupable: true,
        resizable: true,
        editable: false,
        columnMenu: true,
        columns: [{
            field: "crosswalk_id",
            title: "Crosswalk Id",
            editor: crosswalkIdEditor
        }, {
            field:"type_name",
            title:"Crosswalk Type"
        }, {
            field: "submit_date",
            title: "Submit Date"
        }, {
            field: "submit_time",
            title: "Submit Time"
        }, {
            field: "identity_user",
            title: "Identity User"
        }, {
            field: "data_old",
            title: "Data Old"
        }, {
            field: "data_new",
            title: "Data New"
        }, ],
    });
    /* event Search */
    $("#crosswalkExchangeLedgerBtnSearch").click(function() {
        var eventSearchValue = $('#crosswalkExchangeLedgerSearchBox').val();
        $("#crosswalkExchangeLedgerGrid").data("kendoGrid").dataSource.filter({
            logic: "or",
            filters: [{
                field: "data_old",
                operator: "contains",
                value: eventSearchValue
            }, {
                field: "data_new",
                operator: "contains",
                value: eventSearchValue
            }, ]
        });
    });
    $('#crosswalkExchangeLedgerSearchBox').keypress(function(e) {
        if (e.which == 13) { //Enter key pressed
            $('#crosswalkExchangeLedgerBtnSearch').click(); //Trigger search button click event
        }
    });
    //Clearing the filter
    $("#crosswalkExchangeLedgerBtnReset").click(function() {
        $('#crosswalkExchangeLedgerSearchBox').val("");
        $("#crosswalkExchangeLedgerGrid").data("kendoGrid").dataSource.filter({});
    });
    /* calendar Search end*/
});