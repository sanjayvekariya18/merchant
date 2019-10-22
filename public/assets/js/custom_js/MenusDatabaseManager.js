$(document).ready(function() {

    $(".k-plus").css('padding','8px');
    $(".login").css('margin-top','5px');
    $(".manual,.login").hide();

	var token = $('input[name="_token"]').val();
	var requestUrl = $("#requestUrl").val();
    
	var databaseManagerGrid = jQuery("#menusDatabaseManagerGrid").kendoGrid({
		dataSource: {
			serverPaging: true,
			pageSize: 20,
			transport: {
                read: {
                	data:{_token:token},
					url : requestUrl+'/getMenusDatabase',
					dataType : "json",
					type : "GET"
				},
				create: {
					data:{_token:token},
					url: requestUrl + "/createMenusDatabase",
					dataType: "json",
					type: "POST",
					complete:function(data) {
						$("#menusDatabaseManagerGrid").data("kendoGrid").dataSource.read();
					}
				},
				update: {
					data:{_token:token},
					url: requestUrl + "/updateMenusDatabase",
					dataType: "json",
					type: "POST",
					complete:function(data) {
						$("#menusDatabaseManagerGrid").data("kendoGrid").dataSource.read();
					}					
				},
				destroy: {
					data:{_token:token},
					url: requestUrl + "/deleteMenusDatabase",
					dataType: "json",
					type: "POST",
					complete:function(data) {
						$("#menusDatabaseManagerGrid").data("kendoGrid").dataSource.read();
					}
				},
			},
			requestEnd: function(requestResponse) {
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

				if (requestResponse.type === "create" && requestResponse.response.status) {
                    var $toast = toastr["success"]("", "Menu Connection Information Inserted.");
               	}else if (requestResponse.type === "update" && requestResponse.response.status){
               		var $toast = toastr["success"]("", "Menu Connection Information Updated.");	
               	}else if ((requestResponse.type === "update" || requestResponse.type === "create") && !requestResponse.response.status){
               		var $toast = toastr["error"]("", "Menu Connection Already Exist.");	
               	}else if (requestResponse.type === "destroy"){
               		var $toast = toastr["success"]("", "Menu Connection Deleted.");	
               	}
           	},
			schema: {
				total:'total',
				data:'menudatabases',
				model: {
					id:'id',
					fields: {
						menu_id: {
							editable: true
						},
						provider_id: {
							editable: true
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
		sortable: true,
		reorderable: true,
		serverFiltering: true,
		groupable: true,
		resizable: true,
		editable: 'inline',
		toolbar: ["create"],
		columns: [
			{
				command: [
					{ name: "edit", text: { edit: " ", update: " ", cancel: " " } },
					{ name: "destroy", text: " " }
				],
				title: "Action",
				width: 18
			}, {
				field: "menu_id",
				title: "Menu Name",
				editor: menusDropDownEditor,
				template: "#=(data.menu_name)?data.menu_name:''#",
				width: 80
			}, {
				field: "provider_id",
				title: "Provider Name",
				editor: providerDropDownEditor,
				template: "#=(data.provider_name)?data.provider_name:''#",
				width: 60
			}
		],
	});

	function menusDropDownEditor(container, options) {
		$('<input name="' + options.field + '" data-text-field="menu_name" data-value-field="menu_id" data-bind="value:' + options.field + '"/>')
			.appendTo(container)
			.kendoDropDownList({
				autoBind: true,
				dataSource: {
					transport: {
						read : {
							data:{_token:token},
							dataType : "json",
							url : requestUrl+"/getMenusName",
							type : "POST"
						}
					}
				},
				dataTextField: "menu_name",
				dataValueField: "menu_id"
			});
	}

	function providerDropDownEditor(container, options) {
		$('<input name="' + options.field + '" data-text-field="provider_name" data-value-field="provider_id" data-bind="value:' + options.field + '"/>')
			.appendTo(container)
			.kendoDropDownList({
				autoBind: true,
				dataSource: {
					transport: {
						read : {
							data:{_token:token},
							dataType : "json",
							url : requestUrl+"/getProviderName",
							type : "POST"
						}
					}
				},
				dataTextField: "provider_name",
				dataValueField: "provider_id"
			});
	}	
	
});