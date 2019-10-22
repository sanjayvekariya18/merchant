$(document).ready(function () {
        
    $('#merchantForm').bootstrapValidator({
        fields: {
            merchant_name: {
                validators: {
                    notEmpty: {
                        message: 'The field is required and cannot be empty'
                    }
                }
            },
            merchant_email: {
                validators: {
                    /*notEmpty: {
                        message: 'The field is required and cannot be empty'
                    },*/
                    regexp:{
                        regexp: /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/,
                        message: 'Please enter valid email format'
                    }
                }
            },
            merchant_telephone: {
                validators: {
                    notEmpty: {
                        message: 'The number is required and cannot be empty'
                    },
                    regexp: {
                        regexp: /^[23569][0-9]{7}$/,
                        message: 'Telephone number must be 8 digit only and starting from 2,3,5,6,9'
                    }
                }
            },
            merchant_website: {
                validators: {
                    regexp: {
                        regexp: /^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-?=&]*)*\/?$/,
                        message: 'The input is not a valid URL'
                    }
                }
            },
            live_image_url: {
                validators: {
                    regexp: {
                        regexp: /\.(gif|jpg|jpeg|tiff|png|svg)$/i,
                        message: 'The live image URL must end with .jpg, .jpeg, .svg or .png'
                    },
                    uri: {
                        message: 'The live image URL is not valid'
                    }
                }
            },
            live_image_compact_url: {
                validators: {
                    regexp: {
                        regexp: /\.(gif|jpg|jpeg|tiff|png|svg)$/i,
                        message: 'The live image compact URL must end with .jpg, .jpeg, .svg or .png'
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
        $('#merchantForm').data('bootstrapValidator').resetForm();
    });

    $("#merchant_type").select2({
        theme: "bootstrap",
        placeholder: "Merchant Type"
    });

    $("input[name='merchant_status']").bootstrapSwitch();

    $("input[name='merchant_status']").on('switchChange.bootstrapSwitch', function (event, state) {
        if (state) {
            $("#merchant_status").prop('checked',true);
        } else {
            $("#merchant_status").prop('checked',false);
        }
        event.preventDefault();
    });
});