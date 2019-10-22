@extends('layouts/default')

{{-- Page title --}}
@section('title')
    Regex Result
    @parent
@stop

{{-- page level styles --}}
@section('header_styles')
<!--page level css -->
    <link rel="stylesheet" type="text/css" href="assets/kendoui/styles/kendo.common.min.css">
    <link rel="stylesheet" type="text/css" href="assets/kendoui/styles/kendo.mobile.all.min.css">
    <link rel="stylesheet" type="text/css" href="assets/kendoui/styles/kendo.blueopal.min.css">
    <style>
        table thead tr{
            background-color: #d9ecf5;
            color: #003f59
        }
        table tr th,table tr td {
            width: 25%;
        }
    </style>
<!--end of page level css-->
@stop
@section('content')
<section class="content-header">
    <h1>Regex Result</h1>
    <ol class="breadcrumb">
        <li>
            <a href="index ">
                <i class="fa fa-fw fa-home"></i> Dashboard
            </a>
        </li>
        <li class="active">
            Regex Result
        </li>
    </ol>
</section>
<section class="content">
    <section class="content p-l-r-15">
        <div class="row">
            <div class="panel panel-primary ">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <i class="fa fa-fw fa-users"></i> Regex Result
                    </h4>
                </div>
                <input type="hidden" id="requestUrl" value="{!!url('regex_result')!!}">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-12">
                            <form method='POST' action='{!!url("regex_result")!!}/scrapeSocialLinks' id="regexResultForm">
                                {{ csrf_field() }}
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th style="width: 25%">Identity Table</th>
                                                <th style="width: 35%">Website URL</th>
                                                <th style="width: 25%">Regex Label</th>
                                                <th style="width: 15%"></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <input id="identity_table" name="identity_table"/>
                                                </td>
                                                <td>
                                                    <input type="hidden" name="identity_id" id="identity_id">
                                                    <input id="website_uri" name="website_uri" class="k-textbox" style="width: 100%" />
                                                </td>
                                                <td>
                                                    <input id="label_id" name="label_id"/>
                                                </td>
                                                <td>
                                                    <button type="submit" id="resultSubmitBtn" class="send-btn k-button" style="width: 100%">Scrape Social</button>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </form>
                            <div id="regexResultGrid"></div>
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
    <script type="text/javascript" src="{{asset('assets/kendoui/js/kendo.all.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('assets/js/custom_js/RegexResult.js')}}"></script>
    <script type="text/x-kendo-template" id="website_url_template">
        <a target="blank" href="#=website_url#">#=website_url#</a>
    </script>
    <script type="text/x-kendo-template" id="regex_result_template">
        <a target="blank" href="#=result_text#">#=result_text#</a>
    </script>
@stop
