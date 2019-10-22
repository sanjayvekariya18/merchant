@extends('layouts/default')
{{-- Page title --}}
@section('title')
    Trade Status Type
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
    <h1>Edit Trade Status Type</h1>
    <ol class="breadcrumb">
        <li>
            <a href="index ">
                <i class="fa fa-fw fa-home"></i> Dashboard
            </a>
        </li>
        <li class="active">
            Trade Status Type
        </li>
    </ol>
</section>
<section class="content">
    <form id="trade_status_type" method='POST' action = '{!! url("trade_status_type")!!}/{!!$trade_status_type->
        trade_status_id!!}/update' class="form-horizontal">
        <input type = 'hidden' name = '_token' value = '{{Session::token()}}'>
        <div class="row">
            <div class="col-md-12">
                <button type="submit" name="submitBtn" value="Save" class='btn btn-primary btn-inline'>Save</button>
                <button type="submit" name="submitBtn" value="SaveClose" class= 'btn btn-primary btn-inline'>Save &amp; Close</button>
                <a href="{{url('trade_status_type')}}" class='btn btn-primary btn-inline'>
                    <i class="fa fa-angle-double-left"></i>
                </a>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h3 class="panel-title">
                            <i class="livicon" data-name="user-add" data-size="18" data-c="#fff" data-hc="#fff" data-loop="true"></i> Edit Trade Status Type
                        </h3>
                        <span class="pull-right">
                            <i class="fa fa-fw fa-chevron-up clickable"></i>
                        </span>
                    </div>
                    <div class="panel-body">
                        <div class="form-group">
                            <label for="trade_status_code" class="col-sm-3 control-label">Status Code</label>
                            <div class="col-sm-4">
                                <input id="trade_status_code" name="trade_status_code" value="{!!$trade_status_type->trade_status_code!!}" type="text" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="trade_status_name" class="col-sm-3 control-label">Status Name</label>
                            <div class="col-sm-4">
                                <input id="trade_status_name" name="trade_status_name" value="{!!$trade_status_type->trade_status_name!!}" type="text" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="trade_status_color" class="col-sm-3 control-label">Background Color</label>
                            <div class="col-sm-4">
                                <input id="trade_status_color" name="trade_status_color" type="text" class="form-control my-colorpicker1" value="{{ ($trade_status_type->trade_status_color !='') ? $trade_status_type->trade_status_color : '#ffffff' }}">
                            </div>
                        </div> 
                        <div class="form-group">
                            <label for="trade_status_font_color" class="col-sm-3 control-label">Foreground Color</label>
                            <div class="col-sm-4">
                                <input id="trade_status_font_color" name="trade_status_font_color" type="text" class="form-control my-colorpicker1" value="{{ ($trade_status_type->trade_status_font_color !='') ? $trade_status_type->trade_status_font_color : '#000000' }}">
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
<script type="text/javascript" src="{{asset('assets/vendors/colorpicker/js/bootstrap-colorpicker.min.js')}}" ></script>
<script type="text/javascript">
    $(document).ready(function(){
        $(".my-colorpicker1").colorpicker();
    });
</script>
<!-- end of page level js -->
@stop