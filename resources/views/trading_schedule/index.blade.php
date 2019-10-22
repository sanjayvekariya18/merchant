@extends('layouts/default')

{{-- Page title --}}
@section('title')
    Trading schedule
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
    <h1>Trading_schedule</h1>
    <ol class="breadcrumb">
        <li>
            <a href="index ">
                <i class="fa fa-fw fa-home"></i> Dashboard
            </a>
        </li>
        <li class="active">
            Trading schedule
        </li>
    </ol>
</section>
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <a href='{!!url("trading_schedule")!!}/create' class='btn btn-primary btn-inline'>Create New Trading schedule
            </a>
        </div>
    </div>
    <br>
    <section class="content p-l-r-15">
        <div class="row">
            <div class="panel panel-primary ">
                <div class="panel-heading">
                    <h4 class="panel-title">    
                        <i class="fa fa-fw fa-users"></i> Trading schedule List
                    </h4>
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="table">
                            
                            <thead>
                                <th>Trading Schedule id</th>
                                <th>Exchange Name</th>
                                <th>Base Currency</th>
                                <th>QuoteCurrency</th>
                                <th>Volume currency</th>
                                <th>Trading Volume Lower</th>
                                <th>Trading Volume_Upper</th>
                                <th>Trading Fees Taker</th>
                                <th>Trading Fees Maker</th>
                                <th>Actions</th>
                            </thead>
                            <tbody>
                                @foreach($trading_schedules as $trading_schedule) 
                                <tr>
                                    <td>{!!$trading_schedule->trading_schedule_id!!}</td>
                                    <td>{!!$trading_schedule->exchange_name!!}</td>
                                    <td>{!!$trading_schedule->base_currency_name!!}</td>
                                    <td>{!!$trading_schedule->quote_currency_name!!}</td>
                                    <td>{!!$trading_schedule->volume_currency_name!!}</td>
                                    <td>{!!$trading_schedule->trading_volume_lower!!}</td>
                                    <td>{!!$trading_schedule->trading_volume_upper!!}</td>
                                    <td>{!!$trading_schedule->trading_fees_taker!!}</td>
                                    <td>{!!$trading_schedule->trading_fees_maker!!}</td>
                                    <td>
                                        <a href="{!!url('trading_schedule')!!}/{!!$trading_schedule->trading_schedule_id!!}/edit"><i class="fa fa-fw fa-pencil text-primary actions_icon" title="Edit Exchange"></i>
                                        </a>

                                        <a href="#" data-toggle="modal" data-target="#delete" data-link = "{!!url('trading_schedule')!!}/{!!$trading_schedule->trading_schedule_id!!}/delete"><i class="fa fa-fw fa-times text-danger actions_icon" title="Delete Exchange"></i></a>
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
                            <h4 class="modal-title custom_align" id="Heading">Delete Trading Schedule</h4>
                        </div>
                        <div class="modal-body">
                            <div class="alert alert-warning">
                                <span class="glyphicon glyphicon-warning-sign"></span> Are you sure you want to delete this Trading Schedule?
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