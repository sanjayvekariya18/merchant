/**
 * This file is for add/update translation from admin side, as well user can
 * approval translation which already right.
 * */
var onOffBingTranslation;
var commonConstants = {
    'ADD_UPDATE_TRANSLATION_APPROVAL_URL': 'AddUpdateTranslationApproval.php?translationApprovalFunction=',
    'DISPLAY_TRANSLATION_APPROVAL_URL': 'displayTranslation',
    'APPROVE_TRANSLATION_APPROVAL_URL': 'approveTranslation',
    'KENDO_GRID_PAGE_SIZE': 10,
    'KENDO_GRID_HEIGHT': 600,
    'DATA_SOURCE_TYPE': 'POST',
    'DATA_TYPE': 'json',
    'ORIGINAL_WORD_WIDTH': '280px',
    'USER_REFERENCE_WIDTH': '80px',
    'IP_ADDRESS_WIDTH': '60px',
    'KENDO_GRID_COMMAND_WIDTH': '138px',
    'DATE_TIME_WIDTH': '125px',
    'FETCH_ALL_LANGUAGES_URL': 'LanguageTranslation.php/allLanguage',
    'ARABIC_LANGUAGE_CODE': 'ar_sa',
    'ADD_UPDATE_LANGUAGE_TRANSLATE': 'AddUpdateTranslation.php?callFunction=addUpdateTranslationByAdmin',
    'AUTHENTICATION_USER': 'admin',
    'ZERO_KEY_INDEX': 0,
    'TRANSLATION_FIRST_FLAG': 2,
    'TRANSLATE_SUCCESS_MESSAGE': 'Success',
    'TRANSLATION_APPROVAL_GRID':"#translationApprovalGrid",
    'KENDO_GRID':"kendoGrid",
    'MULTI_LANGUAGE_DROPDOWN_DIV_ID':"#multiLanguageDropdown",
    'KENDO_DROPDOWN_LIST':"kendoDropDownList",
    'CREATE_NEW_TRANSLATION':"create",
    'CREATE_NEW_TRANSLATION_MESSAGE':"Add new translation",
    'ORIGINAL_WORD_FIELD':"original_word",
    'ORIGINAL_WORD_FIELD_MESSAGE':"Original Word",
    'TRANSLATION_FIELD':"translation",
    'TRANSLATION_FIELD_MESSAGE':"Translation",
    'USER_REFERENCE_FIELD':"user_reference",
    'USER_REFERENCE_FIELD_MESSAGE':"User",
    'IP_FIELD':"ip",
    'IP_FIELD_MESSAGE':"IP",
    'DATE_TIME_FIELD':"date_time",
    'DATE_TIME_FIELD_MESSAGE':"Date Time",
    'TRANSLATION_APPROVE_MESSAGE':"approve",
    'TR_TAG':"tr",
    'TRANSLATION_SENTENCE_ACTIONS':"Action",
    'KENDO_GRID_INLINE':"inline",
    'ORIGINAL_WORD_ID':"or_id",
    'EDIT_TRANSLATION_VALUE':"edit",
    'LANGUAGE_NAME':"language_name",
    'LANGUAGE_CODE':"language_code",
    'TRANSLATION_APPROVE_LINK':'<a href="javascript:void(0)" class="k-button k-grid-approve">Approve</a>',
    'COOKIE_EXPIRE_DAY': 7,
    'COOOKIE_HOUR': 24,
    'COOKIE_MINUTES': 60,
    'COOKIE_MILISECOND': 1000,
    'COOKIE_EXPIRE_ATTRIBUTE': '; expires=',
    'COOKIE_EQQUAL_ATTRIBUTE': '=',
    'COOKIE_PATH_ATTRIBUTE': '; path=/',
    'COOKIE_NAME': 'onOffBingTranslation',
    'OFF_BING_REALTIME_TRANSLATION':'OFF Bing Realtime Translation',
    'ON_BING_REALTIME_TRANSLATION':'ON Bing Realtime Translation',
    'FIND_BODY':'body',
    'INPUT_NAME_ON_OFF_BING_TRANSLATION':'input[name=onOffBingTranslation]',
    'SEMICOLON_SIGN': ';',
    'ASSIGN_WHITESPACE': ' ',
    'FIRST_INDEX':1,
    'BING_ON_OFF_TRANSLATION_TRUE_TOKEN':'true',
    'BING_ON_OFF_TRANSLATION_FALSE_TOKEN':'false'
};

var jQuery = jQuery.noConflict();
jQuery(document).ready(function () {
    var currentbingTranslationStatus = readCookie(commonConstants.COOKIE_NAME);
    console.log(currentbingTranslationStatus);
    if(currentbingTranslationStatus==commonConstants.BING_ON_OFF_TRANSLATION_TRUE_TOKEN){
        jQuery(commonConstants.FIND_BODY).find(commonConstants.INPUT_NAME_ON_OFF_BING_TRANSLATION).val(commonConstants.OFF_BING_REALTIME_TRANSLATION);
    }
    else if(currentbingTranslationStatus==commonConstants.BING_ON_OFF_TRANSLATION_FALSE_TOKEN){
       jQuery(commonConstants.FIND_BODY).find(commonConstants.INPUT_NAME_ON_OFF_BING_TRANSLATION).val(commonConstants.ON_BING_REALTIME_TRANSLATION); 
    }
    else{
       jQuery(commonConstants.FIND_BODY).find(commonConstants.INPUT_NAME_ON_OFF_BING_TRANSLATION).val(commonConstants.ON_BING_REALTIME_TRANSLATION);  
    }

    var languageCode;
    jQuery(commonConstants.MULTI_LANGUAGE_DROPDOWN_DIV_ID).kendoDropDownList({
        dataTextField: commonConstants.LANGUAGE_NAME,
        dataValueField: commonConstants.LANGUAGE_CODE,
        dataSource: {
            transport: {
                read: {
                    dataType: commonConstants.DATA_TYPE,
                    url: commonConstants.FETCH_ALL_LANGUAGES_URL,
                    type: commonConstants.DATA_SOURCE_TYPE
                }
            }
        },
        change: function (changeLanguage) {
            var translationGrid = jQuery(commonConstants.TRANSLATION_APPROVAL_GRID).data(commonConstants.KENDO_GRID);
            translationGrid.dataSource.read();
        }
    });

    dataSource = new kendo.data.DataSource({
        transport: {
            read: function (readTranslationValues) {
                var languageValues = jQuery(commonConstants.MULTI_LANGUAGE_DROPDOWN_DIV_ID).data(commonConstants.KENDO_DROPDOWN_LIST);
                languageCode = languageValues.value();
                if (!languageCode) {
                    languageCode = commonConstants.ARABIC_LANGUAGE_CODE;
                }

                jQuery.ajax({
                    url: commonConstants.ADD_UPDATE_TRANSLATION_APPROVAL_URL + commonConstants.DISPLAY_TRANSLATION_APPROVAL_URL,
                    dataType: commonConstants.DATA_TYPE,
                    type: commonConstants.DATA_SOURCE_TYPE,
                    data: {
                        languageName: languageCode
                    },
                    success: function (successResponse) {
                        readTranslationValues.success(successResponse);
                    }
                });
            },
            update: function (updateTranslation) {
                var translationId = updateTranslation.data.models[commonConstants.ZERO_KEY_INDEX].or_id;
                var originalWord = updateTranslation.data.models[commonConstants.ZERO_KEY_INDEX].original_word;
                var translationWord = updateTranslation.data.models[commonConstants.ZERO_KEY_INDEX].translation;
                var languageValues = jQuery(commonConstants.MULTI_LANGUAGE_DROPDOWN_DIV_ID).data(commonConstants.KENDO_DROPDOWN_LIST);
                languageCode = languageValues.value();

                jQuery.ajax({
                    dataType: commonConstants.DATA_TYPE,
                    type: commonConstants.DATA_SOURCE_TYPE,
                    url: commonConstants.ADD_UPDATE_LANGUAGE_TRANSLATE,
                    data: {
                        originalContent: originalWord,
                        newTranslationText: translationWord,
                        languageCode: languageCode,
                        userReference: commonConstants.AUTHENTICATION_USER,
                        translationFlag: commonConstants.TRANSLATION_FIRST_FLAG,
                        translationId: translationId,
                        referenceUrl:document.URL,
                    },
                    success: function (translateResponse) {
                        if (translateResponse.response.message == commonConstants.TRANSLATE_SUCCESS_MESSAGE) {
                            var translationGrid = jQuery(commonConstants.TRANSLATION_APPROVAL_GRID).data(commonConstants.KENDO_GRID);
                            translationGrid.dataSource.read();
                        }
                    }
                });
            },
            create: function (addTranslation) {
                var originalWord = addTranslation.data.models[commonConstants.ZERO_KEY_INDEX].original_word;
                var translationWord = addTranslation.data.models[commonConstants.ZERO_KEY_INDEX].translation;
                var languageValues = jQuery(commonConstants.MULTI_LANGUAGE_DROPDOWN_DIV_ID).data(commonConstants.KENDO_DROPDOWN_LIST);
                languageCode = languageValues.value();

                jQuery.ajax({
                    dataType: commonConstants.DATA_TYPE,
                    type: commonConstants.DATA_SOURCE_TYPE,
                    url: commonConstants.ADD_UPDATE_LANGUAGE_TRANSLATE,
                    data: {
                        originalContent: originalWord,
                        newTranslationText: translationWord,
                        languageCode: languageCode,
                        userReference: commonConstants.AUTHENTICATION_USER,
                        translationFlag: commonConstants.TRANSLATION_FIRST_FLAG,
                        referenceUrl:document.URL,
                    },
                    success: function (translateResponse) {
                        if (translateResponse.response.message == commonConstants.TRANSLATE_SUCCESS_MESSAGE) {
                            var translationGrid = jQuery(commonConstants.TRANSLATION_APPROVAL_GRID).data(commonConstants.KENDO_GRID);
                            translationGrid.dataSource.read();
                        }
                    }
                });
            }
        },
        batch: true,
        pageSize: commonConstants.KENDO_GRID_PAGE_SIZE,
        schema: {
            model: {
                id: commonConstants.ORIGINAL_WORD_ID,
                fields: {
                    original_word: {
                        editable: true,
                        validation: {
                            required: true
                        }
                    },
                    translation: {
                        editable: true,
                        validation: {
                            required: true
                        }
                    },
                    user_reference: {
                        editable: false
                    },
                    ip: {
                        editable: false
                    },
                    date_time: {
                        editable: false
                    }
                }
            }
        }
    });

    jQuery(commonConstants.TRANSLATION_APPROVAL_GRID).kendoGrid({
        dataSource: dataSource,
        pageable: {
            refresh: true,
            pageSizes: true
        },
        serverPaging: true,
        serverFiltering: true,
        serverSorting: true,
        scrollable: true,
        sortable: true,
        height: commonConstants.KENDO_GRID_HEIGHT,
        toolbar: [{
            name: commonConstants.CREATE_NEW_TRANSLATION,
            text: commonConstants.CREATE_NEW_TRANSLATION_MESSAGE
        }],
        columns: [{
            field: commonConstants.ORIGINAL_WORD_FIELD,
            title: commonConstants.ORIGINAL_WORD_FIELD_MESSAGE,
            width: commonConstants.ORIGINAL_WORD_WIDTH
        }, {
            field: commonConstants.TRANSLATION_FIELD,
            title: commonConstants.TRANSLATION_FIELD_MESSAGE,
            width: commonConstants.ORIGINAL_WORD_WIDTH
        }, {
            field: commonConstants.USER_REFERENCE_FIELD,
            title: commonConstants.USER_REFERENCE_FIELD_MESSAGE,
            width: commonConstants.USER_REFERENCE_WIDTH
        }, {
            field: commonConstants.IP_FIELD,
            title: commonConstants.IP_FIELD_MESSAGE,
            width: commonConstants.IP_ADDRESS_WIDTH
        }, {
            field: commonConstants.DATE_TIME_FIELD,
            title: commonConstants.DATE_TIME_FIELD_MESSAGE,
            width: commonConstants.DATE_TIME_WIDTH
        }, {
            command: [
                commonConstants.EDIT_TRANSLATION_VALUE, {
                    name: commonConstants.TRANSLATION_APPROVE_MESSAGE,
                    template: commonConstants.TRANSLATION_APPROVE_LINK,
                    click: function (approvalTranslation) {
                        var translationRowValue = jQuery(approvalTranslation.target).closest(commonConstants.TR_TAG);
                        var translationItem = jQuery(commonConstants.TRANSLATION_APPROVAL_GRID).data(commonConstants.KENDO_GRID).dataItem(translationRowValue);
                        var languageValues = jQuery(commonConstants.MULTI_LANGUAGE_DROPDOWN_DIV_ID).data(commonConstants.KENDO_DROPDOWN_LIST);
                        languageCode = languageValues.value();
                        jQuery.ajax({
                            url: commonConstants.ADD_UPDATE_TRANSLATION_APPROVAL_URL + commonConstants.APPROVE_TRANSLATION_APPROVAL_URL,
                            dataType: commonConstants.DATA_TYPE,
                            type: commonConstants.DATA_SOURCE_TYPE,
                            data: {
                                translationFlag: commonConstants.TRANSLATION_FIRST_FLAG,
                                translationId: translationItem.or_id,
                                languageCode: languageCode,
                                userReference: commonConstants.AUTHENTICATION_USER,
                                referenceUrl:document.URL
                            },
                            success: function () {
                                var translationGrid = jQuery(commonConstants.TRANSLATION_APPROVAL_GRID).data(commonConstants.KENDO_GRID);
                                translationGrid.dataSource.read();
                            }
                        });
                    }
                }
            ],
            title: commonConstants.TRANSLATION_SENTENCE_ACTIONS,
            width: commonConstants.KENDO_GRID_COMMAND_WIDTH
        }],
        editable: commonConstants.KENDO_GRID_INLINE
    });
});

function onOffBingTranslateSentence(currentValue) {
    if(currentValue==commonConstants.OFF_BING_REALTIME_TRANSLATION){
        jQuery(commonConstants.FIND_BODY).find(commonConstants.INPUT_NAME_ON_OFF_BING_TRANSLATION).val(commonConstants.ON_BING_REALTIME_TRANSLATION);
        setCookie(bingTranslationToken=false);
    }
    if(currentValue==commonConstants.ON_BING_REALTIME_TRANSLATION){
        jQuery(commonConstants.FIND_BODY).find(commonConstants.INPUT_NAME_ON_OFF_BING_TRANSLATION).val(commonConstants.OFF_BING_REALTIME_TRANSLATION);
        setCookie(bingTranslationToken=true);
    }
}

function setCookie(bingTranslationToken){
    var cookieName = commonConstants.COOKIE_NAME;
    var cookieExpireDays = commonConstants.COOKIE_EXPIRE_DAY;
    if (cookieExpireDays) {
        var setCookieDate = new Date();
        setCookieDate.setTime(setCookieDate.getTime() + (cookieExpireDays * commonConstants.COOOKIE_HOUR * commonConstants.COOKIE_MINUTES * commonConstants.COOKIE_MINUTES * commonConstants.COOKIE_MILISECOND));
        var cookieExpire = commonConstants.COOKIE_EXPIRE_ATTRIBUTE + setCookieDate.toGMTString();
    } else var cookieExpire = commonConstants.ASSIGN_EMPTY_VARIABLE;
    document.cookie = cookieName + commonConstants.COOKIE_EQQUAL_ATTRIBUTE + bingTranslationToken + cookieExpire + commonConstants.COOKIE_PATH_ATTRIBUTE;
}

function readCookie(bingTranslationCookieName){
    var cookieNameValue = commonConstants.COOKIE_NAME + commonConstants.COOKIE_EQQUAL_ATTRIBUTE;
    var cookieName = document.cookie.split(commonConstants.SEMICOLON_SIGN);
    for (var cookieCounter = commonConstants.ZERO_KEY_INDEX; cookieCounter < cookieName.length; cookieCounter++) {
        var bingTranslationCookieName = cookieName[cookieCounter];
        while (bingTranslationCookieName.charAt(commonConstants.ZERO_KEY_INDEX) == commonConstants.ASSIGN_WHITESPACE) bingTranslationCookieName = bingTranslationCookieName.substring(commonConstants.FIRST_INDEX, bingTranslationCookieName.length);
        if (bingTranslationCookieName.indexOf(cookieNameValue) == commonConstants.ZERO_KEY_INDEX) {
            return bingTranslationCookieName.substring(cookieNameValue.length, bingTranslationCookieName.length);
        }
    }
}