var constant = {
        ACTION_URL              :   $('#request_url').val(),
        TOKEN               :       $('input[name="_token"]').val()
}
$(document).ready(function () {
    $("#DropDown_keyword_list").kendoDropDownList({});
});
$(document).ready(function() {
    var token = $('input[name="_token"]').val();
    var requestUrl = $("#requestUrl").val();

    var keywordsListGrid = jQuery("#keywordsListGrid").kendoGrid({
        dataSource: {
            pageSize: 20,
            transport : {   
                read : {
                    url: requestUrl+"/getKeywordsLists",
                    dataType: "json",
                    type: "GET"
                }
            },
            schema: {
                model: {
                    keyword_id:"keyword_id",
                    fields: {   
                        active: {
                            editable: true,
                            defaultValue: { value: 1, text: "Active"}
                        },keyword_status: {
                            editable: false
                        },keyword: {
                            editable: false
                        },activity: {
                            editable: false
                        },region: {
                            editable: false
                        },dateTime: {
                            editable: false
                        }
                    }
                },
            },
            
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
        editable:true,
        detailInit: detailInit,
        columns: [
        {
            command: { text: "X", click: deleteKeyword },
            title: "Delete",
            width: "80px"
        },{ 
            command: { text: "Edit Tree", click: editKeyword },
            title: "", 
            width: "120px"
        },{
            field: "keyword",
            title: "Keywords",
            width: "200px",
        }, {
            field: "active",
            title: "Active",
            editor: statusDropDownEditor,
            template:'#if(active == 1){# <span style="color:Green; text-align:center; font-size:large;">  &bull; </span> #}else{# <span style="color:red; text-align:center; font-size:large;"> &diams; </span> #}#',
            editable:false,
            width: "90px"
        },{
            field: "activity",
            title: "Activity",
        }, {
            field: "region",
            title: "Region",
        },{
            field: "keyword_status",
            title: "Status",
            template: "#=(keyword_status)?'Enable':'Disable'#",
            width:100
        },{
            field: "dateTime",
            title: "Search Date/Time",
        }],
    });

    var keywordsListGrid = $("#keywordsListGrid").data("kendoGrid");
    function statusDropDownEditor(container, options) {
        var data = [
                    { Description: "Active", ID: "1" },
                    { Description: "Deactive", ID: "0" }
                ];
            $('<input id = "status" data-text-field="Description" data-value-field="ID" data-bind="value:' + options.field + '"/>')
                .appendTo(container)
                .kendoDropDownList ({
                    dataSource: data,
                    dataTextField: "Description",
                    dataValueField:"ID",
                    select: function (statusElement) {
                        var keyword_id = options.model.keyword_id;
                        var dataItem = this.dataSource.view()[statusElement.item.index()];
                        var status = dataItem.ID;

                        jQuery.ajax({
                                type: 'GET',
                                data:{
                                        keyword_id:keyword_id,
                                        status:status,
                                    },
                                url: requestUrl+"/updateActiveValue",
                                 success: function (statusMessage) {
                                    $("#keywordsListGrid").data("kendoGrid").dataSource.read();
                }
                        });
                    }
            });
    };
    function deleteKeyword(e) {
        e.preventDefault();
        var dataItem = this.dataItem($(e.currentTarget).closest("tr"));
        window.location = constant.ACTION_URL+"/"+dataItem.keyword_id+"/delete";
    }
    function editKeyword(e) {
        e.preventDefault();
        var dataItem = this.dataItem($(e.currentTarget).closest("tr"));
        window.location = constant.ACTION_URL+"/"+dataItem.keyword_id+"/editKeyword";
    }
    function detailInit(keywordDetail) {
    $("<div/>").appendTo(keywordDetail.detailCell).kendoGrid({
        dataSource: {
            pageSize: 5,
            transport: {
                read: {
                    url: "keyword_list_details/" + keywordDetail.data.keyword_id,
                    type: "GET"
                }
            }
        },

        pageable: {
            refresh: true,
            pageSizes: true
        },
        selectable: 'row',
        scrollable: true,
        sortable: true,
        columns: [{
            field: "links",
            title: "keyword Links",
            width: 100,
            sortable: false,
        }]
    });
}
});