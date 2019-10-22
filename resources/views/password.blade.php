@extends('layouts/default')

{{-- Page title --}}
@section('title')
    Customers
    @parent
@stop

{{-- page level styles --}}
@section('header_styles')
    <!--page level css -->
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/jasny-bootstrap/css/jasny-bootstrap.css')}}" />
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/bootstrap-switch/css/bootstrap-switch.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/bootstrapvalidator/css/bootstrapValidator.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datetimepicker/css/bootstrap-datetimepicker.min.css')}}" >
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/iCheck/css/all.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom_css/wizard.css')}}" />
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/select2/css/select2.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/select2/css/select2-bootstrap.css')}}">
    <link href="{{asset('assets/vendors/toastr/css/toastr.min.css')}}" rel="stylesheet"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/jquerylabel/css/jquery-labelauty.css')}}"/>   
    
    <!--end of page level css-->
@stop
@section('content')
<section class="content-header">
    <h1>Password</h1>
    <ol class="breadcrumb">
        <li>
            <a href="index ">
                <i class="fa fa-fw fa-home"></i> Dashboard
            </a>
        </li>
        <li>
            <a href="#"> Users</a>
        </li>
        <li class="active">
            Change Password
        </li>
    </ol>
</section>
<br>
<!-- Main content -->
<section class="content">
    <form id="password_form"  action = '{!!url("password")!!}' method = 'POST' class="form-horizontal" role="form">
        <div class="row">
            <div class="col-md-12">
                <button type="submit" id="submitBtn" name="submitBtn" value="Save" class='btn btn-primary btn-inline'>Save</button>                               
            </div>
        </div>
        <br/>
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h3 class="panel-title">
                            <i class="livicon" data-name="user-add" data-size="18" data-c="#fff" data-hc="#fff"
                               data-loop="true"></i> Change Password
                        </h3>
                        <span class="pull-right clickable">
                            <i class="glyphicon glyphicon-chevron-up"></i>
                        </span>
                    </div>
                    <div class="panel-body">
                        <input type = 'hidden' name = '_token' value = '{{Session::token()}}'>
                        <div id="pager_wizard">
                            <h2 class="hidden">&nbsp;</h2>
                            <div class="form-group">
                                <label for="Password" class="col-sm-2 control-label">
                                    Password                                    
                                </label>
                                <div class="col-sm-5">
                                    <input id="password" name="password" type="password" class="form-control" placeholder="Enter Password">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="PasswordConfirm" class="col-sm-2 control-label">Password Confirm</label>
                                <div class="col-sm-5">
                                    <input type="password" name="confirmpassword" class="form-control" placeholder="Enter Confirm Password">
                                </div>
                            </div>                             
                        </div>                        
                    </div>
                </div>
            </div>
        </div>
    </form>
</section>
@endsection
{{-- page level scripts --}}
@section('footer_scripts')
<!-- begining of page level js -->
<script type="text/javascript" src="{{asset('assets/vendors/moment/js/moment.min.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/jasny-bootstrap/js/jasny-bootstrap.js')}}" ></script>
<script type="text/javascript" src="{{asset('assets/vendors/bootstrapwizard/js/jquery.bootstrap.wizard.js')}}" ></script>
<script type="text/javascript" src="{{asset('assets/vendors/bootstrapvalidator/js/bootstrapValidator.min.js')}}" ></script>
<script type="text/javascript" src="{{asset('assets/vendors/toastr/js/toastr.min.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/jquerylabel/js/jquery-labelauty.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/js/custom_js/password.js')}}"></script>
<!-- end of page level js -->
@stop