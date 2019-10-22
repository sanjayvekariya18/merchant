@extends('layouts/default')

{{-- Page title --}}
@section('title')
    Create Trade Order
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
    <h1>Create New Trade Order</h1>
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
    <form id="assetDealForm"  action = '{!!url("trade_order")!!}' method = 'POST' class="form-horizontal" role="form" enctype="multipart/form-data">
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
                            <i class="fa fa-fw fa-crosshairs"></i> Add Trade Order Detail
                        </h3>
                    </div>
                    <div class="panel-body">
                        <input type = 'hidden' name = '_token' value = '{{Session::token()}}'>
                        <input type="hidden" name="requestUrl" id="requestUrl" value="{!!url(Request::segment(1))!!}">
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
                        <div class="form-group">
                            <label for="location_country" class="col-sm-3 control-label">Country</label>
                            <div class="col-sm-4">
                                <select name="location_country" id="location_country" class="form-control select21" style="width:100%">
                                    <option></option>
                                    @foreach($location_countries as $country)
                                        <option value="{{$country->country_id}}">{{$country->country_name}}</option>
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
                                <select name="location_city_id" id="location_city" class="form-control select21" style="width:100%">
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
                            <label for="staff_account_id" class="col-sm-3 control-label">Staff Account Name</label>
                            <div class="col-sm-4">
                                <select name="staff_account_id" id="staff_account_id" class="form-control select21" style="width:100%">
                                    <option></option>
                                    @foreach($accounts as $account)
                                       <option value="{{$account->account_id}}">{{$account->account_code_long}}</option>
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
                                       <option value="{{$customer->customer_id}}">{{$customer->customer_name}}</option>
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
                                       <option value="{{$account->account_id}}">{{$account->account_code_long}}</option>
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
                                       <option value="{{$exchange->exchange_id}}">{{$exchange->exchange_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="transaction_internal" class="col-sm-3 control-label">Transaction Internal</label>
                            <div class="col-sm-4">
                                <input id="transaction_internal" name = "transaction_internal" type="text" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="side_type_id" class="col-sm-3 control-label">Trade Order Side Type</label>
                            <div class="col-sm-4">
                                <select name="side_type_id" id="side_type_id" class="form-control select21" style="width:100%">
                                    <option></option>
                                    @foreach($trade_order_side_types as $side_type)
                                       <option value="{{$side_type->side_type_id}}">{{$side_type->side_type_name}}</option>
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
                                       <option value="{{$asset->asset_id}}">{{$asset->asset_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="asset_from_price" class="col-sm-3 control-label">Asset From Price</label>
                            <div class="col-sm-4">
                                <input id="asset_from_price" name = "asset_from_price" type="number" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="asset_from_quantity" class="col-sm-3 control-label">Asset From Quantity</label>
                            <div class="col-sm-4">
                                <input id="asset_from_quantity" name = "asset_from_quantity" type="number" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="asset_into_id" class="col-sm-3 control-label">Asset Into</label>
                            <div class="col-sm-4">
                                <select name="asset_into_id" id="asset_into_id" class="form-control select21" style="width:100%">
                                    <option></option>
                                    @foreach($assets as $asset)
                                       <option value="{{$asset->asset_id}}">{{$asset->asset_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="asset_into_price" class="col-sm-3 control-label">Asset Into Price</label>
                            <div class="col-sm-4">
                                <input id="asset_into_price" name = "asset_into_price" type="number" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="asset_into_quantity" class="col-sm-3 control-label">Asset Into Quantity</label>
                            <div class="col-sm-4">
                                <input id="asset_into_quantity" name = "asset_into_quantity" type="number" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="order_type_id" class="col-sm-3 control-label">Trade Order Type</label>
                            <div class="col-sm-4">
                                <select name="order_type_id" id="order_type_id" class="form-control select21" style="width:100%">
                                    <option></option>
                                    @foreach($trade_order_types as $order_type)
                                       <option value="{{$order_type->type_id}}">{{$order_type->type_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="leverage" class="col-sm-3 control-label">Leverage</label>
                            <div class="col-sm-4">
                                <input id="leverage" name = "leverage" type="number" class="form-control">
                            </div>
                        </div> 
                        <div class="form-group">
                            <label for="start_timezone" class="col-sm-3 control-label">Start Timezone</label>
                            <div class="col-sm-4">
                                <select name="start_timezone" id="start_timezone" class="form-control select21" style="width:100%">
                                    <option></option>
                                    @foreach($timezones as $timezone)
                                       <option value="{{$timezone->timezone_id}}">{{$timezone->timezone_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="start_date" class="col-sm-3 control-label">Start Date</label>
                            <div class="input-group col-sm-4" style="padding-left: 15px;padding-right: 15px;">
                                    <input id="start_date" name="start_date" type="text" value="<?= date('Y-m-d') ?>" class="form-control pull-left" data-language='en' /> 
                                    <div class="input-group-addon">
                                        <i class="fa fa-fw fa-calendar"></i>
                                    </div> 
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="start_time" class="col-sm-3 control-label">Start Time</label>
                            <div class="input-group col-sm-4" style="padding-left: 15px;padding-right: 15px;">
                                <input id="start_time" name="start_time" type="text" value="20:00" class="form-control required">
                                <span class="input-group-addon"><i class="fa fa-clock-o"></i></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="expire_timezone" class="col-sm-3 control-label">Expire Timezone</label>
                            <div class="col-sm-4">
                                <select name="expire_timezone" id="expire_timezone" class="form-control select21" style="width:100%">
                                    <option></option>
                                    @foreach($timezones as $timezone)
                                       <option value="{{$timezone->timezone_id}}">{{$timezone->timezone_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="expire_date" class="col-sm-3 control-label">Expire Date</label>
                            <div class="input-group col-sm-4" style="padding-left: 15px;padding-right: 15px;">
                                    <input id="expire_date" name="expire_date" type="text" value="<?= date('Y-m-d') ?>" class="form-control pull-left" data-language='en' /> 
                                    <div class="input-group-addon">
                                        <i class="fa fa-fw fa-calexpirear"></i>
                                    </div> 
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="expire_time" class="col-sm-3 control-label">Expire Time</label>
                            <div class="input-group col-sm-4" style="padding-left: 15px;padding-right: 15px;">
                                <input id="expire_time" name="expire_time" type="text" value="20:00" class="form-control required">
                                <span class="input-group-addon"><i class="fa fa-clock-o"></i></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="fee_amount" class="col-sm-3 control-label">Fee Amount</label>
                            <div class="col-sm-4">
                                <input id="fee_amount" name = "fee_amount" type="number" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="fee_asset" class="col-sm-3 control-label">Asset Fee</label>
                            <div class="col-sm-4">
                                <select name="fee_asset" id="fee_asset" class="form-control select21" style="width:100%">
                                    <option></option>
                                    @foreach($assets as $asset)
                                       <option value="{{$asset->asset_id}}">{{$asset->asset_name}}</option>
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
                                       <option value="{{$account->account_id}}">{{$account->account_code_long}}</option>
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
                                       <option value="{{$status_operation->type_id}}">{{$status_operation->type_name}}</option>
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
                                       <option value="{{$status_fiat->status_fiat_type_id}}">{{$status_fiat->status_fiat_type_name}}</option>
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
                                       <option value="{{$status_crypto->status_crypto_type_id}}">{{$status_crypto->status_crypto_type_name}}</option>
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
<script type="text/javascript" src="{{asset('assets/js/custom_js/TradeOrderCreate.js')}}"></script>
@stop