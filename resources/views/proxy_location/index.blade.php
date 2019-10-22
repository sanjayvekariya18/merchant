@extends('layouts/default')
{{-- Page title --}}
@section('title')
    Proxy Location
    @parent
@stop
{{-- page level styles --}}
@section('header_styles')
@stop


<link rel="stylesheet" href="{{asset('assets/kendoui/styles/kendo.common.min.css')}}">
<link rel="stylesheet" href="{{asset('assets/kendoui/styles/kendo.rtl.min.css')}}">
<link rel="stylesheet" href="{{asset('assets/kendoui/styles/kendo.default.min.css')}}">
<link rel="stylesheet" href="{{asset('assets/kendoui/styles/kendo.mobile.all.min.css')}}">
<link rel="stylesheet" href="{{asset('assets/kendoui/styles/kendo.blueopal.min.css')}}" />

<style type="text/css">

    .panel-body label{
        font-weight: bold;
    }
    .panel-body div.form-group{
        background-color: #d9ecf5;
        padding: 15px 10px;
    }
</style>

@section('content')
<section class="content-header">
    <h1>Proxy Location</h1>
      <ol class="breadcrumb">
          <li><a href="index "><i class="fa fa-fw fa-home"></i> Dashboard</a></li>
          <li><a href="#"> Sync Proxy Location</a></li>
      </ol>
</section>
<section class="content">
    <div class="preloader" style="background: none !important; ">
        <div class="loader_img">
            <img src="{{asset('assets/img/loader.gif')}}" alt="loading..." height="64" width="64">
        </div>
    </div>
    <form class = 'col s3' method = 'get' action = '{!!url("proxy_location")!!}/create'>
        <button id = 'synclocation' class = 'btn btn-primary' type = 'button'>Synchronize Proxy Location</button>
        <label class="btn btn-success totalrecord" style="display: none">Total : 2</label>
    </form>
    <section class="content p-l-r-15">
        <div class="row">
            <div class="panel panel-primary ">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <i class="fa fa-fw fa-users"></i> Proxies Location Information
                    </h4>
                </div>
                <input type="hidden" id="requestUrl" value="{{url('proxy_location')}}">
                {{ csrf_field() }}
                <div class="panel-body">

                    <div class="row">
                        <div class="col-md-12">
                            <div id="proxyGrid"></div>
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
<script type="text/javascript" src="{{asset('assets/js/custom_js/ProxyLocationIndex.js')}}"></script>
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
