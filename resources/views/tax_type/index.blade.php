@extends('layouts/default')

{{-- Page title --}}
@section('title')
    Tax Types
    @parent
@stop

{{-- page level styles --}}
@section('header_styles')
    <style>
        table thead tr{
            background-color: #d9ecf5;
            color: #003f59
        }
    </style>
    <!--page level css -->
    <link rel="stylesheet" type="text/css" href="assets/kendoui/styles/kendo.common.min.css">
    <link rel="stylesheet" type="text/css" href="assets/kendoui/styles/kendo.mobile.all.min.css">
    <link rel="stylesheet" type="text/css" href="assets/kendoui/styles/kendo.blueopal.min.css">
    <!--end of page level css-->
@stop
@section('content')
<section class="content-header">
    <h1>Tax Types</h1>
    <ol class="breadcrumb">
        <li>
            <a href="index ">
                <i class="fa fa-fw fa-home"></i> Dashboard
            </a>
        </li>
        <li class="active">
            Tax Types
        </li>
    </ol>
</section>
<section class="content">
    <!-- <div class="row">
        <div class="col-md-12">
            <?php //if(in_array("add", $permissions)): ?>
            <a href='{!!url("tax_type")!!}/create' class='btn btn-primary btn-inline'>Create New Tax Type
            </a>
            <?php //endif; ?>
        </div>
    </div>
    <br> -->
    <section class="content p-l-r-15">
        <div class="row">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <i class="fa fa-fw fa-users"></i> Tax Types List
                    </h4>
                </div>
                <input type="hidden" id="requestUrl" value="{!!url(Request::segment(1))!!}">
                {{ csrf_field() }}
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-12">
                            <form method='POST' action='{!!url("tax_type")!!}' id="taxTypeForm">
                                {{ csrf_field() }}
                                <table class="table table-bordered" style='background:#fff;'>
                                    <thead>
                                        <tr>
                                            <th style="width: 32%">Description</th>
                                            <th style="width: 32%">Merchant/Payee</th>
                                            <th style="width: 36%">Category</th>
                                            <!--<th style="width: 25%">Total Amount</th>-->
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <input id="type_name" name="type_name" title="Description Required" type="text" class="k-textbox" required="required" />
                                            </td>
                                            <td>
                                                <input id="merchant_id" name="merchant_id" />
                                            </td>
                                            <td>
                                                <input id="category_id" name="category_id" />
                                                <button type="button" id="addCategory" class="send-btn k-button">New</button>
                                            </td>
                                            <!--<td>
                                                <input id="totalamount" name="totalamount" class="k-textbox taxAmountPercent" />
                                            </td>-->
                                        </tr>
                                    </tbody>
                                </table>
                                <table class="table table-bordered" style='background:#fff;'>
                                    <thead>
                                        <tr>
                                            <!-- <th style="width: 25%">Amount</th> -->
                                            <th style="width: 32%">Percent</th>
                                            <th style="width: 32%">Asset</th>
                                            <!--<th style="width: 25%">Settlement</th>-->
                                            <th style="width: 36%"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <!-- <td>
                                                <input id="amount" name="amount" class="k-textbox" />
                                            </td> -->
                                            <td>
                                                <input id="percentage" name="percentage" class="k-textbox taxAmountPercent" />
                                            </td>
                                            <td>
                                                <input id="asset_id" name="asset_id" />
                                            </td>
                                            <!--<td>
                                                <input id="settlement_id" name="settlement_id" />
                                            </td>-->
                                            <td>
                                                <button type="button" id="submitBtn" class="send-btn k-button">Add Tax</button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <br />
                                Search :
                                <input class=k-textbox type=text name="searchtext" id="taxTypeSearchGrid" placeholder="enter search text..." />
                                <br><br>
                                <div id="taxTypeGrid"></div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- category window -->
        <div id="top_modal" class="modal fade animated position_modal" role="dialog">
            <div class="modal-dialog" style="width: 76% !important">
                <div class="modal-content" style="background-color: #d9ecf5">
                    <div class="modal-header" style="background-color: #13688c;color: white">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Tax Type Category</h4>
                    </div>
                    <div class="panel panel-primary" style="bgooorder-color: snow;">
                        <div class="panel-body">
                            <form method='POST' action="{{url('tax_type/createTaxTypeCategory')}}" id="taxCategoryForm">
                                {{ csrf_field() }}
                                <table class="table table-bordered" style='background:#fff;'>
                                    <thead>
                                        <tr>
                                            <th style="width: 33%">Category Name</th>
                                            <th style="width: 33%">Merchant/Payee</th>
                                            <th style="width: 33%">Percentage</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <input type="text" class="k-textbox" id="category_name" name="category_name" placeholder="Category Name" style="width:100%" required validationMessage="Name Required" />
                                            </td>
                                            <td>
                                                <input id="category_merchant_id" name="category_merchant_id" style="width:100%" />
                                            </td>
                                            <td>
                                                <input type="text" class="k-textbox" id="category_percentage" name="category_percentage" placeholder="Percent" style="width: 100%" />
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <table class="table table-bordered" style='background:#fff;'>
                                    <tbody>
                                        <tr>
                                            <td style="width: 33%"></td>
                                            <td style="width: 33%;text-align: center;">
                                                <button type="submit" id="w_submitBtn" class="send-btn k-button">Add Tax Category</button>
                                            </td>
                                            <td style="width: 33%"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </form>
                            Search :
                            <input class="k-textbox" type="text" name="searchtext" id="taxCategorySearchGrid" placeholder="enter search text..." />
                            <br><br>
                            <div id="taxCategoryListGrid"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- category window end-->
    </section>
</section>
@endsection
<script src="{{asset('assets/kendoui/js/jquery.min.js')}}" type="text/javascript"></script>

@section('footer_scripts')
<script type="text/javascript" src="{{asset('assets/kendoui/js/kendo.all.min.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/js/custom_js/TaxTypeIndex.js')}}"></script>
@stop
