@extends('layouts/default')
{{-- Page title --}}
@section('title')
    Asset Move
    @parent
@stop

{{-- page level styles --}}
@section('header_styles')
    <style>
        #assetMoveForm label,#assetMoveHistoryForm label{
            font-weight: bold;
        }
        #assetMoveForm div.form-group,#assetMoveHistoryForm div.form-group{
            background-color: #d9ecf5;
            padding: 15px 10px;
        }
        select {
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
        }
        .dropdownStatus {
            border-radius: 6px;
            color: #676767;
            background-color: #e9e9e9;
            background-image: none, linear-gradient(to bottom, rgba(0, 0, 0, 0.01)
                0px, rgba(0, 0, 0, 0.08) 100% );
            background-position: 50% 50%;
            border-color: #ccc;
            height: 2em;
            border: none;
            padding-right: 10px;
        }
        #button-style {
            border-radius: 6px;
            color: #676767;
            
          box-shadow: 0 4px white;
        }
    </style>
    <link rel="stylesheet" href="assets/kendoui/styles/kendo.common.min.css">
    <link rel="stylesheet" href="assets/kendoui/styles/kendo.rtl.min.css">
    <link rel="stylesheet" href="assets/kendoui/styles/kendo.default.min.css">
    <link rel="stylesheet" href="assets/kendoui/styles/kendo.mobile.all.min.css">
    <link rel="stylesheet" href="assets/kendoui/styles/kendo.blueopal.min.css" />
@stop
@section('content')
    <section class="content-header">
        <h1>Asset Move</h1>
        <ol class="breadcrumb">
            <li><a href="index "><i class="fa fa-fw fa-home"></i> Dashboard</a></li>
            <li><a href="active"> Asset Move</a></li>
        </ol>
    </section>
    <section class="content">
        <section class="content p-l-r-15">
            <div class="row">
                <div class="panel panel-primary ">
                    <div class="panel-heading">
                        <h4 class="panel-title">    
                            <i class="fa fa-fw fa-users"></i> Asset Move
                        </h4>
                    </div>
                    <input type="hidden" id="requestUrl" value="{!!url(Request::segment(1))!!}">
                    <div class="panel-body" style="background-color: #d9ecf5">
                        <div class="row">
                            <div class="col-md-12">
                                <div id="tabstrip">
                                    <ul>
                                        <li class="k-state-active">Active</li>
                                        <li>History</li>
                                    </ul>
                                    <div id="tab1">
                                        <form method='POST' action='{!!url("asset_move")!!}' id="assetMoveForm">
                                            {{ csrf_field() }}
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="c_merchant_id">Merchants</label><br>
                                                        <input type="text" id="c_merchant_id" name="merchant_id" />
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="c_account_id">Account</label><br>
                                                        <input type="text" id="c_account_id" name="customer_id" disabled="true" />
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="c_asset_id">Assets</label><br>
                                                        <select id="c_asset_id" name="asset_id[]" disabled="true"></select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div id="assetMoveActiveGrid"></div>
                                            <div id="comments"></div>
                                        </form>
                                    </div>
                                    <div id="tab2">
                                        <form method='POST' action='{!!url("asset_move")!!}' id="assetMoveHistoryForm">
                                            {{ csrf_field() }}
                                            <div id="assetMoveHistoryGrid"></div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </section>
    <script type="text/javascript">
        $(document).ready(function () {
            /*setInterval(function () {
                $("#assetMoveActiveGrid").data("kendoGrid").dataSource.read();
            }, 30000);*/
        });
    </script>
@endsection
<script src="assets/kendoui/js/jquery.min.js" type="text/javascript"></script>
@section('footer_scripts')
    <script src="assets/kendoui/js/kendo.all.min.js"></script>
    <script type="text/javascript" src="{{asset('assets/js/custom_js/AssetMoveHistory.js')}}"></script>
    <script type="text/javascript" src="{{asset('assets/js/custom_js/AssetMoveActive.js')}}"></script>
    <script type="text/x-kendo-template" id="actiontemplate">
        # if (statusCount > 2 ){ #
             <select class="dropdownStatus" name="selectedAction" onchange="updateApprovalStatus(#=move_id#,this.options[selectedIndex].text,this.options[selectedIndex].value)" >
                <option>Actions</option>
                # for (var i = 0; i < status.length; i++) { #
                    <option value="#= status[i].id #">#= status[i].name #</option>
                # } #
            </select>  
         # }
       else { #
            # for (var i = 0; i < status.length; i++) { #
            # if (status[i].name == 'Comment') { #
                <button type="button" name="selectedAction" class='k-button updateApprovalStatus' value="#= status[i].id #" onclick="showDetails(#=move_id#)" >#= status[i].name #</button>
            # } 
            else { #
                <button type="button" name="selectedAction" class='k-button dropdownStatus' value="#= status[i].id #" onclick="updateApprovalStatus(#=move_id#,'#= status[i].name #','#= status[i].id #')">#= status[i].name #</button>

                # } #
            # } #
         # }
       #                      
    </script>
    <script type="text/x-kendo-template" id="templateDetail">
        <div class="commentDetails"></div>
    </script>
    <script type="text/x-kendo-template" id="template">
        <div id="details-container">
            <textarea id="approvecomments" rows="4" cols="20"></textarea>
            <button type="button" class='k-button' onclick="saveRejectComment()">Save</button>
            <button type="button" class='k-button' onclick="closeCommentEvent()">Cancel</button>
        </div>
    </script>  
@stop
