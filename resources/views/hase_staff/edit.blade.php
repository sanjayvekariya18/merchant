@extends('layouts/default')

{{-- Page title --}}
@section('title')
    Staffs
    @parent
@stop

{{-- page level styles --}}
@section('header_styles')
    <!--page level css -->
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/bootstrapvalidator/css/bootstrapValidator.min.css')}}">

    <link href="{{asset('assets/vendors/select2/css/select2.min.css')}}" rel="stylesheet" type="text/css">

    <link href="{{asset('assets/vendors/select2/css/select2-bootstrap.css')}}" rel="stylesheet" type="text/css">

    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/bootstrap-switch/css/bootstrap-switch.css')}}"/>
    <!--end of page level css-->
@stop
@section('content')

<section class="content-header">
    <h1>Edit Staff</h1>
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
            Staff
        </li>
    </ol>
</section>
<section class="content">
    <form id="staffForm"  action = '{!! url("hase_staff")!!}/{!!$hase_staff->
        staff_id!!}/update' method = 'POST' class="form-horizontal" role="form">
        <div class="preloader" style="background: none !important; ">
            <div class="loader_img">
                <img src="{{asset('assets/img/loader.gif')}}" alt="loading..." height="64" width="64">
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <button type="submit" name="submitBtn" value="Save" class='btn btn-primary btn-inline'>Save</button>
                <button type="submit" name="submitBtn" value="SaveClose" class = 'btn btn-primary btn-inline'>Save  &amp; close</button>
                <a href="{!!url("hase_staff")!!}" class='btn btn-primary btn-inline'>
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
                            <i class="fa fa-fw fa-crosshairs"></i> Staff Form
                        </h3>
                    </div>
                    <div class="panel-body">
                        <input type = 'hidden' name = '_token' value = '{{Session::token()}}'>
                        <input id ="staff_id" name = "staff_id" type="hidden" value="{!!$hase_staff->staff_id!!}">
                        <?php if($merchantId == 0): ?>
                            <div class="form-group">
                                <label for="merchant_type_id" class="col-sm-2 control-label">
                                    Merchants Type
                                </label>
                                <div class="col-sm-5">
                                    <select name="merchant_type_id" id="merchant_type_id" class="form-control select2" style="width:100%">
                                        <option></option>
                                        @foreach($hase_merchant_types as $hase_merchant_type)
                                            @if($hase_merchant_type->merchant_type_id == $hase_staff->merchant_type_id)
                                                <option value="{{$hase_merchant_type->merchant_type_id}}" selected="">{{$hase_merchant_type->merchant_type_name}}</option>
                                            @else
                                                <option value="{{$hase_merchant_type->merchant_type_id}}">{{$hase_merchant_type->merchant_type_name}}</option>
                                            @endif    
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="Merchant" class="col-sm-2 control-label">
                                    Merchants
                                </label>
                                <div class="col-sm-5">
                                    <select name="merchant_id" id="merchant_id" class="form-control select2" style="width:100%">
                                        @foreach($hase_merchants as $hase_merchant)
                                            @if($hase_merchant->merchant_id == $hase_staff->merchant_id)
                                                <option value="{{$hase_merchant->merchant_id}}" selected>{{$hase_merchant->merchant_name}}</option>
                                            @else
                                                <option value="{{$hase_merchant->merchant_id}}">{{$hase_merchant->merchant_name}}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        <?php else: ?>
                            <input type="hidden" name="merchant_id" id="merchant_id" value="{{$hase_staff->merchant_id}}">
                        <?php endif; ?>
                        <div class="form-group" id="locationBlock">
                            <label for="Parent" class="col-sm-2 control-label">
                                City
                            </label>
                            <div class="col-sm-5">
                                <select name="city_id" id="city_id" class="form-control select2" style="width:100%">
                                    @foreach($merchant_cities as $merchant_city)
                                        @if($merchant_city->city_id == $location_list->location_city_id)
                                            <option value="{{$merchant_city->city_id}}" selected>{{$merchant_city->city_name}}</option>
                                        @else
                                            <option value="{{$merchant_city->city_id}}">{{$merchant_city->city_name}}</option>
                                        @endif
                                        
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group" id="locationBlock">
                            <label for="Parent" class="col-sm-2 control-label">
                                Location
                            </label>
                            <div class="col-sm-5">
                                <select name="location_id" id="location_id" class="form-control select2" style="width:100%">
                                    @foreach($merchant_city_postals as $hase_location)
                                        @if($hase_location->location_id == $location_list->postal_id)
                                            <option value="{{$hase_location->location_id}}" selected>{{$hase_location->location_name}}</option>
                                        @else
                                            <option value="{{$hase_location->location_id}}">{{$hase_location->location_name}}</option>
                                        @endif
                                        
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="Parent" class="col-sm-2 control-label">
                                Department
                            </label>
                            <div class="col-sm-5">
                                <select name="staff_group_id[]" id="staff_group_id" class="form-control select2" multiple style="width:100%">
                                    @foreach($hase_staff_groups as $hase_staff_group)
                                        <option value="{{$hase_staff_group->group_id}}"
                                            <?php
                                                if (in_array($hase_staff_group->group_id, $group_list)){
                                                   echo "selected";     
                                                }
                                            ?>
                                        >{{$hase_staff_group->group_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="Name" class="col-sm-2 control-label">First Name</label>
                            <div class="col-sm-5">
                                <input value="{!!$hase_staff->staff_fname!!}" id="first_name" name="first_name"  type="text" class="form-control" placeholder="Enter Staff First Name">
                                <input type="hidden" name="user_id" id="user_id" value="{{$hase_staff->user_id}}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="Name" class="col-sm-2 control-label">Last Name</label>
                            <div class="col-sm-5">
                                <input value="{!!$hase_staff->staff_lname!!}" id="last_name" name="last_name"  type="text" class="form-control" placeholder="Enter Staff Last Name">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="Email" class="col-sm-2 control-label">Email</label>
                            <div class="col-sm-5">
                                <input value="{!!$hase_staff->identity_email!!}" id="staff_email" name="staff_email" type="text" class="form-control" placeholder="Enter Staff Email">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="Username" class="col-sm-2 control-label">
                                Username
                                <span class="help-block">
                                    Username can not be changed.
                                </span>
                            </label>
                            <div class="col-sm-5">
                                <input value="{!!$hase_staff->username!!}" id="username" name="username" type="text" class="form-control" placeholder="Enter Username" disabled="">
                            </div>
                        </div>
                        @if($user_id == 1)  
                        <div class="form-group">
                                <label for="google2fa_enable" class="col-sm-2 control-label">2FA Status</label>
                                <div class="col-sm-8">
                                    <div class="make-switch" data-on="danger" data-off="default">
                                        <input id="google2fa_enable" name="google2fa_enable" type="checkbox" value="{!!$hase_staff->google2fa_enable!!}" />
                                    </div>
                                </div>
                            </div>
                        @endif  
                        <?php if(Session::get('staffId') == $hase_staff->staff_id) { ?>   
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
                        @if($user_id == 1)                      
                        <div class="form-group">
                            <label for="Status" class="col-sm-2 control-label">Status</label>
                            <div class="col-sm-5">
                                <div class="make-switch" data-on="danger" data-off="default">
                                    @if($hase_staff->staff_status)
                                        <input value="{!!$hase_staff->
                                        staff_status!!}" id="staff_status" name="staff_status" type="checkbox" checked="true" />
                                    @else
                                        <input value="{!!$hase_staff->
                                        staff_status!!}" id="staff_status" name="staff_status" type="checkbox" />
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endif
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

<script type="text/javascript" src="{{asset('assets/vendors/select2/js/select2.js')}}"></script>

<script type="text/javascript" src="{{asset('assets/vendors/bootstrap-switch/js/bootstrap-switch.js')}}"></script>

<script type="text/javascript" src="{{asset('assets/vendors/bootstrapvalidator/js/bootstrapValidator.min.js')}}" ></script>

<script type="text/javascript" src="{{asset('assets/js/custom_js/HaseStaffEdit.js')}}"></script>

<!-- end of page level js -->
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
</script>
@stop