@extends('layouts/default')

{{-- Page title --}}
@section('title')
    Html DOM
    @parent
@stop

{{-- page level styles --}}
@section('header_styles')
<!--page level css -->
<link rel="stylesheet" type="text/css" href="{{asset('assets/vendors/bootstrapvalidator/css/bootstrapValidator.min.css')}}">
    <link rel="stylesheet" type="text/css" href="assets/kendoui/styles/kendo.common.min.css">
    <link rel="stylesheet" type="text/css" href="assets/kendoui/styles/kendo.default.min.css">
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
        [userSelected~=yes] {
            background-color: deepskyblue !important;
        }
        [domChanged~=yes] {
            background-color: #ff6d36 !important;
        }
        [groupSelected~=yes] {
            background-color: lightgreen;
        }
        #main-content {
            display: table-cell;
            margin: 0;
            padding: 10px 0 0 0;
            width: 537px;
            vertical-align: top;
        }
        #main-content #statusPanel h3 {
            margin: 0;
            background-color: #daecf4;
        }
        .k-grid tbody button.k-button {
            min-width: 50px;
        }
        .k-grid tbody .k-button {
            min-width: 40px;
        }
        /* WIDGETS */
        .widget {
            margin: 15px 0 0;
            padding: 0;
            background-color: #ffffff;
            border: 1px solid #e7e7e7;
            border-radius: 3px;
        }
        .widget:hover {
            background-color: #fcfcfc;
            border-color: #cccccc;
        }
        .widget div {
            padding: 10px;
            min-height: 50px;
        }
        .widget h3 {
            font-size: 12px;
            padding: 8px 10px;
            text-transform: uppercase;
            border-bottom: 1px solid #e7e7e7;
        }
        .widget h3 span {
            float: right;
        }
        .widget h3 span:hover {
            cursor: pointer;
            background-color: #e7e7e7;
            border-radius: 20px;
        }
    </style>
<!--end of page level css-->
@stop
@section('content')
<section class="content-header">
    <h1>Html DOM</h1>
    <ol class="breadcrumb">
        <li>
            <a href="index ">
                <i class="fa fa-fw fa-home"></i> Dashboard
            </a>
        </li>
        <li class="active">
            Html DOM
        </li>
    </ol>
</section>
<section class="content">
    <section class="content p-l-r-15">
        <div class="row">
            <div class="panel panel-primary ">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <i class="fa fa-fw fa-users"></i> Html DOM
                    </h4>
                </div>
                <input type="hidden" id="requestUrl" value="{!!url('htmldom')!!}">
                <input type="hidden" id="regexRequestUrl" value="{!!url('regex')!!}">
                <input type="hidden" id="basePath" name="basePath" value="http://{{$hostname}}">
                <input type="hidden" id="scraped" name="scraped" value="{{ Request::get('scraped') ? Request::get('scraped') : 0 }}">
                <div class="panel-body">
                    <div class="preloader" style="background: none !important;">
                        <div class="loader_img">
                            <img src="{{asset('assets/img/loader.gif')}}" alt="loading..." height="64" width="64">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <form method='POST' action='{!!url("htmldom")!!}/scrapeHtmlDom' id="htmlDomForm">
                                {{ csrf_field() }}
                                <div id="tabstrip">
                                    <ul>
                                        <li>List</li>
                                        <li class="k-state-active">DOM</li>
                                        <li>Transform</li>
                                        <li>Checked</li>
                                    </ul>
                                    <div id="tab1">
                                        <div id="checkedSavedHtmlDomGrid"></div>
                                    </div>
                                    <div id="tab2">
                                        <div class="table-responsive">
                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th style="width: 30%">Identity Table</th>
                                                        <th style="width: 37%">Website URL</th>
                                                        <th style="width: 28%">Regex Group</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>
                                                            <input id="identity_table" name="identity_table" value="{{ Request::get('table_id') ? Request::get('table_id') : '56' }}" style="width: 100%"/>
                                                        </td>
                                                        <td>
                                                            <input type="hidden" name="identity_id" id="identity_id" value="{{ Request::get('identity_id') ? Request::get('identity_id') : '' }}">
                                                            <input id="website_uri" name="website_uri" value="{{ Request::get('url') ? Request::get('url') : '' }}" class="k-textbox" required validationMessage="Website URL Required" style="width: 100%" />
                                                        </td>
                                                        <td>
                                                            <input id="label_id" name="label_id"/>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <button type="button" id="resultSubmitBtn" class="send-btn k-button">Scrape</button>
                                        <button type="button" id="applySplit" class="k-button">Apply Split</button>
                                        <button type="button" id="transformPrimitive" class="k-button">Transform Primitive</button>
                                        <button type="button" id="nodeExpandBtn" class="k-button">Transform</button>
                                        <button type="button" id="checkFilteredRaw" class="k-button" style="display: none;">Check Filtered Rows</button>
                                        <button type="button" class="k-button" id="expandAll">Expand All</button>
                                        <button type="button" class="k-button" id="collapseAll">Collapse All</button>
                                        <button type="button" class="k-button" id="refreshTreelist">Refresh</button>
                                        <br><br>
                                        <div id="htmlDomResultGrid"></div>
                                    </div>
                                    <div id="tab3">
                                        <button type="button" id="transformApply" class="k-button">Apply Transform</button>
                                        <div id="transformHtmlDomGrid" style="margin-top: 10px;"></div>
                                    </div>
                                    <div id="tab4">
                                        <button type="button" id="checkedNodeDelete" class="k-button">Delete Checked Entry</button>
                                        <div id="checkedHtmlDomGrid"></div>
                                    </div>
                                </div>
                                <div id="main-content">
                                    <div id="statusPanel" class="widget">
                                        <h3>Status</h3>
                                        <div class="statusDisplay">
                                            <h4>URL: <span class="statusUrl">
                                                <a href=""></a></span>
                                            </h4>
                                            <h4>Status: <span class="processSection" style="display: none;">Processed <span class="processCount"></span> of <span class="totalCount"></span> elements.</span><span class="scrapeError"></span>
                                            </h4>
                                            <h4><span class="processCompleted"></span></h4>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- history window -->
            <div id="html_dom_class_top_modal" class="modal fade animated position_modal" role="dialog">
                <div class="modal-dialog" style="width: 60% !important">
                    <div class="modal-content" style="background-color: #d9ecf5">
                        <div class="modal-header" style="background-color: #13688c;color: white">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="html-dom-class-modal-title">Html Dom history List</h4>
                        </div>
                        <div class="panel panel-primary" style="border-color: snow;">
                            <div class="panel-body">
                                <form method='POST' action="{{url('htmldom')}}" id="htmlDomHistoryForm">
                                    {{ csrf_field() }}
                                    <div id="htmlDomHistoryGrid"></div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- history window -->
        <!-- map target popup -->
        <div id="map_target_top_modal" class="modal fade animated position_modal" role="dialog">
            <div class="modal-dialog" style="width: 60% !important">
                <div class="modal-content" style="background-color: #d9ecf5">
                    <div class="modal-header" style="background-color: #13688c;color: white">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="map-target-fields-modal-title">Map Target Fields</h4>
                    </div>
                    <div class="panel panel-primary" style="border-color: snow;">
                        <div class="panel-body">
                            <button type="button" id="mapNodeValue" class="k-button" style="float: right;">Map Node Value</button>
                            <br/><br/>
                            <input type="hidden" id="domClass" name="domClass">
                            <form method='POST' action="{{url('htmldom')}}" id="mapTargetFieldsForm">
                                {{ csrf_field() }}
                                <div id="mapTargetFieldsGrid"></div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- map target popup -->
        <!-- reference data popup -->
        <div id="reference_data_top_modal" class="modal fade animated position_modal" role="dialog">
            <div class="modal-dialog" style="width: 60% !important">
                <div class="modal-content" style="background-color: #d9ecf5">
                    <div class="modal-header" style="background-color: #13688c;color: white">
                        <h4 class="reference-data-fields-modal-title">Reference Data</h4>
                    </div>
                    <div class="panel panel-primary" style="border-color: snow;">
                        <div class="panel-body">
                            <form method='POST' action="{{url('htmldom')}}" id="referenceDataForm">
                                {{ csrf_field() }}
                                <button type="button" id="textTransform" class="k-button" style="float: right;">Text Transform</button>
                                <br><br>
                                <div id="referenceDataGrid"></div>
                            </form>
                            <div id="referenceButton" style="float: right;">
                                <button type="button" id="referenceContinue" class="send-btn k-button">Continue</button>
                                <button type="button" id="referenceCancel" class="k-button">Cancel</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- reference data popup -->
        <!-- reference fields popup -->
        <div id="reference_modal_fields" class="modal fade animated position_modal" role="dialog">
            <div class="modal-dialog" style="width: 60% !important">
                <div class="modal-content" style="background-color: #d9ecf5">
                    <div class="modal-header" style="background-color: #13688c;color: white">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="reference-modal-fields-title">Reference Info</h4>
                    </div>
                    <div class="panel panel-primary" style="border-color: snow;">
                        <div class="panel-body">
                            <form method='POST' action="{{url('htmldom')}}" id="ReferenceTableColumnForm">
                                {{ csrf_field() }}
                                <div id="referenceTableColumnId"></div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- reference table window -->
    </section>
</section>
@endsection

{{-- page level scripts --}}
<script src="{{asset('assets/kendoui/js/jquery.min.js')}}" type="text/javascript"></script>
@section('footer_scripts')
<script type="text/javascript" src="{{asset('assets/vendors/bootstrapvalidator/js/bootstrapValidator.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('assets/kendoui/js/kendo.all.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('assets/js/custom_js/HtmlDom.js')}}"></script>
    <?php
if (Request::get('url')) {?>
            <script type="text/javascript">
                $(document).ready(function () {
                    $("#resultSubmitBtn").trigger("click");
                });
            </script>
    <?php }?>
    <script type="text/javascript" src="{{asset('assets/js/custom_js/HtmlDomClassHistory.js')}}"></script>
    <script type="text/x-kendo-template" id="node_value_template">
        # if (value.match(/(((https?:\/\/)|(www\.))[^\s]+)/g) != null) { #
            <a target="blank" href="#=value#">#=value#</a>
        # } else { #
            #= value #
        # } #
    </script>
    <script type="text/x-kendo-template" id="class_change_data_template">
        # if (class_change_data == 1) { #
            # if (history_count > 0) { #
                <button type="button" class="historyButton k-button">#=history_count#</button>&nbsp &nbsp 
            # } #
            #=class_data#
        # } else { #
           #=class_data#
        # } #
    </script>
    <script type="text/x-kendo-template" id="reference_id_template">
        <div class="nodeReferenceId">
            # if (ref_id > 0) { #
                #=ref_id#
            # } #
        </div>
    </script>
    <script type="text/x-kendo-template" id="map_target_template">
        <div class="mapTargetFields"></div>
    </script>
    <script type="text/x-kendo-template" id="website_url_template">
        # if (website_url.match(/(((https?:\/\/)|(www\.))[^\s]+)/g) != null) { #
            <a target="blank" href="#=website_url#">#=website_url#</a>
        # } else { #
            <a target="blank" href="http://#=website_url#">#=website_url#</a>
        # } #
    </script>
    <script type="text/x-kendo-template" id="diff_changes_template">
        # if (diff_changes == 1) { #
            #=scrape_date#
        # } else { #
           No
        # } #
    </script>
    <script id="user-confirmation" type="text/x-kendo-template">
        <p class="changes-message">Changes have been made to your DOM.</p>
        <button class="changes-confirm k-button">Continue</button>
        <button class="changes-cancel k-button">Cancel</button>
    </script>
@stop