@extends('layouts/default')

    {{-- Page title --}}
    @section('title')
        X-Editable
    @parent
@stop

{{-- page level styles --}}
@section('header_styles')
    <!-- page level css -->
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/x-editable/css/bootstrap-editable.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/x-editable/css/typeahead.js-bootstrap.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/iCheck/css/all.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/inlinedit.css')}}"/>
    <!-- end of page level css -->
@stop

{{-- Page content --}}
@section('content')
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>X-Editable</h1>
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
                        X-Editable
                </li>
            </ol>
        </section>
        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                <i class="fa fa-fw fa-eyedropper"></i> X-Editable
                            </h3>
                        </div>
                        <div class="panel-body">
                               <div class="row">
                                <div class="col-sm-6 m-t-10">
                                    <div class="form-group">
                                    <label class="m-t-10"><input type="checkbox" id="autoopen" class="custom-checkbox"/>
                                        Auto-open next field</label>
                                        <button id="enable" class="btn btn-success btn-sm">Enable / Disable</button>
                                    </div>

                                </div>
                                <div class="col-sm-5">
                                    <form method="get" id="frm" class="form-inline" action="#">
                                        <div class="form-group">
                                            <label for="c" class="m-t-6 m-t-14 pull-left">Mode</label>
                                            <select name="c" id="c" class="form-control input-sm m-l-10 m-t-10 pull-left">
                                                <option value="popup">Popup</option>
                                                <option value="inline">Inline</option>
                                            </select>
                                            <button type="submit" class="btn btn-responsive btn-warning btn-sm m-l-10 m-t-10 pull-left">
                                                Refresh
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <table id="user" class="table table-bordered table-striped m-t-10">
                                <tbody>
                                <tr>
                                    <td class="table_simple">Simple text field</td>
                                    <td class="table_superuser">
                                        <a href="#" id="username" data-type="text" data-pk="1"
                                           data-title="Enter username" class="editable editable-click"
                                           data-original-title="" title="">superuser</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Empty text field, required</td>
                                    <td>
                                        <a href="#" id="firstname" data-type="text" data-pk="1" data-placement="top"
                                           data-placeholder="Required" data-title="Enter your firstname"
                                           class="editable editable-click editable-empty" data-original-title=""
                                           title="">Change It</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Combodate (date)</td>
                                    <td>
                                        <a href="#" id="dob" data-type="combodate" data-value="1984-05-15"
                                           data-format="YYYY-MM-DD" data-viewformat="DD/MM/YYYY"
                                           data-template="D / MMM / YYYY" data-pk="1" data-title="Select Date of birth"
                                           class="editable editable-click">15/05/1984</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Combodate (datetime)</td>
                                    <td>
                                        <a href="#" id="event" data-type="combodate"
                                           data-template="D / MMM / YYYY , HH:mm" data-format="YYYY-MM-DD HH:mm"
                                           data-viewformat="MMM/D/YYYY, HH:mm" data-pk="1"
                                           data-title="Setup event date and time"
                                           class="editable editable-click editable-empty">Empty</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Text area</td>
                                    <td>
                                        <a href="#" id="comments" data-type="textarea" data-pk="1"
                                           data-placeholder="Your comments here..." data-title="Enter comments"
                                           class="editable editable-pre-wrapped editable-click">awesome user!</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Twitter typeahead.js</td>
                                    <td>
                                        <a href="#" id="state2" data-type="typeaheadjs" data-pk="1"
                                           data-placement="top" data-title="Start typing State.."
                                           class="editable editable-click">California</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Checklist</td>
                                    <td>
                                        <a href="#" id="fruits" data-type="checklist" data-value="2,3"
                                           data-title="Select fruits" class="editable editable-click">
                                            peach
                                            <br>apple</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Custom input, several fields</td>
                                    <td>
                                        <a id="address" data-type="address" data-pk="1"
                                           data-title="Please, fill address" class="editable editable-click"
                                           data-original-title="" title=""> <b>Moscow</b> , Lenina st., bld. 12
                                        </a>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
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
<script type="text/javascript"  src="{{asset('assets/vendors/jquery-mockjax/js/jquery.mockjax.js')}}"></script>
<script type="text/javascript"  src="{{asset('assets/vendors/moment/js/moment.min.js')}}"></script>
<script type="text/javascript"  src="{{asset('assets/vendors/x-editable/js/bootstrap-editable.js')}}"></script>
<script type="text/javascript"  src="{{asset('assets/vendors/x-editable/js/typeahead.js')}}"></script>
<script type="text/javascript"  src="{{asset('assets/vendors/x-editable/js/typeaheadjs.js')}}"></script>
<script type="text/javascript"  src="{{asset('assets/vendors/x-editable/js/address.js')}}"></script>
<script type="text/javascript"  src="{{asset('assets/vendors/iCheck/js/icheck.js')}}"></script>
<script type="text/javascript"  src="{{asset('assets/js/demo-mock.js')}}"></script>
<script type="text/javascript"  src="{{asset('assets/js/demo.js')}}"></script>
<script type="text/javascript"  src="{{asset('assets/js/custom_js/x-editable.js')}}"></script>
<!-- end of global js -->
@stop