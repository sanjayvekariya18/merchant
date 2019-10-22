$(document).ready(function () {
	$(".select21").select2({
	   theme: "bootstrap",
	   placeholder: "Please Select Option",
	   width: '100%'
	});

	$("input[name='enable']").bootstrapSwitch();

    $("input[name='enable']").on('switchChange.bootstrapSwitch', function (event, state) {
        if (state) {
            $("#enable").attr('checked',true);
        } else {
            $("#enable").attr('checked',false);
        }
        event.preventDefault();
    });

    $("input[name='enable']").on('switchChange.bootstrapSwitch', function (event, state) {
        if (state) {
            jQuery("#enable").val(1);
        } else {
            jQuery("#enable").val(0);
        }
       event.preventDefault();
    });

    var languageListStatusValue = $('#enable').val();
    if (languageListStatusValue == 1) {
        $('#enable').bootstrapSwitch('state', true);
    } else {
        $('#enable').bootstrapSwitch('state', false);
    }
});