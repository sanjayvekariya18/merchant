$(function () {

	var notifyValue = $('#notify').val();
	if (notifyValue == 1) {
	       $('#notify').bootstrapSwitch('state', true);
	   } else {
	       $('#notify').bootstrapSwitch('state', false);
	   }


	 //$("#status").val($("#orderStatus").val());
	 $("#assignee_id").val($("#assignStaff").val());
	 $('#status option[value="'+$("#orderStatus").val()+'"]').prop('selected', true);
	 $("#status").trigger("change");
});


$("input[name='notify']").on('switchChange.bootstrapSwitch', function (event, state) {
	if (state) {
		jQuery("#notify").val(1);
	} else {
		jQuery("#notify").val(0);
	}
  	event.preventDefault();
});


$("#status").on('change',function(){

	$("#status_comment").val($(this).children(":selected").attr("data-comment"));
});