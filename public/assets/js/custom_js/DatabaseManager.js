$(".k-plus").css('padding','8px');
$(".login").css('margin-top','5px');
$(".manual,.login").hide();

var token = $('input[name="_token"]').val();
var requestUrl = $("#requestUrl").val();

$("#tabstrip").kendoTabStrip().data("kendoTabStrip");

var databaseManagerGrid = jQuery("#databaseManagerGrid").kendoGrid({
	dataSource: {
		serverPaging: true,
		pageSize: 20,
		transport: {
            read: {
                
            	data:{_token:token},
				url : requestUrl+'/getDatabaseManager',
				dataType : "json",
				type : "POST",
			},
			destroy: {
				data:{_token:token},
				url: requestUrl + "/deleteDatabaseManager",
				dataType: "json",
				type: "POST",
				complete:function(data) {
					$("#databaseManagerGrid").data("kendoGrid").dataSource.read();
				}
			}
		},
		schema: {
			total:'total',
			data:'databaseManager',
			model: {
				id:'id',
				fields: {
					id: {
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
	autoSync:true,
	scrollable: true,
	sortable: true,
	reorderable: true,
	serverFiltering: true,
	groupable: true,
	resizable: true,
	editable: true,
	save:onManagerSave,
	toolbar: ["create"],
	columns: [
		{
			field: "provider_name",
			title: "Provider Name",
			width: "25px",
		},{
            field: "environment_id",
            title: "Environment Name",
            editor: environmentDropDownEditor,
            template: "#=(data.environment_name)?data.environment_name:'Select Environment'#",
            width: "30px"
    	},{
			field: "db_ip",
			title: "Database Ip",
			width: "25px",
		},{
			field: "db_name",
			title: "Database Name",
			width: "25px",
		}, {
			field: "db_port",
			title: "Database Port",
			width: "25px",
		}, {
			field: "username",
			title: "Username",
			width: "25px",
		}, {
			field: "password",
			title: "Password",
			width: "25px",
			template: "********",
			editor: function (container, options) {
			        $('<input data-text-field="' + options.field + '" ' +
			                'class="k-input k-textbox" ' +
			                'type="password" ' +
			                'data-value-field="' + options.field + '" ' +
			                'data-bind="value:' + options.field + '"/>')
			                .appendTo(container)
			    	}			
		}
	],
	dataBound: function(eventData){
        var data = this.dataSource.data();
        $.each(data, function(i, row) {
           if (row.status){
   	        	$('tr[data-uid="' + row.uid + '"]').css("background-color", "green").css("color", "white");
           }else{
           		$('tr[data-uid="' + row.uid + '"]').css("background-color", "red").css("color", "white");
           }
        });
    }
});

function onManagerSave(data) {

    var id = data.model.id;
    var key="";var value=0;var message="";var response="";

    if(data.values.provider_name){
        key = "provider_name";
        value = data.values.provider_name;
    }else if(data.values.db_ip){
        key = "db_ip";
        value = data.values.db_ip;
    }else if(data.values.db_name){
        key = "db_name";
        value = data.values.db_name;
    }else if(data.values.db_port){
        key = "db_port";
        value = data.values.db_port;
    }else if(data.values.username){
        key = "username";
        value = data.values.username;
    }else if(data.values.password){
        key = "password";
        value = data.values.password;
    }else if(data.values.environment_id){
        key = "environment_id";
        value = data.values.environment_id;
    }

    $.ajax({
        type: 'POST',
        data:{id:id,key:key,value:value,_token:token},
        url: requestUrl+"/saveDatabaseManager",
        beforeSend: function () {
            window.kendo.ui.progress($("#databaseManagerGrid"), true);
        },
        success: function (eventData) {

            data.sender.dataSource.read();
            menus_priority_load();
            
            response = eventData.type;

            if(response.localeCompare("success") == 0){
                message = "Database Manager Information Updated";
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
    }).always(function () {
        window.kendo.ui.progress($("#databaseManagerGrid"), false);
    });      
}

function menus_priority_load(){
    $.ajax({
        url: requestUrl + "/getMenusHierarchy",
        method: 'POST',
        data:{_token:token},
        success: function( data ) {
            $('.dd-list').html(data);
        }
    });
}

function environmentDropDownEditor(container, options) {

    $('<input data-text-field="environment_name" data-value-field="environment_id" data-bind="value:' + options.field + '"/>')
        .appendTo(container)
        .kendoDropDownList({
            dataSource: {
                transport : {   
                    read : {
                        dataType : "json",
                        url : requestUrl+"/getEnvironment",
                        type : "GET"
                    }
                }
            },
            dataTextField: "environment_name",
            dataValueField: "environment_id"
        });
}

function isEmpty(stringData) {
   return (!stringData || 0 === stringData.length);
}


$(document).ready(function(){

	menus_priority_load();
});