@extends('layouts/default')
{{-- Page title --}}
@section('title')
    Regex Change History
    @parent
@stop
{{-- page level styles --}}
@section('header_styles')
@stop
@section('content')
<section class="content-header">
<link rel="stylesheet" href="{{asset('assets/kendoui/styles/kendo.common.min.css')}}">
<link rel="stylesheet" href="{{asset('assets/kendoui/styles/kendo.rtl.min.css')}}">
<link rel="stylesheet" href="{{asset('assets/kendoui/styles/kendo.default.min.css')}}">
<link rel="stylesheet" href="{{asset('assets/kendoui/styles/kendo.mobile.all.min.css')}}">
<link rel="stylesheet" href="{{asset('assets/kendoui/styles/kendo.blueopal.min.css')}}" />
<script src="{{asset('assets/kendoui/js/jquery.min.js')}}" type="text/javascript"></script>
<script type="text/javascript" src="{{asset('assets/kendoui/js/kendo.all.min.js')}}" type="text/javascript"></script>
    <h1>Regex Change History</h1>
    <ol class="breadcrumb">
        <li><a href="index "><i class="fa fa-fw fa-home"></i> Dashboard</a></li>
        <li><a href="#"> Regex Change History</a></li>
        <li class="active">Scrape Translation Details</li>
    </ol>
</section>
<section class="content">
    <section class="content p-l-r-15">
        <div class="row">
            <div class="panel panel-primary ">
                <div class="panel-heading">
                    <h4 class="panel-title">    
                        <i class="fa fa-fw fa-users"></i> Regex Change History
                    </h4>
                </div>
                <div class="panel-body">
<div id="grid"></div>
<div id="comments"></div>
<script type="text/x-kendo-template" id="templateComments">
   <iframe src="translation-language-difference" width="630" height="170"> </iframe>
</script>
<script type="text/x-kendo-template" id="scrapeEventTranslationTemplateDetail">
    <div class="scrapeEventsTranslationHistory"></div>
</script>
<script type="text/x-kendo-template" id="templates">
    <br>
    @foreach($hase_scrape_event_translaton_status_filter as $hase_scrape_event_translaton_status_list)
                    <button type="button" name="selectedAction" class='k-button' value="{{$hase_scrape_event_translaton_status_list->approval_status_id}}" onclick="scrapeEventStatusFilterValue('{{$hase_scrape_event_translaton_status_list->approval_status_id}}')">{{$hase_scrape_event_translaton_status_list->approval_status_name}}</button>

    @endforeach
    <button type="button" class='k-button' onclick="scrapeEventStatusFilterValue('all')">All</button><br> </div></br></script> 
    <script type="text/x-kendo-template" id="event_url_template">
<a  target="blank" href="#=result_text#">#=result_text#</a>
</script>
<script type="text/x-kendo-template" id="deleteTemplete">
<form id="translationForm"  action = '{!!url("hase_scrape_details_list")!!}/#=result_id#/delete' method = 'GET' class="form-horizontal" role="form">
                                            <input type = 'hidden' name = '_token' value = '{{Session::token()}}' />
                                            <input type = 'hidden' name = 'result_id' value = '#=result_id#' />
                                            <button type="submit" name="submitBtn" class='send-btn k-button'>Delete</button>
</form>
</script> 
</div>
</div>
</style>
<script type="text/javascript">
var userName="{{$username}}";
localStorage.setItem("userName",userName);
</script> 
<script type="text/javascript" src="{{asset('assets/js/custom_js/HaseScrapeEventTranslationDetail.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/js/custom_js/HaseLanguageDropDown.js')}}"></script>
    <script type="text/javascript">scrapeEventTranslationDetails();</script>
</div>
        </div>
    </section>
</section>
@endsection
{{-- page level scripts --}}
@section('footer_scripts')