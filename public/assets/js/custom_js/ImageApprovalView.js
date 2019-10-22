$(document).ready(function() {
    var token = $('input[name="_token"]').val();
    var requestUrl = $("#requestUrl").val();

    var translationManageGrid = jQuery("#imageApprovalGrid").kendoGrid({
        dataSource: {
            serverPaging: true,
            pageSize: 20,
            transport : {   
                read : {
                    url: requestUrl+"/getImageApprovalLists",
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
        columns: [
        {
            field: "image_url",
            title: "Image Url",
            template: kendo.template(jQuery('#image_url_template').html()),
        },{
            field: "translation_text",
            title: "Image Translation",
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