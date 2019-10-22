@php use App\Http\Traits\PermissionTrait; @endphp
@extends('layouts/default')
{{-- Page title --}}
@section('title')
    Queue Event Url
    @parent
@stop
{{-- page level styles --}}
@section('header_styles')
@stop
@section('content')
<section class="content-header">
    <h1>Queue Event Url List</h1>
    <ol class="breadcrumb">
        <li>
            <a href="index ">
                <i class="fa fa-fw fa-home"></i> Dashboard
            </a>
        </li>
        <li class="active">
           Queue Event Url
        </li>
    </ol>
</section>
<section class="content">
    <div class="row">
        <div class="col-md-12">
                <a href='{!!url("search_result_queue")!!}/create' class='btn btn-primary btn-inline'>Create New Queue Event Url</a>       
        </div>
    </div>
    <br>
    <section class="content p-l-r-15">
        <div class="row">
            <div class="panel panel-primary ">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <i class="fa fa-fw fa-square-o"></i> Queue Event Url List
                    </h4>
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class = "table table-bordered" id="queueTable">
                        <div class="form-group">
                            <label for="queue_status" class="col-sm-1 control-label">QUEUE</label>
                            <div class="col-sm-3">
                            <select name="queue_status" id="queue_status" onchange="updateSearchEventStatus(this.options[selectedIndex].value)" class="form-control select21">
                               <option>Change Status</option>
                               <option value="active">ACTIVE</option>                                       
                            </select>
                            </div>
                        </div><br><br> 
                            <thead>
                               <th>Actions</th>
                               <th></th>
                               <th>User</th>
                               <th>Event Url</th>
                               <th>Entry Date</th>
                               <th>Entry Time</th>
                            </thead>
                            <tbody>


                            @foreach($search_result_queues as $search_result_queue) 
                                <tr>
                                    <td> <a href = '{!!url("search_result_queue")!!}/{!!$search_result_queue->id!!}/edit'><i class = 'fa fa-fw fa-pencil text-primary actions_icon' title="Edit Table"></i></a>
                                    <a href='#' data-toggle="modal" data-target="#delete" data-link = '{!!url("search_result_queue")!!}/{!!$search_result_queue->id!!}/delete'><i class = 'fa fa-fw fa-times text-danger actions_icon' title="Delete Table"></i></a></td>
                                    <td><input name ="selector[]" id="queueStatusUpdate" type="checkbox" value="{!!$search_result_queue->id!!}" ></td>
                                    <td>{!!$search_result_queue->username!!}</td>
                                    <td><a href="queue_event_details_view/{!!$search_result_queue->website_id!!}"> {!!$search_result_queue->website_url!!}</a></td>
                                     <td><?php echo PermissionTrait::convertIntoDate($search_result_queue->entry_date); ?></td>
                                    <td><?php echo PermissionTrait::convertIntoTime($search_result_queue->entry_time); ?></td>
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
                            <h4 class="modal-title custom_align" id="Heading">Delete Queue Event Url list</h4>
                        </div>
                        <div class="modal-body">
                            <div class="alert alert-warning">
                                <span class="glyphicon glyphicon-warning-sign"></span> Are you sure you want to
                                delete this Queue Event Url ?
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
<script type="text/javascript">
    function updateSearchEventStatus(queue_status){
       
        var queueStatusUpdate = [];
        $(':checkbox:checked').each(function(i){
          queueStatusUpdate[i] = $(this).val();
        });
        if(queueStatusUpdate == ''){
             alert("Please selection from the queue event url");
             return false;
        }else {
        $.ajax({
            type:'GET',
            data:{queue_status:queue_status,queueStatusUpdate:queueStatusUpdate},
            url : "event_queue_status_update",
            error:function(xhr,status,error) {
                console.log(error);
            },
            success:function(queueStatusUpdate,status,xhr) {
                location.reload();
            }

        });

        }
    }
</script>
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
   $('#queueTable').DataTable({
       "responsive": true,
       "iDisplayLength": 25
   });
</script>
<!-- end of page level js -->
@stop