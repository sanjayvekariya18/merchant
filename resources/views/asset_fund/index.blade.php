@extends('layouts/default')

{{-- Page title --}}
@section('title')
    Asset Fund
    @parent
@stop

{{-- page level styles --}}
@section('header_styles')
    <style>
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
    <!--page level css -->
    <link rel="stylesheet" type="text/css" href="assets/kendoui/styles/kendo.common.min.css">
    <link rel="stylesheet" type="text/css" href="assets/kendoui/styles/kendo.mobile.all.min.css">
    <link rel="stylesheet" type="text/css" href="assets/kendoui/styles/kendo.blueopal.min.css">
    <!--end of page level css-->
@stop
@section('content')
<section class="content-header">
    <h1>Asset Fund</h1>
    <ol class="breadcrumb">
        <li>
            <a href="index ">
                <i class="fa fa-fw fa-home"></i> Dashboard
            </a>
        </li>
        <li class="active">
            Asset Fund
        </li>
    </ol>
</section>
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <?php if(in_array("add", $permissions)): ?>
            <a href='{!!url("asset_fund")!!}/create' class='btn btn-primary btn-inline'>Create New Asset Fund
            </a>
            <?php endif; ?>
        </div>
    </div>
    <br>
    <section class="content p-l-r-15">
        <div class="row">
            <div class="panel panel-primary ">
                <div class="panel-heading">
                    <h4 class="panel-title">    
                        <i class="fa fa-fw fa-users"></i> Asset Fund
                    </h4>
                </div>
                <input type="hidden" id="requestUrl" value="{!!url(Request::segment(1))!!}">
                <input type="hidden" id="publicUrl" value="{!!url('/images')!!}/">

                <div class="panel-body" style="background-color: #d9ecf5">
                    <div class="row">
                        <div class="col-md-12">
                            <div id="tabstrip">
                                <ul>
                                    <li class="k-state-active">Active</li>
                                    <li>History</li>
                                </ul>
                                <div id="tab1">
                                    <div id="assetFundActiveGrid"></div>
                                    <div id="comments"></div>
                                </div>
                                <div id="tab2">
                                    <div id="assetFundHistoryGrid"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="top_modal" class="modal fade animated position_modal" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header" style="background-color: #d9ecf5;">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Asset Image</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                 <img id="assetImage" src="" style="max-width: 565px;text-align: center;">
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
        setInterval(function () {
            $("#assetFundActiveGrid").data("kendoGrid").dataSource.read();
        }, 30000);
    });
</script>
@endsection

{{-- page level scripts --}}
<script src="assets/kendoui/js/jquery.min.js" type="text/javascript"></script>
@section('footer_scripts')
    <script type="text/javascript" src="{{asset('assets/kendoui/js/kendo.all.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('assets/js/custom_js/AssetFundHistory.js')}}"></script>
    <script type="text/javascript" src="{{asset('assets/js/custom_js/AssetFundActive.js')}}"></script>
    <script type="text/x-kendo-template" id="actiontemplate">
        # if (statusCount > 2 ){ #
             <select class="dropdownStatus" name="selectedAction" onchange="updateApprovalStatus(#=fund_id#,this.options[selectedIndex].text,this.options[selectedIndex].value)" >
                <option>Actions</option>
                # for (var i = 0; i < status.length; i++) { #
                    <option value="#= status[i].id #">#= status[i].name #</option>
                # } #
            </select>  
         # }
       else { #
            # for (var i = 0; i < status.length; i++) { #
            # if (status[i].name == 'Comment') { #
                <button type="button" name="selectedAction" class='k-button updateApprovalStatus' value="#= status[i].id #" onclick="showDetails(#=fund_id#)" >#= status[i].name #</button>
            # } 
            else { #
                <button type="button" name="selectedAction" class='k-button dropdownStatus' value="#= status[i].id #" onclick="updateApprovalStatus(#=fund_id#,'#= status[i].name #','#= status[i].id #')">#= status[i].name #</button>

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
