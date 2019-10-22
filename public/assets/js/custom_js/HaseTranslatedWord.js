var translatedWordList = {
	WORD_TRANSLATION_LIST : "word_translation_list",
	WORD_DATE_TIME : "wordDate",
	WORDS_DROPDOWN_ID : '#wordsActionList',
	TRANSLATION_URL_REFERENCE : 'translationUrlReference',
	URL_REFERANCE_TITLE : 'Default Url',
	REFERANCE_URL_TEMPLATE : '<span id="urlToolTip" title="#=translationUrlReference#"><a target="false" href="#=translationUrlReference#">#=translationUrlReference.substr(0,10)#</a></span>',
	FILTER_DIV : "div.k-filter-help-text",
	DROPDOWN_SPAN : "span.k-dropdown:first",
	FILTER_VALUE_DISPLAY : "display",
	FILTER_VALUE_NONE : "none",
	TEXT_BOX_FIRST : ".k-textbox:first",
	KENDO_TEXTBOX : "k-textbox",
	KENDO_DROPDOWN_LIST : "kendoDropDownList",
	SELECT_ITEM_MESSAGE : "Select an item from the list:",
	WORD_COMMAND_COLUMN_WIDTH:50,
    TEXTAREA_START_TAG:'<textarea data-bind="value: ',
    TEXTAREA_END_TAG:'" style="height: 80px; color:black;"></textarea>',
    TRANSLATED_WORD_TEMPLATE : '<span id="urlToolTip" title="#=translatedWord#">#=translatedWord.substr(0,100)#</span>',
    WORD_TRANSLATION_MULTIPLE_ENTRIES:'words_translation_multiple_entries',
    APPROVE_MESSAGE_DEFAULT_VALUE:0,
    WORD_TRANSLATION_STATUS_TITLE:"Status",
    WORD_TRANSLATION_STATUS_FIELD:'wordTranslationStatus',
    SELECT_SENTENCE_STATUS_MESSAGE : "SELECT STATUS",
    TRANSLATED_WORD_COLUMN_WIDTH:100,
    TRANSLATION_STATUS_COLUMN_WIDTH:50,
    WORDS_APPROVE_COLUMN_WIDTH:60,
    ORIGINAL_WORD_COLUMN_WIDTH:80,
	GLOBAL_URL_FIELD:"globalUrlList",
	GLOBAL_URL_TITLE:"Global Url",
	GLOBAL_URL_COLUMN_WIDTH:100,
	CURRENT_TRASLATION_TITLE:"Current",
	SENTENCE_USER_COLUMN_WIDTH:35,
	SENTENCE_DATE_TIME_COLUMN_WIDTH:50,
	USER_NAME_TEMPLATE : '<span id="urlToolTip" title="#=userReference#">#=userReference.substr(0,20)#</span>',
	TRANSLATION_USER_COLUMN_WIDTH:50,
	GRID_ID : "#grid",
	GRID_ROW : "row",
	JSON_DATA_TYPE: "json",
	DATA_TYPE: "GET",
	GRID_PAGE_SIZE: 20,
	DROPDOWN_VALUE: "value",
	DROPDOWN_ID: "id",
	FLAG_VALUE_TITLE:'wordFlagValue',
	UPDATE_WORDS_NAME:"update_translation_sentence_details",
	FORWARD_SLASH_SEPARATORS : "/",
	DETAIL_COLUMNS_WIDTH : 60,
	EDIT_INLINE : "inline",
	TEXT_ALIGN_CENTER_VALUE:"text-align:center;",
	TRANSLATION_APPROVE_TITLE:'Status',
	WORD_DATE_TIME_TITLE : "Date/Time",
	ORIGINAL_WORD_FIELD : "original_word",
	TRANSLATED_WORD_TITLE : "Translated Word",
	ORIGINAL_WORD_TITLE:'Original Word',
	USER_ID : "userId",
	USER_NAME_TITLE:'User Name',
	USER_NAME:"userName",
	TRANSLATED_WORD : "translatedWord",
	USER_REFERENCE:'userReference',
}
var languageCode;
var wordColumnList = [];
var userName = localStorage.getItem('userName');
wordColumnList.push({
	field : translatedWordList.ORIGINAL_WORD_FIELD,
	title : translatedWordList.ORIGINAL_WORD_TITLE,
	encoded : false,
	width : translatedWordList.ORIGINAL_WORD_COLUMN_WIDTH
}, {
	field : translatedWordList.TRANSLATED_WORD,
	title : translatedWordList.CURRENT_TRASLATION_TITLE,
	width : translatedWordList.TRANSLATED_WORD_COLUMN_WIDTH,
	template : translatedWordList.TRANSLATED_WORD_TEMPLATE,
	editor : textareaEditor,
}, {
	field : translatedWordList.WORD_DATE_TIME,
	title : translatedWordList.WORD_DATE_TIME_TITLE,
	width : translatedWordList.DETAIL_COLUMNS_WIDTH,
}, {
	field : translatedWordList.USER_REFERENCE,
	title : translatedWordList.USER_NAME_TITLE,
	width : translatedWordList.TRANSLATION_USER_COLUMN_WIDTH,
	template : translatedWordList.USER_NAME_TEMPLATE,
}, {
	field : translatedWordList.TRANSLATION_URL_REFERENCE,
	title : translatedWordList.URL_REFERANCE_TITLE,
	template : translatedWordList.REFERANCE_URL_TEMPLATE,
	width : translatedWordList.WORD_COMMAND_COLUMN_WIDTH,
	encoded : false,
},{
	field : translatedWordList.FLAG_VALUE_TITLE,
	title : translatedWordList.TRANSLATION_APPROVE_TITLE,
	width : "40px"
},{
	field: "wordStatus",
    title: "Status",
    template: "#=(wordStatus)?'Enable':'Disable'#",
    width:"40px"

},{
	command : [{
        name: "edit",
        title:"Actions",
        text: {
            edit: "Edit",
            update: "Apply",
            cancel: "Cancel",
        }
    }],
	attributes : {style : translatedWordList.TEXT_ALIGN_CENTER_VALUE},
	width : translatedWordList.WORD_COMMAND_COLUMN_WIDTH,
});
if (userName == 'admin') {
    wordColumnList.push({
            field: "",
            title: "Actions",
            template:kendo.template(jQuery('#deleteTemplete').html()),
            width:"60px"
        });
}
var wordTranslationColumnList = [];
wordTranslationColumnList.push({
	field : translatedWordList.TRANSLATED_WORD,
	title : translatedWordList.TRANSLATED_WORD_TITLE,
	width : translatedWordList.TRANSLATED_WORD_COLUMN_WIDTH,
	template : translatedWordList.TRANSLATED_WORD_TEMPLATE,
	editor : textareaEditor,
},{
	field : translatedWordList.WORD_DATE_TIME,
	title : translatedWordList.WORD_DATE_TIME_TITLE,
	width : translatedWordList.SENTENCE_DATE_TIME_COLUMN_WIDTH,
},{
	field : translatedWordList.USER_REFERENCE,
	title : translatedWordList.USER_NAME_TITLE,
	width : translatedWordList.SENTENCE_USER_COLUMN_WIDTH,
},{
	field : translatedWordList.WORD_TRANSLATION_STATUS_FIELD,
	title : translatedWordList.WORD_TRANSLATION_STATUS_TITLE,
	width : translatedWordList.TRANSLATION_STATUS_COLUMN_WIDTH,
	editable : false,
});

function statusFilterValue(translationStatusValue){
    localStorage.setItem('filtedDefaultId',translationStatusValue);
    location.reload();
}
jQuery('.pull-right').click(function() {
    localStorage.clear();
});
/**
 * Function is display translated words history.
 */
	userKnownLanguageDropDown();
	var translatedWordElement = $(translatedWordList.GRID_ID)
			.kendoGrid(
					{
						dataSource : {
							pageSize : translatedWordList.GRID_PAGE_SIZE,
							transport : {
								read :{
									url : translatedWordList.WORD_TRANSLATION_LIST + translatedWordList.FORWARD_SLASH_SEPARATORS + languageCode + translatedWordList.FORWARD_SLASH_SEPARATORS
                                            + localStorage.getItem('filtedDefaultId'),
									dataType : translatedWordList.JSON_DATA_TYPE,
									type : translatedWordList.DATA_TYPE,
								},
								update: {
				                	url:translatedWordList.UPDATE_WORDS_NAME + translatedWordList.FORWARD_SLASH_SEPARATORS + languageCode,
				                	dataType:translatedWordList.JSON_DATA_TYPE,
									type:translatedWordList.DATA_TYPE,
									complete : function (wordsElement) {
										translatedWordElement.data("kendoGrid").dataSource.read();
				                    }
				            },
							},
							batch : false,
							schema : {								
								model : {
								id : translatedWordList.ORIGINAL_WORD_FIELD,
								fields : {
									original_word : {
										editable : false
									},
									wordDate : {
										editable : false
									},
									userReference : {
										editable : false
									},
									originalWordId : { 
										editable: true
									},
									wordFlagValue : {
										editable : false
									},
									translatedWord : {
										editable : true
									},
									translationUrlReference : {
										editable : false
									},
									globalUrlList : {
										editable : false
									},
									wordStatus:{
									    editable : false
									}
								}
							}
							},
							serverFiltering: true,
						},
						pageable : {
							refresh : true,
							pageSizes : true,
						},
						editable : translatedWordList.EDIT_INLINE,
						selectable : translatedWordList.GRID_ROW,
						toolbar: kendo.template(jQuery("#templates").html()),
						scrollable : true,
						sortable : true,
						detailTemplate: kendo.template($("#templateTranslatedDetail").html()),
                        detailInit: wordTranslationList,
						dataBound: function () {
							 var grid = translatedWordElement.data("kendoGrid");
				    		var gridData = grid.dataSource.view();
							    for (var i = 0; i < gridData.length; i++) {
							        var currentUid = gridData[i].uid;
							        if (gridData[i].wordEditPermission == ' ') {
							            var currenRow = grid.table.find("tr[data-uid='" + currentUid + "']");
							            var editButton = $(currenRow).find(".k-grid-edit");
							            editButton.hide();
							        }
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
			            },
						columns : wordColumnList,
					});

function textareaEditor(wordsContainer, wordsOptions) {
    $(translatedWordList.TEXTAREA_START_TAG + wordsOptions.field + translatedWordList.TEXTAREA_END_TAG)
        .appendTo(wordsContainer);
}
/**
 * Function is display the words all translation entries in Detail tab.
 * @param wordsElement
 */        
function wordTranslationList(wordsElement) {
    	var detailRow = wordsElement.detailRow;
                    detailRow.find(".translatedDetails").kendoGrid({
    						dataSource : {
    							pageSize : translatedWordList.GRID_PAGE_SIZE,
    							transport : {
    								read : {
    									url : translatedWordList.WORD_TRANSLATION_MULTIPLE_ENTRIES + translatedWordList.FORWARD_SLASH_SEPARATORS + languageCode + translatedWordList.FORWARD_SLASH_SEPARATORS + wordsElement.data.originalWordId + translatedWordList.FORWARD_SLASH_SEPARATORS + wordsElement.data.wordFlagValue,
    									dataType : translatedWordList.JSON_DATA_TYPE,
    									type : translatedWordList.DATA_TYPE
    								},
    								update: {
    				                	url:translatedWordList.UPDATE_WORDS_NAME + translatedWordList.FORWARD_SLASH_SEPARATORS + languageCode,
    				                	dataType:translatedWordList.JSON_DATA_TYPE,
    									type:translatedWordList.DATA_TYPE,
    									complete : function (translationElement) {
    										location.reload();
    				                    }
    				            },
    							},
    							batch : false,
    							schema : {								
    								model : {
    								id : translatedWordList.ORIGINAL_WORD_FIELD,
    								fields : {
    									translatedWord : {
    										editable : true
    									},
    									wordTranslationStatus : {
    										editable : false
    									},
    									wordDate : {
    										editable : false
    									},
    									userReference : {
    										editable : false
    									},
    									
    								}
    							}
    							}
    						},
    						pageable : {
    							refresh : true,
    							pageSizes : true
    						},
    						editable : translatedWordList.EDIT_INLINE,
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
    						scrollable : true,
    						sortable : true,
    						resizable : true,
						    columns :wordTranslationColumnList
    					});
    }

