@extends('layouts/default')

{{-- Page title --}}
@section('title')
    Edit Trade Breach
    @parent
@stop

{{-- page level styles --}}
@section('header_styles')
    <!--page level css -->
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/select2/css/select2.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/select2/css/select2-bootstrap.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/bootstrapvalidator/css/bootstrapValidator.min.css')}}">   
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datedropper/datedropper.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/timedropper/css/timedropper.css')}}"> 
    <link href="{{asset('assets/vendors/airdatepicker/css/datepicker.min.css')}}" rel="stylesheet" type="text/css">
    <!--end of page level css-->
@stop
@section('content')
<section class="content-header">
    <h1>Edit Trade Breach</h1>
    <ol class="breadcrumb">
        <li>
            <a href="index ">
                <i class="fa fa-fw fa-home"></i> Dashboard
            </a>
        </li>
        
        <li class="active">
            Trade Breach
        </li>
    </ol>
</section>
<section class="content">
    <div class="preloader" style="background: none !important; ">
        <div class="loader_img">
            <img src="{{asset('assets/img/loader.gif')}}" alt="loading..." height="64" width="64">
        </div>
    </div>
    <form id="assetDealForm"  action = '{!! url("trade_breach")!!}/{!!$trade_breach->
        breach_id!!}/update' method = 'POST' class="form-horizontal" role="form" enctype="multipart/form-data">
        <div class="row">
            <div class="col-md-12">
                <button type="submit" name="submitBtn" value="Save" class='btn btn-primary btn-inline'>Save</button>
                <button type="submit" name="submitBtn" value="SaveClose" class = 'btn btn-primary btn-inline'>Save  &amp; close</button>
                <a href="{!!url("trade_breach")!!}" class='btn btn-primary btn-inline'>
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
                            <i class="fa fa-fw fa-crosshairs"></i> Edit Trade Breach Detail
                        </h3>
                    </div>
                    <div class="panel-body">
                        <div class="preloader" style="background: none !important; ">
                            <div class="loader_img">
                                <img src="{{asset('assets/img/loader.gif')}}" alt="loading..." height="64" width="64">
                            </div>
                        </div>
                        <input type = 'hidden' name = '_token' value = '{{Session::token()}}'>
                        <input type="hidden" name="requestUrl" id="requestUrl" value="{!!url(Request::segment(1))!!}">
                        
                        <input type="hidden"  id="h_location_id" value="{{$trade_breach->location_id}}">
                        <input type="hidden"  id="h_location_city_id" value="{{$trade_breach->location_city_id}}">
                        <input type="hidden"  id="h_merchant_account_id" value="{{$trade_breach->merchant_account_id}}">
                        <input type="hidden"  id="h_customer_id" value="{{$trade_breach->customer_id}}">
                        <input type="hidden"  id="h_customer_account_id" value="{{$trade_breach->customer_account_id}}">
                        <input type="hidden"  id="h_exchange_id" value="{{$trade_breach->exchange_id}}">
                        <input type="hidden"  id="h_asset_id" value="{{$trade_breach->asset_id}}">
                        <input type="hidden"  id="h_settlement_limit_status" value="{{$trade_breach->settlement_limit_status}}">
                        <input type="hidden"  id="h_trading_limit_status" value="{{$trade_breach->trading_limit_status}}">

                        <?php if(Session('merchantId') == 0): ?>
                            <div class="form-group">
                                <label for="merchant_id" class="col-sm-3 control-label">Merchant</label>
                                <div class="col-sm-4">
                                    <select name="merchant_id" id="merchant_id" class="form-control select21" style="width:100%">
                                        @foreach($merchants as $merchant)
                                            @if($trade_breach->merchant_id == $merchant->merchant_id)
                                                <option value="{{$merchant->merchant_id}}" selected="">{{$merchant->merchant_name}}</option>
                                            @else
                                                <option value="{{$merchant->merchant_id}}">{{$merchant->merchant_name}}</option>
                                            @endif    
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        <?php else: ?>
                            <input type="hidden"  id="merchant_id" name="merchant_id" value="{{Session('merchantId')}}">
                        <?php endif; ?>
                        <div class="form-group">
                            <label for="merchant_account_id" class="col-sm-3 control-label">Merchant Account</label>
                            <div class="col-sm-4">
                                <select name="merchant_account_id" id="merchant_account_id" class="form-control select21" style="width:100%">
                                    @foreach($mercahntAccountLists as $mercahntAccountList)
                                        @if($trade_breach->account_id == $mercahntAccountList->account_id)
                                            <option value="{{$mercahntAccountList->account_id}}" selected="">{{$mercahntAccountList->account_name}}</option>
                                        @else
                                            <option value="{{$mercahntAccountList->account_id}}">{{$mercahntAccountList->account_name}}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="city_id" class="col-sm-3 control-label">City</label>
                            <div class="col-sm-4">
                                <select name="city_id" id="city_id" class="form-control select21" style="width:100%">
                                    @foreach($merchant_cities as $merchant_city)
                                        @if($trade_breach->merchant_id == $merchant_city->city_id)
                                            <option value="{{$merchant_city->city_id}}" selected="">{{$merchant_city->city_name}}</option>
                                        @else
                                            <option value="{{$merchant_city->city_id}}">{{$merchant_city->city_name}}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="location_id" class="col-sm-3 control-label">Location</label>
                            <div class="col-sm-4">
                                <select name="location_id" id="location_id" class="form-control select21" style="width:100%">
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="customer_id" class="col-sm-3 control-label">Customer</label>
                            <div class="col-sm-4">
                                <select name="customer_id" id="customer_id" class="form-control select21" style="width:100%">
                                    @foreach($customerLists as $customerList)
                                        @if($trade_breach->customer_id == $customerList->customer_id)
                                            <option value="{{$customerList->customer_id}}" selected="">{{$customerList->customer_name}}</option>
                                        @else
                                            <option value="{{$customerList->customer_id}}">{{$customerList->customer_name}}</option>>
                                        @endif
                                       
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="customer_account_id" class="col-sm-3 control-label">Customer Account</label>
                            <div class="col-sm-4">
                                <select name="customer_account_id" id="customer_account_id" class="form-control select21" style="width:100%">
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="exchange_id" class="col-sm-3 control-label">Exchange</label>
                            <div class="col-sm-4">
                                <select name="exchange_id" id="exchange_id" class="form-control select21" style="width:100%">
                                    @foreach($exchanges as $exchange)
                                        @if($trade_breach->exchange_id == $exchange->exchange_id)
                                            <option value="{{$exchange->exchange_id}}" selected="">{{$exchange->exchange_name}}</option>
                                        @else
                                            <option value="{{$exchange->exchange_id}}">{{$exchange->exchange_name}}</option>
                                        @endif    
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="asset_id" class="col-sm-3 control-label">Asset</label>
                            <div class="col-sm-4">
                                <select name="asset_id" id="asset_id" class="form-control select21" style="width:100%">
                                    @foreach($assets as $asset)
                                        @if($trade_breach->asset_id == $asset->asset_id)
                                            <option value="{{$asset->asset_id}}" selected="">{{$asset->asset_name}}</option>
                                        @else
                                            <option value="{{$asset->asset_id}}">{{$asset->asset_name}}</option>
                                        @endif    
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="price_average" class="col-sm-3 control-label">Price Average</label>
                            <div class="col-sm-4">
                                <input id="price_average" name = "price_average" type="number" class="form-control" value="{{$trade_breach->price_average}}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="trade_exposure" class="col-sm-3 control-label">Trade Exposure</label>
                            <div class="col-sm-4">
                                <input id="trade_exposure" name = "trade_exposure" type="number" class="form-control" value="{{$trade_breach->trade_exposure}}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="settlement_limit" class="col-sm-3 control-label">Settlement Limit</label>
                            <div class="col-sm-4">
                                <input id="settlement_limit" name = "settlement_limit" type="number" class="form-control" value="{{$trade_breach->settlement_limit}}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="settlement_limit_status" class="col-sm-3 control-label">Settlement Limit Status</label>
                            <div class="col-sm-4">
                                <select name="settlement_limit_status" id="settlement_limit_status" class="form-control select21" style="width:100%">
                                    @foreach($trade_status_types as $trade_status_type)
                                        @if($trade_breach->settlement_limit_status == $trade_status_type->trade_status_id)
                                            <option value="{{$trade_status_type->trade_status_id}}" selected="">{{$trade_status_type->trade_status_name}}</option>
                                        @else
                                            <option value="{{$trade_status_type->trade_status_id}}">{{$trade_status_type->trade_status_name}}</option>
                                        @endif    
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="trading_limit_status" class="col-sm-3 control-label">Trading Limit Status</label>
                            <div class="col-sm-4">
                                <select name="trading_limit_status" id="trading_limit_status" class="form-control select21" style="width:100%">
                                    @foreach($trade_status_types as $trade_status_type)
                                       @if($trade_breach->trading_limit_status == $trade_status_type->trade_status_id)
                                            <option value="{{$trade_status_type->trade_status_id}}" selected="">{{$trade_status_type->trade_status_name}}</option>
                                        @else
                                            <option value="{{$trade_status_type->trade_status_id}}">{{$trade_status_type->trade_status_name}}</option>
                                        @endif 
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="trade_limit" class="col-sm-3 control-label">Trade Limit</label>
                            <div class="col-sm-4">
                                <input id="trade_limit" name = "trade_limit" type="number" class="form-control" value="{{$trade_breach->trading_limit}}">
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
<script type="text/javascript" src="{{asset('assets/js/custom_js/TradeBreachEdit.js')}}"></script>
@stop