@extends('layouts/default')

{{-- Page title --}}
@section('title')
    Asset Team List
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
    <h1>Asset Team List</h1>
    <ol class="breadcrumb">
        <li>
            <a href="index ">
                <i class="fa fa-fw fa-home"></i> Dashboard
            </a>
        </li>
        <li class="active">
            Asset Team List
        </li>
    </ol>
</section>
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <?php if(in_array("add", $permissions)): ?>
            <a href='{!!url("asset_team_list")!!}/create' class='btn btn-primary btn-inline'>Create New Asset Team List
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
                        <i class="fa fa-fw fa-users"></i> Asset Team List
                    </h4>
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <div class="searchContainer">
                            <form action='{!!url("asset_team_list")!!}/search' method="POST" role="search">
                               {{ csrf_field() }}
                                <div class="input-group col-sm-3" style="float: left;">
                                    <input type="text" class="form-control" name="search_team_list" placeholder="Search Team List" value="{{$searchTeamList}}">
                                    <span class="input-group-btn">
                                       <button type="submit" class="btn btn-default">
                                           <span class="glyphicon glyphicon-search"></span>
                                       </button>
                                    </span>
                                </div>
                                <div style="float: left;margin-left: 2px;">
                                    @if($searchTeamList)
                                        <a href='{!!url("asset_team_list")!!}' class='btn btn-primary btn-inline'>   Clear
                                        </a>
                                    @endif
                                </div>
                                <br>
                                <div style="margin-top: 15px;clear: both;">
                                   @if(isset($details))
                                    <p> The Search results for your query <b> {{ $query }} </b> are </p>
                                   @endif
                                </div>
                            </form>
                            
                        </div>
                        <br>
                        <table class="table table-bordered" id="table">
                            <thead>
                                <?php if(in_array("manage", $permissions) || in_array("delete", $permissions)):?>
                                <th>Actions</th>
                                <?php endif; ?>
                                <th>List Id</th>
                                <th>Asset Name</th>
                                <th>Team Name</th>
                                <th>Member Name</th>
                                <th>Priority</th>
                                <th>Status</th>
                            </thead>
                            <tbody>
                                @foreach($asset_team_lists as $asset_team_list) 
                                <tr>
                                    <?php if(in_array("manage", $permissions) || in_array("delete", $permissions)):?>
                                    <td>
                                        <a href="{!!url('asset_team_list')!!}/{!!$asset_team_list->list_id!!}/edit"><i class="fa fa-fw fa-pencil text-primary actions_icon" title="Edit Asset Team List"></i>
                                        </a>
                                        <a href="#" data-toggle="modal" data-target="#delete" data-link = "{!!url('asset_team_list')!!}/{!!$asset_team_list->list_id!!}/delete"><i class="fa fa-fw fa-times text-danger actions_icon" title="Delete Asset Team List"></i></a>
                                    </td>
                                    <?php endif; ?>
                                    <td>{!!$asset_team_list->list_id!!}</td>
                                    <td>{!!$asset_team_list->asset_name!!}</td>
                                    <td>{!!$asset_team_list->team_name!!}</td>
                                    <td>{!!$asset_team_list->member_name!!}</td>
                                    <td>{!!$asset_team_list->priority!!}</td>
                                    <td>
                                         @if($asset_team_list->status == 1)
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
                            <h4 class="modal-title custom_align" id="Heading">Delete Asset Team List</h4>
                        </div>
                        <div class="modal-body">
                            <div class="alert alert-warning">
                                <span class="glyphicon glyphicon-warning-sign"></span> Are you sure you want to delete this Asset Team List?
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
        {{ $asset_team_lists->links() }}
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
        var $toast = toastr["{{ Session::pull('type') }}"]("", "{{ Session::pull('msg') }}");
    @endif
</script>
@stop
