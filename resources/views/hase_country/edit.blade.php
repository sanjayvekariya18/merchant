@extends('layouts/default')

{{-- Page title --}}
@section('title')
    Country
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
    <h1>Edit Country</h1>
    <ol class="breadcrumb">
        <li>
            <a href="index ">
                <i class="fa fa-fw fa-home"></i> Dashboard
            </a>
        </li>
        <li>
            <a href="#"> System</a>
        </li>
        <li class="active">
            Countries
        </li>
    </ol>
</section>

<section class="content">
    
    <form id="countryForm"  action = '{!! url("hase_country")!!}/{!!$hase_country->
        country_id!!}/update' method = 'POST' class="form-horizontal" role="form" enctype="multipart/form-data">
        <div class="row">
            <div class="col-md-12">
                <button type="submit" name="submitBtn" value="Save" class='btn btn-primary btn-inline'>Save</button>
                <button type="submit" name="submitBtn" value="SaveClose" class = 'btn btn-primary btn-inline'>Save  &amp; close</button>
                <a href="{!!url("hase_country")!!}" class='btn btn-primary btn-inline'>
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
                            <i class="fa fa-fw fa-crosshairs"></i> Country Form
                        </h3>
                    </div>
                    <div class="panel-body">
                        <input type = 'hidden' name = '_token' value = '{{Session::token()}}'>

                        <input type = 'hidden' name = 'country_id' value = '{!!$hase_country->country_id!!}'>                        
                        
                        <div class="form-group">
                            <label for="country_name" class="col-sm-3 ">Country Name</label>
                            <div class="col-sm-8">
                                <input id="country_name" name = "country_name" type="text" class="form-control" value="{!!$hase_country->country_name!!}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="iso_code_2" class="col-sm-3 ">ISO Code 2</label>
                            <div class="col-sm-8">
                                <input id="iso_code_2" name = "iso_code_2" type="text" class="form-control" maxlength="2" value="{!!$hase_country->iso_code_2!!}"> 
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="iso_code_3" class="col-sm-3 ">ISO Code 3</label>
                            <div class="col-sm-8">
                                <input id="iso_code_3" name = "iso_code_3" type="text" class="form-control" maxlength="3" value="{!!$hase_country->iso_code_3!!}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="format" class="col-sm-3 ">Format</label>
                            <div class="col-sm-8">
                                <input id="format" name = "format" type="text" class="form-control" value="{!!$hase_country->
                                format!!}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="country_phone_code" class="col-sm-3 ">Country Phone Code</label>
                            <div class="col-sm-8">
                                <input id="country_phone_code" name = "country_phone_code" type="text" class="form-control" value="{!!$hase_country->
                                country_phone_code!!}">
                            </div> 
                        </div>
                        <div class="form-group">
                            <label for="telephone_min" class="col-sm-3 ">Telephone Min</label>
                            <div class="col-sm-8">
                                <input id="telephone_min" name = "telephone_min" type="number" class="form-control" value="{!!$hase_country->telephone_min!!}"> 
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="telephone_max" class="col-sm-3 ">Telephone Max</label>
                            <div class="col-sm-8">
                                <input id="telephone_max" name = "telephone_max" type="number" class="form-control" value="{!!$hase_country->telephone_max!!}"> 
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="flag" class="col-sm-3">
                                Country Flag
                                <span class="help-block">
                                    Select a file to update Country Flag, otherwise leave blank.
                                </span>
                            </label>
                            <div class="col-sm-9">
                                <div class="fileinput fileinput-new" data-provides="fileinput">
                                    <div class="fileinput-new thumbnail" style="width: 200px; height: 200px;">
                                        @if(empty($hase_country->flag))
                                           <img src="{{asset(env('image_dir_path').'no_photo.png')}}" alt="country flag ">
                                        @else
                                           <img src="{{asset(env('image_dir_path').$hase_country->flag)}}" alt="country flag ">
                                        @endif
                                    </div>
                                    <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 200px;">
                                    </div>
                                    <div>
                                        <span class="btn btn-default btn-file">
                                            <span class="fileinput-new">Select image</span>
                                            <span class="fileinput-exists">Change</span>
                                            <input id="flag" name="flag" type="file" class="form-control"/>
                                        </span>
                                        <a href="#" class="btn btn-danger fileinput-exists" data-dismiss="fileinput">Remove</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="Status" class="col-sm-3">Status</label>
                            <div class="col-sm-8">
                                <div class="make-switch" data-on="danger" data-off="default">
                                    @if($hase_country->status)
                                        <input value="{!!$hase_country->
                                        status!!}" id="status" name="status" type="checkbox" checked="true" />
                                    @else
                                        <input value="{!!$hase_country->
                                        status!!}" id="status" name="status" type="checkbox" />
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

<script type="text/javascript" src="{{asset('assets/js/custom_js/HaseCountry.js')}}"></script>

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