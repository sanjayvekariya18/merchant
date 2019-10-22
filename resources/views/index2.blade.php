@extends('layouts/default')

{{-- Page title --}}
@section('title')
    Core + Admin Template
    @parent
@stop

{{-- page level styles --}}
@section('header_styles')
    <!--page level css -->
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/toastr/css/toastr.min.css')}}" />
    <!--weathericons-->
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/weathericon/css/weather-icons.min.css')}}" />
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/c3/c3.min.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/nvd3/css/nv.d3.min.css')}}" />
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom_css/dashboard2.css')}}" />
    <!--end of page level css-->
@stop

{{-- Page content --}}
@section('content')
    <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="row">
                <div class="col-md-6 col-xs-6">
                    <div class="header-data">
                        <h1>Dashboard</h1>
                        <p>Welcome To Core Plus</p>
                    </div>
                </div>
                <div class="col-md-6 col-xs-6">
                    <div class="header-charts">
                        <div class="sparkline-chart pull-right hidden-xs">
                            <div class="number" id="sparkline_line"></div>
                            <small class="server-title">Server Load:</small>
                        </div>
                        <div class="sparkline-chart pull-right">
                            <div class="number" id="sparkline_bar"></div>
                            <small class="sales-title">Daily Sales:</small>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-lg-8 col-md-12 col-sm-12 col-xs-12">
                    <div class="panel">
                        <div class="panel-heading">
                            <h3 class="heading_text panel-title">Sales</h3>
                            <span class="pull-right line-bar-charts">
                                <button class="btn btn-sm btn-link chart_switch" data-chart="bar">Bar Chart</button>
                                <button class="btn btn-sm btn-default chart_switch" data-chart="line">Line Chart
                                </button>
                            </span>
                        </div>
                        <div class="panel-body">
                            <div id="sales-line-bar" style="height:280px"></div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12">
                    <div class="panel weather-widget">
                        <div class="row weather-data">
                            <div class="col-md-12 temperature">
                                <h2>19<sup><sup>o</sup><sub>c</sub></sup></h2>
                                <p class="location"><i class="fa fa-map-marker text-default" aria-hidden="true"></i>
                                    Hong Kong, China</p>
                                <p>Showers till tomorrow morning</p>
                                <i class="wi wi-night-rain icon"></i>
                            </div>
                        </div>
                        <div class="weather-footer">
                            <div class="text-center">
                                <div class="col-lg-3 col-xs-2 popup">
                                    <h5>MON</h5>
                                    <i class="wi wi-day-lightning"></i>
                                    <p>21<sup>o<sub>c</sub></sup></p>
                                </div>
                                <div class="col-lg-3 col-xs-2 popup">
                                    <h5>TUE</h5>
                                    <i class="wi wi-cloudy"></i>
                                    <p>28<sup>o<sub>c</sub></sup></p>
                                </div>
                                <div class="col-lg-3 col-xs-2 popup">
                                    <h5>WED</h5>
                                    <i class="wi wi-night-rain-mix"></i>
                                    <p>26<sup>o<sub>c</sub></sup></p>
                                </div>
                                <div class="col-lg-3 col-xs-2 popup">
                                    <h5>THU</h5>
                                    <i class="wi wi-day-sunny"></i>
                                    <p>31<sup>o<sub>c</sub></sup></p>
                                </div>
                                <div class="col-xs-2 hidden-lg popup">
                                    <h5>FRI</h5>
                                    <i class="wi wi-day-lightning"></i>
                                    <p>24<sup>o<sub>c</sub></sup></p>
                                </div>
                                <div class="col-xs-2 hidden-lg popup">
                                    <h5>SAT</h5>
                                    <i class="wi wi-night-alt-snow"></i>
                                    <p>25<sup>o<sub>c</sub></sup></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12">
                    <div class="panel">
                        <div class="panel-heading">
                            <h3 class="panel-title">Live Views</h3>
                        </div>
                        <div class="panel-body real-time">
                            <div class="total-visits">
                                <span>238</span>
                                <small>visitors On-line</small>
                                <div class="progress">
                                    <div role="progressbar" aria-valuenow="17" aria-valuemin="0" aria-valuemax="100"
                                         style="width: 23%;" class="progress-bar progress-bar-info">
                                    </div>
                                </div>
                            </div>
                            <div class="visit-source">
                                <div class="visit-count pull-left">
                                    <small>82 <span>Direct</span></small>
                                    <div class="progress">
                                        <div role="progressbar" aria-valuenow="17" aria-valuemin="0" aria-valuemax="100"
                                             style="width: 29.1%;" class="progress-bar progress-bar-primary">
                                        </div>
                                    </div>
                                </div>
                                <div class="visit-count pull-left">
                                    <small>156 <span>Search</span></small>
                                    <div class="progress">
                                        <div role="progressbar" aria-valuenow="17" aria-valuemin="0" aria-valuemax="100"
                                             style="width: 70.9%;" class="progress-bar progress-bar-danger">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="">
                                <div class="visitors-os pull-left text-center">
                                    <p>43.4%</p>
                                    <span>Windows</span>
                                </div>
                                <div class="visitors-os pull-left text-center">
                                    <p>32.4%</p>
                                    <span>Mac Os</span>
                                </div>
                                <div class="visitors-os pull-left text-center">
                                    <p>34.2%</p>
                                    <span>Others</span>
                                </div>
                            </div>
                            <div id='chart-live' class='chart half with-transitions'>
                                <svg></svg>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12">
                    <div class="panel panel-widget">
                        <div class="panel-heading">
                            <h3 class="panel-title">Daily Traffic</h3>
                        </div>
                        <div class="panel-body">
                            <ul class="basic-list">
                                <li><img src="{{asset('assets/img/chrome.png')}}" alt="chrome"> Chrome
                                    <span class="right label label-success pull-right">42.8%</span></li>
                                <li><img src="{{asset('assets/img/firefox.png')}}" alt="firefox">Firefox
                                    <span class="right label label-danger pull-right">16.9%</span></li>
                                <li><img src="{{asset('assets/img/safari.png')}}" alt="safari">Safari
                                    <span class="right label label-primary pull-right">15.5%</span></li>
                                <li><img src="{{asset('assets/img/opera.png')}}" alt="opera">Opera
                                    <span class="right label label-info pull-right">11.8%</span></li>
                                <li><img src="{{asset('assets/img/Ie.png')}}" alt="Internet Explorer">IE
                                    <span class="right label label-danger pull-right">3.2%</span></li>
                                <li><img src="{{asset('assets/img/mobile.png')}}" alt="mobile">Mobile
                                    <span class="right label label-warning pull-right">3%</span></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12">
                    <div class="panel">
                        <div class="panel-heading">
                            <h3 class="panel-title">Recent Activities</h3>
                        </div>
                        <div class="panel-body">
                            <ul class="auto-update">
                                <li>
                                    <div class="activity">
                                        <div class="activity-image pull-left">
                                            <img src="{{asset('assets/img/authors/avatar.jpg')}}" alt="profile-image"
                                                 class="img-circle media-image">
                                        </div>
                                        <div class="activity-content pull-left">
                                            <h5 class="heading text-primary">Praesent ornare nisl lorem</h5>
                                            <p class="text-muted">
                                                <small>7 min ago</small>
                                            </p>
                                            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit.</p>
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <div class="activity">
                                        <div class="activity-image pull-left">
                                            <img src="{{asset('assets/img/authors/avatar2.jpg')}}" alt="profile-image"
                                                 class="img-circle media-image">
                                        </div>
                                        <div class="activity-content pull-left">
                                            <h5 class="heading text-primary">Nunc ultrices</h5>
                                            <p class="text-muted">
                                                <small>10 min ago</small>
                                            </p>
                                            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit.</p>
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <div class="activity">
                                        <div class="activity-image pull-left">
                                            <img src="{{asset('assets/img/authors/avatar3.jpg')}}" alt="profile-image"
                                                 class="img-circle media-image">
                                        </div>
                                        <div class="activity-content pull-left">
                                            <h5 class="heading text-primary">Praesent ornare nisl</h5>
                                            <p class="text-muted">
                                                <small>Yesterday at 10:20pm</small>
                                            </p>
                                            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit.</p>
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <div class="activity">
                                        <div class="activity-image pull-left">
                                            <img src="{{asset('assets/img/authors/avatar4.jpg')}}" alt="profile-image"
                                                 class="img-circle media-image">
                                        </div>
                                        <div class="activity-content pull-left">
                                            <h5 class="heading text-primary">Nunc ultrices tortor eu</h5>
                                            <p class="text-muted">
                                                <small>2 days ago at 1:20pm</small>
                                            </p>
                                            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit.</p>
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <div class="activity">
                                        <div class="activity-image pull-left">
                                            <img src="{{asset('assets/img/authors/avatar5.jpg')}}" alt="profile-image"
                                                 class="img-circle media-image">
                                        </div>
                                        <div class="activity-content pull-left">
                                            <h5 class="heading text-primary">Praesent ornare nisl</h5>
                                            <p class="text-muted">
                                                <small>Just now</small>
                                            </p>
                                            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit.</p>
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <div class="activity">
                                        <div class="activity-image pull-left">
                                            <img src="{{asset('assets/img/authors/avatar7.jpg')}}" alt="profile-image"
                                                 class="img-circle media-image">
                                        </div>
                                        <div class="activity-content pull-left">
                                            <h5 class="heading text-primary">Nunc ultrices tortor eu massa</h5>
                                            <p class="text-muted">
                                                <small>2 min ago</small>
                                            </p>
                                            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit.</p>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6 col-sm-6 col-xs-12">
                    <div class="panel">
                        <div class="panel-heading">
                            <h3 class="panel-title">Server Load</h3>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="load-measure">
                                    <h5>CPU Load:</h5>
                                    <canvas id="cpu-load" height="300" width="300"></canvas>
                                </div>
                                <div class="load-measure">
                                    <h5>Disk Space Used:</h5>
                                    <canvas id="space-used" height="300" width="300"></canvas>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="col-lg-6 col-sm-6 col-xs-12">
                    <div class="panel panel-widget">
                        <div class="panel-heading">
                            <h3 class="panel-title">To Do List</h3>
                        </div>
                        <div class="panel-body tasks_list">
                            <form class="row list_of_items">
                                <div class="todolist_list showactions">
                                    <div class="col-md-9 col-sm-9 col-xs-8 nopadmar custom_textbox1">
                                        <div class="todoitemcheck checkbox checkbox-info">
                                            <input type="checkbox" class="striked styled">
                                            <label>
                                            </label>
                                        </div>
                                        <div class="todotext  todoitemjs"> The Passage has a strong culture of volunteering
                                        </div>
                                    </div>
                                    <div class="col-md-2  col-sm-2 col-xs-3 todoitembtns">
                                        <a href="#" class="tododelete redcolor pull-right">
                                            <i class="fa fa-times showbtns" aria-hidden="true"></i>
                                        </a>
                                        <span class="striks pull-right showbtns">|</span>
                                        <a href="#" class="todoedit pull-right">
                                            <i class="fa fa-pencil showbtns" aria-hidden="true"></i>
                                        </a>
                                        <a href="#" class="un-do pull-right" hidden>
                                            <i class="fa fa-repeat showbtns" aria-hidden="true"></i>
                                        </a>
                                    </div>
                                </div>
                                <div class="todolist_list showactions">
                                    <div class="col-md-9 col-sm-9 col-xs-8 nopadmar custom_textbox1">
                                        <div class="todoitemcheck checkbox checkbox-info">
                                            <input type="checkbox" class="striked">
                                            <label>
                                            </label>
                                        </div>
                                        <div class="todotext  todoitemjs">There are many variations of passages here
                                        </div>
                                    </div>
                                    <div class="col-md-2  col-sm-2 col-xs-3 todoitembtns">
                                        <a href="#" class="tododelete redcolor pull-right">
                                            <i class="fa fa-times showbtns" aria-hidden="true"></i>
                                        </a>
                                        <span class="striks pull-right showbtns">|</span>
                                        <a href="#" class="todoedit pull-right">
                                            <i class="fa fa-pencil showbtns" aria-hidden="true"></i>
                                        </a>
                                        <a href="#" class="un-do pull-right" hidden>
                                            <i class="fa fa-repeat showbtns" aria-hidden="true"></i>
                                        </a>
                                    </div>
                                </div>
                                <div class="todolist_list showactions">
                                    <div class="col-md-9 col-sm-9 col-xs-8 nopadmar custom_textbox1">
                                        <div class="todoitemcheck checkbox checkbox-info">
                                            <input type="checkbox" class="striked">
                                            <label>
                                            </label>
                                        </div>
                                        <div class="todotext  todoitemjs">It has roots in a piece of classical Latin
                                        </div>
                                    </div>
                                    <div class="col-md-2  col-sm-2 col-xs-3 todoitembtns">
                                        <a href="#" class="tododelete redcolor pull-right">
                                            <i class="fa fa-times showbtns" aria-hidden="true"></i>
                                        </a>
                                        <span class="striks pull-right showbtns">|</span>
                                        <a href="#" class="todoedit pull-right">
                                            <i class="fa fa-pencil showbtns" aria-hidden="true"></i>
                                        </a>
                                        <a href="#" class="un-do pull-right" hidden>
                                            <i class="fa fa-repeat showbtns" aria-hidden="true"></i>
                                        </a>
                                    </div>
                                </div>
                                <div class="todolist_list showactions">
                                    <div class="col-md-9 col-sm-9 col-xs-8 nopadmar custom_textbox1">
                                        <div class="todoitemcheck checkbox checkbox-info">
                                            <input type="checkbox" class="striked">
                                            <label>
                                            </label>
                                        </div>
                                        <div class="todotext  todoitemjs">Combined with a handful of model sentence
                                        </div>
                                    </div>
                                    <div class="col-md-2  col-sm-2 col-xs-3 todoitembtns">
                                        <a href="#" class="tododelete redcolor pull-right">
                                            <i class="fa fa-times showbtns" aria-hidden="true"></i>
                                        </a>
                                        <span class="striks pull-right showbtns">|</span>
                                        <a href="#" class="todoedit pull-right">
                                            <i class="fa fa-pencil showbtns" aria-hidden="true"></i>
                                        </a>
                                        <a href="#" class="un-do pull-right" hidden>
                                            <i class="fa fa-repeat showbtns" aria-hidden="true"></i>
                                        </a>
                                    </div>
                                </div>
                            </form>
                            <div class="todolist_list adds task add_new">
                                <form id="main_input_box" class="form-inline">
                                    <div class="form-group pull-left">
                                        <label class="control-label sr-only" for="custom_textbox">Add Task</label>
                                        <input id="custom_textbox" name="item" type="text" required
                                               placeholder="Add list item here" class="form-control"/>
                                    </div>
                                    <span>
                                        <input type="submit" value="Add Task"
                                               class="btn btn-primary add_button add_task"/>
                                        <input type="button" value="Save" class="btn btn-info save_todo"/>
                                    </span>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row social_popup">
                <div class="col-xs-11 col-sm-6 col-sm-offset-3 col-md-6 col-md-offset-2 social_popup_icons">
                    <div class="social-icons">
                        <div><i class="fa fa-google google" aria-hidden="true"></i></div>
                        <div><i class="fa fa-facebook facebook" aria-hidden="true"></i></div>
                        <div><i class="fa fa-twitter twitter" aria-hidden="true"></i></div>
                        <div><i class="fa fa-linkedin linkedin" aria-hidden="true"></i></div>
                        <div><i class="fa fa-youtube-square youtube" aria-hidden="true"></i></div>
                        <div><i class="fa fa-dribbble dribbble" aria-hidden="true"></i></div>
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
<!--Sparkline Chart-->
<script src="{{asset('assets/js/custom_js/sparkline/jquery.flot.spline.js')}}"></script>
<!--c3 and d3 chart-->
<script type="text/javascript" src="{{asset('assets/vendors/c3/c3.min.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/d3/d3.min.js')}}"></script>
<!--nvd3 charts-->
<script type="text/javascript" src="{{asset('assets/vendors/nvd3/js/nv.d3.min.js')}}"></script>
<!--advanced news ticker-->
<script type="text/javascript" src="{{asset('assets/vendors/advanced_newsTicker/js/newsTicker.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/js/dashboard2.js')}}" ></script>
    <!-- end of page level js -->
@stop