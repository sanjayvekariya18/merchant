
var token = $('input[name="_token"]').val();
var requestUrl = $("#requestUrl").val();
var actionUrl = $("#actionUrl").val();

var orderTypeList = jQuery("#identityCityListGrid").kendoGrid({
		dataSource: {
			serverPaging: true,
			pageSize: 20,
			schema: {
				model: {
					list_id:"list_id",
					fields: {
						priority: {
							editable: true,
							type: "number",
							validation: { required: true,min: 0,max:99 }
						},
						status: {
							editable: true,
							defaultValue: { value: 1, text: "Enable"}
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
		selectable:'row',
		save:onSave,
		edit: function(e){
			var columnNotEditableIndex = [0,1,2,3,4,5,6];
			if($.inArray(parseInt(e.container.index()),columnNotEditableIndex) != -1){
				this.closeCell(); 
			}
		},
		columns: [
		{ 
			command: { text: "Edit", click: showDetails },
			title: " ",
			attributes: {
                style: "#=(!data.postal_lat || !data.postal_lng) ? 'background-color:red' : '' #"
              },
			width: "100px",
		},	{
			field: "list_id",
			title: "List Id",
			hidden:true,
		}, {
			field: "identity_code",
			title: "Identity Code",
		}, {
			field: "country_name",
			title: "Country",
		}, {
			field: "state_name",
			title: "State",
		}, {
			field: "county_name",
			title: "County",
		}, {
			field: "city_name",
			title: "City",
		}, {
			field: "priority",
			title: "Priority",
		}, {
			field: "status",
			title: "Status",
			editor: statusDropDownEditor,
			template: "#=(data.status)?'Enable':'Disable'#"
		}
		],
});

var kendoTreeUrl = actionUrl+"/getLocationTree";

$("#region_id").kendoMultiSelect({
        placeholder: "Enter City...",
        dataTextField: "region_name",
        dataValueField: "region_id",
        filter: "contains",
        height: 400,
        dataSource: {
            transport : {   
                read : {
                    dataType : "json",
                    url : actionUrl +"/getRegions",
                    type : "GET"
                }
            }
        },
        change: function(e) {
            
            kendoTree.dataSource.read();
            var values = this.value();

            if (values.length != 0) {
                $.each(values, function(i, nodeID) {

                    kendoTree.expandTo(nodeID);
                    var getitem = kendoTree.dataSource.get(nodeID);
                    var selectitem = kendoTree.findByUid(getitem.uid);
                    selectitem.find(':checkbox').prop("checked",true);
                });

            }else{

            }
        },
}).data("kendoMultiSelect");

// LOCATION CITY TREE VIEW.

var kendoTree = "";

jQuery.getJSON(kendoTreeUrl, function (JsonReturnData) {
	treeTemplate = "# if(item.level() > 0){# <input type='hidden' id='#=item.id#' parent_id='#=item.parent_id#' d_text='#=item.text#'/> <input type='checkbox' name_a='#= item.text #' id_a='#= item.id #' name='c_#= item.parent_id #' value='true' />#}else{# <input type='hidden' id='#=item.id#' parent_id='#=item.parent_id#' d_text='#=item.text#'/> #}#";
	$("#topologyTree").kendoTreeView({
		loadOnDemand: false,
		checkboxes: {
            checkChildren: true,
            template: treeTemplate,
        },
		dataSource: { data: JsonReturnData },
		loadOnDemand: false,
		dataTextField: "text",
		dataValueField: "id",
		select: function(e){
		    e.preventDefault();
		    var checkNodes = [];
		    var checkbox = $(e.node).find(":checkbox");

		    var checked = checkbox.prop("checked");
		    checkbox.prop("checked", !checked);
			
			$("#topologyTree"+" .k-item input[type=checkbox]:checked").each(function(){
				checkNodes.push($(this).attr('id_a'));
			});
			kendoRegion.value(checkNodes);
		},
		check: function(e){
			var checkNodes = [];
			$("#topologyTree"+" .k-item input[type=checkbox]:checked").each(function(){
				checkNodes.push($(this).attr('id_a'));
			});
			kendoRegion.value(checkNodes);
		}
	});
	kendoTree = $("#topologyTree").data("kendoTreeView");
});

var kendoGrid = $("#identityCityListGrid").data("kendoGrid");
var kendoRegion = $("#region_id").data("kendoMultiSelect");

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
		data:{list_id:list_id,key:key,value:value,_token:token},
		url: actionUrl+"/updateList",
		success: function (eventData) {
			$("#identityCityListGrid").data("kendoGrid").dataSource.read();
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

function showDetails(e) {
	e.preventDefault();

	$('.postal').hide();
	$('.postal-max').hide();
	$('.content .preloader').show();
	$('.content .preloader img').show();

	var tabstrip = $("#tabstrip").data("kendoTabStrip");
	tabstrip.remove("li:not(:first,:last)").select('li:first');

	var dataItem = this.dataItem($(e.currentTarget).closest("tr"));
	console.log(dataItem);
	$.ajax({
			data:{
				_token:token,
				identity_id:dataItem.identity_id,
				identity_table_id:dataItem.identity_table_id,
				location_city_id:dataItem.location_city_id
			},
			type: 'POST',
			dataType:'json',
			url: actionUrl+"/getLocationData",
			success: function (json) {

				$.each(json,function(jsonKey,jsonVal){
					
					$tabIndex = jsonKey+1;
					$parentId = "#tabstrip-"+$tabIndex;
					
					// console.log("Tab Index : "+$tabIndex);
					// console.log("Parent ID : "+$parentId);
					
					if(jsonKey > 0){
						$('.k-last').trigger("click");							
					}else{
						postalInitialize($tabIndex);
					}

					var postal_street_number 	= $("#street_number"+$tabIndex).data("kendoComboBox");
					var postal_route 			= $("#route"+$tabIndex).data("kendoComboBox");
					var postal_neighborhood 	= $("#neighborhood"+$tabIndex).data("kendoComboBox");
					var postal_postcode 		= $("#postcode"+$tabIndex).data("kendoComboBox");

					$($parentId+' #subpremise').val(jsonVal.postal_subpremise);
					$($parentId+' #premise').val(jsonVal.postal_premise);

					/*$("#country_id").data("kendoComboBox").value(jsonVal.country_id);
					$('#state_id').data("kendoComboBox").value(jsonVal.state_id);
					$('#county_id').data("kendoComboBox").value(jsonVal.county_id);
					$('#city_id').data("kendoComboBox").value(jsonVal.city_id);*/

					$('#countryName').text(jsonVal.country_name);
					$('#stateName').text(jsonVal.state_name);
					$('#countyName').text(jsonVal.county_name);
					$('#cityName').text(jsonVal.city_name);

					$($parentId+' #list_id').val(jsonVal.list_id);
					$($parentId+' #postal_id').val(jsonVal.postal_id);
					$('#city_name').val(jsonVal.city_name);
					$('#postal_code_max').val(jsonVal.postal_code_max);

					if(jsonVal.postal_id){
						
						postal_street_number.text(jsonVal.postal_street_number);
						postal_route.text(jsonVal.postal_route);
						postal_neighborhood.text(jsonVal.postal_neighborhood);
						if(jsonVal.postal_postcode){
							postal_postcode.text(jsonVal.postal_postcode.toString());
						}else{
							postal_postcode.value(-1);
						}

						$($parentId+' #lat').val(jsonVal.postal_lat);
						$($parentId+' #lng').val(jsonVal.postal_lng);
						$($parentId+' .postal').show();
					}
					

					if(jsonVal.postal_code_max){
						postal_postcode.text(jsonVal.postal_postcode);
						$($parentId+' .postal-max').show();
					}
				});

				$('.content .preloader').hide();
				$('.content .preloader img').hide();
				$('#top_modal').modal("show");
			}
	});
}

function postalInitialize(parentId) {
	$("#street_number"+parentId).kendoComboBox({
		dataTextField: "text",
		dataValueField: "value",
		dataSource: [{ text: "None", value: "-1" }]
	}).data("kendoComboBox");

	$("#route"+parentId).kendoComboBox({
		dataTextField: "text",
		dataValueField: "value",
		dataSource: [{ text: "None", value: "-1" }]
	}).data("kendoComboBox");

	$("#neighborhood"+parentId).kendoComboBox({
		dataTextField: "text",
		dataValueField: "value",
		dataSource: [{ text: "None", value: "-1" }]
	}).data("kendoComboBox");

	$("#postcode"+parentId).kendoComboBox({
		dataTextField: "text",
		dataValueField: "value",
		dataSource: [{ text: "None", value: "-1" }]
	}).data("kendoComboBox");	
}


// GET CITY LIST USING IDENTITY ID

function getCityList(identity_id,identity_table_id){

	$('.content .preloader').show();
	$('.content .preloader img').show();

	kendoGrid.dataSource.read();

	var cityListDataSource = new kendo.data.DataSource({
		pageSize: 20,
		transport : {   
			read : {
					data:{
						_token:token,
						identity_id:identity_id,
						identity_table_id:identity_table_id
					},
					url: actionUrl+"/getIdentityCityList",
					dataType: "json",
					type: "POST"
				}
			}
		});
	kendoGrid.setDataSource(cityListDataSource);

	$.ajax({

			type:'POST',
			data:{
				_token:token,
				identity_id:identity_id,
				identity_table_id:identity_table_id
			},
			url: actionUrl+"/getIdentityCityList",
			dataType:'json',
			
			success:function(json){
				var cities =[];
				$.each(json, function(key, value) {
					nodeID = value.location_city_id+"_city";
					cities.push(nodeID);
					kendoTree.expandTo(nodeID);
					var getitem = kendoTree.dataSource.get(nodeID);
					var selectitem = kendoTree.findByUid(getitem.uid);
					selectitem.find(':checkbox').prop("checked",true);
				});
				kendoRegion.value(cities);
			}
		});

		$('.content .preloader').hide();
		$('.content .preloader img').hide();
}


$(document).ready(function(){

	
	$("#tabstrip").kendoTabStrip().data("kendoTabStrip");
	var tabstrip = $("#tabstrip").data("kendoTabStrip");

	// CODE FOR SAVE IDENTITY CITY

	$("#submitLocationBtn").click(function(){
		
		$('.content .preloader').show();
		$('.content .preloader img').show();

		var identity_id = $("#identity_id").val();
		var identity_table_id = $("#identity_table_id").val();

		$.ajax({
			type: 'POST',
			data:$("#identityCityListForm").serialize(),
			url: actionUrl+"/insertIdentityCityList",
			success: function (data) {
				getCityList(identity_id,identity_table_id);
				$('.content .preloader').hide();
				$('.content .preloader img').hide();
			}
		});
	});

	// POSTAL INFORMATION CODE

	// Add New Tab in TabStripe

	$('.k-last').click(function(){
		
	    var tabCounter = tabstrip.items().length;

		var tabTitle = 'Postal ' + (tabCounter) + ' <span data-type="remove" class="k-icon k-i-close"></span>';
		
		$postalTab = $("#demo .tab-content").clone();

		$postalTab.find('#subpremise').attr("name","postals["+(tabCounter-1)+"][subpremise]");
		$postalTab.find('#premise').attr("name","postals["+(tabCounter-1)+"][premise]");

		$postalTab.find('#street_number')
						.attr("id","street_number"+(tabCounter));
		$postalTab.find('#postalStreetNumber')
						.attr("name","postals["+(tabCounter-1)+"][street_number]")
						.attr("id","postalStreetNumber"+(tabCounter));

		$postalTab.find('#route')
						.attr("id","route"+(tabCounter));
		$postalTab.find('#postalRoute')
						.attr("name","postals["+(tabCounter-1)+"][route]")
						.attr("id","postalRoute"+(tabCounter));

		$postalTab.find('#neighborhood')
						.attr("id","neighborhood"+(tabCounter));
		$postalTab.find('#postalNeighborhood')
						.attr("name","postals["+(tabCounter-1)+"][neighborhood]")
						.attr("id","postalNeighborhood"+(tabCounter));

		$postalTab.find('#postcode')
						.attr("id","postcode"+(tabCounter));
		$postalTab.find('#postalPostcode')
						.attr("name","postals["+(tabCounter-1)+"][postcode]")
						.attr("id","postalPostcode"+(tabCounter));
		
		$postalTab.find('#lat').attr("name","postals["+(tabCounter-1)+"][lat]");
		$postalTab.find('#lng').attr("name","postals["+(tabCounter-1)+"][lng]");

		$postalTab.find('#list_id').attr("name","postals["+(tabCounter-1)+"][list_id]").val(0);
		$postalTab.find('#postal_id').attr("name","postals["+(tabCounter-1)+"][postal_id]").val(0);

		$postalTab.find('.postal').hide();
		$postalTab.find('.postal-max').hide();

	    tabstrip.insertBefore(
			{ 
				text: tabTitle,
				encoded: false,
				content: $postalTab.html()
			},
			tabstrip.tabGroup.children("li:last")
		);
	    tabstrip.select(tabCounter - 1);
	    getLocationEventIntialize();
	    postalInitialize(tabCounter);
	});


	// Remove Tab From TabStripe

	tabstrip.tabGroup.on("click", "[data-type='remove']", function(e) {
	    e.preventDefault();
	    e.stopPropagation();

	    var item = $(e.target).closest(".k-item");
	    tabstrip.remove(item.index());
	    tabstrip.select(item.index() - 1);
	});

		
	function getLocationEventIntialize() {

		// Get Location Form Submit
		$('.getLocation').click(function(){

			$parentTab = $(this).parents('.k-content');
			$parentId = "#"+$parentTab.attr("id");
			$tabIndex = tabstrip.select().index() + 1;
			console.log($tabIndex);
			var postal_street_number 	= $("#street_number"+$tabIndex).data("kendoComboBox");
			var postal_route 			= $("#route"+$tabIndex).data("kendoComboBox");
			var postal_neighborhood 	= $("#neighborhood"+$tabIndex).data("kendoComboBox");
			var postal_postcode 		= $("#postcode"+$tabIndex).data("kendoComboBox");

			var postalMax = parseInt($('#postal_code_max').val());

			if (locationValidator.validate()) {
				$('.modal .preloader').show();
				$('.modal img').show();
				$.ajax({
					
					dataType: 'json',
					type 	: 'POST',
					data 	: {
								_token:token,
								city_name:$('#city_name').val(),
								postal_subpremise:$($parentId+" #subpremise").val(),
								postal_premise:$($parentId+" #premise").val()
							},
					url 	: actionUrl+"/getPostalAddress",

					success: function (json) {
						
						var street_number 	= [{ text: "None", value: -1 }];
						var route 			= [{ text: "None", value: -1 }];
						var neighborhood 	= [{ text: "None", value: -1 }];
						var postal_code 	= [{ text: "None", value: -1 }];

						$.each(json.street_number,function(skey,svalue){
							street_number.push({ text: svalue, value: skey });
						});
						$.each(json.route,function(rkey,rvalue){
							route.push({ text: rvalue, value: rkey });
						});
						$.each(json.neighborhood,function(nkey,nvalue){
							neighborhood.push({ text: nvalue, value: nkey });
						});
						if(postalMax){
							$.each(json.postal_code,function(pkey,pvalue){
								postal_code.push({ text: pvalue, value: pkey });
							});
							$($parentId+' .postal-max').show();
						}

						postal_street_number.setDataSource(street_number);
						postal_street_number.value(0);
						postal_route.setDataSource(route);
						postal_route.value(0);
						postal_neighborhood.setDataSource(neighborhood);
						postal_neighborhood.value(0);
						postal_postcode.setDataSource(postal_code);
						postal_postcode.value(-1);

						$($parentId+' #lat').val(json.lat);
						$($parentId+' #lng').val(json.lng);

						$($parentId+' .postal').show();

						$("#identityCityListGrid").data("kendoGrid").dataSource.read();
						$('.modal .preloader,.modal img').hide();
					}
				});
			}else{
				return false;
			}
		});
	}

	function setPostalValues(){
		var tabCounter = tabstrip.items().length;
		for (var i = 1; i < tabCounter; i++) {
			
			var psn 	= $("#street_number"+i).data("kendoComboBox");
			var pr 		= $("#route"+i).data("kendoComboBox");
			var pn 		= $("#neighborhood"+i).data("kendoComboBox");
			var pp 		= $("#postcode"+i).data("kendoComboBox");

			console.log(psn.text());

			$("#postalStreetNumber"+i).val(psn.text());
			$("#postalRoute"+i).val(pr.text());
			$("#postalNeighborhood"+i).val(pn.text());
			$("#postalPostcode"+i).val(pp.text());
		}
	}

	// Update Form Submit
	$('#updateLocation').click(function(){

		if (locationValidator.validate()) {
			setPostalValues();
			$('.modal .preloader').show();
			$('.modal img').show();
			$.ajax({
				type 	: 'POST',
				data 	: $("#locationForm").serialize(),
				url 	: actionUrl+"/updateLocation",

				success: function (json) {
					$("#locationForm").trigger('reset');
					$('#top_modal').modal("hide");
					$('.postal').hide();
					$('.postal-max').hide();

					$("#identityCityListGrid").data("kendoGrid").dataSource.read();
					$('.modal .preloader').hide();
					$('.modal img').hide();
				}
			});
		}else{
			return false;
		}
	});

	var locationValidator = $("#locationForm").kendoValidator({
		
	}).data("kendoValidator");

	getLocationEventIntialize();
	
});
