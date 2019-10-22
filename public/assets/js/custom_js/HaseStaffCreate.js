$(document).ready(function () {
    var requestUrl = $("#staffForm").attr('action');
    var constants = {
        INIT_VALUE: 0,
        FIRST_VALUE: 1
    };

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
                        delay: 500,
                        url: requestUrl+'/checkEmail',                        
                        data: function(validator) {
                            return {
                                type: "create",
                                email: validator.getFieldElements('staff_email').val(),
                                _token : validator.getFieldElements('_token').val()
                            };
                        },
                        message: 'The Email is already registered'
                    }
                }
            },
            username: {
                validators: {
                     notEmpty: {
                        message: 'The field is required and cannot be empty'
                    },
                    remote: {
                        type:'POST',
                        delay: 500,
                        url: requestUrl+'/checkUsername',
                        beforeSend:function(){
                            $('.content .preloader').show();
                            $('.content .preloader img').show();
                        },
                        data: function(validator) {
                            return {
                                username: validator.getFieldElements('username').val(),
                                _token : validator.getFieldElements('_token').val()
                            };
                        },
                        message: 'The username is not available'
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
            
            /*password: {
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
                        message: 'The confirm password is required and can\'t be empty'
                    },
                    identical: {
                        field: 'password',
                        message: 'Please enter the password same as above'
                    }
                }
            }*/
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
        
        if ((data.field === 'staff_email' || data.field === 'username')) {
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
        placeholder: "Select Staff Group"
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

    $("#location_id").select2({
        theme: "bootstrap",
        placeholder: "Select Location"
    });

    $("#city_id").select2({
        theme: "bootstrap",
        placeholder: "Select City"
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

                    if(merchants.length == constants.FIRST_VALUE){
                        $('#merchant_id').val(merchants[constants.INIT_VALUE].merchant_id);
                        $('#merchant_id').trigger('change');
                    }                   
                }

                $('.content .preloader').hide();
                $('.content .preloader img').hide();
            }

        });
        
    });

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
    $('#merchant_id').trigger('change');    

    /*$(document).ajaxSend(function(event, request, settings) {
        console.log("called");
        $('.content .preloader').show();
        $('.content .preloader img').show();
    });

    $(document).ajaxComplete(function(event, request, settings) {
        console.log("called");
        $('.content .preloader').hide();
        $('.content .preloader img').hide();
    });*/

    /*$('#staff_email').on("focusout",function(){
        var email = $(this).val();
        var token = $('input[name="_token"]').val();

        $('.content .preloader').show();
        $('.content .preloader img').show();

        $.ajax({
            type:'POST',
            data:{_token:token,email:email},
            dataType:"json",
            url : requestUrl+"/checkEmail",
            success:function(responce,status,xhr){
                
                if(responce.valid){
                    $(".submitBtn").attr('disabled',false);
                }else{
                    $(".submitBtn").attr('disabled',true);
                }

                $('.content .preloader').hide();
                $('.content .preloader img').hide();
            }

        });
    });

    $('#username').on("focusout",function(){
        var username = $(this).val();
        var token = $('input[name="_token"]').val();

        $('.content .preloader').show();
        $('.content .preloader img').show();

        $.ajax({
            type:'POST',
            data:{_token:token,username:username},
            dataType:"json",
            url : requestUrl+"/checkUsername",
            success:function(responce,status,xhr){
                
                if(responce.valid){
                    $(".submitBtn").attr('disabled',false);
                }else{
                    $(".submitBtn").attr('disabled',true);
                }

                $('.content .preloader').hide();
                $('.content .preloader img').hide();
            }
        });
    });*/

});