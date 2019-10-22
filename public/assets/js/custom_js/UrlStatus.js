$(document).ready(function() {
    var requestUrl = $("#requestUrl").val();
    $("#scrapeUrlStatusGrid").kendoGrid({
        dataSource: {
            serverPaging: true,
            pageSize: 20,
            transport: {
                read: {
                    url: requestUrl + '/getScrapeStatusHistory',
                    dataType: "json",
                    type: "GET",
                },
            },
            schema: {
                total: 'total',
                data: 'scrape_status',
                model: {
                    id: 'history_id',
                    fields: {
                        website_url: {
                            type: "string"
                        },
                        setup_date: {
                            type: "int"
                        },
                        scrape_date: {
                            type: "int"
                        },
                        cron_date: {
                            type: "int"
                        },
                        owner: {
                            type: "string"
                        }
                    }
                },
            },
            serverFiltering: true,
        },
        filterable: {
            mode: "row"
        },
        sortable: true,
        pageable: {
            refresh: true,
            pageSizes: true
        },
        serverFiltering: true,
        persistSelection: true,
        columns: [{
            field: "website_url",
            title: "Scrape URL",
            filterable: {
                cell: {
                    operator: "contains",
                    suggestionOperator: "contains"
                }
            },
            template: kendo.template(jQuery('#node_value_template').html()),
            width: 350
        }, {
            field: "setup_date",
            title: "Setup Date",
            filterable: false
        }, {
            field: "scrape_date",
            title: "Scrape Date",
            filterable: false
        }, {
            field: "cron_date",
            title: "Cron Date",
            filterable: false,
            template: kendo.template(jQuery('#cron_date_template').html()),
        }, {
            field: "owner_name",
            title: "Owner",
            filterable: false,
            width: 150
        }]
    });
});