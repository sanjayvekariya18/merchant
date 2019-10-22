$('#delete').on('show.bs.modal', function(e) {
	$(this).find('.btn-danger').attr('href', $(e.relatedTarget).attr('data-link'));
});