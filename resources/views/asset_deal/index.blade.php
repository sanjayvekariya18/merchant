@extends('layouts/default')

{{-- Page title --}}
@section('title')
    Asset Deal
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
    <h1>Asset Deal</h1>
    <ol class="breadcrumb">
        <li>
            <a href="index ">
                <i class="fa fa-fw fa-home"></i> Dashboard
            </a>
        </li>
        <li class="active">
            Asset Deal
        </li>
    </ol>
</section>
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <?php if(in_array("add", $permissions)): ?>
            <a href='{!!url("asset_deal")!!}/create' class='btn btn-primary btn-inline'>Create New Asset Deal
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
                        <i class="fa fa-fw fa-users"></i> Asset Deal List
                    </h4>
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="table">
                            
                            <thead>
                                <?php if(in_array("manage", $permissions) || in_array("delete", $permissions)):?>
                                <th>Actions</th>
                                <?php endif; ?>
                                <th>Asset Deal Id</th>
                                <th>Trader Name</th>
                                <th>Account Name</th>
                                <th>Transaction Id</th>
                                <th>Quantity</th>
                                <th>Side Type Name</th>
                                <th>Asset Quote Name</th>
                                <th>Entry Timezone</th>
                                <th>Entry Date</th>
                                <th>Entry Time</th>
                                <th>Price Index</th>
                                <th>Price Quote</th>
                                <th>Price Fee</th>
                                <th>Price Fee Rate</th>
                                <th>Counter Party Name</th>
                                <th>Asset Base Id</th>
                                <th>Asset Base Quote</th>
                                <th>Asset Base Rate</th>
                                <th>Account Uuid</th>
                                <th>Status Operations Type</th>
                                <th>Status Fiat Type</th>
                                <th>Status Crypto Type</th>
                            </thead>
                            <tbody>
                            @foreach($asset_deals as $asset_deal) 
                            <tr>
                                <?php if(in_array("manage", $permissions) || in_array("delete", $permissions)):?>
                                <td>
                                    <?php if(in_array("manage", $permissions)): ?>
                                    <a href="{!!url('asset_deal')!!}/{!!$asset_deal->deal_id!!}/edit"><i class="fa fa-fw fa-pencil text-primary actions_icon" title="Edit Asset Deal"></i>
                                    </a>
                                    <?php endif; ?>

                                    <?php if(in_array("delete", $permissions)): ?>
                                    <a href="#" data-toggle="modal" data-target="#delete" data-link = "{!!url('asset_deal')!!}/{!!$asset_deal->deal_id!!}/delete"><i class="fa fa-fw fa-times text-danger actions_icon" title="Delete Asset Deal"></i></a>
                                    <?php endif; ?>
                                </td>
                                <?php endif; ?>
                                <td>{!!$asset_deal->deal_id!!}</td>
                                <td>{!!$asset_deal->staff_name!!}</td>
                                <td>{!!$asset_deal->account_code_long!!}</td>
                                <td>{!!$asset_deal->transaction_id!!}</td>
                                <td>{!!$asset_deal->quantity!!}</td>
                                <td>{!!$asset_deal->side_type_name!!}</td>
                                <td>{!!$asset_deal->asset_quote_name!!}</td>
                                <td>{!!$asset_deal->timezone_name!!}</td>
                                <td>
                                    <?php
                                    if($asset_deal->entry_date != 0) {
                                        echo substr_replace(substr_replace($asset_deal->entry_date, '-', 4, 0), '-', 7, 0);
                                    }
                                    ?>
                                </td>
                                <td>
                                    <?php
                                        $reserveMinutes = $asset_deal->entry_time/60;
                                        $reserveHour = sprintf("%02d", floor($reserveMinutes/60));
                                        $reserveMinute = sprintf("%02d", ($reserveMinutes % 60));
                                        echo $reserveHour.':'.$reserveMinute;
                                    ?>
                                </td>
                                <td>{!!$asset_deal->price_index!!}</td>
                                <td>{!!$asset_deal->price_quote!!}</td>
                                <td>{!!$asset_deal->price_fee!!}</td>
                                <td>{!!$asset_deal->price_fee_rate!!}</td>
                                <td>{!!$asset_deal->merchant_name!!}</td>
                                <td>{!!$asset_deal->asset_base_name!!}</td>
                                <td>{!!$asset_deal->asset_base_quote!!}</td>
                                <td>{!!$asset_deal->asset_base_rate!!}</td>
                                <td>{!!$asset_deal->account_uuid!!}</td>
                                <td>{!!$asset_deal->type_name!!}</td>
                                <td>{!!$asset_deal->status_fiat_type_name!!}</td>
                                <td>{!!$asset_deal->status_crypto_type_name!!}</td>
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
                            <h4 class="modal-title custom_align" id="Heading">Delete Asset Deal</h4>
                        </div>
                        <div class="modal-body">
                            <div class="alert alert-warning">
                                <span class="glyphicon glyphicon-warning-sign"></span> Are you sure you want to delete this Asset Deal?
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