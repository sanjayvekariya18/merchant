$(document).ready(function () {
        
    $('#countryForm').bootstrapValidator({
        fields: {
            country_name: {
                validators: {
                    notEmpty: {
                        message: 'The field is required and cannot be empty'
                    },
                    regexp: {
                        regexp: /^[a-zA-Z ]+$/,
                        message: 'Please enter valid country name'
                    }

                }
            },
            iso_code_2: {
                validators: {
                    notEmpty: {
                        message: 'The field is required and cannot be empty'
                    },
                    regexp:{
                        regexp: /^[A-Z]{2}$/,
                        message: 'Please enter valid ISO Format'
                    }
                }
            },
            iso_code_3: {
                validators: {
                    notEmpty: {
                        message: 'The field is required and cannot be empty'
                    },
                    regexp:{
                        regexp: /^[A-Z]{3}$/,
                        message: 'Please enter valid ISO Format'
                    }
                }
            }
            
        },
        submitHandler: function (validator, form, submitButton) {
            
        }
    }).on('reset', function (event) {
        $('#countryForm').data('bootstrapValidator').resetForm();
    });

    $("input[name='status']").bootstrapSwitch();

    $("input[name='status']").on('switchChange.bootstrapSwitch', function (event, state) {
        if (state) {
            $("#status").prop('checked',true);
        } else {
            $("#status").prop('checked',false);
        }
        event.preventDefault();
    });
});