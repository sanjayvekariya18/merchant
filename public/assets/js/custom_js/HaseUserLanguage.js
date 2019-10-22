jQuery(document).ready(function () {
    jQuery(".select21").select2({
        theme: "bootstrap",
        placeholder: "Please Select Option",
        width: '100%'
    });
});
jQuery('#create_user_language_form').bootstrapValidator({
        fields: {
            language_name: {
                validators: {
                    notEmpty: {
                        message: 'The field is required and cannot be empty'
                    }
                }
            },language_priority: {
                validators: {
                    notEmpty: {
                        message: 'The field is required and cannot be empty'
                    }
                }
            }
        },
        submitHandler: function (validator, form, submitButton) {
            
        }

    });

    