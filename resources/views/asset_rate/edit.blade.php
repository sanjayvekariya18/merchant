@extends('layouts/default')
{{-- Page title --}}
@section('title')
    Edit Asset Rate
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
    <h1>Edit Asset Rate</h1>
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
    <form id="asset_rate_edit" method='POST' action='{!! url("asset_rate")!!}/{!!$asset_rate->rate_id!!}/update' class="form-horizontal bv-form">
        <input type = 'hidden' name = '_token' value = '{{Session::token()}}'>
        <input name = "rate_id" type="hidden" value="{!!$asset_rate->rate_id!!}">

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
                            <i class="livicon" data-name="user-add" data-size="18" data-c="#fff" data-hc="#fff" data-loop="true"></i> Edit Asset Rate
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
                                        @if($asset->asset_id == $asset_rate->asset_from_id)
                                            <option value="{{$asset->asset_id}}" selected>{{$asset->identity_code}}</option>
                                        @else
                                            <option value="{{$asset->asset_id}}">{{$asset->identity_code}}</option>
                                        @endif
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
                                        @if($asset->asset_id == $asset_rate->asset_into_id)
                                            <option value="{{$asset->asset_id}}" selected>{{$asset->identity_code}}</option>
                                        @else
                                            <option value="{{$asset->asset_id}}">{{$asset->identity_code}}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="asset_bid_price" class="col-sm-2 control-label">Asset Bid Price</label>
                            <div class="col-sm-5">
                                <input id="asset_bid_price" name="asset_bid_price" value="{{$asset_rate->asset_bid_price}}" type="text" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="asset_ask_price" class="col-sm-2 control-label">Asset Ask Price</label>
                            <div class="col-sm-5">
                                <input id="asset_ask_price" name="asset_ask_price" value="{{$asset_rate->asset_ask_price}}" type="text" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="asset_last_price" class="col-sm-2 control-label">Asset Last Price</label>
                            <div class="col-sm-5">
                                <input id="asset_last_price" name="asset_last_price" value="{{$asset_rate->asset_last_price}}" type="text" class="form-control">
                            </div>
                        </div>
                        <!--<div class="form-group">
                            <label for="asset_last_date" class="col-sm-2 control-label">Asset Last Date</label>
                            <div class="col-sm-5">
                                <input id="asset_last_date" name="asset_last_date" value="{{($asset_rate->asset_last_date)?date('m/d/Y',strtotime($asset_rate->asset_last_date)):''}}" type="text" class="form-control pull-left" data-language='en' placeholder="MM/DD/YYYY"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="asset_last_time" class="col-sm-2 control-label">Asset Last Time</label>
                            <div class="input-group col-sm-4" style="padding-left: 15px;">
                                <input id="asset_last_time" name="asset_last_time" value="{{($asset_rate->asset_last_time)?date('H:i',$asset_rate->asset_last_time):''}}" type="text" class="form-control required">
                                <span class="input-group-addon"><i class="fa fa-clock-o"></i></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="asset_last_timezone" class="col-sm-2 control-label">Asset Last Timezone</label>
                            <div class="col-sm-5">
                                <select name="asset_last_timezone" id="asset_last_timezone" class="select21 form-control required">
                                    <option></option>
                                    @foreach($timezones as $timezone)
                                        @if($timezone->timezone_id == $asset_rate->asset_last_timezone)
                                            <option value="{{$timezone->timezone_id}}" selected>{{$timezone->timezone_name}}</option>
                                        @else
                                            <option value="{{$timezone->timezone_id}}">{{$timezone->timezone_name}}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>-->
                        <input id="asset_last_date" name="asset_last_date" value="{{($asset_rate->asset_last_date)?date('m/d/Y',strtotime($asset_rate->asset_last_date)):''}}" type="hidden" class="form-control pull-left" data-language='en' placeholder="MM/DD/YYYY"/>
                        <input id="asset_last_time" name="asset_last_time" value="{{($asset_rate->asset_last_time)?date('H:i',$asset_rate->asset_last_time):''}}" type="hidden" class="form-control required">

                        <div class="form-group">
                            <label for="asset_source_id" class="col-sm-2 control-label">Asset Source Id</label>
                            <div class="col-sm-5">
                                <select name="asset_source_id" id="asset_source_id" class="select21 form-control required">
                                    <option></option>
                                    @foreach($identitySource as $source)
                                        @if($source->identity_id == $asset_rate->asset_source_id)
                                            <option value="{{$source->identity_id}}" selected>{{$source->identity_name}}</option>
                                        @else
                                            <option value="{{$source->identity_id}}">{{$source->identity_name}}</option>
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
<script type="text/javascript" src="{{asset('assets/vendors/timedropper/js/timedropper.js')}}" ></script>
<script type="text/javascript" src="{{asset('assets/vendors/select2/js/select2.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/js/custom_js/AssetRateEdit.js')}}"></script>
<!-- end of page level js -->
@stop