var token = $('input[name="_token"]').val();
var requestUrl = $("#requestUrl").val();

$(document).ready(function() {

    function user_idEditor(container, options) {
				        $("<input name='" + options.field + "' required type='text' data-text-field='username' data-value-field='user_id' data-bind='value:" + options.field + "' />")
				        .appendTo(container)
				        .kendoDropDownList({
							autoBind: true,
							dataTextField: "username",
				            dataValueField: "user_id",
				            dataSource: {
				                transport : {
									read : {
				                        dataType : 'json',
				                        url : requestUrl+"/getuser_idList",
				                        type : "GET"
				                    }
				                }
				            }
				        });
				    }
				     
					function identity_idEditor(container, options) {
				        $("<input name='" + options.field + "' required type='text' data-text-field='provider_name' data-value-field='id' data-bind='value:" + options.field + "' />")
				        .appendTo(container)
				        .kendoDropDownList({
							autoBind: true,
							dataTextField: "provider_name",
				            dataValueField: "id",
				            dataSource: {
				                transport : {
									read : {
				                        dataType : 'json',
				                        url : requestUrl+"/getidentity_idList",
				                        type : "GET"
				                    }
				                }
				            }
				        });
				    }		     
				
			     
					function identity_table_idEditor(container, options) {
				        $("<input name='" + options.field + "' required type='text' data-text-field='table_name' data-value-field='type_id' data-bind='value:" + options.field + "' />")
				        .appendTo(container)
				        .kendoDropDownList({
							autoBind: true,
							dataTextField: "table_name",
				            dataValueField: "type_id",
				            dataSource: {
				                transport : {
									read : {
				                        dataType : 'json',
				                        url : requestUrl+"/getidentity_table_idList",
				                        type : "GET"
				                    }
				                }
				            }
				        });
				    }
    
    var Portal_exceptionGrid = jQuery("#Portal_exceptionGrid").kendoGrid({
        dataSource: {
            serverPaging: true,
            serverFiltering: true,
            serverSorting: false,
            pageSize: 10,
            autoSync: true,
            transport : {
                read : {
                    data:{_token:token},
                    url: requestUrl+"/getPortal_exception",
                    dataType: "json",
                    type: "POST"
                },
                update: {
                    data:{_token:token},
                    url: requestUrl+"/updatePortal_exception",
                    dataType: "jsonp",
                    type: "POST"
                },
                parameterMap: function (options, operation) {
                    if (operation == "update") {
                        options.datetime = kendo.toString(new Date(options.datetime), "MM/dd/yyyy HH:mm:ss");
                        return options;
                    }
                    else { return options; }

                }
            },
            requestEnd: function(requestResponse) {
                if (requestResponse.type === "update" && requestResponse.response) {
                    var Portal_exceptionGridGrid = $("#Portal_exceptionGrid").data("kendoGrid");
                    Portal_exceptionGridGrid.dataSource.read();
                }
            },
            schema: {
                total:'total',
                data:'Portal_exception',
                model: {
                    id:'id',
                },
            },
        },
        pageable: {
            refresh: true,
            pageSizes: true
        },
        toolbar: [{ template: kendo.template($("#Portal_exceptionSearch").html()) }],
        scrollable: true,
        sortable: false,
        reorderable: true,
        groupable: true,
        resizable: true,
        editable: true,
        columns: [{
						field:"user_id",
						title:"Username",
						template:"#:username#",
						editor:user_idEditor,
					},{
						field:"identity_id",
						title:"Schema Field Name",
						template:"#:provider_name#",
						editor:identity_idEditor,
					},{
					field:"exception",
					title:"Exception",
				},{
					field:"datetime",
					title:"Datetime",		
					template:"<span style='color:red'>#:datetime#</span>",			
				},{
						field:"identity_table_id",
						title:"Schema Name",
						template:"#:table_name#",
						editor:identity_table_idEditor,
					},],
    });
    
    /* event Search */
    $("#Portal_exceptionBtnSearch").click(function () {
      var eventSearchValue = $('#Portal_exceptionSearchBox').val();
      $("#Portal_exceptionGrid").data("kendoGrid").dataSource.filter({
        logic  : "or",
        filters: [
            {
						field   : "username",
						operator: "contains",
						value   : eventSearchValue
					}, {
						field   : "provider_name",
						operator: "contains",
						value   : eventSearchValue
					}, { 
				field   : "exception",
        		operator: "contains",
        		value   : eventSearchValue
      		}, {
						field   : "table_name",
						operator: "contains",
						value   : eventSearchValue
					},
        ]
      });
    });

    $('#Portal_exceptionSearchBox').keypress(function(e){
        if(e.which == 13){//Enter key pressed
            $('#Portal_exceptionBtnSearch').click();//Trigger search button click event
        }
    });

    //Clearing the filter
    $("#Portal_exceptionBtnReset").click(function () {
        $('#Portal_exceptionSearchBox').val("");
        $("#Portal_exceptionGrid").data("kendoGrid").dataSource.filter({});     
    });
    /* calendar Search end*/
});