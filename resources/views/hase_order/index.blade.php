@extends('layouts/default')

{{-- Page title --}}
@section('title')
    Orders
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
    <h1>Orders</h1>
    <ol class="breadcrumb">
        <li>
            <a href="index ">
                <i class="fa fa-fw fa-home"></i> Dashboard
            </a>
        </li>
        <li>
            <a href="#"> Sales</a>
        </li>
        <li class="active">
            Orders
        </li>
    </ol>
</section>
<section class="content">
    <section class="content p-l-r-15">
        <div class="row">
            <div class="panel panel-primary ">
                <div class="panel-heading">
                    <h4 class="panel-title">    
                        <i class="fa fa-fw fa-users"></i> Orders List
                    </h4>
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="table">
                            <thead>
                                <tr class="filters">
                                    <th>Id</th>
                                    <th>Location</th>
                                    <th>Customer Name</th>
                                    <th>Status</th>
                                    <th>Type</th>
                                    <th>Payment</th>
                                    <th>Total</th>
                                    <th>Time-Date</th>
                                    <th>actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($hase_orders as $hase_order) 
                                <tr>
                                    <td>{!!$hase_order->order_id!!}</td>
                                    <td>{!!$hase_order->location_name!!}</td>
                                    <td>{!!$hase_order->first_name!!} {!!$hase_order->last_name!!}</td>
                                    <td>{!!$hase_order->status_name!!}</td>
                                    <td>{!!$hase_order->order_type!!}</td>
                                    <td>{!!$hase_order->payment!!}</td>
                                    <td>{!!$hase_order->order_total!!}</td>
                                    <td>{!!$hase_order->order_time!!} {!!$hase_order->order_date!!}</td>
                                    <td>
                                        <a href="{!!url('hase_order')!!}/{!!$hase_order->order_id!!}/edit ">
                                            <i class="fa fa-fw fa-pencil text-primary actions_icon" title="Edit
                                            Order"></i>
                                        </a>

                                        <a href="#" data-toggle="modal" data-target="#delete" data-link = "{!!url('hase_order')!!}/{!!$hase_order->order_id!!}/delete">
                                            <i class="fa fa-fw fa-times text-danger actions_icon" title="Delete Order"></i>
                                        </a>
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
                            <h4 class="modal-title custom_align" id="Heading">Delete Order</h4>
                        </div>
                        <div class="modal-body">
                            <div class="alert alert-warning">
                                <span class="glyphicon glyphicon-warning-sign"></span> Are you sure you want to delete this Order?
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