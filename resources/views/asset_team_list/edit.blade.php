@extends('layouts/default')

{{-- Page title --}}
@section('title')
    Edit Asset Team List
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
    <h1>Edit Asset Team List</h1>
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
    <form id="assetTeamListForm"  action='{!!url("asset_team_list")!!}/{!!$asset_team_list->
        list_id!!}/update' method='POST' class="form-horizontal" role="form" enctype="multipart/form-data">
        <div class="row">
            <div class="col-md-12">
                <button type="submit" name="submitBtn" value="Save" class='btn btn-primary btn-inline'>Save</button>
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
                            <i class="fa fa-fw fa-crosshairs"></i> Edit Asset Team List
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
                                        @if($asset_team_list->asset_id == $asset->asset_id)
                                            <option value="{{$asset->asset_id}}" selected="">{{$asset->asset_name}} ({{$asset->asset_code}})</option>
                                        @else
                                            <option value="{{$asset->asset_id}}">{{$asset->asset_name}} ({{$asset->asset_code}})</option>
                                        @endif
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
                                        @if($asset_team_list->team_id == $teams->team_id)
                                            <option value="{{$teams->team_id}}" selected="">{{$teams->team_name}}</option>
                                        @else
                                            <option value="{{$teams->team_id}}">{{$teams->team_name}}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <input type="hidden" name="member_id" id="member_id" value="0">
                        <div class="form-group">
                            <label for="priority" class="col-sm-3 control-label">Priority</label>
                            <div class="col-sm-4">
                                <input id="priority" name="priority" type="number" value="{!!$asset_team_list->priority!!}" class="form-control">
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
<script type="text/javascript" src="{{asset('assets/vendors/select2/js/select2.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/js/custom_js/AssetTeamListCreate.js')}}"></script>
@stop
