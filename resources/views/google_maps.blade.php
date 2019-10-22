@extends('layouts/default')

    {{-- Page title --}}
    @section('title')
        Google Maps
    @parent
@stop

{{-- page level styles --}}
@section('header_styles')
    <!--page level css -->
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/gmaps_cust.css')}}">
    <!--end of page level css-->
@stop

{{-- Page content --}}
@section('content')
    <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                Google Maps
            </h1>
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
                    Google Maps
                </li>
            </ol>
        </section>
        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-lg-6">
                    <div class="panel panel-success">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <i class="fa fa-fw fa-map-marker"></i> Basic
                            </h4>
                            <span class="pull-right">
                                <i class="fa fa-fw fa-chevron-up clickable"></i>
                                    <i class="fa fa-fw fa-times removepanel clickable"></i>
                                </span>
                        </div>
                        <div class="panel-body">
                            <div id="gmap-top" class="gmap"></div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <!-- Basic charts strats here-->
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <i class="fa fa-fw fa-map-marker"></i> Terrain
                            </h4>
                            <span class="pull-right">
                                <i class="fa fa-fw fa-chevron-up clickable"></i>
                                    <i class="fa fa-fw fa-times removepanel clickable"></i>
                                </span>
                        </div>
                        <div class="panel-body">
                            <div id="gmap-terrain" class="gmap"></div>
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
                                <i class="fa fa-fw fa-map-marker"></i> Satellite
                            </h4>
                            <span class="pull-right">
                                <i class="fa fa-fw fa-chevron-up clickable"></i>
                                    <i class="fa fa-fw fa-times removepanel clickable"></i>
                                </span>
                        </div>
                        <div class="panel-body">
                            <div id="gmap-satellite" class="gmap"></div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <!-- Basic charts strats here-->
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <i class="fa fa-fw fa-map-marker"></i> Markers
                            </h4>
                            <span class="pull-right">
                                <i class="fa fa-fw fa-chevron-up clickable"></i>
                                    <i class="fa fa-fw fa-times removepanel clickable"></i>
                                </span>
                        </div>
                        <div class="panel-body">
                            <div id="gmap-markers" class="gmap"></div>
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
                                <i class="fa fa-fw fa-map-marker"></i> Styled Maps
                            </h4>
                            <span class="pull-right">
                                <i class="fa fa-fw fa-chevron-up clickable"></i>
                                <i class="fa fa-fw fa-times removepanel clickable"></i>
                            </span>
                        </div>
                        <div class="panel-body">
                            <div id="gmap-styled" class="gmap"></div>
                        </div>
                    </div>
                </div>
                <!-- row -->
                <div class="col-lg-6">
                    <!-- Basic charts strats here-->
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <i class="fa fa-fw fa-map-marker"></i> Maps Types
                            </h4>
                            <span class="pull-right">
                                <i class="fa fa-fw fa-chevron-up clickable"></i>
                                    <i class="fa fa-fw fa-times removepanel clickable"></i>
                                </span>
                        </div>
                        <div class="panel-body">
                            <div id="gmap-types" class="gmap"></div>
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
<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=true"></script>
<script type="text/javascript" src="{{asset('assets/vendors/gmaps/js/gmaps.min.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/js/custom_js/custommaps.js')}}"></script>
    <!-- end of page level js -->
@stop
