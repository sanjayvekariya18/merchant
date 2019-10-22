@extends('layouts/default')

{{-- Page title --}}
@section('title')
    Scrape Status
    @parent
@stop

{{-- page level styles --}}
@section('header_styles')
<!--page level css -->
    <link rel="stylesheet" type="text/css" href="assets/kendoui/styles/kendo.common.min.css">
    <link rel="stylesheet" type="text/css" href="assets/kendoui/styles/kendo.default.min.css">
    <link rel="stylesheet" type="text/css" href="assets/kendoui/styles/kendo.mobile.all.min.css">
    <link rel="stylesheet" type="text/css" href="assets/kendoui/styles/kendo.blueopal.min.css">
<!--end of page level css-->
@stop
@section('content')
<section class="content-header">
    <h1>Scrape Status</h1>
    <ol class="breadcrumb">
        <li>
            <a href="index ">
                <i class="fa fa-fw fa-home"></i> Dashboard
            </a>
        </li>
        <li class="active">
            Scrape Status
        </li>
    </ol>
</section>
<section class="content">
    <section class="content p-l-r-15">
        <div class="row">
            <div class="panel panel-primary ">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <i class="fa fa-fw fa-users"></i> Scrape Status
                    </h4>
                </div>
                <input type="hidden" id="requestUrl" value="{!!url('scrapestatus')!!}">
                <input type="hidden" id="basePath" name="basePath" value="<?php echo env('APP_URL'); ?>">
                <div class="panel-body">
                    <div class="preloader" style="background: none !important;">
                        <div class="loader_img">
                            <img src="{{asset('assets/img/loader.gif')}}" alt="loading..." height="64" width="64">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div id="scrapeUrlStatusGrid"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</section>
@endsection

{{-- page level scripts --}}
<script src="{{asset('assets/kendoui/js/jquery.min.js')}}" type="text/javascript"></script>
@section('footer_scripts')
    <script src="{{asset('assets/kendoui/js/kendo.all.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('assets/js/custom_js/UrlStatus.js')}}"></script>
    <script type="text/x-kendo-template" id="node_value_template">
        # if (website_url.match(/(((https?:\/\/)|(www\.))[^\s]+)/g) != null) { #
            <a target="blank" href="#=scrape_url#">#=website_url#</a>
        # } else { #
            #= website_url #
        # } #
    </script>
    <script type="text/x-kendo-template" id="cron_date_template">
        # if (cron_date != 0) { #
            #= cron_date #
        # } #
    </script>
@stop