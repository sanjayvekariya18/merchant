$(function () {
	var assignStaff = $('#assignStaff').val();
	$('#assignee_id').val(assignStaff);
	var reservationStatus = $('#reserveStatus').val();
	$('#status').val(reservationStatus);

	var notifyValue = $('#notify').val();
	if (notifyValue == 1) {
        $('#notify').bootstrapSwitch('state', true);
    } else {
        $('#notify').bootstrapSwitch('state', false);
    }
});
$("input[name='notify']").on('switchChange.bootstrapSwitch', function (event, state) {
	if (state) {
		jQuery("#notify").val(1);
		jQuery("#customnotify").val(1);
	} else {
		jQuery("#notify").val(0);
		jQuery("#customnotify").val(0);
	}
   event.preventDefault();
});