var token = $('input[name="_token"]').val();
var requestUrl = $("#requestUrl").val();
$("#tabstrip").kendoTabStrip().data("kendoTabStrip");

function venueIdEditor(container, options) {
    $("<input name='" + options.field + "' required type='text' data-text-field='venue_name' data-value-field='venue_id' data-bind='value:" + options.field + "' />").appendTo(container).kendoDropDownList({
        autoBind: true,
        dataTextField: "venue_name",
        dataValueField: "venue_id",
        dataSource: {
            transport: {
                read: {
                    dataType: 'json',
                    url: requestUrl + "/getVenueIdList",
                    type: "GET"
                }
            }
        }
    });
}

function dataTDEditor(container, options) {
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
                    url: requestUrl + "/getDataTDList",
                    type: "GET"
                }
            },
            pageSize: 5,
            serverPaging: true,
            serverFiltering: true
        },
        dataTextField: "data_td",
        dataValueField: "Exchange_id",
        placeholder: "Select TD"
    });
}

function dataSHEditor(container, options) {
    $('<input name="' + options.field + '" required data-bind="value:' + options.field + '"/>').appendTo(container).kendoAutoComplete({
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
                    url: requestUrl + "/getDataSHList",
                    type: "GET"
                }
            },
            pageSize: 5,
            serverPaging: true,
            serverFiltering: true
        },
        dataTextField: "data_sh",
        dataValueField: "data_sh",
        placeholder: "Select SH"
    });
}
/*__input_action_function__*/
$(document).ready(function() {
    if (!Number.prototype.toLowerCase) {
        Number.prototype.toLowerCase = function() {
            return this.toString();
        }
    }
    $(function() {
        var grid = $("#crosswalkExchangeGrid").data("kendoGrid");
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
    var crosswalkExchangeGrid = jQuery("#crosswalkExchangeGrid").kendoGrid({
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
                    url: requestUrl + "/getCrosswalkExchange?data_td=" + 'showTDblank',
                    dataType: "json",
                    type: "POST"
                },
                update: {
                    data: {
                        _token: token
                    },
                    url: requestUrl + "/updateCrosswalkExchange",
                    dataType: "jsonp",
                    type: "POST"
                },
                destroy: {
                    data: {
                        _token: token
                    },
                    url: requestUrl + "/deleteCrosswalkExchange",
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
                if (requestResponse.response.type === "update") {
                    response = requestResponse.response.action;
                        if(response.localeCompare("success") == 0){
                            message = requestResponse.response.message;
                        }else if (response.localeCompare("error") == 0){
                            message = requestResponse.response.message;
                        }
                        toastr.options = {
                            "closeButton": true,
                            "positionClass": "toast-top-right",
                            "showDuration": "1000",
                            "hideDuration": "1000",
                            "timeOut": "5000",
                            "extendedTimeOut": "1000",
                            "showEasing": "swing",
                            "hideEasing": "swing",
                            "showMethod": "show"
                        };
                    var $toast = toastr[response]("", message);
                    var crosswalkExchangeGrid = $("#crosswalkExchangeGrid").data("kendoGrid");
                    crosswalkExchangeGrid.dataSource.read();
                    var crosswalkExchangeLedgerGrid = $("#crosswalkExchangeLedgerGrid").data("kendoGrid");
                    crosswalkExchangeLedgerGrid.dataSource.read();
                }
            },
            schema: {
                total: 'total',
                data: 'crosswalkExchange',
                model: {
                    id: 'Exchange_id',
                    fields: {
                        data_sh: { editable: false },
                        data_td: { editable: true, required: false },
    
                    },
                },
            },
        },
        noRecords: true,
        messages: {
            noRecords: "No event Found"
        },
        pageable: {
            refresh: true,
            pageSizes: [50, 100, 200]
        },
        toolbar: [{
            template: kendo.template($("#crosswalkExchangeHeaderAction").html())
        }],
        scrollable: true,
        filterable: {
            extra: false,
            operators: {
                string: {
                    eq: "Is equal to",
                    startswith: "Starts with",
                    neq: "Is not equal to"
                }
            }
        },
        sortable: false,
        reorderable: true,
        groupable: true,
        resizable: true,
        editable: true,
        columnMenu: true,
        columns: [ /*__module_select_checkbox__*/ {
            field: "venue_name",
            title: "Venue",
            template: "#:venue_name#",
            editor: venueIdEditor,
            filterable: false
        }, {
            field: "data_sh",
            title: "StubHub",
            template: "#=(data_sh)?data_sh:''#",
            filterable: false,
            editor: dataSHEditor
        }, {
            field: "data_td",
            title: "TradeDesk",
            template: "#=(data_td)?data_td:''#",
            editor: dataTDEditor,
            filterable: false
        }, ],
    });

    function dataSHFilter(element) {
        element.kendoAutoComplete({
            dataSource: {
                transport: {
                    read: {
                        dataType: "json",
                        url: requestUrl + "/getDataSHList",
                        type: "GET"
                    }
                }
            },
            dataTextField: "data_sh",
            dataValueField: "data_sh",
            optionLabel: {
                data_sh: "Select data sh",
            }
        });
    }

    function dataTDFilter(element) {
        element.kendoAutoComplete({
            dataSource: {
                transport: {
                    read: {
                        dataType: "json",
                        url: requestUrl + "/getDataTDList",
                        type: "GET"
                    }
                }
            },
            dataTextField: "data_td",
            dataValueField: "data_td",
            optionLabel: {
                data_td: "Select data td",
            }
        });
    }
    $("#pushButton").on("click", function(e) {
        e.preventDefault();
        var pushButton = this.value;
        if (pushButton == "Show TD blank") {
            var data_td = 'hideTDblank';
            document.getElementById("pushButton").value="Hide TD blank";
        }else if (pushButton == "Hide TD blank") {
            var data_td = 'hideSHblank';
            document.getElementById("pushButton").value="Hide SH blank";
        }else if (pushButton == "Hide SH blank") {
            var data_td = 'ShowAll';
            document.getElementById("pushButton").value="Show All";
        } else if (pushButton == "Show All") {
            var data_td = 'showSHblank';
            document.getElementById("pushButton").value="Show SH blank";
        } else if (pushButton == "Show SH blank") {
            var data_td = 'showTDblank';
            document.getElementById("pushButton").value="Show TD blank";
        }


        var dataSource = new kendo.data.DataSource({
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
                    url: requestUrl + "/getCrosswalkExchange?data_td=" + data_td,
                    dataType: "json",
                    type: "POST"
                },
                update: {
                    data: {
                        _token: token
                    },
                    url: requestUrl + "/updateCrosswalkExchange",
                    dataType: "jsonp",
                    type: "POST"
                },
                destroy: {
                    data: {
                        _token: token
                    },
                    url: requestUrl + "/deleteCrosswalkExchange",
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
                if (requestResponse.response.type === "update") {
                    response = requestResponse.response.action;
                        if(response.localeCompare("success") == 0){
                            message = requestResponse.response.message;
                        }else if (response.localeCompare("error") == 0){
                            message = requestResponse.response.message;
                        }
                        toastr.options = {
                            "closeButton": true,
                            "positionClass": "toast-top-right",
                            "showDuration": "1000",
                            "hideDuration": "1000",
                            "timeOut": "5000",
                            "extendedTimeOut": "1000",
                            "showEasing": "swing",
                            "hideEasing": "swing",
                            "showMethod": "show"
                        };
                    var $toast = toastr[response]("", message);
                    var crosswalkExchangeGrid = $("#crosswalkExchangeGrid").data("kendoGrid");
                    crosswalkExchangeGrid.dataSource.read();
                }
            },
            schema: {
                total: 'total',
                data: 'crosswalkExchange',
                model: {
                    id: 'Exchange_id',
                    fields: {
                       data_sh: { editable: false },
                       data_td: { editable: true, required: false },
                    },
                },
            },
        });
        var grid = $("#crosswalkExchangeGrid").data("kendoGrid");
        grid.setDataSource(dataSource);
    });
    /*__module_select_checkbox_function__*/
    /* event Search */
    $("#crosswalkExchangeBtnSearch").click(function() {
        var eventSearchValue = $('#crosswalkExchangeSearchBox').val();
        $("#crosswalkExchangeGrid").data("kendoGrid").dataSource.filter({
            logic: "or",
            filters: [{
                field: "venue_id",
                operator: "contains",
                value: eventSearchValue
            }, {
                field: "data_sh",
                operator: "contains",
                value: eventSearchValue
            }, {
                field: "data_td",
                operator: "contains",
                value: eventSearchValue
            }, ]
        });
    });
    $('#crosswalkExchangeSearchBox').keypress(function(e) {
        if (e.which == 13) { //Enter key pressed
            $('#crosswalkExchangeBtnSearch').click(); //Trigger search button click event
        }
    });
    //Clearing the filter
    $("#crosswalkExchangeBtnReset").click(function() {
        $('#crosswalkExchangeSearchBox').val("");
        $("#crosswalkExchangeGrid").data("kendoGrid").dataSource.filter({});
    });
    /* calendar Search end*/
});
/*__kendo_batch_function__*/