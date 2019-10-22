<?php
require_once('../CurlWrapper.php');
use curl\ CurlWrapper;
//Get URL
$url           ="http://localhost/moodle/lib/cURL/examples/TestGet.php";
$curlWrapperObj=new CurlWrapper();
$result        =$curlWrapperObj->curlGet($url);
echo $result;
?>