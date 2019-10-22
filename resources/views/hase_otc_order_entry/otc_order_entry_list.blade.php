@extends('layouts/default')
{{-- Page title --}}
@section('title')
    OTC Entry List
    @parent
@stop
{{-- page level styles --}}
@section('header_styles')
@stop
<style>
    select {
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
        background-color: #e9e9e9;

    }
    .dropdownStatus {
        border-radius: 6px;
        color: #676767;
        background-color: #e9e9e9;
        background-image: none, linear-gradient(to bottom, rgba(0, 0, 0, 0.01)
            0px, rgba(0, 0, 0, 0.08) 100% );
        background-position: 50% 50%;
        border-color: #ccc;
        height: 2em;
        border: none;
        padding-right: 10px;
    }
    #tradergrid
    {
        clear: both;
    }
    #otc_entry_details_update_form label{
        font-weight: bold;
    }
    #otc_entry_details_update_form div.form-group{
        background-color: white;
        padding: 15px 10px;
    }
    .conformOrderName
    {
        width: 40%;
        font-weight: bold;
    }
</style> 
<!-- <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script> -->
<script src="assets/kendoui/js/jquery.min.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="assets/kendoui/styles/kendo.common.min.css">
<link rel="stylesheet" type="text/css" href="assets/kendoui/styles/kendo.blueopal.min.css">

@section('content')
<section class="content-header">
    <h1>OTC Entry List</h1>
      <ol class="breadcrumb">
          <li><a href="index "><i class="fa fa-fw fa-home"></i> Dashboard</a></li>
          <li><a href="#"> OTC Entry List</a></li>
          <li class="active">List</li>
      </ol>
</section>
<section class="content">
    <section class="content p-l-r-15">
        <div class="row">
            <div class="panel panel-primary ">
                <div class="panel-heading">
                    <h4 class="panel-title">    
                        <i class="fa fa-fw fa-users"></i> OTC Entry List
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
                        <form method = 'POST' enctype="multipart/form-data" action = '{!!url("otc_entry_details_update")!!}' id="otc_entry_details_update_form">
                            <input type="hidden" name="requestUrl" id="requestUrl" value="{!!url(Request::segment(1))!!}">
                            <input type = 'hidden' name = '_token' value = '{{Session::token()}}'>
                            <input type = 'hidden' name = 'order_id' id="order_id" >
                            <input type = 'hidden' name = 'login_merchant_id' id="login_merchant_id" value='{!!$merchantId!!}'> 


                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr style="background-color: #daecf4" class="filters">
                                            <th style="width: 25%">Exchange</th>
                                            <th style="width: 25%">Broker</th>
                                            <th style="width: 25%">Broker Account</th>
                                            <th style="width: 25%">Asset Pairs</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><input id="exchangeList" name="exchangeList" /></td>
                                            <td><select id="brokerDropDownList" name="brokerName" style="width: 100%;" ></select></td>
                                            <td><input id="brokerAccountListAutoComple" name="brokerAccountId" /></td>
                                            <td><select id="assetListFrom" name="assetListFrom[]"></select></td>
                                        </tr>
                                    </tbody>
                                </table>
                                <table class="table table-bordered">
                                    <thead>
                                        <tr style="background-color: #daecf4" class="filters">
                                            <th style="width: 25%">Customer</th>
                                            <th style="width: 25%">Account</th>
                                            <th style="width: 25%">Settlement</th>
                                            <th style="width: 25%">Basket</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><input id="customerAutoComplete" name="customerName" /></td>
                                            <td><input id="customerAccountListAutoComple" name="customerAccountId" /></td>
                                            <td><input id="settlementListAutoComple" name="settlementAssetId"  /></td>
                                            <td>
                                                <select id="orderBasketConfirm" name="orderBasketConfirm" style="width: 100%;" ></select>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <br>
                            <div id="tradergrid"></div>
                            <div id="customergrid"></div>
                            <br>
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <tbody>
                                        <tr>
                                            <td style="width: 25%"></td>
                                            <td style="width: 25%">
                                                <button type="button" id="orderSave" name="submitBtn" class="send-btn k-button">Review</button>
                                            </td>
                                            <td style="width: 25%">
                                                <button type="button" id="orderRecalculate" name="orderRecalculate" class="send-btn k-button">Recalculate</button>
                                            </td>
                                            <td style="width: 25%"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.create modal-dialog -->
        <div class="modal fade" data-backdrop="static" data-keyboard="false" id="orderConform" tabindex="-1" role="dialog" aria-labelledby="Heading" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="preloader">
                       <div class="loader_img">
                           <img src="{{asset('assets/img/loader.gif')}}" alt="loading..." height="64" width="64">
                       </div>
                   </div>
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <h4 class="modal-title custom_align" id="Heading">Confirm Order</h4>
                    </div>
                    <div class="modal-body">
                        <form method = 'POST' class="form-horizontal" enctype="multipart/form-data">
                            <div class="panel panel-primary">
                                <div class="panel-heading">
                                    <h3 class="panel-title">
                                        <i class="livicon" data-name="user-add" data-size="18" data-c="#fff" data-hc="#fff" data-loop="true"></i> Order Details
                                    </h3>
                                </div>
                                <div class="panel-body">
                                    <table class="table table-bordered">
                                        <tbody>
                                            <tr>
                                                <td class="conformOrderName"></td>
                                                <td><div id="sideConformDetails"></div></td>
                                            </tr>
                                            <tr>
                                                <td class="conformOrderName">Coin</td>
                                                <td><div id="coinConformDetails"></div></td>
                                            </tr>
                                            <tr>
                                                <td class="conformOrderName">Quantity</td>
                                                <td><div id="quantityConformDetails"></div></td>
                                            </tr>
                                            <tr>
                                                <td class="conformOrderName">Gross Price</td>
                                                <td><div id="grossPriceConformDetails"></div></td>
                                            </tr>
                                            <tr>
                                                <td class="conformOrderName">Trading Fee</td>
                                                <td><div id="TradingFeeConformDetails"></div></td>
                                            </tr>
                                            <tr>
                                                <td class="conformOrderName">Total Consideration</td>
                                                <td><div id="totalConsiderationConformDetails"></div></td>
                                            </tr>
                                            <tr>
                                                <td class="conformOrderName">Market Offset</td>
                                                <td id="marketOffsetTd"><div id="marketOffsetConformDetails"></div></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <button type="button" class="btn btn-success" id="saveOrder" >
                                <span class="glyphicon glyphicon-ok-sign"></span> Accept
                            </button>
                            <button type="button" class="btn btn-danger" data-dismiss="modal">
                                <span class="glyphicon glyphicon-remove"></span> cancel
                            </button>
                        </form>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
        </div>
        <!-- /.create modal-dialog -->

        <!-- /.create modal-dialog -->
        <div class="modal fade" data-backdrop="static" data-keyboard="false" id="orderRecalculateModel" tabindex="-1" role="dialog" aria-labelledby="Heading" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="preloader">
                       <div class="loader_img">
                           <img src="{{asset('assets/img/loader.gif')}}" alt="loading..." height="64" width="64">
                       </div>
                   </div>
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <h4 class="modal-title custom_align" id="Heading">Recalculate Order</h4>
                    </div>
                    <div class="modal-body">
                        <form method = 'POST' class="form-horizontal" enctype="multipart/form-data">
                            <div class="panel panel-primary">
                                <div class="panel-heading">
                                    <h3 class="panel-title">
                                        <i class="livicon" data-name="user-add" data-size="18" data-c="#fff" data-hc="#fff" data-loop="true"></i> Percentage Calculations
                                    </h3>
                                </div>
                                <div class="panel-body">
                                    <table class="table table-bordered" id="orderRecalculateTable">
                                        <thead>
                                            <tr style="background-color: #daecf4" class="filters">
                                                <th style="width: 5%"></th>
                                                <th style="width: 25%">Percentage</th>
                                                <th style="width: 35%"><div id="fromIntoAssetTitle" ></div></th>
                                                <th style="width: 35%"><div id="intoFromAssetTitle" ></div></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td><input type="radio" name="selectedper" id="brok_1" checked="checked" value="1"></td>
                                                <td><input id="per_1" name="per_1" class="k-textbox" type="text" title="Percentage" style="width: 100%" value="00.00" disabled /></td>
                                                <td><input id="from_1" name="from_1" type="number" title="Asset From" style="width: 100%" /></td>
                                                <td><input id="into_1" name="into_1" type="number" title="Asset Into" style="width: 100%" /></td>
                                            </tr>
                                            <tr>
                                                <td><input type="radio" name="selectedper" id="brok_2" value="2"></td>
                                                <td><input id="per_2" name="per_2" type="number" title="Percentage" style="width: 100%" /></td>
                                                <td><input id="from_2" name="from_2" class="k-textbox" type="text" title="Asset From" style="width: 100%" disabled /></td>
                                                <td><input id="into_2" name="into_2" class="k-textbox" type="text" title="Asset Into" style="width: 100%" disabled /></td>
                                            </tr>
                                            <tr>
                                                <td><input type="radio" name="selectedper" id="brok_3" value="3"></td>
                                                <td><input id="per_3" name="per_3" type="number" title="Percentage" style="width: 100%" /></td>
                                                <td><input id="from_3" name="from_3" class="k-textbox" type="text" title="Asset From" style="width: 100%" disabled /></td>
                                                <td><input id="into_3" name="into_3" class="k-textbox" type="text" title="Asset Into" style="width: 100%" disabled /></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <button type="button" class="btn btn-success" id="acceptRecalculate" >
                                <span class="glyphicon glyphicon-ok-sign"></span> Accept
                            </button>
                            <button type="button" class="btn btn-danger" data-dismiss="modal">
                                <span class="glyphicon glyphicon-remove"></span> cancel
                            </button>
                        </form>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
        </div>
        <!-- /.create modal-dialog -->
    </section>
</section>

<script type="text/x-kendo-template" id="tradingfeeTemplate">
   <div id="tradingfeeTemplates"></div> 
</script>
<script type="text/x-kendo-template" id="amountTemplate">
   <div id="amountTemplates"></div> 
</script>
<script type="text/x-kendo-template" id="accountTemplate">
   <div id="accountTemplates"></div> 
</script>
<script type="text/x-kendo-template" id="sideTemplate"> 
   <div id="sideTemplates"></div> 
</script>
<script type="text/x-kendo-template" id="toatalTemplate">
   <input id="totalTemplates" name="totalTemplates" type="number" title="price"  style="width: 100%" />
</script>
<script type="text/x-kendo-template" id="quantityTemplate">
   <input id="quantity" name="quantity" type="number" title="quantity" style="width: 100%" />
</script>
<script type="text/x-kendo-template" id="priceTemplate">
   <input id="price" name="price" type="number" title="price" style="width: 100%" />
</script>
<script type="text/x-kendo-template" id="assetTemplate">
   <div id="assetTemplates"></div> 
</script>
<script type="text/x-kendo-template" id="traderTradingfeeTemplate">
   <div id="traderTradingfeeTemplates"></div> 
</script>
<script type="text/x-kendo-template" id="AmountTraderTemplate">
   <div id="AmountTraderTemplates"></div> 
</script>
<script type="text/x-kendo-template" id="accountTraderTemplate">
   <div id="accountTraderTemplates"></div> 
</script>
<script type="text/x-kendo-template" id="sideTraderTemplate"> 
   <div id="sideTraderTemplates"></div> 
</script>
<script type="text/x-kendo-template" id="toatalTraderTemplate">
   <input id="totalTraderTemplates" name="totalTraderTemplates" type="number" title="priceTrader" style="width: 100%" />
</script>
<script type="text/x-kendo-template" id="quantityTraderTemplate">
   <input id="quantityTrader" name="quantityTrader" type="number" title="quantity" style="width: 100%" />
</script>
<script type="text/x-kendo-template" id="priceTraderTemplate">
    <input id="priceTrader" name="priceTrader" type="number" title="priceTrader" style="width: 100%" />
</script>
<script type="text/x-kendo-template" id="assetTraderTemplate">
   <div id="assetTraderTemplates"></div> 
</script>
<br>
Search <!-- By Customer/Customer account/merchant/merchant account -->:
<input class=k-textbox type=text id="searchValueGrid" placeholder="enter search text..." />
<br>
<div id="tabstrip">
   <ul>
      <li class="k-state-active">Pending</li>
      <li>Archive</li>
   </ul>
   <div>
      <div id="grid"></div>
   </div>
   <div>
      <div id="gridBroker"></div>
   </div>
</div>
<script type="text/x-kendo-template" id="actiontemplate">
    <?php if($merchantId == 0) { ?>
       <select id="fiatValue" class="dropdownStatus" name="selectedAction" onchange="tradeOrderStatusUpdate(#=order_id#,this.options[selectedIndex].text,this.options[selectedIndex].value,'status_fiat')">
                      <option value="#= status_fiat_type_id #">#= status_fiat_type_name #</option>
                      # for (var i = 0; i < status_list.length; i++) { #
                          <option value="#= status_list[i].id #">#= status_list[i].name #</option>
                      # } #
       </select> 
    <?php } else { ?>
        <lable id="fiatValue" class="dropdownStatus" name="selectedAction">#= status_fiat_type_name #</lable>
   <?php } ?>
</script>
<script type="text/x-kendo-template" id="operationtemplate">
  <?php if($merchantId == 0) { ?>
   <select id="operationValue" class="dropdownStatus" name="selectedAction" onchange="tradeOrderStatusUpdate(#=order_id#,this.options[selectedIndex].text,this.options[selectedIndex].value,'status_operation')">
                  <option value="#= status_operations_type_id #">#= status_operations_type_name #</option>
                  # for (var i = 0; i < operation_status_List.length; i++) { #
                      <option value="#= operation_status_List[i].id #">#= operation_status_List[i].name #</option>
                  # } #
   </select> 
   <?php } else { ?>
        <lable id="operationValue" class="dropdownStatus" name="selectedAction">#= status_operations_type_name #</lable>
   <?php } ?>
</script>
<script type="text/x-kendo-template" id="cryptotemplate">
   <?php if($merchantId == 0) { ?>
   <select id="cryptoStatus" class="dropdownStatus" name="selectedAction" onchange="tradeOrderStatusUpdate(#=order_id#,this.options[selectedIndex].text,this.options[selectedIndex].value,'status_crypto')">
                  <option value="#= status_crypto_type_id #">#= status_crypto_type_name #</option>
                  # for (var i = 0; i < status_crypto_type.length; i++) { #
                      <option value="#= status_crypto_type[i].id #">#= status_crypto_type[i].name #</option>
                  # } #
   </select> 
   <?php } else { ?>
        <lable id="cryptoStatus" class="dropdownStatus" name="selectedAction">#= status_crypto_type_name #</lable>
   <?php } ?>
</script>
<script type="text/x-kendo-template" id="updateTemplate">
  <button type="button" class='k-button' onclick="tradeOrderEdit();">Update</button>
</script>
<script type="text/javascript">
   $("#price").keyup(function(){
       var price =$('#price').val();
       var fxRates=$('#quantity').val();
       if(price != '' && fxRates !=''){
         total=price*fxRates;
          $('#totalTemplates').text(total);
       }
   });
   $("#quantity").keyup(function(){ 
       var fxRates=$('#quantity').val();
       var price =$('#price').val();
       if(price != '' && fxRates !=''){
         total=price*fxRates;
          $('#totalTemplates').text(total);
       }
   });
   $("#priceTrader").keyup(function(){
       var price =$('#priceTrader').val();
       var fxRates=$('#quantityTrader').val();
       if(price != '' && fxRates !=''){
         total=price*fxRates;
          $('#totalTraderTemplates').text(total);
       }
   });
   $("#quantityTrader").keyup(function(){   
       var fxRates=$('#quantityTrader').val();
       var price =$('#priceTrader').val();
       if(price != '' && fxRates !=''){
         total=price*fxRates;
          $('#totalTraderTemplates').text(total);
       }
   });
   $(document).ready(function () {
        setInterval(function () {
            $("#grid").data("kendoGrid").dataSource.read();
            $("#gridBroker").data("kendoGrid").dataSource.read();
        }, 60000);
    });
</script>
@endsection
@section('footer_scripts')
<script src="assets/kendoui/js/kendo.all.min.js" type="text/javascript"></script>
<script type="text/javascript" src="{{asset('assets/js/custom_js/HaseOtcOrderEntryList.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/js/custom_js/HaseTradeOrderEntryList.js')}}"></script>
<script type="text/javascript">
   tradeOrderEntryList();
   tradeBrokerOrderEntryList();
   customerList();
   traderList();
   
</script>
@stop