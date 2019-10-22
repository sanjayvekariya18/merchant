var translationApprovalListDetails = {
    TRANSLATION_APPROVAL_DETAILS_LIST: "translation-approval-detail-list",
    TRANSLATION_APPROVAL_CURRENT_DETAILS_LIST: "translation-approval-current-detail-list",
    GRID_ID : "#grid",
    GRID_ROW : "row",
    JSON_DATA_TYPE: "json",
    DATA_TYPE: "GET",
    GRID_PAGE_SIZE: 20,
    FORWARD_SLASH_SEPARATORS: "/",
    INPUT_FIRST_RADIO_CLASS_VALUE:'input[class=firstRadio]:eq(',
    INPUT_LAST_RADIO_CLASS_VALUE:'input[class=secondRadio]:last',
    INPUT_RADIO_DISABLE_CLASS:'input[class=radioDisable]',
    ROUND_BRACKET_END:')',
    FIND_RADIO_BUTTON_CLASS: 'class',
    RADIO_BUTTON_DISBALE: 'radioDisable',
    RADIO_BUTTON_CLASS_DISABLED: 'disabled',
    RADIO_BUTTON_CHECK:'checked',
    COMPARE_TRANSLATION_TIKI_HISTORY_INPUT_TAG:"<input id='translationId' class='k-button' name='compareVersion' onclick='compareLanguageTranslationHistory();' type='button' value='Compare'>",
    DISPLAY_FOR_SELECT_RADIO_BUTTONS_TEMPLATE:'<input style="margin:0 25% 0 20%" class="firstRadio" id="firstColumn" name="firstColumn" type="radio"/><input class="secondRadio" id="secondColumn" name="secondColumn" type="radio"/>',
    FIND_INPUT_NAME_SECOND_COLUMN:'input[name=secondColumn]',
    FIND_INPUT_NAME_FIRST_COLUMN:'input[name=firstColumn]',
    TRANSLATION_HISTORY_GRID_TEMPLATE:"<div id='backToTranslationHistoryButton' style='display:none;width:30px;height22px'><a onclick='backToTranslationHistoryWindow()' class='k-button'><span class='k-icon k-i-arrow-w'></span></a></div><div id='translationHistoryGrid'></div><div id='translationCompareHistoryGrid'></div>",
    INPUT_NAME_FIRST_COLUMN_RADIO_CHECKED:'input[name=firstColumn]:radio:checked',
    INPUT_NAME_SECOND_COLUMN_RADIO_CHECKED:'input[name=secondColumn]:radio:checked',
    FIND_TR_TAG:'tr',
}

var closeEvent = function() {
   location.reload();
}
jQuery('.pull-right').click(function() {
    localStorage.clear();
});
function imagestatusFilterValue(translationStatusValue){
    localStorage.setItem('filtedDefaultId',translationStatusValue);
    location.reload();
}
function saveTranslationComment(historyId){
   var historyId=localStorage.getItem('historyId');
   var approveRejectComments = $('#approvecomments').val();
   if(approveRejectComments == '' ){
    alert('Please add Comment');
   }else {
   jQuery.ajax({
        type: "GET",
        url: "update_image_translation_comments",
        data: {
            historyId: historyId,
            approveRejectComments: approveRejectComments
        },
        cache: false,
        success: function (updateComments) {
        location.reload();
        }
    });
}
}
commentWindow = jQuery("#comments")
                        .kendoWindow({
                            title: "Comments",
                            modal: true,
                            visible: false,
                            resizable: false,
                            width: "auto",
                            height: "auto",
                            close: closeEvent
                          
                        }).data("kendoWindow");
var commentDetailsTemplate;
commentDetailsTemplate = kendo.template(jQuery("#templateComments").html());
function acceptRejectComment(historyId) {
    localStorage.setItem('historyId',historyId);
    commentWindow.content(commentDetailsTemplate(kendo.template(jQuery("#templateComments").html())));
    commentWindow.center().open();                 
}
function acceptRejectCommentDetails() {
    commentWindow.content(commentDetailsTemplate(kendo.template(jQuery("#templateComments").html())));
    commentWindow.center().open();                 
}
function updateTranslationStatus(translationListValue,historyId,translationGroupId,imageId,staffGroupId,translationActionStatus,statusId){
        if (confirm('Are you sure you want to '+ translationActionStatus + " " + 'selected list?')) {              
            if(translationActionStatus == 'Reject') {
                localStorage.setItem('historyId',historyId);
                jQuery.ajax({
                    type: "GET",
                    url: "update_image_translation_status",
                    data: { 
                        translationListValue:translationListValue,  
                        historyId: historyId,
                        translationGroupId:translationGroupId,
                        imageId : imageId,
                        staffGroupId:staffGroupId,
                        translationStatus: translationActionStatus,
                        statusId:statusId,
                    },
                    success: function (updatestatus) {
                    }
                });
                acceptRejectCommentDetails({
                }, function no() {
                    
                });
            }
            else if(translationActionStatus == 'Comment') {
                acceptRejectComment(historyId);
            }
            else {
                jQuery.ajax({
                    type: "GET",
                    url: "update_image_translation_status",
                    data: {
                        translationListValue:translationListValue,
                        historyId: historyId,
                        translationGroupId:translationGroupId,
                        imageId : imageId,
                        staffGroupId:staffGroupId,
                        translationStatus: translationActionStatus,
                        statusId:statusId,
                    },
                    success: function (updatestatus) {
                        location.reload();
                    }
                });
            }
        }
        else
            return;
}
differenceWindow = jQuery("#Difference")
                        .kendoWindow({
                            title: "Translation Difference",
                            modal: true,
                            visible: false,
                            resizable: false,
                            width: 650,
                            height: 180,   
                        }).data("kendoWindow");

var templateDifference;
templateDifference = kendo.template(jQuery("#templateDifference").html());
function compareLanguageTranslationHistory() {
         var oldVersionNumber = jQuery(translationApprovalListDetails.INPUT_NAME_FIRST_COLUMN_RADIO_CHECKED).closest(translationApprovalListDetails.FIND_TR_TAG).children().eq(6).text();
         var newVersionNumber = jQuery(translationApprovalListDetails.INPUT_NAME_SECOND_COLUMN_RADIO_CHECKED).closest(translationApprovalListDetails.FIND_TR_TAG).children().eq(6).text();
         localStorage.setItem('oldVersionNumber',oldVersionNumber);
         localStorage.setItem('newVersionNumber',newVersionNumber);
         var firstOriginalId = jQuery(translationApprovalListDetails.INPUT_NAME_FIRST_COLUMN_RADIO_CHECKED).closest(translationApprovalListDetails.FIND_TR_TAG).children().eq(4).text();
         var secondOriginalId = jQuery(translationApprovalListDetails.INPUT_NAME_SECOND_COLUMN_RADIO_CHECKED).closest(translationApprovalListDetails.FIND_TR_TAG).children().eq(4).text();

         localStorage.setItem('firstOriginalId',firstOriginalId);
         localStorage.setItem('secondOriginalId',secondOriginalId);
         var firstSelection = jQuery(translationApprovalListDetails.INPUT_NAME_FIRST_COLUMN_RADIO_CHECKED).closest(translationApprovalListDetails.FIND_TR_TAG).children().eq(7).text();
         var secondSelection = jQuery(translationApprovalListDetails.INPUT_NAME_SECOND_COLUMN_RADIO_CHECKED).closest(translationApprovalListDetails.FIND_TR_TAG).children().eq(7).text();
        if(firstSelection != secondSelection){
            alert("Please Select same translation version");
        }else{
         differenceWindow.content(templateDifference(kendo.template(jQuery("#templateDifference").html())));
         differenceWindow.center().open(); 
         }     
}
    var wnd,detailsTemplate,commentDetailsTemplate;
    var tabstrip = $("#tabstrip").kendoTabStrip().data("kendoTabStrip");
    var imageListGridElement = $(translationApprovalListDetails.GRID_ID)
        .kendoGrid({
            dataSource: {
                pageSize: translationApprovalListDetails.GRID_PAGE_SIZE,
                transport: {
                    read: {
                        url: translationApprovalListDetails.TRANSLATION_APPROVAL_CURRENT_DETAILS_LIST + translationApprovalListDetails.FORWARD_SLASH_SEPARATORS
                                            + localStorage.getItem('filtedDefaultId'),
                        dataType: translationApprovalListDetails.JSON_DATA_TYPE,
                        type: translationApprovalListDetails.DATA_TYPE
                    }
                },
                serverFiltering: true,
            },
            pageable: {
                refresh: true,
                pageSizes: true
            },
            selectable: translationApprovalListDetails.GRID_ROW,
            serverPaging: true,
            serverFiltering: true,
            serverSorting: true,
            scrollable: true,
            sortable: true,
            dataBound: function (imageCategoryView) {
                var data = this.dataSource.data();
                            $.each(data, function (i, row) {
                                var originalId=row.get("approval_id");
                                localStorage.setItem('originalId',originalId);
                            var statusFontColor=row.get("statusFontColor");
                            var colorCode=row.get("colorCode");
                            var element = $('tr[data-uid="' + row.uid + '"] ');
                            element.css("background-color",colorCode);
                            element.css("color",statusFontColor);
                    });
                },
            toolbar: kendo.template(jQuery("#templates").html()),
            columns: [{
                field: "originalContent",
                editable: false,
                title: "Original",
                template:'#if(Fields == "image_url"){# <img src="#=originalContent#"  width=110 height=90 /></a> #} else {# #=originalContent# #}#',
                width: 100
            },{
                field: "translation_text",
                editable: true,
                title: "Current",
                width: 100
            },{
                field: "Fields",
                title: "Field",
                width: 110
            },{
                field: "request_date",
                title: "Date/Time",
                width: 110
            },{
                field: "updatedBy",
                title: "Updated By",
                width: 110
            },{
                field: "actionByValue",
                title: "Action by",             
                width: 110
            },{
                field: "updatedAt",
                title: "Date/Time Update",            
                width: 110
             }],
        });

var wnd,detailsTemplate,commentDetailsTemplate;
    var tabstrip = $("#tabstrip").kendoTabStrip().data("kendoTabStrip");
    var imageListGridElement = $("#historyGrid")
        .kendoGrid({
            dataSource: {
                pageSize: translationApprovalListDetails.GRID_PAGE_SIZE,
                group: [{ field: 'Fields ' , dir: "desc" },{ field: 'History' , dir: "desc" }],
                transport: {
                    read: {
                        url: translationApprovalListDetails.TRANSLATION_APPROVAL_DETAILS_LIST + translationApprovalListDetails.FORWARD_SLASH_SEPARATORS
                                            + localStorage.getItem('filtedDefaultId'),
                        dataType: translationApprovalListDetails.JSON_DATA_TYPE,
                        type: translationApprovalListDetails.DATA_TYPE
                    }
                },
                serverFiltering: true,
            },
            pageable: {
                refresh: true,
                pageSizes: true
            },
            selectable: translationApprovalListDetails.GRID_ROW,
            serverPaging: true,
            serverFiltering: true,
            serverSorting: true,
            scrollable: true,
            sortable: true,
            dataBound: function (imageCategoryView) {
                var data = this.dataSource.data();
                            $.each(data, function (i, row) {
                                var originalId=row.get("approval_id");
                                localStorage.setItem('originalId',originalId);
                            var statusFontColor=row.get("statusFontColor");
                            var colorCode=row.get("colorCode");
                            var element = $('tr[data-uid="' + row.uid + '"] ');
                            element.css("background-color",colorCode);
                            element.css("color",statusFontColor);
                    });
                var data = this.dataSource.data();
                            $.each(data, function (i, row) {
                            var commentDetails=row.get("commentId");
                            if (commentDetails != null ) {
                                $('tr[data-uid="' + row.uid + '"] td:nth-child(1)').css("background-color", "blue");
                            }
                });
                var dataSource = this.dataSource;         
                    this.element.find('tr.k-master-row').each(function() {
                       var row = $(this);            
                       var data = dataSource.getByUid(row.data('uid'));
                       if (data.commentId == null) {
                            row.find('.k-hierarchy-cell a').remove();
                       }
                    });

                     jQuery(translationApprovalListDetails.INPUT_FIRST_RADIO_CLASS_VALUE + 0 + translationApprovalListDetails.ROUND_BRACKET_END).attr(translationApprovalListDetails.FIND_RADIO_BUTTON_CLASS, translationApprovalListDetails.RADIO_BUTTON_DISBALE);
                     jQuery(translationApprovalListDetails.INPUT_LAST_RADIO_CLASS_VALUE).attr(translationApprovalListDetails.FIND_RADIO_BUTTON_CLASS, translationApprovalListDetails.RADIO_BUTTON_DISBALE);
                     jQuery(translationApprovalListDetails.INPUT_RADIO_DISABLE_CLASS).attr(translationApprovalListDetails.RADIO_BUTTON_CLASS_DISABLED, true);
                     jQuery(translationApprovalListDetails.FIND_INPUT_NAME_SECOND_COLUMN).eq(0).attr(translationApprovalListDetails.RADIO_BUTTON_CHECK, translationApprovalListDetails.RADIO_BUTTON_CHECK);
                     jQuery(translationApprovalListDetails.FIND_INPUT_NAME_FIRST_COLUMN).last().attr(translationApprovalListDetails.RADIO_BUTTON_CHECK, translationApprovalListDetails.RADIO_BUTTON_CHECK);
                    
                },
            toolbar: kendo.template(jQuery("#templates").html()),
            detailTemplate: kendo.template($("#imageCommentsTemplateDetail").html()),
            detailInit: detailInit,
            columns: [
            {
                field : "translationStatus",
                title : "Action",
                template:kendo.template(jQuery('#actiontemplate').html()),
                width : 100
            },{
               field: "approval_id",
                title: "approval_id",
                hidden:true,
            },{
                title: translationApprovalListDetails.COMPARE_TRANSLATION_TIKI_HISTORY_INPUT_TAG,
                template: translationApprovalListDetails.DISPLAY_FOR_SELECT_RADIO_BUTTONS_TEMPLATE,
                width: 100
            },{
                field : "translation_version",
                title : "Version",
                width : 60
            },{
               field: "approval_grouphash",
                title: "approval_grouphash",
                hidden:true,
            },{
                field: "originalContent",
                editable: false,
                title: "Original",
                template:'#if(Fields == "image_url"){# <img src="#=originalContent#"  width=110 height=90 /></a> #} else {# #=originalContent# #}#',
                width: 140
            },{
                field: "translation_text",
                editable: true,
                title: "Translation",
                width: 100
            },{
                field: "Fields",
                title: "Field",
                width: 110
            },{
                field: "statusName",
                title: "Status",
                width: 110
            },{
                field: "request_date",
                title: "Date/Time",
                width: 110
            },{
                field: "locationName",
                title: "Location",
                width: 110
            },{                        
                field: "section",
                title: "Section",                        
                width: 170
            },{
                field: "updatedBy",
                title: "Updated By",
                width: 110
            },{
                field: "actionByValue",
                title: "Action by",             
                width: 110
            },{
                field: "updatedAt",
                title: "Date/Time Update",            
                width: 110
             }],
        });
function detailInit(e) {
                    var detailRow = e.detailRow;
                    detailRow.find(".commentDetails").kendoGrid({
                        dataSource: {                           
                            transport : {
                                read : {
                                    url : "rejects_comment_list" + "/"
                                            + e.data.commentId,
                                    dataType : "json",
                                    type : "GET",
                                }
                            },
                       pageSize: 5,                           
                       serverFiltering: true,
                },
                pageable: {
                    refresh: true,
                    pageSizes: true
                },
                        scrollable: false,
                        sortable: true,
                        pageable: true,
                        columns: [
                            { field: "comment", title:"Comments", width: "70px" },
                            { field: "commentedBy", title:"Commented By", width: "30px" },
                            { field: "commentDate", title:"Commented Date", width: "30px" },
                            { field: "commentTime", title:"Commented Time", width: "30px" }                                
                        ]
                    });              
                }
