@extends('layouts/default')
{{-- Page title --}}
@section('title')
    Transactions
    @parent
@stop
{{-- page level styles --}}
@section('header_styles')
    <!--page level css -->
    <link rel="stylesheet" type="text/css" href="assets/kendoui/styles/kendo.common.min.css">
    <link rel="stylesheet" type="text/css" href="assets/kendoui/styles/kendo.mobile.all.min.css">
    <link rel="stylesheet" type="text/css" href="assets/kendoui/styles/kendo.blueopal.min.css">
    <!--end of page level css-->
@stop
@section('content')
<section class="content-header">
    <h1>Transactions</h1>
    <ol class="breadcrumb">
        <li>
            <a href="index ">
                <i class="fa fa-fw fa-home"></i> Dashboard
            </a>
        </li>
        <li class="active">
            Transactions
        </li>
    </ol>
</section>
<section class="content">
    <div class="preloader" style="background: none !important; ">
        <div class="loader_img">
            <img src="{{asset('assets/img/loader.gif')}}" alt="loading..." height="64" width="64">
        </div>
    </div>
    <section class="content p-l-r-15">
        <div class="row">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <i class="fa fa-fw fa-users"></i> Transactions
                    </h4>
                </div>
                <div class="panel-body">
                    <input type="hidden" id="requestUrl" value="{!!url(Request::segment(1))!!}">
                    <div id="tabstrip">
                        <ul>
                            <li class="k-state-active" >Transactions</li>
                            <li>Purchased</li>
                            <li>Missed</li>
                            <li>Sales</li>
                            <li>Transfer</li>
                            <li>Open</li>
                        </ul>
                        <div id="tab1">
                            <div id="ticketListingDetails"></div>
                            <input type="hidden" id="transactionsTimeoutId" name="transactionsTimeoutId" />
                        </div>
                        <div id="tab2">
                            <div id="ticketPurchasedEvent"></div>
                            <input type="hidden" id="purchaseTimeoutId" name="purchaseTimeoutId" />
                        </div>
                        <div id="tab3">
                            <div id="ticketMissedEvent"></div>
                            <input type="hidden" id="missedTimeoutId" name="missedTimeoutId" />
                        </div>
                        <div id="tab4">
                            <div id="ticketSalesEvent"></div>
                            <input type="hidden" id="salesTimeoutId" name="salesTimeoutId" />
                        </div>
                        <div id="tab5">
                            <div id="transferGrid"></div>
                            <input type="hidden" id="transferTimeoutId" name="transferTimeoutId" />
                        </div>
                        <div id="tab6">
                            <div id="openGrid"></div>
                            <input type="hidden" id="openTimeoutId" name="openTimeoutId" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</section>
@endsection
{{-- page level scripts --}}
<script src="assets/kendoui/js/jquery.min.js" type="text/javascript"></script>
@section('footer_scripts')
<script type="text/x-kendo-template" id="template">
<div class="tabstrip">
    <ul>
        <li class="k-state-active">
            Purchased
        </li>
        <li>
            Missed
        </li>
    </ul>
    <div id="tab1">
        <div class="ticketPurchasedDetails"></div>
    </div>
    <div id="tab2">
        <div class="ticketMissedDetails"></div>
    </div>
</div>
<br>
<div id="proxyDetails"></div>
</script>
    <script type="text/javascript" src="{{asset('assets/kendoui/js/kendo.all.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('assets/js/custom_js/Ticket/ticketList.js')}}"></script>
    <script type="text/javascript" src="{{asset('assets/js/custom_js/Transfer/Transfer.js')}}"></script>
    <script id="openHeaderAction" type="text/x-kendo-template">
        <div class="searchToolBar" style="float: left;">
            <input type="button" class="k-button" id="openRefreshButton" onkeypress="return noenter()">
        </div>
        <div class="searchToolBar" style="float: right;">
            <label class="search-label" for="searchBox">Search Grid:</label>
            <input type="search" id="openSearchBox" class="k-textbox" style="width: 250px"/>
            <input type="button" id="openBtnSearch" class="k-button" value="Search"/>
            <input type="button" id="openBtnReset" class="k-button" value="Reset"/>
        </div>
    </script>
    <script id="transferHeaderAction" type="text/x-kendo-template">
        <div class="searchToolBar" style="float: left;">
            <input type="button" class="k-button" id="transferRefreshButton" onkeypress="return noenter()">
        </div>
        <div class="searchToolBar" style="float: right;">
            <label class="search-label" for="searchBox">Search Grid:</label>
            <input type="search" id="transferSearchBox" class="k-textbox" style="width: 250px"/>
            <input type="button" id="transferBtnSearch" class="k-button" value="Search"/>
            <input type="button" id="transferBtnReset" class="k-button" value="Reset"/>
        </div>
    </script>
    <script id="TransactionSearch" type="text/x-kendo-template">
        <div class="searchToolBar" style="float: left;">
            <input type="button" class="refreshButton k-button" id="refreshButton" onkeypress="return noenter()">
        </div>
        <div class="searchToolBar" style="float: right;">
            <label class="search-label" for="searchBox">Search Grid:</label>
            <input type="search" id="transactionSearchBox" class="k-textbox" style="width: 250px"/>
            <input type="button" id="transactionBtnSearch" class="k-button" value="Search"/>
            <input type="button" id="transactionBtnReset" class="k-button" value="Reset"/>
        </div>
    </script>
    <script id="PurchasedSearch" type="text/x-kendo-template">
        <div class="searchToolBar" style="float: left;">
            <input type="button" class="k-button" id="purchaseRefreshButton" onkeypress="return noenter()">
        </div>
        <div class="searchToolBar" style="float: right;">
            <label class="search-label" for="searchBox">Search Grid:</label>
            <input type="search" id="purchaseSearchBox" class="k-textbox" style="width: 250px"/>
            <input type="button" id="purchaseBtnSearch" class="k-button" value="Search"/>
            <input type="button" id="purchaseBtnReset" class="k-button" value="Reset"/>
        </div>
    </script>
    <script id="MissedSearch" type="text/x-kendo-template">
        <div class="searchToolBar" style="float: left;">
            <input type="button" class="k-button" id="missedRefreshButton" onkeypress="return noenter()">
        </div>
         <div class="searchToolBar" style="float: right;">
            <label class="search-label" for="searchBox">Search Grid:</label>
            <input type="search" id="missedSearchBox" class="k-textbox" style="width: 250px"/>
            <input type="button" id="missedBtnSearch" class="k-button" value="Search"/>
            <input type="button" id="missedBtnReset" class="k-button" value="Reset"/>
        </div>
    </script>
    <script id="SalesSearch" type="text/x-kendo-template">
        <div class="searchToolBar" style="float: left;">
            <input type="button" class="k-button" id="salesRefreshButton" onkeypress="return noenter()">
        </div>
        <div class="searchToolBar" style="float: right;">
            <label class="search-label" for="searchBox">Search Grid:</label>
            <input type="search" id="salesSearchBox" class="k-textbox" style="width: 250px"/>
            <input type="button" id="salesBtnSearch" class="k-button" value="Search"/>
            <input type="button" id="salesBtnReset" class="k-button" value="Reset"/>
        </div>
    </script>
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
            var $toast = toastr["{{ Session::pull('type') }}"]("", "{{ Session::pull('msg') }}");
        @endif
    </script>
@stop
