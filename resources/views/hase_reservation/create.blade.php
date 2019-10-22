@extends('layouts/default')

{{-- Page title --}}
@section('title')
    Create Reservation
    @parent
@stop

{{-- page level styles --}}
@section('header_styles')
    <!--page level css -->
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/select2/css/select2.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/select2/css/select2-bootstrap.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/bootstrap-switch/css/bootstrap-switch.css')}}"/>
    <link href="{{asset('assets/vendors/airdatepicker/css/datepicker.min.css')}}" rel="stylesheet" type="text/css">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/timedropper/css/timedropper.css')}}">
    <!--end of page level css-->
@stop
@section('content')
<style type="text/css">
input[type=number]::-webkit-inner-spin-button, 
input[type=number]::-webkit-outer-spin-button { 
    -webkit-appearance: none;
    -moz-appearance: none;
    appearance: none;
    margin: 0; 
}
.tab-pane-title {
    padding: 0 0 10px;
    margin: 25px 0px;
    border-bottom: 1px solid rgb(236, 240, 245);
    font-size: 20px;
}
</style>
<section class="content-header">
    <h1>Create Reservation</h1>
    <ol class="breadcrumb">
        <li>
            <a href="index ">
                <i class="fa fa-fw fa-home"></i> Dashboard
            </a>
        </li>
        <li>
            <a href="#"> Table Reservation</a>
        </li>
        <li class="active">
            Create Reservation
        </li>
    </ol>
</section>
<section class="content">
    <div class="preloader" style="background: none !important; ">
        <div class="loader_img">
            <img src="{{asset('assets/img/loader.gif')}}" alt="loading..." height="64" width="64">
        </div>
    </div>
    <form id="reservation_tables_form" method = 'POST' action = '{!!url("hase_reservation")!!}' class="form-horizontal bv-form">
        <input type = 'hidden' name = '_token' value = '{{Session::token()}}'>
        <input type="hidden" name="requestUrl" id="requestUrl" value="{!!url(Request::segment(1))!!}">
        <div class="row">
            <div class="col-md-12">
                <button type="submit" name="submitbutton" value="Save" class='btn btn-primary btn-inline'>Save</button>
                <button type="submit" name="submitbutton" value="SaveClose" class= 'btn btn-primary btn-inline'>Save &amp; Close</button>
                <a href="{!!url("hase_reservation")!!}" class='btn btn-primary btn-inline'>
                    <i class="fa fa-angle-double-left"></i>
                </a>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h3 class="panel-title">
                            <i class="livicon" data-name="user-add" data-size="18" data-c="#fff" data-hc="#fff" data-loop="true"></i> Add New Reservation
                        </h3>
                        <span class="pull-right">
                            <i class="fa fa-fw fa-chevron-up clickable"></i>
                        </span>
                    </div>
                    <div class="panel-body">
                        <ul class="nav nav-tabs ">
                            <li class="active">
                                <a href="#tab1" data-toggle="tab">
                                    Reservation
                                </a>
                            </li>
                        </ul>
                        <div class="tab-content m-t-10">
                            <div id="tab1" class="tab-pane fade active in">
                                <h4 class="tab-pane-title">Basic</h4>
                                <?php if(Session('merchantId') == 0): ?>
                                <div class="form-group">
                                    <label for="merchant_id" class="col-sm-2 control-label">Merchant</label>
                                    <div class="col-sm-5"> 
                                        <select name="merchant_id" id="merchant_id" class = "form-control select21">
                                            <option></option>
                                            @foreach($hase_merchants as $hase_merchant)
                                                <option value="{{$hase_merchant->merchant_id}}">{{$hase_merchant->merchant_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <?php else: ?>
                                    <input type="hidden" name="merchant_id" id="merchant_id" value="{{$hase_merchants->merchant_id}}">
                                <?php endif; ?>
                                <div class="form-group">
                                    <label for="city_id" class="col-sm-2 control-label">City</label>
                                    <div class="col-sm-5"> 
                                        <select name="city_id" id="city_id" class="form-control select21" style="width:100%">
                                            <option></option>
                                            @foreach($merchant_cities as $merchant_city)
                                                <option value="{{$merchant_city->city_id}}">{{$merchant_city->city_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="location_id" class="col-sm-2 control-label">Location</label>
                                    <div class="col-sm-5"> 
                                        <select name="location_id" id="location_id" class="form-control select21" style="width:100%">
                                            <option></option>
                                            @foreach($merchant_city_postals as $merchant_city_postal)
                                                <option value="{{$merchant_city_postal->location_id}}">{{$merchant_city_postal->location_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="seating_id" class="col-sm-2 control-label">Seating Name</label>
                                    <div class="col-sm-5"> 
                                        <select name="seating_id" id="seating_id" class="form-control select21" style="width:100%">
                                            <option></option>
                                            @foreach($seatings as $seating)
                                                <option value="{{$seating->seating_id}}">{{$seating->seating_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <input type="hidden" name="occasion_id" id="occasion_id" value="0">
                                <div class="form-group">
                                    <label for="guest_num" class="col-sm-2 control-label">People</label>
                                    <div class="col-sm-5">
                                        <select name="guest_num" id="guest_num" class="form-control select21">
                                            <option></option>
                                            <?php for($guestNum = 1; $guestNum<=20; $guestNum++) { ?>
                                                <option value="<?php echo $guestNum; ?>"><?php echo $guestNum; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="comment" class="col-sm-2 control-label">Special Request/ Comment</label>
                                    <div class="col-sm-5">
                                        <textarea name="comment" id="comment" rows="3" class="form-control"></textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="assignee_id" class="col-sm-2 control-label">Staff</label>
                                    <div class="col-sm-5">
                                        <select name="assignee_id" id="assignee_id" class="select21 form-control">
                                            <option></option>
                                            @foreach($hase_staffs as $hase_staff)
                                                <option value="{{$hase_staff['staff_id']}}">{{$hase_staff['staff_name']}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="reserve_time" class="col-sm-2 control-label">Reserve Time</label>
                                    <div class="input-group col-sm-4" style="padding-left: 15px;">
                                        <input id="reserve_time" name="reserve_time" type="text" value="20:00" class="form-control required">
                                        <span class="input-group-addon"><i class="fa fa-clock-o"></i></span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="reserve_date" class="col-sm-2 control-label">Reserve Date</label>
                                    <div class="input-group col-sm-4" style="padding-left: 15px;">
                                        <input id="reserve_date" name="reserve_date" type="text" value="<?php echo $currentDate; ?>" class="form-control pull-left" data-language='en' /> 
                                        <div class="input-group-addon">
                                            <i class="fa fa-fw fa-calendar"></i>
                                        </div> 
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="notify" class="col-sm-2 control-label">Notify</label>
                                    <div class="make-switch col-sm-5" data-on="danger" data-off="default">
                                        <input type="checkbox" name="notify" value="0" id="notify" data-on-text="Yes" data-off-text="No">
                                    </div>
                                </div>
                                <input type="hidden" name="status" id="status" value="8">

                                <h4 class="tab-pane-title">Customer</h4>
                                <div class="form-group">
                                    <label for="first_name" class="col-sm-2 control-label">First Name</label>
                                    <div class="col-sm-5">
                                        <input id="first_name" name="first_name" type="text" class="form-control required" > 
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="last_name" class="col-sm-2 control-label">Last Name</label>
                                    <div class="col-sm-5">
                                        <input id="last_name" name="last_name" type="text" class="form-control">
                                    </div>     
                                </div>
                                <div class="form-group">
                                    <label for="email" class="col-sm-2 control-label">Email</label>
                                    <div class="col-sm-5">
                                        <input id="email" name="email" type="text" class="form-control"> 
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="telephone" class="col-sm-2 control-label">Telephone</label>
                                    <div class="col-sm-5">
                                        <input id="telephone" name="telephone" type="number" value="852" class="form-control">
                                    </div> 
                                </div>
                                <div class="form-group">
                                    <label for="newsletter" class="col-sm-2 control-label">Newsletter</label>
                                    <div class="make-switch col-sm-5" data-on="danger" data-off="default">
                                        <input data-on-text="Subscribe" data-off-text="Unsubscribe" id="newsletter" name="newsletter" type="checkbox" value="1" />
                                    </div>
                                </div>
                                <!-- <div class="form-group">
                                    <label for="customer status" class="col-sm-2 control-label">Status</label>
                                    <div class="make-switch col-sm-5" data-on="danger" data-off="default">
                                        <input data-on-text="Enabled" data-off-text="Disabled" id="customer_status" name="customer_status" type="checkbox" value="1" />
                                    </div> 
                                </div> -->
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
<script type="text/javascript" src="{{asset('assets/vendors/select2/js/select2.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/bootstrap-switch/js/bootstrap-switch.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/bootstrapvalidator/js/bootstrapValidator.min.js')}}" ></script>
<script src="{{asset('assets/vendors/airdatepicker/js/datepicker.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/vendors/airdatepicker/js/datepicker.en.js')}}" type="text/javascript"></script>
<script  type="text/javascript" src="{{asset('assets/vendors/timedropper/js/timedropper.js')}}" ></script>
<script type="text/javascript" src="{{asset('assets/js/custom_js/HaseReservationCreate.js')}}"></script>
<!-- end of page level js -->
@stop