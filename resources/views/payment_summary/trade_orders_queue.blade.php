@extends('layouts/default')
{{-- Page title --}}
@section('title')
Trade Order Queue list
@parent
@stop
{{-- page level styles --}}
@section('header_styles')
@stop
<script src="assets/kendoui/js/jquery.min.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="assets/kendoui/styles/kendo.common.min.css">
<link rel="stylesheet" type="text/css" href="assets/kendoui/styles/kendo.blueopal.min.css">
@section('content')
<style>
   .mainBoxDiv
   {
   min-width: 10%;
   display: inline-block;
   float: left;
   background-color: white;
   margin-right: 10px;
   margin-top: 5px;
   height: 16  0px;
   margin-bottom: 7px;
   }
   .checkBoxDiv
   {
       display: inline-block;
       float: left;
       background-color: white;
       margin-right: 10px;
       margin-top: 5px;
       height: 16  0px;
       margin-bottom: 7px;
   }
   .headerContentDiv
   {
   font-size: 14px;
   text-align: center;
   margin-top: 5%;
   }
   .labelContentDiv
   {
   font-weight: bold;
   }
   .mainContentDiv
   {
   padding: 6%;
   }
</style>
<body>
    
   <!-- onkeydown="if_tab(event.which)" -->
   <span id="countdown-1" style="font-family: Verdana; font-size: 28px; font-weight: bold">60 </span><br /><span> <b> seconds </b></span>
</body>
<section class="content-header">
   <h1>Trade order Queue List</h1>
   <ol class="breadcrumb">
      <li><a href="index "><i class="fa fa-fw fa-home"></i> Dashboard</a></li>
      <li><a href="#"> Trade order queue List</a></li>
      <li class="active">Queue</li>
   </ol>
</section>
<section class="content">
   <button id="mobileViewRedirect" class="float-left submit-button" >Mobile View</button>
   <form method = 'POST' enctype="multipart/form-data" class="form-horizontal bv-form" action = '{!!url("update-trade-order-details")!!}' id="hase_image_upload_form">
      <input type="hidden" name="requestUrl" id="requestUrl" value="{!!url(Request::segment(1))!!}">
      <input type = 'hidden' name = '_token' value = '{{Session::token()}}'>
      <div class="row">
         <div class="col-md-12">
            <?php if(!isset($retry))
               { ?>
            <button type="submit" name="submitBtn" value="SaveClose" class = 'btn btn-primary btn-inline'>Save</button>
            <?php } else { ?>
            <?php  }?>
         </div>
      </div>
      <br/>
      <div class="row">
      <div class="col-md-12">
      <div class="panel panel-primary">
         <div class="panel-heading">
            <h3 class="panel-title">
               <i class="livicon" data-name="user-add" data-size="18" data-c="#fff" data-hc="#fff"
                  data-loop="true"></i> Trade Order Queue
            </h3>
            <span class="pull-right clickable"><i class="glyphicon glyphicon-chevron-up"></i>
            </span>
         </div>
         <div class="panel-body">
            <?php if(!isset($retry)){ 
               foreach ($tradeOrdersQueueListData as $tradeOrdersQueueListDataValue){?>
            <input type="hidden" id="assetQueueOrderIdentity" value="{!!$tradeOrdersQueueListDataValue->order_id!!}" />
            <div class="mainBoxDiv">
               <div class="headerContentDiv">
                   <div id="transaction_internal_div">
                       <?php echo $tradeOrdersQueueListDataValue->transaction_internal_ref; ?>
                    : </div>
               </div>
             </div>
            <div class="mainBoxDiv">
               <div class="headerContentDiv">
                  <input name="transaction_internal" id="transaction_internal" value="" class="form-control"/>   
               </div>
            </div>
            <!-- <div class="checkBoxDiv">
                <input type="checkbox" name="transaction_internal" id="transaction_internal"/>
            </div> -->
            <div class="mainBoxDiv">
               <div class="headerContentDiv">
                  <button type="button" id="acceptButton" name="acceptButton" value="acceptButton" class = 'btn btn-primary btn-inline'> Accept </button>   
               </div>
            </div>
            <div class="mainBoxDiv">
               <div class="headerContentDiv">
                  <button type="button" id="rejectButton" name="rejectButton" value="rejectButton" class = 'btn btn-primary btn-inline'> Reject </button>   
               </div>
            </div>
            <div class="mainBoxDiv">
               <div id="grid"></div>
            </div>
            <?php 
               if($tradeOrdersQueueListDataValue->asset_price_upper > 0)
               {
                 if ($tradeOrdersQueueListDataValue->asset_into_price <= $tradeOrdersQueueListDataValue->asset_price_upper){
                    $boundsUpperColor="lightgreen";
                 }else{
                    $boundsUpperColor="Red";
                 }
               } else {
                 $boundsUpperColor="Red";
               }
               
               if($tradeOrdersQueueListDataValue->asset_price_lower > 0)
               {
                 if ($tradeOrdersQueueListDataValue->asset_into_price <= $tradeOrdersQueueListDataValue->asset_price_lower){
                    $boundsLowerColor="lightgreen";
                 }else{
                    $boundsLowerColor="Red";
                 }
               } else {
                 $boundsLowerColor="Red";
               } 
               
               
               if($tradeOrdersQueueListDataValue->asset_quantity_upper > 0)
               {
                 if ($tradeOrdersQueueListDataValue->asset_from_quantity >= $tradeOrdersQueueListDataValue->asset_quantity_upper ){
                    $quantityUpperColor="lightgreen";
                 }else{
                    $quantityUpperColor="Red";
                 }
               } else {
                 $quantityUpperColor="Red";
               }
               
               if($tradeOrdersQueueListDataValue->asset_quantity_lower > 0)
               {
                 if ($tradeOrdersQueueListDataValue->asset_from_quantity <= $tradeOrdersQueueListDataValue->asset_quantity_lower){
                    $quantityLowerColor="lightgreen";
                 }else{
                    $quantityLowerColor="Red";
                 }
               } else {
                 $quantityLowerColor="Red";
               } 
               
               $totalUpper=($tradeOrdersQueueListDataValue->asset_from_quantity)*($tradeOrdersQueueListDataValue->asset_from_price);
               if($tradeOrdersQueueListDataValue->asset_total_upper > 0 && $totalUpper > 0)
               {                               
                 
                 if ($tradeOrdersQueueListDataValue->asset_total_upper <= $totalUpper){
                    $totalUpperColor="lightgreen";
                 }else{
                    $totalUpperColor="Red";
                 }
               } else {
                 $totalUpperColor="Red";
               }
               
               $totalLower=($tradeOrdersQueueListDataValue->asset_into_quantity)*($tradeOrdersQueueListDataValue->asset_into_price);
               if($tradeOrdersQueueListDataValue->asset_total_lower > 0 && $totalLower > 0)
               {
                 if ($tradeOrdersQueueListDataValue->asset_total_lower <= $totalLower){
                    $totalLowerColor="lightgreen";
                 }else{
                    $totalLowerColor="Red";
                 }
               } else {
                 $totalLowerColor="Red";
               }
               ?>                                                       
         </div>
      </div>
   </form>
</section>
</section>
<script src="assets/kendoui/js/kendo.all.min.js" type="text/javascript"></script>
<script type="text/javascript" src="{{asset('assets/js/custom_js/HaseTradeOrdersTimer.js')}}"></script>
<script type="text/javascript">
    $(document).ready(function(){
        var orderId = jQuery("#assetQueueOrderIdentity").val();
        var token = $('input[name="_token"]').val();
        if(orderId)
        {
          $.ajax({
             type:'POST',
             data:{_token:token,order_id:orderId,status_id:8},
             url : "update_queue_status",
             error:function(xhr,status,error) {
                 console.log(error);
             },
             success:function(assetTypeListResponse,status,xhr) {
                localStorage.setItem('assetQueueOrderIdentity', orderId);
                console.log(localStorage.getItem('assetQueueOrderIdentity'));
             }

          });
        }
    });
    $('#acceptButton').attr("disabled", true);

    $("input[name='transaction_internal']").change(function (e) {
        var transaction_internal_value=$(this).val();
        var transaction_internal="{{$tradeOrdersQueueListDataValue->transaction_internal_ref}}";
        if(transaction_internal_value == transaction_internal) {
           $("#transaction_internal_div").attr("disabled", true).css("background-color","lightgreen");
           $('#acceptButton').attr("disabled", false);
            
        } else{
           $("#transaction_internal_div").attr("disabled", false).css("background-color","Red");
           $('#acceptButton').attr("disabled", true);
           
        }
    });
    $("#acceptButton").click(function() {
        var orderId = jQuery("#assetQueueOrderIdentity").val();
        var token = $('input[name="_token"]').val();
        $.ajax({
           type:'POST',
           data:{_token:token,order_id:orderId,status_id:12},
           url : "update_queue_status",
           error:function(xhr,status,error) {
               console.log(error);
           },
           success:function(assetTypeListResponse,status,xhr) {
               location.reload();
           }

        });
    });

    $("#rejectButton").click(function() {
        var orderId = jQuery("#assetQueueOrderIdentity").val();
        var token = $('input[name="_token"]').val();
        $.ajax({
           type:'POST',
           data:{_token:token,order_id:orderId,status_id:13},
           url : "update_queue_status",
           error:function(xhr,status,error) {
               console.log(error);
           },
           success:function(assetTypeListResponse,status,xhr) {
               //$("#grid").data("kendoGrid").dataSource.read();
               location.reload();
           }

        });
    });


   var totalUpperValue = parseFloat('<?php echo $tradeOrdersQueueListDataValue->asset_total_upper; ?>');
   var totalLowerValue = parseFloat('<?php echo $tradeOrdersQueueListDataValue->asset_total_lower; ?>');
   var assetPriceUpper = parseFloat('<?php echo $tradeOrdersQueueListDataValue->asset_price_upper; ?>');
   var assetPriceLower = parseFloat('<?php echo $tradeOrdersQueueListDataValue->asset_price_lower; ?>');
   var assetQuantityUpper = parseFloat('<?php echo $tradeOrdersQueueListDataValue->asset_quantity_upper; ?>');
   var assetQuantityLower = parseFloat('<?php echo $tradeOrdersQueueListDataValue->asset_quantity_lower; ?>');
   var baseUrl="{{$baseUrl}}";
   localStorage.setItem("baseUrl",baseUrl);
   tradeOrdersTimerView();
   criteriaList();
   function criteriaList() {
       var criteriaListGridElement = $("#grid")
           .kendoGrid({
               dataSource: new kendo.data.DataSource({
                   data: [{
                       criteria: "Criteria",
                       boundsUpper:"{{$tradeOrdersQueueListDataValue->asset_price_upper}}",
                       boundsLower:"{{$tradeOrdersQueueListDataValue->asset_price_lower}}",
                       quantityUpper:"{{$tradeOrdersQueueListDataValue->asset_quantity_upper}}",
                       quantityLower:"{{$tradeOrdersQueueListDataValue->asset_quantity_lower}}",
                       totalUpper:"{{$tradeOrdersQueueListDataValue->asset_total_upper}}",
                       totalLower:"{{$tradeOrdersQueueListDataValue->asset_total_lower}}",
                       staffName:"{{$tradeOrdersQueueListDataValue->staff_group_name}}",
                       boundsUpperColor:"{{$boundsUpperColor}}",
                       boundsLowerColor:"{{$boundsLowerColor}}",
                       quantityUpperColor:"{{$quantityUpperColor}}",
                       quantityLowerColor:"{{$quantityLowerColor}}",
                       totalUpperColor:"{{$totalUpperColor}}",
                       totalLowerColor:"{{$totalLowerColor}}",
   
                   },{
                       criteria: "Asset From",
                       boundsUpper:"{{$tradeOrdersQueueListDataValue->asset_from_price}}",
                       boundsLower:"{{$tradeOrdersQueueListDataValue->asset_from_price}}",
                       quantityUpper:"{{$tradeOrdersQueueListDataValue->asset_from_quantity}}",
                       quantityLower:"{{$tradeOrdersQueueListDataValue->asset_from_quantity}}",
                       totalUpper:"{{$totalUpper}}",
                       totalLower:"{{$totalUpper}}",
                       staffName:"{{$tradeOrdersQueueListDataValue->staff_group_name}}",
                       boundsUpperColor:"{{$boundsUpperColor}}",
                       boundsLowerColor:"{{$boundsLowerColor}}",
                       quantityUpperColor:"{{$quantityUpperColor}}",
                       quantityLowerColor:"{{$quantityLowerColor}}",
                       totalUpperColor:"{{$totalUpperColor}}",
                       totalLowerColor:"{{$totalLowerColor}}",
   
                   },{
                       criteria: "Asset Into",
                       boundsUpper:"{{$tradeOrdersQueueListDataValue->asset_into_price}}",
                       boundsLower:"{{$tradeOrdersQueueListDataValue->asset_into_price}}",
                       quantityUpper:"{{$tradeOrdersQueueListDataValue->asset_into_quantity}}",
                       quantityLower:"{{$tradeOrdersQueueListDataValue->asset_into_quantity}}",
                       totalUpper:"{{$totalLower}}",
                       totalLower:"{{$totalLower}}",
                       staffName:"{{$tradeOrdersQueueListDataValue->staff_group_name}}",
                       boundsUpperColor:"{{$boundsUpperColor}}",
                       boundsLowerColor:"{{$boundsLowerColor}}",
                       quantityUpperColor:"{{$quantityUpperColor}}",
                       quantityLowerColor:"{{$quantityLowerColor}}",
                       totalUpperColor:"{{$totalUpperColor}}",
                       totalLowerColor:"{{$totalLowerColor}}",
   
                   }]
               }),
               dataBound: function(e) {
                   var data = this.dataSource.data();
                   $.each(data, function (i, row) {
                       if(assetPriceLower > 0 && assetPriceLower > 0 && parseFloat($('tr[data-uid="' + row.uid + '"] td:nth-child(2)').html()) > assetPriceLower && parseFloat($('tr[data-uid="' + row.uid + '"] td:nth-child(3)').html()) < assetPriceUpper)
                       {
                         $('tr[data-uid="' + row.uid + '"] td:nth-child(2)').css("background-color", "lightgreen");
                         $('tr[data-uid="' + row.uid + '"] td:nth-child(3)').css("background-color", "lightgreen");
                       } else if(assetPriceLower > 0 && parseFloat($('tr[data-uid="' + row.uid + '"] td:nth-child(2)').html()) > assetPriceLower && assetPriceUpper == 0)
                       {
                           $('tr[data-uid="' + row.uid + '"] td:nth-child(2)').css("background-color", "lightgreen");
                           $('tr[data-uid="' + row.uid + '"] td:nth-child(3)').css("background-color", "lightgreen");
                       } else if(assetPriceUpper > 0 && parseFloat($('tr[data-uid="' + row.uid + '"] td:nth-child(3)').html()) < assetPriceUpper && assetPriceLower == 0)
                       {
                           $('tr[data-uid="' + row.uid + '"] td:nth-child(2)').css("background-color", "lightgreen");
                           $('tr[data-uid="' + row.uid + '"] td:nth-child(3)').css("background-color", "lightgreen");
                       } else {
                           $('tr[data-uid="' + row.uid + '"] td:nth-child(2)').css("background-color", "red");
                           $('tr[data-uid="' + row.uid + '"] td:nth-child(3)').css("background-color", "red");
                       }
   
   
                       if(assetQuantityLower > 0 && assetQuantityUpper > 0 && parseFloat($('tr[data-uid="' + row.uid + '"] td:nth-child(4)').html()) > assetQuantityLower && parseFloat($('tr[data-uid="' + row.uid + '"] td:nth-child(5)').html()) < assetQuantityUpper)
                       {
                           $('tr[data-uid="' + row.uid + '"] td:nth-child(4)').css("background-color", "lightgreen");
                           $('tr[data-uid="' + row.uid + '"] td:nth-child(5)').css("background-color", "lightgreen");
   
   
                       } else if(assetQuantityLower > 0 && parseFloat($('tr[data-uid="' + row.uid + '"] td:nth-child(4)').html()) > assetQuantityLower && assetQuantityUpper == 0)
                       {
                           $('tr[data-uid="' + row.uid + '"] td:nth-child(4)').css("background-color", "lightgreen");
                           $('tr[data-uid="' + row.uid + '"] td:nth-child(5)').css("background-color", "lightgreen");
                       } else if(assetQuantityUpper > 0 && parseFloat($('tr[data-uid="' + row.uid + '"] td:nth-child(5)').html()) < assetQuantityUpper && assetQuantityLower == 0)
                       {
                           $('tr[data-uid="' + row.uid + '"] td:nth-child(4)').css("background-color", "lightgreen");
                           $('tr[data-uid="' + row.uid + '"] td:nth-child(5)').css("background-color", "lightgreen");
                       } else {
                           $('tr[data-uid="' + row.uid + '"] td:nth-child(4)').css("background-color", "red");
                           $('tr[data-uid="' + row.uid + '"] td:nth-child(5)').css("background-color", "red");
                       }
                       
   
                       if(parseFloat($('tr[data-uid="' + row.uid + '"] td:nth-child(6)').html()) > totalLowerValue && parseFloat($('tr[data-uid="' + row.uid + '"] td:nth-child(6)').html()) < totalUpperValue){
                         $('tr[data-uid="' + row.uid + '"] td:nth-child(6)').css("background-color", "lightgreen");
                       } else {
                         $('tr[data-uid="' + row.uid + '"] td:nth-child(6)').css("background-color", "red");
                       }
                       if(parseFloat($('tr[data-uid="' + row.uid + '"] td:nth-child(7)').html()) < totalUpperValue && parseFloat($('tr[data-uid="' + row.uid + '"] td:nth-child(7)').html()) > totalLowerValue){
                         $('tr[data-uid="' + row.uid + '"] td:nth-child(7)').css("background-color", "lightgreen");
                       } else {
                       $('tr[data-uid="' + row.uid + '"] td:nth-child(7)').css("background-color", 'red');
                       }
                   });
   
                 },
               columns: [{
                   field: "criteria",
                   title: "Asset ",
                   headerAttributes: {
                       "class": "k-grid-header k-header",
                       style: "text-align: left; font-size: 14px; height:1% padding: .5em .6em .4em .6em"
                   },
                   width: 100        
               }, {
                   field: "boundsLower",
                   title: "Lower Price",
                   headerAttributes: {
                       "class": "k-grid-header k-header",
                       style: "text-align: left; font-size: 14px; height:1% padding: .5em .6em .4em .6em"
                   },
                   width: 100
               }, {
                   field: "boundsUpper",
                   title: "Upper Price",
                   headerAttributes: {
                       "class": "k-grid-header k-header",
                       style: "text-align: left; font-size: 14px; height:1% padding: .5em .6em .4em .6em"
                   },
                   width: 100
               }, {
                   field: "quantityLower",
                   title: "Lower Quantity",
                   headerAttributes: {
                       "class": "k-grid-header k-header",
                       style: "text-align: left; font-size: 14px; height:1% padding: .5em .6em .4em .6em"
                   },
                   width: 100
               }, {
                   field: "quantityUpper",
                   title: "Upper Quantity",
                   headerAttributes: {
                       "class": "k-grid-header k-header",
                       style: "text-align: left; font-size: 14px; height:1% padding: .5em .6em .4em .6em"
                   },
                   width: 100
               },{
                   field: "totalLower",
                   title: "Lower Total",
                   headerAttributes: {
                       "class": "k-grid-header k-header",
                       style: "text-align: left; font-size: 14px; height:1% padding: .5em .6em .4em .6em"
                   },
                   width: 100
               },{
                   field: "totalUpper",
                   title: "Upper Total",
                   headerAttributes: {
                       "class": "k-grid-header k-header",
                       style: "text-align: left; font-size: 14px; height:1% padding: .5em .6em .4em .6em"
                   },
                   width: 100
               }],
           });
   };
</script>
<?php } 
   } else { ?>
<script type="text/javascript" src="{{asset('assets/js/custom_js/HaseTradeOrdersTimer.js')}}"></script>
<script type="text/javascript">
   var baseUrl="{{$baseUrl}}";
   localStorage.setItem("baseUrl",baseUrl);
   tradeOrdersTimerView();
</script>
Waiting for trade review...
<button type="button" id="retryButton" name="button" value="SaveClose" class = 'btn btn-primary btn-inline'> Retry </button> 
<script type="text/javascript">$( "#retryButton" ).click(function() {
   location.reload();
   });
</script><?php } ?> 
<script type="text/javascript">
  $("#mobileViewRedirect").click(function() {
      console.log("hello");
      location.href = "trade_orders_queue_mobile";
    });
</script>
@endsection
{{-- page level scripts --}}
@section('footer_scripts')
@stop