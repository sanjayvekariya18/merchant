@extends('layouts/default')
{{-- Page title --}}
@section('title')
    Transactions Code
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
    <h1>Create Transactions Code</h1>
    <ol class="breadcrumb">
        <li>
            <a href="index ">
                <i class="fa fa-fw fa-home"></i> Dashboard
            </a>
        </li>
        <li class="active">
            Transactions Code
        </li>
    </ol>
</section>
<section class="content">
    <form id="transactions_code" method='POST' action='{!!url("transactions_code")!!}' class="form-horizontal">
        <input type='hidden' name='_token' value='{{Session::token()}}'>
        <div class="row">
            <div class="col-md-12">
                <button type="submit" name="submitBtn" value="Save" class='btn btn-primary btn-inline'>Save</button>
                <button type="submit" name="submitBtn" value="SaveClose" class= 'btn btn-primary btn-inline'>Save &amp; Close</button>
                <a href="{{url('transactions_code')}}" class='btn btn-primary btn-inline'>
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
                            <i class="livicon" data-name="user-add" data-size="18" data-c="#fff" data-hc="#fff" data-loop="true"></i> Add New Transactions Code
                        </h3>
                        <span class="pull-right">
                            <i class="fa fa-fw fa-chevron-up clickable"></i>
                        </span>
                    </div>
                    <div class="panel-body">
                        <div class="form-group">
                            <label for="code_random" class="col-sm-2 control-label">Random Code</label>
                            <div class="col-sm-5">
                                <input id="code_random" name="code_random" type="text" class="form-control">
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