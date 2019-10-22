@extends('layouts/default')
{{-- Page title --}}
@section('title')
    Activities
    @parent
@stop
@section('header_styles')
<link rel="stylesheet" type="text/css" href="assets/kendoui/styles/kendo.common.min.css">
<link rel="stylesheet" type="text/css" href="assets/kendoui/styles/kendo.mobile.all.min.css">
<link rel="stylesheet" type="text/css" href="assets/kendoui/styles/kendo.blueopal.min.css">
<style type="text/css">
    .k-grid-content { min-height:80px; }
    .k-grid-norecords
    {
        height: auto !important;
    }
</style>
<!--end of page level css-->
@stop
@section('content')
<section class="content-header">
    <h1>Activities</h1>
    <ol class="breadcrumb">
        <li>
            <a href="index ">
                <i class="fa fa-fw fa-home"></i> Dashboard
            </a>
        </li>
        <li class="active">
            Activities
        </li>
    </ol>
</section>
<section class="content">
    <div class="preloader" style="background: none !important; ">
        <div class="loader_img">
            <img src="{{asset('assets/img/loader.gif')}}" alt="loading..." height="64" width="64">
        </div>
    </div>
    <div id="top_modal" class="modal fade animated position_modal" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content" style="background-color: white;">
                <div class="modal-header" style="background-color: #d9ecf5;color: black">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Model-Title</h4>
                </div>
                <div class="modal-body">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h4 class="panel-title">    
                                <i class="fa fa-fw fa-users"></i> Panel-Title
                            </h4>
                        </div>
                        <div class="panel-body">
                            <pre id="modalContentData"></pre>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <section class="content p-l-r-15">
        <div class="row">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h4 class="panel-title">    
                        <i class="fa fa-fw fa-users"></i> Activities
                    </h4>
                </div>
                <div class="panel-body">
                    <input type="hidden" id="requestUrl" value="{!!url(Request::segment(1))!!}">
                    <div id="activitiesGrid"></div>
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
    <script type="text/javascript" src="{{asset('assets/js/custom_js/Activities/Activities.js')}}"></script>
    
    <script id="activitiesHeaderAction" type="text/x-kendo-template">
        <div class="searchToolBar" style="float: right;">
            <label class="search-label" for="searchBox">Search Grid:</label>
            <input type="search" id="activitiesSearchBox" class="k-textbox" style="width: 250px"/>
            <input type="button" id="activitiesBtnSearch" class="k-button" value="Search"/>
            <input type="button" id="activitiesBtnReset" class="k-button" value="Reset"/>
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
            var $toast = toastr["{{ Session::get('type') }}"]("", "{{ Session::get('msg') }}");
        @endif
    </script>
@stop