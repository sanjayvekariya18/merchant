$(document).ready(function(){
    $('#password_form').bootstrapValidator({
        fields: {
            password: {
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
                validators: {
                    notEmpty: {
                        message: 'Please provide a password'
                    },
                    identical: {
                        field: 'password',
                        message: 'Please enter the password same as above'
                    }
                }
            }
        },
        submitHandler: function (validator, form, submitButton) {
            
        }
    }).on('keyup', '[name="password"]', function() {
            $('#password_form')
                    .bootstrapValidator('enableFieldValidators', 'password')
                    .bootstrapValidator('enableFieldValidators', 'confirmpassword');
                                
    });
    
});