@extends('layouts/default')

{{-- Page title --}}
@section('title')
    Trad Fee Schedule
    @parent
@stop

{{-- page level styles --}}
@section('header_styles')
    <!--page level css -->
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/select2/css/select2.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/select2/css/select2-bootstrap.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/bootstrapvalidator/css/bootstrapValidator.min.css')}}">    
    <link href="{{asset('assets/vendors/airdatepicker/css/datepicker.min.css')}}" rel="stylesheet" type="text/css">
    <!--end of page level css-->
@stop
@section('content')
<section class="content-header">
    <h1>Edit Trad Fee Schedule</h1>
    <ol class="breadcrumb">
        <li>
            <a href="index ">
                <i class="fa fa-fw fa-home"></i> Dashboard
            </a>
        </li>
        
        <li class="active">
            Trad Fee Schedule
        </li>
    </ol>
</section>
<section class="content">
    <form id="assetForm"  action = '{!! url("trade_fee_schedule")!!}/{!!$trade_fee_schedule->
        trading_schedule_id!!}/update' method = 'POST' class="form-horizontal" role="form" enctype="multipart/form-data">
        <div class="row">
            <div class="col-md-12">
                <button type="submit" name="submitBtn" value="Save" class='btn btn-primary btn-inline'>Save</button>
                <button type="submit" name="submitBtn" value="SaveClose" class = 'btn btn-primary btn-inline'>Save  &amp; close</button>
                <a href="{!!url("trade_fee_schedule")!!}" class='btn btn-primary btn-inline'>
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
                            <i class="fa fa-fw fa-crosshairs"></i> Edit Trad Fee Schedule Detail
                        </h3>
                    </div>
                    <div class="panel-body">
                        <input type = 'hidden' name = '_token' value = '{{Session::token()}}'>
                        <div class="form-group">
                            <label for="exchange_id" class="col-sm-3 control-label">Exchange Name</label>
                            <div class="col-sm-4">
                                <select name="exchange_id" id="exchange_id" class="form-control select21" style="width:100%">
                                    <option></option>
                                    @foreach($exchanges as $exchange)
                                        @if($trade_fee_schedule->exchange_id == $exchange->exchange_id)
                                            <option value="{{$exchange->exchange_id}}" selected="">{{$exchange->exchange_name}}</option>
                                        @else
                                            <option value="{{$exchange->exchange_id}}">{{$exchange->exchange_name}}</option>
                                        @endif    
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="base_currency_id" class="col-sm-3 control-label">Base Currency</label>
                            <div class="col-sm-4">
                                <select name="base_currency_id" id="base_currency_id" class="form-control select21" style="width:100%">
                                    <option></option>
                                    @foreach($assets as $asset)
                                        @if($trade_fee_schedule->base_currency_id == $asset->asset_id)
                                            <option value="{{$asset->asset_id}}" selected="">{{$asset->asset_name}}</option>
                                        @else
                                            <option value="{{$asset->asset_id}}">{{$asset->asset_name}}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="quote_currency_id" class="col-sm-3 control-label">Quote Currency</label>
                            <div class="col-sm-4">
                                <select name="quote_currency_id" id="quote_currency_id" class="form-control select21" style="width:100%">
                                    <option></option>
                                    @foreach($assets as $asset)
                                        @if($trade_fee_schedule->quote_currency_id == $asset->asset_id)
                                            <option value="{{$asset->asset_id}}" selected="">{{$asset->asset_name}}</option>
                                        @else
                                            <option value="{{$asset->asset_id}}">{{$asset->asset_name}}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="volume_currency_id" class="col-sm-3 control-label">Volume Currency</label>
                            <div class="col-sm-4">
                                <select name="volume_currency_id" id="volume_currency_id" class="form-control select21" style="width:100%">
                                    <option></option>
                                    @foreach($assets as $asset)
                                        @if($trade_fee_schedule->volume_currency_id == $asset->asset_id)
                                            <option value="{{$asset->asset_id}}" selected="">{{$asset->asset_name}}</option>
                                        @else
                                            <option value="{{$asset->asset_id}}">{{$asset->asset_name}}</option>
                                        @endif    
                                    @endforeach
                                </select>
                            </div>
                        </div> 
                        <div class="form-group">
                            <label for="trading_volume_lower" class="col-sm-3 control-label">Trading Volume Lower</label>
                            <div class="col-sm-4">
                                <input id="trading_volume_lower" name = "trading_volume_lower" type="text" class="form-control" value = '{!!$trade_fee_schedule->trading_volume_lower!!}'>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="trading_volume_upper" class="col-sm-3 control-label">Trading Volume Upper</label>
                            <div class="col-sm-4">
                                <input id="trading_volume_upper" name = "trading_volume_upper" type="text" class="form-control" value = '{!!$trade_fee_schedule->trading_volume_upper!!}'>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="trading_fees_taker" class="col-sm-3 control-label">Trading Fees Taker</label>
                            <div class="col-sm-4">
                                <input id="trading_fees_taker" name = "trading_fees_taker" type="text" class="form-control" value = '{!!$trade_fee_schedule->trading_fees_taker!!}'>
                            </div>
                        </div>  
                        <div class="form-group">
                            <label for="trading_fees_maker" class="col-sm-3 control-label">Trading Fees Maker</label>
                            <div class="col-sm-4">
                                <input id="trading_fees_maker" name = "trading_fees_maker" type="text" class="form-control" value = '{!!$trade_fee_schedule->trading_fees_maker!!}'>
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
<script type="text/javascript" src="{{asset('assets/vendors/select2/js/select2.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/js/custom_js/TradingScheduleEdit.js')}}"></script>
@stop