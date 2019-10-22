$(document).ready(function() {
    var token = $('input[name="_token"]').val();
    var requestUrl = $("#requestUrl").val();

    var translationManageGrid = jQuery("#translationManageGrid").kendoGrid({
        dataSource: {
            serverPaging: true,
            pageSize: 20,
            transport : {   
                read : {
                    url: requestUrl+"/getUserStatusManageLists",
                    dataType: "json",
                    type: "GET"
                },
                update: {
                    data:{_token:token},
                    url: requestUrl + "/updateUserStatusManageLists",
                    dataType: "json",
                    type: "POST",
                    complete:function(data){
                        translationManageGrid.dataSource.read();
                    }
                },
                destroy: {
                    data:{_token:token},
                    url: requestUrl + "/deleteUserStatusManageLists",
                    dataType: "json",
                    type: "POST",
                    complete:function(data){
                        translationManageGrid.dataSource.read();
                    }
                },
                create: {
                    data:{_token:token},
                    url: requestUrl + "/createUserStatusManageLists",
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
                        status_view_name: { validation: { required: true }},
                        status_view_group_name: { validation: { required: true }},
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
            field: "status_view_group_name",
            title: "Status Target",
            width: "200px",
            editor: sourceStaffGroupId,
        }, {
            field: "status_view_name",
            title: "User View Status",
            editor :targetApprovalStatusId
        },{
            field: "manage_table",
            title: "Translation View table",
            editor:manageTableList
        }],
    });

    var translationManageGrid = $("#translationManageGrid").data("kendoGrid");
    var merchantEditable = jQuery("#merchantEditable").val();
    if(merchantEditable == "disable")
    {
        translationManageGrid.hideColumn('merchant_id');
    }
    function manageTableList(container, options) {

        $('<input data-text-field="key_table" data-value-field="key_table" data-bind="value:' + options.field + '"/>')
            .appendTo(container)
            .kendoDropDownList({
                dataSource: {
                    transport : {   
                        read : {
                            dataType : "json",
                            url : "hase_translation_manage/getManageTableList",
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
                dataValueField: "key_table"
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
                            url : "hase_translation_manage/getStaffGroup",
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

    
    function targetApprovalStatusId(container, options) {

        $('<input data-text-field="approval_status_name" data-value-field="approval_status_id" data-bind="value:' + options.field + '"/>')
            .appendTo(container)
            .kendoDropDownList({
                dataSource: {
                    transport : {   
                        read : {
                            dataType : "json",
                            url : "hase_translation_manage/getApprovalStatus?status=target",
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