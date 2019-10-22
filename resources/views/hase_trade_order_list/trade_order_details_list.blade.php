@extends('layouts/default')
{{-- Page title --}}
@section('title')
    Trade Order List
    @parent
@stop
{{-- page level styles --}}
@section('header_styles')
@stop
@section('content')
<section class="content-header">
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
<link rel="stylesheet" type="text/css" href="assets/kendoui/styles/kendo.common.min.css">
<script src="assets/kendoui/js/kendo.all.min.js" type="text/javascript"></script>
<link rel="stylesheet" href="assets/kendoui/styles/kendo.default.mobile.min.css" />
<link rel="stylesheet" type="text/css" href="assets/kendoui/styles/kendo.uniform.min.css">
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
    <h1>Trade Order List</h1>
    <ol class="breadcrumb">
        <li><a href="index "><i class="fa fa-fw fa-home"></i> Dashboard</a></li>
        <li><a href="#">Trade Order List</a></li>
        <li class="active">List</li>
    </ol>
</section>
<section class="content">
    <section class="content p-l-r-15">
        <div class="row">
            <div class="panel panel-primary ">
                <div class="panel-heading">
                    <h4 class="panel-title">    
                        <i class="fa fa-fw fa-users"></i> Trade Orders Entry List
                    </h4>
                </div>
                <br>
                <div style="background-color: #CEF2EF">
            <div class="panel-body">

<div id="tabstrip">
        <ul>
          <li class="k-state-active">Client</li>
          <li>Broker</li>
        </ul>
        <div><div id="grid"></div></div>
        <div><div id="gridBroker"></div></div>
</div>
<script type="text/x-kendo-template" id="actiontemplate">
         <select class="dropdownStatus" name="selectedAction")">
                        <option>Actions</option>
                        # for (var i = 0; i < status_list.length; i++) { #
                            <option value="#= status_list[i].id #">#= status_list[i].name #</option>
                        # } #
        </select> 
</script>
<script type="text/x-kendo-template" id="operationtemplate">
         <select class="dropdownStatus" name="selectedAction")">
                        <option>Actions</option>
                        # for (var i = 0; i < operation_status_List.length; i++) { #
                            <option value="#= operation_status_List[i].id #">#= operation_status_List[i].name #</option>
                        # } #
        </select> 
</script>
<script type="text/x-kendo-template" id="cryptotemplate">
         <select class="dropdownStatus" name="selectedAction")">
                        <option>Actions</option>
                        # for (var i = 0; i < status_crypto_type.length; i++) { #
                            <option value="#= status_crypto_type[i].id #">#= status_crypto_type[i].name #</option>
                        # } #
        </select> 
</script> 
</div>         
</section>
</section>
<script type="text/javascript" src="{{asset('assets/js/custom_js/HaseTradeOrderEntryList.js')}}"></script>
<script type="text/javascript">
        tradeOrderEntryList();
        tradeBrokerOrderEntryList();
</script>
@endsection
{{-- page level scripts --}}
@section('footer_scripts')
@stop