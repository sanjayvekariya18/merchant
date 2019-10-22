var constant = {
        ACTION_URL              :   $('#request_url').val(),
        TOKEN               :       $('input[name="_token"]').val()
}
$(document).ready(function() {
    var token = $('input[name="_token"]').val();
    var requestUrl = $("#requestUrl").val();

    var searchUrlListGrid = jQuery("#searchListGrid").kendoGrid({
        dataSource: {
            pageSize: 20,
            transport : {   
                read : {
                    url: requestUrl+"/searchUrlLists",
                    dataType: "json",
                    type: "GET"
                },
                update: {
                    data:{_token:token},
                    url: requestUrl + "/updateSearchUrlLists",
                    dataType: "json",
                    type: "POST",
                    complete:function(data){
                        searchUrlListGrid.dataSource.read();
                    }
                },
                destroy: {
                    data:{_token:token},
                    url: requestUrl + "/deleteSearchUrlLists",
                    dataType: "json",
                    type: "POST",
                    complete:function(data){
                        searchUrlListGrid.dataSource.read();
                    }
                },
                create :{
                    data:{_token:token},
                    url: requestUrl + "/createSearchUrlLists",
                    dataType: "json",
                    type: "POST",
                    complete:function(data){
                        searchUrlListGrid.dataSource.read();
                    }
                },
            },
            schema: {
                model: {
                    id:'search_id',
                    fields: {
                        search_url: {
                            type: "url",
                            validation: {
                                required: true
                            }
                        },
                        search_priority: { editable: true, nullable: true }

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
            field: "search_url",
            title: "Search Url",
            width: "200px",
        }, {
            field: "search_priority",
            title: "Priority",
            width: "30px"
        }],
    });
    var searchUrlListGrid = $("#searchUrlListGrid").data("kendoGrid");
});