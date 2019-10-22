@extends('layouts/default')
{{-- Page title --}}
@section('title')
    Create Payment Ledger
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
    <h1>Create Payment Ledger</h1>
    <ol class="breadcrumb">
        <li>
            <a href="index ">
                <i class="fa fa-fw fa-home"></i> Dashboard
            </a>
        </li>
        <li class="active">
            Payment Ledger
        </li>
    </ol>
</section>
<section class="content">
    <form id="payment_ledger" method='POST' action='{!!url("payment_ledger")!!}' class="form-horizontal">
        <input type='hidden' name='_token' value='{{Session::token()}}'>
        <div class="row">
            <div class="col-md-12">
                <button type="submit" name="submitBtn" value="Save" class='btn btn-primary btn-inline'>Save</button>
                <button type="submit" name="submitBtn" value="SaveClose" class= 'btn btn-primary btn-inline'>Save &amp; Close</button>
                <a href="{{url('payment_summary')}}" class='btn btn-primary btn-inline'>
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
                            <i class="livicon" data-name="user-add" data-size="18" data-c="#fff" data-hc="#fff" data-loop="true"></i> Add New Payment Ledger
                        </h3>
                        <span class="pull-right">
                            <i class="fa fa-fw fa-chevron-up clickable"></i>
                        </span>
                    </div>
                    <div class="panel-body">
                        <input type='hidden' name='_token' value='{{Session::token()}}'>
                        <input type="hidden" name="requestUrl" id="requestUrl" value="{!!url(Request::segment(1))!!}">
                        <div class="form-group">
                            <label for="summary_id" class="col-sm-3 control-label">Payment Summary</label>
                            <div class="col-sm-4">
                                <select name="summary_id" id="summary_id" class="select21 form-control">
                                    <option></option>
                                    @foreach($paymentSummaries as $paymentSummary)
                                        <option value="{{$paymentSummary->summary_id}}" merchant-id="{{$paymentSummary->merchant_id}}">{{$paymentSummary->payment_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <!-- <div class="form-group">
                            <label for="city_id" class="col-sm-3 control-label">Location City</label>
                            <div class="col-sm-4">
                                <select name="city_id" id="city_id" class="form-control select21" style="width:100%">
                                    <option></option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="location_id" class="col-sm-3 control-label">Location Name</label>
                            <div class="col-sm-4">
                                <select name="location_id" id="location_id" class="select21 form-control">
                                    <option></option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="staff_id" class="col-sm-3 control-label">Staff</label>
                            <div class="col-sm-4">
                                <select name="staff_id" id="staff_id" class="select21 form-control">
                                    <option></option>
                                    @foreach($allStaffs as $allStaff)
                                        <option value="{{$allStaff->staff_id}}">{{$allStaff->staff_fname}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="merchant_account_id" class="col-sm-3 control-label">Merchant Account Name</label>
                            <div class="col-sm-4">
                                <select name="merchant_account_id" id="merchant_account_id" class="select21 form-control">
                                    <option></option>
                                    @foreach($accounts as $account)
                                        <option value="{{$account->account_id}}">{{$account->account_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="customer_id" class="col-sm-3 control-label">Customer Name</label>
                            <div class="col-sm-4">
                                <select name="customer_id" id="customer_id" class="form-control select21" style="width:100%">
                                    <option></option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="customer_account_id" class="col-sm-3 control-label">Customer Account Name</label>
                            <div class="col-sm-4">
                                <select name="customer_account_id" id="customer_account_id" class="select21 form-control">
                                    <option></option>
                                    @foreach($accounts as $account)
                                        <option value="{{$account->account_id}}">{{$account->account_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>-->
                        <div class="form-group">
                            <label for="vendor_name" class="col-sm-3 control-label">Vendor Name</label>
                            <div class="col-sm-4">
                                <input id="vendor_name" name="vendor_name" type="text" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="payment_type" class="col-sm-3 control-label">Payment Type</label>
                            <div class="col-sm-4">
                                <select name="payment_type" id="payment_type" class="select21 form-control">
                                    <option></option>
                                    @foreach($paymentTypes as $paymentType)
                                        <option value="{{$paymentType->type_id}}">{{$paymentType->type_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="payment_description" class="col-sm-3 control-label">Payment Description</label>
                            <div class="col-sm-4">
                                <input id="payment_description" name="payment_description" type="text" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="payment_name" class="col-sm-3 control-label">Payment Name</label>
                            <div class="col-sm-4">
                                <input id="payment_name" name="payment_name" type="text" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="payment_notes" class="col-sm-3 control-label">Payment Notes</label>
                            <div class="col-sm-4">
                                <input id="payment_notes" name="payment_notes" type="text" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="payment_quantity" class="col-sm-3 control-label">Payment Quantity</label>
                            <div class="col-sm-4">
                                <input id="payment_quantity" name="payment_quantity" type="text" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="payment_unit_price" class="col-sm-3 control-label">Payment Unit Price</label>
                            <div class="col-sm-4">
                                <input id="payment_unit_price" name="payment_unit_price" type="text" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="payment_amount" class="col-sm-3 control-label">Payment Amount</label>
                            <div class="col-sm-4">
                                <input id="payment_amount" name="payment_amount" type="text" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="payment_asset_id" class="col-sm-3 control-label">Payment Asset</label>
                            <div class="col-sm-4">
                                <select name="payment_asset_id" id="payment_asset_id" class="select21 form-control">
                                    <option></option>
                                    @foreach($assets as $asset)
                                        <option value="{{$asset->asset_id}}">{{$asset->asset_name}} ({{$asset->asset_code}})</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="payment_fee_id" class="col-sm-3 control-label">Payment Fee Id</label>
                            <div class="col-sm-4">
                                <select name="payment_fee_id" id="payment_fee_id" class="select21 form-control">
                                    <option></option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="fee_summary_id" class="col-sm-3 control-label">Fee Summary Id</label>
                            <div class="col-sm-4">
                                <select name="fee_summary_id" id="fee_summary_id" class="select21 form-control">
                                    <option></option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="transaction_type_id" class="col-sm-3 control-label">Transaction Type</label>
                            <div class="col-sm-4">
                                <select name="transaction_type_id" id="transaction_type_id" class="select21 form-control">
                                    <option></option>
                                    @foreach($paymentTypes as $paymentType)
                                        <option value="{{$paymentType->type_id}}">{{$paymentType->type_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="transaction_address" class="col-sm-3 control-label">Transaction Address</label>
                            <div class="col-sm-4">
                                <input id="transaction_address" name="transaction_address" type="text" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="transaction_address_url" class="col-sm-3 control-label">Transaction Address Url</label>
                            <div class="col-sm-4">
                                <input id="transaction_address_url" name="transaction_address_url" type="text" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="transaction_photo_url" class="col-sm-3 control-label">Transaction Photo Url</label>
                            <div class="col-sm-4">
                                <input id="transaction_photo_url" name="transaction_photo_url" type="text" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="transaction_internal_ref" class="col-sm-3 control-label">Transaction Internal Ref.</label>
                            <div class="col-sm-4">
                                <input id="transaction_internal_ref" name="transaction_internal_ref" type="text" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="transaction_root" class="col-sm-3 control-label">Transaction Root</label>
                            <div class="col-sm-4">
                                <input id="transaction_root" name="transaction_root" type="text" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="ledger_hash" class="col-sm-3 control-label">Ledger Hash</label>
                            <div class="col-sm-4">
                                <input id="ledger_hash" name="ledger_hash" type="text" class="form-control">
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
<script type="text/javascript" src="{{asset('assets/js/custom_js/PaymentLedgerCreate.js')}}"></script>
<!-- end of page level js -->
@stop
