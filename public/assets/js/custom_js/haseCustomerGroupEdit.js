$(function () {
    var approvalStatusValue = $('#approvalStatus').val();
    if (approvalStatusValue == 1) {
        $('#approval').bootstrapSwitch('state', true);
    } else {
        $('#approval').bootstrapSwitch('state', false);
    }
});
$(".select21").select2({
    theme: "bootstrap",
    placeholder: "Please Select Merchant",
    width: '100%'
});

$('#Edit_customer_group_form').bootstrapValidator({
        fields: {
            merchant_id: {
                validators: {
                    notEmpty: {
                        message: 'Please Select Merchant'
                    }
                }
            },
            group_name: {
                validators: {
                    notEmpty: {
                        message: 'The Customer Group name is required and cannot be empty'
                    }
                }
            }
        },
        submitHandler: function (validator, form, submitButton) {
            
        }
    }).on('reset', function (event) {
        $('#Edit_customer_group_form').data('bootstrapValidator').resetForm();
    });

$("input[name='approval']").on('switchChange.bootstrapSwitch', function (event, state) {
    if (state) {
        jQuery("#approval").val(1);
    } else {
        jQuery("#approval").val(0);
    }
   event.preventDefault();
});
