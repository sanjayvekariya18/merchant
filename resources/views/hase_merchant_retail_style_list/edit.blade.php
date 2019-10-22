@extends('layouts/default')

{{-- Page title --}}
@section('title')
    {!! $labels[0] !!}
    @parent
@stop

{{-- page level styles --}}
@section('header_styles')

    <!--page level css -->
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/bootstrapvalidator/css/bootstrapValidator.min.css')}}">

    <link href="{{asset('assets/vendors/select2/css/select2.min.css')}}" rel="stylesheet" type="text/css">

    <link href="{{asset('assets/vendors/select2/css/select2-bootstrap.css')}}" rel="stylesheet" type="text/css">

    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/jasny-bootstrap/css/jasny-bootstrap.css')}}">

    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/bootstrap-switch/css/bootstrap-switch.css')}}"/>
    <!--end of page level css-->
@stop
@section('content')
<?php use Illuminate\Support\Facades\Request; ?>
<section class="content-header">
    <h1>Edit Assigned {!! $labels[1] !!}</h1>
    <ol class="breadcrumb">
        <li>
            <a href="index ">
                <i class="fa fa-fw fa-home"></i> Dashboard
            </a>
        </li>
        <li>
            <a href="#">Merchants</a>
        </li>
        <li class="active">
            Edit Assigned {!! $labels[1] !!}
        </li>
    </ol>
</section>
<section class="content">
    <div class="preloader" style="background: none !important; ">
        <div class="loader_img">
            <img src="{{asset('assets/img/loader.gif')}}" alt="loading..." height="64" width="64">
        </div>
    </div>
    <form id="editAssignStylesForm"  action = '{!!url(Request::segment(1))!!}/{!!$hase_merchant_retail_style_list->style_list_id!!}/update' method = 'POST' class="form-horizontal" role="form" enctype="multipart/form-data">
        <input type="hidden" name="requestUrl" id="requestUrl" value="">
        <div class="row">
            <div class="col-md-12">
                <!-- <button type="submit" name="submitBtn" value="Save" class='btn btn-primary btn-inline'>Save</button> -->
                <button type="submit" name="submitBtn" value="SaveClose" class = 'btn btn-primary btn-inline'>Save  &amp; close</button>
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
                            <i class="fa fa-fw fa-crosshairs"></i> {!! $labels[1] !!} Form
                        </h3>
                    </div>
                    <div class="panel-body">
                        
                        <input type = 'hidden' name = '_token' value = '{{Session::token()}}'>
                        <input type="hidden" id="merchant_id" name="merchant_id" value="{!!$merchant_data->merchant_id!!}" >
                        <input type="hidden" id="location_id" name="location_id" value="{!!$location_data->location_id!!}" >
                        <?php if(Session('merchantId') == 0): ?>
                            <div class="form-group">
                                <label class="col-sm-3">Merchant</label>
                                <div class="col-sm-8">
                                    {!!$merchant_data->merchant_name!!}
                                </div>
                            </div>
                        <?php endif; ?>
                        <div class="form-group">
                            <label for="location_id" class="col-sm-3">Location</label>
                            <div class="col-sm-8"> 
                                {!!$location_data->location_name!!}
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="style_type_id" class="col-sm-3 ">
                                {!! $labels[0] !!} Type
                            </label>
                            <div class="col-sm-8">
                                <?php 
                                    $listLimit = ($merchant_data->merchant_type == 8)?3:8;
                                 ?>
                                <select name="style_type_id[]" data-limit="<?php echo $listLimit ?>" id="style_type_id" class="form-control select2" multiple style="width:100%">
                                    @foreach($styleTypes as $styleType)
                                        @if(in_array($styleType->style_type_id,$hase_style_exist['style_type_id']))
                                            <option value="{{$styleType->style_type_id}}" selected>{{$styleType->style_name}}</option>
                                        @else
                                            <option value="{{$styleType->style_type_id}}">{{$styleType->style_name}}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="Priority" class="col-sm-3">Priority</label>
                            <div class="col-sm-8">
                                <input value="{!!$hase_merchant_retail_style_list->
                            priority!!}" id="priority" name="priority" min="0" type="number" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="Status" class="col-sm-3">Status</label>
                            <div class="col-sm-8">
                                <div class="make-switch" data-on="danger" data-off="default">
                                    @if($hase_merchant_retail_style_list->enable)
                                        <input value="{!!$hase_merchant_retail_style_list->
                                        enable!!}" id="enable" name="enable" data-on-text="Enabled" data-on-color="success" data-off-color="danger" data-off-text="Disabled" type="checkbox" checked="true" />
                                    @else
                                        <input value="{!!$hase_merchant_retail_style_list->
                                        enable!!}" id="enable" name="enable" data-on-text="Enabled" data-on-color="success" data-off-color="danger" data-off-text="Disabled" type="checkbox" />
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="panel panel-primary">
                                    <div class="panel-heading">
                                        <h3 class="panel-title">
                                            Set Priorities
                                        </h3>
                                    </div>
                                    <div class="panel-body" id="panelList">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <!-- <div class="row">
                                                    <label class="col-md-6">Style Name</label>
                                                    <label class="col-md-6">Priority</label>
                                                </div> -->
                                                <table class="table table-striped table-border table-sortable" id="table">
                                                    <thead>
                                                        <tr>
                                                            <th>Style Name</th>
                                                            <th>Priority</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($styleTypes as $styleType)
                                                            @if(in_array($styleType->style_type_id,$hase_style_exist['style_type_id']))
                                                                <tr id="table-row{{$styleType->style_type_id}}">
                                                                    <input type="hidden" id="style_type_id" name="styles[{{$styleType->style_type_id}}][style_type_id]" value="{{$styleType->style_type_id}}">
                                                                    <td>        
                                                                        <span id="style_name" name="styles[{{$styleType->style_type_id}}][style_name]" class="form-control">{{$styleType->style_name}}</span>
                                                                    </td>   
                                                                    <td class="form-group">
                                                                        <input type="text" id="priority" name="styles[{{$styleType->style_type_id}}][priority]" class="form-control stylePriorityRequired" placeholder="The style priority field must contain only numbers." value="{{$hase_style_exist['priority'][$styleType->style_type_id]}}">
                                                                    </td>
                                                                </tr>
                                                            @endif
                                                        @endforeach
                                                    </tbody>
                                                </table>
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
    <div class="row" id="demo" style="display: none;">
        <table>
            <tr id="table-row">
                <input type="hidden" id="style_id" name="style_id" />
                <td>        
                    <span id="style_name" name="style_name" class="form-control"></span>
                </td>   
                <td class="form-group">
                    <input type="text" id="priority" name="priority" class="form-control stylePriorityRequired" placeholder="The style priority is required and must contain only numbers">
                </td>
            </tr>
        </table>
    </div>
</section>
@endsection
{{-- page level scripts --}}
@section('footer_scripts')
<!-- begining of page level js -->

<script type="text/javascript" src="{{asset('assets/vendors/select2/js/select2.js')}}"></script>

<script type="text/javascript" src="{{asset('assets/vendors/jasny-bootstrap/js/jasny-bootstrap.js')}}"></script>

<script type="text/javascript" src="{{asset('assets/vendors/bootstrap-switch/js/bootstrap-switch.js')}}"></script>

<script type="text/javascript" src="{{asset('assets/vendors/bootstrapvalidator/js/bootstrapValidator.min.js')}}" ></script>

<script type="text/javascript" src="{{asset('assets/js/custom_js/HaseStylesEdit.js')}}"></script>
@stop