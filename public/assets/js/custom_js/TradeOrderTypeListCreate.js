$(document).ready(function (){
	var webUrl = $('#tradeOrderTypeListForm').attr('action');
	var treeUrl = webUrl+"/getOrderTypeListTree";
	// var accounts = new kendo.data.DataSource({
	// 	transport : {   
	// 		read : {
	// 			dataType : "json",
	// 			url : webUrl+"/getAccounts",
	// 			type : "GET",
	// 		}
	// 	}
	// });

	$("#type_id").kendoMultiSelect({
		placeholder: "Select Order Types...",
		dataTextField: "type_name",
		dataValueField: "type_id",
		filter: "contains",
		height: 400,
		dataSource: {
			transport : {   
				read : {
					dataType : "json",
					url : webUrl+"/tradeOrderTypeList",
					type : "GET",
				}
			}
		},
	}).data("kendoMultiSelect");

	// $("#customer_id").kendoComboBox({
	// 	placeholder: "Select Customer...",
	// 	dataTextField: "customer_name",
	// 	dataValueField: "customer_id",
	// 	filter: "contains",
	// 	height: 400,
	// 	dataSource: {
	// 		transport : {   
	// 			read : {
	// 				dataType : "json",
	// 				url : webUrl+"/getCustomers",
	// 				type : "GET",
	// 			}
	// 		}
	// 	},
	// }).data("kendoComboBox");

	// $("#asset_id").kendoComboBox({
	// 	placeholder: "Select Staff Account...",
	// 	dataTextField: "asset_code",
	// 	dataValueField: "asset_id",
	// 	filter: "contains",
	// 	height: 400,
	// 	dataSource: {
	// 		transport : {   
	// 			read : {
	// 				dataType : "json",
	// 				url : webUrl+"/getAssets",
	// 				type : "GET",
	// 			}
	// 		}
	// 	},
	// }).data("kendoComboBox");
	
	// $("#customer_account_id").kendoComboBox({
	// 	placeholder: "Select Customer Account...",
	// 	dataTextField: "account_name",
	// 	dataValueField: "account_id",
	// 	filter: "contains",
	// 	height: 400,
	// 	dataSource:accounts
	// }).data("kendoComboBox");

	// $("#staff_account_id").kendoComboBox({
	// 	placeholder: "Select Staff Account...",
	// 	dataTextField: "account_name",
	// 	dataValueField: "account_id",
	// 	filter: "contains",
	// 	height: 400,
	// 	dataSource:accounts
	// }).data("kendoComboBox");

	var validator = $("#tradeOrderTypeListForm").kendoValidator({
		rules: {
			hasOrderType: function (input) {
				if(input.is("[id=type_id")){
					var ms = input.data("kendoMultiSelect");       
					if(ms.value().length === 0){
						return false;
					}
				} 
				return true;
			},
			/*hasCustomer: function (input) {
				if(input.is("[id=customer_id")){
					if(input.val() == '' || 
						$('#customer_id').data("kendoComboBox").selectedIndex == -1){
						return false;
					}
				} 
				return true;
			},
			hasCustomerAccount: function (input) {
				if(input.is("[id=customer_account_id")){
					if(input.val() == '' || 
						$('#customer_account_id').data("kendoComboBox").selectedIndex == -1){
						return false;
					}
				} 
				return true;
			},
			hasStaffAccount: function (input) {
				if(input.is("[id=staff_account_id")){
					if(input.val() == '' || 
						$('#staff_account_id').data("kendoComboBox").selectedIndex == -1){
						return false;
					}
				} 
				return true;
			},
			hasAsset: function (input) {
				if(input.is("[id=asset_id")){
					if(input.val() == '' || 
						$('#asset_id').data("kendoComboBox").selectedIndex == -1){
						return false;
					}
				} 
				return true;
			}*/
		},
		messages: { 
			hasOrderType: "Please select at least one Order Type" ,
			// hasCustomer: "Please select Customer",
			// hasCustomerAccount: "Please select Customer Account",
			// hasStaffAccount: "Please select Staff Account",
			// hasAsset: "Please select Asset",
		}
	}).data("kendoValidator");

	var treeName = "topologyTree";
	var resultId = "topologyTreeResultId";
	var treeTemplate = "# if(!item.hasChildren){# <input type='hidden' id='#=item.id#' parent_id='#=item.parent_id#' d_text='#=item.text#'/> <input type='radio' class='radioJsonTree' name_a='#= item.text #'  id_a='#= item.id #' name='c_"+treeName+"' value='#=item.text#' data-bind='checked:jsontree' />#}else{# <input type='hidden' id='#=item.id#' parent_id='#=item.parent_id#' d_text='#=item.text#'/> <input type='radio' class='radioJsonTree' name_a='#= item.text #' id_a='#= item.id #' name='c_"+treeName+"' value='#=item.text#' data-bind='checked:jsontree'  /> #}#";
		

	function onSelect(e) {
         nodeId = $("#"+treeName).data('kendoTreeView').dataItem(e.node).id;
         $("input[id_a='"+nodeId+"']").trigger('click');

         $("#"+resultId).val(nodeId);

         $type_id = $("#type_id").data("kendoMultiSelect");
         // $customer_id = $("#customer_id").data("kendoComboBox");
         // $staff_account_id = $("#staff_account_id").data("kendoComboBox");
         // $customer_account_id = $("#customer_account_id").data("kendoComboBox");
         // $asset_id = $("#asset_id").data("kendoComboBox");

         $.ajax({
         	type:'GET',
         	url:webUrl+"/getOrderTypleList/"+nodeId,
         	dataType:'json',
         	success:function(json){

         		var types = new Array();

         		if (json.length > 0){

         			$.each(json, function(index, obj) {

					    types[index] = obj.type_id;

					    // $customer_id.enable(false);
					    // $staff_account_id.enable(false);
					    // $customer_account_id.enable(false);
					    // $asset_id.enable(false);

					    // $customer_id.value(obj.customer_id);
					    // $staff_account_id.value(obj.staff_account_id);
					    // $customer_account_id.value(obj.customer_account_id);
					    // $asset_id.value(obj.asset_id);
					});
         		}else{
        //  			$customer_id.enable(true);
				    // $staff_account_id.enable(true);
				    // $customer_account_id.enable(true);
				    // $asset_id.enable(true);

        //  			$customer_id.value("");
				    // $staff_account_id.value("");
				    // $customer_account_id.value("");
				    // $asset_id.value("");
         		}
         		// $('#customer_id').prop('disabled',false);
         		// $('#staff_account_id').prop('disabled',false);
         		// $('#customer_account_id').prop('disabled',false);
         		// $('#asset_id').prop('disabled',false);
         		$type_id.value(types);
         	}
        });
    }

	jQuery.getJSON(treeUrl, function (JsonReturnData) {
		$("#"+treeName).kendoTreeView({
	        dataSource: { data: JsonReturnData },
			checkboxes: {
				checkChildren: false,
				template: treeTemplate,
			},
	        loadOnDemand: false,
	        dataTextField: "text",
            dataValueField: "id",
            select: onSelect
	    });
	});


	$('#tradeOrderTypeListForm').submit(function(){
		validator.validate();
		if($('input[name=c_topologyTree]:checked').length == 0){
			$('#treeError').show();
			return false;
		}
	});
	
	
});