$(document).ready(function () {

	var requestUrl = $("#requestUrl").val();
	var token = $('input[name="_token"]').val();


	$(".select21").select2({
	   theme: "bootstrap",
	   placeholder: "Please Select Option",
	   width: '100%'
	});

	$("#time_start").timeDropper({
            primaryColor: "#428bca",
            setCurrentTime: false,
            format: "HH:mm"

    });

    $("#time_end").timeDropper({
            primaryColor: "#428bca",
            setCurrentTime: false,
            format: "HH:mm"
    });

    $("#status").bootstrapSwitch();	

    $("#merchant_id").on('change', function() {
        var merchantId = $(this).val();

        $('.content .preloader').show();
        $('.content .preloader img').show();

        $('#city_id').html("<option></option>");
        $('#location_id').html("<option></option>");
        $('select[name="group_id"]').html("<option></option>");
        $('select[name="merchant_account_id"]').html("<option></option>");
        $('select[name="customer_id"]').html("<option></option>");

        $.ajax({
            type:'POST',
            data:{_token:token,merchant_id:merchantId},
            dataType:"json",
            url : requestUrl+"/getMerchantCities",
            success:function(merchantcities,status,xhr){
                if(merchantcities.length)
                {
                    $.each(merchantcities, function(i,value) {
                        $('#city_id').append("<option value='"+value['city_id']+"'>"+value['city_name']+"</option>");
                    });
                    $('#city_id').val($('#h_city_id').val());
                    $('#city_id').trigger('change');
                }
            }
        });

        $.ajax({
            type:'POST',
            data:{_token:token,merchant_id:merchantId},
            dataType:"json",
            url : requestUrl+"/getMerchantGroups",
            success:function(staffGroups,status,xhr) {
                $.each(staffGroups, function(i,value) {
                    $('select[name="group_id"]').append("<option value='"+value['group_id']+"'>"+value['group_name']+"</option>");
                });
                $("#group_id").val($("#h_group_id").val()); 
                $("#group_id").trigger('change');
            }
        });


        $.ajax({
            type:'POST',
            data:{_token:token,merchant_id:merchantId},
            dataType:"json",
            url : requestUrl+"/getMerchantAccounts",
            success:function(merchantaccounts,status,xhr){
                if(merchantaccounts.length)
                {
                    $.each(merchantaccounts, function(i,value) {
                        $('#merchant_account_id').append("<option value='"+value['account_id']+"'>"+value['account_name']+"</option>");
                    });
                    $("#merchant_account_id").val($("#h_merchant_account_id").val()); 
                    $("#merchant_account_id").trigger('change');
                }
            }
        });

        $.ajax({
            type:'POST',
            data:{_token:token,merchant_id:merchantId},
            dataType:"json",
            url : requestUrl+"/getMerchantCustomers",
            success:function(staffGroups,status,xhr) {
                $.each(staffGroups, function(i,value) {
                    $('select[name="customer_id"]').append("<option value='"+value['customer_id']+"'>"+value['customer_name']+"</option>");
                });
                $("#customer_id").val($("#h_customer_id").val()); 
                $("#customer_id").trigger('change');

                $('.content .preloader').hide();
                $('.content .preloader img').hide();
            }
        });

    });

    $('#city_id').change(function(){

        var merchantCityID = $(this).val();
        var merchantID = $('#merchant_id').val();
        
        var token = $('input[name="_token"]').val();
        
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
                    $("#location_id").val($("#h_location_id").val()); 
                    $("#location_id").trigger('change');
                }

                $('.content .preloader').hide();
                $('.content .preloader img').hide();
            }

        });
    });

    $("#group_id").on('change', function() {
        
        var merchantId = $('#merchant_id').val();
        var groupId = $(this).val();

        $('.content .preloader').show();
        $('.content .preloader img').show();

        $('select[name="staff_id"]').html("<option></option>");
        
        $.ajax({
            type:'POST',
            data:{_token:token,merchant_id:merchantId,group_id:groupId},
            dataType:"json",
            url : requestUrl+"/getMerchantGroupStaffs",
            success:function(staffs,status,xhr) {
                $.each(staffs, function(i,value) {
                    $('select[name="staff_id"]').append("<option value='"+value['staff_id']+"'>"+value['staff_name']+"</option>");
                });
                $("#staff_id").val($("#h_staff_id").val()); 
                $("#staff_id").trigger('change');

                $('.content .preloader').hide();
                $('.content .preloader img').hide();
            }
        });
    });

    $("#customer_id").on('change', function() {
        var merchantId = $('#merchant_id').val();
        var customerId = $(this).val();

        $('.content .preloader').show();
        $('.content .preloader img').show();

        $('select[name="customer_account_id"]').html("<option></option>");
        $.ajax({
            type:'POST',
            data:{_token:token,merchant_id:merchantId,customer_id:customerId},
            dataType:"json",
            url : requestUrl+"/getCustomerAccounts",
            success:function(customers,status,xhr) {
                $.each(customers, function(i,value) {
                    $('select[name="customer_account_id"]').append("<option value='"+value['account_id']+"'>"+value['account_name']+"</option>");
                });
                $("#customer_account_id").val($("#h_customer_account_id").val()); 
                $('.content .preloader').hide();
                $('.content .preloader img').hide();
            }
        });
    }); 

    $("#merchant_id").trigger('change');
});
