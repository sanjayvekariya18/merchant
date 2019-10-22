var communicationDetailList = {
       COMMUNICATION_LIST_VIEW: "communication-detail-list",
        GRID_ID : "#grid",
        GRID_ROW : "row",
        JSON_DATA_TYPE: "json",
        DATA_TYPE: "GET",
        GRID_PAGE_SIZE: 20,
        COMMUNICATION_TRANSLATION_HISTORY:"communication-translation-history",
}
function communicationStatusFilterValue(communicationTranslationStatusValue){
    localStorage.setItem('filtedDefaultId',communicationTranslationStatusValue);
    location.reload();
}
     var communicationGrid = jQuery("#grid").kendoGrid({
            dataSource: {
                pageSize: communicationDetailList.GRID_PAGE_SIZE,
                transport: {
                    read: {
                        url: communicationDetailList.COMMUNICATION_LIST_VIEW + "/" + localStorage.getItem('filtedDefaultId'),
                        dataType: communicationDetailList.JSON_DATA_TYPE,
                        type: communicationDetailList.DATA_TYPE
                    },
                    update: {
                        url:"update-communication-details",
                        dataType:communicationDetailList.JSON_DATA_TYPE,
                        type:communicationDetailList.DATA_TYPE,
                        complete : function (data) {
                            communicationListGridElement.dataSource.read();
                        }
                    },
                },
                batch : false,
                            schema : {                              
                                model : {
                                id : "communicationId",
                                fields : {
                                    communicationText : {
                                        editable : true
                                    },
                                    communicationsTopic : {
                                        editable : false
                                    },
                                    currentStatus : {
                                        editable : false
                                    },
                                }
                            }
                            },
                serverFiltering: true,
            },
            pageable: {
                refresh: true,
                pageSizes: true
            },
            selectable: communicationDetailList.GRID_ROW,
            serverPaging: true,
            serverFiltering: true,
            editable : "inline",
            serverSorting: true,
            scrollable: true,
            sortable: true,
            dataBound: function () {
                var grid=communicationGrid.data("kendoGrid");
                var gridData = communicationListGridElement.dataSource.view();
                    for (var i = 0; i < gridData.length; i++) {
                        var currentUid = gridData[i].uid;
                            if (gridData[i].editableStatus == true) {
                                var currenRow = grid.table.find("tr[data-uid='" + currentUid + "']");
                                var editButton = $(currenRow).find(".k-grid-edit");
                                editButton.hide();
                            }
                                }
                var data = this.dataSource.data();
                            $.each(data, function (i, row) {
                            var statusFontColor=row.get("statusFontColor");
                            var colorCode=row.get("colorCode");
                            var element = $('tr[data-uid="' + row.uid + '"] ');
                            element.css("background-color",colorCode);
                            element.css("color",statusFontColor);
                    });
                var dataSource = this.dataSource;         
                    this.element.find('tr.k-master-row').each(function() {
                       var row = $(this);            
                       var data = dataSource.getByUid(row.data('uid')); 
                       if (data.translationHistoryId == '') {
                            row.find('.k-hierarchy-cell a').remove();
                       }
                    });        
                },
            toolbar: kendo.template(jQuery("#templates").html()),
            detailTemplate: kendo.template($("#communicationTranslationTemplateDetail").html()),
            detailInit: communicationTranslationHistoryDetailTab,
            columns: [{
                field: "communicationText",
                title: "Communication Text",
                width: 200
            },{
                field: "communicationsTopic",
                title: "Communication Topic",
                width: 70
            },{
                field: "currentStatus",
                title: "Current Status",
                width: 70
            },{
                command : [{
                    name: "edit",
                    text: {
                        edit: "Edit",
                        update: "Apply",
                        cancel: "Cancel",
                    }
                }],
                attributes : {style : "text-align:center;"},
                width : 70,
            }],
        });
var communicationListGridElement = $("#grid").data("kendoGrid");
function communicationTranslationHistoryDetailTab(e) {
    var originalId=e.data.communicationId;
    localStorage.setItem('originalId',originalId);
    var detailRow = e.detailRow;
                    detailRow.find(".communicationTranslationHistory").kendoGrid({
                        dataSource : {
                            pageSize : communicationDetailList.GRID_PAGE_SIZE,
                            transport : {
                                read : {
                                    url : communicationDetailList.COMMUNICATION_TRANSLATION_HISTORY
                                            + "/"
                                            + e.data.communicationId,
                                    dataType : communicationDetailList.JSON_DATA_TYPE,
                                    type : communicationDetailList.DATA_TYPE
                                }
                            },
                        },

                        pageable : {
                            refresh : true,
                            pageSizes : true
                        },
                        scrollable : true,
                        sortable : true,
                        dataBound: function () {
                                var data = this.dataSource.data();
                                    $.each(data, function (i, row) {
                                    var statusFontColor=row.get("statusFontColor");
                                    var colorCode=row.get("colorCode");
                                    var element = $('tr[data-uid="' + row.uid + '"] ');
                                    element.css("background-color",colorCode);
                                    element.css("color",statusFontColor);
                                });
                        },
                        columns : [{
                            field : "communicationTranslationHistory",
                            title : "Translation List",
                            width : 100
                        },{
                            field : "communicationStatusName",
                            title : "Status",
                            width : 50
                        },{
                            field : "communicationTranslationDate",
                            title : "Date Time",
                            width : 80
                        },{
                            field : "userName",
                            title : "Name",
                            width : 70
                        }]
                    });
}