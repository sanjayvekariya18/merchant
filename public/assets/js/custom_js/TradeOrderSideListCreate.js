$(document).ready(function (){
	var webUrl = $('#tradeOrderSideListForm').attr('action');
	var treeUrl = webUrl+"/getOrderSideListTree";

	$("#side_id").kendoMultiSelect({
		placeholder: "Select Order Types...",
		dataTextField: "side_type_name",
		dataValueField: "side_type_id",
		filter: "contains",
		height: 400,
		dataSource: {
			transport : {   
				read : {
					dataType : "json",
					url : webUrl+"/tradeSideType",
					type : "GET",
				}
			}
		},
	}).data("kendoMultiSelect");

	var validator = $("#tradeOrderSideListForm").kendoValidator({
		rules: {
			hasOrderSide: function (input) {
				if(input.is("[id=side_id")){
					var ms = input.data("kendoMultiSelect");       
					if(ms.value().length === 0){
						return false;
					}
				} 
				return true;
			}
		},
		messages: { 
			hasOrderSide: "Please select at least one Order Side" ,
		}
	}).data("kendoValidator");

	var treeName = "topologyTree";
	var resultId = "topologyTreeResultId";
	var treeTemplate = "# if(!item.hasChildren){# <input type='hidden' id='#=item.id#' parent_id='#=item.parent_id#' d_text='#=item.text#'/> <input type='radio' class='radioJsonTree' name_a='#= item.text #'  id_a='#= item.id #' name='c_"+treeName+"' value='#=item.text#' data-bind='checked:jsontree' />#}else{# <input type='hidden' id='#=item.id#' parent_id='#=item.parent_id#' d_text='#=item.text#'/> <input type='radio' class='radioJsonTree' name_a='#= item.text #' id_a='#= item.id #' name='c_"+treeName+"' value='#=item.text#' data-bind='checked:jsontree'  /> #}#";
		

	function onSelect(e) {
         nodeId = $("#"+treeName).data('kendoTreeView').dataItem(e.node).id;
         $("input[id_a='"+nodeId+"']").trigger('click');

         $("#"+resultId).val(nodeId);

         $side_id = $("#side_id").data("kendoMultiSelect");

         $.ajax({
         	type:'GET',
         	url:webUrl+"/getOrderSideList/"+nodeId,
         	dataType:'json',
         	success:function(json){

         		var sides = new Array();

         		if (json.length > 0){
         			$.each(json, function(index, obj) {
					    sides[index] = obj.side_id;
					});
         		}
         		$side_id.value(sides);
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


	$('#tradeOrderSideListForm').submit(function(){
		validator.validate();
		if($('input[name=c_topologyTree]:checked').length == 0){
			$('#treeError').show();
			return false;
		}
	});
	
	
});