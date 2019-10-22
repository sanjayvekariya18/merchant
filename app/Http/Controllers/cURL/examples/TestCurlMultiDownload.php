<?php
require_once('../CurlWrapper.php');
use curl\ CurlWrapper;
$curlWrapperObj=new CurlWrapper();
//Download file urls
$urls=array(array('url'=>'http://code.jquery.com/jquery-1.10.2.js', 'file'=>fopen('jquery-1.10.2.js', 'wb')), array('url'=>'http://code.jquery.com/jquery-1.10.2.min.js', 'file'=>fopen('jquery-1.10.2.min.js', 'wb')));
$result=$curlWrapperObj->multiDownload($urls);
?>
