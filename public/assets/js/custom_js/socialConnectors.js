$(document).ready(function() {

    var token = $('input[name="_token"]').val();
	var requestUrl = $("#requestUrl").val();
	var requestBaseUrl = $("#requestBaseUrl").val();

    var socialConnectorsGrid = jQuery("#socialConnectorsGrid").kendoGrid({
		dataSource: {
			serverPaging: true,			
			pageSize: 20,
			transport: {
                read: {
                	data:{_token:token},
					url : requestUrl+'/getSocialConnectors',
					dataType : "json",
					type : "POST",
				}				
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
        editable: false,
        dataBound: function(eventObject) {
            var data = this.dataSource.data();
            $.each(data, function(i, row) {
                if ((typeof row.connectorname !== 'undefined' && row.connectorname)) {
                	var clickedConnector = localStorage.getItem("highlighterLogin");
                	if (clickedConnector !== null && clickedConnector === row.connectorname) {
                		var rowElement = $('tr[data-uid="' + row.uid + '"] ');
                    	rowElement.css("background-color", "428BCA");
                    	localStorage.removeItem("highlighterLogin");
                	}
                }
            });
        },        
		columns: [
			{
				field: "connectorid",
				title: "id",
				hidden:true
			},{
				field: "connectorUrl",				
				hidden:true,
				template: "<input type='hidden' id='#= data.connectorname.toLowerCase()#ConnectorUrl' value='"+requestBaseUrl+"/#= data.connectorname.toLowerCase()#'",
			},{
				field: "connectorimage",
				title: "Icon",				
				width:15,
				template: "<img src='#=data.connectorimage#' width=30 height=30/>",
			},{
				field: "connectorname",
				title: "Connector",	
				width:20,
			},{
				field: "user",
				title: "User Name",	
				width:50,
				template: "#if(data.userstatus){#<div class='customer-photo' style='background-image: url(#= data.userprofile #);'></div><div class='customer-name'>#= data.username #</div>#}#"
			},{
				field: "status",
				title: "Connection Status",	
				width:15,
				template: "<a rel='#=(data.userstatus)?'disconnect':'connect'#_#= data.connectorname#'><button type='button' class='k-button'>#=(data.userstatus)?'Disconnect':'Connect'#</button></a>"
			}
		]		
	});
	
});