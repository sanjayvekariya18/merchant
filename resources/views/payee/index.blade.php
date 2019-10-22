@extends('layouts/default')

{{-- Page title --}}
@section('title')
    Payee
    @parent
@stop

{{-- page level styles --}}
@section('header_styles')
    <style type="text/css">
        table thead tr{
            background-color: #d9ecf5;
            color: #003f59
        }
        table tr th,table tr td{
            width: 33%;
        }     
        
    </style>
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
    <h1>Payee</h1>
    <ol class="breadcrumb">
        <li>
            <a href="index ">
                <i class="fa fa-fw fa-home"></i> Dashboard
            </a>
        </li>
        <li class="active">
            Payee
        </li>
    </ol>
</section>
<section class="content">
    <section class="content p-l-r-15">
        <div class="row">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <i class="fa fa-fw fa-users"></i> Payee List
                    </h4>
                </div>
                <input type="hidden" id="requestUrl" value="{!!url(Request::segment(1))!!}">
                {{ csrf_field() }}
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-12">
                            <form method='POST' action='{!!url("payee")!!}' id="payeeForm">
                                {{ csrf_field() }}
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="table-responsive">
                                            <table class = "table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>Payee Code</th>
                                                        <th>Payee Name</th>
                                                        <th></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>
                                                            <input id="payee_code" name="payee_code" class="form-control k-textbox" required validationMessage="Payee Code Required"/>
                                                        </td>
                                                        <td>
                                                            <input id="payee_name" name="payee_name" class="form-control k-textbox" required validationMessage="Payee Name Required"/>
                                                        </td>
                                                        <td>
                                                            <button type="submit" id="payeeSubmit" class="send-btn k-button">Create Payee</button>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                            <table class = "table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>Search</th>
                                                        <th></th>
                                                        <th></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>
                                                            <input class=k-textbox type=text id="payeeSearchGrid" placeholder="enter search text..." />
                                                        </td>
                                                        <td></td>
                                                        <td></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            <div id="payeeGrid"></div>
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
<script type="text/javascript" src="{{asset('assets/js/custom_js/PayeeIndex.js')}}"></script>
@stop
