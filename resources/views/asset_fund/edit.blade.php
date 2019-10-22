@extends('layouts/default')

{{-- Page title --}}
@section('title')
    Edit Asset Fund
    @parent
@stop

{{-- page level styles --}}
@section('header_styles')
    <!--page level css -->
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/select2/css/select2.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/select2/css/select2-bootstrap.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/bootstrapvalidator/css/bootstrapValidator.min.css')}}">   
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datedropper/datedropper.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/timedropper/css/timedropper.css')}}"> 
    <link href="{{asset('assets/vendors/airdatepicker/css/datepicker.min.css')}}" rel="stylesheet" type="text/css">
    <!--<link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/bootstrap-switch/css/bootstrap-switch.css')}}"/>-->
    <!--end of page level css-->
@stop
@section('content')
<section class="content-header">
    <h1>Edit Asset Fund</h1>
    <ol class="breadcrumb">
        <li>
            <a href="index ">
                <i class="fa fa-fw fa-home"></i> Dashboard
            </a>
        </li>
        
        <li class="active">
            Asset Fund
        </li>
    </ol>
</section>
<section class="content">
    <form id="assetFundForm"  action='{!!url("asset_fund")!!}' method='POST' class="form-horizontal" role="form" enctype="multipart/form-data">
        <div class="row">
            <div class="col-md-12">
                <button type="submit" name="submitBtn" value="Save" class='btn btn-primary btn-inline'>Save</button>
                <button type="submit" name="submitBtn" value="SaveClose" class = 'btn btn-primary btn-inline'>Save  &amp; close</button>
                <a href="{!!url("asset_fund")!!}" class='btn btn-primary btn-inline'>
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
                            <i class="fa fa-fw fa-crosshairs"></i> Edit Asset Fund Detail
                        </h3>
                    </div>
                    <div class="panel-body">
                        <input type='hidden' name='_token' value='{{Session::token()}}'>
                        <input type="hidden" name="requestUrl" id="requestUrl" value="{!!url(Request::segment(1))!!}">
                        <?php if(Session('merchantId') == 0): ?>
                        <div class="form-group">
                            <label for="merchant_id" class="col-sm-3 control-label">Merchant Name</label>
                            <div class="col-sm-4">
                                <select name="merchant_id" id="merchant_id" class="form-control select21" style="width:100%">
                                    <option></option>
                                    @foreach($merchants as $merchant)
                                       <option value="{{$merchant->merchant_id}}">{{$merchant->merchant_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <?php endif; ?>
                        
                        <div class="form-group">
                            <label for="staff_group_id" class="col-sm-3 control-label">Staff Group Name</label>
                            <div class="col-sm-4">
                                <select name="staff_group_id" id="staff_group_id" class="form-control select21" style="width:100%">
                                    <option></option>
                                    @foreach($staff_groups as $group)
                                       <option value="{{$group->staff_group_id}}">{{$group->staff_group_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="staff_id" class="col-sm-3 control-label">Staff Name</label>
                            <div class="col-sm-4">
                                <select name="staff_id" id="staff_id" class="form-control select21" style="width:100%">
                                    <option></option>
                                    @foreach($staffs as $staff)
                                       <option value="{{$staff->staff_id}}">{{$staff->staff_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="account_id" class="col-sm-3 control-label">Account Name</label>
                            <div class="col-sm-4">
                                <select name="account_id" id="account_id" class="form-control select21" style="width:100%">
                                    <option></option>
                                    @foreach($accounts as $account)
                                       <option value="{{$account->account_id}}">{{$account->account_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="asset_id" class="col-sm-3 control-label">Asset Name</label>
                            <div class="col-sm-4">
                                <select name="asset_id" id="asset_id" class="form-control select21" style="width:100%">
                                    <option></option>
                                    @foreach($assets as $asset)
                                       <option value="{{$asset->asset_id}}">{{$asset->asset_code}} ({{$asset->asset_name}})</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="asset_price" class="col-sm-3 control-label">Asset Price</label>
                            <div class="col-sm-4">
                                <input id="asset_price" name = "asset_price" type="text" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="asset_quantity" class="col-sm-3 control-label">Asset Quantity</label>
                            <div class="col-sm-4">
                                <input id="asset_quantity" name = "asset_quantity" type="number" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="fund_type" class="col-sm-3 control-label">Fund Type</label>
                            <div class="col-sm-4">
                                <select name="fund_type" id="fund_type" class="form-control select21" style="width:100%">
                                    <option></option>
                                    @foreach($fundTypes as $fundType)
                                       <option value="{{$fundType->type_id}}">{{$fundType->type_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <!--<div class="form-group">
                            <label for="fund_timezone" class="col-sm-3 control-label">Fund Timezone</label>
                            <div class="col-sm-4">
                                <select name="fund_timezone" id="fund_timezone" class="form-control select21" style="width:100%">
                                    <option></option>
                                    @foreach($timezones as $timezone)
                                       <option value="{{$timezone->timezone_id}}">{{$timezone->timezone_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="fund_date" class="col-sm-3 control-label">Fund Date</label>
                            <div class="input-group col-sm-4" style="padding-left: 15px;padding-right: 15px;">
                                    <input id="fund_date" name="fund_date" type="text" value="<?php //date('Y-m-d') ?>" class="form-control pull-left" data-language='en' /> 
                                    <div class="input-group-addon">
                                        <i class="fa fa-fw fa-calendar"></i>
                                    </div> 
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="fund_time" class="col-sm-3 control-label">Fund Time</label>
                            <div class="input-group col-sm-4" style="padding-left: 15px;padding-right: 15px;">
                                <input id="fund_time" name="fund_time" type="text" value="20:00" class="form-control required">
                                <span class="input-group-addon"><i class="fa fa-clock-o"></i></span>
                            </div>
                        </div>-->
                        <input id="fund_date" name="fund_date" type="hidden" value="<?= date('Y-m-d') ?>" class="form-control pull-left" data-language='en' />
                        <input id="fund_time" name="fund_time" type="hidden" value="20:00" class="form-control required">
                        <input id="status" name="status" value="1" type="hidden" checked="" />
                        <!--<div class="form-group">
                            <label for="status" class="col-sm-3 control-label">Status</label>
                            <div class="col-sm-4">
                                <div class="make-switch" data-on="danger" data-off="default">
                                    <input id="status" name="status" value="1" type="checkbox" checked="" />
                                </div>
                            </div>
                        </div>-->
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
<script type="text/javascript" src="{{asset('assets/vendors/select2/js/select2.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/bootstrapvalidator/js/bootstrapValidator.min.js')}}" ></script>
<script src="{{asset('assets/vendors/airdatepicker/js/datepicker.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/vendors/airdatepicker/js/datepicker.en.js')}}" type="text/javascript"></script>
<script  type="text/javascript" src="{{asset('assets/vendors/datedropper/datedropper.js')}}" ></script>
<script  type="text/javascript" src="{{asset('assets/vendors/timedropper/js/timedropper.js')}}" ></script>
<script type="text/javascript" src="{{asset('assets/vendors/select2/js/select2.js')}}"></script>
<!--<script type="text/javascript" src="{{asset('assets/vendors/bootstrap-switch/js/bootstrap-switch.js')}}"></script>-->
<script type="text/javascript" src="{{asset('assets/js/custom_js/AssetFundCreate.js')}}"></script>
@stop
