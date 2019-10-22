@extends('layouts/default')

{{-- Page title --}}
@section('title')
    Bank
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
        .k-grid-norecords
        {
            height: auto !important;
        }
        #identityCityListForm label{
            font-weight: bold;
        }

        #identityCityListForm div.form-group{
            background-color: #d9ecf5;
            padding: 15px 10px;
        }

        .k-i-close{
            margin: 1px 0 !important;
        }

        #tabstrip div.row{
            margin: 10px 0px;
        }

        #locationInfo thead tr{
            width: 100%;
            background-color: #d9ecf5;
            color: #003f59
        }
    </style>
    <!--end of page level css-->
@stop
@section('content')
<section class="content-header">
    <h1>Bank</h1>
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
            Bank
        </li>
    </ol>
</section>
<section class="content">
    <section class="content p-l-r-15">
        <div class="preloader" style="background: none !important; ">
            <div class="loader_img">
                <img src="{{asset('assets/img/loader.gif')}}" alt="loading..." height="64" width="64">
            </div>
        </div>
        <div class="row">
            <div class="panel panel-primary ">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <i class="fa fa-fw fa-users"></i> Bank
                    </h4>
                </div>
                <input type="hidden" id="requestUrl" value="{!!url(Request::segment(1))!!}">
                <input type="hidden" id="default_country_id" value="{{$defaultCountry->country_id}}">
                <div class="panel-body">
                    <form method='POST' action='{!!url("bank")!!}' id="identityCityListForm">
                        {{ csrf_field() }}
                        <div class="row" class="col-md-12">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="bank_name">Bank Name</label><br>
                                    <input type="text" id="bank_name" name="bank_name" style="width: 100%" />
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="clearing_code">Clearing Code</label><br>
                                    <input type="text" id="clearing_code" name="clearing_code" style="width: 100%" />
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="local_name">Local Name</label><br>
                                    <input type="text" id="local_name" name="local_name" style="width: 100%" />
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="country_origin">Origin Country</label><br>
                                    <input id="country_origin" name="country_origin" style="width: 100%" />
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="swift_bic">Swift Bic</label><br>
                                    <input type="text" id="swift_bic" name="swift_bic" style="width: 100%" />
                                </div>
                            </div>
                        </div>
                        <div class="row" class="col-md-12">
                            <div class="col-md-3">
                                <button type="button" id="submitBtn" class="send-btn k-button">Add Bank</button>
                            </div>
                        </div>
                    </form>
                    <div id="bankGrid"></div>
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
<script type="text/javascript" src="{{asset('assets/js/custom_js/Bank.js')}}"></script>

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