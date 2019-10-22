$(document).ready(function () {

    url = $("#staffForm").attr('action');
    url = url.slice(0, url.lastIndexOf('/'));
    var requestUrl = url.slice(0, url.lastIndexOf('/'));
    var staff_id = $("#staff_id").val();
    var google_2fa_status = $('#google2fa_enable').val();

    if (google_2fa_status == 1) {
        $('#google2fa_enable').bootstrapSwitch('state', true);
    } else {
        $('#google2fa_enable').bootstrapSwitch('state', false);
    }

    $('#staffForm').bootstrapValidator({
        fields: {
            first_name: {
                validators: {
                    notEmpty: {
                        message: 'The field is required and cannot be empty'
                    }
                }
            },
            last_name: {
                validators: {
                    notEmpty: {
                        message: 'The field is required and cannot be empty'
                    }
                }
            },
            staff_email: {
                validators: {
                    /*notEmpty: {
                        message: 'The email address is required'
                    },*/
                    regexp: {
                        regexp: /^\S+@\S{1,}\.\S{1,}$/,
                        message: 'Please enter valid email format'
                    },
                    remote: {
                        type:'POST',
                        url: requestUrl+'/checkEmail',
                        data: function(validator) {
                            return {
                                type:"edit",
                                email: validator.getFieldElements('staff_email').val(),
                                staff_id:staff_id,
                                _token : validator.getFieldElements('_token').val()
                            };
                        },
                        message: 'The Email is already registered'
                    }
                }
            },
            password: {
                enabled: false,
                validators: {
                    notEmpty: {
                        message: 'Please provide a password'
                    },
                    stringLength: {
                        min: 6,
                        message: 'The password must be more than 6 characters long'
                    }
                }
            },
            confirmpassword: {
                enabled: false,
                validators: {
                    notEmpty: {
                        message: 'Please provide a password'
                    },
                    identical: {
                        field: 'password',
                        message: 'Please enter the password same as above'
                    }
                }
            },
            location_id: {
                validators: {
                     notEmpty: {
                        message: 'The field is required and cannot be empty'
                    },
                }
            },
        },
        submitHandler: function (validator, form, submitButton) {
            
        }
    }).on('reset', function (event) {
        $('#staffForm').data('bootstrapValidator').resetForm();
    }).on('keyup', '[name="password"]', function() {
            var isEmpty = $(this).val() == '';
            $('#staffForm')
                    .bootstrapValidator('enableFieldValidators', 'password', !isEmpty)
                    .bootstrapValidator('enableFieldValidators', 'confirmpassword', !isEmpty);

            // Revalidate the field when user start typing in the password field
            if ($(this).val().length == 1) {
                $('#staffForm').bootstrapValidator('validateField', 'password')
                                .bootstrapValidator('validateField', 'confirmpassword');
            }
    }).on('status.field.bv', function(e, data) {
        // Change username to your field name
        if (data.field === 'staff_email') {
            if(data.status === 'VALIDATING'){
                $('.content .preloader').show();
                $('.content .preloader img').show();
            }else{
                $('.content .preloader').hide();
                $('.content .preloader img').hide();
            }
        }
    });

    $("#staff_group_id").select2({
        theme: "bootstrap",
        placeholder: "Select Role"
    });

    $("#merchant_type_id").select2({
        theme: "bootstrap",
        placeholder: "Select Merchant Type"
    });

    if($('select#merchant_id').length)
    {
        $("#merchant_id").select2({
            theme: "bootstrap",
            placeholder: "Select Merchant"
        });
    }
    $("#city_id").select2({
        theme: "bootstrap",
        placeholder: "Select City"
    });

    $("#location_id").select2({
        theme: "bootstrap",
        placeholder: "Select Location"
    });

    $("input[name='staff_status']").bootstrapSwitch();

    $("input[name='staff_status']").on('switchChange.bootstrapSwitch', function (event, state) {
        if (state) {
            $("#staff_status").attr('checked',true);
        } else {
            $("#staff_status").attr('checked',false);
        }
        event.preventDefault();
    });

    $("input[name='google2fa_enable']").bootstrapSwitch();

    $("input[name='google2fa_enable']").on('switchChange.bootstrapSwitch', function (event, state) {
        if (state) {
            jQuery("#google2fa_enable").val(1);
        } else {
            jQuery("#google2fa_enable").val(0);
        }
       event.preventDefault();
    });

    $('#merchant_type_id').change(function(){
        var merchantTypeID = $(this).val();
        var token = $('input[name="_token"]').val();
        
        $('#merchant_id').html("<option></option>");
        $('#location_id').html("<option></option>");
        $('#city_id').html("<option></option>");

        $('.content .preloader').show();
        $('.content .preloader img').show();

        $.ajax({
            type:'POST',
            data:{_token:token,merchant_type_id:merchantTypeID},
            dataType:"json",
            url : requestUrl+"/getMerchants",            
            error:function(xhr,status,error){
                console.log(error);
            },
            success:function(merchants,status,xhr){
                if(merchants.length)
                {
                    $.each(merchants, function(i,value) {
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
        var token = $('input[name="_token"]').val();
        $('#location_id').html("");
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
        $('#location_id').html("<option></option>");

        $('.content .preloader').show();
        $('.content .preloader img').show();

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
                if(merchantCityLocations.length)
                {
                    $.each(merchantCityLocations, function(i,value) {
                        $('#location_id').append("<option value='"+value['postal_id']+"'>"+value['location_name']+"</option>");
                    });
                }

                $('.content .preloader').hide();
                $('.content .preloader img').hide();
            }

        });
    });
});