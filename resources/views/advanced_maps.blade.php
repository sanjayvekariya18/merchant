@extends('layouts/default')

    {{-- Page title --}}
    @section('title')
        Advanced Maps
        @parent
    @stop

    {{-- page level styles --}}
    @section('header_styles')

    <!--page level css -->
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/leaflet/css/leaflet.css')}}"/>
    <!-- end of page level css-->
    @stop

{{-- Page content --}}
@section('content')
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                Advanced Maps
            </h1>
            <ol class="breadcrumb">
                <li>
                    <a href="index">
                        <i class="fa fa-fw fa-home"></i> Dashboard
                    </a>
                </li>
                <li><a href="#">Maps</a></li>
                <li class="active">
                        Advanced Maps
                </li>
            </ol>
        </section>
        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-success">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <i class="fa fa-fw fa-location-arrow"></i> Basic
                            </h4>
                            <span class="pull-right">
                                <i class="fa fa-fw fa-chevron-up clickable"></i>
                                    <i class="fa fa-fw fa-times removepanel clickable"></i>
                                </span>
                        </div>
                        <div class="panel-body" style="padding:10px !important;">
                            <div id="advanced_map" class="gmap"></div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- row -->
        @include('layouts.right_sidebar')
            <!-- /.modal ends here -->
        </section>
@stop

{{-- page level scripts --}}
@section('footer_scripts')
    <!-- begining of page level js -->
<script type="text/javascript" src="{{asset('assets/vendors/leaflet/js/leaflet-src.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/js/custom_js/advanced_maps.js')}}"></script>
<!-- end of page level js -->
@stop