<?php 
require_once('../CurlWrapper.php');
use curl\ CurlWrapper;
$curlWrapperObj=new CurlWrapper();
$opts=array(CURLOPT_RETURNTRANSFER=>true, CURLOPT_FOLLOWLOCATION=>true);
//Multiple URLS
$urls=array('http://yahoo.com/', 'http://google.com/', 'http://ask.com/');
$result=$curlWrapperObj->multiSession($urls, $opts);
echo $result[0];
?>