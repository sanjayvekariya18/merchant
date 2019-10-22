@extends('layouts/default')
{{-- Page title --}}
@section('title')
    Regex Table Access List
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
        #regexTableAccessListGrid table tr th,
        #regexTableAccessListGrid table tr td{
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
        .k-grd tbody .k-grid-delete .k-icon {
            margin: 0;
        }
    </style>
    <!--end of page level css-->
@stop
@section('content')
<section class="content-header">
    <h1>Regex Table Access List</h1>
    <ol class="breadcrumb">
        <li>
            <a href="index ">
                <i class="fa fa-fw fa-home"></i> Dashboard
            </a>
        </li>
        <li class="active">
            Regex Table Access List
        </li>
    </ol>
</section>
<section class="content">
    <div class="row">
    </div>
    <br>
    <section class="content p-l-r-15">
        <div class="row">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <i class="fa fa-fw fa-users"></i> Regex Table Access List
                    </h4>
                     <input type="hidden" id="requestUrl" value="{!!url(Request::segment(1))!!}">
                     <input type="hidden" id="request_url" value="{{url('regex_table_access')}}">
                </div>
                <div class="panel-body">
                   <div id="regexTableAccessListGrid"></div>
                </div>
            </div>
        </div>
    </section>
</section>
@endsection
<script src="assets/kendoui/js/jquery.min.js" type="text/javascript"></script>
{{-- page level scripts --}}
@section('footer_scripts')
<script src="assets/kendoui/js/kendo.all.min.js"></script>
<script type="text/javascript" src="{{asset('assets/js/custom_js/RegexTableAccessList.js')}}"></script>
@stop
