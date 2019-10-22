function displayTosterMessage(type,message) {
	toastr.options = {
	   "closeButton": true,
	   "positionClass": "toast-top-right",
	   "showDuration": "1000",
	   "hideDuration": "1000",
	   "timeOut": "5000",
	   "extendedTimeOut": "1000",
	   "showEasing": "swing",
	   "hideEasing": "swing",
	   "showMethod": "show"
	};
	var $toast = toastr[type]("",message);
}