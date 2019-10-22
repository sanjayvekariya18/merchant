<?php
/**
 * This file is for add update language translation from user side with manually
 * and it will be save into DB, tiki side as well into csv files.
 * */
const ROOT_DIRECTORY                      =__DIR__;
const TRANSLATION_LANGUAGE_FOLDER         ='\\language-translate-storage\\';
const TRANSLATION_LANGUAGE_FILE           ='\\TranslationContents.csv';
const COUNT_WORDS                         =0;
const SECONS_KEY_INDEX                    =2;
const CONTENT_MATCH_CONDITION_START       ='#(.*)"(';
const CONTENT_FIND_EXIST_TRANSLATION_VALUE=')":\s?"(.*)"(.*)#m';
const REGEX_LIMIT                         =-1;
const DATE_TIME_PARAMETER                 ='Y-m-d H:i:s';
const DETAIL_TAB_TIKI_PATH                ='MagentoActivityDetailTabs.php?action=';
const CREATE_UPDATE_TIKI_PAGE_MAGENTO_SIDE='createUpdateTikiPageMagentoside';
const TIKI_DIRECOTRY_FOLDER               ='tiki';
const DASH_SEPARATER                      ='-';
const TIKI_DIRECTORY_SEPARATOR            ='/';
const WEB_PROTOCOL                        ='http://';
const ENCODED_STRING                      ='UTF-8';
const TRANSLATION_STATUS_MESSAGE          ='Success';
const FIRST_LANGUAGE_ID_FLAG              =1;
const BLANK_STRING                        ='';
const BING_LANGUAGE_TEXT                  ='Text=';
const TO_LANGUAGE                         ='&To=';
const FROM_LANGUAGE                       ='&From=';
const BING_REQUEST_URL                    ='http://api.microsofttranslator.com/v2/Http.svc/Translate?';
const BING_AUTHENTICATION_MESSAGE         ='Authorization: Bearer ';
const GRANT_TYPE                          ="client_credentials";
const CLIENT_ID                           ="president_hitmystyle_1990";
const CLIENT_SECRET                       ="bDm64yc7Lug6H1grqdRIRQ+JP+tLNuwXLKhE8wgu8TI=";
const ADMIN_USER_REFERENCE                ='admin';
const BING_USER_REFERENCE                 ='bing';
const REPLACE_NEW_TRANSLATION             ='$1"$2":"';
const ADD_REST_VALUE_FROM_FILE            ='"$4';
const ADD_NEW_WORD_STARTING               ='#({)\s?(.*)#m';
const ADD_NEW_WORD_IN_FILE                ="\n\t\"";
const ADD_COLON                           ="\":\"";
const ADD_REST_VALUE_MATCH                ="\",\n$2";
const ADD_VALUE_NOT_EXISTS                ="\"\n$2";
const WRITE_FIRST_CONDITION_VALUE='$1';
const BING_DEFAULT_FROM_LANGUAGE='en';
const INTERNET_ISSUE='Internet is slow/down';
const FIND_DOUBLE_QUOTE_SYMBOL = '#["\[\]\{\}]#m';
const FIND_SPECIAL_SYMBOL = '#["\(\)\*\#\[\]\{\}\$\^]#m';
const USER_TRANSLATION_REFERENCE = 'user';
const DETAIL_TAB_TIKI_PAGE_HISTORY='detailTabTikiPageHistory';
const TRANSLATION_COMPARE_REVISION_HISTORY='tabCompareHistoryRevision';
const CHECK_TIKI_PAGE_NAME_EXIST          ='checkTikiPageNameExist';
const SEPARATE_SENTENCE='/[,.!?]/';
const TRANSLATION_CONFIGURATION_FILE_PATH = 'lib/TranslationConfiguration.php';
const DATABASE_FILE_PATH = 'lib/database.php';
const DEFINE_CONTENT_TYPE_CHARACTER_SET= 'Content-Type: text/html; charset=utf-8';
const ORIGINAL_WORD_VALUE = 'originalWordValue';
const LANGUAGE_CODE = 'languageCode';
const REMOTE_ADDRESS = 'REMOTE_ADDR';
const ORIGINAL_ID = 'or_id';
const TRANSLATION_FLAG = 'flag';
const FIND_HOST_NAME = 'HTTP_HOST';
const TIKIPAGE_NAME_EXIST = 'tikiPageNameExist';
const DETAIL_TAB_VALUES = 'detailTabValues';
const DETAIL_TAB_PAGE_NAME = 'detailTabPageName';
const DETAIL_TAB_PAGE_ID = 'detailTabPageId';
const MAGENTO_USER = 'magentoUser';
const LANGUAGE_NAME = 'language';
const IP_ADDRESS = 'ipAddress';
const TIKI_PAGE_ID = 'tikiPageId';
const TIKI_PAGE_DATA = 'tikiPageData';
const TIKI_PAGE_VERSION = 'tikiPageVersion';
const TRANSLATION_ID = 'translationId';
const USER_REFERENCE = 'userReference';
const ADMIN_APPROVAL_TRANSLATION_FLAG = 'translationFlag';
const NEW_TRANSLATION_TEXT = 'newTranslationText';
const ORIGINAL_CONTENT = 'originalContent';
const CONTENT_TYPE_JSON = "Content-type: application/json";
const TRANSLATION_RESPONSE = "response";
const TRANSLATION_MESSAGE = "message";
const NEW_TRANSLATE_VALUE = 'newTranslateValue';
const SENTENCE_REFERENCE = 'sentenceReference';
const LANGUAGE_CODE_NAME = 'languageCodeName';
const ORIGINAL_WORD = 'original_word';
const LANGUAGE_FIELD_ID = 'id';
const TRANSLATION_FIELD_ID = 'translation';
const TIKI_ID = 'tiki_id';
const BING_LANGUAGE_CODE = 'bing_language_code';
const BING_GRANT_TYPE = 'grant_type';
const BING_TRANSLATION_SCOPE = 'scope';
const BING_CLIENT_ID = 'client_id';
const BING_CLIENT_SECRET_KEY = 'client_secret';
const CONTENT_TYPE_XML = "Content-Type: text/xml";
const ORIGINAL_SENTENCE_VALUE = 'originalSentenceValue';
const OLD_VERSION_NUMBER = 'oldVersionNumber';
const NEW_VERSION_NUMBER = 'newVersionNumber';
const OLDVER_TIKI_TAB_VERSION = 'oldverTikiTabVersion';
const NEWER_TIKI_TAB_VERSION = 'newverTikiTabVersion';
const OLD_DATA_REVISION = 'oldDataRevision';
const NEW_DATA_REVISION = 'newDataRevision';
const TIKI_VERSION = 'tiki_version';
const CALL_FUNCTION_KEY = 'callFunction';
const THIRD_KEY_INDEX = 3;
const ONE_HUNDRED_TWENTY_SEVENTH_CHARACTER = 127;
const REFERENCE_URL='referenceUrl';
const ORIGINAL_WORD_ID_FIELD='original_word_id';
const HISTORY_ID_STATUS_FIELD='history_id';
const ORIGINAL_WORD_ID_BIND_PARAM=':originalWordId';
const LANGUAGE_CODE_BIND_PARAM=':languageCode';
const WORD_STATUS_BIND_PARAM=':wordStatusValue';
const ORIGINAL_ID_FIELD="or_id";
const TRANSLATED_CONTENTS_BIND_PARAM=':translatedContents';
const SENTENCE_REJECT_VALUE='2';
const SENTENCE_HISTORY_STATUS_XML_ACTION='TikiWordsList.php?callFunction=wordTranslationMultipleEntriesDisplay';
const ORIGINAL_WORD_ID_TITLE='originalWordId';
const SELECT_ORIGINAL_ID_QUERY='SELECT or_id FROM english WHERE original_word=:translatedContents';
const WORD_TRANSLATION_ENTRIES='wordsTranslationEntries';
const SENTENCE_ORIGINAL_WORD='originalWord';
const HISTORY_ID_FIELD='historyId';
const LANGUAGE_ID_FIELD='language_id';
const WORD_STATUS_FIELD='translation_status';
const WORD_TRANSLATION_STATUS='wordTranslationStatus';
const DATA_FIELD="data";
const TRANSLATION_TIKI_HISTORY_ID_XML_ACTION='TikiWordsList.php?callFunction=tikiTranslationHistory';
const WORD_TRANSLATION='wordTranslation';
const TRANSLATION_HISTORY_ID_ADD_XML_ACTION='TikiWordsList.php?callFunction=addTranslationHistoryId';
const SENTENCE_ORIGINAL_HTML="This Sentence is <b><span style='color:blue'>Original.</span></b>";
const SENTENCE_DATE_TIME_FIELD='sentenceDateTime';
const DATE_TIME_FORMAT='Y-m-d H:i:s';
const TIKI_HISTORY_DATE_TIME_FIELD='lastModif';
const SENTENCE_CURRENT_STATUS_HISTORY_ID_XML_ACTION='TikiWordsList.php?callFunction=sentenceCurrentStatusHistoryId';
const SENTENCE_TIME_ZONE='sentenceTimeZone';
const ORIGINAL_ID_BIND_PARAM=':originalId';

include TRANSLATION_CONFIGURATION_FILE_PATH;
include CURL_ACCESS_FILEPATH;
use curl\ CurlWrapper;
header(DEFINE_CONTENT_TYPE_CHARACTER_SET);
require_once(DATABASE_FILE_PATH);

/**
 * This class is for add update language translation from user side with manually
 * and it will be save into DB, tiki side as well into csv files.
 * */
class AddUpdateTranslation {
    /**
     * authentication url for bing api.
     * */
    private $authenticationUrl='https://datamarket.accesscontrol.windows.net/v2/OAuth2-13/';
    /**
     * scope url for bing api.
     * */
    private $scopeUrl='http://api.microsofttranslator.com';
    /**
     * This function is for add update language translation from user side with manually
     * and it will be save into DB, tiki side as well into csv files.
     * @return null
     * */
    public function addUpdateLanguageTranslate() {
        global $databaseConnection;
        $originalContentWords=$_POST[ORIGINAL_WORD_VALUE];
        $languageCode        =$_POST[LANGUAGE_CODE];
        $referenceUrl=$_POST[REFERENCE_URL];
        $dateTime            =date(DATE_TIME_PARAMETER);
        $ipAddress           =$_SERVER[REMOTE_ADDRESS];
        $userReference       =ADMIN_USER_REFERENCE;
        $translationFlag     =$wordCounter=COUNT_WORDS;
        $bingUserReference   =BING_USER_REFERENCE;
        foreach ($originalContentWords as $originalWord) {
            $selectedQuery=$databaseConnection->prepare("SELECT or_id,original_word FROM word_translation_stage WHERE original_word=:originalWord AND language_code=:languageCode");
            $selectedQuery->bindParam(':originalWord', $originalWord);
            $selectedQuery->bindParam(':languageCode', $languageCode);
            $selectedQuery->execute();
            $fetchSelectedValues   =$selectedQuery->fetch();
            $existLanguageReference=$fetchSelectedValues[ORIGINAL_ID];
            $translateSelectQuery  =$databaseConnection->prepare('SELECT flag FROM '.$languageCode.' WHERE or_id=:mainLanguageReference');
            $translateSelectQuery->bindParam(':mainLanguageReference', $existLanguageReference);
            $translateSelectQuery->execute();
            $fetchTranslateSelectedValues=$translateSelectQuery->fetch();
            $translateFlag=$fetchTranslateSelectedValues[TRANSLATION_FLAG];
            if ($translateFlag!=SECONS_KEY_INDEX) {
                preg_match_all(SEPARATE_SENTENCE,$originalWord,$matchSentence);
                $splitSentecenFromParagraph = preg_split(SEPARATE_SENTENCE, $originalWord);
                $bingTranslation=$this->addUpdateTranslateByBingApi($languageCode, $splitSentecenFromParagraph[COUNT_WORDS]);
                if ($bingTranslation) {
                    $specialSymbol=BLANK_STRING;
                    foreach ($matchSentence[COUNT_WORDS] as $matchSentenceKey => $matchSentenceValue) {
                        $specialSymbol .= $matchSentenceValue;
                    }
                    $this->addUpdateTranslateSentenceStage($originalWord, $bingTranslation.$specialSymbol, $languageCode, $bingUserReference, $translationFlag, $existLanguageReference,$referenceUrl);
                }
                else {
                    return false;
                }
            }
            $wordCounter++;
        }
        return true;
    }
    /**
     * This function is for add update language translation from user side with manually
     * and it will be save tiki side as well into csv files.
     * @param string $newTranslationText
     * @param integer $tikiPageId
     * @param string $userReference
     * @param string $languageCode
     * @param integer $ipAddress
     * @param integer $LanguegeId
     * @param string $originalContentWords
     * @return null
     * */
    private function addUpdateTranslationTiki($newTranslationText, $tikiPageId, $userReference, $languageCode, $ipAddress, $LanguegeId, $originalContentWords) {
        global $databaseConnection;
        //check translation tikipage exist
        $checkTikiPageNameExistUrl=ACCESS_TIKI_FROM_SEPARATE_SYSTEM.TIKI_DIRECTORY_SEPARATOR.DETAIL_TAB_TIKI_PATH.CHECK_TIKI_PAGE_NAME_EXIST;
        $translationTikiPage      =array(TIKIPAGE_NAME_EXIST=>$LanguegeId.DASH_SEPARATER.$languageCode);
        $translationTikiPageExist=$this->curlMultipleOptions($checkTikiPageNameExistUrl, $translationTikiPage);
        if ($translationTikiPageExist==COUNT_WORDS) {
            $tikiInsertUrl=ACCESS_TIKI_FROM_SEPARATE_SYSTEM.TIKI_DIRECTORY_SEPARATOR.DETAIL_TAB_TIKI_PATH.CREATE_UPDATE_TIKI_PAGE_MAGENTO_SIDE;
            $initialTranslationValues=array(DETAIL_TAB_VALUES=>$originalContentWords, DETAIL_TAB_PAGE_NAME=>$LanguegeId.DASH_SEPARATER.$languageCode, DETAIL_TAB_PAGE_ID=>$tikiPageId, MAGENTO_USER=>$userReference, LANGUAGE_NAME=>$languageCode, IP_ADDRESS=>$ipAddress);
            $this->curlMultipleOptions($tikiInsertUrl, $initialTranslationValues);
        }
        $tikiUrl              =ACCESS_TIKI_FROM_SEPARATE_SYSTEM.TIKI_DIRECTORY_SEPARATOR.DETAIL_TAB_TIKI_PATH.CREATE_UPDATE_TIKI_PAGE_MAGENTO_SIDE;
        $translationValues    =array(DETAIL_TAB_VALUES=>$newTranslationText, DETAIL_TAB_PAGE_NAME=>$LanguegeId.DASH_SEPARATER.$languageCode, DETAIL_TAB_PAGE_ID=>$tikiPageId, MAGENTO_USER=>$userReference, LANGUAGE_NAME=>$languageCode, IP_ADDRESS=>$ipAddress);
        $tikiLanguageReference=$this->curlMultipleOptions($tikiUrl, $translationValues);
        $tikiPageData         =json_decode($tikiLanguageReference, true);
        foreach ($tikiPageData as $tikiPageValues) {
            $translationTikiPageId=$tikiPageValues[TIKI_PAGE_ID];
            $newTranslateWord     =$tikiPageValues[TIKI_PAGE_DATA];
            $tikiPageVersion      =$tikiPageValues[TIKI_PAGE_VERSION];
        }
        /**
         * @todo here required to change the project exicution process, so until QA done keep this code as commented.
         */
       /* $csvFilePathUrl    =ROOT_DIRECTORY.TRANSLATION_LANGUAGE_FOLDER.$languageCode.TRANSLATION_LANGUAGE_FILE;
        $fileGetCsvContents=file_get_contents($csvFilePathUrl);
        for ($replaceSpecialSymbol = COUNT_WORDS; $replaceSpecialSymbol <= THIRD_KEY_INDEX.FIRST_LANGUAGE_ID_FLAG; ++$replaceSpecialSymbol) {
            $fileGetCsvContent = str_replace(chr($replaceSpecialSymbol), BLANK_STRING, $fileGetCsvContents);
        }
        $fileGetCsvContent = str_replace(chr(ONE_HUNDRED_TWENTY_SEVENTH_CHARACTER), BLANK_STRING, $fileGetCsvContent);
        if (COUNT_WORDS === strpos(bin2hex($fileGetCsvContent), $originalContentWords)) {
           $fileGetCsvContent = substr($fileGetCsvContent, THIRD_KEY_INDEX);
        }
        $fileGetCsvContents=preg_replace(CONTENT_MATCH_CONDITION_START.$originalContentWords.CONTENT_FIND_EXIST_TRANSLATION_VALUE, REPLACE_NEW_TRANSLATION.$newTranslationText.ADD_REST_VALUE_FROM_FILE, $fileGetCsvContents, REGEX_LIMIT, $countWords);
        if ($countWords<=COUNT_WORDS) {
            $checkTranslationValues=(array) json_decode($fileGetCsvContent);
            if (empty($checkTranslationValues)) {
                $fileGetCsvContents=preg_replace(ADD_NEW_WORD_STARTING, WRITE_FIRST_CONDITION_VALUE.ADD_NEW_WORD_IN_FILE.$originalContentWords.ADD_COLON.$newTranslateWord.ADD_VALUE_NOT_EXISTS, $fileGetCsvContents);
            }
            else {
                $fileGetCsvContents=preg_replace(ADD_NEW_WORD_STARTING, WRITE_FIRST_CONDITION_VALUE.ADD_NEW_WORD_IN_FILE.$originalContentWords.ADD_COLON.$newTranslateWord.ADD_REST_VALUE_MATCH, $fileGetCsvContents);
            }
        }
        file_put_contents($csvFilePathUrl, $fileGetCsvContents); */
        $addTikiPageIdReference=$databaseConnection->prepare("UPDATE $languageCode SET tiki_id=:tikiPageIdReference,tiki_version=:tikiPageVersion WHERE id=:mainLanguageReference");
        $addTikiPageIdReference->bindParam(':tikiPageIdReference', $translationTikiPageId);
        $addTikiPageIdReference->bindParam(':tikiPageVersion', $tikiPageVersion);
        $addTikiPageIdReference->bindParam(':mainLanguageReference', $LanguegeId);
        $addTikiPageIdReference->execute();

        $historyIdUrl=ACCESS_TIKI_FROM_SEPARATE_SYSTEM.TIKI_DIRECTORY_SEPARATOR.TRANSLATION_TIKI_HISTORY_ID_XML_ACTION;
        $translationSentenceList=array(WORD_TRANSLATION=>$newTranslationText);
        $sentenceHistoryId=$this->curlMultipleOptions($historyIdUrl, $translationSentenceList);
        $jsonHistoryId=json_decode($sentenceHistoryId,true);
        $jsonHistoryId[HISTORY_ID_FIELD];
        $translationWord=$databaseConnection->prepare(SELECT_ORIGINAL_ID_QUERY);
        $translationWord->bindParam(TRANSLATED_CONTENTS_BIND_PARAM, $originalContentWords);
        $translationWord->execute();
        $translationWord=$translationWord->fetch();
        $translationWord[ORIGINAL_ID_FIELD];
        $timeZone=date_default_timezone_get();
        $sentenceStatusInsertUrl=ACCESS_TIKI_FROM_SEPARATE_SYSTEM.TIKI_DIRECTORY_SEPARATOR.TRANSLATION_HISTORY_ID_ADD_XML_ACTION;
        $sentenceStatusInsertList=array(HISTORY_ID_FIELD=> $jsonHistoryId[HISTORY_ID_FIELD],ORIGINAL_WORD_ID_TITLE=>$translationWord[ORIGINAL_ID_FIELD],LANGUAGE_CODE=>$languageCode,SENTENCE_TIME_ZONE=>$timeZone);
        $this->curlMultipleOptions($sentenceStatusInsertUrl, $sentenceStatusInsertList);
    }
    /**
     * Function is revert old revision of words which is reject reviewer.
     */
    function updateRejectRevertOldWord(){
        $languageCode=$_POST[LANGUAGE_CODE];
        $originalContentWords=$_POST[ORIGINAL_CONTENT];
        $newTranslateWord=$_POST[NEW_TRANSLATION_TEXT];
        $csvFilePathUrl    =ROOT_DIRECTORY.TRANSLATION_LANGUAGE_FOLDER.$languageCode.TRANSLATION_LANGUAGE_FILE;
        $fileGetCsvContents=file_get_contents($csvFilePathUrl);
        for ($replaceSpecialSymbol = COUNT_WORDS; $replaceSpecialSymbol <= THIRD_KEY_INDEX.FIRST_LANGUAGE_ID_FLAG; ++$replaceSpecialSymbol) {
            $fileGetCsvContent = str_replace(chr($replaceSpecialSymbol), BLANK_STRING, $fileGetCsvContents);
        }
        $fileGetCsvContent = str_replace(chr(ONE_HUNDRED_TWENTY_SEVENTH_CHARACTER), BLANK_STRING, $fileGetCsvContent);
        if (COUNT_WORDS === strpos(bin2hex($fileGetCsvContent), $originalContentWords)) {
            $fileGetCsvContent = substr($fileGetCsvContent, THIRD_KEY_INDEX);
        }
        $fileGetCsvContents=preg_replace(CONTENT_MATCH_CONDITION_START.$originalContentWords.CONTENT_FIND_EXIST_TRANSLATION_VALUE, REPLACE_NEW_TRANSLATION.$newTranslateWord.ADD_REST_VALUE_FROM_FILE, $fileGetCsvContents, REGEX_LIMIT, $countWords);
        if ($countWords<=COUNT_WORDS) {
            $checkTranslationValues=(array) json_decode($fileGetCsvContent);
            if (empty($checkTranslationValues)) {
                $fileGetCsvContents=preg_replace(ADD_NEW_WORD_STARTING, WRITE_FIRST_CONDITION_VALUE.ADD_NEW_WORD_IN_FILE.$originalContentWords.ADD_COLON.$newTranslateWord.ADD_VALUE_NOT_EXISTS, $fileGetCsvContents);
            }
            else {
                $fileGetCsvContents=preg_replace(ADD_NEW_WORD_STARTING, WRITE_FIRST_CONDITION_VALUE.ADD_NEW_WORD_IN_FILE.$originalContentWords.ADD_COLON.$newTranslateWord.ADD_REST_VALUE_MATCH, $fileGetCsvContents);
            }
        }
        file_put_contents($csvFilePathUrl, $fileGetCsvContents);
    }
    /**
     * This function is for add update language translation from admin side with
     * after correction and it will be into DB, tiki side as well into csv files.
     * @param string $newTranslationText
     * @param integer $tikiPageId
     * @param string $userReference
     * @param string $languageCode
     * @param integer $ipAddress
     * @param integer $LanguegeId
     * @param string $originalContentWords
     * @return null
     * */
    function addUpdateTranslationByAdmin() {
        if(isset($_POST[TRANSLATION_ID])){
            $translationId     =$_POST[TRANSLATION_ID];
        }else {
            $translationId=null;
        }
        $languageCode      =$_POST[LANGUAGE_CODE];
        $userReference     =$_POST[USER_REFERENCE];
        $translationFlag   =$_POST[ADMIN_APPROVAL_TRANSLATION_FLAG];
        $newTranslationText=trim(mb_strtolower($_POST[NEW_TRANSLATION_TEXT], ENCODED_STRING));
        $originalContent   =trim(strtolower($_POST[ORIGINAL_CONTENT]));
        $referenceUrl  =$_POST[REFERENCE_URL];
       $this->addUpdateTranslateSentenceStage($originalContent, $newTranslationText, $languageCode, $userReference, $translationFlag, $translationId=null,$referenceUrl);
        header(CONTENT_TYPE_JSON);
        return json_encode(array(TRANSLATION_RESPONSE=>array(TRANSLATION_MESSAGE=>TRANSLATION_STATUS_MESSAGE)));
    }
    /**
     * This function is for add update language translation from user side with
     * manually and it will be save into translation DB, tikiwiki side and json
     * files
     * @return null
     * Display o/p direct to user.
     * */
    function addUpdateTranslationByUser() {
        $newTranslationText                                                                                                                         =trim(mb_strtolower($_POST[NEW_TRANSLATE_VALUE], ENCODED_STRING));
        $originalContent                                                                                                                            =trim(mb_strtolower($_POST[SENTENCE_REFERENCE], ENCODED_STRING));
        $languageCodeName                                                                                                                           =$_POST[LANGUAGE_CODE_NAME];
        $userReference =$_POST[USER_REFERENCE];
        $translationFlag                                                                                                                            =FIRST_LANGUAGE_ID_FLAG;
        $referenceUrl  =$_POST[REFERENCE_URL];
        $this->addUpdateTranslateSentenceStage($originalContent, $newTranslationText, $languageCodeName, $userReference, $translationFlag, $translationId=null,$referenceUrl);
        header(CONTENT_TYPE_JSON);
        return json_encode(TRANSLATION_STATUS_MESSAGE);
    }
    function addUpdateTranslateSentenceStage($originalContent, $newTranslationText, $languageCode, $userReference, $translationFlag, $translationId=null,$referenceUrl) {
        global $databaseConnection;
        $dateTime                                =date(DATE_TIME_PARAMETER);
        $timestamp = strtotime ($dateTime);
        $approvalDate=date('Ymd');
        $approvalTime=time();
        $ipAddress                               =$_SERVER[REMOTE_ADDRESS];
        $replaceSpecialSymbolFromOriginalSentence=preg_replace(FIND_SPECIAL_SYMBOL, BLANK_STRING, $originalContent);
        $replaceSpecialSymbolFromTranslation     =preg_replace(FIND_DOUBLE_QUOTE_SYMBOL, BLANK_STRING, $newTranslationText);
        $selectedQuery                           =$databaseConnection->prepare("SELECT or_id,original_word FROM word_translation_stage WHERE or_id=:mainLanguageReference OR original_word=:originalContents AND language_code=:languageCode");
        $selectedQuery->bindParam(':mainLanguageReference', $translationId);
        $selectedQuery->bindParam(':originalContents', $replaceSpecialSymbolFromOriginalSentence);
        $selectedQuery->bindParam(':languageCode', $languageCode);
        $selectedQuery->execute();
        $fetchSelectedValues =$selectedQuery->fetch();
        $originalLanguageId  =$fetchSelectedValues[ORIGINAL_ID];
        $existOriginalContent=$fetchSelectedValues[ORIGINAL_WORD];
        if (isset($originalLanguageId)) {
            $updateOriginalWordQuery=$databaseConnection->prepare("UPDATE word_translation_stage SET original_word=:originalWord,translation=:translatedContents, user_reference=:userReference, ip=:ipAddress,date_time=:dateTime WHERE or_id=:mainLanguageReference");
            $updateOriginalWordQuery->bindParam(':originalWord', $replaceSpecialSymbolFromOriginalSentence);
            $updateOriginalWordQuery->bindParam(':translatedContents', $replaceSpecialSymbolFromTranslation);
            $updateOriginalWordQuery->bindParam(':userReference', $userReference);
            $updateOriginalWordQuery->bindParam(':ipAddress', $ipAddress);
            $updateOriginalWordQuery->bindParam(':dateTime', $dateTime);
            $updateOriginalWordQuery->bindParam(':mainLanguageReference', $originalLanguageId);
            $updateOriginalWordQuery->execute();
            $mainLanguageReference=$originalLanguageId;

            $requestTableLive='word_translation';
            $requestTableStage='word_translation_stage';
            $requestFields='original_word';
            $approvalGrouphash=$mainLanguageReference;
            $approvalStatus='1';
            $requestLocationId=0;
            $timeZone=date_default_timezone_get();
            $translationHistoryList=$databaseConnection->prepare("SELECT translation_version from translation_approval where approval_grouphash=:originalWordId AND request_table_stage=:requestTableStage");
            $translationHistoryList->bindParam(':originalWordId', $mainLanguageReference);
            $translationHistoryList->bindParam(':requestTableStage', $requestTableStage);
            $translationHistoryList->execute();
            $translationVersionListDetails=$translationHistoryList->fetchAll(\PDO::FETCH_ASSOC);
            $translationVersionListCount=count($translationVersionListDetails);
            $translationVersion=$translationVersionListCount+1;
            $insertStageDetailsQuery=$databaseConnection->prepare("INSERT INTO translation_approval(request_date,request_time,location_id,request_table_live, request_table_stage,request_fields, approval_grouphash, translation_text, approval_status_id ,language_code,translation_version,time_zone)VALUES (:requestDate,:requestTime,:requestLocationId,:requestTableLive, :requestTableStage, :requestFields, :approvalGrouphash, :translationText, :approvalStatus, :languageCode,:translationVersion,:timeZone)");
            $insertStageDetailsQuery->bindParam(':requestDate', $approvalDate);
            $insertStageDetailsQuery->bindParam(':requestTime', $approvalTime);
            $insertStageDetailsQuery->bindParam(':requestLocationId', $requestLocationId);
            $insertStageDetailsQuery->bindParam(':requestTableLive', $requestTableLive);
            $insertStageDetailsQuery->bindParam(':requestTableStage', $requestTableStage);
            $insertStageDetailsQuery->bindParam(':requestFields', $requestFields);
            $insertStageDetailsQuery->bindParam(':approvalGrouphash', $approvalGrouphash);
            $insertStageDetailsQuery->bindParam(':translationText', $replaceSpecialSymbolFromTranslation);
            $insertStageDetailsQuery->bindParam(':approvalStatus', $approvalStatus);
            $insertStageDetailsQuery->bindParam(':languageCode', $languageCode);
            $insertStageDetailsQuery->bindParam(':translationVersion', $translationVersion);
            $insertStageDetailsQuery->bindParam(':timeZone', $timeZone);
            $insertStageDetailsQuery->execute();
            $approval_id=$databaseConnection->lastInsertId();
            $identity_table_id=60;
            $previous_time= date('Ymd');
            $previous_date=time();

            $insertTranslationReferenceQuery=$databaseConnection->prepare("INSERT INTO translation_reference(approval_id,identity_table_id,identity_id,previous_time,previous_date)VALUES (:approval_id, :identity_table_id, :identity_id, :previous_time, :previous_date)");
                $insertTranslationReferenceQuery->bindParam(':approval_id', $approval_id);
                $insertTranslationReferenceQuery->bindParam(':identity_table_id', $identity_table_id);
                $insertTranslationReferenceQuery->bindParam(':identity_id', $approval_id);
                $insertTranslationReferenceQuery->bindParam(':previous_time', $previous_time);
                $insertTranslationReferenceQuery->bindParam(':previous_date', $previous_date);
                $insertTranslationReferenceQuery->execute();
            /*$checkReferenceUrl=$databaseConnection->prepare("SELECT original_word_id from translate_reference_url where original_word_id=:originalWordId AND global_url=:referenceUrl");
            $checkReferenceUrl->bindParam(':originalWordId', $mainLanguageReference);
            $checkReferenceUrl->bindParam(':referenceUrl', $referenceUrl);
            $checkReferenceUrl->execute();
            $originalWordId=$checkReferenceUrl->fetch();
            $originalWordId= $originalWordId[ORIGINAL_WORD_ID_FIELD];
            if (!isset($originalWordId)){
                $insertGlobalUrlQuery=$databaseConnection->prepare("INSERT INTO translate_reference_url(original_word_id,global_url)VALUES (:originalWordId, :referenceUrl)");
                $insertGlobalUrlQuery->bindParam(':originalWordId', $mainLanguageReference);
                $insertGlobalUrlQuery->bindParam(':referenceUrl', $referenceUrl);
                $insertGlobalUrlQuery->execute();
            }*/
        }
        else {

            $getLanguageId=$databaseConnection->prepare("SELECT language_id from languages where language_code=:languageCode");
            $getLanguageId->bindParam(':languageCode', $languageCode);
            $getLanguageId->execute();
            $languageId=$getLanguageId->fetch();
            $languageId= $languageId[LANGUAGE_ID_FIELD];


            $wordStatus=1;
            $insertMainContentQuery=$databaseConnection->prepare("INSERT INTO word_translation_stage(original_word, translation,language_id,language_code,user_reference, ip, reference_url ,date_time,translation_status) VALUES (:originalWord, :translatedContents, :languageId, :languageCode, :userReference, :ipAddress,:referenceUrl, :dateTime, :wordStatus)");
            $insertMainContentQuery->bindParam(':originalWord', $replaceSpecialSymbolFromOriginalSentence);
            $insertMainContentQuery->bindParam(':translatedContents', $replaceSpecialSymbolFromTranslation);
            $insertMainContentQuery->bindParam(':languageId', $languageId);
            $insertMainContentQuery->bindParam(':languageCode', $languageCode);
            $insertMainContentQuery->bindParam(':userReference', $userReference);
            $insertMainContentQuery->bindParam(':ipAddress', $ipAddress);
            $insertMainContentQuery->bindParam(':dateTime', $dateTime);
            $insertMainContentQuery->bindParam(':referenceUrl', $referenceUrl);
            $insertMainContentQuery->bindParam(':wordStatus', $wordStatus);
            $insertMainContentQuery->execute();
            $mainLanguageReference=$databaseConnection->lastInsertId();

            $requestTableLive='word_translation';
            $requestTableStage='word_translation_stage';
            $requestFields='original_word';
            $approvalGrouphash=$mainLanguageReference;
            $approvalStatus='1';
            $requestLocationId=0;
            $timeZone=date_default_timezone_get();
            $translationHistoryList=$databaseConnection->prepare("SELECT translation_version from translation_approval where approval_grouphash=:originalWordId AND request_table_stage=:requestTableStage");
            $translationHistoryList->bindParam(':originalWordId', $mainLanguageReference);
            $translationHistoryList->bindParam(':requestTableStage', $requestTableStage);
            $translationHistoryList->execute();
             $translationVersionListDetails=$translationHistoryList->fetchAll(\PDO::FETCH_ASSOC);
            $translationVersionListCount=count($translationVersionListDetails);
            $translationVersion=$translationVersionListCount+1;
             $insertStageDetailsQuery=$databaseConnection->prepare("INSERT INTO translation_approval(request_date,request_time,location_id,request_table_live, request_table_stage,request_fields, approval_grouphash, translation_text, approval_status_id,language_code,translation_version,time_zone)VALUES (:requestDate,:requestTime,:requestLocationId,:requestTableLive, :requestTableStage, :requestFields, :approvalGrouphash, :translationText, :approvalStatus, :languageCode,:translationVersion,:timeZone)");
            $insertStageDetailsQuery->bindParam(':requestDate', $approvalDate);
            $insertStageDetailsQuery->bindParam(':requestTime', $approvalTime);
            $insertStageDetailsQuery->bindParam(':requestLocationId', $requestLocationId);
            $insertStageDetailsQuery->bindParam(':requestTableLive', $requestTableLive);
            $insertStageDetailsQuery->bindParam(':requestTableStage', $requestTableStage);
            $insertStageDetailsQuery->bindParam(':requestFields', $requestFields);
            $insertStageDetailsQuery->bindParam(':approvalGrouphash', $approvalGrouphash);
            $insertStageDetailsQuery->bindParam(':translationText', $replaceSpecialSymbolFromTranslation);
            $insertStageDetailsQuery->bindParam(':approvalStatus', $approvalStatus);
            $insertStageDetailsQuery->bindParam(':languageCode', $languageCode);
            $insertStageDetailsQuery->bindParam(':translationVersion', $translationVersion);
            $insertStageDetailsQuery->bindParam(':timeZone', $timeZone);
            $insertStageDetailsQuery->execute();
            $approval_id=$databaseConnection->lastInsertId();
            $identity_table_id=60;
            $previous_time= date('Ymd');
            $previous_date=time();

            $insertTranslationReferenceQuery=$databaseConnection->prepare("INSERT INTO translation_reference(approval_id,identity_table_id,identity_id,previous_time,previous_date)VALUES (:approval_id, :identity_table_id, :identity_id, :previous_time, :previous_date)");
                $insertTranslationReferenceQuery->bindParam(':approval_id', $approval_id);
                $insertTranslationReferenceQuery->bindParam(':identity_table_id', $identity_table_id);
                $insertTranslationReferenceQuery->bindParam(':identity_id', $approval_id);
                $insertTranslationReferenceQuery->bindParam(':previous_time', $previous_time);
                $insertTranslationReferenceQuery->bindParam(':previous_date', $previous_date);
                $insertTranslationReferenceQuery->execute();

            /*$checkReferenceUrl=$databaseConnection->prepare("SELECT original_word_id from translate_reference_url where original_word_id=:originalWordId AND global_url=:referenceUrl");
            $checkReferenceUrl->bindParam(':originalWordId', $mainLanguageReference);
            $checkReferenceUrl->bindParam(':referenceUrl', $referenceUrl);
            $checkReferenceUrl->execute();
            $originalWordId=$checkReferenceUrl->fetch();
            $originalWordId= $originalWordId[ORIGINAL_WORD_ID_FIELD];
            if (!isset($originalWordId)){
                $insertGlobalUrlQuery=$databaseConnection->prepare("INSERT INTO translate_reference_url(original_word_id,global_url)VALUES (:originalWordId,:referenceUrl)");
                $insertGlobalUrlQuery->bindParam(':originalWordId', $mainLanguageReference);
                $insertGlobalUrlQuery->bindParam(':referenceUrl', $referenceUrl);
                $insertGlobalUrlQuery->execute();
            }*/

        }
        $translateSelectQuery=$databaseConnection->prepare('SELECT id,translation,or_id,tiki_id FROM '.$languageCode.' WHERE or_id=:mainLanguageReference OR translation=:translatedContents');
        $translateSelectQuery->bindParam(':mainLanguageReference', $originalLanguageId);
        $translateSelectQuery->bindParam(':translatedContents', $replaceSpecialSymbolFromTranslation);
        $translateSelectQuery->execute();
        $fetchTranslateSelectedValues=$translateSelectQuery->fetch();
        $languageTranslateId         =$fetchTranslateSelectedValues[LANGUAGE_FIELD_ID];
        $existTranslationContents    =$fetchTranslateSelectedValues[TRANSLATION_FIELD_ID];
        $translatedLanguageId        =$fetchTranslateSelectedValues[ORIGINAL_ID];
        if (!$translatedLanguageId) {
            $translatedLanguageId=$originalLanguageId;
        }
        if (isset($languageTranslateId)) {
            $updateQuery=$databaseConnection->prepare("UPDATE $languageCode SET translation=:translation, user_reference=:userReference, ip=:ipAddress, date_time=:dateTime, flag=:translationFlag WHERE or_id=:mainLanguageReference");
            $updateQuery->bindParam(':translation', $replaceSpecialSymbolFromTranslation);
            $updateQuery->bindParam(':userReference', $userReference);
            $updateQuery->bindParam(':ipAddress', $ipAddress);
            $updateQuery->bindParam(':dateTime', $dateTime);
            $updateQuery->bindParam(':mainLanguageReference', $translatedLanguageId);
            $updateQuery->bindParam(':translationFlag', $translationFlag);
            $updateQuery->execute();
        }
        else {
            $insertQuery=$databaseConnection->prepare("INSERT INTO $languageCode (translation, user_reference, ip, date_time, or_id, flag) VALUES (:translation, :userReference, :ipAddress, :dateTime, :mainLanguageReference, :translationFlag)");
            $insertQuery->bindParam(':translation', $replaceSpecialSymbolFromTranslation);
            $insertQuery->bindParam(':userReference', $userReference);
            $insertQuery->bindParam(':ipAddress', $ipAddress);
            $insertQuery->bindParam(':dateTime', $dateTime);
            $insertQuery->bindParam(':mainLanguageReference', $mainLanguageReference);
            $insertQuery->bindParam(':translationFlag', $translationFlag);
            $insertQuery->execute();
            $languageTableId=$databaseConnection->lastInsertId();
        }
    }
 
    /**
     * This common function is for add update language translation from admin/user
     * side with manually and it will be save into translation DB, tikiwiki side
     * and json files
     * @param string $originalContent
     * @param string $languageCode
     * @param string $newTranslationText
     * @param string $userReference
     * @param integer $translationFlag
     * @param integer $translationId
     * @return null
     * */
    function addUpdateTranslateSentence($originalContent, $newTranslationText, $languageCode, $userReference, $translationFlag, $translationId=null,$referenceUrl) {
        global $databaseConnection;
        $dateTime                                =date(DATE_TIME_PARAMETER);
        $ipAddress                               =$_SERVER[REMOTE_ADDRESS];
        $replaceSpecialSymbolFromOriginalSentence=preg_replace(FIND_SPECIAL_SYMBOL, BLANK_STRING, $originalContent);
        $replaceSpecialSymbolFromTranslation     =preg_replace(FIND_DOUBLE_QUOTE_SYMBOL, BLANK_STRING, $newTranslationText);
        $selectedQuery                           =$databaseConnection->prepare("SELECT or_id,original_word FROM english WHERE or_id=:mainLanguageReference OR original_word=:originalContents");
        $selectedQuery->bindParam(':mainLanguageReference', $translationId);
        $selectedQuery->bindParam(':originalContents', $replaceSpecialSymbolFromOriginalSentence);
        $selectedQuery->execute();
        $fetchSelectedValues =$selectedQuery->fetch();
        $originalLanguageId  =$fetchSelectedValues[ORIGINAL_ID];
        $existOriginalContent=$fetchSelectedValues[ORIGINAL_WORD];
        if (isset($originalLanguageId)) {
            $updateOriginalWordQuery=$databaseConnection->prepare("UPDATE english SET original_word=:originalWord, user_reference=:userReference, ip=:ipAddress,date_time=:dateTime WHERE or_id=:mainLanguageReference");
            $updateOriginalWordQuery->bindParam(':originalWord', $replaceSpecialSymbolFromOriginalSentence);
            $updateOriginalWordQuery->bindParam(':userReference', $userReference);
            $updateOriginalWordQuery->bindParam(':ipAddress', $ipAddress);
            $updateOriginalWordQuery->bindParam(':dateTime', $dateTime);
            $updateOriginalWordQuery->bindParam(':mainLanguageReference', $originalLanguageId);
            $updateOriginalWordQuery->execute();
            $mainLanguageReference=$originalLanguageId;
            /*$checkReferenceUrl=$databaseConnection->prepare("SELECT original_word_id from translate_reference_url where original_word_id=:originalWordId AND global_url=:referenceUrl");
            $checkReferenceUrl->bindParam(':originalWordId', $mainLanguageReference);
            $checkReferenceUrl->bindParam(':referenceUrl', $referenceUrl);
            $checkReferenceUrl->execute();
            $originalWordId=$checkReferenceUrl->fetch();
            $originalWordId= $originalWordId[ORIGINAL_WORD_ID_FIELD];
            if (!isset($originalWordId)){
                $insertGlobalUrlQuery=$databaseConnection->prepare("INSERT INTO translate_reference_url(original_word_id,global_url)VALUES (:originalWordId, :referenceUrl)");
                $insertGlobalUrlQuery->bindParam(':originalWordId', $mainLanguageReference);
                $insertGlobalUrlQuery->bindParam(':referenceUrl', $referenceUrl);
                $insertGlobalUrlQuery->execute();
            }*/
        }
        else {
            $insertMainContentQuery=$databaseConnection->prepare("INSERT INTO english (original_word, user_reference, ip, reference_url ,date_time) VALUES (:originalWord, :userReference, :ipAddress,:referenceUrl, :dateTime)");
            $insertMainContentQuery->bindParam(':originalWord', $replaceSpecialSymbolFromOriginalSentence);
            $insertMainContentQuery->bindParam(':userReference', $userReference);
            $insertMainContentQuery->bindParam(':ipAddress', $ipAddress);
            $insertMainContentQuery->bindParam(':dateTime', $dateTime);
            $insertMainContentQuery->bindParam(':referenceUrl', $referenceUrl);
            $insertMainContentQuery->execute();
            $mainLanguageReference=$databaseConnection->lastInsertId();
            /*$checkReferenceUrl=$databaseConnection->prepare("SELECT original_word_id from translate_reference_url where original_word_id=:originalWordId AND global_url=:referenceUrl");
            $checkReferenceUrl->bindParam(':originalWordId', $mainLanguageReference);
            $checkReferenceUrl->bindParam(':referenceUrl', $referenceUrl);
            $checkReferenceUrl->execute();
            $originalWordId=$checkReferenceUrl->fetch();
            $originalWordId= $originalWordId[ORIGINAL_WORD_ID_FIELD];
            if (!isset($originalWordId)){
                $insertGlobalUrlQuery=$databaseConnection->prepare("INSERT INTO translate_reference_url(original_word_id,global_url)VALUES (:originalWordId,:referenceUrl)");
                $insertGlobalUrlQuery->bindParam(':originalWordId', $mainLanguageReference);
                $insertGlobalUrlQuery->bindParam(':referenceUrl', $referenceUrl);
                $insertGlobalUrlQuery->execute();
            }*/

        }
        $translateSelectQuery=$databaseConnection->prepare('SELECT id,translation,or_id,tiki_id FROM '.$languageCode.' WHERE or_id=:mainLanguageReference OR translation=:translatedContents');
        $translateSelectQuery->bindParam(':mainLanguageReference', $originalLanguageId);
        $translateSelectQuery->bindParam(':translatedContents', $replaceSpecialSymbolFromTranslation);
        $translateSelectQuery->execute();
        $fetchTranslateSelectedValues=$translateSelectQuery->fetch();
        $languageTranslateId         =$fetchTranslateSelectedValues[LANGUAGE_FIELD_ID];
        $existTranslationContents    =$fetchTranslateSelectedValues[TRANSLATION_FIELD_ID];
        $translatedLanguageId        =$fetchTranslateSelectedValues[ORIGINAL_ID];
        $translateTikiId             =$fetchTranslateSelectedValues[TIKI_ID];
        if (!isset($translateTikiId)) {
            $translateTikiId=null;
        }
        if (!$translatedLanguageId) {
            $translatedLanguageId=$originalLanguageId;
        }
        if (isset($languageTranslateId)) {
            $updateQuery=$databaseConnection->prepare("UPDATE $languageCode SET translation=:translation, user_reference=:userReference, ip=:ipAddress, date_time=:dateTime, flag=:translationFlag WHERE or_id=:mainLanguageReference");
            $updateQuery->bindParam(':translation', $replaceSpecialSymbolFromTranslation);
            $updateQuery->bindParam(':userReference', $userReference);
            $updateQuery->bindParam(':ipAddress', $ipAddress);
            $updateQuery->bindParam(':dateTime', $dateTime);
            $updateQuery->bindParam(':mainLanguageReference', $translatedLanguageId);
            $updateQuery->bindParam(':translationFlag', $translationFlag);
            $updateQuery->execute();
        }
        else {
            $insertQuery=$databaseConnection->prepare("INSERT INTO $languageCode (translation, user_reference, ip, date_time, or_id, flag) VALUES (:translation, :userReference, :ipAddress, :dateTime, :mainLanguageReference, :translationFlag)");
            $insertQuery->bindParam(':translation', $replaceSpecialSymbolFromTranslation);
            $insertQuery->bindParam(':userReference', $userReference);
            $insertQuery->bindParam(':ipAddress', $ipAddress);
            $insertQuery->bindParam(':dateTime', $dateTime);
            $insertQuery->bindParam(':mainLanguageReference', $mainLanguageReference);
            $insertQuery->bindParam(':translationFlag', $translationFlag);
            $insertQuery->execute();
            $languageTableId=$databaseConnection->lastInsertId();
        }
        if (isset($languageTranslateId)) {
            $languageId=$languageTranslateId;
        }
        else {
            $languageId=$languageTableId;
        }
        $this->addUpdateTranslationTiki($replaceSpecialSymbolFromTranslation, $translateTikiId, $userReference, $languageCode, $ipAddress, $languageId, $replaceSpecialSymbolFromOriginalSentence);
    }
    /**
     * Main Translator Method which wraps all other operations
     * @param string $languageCode
     * @param string $originalContentWords
     * @return string $translationResponseValues
     */
    private function addUpdateTranslateByBingApi($languageCode, $originalContentWords) {
        global $databaseConnection;
        $bingLanguageCodeSelectQuery=$databaseConnection->prepare('SELECT bing_language_code FROM languages WHERE language_code=:languageCode');
        $bingLanguageCodeSelectQuery->bindParam(':languageCode', $languageCode);
        $bingLanguageCodeSelectQuery->execute();
        $fetchBingLanguageCodeSelectQuerys=$bingLanguageCodeSelectQuery->fetch();
        $toLanguage                       =$fetchBingLanguageCodeSelectQuerys[BING_LANGUAGE_CODE];
        $bingTranslationResponse          =$this->getTokenRequestTranslationResponse($toLanguage, $originalContentWords);
        if ($bingTranslationResponse) {
            $newTranslationText=strip_tags($bingTranslationResponse);
            $lowerCaseNewTranslationText=mb_strtolower($newTranslationText, ENCODED_STRING);
            return $lowerCaseNewTranslationText;
        }
        else {
            return false;
        }
    }
    /**
     * send bing api token request and after that reference of token string get
     * translation response from bing api.
     * @param string $toLanguage
     * @param string $lowerCaseOriginalContent
     * @return string $bingTokenAccessError->getMessage();
     * @return string $bingTranslateResponse
     * @return string $bingTranslateResponseError->getMessage();
     * */
    private function getTokenRequestTranslationResponse($toLanguage, $lowerCaseOriginalContent) {
        $curlWrapperObject=new CurlWrapper();
        try {
            $bingParameters         =array(BING_GRANT_TYPE=>GRANT_TYPE, BING_TRANSLATION_SCOPE=>$this->scopeUrl, BING_CLIENT_ID=>CLIENT_ID, BING_CLIENT_SECRET_KEY=>CLIENT_SECRET);
            $bingTranslationResponse=$curlWrapperObject->curlPost($this->authenticationUrl, $bingParameters);
            $bingTranslationDetails =json_decode($bingTranslationResponse);
            $bingTranslateResponse  =$bingTranslationDetails->access_token;
        }
        catch(Exception$bingTokenAccessError) {
            return false;
        }
        try {
            $fromLanguage                =BING_DEFAULT_FROM_LANGUAGE;
            $bingTranslationParameters   =BING_LANGUAGE_TEXT.urlencode($lowerCaseOriginalContent).TO_LANGUAGE.$toLanguage.FROM_LANGUAGE.$fromLanguage;
            $bingUrl                     =BING_REQUEST_URL.$bingTranslationParameters;
            $authHeader                  =BING_AUTHENTICATION_MESSAGE.$bingTranslateResponse;
            $curlRequireParameter        =array(CURLOPT_HTTPHEADER=>array($authHeader, CONTENT_TYPE_XML), CURLOPT_RETURNTRANSFER=>true, CURLOPT_SSL_VERIFYPEER=>false);
            return $bingTranslateResponse=$curlWrapperObject->curlAddSession($bingUrl, $curlRequireParameter);
        }
        catch(Exception$bingTranslateResponseError) {
            return false;
        }
    }
    /**
     * This common function is for get translation history from tikiwiki side add
     * display its into kendo grid at user side.
     * @return null
     * Display direct o/p to user in kendo grid.
     * */
    public function translationCompareHistory() {
        global $databaseConnection;
        $requestTableStage='word_translation_stage';
        $languageCode         =$_POST[LANGUAGE_CODE_NAME];
        $originalSentenceValue=$_POST[ORIGINAL_SENTENCE_VALUE];
        $originalIdQuery =$databaseConnection->prepare('SELECT or_id,ip FROM word_translation_stage WHERE original_word=:translatedContents AND language_code=:languageCode');
        $originalIdQuery->bindParam(':translatedContents', $originalSentenceValue);
        $originalIdQuery->bindParam(LANGUAGE_CODE_BIND_PARAM, $languageCode);
        $originalIdQuery->execute();
        $fetchOriginalSelectedValues=$originalIdQuery->fetch();
        $originalId=$fetchOriginalSelectedValues[ORIGINAL_ID];
        $ipAddress=$fetchOriginalSelectedValues['ip'];
        $translateSelectQuery =$databaseConnection->prepare('SELECT * FROM translation_approval WHERE approval_grouphash=:originalId AND request_table_stage=:requestTableStage');
        $translateSelectQuery->bindParam(':originalId', $originalId);
        $translateSelectQuery->bindParam(':requestTableStage', $requestTableStage);
        $translateSelectQuery->execute();
        $fetchTranslationHistoryList=$translateSelectQuery->fetchAll(\PDO::FETCH_ASSOC);
        foreach ($fetchTranslationHistoryList as $fetchTranslationHistoryListValue) {
                    $userSelectQuery =$databaseConnection->prepare('SELECT * FROM portal_password WHERE user_id=:userId');
                    $userSelectQuery->bindParam(':userId', $fetchTranslationHistoryListValue['request_user_id']);
                    $userSelectQuery->execute();
                    $userSelectedValues=$userSelectQuery->fetch();
                    $userHistoryListValue=$userSelectedValues['username'];
                    $user_id=$userSelectedValues['user_id'];
                        if($user_id == 0){
                            $userName='Guest';
                        }
                        elseif(isset($userHistoryListValue)){
                             $userName=$userHistoryListValue;
                        }else{
                             $userName='Guest';
                        }
                    $timeString=$fetchTranslationHistoryListValue['request_time'];
                    $timezone_name = timezone_name_from_abbr('', $fetchTranslationHistoryListValue['time_zone'] * 3600, false);
                    $localTimeZoneFomat = new DateTime(date('Y-m-d H:i:s', $timeString));
                    $localTimeZoneFomat->setTimezone(new DateTimeZone($timezone_name));
                    $localDateTimeDetails['date']= $localTimeZoneFomat->format('d M Y');
                    $localDateTimeDetails['time']= $localTimeZoneFomat->format('H:i:s'); 
                    $translationHistoryValues[]=array('originalId'=>$fetchTranslationHistoryListValue['approval_grouphash'],'translationId'=>$fetchTranslationHistoryListValue['approval_id'],'tikiPageName'=>$fetchTranslationHistoryListValue['translation_text'],'tikiUserName'=>$userName,'ipAddress'=>$ipAddress,'lastModifyPage'=>date_format( new DateTime($fetchTranslationHistoryListValue['request_date']), "Y-m-d" )." ".$localDateTimeDetails['time'],'tikiPageVersion'=>$fetchTranslationHistoryListValue['translation_version']);
        }
        return json_encode($translationHistoryValues);
    }
    /**
     * This function is for compare any two particular translation revision history
     * from tiki to gui side.
     */
    public function compareTranslationTikiHistory() {
        global $databaseConnection;
        $languageCode         =$_POST[LANGUAGE_CODE_NAME];
        $originalSentenceValue=$_POST[ORIGINAL_SENTENCE_VALUE];
        $oldVersionNumber     =$_POST[OLD_VERSION_NUMBER];
        $newVersionNumber     =$_POST[NEW_VERSION_NUMBER];
        $translationId=$_POST['translationId'];
        $requestTableStage='word_translation_stage';
        if ($oldVersionNumber&&$newVersionNumber) {
                $translationVersionSelectQuery =$databaseConnection->prepare('SELECT translation_text FROM translation_approval WHERE translation_version=:translationVersion AND request_table_stage=:requestTableStage AND approval_grouphash=:approvalGrouphash');
                $translationVersionSelectQuery->bindParam(':translationVersion', $_POST[OLD_VERSION_NUMBER]);
                $translationVersionSelectQuery->bindParam(':requestTableStage', $requestTableStage);
                $translationVersionSelectQuery->bindParam(':approvalGrouphash', $translationId);
                $translationVersionSelectQuery->execute();
                $translationVersionSelectQuery=$translationVersionSelectQuery->fetch();
                $translationHistoryOldDifference=$translationVersionSelectQuery['translation_text'];
                $translationVersionSelectQuery=$databaseConnection->prepare('SELECT translation_text FROM translation_approval WHERE translation_version=:translationVersion AND request_table_stage=:requestTableStage AND approval_grouphash=:approvalGrouphash');
                $translationVersionSelectQuery->bindParam(':translationVersion', $_POST[NEW_VERSION_NUMBER]);
                $translationVersionSelectQuery->bindParam(':requestTableStage', $requestTableStage);
                $translationVersionSelectQuery->bindParam(':approvalGrouphash', $translationId);
                $translationVersionSelectQuery->execute();
                $translationVersionSelectQuery=$translationVersionSelectQuery->fetch();
                $translationHistoryNewDifference=$translationVersionSelectQuery['translation_text']; 
            $fromStart = strspn($translationHistoryOldDifference ^ $translationHistoryNewDifference, "\0");        
            $fromEnd = strspn(strrev($translationHistoryOldDifference) ^ strrev($translationHistoryNewDifference), "\0");

            $oldEnd = strlen($translationHistoryOldDifference) - $fromEnd;
            $newEnd = strlen($translationHistoryNewDifference) - $fromEnd;

            $sentenceStart = substr($translationHistoryNewDifference, 0, $fromStart);
            $sentencesEnd = substr($translationHistoryNewDifference, $newEnd);
            $newDifferences = substr($translationHistoryNewDifference, $fromStart, $newEnd - $fromStart);  
            $oldDifferences = substr($translationHistoryOldDifference, $fromStart, $oldEnd - $fromStart);

            $translationHistoryNewDifference = "$sentenceStart<span style='color:red'>$newDifferences</span>$sentencesEnd";
            $translationHistoryOldDifference = "$sentenceStart<span style='color:blue'>$oldDifferences</span>$sentencesEnd";
            $translationHistoryDifferenceValues=array('oldDataRevision'=>$translationHistoryNewDifference,'newDataRevision'=>$translationHistoryOldDifference);
            return json_encode($translationHistoryDifferenceValues);
        }
    }

    public function translationTikiPageVersion() {
        global $databaseConnection;
        $languageCode         =$_POST[LANGUAGE_CODE_NAME];
        $originalSentenceValue=$_POST[ORIGINAL_SENTENCE_VALUE];
        $translateSelectQuery =$databaseConnection->prepare('SELECT or_id FROM word_translation_stage WHERE original_word=:translatedContents');
        $translateSelectQuery->bindParam(':translatedContents', $originalSentenceValue);
        $translateSelectQuery->execute();
        $fetchTranslateSelectedValues=$translateSelectQuery->fetch();
        $translateTikiVersion=$fetchTranslateSelectedValues[ORIGINAL_ID];
        if ($translateTikiVersion) {
            return json_encode($translateTikiVersion);
        }
        else {
            return json_encode(null);
        }
    }
      /**
     * Function is get the translation status.
     */
    public function translationStatusDisplay(){
        global $databaseConnection;
        $languageCode=$_POST[LANGUAGE_CODE_NAME];
        $originalSentenceValue=$_POST[ORIGINAL_SENTENCE_VALUE];
        $translationWord=$databaseConnection->prepare('SELECT translation_status FROM word_translation_stage WHERE original_word=:translatedContents AND language_code=:languageCode');
        $translationWord->bindParam(TRANSLATED_CONTENTS_BIND_PARAM, $originalSentenceValue);
         $translationWord->bindParam(LANGUAGE_CODE_BIND_PARAM, $languageCode);
        $translationWord->execute();
        $translationWord=$translationWord->fetch();        
        $currentStatus=$translationWord[WORD_STATUS_FIELD];
        $translationWord=$databaseConnection->prepare('SELECT * FROM approval_status WHERE approval_status_id=:translatedContents');
        $translationWord->bindParam(TRANSLATED_CONTENTS_BIND_PARAM, $currentStatus);
        $translationWord->execute();
        $translationWord=$translationWord->fetch();
       echo  $translationWord['approval_status_name']."_".$translationWord['approval_status_color'];

    }
/**
 * Function is display sentence translation list.
 */
    function sentenceHistoryList(){
         global $databaseConnection;
          $languageCode=$_POST[LANGUAGE_CODE_NAME];
          $originalSentenceValue=$_POST[ORIGINAL_SENTENCE_VALUE];
          $translationWord=$databaseConnection->prepare('SELECT or_id FROM word_translation_stage WHERE original_word=:translatedContents AND language_code=:languageCode');
          $translationWord->bindParam(TRANSLATED_CONTENTS_BIND_PARAM, $originalSentenceValue);
          $translationWord->bindParam(LANGUAGE_CODE_BIND_PARAM, $languageCode);
          $translationWord->execute();
          $translationWord=$translationWord->fetch();
          $originalId=$translationWord[ORIGINAL_ID_FIELD];
          $requestTableStage='word_translation_stage';
          $translateSelectQuery =$databaseConnection->prepare('SELECT * FROM translation_approval WHERE approval_grouphash=:originalId AND request_table_stage=:requestTableStage');
        $translateSelectQuery->bindParam(':originalId', $originalId);
        $translateSelectQuery->bindParam(':requestTableStage', $requestTableStage);
        $translateSelectQuery->execute();
        $sentenceTranslationHistoryList=$translateSelectQuery->fetchAll(\PDO::FETCH_ASSOC);
        foreach ($sentenceTranslationHistoryList as $wordTranslationEntriesListValue) {
                $userSelectQuery =$databaseConnection->prepare('SELECT * FROM portal_password WHERE user_id=:userId');
                $userSelectQuery->bindParam(':userId', $wordTranslationEntriesListValue['request_user_id']);
                $userSelectQuery->execute();
                $userSelectedValues=$userSelectQuery->fetch();
                $userHistoryListValue=$userSelectedValues['username'];
                $user_id=$userSelectedValues['user_id'];
                        if($user_id == 0){
                            $userName='Guest';
                        }
                        elseif(isset($userHistoryListValue)){
                             $userName=$userHistoryListValue;
                        }else{
                             $userName='Guest';
                        }
                $translationStatusList=$databaseConnection->prepare('SELECT * FROM approval_status WHERE approval_status_id=:translatedContents');
                $translationStatusList->bindParam(TRANSLATED_CONTENTS_BIND_PARAM, $wordTranslationEntriesListValue['approval_status_id']);
                $translationStatusList->execute();
                $translationStatusList=$translationStatusList->fetch();
                $timeString=$wordTranslationEntriesListValue['request_time'];

                $timezone_name = timezone_name_from_abbr('', $wordTranslationEntriesListValue['time_zone'] * 3600, false);
                $localTimeZoneFomat = new DateTime(date('Y-m-d H:i:s', $timeString));
                $localTimeZoneFomat->setTimezone(new DateTimeZone($timezone_name));
                $localDateTimeDetails['date']= $localTimeZoneFomat->format('d M Y');
                $localDateTimeDetails['time']= $localTimeZoneFomat->format('H:i:s'); 
                $wordTranslationListData[]=array("colorCode"=>$translationStatusList['approval_status_color'],"sentenceTimeZone"=>$wordTranslationEntriesListValue['time_zone'],"sentenceDateTime"=>date_format( new DateTime($wordTranslationEntriesListValue['request_date']), "Y-m-d" )." ".$localDateTimeDetails['time'],'userReference'=>$userName,'historyId'=>$wordTranslationEntriesListValue['approval_id'],'newTranslateValue'=>$wordTranslationEntriesListValue['translation_text'],'wordTranslationStatus'=>$translationStatusList['approval_status_name']);
        }
        return json_encode($wordTranslationListData);
    }
    /**
     * This function is for get information using curl api with different purpose where need its.
     * and return its relavent output.
     * @param string $tikiUrl
     * @param string $curlPostFields
     * @return string
     * Display direct o/p to user in kendo grid.
     * */
    private function curlMultipleOptions($tikiUrl, $curlPostFields) {
        $curlWrapperObject=new CurlWrapper();
        $setCurlOptions=array(CURLOPT_POST=>true, CURLOPT_POSTFIELDS=>$curlPostFields, CURLOPT_RETURNTRANSFER=>true, CURLOPT_CONNECTTIMEOUT=>COUNT_WORDS, CURLOPT_TIMEOUT=>COUNT_WORDS);
        return $curlWrapperObject->curlAddSession($tikiUrl, $setCurlOptions);
    }
}
$updateLanguageTranslation=new AddUpdateTranslation;
echo $updateLanguageTranslation->{$_REQUEST[CALL_FUNCTION_KEY]}();
?>
