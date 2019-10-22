var token = $('input[name="_token"]').val();
var productionRequestUrl = "production_criteria";

function productionIdEditor(container, options) {
    $("<input name='" + options.field + "' required type='text' data-text-field='production_name' data-value-field='production_id' data-bind='value:" + options.field + "' />").appendTo(container).kendoComboBox({
        autoBind: true,
        dataTextField: "production_name",
        dataValueField: "production_id",
        dataSource: {
            transport: {
                read: {
                    dataType: 'json',
                    url: productionRequestUrl + "/getProductionIdList",
                    type: "GET"
                }
            }
        }
    });
}

function waveIdEditor(container, options) {
    $("<input name='" + options.field + "'/>").appendTo(container).kendoNumericTextBox({
        min: 0,
        step: 1,
        decimals: 0
    });
    var tooltipElement = $("<span class='k-invalid-msg' data-for='" + options.field + "'></span>");
    tooltipElement.appendTo(container);
}

function criteriaReferenceIdEditor(container, options) {
    $("<input name='" + options.field + "'/>").appendTo(container).kendoNumericTextBox({
        min: 0,
        step: 1,
        decimals: 0
    });
    var tooltipElement = $("<span class='k-invalid-msg' data-for='" + options.field + "'></span>");
    tooltipElement.appendTo(container);
}

function criteriaReferencePercentEditor(container, options) {
    $("<input name='" + options.field + "'/>").appendTo(container).kendoNumericTextBox({
        min: 0,
        step: 0.00000001,
        decimals: 8
    });
    var tooltipElement = $("<span class='k-invalid-msg' data-for='" + options.field + "'></span>");
    tooltipElement.appendTo(container);
}

function minQuantityEditor(container, options) {
    $("<input name='" + options.field + "'/>").appendTo(container).kendoNumericTextBox({
        min: 0,
        step: 1,
        decimals: 0
    });
    var tooltipElement = $("<span class='k-invalid-msg' data-for='" + options.field + "'></span>");
    tooltipElement.appendTo(container);
}

function maxQuantityEditor(container, options) {
    $("<input name='" + options.field + "'/>").appendTo(container).kendoNumericTextBox({
        min: 0,
        step: 1,
        decimals: 0
    });
    var tooltipElement = $("<span class='k-invalid-msg' data-for='" + options.field + "'></span>");
    tooltipElement.appendTo(container);
}

function deliveryIdEditor(container, options) {
    $("<input name='" + options.field + "'/>").appendTo(container).kendoNumericTextBox({
        min: 0,
        step: 1,
        decimals: 0
    });
    var tooltipElement = $("<span class='k-invalid-msg' data-for='" + options.field + "'></span>");
    tooltipElement.appendTo(container);
}

function externalIdEditor(container, options) {
    $("<textarea data-bind='value:" + options.field + "' ></textarea>").appendTo(container);
}

function externalReferenceIdEditor(container, options) {
    $("<textarea data-bind='value:" + options.field + "' ></textarea>").appendTo(container);
}

function externalReferencePercentEditor(container, options) {
    $("<input name='" + options.field + "'/>").appendTo(container).kendoNumericTextBox({
        min: 0,
        step: 0.00000001,
        decimals: 8
    });
    var tooltipElement = $("<span class='k-invalid-msg' data-for='" + options.field + "'></span>");
    tooltipElement.appendTo(container);
}

function brokerIdsEditor(container, options) {
    $("<input name='" + options.field + "' required type='text' data-text-field='broker_name' data-value-field='broker_id' data-bind='value:" + options.field + "' />").appendTo(container).kendoDropDownList({
        autoBind: true,
        dataTextField: "broker_name",
        dataValueField: "broker_id",
        dataSource: {
            transport: {
                read: {
                    dataType: 'json',
                    url: productionRequestUrl + "/getBrokerIdsList",
                    type: "GET"
                }
            }
        }
    });
}

function purchaseWaitEditor(container, options) {
    $("<input name='" + options.field + "'/>").appendTo(container).kendoNumericTextBox({
        min: 0,
        step: 0.00000001,
        decimals: 8
    });
    var tooltipElement = $("<span class='k-invalid-msg' data-for='" + options.field + "'></span>");
    tooltipElement.appendTo(container);
}
function minPriceEditor(container, options) {
    $("<input name='" + options.field + "'/>").appendTo(container).kendoNumericTextBox({
        min: 0,
        step: 0.00000001,
        decimals: 8
    });
    var tooltipElement = $("<span class='k-invalid-msg' data-for='" + options.field + "'></span>");
    tooltipElement.appendTo(container);
}

function maxPriceEditor(container, options) {
    $("<input name='" + options.field + "'/>").appendTo(container).kendoNumericTextBox({
        min: 0,
        step: 0.00000001,
        decimals: 8
    });
    var tooltipElement = $("<span class='k-invalid-msg' data-for='" + options.field + "'></span>");
    tooltipElement.appendTo(container);
}

function criteriaRangeEditor(container, options) {
    $("<textarea data-bind='value:" + options.field + "' ></textarea>").appendTo(container);
}

function popupAction(gridRowObject) {
    var dataItem = this.dataItem($(gridRowObject.currentTarget).closest("tr"));
    document.getElementById("modalContentData").innerHTML = JSON.stringify(dataItem, null, 2);
    $("#top_modal").modal("show");
}
$(document).ready(function() {
    $(function() {
        var grid = $("#productionCriteriaGrid").data("kendoGrid");
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
    var productionCriteriaGrid = jQuery("#productionCriteriaGrid").kendoGrid({
        dataSource: {
            serverPaging: true,
            serverFiltering: true,
            serverSorting: false,
            pageSize: 10,
            autoSync: true,
            /*__module_group_by_action__*/
            transport: {
                read: {
                    data: {
                        _token: token
                    },
                    url: productionRequestUrl + "/getProductionCriteria",
                    dataType: "json",
                    type: "POST"
                },
                update: {
                    data: {
                        _token: token
                    },
                    url: productionRequestUrl + "/updateProductionCriteria",
                    dataType: "jsonp",
                    type: "POST"
                },
                destroy: {
                    data: {
                        _token: token
                    },
                    url: productionRequestUrl + "/deleteProductionCriteria",
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
                    var productionCriteriaGrid = $("#productionCriteriaGrid").data("kendoGrid");
                    productionCriteriaGrid.dataSource.read();
                }
            },
            schema: {
                total: 'total',
                data: 'productionCriteria',
                model: {
                    id: 'criteria_id',
                    fields: {
                        criteria_id: {
                            editable: false
                        },
                        venue_group_id: {
                            type: "number"
                        },
                        wave_id: {
                            type: "number"
                        },
                        criteria_reference_id: {
                            type: "number"
                        },
                        min_quantity: {
                            type: "number"
                        },
                        max_quantity: {
                            type: "number"
                        },
                        delivery_id: {
                            type: "number"
                        },
                        external_id: {
                            validation: {
                                maxlength: function(input) {
                                    if (input.val().length != 0) {
                                        if (input.val().length > 10) {
                                            input.attr("data-maxlength-msg", "Max length is 10");
                                            return false;
                                        }
                                    }
                                    return true;
                                }
                            }
                        },
                        external_reference_id: {
                            validation: {
                                maxlength: function(input) {
                                    if (input.val().length != 0) {
                                        if (input.val().length > 10) {
                                            input.attr("data-maxlength-msg", "Max length is 10");
                                            return false;
                                        }
                                    }
                                    return true;
                                }
                            }
                        },
                        broker_ids: {
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
                        purchase_wait: {
                            type: "number"
                        },
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
            pageSizes: true
        },
        toolbar: [{
            template: kendo.template($("#productionCriteriaHeaderAction").html())
        }],
        detailInit: initVenueCriteriaDetail,
        scrollable: true,
        sortable: false,
        reorderable: true,
        groupable: true,
        resizable: true,
        editable: false,
        columnMenu: true,
        columns: [{
            headerTemplate: "<input type='checkbox' class='allSelectRow' />",
            template: "<input type='checkbox' class='selectRow' data-bind='checked: checked' primaryKey='#=criteria_id#' />",
            width: "25px",
            filterable: false
        }, {
            field: "criteria_id",
            title: "Criteria Id",
            width: 80
        }, {
            field: "production_name",
            title: "Production Name",
            template: "#:production_name#",
            editor: productionIdEditor,
            hidden: false,
            width: 120
        }, {
            field: "event_date",
            title: "Event Date",
            width: 120
        }, {
            field: "production_id",
            title: "Production Id",
            width: 120
        }, {
            field: "opponent_name",
            title: "Opponent Name",
            width: 120
        }, {
            field: "criteria_range",
            title: "Criteria Range",
            width: 120
        }, {
            field: "wave_id",
            title: "Wave Id",
            editor: waveIdEditor,
            width: 100
        }, {
            field: "criteria_reference_id",
            title: "Criteria Reference Id",
            editor: criteriaReferenceIdEditor,
            width: 140
        }, {
             field: "criteria_reference_percent",
            title: "Criteria Reference Percent",
            editor: criteriaReferencePercentEditor,
            width: 180
        }, { 
            field: "min_quantity",
            title: "Min Quantity",
            editor: minQuantityEditor,
            width: 110
        }, {
            field: "max_quantity",
            title: "Max Quantity",
            editor: maxQuantityEditor,
            hidden: true,
            width: 110
        }, {
            field: "delivery_id",
            title: "Delivery Id",
            editor: deliveryIdEditor,
            width: 110
        }, {
            field: "external_id",
            title: "External Id",
            width: 100
        }, {
            field: "external_reference_id",
            title: "External Reference Id",
            width: 140,
            editor: externalReferenceIdEditor
        }, {
            field: "external_reference_percent",
            title: "External Reference Percent",
            editor: externalReferencePercentEditor,
            width: 180
        }, {
            field: "broker_ids",
            title: "Broker Ids",
            width: 110
        }, {
            field: "purchase_wait",
            title: "Purchase Wait",
            editor: purchaseWaitEditor,
            width: 120
        }, {
            field: "min_price",
            title: "Min Price",
            editor: minPriceEditor,
            width: 100
        }, {
            field: "max_price",
            title: "Max Price",
            editor: maxPriceEditor,
            width: 100
        }],
    });
    function initVenueCriteriaDetail(productionDetails) {
        var venue_group_id = productionDetails.data.venue_group_id;
        $("<div style='width:40%'/>").appendTo(productionDetails.detailCell).kendoGrid({
            dataSource: {
                pageSize: 100,
                serverFiltering: true,
                transport: {
                    read: {
                        data: {
                            _token: token
                        },
                        url: productionRequestUrl + "/venueCriteriaDetails?venue_group_id=" + venue_group_id,
                        dataType: "json",
                        type: "POST"
                    }
                },
                schema: {
                    total: 'total',
                    data: 'venue_details',
                    model: {
                        id: 'criteria_id'
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
            columns: [{
                field: "venue_id",
                title: "Venue",
                template: "#=venue_name#",
                groupHeaderTemplate: "Venue: #= getVenueName(value) #"
            }, {
                field: "section",
                title: "Section"
            }, {
                field: "row",
                title: "Row"
            }, {
                field: "minQuantity",
                title: "Min Quantity",
            }, {
                field: "deliveryId",
                title: "Delivery Id",
            }]
        });
    }
    $("#productionCriteriaGrid").on("click", ".allSelectRow", function(eventData) {
        var checkedData = eventData.target.checked;
        $(".selectRow").each(function(idx, item) {
            if (checkedData) {
                if (!$(this).prop("checked") == true) {
                    $(this).click();
                }
            } else {
                if ($(this).prop("checked") == true) {
                    $(this).click();
                }
            }
        });
    });
    $("#productionCriteriaGrid").on("click", ".selectRow", function(eventData) {
        var numChkBoxes = $(".selectRow").length;
        var numChkBoxesChecked = $(".selectRow:checkbox:checked").length;
        if (numChkBoxes == numChkBoxesChecked && numChkBoxes > 0) {
            $(".allSelectRow").prop("checked", true);
        } else {
            $(".allSelectRow").prop("checked", false);
        }
    });
    /* event Search */
    $("#productionCriteriaBtnSearch").click(function() {
        var eventSearchValue = $('#productionCriteriaSearchBox').val();
        $("#productionCriteriaGrid").data("kendoGrid").dataSource.filter({
            logic: "or",
            filters: [{
                field: "criteria_id",
                operator: "contains",
                value: eventSearchValue
            }, {
                field: "production_name",
                operator: "contains",
                value: eventSearchValue
            }, {
                field: "event_date",
                operator: "contains",
                value: eventSearchValue
            },{
                field: "production_id",
                operator: "contains",
                value: eventSearchValue
            }, {
                field: "opponent_name",
                operator: "contains",
                value: eventSearchValue
            }, {
                field: "criteria_range",
                operator: "contains",
                value: eventSearchValue
            }, {
                field: "wave_id",
                operator: "contains",
                value: eventSearchValue
            }, {
                field: "criteria_reference_id",
                operator: "contains",
                value: eventSearchValue
            }, {
                field: "criteria_reference_percent",
                operator: "contains",
                value: eventSearchValue
            }, {
                field: "min_quantity",
                operator: "contains",
                value: eventSearchValue
            }, {
                field: "max_quantity",
                operator: "contains",
                value: eventSearchValue
            }, {
                field: "delivery_id",
                operator: "contains",
                value: eventSearchValue
            }, {
                field: "external_id",
                operator: "contains",
                value: eventSearchValue
            }, {
                field: "external_reference_id",
                operator: "contains",
                value: eventSearchValue
            }, {
                field: "external_reference_percent",
                operator: "contains",
                value: eventSearchValue
            }, {
                field: "broker_ids",
                operator: "contains",
                value: eventSearchValue
            }, {
                field: "purchase_wait",
                operator: "contains",
                value: eventSearchValue
            }, {
                field: "min_price",
                operator: "contains",
                value: eventSearchValue
            }, {
                field: "max_price",
                operator: "contains",
                value: eventSearchValue
            }, ]
        });
    });
    $('#productionCriteriaSearchBox').keypress(function(e) {
        if (e.which == 13) { //Enter key pressed
            $('#productionCriteriaBtnSearch').click(); //Trigger search button click event
        }
    });
    //Clearing the filter
    $("#productionCriteriaBtnReset").click(function() {
        $('#productionCriteriaSearchBox').val("");
        $("#productionCriteriaGrid").data("kendoGrid").dataSource.filter({});
    });
    /* calendar Search end*/
});

function submitBatchData(eventType) {
    $('.content .preloader').show();
    $('.content .preloader img').show();
    var gridObject = jQuery("#productionCriteriaGrid").data('kendoGrid');
    var gridObjectData = gridObject.dataSource.view();
    var checkedData = [];
    for (var initData = 0; initData < gridObjectData.length; initData++) {
        dataItem = gridObject.table.find("tr[data-uid='" + gridObjectData[initData].uid + "']");
        var checkbox = dataItem.find('.selectRow');
        if (eventType == 'selected') {
            if (checkbox.is(":checked")) {
                checkedData.push(checkbox.attr("primaryKey"));
            }
        } else {
            checkedData.push(checkbox.attr("primaryKey"));
        }
    }
    if (typeof checkedData !== 'undefined' && checkedData.length > 0) {
        console.log(checkedData);
    } else {
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
        var $toast = toastr["error"]("", "Please select at least one row");
    }
    $('.content .preloader').hide();
    $('.content .preloader img').hide();
}
/*__kendo_group_by_function__*/