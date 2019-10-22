@extends('layouts/default')

{{-- Page title --}}
@section('title')
    Permissions
    @parent
@stop

{{-- page level styles --}}
@section('header_styles')
    <!--page level css -->
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/bootstrapvalidator/css/bootstrapValidator.min.css')}}">

    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/bootstrap-switch/css/bootstrap-switch.css')}}"/>
    <!--end of page level css-->
@stop
@section('content')
<section class="content-header">
    <h1>Create Permission</h1>
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
            Permissions
        </li>
    </ol>
</section>
<section class="content">
    <form id="permissionForm"  action = '{!!url("hase_permission")!!}' method = 'POST' class="form-horizontal" role="form">
        <div class="row">
            <div class="col-md-12">
                <button type="submit" name="submitBtn" value="Save" class='btn btn-primary btn-inline'>Save</button>
                <button type="submit" name="submitBtn" value="SaveClose" class = 'btn btn-primary btn-inline'>Save  &amp; close</button>
                <a href="{!!url("hase_permission")!!}" class='btn btn-primary btn-inline'>
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
                            <i class="fa fa-fw fa-crosshairs"></i> Permission Form
                        </h3>
                    </div>
                    <div class="panel-body">
                        <input type = 'hidden' name = '_token' value = '{{Session::token()}}'>

                        <div class="bs-example">
                            <ul class="nav nav-tabs" style="margin-bottom: 15px;">
                                <li class="active">
                                    <a href="#permission" data-toggle="tab">Permission Detail</a>
                                </li>
                            </ul>
                            <div id="myTabContent" class="tab-content">
                                <div class="tab-pane fade active in" id="permission">
                                    <div class="form-group">
                                        <label for="Name" class="col-sm-3 control-label">
                                            Name
                                            <span class="help-block">
                                                Permissions name are made up of (Domain.Context):
                                                Domain - Typically the application domain name (e.g. Admin, Main, Module).
                                                Context - The controller class name (e.g. Menus, Orders, Locations, or Settings).
                                            </span>
                                        </label>
                                        <div class="col-sm-6">
                                            <input id="name" name="name" type="text" class="form-control">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="Action" class="col-sm-3 control-label">
                                            Action
                                            <span class="help-block">
                                                The permitted action (Access, Manage, Add, Delete)
                                            </span>
                                        </label>
                                        <div class="col-sm-6">
                                            <div class="btn-group btn-group-toggle btn-group-7" data-toggle="buttons">
                                                <label class="btn btn-default ">
                                                   <input type="checkbox" name="action[]" value="access">Access
                                                </label>
                                                <label class="btn btn-default ">
                                                   <input type="checkbox" name="action[]" value="manage" >Manage
                                                </label>
                                                <label class="btn btn-default ">
                                                   <input type="checkbox" name="action[]" value="add" >Add
                                                </label>
                                                <label class="btn btn-default ">
                                                   <input type="checkbox" name="action[]" value="delete">Delete
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="Description" class="col-sm-3 control-label">Description</label>
                                        <div class="col-sm-6">
                                            <textarea id="description" name="description" class="form-control"></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="Status" class="col-sm-3 control-label">Status</label>
                                        <div class="col-sm-6">
                                            <div class="make-switch" data-on="danger" data-off="default">
                                                <input id="status" name="status" type="checkbox" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
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

<script type="text/javascript" src="{{asset('assets/js/custom_js/HasePermission.js')}}"></script>
@stop