var translateLanguageWordList = {
	WORD_LANGUAGE_TRANSLATION_LIST : "word_translation_language_list",
	ORIGINAL_WORD_ID:'originalWordId',
    TRANSLATED_WORD_TEMPLATE : '<span id="urlToolTip" title="#=translatedWord#">#=translatedWord.substr(0,100)#</span>',
    WORD_LANGUAGE_TRANSLATION_MULTIPLE_ENTRIES:'words_translation_language_details_list',
    WORD_TRANSLATION_STATUS_TITLE:"Status",
    WORD_TRANSLATION_STATUS_FIELD:'wordTranslationStatus',
    TRANSLATED_WORD_COLUMN_WIDTH:100,
    TRANSLATION_STATUS_COLUMN_WIDTH:50,
    WORDS_APPROVE_COLUMN_WIDTH:60,
    ORIGINAL_WORD_COLUMN_WIDTH:80,
	SENTENCE_USER_COLUMN_WIDTH:40,
	SENTENCE_DATE_TIME_COLUMN_WIDTH:50,
	USER_NAME_TEMPLATE : '<span id="urlToolTip" title="#=userReference#">#=userReference.substr(0,20)#</span>',
	TRANSLATION_USER_COLUMN_WIDTH:50,
	GRID_ID : "#grid",
	GRID_ROW : "row",
	JSON_DATA_TYPE: "json",
	DATA_TYPE: "GET",
	GRID_PAGE_SIZE: 20,
	FLAG_VALUE_TITLE:'wordFlagValue',
	FORWARD_SLASH_SEPARATORS : "/",
	TRANSLATION_APPROVE_TITLE:'Status',
	WORD_DATE_TIME_TITLE : "Date/Time",
	ORIGINAL_WORD_FIELD : "original_word",
	TRANSLATED_WORD_TITLE : "Translated Word",
	ORIGINAL_WORD_TITLE:'Original Word',
	USER_NAME_TITLE:'User Name',
	WORD_DATE_TIME : "wordDate",
	USER_NAME:"userName",
	TRANSLATED_WORD : "translatedWord",
	USER_REFERENCE:'userReference',
	INPUT_FIRST_RADIO_CLASS_VALUE:'input[class=firstRadio]:eq(',
    INPUT_LAST_RADIO_CLASS_VALUE:'input[class=secondRadio]:last',
    INPUT_RADIO_DISABLE_CLASS:'input[class=radioDisable]',
    ROUND_BRACKET_END:')',
    FIND_RADIO_BUTTON_CLASS: 'class',
    RADIO_BUTTON_DISBALE: 'radioDisable',
    RADIO_BUTTON_CLASS_DISABLED: 'disabled',
    RADIO_BUTTON_CHECK:'checked',
    FIND_INPUT_NAME_SECOND_COLUMN:'input[name=secondColumn]',
    FIND_INPUT_NAME_FIRST_COLUMN:'input[name=firstColumn]',
    INPUT_NAME_FIRST_COLUMN_RADIO_CHECKED:'input[name=firstColumn]:radio:checked',
    INPUT_NAME_SECOND_COLUMN_RADIO_CHECKED:'input[name=secondColumn]:radio:checked',
    FIND_TR_TAG:'tr',
}
function statusFilterValue(translationStatusValue){
    localStorage.setItem('filtedDefaultId',translationStatusValue);
    location.reload();
}
translationDifferenceWindow = jQuery("#languageTranslationWindow")
                        .kendoWindow({
                            title: "Translation Language Difference",
                            modal: true,
                            visible: false,
                            resizable: false,
                            width: 650,
                            height: 180, 
                        }).data("kendoWindow");

var translationDifferenceTemplate;
translationDifferenceTemplate = kendo.template(jQuery("#templateComments").html());
    function compareLanguageTranslationHistory() {
         var oldVersionNumber = jQuery(translateLanguageWordList.INPUT_NAME_FIRST_COLUMN_RADIO_CHECKED).closest(translateLanguageWordList.FIND_TR_TAG).children().eq(4).text();
         var newVersionNumber = jQuery(translateLanguageWordList.INPUT_NAME_SECOND_COLUMN_RADIO_CHECKED).closest(translateLanguageWordList.FIND_TR_TAG).children().eq(4).text();
         localStorage.setItem('oldVersionNumber',oldVersionNumber);
         localStorage.setItem('newVersionNumber',newVersionNumber);
         translationDifferenceWindow.content(translationDifferenceTemplate(kendo.template(jQuery("#templateComments").html())));
         translationDifferenceWindow.center().open();      
}
function wordTranslateLanguageDetailList() {
	var translatedWordElement = $(translateLanguageWordList.GRID_ID)
			.kendoGrid(
					{
						dataSource : {
							pageSize : translateLanguageWordList.GRID_PAGE_SIZE,
							transport : {
								read :{
									url : translateLanguageWordList.WORD_LANGUAGE_TRANSLATION_LIST + translateLanguageWordList.FORWARD_SLASH_SEPARATORS 
									+ localStorage.getItem('filtedDefaultId'),
									dataType : translateLanguageWordList.JSON_DATA_TYPE,
									type : translateLanguageWordList.DATA_TYPE,
								}
	
							}
						},
						pageable : {
							refresh : true,
							pageSizes : true,
						},
						editable : translateLanguageWordList.EDIT_INLINE,
						selectable : translateLanguageWordList.GRID_ROW,
						toolbar: kendo.template(jQuery("#templates").html()),
						scrollable : true,
						sortable : true,
						detailTemplate: kendo.template($("#templateTranslatedDetail").html()),
                        detailInit: wordLanguageTranslationList,
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
									field : translateLanguageWordList.ORIGINAL_WORD_FIELD,
									title : translateLanguageWordList.ORIGINAL_WORD_TITLE,
									encoded : false,
									width : translateLanguageWordList.ORIGINAL_WORD_COLUMN_WIDTH
								}, {
									field : translateLanguageWordList.TRANSLATED_WORD,
									title : "Current",
									width : translateLanguageWordList.TRANSLATED_WORD_COLUMN_WIDTH,
									template : translateLanguageWordList.TRANSLATED_WORD_TEMPLATE,
								}, {
									field : translateLanguageWordList.WORD_DATE_TIME,
									title : translateLanguageWordList.WORD_DATE_TIME_TITLE,
									width : 50,
								}, {
									field : translateLanguageWordList.USER_REFERENCE,
									title : translateLanguageWordList.USER_NAME_TITLE,
									width : translateLanguageWordList.TRANSLATION_USER_COLUMN_WIDTH,
									template : translateLanguageWordList.USER_NAME_TEMPLATE,
								},{
									field : translateLanguageWordList.FLAG_VALUE_TITLE,
									title : translateLanguageWordList.TRANSLATION_APPROVE_TITLE,
									width : translateLanguageWordList.WORDS_APPROVE_COLUMN_WIDTH,
								}],
					});
}       
function wordLanguageTranslationList(wordsElement) {
		var originalId=wordsElement.data.originalWordId;
		localStorage.setItem('originalId',originalId);
    	var detailRow = wordsElement.detailRow;
                    detailRow.find(".translatedDetails").kendoGrid({
    						dataSource : {
    							pageSize : translateLanguageWordList.GRID_PAGE_SIZE,
    							transport : {
    								read : {
    									url : translateLanguageWordList.WORD_LANGUAGE_TRANSLATION_MULTIPLE_ENTRIES + translateLanguageWordList.FORWARD_SLASH_SEPARATORS + wordsElement.data.originalWordId,
    									dataType : translateLanguageWordList.JSON_DATA_TYPE,
    									type : translateLanguageWordList.DATA_TYPE
    								}
    							},
    							serverFiltering: true,
    						},
    						pageable : {
    							refresh : true,
    							pageSizes : true
    						},
    						dataBound: function (e) {
    						jQuery(translateLanguageWordList.INPUT_FIRST_RADIO_CLASS_VALUE + 0 + translateLanguageWordList.ROUND_BRACKET_END).attr(translateLanguageWordList.FIND_RADIO_BUTTON_CLASS, translateLanguageWordList.RADIO_BUTTON_DISBALE);
                            jQuery(translateLanguageWordList.INPUT_LAST_RADIO_CLASS_VALUE).attr(translateLanguageWordList.FIND_RADIO_BUTTON_CLASS, translateLanguageWordList.RADIO_BUTTON_DISBALE);
                            jQuery(translateLanguageWordList.INPUT_RADIO_DISABLE_CLASS).attr(translateLanguageWordList.RADIO_BUTTON_CLASS_DISABLED, true);
                            jQuery(translateLanguageWordList.FIND_INPUT_NAME_SECOND_COLUMN).eq(0).attr(translateLanguageWordList.RADIO_BUTTON_CHECK, translateLanguageWordList.RADIO_BUTTON_CHECK);
                            jQuery(translateLanguageWordList.FIND_INPUT_NAME_FIRST_COLUMN).last().attr(translateLanguageWordList.RADIO_BUTTON_CHECK, translateLanguageWordList.RADIO_BUTTON_CHECK);
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
						    columns :[{
									field : translateLanguageWordList.TRANSLATED_WORD,
									title : translateLanguageWordList.TRANSLATED_WORD_TITLE,
									width : translateLanguageWordList.TRANSLATED_WORD_COLUMN_WIDTH,
									template : translateLanguageWordList.TRANSLATED_WORD_TEMPLATE,
								},{
									field : translateLanguageWordList.WORD_DATE_TIME,
									title : translateLanguageWordList.WORD_DATE_TIME_TITLE,
									width : translateLanguageWordList.SENTENCE_DATE_TIME_COLUMN_WIDTH,
								},{
									field : translateLanguageWordList.USER_REFERENCE,
									title : translateLanguageWordList.USER_NAME_TITLE,
									width : translateLanguageWordList.SENTENCE_USER_COLUMN_WIDTH,									
								},{
									field : translateLanguageWordList.WORD_TRANSLATION_STATUS_FIELD,
									title : translateLanguageWordList.WORD_TRANSLATION_STATUS_TITLE,
									width : translateLanguageWordList.TRANSLATION_STATUS_COLUMN_WIDTH,
								},{
									field : "translationVersion",
									title : "Translation Version",
									width : 65,
								},{
                            		title: "<input id='translationId' class='k-button' name='compareVersion' onclick='compareLanguageTranslationHistory();' type='button' value='Compare Translation'>",
                            		template: '<input style="margin:0 25% 0 20%" class="firstRadio" id="firstColumn" name="firstColumn" type="radio"/><input class="secondRadio" id="secondColumn" name="secondColumn" type="radio"/>',
                            		width: 90
                        		}]
    					});
    }