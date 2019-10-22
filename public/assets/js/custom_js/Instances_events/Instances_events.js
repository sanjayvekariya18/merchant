var token = $('input[name="_token"]').val();
var instancesEventsRequestUrl = "instances_events";
$(document).ready(function() {
    if (!Number.prototype.toLowerCase) {
        Number.prototype.toLowerCase = function() {
          return this.toString();
        }
    }
    var Instances_eventsGrid = jQuery("#Instances_eventsGrid").kendoGrid({
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
                    url: instancesEventsRequestUrl + "/getInstances_events",
                    dataType: "json",
                    type: "POST"
                }
            },
            schema: {
                total: 'total',
                data: 'Instances_events',
                model: {
                    id: 'id',
                    fields: {
                        production_id: {
                            editable: true
                        },
                        instance_date: {
                            editable: false
                        },
                        instance_time: {
                            editable: false
                        }, 
                        production_name: {
                            editable: false
                        },
                        start_avg_min: { 
                            type: "number" 
                        },
                        start_avg_max: { 
                            type: "number" 
                        },
                        list: { 
                            type: "number" 
                        },
                        threads: { 
                            type: "number" 
                        },
                        wait_init: { 
                            type: "number" 
                        },
                        low_price: { 
                            type: "number" 
                        },
                    }
                },
            },
        },
        pageable: {
            refresh: true,
            pageSizes: true
        },
        toolbar: [{
            template: kendo.template($("#Instances_eventsSearch").html())
        }],
        scrollable: true,
        sortable: false,
        reorderable: true,
        groupable: false,
        autoSync: true,
        resizable: true,
        editable: 'incell',
        columns: [{
            field: "id",
            title: "Id",
            hidden: true
        }, {
            field: "production_id",
            title: "Production Id",
            template: "#:production_id#",
            editor: productionIdEditor,
            width: 120
        }, {
            field: "stubhub_id",
            title: "Stubhub Id",
            template: "#:stubhub_id#",
            width: 120
        }, {
            field: "instance_date",
            title: "Instance Date",
            width: 100
        }, {
            field: "instance_time",
            title: "Instance Time",
            width: 100
        }, {
            field: "start_avg_min",
            title: "Start Avg Min",
            width: 100
        }, {
            field: "start_avg_max",
            title: "Start Avg Max",
            width: 100
        }, {
            field: "list",
            title: "List",
            width: 70
        }, {
            field: "threads",
            title: "Threads",
            width: 90
        }, {
            field: "wait_init",
            title: "Wait Init",
            width: 70
        }, {
            field: "low_price",
            title: "Low Price",
            width: 100
        }, {
            field: "production_name",
            title: "Event Name",
            width: 100
        }, ],
        save: function(data) {
            if (data.values.production_id) {
                if (data.values.production_id.production_id) {
                    production_id = data.values.production_id.production_id;
                } else {
                    production_id = data.values.production_id;
                }
            } else {
                production_id = data.model.production_id;
            }
            if (data.values.stubhub_id) {
                stubhub_id = data.values.stubhub_id;
            } else {
                stubhub_id = data.model.stubhub_id;
            }
            if (data.values.low_price) {
                low_price = data.values.low_price;
            } else {
                low_price = data.model.low_price;
            }
            if (data.values.start_avg_min) {
                start_avg_min = data.values.start_avg_min;
            } else {
                start_avg_min = data.model.start_avg_min;
            }
            if (data.values.start_avg_max) {
                start_avg_max = data.values.start_avg_max;
            } else {
                start_avg_max = data.model.start_avg_max;
            }
            if (data.values.list) {
                list = data.values.list;
            } else {
                list = data.model.list;
            }
            if (data.values.threads) {
                threads = data.values.threads;
            } else {
                threads = data.model.threads;
            }
            if (data.values.wait_init) {
                wait_init = data.values.wait_init;
            } else {
                wait_init = data.model.wait_init;
            }
            jQuery.ajax({
                type: 'POST',
                data: {
                    id: data.model.id,
                    production_id: production_id,
                    stubhub_id: stubhub_id,
                    low_price: low_price,
                    start_avg_min: start_avg_min,
                    start_avg_max: start_avg_max,
                    list: list,
                    threads: threads,
                    wait_init: wait_init,
                    _token: token
                },
                url: instancesEventsRequestUrl + "/create_instances_events",
                success: function(instanceDetails) {
                    response = instanceDetails.type;
                    if (response.localeCompare("success") == 0) {
                        message = instanceDetails.message;
                    } else if (response.localeCompare("error") == 0) {
                        message = instanceDetails.message;
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
                    var Instances_eventsGridGrid = $("#Instances_eventsGrid").data("kendoGrid");
                    Instances_eventsGridGrid.dataSource.read();
                }
            });
        },
    });
    function productionIdEditor(container, options) {
       $('<input name="' + options.field + '" required data-bind="value:' + options.field + '"/>').appendTo(container).kendoAutoComplete({
            autoBind: true,
            filter: "startswith",
            dataSource: {
                transport: {
                    read: {
                        data: {
                            _token: token
                        },
                        dataType: "json",
                        url: instancesEventsRequestUrl + "/getProductionIdList",
                        type: "POST"
                    }
                }
            },
            dataTextField: "production_id",
            placeholder: "Select Production Id"
        });
    }
    /* event Search */
    $("#Instances_eventsBtnSearch").click(function() {
        var eventSearchValue = $('#Instances_eventsSearchBox').val();
        $("#Instances_eventsGrid").data("kendoGrid").dataSource.filter({
            logic: "or",
            filters: [{
                field: "production_id",
                operator: "contains",
                value: eventSearchValue
            }, {
                field: "instance_date",
                operator: "contains",
                value: eventSearchValue
            }, {
                field: "start_avg_min",
                operator: "contains",
                value: eventSearchValue
            }, {
                field: "start_avg_max",
                operator: "contains",
                value: eventSearchValue
            }, {
                field: "list",
                operator: "contains",
                value: eventSearchValue
            }, {
                field: "threads",
                operator: "contains",
                value: eventSearchValue
            }, {
                field: "wait",
                operator: "contains",
                value: eventSearchValue
            }, {
                field: "low_price",
                operator: "contains",
                value: eventSearchValue
            }, {
                field: "production_name",
                operator: "contains",
                value: eventSearchValue
            }, ]
        });
    });
    $('#Instances_eventsSearchBox').keypress(function(e) {
        if (e.which == 13) { //Enter key pressed
            $('#Instances_eventsBtnSearch').click(); //Trigger search button click event
        }
    });
    //Clearing the filter
    $("#Instances_eventsBtnReset").click(function() {
        $('#Instances_eventsSearchBox').val("");
        $("#Instances_eventsGrid").data("kendoGrid").dataSource.filter({});
    });
    /* calendar Search end*/
});