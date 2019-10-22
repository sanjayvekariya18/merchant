$(document).ready(function () {
    
    $(".select21").select2({
        theme: "bootstrap",
        placeholder: "Please Select Option",
        width: '100%'
    });

    $('#hase_venue_edit').bootstrapValidator({
        fields: {
            venue_name: {
                validators: {
                    notEmpty: {
                        message: 'The field is required and cannot be empty'
                    }
                }
            },
            venue_address1: {
                validators: {
                    notEmpty: {
                        message: 'The field is required and cannot be empty'
                    }
                }
            },
        },
        submitHandler: function (validator, form, submitButton) {
            
        }
    }).on('reset', function (event) {
        $('#hase_venue_edit').data('bootstrapValidator').resetForm();

    });
});