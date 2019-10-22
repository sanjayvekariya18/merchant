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

    <link rel="stylesheet" href="{{asset('assets/kendoui/styles/kendo.common.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/kendoui/styles/kendo.rtl.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/kendoui/styles/kendo.default.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/kendoui/styles/kendo.mobile.all.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/kendoui/styles/kendo.blueopal.min.css')}}" /> 

    <style type="text/css">

    #identityCityListForm label{
        font-weight: bold;
    }

    .k-i-close{
        margin: 1px 0 !important;
    }

    #tabstrip div.row{
        margin: 10px 0px;
    }

    #locationInfo thead tr{
        width: 100%;
        background-color: #d9ecf5;
        color: #003f59
    }
    </style>
    <!--end of page level css-->
@stop
@section('content')
<section class="content-header">
    <h1>Edit Customers</h1>
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
            Edit Customer Index
        </li>
    </ol>
</section>
<br>
<!-- Main content -->
<section class="content">
    
        <div class="row">
            <div class="col-md-12">                
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
                               data-loop="true"></i> Edit Customer
                        </h3>
                        <span class="pull-right clickable">
                            <i class="glyphicon glyphicon-chevron-up"></i>
                        </span>
                    </div>
                    <div class="panel-body">
                        
                        <div id="pager_wizard">
                            <ul class="nav nav-tabs">
                                <li>
                                    <a href="#tab1" data-toggle="tab">Customer Profile</a>
                                </li>
                                <li>
                                    <a href="#tab2" data-toggle="tab">Address</a>
                                </li>
                            </ul>                           
                            
                            <div class="tab-content">
                                <div class="tab-pane" id="tab1">
                                    <h2 class="hidden">&nbsp;</h2>
                                    <div id="option_pager_wizard" class="tab-pane row wrap-all active">
                                        <form method = 'POST' enctype="multipart/form-data" class="form-horizontal bv-form" action='{!!url("hase_customer")!!}/{!!$hase_customer->customer_id!!}/update' id="edit_customer_form">
                                        <input id="customer_id" name = "customer_id" type="hidden" class="form-control" value="{!!$hase_customer->customer_id!!}"/> 
                                        <input id="identity_id" name = "identity_id" type="hidden" class="form-control" value="{!!$hase_customer->
                                            identity_id!!}"/>  
                                        <input id="identity_table_id" name = "identity_table_id" type="hidden" class="form-control" value="4"/>   
                                        <input id="google2fa_enable_status" name = "google2fa_enable_status" type="hidden" class="form-control" value="{!!$hase_customer->google2fa_enable!!}"/>  
                                        <input id="ip_address" name="ip_address" type="hidden" value="{{$currentIp}}" />
                                        <input id="totalAddressAdded" name="totalAddressAdded" type="hidden" value="{{$totalAddressAdded}}" />
                                        <input type = 'hidden' name = '_token' value = '{{Session::token()}}'/>
                                        <input type="hidden" name="requestUrl" id="requestUrl" value="{!!url(Request::segment(1))!!}"/>
                                        <input type="hidden" name="actionUrl" id="actionUrl" value="{!!url(Request::segment(1))!!}"/>                                            
                                            
                                        <div class="form-group">
                                            <label for="first_name" class="col-sm-2 control-label">First Name*</label>
                                            <div class="col-sm-8">
                                                <input id="first_name" name = "first_name" type="text" class="form-control" value="{!!$hase_customer->fname!!}"> 
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="last_name" class="col-sm-2 control-label">last_name*</label>
                                            <div class="col-sm-8">
                                                <input id="last_name" name = "last_name" type="text" class="form-control" value="{!!$hase_customer->lname!!}">
                                            </div>     
                                        </div>                                    
                                        <div class="form-group">
                                            <label for="email" class="col-sm-2 control-label">email*</label>
                                            <div class="col-sm-8">
                                                <input id="email" name = "email" type="text" class="form-control" value="{!!$hase_customer->email!!}"> 
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="customer_code" class="col-sm-2 control-label">Customer Code*</label>
                                            <div class="col-sm-8">
                                                <input id="customer_code" name = "customer_code" type="text" class="form-control" value="{!!$hase_customer->customer_code!!}">
                                            </div>     
                                        </div>                                           
                                        <!--<div class="form-group">
                                            <label for="password" class="col-sm-2 control-label">password
                                            <div class="col-sm-8">
                                                <input id="password" name = "password" type="password" class="form-control" value="{!!$hase_customer->password!!}">
                                            </div>
                                        </div>                                                                           
                                        <div class="form-group">
                                            <label for="security_question_id" class="col-sm-2 control-label">Security Question</label>
                                            <div class="col-sm-8"> 
                                                <select name="security_question_id" id="security_question_id" class = "form-control selectLoad">
                                                    @foreach($hase_security_questions as $hase_security_question)
                                                    <option value="{{$hase_security_question['question_id']}}" <?php // echo ($hase_customer->security_question_id == $hase_security_question['question_id'])?"selected":"" ?>>{{$hase_security_question['text']}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="security_answer" class="col-sm-2 control-label">Security Answer</label>
                                            <div class="col-sm-8">
                                                <input id="security_answer" name = "security_answer" type="text" class="form-control" value="{!!$hase_customer->security_answer!!}"> 
                                            </div>
                                        </div> -->
                                        <div class="form-group">
                                            <label for="customer_group_id" class="col-sm-2 control-label">Customer Group</label>
                                            <div class="col-sm-8">
                                                <select name="customer_group_id[]" id="customer_group_id" class = "form-control selectLoad" multiple="true" <?= (Session::get('role') <> 1) ? "disabled" : "" ?>>
                                                     @foreach($hase_customer_groups as $hase_customer_group)
                                        <option value="{{$hase_customer_group->group_id}}"
                                            <?php
                                                if (in_array($hase_customer_group->group_id, $group_list)){
                                                   echo "selected";     
                                                }
                                            ?>
                                        >{{$hase_customer_group->group_name}}</option>
                                    @endforeach
                                                </select>
                                            </div> 
                                        </div>
                                        <div class="form-group">
                                                <label for="Username" class="col-sm-2 control-label">
                                                    Username          
                                                </label>
                                                <div class="col-sm-8">
                                                    <input id="username" name="username" type="text" class="form-control" value="{!!$hase_customer->username!!}" placeholder="Enter Username" disabled="">
                                                    <input type="hidden" name="user_id" id="user_id" value="{{$hase_customer->user_id}}">
                                                </div>
                                       </div>
                                       <?php if(Session::get('staffId') == $hase_customer->customer_id) { ?>
                                       <div class="form-group">
                                            <label for="Password" class="col-sm-2 control-label">
                                                Password
                                                <span class="help-block">
                                                    Leave blank to leave password unchanged
                                                </span>
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
                                        <?php } ?>                                        
                                        <div class="form-group">
                                            <label for="newsletter" class="col-sm-2 control-label">Newsletter</label>
                                            <div class="make-switch col-sm-8" data-on="danger" data-off="default">
                                                <input type="checkbox" id="newsletter" name="newsletter" value="{!!$hase_customer->newsletter!!}" checked="true">
                                            </div>
                                        </div>
                                        @if($user_id == 1) 
                                        <div class="form-group">
                                            <label for="status" class="col-sm-2 control-label">Status</label>
                                            <div class="make-switch col-sm-8" data-on="danger" data-off="default">
                                                <input id="status" name="status" type="checkbox" value="{!!$hase_customer->status!!}" checked="true"/>
                                            </div> 
                                        </div>
                                        @endif
                                        @if($user_id == 1)  
                                        <div class="form-group">
                                                <label for="google2fa_enable" class="col-sm-2 control-label">2FA Status</label>                              
                                                <div class="make-switch col-sm-8" data-on="danger" data-off="default">
                                                    <input id="google2fa_enable" name="google2fa_enable" type="checkbox" value="{!!$hase_customer->google2fa_enable!!}"/>
                                                </div>                                                
                                            </div>
                                        @endif  
                                        <div class="form-group">
                                                <label class="col-sm-2 control-label"></label>
                                                <button type="submit" name="submitBtn" value="Save" class='btn btn-primary btn-inline' style="margin-left: 15px;">Save</button>
                                                <button type="submit" name="submitBtn" value="SaveClose" class = 'btn btn-primary btn-inline'>Save &amp; close</button>
                                        </div>
                                        </form>
                                    </div>
                                </div>                            
                                <div class="tab-pane" id="tab2">
                                    <h2 class="hidden">&nbsp;</h2>
                                    <div id="option_pager_wizard" class="tab-pane row wrap-all active">
                                        <form method='POST' action='' id="identityCityListForm" name="identityCityListForm" class="form-horizontal bv-form">
                                        <input type = 'hidden' name = '_token' value = '{{Session::token()}}'>
                                        <input name = "identity_id" type="hidden" class="form-control" value="{!!$hase_customer->identity_id!!}">
                                        <input name = "identity_table_id" type="hidden" class="form-control" value="4"> 
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
                                        <div class="form-group">
                                            <div class="col-md-3"></div>
                                            <div class="col-md-2">
                                                <button type="button" id="submitLocationBtn" class="send-btn k-button">Add Location</button>
                                            </div>
                                        </div>  
                                        </form>
                                        <!-- CUSTOMER CITY LIST GRID -->
                                        <div id="identityCityListGrid"></div>
                                    </div>
                                <div>
                            </div>
                        </div>                       
                    </div>
                </div>
            </div>
        </div>

        <!-- form-modal -->
        <div id="top_modal" class="modal fade animated position_modal" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header" style="background-color: #d9ecf5;">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Merchant Location</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <form method='POST' action='{!!url("location")!!}' id="locationForm">
                                    {{ csrf_field() }}
                                    <div class="preloader" style="background: none !important; ">
                                        <div class="loader_img">
                                            <img src="{{asset('assets/img/loader.gif')}}" alt="loading..." height="64" width="64">
                                        </div>
                                    </div>
                                    <div class="table-responsive">
                                        <table id=locationInfo class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Country</th>
                                                    <th>State</th>
                                                    <th>County</th>
                                                    <th>City</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td id="countryName"></td>
                                                    <td id="stateName"></td>
                                                    <td id="countyName"></td>
                                                    <td id="cityName"></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div id="tabstrip">
                                        <ul>
                                            <li class="k-state-active">Postal</li>
                                            <li><i class="fa fa-plus addTab"></i></li>
                                        </ul>
                                        <div id="tabstrip-1">
                                            <div class="tab-content">
                                                <div class="row">
                                                    <div class="form-group">
                                                        <label for="subpremise" class="col-sm-2">Address 1</label>
                                                        <div class="col-sm-5">
                                                            <input id="subpremise" name="postals[0][subpremise]" type="text" class="form-control k-textbox" required validationMessage="Address Required">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="form-group">
                                                        <label for="premise" class="col-sm-2">Address 2</label>
                                                        <div class="col-sm-5">
                                                            <input id="premise" name="postals[0][premise]" type="text" class="form-control k-textbox" />
                                                        </div>
                                                        <div class="col=md-4">
                                                            <button type="button" value="0" class="btn btn-success getLocation">Get Location</button>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row postal" style="display: none;">
                                                    <div class="form-group">
                                                        <label for="street_number1" class="col-sm-2">Street Number</label>
                                                        <div class="col-sm-5">
                                                            <input id="street_number1" style="width: 100%" />
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row postal" style="display: none;">
                                                    <div class="form-group">
                                                        <label for="route1" class="col-sm-2">Route</label>
                                                        <div class="col-sm-5">
                                                            <input id="route1" style="width: 100%" />
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row postal" style="display: none;">
                                                    <div class="form-group">
                                                        <label for="neighborhood1" class="col-sm-2">Neighborhood</label>
                                                        <div class="col-sm-5">
                                                            <input style="width: 100%" id="neighborhood1" />
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row postal-max" style="display: none;">
                                                    <div class="form-group">
                                                        <label for="postcode1" class="col-sm-2">Postcode</label>
                                                        <div class="col-sm-5">
                                                            <input style="width: 100%" id="postcode1" />
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row postal" style="display: none;">
                                                    <div class="form-group">
                                                        <label for="lat" class="col-sm-2">Latitude</label>
                                                        <div class="col-sm-5">
                                                            <input type="text" class="form-control k-textbox" name="postals[0][lat]" id="lat" style="width: 100%" />
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row postal" style="display: none;">
                                                    <div class="form-group">
                                                        <label for="lng" class="col-sm-2">Longitude</label>
                                                        <div class="col-sm-5">
                                                            <input type="text" class="form-control k-textbox" name="postals[0][lng]" id="lng" style="width: 100%" />
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- <div class="form-group">
                                                    <label for="country_id" class="col-sm-2">Country</label>
                                                    <div class="col-sm-5">
                                                        <input type="text" id="country_id" name="merchant_id" style="width: 100%" disabled="" />
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label for="state_id" class="col-sm-2">State</label>
                                                    <div class="col-sm-5">
                                                        <input type="text" id="state_id" name="state_id" style="width: 100%" disabled="" />
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label for="county_id" class="col-sm-2">County</label>
                                                    <div class="col-sm-5">
                                                        <input type="text" id="county_id" name="county_id" style="width: 100%" disabled="" />
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label for="city_id" class="col-sm-2">City</label>
                                                    <div class="col-sm-5">
                                                        <input type="text" id="city_id" name="city_id" style="width: 100%" disabled="" />
                                                    </div>
                                                </div> -->
                                                <input type="hidden" id="postalStreetNumber1" name="postals[0][street_number]" />
                                                <input type="hidden" id="postalRoute1" name="postals[0][route]" />
                                                <input type="hidden" id="postalNeighborhood1" name="postals[0][neighborhood]" />
                                                <input type="hidden" id="postalPostcode1" name="postals[0][postcode]" />


                                                <input type="hidden" id="list_id" name="postals[0][list_id]" value="" >
                                                <input type="hidden" id="postal_id" name="postals[0][postal_id]" value="" >
                                            </div>
                                        </div>
                                        <div style="display: none;"></div>
                                    </div>
                                    <div class="modal-footer" style="padding-left: 33%;text-align:inherit">
                                        <button type="button" id="updateLocation" value="1" class="btn btn-success">Update</button>
                                        <button type="button" class="btn btn-danger" data-dismiss="modal">
                                            Close
                                        </button>
                                    </div>
                                    <input type="hidden" id="city_name" name="city_name" value="" >
                                    <input type="hidden" id="postal_code_max" name="postal_code_max" value="" >
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="demo" style="display: none;">
            <div class="tab-content">
                <div class="row">
                    <div class="form-group">
                        <label for="subpremise" class="col-sm-2">Address 1</label>
                        <div class="col-sm-5">
                            <input id="subpremise" type="text" class="form-control k-textbox" required validationMessage="Address Required">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group">
                        <label for="premise" class="col-sm-2">Address 2</label>
                        <div class="col-sm-5">
                            <input id="premise" type="text" class="form-control k-textbox" />
                        </div>
                        <div class="col=md-4">
                            <button type="button" value="0" class="btn btn-success getLocation">Get Location</button>
                        </div>
                    </div>
                </div>
                <div class="row postal" style="display: none;">
                    <div class="form-group">
                        <label for="street_number" class="col-sm-2">Street Number</label>
                        <div class="col-sm-5">
                            <input id="street_number" style="width: 100%" />
                        </div>
                    </div>
                </div>
                <div class="row postal" style="display: none;">
                    <div class="form-group">
                        <label for="route" class="col-sm-2">Route</label>
                        <div class="col-sm-5">
                            <input id="route" style="width: 100%" />
                        </div>
                    </div>
                </div>
                <div class="row postal" style="display: none;">
                    <div class="form-group">
                        <label for="neighborhood" class="col-sm-2">Neighborhood</label>
                        <div class="col-sm-5">
                            <input id="neighborhood" style="width: 100%"  />
                        </div>
                    </div>
                </div>
                <div class="row postal-max" style="display: none;">
                    <div class="form-group">
                        <label for="postcode" class="col-sm-2">Postcode</label>
                        <div class="col-sm-5">
                            <input id="postcode" style="width: 100%"  />
                        </div>
                    </div>
                </div>
                <div class="row postal" style="display: none;">
                    <div class="form-group">
                        <label for="lat" class="col-sm-2">Latitude</label>
                        <div class="col-sm-5">
                            <input  type="text" id="lat" class="form-control k-textbox"  style="width: 100%" />
                        </div>
                    </div>
                </div>
                <div class="row postal" style="display: none;">
                    <div class="form-group">
                        <label for="lng" class="col-sm-2">Longitude</label>
                        <div class="col-sm-5">
                            <input type="text" id="lng" class="form-control k-textbox" style="width: 100%" />
                        </div>
                    </div>
                </div>
                <input type="hidden" id="postalStreetNumber" />
                <input type="hidden" id="postalRoute" />
                <input type="hidden" id="postalNeighborhood" />
                <input type="hidden" id="postalPostcode" />

                <input type="hidden" id="list_id"  value="" >
                <input type="hidden" id="postal_id" value="" >
            </div>
        </div>
        <!-- form-modal end -->
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
<script type="text/javascript" src="{{asset('assets/vendors/toastr/js/toastr.min.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/jquerylabel/js/jquery-labelauty.js')}}"></script>

<script type="text/javascript" src="{{asset('assets/kendoui/js/kendo.all.min.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/js/custom_js/haseCustomersEdit.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/js/custom_js/Address.js')}}"></script>

<script type="text/javascript">
@if(Session::has('type'))
    toastr.options = {
        "closeButton": true,
        "positionClass": "toast-top-right",
        "showDuration": "1000",
        "hideDuration": "1000",
        "timeOut": "5000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "swing",
        "showMethod": "show"
    };
    var $toast = toastr["{{ Session::pull('type') }}"]("", "{{ Session::pull('msg') }}");
@endif

$(document).ready(function(){

    // CALLED GETCITYLIST FUNCTION FROM ADDRESS.JS

    var identity_id = $("#identity_id").val();
    var identity_table_id = 4;

    getCityList(identity_id,identity_table_id);

});

</script>
<!-- end of page level js -->
@stop