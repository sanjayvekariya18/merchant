var token = $('input[name="_token"]').val();
var requestUrl = $("#requestUrl").val();
var transferRequestUrl = "transfer";
var checkedIds = {};
var REFRESH_TRANSFER_TIMER_MINUTES = 60;

function identityMerchantGroupIdEditor(container, options) {
    $("<input name='" + options.field + "'/>").appendTo(container).kendoNumericTextBox({
        min: 0,
        step: 1,
        decimals: 0
    });
    var tooltipElement = $("<span class='k-invalid-msg' data-for='" + options.field + "'></span>");
    tooltipElement.appendTo(container);
}

function categoryIdEditor(container, options) {
    $("<input name='" + options.field + "'/>").appendTo(container).kendoNumericTextBox({
        min: 0,
        step: 1,
        decimals: 0
    });
    var tooltipElement = $("<span class='k-invalid-msg' data-for='" + options.field + "'></span>");
    tooltipElement.appendTo(container);
}

function invoiceIdEditor(container, options) {
    $("<input name='" + options.field + "'/>").appendTo(container).kendoNumericTextBox({
        min: 0,
        step: 1,
        decimals: 0
    });
    var tooltipElement = $("<span class='k-invalid-msg' data-for='" + options.field + "'></span>");
    tooltipElement.appendTo(container);
}

function listingIdEditor(container, options) {
    $("<input name='" + options.field + "'/>").appendTo(container).kendoNumericTextBox({
        min: 0,
        step: 1,
        decimals: 0
    });
    var tooltipElement = $("<span class='k-invalid-msg' data-for='" + options.field + "'></span>");
    tooltipElement.appendTo(container);
}

function salesDateEditor(container, options) {
    $("<input type='text' data-text-field='" + options.field + "' data-value-field='" + options.field + "' data-bind='value:" + options.field + "' data-format='" + options.format + "'/>").appendTo(container).kendoDateTimePicker({});
}

function exchangeIdEditor(container, options) {
    $("<input name='" + options.field + "'/>").appendTo(container).kendoNumericTextBox({
        min: 0,
        step: 1,
        decimals: 0
    });
    var tooltipElement = $("<span class='k-invalid-msg' data-for='" + options.field + "'></span>");
    tooltipElement.appendTo(container);
}

function eventIdEditor(container, options) {
    $("<input name='" + options.field + "'/>").appendTo(container).kendoNumericTextBox({
        min: 0,
        step: 1,
        decimals: 0
    });
    var tooltipElement = $("<span class='k-invalid-msg' data-for='" + options.field + "'></span>");
    tooltipElement.appendTo(container);
}

function eventDateEditor(container, options) {
    $("<input name='" + options.field + "'/>").appendTo(container).kendoNumericTextBox({
        min: 0,
        step: 1,
        decimals: 0
    });
    var tooltipElement = $("<span class='k-invalid-msg' data-for='" + options.field + "'></span>");
    tooltipElement.appendTo(container);
}

function venueIdEditor(container, options) {
    $("<input name='" + options.field + "'/>").appendTo(container).kendoNumericTextBox({
        min: 0,
        step: 1,
        decimals: 0
    });
    var tooltipElement = $("<span class='k-invalid-msg' data-for='" + options.field + "'></span>");
    tooltipElement.appendTo(container);
}

function itemPriceEditor(container, options) {
    $("<input name='" + options.field + "'/>").appendTo(container).kendoNumericTextBox({
        min: 0,
        step: 0.00000001,
        decimals: 8
    });
    var tooltipElement = $("<span class='k-invalid-msg' data-for='" + options.field + "'></span>");
    tooltipElement.appendTo(container);
}

function seatQuantityEditor(container, options) {
    $("<input name='" + options.field + "'/>").appendTo(container).kendoNumericTextBox({
        min: 0,
        step: 1,
        decimals: 0
    });
    var tooltipElement = $("<span class='k-invalid-msg' data-for='" + options.field + "'></span>");
    tooltipElement.appendTo(container);
}

function invoiceAmountEditor(container, options) {
    $("<input name='" + options.field + "'/>").appendTo(container).kendoNumericTextBox({
        min: 0,
        step: 0.00000001,
        decimals: 8
    });
    var tooltipElement = $("<span class='k-invalid-msg' data-for='" + options.field + "'></span>");
    tooltipElement.appendTo(container);
}

function invoiceCommissionEditor(container, options) {
    $("<input name='" + options.field + "'/>").appendTo(container).kendoNumericTextBox({
        min: 0,
        step: 0.00000001,
        decimals: 8
    });
    var tooltipElement = $("<span class='k-invalid-msg' data-for='" + options.field + "'></span>");
    tooltipElement.appendTo(container);
}

function netAmountEditor(container, options) {
    $("<input name='" + options.field + "'/>").appendTo(container).kendoNumericTextBox({
        min: 0,
        step: 0.00000001,
        decimals: 8
    });
    var tooltipElement = $("<span class='k-invalid-msg' data-for='" + options.field + "'></span>");
    tooltipElement.appendTo(container);
}

function costEditor(container, options) {
    $("<input name='" + options.field + "'/>").appendTo(container).kendoNumericTextBox({
        min: 0,
        step: 0.00000001,
        decimals: 8
    });
    var tooltipElement = $("<span class='k-invalid-msg' data-for='" + options.field + "'></span>");
    tooltipElement.appendTo(container);
}

function roiDollarEditor(container, options) {
    $("<input name='" + options.field + "'/>").appendTo(container).kendoNumericTextBox({
        min: 0,
        step: 0.00000001,
        decimals: 8
    });
    var tooltipElement = $("<span class='k-invalid-msg' data-for='" + options.field + "'></span>");
    tooltipElement.appendTo(container);
}

function roiPercentEditor(container, options) {
    $("<input name='" + options.field + "'/>").appendTo(container).kendoNumericTextBox({
        min: 0,
        step: 0.00000001,
        decimals: 8
    });
    var tooltipElement = $("<span class='k-invalid-msg' data-for='" + options.field + "'></span>");
    tooltipElement.appendTo(container);
}

function paymentDateEditor(container, options) {
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
        var grid = $("#transferGrid").data("kendoGrid");
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
    var transferGrid = jQuery("#transferGrid").kendoGrid({
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
                    url: transferRequestUrl + "/getTransfer",
                    dataType: "json",
                    type: "POST"
                },
                update: {
                    data: {
                        _token: token
                    },
                    url: transferRequestUrl + "/updateTransfer",
                    dataType: "jsonp",
                    type: "POST"
                },
                destroy: {
                    data: {
                        _token: token
                    },
                    url: transferRequestUrl + "/deleteTransfer",
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
                    var transferGrid = $("#transferGrid").data("kendoGrid");
                    transferGrid.dataSource.read();
                }
            },
            schema: {
                total: 'total',
                data: 'transfer',
                model: {
                    id: 'transfer_id',
                    fields: {
                        identity_merchant_group_id: {
                            type: "number"
                        },
                        category_id: {
                            type: "number"
                        },
                        category_name: {
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
                        invoice_id: {
                            type: "number"
                        },
                        listing_id: {
                            type: "number"
                        },
                        exchange_id: {
                            type: "number"
                        },
                        exchange_name: {
                            validation: {
                                maxlength: function(input) {
                                    if (input.val().length != 0) {
                                        if (input.val().length > 25) {
                                            input.attr("data-maxlength-msg", "Max length is 25");
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
                        event_id: {
                            type: "number"
                        },
                        event_name: {
                            validation: {
                                maxlength: function(input) {
                                    if (input.val().length != 0) {
                                        if (input.val().length > 200) {
                                            input.attr("data-maxlength-msg", "Max length is 200");
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
                        event_date: {
                            type: "number"
                        },
                        event_time: {
                            validation: {
                                maxlength: function(input) {
                                    if (input.val().length != 0) {
                                        if (input.val().length > 256) {
                                            input.attr("data-maxlength-msg", "Max length is 256");
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
                        venue_id: {
                            type: "number"
                        },
                        venue_name: {
                            validation: {
                                maxlength: function(input) {
                                    if (input.val().length != 0) {
                                        if (input.val().length > 70) {
                                            input.attr("data-maxlength-msg", "Max length is 70");
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
                        section: {
                            validation: {
                                maxlength: function(input) {
                                    if (input.val().length != 0) {
                                        if (input.val().length > 24) {
                                            input.attr("data-maxlength-msg", "Max length is 24");
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
                        row: {
                            validation: {
                                maxlength: function(input) {
                                    if (input.val().length != 0) {
                                        if (input.val().length > 10) {
                                            input.attr("data-maxlength-msg", "Max length is 10");
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
                        seat_quantity: {
                            type: "number"
                        },
                        seat_start: {
                            validation: {
                                maxlength: function(input) {
                                    if (input.val().length != 0) {
                                        if (input.val().length > 10) {
                                            input.attr("data-maxlength-msg", "Max length is 10");
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
                        start_end: {
                            validation: {
                                maxlength: function(input) {
                                    if (input.val().length != 0) {
                                        if (input.val().length > 10) {
                                            input.attr("data-maxlength-msg", "Max length is 10");
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
                        payment_date: {
                            type: "number"
                        },
                        payment_type: {
                            validation: {
                                maxlength: function(input) {
                                    if (input.val().length != 0) {
                                        if (input.val().length > 15) {
                                            input.attr("data-maxlength-msg", "Max length is 15");
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
                        status: {
                            validation: {
                                maxlength: function(input) {
                                    if (input.val().length != 0) {
                                        if (input.val().length > 20) {
                                            input.attr("data-maxlength-msg", "Max length is 20");
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
            template: kendo.template($("#transferHeaderAction").html())
        }],
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
                transferCountDown(REFRESH_TRANSFER_TIMER_MINUTES);
            }
            localStorage.clear();
        },
        scrollable: true,
        sortable: true,
        reorderable: true,
        groupable: true,
        resizable: true,
        editable: true,
        columnMenu: true,
        columns: [{
            field: "identity_merchant_group_id",
            title: "Identity Merchant Group Id",
            editor: identityMerchantGroupIdEditor,
            width: 200,
        }, {
            field: "category_id",
            title: "Category Id",
            editor: categoryIdEditor,
            width: 100,
        }, {
            field: "category_name",
            title: "Category Name",
            width: 120,
        }, {
            field: "invoice_id",
            title: "Invoice Id",
            editor: invoiceIdEditor,
            width: 100,
        }, {
            field: "listing_id",
            title: "Listing Id",
            editor: listingIdEditor,
            width: 100,
        }, {
            field: "sales_date",
            title: "Sales Date",
            editor: salesDateEditor,
            width: 100,
        }, {
            field: "exchange_id",
            title: "Exchange Id",
            editor: exchangeIdEditor,
            width: 100,
        }, {
            field: "exchange_name",
            title: "Exchange Name",
            width: 120,
        }, {
            field: "event_id",
            title: "Event Id",
            editor: eventIdEditor,
            width: 100,
        }, {
            field: "event_name",
            title: "Event Name",
            width: 100,
        }, {
            field: "event_date",
            title: "Event Date",
            editor: eventDateEditor,
            width: 100,
        }, {
            field: "event_time",
            title: "Event Time",
            width: 100,
        }, {
            field: "venue_id",
            title: "Venue Id",
            editor: venueIdEditor,
             width: 100
        }, {
            field: "venue_name",
            title: "Venue Name",
            width: 100
        }, {
            field: "section",
            title: "Section",
            width: 100
        }, {
            field: "row",
            title: "Row",
            width: 80
        }, {
            field: "item_price",
            title: "Item Price",
            editor: itemPriceEditor,
            width: 100
        }, {
            field: "seat_quantity",
            title: "Seat Quantity",
            editor: seatQuantityEditor,
            width: 100
        }, {
            field: "seat_start",
            title: "Seat Start",
            width: 100
        }, {
            field: "start_end",
            title: "Start End",
            width: 100
        }, {
            field: "invoice_amount",
            title: "Invoice Amount",
            editor: invoiceAmountEditor,
            width: 140
        }, {
            field: "invoice_commission",
            title: "Invoice Commission",
            editor: invoiceCommissionEditor,
            width: 160
        }, {
            field: "net_amount",
            title: "Net Amount",
            editor: netAmountEditor,
            width: 100
        }, {
            field: "cost",
            title: "Cost",
            editor: costEditor,
            width: 120
        }, {
            field: "roi_dollar",
            title: "Roi Dollar",
            editor: roiDollarEditor,
            width: 120
        }, {
            field: "roi_percent",
            title: "Roi Percent",
            editor: roiPercentEditor,
            width: 120
        }, {
            field: "payment_date",
            title: "Payment Date",
            editor: paymentDateEditor,
            width: 120
        }, {
            field: "payment_type",
            title: "Payment Type",
            width: 120
        }, {
            field: "status",
            title: "Status",
            width: 120
        }, ],
    });
    document.getElementById("transferRefreshButton").value="Refresh : 60";
    function transferCountDown(refreshMinutes) {
        function transferTimer() {
            document.getElementById("transferRefreshButton").value="Refresh : " + refreshMinutes;
            refreshMinutes--;
            if (refreshMinutes == 0) {
                localStorage.setItem('resetSortValue','undefined');
                $('#transferGrid').data('kendoGrid').dataSource.read();
            } else {
                var transferTimeoutId = setTimeout(transferTimer, 1000);
                $("#transferTimeoutId").val(transferTimeoutId);
            }
        }
        transferTimer();
    }
    $("#transferRefreshButton").on("click", function(e) {
        e.preventDefault();
        var refreshValue = this.value;
        var regex = /[0-9]/g;
        var refreshFound = refreshValue.match(regex);
        if(refreshFound){
            var transferTimeoutId = $("#transferTimeoutId").val();
            clearTimeout(transferTimeoutId);
            document.getElementById("transferRefreshButton").value="Refresh: paused";
        } else {
            transferCountDown(REFRESH_TRANSFER_TIMER_MINUTES);
        }
    });
    /* event Search */
    $("#transferBtnSearch").click(function() {
        localStorage.clear();
        var eventSearchValue = $('#transferSearchBox').val();
        $("#transferGrid").data("kendoGrid").dataSource.filter({
            logic: "or",
            filters: [{
                field: "category_name",
                operator: "contains",
                value: eventSearchValue
            }, {
                field: "invoice_id",
                operator: "contains",
                value: eventSearchValue
            }, {
                field: "sales_date",
                operator: "contains",
                value: eventSearchValue
            }, {
                field: "exchange_id",
                operator: "contains",
                value: eventSearchValue
            }, {
                field: "listing_id",
                operator: "contains",
                value: eventSearchValue
            }, {
                field: "exchange_name",
                operator: "contains",
                value: eventSearchValue
            }, {
                field: "event_name",
                operator: "contains",
                value: eventSearchValue
            }, {
                field: "event_time",
                operator: "contains",
                value: eventSearchValue
            }, {
                field: "venue_name",
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
                field: "start_end",
                operator: "contains",
                value: eventSearchValue
            }, {
                field: "payment_type",
                operator: "contains",
                value: eventSearchValue
            }, {
                field: "status",
                operator: "contains",
                value: eventSearchValue
            }, ]
        });
    });
    $('#transferSearchBox').keypress(function(e) {
        if (e.which == 13) { //Enter key pressed
            $('#transferBtnSearch').click(); //Trigger search button click event
        }
    });
    //Clearing the filter
    $("#transferBtnReset").click(function() {
        $('#transferSearchBox').val("");
        localStorage.setItem('resetFilterValue','reset');
        $("#transferGrid").data("kendoGrid").dataSource.filter({});
    });
    /* calendar Search end*/

    var openGrid = jQuery("#openGrid").kendoGrid({
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
                    url: transferRequestUrl + "/getTransfer?status=" + "Payment Pending",
                    dataType: "json",
                    type: "POST"
                },
                update: {
                    data: {
                        _token: token
                    },
                    url: transferRequestUrl + "/updateTransfer",
                    dataType: "jsonp",
                    type: "POST"
                },
                destroy: {
                    data: {
                        _token: token
                    },
                    url: transferRequestUrl + "/deleteTransfer",
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
                    var openGrid = $("#openGrid").data("kendoGrid");
                    openGrid.dataSource.read();
                }
            },
            schema: {
                total: 'total',
                data: 'transfer',
                model: {
                    id: 'transfer_id',
                    fields: {
                        identity_merchant_group_id: {
                            type: "number"
                        },
                        category_id: {
                            type: "number"
                        },
                        category_name: {
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
                        invoice_id: {
                            type: "number"
                        },
                        listing_id: {
                            type: "number"
                        },
                        exchange_id: {
                            type: "number"
                        },
                        exchange_name: {
                            validation: {
                                maxlength: function(input) {
                                    if (input.val().length != 0) {
                                        if (input.val().length > 25) {
                                            input.attr("data-maxlength-msg", "Max length is 25");
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
                        event_id: {
                            type: "number"
                        },
                        event_name: {
                            validation: {
                                maxlength: function(input) {
                                    if (input.val().length != 0) {
                                        if (input.val().length > 200) {
                                            input.attr("data-maxlength-msg", "Max length is 200");
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
                        event_date: {
                            type: "number"
                        },
                        event_time: {
                            validation: {
                                maxlength: function(input) {
                                    if (input.val().length != 0) {
                                        if (input.val().length > 256) {
                                            input.attr("data-maxlength-msg", "Max length is 256");
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
                        venue_id: {
                            type: "number"
                        },
                        venue_name: {
                            validation: {
                                maxlength: function(input) {
                                    if (input.val().length != 0) {
                                        if (input.val().length > 70) {
                                            input.attr("data-maxlength-msg", "Max length is 70");
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
                        section: {
                            validation: {
                                maxlength: function(input) {
                                    if (input.val().length != 0) {
                                        if (input.val().length > 24) {
                                            input.attr("data-maxlength-msg", "Max length is 24");
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
                        row: {
                            validation: {
                                maxlength: function(input) {
                                    if (input.val().length != 0) {
                                        if (input.val().length > 10) {
                                            input.attr("data-maxlength-msg", "Max length is 10");
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
                        seat_quantity: {
                            type: "number"
                        },
                        seat_start: {
                            validation: {
                                maxlength: function(input) {
                                    if (input.val().length != 0) {
                                        if (input.val().length > 10) {
                                            input.attr("data-maxlength-msg", "Max length is 10");
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
                        start_end: {
                            validation: {
                                maxlength: function(input) {
                                    if (input.val().length != 0) {
                                        if (input.val().length > 10) {
                                            input.attr("data-maxlength-msg", "Max length is 10");
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
                        payment_date: {
                            type: "number"
                        },
                        payment_type: {
                            validation: {
                                maxlength: function(input) {
                                    if (input.val().length != 0) {
                                        if (input.val().length > 15) {
                                            input.attr("data-maxlength-msg", "Max length is 15");
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
                        status: {
                            validation: {
                                maxlength: function(input) {
                                    if (input.val().length != 0) {
                                        if (input.val().length > 20) {
                                            input.attr("data-maxlength-msg", "Max length is 20");
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
            template: kendo.template($("#openHeaderAction").html())
        }],
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
                openCountDown(REFRESH_TRANSFER_TIMER_MINUTES);
            }
            localStorage.clear();
        },
        scrollable: true,
        sortable: true,
        reorderable: true,
        groupable: true,
        resizable: true,
        editable: true,
        columnMenu: true,
        columns: [{
            field: "identity_merchant_group_id",
            title: "Identity Merchant Group Id",
            editor: identityMerchantGroupIdEditor,
            width: 200,
        }, {
            field: "category_id",
            title: "Category Id",
            editor: categoryIdEditor,
            width: 100,
        }, {
            field: "category_name",
            title: "Category Name",
            width: 120,
        }, {
            field: "invoice_id",
            title: "Invoice Id",
            editor: invoiceIdEditor,
            width: 100,
        }, {
            field: "listing_id",
            title: "Listing Id",
            editor: listingIdEditor,
            width: 100,
        }, {
            field: "sales_date",
            title: "Sales Date",
            editor: salesDateEditor,
            width: 100,
        }, {
            field: "exchange_id",
            title: "Exchange Id",
            editor: exchangeIdEditor,
            width: 100,
        }, {
            field: "exchange_name",
            title: "Exchange Name",
            width: 120,
        }, {
            field: "event_id",
            title: "Event Id",
            editor: eventIdEditor,
            width: 100,
        }, {
            field: "event_name",
            title: "Event Name",
            width: 100,
        }, {
            field: "event_date",
            title: "Event Date",
            editor: eventDateEditor,
            width: 100,
        }, {
            field: "event_time",
            title: "Event Time",
            width: 100,
        }, {
            field: "venue_id",
            title: "Venue Id",
            editor: venueIdEditor,
             width: 100
        }, {
            field: "venue_name",
            title: "Venue Name",
            width: 100
        }, {
            field: "section",
            title: "Section",
            width: 100
        }, {
            field: "row",
            title: "Row",
            width: 80
        }, {
            field: "item_price",
            title: "Item Price",
            editor: itemPriceEditor,
            width: 100
        }, {
            field: "seat_quantity",
            title: "Seat Quantity",
            editor: seatQuantityEditor,
            width: 100
        }, {
            field: "seat_start",
            title: "Seat Start",
            width: 100
        }, {
            field: "start_end",
            title: "Start End",
            width: 100
        }, {
            field: "invoice_amount",
            title: "Invoice Amount",
            editor: invoiceAmountEditor,
            width: 140
        }, {
            field: "invoice_commission",
            title: "Invoice Commission",
            editor: invoiceCommissionEditor,
            width: 160
        }, {
            field: "net_amount",
            title: "Net Amount",
            editor: netAmountEditor,
            width: 100
        }, {
            field: "cost",
            title: "Cost",
            editor: costEditor,
            width: 120
        }, {
            field: "roi_dollar",
            title: "Roi Dollar",
            editor: roiDollarEditor,
            width: 120
        }, {
            field: "roi_percent",
            title: "Roi Percent",
            editor: roiPercentEditor,
            width: 120
        }, {
            field: "status",
            title: "Status",
            width: 120
        }, ],
    });
    document.getElementById("openRefreshButton").value="Refresh : 60";
    function openCountDown(refreshMinutes) {
        function openTimer() {
            document.getElementById("openRefreshButton").value="Refresh : " + refreshMinutes;
            refreshMinutes--;
            if (refreshMinutes == 0) {
                localStorage.setItem('resetSortValue','undefined');
                $('#openGrid').data('kendoGrid').dataSource.read();
            } else {
                var openTimeoutId = setTimeout(openTimer, 1000);
                $("#openTimeoutId").val(openTimeoutId);
            }
        }
        openTimer();
    }
    $("#openRefreshButton").on("click", function(e) {
        e.preventDefault();
        var refreshValue = this.value;
        var regex = /[0-9]/g;
        var refreshFound = refreshValue.match(regex);
        if(refreshFound){
            var openTimeoutId = $("#openTimeoutId").val();
            clearTimeout(openTimeoutId);
            document.getElementById("openRefreshButton").value="Refresh: paused";
        } else {
            openCountDown(REFRESH_TRANSFER_TIMER_MINUTES);
        }
    });
    /* event Search */
    $("#openBtnSearch").click(function() {
        localStorage.clear();
        var eventSearchValue = $('#openSearchBox').val();
        $("#openGrid").data("kendoGrid").dataSource.filter({
            logic: "or",
            filters: [{
                field: "category_name",
                operator: "contains",
                value: eventSearchValue
            }, {
                field: "invoice_id",
                operator: "contains",
                value: eventSearchValue
            }, {
                field: "sales_date",
                operator: "contains",
                value: eventSearchValue
            }, {
                field: "exchange_id",
                operator: "contains",
                value: eventSearchValue
            }, {
                field: "listing_id",
                operator: "contains",
                value: eventSearchValue
            }, {
                field: "exchange_name",
                operator: "contains",
                value: eventSearchValue
            }, {
                field: "event_name",
                operator: "contains",
                value: eventSearchValue
            }, {
                field: "event_time",
                operator: "contains",
                value: eventSearchValue
            }, {
                field: "venue_name",
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
                field: "start_end",
                operator: "contains",
                value: eventSearchValue
            }, {
                field: "payment_type",
                operator: "contains",
                value: eventSearchValue
            }, {
                field: "status",
                operator: "contains",
                value: eventSearchValue
            }, ]
        });
    });
    $('#openSearchBox').keypress(function(e) {
        if (e.which == 13) { //Enter key pressed
            $('#openBtnSearch').click(); //Trigger search button click event
        }
    });
    //Clearing the filter
    $("#openBtnReset").click(function() {
        $('#openSearchBox').val("");
        localStorage.setItem('resetFilterValue','reset');
        $("#openGrid").data("kendoGrid").dataSource.filter({});
    });
});

