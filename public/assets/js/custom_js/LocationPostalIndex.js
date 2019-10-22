$(document).ready(function(){

	// $('#top_modal').modal("show");
	var tabstrip = $("#tabstrip").data("kendoTabStrip");	

	// Add New Tab in TabStripe

	$(document).on('click','#tabstrip > ul > .k-last', function(){

		var tabstrip = $("#tabstrip").data("kendoTabStrip");
		var tabCounter = tabstrip.items().length - 1;

		var tabTitle = 'Postal ' + tabCounter + ' <span data-type="remove" class="k-icon k-i-close"></span>';
		$postalTab = $("#demo").clone();

		// UPDATE LOCATION POSTAL ATTRIBUTE UPDATE

		$postalTab.find(".postaltab").attr("id","postal_"+tabCounter);
		$postalTab.find('#subpremise')
						.attr("id","subpremise_"+tabCounter)
						.attr("name","postals["+tabCounter+"][subpremise]");

		$postalTab.find('#premise')
						.attr("id","premise_"+tabCounter)
						.attr("name","postals["+tabCounter+"][premise]");

		$postalTab.find('#street_number')
						.attr("id","street_number_"+tabCounter)
						.attr("name","postals["+tabCounter+"][street_number]");
		
		$postalTab.find('#route')
						.attr("id","route_"+tabCounter)
						.attr("name","postals["+tabCounter+"][route]");

		$postalTab.find('#neighborhood')
						.attr("id","neighborhood_"+tabCounter)
						.attr("name","postals["+tabCounter+"][neighborhood]");

		$postalTab.find('#postcode')
						.attr("id","postcode_"+tabCounter)
						.attr("name","postals["+tabCounter+"][postcode]");

		$postalTab.find('#lat')
						.attr("id","lat_"+tabCounter)
						.attr("name","postals["+tabCounter+"][lat]");

		$postalTab.find('#lng')
						.attr("id","lng_"+tabCounter)
						.attr("name","postals["+tabCounter+"][lng]");

		$postalTab.find('#postal_id')
						.attr("id","postal_id_"+tabCounter)
						.attr("name","postals["+tabCounter+"][postal_id]").val(0);

		$postalTab.find('#list_id')
						.attr("id","list_id_"+tabCounter)
						.attr("name","postals["+tabCounter+"][list_id]").val(0);								
		
		// WORKING HOURS ATTRIBUTE UPDATE.

		$postalTab.find('#monday_open').attr("name","postals["+tabCounter+"][flexible_hours][0][0][open]");
		$postalTab.find('#monday_close').attr("name","postals["+tabCounter+"][flexible_hours][0][0][close]");
		$postalTab.find('#monday_status').attr("name","postals["+tabCounter+"][flexible_hours][0][0][status]");

		$postalTab.find('#tuesday_open').attr("name","postals["+tabCounter+"][flexible_hours][1][0][open]");
		$postalTab.find('#tuesday_close').attr("name","postals["+tabCounter+"][flexible_hours][1][0][close]");
		$postalTab.find('#tuesday_status').attr("name","postals["+tabCounter+"][flexible_hours][1][0][status]");

		$postalTab.find('#wednesday_open').attr("name","postals["+tabCounter+"][flexible_hours][2][0][open]");
		$postalTab.find('#wednesday_close').attr("name","postals["+tabCounter+"][flexible_hours][2][0][close]");
		$postalTab.find('#wednesday_status').attr("name","postals["+tabCounter+"][flexible_hours][2][0][status]");

		$postalTab.find('#thursday_open').attr("name","postals["+tabCounter+"][flexible_hours][3][0][open]");
		$postalTab.find('#thursday_close').attr("name","postals["+tabCounter+"][flexible_hours][3][0][close]");
		$postalTab.find('#thursday_status').attr("name","postals["+tabCounter+"][flexible_hours][3][0][status]");

		$postalTab.find('#friday_open').attr("name","postals["+tabCounter+"][flexible_hours][4][0][open]");
		$postalTab.find('#friday_close').attr("name","postals["+tabCounter+"][flexible_hours][4][0][close]");
		$postalTab.find('#friday_status').attr("name","postals["+tabCounter+"][flexible_hours][4][0][status]");

		$postalTab.find('#saturday_open').attr("name","postals["+tabCounter+"][flexible_hours][5][0][open]");
		$postalTab.find('#saturday_close').attr("name","postals["+tabCounter+"][flexible_hours][5][0][close]");
		$postalTab.find('#saturday_status').attr("name","postals["+tabCounter+"][flexible_hours][5][0][status]");

		$postalTab.find('#sunday_open').attr("name","postals["+tabCounter+"][flexible_hours][6][0][open]");
		$postalTab.find('#sunday_close').attr("name","postals["+tabCounter+"][flexible_hours][6][0][close]");
		$postalTab.find('#sunday_status').attr("name","postals["+tabCounter+"][flexible_hours][6][0][status]");


		// HOLIDAY WORKING HOURS ATTRIBUTE UPDATE

		$postalTab.find(".holiday")
					.addClass("holiday_0")
					.removeClass("holiday");

		$postalTab.find("#holiday_id")
					.attr("name","postals["+tabCounter+"][holiday_hours][0][0][holiday_id]")
					.html(holidayOptions);
		$postalTab.find("#holiday_date")
					.attr("name","postals["+tabCounter+"][holiday_hours][0][0][date]");
		$postalTab.find("#holiday_open")
					.attr("name","postals["+tabCounter+"][holiday_hours][0][0][open]");
		$postalTab.find("#holiday_close")
					.attr("name","postals["+tabCounter+"][holiday_hours][0][0][close]");
		$postalTab.find("#holiday_status")
					.attr("name","postals["+tabCounter+"][holiday_hours][0][0][status]");
		$postalTab.find(".addHolidayShift")
					.attr("data-tab-id",tabCounter)
					.attr("data-holiday-id",0);

		$postalTab.find("#order")
					.attr("id","order_"+tabCounter);
		$postalTab.find("#offer_delivery")
					.attr("name","postals["+tabCounter+"][offer_delivery]");
		$postalTab.find("#offer_collection")
					.attr("name","postals["+tabCounter+"][offer_collection]");
		$postalTab.find("#delivery_time")
					.attr("name","postals["+tabCounter+"][delivery_time]");
		$postalTab.find("#collection_time")
					.attr("name","postals["+tabCounter+"][collection_time]");
		$postalTab.find("#last_order_time")
					.attr("name","postals["+tabCounter+"][last_order_time]");
		$postalTab.find("#future_orders")
					.attr("name","postals["+tabCounter+"][future_orders]");
		$postalTab.find("#future_order_delivery_days")
					.attr("name","postals["+tabCounter+"][future_order_delivery_days]");
		$postalTab.find("#future_order_collection_days")
					.attr("name","postals["+tabCounter+"][future_order_collection_days]");																								

		$postalTab.find("#reservation")
					.attr("id","reservation_"+tabCounter);
		$postalTab.find("#reservation_time_interval")
					.attr("name","postals["+tabCounter+"][reservation_time_interval]");
		$postalTab.find("#reservation_stay_time")
					.attr("name","postals["+tabCounter+"][reservation_stay_time]");			

		$postalTab.find('.postal').hide();
		$postalTab.find('.postal-max').hide();
		$postalTab.find('.switch').addClass("hourstatus");		

	    tabstrip.insertBefore(
			{ 
				text: tabTitle,
				encoded: false,
				content: $postalTab.html()
			},
			tabstrip.tabGroup.children("li:last")
		);

	    postalInitialize(tabCounter); 
	    getLocationEventIntialize(tabCounter);
	    
	    // WORKING HOURS TAB FUNCTIONS
	    assignWorkingTimer(tabCounter);
    	assignWorkingToggleSwitch(tabCounter);
    	updateWorkingToggleValue(tabCounter);
    	assignHolidayToggleSwitch(tabCounter,0);
    	updateHolidayToggleValue(tabCounter,0);
	    addNewShift();

	    // HOLIDAY WORKING HOURS TAB FUNCTIONS
 		initHolidaySelect(tabCounter,0);
	    initHolidayDatePicker(tabCounter,0);    
	    initHolidayTimer(tabCounter,0);
	    assignHolidayDate(tabCounter,0);    
	    addNewHoliday();
	    addHolidayShift(); 
		
		// ORDER TAB FUNCTIONS
	    initOrderToggleSwitch(tabCounter); 
	    assignFutureOrder(tabCounter); 

	    // RESERVATION TAB
	    initSeatingSelect(tabCounter);
	    assignReservationSeating(tabCounter);

	    setTimeout(function(){
	    	tabstrip.select(tabCounter);
	    },1000);
	     
	});

	// Remove Tab From TabStripe

	tabstrip.tabGroup.on("click", "[data-type='remove']", function(e) {
        e.preventDefault();
        e.stopPropagation();

        var item = $(e.target).closest(".k-item");
        tabstrip.remove(item.index());
        tabstrip.select(item.index() - 1);
    });

	// First Time Initialize All Property To Controls
	postalInitialize(0);
	getLocationEventIntialize(0);

	assignWorkingTimer(0);  // FIRST POSTAL TAB
    assignWorkingToggleSwitch(0);  
    updateWorkingToggleValue(0); 
    assignHolidayToggleSwitch(0,0);
    updateHolidayToggleValue(0,0); 
    addNewShift();
    
    initHolidaySelect(0,0);
    initHolidayDatePicker(0,0);    
    initHolidayTimer(0,0);
    assignHolidayDate(0,0);    
    addNewHoliday();
    addHolidayShift();

    initOrderToggleSwitch(0);
    assignFutureOrder(0);
    initSeatingSelect(0);
    assignReservationSeating(0);    
    
});

// Update Form Submit
$(document).on('click','#updateLocation', function(){
	
	var locationValidator = $("#locationForm").kendoValidator({     
    }).data("kendoValidator");
    
	if (locationValidator.validate()) {
		$('.modal .preloader').show();
		$('.modal img').show();
		$.ajax({
			type 	: 'POST',
			data 	: $(constant.POSTAL_FORM_ID).serialize(),
			url 	: constant.ACTION_URL+constant.UPDATE_LOCATION_METHOD,

			success: function (json) {
				$(constant.POSTAL_FORM_ID).trigger('reset');
				$('#top_modal').modal("hide");
				$('.postal').hide();
				$('.postal-max').hide();

				$(constant.KENDO_GRID_ID).data("kendoGrid").dataSource.read();
				$('.modal .preloader').hide();
				$('.modal img').hide();
			}
		});
	}else{
		return false;
	}
});

function addNewShift() {

	$(".addShift").unbind( "click" );
	$(".addShift").on("click",function(){
		var currentPostalTab = $(this).parents().eq(10).attr("id").split("_");
		
		var currentDay = $(this).attr("data-id");
		var currentPostalTabID = currentPostalTab[1];
		var currentShift = $(this).parents().eq(2).children("div").length;		
		shiftHtml = '<div class="shift"><label class="col-sm-1 control-label text-right input-status"></label>';
	    shiftHtml += '<div class="col-sm-9">';
	    shiftHtml += '<div class="control-group control-group-3 group-margin">';
	    shiftHtml += '<div class="input-group" style="width: 29.1%; float: left;">';
	    shiftHtml += '<input type="text" name="postals['+currentPostalTabID+'][flexible_hours]['+currentDay+']['+currentShift+'][open]" class="form-control timeclock" value="10:00">';
	    shiftHtml += '<span class="input-group-addon"><i class="fa fa-clock-o"></i></span></div>';
	    shiftHtml += '<div class="input-group" style="width: 29.1%; margin-left: 16px; float: left;">';
	    shiftHtml += '<input type="text" name="postals['+currentPostalTabID+'][flexible_hours]['+currentDay+']['+currentShift+'][close]" class="form-control timeclock" value="23:00">';
	    shiftHtml += '<span class="input-group-addon"><i class="fa fa-clock-o"></i></span></div>';
	    shiftHtml += '<div class="btn-group btn-group-switch" data-toggle="buttons">';
	    shiftHtml += '<div class="make-switch col-sm-5" data-on="danger" data-off="default">';
	    shiftHtml += '<input type="checkbox" name="postals['+currentPostalTabID+'][flexible_hours]['+currentDay+']['+currentShift+'][status]" value="1" class="hourstatus" data-on-text="Open" data-off-text="Closed">';
	    shiftHtml += '</div></div>';
	    shiftHtml += '<a class="btn btn-danger" onclick="confirm(\'This can not be undone! Are you sure you want to do this?\') ? $(this).parent().parent().parent().remove() : false;" style="padding: 4px 6px; margin-left: 5px;"><i class="fa fa-times-circle"></i></a>';
	    shiftHtml += '</div></div></div>';

	    $(this).parents().eq(2).append(shiftHtml);
	    assignWorkingShiftTimer(currentPostalTabID,currentDay,currentShift);
	    assignWorkingShiftToggleSwitch(currentPostalTabID,currentDay,currentShift);
	    updateWorkingShiftToggleValue(currentPostalTabID,currentDay,currentShift);
	});
}

function addNewHoliday(){
	$(".addNewHoliday").unbind("click");
	$(".addNewHoliday").on("click",function(){
		var currentPostalTab = $(this).parents().eq(7).attr("id").split("_");
		var currentPostalTabID = currentPostalTab[1];
		var totalHoliday = $("#postal_"+currentPostalTabID+" .holidays").children("div").length;


		var holidayContent = $("#demo .holiday").clone();

		holidayContent.addClass("holiday_"+totalHoliday);
		holidayContent.removeClass("holiday");

		holidayContent.find("#holiday_id")
							.attr("name","postals["+currentPostalTabID+"][holiday_hours]["+totalHoliday+"][0][holiday_id]")
							.html(holidayOptions);
		holidayContent.find("#holiday_date")
							.attr("name","postals["+currentPostalTabID+"][holiday_hours]["+totalHoliday+"][0][date]");
		holidayContent.find("#holiday_open")
							.attr("name","postals["+currentPostalTabID+"][holiday_hours]["+totalHoliday+"][0][open]");
		holidayContent.find("#holiday_close")
							.attr("name","postals["+currentPostalTabID+"][holiday_hours]["+totalHoliday+"][0][close]");
		holidayContent.find("#holiday_status")
							.attr("name","postals["+currentPostalTabID+"][holiday_hours]["+totalHoliday+"][0][status]");
		holidayContent.find(".addHolidayShift")
							.attr("data-tab-id",currentPostalTabID)
							.attr("data-holiday-id",totalHoliday);

		$("#postal_"+currentPostalTabID+" .holidays").append(holidayContent);
		assignHolidayToggleSwitch(currentPostalTabID,totalHoliday);
    	updateHolidayToggleValue(currentPostalTabID,totalHoliday);
		initHolidaySelect(currentPostalTabID,totalHoliday);
    	initHolidayDatePicker(currentPostalTabID,totalHoliday);
    	initHolidayTimer(currentPostalTabID,totalHoliday);
    	assignHolidayDate(currentPostalTabID,totalHoliday);
    	addHolidayShift();
	});
}

function addHolidayShift(){
	$(".addHolidayShift").unbind( "click" );
	$(".addHolidayShift").on("click",function(){
				
		var currentPostalTabID = $(this).attr("data-tab-id");				
		var currentHoliday = $(this).attr("data-holiday-id");		
		var currentShift = $(this).parents().eq(4).children("div").length;

		shiftHtml = '<div class="form-group" style="clear: both;">';
	    shiftHtml += '<div class="col-sm-5">';
	    shiftHtml += '</div>';
	    shiftHtml += '<div class="holiday_info_'+currentShift+'">';
	    shiftHtml += '<div class="col-sm-6">';
	    shiftHtml += '<div class="control-group control-group-3 group-margin">';
	    shiftHtml += '<div class="input-group" style="width: 29.1%; float: left;">';
	    shiftHtml += '<input type="text" name="postals['+currentPostalTabID+'][holiday_hours]['+currentHoliday+']['+currentShift+'][open]" class="form-control timeclock" value="10:00">';
	    shiftHtml += '<span class="input-group-addon"><i class="fa fa-clock-o"></i></span>';
	    shiftHtml += '</div>';
	    shiftHtml += '<div class="input-group" style="width: 29.1%; margin-left: 16px; float: left;">';
	    shiftHtml += '<input type="text" name="postals['+currentPostalTabID+'][holiday_hours]['+currentHoliday+']['+currentShift+'][close]" class="form-control timeclock" value="10:00">';
	    shiftHtml += '<span class="input-group-addon"><i class="fa fa-clock-o"></i></span>';
	    shiftHtml += '</div>';
	    shiftHtml += '<div class="btn-group btn-group-switch" data-toggle="buttons" style="float: left;">';
	    shiftHtml += '<div class="make-switch col-sm-5" data-on="danger" data-off="default">';
	    shiftHtml += '<input type="checkbox" name="postals['+currentPostalTabID+'][holiday_hours]['+currentHoliday+']['+currentShift+'][status]" class="hourstatus" value="1" data-on-text="Open" data-off-text="Closed">';
	    shiftHtml += '</div>';
	    shiftHtml += '</div>';
	    shiftHtml += '<a class="btn btn-danger" onclick="confirm(\'This can not be undone! Are you sure you want to do this?\') ? $(this).parents().eq(3).remove() : false;" style="padding: 4px 6px;"><i class="fa fa-times-circle"></i></a>';
	    shiftHtml += '</div></div></div></div>';

	    $(this).parents().eq(4).append(shiftHtml);	    
	    initHolidayShiftTimer(currentPostalTabID,currentHoliday,currentShift);
	    assignHolidayShiftToggleSwitch(currentPostalTabID,currentHoliday,currentShift);
	    updateHolidayShiftToggleValue(currentPostalTabID,currentHoliday,currentShift);
	});
}	

function assignWorkingToggleSwitch(postalTabId){
	/* flexible opening hours start */
	$("#postal_"+postalTabId+" #opening-flexible .hourstatus").bootstrapSwitch('destroy');
	$("#postal_"+postalTabId+" #opening-flexible .hourstatus").each(function () {
        var statusval = $(this).val();
        if (statusval) {
        	$(this).bootstrapSwitch('state', true);
	    } else {
	        $(this).bootstrapSwitch('state', false);
	    }
    });	    
}

function updateWorkingToggleValue(postalTabId){
	$("#postal_"+postalTabId+" #opening-flexible .hourstatus").on('switchChange.bootstrapSwitch', function (event, state) {
	    if (state) {
	        $(this).val(1);
	    } else {
	        $(this).val(0);
	    }
	   event.preventDefault();
	});
}

function assignWorkingShiftToggleSwitch(postalTabId,dayId,shiftId){
	/* flexible opening hours start */
	$("#postal_"+postalTabId+" #opening-flexible .info_"+dayId).children().eq(shiftId).find(".hourstatus").bootstrapSwitch('destroy');
	$("#postal_"+postalTabId+" #opening-flexible .info_"+dayId).children().eq(shiftId).find(".hourstatus").each(function () {
        var statusval = $(this).val();
        if (statusval) {
        	$(this).bootstrapSwitch('state', true);
	    } else {
	        $(this).bootstrapSwitch('state', false);
	    }
    });	    
}

function updateWorkingShiftToggleValue(postalTabId,dayId,shiftId){
	$("#postal_"+postalTabId+" #opening-flexible .info_"+dayId).children().eq(shiftId).find(".hourstatus").on('switchChange.bootstrapSwitch', function (event, state) {
	    if (state) {
	        $(this).val(1);
	    } else {
	        $(this).val(0);
	    }
	   event.preventDefault();
	});
}

function assignHolidayToggleSwitch(postalTabId,holiday){
	/* flexible opening hours start */
	$("#postal_"+postalTabId+" #holiday-opening-flexible .holiday_"+holiday+" .hourstatus").bootstrapSwitch('destroy');
	$("#postal_"+postalTabId+" #holiday-opening-flexible .holiday_"+holiday+" .hourstatus").each(function () {
        var statusval = $(this).val();
        if (statusval) {
        	$(this).bootstrapSwitch('state', true);
	    } else {
	        $(this).bootstrapSwitch('state', false);
	    }
    });	    
}

function updateHolidayToggleValue(postalTabId,holiday){
	$("#postal_"+postalTabId+" #holiday-opening-flexible .holiday_"+holiday+" .hourstatus").on('switchChange.bootstrapSwitch', function (event, state) {
	    if (state) {
	        $(this).val(1);
	    } else {
	        $(this).val(0);
	    }
	   event.preventDefault();
	});
}

function assignHolidayShiftToggleSwitch(postalTabId,holiday,shiftId){
	/* flexible opening hours start */
	$("#postal_"+postalTabId+" #holiday-opening-flexible .holiday_"+holiday).children().eq(shiftId).find(".hourstatus").bootstrapSwitch('destroy');
	$("#postal_"+postalTabId+" #holiday-opening-flexible .holiday_"+holiday).children().eq(shiftId).find(".hourstatus").each(function () {
        var statusval = $(this).val();
        if (statusval) {
        	$(this).bootstrapSwitch('state', true);
	    } else {
	        $(this).bootstrapSwitch('state', false);
	    }
    });	    
}

function updateHolidayShiftToggleValue(postalTabId,holiday,shiftId){
	$("#postal_"+postalTabId+" #holiday-opening-flexible .holiday_"+holiday).children().eq(shiftId).find(".hourstatus").on('switchChange.bootstrapSwitch', function (event, state) {
	    if (state) {
	        $(this).val(1);
	    } else {
	        $(this).val(0);
	    }
	   event.preventDefault();
	});
}

function assignWorkingTimer(postalTabId){
	$("#postal_"+postalTabId+" #opening-flexible .timeclock").timeDropper({
        primaryColor: "#428bca",
        setCurrentTime: false,
        format: "HH:mm"
    });	    
}

function assignWorkingShiftTimer(postalTabId,dayId,shiftId){	
	$("#postal_"+postalTabId+" #opening-flexible .info_"+dayId).children().eq(shiftId).find(".timeclock").timeDropper({
        primaryColor: "#428bca",
        setCurrentTime: false,
        format: "HH:mm"
    });	    
}

function assignHolidayDate(postalTabId,holiday){	
	$("#postal_"+postalTabId+" #holiday-opening-flexible .holiday_"+holiday+" .holiday_id").on('change',function() {
	   $(this).parent().parent().find('.holiday_date').val($(this).children(":selected").attr("data-date"));
	});
}

function initHolidayDatePicker(postalTabId,holiday){
	$("#postal_"+postalTabId+" #holiday-opening-flexible .holiday_"+holiday+" .holiday_date").dateDropper({
        format: "Y-m-d",
        dropPrimaryColor: "#428bca"
    });
}

function initHolidaySelect(postalTabId,holiday){
	$("#postal_"+postalTabId+" #holiday-opening-flexible .holiday_"+holiday+" .select21").select2({
	   theme: "bootstrap",
	   placeholder: "Please Select Option",
	   width: '100%'
	});
}

function initHolidayTimer(postalTabId,holiday){
	$("#postal_"+postalTabId+" #holiday-opening-flexible .holiday_"+holiday+" .timeclock").timeDropper({
        primaryColor: "#428bca",
        setCurrentTime: false,
        format: "HH:mm"
    });	    
}

function initHolidayShiftTimer(postalTabId,holiday,shiftId){
	$("#postal_"+postalTabId+" #holiday-opening-flexible .holiday_"+holiday).children().eq(shiftId).find(".timeclock").timeDropper({
        primaryColor: "#428bca",
        setCurrentTime: false,
        format: "HH:mm"
    });	    
}

function initOrderToggleSwitch(postalTabId){
	/* flexible opening hours start */
	$("#postal_"+postalTabId+" .orderstatus").bootstrapSwitch('destroy');
	$("#postal_"+postalTabId+" .orderstatus").each(function () {
        var statusval = $(this).val();
        if (statusval) {
        	$(this).bootstrapSwitch('state', true);
	    } else {
	        $(this).bootstrapSwitch('state', false);
	    }
    });	    
}

function assignFutureOrder(postalTabId){
	$("input[name='postals["+postalTabId+"][future_orders]']").on('switchChange.bootstrapSwitch', function (event, state) {
	    if (state) {
	        $("input[name='postals["+postalTabId+"][future_orders]']").val(1);
	        $("#postal_"+postalTabId+" #future-orders-days").show();
	    } else {
	        $("input[name='postals["+postalTabId+"][future_orders]']").val(0);
	        $("#postal_"+postalTabId+" #future-orders-days").hide();
	    }
	   event.preventDefault();
	});
}

function initSeatingSelect(postalTabId){
	$("#postal_"+postalTabId+" .reservation_seating").select2({
	   theme: "bootstrap",
	   placeholder: "Please Select Option",
	   width: '100%'
	});
}

function assignReservationSeating(postalTabId){
	$("#postal_"+postalTabId+" .reservation_seating").on('change',function() {

		var currentPostalTab = $(this).parents().eq(9).attr("id").split("_");
		var currentPostalTabID = currentPostalTab[1];
	    
	    var tableId= $(this).val();
	    var tbl_class = "table-box"+tableId;
	    var tableName = $(this).children(":selected").text();
	    var minCapacity = $(this).children(":selected").attr("data-minimum");
	    var maxCapacity = $(this).children(":selected").attr("data-capacity");
	    var tableRow = '<tr class="'+tbl_class+'"><td class="name">'+tableName+'</td><td>'+minCapacity+'</td><td>'+maxCapacity+'</td><td class="img"><a class="btn btn-danger btn-xs" onclick="confirm(\'This can not be undone! Are you sure you want to remove this?\') ? $(this).parent().parent().remove() : false;"><i class="fa fa-times-circle"></i></a><input type="hidden" name="postals['+currentPostalTabID+'][tables][]" value="'+tableId+'"></td></tr>';
	    
	    if(!$('#postal_'+currentPostalTabID+' #table-box').find('.'+tbl_class).length) {
	        $('#postal_'+currentPostalTabID+' #table-box').find('tbody').append(tableRow);
	    }
	});
}	