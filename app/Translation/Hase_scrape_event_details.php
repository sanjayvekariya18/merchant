<?php
const CALL_FUNCTION_KEY='callFunction';
const ERROR_MESSAGE='ERROR: ';
$directoryRoot=dirname(dirname(__DIR__));
define('CURL_PATH', '/Http/Controllers/cURL/CurlWrapper.php');
use curl\ CurlWrapper;
require_once(dirname(__DIR__).CURL_PATH);
class ScrapeEventDetailsJson {
	private static $databaseConnection;
    private static $databaseName = 'hase_chatbot';
    private static $databaseHost = 'localhost';
    private static $databaseUsername = 'root';
    private static $databasePassword = '123456';

    function __construct() {
        try {
            ScrapeEventDetailsJson::$databaseConnection = new PDO('mysql:host=' . self::$databaseHost . ';dbname=' . self::$databaseName . ';charset=utf8', self::$databaseUsername, self::$databasePassword);
            ScrapeEventDetailsJson::$databaseConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException$pdoException) {
            echo ERROR_MESSAGE . $pdoException->getMessage();
        }
    }
    function scrapeEventDetailsListJson(){
    	    $requestTableLive='hase_event_url_list';
            $requestTableStage='hase_event_url_list_live';
            $requestFields='event_url';
            $approvalGrouphash=$_POST['eventNodeId'];
            $approvalStatus='1';
            $requestLocationId=0;
            $approvalDate=$_POST['approvalDate'];
            $approvalTime=$_POST['approvalTime'];
            $translationText=$_POST['translationText'];
            $languageCode=$_POST['languageCode'];
            $timeZone=$_POST['timeZone'];
            $translationHistoryList=ScrapeEventDetailsJson::$databaseConnection->prepare("SELECT translation_version from hase_translation_approval where approval_grouphash=:originalWordId AND request_table_stage=:requestTableStage");
            $translationHistoryList->bindParam(':originalWordId', $approvalGrouphash);
            $translationHistoryList->bindParam(':requestTableStage', $requestTableStage);
            $translationHistoryList->execute();
            $translationVersionListDetails=$translationHistoryList->fetchAll(\PDO::FETCH_ASSOC);
            $translationVersionListCount=count($translationVersionListDetails);
            $translationVersion=$translationVersionListCount+1;
    	 	$insertScrapeEventDetails=ScrapeEventDetailsJson::$databaseConnection->prepare("INSERT INTO hase_translation_approval(request_date,request_time,request_location_id,request_table_live, request_table_stage,request_fields, approval_grouphash, translation_text, approval_status ,language_code,translation_version,time_zone)VALUES (:requestDate,:requestTime,:requestLocationId,:requestTableLive, :requestTableStage, :requestFields, :approvalGrouphash, :translationText, :approvalStatus, :languageCode,:translationVersion,:timeZone)");
            $insertScrapeEventDetails->bindParam(':requestDate', $approvalDate);
            $insertScrapeEventDetails->bindParam(':requestTime', $approvalTime);
            $insertScrapeEventDetails->bindParam(':requestLocationId', $requestLocationId);
            $insertScrapeEventDetails->bindParam(':requestTableLive', $requestTableLive);
            $insertScrapeEventDetails->bindParam(':requestTableStage', $requestTableStage);
            $insertScrapeEventDetails->bindParam(':requestFields', $requestFields);
            $insertScrapeEventDetails->bindParam(':approvalGrouphash', $approvalGrouphash);
            $insertScrapeEventDetails->bindParam(':translationText', $translationText);
            $insertScrapeEventDetails->bindParam(':approvalStatus', $approvalStatus);
            $insertScrapeEventDetails->bindParam(':languageCode', $languageCode);
            $insertScrapeEventDetails->bindParam(':translationVersion', $translationVersion);
            $insertScrapeEventDetails->bindParam(':timeZone', $timeZone);
            $insertScrapeEventDetails->execute();
    }
}
$insertScrapeEventDetails=new ScrapeEventDetailsJson;
echo $insertScrapeEventDetails->{$_REQUEST[CALL_FUNCTION_KEY]}();
?>