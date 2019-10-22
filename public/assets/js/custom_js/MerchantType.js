$(document).ready(function() {

    $(".k-plus").css('padding','8px');
    $(".login").css('margin-top','5px');
    $(".manual,.login").hide();

	var token = $('input[name="_token"]').val();
	var requestUrl = $("#requestUrl").val();

	$("#tabstrip").kendoTabStrip().data("kendoTabStrip");

    var merchantTypeGrid = jQuery("#merchantTypeGrid").kendoGrid({
		dataSource: {
			serverPaging: true,			
			pageSize: 20,
			transport: {
                read: {
                	data:{_token:token},
					url : requestUrl+'/getMerchantTypes',
					dataType : "json",
					type : "POST",
				}				
			},
			schema: {
				total:'total',
				data:'merchantType',
				model: {
					id:'merchant_type_id',
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
            pageSizes: [5, 10, 20 , 30]         
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
				field: "merchant_type_name",
				title: "Merchant Type",
				width: "45px",
			},{
				field: "merchant_root_id",
				title: "Root Type",
				width: "25px",
				editor: rootDropDownEditor,
				template :"#= (data.root_type_name)?data.root_type_name:'None'#"
			},{
				field: "merchant_parent_id",
				title: "Parent Type",
				width: "25px",
				editor: parentDropDownEditor,
				template :"#= (data.parent_type_name)?data.parent_type_name:'None'#"
			}
		]		
	});

	function onConnectorSave(data) {

        var id = data.model.id;
        var key="";var value=0;var message="";var response="";

        if(data.values.merchant_type_name){
            key = "merchant_type_name";
            value = data.values.merchant_type_name;
        }else if(data.values.merchant_root_id){
            key = "merchant_root_id";
            value = data.values.merchant_root_id;
        }else {
            key = "merchant_parent_id";
            value = data.values.merchant_parent_id;
        }

        $.ajax({
            type: 'POST',
            data:{id:id,key:key,value:value,_token:token},
            url: requestUrl+"/saveMerchantTypes",
            success: function (eventData) {

                data.sender.dataSource.read();
                response = eventData.type;

                if(response.localeCompare("success") == 0){
                    message = "Mechant Types Information Updated";
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

	function rootDropDownEditor(container, options) {

    $('<input data-text-field="merchant_type_name" data-value-field="merchant_type_id" data-bind="value:' + options.field + '"/>')
        .appendTo(container)
        .kendoDropDownList({
            dataSource: {
                transport : {   
                    read : {
                        dataType : "json",
                        url : requestUrl+"/getAllParentMerchantTypes",
                        type : "GET"
                    }
                }
            },
            dataTextField: "merchant_type_name",
            dataValueField: "merchant_type_id",
            optionLabel: {
                    merchant_type_name: "Select Merchant Type",
            }

        });
	}

	function parentDropDownEditor(container, options) {

    $('<input data-text-field="merchant_type_name" data-value-field="merchant_type_id" data-bind="value:' + options.field + '"/>')
        .appendTo(container)
        .kendoDropDownList({
            dataSource: {
                transport : {   
                    read : {
                        dataType : "json",
                        url : requestUrl+"/getAllMerchantTypes",
                        type : "GET"
                    }
                }
            },
            dataTextField: "merchant_type_name",
            dataValueField: "merchant_type_id",
            optionLabel: {
                    merchant_type_name: "Select Merchant Type",
            }
        });
	}
	
});