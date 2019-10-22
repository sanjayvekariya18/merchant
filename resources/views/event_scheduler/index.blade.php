@extends('layouts/default')

{{-- Page title --}}
@section('title')
    Event Scheduler
    @parent
@stop

{{-- page level styles --}}
@section('header_styles')
    <!--page level css -->
    <link rel="stylesheet" type="text/css" href="assets/kendoui/styles/kendo.common.min.css">
    <link rel="stylesheet" type="text/css" href="assets/kendoui/styles/kendo.mobile.all.min.css">
    <link rel="stylesheet" type="text/css" href="assets/kendoui/styles/kendo.sohyper.css">
    <link rel="stylesheet" type="text/css" href="assets/kendoui/styles/kendo.blueopal.min.css">
    <link rel="stylesheet" type="text/css" href="assets/css/custom_css/Scheduler/EventScheduler.css">
    <link rel="stylesheet" type="text/css" href="assets/css/custom_css/SocialEvent/SocialEvent.css">
    <!--end of page level css-->
@stop
@section('content')
<section class="content-header">
    <h1>Event Scheduler</h1>
    <ol class="breadcrumb">
        <li>
            <a href="index ">
                <i class="fa fa-fw fa-home"></i> Dashboard
            </a>
        </li>
        <li class="active">
            Event Scheduler
        </li>
    </ol>
</section>
<section class="content">
    <div class="successMessage">
        <div class="note note-success">
            <i class="fa fa-success fa-2x pull-left"></i>
            <span>Event Successfully saved</span>
        </div>
    </div>
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
                        <i class="fa fa-fw fa-users"></i> Event Scheduler
                    </h4>
                </div>
                <div class="panel-body">
                    <input type="hidden" id="requestUrl" value="{!!url(Request::segment(1))!!}">
                    <div id="event_scheduler" ></div>
                    <div id="commentsWindow"></div>
                </div>
            </div>
        </div>
    </section>
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
</section>
@endsection

{{-- page level scripts --}}
<script id="editor" type="text/x-kendo-template">
    <div class="k-edit-form-container">
        <div class="errorMessage">
            <div class="note note-danger">
                <i class="fa fa-warning fa-2x pull-left"></i>
                <span>Problem in saving events</span>
            </div>
        </div>
        <div class="k-edit-label">
            <label for="title">Title</label>
        </div>
        <div data-container-for="title" class="k-edit-field">
            <input type="text" class="k-input k-textbox" name="title" title="Title" required="required" data-bind="value:title">
        </div>
        <div class="k-edit-label">
            <label for="start">Start</label>
        </div>
        <div data-container-for="start" class="k-edit-field">
            <span class="k-widget k-datetimepicker k-header" >
                <span class="k-picker-wrap k-state-default">
                    <input type="text" required="" data-type="date" data-role="datetimepicker" data-bind="value:start,invisible:isAllDay" data-validate="true" name="start" title="Start" class="k-input" role="combobox" aria-expanded="false" aria-disabled="false" >
                    <span unselectable="on" class="k-select">
                        <span class="k-link k-link-date" aria-label="Open the date view">
                            <span unselectable="on" class="k-icon k-i-calendar"></span>
                        </span>
                        <span class="k-link k-link-time" aria-label="Open the time view">
                            <span unselectable="on" class="k-icon k-i-clock"></span>
                        </span>
                    </span>
                </span>
            </span>
            <span class="k-widget k-datepicker k-header" >
                <span class="k-picker-wrap k-state-default">
                    <input type="text" required="" data-type="date" data-role="datepicker" data-bind="value:start,visible:isAllDay" data-validate="false" name="start" title="Start" class="k-input" role="combobox" aria-expanded="false" aria-disabled="false" >
                    <span unselectable="on" class="k-select" aria-label="select" role="button">
                        <span class="k-icon k-i-calendar"></span>
                    </span>
                </span>
            </span>
            <span data-bind="text: startTimezone"></span>
            <span data-for="start" class="k-invalid-msg" ></span>
        </div>
        <div class="k-edit-label">
            <label for="end">End</label>
        </div>
        <div data-container-for="end" class="k-edit-field">
            <span class="k-widget k-datetimepicker k-header" >
                <span class="k-picker-wrap k-state-default">
                    <input type="text" required="" data-type="date" data-role="datetimepicker" data-bind="value:end,invisible:isAllDay" data-validate="true" name="end" title="End" class="k-input" role="combobox" aria-expanded="false" aria-disabled="false" >
                    <span unselectable="on" class="k-select">
                        <span class="k-link k-link-date" aria-label="Open the date view">
                            <span unselectable="on" class="k-icon k-i-calendar"></span>
                        </span>
                        <span class="k-link k-link-time" aria-label="Open the time view">
                            <span unselectable="on" class="k-icon k-i-clock"></span>
                        </span>
                    </span>
                </span>
            </span>
            <span class="k-widget k-datepicker k-header" >
                <span class="k-picker-wrap k-state-default">
                    <input type="text" required="" data-type="date" data-role="datepicker" data-bind="value:end,visible:isAllDay" data-validate="false" name="end" title="End" class="k-input" role="combobox" aria-expanded="false" aria-disabled="false" >
                    <span unselectable="on" class="k-select" aria-label="select" role="button">
                        <span class="k-icon k-i-calendar"></span>
                    </span>
                </span>
            </span>
            <span data-bind="text: endTimezone"></span>
            <span data-bind="text: startTimezone, invisible: endTimezone"></span>
            <span data-for="end" class="k-invalid-msg" ></span>
        </div>
        <div class="k-edit-label">
            <label for="isAllDay">All day event</label>
        </div>
        <div data-container-for="isAllDay" class="k-edit-field">
            <input type="checkbox" name="isAllDay" title="All day event" data-type="boolean" data-bind="checked:isAllDay">
        </div>
    </div>
</script>
<script type="text/x-kendo-template" id="commentTemplate">
    <div id="details-container">
        <textarea id="unsharecomments" rows="5" column="7" placeholder="Add Comment" onclick="textareHeightWidth()"></textarea>
        <button type="button" class='k-button' onclick="shareStatusWithGroup()">Save</button>
        <button type="button" class='k-button' onclick="closeCommentEvent()">Cancel</button>
    </div>
</script>
<script id="event-template" type="text/x-kendo-template">
    <div class="eventTitle">
        #if(typeof website_link !== 'undefined') {# <a target="_blank" href="#= website_link #">#: title #</a> #} else {# #:title # #}#
        
    </div>
</script>
<script id="eventDetails" type="text/x-kendo-template">
    #var uid = target.parent().attr("data-uid");#
    #var scheduler = target.closest("[data-role=scheduler]").data("kendoScheduler");#
    #var model = scheduler.occurrenceByUid(uid);#
    #if(model) {#
        <div class="movie-template">
            <div class="tooltipImage">
                #if(model.avatar_link) {# <img src="#= model.avatar_link #"> #}#
            </div>
            <div class="tooltipDetails">
                <h6>#: model.title #</h6>
                
                <p class="eventTooltipTime">Time : #: kendo.toString(model.start, "HH:mm") # - #: kendo.toString(model.end, "HH:mm") #</p>
                
                #if(model.location) {# <p class="eventToolTipCity">City : #=model.location#</p> #}#
            </div>
        </div>
    #} else {#
        <strong>No event data is available</strong>
    #}#
</script>
<script src="assets/kendoui/js/jquery.min.js" type="text/javascript"></script>
@section('footer_scripts')
    <script type="text/javascript" src="{{asset('assets/kendoui/js/kendo.all.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('assets/kendoui/tree/SohyperTree.js')}}"></script>
    <script type="text/javascript" src="{{asset('assets/js/custom_js/SocialEvent/SocialEvent.js')}}"></script>
    <script type="text/javascript" src="{{asset('assets/js/custom_js/scheduler/eventScheduler.js')}}"></script>
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
