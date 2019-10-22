@extends('layouts/default')
{{-- Page title --}}
@section('title')
    Translation Approval List
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
    <h1>Translation Approval List</h1>
    <ol class="breadcrumb">
        <li><a href="index "><i class="fa fa-fw fa-home"></i> Dashboard</a></li>
        <li><a href="#"> Translation Approval Details List</a></li>
        <li class="active">Translation Approval</li>
    </ol>
</section>
<section class="content">
    <section class="content p-l-r-15">
        <div class="row">
            <div class="panel panel-primary ">
                <div class="panel-heading">
                    <h4 class="panel-title">    
                        <i class="fa fa-fw fa-users"></i> Translation Approval Details List
                    </h4>
                </div>
                <div class="panel-body">
<div id="tabstrip">
   <ul>
      <li class="k-state-active">Current</li>
      <li>History</li>
   </ul>
   <div>
      <div id="grid"></div>
   </div>
   <div>
      <div id="historyGrid"></div>
   </div>
</div>
<div id="comments"></div>
<script type="text/x-kendo-template" id="imageCommentsTemplateDetail">
    <div class="commentDetails"></div>
</script>
<div id="window"></div>
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
textarea{  
  /* box-sizing: padding-box; */
  overflow:hidden;
  /* demo only: */
  padding:10px;
  width:250px;
  font-size:14px;
  margin:30px auto;
  display:block;
  border-radius:10px;
  border:6px solid #e6f9ff;
}
  </style>
<script type="text/x-kendo-template" id="templates">
    
 </br>
    @foreach($hase_translaton_status_filter as $hase_translaton_status_list)
                    <button type="button" name="selectedAction" class='k-button' value="{{$hase_translaton_status_list->approval_status_id}}" onclick="imagestatusFilterValue('{{$hase_translaton_status_list->approval_status_id}}')">{{$hase_translaton_status_list->approval_status_name}}</button>

    @endforeach
    <button type="button" class='k-button' onclick="imagestatusFilterValue('all')">All</button><br> </div>
  
    </br>
    </script> 

</div>
<script type="text/x-kendo-template" id="imageTranslationTemplateDetail">
    <div class="imageTranslationHistory"></div>
</script>
<div id="Difference"></div>

<script type="text/x-kendo-template" id="templateComments">
            <div id="details-container">
                <textarea id="approvecomments" rows="5" column="7" placeholder="Add Comment" onclick="textareHeightWidth()"></textarea>               
                <button type="button" class='k-button' onclick="saveTranslationComment()">Save</button>
                <button type="button" class='k-button' onclick="closeCommentEvent()">Cancel</button>
        
            </div>
</script>
<script type="text/javascript">
    function textareHeightWidth(){
    var textarea = document.querySelector('textarea');
console.log(textarea);
textarea.addEventListener('keydown', autosize);
             
function autosize(){
  var el = this;
  setTimeout(function(){
    el.style.cssText = 'height:auto; padding:0';
    // for box-sizing other than "content-box" use:
    // el.style.cssText = '-moz-box-sizing:content-box';
    el.style.cssText = 'height:' + el.scrollHeight + 'px';
  },0);
}
}
</script>
<script type="text/x-kendo-template" id="templateDifference">
   <iframe src="translation-language-difference" width="630" height="170"> </iframe>
</script>
<script type="text/x-kendo-template" id="actiontemplate">
 # if (rowStatusTargetListCount > 2 ){ #
         <select class="dropdownStatus" name="selectedAction" onchange="updateTranslationStatus(#=translationListValue#,#=historyId#,#=translationGroupId#,#=approval_id#,#=staffGroupId#,this.options[selectedIndex].text,this.options[selectedIndex].value)">
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
            <button type="button" name="selectedAction" class='k-button' value="#= rowStatusAction[i].id #" onclick="acceptRejectComment(#=historyId#)">#= rowStatusAction[i].name #</button>
        # } 
        else { #
            <button type="button" name="selectedAction" class='k-button' value="#= rowStatusAction[i].id #" onclick="updateTranslationStatus(#=translationListValue#,#=historyId#,#=translationGroupId#,#=approval_id#,#=staffGroupId#,'#= rowStatusAction[i].name #','#= rowStatusAction[i].id #')">#= rowStatusAction[i].name #</button>

            # } #
        # } #
     # }
   #                        
</script>  
<script type="text/javascript" src="{{asset('assets/js/custom_js/HaseTranslationApprovalDetail.js')}}"></script>
</div></div>
</section>
</section>
@endsection
{{-- page level scripts --}}
@section('footer_scripts')