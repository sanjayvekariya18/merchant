<?php
require_once('../CurlWrapper.php');
use curl\ CurlWrapper;
//Put URL
$url           ="http://localhost/moodle/lib/cURL/examples/TestPut.php";
$urlPut        ='C:\xampp\htdocs\moodle\lib\cURL\examples\test.txt';
$curlWrapperObj=new CurlWrapper();
$result        =$curlWrapperObj->curlPut($url, array('file'=>$urlPut));
echo $result;
?>