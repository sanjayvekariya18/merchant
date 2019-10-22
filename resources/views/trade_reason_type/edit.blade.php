@extends('layouts/default')
{{-- Page title --}}
@section('title')
    Trade Reason Type
    @parent
@stop

{{-- page level styles --}}
@section('header_styles')
    <!--page level css -->
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/bootstrapvalidator/css/bootstrapValidator.min.css')}}">
    <!--end of page level css-->
@stop
@section('content')

<section class="content-header">
    <h1>Edit Trade Reason Type</h1>
    <ol class="breadcrumb">
        <li>
            <a href="index ">
                <i class="fa fa-fw fa-home"></i> Dashboard
            </a>
        </li>
        <li class="active">
            Trade Reason Type
        </li>
    </ol>
</section>
<section class="content">
    <form id="trade_reason_type" method='POST' action = '{!! url("trade_reason_type")!!}/{!!$trade_reason_type->
        trade_reason_type_id!!}/update' class="form-horizontal">
        <input type = 'hidden' name = '_token' value = '{{Session::token()}}'>
        <div class="row">
            <div class="col-md-12">
                <button type="submit" name="submitBtn" value="Save" class='btn btn-primary btn-inline'>Save</button>
                <button type="submit" name="submitBtn" value="SaveClose" class= 'btn btn-primary btn-inline'>Save &amp; Close</button>
                <a href="{{url('trade_reason_type')}}" class='btn btn-primary btn-inline'>
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
                            <i class="livicon" data-name="user-add" data-size="18" data-c="#fff" data-hc="#fff" data-loop="true"></i> Edit Trade Reason Type
                        </h3>
                        <span class="pull-right">
                            <i class="fa fa-fw fa-chevron-up clickable"></i>
                        </span>
                    </div>
                    <div class="panel-body">
                        <div class="form-group">
                            <label for="trade_reason_type_code" class="col-sm-2 control-label">Reason Code</label>
                            <div class="col-sm-5">
                                <input id="trade_reason_type_code" name="trade_reason_type_code" value="{!!$trade_reason_type->trade_reason_type_code!!}" type="text" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="trade_reason_type_name" class="col-sm-2 control-label">Reason Name</label>
                            <div class="col-sm-5">
                                <input id="trade_reason_type_name" name="trade_reason_type_name" value="{!!$trade_reason_type->trade_reason_type_name!!}" type="text" class="form-control">
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
<!-- end of page level js -->
@stop