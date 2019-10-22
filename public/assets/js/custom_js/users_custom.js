'use strict';

$(document).ready(function () {
    $('#table').DataTable({
        "responsive": true,
        "iDisplayLength": 25
    });

     $('#delete').on('show.bs.modal', function(e) {
		$(this).find('.btn-danger').attr('href', $(e.relatedTarget).attr('data-link'));
	});
});