@extends('layouts/default')

    {{-- Page title --}}
    @section('title')
        Chartist Charts
    @parent
@stop

{{-- page level styles --}}
@section('header_styles')
    <!--page level css -->
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/chartist/css/chartist.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/chartist.css')}}">
    <!--end of page level css-->
@stop

{{-- Page content --}}
@section('content')
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                Chartist Charts
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
                    Chartist Charts
                </li>
            </ol>
        </section>
        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-lg-6">
                    <!-- Basic charts strats here-->
                    <div class="panel panel-info">
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
                            <div>
                                <div class="ct-chart6 ct-perfect-fourth"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <!-- Basic charts strats here-->
                    <div class="panel panel-success">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <i class="fa fa-fw fa-bar-chart-o"></i> Responsive Bar Chart
                                <small>(Overlapping Bars in Mobile)</small>
                            </h4>
                            <span class="pull-right">
                                    <i class="fa fa-fw fa-chevron-up clickable"></i>
                                    <i class="fa fa-fw fa-times removepanel clickable"></i>
                                </span>
                        </div>
                        <div class="panel-body">
                            <div>
                                <div class="ct-chart1 ct-perfect-fourth"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- row -->
            <div class="row">
                <div class="col-lg-6">
                    <!-- Basic charts strats here-->
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <i class="fa fa-fw fa-area-chart"></i> Series Chart
                            </h4>
                            <span class="pull-right">
                                    <i class="fa fa-fw fa-chevron-up clickable"></i>
                                    <i class="fa fa-fw fa-times removepanel clickable"></i>
                                </span>
                        </div>
                        <div class="panel-body">
                            <div>
                                <div class="ct-chart2 ct-perfect-fourth"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <!-- Basic charts strats here-->
                    <div class="panel panel-warning">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <i class="fa fa-fw fa-bar-chart-o"></i> SVG Animations Chart
                            </h4>
                            <span class="pull-right">
                                    <i class="fa fa-fw fa-chevron-up clickable"></i>
                                    <i class="fa fa-fw fa-times removepanel clickable"></i>
                                </span>
                        </div>
                        <div class="panel-body">
                            <div>
                                <div class="ct-chart3 ct-perfect-fourth"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- row -->
            <div class="row">
                <div class="col-lg-6">
                    <!-- Basic charts strats here-->
                    <div class="panel panel-danger">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <i class="fa fa-fw fa-line-chart"></i> XY Plot Chart
                            </h4>
                            <span class="pull-right">
                                    <i class="fa fa-fw fa-chevron-up clickable"></i>
                                    <i class="fa fa-fw fa-times removepanel clickable"></i>
                                </span>
                        </div>
                        <div class="panel-body">
                            <div>
                                <div class="ct-chart4 ct-perfect-fourth"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <!-- Basic charts strats here-->
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <i class="fa fa-fw fa-bar-chart-o"></i> Fixed Scale Axis Chart
                            </h4>
                            <span class="pull-right">
                                    <i class="fa fa-fw fa-chevron-up clickable"></i>
                                    <i class="fa fa-fw fa-times removepanel clickable"></i>
                                </span>
                        </div>
                        <div class="panel-body">
                            <div>
                                <div class="ct-chart5 ct-perfect-fourth"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6">
                    <!-- Basic charts strats here-->
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <i class="fa fa-fw fa-bar-chart-o"></i> Advanced SMIL Animations Chart
                            </h4>
                            <span class="pull-right">
                                    <i class="fa fa-fw fa-chevron-up clickable"></i>
                                    <i class="fa fa-fw fa-times removepanel clickable"></i>
                                </span>
                        </div>
                        <div class="panel-body">
                            <div>
                                <div class="ct-chart ct-perfect-fourth"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <!-- Basic charts strats here-->
                    <div class="panel panel-warning">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <i class="fa fa-fw fa-area-chart"></i> SVG Path Animation Chart
                            </h4>
                            <span class="pull-right">
                                    <i class="fa fa-fw fa-chevron-up clickable"></i>
                                    <i class="fa fa-fw fa-times removepanel clickable"></i>
                                </span>
                        </div>
                        <div class="panel-body">
                            <div>
                                <div class="ct-chart7 ct-perfect-fourth"></div>
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
<script type="text/javascript" src="{{asset('assets/vendors/chartist/js/chartist.min.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/js/custom_js/chartist.js')}}" ></script>
<!-- end of page level js -->
@stop