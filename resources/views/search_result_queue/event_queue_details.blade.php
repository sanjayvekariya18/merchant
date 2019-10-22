@php use App\Http\Traits\PermissionTrait; @endphp
@extends('layouts/default')
{{-- Page title --}}
@section('title')
    Queue Event Details
    @parent
@stop
{{-- page level styles --}}
@section('header_styles')
@stop
@section('content')
<section class="content-header">
    <h1>Queue Event Details</h1>
    <ol class="breadcrumb">
        <li>
            <a href="index ">
                <i class="fa fa-fw fa-home"></i> Dashboard
            </a>
        </li>
        <li class="active">
           Queue Event Details
        </li>
    </ol>
</section>
<section class="content">
    <div class="row">
        <div class="col-md-12">
                    
        </div>
    </div>
    <br>
    <section class="content p-l-r-15">
        <div class="row">
            <div class="panel panel-primary ">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <i class="fa fa-fw fa-square-o"></i> Queue Event Details
                    </h4>
                </div>
                <div class="panel-body">
                    <div class="row">
                         <div class="col-md-12">
                                <a class = 'btn btn-primary' href='#' data-toggle="modal" data-target="#createStyle">Add New Event Url </a> <button type="button" class="btn btn-success" id="saveStyle" onclick="updateEvneDetails()" >
                                                                        <span class="glyphicon glyphicon-ok-sign"></span> Apply
                                                                    </button>
                         </div>
                    </div><br>
                    <div class="table-responsive">
                        <table class = "table table-bordered" id="table">
                            <thead>
                               <th>Event Url Title</th>
                               <th>Event Url</th>
                            </thead>
                            <tbody>
                            @foreach($eventQueueLists as $eventQueueList) 
                                <tr>
                                   <?php if($eventQueueList->keyword_label != '') {?>
                                    <td>{!!$eventQueueList->keyword_label!!}</td>
                                <?php } else {?>
                                    <td>{!!$eventQueueList->id!!}</td>
                                <?php } ?>
                                    <td>{!!$eventQueueList->event_url!!}</td>         
                            </tr>
                            @endforeach           
                            </tbody>
                        </table>
                        <div class="form-group">
                            <label for="queue_status" class="col-sm-2 control-label">Status</label>
                            <div class="col-sm-3">
                            <select name="queue_status" id="queue_status" class="form-control select21">
                               <option value="" disabled selected>Select Status</option>
                               <option value="active">ACTIVE</option>                                       
                            </select>
                            </div>
                        </div><br><br><br>
                         <div class="form-group">
                           <label for="event_url_title" class="col-sm-2 control-label">Pagination Template</label>
                                <div class="col-sm-3">
                                    <input id="pagination_template" name="pagination_template" type="text" class="form-control required">
                                 </div>
                         </div>
                    </div>
                </div>
            </div>
        </div>
         <div class="modal fade" data-backdrop="static" data-keyboard="false" id="createStyle" tabindex="-1" role="dialog" aria-labelledby="Heading" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="preloader">
                                                               <div class="loader_img">
                                                                   <img src="{{asset('assets/img/loader.gif')}}" alt="loading..." height="64" width="64">
                                                               </div>
                                                           </div>
                                                            <div class="modal-header">
                                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                                                <h4 class="modal-title custom_align" id="Heading">Add Event Url</h4>
                                                            </div>
                                                            <div class="modal-body">
                                                                <form id="create_event_url" method = 'POST' action='{!!url("event_url_detail_update")!!}' class="form-horizontal" enctype="multipart/form-data">
                                                                    <input type = 'hidden' name = '_token' value = '{{Session::token()}}'>
                                                                    <input type="hidden" name="merchant_type" id="merchant_type" value="">
                                                                    <div class="panel panel-primary">
                                                                        <div class="panel-heading">
                                                                            <h3 class="panel-title">
                                                                                <i class="livicon" data-name="user-add" data-size="18" data-c="#fff" data-hc="#fff" data-loop="true"></i> Create Event Url 
                                                                            </h3>
                                                                        </div>
                                                                        <div class="panel-body">
                                                                            <div class="row">
                                                                                <div class="form-group">
                                                                                    <label for="event_url_title" class="col-sm-4 control-label">Event Url Title</label>
                                                                                    <div class="col-sm-5">
                                                                                        <input id="event_url_title" name="event_url_title" type="text" class="form-control required">
                                                                                    </div>
                                                                                </div>
                                                                                <div class="form-group">
                                                                                    <label for="event_url" class="col-sm-4 control-label">Event Url</label>
                                                                                    <div class="col-sm-5">
                                                                                        <input id="event_url" name="event_url" type="text" class="form-control required">
                                                                                    </div>
                                                                                </div>
                                                                            </div>  
                                                                        </div>
                                                                    </div>
                                                                    <button type="button" class="btn btn-success" id="saveStyle" onclick="updateEvnetUrl()" >
                                                                        <span class="glyphicon glyphicon-ok-sign"></span> Save
                                                                    </button>
                                                                    <button type="button" class="btn btn-danger" data-dismiss="modal">
                                                                        <span class="glyphicon glyphicon-remove"></span> cancel
                                                                    </button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                        <!-- /.modal-content -->
                                                    </div>
                                                </div>
    </section>
</section>
@endsection
{{-- page level scripts --}}
@section('footer_scripts')
<script type="text/javascript">
    function updateEvnetUrl(){
        var website_id="{{$website_id}}";
        var event_url_title =$('#event_url_title').val();
        var event_url=$('#event_url').val();
                if(event_url_title == ''){
                     alert("Please Insert event Title");
                     return false;
                }else if(event_url == ''){
                    alert("Please Insert event Url");
                     return false;
                }
                else {
                    $.ajax({
                        type:'GET',
                        data:{event_url_title:event_url_title,event_url:event_url,website_id:website_id},
                        url : "../event_url_update",
                        error:function(xhr,status,error) {
                            console.log(error);
                        },
                        success:function(event_url_title,status,xhr) {
                            location.reload();
                        }
                    });

                }
    }
    function updateEvneDetails(){
        var website_id="{{$website_id}}";
        var pagination_template =$('#pagination_template').val();
        var queue_status=$("#queue_status").val();
                    $.ajax({
                        type:'GET',
                        data:{pagination_template:pagination_template,queue_status:queue_status,website_id:website_id},
                        url : "../event_details_update",
                        error:function(xhr,status,error) {
                            console.log(error);
                        },
                        success:function(event_url_title,status,xhr) {
                        window.location.href = "../search_result_queue";
                        }
                    });
    }
</script>
<!-- begining of page level js -->
<script type="text/javascript" src="{{asset('assets/vendors/datatables/js/jquery.dataTables.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/datatables/js/dataTables.bootstrap.js')}}"></script>

<!-- end of page level js -->
@stop