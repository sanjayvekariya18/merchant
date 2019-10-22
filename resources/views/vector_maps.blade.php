@extends('layouts/default')

{{-- Page title --}}
@section('title')
    Vector Maps
    @parent
@stop

{{-- page level styles --}}
@section('header_styles')
    <!-- page level css-->
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/bower-jvectormap/css/jquery-jvectormap-1.2.2.css')}}" media="screen" />
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/jqvmap/css/jqvmap.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom_css/custom_map.css')}}"/>
    <!-- end of page level css-->
@stop

{{-- Page content --}}
@section('content')
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>Vector Maps</h1>
            <ol class="breadcrumb">
                <li>
                    <a href="index">
                        <i class="fa fa-fw fa-home"></i> Dashboard
                    </a>
                </li>
                <li>
                    <a href="#"> Maps</a>
                </li>
                <li class="active">
                    Vector Maps
                </li>
            </ol>
        </section>
        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-lg-6">
                    <!-- Basic charts strats here-->
                    <div class="panel panel-success">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <i class="fa fa-fw fa-globe"></i> World
                            </h4>
                            <span class="pull-right">
                                <i class="fa fa-fw fa-chevron-up clickable"></i>
                                    <i class="fa fa-fw fa-times removepanel clickable"></i>
                                </span>
                        </div>
                        <div class="panel-body">
                            <div class="map_size" id="vmapworld"></div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <!-- Basic charts strats here-->
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <i class="fa fa-fw fa-globe"></i> Asia
                            </h4>
                            <span class="pull-right">
                                     <i class="fa fa-fw fa-chevron-up clickable"></i>
                                    <i class="fa fa-fw fa-times removepanel clickable"></i>
                                </span>
                        </div>
                        <div class="panel-body">
                            <div id="vmapasian" class="map_size"></div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- row -->
            <div class="row">
                <div class="col-lg-6">
                    <!-- Basic charts strats here-->
                    <div class="panel panel-warning">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <i class="fa fa-fw fa-globe"></i> Europe
                            </h4>
                            <span class="pull-right">
                                     <i class="fa fa-fw fa-chevron-up clickable"></i>
                                    <i class="fa fa-fw fa-times removepanel clickable"></i>
                                </span>
                        </div>
                        <div class="panel-body">
                            <div id="vmapeurope" class="map_size"></div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <!-- Basic charts strats here-->
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <i class="fa fa-fw fa-globe"></i> North America
                            </h4>
                            <span class="pull-right">
                                     <i class="fa fa-fw fa-chevron-up clickable"></i>
                                    <i class="fa fa-fw fa-times removepanel clickable"></i>
                                </span>
                        </div>
                        <div class="panel-body">
                            <div id="vmapnamerica" class="map_size"></div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- row -->
            <div class="row">
                <div class="col-lg-6">
                    <!-- Basic charts strats here-->
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <i class="fa fa-fw fa-globe"></i> Germany
                            </h4>
                            <span class="pull-right">
                                     <i class="fa fa-fw fa-chevron-up clickable"></i>
                                    <i class="fa fa-fw fa-times removepanel clickable"></i>
                                </span>
                        </div>
                        <div class="panel-body">
                            <div id="vmapgermany" class="map_size"></div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <!-- Basic charts strats here-->
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <i class="fa fa-fw fa-globe"></i> Russia
                            </h4>
                            <span class="pull-right">
                                     <i class="fa fa-fw fa-chevron-up clickable"></i>
                                    <i class="fa fa-fw fa-times removepanel clickable"></i>
                                </span>
                        </div>
                        <div class="panel-body">
                            <div id="vmaprussia" class="map_size"></div>
                        </div>
                    </div>
                </div>

            </div>
            <!-- row -->
            @include('layouts.right_sidebar')
        </section>
@stop

{{-- page level scripts --}}
@section('footer_scripts')
<!-- begining of page level js -->
<script type="text/javascript" src="{{asset('assets/vendors/jqvmap/js/jquery.vmap.js')}}" ></script>
<script type="text/javascript" src="{{asset('assets/vendors/jqvmap/js/jquery.vmap.world.js')}}" ></script>
<script type="text/javascript" src="{{asset('assets/vendors/jqvmap/js/jquery.vmap.europe.js')}}" ></script>
<script type="text/javascript" src="{{asset('assets/vendors/jqvmap/js/jquery.vmap.germany.js')}}" ></script>
<script type="text/javascript" src="{{asset('assets/vendors/jqvmap/js/jquery.vmap.russia.js')}}" ></script>
<script type="text/javascript" src="{{asset('assets/vendors/jqvmap/js/jquery.vmap.asia.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/jqvmap/js/jquery.vmap.north-america.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/jqvmap/js/jquery.vmap.sampledata.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/js/custom_js/vector-maps.js')}}"></script>
<!-- end of page level js -->
@stop