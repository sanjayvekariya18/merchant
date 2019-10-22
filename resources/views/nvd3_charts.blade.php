@extends('layouts/default')

    {{-- Page title --}}
    @section('title')
        Nvd3 Charts
    @parent
@stop

{{-- page level styles --}}
@section('header_styles')
    <!--page level css -->
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/nvd3/css/nv.d3.min.css')}}" >
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom_css/nvd3_charts.css')}}" >
    <!--end of page level css-->
@stop

{{-- Page content --}}
@section('content')
    <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>NVD3 Charts</h1>
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
                    NVD3 Charts
                </li>
            </ol>
        </section>
        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <i class="fa fa-fw fa-bar-chart"></i> Stacked/Grouped Multi-Bar Chart
                            </h4>
                            <span class="pull-right">
                                        <i class="fa fa-fw fa-chevron-up clickable"></i>
                                        <i class="fa fa-fw fa-times removepanel clickable"></i>
                                    </span>
                        </div>
                        <div class="panel-body">
                            <div id="chart1">
                                <svg></svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- row -->
            <div class="row">
                <div class="col-lg-6">
                    <div class="panel panel-success">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <i class="fa fa-fw fa-line-chart"></i> Line Plus Bar Chart
                            </h4>
                            <span class="pull-right">
                                        <i class="fa fa-fw fa-chevron-up clickable"></i>
                                        <i class="fa fa-fw fa-times removepanel clickable"></i>
                                    </span>
                        </div>
                        <div class="panel-body bg-panel">
                            <div id="chart2" class='with-3d-shadow with-transitions'>
                                <svg></svg>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="panel panel-warning">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <i class="fa fa-fw fa-bar-chart"></i> Horizontal Multi-Bar Chart
                            </h4>
                            <span class="pull-right">
                                        <i class="fa fa-fw fa-chevron-up clickable"></i>
                                        <i class="fa fa-fw fa-times removepanel clickable"></i>
                                    </span>
                        </div>
                        <div class="panel-body bg-panel-info">
                            <div id="chart3" class='with-3d-shadow with-transitions'>
                                <svg></svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--row-->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-danger">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <i class="fa fa-fw fa-line-chart"></i> Cumulative Line Chart
                            </h4>
                            <span class="pull-right">
                                        <i class="fa fa-fw fa-chevron-up clickable"></i>
                                        <i class="fa fa-fw fa-times removepanel clickable"></i>
                                    </span>
                        </div>
                        <div class="panel-body">
                            <div id="chart4" class='with-3d-shadow with-transitions'>
                                <svg></svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--row-->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel bg-panel panel-info">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <i class="fa fa-fw fa-line-chart"></i> Scatter / Bubble Chart
                            </h4>
                            <span class="pull-right">
                                        <i class="fa fa-fw fa-chevron-up clickable"></i>
                                        <i class="fa fa-fw fa-times removepanel clickable"></i>
                                    </span>
                        </div>
                        <div class="panel-body">
                            <div id="chart5" class='with-3d-shadow with-transitions'>
                                <svg></svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- area chart -->
            <div class="row">
                <div class="col-lg-12">
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
                            <div id="chart6" class='with-3d-shadow with-transitions'>
                                <svg></svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- area chart end -->
            <!-- sunburst chart -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-danger">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <i class="fa fa-fw fa-pie-chart"></i> Sunburst Chart
                            </h4>
                            <span class="pull-right">
                                        <i class="fa fa-fw fa-chevron-up clickable"></i>
                                        <i class="fa fa-fw fa-times removepanel clickable"></i>
                                    </span>
                        </div>
                        <div class="panel-body">
                            <div id="chart7" class='with-3d-shadow with-transitions'>
                                <svg></svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- sunburst chart end -->
            <!-- row -->
        @include('layouts.right_sidebar')
        <!-- right side bar end -->
        </section>
@stop

{{-- page level scripts --}}
@section('footer_scripts')
    <!-- begining of page level js -->
<script type="text/javascript" src="{{asset('assets/vendors/d3/d3.min.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/nvd3/js/nv.d3.min.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/js/custom_js/stream_layers.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/js/custom_js/nvd3_charts.js')}}"></script>
    <!-- end of page level js ->
       @stop

