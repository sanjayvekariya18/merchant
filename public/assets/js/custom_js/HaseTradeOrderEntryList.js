var otcEntryViewList = {
    OTC_ENRTY_LIST_VIEW: "otc-entry-detail-list",
    GRID_ID: "#grid",
    GRID_ROW: "row",
    JSON_DATA_TYPE: "json",
    DATA_TYPE: "GET",
    GRID_PAGE_SIZE: 20,
    FORWARD_SLASH_SEPARATORS: "/",
}
var token = $('input[name="_token"]').val();
var requestUrl = $("#requestUrl").val();
var merchantId = $('#login_merchant_id').val();

function tradeOrderStatusUpdate(order_id,statusvalue,statusId,status_field){
       if (confirm('Are you sure you want to '+ statusvalue + " " + '?')) {
            jQuery.ajax({
                type: "POST",
                url: "update_trade_order_status",
                data: {
                    order_id: order_id,
                    statusId: statusId,
                    status_field:status_field,
                    _token:token
                },
                cache: false,
                success: function (updatestatus) {
                    $(otcEntryViewList.GRID_ID).data("kendoGrid").dataSource.read();
                    $("#gridBroker").data("kendoGrid").dataSource.read();
                }
            });
        }
        else
            return;
}
function tradeOrderEdit()
{
    var customerGridObject = $(otcEntryViewList.GRID_ID).data("kendoGrid");
    var selectedItem = customerGridObject.dataItem(customerGridObject.select());
    if(selectedItem)
    {
        $('.content .preloader').show();
        $('.content .preloader img').show();
        jQuery("#order_id").val(selectedItem.order_id)
        $.ajax({
            type:'POST',
            dataType:"json",
            data:{_token:token,order_id:selectedItem.order_id},
            url : requestUrl+"/customer_order_details",
            error:function(xhr,status,error) {
                console.log(error);
            },
            success:function(customerOrderReponse,status,xhr) {
                if(parseFloat(customerOrderReponse.asset_from_price) > 0)
                    jQuery("#priceTrader").data("kendoNumericTextBox").value(customerOrderReponse.asset_from_price);
                else
                    jQuery("#priceTrader").data("kendoNumericTextBox").value("");

                if(parseFloat(customerOrderReponse.asset_into_price) > 0)
                    jQuery("#price").data("kendoNumericTextBox").value(customerOrderReponse.asset_into_price);
                else
                    jQuery("#price").data("kendoNumericTextBox").value("");

                if(parseFloat(customerOrderReponse.asset_from_quantity) > 0)
                {
                    jQuery("#quantityTrader").data("kendoNumericTextBox").value(customerOrderReponse.asset_from_quantity);
                    jQuery("#totalTraderTemplates").data("kendoNumericTextBox").value(customerOrderReponse.asset_from_quantity*customerOrderReponse.asset_from_price);
                }
                else
                {
                    jQuery("#quantityTrader").data("kendoNumericTextBox").value("");
                    jQuery("#totalTraderTemplates").data("kendoNumericTextBox").value("");
                }
                if(parseFloat(customerOrderReponse.asset_into_quantity) > 0)
                {
                    jQuery("#quantity").data("kendoNumericTextBox").value(customerOrderReponse.asset_into_quantity);
                    jQuery("#totalTemplates").data("kendoNumericTextBox").value(customerOrderReponse.asset_into_quantity*customerOrderReponse.asset_into_price);
                }
                else
                {
                    jQuery("#quantity").data("kendoNumericTextBox").value("");
                    jQuery("#totalTemplates").data("kendoNumericTextBox").value("");
                }


                jQuery("#exchangeList").data("kendoDropDownList").value(customerOrderReponse.exchange_id);
                jQuery("#brokerDropDownList").data("kendoDropDownList").value(customerOrderReponse.merchant_id);
                $.ajax({
                    type:'POST',
                    data:{_token:token,exchange_id:customerOrderReponse.exchange_id},
                    dataType:"json",
                    url : requestUrl+"/asset_settlement_details",
                    error:function(xhr,status,error) {
                        console.log(error);
                    },
                    success:function(assetSettlementResponse,status,xhr) {
                        var settlementList = jQuery("#settlementListAutoComple").data("kendoComboBox");
                        settlementList.value("");
                        settlementList.dataSource.data(assetSettlementResponse);
                        if(customerOrderReponse.asset_settlement_id == 0)
                        {
                            settlementList.value(1);
                        } else {
                            settlementList.value(customerOrderReponse.asset_settlement_id);    
                        }
                    }
                })
                $.ajax({
                    type:'POST',
                    data:{_token:token,merchant_id:customerOrderReponse.merchant_id},
                    dataType:"json",
                    url : requestUrl+"/customer_list",
                    error:function(xhr,status,error) {
                        console.log(error);
                    },
                    success:function(customerListResponse,status,xhr) {
                        var customerAcccountList = jQuery("#customerAutoComplete").data("kendoComboBox");
                        customerAcccountList.value("");
                        customerAcccountList.dataSource.data(customerListResponse);
                        customerAcccountList.value(customerOrderReponse.customer_id);
                    }
                });

                $.ajax({
                    type:'POST',
                    dataType : "json",
                    data:{_token:token,merchant_id:customerOrderReponse.merchant_id,customer_id:customerOrderReponse.customer_id},
                    url : requestUrl+"/trade_basket_details",
                    error:function(xhr,status,error) {
                        console.log(error);
                    },
                    success:function(basketDetailsResponse,status,xhr) {
                        var baskerDetailsList = $("#orderBasketConfirm").data("kendoComboBox");
                        baskerDetailsList.value("");
                        baskerDetailsList.dataSource.data(basketDetailsResponse);
                        baskerDetailsList.value(customerOrderReponse.basket_id);
                    }
                });

                $.ajax({
                    type:'GET',
                    dataType:"json",
                    url : requestUrl+"/fx_all_rates_list",
                    error:function(xhr,status,error) {
                        console.log(error);
                    },
                    success:function(assetsResponse,status,xhr) {
                        var assetMultiSelectList = jQuery("#assetListFrom").data("kendoMultiSelect");
                        assetMultiSelectList.value("");
                        assetMultiSelectList.dataSource.data(assetsResponse);
                        var assetListFromObject = $("#assetListFrom").data("kendoMultiSelect");
                        assetListFromObject.value([customerOrderReponse.asset_from_id, customerOrderReponse.asset_into_id]);
                        var multiselectitems = assetListFromObject.dataItems();
                        jQuery("#assetTraderTemplates").html(multiselectitems[0].identity_code);
                        jQuery("#assetTemplates").html(multiselectitems[1].identity_code);


                        $.ajax({
                            type:'POST',
                            data:{_token:token,asset_id:customerOrderReponse.asset_from_id},
                            dataType:"json",
                            url : requestUrl+"/asset_type_list",
                            error:function(xhr,status,error) {
                                console.log(error);
                            },
                            success:function(assetTypeListResponse,status,xhr) {
                                if(assetTypeListResponse.asset_type_code == 'fiat')
                                {
                                    jQuery("#sideTemplates").html("Buy")
                                    jQuery("#sideTraderTemplates").html("Sell")
                                } else {
                                    jQuery("#sideTemplates").html("Sell")
                                    jQuery("#sideTraderTemplates").html("Buy")
                                }
                            }
                        });
                        $.ajax({
                            type:'POST',
                            data:{_token:token,asset_id:customerOrderReponse.asset_from_id,merchant_id:customerOrderReponse.merchant_id},
                            dataType:"json",
                            url : requestUrl+"/merchant_account_list",
                            error:function(xhr,status,error) {
                                console.log(error);
                            },
                            success:function(brokerAccountResponse,status,xhr) {
                                var brokerAcccountList = jQuery("#brokerAccountListAutoComple").data("kendoComboBox");
                                brokerAcccountList.value("");
                                brokerAcccountList.dataSource.data(brokerAccountResponse);
                                brokerAcccountList.value(customerOrderReponse.merchant_account_id);
                                jQuery("#accountTraderTemplates").html(brokerAcccountList.text());
                                $.ajax({
                                    type:'POST',
                                    data:{_token:token,account_id:customerOrderReponse.merchant_account_id},
                                    dataType:"json",
                                    url : requestUrl+"/account_quantity_Data",
                                    error:function(xhr,status,error) {
                                        console.log(error);
                                    },
                                    success:function(accountQuantityData,status,xhr) {
                                        if(accountQuantityData && accountQuantityData.length != 0)
                                        {
                                            $("#AmountTraderTemplates").html(accountQuantityData.asset_quantity);
                                            $("#traderTradingfeeTemplates").html(accountQuantityData.fee_percentage);
                                            
                                        }
                                    }
                                });
                                $.ajax({
                                    type:'POST',
                                    data:{_token:token,asset_id:customerOrderReponse.asset_into_id,customer_id:customerOrderReponse.customer_id},
                                    dataType:"json",
                                    url : requestUrl+"/customer_account_list",
                                    error:function(xhr,status,error) {
                                        console.log(error);
                                    },
                                    success:function(customerAccountResponse,status,xhr) {
                                        var customerAcccountList = jQuery("#customerAccountListAutoComple").data("kendoComboBox");
                                        customerAcccountList.value("");
                                        customerAcccountList.dataSource.data(customerAccountResponse);
                                        customerAcccountList.value(customerOrderReponse.customer_account_id);
                                        jQuery("#accountTemplates").html(customerAcccountList.text());
                                        $.ajax({
                                            type:'POST',
                                            data:{_token:token,account_id:customerOrderReponse.customer_account_id},
                                            dataType:"json",
                                            url : requestUrl+"/account_quantity_Data",
                                            error:function(xhr,status,error) {
                                                console.log(error);
                                            },
                                            success:function(accountQuantityData,status,xhr) {
                                                if(accountQuantityData && accountQuantityData.length != 0)
                                                {
                                                    $("#amountTemplates").html(accountQuantityData.asset_quantity);
                                                    $("#tradingfeeTemplates").html(accountQuantityData.fee_percentage);
                                                    $('.content .preloader').hide();
                                                    $('.content .preloader img').hide();
                                                }
                                            }
                                        });
                                        
                                    }
                                });
                            }
                        });
                    }
                });
            }
        });
    } else {
        alert("please Select row for update");
    }
    
}
function customerList() {
    var otcListGridElement = $("#customergrid")
        .kendoGrid({
            dataSource: new kendo.data.DataSource({
                data: [{
                    employee: "Asset Into",
                }]
            }),
            columns: [{
                field: "employee",
                title: " ",
                width: 100
            }, {
                field: "customer_trading_fee",
                title: "",
                template: kendo.template(jQuery('#tradingfeeTemplate').html()),
                width: 100,
                hidden:true
            },{
                field: "",
                title: "",
                template: kendo.template(jQuery('#amountTemplate').html()),
                width: 100
            },{
                field: "",
                title: "",
                template: kendo.template(jQuery('#accountTemplate').html()),
                width: 100
            }, {
                field: "",
                title: "",
                template: kendo.template(jQuery('#sideTemplate').html()),
                width: 50
            }, {
                field: "",
                title: "",
                template: kendo.template(jQuery('#toatalTemplate').html()),
                width: 140
            }, {
                field: "",
                title: "",
                template: kendo.template(jQuery('#quantityTemplate').html()),
                width: 140
            }, {
                field: "",
                title: "",
                template: kendo.template(jQuery('#priceTemplate').html()),
                width: 120
            }, {
                field: "",
                title: "",
                template: kendo.template(jQuery('#assetTemplate').html()),
                width: 100
            }],
        });
};

function traderList() {
    var otcListGridElement = $("#tradergrid")
        .kendoGrid({
            dataSource: new kendo.data.DataSource({
                data: [{
                    employee: "Asset From",
                }]
            }),
            columns: [{
                field: "employee",
                title: " ",
                width: 100,
                headerAttributes: {
                    "class": "k-grid-header k-header",
                    style: "text-align: right; font-size: 14px; height:1% padding: .5em .6em .4em .6em"
                }
            }, {
                field: "trader_trading_fee",
                title: "",
                template: kendo.template(jQuery('#traderTradingfeeTemplate').html()),
                width: 100,
                hidden:true
            },{
                field: "",
                title: "Amount",
                template: kendo.template(jQuery('#AmountTraderTemplate').html()),
                width: 100
            },{
                field: "",
                title: "Account",
                template: kendo.template(jQuery('#accountTraderTemplate').html()),
                width: 100
            }, {
                field: "",
                title: "Side",
                template: kendo.template(jQuery('#sideTraderTemplate').html()),
                width: 50
            }, {
                field: "",
                title: "Total",
                template: kendo.template(jQuery('#toatalTraderTemplate').html()),
                width: 140
            }, {
                field: "",
                title: "Quantity",
                template: kendo.template(jQuery('#quantityTraderTemplate').html()),
                width: 140
            }, {
                field: "",
                title: "Price",
                template: kendo.template(jQuery('#priceTraderTemplate').html()),
                width: 120
            }, {
                field: "",
                title: "Asset",
                template: kendo.template(jQuery('#assetTraderTemplate').html()),
                width: 100
            }],
        });
};
$("#searchValueGrid").keypress(function (e) {
    if(e.which == 13) {
        var searchValue = $('#searchValueGrid').val();
        $(otcEntryViewList.GRID_ID).data("kendoGrid").dataSource.filter({
        logic  : "or",
        filters: [{
                field   : "customer_name",
                operator: "contains",
                value   : searchValue
            },
            {
                field   : "customer_account_name",
                operator: "contains",
                value   : searchValue
            },
            {
                field   : "merchant_name",
                operator: "contains",
                value   : searchValue
            },
            {
                field   : "broker_account_name",
                operator: "contains",
                value   : searchValue
            }]
            
        });
        $("#gridBroker").data("kendoGrid").dataSource.filter({
        logic  : "or",
        filters: [{
                field   : "customer_name",
                operator: "contains",
                value   : searchValue
            },
            {
                field   : "customer_account_name",
                operator: "contains",
                value   : searchValue
            },
            {
                field   : "merchant_name",
                operator: "contains",
                value   : searchValue
            },
            {
                field   : "broker_account_name",
                operator: "contains",
                value   : searchValue
            }]
            
        });
    }
});
function tradeOrderEntryList() {
    var tabstrip = $("#tabstrip").kendoTabStrip().data("kendoTabStrip");
    var otcListGridElement = $(otcEntryViewList.GRID_ID)
        .kendoGrid({
            dataSource: {
                serverPaging: true,
                pageSize: otcEntryViewList.GRID_PAGE_SIZE,
                transport: {
                    read: {
                        url: otcEntryViewList.OTC_ENRTY_LIST_VIEW + "/" + 1,
                        dataType: otcEntryViewList.JSON_DATA_TYPE,
                        type: otcEntryViewList.DATA_TYPE
                    }
                },
                schema: {
                    total:'total',
                    data:'trade_order',
                    model: {
                        order_id:'order_id'
                    }
                }
            },
            pageable: {
                refresh: true,
                pageSizes: true
            },
            scrollable: true,
            selectable: otcEntryViewList.GRID_ROW,
            autoSync: true,
            sortable: true,
            reorderable: true,
            serverFiltering: true,
            groupable: true,
            resizable: true,
            editable: false,
            toolbar: kendo.template(jQuery("#updateTemplate").html()),
            dataBound: function(e) {
                var data = this.dataSource.data();
                $.each(data, function (i, row) {
                var status_operations_type_color=row.get("status_operations_type_color");
                $('tr[data-uid="' + row.uid + '"] td:nth-child(1)').css("background-color", status_operations_type_color);
                var status_fiat_type_color=row.get("status_fiat_type_color");
                    $('tr[data-uid="' + row.uid + '"] td:nth-child(2)').css("background-color", status_fiat_type_color); 
                var status_crypto_type_color=row.get("status_crypto_type_color");
                    $('tr[data-uid="' + row.uid + '"] td:nth-child(3)').css("background-color", status_crypto_type_color);
                });            
            var data = this.dataSource.data();
                $.each(data, function (i, row) {
                var status_operations_type_name=row.get("status_operations_type_name");
                var status_fiat_type_name=row.get("status_fiat_type_name");
                var status_crypto_type_name=row.get("status_crypto_type_name");
                 if (status_operations_type_name == 'Complete' && status_fiat_type_name == 'Complete'&& status_crypto_type_name == 'Complete' ) {
                    var element = $('tr[data-uid="' + row.uid + '"] ');
                    element.css("background-color","green");
                    element.css("color",'black');
                }
              });
            }, 

            columns: [{
                field: "status_operations_type_name",
                title: "Operation",
                template: kendo.template(jQuery('#operationtemplate').html()),
                width: 230
            }, {
                field: "status_fiat_type_name",
                title: "Fiat",
                template: kendo.template(jQuery('#actiontemplate').html()),
                width: 120
            }, {
                field: "status_crypto_type_name",
                title: "Crypto",
                template: kendo.template(jQuery('#cryptotemplate').html()),
                width: 120
            },{
                field: "order_id",
                title: "Trade Order Id",
                width: 130
            },
            {
                field: "transaction_internal_ref",
                title: "Internal Ref",
                width: 300
            },{
                field: "start_date",
                title: "Start Date",
                width: 120
            }, {
                field: "start_time",
                title: "Start Time",
                width: 100
            },
            {
                field: "customer_name",
                title: "Customer",
                width: 150
            }, {
                field: "customer_account_code_long",
                editable: true,
                title: "Account",
                width: 150
            }, {
                field: "side_type_name",
                editable: false,
                title: "Side",
                width: 50
            }, {
                field: "asset_from_price",
                editable: true,
                title: "From Price",
                format: "{0:n8}",
                width: 120
            }, {
                field: "asset_from_quantity",
                editable: true,
                title: "From Quantity",
                format: "{0:n8}",
                width: 130
            }, {
                field: "asset_from_code",
                title: "From Asset",
                width: 100
            }, {
                field: "asset_into_price",
                editable: true,
                title: "Into Price",
                format: "{0:n8}",
                width: 120
            }, {
                field: "asset_into_quantity",
                editable: true,
                title: "Into Quantity",
                format: "{0:n8}",
                width: 130
            }, {
                field: "asset_into_code",
                title: "Into Asset",
                width: 100
            }, {
                field: "fee_amount",
                editable: true,
                title: "Fee Amount",
                width: 150
            }, {
                field: "fee_asset_code",
                editable: true,
                title: "Fee Asset",
                width: 100
            },],
        });
};

function tradeBrokerOrderEntryList() {
    var tabstrip = $("#tabstrip").kendoTabStrip().data("kendoTabStrip");
    var otcListGridElement = $("#gridBroker")
        .kendoGrid({
            dataSource: {
                serverPaging: true,
                pageSize: otcEntryViewList.GRID_PAGE_SIZE,
                transport: {
                    read: {
                        url: otcEntryViewList.OTC_ENRTY_LIST_VIEW + "/" + 2,
                        dataType: otcEntryViewList.JSON_DATA_TYPE,
                        type: otcEntryViewList.DATA_TYPE
                    }
                },
                schema: {
                    total:'total',
                    data:'trade_order',
                    model: {
                        order_id:'order_id'
                    }
                },
                serverFiltering: true,
            },
            pageable: {
                refresh: true,
                pageSizes: true
            },
            selectable: otcEntryViewList.GRID_ROW,
            pageable: {
                refresh: true,
                pageSizes: true
            },
            scrollable: true,
            selectable:true,
            autoSync: true,
            sortable: true,
            reorderable: true,
            serverFiltering: true,
            groupable: true,
            resizable: true,
            editable: false,
            dataBound: function(e) {
                var data = this.dataSource.data();
                    $.each(data, function (i, row) {
                    var status_operations_type_color=row.get("status_operations_type_color");
                    $('tr[data-uid="' + row.uid + '"] td:nth-child(1)').css("background-color", status_operations_type_color);
                    var status_fiat_type_color=row.get("status_fiat_type_color");
                        $('tr[data-uid="' + row.uid + '"] td:nth-child(2)').css("background-color", status_fiat_type_color); 
                    var status_crypto_type_color=row.get("status_crypto_type_color");
                        $('tr[data-uid="' + row.uid + '"] td:nth-child(3)').css("background-color", status_crypto_type_color);
                });            
                var data = this.dataSource.data();
                    $.each(data, function (i, row) {
                    var status_operations_type_name=row.get("status_operations_type_name");
                    var status_fiat_type_name=row.get("status_fiat_type_name");
                    var status_crypto_type_name=row.get("status_crypto_type_name");
                     if (status_operations_type_name == 'Complete' && status_fiat_type_name == 'Complete'&& status_crypto_type_name == 'Complete' ) {
                        var element = $('tr[data-uid="' + row.uid + '"] ');
                        element.css("background-color","green");
                        element.css("color",'black');
                    }
              });
            }, 

            columns: [{
                field: "order_id",
                title: "Trade Order Id",
                width: 130
            },
            {
                field: "transaction_internal_ref",
                title: "Internal Ref",
                width: 300
            }, 
            {
                field: "start_timezone",
                title: "Start Timezone",
                width: 120
            }, {
                field: "start_date",
                title: "Start Date",
                width: 100
            }, {
                field: "start_time",
                title: "Start Time",
                width: 100
            },
            {
                field: "customer_name",
                title: "Customer",
                width: 150
            },{
                field: "broker_account_code_long",
                editable: true,
                title: "Account",
                width: 150
            }, {
                field: "side_type_name",
                editable: false,
                title: "Side",
                width: 50
            }, {
                field: "asset_from_price",
                editable: true,
                title: "From Price",
                format: "{0:n8}",
                width: 120
            }, {
                field: "asset_from_quantity",
                editable: true,
                title: "From Quantity",
                format: "{0:n8}",
                width: 130
            }, {
                field: "asset_from_code",
                title: "From Asset",
                width: 100
            }, {
                field: "asset_into_price",
                editable: true,
                title: "Into Price",
                format: "{0:n8}",
                width: 120
            }, {
                field: "asset_into_quantity",
                editable: true,
                title: "Into Quantity",
                format: "{0:n8}",
                width: 130
            }, {
                field: "asset_into_code",
                title: "Into Asset",
                width: 100
            }, {
                field: "fee_amount",
                editable: true,
                title: "Fee Amount",
                width: 150
            }, {
                field: "fee_asset_code",
                editable: true,
                title: "Fee Asset",
                width: 100
            }],
            change: function() {
                var row = this.select();        
                var gview = $("#gridBroker").data("kendoGrid");
                var selectedItem = gview.dataItem(gview.select());
            }
        });
};