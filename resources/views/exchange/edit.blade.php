@extends('layouts/default')

{{-- Page title --}}
@section('title')
    Exchange
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
    <h1>Edit Exchange</h1>
    <ol class="breadcrumb">
        <li>
            <a href="index ">
                <i class="fa fa-fw fa-home"></i> Dashboard
            </a>
        </li>
        
        <li class="active">
            Exchange
        </li>
    </ol>
</section>
<section class="content">
    <form id="assetForm"  action = '{!! url("exchange")!!}/{!!$exchange->
        exchange_id!!}/update' method = 'POST' class="form-horizontal" role="form" enctype="multipart/form-data">
        <div class="row">
            <div class="col-md-12">
                <button type="submit" name="submitBtn" value="Save" class='btn btn-primary btn-inline'>Save</button>
                <button type="submit" name="submitBtn" value="SaveClose" class = 'btn btn-primary btn-inline'>Save  &amp; close</button>
                <a href="{!!url("exchange")!!}" class='btn btn-primary btn-inline'>
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
                            <i class="fa fa-fw fa-crosshairs"></i> Edit Exchange Detail
                        </h3>
                    </div>
                    <div class="panel-body">
                        <input type = 'hidden' name = '_token' value = '{{Session::token()}}'>
                        <input type = 'hidden' name = 'exchange_id' value = '{!!$exchange->exchange_id!!}'>
                        <div class="form-group">
                            <label for="exchange_name" class="col-sm-3">Exchange Name</label>
                            <div class="col-sm-8">
                                <input id="exchange_name" name="exchange_name" type="text" class="form-control" value='{!!$exchange->exchange_name!!}'>
                            </div>
                        </div>  
                        <div class="form-group">
                            <label for="exchange_code" class="col-sm-3">Exchange Code</label>
                            <div class="col-sm-8">
                                <input id="exchange_code" name="exchange_code" type="text" class="form-control" value='{!!$exchange->exchange_code!!}'>
                            </div>
                        </div>  
                        <div class="form-group">
                            <label for="exchange_website" class="col-sm-3">Exchange Website</label>
                            <div class="col-sm-8">
                                <input id="exchange_website" name="exchange_website" type="text" class="form-control" value='{!!$exchange->exchange_website!!}'>
                            </div>
                        </div>                    
                        <div class="form-group">
                            <label for="trading_fees_url" class="col-sm-3">Trading Fees Url</label>
                            <div class="col-sm-8">
                                <input id="trading_fees_url" name="trading_fees_url" type="text" class="form-control" value='{!!$exchange->trading_fees_url!!}'>
                            </div>
                        </div> 
                        <div class="form-group">
                            <label for="trading_api_url" class="col-sm-3">Trading Api Url</label>
                            <div class="col-sm-8">
                                <input id="trading_api_url" name="trading_api_url" type="text" class="form-control" value='{!!$exchange->trading_api_url!!}'>
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
<script type="text/javascript" src="{{asset('assets/js/custom_js/AssetCreate.js')}}"></script>
@stop