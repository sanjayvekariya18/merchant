@extends('layouts/default')

{{-- Page title --}}
@section('title')
    Create Asset Deal
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
    <h1>Create New Asset Deal</h1>
    <ol class="breadcrumb">
        <li>
            <a href="index ">
                <i class="fa fa-fw fa-home"></i> Dashboard
            </a>
        </li>
        
        <li class="active">
            Asset Deal
        </li>
    </ol>
</section>
<section class="content">
    <form id="assetDealForm"  action = '{!!url("asset_deal")!!}' method = 'POST' class="form-horizontal" role="form" enctype="multipart/form-data">
        <div class="row">
            <div class="col-md-12">
                <button type="submit" name="submitBtn" value="Save" class='btn btn-primary btn-inline'>Save</button>
                <button type="submit" name="submitBtn" value="SaveClose" class = 'btn btn-primary btn-inline'>Save  &amp; close</button>
                <a href="{!!url("asset_deal")!!}" class='btn btn-primary btn-inline'>
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
                            <i class="fa fa-fw fa-crosshairs"></i> Add Asset Deal Detail
                        </h3>
                    </div>
                    <div class="panel-body">
                        <input type = 'hidden' name = '_token' value = '{{Session::token()}}'>
                        <div class="form-group">
                            <label for="trader_id" class="col-sm-3 control-label">Tradder Name</label>
                            <div class="col-sm-4">
                                <select name="trader_id" id="trader_id" class="form-control select21" style="width:100%">
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
                                       <option value="{{$account->account_id}}">{{$account->account_code_long}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="transaction_id" class="col-sm-3 control-label">Transaction Id</label>
                            <div class="col-sm-4">
                                <input id="transaction_id" name = "transaction_id" type="text" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="quantity" class="col-sm-3 control-label">Quantity</label>
                            <div class="col-sm-4">
                                <input id="quantity" name = "quantity" type="text" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="side_type_id" class="col-sm-3 control-label">Side Type</label>
                            <div class="col-sm-4">
                                <select name="side_type_id" id="side_type_id" class="form-control select21" style="width:100%">
                                    <option></option>
                                    @foreach($side_types as $side_type)
                                       <option value="{{$side_type->side_type_id}}">{{$side_type->side_type_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="asset_quote_id" class="col-sm-3 control-label">Quote Currency</label>
                            <div class="col-sm-4">
                                <select name="asset_quote_id" id="asset_quote_id" class="form-control select21" style="width:100%">
                                    <option></option>
                                    @foreach($assets as $asset)
                                       <option value="{{$asset->asset_id}}">{{$asset->asset_name}}</option>
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
                                       <option value="{{$timezone->timezone_id}}">{{$timezone->timezone_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="entry_date" class="col-sm-3 control-label">Entry Date</label>
                            <div class="input-group col-sm-4" style="padding-left: 15px;padding-right: 15px;">
                                    <input id="entry_date" name="entry_date" type="text" value="<?= date('Y-m-d') ?>" class="form-control pull-left" data-language='en' /> 
                                    <div class="input-group-addon">
                                        <i class="fa fa-fw fa-calendar"></i>
                                    </div> 
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="entry_time" class="col-sm-3 control-label">Entry Time</label>
                            <div class="input-group col-sm-4" style="padding-left: 15px;padding-right: 15px;">
                                <input id="entry_time" name="entry_time" type="text" value="20:00" class="form-control required">
                                <span class="input-group-addon"><i class="fa fa-clock-o"></i></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="price_index" class="col-sm-3 control-label">Price Index</label>
                            <div class="col-sm-4">
                                <input id="price_index" name = "price_index" type="number" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="price_quote" class="col-sm-3 control-label">Price Quote</label>
                            <div class="col-sm-4">
                                <input id="price_quote" name = "price_quote" type="number" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="price_fee" class="col-sm-3 control-label">Price Fee</label>
                            <div class="col-sm-4">
                                <input id="price_fee" name = "price_fee" type="number" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="price_fee_rate" class="col-sm-3 control-label">Price Fee Rate</label>
                            <div class="col-sm-4">
                                <input id="price_fee_rate" name = "price_fee_rate" type="number" class="form-control">
                            </div>
                        </div> 
                        <div class="form-group">
                            <label for="counterparty_id" class="col-sm-3 control-label">Counter Party Name</label>
                            <div class="col-sm-4">
                                <select name="counterparty_id" id="counterparty_id" class="form-control select21" style="width:100%">
                                    <option></option>
                                    @foreach($merchants as $merchant)
                                       <option value="{{$merchant->merchant_id}}">{{$merchant->merchant_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="asset_base_id" class="col-sm-3 control-label">Base Currency</label>
                            <div class="col-sm-4">
                                <select name="asset_base_id" id="asset_base_id" class="form-control select21" style="width:100%">
                                    <option></option>
                                    @foreach($assets as $asset)
                                       <option value="{{$asset->asset_id}}">{{$asset->asset_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="asset_base_quote" class="col-sm-3 control-label">Asset Base Quote</label>
                            <div class="col-sm-4">
                                <input id="asset_base_quote" name = "asset_base_quote" type="number" class="form-control">
                            </div>
                        </div> 
                        <div class="form-group">
                            <label for="asset_base_rate" class="col-sm-3 control-label">Asset Base Rate</label>
                            <div class="col-sm-4">
                                <input id="asset_base_rate" name = "asset_base_rate" type="number" class="form-control">
                            </div>
                        </div> 
                        <div class="form-group">
                            <label for="account_uuid" class="col-sm-3 control-label">Account Uuid</label>
                            <div class="col-sm-4">
                                <input id="account_uuid" name = "account_uuid" type="text" class="form-control">
                            </div>
                        </div> 
                        <div class="form-group">
                            <label for="type_id" class="col-sm-3 control-label">Status Operation Type</label>
                            <div class="col-sm-4">
                                <select name="type_id" id="type_id" class="form-control select21" style="width:100%">
                                    <option></option>
                                    @foreach($status_operations as $status_operation)
                                       <option value="{{$status_operation->type_id}}">{{$status_operation->type_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="status_fiat_type_id" class="col-sm-3 control-label">Status Fiat Type</label>
                            <div class="col-sm-4">
                                <select name="status_fiat_type_id" id="status_fiat_type_id" class="form-control select21" style="width:100%">
                                    <option></option>
                                    @foreach($status_fiats as $status_fiat)
                                       <option value="{{$status_fiat->status_fiat_type_id}}">{{$status_fiat->status_fiat_type_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="status_crypto_type_id" class="col-sm-3 control-label">Status Crypto Type</label>
                            <div class="col-sm-4">
                                <select name="status_crypto_type_id" id="status_crypto_type_id" class="form-control select21" style="width:100%">
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
<script type="text/javascript" src="{{asset('assets/js/custom_js/AssetDealCreate.js')}}"></script>
@stop