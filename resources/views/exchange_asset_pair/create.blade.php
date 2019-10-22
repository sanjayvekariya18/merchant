@extends('layouts/default')

{{-- Page title --}}
@section('title')
    Create Exchange Asset Pair
    @parent
@stop

{{-- page level styles --}}
@section('header_styles')
    <!--page level css -->
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/select2/css/select2.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/select2/css/select2-bootstrap.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/bootstrap-switch/css/bootstrap-switch.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/bootstrapvalidator/css/bootstrapValidator.min.css')}}">
    <!--end of page level css-->
@stop
@section('content')
<section class="content-header">
    <h1>Create New Exchange Asset Pair</h1>
    <ol class="breadcrumb">
        <li>
            <a href="index ">
                <i class="fa fa-fw fa-home"></i> Dashboard
            </a>
        </li>
        
        <li class="active">
            Exchange Asset Pair
        </li>
    </ol>
</section>
<section class="content">
    <form id="exchangeAssetPairForm"  action='{!!url("exchange_asset_pair")!!}' method='POST' class="form-horizontal" role="form" enctype="multipart/form-data">
        <div class="row">
            <div class="col-md-12">
                <button type="submit" name="submitBtn" value="Save" class='btn btn-primary btn-inline'>Save</button>
                <button type="submit" name="submitBtn" value="SaveClose" class = 'btn btn-primary btn-inline'>Save  &amp; close</button>
                <a href="{!!url("exchange_asset_pair")!!}" class='btn btn-primary btn-inline'>
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
                            <i class="fa fa-fw fa-crosshairs"></i> Add Exchange Asset Pair
                        </h3>
                    </div>
                    <div class="panel-body">
                        <input type='hidden' name='_token' value='{{Session::token()}}'>
                        <input type="hidden" name="requestUrl" id="requestUrl" value="{!!url(Request::segment(1))!!}">
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
                            <label for="asset_from_id" class="col-sm-3 control-label">Asset From</label>
                            <div class="col-sm-4">
                                <select name="asset_from_id" id="asset_from_id" class="select21 form-control required">
                                    <option></option>
                                    @foreach($assets as $asset)
                                        <option value="{{$asset->asset_id}}">{{$asset->identity_code}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="asset_into_id" class="col-sm-3 control-label">Asset Into</label>
                            <div class="col-sm-4">
                                <select name="asset_into_id" id="asset_into_id" class="select21 form-control required">
                                    <option></option>
                                    @foreach($assets as $asset)
                                        <option value="{{$asset->asset_id}}">{{$asset->identity_code}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="priority" class="col-sm-3 control-label">Priority</label>
                            <div class="col-sm-4">
                                <input id="priority" name="priority" type="number" value="0" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="enable" class="col-sm-3 control-label">Status</label>
                            <div class="col-sm-4">
                                <div class="make-switch" data-on="danger" data-off="default">
                                    <input id="enable" name="enable" value="1" type="checkbox" checked="" />
                                </div>
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
<script type="text/javascript" src="{{asset('assets/vendors/bootstrap-switch/js/bootstrap-switch.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/js/custom_js/ExchangeAssetPairCreate.js')}}"></script>
@stop
