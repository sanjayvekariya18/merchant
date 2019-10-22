@extends('layouts/default')
{{-- Page title --}}
@section('title')
    Translation Json Query
    @parent
@stop
{{-- page level styles --}}
@section('header_styles')
@stop
@section('content')
<link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/jasny-bootstrap/css/jasny-bootstrap.css')}}" />
<link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/bootstrap-switch/css/bootstrap-switch.css')}}"/>
<link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/select2/css/select2.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/select2/css/select2-bootstrap.css')}}">
<section class="content-header">
    <h1>Translation Json Query List</h1>
    <ol class="breadcrumb">
        <li><a href="index "><i class="fa fa-fw fa-home"></i> Dashboard</a></li>
        <li><a href="#"> Translation Json Query</a></li>
        <li class="active">Json Query</li>
    </ol>
</section>
<section class="content">
    <form method = 'POST' enctype="multipart/form-data" class="form-horizontal bv-form" action = '{!!url("translation_json_query_list")!!}' id="translation_json_query_form">
        <input type = 'hidden' name = '_token' value = '{{Session::token()}}'>
        <div class="row">
            <div class="col-md-12">
                <button type="submit" name="submitBtn" value="SaveClose" class = 'btn btn-primary btn-inline'>Save &amp; close</button>
            </div>
        </div>
        <br/>
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-primary">
                    <div class="panel-body">

        <div class="form-group">
            <label for="status_name" class="col-sm-3 control-label">Translation Status</label>
            <div class="col-sm-8"> 
            <select name="status_name" id="status_name" class = "form-control select21">
                                    <option></option>
                                        @foreach($hase_translaton_status as $hase_status_list)
                            <option value="{{$hase_status_list->approval_status_id}}">{{$hase_status_list->approval_status_name}}</option>
                         @endforeach
                                    </select>



            </div>
        </div>
        <div class="form-group">
            <label for="userName" class="col-sm-3 control-label">User Name:</label>
            <div class="col-sm-8">
                <input name="userName" id="userName" value="{{$userName}}" class="form-control"/>
            </div>
        </div>
     </div>
   </div>
  </div>
</div>
</form>
</section>

@endsection
@section('footer_scripts')

<!-- begining of page level js -->
<script type="text/javascript" src="{{asset('assets/vendors/moment/js/moment.min.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/bootstrapvalidator/js/bootstrapValidator.min.js')}}" ></script>
<script type="text/javascript" src="{{asset('assets/vendors/bootstrap-switch/js/bootstrap-switch.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/select2/js/select2.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/js/custom_js/HaseTranslationJsonQuery.js')}}"></script>

<!-- end of page level js -->
@stop