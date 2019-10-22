@extends('layouts/default')

{{-- Page title --}}
@section('title')
    Entity Relationship
    @parent
@stop

{{-- page level styles --}}
@section('header_styles')
    <!--page level css -->
    <link rel="stylesheet" type="text/css" href="assets/kendoui/styles/kendo.common.min.css">
    <link rel="stylesheet" type="text/css" href="assets/kendoui/styles/kendo.mobile.all.min.css">
    <link rel="stylesheet" type="text/css" href="assets/kendoui/styles/kendo.sohyper.css">
    <link rel="stylesheet" type="text/css" href="assets/kendoui/styles/kendo.blueopal.min.css">
    <!--end of page level css-->
@stop
@section('content')
<section class="content-header">
    <h1>Entity Relationship</h1>
    <ol class="breadcrumb">
        <li>
            <a href="index ">
                <i class="fa fa-fw fa-home"></i> Dashboard
            </a>
        </li>
        <li class="active">
            Entity Relationship
        </li>
    </ol>
</section>

<section class="content">
    <section class="content p-l-r-15">
        <div class="row">
            <div class="panel panel-primary ">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <i class="fa fa-fw fa-users"></i> Table Tree Creation
                    </h4>
                </div>
                <input type="hidden" id="requestUrl" value="{!!url(Request::segment(1))!!}">
                <div>
                    <div class="panel-body categoryPanel">
                        <form id="table_tree_create" method = 'POST' action = '{!!url(Request::segment(1))!!}' class="form-horizontal" enctype="multipart/form-data">
                            <input type = 'hidden' name = '_token' value = '{{Session::token()}}'>
                            <div class="col-sm-12">
                                <div class="row">
                                    <div class="form-group">
                                        <label for="sort_by" class="col-sm-12">Table List</label>
                                        <div class="col-sm-4">
                                            <input id="treeTableList" />
                                        </div>
                                        <div class="col-sm-2">
                                            <button type="submit" class="btn btn-success" id="searchOtherEventbriteEvents" >
                                                <span class="glyphicon glyphicon-ok-sign"></span> Generate
                                            </button>
                                        </div>
                                        
                                    </div>
                                </div>
                            </div>
                        </form>
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
    <script type="text/javascript" src="{{asset('assets/kendoui/js/kendo.all.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('assets/js/custom_js/EntityRelationship/EntityRelationship.js')}}"></script>
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
