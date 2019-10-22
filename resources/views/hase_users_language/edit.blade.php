@extends('layouts/default')

{{-- Page title --}}
@section('title')
    Edit Users Language
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
    <h1>Edit users Language</h1>
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
            Edit Users Language
        </li>
    </ol>
</section>
<br>
<section class="content">
 <form method = 'POST' enctype="multipart/form-data" class="form-horizontal bv-form" role="form" action = '{!! url("users_language")!!}/{!!$users_language->id!!}/update' id="create_user_language_form">

        <input type = 'hidden' name = '_token' value = '{{Session::token()}}'>
        <div class="row">
            <div class="col-md-12">
                <button type="submit" name="submitBtn" value="SaveClose" class = 'btn btn-primary btn-inline'>Save &amp; close</button>
                <a href="{!!url('users_language')!!}" class='btn btn-primary btn-inline'>
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
                               data-loop="true"></i> Edit Users Language
                        </h3>
                        <span class="pull-right clickable">
                            <i class="glyphicon glyphicon-chevron-up"></i>
                        </span>
                    </div>
                    <div class="panel-body">




            <div class="form-group">
            <label for="language_id" class="col-sm-3 control-label">Language Name</label>
            <div class="col-sm-8"> 
            <select name="language_id" id="language_id" class = "form-control select21">
                <option></option>
                    @foreach($Users_language_list as $users_language_list)
                        @if($users_language_list->language_id == $users_language->language_id)
                            <option value="{{$users_language_list->language_id}}" selected>{{$users_language_list->language_name}}</option>
                        @else
                            <option value="{{$users_language_list->language_id}}">{{$users_language_list->language_name}}</option>
                        @endif
                    @endforeach
                </select>
            </div>
        </div>
                        
        <div class="form-group">
            <label for="language_priority" class="col-sm-3 control-label">Language Prioroty</label>
            <div class="col-sm-8">
            <input id="language_name" name = "language_priority" type="text" class="form-control" value="{!!$users_language->
            language_priority!!}"> 
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


