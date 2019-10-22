@php use App\Http\Traits\PermissionTrait; @endphp
@extends('layouts/default')

{{-- Page title --}}
@section('title')
    Venue
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
    <h1>Edit Venue</h1>
    <ol class="breadcrumb">
        <li>
            <a href="index ">
                <i class="fa fa-fw fa-home"></i> Dashboard
            </a>
        </li>
        <li>
            <a href="#">Edit Venue</a>
        </li>
    </ol>
</section>
<br>
<section class="content">
    <form method = 'POST' enctype="multipart/form-data" class="form-horizontal bv-form" action='{!!url("hase_venue")!!}/{!!$hase_venue->venue_id!!}/update' id="hase_venue_edit">
        <input type = 'hidden' name = '_token' value = '{{Session::token()}}'>
        <div class="row">
            <div class="col-md-12">
                <button type="submit" name="submitBtn" value="Save" class='btn btn-primary btn-inline'>Save</button>
                <button type="submit" name="submitBtn" value="SaveClose" class = 'btn btn-primary btn-inline'>Save &amp; close</button>
                <a href="{!!url('hase_venue')!!}" class='btn btn-primary btn-inline'>
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
                               data-loop="true"></i> Edit Venue
                        </h3>
                        <span class="pull-right clickable">
                            <i class="glyphicon glyphicon-chevron-up"></i>
                        </span>
                    </div>
                    <div class="panel-body">
                        <div class="form-group">
                            <label for="venue_name" class="col-sm-3 control-label">Venue Name</label>
                            <div class="col-sm-5">
                                <input id="venue_name" name="venue_name" type="text" class="form-control" value="{!!$hase_venue->venue_name!!}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="venue_city_id" class="col-sm-3 control-label">Venue City</label>
                            <div class="col-sm-5"> 
                                <select name="venue_city_id" id="venue_city_id" class = "form-control select21">
                                    @foreach($hase_cities as $hase_city)
                                        @if($hase_city->city_id == $hase_venue->postal_city)
                                            <option value="{{$hase_city->city_id}}" selected>{{$hase_city->city_name}}</option>
                                        @else
                                            <option value="{{$hase_city->city_id}}">{{$hase_city->city_name}}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="venue_address1" class="col-sm-3 control-label">Venue Address1</label>
                            <div class="col-sm-5">
                                <input id="venue_address1" name="venue_address1" type="text" class="form-control" value="{!!$hase_venue->postal_route!!}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="venue_address2" class="col-sm-3 control-label">Venue Address2</label>
                            <div class="col-sm-5">
                                <input id="venue_address2" name="venue_address2" type="text" class="form-control" value="{!!$hase_venue->postal_premise!!}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="venue_gps_lat" class="col-sm-3 control-label">Latitude</label>
                            <div class="col-sm-5">
                                <input id="venue_gps_lat" name="venue_gps_lat" type="text" class="form-control" value="{!!$hase_venue->postal_lat!!}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="venue_gps_lng" class="col-sm-3 control-label">Longitude</label>
                            <div class="col-sm-5">
                                <input id="venue_gps_lng" name="venue_gps_lng" type="text" class="form-control" value="{!!$hase_venue->postal_lng!!}">
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
<script type="text/javascript" src="{{asset('assets/js/custom_js/HaseVenueEdit.js')}}"></script>>
<!-- end of page level js -->
@stop