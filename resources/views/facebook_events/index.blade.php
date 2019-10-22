@extends('layouts/default')

{{-- Page title --}}
@section('title')
    Facebook Events
    @parent
@stop

{{-- page level styles --}}
@section('header_styles')
    <!--page level css -->
    <link rel="stylesheet" type="text/css" href="assets/kendoui/styles/kendo.common.min.css">
    <link rel="stylesheet" type="text/css" href="assets/kendoui/styles/kendo.mobile.all.min.css">
    <link rel="stylesheet" type="text/css" href="assets/kendoui/styles/kendo.blueopal.min.css">
    <link rel="stylesheet" type="text/css" href="assets/css/custom_css/Facebook/FacebookEvents.css">
    <!--end of page level css-->
@stop
@section('content')
<section class="content-header">
    <h1>Facebook Events</h1>
    <ol class="breadcrumb">
        <li>
            <a href="index ">
                <i class="fa fa-fw fa-home"></i> Dashboard
            </a>
        </li>
        <li class="active">
            Facebook Events
        </li>
    </ol>
</section>
<section class="content">
    <div class="preloader" style="background: none !important; ">
        <div class="loader_img">
            <img src="{{asset('assets/img/loader.gif')}}" alt="loading..." height="64" width="64">
        </div>
    </div>
    <div class="row">
        <div class="col-md-8">
            @if($tokenData)
                <?php if (in_array("add", $permissions)): ?>
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <td><button type="button" id="searchMyEventbriteEvents" data-url="/me/events/" name="submitBtn" class="send-btn k-button searchOwnEvents">Load Events</button></td>
                            </tr>
                        </tbody>
                    </table>
                <?php endif;?>
            @else
                <div class="note note-danger">
                    <i class="fa fa-warning fa-2x pull-left"></i>
                    <span>Please login to <a id="facebookLoginRedirect" href='#'>Facebook</a>.</span>
                </div>
            @endif
        </div>
    </div>
    <div id="top_modal" class="modal fade animated position_modal" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header" style="background-color: #d9ecf5;">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Share Event</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <form method='POST' action='{!!url("shareToGroup")!!}' id="shareToGroup">
                                {{ csrf_field() }}
                                <div class="preloader" style="background: none !important; ">
                                    <div class="loader_img">
                                        <img src="{{asset('assets/img/loader.gif')}}" alt="loading..." height="64" width="64">
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table id=shareGroupInfo class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th width="30%">Event Name</th>
                                                <th width="70%">Shared Group</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td id="sharedEventName"></td>
                                                <td id="sharedGroupMultiselect"></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <input type="hidden" id="sharedEventId" name="sharedEventId" />
                                <input type="hidden" id="previousGroupCount" name="previousGroupCount" />
                                <input type="hidden" id="sharedEventGroupList" name="sharedEventGroupList" />
                                <div class="modal-footer" style="padding-left: 33%;text-align:inherit">
                                    <button type="button" id="shareEventToGroup" value="1" class="btn btn-success">Share</button>
                                    <button type="button" class="btn btn-danger" data-dismiss="modal">
                                        Close
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <section class="content p-l-r-15">
        <div class="row">
            <div class="panel panel-primary ">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <i class="fa fa-fw fa-users"></i> UnSync Events
                    </h4>
                </div>
                <input type="hidden" id="requestUrl" value="{!!url(Request::segment(1))!!}">
                <div>
                    <div class="panel-body facebookPanel">
                        <div id="facebookSyncEvent"></div>
                    </div>
                </div>
            </div>
            <div class="panel panel-primary ">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <i class="fa fa-fw fa-users"></i> Facebook Events
                    </h4>
                </div>
                <input type="hidden" id="requestUrl" value="{!!url(Request::segment(1))!!}">
                <input type="hidden" id="baseUrl" value="{!!url('/')!!}">
                <div>
                    <div class="panel-body facebookPanel">
                        <div id="facebookEvents"></div>
                        <div id="commentsWindow"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</section>
@endsection
<script src="assets/kendoui/js/jquery.min.js" type="text/javascript"></script>
@section('footer_scripts')
    <script type="text/javascript" src="{{asset('assets/kendoui/js/kendo.all.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('assets/js/custom_js/UnixDateTime.js')}}"></script>
    <script type="text/javascript" src="{{asset('assets/js/custom_js/Facebook/FacebookEvents.js')}}"></script>
    <script type="text/x-kendo-template" id="commentTemplate">
        <div id="details-container">
            <textarea id="unsharecomments" rows="5" column="7" placeholder="Add Comment" onclick="textareHeightWidth()"></textarea>
            <button type="button" class='k-button' onclick="shareStatusWithGroup()">Save</button>
            <button type="button" class='k-button' onclick="closeCommentEvent()">Cancel</button>
        </div>
    </script>
    <script type="text/x-kendo-template" id="syncEventTemplate">
        <button type="button" class='k-button' onclick="syncBatchEvent('checkedEvents')">Sync Events</button>
        <button type="button" class='k-button' onclick="syncBatchEvent('allEvents')">Sync All Events</button>
    </script>
    <script id="facebookGridSearch" type="text/x-kendo-template">
          <label class="search-label" for="searchBox">Search Grid:</label>
          <input type="search" id="facebookSearchBox" class="k-textbox" style="width: 250px"/>
          <input type="button" id="facebookBtnSearch" class="k-button" value="Search"/>
          <input type="button" id="facebookBtnReset" class="k-button" value="Reset"/>
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