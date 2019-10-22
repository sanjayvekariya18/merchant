@extends('layouts/default')
{{-- Page title --}}
@section('title')
    Create Position
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
    <h1>Create Position</h1>
    <ol class="breadcrumb">
        <li>
            <a href="index ">
                <i class="fa fa-fw fa-home"></i> Dashboard
            </a>
        </li>
        <li class="active">
            Position
        </li>
    </ol>
</section>
<section class="content">
    <form id="position" method='POST' action='{!!url("position")!!}' class="form-horizontal">
        <input type = 'hidden' name = '_token' value = '{{Session::token()}}'>
        <div class="row">
            <div class="col-md-12">
                <button type="submit" name="submitBtn" value="Save" class='btn btn-primary btn-inline'>Save</button>
                <button type="submit" name="submitBtn" value="SaveClose" class= 'btn btn-primary btn-inline'>Save &amp; Close</button>
                <a href="{{url('position')}}" class='btn btn-primary btn-inline'>
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
                            <i class="livicon" data-name="user-add" data-size="18" data-c="#fff" data-hc="#fff" data-loop="true"></i> Add New Position
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
                                        <option value="{{$account->account_id}}">{{$account->account_code_long." (".$account->account_code_short.")"}}</option>
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
                                        <option value="{{$asset->asset_id}}">{{$asset->identity_code}}</option>
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
                                        <option value="{{$asset->asset_id}}">{{$asset->identity_code}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="quantity_size" class="col-sm-2 control-label">Quantity Size</label>
                            <div class="col-sm-5">
                                <input id="quantity_size" name="quantity_size" type="text" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="quantity_remaining" class="col-sm-2 control-label">Quantity Remaining</label>
                            <div class="col-sm-5">
                                <input id="quantity_remaining" name="quantity_remaining" type="text" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="quantity_asset_id" class="col-sm-2 control-label">Quantity Asset</label>
                            <div class="col-sm-5">
                                <select name="quantity_asset_id" id="quantity_asset_id" class="select21 form-control">
                                    <option></option>
                                    @foreach($assets as $asset)
                                        <option value="{{$asset->asset_id}}">{{$asset->identity_code}}</option>
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
                            <label for="price_average" class="col-sm-2 control-label">Price Average</label>
                            <div class="col-sm-5">
                                <input id="price_average" name="price_average" type="text" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="leverage" class="col-sm-2 control-label">Leverage</label>
                            <div class="col-sm-5">
                                <input id="leverage" name="leverage" type="text" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="fee_asset" class="col-sm-2 control-label">Fee Asset</label>
                            <div class="col-sm-5">
                                <select name="fee_asset" id="fee_asset" class="select21 form-control">
                                    <option></option>
                                    @foreach($assets as $asset)
                                        <option value="{{$asset->asset_id}}">{{$asset->identity_code}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="pnl" class="col-sm-2 control-label">PNL</label>
                            <div class="col-sm-5">
                                <input id="pnl" name="pnl" type="text" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="pnl_percent" class="col-sm-2 control-label">PNL Percet</label>
                            <div class="col-sm-5">
                                <input id="pnl_percent" name="pnl_percent" type="text" class="form-control">
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
<script type="text/javascript" src="{{asset('assets/js/custom_js/PositionCreate.js')}}"></script>
<!-- end of page level js -->
@stop