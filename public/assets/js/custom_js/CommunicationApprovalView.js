$(document).ready(function() {
    var token = $('input[name="_token"]').val();
    var requestUrl = $("#requestUrl").val();

    var translationManageGrid = jQuery("#communicationApprovalGrid").kendoGrid({
        dataSource: {
            serverPaging: true,
            pageSize: 20,
            transport : {   
                read : {
                    url: requestUrl+"/getCommunicationApprovalLists",
                    dataType: "json",
                    type: "GET"
                }
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
        columns: [{
            field: "communications_text",
            title: "Communications Text",
        },{
            field: "communications_translation_text",
            title: "Communication Translation",
        },{
            field: "user_id",
            title: "User Name",
        },{
            field: "approved_date",
            title: "Approval Date",
        },{
            field: "approved_time",
            title: "Approval Time",
        }],
    });    
});