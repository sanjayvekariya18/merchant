<?php

/**
 * This file is for access to cURL,analog and smarty from other system for send
 * request and get bing translation response as well add/update translation word
 * into tiki side.
 * */
define('ACCESS_MAGENTO_SYSTEM','magento');//For access magento directory for cURL, smarty and kendoUI directory.
define('ACCESS_EVENTBOT_SYSTEM','eventBot');//For access eventbot directory for cURL, smarty and kendoUI directory.
define('ACCESS_TIKI_SYSTEM','tiki');
define('ACCESS_LARAVEL_SYSTEM','laravel');
define('DEFINE_EMPTY_VARIABLE','');////For access Other directory exceptmagento and eventbot for cURL, smarty and kendoUI directory.
define('ACCESS_TIKI_FROM_SEPARATE_SYSTEM','http://'.$_SERVER['HTTP_HOST'].'/tiki');
$systemName='laravel';
/**
 * If system is not magento or eventbot than $otherSystemPath should be path of
 *  your system for curl directory.
 */
$otherSystemPath=DEFINE_EMPTY_VARIABLE;
$documentRoot=dirname(dirname(__DIR__));
switch ($systemName) {
    case ACCESS_TIKI_SYSTEM:
        define('CURL_ACCESS_FILEPATH', $documentRoot.'/tiki/vendor/cURL/CurlWrapper.php');
        define('SMARTY_ACCESS_FILEPATH', $documentRoot.'/tiki/vendor/smarty/smarty/distribution/libs/Smarty.class.php');
        define('KENDO_ACCESS_FILEPATH', 'http://'.$_SERVER['HTTP_HOST'].'/tiki/vendor/kendoui/');
        break;
    case ACCESS_EVENTBOT_SYSTEM:
        define('CURL_ACCESS_FILEPATH', $documentRoot.'/eventbot/application/third_party/cURL/CurlWrapper.php');
        define('SMARTY_ACCESS_FILEPATH', $documentRoot.'/eventbot/application/third_party/smarty/Smarty.class.php');
        define('KENDO_ACCESS_FILEPATH', 'http://'.$_SERVER['HTTP_HOST'].'/eventbot/js/kendoui');
        break;

    case ACCESS_MAGENTO_SYSTEM:
        define('CURL_ACCESS_FILEPATH', $documentRoot.'/magento/lib/cURL/CurlWrapper.php');
        define('SMARTY_ACCESS_FILEPATH', $documentRoot.'/magento/lib/smarty/libs/ConnectSmarty.class.php');
        define('KENDO_ACCESS_FILEPATH', 'http://'.$_SERVER['HTTP_HOST'].'/magento/js/kendoui');
        break;
    case ACCESS_LARAVEL_SYSTEM:
        define('CURL_ACCESS_FILEPATH', $documentRoot.'/app/Http/Controllers/cURL/CurlWrapper.php');
        define('SMARTY_ACCESS_FILEPATH', $documentRoot.'/smarty/libs/Smarty.class.php');
        define('KENDO_ACCESS_FILEPATH', 'http://'.$_SERVER['HTTP_HOST'].'/'.$systemName.'/public/assets/kendoui/');
        break;

    default:
        /**
        *   define here path of your cURL directory for CurlWrapper.php file.
        *   Ex: C:/xampp/htdocs/YOUR_SYSTEM_DIRECTORY_NAME/lib/cURL/CurlWrapper.php
        */
        define('CURL_ACCESS_FILEPATH', $documentRoot.'/tiki/vendor/cURL/CurlWrapper.php');
        /**
        *   define here path of your Smarty directory for Smarty.class.php file.
        *   Ex: C:/xampp/htdocs/YOUR_SYSTEM_DIRECTORY_NAME/smarty/Smarty.class.php
        */
        define('SMARTY_ACCESS_FILEPATH', $documentRoot.'/tiki/vendor/smarty/smarty/distribution/libs/Smarty.class.php');
        /**
        *   define here path of your kendoUI directory for access js and css directory.
        *   Ex: C:/xampp/htdocs/YOUR_SYSTEM_DIRECTORY_NAME/kendoui
        */
        define('KENDO_ACCESS_FILEPATH', 'http://'.$_SERVER['HTTP_HOST'].'/tiki/vendor/kendoui/');
        break;
}
?>
