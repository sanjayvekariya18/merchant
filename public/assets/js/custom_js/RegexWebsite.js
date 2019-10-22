$(document).ready(function() {
    $("#tabstrip").kendoTabStrip().data("kendoTabStrip");
    var requestUrl = $("#requestUrl").val();
    var token = $('input[name="_token"]').val();
    var kendoWebsiteGrid = $("#websiteListGrid").data("kendoGrid");
    var websiteListGrid = jQuery("#websiteListGrid").kendoGrid({
        dataSource: {
            serverPaging: true,
            pageSize: 20,
            transport: {
                read: {
                    url: requestUrl + '/getWebsiteList',
                    dataType: "json",
                    type: "GET",
                },
                create: {
                    data: {
                        _token: token
                    },
                    url: requestUrl + "/createWebsite",
                    dataType: "json",
                    type: "POST",
                    complete: function(data) {
                        $("#websiteListGrid").data("kendoGrid").dataSource.read();
                    }
                },
                update: {
                    data: {
                        _token: token
                    },
                    url: requestUrl + "/updateWebsite",
                    dataType: "json",
                    type: "POST",
                    complete: function(data) {
                        $("#websiteListGrid").data("kendoGrid").dataSource.read();
                    }
                },
                destroy: {
                    data: {
                        _token: token
                    },
                    url: requestUrl + "/deleteWebsite",
                    dataType: "json",
                    type: "POST",
                    complete: function(data) {
                        $("#websiteListGrid").data("kendoGrid").dataSource.read();
                    }
                },
            },
            schema: {
                total: 'total',
                data: 'regex_websites',
                model: {
                    id: 'identity_id',
                    fields: {
                        domain_name: {
                            editable: false
                        },
                        identity_website: {
                            editable: true
                        },
                        regex_block_level_id: {
                            editable: true
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
        editable: 'inline',
        toolbar: ["create"],
        columns: [{
            command: [{
                name: "edit",
                text: {
                    edit: " ",
                    update: " ",
                    cancel: " "
                }
            }, {
                name: "destroy",
                text: " "
            }],
            title: "&nbsp;",
            width: "40px"
        }, {
            template: "<button id='regexlist' class='regex k-button'>#= 'Regex ('+((data.totalRegex)?data.totalRegex:0)+')' #</button>",
            title: "Regex",
            width: "36px"
        }, {
            field: "identity_id",
            title: "Website Id",
            hidden: true,
        }, {
            field: "domain_name",
            title: "Domain",
            template: kendo.template(jQuery('#domain_template').html()),
            width: 52
        }, {
            field: "identity_website",
            title: "Website",
            template: kendo.template(jQuery('#identity_website_template').html()),
            width: 80
        }, {
            field: "regex_block_level_id",
            title: "Block",
            editor: blockLevelDropDownEditor,
            template: "#=(data.block_pattern)?data.block_pattern:''#",
            width: 60
        }, {
            template: "<button id='blocklevel' class='block k-button'>#= 'Block ('+((data.totalBlock)?data.totalBlock:0)+')' #</button>",
            title: "Block Level",
            width: "35px"
        }, {
            template: "<button id='blockelement' class='element k-button'>#= 'Element ('+((data.totalElement)?data.totalElement:0)+')' #</button>",
            title: "Block Element",
            width: "41px"
        }, {
            template: "<button id='paginationlist' class='pagination k-button'>#= 'Template ('+((data.totalPagination)?data.totalPagination:0)+')' #</button>",
            title: "Pagination",
            width: "45px"
        }, {
            template: "<button id='regexscrape' class='scrape k-button'>#= 'Scrape' #</button>",
            title: "Scrape Data",
            width: "35px"
        }],
    });

    function blockLevelDropDownEditor(container, options) {
        $('<input data-text-field="pattern" data-value-field="id" data-bind="value:' + options.field + '"/>').appendTo(container).kendoDropDownList({
            dataSource: {
                transport: {
                    read: {
                        dataType: "json",
                        url: requestUrl + "/getBlockLevel",
                        type: "GET"
                    }
                }
            },
            dataTextField: "pattern",
            dataValueField: "id"
        });
    }
    $("#regexWebsiteSearchGrid").keyup(function(e) {
        var searchValue = $('#regexWebsiteSearchGrid').val();
        var websiteDataSource = new kendo.data.DataSource({
            serverPaging: true,
            pageSize: 20,
            transport: {
                read: {
                    url: requestUrl + "/getWebsiteList?term=" + searchValue,
                    dataType: "json",
                    type: "GET"
                },
                create: {
                    data: {
                        _token: token
                    },
                    url: requestUrl + "/createWebsite",
                    dataType: "json",
                    type: "POST",
                    complete: function(data) {
                        $("#websiteListGrid").data("kendoGrid").dataSource.read();
                    }
                },
                update: {
                    data: {
                        _token: token
                    },
                    url: requestUrl + "/updateWebsite",
                    dataType: "json",
                    type: "POST",
                    complete: function(data) {
                        $("#websiteListGrid").data("kendoGrid").dataSource.read();
                    }
                },
                destroy: {
                    data: {
                        _token: token
                    },
                    url: requestUrl + "/deleteWebsite",
                    dataType: "json",
                    type: "POST",
                    complete: function(data) {
                        $("#websiteListGrid").data("kendoGrid").dataSource.read();
                    }
                }
            },
            schema: {
                total: 'total',
                data: 'regex_websites',
                model: {
                    id: 'identity_id',
                    fields: {
                        domain_name: {
                            editable: false
                        },
                        identity_website: {
                            editable: true
                        },
                        regex_block_level_id: {
                            editable: true
                        }
                    }
                },
            }
        });
        $("#websiteListGrid").data("kendoGrid").setDataSource(websiteDataSource);
    });
    var regexPatternListGrid = jQuery("#regexPatternListGrid").kendoGrid({
        dataSource: {
            serverPaging: true,
            pageSize: 15,
            schema: {
                total: 'total',
                data: 'regex_patterns',
                model: {
                    id: 'pattern_id',
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
        editable: 'inline',
        columns: [{
            selectable: true,
            width: "10px"
        }, {
            field: "pattern_id",
            title: "Pattern#",
            hidden: true,
        }, {
            field: "type_name",
            title: "Regex Type",
            width: 100
        }, {
            field: "pattern",
            title: "Regex Pattern",
            width: 150
            /*},{
            	field: "field_name",
            	title: "Regex Field",
            	width:100*/
        }],
    });
    var websiteListKendoGrid = $("#websiteListGrid").data("kendoGrid");
    var regexPatternGrid = $("#regexPatternListGrid").data("kendoGrid");
    $('#websiteListGrid').on('click', '#regexlist', function(e) {
        var dataItem = websiteListKendoGrid.dataItem($(this).closest('tr'));
        var identityId = dataItem.identity_id;
        var regexCategoryListDataSource = new kendo.data.DataSource({
            serverPaging: true,
            pageSize: 15,
            transport: {
                read: {
                    //data:{identity_id:identityId},
                    url: requestUrl + '/getRegexPatternList',
                    dataType: "json",
                    type: "GET"
                }
            },
            schema: {
                total: 'total',
                data: 'regex_patterns',
                model: {
                    id: 'pattern_id',
                },
            },
            serverFiltering: true,
        });
        regexPatternGrid.setDataSource(regexCategoryListDataSource);
        $("#type_id").kendoComboBox({
            placeholder: "Regex Type ...",
            dataTextField: "type_name",
            dataValueField: "type_id",
            filter: "contains",
            height: 400,
            dataSource: {
                transport: {
                    read: {
                        dataType: "json",
                        url: requestUrl + "/getRegexTypes",
                        type: "GET"
                    }
                }
            },
            change: function(e) {
                var type_id = this.value();
                var patternDataSource = new kendo.data.DataSource({
                    serverPaging: true,
                    pageSize: 15,
                    transport: {
                        read: {
                            url: requestUrl + "/getRegexPatternList?type_id=" + type_id,
                            dataType: "json",
                            type: "GET"
                        }
                    },
                    schema: {
                        total: 'total',
                        data: 'regex_patterns',
                        model: {
                            id: 'pattern_id'
                        },
                    },
                    serverFiltering: true,
                });
                regexPatternGrid.setDataSource(patternDataSource);
            }
        }).data("kendoComboBox");
        $('.modal-title').text("Website : " + dataItem.identity_website);
        $('#identity_id').val(identityId);
        $('#top_modal').modal("show");
    });
    //var regexPatternGrid = $("#regexPatternListGrid").data("kendoGrid");
    $('#submitBtn').click(function() {
        var idsToSend = [];
        var ds = regexPatternGrid.dataSource.view();
        for (var i = 0; i < ds.length; i++) {
            $row = regexPatternGrid.table.find("tr[data-uid='" + ds[i].uid + "']");
            var checkbox = $row.find(".k-checkbox");
            if (checkbox.is(":checked")) {
                idsToSend.push(ds[i].pattern_id);
            }
        }
        formData = $('#regexPatternForm').serializeArray();
        formData.push({
            name: 'pattern_id',
            value: idsToSend
        });
        if (!idsToSend.length) {
            $('span.k-regex-error').text("Please select at least one regex pattern.!");
            return false;
        }
        if (idsToSend.length == 1) {
            $.ajax({
                type: 'POST',
                data: formData,
                url: requestUrl + '/saveWebsiteRegex',
                success: function(eventData) {
                    $('span.k-regex-error').text("");
                    $("#regexPatternListGrid").data("kendoGrid").dataSource.read();
                    $("#websiteListGrid").data("kendoGrid").dataSource.read();
                }
            });
        } else {
            $('span.k-regex-error').text("Please select only one regex pattern.!");
            return false;
        }
    });
    $("#regexPatternSearchGrid").keyup(function(e) {
        var searchValue = $('#regexPatternSearchGrid').val();
        var patternDataSource = new kendo.data.DataSource({
            serverPaging: true,
            pageSize: 15,
            transport: {
                read: {
                    url: requestUrl + "/getRegexPatternList?search_field=" + searchValue,
                    dataType: "json",
                    type: "GET"
                }
            },
            schema: {
                total: 'total',
                data: 'regex_patterns',
                model: {
                    id: 'pattern_id'
                },
            },
            serverFiltering: true,
        });
        $("#regexPatternListGrid").data("kendoGrid").setDataSource(patternDataSource);
    });
    /* website block element code start */
    var websiteElementListGrid = jQuery("#websiteElementListGrid").kendoGrid({
        pageable: {
            refresh: true,
            pageSizes: true
        },
        scrollable: true,
        autoSync: true,
        sortable: true,
        reorderable: true,
        serverFiltering: false,
        groupable: true,
        resizable: true,
        dataBound: onElementDataBound,
        editable: 'inline',
        toolbar: ["create"],
        columns: [{
            command: [{
                name: "edit",
                text: {
                    edit: " ",
                    update: " ",
                    cancel: " "
                }
            }, {
                name: "destroy",
                text: " "
            }],
            title: "&nbsp;",
            width: "35px"
        }, {
            field: "element_id",
            title: "Element#",
            hidden: true,
        }, {
            selectable: true,
            width: "10px"
        }, {
            field: "pattern_start",
            title: "Pattern Start",
            width: 100
        }, {
            field: "pattern_end",
            title: "Pattern End",
            width: 100
        }],
    });

    function onElementDataBound(e) {
        var checkedIds = $('#selected_element_id').val();
        var viewData = this.dataSource.view();
        for (var initData = 0; initData < viewData.length; initData++) {
            if (checkedIds == viewData[initData].id) {
                this.tbody.find("tr[data-uid='" + viewData[initData].uid + "']").addClass("k-state-selected").find(".k-checkbox").attr("checked", "checked");
            }
        }
    }
    var websiteElementGrid = $("#websiteElementListGrid").data("kendoGrid");
    $('#websiteListGrid').on('click', '#blockelement', function(e) {
        var dataItem = websiteListKendoGrid.dataItem($(this).closest('tr'));
        var selectedElementId = dataItem.regex_block_element_id;
        $('#selected_element_id').val(selectedElementId);
        var identityId = dataItem.identity_id;
        var websiteElementDataSource = new kendo.data.DataSource({
            pageSize: 20,
            transport: {
                read: {
                    data: {
                        identity_id: identityId
                    },
                    url: requestUrl + '/getBlockElementList',
                    dataType: "json",
                    type: "GET"
                },
                create: {
                    data: {
                        _token: token
                    },
                    url: requestUrl + "/createBlockElement",
                    dataType: "json",
                    type: "POST",
                    complete: function(data) {
                        websiteElementGrid.dataSource.read();
                    }
                },
                update: {
                    data: {
                        _token: token
                    },
                    url: requestUrl + "/updateBlockElement",
                    dataType: "json",
                    type: "POST",
                    complete: function(data) {
                        websiteElementGrid.dataSource.read();
                    }
                },
                destroy: {
                    data: {
                        _token: token
                    },
                    url: requestUrl + "/deleteBlockElement",
                    dataType: "json",
                    type: "POST",
                    complete: function(data) {
                        websiteElementGrid.dataSource.read();
                        $("#websiteListGrid").data("kendoGrid").dataSource.read();
                    }
                }
            },
            schema: {
                model: {
                    id: 'element_id',
                }
            }
        });
        websiteElementGrid.setDataSource(websiteElementDataSource);
        $('.element-modal-title').text("Website : " + dataItem.identity_website);
        $('#element_identity_id').val(dataItem.identity_id);
        $('#element_top_modal').modal("show");
    });
    $("#websiteElementSearchGrid").keyup(function(e) {
        var searchValue = $('#websiteElementSearchGrid').val();
        $("#websiteElementListGrid").data("kendoGrid").dataSource.filter({
            logic: "or",
            filters: [{
                field: "pattern_start",
                operator: "contains",
                value: searchValue
            }, {
                field: "pattern_end",
                operator: "contains",
                value: searchValue
            }]
        });
    });
    $('#websiteElementSearchGrid').on("keypress", function(eventData) {
        if (eventData.keyCode == 13) {
            return false;
        }
    });
    $('#elementSubmitBtn').click(function() {
        var idsToSend = [];
        var ds = websiteElementGrid.dataSource.view();
        for (var i = 0; i < ds.length; i++) {
            $row = websiteElementGrid.table.find("tr[data-uid='" + ds[i].uid + "']");
            var checkbox = $row.find(".k-checkbox");
            if (checkbox.is(":checked")) {
                idsToSend.push(ds[i].element_id);
            }
        }
        formData = $('#websiteElementForm').serializeArray();
        formData.push({
            name: 'element_id',
            value: idsToSend
        });
        if (!idsToSend.length) {
            $('span.k-error').text("Please select at least one block element.!");
            return false;
        }
        if (idsToSend.length == 1) {
            $.ajax({
                type: 'POST',
                data: formData,
                url: requestUrl + '/assignWebsiteBlockElement',
                success: function(eventData) {
                    $('span.k-error').text("");
                    websiteElementGrid.dataSource.read();
                    $("#websiteListGrid").data("kendoGrid").dataSource.read();
                }
            });
        } else {
            $('span.k-error').text("Please select only one block element.!");
            return false;
        }
    });
    $('.close').click(function() {
        $('span.k-error').text("");
        $('span.k-block-error').text("");
        $('span.k-regex-error').text("");
        $('span.k-pagination-error').text("");
    });
    /* website block element code end */
    /* website block level code start */
    var websiteBlockListGrid = jQuery("#websiteBlockListGrid").kendoGrid({
        pageable: {
            refresh: true,
            pageSizes: true
        },
        scrollable: true,
        autoSync: true,
        sortable: true,
        reorderable: true,
        serverFiltering: false,
        groupable: true,
        resizable: true,
        dataBound: onDataBound,
        editable: 'inline',
        toolbar: ["create"],
        columns: [{
            command: [{
                name: "edit",
                text: {
                    edit: " ",
                    update: " ",
                    cancel: " "
                }
            }, {
                name: "destroy",
                text: " "
            }],
            title: "&nbsp;",
            width: "20px"
        }, {
            field: "id",
            title: "Id#",
            hidden: true,
        }, {
            selectable: true,
            width: "10px"
        }, {
            field: "pattern",
            title: "Block Pattern",
            width: 100
        }],
    });

    function onDataBound(e) {
        var checkedIds = $('#selected_block_id').val();
        var viewData = this.dataSource.view();
        for (var initData = 0; initData < viewData.length; initData++) {
            //if(checkedIds[view[i].id]) {
            if (checkedIds == viewData[initData].id) {
                this.tbody.find("tr[data-uid='" + viewData[initData].uid + "']").addClass("k-state-selected").find(".k-checkbox").attr("checked", "checked");
            }
        }
    }
    var websiteBlockGrid = $("#websiteBlockListGrid").data("kendoGrid");
    $('#websiteListGrid').on('click', '#blocklevel', function(e) {
        var dataItem = websiteListKendoGrid.dataItem($(this).closest('tr'));
        var selectedBlockId = dataItem.regex_block_level_id;
        $('#selected_block_id').val(selectedBlockId);
        var identityId = dataItem.identity_id;
        var websiteBlockDataSource = new kendo.data.DataSource({
            pageSize: 10,
            transport: {
                read: {
                    data: {
                        identity_id: identityId
                    },
                    url: requestUrl + '/getBlockLevel',
                    dataType: "json",
                    type: "GET"
                },
                create: {
                    data: {
                        _token: token
                    },
                    url: requestUrl + "/createBlockLevel",
                    dataType: "json",
                    type: "POST",
                    complete: function(data) {
                        websiteBlockGrid.dataSource.read();
                    }
                },
                update: {
                    data: {
                        _token: token
                    },
                    url: requestUrl + "/updateBlockLevel",
                    dataType: "json",
                    type: "POST",
                    complete: function(data) {
                        websiteBlockGrid.dataSource.read();
                    }
                },
                destroy: {
                    data: {
                        _token: token
                    },
                    url: requestUrl + "/deleteBlockLevel",
                    dataType: "json",
                    type: "POST",
                    complete: function(data) {
                        websiteBlockGrid.dataSource.read();
                        $("#websiteListGrid").data("kendoGrid").dataSource.read();
                    }
                }
            },
            schema: {
                model: {
                    id: 'id',
                }
            }
        });
        websiteBlockGrid.setDataSource(websiteBlockDataSource);
        $('.block-modal-title').text("Website : " + dataItem.identity_website);
        $('#block_identity_id').val(dataItem.identity_id);
        $('#block_top_modal').modal("show");
    });
    $("#websiteBlockSearchGrid").keyup(function(e) {
        var searchValue = $('#websiteBlockSearchGrid').val();
        $("#websiteBlockListGrid").data("kendoGrid").dataSource.filter({
            logic: "or",
            filters: [{
                field: "pattern",
                operator: "contains",
                value: searchValue
            }]
        });
    });
    $('#websiteBlockSearchGrid').on("keypress", function(eventData) {
        if (eventData.keyCode == 13) {
            return false;
        }
    });
    $('#blockSubmitBtn').click(function() {
        var idsToSend = [];
        var ds = websiteBlockGrid.dataSource.view();
        for (var i = 0; i < ds.length; i++) {
            $row = websiteBlockGrid.table.find("tr[data-uid='" + ds[i].uid + "']");
            var checkbox = $row.find(".k-checkbox");
            if (checkbox.is(":checked")) {
                idsToSend.push(ds[i].id);
            }
        }
        formData = $('#websiteBlockForm').serializeArray();
        formData.push({
            name: 'id',
            value: idsToSend
        });
        if (!idsToSend.length) {
            $('span.k-block-error').text("Please select at least one block.!");
            return false;
        }
        if (idsToSend.length == 1) {
            $.ajax({
                type: 'POST',
                data: formData,
                url: requestUrl + '/assignWebsiteBlockLevel',
                success: function(eventData) {
                    $('span.k-block-error').text("");
                    websiteBlockGrid.dataSource.read();
                    $("#websiteListGrid").data("kendoGrid").dataSource.read();
                }
            });
        } else {
            $('span.k-block-error').text("Please select only one block level.!");
            return false;
        }
    });
    /* website block level code end */
    /* website pagination code start */
    var paginationListGrid = jQuery("#paginationListGrid").kendoGrid({
        pageable: {
            refresh: true,
            pageSizes: true
        },
        scrollable: true,
        autoSync: true,
        sortable: true,
        reorderable: true,
        serverFiltering: false,
        groupable: true,
        resizable: true,
        dataBound: onPaginationDataBound,
        editable: 'inline',
        toolbar: ["create"],
        columns: [{
            command: [{
                name: "edit",
                text: {
                    edit: " ",
                    update: " ",
                    cancel: " "
                }
            }, {
                name: "destroy",
                text: " "
            }],
            title: "&nbsp;",
            width: "40px"
        }, {
            field: "pagination_id",
            title: "Pagination#",
            hidden: true,
        }, {
            selectable: true,
            width: "15px"
        }, {
            field: "pagination_url",
            title: "Pagination Url",
            width: 110
        }, {
            field: "pagination_format",
            title: "Pagination Format",
            width: 110
        }, {
            field: "pagination_increment",
            title: "Pagination Increment",
            width: 60
        }],
    });

    function onPaginationDataBound(e) {
        var checkedIds = $('#selected_pagination_id').val();
        var viewData = this.dataSource.view();
        for (var initData = 0; initData < viewData.length; initData++) {
            if (checkedIds == viewData[initData].id) {
                this.tbody.find("tr[data-uid='" + viewData[initData].uid + "']").addClass("k-state-selected").find(".k-checkbox").attr("checked", "checked");
            }
        }
    }
    var websitePaginationGrid = $("#paginationListGrid").data("kendoGrid");
    $('#websiteListGrid').on('click', '#paginationlist', function(e) {
        var dataItem = websiteListKendoGrid.dataItem($(this).closest('tr'));
        var selectedPaginationId = dataItem.pagination_id;
        $('#selected_pagination_id').val(selectedPaginationId);
        var identityId = dataItem.identity_id;
        var paginationDataSource = new kendo.data.DataSource({
            pageSize: 10,
            transport: {
                read: {
                    data: {
                        identity_id: identityId
                    },
                    url: requestUrl + '/getPaginationList',
                    dataType: "json",
                    type: "GET"
                },
                create: {
                    data: {
                        _token: token
                    },
                    url: requestUrl + "/createPagination",
                    dataType: "json",
                    type: "POST",
                    complete: function(data) {
                        websitePaginationGrid.dataSource.read();
                    }
                },
                update: {
                    data: {
                        _token: token
                    },
                    url: requestUrl + "/updatePagination",
                    dataType: "json",
                    type: "POST",
                    complete: function(data) {
                        websitePaginationGrid.dataSource.read();
                    }
                },
                destroy: {
                    data: {
                        _token: token
                    },
                    url: requestUrl + "/deletePagination",
                    dataType: "json",
                    type: "POST",
                    complete: function(data) {
                        websitePaginationGrid.dataSource.read();
                        $("#websiteListGrid").data("kendoGrid").dataSource.read();
                    }
                }
            },
            schema: {
                model: {
                    id: 'pagination_id',
                }
            }
        });
        websitePaginationGrid.setDataSource(paginationDataSource);
        $('.pagination-modal-title').text("Website : " + dataItem.identity_website);
        $('#pagination_identity_id').val(dataItem.identity_id);
        $('#pagination_top_modal').modal("show");
    });
    $("#paginationSearchGrid").keyup(function(e) {
        var searchValue = $('#paginationSearchGrid').val();
        $("#paginationListGrid").data("kendoGrid").dataSource.filter({
            logic: "or",
            filters: [{
                field: "pagination_url",
                operator: "contains",
                value: searchValue
            }, {
                field: "pagination_format",
                operator: "contains",
                value: searchValue
            }]
        });
    });
    $('#paginationSearchGrid').on("keypress", function(eventData) {
        if (eventData.keyCode == 13) {
            return false;
        }
    });
    $('#paginationSubmitBtn').click(function() {
        var idsToSend = [];
        var ds = websitePaginationGrid.dataSource.view();
        for (var i = 0; i < ds.length; i++) {
            $row = websitePaginationGrid.table.find("tr[data-uid='" + ds[i].uid + "']");
            var checkbox = $row.find(".k-checkbox");
            if (checkbox.is(":checked")) {
                idsToSend.push(ds[i].id);
            }
        }
        formData = $('#paginationForm').serializeArray();
        formData.push({
            name: 'pagination_id',
            value: idsToSend
        });
        if (!idsToSend.length) {
            $('span.k-pagination-error').text("Please select at least one pagination.!");
            return false;
        }
        if (idsToSend.length == 1) {
            $.ajax({
                type: 'POST',
                data: formData,
                url: requestUrl + '/assignWebsitePagination',
                success: function(eventData) {
                    $('span.k-pagination-error').text("");
                    websitePaginationGrid.dataSource.read();
                    $("#websiteListGrid").data("kendoGrid").dataSource.read();
                }
            });
        } else {
            $('span.k-pagination-error').text("Please select only one pagination.!");
            return false;
        }
    });
    /* website pagination code end */
    $('#websiteListGrid').on('click', '#regexscrape', function(e) {
        var dataItem = websiteListKendoGrid.dataItem($(this).closest('tr'));
        var websiteId = dataItem.identity_id;
        var blockId = dataItem.regex_block_level_id;
        var blockElementId = dataItem.regex_block_element_id;
        var paginationId = dataItem.pagination_id;
        $.ajax({
            type: 'POST',
            data: {
                _token: token,
                website_id: websiteId
            },
            url: requestUrl + '/scrapeWebsiteLinkData',
            success: function(eventData) {}
        });
    });
});