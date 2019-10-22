jQuery('#hase_image_upload_form').bootstrapValidator({
        fields: {
                userName: {
                validators: {
                    notEmpty: {
                        message: 'The field is required and cannot be empty'
                    }
                }
            },albumName: {
                validators: {
                    notEmpty: {
                        message: 'The field is required and cannot be empty'
                    }
                }
            },imageLocation: {
                validators: {
                    notEmpty: {
                        message: 'The field is required and cannot be empty'
                    }
                }
            },
        },
        submitHandler: function (validator, form, submitButton) {       
        }

    });

    