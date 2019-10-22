var constant = {
	KENDO_GRID_ID   : "#payeeGrid",
	KENDO_GRID_METHOD : "/getPayees",
	UPDATE_LIST_METHOD : "/updatePayee",
}

$(document).ready(function () {
	var requestUrl = $('#requestUrl').val();
	var token = $('input[name="_token"]').val();

	function onSave(data) {
		
		var payeeGridObject = $(constant.KENDO_GRID_ID).data("kendoGrid");
		var selectedItem = payeeGridObject.dataItem(payeeGridObject.select());
		
		var payee_id = selectedItem.payee_id;
		var identityId = selectedItem.identity_id;
		
		var key="";
		var value=0;
						
		if(data.values.payee_code) {
			key = "identity_code";
			value = data.values.payee_code;
		}
		else if(data.values.payee_name) {
			key = "identity_name";
			value = data.values.payee_name;
		}
		else{
			return false;
		}

		$.ajax({
			type: 'POST',
			data:{
				payee_id:payee_id,
				identity_id:identityId,
				key:key,
				value:value,
				_token:token
			},
			url: requestUrl+constant.UPDATE_LIST_METHOD,
			success: function (eventData) {
				$(constant.KENDO_GRID_ID).data("kendoGrid").dataSource.read();
			}
		});
	}

	var payeeGrid = jQuery(constant.KENDO_GRID_ID).kendoGrid({
		dataSource: {
			serverPaging: true,
			pageSize: 20,
			transport : {
				read : {
					data:{_token:token},
					url: requestUrl+constant.KENDO_GRID_METHOD,
					dataType: "json",
					type: "POST"
				}
			},
			schema: {
				total:'total',
				data:'payees',
				model: {
					payee_id:'payee_id',
				},
			},
			serverFiltering: true,
		},
		pageable: {
			refresh: true,
			pageSizes: true
		},
		scrollable: true,
		autoSync: true,
		sortable: true,
		reorderable: true,
		serverFiltering: false,
		groupable: true,
		resizable: true,
		editable: true,
		selectable:'row',
		save:onSave,
		edit: function(e){
			var columnNotEditableIndex = [1,2];
			if($.inArray(parseInt(e.container.index()),columnNotEditableIndex) != -1){
				this.closeCell();
			}
		},
		columns: [
		{ 
			command: { text: "Edit", click: editPayee },
			title: " ", 
			width: "100px"
		},{
			field: "payee_id",
			title: "Payee#",
			hidden:true,
		},{
			field: "payee_code",
			title: "Payee Code",
			width:100
		},{
			field: "payee_name",
			title: "Payee Name",
			width:100
		}
		],
	});

	function editPayee(e) {
		e.preventDefault();
		var dataItem = this.dataItem($(e.currentTarget).closest("tr"));
		console.log(dataItem.payee_id);
		window.location = requestUrl+"/"+dataItem.payee_id+"/edit";
	}


	$("#payeeSearchGrid").keyup(function (e) {
		var searchValue = $('#payeeSearchGrid').val();
		$(constant.KENDO_GRID_ID).data("kendoGrid").dataSource.filter({
			logic  : "or",
			filters: [{
				field   : "payee_code",
				operator: "contains",
				value   : searchValue
			},
			{
				field   : "payee_name",
				operator: "contains",
				value   : searchValue
			},
			{
				field   : "postal_name",
				operator: "contains",
				value   : searchValue
			}]
		});
	});
	
	var payeeValidator = $("#payeeForm").kendoValidator({
		// code for validate
	}).data("kendoValidator");

	$('#payeeForm').on('submit',function(){
		 if (payeeValidator.validate()) {
			$.ajax({
				type: 'POST',
				data:$('#payeeForm').serialize(),
				url: requestUrl,
				success: function (eventData) {

					$('#payeeForm').trigger("reset");	
					$(constant.KENDO_GRID_ID).data("kendoGrid").dataSource.read();	
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
				var $toast = toastr["success"]("", "Payee Added.");		
				}
			});
			return false;
		}
	});
});