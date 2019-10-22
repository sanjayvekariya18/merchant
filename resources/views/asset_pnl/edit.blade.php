@extends('layouts/default')

{{-- Page title --}}
@section('title')
    Create Asset Profit & Loss
    @parent
@stop

{{-- page level styles --}}
@section('header_styles')
    <!--page level css -->
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/select2/css/select2.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/select2/css/select2-bootstrap.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/bootstrapvalidator/css/bootstrapValidator.min.css')}}">   
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datedropper/datedropper.css')}}">
    <link href="{{asset('assets/vendors/airdatepicker/css/datepicker.min.css')}}" rel="stylesheet" type="text/css">
    <!--end of page level css-->
@stop
@section('content')
<section class="content-header">
    <h1>Edit Asset Profit & Loss</h1>
    <ol class="breadcrumb">
        <li>
            <a href="index ">
                <i class="fa fa-fw fa-home"></i> Dashboard
            </a>
        </li>
        
        <li class="active">
            Asset Profit & Loss
        </li>
    </ol>
</section>
<section class="content">
    <form id="assetpnlForm"  action = '{!! url("asset_pnl")!!}/{!!$asset_pnl->
        pnl_id!!}/update' method = 'POST' class="form-horizontal" role="form" enctype="multipart/form-data">
        <div class="row">
            <div class="col-md-12">
                <button type="submit" name="submitBtn" value="Save" class='btn btn-primary btn-inline'>Save</button>
                <button type="submit" name="submitBtn" value="SaveClose" class = 'btn btn-primary btn-inline'>Save  &amp; close</button>
                <a href="{!!url("asset_pnl")!!}" class='btn btn-primary btn-inline'>
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
                            <i class="fa fa-fw fa-crosshairs"></i> Edit Asset Profit & Loss Detail
                        </h3>
                    </div>
                    <div class="panel-body">
                        <input type = 'hidden' name = '_token' value = '{{Session::token()}}'>
                        <div class="form-group">
                            <label for="asset_id" class="col-sm-3 control-label">Asset</label>
                            <div class="col-sm-4">
                                <select name="asset_id" id="asset_id" class="form-control select21" style="width:100%">
                                    <option></option>
                                    @foreach($assets as $asset)
                                        @if($asset_pnl->asset_id == $asset->asset_id)
                                            <option value="{{$asset->asset_id}}" selected="">{{$asset->asset_name}}</option>
                                        @else
                                            <option value="{{$asset->asset_id}}">{{$asset->asset_name}}</option>
                                        @endif    
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="account_id" class="col-sm-3 control-label">Account Name</label>
                            <div class="col-sm-4">
                                <select name="account_id" id="account_id" class="form-control select21" style="width:100%">
                                    <option></option>
                                    @foreach($accounts as $account)
                                        @if($asset_pnl->account_id == $account->account_id)
                                            <option value="{{$account->account_id}}" selected="">{{$account->account_name}}</option>
                                        @else
                                            <option value="{{$account->account_id}}">{{$account->account_name}}</option>
                                        @endif    
                                    @endforeach
                                </select>
                            </div>
                        </div>                        
                        <div class="form-group">
                            <label for="pnl_date" class="col-sm-3 control-label">Date</label>
                            <div class="input-group col-sm-4" style="padding-left: 15px;padding-right: 15px;">
                                    <input id="pnl_date" name="pnl_date" type="text" value="<?php if($asset_pnl->pnl_date != 0) { echo substr_replace(substr_replace($asset_pnl->pnl_date, '-', 4, 0), '-', 7, 0); } else { echo '';}?>" class="form-control pull-left" data-language='en' /> 
                                    <div class="input-group-addon">
                                        <i class="fa fa-fw fa-calendar"></i>
                                    </div> 
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="total_amount" class="col-sm-3 control-label">Amount</label>
                            <div class="col-sm-4">
                                <input id="total_amount" name = "total_amount" type="number" class="form-control" value="{{$asset_pnl->total_amount}}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="trade_fees" class="col-sm-3 control-label">Fees</label>
                            <div class="col-sm-4">
                                <input id="trade_fees" name = "trade_fees" type="number" class="form-control" value="{{$asset_pnl->trade_fees}}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="quantity_closed" class="col-sm-3 control-label">Quantity Closed</label>
                            <div class="col-sm-4">
                                <input id="quantity_closed" name = "quantity_closed" type="number" class="form-control" value="{{$asset_pnl->quantity_closed}}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="quantity_open" class="col-sm-3 control-label">Quantity Open</label>
                            <div class="col-sm-4">
                                <input id="quantity_open" name = "quantity_open" type="number" class="form-control" value="{{$asset_pnl->quantity_open}}">
                            </div>
                        </div> 
                        <div class="form-group">
                            <label for="position_unrealized" class="col-sm-3 control-label">Unrealized</label>
                            <div class="col-sm-4">
                                <input id="position_unrealized" name = "position_unrealized" type="number" class="form-control" value="{{$asset_pnl->position_unrealized}}">
                            </div>
                        </div> 
                        <div class="form-group">
                            <label for="pnl_margin" class="col-sm-3 control-label">Margin</label>
                            <div class="col-sm-4">
                                <input id="pnl_margin" name = "pnl_margin" type="number" class="form-control" value="{{$asset_pnl->pnl_margin}}">
                            </div>
                        </div> 
                        <div class="form-group">
                            <label for="pnl_balance_remaining" class="col-sm-3 control-label">Balance Remaining</label>
                            <div class="col-sm-4">
                                <input id="pnl_balance_remaining" name = "pnl_balance_remaining" type="number" class="form-control" value="{{$asset_pnl->pnl_balance_remaining}}">
                            </div>
                        </div> 
                        <div class="form-group">
                            <label for="trade_long" class="col-sm-3 control-label">Long</label>
                            <div class="col-sm-4">
                                <input id="trade_long" name = "trade_long" type="number" class="form-control" value="{{$asset_pnl->trade_long}}">
                            </div>
                        </div> 
                        <div class="form-group">
                            <label for="trade_short" class="col-sm-3 control-label">Short</label>
                            <div class="col-sm-4">
                                <input id="trade_short" name = "trade_short" type="number" class="form-control" value="{{$asset_pnl->trade_short}}">
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
<script  type="text/javascript" src="{{asset('assets/vendors/datedropper/datedropper.js')}}" ></script>
<script  type="text/javascript" src="{{asset('assets/vendors/timedropper/js/timedropper.js')}}" ></script>
<script type="text/javascript" src="{{asset('assets/vendors/select2/js/select2.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/js/custom_js/AssetPnlCreate.js')}}"></script>
@stop