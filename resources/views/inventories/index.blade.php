@extends('layouts/default')
{{-- Page title --}}
@section('title')
    Inventories
    @parent
@stop
@section('header_styles')
<link rel="stylesheet" type="text/css" href="assets/kendoui/styles/kendo.common.min.css">
<link rel="stylesheet" type="text/css" href="assets/kendoui/styles/kendo.mobile.all.min.css">
<link rel="stylesheet" type="text/css" href="assets/kendoui/styles/kendo.blueopal.min.css">
<!--end of page level css-->
@stop
@section('content')
<section class="content-header">
    <h1>Inventories</h1>
    <ol class="breadcrumb">
        <li>
            <a href="index ">
                <i class="fa fa-fw fa-home"></i> Dashboard
            </a>
        </li>
        <li class="active">
            Inventories
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
                        <i class="fa fa-fw fa-users"></i> Inventories
                    </h4>
                </div>
                <div class="panel-body">
                    <input type="hidden" id="requestUrl" value="{!!url(Request::segment(1))!!}">
                    <div id="tabstrip">
                        <ul>
                            <li class="k-state-active" >Inventory</li>
                            <li>Inventory History</li>
                            <li>Competitors</li>
                            <li>Competitors History</li>
                        </ul>
                        <div id="tab1">
                            <div id="InventoriesGrid"></div>
                        </div>
                        <div id="tab2">
                            <div id="InventoriesHistoryGrid"></div>
                        </div>
                        <div id="tab3">
                            <div id="CompetitorsGrid"></div>
                        </div>
                         <div id="tab4">
                            <div id="CompetitorsHistoryGrid"></div>
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
    <script type="text/javascript" src="{{asset('assets/js/custom_js/Inventories/Inventories.js')}}"></script>
    <script id="InventoriesSearch" type="text/x-kendo-template">
        <div class="searchToolBar" style="float: right;">
            <label class="search-label" for="searchBox">Search Broker:</label>
            <input type="search" id="InventoriesSearchBox" class="k-textbox" style="width: 250px"/>
            <input type="button" id="InventoriesBtnSearch" class="k-button" value="Search"/>
            <input type="button" id="InventoriesBtnReset" class="k-button" value="Reset"/>
        </div>
    </script>
    <script id="InventoriesHistorySearch" type="text/x-kendo-template">
        <div class="searchToolBar" style="float: right;">
            <label class="search-label" for="searchBox">Search Broker:</label>
            <input type="search" id="InventoriesHistorySearchBox" class="k-textbox" style="width: 250px"/>
            <input type="button" id="InventoriesHistoryBtnSearch" class="k-button" value="Search"/>
            <input type="button" id="InventoriesHistoryBtnReset" class="k-button" value="Reset"/>
        </div>
    </script>
    <script id="CompetitorsSearch" type="text/x-kendo-template">
        <div class="searchToolBar" style="float: right;">
            <label class="search-label" for="searchBox">Search Broker:</label>
            <input type="search" id="CompetitorsSearchBox" class="k-textbox" style="width: 250px"/>
            <input type="button" id="CompetitorsBtnSearch" class="k-button" value="Search"/>
            <input type="button" id="CompetitorsBtnReset" class="k-button" value="Reset"/>
        </div>
    </script>
    <script id="CompetitorsHistorySearch" type="text/x-kendo-template">
        <div class="searchToolBar" style="float: right;">
            <label class="search-label" for="searchBox">Search Broker:</label>
            <input type="search" id="CompetitorsHistorySearchBox" class="k-textbox" style="width: 250px"/>
            <input type="button" id="CompetitorsHistoryBtnSearch" class="k-button" value="Search"/>
            <input type="button" id="CompetitorsHistoryBtnReset" class="k-button" value="Reset"/>
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
