@extends('layouts/default')

    {{-- Page title --}}
    @section('title')
        Flot Charts
    @parent
@stop

{{-- page level styles --}}
@section('header_styles')
    <!--page level css -->
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom_css/flot_charts.css')}}" >
    <!--end of page level css-->
@stop

{{-- Page content --}}
@section('content')
    <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                Flot Charts
            </h1>
            <ol class="breadcrumb">
                <li>
                    <a href="index">
                        <i class="fa fa-fw fa-home"></i> Dashboard
                    </a>
                </li>
                <li>
                    <a href="#">Charts</a>
                </li>
                <li class="active">
                    Flot Charts
                </li>
            </ol>
        </section>
        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-md-6">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <i class="fa fa-fw fa-line-chart"></i> Spline Line Chart
                            </h4>
                            <span class="pull-right">
                                    <i class="fa fa-fw fa-chevron-up clickable"></i>
                                    <i class="fa fa-fw fa-times removepanel clickable"></i>
                                </span>
                        </div>
                        <div class="panel-body">
                            <div id="basicFlotLegend1" class="flotLegend"></div>
                            <div id="basicflot" class="flotChart"></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <i class="fa fa-fw fa-line-chart"></i> Line Chart
                            </h4>
                            <span class="pull-right">
                                    <i class="fa fa-fw fa-chevron-up clickable"></i>
                                    <i class="fa fa-fw fa-times removepanel clickable"></i>
                            </span>
                        </div>
                        <div class="panel-body">
                            <div id="basicFlotLegend" class="flotLegend"></div>
                            <div id="line-chart" class="flotChart1"></div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- row -->
            <div class="row">
                <div class="col-lg-6">
                    <!-- Stack charts strats here-->
                    <div class="panel panel-default1">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <i class="fa fa-fw fa-bar-chart-o"></i> Bar Charts
                            </h4>
                            <span class="pull-right">
                                    <i class="fa fa-fw fa-chevron-up clickable"></i>
                                    <i class="fa fa-fw fa-times removepanel clickable"></i>
                                </span>
                        </div>
                        <div class="panel-body body-bg">
                            <div id="basicFlotLegend-barchart" class="flotLegend"></div>
                            <div id="bar-chart" class="flotChart1"></div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <!-- Stack charts strats here-->
                    <div class="panel panel-default1">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <i class="fa fa-fw fa-bar-chart-o"></i> Stacked Bar Charts
                            </h4>
                            <span class="pull-right">
                                    <i class="fa fa-fw fa-chevron-up clickable"></i>
                                    <i class="fa fa-fw fa-times removepanel clickable"></i>
                                </span>
                        </div>
                        <div class="panel-body">
                            <div id="basicFlotLegend-stac" class="flotLegend"></div>
                            <div id="bar-chart-stacked" class="flotChart1"></div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- row -->
            <div class="row">
                <div class="col-lg-4">
                    <!-- Stack charts strats here-->
                    <div class="panel panel-warning">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <i class="fa fa-fw fa-pie-chart"></i> Donut
                            </h4>
                            <span class="pull-right">
                                    <i class="fa fa-fw fa-chevron-up clickable"></i>
                                    <i class="fa fa-fw fa-times removepanel clickable"></i>
                                </span>
                        </div>
                        <div class="panel-body">
                            <div id="donut" class="flotChart2"></div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-8">
                    <!-- Stack charts strats here-->
                    <div class="panel panel-warning">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <i class="fa fa-fw fa-pie-chart"></i> Pie Charts
                            </h4>
                            <span class="pull-right">
                                    <i class="fa fa-fw fa-chevron-up clickable"></i>
                                    <i class="fa fa-fw fa-times removepanel clickable"></i>
                                </span>
                        </div>
                        <div class="panel-body">
                            <div class="col-lg-6">
                                <div id="placeholdertranslabel" class="flotChart1"></div>
                            </div>
                            <div class="col-lg-6">
                                <div id="placeholdertiltedpie" class="flotChart1"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- row -->
            <div class="row">
                <div class="col-lg-12">
                    <!-- Charts with symbols charts strats here-->
                    <div class="panel panel-default1">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <i class="fa fa-fw fa-area-chart"></i> Real Time Charts
                            </h4>
                            <span class="pull-right">
                                    <i class="fa fa-fw fa-chevron-up clickable"></i>
                                    <i class="fa fa-fw fa-times removepanel clickable"></i>
                                </span>
                        </div>
                        <div class="panel-body">
                            <div id="realtime" class="flotChart3"></div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- row -->
            <div class="row">
                <div class="col-lg-6">
                    <!-- Real time charts strats here-->
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <i class="fa fa-fw fa-area-chart"></i> Area Chart
                            </h4>
                            <span class="pull-right">
                                    <i class="fa fa-fw fa-chevron-up clickable"></i>
                                    <i class="fa fa-fw fa-times removepanel clickable"></i>
                            </span>
                        </div>
                        <div class="panel-body">
                            <div id="area-chart" class="flotChart3"></div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <!-- Real time charts strats here-->
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <i class="fa fa-fw fa-area-chart"></i> Spline Area Chart
                            </h4>
                            <span class="pull-right">
                                    <i class="fa fa-fw fa-chevron-up clickable"></i>
                                    <i class="fa fa-fw fa-times removepanel clickable"></i>
                            </span>
                        </div>
                        <div class="panel-body">
                            <div id="chart-spline" class="flotChart3"></div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- row -->
        @include('layouts.right_sidebar')
        <!-- right side bar end -->
        </section>
@stop

{{-- page level scripts --}}
@section('footer_scripts')
    <!-- begining of page level js -->
<script type="text/javascript" src="{{asset('assets/vendors/flotchart/js/jquery.flot.js')}}" ></script>
<script type="text/javascript" src="{{asset('assets/vendors/flotchart/js/jquery.flot.resize.js')}}" ></script>
<script language="javascript" type="text/javascript" src="{{asset('assets/vendors/flotchart/js/jquery.flot.stack.js')}}"></script>
<script language="javascript" type="text/javascript" src="{{asset('assets/vendors/flotchart/js/jquery.flot.time.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/flotspline/js/jquery.flot.spline.min.js')}}" ></script>
<script type="text/javascript" src="{{asset('assets/vendors/flotchart/js/jquery.flot.categories.js')}}" ></script>
<script type="text/javascript" src="{{asset('assets/vendors/flotchart/js/jquery.flot.pie.js')}}" ></script>
<script type="text/javascript" src="{{asset('assets/vendors/flot.tooltip/js/jquery.flot.tooltip.js')}}"></script>
<script language="javascript" type="text/javascript" src="{{asset('assets/js/custom_js/flot_charts.js')}}"></script>
    <!-- end of page level js -->
@stop
