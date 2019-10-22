@extends('layouts/default')

{{-- Page title --}}
@section('title')
    Exchange Asset Rate
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
    <h1>Create New Exchange Asset Rate</h1>
    <ol class="breadcrumb">
        <li>
            <a href="index ">
                <i class="fa fa-fw fa-home"></i> Dashboard
            </a>
        </li>
        
        <li class="active">
            Exchange Asset Rate
        </li>
    </ol>
</section>
<section class="content">
    <form id="exchangeRatesForm"  action = '{!!url("exchange_rates")!!}' method = 'POST' class="form-horizontal" role="form" enctype="multipart/form-data">
        <div class="row">
            <div class="col-md-12">
                <button type="submit" name="submitBtn" value="Save" class='btn btn-primary btn-inline'>Save</button>
                <button type="submit" name="submitBtn" value="SaveClose" class = 'btn btn-primary btn-inline'>Save  &amp; close</button>
                <a href="{!!url("exchange_rates")!!}" class='btn btn-primary btn-inline'>
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
                            <i class="fa fa-fw fa-crosshairs"></i> Add Exchange Asset Rate Detail
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
                                       <option value="{{$exchange->exchange_id}}">{{$exchange->exchange_name}}</option>
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
                                       <option value="{{$asset->asset_id}}">{{$asset->asset_name}}</option>
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
                                       <option value="{{$asset->asset_id}}">{{$asset->asset_name}}</option>
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
                                       <option value="{{$asset->asset_id}}">{{$asset->asset_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div> 
                        <div class="form-group">
                            <label for="level_margin_call" class="col-sm-3 control-label">Level Margin Call</label>
                            <div class="col-sm-4">
                                <input id="level_margin_call" name = "level_margin_call" type="text" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="level_margin_liquidation" class="col-sm-3 control-label">Level Margin Liquidation</label>
                            <div class="col-sm-4">
                                <input id="level_margin_liquidation" name = "level_margin_liquidation" type="text" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="leverage_buy" class="col-sm-3 control-label">Leverage Buy</label>
                            <div class="col-sm-4">
                                <input id="leverage_buy" name = "leverage_buy" type="text" class="form-control">
                            </div>
                        </div>  
                        <div class="form-group">
                            <label for="leverage_sell" class="col-sm-3 control-label">Leverage Sell</label>
                            <div class="col-sm-4">
                                <input id="leverage_sell" name = "leverage_sell" type="text" class="form-control">
                            </div>
                        </div>  
                        <div class="form-group">
                            <label for="margin_percent" class="col-sm-3 control-label">Margin Percent</label>
                            <div class="col-sm-4">
                                <input id="margin_percent" name = "margin_percent" type="text" class="form-control">
                            </div>
                        </div> 
                        <div class="form-group">
                            <label for="funding_start" class="col-sm-3 control-label">Funding Start</label>
                            <div class="input-group col-sm-4" style="padding-left: 15px;padding-right: 15px;">
                                    <input id="funding_start" name="funding_start" type="text" value="<?= date('Y-m-d') ?>" class="form-control pull-left" data-language='en' /> 
                                    <div class="input-group-addon">
                                        <i class="fa fa-fw fa-calendar"></i>
                                    </div> 
                            </div>
                        </div> 
                        <div class="form-group">
                            <label for="funding_interval" class="col-sm-3 control-label">Funding Interval</label>
                            <div class="col-sm-4">
                                <input id="funding_interval" name = "funding_interval" type="text" class="form-control">
                            </div>
                        </div> 
                        <div class="form-group">
                            <label for="funding_rate" class="col-sm-3 control-label">Funding Rate</label>
                            <div class="col-sm-4">
                                <input id="funding_rate" name = "funding_rate" type="text" class="form-control">
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
<script type="text/javascript" src="{{asset('assets/js/custom_js/ExchangeRateCreate.js')}}"></script>
@stop