@extends('layouts/default')
{{-- Page title --}}
@section('title')
    Payment Summary
    @parent
@stop
{{-- page level styles --}}
@section('header_styles')
@stop
<style>
    table thead tr{
        background-color: #d9ecf5;
        color: #003f59
    }
    table tr th{
        width: 25%;
    }
</style>
<!-- <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script> -->
<script src="assets/kendoui/js/jquery.min.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="assets/kendoui/styles/kendo.common.min.css">
<link rel="stylesheet" type="text/css" href="assets/kendoui/styles/kendo.blueopal.min.css">

@section('content')
<section class="content-header">
    <h1>Payment Summary</h1>
      <ol class="breadcrumb">
          <li><a href="index "><i class="fa fa-fw fa-home"></i> Dashboard</a></li>
          <li><a href="#"> Payment Summary</a></li>
          <li class="active">List</li>
      </ol>
</section>
<section class="content">
    <section class="content p-l-r-15">
        <div class="row">
            <div class="panel panel-primary ">
                <div class="panel-heading">
                    <h4 class="panel-title">    
                        <i class="fa fa-fw fa-users"></i> Payment Summary
                    </h4>
                </div>
                <br>
                <div>
                    <div class="panel-body">
                        <div class="preloader" style="background: none !important; ">
                            <div class="loader_img">
                                <img src="{{asset('assets/img/loader.gif')}}" alt="loading..." height="64" width="64">
                            </div>
                        </div>
                        <form method = 'POST' enctype="multipart/form-data" action='{!!url("payment_summary")!!}' id="paymentSummaryForm">
                            <input type="hidden" name="requestUrl" id="requestUrl" value="{!!url(Request::segment(1))!!}">
                            <input type = 'hidden' name = '_token' value = '{{Session::token()}}'>
                            <input type = 'hidden' name = 'order_id' id="order_id" >
                            <input type = 'hidden' name = 'login_merchant_id' id="login_merchant_id" value='{!!$merchantId!!}'> 


                            <div class="table-responsive">
                                <table class = "table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Merchant</th>
                                            <th>Location City</th>
                                            <th>Location</th>
                                            <th>Merchant Account</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <input id="merchant_id" name="merchant_id" />
                                            </td>
                                            <td>
                                                <input id="location_city_id" name="location_city_id" disabled="true" />
                                            </td>
                                            <td>
                                                <input id="location_id" name="location_id" disabled="true" />
                                            </td>
                                            <td>
                                                <input id="merchant_account_id" name="merchant_account_id" disabled="true" />
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <table class = "table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Customer</th>
                                            <th>Customer Account</th>
                                            <th>Asset Name</th>
                                            <th>Payment Type</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <input id="customer_id" name="customer_id" disabled="true" />
                                            </td>
                                            <td>
                                                <input id="customer_account_id" name="customer_account_id" disabled="true"/>
                                            </td>  
                                            <td>
                                                <input id="asset_id" name="asset_id"/>
                                            </td>
                                            <td>
                                                <input id="payment_type_id" name="payment_type_id"/>
                                            </td> 
                                            
                                        </tr>
                                    </tbody>
                                </table>
                                <table class = "table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Payment Name</th>
                                            <th>Payment Price</th>
                                            <th>Payment Quantity</th>
                                            <th>Fee Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <input id="payment_name" name="payment_name" type="text" class="form-control k-textbox" required validationMessage="Payment Name Required" />
                                            </td> 
                                            <td>
                                                <input id="payment_price" name="payment_price" type="text" class="form-control k-textbox" required validationMessage="Payment Price Required"/>
                                            </td> 
                                            <td>
                                                <input id="payment_quantity" name="payment_quantity" type="text" class="form-control k-textbox" required validationMessage="Payment Quantity Required"/>
                                            </td>                                              
                                            <td>
                                                <input id="fee_amount" name="fee_amount" type="text" class="form-control k-textbox" required validationMessage="Fee Amount Required"/>
                                            </td> 
                                        </tr>
                                    </tbody>
                                </table> 
                                <table class = "table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Fee Asset Name</th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <input id="fee_asset_id" name="fee_asset_id"/>
                                            </td>  
                                            <td>
                                                <button type="button" id="submitBtn" class="send-btn k-button">Create Summary</button>
                                            </td>   
                                            <td>
                                                <a href='{!!url("payment_ledger")!!}/create' class="send-btn k-button">Create Payment Ledger
                                                </a>
                                            </td> 
                                            <td></td>              
                                        </tr>
                                    </tbody>
                                </table>                                
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="panel panel-primary ">
                <div class="panel-heading">
                    <h4 class="panel-title">    
                        <i class="fa fa-fw fa-users"></i> Payment Summary
                    </h4>
                </div>
                <input type="hidden" id="requestUrl" value="{!!url(Request::segment(1))!!}">
                <div class="panel-body" style="background-color: #d9ecf5">
                    <div class="row">
                        <div class="col-md-12">
                            {{ csrf_field() }}
                            <div id="paymentSummaryActiveGrid"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>    
</section>
<script type="text/x-kendo-template" id="templateDetail">
    <div class="paymentLedgerGrid"></div>
</script>
@endsection
@section('footer_scripts')
<script src="assets/kendoui/js/kendo.all.min.js" type="text/javascript"></script>
<script type="text/javascript" src="{{asset('assets/js/custom_js/PaymentSummary.js')}}"></script>

@stop