@extends('layouts/default')

{{-- Page title --}}
@section('title')
    Eventbrite Events
    @parent
@stop

{{-- page level styles --}}
@section('header_styles')
    <!--page level css -->
    <link rel="stylesheet" type="text/css" href="assets/kendoui/styles/kendo.common.min.css">
    <link rel="stylesheet" type="text/css" href="assets/kendoui/styles/kendo.mobile.all.min.css">
    <link rel="stylesheet" type="text/css" href="assets/kendoui/styles/kendo.sohyper.css">
    <link rel="stylesheet" type="text/css" href="assets/kendoui/styles/kendo.blueopal.min.css">
    <link rel="stylesheet" type="text/css" href="assets/css/custom_css/SocialEvent/SocialEvent.css">
    <link rel="stylesheet" type="text/css" href="assets/css/custom_css/Eventbrite/EventbriteEvents.css">
    <!--end of page level css-->
@stop
@section('content')
<section class="content-header">
    <h1>Eventbrite Events</h1>
    <ol class="breadcrumb">
        <li>
            <a href="index ">
                <i class="fa fa-fw fa-home"></i> Dashboard
            </a>
        </li>
        <li class="active">
            Eventbrite Events
        </li>
    </ol>
</section>
<section class="content">
    <div class="preloader">
        <div class="loader_img">
            <img src="{{asset('assets/img/loader.gif')}}" alt="loading..." height="64" width="64">
        </div>
    </div>
    <div class="row">
        <div class="col-md-8">
            @if($tokenData)
                <?php if (in_array("add", $permissions)): ?>
                    <div class="syncGroupListDiv">
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <td><a data-toggle="modal" data-target="#searchOtherEvents" class="send-btn k-button" >Search Events</a></td>
                                    <td><button type="button" id="searchMyEventbriteEvents" data-url="/users/me/owned_events/" name="submitBtn" class="send-btn k-button searchOwnEvents">Search Own Events</button></td>
                                    <td><button type="button" id="searchBookmarkEvents" name="submitBtn" class="send-btn k-button searchOwnEvents" data-url="/users/me/bookmarks/">Bookmarked Events</button></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                <?php endif;?>
            @else
                <div class="note note-danger">
                    <i class="fa fa-warning fa-2x pull-left"></i>
                    <span>Please login to <a id="eventBriteLoginRedirect" href='#'>Eventbrite</a>.</span>
                </div>
            @endif
        </div>
    </div>
    <div id="top_modal" class="modal fade animated position_modal" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header sharedGroupHeaderPanel">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Share Event</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <form method='POST' action='{!!url("shareToGroup")!!}' id="shareToGroup">
                                {{ csrf_field() }}
                                <div class="preloader">
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
                                <div class="modal-footer sharedGroupFooterPanel">
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
                        <i class="fa fa-fw fa-users"></i> Eventbrite Events
                    </h4>
                </div>
                <input type="hidden" id="requestUrl" value="{!!url(Request::segment(1))!!}">
                <div>
                    <div class="panel-body eventListPanel">
                        <div id="unSyncGroupPanel"></div>
                    </div>
                </div>
            </div>
            <div class="panel panel-primary ">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <i class="fa fa-fw fa-users"></i> Eventbrite Events
                    </h4>
                </div>
                <input type="hidden" id="requestUrl" value="{!!url(Request::segment(1))!!}">
                <input type="hidden" id="baseUrl" value="{!!url('/')!!}">
                <div>
                    <div class="panel-body eventListPanel">
                        <div id="eventbriteEvents" class="eventListGridPanel"></div>
                        <div id="commentsWindow"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <div class="modal fade" id="eventCategories" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">select categories</h4>
                </div>
                <div class="modal-body">
                    <form id="event_category_form" method = 'POST' action = '{!!url(Request::segment(1))!!}' class="form-horizontal" enctype="multipart/form-data">
                        <input type = 'hidden' name = '_token' value = '{{Session::token()}}'>
                        <input type = 'hidden' name='categoryEventId' id='categoryEventId' />
                        <input type="hidden" name="selectedCategory" id="selectedCategory" />
                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                <h3 class="panel-title">
                                    <i class="livicon" data-name="user-add" data-size="18" data-c="#fff" data-hc="#fff" data-loop="true"></i>Event Categories
                                </h3>
                            </div>
                            <div class="panel-body">
                                <div class="col-sm-6">
                                    <div class="row">
                                        <div class="form-group">
                                            <label for="price" class="col-sm-12">Merchant Type</label>
                                            <div class="col-sm-12">
                                                <select id="merchantTypeList"></select>
                                            </div>

                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group">
                                            <label for="Category Tree" class="col-sm-12">Select Categories</label>
                                            <div class="col-sm-12"> 
                                                <select id="categoryMultiSelect" id="categoryMultiSelect[]"></select>
                                                <div class="demo-section" id="categoryTreeView"></div>
                                                
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-success" id="saveEventCategories" >
                            <span class="glyphicon glyphicon-ok-sign"></span> Save
                        </button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal">
                            <span class="glyphicon glyphicon-remove"></span> cancel
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="searchOtherEvents" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Filter Search</h4>
                </div>
                <div class="modal-body">
                    <form id="event_filter_form" method = 'POST' action = '{!!url(Request::segment(1))!!}' class="form-horizontal" enctype="multipart/form-data">
                        <input type = 'hidden' name = '_token' value = '{{Session::token()}}'>
                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                <h3 class="panel-title">
                                    <i class="livicon" data-name="user-add" data-size="18" data-c="#fff" data-hc="#fff" data-loop="true"></i>Filter Eventbrite
                                </h3>
                            </div>
                            <div class="panel-body">
                                <input id="latitude" name="latitude" type="hidden"  class="form-control">
                                <input id="longitude" name="longitude" type="hidden"  class="form-control">
                                <div class="col-sm-4">
                                    <div class="row">
                                        <div class="form-group">
                                            <label for="price" class="col-sm-12">Event Type</label>
                                            <div class="col-sm-12">
                                                <select id="price" name="price[]"></select>
                                            </div>

                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group">
                                            <label for="style_priority" class="col-sm-12">Keyword</label>
                                            <div class="col-sm-12">
                                                <input id="keyword" name="keyword" type="text"  class="form-control required">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group">
                                            <label for="distance" class="col-sm-12">Distance</label>
                                            <div class="col-sm-12">
                                                <input id="distance" name="distance" type="number"  class="form-control required">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group">
                                            <label for="sort_by" class="col-sm-12">Sort Order</label>
                                            <div class="col-sm-12">
                                                <select name="sort_by" id="sort_by" class="select21 form-control">
                                                    <option value="date">Date</option>
                                                    <option value="distance">Distance</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-8">
                                    <input id="address" class="controls" type="text" value="@if($postalData && $postalData->postal_premise !=''){!!$postalData->postal_premise!!}@endif" placeholder="Search Box">
                                    <div id="googleMap"></div>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-success" id="searchOtherEventbriteEvents" >
                            <span class="glyphicon glyphicon-ok-sign"></span> Search
                        </button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal">
                            <span class="glyphicon glyphicon-remove"></span> cancel
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
<script src="assets/kendoui/js/jquery.min.js" type="text/javascript"></script>
@section('footer_scripts')
    <script type="text/javascript" src="{{asset('assets/kendoui/js/kendo.all.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('assets/kendoui/tree/SohyperTree.js')}}"></script>
    <script type="text/javascript" src="{{asset('assets/js/custom_js/UnixDateTime.js')}}"></script>
    <script type="text/javascript" src="{{asset('assets/js/custom_js/SocialEvent/SocialEvent.js')}}"></script>
    <script type="text/javascript" src="{{asset('assets/js/custom_js/Eventbrite/eventbriteEvents.js')}}"></script>
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
    <script id="eventBriteGridSearch" type="text/x-kendo-template">
          <label class="search-label" for="searchBox">Search Grid:</label>
          <input type="search" id="eventBriteSearchBox" class="k-textbox" style="width: 250px"/>
          <input type="button" id="eventBriteBtnSearch" class="k-button" value="Search"/>
          <input type="button" id="eventBriteBtnReset" class="k-button" value="Reset"/>
    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDGAyiRaUhA8cntur2DvcZtcTG0VGDEer0&libraries=places&callback=initAutocomplete" async defer></script>
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