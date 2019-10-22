<?php use App\Http\Traits\PermissionTrait; ?>
@extends('layouts/default')

    {{-- Page title --}}
    @section('title')
        Dashboard
    @parent
@stop

{{-- page level styles --}}
@section('header_styles')
    <!--page level css -->
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/toastr/css/toastr.min.css')}}" />
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/chartist/css/chartist.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/nvd3/css/nv.d3.min.css')}}" >
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/morrisjs/morris.css')}}" />
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/awesomebootstrapcheckbox/css/awesome-bootstrap-checkbox.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/bower-jvectormap/css/jquery-jvectormap-1.2.2.css')}}" />
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom_css/dashboard1.css')}}" />

    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/animate/animate.min.css')}}">
    <link href="{{asset('assets/css/custom_css/timeline.css')}}" rel="stylesheet"/>

    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/gmaps_cust.css')}}">
    <!--end of page level css-->
@stop

{{-- Page content --}}
@section('content')
    <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="index-header">
                <div class="inner-bg">
                    <div class="header-section">
                        <input type="hidden" id="requestUrl" value='{!!url("home")!!}'' >
                        <input type='hidden' id='_token' value='{{Session::token()}}'>
                        <div class="row">
                            <!-- <div class="col-md-4 col-lg-5 hidden-xs hidden-sm">
                                <h2>Welcome <span class="hidden-md">To Dashboard</span></h2>
                            </div> -->
                            <div class="col-md-12 col-lg-12">
                                <div class="row text-center">
                                    <div class="col-xs-2 col-sm-2">
                                        <h2>
                                            <strong>{!!$hase_approve_pending!!}</strong>
                                            <br>
                                            <small>
                                                <i class="fa fa fa-clock-o fa-2x"></i>
                                                Pending
                                            </small>
                                        </h2>
                                    </div>
                                    <div class="col-xs-2 col-sm-2">
                                        <h2>
                                            <strong>{!!$hase_approve_accept!!}</strong>
                                            <br>
                                            <small>
                                                <i class="fa fa fa-check fa-2x"></i>
                                                Accept 
                                            </small>
                                        </h2>
                                    </div>
                                    <div class="col-xs-2 col-sm-2">
                                        <h2>
                                            <strong>{!!$hase_approve_reject!!}</strong>
                                            <br>
                                            <small>
                                                <i class="fa fa fa-times fa-2x"></i>
                                                Reject
                                            </small>
                                        </h2>
                                    </div>
                                    <div class="col-xs-2 col-sm-2">
                                        <h2>
                                            <strong>{!!$hase_merchant_count!!}</strong>
                                            <br>
                                            <small>
                                                <i class="fa fa fa-home fa-2x"></i>
                                                Merchants 
                                            </small>
                                        </h2>
                                    </div>
                                    <div class="col-xs-2 col-sm-2">
                                        <h2 class="animation-hatch">
                                            <strong>{!!$hase_location_count!!}</strong>
                                            <br>
                                            <small>
                                                <i class="fa fa-map-marker fa-2x"></i>
                                                Locations 
                                            </small>
                                        </h2>
                                    </div>
                                    <div class="col-xs-2 col-sm-2">
                                        <h2 class="animation-hatch">
                                            <strong>{!!$hase_reservations_count!!}</strong>
                                            <br>
                                            <small>
                                                <i class="fa fa-calendar-o fa-2x"></i>
                                                Reservations
                                            </small>
                                        </h2>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <br/><br/><br/>
        <section class="content">
        <!--main content-->
            <div class="row">
                <div class="col-lg-12">
                    <div id="gmap-styled" class="gmap"></div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 timeline_panel">
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                <i class="fa fa-fw fa-clock-o"></i> Activity Log
                            </h3>
                        </div>
                        <div class="panel-body">
                            <!--timeline-->
                            <div>
                                <ul class="timeline">
                                    @foreach($hase_activities as $key => $hase_activity)
                                        <?php $class = ($key % 2 != 0)?"timeline-inverted":"" ?>
                                        <li class="<?php echo $class ?>">
                                            <div class="timeline-badge primary"></div>
                                            <div class="timeline-panel" style="display:inline-block;">
                                                <div class="timeline-heading">
                                                    <h4 class="timeline-title">
                                                    {!! $hase_activity->message !!} from {!! $hase_activity->ip_address !!} <?= ($hase_activity->user_city !="") ? "( ".$hase_activity->user_city." )" : ""?>
                                                    </h4>
                                                    <span class="text-danger">
                                                        <span style="font-size:15px;font-weight: bold;">
                                                            <?php
                                                            $user_timezone = ($hase_activity->user_timezone > 0) ? '+'.$hase_activity->user_timezone : $hase_activity->user_timezone;

                                                            echo date('m/d/Y h:i A',strtotime($hase_activity->date_added))." ".Config::get('app.timezone').($hase_activity->user_timezone !="" ? " [".$hase_activity->user_time. " ".$user_timezone.']' : ""); 

                                                            ?>
                                                        </span>
                                                        <span style="font-size:11px">
                                                            <?php 
                                                                echo PermissionTrait::humanTiming($hase_activity->date_added)
                                                            ?>
                                                            ago
                                                        </span>                                                        
                                                    </span>
                                                </div>
                                            </div>
                                        </li>
                                    @endforeach 
                                </ul>
                            </div>
                            <!--timeline ends-->
                        </div>
                    </div>
                </div>
            </div>
            <!--main content ends-->
        </section>    
        <!--section ends-->
        @include('layouts.right_sidebar')
        <!-- right side bar end -->
        </section>
@stop

{{-- page level scripts --}}
@section('footer_scripts')
    <!-- begining of page level js -->
<div id="qn"></div>
<!-- begining of page level js -->
<script src="{{asset('assets/js/backstretch.js')}}"></script>

<script type="text/javascript" src="{{asset('assets/js/dashboard1.js')}}"></script>

<script type="text/javascript" src="{{asset('assets/vendors/wow/js/wow.min.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/js/custom_js/time_line.js')}}"></script>

<script type="text/javascript" src="http://maps.google.com/maps/api/js?key=AIzaSyB0rfVC02005ehz3RV7cwQfKm4qwBvzghE&sensor=true"></script>

<script type="text/javascript" src="{{asset('assets/vendors/gmaps/js/gmaps.min.js')}}"></script>

<script type="text/javascript" src="{{asset('assets/js/custom_js/dashboardmaps.js')}}"></script>
    <!-- end of page level js -->
@stop