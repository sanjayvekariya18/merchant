@extends('layouts/default')
{{-- Page title --}}
@section('title')
    Website Domain
    @parent
@stop
{{-- page level styles --}}
@section('header_styles')
@stop
@section('content')
<section class="content-header">
    <h1>Website Domain List</h1>
    <ol class="breadcrumb">
        <li>
            <a href="index ">
                <i class="fa fa-fw fa-home"></i> Dashboard
            </a>
        </li>
        <li class="active">
           Website Domain
        </li>
    </ol>
</section>
<section class="content">
    <div class="row">
        <div class="col-md-12">
                <a href='{!!url("website_domain")!!}/create' class='btn btn-primary btn-inline'>Create New Website Domain</a>       
        </div>
    </div>
    <br>
    <section class="content p-l-r-15">
        <div class="row">
            <div class="panel panel-primary ">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <i class="fa fa-fw fa-square-o"></i> Website Domain List
                    </h4>
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class = "table table-bordered" id="table">
                            <thead>
                               <th>Actions</th>
                                <th>Website Domain Id</th>
                                <th>Website Url</th>
                                <th>Status</th>
                            </thead>
                            <tbody>
                           @foreach($website_domains as $website_domain)  
                                <tr>
                                    <td> <a href = '{!!url("website_domain")!!}/{!!$website_domain->website_domain_id!!}/edit'><i class = 'fa fa-fw fa-pencil text-primary actions_icon' title="Edit Table"></i></a>
                                    <a href='#' data-toggle="modal" data-target="#delete" data-link = '{!!url("website_domain")!!}/{!!$website_domain->website_domain_id!!}/delete'><i class = 'fa fa-fw fa-times text-danger actions_icon' title="Delete Table"></i></a></td>
                                    <td>{!!$website_domain->website_domain_id!!}</td>
                                    <td>{!!$website_domain->website_url!!}</td>
                                    <td>{!!$website_domain->status!!}</td>
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
                            <h4 class="modal-title custom_align" id="Heading">Delete Website Domain list</h4>
                        </div>
                        <div class="modal-body">
                            <div class="alert alert-warning">
                                <span class="glyphicon glyphicon-warning-sign"></span> Are you sure you want to
                                delete this Website Domain ?
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
<!-- end of page level js -->
@stop