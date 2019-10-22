$(document).ready(function() {
    var token = $('input[name="_token"]').val();    
    var requestUrl = $("#requestUrl").val();

    $(".select21").select2({
       theme: "bootstrap",
       placeholder: "Please Select Option",
       width: '100%'       
    });


    $("#tabstrip").kendoTabStrip().data("kendoTabStrip");
    var referenceTableDataSource = new kendo.data.DataSource({
        transport: {
            read: {
                dataType: "json",
                url: requestUrl + "/getRegexReferenceTable",
                type: "GET"
            }
        }
    });
    var categoryDataSource = new kendo.data.DataSource({
        transport: {
            read: {
                dataType: "json",
                url: requestUrl + "/getRegexCategories",
                type: "GET"
            }
        }
    });
    var kendoRegexType = $("#type_id").data("kendoComboBox");
    var kendoRegexField = $("#field_id").data("kendoComboBox");
    var kendoRegexCatgory = $("#name_id").data("kendoComboBox");
    var kendoReferenceTableCatgory = $("#reference_table_id").data("kendoComboBox");
    var regexSetupGrid = jQuery("#regexSetupGrid").kendoGrid({
        dataSource: {
            serverPaging: true,
            pageSize: 10,
            transport: {
                read: {
                    url: requestUrl + "/getRegexPatterns",
                    dataType: "json",
                    type: "GET",
                    data: {setup_type_id:'1'}
                }
            },
            schema: {
                total: 'total',
                data: 'regex_patterns',
                model: {
                    id: 'pattern_id',
                    fields: {
                        ref_table: {
                            defaultValue: {
                                table_code: "identity_social"
                            }
                        },
                        ref_column: {
                            defaultValue: {
                                column_name: "identity_code"
                            }
                        },
                        ref_id: {
                            editable: false
                        },
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
        filterable: {
            extra: false
        },
        columnMenu: true,
        columnMenuInit: onColumnMenuInit,
        serverFiltering: true,
        resizable: true,
        editable: 'incell',
        dataBound: function() {
            var grid = regexSetupGrid.data("kendoGrid");
            var gridData = grid.dataSource.view();
            for (var i = 0; i < gridData.length; i++) {
                var currentUid = gridData[i].uid;
                if (gridData[i].pattern_id == 0) {
                    var currenRow = grid.table.find("tr[data-uid='" + currentUid + "']");
                    var editButton = $(currenRow).find(".k-grid-X");
                    editButton.hide();
                    var verifyButton = $(currenRow).find(".verify");
                    verifyButton.hide();
                }
            }
            var data = this.dataSource.data();
                $.each(data, function(i, row) {
                var is_verified = row.get("is_verified");
                var pattern_id = row.get("pattern_id");
                if (!is_verified && pattern_id !=0) {
                    $('tr[data-uid="' + row.uid + '"] td:nth-child(14)').css("background-color", "red");        
                }else if (is_verified && pattern_id !=0) {
                    $('tr[data-uid="' + row.uid + '"] td:nth-child(14)').css("background-color", "green");
                }    
            });
        },
        columns: [{
            command: {
                text: "X",
                click: deleteRegexPattern
            },
            title: "&nbsp;",
            width: "90px"
        }, {
            field: "pattern_id",
            title: "Pattern Id",
            hidden: true,
        }, {
            field: "pattern",
            title: "Pattern",
            width: 140,
            filterable: false
        }, {
            field: "name_id",
            title: "Regex Category",
            editor: regexLabelComboBoxEditor,
            template: "#=(data.name)?data.name:'None'#",
            width: 140
        }, {
            field: "type_name",
            title: "Regex Type",
            editor: regexTypeComboBoxEditor,
            template: "#=(data.type_name)?data.type_name:'None'#",
            width: 140
        }, {
            field: "field_name",
            title: "Regex Field",
            editor: regexFieldComboBoxEditor,
            template: "#=(data.field_name)?data.field_name:'None'#",
            width: 140
        }, {
            field: "pattern_format",
            title: "Pattern Format",
            filterable: false,
            width: 140
        }, {
            field: "ref_class",
            title: "Ref Class",
            filterable: false,
            template: "#=(data.ref_class)?data.ref_class:'None'#",
            width: 90,
            editable: false
        }, {
            field: "ref_table",
            title: "Ref Table",
            editor: regexReferenceTableDropDownEditor,
            template: "#=(ref_table)?ref_table:'None'#",
            width: 140
        }, {
            field: "ref_column",
            title: "Ref Column",
            editor: regexColumnDropDownEditor,
            template: "#=(ref_column)?ref_column:'None'#",
            filterable: false,
            width: 120
        }, {
            field: "ref_id",
            title: "Ref Id",
            template: "#=(ref_id)?ref_id:' '#",
            filterable: false,
            width: 90
        }, {
            field: "language_id",
            title: "Language",
            editor: languageDropDownEditor,
            template: "#=data.language_code#",
            filterable: false,
            width: 140
        }, {
            field: "pattern_interval",
            title: "Interval",
            filterable: false,
            width: 80
        }, {
            template: "<button type='button' class='verify k-button'>Verify</button>",
            title: " ",
            filterable: false,
            width: "81px",
        }],
        save: function(data) {
            var name_id = field_id = type_id = pattern = pattern_format = language_id = null;
            var pattern_interval = 0;
            var language_id = 1;
            var setupTypeId = 1;
            var ref_class = ref_table = ref_column = '';
            if (data.values.name_id) {
                if (data.values.name_id.name_id) {
                    name_id = data.values.name_id.name_id;
                } else {
                    name_id = data.values.name_id;
                }
            } else {
                name_id = data.model.name_id;
            }
            if (data.values.type_name) {
                if (data.values.type_name.type_name) {
                    type_id = data.values.type_name.type_name;
                } else {
                    type_id = data.values.type_name;
                }
            } else {
                type_id = data.model.type_name;
            }
            if (data.values.pattern) {
                if (data.values.pattern.pattern) {
                    pattern = data.values.pattern.pattern;
                } else {
                    pattern = data.values.pattern;
                }
            } else {
                pattern = data.model.pattern;
            }
            if (data.values.pattern_format) {
                if (data.values.pattern_format.pattern_format) {
                    pattern_format = data.values.pattern_format.pattern_format;
                } else {
                    pattern_format = data.values.pattern_format;
                }
            } else {
                pattern_format = data.model.pattern_format;
            }
            if (data.values.language_id) {
                if (data.values.language_id.language_id) {
                    language_id = data.values.language_id.language_id;
                } else {
                    language_id = data.values.language_id;
                }
            } else {
                language_id = data.model.language_id;
            }
            if (data.values.pattern_interval) {
                if (data.values.pattern_interval.pattern_interval) {
                    pattern_interval = data.values.pattern_interval.pattern_interval;
                } else {
                    pattern_interval = data.values.pattern_interval;
                }
            } else {
                pattern_interval = data.model.pattern_interval;
            }
            if (data.values.field_name) {
                if (data.values.field_name.field_name) {
                    field_id = data.values.field_name.field_name;
                } else {
                    field_id = data.values.field_name;
                }
            } else {
                field_id = data.model.field_name;
            }
            if (data.values.ref_table) {
                if (data.values.ref_table.ref_table) {
                    ref_table = data.values.ref_table.ref_table;
                } else {
                    ref_table = data.values.ref_table;
                }
            } else {
                ref_table = data.model.ref_table;
            }
            if (data.values.ref_column) {
                if (data.values.ref_column.ref_column) {
                    ref_column = data.values.ref_column.ref_column;
                } else {
                    ref_column = data.values.ref_column;
                }
            } else {
                ref_column = data.model.ref_column;
            }
            if (data.values.ref_class) {
                if (data.values.ref_class.ref_class) {
                    ref_class = data.values.ref_class.ref_class;
                } else {
                    ref_class = data.values.ref_class;
                }
            } else {
                ref_class = data.model.ref_class;
            }
            jQuery.ajax({
                type: 'POST',
                data: {
                    pattern_id: data.model.pattern_id,
                    type_name: type_id,                    
                    pattern: pattern,
                    pattern_format: pattern_format,
                    language_id: language_id,
                    pattern_interval: pattern_interval,
                    field_name: field_id,
                    ref_table: ref_table,
                    name_id: name_id,
                    ref_class: ref_class,
                    ref_column: ref_column,
                    setup_type_id: setupTypeId,
                    _token: token
                },
                url: requestUrl + "/updateIncellEditRegexPattern",
                success: function(regexTypeDetails) {
                        response = regexTypeDetails.type;
                        if(response.localeCompare("success") == 0){
                            message = regexTypeDetails.message;
                        }else if (response.localeCompare("error") == 0){
                            message = regexTypeDetails.message;
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
                        kendoGrid.dataSource.read();
                    }
            });
        },
    });

    var regexExtractGrid = jQuery("#regexExtractGrid").kendoGrid({
        dataSource: {
            serverPaging: true,
            pageSize: 10,
            transport: {
                read: {
                    url: requestUrl + "/getRegexPatterns",
                    dataType: "json",
                    type: "GET",
                    data: {setup_type_id:'2'}
                }
            },
            schema: {
                total: 'total',
                data: 'regex_patterns',
                model: {
                    id: 'pattern_id',
                    fields: {
                        ref_id: {
                            editable: false
                        },
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
        filterable: {
            extra: false
        },
        columnMenu: true,
        columnMenuInit: onColumnMenuInit,
        serverFiltering: true,
        resizable: true,
        editable: 'incell',
        dataBound: function() {
            var grid = regexExtractGrid.data("kendoGrid");
            var gridData = grid.dataSource.view();
            for (var i = 0; i < gridData.length; i++) {
                var currentUid = gridData[i].uid;
                if (gridData[i].pattern_id == 0) {
                    var currenRow = grid.table.find("tr[data-uid='" + currentUid + "']");
                    var editButton = $(currenRow).find(".k-grid-X");
                    editButton.hide();
                    var lookupDetailsButton = $(currenRow).find("#Lookup");
                    lookupDetailsButton.hide();
                    var crosswalkDetailsButton = $(currenRow).find("#Crosswalk");
                    crosswalkDetailsButton.hide();
                    var refDetailsButton = $(currenRow).find(".k-button");
                    refDetailsButton.hide();
                }
            }
        },
        columns: [{
            field: "pattern_id",
            title: "Pattern Id",
            hidden: true,
        }, {
            field: "field_name",
            title: "Regex Field",
            editor: regexFieldComboBoxEditor,
            template: "#=(data.field_name)?data.field_name:'None'#",
            width: 140
        }, {
            field: "ref_table",
            title: "Ref Table",
            editor: regexReferenceTableDropDownEditor,
            template: "#=(ref_table)?ref_table:'None'#",
            width: 140
        }, {
            field: "ref_column",
            title: "Ref Column",
            editor: regexColumnDropDownEditor,
            template: "#=(ref_column)?ref_column:'None'#",
            filterable: false,
            width: 120
        }, {
            field: "language_id",
            title: "Language",
            editor: languageDropDownEditor,
            template: "#=data.language_code#",
            filterable: false,
            width: 140
        }, {
            command: "<button class='k-button' id='Crosswalk'>Crosswalk</button> | <button class='k-button' id='Lookup'>Lookup</button>",
            title: " ",
            filterable: false,
            width: "150px",
        }],
        save: function(data) {
            var name_id = field_id = type_id = pattern = pattern_format = language_id = null;
            var pattern_interval = 0;
            var language_id = 1;
            var setupTypeId = 2;
            var ref_class = ref_table = ref_column = '';
            if (data.values.name_id) {
                if (data.values.name_id.name_id) {
                    name_id = data.values.name_id.name_id;
                } else {
                    name_id = data.values.name_id;
                }
            } else {
                name_id = data.model.name_id;
            }
            if (data.values.type_name) {
                if (data.values.type_name.type_name) {
                    type_id = data.values.type_name.type_name;
                } else {
                    type_id = data.values.type_name;
                }
            } else {
                type_id = data.model.type_name;
            }
            if (data.values.pattern) {
                if (data.values.pattern.pattern) {
                    pattern = data.values.pattern.pattern;
                } else {
                    pattern = data.values.pattern;
                }
            } else {
                pattern = data.model.pattern;
            }
            if (data.values.pattern_format) {
                if (data.values.pattern_format.pattern_format) {
                    pattern_format = data.values.pattern_format.pattern_format;
                } else {
                    pattern_format = data.values.pattern_format;
                }
            } else {
                pattern_format = data.model.pattern_format;
            }
            if (data.values.language_id) {
                if (data.values.language_id.language_id) {
                    language_id = data.values.language_id.language_id;
                } else {
                    language_id = data.values.language_id;
                }
            } else {
                language_id = data.model.language_id;
            }
            if (data.values.pattern_interval) {
                if (data.values.pattern_interval.pattern_interval) {
                    pattern_interval = data.values.pattern_interval.pattern_interval;
                } else {
                    pattern_interval = data.values.pattern_interval;
                }
            } else {
                pattern_interval = data.model.pattern_interval;
            }
            if (data.values.field_name) {
                if (data.values.field_name.field_name) {
                    field_id = data.values.field_name.field_name;
                } else {
                    field_id = data.values.field_name;
                }
            } else {
                field_id = data.model.field_name;
            }
            if (data.values.ref_table) {
                if (data.values.ref_table.ref_table) {
                    ref_table = data.values.ref_table.ref_table;
                } else {
                    ref_table = data.values.ref_table;
                }
            } else {
                ref_table = data.model.ref_table;
            }
            if (data.values.ref_column) {
                if (data.values.ref_column.ref_column) {
                    ref_column = data.values.ref_column.ref_column;
                } else {
                    ref_column = data.values.ref_column;
                }
            } else {
                ref_column = data.model.ref_column;
            }
            if (data.values.ref_class) {
                if (data.values.ref_class.ref_class) {
                    ref_class = data.values.ref_class.ref_class;
                } else {
                    ref_class = data.values.ref_class;
                }
            } else {
                ref_class = data.model.ref_class;
            }
            jQuery.ajax({
                type: 'POST',
                data: {
                    pattern_id: data.model.pattern_id,
                    type_name: type_id,                    
                    pattern: pattern,
                    pattern_format: pattern_format,
                    language_id: language_id,
                    pattern_interval: pattern_interval,
                    field_name: field_id,
                    ref_table: ref_table,
                    name_id: name_id,
                    ref_class: ref_class,
                    ref_column: ref_column,
                    setup_type_id: setupTypeId,
                    _token: token
                },
                url: requestUrl + "/updateIncellEditRegexPattern",
                success: function(regexTypeDetails) {
                        response = regexTypeDetails.type;
                        if(response.localeCompare("success") == 0){
                            message = regexTypeDetails.message;
                        }else if (response.localeCompare("error") == 0){
                            message = regexTypeDetails.message;
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
                        data.sender.dataSource.read();
                    }
            });
        },
    });

    var regexPrimitiveGrid = jQuery("#regexPrimitiveGrid").kendoGrid({
        dataSource: {
            serverPaging: true,
            pageSize: 10,
            transport: {
                read: {
                    url: requestUrl + "/getRegexPrimitive",
                    dataType: "json",
                    type: "GET",
                }
            },
            schema: {
                total: 'total',
                data: 'regex_patterns',
                model: {
                    id: 'pattern_id',
                    fields: {                        
                        language_id: {
                            defaultValue: {
                                language_id: 1
                            }
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
        filterable: {
            extra: false
        },
        columnMenu: true,
        columnMenuInit: onColumnMenuInit,
        serverFiltering: true,
        resizable: true,
        editable: 'incell',
        dataBound: function() {
            var grid = regexPrimitiveGrid.data("kendoGrid");
            var gridData = grid.dataSource.view();
            for (var i = 0; i < gridData.length; i++) {
                var currentUid = gridData[i].uid;
                if (gridData[i].pattern_id == 0) {
                    var currenRow = grid.table.find("tr[data-uid='" + currentUid + "']");
                    var editButton = $(currenRow).find(".k-grid-X");
                    editButton.hide();
                    var verifyButton = $(currenRow).find(".verify");
                    verifyButton.hide();
                }
            }
            var data = this.dataSource.data();
                $.each(data, function(i, row) {
                var is_verified = row.get("is_verified");
                var pattern_id = row.get("pattern_id");
                if (!is_verified && pattern_id !=0) {
                    $('tr[data-uid="' + row.uid + '"] td:nth-child(14)').css("background-color", "red");        
                }else if (is_verified && pattern_id !=0) {
                    $('tr[data-uid="' + row.uid + '"] td:nth-child(14)').css("background-color", "green");
                }    
            });
        },
        columns: [{
            command: {
                text: "X",
                click: deleteRegexPrimitive
            },
            title: "&nbsp;",
            width: "90px"
        }, {
            field: "pattern_id",
            title: "Pattern Id",
            hidden: true,
        }, {
            field: "pattern",
            title: "Pattern",
            width: 140,
            filterable: false
        }, {
            field: "primitive_code",
            title: "Primitive Code",
            width: 80,
            filterable: false
        }, {
            field: "type_name",
            title: "Regex Type",
            editor: regexTypeComboBoxEditor,
            template: "#=(data.type_name)?data.type_name:'None'#",
            width: 140
        }, {
            field: "language_id",
            title: "Language",
            editor: languageDropDownEditor,
            template: "#=(data.language_code)?data.language_code:'None'#",
            filterable: false,
            width: 140
        }],
        save: function(data) {
            var type_id = pattern = language_id = null;
            var language_id = 1;
            var priority = 1;
            
            if (data.values.type_name) {
                if (data.values.type_name.type_name) {
                    type_id = data.values.type_name.type_name;
                } else {
                    type_id = data.values.type_name;
                }
            } else {
                type_id = data.model.type_name;
            }
            if (data.values.pattern) {
                if (data.values.pattern.pattern) {
                    pattern = data.values.pattern.pattern;
                } else {
                    pattern = data.values.pattern;
                }
            } else {
                pattern = data.model.pattern;
            }

            if (data.values.primitive_code) {
                if (data.values.primitive_code.primitive_code) {
                    primitive_code = data.values.primitive_code.primitive_code;
                } else {
                    primitive_code = data.values.primitive_code;
                }
            } else {
                primitive_code = data.model.primitive_code;
            }
            
            if (data.values.language_id) {
                if (data.values.language_id.language_id) {
                    language_id = data.values.language_id.language_id;
                } else {
                    language_id = data.values.language_id;
                }
            } else {
                language_id = data.model.language_id;
            }
            
            jQuery.ajax({
                type: 'POST',
                data: {
                    pattern_id: data.model.pattern_id,
                    type_name: type_id,                    
                    pattern: pattern,
                    primitive_code: primitive_code,
                    language_id: language_id,
                    _token: token
                },
                url: requestUrl + "/updateIncellEditRegexPrimitive",
                success: function(regexTypeDetails) {
                        response = regexTypeDetails.type;
                        if(response.localeCompare("success") == 0){
                            message = regexTypeDetails.message;
                        }else if (response.localeCompare("error") == 0){
                            message = regexTypeDetails.message;
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
                        data.sender.dataSource.read();
                    }
            });
        },
    });    

    function onColumnMenuInit(regexSetupGrid) {
        var menu = regexSetupGrid.container.find(".k-menu").data("kendoMenu");
        if (regexSetupGrid.field == 'name_id') {
            menu.insertBefore({
                text: "Manage Data",
            }, menu.element.children("li:first"));
            menu.bind("select", function(e) {
                var menuText = $(e.item).text();
                if (menuText === "Manage Data") {
                    var websiteCategoryListDataSource = new kendo.data.DataSource({
                        pageSize: 5,
                        transport: {
                            read: {
                                data: {
                                    _token: token
                                },
                                url: requestUrl + '/getCategoryList',
                                dataType: "json",
                                type: "POST"
                            },
                        },
                        schema: {
                            total: 'total',
                            data: 'regex_category',
                            model: {
                                id: 'name_id',
                            }
                        }
                    });
                    websiteCategoryGrid.setDataSource(websiteCategoryListDataSource);
                    $('#category_top_modal').modal("show");
                }
            });
        }
        if (regexSetupGrid.field == 'type_name') {
            menu.insertBefore({
                text: "Manage Data",
            }, menu.element.children("li:first"));
            menu.bind("select", function(e) {
                var menuText = $(e.item).text();
                if (menuText === "Manage Data") {
                    var categoryFilterValue = '';
                    var regexSetupGridFilter = $("#regexSetupGrid").data("kendoGrid");
                    if (regexSetupGridFilter.dataSource.filter()) {
                        var categoryFilterDetails = regexSetupGridFilter.dataSource.filter().filters[0];
                        var categoryFilterValue = categoryFilterDetails.value;
                    }
                    var regexTypeListDataSource = new kendo.data.DataSource({
                        pageSize: 5,
                        transport: {
                            read: {
                                data: {
                                    _token: token
                                },
                                url: requestUrl + '/getRegexTypeList?categoryFilterValue=' + categoryFilterValue,
                                dataType: "json",
                                type: "POST"
                            },
                            create: {
                                data: {
                                    _token: token
                                },
                                url: requestUrl + "/createRegexType",
                                dataType: "json",
                                type: "POST",
                                complete: function(data) {
                                    websiteRegexTypeGrid.dataSource.read();
                                    kendoGrid.dataSource.read();
                                }
                            },
                        },
                        schema: {
                            total: 'total',
                            data: 'regex_type',
                            model: {
                                id: 'type_id',
                                fields: {
                                    type_name: {
                                        validation: {
                                            required: true
                                        }
                                    },
                                    name: {
                                        defaultValue: {
                                            name: "Social Links"
                                        }
                                    },
                                }
                            }
                        }
                    });
                    websiteRegexTypeGrid.setDataSource(regexTypeListDataSource);
                    $('#regex_type_top_modal').modal("show");
                }
            });
        }
        if (regexSetupGrid.field == 'field_name') {
            menu.insertBefore({
                text: "Manage Data",
            }, menu.element.children("li:first"));
            menu.bind("select", function(e) {
                var menuText = $(e.item).text();
                if (menuText === "Manage Data") {
                    var regexTypeFilterValue = '';
                    var regexSetupGridFilter = $("#regexSetupGrid").data("kendoGrid");
                    if (regexSetupGridFilter.dataSource.filter()) {
                        if (regexSetupGridFilter.dataSource.filter().filters.length == 2) {
                            var regexTypeFilterDetails = regexSetupGridFilter.dataSource.filter().filters[1];
                        } else {
                            var regexTypeFilterDetails = regexSetupGridFilter.dataSource.filter().filters[0];
                        }
                        var regexTypeFilterValue = regexTypeFilterDetails.value;
                    }
                    var websiteRegexFieldListDataSource = new kendo.data.DataSource({
                        pageSize: 5,
                        transport: {
                            read: {
                                data: {
                                    _token: token
                                },
                                url: requestUrl + '/getRegexFieldList?regexTypeFilterValue=' + regexTypeFilterValue,
                                dataType: "json",
                                type: "POST"
                            },
                            create: {
                                data: {
                                    _token: token
                                },
                                url: requestUrl + "/createRegexField",
                                dataType: "json",
                                type: "POST",
                                complete: function(data) {
                                    websiteRegexFieldGrid.dataSource.read();
                                    kendoGrid.dataSource.read();
                                }
                            },
                        },
                        schema: {
                            total: 'total',
                            data: 'regex_field',
                            model: {
                                id: 'field_id',
                                fields: {
                                    field_name: {
                                        validation: {
                                            required: true
                                        }
                                    },
                                    type_name: {
                                        validation: {
                                            required: true
                                        }
                                    },
                                    type_id: {
                                        defaultValue: {
                                            type_id: 2
                                        }
                                    },
                                }
                            }
                        }
                    });
                    websiteRegexFieldGrid.setDataSource(websiteRegexFieldListDataSource);
                    $('#regex_field_top_modal').modal("show");
                }
            });
        }
        if (regexSetupGrid.field == 'ref_table') {
            menu.insertBefore({
                text: "Manage Data",
            }, menu.element.children("li:first"));
            menu.bind("select", function(e) {
                var menuText = $(e.item).text();
                if (menuText === "Manage Data") {
                    var regexTableAccessListDataSource = new kendo.data.DataSource({
                        pageSize: 5,
                        transport: {
                            read: {
                                url: requestUrl + "/getAccessTableList",
                                dataType: "json",
                                type: "GET"
                            },
                            create: {
                                data: {
                                    _token: token
                                },
                                url: requestUrl + "/createAccessTable",
                                dataType: "json",
                                type: "POST",
                                complete: function(data) {
                                    regexTableAccessListGrids.dataSource.read();
                                    kendoGrid.dataSource.read();
                                }
                            },
                        },
                        schema: {
                            total: 'total',
                            data: 'regex_table_accesses',
                            model: {
                                id: 'access_id',
                                fields: {
                                    table_name: {
                                        defaultValue: {
                                            table_name: "identity_social"
                                        }
                                    },
                                    group_name: {
                                        defaultValue: {
                                            group_name: "Portal Admin"
                                        }
                                    },
                                    column_name: {
                                        validation: {
                                            required: true
                                        }
                                    },
                                }
                            },
                        }
                    });
                    regexTableAccessListGrids.setDataSource(regexTableAccessListDataSource);
                    $('#reference_top_modal').modal("show");
                }
            });
        }
        var filterContainer = regexSetupGrid.container.find(".k-filter-menu");
        if (regexSetupGrid.field == 'name_id') {
            regexSetupGrid.container.find("div.k-filter-help-text").text("Select category from the list:");
            regexSetupGrid.container.find("span.k-dropdown:first").css("display", "none");
            var dropDownList = regexSetupGrid.container.find(".k-textbox:first").removeClass("k-textbox").kendoDropDownList({
                dataSource: categoryDataSource,
                dataTextField: "name",
                dataValueField: "name",
                optionLabel: {
                    name: "Select a Category",
                }
            }).data("kendoDropDownList");
        }
        if (regexSetupGrid.field == 'type_name') {
            var categoryFilterValue = '';
            var regexSetupGridFilter = $("#regexSetupGrid").data("kendoGrid");
            if (regexSetupGridFilter.dataSource.filter()) {
                var categoryFilterDetails = regexSetupGridFilter.dataSource.filter().filters[0];
                var categoryFilterValue = categoryFilterDetails.value;
            }
            var regexTypeDataSource = new kendo.data.DataSource({
                transport: {
                    read: {
                        dataType: "json",
                        url: requestUrl + "/getRegexTypes?categoryFilterValue=" + categoryFilterValue,
                        type: "GET"
                    }
                }
            });
            regexSetupGrid.container.find("div.k-filter-help-text").text("Select type from the list:");
            regexSetupGrid.container.find("span.k-dropdown:first").css("display", "none");
            var dropDownList = regexSetupGrid.container.find(".k-textbox:first").removeClass("k-textbox").kendoDropDownList({
                dataSource: regexTypeDataSource,
                dataTextField: "type_name",
                dataValueField: "type_name",
                optionLabel: {
                    type_name: "Select a Type",
                }
            }).data("kendoDropDownList");
        }
        if (regexSetupGrid.field == 'field_name') {
            var regexTypeFilterValue = '';
            var regexSetupGridFilter = $("#regexSetupGrid").data("kendoGrid");
            if (regexSetupGridFilter.dataSource.filter()) {
                if (regexSetupGridFilter.dataSource.filter().filters.length == 2) {
                    var regexTypeFilterDetails = regexSetupGridFilter.dataSource.filter().filters[1];
                } else {
                    var regexTypeFilterDetails = regexSetupGridFilter.dataSource.filter().filters[0];
                }
                var regexTypeFilterValue = regexTypeFilterDetails.value;
            }
            var regexFiledDataSource = new kendo.data.DataSource({
                transport: {
                    read: {
                        dataType: "json",
                        url: requestUrl + "/getRegexFields?regexTypeFilterValue=" + regexTypeFilterValue,
                        type: "GET"
                    }
                }
            });
            regexSetupGrid.container.find("div.k-filter-help-text").text("Select field from the list:");
            regexSetupGrid.container.find("span.k-dropdown:first").css("display", "none");
            var dropDownList = regexSetupGrid.container.find(".k-textbox:first").removeClass("k-textbox").kendoDropDownList({
                dataSource: regexFiledDataSource,
                dataTextField: "field_name",
                dataValueField: "field_name",
                optionLabel: {
                    field_name: "Select a Field",
                }
            }).data("kendoDropDownList");
        }
        if (regexSetupGrid.field == 'ref_table') {
            regexSetupGrid.container.find("div.k-filter-help-text").text("Select an item from the list:");
            regexSetupGrid.container.find("span.k-dropdown:first").css("display", "none");
            var dropDownList = regexSetupGrid.container.find(".k-textbox:first").removeClass("k-textbox").kendoDropDownList({
                dataSource: referenceTableDataSource,
                dataTextField: "table_name",
                dataValueField: "table_name",
                optionLabel: {
                    table_name: "Select a Table",
                }
            }).data("kendoDropDownList");
        }
    };

    function deleteRegexPattern(e) {
        e.preventDefault();
        var dataItem = this.dataItem($(e.currentTarget).closest("tr"));
        jQuery.ajax({
            type: 'GET',
            data: {
                pattern_id: dataItem.pattern_id,
            },
            url: requestUrl + "/deleteRegexPattern",
            success: function(regexDetails) {
                $("#regexSetupGrid").data("kendoGrid").dataSource.read();
                $("#regexExtractGrid").data("kendoGrid").dataSource.read();
            }
        });
    }

    function deleteRegexPrimitive(e) {
        e.preventDefault();
        var dataItem = this.dataItem($(e.currentTarget).closest("tr"));
        jQuery.ajax({
            type: 'GET',
            data: {
                pattern_id: dataItem.pattern_id,
            },
            url: requestUrl + "/deleteRegexPrimitive",
            success: function(regexDetails) {
                $("#regexPrimitiveGrid").data("kendoGrid").dataSource.read();
            }
        });
    } 
    $('#regexExtractGrid').on('click', '#Crosswalk', function() { 
        var regexExtractGridList=$("#regexExtractGrid").data("kendoGrid")
        var dataItem = regexExtractGridList.dataItem($(this).closest('tr'));
        var crosswalk_table = dataItem.ref_table;
        var ref_column = dataItem.ref_column;
        $('#crosswalk_details_top_modal').modal("show");
        var token = $('input[name="_token"]').val();
        var refDetailsRequestUrl = "crosswalk_list";
            function sourceIdEditor(container, options) {
                $("<input name='" + options.field + "'/>").appendTo(container).kendoNumericTextBox({
                    min: 0,
                    step: 1,
                    decimals: 0
                });
                var tooltipElement = $("<span class='k-invalid-msg' data-for='" + options.field + "'></span>");
                tooltipElement.appendTo(container);
            }

            function creatorIdEditor(container, options) {
                $("<input name='" + options.field + "' required type='text' data-text-field='username' data-value-field='user_id' data-bind='value:" + options.field + "' />").appendTo(container).kendoDropDownList({
                    autoBind: true,
                    dataTextField: "username",
                    dataValueField: "user_id",
                    dataSource: {
                        transport: {
                            read: {
                                dataType: 'json',
                                url: refDetailsRequestUrl + "/getCreatorIdList",
                                type: "GET"
                            }
                        }
                    }
                });
            }

            function updaterIdEditor(container, options) {
                $("<input name='" + options.field + "'/>").appendTo(container).kendoNumericTextBox({
                    min: 0,
                    step: 1,
                    decimals: 0
                });
                var tooltipElement = $("<span class='k-invalid-msg' data-for='" + options.field + "'></span>");
                tooltipElement.appendTo(container);
            }

            function lookupIdEditor(container, options) {
                $("<input name='" + options.field + "'/>").appendTo(container).kendoNumericTextBox({
                    min: 0,
                    step: 1,
                    decimals: 0
                });
                var tooltipElement = $("<span class='k-invalid-msg' data-for='" + options.field + "'></span>");
                tooltipElement.appendTo(container);
}
        var crosswalkPositionGrid = jQuery("#crosswalkGrid").kendoGrid({
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
                                _token: token,
                                crosswalk_table: crosswalk_table
                            },
                            url: refDetailsRequestUrl + "/getCrosswalkDetails",
                            dataType: "json",
                            type: "POST"
                        },
                        update: {
                            data: {
                                _token: token,
                                crosswalk_table: crosswalk_table
                            },
                            url: refDetailsRequestUrl + "/updateCrosswalkDetails",
                            dataType: "jsonp",
                            type: "POST"
                        },
                        destroy: {
                            data: {
                                _token: token,
                                crosswalk_table: crosswalk_table
                            },
                            url: refDetailsRequestUrl + "/deleteCrosswalkDetails",
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
                            response = requestResponse.response.action;
                                if(response.localeCompare("success") == 0){
                                    message = requestResponse.response.message;
                                } else if (response.localeCompare("error") == 0){
                                    message = requestResponse.response.message;
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
                            $('#crosswalk_details_top_modal').modal("hide");
                            var crosswalkGrid = $("#crosswalkGrid").data("kendoGrid");
                            crosswalkGrid.dataSource.read();
                        }
                    },
                    schema: {
                        total: 'total',
                        data: 'crosswalkDetails',
                        model: {
                            id: 'id',
                            fields: {
                                source_id: {
                                    type: "number"
                                },
                                creator_timestamp: {
                                    editable: false
                                },
                                updater_id: {
                                    required: false
                                },
                                updater_timestamp: {
                                    editable: false
                                },
                                name: {
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
                                lookup_id: {
                                    type: "number"
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
                    template: kendo.template($("#crosswalkHeaderAction").html())
                }],
                scrollable: true,
                sortable: false,
                reorderable: true,
                groupable: true,
                resizable: true,
                editable: true,
                columnMenu: true,
                columns: [{
                    field: "source_id",
                    title: "Source Id",
                    width: "100px",
                    editor: sourceIdEditor
                }, {
                    field: "creator_id",
                    title: "Creator Id",
                    template: "#:username#",
                    width: "100px",
                    hidden:true,
                    editor: creatorIdEditor
                }, {
                    field: "creator_timestamp",
                    title: "Creator Time",
                    hidden:true,
                    width: "140px"
                }, {
                    field: "updater_id",
                    title: "Updater Id",
                    width: "100px",
                    template: "#:username#",
                    editor: creatorIdEditor
                }, {
                    field: "updater_timestamp",
                    title: "Updater Time",
                    width: "140px",
                }, {
                    field: "name",
                    width: "100px",
                    title: "Name"
                }, {
                    field: "lookup_id",
                    width: "100px",
                    title: "Lookup Id",
                    editor: lookupIdEditor
                }, ],
            });
            /* event Search */
            $("#crosswalkBtnSearch").click(function() {
                var eventSearchValue = $('#crosswalkSearchBox').val();
                $("#crosswalkGrid").data("kendoGrid").dataSource.filter({
                    logic: "or",
                    filters: [{
                        field: "creator_id",
                        operator: "contains",
                        value: eventSearchValue
                    }, {
                        field: "name",
                        operator: "contains",
                        value: eventSearchValue
                    }, ]
                });
            });
            $('#crosswalkSearchBox').keypress(function(e) {
                if (e.which == 13) { //Enter key pressed
                    $('#crosswalkBtnSearch').click(); //Trigger search button click event
                }
            });
            //Clearing the filter
            $("#crosswalkBtnReset").click(function() {
                $('#crosswalkSearchBox').val("");
                $("#crosswalkGrid").data("kendoGrid").dataSource.filter({});
            });
            /* calendar Search end*/
    });  

    $('#regexExtractGrid').on('click', '#Lookup', function() {
        var regexExtractGridList=$("#regexExtractGrid").data("kendoGrid")
        var dataItem = regexExtractGridList.dataItem($(this).closest('tr'));
        var ref_table = dataItem.ref_table;
        var ref_column = dataItem.ref_column;
        $('#ref_details_top_modal').modal("show");
        var token = $('input[name="_token"]').val();
        var refDetailsRequestUrl = "regex_list";
        var checkedIds = {};
        function sourceIdEditor(container, options) {
                $("<input name='" + options.field + "'/>").appendTo(container).kendoNumericTextBox({
                    min: 0,
                    step: 1,
                    decimals: 0
                });
                var tooltipElement = $("<span class='k-invalid-msg' data-for='" + options.field + "'></span>");
                tooltipElement.appendTo(container);
            }

            function creatorIdEditor(container, options) {
                $("<input name='" + options.field + "' required type='text' data-text-field='username' data-value-field='user_id' data-bind='value:" + options.field + "' />").appendTo(container).kendoDropDownList({
                    autoBind: true,
                    dataTextField: "username",
                    dataValueField: "user_id",
                    dataSource: {
                        transport: {
                            read: {
                                dataType: 'json',
                                url:   "crosswalk_list/getCreatorIdList",
                                type: "GET"
                            }
                        }
                    }
                });
            }

            function updaterIdEditor(container, options) {
                $("<input name='" + options.field + "'/>").appendTo(container).kendoNumericTextBox({
                    min: 0,
                    step: 1,
                    decimals: 0
                });
                var tooltipElement = $("<span class='k-invalid-msg' data-for='" + options.field + "'></span>");
                tooltipElement.appendTo(container);
            }
        function parentIdEditor(container, options) {
            $("<input name='" + options.field + "' required type='text' data-text-field='name' data-value-field='id' data-bind='value:" + options.field + "' />").appendTo(container).kendoDropDownList({
                autoBind: true,
                dataTextField: "name",
                dataValueField: "id",
                dataSource: {
                    transport: {
                        read: {
                            dataType: 'json',
                            url: refDetailsRequestUrl + "/getParentIdList",
                            type: "GET",
                            data: {ref_table:ref_table}
                        }
                    }
                }
            });
        }

        function rootIdEditor(container, options) {
           $("<input name='" + options.field + "' required type='text' data-text-field='rootName' data-value-field='root_id' data-bind='value:" + options.field + "' />").appendTo(container).kendoDropDownList({
                autoBind: true,
                dataTextField: "rootName",
                dataValueField: "root_id",
                dataSource: {
                    transport: {
                        read: {
                            dataType: 'json',
                            url: refDetailsRequestUrl + "/getRootIdList",
                            type: "GET",
                            data: {ref_table:ref_table}
                        }
                    }
                }
            });
        }

        function nameEditor(container, options) {
            $("<textarea data-bind='value:" + options.field + "' ></textarea>").appendTo(container);
        }

        function priorityEditor(container, options) {
            $("<input name='" + options.field + "'/>").appendTo(container).kendoNumericTextBox({
                min: 0,
                step: 1,
                decimals: 0
            });
            var tooltipElement = $("<span class='k-invalid-msg' data-for='" + options.field + "'></span>");
            tooltipElement.appendTo(container);
        }

        function imageEditor(container, options) {
            $("<textarea data-bind='value:" + options.field + "' ></textarea>").appendTo(container);
        }
        $(document).ready(function() {
            $(function() {
                var grid = $("#regexListGrid").data("kendoGrid");
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
            var regexListGrid = new kendo.data.DataSource({
                serverPaging: true,
                serverFiltering: true,
                serverSorting: false,
                pageSize: 50,
                autoSync: true,
                group: [],
                transport: {
                    read: {
                        data: {
                            _token: token,
                            ref_table: ref_table
                        },
                        url: refDetailsRequestUrl + "/getRegexList",
                        dataType: "json",
                        type: "POST"
                    },
                    update: {
                        data: {
                            _token: token,
                            ref_table: ref_table
                        },
                        url: refDetailsRequestUrl + "/updateRegexList",
                        dataType: "jsonp",
                        type: "POST"
                    },
                    destroy: {
                        data: {
                            _token: token
                        },
                        url: refDetailsRequestUrl + "/deleteRegexList",
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
                        response = requestResponse.response.action;
                        if(response.localeCompare("success") == 0){
                            message = requestResponse.response.message;
                        }else if (response.localeCompare("error") == 0){
                            message = requestResponse.response.message;
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
                    $('#ref_details_top_modal').modal("hide");
                    var regexListGrid = $("#regexListGrid").data("kendoGrid");
                    regexListGrid.dataSource.read();
                    }
                },
                schema: {
                    total: 'total',
                    data: 'regexList',
                    model: {
                        id: 'id',
                        fields: {
                            id: {
                                editable: false
                            },
                            root_id: {
                                type: "number"
                            },
                            updater_id: {
                                required: false
                            },
                            priority: {
                                type: "number"
                            },
                            updater_timestamp: {
                                editable: false
                            },
                        },
                    },
                },
            });
            jQuery("#regexListGrid").replaceWith("<div id='regexListGrid'></div>");
            console.log(ref_table);
            jQuery("#regexListGrid").kendoGrid({
                dataSource: regexListGrid,
                pageable: {
                    refresh: true,
                    pageSizes: [50, 100, 200]
                },
                noRecords: true,
                messages: {
                    noRecords: "No event Found"
                },
                toolbar: [{
                    template: kendo.template($("#regexListHeaderAction").html())
                }],
                scrollable: true,
                sortable: false,
                reorderable: true,
                groupable: true,
                resizable: true,
                editable: true,
                columnMenu: true,
                columns: [{
                    field: "id",
                    title: "Id",
                    width: "50px",
                    template: "#if(id == 0){# #} else {# #=id# #}#",
                }, {
                    field: "parent_id",
                    title: "Parent",
                    template: "#:name#",
                    width: "100px",
                    editor: parentIdEditor
                }, {
                    field: "root_id",
                    title: "Root",
                    width: "100px",
                    template: "#:rootName#",
                    editor: rootIdEditor
                }, {
                    field: "listName",
                    title: "Name",
                    width: "200px",
                    template: "#=(data.listName)?data.listName:'None'#",
                }, {
                    field: "source_id",
                    title: "Source Id",
                    width: "100px",
                    editor: sourceIdEditor
                }, {
                    field: "creator_id",
                    title: "Creator Id",
                    template: "#=(data.username)?data.username:'None'#",
                    width: "100px",
                    hidden:true,
                    editor: creatorIdEditor
                }, {
                    field: "creator_timestamp",
                    title: "Creator Time",
                    hidden:true,
                    width: "140px"
                }, {
                    field: "updater_id",
                    title: "Updater",
                    width: "100px",
                    template: "#=(data.username)?data.username:''#",
                    editor: creatorIdEditor
                }, {
                    field: "updater_timestamp",
                    title: "Updater Time",
                    template: "#=(data.updater_timestamp)?data.updater_timestamp:''#",
                    width: "140px",
                }, {
                    field: "priority",
                    title: "Priority",
                    width: "60px",
                    editor: priorityEditor
                }, {
                    field: "image",
                    title: "Image",
                    template: "#if(image && image.length>100){# # var myContent =image; #  # var dcontent = myContent.substring(0,100); # <span title='${image}'>#=kendo.toString(dcontent)#...</span> #}#",
                    width: "60px",
                    editor: imageEditor
                }, ],
            });
            /* event Search */
            $("#regexListBtnSearch").click(function() {
                var eventSearchValue = $('#regexListSearchBox').val();
                $("#regexListGrid").data("kendoGrid").dataSource.filter({
                    logic: "or",
                    filters: [{
                        field: "parent_id",
                        operator: "contains",
                        value: eventSearchValue
                    }, {
                        field: "listName",
                        operator: "contains",
                        value: eventSearchValue
                    }, {
                        field: "image",
                        operator: "contains",
                        value: eventSearchValue
                    }, ]
                });
            });
            $('#regexListSearchBox').keypress(function(e) {
                if (e.which == 13) { //Enter key pressed
                    $('#regexListBtnSearch').click(); //Trigger search button click event
                }
            });
            //Clearing the filter
            $("#regexListBtnReset").click(function() {
                $('#regexListSearchBox').val("");
                $("#regexListGrid").data("kendoGrid").dataSource.filter({});
            });
            /* calendar Search end*/
        });
     });    
    var kendoGrid = $("#regexSetupGrid").data("kendoGrid");

    function regexTypeComboBoxEditor(container, options) {
        if (options.model.name_id) {
            var name_id = options.model.name_id;
        }
        var regexTypeDetailsData = new kendo.data.DataSource({
            transport: {
                read: {
                    dataType: "json",
                    url: requestUrl + "/getRegexTypes?name_id=" + name_id,
                    type: "GET"
                }
            }
        });
        var regexTypeComboBox = $('<input id = "regexTypeComboBoxId" data-text-field="type_name" data-value-field="type_name" data-bind="value:' + options.field + '"/>').appendTo(container).kendoComboBox({
            filter: "contains",
            dataSource: regexTypeDetailsData,
            placeholder: "Select Type",
            dataTextField: "type_name",
            dataValueField: "type_name",
        });
    }

    function regexFieldComboBoxEditor(container, options) {
        if (options.model.type_name) {
            var type_name = options.model.type_name;
        }
        var regexFieldDetailsData = new kendo.data.DataSource({
            transport: {
                read: {
                    dataType: "json",
                    url: requestUrl + "/getRegexFields?type_name=" + type_name,
                    type: "GET"
                }
            }
        });
        $('<input id = "regexFieldComboBoxId" data-text-field="field_name" data-value-field="field_name" data-bind="value:' + options.field + '"/>').appendTo(container).kendoComboBox({
            filter: "contains",
            dataSource: regexFieldDetailsData,
            placeholder: "Select Field",
            dataTextField: "field_name",
            dataValueField: "field_name"
        });
    }

    function regexLabelComboBoxEditor(container, options) {
        $('<input data-text-field="name" data-value-field="name_id" data-bind="value:' + options.field + '"/>').appendTo(container).kendoComboBox({
            filter: "contains",
            dataSource: {
                transport: {
                    read: {
                        dataType: "json",
                        url: requestUrl + "/getRegexCategories",
                        type: "GET"
                    }
                }
            },
            placeholder: "Select Category",
            dataTextField: "name",
            dataValueField: "name_id",
        });
    }

    function languageDropDownEditor(container, options) {
        $('<input data-text-field="language_code" data-value-field="language_id" data-bind="value:' + options.field + '"/>').appendTo(container).kendoDropDownList({
            dataSource: {
                transport: {
                    read: {
                        dataType: "json",
                        url: requestUrl + "/getLanguages",
                        type: "GET"
                    }
                }
            },
            dataTextField: "language_code",
            dataValueField: "language_id",
            optionLabel: {
                    language_code: "Select Language",
            }
        });
    }

    function regexCategoryDropDownEditor(container, options) {
        $('<input data-text-field="name" data-value-field="name" data-bind="value:' + options.field + '"/>').appendTo(container).kendoDropDownList({
            dataSource: {
                transport: {
                    read: {
                        dataType: "json",
                        url: requestUrl + "/getRegexCategories",
                        type: "GET"
                    }
                }
            },
            dataTextField: "name",
            dataValueField: "name"
        });
    }

    function regexTypeDropDownEditor(container, options) {
        $('<input data-text-field="type_name" data-value-field="type_id" data-bind="value:' + options.field + '"/>').appendTo(container).kendoDropDownList({
            dataSource: {
                transport: {
                    read: {
                        dataType: "json",
                        url: requestUrl + "/getRegexTypes",
                        type: "GET"
                    }
                }
            },
            dataTextField: "type_name",
            dataValueField: "type_id"
        });
    }

    function regexReferenceTableDropDownEditor(container, options) {
        $('<input data-text-field="table_name" data-value-field="table_name" data-bind="value:' + options.field + '"/>').appendTo(container).kendoDropDownList({
            dataSource: {
                transport: {
                    read: {
                        dataType: "json",
                        url: requestUrl + "/getRegexUserReferenceTable",
                        type: "GET"
                    }
                }
            },
            dataTextField: "table_name",
            dataValueField: "table_name",
            optionLabel: {
                    table_name: "Select Ref Table",
            }
        });
    }

    function regexTableDropDownEditor(container, options) {
        $('<input data-text-field="table_code" data-value-field="table_code" data-bind="value:' + options.field + '"/>').appendTo(container).kendoDropDownList({
            dataSource: {
                transport: {
                    read: {
                        dataType: "json",
                        url: requestUrl + "/getReferenceTable",
                        type: "GET"
                    }
                }
            },
            dataTextField: "table_code",
            dataValueField: "table_code"
        });
    }
    function regexTableGroupDropDownEditor(container, options) {
        $('<input data-text-field="group_name" data-value-field="group_name" data-bind="value:' + options.field + '"/>').appendTo(container).kendoDropDownList({
            dataSource: {
                transport: {
                    read: {
                        dataType: "json",
                        url: requestUrl + "/getReferenceTableGroupList",
                        type: "GET"
                    }
                }
            },
            dataTextField: "group_name",
            dataValueField: "group_name"
        });
    }

    function regexColumnDropDownEditor(container, options) {
        $('<input data-text-field="column_name" data-value-field="column_name" data-bind="value:' + options.field + '"/>').appendTo(container).kendoDropDownList({
            dataSource: {
                transport: {
                    read: {
                        dataType: "json",
                        url: requestUrl + "/getRegexReferenceColumn",
                        type: "GET"
                    }
                }
            },
            dataTextField: "column_name",
            dataValueField: "column_name",
            optionLabel: {
                column_name: "Select Column",
            }
        });
    }
    $('#regexSetupGrid').on('click', '.verify', function(e) {
        e.preventDefault();
        $('#testForm .k-error').text("");
        $('.resultGrid tbody').html("");
        $('#test_url').val("");
        $('.resultBlock').hide();
        var dataItem = kendoGrid.dataItem($(this).closest('tr'));
        console.log(dataItem);
        $('#regex_pattern').val(dataItem.pattern);
        $('button.action').attr('data-pattern_id', dataItem.pattern_id);
        $('#top_modal').modal("show");
    });

    var validator = $("#regex_setup").kendoValidator({
        validateOnBlur: false,
        rules: {
            hasRegexType: function(input) {
                if (input.is("[id=type_id]")) {
                    var ms = input.data("kendoComboBox");
                    if (ms.value().length === 0) {
                        return false;
                    }
                }
                return true;
            },
            hasRegexField: function(input) {
                if (input.is("[id=field_id]")) {
                    var ms = input.data("kendoComboBox");
                    if (ms.value().length === 0) {
                        return false;
                    }
                }
                return true;
            },
            hasRegexList: function(input) {
                if (input.is("[id=name_id]")) {
                    var ms = input.data("kendoComboBox");
                    if (ms.value().length === 0) {
                        return false;
                    }
                }
                return true;
            }
        },
        messages: {
            hasRegexType: "Regex Type Required",
            hasRegexField: "Regex Field Required",
            hasRegexList: "Regex List Name Required"
        }
    }).data("kendoValidator");
    $('#submitBtn').click(function() {
        var idsToSend = [];
        var ds = kendoGrid.dataSource.view();
        for (var i = 0; i < ds.length; i++) {
            $row = kendoGrid.table.find("tr[data-uid='" + ds[i].uid + "']");
            var checkbox = $row.find(".k-checkbox");
            console.log(checkbox);
            if (checkbox.is(":checked")) {
                idsToSend.push(ds[i].pattern_id);
            }
        }
        if (validator.validate()) {
            if (!idsToSend.length) {
                $('span.k-error').text("Please select at least one pattern.!");
                return false;
            }
            formData = $('#regex_setup').serializeArray();
            formData.push({
                name: 'pattern_id',
                value: idsToSend
            });
            $.ajax({
                type: 'POST',
                data: formData,
                url: $('#regex_setup').attr('action'),
                success: function(eventData) {
                    $('span.k-error').text("");
                    kendoRegexType.value("");
                    kendoRegexField.value("");
                    kendoRegexCatgory.value("");
                    kendoGrid.dataSource.read();
                }
            });
        }
    });
    $('#testForm').bootstrapValidator({
        fields: {
            test_url: {
                validators: {
                    regexp: {
                        regexp: /^(?:(?:https?|ftp):\/\/)?(?:(?!(?:10|127)(?:\.\d{1,3}){3})(?!(?:169\.254|192\.168)(?:\.\d{1,3}){2})(?!172\.(?:1[6-9]|2\d|3[0-1])(?:\.\d{1,3}){2})(?:[1-9]\d?|1\d\d|2[01]\d|22[0-3])(?:\.(?:1?\d{1,2}|2[0-4]\d|25[0-5])){2}(?:\.(?:[1-9]\d?|1\d\d|2[0-4]\d|25[0-4]))|(?:(?:[a-z\u00a1-\uffff0-9]-*)*[a-z\u00a1-\uffff0-9]+)(?:\.(?:[a-z\u00a1-\uffff0-9]-*)*[a-z\u00a1-\uffff0-9]+)*(?:\.(?:[a-z\u00a1-\uffff]{2,})))(?::\d{2,5})?(?:\/\S*)?$/,
                        message: 'The input is not a valid URL'
                    }
                }
            }
        }
    });
    $('#testSubmit').on('click', function() {
        if ($('#test_url').val() == "") {
            $('#testForm .k-error').text("Test Url must required!");
            return false;
        }
        $('#testForm .k-error').text("");
        $('.content .preloader').show();
        $('.content img').show();
        $.ajax({
            dataType: 'json',
            type: 'POST',
            data: $('#testForm').serialize(),
            url: requestUrl + "/getRegexDetail",
            success: function(json) {
                $('.content .preloader,.content img').hide();
                $('.resultGrid tbody').html("");
                if (json.length) {
                    $.each(json, function(k, v) {
                        ++k;
                        $htmlBody = "<tr>";
                        $htmlBody += "<td>" + k + "</td>";
                        $htmlBody += "<td>" + v + "</td>";
                        $htmlBody += "</tr>";
                        $('.resultGrid tbody').append($htmlBody);
                    });
                    $('#testForm .k-error').text("");
                    $('.resultBlock').show();
                } else {
                    $('#testForm .k-error').text("We can not found any result..Please try again.!");
                }
            }
        });
        return false;
    });
    $('button.action').click(function() {
        var patternId = $(this).attr('data-pattern_id');
        $('.content .preloader,.content img').show();
        $.ajax({
            dataType: 'json',
            type: 'POST',
            data: {
                pattern_id: patternId,
                type: $(this).val(),
                _token: token
            },
            url: requestUrl + "/updateVerifyStatus",
            success: function(json) {
                $('.content .preloader,.content img').hide();
                $('.resultGrid tbody').html("");
                $('.resultBlock').hide();
                kendoGrid.dataSource.read();
            }
        });
        if ($(this).val() == "accept") {
            $('#top_modal').modal("hide");
        }
    });
    /* regex category window code start */

    var websiteCategoryListGrid = jQuery("#websiteCategoryListGrid").kendoGrid({
        pageable: {
            refresh: true,
            pageSizes: true
        },
        scrollable: true,
        autoSync: true,
        sortable: true,
        reorderable: true,
        serverFiltering: false,
        resizable: true,
        editable: 'incell',
        toolbar: [{
            template: kendo.template($("#regexCategorySearch").html())
        }],
        dataBound: function() {
            var grid = $("#websiteCategoryListGrid").data("kendoGrid");
            var gridData = grid.dataSource.view();
            for (var i = 0; i < gridData.length; i++) {
                var currentUid = gridData[i].uid;
                if (gridData[i].name_id == 0) {
                    var currenRow = grid.table.find("tr[data-uid='" + currentUid + "']");
                    var editButton = $(currenRow).find(".k-grid-X");
                    editButton.hide();
                }
            }
        },
        columns: [{
            command: [{
                text: "X",
                click: deleteRegexCategory
            }],
            title: "&nbsp;",
            width: "35px"
        }, {
            field: "name_id",
            title: "Category#",
            hidden: true,
        }, {
            field: "category_name",
            title: "Category Name",
            template: "#=(data.category_name)?data.category_name:'None'#",
            width: 180
        }],
        save: function(categoryData) {
            var category_name = categoryData.values.category_name;
            var name_id = categoryData.model.name_id;
            jQuery.ajax({
                type: 'POST',
                data: {
                    _token: token,
                    category_name: categoryData.values.category_name,
                    name_id: categoryData.model.name_id,
                },
                url: requestUrl + "/updateCategory",
                success: function(categoryData) {
                    websiteCategoryGrid.dataSource.read();
                    kendoGrid.dataSource.read();
                }
            });
        }
    });

    function deleteRegexCategory(categoryData) {
        categoryData.preventDefault();
        var dataItem = this.dataItem($(categoryData.currentTarget).closest("tr"));
        jQuery.ajax({
            type: 'POST',
            data: {
                _token: token,
                name_id: dataItem.name_id,
            },
            url: requestUrl + "/deleteCategory",
            success: function(categoryData) {
                websiteCategoryGrid.dataSource.read();
            }
        });
    }
    var websiteCategoryGrid = $("#websiteCategoryListGrid").data("kendoGrid");
    var regexCategoryLabel = $("#name_id").data("kendoComboBox");
    $("#RegexCategoryBtnSearch").click(function() {
        var searchValue = $('#RegexCategorySearchBox').val();
        $("#websiteCategoryListGrid").data("kendoGrid").dataSource.filter({
            logic: "or",
            filters: [{
                field: "category_name",
                operator: "contains",
                value: searchValue
            }]
        });
    });
    $('#RegexCategorySearchBox').keypress(function(e) {
        if (e.which == 13) { //Enter key pressed
            $('#websiteCategoryListGrid').click(); //Trigger search button click event
        }
    });
    //Clearing the filter
    $("#RegexCategoryBtnReset").click(function() {
        $('#RegexCategorySearchBox').val("");
        $("#websiteCategoryListGrid").data("kendoGrid").dataSource.filter({});
    });
    /* regex category window code end */
    /* regex field window code start */
    var websiteRegexFieldListGrid = jQuery("#websiteRegexFieldListGrid").kendoGrid({
        pageable: {
            refresh: true,
            pageSizes: true
        },
        toolbar: [{
            template: kendo.template($("#regexFieldSearch").html())
        }],
        scrollable: true,
        autoSync: true,
        sortable: true,
        reorderable: true,
        serverFiltering: false,
        resizable: true,
        editable: 'incell',
        columns: [{
            command: [{
                text: "X",
                click: deleteRegexField
            }],
            title: "&nbsp;",
            width: "35px"
        }, {
            field: "field_id",
            title: "Field#",
            hidden: true,
        }, {
            field: "field_name",
            title: "Field Name",
            width: 100
        }, {
            field: "type_id",
            title: "Regex Type",
            editor: regexTypeDropDownEditor,
            template: "#=type_name#",
            width: 61
        }],
        save: function(regexData) {
            if (regexData.values.field_name) {
                if (regexData.values.field_name.field_name) {
                    field_name = regexData.values.field_name.field_name;
                } else {
                    field_name = regexData.values.field_name;
                }
            } else {
                field_name = regexData.model.field_name;
            }
            if (regexData.values.type_id) {
                if (regexData.values.type_id.type_id) {
                    type_id = regexData.values.type_id.type_id;
                } else {
                    type_id = regexData.values.type_id;
                }
            } else {
                type_id = regexData.model.type_id;
            }
            jQuery.ajax({
                type: 'POST',
                data: {
                    _token: token,
                    field_name: field_name,
                    field_id: regexData.model.field_id,
                    type_id: type_id,
                },
                url: requestUrl + "/updateRegexField",
                success: function(RegexData) {
                    response = RegexData.type;
                    if (response.localeCompare("success") == 0) {
                        message = RegexData.message;
                    } else if (response.localeCompare("error") == 0) {
                        message = RegexData.message;
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
                    websiteRegexFieldGrid.dataSource.read();
                    kendoGrid.dataSource.read();
                }
            });
        }
    });
    function deleteRegexField(RegexFieldData) {
        RegexFieldData.preventDefault();
        var dataItem = this.dataItem($(RegexFieldData.currentTarget).closest("tr"));
        jQuery.ajax({
            type: 'POST',
            data: {
                _token: token,
                field_id: dataItem.field_id,
            },
            url: requestUrl + "/deleteRegexField",
            success: function(regexDetails) {
                websiteRegexFieldGrid.dataSource.read();
                kendoGrid.dataSource.read();
            }
        });
    }
    var websiteRegexFieldGrid = $("#websiteRegexFieldListGrid").data("kendoGrid");
    var regexFieldLabel = $("#field_id").data("kendoComboBox");
    $("#RegexFieldBtnSearch").click(function() {
        var searchValue = $('#RegexFieldSearchBox').val();
        $("#websiteRegexFieldListGrid").data("kendoGrid").dataSource.filter({
            logic: "or",
            filters: [{
                field: "field_name",
                operator: "contains",
                value: searchValue
            }]
        });
    });
    $('#RegexFieldSearchBox').keypress(function(e) {
        if (e.which == 13) { //Enter key pressed
            $('#RegexFieldBtnSearch').click(); //Trigger search button click event
        }
    });
    //Clearing the filter
    $("#RegexFieldBtnReset").click(function() {
        $('#RegexFieldSearchBox').val("");
        $("#websiteRegexFieldListGrid").data("kendoGrid").dataSource.filter({});
    });
    /* regex field window code end */
    var refDetailsListGrid = jQuery("#refDetailsListGrid").kendoGrid({
        pageable: {
            refresh: true,
            pageSizes: true
        },
        scrollable: true,
        autoSync: true,
        sortable: true,
        reorderable: true,
        serverFiltering: false,
        resizable: true,
        editable: 'incell',
        columns: []
    });
    /* regex type window code start */
    var websiteRegexTypeListGrid = jQuery("#websiteRegexTypeListGrid").kendoGrid({
        pageable: {
            refresh: true,
            pageSizes: true
        },
        scrollable: true,
        autoSync: true,
        sortable: true,
        reorderable: true,
        serverFiltering: false,
        resizable: true,
        editable: 'incell',
        toolbar: [{
            template: kendo.template($("#regexTypeSearch").html())
        }],
        dataBound: function() {
            var grid = $("#websiteRegexTypeListGrid").data("kendoGrid");
            var gridData = grid.dataSource.view();
            for (var i = 0; i < gridData.length; i++) {
                var currentUid = gridData[i].uid;
                if (gridData[i].type_id == 0) {
                    var currenRow = grid.table.find("tr[data-uid='" + currentUid + "']");
                    var editButton = $(currenRow).find(".k-grid-X");
                    editButton.hide();
                }
            }
        },
        columns: [{
            command: [{
                text: "X",
                click: deleteRegexType
            }],
            title: "&nbsp;",
            width: "35px"
        }, {
            field: "type_id",
            title: "Regex Type#",
            hidden: true,
        }, {
            field: "type_name",
            title: "Regex Type",
            template: "#=(data.type_name)?data.type_name:'None'#",
            width: 180
        }, {
            field: "name",
            title: "Name",
            editor: regexCategoryDropDownEditor,
            template: "#=name#",
            width: 61
        }],
        save: function(regexData) {
            if (regexData.values.type_name) {
                if (regexData.values.type_name.type_name) {
                    type_name = regexData.values.type_name.type_name;
                } else {
                    type_name = regexData.values.type_name;
                }
            } else {
                type_name = regexData.model.type_name;
            }
            if (regexData.values.name) {
                if (regexData.values.name.name) {
                    name = regexData.values.name.name;
                } else {
                    name = regexData.values.name;
                }
            } else {
                name = regexData.model.name;
            }
            jQuery.ajax({
                type: 'POST',
                data: {
                    _token: token,
                    type_name: type_name,
                    type_id: regexData.model.type_id,
                    name: name,
                },
                url: requestUrl + "/updateRegexType",
                success: function(RegexData) {
                    websiteRegexTypeGrid.dataSource.read();
                    kendoGrid.dataSource.read();
                }
            });
        }
    });

    function deleteRegexType(RegexData) {
        RegexData.preventDefault();
        var dataItem = this.dataItem($(RegexData.currentTarget).closest("tr"));
        jQuery.ajax({
            type: 'POST',
            data: {
                _token: token,
                type_id: dataItem.type_id,
            },
            url: requestUrl + "/deleteRegexType",
            success: function(categoryData) {
                websiteRegexTypeGrid.dataSource.read();
                kendoGrid.dataSource.read();
            }
        });
    }
    var refDetailsGrid = $("#refDetailsListGrid").data("kendoGrid");
    var websiteRegexTypeGrid = $("#websiteRegexTypeListGrid").data("kendoGrid");
    var regexTypeLabel = $("#type_id").data("kendoComboBox");
    $("#RegexTypeBtnSearch").click(function() {
        var searchValue = $('#RegexTypeSearchBox').val();
        $("#websiteRegexTypeListGrid").data("kendoGrid").dataSource.filter({
            logic: "or",
            filters: [{
                field: "type_name",
                operator: "contains",
                value: searchValue
            }]
        });
    });
    $('#RegexTypeSearchBox').keypress(function(e) {
        if (e.which == 13) { //Enter key pressed
            $('#RegexTypeBtnSearch').click(); //Trigger search button click event
        }
    });
    //Clearing the filter
    $("#RegexTypeBtnReset").click(function() {
        $('#RegexTypeSearchBox').val("");
        $("#websiteRegexTypeListGrid").data("kendoGrid").dataSource.filter({});
    });
    /* regex type window code end */
    var regexTableAccessListGrids = jQuery("#regexTableAccessListGrid").kendoGrid({
        pageable: {
            refresh: true,
            pageSizes: true
        },
        scrollable: true,
        autoSync: true,
        sortable: true,
        reorderable: true,
        resizable: true,
        editable: 'incell',
        toolbar: [{
            template: kendo.template($("#regexRefTableSearch").html())
        }],
        dataBound: function() {
            var grid = $("#regexTableAccessListGrid").data("kendoGrid");
            var gridData = grid.dataSource.view();
            for (var i = 0; i < gridData.length; i++) {
                var currentUid = gridData[i].uid;
                if (gridData[i].access_id == 0) {
                    var currenRow = grid.table.find("tr[data-uid='" + currentUid + "']");
                    var editButton = $(currenRow).find(".k-grid-X");
                    editButton.hide();
                }
            }
        },
        columns: [{
            command: {
                text: "X",
                click: deleteAccessTable
            },
            title: "&nbsp;",
            width: "70px"
        }, {
            field: "access_id",
            title: "Access Id",
            hidden: true,
        }, {
            field: "table_name",
            title: "Table Name",
            editor: regexTableDropDownEditor,
            width: "110px",
        }, {
            field: "group_name",
            title: "Group Name",
            editor: regexTableGroupDropDownEditor,
            width: "110px",
        }, {
            field: "column_name",
            title: "Column Name",
            width: "110px"
        }],
        save: function(ReferenceTableData) {
            if (ReferenceTableData.values.table_name) {
                if (ReferenceTableData.values.table_name.table_name) {
                    table_name = ReferenceTableData.values.table_name.table_name;
                } else {
                    table_name = ReferenceTableData.values.table_name;
                }
            } else {
                table_name = ReferenceTableData.model.table_name;
            }
            if (ReferenceTableData.values.group_name) {
                if (ReferenceTableData.values.group_name.group_name) {
                    group_name = ReferenceTableData.values.group_name.group_name;
                } else {
                    group_name = ReferenceTableData.values.group_name;
                }
            } else {
                group_name = ReferenceTableData.model.group_name;
            }
            if (ReferenceTableData.values.column_name) {
                if (ReferenceTableData.values.column_name.column_name) {
                    column_name = ReferenceTableData.values.column_name.column_name;
                } else {
                    column_name = ReferenceTableData.values.column_name;
                }
            } else {
                column_name = ReferenceTableData.model.column_name;
            }
            jQuery.ajax({
                type: 'POST',
                data: {
                    _token: token,
                    table_name: table_name,
                    column_name: column_name,
                    group_name: group_name,
                    access_id:ReferenceTableData.model.access_id,
                },
                url: requestUrl + "/updateAccessTable",
                success: function(RefTableData) {
                    response = RefTableData.type;
                    if (response.localeCompare("success") == 0) {
                        message = RefTableData.message;
                    } else if (response.localeCompare("error") == 0) {
                        message = RefTableData.message;
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
                    regexTableAccessListGrids.dataSource.read();
                    kendoGrid.dataSource.read();
                }
            });
        }
    });
$("#RegexRefTableBtnSearch").click(function() {
        var searchValue = $('#RegexRefTableSearchBox').val();
        $("#regexTableAccessListGrid").data("kendoGrid").dataSource.filter({
            logic: "or",
            filters: [{
                field: "table_name",
                operator: "contains",
                value: searchValue
            }, {
                field: "group_name",
                operator: "contains",
                value: searchValue
            }, {
                field: "column_name",
                operator: "contains",
                value: searchValue
            }]
        });
    });
    $('#RegexRefTableSearchBox').keypress(function(e) {
        if (e.which == 13) { //Enter key pressed
            $('#regexTableAccessListGrid').click(); //Trigger search button click event
        }
    });
    //Clearing the filter
    $("#RegexRefTableBtnReset").click(function() {
        $('#RegexRefTableSearchBox').val("");
        $("#regexTableAccessListGrid").data("kendoGrid").dataSource.filter({});
    });
    function deleteAccessTable(e){
        e.preventDefault();
        var dataItem = this.dataItem($(e.currentTarget).closest("tr"));
        jQuery.ajax({
            type: 'GET',
            data: {
                access_id: dataItem.access_id,
            },
            url: requestUrl + "/deleteAccessTable",
            success: function(refTableDetailsDetails) {
                regexTableAccessListGrids.dataSource.read();
                kendoGrid.dataSource.read();
            }
        });

    }
    var regexTableAccessListGrids = $("#regexTableAccessListGrid").data("kendoGrid");
    
    $(document).on("click",".addSplitter",function(){             

        var newSplitData = $(".splitDemo .child").clone();
        var totalSpliter = $(this).parents().eq(8).find(".child_data").children("div").length;

        newSplitData.addClass("child_"+totalSpliter).removeClass("child");

        newSplitData.find(".regextype")
                        .attr("name","splitData[child]["+totalSpliter+"][type_id]");
        newSplitData.find(".delimiter")
                        .attr("name","splitData[child]["+totalSpliter+"][delimiter]");
        newSplitData.find(".variable")
                        .attr("name","splitData[child]["+totalSpliter+"][variable]");
        newSplitData.find(".regextype")
                        .select2({
                           theme: "bootstrap",
                           placeholder: "Please Select Option",
                           width: '100%'
                        });
        $(this).parents().eq(8).find(".child_data").append(newSplitData);
        
    });

    $(document).on("click",".updateSplitData",function(){

        jQuery.ajax({
            type: 'POST',
            data: $("#regexSplitForm").serialize(),
            url: requestUrl + "/updateSplitData",
            success: function(json) {
                data = JSON.parse(json);

                if(data.type == "success"){
                    $("#regexSplitHierarchyGrid").data("kendoGrid").dataSource.read();
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
                var $toast = toastr[data.type]("",data.message);
                $("input[name='splitData[split_id]").val("");
                $('.regextype').val("").trigger("change");
                $('#regexSplitForm').trigger("reset");
                $('.child_data > div').slice(1).remove();
            }
        });
    });

    var regexSplitHierarchyGrid = jQuery("#regexSplitHierarchyGrid").kendoGrid({
        dataSource: {
            serverPaging: false,
            pageSize: 20,
            transport: {
                read: {
                    url: requestUrl + '/getRegexSplitData',
                    dataType: "json",
                    type: "GET",
                },
                destroy: {
                    data: {
                        _token: token
                    },
                    url: requestUrl + "/deleteSplitData",
                    dataType: "json",
                    type: "POST",
                    complete: function(json) {
                        $("#regexSplitHierarchyGrid").data("kendoGrid").dataSource.read();
                    }
                },
            },
            schema: {
                model: {
                    id: 'split_id',
                    fields: {                        
                        marker: {
                            editable: false
                        },
                        node: {
                            editable: false
                        },
                        identity_name: {
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
        editable: 'inline',
        detailTemplate: '<div class="regexSplitPrimitiveGrid"></div>',
        detailInit: detailInit,
        selectable: 'row',
        change: function () {
            var selectedRows = this.select();
            var rowData = this.dataItem(selectedRows[0]);
            loadAction(rowData.split_id);
        },
        columns: [{
            command: [{
                name: "destroy",
                text: " ",
                template: "<a class='k-button k-grid-delete' style='font-size:15px'><span class='fa fa-trash-o'></span></a>"
            }],
            title: "Action",
            width: "60"
        }, {
            field: "split_id",
            hidden: true,
        }, {
            field: "marker",
            title: "Marker",
        }, {
            field: "node",
            title: "Node",
        }, {
            field: "identity_name",
            title: "Owner",
        }],
    });

    function detailInit(splitPrimitiveDetails) {
        var RegexSplitPrimitiveGrid = splitPrimitiveDetails.detailRow.find(".regexSplitPrimitiveGrid").kendoGrid({
            dataSource: {
                pageSize: 5,
                transport: {
                    read: {
                        url: requestUrl + "/getRegexSplitPrimitiveData" + "/" + splitPrimitiveDetails.data.split_id,
                        type: "GET",
                        dataType: "json",
                    }
                },
                schema: {
                    model: {
                        id: 'split_primitive_id',
                    },
                },
            },
            pageable: {
                refresh: true,
                pageSizes: true
            },
            autoSync: true,
            reorderable: true,            
            groupable: true,
            resizable: true,
            editable: false,
            scrollable: true,
            sortable: true,
            columns: [{
                field: "type_name",
                title: "Regex Type",
            }, {
                field: "delimiter",
                title: "Delimiter",
            }, {
                field: "variable",
                title: "Variable",
            }]
        }).data("kendoGrid");      
    }

    function loadAction(splitId) {
    
        jQuery.ajax({
            type: 'POST',
            data: {
                split_id: splitId,
                _token: token
            },
            url: requestUrl + "/getSplitData",
            success: function(regexDetails) {
                data = JSON.parse(regexDetails);
                splitData = data.splitData;
                splitPrimitiveData = data.splitPrimitiveData;

                $("input[name='splitData[split_id]").val(splitData.split_id);
                $("input[name='splitData[marker]").val(splitData.marker);
                $("input[name='splitData[node]").val(splitData.node);
                $('.child_data > div').slice(1).remove();
                $.each(splitPrimitiveData, function(jsonKey, jsonVal) {
                    if(jsonKey > 0){
                        $(".addSplitter").trigger("click");                        
                    }
                    $("select[name='splitData[child]["+jsonKey+"][type_id]").val(jsonVal.type_id);
                    $("select[name='splitData[child]["+jsonKey+"][type_id]").trigger("change");
                    $("input[name='splitData[child]["+jsonKey+"][delimiter]").val(jsonVal.delimiter);
                    $("input[name='splitData[child]["+jsonKey+"][variable]").val(jsonVal.variable);
                });

            }
        });
    }

});