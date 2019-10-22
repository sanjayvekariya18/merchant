$(document).ready(function () {

	var requestUrl = $("#requestUrl").val();
    var token = $('input[name="_token"]').val();
	$(".select21").select2({
	   theme: "bootstrap",
	   placeholder: "Please Select Option",
	   width: '100%'
	});

	$('#merchant_id').change(function(){        
        
        var merchantID = $(this).val();
        
        $('#city_id').html("<option></option>");
        $('#merchant_account_id').html("<option></option>");
        $('#customer_id').html("<option></option>");
        $('#customer_account_id').html("<option></option>");
        
        $('.content .preloader').show();
        $('.content .preloader img').show();

        $.ajax({
            type:'POST',
            data:{_token:token,merchant_id:merchantID},
            dataType:"json",
            url : requestUrl+"/getMerchantCities",
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

        $.ajax({
            type:'POST',
            data:{_token:token,merchant_id:merchantID},
            dataType:"json",
            url : requestUrl+"/getMerchantAccounts",
            success:function(merchantaccounts,status,xhr){
                if(merchantaccounts.length)
                {
                    $.each(merchantaccounts, function(i,value) {
                        $('#merchant_account_id').append("<option value='"+value['account_id']+"'>"+value['account_name']+"</option>");
                    });
                }
            }
        });

        $.ajax({
            type:'POST',
            data:{_token:token,merchant_id:merchantID},
            dataType:"json",
            url : requestUrl+"/getMerchantCustomers",
            success:function(merchantaccounts,status,xhr){
                if(merchantaccounts.length)
                {
                    $.each(merchantaccounts, function(i,value) {
                        $('#customer_id').append("<option value='"+value['customer_id']+"'>"+value['customer_name']+"</option>");
                    });
                }

                $('.content .preloader').hide();
                $('.content .preloader img').hide();
            }
        });
    });

    $('#customer_id').change(function(){
        var customerID = $(this).val();
        var merchantID = $("#merchant_id").val();
        var token = $('input[name="_token"]').val();

        $('.content .preloader').show();
        $('.content .preloader img').show();
        
        $('#customer_account_id').html("<option></option>");

        $.ajax({
            type:'POST',
            data:{_token:token,customer_id:customerID,merchant_id:merchantID},
            dataType:"json",
            url : requestUrl+"/getCustomerAccounts",
            success:function(customeraccounts,status,xhr){
                if(customeraccounts.length)
                {
                    $.each(customeraccounts, function(i,value) {
                        $('#customer_account_id').append("<option value='"+value['account_id']+"'>"+value['account_name']+"</option>");
                    });
                }
                $('.content .preloader').hide();
                $('.content .preloader img').hide();
            }
        });
    });

    $('#city_id').change(function(){
        var merchantCityID = $(this).val();
        var merchantID = $('#merchant_id').val();

        $('.content .preloader').show();
        $('.content .preloader img').show();
        
        $('#location_id').html("<option></option>");
        $.ajax({
            type:'POST',
            data:{_token:token,merchant_id:merchantID,merchant_city_id:merchantCityID},
            dataType:"json",
            url : requestUrl+"/getMerchantCityPostals",
            success:function(merchantCityLocations,status,xhr){
                if(merchantCityLocations.length)
                {
                    $.each(merchantCityLocations, function(i,value) {
                        $('#location_id').append("<option value='"+value['location_id']+"'>"+value['location_name']+"</option>");
                    });
                }
                $('.content .preloader').hide();
                $('.content .preloader img').hide();
            }

        });
    });

});
