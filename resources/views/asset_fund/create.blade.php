@extends('layouts/default')

{{-- Page title --}}
@section('title')
    Create Asset Fund
    @parent
@stop

{{-- page level styles --}}
@section('header_styles')
    <!--page level css -->
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/jasny-bootstrap/css/jasny-bootstrap.css')}}" />
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/select2/css/select2.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/select2/css/select2-bootstrap.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/bootstrapvalidator/css/bootstrapValidator.min.css')}}">   
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datedropper/datedropper.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/timedropper/css/timedropper.css')}}">
    <link href="{{asset('assets/vendors/airdatepicker/css/datepicker.min.css')}}" rel="stylesheet" type="text/css">
    <!--<link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/bootstrap-switch/css/bootstrap-switch.css')}}"/>-->
    <!--end of page level css-->
@stop
@section('content')
<section class="content-header">
    <h1>Create New Asset Fund</h1>
    <ol class="breadcrumb">
        <li>
            <a href="index ">
                <i class="fa fa-fw fa-home"></i> Dashboard
            </a>
        </li>
        
        <li class="active">
            Asset Fund
        </li>
    </ol>
</section>
<section class="content">
    <form id="assetFundForm" action='{!!url("asset_fund")!!}' method='POST' class="form-horizontal" role="form" enctype="multipart/form-data">
        <div class="row">
            <div class="col-md-12">
                <!--<button type="submit" name="submitBtn" value="Save" class='btn btn-primary btn-inline'>Save</button>-->
                <button type="submit" name="submitBtn" value="SaveClose" class = 'btn btn-primary btn-inline'>Save  &amp; close</button>
                <a href="{!!url("asset_fund")!!}" class='btn btn-primary btn-inline'>
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
                            <i class="fa fa-fw fa-crosshairs"></i> Add Asset Fund Detail
                        </h3>
                    </div>
                    <div class="panel-body">
                        <input type='hidden' name='_token' value='{{Session::token()}}'>
                        <input type="hidden" name="requestUrl" id="requestUrl" value="{!!url(Request::segment(1))!!}">
                        <?php if(Session('merchantId') == 0 || Session('role') != 6): ?>
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
                            <label for="merchant_account_id" class="col-sm-3 control-label">Merchant Account Name</label>
                            <div class="col-sm-4">
                                <select name="merchant_account_id" id="merchant_account_id" class="form-control select21" style="width:100%">
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="customer_id" class="col-sm-3 control-label">Customer Name</label>
                            <div class="col-sm-4">
                                <select name="customer_id" id="customer_id" class="form-control select21" style="width:100%">
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="customer_account_id" class="col-sm-3 control-label">Customer Account Name</label>
                            <div class="col-sm-4">
                                <select name="customer_account_id" id="customer_account_id" class="form-control select21" style="width:100%">
                                </select>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="asset_id" class="col-sm-3 control-label">Asset Name</label>
                            <div class="col-sm-4">
                                <select name="asset_id" id="asset_id" class="form-control select21" style="width:100%">
                                    <option></option>
                                    @foreach($assets as $asset)
                                       <option value="{{$asset->asset_id}}">{{$asset->asset_code}} ({{$asset->asset_name}})</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <input type="hidden" name="asset_id_hidden" id="asset_id_hidden" value="">
                        <input id="asset_price" name="asset_price" type="hidden" value="1.0" class="form-control">
                        <div class="form-group">
                            <label for="asset_quantity" class="col-sm-3 control-label">Asset Amount</label>
                            <div class="col-sm-4">
                                <input id="asset_quantity" name="asset_quantity" type="number" class="form-control" step="0.00000001">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="fund_title" class="col-sm-3 control-label">Fund Title</label>
                            <div class="col-sm-4">
                                <input id="fund_title" name="fund_title" type="text" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="fund_description" class="col-sm-3 control-label">Fund Description</label>
                            <div class="col-sm-4">
                                <textarea id="fund_description" name="fund_description" class="form-control"></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="fund_type" class="col-sm-3 control-label">Fund Transfer Type</label>
                            <div class="col-sm-4">
                                <select name="fund_type" id="fund_type" class="form-control select21" style="width:100%">
                                    <option></option>
                                    @foreach($fundTypes as $fundType)
                                       <option value="{{$fundType->type_id}}">{{$fundType->type_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="action" class="col-sm-3 control-label">Action</label>
                            <div class="col-sm-4">
                                <select name="action" id="action" class="form-control select21" style="width:100%">
                                    <option value="deposit">Deposit</option>
                                    <option value="withdrawal">Withdrawal</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group" style="display: none">
                            <label for="fund_date" class="col-sm-3 control-label">Fund Date</label>
                            <div class="input-group col-sm-4" style="padding-left: 15px;padding-right: 15px;">
                                    <input id="fund_date" name="fund_date" type="text" value="<?= date('Y-m-d') ?>" class="form-control pull-left" data-language='en' /> 
                                    <div class="input-group-addon">
                                        <i class="fa fa-fw fa-calendar"></i>
                                    </div> 
                            </div>
                        </div>
                        <div class="form-group" style="display: none">
                            <label for="fund_time" class="col-sm-3 control-label">Fund Time</label>
                            <div class="input-group col-sm-4" style="padding-left: 15px;padding-right: 15px;">
                                <input id="fund_time" name="fund_time" type="text" value="20:00" class="form-control required">
                                <span class="input-group-addon"><i class="fa fa-clock-o"></i></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="pic" class="col-sm-3 control-label">Fund Image<span class="help-block">Select a file to update asset fund image, otherwise leave blank.</span></label>
                            <div class="col-sm-5">
                                <div class="fileinput fileinput-new" data-provides="fileinput">
                                    <div class="fileinput-new thumbnail" style="width: 200px; height: 200px;">
                                        <img src="{{asset(env('image_dir_path').'no_photo.png')}}" alt="profile pic" class="profile_pic">
                                    </div>
                                    <div class="fileinput-preview fileinput-exists thumbnail"
                                         style="max-width: 200px; max-height: 200px;"></div>
                                    <div class="fileinput-filename" style="display: block !important;">
                                    </div>
                                    <div>
                                        <span class="btn btn-default btn-file">
                                        <span class="fileinput-new">Select image</span>
                                        <span class="fileinput-exists">Change</span>
                                        <input id="image_href" name="image_href" type="file" class="form-control"/>
                                        </span>
                                        <a href="#" class="btn btn-danger fileinput-exists"
                                           data-dismiss="fileinput">Remove</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="image_title" class="col-sm-3 control-label">Image Title</label>
                            <div class="col-sm-4">
                                <input id="image_title" name="image_title" type="text" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="image_description" class="col-sm-3 control-label">Image Description</label>
                            <div class="col-sm-4">
                                <textarea id="image_description" name="image_description" class="form-control"></textarea>
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
<script type="text/javascript" src="{{asset('assets/vendors/jasny-bootstrap/js/jasny-bootstrap.js')}}" ></script>
<script type="text/javascript" src="{{asset('assets/vendors/select2/js/select2.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/bootstrapvalidator/js/bootstrapValidator.min.js')}}" ></script>
<script src="{{asset('assets/vendors/airdatepicker/js/datepicker.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/vendors/airdatepicker/js/datepicker.en.js')}}" type="text/javascript"></script>
<script  type="text/javascript" src="{{asset('assets/vendors/datedropper/datedropper.js')}}" ></script>
<script  type="text/javascript" src="{{asset('assets/vendors/timedropper/js/timedropper.js')}}" ></script>
<script type="text/javascript" src="{{asset('assets/vendors/select2/js/select2.js')}}"></script>
<!--<script type="text/javascript" src="{{asset('assets/vendors/bootstrap-switch/js/bootstrap-switch.js')}}"></script>-->
<script type="text/javascript" src="{{asset('assets/js/custom_js/AssetFundCreate.js')}}"></script>
@stop
