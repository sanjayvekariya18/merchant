$(document).ready(function () {
    var requestUrl = $("#create_user_language_form").attr('action');
    $("#addLanguage").click(function(event){
        $categoryList = $('#languageDetails').clone();
          $categoryList.removeAttr('id');
        $categoryList.removeAttr('style');
         $('#language_id').attr('id', 'id_a');
        $('#panelList').append($categoryList);

    });

});

var constant = {
	TOKEN						: 	$("input[name=_token]").val(),
	ACTION_URL					: 	$('#create_user_language_form').attr('action'),
	FORM_ID 					: 	"#create_user_language_form",

	POSTAL_FORM_ID 				: 	"#locationForm",
    UPDATE_LOCATION_METHOD		: 	"/updateLocation",
    GET_POSTAL_METHOD			: 	"/getPostalAddress",

    TREE_METHOD_NAME			: 	"/getLocationTree",

    DROPDOWN_ID 				: 	"#identity_table_id",
    DROPDOWN_PRIMARY_ID 		: 	"identity_table_id",
    DROPDOWN_PRIMARY_VAL 		: 	"identity_table_name",

    COMBOBOX_ID 				: 	"#identity_id",
    GET_IDENTITY_METHOD_NAME	: 	"/getIdentities",
    COMBOBOX_PRIMARY_ID			: 	"identity_id",
    COMBOBOX_PRIMARY_VAL		: 	"identity_name",
    COMBO_TYPE_ERROR_MESSAGE	:	"Identity Required",

    MULTI_SELECT_DROPDOWN_ID 	: 	"#region_id",
    MULTI_METHOD_NAME		 	: 	"/getRegions",
    MULTI_DROPDOWN_PRIMARY_ID	: 	"region_id",
    MULTI_DROPDOWN_PRIMARY_VAL	: 	"region_name",
    MULTI_TYPE_ERROR_MESSAGE	:	"Please select at least one Identity",

    
    KENDO_GRID_ID				: 	"#identityCityListGrid",
    KENDO_GRID_PRIMARY_ID		: 	"list_id",
    
}
$(document).ready(function (){ 

	$("#tabstrip").kendoTabStrip().data("kendoTabStrip");

	var kendoTreeUrl = constant.ACTION_URL+constant.TREE_METHOD_NAME;

	$(constant.DROPDOWN_ID).kendoDropDownList({
		dataTextField: constant.DROPDOWN_PRIMARY_VAL,
		dataValueField: constant.DROPDOWN_PRIMARY_ID,
		value: 8,
		dataSource:[
			{ "identity_table_name": "Merchants", "identity_table_id": "8" },
			{ "identity_table_name": "Customers", "identity_table_id": "4" },
			{ "identity_table_name": "Staff",   "identity_table_id": "35" },
		],
		change: function(e){
			var identity_table_id = parseInt(this.value());
			var identityDataSource = new kendo.data.DataSource({
				transport : {   
					read : {
							data:{
								identity_table_id:identity_table_id,
								_token:constant.TOKEN
							},
							url: constant.ACTION_URL+constant.GET_IDENTITY_METHOD_NAME,
							dataType: "json",
							type: "POST"
						}
					}
				});

			kendoIdentity.setDataSource(identityDataSource);
			kendoIdentity.value("");
		}
	});
   
	$(constant.COMBOBOX_ID).kendoComboBox({
		placeholder: "Select Identity...",
		dataTextField: constant.COMBOBOX_PRIMARY_VAL,
		dataValueField: constant.COMBOBOX_PRIMARY_ID,
		filter: "contains",
		height: 400,

	}).data("kendoComboBox");
	
	var validator = $(constant.FORM_ID).kendoValidator({
		rules: {
			hasMerchant: function (input) {
				if(input.is("[id="+constant.COMBOBOX_PRIMARY_ID+"]")){
					var ms = input.data("kendoComboBox");       
					if(ms.value().length === 0){
						return false;
					}
				} 
				return true;
			},
		},
		messages: { 
			hasMerchant: constant.COMBO_TYPE_ERROR_MESSAGE
		}
	}).data("kendoValidator");



	var kendoIdentityTable 	= $(constant.DROPDOWN_ID).data("kendoDropDownList");
	var kendoIdentity 		= $(constant.COMBOBOX_ID).data("kendoComboBox");
	var kendoRegion 		= $(constant.MULTI_SELECT_DROPDOWN_ID).data("kendoMultiSelect");
	
	
	kendoIdentityTable.trigger("change");

	function onSave(data) {
		var list_id = data.model.list_id;
		var key="";
		var value=0;
		
		if(typeof data.values.status !== 'undefined'){
			key = "status";
			value = data.values.status;
		}else if(typeof data.values.priority !== 'undefined'){
			key = "priority";
			value = data.values.priority;
		}else{
			return false;
		}

		$.ajax({
			type: 'POST',
			data:{list_id:list_id,key:key,value:value,_token:constant.TOKEN},
			url: constant.ACTION_URL+constant.UPDATE_LIST_METHOD,
			success: function (eventData) {
				$(constant.KENDO_GRID_ID).data("kendoGrid").dataSource.read();
			}
		});
	}
	$("#submitBtn").click(function(){
		if (validator.validate()) {
			$('.content .preloader').show();
			$('.content img').show();
			$.ajax({
				type: 'POST',
				data:$(constant.FORM_ID).serialize(),
				url: "updateDetails",
				success: function (data) {
					window.location.href = "../users_language";
					
				}
			});
		}
	});
	
});

