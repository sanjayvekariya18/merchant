var imageViewList = {
    IMAGE_LIST_VIEW: "image-detail-list",
    IMAGE_NAME: "name",
    WINDOW_HEIGHT_STYLE: 'height=700',
    WINDOW_WIDTH_STYLE: 'width=900',
    VIEWED_STATUS_UPDATE: "image-view-status",
    UPDATE_IMAGE_TEXT: "update-image-text",
    IMAGE_VIEW: 'imageView',
    IMAGE_TEXT:"imageText",
    IMAGE_TEXT_TITLE:"Image Text",
    IMAGE_URL_TEMPLATE:'<a onclick="return imageWindowView(\'#=imageData#\',#=imageId#);" href="javascript:void(0)"><img src="#=imageData#"  width=110 height=90 /></a>',
    IMAGE_TEXT_TEMPLATE:"#if(imageEditPermission != 'add'){# #=imageText# #}else if(statusName != 'Accepted'){# <textarea class='k-input k-textbox' type='textarea' rows='3' name='imageText' onchange='updateImageText(#=imageId#,\"#=photoAlbumName#\",this.value)'>#=imageText#</textarea> #}else if(statusName == 'Accepted' && userId == '1'){# <textarea class='k-input k-textbox' type='textarea' rows='3' name='imageText' onchange='updateImageText(#=imageId#,\"#=photoAlbumName#\",this.value)'>#=imageText#</textarea> #}else {# #=imageText# #}#",
    PHOTO_ALBUM_NAME_TEMPLATE : '<span id="ToolTip" title="#=photoAlbumName#">#=photoAlbumName.substr(0,20)#</span>',
    OLD_VALUE_TEMPLATE : '<span id="ToolTip" title="#=imageOldValue#">#=imageOldValue.substr(0,10)#</span>',
    IMAGE_CREATION_TITLE:'Creation',
    IMAGE_TRANSLATION_HISTORY_LIST:"image-translation-history-list",
    USER_NAME_FIELD:'userName',
    USER_NAME_TITLE : 'User Name',
    USER_NAME_FIELD_WIDTH:60,
    IMAGE_TRANSLATION_HISTORY_FIELD: 'imageTranslationHistory',
    IMAGE_TRANSLATION_HISTORY_VALUE : 'Version',
    IMAGE_TRANSLATION_HISTORY_FIELD_WIDTH:70,
    IMAGE_ATTEMPT_FIELD:"imageAttempt",
    IMAGE_ATTEMPT_TITLE:"Attempt",
    IMAGE_OLD_VALUE_FIELD:"imageOldValue",
    IMAGE_OLD_VALUE_TITLE:"Old",
    IMAGE_NEW_VALUE_TITLE:"New",
    IMAGE_COLUMN_WIDTH:100 ,
    IMAGE_LIST_WIDTH: 120,
    IMAGE_TEXT_WIDTH:150,
    IMAGE_ACTIVITY_REGION_VALUE_WIDTH:90,
    NEW_VALUE_TEMPLATE : '<span id="ToolTip" title="#=imageText#">#=imageText.substr(0,10)#</span>',
    CREATION_DATE_TEMPLATE : '<span id="ToolTip" title="#=modifiedDate#">#=modifiedDate.substr(0,40)#</span>',
    ACTIVITY_IFRAME_VALUE:'activityIframe',
    RESULT_TREEVIEW_REGION:'result_treeviewRegion',
    RESULT_TREEVIEW_ACTIVITY:'result_treeviewActivity',
    INSERT_ACTIVITY_REGION_URL: "insert-activity-region-value",
    IMAGE_ACTIVITY_VALUE_TEMPLATE : '<span id="ToolTip" title="#=imageActivityValue#">#=imageActivityValue.substr(0,40)#</span>',
    IMAGE_REGION_VALUE_TEMPLATE : '<span id="ToolTip" title="#=imageRegionValue#">#=imageRegionValue.substr(0,40)#</span>',
    IMAGE_ACTIVITY_VALUE:'imageActivityValue',
    IMAGE_REGION_VALUE:'imageRegionValue',
    IMAGE_ACTIVITY_TITLE:'Activity',
    IMAGE_REGION_TITLE:'Region',
    KENDO_WINDOW_WIDTH:"850px",
    KENDO_WINDOW_HIEGHT:400,
    IMAGE_CATEGOTY_WINDOW_TITLE:"Image Category View",
    GRID_TABLE_ROW_VALUE:"tr",
    GRID_ID : "#grid",
    GRID_ROW : "row",
    JSON_DATA_TYPE: "json",
    DATA_TYPE: "GET",
    GRID_PAGE_SIZE: 20,
    FLAG_ONE: 1,
    MODIFIED_DATE:"modifiedDate",
    MODIFIED_DATE_TITLE:"Date/Time",
    FORWARD_SLASH_SEPARATORS: "/",
    PHOTO_ALBUM_NAME_FIELD:'photoAlbumName',
    PHOTO_ALBUM_NAME_TITLE:'Photo Album Name',
    IMAGE_URL_TITLE:"Image Url",
    IMAGE_URL_DATA:"imageData",
    IMAGE_ID_VALUE:"imageId",
}
/**
 * Function is for update the image translate text.
 * @param integer imageId
 * @param string imageText
 */
    function updateImageText(imageId,albumName,imageText) {
        jQuery.ajax({
            type: imageViewList.DATA_TYPE,
            url: imageViewList.UPDATE_IMAGE_TEXT,
            data: {
                imageId: imageId,
                imageText: imageText,
                albumName: albumName
            },
            success: function (updateData) {
               imageListGridElement.data("kendoGrid").dataSource.read();
            }
        });
    }
/**
 * Function is for open image URL in new browser window. 
 * @param string imageUrl
 * @param integer imageId
 * @returns Boolean
 */
    function imageWindowView(imageUrl, imageId) {
        if (imageId != imageViewList.IMAGE_VIEW) {
            var viewStatus = imageViewList.FLAG_ONE
            jQuery.ajax({
                type: imageViewList.DATA_TYPE,
                url: imageViewList.VIEWED_STATUS_UPDATE,
                data: {
                    imageId: imageId,
                    statusValue: viewStatus
                },
                success: function (updateData) {
                    imageListGridElement.data("kendoGrid").dataSource.read();
                }
            });
        }
        imageWindow = window.open(imageUrl, imageViewList.IMAGE_NAME, imageViewList.WINDOW_HEIGHT_STYLE, imageViewList.WINDOW_WIDTH_STYLE);
        if (window.focus) {
            imageWindow.focus()
        }
        imageWindow.onblur = function () {
            this.close();
        };
        return false;
    }
    commentWindow = jQuery("#comments")
                        .kendoWindow({
                            title: "Translation Language Difference",
                            modal: true,
                            visible: false,
                            resizable: false,
                            width: 650,
                            height: 180, 
                        }).data("kendoWindow");

var commentDetailsTemplate;
commentDetailsTemplate = kendo.template(jQuery("#templateComments").html());
    function compareLanguageTranslationHistory() {
         var oldVersionNumber = jQuery(imageViewList.INPUT_NAME_FIRST_COLUMN_RADIO_CHECKED).closest(imageViewList.FIND_TR_TAG).children().eq(1).text();
         var newVersionNumber = jQuery(imageViewList.INPUT_NAME_SECOND_COLUMN_RADIO_CHECKED).closest(imageViewList.FIND_TR_TAG).children().eq(1).text();
         localStorage.setItem('oldVersionNumber',oldVersionNumber);
         localStorage.setItem('newVersionNumber',newVersionNumber);
         commentWindow.content(commentDetailsTemplate(kendo.template(jQuery("#templateComments").html())));
         commentWindow.center().open();      
}
function imagestatusFilterValue(translationStatusValue){
    localStorage.setItem('filtedDefaultId',translationStatusValue);
    location.reload();
}
var imageDetailColumnList = [];
var userName =localStorage.getItem('userName');
imageDetailColumnList.push({
                field: imageViewList.IMAGE_URL_DATA,
                editable: false,
                title: imageViewList.IMAGE_URL_TITLE,
                template:imageViewList.IMAGE_URL_TEMPLATE,
                width: imageViewList.IMAGE_LIST_WIDTH
            }, {
                field: imageViewList.IMAGE_TEXT,
                editable: true,
                title: imageViewList.IMAGE_TEXT_TITLE,
                template: kendo.template(imageViewList.IMAGE_TEXT_TEMPLATE),
                width: imageViewList.IMAGE_TEXT_WIDTH
            },{
                field: imageViewList.IMAGE_ATTEMPT_FIELD,
                editable: false,
                title: imageViewList.IMAGE_ATTEMPT_TITLE,
                width: 50
            },{
                field: imageViewList.IMAGE_OLD_VALUE_FIELD,
                editable: false,
                title: imageViewList.IMAGE_OLD_VALUE_TITLE,
                template:imageViewList.OLD_VALUE_TEMPLATE,
                width: imageViewList.IMAGE_COLUMN_WIDTH
            },{
                field: imageViewList.IMAGE_TEXT,
                title: imageViewList.IMAGE_NEW_VALUE_TITLE,
                template:imageViewList.NEW_VALUE_TEMPLATE,
                width: imageViewList.IMAGE_COLUMN_WIDTH
            }/*,{
                field: imageViewList.IMAGE_ACTIVITY_VALUE,
                title: imageViewList.IMAGE_ACTIVITY_TITLE,
                template:imageViewList.IMAGE_ACTIVITY_VALUE_TEMPLATE,
                width: imageViewList.IMAGE_ACTIVITY_REGION_VALUE_WIDTH
            },{
                field: imageViewList.IMAGE_REGION_VALUE,
                title: imageViewList.IMAGE_REGION_TITLE,
                template:imageViewList.IMAGE_REGION_VALUE_TEMPLATE,
                width: imageViewList.IMAGE_ACTIVITY_REGION_VALUE_WIDTH
            }*/,{
                field: imageViewList.MODIFIED_DATE,
                title: imageViewList.IMAGE_CREATION_TITLE,
                template:imageViewList.CREATION_DATE_TEMPLATE,
                width: imageViewList.IMAGE_COLUMN_WIDTH
            },{
            field: "imageStatus",
            title: "Status",
            template: "#=(imageStatus)?'Disable':'Enable'#",
            width:100
        });
if (userName == 'admin') {
    imageDetailColumnList.push({
            field: "",
            title: "Actions",
            template:kendo.template(jQuery('#deleteTemplete').html()),
            width:"100px"
        });
}
    var wnd,detailsTemplate,commentDetailsTemplate;
    var imageListGridElement = jQuery(imageViewList.GRID_ID)
        .kendoGrid({
            dataSource: {
                pageSize: imageViewList.GRID_PAGE_SIZE,
                transport: {
                    read: {
                        url: imageViewList.IMAGE_LIST_VIEW + imageViewList.FORWARD_SLASH_SEPARATORS
                                            + localStorage.getItem('filtedDefaultId'),
                        dataType: imageViewList.JSON_DATA_TYPE,
                        type: imageViewList.DATA_TYPE
                    }
                }
            },
            pageable: {
                refresh: true,
                pageSizes: true
            },
            noRecords: true,
            messages: {
                noRecords: "There is no data on current page"
            },
            selectable: imageViewList.GRID_ROW,
            scrollable: true,
            sortable: true,
            dataBound: function (imageCategoryView) {
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
                       if (data.imageText == '') {
                            row.find('.k-hierarchy-cell a').remove();
                       }
                    });
                    /*var windowGridDetails = this;
                        windowGridDetails.tbody.find(imageViewList.GRID_TABLE_ROW_VALUE).dblclick(function (imageCategoryView) {
                        var row = $(this); 
                        var data = dataSource.getByUid(row.data('uid')); 
                        localStorage.setItem(imageViewList.IMAGE_ID_VALUE, data.imageId);
                        localStorage.setItem(imageViewList.PHOTO_ALBUM_NAME_FIELD, data.photoAlbumName);     
                        localStorage.setItem(imageViewList.IMAGE_REGION_VALUE, data.imageRegionValue);
                        localStorage.setItem(imageViewList.IMAGE_ACTIVITY_VALUE, data.imageActivityValue);
                        commentShowDetails();
                    });*/
                },
            toolbar: kendo.template(jQuery("#templates").html()),
            detailTemplate: kendo.template($("#imageTranslationTemplateDetail").html()),
            detailInit: imageTranslationHistoryDetailTab,
            columns: imageDetailColumnList,
        });
wnd = jQuery("#window")
                        .kendoWindow({
                            width: imageViewList.KENDO_WINDOW_WIDTH,
                            height: imageViewList.KENDO_WINDOW_HIEGHT,
                            title: imageViewList.IMAGE_CATEGOTY_WINDOW_TITLE,
                            modal: true,
                            visible: false,
                            close: function(){
                        activityRegionIframe = document.getElementById(imageViewList.ACTIVITY_IFRAME_VALUE);
                        activityRegionIframeInnerDocument = activityRegionIframe.contentDocument || activityRegionIframe.contentWindow.document;
                        regionIframeInput = activityRegionIframeInnerDocument.getElementById(imageViewList.RESULT_TREEVIEW_REGION);
                        var regionId = regionIframeInput.innerHTML;
                        activityIframeInput = activityRegionIframeInnerDocument.getElementById(imageViewList.RESULT_TREEVIEW_ACTIVITY);
                        var activityId = activityIframeInput.innerHTML; 
                        var imageId=localStorage.getItem(imageViewList.IMAGE_ID_VALUE);
                        var albumName=localStorage.getItem(imageViewList.PHOTO_ALBUM_NAME_FIELD);
                        jQuery.ajax({
                            type: imageViewList.DATA_TYPE,
                            url: imageViewList.INSERT_ACTIVITY_REGION_URL,
                            data: {
                                imageId: imageId,
                                regionId: regionId,
                                activityId: activityId,
                                albumName:albumName
                            },
                            success: function (imageData) {
                                location.reload();
                            }
                        });
                    }   
                        }).data("kendoWindow");
detailsTemplate = kendo.template(jQuery("#template").html());
function commentShowDetails() {
    wnd.content(detailsTemplate(kendo.template(jQuery("#template").html())));
    wnd.center().open();                 
}

/**
 * Function is for display image translation history in detail tab view.
 * @param string imageDetailElement
 */
function imageTranslationHistoryDetailTab(e) {
    var originalId=e.data.imageId;
    localStorage.setItem('originalId',originalId);
    var $ = jQuery.noConflict();
    var detailRow = e.detailRow;
                    detailRow.find(".imageTranslationHistory").kendoGrid({
                        dataSource : {
                            pageSize : imageViewList.GRID_PAGE_SIZE,
                            transport : {
                                read : {
                                    url : imageViewList.IMAGE_TRANSLATION_HISTORY_LIST
                                            + imageViewList.FORWARD_SLASH_SEPARATORS
                                            + e.data.imageId,
                                    dataType : imageViewList.JSON_DATA_TYPE,
                                    type : imageViewList.DATA_TYPE
                                }
                            },
                        },

                        pageable : {
                            refresh : true,
                            pageSizes : true
                        },
                        scrollable : true,
                        sortable : true,
                        dataBound: function (e) {
                            var data = this.dataSource.data();
                                $.each(data, function (i, row) {
                                    var statusFontColor=row.get("statusFontColor");
                                var colorCode=row.get("colorCode");
                                var element = $('tr[data-uid="' + row.uid + '"] ');
                                element.css("background-color",colorCode);
                                element.css("color",statusFontColor);
                            });
                        },
                        columns : [ {
                            field : imageViewList.USER_NAME_FIELD,
                            title : imageViewList.USER_NAME_TITLE,
                            width : imageViewList.USER_NAME_FIELD_WIDTH
                        },{
                            field : "imageTranslationHistory",
                            title : 'Translation',
                            width : imageViewList.IMAGE_TRANSLATION_HISTORY_FIELD_WIDTH
                        },{
                            field : "imageStatusName",
                            title : "Status",
                            width : imageViewList.IMAGE_TRANSLATION_HISTORY_FIELD_WIDTH
                        },{
                            field : imageViewList.MODIFIED_DATE,
                            title : imageViewList.MODIFIED_DATE_TITLE,
                            width : imageViewList.USER_NAME_FIELD_WIDTH
                        }]
                    });
}
