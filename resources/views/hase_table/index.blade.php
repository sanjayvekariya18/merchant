@extends('layouts/default')
{{-- Page title --}}
@section('title')
    Hase Table
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
    <h1>Tables</h1>
    <ol class="breadcrumb">
        <li>
            <a href="index ">
                <i class="fa fa-fw fa-home"></i> Dashboard
            </a>
        </li>
        <li>
            <a href="#"> Restaurants</a>
        </li>
        <li class="active">
            Tables
        </li>
    </ol>
</section>
<section class="content">
    <form class = 'col s3' method = 'get' action = '{!!url("hase_table")!!}/create'>
        <button class='btn btn-primary' type='submit'>Create New Table</button>
    </form>
    <br>
    <section class="content p-l-r-15">
        <div class="row">
            <div class="panel panel-primary ">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <i class="fa fa-fw fa-square-o"></i> Table List
                    </h4>
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class = "table table-bordered" id="table">
                            <thead>
                                <th>Actions</th>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Minimum</th>
                                <th>Capacity</th>
                                <th>Status</th>
                            </thead>
                            <tbody>
                                @foreach($hase_tables as $hase_table) 
                                <tr>
                                    <td>                    
                                        <a href = '{!!url("hase_table")!!}/{!!$hase_table->seating_id!!}/edit'><i class = 'fa fa-fw fa-pencil text-primary actions_icon' title="Edit Table"></i></a>

                                        <a href='#' data-toggle="modal" data-target="#delete" data-link = '{!!url("hase_table")!!}/{!!$hase_table->seating_id!!}/delete'><i class = 'fa fa-fw fa-times text-danger actions_icon' title="Delete Table"></i></a>
                                    </td>
                                    <td>{!!$hase_table->seating_id!!}</td>
                                    <td>{!!$hase_table->seating_name!!}</td>
                                    <td>{!!$hase_table->min_capacity!!}</td>
                                    <td>{!!$hase_table->max_capacity!!}</td>
                                    @if($hase_table->status == 1)
                                        <td><span class="btn-success btn-xs">Enabled</span></td>
                                    @else
                                        <td><span class="btn-danger btn-xs">Disabled</span></td>
                                    @endif
                                    
                                </tr>
                                @endforeach 
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>    

            <!-- /.modal-dialog -->
            <div class="modal fade" id="delete" tabindex="-1" role="dialog" aria-labelledby="Heading" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                            <h4 class="modal-title custom_align" id="Heading">Delete Table</h4>
                        </div>
                        <div class="modal-body">
                            <div class="alert alert-warning">
                                <span class="glyphicon glyphicon-warning-sign"></span> Are you sure you want to
                                delete this Table ?
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
        <!-- row-->  
        @include('layouts.right_sidebar')
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
<!-- end of page level js -->
@stop