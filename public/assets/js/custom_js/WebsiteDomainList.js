var constant = {
        ACTION_URL              :   $('#request_url').val(),
        TOKEN               :       $('input[name="_token"]').val()
}
$(document).ready(function() {
    var token = $('input[name="_token"]').val();
    var requestUrl = $("#requestUrl").val();

    var websiteDomainListGrids = jQuery("#websiteDomainListGrid").kendoGrid({
        dataSource: {
            pageSize: 20,
            transport : {   
                read : {
                    url: requestUrl+"/websiteDomainLists",
                    dataType: "json",
                    type: "GET"
                },
                update: {
                    data:{_token:token},
                    url: requestUrl+"/updateWebsiteDomain",
                    dataType: "json",
                    type: "POST",
                    complete:function(data){
                        websiteDomainListGrids.dataSource.read();
                    }
                },
                destroy: {
                    data:{_token:token},
                    url: requestUrl+"/deleteWebsiteDomain",
                    dataType: "json",
                    type: "POST",
                    complete:function(data){
                        websiteDomainListGrids.dataSource.read();
                    }
                },
                create :{
                    data:{_token:token},
                    url:  requestUrl+"/createWebsiteDomain",
                    dataType: "json",
                    type: "POST",
                    complete:function(data){
                        websiteDomainListGrids.dataSource.read();
                    }
                },
            },
            schema: {
                model: {
                    id:'website_domain_id',
                    fields: {
                        website_url: {
                            validation: {
                                required: true
                            }
                        },
                        status: { editable: true, nullable: true }
                    }
                },
            }
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
            width: "40px"
        },{
            field: "website_url",
            title: "Website Url",
            width: "200px",
        }, {
            field: "status",
            title: "Status",
            width: "30px"
        }],
    });
    var websiteDomainListGrids = $("#websiteDomainListGrid").data("kendoGrid");
});