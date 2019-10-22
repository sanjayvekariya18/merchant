@extends('layouts/default')
{{-- Page title --}}
@section('title')
    Tickers
    @parent
@stop
@section('header_styles')
<link rel="stylesheet" type="text/css" href="assets/kendoui/styles/kendo.common.min.css">
<link rel="stylesheet" type="text/css" href="assets/kendoui/styles/kendo.mobile.all.min.css">
<link rel="stylesheet" type="text/css" href="assets/kendoui/styles/kendo.blueopal.min.css">
<link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/bootstrapvalidator/css/bootstrapValidator.min.css')}}">
<link href="{{asset('assets/vendors/select2/css/select2.min.css')}}" rel="stylesheet" type="text/css">
<link href="{{asset('assets/vendors/select2/css/select2-bootstrap.css')}}" rel="stylesheet" type="text/css">
<link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/bootstrap-switch/css/bootstrap-switch.css')}}"/>
<!--end of page level css-->
@stop
@section('content')
<section class="content-header">
    <h1>Tickers</h1>
    <ol class="breadcrumb">
        <li>
            <a href="index ">
                <i class="fa fa-fw fa-home"></i> Dashboard
            </a>
        </li>
        <li class="active">
            Tickers
        </li>
    </ol>
</section>
<section class="content">
    <div class="preloader" style="background: none !important; ">
        <div class="loader_img">
            <img src="{{asset('assets/img/loader.gif')}}" alt="loading..." height="64" width="64">
        </div>
    </div>
    <section class="content p-l-r-15">
        <div class="row">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <i class="fa fa-fw fa-users"></i> Tickers
                    </h4>
                </div>
                <div class="panel-body">
                    <input type="hidden" id="requestUrl" value="{!!url(Request::segment(1))!!}">
                    <div id="tabstrip">
                        <ul>
                            <li class="k-state-active" >Tickers</li>
                            <li>Tickers History</li>
                        </ul>
                        <div id="tab1">
                            <div id="TickersGrid"></div>
                            <input type="hidden" id="tickersTimeoutId" name="tickersTimeoutId" />
                        </div>
                        <div id="tab2">
                            <div id="TickersHistoryGrid"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</section>
@endsection
{{-- page level scripts --}}
<script src="assets/kendoui/js/jquery.min.js" type="text/javascript"></script>
@section('footer_scripts')
<script type="text/javascript" src="{{asset('assets/kendoui/js/kendo.all.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('assets/js/custom_js/Tickers/Tickers.js')}}"></script>
    <script type="text/javascript" src="{{asset('assets/vendors/select2/js/select2.js')}}"></script>
    <script type="text/javascript" src="{{asset('assets/vendors/bootstrap-switch/js/bootstrap-switch.js')}}"></script>
    <script type="text/javascript" src="{{asset('assets/vendors/bootstrapvalidator/js/bootstrapValidator.min.js')}}" ></script>
    <script id="TickersSearch" type="text/x-kendo-template">
         <div class="searchToolBar" style="float: left;">
            <input type="button" class="k-button" id="tickersRefreshButton" onkeypress="return noenter()">
         </div>
        <div class="searchToolBar" style="float: right;">
            <label class="search-label" for="searchBox">Search Grid:</label>
            <input type="search" id="TickersSearchBox" class="k-textbox" style="width: 250px"/>
            <input type="button" id="TickersBtnSearch" class="k-button" value="Search"/>
            <input type="button" id="TickersBtnReset" class="k-button" value="Reset"/>
        </div>
    </script>
    <script id="TickersHistorySearch" type="text/x-kendo-template">
        <div class="searchToolBar" style="float: right;">
            <label class="search-label" for="searchBox">Search Grid:</label>
            <input type="search" id="TickersHistorySearchBox" class="k-textbox" style="width: 250px"/>
            <input type="button" id="TickersHistoryBtnSearch" class="k-button" value="Search"/>
            <input type="button" id="TickersHistoryBtnReset" class="k-button" value="Reset"/>
        </div>
    </script>
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
@stop
