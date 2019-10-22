@extends('layouts/default')
{{-- Page title --}}
@section('title')
    Create Location
    @parent
@stop

{{-- page level styles --}}
@section('header_styles')
    <!--page level css -->
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/jasny-bootstrap/css/jasny-bootstrap.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/bootstrap-switch/css/bootstrap-switch.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/select2/css/select2.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/select2/css/select2-bootstrap.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datedropper/datedropper.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/timedropper/css/timedropper.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/jquerylabel/css/jquery-labelauty.css')}}"/>    
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom_css/radio_checkbox.css')}}">
    <!--end of page level css-->
@stop
@section('content')

<style>
.tab-pane-title {
    padding: 0 0 10px;
    margin: 25px 0px;
    border-bottom: 1px solid rgb(236, 240, 245);
    font-size: 20px;
}
.btn-group-toggle {
    width: 100%;
}
.btn-group-3 > .btn {
    width: 33.4445%;
}
.btn-group-toggle .btn:not(.active) {
    background-color: inherit;
    border-color: #D2DCE7;
    color: inherit;
    text-shadow: 1px 1px 0px #F5F5F5;
    box-shadow: 0px 1px 1px rgba(90, 90, 90, 0.1);
    background-clip: border-box;
}
.btn-group-7 > .btn {
    width: 14.2857%;
}
.panel-heading .area-toggle, .panel-heading .area-name, .panel-heading .area-color, .panel-heading .area-buttons {
    display: inline-block;
}
.panel-delivery-areas .panel > .panel-heading {
    cursor: pointer;
}
.panel-heading:not(.collapsed) .area-toggle .up, .panel-heading.collapsed .area-toggle .down {
    display: none !important;
}
.form-horizontal .control-label {
    text-align: left;
}
.control-label .help-block {
    font-size: 68%;
    font-weight: normal;
    font-style: italic;
}
input[type=number]::-webkit-inner-spin-button, 
input[type=number]::-webkit-outer-spin-button { 
    -webkit-appearance: none;
    -moz-appearance: none;
    appearance: none;
    margin: 0; 
}
</style>
<section class="content-header">
    <h1>Create Location</h1>
    <ol class="breadcrumb">
        <li>
            <a href="index ">
                <i class="fa fa-fw fa-home"></i> Dashboard
            </a>
        </li>
        <li>
            <a href="#"> Merchants</a>
        </li>
        <li class="active">
            Create Location
        </li>
    </ol>
</section>
<section class="content">
    <form id='location_tables_form' method='POST' class='form-horizontal bv-form' action='{!!url("hase_location")!!}' enctype="multipart/form-data" novalidate="novalidate">
        <input type = 'hidden' name = '_token' value = '{{Session::token()}}'>
        <input type="hidden" name="requestUrl" id="requestUrl" value="{!!url(Request::segment(1))!!}">
        <div class="row">
            <div class="col-md-12">
                <button type="submit" name="submitbutton" value="Save" class='btn btn-primary btn-inline'>Save</button>
                <button type="submit" name="submitbutton" value="SaveClose" class= 'btn btn-primary btn-inline'>Save &amp; Close</button>
                <a href="{!!url("hase_location")!!}" class='btn btn-primary btn-inline'>
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
                            <i class="fa fa-fw fa-square-o" data-c="#fff" data-hc="#fff" data-loop="true"></i> Create New Location
                        </h3>
                        <span class="pull-right">
                            <i class="fa fa-fw fa-chevron-up clickable"></i>
                        </span>
                    </div>
                    <div class="panel-body">
                        <ul class="nav nav-tabs ">
                            <li class="active">
                                <a href="#tab1" data-toggle="tab">
                                    Basic Information
                                </a>
                            </li>
                            <li>
                                <a href="#tab2" data-toggle="tab">Location</a>
                            </li>
                            <li>
                                <a href="#tab3" data-toggle="tab">
                                    Opening Hours
                                </a>
                            </li>
                            <li>
                                <a href="#tab4" data-toggle="tab">
                                    Holiday Hours
                                </a>
                            </li>
                            <li>
                                <a href="#tab5" data-toggle="tab">
                                    Order
                                </a>
                            </li>
                                <?php if($merchantId == 0) { ?>
                                    <li class="reservationTab">
                                        <a href="#tab6" data-toggle="tab">Reservation</a>
                                    </li>
                                <?php } else {
                                    /*$merchantType = $hase_merchant[0]['merchant_type'];
                                    if($merchantType == 8) {*/
                                ?>
                                    <li class="reservationTab">
                                        <a href="#tab6" data-toggle="tab">Reservation</a>
                                    </li>
                                <?php
                                /* } */
                            } ?>
                            <!-- <li>
                                <a href="#tab7" data-toggle="tab">
                                    Delivery
                                </a>
                            </li> 
                            <li>
                                <a href="#tab8" data-toggle="tab">
                                    Gallery
                                </a>
                            </li>-->
                        </ul>
                        <div class="tab-content m-t-10">
                            <div id="tab1" class="tab-pane fade active in">
                                <h4 class="tab-pane-title">Basic</h4>
                                <div class="form-group">
                                    <label for="location_name" class="col-sm-2 control-label">Name</label>
                                    <div class="col-sm-5">
                                        <input id="location_name" name="location_name" type="text" class="form-control required">
                                    </div>
                                </div>
                                <?php $merchantType = '';
                                if(session('merchantId') == 0): ?>
                                    <div class="form-group">
                                        <label for="Merchant" class="col-sm-2 control-label">Merchant</label>
                                        <div class="col-sm-5"> 
                                            <select name="merchant_id" id="merchant_id" class = "form-control select21">
                                                @foreach($hase_merchants as $hase_merchant)
                                                    <option value="{{$hase_merchant->merchant_id}}">{{$hase_merchant->merchant_name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="Merchant Type" class="col-sm-2 control-label">
                                            Merchant type
                                        </label>
                                        <div class="col-sm-5">
                                            <select name="merchant_type" id="merchant_type" class="form-control select21">
                                                @foreach($merchant_parent_types as $merchant_parent_type)
                                                    @if($merchant_parent_type->merchant_type_id == 8)
                                                        <option value="{{$merchant_parent_type->merchant_type_id}}" selected>{{$merchant_parent_type->merchant_type_name}}</option>
                                                    @else
                                                        <option value="{{$merchant_parent_type->merchant_type_id}}">{{$merchant_parent_type->merchant_type_name}}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="Parent" class="col-sm-2 control-label">
                                            Merchant sub types
                                        </label>
                                        <div class="col-sm-5">
                                            <select name="merchant_type_id[]" id="merchant_type_id" class="merchantTypeId form-control select21" multiple>
                                                @foreach($merchantTypes as $key=>$parent)
                                                    @foreach($parent as $child)
                                                        @if($child->merchant_root_id == 8)
                                                            <option value="{{$child->merchant_type_id}}">{{$child->merchant_type_name}}</option>
                                                        @endif
                                                    @endforeach
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                <?php else:
                                    /*$merchantType = $hase_merchant[0]['merchant_type'];*/ ?>
                                    <div class="form-group">
                                        <label for="Parent" class="col-sm-2 control-label">
                                            Merchant sub types
                                        </label>
                                        <div class="col-sm-5">
                                            <select name="merchant_type_id[]" id="merchant_type_id" class="merchantTypeId form-control select21" multiple>
                                                @foreach($merchantTypes as $key=>$parent)
                                                    @foreach($parent as $child)
                                                        @if($child->merchant_root_id == $merchantType)
                                                            <option value="{{$child->merchant_type_id}}">{{$child->merchant_type_name}}</option>
                                                        @endif
                                                    @endforeach
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                @foreach($merchantTypes as $key=>$parent)
                                    <div id="<?php echo $key.'Types' ?>" style="display: none;">
                                    @foreach($parent as $child)
                                        <option value="{{$child->merchant_type_id}}">{{$child->merchant_type_name}}</option>
                                    @endforeach
                                    </div>
                                @endforeach
                                <div class="form-group">
                                    <label for="location_email" class="col-sm-2 control-label">Email</label>
                                    <div class="col-sm-5">
                                        <input id="location_email" name="location_email" type="text" class="form-control required">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="location_telephone" class="col-sm-2 control-label">Telephone</label>
                                    <div class="col-sm-4 input-group" style="padding-left: 15px;">
                                        <span class="input-group-addon countryPhoneCode">+852</span>
                                        <input id="location_telephone" name="location_telephone" type="number" class="form-control">
                                    </div>
                                </div>
                                    <div class="form-group">
                                        <label for="priority" class="col-sm-2 control-label">Priority</label>
                                        <div class="col-sm-5">
                                            <input id="priority" name="priority" type="number" class="form-control"> 
                                        </div>
                                    </div>
                                <div class="form-group">
                                    <label for="description" class="col-sm-2 control-label">Description</label>
                                    <div class="col-sm-5">
                                        <textarea name="description" id="description" rows="4" class="form-control"></textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="location_website" class="col-sm-2 control-label">Website</label>
                                    <div class="col-sm-5">
                                        <input id="location_website" name="location_website" type="text" class="form-control">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="pic" class="col-sm-2 control-label">Image<span class="help-block" style="font-size: 82%;">Select an image to use as the location logo, this image is displayed in the restaurant list.</span>
                                    </label>
                                    <div class="col-sm-5">
                                        <div class="fileinput fileinput-new" data-provides="fileinput">
                                            <div class="fileinput-new thumbnail"
                                                style="width: 200px; height: 200px;">
                                                <img src="{{asset(env('image_dir_path').'no_photo.png')}}" alt="profile pic" class="profile_pic">
                                            </div>
                                            <div class="fileinput-preview fileinput-exists thumbnail"
                                                style="max-width: 200px; max-height: 200px;"></div>
                                            <div class="fileinput-filename" style="display: block !important;">
                                            </div>
                                            <div>
                                                <span class="btn btn-default btn-file">
                                                    <span class="fileinput-new">Select image</span>
                                                    <span class="fileinput-exists">Change</span>
                                                    <input id="location_image" name="location_image" type="file" class="form-control"/>
                                                </span>
                                                <a href="#" class="btn btn-danger fileinput-exists" data-dismiss="fileinput">Remove</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="image Url" class="col-sm-2 control-label">Image Url</label>
                                    <div class="col-sm-8">
                                        <input id="live_image_url" name = "live_image_url" type="text" class="form-control"> 
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="location_status" class="col-sm-2 control-label">Status</label>
                                    <div class="make-switch col-sm-5" data-on="danger" data-off="default">
                                        <input type="checkbox" name="location_status" value="1" id="location_status" data-on-text="Enabled" data-off-text="Disabled">
                                    </div>
                                </div>
                            </div>

                            <div id="tab2" class="tab-pane fade">
                                <h4 class="tab-pane-title">Address</h4>
                                <div class="form-group">
                                    <label for="location_address_1" class="col-sm-2 control-label">Address 1</label>
                                    <div class="col-sm-5">
                                        <input id="location_address_1" name="location_address_1" type="text" class="form-control required">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="location_address_2" class="col-sm-2 control-label">Address 2</label>
                                    <div class="col-sm-5">
                                        <input id="location_address_2" name="location_address_2" type="text" class="form-control required">
                                    </div>
                                </div>
                                <input type="hidden" name="location_city_name" id="location_city_name">
                                <input type="hidden" name="location_county_name" id="location_county_name">
                                <input type="hidden" name="location_state_name" id="location_state_name">
                                <div class="form-group">
                                    <label for="location_country_id" class="col-sm-2 control-label">Country</label>
                                    <div class="col-sm-5">
                                        <select name="location_country_id" id="location_country_id" class="select21 form-control">
                                            <option></option>
                                            @foreach($hase_countries as $hase_country)
                                                @if($hase_country['country_id'] == 96)
                                                    <option value="{{$hase_country['country_id']}}" data-phonecode="<?php echo isset($hase_country['country_phone_code'])? $hase_country['country_phone_code'] : '' ?>" data-telephonemin="<?php echo isset($hase_country['telephone_min'])? $hase_country['telephone_min'] : '' ?>" data-telephonemax="<?php echo isset($hase_country['telephone_max'])? $hase_country['telephone_max'] : '' ?>" selected="selected">{{$hase_country['country_name']}}</option>
                                                @else
                                                    <option value="{{$hase_country['country_id']}}" data-phonecode="<?php echo isset($hase_country['country_phone_code'])? $hase_country['country_phone_code'] : '' ?>" data-telephonemin="<?php echo isset($hase_country['telephone_min'])? $hase_country['telephone_min'] : '' ?>" data-telephonemax="<?php echo isset($hase_country['telephone_max'])? $hase_country['telephone_max'] : '' ?>">{{$hase_country['country_name']}}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="location_state" class="locationState col-sm-2 control-label">Territories</label>
                                    <div class="col-sm-5">
                                        <select name="location_state" id="location_state" class="select21 form-control myState">
                                            <option></option>
                                            @foreach($hase_states as $hase_state)
                                                <option value="{{$hase_state['state_id']}}">{{$hase_state['state_name']}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="county_id" class="locationCounty col-sm-2 control-label">District</label>
                                    <div class="col-sm-5">
                                        <select name="county_id" id="county_id" class="select21 form-control">
                                            <option></option>
                                            @foreach($hase_counties as $hase_county)
                                                <option value="{{$hase_county['county_id']}}">{{$hase_county['county_name']}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="location_city" class="locationCity col-sm-2 control-label">Neighborhood</label>
                                    <div class="col-sm-5">
                                        <select name="location_city" id="location_city" class="select21 form-control">
                                            <option></option>
                                            @foreach($hase_cities as $hase_city)
                                                <option value="{{$hase_city['city_id']}}" data-county="{{$hase_city['county_id']}}" data-state="{{$hase_city['state_id']}}" data-country="{{$hase_city['country_id']}}">{{$hase_city['city_name']}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="location_postcode" class="col-sm-2 control-label">Postcode</label>
                                    <div class="col-sm-5">
                                        <input id="location_postcode" name="location_postcode" type="text" class="form-control required">
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label for="request_coordinates" class="col-sm-2 control-label">Request Coordinates</label>
                                    <div class="col-sm-5">
                                        <input class="to-labelauty terms-icon" id="request_coordinates" type="checkbox" data-labelauty="Selected|Get Coordinates" checked />
                                    </div>
                                </div>
                                <div id="latitudeLongitude" style="display: none;">
                                    <div class="form-group">
                                        <label for="location_lat" class="col-sm-2 control-label">Latitude</label>
                                        <div class="col-sm-5">
                                            <input id="location_lat" name="location_lat" type="text" class="form-control">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="location_lng" class="col-sm-2 control-label">Longitude</label>
                                        <div class="col-sm-5">
                                            <input id="location_lng" name="location_lng" type="text" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                
                            </div>

                            <div id="tab3" class="tab-pane fade">
                                <div id="opening-flexible">
                                    <div class="form-group">
                                        <label for="" class="col-sm-2 control-label"></label>
                                        <div class="col-sm-5">
                                            <div class="control-group control-group-2">
                                                <div class="input-group" style="width: 48%;float: left;">
                                                    <b>Open hour</b>
                                                </div>
                                                <div class="input-group" style="width: 48%;float: left;">
                                                    <b>Close hour</b>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="input-status" class="col-sm-2 control-label text-right">
                                            <span class="text-right">Monday</span>
                                        </label>
                                        <div class="multipleShift0">
                                            <div class="col-sm-10">
                                                <div class="control-group control-group-3">
                                                    <div class="input-group" style="width: 30.1%; float: left;">
                                                        <input type="text" name="flexible_hours[0][0][open]" id="flexible_monday_open" class="form-control" value="10:00">
                                                        <span class="input-group-addon"><i class="fa fa-clock-o"></i></span>
                                                    </div>
                                                    <div class="input-group" style="width: 30.1%; margin-left: 16px; float: left;">
                                                        <input type="text" name="flexible_hours[0][0][close]" id="flexible_monday_close" class="form-control" value="23:00">
                                                        <span class="input-group-addon"><i class="fa fa-clock-o"></i></span>
                                                    </div>
                                                    <div class="btn-group btn-group-switch" data-toggle="buttons">
                                                        <div class="make-switch col-sm-5" data-on="danger" data-off="default">
                                                            <input type="checkbox" name="flexible_hours[0][0][status]" id="flexible_hours_0_status" value="1" data-on-text="Open" data-off-text="Closed">
                                                        </div>
                                                    </div>
                                                    <a class="btn btn-primary btn-lg" onclick="addNewShift(0);" style="padding: 3px 5px;">
                                                        <i class="fa fa-plus"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="input-status" class="col-sm-2 control-label text-right">
                                            <span class="text-right">Tuesday</span>
                                        </label>
                                        <div class="multipleShift1">
                                            <div class="col-sm-10">
                                                <div class="control-group control-group-3">
                                                    <div class="input-group" style="width: 30.1%; float: left;">
                                                        <input type="text" name="flexible_hours[1][0][open]" id="flexible_tuesday_open" class="form-control" value="10:00">
                                                        <span class="input-group-addon"><i class="fa fa-clock-o"></i></span>
                                                    </div>
                                                    <div class="input-group" style="width: 30.1%; margin-left: 16px; float: left;">
                                                        <input type="text" name="flexible_hours[1][0][close]" id="flexible_tuesday_close" class="form-control" value="23:00">
                                                        <span class="input-group-addon"><i class="fa fa-clock-o"></i></span>
                                                    </div>
                                                    <div class="btn-group btn-group-switch" data-toggle="buttons">
                                                        <div class="make-switch col-sm-5" data-on="danger" data-off="default">
                                                            <input type="checkbox" name="flexible_hours[1][0][status]" id="flexible_hours_1_status" value="1" data-on-text="Open" data-off-text="Closed">
                                                        </div>
                                                    </div>
                                                    <a class="btn btn-primary btn-lg" onclick="addNewShift(1);" style="padding: 3px 5px;">
                                                        <i class="fa fa-plus"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="input-status" class="col-sm-2 control-label text-right">
                                            <span class="text-right">Wednesday</span>
                                        </label>
                                        <div class="multipleShift2">
                                            <div class="col-sm-10">
                                                <div class="control-group control-group-3">
                                                    <div class="input-group" style="width: 30.1%; float: left;">
                                                        <input type="text" name="flexible_hours[2][0][open]" id="flexible_wednesday_open" class="form-control" value="10:00">
                                                        <span class="input-group-addon"><i class="fa fa-clock-o"></i></span>
                                                    </div>
                                                    <div class="input-group" style="width: 30.1%; margin-left: 16px; float: left;">
                                                        <input type="text" name="flexible_hours[2][0][close]" id="flexible_wednesday_close" class="form-control" value="23:00">
                                                        <span class="input-group-addon"><i class="fa fa-clock-o"></i></span>
                                                    </div>
                                                    <div class="btn-group btn-group-switch" data-toggle="buttons">
                                                        <div class="make-switch col-sm-5" data-on="danger" data-off="default">
                                                            <input type="checkbox" name="flexible_hours[2][0][status]" id="flexible_hours_2_status" value="1" data-on-text="Open" data-off-text="Closed">
                                                        </div>
                                                    </div>
                                                    <a class="btn btn-primary btn-lg" onclick="addNewShift(2);" style="padding: 3px 5px;">
                                                        <i class="fa fa-plus"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="input-status" class="col-sm-2 control-label text-right">
                                            <span class="text-right">Thursday</span>
                                        </label>
                                        <div class="multipleShift3">
                                            <div class="col-sm-10">
                                                <div class="control-group control-group-3">
                                                    <div class="input-group" style="width: 30.1%; float: left;">
                                                        <input type="text" name="flexible_hours[3][0][open]" id="flexible_thursday_open" class="form-control" value="10:00">
                                                        <span class="input-group-addon"><i class="fa fa-clock-o"></i></span>
                                                    </div>
                                                    <div class="input-group" style="width: 30.1%; margin-left: 16px; float: left;">
                                                        <input type="text" name="flexible_hours[3][0][close]" id="flexible_thursday_close" class="form-control" value="23:00">
                                                        <span class="input-group-addon"><i class="fa fa-clock-o"></i></span>
                                                    </div>
                                                    <div class="btn-group btn-group-switch" data-toggle="buttons">
                                                        <div class="make-switch col-sm-5" data-on="danger" data-off="default">
                                                            <input type="checkbox" name="flexible_hours[3][0][status]" id="flexible_hours_3_status" value="1" data-on-text="Open" data-off-text="Closed">
                                                        </div>
                                                    </div>
                                                    <a class="btn btn-primary btn-lg" onclick="addNewShift(3);" style="padding: 3px 5px;">
                                                        <i class="fa fa-plus"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="input-status" class="col-sm-2 control-label text-right">
                                            <span class="text-right">Friday</span>
                                        </label>
                                        <div class="multipleShift4">
                                            <div class="col-sm-10">
                                                <div class="control-group control-group-3">
                                                    <div class="input-group" style="width: 30.1%; float: left;">
                                                        <input type="text" name="flexible_hours[4][0][open]" id="flexible_friday_open" class="form-control" value="10:00">
                                                        <span class="input-group-addon"><i class="fa fa-clock-o"></i></span>
                                                    </div>
                                                    <div class="input-group" style="width: 30.1%; margin-left: 16px; float: left;">
                                                        <input type="text" name="flexible_hours[4][0][close]" id="flexible_friday_close" class="form-control" value="23:00">
                                                        <span class="input-group-addon"><i class="fa fa-clock-o"></i></span>
                                                    </div>
                                                    <div class="btn-group btn-group-switch" data-toggle="buttons">
                                                        <div class="make-switch col-sm-5" data-on="danger" data-off="default">
                                                            <input type="checkbox" name="flexible_hours[4][0][status]" id="flexible_hours_4_status" value="1" data-on-text="Open" data-off-text="Closed">
                                                        </div>
                                                    </div>
                                                    <a class="btn btn-primary btn-lg" onclick="addNewShift(4);" style="padding: 3px 5px;">
                                                        <i class="fa fa-plus"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="input-status" class="col-sm-2 control-label text-right">
                                            <span class="text-right">Saturday</span>
                                        </label>
                                        <div class="multipleShift5">
                                            <div class="col-sm-10">
                                                <div class="control-group control-group-3">
                                                    <div class="input-group" style="width: 30.1%; float: left;">
                                                        <input type="text" name="flexible_hours[5][0][open]" id="flexible_saturday_open" class="form-control" value="10:00">
                                                        <span class="input-group-addon"><i class="fa fa-clock-o"></i></span>
                                                    </div>
                                                    <div class="input-group" style="width: 30.1%; margin-left: 16px; float: left;">
                                                        <input type="text" name="flexible_hours[5][0][close]" id="flexible_saturday_close" class="form-control" value="23:00">
                                                        <span class="input-group-addon"><i class="fa fa-clock-o"></i></span>
                                                    </div>
                                                    <div class="btn-group btn-group-switch" data-toggle="buttons">
                                                        <div class="make-switch col-sm-5" data-on="danger" data-off="default">
                                                            <input type="checkbox" name="flexible_hours[5][0][status]" id="flexible_hours_5_status" value="1" data-on-text="Open" data-off-text="Closed">
                                                        </div>
                                                    </div>
                                                    <a class="btn btn-primary btn-lg" onclick="addNewShift(5);" style="padding: 3px 5px;">
                                                        <i class="fa fa-plus"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="input-status" class="col-sm-2 control-label text-right">
                                            <span class="text-right">Sunday</span>
                                        </label>
                                        <div class="multipleShift6">
                                            <div class="col-sm-10">
                                                <div class="control-group control-group-3">
                                                    <div class="input-group" style="width: 30.1%; float: left;">
                                                        <input type="text" name="flexible_hours[6][0][open]" id="flexible_sunday_open" class="form-control" value="10:00">
                                                        <span class="input-group-addon"><i class="fa fa-clock-o"></i></span>
                                                    </div>
                                                    <div class="input-group" style="width: 30.1%; margin-left: 16px; float: left;">
                                                        <input type="text" name="flexible_hours[6][0][close]" id="flexible_sunday_close" class="form-control" value="23:00">
                                                        <span class="input-group-addon"><i class="fa fa-clock-o"></i></span>
                                                    </div>
                                                    <div class="btn-group btn-group-switch" data-toggle="buttons">
                                                        <div class="make-switch col-sm-5" data-on="danger" data-off="default">
                                                            <input type="checkbox" name="flexible_hours[6][0][status]" id="flexible_hours_6_status" value="1" data-on-text="Open" data-off-text="Closed">
                                                        </div>
                                                    </div>
                                                    <a class="btn btn-primary btn-lg" onclick="addNewShift(6);" style="padding: 3px 5px;">
                                                        <i class="fa fa-plus"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- <div id="delivery-type" class="form-group">
                                    <label for="" class="col-sm-2 control-label">Delivery Hours</label>
                                    <div class="col-sm-5">
                                        <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                            <label class="btn btn-default active" style="width: 50%;"><input type="radio" name="delivery_type" value="0" checked="checked">Same As Opening</label>
                                            <label class="btn btn-default" style="width: 50%;"><input type="radio" name="delivery_type" value="1">Custom</label>
                                        </div>
                                    </div>
                                </div>
                                <div id="delivery-hours-daily">
                                    <div class="form-group">
                                        <label for="input-delivery-days" class="col-sm-2 control-label">Days</label>
                                        <div class="col-sm-5">
                                            <div class="btn-group btn-group-toggle btn-group-7" data-toggle="buttons">
                                                <label class="btn btn-default active">
                                                    <input type="checkbox" name="delivery_days[]" value="0" checked="checked">Mon
                                                </label>
                                                <label class="btn btn-default active">
                                                    <input type="checkbox" name="delivery_days[]" value="1" checked="checked">Tue
                                                </label>
                                                <label class="btn btn-default active">
                                                    <input type="checkbox" name="delivery_days[]" value="2" checked="checked">Wed
                                                </label>
                                                <label class="btn btn-default active">
                                                    <input type="checkbox" name="delivery_days[]" value="3" checked="checked">Thu
                                                </label>
                                                <label class="btn btn-default active">
                                                    <input type="checkbox" name="delivery_days[]" value="4" checked="checked">Fri
                                                </label>
                                                <label class="btn btn-default active">
                                                    <input type="checkbox" name="delivery_days[]" value="5" checked="checked">Sat
                                                </label>
                                                <label class="btn btn-default active">
                                                    <input type="checkbox" name="delivery_days[]" value="6" checked="checked">Sun
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="input-delivery-hours" class="col-sm-2 control-label">Hours</label>
                                        <div class="col-sm-5">
                                            <div class="control-group control-group-2">
                                                <div class="input-group" style="width: 48%;float: left;">
                                                    <input type="text" name="delivery_hours[open]" id="deliveryopentime" class="form-control" value="10:00">
                                                    <span class="input-group-addon">
                                                        <i class="fa fa-clock-o"></i>
                                                    </span>
                                                </div>
                                                <div class="input-group" style="width: 48%;float: left; margin-left: 15px;">
                                                    <input type="text" name="delivery_hours[close]" id="deliveryclosetime" class="form-control" value="23:00">
                                                    <span class="input-group-addon">
                                                        <i class="fa fa-clock-o"></i>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div id="collection-type" class="form-group">
                                    <label for="" class="col-sm-2 control-label">Pick-up Hours</label>
                                    <div class="col-sm-5">
                                        <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                            <label class="btn btn-default active" style="width: 50%;"><input type="radio" name="collection_type" value="0" checked="checked">Same As Opening</label>
                                            <label class="btn btn-default" style="width: 50%;"><input type="radio" name="collection_type" value="1">Custom</label>
                                        </div>
                                    </div>
                                </div>
                                <div id="collection-hours-daily">
                                    <div class="form-group">
                                        <label for="input-collection-days" class="col-sm-2 control-label">Days</label>
                                        <div class="col-sm-5">
                                            <div class="btn-group btn-group-toggle btn-group-7" data-toggle="buttons">
                                                <label class="btn btn-default active">
                                                    <input type="checkbox" name="collection_days[]" value="0" checked="checked">Mon
                                                </label>
                                                <label class="btn btn-default active">
                                                    <input type="checkbox" name="collection_days[]" value="1" checked="checked">Tue
                                                </label>
                                                <label class="btn btn-default active">
                                                    <input type="checkbox" name="collection_days[]" value="2" checked="checked">Wed
                                                </label>
                                                <label class="btn btn-default active">
                                                    <input type="checkbox" name="collection_days[]" value="3" checked="checked">Thu
                                                </label>
                                                <label class="btn btn-default active">
                                                    <input type="checkbox" name="collection_days[]" value="4" checked="checked">Fri
                                                </label>
                                                <label class="btn btn-default active">
                                                    <input type="checkbox" name="collection_days[]" value="5" checked="checked">Sat
                                                </label>
                                                <label class="btn btn-default active">
                                                    <input type="checkbox" name="collection_days[]" value="6" checked="checked">Sun
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="input-collection-hours" class="col-sm-2 control-label">Hours</label>
                                        <div class="col-sm-5">
                                            <div class="control-group control-group-2">
                                                <div class="input-group" style="width: 48%;float: left;">
                                                    <input type="text" name="collection_hours[open]" id="collectionopentime" class="form-control" value="10:00">
                                                    <span class="input-group-addon">
                                                        <i class="fa fa-clock-o"></i>
                                                    </span>
                                                </div>
                                                <div class="input-group" style="width: 48%;float: left; margin-left: 15px;">
                                                    <input type="text" name="collection_hours[close]" id="collectionclosetime" class="form-control" value="23:00">
                                                    <span class="input-group-addon">
                                                        <i class="fa fa-clock-o"></i>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div> -->
                            </div>

                            <div id="tab4" class="tab-pane fade">
                                <div id="holiday-hours">
                                    <div class="form-group" style="border-top: 1px solid #ddd;border-bottom: 1px solid #ddd;">
                                        <a class="btn btn-primary btn-lg" onclick="addNewHoliday();" style="padding: 5px 7px; margin: 7px 0 7px; margin-left: 10px;">
                                            <i class="fa fa-plus"></i>
                                        </a>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-2 input-group" style="padding-left: 15px; float: left;">
                                            <b>Name</b>
                                        </div>
                                        <div class="col-sm-10">
                                            <div class="control-group control-group-3">
                                                <div class="input-group" style="width: 27%;float: left;">
                                                    <b>Date</b>
                                                </div>
                                                <div class="input-group" style="width: 24%;float: left;">
                                                    <b>Open hour</b>
                                                </div>
                                                <div class="input-group" style="width: 24%;float: left;">
                                                    <b>Close hour</b>
                                                </div>
                                                <div class="input-group" style="width: 15%;float: left;">
                                                    <b>Status</b>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="holidayShift0">
                                            <div class="col-sm-2">
                                                <select name="holiday_hours[0][0][holiday_id]" class="holiday_id select21 form-control">
                                                    <option></option>
                                                    @foreach($hase_holidays as $hase_holiday)
                                                        <option value="{{$hase_holiday['holiday_id']}}" data-date="<?php echo substr_replace(substr_replace($hase_holiday['holiday_date'], '-', 4, 0), '-', 7, 0) ?>">{{$hase_holiday['holiday_name']}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-sm-10">
                                                <div class="control-group control-group-3">
                                                    <div class="input-group" style="width:25.3%; float:left;">
                                                        <input type="text" name="holiday_hours[0][0][date]" value="0000-00-00" class="form-control holiday_0_date" placeholder="YYYY-MM-DD"/>
                                                        <div class="input-group-addon">
                                                            <i class="fa fa-fw fa-calendar"></i>
                                                        </div>
                                                    </div>
                                                    <div class="input-group" style="width:22.1%; float:left; margin-left:12px;">
                                                        <input type="text" name="holiday_hours[0][0][open]" class="form-control holiday_0_open" value="10:00">
                                                        <span class="input-group-addon"><i class="fa fa-clock-o"></i></span>
                                                    </div>
                                                    <div class="input-group" style="width:22.1%; margin-left: 12px; float: left;">
                                                        <input type="text" name="holiday_hours[0][0][close]" class="form-control holiday_0_close" value="23:00">
                                                        <span class="input-group-addon"><i class="fa fa-clock-o"></i></span>
                                                    </div>
                                                    <div class="btn-group btn-group-switch" data-toggle="buttons">
                                                        <div class="make-switch col-sm-5" data-on="danger" data-off="default">
                                                            <input type="checkbox" name="holiday_hours[0][0][status]" id="holiday_0_status" value="1" data-on-text="Open" data-off-text="Closed">
                                                        </div>
                                                    </div>
                                                    <a class="btn btn-primary btn-lg" onclick="addNewHolidayShift(0);" style="padding: 3px 5px;">
                                                        <i class="fa fa-plus"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="tab5" class="tab-pane fade">
                                <div class="form-group">
                                    <label for="offer_delivery" class="col-sm-2 control-label">Offer Delivery</label>
                                    <div class="make-switch col-sm-5" data-on="danger" data-off="default">
                                        <input type="checkbox" name="offer_delivery" id="offer_delivery" data-on-text="YES" data-off-text="NO">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="offer_collection" class="col-sm-2 control-label">Offer Pick-up</label>
                                    <div class="make-switch col-sm-5" data-on="danger" data-off="default">
                                        <input type="checkbox" name="offer_collection" id="offer_collection" data-on-text="YES" data-off-text="NO">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="delivery_time" class="col-sm-2 control-label">Delivery Time
                                        <span class="help-block" style="font-size: 82%;">Set number of minutes an order will be delivered after being placed, or set to 0 to use default</span>
                                    </label>
                                    <div class="input-group col-sm-5">
                                        <input id="delivery_time" name="delivery_time" type="text" class="form-control">
                                        <span class="input-group-addon">minutes</span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="collection_time" class="col-sm-2 control-label">Pick-up Time
                                        <span class="help-block" style="font-size: 82%;">Set number of minutes an order will be ready for pick-up after being placed, or set to 0 to use default</span>
                                    </label>
                                    <div class="input-group col-sm-5">
                                        <input id="collection_time" name="collection_time" type="text" class="form-control">
                                        <span class="input-group-addon">minutes</span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="last_order_time" class="col-sm-2 control-label">Last Order Time
                                        <span class="help-block" style="font-size: 82%;">Set number of minutes before closing time for last order, or set to 0 to use closing hour.</span>
                                    </label>
                                    <div class="input-group col-sm-5">
                                        <input id="last_order_time" name="last_order_time" type="text" class="form-control">
                                        <span class="input-group-addon">minutes</span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="future_orders" class="col-sm-2 control-label">Accept Future Orders
                                        <span class="help-block" style="font-size: 82%;">Allow customer to place order for a later time when restaurant is closed for delivery or pick-up during opening hours
                                        </span>
                                    </label>
                                    <div class="make-switch col-sm-5" data-on="danger" data-off="default">
                                        <input type="checkbox" name="future_orders" value="0" id="future_orders" data-on-text="YES" data-off-text="NO">
                                    </div>
                                </div>
                                <br>
                                <div id="future-orders-days">
                                    <div class="form-group">
                                        <label for="input-delivery-days" class="col-sm-2 control-label">Future Order Days In Advance
                                            <span class="help-block" style="font-size: 82%;">Set the number of days in advance to allow customer to place a delivery or pick-up order for a later time.
                                            </span>
                                        </label>
                                        <div class="col-sm-7">
                                            <div class="control-group control-group-2">
                                                <div class="input-group" style="width: 48%;float: left;">
                                                    <span class="input-group-addon"><b>Delivery:</b></span>
                                                    <input type="text" name="future_order_days[delivery]" class="form-control" value="">
                                                    <span class="input-group-addon">days</span>
                                                </div>
                                                <div class="input-group" style="width: 48%;float: left; margin-left: 18px;">
                                                    <span class="input-group-addon"><b>Pick-up:</b></span>
                                                    <input type="text" name="future_order_days[collection]" class="form-control" value="">
                                                    <span class="input-group-addon">days</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="input-payments" class="col-sm-2 control-label">Payments
                                        <span class="help-block" style="font-size: 82%;">Select the payment(s) available at this location. Leave blank to use all enabled payments.</span>
                                    </label>
                                    <div class="col-sm-5">
                                        
                                    </div>
                                </div>
                            </div>

                            <div id="tab6" class="tab-pane fade">
                                <div class="form-group">
                                    <label for="reservation_time_interval" class="col-sm-2 control-label">Time Interval
                                        <span class="help-block" style="font-size: 82%;">Set the number of minutes between each reservation time, Leave as 0 to use system setting value</span>
                                    </label>
                                    <div class="input-group col-sm-5">
                                        <input id="reservation_time_interval" name="reservation_time_interval" type="text" value="" class="form-control">
                                        <span class="input-group-addon">minutes</span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="reservation_stay_time" class="col-sm-2 control-label">Stay Time
                                        <span class="help-block" style="font-size: 82%;">Set in minutes the average time a guest will stay at a table, Leave as 0 to use system setting value</span>
                                    </label>
                                    <div class="input-group col-sm-5">
                                        <input id="reservation_stay_time" name="reservation_stay_time" type="text" class="form-control">
                                        <span class="input-group-addon">minutes</span>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="input-table" class="col-sm-2 control-label">Tables</label>
                                    <div class="col-sm-5">
                                        <select name="location_tables" id="location_tables" class="select21 form-control">
                                            <option></option>
                                            @foreach($hase_tables as $hase_table)
                                                <option value="{{$hase_table['table_id']}}" data-minimum="{{$hase_table['min_capacity']}}" data-capacity="{{$hase_table['max_capacity']}}">{{$hase_table['table_name']}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div id="table-box" class="col-sm-12 wrap-top">
                                        <div class="table-responsive">
                                            <table class="table table-striped">
                                                <thead>
                                                    <tr>
                                                        <th width="40%">Name</th>
                                                        <th>Minimum</th>
                                                        <th>Capacity</th>
                                                        <th>Remove</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- <div id="tab7" class="tab-pane fade">
                                <div class="col-md-7 wrap-none">

                                </div>
                                <div class="col-md-5 wrap-none">
                                    <div class="panel panel-default panel-delivery-areas border-left-3">
                                        <div class="panel-heading">
                                            <h3 class="panel-title">Delivery Areas</h3>
                                        </div>
                                        <div id="delivery-areas" class="panel-body">
                                            
                                        </div>
                                        <div class="panel-footer">
                                            <div class="clearfix text-center">
                                                <button type="button" class="btn btn-default area-new" onclick="addDeliveryArea();" data-original-title="" title="">
                                                    <i class="fa fa-plus"></i>&nbsp;&nbsp;Add new area
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div> -->
                            <!--
                            <div id="tab8" class="tab-pane fade">
                                <div class="form-group">
                                    <label for="input-gallery-title" class="col-sm-2 control-label">Title</label>
                                    <div class="col-sm-5">
                                        <input id="input-gallery-title" name="gallery[title]" type="text" class="form-control required">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="input-gallery-description" class="col-sm-2 control-label">Description</label>
                                    <div class="col-sm-5">
                                        <textarea name="gallery[description]" id="input-gallery-description" rows="4" class="form-control"></textarea>
                                    </div>
                                </div>
                                <br>
                                <div id="gallery-images" class="row">
                                    <div class="panel panel-default panel-table">
                                        <table class="table table-striped table-border table-sortable">
                                            <thead>
                                                <tr>
                                                    <th class="action"></th>
                                                    <th class="col-sm-2">Select Image</th>
                                                    <th class="col-sm-2">Thumbnail</th>
                                                    <th class="col-sm-2">Filename</th>
                                                    <th class="col-sm-2">Alt Text</th>
                                                    <th class="col-sm-4 text-center">Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                
                                            </tbody>
                                            <tfoot>
                                                <tr id="tfoot">
                                                    <td class="action action-one">
                                                        <a class="btn btn-primary btn-lg" onclick="addImageToGallery();" style="padding: 5px 7px;">
                                                            <i class="fa fa-plus"></i>
                                                        </a>
                                                    </td>
                                                    <td colspan="4"></td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>  -->
                            <div class="holiday-clone" style="display: none;">
                                <select name="" class="holiday_id form-control" >
                                    <option></option>
                                    @foreach($hase_holidays as $hase_holiday)
                                        <option value="{{$hase_holiday['holiday_id']}}" data-date="<?php echo substr_replace(substr_replace($hase_holiday['holiday_date'], '-', 4, 0), '-', 7, 0) ?>">{{$hase_holiday['holiday_name']}}</option>
                                    @endforeach
                                </select>
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
<script type="text/javascript" src="{{asset('assets/vendors/jasny-bootstrap/js/jasny-bootstrap.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/select2/js/select2.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/bootstrap-switch/js/bootstrap-switch.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/bootstrapwizard/js/jquery.bootstrap.wizard.js')}}" ></script>
<script type="text/javascript" src="{{asset('assets/vendors/bootstrapvalidator/js/bootstrapValidator.min.js')}}" ></script>
<script  type="text/javascript" src="{{asset('assets/vendors/datedropper/datedropper.js')}}" ></script>
<script  type="text/javascript" src="{{asset('assets/vendors/timedropper/js/timedropper.js')}}" ></script>
<script type="text/javascript" src="{{asset('assets/vendors/jquerylabel/js/jquery-labelauty.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/js/custom_js/HaseLocationCreate.js')}}"></script>
<!-- end of page level js -->
@stop