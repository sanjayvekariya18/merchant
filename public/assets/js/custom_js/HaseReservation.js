$(document).ready(function () {
	var rows_selected = [];
	var reservationTable = $('#reservationTable').DataTable({
        "responsive": true,
        "iDisplayLength": 25,
        "order": [],
		"columnDefs": [ { orderable: false, targets: [0] } ]
    });

	$('#delete').on('show.bs.modal', function(e) {
		$(this).find('.btn-danger').attr('href', $(e.relatedTarget).attr('data-link'));
	});

	if($('#reservationTable thead th:first').text() == "Reservation Status ID") {
		$('input[type=radio][name=reservation_status]').change(function() {
			if(this.value != '') {
				reservationTable.columns(0).search(this.value).draw();
			}
			else {
				reservationTable.search('').columns().search('').draw();
			}
		});
	}
	function updateDataTableSelectAllCtrl(table){
		var $table             = table.table().node();
		var $chkbox_all        = $('tbody input[type="checkbox"]', $table);
		var $chkbox_checked    = $('tbody input[type="checkbox"]:checked', $table);
		var chkbox_select_all  = $('thead input[name="select_all"]', $table).get(0);

		// If none of the checkboxes are checked
		if($chkbox_checked.length === 0){
		  chkbox_select_all.checked = false;
		  if('indeterminate' in chkbox_select_all){
		     chkbox_select_all.indeterminate = false;
		  }

		// If all of the checkboxes are checked
		} else if ($chkbox_checked.length === $chkbox_all.length){
		  chkbox_select_all.checked = true;
		  if('indeterminate' in chkbox_select_all){
		     chkbox_select_all.indeterminate = false;
		  }

		// If some of the checkboxes are checked
		} else {
		  chkbox_select_all.checked = true;
		  if('indeterminate' in chkbox_select_all){
		     chkbox_select_all.indeterminate = true;
		  }
		}
	}
	// Handle click on "Select all" control
   $('thead input[name="select_all"]', reservationTable.table().container()).on('click', function(e){
   		if(this.checked){
		 $('#reservationTable tbody input[type="checkbox"]:not(:checked)').trigger('click');
		} else {
		 $('#reservationTable tbody input[type="checkbox"]:checked').trigger('click');
		}

		// Prevent click event from propagating to parent
		e.stopPropagation();
	});

	// Handle table draw event
	reservationTable.on('draw', function(){
		// Update state of "Select all" control
		updateDataTableSelectAllCtrl(reservationTable);
	});
	$('#reservationTable tbody').on('click', 'input[type="checkbox"]', function(e){
		var $row = $(this).closest('tr');

		// Get row data
		var data = reservationTable.row($row).data();

		// Get row ID
		var rowId = $(data[1]).val();
		// Determine whether row ID is in the list of selected row IDs 
		var index = $.inArray(rowId, rows_selected);

		// If checkbox is checked and row ID is not in list of selected row IDs
		if(this.checked && index === -1){
		 rows_selected.push(rowId);

		// Otherwise, if checkbox is not checked and row ID is in list of selected row IDs
		} else if (!this.checked && index !== -1){
		 rows_selected.splice(index, 1);
		}

		if(this.checked){
		 $row.addClass('selected');
		} else {
		 $row.removeClass('selected');
		}

		// Update state of "Select all" control
		updateDataTableSelectAllCtrl(reservationTable);

		// Prevent click event from propagating to parent
		e.stopPropagation();																	
	});
	$('#massAccept').on('show.bs.modal', function(e) {
		var checkedNum = $('input[name="checked_reservation[]"]:checked').length;
		if (!checkedNum) {
		    e.preventDefault()
		} 
		$('#reservationAcceptReject').attr('action',$(e.relatedTarget).attr('data-link'))
	});
	$('#massReject').on('show.bs.modal', function(e) {
		var checkedNum = $('input[name="checked_reservation[]"]:checked').length;
		if (!checkedNum) {
		    e.preventDefault()
		}
		$('#reservationAcceptReject').attr('action',$(e.relatedTarget).attr('data-link'))
	});
});

