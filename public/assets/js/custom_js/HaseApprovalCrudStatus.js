$(document).ready(function () {

    $("#approval_status").bootstrapValidator({
        fields: {
            status_code: {
                validators: {
                    notEmpty: {
                        message: 'The status code is required'
                    }
                },
            },
            status_name: {
                validators: {
                    notEmpty: {
                        message: 'The status name is required'
                    }
                },
            },
        }
    });
    $(".my-colorpicker1").colorpicker();
});