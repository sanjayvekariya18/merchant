$(document).ready(function (){
    var currentlySelectedCustomer = [];
    var assetTypeListResponse = [];
    var merchantId = $("#brokerId").val();
    var token = $('input[name="_token"]').val();
    var requestUrl = $("#requestUrl").val();

    Number.prototype.formatMoney = function(c, d, t){
    var n = this, 
        c = isNaN(c = Math.abs(c)) ? 2 : c, 
        d = d == undefined ? "." : d, 
        t = t == undefined ? "," : t, 
        s = n < 0 ? "-" : "", 
        i = String(parseInt(n = Math.abs(Number(n) || 0).toFixed(c))), 
        j = (j = i.length) > 3 ? j % 3 : 0;
       return s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
     };
    
    // create DropDownList from select HTML element
    
    $("#otc_entry_details_update_form").kendoValidator({
        validateOnBlur: false,
        rules: {
         customRule2: function(input){
              if (input.is("[name=customerName]")) {
                if(input.val() == ''){
                    return false;    

                }else{
                    return true;
                }
              }
              return true;
          },
          customRule6: function(input){
              if (input.is("[name=brokerAccountId]")) {
                if(input.val() == ''){
                    return false;    

                }else{
                    return true;
                }
              }
              return true;
          },customRule7: function(input){
              if (input.is("[name=customerAccountId]")) {
                if(input.val() == ''){
                    return false;    

                }else{
                    return true;
                }
              }
              return true;
          },
          customRule1: function(input){
            if(input.is("[id=assetListFrom]")){
            var ms = input.data("kendoMultiSelect");       
                if(ms.value().length === 0){
                     return false;
                 }
            } 
            return true;
          },customRule3: function(input){
              if(input.is("[id=assetListFrom]")){
                var ms = input.data("kendoMultiSelect");       
                    if(ms.value().length === 1){
                         return false;
                     }
              } 
                return true;
          }
        },
        messages: {
            customRule1: "Please Select Asset",
            customRule2: "Please Enter Customer",
            customRule3: "Into Asset Required",
            customRule4: "Rates Required",
            customRule5: "Price Required",
            customRule6:"Broker Acccount Required",
            customRule7:"Customer Acccount Required",
        },
        validate: function(e) {
            console.log("valid" + e.valid);
        },
        validateInput: function(e) {
            console.log("input " + e.input.attr("customerAccountId") + " changed to valid: " + e.valid);
        }
    });
    var validatable = $("#otc_entry_details_update_form").data("kendoValidator");
    //validate the state on button click
    $("#orderSave").click(function() {
        //validate the input elements and check if there are any errors
        if (validatable.validate()) {
            var selectedAssetValues = $("#assetListFrom").data("kendoMultiSelect");
            var assetFromId = selectedAssetValues.value()[0];
            var assetIntoId = selectedAssetValues.value()[1];
            var settlementAssetId = $("#settlementListAutoComple").data("kendoComboBox").value();
            var settlementAssetName = $("#settlementListAutoComple").data("kendoComboBox").text()
            if(!settlementAssetId)
            {

                settlementAssetId = 1;
                settlementAssetName = "USD";
            }
            $.ajax({
                type:'POST',
                data:{_token:token,asset_id:assetFromId},
                dataType:"json",
                url : requestUrl+"/asset_type_list",
                error:function(xhr,status,error) {
                    console.log(error);
                },
                success:function(assetTypeListResponse,status,xhr) {
                    if(assetTypeListResponse.asset_type_code == 'fiat')
                    {
                        /* buy */
                        $.ajax({
                            type:'POST',
                            data:{_token:token,asset_into_id:assetIntoId,asset_settlement:settlementAssetId},
                            dataType:"json",
                            url : requestUrl+"/asset_buy_price",
                            error:function(xhr,status,error) {
                                console.log(error);
                            },
                            success:function(assetBuyPrice,status,xhr) {
                                 $.ajax({
                                    type:'POST',
                                    data:{_token:token,asset_from_id:assetFromId,asset_settlement:settlementAssetId},
                                    dataType:"json",
                                    url : requestUrl+"/asset_sell_price",
                                    error:function(xhr,status,error) {
                                        console.log(error);
                                    },
                                    success:function(assetSellPrice,status,xhr) {
                                        if(assetBuyPrice && assetBuyPrice.length != 0 && assetSellPrice && assetSellPrice.length != 0)
                                        {
                                            var priceCustomer = 1/(assetSellPrice.asset_ask_price * assetBuyPrice.asset_bid_price);
                                            if(parseFloat($("#price").data("kendoNumericTextBox").value()) > priceCustomer)
                                            {
                                                var offsetfraction = parseFloat($("#price").data("kendoNumericTextBox").value())/priceCustomer;
                                            } else {
                                                var offsetfraction = priceCustomer/parseFloat($("#price").data("kendoNumericTextBox").value());
                                            }
                                            
                                            var buyGrossPrice = assetBuyPrice.asset_bid_price * parseFloat($("#quantity").data("kendoNumericTextBox").value());
                                            var totalBuyGross = buyGrossPrice + (buyGrossPrice*parseFloat($("#tradingfeeTemplates").html())/100);
                                            $("#coinConformDetails").html($("#assetTemplates").html());
                                            $("#quantityConformDetails").html(parseFloat($("#quantity").data("kendoNumericTextBox").value()));
                                            $("#grossPriceConformDetails").html(buyGrossPrice.formatMoney(4)+'\xa0'+settlementAssetName);
                                            $("#TradingFeeConformDetails").html(parseFloat($("#tradingfeeTemplates").html())+'%');
                                            $("#totalConsiderationConformDetails").html(totalBuyGross.formatMoney(4)+'\xa0'+settlementAssetName);
                                            $("#sideConformDetails").html("You Bought");
                                            
                                            $("#marketOffsetConformDetails").html(offsetfraction.toFixed(2)+'%');
                                            if(offsetfraction > 5)
                                            {
                                                $("#marketOffsetTd").css('background-color','#FB8678')
                                            }
                                        }
                                    }
                                });
                            }
                        });
                    } else {
                        /* sell */
                        $.ajax({
                            type:'POST',
                            data:{_token:token,asset_into_id:assetIntoId,asset_settlement:settlementAssetId},
                            dataType:"json",
                            url : requestUrl+"/asset_buy_price",
                            error:function(xhr,status,error) {
                                console.log(error);
                            },
                            success:function(assetBuyPrice,status,xhr) {
                                $.ajax({
                                    type:'POST',
                                    data:{_token:token,asset_from_id:assetFromId,asset_settlement:settlementAssetId},
                                    dataType:"json",
                                    url : requestUrl+"/asset_sell_price",
                                    error:function(xhr,status,error) {
                                        console.log(error);
                                    },
                                    success:function(assetSellPrice,status,xhr) {
                                        if(assetSellPrice && assetSellPrice.length != 0 && assetBuyPrice && assetBuyPrice.length != 0)
                                        {
                                            var priceTrader = 1/(assetSellPrice.asset_ask_price * assetBuyPrice.asset_bid_price);

                                            if(parseFloat($("#priceTrader").data("kendoNumericTextBox").value()) > priceTrader)
                                            {
                                                var offsetfraction = parseFloat($("#priceTrader").data("kendoNumericTextBox").value())/priceTrader;
                                            } else {
                                                var offsetfraction = priceTrader/parseFloat($("#priceTrader").data("kendoNumericTextBox").value());
                                            }

                                            var sellGrossPrice = assetSellPrice.asset_ask_price * parseFloat($("#quantityTrader").data("kendoNumericTextBox").value());
                                            var totalSellGross = sellGrossPrice - (sellGrossPrice*parseFloat($("#traderTradingfeeTemplates").html())/100);
                                            $("#coinConformDetails").html($("#assetTraderTemplates").html());
                                            $("#quantityConformDetails").html(parseFloat($("#quantityTrader").data("kendoNumericTextBox").value()));
                                            $("#grossPriceConformDetails").html(sellGrossPrice.formatMoney(4)+'\xa0'+settlementAssetName);
                                            $("#TradingFeeConformDetails").html(parseFloat($("#traderTradingfeeTemplates").html())+'%');
                                            $("#totalConsiderationConformDetails").html(totalSellGross.formatMoney(4)+'\xa0'+settlementAssetName);
                                            $("#sideConformDetails").html("You Sell");
                                            $("#marketOffsetConformDetails").html(offsetfraction.toFixed(2)+'%');
                                            if(offsetfraction > 5)
                                            {
                                                $("#marketOffsetTd").css('background-color','#FB8678')
                                            }
                                        }
                                    }
                                });
                            }
                        });
                        
                    }
                }
            });
            $('#orderConform').modal('show');
        } 
    });

    $("#saveOrder").click(function() {
        $('#otc_entry_details_update_form').trigger('submit'); 
    });


    //recalculate the values
    $("#orderRecalculate").click(function() {
        //validate the input elements and check if there are any errors
        if (validatable.validate()) {
            $("#fromIntoAssetTitle").html($("#assetTraderTemplates").html()+$("#assetTemplates").html());
            $("#intoFromAssetTitle").html($("#assetTemplates").html()+$("#assetTraderTemplates").html());
            $("#per_2").focusin(function() {
                $("#brok_"+parseFloat($(this).attr("id").toString().split('').pop())).prop("checked", true);
            });
            $("#per_3").focusin(function() {
                $("#brok_"+parseFloat($(this).attr("id").toString().split('').pop())).prop("checked", true);
            });
            if(parseFloat($("#priceTrader").data("kendoNumericTextBox").value()) == 1)
            {
                var recalculateIntoPrice = 1/parseFloat($("#price").data("kendoNumericTextBox").value());
                var recalculateFromPrice = 1/recalculateIntoPrice;

                $("#from_1").data("kendoNumericTextBox").value(recalculateFromPrice.formatMoney(8));
                $("#into_1").data("kendoNumericTextBox").value(recalculateIntoPrice.formatMoney(8));
                var recalculateHalfFromPrice = recalculateFromPrice + (recalculateFromPrice*parseFloat($("#per_2").data("kendoNumericTextBox").value())/100);
                var recalculateHalfIntoPrice = recalculateIntoPrice + (recalculateIntoPrice*parseFloat($("#per_2").data("kendoNumericTextBox").value())/100);

                var recalculateThirdFouthFromPrice = recalculateFromPrice + (recalculateFromPrice*parseFloat($("#per_3").data("kendoNumericTextBox").value())/100);
                var recalculateThirdFouthIntoPrice = recalculateIntoPrice + (recalculateIntoPrice*parseFloat($("#per_3").data("kendoNumericTextBox").value())/100);

                

                $("#from_2").val(recalculateHalfFromPrice.formatMoney(8));
                $("#into_2").val(recalculateHalfIntoPrice.formatMoney(8));

                $("#from_3").val(recalculateThirdFouthFromPrice.formatMoney(8));
                $("#into_3").val(recalculateThirdFouthIntoPrice.formatMoney(8));
                /*recalculation logic */
                $("#per_2").focusout(function() {
                    var recalculateHalfFromPrice = parseFloat($("#from_1").data("kendoNumericTextBox").value()) + (parseFloat($("#from_1").data("kendoNumericTextBox").value())*parseFloat($(this).val())/100);
                        var recalculateHalfIntoPrice = parseFloat($("#into_1").data("kendoNumericTextBox").value()) + (parseFloat($("#into_1").data("kendoNumericTextBox").value())*parseFloat($(this).val())/100);
                        $("#from_2").val(recalculateHalfFromPrice.formatMoney(8));
                        $("#into_2").val(recalculateHalfIntoPrice.formatMoney(8));
                    
                });

                $("#per_3").focusout(function() {
                    var recalculateHalfFromPrice = parseFloat($("#from_1").data("kendoNumericTextBox").value()) + (parseFloat($("#from_1").data("kendoNumericTextBox").value())*parseFloat($(this).val())/100);
                        var recalculateHalfIntoPrice = parseFloat($("#into_1").data("kendoNumericTextBox").value()) + (parseFloat($("#into_1").data("kendoNumericTextBox").value())*parseFloat($(this).val())/100);
                        $("#from_3").val(recalculateHalfFromPrice.formatMoney(8));
                        $("#into_3").val(recalculateHalfIntoPrice.formatMoney(8));
                    
                });
                $("#from_1").focusout(function() {
                    console.log("in-out");
                    console.log("in-from");
                    var recalculateHalfFromPrice = parseFloat($(this).val()) + (parseFloat($(this).val())*parseFloat($("#per_2").data("kendoNumericTextBox").value())/100);
                    var recalculateThirdFourthFromPrice = parseFloat($(this).val()) + (parseFloat($(this).val())*parseFloat($("#per_3").data("kendoNumericTextBox").value())/100);
                    
                    $("#from_2").val(recalculateHalfFromPrice.formatMoney(8));
                    $("#from_3").val(recalculateThirdFourthFromPrice.formatMoney(8));

                    var recalculateIntoPrice = 1/parseFloat($(this).val());
                    var recalculateHalfIntoPrice = recalculateIntoPrice + (recalculateIntoPrice*parseFloat($("#per_2").data("kendoNumericTextBox").value())/100);
                    var recalculateThirdFourthIntoPrice = recalculateIntoPrice + (recalculateIntoPrice*parseFloat($("#per_3").data("kendoNumericTextBox").value())/100);
                    $("#into_1").data("kendoNumericTextBox").value(recalculateIntoPrice);
                    $("#into_2").val(recalculateHalfIntoPrice.formatMoney(8));
                    $("#into_3").val(recalculateThirdFourthIntoPrice.formatMoney(8));
                });

                $("#into_1").focusout(function() {
                    console.log("in-into-out");
                    console.log("in-into");
                    var recalculateHalfIntoPrice = parseFloat($(this).val()) + (parseFloat($(this).val())*parseFloat($("#per_2").data("kendoNumericTextBox").value())/100);
                    var recalculateThirdFourthIntoPrice = parseFloat($(this).val()) + (parseFloat($(this).val())*parseFloat($("#per_3").data("kendoNumericTextBox").value())/100);
                    $("#into_2").val(recalculateHalfIntoPrice.formatMoney(8));
                    $("#into_3").val(recalculateThirdFourthIntoPrice.formatMoney(8));

                    var recalculateFromPrice = 1/parseFloat($(this).val());
                    var recalculateHalfFromPrice = recalculateFromPrice + (recalculateFromPrice*parseFloat($("#per_2").data("kendoNumericTextBox").value())/100);
                    var recalculateThirdFourthFromPrice = recalculateFromPrice + (recalculateFromPrice*parseFloat($("#per_3").data("kendoNumericTextBox").value())/100);
                    $("#from_1").data("kendoNumericTextBox").value(recalculateFromPrice);
                    $("#from_2").val(recalculateHalfFromPrice.formatMoney(8));
                    $("#from_3").val(recalculateThirdFourthFromPrice.formatMoney(8));


                });
                /*end recalculation logic */

            } /*if(parseFloat($("#price").data("kendoNumericTextBox").value()) == 1)*/
            else
            {
                var recalculateFromPrice = 1/parseFloat($("#priceTrader").data("kendoNumericTextBox").value());
                var recalculateIntoPrice = 1/recalculateFromPrice;
                $("#from_1").data("kendoNumericTextBox").value(recalculateFromPrice);
                $("#into_1").data("kendoNumericTextBox").value(recalculateIntoPrice);

                var recalculateHalfFromPrice = recalculateFromPrice - (recalculateFromPrice*parseFloat($("#per_2").data("kendoNumericTextBox").value())/100);
                var recalculateHalfIntoPrice = recalculateIntoPrice - (recalculateIntoPrice*parseFloat($("#per_2").data("kendoNumericTextBox").value())/100);

                var recalculateThirdFouthFromPrice = recalculateFromPrice - (recalculateFromPrice*parseFloat($("#per_3").data("kendoNumericTextBox").value())/100);
                var recalculateThirdFouthIntoPrice = recalculateIntoPrice - (recalculateIntoPrice*parseFloat($("#per_3").data("kendoNumericTextBox").value())/100);

                

                $("#from_2").val(recalculateHalfFromPrice.formatMoney(8));
                $("#into_2").val(recalculateHalfIntoPrice.formatMoney(8));

                $("#from_3").val(recalculateThirdFouthFromPrice.formatMoney(8));
                $("#into_3").val(recalculateThirdFouthIntoPrice.formatMoney(8));

                /*recalculation logic */
                $("#per_2").focusout(function() {
                    var recalculateHalfFromPrice = parseFloat($("#from_1").data("kendoNumericTextBox").value()) - (parseFloat($("#from_1").data("kendoNumericTextBox").value())*parseFloat($(this).val())/100);
                    var recalculateHalfIntoPrice = parseFloat($("#into_1").data("kendoNumericTextBox").value()) - (parseFloat($("#into_1").data("kendoNumericTextBox").value())*parseFloat($(this).val())/100);
                    $("#from_2").val(recalculateHalfFromPrice.formatMoney(8));
                    $("#into_2").val(recalculateHalfIntoPrice.formatMoney(8));
                    
                });

                $("#per_3").focusout(function() {
                    var recalculateHalfFromPrice = parseFloat($("#from_1").data("kendoNumericTextBox").value()) - (parseFloat($("#from_1").data("kendoNumericTextBox").value())*parseFloat($(this).val())/100);
                    var recalculateHalfIntoPrice = parseFloat($("#into_1").data("kendoNumericTextBox").value()) - (parseFloat($("#into_1").data("kendoNumericTextBox").value())*parseFloat($(this).val())/100);
                    $("#from_3").val(recalculateHalfFromPrice.formatMoney(8));
                    $("#into_3").val(recalculateHalfIntoPrice.formatMoney(8));
                    
                });
                $("#from_1").focusout(function() {
                    var recalculateHalfFromPrice = parseFloat($(this).val()) - (parseFloat($(this).val())*parseFloat($("#per_2").data("kendoNumericTextBox").value())/100);
                    var recalculateThirdFourthFromPrice = parseFloat($(this).val()) - (parseFloat($(this).val())*parseFloat($("#per_3").data("kendoNumericTextBox").value())/100);
                    
                    $("#from_2").val(recalculateHalfFromPrice.formatMoney(8));
                    $("#from_3").val(recalculateThirdFourthFromPrice.formatMoney(8));

                    var recalculateIntoPrice = 1/parseFloat($(this).val());
                    var recalculateHalfIntoPrice = recalculateIntoPrice - (recalculateIntoPrice*parseFloat($("#per_2").data("kendoNumericTextBox").value())/100);
                    var recalculateThirdFourthIntoPrice = recalculateIntoPrice - (recalculateIntoPrice*parseFloat($("#per_3").data("kendoNumericTextBox").value())/100);
                    $("#into_1").data("kendoNumericTextBox").value(recalculateIntoPrice);
                    $("#into_2").val(recalculateHalfIntoPrice.formatMoney(8));
                    $("#into_3").val(recalculateThirdFourthIntoPrice.formatMoney(8));
                });

                $("#into_1").focusout(function() {
                    var recalculateHalfIntoPrice = parseFloat($(this).val()) - (parseFloat($(this).val())*parseFloat($("#per_2").data("kendoNumericTextBox").value())/100);
                    var recalculateThirdFourthIntoPrice = parseFloat($(this).val()) - (parseFloat($(this).val())*parseFloat($("#per_3").data("kendoNumericTextBox").value())/100);
                    $("#into_2").val(recalculateHalfIntoPrice.formatMoney(8));
                    $("#into_3").val(recalculateThirdFourthIntoPrice.formatMoney(8));

                    var recalculateFromPrice = 1/parseFloat($(this).val());
                    var recalculateHalfFromPrice = recalculateFromPrice - (recalculateFromPrice*parseFloat($("#per_2").data("kendoNumericTextBox").value())/100);
                    var recalculateThirdFourthFromPrice = recalculateFromPrice - (recalculateFromPrice*parseFloat($("#per_3").data("kendoNumericTextBox").value())/100);
                    $("#from_1").data("kendoNumericTextBox").value(recalculateFromPrice);
                    $("#from_2").val(recalculateHalfFromPrice.formatMoney(8));
                    $("#from_3").val(recalculateThirdFourthFromPrice.formatMoney(8));
                });
                /*end recalculation logic */
            }

            
            $('#orderRecalculateModel').modal('show');
        }
    });
    
    $("#acceptRecalculate").click(function() {
        if(parseFloat($("#priceTrader").data("kendoNumericTextBox").value()) == 1)
        {
            var rowNumber = $("input[name='selectedper']:checked", '#orderRecalculateTable').val();
            $("#price").data("kendoNumericTextBox").value(parseFloat($("#from_"+rowNumber).val()));
            $('#price').trigger('focusout');
            
        }

        if(parseFloat($("#price").data("kendoNumericTextBox").value()) == 1)
        {
            var rowNumber = $("input[name='selectedper']:checked", '#orderRecalculateTable').val();
            $("#priceTrader").data("kendoNumericTextBox").value(parseFloat($("#into_"+rowNumber).val()));
            $('#priceTrader').trigger('focusout');
            
        }
        $('#orderRecalculateModel').modal('hide');
    });
    
    $('#orderConform').on('hidden.bs.modal', function () {
        $("#coinConformDetails").html("");
        $("#quantityConformDetails").html("");
        $("#grossPriceConformDetails").html("");
        $("#TradingFeeConformDetails").html("");
        $("#totalConsiderationConformDetails").html("");
        $("#marketOffsetConformDetails").html("");
        $("#marketOffsetTd").css('background-color','white');
    });

    $('#orderRecalculateModel').on('hidden.bs.modal', function () {
        $("#from_2").val("");
        $("#from_3").val("");
        $("#into_2").val("");
        $("#into_3").val("");
        $("#from_1").data("kendoNumericTextBox").value("");
        $("#into_1").data("kendoNumericTextBox").value("");
    });
    
    /* broker Details */
    function brokerDropDownListChange(e)
    {
        var token = $('input[name="_token"]').val();
        dataItems = e.sender.dataItems();
        /* check asset from selected or not*/
        if (!dataItems.hasOwnProperty(0)) {
            var fxRatesList = $("#assetListFrom").data("kendoMultiSelect");
            fxRatesList.dataSource.filter([])
            var exchangeId = $("#exchangeList").val();
            $('.content .preloader').show();
            $('.content .preloader img').show();
            $.ajax({
                type:'POST',
                dataType:"json",
                data:{_token:token,exchange_id:exchangeId},
                url : requestUrl+"/fx_rates_list",
                error:function(xhr,status,error) {
                    console.log(error);
                },
                success:function(fxRatesListReponse,status,xhr) {
                    var fxRatesList = $("#assetListFrom").data("kendoMultiSelect");
                    fxRatesList.dataSource.data(fxRatesListReponse);
                    $('.content .preloader').hide();
                    $('.content .preloader img').hide();
                }
            });
            
            var brokerAcccountList = $("#brokerAccountListAutoComple").data("kendoComboBox");
            brokerAcccountList.value("");
            brokerAcccountList.dataSource.data("");

            var settlementList = $("#settlementListAutoComple").data("kendoComboBox");
            settlementList.value(1);

            $("#sideTemplates").html("")
            $("#sideTraderTemplates").html("")

            $("#assetTraderTemplates").html("")
            $("#quantityTrader").data("kendoNumericTextBox").value("");
            $("#totalTraderTemplates").data("kendoNumericTextBox").value("");
            $("#accountTraderTemplates").html("")
            $("#AmountTraderTemplates").html("")
            $("#traderTradingfeeTemplates").html("")
        }
        /* check asset into selected or not*/
        if (!dataItems.hasOwnProperty(1) && dataItems.hasOwnProperty(0)) {
            $('.content .preloader').show();
            $('.content .preloader img').show();
            var customerAcccountList = $("#customerAccountListAutoComple").data("kendoComboBox");
            customerAcccountList.value("");

            $("#assetTemplates").html("")
            $("#accountTemplates").html("")
            $("#amountTemplates").html("")
            $("#tradingfeeTemplates").html("")
            $("#price").data("kendoNumericTextBox").value("");
            $("#priceTrader").data("kendoNumericTextBox").value("");
            $("#quantity").data("kendoNumericTextBox").value("");
            $("#quantityTrader").data("kendoNumericTextBox").value("");
            $("#totalTemplates").data("kendoNumericTextBox").value("");
            $("#totalTraderTemplates").data("kendoNumericTextBox").value("");

            var exchangeId = $("input[name=exchangeList]").val();
            var assetFromId = dataItems[0].asset_id;
            var assetFromName = dataItems[0].identity_code;
            $.ajax({
                type:'POST',
                data:{_token:token,asset_id:assetFromId,exchange_id:exchangeId},
                dataType:"json",
                url : requestUrl+"/asset_into_Value",
                error:function(xhr,status,error) {
                    console.log(error);
                },
                success:function(assetIntoResponse,status,xhr) {
                    assetIntoResponse.push({identity_code:assetFromName,asset_id:assetFromId});
                    if (assetIntoResponse && assetIntoResponse.length != 0) {
                        var assetIntoList = $("#assetListFrom").data("kendoMultiSelect");
                        assetIntoList.dataSource.data(assetIntoResponse);
                    }
                    else {
                        var assetIntoList = $("#assetListFrom").data("kendoMultiSelect");
                        assetIntoList.dataSource.filter({
                            field: 'asset_id',
                            operator: 'eq',
                            value: assetFromId
                        });
                    }
                    $('.content .preloader').hide();
                    $('.content .preloader img').hide();
                    
                }
            });
        }

        /* update customer based on asset from */
        if (dataItems.hasOwnProperty(0)) {
            $('.content .preloader').show();
            $('.content .preloader img').show();
            $("#price").data("kendoNumericTextBox").enable(true);
            $("#priceTrader").data("kendoNumericTextBox").enable(true);
            var assetFromId = dataItems[0].asset_id;
            var assetFromCode = dataItems[0].identity_code;
            $("#assetTraderTemplates").html(assetFromCode);
            var merchantId = $("select[name=brokerName]").val();
            $.ajax({
                type:'POST',
                data:{_token:token,asset_id:assetFromId,merchant_id:merchantId},
                dataType:"json",
                url : requestUrl+"/merchant_account_list",
                error:function(xhr,status,error) {
                    console.log(error);
                },
                success:function(brokerAccountResponse,status,xhr) {
                    var brokerAcccountList = $("#brokerAccountListAutoComple").data("kendoComboBox");
                    brokerAcccountList.value("");
                    if(brokerAccountResponse && brokerAccountResponse.length != 0)
                    {
                        brokerAcccountList.dataSource.data(brokerAccountResponse);

                        if(brokerAccountResponse.length == 1)
                        {
                            $("#brokerAccountListAutoComple").data("kendoComboBox").value(brokerAccountResponse[0].account_code_long);
                            $("#accountTraderTemplates").html(brokerAccountResponse[0].account_code_long);
                            $("#AmountTraderTemplates").html(brokerAccountResponse[0].asset_quantity);
                            $("#traderTradingfeeTemplates").html(brokerAccountResponse[0].fee_percentage);
                            if(brokerAccountResponse[0].account_settlement != 0)
                            {
                                $("#settlementListAutoComple").data("kendoComboBox").value(brokerAccountResponse[0].account_settlement);
                            }
                        }
                    }
                    $('.content .preloader').hide();
                    $('.content .preloader img').hide();
                }
            });

            $.ajax({
                type:'POST',
                data:{_token:token,asset_id:assetFromId},
                dataType:"json",
                url : requestUrl+"/asset_type_list",
                error:function(xhr,status,error) {
                    console.log(error);
                },
                success:function(assetTypeListResponse,status,xhr) {
                    if(assetTypeListResponse.asset_type_code == 'fiat')
                    {
                        $("#sideTemplates").html("Buy")
                        $("#sideTraderTemplates").html("Sell")
                    } else {
                        $("#sideTemplates").html("Sell")
                        $("#sideTraderTemplates").html("Buy")
                    }
                    $('.content .preloader').hide();
                    $('.content .preloader img').hide();
                }
            });
        }
        /* update customer based on asset into */
        if (dataItems.hasOwnProperty(1) && currentlySelectedCustomer.hasOwnProperty("customer_id")) {
            $('.content .preloader').show();
            $('.content .preloader img').show();
            var customerId = currentlySelectedCustomer.customer_id;
            var assetFromId = dataItems[0].asset_id;
            var assetIntoId = dataItems[1].asset_id;
            var merchantId = $("select[name=brokerName]").val();
            $.ajax({
                type:'POST',
                data:{_token:token,asset_id:assetFromId,merchant_id:merchantId},
                dataType:"json",
                url : requestUrl+"/merchant_account_list",
                error:function(xhr,status,error) {
                    console.log(error);
                },
                success:function(brokerAccountResponse,status,xhr) {
                    if(brokerAccountResponse && brokerAccountResponse.length != 0)
                    {
                        if(brokerAccountResponse[0].account_settlement == 0)
                        {
                            $("#settlementListAutoComple").data("kendoComboBox").value(1);
                        }
                    }
                    $('.content .preloader').hide();
                    $('.content .preloader img').hide();
                }
            });
            $.ajax({
                type:'POST',
                data:{_token:token,asset_id:assetIntoId,customer_id:customerId},
                dataType:"json",
                url : requestUrl+"/customer_account_list",
                error:function(xhr,status,error) {
                    console.log(error);
                },
                success:function(customerAccountResponse,status,xhr) {
                    var customerAcccountList = $("#customerAccountListAutoComple").data("kendoComboBox");
                    customerAcccountList.value("");
                    if(customerAccountResponse && customerAccountResponse.length != 0)
                    {
                        customerAcccountList.dataSource.data(customerAccountResponse);
                        if(customerAccountResponse.length == 1)
                        {
                            $("#customerAccountListAutoComple").data("kendoComboBox").value(customerAccountResponse[0].account_code_long);
                            $("#accountTemplates").html(customerAccountResponse[0].account_code_long);
                            $("#amountTemplates").html(customerAccountResponse[0].asset_quantity);
                            $("#tradingfeeTemplates").html(customerAccountResponse[0].fee_percentage);
                        }
                    }
                    $('.content .preloader').hide();
                    $('.content .preloader img').hide();
                }
            });
        }

        if (dataItems.hasOwnProperty(1))
        {
            $('.content .preloader').show();
            $('.content .preloader img').show();
            var assetFromId = dataItems[0].asset_id;
            var assetIntoId = dataItems[1].asset_id;

            var merchantId = $("select[name=brokerName]").val();
            $.ajax({
                type:'POST',
                data:{_token:token,asset_id:assetFromId,merchant_id:merchantId},
                dataType:"json",
                async :false,
                url : requestUrl+"/merchant_account_list",
                error:function(xhr,status,error) {
                    console.log(error);
                },
                success:function(brokerAccountResponse,status,xhr) {
                    if(brokerAccountResponse && brokerAccountResponse.length != 0)
                    {
                        if(brokerAccountResponse[0].account_settlement == 0)
                        {
                            $("#settlementListAutoComple").data("kendoComboBox").value(1);
                        }
                        var assetIntoCode = dataItems[1].identity_code;
                        var settlementAssetId = $("#settlementListAutoComple").data("kendoComboBox").value();
                    }
                    
                    
                    $("#assetTemplates").html(assetIntoCode);
                    $.ajax({
                        type:'POST',
                        data:{_token:token,asset_from_id:assetFromId,asset_settlement:settlementAssetId},
                        dataType:"json",
                        url : requestUrl+"/asset_sell_price",
                        error:function(xhr,status,error) {
                            console.log(error);
                        },
                        success:function(assetSellPrice,status,xhr) {
                            $.ajax({
                                type:'POST',
                                data:{_token:token,asset_into_id:assetIntoId,asset_settlement:settlementAssetId},
                                dataType:"json",
                                url : requestUrl+"/asset_buy_price",
                                error:function(xhr,status,error) {
                                    console.log(error);
                                },
                                success:function(assetBuyPrice,status,xhr) {
                                    $.ajax({
                                        type:'POST',
                                        data:{_token:token,asset_id:assetFromId},
                                        dataType:"json",
                                        url : requestUrl+"/asset_type_list",
                                        error:function(xhr,status,error) {
                                            console.log(error);
                                        },
                                        success:function(assetTypeListResponse,status,xhr) {
                                            if(assetSellPrice && assetSellPrice.length != 0 && assetBuyPrice && assetBuyPrice.length != 0)
                                            {
                                                if(assetTypeListResponse.asset_type_code == 'fiat')
                                                {
                                                    var priceCustomer = 1/(assetSellPrice.asset_ask_price * assetBuyPrice.asset_bid_price);
                                                    var quantitytrader = assetSellPrice.asset_ask_price * assetBuyPrice.asset_bid_price;

                                                    $("#quantityTrader").data("kendoNumericTextBox").value(quantitytrader);
                                                    $("#quantity").data("kendoNumericTextBox").value(1);
                                                    $("#price").data("kendoNumericTextBox").value(priceCustomer);
                                                    $("#priceTrader").data("kendoNumericTextBox").value(1);
                                                    $("#priceTrader").data("kendoNumericTextBox").enable(false);
                                                    $("#priceTrader").prop('disabled',false);
                                                    $("#totalTraderTemplates").data("kendoNumericTextBox").value(quantitytrader);
                                                    $("#totalTemplates").data("kendoNumericTextBox").value(priceCustomer);
                                                } else {

                                                    var priceTrader = 1/(assetSellPrice.asset_ask_price * assetBuyPrice.asset_bid_price);
                                                    var quantityCustomer = assetSellPrice.asset_ask_price * assetBuyPrice.asset_bid_price;

                                                    $("#quantity").data("kendoNumericTextBox").value(quantityCustomer);
                                                    $("#quantityTrader").data("kendoNumericTextBox").value(1);
                                                    $("#priceTrader").data("kendoNumericTextBox").value(priceTrader);
                                                    $("#price").data("kendoNumericTextBox").value(1);
                                                    $("#price").data("kendoNumericTextBox").enable(false);
                                                    $("#price").prop('disabled',false);
                                                    $("#totalTraderTemplates").data("kendoNumericTextBox").value(priceTrader);
                                                    $("#totalTemplates").data("kendoNumericTextBox").value(quantityCustomer);
                                                }

                                            } else {
                                                $("#priceTrader").data("kendoNumericTextBox").enable(false);
                                                $("#priceTrader").prop('disabled',false);
                                                $("#price").data("kendoNumericTextBox").enable(false);
                                                $("#price").prop('disabled',false);
                                            }
                                            $('.content .preloader').hide();
                                            $('.content .preloader img').hide();
                                        }

                                    });
                                }
                            });
                        }
                    });
                }
            });
            
        }
    }

    var brokerDetailsList = new kendo.data.DataSource({
        transport : {   
            read : {
                dataType : "json",
                url : requestUrl+"/broker_name_details",
                type : "GET",
            }
        }
    });
    var brokerDropDownList = $("#brokerDropDownList").kendoDropDownList({
        dataTextField: "identity_name",
        dataValueField: "merchant_id",
        filter: "contains",
        height: 400,
        dataSource: brokerDetailsList,
        select: function(e) {
            $('.content .preloader').show();
            $('.content .preloader img').show();
            currentlySelectedBroker = this.dataItem(e.item.index());
            if($("#assetListFrom").data("kendoMultiSelect").value()[0])
            {
                var assetFromId = $("#assetListFrom").data("kendoMultiSelect").value()[0];
                $.ajax({
                    type:'POST',
                    data:{_token:token,asset_id:assetFromId,merchant_id:currentlySelectedBroker.merchant_id},
                    dataType:"json",
                    url : requestUrl+"/merchant_account_list",
                    error:function(xhr,status,error) {
                        console.log(error);
                    },
                    success:function(brokerAccountResponse,status,xhr) {
                        var brokerAcccountList = $("#brokerAccountListAutoComple").data("kendoComboBox");
                        brokerAcccountList.value("");
                        if(brokerAccountResponse && brokerAccountResponse.length != 0)
                        {
                            brokerAcccountList.dataSource.data(brokerAccountResponse);
                            if(brokerAccountResponse.length == 1)
                            {
                                $("#brokerAccountListAutoComple").data("kendoComboBox").value(brokerAccountResponse[0].account_code_long);
                                $("#accountTraderTemplates").html(brokerAccountResponse[0].account_code_long);
                                $("#AmountTraderTemplates").html(brokerAccountResponse[0].asset_quantity);
                                $("#traderTradingfeeTemplates").html(brokerAccountResponse[0].fee_percentage);
                                $("#settlementListAutoComple").data("kendoComboBox").value(brokerAccountResponse[0].account_settlement);
                            }
                        }
                        $('.content .preloader').hide();
                        $('.content .preloader img').hide();
                    }
                });
            }
            $.ajax({
                type:'POST',
                data:{_token:token,merchant_id:currentlySelectedBroker.merchant_id},
                dataType:"json",
                url : requestUrl+"/customer_list",
                error:function(xhr,status,error) {
                    console.log(error);
                },
                success:function(customerListResponse,status,xhr) {
                    var customerAcccountList = $("#customerAutoComplete").data("kendoComboBox");
                    customerAcccountList.value("");
                    if(customerListResponse && customerListResponse.length != 0)
                    {
                        customerAcccountList.dataSource.data(customerListResponse);
                    }
                    var customerAcccountList = $("#customerAccountListAutoComple").data("kendoComboBox");
                    customerAcccountList.value("");
                    customerAcccountList.dataSource.data("");
                    $('.content .preloader').hide();
                    $('.content .preloader img').hide();
                }
            });
        }
    }).data("kendoDropDownList");

    brokerDropDownList.dataSource.one("change", function() {
        var merchantId = $("#brokerDropDownList").val();
        /* Get list of customers*/
        $.ajax({
            type:'POST',
            data:{_token:token,merchant_id:merchantId},
            dataType:"json",
            url : requestUrl+"/customer_list",
            error:function(xhr,status,error) {
                console.log(error);
            },
            success:function(customerDataSource,status,xhr) {
                var customerList = $("#customerAutoComplete").data("kendoComboBox");
                customerList.dataSource.data(customerDataSource);
            }
        });
    });

    /* broker account Details autocomplete initilized*/
    $("#brokerAccountListAutoComple").kendoComboBox({
        dataTextField: "account_code_long",
        dataValueField: "account_id",
        placeholder: "Select Broker...",
        filter: "contains",
        height: 400,
        select: function(e) {
            currentlySelectedBrokerAccount = this.dataItem(e.item.index());
            $("#accountTraderTemplates").html(currentlySelectedBrokerAccount.account_code_long);
            $("#AmountTraderTemplates").html(currentlySelectedBrokerAccount.asset_quantity);
            $("#traderTradingfeeTemplates").html(currentlySelectedBrokerAccount.fee_percentage);
            $.ajax({
                type:'POST',
                data:{_token:token,account_id:currentlySelectedBrokerAccount.account_id},
                dataType:"json",
                url : requestUrl+"/account_settlement_selected",
                error:function(xhr,status,error) {
                    console.log(error);
                },
                success:function(assetSettlementResponse,status,xhr) {
                    var settlementList = $("#settlementListAutoComple").data("kendoComboBox");
                    if(assetSettlementResponse.account_settlement && assetSettlementResponse.account_settlement != 0)
                    {
                        settlementList.value(assetSettlementResponse.account_settlement);
                    }
                }
            });

        }
    }).data("kendoComboBox");


    /* Get list of customers*/
    $("#orderBasketConfirm").kendoComboBox({
        placeholder: "Select Basket...",
        dataTextField: "basket_code",
        dataValueField: "basket_id",
        filter: "contains",
        value:0,
    });
    
    
    

    $("#customerAutoComplete").kendoComboBox({
        dataTextField: "identity_name",
        filter: "contains",
        dataValueField: "customer_id",
        placeholder: "Select Customer...",
        select: function(e) {
            currentlySelectedCustomer = this.dataItem(e.item.index());
            var multiSelectedVal = $("#assetListFrom").data("kendoMultiSelect").value();
            var customerId = currentlySelectedCustomer.customer_id;
            var merchantId = $("#brokerDropDownList").data("kendoDropDownList").value();
            $.ajax({
                type:'POST',
                dataType : "json",
                data:{_token:token,merchant_id:merchantId,customer_id:customerId},
                url : requestUrl+"/trade_basket_details",
                error:function(xhr,status,error) {
                    console.log(error);
                },
                success:function(basketDetailsResponse,status,xhr) {
                    var baskerDetailsList = $("#orderBasketConfirm").data("kendoComboBox");
                    baskerDetailsList.value("");
                    baskerDetailsList.dataSource.data(basketDetailsResponse);
                }
            });
            if(typeof multiSelectedVal[1] !== 'undefined') {
                var assetIntoId = multiSelectedVal[1];
                $.ajax({
                    type:'POST',
                    data:{_token:token,asset_id:assetIntoId,customer_id:customerId},
                    dataType:"json",
                    url : requestUrl+"/customer_account_list",
                    error:function(xhr,status,error) {
                        console.log(error);
                    },
                    success:function(customerAccountResponse,status,xhr) {
                        var customerAcccountList = $("#customerAccountListAutoComple").data("kendoComboBox");
                        customerAcccountList.value("");
                        if(customerAccountResponse && customerAccountResponse.length != 0)
                        {
                            customerAcccountList.dataSource.data(customerAccountResponse);
                            if(customerAccountResponse.length == 1)
                            {
                                $("#customerAccountListAutoComple").data("kendoComboBox").value(customerAccountResponse[0].account_code_long);
                                $("#accountTemplates").html(customerAccountResponse[0].account_code_long);
                                $("#amountTemplates").html(customerAccountResponse[0].asset_quantity);
                                $("#tradingfeeTemplates").html(customerAccountResponse[0].fee_percentage)
                            }
                        }
                    }
                });
            } 
            

        }
    }).data("kendoComboBox");
    /* end list of customers*/


    /* customer Account list autocomplete initilized */
    var accountList= $("#customerAccountListAutoComple").kendoComboBox({
        dataTextField: "account_code_long",
        dataValueField: "account_id",
        placeholder: "Select Account...",
        height: 400,
        select: function(e) {
            currentlySelectedCustomerAccount = this.dataItem(e.item.index());
            $("#accountTemplates").html(currentlySelectedCustomerAccount.account_code_long);
            $("#amountTemplates").html(currentlySelectedCustomerAccount.asset_quantity);
            $("#tradingfeeTemplates").html(currentlySelectedCustomerAccount.fee_percentage)
        }
    }).data("kendoComboBox");
    /* end customer Account list autocomplete initilized */

    /* exchange list */

    var exchangeDetailsList = new kendo.data.DataSource({
        transport : {   
            read : {
                dataType : "json",
                url : requestUrl+"/exchange_name_details",
                type : "GET",
            }
        }
    });
    var exchangedDropDownList = $("#exchangeList").kendoDropDownList({
        dataTextField: "identity_name",
        dataValueField: "exchange_id",
        filter: "contains",
        height: 400,
        dataSource: exchangeDetailsList,
        change: function() {
            var brokerAcccountList = $("#brokerAccountListAutoComple").data("kendoComboBox");
            brokerAcccountList.value("");
            brokerAcccountList.dataSource.data("");

            var settlementList = $("#settlementListAutoComple").data("kendoComboBox");
            settlementList.value(1);

            var customerAcccountList = $("#customerAccountListAutoComple").data("kendoComboBox");
            customerAcccountList.value("");
            customerAcccountList.dataSource.data("");


            $("#sideTemplates").html("")
            $("#sideTraderTemplates").html("")
            $("#accountTemplates").html("")
            $("#amountTemplates").html("")
            $("#tradingfeeTemplates").html("")
            $("#accountTraderTemplates").html("")
            $("#AmountTraderTemplates").html("")
            $("#traderTradingfeeTemplates").html("")
            $("#assetTemplates").html("")
            $("#assetTraderTemplates").html("")

            $("#priceTrader").data("kendoNumericTextBox").value("");
            $("#price").data("kendoNumericTextBox").value("");
            $("#quantity").data("kendoNumericTextBox").value("");
            $("#quantityTrader").data("kendoNumericTextBox").value("");
            $("#totalTemplates").data("kendoNumericTextBox").value("");
            $("#totalTraderTemplates").data("kendoNumericTextBox").value("");
            var exchangeId = this.value();
            $.ajax({
                type:'POST',
                data:{_token:token,exchange_id:exchangeId},
                dataType:"json",
                url : requestUrl+"/fx_rates_list",
                error:function(xhr,status,error) {
                    console.log(error);
                },
                success:function(assetsResponse,status,xhr) {
                    var assetMultiSelectList = $("#assetListFrom").data("kendoMultiSelect");
                    assetMultiSelectList.value("");
                    assetMultiSelectList.dataSource.data(assetsResponse);
                }
            });
            
        }
    }).data("kendoDropDownList");

    /* asset pair list */
    $("#assetListFrom").kendoMultiSelect({
        dataTextField: "identity_code",
        dataValueField: "asset_id",
        change: brokerDropDownListChange,
    });
    $("#assetListFrom").data("kendoMultiSelect").options.maxSelectedItems = 2;
    
    /* Settlement combo box */ 
    $("#settlementListAutoComple").kendoComboBox({
        dataTextField: "asset_code",
        dataValueField: "asset_id",
    });

    exchangedDropDownList.dataSource.one("change", function() {
        var exchangeId = $("#exchangeList").val();
        $.ajax({
            type:'POST',
            data:{_token:token,exchange_id:exchangeId},
            dataType:"json",
            url : requestUrl+"/fx_rates_list",
            error:function(xhr,status,error) {
                console.log(error);
            },
            success:function(assetsResponse,status,xhr) {
                var assetMultiSelectList = $("#assetListFrom").data("kendoMultiSelect");
                assetMultiSelectList.value("");
                assetMultiSelectList.dataSource.data(assetsResponse);
            }
        });

        $.ajax({
            type:'POST',
            data:{_token:token,exchange_id:exchangeId},
            dataType:"json",
            url : requestUrl+"/asset_settlement_details",
            error:function(xhr,status,error) {
                console.log(error);
            },
            success:function(assetSettlementResponse,status,xhr) {
                var settlementList = $("#settlementListAutoComple").data("kendoComboBox");
                settlementList.value("");
                settlementList.dataSource.data(assetSettlementResponse);
                settlementList.value(1);
            }
        });
    });
    $("#totalTemplates").kendoNumericTextBox({format: "n8",decimals: 10,min: 0});
    $("#quantity").kendoNumericTextBox({format: "n8",decimals: 10,min: 0});
    $("#price").kendoNumericTextBox({format: "n8",decimals: 10,min: 0});
    $("#totalTraderTemplates").kendoNumericTextBox({format: "n8",decimals: 10,min: 0});
    $("#quantityTrader").kendoNumericTextBox({format: "n8",decimals: 10,min: 0});
    $("#priceTrader").kendoNumericTextBox({format: "n8",decimals: 10,min: 0});


    /* for recalculation */
    
    $("#from_1").kendoNumericTextBox({format: "n8",decimals: 10,min: 0});
    $("#into_1").kendoNumericTextBox({format: "n8",decimals: 10,min: 0});
    $("#per_2").kendoNumericTextBox({format: "n2",decimals: 2,min: 0});
    $("#per_2").data("kendoNumericTextBox").value(0.50);

    $("#per_3").kendoNumericTextBox({format: "n2",decimals: 2,min: 0});
    $("#per_3").data("kendoNumericTextBox").value(0.75);

    /*end recalculation */ 
    
    
    $("#quantityTrader").focusout(function() {
        if((parseFloat($(this).val()) > 0) && (parseFloat($("#priceTrader").data("kendoNumericTextBox").value())>0) && (parseFloat($("#price").data("kendoNumericTextBox").value()) > 0 ))
        {   
            var totalTraderPrice = parseFloat($(this).val())*parseFloat($("#priceTrader").data("kendoNumericTextBox").value())
            $("#totalTraderTemplates").data("kendoNumericTextBox").value(totalTraderPrice);
            if(!$('#price').prop('disabled'))
            {
                var totalCustomerQuantity = parseFloat($(this).val())*parseFloat($("#price").data("kendoNumericTextBox").value())
            } else {
                var totalCustomerQuantity = parseFloat($(this).val())/parseFloat($("#priceTrader").data("kendoNumericTextBox").value())
            }
            $("#quantity").data("kendoNumericTextBox").value(totalCustomerQuantity);
            
            var totalCustomerPrice = parseFloat($("#quantity").data("kendoNumericTextBox").value())*parseFloat($("#price").data("kendoNumericTextBox").value());
            $("#totalTemplates").data("kendoNumericTextBox").value(totalCustomerPrice);
            
        }
        
    });

    $("#quantity").focusout(function() {
        if(($("#priceTrader").data("kendoNumericTextBox").value() > 0 )&& (parseFloat($(this).val()) > 0) && (parseFloat($("#price").data("kendoNumericTextBox").value()) >0))
        {   

            var totalCustomerPrice = parseFloat($(this).val())*parseFloat($("#price").data("kendoNumericTextBox").value());
            $("#totalTemplates").data("kendoNumericTextBox").value(totalCustomerPrice);

            if(!$('#price').prop('disabled'))
            {
                var totalTraderQuantity = parseFloat($(this).val())/parseFloat($("#price").data("kendoNumericTextBox").value());
            } else {
                var totalTraderQuantity = parseFloat($(this).val())*parseFloat($("#priceTrader").data("kendoNumericTextBox").value());
            }
            $("#quantityTrader").data("kendoNumericTextBox").value(totalTraderQuantity);

            var totalTraderPrice = parseFloat($("#quantityTrader").data("kendoNumericTextBox").value())*parseFloat($("#priceTrader").data("kendoNumericTextBox").value());
            $("#totalTraderTemplates").data("kendoNumericTextBox").value(totalTraderPrice);

            
        }
    });

    $("#totalTraderTemplates").focusout(function() {       
        if((parseFloat($("#price").data("kendoNumericTextBox").value()) > 0) && (parseFloat($(this).val()) > 0) && (parseFloat($("#priceTrader").data("kendoNumericTextBox").value()) > 0))
        {   

            var totalTraderQuantity = parseFloat($(this).val())/parseFloat($("#priceTrader").data("kendoNumericTextBox").value())
            $("#quantityTrader").data("kendoNumericTextBox").value(totalTraderQuantity);

            if(!$('#price').prop('disabled'))
            {
                var totalCustomerQuantity = totalTraderQuantity*parseFloat($("#price").data("kendoNumericTextBox").value());
            } else {
                var totalCustomerQuantity = totalTraderQuantity/parseFloat($("#priceTrader").data("kendoNumericTextBox").value());
            }
            $("#quantity").data("kendoNumericTextBox").value(totalCustomerQuantity);

            var totalCustomerPrice = totalCustomerQuantity*parseFloat($("#price").data("kendoNumericTextBox").value());
            $("#totalTemplates").data("kendoNumericTextBox").value(totalCustomerPrice);
            

            
        }
    });

    $("#totalTemplates").focusout(function() {
        if((parseFloat($("#price").data("kendoNumericTextBox").value()) > 0) && (parseFloat($(this).val()) > 0))
        {   
            var totalCustomerQuantity = parseFloat($(this).val())/parseFloat($("#price").data("kendoNumericTextBox").value());
            $("#quantity").data("kendoNumericTextBox").value(totalCustomerQuantity);

            if(!$('#price').prop('disabled'))
            {
                var totalTraderQuantity = totalCustomerQuantity/parseFloat($("#price").data("kendoNumericTextBox").value());
            } else {
                var totalTraderQuantity = totalCustomerQuantity*parseFloat($("#priceTrader").data("kendoNumericTextBox").value());
            }
            $("#quantityTrader").data("kendoNumericTextBox").value(totalTraderQuantity);

            var totalTraderPrice = totalTraderQuantity*parseFloat($("#priceTrader").data("kendoNumericTextBox").value());
            $("#totalTraderTemplates").data("kendoNumericTextBox").value(totalTraderPrice);

            
        }
    });

    $("#priceTrader").focusout(function() {
        if(parseFloat($(this).val()) > 0 && parseFloat($("#quantityTrader").data("kendoNumericTextBox").value()) > 0)
        {   
            var totalTrader = parseFloat($(this).val())*parseFloat($("#quantityTrader").data("kendoNumericTextBox").value());
            $("#totalTraderTemplates").data("kendoNumericTextBox").value(totalTrader);

            var totalCustomerQuantity = parseFloat($("#quantityTrader").data("kendoNumericTextBox").value())/parseFloat($(this).val());
            $("#quantity").data("kendoNumericTextBox").value(totalCustomerQuantity);

            var totalCustomerPrice = totalCustomerQuantity*parseFloat($("#price").data("kendoNumericTextBox").value());
            $("#totalTemplates").data("kendoNumericTextBox").value(totalCustomerPrice);
        }
    });

    $("#price").focusout(function() {
        if(parseFloat($(this).val()) > 0 && parseFloat($("#quantity").data("kendoNumericTextBox").value()) > 0)
        {   
            var totalCustomer = parseFloat($(this).val())*parseFloat($("#quantity").data("kendoNumericTextBox").value());
            $("#totalTemplates").data("kendoNumericTextBox").value(totalCustomer);

            var totalTraderQuantity = parseFloat($("#quantity").data("kendoNumericTextBox").value())/parseFloat($(this).val());
            $("#quantityTrader").data("kendoNumericTextBox").value(totalTraderQuantity);

            var totalTraderPrice = totalTraderQuantity*parseFloat($("#priceTrader").data("kendoNumericTextBox").value());
            $("#totalTraderTemplates").data("kendoNumericTextBox").value(totalTraderPrice);
        }
    });
});