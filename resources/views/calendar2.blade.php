@extends('layouts/default')

{{-- Page title --}}
@section('title')
    Advanced Calendar
    @parent
@stop

{{-- page level styles --}}
@section('header_styles')
    <!--page level css -->
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/bootstrap-calendar/css/calendar.min.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/jquerydaterangepicker/css/daterangepicker.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/iCheck/css/all.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/calendar_custom2.css')}}"/>
    <!--end of page level css-->
@stop

{{-- Page content --}}
@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>Advanced Calendar</h1>
        <ol class="breadcrumb">
            <li>
                <a href="index">
                    <i class="fa fa-fw fa-home"></i> Dashboard
                </a>
            </li>
            <li>
                <a href="#">Calendar</a>
            </li>
            <li class="active">
                Advanced Calendar
            </li>
        </ol>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="page-header">
            <div class="pull-right form-inline">
                <div class="btn-group m-t-10">
                    <button class="btn btn-primary" data-calendar-nav="prev">
                        <i class="fa fa-fw fa-angle-double-left" aria-hidden="true"></i> Prev
                    </button>
                    <button class="btn btn-default" data-calendar-nav="today">Today</button>
                    <button class="btn btn-primary" data-calendar-nav="next">Next <i
                                class="fa fa-fw fa-angle-double-right" aria-hidden="true"></i>
                    </button>
                </div>
                <div class="btn-group m-t-10">
                    <button class="btn btn-warning" data-calendar-view="year">Year</button>
                    <button class="btn btn-warning active" data-calendar-view="month">Month</button>
                    <button class="btn btn-warning" data-calendar-view="week">Week</button>
                    <button class="btn btn-warning" data-calendar-view="day">Day</button>
                </div>
            </div>
            <h3></h3>
        </div>
        <div class="row">
            <div class="col-md-9">
                <div id="calendar" class="m-b-25"></div>
            </div>
            <div class="col-md-3">
                <div class="row">
                    <div class="col-md-12">
                        <H4>Calendar Settings</H4>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="input-group">
                                    <label for="first_day">First day of week </label>
                                    <select id="first_day" class="form-control">
                                        <option value="" selected="selected">language-dependant</option>
                                        <option value="2">Sunday</option>
                                        <option value="1">Monday</option>
                                    </select>
                                </div>
                                <div class="input-group">
                                    <label for="language">Select Language</label>
                                    <select id="language" class="form-control">
                                        <option value="">default: English-US</option>
                                        <option value="bg-BG">Bulgarian</option>
                                        <option value="nl-NL">Dutch</option>
                                        <option value="fr-FR">French</option>
                                        <option value="de-DE">German</option>
                                        <option value="el-GR">Greek</option>
                                        <option value="hu-HU">Hungarian</option>
                                        <option value="id-ID">Bahasa Indonesia</option>
                                        <option value="it-IT">Italian</option>
                                        <option value="pl-PL">Polish</option>
                                        <option value="pt-BR">Portuguese (Brazil)</option>
                                        <option value="ro-RO">Romania</option>
                                        <option value="es-CO">Spanish (Colombia)</option>
                                        <option value="es-MX">Spanish (Mexico)</option>
                                        <option value="es-ES">Spanish (Spain)</option>
                                        <option value="ru-RU">Russian</option>
                                        <option value="sk-SR">Slovak</option>
                                        <option value="sv-SE">Swedish</option>
                                        <option value="zh-CN">简体中文</option>
                                        <option value="zh-TW">繁體中文</option>
                                        <option value="ko-KR">한국어</option>
                                        <option value="th-TH">Thai (Thailand)</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 m-t-10">
                                <label class="checkbox">
                                    <input type="checkbox" value="#events-modal" id="events-in-modal"> Open events
                                    in modal window
                                </label>
                            </div>
                            <div class="col-md-12 m-t-10">
                                <label class="checkbox">
                                    <input type="checkbox" id="format-12-hours"> 12 Hour format
                                </label>
                            </div>
                            <div class="col-md-12 m-t-10">
                                <label class="checkbox">
                                    <input type="checkbox" id="show_wb" checked> Show week box
                                </label>
                            </div>
                            <div class="col-md-12 m-t-10">
                                <label class="checkbox">
                                    <input type="checkbox" id="show_wbn" checked> Show week box number
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <hr/>
                <div class="m-t-25">
                    <h4>Events List</h4>
                </div>
                <ul id="eventlist" class="nav nav-list"></ul>
                <div class="row m-t-10">
                    <div class="col-md-12">
                        <a href="#" class="btn btn-success btn-block" data-toggle="modal" data-target="#myModal">Create
                            event</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
             aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title" id="myModalLabel">
                            <i class="fa fa-plus"></i> Create Event
                        </h4>
                    </div>
                    <form role="form" name="eventform" id="eventform">
                        <div class="modal-body">
                            <div class="input-group">
                                <label for="new-event">Event Name</label>
                                <input type="text" id="new-event" class="form-control" placeholder="Event">
                            </div>
                            <div class="input-group">
                                <label for="eventclass">Event Class</label>
                                <select name="eventclass" id="eventclass" class="form-control">
                                    <option value="event-important" selected>Important</option>
                                    <option value="event-success">Success</option>
                                    <option value="event-primary">primary</option>
                                    <option value="event-default">Default</option>
                                    <option value="event-info">Info</option>
                                    <option value="event-warning">Warning</option>
                                </select>
                            </div>
                            <label for="event_url">Event URL</label>
                            <div class="input-group">
                                <span class="input-group-addon">HTTP://</span>
                                <input type="text" class="form-control pull-right" id="event_url"
                                       placeholder="Enter The URL related to event"/>
                            </div>
                            <div class="input-group">
                                <label for="date-range0">Date Range</label>
                                <input type="text" class="form-control pull-right" id="date-range0"
                                       placeholder="Select Date Range For Event"/>
                            </div>
                            <!-- /input-group -->
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger pull-right" id="close_calendar_event"
                                    data-dismiss="modal">
                                Close
                                <i class="fa fa-times"></i>
                            </button>
                            <button type="button" class="btn btn-success pull-left" id="add-new-event">
                                <i class="fa fa-plus"></i> Add
                            </button>
                            <button type="reset" class="hidden">Reset</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="modal fade" id="events-modal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h3>Event</h3>
                    </div>
                    <div class="modal-body" style="height: 400px">
                    </div>
                    <div class="modal-footer">
                        <a href="#" data-dismiss="modal" class="btn btn-danger">Close</a>
                    </div>
                </div>
            </div>
        </div>
        <!--main content ends-->
        @include('layouts.right_sidebar')
    </section>
@stop

{{-- page level scripts --}}
@section('footer_scripts')
    <!-- begining of page level js -->
    <script type="text/javascript" src="{{asset('assets/vendors/moment/js/moment.min.js')}}" ></script>
    <script type="text/javascript" src="{{asset('assets/vendors/jquerydaterangepicker/js/jquery.daterangepicker.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('assets/vendors/underscore/js/underscore-min.js')}}"></script>
    <script type="text/javascript" src="{{asset('assets/vendors/bootstrap-calendar/js/calendar.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('assets/vendors/iCheck/js/icheck.js')}}"></script>
    <script type="text/javascript" src="{{asset('assets/js/custom_js/calendar_custom2.js')}}"></script>
    <!-- end of page level js -->
@stop
