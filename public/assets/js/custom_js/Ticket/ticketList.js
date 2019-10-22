var token = $('input[name="_token"]').val();
var requestUrl = $("#requestUrl").val();
var constant = {
    REFRESH_TRANSACTIONS_TIMER_MINUTES: 60,
}
$(document).ready(function() {
    $("#tabstrip").kendoTabStrip().data("kendoTabStrip");
    var ticketPurchasedEventGrid = jQuery("#ticketListingDetails").kendoGrid({
        dataSource: {
            serverPaging: true,
            serverFiltering: true,
            serverSorting: true,
            pageSize: 10,
            autoSync: true,
            transport: {
                read: {
                    data: {
                        _token: token
                    },
                    url: requestUrl + "/ticketListingEvent",
                    dataType: "json",
                    type: "POST"
                }
            },
            schema: {
                total: 'total',
                data: 'ticket_listing_events',
                model: {
                    id: 'production_id',
                    fields: {
                        production_id: {
                            validation: {
                                required: true
                            }
                        }
                    }
                },
            },
        },
        pageable: {
            refresh: true,
            pageSizes: true
        },
        toolbar: [{
            template: kendo.template($("#TransactionSearch").html())
        }],
        scrollable: true,
        sortable: true,
        reorderable: true,
        columnMenu: true,
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
        serverFiltering: true,
        detailTemplate: kendo.template($("#template").html()),
        detailInit: initGridDetail,
        dataBound: function(e) {
            var filterCheck=e.sender.dataSource.filter();
            var resetFilterValue = localStorage.getItem('resetFilterValue');
            var sortingCheck=e.sender.dataSource.sort();
            if(sortingCheck != undefined && localStorage.getItem('resetSortValue') != 'undefined'){
                localStorage.setItem('resetSortValue','resetSortValue');
            } else {
                localStorage.setItem('resetSortValue',undefined);
            }
            var resetSortValue = localStorage.getItem('resetSortValue');
            if(resetSortValue == 'undefined' && filterCheck == undefined && resetFilterValue == null){
                 countDown(constant.REFRESH_TRANSACTIONS_TIMER_MINUTES);
            }
            localStorage.clear();
            var data = this.dataSource.data();
            $.each(data, function(i, row) {
                var bought_count = row.get("bought_count");
                if (bought_count == 0) {
                    var element = $('tr[data-uid="' + row.uid + '"] ');
                    element.css("background-color", 'red');
                    element.css("color", 'black');
                } else {
                    var element = $('tr[data-uid="' + row.uid + '"] ');
                    element.css("background-color", 'green');
                    element.css("color", 'black');
                }
            });
            var transactionsGrid = $("#ticketListingDetails").data("kendoGrid");
            var transactionsData = transactionsGrid.dataSource.data();
            var transactionsDetailsLength = transactionsData.length;
            for (var transactionsDetails = 0; transactionsDetails < transactionsDetailsLength; transactionsDetails++) {
                var row = transactionsData[transactionsDetails];
                if (transactionsDetails == 0 || transactionsDetails == 1 || transactionsDetails == 2) {
                    transactionsGrid.expandRow("tr[data-uid='" + row.uid + "']");
                }
            }
        },
        columns: [{
            field: "bought_count",
            title: "Tickets",
            filterable: true,
            width: 80,
        }, {
            field: "quantity",
            title: "Quantity",
            filterable: false,
            width: 80,
        }, {
            field: "production_id",
            title: "Production Id",
            width: 120,
        }, {
            field: "production_name",
            title: "Production Name",
            width: 140,
        }, {
            field: "venue_name",
            title: "Venue Name",
            width: 100,
        }, {
            field: "event_date",
            title: "Event Date",
            width: 100,
        }, {
            field: "event_time",
            title: "Event Time",
            filterable: false,
            width: 100,
        }]
    });
    document.getElementById("refreshButton").value="Refresh : 60";
    function countDown(refreshMinutes) {
        function onTimer() {
            document.getElementById("refreshButton").value="Refresh : " + refreshMinutes;
            refreshMinutes--;
            if (refreshMinutes == 0) {
                localStorage.setItem('resetSortValue','undefined');
                $("#ticketListingDetails").data("kendoGrid").dataSource.read();
            } else {
                var transactionsTimeoutId = setTimeout(onTimer, 1000);
                $("#transactionsTimeoutId").val(transactionsTimeoutId);
            }
        }
        onTimer();
    }
    $("#refreshButton").on("click", function(e) {
        e.preventDefault();
        var refreshValue = this.value;
        var regex = /[0-9]/g;
        var refreshFound = refreshValue.match(regex);
        if(refreshFound){
            var transactionsTimeoutId = $("#transactionsTimeoutId").val();
            clearTimeout(transactionsTimeoutId);
            document.getElementById("refreshButton").value="Refresh: paused";
        } else {
            countDown(constant.REFRESH_TRANSACTIONS_TIMER_MINUTES);
        }
    });
     /* event Search */
    $("#transactionBtnSearch").click(function() {
        localStorage.clear();
        var eventSearchValue = $('#transactionSearchBox').val();
        $("#ticketListingDetails").data("kendoGrid").dataSource.filter({
            logic: "or",
            filters: [{
                field: "bought_count",
                operator: "contains",
                value: eventSearchValue
            }, {
                field: "quantity",
                operator: "contains",
                value: eventSearchValue
            }, {
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
                field: "event_date",
                operator: "contains",
                value: eventSearchValue
            } ]
        });
    });
    $('#transactionSearchBox').keypress(function(e) {
        if (e.which == 13) { //Enter key pressed
            $('#transactionBtnSearch').click(); //Trigger search button click event
        }
    });
    //Clearing the filter
    $("#transactionBtnReset").click(function() {
        $('#transactionSearchBox').val("");
        localStorage.setItem('resetFilterValue','reset');
        $("#ticketListingDetails").data("kendoGrid").dataSource.filter({});
    });
    var ticketPurchasedListGrid = jQuery("#ticketPurchasedEvent").kendoGrid({
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
                    url: requestUrl + "/ticketPurchasedEvent",
                    dataType: "json",
                    type: "POST"
                }
            },
            schema: {
                total: 'total',
                data: 'ticket_purchased_events',
                model: {
                    id: 'production_id',
                    fields: {
                        production_id: {
                            validation: {
                                required: true
                            }
                        }
                    }
                },
            },
        },
        pageable: {
            refresh: true,
            pageSizes: true
        },
        columnMenu: true,
        toolbar: [{
            template: kendo.template($("#PurchasedSearch").html())
        }],
        scrollable: true,
        sortable: true,
        reorderable: true,
        resizable: true,
        editable: false,
        detailInit: purchasedDetails,
        dataBound: function(e) {
            var filterCheck=e.sender.dataSource.filter();
            var resetFilterValue = localStorage.getItem('resetFilterValue');
            var sortingCheck=e.sender.dataSource.sort();
            if(sortingCheck != undefined && localStorage.getItem('resetSortValue') != 'undefined'){
                localStorage.setItem('resetSortValue','resetSortValue');
            } else {
                localStorage.setItem('resetSortValue',undefined);
            }
            var resetSortValue = localStorage.getItem('resetSortValue');
            if(resetSortValue == 'undefined' && filterCheck == undefined && resetFilterValue == null){
                purchasedCountDown(constant.REFRESH_TRANSACTIONS_TIMER_MINUTES);
            }
            localStorage.clear();
            var purchasedGrid = $("#ticketPurchasedEvent").data("kendoGrid");
            var purchasedData = purchasedGrid.dataSource.data();
            var purchasedDetailsLength = purchasedData.length;
            for (var purchasedDetails = 0; purchasedDetails < purchasedDetailsLength; purchasedDetails++) {
                var row = purchasedData[purchasedDetails];
                if (purchasedDetails == 0 || purchasedDetails == 1 || purchasedDetails == 2) {
                    purchasedGrid.expandRow("tr[data-uid='" + row.uid + "']");
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
            width: 100,
        }, {
            field: "venue_name",
            title: "Venue Name",
            width: 100,
        }, {
            field: "event_date",
            title: "Event Date",
            width: 100,
        }, {
            field: "event_time",
            title: "Event Time",
            width: 100,
        }]
    });
    document.getElementById("purchaseRefreshButton").value="Refresh : 60";
    function purchasedCountDown(refreshMinutes) {
        function purchasedTimer() {
            document.getElementById("purchaseRefreshButton").value="Refresh : " + refreshMinutes;
            refreshMinutes--;
            if (refreshMinutes == 0) {
                localStorage.setItem('resetSortValue','undefined');
                $('#ticketPurchasedEvent').data('kendoGrid').dataSource.read();
            } else {
                var purchaseTimeOutId = setTimeout(purchasedTimer, 1000);
                $("#purchaseTimeoutId").val(purchaseTimeOutId);
            }
        }
        purchasedTimer();
    }
    $("#purchaseRefreshButton").on("click", function(e) {
        e.preventDefault();
        var refreshValue = this.value;
        var regex = /[0-9]/g;
        var refreshFound = refreshValue.match(regex);
        if(refreshFound){
            var purchaseTimeOutId = $("#purchaseTimeoutId").val();
            clearTimeout(purchaseTimeOutId);
            document.getElementById("purchaseRefreshButton").value="Refresh: paused";
        } else {
            purchasedCountDown(constant.REFRESH_TRANSACTIONS_TIMER_MINUTES);
        }
    });
    /* event Search */
    $("#purchaseBtnSearch").click(function() {
        localStorage.clear();
        var eventSearchValue = $('#purchaseSearchBox').val();
        $("#ticketPurchasedEvent").data("kendoGrid").dataSource.filter({
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
                field: "event_date",
                operator: "contains",
                value: eventSearchValue
            } ]
        });
    });
    $('#purchaseSearchBox').keypress(function(e) {
        if (e.which == 13) { //Enter key pressed
            $('#purchaseBtnSearch').click(); //Trigger search button click event
        }
    });
    //Clearing the filter
    $("#purchaseBtnReset").click(function() {
        $('#purchaseSearchBox').val("");
        localStorage.setItem('resetFilterValue','reset');
        $("#ticketPurchasedEvent").data("kendoGrid").dataSource.filter({});
    });
    var ticketMissedEventListGrid = jQuery("#ticketMissedEvent").kendoGrid({
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
                    url: requestUrl + "/ticketMissedEvent",
                    dataType: "json",
                    type: "POST"
                }
            },
            schema: {
                total: 'total',
                data: 'ticket_missed_events',
                model: {
                    id: 'production_id',
                    fields: {
                        production_id: {
                            validation: {
                                required: true
                            }
                        }
                    }
                },
            },
        },
        pageable: {
            refresh: true,
            pageSizes: true
        },
         toolbar: [{
            template: kendo.template($("#MissedSearch").html())
        }],
        columnMenu: true,
        scrollable: true,
        sortable: true,
        reorderable: true,
        resizable: true,
        editable: false,
        detailInit: missedDetails,
        dataBound: function(e) {
            var filterCheck=e.sender.dataSource.filter();
            var resetFilterValue = localStorage.getItem('resetFilterValue');
            var sortingCheck=e.sender.dataSource.sort();
            if(sortingCheck != undefined && localStorage.getItem('resetSortValue') != 'undefined'){
                localStorage.setItem('resetSortValue','resetSortValue');
            } else {
                localStorage.setItem('resetSortValue',undefined);
            }
            var resetSortValue = localStorage.getItem('resetSortValue');
            if(resetSortValue == 'undefined' && filterCheck == undefined && resetFilterValue == null){
                missedCountDown(constant.REFRESH_TRANSACTIONS_TIMER_MINUTES);
            }
            localStorage.clear();
            var missedGrid = $("#ticketMissedEvent").data("kendoGrid");
            var missedData = missedGrid.dataSource.data();
            var missedDetailsLength = missedData.length;
            for (var missedDetails = 0; missedDetails < missedDetailsLength; missedDetails++) {
                var row = missedData[missedDetails];
                if (missedDetails == 0 || missedDetails == 1 || missedDetails == 2) {
                    missedGrid.expandRow("tr[data-uid='" + row.uid + "']");
                }
            }
        },
        columns: [{
            field: "production_id",
            title: "Production Id",
            width: 90,
        }, {
            field: "production_name",
            title: "Production Name",
            width: 140,
        }, {
            field: "venue_name",
            title: "Venue Name",
            width: 110,
        }, {
            field: "event_date",
            title: "Event Date",
            width: 100,
        }, {
            field: "event_time",
            title: "Event Time",
            width: 100,
        }]
    });
    document.getElementById("missedRefreshButton").value="Refresh : 60";
    function missedCountDown(refreshMinutes) {
        function missedTimer() {
            document.getElementById("missedRefreshButton").value="Refresh : " + refreshMinutes;
            refreshMinutes--;
            if (refreshMinutes == 0) {
                localStorage.setItem('resetSortValue','undefined');
                $('#ticketMissedEvent').data('kendoGrid').dataSource.read();
            } else {
                var missedTimeoutId = setTimeout(missedTimer, 1000);
                $("#missedTimeoutId").val(missedTimeoutId);
            }
        }
        missedTimer();
    }
    $("#missedRefreshButton").on("click", function(e) {
        e.preventDefault();
        var refreshValue = this.value;
        var regex = /[0-9]/g;
        var refreshFound = refreshValue.match(regex);
        if(refreshFound){
            var missedTimeoutId = $("#missedTimeoutId").val();
            clearTimeout(missedTimeoutId);
            document.getElementById("missedRefreshButton").value="Refresh: paused";
        } else {
            missedCountDown(constant.REFRESH_TRANSACTIONS_TIMER_MINUTES);
        }
    });
    /* event Search */
    $("#missedBtnSearch").click(function() {
        localStorage.clear();
        var eventSearchValue = $('#missedSearchBox').val();
        $("#ticketMissedEvent").data("kendoGrid").dataSource.filter({
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
                field: "event_date",
                operator: "contains",
                value: eventSearchValue
            } ]
        });
    });
    $('#missedSearchBox').keypress(function(e) {
        if (e.which == 13) { //Enter key pressed
            $('#missedBtnSearch').click(); //Trigger search button click event
        }
    });
    //Clearing the filter
    $("#missedBtnReset").click(function() {
        $('#missedSearchBox').val("");
        localStorage.setItem('resetFilterValue','reset');
        $("#ticketMissedEvent").data("kendoGrid").dataSource.filter({});
    });
    var ticketSalesListGrid = jQuery("#ticketSalesEvent").kendoGrid({
        dataSource: {
            serverPaging: true,
            serverFiltering: true,
            serverSorting: false,
            pageSize: 10,
            autoSync: true,
            group: [],
            transport: {
                read: {
                    data: {
                        _token: token
                    },
                    url: requestUrl + "/ticketSalesEvent",
                    dataType: "json",
                    type: "POST"
                }
            },
            schema: {
                total: 'total',
                data: 'ticket_sales_events',
                model: {
                    id: 'invoice_id',
                    fields: {
                        invoice_id: {
                            validation: {
                                required: true
                            }
                        }
                    }
                },
            },
        },
        pageable: {
            refresh: true,
            pageSizes: true
        },
        columnMenu: true,
        toolbar: [{
            template: kendo.template($("#SalesSearch").html())
        }],
        dataBound: function(e) {
            var filterCheck=e.sender.dataSource.filter();
            var resetFilterValue = localStorage.getItem('resetFilterValue');
            var sortingCheck=e.sender.dataSource.sort();
            if(sortingCheck != undefined && localStorage.getItem('resetSortValue') != 'undefined'){
                localStorage.setItem('resetSortValue','resetSortValue');
            } else {
                localStorage.setItem('resetSortValue',undefined);
            }
            var resetSortValue = localStorage.getItem('resetSortValue');
            if(resetSortValue == 'undefined' && filterCheck == undefined && resetFilterValue == null){
                 salesCountDown(constant.REFRESH_TRANSACTIONS_TIMER_MINUTES);
            }
            localStorage.clear();
        },
        scrollable: true,
        sortable: true,
        reorderable: true,
        resizable: true,
        editable: false,
        groupable: true,
        columns: [{
            field: "invoice_id",
            title: "Invoice Id",
            width: 100,
        },{
            field: "production_id",
            title: "Production Id",
            width: 120,
        }, {
            field: "base_production_name",
            title: "Production Name",
            template: "#if(data.base_production_name){# #=data.base_production_name# #}else{# #=data.production_name# #} #",
            width: 140,
        }, {
            field: "base_venue_name",
            title: "Venue Name",
            template: "#if(data.base_venue_name){# #=data.base_venue_name# #}else{# #=data.venue_name# #} #",
            width: 140,
        }, {
            field: "event_date",
            title: "Event Date",
            width: 100,
        }, {
            field: "event_time",
            title: "Event Time",
            width: 100,
        },{
            field: "section",
            title: "Section",
            width: 90,
        },{
            field: "row",
            title: "Row",
            width: 70,
        },{
            field: "seat_start",
            title: "Start",
            width: 70,
        },{
            field: "seat_end",
            title: "end",
            width: 70,
        },{
            field: "purchase_total",
            title: "Total",
            width: 100,
        }]
    });
    document.getElementById("salesRefreshButton").value="Refresh : 60";
    function salesCountDown(refreshMinutes) {
        function salesTimer() {
            document.getElementById("salesRefreshButton").value="Refresh : " + refreshMinutes;
            refreshMinutes--;
            if (refreshMinutes == 0) {
                localStorage.setItem('resetSortValue','undefined');
                $('#ticketSalesEvent').data('kendoGrid').dataSource.read();
            } else {
                var salesTimeoutId = setTimeout(salesTimer, 1000);
                $("#salesTimeoutId").val(salesTimeoutId);
            }
        }
        salesTimer();
    }
    $("#salesRefreshButton").on("click", function(e) {
        e.preventDefault();
        var refreshValue = this.value;
        var regex = /[0-9]/g;
        var refreshFound = refreshValue.match(regex);
        if(refreshFound){
            var salesTimeoutId = $("#salesTimeoutId").val();
            clearTimeout(salesTimeoutId);
            document.getElementById("salesRefreshButton").value="Refresh: paused";
        } else {
            salesCountDown(constant.REFRESH_TRANSACTIONS_TIMER_MINUTES);
        }
    });
    /* event Search */
    $("#salesBtnSearch").click(function() {
        localStorage.clear();
        var eventSearchValue = $('#salesSearchBox').val();
        $("#ticketSalesEvent").data("kendoGrid").dataSource.filter({
            logic: "or",
            filters: [{
                field: "invoice_id",
                operator: "contains",
                value: eventSearchValue
            }, {
                field: "production_id",
                operator: "contains",
                value: eventSearchValue
            }, {
                field: "base_production_name",
                operator: "contains",
                value: eventSearchValue
            }, {
                field: "base_venue_name",
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
            }, {
                field: "seat_end",
                operator: "contains",
                value: eventSearchValue
            }, {
                field: "purchase_total",
                operator: "contains",
                value: eventSearchValue
            }, {
                field: "event_date",
                operator: "contains",
                value: eventSearchValue
            } ]
        });
    });
    $('#salesSearchBox').keypress(function(e) {
        if (e.which == 13) { //Enter key pressed
            $('#salesBtnSearch').click(); //Trigger search button click event
        }
    });
    //Clearing the filter
    $("#salesBtnReset").click(function() {
        $('#salesSearchBox').val("");
        localStorage.setItem('resetFilterValue','reset');
        $("#ticketSalesEvent").data("kendoGrid").dataSource.filter({});
    });
    function purchasedDetails(purchaseDetails) {
        var production_id = purchaseDetails.data.production_id;
        $("<div/>").appendTo(purchaseDetails.detailCell).kendoGrid({
            dataSource: {
                pageSize: 5,
                transport: {
                    read: {
                        data: {
                            _token: token
                        },
                        url: requestUrl + "/ticketPurchasedWithMissed?production_id=" + production_id,
                        dataType: "json",
                        type: "POST"
                    }
                },
                schema: {
                    total: 'total',
                    data: 'ticket_purchased_with_missed',
                    model: {
                        id: 'production_id'
                    },
                },
            },
            pageable: {
                refresh: true,
                pageSizes: true
            },
            columnMenu: true,
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
            selectable: 'row',
            scrollable: true,
            sortable: true,
            detailInit: initPurchasedListingDetail,
            columns: [{
                field: "listing_id",
                title: "Listing Id",
                width: 100,
            }, {
                field: "purchase_date",
                title: "Purchase Date",
                filterable: false,
                width: 120,
            }, {
                field: "purchase_time",
                title: "Purchase Time",
                filterable: false,
                width: 120,
            }, {
                field: "price",
                title: "Price",
                width: 100,
            }, {
                field: "section",
                title: "Section",
                width: 100,
            }, {
                field: "row",
                title: "Row",
                width: 100,
            }, {
                field: "quantity",
                title: "Quantity",
                width: 100,
            }, {
                field: "seat_start",
                title: "Seat Start",
                width: 100,
            }, {
                field: "seat_end",
                title: "Seat End",
                width: 100,
            }, {
                field: "bought",
                title: "Bought",
                width: 100,
            }]
        });
    }

    function initPurchasedListingDetail(purchaseDetails) {
        var listing_id = purchaseDetails.data.listing_id;
        $("<div/>").appendTo(purchaseDetails.detailCell).kendoGrid({
            dataSource: {
                pageSize: 5,
                transport: {
                    read: {
                        data: {
                            _token: token
                        },
                        url: requestUrl + "/ticketPurchasedListingDetails?listing_id=" + listing_id,
                        dataType: "json",
                        type: "POST"
                    }
                },
                schema: {
                    total: 'total',
                    data: 'ticket_purchased_listing',
                    model: {
                        id: 'production_id'
                    },
                },
            },
            pageable: {
                refresh: true,
                pageSizes: true
            },
            columnMenu: true,
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
            selectable: 'row',
            scrollable: true,
            sortable: true,
            columns: [{
                field: "code",
                title: "Code",
                width: 100,
            }, {
                field: "message",
                title: "Message",
                width: 100,
            }, {
                field: "response",
                title: "Response",
                width: 120,
            }, {
                field: "pull_proxy_ip",
                title: "Pull Proxy Ip",
                width: 150,
            }, {
                field: "purchase_proxy_ip",
                title: "Purchase Proxy Ip",
                width: 150,
            }, {
                field: "time_connect",
                title: "Time Connect",
                filterable: false,
                width: 150,
            }, {
                field: "time_internal_submit",
                title: "Time Internal Submit",
                filterable: false,
                width: 150,
            }, {
                field: "time_internal_receive",
                title: "Time Internal Receive",
                filterable: false,
                width: 150,
            }, {
                field: "time_submit",
                title: "Time Submit",
                filterable: false,
                width: 130,
            }, {
                field: "time_response",
                title: "Time Response",
                filterable: false,
                width: 130,
            }, {
                field: "time_data",
                title: "Time Data",
                filterable: false,
                width: 100,
            }]
        });
    }

    function missedDetails(misseDetails) {
        var production_id = misseDetails.data.production_id;
        $("<div/>").appendTo(misseDetails.detailCell).kendoGrid({
            dataSource: {
                pageSize: 5,
                transport: {
                    read: {
                        data: {
                            _token: token
                        },
                        url: requestUrl + "/ticketMissedWithPurchase?production_id=" + production_id,
                        dataType: "json",
                        type: "POST"
                    }
                },
                schema: {
                    total: 'total',
                    data: 'ticket_missed_with_purchase',
                    model: {
                        id: 'production_id'
                    },
                },
            },
            pageable: {
                refresh: true,
                pageSizes: true
            },
            columnMenu: true,
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
            selectable: 'row',
            scrollable: true,
            detailInit: initMissedListingDetail,
            sortable: true,
            columns: [{
                field: "listing_id",
                title: "Listing Id",
                width: 100,
            }, {
                field: "purchase_date",
                title: "Purchase Date",
                filterable: false,
                width: 120,
            }, {
                field: "purchase_time",
                title: "Purchase Time",
                filterable: false,
                width: 120,
            }, {
                field: "price",
                title: "Price",
                width: 100,
            }, {
                field: "section",
                title: "Section",
                width: 100,
            }, {
                field: "row",
                title: "Row",
                width: 100,
            }, {
                field: "quantity",
                title: "Quantity",
                width: 100,
            }, {
                field: "seat_start",
                title: "Seat Start",
                width: 100,
            }, {
                field: "seat_end",
                title: "Seat End",
                width: 100,
            }, {
                field: "message",
                title: "Message",
                width: 100,
            }, {
                field: "bought",
                title: "Bought",
                width: 100,
            }]
        });
    }

    function initMissedListingDetail(missedDetails) {
        var listing_id = missedDetails.data.listing_id;
        $("<div/>").appendTo(missedDetails.detailCell).kendoGrid({
            dataSource: {
                pageSize: 5,
                transport: {
                    read: {
                        data: {
                            _token: token
                        },
                        url: requestUrl + "/ticketMissedListingDetails?listing_id=" + listing_id,
                        dataType: "json",
                        type: "POST"
                    }
                },
                schema: {
                    total: 'total',
                    data: 'ticket_missed_listing',
                    model: {
                        id: 'listing_id'
                    },
                },
            },
            pageable: {
                refresh: true,
                pageSizes: true
            },
            columnMenu: true,
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
            selectable: 'row',
            scrollable: true,
            sortable: true,
            columns: [{
                field: "code",
                title: "Code",
                width: 100,
            }, {
                field: "message",
                title: "Message",
                width: 100,
            }, {
                field: "response",
                title: "Response",
                width: 120,
            }, {
                field: "pull_proxy_ip",
                title: "Pull Proxy Ip",
                width: 150,
            }, {
                field: "purchase_proxy_ip",
                title: "Purchase Proxy Ip",
                width: 150,
            }, {
                field: "time_connect",
                title: "Time Connect",
                filterable: false,
                width: 150,
            }, {
                field: "time_internal_submit",
                title: "Time Internal Submit",
                filterable: false,
                width: 150,
            }, {
                field: "time_internal_receive",
                title: "Time Internal Receive",
                filterable: false,
                width: 150,
            }, {
                field: "time_submit",
                title: "Time Submit",
                filterable: false,
                width: 130,
            }, {
                field: "time_response",
                title: "Time Response",
                filterable: false,
                width: 130,
            }, {
                field: "time_data",
                title: "Time Data",
                filterable: false,
                width: 120,
            }]
        });
    }

    function initGridDetail(e) {
        {
            var listing_id = e.data.listing_id;
            var production_id = e.data.production_id;
            var detailRow = e.detailRow;
            detailRow.find(".tabstrip").kendoTabStrip({
                animation: {
                    open: {
                        effects: "fadeIn"
                    }
                }
            });
            detailRow.find(".ticketPurchasedDetails").kendoGrid({
                dataSource: {
                    serverPaging: true,
                    serverFiltering: true,
                    serverSorting: false,
                    pageSize: 5,
                    transport: {
                        read: {
                            data: {
                                _token: token
                            },
                            url: requestUrl + "/ticketPurchasedWithMissed?production_id=" + production_id,
                            dataType: "json",
                            type: "POST"
                        }
                    },
                    schema: {
                        total: 'total',
                        data: 'ticket_purchased_with_missed',
                        model: {
                            id: 'production_id'
                        },
                    },
                },
                pageable: {
                    refresh: true,
                    pageSizes: true
                },
                columnMenu: true,
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
                reorderable: true,
                resizable: true,
                editable: false,
                detailInit: proxyPullDetails,
                columns: [{
                    field: "listing_id",
                    title: "Listing Id",
                    width: 100,
                }, {
                    field: "purchase_date",
                    title: "Purchase Date",
                    filterable: false,
                    width: 120,
                }, {
                    field: "purchase_time",
                    title: "Purchase Time",
                    filterable: false,
                    width: 120,
                }, {
                    field: "pull_proxy_ip",
                    title: "Proxy Ip",
                    width: 100,
                }, {
                    field: "section",
                    title: "Section",
                    width: 80,
                }, {
                    field: "row",
                    title: "Row",
                    width: 60,
                }, {
                    field: "quantity",
                    title: "Quantity",
                    width: 90,
                }, {
                    field: "price",
                    title: "Price",
                    width: 100,
                }, {
                    field: "price_max",
                    title: "Price Max",
                    width: 100,
                }, {
                    field: "seat_start",
                    title: "Seat Start",
                    width: 90,
                }, {
                    field: "seat_end",
                    title: "Seat End",
                    width: 90,
                }, {
                    field: "time_connect",
                    title: "Time Connect",
                    filterable: false,
                    width: 130,
                }, {
                    field: "time_internal_submit",
                    title: "Time Internal Submit",
                    filterable: false,
                    width: 150,
                }, {
                    field: "time_internal_receive",
                    title: "Time Internal Receive",
                    filterable: false,
                    width: 150,
                }, {
                    field: "time_submit",
                    title: "Time Submit",
                    filterable: false,
                    width: 130,
                }, {
                    field: "time_response",
                    title: "Time Response",
                    filterable: false,
                    width: 130,
                }, {
                    field: "time_data",
                    title: "Time Data",
                    filterable: false,
                    width: 100,
                }]
            });
            detailRow.find(".ticketMissedDetails").kendoGrid({
                dataSource: {
                    serverPaging: true,
                    serverFiltering: true,
                    serverSorting: false,
                    pageSize: 5,
                    transport: {
                        read: {
                            data: {
                                _token: token
                            },
                            url: requestUrl + "/ticketMissedWithPurchase?production_id=" + production_id,
                            dataType: "json",
                            type: "POST"
                        }
                    },
                    schema: {
                        total: 'total',
                        data: 'ticket_missed_with_purchase',
                        model: {
                            id: 'production_id'
                        },
                    },
                },
                pageable: {
                    refresh: true,
                    pageSizes: true
                },
                columnMenu: true,
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
                reorderable: true,
                resizable: true,
                editable: false,
                detailInit: proxyPullDetails,
                columns: [{
                    field: "purchase_date",
                    title: "Purchase Date",
                    filterable: false,
                    width: 130,
                }, {
                    field: "purchase_time",
                    title: "Purchase Time",
                    filterable: false,
                    width: 130,
                }, {
                    field: "message",
                    title: "Message",
                    width: 100,
                }, {
                    field: "section",
                    title: "Section",
                    width: 80,
                }, {
                    field: "row",
                    title: "Row",
                    width: 60,
                }, {
                    field: "quantity",
                    title: "Quantity",
                    width: 90,
                }, {
                    field: "price",
                    title: "Price",
                    width: 100,
                }, {
                    field: "price_max",
                    title: "Price Max",
                    width: 100,
                }, {
                    field: "seat_start",
                    title: "Seat Start",
                    width: 90,
                }, {
                    field: "seat_end",
                    title: "Seat End",
                    width: 90,
                }, {
                    field: "pull_proxy_ip",
                    title: "Proxy Ip",
                    width: 100,
                }, {
                    field: "time_connect",
                    title: "Time Connect",
                    filterable: false,
                    width: 130,
                }, {
                    field: "time_internal_submit",
                    title: "Time Internal Submit",
                    filterable: false,
                    width: 150,
                }, {
                    field: "time_internal_receive",
                    title: "Time Internal Receive",
                    filterable: false,
                    width: 150,
                }, {
                    field: "time_submit",
                    title: "Time Submit",
                    filterable: false,
                    width: 130,
                }, {
                    field: "time_response",
                    title: "Time Response",
                    filterable: false,
                    width: 130,
                }, {
                    field: "time_data",
                    title: "Time Data",
                    filterable: false,
                    width: 100,
                }]
            });
        }
    }

    function proxyPullDetails(proxyDetails) {
        var listing_id = proxyDetails.data.listing_id;
        var purchase_proxy_ip = proxyDetails.data.purchase_proxy_ip;
        $("<div style='width:47%'/>").appendTo(proxyDetails.detailCell).kendoGrid({
            dataSource: {
                pageSize: 5,
                width: "50px",
                transport: {
                    read: {
                        data: {
                            _token: token
                        },
                        url: requestUrl + "/proxyPurchasedDetails?purchase_proxy_ip=" + proxyDetails.data.pull_proxy_ip,
                        dataType: "json",
                        type: "POST"
                    }
                }
            },
            pageable: {
                refresh: true,
                pageSizes: true
            },
            selectable: 'row',
            scrollable: true,
            sortable: true,
            columns: [{
                field: "target_ip",
                title: "Proxy Target Ip",
                width: 100
            }, {
                field: "proxy_port",
                title: "Proxy Port",
                width: 100
            }, {
                field: "request_date",
                title: "Request Date",
                filterable: false,
                width: 100
            }, {
                field: "request_time",
                title: "Request Time",
                filterable: false,
                width: 100
            }, {
                field: "response_duration",
                title: "Request Duration",
                width: 100
            }, {
                field: "",
                title: "Proxy Type",
                width: 100
            }]
        });
        if (proxyDetails.data.listingId != null) {
            $("<div/ style='width:47%'/>").appendTo(proxyDetails.detailCell).kendoGrid({
                dataSource: {
                    pageSize: 5,
                    transport: {
                        read: {
                            data: {
                                _token: token
                            },
                            url: requestUrl + "/purchasingDetails?listingId=" + proxyDetails.data.listingId,
                            dataType: "json",
                            type: "POST"
                        }
                    }
                },
                pageable: {
                    refresh: true,
                    pageSizes: true
                },
                selectable: 'row',
                scrollable: true,
                sortable: true,
                columns: [{
                    field: "purchase_date",
                    title: "Purchase Date",
                    width: 20
                }, {
                    field: "purchase_time",
                    title: "Purchase Time",
                    width: 20
                }, {
                    field: "proxy_node_target_id",
                    title: "Target Id",
                    width: 20
                }, {
                    field: "delay",
                    title: "Delay",
                    width: 20
                }]
            });
        }
    }
});