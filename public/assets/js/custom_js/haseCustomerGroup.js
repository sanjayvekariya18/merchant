$('#delete').on('show.bs.modal', function(e) {
	$(this).find('.btn-danger').attr('href', $(e.relatedTarget).attr('data-link'));
});
//$("[name='newsletter']").bootstrapSwitch(options);
$("input[name='newsletter']").bootstrapSwitch();
$("input[name='status']").bootstrapSwitch();
$("input[name='newsletter']").on('switchChange.bootstrapSwitch', function (event, state) {
	if (state) {
		jQuery("#newsletter").val(1);
	} else {
		jQuery("#newsletter").val(0);
	}
   event.preventDefault();
});
$("input[name='status']").on('switchChange.bootstrapSwitch', function (event, state) {
	if (state) {
		jQuery("#status").val(1);
	} else {
		jQuery("#status").val(0);
	}
   event.preventDefault();
});