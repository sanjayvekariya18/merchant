@extends('layouts/default')

{{-- Page title --}}
@section('title')
    Approval Routing
    @parent
@stop

{{-- page level styles --}}
@section('header_styles')
    <!--page level css -->
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/jasny-bootstrap/css/jasny-bootstrap.css')}}" />
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/bootstrapvalidator/css/bootstrapValidator.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/select2/css/select2.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/select2/css/select2-bootstrap.css')}}">
    <!--end of page level css-->
@stop
@section('content')
<section class="content-header">
    <h1>Edit Approval Routing</h1>
    <ol class="breadcrumb">
        <li>
            <a href="index ">
                <i class="fa fa-fw fa-home"></i> Dashboard
            </a>
        </li>
        <li>
            <a href="#"> Systems</a>
        </li>
        <li class="active">
            Edit Approval Routing
        </li>
    </ol>
</section>
<br>
<section class="content">
    <form method = 'POST' enctype="multipart/form-data" class="form-horizontal bv-form" action='{!!url("hase_approval_group_list")!!}/{!!$hase_approval_group_list->staff_group_list_id!!}/update' id="edit_approval_group_form">
        <input type = 'hidden' name = '_token' value = '{{Session::token()}}'>
        <div class="row">
            <div class="col-md-12">
                <button type="submit" name="submitBtn" value="Save" class='btn btn-primary btn-inline'>Save</button>
                <button type="submit" name="submitBtn" value="SaveClose" class = 'btn btn-primary btn-inline'>Save &amp; close</button>
                <a href="{!!url('hase_approval_group_list')!!}" class='btn btn-primary btn-inline'>
                    <i class="fa fa-fw fa-arrow-left"></i>
                </a>
            </div>
        </div>
        <br/>
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h3 class="panel-title">
                            <i class="livicon" data-name="user-add" data-size="18" data-c="#fff" data-hc="#fff"
                               data-loop="true"></i> Edit Approval Routing
                        </h3>
                        <span class="pull-right clickable">
                            <i class="glyphicon glyphicon-chevron-up"></i>
                        </span>
                    </div>
                    <div class="panel-body">
                        <div class="form-group">
                            <label for="source_staff_group_id" class="col-sm-3 control-label">Source Group</label>
                            <div class="col-sm-5">
                                <select name="source_staff_group_id" id="source_staff_group_id" class = "form-control select21">
                                    @foreach($hase_staff_groups as $hase_staff_group)
                                        @if($hase_staff_group->group_id == $hase_approval_group_list->source_staff_group_id)
                                            <option value="{{$hase_staff_group->group_id}}" selected>{{$hase_staff_group->group_name}}</option>
                                        @else
                                            <option value="{{$hase_staff_group->group_id}}">{{$hase_staff_group->group_name}}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="target_staff_group_id" class="col-sm-3 control-label">Target Group</label>
                            <div class="col-sm-5">
                                <select name="target_staff_group_id" id="target_group_id" class = "form-control select21">
                                    @foreach($hase_staff_groups as $hase_staff_group)
                                        @if($hase_staff_group->group_id == $hase_approval_group_list->target_group_id)
                                            <option value="{{$hase_staff_group->group_id}}" selected>{{$hase_staff_group->group_name}}</option>
                                        @else
                                            <option value="{{$hase_staff_group->group_id}}">{{$hase_staff_group->group_name}}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="source_approval_status_id" class="col-sm-3 control-label">Source Status</label>
                            <div class="col-sm-5">
                                <select name="source_approval_status_id" id="status_source" class = "form-control select21">
                                    @foreach($hase_approval_statuses as $hase_approval_status)
                                        @if($hase_approval_status->approval_status_id == $hase_approval_group_list->source_approval_status_id)
                                            <option value="{{$hase_approval_status->approval_status_id}}" selected>{{$hase_approval_status->approval_status_name}}</option>
                                        @else
                                            <option value="{{$hase_approval_status->approval_status_id}}">{{$hase_approval_status->approval_status_name}}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="target_approval_status_id" class="col-sm-3 control-label">Target Status</label>
                            <div class="col-sm-5">
                                <select name="target_approval_status_id" id="status_target" class = "form-control select21">
                                    @foreach($hase_approval_statuses as $hase_approval_status)
                                        @if($hase_approval_status->approval_status_id == $hase_approval_group_list->target_approval_status_id)
                                            <option value="{{$hase_approval_status->approval_status_id}}" selected>{{$hase_approval_status->approval_status_name}}</option>
                                        @else
                                            <option value="{{$hase_approval_status->approval_status_id}}">{{$hase_approval_status->approval_status_name}}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</section>
@endsection
{{-- page level scripts --}}
@section('footer_scripts')
<!-- begining of page level js -->
<script type="text/javascript" src="{{asset('assets/vendors/moment/js/moment.min.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/jasny-bootstrap/js/jasny-bootstrap.js')}}" ></script>
<script type="text/javascript" src="{{asset('assets/vendors/select2/js/select2.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/bootstrapvalidator/js/bootstrapValidator.min.js')}}" ></script>
<script type="text/javascript" src="{{asset('assets/js/custom_js/haseApprovalGroup.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/select2/js/select2.js')}}"></script>
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