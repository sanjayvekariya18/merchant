@extends('layouts/default')
{{-- Page title --}}
@section('title')
    Edit Transaction Summary
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
    <h1>Edit Transaction Summary</h1>
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
    <form id="transaction_summary" method='POST' action='{!! url("transaction_summary")!!}/{!!$transaction_summary->
        transaction_summary_id!!}/update' class="form-horizontal">
        
        <input type = 'hidden' name = '_token' value = '{{Session::token()}}'>
        <input name = "transaction_summary_id" type="hidden" value="{!!$transaction_summary->transaction_summary_id!!}">

        <div class="row">
            <div class="col-md-12">
                <button type="submit" name="submitBtn" value="Save" class='btn btn-primary btn-inline'>Save</button>
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
                            <i class="livicon" data-name="user-add" data-size="18" data-c="#fff" data-hc="#fff" data-loop="true"></i> Edit Transaction Summary
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
                                        @if($hase_staff->staff_id == $transaction_summary->trader_id)
                                            <option value="{{$hase_staff->staff_id}}" selected>{{$hase_staff->staff_name}}</option>
                                        @else
                                            <option value="{{$hase_staff->staff_id}}">
                                            {{$hase_staff->staff_name}}</option>
                                        @endif
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
                                        @if($account->account_id == $transaction_summary->account_id)
                                            <option value="{{$account->account_id}}" selected>{{$account->identity_name}}</option>
                                        @else
                                            <option value="{{$account->account_id}}">{{$account->identity_name}}</option>
                                        @endif
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
                                        @if($exchange->exchange_id == $transaction_summary->exchange_id)
                                            <option value="{{$exchange->exchange_id}}" selected>{{$exchange->identity_name}}</option>
                                        @else
                                            <option value="{{$exchange->exchange_id}}">{{$exchange->identity_name}}</option>
                                        @endif
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
                                        @if($timezone->timezone_id == $transaction_summary->trade_timezone)
                                            <option value="{{$timezone->timezone_id}}" selected>{{$timezone->timezone_name}}</option>
                                        @else
                                            <option value="{{$timezone->timezone_id}}">{{$timezone->timezone_name}}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="trade_date" class="col-sm-2 control-label">Trade Date</label>
                            <div class="col-sm-5">
                                <input id="trade_date" name="trade_date" value="{{($transaction_summary->trade_date)?date('m-d-Y',strtotime($transaction_summary->trade_date)):''}}" type="text" class="form-control pull-left" data-language='en' placeholder="MM/DD/YYYY"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="trade_time" class="col-sm-2 control-label">Reserve Time</label>
                            <div class="input-group col-sm-4" style="padding-left: 15px;">
                                <input id="trade_time" name="trade_time" value="{{($transaction_summary->trade_time)?$transaction_summary->trade_time:''}}" type="text" value="" class="form-control required">
                                <span class="input-group-addon"><i class="fa fa-clock-o"></i></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="side_type_id" class="col-sm-2 control-label">Trade Side Type</label>
                            <div class="col-sm-5">
                                <select name="side_type_id" id="side_type_id" class="select21 form-control">
                                    <option></option>
                                    @foreach($sideTypes as $sideType)
                                        @if($sideType->side_type_id == $transaction_summary->side_type_id)
                                            <option value="{{$sideType->side_type_id}}" selected>{{$sideType->side_type_name}}</option>
                                        @else
                                            <option value="{{$sideType->side_type_id}}">{{$sideType->side_type_name}}</option>
                                        @endif
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
                                        @if($asset->asset_id == $transaction_summary->asset_trade_id)
                                            <option value="{{$asset->asset_id}}" selected>{{$asset->identity_name}}</option>
                                        @else
                                            <option value="{{$asset->asset_id}}">{{$asset->identity_name}}</option>
                                        @endif
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
                                        @if($asset->asset_id == $transaction_summary->asset_base_id)
                                            <option value="{{$asset->asset_id}}" selected>{{$asset->identity_name}}</option>
                                        @else
                                            <option value="{{$asset->asset_id}}">{{$asset->identity_name}}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="quantity_executed" class="col-sm-2 control-label">Quantity Executed</label>
                            <div class="col-sm-5">
                                <input id="quantity_executed" name="quantity_executed" value="{{$transaction_summary->quantity_executed}}" type="text" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="quantity_asset_id" class="col-sm-2 control-label">Quantity Asset</label>
                            <div class="col-sm-5">
                                <select name="quantity_asset_id" id="quantity_asset_id" class="select21 form-control">
                                    <option></option>
                                    @foreach($assets as $asset)
                                        @if($asset->asset_id == $transaction_summary->quantity_asset_id)
                                            <option value="{{$asset->asset_id}}" selected>{{$asset->identity_name}}</option>
                                        @else
                                            <option value="{{$asset->asset_id}}">{{$asset->identity_name}}</option>
                                        @endif
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
                                        @if($tradeOrderType->trade_order_type_id == $transaction_summary->trade_order_type_id)
                                            <option value="{{$tradeOrderType->trade_order_type_id}}" selected>{{$tradeOrderType->trade_order_type_name}}</option>
                                        @else
                                            <option value="{{$tradeOrderType->trade_order_type_id}}">{{$tradeOrderType->trade_order_type_name}}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="price" class="col-sm-2 control-label">Price</label>
                            <div class="col-sm-5">
                                <input id="price" name="price" value="{{$transaction_summary->price}}" type="text" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="trade_status_type_id" class="col-sm-2 control-label">Trade Status Type</label>
                            <div class="col-sm-5">
                                <select name="trade_status_type_id" id="trade_status_type_id" class="select21 form-control">
                                    <option></option>
                                    @foreach($tradeStatusTypes as $tradeStatusType)
                                        @if($tradeStatusType->trade_status_id == $transaction_summary->trade_status_type_id)
                                            <option value="{{$tradeStatusType->trade_status_id}}" selected>{{$tradeStatusType-> trade_status_name}}</option>
                                        @else
                                            <option value="{{$tradeStatusType->trade_status_id}}">{{$tradeStatusType-> trade_status_name}}</option>
                                        @endif
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
                                        @if($tradeReasonType->trade_reason_type_id == $transaction_summary->trade_reason_type_id)
                                            <option value="{{$tradeReasonType->trade_reason_type_id}}" selected>{{$tradeReasonType-> trade_reason_type_name}}</option>
                                        @else
                                            <option value="{{$tradeReasonType->trade_reason_type_id}}">{{$tradeReasonType-> trade_reason_type_name}}</option>
                                        @endif
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
                                        @if($tradeTransactionType->trade_transaction_type_id == $transaction_summary->trade_transaction_type_id)
                                            <option value="{{$tradeTransactionType->trade_transaction_type_id}}" selected>{{$tradeTransactionType-> trade_transaction_type_name}}</option>
                                        @else
                                            <option value="{{$tradeTransactionType->trade_transaction_type_id}}">{{$tradeTransactionType-> trade_transaction_type_name}}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="transaction_fee" class="col-sm-2 control-label">Transaction Fee</label>
                            <div class="col-sm-5">
                                <input id="transaction_fee" name="transaction_fee" value="{{$transaction_summary->transaction_fee}}" type="text" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="transaction_fee_asset" class="col-sm-2 control-label">Transaction Fee Asset</label>
                            <div class="col-sm-5">
                                <select name="transaction_fee_asset" id="transaction_fee_asset" class="select21 form-control">
                                    <option></option>
                                    @foreach($assets as $asset)
                                        @if($asset->asset_id == $transaction_summary->transaction_fee_asset)
                                            <option value="{{$asset->asset_id}}" selected>{{$asset->identity_name}}</option>
                                        @else
                                            <option value="{{$asset->asset_id}}">{{$asset->identity_name}}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="transaction_exchange" class="col-sm-2 control-label">Transaction Exchange</label>
                            <div class="col-sm-5">
                                <input id="transaction_exchange" name="transaction_exchange" value="{{$transaction_summary->transaction_exchange}}" type="text" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="transaction_internal" class="col-sm-2 control-label">Transaction Internal</label>
                            <div class="col-sm-5">
                                <input id="transaction_internal" name="transaction_internal" value="{{$transaction_summary->transaction_internal}}" type="text" class="form-control">
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
<script type="text/javascript" src="{{asset('assets/js/custom_js/TransactionSummaryEdit.js')}}"></script>
<!-- end of page level js -->
@stop