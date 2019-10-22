
@extends('layouts/default')

{{-- Page title --}}
@section('title')
    Approval Queue
    @parent
@stop

{{-- page level styles --}}
@section('header_styles')

@stop
@section('content')
<section class="content-header">
    <link rel="stylesheet" href="assets/kendoui/styles/kendo.common.min.css">
    <link rel="stylesheet" href="assets/kendoui/styles/kendo.rtl.min.css">
    <link rel="stylesheet" href="assets/kendoui/styles/kendo.default.min.css">
    <link rel="stylesheet" href="assets/kendoui/styles/kendo.mobile.all.min.css">
    <link rel="stylesheet" href="assets/kendoui/styles/kendo.blueopal.min.css" />
    <script src="assets/kendoui/js/jquery.min.js" type="text/javascript"></script>
    <script src="assets/kendoui/js/kendo.all.min.js"></script>
    <h1>Approval Queue</h1>
        <ol class="breadcrumb">
            <li>
                <a href="index ">
                    <i class="fa fa-fw fa-home"></i> Dashboard
                </a>
            </li>
            <li>
                <a href="#"> Approval Queue</a>
            </li>
            <li class="active">
                Queue
            </li>
    </ol>
</section>
<section class="content">
<form method = 'get' enctype="multipart/form-data" class="form-horizontal bv-form" role="form" action = 'update_approval_status_multiple' name="adminform" id="adminform">
<input type = 'hidden' name = 'task' value = ' '>
<input type = 'hidden' name = 'boxchecked' value ='0'>
    <section class="content p-l-r-15">
        <div class="row">
            <div class="panel panel-primary ">
                <div class="panel-heading">
                    <h4 class="panel-title">    
                        <i class="fa fa-fw fa-users"></i> Approval Queue
                    </h4>

                </div>

<div class="panel-body">
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
}#filterdiv{
    border-top: 1px solid silver;
    padding-top: 7px;
    width="100px"
}
  </style>
 </script>
    <div id="grid" ></div> 
    <div id="comments"></div>
<script type="text/x-kendo-template" id="templates">
    <br><div class="toolbar" width="100px">
{{--
<div>
@if($statusCountList > 5)
 
        <select class="dropdownStatus" name="selectedAction" onchange="submitApproveRejectValue(this.options[selectedIndex].text)">
                        <option>Select Action</option>
                        @foreach($hase_status as $hase_status_list)
                            <option value="{{$hase_status_list->approval_status_id}}">{{$hase_status_list->approval_status_name}}</option>
                         @endforeach
        </select>
@else

        @foreach($hase_status as $hase_status_list)
                <button id='button-style' type="button" name="selectedAction" class='k-button' value="{{$hase_status_list->approval_status_id}}" onclick="submitApproveRejectValue('{{$hase_status_list->approval_status_name}}')">{{$hase_status_list->approval_status_name}}</button>
        @endforeach

@endif 
</div>
<br>
--}}
    @foreach($hase_status_filter as $hase_status_list)
                    <button type="button" name="selectedAction" class='k-button' value="{{$hase_status_list->approval_status_id}}" onclick="statusFilterValue('{{$hase_status_list->approval_status_id}}')">{{$hase_status_list->approval_status_name}}</button>

    @endforeach
    <button type="button" class='k-button' onclick="statusFilterValue('all')">All</button><br> </div>
</script> 
<script type="text/x-kendo-template" id="templateDetail">
    <div class="commentDetails"></div>
</script>
<script type="text/x-kendo-template" id="template">
            <div id="details-container">
                <textarea id="approvecomments" rows="5" column="7" placeholder="Add Comment" onclick="textareHeightWidth()"></textarea> 
                <button type="button" class='k-button' onclick="saveRejectComment()">Save</button>
                <button type="button" class='k-button' onclick="closeCommentEvent()">Cancel</button>
            </div>
</script>
<script type="text/javascript">
    function textareHeightWidth(){
        var textarea = document.querySelector('textarea');  
        textarea.addEventListener('keydown', autosize);
             function autosize(){
                var el = this;
                setTimeout(function(){
                    el.style.cssText = 'height:auto; padding:0';
                    el.style.cssText = 'height:' + el.scrollHeight + 'px';
                },0);
            }
    }
</script>  
<script type="text/x-kendo-template" id="actiontemplate">
    # if (rowStatusTargetListCount > 2 ){ #
         <select class="dropdownStatus" name="selectedAction" onchange="updateApprovalStatus(#=staffGroupId#,#=approval_id#,this.options[selectedIndex].text,this.options[selectedIndex].value)">
                        <option>Actions</option>
                        # for (var i = 0; i < rowStatusAction.length; i++) { #
                            <option value="#= rowStatusAction[i].id #">#= rowStatusAction[i].name #</option>
                        # } #
        </select>  
     # }
   else
{ #
        # for (var i = 0; i < rowStatusAction.length; i++) { #
        # if (rowStatusAction[i].name == 'Comment') { #
            <button type="button" name="selectedAction" class='k-button' value="#= rowStatusAction[i].id #" onclick="showDetails(#=approval_id#)">#= rowStatusAction[i].name #</button>
        # } 
        else { #
            <button type="button" name="selectedAction" class='k-button' value="#= rowStatusAction[i].id #" onclick="updateApprovalStatus(#=staffGroupId#,#=approval_id#,'#= rowStatusAction[i].name #','#= rowStatusAction[i].id #')">#= rowStatusAction[i].name #</button>

            # } #
        # } #
     # }
   #                      
</script>  
<script type="text/javascript" src="{{asset('assets/js/custom_js/approval.js')}}"></script>
                </div>
            </div>
        </div>
         </form>
    </section>
</section>
@endsection
{{-- page level scripts --}}
@section('footer_scripts')