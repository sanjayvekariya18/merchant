@extends('layouts/default')
{{-- Page title --}}
@section('title')
    Image Translation List
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
    <h1>Translation Details List</h1>
    <ol class="breadcrumb">
        <li><a href="index "><i class="fa fa-fw fa-home"></i> Dashboard</a></li>
        <li><a href="#"> Image Translation Details List</a></li>
        <li class="active">Image Translation</li>
    </ol>
</section>
<section class="content">
    <section class="content p-l-r-15">
        <div class="row">
            <div class="panel panel-primary ">
                <div class="panel-heading">
                    <h4 class="panel-title">    
                        <i class="fa fa-fw fa-users"></i> Image Translation Details List
                    </h4>
                </div>
                <div class="panel-body">
<div id="grid"></div>
<div id="comments"></div>
<script type="text/x-kendo-template" id="templateComments">
   <iframe src="translation-language-difference" width="630" height="170"> </iframe>
</script>
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
.k-input {
  width: 130px;
  height: 50px;
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
<script type="text/x-kendo-template" id="templates">
    <br><div class="toolbar" width="100px">

    @foreach($hase_translaton_status_filter as $hase_translaton_status_list)
                    <button type="button" name="selectedAction" class='k-button' value="{{$hase_translaton_status_list->approval_status_id}}" onclick="imagestatusFilterValue('{{$hase_translaton_status_list->approval_status_id}}')">{{$hase_translaton_status_list->approval_status_name}}</button>

    @endforeach
    <button type="button" class='k-button' onclick="imagestatusFilterValue('all')">All</button><br> </div>
    </div>
    </br>
    </script> 

</div>
<script type="text/x-kendo-template" id="imageTranslationTemplateDetail">
    <div class="imageTranslationHistory"></div>
</script>
 
<script type="text/x-kendo-template" id="template">
<input type="hidden" value="" id="activityIdentity" name="activityIdentity" >
<input type="hidden" value="" id="regionIdentity" name="regionIdentity" >
<iframe name="activityIframe" id="activityIframe" src="activity-region-tree" scrolling="auto" frameborder="0"  width="825" height="600"> </iframe>
</script>
<script type="text/x-kendo-template" id="deleteTemplete">
    <form id="translationForm"  action = '{!!url("image_translation")!!}/#=id#/delete' method = 'GET' class="form-horizontal" role="form">
                                            <input type = 'hidden' name = '_token' value = '{{Session::token()}}' />
                                            <input type = 'hidden' name = 'userId' value = '#=id#' />
                                            <button type="submit" name="submitBtn" class='send-btn k-button'>Delete</button>
                                        </form>
</script> 
<script type="text/javascript">
var userName="{{$username}}";
localStorage.setItem("userName",userName);
</script> 
<script type="text/javascript" src="{{asset('assets/js/custom_js/HaseTranslatedImageDetail.js')}}"></script>
</div>
        </div>
    </section>
</section>
@endsection
{{-- page level scripts --}}
<script src="assets/kendoui/js/jquery.min.js" type="text/javascript"></script>
<script src="assets/kendoui/js/kendo.all.min.js" type="text/javascript"></script>
@section('footer_scripts')
<script type="text/javascript">
@if(Session::has('type'))
    toastr.options = {
        "closeButton": true,
        "positionClass": "toast-top-right",
        "showDuration": "1000",
        "hideDuration": "1000",
        "timeOut": "5000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "swing",
        "showMethod": "show"
    };
    var $toast = toastr["{{ Session::get('type') }}"]("", "{{ Session::get('msg') }}");
@endif
</script>