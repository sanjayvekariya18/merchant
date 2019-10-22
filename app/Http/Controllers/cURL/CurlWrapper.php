<?php
namespace curl;
require_once 'CURL.php';
require_once 'vendor/autoload.php';
use dcai\curl as curlClass;
use curl\ semlabs\ CURL as CURL;

class CurlWrapper {

    private $curl;

    private $curlClass;

    function __construct() {
        $this->curl=new CURL();
        $this->curlClass=new curlClass();
    }

    public function curlPost($url, $params) {
        return $this->curlClass->post($url, $params);
    }

    public function curlGet($url) {
        return $this->curlClass->get($url);
    }

    public function curlPut($url, $params) {
        return $this->curlClass->put($url, $params);
    }

    public function curlAddSession($url, $params) {
        $this->curl->addSession($url, $params);
        $curlResult=$this->curl->exec();
        $this->curl->clear();
        return $curlResult;
    }

    public function multiSession($urls, $params) {
        foreach ($urls as $url)
            $this->curl->addSession($url, $params);
        $curlResult=$this->curl->exec();
        $this->curl->clear();
        return $curlResult;
    }

    public function multiDownload($urls) {
        return $this->curlClass->download($urls);
    }
}
