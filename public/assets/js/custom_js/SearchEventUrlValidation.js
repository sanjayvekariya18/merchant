    jQuery('#search_result_queue').bootstrapValidator({
        fields: {
        event_url: {
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


    