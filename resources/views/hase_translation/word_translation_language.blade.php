@extends('layouts/default')
{{-- Page title --}}
@section('title')
    Translation Language List
    @parent
@stop
{{-- page level styles --}}
@section('header_styles')
@stop
@section('content')
<section class="content-header">
<script src="assets/kendoui/js/jquery.min.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="assets/kendoui/styles/kendo.common.min.css">
<script src="assets/kendoui/js/kendo.all.min.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="assets/kendoui/styles/kendo.uniform.min.css">
    <h1>Translation Language List</h1>
    <ol class="breadcrumb">
        <li><a href="index "><i class="fa fa-fw fa-home"></i> Dashboard</a></li>
        <li><a href="#"> Translation Language List</a></li>
        <li class="active">Language Translation</li>
    </ol>
</section>
<section class="content">
    <section class="content p-l-r-15">
        <div class="row">
            <div class="panel panel-primary ">
                <div class="panel-heading">
                    <h4 class="panel-title">    
                        <i class="fa fa-fw fa-users"></i> Translation Language List
                    </h4>
                </div>
                <div class="panel-body">
<div id="grid"></div>
<div id="languageTranslationWindow"></div>
<script type="text/x-kendo-template" id="templates">
    <br><div class="toolbar" width="100px"><br>
<div id='filterdiv'>
    @foreach($hase_translaton_status_filter as $hase_translaton_status_list)
                    <button type="button" name="selectedAction" class='k-button' value="{{$hase_translaton_status_list->approval_status_id}}" onclick="statusFilterValue('{{$hase_translaton_status_list->approval_status_id}}')">{{$hase_translaton_status_list->approval_status_name}}</button>

    @endforeach
    <button type="button" class='k-button' onclick="statusFilterValue('all')">All</button><br> </div>
    </div>
    </br>
    </script> 

</div>
<script type="text/x-kendo-template" id="templateTranslatedDetail">
<div class="translatedDetails"></div>
</script>
<script type="text/x-kendo-template" id="templateComments">
   <iframe src="translation-language-difference" width="630" height="170"> </iframe>
</script>
<script type="text/javascript" src="{{asset('assets/js/custom_js/HaseTranslateWordLanguageDetails.js')}}"></script>
    <script type="text/javascript">
        wordTranslateLanguageDetailList();
    </script>
</div>
        </div>
    </section>
</section>
@endsection
{{-- page level scripts --}}
@section('footer_scripts')
@stop