var constant = {
    ACTION_URL: $('#request_url').val(),
    TOKEN: $('input[name="_token"]').val(),
    KENDO_GRID_ID: "#staffGrid",
    KENDO_GRID_METHOD: "/getStaffList/",
    KENDO_GRID_PRIMARY_ID: "customer_id",
    UPDATE_LIST_METHOD: "/updateStaffDetails",
    ADMIN_USER_ID:1,
    FULL_ACCESS:4
}
$(document).ready(function() {

    var staffColumnList = [];
    var userId = localStorage.getItem('userId');
    var accessibility = localStorage.getItem('accessibility');

    staffColumnList.push({
        field: "staff_id",
        title: "Id",
        width: 50,
    }, {
        field: "staff_fname",
        title: "First Name",
        template: "#=(data.staff_fname)?data.staff_fname:'None'#",
        width: 100
    }, {
        field: "staff_lname",
        title: "Last Name",
        template: "#=(data.staff_lname)?data.staff_lname:'None'#",
        width: 100
    }, {
        field: "merchant_id",
        title: "Merchant#",
        hidden: true,
    }, {
        field: "merchant_name",
        title: "Merchant",
        editor: merchantNameComboBoxEditor,
        template: "#=(data.merchant_name)?data.merchant_name:'None'#",
        width: 140
    }, {
        field: "identity_name",
        title: "Name",
        width: 100
    }, {
        field: "identity_email",
        title: "Email",
        template: "#=(data.identity_email)?data.identity_email:'None'#",
        width: 150
    }, {
        field: "username",
        title: "User Name",
        width: 100
    }, {
        field: "group_details",
        title: "Roles",
        editor: staffGroupMultiSelect,
        template: staffGroupDisplay,
        width: 130
    }, {
        field: "city_name",
        title: "City",
        editor: cityNameEditor,
        template: "#=(data.city_name)?data.city_name:'None'#",
        width: 120
    }, {
        field: "location_name",
        title: "Location",
        editor: LocationEditor,
        template: "#=(data.location_name)?data.location_name:'None'#",
        width: 120
    }, {
        field: "clear_password",
        title: "Clear Password",
        width: 110
    }, {
        field: "staff_status",
        title: "Status",
        editor: statusDropDownEditor,
        template: "#=(data.staff_status)?'Enable':'Disable'#",
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
        staffColumnList.push({
            field: "google2fa_enable",
            editor: statusDropDownEditor,
            title: "2FA Status",
            template: "#if(data.google2fa_enable == 1){# Enable #} else {# Disable #}#",
            width: 100
        }, {
        command: [{
            text: "Reset",
            click: resetPasswordDetails
        }],
        title: "&nbsp;",
        width: "81px"
    });
    }
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
                data: 'staff_list',
                model: {
                    customer_id: constant.KENDO_GRID_PRIMARY_ID,
                    fields: {                        
                        staff_status: {
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
                        staff_id: {
                            editable: false,
                        },
                        merchant_name: {
                            editable: (userId == constant.ADMIN_USER_ID)?true:false,
                        },
                        username: {
                            editable: false,
                        },
                        clear_password: {
                            editable: false,
                        },
                        identity_email: { type: "email", validation: { required: false} },
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
        dataBound: function(imageCategoryView) {
            var dataSource = this.dataSource;
            var grid = $("#staffGrid").data("kendoGrid");
            var gridData = grid.dataSource.view();
            for (var i = 0; i < gridData.length; i++) {
                var currentUid = gridData[i].uid;                
                if (gridData[i].staff_id == 0) {
                    var currenRow = grid.table.find("tr[data-uid='" + currentUid + "']");
                    var editButton = $(currenRow).find(".k-grid-Reset");
                    editButton.hide();
                }                
            }
        },
        editable: "incell",
        selectable: 'row',
        columns: staffColumnList,
        edit: function(e) {
            var staffGridObject = $(constant.KENDO_GRID_ID).data("kendoGrid");
            var selectedItem = staffGridObject.dataItem(staffGridObject.select());
            if(selectedItem.user_id != userId){
                e.container.find("input[type='password']").attr("disabled","disabled");
            }
            
        },
        save: function(staffData) {
            var staffGridObject = $(constant.KENDO_GRID_ID).data("kendoGrid");
            var selectedItem = staffGridObject.dataItem(staffGridObject.select());
            var staff_id = selectedItem.staff_id;
            var merchant_id = selectedItem.merchant_id;
            var identity_id = selectedItem.identity_id;
            var key = "";
            var value = 0;
            if (staffData.values.merchant_name) {
                key = "merchant_name";
                if (staffData.values.merchant_name.merchant_name) {
                    value = staffData.values.merchant_name.merchant_name;
                } else {
                    value = staffData.values.merchant_name;
                }
            }
            if (staffData.values.city_name) {
                key = "city_name";
                if (staffData.values.city_name.city_name) {
                    value = staffData.values.city_name.city_name;
                } else {
                    value = staffData.values.city_name;
                }
            }
            if (staffData.values.location_name) {
                key = "location_name";
                if (staffData.values.location_name.location_name) {
                    value = staffData.values.location_name.location_name;
                } else {
                    value = staffData.values.location_name;
                }
            }
            if (staffData.values.group_name) {
                key = "group_name";
                if (staffData.values.group_name[0].group_name) {
                    value = staffData.values.group_name[0].group_name;
                } else {
                    value = staffData.values.group_name;
                }
            }
            if (staffData.values.username) {
                key = "username";
                value = staffData.values.username;
            }
            if (staffData.values.staff_lname) {
                key = "staff_lname";
                value = staffData.values.staff_lname;
            }
            if (staffData.values.staff_fname) {
                key = "staff_fname";
                value = staffData.values.staff_fname;
            }
            if (staffData.values.identity_email) {
                key = "identity_email";
                value = staffData.values.identity_email;
            }
            if (staffData.values.identity_name) {
                key = "identity_name";
                value = staffData.values.identity_name;
            }
            if (staffData.values.staff_status) {
                key = "staff_status";
                value = staffData.values.staff_status;
            }
            if (staffData.values.google2fa_enable) {
                key = "google2fa_enable";
                value = staffData.values.google2fa_enable;
            }if (staffData.values.password) {
                key = "password";
                value = staffData.values.password;
            }

            $.ajax({
                type: 'POST',
                data: {
                    merchant_id: merchant_id,
                    staff_id: staff_id,
                    identity_id: identity_id,
                    key: key,
                    value: value,
                    _token: constant.TOKEN
                },
                url: constant.ACTION_URL + constant.UPDATE_LIST_METHOD,
                success: function(staffDeatils) {
                    response = staffDeatils.type;
                    if (response.localeCompare("success") == 0) {
                        message = staffDeatils.message;
                    } else if (response.localeCompare("error") == 0) {
                        message = staffDeatils.message;
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
    });
    function LocationEditor(container, options){
        if (options.model.merchant_id) {
            var merchant_id = options.model.merchant_id;
        }
        var locationDetailsData = new kendo.data.DataSource({
            transport: {
                read: {
                    dataType: "json",
                    type: 'POST',
                    data: {
                       _token: constant.TOKEN,
                        merchant_id: merchant_id,
                        staff_id:options.model.staff_id,
                        city_name:options.model.city_name
                    },
                    url: constant.ACTION_URL + "/locationDetailsList",
                }
            }
        });
        var merchantTypeComboBox = $('<input data-text-field="location_name" data-value-field="location_name" data-bind="value:' + options.field + '"/>').appendTo(container).kendoDropDownList({
            dataSource: locationDetailsData,
            dataTextField: "location_name",
            dataValueField: "location_name",
            optionLabel: {
                    location_name: "Select Location",
            }
        });

    }
    function cityNameEditor(container, options){
         if (options.model.merchant_id) {
            var merchant_id = options.model.merchant_id;
        }
        var cityNameDetailsData = new kendo.data.DataSource({
            transport: {
                read: {
                    dataType: "json",
                    type: 'POST',
                    data: {
                       _token: constant.TOKEN,
                        merchant_id: merchant_id
                    },
                    url: constant.ACTION_URL + "/cityDetailsList",
                }
            }
        });
        var merchantTypeComboBox = $('<input data-text-field="city_name" data-value-field="city_name" data-bind="value:' + options.field + '"/>').appendTo(container).kendoDropDownList({
            dataSource: cityNameDetailsData,
            dataTextField: "city_name",
            dataValueField: "city_name",
            optionLabel: {
                    city_name: "Select City",
            }
        });
    }
    function merchantNameComboBoxEditor(container, options) {
        var merchantTypeDetailsData = new kendo.data.DataSource({
            transport: {
                read: {
                    dataType: "json",
                    url: "hase_customer/getMerchants",
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

    function staffGroupMultiSelect(container, options) {
       $("<select id = 'staffFieldBoxId' data-text-field='group_name' data-value-field='group_id' data-bind='value:group_details' />").appendTo(container).kendoMultiSelect({
           autoBind: true,
           dataSource: {
               transport: {
                   read: {
                       dataType: 'json',
                       url: constant.ACTION_URL + "/getStaffGroup",
                       type: 'POST',
                       data: {
                           _token: constant.TOKEN,
                       }
                   }
               }
           },
           change: onChange,
       });
    }

    function onChange(e) {
        var multiselect = $("#staffFieldBoxId").data("kendoMultiSelect");
        var dataItem = multiselect.dataItems();
        var selectedData = multiselect.value();
        var staffGridObject = $(constant.KENDO_GRID_ID).data("kendoGrid");
        var selectedItem = staffGridObject.dataItem(staffGridObject.select());
        var staff_id = selectedItem.staff_id;
        var merchant_id = selectedItem.merchant_id;
        var identity_id = selectedItem.identity_id;

        $.ajax({
            type: 'POST',
            data: {
                merchant_id: merchant_id,
                staff_id: staff_id,
                identity_id: identity_id,
                key: "group_name",
                value: selectedData,
                _token: constant.TOKEN
            },
            url: constant.ACTION_URL + constant.UPDATE_LIST_METHOD,
            success: function(staffDeatils) {
                response = staffDeatils.type;
                if (response.localeCompare("success") == 0) {
                    message = staffDeatils.message;
                } else if (response.localeCompare("error") == 0) {
                    message = staffDeatils.message;
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

    function staffGroupDisplay(staffData) {
        if(staffData.staff_id > 0){
            rolesData = staffData.group_details;
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
    }
});