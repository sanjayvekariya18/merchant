var constant = {
    ACTION_URL: $('#request_url').val(),
    TOKEN: $('input[name="_token"]').val(),
    KENDO_GRID_ID: "#customerGrid",
    KENDO_GRID_METHOD: "/getCustomers/",
    KENDO_GRID_PRIMARY_ID: "customer_id",
    UPDATE_LIST_METHOD: "/updateCustomer",
    ADMIN_USER_ID:1,
    FULL_ACCESS:4
}
$(document).ready(function() {
    var topmodal = $("#top_modal").html(); 
    $("#tabstrip").kendoTabStrip().data("kendoTabStrip");

    $(".select21").select2({
        theme: "bootstrap",
        placeholder: "Please Select Option",
        width: '100%'
    });

    var kendoTreeUrl = constant.ACTION_URL + "/getLocationTree";
    var locationValidator = $("#locationForm").kendoValidator({}).data("kendoValidator");

    function onSave(data) {
        var customerGridObject = $(constant.KENDO_GRID_ID).data("kendoGrid");
        var selectedItem = customerGridObject.dataItem(customerGridObject.select());
        var customer_id = selectedItem.customer_id;
        var merchant_id = selectedItem.merchant_id;
        var identity_id = selectedItem.identity_id;
        var key = "";
        var value = 0;
        if (data.values.username) {
            key = "username";
            value = data.values.username;
        }
        if (data.values.fname) {
            key = "fname";
            value = data.values.fname;
        }
        if (data.values.lname) {
            key = "lname";
            value = data.values.lname;
        }
        if (data.values.email) {
            key = "email";
            value = data.values.email;
        }
        if (data.values.status) {
            key = "status";
            value = data.values.status;
        }
        if (data.values.google2fa_enable) {
            key = "google2fa_enable";
            value = data.values.google2fa_enable;
        }
        if (data.values.group_name) {
            key = "customer_id";
            if (data.values.group_name[1].group_name) {
                value = data.values.group_name[1].group_name;
            } else {
                value = data.values.group_name;
            }
        }
        if (data.values.merchant_name) {
            key = "merchant_name";
            if (data.values.merchant_name.merchant_name) {
                value = data.values.merchant_name.merchant_name;
            } else {
                value = data.values.merchant_name;
            }
        }
        if (data.values.password) {
            key = "password";
            value = data.values.password;
        }
        if (data.values.newsletter) {
            key = "newsletter";
            value = data.values.newsletter;
        }
        $.ajax({
            type: 'POST',
            data: {
                merchant_id: merchant_id,
                customer_id: customer_id,
                identity_id: identity_id,
                key: key,
                value: value,
                _token: constant.TOKEN
            },
            url: constant.ACTION_URL + constant.UPDATE_LIST_METHOD,
            success: function(customerDeatils) {
                response = customerDeatils.type;
                if (response.localeCompare("success") == 0) {
                    message = customerDeatils.message;
                } else if (response.localeCompare("error") == 0) {
                    message = customerDeatils.message;
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
                $(constant.KENDO_GRID_ID).data("kendoGrid").dataSource.read();
            }
        });
    }
    var customerColumnList = [];
    var userId = localStorage.getItem('userId');
    var accessibility = localStorage.getItem('accessibility');

    customerColumnList.push({
        command: {
            text: "Edit",
            click: showDetails
        },
        title: " ",
        width: "100px",
    },{
        field: "customer_id",
        title: "Customer#",
        hidden: true,
    }, {
        field: "identity_id",
        title: "identity_id",
        hidden: true,
    }, {
        field: "isdelete",
        title: "isdelete",
        hidden: true,
    }, {
        field: "merchant_id",
        title: "Merchant#",
        hidden: true,
    }, {
        field: "fname",
        title: "First Name",
        template: "#=(data.fname)?data.fname:'None'#",
        filterable: false,
        width: 100
    }, {
        field: "lname",
        title: "Last Name",
        template: "#=(data.lname)?data.lname:'None'#",
        filterable: false,
        width: 100
    }, {
        field: "email",
        title: "Email",
        template: "#=(data.email)?data.email:'None'#",
        filterable: false,
        width: 150
    }, {
        field: "username",
        title: "Name",
        filterable: false,
        width: 100
    }, {
        field: "merchant_name",
        title: "Merchant",
        editor: merchantNameComboBoxEditor,
        template: "#=(data.merchant_name)?data.merchant_name:'None'#",
        width: 140
    }, {
        field: "group_details",
        title: "Roles",
        editor: customerGroupMultiSelect,
        template: customerGroupDisplay,
        width: 120
    }, {
        field: "location_name",
        title: "Location",
        editor: locationNameComboBoxEditor,
        filterable: false,
        template: "#=(data.location_name)?data.location_name:'None'#",
        width: 120
    }, {
        field: "clear_password",
        title: "Clear Password",
        filterable: false,
        width: 120
    }, {
        field: "newsletter",
        title: "Newsletter",
        editor: newsletterDropDownEditor,
        filterable: false,
        hidden: true,
        template: "#=(data.newsletter)?'Subscribe':'Un-Subscribe'#",
        width: 120
    }, {
        field: "status",
        title: "Status",
        editor: statusDropDownEditor,
        filterable: false,
        template: "#=(data.status)?'Enable':'Disable'#",
        width: 100
    }, {
        field: "password",
        title: "Password",
        template: "********",
        editor: function (container, options) {
            $('<input data-text-field="' + options.field + '" ' +
                'class="k-input k-textbox" ' +
                'type="password" ' +
                'data-value-field="' + options.field + '" ' +
                'data-bind="value:' + options.field + '"/>')
            .appendTo(container)
            },
            width: 100
        });
    if (accessibility == constant.FULL_ACCESS) {
        customerColumnList.push({
            field: "google2fa_enable",
            title: "2FA Status",
            editor: statusDropDownEditor,
            filterable: false,
            template: "#if(data.google2fa_enable == 1){# Enable #} else {# Disable #}#",
            width: 100
        });
    
        customerColumnList.push({
            command: [{
                text: "Reset",
                click: resetPasswordDetails
            }],
            title: "&nbsp;",
            width: "81px"
        });
    }
    var customerGrid = jQuery(constant.KENDO_GRID_ID).kendoGrid({
        dataSource: {
            serverPaging: true,
            pageSize: 20,
            transport: {
                read: {
                    url: constant.ACTION_URL + constant.KENDO_GRID_METHOD + "0",
                    dataType: "json",
                    type: "GET"
                }
            },
            schema: {
                total: 'total',
                data: 'customer_list',
                model: {
                    customer_id: constant.KENDO_GRID_PRIMARY_ID,
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
                            editable: (userId == constant.ADMIN_USER_ID)?true:false,
                            defaultValue: {
                                value: 1,
                                text: "Enable"
                            }
                        },
                        google2fa_enable: {
                            editable: true,
                            defaultValue: {
                                value: 1,
                                text: "Enable"
                            }
                        },
                        group_details: {
                            editable: (userId == constant.ADMIN_USER_ID)?true:false,
                        },
                        location_name: {
                            editable: false,
                        },
                        merchant_name: {
                            editable: (userId == constant.ADMIN_USER_ID)?true:false,
                        },
                        username: {
                            editable: false,
                        },
                        group_name: {
                            editable: true,
                        },
                        clear_password: {
                            editable: false,
                        },
                        fname: {
                            editable: true,
                        },
                        lname: {
                            editable: true,
                        },
                        email: { type: "email", validation: { required: false} },
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
        groupable: false,
        resizable: false,
        editable: "incell",
        selectable: 'row',
        dataBound: function(imageCategoryView) {
            var grid = $("#customerGrid").data("kendoGrid");
            var gridData = grid.dataSource.view();
            for (var i = 0; i < gridData.length; i++) {
                var currentUid = gridData[i].uid;
                if (gridData[i].customer_id == 0) {
                    var currenRow = grid.table.find("tr[data-uid='" + currentUid + "']");
                    var editButton = $(currenRow).find(".k-grid-Edit");
                    editButton.hide();
                }
            }
        },                
        save: onSave,
        filterable: {
            extra: false
        },
        columnMenu: true,
        columnMenuInit: onColumnMenuInit,
        columns: customerColumnList,
        edit: function(e) {
            var customerGridObject = $(constant.KENDO_GRID_ID).data("kendoGrid");
            var selectedItem = customerGridObject.dataItem(customerGridObject.select());
            if(selectedItem.user_id != userId){
                e.container.find("input[type='password']").attr("disabled","disabled");
            }
        }
    });

    function resetPasswordDetails(customerDetails) {
        customerDetails.preventDefault();
        var dataItem = this.dataItem($(customerDetails.currentTarget).closest("tr"));
        jQuery.ajax({
            type: 'POST',
            data: {
                _token: constant.TOKEN,
                user_id: dataItem.user_id,
            },
            url: constant.ACTION_URL + "/reset",
            success: function(customerData) {
                response = customerData.type;
                if (response.localeCompare("success") == 0) {
                    message = customerData.message;
                } else if (response.localeCompare("error") == 0) {
                    message = customerData.message;
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
                $(constant.KENDO_GRID_ID).data("kendoGrid").dataSource.read();
            }
        });
    }

    function onColumnMenuInit(customerDetails) {
        if (customerDetails.field == 'merchant_name') {
            var merchantTypeDetailsData = new kendo.data.DataSource({
                transport: {
                    read: {
                        dataType: "json",
                        url: constant.ACTION_URL + "/getAllMerchants",
                        type: "GET"
                    }
                }
            });
            customerDetails.container.find("div.k-filter-help-text").text("Select Merchant from the list:");
            customerDetails.container.find("span.k-dropdown:first").css("display", "none");
            var dropDownList = customerDetails.container.find(".k-textbox:first").removeClass("k-textbox").kendoDropDownList({
                dataSource: merchantTypeDetailsData,
                dataTextField: "merchant_name",
                dataValueField: "merchant_name",
                optionLabel: {
                    merchant_name: "Select Merchant",
                }
            }).data("kendoDropDownList");
        }
    };
    
    function showDetails(e) {
        e.preventDefault();
        $('.content .preloader').show();
        $('.content .preloader img').show();
        var tabstrip = $("#tabstrip").data("kendoTabStrip");
        var dataItem = this.dataItem($(e.currentTarget).closest("tr"));
        $.ajax({
            data: {
                _token: constant.TOKEN,
                identity_id: dataItem.identity_id,
                identity_table_id: dataItem.identity_table_id,
            },
            type: 'POST',
            dataType: 'json',
            url: constant.ACTION_URL + "/getLocationData",
            success: function(json) {
                $.each(json, function(jsonKey, jsonVal) {
                    $tabIndex = jsonKey + 1;
                    $parentId = "#tabstrip-" + $tabIndex;

                    // EXECUTE TRIGGER FOR ADD LOCATION TAB

                    $('#addLocation').trigger("click");

                    var postal_street_number = $("#street_number" + $tabIndex).data("kendoComboBox");
                    var postal_route = $("#route" + $tabIndex).data("kendoComboBox");
                    var postal_neighborhood = $("#neighborhood" + $tabIndex).data("kendoComboBox");
                    var postal_postcode = $("#postcode" + $tabIndex).data("kendoComboBox");

                    $($parentId + ' #country_label_'+$tabIndex).text(jsonVal.country_name);
                    $($parentId + ' #country_id_'+$tabIndex).val(jsonVal.country_id);
                    $($parentId + ' #state_label_'+$tabIndex).text(jsonVal.state_name);
                    $($parentId + ' #state_id_'+$tabIndex).val(jsonVal.state_id);
                    $($parentId + ' #county_label_'+$tabIndex).text(jsonVal.county_name);
                    $($parentId + ' #county_id_'+$tabIndex).val(jsonVal.county_id);
                    $($parentId + ' #city_label_'+$tabIndex).text(jsonVal.city_name);
                    $($parentId + ' #city_id_'+$tabIndex).val(jsonVal.city_id);


                    $($parentId + ' #subpremise').val(jsonVal.postal_subpremise);
                    $($parentId + ' #premise').val(jsonVal.postal_premise);
                    $($parentId + ' #list_id').val(jsonVal.list_id);
                    $($parentId + ' #postal_id').val(jsonVal.postal_id);

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
                        $($parentId + ' #lat').val(jsonVal.postal_lat);
                        $($parentId + ' #lng').val(jsonVal.postal_lng);
                        $($parentId + ' .postal').show();
                    }
                    if (jsonVal.postal_code_max) {
                        postal_postcode.text(jsonVal.postal_postcode);
                        $($parentId + ' .postal-max').show();
                    }
                });

                $("#identity_id").val(dataItem.identity_id);
                $("#identity_table_id").val(dataItem.identity_table_id);
                $('.content .preloader').hide();
                $('.content .preloader img').hide();
                $('#top_modal').modal("show");
            }
        });
    }

    function setPostalValues() {
        var tabstrip = $("#tabstrip").data("kendoTabStrip");
        var tabCounter = tabstrip.items().length;
        for (var i = 1; i < tabCounter; i++) {
            var psn = $("#street_number" + i).data("kendoComboBox");
            var pr = $("#route" + i).data("kendoComboBox");
            var pn = $("#neighborhood" + i).data("kendoComboBox");
            var pp = $("#postcode" + i).data("kendoComboBox");
            $("#postalStreetNumber" + i).val(psn.text());
            $("#postalRoute" + i).val(pr.text());
            $("#postalNeighborhood" + i).val(pn.text());
            $("#postalPostcode" + i).val(pp.text());
        }
    }

    function postalInitialize(parentId) {
        $("#street_number" + parentId).kendoComboBox({
            dataTextField: "text",
            dataValueField: "value",
            dataSource: [{
                text: "None",
                value: "-1"
            }]
        }).data("kendoComboBox");
        $("#route" + parentId).kendoComboBox({
            dataTextField: "text",
            dataValueField: "value",
            dataSource: [{
                text: "None",
                value: "-1"
            }]
        }).data("kendoComboBox");
        $("#neighborhood" + parentId).kendoComboBox({
            dataTextField: "text",
            dataValueField: "value",
            dataSource: [{
                text: "None",
                value: "-1"
            }]
        }).data("kendoComboBox");
        $("#postcode" + parentId).kendoComboBox({
            dataTextField: "text",
            dataValueField: "value",
            dataSource: [{
                text: "None",
                value: "-1"
            }]
        }).data("kendoComboBox");
    }

    function merchantNameComboBoxEditor(container, options) {
        var merchantTypeDetailsData = new kendo.data.DataSource({
            transport: {
                read: {
                    dataType: "json",
                    url: constant.ACTION_URL + "/getAllMerchants",
                    type: "GET"
                }
            }
        });
        var merchantTypeComboBox = $('<input data-text-field="merchant_name" data-value-field="merchant_name" data-bind="value:' + options.field + '"/>').appendTo(container).kendoComboBox({
            filter: "contains",
            dataSource: merchantTypeDetailsData,
            placeholder: "Select Merchant",
            dataTextField: "merchant_name",
            dataValueField: "merchant_id",
        });
    }

    function locationNameComboBoxEditor(container, options) {
        var identity_id = options.model.identity_id;
        var locationDetailsData = new kendo.data.DataSource({
            transport: {
                read: {
                    dataType: "json",
                    data: {
                        identity_id: identity_id,
                        identity_table_id: identity_table_id,
                        _token: constant.TOKEN
                    },
                    url: constant.ACTION_URL + "/getIdentityCityList",
                    type: "POST"
                }
            }
        });
        var locationComboBox = $('<input data-text-field="city_name" data-value-field="city_name" data-bind="value:' + options.field + '"/>').appendTo(container).kendoComboBox({
            filter: "contains",
            dataSource: locationDetailsData,
            placeholder: "Select Location",
            dataTextField: "city_name",
            dataValueField: "city_name",
        });
    }

    function customerGroupMultiSelect(container, options) {
       $("<select id = 'customerFieldBoxId' data-text-field='group_name' data-value-field='group_id' data-bind='value:group_details' />").appendTo(container).kendoMultiSelect({
           autoBind: true,
           dataSource: {
               transport: {
                   read: {
                       dataType: 'json',
                       url: constant.ACTION_URL + "/getCustomerGroup",
                       type: "GET"
                   }
               }
           },
           change: onChange,
       });
    }

    function onChange(e) {
        var multiselect = $("#customerFieldBoxId").data("kendoMultiSelect");
        var dataItem = multiselect.dataItems();
        var selectedData = multiselect.value();
        var customerGridObject = $(constant.KENDO_GRID_ID).data("kendoGrid");
        var selectedItem = customerGridObject.dataItem(customerGridObject.select());
        var customer_id = selectedItem.customer_id;
        var merchant_id = selectedItem.merchant_id;
        var identity_id = selectedItem.identity_id;

        $.ajax({
            type: 'POST',
            data: {
                merchant_id: merchant_id,
                customer_id: customer_id,
                identity_id: identity_id,
                key: "group_name",
                value: selectedData,
                _token: constant.TOKEN
            },
            url: constant.ACTION_URL + constant.UPDATE_LIST_METHOD,
            success: function(customerDeatils) {
                response = customerDeatils.type;
                if (response.localeCompare("success") == 0) {
                    message = customerDeatils.message;
                } else if (response.localeCompare("error") == 0) {
                    message = customerDeatils.message;
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
                $(constant.KENDO_GRID_ID).data("kendoGrid").dataSource.read();
            }
        });
    }

    function customerGroupDisplay(customerData) {
        if(customerData.customer_id > 0){
            rolesData = customerData.group_details;
            var res = [];
            if(rolesData.length > 0){
                $.each(rolesData, function (id, record) {
                    
                    if(record.hasOwnProperty('group_id'))
                    {
                        res.push(record.group_name);                    
                    }                
                });
                return res.join(", ");
            }else{
                return "None";
            }
        }else{
            return "None";
        }        
    }

    function statusDropDownEditor(container, options) {
        var data = [{
            Description: "Enable",
            ID: "1"
        }, {
            Description: "Disable",
            ID: "0"
        }];
        $('<input data-text-field="Description" data-value-field="ID" data-bind="value:' + options.field + '"/>').appendTo(container).kendoDropDownList({
            dataSource: data,
            dataTextField: "Description",
            dataValueField: "ID"
        });
    };
    function newsletterDropDownEditor(container, options) {
        var data = [{
            Description: "subscribe",
            ID: "1"
        }, {
            Description: "Un-subscribe",
            ID: "0"
        }];
        $('<input data-text-field="Description" data-value-field="ID" data-bind="value:' + options.field + '"/>').appendTo(container).kendoDropDownList({
            dataSource: data,
            dataTextField: "Description",
            dataValueField: "ID"
        });
    };

    $("#location_country").on('change', function() {
        var countryId = $(this).val();        
        $('#location_state').html("");
        $('#location_county').html("");
        $('#location_city').html("");
        $('#addLocation').prop('disabled',true);
        $('.modal .preloader').show();
        $('.modal img').show();
        $.ajax({
            type:'POST',
            data:{_token:constant.TOKEN,country_id:countryId},
            dataType:"json",
            url : constant.ACTION_URL+"/getStates",
            error:function(xhr,status,error) {
                $('.modal .preloader,.modal img').hide();
            },
            success:function(locationState,status,xhr) {
                $('#location_state').append("<option></option>");
                $.each(locationState, function(i,value) {
                    $('#location_state').append("<option value='"+value['state_id']+"' data-name='"+value['state_name']+"'>"+value['state_name']+"</option>");
                });
                $('.modal .preloader,.modal img').hide();
            }
        });        
    });

    $("#location_state").on('change', function() {
        var stateId = $(this).val();
        $('#location_county').html("");
        $('#location_city').html("");
        $('#addLocation').prop('disabled',true);
        $('.modal .preloader').show();
        $('.modal img').show();
        $.ajax({
            type:'POST',
            data:{_token:constant.TOKEN,state_id:stateId},
            dataType:"json",
            url : constant.ACTION_URL+"/getCounties",
            error:function(xhr,status,error) {
                $('.modal .preloader,.modal img').hide();
            },
            success:function(locationCounty,status,xhr) {
                $('#location_county').append("<option></option>");
                $.each(locationCounty, function(i,value) {
                    $('#location_county').append("<option value='"+value['county_id']+"' data-name='"+value['county_name']+"'>"+value['county_name']+"</option>");
                });
                $('.modal .preloader,.modal img').hide();                
            }
        });
    });

    $("#location_county").on('change', function() {
        var countyId = $(this).val();
        $('#location_city').html("");
        $('#addLocation').prop('disabled',true);
        $('.modal .preloader').show();
        $('.modal img').show();        
        $.ajax({
            type:'POST',
            data:{_token:constant.TOKEN,county_id:countyId},
            dataType:"json",
            url : constant.ACTION_URL+"/getCities",
            error:function(xhr,status,error) {
                $('.modal .preloader,.modal img').hide();
            },
            success:function(locationCity,status,xhr) {
                $('#location_city').append("<option></option>");
                $.each(locationCity, function(i,value) {
                    $('#location_city').append("<option value='"+value['city_id']+"' data-name='"+value['city_name']+"'>"+value['city_name']+"</option>");
                }); 
                $('.modal .preloader,.modal img').hide();               
            }
        });
    });

    $("#location_city").on('change', function() {
        $('#addLocation').prop('disabled',false);
    });

    function addLocation(){
        $('#addLocation').click(function() {

            var tabstrip = $("#tabstrip").data("kendoTabStrip");
            var tabCounter = tabstrip.items().length;

            var country_id = $('#location_country').val();
            var country_name = $('#location_country').find(":selected").data('name');
            var state_id = $('#location_state').val();
            var state_name = $('#location_state').find(":selected").data('name');
            var county_id = $('#location_county').val();
            var county_name = $('#location_county').find(":selected").data('name');
            var city_id = $('#location_city').val();
            var city_name = $('#location_city').find(":selected").data('name');


            var tabTitle = 'Postal ' + (tabCounter) + ' <span data-type="remove" class="k-icon k-i-close"></span>';
            $postalTab = $("#demo .tab-content").clone();
            $postalTab.find('#subpremise').attr("name", "postals[" + (tabCounter) + "][subpremise]");
            $postalTab.find('#premise').attr("name", "postals[" + (tabCounter) + "][premise]");
            $postalTab.find('#street_number').attr("id", "street_number" + (tabCounter));
            $postalTab.find('#postalStreetNumber').attr("name", "postals[" + (tabCounter) + "][street_number]").attr("id", "postalStreetNumber" + (tabCounter));
            $postalTab.find('#route').attr("id", "route" + (tabCounter));
            $postalTab.find('#postalRoute').attr("name", "postals[" + (tabCounter) + "][route]").attr("id", "postalRoute" + (tabCounter));
            $postalTab.find('#neighborhood').attr("id", "neighborhood" + (tabCounter));
            $postalTab.find('#postalNeighborhood').attr("name", "postals[" + (tabCounter) + "][neighborhood]").attr("id", "postalNeighborhood" + (tabCounter));
            $postalTab.find('#postcode').attr("id", "postcode" + (tabCounter));
            $postalTab.find('#postalPostcode').attr("name", "postals[" + (tabCounter) + "][postcode]").attr("id", "postalPostcode" + (tabCounter));
            $postalTab.find('#lat').attr("name", "postals[" + (tabCounter) + "][lat]");
            $postalTab.find('#lng').attr("name", "postals[" + (tabCounter) + "][lng]");
            $postalTab.find('#list_id').attr("name", "postals[" + (tabCounter) + "][list_id]").val(0);
            $postalTab.find('#postal_id').attr("name", "postals[" + (tabCounter) + "][postal_id]").val(0);
            $postalTab.find('#country_label').attr("id", "country_label_"+ (tabCounter)).html(country_name);
            $postalTab.find('#country_id').attr("id", "country_id_"+ (tabCounter)).attr("name","postals[" + (tabCounter) + "][country_id]").val(country_id);
            $postalTab.find('#state_label').attr("id", "state_label_"+ (tabCounter)).html(state_name);
            $postalTab.find('#state_id').attr("id", "state_id_"+ (tabCounter)).attr("name","postals[" + (tabCounter) + "][state_id]").val(state_id);
            $postalTab.find('#county_label').attr("id", "county_label_"+ (tabCounter)).html(county_name);
            $postalTab.find('#county_id').attr("id", "county_id_"+ (tabCounter)).attr("name","postals[" + (tabCounter) + "][county_id]").val(county_id);
            $postalTab.find('#city_label').attr("id", "city_label_"+ (tabCounter)).html(city_name);
            $postalTab.find('#city_id').attr("id", "city_id_"+ (tabCounter)).attr("name","postals[" + (tabCounter) + "][city_id]").val(city_id);


            $postalTab.find('.postal').hide();
            $postalTab.find('.postal-max').hide();

            tabstrip.insertBefore({
                text: tabTitle,
                encoded: false,
                content: $postalTab.html()
            }, tabstrip.tabGroup.children("li:last"));
            tabstrip.select(tabCounter - 1);   
            postalInitialize(tabCounter);

            // Init Get Location Functionality  
            getLocation();

        });

        // Initialize Remove Tab From TabStripe
        var tabstrip = $("#tabstrip").data("kendoTabStrip");
        tabstrip.tabGroup.on("click", "[data-type='remove']", function(e) {
            e.preventDefault();
            e.stopPropagation();
            var item = $(e.target).closest(".k-item");
            tabstrip.remove(item.index());
            tabstrip.select(item.index() - 1);
        });  
    }

    function getLocation(){
        $('.getLocation').on("click",function() {            
            var tabstrip = $("#tabstrip").data("kendoTabStrip");
            $parentTab = $(this).parents('.k-content');
            $parentId = "#" + $parentTab.attr("id");
            $tabIndex = tabstrip.select().index() + 1;            

            var postal_street_number = $("#street_number" + $tabIndex).data("kendoComboBox");
            var postal_route = $("#route" + $tabIndex).data("kendoComboBox");
            var postal_neighborhood = $("#neighborhood" + $tabIndex).data("kendoComboBox");
            var postal_postcode = $("#postcode" + $tabIndex).data("kendoComboBox");
            var postalMax = parseInt($('#postal_code_max').val());
            if (locationValidator.validate()) {
                $('.modal .preloader').show();
                $('.modal img').show();
                $.ajax({
                    dataType: 'json',
                    type: 'POST',
                    data: {
                        _token: constant.TOKEN,
                        city_name: $('#city_label_'+$tabIndex).text(),
                        postal_subpremise: $($parentId + " #subpremise").val(),
                        postal_premise: $($parentId + " #premise").val()
                    },
                    url: constant.ACTION_URL + "/getPostalAddress",
                    success: function(json) {
                        var street_number = [{
                            text: "None",
                            value: -1
                        }];
                        var route = [{
                            text: "None",
                            value: -1
                        }];
                        var neighborhood = [{
                            text: "None",
                            value: -1
                        }];
                        var postal_code = [{
                            text: "None",
                            value: -1
                        }];
                        $.each(json.street_number, function(skey, svalue) {
                            street_number.push({
                                text: svalue,
                                value: skey
                            });
                        });
                        $.each(json.route, function(rkey, rvalue) {
                            route.push({
                                text: rvalue,
                                value: rkey
                            });
                        });
                        $.each(json.neighborhood, function(nkey, nvalue) {
                            neighborhood.push({
                                text: nvalue,
                                value: nkey
                            });
                        });
                        if (postalMax) {
                            $.each(json.postal_code, function(pkey, pvalue) {
                                postal_code.push({
                                    text: pvalue,
                                    value: pkey
                                });
                            });
                            $($parentId + ' .postal-max').show();
                        }
                        postal_street_number.setDataSource(street_number);
                        postal_street_number.value(0);
                        postal_route.setDataSource(route);
                        postal_route.value(0);
                        postal_neighborhood.setDataSource(neighborhood);
                        postal_neighborhood.value(0);
                        postal_postcode.setDataSource(postal_code);
                        postal_postcode.value(-1);
                        $($parentId + ' #lat').val(json.lat);
                        $($parentId + ' #lng').val(json.lng);
                        $($parentId + ' .postal').show();
                        $('.modal .preloader,.modal img').hide();
                    }
                });
            } else {
                return false;
            }
        });
    }

    function updateLocation(){
        $('#updateLocation').on("click",function() {
            if (locationValidator.validate()) {
                setPostalValues();
                $('.modal .preloader').show();
                $('.modal img').show();
                $.ajax({
                    type: 'POST',
                    data: $("#locationForm").serialize(),
                    url: constant.ACTION_URL + "/updateLocation",
                    success: function(json) {
                        $("#locationForm").trigger('reset');
                        $('#top_modal').modal("hide");
                        $('.postal').hide();
                        $('.postal-max').hide();
                        $('.modal .preloader').hide();
                        $('.modal img').hide();
                    }
                });
            } else {
                return false;
            }
        });
    }

    $(document).on('hidden.bs.modal','#top_modal', function(){
        $("#top_modal").html(topmodal);
        $("#tabstrip").kendoTabStrip().data("kendoTabStrip");

        $(".select21").select2({
            theme: "bootstrap",
            placeholder: "Please Select Option",
            width: '100%'
        });

        addLocation();
        getLocation();
        updateLocation();
    });

    // CALL FUNCTIONS FOR INITIALLY ASSIGN EVENTS

    addLocation();
    getLocation();
    updateLocation();
});