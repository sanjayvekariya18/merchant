@extends('layouts/default')

{{-- Page title --}}
@section('title')
    Edit Translation Manage
    @parent
@stop

{{-- page level styles --}}
@section('header_styles')
    <!--page level css -->
    
     <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/jasny-bootstrap/css/jasny-bootstrap.css')}}" />
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/bootstrap-switch/css/bootstrap-switch.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/select2/css/select2.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/select2/css/select2-bootstrap.css')}}">
    <!--end of page level css-->
@stop
@section('content')
<section class="content-header">
    <h1>Edit Translation Manage</h1>
    <ol class="breadcrumb">
        <li>
            <a href="index ">
                <i class="fa fa-fw fa-home"></i> Dashboard
            </a>
        </li>
        <li>
            <a href="#"> system</a>
        </li>
        <li class="active">
            Edit Translation Manage
        </li>
    </ol>
</section>
<br>
<section class="content">
 <form method = 'POST' enctype="multipart/form-data" class="form-horizontal bv-form" role="form" action = '{!! url("hase_translation_manage")!!}/{!!$hase_translation_manage->manage_id!!}/update' id="create_translation_manage_form">

        <input type = 'hidden' name = '_token' value = '{{Session::token()}}'>
        <div class="row">
            <div class="col-md-12">
                <button type="submit" name="submitBtn" value="SaveClose" class = 'btn btn-primary btn-inline'>Save &amp; close</button>
                <a href="{!!url('hase_translation_manage')!!}" class='btn btn-primary btn-inline'>
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
                               data-loop="true"></i> Edit Translation Manage
                        </h3>
                        <span class="pull-right clickable">
                            <i class="glyphicon glyphicon-chevron-up"></i>
                        </span>
                    </div>
                    <div class="panel-body">
        <div class="form-group">
            <label for="manage_table_id" class="col-sm-3 control-label">Manage table</label>
            <div class="col-sm-8">
            <select name="manage_table_id" id="manage_table_id" class = "form-control select21">
                @foreach($hase_translation_keys as $hase_translation_key)
                    @if($hase_translation_manage->manage_table_id == $hase_translation_key->key_id)
                        <option value="{{$hase_translation_key->key_id}}" selected>{{$hase_translation_key->key_table}}</option>
                    @else
                        <option value="{{$hase_translation_key->key_id}}">{{$hase_translation_key->key_table}}</option>
                    @endif
                @endforeach
            </select>
            </div>
        </div>
        <div class="form-group">
            <label for="language_source" class="col-sm-3 control-label">Language Source</label>
            <div class="col-sm-8">
            <select name="language_source" id="language_source" class = "form-control select21">
                @foreach($hase_staff_groups as $hase_staff_group)
                    @if($hase_translation_manage->language_source == $hase_staff_group->staff_group_id)
                        <option value="{{$hase_staff_group->staff_group_id}}" selected>{{$hase_staff_group->staff_group_name}}</option>
                    @else
                        <option value="{{$hase_staff_group->staff_group_id}}">{{$hase_staff_group->staff_group_name}}</option>
                    @endif
                    
                @endforeach
            </select>
            </div>
        </div>
        <div class="form-group">
            <label for="language_target" class="col-sm-3 control-label">Language Target</label>
            <div class="col-sm-8">
            <select name="language_target" id="language_target" class = "form-control select21">
                @foreach($hase_staff_groups as $hase_staff_group)
                        @if($hase_translation_manage->language_target == $hase_staff_group->staff_group_id)
                            <option value="{{$hase_staff_group->staff_group_id}}" selected>{{$hase_staff_group->staff_group_name}}</option>
                        @else
                            <option value="{{$hase_staff_group->staff_group_id}}">{{$hase_staff_group->staff_group_name}}</option>
                        @endif
                @endforeach
            </select>
            </div>
        </div>
        <div class="form-group">
            <label for="language_status" class="col-sm-3 control-label">Language Status</label>
            <div class="col-sm-8">
           <select name="language_status" id="language_status" class = "form-control select21">
                    @foreach($hase_approval_statuses as $hase_approval_status)
                        @if($hase_translation_manage->language_status == $hase_approval_status->approval_status_id)
                            <option value="{{$hase_approval_status->approval_status_id}}" selected>{{$hase_approval_status->approval_status_name}}</option>
                        @else
                            <option value="{{$hase_approval_status->approval_status_id}}">{{$hase_approval_status->approval_status_name}}</option>
                        @endif                            
                    @endforeach
            </select>
            </div>
        </div>
        <div class="form-group">
            <label for="translator_user_id" class="col-sm-3 control-label">Translator User Id</label>
            <div class="col-sm-8">
            <select name="translator_user_id" id="translator_user_id" class = "form-control select21">
                    @foreach($hase_user_list as $hase_user_lists)
                         @if($hase_translation_manage->translator_user_id == $hase_user_lists->user_id)
                            <option value="{{$hase_user_lists->user_id}}" selected>{{$hase_user_lists->username}}</option>
                         @else
                            <option value="{{$hase_user_lists->user_id}}">{{$hase_user_lists->username}}</option>
                         @endif
                    @endforeach
            </select>
            </div>
        </div>

        <div class="form-group">
            <label for="approval_user_id" class="col-sm-3 control-label">Approval User Id</label>
            <div class="col-sm-8"> 
            <select name="approval_user_id" id="approval_user_id" class = "form-control select21">
                    @foreach($hase_user_list as $hase_user_lists)
                        @if($hase_translation_manage->approval_user_id == $hase_user_lists->user_id)
                            <option value="{{$hase_user_lists->user_id}}" selected>{{$hase_user_lists->username}}</option>
                        @else
                            <option value="{{$hase_user_lists->user_id}}">{{$hase_user_lists->username}}</option>
                        @endif
                    @endforeach
            </select>
            </div>
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
<script type="text/javascript" src="{{asset('assets/vendors/bootstrapwizard/js/jquery.bootstrap.wizard.js')}}" ></script>
<script type="text/javascript" src="{{asset('assets/vendors/bootstrapvalidator/js/bootstrapValidator.min.js')}}" ></script>
<script type="text/javascript" src="{{asset('assets/vendors/bootstrap-switch/js/bootstrap-switch.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/toastr/js/toastr.min.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/js/custom_js/hasePromotion.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/select2/js/select2.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/js/custom_js/HaseChatbotCommunication.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/js/custom_js/HaseUserLanguage.js')}}"></script>
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
