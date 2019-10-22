$(document).ready(function() {
    var domConstants = {
        PRIMITIVE_REGEX_GROUP:6,
        LIST_GRID_TAB:0,
        DOM_GRID_TAB:1,        
        TRANSFORM_GRID_TAB:2,
        CHECKED_GRID_TAB:3,
        ASCENDING_ORDER:1,
        DESCENDING_ORDER:-1
    };

    var requestUrl = $("#requestUrl").val();    
    var token = $('input[name="_token"]').val();
    var regexRequestUrl = $("#regexRequestUrl").val();
    var basePath = $('#basePath').val();
    var versionFilePath = basePath+"/laravel/public/version.txt";
    var version = "";
    var totalScrapeNode = 0;

    // INITIALY ENABLE/DISABLE FUNCTIONALITY
    enableScraping();

    $.get(versionFilePath, function(data) {
       version = data;
    }, 'text');
    
    var checkedTransformNodeData = [];
    $("#tabstrip").kendoTabStrip().data("kendoTabStrip");
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
        }
    }).data("kendoDropDownList");
    $("#website_uri").kendoAutoComplete({
        placeholder: "Website URL ...",
        minLength: 1,
        dataTextField: "identity_website",
        filter: "contains",
        select: onWebsiteSelect,
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

    function getScrapeUrl() {
        var websiteUrl = $('#website_uri').val();
        var urlData = websiteUrl.split("//");
        var websiteFullUrl;
        if (urlData[1]) {
            websiteFullUrl = websiteUrl;
        } else {
            websiteFullUrl = 'http://' + websiteUrl;
        }
        return websiteFullUrl;
    }

    function getDomainUrl() {
        var websiteUrl = $('#website_uri').val();
        var urlData = websiteUrl.split("//");
        var firstUrlPart;
        if (urlData[1]) {
            firstUrlPart = urlData[1];
        } else {
            firstUrlPart = urlData[0];
        }
        var domainData = firstUrlPart.split("/");
        return domainData[0];
    }

    function isEmpty(stringData) {
        return (!stringData || !stringData.length);
    }

    function setCheckedNodeDataSource(checkedNodeDataSource) {
        checkedNodeDataSource.sort(dynamicSort("rootParent"));
        var nodeDataSource = new kendo.data.DataSource({
            data: checkedNodeDataSource,
            group: {
                field: 'rootParent',
            },
            schema: {
                model: {
                    id: "nodeId",
                    fields: {
                        tag: {
                            type: "string",
                            editable: false
                        },
                        field: {
                            type: "string"
                        },
                        value: {
                            type: "string",
                            editable: false
                        },
                        status: {
                            type: "string",
                            editable: false
                        }
                    }
                }
            },
            pageSize: 20
        });
        $("#checkedHtmlDomGrid").data("kendoGrid").setDataSource(nodeDataSource);
    }

    function dynamicSort(property) {
        var sortOrder = domConstants.ASCENDING_ORDER;
        if(property[0] === "-") {
            sortOrder = domConstants.DESCENDING_ORDER;
            property = property.substr(1);
        }
        return function (a,b) {
            var result = (a[property] < b[property]) ? domConstants.DESCENDING_ORDER : (a[property] > b[property]) ? domConstants.ASCENDING_ORDER : 0;
            return result * sortOrder;
        }
    }

    function onWebsiteSelect(e) {
        var selectedOne = this.dataItem(e.item.index());
        var identityId = selectedOne.identity_id;
        $('#identity_id').val(identityId);
    }
    var autoExpand = 0;
    var filteredNodes = [];
    $("#label_id").kendoComboBox({
        placeholder: "Regex Group...",
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
            var data = this.dataSource.data();
            var groupId = this.value();
            var groupName = this.text();

            var matchType = data.filter(function (item) {
                return item.name_id == groupId;
            })[0].match_type;

            if (autoExpand) {
                
                if (!!groupId) {
                	$('.content .preloader').show();
                	$('.content img').show();
                    $('.processCompleted').text('');
                    var websiteUrl = getScrapeUrl();
                    $('.statusUrl').html('<a href="' + websiteUrl + '">' + websiteUrl + '</a>');
                    $('.processCount').text(0);
                    var viewData = resultDataSource.view();
                    $('.totalCount').html(viewData.length);
                    $('.processSection').show();
                    $.ajax({
                        type: 'GET',
                        data: {
                            group_id: groupId
                        },
                        dataType: 'json',
                        url: requestUrl + "/getGroupPatterns",
                        success: function(regexPattern) {                        	
                            var domTreeView = jQuery("#htmlDomResultGrid").data("kendoTreeList");
                            for (var initData = 0; initData < viewData.length; initData++) {
                                switch(matchType) {
                                  case "Link":
                                  case "Value":                                  
                                    if(matchType == "Link"){
                                        var tagValue = 'href="' + viewData[initData].value + '"';
                                    }else{
                                        var tagValue = viewData[initData].value.trim();
                                    }
                                    jQuery.each(regexPattern, function(patternIndex, patternValue) {
                                        if(patternValue.pattern.charAt(0)!='/'){
                                            patternValue.pattern = "/"+patternValue.pattern;
                                        }

                                        if(patternValue.pattern.slice(-1)!='/'){
                                            patternValue.pattern = patternValue.pattern+"/";
                                        }
                                        
                                        var patternData = eval('(' + patternValue.pattern + ')');
                                        var patternMatch = tagValue.match(patternData);
                                        if (patternMatch != null) {
                                            var parentPath = viewData[initData].path;
                                            var selectedNodePath = parentPath.split('/');
                                            var pathLength = selectedNodePath.length;
                                            for (var nodeData = 0; nodeData < pathLength; nodeData++) {
                                                if (!!selectedNodePath[nodeData]) {
                                                    var domNodeRow = domTreeView.content.find("tr").eq(selectedNodePath[nodeData]);
                                                    domTreeView.expand(domNodeRow);
                                                }
                                            }
                                            domTreeView.content.find("tr[data-uid='" + viewData[initData].uid + "']").attr("groupSelected", "yes");
                                            if (jQuery.inArray(viewData[initData].nodeId, filteredNodes) == -1) {
                                                filteredNodes.push(viewData[initData].nodeId);
                                            }
                                        }
                                    });
                                    break;
                                  case "Field":
                                    if (jQuery.inArray(viewData[initData].field, regexPattern) != -1) {
                                        if (!!$.trim(viewData[initData].value)) {
                                            var parentPath = viewData[initData].path;
                                            var selectedNodePath = parentPath.split('/');
                                            var pathLength = selectedNodePath.length;
                                            for (var nodeData = 0; nodeData < pathLength; nodeData++) {
                                                if (!!selectedNodePath[nodeData]) {
                                                    var domNodeRow = domTreeView.content.find("tr").eq(selectedNodePath[nodeData]);
                                                    domTreeView.expand(domNodeRow);
                                                }
                                            }
                                            domTreeView.content.find("tr[data-uid='" + viewData[initData].uid + "']").attr("groupSelected", "yes");
                                            if (jQuery.inArray(viewData[initData].nodeId, filteredNodes) == -1) {
                                                filteredNodes.push(viewData[initData].nodeId);
                                            }
                                        }
                                    }
                                    break;
                                }
                                $('.processCount').text(initData + 1);
                            }
                            $('.processCompleted').text('Completed');
                            $('.processCount').text(viewData.length);
                            $('#group-title').text(groupName);
                            $('.content .preloader,.content img').hide();
                        }
                    });
                }
            }
        }
    }).data("kendoComboBox");
    
    var tabStrip = $("#tabstrip").kendoTabStrip().data("kendoTabStrip");
    $.ajax({
        type: 'GET',
        dataType: 'html',
        url: requestUrl + "/getUserScrapeLists",
        success: function(eventData) {
            if(eventData > 1) {
                tabStrip.select(domConstants.DOM_GRID_TAB);
            }
            else {
                tabStrip.select(domConstants.LIST_GRID_TAB);
                $("div#tabstrip ul li:nth-child(1)").trigger("click");
            }
        }
    });
    var resultDataSource;
    var checkedNodeByUser = [];
    
    $('#resultSubmitBtn').click(function() {
        tabStrip.select(domConstants.DOM_GRID_TAB);
        $('.processSection').hide();
        $('.processCompleted').text('');
        var websiteFullUrl = getScrapeUrl();
        $('.statusUrl').html('<a href="' + websiteFullUrl + '">' + websiteFullUrl + '</a>');
        $('.processCount').text(0);
        $('.scrapeError').html('');
        var nodeDataSource = new kendo.data.DataSource({
            data: [],
            group: {
                field: 'rootParent',
            },
            schema: {
                model: {
                    id: "nodeId",
                }
            },
            pageSize: 20
        });
        $("#checkedHtmlDomGrid").data("kendoGrid").setDataSource(nodeDataSource);
        var listGridDataSource = new kendo.data.DataSource({
            data: [],
            schema: {
                model: {
                    id: "nodeId",
                }
            },
            pageSize: 20
        });
        $("#checkedSavedHtmlDomGrid").data("kendoGrid").setDataSource(listGridDataSource);
        var identityTable = $('#identity_table').val();
        var identityId = $('#identity_id').val();
        var websiteUrl = $('#website_uri').val();
        var regexGroup = $('#label_id').val();
        var regexLabel = $('#label_id').data("kendoComboBox").text();
        var htmlDomDataSource = new kendo.data.TreeListDataSource({
            transport: {
                read: {
                    url: requestUrl + "/scrapeHtmlDom?website_uri=" + encodeURIComponent(websiteUrl) + "&identity_table=" + identityTable + "&identity_id=" + identityId + "&regex_group=" + regexGroup + "&regex_label=" + regexLabel,
                    dataType: "json",
                    type: "GET"
                },
                parameterMap: function(options, operation) {
                    if (operation !== "read" && options.models) {
                        return {
                            models: kendo.stringify(options.models)
                        };
                    }
                }
            },
            batch: true,
            schema: {
                model: {
                    id: "nodeId",
                    parentId: "parentNodeId",
                    fields: {
                        nodeId: {
                            type: "number",
                            editable: false
                        },
                        parentNodeId: {
                            field: "parentNodeId",
                            nullable: true
                        },
                        class_change_data: {
                            editable: false
                        },
                    }
                }
            },
            error: function(errorData) {
                var errorStatus = errorData.status;
                var errorMessage = errorData.errorThrown;
                var statusCode = errorData.xhr.status;
                errorStatus = errorStatus.toLowerCase().replace(/\b[a-z]/g, function(letter) {
                    return letter.toUpperCase();
                });
                $('.scrapeError').html('<font color="red">' + errorStatus + '</font> - ' + statusCode + ' ' + errorMessage);
            },
            requestEnd: function(e) {
                if (e.type === "read" && e.response) {
                    totalScrapeNode = e.response.length - 1;                    
                }
            }
        });
        $("#htmlDomResultGrid").data("kendoTreeList").setDataSource(htmlDomDataSource); 
        resultDataSource = htmlDomDataSource;
        autoExpand = 1;
        checkedNodeByUser = []; 
    });

    function autoExpandNode(viewData) {
        for (var initData = 0; initData < viewData.length; initData++) {
            var domTreeView = jQuery("#htmlDomResultGrid").data("kendoTreeList");
            if (viewData[initData].selected == true) {
                var domNodeId = viewData[initData].nodeId;
                var parentPath = viewData[initData].path;
                var selectedNodePath = parentPath.split('/');
                var pathLength = selectedNodePath.length;
                for (var nodeData = 0; nodeData < pathLength; nodeData++) {
                    if (!!selectedNodePath[nodeData]) {
                        var domNodeRow = domTreeView.content.find("tr").eq(selectedNodePath[nodeData]);
                        domTreeView.expand(domNodeRow);
                    }
                }
                domTreeView.content.find("tr[data-uid='" + viewData[initData].uid + "']").attr("groupSelected", "yes");
                if (jQuery.inArray(viewData[initData].nodeId, filteredNodes) == -1) {
                    filteredNodes.push(viewData[initData].nodeId);
                }
            }
            if (viewData[initData].dom_changes == 'Yes') {
                domTreeView.content.find("tr[data-uid='" + viewData[initData].uid + "']").attr("domChanged", "yes");
            }
        }
    }
    $("#htmlDomResultGrid").on('click', '.allNode', function(eventData) {
        var viewData = resultDataSource.view();
        var checkedData = eventData.target.checked;
        for (var initData = 0; initData < viewData.length; initData++) {
            viewData[initData].set('checked', checkedData);
        }
    });
    var referenceTableColumnIdGrid = jQuery("#referenceTableColumnId").kendoGrid({
        pageable: {
            refresh: true,
            pageSizes: true
        },
        scrollable: true,
        autoSync: false,
        sortable: true,
        reorderable: true,
        groupable: true,
        resizable: true,
        editable: true,
        columns: [{
            field: "id",
            title: "Id#",
            hidden: true,
        }, {
            field: "table_id",
            title: "table_id#",
            hidden: true,
        }, {
            field: "reference_table",
            title: "Reference Table",
            editor: referenceTableEditor,
            width: "150px",
        }, {
            field: "reference_column",
            title: "Reference Column",
            editor: tableColumnsEditor,
            width: "100px"
        }, {
            field: "reference_id",
            title: "Reference Id",
            template: "#=(reference_id > 0)?reference_id:' '#",
            width: "100px"
        }],
    });
    var domChanges = true;
    $("#htmlDomResultGrid").kendoTreeList({
        dataSource: [],
        filterable: true,
        filterMenuInit: function(eventData) {
            var firstValueDropDown = eventData.container.find("select:eq(0)").data("kendoDropDownList");
            firstValueDropDown.value("contains");
            firstValueDropDown.trigger("change");
            var logicDropDown = eventData.container.find("select:eq(1)").data("kendoDropDownList");
            logicDropDown.value("or");
            logicDropDown.trigger("change");
            var secondValueDropDown = eventData.container.find("select:eq(2)").data("kendoDropDownList");
            secondValueDropDown.value("contains");
            secondValueDropDown.trigger("change");
            var filterButton = $(eventData.container).find('.k-primary');
            $(filterButton).click(function(filterEvent) {
                $('.content .preloader').show();
                $('.content img').show();
                setTimeout(function() {
                    $('.content .preloader,.content img').hide();
                }, 250);
            });
            var clearButton = $(eventData.container).find('button[type="reset"]');
            $(clearButton).click(function(filterEvent) {
                $("#htmlDomResultGrid").data("kendoTreeList").dataSource.filter([]);
            });
        },
        resizable: true,
        scrollable: true,
        autoSync: true,
        sortable: true,
        reorderable: true,
        serverFiltering: true,
        editable: 'incell',
        columns: [{
            headerTemplate: "<input type='checkbox' class='allNode' />",
            template: "<input type='checkbox' class='domNode' data-bind='checked: checked' />",
            width: 32,
            filterable: false
        }, {
            field: "nodeId",
            title: "Id",
            filterable: false,
            editable: false,
            width: 30,
        }, {
            field: "class_data",
            title: "Class",
            template: kendo.template(jQuery('#class_change_data_template').html()),
            width: 150
        }, {
            field: "field",
            title: "ClassData#",
            hidden: true,
        }, {
            field: "identityTableId",
            title: "identityTableId#",
            hidden: true,
        }, {
            field: "tag",
            expandable: true,
            title: "Tag",
            width: 200,
            filterable: false
        }, {
            field: "value",
            title: "Value",
            template: kendo.template(jQuery('#node_value_template').html()),
            width: 200
        }, {
            field: "dom_changes",
            title: "New Changes",
            filterable: true,
            width: 100
        }, {
            field: "ref_id",
            title: "Reference Id",
            filterable: false,
            template: kendo.template(jQuery('#reference_id_template').html()),
            width: 100
        }],
        save: function(e) {
            var identityId = $('#identity_id').val();
            $('.content .preloader').show();
            $('.content img').show();
            jQuery.ajax({
                type: 'POST',
                data: {
                    _token: token,
                    table_id: e.model.identityTableId,
                    class_data: e.model.field,
                    node_id: e.model.nodeId,
                    class_change: e.values.class_data,
                    identityId: identityId
                },
                url: requestUrl + "/updateHtmlDomClass",
                success: function(statusMessage) {  
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
                    var $toast = toastr["success"]("","Information updated");                  
                    $('.content .preloader,.content img').hide();
                }
            });
            checkedNodeDataSource.push({
                tag: e.model.tag,
                field: e.values.class_data,
                class_field: e.model.field,
                value: e.model.value,
                nodeId: e.model.nodeId,
                parentNodeId: e.model.parentNodeId,
                status: e.model.status,
                rootParent: parseInt(e.model.parentNodeId),
                identity_table_id: e.model.identityTableId,
                identity_id: identityId
            });
            setCheckedNodeDataSource(checkedNodeDataSource);
        },
        dataBound: function(eventData) {
            var viewDataSource = this.dataSource.view();
            this.items().each(function(indexData, rowData) {
                kendo.bind(rowData, viewDataSource[indexData]);
            });
            if (autoExpand) {
                autoExpandNode(viewDataSource);
                var scrapedData = $('#scraped').val();
                if (parseInt(scrapedData) && domChanges) {
                    domChanges = false;
                    var domDataSource = $("#htmlDomResultGrid").data("kendoTreeList").dataSource;
                    var newFilter = {
                        field: "dom_changes",
                        operator: "contains",
                        value: 'Yes'
                    };
                    var domFilters = null;
                    if (domDataSource.filter() != null) {
                        domFilters = domDataSource.filter().filters;
                    }
                    if (domFilters == null) {
                        domFilters = [newFilter];
                    }
                    domDataSource.filter(domFilters);
                }
            }
            var domTreeList = $('#htmlDomResultGrid').data('kendoTreeList');
            if (eventData.sender.dataSource.filter()) {
                var rowGroupData = domTreeList.content.find('tr.k-treelist-group');
                for (var initData = 0; initData < rowGroupData.length; initData++) {
                    domTreeList.expand(rowGroupData[initData]);
                }
                var filtersData = domTreeList.dataSource.filter();
                var filterColumn = filtersData.filters[0].field;
                var filterLength = filtersData.filters.length;
                var firstValue;
                var secondValue;
                if (filterLength >= 2) {
                    firstValue = filtersData.filters[0].value;
                    secondValue = filtersData.filters[1].value;
                } else {
                    firstValue = filtersData.filters[0].value;
                }
                var filterDataSource = domTreeList.dataSource.view();
                for (var nodeData = 0; nodeData < filterDataSource.length; nodeData++) {
                    domTreeList.content.find("tr[data-uid='" + filterDataSource[nodeData].uid + "']").attr("groupSelected", "no");
                    if (filterColumn == 'field') {
                        var firstMatch = filterDataSource[nodeData].field.includes(firstValue);
                        var secondMatch = filterDataSource[nodeData].field.includes(secondValue);
                    } else if (filterColumn == 'value') {
                        var firstMatch = filterDataSource[nodeData].value.includes(firstValue);
                        var secondMatch = filterDataSource[nodeData].value.includes(secondValue);
                    }
                    if (firstMatch || secondMatch) {
                        domTreeList.content.find("tr[data-uid='" + filterDataSource[nodeData].uid + "']").attr("groupSelected", "yes");
                    }
                }
            }
            $('.nodeReferenceId').parent().on('click', function(eventData) {
                eventData.preventDefault();
                var dataItem = domTreeList.dataItem($(this).closest('tr'));
                var nodeId = dataItem.nodeId;
                var identityTableId = dataItem.identityTableId;
                var referenceId = dataItem.ref_id;
                var referenceTable = dataItem.ref_table;
                var referenceColumn = dataItem.ref_column;
                var identityId = $('#identity_id').val();
                var referenceNodesDataSource = new kendo.data.DataSource({
                    pageSize: 5,
                    autoSync: true,
                    transport: {
                        read: {
                            url: requestUrl + "/getReferenceNodeDetails?node_id=" + nodeId + "&identity_table=" + identityTableId + "&identity_id=" + identityId + "&reference_id="+referenceId+"&reference_table="+referenceTable+"&reference_column="+referenceColumn,
                            dataType: "json",
                            type: "GET"
                        },
                        update: {
                            data: {
                                _token: token,
                                node_id: nodeId,
                                identity_table_id: identityTableId,
                                identity_id: identityId
                            },
                            url: requestUrl + "/assignReferenceInfo",
                            dataType: "json",
                            type: "POST",
                            complete: function(data) {
                                referenceId = data.responseJSON.reference_id;
                                referenceColumn = data.responseJSON.reference_column;
                                referenceTable = data.responseJSON.reference_table;
                                $("#referenceTableColumnId").data("kendoGrid").dataSource.read();
                            }
                        },
                    },
                    schema: {
                        model: {
                            id: 'id',
                            fields: {
                                id: {
                                    type: "number"
                                },
                                reference_id: {
                                    type: "number",
                                    editable: false
                                },
                            }
                        },
                    }
                });
                $("#referenceTableColumnId").data("kendoGrid").setDataSource(referenceNodesDataSource);
                $('#reference_modal_fields').modal("show");

                var dataSourceLength = eventData.sender.dataSource.total();
                if (dataSourceLength > 0) {
                    $('.processCount').text(dataSourceLength);
                    $('.totalCount').html(dataSourceLength);
                    $('.processSection').show();
                    $('.processCompleted').text('Completed');
                    $('#checkFilteredRaw').show();
                } 
            });

            var totalNodes = $('#htmlDomResultGrid').data('kendoTreeList').dataSource.total();
            // AFTER SUCCESSFULLY SCRAPE APPLY ENABLE/DISABLE OPTIONS
            if(totalNodes > 0){
                disableScraping(); 
            }

            window.kendo.ui.progress($("#htmlDomResultGrid"), false);
        }
    });
    $("#htmlDomResultGrid").on("keypress", function(eventData) {
        if (eventData.keyCode == 13) {
            $("#website_uri").focus();
        }
    });

    $(document).on("click","#refreshTreelist",function(){
        window.kendo.ui.progress($("#htmlDomResultGrid"), true);
        $("#htmlDomResultGrid").data("kendoTreeList").dataSource.read();
    });

    function referenceTableEditor(container, options) {
        $('<input data-text-field="table_name" data-value-field="table_name" data-bind="value:' + options.field + '"/>').appendTo(container).kendoDropDownList({
            dataSource: {
                transport: {
                    read: {
                        dataType: "json",
                        url: requestUrl + "/getTargetTables",
                        type: "GET"
                    }
                }
            },
            dataTextField: "table_name",
            dataValueField: "table_name"
        });
    }

    function tableColumnsEditor(container, options) {
        var referenceTable = options.model.reference_table;
        $('<input data-text-field="column_name" data-value-field="column_name" data-bind="value:' + options.field + '"/>').appendTo(container).kendoDropDownList({
            dataSource: {
                transport: {
                    read: {
                        data: {
                            target_table: referenceTable,
                        },
                        dataType: "json",
                        url: requestUrl + "/getTargetTableColumns",
                        type: "GET"
                    }
                }
            },
            dataTextField: "column_name",
            dataValueField: "column_name"
        });
    }
    var checkedNodes = [];

    function onChange(arguments) {
        checkedNodes.push(this.selectedKeyNames());
    }
    var targetMapBound = false;
    $("#checkedHtmlDomGrid").kendoGrid({
        dataSource: [],
        filterable: {
            mode: "row"
        },
        sortable: true,
        resizable: true,
        pageable: true,
        persistSelection: true,
        editable: true,
        change: onChange,
        columns: [{
            selectable: true,
            width: "12px"
        }, {
            field: "tag",
            title: "Tag",
            filterable: false,
            width: "30px"
        }, {
            field: "field",
            title: "Class",
            filterable: {
                cell: {
                    operator: "contains",
                    suggestionOperator: "contains"
                }
            },
            width: "60px"
        }, {
            field: "value",
            title: "Node Value",
            filterable: {
                cell: {
                    operator: "contains",
                    suggestionOperator: "contains"
                }
            },
            template: kendo.template(jQuery('#node_value_template').html()),
            width: "110px"
        }, {
            field: "status",
            title: "Status",
            filterable: false,
            width: "25px"
        }, {
            title: "Map",
            template: kendo.template(jQuery('#map_target_template').html()),
            width: "25px"
        }],
        save: function(eventData) {
            $('.content .preloader').show();
            $('.content img').show();
            jQuery.ajax({
                type: 'POST',
                data: {
                    _token: token,
                    table_id: eventData.model.identity_table_id,
                    class_data: eventData.model.class_field,
                    node_id: eventData.model.nodeId,
                    class_change: eventData.values.field,
                    identityId: eventData.model.identity_id
                },
                url: requestUrl + "/updateHtmlDomClass",
                success: function(statusMessage) {
                    $('.content .preloader,.content img').hide();
                    var nodeDataId = eventData.model.nodeId;
                    var newField = eventData.values.field;
                    var classField = eventData.model.class_field;
                    var updatedNodeTag = eventData.model.tag;
                    $(checkedNodeDataSource).each( function() {
                        if((this.class_field == classField) && (this.field != newField))  {
                            if(this.tag == updatedNodeTag) {
                                this.field = newField;
                            }
                        }
                    });
                    setCheckedNodeDataSource(checkedNodeDataSource);
                    var domainUrl = $('#website_uri').val();
                    var checkedNodeGrid = $("#checkedHtmlDomGrid").data("kendoGrid");
                    var checkedNodes = checkedNodeGrid.dataSource.data();
                    $.ajax({
                        type: 'POST',
                        data: {
                            selected_node: JSON.stringify(checkedNodes),
                            all_dom_node: '',
                            domain_url: domainUrl,
                            regex_group: '',
                            _token: token
                        },
                        url: requestUrl + "/saveDomNodeValues",
                        success: function(eventData) {}
                    });
                }
            });
        },
        dataBound: function(eventData) {
            var checkedNodeGrid = $("#checkedHtmlDomGrid").data("kendoGrid");
            $('.mapTargetFields').parent().on('click', function(eventData) {
                targetMapBound = true;
                var dataItem = checkedNodeGrid.dataItem($(this).closest('tr'));
                var identityTableId = dataItem.identity_table_id;
                var identityId = dataItem.identity_id;
                var domClass = dataItem.field;
                $('#domClass').val(domClass);
                var checkedNodeGridData = $("#checkedHtmlDomGrid").data("kendoGrid");
                var checkedNodes = checkedNodeGridData.dataSource.data();
                var mapTargetDataSource = new kendo.data.DataSource({
                    pageSize: 5,
                    autoSync: true,
                    transport: {
                        read: {
                            data: {
                                identity_id: identityId,
                                identity_table_id: identityTableId,
                                dom_class: domClass,
                                checked_node: JSON.stringify(checkedNodes),
                                _token: token
                            },
                            url: requestUrl + "/getTargetFieldsDetails",
                            dataType: "json",
                            type: "POST"
                        },
                        update: {
                            data: {
                                _token: token,
                                identity_table_id: identityTableId,
                                identity_id: identityId,
                                dom_class: domClass
                            },
                            url: requestUrl + "/saveTargetTableInfo",
                            dataType: "json",
                            type: "POST",
                            complete: function(dataObject) {
                                $("#mapTargetFieldsGrid").data("kendoGrid").dataSource.read();
                            }
                        }
                    },
                    schema: {
                        model: {
                            id: 'map_id',
                            fields: {
                                map_id: {
                                    type: "number"
                                },
                                dom_class: {
                                    type: "string",
                                    editable: false
                                },
                            }
                        },
                    }
                });
                $("#mapTargetFieldsGrid").data("kendoGrid").setDataSource(mapTargetDataSource);
                $('#map_target_top_modal').modal("show");
            });
        }
    });

    $("div#tabstrip ul li:nth-child(4)").on("click", function () {
        var domainUrl = $('#website_uri').val();
        if(domainUrl) {
            var urlData = domainUrl.split("//");
            var firstUrlPart;
            if (urlData[1]) {
                firstUrlPart = urlData[1];
            } else {
                firstUrlPart = urlData[0];
            }
            var urlReplaceDot = firstUrlPart.replace(/\./g, '-');
            var urlReplaceSlash = urlReplaceDot.replace(/\//g, '_');
            var urlReplaceHash = urlReplaceSlash.replace(/\#/g, '');
            var checkedDomFilePath = basePath + '/laravel/storage/htmldom/' + urlReplaceHash + '.checked.json';
            $.getJSON(checkedDomFilePath, function(nodeData) {
                checkedNodeDataSource = nodeData;
                setCheckedNodeDataSource(nodeData);
            });
        }
    });

    var checkedNodeGrid = $("#checkedHtmlDomGrid").data("kendoGrid");
    var parentBlock;
    var parentClass;
    var parentFieldId;
    function getParentField(parentNode) {
        var viewData = resultDataSource.view();
        var parentData = viewData[parentNode].field;
        var parentDataCheck = isEmpty(parentData);
        if (!!parentData) {
            parentBlock = viewData[parentNode].field;
            parentFieldId = parentNode;
        } else if (parentDataCheck) {
            var parentNodeData = viewData[parentNode].parentNodeId;
            parentBlock = getParentField(parentNodeData);
        }
        return parentBlock;
    }
    var kendoTreeResult = $("#htmlDomResultGrid").data("kendoTreeList");
    $("#htmlDomResultGrid").on('click', '.domNode', function(eventData) {
        autoExpand = 0;
        var domDataItem = kendoTreeResult.dataItem($(this).closest('tr'));
        var currentNodeId = domDataItem.nodeId;
        var checkedData = eventData.target.checked;
        if (checkedData) {
            kendoTreeResult.content.find($(this).closest('tr')).attr("userSelected", "yes");
            checkedNodeByUser.push(currentNodeId);
        } else {
            kendoTreeResult.content.find($(this).closest('tr')).attr("userSelected", "No");
            checkedNodeByUser.splice($.inArray(currentNodeId, checkedNodeByUser), 1);
        }
    });
    $('#checkFilteredRaw').click(function() {
        $('.content .preloader').show();
        $('.content img').show();
        setTimeout(function() {
            var filterNodeLength = filteredNodes.length;
            var viewData = resultDataSource.view();
            for (var initData = 0; initData < filterNodeLength; initData++) {
                var filteredNode = filteredNodes[initData];
                viewData[filteredNode].set('checked', true);
            }
            $('.content .preloader,.content img').hide();
        }, 100);
    });

    function containsAll() {
        var outputData = [];
        var countObject = {};
        var arrayField, itemData, countData;
        for (var initData = 0; initData < arguments.length; initData++) {
            arrayField = arguments[initData];
            for (var initItem = 0; initItem < arrayField.length; initItem++) {
                itemData = "-" + arrayField[initItem];
                countData = countObject[itemData] || 0;
                if (countData == initData) {
                    countObject[itemData] = countData + 1;
                }
            }
        }
        for (itemData in countObject) {
            if (countObject.hasOwnProperty(itemData) && countObject[itemData] === arguments.length) {
                outputData.push(itemData.substring(1));
            }
        }
        return (outputData);
    }
    var parentNodeField;
    var parentLevelCount;
    function getParentClass(allCommonClass, parentLength) {

        var parentNodeClass = allCommonClass[parentLength];
        if (!!parentNodeClass) {
            parentNodeField = parentNodeClass;
            parentLevelCount = parentLength;
        } else {
            parentNodeField = getParentClass(allCommonClass, parentLength - 1);
        }
        return parentNodeField;
    }
    var checkedNodeDataSource = [];
    var referenceValueData = [];

    $('#nodeExpandBtn').click(function() {
        $('.content .preloader').show();
        $('.content img').show();
        var identityId = $('#identity_id').val();
        var checkedDomNodeLength = checkedNodeByUser.length;
        if (checkedDomNodeLength > 0) {
            $('.processCompleted').text('');
            var websiteUrl = getScrapeUrl();
            $('.statusUrl').html('<a href="' + websiteUrl + '">' + websiteUrl + '</a>');
            $('.processCount').text(0);
            var viewData = $("#htmlDomResultGrid").data("kendoTreeList").dataSource.data();
            $('.totalCount').html(viewData.length);
            $('.processSection').show();
        }
        var selectedNodeData = [];
        setTimeout(function() {
            if (checkedDomNodeLength <= 0) {
                $('.content .preloader,.content img').hide();
            } else {
                checkedNodeDataSource = [];
                for (var initData = 0; initData < checkedDomNodeLength; initData++) {
                    var checkedNodeId = checkedNodeByUser[initData];
                    var parentNodeId = viewData[checkedNodeId].parentNodeId;
                    var parentClassField = viewData[parentNodeId].field;
                    var parentTag = viewData[parentNodeId].tag;
                    var status = viewData[parentNodeId].status;
                    selectedNodeData.push({
                        tag: viewData[checkedNodeId].tag,
                        field: viewData[checkedNodeId].field,
                        class_data: viewData[checkedNodeId].class_data,
                        value: viewData[checkedNodeId].value,
                        status: viewData[checkedNodeId].status,
                        nodeId: checkedNodeId,
                        parentNodeId: parentNodeId,
                        path: viewData[checkedNodeId].path,
                        child: viewData[checkedNodeId].child,
                        nodeIndex: viewData[checkedNodeId].nodeIndex,
                        parentClass: parentClassField,
                        parentTag: parentTag
                    });
                }

                var checkedNodeCount = selectedNodeData.length;
                if (checkedNodeCount == 1) {
                    var domNodeId = selectedNodeData[0].nodeId;
                    var domNodeTag = selectedNodeData[0].tag;
                    var domNodeField = selectedNodeData[0].field;
                    var domNodeClass = selectedNodeData[0].class_data;
                    var domParentId = selectedNodeData[0].parentNodeId;
                    var nodePosition = selectedNodeData[0].nodeIndex;
                    parentClass = viewData[domParentId].field;
                    var parentClassData = isEmpty(parentClass);
                    if (viewData[domParentId].child > 1 && !!parentClass) {
                        parentBlock = viewData[domParentId].field;
                        parentFieldId = domParentId;
                    } else if (viewData[domParentId].child == 1 || parentClassData) {
                        var parentNode = viewData[domParentId].parentNodeId;
                        parentBlock = getParentField(parentNode);
                    }

                    var parentLevel;
                    var splitClassArray = [];
                    var nodePath = selectedNodeData[0].path;
                    var splitPath = nodePath.split('/');
                    for (var initData = 0; initData < splitPath.length; initData++) {
                        if (!!splitPath[initData]) {
                            var splitPathId = splitPath[initData];
                            var parentNodeClass = viewData[splitPathId].field;
                            var parentNodeDataId = 0;
                            splitClassArray.push(parentNodeClass);
                            if (parentNodeClass == parentBlock) {
                                parentLevel = initData;
                                parentNodeDataId = viewData[splitPathId].nodeId;
                            }
                        }
                    }

                    var parentNodeDistance = domNodeId - parentNodeDataId;

                    var nodeLevel = splitClassArray.length - parentLevel;
                    var domTreeView = jQuery("#htmlDomResultGrid").data("kendoTreeList");
                    for (var initData = 0; initData < viewData.length; initData++) {
                        if (viewData[initData].tag == domNodeTag && viewData[initData].class_data == domNodeClass) {
                            viewData[initData].set('checked', false);
                            var splitNodePath = viewData[initData].path.split('/');
                            var pathLength = splitNodePath.length;
                            var levelDiff = pathLength - nodeLevel - 1;
                            var parentDataNode = splitNodePath[levelDiff];

                            if (parentDataNode >= 0) {
                                var parentField = viewData[parentDataNode].field;
                                var parentNodeIdValue = viewData[parentDataNode].nodeId;
                                var currentNodeDistance = viewData[initData].nodeId - parentNodeIdValue;
                                
                                if (parentBlock == parentField) {
                                    viewData[initData].set('checked', true);
                                    for (var nodeData = 0; nodeData < pathLength; nodeData++) {
                                        if (!!splitNodePath[nodeData]) {
                                            var domNodeRow = domTreeView.content.find("tr").eq(splitNodePath[nodeData]);
                                            domTreeView.expand(domNodeRow);
                                        }
                                    }
                                    checkedNodeDataSource.push({
                                        tag: viewData[initData].tag,
                                        field: viewData[initData].class_data,
                                        class_field: viewData[initData].field,
                                        value: viewData[initData].value,
                                        nodeId: viewData[initData].nodeId,
                                        parentNodeId: viewData[initData].parentNodeId,
                                        status: viewData[initData].status,
                                        rootParent: parseInt(parentDataNode),
                                        identity_table_id: viewData[initData].identityTableId,
                                        identity_id: identityId
                                    });
                                }
                            }
                        }
                        $('.processCount').text(initData);
                    }
                } else {

                    var outputTreeObject = {};
                    function addnodeTree(selectedNodeObject){
                        var existNode = searchTree(outputTreeObject,selectedNodeObject.path,selectedNodeObject.nodeId,selectedNodeObject.parentNodeId,selectedNodeObject.tag,selectedNodeObject.parentTag,selectedNodeObject.class_data,selectedNodeObject.parentClass);
                        var tempTreeArray = outputTreeObject;
                        if(!existNode)
                        {
                            var splitpath = selectedNodeObject.path.replace(/^\/|\/$/g, "").split('/');
                            splitpath.push(''+selectedNodeObject.nodeId+'');

                            for (nodeIndex=0;nodeIndex<splitpath.length;nodeIndex++)
                            {
                                nodeObject = { nodeId: splitpath[nodeIndex]};
                                if(nodeIndex == splitpath.length-1)
                                {
                                    {nodeObject.nodeId = selectedNodeObject.nodeId;nodeObject.tag = selectedNodeObject.tag;nodeObject.field = selectedNodeObject.field;nodeObject.class_data = selectedNodeObject.class_data;nodeObject.value = selectedNodeObject.value;nodeObject.status = selectedNodeObject.status;nodeObject.parentNodeId = selectedNodeObject.parentNodeId;nodeObject.path = selectedNodeObject.path;nodeObject.child = selectedNodeObject.child;nodeObject.nodeIndex = selectedNodeObject.nodeIndex;nodeObject.parentClass = selectedNodeObject.parentClass;nodeObject.parentTag = selectedNodeObject.parentTag;}
                                }
                                tempTreeArray[splitpath[nodeIndex]] = tempTreeArray[splitpath[nodeIndex]]||nodeObject;
                                tempTreeArray[splitpath[nodeIndex]].children=tempTreeArray[splitpath[nodeIndex]].children||{};
                                tempTreeArray=tempTreeArray[splitpath[nodeIndex]].children;
                            }
                        } else if(existNode && existNode!='noAction'){
                            var splitpath = existNode.path.replace(/^\/|\/$/g, "").split('/');
                            selectedNodeObject.path = existNode.path; 
                            splitpath.push(''+selectedNodeObject.nodeId+'');

                            for (nodeIndex=0;nodeIndex<splitpath.length;nodeIndex++)
                            {
                                nodeObject = { nodeId: splitpath[nodeIndex]};
                                if(nodeIndex == splitpath.length-1)
                                {
                                    {nodeObject.nodeId = selectedNodeObject.nodeId;nodeObject.tag = selectedNodeObject.tag;nodeObject.field = selectedNodeObject.field;nodeObject.class_data = selectedNodeObject.class_data;nodeObject.value = selectedNodeObject.value;nodeObject.status = selectedNodeObject.status;nodeObject.parentNodeId = selectedNodeObject.parentNodeId;nodeObject.path = selectedNodeObject.path;nodeObject.child = selectedNodeObject.child;nodeObject.nodeIndex = selectedNodeObject.nodeIndex;nodeObject.parentClass = selectedNodeObject.parentClass;nodeObject.parentTag = selectedNodeObject.parentTag;}
                                }
                                tempTreeArray[splitpath[nodeIndex]] = tempTreeArray[splitpath[nodeIndex]]||nodeObject;
                                tempTreeArray[splitpath[nodeIndex]].children=tempTreeArray[splitpath[nodeIndex]].children||{};
                                tempTreeArray=tempTreeArray[splitpath[nodeIndex]].children;
                            }
                            tempTreeArray[splitpath[splitpath.length-1]] = nodeObject;
                            tempTreeArray[splitpath[splitpath.length-1]].children={};
                            tempTreeArray=tempTreeArray[splitpath[splitpath.length-1]].children;
                        }
                        
                    }

                    selectedNodeData.map(addnodeTree);
                    var mainParentClassId = getGroupRootNode(viewData,outputTreeObject);
                    var mainParentClass = viewData[mainParentClassId].field;
                    var parentSplitpath = viewData[mainParentClassId].path.replace(/^\/|\/$/g, "").split('/');
                    var parentLevel = parentSplitpath.length;


                    var checkedNodeArray = [];
                    var checkedNodeLevel = [];
                    var duplicateElement = [];
                    
                    for (var nodeData = 0; nodeData < selectedNodeData.length; nodeData++) {
                        var splitClassArray = [];
                        var nodePath = selectedNodeData[nodeData].path;
                        var splitPath = nodePath.split('/');                        
                        for (var initData = 0; initData < splitPath.length; initData++) {
                            if (!!splitPath[initData]) {
                                var splitPathId = splitPath[initData];
                                var parentNodeClass = viewData[splitPathId].field;
                                
                                if (jQuery.inArray(parentNodeClass, splitClassArray) != -1) {
                                    duplicateElement.push({
                                        id: initData,
                                        class: parentNodeClass
                                    });
                                } 
                                splitClassArray.push([parentNodeClass,viewData[splitPathId].nodeId]);
                            }
                        }
                        checkedNodeArray.push(splitClassArray);
                    } 
                    for (var nodeData = 0; nodeData < selectedNodeData.length; nodeData++) {
                        var checkedNodeFlag = true;
                        checkedNodeLevel.forEach(function(nodeLevelItem){
                            if(selectedNodeData[nodeData].tag == nodeLevelItem.tag && selectedNodeData[nodeData].parentTag == nodeLevelItem.parentTag && selectedNodeData[nodeData].class_data == nodeLevelItem.class_data && selectedNodeData[nodeData].parentClass == nodeLevelItem.parentClass) {
                                checkedNodeFlag = false;
                            }
                        });
                        if(checkedNodeFlag)
                        {
                            var nodeLevel = checkedNodeArray[nodeData].length - parentLevel;
                            var nodeDistance = selectedNodeData[nodeData].nodeId - selectedNodeData[nodeData].parentNodeId;
                            
                            if(selectedNodeData[nodeData].nodeId > totalScrapeNode){
                                
                                var nodeDistance = (selectedNodeData[nodeData].parentNodeId - mainParentClassId) + (selectedNodeData[nodeData].nodeIndex + 1);
                            }

                            checkedNodeLevel.push({
                                tag: selectedNodeData[nodeData].tag,
                                field: selectedNodeData[nodeData].field,
                                class_data: selectedNodeData[nodeData].class_data,
                                level: nodeLevel,
                                position: selectedNodeData[nodeData].nodeIndex,
                                path: selectedNodeData[nodeData].path,
                                child: selectedNodeData[nodeData].child,
                                parentClass: selectedNodeData[nodeData].parentClass,
                                parentTag: selectedNodeData[nodeData].parentTag,
                                status: selectedNodeData[nodeData].status,
                                distance: nodeDistance
                            });
                        }
                    }
                    for (var initNode = 0; initNode < viewData.length; initNode++) {
                        if(viewData[initNode].checked) {
                            viewData[initNode].set('checked', false);
                        }
                    }
                    var domTreeView = jQuery("#htmlDomResultGrid").data("kendoTreeList");
                    for (var initData = 0; initData < checkedNodeLevel.length; initData++) {
                        var nodeTag = checkedNodeLevel[initData].tag;
                        var nodeField = checkedNodeLevel[initData].field;
                        var changedClass = checkedNodeLevel[initData].class_data;
                        var nodeLevel = checkedNodeLevel[initData].level;
                        var status = checkedNodeLevel[initData].status;
                        var nodePosition = checkedNodeLevel[initData].position;
                        var nodePath = checkedNodeLevel[initData].path;
                        var nodeParentTag = checkedNodeLevel[initData].parentTag;
                        var nodeParentClass = checkedNodeLevel[initData].parentClass;
                        var parentNodeDistance = checkedNodeLevel[initData].distance;
                        var nodeFieldData = isEmpty(nodeField);
                        if(nodeFieldData && nodeField != changedClass) {
                            var nodeClassData = changedClass;
                        }
                        else {
                            var nodeClassData = nodeField;
                        }

                        for (var initNode = 0; initNode < viewData.length; initNode++) {

                            if (viewData[initNode].tag == nodeTag && viewData[initNode].class_data == changedClass) {
                               
                                var nodeParentId = viewData[initNode].parentNodeId;
                                var parentNodeTag = viewData[nodeParentId].tag;
                                var parentNodeField = viewData[nodeParentId].field;
                                if (parentNodeTag == nodeParentTag && parentNodeField == nodeParentClass) {



                                    var splitNodePath = viewData[initNode].path.split('/');
                                    var pathLength = splitNodePath.length;
                                    var levelDiff = pathLength - nodeLevel - 1;
                                    var parentDataNode = splitNodePath[levelDiff];
                                    if (parentDataNode >= 0) {
                                        var parentField = viewData[parentDataNode].field;
                                        var parentClassId = viewData[parentDataNode].nodeId;
                                        var currentNodeDistance = viewData[initNode].nodeId - viewData[initNode].parentNodeId;    
                                        
                                        if(viewData[initNode].nodeId > totalScrapeNode){
                                            
                                            var currentNodeDistance = (viewData[initNode].parentNodeId - parentClassId) + (viewData[initNode].nodeIndex + 1);

                                        }                                        
                                        if (mainParentClass == parentField ) {
                                            viewData[initNode].set('checked', true);
                                            for (var nodeData = 0; nodeData < pathLength; nodeData++) {
                                                if (!!splitNodePath[nodeData]) {
                                                    var domNodeRow = domTreeView.content.find("tr").eq(splitNodePath[nodeData]);
                                                    domTreeView.expand(domNodeRow);
                                                }
                                            }
                                            var domClassData = isEmpty(viewData[initNode].class_data);
                                            if(nodeClassData && nodeClassData == viewData[initNode].class_data) {
                                                nodeChangeClass = nodeClassData;
                                            }
                                            else if(nodeClassData && domClassData) {
                                                nodeChangeClass = nodeClassData;
                                            }
                                            else {
                                                nodeChangeClass = viewData[initNode].class_data;
                                            }

                                            // NEED TO CHECK NODE CHANGE CLASS IS EXISTED
                                            const found = checkedNodeDataSource.some(el => el.tag == viewData[initNode].tag && el.parentNodeId == viewData[initNode].parentNodeId);
                                            if(found && currentNodeDistance == parentNodeDistance){
                                                checkedNodeDataSource.push({
                                                    tag: viewData[initNode].tag,
                                                    field: nodeChangeClass,
                                                    class_field: viewData[initNode].field,
                                                    value: viewData[initNode].value,
                                                    nodeId: viewData[initNode].nodeId,
                                                    parentNodeId: viewData[initNode].parentNodeId,
                                                    status: viewData[initNode].status,
                                                    rootParent: parseInt(parentDataNode),
                                                    identity_table_id: viewData[initNode].identityTableId,
                                                    identity_id: identityId
                                                });
                                            }else if (!found){
                                                checkedNodeDataSource.push({
                                                    tag: viewData[initNode].tag,
                                                    field: nodeChangeClass,
                                                    class_field: viewData[initNode].field,
                                                    value: viewData[initNode].value,
                                                    nodeId: viewData[initNode].nodeId,
                                                    parentNodeId: viewData[initNode].parentNodeId,
                                                    status: viewData[initNode].status,
                                                    rootParent: parseInt(parentDataNode),
                                                    identity_table_id: viewData[initNode].identityTableId,
                                                    identity_id: identityId
                                                });
                                            } 
                                        }
                                    }
                                }
                            }
                        }
                        $('.processCount').text(initData + 1);
                        nodeChangeClass = '';
                    }
                }
                
                var domainUrl = $('#website_uri').val();
                var domTreeView = jQuery("#htmlDomResultGrid").data("kendoTreeList");
                var allNodesData = domTreeView.dataSource.data();

                // UPDATE CHECKED NODE FOR SET ATTTRIBUTE ON USER SELECTED

                $(checkedNodeByUser).each(function(key,nodeId){

                    var nodeIndex = checkedNodeDataSource.findIndex(x => x.nodeId == nodeId);
                    checkedNodeDataSource[nodeIndex].checkByUser = true;
                });
                
                // SET DATASOURCE IN CHECKED NODE GRID FOR CHECKED TAB

                setCheckedNodeDataSource(checkedNodeDataSource);
                var checkedNodes = checkedNodeGrid.dataSource.data();
                
                var regexGroup = $('#label_id').val();
                $.ajax({
                    type: 'POST',
                    data: {
                        selected_node: JSON.stringify(checkedNodes),
                        all_dom_node: JSON.stringify(allNodesData),
                        domain_url: domainUrl,
                        regex_group: regexGroup,
                        _token: token
                    },
                    dataType: 'json',
                    url: requestUrl + "/saveDomNodeValues",
                    success: function(eventData) {}
                });
                
                $.ajax({
                    type: 'GET',
                    dataType: 'json',
                    async: false,
                    url: requestUrl + "/getMatchReferenceClass",
                    success: function(referenceClass) {
                        for (var initData = 0; initData < checkedNodes.length; initData++) {
                            if (jQuery.inArray(checkedNodes[initData].field, referenceClass) != -1) {
                                referenceValueData.push({
                                    node_id: checkedNodes[initData].nodeId,
                                    reference_key: checkedNodes[initData].field,
                                    reference_value: checkedNodes[initData].value
                                });
                            }
                        }

                        // SAVE ORIGINAL REFERENCE VALUE IN CROSSWALK SCHEMA
                        $.ajax({
                            type: 'POST',
                            data: {
                                _token: token,
                                crosswalk_data: JSON.stringify(referenceValueData)
                            },
                            url: requestUrl + "/saveCrosswalkData",
                            success: function(eventData) {                                    
                            }
                        });
                        
                        if(referenceValueData.length > 0) {
                            $.ajax({
                                type: 'POST',
                                data: {
                                    _token: token,
                                    reference_data: JSON.stringify(referenceValueData)
                                },
                                url: requestUrl + "/saveTempReferenceData",
                                success: function(eventData) {
                                    $('.content .preloader,.content img').hide();
                                    var referenceDataSource = new kendo.data.DataSource({
                                        pageSize: 10,
                                        autoSync: true,
                                        transport: {
                                            read: {
                                                url: requestUrl + "/getReferenceData",
                                                dataType: "json",
                                                type: "GET"
                                            },
                                            update: {
                                                data: {
                                                    _token: token,
                                                },
                                                url: requestUrl + "/updateReferenceData",
                                                type: "POST",
                                                complete: function(dataObject) {
                                                    $("#referenceDataGrid").data("kendoGrid").dataSource.read();
                                                }
                                            }
                                        },
                                        schema: {
                                            model: {
                                                id: 'id',
                                                fields: {
                                                    id: {
                                                        type: "number"
                                                    },
                                                    reference_key: {
                                                        type: "string",
                                                        editable: false
                                                    },
                                                    reference_value: {
                                                        type: "string",
                                                        editable: true
                                                    },
                                                }
                                            },
                                        }
                                    });
                                    $("#referenceDataGrid").data("kendoGrid").setDataSource(referenceDataSource);
                                    $('#reference_data_top_modal').modal("show");
                                    $('.processCompleted').text('Completed');
                                    $('.processCount').text(viewData.length);
                                }
                            });

                        }else{
                            $('.content .preloader,.content img').hide();
                        }
                    }
                });
            }
        }, 100);
    });

    function converDateToYYYYMMDD(date) {

        if(date.includes('-')){
            var seperator = '-';
        }else if(date.includes('/')){
            var seperator = '/';
        }

        var pieces = date.split(seperator);
        if(pieces[0] > 12 && pieces[0].length <= 2){
            date = pieces.reverse().join("-");
        }else if(pieces[0].length == 4){
            date = pieces[1]+"-"+pieces[2]+"-"+pieces[0];
        }
        var dateObj= new Date(date);
        
        year    = dateObj.getFullYear();
        month   = (dateObj.getMonth()+1) < 10 ? '0' + (dateObj.getMonth()+1) : (dateObj.getMonth()+1);
        day     = dateObj.getDate()  < 10 ? '0' + dateObj.getDate()  : dateObj.getDate();
        newDate = year.toString()+month.toString()+day.toString();

        return newDate;
    }

    function convertTimeToUnixTime(time) {

        var timeSeconds = 0;

        if(time.includes(':')){
            var seperator = ':';
        }else if(time.includes('.')){
            var seperator = '.';
        }

        var pieces = time.split(seperator);
        
        if(typeof(pieces[2]) == "undefined" && pieces[2] == null){
            pieces[2] = 0;
        }

        timeSeconds = parseInt(pieces[0])*3600 + parseInt(pieces[1])*60 + parseInt(pieces[2]);

        return timeSeconds;
    }

    function convertDatetime(datetime){

        if(datetime.includes(" ")){
            var seperator = " ";
        }else if(datetime.includes('_')){
            var seperator = '_';
        }

        var pieces = datetime.split(seperator);

        return converDateToYYYYMMDD(pieces[0])+"_"+convertTimeToUnixTime(pieces[1]);
    }

    $("#transformHtmlDomGrid").kendoGrid({
        dataSource: [],
        sortable: true,
        resizable: true,
        pageable: true,
        persistSelection: true,
        editable: true,
        columns: [{
            headerTemplate:"<input type='checkbox' class='allSelectRow' />",
            template: "<input type='checkbox' class='selectRow' data-bind='checked: checked' primaryKey='#=nodeId#' />",
            width: "20px",
            filterable: false
        }, {
            field: "nodeId",
            title: "Node #",
            filterable: false,
            width: "30px"
        }, {
            field: "value",
            title: "Orignal Value",
            width: "60px"
        }, {
            field: "primitiveCode",
            title: "Match Primitive Code",
            width: "60px"
        }, {
            field: "newvalue",
            title: "Transform Value",
            width: "60px"
        }],
        dataBound: function(eventData){
            var data = this.dataSource.data();
            $.each(data, function(i, row) {
               if (row.checked){
                $('tr[data-uid="' + row.uid + '"]').addClass("k-state-selected");
                $('tr[data-uid="' + row.uid + '"]').find(".selectRow").prop("checked",true);
               }
            });
        }      
        
    });

    $("#transformHtmlDomGrid").on("click", ".allSelectRow", function(eventData) {
        var checkedData = eventData.target.checked;
        $(".selectRow").each(function (idx, item) {
           if (checkedData) {
               if(!$(this).prop("checked")){
                   $(this).click();
                }
             } else {
               if($(this).prop("checked")){
                   $(this).click();
               }
            }
        });
    });

    $("#transformHtmlDomGrid").on("click", ".selectRow", function(eventData) {
       var numChkBoxes = $(".selectRow").length;
       var numChkBoxesChecked = $(".selectRow:checkbox:checked").length;
        if (numChkBoxes == numChkBoxesChecked && numChkBoxes > 0) {
           $(".allSelectRow").prop("checked", true);           
        }
        else {
           $(".allSelectRow").prop("checked", false);           
        }

        if($(this).prop("checked")){
            $(this).parent().parent().addClass("k-state-selected");
        }else{
            $(this).parent().parent().removeClass("k-state-selected");
        }
    });

    function setPrimitiveNodeDataSource(primitiveNodeDataSource) {
        var tabStrip = $("#tabstrip").kendoTabStrip().data("kendoTabStrip");
        var nodeDataSource = new kendo.data.DataSource({
            data: primitiveNodeDataSource,
            schema: {
                model: {
                    id: "nodeId",
                    fields: {
                        nodeId: {
                            type: "number",
                            editable: false
                        },
                        value: {
                            type: "string",
                            editable: false
                        },
                        primitiveCode: {
                            type: "string",
                            editable: false
                        },
                        newvalue: {
                            type: "string",
                            editable: false
                        }
                    }
                }
            },
            pageSize: 20
        });
        $("#transformHtmlDomGrid").data("kendoGrid").setDataSource(nodeDataSource);
        tabStrip.select(domConstants.TRANSFORM_GRID_TAB);
    }

    $('#transformPrimitive').click(function(){
        var transformNodeGrid = $("#transformHtmlDomGrid").data("kendoGrid");
        var viewData = $("#htmlDomResultGrid").data("kendoTreeList").dataSource.data();
        var transformNodeData = [];
        var primitivePattern;
        $.ajax({
            type: 'GET',
            data: {
                group_id: domConstants.PRIMITIVE_REGEX_GROUP
            },
            dataType: 'json',
            url: requestUrl + "/getGroupPatterns",
            success: function(regexPattern) {
                primitivePattern = regexPattern;
                $(viewData).each( function() {
                    var nodeId    = this.nodeId;
                    var nodeValue = this.value.trim();
                    if(nodeValue !=""){
                        $.each(primitivePattern, function(patternIndex, patternValue) {

                            if(patternValue.pattern.charAt(0)!='/'){
                                patternValue.pattern = "/"+patternValue.pattern;
                            }

                            if(patternValue.pattern.slice(-1)!='/'){
                                patternValue.pattern = patternValue.pattern+"/";
                            }
                            
                            var patternData = eval('(' + patternValue.pattern + ')');
                            var patternMatch = nodeValue.match(patternData);
                            if (patternMatch != null){
                                if(patternValue.type_name =="date"){
                                    var convertValue = converDateToYYYYMMDD(nodeValue);
                                }else if(patternValue.type_name =="time"){ 
                                    var convertValue = convertTimeToUnixTime(nodeValue);
                                }else if(patternValue.type_name =="datetime"){ 
                                    var convertValue = convertDatetime(nodeValue);
                                }

                                transformNodeData.push({
                                    nodeId: nodeId,
                                    value: nodeValue,
                                    newvalue: convertValue,
                                    primitiveCode: patternValue.primitive_code
                                });
                            } 
                        });
                    }        
                });
                setPrimitiveNodeDataSource(transformNodeData);  

                // STORE TRANSFORM NODE DATA IN JSON FILE
                var identityTableId = $('#identity_table').val();
                var identityId = $('#identity_id').val();
                var websiteUrl = getScrapeUrl();                
                var transformPrimitiveData = transformNodeGrid.dataSource.data();

                $('.content .preloader').show();
                $('.content img').show();
                $.ajax({
                    type: 'POST',
                    data: {
                        identity_id: identityId,
                        identity_table_id: identityTableId,
                        website_url: websiteUrl,
                        primitive_node: JSON.stringify(transformPrimitiveData),
                        flag:'transform',
                        _token: token
                    },
                    url: requestUrl + '/savePrimitiveValues',
                    success: function(eventData) {
                        $('.content .preloader,.content img').hide();
                    }
                });              
            }
        });
    });

    $('#transformApply').click(function(){
        
        var identityTableId = $('#identity_table').val();
        var identityId = $('#identity_id').val();
        var websiteUrl = getScrapeUrl();

        var tabStrip = $("#tabstrip").kendoTabStrip().data("kendoTabStrip");
        var viewData = $("#htmlDomResultGrid").data("kendoTreeList").dataSource.data();

        var transformPrimitiveGrid = $("#transformHtmlDomGrid").data("kendoGrid");
        var transformPrimitiveData = transformPrimitiveGrid.dataSource.view();        

        
        for (var initData = 0; initData < transformPrimitiveData.length; initData++){
            dataItem = transformPrimitiveGrid.table.find("tr[data-uid='" + transformPrimitiveData[initData].uid + "']");
            var checkbox = dataItem.find(".selectRow");
            if (checkbox.is(":checked")) {
              var found = checkedTransformNodeData.some(el => el.nodeId == transformPrimitiveData[initData].nodeId);
               if(!found){ 
                    checkedTransformNodeData.push({'nodeId':transformPrimitiveData[initData].nodeId,'value':transformPrimitiveData[initData].value,'newValue':transformPrimitiveData[initData].newvalue});
                    transformPrimitiveData[initData].checked = true;
                }
            }
        }

        for (var checkInitData = 0; checkInitData < checkedTransformNodeData.length; checkInitData++) {
            viewData[checkedTransformNodeData[checkInitData].nodeId].value = checkedTransformNodeData[checkInitData].newValue;
        }

        $('.content .preloader').show();
        $('.content img').show();
        $.ajax({
            type: 'POST',
            data: {
                identity_id: identityId,
                identity_table_id: identityTableId,
                website_url: websiteUrl,
                primitive_node: JSON.stringify(transformPrimitiveData),
                flag:'transform_apply',
                _token: token
            },
            url: requestUrl + '/savePrimitiveValues',
            success: function(eventData) {
                $('.content .preloader,.content img').hide();
            }
        });

        var treeListJsonData = JSON.parse(kendo.stringify(viewData));
        
        var urlDomDataSource = new kendo.data.TreeListDataSource({
                data: treeListJsonData,
                schema: {
                    model: {
                        id: "nodeId",
                        parentId: "parentNodeId",
                        fields: {
                            nodeId: {
                                type: "number",
                                editable: false
                            },
                            parentNodeId: {
                                field: "parentNodeId",
                                nullable: true
                            },
                            class_change_data: {
                                editable: false
                            },
                            class_data: {
                                type: "string"
                            },
                            ref_id: {
                                type: "number"
                            }
                        }
                    }
                },
                dataBound: function(e) {
                    window.kendo.ui.progress($("#htmlDomResultGrid"), false);
                }
            });

        
        var treelist = $("#htmlDomResultGrid").data("kendoTreeList");

        var emptyDataSource = new kendo.data.DataSource({
          data: []
        });

        treelist.setDataSource(emptyDataSource); 
        treelist.setDataSource(urlDomDataSource); 
        tabStrip.select(domConstants.DOM_GRID_TAB);
                
    });

    $('#checkedNodeDelete').click(function() {
                
        var checkedNodeLength = checkedNodes.length;
        if (checkedNodeLength > 0){
            setTimeout(function() {            
                $('.content .preloader').show();
                $('.content img').show();
                var checkedNodesData = checkedNodes[checkedNodeLength - 1]
                for (var initData = 0; initData < checkedNodesData.length; initData++) {
                    checkedNodeDataSource = checkedNodeDataSource.filter(function(nodeItem) {
                        return nodeItem.nodeId !== parseInt(checkedNodesData[initData]);
                    });
                }
                setCheckedNodeDataSource(checkedNodeDataSource);
                $('.content .preloader,.content img').hide();
            }, 50);    
        }
        
    });
        
    $("div#tabstrip ul li:nth-child(1)").on("click", function () {
        var urlStatusDataSource = new kendo.data.DataSource({
            pageSize: 10,
            transport: {
                read: {
                    url: requestUrl + "/getScrapeUrlStatus",
                    dataType: "json",
                    type: "GET"
                },
            },
            schema: {
                model: {
                    id: 'history_id',
                    fields: {
                        website_url: {
                            type: "string"
                        },
                        scrape_date: {
                            type: "string"
                        },
                        save_date: {
                            type: "string"
                        },
                        diff_changes: {
                            type: "string"
                        }
                    }
                },
            }
        });
        $("#checkedSavedHtmlDomGrid").data("kendoGrid").setDataSource(urlStatusDataSource);
    });
    $("#checkedSavedHtmlDomGrid").kendoGrid({
        dataSource: [],
        filterable: {
            mode: "row"
        },
        sortable: true,
        pageable: true,
        resizable: true,
        persistSelection: true,
        columns: [{
            field: "website_url",
            title: "Scrape URL",
            template: kendo.template(jQuery('#website_url_template').html()),
            filterable: {
                cell: {
                    operator: "contains",
                    suggestionOperator: "contains"
                }
            },
            width: 250
        }, {
            field: "scrape_date",
            title: "Last Scrape",
            filterable: {
                cell: {
                    operator: "contains",
                    suggestionOperator: "contains"
                }
            },
        }, {
            field: "save_date",
            title: "Last Save",
            filterable: false
        }, {
            field: "owner",
            title: "owner_id",
            hidden: true,
        }, {
            field: "owner_name",
            title: "Owner",
            filterable: false,
            width: 100
        }, {
            field: "diff_changes",
            title: "Changes",
            template: kendo.template(jQuery('#diff_changes_template').html()),
            filterable: false
        }, {
            template: "<button type='button' id='changesLoad' class='loadchange k-button'>#= 'Load' #</button>",
            title: "Load",
            width: "65px"
        }]
    });
    $('#checkedSavedHtmlDomGrid').on('click', '#changesLoad', function(eventData) {
        tabStrip.select(domConstants.DOM_GRID_TAB);
        var scrapeListKendoGrid = $("#checkedSavedHtmlDomGrid").data("kendoGrid");
        var dataItem = scrapeListKendoGrid.dataItem($(this).closest('tr'));
        var websiteUrl = dataItem.website_url;
        var identityId = dataItem.identity_id;
        var identityTableId = dataItem.identity_table_id;
        checkedNodeByUser = [];

        $('#website_uri').val(websiteUrl);
        $('#identity_id').val(identityId);
        $('#identity_table').data("kendoDropDownList").value(identityTableId);
        if(websiteUrl) {
            var urlData = websiteUrl.split("//");
            var firstUrlPart;
            if (urlData[1]) {
                firstUrlPart = urlData[1];
            } else {
                firstUrlPart = urlData[0];
            }

            var urlReplaceDot = firstUrlPart.replace(/\./g, '-');
            var urlReplaceSlash = urlReplaceDot.replace(/\//g, '_');
            var urlReplaceHash = urlReplaceSlash.replace(/\#/g, '');
            var urlDomFilePath = basePath + '/laravel/storage/htmldom/' + urlReplaceHash + '.all.json?'+version+"_"+Math.random();
            var urlCheckedDomFilePath = basePath + '/laravel/storage/htmldom/' + urlReplaceHash + '.checked.json?'+version+"_"+Math.random();
            var primitiveFilePath = basePath + '/laravel/storage/htmldom/' + urlReplaceHash + 'primitive.json?'+version+"_"+Math.random();
            
            if(UrlExists(urlDomFilePath)){
                $.getJSON(urlDomFilePath, function(allDomData) {
                    // GET PRIMITIVE JSON FILE DATA AND UPDATE HTMLDOM NODE
                    $.ajax({
                        url:primitiveFilePath,
                        success: function()
                        {
                            $.getJSON(primitiveFilePath, function(allPrimitiveData) {
                                if(allPrimitiveData.length > 0){

                                    var transformPrimitiveDataSource = new kendo.data.DataSource({
                                        data: allPrimitiveData,
                                        schema: {
                                            model: {
                                                id: "nodeId",
                                                fields: {
                                                    nodeId: {
                                                        type: "number",
                                                        editable: false
                                                    },
                                                    value: {
                                                        type: "string",
                                                        editable: false
                                                    },
                                                    primitiveCode: {
                                                        type: "string",
                                                        editable: false
                                                    },
                                                    newvalue: {
                                                        type: "string",
                                                        editable: false
                                                    }
                                                }
                                            }
                                        },
                                        pageSize: 20
                                    });

                                    transformHtmlDomGrid = $("#transformHtmlDomGrid").data("kendoGrid");
                                    transformHtmlDomGrid.setDataSource(transformPrimitiveDataSource); 

                                    $(allPrimitiveData).each( function() {
                                        if(this.checked){
                                            allDomData.scrapedDom[this.nodeId].value = this.newvalue;
                                            checkedTransformNodeData.push({'nodeId':this.nodeId,'value':this.value,'newValue':this.newvalue});
                                        }                                       
                                    });                                   
                                }
                            });                        
                        }
                    });             

                    setTimeout(function(){
                        var urlDomDataSource = new kendo.data.TreeListDataSource({
                            data: allDomData.scrapedDom,
                            schema: {
                                model: {
                                    id: "nodeId",
                                    parentId: "parentNodeId",
                                    fields: {
                                        nodeId: {
                                            type: "number",
                                            editable: false
                                        },
                                        parentNodeId: {
                                            field: "parentNodeId",
                                            nullable: true
                                        },
                                        class_change_data: {
                                            editable: false
                                        },
                                        class_data: {
                                            type: "string"
                                        },
                                        ref_id: {
                                            type: "number"
                                        }
                                    }
                                }
                            },
                        });

                        $("#htmlDomResultGrid").data("kendoTreeList").setDataSource(urlDomDataSource);
                        
                        var regexGroup = allDomData.regex_group;
                        $('#label_id').data("kendoComboBox").value(regexGroup);
            
                        // ASSIGN CURRENT JSON DATA SOURCE TO RESULT DATA SOURCE                
                        resultDataSource = urlDomDataSource;
                        autoExpand = 1;
                        
                    },1000);     
                });
            }
            
            if(UrlExists(urlCheckedDomFilePath)){
                $.getJSON(urlCheckedDomFilePath, function(allCheckedDomData) {
                    $(allCheckedDomData).each( function() {
                        if(this.hasOwnProperty("checkByUser")){
                            checkedNodeByUser.push(this.nodeId);
                        }    
                    });
                });
            }
        }
    });

    function UrlExists(filepath)
    {
        $.ajax({
            url:filepath,
            type: 'HEAD',
            error: function()
            {
                return false;
            },
            success: function()
            {
                return true;
            }
        });
    }

    function extraForeignColumnEditor(container, options) {
        var mappingTable = options.model.mapping_table;
        $('<input id="extraForeignColumn" data-text-field="column_name" data-value-field="column_name" data-bind="value:' + options.field + '"/>').appendTo(container).kendoDropDownList({
            dataSource: {
                transport: {
                    read: {
                        data: {
                            target_table: mappingTable,
                        },
                        dataType: "json",
                        url: requestUrl + "/getTargetTableColumns",
                        type: "GET"
                    }
                }
            },
            dataTextField: "column_name",
            dataValueField: "column_name"
        });
    }

    var mapTargetFieldsGrid = jQuery("#mapTargetFieldsGrid").kendoGrid({
        pageable: {
            refresh: true,
            pageSizes: true
        },
        scrollable: true,
        autoSync: false,
        sortable: true,
        reorderable: true,
        groupable: true,
        resizable: true,
        editable: true,
        columns: [{
            field: "map_id",
            title: "Map Id#",
            hidden: true,
        }, {
            field: "dom_class",
            title: "Class",
            filterable: false,
            width: "85px",
        }, {
            field: "mapping_table",
            title: "Mapping Table",
            editor: mappingTableEditor,
            filterable: false,
            width: "110px",
        }, {
            field: "mapping_value_column",
            title: "Mapping Value Column",
            editor: mappingValueColumnEditor,
            filterable: false,
            width: "150px"
        }, {
            field: "target_table",
            title: "Target Table",
            editor: targetTableEditor,
            filterable: false,
            width: "100px"
        }, {
            field: "mapping_foreign_column",
            title: "Mapping Foreign Column",
            editor: mappingForeignColumnEditor,
            filterable: false,
            width: "150px"
        }],
        dataBound: function(eventData) {
            eventData.preventDefault();
            var currentDomClass = $('#domClass').val();
            var currentData = eventData.sender._data;
            var identityId = $('#identity_id').val();
            if(identityId && targetMapBound) {
                var mapDataSource = $("#mapTargetFieldsGrid").data("kendoGrid").dataSource;
                setTimeout(function() {
                    var newFilter = {
                        field: "dom_class",
                        operator: "contains",
                        value: currentDomClass
                    };
                    var domFilters = null;
                    if (mapDataSource.filter() != null) {
                        domFilters = mapDataSource.filter().filters;
                    }
                    mapDataSource.filter(domFilters);
                    targetMapBound = false;
                }, 3000);
            }
        }
    });

    function mappingForeignColumnEditor(container, options) {
        var mappingTable = options.model.mapping_table;
        $('<input id="mappingForeignColumn" data-text-field="column_name" data-value-field="column_name" data-bind="value:' + options.field + '"/>').appendTo(container).kendoDropDownList({
            dataSource: {
                transport: {
                    read: {
                        data: {
                            target_table: mappingTable,
                        },
                        dataType: "json",
                        url: requestUrl + "/getTargetTableColumns",
                        type: "GET"
                    }
                }
            },
            dataTextField: "column_name",
            dataValueField: "column_name"
        });
    }

    function mappingTableEditor(container, options) {
        $('<input data-text-field="table_name" data-value-field="table_name" data-bind="value:' + options.field + '"/>').appendTo(container).kendoDropDownList({
            dataSource: {
                transport: {
                    read: {
                        dataType: "json",
                        url: requestUrl + "/getTargetTables",
                        type: "GET"
                    }
                }
            },
            dataTextField: "table_name",
            dataValueField: "table_name"
        });
    }

    function targetIdColumnEditor(container, options) {
        var targetTable = options.model.target_table;
        $('<input id="targetIdColumn" data-text-field="column_name" data-value-field="column_name" data-bind="value:' + options.field + '"/>').appendTo(container).kendoDropDownList({
            dataSource: {
                transport: {
                    read: {
                        data: {
                            target_table: targetTable,
                        },
                        dataType: "json",
                        url: requestUrl + "/getTargetTableColumns",
                        type: "GET"
                    }
                }
            },
            dataTextField: "column_name",
            dataValueField: "column_name"
        });
    }

    function targetTableEditor(container, options) {
        $('<input data-text-field="table_name" data-value-field="table_name" data-bind="value:' + options.field + '"/>').appendTo(container).kendoDropDownList({
            dataSource: {
                transport: {
                    read: {
                        dataType: "json",
                        url: requestUrl + "/getTargetTables",
                        type: "GET"
                    }
                }
            },
            dataTextField: "table_name",
            dataValueField: "table_name"
        });
    }

    function referenceColumnEditor(container, options) {
        var mappingTable = options.model.mapping_table;
        $('<input id="referenceColumn" data-text-field="column_name" data-value-field="column_name" data-bind="value:' + options.field + '"/>').appendTo(container).kendoDropDownList({
            dataSource: {
                transport: {
                    read: {
                        data: {
                            target_table: mappingTable,
                        },
                        dataType: "json",
                        url: requestUrl + "/getTargetTableColumns",
                        type: "GET"
                    }
                }
            },
            dataTextField: "column_name",
            dataValueField: "column_name"
        });
    }

    function targetTableCodeEditor(container, options) {
        var targetTable = options.model.target_table;
        var referenceColumn = options.model.reference_column;
        if(referenceColumn == 'asset_id') {
            $('<input id="targetTableCodeColumn" data-text-field="target_table_code" data-value-field="target_table_ref_id" data-bind="value:' + options.field + '"/>').appendTo(container).kendoDropDownList({
                dataSource: {
                    transport: {
                        read: {
                            data: {
                                target_table: targetTable,
                            },
                            dataType: "json",
                            url: requestUrl + "/getAllAsset",
                            type: "GET"
                        }
                    }
                },
                dataTextField: "target_table_code",
                dataValueField: "target_table_ref_id",
                filter: "contains"
            });
        } else if(referenceColumn == 'social_id') {
            $('<input id="targetTableCodeColumn" data-text-field="target_table_code" data-value-field="target_table_ref_id" data-bind="value:' + options.field + '"/>').appendTo(container).kendoDropDownList({
                dataSource: {
                    transport: {
                        read: {
                            dataType: "json",
                            url: requestUrl + "/getAllSocials",
                            type: "GET"
                        }
                    }
                },
                dataTextField: "target_table_code",
                dataValueField: "target_table_ref_id",
                filter: "contains"
            });
        }
    }

    function onTargetTableSelect(eventData) {
        var dataItem = this.dataItem(eventData.item);
        var tableName = dataItem.table_code;
        var classColumnDataSource = new kendo.data.DataSource({
            transport: {
                read: {
                    data: {
                        target_table: tableName,
                    },
                    url: requestUrl + "/getTargetTableColumns",
                    dataType: "json",
                    type: "GET"
                },
            },
        });
    }

    function mappingValueColumnEditor(container, options) {
        var mappingTable = options.model.mapping_table;
        $('<input id="mappingValueColumn" data-text-field="column_name" data-value-field="column_name" data-bind="value:' + options.field + '"/>').appendTo(container).kendoDropDownList({
            dataSource: {
                transport: {
                    read: {
                        data: {
                            target_table: mappingTable,
                        },
                        dataType: "json",
                        url: requestUrl + "/getTargetTableColumns",
                        type: "GET"
                    }
                }
            },
            dataTextField: "column_name",
            dataValueField: "column_name"
        });
    }

    var referenceDataGrid = jQuery("#referenceDataGrid").kendoGrid({
        pageable: {
            refresh: true,
            pageSizes: true
        },
        scrollable: true,
        autoSync: false,
        filterable: {
            mode: "row"
        },
        sortable: true,
        reorderable: true,
        groupable: true,
        resizable: true,
        editable: true,
        columns: [{
            command: {
                text: "X",
                click: deleteReferenceData
            },
            title: "&nbsp;",
            width: "30px"
        }, {
            field: "id",
            title: "Id#",
            hidden: true,
        }, {
            field: "node_id",
            title: "node_id",
            hidden: true,
        }, {
            field: "user_id",
            title: "User Id#",
            hidden: true,
        }, {
            field: "original_value",
            title: "Orignal Reference Value",
            hidden: true,
        }, {
            field: "reference_key",
            title: "Reference Key",
            editable: false,
            filterable: {
                cell: {
                    operator: "contains",
                    suggestionOperator: "contains"
                }
            },
            width: "130px",
        }, {
            field: "reference_value",
            title: "Reference Value",
            filterable: {
                cell: {
                    operator: "contains",
                    suggestionOperator: "contains"
                }
            },
            width: "150px",
        }]
    });

    function deleteReferenceData(eventData) {
        eventData.preventDefault();
        $('.content .preloader').show();
        $('.content img').show();
        var dataItem = this.dataItem($(eventData.currentTarget).closest("tr"));
        jQuery.ajax({
            type: 'POST',
            data: {
                _token: token,
                id: dataItem.id,
            },
            url: requestUrl + "/deleteReferenceData",
            success: function(referenceData) {
                $("#referenceDataGrid").data("kendoGrid").dataSource.read();
                $('.content .preloader,.content img').hide();
            }
        });
    }

    $('#textTransform').click(function() {
        $('.content .preloader').show();
        $('.content img').show();
        setTimeout(function() {
	        var referenceGrid = $("#referenceDataGrid").data("kendoGrid");
	        var referenceData = referenceGrid.dataSource.data();
            // CREATE AN ARRAY FOR STORE TARGET NODE ID WITH REFERENCE VALUE
            var targetReferenceNodes = new Array();
            
	        $(referenceData).each(function() {
                this.original_value = this.reference_value;
	        	this.reference_value = this.reference_value.charAt(0).toUpperCase() + this.reference_value.slice(1).toLowerCase();
                var nodeIndex = checkedNodeDataSource.findIndex(x => x.nodeId == this.node_id);
                checkedNodeDataSource[nodeIndex].value = this.reference_value;
	        });

            var referenceDataSource = new kendo.data.DataSource({
	            data: referenceData,
	            schema: {
	                model: {
	                    id: 'id',
	                    fields: {
	                        id: {
	                            type: "number"
	                        },
	                        reference_key: {
	                            type: "string",
	                            editable: false
	                        },
	                        reference_value: {
	                            type: "string",
	                            editable: true
	                        },
	                    }
	                },
	            },
	            pageSize: 10
	        });
	        $("#referenceDataGrid").data("kendoGrid").setDataSource(referenceDataSource);
            setCheckedNodeDataSource(checkedNodeDataSource);

            // UPDATE CHECKED NODE JSON FILE
            var domainUrl = $('#website_uri').val();
            var checkedNodeGrid = $("#checkedHtmlDomGrid").data("kendoGrid");
            var checkedNodes = checkedNodeGrid.dataSource.data();
            $.ajax({
                type: 'POST',
                data: {
                    selected_node: JSON.stringify(checkedNodes),
                    all_dom_node: '',
                    domain_url: domainUrl,
                    regex_group: '',
                    _token: token
                },
                url: requestUrl + "/saveDomNodeValues",
                success: function(eventData) {}
            });

	        $('.content .preloader,.content img').hide();
        }, 50);
    });

    $('#referenceCancel').click(function() {
        $('#reference_data_top_modal').modal("hide");
        tabStrip.select(domConstants.TRANSFORM_GRID_TAB);
    });

    $('#referenceContinue').click(function() {
        $('.content .preloader').show();
        $('.content img').show();
        setTimeout(function() {
            var referenceGrid = $("#referenceDataGrid").data("kendoGrid");
            var referenceData = referenceGrid.dataSource.data();
            $.ajax({
                type: 'POST',
                data: {
                    reference_data: JSON.stringify(referenceData),
                    _token: token
                },
                url: requestUrl + "/saveLookupData",
                success: function(eventData) {
                    $('.content .preloader,.content img').hide();
                    $('#reference_data_top_modal').modal("hide");
                    $.ajax({
                        type: 'POST',
                        data: {
                            _token: token,
                        },
                        url: requestUrl + "/cleanUserReferenceData",
                        success: function(eventData) {}
                    });
                    tabStrip.select(domConstants.CHECKED_GRID_TAB);
                }
            });
        }, 50);
    });

    $('#expandAll').click(function() {
        $('.content .preloader').show();
        $('.content img').show();
        setTimeout(function() {
            var domAllRows = $("tr.k-treelist-group", kendoTreeResult.tbody);
            $.each(domAllRows, function(indexData, rowData) {
                kendoTreeResult.expand(rowData);
            });
            $('.content .preloader,.content img').hide();
        }, 50);
    });

    $('#collapseAll').click(function() {
        $('.content .preloader').show();
        $('.content img').show();
        setTimeout(function() {
            var domAllRows = $("tr.k-treelist-group", kendoTreeResult.tbody);
            $.each(domAllRows, function(indexData, rowData) {
                kendoTreeResult.collapse(rowData);
            });
            $('.content .preloader,.content img').hide();
        }, 50);
    });

    $('#mapNodeValue').click(function() {
        var identityTableId = $('#identity_table').val();
        var identityId = $('#identity_id').val();
        $('.content .preloader').show();
        $('.content img').show();
        var checkedNodeGrid = $("#checkedHtmlDomGrid").data("kendoGrid");
        var checkedNodes = checkedNodeGrid.dataSource.data();
        $.ajax({
            type: 'POST',
            data: {
                identity_id: identityId,
                identity_table_id: identityTableId,
                checked_node: JSON.stringify(checkedNodes),
                _token: token
            },
            url: requestUrl + '/saveTargetMappingValues',
            success: function(responseData) {
                var response = jQuery.parseJSON(responseData);                
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
                var $toast = toastr[response.type]("",response.message);
                $('.content .preloader,.content img').hide();
            }
        });
    });

    $(document).on("click","#applySplit",function(){
        var viewData = $("#htmlDomResultGrid").data("kendoTreeList").dataSource.data();
        var identityTableId = $('#identity_table').val();
        var identityId = $('#identity_id').val();
        var websiteUrl = getScrapeUrl();

        var tabStrip = $("#tabstrip").kendoTabStrip().data("kendoTabStrip");
        window.kendo.ui.progress($("#htmlDomResultGrid"), true);
        
        $.ajax({
            type: 'POST',
            data: {
                identity_id: identityId,
                identity_table_id: identityTableId,
                website_url: websiteUrl,
                html_node: JSON.stringify(viewData),
                _token: token
            },
            url: requestUrl + '/applySplitOnNodeValue',
            success: function(treeListJsonData) {

                var urlDomDataSource = new kendo.data.TreeListDataSource({
                    data: treeListJsonData,
                    schema: {
                        model: {
                            id: "nodeId",
                            parentId: "parentNodeId",
                            fields: {
                                nodeId: {
                                    type: "number",
                                    editable: false
                                },
                                parentNodeId: {
                                    field: "parentNodeId",
                                    nullable: true
                                },
                                class_change_data: {
                                    editable: false
                                },
                                class_data: {
                                    type: "string"
                                },
                                ref_id: {
                                    type: "number"
                                }
                            }
                        }
                    },
                    dataBound: function(e) {
                        window.kendo.ui.progress($("#htmlDomResultGrid"), false);
                    }
                });
        
                var treelist = $("#htmlDomResultGrid").data("kendoTreeList");
                
                treelist.setDataSource(urlDomDataSource); 
                tabStrip.select(domConstants.DOM_GRID_TAB);
            }
        });
    });

    function enableScraping(){
        $("#resultSubmitBtn").prop('disabled', false);
        $("#applySplit").prop('disabled', true);
        $("#transformPrimitive").prop('disabled', true);
        $("#nodeExpandBtn").prop('disabled', true);
        $("#checkFilteredRaw").prop('disabled', true);
        $("#expandAll").prop('disabled', true);
        $("#collapseAll").prop('disabled', true);
        $("#refreshTreelist").prop('disabled', true);
    }

    function disableScraping(){
        $("#resultSubmitBtn").prop('disabled', true);
        $("#applySplit").prop('disabled', false);
        $("#transformPrimitive").prop('disabled', false);
        $("#nodeExpandBtn").prop('disabled', false);
        $("#checkFilteredRaw").prop('disabled', false);
        $("#expandAll").prop('disabled', false);
        $("#collapseAll").prop('disabled', false);
        $("#refreshTreelist").prop('disabled', false);
    }

    $('#website_uri').on("change",function(){
        enableScraping();
    });
});
var elementMaxPath = [];
function isEmptyObject(obj) {
    for(var key in obj) {
        if(obj.hasOwnProperty(key))
            return false;
    }
    return true;
}
function getDomDepthLevel(root = document.documentElement) {
    for(element in root){
        if(typeof(root[element].children) === 'object' && !isEmptyObject(root[element].children))
        {
            getDomDepthLevel(root[element].children)
        } else {
            var elementPath = root[element].path.replace(/^\/|\/$/g, "").split('/');
            if(elementPath.length > elementMaxPath.length)
            {
                Array.prototype.splice.apply(elementPath, [0, elementMaxPath.length].concat(elementMaxPath));
                elementMaxPath = elementPath;
            }
        }
    }
    return elementMaxPath;
}

function searchTree(elements,path,nodeId,parentNodeId,tag,parentTag,classData,parentClass){
    var splitpath = path.replace(/^\/|\/$/g, "").split('/');
    var selectedNode = {};
    for(element in elements){
        if(elements[element].tag == tag && elements[element].parentTag == parentTag && elements[element].class_data == classData && elements[element].parentClass == parentClass && elements[element].parentNodeId != parentNodeId){
            return 'noAction';
        } else if(typeof(elements[element].children) === 'object' && !isEmptyObject(elements[element].children)) {
            var foundElement = searchTree(elements[element].children,path,nodeId,parentNodeId, tag,parentTag,classData,parentClass);
            return foundElement;
        } 
        
        var elementPath = getDomDepthLevel(elements);
        if(elementPath.length > splitpath.length)
        {
            var elementPath = elementPath.slice(0,splitpath.length);
        }
        Array.prototype.splice.apply(splitpath, [0, elementPath.length].concat(elementPath));
        
    }
    selectedNode.path = splitpath.join('/')+'/';
    return selectedNode;
}

function countProperties (obj) {
    var count = 0;

    for (var property in obj) {
        if (Object.prototype.hasOwnProperty.call(obj, property)) {
            count++;
        }
    }

    return count;
}

function getGroupRootNode(domGridObject,elements)
{
    for(element in elements){
        if(typeof(elements[element].children) === 'object' && !isEmptyObject(elements[element].children)) {
            if(countProperties(elements[element].children) > 1){
                var nodeId = elements[element].nodeId;
                if(!!domGridObject[nodeId].field)
                {
                    return parseInt(elements[element].nodeId);
                } else {
                    var parentSplitpath = domGridObject[nodeId].path.replace(/^\/|\/$/g, "").split('/');
                    return getGroupParentClass(domGridObject,parentSplitpath)
                }

            }
            return getGroupRootNode(domGridObject,elements[element].children);
        } 
    }
}

function getGroupParentClass(domGridObject, parentSplitpath) {
    var parentSplitpath = parentSplitpath.reverse();
    for (nodeIndex = 0;nodeIndex < parentSplitpath.length;nodeIndex++) {
        var parentSplitPathNodeId = parseInt(parentSplitpath[nodeIndex]);
        if(!!domGridObject[parentSplitPathNodeId].field)
        {
            return domGridObject[parentSplitPathNodeId].nodeId;
        }
    }
}