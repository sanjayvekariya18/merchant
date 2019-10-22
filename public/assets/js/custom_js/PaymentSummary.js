$(document).ready(function (){
    
    var token = $('input[name="_token"]').val();
    var requestUrl = $("#requestUrl").val();

    var selectedPullCityId = 0;
    var selectedPullMechantAccountId = 0;
    var selectedPullCustomerId = 0;
    var selectedPullCustomerAccountId = 0;
    var selectedPullLocationId = 0;

    $("#location_id").kendoComboBox({
        placeholder: "Select Location",
        dataTextField: "location_name",
        dataValueField: "list_id",
        filter: "contains",
        height: 400,
        
    }).data("kendoComboBox");


    $("#location_city_id").kendoComboBox({
        placeholder: "Select Location",
        dataTextField: "city_name",
        dataValueField: "city_id",
        filter: "contains",
        height: 400,
        change: function (e) {
            if($('#merchant_id').val() != "" && $('#location_city_id').val() != ""){
                
                var merchant_id = $('#merchant_id').val();
                var location_city_id = $('#location_city_id').val();

                /*GET CUSTOMER ACCOUNT LIST NAME*/
                var location_id = $("#location_id").data("kendoComboBox");
                var postalDataSource = new kendo.data.DataSource({
                    transport : {   
                        read : {
                                url: requestUrl+"/getLocationPostalList",
                                dataType: "json",
                                type: "post",
                                data:{_token:token,'merchant_id':merchant_id,'location_city_id':location_city_id}
                            }
                        }
                    });

                location_id.setDataSource(postalDataSource);
                if(selectedPullLocationId){
                    location_id.value(selectedPullLocationId);  
                }else{
                    location_id.value("");
                }
                location_id.enable(true);

            }else{
                    selectedPullLocationId = 0;
                }
            }
        
    }).data("kendoComboBox");

    $("#merchant_account_id").kendoComboBox({
        placeholder: "Select Merchant Account",
        dataTextField: "account_code_long",
        dataValueField: "account_id",
        filter: "contains",
        height: 400,
        
    }).data("kendoComboBox");

    $("#customer_id").kendoComboBox({
        placeholder: "Select Customer",
        dataTextField: "customer_name",
        dataValueField: "customer_id",
        filter: "contains",
        height: 400,
        change: function (e) {
            if($('#merchant_id').val() != "" && $('#customer_id').val() != ""){
                
                var customer_account_id = $("#customer_account_id").data("kendoComboBox");
                var merchant_id = $('#merchant_id').val();
                var customer_id = $('#customer_id').val();

                /*GET CUSTOMER ACCOUNT LIST NAME*/

                var customerAccountDataSource = new kendo.data.DataSource({
                    transport : {   
                        read : {
                                url: requestUrl+"/customerAccountList",
                                dataType: "json",
                                type: "post",
                                data:{_token:token,'merchant_id':merchant_id,'customer_id':customer_id}
                            }
                        }
                    });

                customer_account_id.setDataSource(customerAccountDataSource);
                if(selectedPullCustomerAccountId){
                    customer_account_id.value(selectedPullCityId);  
                }else{
                    customer_account_id.value("");
                }
                customer_account_id.enable(true);

            }else{
                    selectedPullCustomerAccountId = 0;
                }
            }
    }).data("kendoComboBox");

    $("#customer_account_id").kendoComboBox({
        placeholder: "Select Customer Account",
        dataTextField: "account_code_long",
        dataValueField: "account_id",
        filter: "contains",
        height: 400,
        
    }).data("kendoComboBox");

    $("#payment_type_id").kendoComboBox({
        placeholder: "Select Payment Type",
        dataTextField: "type_name",
        dataValueField: "type_id",
        filter: "contains",
        height: 400,
        dataSource: {
            transport : {   
                read : {
                    dataType : "json",
                    url : requestUrl+"/getPaymentTypes",
                    type : "GET",
                }
            }
        }
        
    }).data("kendoComboBox");

    $("#asset_id").kendoComboBox({
        placeholder: "Select Asset",
        dataTextField: "asset_code",
        dataValueField: "asset_id",
        filter: "contains",
        height: 400,
        dataSource: {
            transport : {   
                read : {
                    dataType : "json",
                    url : requestUrl+"/getAssets",
                    type : "GET",
                }
            }
        }
        
    }).data("kendoComboBox");

    $("#fee_asset_id").kendoComboBox({
        placeholder: "Select Fee Asset",
        dataTextField: "asset_code",
        dataValueField: "asset_id",
        filter: "contains",
        height: 400,
        dataSource: {
            transport : {   
                read : {
                    dataType : "json",
                    url : requestUrl+"/getAssets",
                    type : "GET",
                }
            }
        }
        
    }).data("kendoComboBox");

    $("#merchant_id").kendoComboBox({
        placeholder: "Select Person",
        dataTextField: "person_name",
        dataValueField: "person_id",
        filter: "contains",
        height: 400,
        dataSource: {
            transport : {   
                read : {
                    dataType : "json",
                    url : requestUrl+"/getPersons",
                    type : "GET",
                }
            }
        },
        change: function (e) {
            console.log($('#merchant_id').val());

            if($('#merchant_id').val() != ""){
                var location_city_id = $("#location_city_id").data("kendoComboBox");
                var merchant_account_id = $("#merchant_account_id").data("kendoComboBox");
                var customer_id = $("#customer_id").data("kendoComboBox");

                var merchant_id = $('#merchant_id').val();

                /*GET MERCHANT CITY NAMES*/

                var cityDataSource = new kendo.data.DataSource({
                    transport : {   
                        read : {
                                url: requestUrl+"/getMerchantCityList",
                                dataType: "json",
                                type: "post",
                                data:{_token:token,'merchant_id':merchant_id}
                            }
                        }
                    });

                location_city_id.setDataSource(cityDataSource);
                if(selectedPullCityId){
                    location_city_id.value(selectedPullCityId);  
                }else{
                    location_city_id.value("");
                }
                location_city_id.enable(true);

                /*GET MERCHANT ACCOUNT LIST NAME*/

                var merchantAccountDataSource = new kendo.data.DataSource({
                    transport : {   
                        read : {
                                url: requestUrl+"/merchantAccountList",
                                dataType: "json",
                                type: "post",
                                data:{_token:token,'merchant_id':merchant_id}
                            }
                        }
                    });

                merchant_account_id.setDataSource(merchantAccountDataSource);
                if(selectedPullMechantAccountId){
                    merchant_account_id.value(selectedPullCityId);  
                }else{
                    merchant_account_id.value("");
                }
                merchant_account_id.enable(true);

                /*GET MERCHANT CUSTOMER LIST NAME*/

                var merchantCustomerDataSource = new kendo.data.DataSource({
                    transport : {   
                        read : {
                                url: requestUrl+"/getMerchantCustomers",
                                dataType: "json",
                                type: "post",
                                data:{_token:token,'merchant_id':merchant_id}
                            }
                        }
                    });

                customer_id.setDataSource(merchantCustomerDataSource);
                if(selectedPullCustomerId){
                    customer_id.value(selectedPullCityId);  
                }else{
                    customer_id.value("");
                }
                customer_id.enable(true);


            }else{
                selectedPullCityId = 0;
                selectedPullMechantAccountId = 0;
                selectedPullCustomerId = 0;
            }
        }
    }).data("kendoComboBox");

    var validator = $("#paymentSummaryForm").kendoValidator({
        rules: {
            hasMerchant: function (input) {
                if(input.is("[id=merchant_id]")){
                    var ms = input.data("kendoComboBox");       
                    if(ms.value().length === 0){
                        return false;
                    }
                } 
                return true;
            },
            hasLocation: function (input) {
                if(input.is("[id=location_city_id]")){
                    var ms = input.data("kendoComboBox");       
                    if(ms.value().length === 0){
                        return false;
                    }
                } 
                return true;
            },            
            hasPaymentType: function (input) {
                if(input.is("[id=payment_type_id]")){
                    var ms = input.data("kendoComboBox");       
                    if(ms.value().length === 0){
                        return false;
                    }
                } 
                return true;
            },            
            hasAsset: function (input) {
                if(input.is("[id=asset_id]")){
                    var ms = input.data("kendoComboBox");       
                    if(ms.value().length === 0){
                        return false;
                    }
                } 
                return true;
            },
            hasFeeAsset: function (input) {
                if(input.is("[id=fee_asset_id]")){
                    var ms = input.data("kendoComboBox");       
                    if(ms.value().length === 0){
                        return false;
                    }
                } 
                return true;
            },
        },
        messages: { 
            hasMerchant: "Merchant Required",
            hasLocation: "Location Required",
            hasPaymentType: "Payment Type Required",
            hasAsset: "Asset Required",
            hasFeeAsset: "Fee Asset Required"
        }
    }).data("kendoValidator");

    $('#submitBtn').click(function(){
        if (validator.validate()) {
            $.ajax({
                type: 'POST',
                data:$('#paymentSummaryForm').serialize(),
                url: requestUrl+"/createPaymentSummary",
                success: function (eventData) {
                    $("#paymentSummaryForm").trigger('reset');
                    $("#paymentSummaryActiveGrid").data("kendoGrid").dataSource.read();
                }
            });
        }
    });
    
    function onLedgerSave(data) {
        var ledger_id = data.container.closest("tr").find("td:eq(0)").text();
        var key="";
        var value=0;
                        
        if(data.values.vendor_name) {
            key = "vendor_name";
            value = data.values.vendor_name;
        }
        else if(data.values.payment_description) {
            key = "payment_description";
            value = data.values.payment_description;
        }
        else if(data.values.payment_name) {
            key = "payment_name";
            value = data.values.payment_name;
        }
        else if(data.values.payment_notes) {
            key = "payment_notes";
            value = data.values.payment_notes;
        }
        else if(data.values.transaction_address) {
            key = "transaction_address";
            value = data.values.transaction_address;
        }
        else if(data.values.transaction_address_url) {
            key = "transaction_address_url";
            value = data.values.transaction_address_url;
        }
        else if(data.values.transaction_internal_ref) {
            key = "transaction_internal_ref";
            value = data.values.transaction_internal_ref;
        }
        else if(data.values.transaction_root) {
            key = "transaction_root";
            value = data.values.transaction_root;
        }
        else if(data.values.ledger_hash) {
            key = "ledger_hash";
            value = data.values.ledger_hash;
        }
        else {
            return false;
        }
        $.ajax({
            type: 'POST',
            data:{
                ledger_id:ledger_id,
                key:key,
                value:value,
                _token:token
            },
            url: requestUrl+"/updatePaymentLedger",
            success: function (eventData) {
                data.sender.element.closest('[data-role="grid"]').data("kendoGrid").dataSource.read();
            }
        });
    }
    function detailInit(e) {
        var detailRow = e.detailRow;
        detailRow.find(".paymentLedgerGrid").kendoGrid({
            dataSource: {
                serverPaging: false,
                pageSize: 5,
                transport: {
                    read: {
                        url: requestUrl+"/getAllPaymentLedger",
                        data: {
                            summaryId: e.data.summary_id,
                            _token:token
                        },
                        dataType: "json",
                        type: "POST",
                    }
                },
                schema: {
                model: {
                        ledger_id:'ledger_id',
                    },
                },
                serverFiltering: false,
                pageSize: 5,
            },
            pageable: {
                refresh: true,
                pageSizes: true
            },
            scrollable: true,
            autoSync: true,
            sortable: true,
            reorderable: true,
            serverFiltering: false,
            groupable: true,
            resizable: true,
            editable: true,
            selectable : "row",
            save:onLedgerSave,
            edit: function(e){
                var columnNotEditableIndex = [1,2,3,4,5,6,8,12,13,14,15,16,17];
                if($.inArray(parseInt(e.container.index()),columnNotEditableIndex) != -1){
                    this.closeCell();
                }
            },
            columns: [{
                field: "ledger_id",
                title: "Ledger#",
                hidden:true,
            },{
                field: "summary_id",
                title: "Summary Id",
                width:100
            },{
                field: "city_name",
                title: "Location Name",
                width:100
            },{
                field: "group_name",
                title: "Group Name",
                width:100
            },{
                field: "staff_name",
                title: "Staff Name",
                width:100
            },{
                field: "merchant_account_name",
                title: "Merchant Account Name",
                width:100
            },{
                field: "customer_account_name",
                title: "Customer Account Name",
                width:100
            },{
                field: "vendor_name",
                title: "Vendor Name",
                width:100
            },{
                field: "payment_type_name",
                title: "Payment Type",
                width:100
            },{
                field: "payment_description",
                title: "Payment Description",
                width:100
            },{
                field: "payment_name",
                title: "Payment Name",
                width:100
            },{
                field: "payment_notes",
                title: "Payment Notes",
                width:100
            },{
                field: "payment_quantity",
                title: "Quantity",
                width:100
            },{
                field: "payment_unit_price",
                title: "Unit Price",
                width:100
            },{
                field: "payment_amount",
                title: "Amount",
                width:100
            },{
                field: "payment_asset_name",
                title: "Asset Name",
                width:100
            },{
                field: "payment_fee_id",
                title: "Payment Fee Id",
                width:100
            },{
                field: "transaction_type_name",
                title: "Transaction Type",
                width:100
            },{
                field: "transaction_address",
                title: "Transaction Address",
                width: 100
            }, {
                field: "transaction_address_url",
                title: "Transaction Address Url",
                width: 100
            }, {
                field: "transaction_internal_ref",
                title: "Transaction Internal",
                width: 100
            }, {
                field: "transaction_root",
                title: "Transaction Root",
                width: 100
            }, {
                field: "ledger_hash",
                title: "Ledger Hash",
                width: 100
            }],
        });
    }
    var paymentSummaryActiveGrid = jQuery("#paymentSummaryActiveGrid").kendoGrid({
        dataSource: {
            serverPaging: true,
            pageSize: 20,
            group: {
               field: "group_name",
               dir: "asc"
            },
            transport : {
                read : {
                    data:{_token:token},
                    url: requestUrl+"/getPaymentSummaryList",
                    dataType: "json",
                    type: "POST"
                }
            },
            schema: {
                total:'total',
                data:'payment_list',
                model: {
                    fund_id:'summary_id',
                    fields: {
                        priority: {
                            type: "number",
                            validation: { required: true,min: 0,max:99 }
                        },
                        status: {
                            defaultValue: { value: 1, text: "Accept"}
                        }
                    }
                },
            },
            serverFiltering: true,
        },
        pageable: {
            refresh: true,
            pageSizes: true
        },
        groupable: false,
        scrollable: true,
        autoSync: true,
        sortable: true,
        reorderable: true,
        serverFiltering: true,
        groupable: true,
        resizable: true,
        editable: false,
        detailTemplate: kendo.template($("#templateDetail").html()),
        detailInit: detailInit,
        dataBound : function(e) {
            var data = this.dataSource.data();
            $.each(data, function(i, row) {
                var ledgerCount = row.ledger_count;
                if (ledgerCount != '') {
                    $('tr[data-uid="' + row.uid + '"] td:nth-child(2)').css("background-color", "lightblue");
                }
            });
            var dataSource = this.dataSource;
            this.element.find('tr.k-master-row').each(function() {
                var row = $(this);
                var data = dataSource.getByUid(row.data('uid'));
                if (data.ledger_count == 0) {
                    row.find('.k-hierarchy-cell a').remove();
                }
            });
        },
        /*edit: function(e){
            var columnNotEditableIndex = [0,1,2,3,4,5,6,7,8,9,10,11];
            if($.inArray(parseInt(e.container.index()),columnNotEditableIndex) != -1){
                this.closeCell(); 
            }
        },*/
        columns: [
        {
            field: "summary_id",
            title: "Summary Id",
            hidden:true,
        },{
            field: "group_name",
            title: "Merchant Type",
            hidden:true,
        }, {
            field: "merchant_name",
            title: "Merchant Name",
            width:100
        }, {
            field: "location_city",
            title: "Location Name",
            width:100
        }, {
            field: "merchant_account",
            title: "Merchant Account",
            width:110
        }, {
            field: "customer_account",
            title: "Customer Account",
            width:110
        }, {
            field: "payment_type",
            title: "Payment Type",
            width:90
        }, {
            field: "payment_name",
            title: "Payment Name",
            width:95
        },{
            field: "payment_price",
            title: "Payment Price",
            width:95
        },{
            field: "payment_quantity",
            title: "Payment Quantity",
            width:120
        },{
            field: "payment_asset_name",
            title: "Payment Asset",
            width:100
        },{
            field: "fee_amount",
            title: "Fee Amount",
            width:90
        },{
            field: "fee_asset_name",
            title: "Fee Asset",
            width:100
        }
        ],
    });

});