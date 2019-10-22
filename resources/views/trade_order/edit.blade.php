@extends('layouts/default')

{{-- Page title --}}
@section('title')
    Edit Trade Order
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
    <h1>Edit Trade Order</h1>
    <ol class="breadcrumb">
        <li>
            <a href="index ">
                <i class="fa fa-fw fa-home"></i> Dashboard
            </a>
        </li>
        
        <li class="active">
            Trade Order
        </li>
    </ol>
</section>
<section class="content">
    <form id="assetDealForm"  action = '{!! url("trade_order")!!}/{!!$trade_order->
        order_id!!}/update' method = 'POST' class="form-horizontal" role="form" enctype="multipart/form-data">
        <div class="row">
            <div class="col-md-12">
                <button type="submit" name="submitBtn" value="Save" class='btn btn-primary btn-inline'>Save</button>
                <button type="submit" name="submitBtn" value="SaveClose" class = 'btn btn-primary btn-inline'>Save  &amp; close</button>
                <a href="{!!url("trade_order")!!}" class='btn btn-primary btn-inline'>
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
                            <i class="fa fa-fw fa-crosshairs"></i> Edit Trade Order Detail
                        </h3>
                    </div>
                    <div class="panel-body">
                        <input type = 'hidden' name = '_token' value = '{{Session::token()}}'>
                        <input type="hidden" name="requestUrl" id="requestUrl" value="{!!url(Request::segment(1))!!}">
                        <input type = 'hidden' id ='location_state_id' value='{{$trade_order->state_id}}'>
                        <input type = 'hidden' id ='location_county_id' value='{{$trade_order->county_id}}'>
                        <input type = 'hidden' id ='location_city_id' value='{{$trade_order->location_city_id}}'>
                        <div class="form-group">
                            <label for="merchant_id" class="col-sm-3 control-label">Merchant Name</label>
                            <div class="col-sm-4">
                                <select name="merchant_id" id="merchant_id" class="form-control select21" style="width:100%">
                                    <option></option>
                                    @foreach($merchants as $merchant)
                                        @if($trade_order->merchant_id == $merchant->merchant_id)
                                            <option value="{{$merchant->merchant_id}}" selected="">{{$merchant->merchant_name}}</option>
                                        @else
                                            <option value="{{$merchant->merchant_id}}">{{$merchant->merchant_name}}</option>
                                        @endif    
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="location_country" class="col-sm-3 control-label">Country</label>
                            <div class="col-sm-4">
                                <select name="location_country" id="location_country" class="form-control select21" style="width:100%">
                                    <option></option>
                                    @foreach($location_countries as $country)
                                        @if($trade_order->country_id == $country->country_id)
                                            <option value="{{$country->country_id}}" selected="">{{$country->country_name}}</option>
                                        @else
                                            <option value="{{$country->country_id}}">{{$country->country_name}}</option>
                                        @endif        
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="location_state" class="col-sm-3 control-label">State</label>
                            <div class="col-sm-4">
                                <select name="location_state" id="location_state" class="form-control select21" style="width:100%">
                                    <option></option>
                                    
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="location_county" class="col-sm-3 control-label">County</label>
                            <div class="col-sm-4">
                                <select name="location_county" id="location_county" class="form-control select21" style="width:100%">
                                    <option></option>
                                    
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="location_city" class="col-sm-3 control-label">City</label>
                            <div class="col-sm-4">
                                <select name="location_city" id="location_city" class="form-control select21" style="width:100%">
                                    <option></option>
                                    
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="group_id" class="col-sm-3 control-label">Group Name</label>
                            <div class="col-sm-4">
                                <select name="group_id" id="group_id" class="form-control select21" style="width:100%">
                                    <option></option>
                                    @foreach($staff_groups as $group)
                                        @if($trade_order->group_id == $group->staff_group_id)
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
                                        @if($trade_order->staff_id == $staff->staff_id)
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
                                        @if($trade_order->staff_account_id == $account->account_id)
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
                                        @if($trade_order->customer_id == $customer->customer_id)
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
                                        @if($trade_order->customer_account_id == $account->account_id)
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
                                        @if($trade_order->exchange_id == $exchange->exchange_id)
                                            <option value="{{$exchange->exchange_id}}" selected="">{{$exchange->exchange_name}}</option>
                                        @else
                                            <option value="{{$exchange->exchange_id}}">{{$exchange->exchange_name}}</option>
                                        @endif    
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="transaction_internal" class="col-sm-3 control-label">Transaction Internal</label>
                            <div class="col-sm-4">
                                <input id="transaction_internal" name = "transaction_internal" type="text" class="form-control" value="{{$trade_order->transaction_internal_ref}}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="side_type_id" class="col-sm-3 control-label">Trade Order Side Type</label>
                            <div class="col-sm-4">
                                <select name="side_type_id" id="side_type_id" class="form-control select21" style="width:100%">
                                    <option></option>
                                    @foreach($trade_order_side_types as $side_type)
                                        @if($trade_order->side_type_id == $side_type->side_type_id)
                                            <option value="{{$side_type->side_type_id}}" selected="">{{$side_type->side_type_name}}</option>
                                        @else
                                            <option value="{{$side_type->side_type_id}}">{{$side_type->side_type_name}}</option>
                                        @endif    
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="asset_from_id" class="col-sm-3 control-label">Asset From</label>
                            <div class="col-sm-4">
                                <select name="asset_from_id" id="asset_from_id" class="form-control select21" style="width:100%">
                                    <option></option>
                                    @foreach($assets as $asset)
                                        @if($trade_order->asset_from_id == $asset->asset_id)
                                            <option value="{{$asset->asset_id}}" selected="">{{$asset->asset_name}}</option>
                                        @else
                                            <option value="{{$asset->asset_id}}">{{$asset->asset_name}}</option>
                                        @endif    
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="asset_from_price" class="col-sm-3 control-label">Asset From Price</label>
                            <div class="col-sm-4">
                                <input id="asset_from_price" name = "asset_from_price" type="number" class="form-control" value="{{$trade_order->asset_from_price}}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="asset_from_quantity" class="col-sm-3 control-label">Asset From Quantity</label>
                            <div class="col-sm-4">
                                <input id="asset_from_quantity" name = "asset_from_quantity" type="number" class="form-control" value="{{$trade_order->asset_from_quantity}}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="asset_into_id" class="col-sm-3 control-label">Asset Into</label>
                            <div class="col-sm-4">
                                <select name="asset_into_id" id="asset_into_id" class="form-control select21" style="width:100%">
                                    <option></option>
                                    @foreach($assets as $asset)
                                        @if($trade_order->asset_into_id == $asset->asset_id)
                                            <option value="{{$asset->asset_id}}" selected="">{{$asset->asset_name}}</option>
                                        @else
                                            <option value="{{$asset->asset_id}}">{{$asset->asset_name}}</option>
                                        @endif    
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="asset_into_price" class="col-sm-3 control-label">Asset Into Price</label>
                            <div class="col-sm-4">
                                <input id="asset_into_price" name = "asset_into_price" type="number" class="form-control" value="{{$trade_order->asset_into_price}}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="asset_into_quantity" class="col-sm-3 control-label">Asset Into Quantity</label>
                            <div class="col-sm-4">
                                <input id="asset_into_quantity" name = "asset_into_quantity" type="number" class="form-control" value="{{$trade_order->asset_into_quantity}}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="order_type_id" class="col-sm-3 control-label">Trade Order Type</label>
                            <div class="col-sm-4">
                                <select name="order_type_id" id="order_type_id" class="form-control select21" style="width:100%">
                                    <option></option>
                                    @foreach($trade_order_types as $order_type)
                                        @if($trade_order->order_type_id == $order_type->type_id)
                                            <option value="{{$order_type->type_id}}" selected="">{{$order_type->type_name}}</option>
                                        @else
                                            <option value="{{$order_type->type_id}}">{{$order_type->type_name}}</option>
                                        @endif    
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="leverage" class="col-sm-3 control-label">Leverage</label>
                            <div class="col-sm-4">
                                <input id="leverage" name = "leverage" type="number" class="form-control" value="{{$trade_order->leverage}}">
                            </div>
                        </div> 
                        <div class="form-group">
                            <label for="start_timezone" class="col-sm-3 control-label">Start Timezone</label>
                            <div class="col-sm-4">
                                <select name="start_timezone" id="start_timezone" class="form-control select21" style="width:100%">
                                    <option></option>
                                    @foreach($timezones as $timezone)
                                        @if($trade_order->start_timezone == $timezone->timezone_id)
                                            <option value="{{$timezone->timezone_id}}" selected="">{{$timezone->timezone_name}}</option>
                                        @else
                                            <option value="{{$timezone->timezone_id}}">{{$timezone->timezone_name}}</option>   
                                        @endif     
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="start_date" class="col-sm-3 control-label">Start Date</label>
                            <div class="input-group col-sm-4" style="padding-left: 15px;padding-right: 15px;">
                                    <input id="start_date" name="start_date" type="text" value="<?php if($trade_order->start_date != 0) { echo substr_replace(substr_replace($trade_order->start_date, '-', 4, 0), '-', 7, 0); } else { echo '';}?>" class="form-control pull-left" data-language='en' /> 
                                    <div class="input-group-addon">
                                        <i class="fa fa-fw fa-calendar"></i>
                                    </div> 
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="start_time" class="col-sm-3 control-label">Start Time</label>
                            <div class="input-group col-sm-4" style="padding-left: 15px;padding-right: 15px;">
                                @if(isset($trade_order->start_time))
                                    <?php
                                        $startMinutes = $trade_order->start_time/60;
                                        $startHour = sprintf("%02d", floor($startMinutes/60));
                                        $startMinute = sprintf("%02d", ($startMinutes % 60));
                                        $startTime = $startHour.':'.$startMinute;
                                    ?>
                                @endif
                                <input id="start_time" name="start_time" type="text" value="<?php echo isset($startTime)? $startTime:'' ?>" class="form-control required">
                                <span class="input-group-addon"><i class="fa fa-clock-o"></i></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="expire_timezone" class="col-sm-3 control-label">Expire Timezone</label>
                            <div class="col-sm-4">
                                <select name="expire_timezone" id="expire_timezone" class="form-control select21" style="width:100%">
                                    <option></option>
                                    @foreach($timezones as $timezone)
                                        @if($trade_order->expire_timezone == $timezone->timezone_id)
                                            <option value="{{$timezone->timezone_id}}" selected="">{{$timezone->timezone_name}}</option>
                                        @else
                                            <option value="{{$timezone->timezone_id}}">{{$timezone->timezone_name}}</option>
                                        @endif    
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="expire_date" class="col-sm-3 control-label">Expire Date</label>
                            <div class="input-group col-sm-4" style="padding-left: 15px;padding-right: 15px;">
                                    <input id="expire_date" name="expire_date" type="text" value="<?php if($trade_order->expire_date != 0) { echo substr_replace(substr_replace($trade_order->expire_date, '-', 4, 0), '-', 7, 0); } else { echo '';}?>" class="form-control pull-left" data-language='en' /> 
                                    <div class="input-group-addon">
                                        <i class="fa fa-fw fa-calexpirear"></i>
                                    </div> 
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="expire_time" class="col-sm-3 control-label">Expire Time</label>
                            <div class="input-group col-sm-4" style="padding-left: 15px;padding-right: 15px;">
                                @if(isset($trade_order->expire_time))
                                    <?php
                                        $expireMinutes = $trade_order->expire_time/60;
                                        $expireHour = sprintf("%02d", floor($expireMinutes/60));
                                        $expireMinute = sprintf("%02d", ($expireMinutes % 60));
                                        $expireTime = $expireHour.':'.$expireMinute;
                                    ?>
                                @endif
                                <input id="expire_time" name="expire_time" type="text" value="<?php echo isset($expireTime)? $expireTime:'' ?>" class="form-control required">
                                <span class="input-group-addon"><i class="fa fa-clock-o"></i></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="fee_amount" class="col-sm-3 control-label">Fee Amount</label>
                            <div class="col-sm-4">
                                <input id="fee_amount" name = "fee_amount" type="number" class="form-control" value="{{$trade_order->fee_amount}}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="fee_asset" class="col-sm-3 control-label">Asset Fee</label>
                            <div class="col-sm-4">
                                <select name="fee_asset" id="fee_asset" class="form-control select21" style="width:100%">
                                    <option></option>
                                    @foreach($assets as $asset)
                                        @if($trade_order->fee_asset == $asset->asset_id)
                                            <option value="{{$asset->asset_id}}" selected="">{{$asset->asset_name}}</option>
                                        @else
                                            <option value="{{$asset->asset_id}}">{{$asset->asset_name}}</option>
                                        @endif    
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="fee_referrer" class="col-sm-3 control-label">Fee Referrer</label>
                            <div class="col-sm-4">
                                <select name="fee_referrer" id="fee_referrer" class="form-control select21" style="width:100%">
                                    <option></option>
                                    @foreach($accounts as $account)
                                        @if($trade_order->fee_referrer == $account->account_id)
                                            <option value="{{$account->account_id}}" selected="">{{$account->account_code_long}}</option>
                                        @else
                                            <option value="{{$account->account_id}}">{{$account->account_code_long}}</option>
                                        @endif    
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="status_operation" class="col-sm-3 control-label">Status Operation Type</label>
                            <div class="col-sm-4">
                                <select name="status_operation" id="status_operation" class="form-control select21" style="width:100%">
                                    <option></option>
                                    @foreach($status_operations as $status_operation)
                                        @if($trade_order->status_operation == $status_operation->type_id)
                                            <option value="{{$status_operation->type_id}}" selected="">{{$status_operation->type_name}}</option>
                                       @else
                                            <option value="{{$status_operation->type_id}}">{{$status_operation->type_name}}</option>
                                       @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="status_fiat" class="col-sm-3 control-label">Status Fiat Type</label>
                            <div class="col-sm-4">
                                <select name="status_fiat" id="status_fiat" class="form-control select21" style="width:100%">
                                    <option></option>
                                    @foreach($status_fiats as $status_fiat)
                                        @if($trade_order->status_fiat == $status_fiat->status_fiat_type_id)
                                            <option value="{{$status_fiat->status_fiat_type_id}}" selected="">{{$status_fiat->status_fiat_type_name}}</option>
                                        @else
                                            <option value="{{$status_fiat->status_fiat_type_id}}">{{$status_fiat->status_fiat_type_name}}</option>
                                        @endif    
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="status_crypto" class="col-sm-3 control-label">Status Crypto Type</label>
                            <div class="col-sm-4">
                                <select name="status_crypto" id="status_crypto" class="form-control select21" style="width:100%">
                                    <option></option>
                                    @foreach($status_cryptos as $status_crypto)
                                        @if($trade_order->status_crypto == $status_crypto->status_crypto_type_id)
                                            <option value="{{$status_crypto->status_crypto_type_id}}" selected="">{{$status_crypto->status_crypto_type_name}}</option>
                                        @else
                                            <option value="{{$status_crypto->status_crypto_type_id}}">{{$status_crypto->status_crypto_type_name}}</option>
                                        @endif    
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
<script type="text/javascript" src="{{asset('assets/vendors/moment/js/moment.min.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/select2/js/select2.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/bootstrapvalidator/js/bootstrapValidator.min.js')}}" ></script>
<script src="{{asset('assets/vendors/airdatepicker/js/datepicker.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/vendors/airdatepicker/js/datepicker.en.js')}}" type="text/javascript"></script>
<script  type="text/javascript" src="{{asset('assets/vendors/datedropper/datedropper.js')}}" ></script>
<script  type="text/javascript" src="{{asset('assets/vendors/timedropper/js/timedropper.js')}}" ></script>
<script type="text/javascript" src="{{asset('assets/vendors/select2/js/select2.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/js/custom_js/TradeOrderEdit.js')}}"></script>
@stop