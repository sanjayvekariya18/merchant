<?php
//CURL
require_once dirname(__DIR__).DIRECTORY_SEPARATOR.'CurlWrapper.php';
use curl\ CurlWrapper;

class TestSimpleCurl extends PHPUnit_Framework_TestCase {
    var $postData=array('TestName'=>'Value for the Test Name', 'TestVar'=>'Value for the Var');

    public function testCurlAddSession() {
        //post URL
        $url="http://localhost/moodle/lib/cURL/examples/TestPost.php";
        $simpleCurlObj=new CurlWrapper();
        $opts=array(CURLOPT_POST=>true, CURLOPT_POSTFIELDS=>$this->postData, CURLOPT_RETURNTRANSFER=>true);
        $result=$simpleCurlObj->curlAddSession($url, $opts);
        $this->assertEquals($this->postData['TestName'], $result);
    }

    public function _testCurlAddSessionError() {
        //post URL
        $url="http://localhost/moodle/lib/cURL/examples/TestPost.php";
        $receivedData="Error Value for the Test Name";
        $simpleCurlObj=new CurlWrapper();
        $opts=array(CURLOPT_POST=>true, CURLOPT_POSTFIELDS=>$this->postData, CURLOPT_RETURNTRANSFER=>true);
        $result=$simpleCurlObj->curlAddSession($url, $opts);
        $this->assertEquals($receivedData, $result);
    }

    public function testMultiSession() {
        $simpleCurlObj=new CurlWrapper();
        $opts=array(CURLOPT_RETURNTRANSFER=>true, CURLOPT_POSTFIELDS=>$this->postData, CURLOPT_FOLLOWLOCATION=>true);
        //Multiple URLS
        $urls=array('http://localhost/moodle/lib/cURL/examples/TestPost.php');
        $result=$simpleCurlObj->multiSession($urls, $opts);
        $this->assertEquals($this->postData['TestName'], $result);
    }

    public function _testMultiSessionError() {
        $simpleCurlObj=new CurlWrapper();
        $receivedData="Error Value for the Test Name";
        $opts=array(CURLOPT_RETURNTRANSFER=>true, CURLOPT_POSTFIELDS=>$this->postData, CURLOPT_FOLLOWLOCATION=>true);
        //Multiple URLS
        $urls=array('http://localhost/moodle/lib/cURL/examples/TestPost.php');
        $result=$simpleCurlObj->multiSession($urls, $opts);
        $this->assertEquals($receivedData, $result);
    }
}
