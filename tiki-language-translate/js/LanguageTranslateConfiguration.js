var $jQuery = jQuery.noConflict();
var bingTranslationToken = false;
var getUrl = window.location;
var baseUrl = getUrl .protocol + "//" + getUrl.host ;
var pathName = getUrl.pathname.split('/')[1];
var basePathUrl = getUrl .protocol + "//" + getUrl.host + "/" + getUrl.pathname.split('/')[1];
if(pathName == 'tiki-language-translate'){
    var filePathConstants = {
        'FILEPATH_URL': baseUrl + '/tiki-language-translate/'
    };
}else{
    var filePathConstants = {
        'FILEPATH_URL': basePathUrl + '/tiki-language-translate/'
    };
}

var languagePackage = (function () {
    var commonConstants = {
        'ENGLISH_LANGUAGE': 'en_us',
        'HTML_TITLE_ATTRIBUTE': 'title',
        'HTML_ALT_ATTRIBUTE': 'alt',
        'HTML_PLACEHOLDER_ATTRIBUTE': 'placeholder',
        'ZERO_INDEX': 0,
        'FIRST_INDEX': 1,
        'SECOND_INDEX': 2,
        'THIRD_INDEX': 3,
        'LANGUAGE_PACKAGE_LOAD_ERROR': 'Cannot load language pack, no file path specified!',
        'HTML_INPUT_BUTTON_TYPE': 'button',
        'HTML_INPUT_SUBMIT_TYPE': 'submit',
        'HTML_INPUT_RESET_TYPE': 'reset',
        'LANGUAGE_PACKAGE_PATH_FIND_ERROR': 'Language pack could not load from: ',
        'LANGUAGE_PACKAGE_EXISTS_ERROR': 'Language pack not defined for: ',
        'DATA_TYPE': 'json',
        'ASSIGN_BLANK_VARIABLE': '',
        'SPACE_SEPARATER': ' ',
        'HTML_INPUT_TEXT_TYPE': 'text',
        'STRING_APPEND_ARGUMENT': 'append',
        'STRING_APPENDTO_ARGUMENT': 'appendTo',
        'STRING_PREPEND_ARGUMENT': 'prepend',
        'STRING_BEFORE_ARGUMENT': 'before',
        'STRING_AFTER_ARGUMENT': 'after',
        'STRING_HTML_ARGUMENT': 'html',
        'FIND_NON_ALPHABETIC_CHARATERS': /\W|[0-9]|_/g,
        'END_POSITION_ALL_NON_ALPHABET': /[$\W|[0-9]|_].*$/g,
        'DISPLAY_TRANSLATION_CONTENT_URL': 'AddUpdateTranslation.php?callFunction=addUpdateLanguageTranslate',
        'DATA_SOURSE_TYPE': 'post',
        'EMAIL_ID_VALIDATION': /^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/,
        'FIND_FORWARD_SLASH': /[\/].*/g,
        'FIND_FORWARD_SLASH_REGULAR_EXPRESSION': /\//g,
        'APPLY_FIRST_REGEX_MATCH': '$1',
        'APPLY_SECOND_REGEX_MATCH': '$2',
        'ADD_FORWARD_SLASH': '/',
        'ARABIC_LANGUAGE_TEXT_ALIGN': 'ar_sa',
        'URDU_LANGUAGE_TEXT_ALIGN': 'ur_pk',
        'HEBREW_LANGUAGE_TEXT_ALIGN': 'he_il',
        'PERSIAN_LANGUAGE_TEXT_ALIGN': 'fa_fa',
        'HTML_INPUT_SEARCH_TYPE': 'search',
        'FIND_ALPHABETIC_CHARATERS': /[^\W].*/g,
        'FIND_COMMENTED_HTML_TAG': /(<([^>]+)>)/ig,
        'DOT_SEPARATER': '.',
        'FIND_DOT_SEPARATER_REGEX': /[\.,?\!]+/g,
        'FIND_SPECIAL_SMBOL_EXCEPT_SPACE': /[^a-z ]+/g,
        'FIND_SPECIAL_SYMBOL_WORD': /[ ][^a-z ]+[ ]/g,
        'FIND_SPECIAL_SYMBOL_SENTENCE': /^[^a-z ]+$/g,
        'FIND_DOUBLE_QUOTE_SYMBOL': /["\(\)\*\#\[\]\{\}\$\^]+/g,
        'KEEP_DELIMITER_FROM_STRING': /[^\.,!\?]+[\.,!\?]+/g,
        'FIND_SENTENCE_END_FROM_TRANSLATION': /[.!।]$/g,
        'ADD_SPACE_WITH_END_TRANSLATION': '. ',
        'NOT_DEFINE_VARIABLE': 'undefined',
        'FIND_ALL_BODY_HTML_TAG': '*',
        'INTERNET_DOWN_MESSAGE': 'Internet Slow/Down',
        'JQUERY_FIND_BODY': 'body',
        'JQUERY_FIND_SPAN': 'span',
        'CHECK_IS_LINK':'link',
        'CHECK_IS_SCRIPT':'script',
        'CHECK_IS_STYLE':'style',
        'CHECK_IS_NO_SCRIPT':'noscript',
        'INPUT_TAG':'input',
        'INPUT_TYPE':'type',
        'TRANSLATION_NODE_VALUE':'LanguagePackageValues',
        'HTML_ATTRIBUTE_VALUE':'value',
        'TEXTAREA_TAG':'textarea',
        'TEXT_ALIGN_CSS':'text-align',
        'TEXT_ALIGN_CSS_VALUE':'right',
        'CUSTOM_LANGUAGE_TRANSLATE_ATTRIBUTE':'[languageTranslate=',
        'END_ATTRIBUTE_SQUARE_BRACKET':']',
        'ATTRIBUTE_SPAN_TAG':'<span class="originalSentence-',
        'DISPLAY_NONE_CSS_STYLE':'" style="display:none">',
        'END_SPAN_TAG':'</span>',
        'FIND_LANGUAGETRANSLATE_ATTRIBUTE':'languageTranslate',
        'LANGUAGETRANSLATE_ATTRIBUTE_SPAN_TAG':'<span languageTranslate=',
        'END_PLURAL_SPAN_TAG':'>',
        'ORIGINAL_SENETENCE_SPAN_TAG':'</span><span class="originalSentence-',
        'FIND_MULTI_LANGUAGE_DROPDOWN_LIST_UL_LI':'div#multiLanguageDropdown-list ul li',
        'FIND_SPAN_MULTI_LANGUAGE_DROPDOWN_LISTBOX_DROPDOWN_INPUT':'span[aria-owns=multiLanguageDropdown_listbox] .k-dropdown-wrap .k-input',
        'FIND_ADD_UPDATE_TRANSLATION_WINDOW_DIV_VIRTUAL_KEYBOARD_UL_LI':'div#addUpdateTranslationWindow div#virtualKeyboard ul li',
        'LANGUAGE_PACKAGE_TEXT_VALUE':'LanguagePackageTextValue',
        'ORIGINALSENTENCE_CLASS_NAME':'.originalSentence-',
        'SPLIT_TAG_VALUE_WITH_BREAK_LINE':'<br>',
        'FIND_BREAK_LINE_TAG_VALUE':'br',
        'REMOVE_SPAN_HIDE_LOAD_TAB':'span.hideLoadTab',
        'COOKIE_EQQUAL_ATTRIBUTE': '=',
        'SEMICOLON_SIGN': ';',
        'ASSIGN_WHITESPACE': ' ',
        'COOKIE_NAME': 'onOffBingTranslation',
        'BING_ON_OFF_TRANSLATION_TOKEN':'true',
        'LANGUAGE_UNDEFINED': "undefined"
    };

    var callAjaxRequest = true;
    var originalWordValue = new Array();
    var languagePackage = function (defaultLanguage, currentLanguage) {
        var selfLanguage = this;
        this._dynamicLanguage = {};
        this.defaultLanguage = defaultLanguage || commonConstants.ENGLISH_LANGUAGE;
        this.currentLanguage = defaultLanguage || commonConstants.ENGLISH_LANGUAGE;

        $jQuery(function () {
            selfLanguage._getStringValue();
        });
    };

    /**
     * Object that holds the language packs.
     */
    languagePackage.prototype.languagePackage = {};

    /**
     * Array of translatable attributes to check for on elements.
     */
    languagePackage.prototype.attributeList = [commonConstants.HTML_TITLE_ATTRIBUTE, commonConstants.HTML_ALT_ATTRIBUTE, commonConstants.HTML_PLACEHOLDER_ATTRIBUTE];

    /**
     * Defines a language pack that can be dynamically loaded and the path to
     * use when doing so.
     * @param {String}
     *            languagePackage The language two-letter iso-code.
     * @param {String}
     *            path The path to the language pack js file.
     */
    languagePackage.prototype.dynamicLanguagePackage = function (languagePackage, languagePackagePath) {
        if (languagePackage !== undefined && languagePackagePath !== undefined) {
            this._dynamicLanguage[languagePackage] = languagePackagePath;
        }
    };

    /**
     * Loads a new language pack for the given language.
     * @param {string}
     *            languagePackage The language to load the pack for.
     * @param {Function=}
     *            callback Optional callback when the file has loaded.
     */
    languagePackage.prototype.loadPackage = function (languagePackage) {
        var selfLanguage = this;
        if (languagePackage && selfLanguage._dynamicLanguage[languagePackage]) {
            $jQuery.ajax({
                dataType: commonConstants.DATA_TYPE,
                url: selfLanguage._dynamicLanguage[languagePackage],
                success: function (loadNewPackageValues) {
                    if (Object.keys(loadNewPackageValues).length > commonConstants.ZERO_INDEX) {
                        selfLanguage.languagePackage[languagePackage] = loadNewPackageValues;
                    } else {
                        selfLanguage.languagePackage[languagePackage] = {};
                    }

                }
            });
        } else {
            throw (commonConstants.LANGUAGE_PACKAGE_LOAD_ERROR);
        }
    };

    /**
     * Scans the DOM for elements with [languagePackage] selector and saves translate data
     * for them for later use.
     */
    languagePackage.prototype._getStringValue = function (tagSelector) {
        var languageDropdownValue = $jQuery(commonConstants.JQUERY_FIND_BODY).find(commonConstants.FIND_ALL_BODY_HTML_TAG).not(commonConstants.FIND_MULTI_LANGUAGE_DROPDOWN_LIST_UL_LI);
        var selectedLanguageValue = languageDropdownValue.not(commonConstants.FIND_SPAN_MULTI_LANGUAGE_DROPDOWN_LISTBOX_DROPDOWN_INPUT);
        var originalText = selectedLanguageValue.not(commonConstants.FIND_MULTI_LANGUAGE_DROPDOWN_LIST_UL_LI);
        var removeVirtualKeyboardText = originalText.not(commonConstants.FIND_ADD_UPDATE_TRANSLATION_WINDOW_DIV_VIRTUAL_KEYBOARD_UL_LI);
        var removeHiddenLoadTab = removeVirtualKeyboardText.not(commonConstants.REMOVE_SPAN_HIDE_LOAD_TAB);
        var originalTextCount = removeHiddenLoadTab.length,
            textElement;
        while (originalTextCount--) {
            textElement = $jQuery(removeHiddenLoadTab[originalTextCount]);
            if (!textElement.is(commonConstants.CHECK_IS_LINK) && !textElement.is(commonConstants.CHECK_IS_SCRIPT) && !textElement.is(commonConstants.CHECK_IS_STYLE) && !textElement.is(commonConstants.CHECK_IS_NO_SCRIPT)) {
                this._storeContent(textElement);
            }
        }
    };

    /**
     * Reads the existing content from the element and stores it for later use
     * in translation.
     * @param textElement
     */
    languagePackage.prototype._storeContent = function (textElement) {
        if (textElement.is(commonConstants.INPUT_TAG)) {
            switch (textElement.attr(commonConstants.INPUT_TYPE)) {
            case commonConstants.HTML_INPUT_BUTTON_TYPE:
            case commonConstants.HTML_INPUT_SUBMIT_TYPE:
            case commonConstants.HTML_INPUT_RESET_TYPE:
                textElement.data(commonConstants.TRANSLATION_NODE_VALUE, textElement.val());
                break;
            case commonConstants.HTML_INPUT_TEXT_TYPE:
            case commonConstants.HTML_INPUT_SEARCH_TYPE:
                var placeholderValue = $jQuery(textElement).attr(commonConstants.HTML_PLACEHOLDER_ATTRIBUTE);
                if (placeholderValue) {
                    textElement.data(commonConstants.TRANSLATION_NODE_VALUE, placeholderValue);
                }
                var inputTypeValue = $jQuery(textElement).attr(commonConstants.HTML_ATTRIBUTE_VALUE);
                if (inputTypeValue) {
                    textElement.data(commonConstants.TRANSLATION_NODE_VALUE, inputTypeValue);
                }
                break;
            }
        } else if (textElement.is(commonConstants.TEXTAREA_TAG)) {
            var textareaPlaceholder = $jQuery(textElement).attr(commonConstants.HTML_PLACEHOLDER_ATTRIBUTE);
            if (textareaPlaceholder) {
                textElement.data(commonConstants.TRANSLATION_NODE_VALUE, textareaPlaceholder);
            }
            var textareaValue = $jQuery(textElement).val();
            if (textareaValue) {
                textElement.data(commonConstants.TRANSLATION_NODE_VALUE, textareaValue);
            }
        } else {
            var textValuesNode = this._getTextNodes(textElement);
            if (textValuesNode) {
                textElement.data(commonConstants.LANGUAGE_PACKAGE_TEXT_VALUE, textValuesNode);
            }
        }
    };

    /**
     * Retrieves the text nodes from an element and returns them in array wrap
     * into object with two properties: - node - which correspondence to text node, -
     * languageDefaultText - which remember current data of text node
     * @param textElement
     * @returns Array textNodeObject
     */
    languagePackage.prototype._getTextNodes = function (textElement) {
        var textValuesNode = textElement.contents(),
            textNodeObject = [],
            nodeObject = {};
        $jQuery.each(textValuesNode, function (nodeIndex, nodeValues) {
            nodeObject = {
                nodeValues: nodeValues,
                languageDefaultText: nodeValues.data
            };
            var emailIdValue = textElement.text();
            var emailIdValidation = commonConstants.EMAIL_ID_VALIDATION;
            if (emailIdValidation.test(emailIdValue) != true) {
                textNodeObject.push(nodeObject);
            }
        });
        return textNodeObject;
    };

    /**
     * Translates and sets the contents of an element to the passed language.
     * @param textElement
     * @param languagePackage
     */
    languagePackage.prototype._translateContent = function (textElement, languagePackage) {
        if (languagePackage == commonConstants.ARABIC_LANGUAGE_TEXT_ALIGN || languagePackage == commonConstants.URDU_LANGUAGE_TEXT_ALIGN || languagePackage == commonConstants.HEBREW_LANGUAGE_TEXT_ALIGN || languagePackage == commonConstants.PERSIAN_LANGUAGE_TEXT_ALIGN) {
            textElement.css(commonConstants.TEXT_ALIGN_CSS, commonConstants.TEXT_ALIGN_CSS_VALUE);
        }
        var languageNotDefault = languagePackage !== this.defaultLanguage,
            textTranslation, textValuesNode;
        if (textElement.is(commonConstants.INPUT_TAG)) {
            switch (textElement.attr(commonConstants.INPUT_TYPE)) {
            case commonConstants.HTML_INPUT_BUTTON_TYPE:
            case commonConstants.HTML_INPUT_SUBMIT_TYPE:
            case commonConstants.HTML_INPUT_RESET_TYPE:
                var originalText = textElement.data(commonConstants.TRANSLATION_NODE_VALUE);
                for (var inputTranslate = commonConstants.ZERO_INDEX; inputTranslate < textElement.length; inputTranslate++) {
                    var inputTextValues = this._wordTranslate(textElement, originalText, languagePackage);
                    textElement.attr(commonConstants.FIND_LANGUAGETRANSLATE_ATTRIBUTE, languagePackage);
                    $jQuery(textElement).closest(commonConstants.CUSTOM_LANGUAGE_TRANSLATE_ATTRIBUTE + languagePackage + commonConstants.END_ATTRIBUTE_SQUARE_BRACKET).after(commonConstants.ATTRIBUTE_SPAN_TAG + languagePackage + commonConstants.DISPLAY_NONE_CSS_STYLE + originalText + commonConstants.END_SPAN_TAG);
                    if (inputTextValues) {
                        textElement.val(inputTextValues);
                    }
                    if (!languageNotDefault) {
                        textElement.val(textElement.data(commonConstants.TRANSLATION_NODE_VALUE));
                    }
                }
                break;
            case commonConstants.HTML_INPUT_TEXT_TYPE:
            case commonConstants.HTML_INPUT_SEARCH_TYPE:
                var placeholderValue = $jQuery(textElement).attr(commonConstants.HTML_PLACEHOLDER_ATTRIBUTE);
                var inputTypeValue = $jQuery(textElement).attr(commonConstants.HTML_ATTRIBUTE_VALUE);
                if (placeholderValue || inputTypeValue) {
                    var originalText = textElement.data(commonConstants.TRANSLATION_NODE_VALUE);
                    for (var inputTranslate = commonConstants.ZERO_INDEX; inputTranslate < textElement.length; inputTranslate++) {
                        var inputTextValues = this._wordTranslate(textElement, originalText, languagePackage);
                        textElement.attr(commonConstants.FIND_LANGUAGETRANSLATE_ATTRIBUTE, languagePackage);
                        $jQuery(textElement).closest(commonConstants.CUSTOM_LANGUAGE_TRANSLATE_ATTRIBUTE + languagePackage + commonConstants.END_ATTRIBUTE_SQUARE_BRACKET).after(commonConstants.ATTRIBUTE_SPAN_TAG + languagePackage + commonConstants.DISPLAY_NONE_CSS_STYLE + originalText + commonConstants.END_SPAN_TAG);
                        if (placeholderValue) {
                            textElement.attr(commonConstants.HTML_PLACEHOLDER_ATTRIBUTE, inputTextValues);
                        }
                        if (inputTypeValue) {
                            textElement.attr(commonConstants.HTML_ATTRIBUTE_VALUE, inputTextValues);
                        }
                        if (!languageNotDefault) {
                            if (placeholderValue) {
                                textElement.attr(commonConstants.HTML_PLACEHOLDER_ATTRIBUTE, textElement.data(commonConstants.TRANSLATION_NODE_VALUE));
                            }
                            if (inputTypeValue) {
                                textElement.attr(commonConstants.HTML_ATTRIBUTE_VALUE, textElement.data(commonConstants.TRANSLATION_NODE_VALUE));
                            }
                        }
                    }
                }
                break;
            }
        } else if (textElement.is(commonConstants.TEXTAREA_TAG)) {
            var textareaPlaceholder = $jQuery(textElement).attr(commonConstants.HTML_PLACEHOLDER_ATTRIBUTE);
            var textareaValue = $jQuery(textElement).val();
            var originalText = textElement.data(commonConstants.TRANSLATION_NODE_VALUE);
            if (textareaPlaceholder || textareaValue) {
                for (var inputTranslate = commonConstants.ZERO_INDEX; inputTranslate < textElement.length; inputTranslate++) {
                    var textareaPlaceholderTranslate = this._wordTranslate(textElement, originalText, languagePackage);
                    textElement.attr(commonConstants.FIND_LANGUAGETRANSLATE_ATTRIBUTE, languagePackage);
                    $jQuery(textElement).closest(commonConstants.CUSTOM_LANGUAGE_TRANSLATE_ATTRIBUTE + languagePackage + commonConstants.END_ATTRIBUTE_SQUARE_BRACKET).after(commonConstants.ATTRIBUTE_SPAN_TAG + languagePackage + commonConstants.DISPLAY_NONE_CSS_STYLE + originalText + commonConstants.END_SPAN_TAG);
                    if (textareaPlaceholder) {
                        textElement.attr(commonConstants.HTML_PLACEHOLDER_ATTRIBUTE, textareaPlaceholderTranslate);
                    }
                    if (textareaValue) {
                        textElement.val(textareaPlaceholderTranslate);
                    }
                    if (!languageNotDefault) {
                        if (textareaPlaceholder) {
                            textElement.attr(commonConstants.HTML_PLACEHOLDER_ATTRIBUTE, textElement.data(commonConstants.TRANSLATION_NODE_VALUE));
                        }
                        if (textareaValue) {
                            textElement.val(textElement.data(commonConstants.TRANSLATION_NODE_VALUE));
                        }
                    }
                }
            }
        } else {
            var textValuesNode = textElement.data(commonConstants.LANGUAGE_PACKAGE_TEXT_VALUE);
            if (textValuesNode) {
                this._setTextNodes(textElement, textValuesNode, languagePackage);
            }
        }
    };

    /**
     * Call this to change the current language on the page.
     * @param {String}
     *            languagePackage The new two-letter language code to change to.
     * @param {String=}
     *            tagSelector Optional selector to find language-based elements for
     *            updating.
     * @param {Function=}
     *            languagePackageCallback Optional callback function that will be called once
     *            the language change has been successfully processed. This is
     *            especially useful if you are using dynamic language pack
     *            loading since you will get a callback once it has been loaded
     *            and changed. Your callback will be passed three arguments, a
     *            boolean to denote if there was an error (true if error), the
     *            second will be the language you passed in the change call (the
     *            languagePackage argument) and the third will be the selector used in the
     *            change update.
     */
    languagePackage.prototype.changeLanguagePackage = function (languagePackage, tagSelector) {
        var selfLanguage = this;
        if (languagePackage === this.defaultLanguage || this.languagePackage[languagePackage] || this._dynamicLanguage[languagePackage]) {
            if (languagePackage !== this.defaultLanguage) {
                if (!this.languagePackage[languagePackage] && this._dynamicLanguage[languagePackage]) {
                    this.loadPackage(languagePackage);
                	selfLanguage.getOriginalContentsTranslate(languagePackage);
                } else {
                    selfLanguage.getOriginalContentsTranslate(languagePackage);
                }
            } else {
                selfLanguage.getOriginalContentsTranslate(languagePackage);
            }
        }
    };

    /**
     * get all html body tag for translate its values.
     * @param string languagePackage
     */
    languagePackage.prototype.getOriginalContentsTranslate = function (languagePackage) {
        var languageDropdownValue = $jQuery(commonConstants.JQUERY_FIND_BODY).find(commonConstants.FIND_ALL_BODY_HTML_TAG).not(commonConstants.FIND_MULTI_LANGUAGE_DROPDOWN_LIST_UL_LI);
        var selectedLanguageValue = languageDropdownValue.not(commonConstants.FIND_SPAN_MULTI_LANGUAGE_DROPDOWN_LISTBOX_DROPDOWN_INPUT);
        var originalText = selectedLanguageValue.not(commonConstants.FIND_MULTI_LANGUAGE_DROPDOWN_LIST_UL_LI);
        var removeVirtualKeyboardText = originalText.not(commonConstants.FIND_ADD_UPDATE_TRANSLATION_WINDOW_DIV_VIRTUAL_KEYBOARD_UL_LI);
        var removeHiddenLoadTab = removeVirtualKeyboardText.not(commonConstants.REMOVE_SPAN_HIDE_LOAD_TAB);
        var originalTextCount = removeHiddenLoadTab.length,
            textElement;
        var languageNotDefault = languagePackage !== this.defaultLanguage;
        if (languageNotDefault) {
            while (originalTextCount--) {
                textElement = $jQuery(removeHiddenLoadTab[originalTextCount]);
                if (!textElement.is(commonConstants.CHECK_IS_LINK) && !textElement.is(commonConstants.CHECK_IS_SCRIPT) && !textElement.is(commonConstants.CHECK_IS_STYLE) && !textElement.is(commonConstants.CHECK_IS_NO_SCRIPT)) {
                    this._translateContent(textElement, languagePackage);
                }
            }
        }

        var bingTranslationTokenValue;
        var cookieNameValue = commonConstants.COOKIE_NAME + commonConstants.COOKIE_EQQUAL_ATTRIBUTE;
        var cookieName = document.cookie.split(commonConstants.SEMICOLON_SIGN);
        for (var cookieCounter = commonConstants.ZERO_INDEX; cookieCounter < cookieName.length; cookieCounter++) {
            var bingTokenTranslation = cookieName[cookieCounter];
            while (bingTokenTranslation.charAt(commonConstants.ZERO_INDEX) == commonConstants.ASSIGN_WHITESPACE) bingTokenTranslation = bingTokenTranslation.substring(commonConstants.FIRST_INDEX, bingTokenTranslation.length);
            if (bingTokenTranslation.indexOf(cookieNameValue) == commonConstants.ZERO_INDEX) {
                bingTranslationTokenValue = bingTokenTranslation.substring(cookieNameValue.length, bingTokenTranslation.length);
            }
        }

        if (originalWordValue.length > commonConstants.ZERO_INDEX && bingTranslationTokenValue==commonConstants.BING_ON_OFF_TRANSLATION_TOKEN && bingTranslationTokenValue!=undefined) {
            if (callAjaxRequest == true && bingTranslationToken == false) {
                console.log(originalWordValue);
                $jQuery.ajax({
                    url: filePathConstants.FILEPATH_URL + commonConstants.DISPLAY_TRANSLATION_CONTENT_URL,
                    data: {
                        originalWordValue: originalWordValue,
                        languageCode: languagePackage,
                        userReference:localStorage.getItem(commonConstants.TRANSLATION_USER_NAME),
                        referenceUrl:document.URL,
                    },
                    type: commonConstants.DATA_SOURSE_TYPE,
                    success: function (bingResponseValue) {
                        if (bingResponseValue == commonConstants.FIRST_INDEX) {
                            window.location.reload();
                        } else if (bingResponseValue == commonConstants.ZERO_INDEX) {
                            console.log(commonConstants.INTERNET_DOWN_MESSAGE);
                        } else {
                            console.log(bingResponseValue);
                        }
                    }
                });
                callAjaxRequest = false;
            }
        }
    };

    /**
     * Sets text nodes of an element translated based on the passed language.
     * @param textElement
     * @param Array textValuesNode array of objecs with text node and
     * defaultText returned from _getTextNodes.
     * @param languagePackage
     */
    languagePackage.prototype._setTextNodes = function (textElement, textValuesNode, languagePackage) {
        var nodeIndex, textNode, defaultText, textTranslation, languageNotDefault = languagePackage !== this.defaultLanguage;
        var breakLineTagElemenet= new Array();
        for (nodeIndex = commonConstants.ZERO_INDEX; nodeIndex < textValuesNode.length; nodeIndex++) {
            textNode = textValuesNode[nodeIndex];
            if (languageNotDefault) {
                defaultText = $jQuery.trim(textNode.languageDefaultText);
                if (defaultText) {
                    wordTranslate = this._wordTranslate(textElement, defaultText, languagePackage);
                    if (wordTranslate) {
                        try {
                            textNode.nodeValues.data = textNode.nodeValues.data.split(
                                $jQuery.trim(textNode.nodeValues.data)).join(wordTranslate);
                            var lineSeperatorLowerCase = $jQuery.trim(textNode.languageDefaultText).toLowerCase();
                            var lineSeperator = lineSeperatorLowerCase.split(commonConstants.FIND_DOT_SEPARATER_REGEX);
                            var translateLineSeperator = wordTranslate.split(commonConstants.FIND_DOT_SEPARATER_REGEX);
                            var defaultSentenceLineSeperator = lineSeperator.filter(function(sentenceValue){ 
                                return sentenceValue != null && sentenceValue !== commonConstants.ASSIGN_BLANK_VARIABLE && sentenceValue !== undefined; 
                            });
                            var translateSentencelineSeperator = translateLineSeperator.filter(function(translateSentenceValue){ 
                                return translateSentenceValue != null && translateSentenceValue !== commonConstants.ASSIGN_BLANK_VARIABLE && translateSentenceValue !== undefined; 
                            });
                            var originalSentencelineSeperator = lineSeperator.filter(Boolean);
                            var matchLineSeperator = lineSeperatorLowerCase.match(commonConstants.KEEP_DELIMITER_FROM_STRING);
                            var translatedSentence = wordTranslate.match(commonConstants.KEEP_DELIMITER_FROM_STRING);
                            var textElementCombine = {};
                            var originalSentenceParentElement = $jQuery(textElement).contents();
                            if($jQuery(textElement).find(commonConstants.FIND_BREAK_LINE_TAG_VALUE).length>commonConstants.ZERO_INDEX){
                                var findBreakLineTagValue = $jQuery(textElement).html();
                                var findBreakLineTagElementValue = findBreakLineTagValue.split(commonConstants.SPLIT_TAG_VALUE_WITH_BREAK_LINE);
                                var originalSentenceValues = defaultText;
                                var translationSentenceValues = wordTranslate;
                                for(var findBreakLineTag=commonConstants.ZERO_INDEX;findBreakLineTag<commonConstants.FIRST_INDEX;findBreakLineTag++){
                                    breakLineTagElemenet.push(commonConstants.LANGUAGETRANSLATE_ATTRIBUTE_SPAN_TAG + languagePackage + commonConstants.END_PLURAL_SPAN_TAG + translationSentenceValues + commonConstants.ORIGINAL_SENETENCE_SPAN_TAG + languagePackage + commonConstants.DISPLAY_NONE_CSS_STYLE + originalSentenceValues + commonConstants.END_SPAN_TAG+commonConstants.SPLIT_TAG_VALUE_WITH_BREAK_LINE);
                                }
                            }
                            else if($jQuery(textElement).children().length>commonConstants.ZERO_INDEX && originalSentenceParentElement){
                                var originalSentenceChildElement = $jQuery(textElement).children().contents().not(commonConstants.ORIGINALSENTENCE_CLASS_NAME + languagePackage);
                                if(originalSentenceChildElement.length<=commonConstants.FIRST_INDEX && originalSentenceParentElement){
                                    
                                    if($jQuery(textElement).next(commonConstants.JQUERY_FIND_SPAN).length==commonConstants.ZERO_INDEX){
                                        $jQuery(textElement).after(commonConstants.ATTRIBUTE_SPAN_TAG + languagePackage + commonConstants.DISPLAY_NONE_CSS_STYLE + defaultText + commonConstants.END_SPAN_TAG);
                                    }
                                    else if($jQuery(textElement).children().contents(commonConstants.JQUERY_FIND_SPAN).length==commonConstants.ZERO_INDEX){
                                        $jQuery(originalSentenceChildElement).after(commonConstants.ATTRIBUTE_SPAN_TAG + languagePackage + commonConstants.DISPLAY_NONE_CSS_STYLE + defaultText + commonConstants.END_SPAN_TAG);
                                    }
                                    $jQuery(textElement).attr(commonConstants.FIND_LANGUAGETRANSLATE_ATTRIBUTE, languagePackage);
                                    $jQuery(originalSentenceChildElement).attr(commonConstants.FIND_LANGUAGETRANSLATE_ATTRIBUTE, languagePackage);
                                }
                                else{
                                    textElement.attr(commonConstants.FIND_LANGUAGETRANSLATE_ATTRIBUTE, languagePackage).after(commonConstants.ATTRIBUTE_SPAN_TAG + languagePackage + commonConstants.DISPLAY_NONE_CSS_STYLE + defaultText + commonConstants.END_SPAN_TAG);
                                }
                            }
                            else if (matchLineSeperator || translatedSentence) {
                                var translateSentenceRemoveEmptyElement = translatedSentence.filter(Boolean);
                                var originalSentenceRemoveEmptyElement = matchLineSeperator.filter(Boolean);
                                for (var lineSeparatorStore = commonConstants.ZERO_INDEX; lineSeparatorStore < originalSentencelineSeperator.length; lineSeparatorStore++) {
                                    if(translateSentenceRemoveEmptyElement[lineSeparatorStore]!=undefined){
                                        textElementCombine[originalSentenceRemoveEmptyElement[lineSeparatorStore]] = translateSentenceRemoveEmptyElement[lineSeparatorStore];
                                    }
                                    else{
                                        textElementCombine[defaultSentenceLineSeperator[lineSeparatorStore]] = translateSentencelineSeperator[lineSeparatorStore];
                                    }
                                }
                                var combineWordToSentence=commonConstants.ASSIGN_BLANK_VARIABLE;
                                $jQuery.map(textElementCombine, function (textElementCombineKey, textElementCombineValue) {
                                    combineWordToSentence +=commonConstants.LANGUAGETRANSLATE_ATTRIBUTE_SPAN_TAG + languagePackage + commonConstants.END_PLURAL_SPAN_TAG + textElementCombineKey + commonConstants.ORIGINAL_SENETENCE_SPAN_TAG + languagePackage + commonConstants.DISPLAY_NONE_CSS_STYLE + textElementCombineValue.trim() + commonConstants.END_SPAN_TAG;
                                });
                                $jQuery(textElement).html(combineWordToSentence);
                            } else {
                                textElement.attr(commonConstants.FIND_LANGUAGETRANSLATE_ATTRIBUTE, languagePackage).after(commonConstants.ATTRIBUTE_SPAN_TAG + languagePackage + commonConstants.DISPLAY_NONE_CSS_STYLE + defaultText + commonConstants.END_SPAN_TAG);
                            }
                        } catch (translationError) {}
                    } else {
                        textNode.nodeValues.data = textNode.languageDefaultText;
                    }
                }
            } else {
                try {
                    textNode.nodeValues.data = textNode.languageDefaultText;
                } catch (translationError) {}
            }
        }
    };

    /**
     * Translates text from the default language into the passed language.
     * @param {String}
     *            text The text to translate.
     * @param {String}
     *            languagePackage The two-letter language code to translate to.
     * @returns replacedTranslationText
     */
    languagePackage.prototype._wordTranslate = function (textElement, originalValues, languagePackage) {
        languagePackage = languagePackage || this.currentLanguage;
        if (typeof originalValues !== commonConstants.LANGUAGE_UNDEFINED) {
            var findCommentedTag = originalValues.match(commonConstants.FIND_COMMENTED_HTML_TAG);
        }
        if (!findCommentedTag) {
            if (this.languagePackage[languagePackage]) {
                var textTranslation = commonConstants.ASSIGN_BLANK_VARIABLE;
                var replacedTranslationText = commonConstants.ASSIGN_BLANK_VARIABLE;
                var replacedTranslationSentence = commonConstants.ASSIGN_BLANK_VARIABLE;
                var findNonAlphabetic = commonConstants.FIND_NON_ALPHABETIC_CHARATERS;
                if (languagePackage != this.defaultLanguage) {
                	if (typeof originalValues !== commonConstants.LANGUAGE_UNDEFINED) {
	                    var paragraphLineSeperator = originalValues.toLowerCase();
	                    var lineSeperatorLowerCase = paragraphLineSeperator.trim();
	                    var splitLineSeperator = lineSeperatorLowerCase.split(commonConstants.FIND_DOT_SEPARATER_REGEX);
	                    var lineSeperator = splitLineSeperator.filter(function(sentenceValue){ 
	                        return sentenceValue != null && sentenceValue !== commonConstants.ASSIGN_BLANK_VARIABLE && sentenceValue !== undefined; 
	                    });
	                    var matchLineSeperator = lineSeperatorLowerCase.match(commonConstants.KEEP_DELIMITER_FROM_STRING);
	                    for (var lineSeparatorStore = commonConstants.ZERO_INDEX; lineSeparatorStore < lineSeperator.length; lineSeparatorStore++) {
	                        var sentenceFromLine = commonConstants.ASSIGN_BLANK_VARIABLE;
	                        if (matchLineSeperator!=null) {
	                            if(matchLineSeperator[lineSeparatorStore]!= undefined){
	                                sentenceFromLine = matchLineSeperator[lineSeparatorStore];
	                            }
	                            else {
	                                sentenceFromLine = lineSeperator[lineSeparatorStore];
	                            }
	                        } else {
	                            sentenceFromLine = lineSeperator[lineSeparatorStore];
	                        }
	                        if (typeof (sentenceFromLine) != commonConstants.NOT_DEFINE_VARIABLE && sentenceFromLine.length > commonConstants.ZERO_INDEX) {
	                            var originalSentence = sentenceFromLine.trim();
	                            var findSpecialSymbolSentence = originalSentence.match(commonConstants.FIND_SPECIAL_SYMBOL_SENTENCE);
	                            if (findSpecialSymbolSentence != null) {
	                                replacedTranslationText = replacedTranslationText + sentenceFromLine.replace(sentenceFromLine, sentenceFromLine);
	                            } else {
	                                var findSeparateSpecialCharacter = originalSentence.replace(commonConstants.FIND_DOUBLE_QUOTE_SYMBOL, commonConstants.ASSIGN_BLANK_VARIABLE);
	                                if (findSeparateSpecialCharacter.length > commonConstants.ZERO_INDEX) {
	                                    var sentenceTranslation = this.languagePackage[languagePackage][findSeparateSpecialCharacter];
	                                    if (sentenceTranslation) {
	                                        var arrangeSentenceAfterTranslate = sentenceTranslation.replace(commonConstants.FIND_SENTENCE_END_FROM_TRANSLATION, commonConstants.ADD_SPACE_WITH_END_TRANSLATION);
	                                        replacedTranslationText = replacedTranslationText + sentenceFromLine.replace(sentenceFromLine, arrangeSentenceAfterTranslate);
	                                    } else {
	                                        replacedTranslationText = replacedTranslationText + sentenceFromLine.replace(sentenceFromLine, sentenceFromLine);
	                                        var checkDuplicateWord = $jQuery.inArray(findSeparateSpecialCharacter, originalWordValue);
	                                        if (checkDuplicateWord < commonConstants.ZERO_INDEX) {
	                                            originalWordValue.push(findSeparateSpecialCharacter);
	                                        }
	                                    }
	                                }
	                            }
	                        }
	                    }
	                    return replacedTranslationText;
                	}
                }
            }
        }
    };
    return languagePackage;
})();
