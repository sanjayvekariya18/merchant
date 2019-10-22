var constant = {
    ACTION_URL: $('#request_url').val(),
    TOKEN: $('input[name="_token"]').val()
}
$(document).ready(function() {
    var token = $('input[name="_token"]').val();
    var requestUrl = $("#requestUrl").val();
    $("#tabstrip").kendoTabStrip().data("kendoTabStrip");
    var proxyNodeSourceListGrid = jQuery("#proxyNodeSourceList").kendoGrid({
        dataSource: {
            serverPaging: true,
            serverFiltering: true,
            serverSorting: true,
            pageSize: 20,
            transport: {
                read: {
                    url: requestUrl + "/proxyNodeSourceList",
                    dataType: "json",
                    type: "GET"
                }
            },
            schema: {
                total: 'total',
                data: 'proxy_node_source',
                model: {
                    id: 'proxy_id',
                    fields: {
                        proxy_id: {
                            editable: false
                        },
                        proxy_source_ip: {
                            editable: false
                        },
                        proxy_target_ip: {
                            editable: false
                        },
                        proxy_port: {
                            editable: false
                        },
                        proxy_beg_date: {
                            editable: false
                        },
                        proxy_beg_time: {
                            editable: false
                        },
                        proxy_service: {
                            editable: false
                        },
                        proxy_type: {
                            editable: false
                        },
                        proxy_state: {
                            editable: true
                        },
                        old_state: {
                            editable: false
                        },
                        request_date: {
                            editable: false
                        },
                        request_time: {
                            editable: false
                        },
                        request_duration: {
                            editable: false
                        },
                        proxy_status: {
                            editable: false
                        },
                        proxy_onbehalf: {
                            editable: false
                        },
                        status_name: {
                            defaultValue: {
                                status_name: "new"
                            }
                        },
                    }
                },
            }
        },
        noRecords: true,
        messages: {
            noRecords: "There is no data on current page"
        },
        pageable: {
            refresh: true,
            pageSizes: true
        },
        scrollable: true,
        autoSync: true,
        sortable: true,
        reorderable: true,
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
        groupable: false,
        resizable: true,
        editable: 'incell',
        detailInit: detailInit,
        dataBound: function(proxyDetails) {
            var dataSource = this.dataSource;
            this.element.find('tr.k-master-row').each(function() {
                var row = $(this);
                var data = dataSource.getByUid(row.data('uid'));
                if (data.proxy_details_id == 0 && data.location_id == null) {
                    row.find('.k-hierarchy-cell a').remove();
                }
            });
        },
        save: function(e) {
            jQuery.ajax({
                type: 'GET',
                data: {
                    proxy_id: e.model.proxy_id,
                    status_name: e.model.proxy_state,
                    current_status: e.values.proxy_state,
                },
                url: requestUrl + "/updateProxyDetailsStatus",
                success: function(statusMessage) {
                    $("#proxyNodeSourceList").data("kendoGrid").dataSource.read();
                }
            });
        },
        columns: [{
            field: "proxy_id",
            title: "ID",
            filterable: false,
            width: 50
        }, {
            field: "proxy_source_ip",
            title: "Source",
            width: 100
        }, {
            field: "proxy_target_ip",
            title: "Target",
            width: 100
        }, {
            field: "proxy_port",
            title: "Port",
            width: 80
        }, {
            field: "proxy_beg_date",
            title: "Begin Date",
            filterable: false,
            width: 90
        }, {
            field: "proxy_beg_time",
            title: "Begin Time",
            filterable: false,
            width: 80
        }, {
            field: "proxy_type",
            title: "Type",
            width: 80
        }, {
            field: "proxy_status",
            title: "Proxy Status",
            filterable: true,
            width: 120
        }, {
            field: "proxy_onbehalf",
            title: "Proxy Onbehalf",
            filterable: true,
            width: 130
        }, {
            field: "proxy_state",
            title: "Proxy State",
            editor: statusDropDownEditor,
            width: 120
        }, {
            field: "old_state",
            title: "Old State",
            width: 120
        }, {
            field: "request_date",
            title: "Request Date",
            width: 120
        }, {
            field: "request_time",
            title: "Request Time",
            width: 120
        }, {
            field: "request_duration",
            title: "Request Duration",
            width: 140
        }]
    });
    var proxyNodeSourceListGrid = $("#proxyNodeSourceList").data("kendoGrid");
    var proxyStatusColorListGrid = jQuery("#proxyStatusColorListGrid").kendoGrid({
        pageable: {
            refresh: true,
            pageSizes: true
        },
        scrollable: true,
        autoSync: true,
        sortable: true,
        reorderable: true,
        serverFiltering: true,
        editable: "incell",
        columns: [{
            field: "status_id",
            title: "Status Id",
            width: 10
        }, {
            field: "proxy_status_color",
            width: 20,
            title: "Status Color",
            template: function(dataItem) {
                return "<div style='background-color: " + dataItem.proxy_status_color + ";'>&nbsp;</div>";
            },
            editor: function(container, options) {
                var proxy_status_id = options.model.status_id;
                var input = $("<input/>");
                input.attr("name", options.field);
                input.appendTo(container);
                input.kendoColorPicker({
                    value: options.model.proxy_status_color,
                    buttons: false,
                    select: function(e) {
                        proxy_status_color_code = e.value;
                        jQuery("#background").css("background-color", e.value);
                        jQuery.ajax({
                            type: 'GET',
                            data: {
                                proxy_status_id: proxy_status_id,
                                proxy_status_color_code: proxy_status_color_code,
                            },
                            url: requestUrl + "/updateProxyStatusColorCode",
                            success: function(statusMessage) {
                                proxyColorStatusListGrid.dataSource.read();
                            }
                        });
                    },
                });
            }
        }, {
            field: "status_name",
            title: "Status Name",
            width: 80
        }]
    });
    var proxyColorStatusListGrid = $("#proxyStatusColorListGrid").data("kendoGrid");
    var proxyColorStatusListDataSource = new kendo.data.DataSource({
        pageSize: 10,
        transport: {
            read: {
                data: {
                    _token: token
                },
                url: requestUrl + '/getProxyStatusColorList',
                dataType: "json",
                type: "GET"
            }
        },
        schema: {
            model: {
                id: 'status_id',
                fields: {
                    status_name: {
                        editable: false
                    },
                    proxy_status_color: {
                        editable: true
                    },
                }
            }
        }
    });
    proxyColorStatusListGrid.setDataSource(proxyColorStatusListDataSource);

    function statusDropDownEditor(container, options) {
        $('<input data-text-field="status_name" data-value-field="status_name" data-bind="value:' + options.field + '"/>').appendTo(container).kendoDropDownList({
            dataSource: {
                transport: {
                    read: {
                        dataType: "json",
                        url: requestUrl + "/getProxyStatusList",
                        type: "GET"
                    }
                }
            },
            dataTextField: "status_name",
            dataValueField: "status_name"
        });
    }

    function detailInit(proxyDetails) {
        console.log(proxyDetails.data.location_id);
        if (proxyDetails.data.location_id != null) {
            $("<div/>").appendTo(proxyDetails.detailCell).kendoGrid({
                dataSource: {
                    pageSize: 5,
                    transport: {
                        read: {
                            url: requestUrl + "/proxyLocationDetails" + "/" + proxyDetails.data.proxy_id,
                            type: "GET"
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
                dataBound: function() {
                    $('.k-detail-cell .k-display-block').css("margin-bottom", "10px");
                },
                columns: [{
                    field: "country_flag",
                    title: "Flag",
                    template: "<img src='#=data.country_flag#' width=20 height=20/>",
                    width: 10
                }, {
                    field: "country_code",
                    title: "Country Code",
                    width: 20
                }, {
                    field: "country_name",
                    title: "Country Name",
                    width: 20
                }, {
                    field: "region_code",
                    title: "Region Code",
                    width: 20
                }, {
                    field: "region_name",
                    title: "Region Name",
                    width: 20
                }, {
                    field: "city",
                    title: "City",
                    width: 20
                }, {
                    field: "zip",
                    title: "Zipcode",
                    width: 20
                }]
            });
        }
        if (proxyDetails.data.proxy_details_id == 1) {
            $("<div/>").appendTo(proxyDetails.detailCell).kendoGrid({
                dataSource: {
                    pageSize: 5,
                    transport: {
                        read: {
                            url: requestUrl + "/proxySummeryDetails" + "/" + proxyDetails.data.proxy_id,
                            type: "GET"
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
                    field: "proxy_summery_status",
                    title: "Status",
                    width: 70,
                }, {
                    field: "request_date",
                    title: "Date",
                    width: 70,
                }, {
                    field: "request_time",
                    title: "Time",
                    width: 70,
                }, {
                    field: "request_duration",
                    title: "Duration",
                    width: 50,
                }, {
                    field: "response_code",
                    title: "Code",
                    width: 50,
                }, {
                    field: "response_reason",
                    title: "Reason",
                    width: 100,
                }]
            });
        }
    }
});