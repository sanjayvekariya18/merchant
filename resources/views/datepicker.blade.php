@extends('layouts/default')

    {{-- Page title --}}
    @section('title')
        Date pickers
    @parent
@stop

{{-- page level styles --}}
@section('header_styles')
    <!--page level css -->
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/daterangepicker/css/daterangepicker.css')}}" />
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datetimepicker/css/bootstrap-datetimepicker.min.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datedropper/datedropper.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/timedropper/css/timedropper.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/jquerydaterangepicker/css/daterangepicker.min.css')}}">
    <!--clock face css-->
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/clockpicker/css/bootstrap-clockpicker.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/datepicker.css')}}">
    <!--end of page level css-->
@stop

{{-- Page content --}}
@section('content')
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <!--section starts-->
            <h1>
                Date pickers
            </h1>
            <ol class="breadcrumb">
                <li>
                    <a href="index">
                        <i class="fa fa-fw fa-home"></i> Dashboard
                    </a>
                </li>
                <li>
                    <a href="#">Forms</a>
                </li>
                <li>
                        Date pickers
                </li>
            </ol>
        </section>
        <!--section ends-->
        <section class="content">
            <!--main content-->
            <div class="row">
                <div class="col-md-6">
                    <div class="panel panel-warning">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                <i class="fa fa-fw fa-calendar"></i> Date and Time range picker
                            </h3>
                            <span class="pull-right">
                                    <i class="fa fa-fw fa-chevron-up clickable"></i>
                                    <i class="fa fa-fw fa-times removepanel clickable"></i>
                                </span>
                        </div>
                        <div class="panel-body">
                            <div class="box-body">
                                <!-- Date range -->
                                <div class="form-group">
                                    <label>
                                        Date picker:
                                    </label>
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fa fa-fw fa-calendar"></i>
                                        </div>
                                        <input type="text" class="form-control pull-right" id="date-range0"
                                               placeholder="YYYY-MM-DD to YYYY-MM-DD"/>
                                    </div>
                                    <!-- /.input group -->
                                </div>
                                <!-- /.form group -->
                                <!-- Date and time range -->
                                <div class="form-group">
                                    <label>
                                        Date and Time Picker:
                                    </label>
                                    <div class="input-group ">
                                        <input type="text" class="form-control" id="dateclock"
                                               placeholder="YYYY-MM-DD HH:MM ~ YYYY-MM-DD HH:MM">
                                        <span class="input-group-addon">
                                                <span class="glyphicon glyphicon-time"></span>
                                            </span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>
                                        Span instead of Input:
                                    </label>
                                    <div class="input-group">
                                        <span id="date-range9"
                                              style="background-color:#428BCA; color:white;padding:3px; cursor:pointer; border-radius:4px;">YYYY-MM-DD to YYYY-MM-DD</span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>
                                        Date picker with Animation:
                                    </label>
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fa fa-fw fa-clock-o"></i>
                                        </div>
                                        <input id="date-range50" size="30" value="" class="form-control"
                                               placeholder="YYYY-MM-DD to YYYY-MM-DD">
                                    </div>
                                    <!-- /.input group -->
                                </div>
                                <!-- /.form group -->
                                <!-- Date and time range -->
                                <div class="form-group">
                                    <label>
                                        Hotel booking:
                                    </label>
                                    <div class="input-group">

                                        <input id="hotel-booking" class="form-control" size="60" value=""
                                               placeholder="Days Booked">
                                        <span></span>
                                        <div class="input-group-addon">
                                            <i class="fa fa-fw fa-calendar"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>
                                        Select backward:
                                    </label>
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fa fa-fw fa-calendar"></i>
                                        </div>
                                        <input class="form-control" id="date-range26" size="30" value=""
                                               placeholder="YYYY-MM-DD to YYYY-MM-DD">
                                    </div>
                                    <!-- /.input group -->
                                </div>
                                <!-- /.form group -->
                                <div class="form-group">
                                    <label>
                                        Single Date mode with single month:
                                    </label>
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fa fa-fw fa-calendar"></i>
                                        </div>
                                        <input class="form-control" id="date-range13-2" size="40"
                                               placeholder="YYYY-MM-DD">
                                    </div>
                                    <!-- /.input group -->
                                </div>
                                <!-- /.form group -->
                                <div class="form-group">
                                    <label>
                                        Batch mode ( week ):
                                    </label>
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fa fa-fw fa-calendar"></i>
                                        </div>
                                        <input class="form-control" id="date-range14" size="60" value=""
                                               placeholder="YYYY-MM-DD to YYYY-MM-DD">
                                    </div>
                                    <!-- /.input group -->
                                </div>
                                <!-- /.form group -->
                            </div>
                            <!-- /.form group -->
                            <!-- time picker -->
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                <i class="fa fa-fw fa-tint"></i> Date & Time Dropper
                            </h3>
                            <span class="pull-right">
                                    <i class="fa fa-fw fa-chevron-up clickable"></i>
                                    <i class="fa fa-fw fa-times removepanel clickable"></i>
                                </span>
                        </div>
                        <div class="panel-body">
                            <div class="form-group">
                                <label>
                                    Date dropper:
                                </label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-fw fa-calendar"></i>
                                    </div>
                                    <input type="text" class="form-control" id="departure" placeholder="DD/MM/YYYY"/>
                                </div>
                                <!-- /.input group -->
                            </div>
                            <!-- /.form group -->
                            <div class="form-group">
                                <label>
                                    Date format:
                                </label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-fw fa-calendar"></i>
                                    </div>
                                    <input type="text" class="form-control" id="departure1" placeholder="YYYY-MM-DD"/>
                                </div>
                                <!-- /.input group -->
                            </div>
                            <!-- /.form group -->
                            <div class="form-group">
                                <label>
                                    Maximum Year:
                                </label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-fw fa-calendar"></i>
                                    </div>
                                    <input type="text" class="form-control" id="departure2" placeholder="DD/MM/YYYY"/>
                                </div>
                                <!-- /.input group -->
                            </div>
                            <div class="form-group">
                                <label>
                                    Animate date dropper
                                </label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-fw fa-calendar"></i>
                                    </div>
                                    <input type="text" class="form-control" id="departure3" placeholder="DD/MM/YYYY"/>
                                </div>
                                <!-- /.input group -->
                            </div>
                            <!-- /.form group -->
                            <div class="form-group">
                                <label>
                                    Time dropper:
                                </label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-fw fa-clock-o"></i>
                                    </div>
                                    <input type="text" class="form-control" id="alarm" placeholder="HH-MM"/>
                                </div>
                                <!-- /.input group -->
                            </div>
                            <!-- /.form group -->
                            <div class="form-group">
                                <label>
                                    Meridian time dropper:
                                </label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-fw fa-clock-o"></i>
                                    </div>
                                    <input type="text" class="form-control" id="alarm1" placeholder="HH-MM"/>
                                </div>
                                <!-- /.input group -->
                            </div>
                            <div class="form-group">
                                <label>
                                    Animate time dropper:
                                </label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-fw fa-clock-o"></i>
                                    </div>
                                    <input type="text" class="form-control" id="alarm2" placeholder="HH-MM"/>
                                </div>
                                <!-- /.input group -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                <i class="fa fa-fw fa-calendar"></i> Date Range picker
                            </h3>
                            <span class="pull-right">
                                    <i class="fa fa-fw fa-chevron-up clickable"></i>
                                    <i class="fa fa-fw fa-times removepanel clickable"></i>
                                </span>
                        </div>
                        <div class="panel-body">
                            <div class="box-body">
                                <!-- Date range -->
                                <div class="form-group">
                                    <label>
                                        Date range:
                                    </label>
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fa fa-fw fa-calendar"></i>
                                        </div>
                                        <input type="text" class="form-control pull-right" id="reservation"
                                               placeholder="MM/DD/YYYY - MM/DD/YYYY"/>
                                    </div>
                                    <!-- /.input group -->
                                </div>
                                <!-- /.form group -->
                                <!-- Date and time range -->
                                <div class="form-group">
                                    <label>
                                        Clock Picker:
                                    </label>
                                    <div class="input-group clockpicker" data-placement="left" data-align="top"
                                         data-autoclose="true">
                                        <input type="text" class="form-control" value="Now" placeholder="HH:MM">
                                        <span class="input-group-addon">
                                                <span class="glyphicon glyphicon-time"></span>
                                            </span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>
                                        Call Backs Clock Picker:
                                    </label>
                                    <div class="input-group clockpicker-with-callbacks">
                                        <input type="text" class="form-control" value="Now" placeholder="HH:MM">
                                        <span class="input-group-addon">
                                                <span class="glyphicon glyphicon-time"></span>
                                            </span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>
                                        Date and time range:
                                    </label>
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fa fa-fw fa-clock-o"></i>
                                        </div>
                                        <input type="text" class="form-control pull-right" id="reservationtime"
                                               placeholder="MM/DD/YYYY HH:MM-MM/DD/YYYY HH:MM">
                                    </div>
                                    <!-- /.input group -->
                                </div>
                                <!-- /.form group -->
                                <!-- Date and time range -->
                                <div class="form-group">
                                    <label>
                                        Date range button:
                                    </label>
                                    <div class="input-group">
                                            <span class="input-group-addon">
                                                    <i class="fa fa-calendar"></i>
                                                </span>
                                        <input type="text" class="form-control" id="reportrange"
                                               placeholder="DD/MM/YYYY-DD/MM/YYYY">
                                    </div>
                                </div>
                                <!-- /.form group -->
                                <!-- time picker -->
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="panel panel-success">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                <i class="fa fa-fw fa-heart-o"></i> Input masks
                            </h3>
                            <span class="pull-right">
                                    <i class="fa fa-fw fa-chevron-up clickable"></i>
                                    <i class="fa fa-fw fa-times removepanel clickable"></i>
                                </span>
                        </div>
                        <div class="panel-body">
                            <div class="box-body">
                                <!-- Date dd/mm/yyyy -->
                                <div class="form-group">
                                    <label>
                                        Date masks:
                                    </label>
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fa fa-fw fa-calendar"></i>
                                        </div>
                                        <input type="text" class="form-control" data-inputmask="'alias': 'dd/mm/yyyy'"
                                               data-mask/>
                                    </div>
                                    <!-- /.input group -->
                                </div>
                                <!-- /.form group -->
                                <!-- Date mm/dd/yyyy -->
                                <div class="form-group">
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fa fa-fw fa-calendar"></i>
                                        </div>
                                        <input type="text" class="form-control" data-inputmask="'alias': 'mm/dd/yyyy'"
                                               data-mask/>
                                    </div>
                                    <!-- /.input group -->
                                </div>
                                <!-- /.form group -->
                                <!-- phone mask -->
                                <div class="form-group">
                                    <label>
                                        US phone mask:
                                    </label>
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fa fa-fw fa-phone"></i>
                                        </div>
                                        <input type="text" class="form-control"
                                               data-inputmask='"mask": "(999) 999-9999"' data-mask/>
                                    </div>
                                    <!-- /.input group -->
                                </div>
                                <!-- /.form group -->
                                <!-- phone mask -->
                                <div class="form-group">
                                    <label>
                                        Intl US phone mask:
                                    </label>
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fa fa-fw fa-phone"></i>
                                        </div>
                                        <input type="text" class="form-control"
                                               data-inputmask="'mask': ['999-999-9999 [x99999]', '+099 99 99 9999[9]-9999']"
                                               data-mask/>
                                    </div>
                                    <!-- /.input group -->
                                </div>
                                <!-- /.form group -->
                                <!-- IP mask -->
                                <div class="form-group">
                                    <label>IP mask:</label>
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fa fa-fw fa-laptop"></i>
                                        </div>
                                        <input type="text" class="form-control" data-inputmask="'alias': 'ip'"
                                               data-mask/>
                                    </div>
                                    <!-- /.input group -->
                                </div>
                                <!-- /.form group -->
                            </div>
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
<!-- InputMask -->
<script  type="text/javascript" src="{{asset('assets/vendors/moment/js/moment.min.js')}}"></script>
<script  type="text/javascript" src="{{asset('assets/vendors/inputmask/inputmask/inputmask.js')}}"></script>
<script  type="text/javascript" src="{{asset('assets/vendors/inputmask/inputmask/jquery.inputmask.js')}}" ></script>
<script  type="text/javascript" src="{{asset('assets/vendors/inputmask/inputmask/inputmask.date.extensions.js')}}" ></script>
<script  type="text/javascript" src="{{asset('assets/vendors/inputmask/inputmask/inputmask.extensions.js')}}" ></script>
<!-- date-range-picker -->
<script  type="text/javascript" src="{{asset('assets/vendors/daterangepicker/js/daterangepicker.js')}}" ></script>
<!-- bootstrap time picker -->
<script  type="text/javascript" src="{{asset('assets/vendors/datetimepicker/js/bootstrap-datetimepicker.min.js')}}" ></script>
<script  type="text/javascript" src="{{asset('assets/vendors/clockpicker/js/bootstrap-clockpicker.min.js')}}" ></script>
<script  type="text/javascript" src="{{asset('assets/vendors/jquerydaterangepicker/js/jquery.daterangepicker.min.js')}}" ></script>
<script  type="text/javascript" src="{{asset('assets/vendors/datedropper/datedropper.js')}}" ></script>
<script  type="text/javascript" src="{{asset('assets/vendors/timedropper/js/timedropper.js')}}" ></script>
<script  type="text/javascript" src="{{asset('assets/js/custom_js/datepickers.js')}}" ></script>
        <!-- end of page level js -->
    @stop

