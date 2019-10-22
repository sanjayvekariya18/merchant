var token = $('input[name="_token"]').val();
var requestUrl = $("#requestUrl").val();
$("#tabstrip").kendoTabStrip().data("kendoTabStrip");
$('.venue_criteria').click(function() {
    $('#venue_criteria_top_modal').modal("show");
});

function venue_idEditor(container, options) {
    $("<input name='" + options.field + "' required type='text' data-text-field='venue_name' data-value-field='venue_id' data-bind='value:" + options.field + "' />").appendTo(container).kendoDropDownList({
        autoBind: true,
        dataTextField: "venue_name",
        dataValueField: "venue_id",
        dataSource: {
            transport: {
                read: {
                    dataType: 'json',
                    url: requestUrl + "/getvenue_idList",
                    type: "GET"
                }
            }
        }
    });
}

function group_idEditor(container, options) {
    $("<input name='" + options.field + "'/>").appendTo(container).kendoNumericTextBox({
        min: 0,
        step: 1,
        decimals: 0
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

function deliveryIdEditor(container, options) {
    $("<input name='" + options.field + "'/>").appendTo(container).kendoNumericTextBox({
        min: 0,
        step: 1,
        decimals: 0
    });
    var tooltipElement = $("<span class='k-invalid-msg' data-for='" + options.field + "'></span>");
    tooltipElement.appendTo(container);
}
/*__input_action_funcation__*/
$(document).ready(function() {
    $(function() {
        var grid = $("#Venue_criteriaGrid").data("kendoGrid");
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
    var Venue_criteriaGrid = jQuery("#Venue_criteriaGrid").kendoGrid({
        dataSource: {
            serverPaging: true,
            serverFiltering: true,
            serverSorting: false,
            group: [{
                field: "venue_id",
                dir: "asc"
            }, {
                field: "group_id",
                dir: "asc"
            }, {
                field: "section",
                dir: "asc"
            }],
            pageSize: 50,
            autoSync: true,
            transport: {
                read: {
                    data: {
                        _token: token
                    },
                    url: requestUrl + "/getVenue_criteria",
                    dataType: "json",
                    type: "POST"
                },
                update: {
                    data: {
                        _token: token
                    },
                    url: requestUrl + "/updateVenue_criteria",
                    dataType: "jsonp",
                    type: "POST"
                },
                destroy: {
                    data: {
                        _token: token
                    },
                    url: requestUrl + "/deleteVenue_criteria",
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
                    var Venue_criteriaGridGrid = $("#Venue_criteriaGrid").data("kendoGrid");
                    Venue_criteriaGridGrid.dataSource.read();
                }
            },
            schema: {
                total: 'total',
                data: 'Venue_criteria',
                model: {
                    id: 'criteria_id',
                    fields: {
                        group_id: {
                            type: "number"
                        },
                        section: {
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
                        minQuantity: {
                            type: "number"
                        },
                        deliveryId: {
                            type: "number"
                        },
                    },
                },
            },
        },
        pageable: {
            refresh: true,
            pageSizes: [50, 100, 'All']
        },
        toolbar: [{
            template: kendo.template($("#Venue_criteriaSearch").html())
        }],
        scrollable: true,
        sortable: false,
        reorderable: true,
        groupable: true,
        resizable: true,
        editable: true,
        columnMenu: true,
        columns: [{
            headerTemplate: "<input type='checkbox' class='allSelectRow' />",
            template: "<input type='checkbox' class='selectRow' data-bind='checked: checked' primaryKey='#=criteria_id#' />",
            width: "25px",
            filterable: false
        }, {
            field: "venue_id",
            title: "Venue",
            template: "#=venue_name#",
            editor: venue_idEditor,
            groupHeaderTemplate: "Venue: #= getVenueName(value) #"
        }, {
            field: "group_id",
            title: "Group Id",
            editor: group_idEditor
        }, {
            field: "section",
            title: "Section"
        }, {
            field: "row",
            title: "Row"
        }, {
            field: "minQuantity",
            title: "Min Quantity",
            editor: minQuantityEditor
        }, {
            field: "deliveryId",
            title: "Delivery Id",
            editor: deliveryIdEditor
        }, ],
    });
    /* event Search */
    $("#Venue_criteriaBtnSearch").click(function() {
        var eventSearchValue = $('#Venue_criteriaSearchBox').val();
        $("#Venue_criteriaGrid").data("kendoGrid").dataSource.filter({
            logic: "or",
            filters: [{
                field: "venue_id",
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
            }, ]
        });
    });
    $('#Venue_criteriaSearchBox').keypress(function(e) {
        if (e.which == 13) { //Enter key pressed
            $('#Venue_criteriaBtnSearch').click(); //Trigger search button click event
        }
    });
    //Clearing the filter
    $("#Venue_criteriaBtnReset").click(function() {
        $('#Venue_criteriaSearchBox').val("");
        $("#Venue_criteriaGrid").data("kendoGrid").dataSource.filter({});
    });
    /* calendar Search end*/
});

function getVenueName(venueId) {
    var venueNameValue;
    $.ajax({
        type: 'POST',
        data: {
            venue_id: venueId,
            _token: token
        },
        url: requestUrl + "/getVenueName",
        async: false,
        success: function(venueObject) {
            if (venueObject.venue_name) {
                venueNameValue = venueObject.venue_name;
            }
        }
    });
    return venueNameValue;
}
$("#Venue_criteriaGrid").on("click", ".allSelectRow", function(eventData) {
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
$("#Venue_criteriaGrid").on("click", ".selectRow", function(eventData) {
    var numChkBoxes = $(".selectRow").length;
    var numChkBoxesChecked = $(".selectRow:checkbox:checked").length;
    if (numChkBoxes == numChkBoxesChecked && numChkBoxes > 0) {
        $(".allSelectRow").prop("checked", true);
    } else {
        $(".allSelectRow").prop("checked", false);
    }
});

function submitBatchData(eventType) {
    $('.content .preloader').show();
    $('.content .preloader img').show();
    var gridObject = jQuery("#Venue_criteriaGrid").data('kendoGrid');
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
$(document).ready(function() {
    $("#fileToUpload").kendoUpload();
});
$("input#fileToUpload").change(function() {
    var element = document.getElementById($(this).attr('id'));
    var result = element.files;
    for (var x = 0; x < result.length; x++) {
        var file = result[x];
        localStorage.setItem('fileName', file.name);
    }
});
$('#add_venue_criteria_form').submit(function(e) {
    $('.content .preloader').show();
    $('.content img').show();
    e.preventDefault();
    var formData = new FormData(this);
    $.ajax({
        url: requestUrl + "/criteria_json_upload",
        type: 'POST',
        data: formData,
        success: function(jsonUplaodObject) {
            $('.content .preloader,.content img').hide();
            response = jsonUplaodObject.type;
            if (response.localeCompare("success") == 0) {
                message = jsonUplaodObject.message;
            } else if (response.localeCompare("error") == 0) {
                message = jsonUplaodObject.message;
            }
            var rowString = message;
            var rowStringMatch = rowString.match(/Row/g);
            if (rowStringMatch != null) {
                var str = rowString;
                var lastIndex = str.lastIndexOf(" ");
                message = str.substring(0, lastIndex);
                var jsonUrl = $('#jsonUrl').val();
                var fileToUpload = $('#fileToUpload').val();
                var details = fileToUpload.split('/');
                var uploadedFileNameDetails = fileToUpload.split("\\");
                var uploadedFileName = uploadedFileNameDetails[uploadedFileNameDetails.length - 1];
                $('#venue_criteria_list li').show();
                var x = document.getElementById("CriteriaExceptionsGrid");
                x.style.display = "block";
                jQuery("#CriteriaExceptionsGrid").replaceWith("<div id='CriteriaExceptionsGrid'></div>");
                var CriteriaExceptionsGrid = jQuery("#CriteriaExceptionsGrid").kendoGrid({
                    dataSource: {
                        serverPaging: true,
                        serverFiltering: true,
                        serverSorting: false,
                        pageSize: 50,
                        autoSync: true,
                        transport: {
                            read: {
                                data: {
                                    _token: token,
                                    jsonUrl: jsonUrl,
                                    fileToUpload: localStorage.getItem('fileName')
                                },
                                url: requestUrl + "/getcriteria_exceptions_details",
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
                                var CriteriaJsonDetailsGridGrid = $("#CriteriaExceptionsGrid").data("kendoGrid");
                                CriteriaJsonDetailsGridGrid.dataSource.read();
                            }
                        },
                        schema: {
                            total: 'total',
                            data: 'criteria_exceptions_details',
                            model: {
                                id: 'criteria_id',
                            },
                        },
                    },
                    pageable: {
                        refresh: true,
                        pageSizes: [50, 100, 'All']
                    },
                    toolbar: [{
                        template: kendo.template($("#CriteriaJsonDetailsSearch").html())
                    }],
                    scrollable: true,
                    sortable: false,
                    reorderable: true,
                    groupable: true,
                    resizable: true,
                    editable: false,
                    columnMenu: true,
                    columns: [{
                        field: "files",
                        title: "File or Url",
                    }, {
                        field: "section",
                        title: "Section"
                    }, {
                        field: "row",
                        title: "Row"
                    }, {
                        field: "json",
                        title: "Json",
                        template: kendo.template(jQuery('#jsonDetailsTemplate').html()),
                    }, ],
                });
                /* event Search */
                $("#CriteriaJsonDetailsBtnSearch").click(function() {
                    var eventSearchValue = $('#CriteriaJsonDetailsSearchBox').val();
                    $("#CriteriaExceptionsGrid").data("kendoGrid").dataSource.filter({
                        logic: "or",
                        filters: [{
                            field: "files",
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
                            field: "json",
                            operator: "contains",
                            value: eventSearchValue
                        }, ]
                    });
                });
                $('#CriteriaJsonDetailsSearchBox').keypress(function(e) {
                    if (e.which == 13) { //Enter key pressed
                        $('#CriteriaJsonDetailsBtnSearch').click(); //Trigger search button click event
                    }
                });
                //Clearing the filter
                $("#CriteriaJsonDetailsBtnReset").click(function() {
                    $('#CriteriaJsonDetailsSearchBox').val("");
                    $("#CriteriaExceptionsGrid").data("kendoGrid").dataSource.filter({});
                });
            } else {
                if ($('#exceptionsTab:visible').length != 0) {
                    document.getElementById("venue_criteria_list").children[3].style.display = "none";
                    var x = document.getElementById("CriteriaExceptionsGrid");
                    x.style.display = "none";
                }
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
            var Venue_criteriaGridGrid = $("#Venue_criteriaGrid").data("kendoGrid");
            Venue_criteriaGridGrid.dataSource.read();
            var productionCriteriaGrid = $("#productionCriteriaGrid").data("kendoGrid");
            productionCriteriaGrid.dataSource.read();
            var Instances_eventsGrid = $("#Instances_eventsGrid").data("kendoGrid");
            Instances_eventsGrid.dataSource.read();
            $('#venue_criteria_top_modal').modal('hide');
        },
        cache: false,
        contentType: false,
        processData: false
    });
});