var scrapeEventDetailList = {
        SCRAPE_EVENT_DETAILS_LIST_VIEW: "scrape-event-detail-list",
        GRID_ID : "#grid",
        GRID_ROW : "row",
        JSON_DATA_TYPE: "json",
        DATA_TYPE: "GET",
        GRID_PAGE_SIZE: 20,
        SCRAPE_EVENT_TRANSLATION_HISTORY:"scrape-event-translation-history",
}
function scrapeEventStatusFilterValue(scrapeEventTranslationStatusValue){
    localStorage.setItem('filtedDefaultId',scrapeEventTranslationStatusValue);
    location.reload();
}
var regexHistoryColumnList = [];
var userName = localStorage.getItem('userName');
regexHistoryColumnList.push({
                field: "result_text",
                title: "Website Url",
                template: kendo.template(jQuery('#event_url_template').html()),
                width: 100
            },{
                field: "tupleregex",
                title: "Regex",
                width: 100
            },{
                field: "table_code",
                title: "Identity Type",
                width: 100
            },{
                field: "currentStatus",
                title: "Current Status",
                width: 100
             },/*{
                command : [{
                name: "edit",
                title:"Actions",
                text: {
                    edit: "Edit",
                    update: "Apply",
                    cancel: "Cancel",
                },title:"Actions",
                }],
                attributes : {style : "text-align:center;"},
                width: 100
                },*/);
if (userName == 'admin') {
    regexHistoryColumnList.push({
                    field: "",
                    title: "Actions",
                    template:kendo.template(jQuery('#deleteTemplete').html()),
                    width:"60px"
                });
}
function scrapeEventTranslationDetails() {
    var scrapeEventListGridElement = $(scrapeEventDetailList.GRID_ID)
        .kendoGrid({
            dataSource: {
                pageSize: scrapeEventDetailList.GRID_PAGE_SIZE,
                transport: {
                    read: {
                        url: scrapeEventDetailList.SCRAPE_EVENT_DETAILS_LIST_VIEW + "/" + localStorage.getItem('filtedDefaultId'),
                        dataType: scrapeEventDetailList.JSON_DATA_TYPE,
                        type: scrapeEventDetailList.DATA_TYPE
                    },
                    update: {
                        url:"update-website-url",
                        dataType:scrapeEventDetailList.JSON_DATA_TYPE,
                        type:scrapeEventDetailList.DATA_TYPE,
                        complete : function (data) {
                            scrapeEventListGridElement.dataSource.read();
                        }
                    },
                },
                batch : false,
                schema : {                              
                        model : {
                        id : "result_id",
                            fields : {
                                result_text : {
                                    editable : true
                                },
                                tupleregex : {
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
            selectable: scrapeEventDetailList.GRID_ROW,
            serverPaging: true,
            serverFiltering: true,
            serverSorting: true,
            scrollable: true,
            sortable: true,
            dataBound: function (scrapeEventList) {
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
                       if (data.translationHistoryId == '0') {
                            row.find('.k-hierarchy-cell a').remove();
                       }
                    });
            },
            toolbar: kendo.template(jQuery("#templates").html()),
            detailTemplate: kendo.template($("#scrapeEventTranslationTemplateDetail").html()),
            detailInit: scrapeEventTranslationHistoryDetailTab,
            columns: regexHistoryColumnList
        });
};
function scrapeEventTranslationHistoryDetailTab(e) {
    var originalId=e.data.result_id;
    localStorage.setItem('originalId',originalId);
    var detailRow = e.detailRow;
                    detailRow.find(".scrapeEventsTranslationHistory").kendoGrid({
                        dataSource : {
                            pageSize : scrapeEventDetailList.GRID_PAGE_SIZE,
                            transport : {
                                read : {
                                    url : scrapeEventDetailList.SCRAPE_EVENT_TRANSLATION_HISTORY
                                            + "/"
                                            + e.data.result_id,
                                    dataType : scrapeEventDetailList.JSON_DATA_TYPE,
                                    type : scrapeEventDetailList.DATA_TYPE
                                }
                            },
                        },

                        pageable : {
                            refresh : true,
                            pageSizes : true
                        },
                        dataBound: function (scrapeEventList) {   
                                var data = this.dataSource.data();
                                $.each(data, function (i, row) {
                                var statusFontColor=row.get("statusFontColor");
                                var colorCode=row.get("colorCode");
                                var element = $('tr[data-uid="' + row.uid + '"] ');
                                element.css("background-color",colorCode);
                                element.css("color",statusFontColor);
                            });
                        },
                        scrollable : true,
                        sortable : true,
                        columns : [{
                            field : "userName",
                            title : "Name",
                            width : 70
                        },{
                            field : "scrapeEventTranslationHistory",
                            title : "Translation List",
                            width : 100
                        },{
                            field : "scrapeEventStatusName",
                            title : "Status",
                            width : 50
                        },{
                            field : "scrapeEventTranslationDate",
                            title : "Date Time",
                            width : 80
                        }]
                    });
}