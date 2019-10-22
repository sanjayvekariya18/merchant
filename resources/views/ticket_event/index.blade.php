@extends('layouts/default')

{{-- Page title --}}
@section('title')
    Artifacts
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
    <h1>Artifacts</h1>
    <ol class="breadcrumb">
        <li>
            <a href="index ">
                <i class="fa fa-fw fa-home"></i> Dashboard
            </a>
        </li>
        <li class="active">
            Artifacts
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
                        <i class="fa fa-fw fa-users"></i> Artifacts
                    </h4>
                </div>
                <div class="panel-body">
                    <input type="hidden" id="requestUrl" value="{!!url(Request::segment(1))!!}">
                    <div id="tabstrip">
                        <ul>
                            <li class="k-state-active" >Production</li>
                            <li>Service</li>
                            <li>Venue</li>
                            <li>Opponent</li>
                        </ul>
                        <div id="tab1">
                            <div id="ticketProducationDetails"></div>
                        </div>
                        <div id="tab2">
                            <div id="ticketServiceDetails"></div>
                        </div>
                        <div id="tab3">
                            <div id="ticketVenueDetails"></div>
                        </div>
                        <div id="tab4">
                            <div id="ticketOpponentDetails"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- form-modal -->
                <div id="production_json_top_modal" class="modal fade animated position_modal" role="dialog">
                    <div class="modal-dialog">
                    <div class="modal-content" style="background-color: #d9ecf5">
                        <div class="modal-header" style="background-color: #d9ecf5;">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">Production Json</h4>
                        </div>
                        <div class="panel panel-primary" style="border-color: snow;">
                            <div class="panel-body" >
                                <div class="preloader" style="background: none !important; ">
                                    <div class="loader_img">
                                        <img src="{{asset('assets/img/loader.gif')}}" alt="loading..." height="64" width="64">
                                    </div>
                                </div>
                                <textarea cols="70" id="productionJsonDetail" ></textarea>
                                <br><br>
                                <button  class="k-button" onclick="productionJsonCopy()">Copy Json</button>   
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <!-- form-modal end -->
    </section>
</section>
@endsection
{{-- page level scripts --}}
<script src="assets/kendoui/js/jquery.min.js" type="text/javascript"></script>
@section('footer_scripts')
    <script type="text/javascript" src="{{asset('assets/kendoui/js/kendo.all.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('assets/js/custom_js/Ticket/ticketEvent.js')}}"></script>
    <script id="productionGridSearch" type="text/x-kendo-template">
        <div class="searchToolBar" style="float: right;">
            <label class="search-label" for="searchBox">Search Grid:</label>
            <input type="search" id="productionSearchBox" class="k-textbox" style="width: 250px"/>
            <input type="button" id="productionBtnSearch" class="k-button" value="Search"/>
            <input type="button" id="productionBtnReset" class="k-button" value="Reset"/>
        </div>
    </script>

    <script id="venueGridSearch" type="text/x-kendo-template">
        <div class="searchToolBar" style="float: right;">
            <label class="search-label" for="searchBox">Search Grid:</label>
            <input type="search" id="venueSearchBox" class="k-textbox" style="width: 250px"/>
            <input type="button" id="venueBtnSearch" class="k-button" value="Search"/>
            <input type="button" id="venueBtnReset" class="k-button" value="Reset"/>
        </div>
    </script>

    <script id="opponentGridSearch" type="text/x-kendo-template">
        <div class="searchToolBar" style="float: right;">
            <label class="search-label" for="searchBox">Search Grid:</label>
            <input type="search" id="opponentSearchBox" class="k-textbox" style="width: 250px"/>
            <input type="button" id="opponentBtnSearch" class="k-button" value="Search"/>
            <input type="button" id="opponentBtnReset" class="k-button" value="Reset"/>
        </div>
    </script>

    <script id="serviceGridSearch" type="text/x-kendo-template">
        <div class="searchToolBar" style="float: right;">
            <label class="search-label" for="searchBox">Search Grid:</label>
            <input type="search" id="serviceSearchBox" class="k-textbox" style="width: 250px"/>
            <input type="button" id="serviceBtnSearch" class="k-button" value="Search"/>
            <input type="button" id="serviceBtnReset" class="k-button" value="Reset"/>
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
