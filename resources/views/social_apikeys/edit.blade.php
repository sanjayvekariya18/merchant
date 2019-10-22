@extends('layouts/default')

{{-- Page title --}}
@section('title')
    Social
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
    <h1>Edit Social</h1>
    <ol class="breadcrumb">
        <li>
            <a href="index ">
                <i class="fa fa-fw fa-home"></i> Dashboard
            </a>
        </li>
        
        <li class="active">
            Social
        </li>
    </ol>
</section>
<section class="content">
    <form id="assetForm"  action = '{!! url("social_apikeys")!!}/{!!$social_apikey->
        social_id!!}/update' method = 'POST' class="form-horizontal" role="form" enctype="multipart/form-data">
        <div class="row">
            <div class="col-md-12">
                <button type="submit" name="submitBtn" value="Save" class='btn btn-primary btn-inline'>Save</button>
                <button type="submit" name="submitBtn" value="SaveClose" class = 'btn btn-primary btn-inline'>Save  &amp; close</button>
                <a href="{!!url("social_apikeys")!!}" class='btn btn-primary btn-inline'>
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
                            <i class="fa fa-fw fa-crosshairs"></i> Edit Social Detail
                        </h3>
                    </div>
                    <div class="panel-body">
                        <input type = 'hidden' name = '_token' value = '{{Session::token()}}'>
                        <input id="social_id" name="social_id" type="hidden" class="form-control" value="{{$social_apikey->social_id}}">
                        <input id="identity_id" name="identity_id" type="hidden" class="form-control" value="{{$social_apikey->identity_id}}">
                        <div class="form-group">
                            <label for="social_code" class="col-sm-3 control-label">Social Code</label>
                            <div class="col-sm-4">
                                <input id="social_code" name="social_code" type="text" class="form-control" value="{{$social_apikey->social_code}}">
                            </div>
                        </div> 
                        <div class="form-group">
                            <label for="social_name" class="col-sm-3 control-label">Social Name</label>
                            <div class="col-sm-4">
                                <input id="social_name" name="social_name" type="text" class="form-control" value="{{$social_apikey->social_name}}">
                            </div>
                        </div>  
                        <div class="form-group">
                            <label for="connector_id" class="col-sm-3 control-label">Connector Name</label>
                            <div class="col-sm-4">
                                <select name="connector_id" id="connector_id" class="form-control select21" style="width:100%">
                                    <option></option>
                                    @foreach($connectors as $connector)
                                        @if($social_apikey->connector_id == $connector->connector_id)
                                            <option value="{{$connector->connector_id}}" selected="">{{$connector->connector_name}}</option>
                                        @else
                                            <option value="{{$connector->connector_id}}">{{$connector->connector_name}}</option>
                                        @endif    
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="connector_key" class="col-sm-3 control-label">Connector Key</label>
                            <div class="col-sm-4">
                                <input id="connector_key" name="connector_key" type="text" class="form-control" value="{{$social_apikey->connector_key}}">
                            </div>
                        </div> 
                        <div class="form-group">
                            <label for="connector_passcode" class="col-sm-3 control-label">Connector Passcode</label>
                            <div class="col-sm-4">
                                <input id="connector_passcode" name="connector_passcode" type="text" class="form-control" value="{{$social_apikey->connector_passcode}}">
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
<script type="text/javascript" src="{{asset('assets/js/custom_js/SocialApikeysEdit.js')}}"></script>
@stop