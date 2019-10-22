@extends('layouts/default')
{{-- Page title --}}
@section('title')
    Portal Social Apikeys
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
    <!--end of page level css-->
@stop
@section('content')
<section class="content-header">
    <h1>Portal Social Apikeys</h1>
    <ol class="breadcrumb">
        <li>
            <a href="index ">
                <i class="fa fa-fw fa-home"></i> Dashboard
            </a>
        </li>
        <li class="active">
            Portal Social Apikeys
        </li>
    </ol>
</section>
<section class="content">
    <section class="content p-l-r-15">
        <div class="row">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <i class="fa fa-fw fa-users"></i>Portal Social Api Keys
                    </h4>
                </div>

                <input type="hidden" id="requestUrl" value="{{url('portal_social_api')}}">
                <input type="hidden" id="imageUrl" value="{{asset('/images/connectors')}}">
                {{ csrf_field() }}
                <div class="panel-body">

                    <div class="row">
                        <div class="col-md-12">
                            <div id="portalSocialApiGrid"></div>
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
<script type="text/javascript" src="{{asset('assets/kendoui/js/kendo.all.min.js')}}" type="text/javascript"></script>
<script type="text/javascript" src="{{asset('assets/js/custom_js/PortalSocialApi.js')}}"></script>
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