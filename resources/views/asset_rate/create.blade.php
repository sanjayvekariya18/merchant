@extends('layouts/default')
{{-- Page title --}}
@section('title')
    Create Asset Rate
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
    <h1>Create Asset Rate</h1>
    <ol class="breadcrumb">
        <li>
            <a href="index ">
                <i class="fa fa-fw fa-home"></i> Dashboard
            </a>
        </li>
        <li class="active">
            Asset Rate
        </li>
    </ol>
</section>
<section class="content">
    <form id="asset_rate" method='POST' action='{!!url("asset_rate")!!}' class="form-horizontal bv-form">
        <input type = 'hidden' name = '_token' value = '{{Session::token()}}'>
        <div class="row">
            <div class="col-md-12">
                <button type="submit" name="submitBtn" value="Save" class='btn btn-primary btn-inline'>Save</button>
                <button type="submit" name="submitBtn" value="SaveClose" class= 'btn btn-primary btn-inline'>Save &amp; Close</button>
                <a href="{{url('asset_rate')}}" class='btn btn-primary btn-inline'>
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
                            <i class="livicon" data-name="user-add" data-size="18" data-c="#fff" data-hc="#fff" data-loop="true"></i> Add New Asset Rate
                        </h3>
                        <span class="pull-right">
                            <i class="fa fa-fw fa-chevron-up clickable"></i>
                        </span>
                    </div>
                    <div class="panel-body">
                        <div class="form-group">
                            <label for="asset_from_id" class="col-sm-2 control-label">Asset From</label>
                            <div class="col-sm-5">
                                <select name="asset_from_id" id="asset_from_id" class="select21 form-control required">
                                    <option></option>
                                    @foreach($assets as $asset)
                                        <option value="{{$asset->asset_id}}">{{$asset->identity_code}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="asset_into_id" class="col-sm-2 control-label">Asset Into</label>
                            <div class="col-sm-5">
                                <select name="asset_into_id" id="asset_into_id" class="select21 form-control required">
                                    <option></option>
                                    @foreach($assets as $asset)
                                        <option value="{{$asset->asset_id}}">{{$asset->identity_code}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="asset_buy_price" class="col-sm-2 control-label">Asset Bid Price</label>
                            <div class="col-sm-5">
                                <input id="asset_bid_price" name="asset_bid_price" type="text" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="asset_ask_price" class="col-sm-2 control-label">Asset Ask Price</label>
                            <div class="col-sm-5">
                                <input id="asset_ask_price" name="asset_ask_price" type="text" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="asset_last_price" class="col-sm-2 control-label">Asset Last Price</label>
                            <div class="col-sm-5">
                                <input id="asset_last_price" name="asset_last_price" type="text" class="form-control">
                            </div>
                        </div>
                        <!--<div class="form-group">
                            <label for="asset_last_date" class="col-sm-2 control-label">Asset Last Date</label>
                            <div class="col-sm-5">
                                <input id="asset_last_date" name="asset_last_date" type="text" class="form-control pull-left" data-language='en' placeholder="MM/DD/YYYY"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="asset_last_time" class="col-sm-2 control-label">Asset Last Time</label>
                            <div class="input-group col-sm-4" style="padding-left: 15px;">
                                <input id="asset_last_time" name="asset_last_time" type="text" value="20:00" class="form-control required">
                                <span class="input-group-addon"><i class="fa fa-clock-o"></i></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="asset_last_timezone" class="col-sm-2 control-label">Asset Last Timezone</label>
                            <div class="col-sm-5">
                                <select name="asset_last_timezone" id="asset_last_timezone" class="select21 form-control required">
                                    <option></option>
                                    @foreach($timezones as $timezone)
                                        <option value="{{$timezone->timezone_id}}">{{$timezone->timezone_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>-->
                        <input id="asset_last_date" name="asset_last_date" type="hidden" value="<?= date('Y-m-d') ?>" class="form-control pull-left" data-language='en' />
                        <input id="asset_last_time" name="asset_last_time" type="hidden" value="20:00" class="form-control required">
                        <div class="form-group">
                            <label for="asset_source_id" class="col-sm-2 control-label">Asset Source Id</label>
                            <div class="col-sm-5">
                                <select name="asset_source_id" id="asset_source_id" class="select21 form-control required">
                                    <option></option>
                                    @foreach($identitySource as $source)
                                        <option value="{{$source->identity_id}}">{{$source->identity_name}}</option>
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
<script type="text/javascript" src="{{asset('assets/vendors/timedropper/js/timedropper.js')}}" ></script>
<script type="text/javascript" src="{{asset('assets/vendors/select2/js/select2.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/js/custom_js/AssetRateCreate.js')}}"></script>
<!-- end of page level js -->
@stop