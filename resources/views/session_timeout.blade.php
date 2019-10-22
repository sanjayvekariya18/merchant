@extends('layouts/default')

{{-- Page title --}}
@section('title')
    Session Timeout
    @parent
@stop

{{-- page level styles --}}
@section('header_styles')
    <!--page level css -->
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/session_timeout.css')}}">
    <!--end of page level css-->
@stop

{{-- Page content --}}
@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <!--section starts-->
        <h1>Session Timeout</h1>
        <ol class="breadcrumb">
            <li>
                <a href="index">
                    <i class="fa fa-fw fa-home"></i> Dashboard
                </a>
            </li>
            <li>
                <a href="#">UI Features</a>
            </li>
            <li class="active">
                Session Timeout
            </li>
        </ol>
    </section>
    <!-- Main content -->
    <!-- Main content -->
    <section class="content">
        <!-- BEGIN SAMPLE PORTLET CONFIGURATION MODAL FORM-->
        <div class="modal fade" id="portlet-config" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                        <h4 class="modal-title">Modal title</h4>
                    </div>
                    <div class="modal-body">Widget settings form goes here</div>
                    <div class="modal-footer">
                        <button type="button" class="btn blue">Save changes</button>
                        <button type="button" class="btn default" data-dismiss="modal">Close</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!-- /.modal -->
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h3 class="panel-title">
                            <i class="fa fa-fw fa-key"></i> Session Timeout
                        </h3>
                        <span class="pull-right">
                                    <i class="fa fa-fw fa-chevron-up clickable"></i>
                                    <i class="fa fa-fw fa-times removepanel clickable"></i>
                                </span>
                    </div>
                    <div class="panel-body">
                        <div class="note note-success">
                            <p>
                                After a set amount of time(10 seconds set for demo), a dialog is shown to the user
                                with the option to either log out now, or stay connected. If log out now is
                                selected, the page is redirected to a logout URL. If stay connected is selected, a
                                keep-alive URL is requested through AJAX. If no options is selected after another
                                set amount of time, the page is automatically redirected to a timeout URL. To learn
                                more please check out
                                <a href="https://github.com/maxfierke/jquery-sessionTimeout-bootstrap"
                                   target="_blank">the plugin's official homepage</a>
                            </p>
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
    <script type="text/javascript" src="{{asset('assets/js/jquery.sessionTimeout.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('assets/js/custom_js/session_timeout.js')}}" ></script>
    <!-- end of page level js -->
@stop