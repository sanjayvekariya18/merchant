var constant = {
    REFRESH_TIMER_MINUTES: 60,
}
var token = $('input[name="_token"]').val();
var requestUrl = $("#requestUrl").val();
$(document).ready(function() {
    $("#tabstrip").kendoTabStrip().data("kendoTabStrip");

    function sectionEditor(container, options) {
        $("<input type='text' data-text-field='" + options.field + "' data-value-field='" + options.field + "' data-bind='value:" + options.field + "' data-format='" + options.format + "'/>").appendTo(container).kendoDatePicker({});
    }

    function rowEditor(container, options) {
        $("<input type='text' data-text-field='" + options.field + "' data-value-field='" + options.field + "' data-bind='value:" + options.field + "' data-format='" + options.format + "'/>").appendTo(container).kendoDatePicker({});
    }

    function seat_startEditor(container, options) {
        $("<input type='text' data-text-field='" + options.field + "' data-value-field='" + options.field + "' data-bind='value:" + options.field + "' data-format='" + options.format + "'/>").appendTo(container).kendoDatePicker({});
    }

    function seat_endEditor(container, options) {
        $("<input type='text' data-text-field='" + options.field + "' data-value-field='" + options.field + "' data-bind='value:" + options.field + "' data-format='" + options.format + "'/>").appendTo(container).kendoDatePicker({});
    }

    function descriptionEditor(container, options) {
        $("<input type='text' data-text-field='" + options.field + "' data-value-field='" + options.field + "' data-bind='value:" + options.field + "' data-format='" + options.format + "'/>").appendTo(container).kendoDatePicker({});
    }
    function dataSectionEditor(container, options) {
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
                    url: "crosswalk_exchange/getDataTDList",
                    type: "GET"
                }
            },
            pageSize: 5,
            serverPaging: true,
            serverFiltering: true
        },
        dataTextField: "data_td",
        dataValueField: "data_td",
        placeholder: "Select Section"
    });
}
    var TickersGrid = jQuery("#TickersGrid").kendoGrid({
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
                    url: requestUrl + "/getTickers",
                    dataType: "json",
                    type: "POST"
                },
                update: {
                    data: {
                        _token: token
                    },
                    url: requestUrl + "/updateTickers",
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
                    var TickersGridGrid = $("#TickersGrid").data("kendoGrid");
                    TickersGridGrid.dataSource.read();
                }
            },
            schema: {
                total: 'total',
                data: 'Tickers',
                model: {
                    id: 'id',
                },
            },
        },
        pageable: {
            refresh: true,
            pageSizes: true
        },
        toolbar: [{
            template: kendo.template($("#TickersSearch").html())
        }],
        columnMenu: true,
        scrollable: true,
        sortable: true,
        reorderable: true,
        groupable: false,
        resizable: true,
        editable: false,
        dataBound: function(e) {
            var filterCheck=e.sender.dataSource.filter();
            var sortingCheck=e.sender.dataSource.sort();
            if(sortingCheck != undefined && localStorage.getItem('resetSortValue') != 'undefined'){
                localStorage.setItem('resetSortValue','resetSortValue');
            } else {
                localStorage.setItem('resetSortValue',undefined);
            }
            var resetSortValue = localStorage.getItem('resetSortValue');
            var resetFilterValue = localStorage.getItem('resetFilterValue');
            if(resetSortValue == 'undefined' && filterCheck == undefined && resetFilterValue == null){
                tickersCountDown(constant.REFRESH_TIMER_MINUTES);
            }
            localStorage.clear();
            var tickerGrid = $("#TickersGrid").data("kendoGrid");
            var tickerData = tickerGrid.dataSource.data();
            var tickerDetailsLength = tickerData.length;
            for (var tickerDetails = 0; tickerDetails < tickerDetailsLength; tickerDetails++) {
                var row = tickerData[tickerDetails];
                if (tickerDetails == 0 || tickerDetails == 1 || tickerDetails == 2) {
                    tickerGrid.expandRow("tr[data-uid='" + row.uid + "']");
                }
            }
        },
        detailInit: initTickerDetail,
        columns: [{
            field: "production_id",
            title: "Production Id",
            hidden: true,
            width: 100,
        }, {
            field: "production_name",
            title: "Production Name",
            width: 120,
        }, {
            field: "venue_name",
            title: "Venue Name",
            width: 100,
        }, {
            field: "event_date",
            title: "Event Date",
            width: 80,
        }, {
            field: "event_time",
            title: "Event Time",
            width: 80,
        }, ],
    });

    function initTickerDetail(tickerDetails) {
        var production_id = tickerDetails.data.production_id;
        var section = tickerDetails.data.section;
        var row = tickerDetails.data.row;
        var seat_start = tickerDetails.data.seat_start;
        $("<div/>").appendTo(tickerDetails.detailCell).kendoGrid({
            dataSource: {
                pageSize: 100,
                serverFiltering: true,
                transport: {
                    read: {
                        data: {
                            _token: token
                        },
                        url: requestUrl + "/tickerDetailsList?production_id=" + production_id,
                        dataType: "json",
                        type: "POST"
                    }
                },
                schema: {
                    total: 'total',
                    data: 'ticker_details',
                    model: {
                        id: 'production_id',
                        fields: {
                            listing_id: { editable: false },
                            data_td: { editable: true, required: false },
                            data_sh: { editable: false },
                            list_date_time: { editable: false },
                            price: { editable: false },
                            row: { editable: false },
                            quantity: { editable: false },
                            seat_start: { editable: false },
                            seat_end: { editable: false },
                            broker_name: { editable: false },
                        },
                    },
                },
            },
            pageable: {
                refresh: true,
                pageSizes: [50, 100, 200]
            },
            selectable: 'row',
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
            scrollable: true,
            sortable: true,
            editable: true,
            columns: [{
                field: "listing_id",
                title: "Listing Id",
                filterable: false,
                width: 100,
            }, {
                sortable: { allowUnsort: false },
                field: "list_date_time",
                title: "List Date Time",
                filterable: false,
                width: 140,
            }, {
                field: "price",
                title: "Price",
                filterable: false,
                width: 100,
            }, {
                field: "data_sh",
                title: "Section (SH)",
                width: 120,
            }, {
                field: "data_td",
                title: "Section",
                template: "#=(data_td)?data_td:''#",
                editor: dataSectionEditor,
                width: 120,
            }, {
                field: "row",
                title: "Row",
                width: 70,
            }, {
                field: "quantity",
                title: "Quantity",
                filterable: false,
                width: 70,
            }, {
                field: "seat_start",
                title: "Seat Start",
                filterable: false,
                width: 80,
            }, {
                field: "seat_end",
                title: "Seat End",
                filterable: false,
                width: 80,
            }, {
                field: "broker_name",
                title: "Broker Name",
                filterable: false,
                width: 90,
            }],
            save: function(data) {
                if (data.values.data_td) {
                    if (data.values.data_td.data_td) {
                        data_td = data.values.data_td.data_td;
                    } else {
                        data_td = data.values.data_td;
                    }
                } else {
                    data_td = data.model.data_td;
                }
                if (data.values.data_sh) {
                    data_sh = data.values.data_sh;
                } else {
                    data_sh = data.model.data_sh;
                }
                jQuery.ajax({
                type: 'POST',
                data: {
                    Exchange_id: data.model.Exchange_id,
                    data_td: data_td,
                    data_sh: data_sh,
                    _token: token
                },
                url: "crosswalk_exchange/updateCrosswalkExchange",
                success: function(tickerDetailsUpdate) {
                        var TickersGrid = $("#TickersGrid").data("kendoGrid");
                        TickersGrid.dataSource.read();
                }
            });
            }
        });
    }
    /* event Search */
    $("#TickersBtnSearch").click(function() {
        localStorage.clear();
        var eventSearchValue = $('#TickersSearchBox').val();
        $("#TickersGrid").data("kendoGrid").dataSource.filter({
            logic: "or",
            filters: [{
                field: "production_name",
                operator: "contains",
                value: eventSearchValue
            }, {
                field: "venue_name",
                operator: "contains",
                value: eventSearchValue
            }, {
                field: "event_date",
                operator: "contains",
                value: eventSearchValue
            }, ]
        });
    });
    $('#TickersSearchBox').keypress(function(e) {
        if (e.which == 13) { //Enter key pressed
            $('#TickersBtnSearch').click(); //Trigger search button click event
        }
    });
    //Clearing the filter
    $("#TickersBtnReset").click(function() {
        $('#TickersSearchBox').val("");
        localStorage.setItem('resetFilterValue','reset');
        $("#TickersGrid").data("kendoGrid").dataSource.filter({});
    });
    document.getElementById("tickersRefreshButton").value="Refresh : 60";
    function tickersCountDown(refreshMinutes) {
        function tickersTimer() {
            document.getElementById("tickersRefreshButton").value="Refresh : " + refreshMinutes;
            refreshMinutes--;
            if (refreshMinutes == 0) {
                localStorage.setItem('resetSortValue','undefined');
                $('#TickersGrid').data('kendoGrid').dataSource.read();
            } else {
                var tickersTimeoutId = setTimeout(tickersTimer, 1000);
                $("#tickersTimeoutId").val(tickersTimeoutId);
            }
        }
        tickersTimer();
    }
    $("#tickersRefreshButton").on("click", function(e) {
        e.preventDefault();
        var refreshValue = this.value;
        var regex = /[0-9]/g;
        var refreshFound = refreshValue.match(regex);
        if(refreshFound){
            var tickersTimeoutId = $("#tickersTimeoutId").val();
            clearTimeout(tickersTimeoutId);
            document.getElementById("tickersRefreshButton").value="Refresh: paused";
        } else {
            tickersCountDown(constant.REFRESH_TIMER_MINUTES);
        }
    });
    /* calendar Search end*/
    var TickersGrid = jQuery("#TickersHistoryGrid").kendoGrid({
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
                    url: requestUrl + "/getTickersHistory",
                    dataType: "json",
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
                    var TickersGridGrid = $("#TickersHistoryGrid").data("kendoGrid");
                    TickersGridGrid.dataSource.read();
                }
            },
            schema: {
                total: 'total',
                data: 'Tickers_History',
                model: {
                    id: 'id',
                },
            },
        },
        pageable: {
            refresh: true,
            pageSizes: true
        },
        toolbar: [{
            template: kendo.template($("#TickersHistorySearch").html())
        }],
        columnMenu: true,
        scrollable: true,
        sortable: true,
        reorderable: true,
        groupable: false,
        resizable: true,
        dataBound: function() {
            var tickerHistoryGrid = $("#TickersHistoryGrid").data("kendoGrid");
            var tickerHistoryData = tickerHistoryGrid.dataSource.data();
            var tickerHistoryDetailsLength = tickerHistoryData.length;
            for (var tickerHistoryDetails = 0; tickerHistoryDetails < tickerHistoryDetailsLength; tickerHistoryDetails++) {
                var row = tickerHistoryData[tickerHistoryDetails];
                if (tickerHistoryDetails == 0 || tickerHistoryDetails == 1 || tickerHistoryDetails == 2) {
                    tickerHistoryGrid.expandRow("tr[data-uid='" + row.uid + "']");
                }
            }
        },
        detailInit: initTickerHistoryDetail,
        columns: [{
            field: "production_id",
            title: "Production Id",
            hidden: true,
            width: 100,
        }, {
            field: "production_name",
            title: "Production Name",
            width: 120,
        }, {
            field: "venue_name",
            title: "Venue Name",
            width: 100,
        }, {
            field: "event_date",
            title: "Event Date",
            width: 80,
        }, {
            field: "event_time",
            title: "Event Time",
            width: 80,
        }, ],
    });

    function initTickerHistoryDetail(tickerHistoryDetails) {
        var production_id = tickerHistoryDetails.data.production_id;
        var section = tickerHistoryDetails.data.section;
        var row = tickerHistoryDetails.data.row;
        var seat_start = tickerHistoryDetails.data.seat_start;
        $("<div/>").appendTo(tickerHistoryDetails.detailCell).kendoGrid({
            dataSource: {
                pageSize: 100,
                serverFiltering: true,
                transport: {
                    read: {
                        data: {
                            _token: token
                        },
                        url: requestUrl + "/tickerHistoryDetailsList?production_id=" + production_id,
                        dataType: "json",
                        type: "POST"
                    }
                },
                schema: {
                    total: 'total',
                    data: 'ticker_history_details',
                    model: {
                        id: 'production_id'
                    },
                },
            },
            pageable: {
                refresh: true,
                pageSizes: [50, 100, 200]
            },
            selectable: 'row',
            scrollable: true,
            sortable: true,
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
            columns: [{
                field: "listing_id",
                title: "Listing Id",
                filterable: false,
                width: 100,
            }, {
                sortable: { allowUnsort: false },
                field: "list_date_time",
                title: "List Date Time",
                filterable: false,
                width: 140,
            }, {
                field: "price",
                title: "Price",
                filterable: false,
                width: 100,
            }, {
                field: "section",
                title: "Section",
                width: 120,
            }, {
                field: "row",
                title: "Row",
                width: 70,
            }, {
                field: "quantity",
                title: "Quantity",
                filterable: false,
                width: 70,
            }, {
                field: "seat_start",
                title: "Seat Start",
                filterable: false,
                width: 80,
            }, {
                field: "seat_end",
                title: "Seat End",
                filterable: false,
                width: 80,
            }, {
                field: "broker_name",
                title: "Broker Name",
                filterable: false,
                width: 90,
            }]
        });
    }
    /* event Search */
    $("#TickersHistoryBtnSearch").click(function() {
        var eventSearchValue = $('#TickersHistorySearchBox').val();
        $("#TickersHistoryGrid").data("kendoGrid").dataSource.filter({
            logic: "or",
            filters: [{
                field: "production_name",
                operator: "contains",
                value: eventSearchValue
            }, {
                field: "venue_name",
                operator: "contains",
                value: eventSearchValue
            }, {
                field: "event_date",
                operator: "contains",
                value: eventSearchValue
            }, ]
        });
    });
    $('#TickersHistorySearchBox').keypress(function(e) {
        if (e.which == 13) { //Enter key pressed
            $('#TickersHistoryBtnSearch').click(); //Trigger search button click event
        }
    });
    //Clearing the filter
    $("#TickersHistoryBtnReset").click(function() {
        $('#TickersHistorySearchBox').val("");
        $("#TickersHistoryGrid").data("kendoGrid").dataSource.filter({});
    });
});