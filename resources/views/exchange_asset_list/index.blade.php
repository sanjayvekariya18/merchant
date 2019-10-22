@extends('layouts/default')

{{-- Page title --}}
@section('title')
    Exchange List
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
    <h1>Exchange List</h1>
    <ol class="breadcrumb">
        <li>
            <a href="index ">
                <i class="fa fa-fw fa-home"></i> Dashboard
            </a>
        </li>
        <li class="active">
            Exchange List
        </li>
    </ol>
</section>
<section class="content">
    <div class="bs-example">
        <ul class="nav nav-tabs" style="margin-bottom: 15px;">
            <li class="active">
                <a href="#asset_list_panel" data-toggle="tab">Asset List</a>
            </li>
            <li>
                <a href="#language_list_panel" data-toggle="tab">Language List</a>
            </li>
        </ul>
        <div id="myTabContent" class="tab-content">
            <div class="tab-pane fade active in" id="asset_list_panel">
                <div class="row">
                    <div class="col-md-12">
                        <?php if(in_array("add", $permissions_asset)): ?>
                        <a href='{!!url("exchange_asset_list")!!}/create' class='btn btn-primary btn-inline'>Create New Exchange Asset List
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
                                    <i class="fa fa-fw fa-users"></i> Exchange Asset List
                                </h4>
                            </div>
                            <div class="panel-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="table">
                                        <thead>
                                            <!-- <?php // if(in_array("manage", $permissions_asset) || in_array("delete", $permissions_asset)):?>
                                            <th>Actions</th>
                                            <?php // endif; ?> -->
                                            <th>List Id</th>
                                            <th>Exchange Name</th>
                                            <th>Asset Name</th>
                                            <th>Asset Code</th>
                                            <th>Priority</th>
                                            <th>Status</th>
                                        </thead>
                                        <tbody>
                                            @foreach($exchange_asset_lists as $exchange_asset_list) 
                                            <tr>
                                                <!-- <?php // if(in_array("manage", $permissions_asset) || in_array("delete", $permissions_asset)):?>
                                                <td>
                                                    <a href="{!!url('exchange_asset_list')!!}/{!!$exchange_asset_list->list_id!!}/edit"><i class="fa fa-fw fa-pencil text-primary actions_icon" title="Edit Exchange Asset List"></i>
                                                    </a>
                                                    <a href="#" data-toggle="modal" data-target="#delete" data-link = "{!!url('exchange_asset_list')!!}/{!!$exchange_asset_list->list_id!!}/delete"><i class="fa fa-fw fa-times text-danger actions_icon" title="Delete Exchange Asset List"></i></a>
                                                </td>
                                                <?php // endif; ?> -->
                                                <td>{!!$exchange_asset_list->list_id!!}</td>
                                                <td>{!!$exchange_asset_list->exchange_name!!}</td>
                                                <td>{!!$exchange_asset_list->asset_name!!}</td>
                                                <td>{!!$exchange_asset_list->asset_code!!}</td>
                                                <td>{!!$exchange_asset_list->priority!!}</td>
                                                <td>
                                                    @if($exchange_asset_list->status == 1)
                                                    <?php echo 'Enable'; ?>
                                                    @else
                                                    <?php echo 'Disable'; ?>
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
                                        <h4 class="modal-title custom_align" id="Heading">Delete Exchange Asset List</h4>
                                    </div>
                                    <div class="modal-body">
                                        <div class="alert alert-warning">
                                            <span class="glyphicon glyphicon-warning-sign"></span> Are you sure you want to delete this Exchange Asset List?
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
            </div>
            <div class="tab-pane fade" id="language_list_panel">
                <div class="row">
                    <div class="col-md-12">
                        <?php if(in_array("add", $permissions_language)): ?>
                        <a href='{!!url("exchange_language_list")!!}/create' class='btn btn-primary btn-inline'>Create New Exchange Language List
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
                                    <i class="fa fa-fw fa-users"></i> Exchange Language List
                                </h4>
                            </div>
                            <div class="panel-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="table">
                                        <thead>
                                            <!-- <?php // if(in_array("manage", $permissions_language) || in_array("delete", $permissions_language)):?>
                                            <th>Actions</th>
                                            <?php // endif; ?> -->
                                            <th>List Id</th>
                                            <th>Exchange Name</th>
                                            <th>Language Name</th>
                                            <th>Priority</th>
                                            <th>Enable</th>
                                        </thead>
                                        <tbody>
                                            @foreach($exchange_language_lists as $exchange_language_list) 
                                            <tr>
                                                <!-- <?php // if(in_array("manage", $permissions_language) || in_array("delete", $permissions_language)):?>
                                                <td>
                                                    <a href="{!!url('exchange_language_list')!!}/{!!$exchange_language_list->list_id!!}/edit"><i class="fa fa-fw fa-pencil text-primary actions_icon" title="Edit Exchange Language List"></i>
                                                    </a>
                                                    <a href="#" data-toggle="modal" data-target="#delete" data-link = "{!!url('exchange_language_list')!!}/{!!$exchange_language_list->list_id!!}/delete"><i class="fa fa-fw fa-times text-danger actions_icon" title="Delete Exchange Language List"></i></a>
                                                </td>
                                                <?php // endif; ?> -->
                                                <td>{!!$exchange_language_list->list_id!!}</td>
                                                <td>{!!$exchange_language_list->exchange_name!!}</td>
                                                <td>{!!$exchange_language_list->language_name!!}</td>
                                                <td>{!!$exchange_language_list->priority!!}</td>
                                                <td>
                                                    @if($exchange_language_list->status == 1)
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
                                        <h4 class="modal-title custom_align" id="Heading">Delete Exchange Language List</h4>
                                    </div>
                                    <div class="modal-body">
                                        <div class="alert alert-warning">
                                            <span class="glyphicon glyphicon-warning-sign"></span> Are you sure you want to delete this Exchange Language List?
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
            </div>            
        </div>
    </div>         
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
