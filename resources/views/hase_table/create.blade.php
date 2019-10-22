@extends('layouts/default')
{{-- Page title --}}
@section('title')
    Create Table
    @parent
@stop

{{-- page level styles --}}
@section('header_styles')
    <!--page level css -->
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/bootstrap-switch/css/bootstrap-switch.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/bootstrapvalidator/css/bootstrapValidator.min.css')}}">
    <!--end of page level css-->
@stop
@section('content')

<section class="content-header">
    <h1>Create Table</h1>
    <ol class="breadcrumb">
        <li>
            <a href="index ">
                <i class="fa fa-fw fa-home"></i> Dashboard
            </a>
        </li>
        <li>
            <a href="#"> Restaurants</a>
        </li>
        <li class="active">
            Create Table
        </li>
    </ol>
</section>
<section class="content">
    <form id="restaurant_tables_form" method='POST' action='{!!url("hase_table")!!}' class="form-horizontal">
        <input type = 'hidden' name = '_token' value = '{{Session::token()}}'>
        <div class="row">
            <div class="col-md-12">
                <button type="submit" name="submitbutton" value="Save" class='btn btn-primary btn-inline'>Save</button>
                <button type="submit" name="submitbutton" value="SaveClose" class= 'btn btn-primary btn-inline'>Save &amp; Close</button>
                <a href="{!!url("hase_table")!!}" class='btn btn-primary btn-inline'>
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
                            <i class="livicon" data-name="user-add" data-size="18" data-c="#fff" data-hc="#fff" data-loop="true"></i> Add New Table
                        </h3>
                        <span class="pull-right">
                            <i class="fa fa-fw fa-chevron-up clickable"></i>
                        </span>
                    </div>
                    <div class="panel-body">
                        <div class="form-group">
                            <label for="seating_name" class="col-sm-3 control-label">Name</label>
                            <div class="col-sm-9">
                                <input id="seating_name" name="seating_name" type="text" class="form-control required">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="min_capacity" class="col-sm-3 control-label">Minimum Capacity</label>
                            <div class="col-sm-9">
                                <input id="min_capacity" name="min_capacity" type="text" class="form-control required">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="max_capacity" class="col-sm-3 control-label">Maximum Capacity</label>
                            <div class="col-sm-9">
                                <input id="max_capacity" name = "max_capacity" type="text" class="form-control required">
                            </div>
                        </div>
                        
                        <input id="autobook" name="autobook" type="hidden" value="0">
                        
                        <div class="form-group">
                            <label for="status" class="col-sm-3 control-label">Status</label>
                            <div class="make-switch col-sm-9" data-on="danger" data-off="default">
                                <input type="checkbox" name="status" value="1" id="status" data-on-text="Enabled" data-off-text="Disabled">
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
<script type="text/javascript" src="{{asset('assets/vendors/bootstrap-switch/js/bootstrap-switch.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/bootstrapvalidator/js/bootstrapValidator.min.js')}}" ></script>
<script type="text/javascript" src="{{asset('assets/js/custom_js/HaseTableCreate.js')}}"></script>
<!-- end of page level js -->
@stop