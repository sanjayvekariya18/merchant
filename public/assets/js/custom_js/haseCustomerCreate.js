$(document).ready(function(){
    
    var token = $('input[name="_token"]').val();
    var requestUrl = $("#requestUrl").val();
    var actionUrl = $("#actionUrl").val();

    $('#newsletter').bootstrapSwitch('state', false);
    $('#status').bootstrapSwitch('state', false);
    $("#create_customer_form").bootstrapValidator({
        fields: {
            first_name: {
                validators: {
                    notEmpty: {
                        message: 'The first name is required'
                    }
                },
                required: true,
                minlength: 3
            },
            last_name: {
                validators: {
                    notEmpty: {
                        message: 'The last name is required'
                    }
                },
                required: true,
                minlength: 3
            },
            email: {
                validators: {
                    notEmpty: {
                        message: 'The email address is required'
                    },
                    regexp: {
                        regexp: /^\S+@\S{1,}\.\S{1,}$/,
                        message: 'The input is not a valid email address'
                    }
                }
            },
            telephone: {
                validators: {
                    notEmpty: {
                        message: 'telephone is required and cannot be empty'
                    }
                },
                minlength: 20
            },

            security_answer: {
                validators: {
                    notEmpty: {
                        message: 'security_answer is required'
                    }
                }
            },
            security_question_id: {
                validators: {
                    notEmpty: {
                        message: 'Please Select Security Question'
                    }
                }
            },
            customer_group_id: {
                validators: {
                    notEmpty: {
                        message: 'Please Select Customer Group'
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
            }
        }
    });

    $(".select21").select2({
        theme: "bootstrap",
        placeholder: "Please Select Option",
        width: '100%'
    });
    
    $('#pager_wizard').bootstrapWizard({
        'tabClass': 'nav nav-pills',
        onTabClick: function (tab, navigation, index) {
            var $total = navigation.find('li').length;
            var $current = index + 1;
            var $percent = ($current / $total) * 100;
            var pager_wizard = $('#pager_wizard');
            // If it's the last tab then hide the last button and show the finish instead
            if ($current >= $total) {
                pager_wizard.find('.pager .next').hide();
                pager_wizard.find('.pager .finish').show();
                pager_wizard.find('.pager .finish').removeClass('disabled');
            } else {
                pager_wizard.find('.pager .next').show();
                pager_wizard.find('.pager .finish').hide();
            }
        }
    });    


    $("input[name='newsletter']").on('switchChange.bootstrapSwitch', function (event, state) {
        if (state) {
            jQuery("#newsletter").val(1);
        } else {
            jQuery("#newsletter").val(0);
        }
       event.preventDefault();
    });
    $("input[name='status']").on('switchChange.bootstrapSwitch', function (event, state) {
        if (state) {
            jQuery("#status").val(1);
        } else {
            jQuery("#status").val(0);
        }
       event.preventDefault();
    });


    function merchantTypeChange(){

        $("#merchant_type_id").on('change', function() {
            var merchantTypeId = $(this).val();

            $.ajax({
                type:'POST',
                data:{_token:token,merchant_type_id:merchantTypeId},
                dataType:"json",
                url : requestUrl+"/getMerchants",
                error:function(xhr,status,error) {
                    console.log(error);
                },
                success:function(merchants,status,xhr) {
                   
                    $('#merchant_id').html("<option></option>");
                    $.each(merchants, function(i,value) {
                        $('#merchant_id').append("<option value='"+value['merchant_id']+"'>"+value['merchant_name']+"</option>");
                    });
                }
            });
        });
    }    

    merchantTypeChange();

});