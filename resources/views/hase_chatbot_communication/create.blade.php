@extends('layouts/default')

{{-- Page title --}}
@section('title')
    Create Chatbot Communication
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
    <h1>Create Chatbot Communication</h1>
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
            Create Chatbot Communication
        </li>
    </ol>
</section>
<br>
<section class="content">
    <form method = 'POST' enctype="multipart/form-data" class="form-horizontal bv-form" action = '{!!url("hase_chatbot_communication")!!}' id="create_Chatbot_Communication_form">
        <input type = 'hidden' name = '_token' value = '{{Session::token()}}'>
        <div class="row">
            <div class="col-md-12">
                <button type="submit" name="submitBtn" value="SaveClose" class = 'btn btn-primary btn-inline'>Save &amp; close</button>
                <a href="{!!url('hase_chatbot_communication')!!}" class='btn btn-primary btn-inline'>
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
                               data-loop="true"></i> Create Chatbot Communication
                        </h3>
                        <span class="pull-right clickable">
                            <i class="glyphicon glyphicon-chevron-up"></i>
                        </span>
                    </div>
                    <div class="panel-body">

        <div class="form-group">
            <label for="communications_topic" class="col-sm-3 control-label">Communications Topic*</label>
            <div class="col-sm-8"> 
            <select name="communication_topic_id" id="communication_topic_id" class = "form-control select21">
                                    <option></option>
                                        @foreach($Hase_chatbot_communication as $hase_chatbot_communication)
                                        <option value="{{$hase_chatbot_communication->topic_id}}">{{$hase_chatbot_communication->topic_name}}</option>
                                        @endforeach
                                    </select>
            </div>
        </div>
        <div class="form-group">
            <label for="communication_opcode" class="col-sm-3 control-label">Communications opcode</label>
            <div class="col-sm-8">
            <input id="communication_opcode" name = "communication_opcode" type="text" class="form-control">
            </div>
        </div>
        <div class="form-group">
            <label for="communication_text" class="col-sm-3 control-label">Communications Text</label>
            <div class="col-sm-8">
             <textarea id="communication_text" name ="communication_text" rows="4" class="form-control resize_vertical" placeholder="Communications Content"></textarea> 
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
<script type="text/javascript" src="{{asset('assets/vendors/bootstrapvalidator/js/bootstrapValidator.min.js')}}" ></script>
<script src="{{asset('assets/vendors/airdatepicker/js/datepicker.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/vendors/airdatepicker/js/datepicker.en.js')}}" type="text/javascript"></script>
<script type="text/javascript" src="{{asset('assets/vendors/bootstrap-switch/js/bootstrap-switch.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/js/custom_js/hasePromotion.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/select2/js/select2.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/js/custom_js/HaseChatbotCommunication.js')}}"></script>
<!-- end of page level js -->
@stop


