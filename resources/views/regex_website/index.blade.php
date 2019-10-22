@extends('layouts/default')

{{-- Page title --}}
@section('title')
    Regex Website
    @parent
@stop

{{-- page level styles --}}
@section('header_styles')
<!--page level css -->
    <link rel="stylesheet" type="text/css" href="assets/kendoui/styles/kendo.common.min.css">
    <link rel="stylesheet" type="text/css" href="assets/kendoui/styles/kendo.mobile.all.min.css">
    <link rel="stylesheet" type="text/css" href="assets/kendoui/styles/kendo.blueopal.min.css">
    <style>
        table thead tr {
            background-color: #d9ecf5;
            color: #003f59
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
        .k-grid tbody button.k-button {
            min-width: 60px;
        }
        span.k-error, .k-block-error, .k-regex-error, .k-pagination-error {
            color: red;
        }
    </style>
    <!--end of page level css-->
@stop
@section('content')
<section class="content-header">
    <h1>Regex Website</h1>
    <ol class="breadcrumb">
        <li>
            <a href="index ">
                <i class="fa fa-fw fa-home"></i> Dashboard
            </a>
        </li>
        <li class="active">
            Regex Website
        </li>
    </ol>
</section>
<section class="content">
    <section class="content p-l-r-15">
        <div class="row">
            <div class="panel panel-primary ">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <i class="fa fa-fw fa-users"></i> Regex Website
                    </h4>
                </div>
                <input type="hidden" id="requestUrl" value="{!!url('regex_website')!!}">
                <div class="panel-body" style="background-color: #d9ecf5">
                    <div class="row">
                        <div class="col-md-12">
                            {{ csrf_field() }}
                            Domain Filter :
                            <input class="k-textbox" type="text" id="regexWebsiteSearchGrid" placeholder="enter search text..." />
                            <br><br>
                            <!-- <div id="tabstrip">
                                <ul>
                                    <li class="k-state-active">Website List</li>
                                    <li>Website URI</li>
                                </ul>
                                <div id="tab1">
                                    Domain Filter :
                                    <input class="k-textbox" type="text" id="regexWebsiteSearchGrid" placeholder="enter search text..." />
                                    <br><br>
                                    <div id="websiteListGrid"></div>
                                </div>
                                <div id="tab2">
                                    <div id="websiteCategoryGrid"></div>
                                </div>
                            </div> -->
                            <div id="websiteListGrid"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- regex pattern window -->
        <div id="top_modal" class="modal fade animated position_modal" role="dialog">
            <div class="modal-dialog" style="width: 76% !important">
                <div class="modal-content" style="background-color: #d9ecf5">
                    <div class="modal-header" style="background-color: #13688c;color: white">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Regex Pattern List</h4>
                    </div>
                    <div class="panel panel-primary" style="border-color: snow;">
                        <div class="panel-body">
                            <form method='POST' action="{{url('regex_website')}}" id="regexPatternForm">
                                {{ csrf_field() }}
                                <input class="k-textbox" type="text" id="regexPatternSearchGrid" placeholder="enter search text..." />
                                <input id="type_id" name="type_id" />
                                <span class="k-regex-error"></span>
                                <input type="hidden" name="identity_id" id="identity_id">
                                <button type="button" id="submitBtn" class="send-btn k-button" style="float: right;">Assign Regex</button>
                                <br><br>
                                <div id="regexPatternListGrid"></div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- regex pattern window end-->

        <!-- block element window -->
        <div id="element_top_modal" class="modal fade animated position_modal" role="dialog">
            <div class="modal-dialog" style="width: 60% !important">
                <div class="modal-content" style="background-color: #d9ecf5">
                    <div class="modal-header" style="background-color: #13688c;color: white">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="element-modal-title">Element List</h4>
                    </div>
                    <div class="panel panel-primary" style="border-color: snow;">
                        <div class="panel-body">
                            <form method='POST' action="{{url('regex_website')}}" id="websiteElementForm">
                                {{ csrf_field() }}
                                <input class="k-textbox" type="text" id="websiteElementSearchGrid" placeholder="enter search text..." />
                                <span class="k-error"></span>
                                <input type="hidden" name="element_identity_id" id="element_identity_id">
                                <input type="hidden" name="selected_element_id" id="selected_element_id">
                                <button type="button" id="elementSubmitBtn" class="send-btn k-button" style="float: right;">Assign Block Element</button>
                                <br><br>
                                <div id="websiteElementListGrid"></div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- block element window -->

        <!-- block level window -->
        <div id="block_top_modal" class="modal fade animated position_modal" role="dialog">
            <div class="modal-dialog" style="width: 60% !important">
                <div class="modal-content" style="background-color: #d9ecf5">
                    <div class="modal-header" style="background-color: #13688c;color: white">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="block-modal-title">Block List</h4>
                    </div>
                    <div class="panel panel-primary" style="border-color: snow;">
                        <div class="panel-body">
                            <form method='POST' action="{{url('regex_website')}}" id="websiteBlockForm">
                                {{ csrf_field() }}
                                <input class="k-textbox" type="text" id="websiteBlockSearchGrid" placeholder="enter search text..." />
                                <span class="k-block-error"></span>
                                <input type="hidden" name="block_identity_id" id="block_identity_id">
                                <input type="hidden" name="selected_block_id" id="selected_block_id">
                                <button type="button" id="blockSubmitBtn" class="send-btn k-button" style="float: right;">Assign Block</button>
                                <br><br>
                                <div id="websiteBlockListGrid"></div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- block element window -->

        <!-- pagination window -->
        <div id="pagination_top_modal" class="modal fade animated position_modal" role="dialog">
            <div class="modal-dialog" style="width: 60% !important">
                <div class="modal-content" style="background-color: #d9ecf5">
                    <div class="modal-header" style="background-color: #13688c;color: white">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="pagination-modal-title">Pagination List</h4>
                    </div>
                    <div class="panel panel-primary" style="border-color: snow;">
                        <div class="panel-body">
                            <form method='POST' action="{{url('regex_website')}}" id="paginationForm">
                                {{ csrf_field() }}
                                <input class="k-textbox" type="text" id="paginationSearchGrid" placeholder="enter search text..." />
                                <span class="k-pagination-error"></span>
                                <input type="hidden" name="pagination_identity_id" id="pagination_identity_id">
                                <input type="hidden" name="selected_pagination_id" id="selected_pagination_id">
                                <button type="button" id="paginationSubmitBtn" class="send-btn k-button" style="float: right;">Assign Pagination Template</button>
                                <br><br>
                                <div id="paginationListGrid"></div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- pagination window -->
    </section>
</section>
@endsection

{{-- page level scripts --}}
<script src="{{asset('assets/kendoui/js/jquery.min.js')}}" type="text/javascript"></script>
@section('footer_scripts')
    <script type="text/javascript" src="{{asset('assets/kendoui/js/kendo.all.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('assets/js/custom_js/RegexWebsite.js')}}"></script>
    <script type="text/x-kendo-template" id="domain_template">
        # $.each(identity_website.split('/'), function(index, data) { #
        <a target="blank" href="http:///#=data#">
            #= data #
        </a>
        # return false;}); #
    </script>
    <script type="text/x-kendo-template" id="identity_website_template">
        <a target="blank" href="http:///#=identity_website#">#=identity_website#</a>
    </script>
@stop
