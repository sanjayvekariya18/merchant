@extends('layouts/default')

{{-- Page title --}}
@section('title')
    Approval Routing
    @parent
@stop

{{-- page level styles --}}
@section('header_styles')
    <!--page level css -->
    <link rel="stylesheet" href="assets/kendoui/styles/kendo.common.min.css">
    <link rel="stylesheet" href="assets/kendoui/styles/kendo.rtl.min.css">
    <link rel="stylesheet" href="assets/kendoui/styles/kendo.default.min.css">
    <link rel="stylesheet" href="assets/kendoui/styles/kendo.mobile.all.min.css">
    <link rel="stylesheet" href="assets/kendoui/styles/kendo.blueopal.min.css" />
    <style>
        table thead tr{
            background-color: #d9ecf5;
            color: #003f59
        }
        #approvalRoutingGrid table tr th,
        #approvalRoutingGrid table tr td{
            width: 33%;
        }

        span.k-error{
            color: red;
        }
        .k-alert{
            top:250px !important;
            min-width: 200px !important;
        }
        
        .k-grid .k-grid-toolbar .k-grid-add,
        .k-grid tbody .k-grid-edit,
        .k-grid tbody .k-grid-update,
        .k-grid tbody .k-grid-cancel,
        .k-grid tbody .k-grid-delete {
            min-width: 0;
        }

        .k-grid .k-grid-toolbar .k-grid-add .k-icon,
        .k-grid tbody .k-grid-edit .k-icon,
        .k-grid tbody .k-grid-update .k-icon,
        .k-grid tbody .k-grid-cancel .k-icon,
        .k-grid tbody .k-grid-delete .k-icon {
            margin: 0;
        }
    </style>
    <!--end of page level css-->
@stop
@section('content')
<section class="content-header">
    <h1>Approval Routing</h1>
    <ol class="breadcrumb">
        <li>
            <a href="index ">
                <i class="fa fa-fw fa-home"></i> Dashboard
            </a>
        </li>
        <li>
            <a href="#"> System</a>
        </li>
        <li class="active">
            Approval Routing
        </li>
    </ol>
</section>
<section class="content">
    <section class="content p-l-r-15">
        <div class="row">
            <div class="panel panel-primary ">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <i class="fa fa-fw fa-users"></i> Approval Routing
                    </h4>
                </div>
                <input type="hidden" id="requestUrl" value="{!!url(Request::segment(1))!!}">
                <input type="hidden" id="merchantEditable" value="{!!$merchantEditable!!}">
                <div class="panel-body">
                    <div id="approvalRoutingGrid"></div>
                </div>
            </div>
        </div>
    </section>
</section>
@endsection
<script src="assets/kendoui/js/jquery.min.js" type="text/javascript"></script>
{{-- page level scripts --}}
@section('footer_scripts')
<!-- begining of page level js -->
<script src="assets/kendoui/js/kendo.all.min.js"></script>
<script type="text/javascript" src="{{asset('assets/js/custom_js/ApprovalRoutingGrid.js')}}"></script>

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