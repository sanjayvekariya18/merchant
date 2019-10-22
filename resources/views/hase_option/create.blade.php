@extends('layouts/default')

{{-- Page title --}}
@section('title')
    Create {!! $labels[2] !!} option
    @parent
@stop

{{-- page level styles --}}
@section('header_styles')
    <!--page level css -->
    
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/bootstrapvalidator/css/bootstrapValidator.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/iCheck/css/all.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/custom_css/wizard.css')}}" >
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/select2/css/select2.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/select2/css/select2-bootstrap.css')}}">
    
    <!--end of page level css-->
@stop
@section('content')
<?php use Illuminate\Support\Facades\Request; ?>
<section class="content-header">
    <h1>Create {!! $labels[0] !!} Option</h1>
    <ol class="breadcrumb">
        <li>
            <a href="index ">
                <i class="fa fa-fw fa-home"></i> Dashboard
            </a>
        </li>
        <li>
            <a href="#"> {!! $labels[1] !!}</a>
        </li>
        <li class="active">
            Create {!! $labels[2] !!} Option
        </li>
    </ol>
</section>
<br>
<section class="content">
    <form method = 'POST' enctype="multipart/form-data" class="form-horizontal bv-form" action='{!!url(Request::segment(1))!!}' id="create_menu_options_form">
        <div class="row">
            <div class="col-md-12">
                <button type="submit" name="submitBtn" value="Save" class='btn btn-primary btn-inline'>Save</button>
                <button type="submit" name="submitBtn" value="SaveClose" class = 'btn btn-primary btn-inline'>Save &amp; close</button>
                <a href="{!!url(Request::segment(1))!!}" class='btn btn-primary btn-inline'>
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
                               data-loop="true"></i> Create {!! $labels[2] !!} Option
                        </h3>
                        <span class="pull-right clickable">
                            <i class="glyphicon glyphicon-chevron-up"></i>
                        </span>
                    </div>
                    <div class="panel-body">
                    
                        <input type = 'hidden' name = '_token' value = '{{Session::token()}}'>
                        <div id="pager_wizard">
                            <ul class="nav nav-tabs">
                                <li>
                                    <a href="#tab1" data-toggle="tab">Details</a>
                                </li>
                                <li>
                                    <a href="#tab2" data-toggle="tab">Option Values</a>
                                </li>
                            </ul>
                            <div class="tab-content " >
                                <div class="tab-pane" id="tab1">
                                    <h2 class="hidden">&nbsp;</h2>
                                    @if(session('merchantId') != 0 )
                                        <div class="form-group">
                                            <label for="location_id" class="col-sm-3 control-label">Location*</label>
                                            <div class="col-sm-5"> 
                                                <select name="location_id" id="location_id" class = "form-control select21">
                                                    <option value="">None</option>
                                                    @foreach($hase_locations as $hase_location)
                                                        <option value="{{$hase_location->location_id}}">{{$hase_location->location_name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    @endif
                                    <div class="form-group">
                                        <label for="option_name" class="col-sm-3 control-label">Option Name*</label>
                                        <div class="col-sm-5">
                                            <input id="option_name" name = "option_name" type="text" class="form-control"> 
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="display_type" class="col-sm-3 control-label">Display Type</label>
                                        <div class="col-sm-5">
                                            <div class="input-group">
                                                <select name="display_type" id="display_type" class = "form-control select21">
                                                    <option value="radio">Radio</option>
                                                    <option value="checkbox">Checkbox</option><option value="select">Select</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="priority" class="col-sm-3 control-label">Priority*</label>
                                        <div class="col-sm-5">
                                            <div class="input-group">
                                                <input id="priority" name = "priority" type="text" class="form-control" value="">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane" id="tab2">
                                    <div class="panel panel-default panel-table">
                                        <div class="table-responsive">
                                            <table class="table table-striped table-border table-sortable" id="table">
                                                <thead>
                                                    <tr>
                                                        <th class="action action-one"></th>
                                                        <th class="action action-one"></th>
                                                        <th>Option Value</th>
                                                        <th>Option Price</th>
                                                        <th class="id">ID</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                                <tfoot>
                                                    <tr id="tfoot">
                                                        <td class="action action-one" colspan="5"><a class="btn btn-primary" onclick="addValue();"><i class="fa fa-plus"></i></a></td>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>    
                                </div>
                            </div>
                        </div>
                        <div id="menuDynamicOptionRow">
                            <table>
                                <tr id="table-row">
                                    <td class="action action-one">
                                        <i class="fa fa-sort handle"></i>
                                    </td>
                                    <td class="action action-one" style="width: 4.33% !important;">
                                        <a class="btn btn-danger" onclick="confirm('This cannot be undone! Are you sure you want to do this?') ? $(this).parent().parent().remove() : false;"><i class="fa fa-times-circle"></i></a>
                                    </td>
                                    <td>        
                                        <input type="text" id="value" name="value" class="form-control optionValueRequired" placeholder="The Option Value field is required.." value="">
                                    </td>   
                                    <td>
                                        <input type="text" id="price" name="price" class="form-control optionPriceRequired" placeholder="The Option Price field must contain only numbers." value="">
                                    </td>   
                                    <td class="id">     
                                        <input type="hidden" id="option_value_id" name="option_value_id" class="form-control" >-
                                    </td>
                                </tr>
                            </table>
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
<script type="text/javascript" src="{{asset('assets/vendors/datatables/js/jquery.dataTables.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/datatables/js/dataTables.bootstrap.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/select2/js/select2.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/bootstrapwizard/js/jquery.bootstrap.wizard.js')}}" ></script>
<script type="text/javascript" src="{{asset('assets/vendors/bootstrapvalidator/js/bootstrapValidator.min.js')}}" ></script>
<script type="text/javascript" src="{{asset('assets/js/custom_js/haseOptionCreate.js')}}"></script>

<!-- end of page level js -->
@stop