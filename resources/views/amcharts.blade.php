@extends('layouts/default')

    {{-- Page title --}}
    @section('title')
        Amcharts
    @parent
@stop

{{-- page level styles --}}
@section('header_styles')
    <!--page level css -->
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/amcharts/css/export.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom_css/amcharts.css')}}">
    <!--end of page level css-->
@stop

{{-- Page content --}}
@section('content')
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                Amcharts
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
                    Amcharts
                </li>
            </ol>
        </section>
        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-md-6">
                    <!-- Basic charts strats here-->
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <i class="fa fa-fw fa-bar-chart"></i> Column and Line mix Chart
                            </h4>
                            <span class="pull-right">
                                    <i class="fa fa-fw fa-chevron-up clickable"></i>
                                    <i class="fa fa-fw fa-times removepanel clickable"></i>
                                </span>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="amchart1" id="chart1"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <!-- Basic charts strats here-->
                    <div class="panel panel-warning">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <i class="fa fa-fw fa-line-chart"></i> Column chart with images on top
                            </h4>
                            <span class="pull-right">
                                    <i class="fa fa-fw fa-chevron-up clickable"></i>
                                    <i class="fa fa-fw fa-times removepanel clickable"></i>
                                </span>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="amchart1" id="chart3"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- row -->
            <div class="row">
                <div class="col-md-12">
                    <!-- Basic charts strats here-->
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <i class="fa fa-fw fa-area-chart"></i> 3D Stacked Column Chart
                            </h4>
                            <span class="pull-right">
                                    <i class="fa fa-fw fa-chevron-up clickable"></i>
                                    <i class="fa fa-fw fa-times removepanel clickable"></i>
                                </span>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="amchart1" id="chart2"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- row -->
            <div class="row">
                <div class="col-md-6">
                    <!-- Basic charts strats here-->
                    <div class="panel panel-success">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <i class="fa fa-fw fa-bar-chart"></i> Duration on Value Axis Chart
                            </h4>
                            <span class="pull-right">
                                    <i class="fa fa-fw fa-chevron-up clickable"></i>
                                    <i class="fa fa-fw fa-times removepanel clickable"></i>
                                </span>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="amchart1" id="chart4"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <!-- Basic charts strats here-->
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <i class="fa fa-fw fa-line-chart"></i> Reversed Value Axis Chart
                            </h4>
                            <span class="pull-right">
                                    <i class="fa fa-fw fa-chevron-up clickable"></i>
                                    <i class="fa fa-fw fa-times removepanel clickable"></i>
                                </span>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="amchart1" id="chart5"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- row -->
        @include('layouts.right_sidebar')
            <!-- row -->
        </section>
@stop

{{-- page level scripts --}}
@section('footer_scripts')
<!-- begining of page level js -->
<script type="text/javascript" src="{{asset('assets/vendors/amcharts/js/amcharts.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/amcharts/js/serial.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/amcharts/js/export.min.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/amcharts/js/light.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/js/custom_js/amcharts.js')}}"></script>
<!-- end of page level js -->
@stop
