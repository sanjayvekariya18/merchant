jQuery(document).ready(function () {
    jQuery(".select21").select2({
        theme: "bootstrap",
        placeholder: "Please Select Option",
        width: '100%'
    });
});
jQuery('#translation_json_query_form').bootstrapValidator({
        fields: {
            status_name: {
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

    