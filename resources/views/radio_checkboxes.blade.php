@extends('layouts/default')

{{-- Page title --}}
@section('title')
    Radio and Checkbox
    @parent
@stop

{{-- page level styles --}}
@section('header_styles')
    <!--page level css -->
    <!-- daterange picker -->
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/daterangepicker/css/daterangepicker.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datetimepicker/css/bootstrap-datetimepicker.min.css')}}"/>
    <!--prettycheckable --->
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/prettycheckable/css/prettyCheckable.css')}}"/>
    <!-- labelauty -->
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/jquerylabel/css/jquery-labelauty.css')}}"/>
    <!--select css-->
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/select2/css/select2.min.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/select2/css/select2-bootstrap.css')}}"/>
    <!--clock face css-->
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/iCheck/css/all.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/clockpicker/css/bootstrap-clockpicker.min.css')}}">
    <link media="all" rel="stylesheet" type="text/css" href="{{asset('assets/vendors/bootstrap-fileinput/css/fileinput.min.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/awesomebootstrapcheckbox/css/awesome-bootstrap-checkbox.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom_css/radio_checkbox.css')}}">
    <!--end of page level css-->
@stop

{{-- Page content --}}
@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <!--section starts-->
        <h1>
            Radio and Checkbox
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
            <li class="active">
                Radio and Checkbox
            </li>
        </ol>
    </section>
    <!--section ends-->
    <section class="content">
        <div class="row">
            <div class="col-md-6">
                <div class="panel panel-danger">
                    <div class="panel-heading">
                        <h3 class="panel-title">
                            <i class="fa fa-fw fa-check-circle-o"></i> iCheck - Checkbox Inputs
                        </h3>
                        <span class="pull-right">
                                    <i class="fa fa-fw fa-chevron-up clickable"></i>
                                    <i class="fa fa-fw fa-times removepanel clickable"></i>
                                </span>
                    </div>
                    <div class="panel-body">
                        <div class="box-body">
                            <div class="form-group">
                                <label>
                                    <input type="checkbox" class="square-blue" checked/>
                                </label>
                                <label>
                                    <input type="checkbox" class="square-blue"/>
                                </label>
                                <label>
                                    <input type="checkbox" class="square-blue" disabled/>
                                </label>
                                <label class="m-l-10">
                                    Square blue skin checkbox
                                </label>
                            </div>
                            <!-- checkbox -->
                            <div class="form-group">
                                <label>
                                    <input type="checkbox" class="flat-red" checked/>
                                </label>
                                <label>
                                    <input type="checkbox" class="flat-red"/>
                                </label>
                                <label>
                                    <input type="checkbox" class="flat-red" disabled/>
                                </label>
                                <label class="m-l-10">
                                    Flat red skin checkbox
                                </label>
                            </div>
                            <!-- checkbox -->
                            <div class="form-group">
                                <label>
                                    <input type="checkbox" class="minimal" checked/>
                                </label>
                                <label>
                                    <input type="checkbox" class="minimal"/>
                                </label>
                                <label>
                                    <input type="checkbox" class="minimal" disabled/>
                                </label>
                                <label class="m-l-10">
                                    Minimal skin checkbox
                                </label>
                            </div>
                            <!-- Minimal red style -->
                            <!-- checkbox -->
                            <div class="form-group">
                                <label>
                                    <input type="checkbox" class="minimal-red" checked/>
                                </label>
                                <label>
                                    <input type="checkbox" class="minimal-red"/>
                                </label>
                                <label>
                                    <input type="checkbox" class="minimal-red" disabled/>
                                </label>
                                <label class="m-l-10">
                                    Minimal red skin checkbox
                                </label>
                            </div>
                            <!-- checkbox -->
                            <div class="form-group">
                                <label>
                                    <input type="checkbox" class="minimal-green" checked/>
                                </label>
                                <label>
                                    <input type="checkbox" class="minimal-green"/>
                                </label>
                                <label>
                                    <input type="checkbox" class="minimal-green" disabled/>
                                </label>
                                <label class="m-l-10">
                                    Minimal green skin checkbox
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="panel panel-info">
                    <div class="panel-heading">
                        <h3 class="panel-title">
                            <i class="fa fa-fw fa-life-ring"></i> iCheck - Radio Inputs
                        </h3>
                        <span class="pull-right">
                                    <i class="fa fa-fw fa-chevron-up clickable"></i>
                                    <i class="fa fa-fw fa-times removepanel clickable"></i>
                                </span>
                    </div>
                    <div class="panel-body">
                        <!-- radio -->
                        <div class="form-group">
                            <label>
                                <input type="radio" name="optionsRadios" value="option1" class="square-blue"
                                       checked>
                            </label>
                            <label>
                                <input type="radio" name="optionsRadios" value="option1" class="square-blue">
                            </label>
                            <label>
                                <input type="radio" name="optionsRadios" value="option1" class="square-blue"
                                       disabled/>
                            </label>
                            <label class="m-l-10">
                                Square blue skin radio
                            </label>
                        </div>
                        <div class="form-group">
                            <label>
                                <input type="radio" name="r3" class="flat-red" checked/>
                            </label>
                            <label>
                                <input type="radio" name="r3" class="flat-red"/>
                            </label>
                            <label>
                                <input type="radio" name="r3" class="flat-red" disabled/>
                            </label>
                            <label class="m-l-10">
                                Flat red skin radio
                            </label>
                        </div>
                        <div class="form-group">
                            <label>
                                <input type="radio" name="r1" class="minimal" checked/>
                            </label>
                            <label>
                                <input type="radio" name="r1" class="minimal"/>
                            </label>
                            <label>
                                <input type="radio" name="r1" class="minimal" disabled/>
                            </label>
                            <label class="m-l-10">
                                Minimal skin radio
                            </label>
                        </div>
                        <!-- radio -->
                        <div class="form-group">
                            <label>
                                <input type="radio" name="r2" class="minimal-red" checked/>
                            </label>
                            <label>
                                <input type="radio" name="r2" class="minimal-red"/>
                            </label>
                            <label>
                                <input type="radio" name="r2" class="minimal-red" disabled/>
                            </label>
                            <label class="m-l-10">
                                Minimal red skin radio
                            </label>
                        </div>
                        <!-- radio -->
                        <div class="form-group">
                            <label>
                                <input type="radio" name="r4" class="minimal-green" checked/>
                            </label>
                            <label>
                                <input type="radio" name="r4" class="minimal-green"/>
                            </label>
                            <label>
                                <input type="radio" name="r4" class="minimal-green" disabled/>
                            </label>
                            <label class="m-l-10">
                                Minimal green skin radio
                            </label>
                        </div>
                        <!-- radio -->
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="panel panel-warning">
                    <div class="panel-heading">
                        <h3 class="panel-title">
                            <i class="fa fa-fw fa-check-square"></i> Labelauty Radio and Checkboxes
                        </h3>
                        <span class="pull-right">
                                    <i class="fa fa-fw fa-chevron-up clickable"></i>
                                    <i class="fa fa-fw fa-times removepanel clickable"></i>
                                </span>
                    </div>
                    <div class="panel-body" id="lby-content">
                        <div class="row" id="lby-demo">

                            <div class="col-lg-6 col-md-12 col-sm-6">
                                <h4>Labeled Checkboxes</h4>
                                <label>
                                    <input class="to-labelauty synch-icon1" data-labelauty="Unselected|Australia"
                                           type="checkbox" checked/>
                                </label>
                                <label>
                                    <input class="to-labelauty terms-icon" type="checkbox"
                                           data-labelauty="Unselected|Selected"/>
                                </label>
                                <label>
                                    <input class="to-labelauty synch-icon" type="checkbox"
                                           data-labelauty="I am disabled!" disabled/>
                                </label>
                            </div>
                            <div class="col-lg-6 col-md-12 col-sm-6">
                                <h4>Non-labeled Check</h4>
                                <label><input class="to-labelauty-icon check-icon" type="checkbox" checked/></label>
                                <label><input class="to-labelauty-icon check-icon" type="checkbox"/></label>
                                <label><input class="to-labelauty-icon check-icon" type="checkbox" disabled
                                              checked/></label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6 col-md-12 col-sm-6">
                                <h4>Labeled Radio Buttons</h4>
                                <label>
                                    <input class="to-labelauty synch-icon2" type="radio" name="rd2"
                                           data-labelauty="Unselected|USA"/>
                                </label>
                                <label>
                                    <input class="to-labelauty terms-icon" type="radio" name="rd2"
                                           data-labelauty="Unselected|Selected" checked/>
                                </label>
                                <label>
                                    <input class="to-labelauty synch-icon" type="radio" name="rd3" disabled
                                           checked/>
                                </label>
                            </div>
                            <div class="col-lg-6 col-md-12 col-sm-6">
                                <h4>Non-labeled Radio</h4>
                                <label><input class="to-labelauty-icon" type="radio" name="rd4" checked/></label>
                                <label><input class="to-labelauty-icon" type="radio" name="rd4"/></label>
                                <label><input class="to-labelauty-icon" type="radio" name="rd3" disabled
                                              checked/></label>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="panel panel-success">
                    <div class="panel-heading">
                        <h3 class="panel-title">
                            <i class="fa fa-fw fa-check-square-o"></i> jQuery prettyCheckable
                        </h3>
                        <span class="pull-right">
                                    <i class="fa fa-fw fa-chevron-up clickable"></i>
                                    <i class="fa fa-fw fa-times removepanel clickable"></i>
                                </span>
                    </div>
                    <div class="panel-body">
                        <div class="form-group">
                            <label for="Test1">Right positioned label</label>
                            <input type="checkbox" class="test1" value="1" id="Test1" name="Test1" checked/>
                        </div>
                        <div class="form-group">
                            <label>
                                <input type="checkbox" class="TestDisabled" value="3" id="TestDisabled"
                                       name="TestDisabled" disabled data-label='Disabled Checkbox'>
                            </label>
                        </div>
                        <div class="form-group">
                            <label>
                                <input type="checkbox" class="test2" value="2" id="Test2" name="Test2"
                                       data-label="Left positioned label" data-labelPosition="left"/>
                            </label>
                        </div>
                        <div class="form-group">
                            <label class="test_radio">Radios! </label>
                            <label>
                                <input type="radio" class="test3" value="1" id="Test3_0" name="Test3" data-label="Yes"
                                       checked data-customclass="margin-right"/>
                            </label>
                            <label>
                                <input type="radio" class="test4" value="2" id="Test3_1" name="Test3" data-label="No"/>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h3 class="panel-title">
                            <i class="fa fa-fw fa-check-circle"></i> Awesome Radio &amp; Checkbox
                        </h3>
                        <span class="pull-right">
                                    <i class="fa fa-fw fa-chevron-up clickable"></i>
                                    <i class="fa fa-fw fa-times removepanel clickable"></i>
                                </span>
                    </div>
                    <div class="panel-body">
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-6 col-sm-6 awesomeradio_grid_sep">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h4>Checkboxes</h4>
                                            <div class="col-lg-4 col-sm-6">
                                                <div class="checkbox checkbox-default">
                                                    <input id="checkbox1" class="styled" type="checkbox">
                                                    <label for="checkbox1">
                                                        &nbsp;Default
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-lg-4 col-sm-6">
                                                <div class="checkbox checkbox-primary">
                                                    <input id="checkbox2" class="styled" type="checkbox">
                                                    <label for="checkbox2">
                                                        &nbsp;Primary
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-lg-4 col-sm-6">
                                                <div class="checkbox checkbox-success">
                                                    <input id="checkbox3" class="styled" type="checkbox">
                                                    <label for="checkbox3">
                                                        &nbsp;Success
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-lg-4 col-sm-6">
                                                <div class="checkbox checkbox-info">
                                                    <input id="checkbox4" class="styled" type="checkbox">
                                                    <label for="checkbox4">
                                                        &nbsp;Info
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-lg-4 col-sm-6">
                                                <div class="checkbox checkbox-warning">
                                                    <input id="checkbox5" type="checkbox" class="styled">
                                                    <label for="checkbox5">
                                                        &nbsp;Warning
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-lg-4 col-sm-6">
                                                <div class="checkbox checkbox-danger">
                                                    <input id="checkbox6" type="checkbox" class="styled">
                                                    <label for="checkbox6">
                                                        &nbsp;Danger
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h4>Inline checkboxes</h4>
                                            <div class="col-lg-4 col-sm-6">
                                                <div class="checkbox checkbox-inline ">
                                                    <input type="checkbox" class="styled" id="inlineCheckbox1"
                                                           value="option1">
                                                    <label for="inlineCheckbox1"> &nbsp;Inline One </label>
                                                </div>
                                            </div>
                                            <div class="col-lg-4 col-sm-6">
                                                <div class="checkbox checkbox-inline ">
                                                    <input type="checkbox" class="styled" id="inlineCheckbox2"
                                                           value="option1">
                                                    <label for="inlineCheckbox2"> &nbsp;Inline Two </label>
                                                </div>
                                            </div>
                                            <div class="col-lg-4 col-sm-6">
                                                <div class="checkbox checkbox-inline ">
                                                    <input type="checkbox" class="styled" id="inlineCheckbox3"
                                                           value="option1">
                                                    <label for="inlineCheckbox3"> &nbsp;Inline Three </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h4>Circled checkboxes</h4>
                                            <div class="col-md-6">
                                                <div class="checkbox checkbox-circle">
                                                    <input id="checkbox7" class="styled" type="checkbox">
                                                    <label for="checkbox7">
                                                        &nbsp;Simply Rounded
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="checkbox checkbox-info checkbox-circle">
                                                    <input id="checkbox8" class="styled" type="checkbox">
                                                    <label for="checkbox8">
                                                        &nbsp;Me too
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h4>
                                                Disabled
                                            </h4>
                                            <div class="col-lg-4 col-sm-6">
                                                <div class="checkbox">
                                                    <input class="styled" id="checkbox9" type="checkbox"
                                                           disabled>
                                                    <label for="checkbox9">
                                                        &nbsp;Can't check
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-lg-4 col-sm-6">
                                                <div class="checkbox checkbox-success">
                                                    <input class="styled styled" id="checkbox10" type="checkbox"
                                                           disabled checked>
                                                    <label for="checkbox10">
                                                        &nbsp;This too
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-lg-4 col-sm-6">
                                                <div class="checkbox checkbox-warning checkbox-circle">
                                                    <input class="styled" id="checkbox11" type="checkbox"
                                                           disabled checked>
                                                    <label for="checkbox11">
                                                        &nbsp;And this
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h4>Checkboxes with indeterminate state</h4>
                                            <div class="col-md-12">
                                                <div class="checkbox checkbox-primary">
                                                    <input id="indeterminateCheckbox" class="styled"
                                                           type="checkbox" onclick="changeState(this)">
                                                    <label></label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-6">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h4>Radios</h4>
                                            <div class="col-sm-6">
                                                <div class="radio">
                                                    <input type="radio" name="radio1" id="radio1"
                                                           value="option1">
                                                    <label for="radio1">
                                                        &nbsp;Small
                                                    </label>
                                                </div>
                                                <div class="radio">
                                                    <input type="radio" name="radio1" id="radio2"
                                                           value="option2">
                                                    <label for="radio2">
                                                        &nbsp;Big
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="radio radio-danger">
                                                    <input type="radio" name="radio2" id="radio3"
                                                           value="option1">
                                                    <label for="radio3">
                                                        &nbsp;Next
                                                    </label>
                                                </div>
                                                <div class="radio radio-danger">
                                                    <input type="radio" name="radio2" id="radio4"
                                                           value="option2">
                                                    <label for="radio4">
                                                        &nbsp;One
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h4>
                                                Disabled state
                                            </h4>
                                            <div class="col-md-6">
                                                <div class="radio radio-danger">
                                                    <input type="radio" name="radio3" id="radio5"
                                                           value="option1" disabled>
                                                    <label for="radio5">
                                                        &nbsp;Next
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="radio">
                                                    <input type="radio" name="radio3" id="radio6"
                                                           value="option2" checked disabled>
                                                    <label for="radio6">
                                                        &nbsp;One
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h4>Inline radios</h4>
                                            <div class="col-md-6">
                                                <div class="radio radio-info radio-inline ">
                                                    <input type="radio" id="inlineRadio1" value="option1"
                                                           name="radioInline">
                                                    <label for="inlineRadio1"> &nbsp;Inline One </label>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="radio radio-inline ">
                                                    <input type="radio" id="inlineRadio2" value="option2"
                                                           name="radioInline">
                                                    <label for="inlineRadio2"> &nbsp;Inline Two </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h4>
                                                Radio As Checkboxes
                                            </h4>
                                            <div class="checkbox checkbox-default">
                                                <input type="radio" name="radio4" id="radio7" value="option1">
                                                <label for="radio7">
                                                    &nbsp;Default
                                                </label>
                                            </div>
                                            <div class="checkbox checkbox-success">
                                                <input type="radio" name="radio4" id="radio8" value="option2">
                                                <label for="radio8">
                                                    &nbsp;<span class="text-success">Success</span>
                                                </label>
                                            </div>
                                            <div class="checkbox checkbox-danger">
                                                <input type="radio" name="radio4" id="radio9" value="option3">
                                                <label for="radio9">
                                                    &nbsp;<span class="text-danger">Danger</span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--row end-->
        @include('layouts.right_sidebar')
    </section>
@stop

{{-- page level scripts --}}
@section('footer_scripts')
    <!-- begining of page level js -->
    <!---prettycheckbale --->
    <script type="text/javascript" src="{{asset('assets/vendors/prettycheckable/js/prettyCheckable.min.js')}}"></script>
    <!--- labelauty -->
    <script type="text/javascript" src="{{asset('assets/vendors/jquerylabel/js/jquery-labelauty.js')}}"></script>
    <script type="text/javascript" src="{{asset('assets/vendors/iCheck/js/icheck.js')}}"></script>
    <script type="text/javascript" src="{{asset('assets/js/custom_js/radio_checkbox.js')}}"></script>
    <!-- end of page level js -->
@stop
