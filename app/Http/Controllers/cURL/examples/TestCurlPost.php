<?php
require_once('../CurlWrapper.php');
use curl\ CurlWrapper;
$postData            =array();
$postData['TestName']="Value for the Test Name";
$postData['TestVar'] ="Value for the Var";
//post URL
$url           ="http://localhost/moodle/lib/cURL/examples/TestPost.php";
$curlWrapperObj=new CurlWrapper();
$result        =$curlWrapperObj->curlPost($url, $postData);
echo $result;
?>