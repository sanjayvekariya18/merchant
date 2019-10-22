@extends('layouts/default')

{{-- Page title --}}
@section('title')
    Trade Fee Schedule
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
    <h1>Trade Fee Schedule</h1>
    <ol class="breadcrumb">
        <li>
            <a href="index ">
                <i class="fa fa-fw fa-home"></i> Dashboard
            </a>
        </li>
        <li class="active">
            Trade Fee Schedule
        </li>
    </ol>
</section>
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <?php if(in_array("add", $permissions)): ?>
            <a href='{!!url("trade_fee_schedule")!!}/create' class='btn btn-primary btn-inline'>Create New Trade Fee Schedule
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
                        <i class="fa fa-fw fa-users"></i> Trade Fee Schedule List
                    </h4>
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="table">
                            
                            <thead>
                                <?php if(in_array("manage", $permissions) || in_array("delete", $permissions)):?>
                                <th>Actions</th>
                                <?php endif; ?>
                                @if(env('showID'))
                                <th>Trade Fee Schedule id</th>
                                @endif
                                <th>Exchange Name</th>
                                <th>Base Currency</th>
                                <th>QuoteCurrency</th>
                                <th>Volume currency</th>
                                <th>Trading Volume Lower</th>
                                <th>Trading Volume Upper</th>
                                <th>Trading Fees Taker</th>
                                <th>Trading Fees Maker</th>
                            </thead>
                            <tbody>
                                @foreach($trade_fee_schedules as $trade_fee_schedule) 
                                <tr>
                                    <?php if(in_array("manage", $permissions) || in_array("delete", $permissions)):?>
                                    <td>
                                        <?php if(in_array("manage", $permissions)): ?>
                                        <a href="{!!url('trade_fee_schedule')!!}/{!!$trade_fee_schedule->trading_schedule_id!!}/edit"><i class="fa fa-fw fa-pencil text-primary actions_icon" title="Edit Trade Fee Schedule"></i>
                                        </a>
                                        <?php endif; ?>

                                        <?php if(in_array("delete", $permissions)): ?>
                                        <a href="#" data-toggle="modal" data-target="#delete" data-link = "{!!url('trade_fee_schedule')!!}/{!!$trade_fee_schedule->trading_schedule_id!!}/delete"><i class="fa fa-fw fa-times text-danger actions_icon" title="Delete Trade Fee Schedule"></i></a>
                                        <?php endif; ?>
                                    </td>
                                    <?php endif; ?>
                                    @if(env('showID'))  
                                    <td>{!!$trade_fee_schedule->trading_schedule_id!!}</td>
                                    @endif
                                    <td>{!!$trade_fee_schedule->exchange_name!!}</td>
                                    <td>{!!$trade_fee_schedule->base_currency_name!!}</td>
                                    <td>{!!$trade_fee_schedule->quote_currency_name!!}</td>
                                    <td>{!!$trade_fee_schedule->volume_currency_name!!}</td>
                                    <td>{!!$trade_fee_schedule->trading_volume_lower!!}</td>
                                    <td>{!!$trade_fee_schedule->trading_volume_upper!!}</td>
                                    <td>{!!$trade_fee_schedule->trading_fees_taker!!}</td>
                                    <td>{!!$trade_fee_schedule->trading_fees_maker!!}</td>
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
                            <h4 class="modal-title custom_align" id="Heading">Delete Trade Fee Schedule</h4>
                        </div>
                        <div class="modal-body">
                            <div class="alert alert-warning">
                                <span class="glyphicon glyphicon-warning-sign"></span> Are you sure you want to delete this Trade Fee Schedule?
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