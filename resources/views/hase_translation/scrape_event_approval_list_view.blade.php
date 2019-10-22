@php use App\Http\Traits\PermissionTrait; @endphp
@extends('layouts/default')
{{-- Page title --}}
@section('title')
    Scrape Event Approval List
    @parent
@stop
{{-- page level styles --}}
@section('header_styles')
<!--page level css -->
    <link rel="stylesheet" href="assets/kendoui/styles/kendo.common.min.css">
    <link rel="stylesheet" href="assets/kendoui/styles/kendo.rtl.min.css">
    <link rel="stylesheet" href="assets/kendoui/styles/kendo.default.min.css">
    <link rel="stylesheet" href="assets/kendoui/styles/kendo.mobile.all.min.css">
    <link rel="stylesheet" href="assets/kendoui/styles/kendo.blueopal.min.css" />
    <style>
        table thead tr{
            background-color: #d9ecf5;
            color: #003f59
        }
        #scrapeApprovalGrid table tr th,
        #scrapeApprovalGrid table tr td{
            width: 33%;
        }

        span.k-error{
            color: red;
        }
        .k-alert{
            top:250px !important;
            min-width: 200px !important;
        }
        
        .k-grid .k-grid-toolbar .k-grid-add,
        .k-grid tbody .k-grid-edit,
        .k-grid tbody .k-grid-update,
        .k-grid tbody .k-grid-cancel,
        .k-grid tbody .k-grid-delete {
            min-width: 0;
        }

        .k-grid .k-grid-toolbar .k-grid-add .k-icon,
        .k-grid tbody .k-grid-edit .k-icon,
        .k-grid tbody .k-grid-update .k-icon,
        .k-grid tbody .k-grid-cancel .k-icon,
        .k-grid tbody .k-grid-delete .k-icon {
            margin: 0;
        }
    </style>
    <!--end of page level css-->
@stop
@section('content')
<section class="content-header">
    <h1>Scrape Event Approval List</h1>
    <ol class="breadcrumb">
        <li><a href="index "><i class="fa fa-fw fa-home"></i> Dashboard</a></li>
        <li><a href="#"> Scrape Event Approval List</a></li>
        <li class="active">Scrape Event</li>
    </ol>
</section>
<section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h3 class="panel-title">
                            <i class="livicon" data-name="user-add" data-size="18" data-c="#fff" data-hc="#fff"
                               data-loop="true"></i> Scrape Event Approval List
                        </h3>
                        <span class="pull-right clickable">
                            <i class="glyphicon glyphicon-chevron-up"></i>
                        </span>
                        <input type="hidden" id="requestUrl" value="{!!url(Request::segment(1))!!}">
                    </div>
                    <div class="panel-body">
                        <div id="scrapeApprovalGrid"></div>
                    </div>
    </section>
</section>
@endsection
<script src="assets/kendoui/js/jquery.min.js" type="text/javascript"></script>
{{-- page level scripts --}}
@section('footer_scripts')
<script src="assets/kendoui/js/kendo.all.min.js"></script>
<script type="text/x-kendo-template" id="image_url_template">
 # if (event_url == '1') { #
            <img src="#=events_value#" style="width: 80px; height: 40px;"/>
        # } else { #
           #=events_value#
 # } #
</script>
<script type="text/javascript" src="{{asset('assets/js/custom_js/ScrapeApprovalView.js')}}"></script>
@stop