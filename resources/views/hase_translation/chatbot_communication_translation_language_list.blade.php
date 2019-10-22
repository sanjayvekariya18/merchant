@extends('layouts/default')
{{-- Page title --}}
@section('title')
    Communication Translation Language List
    @parent
@stop
{{-- page level styles --}}
@section('header_styles')
@stop
@section('content')
<section class="content-header">
<link rel="stylesheet" type="text/css" href="assets/kendoui/styles/kendo.common.min.css">
<link rel="stylesheet" type="text/css" href="assets/kendoui/styles/kendo.uniform.min.css">
    <h1>Communication Details Language List</h1>
    <ol class="breadcrumb">
        <li><a href="index "><i class="fa fa-fw fa-home"></i> Dashboard</a></li>
        <li><a href="#"> Communication Translation Details Language List</a></li>
        <li class="active">Communication Translation Language</li>
    </ol>
</section>
<section class="content">
    <section class="content p-l-r-15">
        <div class="row">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h4 class="panel-title">    
                        <i class="fa fa-fw fa-users"></i> Communication Translation Details Language List
                    </h4>
                </div>
                <div class="panel-body">
                <table style="width: 40%">
        <tr><div class="approveLanguageDropdown" style='float: left;'>Filter Translation : <input id="multiLanguageDropdown" /></div>
        </br>
        </br>
        </tr>
</table>
<div id="grid"></div>
<div id="comments"></div>
<script type="text/x-kendo-template" id="templateComments">
   <iframe src="translation-language-difference" width="630" height="170"> </iframe>
</script>
<script type="text/x-kendo-template" id="communicationTranslationTemplateDetail">
    <div class="communicationTranslationHistory"></div>
</script>
<script type="text/x-kendo-template" id="templates">
    <br><div class="toolbar" width="100px"><br>
<div id='filterdiv'>
    @foreach($hase_communication_translaton_status_filter as $hase_communication_translaton_status_list)
                    <button type="button" name="selectedAction" class='k-button' value="{{$hase_communication_translaton_status_list->approval_status_id}}" onclick="communicationStatusFilterValue('{{$hase_communication_translaton_status_list->approval_status_id}}')">{{$hase_communication_translaton_status_list->approval_status_name}}</button>

    @endforeach
    <button type="button" class='k-button' onclick="communicationStatusFilterValue('all')">All</button><br> </div>
    </div></br></script> 
</div>
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
<script type="text/javascript" src="{{asset('assets/js/custom_js/HaseCommunicationTranslationLanguageDetail.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/js/custom_js/HaseLanguageDropDown.js')}}"></script>
    <script type="text/javascript">communicationTranslationDetails();</script>
</div>
        </div>
    </section>
</section>
@endsection
{{-- page level scripts --}}
<script src="assets/kendoui/js/jquery.min.js" type="text/javascript"></script>
<script src="assets/kendoui/js/kendo.all.min.js" type="text/javascript"></script>
@section('footer_scripts')