@extends('layouts/default')

{{-- Page title --}}
@section('title')
    Trade Limits
    @parent
@stop

{{-- page level styles --}}
@section('header_styles')
    <!--page level css -->
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/dataTables.bootstrap.css')}}"/>
    <!--end of page level css-->
@stop
@section('content')
<section class="content-header">
    <h1>Trade Limits</h1>
    <ol class="breadcrumb">
        <li>
            <a href="index ">
                <i class="fa fa-fw fa-home"></i> Dashboard
            </a>
        </li>
        <li class="active">
            Trade Limits
        </li>
    </ol>
</section>
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <?php if(in_array("add", $permissions)): ?>
            <a href='{!!url("trade_limits")!!}/create' class='btn btn-primary btn-inline'>Create New Trade Limits
            </a>
            <?php endif; ?>
        </div>
    </div>
    <br>
    <section class="content p-l-r-15">
        <div class="row">
            <div class="panel panel-primary ">
                <div class="panel-heading">
                    <h4 class="panel-title">    
                        <i class="fa fa-fw fa-users"></i> Trade Limits
                    </h4>
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="table">
                            <thead>
                                <?php if(in_array("manage", $permissions) || in_array("delete", $permissions)):?>
                                <th>Actions</th>
                                <?php endif; ?>
                                <th>Limits Id</th>
                                <th>Merchant Name</th>
                                <th>Location Name</th>
                                <th>Group Name</th>
                                <th>Staff Name</th>
                                <th>Staff Account Name</th>
                                <th>Customer Name</th>
                                <th>Customer Account Name</th>
                                <th>Asset Name </th>
                                <th>Timezone</th>
                                <th>Start Day</th>
                                <th>End Day</th>
                                <th>Start Time</th>
                                <th>End Time</th>
                                <th>Price Offset</th>
                                <th>Maximum Quantity</th>
                                <th>Priority</th>
                                <th>Status</th>
                            </thead>
                            <tbody>
                                @foreach($trade_limits as $trade_limit) 
                                <tr>
                                    <?php if(in_array("manage", $permissions) || in_array("delete", $permissions)):?>
                                    <td>
                                        <?php if(in_array("manage", $permissions)): ?>
                                        <a href="{!!url('trade_limits')!!}/{!!$trade_limit->limits_id!!}/edit"><i class="fa fa-fw fa-pencil text-primary actions_icon" title="Edit Trade Limits"></i>
                                        </a>
                                        <?php endif; ?>

                                        <?php if(in_array("delete", $permissions)): ?>
                                        <a href="#" data-toggle="modal" data-target="#delete" data-link = "{!!url('trade_limits')!!}/{!!$trade_limit->limits_id!!}/delete"><i class="fa fa-fw fa-times text-danger actions_icon" title="Delete Trade Limits"></i></a>
                                        <?php endif; ?>
                                    </td>
                                    <?php endif; ?>
                                    <td>{!!$trade_limit->limits_id!!}</td>
                                    <td>{!!$trade_limit->merchant_name!!}</td>
                                    <td>{!!$trade_limit->location_name!!}</td>
                                    <td>{!!$trade_limit->group_name!!}</td>
                                    <td>{!!$trade_limit->staff_name!!}</td>
                                    <td>{!!$trade_limit->staff_account_code_long!!}</td>
                                    <td>{!!$trade_limit->customer_name!!}</td>
                                    <td>{!!$trade_limit->customer_account_code_long!!}</td>
                                    <td>{!!$trade_limit->asset_name!!}</td>
                                    <td>{!!$trade_limit->timezone_name!!}</td>
                                    <td>{!!$trade_limit->day_start!!}</td>
                                    <td>{!!$trade_limit->day_end!!}</td>
                                    <td>
                                        <?php
                                            $startMinutes = $trade_limit->time_start/60;
                                            $startHour = sprintf("%02d", floor($startMinutes/60));
                                            $startMinute = sprintf("%02d", ($startMinutes % 60));
                                            echo $startHour.':'.$startMinute;
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                            $endMinutes = $trade_limit->time_end/60;
                                            $endHour = sprintf("%02d", floor($endMinutes/60));
                                            $endMinute = sprintf("%02d", ($endMinutes % 60));
                                            echo $endHour.':'.$endMinute;
                                        ?>
                                    </td>
                                    <td>{!!$trade_limit->price_pct_offset!!}</td>
                                    <td>{!!$trade_limit->quantity_maximum!!}</td>
                                    <td>{!!$trade_limit->priority!!}</td>
                                    <td>
                                         @if($trade_limit->status == 1)
                                            <span class="btn-success btn-xs">Enable</span>
                                        @else
                                            <span class="btn-danger btn-xs">Disable</span>
                                        @endif
                                    </td> 
                                    
                                </tr>
                                @endforeach 
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="delete" tabindex="-1" role="dialog" aria-labelledby="Heading" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                            <h4 class="modal-title custom_align" id="Heading">Delete Trade Limits</h4>
                        </div>
                        <div class="modal-body">
                            <div class="alert alert-warning">
                                <span class="glyphicon glyphicon-warning-sign"></span> Are you sure you want to delete this Trade Limits?
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
        </div>
    </section>    
</section>
@endsection

{{-- page level scripts --}}
@section('footer_scripts')

<!-- begining of page level js -->
<script type="text/javascript" src="{{asset('assets/vendors/datatables/js/jquery.dataTables.js')}}"></script>

<script type="text/javascript" src="{{asset('assets/vendors/datatables/js/dataTables.bootstrap.js')}}"></script>

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
@stop