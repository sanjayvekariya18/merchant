@extends('layouts/default')

{{-- Page title --}}
@section('title')
    Create {!! $labels[2] !!}
    @parent
@stop

{{-- page level styles --}}
@section('header_styles')
    <!--page level css -->
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/bootstrap-switch/css/bootstrap-switch.css')}}"/>

    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/jasny-bootstrap/css/jasny-bootstrap.css')}}">

    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/select2/css/select2.min.css')}}">

    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/select2/css/select2-bootstrap.css')}}">

    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/bootstrapvalidator/css/bootstrapValidator.min.css')}}">
    
    <link href="{{asset('assets/vendors/airdatepicker/css/datepicker.min.css')}}" rel="stylesheet" type="text/css">

    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/iCheck/css/all.css')}}"/>

    <style type="text/css">
        #panelList .row{
            margin-bottom: 5px
        }
    </style>
    <!--end of page level css-->
@stop
@section('content')
<?php use Illuminate\Support\Facades\Request; ?>
<section class="content-header">
    <h1>Create {!! $labels[2] !!}</h1>
    <ol class="breadcrumb">
        <li>
            <a href="index ">
                <i class="fa fa-fw fa-home"></i> Dashboard
            </a>
        </li>
        <li>
            <a href="#"> {!! $labels[1] !!}</a>
        </li>
        <li class="active">
            Create {!! $labels[2] !!}
        </li>
    </ol>
</section>
<br>
<section class="content">
    <div class="preloader" style="background: none !important; ">
        <div class="loader_img">
            <img src="{{asset('assets/img/loader.gif')}}" alt="loading..." height="64" width="64">
        </div>
    </div>
    <form method = 'POST' enctype="multipart/form-data" class="form-horizontal bv-form" action='{!!url(Request::segment(1))!!}' id="create_menu_form">

        <input type="hidden" name="requestUrl" id="requestUrl" value="{!!url(Request::segment(1))!!}">

        <div class="row">
            <div class="col-md-12">
                <button type="submit" name="submitBtn" value="Save" class='btn btn-primary btn-inline'>Save</button>
                <button type="submit" name="submitBtn" value="SaveClose" class = 'btn btn-primary btn-inline'>Save &amp; close</button>
                <a href="{!!url(Request::segment(1))!!}" class='btn btn-primary btn-inline'>
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
                            <i class="fa fa-fw fa-crosshairs"></i> {!! $labels[2] !!}
                        </h3>
                    </div>
                    <div class="panel-body">
                        <input type = 'hidden' name = '_token' value = '{{Session::token()}}'>
                        <div class="bs-example">
                            <ul class="nav nav-tabs" style="margin-bottom: 15px;">
                                <li class="active">
                                    <a href="#menuTab" data-toggle="tab">{!! $labels[2] !!}</a>
                                </li>
                                <li>
                                    <a href="#cuisineTab" data-toggle="tab">{!! $styleLabels[1] !!} Tagging</a>
                                </li>
                                <li>
                                    <a href="#categoryTab" data-toggle="tab">Category Tagging</a>
                                </li>
                            </ul>
                            <div id="myTabContent" class="tab-content">

                                <div class="tab-pane fade active in" id="menuTab">
                                
                                    <?php if(Session('merchantId') == 0): ?>
                                        <div class="form-group">
                                            <label for="merchant_id" class="col-sm-3 control-label">Merchant</label>
                                            <div class="col-sm-5"> 
                                                <select name="merchant_id" id="merchant_id" class = "form-control select21">
                                                    <option></option>
                                                    @foreach($hase_merchants as $hase_merchant)
                                                        <?php 
                                                            $listLimit = ($merchantType == 8)?3:8;
                                                         ?>
                                                        <option data-limit="<?php echo $listLimit ?>" value="{{$hase_merchant->merchant_id}}">{{$hase_merchant->merchant_name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    <?php else: ?>
                                         <input type="hidden" name="merchant_id" id="merchant_id" value="{{$merchantId}}">
                                    <?php endif; ?>

                                    <div id="cityBlock" class="form-group">
                                        <label for="Parent" class="col-sm-3 control-label">
                                            City
                                        </label>
                                        <div class="col-sm-5">
                                            <select name="city_id" id="city_id" class="form-control select2" style="width:100%">
                                                <option></option>
                                            </select>
                                        </div>
                                    </div>
                                    <div id="locationBlock" class="form-group">
                                        <label for="Parent" class="col-sm-3 control-label">
                                            Location
                                        </label>
                                        <div class="col-sm-5">
                                            <select name="location_id" id="location_id" class="form-control select2" style="width:100%">
                                                <option></option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="product_name" class="col-sm-3 control-label">{!! $labels[2] !!} Name</label>
                                        <div class="col-sm-5">
                                            <input id="product_name" name = "product_name" type="text" class="form-control"> 
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="product_description" class="col-sm-3 control-label">{!! $labels[2] !!} Description*</label>
                                        <div class="col-sm-5 col-md-5">
                                            <textarea id="product_description" name = "product_description" rows="4" class="form-control resize_vertical"
                                              placeholder="Description"></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="base_price" class="col-sm-3 control-label">Original Price</label>
                                        <div class="col-sm-5">
                                            <div class="input-group">
                                                <input id="base_price" name = "base_price" type="text" class="form-control" value=""> 
                                            <span class="input-group-addon"><i class="fa fa-money"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="product_category_id" class="col-sm-3 control-label">Category</label>
                                        <div class="col-sm-5"> 
                                            <select name="product_category_id" id="product_category_id" class = "form-control select21">
                                            <option></option>
                                                @foreach($hase_categories as $hase_category)
                                                <option value="{{$hase_category->category_type_id}}">{{$hase_category->category_name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="pic" class="col-sm-3 control-label">Image<span class="help-block">Select a file to update {!! strtolower($labels[2]) !!} image, otherwise leave blank.</span></label>
                                        <div class="col-sm-5">
                                            <div class="fileinput fileinput-new" data-provides="fileinput">
                                                <div class="fileinput-new thumbnail"
                                                     style="width: 200px; height: 200px;">
                                                    <img src="{{asset(env('image_dir_path').'no_photo.png')}}" alt="profile pic" class="profile_pic">
                                                </div>
                                                <div class="fileinput-preview fileinput-exists thumbnail"
                                                     style="max-width: 200px; max-height: 200px;"></div>
                                                 <div class="fileinput-filename" style="display: block !important;"></div>
                                                <div>
                                                        <span class="btn btn-default btn-file">
                                                        <span class="fileinput-new">Select image</span>
                                                        <span class="fileinput-exists">Change</span>
                                                        <input id="product_image" name="product_image" type="file" class="form-control"/>
                                                        </span>
                                                    <a href="#" class="btn btn-danger fileinput-exists"
                                                       data-dismiss="fileinput">Remove</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="image Url" class="col-sm-3 control-label">Image Url</label>
                                        <div class="col-sm-8">
                                            <input id="live_image_url" name = "live_image_url" type="text" class="form-control"> 
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="pic" class="col-sm-3 control-label">Image Compact<span class="help-block">Select a file to update {!! strtolower($labels[2]) !!} compact image, otherwise leave blank.</span></label>
                                        <div class="col-sm-5">
                                            <div class="fileinput fileinput-new" data-provides="fileinput">
                                                <div class="fileinput-new thumbnail"
                                                     style="width: 200px; height: 200px;">
                                                    <img src="{{asset(env('image_dir_path').'no_photo.png')}}" alt="profile pic" class="profile_pic">
                                                </div>
                                                <div class="fileinput-preview fileinput-exists thumbnail"
                                                     style="max-width: 200px; max-height: 200px;">
                                                </div>
                                                <div class="fileinput-filename" style="display: block !important;"></div>
                                                <div>
                                                        <span class="btn btn-default btn-file">
                                                        <span class="fileinput-new">Select image</span>
                                                        <span class="fileinput-exists">Change</span>
                                                        <input id="product_image_compact" name="product_image_compact" type="file" class="form-control"/>
                                                        </span>
                                                    <a href="#" class="btn btn-danger fileinput-exists"
                                                       data-dismiss="fileinput">Remove</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="image Url" class="col-sm-3 control-label">Image Compact Url</label>
                                        <div class="col-sm-8">
                                            <input id="live_image_compact_url" name = "live_image_compact_url" type="text" class="form-control"> 
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="special_status" class="col-sm-3 control-label">Special</label>
                                        <div class="col-sm-5">
                                            <input class="switch form-control" data-on-color="success" data-off-color="danger" data-on-text="Enabled" data-off-text="Disabled" id="special_status" name="special_status" type="checkbox" value="0" />
                                        </div>
                                    </div>
                                    <div class="specialMenuEnable">
                                        <div class="form-group">
                                            <label for="special_price" class="col-sm-3 control-label">Discount Price</label>
                                            <div class="col-sm-5">
                                                <div class="input-group">
                                                    <input id="special_price" name = "special_price" type="text" class="form-control">
                                                    <span class="input-group-addon"><i class="fa fa-money"></i></span>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="pic" class="col-sm-3 control-label">Special Image<span class="help-block">Select a file to update {!! strtolower($labels[2]) !!} image, otherwise leave blank.</span></label>
                                            <div class="col-sm-5">
                                                <div class="fileinput fileinput-new" data-provides="fileinput">
                                                    <div class="fileinput-new thumbnail"
                                                         style="width: 200px; height: 200px;">
                                                        <img src="{{asset(env('image_dir_path').'no_photo.png')}}" alt="profile pic"
                                                        class="profile_pic">
                                                    </div>
                                                    <div class="fileinput-preview fileinput-exists thumbnail"
                                                         style="max-width: 200px; max-height: 200px;"></div>
                                                    <div class="fileinput-filename" style="display: block !important;"></div>
                                                    <div>
                                                            <span class="btn btn-default btn-file">
                                                            <span class="fileinput-new">Select image</span>
                                                            <span class="fileinput-exists">Change</span>
                                                            <input id="special_image" name="special_image" type="file" class="form-control"/>
                                                            </span>
                                                        <a href="#" class="btn btn-danger fileinput-exists"
                                                           data-dismiss="fileinput">Remove</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="special image Url" class="col-sm-3 control-label">Special Image Url</label>
                                            <div class="col-sm-8">
                                                <input id="live_special_image_url" name = "live_special_image_url" type="text" class="form-control"> 
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="Special Compact" class="col-sm-3 control-label">Special Image Compact<span class="help-block">Select a file to update {!! strtolower($labels[2]) !!} compact image, otherwise leave blank.</span></label>
                                            <div class="col-sm-5">
                                                <div class="fileinput fileinput-new" data-provides="fileinput">
                                                    <div class="fileinput-new thumbnail"
                                                         style="width: 200px; height: 200px;">
                                                        <img src="{{asset(env('image_dir_path').'no_photo.png')}}" alt="profile pic"
                                                        class="profile_pic">
                                                    </div>
                                                    <div class="fileinput-preview fileinput-exists thumbnail"
                                                         style="max-width: 200px; max-height: 200px;"></div>
                                                    <div class="fileinput-filename" style="display: block !important;"></div>
                                                    <div>
                                                            <span class="btn btn-default btn-file">
                                                            <span class="fileinput-new">Select image</span>
                                                            <span class="fileinput-exists">Change</span>
                                                            <input id="special_image_compact" name="special_image_compact" type="file" class="form-control"/>
                                                            </span>
                                                        <a href="#" class="btn btn-danger fileinput-exists"
                                                           data-dismiss="fileinput">Remove</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="special image Url" class="col-sm-3 control-label">Special Image compact Url</label>
                                            <div class="col-sm-8">
                                                <input id="live_special_image_compact_url" name = "live_special_image_compact_url" type="text" class="form-control"> 
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="special_details" class="col-sm-3 control-label">Offer Details</label>
                                            <div class="input-group col-sm-5">
                                                <input id="special_details" name = "special_details" type="text" class="form-control">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="special_terms" class="col-sm-3 control-label">Offer Terms and Conditions</label>
                                            <div class="input-group col-sm-5">
                                                <input id="special_terms" name = "special_terms" type="text" class="form-control">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="special_url" class="col-sm-3 control-label">Offer URL</label>
                                            <div class="input-group col-sm-5">
                                                <input id="special_url" name = "special_url" type="text" class="form-control">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="start_date" class="col-sm-3 control-label">Offer Start Date</label>
                                            <div class="input-group col-sm-5">
                                                <input id="start_date" name="start_date" type="text" class="form-control pull-left" data-language='en' /> 
                                                <div class="input-group-addon">
                                                    <i class="fa fa-fw fa-calendar"></i>
                                                </div> 
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="end_date" class="col-sm-3 control-label">Offer End Date</label>
                                            <div class="input-group col-sm-5">
                                                <input id="end_date" name="end_date" type="text" class="form-control pull-left" data-language='en' /> 
                                                <div class="input-group-addon">
                                                    <i class="fa fa-fw fa-calendar"></i>
                                                </div> 
                                            </div>
                                        </div>
                                    </div>        
                                </div>
                                <div class="tab-pane fade" id="cuisineTab">
                                    <div class="form-group">
                                        <label for="Parent" class="col-sm-2 ">
                                            {!! $styleLabels[1] !!} Type
                                        </label>
                                        <div class="col-sm-6">
                                            <?php 
                                                $listLimit = ($merchantType == 8)?3:8;
                                             ?>
                                            <select name="style_type_id[]" data-limit="<?php echo $listLimit ?>" id="style_type_id" class="form-control select2" multiple style="width: 100%">
                                                @if(session('merchantId') != 0 )
                                                    @foreach($styleTypes as $styleType)
                                                        <option value="{{$styleType->style_type_id}}">{{$styleType->style_name}}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="panel panel-primary">
                                                <div class="panel-heading">
                                                    <h3 class="panel-title">
                                                        Set Priorities
                                                    </h3>
                                                </div>
                                                <div class="panel-body">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <table class="table table-striped table-border table-sortable" id="table">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Style Name</th>
                                                                        <th>Priority</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody></tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="tab-pane fade" id="categoryTab">
                                    <div class="form-group">
                                        <label for="Parent" class="col-sm-3 ">
                                            Retail Category Type
                                        </label>
                                        <div class="col-sm-8">
                                            <select id="category_type_id" class="form-control select2" multiple style="width:100%">
                                                @if(session('merchantId') != 0 )
                                                    @foreach($categoryTypes as $categoryType)
                                                        <option value="{{$categoryType->category_type_id}}">{{$categoryType->category_name}}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div>

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
                </div>
            </div>
        </div>    
    </form>
    <div class="row" id="categryDemo" style="display: none;">
        <div class="col-md-2">
            <label id="category_type_name"></label>
            <input type="hidden" name="category_type_id" value="" id="categoryTypeId">
        </div>
        <div class="col-sm-5">
            <select name="category_option_type_id" id="category_option_type_id" class="form-control" multiple="" style="width: 100%">
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

    <div class="row" id="demo" style="display: none;">
        <table>
            <tr id="table-row" >
                <input type="hidden" id="style_id" name="style_id" />
                <td>        
                    <span id="style_name" name="style_name" class="form-control"></span>
                </td>   
                <td class="form-group">
                    <input type="text" id="priority" name="priority" class="    form-control stylePriorityRequired" placeholder="The style priority is required and must contain only numbers.">
                </td>
            </tr>
        </table>
    </div>
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

<script type="text/javascript" src="{{asset('assets/vendors/iCheck/js/icheck.js')}}"></script>

<script type="text/javascript" src="{{asset('assets/vendors/bootstrap-switch/js/bootstrap-switch.js')}}"></script>

<script type="text/javascript" src="{{asset('assets/js/custom_js/haseMenuCreate.js')}}"></script>

<!-- <script type="text/javascript" src="{{asset('assets/js/custom_js/HaseStylesCreate.js')}}"></script> -->

<!-- end of page level js -->
@stop