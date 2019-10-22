@extends('layouts/default')

{{-- Page title --}}
@section('title')
    Merchants
    @parent
@stop

{{-- page level styles --}}
@section('header_styles')
    <!--page level css -->
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/dataTables.bootstrap.css')}}"/>

    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/bootstrap-switch/css/bootstrap-switch.css')}}"/>
    <!--end of page level css-->
@stop
@section('content')
<section class="content-header">
    <h1>Merchants</h1>
    <ol class="breadcrumb">
        <li>
            <a href="index ">
                <i class="fa fa-fw fa-home"></i> Dashboard
            </a>
        </li>
        <li>
            <a href="#"> Merchants </a>
        </li>
        <li class="active">
            Merchants
        </li>
    </ol>
</section>
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <?php if($permissions['Add']): ?>
                <a href='{!!url("hase_merchant")!!}/create' class='btn btn-primary btn-inline'>     Create New Merchant
                </a>
            <?php endif; ?>
            <?php if(session('merchantId') == 0): ?>
                <input id="toggle" type="checkbox" data-on-text="Restaurant" data-off-text="Shop" checked data-on-color="success" data-off-color="success" class="btn-inline">
                <!-- <button value="0" id="Restaurant" class='btn btn-default btn-inline'>Restaurant</button>
                <button value="0" id="Shop" class='btn btn-default btn-inline'>Shop</button> -->
            <?php endif; ?>
        </div>
    </div>
    <br>
    <section class="content p-l-r-15">
        <div class="row">
            <div class="panel panel-primary ">
                <div class="panel-heading">
                    <h4 class="panel-title">    
                        <i class="fa fa-fw fa-users"></i> Merchant List
                    </h4>
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="merchantTable">
                            <thead>
                                <tr class="filters">
                                    <th class="hidden">Merchant Type</th>
                                    <th>Id</th>
                                    <th>Merchant Name</th>
                                    <?php if($permissions['Manage'] || $permissions['Delete']):?>
                                        <th>actions</th>
                                    <?php endif; ?>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($hase_merchant as $hase_merchant) 
                                <tr>
                                    <td class="hidden">{!!$hase_merchant->type_name!!}</td>
                                    <td>{!!$hase_merchant->merchant_id!!}</td>
                                    <td>{!!$hase_merchant->merchant_name!!}</td>
                                    <?php if($permissions['Manage'] || $permissions['Delete']):?>
                                        <td>
                                            <?php if($permissions['Manage']): ?>
                                                <a href="{!!url('hase_merchant')!!}/{!!$hase_merchant->merchant_id!!}/edit ">
                                                    <i class="fa fa-fw fa-pencil text-primary actions_icon" title="Edit Category"></i>
                                                    
                                                </a>
                                            <?php endif; ?>
                                            <?php //if($permissions['Delete']): ?>
                                                <!-- <a href="#" data-toggle="modal" data-target="#delete" data-link = "{!!url('hase_merchant')!!}/{!!$hase_merchant->merchant_id!!}/delete">
                                                    <i class="fa fa-fw fa-times text-danger actions_icon" title="Delete Merchant"></i>
                                                </a> -->
                                            <?php //endif; ?>
                                        </td>
                                    <?php endif; ?>
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
                            <h4 class="modal-title custom_align" id="Heading">Delete Merchant</h4>
                        </div>
                        <div class="modal-body">
                            <div class="alert alert-warning">
                                <span class="glyphicon glyphicon-warning-sign"></span> Are you sure you want to delete this Merchant?
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

<script type="text/javascript" src="{{asset('assets/vendors/bootstrap-switch/js/bootstrap-switch.js')}}"></script>

<script type="text/javascript" src="{{asset('assets/js/custom_js/HaseMerchantIndex.js')}}"></script>

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