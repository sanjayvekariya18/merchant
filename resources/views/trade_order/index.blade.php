@extends('layouts/default')

{{-- Page title --}}
@section('title')
    Trade Order
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
    <h1>Trade Order</h1>
    <ol class="breadcrumb">
        <li>
            <a href="index ">
                <i class="fa fa-fw fa-home"></i> Dashboard
            </a>
        </li>
        <li class="active">
            Trade Order
        </li>
    </ol>
</section>
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <?php if(in_array("add", $permissions)): ?>
            <a href='{!!url("trade_order")!!}/create' class='btn btn-primary btn-inline'>Create New Trade Order
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
                        <i class="fa fa-fw fa-users"></i> Trade Order
                    </h4>
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="table">
                            <thead>
                                <?php if(in_array("manage", $permissions) || in_array("delete", $permissions)):?>
                                <th>Actions</th>
                                <?php endif; ?>
                                <th>Order Id</th>
                                <th>Merchant Name</th>
                                <th>Location Name</th>
                                <th>Group Name</th>
                                <th>Staff Name</th>
                                <th>Staff Account Name</th>
                                <th>Customer Name</th>
                                <th>Customer Account Name</th>
                                <th>Exchange Name</th>
                                <th>Transaction internal</th>
                                <th>Side Type Name</th>
                                <th>Asset From </th>
                                <th>Asset From Price</th>
                                <th>Asset From Quantity</th>
                                <th>Asset Into </th>
                                <th>Asset Into Price</th>
                                <th>Asset Into Quantity</th>
                                <th>Order Type Name</th>
                                <th>Leverage</th>
                                <th>Start Timezone</th>
                                <th>Start Date</th>
                                <th>Start Time</th>
                                <th>Expire Timezone</th>
                                <th>Expire Date</th>
                                <th>Expire Time</th>
                                <th>Fee Amount</th>
                                <th>Fee Asset</th>
                                <th>Fee Referrer</th>
                                <th>Status Operation</th>
                                <th>Status Fiat</th>
                                <th>Status Crypto</th>
                            </thead>
                            <tbody>
                                @foreach($trade_orders as $trade_order) 
                                <tr>
                                    <?php if(in_array("manage", $permissions) || in_array("delete", $permissions)):?>
                                    <td>
                                        <?php if(in_array("manage", $permissions)): ?>
                                        <a href="{!!url('trade_order')!!}/{!!$trade_order->order_id!!}/edit"><i class="fa fa-fw fa-pencil text-primary actions_icon" title="Edit Trade Order"></i>
                                        </a>
                                        <?php endif; ?>

                                        <?php if(in_array("delete", $permissions)): ?>
                                        <a href="#" data-toggle="modal" data-target="#delete" data-link = "{!!url('trade_order')!!}/{!!$trade_order->order_id!!}/delete"><i class="fa fa-fw fa-times text-danger actions_icon" title="Delete Trade Order"></i></a>
                                        <?php endif; ?>
                                    </td>
                                    <?php endif; ?>
                                    <td>{!!$trade_order->order_id!!}</td>
                                    <td>{!!$trade_order->merchant_name!!}</td>
                                    <td>{!!$trade_order->city_name!!}</td>
                                    <td>{!!$trade_order->staff_group_name!!}</td>
                                    <td>{!!$trade_order->staff_name!!}</td>
                                    <td>{!!$trade_order->staff_account_code_long!!}</td>
                                    <td>{!!$trade_order->customer_name!!}</td>
                                    <td>{!!$trade_order->customer_account_code_long!!}</td>
                                    <td>{!!$trade_order->exchange_name!!}</td>
                                    <td>{!!$trade_order->transaction_internal!!}</td>
                                    <td>{!!$trade_order->side_type_name!!}</td>
                                    <td>{!!$trade_order->asset_from_name!!}</td>
                                    <td>{!!$trade_order->asset_from_price!!}</td>
                                    <td>{!!$trade_order->asset_from_quantity!!}</td>
                                    <td>{!!$trade_order->asset_into_name!!}</td>
                                    <td>{!!$trade_order->asset_into_price!!}</td>
                                    <td>{!!$trade_order->asset_into_quantity!!}</td>
                                    <td>{!!$trade_order->order_type_name!!}</td>
                                    <td>{!!$trade_order->leverage!!}</td>
                                    <td>{!!$trade_order->start_timezone_name!!}</td>
                                    <td>
                                    <?php
                                    if($trade_order->start_date != 0) {
                                        echo substr_replace(substr_replace($trade_order->start_date, '-', 4, 0), '-', 7, 0);
                                    }
                                    ?>
                                    </td>
                                    <td>
                                        <?php
                                            $startMinutes = $trade_order->start_time/60;
                                            $startHour = sprintf("%02d", floor($startMinutes/60));
                                            $startMinute = sprintf("%02d", ($startMinutes % 60));
                                            echo $startHour.':'.$startMinute;
                                        ?>
                                    </td>
                                    <td>{!!$trade_order->expire_timezone_name!!}</td>
                                    <td>
                                    <?php
                                    if($trade_order->expire_date != 0) {
                                        echo substr_replace(substr_replace($trade_order->expire_date, '-', 4, 0), '-', 7, 0);
                                    }
                                    ?>
                                    </td>
                                    <td>
                                        <?php
                                            $expireMinutes = $trade_order->expire_time/60;
                                            $expireHour = sprintf("%02d", floor($expireMinutes/60));
                                            $expireMinute = sprintf("%02d", ($expireMinutes % 60));
                                            echo $expireHour.':'.$expireMinute;
                                        ?>
                                    </td>
                                    <td>{!!$trade_order->fee_amount!!}</td>
                                    <td>{!!$trade_order->fee_asset_name!!}</td>
                                    <td>{!!$trade_order->fee_referrer_account_code_long!!}</td>
                                    <td>{!!$trade_order->type_name!!}</td>
                                    <td>{!!$trade_order->status_fiat_type_name!!}</td>
                                    <td>{!!$trade_order->status_crypto_type_name!!}</td>
                                    
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
                            <h4 class="modal-title custom_align" id="Heading">Delete Trade Order</h4>
                        </div>
                        <div class="modal-body">
                            <div class="alert alert-warning">
                                <span class="glyphicon glyphicon-warning-sign"></span> Are you sure you want to delete this Trade Order?
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