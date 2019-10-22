var constant = {
	TOKEN						: 	$("input[name=_token]").val(),
	ACTION_URL					: 	$('#venueForm').attr('action'),

	POSTAL_FORM_ID 				: 	"#venueForm",
    UPDATE_LOCATION_METHOD		: 	"/updateLocation",
    GET_POSTAL_METHOD			: 	"/getPostalAddress",

    GET_LIST_METHOD_NAME		: 	"/getVenues",
    UPDATE_VENUE_METHOD 		: 	"/updateVenue",
    
    KENDO_GRID_ID				: 	"#venueGrid",
    KENDO_GRID_PRIMARY_ID		: 	"venue_id",

    COUNTRY_METHOD_NAME		 	: 	"/getCountries",
    STATE_METHOD_NAME		 	: 	"/getStates",
    COUNTY_METHOD_NAME		 	: 	"/getCounties",
    CITY_METHOD_NAME		 	: 	"/getCities",  
}

$(document).ready(function(){

	var TOKEN = $('input[name=_token]').val();
	var requestUrl = $("#requestUrl").val();

	$("#tabstrip").kendoTabStrip().data("kendoTabStrip");
	var tabstrip = $("#tabstrip").data("kendoTabStrip");

	// Add New Tab in TabStripe

	$('.k-last').click(function(){
		
		var tabCounter = tabstrip.items().length;

		var tabTitle = 'Venue ' + (tabCounter) + ' <span data-type="remove" class="k-icon k-i-close"></span>';
		
		$postalTab = $("#demo .tab-content").clone();

		$postalTab.find('#venue_name').attr("name","postals["+(tabCounter-1)+"][venue_name]");
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

		$postalTab.find('#country')
						.attr("id","country"+(tabCounter))
						.attr("name","postals["+(tabCounter-1)+"][country]");

		$postalTab.find('#state')
						.attr("id","state"+(tabCounter))
						.attr("name","postals["+(tabCounter-1)+"][state]");

		$postalTab.find('#county')
						.attr("id","county"+(tabCounter))
						.attr("name","postals["+(tabCounter-1)+"][county]");

		$postalTab.find('#city')
						.attr("id","city"+(tabCounter))
						.attr("name","postals["+(tabCounter-1)+"][city]");
		
		$postalTab.find('#lat').attr("name","postals["+(tabCounter-1)+"][lat]");
		$postalTab.find('#lng').attr("name","postals["+(tabCounter-1)+"][lng]");

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

	function onSave(data) {
		var venue_id = data.model.venue_id;
		var key="";
		var value=0;
		
		if(typeof data.values.venue_name !== 'undefined'){
			key = "identity_name";
			value = data.values.venue_name;
		}else{
			return false;
		}

		$.ajax({
			type: 'POST',
			data:{venue_id:venue_id,key:key,value:value,_token:constant.TOKEN},
			url: constant.ACTION_URL+constant.UPDATE_VENUE_METHOD,
			success: function (eventData) {
				$(constant.KENDO_GRID_ID).data("kendoGrid").dataSource.read();
			}
		});
	}

	var venueList = jQuery(constant.KENDO_GRID_ID).kendoGrid({
		dataSource: {
			serverPaging: true,
			pageSize: 20,
			transport : {   
				read : {
						url: constant.ACTION_URL+constant.GET_LIST_METHOD_NAME,
						dataType: "json",
						type: "GET"
					}
			},
			schema: {
				total:'total',
	            data:'hase_venues',
				model: {
					venue_id:constant.KENDO_GRID_PRIMARY_ID
				}
			}
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
			var columnNotEditableIndex = [0,2,3,4,5];
			if($.inArray(parseInt(e.container.index()),columnNotEditableIndex) != -1){
				this.closeCell(); 
			}
		},
		columns: [
		{ 
			command: { text: "Edit", click: editVenue },
			title: " ", 
			width: "100px"
		},{
			field: constant.KENDO_GRID_PRIMARY_ID,
			title: "Venue Id",
			hidden:true,
		}, {
			field: "venue_name",
			title: "Venue Name",
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
		}
		],
	});

	function editVenue(e) {
		e.preventDefault();
		var dataItem = this.dataItem($(e.currentTarget).closest("tr"));
		var venue_id = dataItem.venue_id;
		jQuery.ajax({
            type: "POST",
            url: requestUrl+"/getVenue",
            data: {
                venue_id: venue_id,
                _token:TOKEN
            },
            cache: false,
            success: function(venuedata) {
            	venue = jQuery.parseJSON(venuedata);
                $("#venue_name").val(venue.venue_name);
                $("#subpremise").val(venue.postal_subpremise);
                $("#premise").val(venue.postal_premise);

                $(".editvenue").css('display','block');
                
                $("#street_number1").data("kendoComboBox").value(venue.postal_street_number);
                $("#route1").data("kendoComboBox").value(venue.postal_route);
                $("#neighborhood1").data("kendoComboBox").value(venue.postal_neighborhood);
                $("#lat").val(venue.postal_lat);
                $("#lng").val(venue.postal_lng);
                $("#country1").data("kendoComboBox").value(venue.country_id);
                $("#country1").data("kendoComboBox").trigger("change");
                $("#state1").data("kendoComboBox").value(venue.state_id);
                $("#state1").data("kendoComboBox").trigger("change");
                $("#county1").data("kendoComboBox").value(venue.county_id);
                $("#county1").data("kendoComboBox").trigger("change");
                $("#city1").data("kendoComboBox").value(venue.city_id);
                
                $("#venueSave").html("Update Venue");
                $(".addVenueTab").css('display','none');
                $("#venueForm").append("<input type='hidden' name='postals[0][postal_id]' value='"+venue.postal_id+"'>");
                $("#venueForm").append("<input type='hidden' name='postals[0][identity_id]' value='"+venue.identity_id+"'>");

            }
        });		
	}


	var kendoGrid = $(constant.KENDO_GRID_ID).data("kendoGrid");
	
	function getLocationEventIntialize() {

		// Get Location Form Submit
		$('.getLocation').click(function(){

			$parentTab = $(this).parents('.k-content');
			$parentId = "#"+$parentTab.attr("id");
			$tabIndex = tabstrip.select().index() + 1;
			var postal_street_number 	= $("#street_number"+$tabIndex).data("kendoComboBox");
			var postal_route 			= $("#route"+$tabIndex).data("kendoComboBox");
			var postal_neighborhood 	= $("#neighborhood"+$tabIndex).data("kendoComboBox");
			var postal_postcode 		= $("#postcode"+$tabIndex).data("kendoComboBox");

			var postal_country 		= $("#country"+$tabIndex).data("kendoComboBox");
			var postal_state 		= $("#state"+$tabIndex).data("kendoComboBox");
			var postal_county 		= $("#county"+$tabIndex).data("kendoComboBox");
			var postal_city 		= $("#city"+$tabIndex).data("kendoComboBox");

			var postalMax = parseInt($('#postal_code_max').val());

			if (locationValidator.validate()) {
				$('.venueLoader .preloader,.venueLoader img').show();
				$.ajax({
					
					dataType: 'json',
					type 	: 'POST',
					data 	: {
								_token:constant.TOKEN,
								city_name:$('#city_name').val(),
								postal_subpremise:$($parentId+" #subpremise").val(),
								postal_premise:$($parentId+" #premise").val()
							},
					url 	: constant.ACTION_URL+constant.GET_POSTAL_METHOD,

					success: function (json) {
						
						var street_number 	= [{ text: "None", value: -1 }];
						var route 			= [{ text: "None", value: -1 }];
						var neighborhood 	= [{ text: "None", value: -1 }];
						var postal_code 	= [{ text: "None", value: -1 }];

						var country = [{ text: "None", value: 0 }];
						var state 	= [{ text: "None", value: 0 }];
						var county 	= [{ text: "None", value: 0 }];
						var city 	= [{ text: "None", value: 0 }];

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

						if(json.cityInfo){
							postal_country.value(json.cityInfo.country_id);
							postal_state.value(json.cityInfo.state_id);
							postal_county.value(json.cityInfo.county_id);
							postal_city.value(json.cityInfo.city_id);
						}else{
							postal_country.value("");
							postal_state.value("");
							postal_county.value("");
							postal_city.value("");
						}

						$($parentId+' #lat').val(json.lat);
						$($parentId+' #lng').val(json.lng);

						$($parentId+' .postal').show();

						$('.venueLoader .preloader,.venueLoader img').hide();
					}
				});
			}else{
				return false;
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

		$("#country"+parentId).kendoComboBox({
			placeholder: "Select Country...",
			dataTextField: "country_name",
			dataValueField: "country_id",
			filter: "contains",
			height: 400,
			value:0,
			dataSource: {
				transport : {   
					read : {
						dataType : "json",
						url : constant.ACTION_URL+constant.COUNTRY_METHOD_NAME,
						type : "GET",
					}
				}
			},
			change: function(e) {

				var state_id = $("#state"+parentId).data("kendoComboBox");
				var county_id = $("#county"+parentId).data("kendoComboBox");
				var city_id = $("#city"+parentId).data("kendoComboBox");
				
				var country_id = this.value();

				state_id.value("");
				county_id.value("");
				city_id.value("");
				
				if(country_id != ""){
					var requestUrl = constant.ACTION_URL+constant.STATE_METHOD_NAME+"?id="+country_id;
				}

				var stateDataSource = new kendo.data.DataSource({
					transport : {   
						read : {
								url: requestUrl,
								dataType: "json",
								type: "GET"
							}
						}
					});
				state_id.setDataSource(stateDataSource);
			}
		}).data("kendoComboBox");

		$("#state"+parentId).kendoComboBox({
			placeholder: "Select State...",
			dataTextField: "state_name",
			dataValueField: "state_id",
			filter: "contains",
			height: 400,
			value:0,
			dataSource: {
				transport : {   
					read : {
						dataType : "json",
						url : constant.ACTION_URL+constant.STATE_METHOD_NAME,
						type : "GET",
					}
				}
			},
			change: function(e) {

				var county_id = $("#county"+parentId).data("kendoComboBox");
				var city_id = $("#city"+parentId).data("kendoComboBox");
				var state_id = this.value();
				
				county_id.value("");
				city_id.value("");

				if(state_id != ""){
					var requestUrl = constant.ACTION_URL+constant.COUNTY_METHOD_NAME+"?id="+state_id;
				}

				var stateDataSource = new kendo.data.DataSource({
					transport : {   
						read : {
								url: requestUrl,
								dataType: "json",
								type: "GET"
							}
						}
					});
				county_id.setDataSource(stateDataSource);
			}
		}).data("kendoComboBox");

		$("#county"+parentId).kendoComboBox({
			placeholder: "Select County...",
			dataTextField: "county_name",
			dataValueField: "county_id",
			filter: "contains",
			height: 400,
			value:0,
			dataSource: {
				transport : {   
					read : {
						dataType : "json",
						url : constant.ACTION_URL+constant.COUNTY_METHOD_NAME,
						type : "GET",
					}
				}
			},
			change: function(e) {
				var city_id = $("#city"+parentId).data("kendoComboBox");
				var county_id = this.value();

				city_id.value("");
				
				if(county_id != ""){
					var requestUrl = constant.ACTION_URL+constant.CITY_METHOD_NAME+"?id="+county_id;
				}

				var cityDataSource = new kendo.data.DataSource({
					transport : {   
						read : {
								url: requestUrl,
								dataType: "json",
								type: "GET"
							}
						}
					});
				city_id.setDataSource(cityDataSource);
			}
		}).data("kendoComboBox");

		$("#city"+parentId).kendoComboBox({
			placeholder: "Select City...",
			dataTextField: "city_name",
			dataValueField: "city_id",
			filter: "contains",
			height: 400,
			value:0,
			dataSource: {
				transport : {   
					read : {
						dataType : "json",
						url : constant.ACTION_URL+constant.CITY_METHOD_NAME,
						type : "GET",
					}
				}
			},
		}).data("kendoComboBox");
	}

	function setPostalValues(){
		var tabCounter = tabstrip.items().length;
		for (var i = 1; i < tabCounter; i++) {
			
			var psn 	= $("#street_number"+i).data("kendoComboBox");
			var pr 		= $("#route"+i).data("kendoComboBox");
			var pn 		= $("#neighborhood"+i).data("kendoComboBox");
			var pp 		= $("#postcode"+i).data("kendoComboBox");

			$("#postalStreetNumber"+i).val(psn.text());
			$("#postalRoute"+i).val(pr.text());
			$("#postalNeighborhood"+i).val(pn.text());
			$("#postalPostcode"+i).val(pp.text());
		}
	}

	// Update Form Submit
	$('#venueSave').click(function(){

		if (locationValidator.validate()) {
			setPostalValues();
			$('.venueLoader .preloader,.venueLoader img').show();
			$.ajax({
				type 	: 'POST',
				data 	: $(constant.POSTAL_FORM_ID).serialize(),
				url 	: constant.ACTION_URL+constant.UPDATE_LOCATION_METHOD,

				success: function (json) {
					$(constant.POSTAL_FORM_ID).trigger('reset');
					$('.postal').hide();
					$('.postal-max').hide();
					tabstrip.remove("li:not(:first,:last)").select('li:first');
					$(constant.KENDO_GRID_ID).data("kendoGrid").dataSource.read();
					$("#venueSave").html("Add Venue");
					$('input[name="postals[0][postal_id]"]').remove();
					$('input[name="postals[0][identity_id]"]').remove();
					$(".addVenueTab").css('display','inline-block');
					$('.venueLoader .preloader,.venueLoader img').hide();
				}
			});
		}else{
			return false;
		}
	});

	var locationValidator = $(constant.POSTAL_FORM_ID).kendoValidator({
		
	}).data("kendoValidator");

	getLocationEventIntialize();
	postalInitialize(1);
});