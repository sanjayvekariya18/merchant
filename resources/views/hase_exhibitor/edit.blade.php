@php use App\Http\Traits\PermissionTrait; @endphp
@extends('layouts/default')

{{-- Page title --}}
@section('title')
    Exhibitor
    @parent
@stop

{{-- page level styles --}}
@section('header_styles')
    <!--page level css -->
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/bootstrapvalidator/css/bootstrapValidator.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/select2/css/select2.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/select2/css/select2-bootstrap.css')}}">
    <!--end of page level css-->
@stop
@section('content')
<section class="content-header">
    <h1>Edit Exhibitor</h1>
    <ol class="breadcrumb">
        <li>
            <a href="index ">
                <i class="fa fa-fw fa-home"></i> Dashboard
            </a>
        </li>
        <li>
            <a href="#">Edit Exhibitor</a>
        </li>
    </ol>
</section>
<br>
<section class="content">
    <form method = 'POST' enctype="multipart/form-data" class="form-horizontal bv-form" action='{!!url("hase_exhibitor")!!}/{!!$hase_exhibitor->exhibitor_id!!}/update' id="hase_exhibitor_edit">
        <input type = 'hidden' name = '_token' value = '{{Session::token()}}'>
        <div class="row">
            <div class="col-md-12">
                <button type="submit" name="submitBtn" value="Save" class='btn btn-primary btn-inline'>Save</button>
                <button type="submit" name="submitBtn" value="SaveClose" class = 'btn btn-primary btn-inline'>Save &amp; close</button>
                <a href="{!!url('hase_exhibitor')!!}" class='btn btn-primary btn-inline'>
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
                            <i class="livicon" data-name="user-add" data-size="18" data-c="#fff" data-hc="#fff"
                               data-loop="true"></i> Edit Exhibitor
                        </h3>
                        <span class="pull-right clickable">
                            <i class="glyphicon glyphicon-chevron-up"></i>
                        </span>
                    </div>
                    <div class="panel-body">
                        <div class="form-group">
                            <label for="merchant_id" class="col-sm-3 control-label">Merchant</label>
                            <div class="col-sm-5"> 
                                <select name="merchant_id" id="merchant_id" class = "form-control select21">
                                    @foreach($hase_merchants as $hase_merchant)
                                        @if($hase_merchant->merchant_id == $hase_exhibitor->merchant_id)
                                            <option value="{{$hase_merchant->merchant_id}}" selected>{{$hase_merchant->merchant_name}}</option>
                                        @else
                                            <option value="{{$hase_merchant->merchant_id}}">{{$hase_merchant->merchant_name}}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="exhibitor_floor" class="col-sm-3 control-label">Exhibitor Floor</label>
                            <div class="col-sm-5">
                                <input id="exhibitor_floor" name="exhibitor_floor" type="text" class="form-control" value="{!!$hase_exhibitor->exhibitor_floor!!}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="exhibitor_hall" class="col-sm-3 control-label">Exhibitor Hall</label>
                            <div class="col-sm-5">
                                <input id="exhibitor_hall" name="exhibitor_hall" type="text" class="form-control" value="{!!$hase_exhibitor->exhibitor_hall!!}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="exhibitor_location" class="col-sm-3 control-label">Exhibitor Location</label>
                            <div class="col-sm-5">
                                <input id="exhibitor_location" name="exhibitor_location" type="text" class="form-control" value="{!!$hase_exhibitor->exhibitor_location!!}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="exhibitor_description" class="col-sm-3 control-label">Exhibitor Description</label>
                            <div class="col-sm-5">
                                <input id="exhibitor_description" name="exhibitor_description" type="text" class="form-control" value="{!!$hase_exhibitor->exhibitor_description!!}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="exhibitor_contact" class="col-sm-3 control-label">Exhibitor Contact</label>
                            <div class="col-sm-5">
                                <input id="exhibitor_contact" name="exhibitor_contact" type="text" class="form-control" value="{!!$hase_exhibitor->exhibitor_contact!!}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="exhibitor_namecard_url" class="col-sm-3 control-label">Exhibitor Namecard Url</label>
                            <div class="col-sm-5">
                                <input id="exhibitor_namecard_url" name="exhibitor_namecard_url" type="text" class="form-control" value="{!!$hase_exhibitor->exhibitor_namecard_url!!}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="exhibitor_location_map_url" class="col-sm-3 control-label">Exhibitor Location Map Url</label>
                            <div class="col-sm-5">
                                <input id="exhibitor_location_map_url" name="exhibitor_location_map_url" type="text" class="form-control" value="{!!$hase_exhibitor->exhibitor_location_map_url!!}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="exhibitor_location_directions" class="col-sm-3 control-label">Exhibitor Location Directions</label>
                            <div class="col-sm-5">
                                <input id="exhibitor_location_directions" name="exhibitor_location_directions" type="text" class="form-control" value="{!!$hase_exhibitor->exhibitor_location_directions!!}">
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
<script type="text/javascript" src="{{asset('assets/vendors/bootstrapvalidator/js/bootstrapValidator.min.js')}}" ></script>
<script type="text/javascript" src="{{asset('assets/vendors/select2/js/select2.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/js/custom_js/HaseExhibitorEdit.js')}}"></script>>
<!-- end of page level js -->
@stop