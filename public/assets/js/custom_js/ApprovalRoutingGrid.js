$(document).ready(function() {
    var token = $('input[name="_token"]').val();
    var requestUrl = $("#requestUrl").val();

    var approvalRoutingGrid = jQuery("#approvalRoutingGrid").kendoGrid({
        dataSource: {
            serverPaging: true,
            pageSize: 20,
            transport : {   
                read : {
                    url: requestUrl+"/getApprovalGropLists",
                    dataType: "json",
                    type: "GET"
                },
                update: {
                    data:{_token:token},
                    url: requestUrl + "/updateApprovalGropLists",
                    dataType: "json",
                    type: "POST",
                    complete:function(data){
                        approvalRoutingGrid.dataSource.read();
                    }
                },
                destroy: {
                    data:{_token:token},
                    url: requestUrl + "/deleteApprovalGropLists",
                    dataType: "json",
                    type: "POST",
                    complete:function(data){
                        approvalRoutingGrid.dataSource.read();
                    }
                },
                create: {
                    data:{_token:token},
                    url: requestUrl + "/createApprovalGropLists",
                    dataType: "json",
                    type: "POST",
                    complete:function(data){
                        approvalRoutingGrid.dataSource.read();
                    }
                },
            },
            schema: {
                total:'total',
                data:'approval_group_list',  
                model: {
                    id:'staff_group_list_id',
                    fields: {
                        merchant_id: { validation: { required: true }},
                        category_id: { validation: { required: true }},
                        source_staff_group_id: { validation: { required: true }},
                        source_approval_status_id: { validation: { required: true }},
                        target_staff_group_id: { validation: { required: true }},
                        target_approval_status_id: { validation: { required: true }},
                    }
                },
            },
            serverFiltering: true,
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
        serverFiltering: true,
        groupable: true,
        resizable: true,
        editable: 'inline',
        toolbar: ["create"],
        columns: [
        
        {
            command: [
                { name: "edit", text: { edit: " ", update: " ", cancel: " " } },
                { name: "destroy", text: " " }
                ],
            title: "&nbsp;",
            width: "90px"
        },{
            field: "staff_group_list_id",
            title: "Staff Group List",
            hidden:true,
        },{
            field: "merchant_id",
            title: "Merchant",
            editor: mercahntList,
            template: "#=data.merchant_name#"
        },{
            field: "category_id",
            title: "Category Type",
            editor: categoryList,
            template: "#=data.category_name#"
        },{
            field: "source_staff_group_id",
            title: "Source Group",
            editor: sourceStaffGroupId,
            template: "#=data.source_group_name#"
        }, {
            field: "source_approval_status_id",
            title: "Source Status",
            editor: sourceApprovalStatusId,
            template: "#=data.source_status_name#"
        }, {
            field: "target_staff_group_id",
            title: "Target Group",
            editor: targetStaffGroupId,
            template: "#=data.target_group_name#"
        }, {
            field: "target_approval_status_id",
            title: "Target Sattus",
            editor: targetApprovalStatusId,
            template: "#=data.target_status_name#"
        }],
    });

    var approvalRoutingGrid = $("#approvalRoutingGrid").data("kendoGrid");
    var merchantEditable = jQuery("#merchantEditable").val();
    if(merchantEditable == "disable")
    {
        approvalRoutingGrid.hideColumn('merchant_id');
    }
    function mercahntList(container, options) {

        $('<input data-text-field="merchant_name" data-value-field="merchant_id" data-bind="value:' + options.field + '"/>')
            .appendTo(container)
            .kendoDropDownList({
                dataSource: {
                    transport : {   
                        read : {
                            dataType : "json",
                            url : requestUrl+"/getMerchantList",
                            type : "GET"
                        }
                    }
                },
                dataBound: function() {
                    if (this.select() === -1) { //check whether any item is selected
                        this.select(0);
                        this.trigger("change");
                    }
                },
                filter: "contains",
                dataTextField: "merchant_name",
                dataValueField: "merchant_id"
            });
    }

    function categoryList(container, options) {

        $('<input data-text-field="category_name" data-value-field="category_id" data-bind="value:' + options.field + '"/>')
            .appendTo(container)
            .kendoDropDownList({
                dataSource: {
                    transport : {   
                        read : {
                            dataType : "json",
                            url : requestUrl+"/getCategoryList",
                            type : "GET"
                        }
                    }
                },
                dataBound: function() {
                    if (this.select() === -1) { //check whether any item is selected
                        this.select(0);
                        this.trigger("change");
                    }
                },
                filter: "contains",
                dataTextField: "category_name",
                dataValueField: "category_id"
            });
    }

    function sourceStaffGroupId(container, options) {

        $('<input data-text-field="source_group_name" data-value-field="source_staff_group_id" data-bind="value:' + options.field + '"/>')
            .appendTo(container)
            .kendoDropDownList({
                dataSource: {
                    transport : {   
                        read : {
                            dataType : "json",
                            url : requestUrl+"/getStaffGroup?group=source",
                            type : "GET"
                        }
                    }
                },
                dataBound: function() {
                    if (this.select() === -1) { //check whether any item is selected
                        this.select(0);
                        this.trigger("change");
                    }
                },
                filter: "contains",
                dataTextField: "source_group_name",
                dataValueField: "source_staff_group_id"
            });
    }

    function targetStaffGroupId(container, options) {

        $('<input data-text-field="target_group_name" data-value-field="target_staff_group_id" data-bind="value:' + options.field + '"/>')
            .appendTo(container)
            .kendoDropDownList({
                dataSource: {
                    transport : {   
                        read : {
                            dataType : "json",
                            url : requestUrl+"/getStaffGroup?group=target",
                            type : "GET"
                        }
                    }
                },
                dataBound: function() {
                    if (this.select() === -1) { //check whether any item is selected
                        this.select(0);
                        this.trigger("change");
                    }
                },
                filter: "contains",
                dataTextField: "target_group_name",
                dataValueField: "target_staff_group_id"
            });
    }

    function sourceApprovalStatusId(container, options) {

        $('<input data-text-field="source_status_name" data-value-field="source_approval_status_id" data-bind="value:' + options.field + '"/>')
            .appendTo(container)
            .kendoDropDownList({
                dataSource: {
                    transport : {   
                        read : {
                            dataType : "json",
                            url : requestUrl+"/getApprovalStatus?status=source",
                            type : "GET"
                        }
                    }
                },
                dataBound: function() {
                    if (this.select() === -1) { //check whether any item is selected
                        this.select(0);
                        this.trigger("change");
                    }
                },
                filter: "contains",
                dataTextField: "source_status_name",
                dataValueField: "source_approval_status_id"
            });
    }

    function targetApprovalStatusId(container, options) {

        $('<input data-text-field="target_status_name" data-value-field="target_approval_status_id" data-bind="value:' + options.field + '"/>')
            .appendTo(container)
            .kendoDropDownList({
                dataSource: {
                    transport : {   
                        read : {
                            dataType : "json",
                            url : requestUrl+"/getApprovalStatus?status=target",
                            type : "GET"
                        }
                    }
                },
                dataBound: function() {
                    if (this.select() === -1) { //check whether any item is selected
                        this.select(0);
                        this.trigger("change");
                    }
                },
                filter: "contains",
                dataTextField: "target_status_name",
                dataValueField: "target_approval_status_id"
            });
    }
});