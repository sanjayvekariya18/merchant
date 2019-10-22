$(document).ready(function() {
    var requestUrl = $('#requestUrl').val();
    var token = $('input[name="_token"]').val();
    var proxyLocationGrid = jQuery("#proxyGrid").kendoGrid({
        dataSource: {
            serverPaging: true,
            pageSize: 20,
            transport: {
                read: {
                    data: {
                        _token: token
                    },
                    url: requestUrl + "/getProxyLocationList",
                    dataType: "json",
                    type: "POST"
                }
            },
            schema: {
                data: 'proxy_location_list',
                total: 'total',
                model: {
                    id: 'proxy_id',
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
        editable: false,
        columns: [{
            field: "country_flag",
            title: "Flag",
            template: "<img src='#=data.country_flag#' width=20 height=20/>",
            width: 10
        }, {
            field: "proxy_target_ip",
            title: "Proxy Address",
            width: 30,
        }, {
            field: "country_code",
            title: "Country Code",
            width: 20
        }, {
            field: "country_name",
            title: "Country Name",
            width: 20
        }, {
            field: "region_code",
            title: "Region Code",
            width: 20
        }, {
            field: "region_name",
            title: "Region Name",
            width: 20
        }, {
            field: "city",
            title: "City",
            width: 20
        }, {
            field: "zip",
            title: "Zipcode",
            width: 20
        }],
    });
    $('#synclocation').click(function() {
        $('.content .preloader').show();
        $('.content .preloader img').show();
        $.ajax({
            url: requestUrl + "/fetchProxyLocation",
            type: "post",
            data: {
                _token: token
            },
            success: function(data) {
                $('.content .preloader').hide();
                $('.content .preloader img').hide();
                $('.totalrecord').html("Total : " + data);
                $('.totalrecord').show();
                $('#proxyGrid').data('kendoGrid').dataSource.read();
                $('#proxyGrid').data('kendoGrid').refresh();
            }
        });
    });
});