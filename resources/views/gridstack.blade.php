@extends('layouts/default')

    {{-- Page title --}}
    @section('title')
        Grid Stack
    @parent
@stop

{{-- page level styles --}}
@section('header_styles')
    <!--page level css -->
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/gridstack/css/gridstack.min.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom_css/gridstack.css')}}"/>
    <!--end of page level css-->
@stop

{{-- Page content --}}
@section('content')
    <!-- Content Header (Page header) -->
        <section class="content-header">
            <!--section starts-->
            <h1>
                Grid Stack
            </h1>
            <ol class="breadcrumb">
                <li>
                    <a href="index">
                        <i class="fa fa-fw fa-home"></i> Dashboard
                    </a>
                </li>
                <li>
                    <a href="#">
                        UI Features
                    </a>
                </li>
                <li class="active">
                    Grid Stack
                </li>
            </ol>
        </section>
        <section class="content">
            <!--main content-->
            <div class="row">
                <div class="col-md-12 ">
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                <i class="fa fa-fw fa-th"></i> Gridstack
                            </h3>
                            <span class="pull-right">
                                    <i class="fa fa-fw fa-chevron-up clickable"></i>
                                    <i class="fa fa-fw fa-times removepanel clickable"></i>
                                </span>
                        </div>
                        <div class="panel-body" id="slim1">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="grid-stack" data-gs-width="12" data-gs-animate="yes">
                                        <div class="grid-stack-item" data-gs-x="0" data-gs-y="0" data-gs-width="4"
                                             data-gs-height="2">
                                            <div class="grid-stack-item-content">1</div>
                                        </div>
                                        <div class="grid-stack-item" data-gs-x="4" data-gs-y="0" data-gs-width="4"
                                             data-gs-height="4">
                                            <div class="grid-stack-item-content">2</div>
                                        </div>
                                        <div class="grid-stack-item" data-gs-x="8" data-gs-y="0" data-gs-width="2"
                                             data-gs-height="2" data-gs-min-width="2" data-gs-no-resize="yes">
                                            <div class="grid-stack-item-content"><span class="fa fa-hand-o-up"></span>
                                                Drag me!
                                            </div>
                                        </div>
                                        <div class="grid-stack-item" data-gs-x="10" data-gs-y="0" data-gs-width="2"
                                             data-gs-height="2">
                                            <div class="grid-stack-item-content">4</div>
                                        </div>
                                        <div class="grid-stack-item" data-gs-x="0" data-gs-y="2" data-gs-width="2"
                                             data-gs-height="2">
                                            <div class="grid-stack-item-content">5</div>
                                        </div>
                                        <div class="grid-stack-item" data-gs-x="2" data-gs-y="2" data-gs-width="2"
                                             data-gs-height="4">
                                            <div class="grid-stack-item-content">6</div>
                                        </div>
                                        <div class="grid-stack-item" data-gs-x="8" data-gs-y="2" data-gs-width="4"
                                             data-gs-height="2">
                                            <div class="grid-stack-item-content">7</div>
                                        </div>
                                        <div class="grid-stack-item" data-gs-x="0" data-gs-y="4" data-gs-width="2"
                                             data-gs-height="2">
                                            <div class="grid-stack-item-content">8</div>
                                        </div>
                                        <div class="grid-stack-item" data-gs-x="4" data-gs-y="4" data-gs-width="4"
                                             data-gs-height="2">
                                            <div class="grid-stack-item-content">9</div>
                                        </div>
                                        <div class="grid-stack-item" data-gs-x="8" data-gs-y="4" data-gs-width="2"
                                             data-gs-height="2">
                                            <div class="grid-stack-item-content">10</div>
                                        </div>
                                        <div class="grid-stack-item" data-gs-x="10" data-gs-y="4" data-gs-width="2"
                                             data-gs-height="2">
                                            <div class="grid-stack-item-content">11</div>
                                        </div>
                                    </div>
                                </div>
                                <!-- end row -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--main content ends-->
            <!-- row -->
        @include('layouts.right_sidebar')
        <!-- right side bar end -->
        </section>
@stop

{{-- page level scripts --}}
@section('footer_scripts')
    <!-- begining of page level js -->
<script type="text/javascript" src="{{asset('assets/vendors/lodash/js/lodash.min.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/gridstack/js/gridstack.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/js/custom_js/gridstack.js')}}"></script>
    <!-- end of page level js -->
@stop
