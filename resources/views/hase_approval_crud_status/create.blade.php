@extends('layouts/default')

{{-- Page title --}}
@section('title')
    Approval Status
    @parent
@stop

{{-- page level styles --}}
@section('header_styles')
    <!--page level css -->
    
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/bootstrapvalidator/css/bootstrapValidator.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/colorpicker/css/bootstrap-colorpicker.min.css')}}" />
    <!--end of page level css-->
@stop
@section('content')
<section class="content-header">
    <h1>Create Approval Status</h1>
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
            Create Approval Status
        </li>
    </ol>
</section>
<br>
<section class="content">
    <form method = 'POST' enctype="multipart/form-data" class="form-horizontal bv-form" action='{!!url("hase_approval_crud_status")!!}' id="approval_status">
        <input type = 'hidden' name = '_token' value = '{{Session::token()}}'>
        <input id="statusDisplay" name="statusDisplay" type="hidden" value="1"  />
        <div class="row">
            <div class="col-md-12">
                <button type="submit" name="submitBtn" value="Save" class='btn btn-primary btn-inline'>Save</button>
                <button type="submit" name="submitBtn" value="SaveClose" class = 'btn btn-primary btn-inline'>Save &amp; close</button>
                <a href="{!!url('hase_approval_crud_status')!!}" class='btn btn-primary btn-inline'>
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
                               data-loop="true"></i> Create Approval Status
                        </h3>
                        <span class="pull-right clickable">
                            <i class="glyphicon glyphicon-chevron-up"></i>
                        </span>
                    </div>
                    <div class="panel-body">
                        <div class="form-group">
                            <label for="crud_status_code" class="col-sm-3 control-label">Status Code</label>
                            <div class="col-sm-5">
                                <input id="crud_status_code" name="crud_status_code" type="text" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="crud_status_name" class="col-sm-3 control-label">Status Name</label>
                            <div class="col-sm-5">
                                <input id="crud_status_name" name="crud_status_name" type="text" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="crud_status_color" class="col-sm-3 control-label">Status Color</label>
                            <div class="col-sm-5">
                                <input type="text" class="form-control my-colorpicker1" id="crud_status_color" name="crud_status_color">
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


<script type="text/javascript" src="{{asset('assets/vendors/bootstrapvalidator/js/bootstrapValidator.min.js')}}" ></script>
<script type="text/javascript" src="{{asset('assets/js/custom_js/HaseApprovalCrudStatus.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/colorpicker/js/bootstrap-colorpicker.min.js')}}" ></script>
<!-- end of page level js -->
@stop