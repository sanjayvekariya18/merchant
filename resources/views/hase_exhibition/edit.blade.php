@php use App\Http\Traits\PermissionTrait; @endphp
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
    <form method = 'POST' enctype="multipart/form-data" class="form-horizontal bv-form" action='{!!url("hase_exhibition")!!}/{!!$hase_exhibition->exhibition_id!!}/update' id="hase_exhibition_edit">
        <input type = 'hidden' name = '_token' value = '{{Session::token()}}'>
        <input type = 'hidden' name = 'exhibitionDays' id='exhibitionDays' value = '{!!$total_exhibition_day!!}'>
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
                               data-loop="true"></i> Edit Exhibition
                        </h3>
                        <span class="pull-right clickable">
                            <i class="glyphicon glyphicon-chevron-up"></i>
                        </span>
                    </div>
                    <div class="panel-body">
                        <div class="form-group">
                            <label for="exhibition_name" class="col-sm-3 control-label">Exhibition Name</label>
                            <div class="col-sm-5">
                                <input id="exhibition_name" name="exhibition_name" type="text" class="form-control" value="{!!$hase_exhibition->exhibition_name!!}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="venue_id" class="col-sm-3 control-label">Exhibition Venue</label>
                            <div class="col-sm-5">
                                <select name="venue_id" id="venue_id" class="form-control select21" style="width:100%">
                                    <option></option>
                                    @foreach($venues as $venue)
                                        @if($venue->venue_id == $hase_exhibition->venue_id)
                                            <option value="{{$venue->venue_id}}" selected="true">{{$venue->venue_name}}</option>
                                        @else
                                            <option value="{{$venue->venue_id}}">{{$venue->venue_name}}</option>
                                        @endif    
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
                            <?php $exhibitionFlag = true ?>
                            <div class="form-group">
                                @foreach($hase_exhibition_working_hours as $working_hour_key => $working_hour_value)

                                    <?php
                                    $exhibition_date =  date('Y-m-d',strtotime($working_hour_value->exhibition_date));
                                    $exhibition_time_open = PermissionTrait::convertIntoTime($working_hour_value->exhibition_time_start); 
                                    $exhibition_time_close = PermissionTrait::convertIntoTime($working_hour_value->exhibition_time_end); 
                                    ?>
                                    
                                    @if($exhibitionFlag)
                                    <div class="holidayShift{{$working_hour_key}}">
                                        <div class="col-sm-1" >
                                            <a class="btn btn-primary btn-lg" style="padding: 3px 5px;" onclick="addNewHolidayShift();" >
                                                <i class="fa fa-plus"></i>
                                            </a>
                                        </div>
                                    <?php $exhibitionFlag = false ?>
                                    @else
                                    <div class="holidayShift{{$working_hour_key}}" style="margin-top:10px">
                                        <div class="col-sm-1" >
                                            <a class="btn btn-danger" onclick="confirm('This can not be undone! Are you sure you want to do this?') ? $(this).parent().parent().remove() : false;" style="padding: 4px 6px;">
                                                <i class="fa fa-times-circle"></i>
                                            </a>
                                        </div>

                                    @endif
                                        <div class="col-sm-3" >
                                            <div class="input-group">
                                                <input type="text" name="holiday_hours[{{$working_hour_key}}][date]" class="form-control holiday_0_date" placeholder="YYYY-MM-DD" value="{!!$exhibition_date!!}" />
                                                <div class="input-group-addon">
                                                    <i class="fa fa-fw fa-calendar"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-3" >
                                            <div class="input-group">
                                                <input type="text" name="holiday_hours[{{$working_hour_key}}][open]" class="form-control holiday_0_open" placeholder="H:i" value="{!!$exhibition_time_open!!}">
                                                <span class="input-group-addon"><i class="fa fa-clock-o"></i></span>
                                            </div>
                                        </div>
                                        <div class="col-sm-3" >
                                            <div class="input-group">
                                                <input type="text" name="holiday_hours[{{$working_hour_key}}][close]" class="form-control holiday_0_close" placeholder="H:i" value="{!!$exhibition_time_close!!}">
                                                <span class="input-group-addon"><i class="fa fa-clock-o"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                @endforeach
                            </div>
                        </div>
                        <!-- <div class="form-group">
                            <label for="exhibition_date_start" class="col-sm-3 control-label">Exhibition Start Date</label>
                            <div class="col-sm-5">
                                <input id="exhibition_date_start" name="exhibition_date_start" type="text" class="form-control pull-left exhibitionDate" data-language='en' placeholder="MM/DD/YYYY" value="{!!$hase_exhibition->exhibition_date_start!!}" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="exhibition_time_start" class="col-sm-3 control-label">Exhibition Start Time</label>
                            <div class="col-sm-5">
                                <?php
                                    $exhibition_time_start = PermissionTrait::convertIntoTime($hase_exhibition->exhibition_time_start); 
                                    ?>
                                <input type="text" class="form-control exhibitionTime" id="exhibition_time_start" name="exhibition_time_start" value="{!!$exhibition_time_start!!}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="exhibition_date_end" class="col-sm-3 control-label">Exhibition End Date</label>
                            <div class="col-sm-5">
                                <input id="exhibition_date_end" name="exhibition_date_end" type="text" class="form-control pull-left exhibitionDate" data-language='en' placeholder="MM/DD/YYYY" value="{!!$hase_exhibition->exhibition_date_end!!}" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="exhibition_time_end" class="col-sm-3 control-label">Exhibition End Time</label>
                            <div class="col-sm-5">
                                <?php
                                    $exhibition_time_end = PermissionTrait::convertIntoTime($hase_exhibition->exhibition_time_end); 
                                ?>
                                <input type="text" class="form-control exhibitionTime" id="exhibition_time_end" name="exhibition_time_end" value="{!!$exhibition_time_end!!}" >
                                
                            </div>
                        </div> -->
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
<script type="text/javascript" src="{{asset('assets/js/custom_js/HaseExhibitionEdit.js')}}"></script>>
<!-- end of page level js -->
@stop