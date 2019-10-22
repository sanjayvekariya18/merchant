var token = $('input[name="_token"]').val();
var requestUrl = $("#requestUrl").val();
$(document).ready(function() {
    $("#tabstrip").kendoTabStrip().data("kendoTabStrip");
    var InventoriesGrid = jQuery("#InventoriesGrid").kendoGrid({
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
                    url: requestUrl + "/getInventories",
                    dataType: "json",
                    type: "POST"
                },
                update: {
                    data: {
                        _token: token
                    },
                    url: requestUrl + "/updateInventories",
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
                    var InventoriesGridGrid = $("#InventoriesGrid").data("kendoGrid");
                    InventoriesGridGrid.dataSource.read();
                }
            },
            schema: {
                total: 'total',
                data: 'Inventories',
                model: {
                    id: 'production_id',
                },
            },
        },
        pageable: {
            refresh: true,
            pageSizes: true
        },
        toolbar: [{
            template: kendo.template($("#InventoriesSearch").html())
        }],
        detailInit: initInventoryDetail,
        dataBound: function() {
            var grid = $("#InventoriesGrid").data("kendoGrid");
            var data = grid.dataSource.data();
            var inventoryDetailsLenght = data.length;
            for (var inventoryDetails = 0; inventoryDetails < inventoryDetailsLenght; inventoryDetails++) {
                var row = data[inventoryDetails];
                if (inventoryDetails == 0 || inventoryDetails == 1 || inventoryDetails == 2) {
                    grid.expandRow("tr[data-uid='" + row.uid + "']");
                }
            }
        },
        scrollable: true,
        sortable: true,
        reorderable: true,
        groupable: false,
        resizable: true,
        //editable: true,
        columns: [{
            field: "production_id",
            title: "Production Id",
            width: 100,
        }, {
            field: "production_name",
            title: "Production Name",
            width: 120,
        }, {
            field: "event_date",
            title: "Event Date",
            width: 80,
        }, {
            field: "section",
            title: "Section",
            width: 80,
        }, {
            field: "row",
            title: "Row",
            width: 80,
        }, {
            field: "seat_start",
            title: "Seat Start",
            width: 100,
        }, ],
    });
    /* event Search */
    $("#InventoriesBtnSearch").click(function() {
        var eventSearchValue = $('#InventoriesSearchBox').val();
        $("#InventoriesGrid").data("kendoGrid").dataSource.filter({
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
                field: "broker_name",
                operator: "contains",
                value: eventSearchValue
            }, {
                field: "broker_id",
                operator: "contains",
                value: eventSearchValue
            }, {
                field: "event_date",
                operator: "contains",
                value: eventSearchValue
            }, {
                field: "section",
                operator: "contains",
                value: eventSearchValue
            }, {
                field: "row",
                operator: "contains",
                value: eventSearchValue
            }, {
                field: "seat_start",
                operator: "contains",
                value: eventSearchValue
            }]
        });
    });
    $('#InventoriesSearchBox').keypress(function(e) {
        if (e.which == 13) { //Enter key pressed
            $('#InventoriesBtnSearch').click(); //Trigger search button click event
        }
    });
    //Clearing the filter
    $("#InventoriesBtnReset").click(function() {
        $('#InventoriesSearchBox').val("");
        $("#InventoriesGrid").data("kendoGrid").dataSource.filter({});
    });
    function initInventoryDetail(inventoryDetails){
        var production_id = inventoryDetails.data.production_id;
        var section = inventoryDetails.data.section;
        var row = inventoryDetails.data.row;
        var seat_start = inventoryDetails.data.seat_start;
        $("<div/>").appendTo(inventoryDetails.detailCell).kendoGrid({
            dataSource: {
                pageSize: 5,
                transport: {
                    read: {
                        data: {
                            _token: token
                        },
                        url: requestUrl + "/inventoryDetailsList?production_id=" + production_id  + "&section=" + section + "&row=" + row + "&seat_start=" + seat_start,
                        dataType: "json",
                        type: "POST"
                    }
                },
                schema: {
                    total: 'total',
                    data: 'inventory_details',
                    model: {
                        id: 'production_id'
                    },
                },
            },
            pageable: {
                refresh: true,
                pageSizes: true
            },
            selectable: 'row',
            scrollable: true,
            sortable: true,
            columns: [ {
                    field: "list_date",
                    title: "List Date",
                    width: 100,
                }, {
                    field: "list_time",
                    title: "List Time",
                    width: 100,
                }, {
                    field: "listing_id",
                    title: "Listing Id",
                    width: 100,
                }, {
                    field: "broker_name",
                    title: "Broker Name",
                    width: 100,
                }, {
                    field: "price",
                    title: "Price",
                    width: 100,
                }, {
                    field: "seatDetails",
                    title: "Seat Details",
                    width: 100,
                }, {
                    field: "quantity",
                    title: "Quantity",
                    width: 120,
                }]
        });
    }
    var CompetitorsGrid = jQuery("#CompetitorsGrid").kendoGrid({
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
                    url: requestUrl + "/getCompetitors",
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
                    var InventoriesGridGrid = $("#CompetitorsGrid").data("kendoGrid");
                    InventoriesGridGrid.dataSource.read();
                }
            },
            schema: {
                total: 'total',
                data: 'Competitors',
                model: {
                    id: 'production_id',
                },
            },
        },
        pageable: {
            refresh: true,
            pageSizes: true
        },
        toolbar: [{
            template: kendo.template($("#CompetitorsSearch").html())
        }],
        detailInit: initCompetitorsDetail,
        dataBound: function() {
            var grid = $("#CompetitorsGrid").data("kendoGrid");
            var data = grid.dataSource.data();
            var competitorsDetailsLenght = data.length;
            for (var competitorsDetails = 0; competitorsDetails < competitorsDetailsLenght; competitorsDetails++) {
                var row = data[competitorsDetails];
                if (competitorsDetails == 0 || competitorsDetails == 1 || competitorsDetails == 2) {
                    grid.expandRow("tr[data-uid='" + row.uid + "']");
                }
            }
        },
        scrollable: true,
        sortable: true,
        reorderable: true,
        groupable: false,
        resizable: true,
        columns: [{
            field: "production_id",
            title: "Production Id",
            width: 100,
        }, {
            field: "production_name",
            title: "Production Name",
            width: 120,
        }, {
            field: "event_date",
            title: "Event Date",
            width: 80,
        }, {
            field: "section",
            title: "Section",
            width: 80,
        }, {
            field: "row",
            title: "Row",
            width: 80,
        }, {
            field: "seat_start",
            title: "Seat Start",
            width: 100,
        }, ],
    });
    /* event Search */
    $("#CompetitorsBtnSearch").click(function() {
        var eventSearchValue = $('#CompetitorsSearchBox').val();
        $("#CompetitorsGrid").data("kendoGrid").dataSource.filter({
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
                field: "broker_name",
                operator: "contains",
                value: eventSearchValue
            }, {
                 field: "broker_id",
                operator: "contains",
                value: eventSearchValue
            }, {
                field: "event_date",
                operator: "contains",
                value: eventSearchValue
            }, {
                field: "section",
                operator: "contains",
                value: eventSearchValue
            }, {
                field: "row",
                operator: "contains",
                value: eventSearchValue
            }, {
                field: "seat_start",
                operator: "contains",
                value: eventSearchValue
            }]
        });
    });
    $('#CompetitorsSearchBox').keypress(function(e) {
        if (e.which == 13) { //Enter key pressed
            $('#CompetitorsBtnSearch').click(); //Trigger search button click event
        }
    });
    //Clearing the filter
    $("#CompetitorsBtnReset").click(function() {
        $('#CompetitorsSearchBox').val("");
        $("#CompetitorsGrid").data("kendoGrid").dataSource.filter({});
    });
    function initCompetitorsDetail(competitorsDetails){
        var production_id = competitorsDetails.data.production_id;
        var section = competitorsDetails.data.section;
        var row = competitorsDetails.data.row;
        var seat_start = competitorsDetails.data.seat_start;
        $("<div/>").appendTo(competitorsDetails.detailCell).kendoGrid({
            dataSource: {
                pageSize: 10,
                transport: {
                    read: {
                        data: {
                            _token: token
                        },
                        url: requestUrl + "/competitorsDetailsList?production_id=" + production_id + "&section=" + section + "&row=" + row + "&seat_start=" + seat_start,
                        dataType: "json",
                        type: "POST"
                    }
                },
                schema: {
                    total: 'total',
                    data: 'competitors_details',
                    model: {
                        id: 'production_id'
                    },
                },
            },
            pageable: {
                refresh: true,
                pageSizes: true
            },
            selectable: 'row',
            scrollable: true,
            sortable: true,
            columns: [ {
                    field: "list_date",
                    title: "List Date",
                    width: 100,
                }, {
                    field: "list_time",
                    title: "List Time",
                    width: 100,
                }, {
                    field: "listing_id",
                    title: "Listing Id",
                    width: 100,
                }, {
                    field: "broker_name",
                    title: "Broker Name",
                    width: 100,
                }, {
                    field: "price",
                    title: "Price",
                    width: 100,
                }, {
                    field: "seatDetails",
                    title: "Seat Details",
                    width: 100,
                }, {
                    field: "quantity",
                    title: "Quantity",
                    width: 120,
                }, ]
        });
    }
    var CompetitorsHistoryGrid = jQuery("#CompetitorsHistoryGrid").kendoGrid({
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
                    url: requestUrl + "/getCompetitorsHistory",
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
                    var InventoriesGridGrid = $("#CompetitorsHistoryGrid").data("kendoGrid");
                    InventoriesGridGrid.dataSource.read();
                }
            },
            schema: {
                total: 'total',
                data: 'CompetitorsHistory',
                model: {
                    id: 'production_id',
                },
            },
        },
        pageable: {
            refresh: true,
            pageSizes: true
        },
        toolbar: [{
            template: kendo.template($("#CompetitorsHistorySearch").html())
        }],
        detailInit: initCompetitorsHistoryDetail,
        scrollable: true,
        sortable: true,
        reorderable: true,
        groupable: false,
        resizable: true,
        dataBound: function() {
            var grid = $("#CompetitorsHistoryGrid").data("kendoGrid");
            var data = grid.dataSource.data();
            var competitorsHistoryDetailsLength = data.length;
            for (var competitorsHistory = 0; competitorsHistory < competitorsHistoryDetailsLength; competitorsHistory++) {
                var row = data[competitorsHistory];
                if (competitorsHistory == 0 || competitorsHistory == 1 || competitorsHistory == 2) {
                    grid.expandRow("tr[data-uid='" + row.uid + "']");
                }
            }
        },
        columns: [{
            field: "production_id",
            title: "Production Id",
            width: 100,
        }, {
            field: "production_name",
            title: "Production Name",
            width: 120,
        }, {
            field: "event_date",
            title: "Event Date",
            width: 80,
        }, {
            field: "section",
            title: "Section",
            width: 80,
        }, {
            field: "row",
            title: "Row",
            width: 80,
        }, {
            field: "seat_start",
            title: "Seat Start",
            width: 100,
        }, ],
    });
    function initCompetitorsHistoryDetail(competitorsHistoryDetails) {
        var production_id = competitorsHistoryDetails.data.production_id;
        var section = competitorsHistoryDetails.data.section;
        var row = competitorsHistoryDetails.data.row;
        var seat_start = competitorsHistoryDetails.data.seat_start;
        $("<div/>").appendTo(competitorsHistoryDetails.detailCell).kendoGrid({
            dataSource: {
                pageSize: 10,
                transport: {
                    read: {
                        data: {
                            _token: token
                        },
                        url: requestUrl + "/competitorsHistoryDetailsList?production_id=" + production_id + "&section=" + section + "&row=" + row + "&seat_start=" + seat_start,
                        dataType: "json",
                        type: "POST"
                    }
                },
                schema: {
                    total: 'total',
                    data: 'competitors_history_details',
                    model: {
                        id: 'production_id'
                    },
                },
            },
            pageable: {
                refresh: true,
                pageSizes: true
            },
            selectable: 'row',
            scrollable: true,
            sortable: true,
            columns: [{
                field: "list_date",
                title: "List Date",
                width: 100,
            }, {
                field: "list_time",
                title: "List Time",
                width: 100,
            }, {
                field: "listing_id",
                title: "Listing Id",
                width: 100,
            }, {
                field: "broker_name",
                title: "Broker Name",
                width: 100,
            }, {
                field: "price",
                title: "Price",
                width: 100,
            }, {
                field: "seatDetails",
                title: "Seat Details",
                width: 100,
            }, {
                field: "quantity",
                title: "Quantity",
                width: 120,
            }]
        });
    }
    /* event Search */
    $("#CompetitorsHistoryBtnSearch").click(function() {
        var eventSearchValue = $('#CompetitorsHistorySearchBox').val();
        $("#CompetitorsHistoryGrid").data("kendoGrid").dataSource.filter({
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
                field: "broker_name",
                operator: "contains",
                value: eventSearchValue
            }, {
                field: "broker_id",
                operator: "contains",
                value: eventSearchValue
            }, {
                field: "event_date",
                operator: "contains",
                value: eventSearchValue
            }, {
                field: "section",
                operator: "contains",
                value: eventSearchValue
            }, {
                field: "row",
                operator: "contains",
                value: eventSearchValue
            }, {
                field: "seat_start",
                operator: "contains",
                value: eventSearchValue
            }]
        });
    });
    $('#CompetitorsHistorySearchBox').keypress(function(e) {
        if (e.which == 13) { //Enter key pressed
            $('#CompetitorsHistoryBtnSearch').click(); //Trigger search button click event
        }
    });
    //Clearing the filter
    $("#CompetitorsHistoryBtnReset").click(function() {
        $('#CompetitorsHistorySearchBox').val("");
        $("#CompetitorsHistoryGrid").data("kendoGrid").dataSource.filter({});
    });
    var InventoriesHistoryGrid = jQuery("#InventoriesHistoryGrid").kendoGrid({
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
                    url: requestUrl + "/getInventoriesHistory",
                    dataType: "json",
                    type: "POST"
                },
                update: {
                    data: {
                        _token: token
                    },
                    url: requestUrl + "/updateInventoriesHistory",
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
                    var InventoriesHistoryGridGrid = $("#InventoriesHistoryGrid").data("kendoGrid");
                    InventoriesHistoryGridGrid.dataSource.read();
                }
            },
            schema: {
                total: 'total',
                data: 'InventoriesHistory',
                model: {
                    id: 'production_id',
                },
            },
        },
        pageable: {
            refresh: true,
            pageSizes: true
        },
        toolbar: [{
            template: kendo.template($("#InventoriesHistorySearch").html())
        }],
        dataBound: function() {
            var grid = $("#InventoriesHistoryGrid").data("kendoGrid");
            var data = grid.dataSource.data();
            var inventoriesHistoryDetailsLength = data.length;
            for (var inventoriesHistory = 0; inventoriesHistory < inventoriesHistoryDetailsLength; inventoriesHistory++) {
                var row = data[inventoriesHistory];
                if (inventoriesHistory == 0 || inventoriesHistory == 1 || inventoriesHistory == 2) {
                    grid.expandRow("tr[data-uid='" + row.uid + "']");
                }
            }
        },
        detailInit: initInventoryHistoryDetail,
        scrollable: true,
        sortable: true,
        reorderable: true,
        groupable: false,
        resizable: true,
        columns: [{
            field: "production_id",
            title: "Production Id",
            width: 100,
        }, {
            field: "production_name",
            title: "Production Name",
            width: 120,
        }, {
            field: "event_date",
            title: "Event Date",
            width: 80,
        }, {
            field: "section",
            title: "Section",
            width: 80,
        }, {
            field: "row",
            title: "Row",
            width: 80,
        }, {
            field: "seat_start",
            title: "Seat Start",
            width: 100,
        }, ],
    });
    function initInventoryHistoryDetail(inventoriesHistoryDetails) {
        var production_id = inventoriesHistoryDetails.data.production_id;
        var section = inventoriesHistoryDetails.data.section;
        var row = inventoriesHistoryDetails.data.row;
        var seat_start = inventoriesHistoryDetails.data.seat_start;
        $("<div/>").appendTo(inventoriesHistoryDetails.detailCell).kendoGrid({
            dataSource: {
                pageSize: 10,
                transport: {
                    read: {
                        data: {
                            _token: token
                        },
                        url: requestUrl + "/inventoriesHistoryDetailsList?production_id=" + production_id + "&section=" + section + "&row=" + row + "&seat_start=" + seat_start,
                        dataType: "json",
                        type: "POST"
                    }
                },
                schema: {
                    total: 'total',
                    data: 'inventories_history_details',
                    model: {
                        id: 'production_id'
                    },
                },
            },
            pageable: {
                refresh: true,
                pageSizes: true
            },
            selectable: 'row',
            scrollable: true,
            sortable: true,
            columns: [{
                field: "list_date",
                title: "List Date",
                width: 100,
            }, {
                field: "list_time",
                title: "List Time",
                width: 100,
            }, {
                field: "listing_id",
                title: "Listing Id",
                width: 100,
            }, {
                field: "broker_name",
                title: "Broker Name",
                width: 100,
            }, {
                field: "price",
                title: "Price",
                width: 100,
            }, {
                field: "seatDetails",
                title: "Seat Details",
                width: 100,
            }, {
                field: "quantity",
                title: "Quantity",
                width: 120,
            }]
        });
    }
    /* event Search */
    $("#InventoriesHistoryBtnSearch").click(function() {
        var eventSearchValue = $('#InventoriesHistorySearchBox').val();
        $("#InventoriesHistoryGrid").data("kendoGrid").dataSource.filter({
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
                field: "broker_name",
                operator: "contains",
                value: eventSearchValue
            }, {
                field: "broker_id",
                operator: "contains",
                value: eventSearchValue
            }, {
                field: "event_date",
                operator: "contains",
                value: eventSearchValue
            }, {
                field: "section",
                operator: "contains",
                value: eventSearchValue
            }, {
                field: "row",
                operator: "contains",
                value: eventSearchValue
            }, {
                field: "seat_start",
                operator: "contains",
                value: eventSearchValue
            }]
        });
    });
    $('#InventoriesHistorySearchBox').keypress(function(e) {
        if (e.which == 13) { //Enter key pressed
            $('#InventoriesHistoryBtnSearch').click(); //Trigger search button click event
        }
    });
    //Clearing the filter
    $("#InventoriesHistoryBtnReset").click(function() {
        $('#InventoriesHistorySearchBox').val("");
        $("#InventoriesHistoryGrid").data("kendoGrid").dataSource.filter({});
    });
});