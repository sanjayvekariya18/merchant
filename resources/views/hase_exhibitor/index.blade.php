@php use App\Http\Traits\PermissionTrait; @endphp
@extends('layouts/default')

{{-- Page title --}}
@section('title')
    Exhibitor
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
    <h1>Exhibitor</h1>
    <ol class="breadcrumb">
        <li>
            <a href="index ">
                <i class="fa fa-fw fa-home"></i> Dashboard
            </a>
        </li>
        <li>
            <a href="#"> Exhibitor</a>
        </li>
    </ol>
</section>
<section class="content">
    <?php if(in_array('add', $permissions)) : ?>
        <form class = 'col s3' method = 'get' action = '{!!url("hase_exhibitor")!!}/create'>
            <button class = 'btn btn-primary' type = 'submit'>Create New Exhibitor</button>
        </form>
    <?php endif; ?>
    <br>
    <br>
    <section class="content p-l-r-15">
        <div class="row">
            <div class="panel panel-primary ">
                <div class="panel-heading">
                    <h4 class="panel-title">    
                        <i class="fa fa-fw fa-users"></i> Exhibitor
                    </h4>
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="table">
                            <thead>
                                <tr class="filters">
                                    <?php if(in_array('manage', $permissions) || in_array('delete', $permissions)) : ?>
                                        <th>Actions</th>
                                    <?php endif; ?>
                                    <th>Id</th>
                                    <th>Merchant Name</th>
                                    <th>Exhibitor Place</th>
                                    <th>Exhibitor Contact</th>
                                </tr>
                            </thead>
                            <tbody>
                                 @foreach($hase_exhibitors as $hase_exhibitor) 
                                <tr>
                                    <?php if(in_array('manage', $permissions) || in_array('delete', $permissions)) : ?>
                                        <td>
                                            <?php if(in_array('manage', $permissions)) : ?>
                                                <a href="{!!url('hase_exhibitor')!!}/{!!$hase_exhibitor->exhibitor_id!!}/edit ">
                                                    <i class="fa fa-fw fa-pencil text-primary actions_icon" title="Edit Exhibitor"></i>
                                                </a>
                                            <?php endif; ?>
                                            <?php if(in_array('delete', $permissions)) : ?>
                                                <!-- <a href="#" data-toggle="modal" data-target="#delete" data-link = "{!!url('hase_exhibitor')!!}/{!!$hase_exhibitor->exhibitor_id!!}/delete">
                                                    <i class="fa fa-fw fa-times text-danger actions_icon" title="Delete Exhibitor"></i>
                                                </a> -->
                                            <?php endif; ?>
                                        </td>
                                    <?php endif; ?>
                                    <td>{!!$hase_exhibitor->exhibitor_id!!}</td>
                                    <td>{!!$hase_exhibitor->merchant_name!!}</td>
                                    <td>{!!$hase_exhibitor->exhibitor_floor!!},{!!$hase_exhibitor->exhibitor_hall!!}</td>
                                    <td>{!!$hase_exhibitor->exhibitor_contact!!}</td>
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
                            <h4 class="modal-title custom_align" id="Heading">Delete Exhibitor</h4>
                        </div>
                        <div class="modal-body">
                            <div class="alert alert-warning">
                                <span class="glyphicon glyphicon-warning-sign"></span> Are you sure you want to delete this Exhibitor?
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
        var $toast = toastr["{{ Session::get('type') }}"]("", "{{ Session::get('msg') }}");
    @endif
</script>
@stop