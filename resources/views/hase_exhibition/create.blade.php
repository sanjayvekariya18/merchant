@extends('layouts/default')

{{-- Page title --}}
@section('title')
    Exhibition
    @parent
@stop

{{-- page level styles --}}
@section('header_styles')
    <!--page level css -->
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/bootstrapvalidator/css/bootstrapValidator.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datedropper/datedropper.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/timedropper/css/timedropper.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/select2/css/select2.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/select2/css/select2-bootstrap.css')}}">
    
    <!--end of page level css-->
@stop
@section('content')
<section class="content-header">
    <h1>Exhibition</h1>
    <ol class="breadcrumb">
        <li>
            <a href="index ">
                <i class="fa fa-fw fa-home"></i> Dashboard
            </a>
        </li>
        <li>
            <a href="#"> Exhibition</a>
        </li>
    </ol>
</section>
<br>
<section class="content">
    <form method = 'POST' enctype="multipart/form-data" class="form-horizontal bv-form" action='{!!url("hase_exhibition")!!}' id="hase_exhibition_create">
        <input type = 'hidden' name = '_token' value = '{{Session::token()}}'>
        <div class="row">
            <div class="col-md-12">
                <button type="submit" name="submitBtn" value="Save" class='btn btn-primary btn-inline'>Save</button>
                <button type="submit" name="submitBtn" value="SaveClose" class = 'btn btn-primary btn-inline'>Save &amp; close</button>
                <a href="{!!url('hase_exhibition')!!}" class='btn btn-primary btn-inline'>
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
                               data-loop="true"></i> Create Exhibition
                        </h3>
                        <span class="pull-right clickable">
                            <i class="glyphicon glyphicon-chevron-up"></i>
                        </span>
                    </div>
                    <div class="panel-body">
                        <div class="form-group">
                            <label for="exhibition_name" class="col-sm-3 control-label">Exhibition Name</label>
                            <div class="col-sm-5">
                                <input id="exhibition_name" name="exhibition_name" type="text" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="venue_id" class="col-sm-3 control-label">Exhibition Venue</label>
                            <div class="col-sm-5">
                                <select name="venue_id" id="venue_id" class="form-control select21" style="width:100%">
                                    <option></option>
                                    @foreach($venues as $venue)
                                       <option value="{{$venue->venue_id}}">{{$venue->venue_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div id="holiday-hours">
                            <div class="form-group">
                                <div class="col-sm-1" >
                                </div>
                                <div class="col-sm-3" >
                                    <b>Date</b>
                                </div>
                                <div class="col-sm-3" >
                                    <b>Open hour</b>
                                </div>
                                <div class="col-sm-3" >
                                    <b>Close hour</b>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="holidayShift0">
                                    <div class="col-sm-1" >
                                        <a class="btn btn-primary btn-lg" style="padding: 3px 5px;" onclick="addNewHolidayShift();" >
                                            <i class="fa fa-plus"></i>
                                        </a>
                                    </div>
                                    <div class="col-sm-3" >
                                        <div class="input-group">
                                            <input type="text" name="holiday_hours[0][date]" class="form-control holiday_0_date" placeholder="YYYY-MM-DD"/>
                                            <div class="input-group-addon">
                                                <i class="fa fa-fw fa-calendar"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-3" >
                                        <div class="input-group">
                                            <input type="text" name="holiday_hours[0][open]" class="form-control holiday_0_open" placeholder="H:i">
                                            <span class="input-group-addon"><i class="fa fa-clock-o"></i></span>
                                        </div>
                                    </div>
                                    <div class="col-sm-3" >
                                        <div class="input-group">
                                            <input type="text" name="holiday_hours[0][close]" class="form-control holiday_0_close" placeholder="H:i">
                                            <span class="input-group-addon"><i class="fa fa-clock-o"></i></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
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
<script  type="text/javascript" src="{{asset('assets/vendors/datedropper/datedropper.js')}}" ></script>
<script  type="text/javascript" src="{{asset('assets/vendors/timedropper/js/timedropper.js')}}" ></script>
<script type="text/javascript" src="{{asset('assets/js/custom_js/HaseExhibitionCreate.js')}}"></script>>
<!-- end of page level js -->
@stop