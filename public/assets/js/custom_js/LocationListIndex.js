var constant = {
    TOKEN: $("input[name=_token]").val(),
    ACTION_URL: $('#identityCityListForm').attr('action'),
    FORM_ID: "#identityCityListForm",

    POSTAL_FORM_ID: "#locationForm",
    UPDATE_LOCATION_METHOD: "/updateLocation",
    GET_POSTAL_METHOD: "/getPostalAddress",

    TREE_NAME: "topologyTree",
    TREE_ID: "#topologyTree",
    TREE_ERROR_ID: "#treeError",
    TREE_METHOD_NAME: "/getLocationTree",

    DROPDOWN_ID: "#identity_table_id",
    DROPDOWN_PRIMARY_ID: "identity_table_id",
    DROPDOWN_PRIMARY_VAL: "identity_table_name",

    COMBOBOX_ID: "#identity_id",
    GET_IDENTITY_METHOD_NAME: "/getIdentities",
    COMBOBOX_PRIMARY_ID: "identity_id",
    COMBOBOX_PRIMARY_VAL: "identity_name",
    COMBO_TYPE_ERROR_MESSAGE: "Identity Required",

    MULTI_SELECT_DROPDOWN_ID: "#region_id",
    MULTI_METHOD_NAME: "/getRegions",
    MULTI_DROPDOWN_PRIMARY_ID: "region_id",
    MULTI_DROPDOWN_PRIMARY_VAL: "region_name",
    MULTI_TYPE_ERROR_MESSAGE: "Please select at least one Identity",

    GET_LIST_METHOD_NAME: "/getIdentityCityList",
    GET_LIST_DATA_METHOD_NAME: "/getIdentityCityListData",

    KENDO_GRID_ID: "#identityCityListGrid",
    KENDO_GRID_PRIMARY_ID: "list_id",

    UPDATE_LIST_METHOD: "/updateList",
    GET_LOCATON_METHOD: "/getLocationData",

    COUNTRY_METHOD_NAME: "/getCountries",
    STATE_METHOD_NAME: "/getStates",
    COUNTY_METHOD_NAME: "/getCounties",
    CITY_METHOD_NAME: "/getCities",
    TREE_INTERVAL: 10,
    ADD_MULTISELECT: 50,
    HIDE_FIRST_NODE: 6,
    AUTO_SCROLLING: 113,
    RADIO_TREE_CHECK_ID: 0,
    CHECK_LENGTH: 0,
    HIGHLIGHT_NODE: -1,
    PATH_LENGTH_COUNT: 0,
    DIV_SCROLL_TOP: 0,
    SEARCH_NODE: 2,
    ASSIGN_VALUE: 0,


}

var holidayOptions; 
$(document).ready(function() {

    var topmodal = $("#top_modal").html(); 
    $("#tabstrip").kendoTabStrip().data("kendoTabStrip");

    var kendoTreeUrl = constant.ACTION_URL + constant.TREE_METHOD_NAME;
      

    $(constant.DROPDOWN_ID).kendoDropDownList({
        dataTextField: constant.DROPDOWN_PRIMARY_VAL,
        dataValueField: constant.DROPDOWN_PRIMARY_ID,
        value: 8,
        dataSource: [{
                "identity_table_name": "Merchants",
                "identity_table_id": "8"
            },
            {
                "identity_table_name": "Customers",
                "identity_table_id": "4"
            },
            {
                "identity_table_name": "Peoples",
                "identity_table_id": "15"
            },
            {
                "identity_table_name": "Payees",
                "identity_table_id": "21"
            }
        ],
        change: function(e) {


            var identity_table_id = parseInt(this.value());

            var identityDataSource = new kendo.data.DataSource({
                transport: {
                    read: {
                        data: {
                            identity_table_id: identity_table_id,
                            _token: constant.TOKEN
                        },
                        url: constant.ACTION_URL + constant.GET_IDENTITY_METHOD_NAME,
                        dataType: "json",
                        type: "POST"
                    }
                }
            });

            kendoIdentity.setDataSource(identityDataSource);
            kendoIdentity.value("");
            kendoRegion.value([]);
        }
    });

    $(constant.COMBOBOX_ID).kendoComboBox({
        placeholder: "Select Identity...",
        dataTextField: constant.COMBOBOX_PRIMARY_VAL,
        dataValueField: constant.COMBOBOX_PRIMARY_ID,
        filter: "contains",
        height: 400,
        change: function(e) {

            if (!this.value()) {
                kendoTree.dataSource.read();
                kendoRegion.value([]);
                return false;
            }

            $('.content .preloader').show();
            $('.content .preloader img').show();

            kendoGrid.dataSource.read();

            var identity_table_id = kendoIdentityTable.value();
            var identity_id = this.value();

            $.ajax({

                type: 'POST',
                data: {
                    _token: constant.TOKEN,
                    identity_id: identity_id,
                    identity_table_id: identity_table_id
                },
                url: constant.ACTION_URL + constant.GET_LIST_METHOD_NAME,
                dataType: 'json',

                success: function(json) {                    
                    var cities = [];
                    $.each(json, function(key, value) {
                        path = value.country_id + "_country/" + value.state_id + "_state/" + value.city_id + "_city";
                        nodeID = value.location_city_id + "_city";
                        cities.push(nodeID);
                        var treeName = constant.TREE_NAME;
                        getTreeExpandWithSelectedNode(nodeID, path, treeName);
                    });
                    var previousNodes = kendoRegion._savedOld;
                    var currentNodes = cities;
                    var differanceOfNodes = jQuery(previousNodes).not(currentNodes).get();
                    kendoRegion._savedOld = cities;
                    if (differanceOfNodes != '') {
                        $.each(differanceOfNodes, function(key, value) {
                            removeFromTree(value);
                        });
                    }
                    kendoRegion.value(cities);
                    setTimeout(function() {
                        var treeName = constant.TREE_NAME;
                        kendoUiHoverAutoScrollingNodes(treeName);
                    }, constant.AUTO_SCROLLING);

                }
            });
            
            var gridDataSource = new kendo.data.DataSource({
               transport: {
                   read: {
                       data: {
                           _token: constant.TOKEN,
                            identity_id: identity_id,
                            identity_table_id: identity_table_id
                       },
                       url: constant.ACTION_URL + constant.GET_LIST_DATA_METHOD_NAME,
                       type: 'POST',
                       dataType: "json"
                   },
               },
               serverPaging: true,
               serverSorting: true,
               pageSize: 5,
               batch: true,
               schema: {
                   data: "identity_city_lists",
                   total: "total",
                   model: {
                       list_id: constant.KENDO_GRID_PRIMARY_ID,
                   },
               },
            });
            kendoGrid.setDataSource(gridDataSource);

            $('.content .preloader').hide();
            $('.content .preloader img').hide();
        }
    }).data("kendoComboBox");

    $(constant.MULTI_SELECT_DROPDOWN_ID).kendoMultiSelect({
        placeholder: "Enter City...",
        dataTextField: constant.MULTI_DROPDOWN_PRIMARY_VAL,
        dataValueField: constant.MULTI_DROPDOWN_PRIMARY_ID,
        filter: "contains",
        height: 400,
        dataSource: {
            transport: {
                read: {
                    dataType: "json",
                    url: constant.ACTION_URL + constant.MULTI_METHOD_NAME,
                    type: "GET"
                }
            }
        },
        select: onSelectChange,
        change: function() {
            var previousNodes = this._savedOld;
            var currentNodes = this.value();
            var differanceOfNodes = jQuery(previousNodes).not(currentNodes).get();
            saveCurrent(this);
            if (differanceOfNodes != '') {
                removeFromTree(differanceOfNodes);
            }
            setTimeout(function() {
                var treeName = constant.TREE_NAME;
                kendoUiHoverAutoScrollingNodes(treeName);
            }, constant.AUTO_SCROLLING);
        }
    }).data("kendoMultiSelect");

    function saveCurrent(multiSelect) {
        multiSelect._savedOld = multiSelect.value().slice(constant.ASSIGN_VALUE);
    }

    var validator = $(constant.FORM_ID).kendoValidator({
        rules: {
            hasMerchant: function(input) {
                if (input.is("[id=" + constant.COMBOBOX_PRIMARY_ID + "]")) {
                    var ms = input.data("kendoComboBox");
                    if (ms.value().length === 0) {
                        return false;
                    }
                }
                return true;
            },
        },
        messages: {
            hasMerchant: constant.COMBO_TYPE_ERROR_MESSAGE
        }
    }).data("kendoValidator");

    var kendoTree = "";

    jQuery.getJSON(kendoTreeUrl, function(JsonReturnData) {
        /* If parent need checkbox */
        //treeTemplate = "# if(!item.hasChildren){# <input type='hidden' id='#=item.id#' parent_id='#=item.parent_id#' d_text='#=item.text#'/> <input type='checkbox' name_a='#= item.text #'  id_a='#= item.id #' name='c_#= item.id #' value='true' />#}else{# <input type='hidden' id='#=item.id#' parent_id='#=item.parent_id#' d_text='#=item.text#'/> <input type='checkbox' name_a='#= item.text #' id_a='#= item.id #' name='c_#= item.id #' value='true' /> #}#";

        /*only child checkbox */
        //treeTemplate = "# if(!item.hasChildren){# <input type='hidden' id='#=item.id#' parent_id='#=item.parent_id#' d_text='#=item.text#'/> <input type='checkbox' name_a='#= item.text #' id_a='#= item.id #' name='c_#= item.parent_id #' value='true' />#}else{# <input type='hidden' id='#=item.id#' parent_id='#=item.parent_id#' d_text='#=item.text#'/> #}#";

        /*if dosen't have child */
        //treeTemplate = "# if(!item.item){# <input type='hidden' id='#=item.id#' parent_id='#=item.parent_id#' d_text='#=item.text#'/> <input type='checkbox' name_a='#= item.text #' id_a='#= item.id #' name='c_#= item.parent_id #' value='true' />#}else{# <input type='hidden' id='#=item.id#' parent_id='#=item.parent_id#' d_text='#=item.text#'/> #}#";

        treeTemplate = "# if(item.level() > 1){# <input type='hidden' id='#=item.id#' parent_id='#=item.parent_id#' d_text='#=item.text#'/> <input type='checkbox' name_a='#= item.text #' id_a='#= item.id #' name='c_#= item.parent_id #' value='true' />#}else{# <input type='hidden' id='#=item.id#' parent_id='#=item.parent_id#' d_text='#=item.text#'/> #}#";
        $(constant.TREE_ID).kendoTreeView({
            checkboxes: {
                checkChildren: true,
                template: treeTemplate,
            },
            dataSource: {
                data: JsonReturnData
            },
            dataTextField: "text",
            dataValueField: "id",
            select: function(e) {
                e.preventDefault();
                var checkNodes = [];
                var checkbox = $(e.node).find(":checkbox");

                var checked = checkbox.prop("checked");
                checkbox.prop("checked", !checked);

                $(constant.TREE_ID + " .k-item input[type=checkbox]:checked").each(function() {
                    checkNodes.push($(this).attr('id_a'));
                });
                kendoRegion.value(checkNodes);
            },
            check: function(event) {
                var checkNodes = [];
                $(constant.TREE_ID + " .k-item input[type=checkbox]:checked").each(function() {
                    checkNodes.push($(this).attr('id_a'));
                });
                kendoRegion.value([]);
                kendoRegion.value(checkNodes);
            }
        });
        kendoTree = $(constant.TREE_ID).data("kendoTreeView");
    });

    var kendoIdentityTable = $(constant.DROPDOWN_ID).data("kendoDropDownList");
    var kendoIdentity = $(constant.COMBOBOX_ID).data("kendoComboBox");
    var kendoRegion = $(constant.MULTI_SELECT_DROPDOWN_ID).data("kendoMultiSelect");


    kendoIdentityTable.trigger("change");

    function onSave(data) {
        var list_id = data.model.list_id;
        var key = "";
        var value = 0;

        if (typeof data.values.status !== 'undefined') {
            key = "status";
            value = data.values.status;
        } else if (typeof data.values.priority !== 'undefined') {
            key = "priority";
            value = data.values.priority;
        } else {
            return false;
        }

        $.ajax({
            type: 'POST',
            data: {
                list_id: list_id,
                key: key,
                value: value,
                _token: constant.TOKEN
            },
            url: constant.ACTION_URL + constant.UPDATE_LIST_METHOD,
            success: function(eventData) {
                $(constant.KENDO_GRID_ID).data("kendoGrid").dataSource.read();
            }
        });
    }

    var orderTypeList = jQuery(constant.KENDO_GRID_ID).kendoGrid({
        dataSource: {
            serverPaging: true,
            pageSize: 20,
            schema: {
                model: {
                    list_id: constant.KENDO_GRID_PRIMARY_ID,
                    fields: {
                        priority: {
                            editable: true,
                            type: "number",
                            validation: {
                                required: true,
                                min: 0,
                                max: 99
                            }
                        },
                        status: {
                            editable: true,
                            defaultValue: {
                                value: 1,
                                text: "Enable"
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
        serverFiltering: true,
        groupable: true,
        resizable: true,
        editable: true,
        selectable: 'row',
        save: onSave,
        edit: function(e) {
            var columnNotEditableIndex = [0, 1, 2, 3, 4, 5, 6];
            if ($.inArray(parseInt(e.container.index()), columnNotEditableIndex) != -1) {
                this.closeCell();
            }
        },
        columns: [{
            command: {
                text: "Edit",
                click: showDetails
            },
            title: " ",
            attributes: {
                style: "#=(!data.postal_lat || !data.postal_lng) ? 'background-color:red' : '' #"
            },
            width: "100px",
        }, {
            field: constant.KENDO_GRID_PRIMARY_ID,
            title: "List Id",
            hidden: true,
        }, {
            field: "identity_code",
            title: "Identity Code",
        }, {
            field: "country_name",
            title: "Country",
        }, {
            field: "state_name",
            title: "State",
        }, {
            field: "county_name",
            title: "County",
        }, {
            field: "city_name",
            title: "City",
        }, {
            field: "priority",
            title: "Priority",
        }, {
            field: "status",
            title: "Status",
            editor: statusDropDownEditor,
            template: "#=(data.status)?'Enable':'Disable'#"
        }],
    });
    var kendoGrid = $(constant.KENDO_GRID_ID).data("kendoGrid");

    function statusDropDownEditor(container, options) {
        var data = [{
                Description: "Enable",
                ID: "1"
            },
            {
                Description: "Disable",
                ID: "0"
            }
        ];
        $('<input data-text-field="Description" data-value-field="ID" data-bind="value:' + options.field + '"/>')
            .appendTo(container)
            .kendoDropDownList({
                dataSource: data,
                dataTextField: "Description",
                dataValueField: "ID"
            });
    };


    function showDetails(e) {
        e.preventDefault();

        $('.postal').hide();
        $('.postal-max').hide();
        $('.content .preloader').show();
        $('.content .preloader img').show();

        var tabstrip = $("#tabstrip").data("kendoTabStrip");
        tabstrip.remove("li:not(:first,:last)").select('li:first');

        var dataItem = this.dataItem($(e.currentTarget).closest("tr"));
        $.ajax({
            data: {
                _token: constant.TOKEN,
                identity_id: dataItem.identity_id,
                identity_table_id: dataItem.identity_table_id,
                location_city_id: dataItem.location_city_id
            },
            type: 'POST',
            dataType: 'json',
            url: constant.ACTION_URL + constant.GET_LOCATON_METHOD,
            success: function(json) {
                              
                holidayOptions = "<option></option>";
                $.each(json.holidays, function(holidayKey, holidayData) {
                    holidayOptions += "<option value='"+holidayData.holiday_id+"' data-date='"+holidayData.holiday_date+"'>"+holidayData.holiday_name+"</option>";                              
                }); 

                $.each(json.locationData, function(jsonKey, jsonVal) {

                    $tabIndex = jsonKey;
                    
                    if (jsonKey > 0) {                        
                        $('#tabstrip > ul > .k-last').trigger("click");
                    } else {
                        postalInitialize($tabIndex);
                        getLocationEventIntialize($tabIndex);
                    }

                    var postal_street_number = $("#street_number_" + $tabIndex).data("kendoComboBox");
                    var postal_route = $("#route_" + $tabIndex).data("kendoComboBox");
                    var postal_neighborhood = $("#neighborhood_" + $tabIndex).data("kendoComboBox");
                    var postal_postcode = $("#postcode_" + $tabIndex).data("kendoComboBox");

                    $('#countryName').text(jsonVal.country_name);
                    $('#stateName').text(jsonVal.state_name);
                    $('#countyName').text(jsonVal.county_name);
                    $('#cityName').text(jsonVal.city_name);
                    $('#list_identity_id').val(jsonVal.identity_id);
                    $('#list_identity_table_id').val(jsonVal.identity_table_id);
                    $('#list_location_city_id').val(jsonVal.location_city_id);
                    
                    $("#list_id_"+$tabIndex).val(jsonVal.list_id);

                    $("#subpremise_"+$tabIndex).val((jsonVal.postal_subpremise != 'None')?jsonVal.postal_subpremise:'');
                    $("#premise_"+$tabIndex).val((jsonVal.postal_premise != 'None')?jsonVal.postal_premise:'');
                    
                    $("#postal_id_"+$tabIndex).val(jsonVal.postal_id);
                    $('#city_name').val(jsonVal.city_name);
                    $('#postal_code_max').val(jsonVal.postal_code_max);

                    if (jsonVal.postal_id) {

                        postal_street_number.text(jsonVal.postal_street_number);
                        postal_route.text(jsonVal.postal_route);
                        postal_neighborhood.text(jsonVal.postal_neighborhood);
                        if (jsonVal.postal_postcode) {
                            postal_postcode.text(jsonVal.postal_postcode.toString());
                        } else {
                            postal_postcode.value(-1);
                        }

                        $("#lat_"+$tabIndex).val(jsonVal.postal_lat);
                        $("#lng_"+$tabIndex).val(jsonVal.postal_lng);

                        $("input[name='postals["+$tabIndex+"][delivery_time]']").val(jsonVal.delivery_time);
                        $("input[name='postals["+$tabIndex+"][collection_time]']").val(jsonVal.collection_time);
                        $("input[name='postals["+$tabIndex+"][last_order_time]']").val(jsonVal.last_order_time);
                        $("input[name='postals["+$tabIndex+"][future_order_delivery_days]']").val(jsonVal.future_order_delivery_days);
                        $("input[name='postals["+$tabIndex+"][future_order_collection_days]']").val(jsonVal.future_order_collection_days);
                        $("input[name='postals["+$tabIndex+"][reservation_time_interval]']").val(jsonVal.reservation_time_interval);
                        $("input[name='postals["+$tabIndex+"][reservation_stay_time]']").val(jsonVal.reservation_stay_time);
                        
                        if (jsonVal.offer_delivery) {   
                            $("input[name='postals["+$tabIndex+"][offer_delivery]']").bootstrapSwitch('state', true);
                        } else {
                            $("input[name='postals["+$tabIndex+"][offer_delivery]']").bootstrapSwitch('state', false);
                        }

                        if (jsonVal.offer_collection) {
                            $("input[name='postals["+$tabIndex+"][offer_collection]']").bootstrapSwitch('state', true);
                        } else {
                            $("input[name='postals["+$tabIndex+"][offer_collection]']").bootstrapSwitch('state', false);
                        }

                        if (jsonVal.future_orders) {   
                            $("input[name='postals["+$tabIndex+"][future_orders]']").bootstrapSwitch('state', true);
                            $("#postal_"+$tabIndex+" #future-orders-days").show();
                        } else {
                            $("input[name='postals["+$tabIndex+"][future_orders]']").bootstrapSwitch('state', false);
                            $("#postal_"+$tabIndex+" #future-orders-days").hide();
                        } 

                        $("select[name='postals["+$tabIndex+"][holiday_hours][0][0][holiday_id]']").html(holidayOptions);                           

                        $("#postal_"+ $tabIndex + " .postal").show();
                    }                    

                });

                setTimeout(function(){                    

                    $.each(json.workingHours, function(listId, workingHoursData) {

                        $.each(workingHoursData, function(dayId, workingHourData) {                            

                            if(workingHourData.shift > 0){
                                shiftHtml = '<div class="shift"><label class="col-sm-1 control-label text-right input-status"></label>';
                                shiftHtml += '<div class="col-sm-9">';
                                shiftHtml += '<div class="control-group control-group-3 group-margin">';
                                shiftHtml += '<div class="input-group" style="width: 29.1%; float: left;">';
                                shiftHtml += '<input type="text" name="postals['+listId+'][flexible_hours]['+workingHourData.weekday+']['+workingHourData.shift+'][open]" class="form-control timeclock" value="10:00">';
                                shiftHtml += '<span class="input-group-addon"><i class="fa fa-clock-o"></i></span></div>';
                                shiftHtml += '<div class="input-group" style="width: 29.1%; margin-left: 16px; float: left;">';
                                shiftHtml += '<input type="text" name="postals['+listId+'][flexible_hours]['+workingHourData.weekday+']['+workingHourData.shift+'][close]" class="form-control timeclock" value="23:00">';
                                shiftHtml += '<span class="input-group-addon"><i class="fa fa-clock-o"></i></span></div>';
                                shiftHtml += '<div class="btn-group btn-group-switch" data-toggle="buttons">';
                                shiftHtml += '<div class="make-switch col-sm-5" data-on="danger" data-off="default">';
                                shiftHtml += '<input type="checkbox" name="postals['+listId+'][flexible_hours]['+workingHourData.weekday+']['+workingHourData.shift+'][status]" value="1" class="hourstatus" data-on-text="Open" data-off-text="Closed">';
                                shiftHtml += '</div></div>';
                                shiftHtml += '<a class="btn btn-danger" onclick="confirm(\'This can not be undone! Are you sure you want to do this?\') ? $(this).parent().parent().parent().remove() : false;" style="padding: 4px 6px; margin-left: 5px;"><i class="fa fa-times-circle"></i></a>';
                                shiftHtml += '</div></div></div>';

                                $("#postal_"+listId+" #opening-flexible .info_"+workingHourData.weekday).append(shiftHtml);
                                assignWorkingShiftTimer(listId,workingHourData.weekday,workingHourData.shift);
                            } 

                            $("input[name='postals["+listId+"][flexible_hours]["+workingHourData.weekday+"]["+workingHourData.shift+"][open]']").val(workingHourData.opening_time);
                            $("input[name='postals["+listId+"][flexible_hours]["+workingHourData.weekday+"]["+workingHourData.shift+"][close]']").val(workingHourData.closing_time);
                            $("input[name='postals["+listId+"][flexible_hours]["+workingHourData.weekday+"]["+workingHourData.shift+"][status]']").val(workingHourData.status);
                            
                            if (workingHourData.status) {   
                                $("input[name='postals["+listId+"][flexible_hours]["+workingHourData.weekday+"]["+workingHourData.shift+"][status]']").bootstrapSwitch('state', true);
                            } else {
                                $("input[name='postals["+listId+"][flexible_hours]["+workingHourData.weekday+"]["+workingHourData.shift+"][status]']").bootstrapSwitch('state', false);
                            }                            
                        });                        
                    });

                    $.each(json.holidayHours, function(listId, holidayHoursData) {
                        $.each(holidayHoursData, function(dayId, holidayHourData) {                            
                            
                            if(dayId > 0){
                                var currentPostalTabID = listId;
                                var totalHoliday = $("#postal_"+currentPostalTabID+" .holidays").children("div").length;
                                var holidayContent = $("#demo .holiday").clone();

                                holidayContent.addClass("holiday_"+totalHoliday);
                                holidayContent.removeClass("holiday");
                                holidayContent.find("#holiday_id")
                                                    .attr("name","postals["+currentPostalTabID+"][holiday_hours]["+totalHoliday+"][0][holiday_id]");
                                holidayContent.find("#holiday_date")
                                                    .attr("name","postals["+currentPostalTabID+"][holiday_hours]["+totalHoliday+"][0][date]");
                                holidayContent.find("#holiday_open")
                                                    .attr("name","postals["+currentPostalTabID+"][holiday_hours]["+totalHoliday+"][0][open]");
                                holidayContent.find("#holiday_close")
                                                    .attr("name","postals["+currentPostalTabID+"][holiday_hours]["+totalHoliday+"][0][close]");
                                holidayContent.find("#holiday_status")
                                                    .attr("name","postals["+currentPostalTabID+"][holiday_hours]["+totalHoliday+"][0][status]");
                                holidayContent.find(".addHolidayShift")
                                                    .attr("data-tab-id",currentPostalTabID)
                                                    .attr("data-holiday-id",totalHoliday);                                                    

                                $("#postal_"+currentPostalTabID+" .holidays").append(holidayContent);
                                initHolidaySelect(currentPostalTabID,totalHoliday);
                                initHolidayDatePicker(currentPostalTabID,totalHoliday);
                                initHolidayTimer(currentPostalTabID,totalHoliday);
                                assignHolidayDate(currentPostalTabID,totalHoliday);
                            }

                            $("select[name='postals["+listId+"][holiday_hours]["+dayId+"]["+holidayHourData.shift+"][holiday_id]']").html(holidayOptions);
                            $("select[name='postals["+listId+"][holiday_hours]["+dayId+"]["+holidayHourData.shift+"][holiday_id]']").val(holidayHourData.holiday_id);
                            $("select[name='postals["+listId+"][holiday_hours]["+dayId+"]["+holidayHourData.shift+"][holiday_id]']").change();
                            $("input[name='postals["+listId+"][holiday_hours]["+dayId+"]["+holidayHourData.shift+"][open]']").val(holidayHourData.opening_time);
                            $("input[name='postals["+listId+"][holiday_hours]["+dayId+"]["+holidayHourData.shift+"][close]']").val(holidayHourData.closing_time);
                            $("input[name='postals["+listId+"][holiday_hours]["+dayId+"]["+holidayHourData.shift+"][status]']").val(holidayHourData.status);
                            
                            if (holidayHourData.status) {   
                                $("input[name='postals["+listId+"][holiday_hours]["+dayId+"]["+holidayHourData.shift+"][status]']").bootstrapSwitch('state', true);
                            } else {
                                $("input[name='postals["+listId+"][holiday_hours]["+dayId+"]["+holidayHourData.shift+"][status]']").bootstrapSwitch('state', false);
                            } 

                            $.each(holidayHourData.shiftData,function(shiftId,shiftHourData){
                                
                                shiftHtml = '<div class="form-group" style="clear: both;">';
                                shiftHtml += '<div class="col-sm-5"></div>';
                                shiftHtml += '<div class="holiday_info_'+shiftHourData.shift+'">';
                                shiftHtml += '<div class="col-sm-6">';
                                shiftHtml += '<div class="control-group control-group-3 group-margin">';
                                shiftHtml += '<div class="input-group" style="width: 29.1%; float: left;">';
                                shiftHtml += '<input type="text" name="postals['+listId+'][holiday_hours]['+dayId+']['+shiftHourData.shift+'][open]" class="form-control timeclock" value="10:00">';
                                shiftHtml += '<span class="input-group-addon"><i class="fa fa-clock-o"></i></span>';
                                shiftHtml += '</div>';
                                shiftHtml += '<div class="input-group" style="width: 29.1%; margin-left: 16px; float: left;">';
                                shiftHtml += '<input type="text" name="postals['+listId+'][holiday_hours]['+dayId+']['+shiftHourData.shift+'][close]" class="form-control timeclock" value="10:00">';
                                shiftHtml += '<span class="input-group-addon"><i class="fa fa-clock-o"></i></span>';
                                shiftHtml += '</div>';
                                shiftHtml += '<div class="btn-group btn-group-switch" data-toggle="buttons" style="float: left;">';
                                shiftHtml += '<div class="make-switch col-sm-5" data-on="danger" data-off="default">';
                                shiftHtml += '<input type="checkbox" name="postals['+listId+'][holiday_hours]['+dayId+']['+shiftHourData.shift+'][status]" class="hourstatus" value="1" data-on-text="Open" data-off-text="Closed">';
                                shiftHtml += '</div>';
                                shiftHtml += '</div>';
                                shiftHtml += '<a class="btn btn-danger" onclick="confirm(\'This can not be undone! Are you sure you want to do this?\') ? $(this).parents().eq(3).remove() : false;" style="padding: 4px 6px;"><i class="fa fa-times-circle"></i></a>';
                                shiftHtml += '</div></div></div></div>';

                                $("#postal_"+listId+" .holidays .holiday_"+dayId).append(shiftHtml);

                                $("input[name='postals["+listId+"][holiday_hours]["+dayId+"]["+shiftHourData.shift+"][open]']").val(shiftHourData.opening_time);
                                $("input[name='postals["+listId+"][holiday_hours]["+dayId+"]["+shiftHourData.shift+"][close]']").val(shiftHourData.closing_time);
                                $("input[name='postals["+listId+"][holiday_hours]["+dayId+"]["+shiftHourData.shift+"][status]']").val(shiftHourData.status);
                                
                                if (shiftHourData.status) {   
                                    $("input[name='postals["+listId+"][holiday_hours]["+dayId+"]["+shiftHourData.shift+"][status]']").bootstrapSwitch('state', true);
                                } else {
                                    $("input[name='postals["+listId+"][holiday_hours]["+dayId+"]["+shiftHourData.shift+"][status]']").bootstrapSwitch('state', false);
                                }

                                initHolidayShiftTimer(listId,dayId,shiftHourData.shift);                                
                            });                           
                        });                        
                    });
                    
                    $.each(json.reservationTables, function(listId, reservationTablesData) {

                        $.each(reservationTablesData, function(seatingId, reservationTableData) {                            

                            var tbl_class = "table-box"+reservationTableData.seating_id;
                            var tableRow = '<tr class="'+tbl_class+'">'
                                tableRow +='<td class="name">'+reservationTableData.seating_name+'</td>';
                                tableRow +='<td>'+reservationTableData.min_capacity+'</td>';
                                tableRow +='<td>'+reservationTableData.max_capacity+'</td>';
                                tableRow +='<td class="img">';
                                tableRow +='<a class="btn btn-danger btn-xs" onclick="confirm(\'This can not be undone! Are you sure you want to remove this?\') ? $(this).parent().parent().remove() : false;"><i class="fa fa-times-circle"></i></a>';
                                tableRow +='<input type="hidden" name="postals['+listId+'][tables][]" value="'+reservationTableData.seating_id+'">';
                                tableRow +='</td>';
                                tableRow +='</tr>';

                            $('#postal_'+listId+' #table-box').find('tbody').append(tableRow);    
                        });                        
                    });                    

                tabstrip.select(0);
                $('.content .preloader').hide();
                $('.content .preloader img').hide();
                $('#top_modal').modal("show");

                },1500);        
                              
            }
        });
    }

    // Submit Form

    $("#submitBtn").click(function() {
        if (validator.validate()) {
            $('.content .preloader').show();
            $('.content .preloader img').show();

            $.ajax({
                type: 'POST',
                data: $(constant.FORM_ID).serialize(),
                url: constant.ACTION_URL,
                success: function(data) {
                    kendoIdentity.trigger('change');
                    $('.content .preloader').hide();
                    $('.content .preloader img').hide();
                }
            });
        }
    });

    $(document).on('hidden.bs.modal','#top_modal', function(){
    
        $("#top_modal").html(topmodal);
        $("#tabstrip").kendoTabStrip().data("kendoTabStrip");

        // First Time Initialize All Property To Controls
        postalInitialize(0);
        getLocationEventIntialize(0);

        assignWorkingTimer(0);  // FIRST POSTAL TAB
        assignWorkingToggleSwitch(0);   
        updateWorkingToggleValue(0); 
        addNewShift();
        
        initHolidaySelect(0,0);
        initHolidayDatePicker(0,0);
        initHolidayTimer(0,0);
        assignHolidayDate(0,0);    
        addNewHoliday();
        addHolidayShift();

        initOrderToggleSwitch(0);
        assignFutureOrder(0);
        initSeatingSelect(0);
        assignReservationSeating(0);

        $('.content .preloader').hide();
        $('.content .preloader img').hide();        
    });
});

// Init Postal Tab Kendo Dropdown As Well As Inner TabStripe

function postalInitialize(parentId) {
    $("#street_number_" + parentId).kendoComboBox({
        dataTextField: "text",
        dataValueField: "value",
        dataSource: [{
            text: "None",
            value: "-1"
        }]
    }).data("kendoComboBox");

    $("#route_" + parentId).kendoComboBox({
        dataTextField: "text",
        dataValueField: "value",
        dataSource: [{
            text: "None",
            value: "-1"
        }]
    }).data("kendoComboBox");

    $("#neighborhood_" + parentId).kendoComboBox({
        dataTextField: "text",
        dataValueField: "value",
        dataSource: [{
            text: "None",
            value: "-1"
        }]
    }).data("kendoComboBox");

    $("#postcode_" + parentId).kendoComboBox({
        dataTextField: "text",
        dataValueField: "value",
        dataSource: [{
            text: "None",
            value: "-1"
        }]
    }).data("kendoComboBox");

    setTimeout(function(){
        $("#postal_"+parentId+" > .tab-content > .innertab").kendoTabStrip().data("kendoTabStrip");
    },50);
}


// Init Get Location Event 
function getLocationEventIntialize(parentId) {
    // Get Location Form Submit
    $("#postal_"+parentId+" .getLocation").unbind("click");
    $("#postal_"+parentId+" .getLocation").click(function(){

        var postal_street_number    = $("#street_number_"+parentId).data("kendoComboBox");
        var postal_route            = $("#route_"+parentId).data("kendoComboBox");
        var postal_neighborhood     = $("#neighborhood_"+parentId).data("kendoComboBox");
        var postal_postcode         = $("#postcode_"+parentId).data("kendoComboBox");

        var postalMax = parseInt($('#postal_code_max').val());

        var subpremise = $("#subpremise_"+parentId).val();
        var premise = $("#premise_"+parentId).val();

        var locationValidator = $("#locationForm").kendoValidator({     
        }).data("kendoValidator");

        if (locationValidator.validate()) {
            $('.modal .preloader').show();
            $('.modal img').show();
            $.ajax({
                
                dataType: 'json',
                type    : 'POST',
                data    : {
                            _token:constant.TOKEN,
                            city_name:$('#city_name').val(),
                            postal_subpremise:subpremise,
                            postal_premise:premise
                        },
                url     : constant.ACTION_URL+constant.GET_POSTAL_METHOD,

                success: function (json) {
                    
                    var street_number   = [{ text: "None", value: -1 }];
                    var route           = [{ text: "None", value: -1 }];
                    var neighborhood    = [{ text: "None", value: -1 }];
                    var postal_code     = [{ text: "None", value: -1 }];

                    $.each(json.street_number,function(skey,svalue){
                        street_number.push({ text: svalue, value: skey });
                    });
                    $.each(json.route,function(rkey,rvalue){
                        route.push({ text: rvalue, value: rkey });
                    });
                    $.each(json.neighborhood,function(nkey,nvalue){
                        neighborhood.push({ text: nvalue, value: nkey });
                    });
                    
                    postal_street_number.setDataSource(street_number);
                    postal_street_number.value(json.street_number);
                    postal_route.setDataSource(route);
                    postal_route.value(json.route);
                    postal_neighborhood.setDataSource(neighborhood);
                    postal_neighborhood.value(json.neighborhood);
                    postal_postcode.setDataSource(postal_code);
                    postal_postcode.value(-1);

                    $("#lat_"+parentId).val(json.lat);
                    $("#lng_"+parentId).val(json.lng);

                    $("#postal_"+parentId+" .postal").show();

                    $(constant.KENDO_GRID_ID).data("kendoGrid").dataSource.read();
                    $('.modal .preloader,.modal img').hide();
                }
            });
        }else{
            return false;
        }
    }); 
}

function getNodeClickWithExpand(idElement, treeName) {
    jQuery("input[type='checkbox'][id_a='" + idElement + "']").prop('checked', true);
    var treeView = jQuery("#" + treeName).data("kendoTreeView");
    treeView.expand(document.getElementById(idElement));
    jQuery('#' + idElement).closest('div').find('span:last').addClass("k-state-selected");
    kendoUiHoverAutoScrolling(idElement, treeName);
}

function getTreeExpandWithSelectedNode(idElement, treePath, treeName) {
    if ((jQuery("#" + idElement).length) > (constant.CHECK_LENGTH)) {
        setTimeout(function() {
            getNodeClickWithExpand(idElement, treeName);
        }, constant.ADD_MULTISELECT);
    } else {
        selectedNodePath = treePath.split('/');
        pathLength = (selectedNodePath.length);
        var pathLengthCount = constant.PATH_LENGTH_COUNT;

        function loopLi() {
            for (var pathLengthCount = constant.CHECK_LENGTH; pathLengthCount < pathLength; pathLengthCount++) {
                if (pathLengthCount < pathLength) {
                    var treeView = jQuery("#" + treeName).data("kendoTreeView");
                    treeView.expand(document.getElementById(selectedNodePath[pathLengthCount]));
                    if (idElement == selectedNodePath[pathLengthCount]) {
                        getNodeClickWithExpand(idElement, treeName);
                    }
                }
            }
        }
        jQuery(loopLi);
    }
}

function onSelectChange(eventData) {
    var treeName = constant.TREE_NAME;
    var dataItem = this.dataSource.view()[eventData.item.index()];
    getTreeExpandWithSelectedNode(dataItem.region_id, dataItem.path, treeName);

}

function removeFromTree(idElement) {
    var treeName = constant.TREE_NAME;
    jQuery("input[type='checkbox'][id_a='" + idElement + "']").trigger('click');
    jQuery('#' + idElement).closest('div').find('span:last').removeClass("k-state-selected");
    kendoUiHoverAutoScrolling(idElement, treeName);
}

function kendoUiHoverAutoScrollingNodes(treeName) {
    jQuery('div.k-multiselect-wrap ul.k-reset li').hover(function() {
        var hoverText = jQuery(this).find('span:first').text();
        var hoverId = jQuery(this).find('.multiNodeDetail').attr('region_id');
        kendoUiHoverAutoScrolling(hoverId, treeName);
    }, function() {});
}

function kendoUiHoverAutoScrolling(idElement, treeName) {
    if ((jQuery("#" + idElement).length) > (constant.CHECK_LENGTH)) {
        jQuery("#" + treeName).scrollTop(constant.DIV_SCROLL_TOP);
        treePosition = jQuery("div#" + treeName).offset().top;
        nodePosition = jQuery('#' + idElement).closest('div').offset().top;
        finalPosition = parseInt(nodePosition) - parseInt(treePosition) - (constant.HIGHLIGHT_NODE);
        jQuery("div#" + treeName).scrollTop(finalPosition);
    }
}