jQuery(document).ready(function () {
    jQuery(".select21").select2({
        theme: "bootstrap",
        placeholder: "Please Select Option",
        width: '100%'
    });
});
   jQuery('#create_Chatbot_Communication_form').bootstrapValidator({
        fields: {
            communications_topic: {
                validators: {
                    notEmpty: {
                        message: 'The field is required and cannot be empty'
                    }
                }
            },communications_opcode: {
                validators: {
                    notEmpty: {
                        message: 'The field is required and cannot be empty'
                    }
                }
            },communications_text: {
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

    