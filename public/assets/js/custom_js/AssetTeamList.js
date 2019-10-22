
function assetTeamListData()
{
    var categoriesElement = jQuery("#assetTeamListGrid").kendoGrid({
        dataSource: {
            serverPaging: true,
            pageSize: 20,
            transport: {
                read: {
                    url: "asset_team_list/asset_list",
                    dataType: "json",
                    type: "GET",
                },
            },
            schema: {
                total:'total',
                data:'asset_list',
                model: {
                    id:'asset_id',
                    fields: {
                        asset_name: {
                            editable: false
                        },
                        asset_code: {
                            editable: false
                        },
                        asset_website: {
                            editable: false
                        },
                        socials:{
                            editable: false
                        }
                    }
                },
            },
            serverFiltering: true,
        },
        pageable: {
            refresh: true,
            pageSizes: true
        },
        toolbar: [{
            template: kendo.template($("#assetGridSearch").html())
        }],
        scrollable: true,
        autoSync: true,
        sortable: true,
        reorderable: true,
        serverFiltering: false,
        groupable: true,
        resizable: true,
        detailInit: detailInit,
        dataBound: OnDataBound,
        editable: true,
       
        columns: [
        {
            field: "asset_id",
            title: "Asset Id",
            hidden: true
        },
        {
            field: "asset_name",
            title: "Asset Name",
            width: 100
        }, {
            field: "asset_code",
            title: "Asset Symbol",
            width: 100
        }, {
            field: "asset_website",
            title: "Website Url",
            width: 100,
            template: "#if(data.asset_website){#<a href='#=data.asset_website#' target='_blank'>#=data.asset_website#</a>#}#",
        }, {
            field: "socials",
            title: "socials",
            width: 100,
            template: "# if(data.socials) { for (var i = 0; i < data.socials.length; i++) {# #= data.socials[i] # #} }#",
        }

        ],
    });
}

function OnDataBound() {

    var grid = jQuery("#assetTeamListGrid").data("kendoGrid");  
    var data = grid.dataSource._data;
    var len = data.length;

    for(var i = 0; i < len; i++) {
        var dataItem = data[i];
        if(dataItem.peoples == 0) { 
            var row = grid.table.find("tr[data-uid='" + dataItem.uid + "']");
            var cell = row.find(".k-i-expand");
            cell.css('display','none');
        }
    }
}


function detailInit(assetTeamListElement) {
    jQuery("<div/>").appendTo(assetTeamListElement.detailCell).kendoGrid({
        dataSource: {
            pageSize: 20,
            transport: {
                read: {
                    url: "asset_team_list/people/"+assetTeamListElement.data.asset_id,
                    dataType: "json",
                    type: "GET",
                },
            },
            batch: false,
            schema: {
                model: {
                    people_id:'people_id',
                    fields: {
                        people_name: {
                            editable: false
                        },
                        people_title: {
                            editable: false
                        },
                        socials: {
                            editable: false
                        },
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
        sortable: true,
        reorderable: true,
        resizable: true,
        editable: false,
        columns: [
        {
            field: "people_name",
            title: "People Name",
            width: 100
        },{
            field: "people_title",
            title: "People Title",
            width: 100
        },{
            field: "socials",
            title: "socials",
            width: 100,
            template: "# if(data.socials) { for (var i = 0; i < data.socials.length; i++) {# #= data.socials[i] # #} }#",
        }
        ],
    });
}

$(document).ready(function() {
    $("#assetBtnSearch").on("click",function (e) {
        var searchValue = $('#assetSearchBox').val();
        $("#assetTeamListGrid").data("kendoGrid").dataSource.filter({
            logic  : "or",
            filters: [{
                field   : "asset_code",
                operator: "contains",
                value   : searchValue
            },
            {
                field   : "asset_name",
                operator: "contains",
                value   : searchValue
            }]
        });
    });
});