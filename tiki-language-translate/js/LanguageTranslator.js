var commonConstants = {
    'DATA_SOURCE_TYPE': 'POST',
    'DATA_TYPE': 'json',
    'FETCH_ALL_LANGUAGES_URL': 'LanguageTranslation.php/allLanguage',
    'ENGLISH_DEFAULT_LANGUAGE': 'Select Language',
    'ENGLISH_LANGUAGE_CODE': 'en',
    'LANGUAGE_FILES_DIRECTORY': 'language-translate-storage/',
    'LANGUAGE_FILE_NAME_DIRECTORY': '/TranslationContents.csv',
    'COOKIE_EXPIRE_DAY': 7,
    'COOOKIE_HOUR': 24,
    'COOKIE_MINUTES': 60,
    'COOKIE_MILISECOND': 1000,
    'COOKIE_EXPIRE_ATTRIBUTE': '; expires=',
    'ASSIGN_EMPTY_VARIABLE': '',
    'ZERO_KEY_INDEX': 0,
    'EXTRACT_STARTING_ARGUMENT': 1,
    'COOKIE_EQQUAL_ATTRIBUTE': '=',
    'COOKIE_PATH_ATTRIBUTE': '; path=/',
    'ASSIGN_WHITESPACE': ' ',
    'SEMICOLON_SIGN': ';',
    'COOKIE_LANGUAGE_NAME': 'languageCode',
    'KENDO_TRANSLATION_POPUP_WINDOW_WIDTH': 'auto',
    'KENDO_POPUP_WINDOW_TITLE': 'Language Translation Popup Window',
    'TRANSLATION_WINDOW_LEFT': "300px",
    'KENDO_WINDOW_CLOSE': 'Close',
    'JQUERY_FIND_BODY': 'body',
    'JQUERY_DOUBLE_CLICK_BODY': 'dblclick',
    'HTML_INPUT_BUTTON_TYPE': 'button',
    'HTML_INPUT_SUBMIT_TYPE': 'submit',
    'HTML_INPUT_RESET_TYPE': 'reset',
    'HTML_INPUT_TEXT_TYPE': 'text',
    'HTML_INPUT_SEARCH_TYPE': 'search',
    'DATA_SOURSE_TYPE': 'post',
    'ADD_UPDATE_TRANSLATION_CONTENT_URL': 'AddUpdateTranslation.php?callFunction=',
    'KENDO_TRANSLATION_POPUP_WINDOW_HEIGHT': 'auto',
    'TRANSLATION_WINDOW_TOP': "10px",
    'ADD_UPDATE_TRANSLATION_BY_USER': 'addUpdateTranslationByUser',
    'CHECK_TRANSLATION_COMPARE_HISTORY': 'translationCompareHistory',
    'KENDO_TRANSLATION_WINDOW_TITLE': 'Translation History window',
    'FIELDS_TYPE': 'string',
    'KENDO_WINDOW_PAGE_SIZE': 7,
    'TAB_REVISION_GRID_HEIGHT': 400,
    'GRID_BUTTON_COUNT': 5,
    'FIND_RADIO_BUTTON_CLASS': 'class',
    'RADIO_BUTTON_DISBALE': 'radioDisable',
    'RADIO_BUTTON_CLASS_DISABLED': 'disabled',
    'USERNAME_FIELD': 'tikiUserName',
    'USERNAME_TITLE': 'Username',
    'USERNAME_WIDTH': 60,
    'IP_ADDRESS_FIELD': 'ipAddress',
    'IP_ADDRESS_TITLE': 'Ip Address',
    'IP_ADDRESS_WIDTH': 50,
    'LAST_MODIFY_PAGE_FIELD': 'lastModifyPage',
    'LAST_MODIFY_PAGE_TITLE': 'Last Modified',
    'LAST_MODIFY_PAGE_WIDTH': 60,
    'TIKI_PAGE_VERSION_FIELD': 'tikiPageVersion',
    'TIKI_PAGE_VERSION_TITLE': 'Version',
    'TIKI_PAGE_VERSION_WIDTH': 30,
    'COMPARE_VERSION_SIZE': 60,
    'COMPARE_TRANSLATION_TIKI_HISTORY': 'compareTranslationTikiHistory',
    'OLD_DATA_REVISION_FIELD': 'oldDataRevision',
    'OLD_DATA_REVISION_TITLE': 'Old Detail Tab Value',
    'COMMON_SIZE': 200,
    'NEW_DATA_REVISION_FIELD': 'newDataRevision',
    'NEW_DATA_REVISION_TITLE': 'New Detail Tab Value',
    'THIRD_KEY_INDEX': 3,
    'TRANSLATION_TIKI_PAGE_VERSION': 'translationTikiPageVersion',
    'JQUERY_BODY_CHANGE_EVENT': 'change',
    'TRANSLATION_HISTORY_WINDOW_WITH': "900px",
    'TRANSLATION_HISTORY_WINDOW_HEIGHT': "400px",
    'TRANSLATION_HISTORY_GRID_HEIGHT': "375px",
    'TRANSLATION_COMPARE_HISTORY_GRID_HEIGHT': "360px",
    'HTML_INPUT_PLACEHOLDER_TYPE': 'placeholder',
    'FIND_INPUT_TAG': 'input',
    'FIND_TEXTAREA_TAG': 'textarea',
    'KEYBOARD_IMAGE_FILE_PATH': 'image/keyboard.gif',
    'KENDO_TOOL_TIP_POSITION': 'top',
    'KENDO_VIRTUAL_TOOL_TIP_MESSAGE': 'Turn On Virtual Keyboard',
    'KENDO_BACK_HISTORY_TOOL_TIP_MESSAGE': 'Go Back To Translation History',
    'ADD_UPDATE_WINDOW_TIME_OUT': 1700,
    'IMAGE_LOADER_FILE_PATH':'image/ajaxLoader.gif',
    'TRANSLATION_HISTORY_TOOL_TIP_MESSAGE':'See translation history',
    'HREF_TAG':'a',
    'CHECK_MOUSE_RIGHT_CLICK':3,
    'INPUT_TYPE':'type',
    'ADD_UPDATE_TRANSLATION_WINDOW_DIV_ID':'#addUpdateTranslationWindow',
    'ORIGINAL_BASE_SENTENCE_div_id':'#originalBaseSentence',
    'CHECK_TRANSLATION_HISTORY_EXIST_DIV_ID':"#checkTranslationHistoryExist",
    'SPAN_TAG':'span',
    'ORIGINAL_SENTENCE_CLASS':'.originalSentence-',
    'HREF_TAG_CUSTOM_ATTRIBITE':'a[languagetranslate]',
    'SPAN_TAG_CUSTOM_ATTRIBITE':'span[languagetranslate]',
    'FIND_INPUT_TYPE_RADIO':'#translationHistoryGrid input[type=radio]',
    'FIND_INPUT_NAME_FIRST_COLUMN':'input[name=firstColumn]',
    'RADION_BUTTON_CHECKED':':checked',
    'FIND_INPUT_FIRST_COLUMN_LAST':'input[name=firstColumn]:last',
    'FIND_FIRST_INPUT_RADIO_SECOND_COLUMN':'input[name=secondColumn]:eq("',
    'END_ROUND_BRACKET':'")',
    'FIND_INPUT_NAME_SECOND_COLUMN':'input[name=secondColumn]',
    'KENDO_WINDOW':"kendoWindow",
    'NEW_TRANSLATION_INPUT_TEXTAREA_DIV_ID':'#newTranslationInputTextarea',
    'FIND_IMAGE_TAG':'img[id="displayVirtualKeyboard"]',
    'VIRTUAL_KEYBOARD_DIV_ID':'#virtualKeyboard',
    'CSS_DISPLAY_STYLE':'display',
    'CSS_DISPLAY_VALUE_STYLE':'block',
    'VIRTUAL_KEYBOARD_OFF_MESSAGE':'Turn Off Virtual Keyboard',
    'TOOLTIP_CONTENT_CLASS':'.k-tooltip-content',
    'FIND_DIV_ID':'div #',
    'ORIGINAL_VALUES_CLASS':'-originalValues',
    'TEXTAREA_NAME_NEW_TRANSLATION':'textarea[name="newTranslation-',
    'END_SQUARE_BRACKET':'"]',
    'DISPLAY_VIRTUAL_KEYBOARD_DIV_ID':"#displayVirtualKeyboard",
    'DATA_VIRTUAL_KEYBOARD_LAYOUT':'data-virtualKeyboard-layout',
    'TRANSLATION_HISTORY_WINDOW_DIV_ID':"#translationHistoryWindow",
    'TRANSLATION_HISTORY_GRID_DIV_ID':"#translationHistoryGrid",
    'INPUT_FIRST_RADIO_CLASS_VALUE':'input[class=firstRadio]:eq(',
    'INPUT_LAST_RADIO_CLASS_VALUE':'input[class=secondRadio]:last',
    'INPUT_RADIO_DISABLE_CLASS':'input[class=radioDisable]',
    'TIKI_PAGE_VERSION_DIV_ID':'#tikiPageVersion',
    'ROUND_BRACKET_END':')',
    'BACK_TO_TRANSLATION_HISTORY_BUTTON_DIV_ID':"#backToTranslationHistoryButton",
    'TRANSLATION_SUBMIT_BUTTON_CLASS':'.translationSubmitButton',
    'IMAGE_TAG_WITH_SRC':'<img src="',
    'IMAGE_TAG_ALT_WIDTH_HEIGHT':'" alt="not display" width="15" height="15"/>',
    'FIND_TRANSLATION_SUBMIT_BUTTON_CLASS_LAST_CHILD':'.translationSubmitButton img:last-child',
    'MULTI_LANGUAGE_DROPDOWN_DIV_ID':'#multiLanguageDropdown',
    'LANGUAGE_NAME':"language_name",
    'LANGUAGE_CODE':"language_code",
    'KENDO_DROPDOWN_LIST':"kendoDropDownList",
    'FIND_LANGUAGE_TRANSLATE_CUSTOM_ATTRIBUTE':'[languagetranslate="',
    'MULTI_LANGUAGE_DROPDOWN_DIV_ID':"#multiLanguageDropdown",
    'INPUT_VALUE':'value',
    'FIND_INPUT_NAME_FIRST_COLUMN_FIRST_RADIO':'input[name=firstColumn]:eq("',
    'TRANSLATION_COMPARE_HISTORY_GRID':"#translationCompareHistoryGrid",
    'ON_BODY_CLICK':'click',
    'CSS_DISPLAY_NONE':'none',
    'INPUT_NAME_FIRST_COLUMN_RADIO_CHECKED':'input[name=firstColumn]:radio:checked',
    'INPUT_NAME_SECOND_COLUMN_RADIO_CHECKED':'input[name=secondColumn]:radio:checked',
    'FIND_TR_TAG':'tr',
    'GERMAN_SWITZERLAND_LANGUAGE_CODE': 'de_ch',
    'GERMAN_GERMANY_LANGUAGE_CODE': 'de_de',
    'CHINESE_TRADITIONAL_LANGUAGE_CODE': 'zh_tw',
    'CHINESE_SIMPLIFIED_LANGUAGE_CODE': 'zh_cn',
    'FINNISH_LANGUAGE_CODE': 'fi_fi',
    'NORWEGIAN_LANGUAGE_CODE': 'nb_no',
    'HAITIAN_CREOLE_LANGUAGE_CODE': 'ht_ht',
    'FRENCH_LANGUAGE_CODE': 'fr_fr',
    'FIND_INPUT_BUTTON_HREF_TAG':"input[type='button'], input[type='submit'], option, button, a, ul li span, ul li, span",
    'HTML_PLACEHOLDER_ATTRIBUTE':'placeholder',
    'FIND_DOT_SEPARATER_REGEX': /[\.,?\!\n]+/g,
    'ADD_UPDATE_TRANSLATION_WINDOW_DIV':"<div id='addUpdateTranslationWindow' style='width:900px;'></div>",
    'TRANSLATION_HISTORY_WINDOW_DIV':"<div id='translationHistoryWindow'></div>",
    'TRANSLATION_HISTORY_BUTTON_DIV':"<div style='width:4%' id='checkTranslationHistoryExist'><a class='k-button' onClick=translationHistoryButton()><span class='k-icon k-i-refresh'></span></a></div>",
    'COMPARE_TRANSLATION_TIKI_HISTORY_INPUT_TAG':"<input id='translationId' class='k-button' name='compareVersion' onclick='compareTranslationTikiHistory();' type='button' value='Compare Version'>",
    'TRANSLATION_HISTORY_GRID_TEMPLATE':"<div id='backToTranslationHistoryButton' style='display:none;width:30px;height22px'><a onclick='backToTranslationHistoryWindow()' class='k-button'><span class='k-icon k-i-arrow-w'></span></a></div><div id='translationHistoryGrid'></div><div id='translationCompareHistoryGrid'></div>",
    'DISPLAY_FOR_SELECT_RADIO_BUTTONS_TEMPLATE':'<input style="margin:0 25% 0 20%" class="firstRadio" id="firstColumn" name="firstColumn" type="radio"/><input class="secondRadio" id="secondColumn" name="secondColumn" type="radio"/>',
    'ORIGINAL_SENTENCE_DIV_TEMPLATE':"<div id='originalBaseSentence' style='width:50%;float:left;'><div style='text-align:left;font-size:17px'>Original Sentence :</div><div style='margin:0.1%;padding:1%;background-color:lightgray;text-align:left' name='oldTextArea' id='",
    'DISPLAY_VIRTUAL_IMAGE_TAG_TEMPLATE':"-originalValues'></div><img id='displayVirtualKeyboard' style='cursor:pointer;float:left' width='22' height='22' src='",
    'DISPLAY_CURRENT_NEW_TRANSLATION_TEMPLATE':"' alt='not display'></div><div style='width:49%;margin:.1% 0 0 .5%;float:left;' id='translationValues'><div style='text-align:left;font-size:17px'>current status: <span id='currentStatus'></span>",
    'CURRENT_STATUS_DISPLAY':"</div><textarea ondblclick='textAreaDoubleClickCheck(this.value)' onclick='singleClickForTextArea()' style='resize: none;width:435px;height:100%;margin-bottom:1%;-webkit-user-select: none;-moz-user-select: none;-ms-user-select: none;user-select: none;' id='newTranslationInputTextarea' name='newTranslation-",
    'TEXT_BOX_CLASS':"' class='k-textbox'>",
    'VIRTUL_KEYBOARD_UPDATE_TRANSLATION_FUNCTION_TEMPLATE':"</textarea></div><div class='displayVirtualKeyboard'><div class='translationSubmitButton' style='float:right;margin-right:.4%;width:21.5%'><a class='k-button' onClick='addUpdateTranslation()' style='margin-right:3%;float:left !important'>Submit Translation</a></div></div>",
    'RADIO_BUTTON_CHECK':'checked',
    'OLD_TEXT_AREA_VALUE':'oldTextAreaValue',
    'OLD_TEXT_AREA_DIV':'div[name=oldTextArea]',
    'UNDEFINED_VALUE':'undefined',
    'OLD_TEXT_AREA_DIV_NAME':"<div name='oldTextArea'>",
    'OLD_TEXT_AREA_DIV_END':"</div>",
    'OLD_TEXT_AREA_NAME':"[name='oldTextArea']",
    'TRANSLATION_STATUS_DISPLAY': 'translationStatusDisplay',
    'HTML_DATA_TYPE':'html',
    'TOOLTIP_HEIGHT_WIDTH':'250',
    'TOOLTIP_HEIGHT_HEIGHT':'40',
    'SENTENCE_HISTORY_WINDOW_DIV_ID':"#sentenceHistoryWindow",
    'SENTENCE_HISTORY_WINDOW_DIV':"<div id='sentenceHistoryWindow'></div>",
    'SENTENCE_KENDO_WINDOW_WIDTH':"800",
    'SENTENCE_KENDO_WINDOW_HEIGHT':"300",
    'SENTENCE_HISTORY_KENDO_WINDOW_TITLE':"Sentence History Revision",
    'SENTENCE_TRANSLATION_HISTORY': 'sentenceHistoryList',
    'SENTENCE_HISTORY_ID_GRID':"<div id='sentenceHistoryGrid'></div>",
    'SENTENCE_HISTORY_GRID_DIV_ID':"#sentenceHistoryGrid",
    'SENTENCE_KENDO_WINDOW_PAGE_SIZE': 5,
    'SENTENCE_STATUS_WIDTH': 30,
    'SENTENCE_TRANSLATION_TITLE':"Translation",
    'SENTENCE_STATUS_TITLE':"Sentence Status",
    'WORD_TRANSLATION_STATUS':"wordTranslationStatus",
    'TRANSLATION_VALUE_FIELD':"newTranslateValue",
    'USER_REFERENCE_FIELD':'userReference',
    'USER_NAME_TITLE':'User Name',
    'SENTENCE_TRANSLATION_TEMPLETE':"<b><span style='color:#=colorCode#'>#=wordTranslationStatus#</span></b>",
    'DATE_TIME_TITLE':"Date/Time",
    'DATE_TIME_FIELD':"sentenceDateTime",
    'DATE_TIME_COLUMN_WIDTH': 40,
    'CURRENT_STATUS_NAME':"#currentStatus",
    'SENTENCE_TIME_ZONE':'sentenceTimeZone',
    'TIME_ZONE_TITLE':"Time Zone",
    'TIME_ZONE_COLUMN_WIDTH': 25,
    'SENTENCE_TRANSLATION_WIDTH': 40,
    'TRANSLATION_USER_NAME':'translationUserName',
    'MAGENTO_USER_NAME_URL':"/magento/viewUserSession.php",
    'TRANSLATION_GUEST_USER':'Guest',
    'MAGENTO_COMPARE_STRING':/magento.*/,
    'EVENTBOT_COMPARE_STRING':/eventbot.*/,
    'GET_DATA_TYPE': 'GET',
    'EVENTBOT_USER_NAME_URL':"/eventbot/public/users/user-detail",
};
var isChanged = false;
var languagePackage = new languagePackage(commonConstants.ENGLISH_LANGUAGE_CODE);
var languageCodeName;
var originalSentenceValue;
var sentenceReference;
var htmlElement;
var itSelfElement;
var originalHtmlContents;
var countKeyboardClick = commonConstants.ZERO_KEY_INDEX;
var bingTranslationToken = false;
var displayCurrentTranslation;

function singleClickForTextArea()
{
    var oldTextAreaValue = $jQuery(commonConstants.OLD_TEXT_AREA_DIV).html();
    if(typeof(oldTextAreaValue) != commonConstants.UNDEFINED_VALUE) {
        localStorage.setItem(commonConstants.OLD_TEXT_AREA_VALUE, oldTextAreaValue);
    }
}
function textAreaDoubleClickCheck(textAreaValue){
    var currentTextAreaValue=$jQuery(commonConstants.OLD_TEXT_AREA_DIV).html();
    $jQuery(commonConstants.NEW_TRANSLATION_INPUT_TEXTAREA_DIV_ID).html(textAreaValue);
    var setTextAreaValue = localStorage.getItem(commonConstants.OLD_TEXT_AREA_VALUE);
    $jQuery(commonConstants.OLD_TEXT_AREA_DIV).replaceWith(commonConstants.OLD_TEXT_AREA_DIV_NAME+setTextAreaValue+commonConstants.OLD_TEXT_AREA_DIV_END);
}
$jQuery(document).ready(function () {
    $jQuery(document.body).append($jQuery(commonConstants.ADD_UPDATE_TRANSLATION_WINDOW_DIV));
    $jQuery(document.body).append($jQuery(commonConstants.TRANSLATION_HISTORY_WINDOW_DIV));
    $jQuery(document.body).append($jQuery(commonConstants.SENTENCE_HISTORY_WINDOW_DIV));
    $jQuery(document.body).append($jQuery(commonConstants.SENTENCE_HISTORY_ID_GRID));
    var cookieName = commonConstants.COOKIE_LANGUAGE_NAME;
    languageCodeName = readCookie(cookieName);
    if (!languageCodeName) {
        languageCodeName = commonConstants.ENGLISH_LANGUAGE_CODE;
    }

    $jQuery(commonConstants.MULTI_LANGUAGE_DROPDOWN_DIV_ID).kendoDropDownList({
        optionLabel: commonConstants.ENGLISH_DEFAULT_LANGUAGE,
        dataTextField: commonConstants.LANGUAGE_NAME,
        dataValueField: commonConstants.LANGUAGE_CODE,
        dataSource: {
            transport: {
                read: {
                    dataType: commonConstants.DATA_TYPE,
                    url: filePathConstants.FILEPATH_URL + commonConstants.FETCH_ALL_LANGUAGES_URL,
                    type: commonConstants.DATA_SOURCE_TYPE
                }
            }
        },
        value: languageCodeName,
        change: function () {
            var languageValues = $jQuery(commonConstants.MULTI_LANGUAGE_DROPDOWN_DIV_ID).data(commonConstants.KENDO_DROPDOWN_LIST);
            languageCodeName = languageValues.value();
            if (!languageCodeName) {
                languageCodeName = commonConstants.ENGLISH_LANGUAGE_CODE;
            }
            var cookieExpireDays = commonConstants.COOKIE_EXPIRE_DAY;
            setCookieLanguageName(cookieName, languageCodeName, cookieExpireDays);
            window.location.reload();
        }
    });

    $jQuery(commonConstants.JQUERY_FIND_BODY).on(commonConstants.JQUERY_DOUBLE_CLICK_BODY, commonConstants.FIND_LANGUAGE_TRANSLATE_CUSTOM_ATTRIBUTE + languageCodeName + commonConstants.END_SQUARE_BRACKET, function (textNodeElement) {
        if (textNodeElement.target == this) {
            bingTranslationToken = true;
            var originalSentence;
            htmlElement = $jQuery(this);
            originalHtmlContents = htmlElement.html();
            itSelfElement = jQuery(htmlElement).clone().children().remove().end();
            var tagElementName = this.tagName;
            var tagElementType = this.type;
            sentenceReference = $jQuery(this).next().text();
            if (tagElementName.toLowerCase() == commonConstants.FIND_INPUT_TAG) {
                switch (htmlElement.attr(commonConstants.INPUT_TYPE)) {
                case commonConstants.HTML_INPUT_BUTTON_TYPE:
                case commonConstants.HTML_INPUT_SUBMIT_TYPE:
                case commonConstants.HTML_INPUT_RESET_TYPE:
                    originalSentence = itSelfElement.val();
                    break;
                case commonConstants.HTML_INPUT_PLACEHOLDER_TYPE:
                    var placeholderValue = itSelfElement.attr(commonConstants.HTML_PLACEHOLDER_ATTRIBUTE);
                    if (placeholderValue) {
                        originalSentence = placeholderValue;
                    }
                    break;
                case commonConstants.HTML_INPUT_TEXT_TYPE:
                case commonConstants.HTML_INPUT_SEARCH_TYPE:
                    var placeholderValue = itSelfElement.attr(commonConstants.HTML_PLACEHOLDER_ATTRIBUTE);
                    if (placeholderValue) {
                        originalSentence = placeholderValue;
                    }
                    var inputTypeValue = itSelfElement.attr(commonConstants.INPUT_VALUE);
                    if (inputTypeValue) {
                        originalSentence = inputTypeValue;
                    }
                    break;
                }
                if (htmlElement.attr(commonConstants.HTML_PLACEHOLDER_ATTRIBUTE)) {
                    originalSentence = itSelfElement.attr(commonConstants.HTML_PLACEHOLDER_ATTRIBUTE);
                }
            } else if (tagElementName.toLowerCase() == commonConstants.FIND_TEXTAREA_TAG) {
                var textareaPlaceholder = $jQuery(htmlElement).attr(commonConstants.HTML_PLACEHOLDER_ATTRIBUTE);
                var textareaValue = $jQuery(htmlElement).val();
                if (textareaPlaceholder) {
                    originalSentence = $jQuery(htmlElement).attr(commonConstants.HTML_PLACEHOLDER_ATTRIBUTE);
                }
                if (textareaValue) {
                    originalSentence = $jQuery(htmlElement).val();
                }

            } else {
                originalSentence = itSelfElement.text();
            }
            originalSentenceValue = originalSentence.trim();
            if (originalSentenceValue) {
                
                editTranslationButton();
                displayCurrentTranslation = originalSentenceValue.match(commonConstants.FIND_DOT_SEPARATER_REGEX);
            }

            var sentenceReferenceNew = $jQuery(commonConstants.OLD_TEXT_AREA_NAME).html();
            checkTranslationHistoryExist(sentenceReferenceNew);
            $jQuery.ajax({
                url: filePathConstants.FILEPATH_URL + commonConstants.ADD_UPDATE_TRANSLATION_CONTENT_URL + commonConstants.TRANSLATION_STATUS_DISPLAY,
                type: commonConstants.DATA_SOURCE_TYPE,
                dataType: commonConstants.HTML_DATA_TYPE,
                data: {
                    languageCodeName: languageCodeName,
                    originalSentenceValue: sentenceReferenceNew,
                },
                success: function (updateTranslationResponse) {
                    var statusList = updateTranslationResponse.split('_');
                    var statusName = statusList[0];
                    var statusColor = statusList[1];
                    if(statusName != ''){
                        $jQuery(commonConstants.CURRENT_STATUS_NAME).html('<a onclick="sentenceHistoryList()"><b><span style="color:'+ statusColor +'">' + statusName +'</span></b></a>');
                   }else{
                        $jQuery(commonConstants.CURRENT_STATUS_NAME).html('<b><span style="color:blue">No Status</span></b>');
                   }
                    }
    });
            if (document.URL.match(commonConstants.MAGENTO_COMPARE_STRING)) {
            $jQuery.ajax({
                url: commonConstants.MAGENTO_USER_NAME_URL,
                type: commonConstants.DATA_SOURCE_TYPE,
                dataType: commonConstants.HTML_DATA_TYPE,
                success: function (translationUserName) {
                    localStorage.setItem(commonConstants.TRANSLATION_USER_NAME, translationUserName);
                    }
            });
            } else if(document.URL.match(commonConstants.EVENTBOT_COMPARE_STRING)){
                $jQuery.ajax({
                    url: commonConstants.EVENTBOT_USER_NAME_URL,
                    type: commonConstants.GET_DATA_TYPE,
                    dataType: commonConstants.HTML_DATA_TYPE,
                    success: function (translationUserName) {
                        localStorage.setItem(commonConstants.TRANSLATION_USER_NAME, translationUserName);
                        }
                });
            }
            else {
                localStorage.setItem(commonConstants.TRANSLATION_USER_NAME,commonConstants.TRANSLATION_GUEST_USER);
            }
            
        }
    });

    $jQuery(commonConstants.FIND_INPUT_BUTTON_HREF_TAG).mousedown(function(textNodeElement){
        if(textNodeElement.which==commonConstants.CHECK_MOUSE_RIGHT_CLICK){
            bingTranslationToken = true;
            htmlElement = $jQuery(this);
            originalHtmlContents = htmlElement.html();
            var checkCurrentElement = textNodeElement.target;
            var findElementAttribute = textNodeElement.currentTarget.attributes[commonConstants.ZERO_KEY_INDEX];
            var checkContainChildElement = $jQuery(textNodeElement.target).has(commonConstants.SPAN_TAG);
            var findNextElement = $jQuery(textNodeElement.target).find(commonConstants.FIND_LANGUAGE_TRANSLATE_CUSTOM_ATTRIBUTE + languageCodeName + commonConstants.END_SQUARE_BRACKET);
            if(findNextElement.length==commonConstants.ZERO_KEY_INDEX){
                originalSentenceValue = $jQuery(textNodeElement.target).text();
                sentenceReference = $jQuery(textNodeElement.target).next().text();
            }
            else{
                originalSentenceValue = findNextElement.text();
                sentenceReference = $jQuery(textNodeElement.target).find(commonConstants.ORIGINAL_SENTENCE_CLASS + languageCodeName).text();
            }
            if(findElementAttribute.value==commonConstants.HTML_INPUT_BUTTON_TYPE||findElementAttribute.value==commonConstants.HTML_INPUT_SUBMIT_TYPE){
                if(checkContainChildElement.length>commonConstants.ZERO_KEY_INDEX){
                    originalSentenceValue = $jQuery(textNodeElement.target).find(commonConstants.FIND_LANGUAGE_TRANSLATE_CUSTOM_ATTRIBUTE + languageCodeName + commonConstants.END_SQUARE_BRACKET).text();
                }
                else{
                    originalSentenceValue = $jQuery(textNodeElement.target).val();
                }
            }
            
            if($jQuery(checkCurrentElement).is(commonConstants.HREF_TAG_CUSTOM_ATTRIBITE)){
                if(checkContainChildElement.length>commonConstants.ZERO_KEY_INDEX){
                    originalSentenceValue = $jQuery(textNodeElement.target).find(commonConstants.FIND_LANGUAGE_TRANSLATE_CUSTOM_ATTRIBUTE + languageCodeName + commonConstants.END_SQUARE_BRACKET).text();
                }
                else{
                    originalSentenceValue = $jQuery(textNodeElement.target).text();
                }
            }
            if($jQuery(checkCurrentElement).is(commonConstants.SPAN_TAG_CUSTOM_ATTRIBITE)){
                if(checkContainChildElement.length>commonConstants.ZERO_KEY_INDEX){
                    originalSentenceValue = $jQuery(textNodeElement.target).find(commonConstants.FIND_LANGUAGE_TRANSLATE_CUSTOM_ATTRIBUTE + languageCodeName + commonConstants.END_SQUARE_BRACKET).text();
                }
                else{
                    originalSentenceValue = $jQuery(textNodeElement.target).text();
                }
            }
            originalSentenceValue = originalSentenceValue.trim();
            if (originalSentenceValue) {
                checkTranslationHistoryExist(originalSentenceValue);
                editTranslationButton();
                displayCurrentTranslation = originalSentenceValue.match(commonConstants.FIND_DOT_SEPARATER_REGEX);
            }
        }
    });

    $jQuery(commonConstants.JQUERY_FIND_BODY).on(commonConstants.JQUERY_BODY_CHANGE_EVENT, commonConstants.FIND_INPUT_TYPE_RADIO, function () {
        if ($jQuery(commonConstants.FIND_INPUT_NAME_FIRST_COLUMN).is(commonConstants.RADION_BUTTON_CHECKED)) {
            var firstRadioButton = $jQuery(commonConstants.FIND_INPUT_NAME_FIRST_COLUMN);
            $jQuery(commonConstants.FIND_INPUT_NAME_FIRST_COLUMN).eq(commonConstants.ZERO_KEY_INDEX).attr(commonConstants.RADIO_BUTTON_CLASS_DISABLED, true);
            var firstRadioButtonIndex = jQuery(firstRadioButton.index(this));
            var lastRadioButtonIndex = $jQuery(commonConstants.FIND_INPUT_FIRST_COLUMN_LAST).index(commonConstants.FIND_INPUT_NAME_FIRST_COLUMN);
            var firstRadioButtonInitialIndex = firstRadioButtonIndex[commonConstants.ZERO_KEY_INDEX];
            if (firstRadioButtonInitialIndex >= commonConstants.ZERO_KEY_INDEX) {
                for (var secondRadioDisable = firstRadioButtonInitialIndex; secondRadioDisable <= lastRadioButtonIndex; secondRadioDisable++) {
                    var secondRadioButtonDisable = $jQuery(commonConstants.FIND_FIRST_INPUT_RADIO_SECOND_COLUMN + secondRadioDisable + commonConstants.END_ROUND_BRACKET);
                    $jQuery(secondRadioButtonDisable).attr(commonConstants.RADIO_BUTTON_CLASS_DISABLED, true);
                }
            }
            for (var secondRadioEnable = commonConstants.ZERO_KEY_INDEX; secondRadioEnable < firstRadioButtonInitialIndex; secondRadioEnable++) {
                var secondRadioButtonEnable = $jQuery(commonConstants.FIND_FIRST_INPUT_RADIO_SECOND_COLUMN + secondRadioEnable + commonConstants.END_ROUND_BRACKET);
                $jQuery(secondRadioButtonEnable).attr(commonConstants.RADIO_BUTTON_CLASS_DISABLED, false);
            }
        }

        if ($jQuery(commonConstants.FIND_INPUT_NAME_SECOND_COLUMN).is(commonConstants.RADION_BUTTON_CHECKED) || $jQuery(commonConstants.FIND_INPUT_NAME_SECOND_COLUMN).eq(commonConstants.ZERO_KEY_INDEX).is(commonConstants.RADION_BUTTON_CHECKED)) {
            var secondRadioButton = $jQuery(commonConstants.FIND_INPUT_NAME_SECOND_COLUMN);
            var secondRadioButtonIndex = jQuery(secondRadioButton.index(this));
            var secondRadioButtonInitialIndex = secondRadioButtonIndex[commonConstants.ZERO_KEY_INDEX];
            if (secondRadioButtonInitialIndex >= commonConstants.ZERO_KEY_INDEX) {
                for (var firstRadioDisable = commonConstants.ZERO_KEY_INDEX; firstRadioDisable <= secondRadioButtonInitialIndex; firstRadioDisable++) {
                    var firstRadioButtonDisable = $jQuery(commonConstants.FIND_INPUT_NAME_FIRST_COLUMN_FIRST_RADIO + firstRadioDisable + commonConstants.END_ROUND_BRACKET);
                    $jQuery(firstRadioButtonDisable).attr(commonConstants.RADIO_BUTTON_CLASS_DISABLED, true);
                }
            }
            for (var firstRadioEnable = lastRadioButtonIndex; firstRadioEnable > secondRadioButtonInitialIndex; firstRadioEnable--) {
                var firstRadioButtonEnable = $jQuery(commonConstants.FIND_INPUT_NAME_FIRST_COLUMN_FIRST_RADIO + firstRadioEnable + commonConstants.END_ROUND_BRACKET);
                $jQuery(firstRadioButtonEnable).attr(commonConstants.RADIO_BUTTON_CLASS_DISABLED, false);
            }
        }
        $jQuery(commonConstants.FIND_INPUT_NAME_FIRST_COLUMN).eq(commonConstants.ZERO_KEY_INDEX).attr(commonConstants.RADIO_BUTTON_CLASS_DISABLED, true);
    });
    $jQuery(commonConstants.ADD_UPDATE_TRANSLATION_WINDOW_DIV_ID).kendoWindow({
        position: {
            top: commonConstants.TRANSLATION_WINDOW_TOP,
            left: commonConstants.TRANSLATION_WINDOW_LEFT
        },
        content: {
            template: commonConstants.ORIGINAL_SENTENCE_DIV_TEMPLATE+languageCodeName+commonConstants.DISPLAY_VIRTUAL_IMAGE_TAG_TEMPLATE+filePathConstants.FILEPATH_URL+commonConstants.KEYBOARD_IMAGE_FILE_PATH+commonConstants.DISPLAY_CURRENT_NEW_TRANSLATION_TEMPLATE+commonConstants.CURRENT_STATUS_DISPLAY+languageCodeName+commonConstants.TEXT_BOX_CLASS+originalSentenceValue+commonConstants.VIRTUL_KEYBOARD_UPDATE_TRANSLATION_FUNCTION_TEMPLATE
        },
        modal: true,
        draggable: true,
        visible: false,
        width: commonConstants.KENDO_TRANSLATION_POPUP_WINDOW_WIDTH,
        height: commonConstants.KENDO_TRANSLATION_POPUP_WINDOW_HEIGHT,
        resizable: false,
        pinned: true,
        title: commonConstants.KENDO_POPUP_WINDOW_TITLE,
        actions: [commonConstants.KENDO_WINDOW_CLOSE],
        close: function(){
            $jQuery(commonConstants.CHECK_TRANSLATION_HISTORY_EXIST_DIV_ID).remove();
        }
    }).data(commonConstants.KENDO_WINDOW).center();

    $jQuery(commonConstants.NEW_TRANSLATION_INPUT_TEXTAREA_DIV_ID).click().virtualKeyboard();
    $jQuery(commonConstants.JQUERY_FIND_BODY).on(commonConstants.ON_BODY_CLICK, commonConstants.FIND_IMAGE_TAG, function () {
        if (countKeyboardClick == commonConstants.ZERO_KEY_INDEX) {
            $jQuery(commonConstants.VIRTUAL_KEYBOARD_DIV_ID).css(commonConstants.CSS_DISPLAY_STYLE, commonConstants.CSS_DISPLAY_VALUE_STYLE);
            jQuery(commonConstants.TOOLTIP_CONTENT_CLASS).html(commonConstants.VIRTUAL_KEYBOARD_OFF_MESSAGE);
            countKeyboardClick = commonConstants.EXTRACT_STARTING_ARGUMENT;
            $jQuery(commonConstants.NEW_TRANSLATION_INPUT_TEXTAREA_DIV_ID).focus();
        } else {
            $jQuery(commonConstants.VIRTUAL_KEYBOARD_DIV_ID).css(commonConstants.CSS_DISPLAY_STYLE, commonConstants.CSS_DISPLAY_NONE);
            jQuery(commonConstants.TOOLTIP_CONTENT_CLASS).html(commonConstants.KENDO_VIRTUAL_TOOL_TIP_MESSAGE);
            countKeyboardClick = commonConstants.ZERO_KEY_INDEX;
        }
    });

    $jQuery(commonConstants.BACK_TO_TRANSLATION_HISTORY_BUTTON_DIV_ID).kendoTooltip({
        content: commonConstants.KENDO_BACK_HISTORY_TOOL_TIP_MESSAGE,
        position: commonConstants.KENDO_TOOL_TIP_POSITION
    });

    $jQuery(commonConstants.DISPLAY_VIRTUAL_KEYBOARD_DIV_ID).kendoTooltip({
        content: commonConstants.KENDO_VIRTUAL_TOOL_TIP_MESSAGE,
        position: commonConstants.KENDO_TOOL_TIP_POSITION
    });
});

/**
 * Calling at page load/refresh time, for load language package with particular
 * language.
 * */
$jQuery(function () {
    languagePackage.dynamicLanguagePackage(languageCodeName, filePathConstants.FILEPATH_URL + commonConstants.LANGUAGE_FILES_DIRECTORY + languageCodeName + commonConstants.LANGUAGE_FILE_NAME_DIRECTORY);
    window.languagePackage.changeLanguagePackage(languageCodeName);
});

/**
 * Call after all ajax will stop, this is for get all ajax data for translate its.
 * */
$jQuery(document).ajaxStop(function () {
    if (bingTranslationToken == false) {
        languagePackage._getStringValue();
        languagePackage.dynamicLanguagePackage(languageCodeName, filePathConstants.FILEPATH_URL + commonConstants.LANGUAGE_FILES_DIRECTORY + languageCodeName + commonConstants.LANGUAGE_FILE_NAME_DIRECTORY);
        window.languagePackage.changeLanguagePackage(languageCodeName);
    }
});

function checkTranslationHistoryExist(originalSentenceValue) {
    $jQuery.ajax({
        url: filePathConstants.FILEPATH_URL + commonConstants.ADD_UPDATE_TRANSLATION_CONTENT_URL + commonConstants.TRANSLATION_TIKI_PAGE_VERSION,
        type: commonConstants.DATA_SOURCE_TYPE,
        dataType: commonConstants.DATA_TYPE,
        data: {
            languageCodeName: languageCodeName,
            originalSentenceValue: originalSentenceValue,
        },
        success: function (updateTranslationResponse) {
            if (updateTranslationResponse !=null) {
                if($jQuery(commonConstants.ADD_UPDATE_TRANSLATION_WINDOW_DIV_ID).find(commonConstants.CHECK_TRANSLATION_HISTORY_EXIST_DIV_ID).length==commonConstants.ZERO_KEY_INDEX){
                    $jQuery(commonConstants.ADD_UPDATE_TRANSLATION_WINDOW_DIV_ID).find(commonConstants.ORIGINAL_BASE_SENTENCE_div_id).before(commonConstants.TRANSLATION_HISTORY_BUTTON_DIV);
                }
                $jQuery(commonConstants.CHECK_TRANSLATION_HISTORY_EXIST_DIV_ID).kendoTooltip({
                    content: commonConstants.TRANSLATION_HISTORY_TOOL_TIP_MESSAGE,
                    position: commonConstants.KENDO_TOOL_TIP_POSITION
                });
            }
        }
    });
}
/**
 * This function is for display add/update translation's kendo window from gui
 * side.
 */
function editTranslationButton(languageTranslatewindow) {
    $jQuery(commonConstants.ADD_UPDATE_TRANSLATION_WINDOW_DIV_ID).data(commonConstants.KENDO_WINDOW).open();
    $jQuery(document.body).find(commonConstants.FIND_DIV_ID + languageCodeName + commonConstants.ORIGINAL_VALUES_CLASS).html(sentenceReference);
    $jQuery(document.body).find(commonConstants.TEXTAREA_NAME_NEW_TRANSLATION + languageCodeName + commonConstants.END_SQUARE_BRACKET).val(originalSentenceValue);
    var virtualLanguageCodeName;
    if (languageCodeName == commonConstants.GERMAN_SWITZERLAND_LANGUAGE_CODE) {
        virtualLanguageCodeName = commonConstants.GERMAN_GERMANY_LANGUAGE_CODE;
    }
    else if (languageCodeName == commonConstants.CHINESE_TRADITIONAL_LANGUAGE_CODE) {
        virtualLanguageCodeName = commonConstants.CHINESE_SIMPLIFIED_LANGUAGE_CODE;
    }
    else if(languageCodeName == commonConstants.FINNISH_LANGUAGE_CODE){
        virtualLanguageCodeName = commonConstants.NORWEGIAN_LANGUAGE_CODE;
    }
    else if(languageCodeName ==commonConstants.HAITIAN_CREOLE_LANGUAGE_CODE){
        virtualLanguageCodeName = commonConstants.FRENCH_LANGUAGE_CODE;
    }
    else{
        virtualLanguageCodeName = languageCodeName;
    }
    $jQuery(commonConstants.NEW_TRANSLATION_INPUT_TEXTAREA_DIV_ID).attr(commonConstants.DATA_VIRTUAL_KEYBOARD_LAYOUT, virtualLanguageCodeName);
}
/**
 * Function is display sentence history list.
 */
function sentenceHistoryList(){
        $jQuery(commonConstants.TRANSLATION_HISTORY_WINDOW_DIV_ID).kendoWindow({
            content: {
                template: commonConstants.TRANSLATION_HISTORY_GRID_TEMPLATE
            },
            modal : true,
            draggable : true,
            visible : false,
            width : commonConstants.SENTENCE_KENDO_WINDOW_WIDTH,
            height : commonConstants.SENTENCE_KENDO_WINDOW_HEIGHT,
            resizable : false,
            actions : [ commonConstants.KENDO_WINDOW_CLOSE ],
            title : commonConstants.SENTENCE_HISTORY_KENDO_WINDOW_TITLE
        }).data(commonConstants.KENDO_WINDOW).center().open();
        var sentenceReferenceNew = $jQuery(commonConstants.OLD_TEXT_AREA_NAME).html();
        $jQuery(commonConstants.TRANSLATION_HISTORY_GRID_DIV_ID).kendoGrid({
            dataSource: {
                serverPaging: false,
                pageSize: commonConstants.SENTENCE_KENDO_WINDOW_PAGE_SIZE,
                transport: {
                    read: {
                        url: filePathConstants.FILEPATH_URL + commonConstants.ADD_UPDATE_TRANSLATION_CONTENT_URL + commonConstants.SENTENCE_TRANSLATION_HISTORY,
                        type: commonConstants.DATA_SOURCE_TYPE,
                        dataType: commonConstants.DATA_TYPE,
                        data: {
                            languageCodeName: languageCodeName,
                            originalSentenceValue: sentenceReferenceNew,
                        }
                    }
                },
                schema: {
                    model: {
                        fields: {
                            newTranslateValue: {
                                type: commonConstants.FIELDS_TYPE
                            },
                            wordTranslationStatus: {
                                type: commonConstants.FIELDS_TYPE
                            },
                        }
                    }
                }
            },
            serverSorting: true,
            serverFiltering: true,
            pageable: {
                refresh: true,
                pageSizes: true,
            },
            columns: [{
                field: commonConstants.TRANSLATION_VALUE_FIELD,
                title: commonConstants.SENTENCE_TRANSLATION_TITLE,
                width: commonConstants.SENTENCE_TRANSLATION_WIDTH
            },{
                field: commonConstants.USER_REFERENCE_FIELD,
                title: commonConstants.USER_NAME_TITLE,
                width: commonConstants.SENTENCE_STATUS_WIDTH
            },{
                field: commonConstants.DATE_TIME_FIELD,
                title: commonConstants.DATE_TIME_TITLE,
                width: commonConstants.DATE_TIME_COLUMN_WIDTH
            },{
                field: commonConstants.SENTENCE_TIME_ZONE,
                title: commonConstants.TIME_ZONE_TITLE,
                width: commonConstants.TIME_ZONE_COLUMN_WIDTH
            },{
                field: commonConstants.WORD_TRANSLATION_STATUS,
                title:commonConstants.SENTENCE_STATUS_TITLE,
                width: commonConstants.SENTENCE_STATUS_WIDTH,
                template:commonConstants.SENTENCE_TRANSLATION_TEMPLETE
            }],
        });
}
/**
 * This function is for display translation history from tiki side to gui side,
 * in kendo grid.
 */
function translationHistoryButton() {
    $jQuery(commonConstants.ADD_UPDATE_TRANSLATION_WINDOW_DIV_ID).data(commonConstants.KENDO_WINDOW).close();
    $jQuery(commonConstants.TRANSLATION_HISTORY_WINDOW_DIV_ID).kendoWindow({
        content: {
            template: commonConstants.TRANSLATION_HISTORY_GRID_TEMPLATE
        },
        modal: true,
        draggable: true,
        visible: false,
        width: commonConstants.TRANSLATION_HISTORY_WINDOW_WITH,
        height: commonConstants.TRANSLATION_HISTORY_WINDOW_HEIGHT,
        resizable: false,
        actions: [commonConstants.KENDO_WINDOW_CLOSE],
        title: commonConstants.KENDO_TRANSLATION_WINDOW_TITLE
    }).data(commonConstants.KENDO_WINDOW).center().open();

    $jQuery(commonConstants.TRANSLATION_HISTORY_GRID_DIV_ID).kendoGrid({
        dataSource: {
            serverPaging: false,
            pageSize: commonConstants.KENDO_WINDOW_PAGE_SIZE,
            transport: {
                read: {
                    url: filePathConstants.FILEPATH_URL + commonConstants.ADD_UPDATE_TRANSLATION_CONTENT_URL + commonConstants.CHECK_TRANSLATION_COMPARE_HISTORY,
                    type: commonConstants.DATA_SOURCE_TYPE,
                    dataType: commonConstants.DATA_TYPE,
                    data: {
                        languageCodeName: languageCodeName,
                        originalSentenceValue: originalSentenceValue,
                    }
                }
            },
            schema: {
                model: {
                    fields: {
                        tikiUserName: {
                            type: commonConstants.FIELDS_TYPE
                        },
                        lastModifyPage: {
                            type: commonConstants.FIELDS_TYPE
                        },
                        tikiPageVersion: {
                            type: commonConstants.FIELDS_TYPE
                        },
                    }
                }
            }
        },
        serverSorting: true,
        serverFiltering: true,
        pageable: {
            refresh: true,
            pageSizes: true,
            buttonCount: commonConstants.GRID_BUTTON_COUNT
        },
        height: commonConstants.TRANSLATION_HISTORY_GRID_HEIGHT,
        dataBound: function () {
            var data = this.dataSource.data();
            $jQuery.each(data, function (i, row) {
            var translationId=row.get("originalId");
            localStorage.setItem('translationId', translationId);
            });
            $jQuery(commonConstants.INPUT_FIRST_RADIO_CLASS_VALUE + commonConstants.ZERO_KEY_INDEX + commonConstants.ROUND_BRACKET_END).attr(commonConstants.FIND_RADIO_BUTTON_CLASS, commonConstants.RADIO_BUTTON_DISBALE);
            $jQuery(commonConstants.INPUT_LAST_RADIO_CLASS_VALUE).attr(commonConstants.FIND_RADIO_BUTTON_CLASS, commonConstants.RADIO_BUTTON_DISBALE);
            $jQuery(commonConstants.INPUT_RADIO_DISABLE_CLASS).attr(commonConstants.RADIO_BUTTON_CLASS_DISABLED, true);
            $jQuery(commonConstants.FIND_INPUT_NAME_SECOND_COLUMN).eq(commonConstants.ZERO_KEY_INDEX).attr(commonConstants.RADIO_BUTTON_CHECK, commonConstants.RADIO_BUTTON_CHECK);
            $jQuery(commonConstants.FIND_INPUT_NAME_FIRST_COLUMN).last().attr(commonConstants.RADIO_BUTTON_CHECK, commonConstants.RADIO_BUTTON_CHECK);
        },
        columns: [{
            field: commonConstants.USERNAME_FIELD,
            title: commonConstants.USERNAME_TITLE,
            width: commonConstants.USERNAME_WIDTH
        }, {
            field: commonConstants.IP_ADDRESS_FIELD,
            title: commonConstants.IP_ADDRESS_TITLE,
            width: commonConstants.IP_ADDRESS_WIDTH
        }, {
            field: commonConstants.LAST_MODIFY_PAGE_FIELD,
            title: commonConstants.LAST_MODIFY_PAGE_TITLE,
            width: commonConstants.LAST_MODIFY_PAGE_WIDTH
        }, {
            field: commonConstants.TIKI_PAGE_VERSION_FIELD,
            title: commonConstants.TIKI_PAGE_VERSION_TITLE,
            width: commonConstants.TIKI_PAGE_VERSION_WIDTH
        }, {
            title: commonConstants.COMPARE_TRANSLATION_TIKI_HISTORY_INPUT_TAG,
            template: commonConstants.DISPLAY_FOR_SELECT_RADIO_BUTTONS_TEMPLATE,
            width: commonConstants.COMPARE_VERSION_SIZE
        }, ],
    });
}

/**
 * This function is for add/update translation from gui side using kendo window.
 */
function addUpdateTranslation() {
    var newTranslateValue = $jQuery(document.body).find(commonConstants.TEXTAREA_NAME_NEW_TRANSLATION + languageCodeName + commonConstants.END_SQUARE_BRACKET).val();
    var newTranslateValueRemoveSpecialSymbol = newTranslateValue.replace(commonConstants.FIND_DOT_SEPARATER_REGEX,commonConstants.ASSIGN_EMPTY_VARIABLE);
    if(displayCurrentTranslation!=null){
        newTranslateValueRemoveSpecialSymbol = newTranslateValueRemoveSpecialSymbol+displayCurrentTranslation[commonConstants.ZERO_KEY_INDEX];
    }
    var sentenceReferenceNew = $jQuery(commonConstants.OLD_TEXT_AREA_NAME).html();
    $jQuery.ajax({
        url: filePathConstants.FILEPATH_URL + commonConstants.ADD_UPDATE_TRANSLATION_CONTENT_URL + commonConstants.ADD_UPDATE_TRANSLATION_BY_USER,
        data: {
            sentenceReference: sentenceReferenceNew,
            newTranslateValue: newTranslateValueRemoveSpecialSymbol,
            languageCodeName: languageCodeName,
            userReference:localStorage.getItem(commonConstants.TRANSLATION_USER_NAME),
            referenceUrl:document.URL,
        },
        type: commonConstants.DATA_SOURSE_TYPE,
        beforeSend: function () {
            jQuery(commonConstants.TRANSLATION_SUBMIT_BUTTON_CLASS).append(commonConstants.IMAGE_TAG_WITH_SRC + filePathConstants.FILEPATH_URL + commonConstants.IMAGE_LOADER_FILE_PATH + commonConstants.IMAGE_TAG_ALT_WIDTH_HEIGHT);
        },
        complete: function () {
           /**
             * @todo here required to change the project exicution process, so until QA done keep this code as commented.
             */
           /* if (htmlElement.is(commonConstants.FIND_INPUT_TAG)) {
                switch (htmlElement.attr(commonConstants.INPUT_TYPE)) {
                case commonConstants.HTML_INPUT_BUTTON_TYPE:
                case commonConstants.HTML_INPUT_SUBMIT_TYPE:
                case commonConstants.HTML_INPUT_RESET_TYPE:
                    var inputTypeValue = $jQuery(htmlElement).attr(commonConstants.INPUT_VALUE);
                    if(inputTypeValue){
                        htmlElement.attr(commonConstants.INPUT_VALUE, newTranslateValueRemoveSpecialSymbol);
                    }
                    else if(originalHtmlContents){
                        htmlElement.html(originalHtmlContents.replace(originalSentenceValue, newTranslateValueRemoveSpecialSymbol));
                    }
                    else{
                        htmlElement.val(newTranslateValueRemoveSpecialSymbol);
                    }
                    break;
                case commonConstants.HTML_INPUT_TEXT_TYPE:
                case commonConstants.HTML_INPUT_SEARCH_TYPE:
                    var placeholderValue = $jQuery(htmlElement).attr(commonConstants.HTML_PLACEHOLDER_ATTRIBUTE);
                    if (placeholderValue) {
                        if(originalHtmlContents){
                            htmlElement.html(originalHtmlContents.replace(originalSentenceValue, newTranslateValueRemoveSpecialSymbol));
                        }
                        else{
                            htmlElement.attr(commonConstants.HTML_PLACEHOLDER_ATTRIBUTE, newTranslateValueRemoveSpecialSymbol);
                        }
                    }
                    var inputTypeValue = $jQuery(htmlElement).attr(commonConstants.INPUT_VALUE);
                    if (inputTypeValue) {
                        if(originalHtmlContents){
                            htmlElement.html(originalHtmlContents.replace(originalSentenceValue, newTranslateValueRemoveSpecialSymbol));
                        }
                        else{
                            htmlElement.attr(commonConstants.INPUT_VALUE, newTranslateValueRemoveSpecialSymbol);
                        }
                    }
                    break;
                }
            } else if (htmlElement.is(commonConstants.FIND_TEXTAREA_TAG)) {
                var textareaPlaceholder = $jQuery(htmlElement).attr(commonConstants.HTML_PLACEHOLDER_ATTRIBUTE);
                var textareaValue = $jQuery(htmlElement).val();
                if (textareaPlaceholder) {
                    htmlElement.attr(commonConstants.HTML_PLACEHOLDER_ATTRIBUTE, newTranslateValueRemoveSpecialSymbol);
                }
                if (textareaValue) {
                    if(originalHtmlContents){
                        htmlElement.html(originalHtmlContents.replace(originalSentenceValue, newTranslateValueRemoveSpecialSymbol));
                    }
                    else{
                        htmlElement.val(newTranslateValueRemoveSpecialSymbol);
                    }
                }
            } else {
                htmlElement.html(originalHtmlContents.replace(originalSentenceValue, newTranslateValueRemoveSpecialSymbol));
            } */
            $jQuery(commonConstants.FIND_TRANSLATION_SUBMIT_BUTTON_CLASS_LAST_CHILD).remove();
            $jQuery(commonConstants.ADD_UPDATE_TRANSLATION_WINDOW_DIV_ID).data(commonConstants.KENDO_WINDOW).close();
        }
    });
}

/**
 * To compare translation tiki history revision for check differences from tikiwiki
 * to gui side in kendo grid window.
 */
function compareTranslationTikiHistory() {
    jQuery(commonConstants.TRANSLATION_HISTORY_GRID_DIV_ID).hide();
    jQuery(commonConstants.TRANSLATION_HISTORY_GRID_DIV_ID).hide();
    jQuery(commonConstants.TRANSLATION_COMPARE_HISTORY_GRID).show();
    jQuery(commonConstants.BACK_TO_TRANSLATION_HISTORY_BUTTON_DIV_ID).show();
    var oldVersionNumber = $jQuery(commonConstants.INPUT_NAME_FIRST_COLUMN_RADIO_CHECKED).closest(commonConstants.FIND_TR_TAG).children().eq(commonConstants.THIRD_KEY_INDEX).text();
    var newVersionNumber = $jQuery(commonConstants.INPUT_NAME_SECOND_COLUMN_RADIO_CHECKED).closest(commonConstants.FIND_TR_TAG).children().eq(commonConstants.THIRD_KEY_INDEX).text();
    dataSource = new kendo.data.DataSource({
        transport: {
            read: {
                url: filePathConstants.FILEPATH_URL + commonConstants.ADD_UPDATE_TRANSLATION_CONTENT_URL + commonConstants.COMPARE_TRANSLATION_TIKI_HISTORY,
                type: commonConstants.DATA_SOURCE_TYPE,
                dataType: commonConstants.DATA_TYPE,
                data: {
                    languageCodeName: languageCodeName,
                    originalSentenceValue: originalSentenceValue,
                    oldVersionNumber: oldVersionNumber,
                    newVersionNumber: newVersionNumber,
                    translationId:localStorage.getItem('translationId'),
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

    $jQuery(commonConstants.TRANSLATION_COMPARE_HISTORY_GRID).kendoGrid({
        dataSource: dataSource,
        pageable: false,
        height: commonConstants.TRANSLATION_COMPARE_HISTORY_GRID_HEIGHT,
        columns: [{
            field: commonConstants.OLD_DATA_REVISION_FIELD,
            title: commonConstants.OLD_DATA_REVISION_TITLE,
            encoded: false,
            width: commonConstants.COMMON_SIZE
        }, {
            field: commonConstants.NEW_DATA_REVISION_FIELD,
            title: commonConstants.NEW_DATA_REVISION_TITLE,
            encoded: false,
            width: commonConstants.COMMON_SIZE
        }, ],
    });
}

/**
 * To back translation history from compare history kendo window.
 */
function backToTranslationHistoryWindow() {
    jQuery(commonConstants.TRANSLATION_COMPARE_HISTORY_GRID).hide();
    jQuery(commonConstants.TRANSLATION_HISTORY_GRID_DIV_ID).show();
    jQuery(commonConstants.BACK_TO_TRANSLATION_HISTORY_BUTTON_DIV_ID).hide();
}

/**
 * Set cookie for language code name to get/set language as a initial after
 * one time selected by user.
 * @param string cookieName
 * @param string languageCode
 * @param integer cookieExpireDays
 */
function setCookieLanguageName(cookieName, languageCode, cookieExpireDays) {
    if (cookieExpireDays) {
        var setCookieDate = new Date();
        setCookieDate.setTime(setCookieDate.getTime() + (cookieExpireDays * commonConstants.COOOKIE_HOUR * commonConstants.COOKIE_MINUTES * commonConstants.COOKIE_MINUTES * commonConstants.COOKIE_MILISECOND));
        var cookieExpire = commonConstants.COOKIE_EXPIRE_ATTRIBUTE + setCookieDate.toGMTString();
    } else var cookieExpire = commonConstants.ASSIGN_EMPTY_VARIABLE;
    document.cookie = cookieName + commonConstants.COOKIE_EQQUAL_ATTRIBUTE + languageCode + cookieExpire + commonConstants.COOKIE_PATH_ATTRIBUTE;
}

/**
 * read cookie for get language code name for set language for translator.
 * @param string languageCodeName
 * @returns string languageName
 */
function readCookie(languageCodeName) {
    var cookieNameValue = languageCodeName + commonConstants.COOKIE_EQQUAL_ATTRIBUTE;
    var cookieName = document.cookie.split(commonConstants.SEMICOLON_SIGN);
    for (var cookieCounter = commonConstants.ZERO_KEY_INDEX; cookieCounter < cookieName.length; cookieCounter++) {
        var languageName = cookieName[cookieCounter];
        while (languageName.charAt(commonConstants.ZERO_KEY_INDEX) == commonConstants.ASSIGN_WHITESPACE) languageName = languageName.substring(commonConstants.EXTRACT_STARTING_ARGUMENT, languageName.length);
        if (languageName.indexOf(cookieNameValue) == commonConstants.ZERO_KEY_INDEX) {
            return languageName.substring(cookieNameValue.length, languageName.length);
        }
    }
}
