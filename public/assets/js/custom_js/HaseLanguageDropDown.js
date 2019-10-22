 var userLanguageList = {
	COOKIES_LANGUAGE_CODE:'languageCode',
	COOKIE_EXPIRE_DAY: 7,
    COOOKIE_HOUR: 24,
    COOKIE_MINUTES: 60,
    COOKIE_MILISECOND: 1000,
    COOKIE_EXPIRE_ATTRIBUTE: '; expires=',
    EQUAL_SEPARATOR:'=',
    COOKIE_PATH_ATTRIBUTE: '; path=/',
    SEMICOLON_SIGN: ';',
    WHITESPACE_LANGUAGE_NAME: ' ',
    COUNTER_VALUE:0,
    LANGUAGE_SUBSTRING_VALUE:1,
    JSON_DATA_TYPE: "json",
    DATA_TYPE: "GET",
    LANGUAGE_NAME_FIELD:"language_name",
    LANGUAGE_CODE_FIELD:"language_code",
    MULTI_LANGUAGE_DROPDOWN_ID:"#multiLanguageDropdown",
    KENDO_DROPDOWN_LIST_VALUE:"kendoDropDownList",
    DEFAULT_LANGUAGE_CODE:"en_us",
    DROPDOWN_VALUE: "value",
    DROPDOWN_ID: "id",
    LANGUAGE_NAME:"languageName",
    USER_KNOWN_LANGUAGE_LIST:'user_known_language_list',
    LANGUAGE_NAME_TILTE:'Language Name',
    SELECT_LANGUAGE_LABEL:'Select Language',
}
 /**
  * Function is dispaly language in drop down.
  */
function userKnownLanguageDropDown(){
    var cookieName = userLanguageList.COOKIES_LANGUAGE_CODE;
    languageCode = readLanguageNameCookie(cookieName);
    if (!languageCode) {
        languageCode = userLanguageList.DEFAULT_LANGUAGE_CODE;
    }
	jQuery(userLanguageList.MULTI_LANGUAGE_DROPDOWN_ID)
			.kendoDropDownList(
					{
						dataTextField : userLanguageList.LANGUAGE_NAME_FIELD,
						dataValueField : userLanguageList.LANGUAGE_CODE_FIELD,
						optionLabel : userLanguageList.SELECT_LANGUAGE_LABEL,
						dataSource : {
							transport : {
								read : {
									dataType : userLanguageList.JSON_DATA_TYPE,
									url : userLanguageList.USER_KNOWN_LANGUAGE_LIST,
									type : userLanguageList.DATA_TYPE
								}
							}
						},
					        value: languageCode,
					        change: function () {
					            var languageValues = jQuery(userLanguageList.MULTI_LANGUAGE_DROPDOWN_ID).data(userLanguageList.KENDO_DROPDOWN_LIST_VALUE);
							    console.log(languageValues);
                                languageCode = this.value();
					            if (!languageCode) {
					                languageCode = userLanguageList.DEFAULT_LANGUAGE_CODE;
					            }
					            var cookieExpireDays = userLanguageList.COOKIE_EXPIRE_DAY;
					            setLanguageName(cookieName, languageCode, cookieExpireDays);
					            window.location.reload();
					        }
					});
}
/**
* Set cookie for language name to get/set language which reviewer select from dropdown.
* @param string cookieName
* @param string languageCode
* @param integer cookieExpireDays
*/
function setLanguageName(cookieName,languageCode,cookieExpireDays) {
    if (cookieExpireDays) {
        var setCookieDate = new Date();
        setCookieDate.setTime(setCookieDate.getTime() + (cookieExpireDays * userLanguageList.COOOKIE_HOUR * userLanguageList.COOKIE_MINUTES * userLanguageList.COOKIE_MINUTES * userLanguageList.COOKIE_MILISECOND));
        var cookieExpire = userLanguageList.COOKIE_EXPIRE_ATTRIBUTE + setCookieDate.toGMTString();
    } else var cookieExpire = userLanguageList.SINGLE_SEPARATORS;
    document.cookie = cookieName + userLanguageList.EQUAL_SEPARATOR + languageCode + cookieExpire + userLanguageList.COOKIE_PATH_ATTRIBUTE;
}

/**
 * Read cookie for get language name for set language for translator.
 * @param string languageCodeName
 * @returns string languageName
 */
function readLanguageNameCookie(languageCodeName) {
    var cookieNameValue = languageCodeName + userLanguageList.EQUAL_SEPARATOR;
    var cookieName = document.cookie.split(userLanguageList.SEMICOLON_SIGN);
    for (var cookieCounter = userLanguageList.COUNTER_VALUE; cookieCounter < cookieName.length; cookieCounter++) {
        var languageName = cookieName[cookieCounter];
        while (languageName.charAt(userLanguageList.COUNTER_VALUE) == userLanguageList.WHITESPACE_LANGUAGE_NAME) languageName = languageName.substring(userLanguageList.LANGUAGE_SUBSTRING_VALUE, languageName.length);
        if (languageName.indexOf(cookieNameValue) == userLanguageList.COUNTER_VALUE) {
            return languageName.substring(cookieNameValue.length, languageName.length);
        }
    }
}