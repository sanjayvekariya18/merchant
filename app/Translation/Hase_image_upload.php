<?php
/**
 * File is upload the image detail in Json file.
 */
const CALL_FUNCTION_KEY='callFunction';
const IMAGE_DETAIL_FILE_NAME="/jsonUploads/";
const FILE_CONTENTS_TRUE_VALUE='1';
const SLASH_SIGN='/';
const IMAGE_NAME='imageName';
const USER_ADMINISTRATOR="Administrator";
const GENERAL_USER_MESSAGE="General Users";
const USER_CATEGORY_MESSAGE='userCategory';
const IMAGE_LOCATION='imageLocation';
const REPLACE_PLUS="+";
const IMAGE_LOCATION_LONGITUDE_LATITUDE_URL="http://maps.google.com/maps/api/geocode/json?address=";
const LONGITUDE_LATITUDE_URL_SENSOR_VALUE="&sensor=false";
const IMAGE_LONGITUDE='imageLongitude';
const IMAGE_LATITUDE='imageLatitude';
const LONGITUDE_LATITUDE_RESULTS='results';
const LONGITUDE_LATITUDE_GEOMETRY='geometry';
const LONGITUDE_LATITUDE_LOCATION='location';
const LATITUDE_FIELD_VALUE='lat';
const LONGITUDE_FIELD_VALUE='lng';
const IMAGE_ELEVATION_URL="https://maps.googleapis.com/maps/api/elevation/json?locations=";
const ELEVATION_FIELD_VALUE='elevation';
const IMAGE_ELEVATION='imageElevation';
const IMAGE_DATA='imageData';
const SINGLE_SEPARATORS='';
const INTEGER_ZERO=0;
const COMMA_SEPARATORS=",";
const IMAGE_ID_VALUE='imageId';
const ERROR_MESSAGE='ERROR: ';
const IMAGE_ID_FIELD='id';
const IMAGE_URL_BIND_PARAM=':imageUrl';
const USER_ID_BIND_PARAM=':userId';
const IMAGE_URL_SELECT_QUERY='SELECT id FROM image_translation_stage WHERE image_url=:imageUrl';
const IMAGE_URL_INSERT_QUERY="INSERT INTO image_translation_stage(image_url,user_id,photo_album_id,date_time,translation_status)VALUES (:imageUrl, :userId,:photoAlbumId,:dateTime,:imageStatus)";
const USER_ID='userId';
const IMAGE_UPLOAD_CALL_VALUE="storeImageDetails";
const ALBUM_NAME='albumName';
const PHOTO_ALBUM_ID_FIELD='photo_album_id';
const ALBUM_NAME_BIND_PARAM=':photoAlbumName';
const ALBUM_NAME_SELECT_QUERY='SELECT * FROM photo_album WHERE photo_album_name LIKE :photoAlbumName';
const ALBUM_NAME_INSERT_QUERY="INSERT INTO photo_album(photo_album_name,user_id)VALUES (:photoAlbumName, :userId)";
const PHOTO_ALBUM_ID='photoAlbumId';
const PHOTO_ALBUM_ID_BIND_PARAM=':photoAlbumId';
const ALBUM_NAME_LIST='albumNameList';
const PHOTO_ALBUM_NAME_FIELD='photo_album_name';
const PHP_INPUT_VALUE='php://input';
const IMAGE_DETAIL_FILE_EXTENTION=".json";
const ALBUM_BLANK_SPACE=' ';
const UNDERSCORE_SEPARATOR="_";
const LOW_RANDOM_RANGE=1111111111;
const HIGH_RANDOM_RANGE=9999999999;
const USER_NAME_FIELD='username';
const USER_NAME_SELECT_QUERY='SELECT username FROM portal_password WHERE user_id=:userId';
const PHOTO_ALBUM_NAME='photoAlbumName';
const LIKE_QUERY_ALBUM_NAME="%";
const DATE_TIME_PARAMETER='Y-m-d H:i:s';
const DATE_TIME_BIND_PARAM=':dateTime';
const DEFAULT_TIME_ZONE='UTC';
const IMAGE_UPLOAD_URL="\public\images\Translation";
const IMAGE_SAMPLE_NUMBER=10;
const RED_COLOR_VALUE=16;
const GREEN_COLOR_VALUE=8;
const IMAGE_BRIGHTNESS_MAXIMUM_VALUE=100;
const IMAGE_BRIGHTNESS_RANGE=150;
const IMAGE_BRIGHTNESS_DIVISION_VALUE=6;
const IMAGE_CAPTCHA_UPADTE_QUERY="UPDATE image_translation_stage SET image_text=:imageCaptcha WHERE id=:imageId";
const IMAGE_ID_BIND_PARAM=':imageId';
const IMAGE_CAPTCHA_BIND_PARAM=':imageCaptcha';
const IMAGE_CAPTCHA_SELECT_QUERY='SELECT id FROM image_translation_stage WHERE image_text=:imageCaptcha';
const IMAGE_TEMPORARY_NAME='temp.';
const IMAGE_BYPASS_CAPTCHA_VALUE=2;
const IMAGE_CAPTCHA_STATUS_BIND_PARAM=':imageCaptchaStatus';
const IMAGE_CAPTCHA_STATUS_UPDATE_QUERY="UPDATE image_translation_stage SET captcha_status=:imageCaptchaStatus,image_attempt=:imageAttemptValue WHERE id=:imageId";
const IMAGE_CAPTCHA_STATUS='imageCaptchaStatus';
const IMAGE_CAPTCHA_STATUS_VALUE=2;
const IMAGE_CAPTCHA_VALUE='imageCaptchaValue';
const TIKI_PAGE_ACTION="create_update_page_tiki";
const TIKI_XML_ACTION='BaseTikiApi.php?action=';
const IMAGE_TRANSLATION_DATE_TIME_FORMAT='Y/m/d h:i A';
const TIKI_URL="/tiki/";
const TIKI_PAGE_NAME_FIELD='pageName';
const TIKI_PAGE_DATA_FIELD='data';
const TIKI_LAST_MODIFICATION_FIELD='lastModif';
const TIKI_USER_FIELD='user';
const SERVER_NAME='SERVER_NAME';
const IMAGE_ATTEMPT_VALUE="imageAttempt";
const IMAGE_ATTEMPT_STATUS_BIND_PARAM=':imageAttemptValue';
const IMAGE_OLD_VALUE='imageOldValue';
const IMAGE_NEW_VALUE='imageNewValue';
const CAPTCHA_BOT_VALUE="captcha bot";
const JPG_IMAGE_FILE_TYPE='jpg';
const JPEG_IMAGE_FILE_TYPE='jpeg';
const PNG_IMAGE_FILE_TYPE='png';
const GIF_IMAGE_FILE_TYPE='gif';
const OPENSSL_CIPHERNAME_KEY='AES-128-CBC';
const CAPTCHA_FALSE_VALUE=-1;
const RB_FILE_EXTENSION="rb";
const LINE_SEPARATOR="\n";
const FORTY_STRING_LENGTH=40;
const THIRTY_TWO_STRING_LENGHT=32;
const BYPASS_CAPTCHA_URL="http://bypasscaptcha.com/upload.php";
const CAPTCH_KEY_VALUE="key";
const CAPTCHA_SUBMIT_VALUE="Submit";
const CAPTCHA_FILE_VALUE="file";
const CAPTCHA_VALUE="Value";
const CAPTCHA_TASK_ID_VALUE="TaskId";
const BASE64_CODE_VALUE="base64_code";
const CAPTCHA_SUBMIT_KEY="submit";
const CAPTCHA_GENERAL_TASK_ID="gen_task_id";
const IMAGE_ACTIVITY_VALUE='imageActivityValue';
const IMAGE_REGION_VALUE='imageRegionValue';
const IMAGE_STATUS_VALUE='imageStatusValue';
const JSON_POST_FUNCTION_CALL_VALUE="imageJsonPostQuery";
const JSON_POST_SELECT_QUERY='SELECT * FROM image_translation_stage WHERE user_id=:userId AND captcha_status=:imageStatus';
const IMAGE_STATUS_BIND_PARAM=':imageStatus';

$uploadedImageDetails=file_get_contents(PHP_INPUT_VALUE);
$uploadedImageData = json_decode($uploadedImageDetails,true);
$imageUpload = new ImageUploadJson;
if(isset($uploadedImageData[IMAGE_STATUS_VALUE])){
    call_user_func_array(array($imageUpload, JSON_POST_FUNCTION_CALL_VALUE), array($uploadedImageData[IMAGE_STATUS_VALUE],$uploadedImageData[USER_ID]));
}
if(isset($uploadedImageData[IMAGE_DATA])){
    call_user_func_array(array($imageUpload, IMAGE_UPLOAD_CALL_VALUE), array($uploadedImageData[IMAGE_DATA],$uploadedImageData[IMAGE_LOCATION],$uploadedImageData[USER_ID],$uploadedImageData[ALBUM_NAME]));
}
$directoryRoot=dirname(dirname(__DIR__));
define('CURL_PATH', '/Http/Controllers/cURL/CurlWrapper.php');
use curl\ CurlWrapper;
require_once(dirname(__DIR__).CURL_PATH);
class ImageUploadJson {
    private static $databaseConnection;
    private static $databaseName = 'dev_v400';
    private static $databaseHost = 'localhost';
    private static $databaseUsername = 'root';
    private static $databasePassword = '123456abcde';
    private static $captchaKey = '3ab92b43668c5da746cf69eca8863323';

    function __construct() {
        try {
            ImageUploadJson::$databaseConnection = new PDO('mysql:host=' . self::$databaseHost . ';dbname=' . self::$databaseName . ';charset=utf8', self::$databaseUsername, self::$databasePassword);
            ImageUploadJson::$databaseConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException$pdoException) {
            echo ERROR_MESSAGE . $pdoException->getMessage();
        }
    }
    function imageJsonPostQuery($imageStatus=null,$userId=null){
        if(isset($_POST[IMAGE_STATUS_VALUE]))
        {
            $imageStatusValue=$_POST[IMAGE_STATUS_VALUE];
            $userId=$_POST[USER_ID];
        }else{
            $imageStatusValue=$imageStatus;
            $userId=$userId;
        }
        $imageJsonPostQuery=ImageUploadJson::$databaseConnection->prepare(JSON_POST_SELECT_QUERY);
        $imageJsonPostQuery->bindParam(IMAGE_STATUS_BIND_PARAM, $imageStatusValue);
        $imageJsonPostQuery->bindParam(USER_ID_BIND_PARAM, $userId);
        $imageJsonPostQuery->execute();
        $imageJsonQuery=$imageJsonPostQuery->fetchAll(\PDO::FETCH_ASSOC);
        if(isset($_POST[IMAGE_STATUS_VALUE]))
        {
            return json_encode($imageJsonQuery);
        }else {
            echo json_encode($imageJsonQuery);
        }
    }
    /**
     * Function is insert the image detail in json file.
     * @param string $imageUrl
     * @param string $imageLocation
     * @param integer $userId
     * @param string $albumName
     */
    function storeImageDetails($imageUrl=null,$imageLocation=null,$userId=null,$albumName=null) {
        if(isset($_POST[IMAGE_DATA])){
            $urlImages=$_POST[IMAGE_DATA];
            $imageLocation=$_POST[IMAGE_LOCATION];
            $userId=$_POST[USER_ID];
            $albumName=$_POST[ALBUM_NAME];
        }else
        {
            $urlImages=$imageUrl;
            $imageLocation=$imageLocation;
            $userId=$userId;
            $albumName=$albumName;
        }
        date_default_timezone_set(DEFAULT_TIME_ZONE);
        $userNameString=ImageUploadJson::queryDBDisplayUserName($userId);
        $userName=str_replace(ALBUM_BLANK_SPACE, SINGLE_SEPARATORS,$userNameString);
        $photoAlbumDetail=ImageUploadJson::insertDBPhotoAlbumName($albumName,$userId,$userName);
        $albumJsonName=str_replace(ALBUM_BLANK_SPACE, SINGLE_SEPARATORS, $photoAlbumDetail[PHOTO_ALBUM_NAME]);
        $directoryRoot=dirname(dirname(__DIR__));
        if (!file_exists(dirname(dirname(__DIR__)).IMAGE_DETAIL_FILE_NAME)) {
            mkdir(dirname(dirname(__DIR__)).IMAGE_DETAIL_FILE_NAME, 0777, true);
        }
        if(isset($photoAlbumDetail[PHOTO_ALBUM_NAME])){
            $fileNameCheck=file_exists(dirname(dirname(__DIR__)).IMAGE_DETAIL_FILE_NAME.$albumJsonName.IMAGE_DETAIL_FILE_EXTENTION);
            if($fileNameCheck == SINGLE_SEPARATORS){
                dirname(dirname(__DIR__)).IMAGE_DETAIL_FILE_NAME.$albumJsonName.IMAGE_DETAIL_FILE_EXTENTION;
                file_put_contents(dirname(dirname(__DIR__)).IMAGE_DETAIL_FILE_NAME.$albumJsonName.IMAGE_DETAIL_FILE_EXTENTION,SINGLE_SEPARATORS);
            }
        }
        $imageId=ImageUploadJson::insertDBImageTranslationDetail($urlImages,$photoAlbumDetail[PHOTO_ALBUM_ID],$userId);
        if(isset($imageId)){
            if(stripos($urlImages, self::$databaseHost) !== FALSE){
                $liveUrl=true;
            }else {
                $liveUrl=false;
            }
            $imageDataItem = array();
            $imageLocationData=ImageUploadJson::getLongitudeLatitude($imageLocation);
            $imageUrlString=explode(SLASH_SIGN,$urlImages);
            $imageName=end($imageUrlString);
            $directoryRoot;
            $imageUrl =$urlImages;
            if($liveUrl != SINGLE_SEPARATORS){
                $imageContent = file_get_contents($urlImages);
                file_put_contents($directoryRoot.IMAGE_UPLOAD_URL.SLASH_SIGN.$_POST[IMAGE_DATA], $imageContent);
            }
            $luminanceValue = ImageUploadJson::getAverageLuminance($imageUrl,IMAGE_SAMPLE_NUMBER);
            if ($luminanceValue < IMAGE_BRIGHTNESS_MAXIMUM_VALUE) {
                $captchImageType = pathinfo($imageUrl, PATHINFO_EXTENSION);
                if($captchImageType == JPG_IMAGE_FILE_TYPE ||  $captchImageType == JPEG_IMAGE_FILE_TYPE){
                    $imageChange = imagecreatefromjpeg($imageUrl);
                }else if ($captchImageType == PNG_IMAGE_FILE_TYPE){
                    $imageChange = imagecreatefrompng($imageUrl);
                }else if ($captchImageType == GIF_IMAGE_FILE_TYPE){
                    $imageChange = imagecreatefromgif($imageUrl);
                }
                if($imageChange && imagefilter($imageChange, IMG_FILTER_BRIGHTNESS, IMAGE_BRIGHTNESS_RANGE))
                {
                    imagejpeg($imageChange, $directoryRoot.IMAGE_UPLOAD_URL.SLASH_SIGN.IMAGE_TEMPORARY_NAME.$captchImageType);
                    $imageCaptchaUrl=$directoryRoot.IMAGE_UPLOAD_URL.SLASH_SIGN.IMAGE_TEMPORARY_NAME.$captchImageType;
                }
            }
            else{
                $imageCaptchaUrl=$imageUrl;
            }
            $imageCaptchaValue = ImageUploadJson::bypassCaptchaSubmitCaptcha(self::$captchaKey,$imageCaptchaUrl);

            if (is_numeric($imageCaptchaValue))
            {
               $imageCaptcha=$imageCaptchaValue;
               ImageUploadJson::updateDBImageCaptcha($imageId,$imageCaptcha,$userName);
               $imageAttemptValue=FILE_CONTENTS_TRUE_VALUE;
               ImageUploadJson::updateDBImageCaptchaValue($imageId,IMAGE_CAPTCHA_STATUS_VALUE,$imageAttemptValue);
               $imageCaptchaValue=IMAGE_CAPTCHA_STATUS_VALUE;
               $imageNewValue=$imageCaptcha;
            }else {
                $imageAttemptValue=INTEGER_ZERO;
                $imageCaptcha=SINGLE_SEPARATORS;
                $imageCaptchaValue=INTEGER_ZERO;
                $imageNewValue=SINGLE_SEPARATORS;
            }
            $imageType = pathinfo($urlImages, PATHINFO_EXTENSION);
             if($liveUrl == SINGLE_SEPARATORS){
                $base64ImageUrl =$urlImages;
            }else if(isset($imageType)){
                $base64ImageUrl =base64_encode(openssl_encrypt($urlImages,OPENSSL_CIPHERNAME_KEY,INTEGER_ZERO, OPENSSL_RAW_DATA,SINGLE_SEPARATORS));
            }else{
                $base64ImageUrl=$urlImages;
            }
            if($userId == FILE_CONTENTS_TRUE_VALUE){
                $userCategory=USER_ADMINISTRATOR;
            }else{
                $userCategory=GENERAL_USER_MESSAGE;
            }
            
            $imageDataItem=array(IMAGE_REGION_VALUE=>SINGLE_SEPARATORS,IMAGE_ACTIVITY_VALUE=>SINGLE_SEPARATORS,IMAGE_NEW_VALUE=>$imageNewValue,IMAGE_OLD_VALUE=>SINGLE_SEPARATORS,IMAGE_ATTEMPT_VALUE=>$imageAttemptValue,IMAGE_CAPTCHA_STATUS=>$imageCaptchaValue,IMAGE_CAPTCHA_VALUE=>$imageCaptcha,PHOTO_ALBUM_ID=>$photoAlbumDetail[PHOTO_ALBUM_ID],IMAGE_ID_VALUE=>$imageId,USER_CATEGORY_MESSAGE=>$userCategory,USER_ID=>$userId,IMAGE_ELEVATION=>$imageLocationData[IMAGE_ELEVATION],IMAGE_LONGITUDE=>$imageLocationData[IMAGE_LONGITUDE],IMAGE_LATITUDE=>$imageLocationData[IMAGE_LATITUDE],IMAGE_DATA=>$base64ImageUrl,IMAGE_NAME=>$imageName);
            $imageJsonFile = dirname(dirname(__DIR__)).IMAGE_DETAIL_FILE_NAME.$albumJsonName.IMAGE_DETAIL_FILE_EXTENTION;
            $imageData= json_decode(file_get_contents($imageJsonFile),FILE_CONTENTS_TRUE_VALUE);
            if (isset($imageData)){
                array_push( $imageData,$imageDataItem);
                $imageJsonDetail = json_encode($imageData);
                file_put_contents($imageJsonFile, $imageJsonDetail);
            }else{
                $imageDataDetails[]=$imageDataItem;
                $imageJsonDetail = json_encode($imageDataDetails);
                file_put_contents($imageJsonFile, $imageJsonDetail);
            }
            $imageDataDetails[]=$imageDataItem;
            return json_encode(array('imageTranslatedValue'=>$imageDataDetails));
        }
    }
    /**
     * Function is update the image captcha value.
     * @param integer $imageId.
     * @param integer $imageCaptcha.
     * @param string $userName.
     */
    function updateDBImageCaptcha($imageId,$imageCaptcha,$userName){
            $curlWrapper=new CurlWrapper();
            $imageCaptchaHistoryData=array(TIKI_PAGE_NAME_FIELD=>$imageId,TIKI_PAGE_DATA_FIELD=>$imageCaptcha,TIKI_LAST_MODIFICATION_FIELD=>date(IMAGE_TRANSLATION_DATE_TIME_FORMAT),TIKI_USER_FIELD=>CAPTCHA_BOT_VALUE);
            $curlWrapper->curlPost($_SERVER[SERVER_NAME].TIKI_URL.TIKI_XML_ACTION.TIKI_PAGE_ACTION,$imageCaptchaHistoryData);
            $updateImageCaptcha=ImageUploadJson::$databaseConnection->prepare(IMAGE_CAPTCHA_UPADTE_QUERY);
            $updateImageCaptcha->bindParam(IMAGE_ID_BIND_PARAM, $imageId);
            $updateImageCaptcha->bindParam(IMAGE_CAPTCHA_BIND_PARAM, $imageCaptcha);
            $updateImageCaptcha->execute();
    }
    /**
     * Function is update the image captcha status value.
     * @param integer $imageId.
     * @param integer $imageCaptchaValue.
     * @param integer $imageAttemptValue.
     */
    function updateDBImageCaptchaValue($imageId,$imageCaptchaValue,$imageAttemptValue){
        $updateImageCaptchaValue=ImageUploadJson::$databaseConnection->prepare(IMAGE_CAPTCHA_STATUS_UPDATE_QUERY);
        $updateImageCaptchaValue->bindParam(IMAGE_ID_BIND_PARAM, $imageId);
        $updateImageCaptchaValue->bindParam(IMAGE_CAPTCHA_STATUS_BIND_PARAM, $imageCaptchaValue);
        $updateImageCaptchaValue->bindParam(IMAGE_ATTEMPT_STATUS_BIND_PARAM, $imageAttemptValue);
        $updateImageCaptchaValue->execute();
    }
    /**
     * Function is get the image resolution value.
     * @param string $imageUrl.
     * @param interger $samplesNumber.
     */
    function getAverageLuminance($imageUrl, $samplesNumber=IMAGE_SAMPLE_NUMBER) {
        $fileName = imagecreatefromjpeg($imageUrl);
        $imageWidth = imagesx($fileName);
        $imageHeight = imagesy($fileName);
        $imageXStep = intval($imageWidth/$samplesNumber);
        $imageYStep = intval($imageHeight/$samplesNumber);
        $totalLuminance = INTEGER_ZERO;
        $sampleNumber = FILE_CONTENTS_TRUE_VALUE;
        for ($imageXAxis=INTEGER_ZERO; $imageXAxis<$imageWidth; $imageXAxis+=$imageXStep) {
            for ($imageYAxis=INTEGER_ZERO; $imageYAxis<$imageHeight; $imageYAxis+=$imageYStep) {
                $imagesRedGreenBlue = imagecolorat($fileName, $imageXAxis, $imageYAxis);
                $imageRedValue = ($imagesRedGreenBlue >> RED_COLOR_VALUE) & 0xFF;
                $imageGreenValue = ($imagesRedGreenBlue >> GREEN_COLOR_VALUE) & 0xFF;
                $imageBlueValue = $imagesRedGreenBlue & 0xFF;
                $imageLuminance = ($imageRedValue+$imageRedValue+$imageBlueValue+$imageGreenValue+$imageGreenValue+$imageGreenValue)/IMAGE_BRIGHTNESS_DIVISION_VALUE;
                $totalLuminance += $imageLuminance;
                $sampleNumber++;
            }
        }
        return $averageLuminance  = $totalLuminance/$sampleNumber;
    }
    /**
     * Function is get the longitude/latitude/elevation of location.
     * @param string $imageLocation
     */
    static function getLongitudeLatitude($imageLocation){
       $address =$imageLocation; // Google HQ
          $prepAddr = str_replace(' ','+',$address);
          $geocode=file_get_contents('http://maps.google.com/maps/api/geocode/json?address='.$prepAddr.'&sensor=false');
          $output= json_decode($geocode);
          if(isset($output->results[0])){
            $imageLatitude = $output->results[0]->geometry->location->lat;
            $imageLongitude = $output->results[0]->geometry->location->lng;
            $imageElevationData=file_get_contents(IMAGE_ELEVATION_URL.$imageLatitude.",".$imageLongitude);
            $imageElevationJson = json_decode($imageElevationData);
            $imageElevation=$imageElevationJson->{LONGITUDE_LATITUDE_RESULTS}[0]->{ELEVATION_FIELD_VALUE};
          }else{
            $imageLatitude = '';
            $imageLongitude = '';
            $imageElevation='';
          }
        return array(IMAGE_LONGITUDE=>$imageLongitude,IMAGE_LATITUDE=>$imageLatitude,IMAGE_ELEVATION=>$imageElevation);
    }
    /**
     * Function is insert uploaded image details.
     * @param string $imageUrl.
     * @param integer $photoAlbumId
     * @param integer $userId.
     */
    static function insertDBImageTranslationDetail($imageUrl,$photoAlbumId,$userId){
        $imageUrlList=ImageUploadJson::$databaseConnection->prepare(IMAGE_URL_SELECT_QUERY);
        $imageUrlList->bindParam(IMAGE_URL_BIND_PARAM, $imageUrl);
        $imageUrlList->execute();
        $imageId=$imageUrlList->fetch();
        $imageUrlId=$imageId[IMAGE_ID_FIELD];
        if(!isset($imageUrlId)){
            $imageStatusValue=1;
            $imageTime = time();
            $insertImageUrl=ImageUploadJson::$databaseConnection->prepare(IMAGE_URL_INSERT_QUERY);
            $insertImageUrl->bindParam(IMAGE_URL_BIND_PARAM, $imageUrl);
            $insertImageUrl->bindParam(USER_ID_BIND_PARAM, $userId);
            $insertImageUrl->bindParam(PHOTO_ALBUM_ID_BIND_PARAM, $photoAlbumId);
            @$insertImageUrl->bindParam(DATE_TIME_BIND_PARAM,$imageTime);
            $insertImageUrl->bindParam(IMAGE_STATUS_BIND_PARAM, $imageStatusValue);
            $insertImageUrl->execute();
            return ImageUploadJson::$databaseConnection->lastInsertId();
        }
    }
    /**
     * Function is get the user name.
     * @param integer $userId.
     */
    static function queryDBDisplayUserName($userId){
        $userNameList=ImageUploadJson::$databaseConnection->prepare(USER_NAME_SELECT_QUERY);
        $userNameList->bindParam(USER_ID_BIND_PARAM, $userId);
        $userNameList->execute();
        $userNameDetail=$userNameList->fetch();
        return $userName=$userNameDetail[USER_NAME_FIELD];
    }
    /**
     * Function is insert the album name.
     * @param string $albumName.
     * @param integer $userId.
     * @param string $userName.
     */
    static function insertDBPhotoAlbumName($albumName,$userId,$userName){
        $usersAlbumString = explode(UNDERSCORE_SEPARATOR, $albumName);
        $usersAlbumString[INTEGER_ZERO];
        if($usersAlbumString[INTEGER_ZERO] == $userName){
            $albumNameString=LIKE_QUERY_ALBUM_NAME.$albumName.LIKE_QUERY_ALBUM_NAME;

        }else {
            $albumNameString = LIKE_QUERY_ALBUM_NAME.$userName.UNDERSCORE_SEPARATOR.$albumName.LIKE_QUERY_ALBUM_NAME;
        }
        $albumNameList=ImageUploadJson::$databaseConnection->prepare(ALBUM_NAME_SELECT_QUERY);
        $albumNameList->bindParam(ALBUM_NAME_BIND_PARAM, $albumNameString);
        $albumNameList->execute();
        $albumNameDetail=$albumNameList->fetch();
        $photoAlbumId=$albumNameDetail[PHOTO_ALBUM_ID_FIELD];
        $photoAlbumsName=$albumNameDetail[PHOTO_ALBUM_NAME_FIELD];
        if(!isset($photoAlbumId)){
         $randomNumber=(rand(1111111111,100));
          $albumNameRandomly=$userName.UNDERSCORE_SEPARATOR.$albumName.UNDERSCORE_SEPARATOR.$randomNumber;
            $insertPhotoAlbumName=ImageUploadJson::$databaseConnection->prepare(ALBUM_NAME_INSERT_QUERY);
            $insertPhotoAlbumName->bindParam(ALBUM_NAME_BIND_PARAM, $albumNameRandomly);
            $insertPhotoAlbumName->bindParam(USER_ID_BIND_PARAM, $userId);
            $insertPhotoAlbumName->execute();
            return array(PHOTO_ALBUM_ID=>ImageUploadJson::$databaseConnection->lastInsertId(),PHOTO_ALBUM_NAME=>$albumNameRandomly);
        }else{
            return array(PHOTO_ALBUM_ID=>$albumNameDetail[PHOTO_ALBUM_ID_FIELD],PHOTO_ALBUM_NAME=>$photoAlbumsName);
        }

    }
    /**
     * Function is submit the bypasscaptcha vale.
     * @param integer $captchaKey
     * @param string $imageUrl
     */
    function bypassCaptchaSubmitCaptcha($captchaKey, $imageUrl)
    {
        global $bypassCaptchaTaskId;
        $bypassCaptchaTaskId = CAPTCHA_FALSE_VALUE;
        $fileOpen = fopen($imageUrl, RB_FILE_EXTENSION);
        if(!$fileOpen) return NULL;
        @$fileSize = filesize($imageUrl);
        if($fileSize <= INTEGER_ZERO) return NULL;
        $imageData = fread($fileOpen, $fileSize);
        fclose($fileOpen);

        $encodingData = base64_encode($imageData);

        if(strlen($captchaKey) != FORTY_STRING_LENGTH && strlen($captchaKey) != THIRTY_TWO_STRING_LENGHT) return NULL;
        $imageData = ImageUploadJson::bypassCaptchaPostData(BYPASS_CAPTCHA_URL,array(CAPTCH_KEY_VALUE=> $captchaKey, CAPTCHA_FILE_VALUE => $encodingData, CAPTCHA_SUBMIT_KEY => CAPTCHA_SUBMIT_VALUE,CAPTCHA_GENERAL_TASK_ID =>FILE_CONTENTS_TRUE_VALUE, BASE64_CODE_VALUE => FILE_CONTENTS_TRUE_VALUE));
        $bypassCaptchaSplit = ImageUploadJson::bypassCaptchaSplit($imageData);
        if(array_key_exists(CAPTCHA_TASK_ID_VALUE, $bypassCaptchaSplit) && array_key_exists(CAPTCHA_VALUE, $bypassCaptchaSplit))
        {
            $bypassCaptchaTaskId = $bypassCaptchaSplit[CAPTCHA_TASK_ID_VALUE];
            return $bypassCaptchaSplit[CAPTCHA_VALUE];
        }
        return NULL;
    }
    function bypassCaptchaPostData($captchaUrl, $imageCaptchaData)
    {
        $curlWrapper=new CurlWrapper();
        return $curlWrapper->curlPost($captchaUrl,$imageCaptchaData);
    }
    function bypassCaptchaSplit($imageCaptchaData)
    {
        $imageCaptchaSplitData = array();
        $imageCaptchaLines = explode(LINE_SEPARATOR, $imageCaptchaData);
        if($imageCaptchaLines)
        {
            foreach($imageCaptchaLines as $imageCaptchaLineDetails)
            {
                $captchaDetails = trim($imageCaptchaLineDetails);
                if(strlen($captchaDetails) == INTEGER_ZERO) continue;

                $imageCaptchaValue = strstr($captchaDetails, ALBUM_BLANK_SPACE);
                $imageName = SINGLE_SEPARATORS;
                if($imageCaptchaValue === FALSE)
                {
                    $imageName = $captchaDetails;
                    $imageCaptchaValue = SINGLE_SEPARATORS;
                }
                else
                {
                    $imageName = substr($captchaDetails, INTEGER_ZERO, strlen($captchaDetails) - strlen($imageCaptchaValue));
                    $imageCaptchaValue = trim($imageCaptchaValue);
                }
                $imageCaptchaSplitData[$imageName] = $imageCaptchaValue;
            }
        }
        return $imageCaptchaSplitData;
    }
}
if(isset($_REQUEST[CALL_FUNCTION_KEY])){
    $insertUploadedImage=new ImageUploadJson;
    echo $insertUploadedImage->{$_REQUEST[CALL_FUNCTION_KEY]}();
}
?>