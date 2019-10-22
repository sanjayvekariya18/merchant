$(document).ready(function () {
    var requestUrl = $("#requestUrl").val();
    
    $(".select21").select2({
        theme: "bootstrap",
        placeholder: "Please Select Option",
        width: '100%'
    });

    $('#create_promotion_form').bootstrapValidator({
        fields: {
            offer_details: {
                validators: {
                    notEmpty: {
                        message: 'The field is required and cannot be empty'
                    }
                }
            },
            offer_hottest: {
                validators: {
                    regexp: {
                        regexp: /^[0-9]+$/,
                        message: 'The hottest offer can only consist of numbers'
                    }
                }
            },
            offer_featured: {
                validators: {
                    regexp: {
                        regexp: /^[0-9]+$/,
                        message: 'The featured offer can only consist of numbers'
                    }
                }
            },
            offer_begin: {
                validators: {
                    notEmpty: {
                        message: 'The field is required and cannot be empty'
                    },
                    date: {
                        format: 'MM/DD/YYYY',
                        message: 'The value is not a valid date'
                    }
                }
            },
            location_id: {
                validators: {
                    notEmpty: {
                        message: 'The field is required and cannot be empty'
                    }
                }
            },
            offer_expire: {
                validators: {
                    notEmpty: {
                        message: 'The field is required and cannot be empty'
                    },
                    date: {
                        format: 'MM/DD/YYYY',
                        message: 'The value is not a valid date'
                    }
                }
            },
            offer_url: {
                validators: {
                    /*notEmpty: {
                        message: 'The field is required and cannot be empty'
                    },*/
                    regexp: {
                        regexp: /^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-?=&]*)*\/?$/,
                        message: 'The input is not a valid URL'
                    }
                }
            },
            live_image_url: {
                validators: {
                    regexp: {
                        regexp: /\.(gif|jpg|jpeg|tiff|png)$/i,
                        message: 'The live image URL must end with .jpg, .jpeg or .png'
                    },
                    uri: {
                        message: 'The live image URL is not valid'
                    }
                }
            },
            live_image_compact_url: {
                validators: {
                    regexp: {
                        regexp: /\.(gif|jpg|jpeg|tiff|png)$/i,
                        message: 'The live image compact URL must end with .jpg, .jpeg or .png'
                    },
                    uri: {
                        message: 'The live image URL is not valid'
                    }
                }
            }
            
        },
        submitHandler: function (validator, form, submitButton) {
            
        }
    }).on('reset', function (event) {
        $('#create_promotion_form').data('bootstrapValidator').resetForm();

    }).on('focusout', '[name="offer_begin"]', function() {
            var isEmpty = $(this).val() != '';
            $('#create_promotion_form')
                    .bootstrapValidator('enableFieldValidators', 'offer_begin', !isEmpty);

            // Revalidate the field when user start typing in the password field
            if ($(this).val().length == 1) {
                $('#create_promotion_form').bootstrapValidator('validateField', 'offer_begin');
            }
    }).on('focusout', '[name="offer_expire"]', function() {
            var isEmpty = $(this).val() != '';
            $('#create_promotion_form')
                    .bootstrapValidator('enableFieldValidators', 'offer_expire', !isEmpty);

            // Revalidate the field when user start typing in the password field
            if ($(this).val().length == 1) {
                $('#create_promotion_form')
                    .bootstrapValidator('validateField', 'offer_expire');
            }
    });

    $('#merchant_type_id').change(function(){
        var merchantTypeID = $(this).val();
        
        $('.content .preloader').show();
        $('.content .preloader img').show();

        $('#merchant_id').html("<option></option>");
        $('#location_id').html("<option></option>");
        $('#city_id').html("<option></option>");
        
        var token = $('input[name="_token"]').val();
        $.ajax({
            type:'POST',
            data:{_token:token,merchant_type_id:merchantTypeID},
            dataType:"json",
            url : requestUrl+"/getMerchants",
            error:function(xhr,status,error){
                console.log(error);
            },
            success:function(merchant,status,xhr){
                if(merchant.length)
                {
                    $.each(merchant, function(i,value) {
                        $('#merchant_id').append("<option value='"+value['merchant_id']+"'>"+value['merchant_name']+"</option>");
                    });
                }

                $('.content .preloader').hide();
                $('.content .preloader img').hide();

            }

        });
    });

    $('#merchant_id').change(function(){
        var merchantID = $(this).val();

        $('.content .preloader').show();
        $('.content .preloader img').show();

        $('#location_id').html("<option></option>");
        $('#city_id').html("<option></option>");
        var token = $('input[name="_token"]').val();

        $.ajax({
            type:'POST',
            data:{_token:token,merchant_id:merchantID},
            dataType:"json",
            url : requestUrl+"/getMerchantCities",
            beforeSend:function(){
                if(merchantID == 0){
                    $('#location_id').html("<option></option>");
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
                    $('#location_id').html("<option></option>");
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

    if($('select#merchant_id').length)
    {
        $('#merchant_id').trigger('change');
    }
    if(!$('select#merchant_id').length && $('#role_id').val() == 4)
    {
        $('#city_id').trigger('change');
    }
    /*var offerFeaturedValue = $('#offerFeatured').val();
    var offerHottestValue = $('#offerHottest').val();*/
    var promotionStatus = $('#promotionStatus').val();
    var yearRound = $('#yearRound').val();

    if (promotionStatus == 1) {
        $('#status').bootstrapSwitch('state', true);
    } else {
        $('#status').bootstrapSwitch('state', false);
    }

    $('#featured_status').bootstrapSwitch();
    $("#featured_status").on('switchChange.bootstrapSwitch', function (event, state) {
        if (state) {
            $('#featured_block').show();
        } else {
            $('#featured_block').hide();
        }
       event.preventDefault();
    });

    $('#hottest_status').bootstrapSwitch();
    $("#hottest_status").on('switchChange.bootstrapSwitch', function (event, state) {
        if (state) {
            $('.hottest_block').show();
            if(!parseInt($("#offer_hottest").val()))
            {
                $("#offer_hottest").val(999999);   
            }
        } else {
            $('.hottest_block').hide();
        }
       event.preventDefault();
    });


    /*if (offerFeaturedValue == 1) {
        $('#offer_featured').bootstrapSwitch('state', true);
    } else {
        $('#offer_featured').bootstrapSwitch('state', false);
    }
    if (offerHottestValue == 1) {
        $('#offer_hottest').bootstrapSwitch('state', true);
    } else {
        $('#offer_hottest').bootstrapSwitch('state', false);
    }*/
    if (yearRound == 1) {
        $('#year_round').bootstrapSwitch('state', true);
        $('#dateRange').hide();
    } else {
        $('#year_round').bootstrapSwitch('state', false);
    }
    $('#offer_begin').datepicker({
        autoClose : true,
        minDate : new Date
    });
    $('#offer_expire').datepicker({
        autoClose : true,
        minDate : new Date
    });
    $("input[name='year_round']").on('switchChange.bootstrapSwitch', function (event, state) {
        if (state) {
            $('#dateRange').hide();
        } else {
            $('#dateRange').show();
        }
       event.preventDefault();
    });

});