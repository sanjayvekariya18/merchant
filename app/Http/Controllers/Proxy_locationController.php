<?php

namespace App\Http\Controllers;

use App\Helpers\ConnectionManager;
use App\Http\Controllers\Controller;
use App\Http\Traits\PermissionTrait;
use App\Proxy_location;
use App\Proxy_source;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Session;
use URL;

/**
 * Class Hase_staffController.
 *
 * @author  The scaffold-interface created at 2017-03-08 07:43:27am
 * @link  https://github.com/amranidev/scaffold-interface
 */

class Proxy_locationController extends PermissionsController
{
    use PermissionTrait;
    public $allRequest      = 0;
    public $allRequestServe = 0;
    public $totalRequest    = 0;
    public $serveRequest    = 0;
    public $responseMessage = "";

    public function __construct()
    {
        parent::__construct();

        $connectionStatus = ConnectionManager::setDbConfig('proxy_location', 'mysqlDynamicConnector');
        if (strcmp($connectionStatus['type'], "error") == 0) {
            Session::flash('type', $connectionStatus['type']);
            Session::flash('msg', $connectionStatus['message']);
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return  \Illuminate\Http\Response
     */
    public function index()
    {
        return view('proxy_location.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return  \Illuminate\Http\Response
     */
    public function fetchProxyLocation()
    {

        $connector = PermissionTrait::getIpstackApi();

        if (isset($connector->api_key)) {

            $access_key = $connector->api_key;

            $promises = array();

            $proxy_location_list_query = Proxy_source::
                select("proxy_id", "proxy_target_ip")
                ->where("proxy_target_ip", '!=', "")
                ->whereNOTIn('proxy_id', function ($query) {
                    $query->select('proxy_id')->from('proxy_location');
                })
                ->groupby('proxy_target_ip');

            $proxy_location_list = $proxy_location_list_query->get();

            $this->totalRequest = $proxy_location_list_query->get()->count();

            foreach ($proxy_location_list as $proxy) {
                $promises[] = 'http://api.ipstack.com/' . $proxy->proxy_target_ip . '?access_key=' . $access_key;
            }

            if ($this->totalRequest == 1) {
                // single curl request
                $rc = new RollingCurl("request_callback");
                $rc->request($promises[0]);
                $rc->execute();
                $this->serveRequest = $rc->getCountRecord();
            } else if ($this->totalRequest > 1) {
                $rc              = new RollingCurl("request_callback");
                $rc->window_size = 20;
                foreach ($promises as $url) {
                    $request = new RollingCurlRequest($url);
                    $rc->add($request);
                }
                $rc->execute();
                $this->serveRequest = $rc->getCountRecord();
            }

            $this->allRequest += $this->serveRequest;
            $this->allRequestServe += $this->serveRequest;
            $this->responseMessage = $this->allRequestServe . "/" . $this->allRequest;

        }

        return $this->responseMessage;

    }

    public function request_callback($response, $info)
    {

        $parts = parse_url($info['url']);
        parse_str($parts['query'], $query);
        $api_key = $query['access_key'];

        $connector = PermissionTrait::getIpstackApiByKey($api_key);

        $result = json_decode($response, true);

        if (isset($result['ip'])) {

            $proxies = Proxy_source::select('proxy_id', 'proxy_target_ip')->where('proxy_target_ip', $result['ip'])->get();

            foreach ($proxies as $proxy) {

                $proxy_location               = new Proxy_location();
                $proxy_location->proxy_id     = $proxy->proxy_id;
                $proxy_location->country_code = $result['country_code'];
                $proxy_location->country_name = $result['country_name'];
                $proxy_location->region_code  = $result['region_code'];
                $proxy_location->region_name  = $result['region_name'];
                $proxy_location->city         = $result['city'];
                $proxy_location->zip          = $result['zip'];
                $proxy_location->country_flag = $result['location']['country_flag'];
                $proxy_location->save();

                $proxy_source               = Proxy_source::find($proxy->proxy_id);
                $proxy_source->request_date = date('Ymd');
                $proxy_source->request_time = time();
                $proxy_source->save();

            }

            PermissionTrait::updateIpstackApiCounter($connector->id);

        } else if (isset($result['error'])) {
            $this->allRequest += $this->serveRequest;
            $this->allRequestServe += $this->serveRequest;
            $this->responseMessage = "";
            PermissionTrait::disableIpstackApiCounter($connector->id);
            $this->fetchProxyLocation();
        }

        return true;
    }

    public function getProxyLocationList(Request $request)
    {

        $proxy_location_list = Proxy_location::
            distinct()
            ->select('proxy_target_ip', 'country_code', 'country_name', 'region_code', 'region_name', 'city', 'zip', 'country_flag')
            ->join("proxy_source", 'proxy_source.proxy_id', 'proxy_location.proxy_id')
            ->offset($request->skip)
            ->limit($request->take)
            ->get();

        $total_records = Proxy_location::
            join("proxy_source", 'proxy_source.proxy_id', 'proxy_location.proxy_id')
            ->count();

        $proxies_location['proxy_location_list'] = $proxy_location_list;
        $proxies_location['total']               = $total_records;

        return json_encode($proxies_location);

    }
}

class RollingCurlRequest
{
    public $url       = false;
    public $method    = 'GET';
    public $post_data = null;
    public $headers   = null;
    public $options   = null;
    /**
     * @param string $url
     * @param string $method
     * @param  $post_data
     * @param  $headers
     * @param  $options
     * @return void
     */
    public function __construct($url, $method = "GET", $post_data = null, $headers = null, $options = null)
    {
        $this->url       = $url;
        $this->method    = $method;
        $this->post_data = $post_data;
        $this->headers   = $headers;
        $this->options   = $options;
    }
    /**
     * @return void
     */
    public function __destruct()
    {
        unset($this->url, $this->method, $this->post_data, $this->headers, $this->options);
    }
}
/**
 * RollingCurl custom exception
 */
class RollingCurlException extends Exception
{}
/**
 * Class that holds a rolling queue of curl requests.
 *
 * @throws RollingCurlException
 */
class RollingCurl
{
    /**
     * @var int
     *
     * Window size is the max number of simultaneous connections allowed.
     *
     * REMEMBER TO RESPECT THE SERVERS:
     * Sending too many requests at one time can easily be perceived
     * as a DOS attack. Increase this window_size if you are making requests
     * to multiple servers or have permission from the receving server admins.
     */
    private $window_size = 5;
    /**
     * @var float
     *
     * Timeout is the timeout used for curl_multi_select.
     */
    private $timeout = 10;
    /**
     * @var string|array
     *
     * Callback function to be applied to each result.
     */
    private $callback;

    private $countRecord = 0;
    /**
     * @var array
     *
     * Set your base options that you want to be used with EVERY request.
     */
    protected $options = array(
        CURLOPT_SSL_VERIFYPEER => 0,
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_CONNECTTIMEOUT => 30,
        CURLOPT_TIMEOUT        => 30,
    );

    /**
     * @var array
     */
    private $headers = array();
    /**
     * @var Request[]
     *
     * The request queue
     */
    private $requests = array();
    /**
     * @var RequestMap[]
     *
     * Maps handles to request indexes
     */
    private $requestMap = array();
    /**
     * @var returns[]
     *
     * All returns of requests
     */
    private $returns = array();
    /**
     * @param  $callback
     * Callback function to be applied to each result.
     *
     * Can be specified as 'my_callback_function'
     * or array($object, 'my_callback_method').
     *
     * Function should take three parameters: $response, $info, $request.
     * $response is response body, $info is additional curl info.
     * $request is the original request
     *
     * @return void
     */
    public function __construct($callback = null)
    {
        $this->callback = $callback;
    }
    /**
     * @param string $name
     * @return mixed
     */
    public function __get($name)
    {
        return (isset($this->{$name})) ? $this->{$name} : null;
    }
    /**
     * @param string $name
     * @param mixed $value
     * @return bool
     */
    public function __set($name, $value)
    {
        // append the base options & headers
        if ($name == "options" || $name == "headers") {
            $this->{$name} = $value + $this->{$name};
        } else {
            $this->{$name} = $value;
        }
        return true;
    }
    /**
     * Add a request to the request queue
     *
     * @param Request $request
     * @return bool
     */
    public function add($request)
    {
        $this->requests[] = $request;
        return true;
    }
    /**
     * @param \returns[] $returns
     */
    public function setReturns($returns)
    {
        $this->returns = $returns;
    }
    /**
     * @return \returns[]
     */
    public function getReturns()
    {
        return $this->returns;
    }

    /**
     * @return \countRecord
     */
    public function getCountRecord()
    {
        return $this->countRecord;
    }

    /**
     * Create new Request and add it to the request queue
     *
     * @param string $url
     * @param string $method
     * @param  $post_data
     * @param  $headers
     * @param  $options
     * @return bool
     */
    public function request($url, $method = "GET", $post_data = null, $headers = null, $options = null)
    {
        $this->requests[] = new RollingCurlRequest($url, $method, $post_data, $headers, $options);
        return true;
    }
    /**
     * Perform GET request
     *
     * @param string $url
     * @param  $headers
     * @param  $options
     * @return bool
     */
    public function get($url, $headers = null, $options = null)
    {
        return $this->request($url, "GET", null, $headers, $options);
    }
    /**
     * Perform POST request
     *
     * @param string $url
     * @param  $post_data
     * @param  $headers
     * @param  $options
     * @return bool
     */
    public function post($url, $post_data = null, $headers = null, $options = null)
    {
        return $this->request($url, "POST", $post_data, $headers, $options);
    }
    /**
     * Execute the curl
     *
     * @param int $window_size Max number of simultaneous connections
     * @return string|bool
     */
    public function execute($window_size = null)
    {
        // rolling curl window must always be greater than 1
        if (sizeof($this->requests) == 1) {
            return $this->single_curl();
        } else {
            // start the rolling curl. window_size is the max number of simultaneous connections
            return $this->rolling_curl($window_size);
        }
    }
    /**
     * Performs a single curl request
     *
     * @access private
     * @return string
     */
    private function single_curl()
    {
        $ch       = curl_init();
        $request  = array_shift($this->requests);
        $options  = $this->get_options($request);
        $proxyObj = new Proxy_locationController();
        curl_setopt_array($ch, $options);
        $output = curl_exec($ch);
        $info   = curl_getinfo($ch);
        // it's not neccesary to set a callback for one-off requests
        if ($this->callback) {
            $callback = $this->callback;
            if (is_callable(array($proxyObj, $callback))) {
                call_user_func(array($proxyObj, $callback), $output, $info, $request);
                $this->countRecord++;
            }
        } else {
            return $output;
        }

        return true;
    }
    /**
     * Performs multiple curl requests
     *
     * @access private
     * @throws RollingCurlException
     * @param int $window_size Max number of simultaneous connections
     * @return bool
     */
    private function rolling_curl($window_size = null)
    {
        if ($window_size) {
            $this->window_size = $window_size;
        }

        // make sure the rolling window isn't greater than the # of urls
        if (sizeof($this->requests) < $this->window_size) {
            $this->window_size = sizeof($this->requests);
        }

        if ($this->window_size < 2) {
            throw new RollingCurlException("Window size must be greater than 1");
        }
        $proxyObj = new Proxy_locationController();
        $master   = curl_multi_init();
        // start the first batch of requests
        for ($i = 0; $i < $this->window_size; $i++) {
            $ch      = curl_init();
            $options = $this->get_options($this->requests[$i]);
            curl_setopt_array($ch, $options);
            curl_multi_add_handle($master, $ch);
            // Add to our request Maps
            $key                    = (string) $ch;
            $this->requestMap[$key] = $i;
        }
        do {
            while (($execrun = curl_multi_exec($master, $running)) == CURLM_CALL_MULTI_PERFORM);
            if ($execrun != CURLM_OK) {
                break;
            }
            // a request was just completed -- find out which one
            while ($done = curl_multi_info_read($master)) {
                // get the info and content returned on the request
                $info   = curl_getinfo($done['handle']);
                $output = curl_multi_getcontent($done['handle']);
                array_push($this->returns, array(
                    'return' => $output,
                    'info'   => $info,
                ));

                // send the return values to the callback function.
                $callback = $this->callback;

                if (is_callable(array($proxyObj, $callback))) {
                    $key     = (string) $done['handle'];
                    $request = $this->requests[$this->requestMap[$key]];
                    unset($this->requestMap[$key]);
                    call_user_func(array($proxyObj, $callback), $output, $info, $request);
                    $this->countRecord++;
                }
                // start a new request (it's important to do this before removing the old one)
                if ($i < sizeof($this->requests) && isset($this->requests[$i]) && $i < count($this->requests)) {
                    $ch      = curl_init();
                    $options = $this->get_options($this->requests[$i]);
                    curl_setopt_array($ch, $options);
                    curl_multi_add_handle($master, $ch);
                    // Add to our request Maps
                    $key                    = (string) $ch;
                    $this->requestMap[$key] = $i;
                    $i++;
                }
                // remove the curl handle that just completed
                curl_multi_remove_handle($master, $done['handle']);
            }
            // Block for data in / output; error handling is done by curl_multi_exec
            if ($running) {
                curl_multi_select($master, $this->timeout);
            }
        } while ($running);
        curl_multi_close($master);
        return true;
    }
    /**
     * Helper function to set up a new request by setting the appropriate options
     *
     * @access private
     * @param Request $request
     * @return array
     */
    private function get_options($request)
    {
        // options for this entire curl object
        $options = $this->__get('options');
        // NOTE: The PHP cURL library won't follow redirects if either safe_mode is on
        // or open_basedir is defined.
        // See: https://bugs.php.net/bug.php?id=30609
        if ((ini_get('safe_mode') == 'Off' || !ini_get('safe_mode'))
            && ini_get('open_basedir') == '') {
            $options[CURLOPT_FOLLOWLOCATION] = 1;
            $options[CURLOPT_MAXREDIRS]      = 5;
        }
        $headers = $this->__get('headers');
        // append custom options for this specific request
        if ($request->options) {
            $options = $request->options + $options;
        }
        // set the request URL
        $options[CURLOPT_URL] = $request->url;
        // posting data w/ this request?
        if ($request->post_data) {
            $options[CURLOPT_POST]       = 1;
            $options[CURLOPT_POSTFIELDS] = $request->post_data;
        }
        if ($headers) {
            $options[CURLOPT_HEADER]     = 0;
            $options[CURLOPT_HTTPHEADER] = $headers;
        }

        // Due to a bug in cURL CURLOPT_WRITEFUNCTION must be defined as the last option
        // Otherwise it doesn't register. So let's unset and set it again
        // See http://stackoverflow.com/questions/15937055/curl-writefunction-not-being-called
        if (!empty($options[CURLOPT_WRITEFUNCTION])) {
            $writeCallback = $options[CURLOPT_WRITEFUNCTION];
            unset($options[CURLOPT_WRITEFUNCTION]);
            $options[CURLOPT_WRITEFUNCTION] = $writeCallback;
        }
        return $options;
    }
    /**
     * @return void
     */
    public function __destruct()
    {
        unset($this->window_size, $this->callback, $this->options, $this->headers, $this->requests);
    }
}
