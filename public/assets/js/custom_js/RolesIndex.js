var constant = {
		ACTION_URL				: 	$('#request_url').val(),
		TOKEN					: 		$('input[name="_token"]').val(),
		
		KENDO_GRID_ID     		:   "#staffGrid",
		KENDO_GRID_METHOD   	:   "/getRolesList/",
		KENDO_GRID_PRIMARY_ID 	:   "group_id",
		UPDATE_LIST_METHOD    	:   "/updateRoles",
		CLONE_ROLE_METHOD		: 	"/cloneRole",
		FULL_ACCESS				: 	4
}

$(document).ready(function (){

	var accessibility = localStorage.getItem('accessibility');
	var columnList = [];

	columnList.push({
			field: "group_id",
			title: "Id",
			width:100
		},{
			field: "group_name",
			title: "Name",
			width:100,
		},{
			field: "total",
			title: "# Users",
			width:100
		},{
			field: "status",
			title: "Roles Status",
			editor: statusDropDownEditor,
			template: "#=(data.status)?'Enable':'Disable'#",
			width:100
		}
	);

	if (accessibility == constant.FULL_ACCESS) {
        columnList.unshift({ 
			command: { text: "Edit", click: editRole },
			title: " ", 
			width: "50px"
		},{ 
			command: { text: "Clone", click: cloneRole },
			title: " ", 
			width: "50px"
		});
    }

	$(".select21").select2({
	   theme: "bootstrap",
	   placeholder: "Please Select Option",
	   width: '100%'
	});

	var rolesGrid = jQuery(constant.KENDO_GRID_ID).kendoGrid({
		dataSource: {
			serverPaging: true,
			pageSize: 20,
			transport : {   
				read : {
						url: constant.ACTION_URL+constant.KENDO_GRID_METHOD+"0",
						dataType: "json",
						type: "GET"
					}
			},
			schema: {
				total:'total',
                data:'staff_groups',
				model: {
					id:'group_id',
					fields: {
						group_id:{
							editable: false,
						},
						group_name: {
							editable: false,							
						},
						total: {
							editable: false,							
						},
						status: {
							editable: (accessibility == constant.FULL_ACCESS)?true:false,	
							defaultValue: {
                                value: 1,
                                text: "Enable"
                            }						
						}
					}
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
		serverFiltering: true,
		groupable: true,
		resizable: true,
		editable: "incell",
        selectable: 'row',
        save:onSave,
		dataBound: function () {
							var grid = rolesGrid.data("kendoGrid");
				    		var gridData = grid.dataSource.view();
							    for (var i = 0; i < gridData.length; i++) {
							        var currentUid = gridData[i].uid;							        
							        if (gridData[i].edit != "edit") {
							            var currenRow = grid.table.find("tr[data-uid='" + currentUid + "']");
							            var editButton = $(currenRow).find(".k-grid-edit");
							            editButton.hide();
							        }
							    }
			            },
		columns: columnList,
	});

	function onSave(data) {
        var staffGroupGridObject = $(constant.KENDO_GRID_ID).data("kendoGrid");
        var selectedItem = staffGroupGridObject.dataItem(staffGroupGridObject.select());
        var group_id = selectedItem.group_id;
        var key = "";
        var value = 0;
        if (data.values.status) {
            key = "status";
            value = data.values.status;
        }        
        $.ajax({
            type: 'POST',
            data: {
                group_id: group_id,
                key: key,
                value: value,
                _token: constant.TOKEN
            },
            url: constant.ACTION_URL + constant.UPDATE_LIST_METHOD,
            success: function(rolesDeatils) {
                response = rolesDeatils.type;
                if (response.localeCompare("success") == 0) {
                    message = rolesDeatils.message;
                } else if (response.localeCompare("error") == 0) {
                    message = rolesDeatils.message;
                }
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
                var $toast = toastr[response]("", message);
                $(constant.KENDO_GRID_ID).data("kendoGrid").dataSource.read();
            }
        });
    }

	function statusDropDownEditor(container, options) {
        var data = [{
            Description: "Enable",
            ID: "1"
        }, {
            Description: "Disable",
            ID: "0"
        }];
        $('<input data-text-field="Description" data-value-field="ID" data-bind="value:' + options.field + '"/>').appendTo(container).kendoDropDownList({
            dataSource: data,
            dataTextField: "Description",
            dataValueField: "ID"
        });
    };

	function editRole(e) {
		e.preventDefault();
		var dataItem = this.dataItem($(e.currentTarget).closest("tr"));		
		window.location = constant.ACTION_URL+"/"+dataItem.group_id+"/edit";
	}	

	function cloneRole(e){
		e.preventDefault();
		var dataItem = this.dataItem($(e.currentTarget).closest("tr"));
		var groupId  = dataItem.group_id;
		$("#group_id").val(groupId);		
        $('#top_modal').modal("show");
	}

	$("#roleForm").on("submit",function(e){
		e.preventDefault();
		$('#top_modal').modal("hide");
		$('.content .preloader').show();
        $('.content .preloader img').show();
		$.ajax({
            type: 'POST',
            data: $(this).serialize(),
            url: constant.ACTION_URL + constant.CLONE_ROLE_METHOD,
            success: function(rolesDeatils) {
                var response = rolesDeatils.type;
                var message =  rolesDeatils.message;

                $('.content .preloader').hide();
        		$('.content .preloader img').hide();
                
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
                var $toast = toastr[response]("", message);
                $(constant.KENDO_GRID_ID).data("kendoGrid").dataSource.read();
            }
        });		
	});
});