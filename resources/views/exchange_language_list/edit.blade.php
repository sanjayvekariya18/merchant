@extends('layouts/default')

{{-- Page title --}}
@section('title')
    Edit Exchange Language List
    @parent
@stop

{{-- page level styles --}}
@section('header_styles')
    <!--page level css -->
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/select2/css/select2.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/select2/css/select2-bootstrap.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/bootstrap-switch/css/bootstrap-switch.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/bootstrapvalidator/css/bootstrapValidator.min.css')}}">
    <!--end of page level css-->
@stop
@section('content')
<section class="content-header">
    <h1>Edit Exchange Language List</h1>
    <ol class="breadcrumb">
        <li>
            <a href="index ">
                <i class="fa fa-fw fa-home"></i> Dashboard
            </a>
        </li>
        
        <li class="active">
            Exchange Language List
        </li>
    </ol>
</section>
<section class="content">
    <form id="exchangeLanguageListForm" action='{!!url("exchange_language_list")!!}/{!!$exchange_language_list->
        list_id!!}/update' method='POST' class="form-horizontal" role="form" enctype="multipart/form-data">
        <div class="row">
            <div class="col-md-12">
                <button type="submit" name="submitBtn" value="Save" class='btn btn-primary btn-inline'>Save</button>
                <button type="submit" name="submitBtn" value="SaveClose" class = 'btn btn-primary btn-inline'>Save  &amp; close</button>
                <a href="{!!url("exchange_language_list")!!}" class='btn btn-primary btn-inline'>
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
                            <i class="fa fa-fw fa-crosshairs"></i> Edit Exchange Language List
                        </h3>
                    </div>
                    <div class="panel-body">
                        <input type = 'hidden' name = '_token' value = '{{Session::token()}}'>
                        <input type="hidden" name="requestUrl" id="requestUrl" value="{!!url(Request::segment(1))!!}">
                        <div class="form-group">
                            <label for="exchange_id" class="col-sm-3 control-label">Exchange Name</label>
                            <div class="col-sm-4">
                                <select name="exchange_id" id="exchange_id" class="form-control select21" style="width:100%">
                                    <option></option>
                                    @foreach($exchanges as $exchange)
                                        @if($exchange_language_list->exchange_id == $exchange->exchange_id)
                                            <option value="{{$exchange->exchange_id}}" selected="">{{$exchange->exchange_name}}</option>
                                        @else
                                            <option value="{{$exchange->exchange_id}}">{{$exchange->exchange_name}}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="language_id" class="col-sm-3 control-label">Language Name</label>
                            <div class="col-sm-4">
                                <select name="language_id" id="language_id" class="form-control select21" style="width:100%">
                                    <option></option>
                                    @foreach($languages as $language)
                                       @if($exchange_language_list->language_id == $language->language_id)
                                            <option value="{{$language->language_id}}" selected="">{{$language->language_name}}</option>
                                        @else 
                                            <option value="{{$language->language_id}}">{{$language->language_name}}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="priority" class="col-sm-3 control-label">Priority</label>
                            <div class="col-sm-4">
                                <input id="priority" name="priority" type="number" value="{{$exchange_language_list->priority}}" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="status" class="col-sm-3 control-label">Status</label>
                            <div class="col-sm-4">
                                <div class="make-switch" data-on="danger" data-off="default">
                                    <input id="status" name="status" value="{{$exchange_language_list->status}}" type="checkbox" checked="" />
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
<script type="text/javascript" src="{{asset('assets/vendors/select2/js/select2.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/vendors/bootstrap-switch/js/bootstrap-switch.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/js/custom_js/ExchangeLanguageListCreate.js')}}"></script>
@stop
