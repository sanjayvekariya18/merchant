@extends('layouts/default')
{{-- Page title --}}
@section('title')
    Event Url List
    @parent
@stop
{{-- page level styles --}}
@section('header_styles')
@stop
@section('content')
<section class="content-header">
    <h1>Event Url List</h1>
    <ol class="breadcrumb">
        <li>
            <a href="index ">
                <i class="fa fa-fw fa-home"></i> Dashboard
            </a>
        </li>
        <li class="active">
            Event Url List
        </li>
    </ol>
</section>
<section class="content">
    <div class="row">
        <div class="col-md-12">
                <a href='{!!url("event_url_list")!!}/1' class='btn btn-primary btn-inline'>HOLD</a>
                <a href='{!!url("event_url_list")!!}/2' class='btn btn-primary btn-inline'>ACTIVE</a>
                <a href='{!!url("event_url_list")!!}/3' class='btn btn-primary btn-inline'>BLOCK</a>
                <a href='{!!url("event_url_list")!!}/5' class='btn btn-primary btn-inline'>UNRELATED</a>
                <a href='{!!url("event_url_list")!!}/4' class='btn btn-primary btn-inline'>DEFUNCT</a>
                      
        </div>
    </div>
    <br>
    <section class="content p-l-r-15">
        <div class="row">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h4 class="panel-title">    
                        <i class="fa fa-fw fa-users"></i> Event Url List
                    </h4>
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                         <table class="table table-bordered" id="eventTable">
                            
                            <thead>
                                
                                <th>Actions</th>
                                <th>Event Url</th>
                                <th>Status</th>
                                <th>user Name</th>
                              
                            </thead>
                            <tbody>
                                @foreach($event_url_list as $event_url_lists) 
                                <tr>
                                    
                                    <td>     
                                        <a href="{!!url('event_url_list')!!}/{!!$event_url_lists->event_url_node_id!!}/edit"><i class="fa fa-fw fa-pencil text-primary actions_icon" title="Edit Asset"></i>
                                        </a>
                                       
                                        <a href="#" data-toggle="modal" data-target="#delete" data-link = "{!!url('event_url_list')!!}/{!!$event_url_lists->event_url_node_id!!}/delete"><i class="fa fa-fw fa-times text-danger actions_icon" title="Delete Asset"></i></a>
                                    </td> <td>
                                    <a href="../event_url_details_view/{!!$event_url_lists->website_id!!}">{!!$event_url_lists->event_url!!}</a></td>
                                    <td>{!!$event_url_lists->status!!}</td>
                                    <td>{!!$event_url_lists->username!!}</td>   
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
                            <h4 class="modal-title custom_align" id="Heading">Delete Event Url</h4>
                        </div>
                        <div class="modal-body">
                            <div class="alert alert-warning">
                                <span class="glyphicon glyphicon-warning-sign"></span> Are you sure you want to delete this event url?
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
<script type="text/javascript">
$('#eventTable').DataTable({
       "responsive": true,
       "iDisplayLength": 25
   });
</script>
@stop