@extends('layouts/default')
{{-- Page title --}}
@section('title')
    Hase Reservation
    @parent
@stop

{{-- page level styles --}}
@section('header_styles')
    <!--page level css -->
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/dataTables.bootstrap.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/advbuttons.css')}}">
    <!--end of page level css-->
@stop
@section('content')

<style type="text/css">
.btn-group-toggle .btn:not(.active) {
    background-color: inherit;
    border-color: #D2DCE7;
    color: inherit;
    text-shadow: 1px 1px 0px #F5F5F5;
    box-shadow: 0px 1px 1px rgba(90, 90, 90, 0.1);
    background-clip: border-box;
}
</style>

<section class="content-header">
    <h1>Reservations</h1>
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
            Reservations
        </li>
    </ol>
</section>
<section class="content">
    <div class="row">
        <div class="col-sm-7">
            <div class="btn-group btn-group-toggle btn-group-4" data-toggle="buttons">
                <label class="btn btn-success active"><input type="radio" name="reservation_status" value="" checked="checked">All</label>
                <label class="btn btn-success"><input type="radio" name="reservation_status" value="Confirmed">Accepted</label>
                <label class="btn btn-success"><input type="radio" name="reservation_status" value="Pending">Pending</label>
                <label class="btn btn-success"><input type="radio" name="reservation_status" value="Cancelled By Staff">Cancelled By Staff</label>
                <label class="btn btn-success"><input type="radio" name="reservation_status" value="Cancelled By User">Cancelled By User</label>
            </div>
        </div>
    </div>
    <br>
    <?php if(in_array("add", $permissions)): ?>
        <form class = 'col s3' method = 'get' action = '{!!url("hase_reservation")!!}/create'>
            <button class='btn btn-primary' type='submit'>Create New Reservation</button>
        </form>
    <?php endif; ?>
    <br>
    <section class="content p-l-r-15">
        <form id='reservationAcceptReject' method = 'POST' action ='#'>
        <input type = 'hidden' name = '_token' value = '{{Session::token()}}'>
        <div style="display: none;" id="example-console"></div>
            <div class="row">
                <div class="panel panel-primary ">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <i class="fa fa-fw fa-square-o"></i> Reservation List
                        </h4>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-sm-12">
                                <a href='#' data-toggle="modal" data-target="#massAccept" data-link = '{!!url("hase_reservation")!!}/accept'><button class='btn btn-success'><span class="glyphicon glyphicon-ok"></span>Accept</button></a>

                                <a href='#' data-toggle="modal" data-target="#massReject" data-link = '{!!url("hase_reservation")!!}/reject'><button class='btn btn-danger'><span class="glyphicon glyphicon-remove"></span>Reject</button></a>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class = "table table-bordered" id="reservationTable">
                                <thead>
                                    <?php //if(session('merchantId') == 0): ?>
                                        <th class="hidden">Reservation Status ID</th>
                                    <?php //endif; ?>
                                    <th><input name="select_all" value="1" type="checkbox">Select All</th>
                                    <th>ID</th>
                                    <?php if(in_array("manage", $permissions) || in_array("delete", $permissions) || in_array("access", $permissions)):?>
                                        <th>Actions</th>
                                    <?php endif; ?>
                                    <th>Date</th>
                                    <th>Time</th>
                                    <th>Location</th>
                                    <th>Customer Name</th>
                                    <th>People</th>
                                    <th>Table</th>
                                    <th>Phone num</th>
                                    <th>Reference</th>
                                    <th>Staff</th>
                                    <th>Reminder</th>
                                    <th>Status</th>
                                </thead>
                                <tbody>
                                    @foreach($hase_reservations_data as $hase_reservation) 
                                    <tr>
                                        <?php //if(session('merchantId') == 0): ?>
                                            <td class="hidden">{!!$hase_reservation->status_name!!}</td>
                                        <?php //endif; ?>
                                        <td><input type="checkbox" name="checked_reservation[]" value="{!!$hase_reservation->reservation_id!!}"></td>
                                        
                                        <td>{!!$hase_reservation->reservation_id!!}</td>
                                        <?php if(in_array("manage", $permissions) || in_array("delete", $permissions) || in_array("access", $permissions)):?>
                                            <td width="19%">
                                                <div class="ui-group-buttons">
                                                    @if($hase_reservation->reservation_status != 6)
                                                    <a href='{!! url("hase_reservation")!!}/{!!$hase_reservation->reservation_id!!}/acceptReject/6' class="btn btn-success btn-sm btn-responsive" role="button" style="padding-right: 8px;">
                                                        <span class="glyphicon glyphicon-ok"></span> Accept
                                                    </a>
                                                    @endif
                                                    @if($hase_reservation->reservation_status != 7)
                                                    <a href='{!! url("hase_reservation")!!}/{!!$hase_reservation->reservation_id!!}/acceptReject/7' class="btn btn-danger btn-sm btn-responsive" style="margin-left: 4px;" role="button" style="padding-left: 8px; margin-left: 3px;">
                                                        <span class="glyphicon glyphicon-remove" style="margin-left: -6px;"></span> Reject
                                                    </a>
                                                    @endif
                                                </div>
                                                <?php if(in_array("manage", $permissions)): ?>
                                                    <a href = '{!!url("hase_reservation")!!}/{!!$hase_reservation->reservation_id!!}/edit'><i class = 'fa fa-fw fa-pencil text-primary actions_icon' title="Edit Reservation"></i></a>
                                                <?php endif; ?>
                                                <!-- <a href='#' data-toggle="modal" data-target="#delete" data-link = '{!!url("hase_reservation")!!}/{!!$hase_reservation->reservation_id!!}/delete'><i class = 'fa fa-fw fa-times text-danger actions_icon' title="Delete Reservation"></i></a> -->
                                                <?php if(in_array("access", $permissions)): ?>
                                                    <a href = '{!!url("hase_reservation")!!}/{!!$hase_reservation->reservation_id!!}'><i class = 'fa fa-file-text-o fa-2x text-success actions_icon'  title="View Reservation"></i></a>
                                                <?php endif; ?>
                                            </td>
                                        <?php endif; ?>
                                        <td>
                                            <?php
                                            if($hase_reservation->reserve_date != 0) {
                                                echo substr_replace(substr_replace($hase_reservation->reserve_date, '-', 4, 0), '-', 7, 0);
                                            }
                                            ?>
                                        </td>
                                        <td>
                                        <?php
                                            $reserveMinutes = $hase_reservation->reserve_time/60;
                                            $reserveHour = sprintf("%02d", floor($reserveMinutes/60));
                                            $reserveMinute = sprintf("%02d", ($reserveMinutes % 60));
                                            echo $reserveHour.':'.$reserveMinute;
                                        ?>
                                        </td>
                                        <td>{!!$hase_reservation->location_name!!}</td>
                                        <td>{!!$hase_reservation->customer_name!!}</td>
                                        <td>{!!$hase_reservation->guest_num!!}</td>
                                        <td>{!!$hase_reservation->seating_name!!}</td>
                                        <td>{!!$hase_reservation->telephone!!}</td>
                                        <td>{!!$hase_reservation->reservation_id!!}</td>
                                        <td>
                                            @if($hase_reservation->staff_name)
                                                {!!$hase_reservation->staff_name!!}
                                            @else
                                                None
                                            @endif
                                        </td>
                                        <td>
                                            @if ($hase_reservation->notify == 1)
                                                YES
                                            @else
                                                NO
                                            @endif
                                        <td>{!!$hase_reservation->status_name!!}</td>
                                    </tr>
                                    @endforeach 
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>    
                <!-- /.modal-dialog -->
                <div class="modal fade" id="delete" tabindex="-1" role="dialog" aria-labelledby="Heading" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                <h4 class="modal-title custom_align" id="Heading">Delete Reservation</h4>
                            </div>
                            <div class="modal-body">
                                <div class="alert alert-warning">
                                    <span class="glyphicon glyphicon-warning-sign"></span> Are you sure you want to
                                    delete this Reservation ?
                                </div>
                            </div>
                            <div class="modal-footer ">
                                <a href="#" class="btn btn-danger">
                                    <span class="glyphicon glyphicon-ok-sign"></span> Yes
                                </a>
                                <button type="button" class="btn btn-success" data-dismiss="modal">
                                    <span class="glyphicon glyphicon-remove"></span> No
                                </button>
                            </div>
                        </div>
                        <!-- /.modal-content -->
                    </div>
                </div>
                <!-- /.modal-dialog -->
                <!-- /.modal-dialog -->
                <div class="modal fade" id="massAccept" tabindex="-1" role="dialog" aria-labelledby="Heading" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                <h4 class="modal-title custom_align" id="Heading">Accept Reservations</h4>
                            </div>
                            <div class="modal-body">
                                <div class="alert alert-warning">
                                    <span class="glyphicon glyphicon-warning-sign"></span> Are you sure you want to
                                    accept this Reservations ?
                                </div>
                            </div>
                            <div class="modal-footer ">
                                <!-- <a href="#" class="btn btn-success">
                                    <span class="glyphicon glyphicon-ok-sign"></span> <button type="submit" name="submitbutton" value="Save" class='btn btn-primary btn-inline'>Yes</button>
                                </a> -->
                                <button type="submit" class="btn btn-success">
                                    <span class="glyphicon glyphicon-remove"></span> Yes
                                </button>
                                <button type="button" class="btn btn-danger" data-dismiss="modal">
                                    <span class="glyphicon glyphicon-remove"></span> No
                                </button>
                            </div>
                        </div>
                        <!-- /.modal-content -->
                    </div>
                </div>
                <!-- /.modal-dialog -->
                <!-- /.modal-dialog -->
                <div class="modal fade" id="massReject" tabindex="-1" role="dialog" aria-labelledby="Heading" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                <h4 class="modal-title custom_align" id="Heading">Reject Reservations</h4>
                            </div>
                            <div class="modal-body">
                                <div class="alert alert-warning">
                                    <span class="glyphicon glyphicon-warning-sign"></span> Are you sure you want to
                                    Reject this Reservations ?
                                </div>
                            </div>
                            <div class="modal-footer ">
                                <!-- <a href="#" class="btn btn-success">
                                    <span class="glyphicon glyphicon-ok-sign"></span> <button type="submit" name="submitbutton" value="Save" class='btn btn-primary btn-inline'>Yes</button>
                                </a> -->
                                <button type="submit" class="btn btn-danger">
                                    <span class="glyphicon glyphicon-remove"></span> Yes
                                </button>
                                <button type="button" class="btn btn-success" data-dismiss="modal">
                                    <span class="glyphicon glyphicon-remove"></span> No
                                </button>
                            </div>
                        </div>
                        <!-- /.modal-content -->
                    </div>
                </div>
                <!-- /.modal-dialog -->
            </div>
        </form>
        <!-- row-->  
        @include('layouts.right_sidebar')
    </section>
</section>
@endsection

{{-- page level scripts --}}
@section('footer_scripts')
<!-- begining of page level js -->
<script type="text/javascript" src="{{asset('assets/js/custom_js/HaseReservation.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/datatables/js/jquery.dataTables.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/datatables/js/dataTables.bootstrap.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/bootstrap-switch/js/bootstrap-switch.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/js/custom_js/users_custom.js')}}"></script>
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