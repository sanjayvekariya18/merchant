@extends('layouts/default')
{{-- Page title --}}
@section('title')
Apikey Limits
@parent
@stop
{{-- page level styles --}}
@section('header_styles')
    <style>
        table thead tr{
            background-color: #d9ecf5;
            color: #003f59
        }        
        span.k-error{
            color: red;
        }     

        .k-confirm {
            top: 300px !important;
        }

        .k-grid-edit,.k-grid-delete,.k-grid-update,.k-grid-cancel {
           min-width: 35px !important;
           width: 35px !important;
        }       
    </style>
    
    <script src="assets/kendoui/js/jquery.min.js" type="text/javascript"></script>
    <link rel="stylesheet" type="text/css" href="assets/kendoui/styles/kendo.common.min.css">
    <link rel="stylesheet" type="text/css" href="assets/kendoui/styles/kendo.blueopal.min.css">
    <link href="{{ asset('la-assets/css/font-awesome.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('la-assets/css/ionicons.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('la-assets/css/AdminLTE.css') }}" rel="stylesheet" type="text/css" />

@stop
<?php
use App\Http\Traits\PermissionTrait;
?>
@section('content')
    <section class="content-header">
        <h1>Apikey Limits</h1>
        <ol class="breadcrumb">
            <li><a href="index "><i class="fa fa-fw fa-home"></i> Dashboard</a></li>
            <li><a href="active">Apikey Limits</a></li>
        </ol>
    </section>
    <section class="content">
        <section class="content p-l-r-15">
            <div class="row">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h4 class="panel-title">    
                            <i class="fa fa-fw fa-users"></i> Apikey Limits
                        </h4>
                    </div>
                    <input type="hidden" id="requestUrl" value="{!!url(Request::segment(1))!!}">
                    <div class="panel-body" style="background-color: #d9ecf5">
                        <div id="limitsApikeyGrid"></div>
                    </div>
                </div>
            </div>            
        </section>        
    </section>
@endsection
<script src="assets/kendoui/js/jquery.min.js" type="text/javascript"></script>
@section('footer_scripts')
<script src="assets/kendoui/js/kendo.all.min.js" type="text/javascript"></script>
<script type="text/javascript" src="{{asset('assets/js/custom_js/limitsApikey.js')}}"></script>
@stop
