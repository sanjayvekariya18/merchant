@extends('layouts/default')

{{-- Page title --}}
@section('title')
    Countries
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
    <h1>Countries</h1>
    <ol class="breadcrumb">
        <li>
            <a href="index ">
                <i class="fa fa-fw fa-home"></i> Dashboard
            </a>
        </li>
        <li>
            <a href="#"> System </a>
        </li>
        <li class="active">
            Countries
        </li>
    </ol>
</section>
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <?php if(in_array("add", $permissions)): ?>
                <a href='{!!url("hase_country")!!}/create' class='btn btn-primary btn-inline'>Create New Country
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
                        <i class="fa fa-fw fa-users"></i> Country List
                    </h4>
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="table">
                            <thead>
                                <tr class="filters">
                                    <th>Actions</th>
                                    <th>Country Id</th>
                                    <th>Country Name</th>
                                    <th>ISO Code 2</th>
                                    <th>ISO Code 3</th>
                                    <th>Format</th>
                                    <th>Status</th>
                                    <th>Flag</th>
                                    <th>Country Phone Code</th>
                                    <th>Telephone Min</th>
                                    <th>Telephone Max</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($hase_countries as $hase_country)
                                    <tr>
                                        <?php if(in_array("manage", $permissions) || in_array("delete", $permissions)):?>
                                            <td>
                                                <?php if(in_array("manage", $permissions)): ?>
                                                    <a href="{!!url('hase_country')!!}/{!!$hase_country->country_id!!}/edit ">
                                                        <i class="fa fa-fw fa-pencil text-primary actions_icon" title="Edit Country"></i>
                                                        
                                                    </a>
                                                <?php endif; ?>
                                                <?php if(in_array("delete", $permissions)): ?>
                                                    <a href="#" data-toggle="modal" data-target="#delete" data-link = "{!!url('hase_country')!!}/{!!$hase_country->country_id!!}/delete">
                                                        <i class="fa fa-fw fa-times text-danger actions_icon" title="Delete Country"></i>
                                                    </a>
                                                <?php endif; ?>
                                            </td>
                                        <?php endif; ?>
                                        <td>{!!$hase_country->country_id!!}</td>
                                        <td>{!!$hase_country->country_name!!}</td>
                                        <td>{!!$hase_country->iso_code_2!!}</td>
                                        <td>{!!$hase_country->iso_code_3!!}</td>
                                        <td>{!!$hase_country->format!!}</td>
                                        <td>{!!$hase_country->status!!}</td>
                                        <td>
                                            @if($hase_country->flag != NULL && is_file(env('image_dir_path').$hase_country->flag) )
                                                <img src="{{asset(env('image_dir_path').$hase_country->flag)}}" style="width: 80px; height: 40px;"/>
                                            @else
                                                <img src="{{asset(env('image_dir_path').'no_photo.png')}}" style="width: 80px; height: 40px;"/>
                                            @endif
                                        </td>
                                        <td>{!!$hase_country->country_phone_code!!}</td>
                                        <td>{!!$hase_country->telephone_min!!}</td>
                                        <td>{!!$hase_country->telephone_max!!}</td>
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
                            <h4 class="modal-title custom_align" id="Heading">Delete Country</h4>
                        </div>
                        <div class="modal-body">
                            <div class="alert alert-warning">
                                <span class="glyphicon glyphicon-warning-sign"></span> Are you sure you want to delete this Country?
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