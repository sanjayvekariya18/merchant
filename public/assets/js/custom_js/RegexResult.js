$(document).ready(function() {
    var requestUrl = $("#requestUrl").val();
    var token = $('input[name="_token"]').val();
    var regexResultGrid = $("#regexResultGrid").data("kendoGrid");
    var websiteUrlAutoComplete = $("#website_uri").data("kendoAutoComplete");
    $("#identity_table").kendoDropDownList({
        placeholder: "Identity Table ...",
        dataTextField: "table_code",
        dataValueField: "type_id",
        filter: "contains",
        height: 400,
        dataSource: {
            transport: {
                read: {
                    dataType: "json",
                    url: requestUrl + "/getIdentityTables",
                    type: "GET"
                }
            }
        },
        change: function(e) {
            var tableId = this.value();
            var tableCode = this.text();
            var websiteDataSource = new kendo.data.DataSource({
                transport: {
                    read: {
                        url: requestUrl + "/getWebsiteUrl?table_code=" + tableCode,
                        dataType: "json",
                        type: "GET"
                    }
                },
                schema: {
                    model: {
                        id: 'identity_id'
                    },
                },
            });
            $("#website_uri").data("kendoAutoComplete").setDataSource(websiteDataSource);
            var identityId = $('#identity_id').val();
            var labelId = $("#label_id").val();
            var resultDataSource = new kendo.data.DataSource({
                serverPaging: true,
                pageSize: 20,
                transport: {
                    read: {
                        url: requestUrl + "/getPatternRegexResult?table_id=" + tableId + "&identity_id=" + identityId + "&label_id=" + labelId,
                        dataType: "json",
                        type: "GET"
                    }
                },
                schema: {
                    total: 'total',
                    data: 'regex_result',
                    model: {
                        id: 'result_id'
                    },
                },
                serverFiltering: true,
            });
            $("#regexResultGrid").data("kendoGrid").setDataSource(resultDataSource);
        }
    }).data("kendoDropDownList");
    $('#identity_table').data('kendoDropDownList').value("56");
    $("#website_uri").kendoAutoComplete({
        placeholder: "Website URL ...",
        minLength: 1,
        dataTextField: "identity_website",
        filter: "contains",
        select: onWebsiteSelect,
        filtering: onFiltering,
        dataSource: {
            transport: {
                read: {
                    dataType: "json",
                    url: requestUrl + "/getWebsiteUrl?table_code=identity_website",
                    type: "GET"
                },
            },
        }
    }).data("kendoAutoComplete");

    function onFiltering(e) {
        $('#identity_id').val('');
        var tableId = $("#identity_table").val();
        var identityId = $("#identity_id").val();
        var labelId = $("#label_id").val();
        var resultDataSource = new kendo.data.DataSource({
            serverPaging: true,
            pageSize: 20,
            transport: {
                read: {
                    url: requestUrl + "/getPatternRegexResult?table_id=" + tableId + "&identity_id=" + identityId + "&label_id=" + labelId,
                    dataType: "json",
                    type: "GET"
                }
            },
            schema: {
                total: 'total',
                data: 'regex_result',
                model: {
                    id: 'result_id'
                },
            },
            serverFiltering: true,
        });
        $("#regexResultGrid").data("kendoGrid").setDataSource(resultDataSource);
    }

    function onWebsiteSelect(e) {
        var selectedOne = this.dataItem(e.item.index());
        var identityId = selectedOne.identity_id;
        var websiteUrl = selectedOne.identity_website;
        $('#identity_id').val(identityId);
        var tableId = $("#identity_table").val();
        var labelId = $("#label_id").val();
        var resultDataSource = new kendo.data.DataSource({
            serverPaging: true,
            pageSize: 20,
            transport: {
                read: {
                    url: requestUrl + "/getPatternRegexResult?table_id=" + tableId + "&identity_id=" + identityId + "&label_id=" + labelId,
                    dataType: "json",
                    type: "GET"
                }
            },
            schema: {
                total: 'total',
                data: 'regex_result',
                model: {
                    id: 'result_id'
                },
            },
            serverFiltering: true,
        });
        $("#regexResultGrid").data("kendoGrid").setDataSource(resultDataSource);
    }
    $("#label_id").kendoComboBox({
        placeholder: "Category Label ...",
        dataTextField: "name",
        dataValueField: "name_id",
        filter: "contains",
        height: 400,
        dataSource: {
            transport: {
                read: {
                    dataType: "json",
                    url: requestUrl + "/getRegexCategories",
                    type: "GET"
                }
            }
        },
        change: function(e) {
            var tableId = $("#identity_table").val();
            var identityId = $('#identity_id').val();
            var labelId = this.value();
            var resultDataSource = new kendo.data.DataSource({
                serverPaging: true,
                pageSize: 20,
                transport: {
                    read: {
                        url: requestUrl + "/getPatternRegexResult?table_id=" + tableId + "&identity_id=" + identityId + "&label_id=" + labelId,
                        dataType: "json",
                        type: "GET"
                    }
                },
                schema: {
                    total: 'total',
                    data: 'regex_result',
                    model: {
                        id: 'result_id'
                    },
                },
                serverFiltering: true,
            });
            $("#regexResultGrid").data("kendoGrid").setDataSource(resultDataSource);
        }
    }).data("kendoComboBox");
    var regexResultPattern = jQuery("#regexResultGrid").kendoGrid({
        dataSource: {
            serverPaging: true,
            pageSize: 20,
            transport: {
                read: {
                    url: requestUrl + "/getPatternRegexResult",
                    dataType: "json",
                    type: "GET",
                },
            },
            schema: {
                total: 'total',
                data: 'regex_result',
                model: {
                    id: 'result_id',
                    fields: {
                        identity_table: {
                            editable: false
                        },
                        website_url: {
                            editable: false
                        },
                        regex_field: {
                            editable: false
                        },
                        regex_name: {
                            editable: false
                        },
                        regex_pattern: {
                            editable: false
                        },
                        result_text: {
                            editable: false
                        }
                    }
                },
            },
            serverFiltering: true,
        },
        pageable: {
            refresh: true,
            pageSizes: true
        },
        scrollable: true,
        autoSync: true,
        sortable: true,
        reorderable: true,
        serverFiltering: true,
        groupable: true,
        resizable: true,
        editable: true,
        columns: [{
            field: "result_id",
            title: "Result Id",
            hidden: true
        }, {
            field: "identity_table",
            title: "Identity Table",
            width: 70
        }, {
            field: "website_url",
            title: "Website",
            template: kendo.template(jQuery('#website_url_template').html()),
            width: 80
        }, {
            field: "regex_field",
            title: "Regex Field",
            width: 75
        }, {
            field: "regex_name",
            title: "Regex Label",
            width: 75
        }, {
            field: "regex_pattern",
            title: "Regex Pattern",
            width: 75
        }, {
            field: "result_text",
            title: "Result",
            template: kendo.template(jQuery('#regex_result_template').html()),
            width: 100
        }],
    });
});