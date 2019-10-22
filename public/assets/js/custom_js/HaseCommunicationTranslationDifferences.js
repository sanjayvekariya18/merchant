var communicationDetailList = {
       COMMUNICATION_DIFFRENCE_LIST_VIEW: "../public/communication-diffrence-detail-list",
        GRID_ID : "#grid",
        JSON_DATA_TYPE: "json",
        DATA_TYPE: "GET",
        GRID_PAGE_SIZE: 20,
}
function communicationTranslationDifferences() {
    var oldVersionNumber = localStorage.getItem('oldVersionNumber');
    var newVersionNumber = localStorage.getItem('newVersionNumber');
    var originalId = localStorage.getItem('originalId');
    var firstOriginalId=localStorage.getItem('firstOriginalId');
    var secondOriginalId = localStorage.getItem('secondOriginalId');   
    dataSource = new kendo.data.DataSource({
        transport: {
            read: {
                url: communicationDetailList.COMMUNICATION_DIFFRENCE_LIST_VIEW + "/" + localStorage.getItem('oldVersionNumber') + "/" + localStorage.getItem('newVersionNumber'),
                type: 'GET',
                dataType: communicationDetailList.JSON_DATA_TYPE,
                data: {
                    oldVersionNumber: oldVersionNumber,
                    newVersionNumber: newVersionNumber,
                    originalId: originalId,
                    firstOriginalId:firstOriginalId,
                    secondOriginalId:secondOriginalId

                }
            }
        },
        schema: {
            model: {
                fields: {
                    oldDataRevision: {
                        validation: {
                            required: true
                        }
                    },
                    newDataRevision: {
                        validation: {
                            required: true
                        }
                    },
                }
            }
        },
    });
        jQuery(communicationDetailList.GRID_ID).kendoGrid({
        dataSource: dataSource,
        pageable: false,
        columns: [{
                field: "oldDataRevision",
                title: "Translation Sentence first",
                encoded: false,
                width: 50
        }, {
                field: "newDataRevision",
                title: "Translation Sentence second",
                encoded: false,
                width: 70
        }, ],
    });
};
