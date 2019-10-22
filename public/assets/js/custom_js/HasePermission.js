$(document).ready(function () {
    $('#permissionForm').bootstrapValidator({
        fields: {
            name: {
                validators: {
                    notEmpty: {
                        message: 'The field is required and cannot be empty'
                    }
                }
            },
            description: {
                validators: {
                    notEmpty: {
                        message: 'The field is required and cannot be empty'
                    }
                }
            },
            'action[]': {
                validators: {
                    choice: {
                        min: 1,
                        message: 'Please choose at least one action'
                    }
                } 
            }
        },
        submitHandler: function (validator, form, submitButton) {
            
        }
    }).on('reset', function (event) {
        $('#permissionForm').data('bootstrapValidator').resetForm();
    });

    $("input[name='status']").bootstrapSwitch();

    $("input[name='status']").on('switchChange.bootstrapSwitch', function (event, state) {
        if (state) {
            $("#status").attr('checked',true);
        } else {
            $("#status").attr('checked',false);
        }
        event.preventDefault();
    });

    $('.btn-group-toggle label.btn').click(function(){

        var isChecked = $(this).children().is(':checked');
        
        if(isChecked){
            $(this).children().prop("checked",false);
        }else{
            $(this).children().prop("checked",true);
        }
    });

});