$(document).ready(function() {

    $(".k-plus").css('padding','8px');
    $(".login").css('margin-top','5px');
    $(".manual,.login").hide();

	var token = $('input[name="_token"]').val();
	var requestUrl = $("#requestUrl").val();

	$("#tabstrip").kendoTabStrip().data("kendoTabStrip");

    var limitsApikeyGrid = jQuery("#limitsApikeyGrid").kendoGrid({
		dataSource: {
			serverPaging: true,			
			pageSize: 20,
			transport: {
                read: {
                	data:{_token:token},
					url : requestUrl+'/getLimitsApikey',
					dataType : "json",
					type : "POST",
				}				
			},
			schema: {
				total:'total',
				data:'limitsApikey',
				model: {
					id:'id',
					fields: {
						id: {
							editable: false
						},
						status: {
							defaultValue:1
						},
						access_time:{
							editable: false	
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
        editable: true,
        toolbar: ["create"],
        save:onConnectorSave,
		columns: [
			{
				field: "provider_name",
				title: "Provider Name",
				width: "45px",
			},{
				field: "email",
				title: "Email",
				width: "45px",
			},{
				field: "api_key",
				title: "Api Key",
				width: "60px",
			},{
				field: "total_limit",
				title: "Available Limit",
				width: "25px",
			},{
				field: "access_time",
				title: "Access Time",
				width: "25px",
			}, {
				field: "status",
				title: "Status",
				width: "25px",
				editor: statusDropDownEditor,
				template: "#=(data.status)?'Enable':'Disable'#",
			}
		]		
	});

	function onConnectorSave(data) {

        var id = data.model.id;
        var key="";var value=0;var message="";var response="";

        if(data.values.provider_name){
            key = "provider_name";
            value = data.values.provider_name;
        }else if(data.values.email){
            key = "email";
            value = data.values.email;
        }else if(data.values.api_key){
            key = "api_key";
            value = data.values.api_key;
        }else if(data.values.total_limit){
            key = "total_limit";
            value = data.values.total_limit;
        }else if(data.values.status){
            key = "status";
            value = data.values.status;
        }

        $.ajax({
            type: 'POST',
            data:{id:id,key:key,value:value,_token:token},
            url: requestUrl+"/saveLimitsApikey",
            success: function (eventData) {

                data.sender.dataSource.read();
                response = eventData.type;

                if(response.localeCompare("success") == 0){
                    message = "Api Information Updated";
                }else if (response.localeCompare("error") == 0){
                    message = eventData.message;
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

            }
        });
    }

	function statusDropDownEditor(container, options) {
		var data = [
					{ Description: "Enable", ID: "1" },
					{ Description: "Disable", ID: "0" }
				];
			$('<input data-text-field="Description" data-value-field="ID" data-bind="value:' + options.field + '"/>')
				.appendTo(container)
				.kendoDropDownList ({
					dataSource: data,
					dataTextField: "Description",
					dataValueField:"ID"
			 });
	};

	function isEmpty(stringData) {
       return (!stringData || 0 === stringData.length);
   	}
	
});