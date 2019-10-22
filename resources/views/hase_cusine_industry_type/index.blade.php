@extends('layouts/default')
{{-- Page title --}}
@section('title')
    {!! $labels[0] !!}
@stop

{{-- page level styles --}}
@section('header_styles')
    <!--page level css -->
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datatables/css/dataTables.bootstrap.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/select2/css/select2.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/select2/css/select2-bootstrap.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/bootstrapvalidator/css/bootstrapValidator.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/bootstrap-switch/css/bootstrap-switch.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/jasny-bootstrap/css/jasny-bootstrap.css')}}">

    <script src="{{asset('assets/js/custom_js/jquery-2.1.3.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('assets/js/custom_js/jquery.bootpag.min.js')}}"></script>
    <!--end of page level css-->
    <style type="text/css">
        .tab-pane
        {
            border-right: 1px solid #ddd;
            border-bottom: 1px solid #ddd;
            border-left: 1px solid #ddd;
        }

        span.select2-container {
            z-index:10050;
        }
    </style>
@stop
@section('content')
<?php use Illuminate\Support\Facades\Request; ?>
<section class="content-header">
    <h1>{!! $labels[0] !!}</h1>
    <ol class="breadcrumb">
        <li>
            <a href="index ">
                <i class="fa fa-fw fa-home"></i> Dashboard
            </a>
        </li>
        <li>
            <a href="#"> {!! $labels[2] !!}</a>
        </li>
        <li class="active">
            {!! $labels[1] !!}
        </li>
    </ol>
</section>
<section class="content">
    
    <br>
    <section class="content p-l-r-15">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h3 class="panel-title">
                            <i class="fa fa-fw fa-crosshairs"></i> {!! $labels[1] !!} Details
                        </h3>
                    </div>
                    <div class="panel-body">
                        <input type = 'hidden' name = '_token' value = '{{Session::token()}}'>
                        <input type="hidden" name="requestUrl" id="requestUrl" value="{!!url(Request::segment(1))!!}">
                        <div class="bs-example">
                            <ul class="nav nav-tabs">
                                <?php if(in_array('access', $stylepermissions)) : ?>
                                    <li class="active">
                                        <a href="#cusineType" data-toggle="tab">{!! $labels[1] !!} Tag</a>
                                    </li>
                                <?php endif; ?>
                                <?php if(in_array('access', $categorypermissions)) : ?>
                                    <li>
                                        <a href="#categoryType" data-toggle="tab">Category Tag</a>
                                    </li>
                                <?php endif; ?>
                                <?php if(in_array('access', $categoryoptionpermissions)) : ?>
                                    <li>
                                        <a href="#optionType" data-toggle="tab">Option Tag</a>
                                    </li>
                                <?php endif; ?>
                            </ul>
                            <div id="myTabContent" class="tab-content">
                                <?php if(in_array('access', $stylepermissions)) : ?>
                                    <div class="tab-pane fade active in" id="cusineType">
                                        <div class="row">
                                            
                                            <div class="col-md-12">
                                                <div class="panel panel-primary" style="border: none;">
                                                    <input id="getStyleRow" type="hidden" value='{!!url("hase_retail_style_type")!!}/getRowStyle' />
                                                    <input id="getParentStyle" type="hidden" value='{!!url("hase_retail_style_type")!!}/getParentStyle' />
                                                    <div class="panel-body">
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <?php if(in_array('add', $stylepermissions)) : ?>
                                                                    
                                                                    <a class = 'btn btn-primary' href='#' data-toggle="modal" data-target="#createStyle">
                                                                        Create New {!! $labels[1] !!}
                                                                    </a>
                                                                <?php endif; ?>
                                                            </div>
                                                        </div>
                                                        <br>
                                                        <div class="table-responsive">
                                                            <table class = "table table-bordered" id="styleTable">
                                                                <thead>
                                                                    <?php if(in_array('manage', $stylepermissions) || in_array('delete', $stylepermissions)) : ?>
                                                                        <th>Actions</th>
                                                                    <?php endif; ?>
                                                                    <th>ID</th>
                                                                    <th>Name</th>
                                                                    <th>Parent</th>
                                                                    <th>Priority</th>
                                                                    <th>Image</th>
                                                                </thead>
                                                                <tbody>
                                                                    @foreach($hase_merchant_retail_style_types as $hase_merchant_retail_style_type) 
                                                                    <tr id="{!!$hase_merchant_retail_style_type->style_type_id!!}">
                                                                        <?php if(in_array('manage', $stylepermissions) || in_array('delete', $stylepermissions)) : ?>
                                                                            <td>
                                                                                <?php if(in_array('manage', $stylepermissions)) : ?>
                                                                                    <a href='#' data-url='{!!url("hase_retail_style_type")!!}/{!!$hase_merchant_retail_style_type->style_type_id!!}/update' type-id="{!!$hase_merchant_retail_style_type->style_type_id!!}" class="editStyle" >
                                                                                        <i class = 'fa fa-fw fa-pencil text-primary actions_icon' title="Edit {!! $labels[1] !!} Tag"></i>
                                                                                    </a>
                                                                                    
                                                                                <?php endif; ?>
                                                                                <?php if(in_array('delete', $stylepermissions)) : ?>
                                                                                    <a href='#' data-url = '{!!url("hase_retail_style_type")!!}/{!!$hase_merchant_retail_style_type->style_type_id!!}/delete' type-id="{!!$hase_merchant_retail_style_type->style_type_id!!}" class="deleteStyle"><i class = 'fa fa-fw fa-times text-danger actions_icon' title="Delete Style"></i></a>
                                                                                <?php endif; ?>
                                                                            </td>
                                                                        <?php endif; ?>
                                                                        <td class="style_type_id">{!!$hase_merchant_retail_style_type->style_type_id!!}</td>
                                                                        <td class="style_name">{!!$hase_merchant_retail_style_type->style_name!!}</td>
                                                                        <td class="parent_id">
                                                                            @foreach($hase_merchant_retail_style_types as $hase_merchant_retail_style_type1)
                                                                                @if($hase_merchant_retail_style_type->style_parent_id == $hase_merchant_retail_style_type1->style_type_id)
                                                                                    {!!$hase_merchant_retail_style_type1->style_name!!}    
                                                                                @endif
                                                                            @endforeach 
                                                                        </td>
                                                                        <td class="style_priority">{!!$hase_merchant_retail_style_type->style_priority!!}</td>
                                                                        <td class="style_image">
                                                                            <?php
                                                                            $merchantTypeImageUrl = parse_url($hase_merchant_retail_style_type->style_image);
                                                                            ?>
                                                                            @if(isset($merchantTypeImageUrl['scheme']))
                                                                                @if($merchantTypeImageUrl['scheme'] == 'https' || $merchantTypeImageUrl['scheme'] == 'http')
                                                                                   <img src="{!!$hase_merchant_retail_style_type->style_image!!}" style="width: 80px; height: 40px;"/>
                                                                                @endif
                                                                            @else
                                                                                @if($hase_merchant_retail_style_type->style_image != '' && file_exists(public_path(env('image_dir_path').$hase_merchant_retail_style_type->style_image)))
                                                                                    <img src="{{asset(env('image_dir_path').$hase_merchant_retail_style_type->style_image)}}" style="width: 80px; height: 40px;"/>
                                                                                @else
                                                                                    <img src="{{asset(env('image_dir_path').'no_photo.png')}}" style="width: 80px; height: 40px;"/>
                                                                                @endif
                                                                            @endif
                                                                        </td>
                                                                    </tr>
                                                                    @endforeach 
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <!-- /.delete modal-dialog -->
                                                <div class="modal fade" id="deleteStyle" tabindex="-1" role="dialog" aria-labelledby="Heading" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                                                <h4 class="modal-title custom_align" id="Heading">Delete Styles</h4>
                                                            </div>
                                                            <div class="modal-body">
                                                                <form id="delete_style_form" method = 'POST' action = '{!!url(Request::segment(1))!!}' class="form-horizontal" enctype="multipart/form-data">
                                                                    <input type = 'hidden' name = '_token' value = '{{Session::token()}}'>
                                                                    <div class="alert alert-warning">
                                                                        <span class="glyphicon glyphicon-warning-sign"></span> Are you sure you want to
                                                                        delete this Styles ?
                                                                    </div>
                                                                    <button type="submit" class="btn btn-success" id="saveStyle" >
                                                                        <span class="glyphicon glyphicon-ok-sign"></span> Yes
                                                                    </button>
                                                                    <button type="button" class="btn btn-danger" data-dismiss="modal">
                                                                        <span class="glyphicon glyphicon-remove"></span> No
                                                                    </button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                        <!-- /.modal-content -->
                                                    </div>
                                                </div>
                                                <!-- /.delete modal-dialog -->
                                                <!-- /.edit modal-dialog -->
                                                <div class="modal fade" data-backdrop="static" data-keyboard="false" id="editStyle" role="dialog" aria-labelledby="Heading" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="preloader">
                                                               <div class="loader_img">
                                                                   <img src="{{asset('assets/img/loader.gif')}}" alt="loading..." height="64" width="64">
                                                               </div>
                                                           </div>
                                                            <div class="modal-header">
                                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                                                <h4 class="modal-title custom_align" id="Heading">Edit Style</h4>
                                                            </div>
                                                            <div class="modal-body">
                                                                <form id="edit_style_form" method = 'POST' action = '{!!url(Request::segment(1))!!}' class="form-horizontal" enctype="multipart/form-data">
                                                                    <input type = 'hidden' name = '_token' value = '{{Session::token()}}'>
                                                                    <input type="hidden" name="merchant_type_id" id="merchant_type" value="{!!$merchantType!!}">
                                                                    <div class="panel panel-primary">
                                                                        <div class="panel-heading">
                                                                            <h3 class="panel-title">
                                                                                <i class="livicon" data-name="user-add" data-size="18" data-c="#fff" data-hc="#fff" data-loop="true"></i> Edit Style Item
                                                                            </h3>
                                                                        </div>
                                                                        <div class="panel-body">
                                                                            <div class="row">
                                                                                <div class="form-group">
                                                                                    <label for="style_name" class="col-sm-4 control-label">Name</label>
                                                                                    <div class="col-sm-5">
                                                                                        <input id="style_name" name="style_name" type="text" class="form-control required">
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="row">
                                                                                <div class="form-group">
                                                                                    <label for="parent_id" class="col-sm-4 control-label">Parent</label>
                                                                                    <div class="col-sm-5">
                                                                                        <select name="style_parent_id" id="parent_id" class="select21 form-control ">
                                                                                            <option></option>
                                                                                        </select>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="row">
                                                                                <div class="form-group">
                                                                                    <label for="style_priority" class="col-sm-4 control-label">Select Order
                                                                                    <span class="help-block" style="font-size: 82%;">This is the order used when we select the pulldown in tagged</span>
                                                                                    </label>
                                                                                    <div class="col-sm-5">
                                                                                        <input id="style_priority" name="style_priority" type="text" value="999999" class="form-control required">
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            
                                                                            <div class="row">
                                                                                <div class="form-group">
                                                                                    <label for="style_image" class="col-sm-4 control-label">Image
                                                                                    </label>
                                                                                    <div class="col-sm-5">
                                                                                        <div class="fileinput fileinput-new" data-provides="fileinput">
                                                                                            <div class="fileinput-new thumbnail"
                                                                                                style="width: 200px; height: 200px;">
                                                                                                <img src="{{asset(env('image_dir_path').'no_photo.png')}}" alt="profile pic" class="profile_pic">
                                                                                            </div>
                                                                                            <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 200px;">
                                                                                                <img src="{{asset(env('image_dir_path').'no_photo.png')}}" alt="profile pic" class="profile_pic">
                                                                                            </div>
                                                                                            <div class="fileinput-filename" style="display: block !important;width: 30%;word-wrap: break-word;"></div>
                                                                                            <div>
                                                                                                <span class="btn btn-default btn-file">
                                                                                                    <span class="fileinput-new">Select image</span>
                                                                                                    <span class="fileinput-exists">Change</span>
                                                                                                    <input id="style_image" name="style_image" type="file" class="form-control"/>
                                                                                                </span>
                                                                                                <a href="#" class="btn btn-danger fileinput-exists" data-dismiss="fileinput">Remove</a>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="form-group">
                                                                                <label for="style image Url" class="col-sm-4 control-label">Image Url</label>
                                                                                <div class="col-sm-5">
                                                                                    <input id="live_image_url" name = "live_image_url" type="text" class="form-control"> 
                                                                                </div>
                                                                            </div>
                                                                            <div class="row">
                                                                                <div class="form-group">
                                                                                    <label for="style_image_compact" class="col-sm-4 control-label">Image Compact
                                                                                    </label>
                                                                                    <div class="col-sm-5">
                                                                                        <div class="fileinput fileinput-new" data-provides="fileinput">
                                                                                            <div class="fileinput-new thumbnail" style="width: 200px; height: 200px;">
                                                                                                <img src="{{asset(env('image_dir_path').'no_photo.png')}}" alt="profile pic" class="profile_pic">
                                                                                            </div>
                                                                                            <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 200px;"></div>
                                                                                            <div class="fileinput-filename" style="display: block !important;width: 30%;word-wrap: break-word;"></div>
                                                                                            <div>
                                                                                                <span class="btn btn-default btn-file">
                                                                                                    <span class="fileinput-new">Select image</span>
                                                                                                    <span class="fileinput-exists">Change</span>
                                                                                                    <input id="style_image_compact" name="style_image_compact" type="file" class="form-control"/>
                                                                                                </span>
                                                                                                <a href="#" class="btn btn-danger fileinput-exists" data-dismiss="fileinput">Remove</a>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="form-group">
                                                                                <label for="style image Compact Url" class="col-sm-4 control-label">Image Compact Url</label>
                                                                                <div class="col-sm-5">
                                                                                    <input id="live_image_compact_url" name = "live_image_compact_url" type="text" class="form-control"> 
                                                                                </div>
                                                                            </div>  
                                                                        </div>
                                                                    </div>
                                                                    <button type="submit" class="btn btn-success" id="saveStyle" >
                                                                        <span class="glyphicon glyphicon-ok-sign"></span> Save
                                                                    </button>
                                                                    <button type="button" class="btn btn-danger" data-dismiss="modal">
                                                                        <span class="glyphicon glyphicon-remove"></span> cancel
                                                                    </button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                        <!-- /.modal-content -->
                                                    </div>
                                                </div>
                                                <!-- /.edit modal-dialog -->
                                                <!-- /.create modal-dialog -->
                                                <div class="modal fade" data-backdrop="static" data-keyboard="false" id="createStyle" role="dialog" aria-labelledby="Heading" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="preloader">
                                                               <div class="loader_img">
                                                                   <img src="{{asset('assets/img/loader.gif')}}" alt="loading..." height="64" width="64">
                                                               </div>
                                                           </div>
                                                            <div class="modal-header">
                                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                                                <h4 class="modal-title custom_align" id="Heading">Add style</h4>
                                                            </div>
                                                            <div class="modal-body">
                                                                <form id="create_style_form" method = 'POST' action='{!!url("hase_retail_style_type")!!}' class="form-horizontal" enctype="multipart/form-data">
                                                                    <input type = 'hidden' name = '_token' value = '{{Session::token()}}'>
                                                                    <input type="hidden" name="merchant_type" id="merchant_type" value="{!!$merchantType!!}">
                                                                    <div class="panel panel-primary">
                                                                        <div class="panel-heading">
                                                                            <h3 class="panel-title">
                                                                                <i class="livicon" data-name="user-add" data-size="18" data-c="#fff" data-hc="#fff" data-loop="true"></i> Create Style Item
                                                                            </h3>
                                                                        </div>
                                                                        <div class="panel-body">
                                                                            <div class="row">
                                                                                <div class="form-group">
                                                                                    <label for="style_name" class="col-sm-4 control-label">Name</label>
                                                                                    <div class="col-sm-5">
                                                                                        <input id="style_name" name="style_name" type="text" class="form-control required">
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="row">
                                                                                <div class="form-group">
                                                                                    <label for="parent_id" class="col-sm-4 control-label">Parent</label>
                                                                                    <div class="col-sm-5">
                                                                                        <select name="style_parent_id" id="parent_id" class="select21 form-control">
                                                                                            <option></option>
                                                                                            @foreach($hase_merchant_retail_style_types as $hase_merchant_retail_style_type)
                                                                                                <option value="{{$hase_merchant_retail_style_type['style_type_id']}}">{{$hase_merchant_retail_style_type['style_name']}}</option>
                                                                                            @endforeach
                                                                                        </select>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            
                                                                            <div class="row">
                                                                                <div class="form-group">
                                                                                    <label for="style_priority" class="col-sm-4 control-label">Select Order
                                                                                    <span class="help-block" style="font-size: 82%;">This is the order used when we select the pulldown in tagged</span>
                                                                                    </label>
                                                                                    <div class="col-sm-5">
                                                                                        <input id="style_priority" name="style_priority" value="999999" type="text" class="form-control required">
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        
                                                                            <div class="row">
                                                                                <div class="form-group">
                                                                                    <label for="style_image" class="col-sm-4 control-label">Image
                                                                                    </label>
                                                                                    <div class="col-sm-5">
                                                                                        <div class="fileinput fileinput-new" data-provides="fileinput">
                                                                                            <div class="fileinput-new thumbnail"
                                                                                                style="width: 200px; height: 200px;">
                                                                                                <img src="{{asset(env('image_dir_path').'no_photo.png')}}" alt="profile pic" class="profile_pic">
                                                                                            </div>
                                                                                            <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 200px;"></div>
                                                                                            <div class="fileinput-filename" style="display: block !important;width: 30%;word-wrap: break-word;"></div>
                                                                                            <div>
                                                                                                <span class="btn btn-default btn-file">
                                                                                                    <span class="fileinput-new">Select image</span>
                                                                                                    <span class="fileinput-exists">Change</span>
                                                                                                    <input id="style_image" name="style_image" type="file" class="form-control"/>
                                                                                                </span>
                                                                                                <a href="#" class="btn btn-danger fileinput-exists" data-dismiss="fileinput">Remove</a>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="form-group">
                                                                                <label for="style image Url" class="col-sm-4 control-label">Image Url</label>
                                                                                <div class="col-sm-5">
                                                                                    <input id="live_image_url" name = "live_image_url" type="text" class="form-control"> 
                                                                                </div>
                                                                            </div>

                                                                            <div class="row">
                                                                                <div class="form-group">
                                                                                    <label for="style_image_compact" class="col-sm-4 control-label">Image Compact
                                                                                    </label>
                                                                                    <div class="col-sm-5">
                                                                                        <div class="fileinput fileinput-new" data-provides="fileinput">
                                                                                            <div class="fileinput-new thumbnail" style="width: 200px; height: 200px;">
                                                                                                <img src="{{asset(env('image_dir_path').'no_photo.png')}}" alt="profile pic" class="profile_pic">
                                                                                            </div>
                                                                                            <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 200px;"></div>
                                                                                            <div class="fileinput-filename" style="display: block !important;width: 30%;word-wrap: break-word;"></div>
                                                                                            <div>
                                                                                                <span class="btn btn-default btn-file">
                                                                                                    <span class="fileinput-new">Select image</span>
                                                                                                    <span class="fileinput-exists">Change</span>
                                                                                                    <input id="style_image_compact" name="style_image_compact" type="file" class="form-control"/>
                                                                                                </span>
                                                                                                <a href="#" class="btn btn-danger fileinput-exists" data-dismiss="fileinput">Remove</a>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="form-group">
                                                                                <label for="style image Compact Url" class="col-sm-4 control-label">Image Compact Url</label>
                                                                                <div class="col-sm-5">
                                                                                    <input id="live_image_compact_url" name = "live_image_compact_url" type="text" class="form-control"> 
                                                                                </div>
                                                                            </div>    
                                                                        </div>
                                                                    </div>
                                                                    <button type="submit" class="btn btn-success" id="saveStyle" >
                                                                        <span class="glyphicon glyphicon-ok-sign"></span> Save
                                                                    </button>
                                                                    <button type="button" class="btn btn-danger" data-dismiss="modal">
                                                                        <span class="glyphicon glyphicon-remove"></span> cancel
                                                                    </button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                        <!-- /.modal-content -->
                                                    </div>
                                                </div>
                                                <!-- /.create modal-dialog -->
                                            </div>

                                        </div>
                                        <!-- row-->
                                    </div>
                                <?php endif; ?>
                                <?php if(in_array('access', $categorypermissions)) : ?>
                                    <div class="tab-pane fade" id="categoryType">
                                        <div class="row">
                                            
                                            <div class="col-md-12">
                                                <div class="panel panel-primary" style="border: none;">
                                                    <input id="getCategoryRow" type="hidden" value='{!!url("hase_retail_category_type")!!}/getRowCategory' />
                                                    <input id="getParentCategory" type="hidden" value='{!!url("hase_retail_category_type")!!}/getParentCategory' />
                                                    <div class="panel-body">
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <?php if(in_array('add', $categorypermissions)) : ?>
                                                                    <a class = 'btn btn-primary' href='#' data-toggle="modal" data-target="#createCategory">
                                                                        Create New Category
                                                                    </a>
                                                                <?php endif; ?>
                                                            </div>
                                                        </div>
                                                        <br>
                                                        <div class="table-responsive">
                                                            <table class = "table table-bordered" id="categoryTable">
                                                                <thead>
                                                                    <?php if(in_array('manage', $categorypermissions) || in_array('delete', $categorypermissions)) : ?>
                                                                        <th>Actions</th>
                                                                    <?php endif; ?>
                                                                    <th>ID</th> 
                                                                    <th>Name</th>
                                                                    <th>Parent</th>
                                                                    <th>Priority</th>
                                                                    <th>Image</th>
                                                                </thead>
                                                                <tbody id="category-content">
                                                                    @foreach($hase_merchant_retail_category_types as $hase_merchant_retail_category_type) 
                                                                        <tr id="{!!$hase_merchant_retail_category_type->category_type_id!!}">
                                                                            <?php if(in_array('manage', $categorypermissions) || in_array('delete', $categorypermissions)) : ?>
                                                                                <td>
                                                                                    <?php if(in_array('manage', $categorypermissions)) : ?>
                                                                                        <a href='#' data-url='{!!url("hase_retail_category_type")!!}/{!!$hase_merchant_retail_category_type->category_type_id!!}/update' type-id="{!!$hase_merchant_retail_category_type->category_type_id!!}" class="editCategory" >
                                                                                            <i class = 'fa fa-fw fa-pencil text-primary actions_icon' title="Edit Category"></i>
                                                                                        </a>
                                                                                    <?php endif; ?>
                                                                                    <?php if(in_array('delete', $categorypermissions)) : ?>
                                                                                        <a href='#' data-url = '{!!url("hase_retail_category_type")!!}/{!!$hase_merchant_retail_category_type->category_type_id!!}/delete' type-id="{!!$hase_merchant_retail_category_type->category_type_id!!}" class="deleteCategory"><i class = 'fa fa-fw fa-times text-danger actions_icon' title="Delete Category"></i></a>
                                                                                    <?php endif; ?>
                                                                                </td>
                                                                            <?php endif; ?>
                                                                            <td class="type_id">{!!$hase_merchant_retail_category_type->category_type_id!!}</td>
                                                                            <td class="category_name">{!!$hase_merchant_retail_category_type->category_name!!}</td>
                                                                            <td class="parent_id">
                                                                                @foreach($hase_merchant_retail_category_types as $hase_merchant_retail_category_type1)
                                                                                    @if($hase_merchant_retail_category_type->category_parent_id == $hase_merchant_retail_category_type1->category_type_id)
                                                                                        {!!$hase_merchant_retail_category_type1->category_name!!}    
                                                                                    @endif
                                                                                @endforeach 
                                                                            </td>
                                                                            <td class="category_priority">
                                                                                {!!$hase_merchant_retail_category_type->category_priority!!}</td>
                                                                            <td class="category_image">
                                                                                <?php
                                                                                $categoryTypeImageUrl = parse_url($hase_merchant_retail_category_type->category_image);
                                                                                ?>
                                                                                @if(isset($categoryTypeImageUrl['scheme']))
                                                                                    @if($categoryTypeImageUrl['scheme'] == 'https' || $categoryTypeImageUrl['scheme'] == 'http')
                                                                                       <img src="{!!$hase_merchant_retail_category_type->category_image!!}" style="width: 80px; height: 40px;"/>
                                                                                    @endif
                                                                                @else
                                                                                    @if($hase_merchant_retail_category_type->category_image != '' && file_exists(public_path(env('image_dir_path').$hase_merchant_retail_category_type->category_image)))
                                                                                        <img src="{{asset(env('image_dir_path').$hase_merchant_retail_category_type->category_image)}}" style="width: 80px; height: 40px;"/>
                                                                                    @else
                                                                                        <img src="{{asset(env('image_dir_path').'no_photo.png')}}" style="width: 80px; height: 40px;"/>
                                                                                    @endif
                                                                                @endif
                                                                            </td>
                                                                        </tr>
                                                                    @endforeach 
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                        <?php
                                                        $totalCategoryPage = $retailCategoryCount/25;
                                                        ?>
                                                        <div id="category-selection"></div>
                                                <script>
                                                    var requestUrl = $("#requestUrl").val();
                                                    var token = $('input[name="_token"]').val();
                                                    $('#category-selection').bootpag({
                                                        total: <?php echo ceil($totalCategoryPage); ?>,
                                                        maxVisible: 10,
                                                        leaps: true,
                                                        firstLastUse: true,
                                                        first: '←',
                                                        last: '→',
                                                    }).on("page", function(eventData, currentPage) {
                                                        $.ajax({
                                                            type:'POST',
                                                            data:{_token:token,page_id:currentPage},
                                                            dataType:"html",
                                                            url : requestUrl+"/getCategoryType",
                                                            error:function(xhr,status,error) {
                                                                console.log(error);
                                                            },
                                                            success:function(categoryType,status,xhr) {
                                                                $("#category-content").html(categoryType);
                                                            }
                                                        });
                                                    });
                                                </script>
                                                    </div>
                                                </div>

                                                 <!-- /.delete modal-dialog -->
                                                <div class="modal fade" id="deleteCategory" tabindex="-1" role="dialog" aria-labelledby="Heading" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                                                <h4 class="modal-title custom_align" id="Heading">Delete Category</h4>
                                                            </div>
                                                            <div class="modal-body">
                                                                <form id="delete_category_form" method = 'POST' action = '{!!url(Request::segment(1))!!}' class="form-horizontal" enctype="multipart/form-data">
                                                                    <input type = 'hidden' name = '_token' value = '{{Session::token()}}'>
                                                                    <div class="alert alert-warning">
                                                                        <span class="glyphicon glyphicon-warning-sign"></span> Are you sure you want to
                                                                        delete this Category ?
                                                                    </div>
                                                                    <button type="submit" class="btn btn-success" id="saveCategory" >
                                                                        <span class="glyphicon glyphicon-ok-sign"></span> Yes
                                                                    </button>
                                                                    <button type="button" class="btn btn-danger" data-dismiss="modal">
                                                                        <span class="glyphicon glyphicon-remove"></span> No
                                                                    </button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                        <!-- /.modal-content -->
                                                    </div>
                                                </div>
                                                <!-- /.delete modal-dialog -->
                                                <!-- /.edit modal-dialog -->
                                                <div class="modal fade" data-backdrop="static" data-keyboard="false" id="editCategory" role="dialog" aria-labelledby="Heading" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="preloader">
                                                               <div class="loader_img">
                                                                   <img src="{{asset('assets/img/loader.gif')}}" alt="loading..." height="64" width="64">
                                                               </div>
                                                           </div>
                                                            <div class="modal-header">
                                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                                                <h4 class="modal-title custom_align" id="Heading">Edit Category</h4>
                                                            </div>
                                                            <div class="modal-body">
                                                                <form id="edit_category_form" method = 'POST' action = '{!!url(Request::segment(1))!!}' class="form-horizontal" enctype="multipart/form-data">
                                                                    <input type = 'hidden' name = '_token' value = '{{Session::token()}}'>
                                                                    <input type="hidden" name="merchant_type_id" id="merchant_type" value="{!!$merchantType!!}">
                                                                    <div class="panel panel-primary">
                                                                        <div class="panel-heading">
                                                                            <h3 class="panel-title">
                                                                                <i class="livicon" data-name="user-add" data-size="18" data-c="#fff" data-hc="#fff" data-loop="true"></i> Edit Category Item
                                                                            </h3>
                                                                        </div>
                                                                        <div class="panel-body">
                                                                            <div class="row">
                                                                                <div class="form-group">
                                                                                    <label for="category_name" class="col-sm-4 control-label">Name</label>
                                                                                    <div class="col-sm-5">
                                                                                        <input id="category_name" name="category_name" type="text" class="form-control required">
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="row">
                                                                                <div class="form-group">
                                                                                    <label for="parent_id" class="col-sm-4 control-label">Parent</label>
                                                                                    <div class="col-sm-5">
                                                                                        <select name="category_parent_id" id="parent_id" class="select21 form-control">
                                                                                            <option></option>
                                                                                        </select>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            
                                                                            <div class="row">
                                                                                <div class="form-group">
                                                                                    <label for="category_priority" class="col-sm-4 control-label">Select Order
                                                                                    <span class="help-block" style="font-size: 82%;">This is the order used when we select the pulldown in tagged</span>
                                                                                    </label>
                                                                                    <div class="col-sm-5">
                                                                                        <input id="category_priority" name="category_priority" type="text" value="999999" class="form-control required">
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        
                                                                            <div class="row">
                                                                                <div class="form-group">
                                                                                    <label for="category_image" class="col-sm-4 control-label">Image
                                                                                    </label>
                                                                                    <div class="col-sm-5">
                                                                                        <div class="fileinput fileinput-new" data-provides="fileinput">
                                                                                            <div class="fileinput-new thumbnail"
                                                                                                style="width: 200px; height: 200px;">
                                                                                                <img src="{{asset(env('image_dir_path').'no_photo.png')}}" alt="profile pic" class="profile_pic">
                                                                                            </div>
                                                                                            <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 200px;">
                                                                                                <img src="{{asset(env('image_dir_path').'no_photo.png')}}" alt="profile pic" class="profile_pic">
                                                                                            </div>
                                                                                            <div class="fileinput-filename" style="display: block !important;width: 30%;word-wrap: break-word;"></div>
                                                                                            <div>
                                                                                                <span class="btn btn-default btn-file">
                                                                                                    <span class="fileinput-new">Select image</span>
                                                                                                    <span class="fileinput-exists">Change</span>
                                                                                                    <input id="category_image" name="category_image" type="file" class="form-control"/>
                                                                                                </span>
                                                                                                <a href="#" class="btn btn-danger fileinput-exists" data-dismiss="fileinput">Remove</a>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="form-group">
                                                                                <label for="category image Url" class="col-sm-4 control-label">Image Url</label>
                                                                                <div class="col-sm-5">
                                                                                    <input id="live_image_url" name = "live_image_url" type="text" class="form-control"> 
                                                                                </div>
                                                                            </div>

                                                                            <div class="row">
                                                                                <div class="form-group">
                                                                                    <label for="category_image_compact" class="col-sm-4 control-label">Image Compact
                                                                                    </label>
                                                                                    <div class="col-sm-5">
                                                                                        <div class="fileinput fileinput-new" data-provides="fileinput">
                                                                                            <div class="fileinput-new thumbnail" style="width: 200px; height: 200px;">
                                                                                                <img src="{{asset(env('image_dir_path').'no_photo.png')}}" alt="profile pic" class="profile_pic">
                                                                                            </div>
                                                                                            <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 200px;"></div>
                                                                                            <div class="fileinput-filename" style="display: block !important;width: 30%;word-wrap: break-word;"></div>
                                                                                            <div>
                                                                                                <span class="btn btn-default btn-file">
                                                                                                    <span class="fileinput-new">Select image</span>
                                                                                                    <span class="fileinput-exists">Change</span>
                                                                                                    <input id="category_image_compact" name="category_image_compact" type="file" class="form-control"/>
                                                                                                </span>
                                                                                                <a href="#" class="btn btn-danger fileinput-exists" data-dismiss="fileinput">Remove</a>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div> 
                                                                            <div class="form-group">
                                                                                <label for="category image Url" class="col-sm-4 control-label">Image Compact Url</label>
                                                                                <div class="col-sm-5">
                                                                                    <input id="live_image_compact_url" name = "live_image_compact_url" type="text" class="form-control"> 
                                                                                </div>
                                                                            </div>   
                                                                        </div>
                                                                    </div>
                                                                    <button type="submit" class="btn btn-success" id="saveCategory" >
                                                                        <span class="glyphicon glyphicon-ok-sign"></span> Save
                                                                    </button>
                                                                    <button type="button" class="btn btn-danger" data-dismiss="modal">
                                                                        <span class="glyphicon glyphicon-remove"></span> cancel
                                                                    </button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                        <!-- /.modal-content -->
                                                    </div>
                                                </div>
                                                <!-- /.edit modal-dialog -->
                                                <!-- /.create modal-dialog -->
                                                <div class="modal fade" data-backdrop="static" data-keyboard="false" id="createCategory" role="dialog" aria-labelledby="Heading" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="preloader">
                                                               <div class="loader_img">
                                                                   <img src="{{asset('assets/img/loader.gif')}}" alt="loading..." height="64" width="64">
                                                               </div>
                                                           </div>
                                                            <div class="modal-header">
                                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                                                <h4 class="modal-title custom_align" id="Heading">Add style</h4>
                                                            </div>
                                                            <div class="modal-body">
                                                                <form id="create_category_form" method = 'POST' action='{!!url("hase_retail_category_type")!!}' class="form-horizontal" enctype="multipart/form-data">
                                                                    <input type = 'hidden' name = '_token' value = '{{Session::token()}}'>
                                                                    <input type="hidden" name="merchant_type" id="merchant_type" value="{!!$merchantType!!}">
                                                                    <div class="panel panel-primary">
                                                                        <div class="panel-heading">
                                                                            <h3 class="panel-title">
                                                                                <i class="livicon" data-name="user-add" data-size="18" data-c="#fff" data-hc="#fff" data-loop="true"></i> Create Category Item
                                                                            </h3>
                                                                        </div>
                                                                        <div class="panel-body">
                                                                            <div class="row">
                                                                                <div class="form-group">
                                                                                    <label for="style_name" class="col-sm-4 control-label">Name</label>
                                                                                    <div class="col-sm-5">
                                                                                        <input id="category_name" name="category_name" type="text" class="form-control required">
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="row">
                                                                                <div class="form-group">
                                                                                    <label for="parent_id" class="col-sm-4 control-label">Parent</label>
                                                                                    <div class="col-sm-5">
                                                                                        <select name="category_parent_id" id="parent_id" class="select21 form-control">
                                                                                            <option></option>
                                                                                            @foreach($hase_merchant_retail_category_types as $hase_merchant_retail_category_type)
                                                                                                <option value="{{$hase_merchant_retail_category_type['category_type_id']}}">{{$hase_merchant_retail_category_type['category_name']}}</option>
                                                                                            @endforeach
                                                                                        </select>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            
                                                                            <div class="row">
                                                                                <div class="form-group">
                                                                                    <label for="category_priority" class="col-sm-4 control-label">Select Order
                                                                                    <span class="help-block" style="font-size: 82%;">This is the order used when we select the pulldown in tagged</span>
                                                                                    </label>
                                                                                    <div class="col-sm-5">
                                                                                        <input id="category_priority" name="category_priority" type="text" value="999999" class="form-control required">
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        
                                                                            <div class="row">
                                                                                <div class="form-group">
                                                                                    <label for="category_image" class="col-sm-4 control-label">Image
                                                                                    </label>
                                                                                    <div class="col-sm-5">
                                                                                        <div class="fileinput fileinput-new" data-provides="fileinput">
                                                                                            <div class="fileinput-new thumbnail"
                                                                                                style="width: 200px; height: 200px;">
                                                                                                <img src="{{asset(env('image_dir_path').'no_photo.png')}}" alt="profile pic" class="profile_pic">
                                                                                            </div>
                                                                                            <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 200px;"></div>
                                                                                            <div class="fileinput-filename" style="display: block !important;width: 30%;word-wrap: break-word;"></div>
                                                                                            <div>
                                                                                                <span class="btn btn-default btn-file">
                                                                                                    <span class="fileinput-new">Select image</span>
                                                                                                    <span class="fileinput-exists">Change</span>
                                                                                                    <input id="category_image" name="category_image" type="file" class="form-control"/>
                                                                                                </span>
                                                                                                <a href="#" class="btn btn-danger fileinput-exists" data-dismiss="fileinput">Remove</a>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="form-group">
                                                                                <label for="category image Url" class="col-sm-4 control-label">Image Url</label>
                                                                                <div class="col-sm-5">
                                                                                    <input id="live_image_url" name = "live_image_url" type="text" class="form-control"> 
                                                                                </div>
                                                                            </div>

                                                                            <div class="row">
                                                                                <div class="form-group">
                                                                                    <label for="category_image_compact" class="col-sm-4 control-label">Image Compact
                                                                                    </label>
                                                                                    <div class="col-sm-5">
                                                                                        <div class="fileinput fileinput-new" data-provides="fileinput">
                                                                                            <div class="fileinput-new thumbnail" style="width: 200px; height: 200px;">
                                                                                                <img src="{{asset(env('image_dir_path').'no_photo.png')}}" alt="profile pic" class="profile_pic">
                                                                                            </div>
                                                                                            <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 200px;"></div>
                                                                                            <div class="fileinput-filename" style="display: block !important;width: 30%;word-wrap: break-word;"></div>
                                                                                            <div>
                                                                                                <span class="btn btn-default btn-file">
                                                                                                    <span class="fileinput-new">Select image</span>
                                                                                                    <span class="fileinput-exists">Change</span>
                                                                                                    <input id="category_image_compact" name="category_image_compact" type="file" class="form-control"/>
                                                                                                </span>
                                                                                                <a href="#" class="btn btn-danger fileinput-exists" data-dismiss="fileinput">Remove</a>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="form-group">
                                                                                <label for="category image Compact Url" class="col-sm-4 control-label">Image Compact Url</label>
                                                                                <div class="col-sm-5">
                                                                                    <input id="live_image_compact_url" name = "live_image_compact_url" type="text" class="form-control"> 
                                                                                </div>
                                                                            </div>   
                                                                        </div>
                                                                    </div>
                                                                    <button type="submit" class="btn btn-success" id="saveCategory" >
                                                                        <span class="glyphicon glyphicon-ok-sign"></span> Save
                                                                    </button>
                                                                    <button type="button" class="btn btn-danger" data-dismiss="modal">
                                                                        <span class="glyphicon glyphicon-remove"></span> cancel
                                                                    </button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                        <!-- /.modal-content -->
                                                    </div>
                                                </div>
                                                <!-- /.create modal-dialog -->
                                            </div>
                                        </div>
                                        <!-- row-->
                                    </div>
                                <?php endif; ?>
                                <?php if(in_array('access', $categoryoptionpermissions)) : ?>
                                    <div class="tab-pane fade" id="optionType">
                                        <div class="row">
                                            
                                            <div class="col-md-12">
                                                <div class="panel panel-primary" style="border: none;">
                                                    <input id="getOptionRow" type="hidden" value='{!!url("hase_retail_option_type")!!}/getRowOption' />
                                                    <div class="panel-body">
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <?php if(in_array('add', $categoryoptionpermissions)) : ?>
                                                                    <a class = 'btn btn-primary' href='#' data-toggle="modal" data-target="#createOption">
                                                                        Create New Option
                                                                    </a>
                                                                <?php endif; ?>
                                                            </div>
                                                        </div>
                                                        <div class="table-responsive">
                                                            <table class = "table table-bordered" id="optionTable">
                                                                <thead>
                                                                    <?php if(in_array('manage', $categoryoptionpermissions) || in_array('delete', $categoryoptionpermissions)) : ?>
                                                                        <th>Actions</th>
                                                                    <?php endif; ?>
                                                                    <th>ID</th>
                                                                    <th>Name</th>
                                                                    <th>Image</th>
                                                                </thead>
                                                                <tbody>
                                                                    @foreach($hase_merchant_retail_category_options as $hase_merchant_retail_category_option) 
                                                                    <tr id="{!!$hase_merchant_retail_category_option->category_option_type_id!!}">
                                                                        <?php if(in_array('manage', $categoryoptionpermissions) || in_array('delete', $categorypermissions)) : ?>
                                                                            <td>
                                                                                <?php if(in_array('manage', $categoryoptionpermissions)) : ?>
                                                                                    <a href='#' data-url='{!!url("hase_retail_option_type")!!}/{!!$hase_merchant_retail_category_option->category_option_type_id!!}/update' type-id="{!!$hase_merchant_retail_category_option->category_option_type_id!!}" class="editoption" >
                                                                                        <i class = 'fa fa-fw fa-pencil text-primary actions_icon' title="Edit option"></i>
                                                                                <?php endif; ?>
                                                                                <?php if(in_array('delete', $categoryoptionpermissions)) : ?>
                                                                                    <a href='#' data-url = '{!!url("hase_retail_option_type")!!}/{!!$hase_merchant_retail_category_option->category_option_type_id!!}/delete' type-id="{!!$hase_merchant_retail_category_option->category_option_type_id!!}" class="deleteOption"><i class = 'fa fa-fw fa-times text-danger actions_icon' title="Delete Option"></i></a>
                                                                                <?php endif; ?>
                                                                            </td>
                                                                        <?php endif; ?>
                                                                        <td class="option_type_id">{!!$hase_merchant_retail_category_option->category_option_type_id!!}</td>
                                                                        <td class="option_name">{!!$hase_merchant_retail_category_option->option_name!!}</td>
                                                                        <td class="option_image">
                                                                            <?php
                                                                            $categoryOptionTypeImageUrl = parse_url($hase_merchant_retail_category_option->option_image);
                                                                            ?>
                                                                            @if(isset($categoryOptionTypeImageUrl['scheme']))
                                                                                @if($categoryOptionTypeImageUrl['scheme'] == 'https' || $categoryOptionTypeImageUrl['scheme'] == 'http')
                                                                                   <img src="{!!$hase_merchant_retail_category_option->option_image!!}" style="width: 80px; height: 40px;"/>
                                                                                @endif
                                                                            @else
                                                                                @if($hase_merchant_retail_category_option->option_image != '' && file_exists(public_path(env('image_dir_path').$hase_merchant_retail_category_option->option_image)))
                                                                                    <img src="{{asset(env('image_dir_path').$hase_merchant_retail_category_option->option_image)}}" style="width: 80px; height: 40px;"/>
                                                                                @else
                                                                                    <img src="{{asset(env('image_dir_path').'no_photo.png')}}" style="width: 80px; height: 40px;"/>
                                                                                @endif
                                                                            @endif
                                                                        </td>
                                                                    </tr>
                                                                    @endforeach 
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- /. delete modal-dialog -->
                                                <div class="modal fade" id="deleteOption" tabindex="-1" role="dialog" aria-labelledby="Heading" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                                                <h4 class="modal-title custom_align" id="Heading">Delete Options</h4>
                                                            </div>
                                                            <div class="modal-body">
                                                                <form id="delete_option_form" method = 'POST' action = '{!!url(Request::segment(1))!!}' class="form-horizontal" enctype="multipart/form-data">
                                                                    <input type = 'hidden' name = '_token' value = '{{Session::token()}}'>
                                                                    <div class="alert alert-warning">
                                                                        <span class="glyphicon glyphicon-warning-sign"></span> Are you sure you want to
                                                                        delete this Option ?
                                                                    </div>
                                                                    <button type="submit" class="btn btn-success" id="saveStyle" >
                                                                        <span class="glyphicon glyphicon-ok-sign"></span> Yes
                                                                    </button>
                                                                    <button type="button" class="btn btn-danger" data-dismiss="modal">
                                                                        <span class="glyphicon glyphicon-remove"></span> No
                                                                    </button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                        <!-- /.modal-content -->
                                                    </div>
                                                </div>
                                                <!-- /.delete modal-dialog -->
                                                <!-- /.edit modal-dialog -->
                                                <div class="modal fade" data-backdrop="static" data-keyboard="false" id="editOption"  role="dialog" aria-labelledby="Heading" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="preloader">
                                                               <div class="loader_img">
                                                                   <img src="{{asset('assets/img/loader.gif')}}" alt="loading..." height="64" width="64">
                                                               </div>
                                                           </div>
                                                            <div class="modal-header">
                                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                                                <h4 class="modal-title custom_align" id="Heading">Edit Option</h4>
                                                            </div>
                                                            <div class="modal-body">
                                                                <form id="edit_option_form" method = 'POST' action = '{!!url(Request::segment(1))!!}' class="form-horizontal" enctype="multipart/form-data">
                                                                    <input type = 'hidden' name = '_token' value = '{{Session::token()}}'>
                                                                    <input type="hidden" name="merchant_type" id="merchant_type" value="{!!$merchantType!!}">
                                                                    <div class="panel panel-primary">
                                                                        <div class="panel-heading">
                                                                            <h3 class="panel-title">
                                                                                <i class="livicon" data-name="user-add" data-size="18" data-c="#fff" data-hc="#fff" data-loop="true"></i> Edit Option Item
                                                                            </h3>
                                                                        </div>
                                                                        <div class="panel-body">
                                                                            <div class="row">
                                                                                <div class="form-group">
                                                                                    <label for="option_name" class="col-sm-4 control-label">Name</label>
                                                                                    <div class="col-sm-5">
                                                                                        <input id="option_name" name="option_name" type="text" class="form-control required">
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            
                                                                            <div class="row">
                                                                                <div class="form-group">
                                                                                    <label for="option_image" class="col-sm-4 control-label">Image
                                                                                    </label>
                                                                                    <div class="col-sm-5">
                                                                                        <div class="fileinput fileinput-new" data-provides="fileinput">
                                                                                            <div class="fileinput-new thumbnail"
                                                                                                style="width: 200px; height: 200px;">
                                                                                                <img src="{{asset(env('image_dir_path').'no_photo.png')}}" alt="profile pic" class="profile_pic">
                                                                                            </div>
                                                                                            <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 200px;">
                                                                                                <img src="{{asset(env('image_dir_path').'no_photo.png')}}" alt="profile pic" class="profile_pic">
                                                                                            </div>
                                                                                            <div class="fileinput-filename" style="display: block !important;width: 30%;word-wrap: break-word;"></div>
                                                                                            <div>
                                                                                                <span class="btn btn-default btn-file">
                                                                                                    <span class="fileinput-new">Select image</span>
                                                                                                    <span class="fileinput-exists">Change</span>
                                                                                                    <input id="option_image" name="option_image" type="file" class="form-control"/>
                                                                                                </span>
                                                                                                <a href="#" class="btn btn-danger fileinput-exists" data-dismiss="fileinput">Remove</a>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="form-group">
                                                                                <label for="category Option image Url" class="col-sm-4 control-label">Image Url</label>
                                                                                <div class="col-sm-5">
                                                                                    <input id="live_image_url" name = "live_image_url" type="text" class="form-control"> 
                                                                                </div>
                                                                            </div>
                                                                            
                                                                            <div class="row">
                                                                                <div class="form-group">
                                                                                    <label for="option_image_compact" class="col-sm-4 control-label">Image Compact
                                                                                    </label>
                                                                                    <div class="col-sm-5">
                                                                                        <div class="fileinput fileinput-new" data-provides="fileinput">
                                                                                            <div class="fileinput-new thumbnail" style="width: 200px; height: 200px;">
                                                                                                <img src="{{asset(env('image_dir_path').'no_photo.png')}}" alt="profile pic" class="profile_pic">
                                                                                            </div>
                                                                                            <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 200px;"></div>
                                                                                            <div class="fileinput-filename" style="display: block !important;width: 30%;word-wrap: break-word;"></div>
                                                                                            <div>
                                                                                                <span class="btn btn-default btn-file">
                                                                                                    <span class="fileinput-new">Select image</span>
                                                                                                    <span class="fileinput-exists">Change</span>
                                                                                                    <input id="option_image_compact" name="option_image_compact" type="file" class="form-control"/>
                                                                                                </span>
                                                                                                <a href="#" class="btn btn-danger fileinput-exists" data-dismiss="fileinput">Remove</a>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>    
                                                                            <div class="form-group">
                                                                                <label for="category Option image Compact Url" class="col-sm-4 control-label">Image Url</label>
                                                                                <div class="col-sm-5">
                                                                                    <input id="live_image_compact_url" name = "live_image_compact_url" type="text" class="form-control"> 
                                                                                </div>
                                                                            </div>
                                                                            <div class="row">
                                                                                <div class="form-group">
                                                                                    <label for="option_enable" class="col-sm-4 control-label">Status</label>
                                                                                    <div class="col-sm-5">
                                                                                        <input class="switch form-control option_enable" data-on-color="success" data-off-color="danger" data-on-text="Enabled" data-off-text="Disabled" id="option_enable" name="option_enable" type="checkbox" value="0" />
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <button type="submit" class="btn btn-success" id="saveStyle" >
                                                                        <span class="glyphicon glyphicon-ok-sign"></span> Save
                                                                    </button>
                                                                    <button type="button" class="btn btn-danger" data-dismiss="modal">
                                                                        <span class="glyphicon glyphicon-remove"></span> cancel
                                                                    </button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                        <!-- /.modal-content -->
                                                    </div>
                                                </div>
                                                <!-- /.edit modal-dialog -->
                                                <!-- /.create modal-dialog -->
                                                <div class="modal fade" data-backdrop="static" data-keyboard="false" id="createOption" role="dialog" aria-labelledby="Heading" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="preloader">
                                                               <div class="loader_img">
                                                                   <img src="{{asset('assets/img/loader.gif')}}" alt="loading..." height="64" width="64">
                                                               </div>
                                                           </div>
                                                            <div class="modal-header">
                                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                                                <h4 class="modal-title custom_align" id="Heading">Add Option</h4>
                                                            </div>
                                                            <div class="modal-body">
                                                                <form id="create_option_form" method = 'POST' action='{!!url("hase_retail_option_type")!!}' class="form-horizontal" enctype="multipart/form-data">
                                                                    <input type = 'hidden' name = '_token' value = '{{Session::token()}}'>
                                                                    <input type="hidden" name="merchant_type" id="merchant_type" value="{!!$merchantType!!}">
                                                                    <div class="panel panel-primary">
                                                                        <div class="panel-heading">
                                                                            <h3 class="panel-title">
                                                                                <i class="livicon" data-name="user-add" data-size="18" data-c="#fff" data-hc="#fff" data-loop="true"></i> Create Option Item
                                                                            </h3>
                                                                        </div>
                                                                        <div class="panel-body">
                                                                            <div class="row">
                                                                                <div class="form-group">
                                                                                    <label for="option_name" class="col-sm-4 control-label">Name</label>
                                                                                    <div class="col-sm-5">
                                                                                        <input id="option_name" name="option_name" type="text" class="form-control required">
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            
                                                                            <div class="row">
                                                                                <div class="form-group">
                                                                                    <label for="option_image" class="col-sm-4 control-label">Image
                                                                                    </label>
                                                                                    <div class="col-sm-5">
                                                                                        <div class="fileinput fileinput-new" data-provides="fileinput">
                                                                                            <div class="fileinput-new thumbnail"
                                                                                                style="width: 200px; height: 200px;">
                                                                                                <img src="{{asset(env('image_dir_path').'no_photo.png')}}" alt="profile pic" class="profile_pic">
                                                                                            </div>
                                                                                            <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 200px;"></div>
                                                                                            <div class="fileinput-filename" style="display: block !important;width: 30%;word-wrap: break-word;"></div>
                                                                                            <div>
                                                                                                <span class="btn btn-default btn-file">
                                                                                                    <span class="fileinput-new">Select image</span>
                                                                                                    <span class="fileinput-exists">Change</span>
                                                                                                    <input id="option_image" name="option_image" type="file" class="form-control"/>
                                                                                                </span>
                                                                                                <a href="#" class="btn btn-danger fileinput-exists" data-dismiss="fileinput">Remove</a>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="form-group">
                                                                                <label for="category Option image Url" class="col-sm-4 control-label">Image Url</label>
                                                                                <div class="col-sm-5">
                                                                                    <input id="live_image_url" name = "live_image_url" type="text" class="form-control"> 
                                                                                </div>
                                                                            </div>
                                                                            
                                                                            <div class="row">
                                                                                <div class="form-group">
                                                                                    <label for="option_image_compact" class="col-sm-4 control-label">Image Compact
                                                                                    </label>
                                                                                    <div class="col-sm-5">
                                                                                        <div class="fileinput fileinput-new" data-provides="fileinput">
                                                                                            <div class="fileinput-new thumbnail" style="width: 200px; height: 200px;">
                                                                                                <img src="{{asset(env('image_dir_path').'no_photo.png')}}" alt="profile pic" class="profile_pic">
                                                                                            </div>
                                                                                            <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 200px;"></div>
                                                                                            <div class="fileinput-filename" style="display: block !important;width: 30%;word-wrap: break-word;"></div>
                                                                                            <div>
                                                                                                <span class="btn btn-default btn-file">
                                                                                                    <span class="fileinput-new">Select image</span>
                                                                                                    <span class="fileinput-exists">Change</span>
                                                                                                    <input id="option_image_compact" name="option_image_compact" type="file" class="form-control"/>
                                                                                                </span>
                                                                                                <a href="#" class="btn btn-danger fileinput-exists" data-dismiss="fileinput">Remove</a>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="form-group">
                                                                                <label for="category Option image Compact Url" class="col-sm-4 control-label">Image Compact Url</label>
                                                                                <div class="col-sm-5">
                                                                                    <input id="live_image_compact_url" name = "live_image_compact_url" type="text" class="form-control"> 
                                                                                </div>
                                                                            </div>  
                                                                            <div class="row">
                                                                                <div class="form-group">
                                                                                    <label for="option_enable" class="col-sm-4 control-label">Status</label>
                                                                                    <div class="col-sm-5">
                                                                                        <input class="switch form-control option_enable" data-on-color="success" data-off-color="danger" data-on-text="Enabled" data-off-text="Disabled" id="option_enable" name="category_option_enable" type="checkbox" value="0" />
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <button type="submit" class="btn btn-success" id="saveOption" >
                                                                        <span class="glyphicon glyphicon-ok-sign"></span> Save
                                                                    </button>
                                                                    <button type="button" class="btn btn-danger" data-dismiss="modal">
                                                                        <span class="glyphicon glyphicon-remove"></span> cancel
                                                                    </button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                        <!-- /.modal-content -->
                                                    </div>
                                                </div>
                                                <!-- /.create modal-dialog -->
                                            </div>
                                        </div>
                                        <!-- row-->
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</section>
@endsection

{{-- page level scripts --}}
@section('footer_scripts')

<!-- begining of page level js -->

<script type="text/javascript" src="{{asset('assets/vendors/datatables/js/jquery.dataTables.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/datatables/js/dataTables.bootstrap.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/bootstrapvalidator/js/bootstrapValidator.min.js')}}" ></script>

<script type="text/javascript" src="{{asset('assets/vendors/jasny-bootstrap/js/jasny-bootstrap.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/select2/js/select2.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/bootstrap-switch/js/bootstrap-switch.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/js/custom_js/HaseCusineIndustryIndex.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/js/custom_js/HaseRetailStyleType.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/js/custom_js/HaseRetailCategoryOption.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/js/custom_js/HaseRetailCategoryType.js')}}"></script>
<!-- end of page level js -->
@stop