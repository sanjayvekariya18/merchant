@extends('layouts/default')

{{-- Page title --}}
@section('title')
    Payee
    @parent
@stop

{{-- page level styles --}}
@section('header_styles')
    <!--page level css -->
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/select2/css/select2.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/select2/css/select2-bootstrap.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/bootstrapvalidator/css/bootstrapValidator.min.css')}}">
    <!--end of page level css-->
@stop
@section('content')
<section class="content-header">
    <h1>Create New Payee</h1>
    <ol class="breadcrumb">
        <li>
            <a href="index ">
                <i class="fa fa-fw fa-home"></i> Dashboard
            </a>
        </li>
        
        <li class="active">
            Payee
        </li>
    </ol>
</section>
<section class="content">
    <form id="payeeForm" action='{!!url("payee")!!}' method='POST' class="form-horizontal" role="form" enctype="multipart/form-data">
        <div class="row">
            <div class="col-md-12">
                <button type="submit" name="submitBtn" value="Save" class='btn btn-primary btn-inline'>Save</button>
                <button type="submit" name="submitBtn" value="SaveClose" class = 'btn btn-primary btn-inline'>Save  &amp; close</button>
                <a href="{!!url("payee")!!}" class='btn btn-primary btn-inline'>
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
                            <i class="fa fa-fw fa-crosshairs"></i> Add Payee Detail
                        </h3>
                    </div>
                    <div class="panel-body">
                        <input type='hidden' name='_token' value='{{Session::token()}}'>
                        <div class="form-group">
                            <label for="payee_symbol" class="col-sm-3 control-label">Payee Code</label>
                            <div class="col-sm-4">
                                <input id="payee_symbol" name="payee_symbol" type="text" class="form-control">
                            </div>
                        </div> 
                        <div class="form-group">
                            <label for="payee_name" class="col-sm-3 control-label">Payee Name</label>
                            <div class="col-sm-4">
                                <input id="payee_name" name="payee_name" type="text" class="form-control">
                            </div>
                        </div>
                        <input id="postal_id" name="postal_id" type="hidden" value="0" class="form-control">
                        <!-- <div class="form-group">
                            <label for="postal_name" class="col-sm-3 control-label">Postal</label>
                            <div class="col-sm-4">
                                <select name="postal_id" id="postal_id" class="form-control select21" style="width:100%">
                                    <option></option>
                                    @foreach($allPostal as $postalData)
                                       <option value="{{$postalData->postal_id}}">{{$postalData->postal_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div> -->
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
<script type="text/javascript" src="{{asset('assets/vendors/select2/js/select2.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/bootstrapvalidator/js/bootstrapValidator.min.js')}}" ></script>
<script type="text/javascript" src="{{asset('assets/js/custom_js/PayeeCreate.js')}}"></script>
@stop
