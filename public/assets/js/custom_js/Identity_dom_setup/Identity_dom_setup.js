var token = $('input[name="_token"]').val();
var requestUrl = $("#requestUrl").val();
var checkedIds = {};
	function tableIdentityIdEditor(container, options) {
        $("<input name='" + options.field + "' required type='text' data-text-field='table_code' data-value-field='type_id' data-bind='value:" + options.field + "' />")
        .appendTo(container)
        .kendoComboBox({
			autoBind: true,
			dataTextField: "table_code",
            dataValueField: "type_id",
            dataSource: {
                transport : {
					read : {
                        dataType : 'json',
                        url : requestUrl+"/getTableIdentityIdList",
                        type : "GET"
                    }
                }
            }
        });
    }
     
	function urlIdEditor(container, options) {
        $("<input name='" + options.field + "' required type='text' data-text-field='identity_website' data-value-field='identity_id' data-bind='value:" + options.field + "' />")
        .appendTo(container)
        .kendoComboBox({
			autoBind: true,
			dataTextField: "identity_website",
            dataValueField: "identity_id",
            dataSource: {
                transport : {
					read : {
                        dataType : 'json',
                        url : requestUrl+"/getUrlIdList",
                        type : "GET"
                    }
                }
            }
        });
    }

$(document).ready(function() {
    
    var identityDomSetupGrid = jQuery("#identityDomSetupGrid").kendoGrid({
        dataSource: {
            serverPaging: true,
            serverFiltering: true,
            serverSorting: false,
            pageSize: 50,
            group: [],
            transport : {
                read : {
                    data:{_token:token},
                    url: requestUrl+"/getIdentityDomSetup",
                    dataType: "json",
                    type: "POST"
                }
            },
            schema: {
                total:'total',
                data:'identityDomSetup',
                model: {
                    id:'type_id',
                    fields: {
                        type_name : { 
							validation: { 
								maxlength: function (input) {
									if (input.val().length != 0) {
										if (input.val().length > 50) {
											input.attr("data-maxlength-msg", "Max length is 50");
				                               return false;
								        }
								        if (input.val().length < 0) {
											input.attr("data-maxlength-msg", "Min length is 0");
				                               return false;
								        }
								    }
							        return true;
							    }
							} 
						},
					  table_source : { 
							validation: { 
								maxlength: function (input) {
									if (input.val().length != 0) {
										if (input.val().length > 50) {
											input.attr("data-maxlength-msg", "Max length is 50");
				                               return false;
								        }
								        if (input.val().length < 0) {
											input.attr("data-maxlength-msg", "Min length is 0");
				                               return false;
								        }
								    }
							        return true;
							    }
							} 
						},
                    },
                },
            },
        },
        pageable: {
            refresh: true,
            pageSizes: [50, 100, 200]
        },        
        noRecords: true,
        messages: {
            noRecords: "No event Found"
        },
        toolbar: [{ template: kendo.template($("#identityDomSetupHeaderAction").html()) }],
        scrollable: true,
        sortable: false,
        reorderable: true,
        groupable: true,
        resizable: true,
        editable: "incell",
        selectable: 'row',
        columnMenu: true,
        columns: [{
					field:"type_id",
					title:"Type Id",
					hidden: true
				},{
					field:"type_name",
					title:"Type Name"
				},{
					field:"table_source",
					title:"Table Source"
				},{
					field:"table_identity_id",
					title:"Tableentity",
					template:"#:table_code#",
					editor:tableIdentityIdEditor
				},{
					field:"url_id",
					title:"Url",
					template:"#:identity_website#",
					editor:urlIdEditor
				}],
		save: function(data) {
            
            if (data.values.type_name) {
                type_name = data.values.type_name;
            } else {
                type_name = data.model.type_name;
            }
            if (data.values.table_source) {
                table_source = data.values.table_source;
            } else {
                table_source = data.model.table_source;
            }
            if (data.values.table_identity_id) {
                table_identity_id = data.values.table_identity_id;
            } else {
                table_identity_id = data.model.table_identity_id;
            }
            if (data.values.url_id) {
                url_id = data.values.url_id;
            } else {
                url_id = data.model.url_id;
            }
            
            jQuery.ajax({
                type: 'POST',
                data: {
                    type_id: data.model.type_id,
                    type_name: type_name,
                    table_source: table_source,
                    table_identity_id: table_identity_id,
                    url_id: url_id,
                    _token: token
                },
                url: requestUrl + "/createIdentityDomSetup",
                success: function(instanceDetails) {
                    response = instanceDetails.type;
                    if (response.localeCompare("success") == 0) {
                        message = instanceDetails.message;
                    } else if (response.localeCompare("error") == 0) {
                        message = instanceDetails.message;
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
                    $("#identityDomSetupGrid").data("kendoGrid").dataSource.read();
                }
            });
        }		
    });
    
    
    /* event Search */
    $("#identityDomSetupBtnSearch").click(function () {
      var eventSearchValue = $('#identityDomSetupSearchBox').val();
      $("#identityDomSetupGrid").data("kendoGrid").dataSource.filter({
        logic  : "or",
        filters: [
            { 
				field   : "type_name",
        		operator: "contains",
        		value   : eventSearchValue
      		}, { 
				field   : "table_source",
        		operator: "contains",
        		value   : eventSearchValue
      		}, { 
				field   : "table_identity_id",
        		operator: "contains",
        		value   : eventSearchValue
      		}, { 
				field   : "url_id",
        		operator: "contains",
        		value   : eventSearchValue
      		},
        ]
      });
    });

    $('#identityDomSetupSearchBox').keypress(function(e){
        if(e.which == 13){//Enter key pressed
            $('#identityDomSetupBtnSearch').click();//Trigger search button click event
        }
    });

    //Clearing the filter
    $("#identityDomSetupBtnReset").click(function () {
        $('#identityDomSetupSearchBox').val("");
        $("#identityDomSetupGrid").data("kendoGrid").dataSource.filter({});     
    });
    /* calendar Search end*/
});	
	