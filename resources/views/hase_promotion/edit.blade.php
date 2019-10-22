@extends('layouts/default')

{{-- Page title --}}
@section('title')
    Promotion
    @parent
@stop

{{-- page level styles --}}
@section('header_styles')
    <!--page level css -->
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/jasny-bootstrap/css/jasny-bootstrap.css')}}" />
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/bootstrap-switch/css/bootstrap-switch.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/bootstrapvalidator/css/bootstrapValidator.min.css')}}">
    <link href="{{asset('assets/vendors/airdatepicker/css/datepicker.min.css')}}" rel="stylesheet" type="text/css">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/select2/css/select2.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/select2/css/select2-bootstrap.css')}}">
    <!--end of page level css-->
@stop
@section('content')
<section class="content-header">
    <h1>Edit Promotion</h1>
    <ol class="breadcrumb">
        <li>
            <a href="index ">
                <i class="fa fa-fw fa-home"></i> Dashboard
            </a>
        </li>
        <li>
            <a href="#"> Promotion</a>
        </li>
        <li class="active">
            edit Promotion Index
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
    <form method = 'POST' enctype="multipart/form-data" class="form-horizontal bv-form" action='{!! url("hase_promotion")!!}/{!!$hase_promotion->promotion_id!!}/update' id="create_promotion_form">
        <input type = 'hidden' name = '_token' value = '{{Session::token()}}'>
        <input type="hidden" name="requestUrl" id="requestUrl" value="{!!url(Request::segment(1))!!}">
        <input type = 'hidden' name = 'role_id' id='role_id' value = '{{$roleId}}'>
        <input type = 'hidden' name = 'promotion_postal_id' id='promotion_postal_id' value = '{{$hase_promotion->location_id}}'>
        <input id="promotionStatus" name="promotionStatus" type="hidden" value="{!!$hase_promotion->status!!}"  />
        <input type="hidden" name="selectedLocation" id="selectedLocation" value="{{$hase_promotion->location_city_id}}">
        <?php if($hase_promotion->offer_begin == date('m/d/Y', strtotime('12/31')))
            {
                if($hase_promotion->offer_begin == $hase_promotion->offer_expire)
                {
                    echo '<input type="hidden" name="yearRound" id="yearRound" value="1">';
                }
            } 
        ?>
        <div class="row">
            <div class="col-md-12">
                <button type="submit" name="submitBtn" value="Save" class='btn btn-primary btn-inline'>Save</button>
                <button type="submit" name="submitBtn" value="SaveClose" class = 'btn btn-primary btn-inline'>Save &amp; close</button>
                <a href="{!!url('hase_promotion')!!}" class='btn btn-primary btn-inline'>
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
                               data-loop="true"></i> Edit Promotion
                        </h3>
                        <span class="pull-right clickable">
                            <i class="glyphicon glyphicon-chevron-up"></i>
                        </span>
                    </div>
                    <div class="panel-body">
                        <?php if(Session('roleId') < 5): ?>
                            <div class="form-group">
                                <label for="merchant_id" class="col-sm-3 control-label">Merchant</label>
                                <div class="col-sm-8">
                                    <input type="hidden" id="merchant_id" name="merchant_id" value="{!!$hase_promotion->merchant_id!!}" >
                                    {!!$hase_promotion->merchant_name!!}
                                </div>
                            </div>
                        <?php endif; ?>
                        <div class="form-group">
                            <label for="city_id" class="col-sm-3 control-label">City</label>
                            <div class="col-sm-8"> 
                                <select name="city_id" id="city_id" class="form-control select21" style="width:100%">
                                    <option></option>
                                    @foreach($merchant_cities as $merchant_city)
                                        @if($merchant_city->city_id == $hase_promotion->city_id)
                                            <option value="{{$merchant_city->city_id}}" selected>{{$merchant_city->city_name}}</option>
                                        @else
                                            <option value="{{$merchant_city->city_id}}">{{$merchant_city->city_name}}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="location_id" class="col-sm-3 control-label">Location</label>
                            <div class="col-sm-8"> 
                                <select name="location_id" id="location_id" class="form-control select21" style="width:100%">
                                    <option></option>
                                    @foreach($merchant_city_postals as $merchant_city_postal)
                                        @if($merchant_city_postal->location_id == $hase_promotion->location_id)
                                            <option value="{{$merchant_city_postal->location_id}}" selected>{{$merchant_city_postal->location_name}}</option>
                                        @else
                                            <option value="{{$merchant_city_postal->location_id}}">{{$merchant_city_postal->location_name}}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        
                        <?php
                        $promotionLiveImageUrl = parse_url($hase_promotion->image_url);
                        ?>
                        @if(isset($promotionLiveImageUrl['scheme']))
                            @if($promotionLiveImageUrl['scheme'] == 'https' || $promotionLiveImageUrl['scheme'] == 'http')
                                <div class="form-group">
                                    <label for="compact Pic" class="col-sm-3 control-label">Image<span class="help-block">Select a file to update promotion image, otherwise leave blank.</span></label>
                                    <div class="col-sm-5">
                                        <div class="fileinput fileinput-<?php echo isset($hase_promotion->image_url)?'exists':'new' ?>" data-provides="fileinput">
                                            <div class="fileinput-new thumbnail"
                                                 style="width: 200px; height: 200px;">
                                                    <img src="{{asset(env('image_dir_path').'no_photo.png')}}" alt="profile pic" class="profile_pic">
                                            </div>
                                            <div class="fileinput-preview fileinput-exists thumbnail"
                                                 style="max-width: 200px; max-height: 200px;">
                                                <img src="{!!$hase_promotion->image_url!!}" alt="profile pic" class="profile_pic">
                                            </div>
                                            <div class="fileinput-filename" style="display: block !important;width: 60%;word-wrap: break-word;">
                                            </div>
                                            <div>
                                                    <span class="btn btn-default btn-file">
                                                    <span class="fileinput-new">Select image</span>
                                                    <span class="fileinput-exists">Change</span>
                                                    <input id="image_url" name="image_url" type="file" class="form-control"/>
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
                                        <input id="live_image_url" name = "live_image_url" type="text" class="form-control" value="{!!$hase_promotion->image_url!!}"> 
                                    </div>
                                </div>
                            @endif
                        @else
                            @if($hase_promotion->image_url != "" && file_exists(public_path(env('image_dir_path').$hase_promotion->image_url)))
                                <div class="form-group">
                                    <label for="compact Pic" class="col-sm-3 control-label">Image<span class="help-block">Select a file to update promotion image, otherwise leave blank.</span></label>
                                    <div class="col-sm-5">
                                        <div class="fileinput fileinput-<?php echo isset($hase_promotion->image_url)?'exists':'new' ?>" data-provides="fileinput">
                                            <div class="fileinput-new thumbnail"
                                                 style="width: 200px; height: 200px;">
                                                <img src="{{asset(env('image_dir_path').'no_photo.png')}}" alt="profile pic" class="profile_pic">
                                            </div>
                                            <div class="fileinput-preview fileinput-exists thumbnail"
                                                 style="max-width: 200px; max-height: 200px;">
                                                @if($hase_promotion->image_url)
                                                    <img src="{{asset(env('image_dir_path').$hase_promotion->image_url)}}" alt="profile pic" class="profile_pic">
                                                @endif         
                                            </div>
                                            <div class="fileinput-filename" style="display: block !important;width: 60%;word-wrap: break-word;">
                                                {!!$hase_promotion->image_url!!}
                                            </div>
                                            <div>
                                                    <span class="btn btn-default btn-file">
                                                    <span class="fileinput-new">Select image</span>
                                                    <span class="fileinput-exists">Change</span>
                                                    <input id="image_url" name="image_url" type="file" class="form-control"/>
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
                                        <input id="live_image_url" name = "live_image_url" type="text" class="form-control" > 
                                    </div>
                                </div>
                            @else
                                <div class="form-group">
                                    <label for="compact Pic" class="col-sm-3 control-label">Image<span class="help-block">Select a file to update promotion image, otherwise leave blank.</span></label>
                                    <div class="col-sm-5">
                                        <div class="fileinput fileinput-new" data-provides="fileinput">
                                            <div class="fileinput-new thumbnail"
                                                 style="width: 200px; height: 200px;">
                                                <img src="{{asset(env('image_dir_path').'no_photo.png')}}" alt="profile pic" class="profile_pic">
                                            </div>
                                            <div class="fileinput-preview fileinput-exists thumbnail"
                                                 style="max-width: 200px; max-height: 200px;"></div>
                                            <div class="fileinput-filename" style="display: block !important;width: 60%;word-wrap: break-word;">
                                            </div>
                                            <div>
                                                    <span class="btn btn-default btn-file">
                                                    <span class="fileinput-new">Select image</span>
                                                    <span class="fileinput-exists">Change</span>
                                                    <input id="image_url" name="image_url" type="file" class="form-control"/>
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
                                        <input id="live_image_url" name = "live_image_url" type="text" class="form-control" > 
                                    </div>
                                </div>   
                            @endif
                        @endif
                        <?php
                        $promotionLiveImageCompactUrl = parse_url($hase_promotion->image_url_compact);
                        ?>
                        @if(isset($promotionLiveImageCompactUrl['scheme']))
                            @if($promotionLiveImageCompactUrl['scheme'] == 'https' || $promotionLiveImageCompactUrl['scheme'] == 'http')
                                <div class="form-group">
                                    <label for="pic" class="col-sm-3 control-label">Image Compact<span class="help-block">Select a file to update promotion image compact, otherwise leave blank.</span></label>
                                    <div class="col-sm-5">
                                        <div class="fileinput fileinput-<?php echo isset($hase_promotion->image_url_compact)?'exists':'new' ?>" data-provides="fileinput">
                                            <div class="fileinput-new thumbnail"
                                                 style="width: 200px; height: 200px;">
                                                 <img src="{{asset(env('image_dir_path').'no_photo.png')}}" alt="profile pic" class="profile_pic">
                                            </div>
                                            <div class="fileinput-preview fileinput-exists thumbnail"
                                                 style="max-width: 200px; max-height: 200px;">
                                                <img src="{!!$hase_promotion->image_url_compact!!}" alt="profile pic" class="profile_pic">     
                                            </div>
                                            <div class="fileinput-filename" style="display: block !important;width: 60%;word-wrap: break-word;">
                                            </div>
                                            <div>
                                                    <span class="btn btn-default btn-file">
                                                    <span class="fileinput-new">Select image</span>
                                                    <span class="fileinput-exists">Change</span>
                                                    <input id="image_url_compact" name="image_url_compact" type="file" class="form-control"/>
                                                    </span>
                                                <a href="#" class="btn btn-danger fileinput-exists"
                                                   data-dismiss="fileinput">Remove</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="image Url compact" class="col-sm-3 control-label">Image Url compact</label>
                                    <div class="col-sm-8">
                                        <input id="live_image_compact_url" name = "live_image_compact_url" type="text" class="form-control" value="{{$hase_promotion->image_url_compact}}"> 
                                    </div>
                                </div>
                            @endif
                        @else
                            @if($hase_promotion->image_url_compact != "" && file_exists(public_path(env('image_dir_path').$hase_promotion->image_url_compact)))
                                <div class="form-group">
                                    <label for="pic" class="col-sm-3 control-label">Image Compact<span class="help-block">Select a file to update promotion image compact, otherwise leave blank.</span></label>
                                    <div class="col-sm-5">
                                        <div class="fileinput fileinput-<?php echo isset($hase_promotion->image_url_compact)?'exists':'new' ?>" data-provides="fileinput">
                                            <div class="fileinput-new thumbnail"
                                                 style="width: 200px; height: 200px;">
                                                <img src="{{asset(env('image_dir_path').'no_photo.png')}}" alt="profile pic" class="profile_pic">
                                            </div>
                                            <div class="fileinput-preview fileinput-exists thumbnail"
                                                 style="max-width: 200px; max-height: 200px;">
                                                @if($hase_promotion->image_url_compact)
                                                    <img src="{{asset(env('image_dir_path').$hase_promotion->image_url_compact)}}" alt="profile pic" class="profile_pic">
                                                @endif         
                                            </div>
                                            <div class="fileinput-filename" style="display: block !important;width: 60%;word-wrap: break-word;">
                                                {!!$hase_promotion->image_url_compact!!}
                                            </div>
                                            <div>
                                                    <span class="btn btn-default btn-file">
                                                    <span class="fileinput-new">Select image</span>
                                                    <span class="fileinput-exists">Change</span>
                                                    <input id="image_url_compact" name="image_url_compact" type="file" class="form-control"/>
                                                    </span>
                                                <a href="#" class="btn btn-danger fileinput-exists"
                                                   data-dismiss="fileinput">Remove</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="image Url compact" class="col-sm-3 control-label">Image Url</label>
                                    <div class="col-sm-8">
                                        <input id="live_image_compact_url" name = "live_image_compact_url" type="text" class="form-control" > 
                                    </div>
                                </div>
                            @else
                                <div class="form-group">
                                    <label for="pic" class="col-sm-3 control-label">Image Compact<span class="help-block">Select a file to update promotion image compact, otherwise leave blank.</span></label>
                                    <div class="col-sm-5">
                                        <div class="fileinput fileinput-new" data-provides="fileinput">
                                            <div class="fileinput-new thumbnail"
                                                 style="width: 200px; height: 200px;">
                                                <img src="{{asset(env('image_dir_path').'no_photo.png')}}" alt="profile pic" class="profile_pic">
                                            </div>
                                            <div class="fileinput-preview fileinput-exists thumbnail"
                                                 style="max-width: 200px; max-height: 200px;"></div>
                                            <div class="fileinput-filename" style="display: block !important;width: 60%;word-wrap: break-word;">
                                            </div>
                                            <div>
                                                    <span class="btn btn-default btn-file">
                                                    <span class="fileinput-new">Select image</span>
                                                    <span class="fileinput-exists">Change</span>
                                                    <input id="image_url_compact" name="image_url_compact" type="file" class="form-control"/>
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
                                        <input id="live_image_compact_url" name = "live_image_compact_url" type="text" class="form-control" > 
                                    </div>
                                </div>   
                            @endif
                        @endif
                        <div class="form-group">
                            <label for="status" class="col-sm-3 control-label">Status</label>
                            <div class="col-sm-5">
                                <input class="switch form-control" data-on-color="success" data-off-color="danger" data-on-text="Enabled" data-off-text="Disabled" id="status" name="status" type="checkbox" value="{!!$hase_promotion->status!!}" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="offer_details" class="col-sm-3 control-label">Offer Details</label>
                            <div class="col-sm-5">
                                <input id="offer_details" name = "offer_details" type="text" class="form-control" value="{!!$hase_promotion->offer_details!!}"> 
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="offer_url" class="col-sm-3 control-label">URL</label>
                            <div class="col-sm-5">
                                <input id="offer_url" name = "offer_url" type="text" class="form-control" value="{!!$hase_promotion->offer_url!!}"> 
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="offer_terms" class="col-sm-3 control-label">Offer Terms</label>
                            <div class="col-sm-5">
                                <input id="offer_terms" name = "offer_terms" type="text" class="form-control" value="{!!$hase_promotion->offer_terms!!}"> 
                            </div>
                        </div>
                        <?php $featuredOffer = $hase_promotion->offer_featured; ?>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">{!! $labels[0] !!}</label>
                            <div class="col-sm-5">
                                <input class="switch form-control" data-on-color="success" data-off-color="danger" data-on-text="Enabled" data-off-text="Disabled" name="featured_status" id="featured_status" type="checkbox" <?php echo ($featuredOffer != 0 ) ? "checked" : ""  ?> />
                            </div>
                        </div>
                        <div class="form-group" id="featured_block" style="<?php echo ($featuredOffer == 0 ) ? "display:none;" : ""  ?>">
                            <label for="offer_featured" class="col-sm-3 control-label">Featured Priority</label>
                            <div class="col-sm-5">
                                <input id="offer_featured" name = "offer_featured" type="text" class="form-control" value="{!!$hase_promotion->offer_featured!!}"> 
                                <!-- <input class="switch form-control" data-on-color="success" data-off-color="danger" data-on-text="Yes" data-off-text="No" id="offer_featured" name="offer_featured" type="checkbox" value="{!!$hase_promotion->offer_featured!!}" /> -->
                            </div>
                        </div>
                        <?php $hottestOffer = $hase_promotion->offer_hottest; ?>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Hottest Offer</label>
                            <div class="col-sm-5">
                                <input class="switch form-control" data-on-color="success" data-off-color="danger" data-on-text="Enabled" data-off-text="Disabled" name="hottest_status" id="hottest_status" type="checkbox" <?php echo ($hottestOffer != 0 ) ? "checked" : ""  ?> />
                            </div>
                        </div>
                        <div class="form-group hottest_block" style="<?php echo ($hottestOffer == 0 ) ? "display:none;" : ""  ?>">
                            <label for="offer_hottest" class="col-sm-3 control-label">Hottest Priority</label>
                            <div class="col-sm-5">
                                <input id="offer_hottest" name = "offer_hottest" type="text" class="form-control" value="{!!$hase_promotion->offer_hottest!!}">
                                <!-- <input class="switch form-control" data-on-color="success" data-off-color="danger" data-on-text="Yes" data-off-text="No" id="offer_hottest" name="offer_hottest" type="checkbox" value="{!!$hase_promotion->offer_hottest!!}" /> -->
                            </div>
                        </div>
                        <div class="form-group hottest_block" style="<?php echo ($hottestOffer == 0 ) ? "display:none;" : ""  ?>">
                            <label for="year_round" class="col-sm-3 control-label">Year-round Promotion</label>
                            <div class="col-sm-5">
                                <input class="switch form-control" data-on-color="success" data-off-color="danger" data-on-text="Yes" data-off-text="No" id="year_round" name="year_round" type="checkbox" value="0" />
                            </div>
                        </div>
                        <div id="dateRange" >
                            <div class="form-group">
                                <label for="offer_begin" class="col-sm-3 control-label">Promotion Begin</label>
                                <div class="col-sm-5">
                                    <input id="offer_begin" name="offer_begin" type="text" class="form-control pull-left" data-language='en' placeholder="MM/DD/YYYY" value="{!!$hase_promotion->offer_begin!!}"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="offer_expire" class="col-sm-3 control-label">Promotion End</label>
                                <div class="col-sm-5">
                                    <input id="offer_expire" name="offer_expire" type="text" class="form-control pull-left" data-language='en' placeholder="MM/DD/YYYY" value="{!!$hase_promotion->offer_expire!!}"/>
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
<script type="text/javascript" src="{{asset('assets/vendors/moment/js/moment.min.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/jasny-bootstrap/js/jasny-bootstrap.js')}}" ></script>
<script type="text/javascript" src="{{asset('assets/vendors/select2/js/select2.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/bootstrapvalidator/js/bootstrapValidator.min.js')}}" ></script>
<script src="{{asset('assets/vendors/airdatepicker/js/datepicker.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/vendors/airdatepicker/js/datepicker.en.js')}}" type="text/javascript"></script>
<script type="text/javascript" src="{{asset('assets/vendors/bootstrap-switch/js/bootstrap-switch.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/js/custom_js/hasePromotion.js')}}"></script>
<!-- end of page level js -->
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
        var $toast = toastr["{{ Session::pull('type') }}"]("{{ Session::pull('msg') }}","{{ Session::pull('title') }}");
    @endif
</script>
@stop