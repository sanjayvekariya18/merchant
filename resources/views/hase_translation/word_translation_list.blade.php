@extends('layouts/default')
{{-- Page title --}}
@section('title')
    Translation List
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
        <li><a href="#"> Translation Details List</a></li>
        <li class="active">Translation</li>
    </ol>
</section>
<section class="content">
    <section class="content p-l-r-15">
        <div class="row">
            <div class="panel panel-primary ">
                <div class="panel-heading">
                    <h4 class="panel-title">    
                        <i class="fa fa-fw fa-users"></i> Translation Details List
                    </h4>
                </div>
                <div class="panel-body">
<table style="width: 40%">
        <tr><div class="approveLanguageDropdown" style='float: left;'>Filter Translation : <input id="multiLanguageDropdown" /></div> &nbsp&nbsp&nbsp <a href="../public/users_language">User Can select known Language </a>
        </br>
        </br>
        </tr>  
</table> 
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
<div id="grid"></div>
<script type="text/x-kendo-template" id="templates">
    <br>
    @foreach($hase_translaton_status_filter as $hase_translaton_status_list)
                    <button type="button" name="selectedAction" class='k-button' value="{{$hase_translaton_status_list->approval_status_id}}" onclick="statusFilterValue('{{$hase_translaton_status_list->approval_status_id}}')">{{$hase_translaton_status_list->approval_status_name}}</button>

    @endforeach
    <button type="button" class='k-button' onclick="statusFilterValue('all')">All</button><br> </div>
    </br>
    </script> 
</div>
<script type="text/x-kendo-template" id="templateTranslatedDetail">
<div class="translatedDetails"></div>
</script>
<script type="text/x-kendo-template" id="deleteTemplete">
    <form id="translationForm"  action = '{!!url("hase_translation_list")!!}/#=originalWordId#/delete' method = 'GET' class="form-horizontal" role="form">
                                            <input type = 'hidden' name = '_token' value = '{{Session::token()}}' />
                                            <input type = 'hidden' name = 'userId' value = '#=originalWordId#' />
                                            <button type="submit" name="submitBtn" class='send-btn k-button'>Delete</button>
                                        </form>
</script> 
<script type="text/javascript">
var userName="{{$username}}";
localStorage.setItem("userName",userName);
</script> 
<script src="assets/kendoui/js/jquery.min.js" type="text/javascript"></script>
<script src="assets/kendoui/js/kendo.all.min.js" type="text/javascript"></script>
<script type="text/javascript" src="{{asset('assets/js/custom_js/HaseLanguageDropDown.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/js/custom_js/HaseTranslatedWord.js')}}"></script>

</div>
        </div>
    </section>
</section>
@endsection
{{-- page level scripts --}}
@section('footer_scripts')
@stop