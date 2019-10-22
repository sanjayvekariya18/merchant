@extends('layouts/default')

{{-- Page title --}}
@section('title')
    Edit {!! $labels[2] !!}
    @parent
@stop

{{-- page level styles --}}
@section('header_styles')
    <!--page level css -->
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/bootstrap-switch/css/bootstrap-switch.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/jasny-bootstrap/css/jasny-bootstrap.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/select2/css/select2.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/select2/css/select2-bootstrap.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/bootstrapvalidator/css/bootstrapValidator.min.css')}}">
    
    <link href="{{asset('assets/vendors/airdatepicker/css/datepicker.min.css')}}" rel="stylesheet" type="text/css">
    
    <style type="text/css">
        #panelList .row{
            margin-bottom: 5px
        }
    </style>
    <!--end of page level css-->
@stop
@section('content')
<?php use Illuminate\Support\Facades\Request; ?>

<section class="content-header">
    <h1>Edit {!! $labels[2] !!}</h1>
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
            Edit {!! $labels[2] !!}
        </li>
    </ol>
</section>
<br>
<section class="content">
    <div class="preloader" style="background: none !important; ">
        <div class="loader_img">
            <img src="{{asset('assets/img/loader.gif')}}" alt="loading..." height="64" width="64">
        </div>
    </div>
    <form method = 'POST' enctype="multipart/form-data" class="form-horizontal bv-form" role="form" id="edit_menu_form" action = '{!! url(Request::segment(1)) !!}/{!!$hase_menu->product_id!!}/update'> 
            
        <input type="hidden" name="requestUrl" id="requestUrl" value="{!!url(Request::segment(1))!!}">

        <input type="hidden" id="menu_id" value="{{$hase_menu->product_id}}">

        <input type="hidden" name="selectedLocation" id="selectedLocation" value="{{$hase_menu->location_id}}">

        <input type="hidden" name="selectedLocationCity" id="selectedLocationCity" value="{{$hase_menu->location_city_id}}">

        <input type="hidden" id="merchant_id" name="merchant_id" value="{!!$merchantInfo->merchant_id!!}" >
        <input type="hidden" id="identity_id" name="identity_id" value="{!!$hase_menu->identity_id!!}" >

        <input type = 'hidden' name = '_token' value = '{{Session::token()}}'>

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
                               data-loop="true"></i> Edit {!! $labels[2] !!}
                        </h3>
                        <span class="pull-right clickable">
                            <i class="glyphicon glyphicon-chevron-up"></i>
                        </span>
                    </div>

                    <div class="panel-body">
                        @if(!empty($hase_menu_Specials))
                            <input id="menuSpecialStatus" name="menuSpecialStatus" type="hidden" value="{!!$hase_menu_Specials->special_status!!}"  />
                        @else
                            <input id="menuSpecialStatus" name="menuSpecialStatus" type="hidden" value="0"  />
                        @endif

                        <div class="bs-example">
                            <ul class="nav nav-tabs" style="margin-bottom: 15px;">
                                <li class="active">
                                    <a href="#menuTab" data-toggle="tab">{!! $labels[2] !!}</a>
                                </li>
                                <li>
                                    <a href="#cuisineTab" data-toggle="tab">{!! $styleLabels[1] !!} Tagging</a>
                                </li>
                                <li>
                                    <a href="#categoryTab" data-toggle="tab">Category Tagging</a>
                                </li>
                            </ul>
                            <div id="myTabContent" class="tab-content">

                                <div class="tab-pane fade active in" id="menuTab">
                                    <div class="form-group">
                                        <label  class="col-sm-3 control-label">Merchant</label>
                                        <div class="col-sm-8">
                                            {!!$merchantInfo->merchant_name!!}
                                        </div>
                                    </div>
                                    <div id="cityBlock" class="form-group">
                                        <label for="Parent" class="col-sm-3 control-label">
                                            City
                                        </label>
                                        <div class="col-sm-5">
                                            <select name="city_id" id="city_id" class="form-control select2" style="width:100%">
                                                <option></option>
                                                @foreach($hase_location_cities as $hase_location_city)
                                                    @if($hase_location_city->location_city_id == $hase_menu->location_city_id)
                                                        <option value="{{$hase_location_city->location_city_id}}" selected>{{$hase_location_city->city_name}}</option>
                                                    @else
                                                        <option value="{{$hase_location_city->location_city_id}}">{{$hase_location_city->city_name}}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>                                    
                                    <div id="locationBlock" class="form-group">
                                        <label for="location_id" class="col-sm-3 control-label">Location</label>
                                        <div class="col-sm-5"> 
                                            <select name="location_id" id="location_id" class="form-control select21" style="width:100%">
                                                <option></option>
                                                @foreach($hase_locations as $hase_location)
                                                    @if($hase_location->location_id == $hase_menu->location_id)
                                                        <option value="{{$hase_location->location_id}}" selected>{{$hase_location->location_name}}</option>
                                                    @else
                                                        <option value="{{$hase_location->location_id}}">{{$hase_location->location_name}}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="product_name" class="col-sm-3 control-label">{!! $labels[2] !!} Name</label>
                                        <div class="col-sm-5">
                                            <input id="product_name" name = "product_name" type="text" class="form-control" value="{!!$hase_menu->
                                            product_name!!}"> 
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="product_code" class="col-sm-3 control-label">{!! $labels[2] !!} Code</label>
                                        <div class="col-sm-5">
                                            <input id="product_code" name = "product_code" type="text" class="form-control" value="{!!$hase_menu->
                                            product_code!!}"> 
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="product_description" class="col-sm-3 control-label">{!! $labels[2] !!} Description*</label>
                                        <div class="col-sm-5 col-md-5">
                                            <textarea id="product_description" name = "product_description" rows="4" class="form-control resize_vertical"
                                              placeholder="Description">{!!$hase_menu->product_description!!}</textarea>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="base_price" class="col-sm-3 control-label">Original Price</label>
                                        <div class="col-sm-5">
                                            <div class="input-group">
                                                <input id="base_price" name = "base_price" type="text" class="form-control" value="{!!$hase_menu->base_price!!}"> 
                                            <span class="input-group-addon"><i class="fa fa-money"></i></span>
                                            </div>
                                        </div>
                                    </div>                                    
                                    <div class="form-group">
                                        <label for="product_category_id" class="col-sm-3 control-label">Category</label>
                                        <div class="col-sm-5"> 
                                            
                                            <select name="product_category_id" id="product_category_id" class = "form-control select21">

                                                @foreach($hase_categories as $hase_category)
                                                    <?php if( $hase_category->category_type_id == $hase_menu->category_type_id) : ?>
                                                            <option value="{{$hase_category->category_type_id}}" selected>{{$hase_category->category_name}}</option>
                                                    <?php else: ?>
                                                            <option value="{{$hase_category->category_type_id}}">{{$hase_category->category_name}}</option>
                                                    <?php endif; ?>
                                                @endforeach

                                            </select>
                                        </div>
                                    </div>
                                    <?php
                                    $menuLiveImageUrl = parse_url($hase_menu->product_image);
                                    ?>
                                    @if(isset($menuLiveImageUrl['scheme']))
                                        @if($menuLiveImageUrl['scheme'] == 'https' || $menuLiveImageUrl['scheme'] == 'http')
                                            <div class="form-group">
                                                <label for="pic" class="col-sm-3 control-label">Image
                                                <span class="help-block">Select a file to update {!! strtolower($labels[2]) !!} image, otherwise leave blank.</span></label>
                                                <div class="col-sm-5">
                                                    <div class="fileinput fileinput-<?php echo isset($hase_menu->product_image)?'exists':'new' ?>" data-provides="fileinput">
                                                        <div class="fileinput-new thumbnail" style="width: 200px; height: 200px;">
                                                             <img src="{{asset(env('image_dir_path').'no_photo.png')}}" alt="profile pic" class="profile_pic">
                                                        </div>
                                                        <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 200px;">
                                                            <img src="{!!$hase_menu->product_image!!}" alt="profile pic" class="profile_pic">
                                                        </div>
                                                        <div>
                                                            <span class="btn btn-default btn-file">
                                                            <span class="fileinput-new">Select image</span>
                                                            <span class="fileinput-exists">Change</span>
                                                            <input id="product_image" name="product_image" type="file" class="form-control"/>
                                                            </span>
                                                            <a href="#" class="btn btn-danger fileinput-exists"
                                                               data-dismiss="fileinput">Remove</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="image Url" class="col-sm-3 control-label">Image Url</label>
                                                <div class="col-sm-8">
                                                    <input id="live_image_url" name = "live_image_url" type="text" class="form-control" value="{!!$hase_menu->product_image!!}"> 
                                                </div>
                                            </div>
                                        @endif
                                    @else
                                        @if($hase_menu->product_image != "" && file_exists(public_path(env('image_dir_path').$hase_menu->product_image)))
                                            <div class="form-group">
                                                <label for="pic" class="col-sm-3 control-label">Image
                                                <span class="help-block">Select a file to update {!! strtolower($labels[2]) !!} image, otherwise leave blank.</span></label>

                                                <div class="col-sm-5">
                                                    <div class="fileinput fileinput-<?php echo isset($hase_menu->product_image)?'exists':'new' ?>" data-provides="fileinput">
                                                        <div class="fileinput-new thumbnail"
                                                             style="width: 200px; height: 200px;">
                                                             <img src="{{asset(env('image_dir_path').'no_photo.png')}}" alt="profile pic" class="profile_pic">
                                                        </div>
                                                        <div class="fileinput-preview fileinput-exists thumbnail"
                                                             style="max-width: 200px; max-height: 200px;">
                                                            @if($hase_menu->product_image)
                                                                <img src="{{asset(env('image_dir_path').$hase_menu->product_image)}}" alt="profile pic" class="profile_pic">
                                                            @endif
                                                        </div>
                                                        <div class="fileinput-filename" style="display: block !important;">
                                                            {!!$hase_menu->product_image!!}
                                                        </div>
                                                        <div>
                                                                <span class="btn btn-default btn-file">
                                                                <span class="fileinput-new">Select image</span>
                                                                <span class="fileinput-exists">Change</span>
                                                                <input id="product_image" name="product_image" type="file" class="form-control"/>
                                                                </span>
                                                            <a href="#" class="btn btn-danger fileinput-exists"
                                                               data-dismiss="fileinput">Remove</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="image Url" class="col-sm-3 control-label">Image Url</label>
                                                <div class="col-sm-8">
                                                    <input id="live_image_url" name = "live_image_url" type="text" class="form-control"> 
                                                </div>
                                            </div>
                                        @else
                                            <div class="form-group">
                                                <label for="pic" class="col-sm-3 control-label">Image
                                                <span class="help-block">Select a file to update {!! strtolower($labels[2]) !!} image, otherwise leave blank.</span></label>
                                                <div class="col-sm-5">
                                                    <div class="fileinput fileinput-new" data-provides="fileinput">
                                                        <div class="fileinput-new thumbnail" style="width: 200px; height: 200px;">
                                                             <img src="{{asset(env('image_dir_path').'no_photo.png')}}" alt="profile pic" class="profile_pic">
                                                        </div>
                                                        <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 200px;"></div>
                                                        <div>
                                                            <span class="btn btn-default btn-file">
                                                            <span class="fileinput-new">Select image</span>
                                                            <span class="fileinput-exists">Change</span>
                                                            <input id="product_image" name="product_image" type="file" class="form-control"/>
                                                            </span>
                                                            <a href="#" class="btn btn-danger fileinput-exists"
                                                               data-dismiss="fileinput">Remove</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="image Url" class="col-sm-3 control-label">Image Url</label>
                                                <div class="col-sm-8">
                                                    <input id="live_image_url" name = "live_image_url" type="text" class="form-control"> 
                                                </div>
                                            </div>   
                                        @endif
                                    @endif
                                    
                                    <?php
                                    $menuLiveImagecompactUrl = parse_url($hase_menu->product_image_compact);
                                    ?>
                                    @if(isset($menuLiveImagecompactUrl['scheme']))
                                        @if($menuLiveImagecompactUrl['scheme'] == 'https' || $menuLiveImagecompactUrl['scheme'] == 'http')
                                            <div class="form-group">
                                                <label for="pic" class="col-sm-3 control-label">Image compact
                                                <span class="help-block">Select a file to update {!! strtolower($labels[2]) !!} compact image, otherwise leave blank.</span></label>
                                                <div class="col-sm-5">
                                                    <div class="fileinput fileinput-<?php echo isset($hase_menu->product_image_compact)?'exists':'new' ?>" data-provides="fileinput">
                                                        <div class="fileinput-new thumbnail" style="width: 200px; height: 200px;">
                                                            <img src="{{asset(env('image_dir_path').'no_photo.png')}}" alt="profile pic" class="profile_pic">
                                                        </div>
                                                        <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 200px;">
                                                            <img src="{!!$hase_menu->product_image_compact!!}" alt="profile pic" class="profile_pic">
                                                        </div>
                                                        <div>
                                                            <span class="btn btn-default btn-file">
                                                            <span class="fileinput-new">Select image</span>
                                                            <span class="fileinput-exists">Change</span>
                                                            <input id="product_image_compact" name="product_image_compact" type="file" class="form-control"/>
                                                            </span>
                                                            <a href="#" class="btn btn-danger fileinput-exists"
                                                               data-dismiss="fileinput">Remove</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="image Compact Url" class="col-sm-3 control-label">Image Compact Url</label>
                                                <div class="col-sm-8">
                                                    <input id="live_image_compact_url" name = "live_image_compact_url" type="text" class="form-control" value="{!!$hase_menu->product_image_compact!!}"> 
                                                </div>
                                            </div>
                                        @endif
                                    @else
                                        @if($hase_menu->product_image_compact != "" && file_exists(public_path(env('image_dir_path').$hase_menu->product_image_compact)))
                                            <div class="form-group">
                                                <label for="pic" class="col-sm-3 control-label">Image Compact
                                                <span class="help-block">Select a file to update {!! strtolower($labels[2]) !!} compact image, otherwise leave blank.</span></label>

                                                <div class="col-sm-5">
                                                    <div class="fileinput fileinput-<?php echo isset($hase_menu->product_image_compact)?'exists':'new' ?>" data-provides="fileinput">
                                                        <div class="fileinput-new thumbnail"
                                                             style="width: 200px; height: 200px;">
                                                             <img src="{{asset(env('image_dir_path').'no_photo.png')}}" alt="profile pic" class="profile_pic">
                                                        </div>
                                                        <div class="fileinput-preview fileinput-exists thumbnail"
                                                             style="max-width: 200px; max-height: 200px;">
                                                            @if($hase_menu->product_image_compact)
                                                                <img src="{{asset(env('image_dir_path').$hase_menu->product_image_compact)}}" alt="profile pic" class="profile_pic">
                                                            @endif
                                                        </div>
                                                        <div class="fileinput-filename" style="display: block !important;">
                                                            {!!$hase_menu->product_image_compact!!}
                                                        </div>
                                                        <div>
                                                                <span class="btn btn-default btn-file">
                                                                <span class="fileinput-new">Select image</span>
                                                                <span class="fileinput-exists">Change</span>
                                                                <input id="product_image_compact" name="product_image_compact" type="file" class="form-control"/>
                                                                </span>
                                                            <a href="#" class="btn btn-danger fileinput-exists"
                                                               data-dismiss="fileinput">Remove</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="image Compact Url" class="col-sm-3 control-label">Image Compact Url</label>
                                                <div class="col-sm-8">
                                                    <input id="live_image_compact_url" name = "live_image_compact_url" type="text" class="form-control" > 
                                                </div>
                                            </div>
                                        @else
                                            <div class="form-group">
                                                <label for="pic" class="col-sm-3 control-label">Image Compact
                                                <span class="help-block">Select a file to update {!! strtolower($labels[2]) !!} image, otherwise leave blank.</span></label>
                                                <div class="col-sm-5">
                                                    <div class="fileinput fileinput-new" data-provides="fileinput">
                                                        <div class="fileinput-new thumbnail" style="width: 200px; height: 200px;">
                                                             <img src="{{asset(env('image_dir_path').'no_photo.png')}}" alt="profile pic" class="profile_pic">
                                                        </div>
                                                        <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 200px;"></div>
                                                        <div>
                                                            <span class="btn btn-default btn-file">
                                                            <span class="fileinput-new">Select image</span>
                                                            <span class="fileinput-exists">Change</span>
                                                            <input id="product_image_compact" name="product_image_compact" type="file" class="form-control"/>
                                                            </span>
                                                            <a href="#" class="btn btn-danger fileinput-exists"
                                                               data-dismiss="fileinput">Remove</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="image Compact Url" class="col-sm-3 control-label">Image Compact Url</label>
                                                <div class="col-sm-8">
                                                    <input id="live_image_compact_url" name = "live_image_compact_url" type="text" class="form-control"> 
                                                </div>
                                            </div>   
                                        @endif
                                    @endif

                                    @if(!empty($hase_menu_Specials))
                                        <input id="special_id" name="special_id" type="hidden" value="{!!$hase_menu_Specials->product_special_id!!}" />
                                    @endif
                                    <div class="form-group">
                                        <label for="special_status" class="col-sm-3 control-label">Special</label>
                                        <div class="col-sm-5">
                                        @if(!empty($hase_menu_Specials))
                                            <input class="switch" data-on-color="success" data-off-color="danger" data-on-text="Enabled" data-off-text="Disabled" id="special_status" name="special_status" type="checkbox" value="{!!$hase_menu_Specials->special_status!!}" />
                                        @else
                                            <input class="switch" data-on-color="success" data-off-color="danger" data-on-text="Enabled" data-off-text="Disabled" id="special_status" name="special_status" type="checkbox" value="0" />
                                        @endif
                                        </div>
                                    </div>
                                    <div class="specialMenuEnable">
                                        <div class="form-group">
                                            <label for="special_price" class="col-sm-3 control-label">Discount Price</label>
                                            <div class="col-sm-5">
                                                <div class="input-group">
                                                    @if(!empty($hase_menu_Specials))
                                                        <input id="special_price" name = "special_price" type="text" class="form-control" value="{!!$hase_menu_Specials->special_price!!}">
                                                    @else
                                                        <input id="special_price" name = "special_price" type="text" class="form-control" value="0">
                                                    @endif
                                                    <span class="input-group-addon"><i class="fa fa-money"></i></span>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        @if(!empty($hase_menu_Specials))
                                            <?php                                            
                                            $menuLiveSpecialImageUrl = parse_url($hase_menu_Specials->special_image);
                                            ?>
                                            @if(isset($menuLiveSpecialImageUrl['scheme']))
                                                @if($menuLiveSpecialImageUrl['scheme'] == 'https' || $menuLiveSpecialImageUrl['scheme'] == 'http')
                                                    <div class="form-group">
                                                        <label for="pic" class="col-sm-3 control-label">Special Image
                                                        <span class="help-block">Select a file to update {!! strtolower($labels[2]) !!} compact image, otherwise leave blank.</span></label>
                                                        <div class="col-sm-5">
                                                            <div class="fileinput fileinput-<?php echo isset($hase_menu_Specials->special_image)?'exists':'new' ?>" data-provides="fileinput">
                                                                <div class="fileinput-new thumbnail" style="width: 200px; height: 200px;">
                                                                    <img src="{{asset(env('image_dir_path').'no_photo.png')}}" alt="profile pic" class="profile_pic">
                                                                </div>
                                                                <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 200px;">
                                                                    <img src="{!!$hase_menu_Specials->special_image!!}" alt="profile pic" class="profile_pic">
                                                                </div>
                                                                <div>
                                                                    <span class="btn btn-default btn-file">
                                                                    <span class="fileinput-new">Select image</span>
                                                                    <span class="fileinput-exists">Change</span>
                                                                    <input id="special_image" name="special_image" type="file" class="form-control"/>
                                                                    </span>
                                                                    <a href="#" class="btn btn-danger fileinput-exists"
                                                                       data-dismiss="fileinput">Remove</a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="special image Url" class="col-sm-3 control-label">Special Image Url</label>
                                                        <div class="col-sm-8">
                                                            <input id="live_special_image_url" name = "live_special_image_url" type="text" class="form-control" value="{!!$hase_menu_Specials->special_image!!}"> 
                                                        </div>
                                                    </div>
                                                @endif
                                            @else
                                                @if($hase_menu_Specials->special_image != "" && file_exists(public_path(env('image_dir_path').$hase_menu_Specials->special_image)))
                                                    <div class="form-group">
                                                        <label for="pic" class="col-sm-3 control-label">Special Image
                                                        <span class="help-block">Select a file to update {!! strtolower($labels[2]) !!} compact image, otherwise leave blank.</span></label>

                                                        <div class="col-sm-5">
                                                            <div class="fileinput fileinput-<?php echo isset($hase_menu_Specials->special_image)?'exists':'new' ?>" data-provides="fileinput">
                                                                <div class="fileinput-new thumbnail"
                                                                     style="width: 200px; height: 200px;">
                                                                     <img src="{{asset(env('image_dir_path').'no_photo.png')}}" alt="profile pic" class="profile_pic">
                                                                </div>
                                                                <div class="fileinput-preview fileinput-exists thumbnail"
                                                                     style="max-width: 200px; max-height: 200px;">
                                                                    @if($hase_menu_Specials->special_image)
                                                                        <img src="{{asset(env('image_dir_path').$hase_menu_Specials->special_image)}}" alt="profile pic" class="profile_pic" />
                                                                    @endif
                                                                </div>
                                                                <div class="fileinput-filename" style="display: block !important;">
                                                                    {!!$hase_menu_Specials->special_image!!}
                                                                </div>
                                                                <div>
                                                                        <span class="btn btn-default btn-file">
                                                                        <span class="fileinput-new">Select image</span>
                                                                        <span class="fileinput-exists">Change</span>
                                                                        <input id="special_image" name="special_image" type="file" class="form-control"/>
                                                                        </span>
                                                                    <a href="#" class="btn btn-danger fileinput-exists"
                                                                       data-dismiss="fileinput">Remove</a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="special image Url" class="col-sm-3 control-label">Special Image Url</label>
                                                        <div class="col-sm-8">
                                                            <input id="live_special_image_url" name = "live_special_image_url" type="text" class="form-control" > 
                                                        </div>
                                                    </div>
                                                @else
                                                    <div class="form-group">
                                                        <label for="pic" class="col-sm-3 control-label">Special Image
                                                        <span class="help-block">Select a file to update {!! strtolower($labels[2]) !!} compact image, otherwise leave blank.</span></label>

                                                        <div class="col-sm-5">
                                                            <div class="fileinput fileinput-new" data-provides="fileinput">
                                                                <div class="fileinput-new thumbnail"
                                                                     style="width: 200px; height: 200px;">
                                                                     <img src="{{asset(env('image_dir_path').'no_photo.png')}}" alt="profile pic" class="profile_pic">
                                                                </div>
                                                                <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 200px;"></div>
                                                                <div>
                                                                        <span class="btn btn-default btn-file">
                                                                        <span class="fileinput-new">Select image</span>
                                                                        <span class="fileinput-exists">Change</span>
                                                                        <input id="special_image" name="special_image" type="file" class="form-control"/>
                                                                        </span>
                                                                    <a href="#" class="btn btn-danger fileinput-exists"
                                                                       data-dismiss="fileinput">Remove</a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="special image Url" class="col-sm-3 control-label">Special Image Url</label>
                                                        <div class="col-sm-8">
                                                            <input id="live_special_image_url" name = "live_special_image_url" type="text" class="form-control"> 
                                                        </div>
                                                    </div>   
                                                @endif
                                            @endif

                                            <?php
                                            $menuLiveSpecialImageCompactUrl = parse_url($hase_menu_Specials->special_image_compact);
                                            ?>
                                            @if(isset($menuLiveSpecialImageCompactUrl['scheme']))
                                                @if($menuLiveSpecialImageCompactUrl['scheme'] == 'https' || $menuLiveSpecialImageCompactUrl['scheme'] == 'http')
                                                    <div class="form-group">
                                                        <label for="pic" class="col-sm-3 control-label">Special Image Compact
                                                        <span class="help-block">Select a file to update {!! strtolower($labels[2]) !!} compact image, otherwise leave blank.</span></label>
                                                        <div class="col-sm-5">
                                                            <div class="fileinput fileinput-<?php echo isset($hase_menu_Specials->special_image_compact)?'exists':'new' ?>" data-provides="fileinput">
                                                                <div class="fileinput-new thumbnail" style="width: 200px; height: 200px;">
                                                                    <img src="{{asset(env('image_dir_path').'no_photo.png')}}" alt="profile pic" class="profile_pic">
                                                                </div>
                                                                <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 200px;">
                                                                    <img src="{!!$hase_menu_Specials->special_image_compact!!}" alt="profile pic" class="profile_pic">
                                                                </div>
                                                                <div>
                                                                    <span class="btn btn-default btn-file">
                                                                    <span class="fileinput-new">Select image</span>
                                                                    <span class="fileinput-exists">Change</span>
                                                                    <input id="special_image_compact" name="special_image_compact" type="file" class="form-control"/>
                                                                    </span>
                                                                    <a href="#" class="btn btn-danger fileinput-exists"
                                                                       data-dismiss="fileinput">Remove</a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="special image Compact Url" class="col-sm-3 control-label">Special Image Compact Url</label>
                                                        <div class="col-sm-8">
                                                            <input id="live_special_image_compact_url" name = "live_special_image_compact_url" type="text" class="form-control" value="{!!$hase_menu_Specials->special_image_compact!!}"> 
                                                        </div>
                                                    </div>
                                                @endif
                                            @else
                                                @if($hase_menu_Specials->special_image_compact != "" && file_exists(public_path(env('image_dir_path').$hase_menu_Specials->special_image_compact)))
                                                    <div class="form-group">
                                                        <label for="pic" class="col-sm-3 control-label">Special Image Compact
                                                        <span class="help-block">Select a file to update {!! strtolower($labels[2]) !!} compact image, otherwise leave blank.</span></label>

                                                        <div class="col-sm-5">
                                                            <div class="fileinput fileinput-<?php echo isset($hase_menu_Specials->special_image_compact)?'exists':'new' ?>" data-provides="fileinput">
                                                                <div class="fileinput-new thumbnail"
                                                                     style="width: 200px; height: 200px;">
                                                                     <img src="{{asset(env('image_dir_path').'no_photo.png')}}" alt="profile pic" class="profile_pic">
                                                                </div>
                                                                <div class="fileinput-preview fileinput-exists thumbnail"
                                                                     style="max-width: 200px; max-height: 200px;">
                                                                        <img src="{{asset(env('image_dir_path').$hase_menu_Specials->special_image_compact)}}" alt="profile pic" class="profile_pic">
                                                                </div>
                                                                <div class="fileinput-filename" style="display: block !important;">
                                                                    {!!$hase_menu_Specials->special_image_compact!!}
                                                                </div>
                                                                <div>
                                                                        <span class="btn btn-default btn-file">
                                                                        <span class="fileinput-new">Select image</span>
                                                                        <span class="fileinput-exists">Change</span>
                                                                        <input id="special_image_compact" name="special_image_compact" type="file" class="form-control"/>
                                                                        </span>
                                                                    <a href="#" class="btn btn-danger fileinput-exists"
                                                                       data-dismiss="fileinput">Remove</a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="special image Compact Url" class="col-sm-3 control-label">Special Image Compact Url</label>
                                                        <div class="col-sm-8">
                                                            <input id="live_special_image_compact_url" name = "live_special_image_compact_url" type="text" class="form-control" > 
                                                        </div>
                                                    </div>
                                                @else
                                                    <div class="form-group">
                                                        <label for="pic" class="col-sm-3 control-label">Special Image Compact
                                                        <span class="help-block">Select a file to update {!! strtolower($labels[2]) !!} compact image, otherwise leave blank.</span></label>

                                                        <div class="col-sm-5">
                                                            <div class="fileinput fileinput-<?php echo isset($hase_menu_Specials->special_image_compact)?'exists':'new' ?>" data-provides="fileinput">
                                                                <div class="fileinput-new thumbnail"
                                                                     style="width: 200px; height: 200px;">
                                                                     <img src="{{asset(env('image_dir_path').'no_photo.png')}}" alt="profile pic" class="profile_pic">
                                                                </div>
                                                                <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 200px;"></div>
                                                                <div>
                                                                        <span class="btn btn-default btn-file">
                                                                        <span class="fileinput-new">Select image</span>
                                                                        <span class="fileinput-exists">Change</span>
                                                                        <input id="special_image_compact" name="special_image_compact" type="file" class="form-control"/>
                                                                        </span>
                                                                    <a href="#" class="btn btn-danger fileinput-exists"
                                                                       data-dismiss="fileinput">Remove</a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="special image Compact Url" class="col-sm-3 control-label">Special Image Compact Url</label>
                                                        <div class="col-sm-8">
                                                            <input id="live_special_image_compact_url" name = "live_special_image_compact_url" type="text" class="form-control"> 
                                                        </div>
                                                    </div>   
                                                @endif
                                            @endif
                                        @endif
                                        <div class="form-group">
                                            <label for="special_details" class="col-sm-3 control-label">Offer Details</label>
                                            <div class="input-group col-sm-5">
                                                @if(isset($hase_menu_Specials->special_details))
                                                    <input id="special_details" name = "special_details" type="text" class="form-control" value="{!!$hase_menu_Specials->special_details!!}">
                                                @else
                                                    <input id="special_details" name = "special_details" type="text" class="form-control">
                                                @endif
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="special_terms" class="col-sm-3 control-label">Offer Terms and Conditions</label>
                                            <div class="input-group col-sm-5">
                                                @if(isset($hase_menu_Specials->special_terms))
                                                    <input id="special_terms" name = "special_terms" type="text" class="form-control" value="{!!$hase_menu_Specials->special_terms!!}">
                                                @else
                                                    <input id="special_terms" name = "special_terms" type="text" class="form-control" >
                                                @endif
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="special_url" class="col-sm-3 control-label">Offer URL</label>
                                            <div class="input-group col-sm-5">
                                                @if(isset($hase_menu_Specials->special_url))
                                                    <input id="special_url" name = "special_url" type="text" class="form-control" value="{!!$hase_menu_Specials->special_url!!}">
                                                @else
                                                    <input id="special_url" name = "special_url" type="text" class="form-control" >
                                                @endif
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="start_date" class="col-sm-3 control-label">Offer Start Date</label>
                                            <div class="input-group col-sm-5">
                                                @if(!empty($hase_menu_Specials) && $hase_menu_Specials->special_begin_date != 0)
                                                    <input id="start_date" value="{!!date('m/d/Y',strtotime($hase_menu_Specials->special_begin_date))!!}" name="start_date" type="text" class="form-control pull-left" data-language='en' />
                                                @else
                                                    <input id="start_date" name="start_date" type="text" class="form-control pull-left" data-language='en' />
                                                @endif
                                                <div class="input-group-addon">
                                                    <i class="fa fa-fw fa-calendar"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="end_date" class="col-sm-3 control-label">Offer End Date</label>
                                            <div class="input-group col-sm-5">
                                                @if(!empty($hase_menu_Specials) && $hase_menu_Specials->special_expire_date != 0)
                                                    <input id="end_date" value="{!!date('m/d/Y',strtotime($hase_menu_Specials->special_expire_date))!!}" name="end_date" type="text" class="form-control pull-left" data-language='en' />
                                                @else
                                                    <input id="end_date" name="end_date" type="text" class="form-control pull-left" data-language='en' />
                                                @endif
                                                <div class="input-group-addon">
                                                    <i class="fa fa-fw fa-calendar"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="tab-pane fade" id="cuisineTab">
                                    <div class="form-group">
                                        <label for="style_type_id" class="col-sm-3 ">
                                            {!! $labels[0] !!} Type
                                        </label>
                                        <div class="col-sm-8">
                                            <?php 
                                                $listLimit = ($merchantInfo->merchant_type == 8)?3:8;
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
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="panel panel-primary">
                                                <div class="panel-heading">
                                                    <h3 class="panel-title">
                                                        Set Priorities
                                                    </h3>
                                                </div>
                                                <div class="panel-body">
                                                    <div class="row">
                                                        <div class="col-md-12">
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
                                                                                <input type="hidden" id="style_id" name="styles[{{$styleType->style_type_id}}][style_id]" value="{{$styleType->style_type_id}}">
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

                                <div class="tab-pane fade" id="categoryTab">
                                    <div class="form-group">
                                        <label for="category_type_id" class="col-sm-3 ">
                                            Retail Category Type
                                        </label>
                                        <div class="col-sm-8">
                                            <select id="category_type_id" class="form-control select2" multiple style="width:100%">
                                                @foreach($categoryTypes as $categoryType)
                                                    <option value="{{$categoryType->category_type_id}}">{{$categoryType->category_name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="panel panel-primary">
                                                <div class="panel-heading">
                                                    <h3 class="panel-title">
                                                        Select Category Options
                                                    </h3>
                                                </div>
                                                <div class="panel-body" id="panelList">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="row">
                                                                <label class="col-md-2">Category Type</label>
                                                                <label class="col-md-5">Category Option</label>
                                                                <label class="col-md-2">Priority</label>
                                                                <label class="col-md-3">Status</label>
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
                    </div>
                </div>
            </div>
        </div>
    </form>
    <div class="row" id="categoryDemo" style="display: none;">
        <div class="col-md-2">
            <label id="category_type_name"></label>
            <input type="hidden" name="category_type_id" value="" id="categoryTypeId">
        </div>
        <div class="col-sm-5">
            <select name="category_option_type_id" id="category_option_type_id" class="form-control" multiple="" style="width: 100%">
                @foreach($hase_options as $hase_option)
                    <option value="{{$hase_option->category_option_type_id}}">{{$hase_option->option_name}}</option>
                @endforeach
            </select>
        </div>
        <div class="col-sm-2">
            <input id="priority" name="priority" type="number" class="form-control" min="0" value="0" required="">
        </div>
        <div class="col-sm-3">
            <input name="enable" type="checkbox" data-on-text="On" data-on-color="success" data-off-color="danger" data-off-text="Off" id="enable" checked="true" />
        </div>
    </div>
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
<script type="text/javascript" src="{{asset('assets/vendors/moment/js/moment.min.js')}}"></script>

<script type="text/javascript" src="{{asset('assets/vendors/jasny-bootstrap/js/jasny-bootstrap.js')}}" ></script>

<script type="text/javascript" src="{{asset('assets/vendors/select2/js/select2.js')}}"></script>

<script type="text/javascript" src="{{asset('assets/vendors/bootstrapvalidator/js/bootstrapValidator.min.js')}}" ></script>

<script src="{{asset('assets/vendors/airdatepicker/js/datepicker.min.js')}}" type="text/javascript"></script>

<script src="{{asset('assets/vendors/airdatepicker/js/datepicker.en.js')}}" type="text/javascript"></script>

<script type="text/javascript" src="{{asset('assets/vendors/bootstrap-switch/js/bootstrap-switch.js')}}"></script>

<script type="text/javascript" src="{{asset('assets/js/custom_js/haseMenuEdit.js')}}"></script>

<!-- <script type="text/javascript" src="{{asset('assets/js/custom_js/HaseStylesEdit.js')}}"></script> -->

<script type="text/javascript">

@if(Session::has('type'))
   toastr.options = {
"closeButton": true,
"positionClass": "toast-top-right",
"showDuration": "1000",
"hideDuration": "1000",
"timeOut": "5000",
"extendedTimeOut": "1000",
"showEasing": "swing",
"hideEasing": "swing",
"showMethod": "show"
};
var $toast = toastr["{{ Session::pull('type') }}"]("", "{{ Session::pull('msg') }}");
   @endif

</script>
<!-- end of page level js -->
@stop