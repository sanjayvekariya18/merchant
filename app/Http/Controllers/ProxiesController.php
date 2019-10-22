<?php

namespace App\Http\Controllers;

use App\Helpers\ConnectionManager;
use App\Http\Traits\PermissionTrait;
use App\ProxyStatus;
use App\Proxy_details;
use App\Proxy_location;
use App\Proxy_source;
use App\Proxy_type;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Session;
use Analog\AnalogHelper as Debug;
include_once base_path('vendor/').'analog-helper/AnalogHelper.php';

class ProxiesController extends PermissionsController
{
    use PermissionTrait;

    public function __construct()
    {
        parent::__construct();

        $connectionStatus = ConnectionManager::setDbConfig('proxy_location', 'mysqlDynamicConnector');
        if (strcmp($connectionStatus['type'], "error") == 0) {
            Session::flash('type', $connectionStatus['type']);
            Session::flash('msg', $connectionStatus['message']);
        }
    }
    public function index()
    {
        return view('proxy.proxy_node_source');
    }
    public function donutsList()
    {
        $proxy_type_list = Proxy_type::get();
        foreach ($proxy_type_list as $key => $proxy_type_list_value) {
            $proxy_tor_last_node_source = Proxy_source::
                leftjoin('proxy_status as proxyStatus', 'proxyStatus.status_id', 'proxy_source.proxy_speed')
                ->where('proxy_source.proxy_type', $proxy_type_list_value->type_id)
                ->groupBy('proxy_source.proxy_speed')
                ->where('proxy_source.proxy_speed', '!=', 11)
                ->select(DB::raw('count(*) as status_speed_count,proxyStatus.*'))->first();
            if (isset($proxy_tor_last_node_source->status_speed_count)) {
                $proxy_type_list[$key]->status_speed_count = $proxy_tor_last_node_source->status_speed_count;
            } else {
                $proxy_type_list[$key]->status_speed_count = 0;
            }
            $proxy_tor_init_node_source = Proxy_source::
                leftjoin('proxy_status as proxyStatus', 'proxyStatus.status_id', 'proxy_source.proxy_state')
                ->where('proxy_source.proxy_type', $proxy_type_list_value->type_id)
                ->where('proxy_source.proxy_state', '!=', 11)
                ->groupBy('proxy_source.proxy_state')
                ->select(DB::raw('count(*) as status_state_count,proxyStatus.*'))->first();
            if (isset($proxy_tor_init_node_source->status_state_count)) {
                $proxy_type_list[$key]->status_state_count = $proxy_tor_init_node_source->status_state_count;
            } else {
                $proxy_type_list[$key]->status_state_count = 0;
            }
        }
        $proxy_type_list = $proxy_type_list->where('status_state_count', '!=', 0)->where('status_speed_count', '!=', 0)->toArray();
        $proxy_type      = array_values($proxy_type_list);
        return view('proxy.monitoring', compact('proxy_type', 'title'));
    }
    public function proxyAllStatusDetails()
    {
        $proxy_type_list = Proxy_type::get();
        foreach ($proxy_type_list as $key => $proxy_type_list_value) {
            $proxy_tor_last_node_source = Proxy_source::
                leftjoin('proxy_status as proxyStatus', 'proxyStatus.status_id', 'proxy_source.proxy_speed')
                ->where('proxy_source.proxy_type', $proxy_type_list_value->type_id)
                ->groupBy('proxy_source.proxy_speed')
                ->where('proxy_source.proxy_speed', '!=', 11)
                ->select(DB::raw('count(*) as status_speed_count,proxyStatus.*'))->first();
            if (isset($proxy_tor_last_node_source->status_speed_count)) {
                $proxy_type_list[$key]->status_speed_count = $proxy_tor_last_node_source->status_speed_count;
            }
            $proxy_tor_init_node_source = Proxy_source::
                leftjoin('proxy_status as proxyStatus', 'proxyStatus.status_id', 'proxy_source.proxy_state')
                ->where('proxy_source.proxy_type', $proxy_type_list_value->type_id)
                ->where('proxy_source.proxy_state', '!=', 11)
                ->groupBy('proxy_source.proxy_state')
                ->select(DB::raw('count(*) as status_state_count,proxyStatus.*'))->first();
            if (isset($proxy_tor_init_node_source->status_state_count)) {
                $proxy_type_list[$key]->status_state_count = $proxy_tor_init_node_source->status_state_count;
            }
        }
        return $proxy_type = $proxy_type_list->toArray();
    }
    public function lastStatusChartDetailsList($proxy_status_id)
    {
        $proxy_tor_last_node_source_list = array();
        $proxy_tor_last_node_source      = Proxy_source::
            leftjoin('proxy_status as proxyStatus', 'proxyStatus.status_id', 'proxy_source.proxy_speed')
            ->where('proxy_source.proxy_type', $proxy_status_id)
            ->groupBy('proxy_source.proxy_speed')
            ->where('proxy_source.proxy_speed', '!=', 11)
            ->select(DB::raw('count(*) as status_count,proxyStatus.*'))->get();
        foreach ($proxy_tor_last_node_source as $key => $proxy_details_value) {
            $proxy_tor_last_node_source_list[] = array('data' => $proxy_details_value->status_count, 'label' => $proxy_details_value->status_name, 'color' => $proxy_details_value->proxy_status_color);
        }
        return json_encode($proxy_tor_last_node_source_list);

    }
    public function prevSpeedChartList($proxy_status_id)
    {
        $proxy_tor_prev_node_source_list = array();
        $proxy_tor_prev_node_source      = Proxy_source::
            leftjoin('proxy_status as proxyStatus', 'proxyStatus.status_id', 'proxy_source.proxy_test')
            ->where('proxy_source.proxy_type', $proxy_status_id)
            ->groupBy('proxy_source.proxy_test')
            ->where('proxy_source.proxy_test', '!=', 11)
            ->select(DB::raw('count(*) as status_count,proxyStatus.*'))->get();
        foreach ($proxy_tor_prev_node_source as $key => $proxy_details_value) {
            $proxy_tor_prev_node_source_list[] = array('data' => $proxy_details_value->status_count, 'label' => $proxy_details_value->status_name, 'color' => $proxy_details_value->proxy_status_color);
        }
        return json_encode($proxy_tor_prev_node_source_list);
    }
    public function initInitialChartDetailsList($proxy_status_id)
    {
        $proxy_tor_init_node_source_list = array();
        $proxy_tor_init_node_source      = Proxy_source::
            leftjoin('proxy_status as proxyStatus', 'proxyStatus.status_id', 'proxy_source.proxy_state')
            ->where('proxy_source.proxy_type', $proxy_status_id)
            ->where('proxy_source.proxy_state', '!=', 11)
            ->groupBy('proxy_source.proxy_state')
            ->select(DB::raw('count(*) as status_count,proxyStatus.*'))->get();
        foreach ($proxy_tor_init_node_source as $key => $proxy_details_value) {
            $proxy_tor_init_node_source_list[] = array('data' => $proxy_details_value->status_count, 'label' => $proxy_details_value->status_name, 'color' => $proxy_details_value->proxy_status_color);
        }
        return json_encode($proxy_tor_init_node_source_list);
    }
    public function chartAllLastDetailsList()
    {
        $proxy_all_last_node_source_list = array();
        $proxy_all_last_node_source      = Proxy_source::
            leftjoin('proxy_status as proxyStatus', 'proxyStatus.status_id', 'proxy_source.proxy_speed')
            ->groupBy('proxy_source.proxy_speed')
            ->where('proxy_source.proxy_speed', '!=', 11)
            ->select(DB::raw('count(*) as status_count,proxyStatus.*'))->get();
        foreach ($proxy_all_last_node_source as $key => $proxy_details_value) {
            $proxy_all_last_node_source_list[] = array('data' => $proxy_details_value->status_count, 'label' => $proxy_details_value->status_name, 'color' => $proxy_details_value->proxy_status_color);
        }
        return json_encode($proxy_all_last_node_source_list);
    }
    public function chartAllPrevDetailsList()
    {
        $proxy_all_prev_node_source_list = array();
        $proxy_all_prev_node_source      = Proxy_source::
            leftjoin('proxy_status as proxyStatus', 'proxyStatus.status_id', 'proxy_source.proxy_test')
            ->groupBy('proxy_source.proxy_test')
            ->where('proxy_source.proxy_test', '!=', 11)
            ->select(DB::raw('count(*) as status_count,proxyStatus.*'))->get();
        foreach ($proxy_all_prev_node_source as $key => $proxy_details_value) {
            $proxy_all_prev_node_source_list[] = array('data' => $proxy_details_value->status_count, 'label' => $proxy_details_value->status_name, 'color' => $proxy_details_value->proxy_status_color);
        }
        return json_encode($proxy_all_prev_node_source_list);
    }
    public function chartAllInitDetailsList()
    {
        $proxy_chart_all_init_node_source_list = array();
        $proxy_chart_all_init_node_source      = Proxy_source::
            leftjoin('proxy_status as proxyStatus', 'proxyStatus.status_id', 'proxy_source.proxy_state')
            ->groupBy('proxy_source.proxy_state')
            ->where('proxy_source.proxy_state', '!=', 11)
            ->select(DB::raw('count(*) as status_count,proxyStatus.*'))->get();
        foreach ($proxy_chart_all_init_node_source as $key => $proxy_details_value) {
            $proxy_chart_all_init_node_source_list[] = array('data' => $proxy_details_value->status_count, 'label' => $proxy_details_value->status_name, 'color' => $proxy_details_value->proxy_status_color);
        }
        return json_encode($proxy_chart_all_init_node_source_list);
    }
    public function proxyNodeSourceListData(Request $request)
    {
        $total_records             = 0;
        $proxy_node_source_details = Proxy_source::
            select('proxy_source.*', 'proxy_status.status_name as proxy_state', 'proxyStatus.status_name as old_state', 'proxy_type.type_name as proxy_type')
            ->leftjoin('proxy_status', 'proxy_status.status_id', 'proxy_source.proxy_state')
            ->leftjoin('proxy_type', 'proxy_type.type_id', 'proxy_source.proxy_type')
            ->leftjoin('proxy_status as proxyStatus', 'proxyStatus.status_id', 'proxy_source.proxy_speed');
        if (isset($request->filter['filters'])) {
            $filterValue = $request->filter['filters'][0]['value'];
            $filterField = $request->filter['filters'][0]['field'];
            if ($filterField === 'proxy_state') {
                $filterField = 'proxy_status.status_name';
            }
            if ($filterField === 'old_state') {
                $filterField = 'proxyStatus.status_name';
            }
            if ($filterField === 'proxy_type') {
                $filterField = 'proxy_type.type_name';
            }
            if ($request->filter['filters'][0]['operator'] == 'eq') {
                $proxy_node_source_details->where($filterField, '=', $filterValue);
            } elseif ($request->filter['filters'][0]['operator'] == 'neq') {
                $proxy_node_source_details->where($filterField, '!=', $filterValue);
            } else {
                $proxy_node_source_details->where($filterField, 'LIKE', '%' . $filterValue . '%');
            }
        }
        $total_records = $total_records + $proxy_node_source_details->get()->count();
        if (isset($request->take)) {
            $proxy_node_source_details->offset($request->skip)->limit($request->take);
        }
        $proxy_node_source = $proxy_node_source_details->get();
        foreach ($proxy_node_source as $key => $proxy_node_source_value) {
            $proxy_details = Proxy_details::where('source_id', '=', $proxy_node_source_value->proxy_id)->first();
            if (isset($proxy_details->source_id)) {
                $summary_id = 1;
            } else {
                $summary_id = 0;
            }

            $proxy_node_source[$key]->proxy_details_id = $summary_id;
            $datetime                                  = json_decode(PermissionTrait::covertToLocalTz($proxy_node_source_value->proxy_beg_time));
            $proxy_node_source[$key]->proxy_beg_date   = $datetime->date;
            $proxy_node_source[$key]->proxy_beg_time   = $datetime->time;

            $proxy_summery_datetime                = json_decode(PermissionTrait::covertToLocalTz($proxy_node_source_value->request_time));
            $proxy_node_source[$key]->request_date = $proxy_summery_datetime->date;
            $proxy_node_source[$key]->request_time = $proxy_summery_datetime->time;
        }
        $proxy_node_source['proxy_node_source'] = $proxy_node_source->toArray();
        $proxy_node_source['total']             = $total_records;
        return $proxy_node_source;
    }
    public function proxySummeryDetailsList($summaryId)
    {
        $proxy_details = Proxy_details::
            leftjoin('proxy_status', 'proxy_status.status_id', 'proxy_details.proxy_speed')
            ->where('proxy_details.source_id', $summaryId)
            ->select('proxy_details.*', 'proxy_status.status_name as proxy_summery_status')->get();
        foreach ($proxy_details as $key => $proxy_details_value) {
            $datetime                          = json_decode(PermissionTrait::covertToLocalTz($proxy_details_value->request_time));
            $proxy_details[$key]->request_date = $datetime->date;
            $proxy_details[$key]->request_time = $datetime->time;
        }
        $proxy_details_details = $proxy_details->toArray();
        return $proxy_details_details;
    }

    public function proxyLocationDetails($proxyId)
    {
        $proxy_details = Proxy_location::where("proxy_id", $proxyId)->get()->toArray();
        return $proxy_details;
    }

    public function updateProxyDetailsStatusList(Request $request)
    {
        try {
            $proxy_summary              = Proxy_source::findOrfail($request->proxy_id);
            $proxy_status_details       = ProxyStatus::where('status_name', '=', $request->current_status)->first();
            $proxy_summary->proxy_state = $proxy_status_details->status_id;
            $proxy_proxy_speed_details  = ProxyStatus::where('status_name', '=', $request->status_name)->first();
            $proxy_summary->proxy_speed  = $proxy_proxy_speed_details->status_id;
            $proxy_summary->save();
            return $proxy_summary;

        } catch (\Exception $e) {

            $exceptionMessage = ConnectionManager::renderPdoException($e->getCode());
            return (array("error" => $exceptionMessage));
        }
    }
    public function proxyStatusList()
    {
        $proxy_status = ProxyStatus::get();
        return json_encode($proxy_status);
    }
    public function updateProxyStatusColor(Request $request)
    {
        try {
            $proxyStatus                     = ProxyStatus::findOrFail($request->proxy_status_id);
            $proxyStatus->proxy_status_color = $request->proxy_status_color_code;
            $proxyStatus->save();
            return $proxyStatus;
        } catch (\Exception $e) {

            $exceptionMessage = ConnectionManager::renderPdoException($e->getCode());
            return (array("error" => $exceptionMessage));
        }
    }
}
