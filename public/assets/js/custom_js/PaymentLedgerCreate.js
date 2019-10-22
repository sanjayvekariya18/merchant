$(document).ready(function() {
	var requestUrl = $("#requestUrl").val();
	var token = $('input[name="_token"]').val();

    $(".select21").select2({
       theme: "bootstrap",
       placeholder: "Please Select Option",
       width: '100%'
    });

    /*$('#summary_id').change(function() {
        var merchantID = $(this).children(":selected").attr("merchant-id");
        console.log(requestUrl);

        $('#city_id').html("");
        $.ajax({
            type:'POST',
            data:{_token:token,merchant_id:merchantID},
            dataType:"json",
            url : requestUrl+"/getMerchantCities",
            beforeSend:function(){
                if(merchantID == 0){
                    $('#location_id').html("");
                }
            },
            error:function(xhr,status,error){
                console.log(error);
            },
            success:function(merchantcities,status,xhr){
                if(merchantcities.length)
                {
                    $.each(merchantcities, function(i,value) {
                        $('#city_id').append("<option value='"+value['city_id']+"'>"+value['city_name']+"</option>");
                    });
                    $('#city_id').trigger('change');
                }
            }
        });

        $('#merchant_account_id').html("");
        $.ajax({
            type:'POST',
            data:{_token:token,merchant_id:merchantID},
            dataType:"json",
            url : requestUrl+"/getMerchantAccounts",
            beforeSend:function(){
                if(merchantID == 0){
                    $('#merchant_account_id').html("");
                }
            },
            error:function(xhr,status,error){
                console.log(error);
            },
            success:function(merchantaccounts,status,xhr){
            	$('#merchant_account_id').append("<option></option>");
                if(merchantaccounts.length)
                {
                    $.each(merchantaccounts, function(i,value) {
                        $('#merchant_account_id').append("<option value='"+value['account_id']+"'>"+value['account_name']+"</option>");
                    });
                }
            }
        });

        $('#customer_id').html("");
        $.ajax({
            type:'POST',
            data:{_token:token,merchant_id:merchantID},
            dataType:"json",
            url : requestUrl+"/getMerchantCustomers",
            beforeSend:function(){
                if(merchantID == 0) {
                    $('#customer_id').html("");
                }
            },
            error:function(xhr,status,error){
                console.log(error);
            },
            success:function(merchantaccounts,status,xhr){
            	$('#customer_id').append("<option></option>");
                if(merchantaccounts.length)
                {
                    $.each(merchantaccounts, function(i,value) {
                        $('#customer_id').append("<option value='"+value['customer_id']+"'>"+value['customer_name']+"</option>");
                    });
                }
            }
        });
    });

    $('#customer_id').change(function() {
        var customerID = $(this).val();
        $('#customer_account_id').html("");

        $.ajax({
            type:'POST',
            data:{_token:token,customer_id:customerID},
            dataType:"json",
            url : requestUrl+"/getCustomerAccounts",
            beforeSend:function(){
                if(customerID == 0){
                    $('#customer_account_id').html("");
                }
            },
            error:function(xhr,status,error){ console.log(error); },
            success:function(customeraccounts,status,xhr){
            	$('#customer_account_id').append("<option></option>");
                if(customeraccounts.length)
                {
                    $.each(customeraccounts, function(i,value) {
                        $('#customer_account_id').append("<option value='"+value['account_id']+"'>"+value['account_name']+"</option>");
                    });
                }
            }
        });
    });

    $('#city_id').change(function() {
        var merchantCityID = $(this).val();
        var merchantID = $('#summary_id').children(":selected").attr("merchant-id");
                
        $('#location_id').html("");
        $.ajax({
            type:'POST',
            data:{_token:token,merchant_id:merchantID,merchant_city_id:merchantCityID},
            dataType:"json",
            url : requestUrl+"/getMerchantCityPostals",
            beforeSend:function(){
                if(merchantID == 0){
                    $('#location_id').html("");
                }
            },
            error:function(xhr,status,error){
                console.log(error);
            },
            success:function(merchantCityLocations,status,xhr){
                if(merchantCityLocations.length)
                {
                    $.each(merchantCityLocations, function(i,value) {
                        $('#location_id').append("<option value='"+value['location_id']+"'>"+value['location_name']+"</option>");
                    });
                }
            }
        });
    });*/
});