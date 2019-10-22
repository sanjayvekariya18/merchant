@extends('layouts/default')

{{-- Page title --}}
@section('title')
    Deleted Users
    @parent
@stop

{{-- page level styles --}}
@section('header_styles')
    <!--page level css -->
    <link type="text/css" rel="stylesheet" href="{{asset('assets/vendors/jasny-bootstrap/css/jasny-bootstrap.css')}}" >
    <link type="text/css" rel="stylesheet" href="{{asset('assets/css/custom_css/wizard.css')}}">
    <!--end of page level css -->
@stop

{{-- Page content --}}
@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Deleted Users
        </h1>
        <ol class="breadcrumb">
            <li>
                <a href="index">
                    <i class="fa fa-fw fa-home"></i> Dashboard
                </a>
            </li>
            <li><a href="#"> Users</a></li>
            <li class="active">
                Deleted Users
            </li>
        </ol>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="panel panel-warning">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <i class="fa fa-fw fa-user" data-size="18" data-c="#ffffff" data-hc="#ffffff"></i> Deleted
                        Users List
                    </h4>
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="table">
                            <thead>
                            <tr class="filters">
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>User E-mail</th>
                                <th>Created At</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>Adele</td>
                                <td>Becker</td>
                                <td>otis98@hotmail.com</td>
                                <td>2 weeks ago</td>
                                <td>
                                    <a href="users"><i class="fa fa-users" data-c="#428BCA" data-hc="#428BCA"
                                                                 data-size="18" title="Restore"></i></a>
                                </td>
                            </tr>
                            <tr>
                                <td>Adan</td>
                                <td>Schmeler</td>
                                <td>arnoldo19@gmail.com</td>
                                <td>2 weeks ago</td>
                                <td>
                                    <a href="users"><i class="fa fa-users" data-c="#428BCA" data-hc="#428BCA"
                                                                 data-size="18" title="Restore"></i></a>
                                </td>
                            </tr>
                            <tr>
                                <td>Abbey</td>
                                <td>Conn</td>
                                <td>lehner.rhianna@christiansenwill.info</td>
                                <td>2 weeks ago</td>
                                <td>
                                    <a href="users"><i class="fa fa-users" data-c="#428BCA" data-hc="#428BCA"
                                                                 data-size="18" title="Restore"></i></a>
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
    <script type="text/javascript" src="{{asset('assets/vendors/moment/js/moment.min.js')}}" ></script>
    <script type="text/javascript" src="{{asset('assets/vendors/jasny-bootstrap/js/jasny-bootstrap.js')}}"></script>
    <script type="text/javascript" src="{{asset('assets/vendors/bootstrapwizard/js/jquery.bootstrap.wizard.js')}}" ></script>
    <script type="text/javascript" src="{{asset('assets/vendors/bootstrapvalidator/js/bootstrapValidator.min.js')}}"></script>
    <!-- end of page level js -->
@stop
