$(".select21").select2({
   theme: "bootstrap",
   placeholder: "Please Select Option",
   width: '100%'
});

$('#reserve_date').datepicker({
	dateFormat: 'yyyy-mm-dd',
});

$(function () {
	var notifyStatusValue = $('#notify').val();
    if (notifyStatusValue == 1) {
        $('#notify').bootstrapSwitch('state', true);
    } else {
        $('#notify').bootstrapSwitch('state', false);
    }

    var newsLetterValue = $('#newsletter').val();
    if (newsLetterValue == 1) {
        $('#newsletter').bootstrapSwitch('state', true);
    } else {
        $('#newsletter').bootstrapSwitch('state', false);
    }

    var customerStatusValue = $('#customer_status').val();
    if (customerStatusValue == 1) {
        $('#customer_status').bootstrapSwitch('state', true);
    } else {
        $('#customer_status').bootstrapSwitch('state', false);
    }

    $("#reserve_time").timeDropper({
        primaryColor: "#428bca",
        setCurrentTime: false,
        format: "HH:mm"
    });

    $("#reservation_tables_form").bootstrapValidator({
        excluded: [':disabled'],
        fields: {
            first_name: {
                validators: {
                    notEmpty: {
                        message: 'The First Name field is required.'
                    }
                },
                required: true,
                minlength: 3
            },
            /*telephone: {
                validators: {
                    regexp: {
                        regexp: /[2-9]{1}\d{3}/,
                        message: 'The telephone number can only consist of numbers'
                    }
                }
            },*/
        }
    });

    var requestUrl = $("#requestUrl").val();
    $('#merchant_id').change(function(){
        var merchantID = $(this).val();
        var token = $('input[name="_token"]').val();

        $('#location_id').html("<option></option>");
        $('#city_id').html("<option></option>");

        $('.content .preloader').show();
        $('.content .preloader img').show();
        
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
            beforeSend:function(){
                if(merchantID == 0){
                    $('#location_id').html("");
                }
            },
            error:function(xhr,status,error){
                console.log(error);
            },
            success:function(merchantCityLocations,status,xhr){
                if(merchantCityLocations && merchantCityLocations.length)
                {   
                    $.each(merchantCityLocations, function(i,value) {
                        $('#location_id').append("<option value='"+value['location_id']+"'>"+value['location_name']+"</option>");
                        $('#location_id').val($('#promotion_postal_id').val());
                    });
                }

                $('.content .preloader').hide();
                $('.content .preloader img').hide();
            }

        });
    });

    $('#location_id').change(function(){
        var locationID = $(this).val();
        var token = $('input[name="_token"]').val();

        $('.content .preloader').show();
        $('.content .preloader img').show();

        $('#seating_id').html("<option></option>");
        $.ajax({
            type:'POST',
            data:{_token:token,location_id:locationID},
            dataType:"json",
            url : requestUrl+"/getSeatings",
            success:function(seatings,status,xhr){
                if(seatings && seatings.length)
                {   
                    $.each(seatings, function(i,value) {
                        $('#seating_id').append("<option value='"+value['seating_id']+"'>"+value['seating_name']+"</option>");
                        $('#seating_id').val($('#reservations_seating_id').val());
                    });
                }

                $('.content .preloader').hide();
                $('.content .preloader img').hide();
            }

        });
    });

    if($('select#merchant_id').length)
    {
        $('#merchant_id').trigger('change');
    }
    if(!$('select#merchant_id').length && $('#role_id').val() == 4)
    {
        $('#city_id').trigger('change');
    }
});

$("input[name='notify']").on('switchChange.bootstrapSwitch', function (event, state) {
    if (state) {
        jQuery("#notify").val(1);
    } else {
        jQuery("#notify").val(0);
    }
   event.preventDefault();
});

$("input[name='newsletter']").on('switchChange.bootstrapSwitch', function (event, state) {
    if (state) {
        jQuery("#newsletter").val(1);
    } else {
        jQuery("#newsletter").val(0);
    }
   event.preventDefault();
});

$("input[name='customer_status']").on('switchChange.bootstrapSwitch', function (event, state) {
    if (state) {
        jQuery("#customer_status").val(1);
    } else {
        jQuery("#customer_status").val(0);
    }
   event.preventDefault();
});