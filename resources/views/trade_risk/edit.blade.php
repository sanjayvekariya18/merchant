@extends('layouts/default')

{{-- Page title --}}
@section('title')
    Edit Trade Risk
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
    <!--end of page level css-->
@stop
@section('content')
<section class="content-header">
    <h1>Edit Trade Risk</h1>
    <ol class="breadcrumb">
        <li>
            <a href="index ">
                <i class="fa fa-fw fa-home"></i> Dashboard
            </a>
        </li>
        
        <li class="active">
            Trade Risk
        </li>
    </ol>
</section>
<section class="content">
    <form id="assetDealForm"  action = '{!! url("trade_risk")!!}/{!!$trade_risk->
        risk_id!!}/update' method = 'POST' class="form-horizontal" role="form" enctype="multipart/form-data">
        <div class="row">
            <div class="col-md-12">
                <button type="submit" name="submitBtn" value="Save" class='btn btn-primary btn-inline'>Save</button>
                <button type="submit" name="submitBtn" value="SaveClose" class = 'btn btn-primary btn-inline'>Save  &amp; close</button>
                <a href="{!!url("trade_risk")!!}" class='btn btn-primary btn-inline'>
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
                            <i class="fa fa-fw fa-crosshairs"></i> Edit Trade Risk Detail
                        </h3>
                    </div>
                    <div class="panel-body">
                        <input type = 'hidden' name = '_token' value = '{{Session::token()}}'>
                        <input type="hidden" name="requestUrl" id="requestUrl" value="{!!url(Request::segment(1))!!}">
                        <?php if(Session('merchantId') == 0): ?>
                        <div class="form-group">
                            <label for="merchant_id" class="col-sm-3 control-label">Merchant Name</label>
                            <div class="col-sm-4">
                                <select name="merchant_id" id="merchant_id" class="form-control select21" style="width:100%">
                                    <option></option>
                                    @foreach($merchants as $merchant)
                                        @if($trade_risk->merchant_id == $merchant->merchant_id)
                                            <option value="{{$merchant->merchant_id}}" selected="">{{$merchant->merchant_name}}</option>
                                        @else
                                            <option value="{{$merchant->merchant_id}}">{{$merchant->merchant_name}}</option>
                                        @endif    
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <?php endif; ?>
                        <div class="form-group">
                            <label for="location_id" class="col-sm-3 control-label">Location Name</label>
                            <div class="col-sm-4">
                                <select name="location_id" id="location_id" class="form-control select21" style="width:100%">
                                    <option></option>
                                    @foreach($location_cities as $city)
                                        @if($trade_risk->location_id == $city->city_id)
                                            <option value="{{$city->city_id}}" selected="">{{$city->city_name}}</option>
                                        @else
                                            <option value="{{$city->city_id}}">{{$city->city_name}}</option>
                                        @endif    
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="group_id" class="col-sm-3 control-label">Group Name</label>
                            <div class="col-sm-4">
                                <select name="group_id" id="group_id" class="form-control select21" style="width:100%">
                                    <option></option>
                                    @foreach($staff_groups as $group)
                                        @if($trade_risk->group_id == $group->staff_group_id)
                                            <option value="{{$group->staff_group_id}}" selected="">{{$group->staff_group_name}}</option>
                                        @else
                                            <option value="{{$group->staff_group_id}}">{{$group->staff_group_name}}</option>
                                        @endif    
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
                                        @if($trade_risk->staff_id == $staff->staff_id)
                                            <option value="{{$staff->staff_id}}" selected="">{{$staff->staff_name}}</option>
                                        @else
                                            <option value="{{$staff->staff_id}}">{{$staff->staff_name}}</option>
                                        @endif    
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="staff_account_id" class="col-sm-3 control-label">Staff Account Name</label>
                            <div class="col-sm-4">
                                <select name="staff_account_id" id="staff_account_id" class="form-control select21" style="width:100%">
                                    <option></option>
                                    @foreach($accounts as $account)
                                        @if($trade_risk->staff_account_id == $account->account_id)
                                            <option value="{{$account->account_id}}" selected="">{{$account->account_code_long}}</option>
                                        @else
                                            <option value="{{$account->account_id}}">{{$account->account_code_long}}</option>
                                        @endif    
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="customer_id" class="col-sm-3 control-label">Customer Name</label>
                            <div class="col-sm-4">
                                <select name="customer_id" id="customer_id" class="form-control select21" style="width:100%">
                                    <option></option>
                                    @foreach($customers as $customer)
                                        @if($trade_risk->customer_id == $customer->customer_id)
                                            <option value="{{$customer->customer_id}}" selected="">{{$customer->customer_name}}</option>
                                        @else
                                            <option value="{{$customer->customer_id}}">{{$customer->customer_name}}</option>
                                        @endif

                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="customer_account_id" class="col-sm-3 control-label">Customer Account Name</label>
                            <div class="col-sm-4">
                                <select name="customer_account_id" id="customer_account_id" class="form-control select21" style="width:100%">
                                    <option></option>
                                    @foreach($accounts as $account)
                                        @if($trade_risk->customer_account_id == $account->account_id)
                                            <option value="{{$account->account_id}}" selected="">{{$account->account_code_long}}</option>
                                        @else
                                            <option value="{{$account->account_id}}">{{$account->account_code_long}}</option>
                                        @endif    
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="exchange_id" class="col-sm-3 control-label">Exchange Name</label>
                            <div class="col-sm-4">
                                <select name="exchange_id" id="exchange_id" class="form-control select21" style="width:100%">
                                    <option></option>
                                    @foreach($exchanges as $exchange)
                                        @if($trade_risk->exchange_id == $exchange->exchange_id)
                                            <option value="{{$exchange->exchange_id}}" selected="">{{$exchange->exchange_name}}</option>
                                        @else
                                            <option value="{{$exchange->exchange_id}}">{{$exchange->exchange_name}}</option>
                                        @endif    
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="asset_id" class="col-sm-3 control-label">Asset</label>
                            <div class="col-sm-4">
                                <select name="asset_id" id="asset_id" class="form-control select21" style="width:100%">
                                    <option></option>
                                    @foreach($assets as $asset)
                                        @if($trade_risk->asset_id == $asset->asset_id)
                                            <option value="{{$asset->asset_id}}" selected="">{{$asset->asset_name}}</option>
                                        @else
                                            <option value="{{$asset->asset_id}}">{{$asset->asset_name}}</option>
                                        @endif    
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="entry_timezone" class="col-sm-3 control-label">Entry Timezone</label>
                            <div class="col-sm-4">
                                <select name="entry_timezone" id="entry_timezone" class="form-control select21" style="width:100%">
                                    <option></option>
                                    @foreach($timezones as $timezone)
                                        @if($trade_risk->entry_timezone == $timezone->timezone_id)
                                            <option value="{{$timezone->timezone_id}}" selected="">{{$timezone->timezone_name}}</option>
                                        @else
                                            <option value="{{$timezone->timezone_id}}">{{$timezone->timezone_name}}</option>   
                                        @endif     
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="entry_date" class="col-sm-3 control-label">Entry Date</label>
                            <div class="input-group col-sm-4" style="padding-left: 15px;padding-right: 15px;">
                                    <input id="entry_date" name="entry_date" type="text" value="<?php if($trade_risk->entry_date != 0) { echo substr_replace(substr_replace($trade_risk->entry_date, '-', 4, 0), '-', 7, 0); } else { echo '';}?>" class="form-control pull-left" data-language='en' /> 
                                    <div class="input-group-addon">
                                        <i class="fa fa-fw fa-calendar"></i>
                                    </div> 
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="entry_time" class="col-sm-3 control-label">Entry Time</label>
                            <div class="input-group col-sm-4" style="padding-left: 15px;padding-right: 15px;">
                                @if(isset($trade_risk->entry_time))
                                    <?php
                                        $entryMinutes = $trade_risk->entry_time/60;
                                        $entryHour = sprintf("%02d", floor($entryMinutes/60));
                                        $entryMinute = sprintf("%02d", ($entryMinutes % 60));
                                        $entryTime = $entryHour.':'.$entryMinute;
                                    ?>
                                @endif
                                <input id="entry_time" name="entry_time" type="text" value="<?php echo isset($entryTime)? $entryTime:'' ?>" class="form-control required">
                                <span class="input-group-addon"><i class="fa fa-clock-o"></i></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="price_average" class="col-sm-3 control-label">Price Average</label>
                            <div class="col-sm-4">
                                <input id="price_average" name = "price_average" type="number" class="form-control" value="{{$trade_risk->price_average}}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="trade_exposure" class="col-sm-3 control-label">Trade Exposure</label>
                            <div class="col-sm-4">
                                <input id="trade_exposure" name = "trade_exposure" type="number" class="form-control" value="{{$trade_risk->trade_exposure}}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="settlement_limit" class="col-sm-3 control-label">Settlement Limit</label>
                            <div class="col-sm-4">
                                <input id="settlement_limit" name = "settlement_limit" type="number" class="form-control" value="{{$trade_risk->settlement_limit}}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="settlement_limit_status" class="col-sm-3 control-label">Settlement Limit Status</label>
                            <div class="col-sm-4">
                                <select name="settlement_limit_status" id="settlement_limit_status" class="form-control select21" style="width:100%">
                                    <option></option>
                                    @foreach($trade_status_types as $trade_status_type)
                                        @if($trade_risk->settlement_limit_status == $trade_status_type->trade_status_id)
                                            <option value="{{$trade_status_type->trade_status_id}}" selected="">{{$trade_status_type->trade_status_name}}</option>
                                        @else
                                            <option value="{{$trade_status_type->trade_status_id}}">{{$trade_status_type->trade_status_name}}</option>
                                        @endif    
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="trading_limit_status" class="col-sm-3 control-label">Trading Limit Status</label>
                            <div class="col-sm-4">
                                <select name="trading_limit_status" id="trading_limit_status" class="form-control select21" style="width:100%">
                                    <option></option>
                                    @foreach($trade_status_types as $trade_status_type)
                                       @if($trade_risk->trading_limit_status == $trade_status_type->trade_status_id)
                                            <option value="{{$trade_status_type->trade_status_id}}" selected="">{{$trade_status_type->trade_status_name}}</option>
                                        @else
                                            <option value="{{$trade_status_type->trade_status_id}}">{{$trade_status_type->trade_status_name}}</option>
                                        @endif 
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="trade_limit" class="col-sm-3 control-label">Trade Limit</label>
                            <div class="col-sm-4">
                                <input id="trade_limit" name = "trade_limit" type="number" class="form-control" value="{{$trade_risk->trading_limit}}">
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
<script type="text/javascript" src="{{asset('assets/vendors/select2/js/select2.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/bootstrapvalidator/js/bootstrapValidator.min.js')}}" ></script>
<script src="{{asset('assets/vendors/airdatepicker/js/datepicker.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/vendors/airdatepicker/js/datepicker.en.js')}}" type="text/javascript"></script>
<script  type="text/javascript" src="{{asset('assets/vendors/datedropper/datedropper.js')}}" ></script>
<script  type="text/javascript" src="{{asset('assets/vendors/timedropper/js/timedropper.js')}}" ></script>
<script type="text/javascript" src="{{asset('assets/vendors/select2/js/select2.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/js/custom_js/TradeRiskCreate.js')}}"></script>
@stop