$(document).ready(function(){

    var token = $('input[name="_token"]').val();
    var requestUrl = $("#requestUrl").val();
    var actionUrl = $("#actionUrl").val();

    var identity_id = $("#identity_id").val();
    var identity_table_id = 4;
    
    var newSletterValue = $('#newsletter').val();
    var statusValue = $('#status').val();
    var google_2fa_status = $('#google2fa_enable_status').val();

    if (newSletterValue == 1) {
        $('#newsletter').bootstrapSwitch('state', true);
    } else {
        $('#newsletter').bootstrapSwitch('state', false);
    }
    if (statusValue == 1) {
        $('#status').bootstrapSwitch('state', true);
    } else {
        $('#status').bootstrapSwitch('state', false);
    }

    if (google_2fa_status == 1) {
        $('#google2fa_enable').bootstrapSwitch('state', true);
    } else {
        $('#google2fa_enable').bootstrapSwitch('state', false);
    }

    $(".selectLoad").select2({
        theme: "bootstrap",
        placeholder: "Please Select Option",
        width: '100%'
    });

    $(".select21").select2({
        theme: "bootstrap",
        placeholder: "Please Select Option",
        width: '100%'
    });

        
    $(".to-labelauty").labelauty({minimum_width: "135px"});

    $("#edit_customer_form").bootstrapValidator({
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
                        url: requestUrl+'/checkEditUsername',
                        beforeSend:function(){
                            $('.content .preloader').show();
                            $('.content .preloader img').show();
                        },
                        data: function(validator) {
                            return {
                                username: validator.getFieldElements('username').val(),
                                user_id: validator.getFieldElements('user_id').val(),
                                _token : validator.getFieldElements('_token').val()
                            };
                        },
                        message: 'The username is not available'
                    }
                }
            }
        }
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

    $('#option_pager_wizard').bootstrapWizard({
        'tabClass': 'nav nav-pills',
        onTabClick: function (tab, navigation, index) {
            var $total = navigation.find('li').length;
            var $current = index + 1;
            var $percent = ($current / $total) * 100;
            var pager_wizard = $('#option_pager_wizard');
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

    $('#option_pager_wizard').find('.nav-tabs').on('click','.closeTab',function(){
        var returnResponse = confirm("This can not be undone! Are you sure you want to do this?");
        if (returnResponse) {
            var tabID = $(this).parents('a').attr('href');
            $(this).parents('li').remove();
            $(tabID).remove();
            var tabFirst = $('#option_pager_wizard a:first');
            tabFirst.tab('show');
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

});
