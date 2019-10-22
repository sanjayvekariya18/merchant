<?php
//curl.class
require_once dirname(__DIR__).DIRECTORY_SEPARATOR.'CurlWrapper.php';
use curl\ CurlWrapper;

class TestCurlClass extends PHPUnit_Framework_TestCase {
    var $postData=array('TestName'=>'Value for the Test Name', 'TestVar'=>'Value for the Var');
    var $getData="Test content for the Get Value";

    public function testCurlGet() {
        //Get URL
        $url="http://localhost/moodle/lib/cURL/examples/TestGet.php";
        $simpleCurlObj=new CurlWrapper();
        $result=$simpleCurlObj->curlGet($url);
        $this->assertEquals($this->getData, $result);
    }

    public function _testCurlGetError() {
        //Get URL
        $url="http://localhost/moodle/lib/cURL/examples/TestGet.php";
        $receivedData="Error Test content for the Get Value";
        $simpleCurlObj=new CurlWrapper();
        $result=$simpleCurlObj->curlGet($url);
        $this->assertEquals($receivedData, $result);
    }

    public function testCurlPost() {
        $url="http://localhost/moodle/lib/cURL/examples/TestPost.php";
        $simpleCurlObj=new CurlWrapper();
        $result=$simpleCurlObj->curlPost($url, $this->postData);
        $this->assertEquals($this->postData['TestName'], $result);
    }

    public function _testCurlPostError() {
        $url="http://localhost/moodle/lib/cURL/examples/TestPost.php";
        $simpleCurlObj=new CurlWrapper();
        $postData="Error Value for the Test Name";
        $result=$simpleCurlObj->curlPost($url, $this->postData);
        $this->assertEquals($postData, $result);
    }

    public function testCurlPut() {
        //Put Urls
        $url="http://localhost/moodle/lib/cURL/examples/TestPut.php";
        //Get Data Urls
        $urlPut='C:\xampp\htdocs\moodle\lib\cURL\examples\test.txt';
        $simpleCurlObj=new CurlWrapper();
        $ReceivedData="the RAW data string I want to send";
        $result=$simpleCurlObj->curlPut($url, array('file'=>$urlPut));
        $this->assertEquals($ReceivedData, $result);
    }

    public function _testCurlPutError() {
        //Put Urls
        $url="http://localhost/moodle/lib/cURL/examples/TestPut.php";
        //Get Data Urls
        $urlPut='C:\xampp\htdocs\moodle\lib\cURL\examples\test.txt';
        $simpleCurlObj=new CurlWrapper();
        $ReceivedData="Error the RAW data string I want to send";
        $result=$simpleCurlObj->curlPut($url, array('file'=>$urlPut));
        $this->assertEquals($ReceivedData, $result);
    }

    public function testCurlDownload() {
        $simpleCurlObj=new CurlWrapper();
        //Download file urls
        $urls=array(array('url'=>'http://code.jquery.com/jquery-1.10.2.js', 'file'=>fopen('D:\Projects\sohyper\workspace\base-curl\curl\tests\jquery-1.10.2.js', 'wb')), array('url'=>'http://code.jquery.com/jquery-1.10.2.min.js', 'file'=>fopen('D:\Projects\sohyper\workspace\base-curl\curl\tests\jquery-1.10.2.min.js', 'wb')));
        $result=$simpleCurlObj->multiDownload($urls);
        $this->assertTrue(file_exists('D:\Projects\sohyper\workspace\base-curl\curl\tests\jquery-1.10.2.js'), 'File created');
        $this->assertTrue(file_exists('D:\Projects\sohyper\workspace\base-curl\curl\tests\jquery-1.10.2.min.js'), 'File created');
    }
}
