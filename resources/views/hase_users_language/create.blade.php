@extends('layouts/default')

{{-- Page title --}}
@section('title')
    Create User Language
    @parent
@stop

{{-- page level styles --}}
@section('header_styles')
<!--page level css -->
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/bootstrapvalidator/css/bootstrapValidator.min.css')}}">
    <link href="{{asset('assets/vendors/select2/css/select2.min.css')}}" rel="stylesheet" type="text/css">
    <link href="{{asset('assets/vendors/select2/css/select2-bootstrap.css')}}" rel="stylesheet" type="text/css">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/jasny-bootstrap/css/jasny-bootstrap.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/bootstrap-switch/css/bootstrap-switch.css')}}"/>

<!--end of page level css-->
@stop
@section('content')
<section class="content-header">
    <h1>Create User Language</h1>
    <ol class="breadcrumb">
        <li>
            <a href="index ">
                <i class="fa fa-fw fa-home"></i> Dashboard
            </a>
        </li>
        <li>
            <a href="#"> System</a>
        </li>
        <li class="active">
            Create User Language
        </li>
    </ol>
</section>
<br>
<section class="content">
    <form id="create_user_language_form"  method = 'POST' enctype="multipart/form-data" class="form-horizontal bv-form" action = '{!!url("users_language")!!}' id="create_user_language_form">
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
                               data-loop="true"></i> Create User Language
                        </h3>
                        <span class="pull-right clickable">
                            <i class="glyphicon glyphicon-chevron-up"></i>
                        </span>
                    </div>
                     <input type="hidden" id="requestUrl" value="{!!url(Request::segment(1))!!}">
                    <div class="panel-body">
                        <div class="form-group">
                                        <label for="person_id" class="col-sm-3 control-label">Identity Name</label>
                                        <div class="col-sm-5">
                                            <select name="person_id" id="person_id" class = "form-control select21">
                                                                        <option></option>
                                                                            @foreach($people as $peoples)
                                                                           <?php
                                                                            $identity_detail = explode('_', $peoples['person_id']); 
                                                                            echo $identity_detail[0];?>
                                                                            <option value="{{$peoples['person_id']}}"><?php echo $identity_detail[0]."/";?>{{$peoples['person_name']}}</option>
                                                                            @endforeach
                                                                        </select>
                                        </div> 
                        </div>
                     
                                <div class="panel panel-primary">
                                    <div class="panel-heading">
                                        <h3 class="panel-title">
                                            Select User Language
                                        </h3>
                                    </div>
                                    <div class="panel-body" id="panelList">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="row">
                                                    <label class="col-md-5">Language</label>
                                                    <label class="col-md-2">Priority</label>
                                                </div>
                                                <div class="row" id="demo">
                                            <div class="col-sm-5">
                                               <select name="language_id" id="language_id" class = "form-control select21" required="true" onchange="updateLanguageDetails(this.value)">
                                                                        <option></option>
                                                                            @foreach($Users_language as $users_language)
                                                                            <option value="{{$users_language->language_id}}">{{$users_language->language_name}}</option>
                                                                            @endforeach
                                                                        </select>
                                            </div>
                                            <div class="col-sm-2">
                                                <input id="language_priority" name="language_priority" type="number" class="form-control" min="0" value="0" required="true">
                                            </div>
                                            <div class="col-sm-5">
                                            <button type="button" id='addLanguage' value="addLanguage" class = 'btn btn-primary btn-inline'> + </button>
                                        </div> 
                                            <br><br>
                                            <br>
                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row" id="languageDetails" style="display: none;">
                                            <div class="col-sm-5">
                                               <select name="language_id1" id="language_id1" class ="form-control select21">
                                                                        <option></option>
                                                                            @foreach($Users_language as $users_language)
                                                                            <option value="{{$users_language->language_id}}">{{$users_language->language_name}}</option>
                                                                            @endforeach
                                                                        </select>
                                            </div>
                                            <div class="col-sm-2">
                                                <input id="language_priority1" name="language_priority1" type="number" class="form-control" min="0" value="0" required="">
                                            </div>
                                            <br><br>
                                            <br>
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
<script type="text/javascript" src="{{asset('assets/vendors/bootstrapvalidator/js/bootstrapValidator.min.js')}}" ></script>
<script src="{{asset('assets/vendors/airdatepicker/js/datepicker.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/vendors/airdatepicker/js/datepicker.en.js')}}" type="text/javascript"></script>
<script type="text/javascript" src="{{asset('assets/vendors/bootstrap-switch/js/bootstrap-switch.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/select2/js/select2.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/js/custom_js/HaseUserLanguage.js')}}"></script> 
<script type="text/javascript" src="{{asset('assets/js/custom_js/UserLanguageCreate.js')}}"></script>
 <script type="text/javascript">
     function updateLanguageDetails(category_id,scrapeEventId){
                $.ajax({
                        type:'GET',
                        data:{category_id:category_id,scrapeEventId:scrapeEventId},
                        url : "update_language_list",
                        error:function(xhr,status,error) {
                            console.log(error);
                        },
                        success:function(category_id,status,xhr) {
                        }
                });
    }
 </script>
<!-- end of page level js -->
@stop





