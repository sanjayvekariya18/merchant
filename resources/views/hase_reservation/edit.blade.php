@extends('layouts/default')
{{-- Page title --}}
@section('title')
    Edit Reservation
    @parent
@stop

{{-- page level styles --}}
@section('header_styles')
    <!--page level css -->
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/bootstrap-switch/css/bootstrap-switch.css')}}"/>
    <link href="{{asset('assets/vendors/airdatepicker/css/datepicker.min.css')}}" rel="stylesheet" type="text/css">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/timedropper/css/timedropper.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/select2/css/select2.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/select2/css/select2-bootstrap.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/dataTables.bootstrap.css')}}"/>
    <!--end of page level css-->
@stop
@section('content')

<section class="content-header">
    <h1>Edit Reservations</h1>
    <ol class="breadcrumb">
        <li>
            <a href="index ">
                <i class="fa fa-fw fa-home"></i> Dashboard
            </a>
        </li>
        <li>
            <a href="#"> Table Reservation</a>
        </li>
        <li class="active">
            Edit Reservation
        </li>
    </ol>
</section>
<section class="content">
   
    <form method = 'POST' action = '{!! url("hase_reservation")!!}/{!!$hase_reservation->reservation_id!!}/update'>
        <input type = 'hidden' name='_token' value = '{{Session::token()}}'>
        <input id="reservation_id" name = "reservation_id" type="hidden" value="{!!$hase_reservation->reservation_id!!}">
        <input id="location_id" name="location_id" type="hidden" value="{!!$hase_reservation->location_id!!}">
        <input id="seating_id" name="seating_id" type="hidden" value="{!!$hase_reservation->seating_id!!}">
        <input id="customnotify" name = "customnotify" type="hidden" value="{!!$hase_reservation->notify!!}">
        <div class="row">
            <div class="col-md-12">
                <button type="submit" name="submitbutton" value="Save" class='btn btn-primary btn-inline'>Save</button>
                <button type="submit" name="submitbutton" value="SaveClose" class= 'btn btn-primary btn-inline'>Save &amp; Close</button>
                <a href="{!!url("hase_reservation")!!}" class='btn btn-primary btn-inline'>
                    <i class="fa fa-angle-double-left"></i>
                </a>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-lg-12">
                <ul class="nav nav-tabs ">
                    <li class="active">
                        <a href="#tab1" data-toggle="tab">
                            Reservation
                        </a>
                    </li>
                    <li>
                        <a href="#tab2" data-toggle="tab">History</a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div id="tab1" class="tab-pane wrap-all active in">
                        <div class="row">
                            <div class="col-xs-12" style="margin-top: 15px;">
                                <div class="panel panel-primary">
                                    <div class="panel-heading">
                                        <h3 class="panel-title">Status & Assign</h3>
                                    </div>
                                    <div class="panel-body">
                                        <div class="col-xs-12 col-sm-3">
                                            <label class="control-label">Assign Staff</label>
                                            <input type="hidden" id="assignStaff" value="{!!$hase_reservation->assignee_id!!}"/>
                                            <select name="assignee_id" id="assignee_id" class="form-control select21">
                                                <option value="0"> - please select - </option>
                                                @foreach($hase_staffs as $hase_staff)
                                                    @if($hase_staff['staff_id'] == $hase_reservation->assignee_id)
                                                        <option value="{{$hase_staff['staff_id']}}" selected>{{$hase_staff['staff_name']}}</option>
                                                    @else
                                                        <option value="{{$hase_staff['staff_id']}}">{{$hase_staff['staff_name']}}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-xs-12 col-sm-2">
                                            <label class="control-label">Reservation Status</label>
                                            <input type="hidden" id="reserveStatus" value="{!!$hase_reservation->status!!}"/>
                                            <select name="status" id="status" class="form-control select21">
                                                @foreach($hase_statuses as $hase_status)
                                                    @if($hase_status->status_id == $hase_reservation->status)
                                                        <option value="{{$hase_status->status_id}}" data-comment="{{$hase_status->status_comment}}" selected>{{$hase_status->status_name}}</option>
                                                    @else
                                                        <option value="{{$hase_status->status_id}}" data-comment="{{$hase_status->status_comment}}">{{$hase_status->status_name}}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-xs-12 col-sm-5">
                                            <label class="control-label">Comment</label>
                                            <div class="">
                                                <textarea id="status_comment" name="status_comment" rows="3" class="form-control">
                                                    @foreach($hase_statuses as $hase_status)
                                                        @if ($hase_status->status_id == $hase_reservation->status)
                                                            {{$hase_status->status_comment}}
                                                        @endif
                                                    @endforeach
                                                </textarea>
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-sm-2">
                                            <label class="control-label">Notify Customer</label>
                                            <div class="make-switch col-sm-10" data-on="danger" data-off="default">
                                                <input type="checkbox" name="notify" id="notify" data-on-text="Yes" data-off-text="No" value="<?php echo isset($hase_reservation->notify) ? $hase_reservation->notify : 0; ?>">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 col-sm-4" style="margin-top: 10px;">
                                <div class="panel panel-primary">
                                    <div class="panel-heading">
                                        <h3 class="panel-title">Reservation</h3>
                                    </div>
                                    <div class="panel-body">
                                        <div class="form-group col-xs-12">
                                            <label class="control-label">Reservation ID</label>
                                            <div class="">
                                                #{!!$hase_reservation->reservation_id!!}
                                            </div>
                                        </div>
                                        <input type="hidden" name="reserve_people_old" value="{!!$hase_reservation->guest_num!!}">
                                        <div class="form-group col-xs-12">
                                            <label class="control-label">Guest Number</label>
                                            <div class="">
                                                <select name="guest_num" id="guest_num" class="form-control select21">
                                                    <option></option>
                                                    <?php for($guestNum = 1; $guestNum<=20; $guestNum++) {
                                                        if($guestNum == $hase_reservation->guest_num) { ?>
                                                            <option value="<?php echo $guestNum; ?>" selected><?php echo $guestNum; ?></option>
                                                        <?php } else { ?>
                                                        <option value="<?php echo $guestNum; ?>"><?php echo $guestNum; ?></option>
                                                    <?php } } ?>
                                                </select>
                                            </div>
                                        </div>
                                        <input type="hidden" name="reserve_date_old" value="{!!$hase_reservation->reserve_date!!}">
                                        <input type="hidden" name="reserve_time_old" value="{!!$hase_reservation->reserve_time!!}">
                                        <div class="form-group col-xs-12">
                                            <label class="control-label">Reservation Date</label>
                                            <div class="">
                                                <input id="reserve_date" name="reserve_date" type="text" value="<?php if($hase_reservation->reserve_date != 0) { echo substr_replace(substr_replace($hase_reservation->reserve_date, '-', 4, 0), '-', 7, 0); } else { echo '';}?>" class="form-control pull-left" data-language='en' />
                                            </div>
                                        </div>
                                        <div class="form-group col-xs-12">
                                            <label class="control-label">Reservation Time</label>
                                            <div class="">
                                                @if(isset($hase_reservation->reserve_time))
                                                    <?php
                                                        $reserveMinutes = $hase_reservation->reserve_time/60;
                                                        $reserveHour = sprintf("%02d", floor($reserveMinutes/60));
                                                        $reserveMinute = sprintf("%02d", ($reserveMinutes % 60));
                                                        $reserveTime = $reserveHour.':'.$reserveMinute;
                                                    ?>
                                                @endif
                                                <input id="reserve_time" name="reserve_time" type="text" value="<?php echo isset($reserveTime)? $reserveTime:'' ?>" class="form-control required">
                                            </div>
                                        </div>
                                        <div class="form-group col-xs-12">
                                            <label class="control-label">Occasion</label>
                                            <div class="">
                                                @if(isset($hase_reservation->occasion_id))
                                                    {!!$hase_reservation->occasion_id!!}
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xs-12 col-sm-4" style="margin-top: 15px;">
                                <div class="panel panel-primary">
                                    <div class="panel-heading">
                                        <h3 class="panel-title">Customer</h3>
                                    </div>
                                    <div class="panel-body">
                                        <div class="form-group col-xs-12">
                                            <label class="control-label">Name</label>
                                            <div class="">
                                                @if(isset($hase_customer->customer_name))
                                                    {!!$hase_customer->customer_name!!} 
                                                @endif                                                
                                            </div>
                                        </div>                                        
                                        <div class="form-group col-xs-12">
                                            <label class="control-label">Email</label>
                                            <div class="">
                                                @if(isset($hase_customer->email))
                                                    {!!$hase_customer->email!!}
                                                @endif
                                            </div>
                                        </div>
                                        <div class="form-group col-xs-12">
                                            <label class="control-label">Telephone</label>
                                            <div class="">
                                                @if(isset($hase_customer->telephone))
                                                    {!!$hase_customer->telephone!!}
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xs-12 col-sm-4" style="margin-top: 15px;">
                                <div class="panel panel-primary">
                                    <div class="panel-body">
                                        <div class="form-group col-xs-12">
                                            <label class="control-label">Date Added</label>
                                            <div class="">
                                                <?php
                                                    echo substr_replace(substr_replace($hase_reservation->date_added, '-', 4, 0), '-', 7, 0);
                                                ?>
                                            </div>
                                        </div>
                                        <div class="form-group col-xs-12">
                                            <label class="control-label">Date Modified</label>
                                            <div class="">
                                                <?php
                                                    echo substr_replace(substr_replace($hase_reservation->date_modified, '-', 4, 0), '-', 7, 0);
                                                ?>
                                            </div>
                                        </div>
                                        <div class="form-group col-xs-12">
                                            <label class="control-label">Customer Notified</label>
                                            <div class="">
                                                @if ($hase_reservation->notify == 1)
                                                    YES
                                                @else
                                                    NO
                                                @endif
                                            </div>
                                        </div>
                                        <div class="form-group col-xs-12">
                                            <label class="control-label">IP Address</label>
                                            <div class="">
                                                {!!$hase_reservation->ip_address!!}
                                            </div>
                                        </div>
                                        <div class="form-group col-xs-12">
                                            <label class="control-label">User Agent</label>
                                            <div class="">
                                                {!!$hase_reservation->user_agent!!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-xs-12 col-sm-6" style="margin-top: 10px;">
                                <div class="panel panel-primary">
                                    <div class="panel-heading">
                                        <h3 class="panel-title">Restaurant</h3>
                                    </div>
                                    <div class="panel-body">
                                        <div class="form-group col-xs-12">
                                            <div class="">
                                                {{$hase_location->postal_subpremise}},
                                                {{$hase_location->postal_premise}},
                                                {{$hase_location->postal_street_number}},
                                                {{$hase_location->postal_street}},
                                                {{$hase_location->postal_route}}
                                                <br>
                                                {{$hase_location->city_name}},<br>
                                                {{$hase_location->county_name}},<br>
                                                {{$hase_location->state_name}} - 
                                                {{$hase_location->postal_postcode}},<br>
                                                {{$hase_location->country_name}}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        
                            <div class="col-xs-12 col-sm-6" style="margin-top: 10px;">
                                <div class="panel panel-primary">
                                    <div class="panel-heading">
                                        <h3 class="panel-title">Table</h3>
                                    </div>
                                    <div class="panel-body">
                                        <div class="form-group col-xs-12">
                                            <label class="control-label">Table Name</label>
                                            <div class="">
                                                @if(isset($seatings))
                                                    {{$seatings->seating_name}}
                                                @endif
                                            </div>
                                        </div>
                                        <div class="form-group col-xs-12">
                                            <label class="control-label">Table Minimum</label>
                                            <div class="">
                                                @if(isset($seatings))
                                                    {{$seatings->min_capacity}}
                                                @endif
                                            </div>
                                        </div>
                                        <div class="form-group col-xs-12">
                                            <label class="control-label">Table Capacity</label>
                                            <div class="">
                                                @if(isset($seatings))
                                                    {{$seatings->max_capacity}}
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-xs-12 col-sm-12" style="margin-top: 10px;">
                                <div class="panel panel-primary">
                                    <div class="panel-heading">
                                        <h3 class="panel-title">Comment</h3>
                                    </div>
                                    <div class="panel-body">
                                        <div class="form-group col-xs-12">
                                            <div class="">
                                                <input type="hidden" name="reserve_comment_old" value="{!!$hase_reservation->comment!!}">
                                                <textarea id="comment" name="comment" rows="3" class="form-control">{!!$hase_reservation->comment!!}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div id="tab2" class="tab-pane fade">
                        <div class="row">
                            <div class="col-xs-12" style="margin-top: 15px;">
                                <div class="panel panel-primary">
                                    <div class="panel-heading">
                                        <h3 class="panel-title">Status History</h3>
                                    </div>
                                    <div class="panel-body">
                                        <div class="table-responsive">
                                            <table class="table table-border table-no-spacing" id="table1">
                                                <thead>
                                                    <tr>
                                                        <th>Date - Time</th>
                                                        <th>Assigned Staff</th>
                                                        <th>Staff Assignee</th>
                                                        <th>Status</th>
                                                        <th>Comment</th>
                                                        <th>Customer Notified</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($hase_status_history as $hase_history)
                                                    <tr>
                                                        <td>{{$hase_history->date_added}}</td>
                                                        <td>{{$hase_history->staff_name}}</td>
                                                        <td>{{$hase_history->assignee_name}}</td>
                                                        <td>{{$hase_history->status_name}}</td>
                                                        <td>{{$hase_history->comment}}</td>
                                                        <td>
                                                            @if ($hase_history->notify == 1)
                                                                YES
                                                            @else
                                                                NO
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
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
<br>
</section>
@endsection

{{-- page level scripts --}}
@section('footer_scripts')
<!-- begining of page level js -->
<script type="text/javascript" src="{{asset('assets/vendors/bootstrap-switch/js/bootstrap-switch.js')}}"></script>
<script src="{{asset('assets/vendors/airdatepicker/js/datepicker.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/vendors/airdatepicker/js/datepicker.en.js')}}" type="text/javascript"></script>
<script  type="text/javascript" src="{{asset('assets/vendors/timedropper/js/timedropper.js')}}" ></script>
<script type="text/javascript" src="{{asset('assets/vendors/select2/js/select2.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/js/custom_js/HaseReservationEdit.js')}}"></script>
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
<!-- end of page level js -->
@stop