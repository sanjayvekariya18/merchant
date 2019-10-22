@extends('layouts/default')

{{-- Page title --}}
@section('title')
    Create Asset Flow
    @parent
@stop

{{-- page level styles --}}
@section('header_styles')
    <!--page level css -->
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/select2/css/select2.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/select2/css/select2-bootstrap.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/bootstrapvalidator/css/bootstrapValidator.min.css')}}">   
    <!--end of page level css-->
@stop
@section('content')
<section class="content-header">
    <h1>Create New Asset Flow</h1>
    <ol class="breadcrumb">
        <li>
            <a href="index ">
                <i class="fa fa-fw fa-home"></i> Dashboard
            </a>
        </li>
        
        <li class="active">
            Asset Flow
        </li>
    </ol>
</section>
<section class="content">
    <form id="assetFlowForm"  action = '{!!url("asset_flow")!!}' method = 'POST' class="form-horizontal" role="form" enctype="multipart/form-data">
        <div class="row">
            <div class="col-md-12">
                <button type="submit" name="submitBtn" value="Save" class='btn btn-primary btn-inline'>Save</button>
                <button type="submit" name="submitBtn" value="SaveClose" class = 'btn btn-primary btn-inline'>Save  &amp; close</button>
                <a href="{!!url("asset_flow")!!}" class='btn btn-primary btn-inline'>
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
                            <i class="fa fa-fw fa-crosshairs"></i> Add Asset Flow
                        </h3>
                    </div>
                    <div class="panel-body">
                        <input type='hidden' name='_token' value='{{Session::token()}}'>
                        <input type="hidden" name="requestUrl" id="requestUrl" value="{!!url(Request::segment(1))!!}">
                        <?php if(Session('merchantId') == 0): ?>
                            <div class="form-group">
                                <label for="merchant_id" class="col-sm-3 control-label">Merchant Name</label>
                                <div class="col-sm-4">
                                    <select name="merchant_id" id="merchant_id" class="form-control select21" style="width:100%">
                                        <option></option>
                                        @foreach($merchants as $merchant)
                                           <option value="{{$merchant->merchant_id}}">{{$merchant->merchant_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        <?php endif; ?>
                        <div class="form-group">
                            <label for="staff_id" class="col-sm-3 control-label">Staff Name</label>
                            <div class="col-sm-4">
                                <select name="staff_id" id="staff_id" class="form-control select21" style="width:100%">
                                    <option></option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="asset_id" class="col-sm-3 control-label">Asset Name</label>
                            <div class="col-sm-4">
                                <select name="asset_id" id="asset_id" class="form-control select21" style="width:100%">
                                    <option></option>
                                    @foreach($assets as $asset)
                                       <option value="{{$asset->asset_id}}">{{$asset->asset_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="asset_price_lower" class="col-sm-3 control-label">Asset Price Lower</label>
                            <div class="col-sm-4">
                                <input id="asset_price_lower" name = "asset_price_lower" type="number" class="form-control" step="0.01">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="asset_price_upper" class="col-sm-3 control-label">Asset Price Upper</label>
                            <div class="col-sm-4">
                                <input id="asset_price_upper" name = "asset_price_upper" type="number" class="form-control" step="0.01">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="asset_quantity_lower" class="col-sm-3 control-label">Asset Quantity Lower</label>
                            <div class="col-sm-4">
                                <input id="asset_quantity_lower" name = "asset_quantity_lower" type="number" class="form-control" step="0.01">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="asset_quantity_upper" class="col-sm-3 control-label">Asset Quantity Upper</label>
                            <div class="col-sm-4">
                                <input id="asset_quantity_upper" name = "asset_quantity_upper" type="number" class="form-control" step="0.01">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="asset_total_lower" class="col-sm-3 control-label">Asset Total Lower</label>
                            <div class="col-sm-4">
                                <input id="asset_total_lower" name = "asset_total_lower" type="number" class="form-control" step="0.01">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="asset_total_upper" class="col-sm-3 control-label">Asset Total Upper</label>
                            <div class="col-sm-4">
                                <input id="asset_total_upper" name = "asset_total_upper" type="number" class="form-control" step="0.01">
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
<script type="text/javascript" src="{{asset('assets/js/custom_js/AssetFlowCreate.js')}}"></script>
@stop
