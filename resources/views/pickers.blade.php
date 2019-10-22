@extends('layouts/default')

    {{-- Page title --}}
    @section('title')
        Pickers
    @parent
@stop

{{-- page level styles --}}
@section('header_styles')
    <!--page level css -->
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/clockface/css/clockface.css')}}" />
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/colorpicker/css/bootstrap-colorpicker.min.css')}}" />
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datetimepicker/css/bootstrap-datetimepicker.min.css')}}" />
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/bootstrap-touchspin/css/jquery.bootstrap-touchspin.css')}}" />
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/bootstrap-multiselect/css/bootstrap-multiselect.css')}}" />
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/clockpicker/css/bootstrap-clockpicker.min.css')}}" />
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/bootstrap-switch/css/bootstrap-switch.css')}}" />
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/pickers.css')}}">
    <!--end of page level css-->
@stop

{{-- Page content --}}
@section('content')
    <!-- Content Header (Page header) -->
        <section class="content-header">
            <!--section starts-->
            <h1>Pickers</h1>
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
                    Pickers
                </li>
            </ol>
        </section>
        <!--section ends-->
        <section class="content">
            <!--main content-->
            <div class="row">
                <div class="col-md-6">
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                <i class="fa fa-fw fa-paint-brush"></i> Color Picker
                            </h3>
                            <span class="pull-right">
                                <i class="fa fa-fw fa-chevron-up clickable"></i>
                                    <i class="fa fa-fw fa-times removepanel clickable"></i>
                                </span>
                        </div>
                        <div class="panel-body">
                            <div class="box-body">
                                <!-- Color Picker -->
                                <div class="form-group">
                                    <label>Default</label>
                                    <input type="text" class="form-control my-colorpicker1" id="cp1">
                                </div>
                                <!-- /.form group -->
                                <!-- Color Picker -->
                                <div class="form-group">
                                    <label>
                                        Color picker with RGBA notation
                                    </label>
                                    <input type="text" class="form-control my-colorpicker2" id="cp2"
                                           data-color-format="rgba">
                                </div>
                                <!-- /.form group -->
                            </div>
                            <!-- /.box-body -->
                        </div>
                    </div>
                    <!--time picker-->
                    <div class="panel panel-warning">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                <i class="fa fa-fw fa-thumb-tack"></i> Bootstrap TouchSpin
                            </h3>
                            <span class="pull-right">
                                <i class="fa fa-fw fa-chevron-up clickable"></i>
                                    <i class="fa fa-fw fa-times removepanel clickable"></i>
                                    
                                </span>
                        </div>
                        <div class="panel-body">
                            <div class="box-body">
                                <!-- Touch spin -->
                                <div class="form-group">
                                    <label>Postfix</label>
                                    <input id="demo1" type="text" value="55" name="demo1" class="form-control">
                                </div>
                                <!-- /.form group -->
                                <!-- Touch spin -->
                                <div class="form-group">
                                    <label>Prefix</label>
                                    <div class="form-group">
                                        <input id="demo2" type="text" value="0" name="demo2" class="form-control">
                                    </div>
                                </div>
                                <!-- /.form group -->
                                <!-- Touch spin -->
                                <div class="form-group">
                                    <label>
                                        Vertical button alignment
                                    </label>
                                    <div class="form-group">
                                        <input id="demo_vertical" type="text" value="" name="demo_vertical">
                                    </div>
                                </div>
                                <!-- /.form group -->
                                <!-- Touch spin -->
                                <div class="form-group">
                                    <label>
                                        Vertical buttons with custom icons
                                    </label>
                                    <div class="form-group">
                                        <input id="demo_vertical2" type="text" value="" name="demo_vertical2">
                                    </div>
                                </div>
                                <!-- /.form group -->
                                <!-- Touch spin -->
                                <div class="form-group">
                                    <label>
                                        Init with empty value
                                    </label>
                                    <div class="form-group">
                                        <input id="demo3" type="text" value="" name="demo3">
                                    </div>
                                </div>
                                <!-- /.form group -->
                                <!-- Touch spin -->
                                <div class="form-group">
                                    <label>
                                        Value attribute is not set (applying settings.initval)
                                    </label>
                                    <div class="form-group">
                                        <input id="demo3_21" type="text" value="" name="demo3_21">
                                    </div>
                                </div>
                                <!-- /.form group -->
                                <!-- Touch spin -->
                                <div class="form-group">
                                    <label>
                                        Button postfix (small)
                                    </label>
                                    <div class="form-group">
                                        <input id="demo4" type="text" value="" name="demo4" class="input-sm">
                                    </div>
                                </div>
                                <!-- /.form group -->
                                <!-- Touch spin -->
                                <div class="form-group">
                                    <label>
                                        Button postfix (large)
                                    </label>
                                    <div class="form-group">
                                        <input id="demo4_2" type="text" value="" name="demo4_2"
                                               class="form-control input-lg">
                                    </div>
                                </div>
                                <!-- /.form group -->
                                <!-- Touch spin -->
                                <div class="form-group">
                                    <label>
                                        Button group
                                    </label>
                                    <div class="form-group">
                                        <div class="input-group">
                                            <input id="demo5" type="text" class="form-control" name="demo5" value="50">
                                        </div>
                                    </div>
                                </div>
                                <!-- /.form group -->
                                <!-- Touch spin -->
                                <!-- /.form group -->
                            </div>
                            <!-- /.box-body -->
                        </div>
                    </div>
                    <!--time picker ends-->
                    <!--Switch-->
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                <i class="fa fa-fw fa-toggle-on"></i> Bootstrap Switch
                            </h3>
                            <span class="pull-right">
                                <i class="fa fa-fw fa-chevron-up clickable"></i>
                                    <i class="fa fa-fw fa-times removepanel clickable"></i>
                                    
                                </span>
                        </div>
                        <div class="panel-body">
                            <!--switch -->
                            <div class="form-group">
                                <label>
                                    Default Sizes
                                </label>
                                <div class="form-group">
                                    <input type="checkbox" name="my-checkbox" data-size="mini" checked id="switchsize">
                                </div>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-default changesize">mini</button>
                                    <button type="button" class="btn btn-default changesize">small</button>
                                    <button type="button" class="btn btn-default changesize">normal</button>
                                    <button type="button" class="btn btn-default changesize">large</button>
                                </div>
                            </div>
                            <!-- /.form group -->
                            <!--toggle Indeterminate-->
                            <div class="form-group">
                                <label>
                                    Indeterminate State
                                </label>
                                <div class="form-group">
                                    <input type="checkbox" name="my-checkbox" data-size="large" checked
                                           id="indeterminate">
                                </div>
                                <button type="button" class="btn btn-default changeindeterminate">Indeterminate</button>
                            </div>
                            <!--end-->
                            <!--On And Off Text-->
                            <div class="form-group">
                                <label>
                                    Custom On And Off Text
                                </label>
                                <div class="form-group">
                                    <input type="checkbox" name="my-checkbox" data-size="large" checked id="onofftext">
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <label class="sr-only">On Text</label>
                                                <input type="text" class="ontext form-control m-t-10" maxlength="5"
                                                       placeholder="On Text">
                                            </div>
                                        </div>
                                        <div class="col-md-6 ">
                                            <div class="input-group">
                                                <label class="sr-only">Off Text</label>
                                                <input type="text" class="offtext form-control m-t-10" maxlength="5"
                                                       placeholder="Off Text">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--End-->
                            <!--switch -->
                            <div class="form-group">
                                <label>
                                    Color Switch
                                </label>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-4 col-sm-4 m-t-10">
                                            <input type="checkbox" name="my-checkbox" checked data-on-color="primary"
                                                   data-off-color="info">
                                        </div>
                                        <div class="col-md-4 col-sm-4 m-t-10">
                                            <input type="checkbox" name="my-checkbox" checked data-on-color="success"
                                                   data-off-color="warning">
                                        </div>
                                        <div class="col-md-4 col-sm-4 m-t-10">
                                            <input type="checkbox" name="my-checkbox" checked data-on-color="warning"
                                                   data-off-color="danger">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- /.form group -->
                            <!--switch -->
                            <div class="form-group">
                                <label>Without Animation</label>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-4 col-sm-4 m-t-10">
                                            <input type="checkbox" name="my-checkbox" data-on-color="info"
                                                   data-off-color="primary" data-animate>
                                        </div>
                                        <div class="col-md-4 col-sm-4 m-t-10">
                                            <input type="checkbox" name="my-checkbox" checked data-on-color="danger"
                                                   data-off-color="warning" data-animate>
                                        </div>
                                        <div class="col-md-4 col-sm-4 m-t-10">
                                            <input type="checkbox" name="my-checkbox" checked data-on-color="warning"
                                                   data-off-color="success" data-animate>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- /.form group -->
                            <!--switch -->
                            <div class="form-group">
                                <label>
                                    Disabled / Readonly
                                </label>
                                <div class="form-group">
                                    <input type="checkbox" checked disabled name="my-checkbox"/>
                                    <input type="checkbox" disabled name="my-checkbox"/>
                                </div>
                            </div>
                            <!-- /.form group -->
                        </div>
                    </div>
                </div>
                <!--col-md-6 ends-->
                <div class="col-md-6">
                    <div class="panel panel-danger">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                <i class="fa fa-fw fa-clock-o"></i> Clock Face Picker
                            </h3>
                            <span class="pull-right">
                                <i class="fa fa-fw fa-chevron-up clickable"></i>
                                    <i class="fa fa-fw fa-times removepanel clickable"></i>
                                    
                                </span>
                        </div>
                        <div class="panel-body">
                            <div class="form-group">
                                <label for="t1" class="control-label">
                                    Default clock
                                </label>
                                <input id="t1" class="form-control input-small" value="2:30 PM" data-format="hh:mm A"
                                       type="text">
                            </div>
                            <div class="form-group">
                                <label for="t2" class="control-label">Button</label>
                                <div class="input-group">
                                    <input type="text" class="form-control input-small" id="t2" value="14:30" readonly>
                                    <span class="input-group-btn">
                                            <button class="btn" type="button" id="toggle-btn">
                                                <i class="fa fa-fw fa-clock-o"></i>
                                            </button>
                                        </span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="t2" class="control-label">
                                    Clock picker
                                </label>
                                <div class="input-group form-inline">
                                    <input type="text" class="form-control" id="input-a" value="" data-default="20:48">
                                    <span class="input-group-btn">
                                        <button class="btn btn-info" type="button"
                                                id="button-a"> Pick your time</button>
                                      </span>
                                </div>


                            </div>
                        </div>
                    </div>
                    <!--date picker-->
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                <i class="fa fa-fw fa-calendar"></i> Date Picker
                            </h3>
                            <span class="pull-right">
                                <i class="fa fa-fw fa-chevron-up clickable"></i>
                                        <i class="fa fa-fw fa-times removepanel clickable"></i>
                                        
                                    </span>
                        </div>
                        <div class="panel-body">
                            <div class="form-group">
                                <label>Default:</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-laptop"></i>
                                    </div>
                                    <input type="text" class="form-control" id="datetime1"/>
                                </div>
                                <!-- /.input group -->
                            </div>
                            <div class="form-group">
                                <label>Custom Format:</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-laptop"></i>
                                    </div>
                                    <input type="text" class="form-control" id="datetime2"/>
                                </div>
                                <!-- /.input group -->
                            </div>
                            <div class="form-group">
                                <label>Custom View:</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-laptop"></i>
                                    </div>
                                    <input type="text" class="form-control" id="datetime3"/>
                                </div>
                                <!-- /.input group -->
                            </div>
                            <div class="form-group">
                                <label>Min View:</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-laptop"></i>
                                    </div>
                                    <input type="text" class="form-control" id="datetime4"/>
                                </div>
                                <!-- /.input group -->
                            </div>
                            <label>Inline View:</label>
                            <div id="datetime5"></div>
                            <!-- /.input group -->
                        </div>
                    </div>
                    <!--multi select-->
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                <i class="fa fa-fw fa-fighter-jet"></i> Multiselect
                            </h3>
                            <span class="pull-right">
                                <i class="fa fa-fw fa-chevron-up clickable"></i>
                                    <i class="fa fa-fw fa-times removepanel clickable"></i>
                                    
                                </span>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="form-group col-md-5 col-sm-6 col-xs-12">
                                    <label>
                                        Normal Select
                                    </label>
                                    <div class="input-group  col-md-8">
                                        <select class="multiselect" multiple="multiple">
                                            <option value="cheese">Cheese</option>
                                            <option value="tomatoes">Tomatoes</option>
                                            <option value="mozarella">Mozzarella</option>
                                            <option value="mushrooms">Mushrooms</option>
                                            <option value="pepperoni">Pepperoni</option>
                                            <option value="onions">Onions</option>
                                        </select>
                                    </div>
                                </div>
                                <!-- /.form group -->
                                <div class="form-group col-md-5 col-sm-6 col-xs-12">
                                    <label>
                                        Preselected
                                    </label>
                                    <div class="input-group  col-md-8">
                                        <select id="example2" multiple="multiple">
                                            <option value="cheese" selected>Cheese</option>
                                            <option value="tomatoes" selected>Tomatoes</option>
                                            <option value="mozarella">Mozzarella</option>
                                            <option value="mushrooms">Mushrooms</option>
                                            <option value="pepperoni">Pepperoni</option>
                                            <option value="onions">Onions</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <!-- /.form group -->
                            <div class="row">
                                <div class="form-group col-md-5 col-sm-6 col-xs-12">
                                    <label>Select All</label>
                                    <div class="input-group  col-md-8">
                                        <select id="example27" multiple="multiple">
                                            <option value="cheese">Cheese</option>
                                            <option value="tomatoes">Tomatoes</option>
                                            <option value="mozarella">Mozzarella</option>
                                            <option value="mushrooms">Mushrooms</option>
                                            <option value="pepperoni">Pepperoni</option>
                                            <option value="onions">Onions</option>
                                        </select>
                                    </div>
                                </div>
                                <!-- /.form group -->
                                <div class="form-group col-md-5 col-sm-6 col-xs-12">
                                    <label>
                                        Link Select
                                    </label>
                                    <div class="input-group  col-md-8">
                                        <div class="btn-group">
                                            <select id="example3" multiple="multiple">
                                                <option value="cheese">Cheese</option>
                                                <option value="tomatoes">Tomatoes</option>
                                                <option value="mozarella">Mozzarella</option>
                                                <option value="mushrooms">Mushrooms</option>
                                                <option value="pepperoni">Pepperoni</option>
                                                <option value="onions">Onions</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- /.form group -->
                            <div class="row">
                                <div class="form-group col-md-5 col-sm-6 col-xs-12">
                                    <label>
                                        Group Label
                                    </label>
                                    <div class="input-group  col-md-8">
                                        <div class="btn-group">
                                            <select id="example19" multiple="multiple">
                                                <optgroup label="Mathematics">
                                                    <option value="analysis">Analysis</option>
                                                    <option value="algebra">
                                                        Linear Algebra
                                                    </option>
                                                    <option value="discrete">
                                                        Discrete Mathematics
                                                    </option>
                                                    <option value="numerical">
                                                        Numerical Analysis
                                                    </option>
                                                    <option value="probability">
                                                        Probability Theory
                                                    </option>
                                                </optgroup>
                                                <optgroup label="Computer Science">
                                                    <option value="programming">
                                                        Programming
                                                    </option>
                                                    <option value="automata">
                                                        Automata Theory
                                                    </option>
                                                    <option value="complexity">
                                                        Complexity Theory
                                                    </option>
                                                    <option value="software">
                                                        Software Engineering
                                                    </option>
                                                </optgroup>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <!-- /.form group -->
                                <div class="form-group col-md-5 col-sm-6 col-xs-12">
                                    <label>Add-ons</label>
                                    <div class="input-group  col-md-8">
                                        <div class="input-group btn-group">
                                            <span class="input-group-addon"> <b
                                                    class="glyphicon glyphicon-list-alt"></b>
                                            </span>
                                            <select id="example6" multiple="multiple">
                                                <option value="cheese">Cheese</option>
                                                <option value="tomatoes">Tomatoes</option>
                                                <option value="mozarella">Mozzarella</option>
                                                <option value="mushrooms">Mushrooms</option>
                                                <option value="pepperoni">Pepperoni</option>
                                                <option value="onions">Onions</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- /.form group -->
                            <div class="row">
                                <div class="form-group col-md-5 col-sm-6 col-xs-12">
                                    <label>On Change</label>
                                    <div class="input-group  col-md-8">
                                        <div class="btn-group">
                                            <select id="example9" multiple="multiple">
                                                <option value="cheese">Cheese</option>
                                                <option value="tomatoes">Tomatoes</option>
                                                <option value="mozarella">Mozzarella</option>
                                                <option value="mushrooms">Mushrooms</option>
                                                <option value="pepperoni">Pepperoni</option>
                                                <option value="onions">Onions</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <!-- /.form group -->
                                <div class="form-group col-md-5 col-sm-6 col-xs-12">
                                    <label>Disable</label>
                                    <div class="input-group  col-md-8">
                                        <div class="btn-group">
                                            <select id="example13" multiple="multiple">
                                                <option value="enabled1">Enabled 1</option>
                                                <option value="enabled2">Enabled 2</option>
                                                <option value="disabled2" disabled="disabled">Disabled 1</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- /.form group -->
                            <div class="row">
                              <div class="form-group col-md-5 col-sm-6 col-xs-12">
                                        <label>Select All</label>
                                        <div class="input-group col-md-8">
                                            <select id="example28" multiple="multiple"></select>                                     
                                        </div>
                                        <div class="col-md-8 m-t-10 input-group"><button id="example28-values" class="btn btn-primary">Chosen</button></div>
                                    </div>
                                <!-- /.form group -->
                                <div class="form-group col-md-5 col-sm-6 col-xs-12">
                                    <label>
                                        Multiselect
                                    </label>
                                    <div class="input-group  col-md-8">
                                        <select id="example35" multiple="multiple" disabled>
                                            <option value="cheese">Cheese</option>
                                            <option value="tomatoes">Tomatoes</option>
                                            <option value="mozarella">Mozzarella</option>
                                            <option value="mushrooms">Mushrooms</option>
                                            <option value="pepperoni">Pepperoni</option>
                                            <option value="onions">Onions</option>
                                        </select>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <button id="example35-enable" class="btn btn-default m-t-10">Enable
                                                </button>
                                            </div>
                                            <div class="col-md-6">
                                                <button id="example35-disable" class="btn btn-default m-t-10">Disable
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- /.form group -->
                        </div>
                    </div>
                </div>
                <!--col-md-6 ends-->
            </div>
            <!-- row -->
        @include('layouts.right_sidebar')
        <!-- right side bar end -->
        </section>
@stop

{{-- page level scripts --}}
@section('footer_scripts')
    <!-- begining of page level js -->
<script type="text/javascript" src="{{asset('assets/vendors/moment/js/moment.min.js')}}" ></script>
<script type="text/javascript" src="{{asset('assets/vendors/colorpicker/js/bootstrap-colorpicker.min.js')}}" ></script>
<script type="text/javascript" src="{{asset('assets/vendors/clockpicker/js/bootstrap-clockpicker.min.js')}}" ></script>
<script type="text/javascript" src="{{asset('assets/vendors/datetimepicker/js/bootstrap-datetimepicker.min.js')}}" ></script>
<script type="text/javascript" src="{{asset('assets/vendors/bootstrap-multiselect/js/bootstrap-multiselect.js')}}" ></script>
<script type="text/javascript" src="{{asset('assets/vendors/clockface/js/clockface.js')}}" ></script>
<script type="text/javascript" src="{{asset('assets/vendors/bootstrap-touchspin/js/jquery.bootstrap-touchspin.js')}}" ></script>
<script type="text/javascript" src="{{asset('assets/vendors/bootstrap-switch/js/bootstrap-switch.js')}}" ></script>
<script type="text/javascript" src="{{asset('assets/vendors/toolbar/js/jquery.toolbar.min.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/js/custom_js/pickers.js')}}"></script>
    <!-- end of page level js ->
@stop
