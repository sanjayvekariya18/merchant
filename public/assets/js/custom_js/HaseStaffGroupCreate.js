$(document).ready(function () {
    $(".select21").select2({
        theme: "bootstrap",
        placeholder: "Please Select Merchant",
        width: '100%'
    });
    $('#staffgroupForm').bootstrapValidator({
        fields: {
            merchant_id: {
                validators: {
                    notEmpty: {
                        message: 'Please Select Merchant'
                    }
                }
            },
            staff_group_name: {
                validators: {
                    notEmpty: {
                        message: 'The Staff Group name is required and cannot be empty'
                    }
                }
            }
        },
        submitHandler: function (validator, form, submitButton) {
            
        }
    }).on('reset', function (event) {
        $('#staffgroupForm').data('bootstrapValidator').resetForm();
    });

    $("input[name='customer_account_access']").bootstrapSwitch();
    $("input[name='location_access']").bootstrapSwitch();
    $("input[name='staff_group_status']").bootstrapSwitch();
    
});