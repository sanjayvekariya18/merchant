$(function () {
	var tableStatusValue = $('#status').val();
	if (tableStatusValue == 1) {
        $('#status').bootstrapSwitch('state', true);
    } else {
        $('#status').bootstrapSwitch('state', false);
    }

    $("#restaurant_tables_form").bootstrapValidator({
        fields: {
            table_name: {
                validators: {
                    notEmpty: {
                        message: 'The Name field is required.'
                    }
                },
                required: true,
                minlength: 3
            },
            min_capacity: {
                validators: {
                    notEmpty: {
                        message: 'The Minimum Capacity field is required.'
                    },
                    regexp: {
                        regexp: /[2-9]{0}\d{1}/,
                        message: 'The Minimum Capacity field must contain an integer.'
                    }
                },
                required: true
            },
            max_capacity: {
                validators: {
                    notEmpty: {
                        message: 'The Maximum Capacity field is required.'
                    },
                    regexp: {
                        regexp: /[2-9]{0}\d{1}/,
                        message: 'The Maximum Capacity field must contain an integer.'
                    }
                },
                required: true
            },
        }
    });
});

$("input[name='status']").on('switchChange.bootstrapSwitch', function (event, state) {
	if (state) {
		jQuery("#status").val(1);
	} else {
		jQuery("#status").val(0);
	}
   event.preventDefault();
});