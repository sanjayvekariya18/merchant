@extends('layouts/default')

{{-- Page title --}}
@section('title')
    Create Exchange Asset List
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
    <h1>Create New Exchange Asset List</h1>
    <ol class="breadcrumb">
        <li>
            <a href="index ">
                <i class="fa fa-fw fa-home"></i> Dashboard
            </a>
        </li>
        
        <li class="active">
            Exchange Asset List
        </li>
    </ol>
</section>
<section class="content">
    <form id="exchangeAssetListForm"  action='{!!url("exchange_asset_list")!!}' method='POST' class="form-horizontal" role="form" enctype="multipart/form-data">
        <div class="row">
            <div class="col-md-12">
                <button type="submit" name="submitBtn" value="Save" class='btn btn-primary btn-inline'>Save</button>
                <button type="submit" name="submitBtn" value="SaveClose" class = 'btn btn-primary btn-inline'>Save  &amp; close</button>
                <a href="{!!url("exchange_asset_list")!!}" class='btn btn-primary btn-inline'>
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
                            <i class="fa fa-fw fa-crosshairs"></i> Add Exchange Asset List
                        </h3>
                    </div>
                    <div class="panel-body">
                        <input type = 'hidden' name = '_token' value = '{{Session::token()}}'>
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
                            <label for="asset_id" class="col-sm-3 control-label">Asset Name</label>
                            <div class="col-sm-4">
                                <select name="asset_id[]" id="asset_id" class="form-control select21" multiple="" style="width:100%">
                                    <option></option>
                                    @foreach($assets as $asset)
                                       <option value="{{$asset->asset_id}}" asset_code="{{$asset->asset_code}}">{{$asset->asset_name}} ({{$asset->asset_code}})</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="panel panel-primary">
                                    <div class="panel-heading">
                                        <h3 class="panel-title">
                                            Select Exchange Asset  
                                        </h3>
                                    </div>
                                    <div class="panel-body" id="panelList">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="row">
                                                    <label class="col-md-4">Asset Name</label>
                                                    <label class="col-md-3">Asset Code</label>
                                                    <label class="col-md-3">Priority</label>
                                                    <label class="col-md-2">Status</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> 
                    </div>
                </div>
            </div>
        </div>
    </form>
    <div class="row" id="assetDemo" style="display: none;">
        <div class="col-md-4">
            <label id="asset_name"></label>
            <input type="hidden" name="" value="" id="assetId">
        </div>
        <div class="col-sm-3">
            <input id="asset_code" name="asset_code" type="text" class="form-control" required="">
        </div>
        <div class="col-sm-3">
            <input id="priority" name="priority" type="number" class="form-control" value="0" required="">
        </div>
        <div class="col-sm-2">
            <input name="status" type="checkbox" id="status" data-on-text="On" data-on-color="success" data-off-color="danger" data-off-text="Off" checked="true" />
        </div>
    </div>
</section>
@endsection

{{-- page level scripts --}}
@section('footer_scripts')
<!-- begining of page level js -->
<script type="text/javascript" src="{{asset('assets/vendors/moment/js/moment.min.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/select2/js/select2.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/bootstrap-switch/js/bootstrap-switch.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/js/custom_js/ExchangeAssetListCreate.js')}}"></script>
@stop
