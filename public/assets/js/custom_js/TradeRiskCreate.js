$(document).ready(function () {

	/*var requestUrl = $("#requestUrl").val();*/

	$(".select21").select2({
	   theme: "bootstrap",
	   placeholder: "Please Select Option",
	   width: '100%'
	});

	$('#entry_date').datepicker({
		dateFormat: 'yyyy-mm-dd',
		autoClose: true
	});

	$("#entry_time").timeDropper({
	        primaryColor: "#428bca",
	        setCurrentTime: false,
	        format: "HH:mm"

	});

});
