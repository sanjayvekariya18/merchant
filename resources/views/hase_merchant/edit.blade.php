@extends('layouts/default')

{{-- Page title --}}
@section('title')
    Merchants
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
@stop
@section('content')
<section class="content-header">
    <h1>Edit Merchant</h1>
    <ol class="breadcrumb">
        <li>
            <a href="index ">
                <i class="fa fa-fw fa-home"></i> Dashboard
            </a>
        </li>
        <li>
            <a href="#"> Merchants</a>
        </li>
        <li class="active">
            Merchant
        </li>
    </ol>
</section>
<section class="content">
    <form id="merchantForm"  action = '{!! url("hase_merchant")!!}/{!!$hase_merchant->
        merchant_id!!}/update' method = 'POST' class="form-horizontal" role="form" enctype="multipart/form-data">
        <div class="row">
            <div class="col-md-12">
                <button type="submit" name="submitBtn" value="Save" class='btn btn-primary btn-inline'>Save</button>
                <button type="submit" name="submitBtn" value="SaveClose" class = 'btn btn-primary btn-inline'>Save  &amp; close</button>
                <a href="{!!url("hase_merchant")!!}" class='btn btn-primary btn-inline'>
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
                            <i class="fa fa-fw fa-crosshairs"></i> Merchant Form
                        </h3>
                    </div>
                    <div class="panel-body">
                        <input type = 'hidden' name = '_token' value = '{{Session::token()}}'>
                        <input type = 'hidden' name = 'merchant_id' value = '{!!$hase_merchant->merchant_id!!}'>
                        <?php if(session('merchantId') == 0): ?>
                            <div class="form-group">
                                <label for="merchant_type_id" class="col-sm-3 control-label">
                                    Merchant Type
                                </label>
                                <div class="col-sm-8">
                                    <select name="merchant_type_id" id="merchant_type_id" class="form-control select2" style="width:100%">

                                        @foreach($merchant_parent_types as $merchant_parent_type)
                                            @if($merchant_parent_type->merchant_type_id == $hase_merchant->merchant_type_id)
                                                <option value="{{$merchant_parent_type->merchant_type_id}}" selected>{{$merchant_parent_type->merchant_type_name}}</option>
                                            @else
                                                <option value="{{$merchant_parent_type->merchant_type_id}}">{{$merchant_parent_type->merchant_type_name}}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        <?php endif; ?>
                        
                        <div class="form-group">
                            <label for="Name" class="col-sm-3 control-label">Merchant Name</label>
                            <div class="col-sm-8">
                                <input value="{!!$hase_merchant->merchant_name!!}" id="merchant_name" name="merchant_name" type="text" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="merchant_code" class="col-sm-3 control-label">Merchant Code*</label>
                            <div class="col-sm-8">
                                <input id="merchant_code" name = "merchant_code" type="text" class="form-control" value="{!!$hase_merchant->merchant_code!!}">
                            </div>     
                        </div>
                        <div class="form-group">
                            <label for="Email" class="col-sm-3 control-label">Email</label>
                            <div class="col-sm-8">
                                <input value="{!!$hase_merchant->merchant_email!!}" id="merchant_email" name="merchant_email" type="text" class="form-control">
                            </div>
                        </div>
                        <div class="form-group" style="display: none">
                            <label for="Telephone" class="col-sm-3 control-label">Telephone</label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <span class="input-group-addon">+852</span>
                                    <input value="{!!$hase_merchant->merchant_telephone!!}" id="merchant_telephone" name="merchant_telephone" type="number" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="Website" class="col-sm-3 control-label">Website</label>
                            <div class="col-sm-8">
                                <input value="{!!$hase_merchant->merchant_website!!}" id="merchant_website" name="merchant_website" type="text" class="form-control">
                            </div>
                        </div>
                        <?php
                        $merchantLogoImageUrl = parse_url($hase_merchant->merchant_logo);
                        ?>
                        @if(isset($merchantLogoImageUrl['scheme']))
                            @if($merchantLogoImageUrl['scheme'] == 'https' || $merchantLogoImageUrl['scheme'] == 'http')
                                <div class="form-group">
                                    <label for="MerchantLogo" class="col-sm-3 control-label">
                                        Merchant Logo
                                        <span class="help-block">
                                            Select a file to update Merchant Logo, otherwise leave blank.
                                        </span>
                                    </label>
                                    <div class="col-sm-9">
                                        <div class="fileinput fileinput-<?php echo isset($hase_merchant->merchant_logo)?'exists':'new' ?>" data-provides="fileinput">
                                            <div class="fileinput-new thumbnail" style="width: 200px; height: 200px;">
                                                <img src="{{asset(env('image_dir_path').'no_photo.png')}}" alt="merchant logo ">
                                            </div>
                                            <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 200px;">
                                                <img src="{!!$hase_merchant->merchant_logo!!}" alt="profile pic" class="profile_pic">
                                            </div>
                                            <div class="fileinput-filename" style="display: block !important;">
                                            </div>
                                            <div>
                                                <span class="btn btn-default btn-file">
                                                    <span class="fileinput-new">Select image</span>
                                                    <span class="fileinput-exists">Change</span>
                                                    <input id="merchant_logo" name="merchant_logo" type="file" class="form-control"/>
                                                </span>
                                                <a href="#" class="btn btn-danger fileinput-exists" data-dismiss="fileinput">Remove</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="merchant image Url" class="col-sm-3 control-label">Image Url</label>
                                    <div class="col-sm-8">
                                        <input id="live_image_url" name = "live_image_url" type="text" class="form-control" value="{!!$hase_merchant->merchant_logo!!}"> 
                                    </div>
                                </div>
                            @endif
                        @else
                            @if($hase_merchant->merchant_logo != "" && file_exists(public_path(env('image_dir_path').$hase_merchant->merchant_logo)))
                                <div class="form-group">
                                    <label for="MerchantLogo" class="col-sm-3 control-label">
                                        Merchant Logo
                                        <span class="help-block">
                                            Select a file to update Merchant Logo, otherwise leave blank.
                                        </span>
                                    </label>
                                    <div class="col-sm-9">
                                        <div class="fileinput fileinput-<?php echo isset($hase_merchant->merchant_logo)?'exists':'new' ?>" data-provides="fileinput">

                                            <div class="fileinput-new thumbnail"
                                                 style="width: 200px; height: 200px;">
                                                 <img src="{{asset(env('image_dir_path').'no_photo.png')}}" alt="profile pic" class="profile_pic">
                                            </div>
                                            <div class="fileinput-preview fileinput-exists thumbnail"
                                                 style="max-width: 200px; max-height: 200px;">
                                                @if($hase_merchant->merchant_logo)
                                                    <img src="{{asset(env('image_dir_path').$hase_merchant->merchant_logo)}}" alt="profile pic" class="profile_pic">
                                                @endif
                                            </div>
                                            <div class="fileinput-filename" style="display: block !important;">
                                                {!!$hase_merchant->merchant_logo!!}
                                            </div>
                                            <div>
                                                <span class="btn btn-default btn-file">
                                                    <span class="fileinput-new">Select image</span>
                                                    <span class="fileinput-exists">Change</span>
                                                    <input id="merchant_logo" name="merchant_logo" type="file" class="form-control"/>
                                                </span>
                                                <a href="#" class="btn btn-danger fileinput-exists" data-dismiss="fileinput">Remove</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="merchant image Url" class="col-sm-3 control-label">Image Url</label>
                                    <div class="col-sm-8">
                                        <input id="live_image_url" name = "live_image_url" type="text" class="form-control" > 
                                    </div>
                                </div>
                            @else
                                <div class="form-group">
                                    <label for="MerchantLogo" class="col-sm-3 control-label">
                                        Merchant Logo
                                        <span class="help-block">
                                            Select a file to update Merchant Logo, otherwise leave blank.
                                        </span>
                                    </label>
                                    <div class="col-sm-9">
                                        <div class="fileinput fileinput-new" data-provides="fileinput">
                                            <div class="fileinput-new thumbnail" style="width: 200px; height: 200px;">
                                                <img src="{{asset(env('image_dir_path').'no_photo.png')}}" alt="merchant logo ">
                                            </div>
                                            <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 200px;">
                                            </div>
                                            <div class="fileinput-filename" style="display: block !important;">
                                            </div>
                                            <div>
                                                <span class="btn btn-default btn-file">
                                                    <span class="fileinput-new">Select image</span>
                                                    <span class="fileinput-exists">Change</span>
                                                    <input id="merchant_logo" name="merchant_logo" type="file" class="form-control"/>
                                                </span>
                                                <a href="#" class="btn btn-danger fileinput-exists" data-dismiss="fileinput">Remove</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="image Url" class="col-sm-3 control-label">Image Url</label>
                                    <div class="col-sm-8">
                                        <input id="live_image_url" name = "live_image_url" type="text" class="form-control" > 
                                    </div>
                                </div>   
                            @endif
                        @endif
                        <?php
                        $merchantLogoImageCompactUrl = parse_url($hase_merchant->merchant_logo_compact);
                        ?>
                        @if(isset($merchantLogoImageCompactUrl['scheme']))
                            @if($merchantLogoImageCompactUrl['scheme'] == 'https' || $merchantLogoImageCompactUrl['scheme'] == 'http')
                                <div class="form-group">
                                    <label for="MerchantCompactLogo" class="col-sm-3 control-label">
                                        Merchant Logo compact
                                        <span class="help-block">
                                            Select a file to update Merchant Logo compact, otherwise leave blank.
                                        </span>
                                    </label>
                                    <div class="col-sm-9">
                                        <div class="fileinput fileinput-<?php echo isset($hase_merchant->merchant_logo_compact)?'exists':'new' ?>" data-provides="fileinput">
                                            <div class="fileinput-new thumbnail" style="width: 200px; height: 200px;">
                                                <img src="{{asset(env('image_dir_path').'no_photo.png')}}" alt="merchant logo ">
                                            </div>
                                            <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 200px;">
                                                <img src="{!!$hase_merchant->merchant_logo_compact!!}" alt="profile pic" class="profile_pic">
                                            </div>
                                            <div class="fileinput-filename" style="display: block !important;">
                                            </div>
                                            <div>
                                                <span class="btn btn-default btn-file">
                                                    <span class="fileinput-new">Select image</span>
                                                    <span class="fileinput-exists">Change</span>
                                                    <input id="merchant_logo_compact" name="merchant_logo_compact" type="file" class="form-control"/>
                                                </span>
                                                <a href="#" class="btn btn-danger fileinput-exists" data-dismiss="fileinput">Remove</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="Merchant Image Compact Url" class="col-sm-3 control-label">Image Compact Url</label>
                                    <div class="col-sm-8">
                                        <input id="live_image_compact_url" name = "live_image_compact_url" type="text" class="form-control" value="{!!$hase_merchant->merchant_logo_compact!!}"> 
                                    </div>
                                </div>
                            @endif
                        @else
                            @if($hase_merchant->merchant_logo_compact != "" && file_exists(public_path(env('image_dir_path').$hase_merchant->merchant_logo_compact)))
                                <div class="form-group">
                                    <label for="MerchantCompactLogo" class="col-sm-3 control-label">
                                        Merchant Logo compact
                                        <span class="help-block">
                                            Select a file to update Merchant Logo compact, otherwise leave blank.
                                        </span>
                                    </label>
                                    <div class="col-sm-9">
                                        <div class="fileinput fileinput-<?php echo isset($hase_merchant->merchant_logo_compact)?'exists':'new' ?>" data-provides="fileinput">
                                            <div class="fileinput-new thumbnail" style="width: 200px; height: 200px;">
                                                <img src="{{asset(env('image_dir_path').'no_photo.png')}}" alt="merchant logo ">
                                            </div>
                                            <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 200px;">
                                                <img src="{{asset(env('image_dir_path').$hase_merchant->merchant_logo_compact)}}" alt="merchant logo compact">
                                            </div>
                                            <div class="fileinput-filename" style="display: block !important;">
                                                {!!$hase_merchant->merchant_logo_compact!!}
                                            </div>
                                            <div>
                                                <span class="btn btn-default btn-file">
                                                    <span class="fileinput-new">Select image</span>
                                                    <span class="fileinput-exists">Change</span>
                                                    <input id="merchant_logo_compact" name="merchant_logo_compact" type="file" class="form-control"/>
                                                </span>
                                                <a href="#" class="btn btn-danger fileinput-exists" data-dismiss="fileinput">Remove</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="Merchant Image Compact Url" class="col-sm-3 control-label">Image Compact Url</label>
                                    <div class="col-sm-8">
                                        <input id="live_image_compact_url" name = "live_image_compact_url" type="text" class="form-control" > 
                                    </div>
                                </div>
                            @else
                                <div class="form-group">
                                    <label for="MerchantCompactLogo" class="col-sm-3 control-label">
                                        Merchant Logo compact
                                        <span class="help-block">
                                            Select a file to update Merchant Logo compact, otherwise leave blank.
                                        </span>
                                    </label>
                                    <div class="col-sm-9">
                                        <div class="fileinput fileinput-new" data-provides="fileinput">
                                            <div class="fileinput-new thumbnail" style="width: 200px; height: 200px;">
                                                <img src="{{asset(env('image_dir_path').'no_photo.png')}}" alt="merchant logo ">
                                            </div>
                                            <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 200px;">
                                            </div>
                                            <div class="fileinput-filename" style="display: block !important;">
                                            </div>
                                            <div>
                                                <span class="btn btn-default btn-file">
                                                    <span class="fileinput-new">Select image</span>
                                                    <span class="fileinput-exists">Change</span>
                                                    <input id="merchant_logo_compact" name="merchant_logo_compact" type="file" class="form-control"/>
                                                </span>
                                                <a href="#" class="btn btn-danger fileinput-exists" data-dismiss="fileinput">Remove</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="Merchant Image Compact Url" class="col-sm-3 control-label">Image Compact Url</label>
                                    <div class="col-sm-8">
                                        <input id="live_image_compact_url" name = "live_image_compact_url" type="text" class="form-control" > 
                                    </div>
                                </div>   
                            @endif
                        @endif
                        <div class="form-group">
                            <label for="Status" class="col-sm-3 control-label">Status</label>
                            <div class="col-sm-8">
                                <div class="make-switch" data-on="danger" data-off="default">
                                    @if($hase_merchant->merchant_status)
                                        <input value="{!!$hase_merchant->
                                        merchant_status!!}" id="merchant_status" name="merchant_status" type="checkbox" checked="true" />
                                    @else
                                        <input value="{!!$hase_merchant->
                                        merchant_status!!}" id="merchant_status" name="merchant_status" type="checkbox" />
                                    @endif
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

<script type="text/javascript" src="{{asset('assets/vendors/select2/js/select2.js')}}"></script>

<script type="text/javascript" src="{{asset('assets/vendors/jasny-bootstrap/js/jasny-bootstrap.js')}}"></script>

<script type="text/javascript" src="{{asset('assets/vendors/bootstrap-switch/js/bootstrap-switch.js')}}"></script>

<script type="text/javascript" src="{{asset('assets/vendors/bootstrapvalidator/js/bootstrapValidator.min.js')}}" ></script>

<script type="text/javascript" src="{{asset('assets/js/custom_js/HaseMerchant.js')}}"></script>

<script type="text/javascript">
    
    @if(Session::has('type'))
        toastr.options = {
            "closeButton": true,
            "positionClass": "toast-top-right",
            "showDuration": "1000",
            "hideDuration": "1000",
            "timeOut": "5000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "swing",
            "showMethod": "show"
        };
        var $toast = toastr["{{ Session::pull('type') }}"]("", "{{ Session::pull('msg') }}");
    @endif

</script>
@stop