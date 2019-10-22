@extends('layouts/default')
{{-- Page title --}}
@section('title')
    Create Transaction Summary
    @parent
@stop

{{-- page level styles --}}
@section('header_styles')
    <!--page level css -->
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/select2/css/select2.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/select2/css/select2-bootstrap.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/bootstrapvalidator/css/bootstrapValidator.min.css')}}">
    <link href="{{asset('assets/vendors/airdatepicker/css/datepicker.min.css')}}" rel="stylesheet" type="text/css">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/timedropper/css/timedropper.css')}}">
    <!--end of page level css-->
@stop
@section('content')

<section class="content-header">
    <h1>Create Transaction Summary</h1>
    <ol class="breadcrumb">
        <li>
            <a href="index ">
                <i class="fa fa-fw fa-home"></i> Dashboard
            </a>
        </li>
        <li class="active">
            Transaction Summary
        </li>
    </ol>
</section>
<section class="content">
    <form id="transaction_summary" method='POST' action='{!!url("transaction_summary")!!}' class="form-horizontal">
        <input type = 'hidden' name = '_token' value = '{{Session::token()}}'>
        <div class="row">
            <div class="col-md-12">
                <!-- <button type="submit" name="submitBtn" value="Save" class='btn btn-primary btn-inline'>Save</button> -->
                <button type="submit" name="submitBtn" value="SaveClose" class= 'btn btn-primary btn-inline'>Save &amp; Close</button>
                <a href="{{url('transaction_summary')}}" class='btn btn-primary btn-inline'>
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
                            <i class="livicon" data-name="user-add" data-size="18" data-c="#fff" data-hc="#fff" data-loop="true"></i> Add New Transaction Summary
                        </h3>
                        <span class="pull-right">
                            <i class="fa fa-fw fa-chevron-up clickable"></i>
                        </span>
                    </div>
                    <div class="panel-body">
                        <div class="form-group">
                            <label for="trader_id" class="col-sm-2 control-label">Trader Name</label>
                            <div class="col-sm-5">
                                <select name="trader_id" id="trader_id" class="select21 form-control">
                                    <option></option>
                                    @foreach($hase_staffs as $hase_staff)
                                        <option value="{{$hase_staff->staff_id}}">{{$hase_staff->staff_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="account_id" class="col-sm-2 control-label">Account Name</label>
                            <div class="col-sm-5">
                                <select name="account_id" id="account_id" class="select21 form-control">
                                    <option></option>
                                    @foreach($accounts as $account)
                                        <option value="{{$account->account_id}}">{{$account->identity_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="exchange_id" class="col-sm-2 control-label">Exchange Name</label>
                            <div class="col-sm-5">
                                <select name="exchange_id" id="exchange_id" class="select21 form-control">
                                    <option></option>
                                    @foreach($exchanges as $exchange)
                                        <option value="{{$exchange->exchange_id}}">{{$exchange->identity_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="trade_timezone" class="col-sm-2 control-label">Timezone Name</label>
                            <div class="col-sm-5">
                                <select name="trade_timezone" id="trade_timezone" class="select21 form-control">
                                    <option></option>
                                    @foreach($timezones as $timezone)
                                        <option value="{{$timezone->timezone_id}}">{{$timezone->timezone_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="trade_date" class="col-sm-2 control-label">Trade Date</label>
                            <div class="col-sm-5">
                                <input id="trade_date" name="trade_date" type="text" class="form-control pull-left" data-language='en' placeholder="MM/DD/YYYY"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="trade_time" class="col-sm-2 control-label">Trade Time</label>
                            <div class="input-group col-sm-4" style="padding-left: 15px;">
                                <input id="trade_time" name="trade_time" type="text" value="20:00" class="form-control required">
                                <span class="input-group-addon"><i class="fa fa-clock-o"></i></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="side_type_id" class="col-sm-2 control-label">Trade Side Type</label>
                            <div class="col-sm-5">
                                <select name="side_type_id" id="side_type_id" class="select21 form-control">
                                    <option></option>
                                    @foreach($sideTypes as $sideType)
                                        <option value="{{$sideType->side_type_id}}">{{$sideType->side_type_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="asset_trade_id" class="col-sm-2 control-label">Asset Trade</label>
                            <div class="col-sm-5">
                                <select name="asset_trade_id" id="asset_trade_id" class="select21 form-control">
                                    <option></option>
                                    @foreach($assets as $asset)
                                        <option value="{{$asset->asset_id}}">{{$asset->identity_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="asset_base_id" class="col-sm-2 control-label">Asset Base</label>
                            <div class="col-sm-5">
                                <select name="asset_base_id" id="asset_base_id" class="select21 form-control">
                                    <option></option>
                                    @foreach($assets as $asset)
                                        <option value="{{$asset->asset_id}}">{{$asset->identity_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="quantity_executed" class="col-sm-2 control-label">Quantity Executed</label>
                            <div class="col-sm-5">
                                <input id="quantity_executed" name="quantity_executed" type="text" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="quantity_asset_id" class="col-sm-2 control-label">Quantity Asset</label>
                            <div class="col-sm-5">
                                <select name="quantity_asset_id" id="quantity_asset_id" class="select21 form-control">
                                    <option></option>
                                    @foreach($assets as $asset)
                                        <option value="{{$asset->asset_id}}">{{$asset->identity_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="trade_order_type_id" class="col-sm-2 control-label">Trade Order Type</label>
                            <div class="col-sm-5">
                                <select name="trade_order_type_id" id="trade_order_type_id" class="select21 form-control">
                                    <option></option>
                                    @foreach($tradeOrderTypes as $tradeOrderType)
                                        <option value="{{$tradeOrderType->trade_order_type_id}}">{{$tradeOrderType->trade_order_type_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="price" class="col-sm-2 control-label">Price</label>
                            <div class="col-sm-5">
                                <input id="price" name="price" type="text" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="trade_status_type_id" class="col-sm-2 control-label">Trade Status Type</label>
                            <div class="col-sm-5">
                                <select name="trade_status_type_id" id="trade_status_type_id" class="select21 form-control">
                                    <option></option>
                                    @foreach($tradeStatusTypes as $tradeStatusType)
                                        <option value="{{$tradeStatusType->trade_status_id}}">{{$tradeStatusType-> trade_status_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="trade_reason_type_id" class="col-sm-2 control-label">Trade Reason Type</label>
                            <div class="col-sm-5">
                                <select name="trade_reason_type_id" id="trade_reason_type_id" class="select21 form-control">
                                    <option></option>
                                    @foreach($tradeReasonTypes as $tradeReasonType)
                                        <option value="{{$tradeReasonType->trade_reason_type_id}}">{{$tradeReasonType-> trade_reason_type_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="trade_transaction_type_id" class="col-sm-2 control-label">Trade Transaction Type</label>
                            <div class="col-sm-5">
                                <select name="trade_transaction_type_id" id="trade_transaction_type_id" class="select21 form-control">
                                    <option></option>
                                    @foreach($tradeTransactionTypes as $tradeTransactionType)
                                        <option value="{{$tradeTransactionType->trade_transaction_type_id}}">{{$tradeTransactionType-> trade_transaction_type_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="transaction_fee" class="col-sm-2 control-label">Transaction Fee</label>
                            <div class="col-sm-5">
                                <input id="transaction_fee" name="transaction_fee" type="text" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="transaction_fee_asset" class="col-sm-2 control-label">Transaction Fee Asset</label>
                            <div class="col-sm-5">
                                <select name="transaction_fee_asset" id="transaction_fee_asset" class="select21 form-control">
                                    <option></option>
                                    @foreach($assets as $asset)
                                        <option value="{{$asset->asset_id}}">{{$asset->identity_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="transaction_exchange" class="col-sm-2 control-label">Transaction Exchange</label>
                            <div class="col-sm-5">
                                <input id="transaction_exchange" name="transaction_exchange" type="text" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="transaction_internal" class="col-sm-2 control-label">Transaction Internal</label>
                            <div class="col-sm-5">
                                <input id="transaction_internal" name="transaction_internal" type="text" class="form-control">
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
<script type="text/javascript" src="{{asset('assets/vendors/timedropper/js/timedropper.js')}}" ></script>
<script type="text/javascript" src="{{asset('assets/vendors/select2/js/select2.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/js/custom_js/TransactionSummaryCreate.js')}}"></script>
<!-- end of page level js -->
@stop