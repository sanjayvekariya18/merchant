var communicationLanguageDetailList = {
       COMMUNICATION_LIST_VIEW: "communication-detail-language-list",
        GRID_ID : "#grid",
        GRID_ROW : "row",
        JSON_DATA_TYPE: "json",
        DATA_TYPE: "GET",
        GRID_PAGE_SIZE: 20,
        COMMUNICATION_TRANSLATION_HISTORY:"communication-translation-language-history",
        
}
function communicationStatusFilterValue(communicationTranslationStatusValue){
    localStorage.setItem('filtedDefaultId',communicationTranslationStatusValue);
    location.reload();
}
function communicationTranslationDetails() {
    userKnownLanguageDropDown();
    var communicationListGridElement = $(communicationLanguageDetailList.GRID_ID)
        .kendoGrid({
            dataSource: {
                pageSize: communicationLanguageDetailList.GRID_PAGE_SIZE,
                transport: {
                    read: {
                        url: communicationLanguageDetailList.COMMUNICATION_LIST_VIEW + "/" + localStorage.getItem('filtedDefaultId'),
                        dataType: communicationLanguageDetailList.JSON_DATA_TYPE,
                        type: communicationLanguageDetailList.DATA_TYPE
                    }
                },
                serverFiltering: true,
            },
            pageable: {
                refresh: true,
                pageSizes: true
            },
            selectable: communicationLanguageDetailList.GRID_ROW,
            scrollable: true,
            sortable: true,
            dataBound: function (communicationTranslationView) {
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
                width: 100
            },{
                field: "communicationsTopic",
                title: "Communication Topic",
                width: 100
            },{
                field: "currentStatus",
                title: "Current Status",
                width: 100
            }],
        });
};
function communicationTranslationHistoryDetailTab(e) {
    var $ = jQuery.noConflict();
    var detailRow = e.detailRow;
                    detailRow.find(".communicationTranslationHistory").kendoGrid({
                        dataSource : {
                            pageSize : communicationLanguageDetailList.GRID_PAGE_SIZE,
                            transport : {
                                read : {
                                    url : communicationLanguageDetailList.COMMUNICATION_TRANSLATION_HISTORY
                                            + "/"
                                            + e.data.communicationId,
                                    dataType : communicationLanguageDetailList.JSON_DATA_TYPE,
                                    type : communicationLanguageDetailList.DATA_TYPE
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
                            field : "userName",
                            title : "user Name",
                            width : 70
                        },{
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
                        }]
                    });
}