@extends('layouts/default')
{{-- Page title --}}
@section('title')
    Communication Translation List
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
    <h1>Communication Details List</h1>
    <ol class="breadcrumb">
        <li><a href="index "><i class="fa fa-fw fa-home"></i> Dashboard</a></li>
        <li><a href="#"> Communication Translation Details List</a></li>
        <li class="active">Communication Translation</li>
    </ol>
</section>
<section class="content">
    <section class="content p-l-r-15">
        <div class="row">
            <div class="panel panel-primary ">
                <div class="panel-heading">
                    <h4 class="panel-title">    
                        <i class="fa fa-fw fa-users"></i> Communication Translation Details List
                    </h4>
                </div>
                <div class="panel-body">
<div id="grid"></div>
<div id="gridTranslation"></div>
<div id="comments"></div>
<script type="text/x-kendo-template" id="templateComments">
   <iframe src="translation-language-difference" width="630" height="170"> </iframe>
</script>
<script type="text/x-kendo-template" id="communicationTranslationTemplateDetail">
    <div class="communicationTranslationHistory"></div>
</script>
<script type="text/x-kendo-template" id="templates">
    <br><div class="toolbar" width="100px">
    @foreach($hase_communication_translaton_status_filter as $hase_communication_translaton_status_list)
                    <button type="button" name="selectedAction" class='k-button' value="{{$hase_communication_translaton_status_list->approval_status_id}}" onclick="communicationStatusFilterValue('{{$hase_communication_translaton_status_list->approval_status_id}}')">{{$hase_communication_translaton_status_list->approval_status_name}}</button>

    @endforeach
    <button type="button" class='k-button' onclick="communicationStatusFilterValue('all')">All</button><br>
    </div></br></script> 
</div>
<script type="text/javascript" src="{{asset('assets/js/custom_js/HaseCommunicationTranslationDetail.js')}}"></script>
</div>
        </div>
    </section>
</section>
@endsection
{{-- page level scripts --}}
@section('footer_scripts')