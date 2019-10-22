@extends('layouts/default')

{{-- Page title --}}
@section('title')
    Create Asset Team List
    @parent
@stop

{{-- page level styles --}}
@section('header_styles')
    <!--page level css -->
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/select2/css/select2.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/select2/css/select2-bootstrap.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/bootstrapvalidator/css/bootstrapValidator.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/datedropper/datedropper.css')}}">
    <link href="{{asset('assets/vendors/airdatepicker/css/datepicker.min.css')}}" rel="stylesheet" type="text/css">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/bootstrap-switch/css/bootstrap-switch.css')}}"/>
    <!--end of page level css-->
@stop
@section('content')
<section class="content-header">
    <h1>Create New Asset Team List</h1>
    <ol class="breadcrumb">
        <li>
            <a href="index ">
                <i class="fa fa-fw fa-home"></i> Dashboard
            </a>
        </li>
        
        <li class="active">
            Asset Team List
        </li>
    </ol>
</section>
<section class="content">
    <div class="bs-example">
        <ul class="nav nav-tabs" style="margin-bottom: 15px;">
            <li class="active">
                <a href="#asset_team_panel" data-toggle="tab">Asset Team List</a>
            </li>
            <li>
                <a href="#social_panel" data-toggle="tab">Social</a>
            </li>
        </ul>
        <div id="myTabContent" class="tab-content">
            <div class="tab-pane fade active in" id="asset_team_panel">
                <form id="assetTeamListForm"  action='{!!url("asset_team_list")!!}' method='POST' class="form-horizontal" role="form" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-12">
                            <!-- <button type="submit" name="submitBtn" value="Save" class='btn btn-primary btn-inline'>Save</button> -->
                            <button type="submit" name="submitBtn" value="SaveClose" class = 'btn btn-primary btn-inline'>Save  &amp; close</button>
                            <a href="{!!url("asset_team_list")!!}" class='btn btn-primary btn-inline'>
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
                                        <i class="fa fa-fw fa-crosshairs"></i> Add Asset Team List
                                    </h3>
                                </div>
                                <div class="panel-body">
                                    <input type='hidden' name='_token' value='{{Session::token()}}'>
                                    <input type="hidden" name="requestUrl" id="requestUrl" value="{!!url(Request::segment(1))!!}">
                                    <div class="form-group">
                                        <label for="asset_id" class="col-sm-3 control-label">Asset Name</label>
                                        <div class="col-sm-4">
                                            <select name="asset_id" id="asset_id" class="form-control select21" style="width:100%">
                                                <option></option>
                                                @foreach($assets as $asset)
                                                   <option value="{{$asset->asset_id}}">{{$asset->asset_name}} ({{$asset->asset_code}})</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="team_id" class="col-sm-3 control-label">Team Name</label>
                                        <div class="col-sm-4">
                                            <select name="team_id" id="team_id" class="form-control select21" style="width:100%">
                                                <option></option>
                                                @foreach($assetTeams as $teams)
                                                   <option value="{{$teams->team_id}}">{{$teams->team_name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="member_id" class="col-sm-3 control-label">Member Name</label>
                                        <div class="col-sm-4">
                                            <select name="member_id[]" id="member_id" class="form-control select21" multiple style="width:100%">
                                                <option></option>
                                                @foreach($peoples as $people)
                                                   <option value="{{$people->people_id}}">{{$people->people_name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="panel panel-primary">
                                                <div class="panel-heading">
                                                    <h3 class="panel-title">
                                                         Member List  
                                                    </h3>
                                                </div>
                                                <div class="panel-body" id="panelList">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="row">
                                                                <label class="col-md-2">Member Name</label>
                                                                <label class="col-md-2">Member Title</label>
                                                                <label class="col-md-2">Priority</label>
                                                                <label class="col-md-2">Begin Date</label>
                                                                <label class="col-md-2">End Date</label>
                                                                <label class="col-md-2">Status</label>
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
                <div class="row" id="memberDemo" style="display: none;">
                    <div class="col-md-2">
                        <label id="member_name"></label>
                        <input type="hidden" name="member_id" value="" id="memberId">
                    </div>
                    <div class="col-sm-2">
                        <input id="member_title" name="member_title" type="text" class="form-control" required="">
                    </div>
                    <div class="col-sm-2">
                        <input id="priority" name="priority" type="number" class="form-control" value="0" required="">
                    </div>
                    <div class="col-sm-2">
                            <input id="status_date_begin" name="status_date_begin" type="text" class="form-control pull-left datepick" data-language='en' /> 
                    </div>
                    <div class="col-sm-2">
                            <input id="status_date_end" name="status_date_end" type="text" class="form-control pull-left datepick" data-language='en' /> 
                    </div>
                    <div class="col-sm-2">
                        <input name="status" type="checkbox" id="status" data-on-text="On" data-on-color="success" data-off-color="danger" data-off-text="Off" checked="true" />
                        <a id="" class="btn btn-primary addSocial"><i class="fa fa-plus"></i></a>
                    </div>
                    <div id="socialList" class="col-md-12">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row">
                                    <label class="col-md-2">&nbsp;</label>
                                    <label class="col-md-2">Social Name</label>
                                    <label class="col-md-4">Social Url</label>
                                    <label class="col-md-2">Priority</label>
                                    <label class="col-md-2">Status</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr class="col-md-12">   
                </div>
                <div class="row" id="socialDemo" style="display: none;">
                    <div class="col-md-2"></div>
                    <div class="col-md-2">
                        <select name="social_id" id="social_id" class="form-control" style="width:100%">
                            <option></option>
                            @foreach($socials as $social)
                               <option value="{{$social->social_id}}">{{$social->social_name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-sm-4">
                        <input id="social_url" name="social_url" type="text" class="form-control" required="">
                    </div>
                    <div class="col-sm-2">
                        <input id="priority" name="priority" type="number" class="form-control" value="0" required="">
                    </div>
                    <div class="col-sm-2">
                        <input name="status" type="checkbox" id="status" data-on-text="On" data-on-color="success" data-off-color="danger" data-off-text="Off" checked="true" />
                        <a id="" class="btn btn-danger deleteSocial"><i class="fa fa-trash"></i></a>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="social_panel">
                <form id="assetForm"  action = '{!!url("social_apikeys")!!}' method = 'POST' class="form-horizontal" role="form" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-12">
                            <button type="submit" name="submitBtn" value="SaveClose" class = 'btn btn-primary btn-inline'>Save</button>
                        </div>
                    </div>
                    <br/>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="panel panel-primary">
                                <div class="panel-heading">
                                    <h3 class="panel-title">
                                        <i class="fa fa-fw fa-crosshairs"></i> Add Social Detail
                                    </h3>
                                </div>
                                <div class="panel-body">
                                    <input type = 'hidden' name = '_token' value = '{{Session::token()}}'>
                                    <div class="form-group">
                                        <label for="social_code" class="col-sm-3 control-label">Social Code</label>
                                        <div class="col-sm-4">
                                            <input id="social_code" name="social_code" type="text" class="form-control">
                                        </div>
                                    </div> 
                                    <div class="form-group">
                                        <label for="social_name" class="col-sm-3 control-label">Social Name</label>
                                        <div class="col-sm-4">
                                            <input id="social_name" name="social_name" type="text" class="form-control">
                                        </div>
                                    </div>  
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>            
        </div>
    </div>     
</section>
@endsection

{{-- page level scripts --}}
@section('footer_scripts')
<!-- begining of page level js -->
<script type="text/javascript" src="{{asset('assets/vendors/moment/js/moment.min.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/select2/js/select2.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/js/custom_js/AssetTeamListCreate.js')}}"></script>
<script src="{{asset('assets/vendors/airdatepicker/js/datepicker.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/vendors/airdatepicker/js/datepicker.en.js')}}" type="text/javascript"></script>
<script  type="text/javascript" src="{{asset('assets/vendors/datedropper/datedropper.js')}}" ></script>
<script type="text/javascript" src="{{asset('assets/vendors/bootstrap-switch/js/bootstrap-switch.js')}}"></script>
@stop
