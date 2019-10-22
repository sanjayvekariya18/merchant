$(document).ready(function() {
    var token = $('input[name="_token"]').val();
    var requestUrl = $("#requestUrl").val();

    var translationManageGrid = jQuery("#translationManageGrid").kendoGrid({
        dataSource: {
            serverPaging: true,
            pageSize: 20,
            transport : {   
                read : {
                    url: requestUrl+"/getTranslationManageLists",
                    dataType: "json",
                    type: "GET"
                },
                update: {
                    data:{_token:token},
                    url: requestUrl + "/updateTranslationManageLists",
                    dataType: "json",
                    type: "POST",
                    complete:function(data){
                        translationManageGrid.dataSource.read();
                    }
                },
                destroy: {
                    data:{_token:token},
                    url: requestUrl + "/deleteTranslationManageLists",
                    dataType: "json",
                    type: "POST",
                    complete:function(data){
                        translationManageGrid.dataSource.read();
                    }
                },
                create: {
                    data:{_token:token},
                    url: requestUrl + "/createTranslationManageLists",
                    dataType: "json",
                    type: "POST",
                    complete:function(data){
                        translationManageGrid.dataSource.read();
                    }
                },
            },
            schema: {
                model: {
                    id:'manage_id',
                    fields: {
                        manage_table: { validation: { required: true }},
                        manage_table_id: { editable: false, nullable: true },
                        source_group_name: { validation: { required: true }},
                        target_group_name: { validation: { required: true }},
                        source_status_name: { validation: { required: true }},
                        translator_user_id: { validation: { required: true }},
                        approval_user_id: { validation: { required: true }},
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
            field: "manage_table",
            title: "Manage Table",
            width: "200px",
            editor: manageTableList,
        }, {
            field: "manage_table_id",
            title: "Manage Table Id",
            editable:false
        },{
            field: "source_group_name",
            title: "Language Source",
            editor: sourceStaffGroupId,
        }, {
            field: "target_group_name",
            title: "Language Target",
            editor: sourceStaffGroupId,
        }, {
            field: "source_status_name",
            title: "Language Status",
           editor: targetApprovalStatusId,
        }, {
            field: "translator_user_id",
            title: "Translator User Id",
            editor: usersList,
        }, {
            field: "approval_user_id",
            title: "Approval User Id",
            editor: usersList,
        }],
    });

    var translationManageGrid = $("#translationManageGrid").data("kendoGrid");
    var merchantEditable = jQuery("#merchantEditable").val();
    if(merchantEditable == "disable")
    {
        translationManageGrid.hideColumn('merchant_id');
    }
    function manageTableList(container, options) {

        $('<input data-text-field="key_table" data-value-field="key_id" data-bind="value:' + options.field + '"/>')
            .appendTo(container)
            .kendoDropDownList({
                dataSource: {
                    transport : {   
                        read : {
                            dataType : "json",
                            url : requestUrl+"/getManageTableList",
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
                dataTextField: "key_table",
                dataValueField: "key_id"
            });
    }

    function sourceStaffGroupId(container, options) {

        $('<input data-text-field="person_name" data-value-field="person_id" data-bind="value:' + options.field + '"/>')
            .appendTo(container)
            .kendoDropDownList({
                dataSource: {
                    transport : {   
                        read : {
                            dataType : "json",
                            url : requestUrl+"/getStaffGroup",
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
                dataTextField: "person_name",
                dataValueField: "person_id"
            });
    }

    function usersList(container, options) {

        $('<input data-text-field="username" data-value-field="user_id" data-bind="value:' + options.field + '"/>')
            .appendTo(container)
            .kendoDropDownList({
                dataSource: {
                    transport : {   
                        read : {
                            dataType : "json",
                            url : requestUrl+"/usersList",
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
                dataTextField: "username",
                dataValueField: "user_id"
            });
    }
   
    function targetApprovalStatusId(container, options) {

        $('<input data-text-field="approval_status_name" data-value-field="approval_status_id" data-bind="value:' + options.field + '"/>')
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
                dataTextField: "approval_status_name",
                dataValueField: "approval_status_id"
            });
    }
});