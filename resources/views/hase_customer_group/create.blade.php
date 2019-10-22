@extends('layouts/default')

{{-- Page title --}}
@section('title')
    Create Customer Group
    @parent
@stop

{{-- page level styles --}}
@section('header_styles')
    <!--page level css -->
    
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/bootstrapvalidator/css/bootstrapValidator.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/iCheck/css/all.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/bootstrap-switch/css/bootstrap-switch.css')}}"/>

    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/select2/css/select2.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/select2/css/select2-bootstrap.css')}}">
    
    <!--end of page level css-->
@stop
@section('content')
<section class="content-header">
    <h1>Create Customer Group</h1>
    <ol class="breadcrumb">
        <li>
            <a href="index ">
                <i class="fa fa-fw fa-home"></i> Dashboard
            </a>
        </li>
        <li>
            <a href="#"> Usersp</a>
        </li>
        <li class="active">
            Create Customer Group
        </li>
    </ol>
</section>
<br>
<section class="content">
    <form method = 'POST' enctype="multipart/form-data" class="form-horizontal bv-form" action='{!!url("hase_customer_group")!!}' id="create_customer_group_form">
        <input type = 'hidden' name = '_token' value = '{{Session::token()}}'>
        <div class="row">
            <div class="col-md-12">
                <button type="submit" name="submitBtn" value="Save" class='btn btn-primary btn-inline'>Save</button>
                <button type="submit" name="submitBtn" value="SaveClose" class = 'btn btn-primary btn-inline'>Save &amp; close</button>
                <a href="{!!url('hase_customer_group')!!}" class='btn btn-primary btn-inline'>
                    <i class="fa fa-fw fa-arrow-left"></i>
                </a>
            </div>
        </div>
        <br/>
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h3 class="panel-title">
                            <i class="livicon" data-name="user-add" data-size="18" data-c="#fff" data-hc="#fff"
                               data-loop="true"></i> Create Customer Group
                        </h3>
                        <span class="pull-right clickable">
                            <i class="glyphicon glyphicon-chevron-up"></i>
                        </span>
                    </div>
                    <div class="panel-body">
                        
                        <div class="form-group">
                            <label for="group_name" class="col-sm-3 control-label">Name*</label>
                            <div class="col-sm-8">
                                <input id="group_name" name = "group_name" type="text" class="form-control"> 
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="approval" class="col-sm-3 control-label">Approval<span class="help-block">Select a file to update menu image, otherwise leave blank.</span></label>
                            <div class="col-sm-8">
                                <input class="switch form-control" data-on-color="success" data-off-color="danger" data-on-text="Enabled" data-off-text="Disabled" id="approval" name="approval" type="checkbox" value="0" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="description" class="col-sm-3 control-label">Description*</label>
                            <div class="col-sm-8">
                                <textarea id="description" name = "description" rows="4" class="form-control resize_vertical" placeholder="Description"></textarea> 
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
<script type="text/javascript" src="{{asset('assets/vendors/bootstrapwizard/js/jquery.bootstrap.wizard.js')}}" ></script>
<script type="text/javascript" src="{{asset('assets/vendors/bootstrapvalidator/js/bootstrapValidator.min.js')}}" ></script>
<script type="text/javascript" src="{{asset('assets/vendors/bootstrap-switch/js/bootstrap-switch.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/select2/js/select2.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/js/custom_js/haseCustomerGroupCreate.js')}}"></script>
<!-- end of page level js -->
@stop


