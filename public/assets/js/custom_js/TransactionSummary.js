$(document).ready(function() {
    var requestUrl = $('#requestUrl').val();
    function detailInit(transactionSummaryElement) {
        jQuery("<div/>").appendTo(transactionSummaryElement.detailCell).kendoGrid({
            dataSource: {
                serverPaging: true,
                pageSize: 20,
                transport: {
                    read: {
                        url: "ledger_list/"+transactionSummaryElement.data.summary_id,
                        dataType: "json",
                        type: "GET",
                    },
                },
                batch: false,
                schema: {                    
                    model: {
                        ledger_id:'ledger_id',
                        fields: {
                            group_name: {
                                editable: false
                            },
                            staff_name: {
                                editable: false
                            },
                            staff_account_code_short: {
                                editable: false
                            },
                            customer_account_code_short: {
                                editable: false
                            },
                            exchange_name: {
                                editable: false
                            },
                            timezone_name: {
                                editable: false
                            },
                            trade_date: {
                                editable: false
                            },
                            trade_time: {
                                editable: false
                            },
                            side_type_name: {
                                editable: false
                            },
                            asset_from_code: {
                                editable: false
                            },
                            asset_from_price: {
                                editable: false
                            },
                            asset_from_quantity: {
                                editable: false
                            },
                            asset_into_code: {
                                editable: false
                            },
                            asset_into_price: {
                                editable: false
                            },
                            asset_into_quantity: {
                                editable: false
                            },
                            trade_order_type_name: {
                                editable: false
                            },
                            trade_status_name: {
                                editable: false
                            },
                            trade_reason_type_name: {
                                editable: false
                            },
                            fee_amount: {
                                editable: false
                            },
                            fee_asset_code: {
                                editable: false
                            },
                            transaction_address: {
                                editable: false
                            },
                            transaction_address_url: {
                                editable: false
                            },
                            transaction_exchange_id: {
                                editable: false
                            },
                            transaction_internal: {
                                editable: false
                            },
                            transaction_root: {
                                editable: false
                            },
                        }
                    },
                },
                serverFiltering: true,
            },
            pageable: {
                refresh: true,
                pageSizes: true
            },
            selectable: "multiple",
            scrollable: true,
            sortable: true,
            reorderable: true,
            resizable: true,
            editable: "inline",
            columns: [
            {
                field: "ledger_id",
                title: "Ledger Id",
                hidden:true,
                width: 100
            }, {
                field: "group_name",
                title: "Group Name",
                width: 100
            }, {
                field: "staff_name",
                title: "Staff Name",
                width: 80
            }, {
                field: "staff_account_code_short",
                title: "Staff Account Name",
                width: 80
            }, {
                field: "customer_account_code_short",
                title: "Customer Account Name",
                width: 80
            }, {
                field: "exchange_name",
                title: "Exchange Name",
                width: 90
            }, {
                field: "timezone_name",
                title: "Timezone",
                width: 150
            }, {
                field: "trade_date",
                title: "Trade Date",
                width: 100
            }, {
                field: "trade_time",
                title: "Trade Time",
                width: 70
            }, {
                field: "side_type_name",
                title: "Side Type",
                width: 70
            },{
                field: "asset_from_code",
                title: "Asset From",
                width: 70
            }, {
                field: "asset_from_price",
                title: "From Price",
                width: 170
            }, {
                field: "asset_from_quantity",
                title: "From Quantity",
                width: 150
            }, {
                field: "asset_into_code",
                title: "Asset Into",
                width: 70
            }, {
                field: "asset_into_price",
                title: "Into Price",
                width: 170
            }, {
                field: "asset_into_quantity",
                title: "Into Quantity",
                width: 150
            }, {
                field: "type_name",
                title: "Trade Order",
                width: 80
            }, {
                field: "trade_status_name",
                title: "Trade Status",
                width: 80
            }, {
                field: "trade_reason_type_name",
                title: "Trade Reason",
                width: 80
            }, {
                field: "fee_amount",
                title: "Fee Amount",
                width: 150
            }, {
                field: "fee_asset_code",
                title: "Fee Asset Code",
                width: 70
            }, {
                field: "transaction_address",
                title: "Transaction Address",
                width: 70
            }, {
                field: "transaction_address_url",
                title: "Transaction Address Url",
                width: 70
            }, {
                field: "trade_transaction_type_name",
                title: "Trade Transaction Type",
                width: 70
            }, {
                field: "transaction_exchange_id",
                title: "Transaction Exchange",
                width: 70
            }, {
                field: "transaction_internal",
                title: "Transaction Internal",
                width: 200
            }, {
                field: "transaction_root",
                title: "Transaction Root",
                width: 200
            }  

            ],
        });
    }

    var categoriesElement = jQuery("#transactionSummaryGrid").kendoGrid({
        dataSource: {
            serverPaging: true,
            pageSize: 20,
            transport: {
                read: {
                    url: requestUrl+"/summary_list",
                    dataType: "json",
                    type: "GET",
                },
            },
            schema: {
                total:'total',
                data:'transaction_summaries',
                model: {
                    summary_id:'summary_id',
                    fields: {
                        merchant_name: {
                            editable: false
                        },
                        group_name: {
                            editable: false
                        },
                        staff_name: {
                            editable: false
                        },
                        staff_account_code_short: {
                            editable: false
                        },
                        customer_account_code_short: {
                            editable: false
                        },
                        exchange_name: {
                            editable: false
                        },
                        timezone_name: {
                            editable: false
                        },
                        trade_date: {
                            editable: false
                        },
                        trade_time: {
                            editable: false
                        },
                        side_type_name: {
                            editable: false
                        },
                        asset_from_code: {
                            editable: false
                        },
                        asset_from_price: {
                            editable: false
                        },
                        asset_from_quantity: {
                            editable: false
                        },
                        asset_into_code: {
                            editable: false
                        },
                        asset_into_price: {
                            editable: false
                        },
                        asset_into_quantity: {
                            editable: false
                        },
                        trade_order_type_name: {
                            editable: false
                        },
                        trade_status_name: {
                            editable: false
                        },
                        trade_reason_type_name: {
                            editable: false
                        },
                        fee_amount: {
                            editable: false
                        },
                        fee_asset_code: {
                            editable: false
                        },
                        transaction_address: {
                            editable: false
                        },
                        transaction_address_url: {
                            editable: false
                        },
                        transaction_exchange_id: {
                            editable: false
                        },
                        transaction_internal: {
                            editable: false
                        },
                        transaction_root: {
                            editable: false
                        },
                    }
                },
            },
            serverFiltering: true,
        },
        pageable: {
            refresh: true,
            pageSizes: true
        },
        scrollable: true,
        autoSync: true,
        sortable: true,
        reorderable: true,
        serverFiltering: true,
        groupable: true,
        resizable: true,
        detailInit: detailInit,
        dataBound : function(e) {
            var data = this.dataSource.data();
            $.each(data, function(i, row) {
                var ledgerCount = row.ledger_count;
                if (ledgerCount != '') {
                    $('tr[data-uid="' + row.uid + '"] td:nth-child(1)').css("background-color", "blue");
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
        editable: true,
        columns: [
        {
            field: "merchant_name",
            title: "Merchant Name",
            width: 100
        }, {
            field: "group_name",
            title: "Group Name",
            width: 100
        }, {
            field: "staff_name",
            title: "Staff Name",
            width: 80
        }, {
            field: "staff_account_code_short",
            title: "Staff Account Name",
            width: 80
        }, {
            field: "customer_account_code_short",
            title: "Customer Account Name",
            width: 80
        }, {
            field: "exchange_name",
            title: "Exchange Name",
            width: 90
        }, {
            field: "timezone_name",
            title: "Timezone",
            width: 150
        }, {
            field: "trade_date",
            title: "Trade Date",
            width: 100
        }, {
            field: "trade_time",
            title: "Trade Time",
            width: 70
        }, {
            field: "side_type_name",
            title: "Side Type",
            width: 70
        },{
            field: "asset_from_code",
            title: "Asset From",
            width: 70
        }, {
            field: "asset_from_price",
            title: "From Price",
            width: 170
        }, {
            field: "asset_from_quantity",
            title: "From Quantity",
            width: 150
        }, {
            field: "asset_into_code",
            title: "Asset Into",
            width: 70
        }, {
            field: "asset_into_price",
            title: "Into Price",
            width: 170
        }, {
            field: "asset_into_quantity",
            title: "Into Quantity",
            width: 150
        }, {
            field: "type_name",
            title: "Trade Order",
            width: 80
        }, {
            field: "trade_status_name",
            title: "Trade Status",
            width: 80
        }, {
            field: "trade_reason_type_name",
            title: "Trade Reason",
            width: 80
        }, {
            field: "fee_amount",
            title: "Fee Amount",
            width: 150
        }, {
            field: "fee_asset_code",
            title: "Fee Asset Code",
            width: 70
        }, {
            field: "transaction_address",
            title: "Transaction Address",
            width: 70
        }, {
            field: "transaction_address_url",
            title: "Transaction Address Url",
            width: 70
        }, {
            field: "trade_transaction_type_name",
            title: "Trade Transaction Type",
            width: 70
        }, {
            field: "transaction_exchange_id",
            title: "Transaction Exchange",
            width: 70
        }, {
            field: "transaction_internal",
            title: "Transaction Internal",
            width: 200
        }, {
            field: "transaction_root",
            title: "Transaction Root",
            width: 200
        }
        ],
    });
});

