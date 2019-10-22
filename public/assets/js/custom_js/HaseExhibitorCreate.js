$(document).ready(function () {
    
    $(".select21").select2({
        theme: "bootstrap",
        placeholder: "Please Select Option",
        width: '100%'
    });

    $('#hase_exhibitor_create').bootstrapValidator({
        fields: {
            exhibitor_hall: {
                validators: {
                    notEmpty: {
                        message: 'The field is required and cannot be empty'
                    }
                }
            },
            exhibitor_contact: {
                validators: {                    
                    /*regexp: {
                        regexp: /^[23569][0-9]{7}$/,
                        message: 'Contact number must be 8 digit only and starting from 2,3,5,6,9'
                    }*/
                }
            },
            exhibitor_namecard_url: {
                validators: {
                    regexp: {
                        regexp: /^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-?=&]*)*\/?$/,
                        message: 'The input is not a valid URL'
                    }
                }
            },
            exhibitor_location_map_url: {
                validators: {
                    regexp: {
                        regexp: /^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-?=&]*)*\/?$/,
                        message: 'The input is not a valid URL'
                    }
                }
            }
        },
        submitHandler: function (validator, form, submitButton) {
            
        }
    }).on('reset', function (event) {
        $('#hase_exhibitor_create').data('bootstrapValidator').resetForm();

    });
});