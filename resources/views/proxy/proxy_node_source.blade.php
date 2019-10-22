@extends('layouts/default')
{{-- Page title --}}
@section('title')
    Proxy Node Surce
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
        .k-selected-color{
            width: 75% !important;
        }
        table thead tr{
            background-color: #d9ecf5;
            color: #003f59
        }
        table tr th,table tr td {
            width: 25%;
        }
    </style>
    <!--end of page level css-->
@stop
@section('content')
<section class="content-header">
    <ol class="breadcrumb">
        <li>
            <a href="index ">
                <i class="fa fa-fw fa-home"></i> Dashboard
            </a>
        </li>
        <li>
            <a href="#"> Proxy </a>
        </li>
        <li class="active">
            Details
        </li>
    </ol>
</section>
<section class="content">
    <section class="content p-l-r-15">
        <div class="row">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <i class="fa fa-fw fa-users"></i>Proxy Details
                    </h4>
                </div>
                <input type="hidden" id="requestUrl" value="{!!url(Request::segment(1))!!}">
                {{ csrf_field() }}
                <div class="panel-body">

                            <form method='POST' action='{!!url("proxy_node_source")!!}' id="proxyNodeSourceForm">
                                {{ csrf_field() }}
                            </form>
                            <div id="tabstrip">
                                <ul>
                                    <li class="k-state-active" >Proxy Details</li>
                                    <li>Proxy Status</li>
                                </ul>
                                <div id="tab1">
                                    <div id="proxyNodeSourceList"></div>
                                </div>
                                <div id="tab2">
                                    <div id="proxyStatusColorListGrid"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</section>
@endsection
<script src="{{asset('assets/kendoui/js/jquery.min.js')}}" type="text/javascript"></script>
@section('footer_scripts')
<script type="text/javascript" src="{{asset('assets/kendoui/js/kendo.all.min.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/js/custom_js/ProxyNodeSource.js')}}"></script>
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
