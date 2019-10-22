$(document).ready(function () {
    
    var locationTable = $('#locationTable').DataTable({
        "responsive": true,
        "iDisplayLength": 25
    });

    $('#delete').on('show.bs.modal', function(e) {
        $(this).find('.btn-danger').attr('href', $(e.relatedTarget).attr('data-link'));
    });

    $("#restaurantToggle").bootstrapSwitch();
    $("#shopToggle").bootstrapSwitch();
    $('.bootstrap-switch-id-restaurantToggle span.bootstrap-switch-label').text("Shop");
    $('.bootstrap-switch-id-shopToggle span.bootstrap-switch-label').text("Restaurant");
    var requestUrl = $("#requestUrl").val();
    var token = $('input[name="_token"]').val();
    $("#restaurantToggle").on('switchChange.bootstrapSwitch', function (event, state) {
        if (state) {
            $.ajax({
                type:'POST',
                data:{_token:token,merchant_type:'8'},
                dataType:"text",
                url : requestUrl+"/getFilter",
                error:function(xhr,status,error) {
                    console.log(error);
                },
                success:function(merchantType,status,xhr) {
                    location.href=requestUrl;
                }
            });
        } else {
            $.ajax({
                type:'POST',
                data:{_token:token,merchant_type:'2'},
                dataType:"text",
                url : requestUrl+"/getFilter",
                error:function(xhr,status,error) {
                    console.log(error);
                },
                success:function(merchantType,status,xhr) {
                    location.href=requestUrl;
                }
            });
            $('span.bootstrap-switch-label').text(RESTAURANT_TYPE_NAME);
        }
        event.preventDefault();
    });

    $("#shopToggle").on('switchChange.bootstrapSwitch', function (event, state) {
        if (state) {
            $.ajax({
                type:'POST',
                data:{_token:token,merchant_type:'2'},
                dataType:"text",
                url : requestUrl+"/getFilter",
                error:function(xhr,status,error) {
                    console.log(error);
                },
                success:function(merchantType,status,xhr) {
                    location.href=requestUrl;
                }
            });
        } else {
            $.ajax({
                type:'POST',
                data:{_token:token,merchant_type:'8'},
                dataType:"text",
                url : requestUrl+"/getFilter",
                error:function(xhr,status,error) {
                    console.log(error);
                },
                success:function(merchantType,status,xhr) {
                    location.href=requestUrl;
                }
            });
            $('span.bootstrap-switch-label').text(SHOP_TYPE_NAME);
        }
        event.preventDefault();
    });

    if($('#locationTable thead th:first').text() == "Merchant Type ID"){
        locationTable.columns(0).search(RESTAURANT_TYPE_ID).draw();
        $('span.bootstrap-switch-label').text(SHOP_TYPE_NAME);

        $("#toggle").on('switchChange.bootstrapSwitch', function (event, state) {
            if (state) {
                locationTable.columns(0).search(RESTAURANT_TYPE_ID).draw();
                $('span.bootstrap-switch-label').text(SHOP_TYPE_NAME);
            } else {
                locationTable.columns(0).search(SHOP_TYPE_ID).draw();
                $('span.bootstrap-switch-label').text(RESTAURANT_TYPE_NAME);
            }
            event.preventDefault();
        });
    }
});