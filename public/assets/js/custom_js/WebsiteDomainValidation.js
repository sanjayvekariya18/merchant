    jQuery('#website_domain').bootstrapValidator({
        fields: {
        website_url: {
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


    