@extends('layouts/default')

{{-- Page title --}}
@section('title')
    Transactions Code
    @parent
@stop

{{-- page level styles --}}
@section('header_styles')
    <!--page level css -->
    <link rel="stylesheet" href="https://kendo.cdn.telerik.com/2018.1.221/styles/kendo.common.min.css">
    <link rel="stylesheet" href="https://kendo.cdn.telerik.com/2018.1.221/styles/kendo.rtl.min.css">
    <link rel="stylesheet" href="https://kendo.cdn.telerik.com/2018.1.221/styles/kendo.default.min.css">
    <link rel="stylesheet" href="https://kendo.cdn.telerik.com/2018.1.221/styles/kendo.mobile.all.min.css">
    <link rel="stylesheet" href="http://kendo.cdn.telerik.com/2018.1.221/styles/kendo.blueopal.min.css" />
    <!--end of page level css-->
@stop
@section('content')
<section class="content-header">
    <h1>Transactions Code</h1>
    <ol class="breadcrumb">
        <li>
            <a href="index ">
                <i class="fa fa-fw fa-home"></i> Dashboard
            </a>
        </li>
        <li class="active">
            Transactions Code
        </li>
    </ol>
</section>
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <?php if(in_array("add", $permissions)): ?>
            <a href='{!!url("transactions_code")!!}/create' class='btn btn-primary btn-inline'>Create New Transactions Code
            </a>
            <?php endif; ?>
        </div>
    </div>
    <br>
    <section class="content p-l-r-15">
        <div class="row">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <i class="fa fa-fw fa-users"></i> Transactions Code List
                    </h4>
                </div>
                <input type="hidden" id="requestUrl" value="{!!url(Request::segment(1))!!}">
                {{ csrf_field() }}
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-12">
                            <form method='POST' action='{!!url("transactions_code")!!}' id="transactionsCodeForm">
                                {{ csrf_field() }}
                                Search :
                                <input class=k-textbox type=text id="transactionsCodeSearchGrid" placeholder="enter search text..." />
                                <br><br>
                                <div id="transactionsCodeGrid"></div>
                            </form>
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
<script src="https://kendo.cdn.telerik.com/2018.1.221/js/kendo.all.min.js"></script>
<script type="text/javascript" src="{{asset('assets/js/custom_js/TransactionsCodeIndex.js')}}"></script>
@stop
