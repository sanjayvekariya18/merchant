@extends('layouts/default')

{{-- Page title --}}
@section('title')
    Create Status Operations Type
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
    <h1>Create Status Operations Type</h1>
    <ol class="breadcrumb">
        <li>
            <a href="index ">
                <i class="fa fa-fw fa-home"></i> Dashboard
            </a>
        </li>
        
        <li class="active">
            Status Operations Type
        </li>
    </ol>
</section>
<section class="content">
    <form id="statusOperationTypeForm"  action = '{!!url("status_operations_type")!!}' method = 'POST' class="form-horizontal" role="form" enctype="multipart/form-data">
        <div class="row">
            <div class="col-md-12">
                <button type="submit" name="submitBtn" value="Save" class='btn btn-primary btn-inline'>Save</button>
                <button type="submit" name="submitBtn" value="SaveClose" class = 'btn btn-primary btn-inline'>Save  &amp; close</button>
                <a href="{!!url("status_operations_type")!!}" class='btn btn-primary btn-inline'>
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
                            <i class="fa fa-fw fa-crosshairs"></i> Add Status Operations Type
                        </h3>
                    </div>
                    <div class="panel-body">
                        <input type = 'hidden' name = '_token' value = '{{Session::token()}}'>
                        <div class="form-group">
                            <label for="type_code" class="col-sm-3 control-label">Type Code</label>
                            <div class="col-sm-4">
                                <input id="type_code" name="type_code" type="text" class="form-control">
                            </div>
                        </div>  
                        <div class="form-group">
                            <label for="type_name" class="col-sm-3 control-label">Type Name</label>
                            <div class="col-sm-4">
                                <input id="type_name" name="type_name" type="text" class="form-control">
                            </div>
                        </div> 
                        <div class="form-group">
                            <label for="color_background" class="col-sm-3 control-label">Background Color</label>
                            <div class="col-sm-4">
                                <input id="color_background" name="color_background" type="text" class="form-control my-colorpicker1" value="#ffffff">
                            </div>
                        </div> 
                        <div class="form-group">
                            <label for="color_foreground" class="col-sm-3 control-label">Foreground Color</label>
                            <div class="col-sm-4">
                                <input id="color_foreground" name="color_foreground" type="text" class="form-control my-colorpicker1" value="#000000">
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
<script type="text/javascript" src="{{asset('assets/vendors/bootstrapvalidator/js/bootstrapValidator.min.js')}}" ></script>
<script type="text/javascript" src="{{asset('assets/vendors/colorpicker/js/bootstrap-colorpicker.min.js')}}" ></script>
<script type="text/javascript">
    $(document).ready(function(){
        $(".my-colorpicker1").colorpicker();
    });
</script>
@stop