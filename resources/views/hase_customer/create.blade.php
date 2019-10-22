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
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom_css/wizard.css')}}" />
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/select2/css/select2.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/select2/css/select2-bootstrap.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/jquerylabel/css/jquery-labelauty.css')}}"/>   

    <link rel="stylesheet" href="{{asset('assets/kendoui/styles/kendo.common.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/kendoui/styles/kendo.rtl.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/kendoui/styles/kendo.default.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/kendoui/styles/kendo.mobile.all.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/kendoui/styles/kendo.blueopal.min.css')}}" /> 

    <!--end of page level css-->
@stop
@section('content')
<section class="content-header">
    <h1>Create Customers</h1>
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
            Create Customer Index
        </li>
    </ol>
</section>
<br>
<!-- Main content -->
<section class="content">
    <form method = 'POST' enctype="multipart/form-data" class="form-horizontal bv-form" action='{!!url("hase_customer")!!}' id="create_customer_form">
        <input type = 'hidden' name = '_token' value = '{{Session::token()}}'>
        <input type="hidden" name="requestUrl" id="requestUrl" value="{!!url(Request::segment(1))!!}">
        <input type="hidden" name="actionUrl" id="actionUrl" value="{!!url(Request::segment(1))!!}">

        <input id="ip_address" name="ip_address" type="hidden" value="{{$currentIp}}" />
        <input id="date_added" name="date_added" type="hidden" value="{{$createdAt}}" />
        <div class="row">
            <div class="col-md-12">
                <button type="submit" name="submitBtn" value="Save" class='btn btn-primary btn-inline'>Save</button>
                <button type="submit" name="submitBtn" value="SaveClose" class = 'btn btn-primary btn-inline'>Save &amp; close</button>
                <a href="{!!url('hase_customer')!!}" class='btn btn-primary btn-inline'>
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
                               data-loop="true"></i> Create Customer
                        </h3>
                        <span class="pull-right clickable">
                            <i class="glyphicon glyphicon-chevron-up"></i>
                        </span>
                    </div>
                    <div class="panel-body">
                        <input type = 'hidden' name = '_token' value = '{{Session::token()}}'>
                        <div id="pager_wizard">
                            <h2 class="hidden">&nbsp;</h2>
                            <?php if(Session('merchantId') != 0): ?>
                            <div class="form-group">
                                <label for="merchant_type_id" class="col-sm-3 control-label">Merchant Type</label>
                                <div class="col-sm-5">
                                    <select name="merchant_type_id" id="merchant_type_id" class="form-control select21" style="width:100%">
                                        <option></option>
                                        @foreach($merchant_parent_types as $merchant_parent_type)
                                                <option value="{{$merchant_parent_type->merchant_type_id}}">{{$merchant_parent_type->merchant_type_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <?php endif; ?>
                            <?php if(Session('merchantId') == 0){ ?>
                            <div class="form-group">
                                <label for="merchant_id" class="col-sm-3 control-label">Merchant Name</label>
                                <div class="col-sm-5">
                                    <select name="merchant_id" id="merchant_id" class="form-control select21" style="width:100%">
                                        <option></option>
                                        @foreach($merchants as $merchant)
                                           <option value="{{$merchant->merchant_id}}">{{$merchant->merchant_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <?php }else{ ?>
                            <div class="form-group">
                                <label for="merchant_id" class="col-sm-3 control-label">Merchant Name</label>
                                <div class="col-sm-5">
                                    <select name="merchant_id" id="merchant_id" class="form-control select21" style="width:100%">
                                        <option></option>
                                    </select>
                                </div>
                            </div>
                            <?php } ?>
                            <div class="form-group">
                                <label for="first_name" class="col-sm-3 control-label">First Name*</label>
                                <div class="col-sm-5">
                                    <input id="first_name" name = "first_name" type="text" class="form-control" > 
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="last_name" class="col-sm-3 control-label">Last Name*</label>
                                <div class="col-sm-5">
                                    <input id="last_name" name = "last_name" type="text" class="form-control" >
                                </div>     
                            </div>
                            <div class="form-group">
                                <label for="email" class="col-sm-3 control-label">email*</label>
                                <div class="col-sm-5">
                                    <input id="email" name = "email" type="text" class="form-control" > 
                                </div>
                            </div>
                            <!-- <div class="form-group">
                                <label for="password" class="col-sm-3 control-label">password
                                <div class="col-sm-5">
                                    <input id="password" name = "password" type="password" class="form-control" value="">
                                </div>
                            </div> 
                            <div class="form-group">
                                <label for="security_question_id" class="col-sm-3 control-label">Security Question</label>
                                <div class="col-sm-5"> 
                                    <select name="security_question_id" id="security_question_id" class = "form-control select21">
                                        @foreach($hase_security_questions as $hase_security_question)
                                        <option value="{{$hase_security_question['question_id']}}">{{$hase_security_question['text']}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="security_answer" class="col-sm-3 control-label">Security Answer</label>
                                <div class="col-sm-5">
                                    <input id="security_answer" name = "security_answer" type="text" class="form-control" > 
                                </div>
                            </div> -->
                            <div class="form-group">
                                <label for="customer_group_id" class="col-sm-3 control-label">Customer Group</label>
                                <div class="col-sm-5">
                                    <select name="customer_group_id[]" id="customer_group_id" class = "form-control select21" multiple="true">
                                        @foreach($hase_customer_groups as $hase_customer_group)
                                       <option value="{{$hase_customer_group->group_id}}">{{$hase_customer_group->group_name}}</option>
                                        @endforeach
                                    </select>
                                </div> 
                            </div>
                            <div class="form-group">
                                <label for="Username" class="col-sm-3 control-label">
                                    Username
                                    <span class="help-block">
                                        Username can not be changed.
                                    </span>
                                </label>
                                <div class="col-sm-5">
                                    <input id="username" name="username" type="text" class="form-control" placeholder="Enter Username">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="newsletter" class="col-sm-3 control-label">Newsletter</label>
                                <div class="make-switch col-sm-5" data-on="danger" data-off="default">
                                    <input class="switch form-control" data-on-color="success" data-off-color="danger" data-on-text="Subscribe" data-off-text="Un-subscribe" id="newsletter" name="newsletter" type="checkbox" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="status" class="col-sm-3 control-label">Status</label>
                                <div class="make-switch col-sm-5" data-on="danger" data-off="default">
                                    <input class="switch form-control" data-on-color="success" data-off-color="danger" data-on-text="Enabled" data-off-text="Disabled" id="status" name="status" type="checkbox" />
                                </div> 
                            </div>
                            <div class="form-group">
                                <label for="topologyTree" class="col-sm-3 control-label">Locations</label>
                                <div class="col-sm-5" style="background-color: aliceblue;padding: 15px 10px;margin-left: 15px;">
                                    <select id="region_id" name="region_id[]" style="width: 100%"></select>
                                    <br>
                                    <div style="height:200px;overflow-y:scroll;">
                                        <div id="topologyTree"></div>   
                                    </div>
                                    <span id="treeError" style="color: red;display: none;">Please select at least one Node</span>
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
<script type="text/javascript" src="{{asset('assets/vendors/select2/js/select2.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/bootstrapwizard/js/jquery.bootstrap.wizard.js')}}" ></script>
<script type="text/javascript" src="{{asset('assets/vendors/bootstrapvalidator/js/bootstrapValidator.min.js')}}" ></script>
<script type="text/javascript" src="{{asset('assets/vendors/datetimepicker/js/bootstrap-datetimepicker.min.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/iCheck/js/icheck.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/bootstrap-switch/js/bootstrap-switch.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/jquerylabel/js/jquery-labelauty.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/kendoui/js/kendo.all.min.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/js/custom_js/haseCustomerCreate.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/js/custom_js/Address.js')}}"></script>
<!-- end of page level js -->
@stop