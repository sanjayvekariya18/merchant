@extends('layouts/default')
{{-- Page title --}}
@section('title')
    Edit Order
    @parent
@stop

{{-- page level styles --}}
@section('header_styles')
    <!--page level css -->
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/dataTables.bootstrap.css')}}"/>

    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/bootstrap-switch/css/bootstrap-switch.css')}}"/>

    <link href="{{asset('assets/vendors/select2/css/select2-bootstrap.css')}}" rel="stylesheet" type="text/css">
    
    <!--end of page level css-->
@stop
@section('content')
<section class="content-header">
    <h1>Edit Orders</h1>
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
            Edit Order
        </li>
    </ol>
</section>
<section class="content">
    <form method = 'POST' action = '{!! url("hase_order")!!}/{!!$hase_order->order_id!!}/update'>

        <div class="row">
            <div class="col-md-12">
                <button type="submit" name="submitBtn" value="Save" class='btn btn-primary btn-inline'>Save</button>
                <button type="submit" name="submitBtn" value="SaveClose" class = 'btn btn-primary btn-inline'>Save  &amp; close</button>
                <a href="{!!url("hase_staff")!!}" class='btn btn-primary btn-inline'>
                    <i class="fa fa-fw fa-arrow-left"></i>
                </a>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-lg-12">
                <ul class="nav nav-tabs ">
                    <li class="active">
                        <a href="#tab1" data-toggle="tab">
                            Order
                        </a>
                    </li>
                    <li class="">
                        <a href="#tab2" data-toggle="tab">
                            Menu Items
                        </a>
                    </li>
                </ul>
                
                <input type = 'hidden' name='_token' value = '{{Session::token()}}'>
                <input id="order_id" name = "order_id" type="hidden" value="{!!$hase_order->order_id!!}">
                <div class="tab-content">
                    <div id="tab1" class="tab-pane wrap-all active in">
                        <div class="row">
                            <div class="col-xs-12 col-sm-4" style="margin-top: 15px;">
                                <div class="panel panel-primary">
                                    <div class="panel-heading">
                                        <h3 class="panel-title">Order Details</h3>
                                    </div>
                                    <div class="panel-body">
                                        <div class="form-group col-xs-12">
                                            <label class="control-label">Order ID</label>
                                            <div class="">
                                                #{!!$hase_order->order_id!!}
                                            </div>
                                        </div>
                                        <div class="form-group col-xs-12">
                                            <label class="control-label">Order Type</label>
                                            <div class="">
                                                {!!$hase_order->order_type!!}
                                            </div>
                                        </div>
                                        <div class="form-group col-xs-12">
                                            <label class="control-label">Delivery/Pick-up Time</label>
                                            <div class="">
                                                {!!$hase_order->order_time!!}
                                            </div>
                                        </div>
                                        <div class="form-group col-xs-12">
                                            <label class="control-label">Order Status</label>
                                            <div class="">
                                                {!!$hase_order->status_name!!}
                                            </div>
                                        </div>
                                        <div class="form-group col-xs-12">
                                            <label class="control-label">Payment Method</label>
                                            <div class="">
                                                {!!$hase_order->payment!!}
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
                                                {!!$hase_order->first_name!!} {!!$hase_order->last_name!!}
                                            </div>
                                        </div>
                                        <div class="form-group col-xs-12">
                                            <label class="control-label">Email</label>
                                            <div class="">
                                                {!!$hase_order->email!!}
                                            </div>
                                        </div>
                                        <div class="form-group col-xs-12">
                                            <label class="control-label">Telephone</label>
                                            <div class="">
                                                {!!$hase_order->telephone!!}
                                            </div>
                                        </div>
                                        <div class="form-group col-xs-12">
                                            <label class="control-label">IP Address</label>
                                            <div class="">
                                                {!!$hase_order->ip_address!!}
                                            </div>
                                        </div>
                                        <div class="form-group col-xs-12">
                                            <label class="control-label">User Agent</label>
                                            <div class="">
                                                {!!$hase_order->user_agent!!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xs-12 col-sm-4" style="margin-top: 15px;">
                                <div class="panel panel-primary">
                                    <div class="panel-heading">
                                        <h3 class="panel-title">Other Details</h3>
                                    </div>
                                    <div class="panel-body">                                    
                                        <div class="form-group col-xs-12">
                                            <label class="control-label">Invoice</label>
                                            <div class="">
                                                {!!$hase_order->invoice_prefix!!} {!!$hase_order->invoice_no!!}
                                            </div>
                                        </div>
                                        <div class="form-group col-xs-12">
                                            <label class="control-label">Order Total</label>
                                            <div class="">
                                                {!!$hase_order->order_total!!}
                                            </div>
                                        </div>
                                        <div class="form-group col-xs-12">
                                            <label class="control-label">Date Added</label>
                                            <div class="">
                                                {!!$hase_order->date_added!!}
                                            </div>
                                        </div>
                                        <div class="form-group col-xs-12">
                                            <label class="control-label">Date Modified</label>
                                            <div class="">
                                                {!!$hase_order->date_modified!!}
                                            </div>
                                        </div> 
                                        <div class="form-group col-xs-12">
                                            <label class="control-label">Customer Notified</label>
                                            <div class="">
                                                
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
                                        <h3 class="panel-title">Restaurant - {{$hase_locations->location_name}}</h3>
                                    </div>
                                    <div class="panel-body">
                                        <div class="form-group col-xs-12">
                                            <div class="">
                                                <span>
                                                    {{$hase_locations->location_address_1}},<br/>
                                                    {{$hase_locations->location_address_2}},<br/>
                                                    {{$hase_locations->city_name}},<br/>
                                                    {{$hase_locations->county_name}},<br/>
                                                    {{$hase_locations->location_postcode}},<br/>
                                                    {{$hase_locations->state_name}},<br/>
                                                    {{$hase_locations->country_name}}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        
                            <div class="col-xs-12 col-sm-6" style="margin-top: 10px;">
                                <div class="panel panel-primary">
                                    <div class="panel-heading">
                                        <h3 class="panel-title">Delivery Address</h3>
                                    </div>
                                    <div class="panel-body">
                                        <div class="form-group col-xs-12">
                                            <div class="">
                                                <span>
                                                    {{$address_data->address_1}},<br/>
                                                    {{$address_data->address_2}},<br/>
                                                    {{$address_data->city}},<br/>
                                                    {{$address_data->state}}<br/>
                                                    {{$address_data->postcode}},
                                                </span>
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
                                        <h3 class="panel-title">Order Comment</h3>
                                    </div>
                                    <div class="panel-body">
                                        <div class="form-group col-xs-12">
                                            <div class="">
                                                {!!$hase_order->comment!!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-xs-12" style="margin-top: 10px;">
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
                                                                @if ($hase_history['notify'] == 1)
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

                        <div class="row">
                            <div class="col-xs-12" style="margin-top: 10px;">
                                <div class="panel panel-primary">
                                    <div class="panel-heading">
                                        <h3 class="panel-title">Status & Assign</h3>
                                    </div>
                                    <div class="panel-body">
                                        <div class="col-xs-12 col-sm-3">
                                            <label class="control-label">Assign Staff</label>
                                            <input type="hidden" id="assignStaff" value="{!!$hase_order->assignee_id!!}"/>
                                            <select name="assignee_id" id="assignee_id" class="form-control">
                                                <option value="0"> - please select - </option>
                                                @foreach($hase_staffs as $hase_staff)
                                                    <?php if($hase_order->assignee_id == $hase_staff->staff_id): ?>
                                                        <option value="{{$hase_staff['staff_id']}}" selected>{{$hase_staff['staff_name']}}</option>
                                                    <?php else: ?>
                                                        <option value="{{$hase_staff['staff_id']}}">{{$hase_staff['staff_name']}}</option>
                                                    <?php endif; ?>
                                                @endforeach
                                            </select>
                                        </div>
                                        
                                        <div class="col-xs-12 col-sm-2">
                                            <label class="control-label">Order Status</label>
                                            <input type="hidden" id="orderStatus" value="{!!$hase_order->status_id!!}"/>
                                            <select name="status" id="status" class="form-control">
                                                @foreach($hase_statuses as $hase_status)
                                                    <option value="{{$hase_status->status_id}}" data-comment="{{$hase_status->status_comment}}">{{$hase_status->status_name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-xs-12 col-sm-5">
                                            <label class="control-label">Comment</label>
                                            <div class="">
                                                <textarea id="status_comment" name="status_comment" rows="3" class="form-control">
                                                    @foreach($hase_statuses as $hase_status)
                                                        @if ($hase_status->status_id == $hase_order->status)
                                                            {{$hase_status->status_comment}}
                                                        @endif
                                                    @endforeach
                                                </textarea>
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-sm-2">
                                            <label class="control-label">Notify Customer</label>
                                            <div class="make-switch col-sm-10" data-on="danger" data-off="default">
                                                <input type="checkbox" name="notify" id="notify" data-on-text="Yes" data-off-text="No" value="{!!$hase_order->notify!!}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="tab2" class="tab-pane wrap-all">
                        <div class="row">
                            <div class="panel panel-primary ">
                                <div class="panel-heading">
                                    <h4 class="panel-title">    
                                        <i class="fa fa-fw fa-users"></i> Orders Menu List
                                    </h4>
                                </div>
                                <div class="panel-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered" id="table">
                                            <thead>
                                                <tr>
                                                    <th>Id</th>
                                                    <th>Name</th>
                                                    <th>Price</th>
                                                    <th>Total</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php $recordNo = 0 ?>
                                                @foreach($hase_order_menu as $order_menu) 
                                                <?php $recordNo += 1 ?> 
                                                <tr>
                                                    <td><?= $recordNo ?></td>
                                                    <td>{!!$order_menu->name!!}</td>
                                                    <td>{!!$order_menu->price!!}</td>        
                                                    <td>{!!$order_menu->subtotal!!}</td>          
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
            <div>
        <div>
    </form>
<br>
</section>
@endsection

{{-- page level scripts --}}
@section('footer_scripts')
<!-- begining of page level js -->

<script type="text/javascript" src="{{asset('assets/vendors/bootstrap-switch/js/bootstrap-switch.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/js/custom_js/HaseOrderEdit.js')}}"></script>
<!-- end of page level js -->

<!-- end of page level js -->
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