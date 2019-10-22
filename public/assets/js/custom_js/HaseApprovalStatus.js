$(document).ready(function () {

	$("#approval_status").bootstrapValidator({
        fields: {
            approval_status_code: {
                validators: {
                    notEmpty: {
                        message: 'The status code is required'
                    }
                },
            },
            approval_status_name: {
                validators: {
                    notEmpty: {
                        message: 'The status name is required'
                    }
                },
            },
        }
    });
	
	var statusDisplayValue = $('#statusDisplay').val();
    if (statusDisplayValue == 1) {
        $('#approval_status_display').bootstrapSwitch('state', true);
    } else {
        $('#approval_status_display').bootstrapSwitch('state', false);
    }
    $(".my-colorpicker1").colorpicker();
});