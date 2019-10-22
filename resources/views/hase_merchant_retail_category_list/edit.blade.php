@extends('layouts/default')

{{-- Page title --}}
@section('title')
    Categories
    @parent
@stop

{{-- page level styles --}}
@section('header_styles')

    <!--page level css -->
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/bootstrapvalidator/css/bootstrapValidator.min.css')}}">

    <link href="{{asset('assets/vendors/select2/css/select2.min.css')}}" rel="stylesheet" type="text/css">

    <link href="{{asset('assets/vendors/select2/css/select2-bootstrap.css')}}" rel="stylesheet" type="text/css">

    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/jasny-bootstrap/css/jasny-bootstrap.css')}}">

    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/bootstrap-switch/css/bootstrap-switch.css')}}"/>
    <!--end of page level css-->

    <style type="text/css">
        #panelList .row{
            margin-top: 10px;
        }
    </style>
@stop
@section('content')

<section class="content-header">
    <h1>Edit Categories</h1>
    <ol class="breadcrumb">
        <li>
            <a href="index ">
                <i class="fa fa-fw fa-home"></i> Dashboard
            </a>
        </li>
        <li>
            <a href="#"> Merchants </a>
        </li>
        <li class="active">
            Categories
        </li>
    </ol>
</section>
<section class="content">
    <div class="preloader" style="background: none !important; ">
        <div class="loader_img">
            <img src="{{asset('assets/img/loader.gif')}}" alt="loading..." height="64" width="64">
        </div>
    </div>
    <form id="CategoriesForm"  action = '{!!url("hase_category_list")!!}/{!!$hase_merchant_retail_category_list->category_list_id!!}/update' method = 'POST' class="form-horizontal" role="form" enctype="multipart/form-data">

        <div class="row">
            <div class="col-md-12">
                <!-- <button type="submit" name="submitBtn" value="Save" class='btn btn-primary btn-inline'>Save</button> -->
                <button type="submit" name="submitBtn" value="SaveClose" class = 'btn btn-primary btn-inline'>Save  &amp; close</button>

                <a id="actionUrl" href="{!!url('hase_category_list')!!}" class='btn btn-primary btn-inline'>
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
                            <i class="fa fa-fw fa-crosshairs"></i> Edit Categories
                        </h3>
                    </div>
                    <div class="panel-body">
                        
                        <input type = 'hidden' name = '_token' value = '{{Session::token()}}'>
                        <input type="hidden" id="merchant_id" name="merchant_id" value="{!!$merchant_data->merchant_id!!}" >
                        <input type="hidden" id="location_id" name="location_id" value="{!!$location_data->location_id!!}" >
                        
                        <?php if(Session('merchantId') == 0): ?>
                            <div class="form-group">
                                <label class="col-sm-3">Merchant</label>
                                <div class="col-sm-8">
                                    {!!$merchant_data->merchant_name!!}
                                </div>
                            </div>
                        <?php endif; ?>
                        <div class="form-group">
                            <label for="location_id" class="col-sm-3">Location</label>
                            <div class="col-sm-8"> 
                                {!!$location_data->location_name!!}
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="category_type_id" class="col-sm-3 ">
                                Retail Category Type
                            </label>
                            <div class="col-sm-8">
                                <select id="category_type_id" class="form-control select2" multiple style="width:100%">
                                    @foreach($categoryTypes as $categoryType)
                                        <option value="{{$categoryType->category_type_id}}">{{$categoryType->category_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        
                        <!-- <div class="form-group">
                            <label for="Priority" class="col-sm-3">Priority</label>
                            <div class="col-sm-8">
                                <input value="{!!$hase_merchant_retail_category_list->
                            category_priority!!}" id="category_priority" name="category_priority" min="0" type="number" class="form-control">
                            </div>
                        </div> -->
                        
                        <!-- <div class="form-group">
                            <label for="Status" class="col-sm-3">Status</label>
                            <div class="col-sm-8">
                                <div class="make-switch" data-on="danger" data-off="default">
                                    @if($hase_merchant_retail_category_list->category_status)
                                        <input value="{!!$hase_merchant_retail_category_list->
                                        category_status!!}" id="category_status" name="category_status" data-on-text="Enabled" data-on-color="success" data-off-color="danger" data-off-text="Disabled" type="checkbox" checked="true" />
                                    @else
                                        <input value="{!!$hase_merchant_retail_category_list->
                                        category_status!!}" id="category_status" name="category_status" data-on-text="Enabled" data-on-color="success" data-off-color="danger" data-off-text="Disabled" type="checkbox" />
                                    @endif
                                </div>
                            </div>
                        </div> -->
                        <div class="row">
                            <div class="col-md-12">
                                <div class="panel panel-primary">
                                    <div class="panel-heading">
                                        <h3 class="panel-title">
                                            Select Category Options
                                        </h3>
                                    </div>
                                    <div class="panel-body" id="panelList">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="row">
                                                    <label class="col-md-2">Category Type</label>
                                                    <label class="col-md-5">Category Option</label>
                                                    <label class="col-md-2">Priority</label>
                                                    <label class="col-md-3">Status</label>
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
    <div class="row" id="demo" style="display: none;">
        <div class="col-md-2">
            <label id="category_type_name"></label>
            <input type="hidden" name="category_type_id" value="" id="categoryTypeId">
        </div>
        <div class="col-sm-5">
            <select name="category_option_type_id" id="category_option_type_id" class="form-control" multiple="">
                @foreach($hase_options as $hase_option)
                    <option value="{{$hase_option->category_option_type_id}}">{{$hase_option->option_name}}</option>
                @endforeach
            </select>
        </div>
        <div class="col-sm-2">
            <input id="priority" name="priority" type="number" class="form-control" min="0" value="0" required="">
        </div>
        <div class="col-sm-3">
            <input name="enable" type="checkbox" data-on-text="On" data-on-color="success" data-off-color="danger" data-off-text="Off" id="enable" checked="true" />
        </div>
    </div>
</section>
@endsection
{{-- page level scripts --}}
@section('footer_scripts')
<!-- begining of page level js -->

<script type="text/javascript" src="{{asset('assets/vendors/select2/js/select2.js')}}"></script>

<script type="text/javascript" src="{{asset('assets/vendors/jasny-bootstrap/js/jasny-bootstrap.js')}}"></script>

<script type="text/javascript" src="{{asset('assets/vendors/bootstrap-switch/js/bootstrap-switch.js')}}"></script>

<script type="text/javascript" src="{{asset('assets/vendors/bootstrapvalidator/js/bootstrapValidator.min.js')}}" ></script>

<script type="text/javascript" src="{{asset('assets/js/custom_js/HaseCategoryListEdit.js')}}"></script>
@stop