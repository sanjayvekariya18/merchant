@extends('layouts/default')
{{-- Page title --}}
@section('title')
    Monitoring
    @parent
@stop

{{-- page level styles --}}
@section('header_styles')
    <!--page level css -->
    <link rel="stylesheet" href="assets/kendoui/styles/kendo.common.min.css">
    <link rel="stylesheet" href="assets/kendoui/styles/kendo.rtl.min.css">
    <link rel="stylesheet" href="assets/kendoui/styles/kendo.default.min.css">
    <link rel="stylesheet" href="assets/kendoui/styles/kendo.mobile.all.min.css">
    <link rel="stylesheet" href="assets/kendoui/styles/kendo.default.mobile.min.css" />
    <link rel="stylesheet" href="assets/kendoui/styles/kendo.blueopal.min.css" />
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom_css/flot_charts.css')}}" >
        <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/bootstrapvalidator/css/bootstrapValidator.min.css')}}">
    <link href="{{asset('assets/vendors/select2/css/select2.min.css')}}" rel="stylesheet" type="text/css">
    <link href="{{asset('assets/vendors/select2/css/select2-bootstrap.css')}}" rel="stylesheet" type="text/css">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/bootstrap-switch/css/bootstrap-switch.css')}}"/>
    <style type="text/css">
        .flotChart {
            width: 100%;
            height: 140px;
            position: relative;
        }
        .k-grid-content {
    height: 0px !important;
    }
        table thead tr{
            background-color: #d9ecf5;
            color: #003f59
        }
    </style>
@stop
@section('content')
<section class="content-header">
    <ol class="breadcrumb">
        <li>
            <a href="index ">
                <i class="fa fa-fw fa-home"></i> Dashboard
            </a>
        </li>
        <li>
            <a href="#"> Proxy </a>
        </li>
        <li class="active">
            Monitoring
        </li>
    </ol>
</section>
<section class="content">
    <section class="content p-l-r-15">
        <div class="row">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <i class="fa fa-fw fa-users"></i>Monitoring
                    </h4>
                </div>
                <input type="hidden" id="requestUrl" value="{!!url(Request::segment(1))!!}">
                <div class="row">
                        <div class="col-md-12">
                            <form method='POST' action='{!!url("proxy_node_source")!!}' id="proxyNodeSourceForm">
                                {{ csrf_field() }}
                <div class="panel-body">
                    <div id="grid"></div>
                    <br>
                     <div id="allStatusTypeDetails" style="display: none;">
                        @foreach($proxy_type as $proxy_type_list)
                            @if(isset($proxy_type_list['status_speed_count']))
                                        <div class="col-lg-6">
                                            <div class="panel panel-warning">
                                                <div class="panel-heading">
                                                    <h4 class="panel-title">
                                                                <i class="fa fa-fw fa-pie-chart"></i> {{$proxy_type_list['type_name']}} Speed
                                                    </h4>
                                                </div>
                                                <div class="panel-body">
                                                    <div id="statusAll{{$proxy_type_list['type_name']}}" class="flotChart"></div>
                                                 </div>
                                            </div>
                                        </div>
                            
                                @endif
                                @if(isset($proxy_type_list['status_state_count']))
                                    <div class="col-lg-6">
                                        <div class="panel panel-warning">
                                            <div class="panel-heading">
                                                <h4 class="panel-title">
                                                    <i class="fa fa-fw fa-pie-chart"></i> {{$proxy_type_list['type_name']}} State
                                                </h4>
                                            </div>
                                            <div class="panel-body">
                                                <div id="initialAll{{$proxy_type_list['type_name']}}" class="flotChart">
                                                    
                                                </div>
                                            </div>
                                        </div>
                                     </div>
                                @endif
                    @endforeach
                </div>
                @foreach($proxy_type as $index=>$proxy_type_list)
                                    @if($index == 0)
                                       <div id="default" style="display: none;">
                                       <input type="hidden" id="proxy_status_id" name="proxy_status_id" value="{{$proxy_type_list['type_id']}}">
                                       <input type="hidden" id="proxy_status_name" name="proxy_status_name" value="{{$proxy_type_list['type_name']}}">
                                            <div class="row">
                                                        @if(isset($proxy_type_list['status_speed_count']))
                                                            <div>
                                                            <div class="col-lg-6">
                                                                <div class="panel panel-warning">
                                                                    <div class="panel-heading">
                                                                        <h4 class="panel-title">
                                                                            <i class="fa fa-fw fa-pie-chart"></i> {{$proxy_type_list['type_name']}} Speed
                                                                        </h4>
                                                                    </div>
                                                                    <div class="panel-body">
                                                                        <div id="status{{$proxy_type_list['type_name']}}" class="flotChart"></div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        @endif
                                                        @if(isset($proxy_type_list['status_state_count']))
                                                        <div>
                                                            <div class="col-lg-6">
                                                                <div class="panel panel-warning">
                                                                    <div class="panel-heading">
                                                                        <h4 class="panel-title">
                                                                            <i class="fa fa-fw fa-pie-chart"></i> {{$proxy_type_list['type_name']}} State
                                                                        </h4>
                                                                    </div>
                                                                    <div class="panel-body">
                                                                        <div id="initial{{$proxy_type_list['type_name']}}" class="flotChart"></div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        @endif
                                            </div>
                                        </div>
                                    @break
                                 @endif
                    @endforeach
                    @foreach($proxy_type as $proxy_type_list)
                                        <div id="{{$proxy_type_list['type_id']}}" style="display: none;">
                                            <div class="row">
                                               @if(isset($proxy_type_list['status_speed_count']))
                                                            <div>
                                                            <div class="col-lg-6">
                                                                <div class="panel panel-warning">
                                                                    <div class="panel-heading">
                                                                        <h4 class="panel-title">
                                                                            <i class="fa fa-fw fa-pie-chart"></i> {{$proxy_type_list['type_name']}} Speed
                                                                        </h4>
                                                                    </div>
                                                                    <div class="panel-body">
                                                                        <div id="status{{$proxy_type_list['type_name']}}" class="flotChart"></div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        @endif
                                                        <!-- <div>
                                                            <div class="col-lg-4">
                                                                <div class="panel panel-warning">
                                                                    <div class="panel-heading">
                                                                        <h4 class="panel-title">
                                                                            <i class="fa fa-fw fa-pie-chart"></i> {{$proxy_type_list['type_name']}} Test
                                                                        </h4>
                                                                    </div>
                                                                    <div class="panel-body">
                                                                        <div id="speed{{$proxy_type_list['type_name']}}" class="flotChart"></div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div> -->
                                                        @if(isset($proxy_type_list['status_state_count']))
                                                        <div>
                                                            <div class="col-lg-6">
                                                                <div class="panel panel-warning">
                                                                    <div class="panel-heading">
                                                                        <h4 class="panel-title">
                                                                            <i class="fa fa-fw fa-pie-chart"></i> {{$proxy_type_list['type_name']}} State
                                                                        </h4>
                                                                    </div>
                                                                    <div class="panel-body">
                                                                        <div id="initial{{$proxy_type_list['type_name']}}" class="flotChart"></div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        @endif
                                            </div>
                                        </div>
                    @endforeach
                        <div id="allProxyStatusType" style="display: none;">
                                            <div class="row">
                                                            <div>
                                                            <div class="col-lg-4">
                                                                <div class="panel panel-warning">
                                                                    <div class="panel-heading">
                                                                        <h4 class="panel-title">
                                                                            <i class="fa fa-fw fa-pie-chart"></i> All Speed
                                                                        </h4>
                                                                    </div>
                                                                    <div class="panel-body">
                                                                        <div id="donutAllLast" class="flotChart"></div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <!-- <div>
                                                            <div class="col-lg-4">
                                                                <div class="panel panel-warning">
                                                                    <div class="panel-heading">
                                                                        <h4 class="panel-title">
                                                                            <i class="fa fa-fw fa-pie-chart"></i>
                                                                           All Test
                                                                        </h4>
                                                                    </div>
                                                                    <div class="panel-body">
                                                                        <div id="donutAllPrev" class="flotChart"></div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div> -->
                                                        <div>
                                                            <div class="col-lg-4">
                                                                <div class="panel panel-warning">
                                                                    <div class="panel-heading">
                                                                        <h4 class="panel-title">
                                                                            <i class="fa fa-fw fa-pie-chart"></i> All State
                                                                        </h4>
                                                                    </div>
                                                                    <div class="panel-body">
                                                                        <div id="donutAllInit" class="flotChart"></div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                            </div>
                                            </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</section>
@endsection
<script src="{{asset('assets/kendoui/js/jquery.min.js')}}" type="text/javascript"></script>
@section('footer_scripts')
<script type="text/javascript" src="{{asset('assets/kendoui/js/kendo.all.min.js')}}"></script>
<script type="text/x-kendo-template" id="templates">
    <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th style="width: 40%; font-size: 15px;"><b>Proxy Type</b></th>
                            <th style="width: 60%; font-size: 15px;"><b>Display</b></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>All</td>
                            <td><input id="type_status_all" name="type_status_all" type="checkbox"/></td>
                        </tr>
                        @foreach($proxy_type as $index=>$proxy_type_list)

                         @if(isset($proxy_type_list['status_speed_count']) || isset($proxy_type_list['status_state_count']))
                                <tr>
                                    <td>{{$proxy_type_list['type_name']}}</td>
                                    @if($index == 0)
                                        <td><input id="types_status" name="types_status" type="checkbox" proxy_id = '{{$proxy_type_list['type_id']}}' proxy_name = '{{$proxy_type_list['type_name']}}' checked="true"/></td>
                                    @else
                                        <td><input id="type_status" name="type_status" type="checkbox" proxy_id = '{{$proxy_type_list['type_id']}}' proxy_name = '{{$proxy_type_list['type_name']}}'/></td>
                                    @endif
                                </tr>
                             @endif
                        @endforeach
                    </tbody>
    </table>
</script>
<script type="text/javascript" src="{{asset('assets/vendors/flotchart/js/jquery.flot.js')}}" ></script>
<script type="text/javascript" src="{{asset('assets/vendors/flotchart/js/jquery.flot.resize.js')}}" ></script>
<script language="javascript" type="text/javascript" src="{{asset('assets/vendors/flotchart/js/jquery.flot.stack.js')}}"></script>
<script language="javascript" type="text/javascript" src="{{asset('assets/vendors/flotchart/js/jquery.flot.time.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/flotspline/js/jquery.flot.spline.min.js')}}" ></script>
<script type="text/javascript" src="{{asset('assets/vendors/flotchart/js/jquery.flot.categories.js')}}" ></script>
<script type="text/javascript" src="{{asset('assets/vendors/flotchart/js/jquery.flot.pie.js')}}" ></script>
<script type="text/javascript" src="{{asset('assets/vendors/flot.tooltip/js/jquery.flot.tooltip.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/select2/js/select2.js')}}"></script>

<script type="text/javascript" src="{{asset('assets/vendors/bootstrap-switch/js/bootstrap-switch.js')}}"></script>

<script type="text/javascript" src="{{asset('assets/vendors/bootstrapvalidator/js/bootstrapValidator.min.js')}}" ></script>

<script type="text/javascript" src="{{asset('assets/js/custom_js/ProxyStatusType.js')}}"></script>
@stop
