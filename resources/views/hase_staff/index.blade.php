@extends('layouts/default')

{{-- Page title --}}
@section('title')
    Staffs
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
    <h1>Staffs</h1>
    <ol class="breadcrumb">
        <li>
            <a href="index ">
                <i class="fa fa-fw fa-home"></i> Dashboard
            </a>
        </li>
        <li>
            <a href="#"> Users</a>
        </li>
        <li class="active">
            Staffs
        </li>
    </ol>
</section>
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <?php if(in_array("add", $permissions)): ?>
                <a href='{!!url("hase_staff")!!}/create' class='btn btn-primary btn-inline'>     Create New Staff
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
                        <i class="fa fa-fw fa-users"></i> Staff List
                    </h4>
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="table">
                            <thead>
                                <tr class="filters">
                                    <?php if(in_array("manage", $permissions) || in_array("delete", $permissions)):?>
                                        <th>Actions</th>
                                    <?php endif; ?>
                                    <th>Id</th>
                                    <th>Merchant</th>
                                    <th>Name</th>
                                    <th>Staff Group</th>
                                    <th>Location</th>
                                    <th>Clear Password</th>
                                    <th>Status</th>
                                    <th>Reset Password</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($hase_staffs as $hase_staff) 
                                <tr>
                                    <?php if(in_array("manage", $permissions) || in_array("delete", $permissions)):?>
                                        <td>
                                            <?php if(in_array("manage", $permissions)): ?>
                                                <a href="{!!url('hase_staff')!!}/{!!$hase_staff->staff_id!!}/edit ">
                                                    <i class="fa fa-fw fa-pencil text-primary actions_icon" title="Edit Category"></i>
                                                </a>
                                            <?php endif; ?>
                                            <?php if(in_array("delete", $permissions)): ?>
                                                <a href="#" data-toggle="modal" data-target="#delete" data-link = "{!!url('hase_staff')!!}/{!!$hase_staff->staff_id!!}/delete">
                                                    <i class="fa fa-fw fa-times text-danger actions_icon" title="Delete Staff"></i>
                                                </a>
                                            <?php endif; ?>
                                        </td>
                                    <?php endif; ?>
                                    <td>{!!$hase_staff->staff_id!!}</td>
                                    <td>{!!$hase_staff->merchant_name!!}</td>
                                    <td>{!!$hase_staff->identity_name!!}</td>
                                    <td>{!!$hase_staff->group_name!!}</td>
                                    @if($hase_staff->location_name)
                                        <td>{!!$hase_staff->location_name!!}</td>
                                    @else
                                        <td>None</td>
                                    @endif
                                    <td>{!!$hase_staff->clear_password!!}</td>
                                    <td>
                                         @if($hase_staff->staff_status == 1)
                                            <span class="btn-success btn-xs">Enable</span>
                                        @else
                                            <span class="btn-danger btn-xs">Disable</span>
                                        @endif
                                    </td>
                                    <td>
                                        <form id="staffForm"  action = '{!!url("hase_staff")!!}/reset' method = 'POST' class="form-horizontal" role="form">
                                            <input type = 'hidden' name = '_token' value = '{{Session::token()}}' />
                                            <input type = 'hidden' name = 'user_id' value = '{!!$hase_staff->user_id!!}' />
                                            <button type="submit" name="submitBtn" class='btn btn-danger btn-xs'>Reset</button>
                                        </form>
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
                            <h4 class="modal-title custom_align" id="Heading">Delete Staff</h4>
                        </div>
                        <div class="modal-body">
                            <div class="alert alert-warning">
                                <span class="glyphicon glyphicon-warning-sign"></span> Are you sure you want to delete this Staff?
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