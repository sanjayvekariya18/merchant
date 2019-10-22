$(".select21").select2({
   theme: "bootstrap",
   placeholder: "Please Select Option",
   width: '100%'
});

$("#location_city").on('change',function() {
    $('#county_id option[value="'+$(this).children(":selected").attr("data-county")+'"]').prop('selected', true);
    $('#location_state option[value="'+$(this).children(":selected").attr("data-state")+'"]').prop('selected', true);
    $('#location_country_id option[value="'+$(this).children(":selected").attr("data-country")+'"]').prop('selected', true);

    var cityName = $("#location_city option:selected").text();
    var countyName = $("#county_id option:selected").text();
    var stateName = $("#location_state option:selected").text();
    $('#location_city_name').val(cityName);
    $('#location_county_name').val(countyName);
    $('#location_state_name').val(stateName);
    $("#location_country_id").select2({
        theme: "bootstrap",
        placeholder: "Select Country"
    });
    $("#county_id").select2({
        theme: "bootstrap",
        placeholder: "Select County"
    });
    $("#location_state").select2({
        theme: "bootstrap",
        placeholder: "Select State"
    });
});

var telephoneMin = 8;
var telephoneMax = 8;
var telephoneDigit = telephoneMin - 1;
var telephoneregex = new RegExp("[2-9]{1}\\d{" + telephoneDigit + "}");

var requestUrl = $("#requestUrl").val();
var token = $('input[name="_token"]').val();
$("#location_country_id").on('change', function() {
    var countryId = $(this).val();
    if(countryId == 96) {
        $("#location_postcode").parent().parent().hide();
        $("label.locationCity").text('Neighborhood');
        $("label.locationCounty").text('District');
        $("label.locationState").text('Territories');
    }
    else {
        $("#location_postcode").parent().parent().show();
        $("label.locationCity").text('City');
        $("label.locationCounty").text('County');
        $("label.locationState").text('State');
    }
    $('.countryPhoneCode').text($(this).children(":selected").attr("data-phonecode"));
    /*telephoneMin = $(this).children(":selected").attr("data-telephonemin");
    telephoneMax = $(this).children(":selected").attr("data-telephonemax");
    telephoneDigit = telephoneMin - 1;
    telephoneregex = new RegExp("[2-9]{1}\\d{" + telephoneDigit + "}");*/
    $('#location_city').html("");
    $.ajax({
        type:'POST',
        data:{_token:token,country_id:countryId},
        dataType:"json",
        url : requestUrl+"/getCity",
        error:function(xhr,status,error) {
            console.log(error);
        },
        success:function(locationCity,status,xhr) {
            $('#location_city').append("<option></option>");
            $.each(locationCity, function(i,value) {
                $('#location_city').append("<option value='"+value['city_id']+"' data-county='"+value['county_id']+"' data-state='"+value['state_id']+"' data-country='"+value['country_id']+"'>"+value['city_name']+"</option>");
            });
        }
    });
    $('#county_id').html("");
    $.ajax({
        type:'POST',
        data:{_token:token,country_id:countryId},
        dataType:"json",
        url : requestUrl+"/getCounty",
        error:function(xhr,status,error) {
            console.log(error);
        },
        success:function(locationCounty,status,xhr) {
            $('#county_id').append("<option></option>");
            $.each(locationCounty, function(i,value) {
                $('#county_id').append("<option value='"+value['county_id']+"'>"+value['county_name']+"</option>");
            });
        }
    });
    $('#location_state').html("");
    $.ajax({
        type:'POST',
        data:{_token:token,country_id:countryId},
        dataType:"json",
        url : requestUrl+"/getState",
        error:function(xhr,status,error) {
            console.log(error);
        },
        success:function(locationState,status,xhr) {
            $('#location_state').append("<option></option>");
            $.each(locationState, function(i,value) {
                $('#location_state').append("<option value='"+value['state_id']+"'>"+value['state_name']+"</option>");
            });
        }
    });
    $("#location_city").select2({
        theme: "bootstrap",
        placeholder: "Select City"
    });
    $("#county_id").select2({
        theme: "bootstrap",
        placeholder: "Select County"
    });
    $("#location_state").select2({
        theme: "bootstrap",
        placeholder: "Select State"
    });
});

$("#location_state").on('change', function() {
    var stateId = $(this).val();
    $('#location_city').html("");
    $.ajax({
        type:'POST',
        data:{_token:token,state_id:stateId},
        dataType:"json",
        url : requestUrl+"/getCity",
        error:function(xhr,status,error) {
            console.log(error);
        },
        success:function(locationCity,status,xhr) {
            $('#location_city').append("<option></option>");
            $.each(locationCity, function(i,value) {
                $('#location_city').append("<option value='"+value['city_id']+"' data-county='"+value['county_id']+"' data-state='"+value['state_id']+"' data-country='"+value['country_id']+"'>"+value['city_name']+"</option>");
            });
        }
    });

    $('#county_id').html("");
    $.ajax({
        type:'POST',
        data:{_token:token,state_id:stateId},
        dataType:"json",
        url : requestUrl+"/getCounty",
        error:function(xhr,status,error) {
            console.log(error);
        },
        success:function(locationCounty,status,xhr) {
            $('#county_id').append("<option></option>");
            $.each(locationCounty, function(i,value) {
                $('#county_id').append("<option value='"+value['county_id']+"'>"+value['county_name']+"</option>");
            });
        }
    });
});

$("#county_id").on('change', function() {
    var countyId = $(this).val();
    $('#location_city').html("");
    $.ajax({
        type:'POST',
        data:{_token:token,county_id:countyId},
        dataType:"json",
        url : requestUrl+"/getCity",
        error:function(xhr,status,error) {
            console.log(error);
        },
        success:function(locationCity,status,xhr) {
            console.log(locationCity);
            $('#location_city').append("<option></option>");
            $.each(locationCity, function(i,value) {
                $('#location_city').append("<option value='"+value['city_id']+"' data-county='"+value['county_id']+"' data-state='"+value['state_id']+"' data-country='"+value['country_id']+"'>"+value['city_name']+"</option>");
            });
        }
    });
});

$("#merchant_type").on('change', function() {
    var merchantTypeId = $(this).val();
    if(merchantTypeId == 2) {
        $('.reservationTab').hide();
        //$("#reservation_time_interval").parent().parent().hide();
    }
    else {
        $('.reservationTab').show();
        //$("#reservation_time_interval").parent().parent().show();
    }
});

$(".holiday_id").on('change',function() {
    $(this).parent().parent().find('.holiday_0_date').val($(this).children(":selected").attr("data-date"));
});

$("#location_tables").on('change',function() {
    var tableId= $(this).val();
    var tbl_class = "table-box"+tableId;
    var tableName = $(this).children(":selected").text();
    var minCapacity = $(this).children(":selected").attr("data-minimum");
    var maxCapacity = $(this).children(":selected").attr("data-capacity");
    var tableRow = '<tr class="'+tbl_class+'"><td class="name">'+tableName+'</td><td>'+minCapacity+'</td><td>'+maxCapacity+'</td><td class="img"><a class="btn btn-danger btn-xs" onclick="confirm(\'This can not be undone! Are you sure you want to remove this?\') ? $(this).parent().parent().remove() : false;"><i class="fa fa-times-circle"></i></a><input type="hidden" name="tables[]" value="'+tableId+'"></td></tr>';
    
    if(!$('#table-box').find('.'+tbl_class).length) {
        $('#table-box').find('tbody').append(tableRow);
    }
});

$(function () {
    $(".to-labelauty").labelauty({minimum_width: "135px"});

    $("#location_address_1").focusout(function() {
        $('.to-labelauty').prop('checked', true);
    });

    $('#request_coordinates').click(function() {
        var locationAddress = $('#location_address_1').val();
        var locationCityName = $('#location_city_name').val();
        var locationCountyName = $('#location_county_name').val();
        if(!$(this).is(':checked')) {
            $.ajax({
                type:'POST',
                data:{_token:token,location_address_1:encodeURIComponent(locationAddress),location_city_name:encodeURIComponent(locationCityName),location_county_name:encodeURIComponent(locationCountyName)},
                url : requestUrl+"/getCoordinates",
                error:function(xhr,status,error) {
                    console.log(error);
                },
                success:function(locationCoordinates,status,xhr) {
                    var latitudeLongitude = locationCoordinates.split(',');
                    $('#location_lat').val(latitudeLongitude[0]);
                    $('#location_lng').val(latitudeLongitude[1]);
                    $('#latitudeLongitude').show();
                }
            });
        }
    });

    $("#merchant_type").on('change',function() {
        var selectedType = $(this).children(":selected").text();
        var optionHtml = $("#"+selectedType+"Types").html();
        $("#merchant_type_id").children('option').remove();
        $("#merchant_type_id").append(optionHtml);
        $("#merchant_type_id").select2({
           theme: "bootstrap",
           placeholder: "Please Select Option",
           width: '100%'
        });
    });

    var selectedItems = new Array();
    var typeIndex = 0;
    $("#merchant_type_id").on("select2:select", function (e) {
        var lastSelectedItem = e.params.data.id;
        if(lastSelectedItem == 6) {
            var timeInterval = jQuery('#reservation_time_interval').val();
            if(timeInterval == 0 || timeInterval == null) {
                jQuery('#reservation_time_interval').val(15);
            }
        }
        selectedItems[typeIndex] = lastSelectedItem;
        typeIndex = typeIndex + 1;
    });
    $("#merchant_type_id").on("select2:unselect", function (e) {
        var lastSelectedItem = e.params.data.id;
        if(lastSelectedItem == 6) {
            var timeInterval = jQuery('#reservation_time_interval').val();
            if(timeInterval != 0) {
                jQuery('#reservation_time_interval').val(0);
            }
        }
        selectedItems = jQuery.grep(selectedItems, function(value) {
            return value != lastSelectedItem;
        });
        typeIndex = typeIndex - 1;
    });
    
    $("#reservation_time_interval").focusout(function() {
        var currentSelected = jQuery('.merchantTypeId').val();
        var timeInterval = jQuery('#reservation_time_interval').val();
        if(timeInterval != 0) {
            selectedItems[typeIndex] = "6";
            $(".merchantTypeId").val(selectedItems).trigger('change');
            typeIndex = typeIndex + 1;
        }
        if(timeInterval == 0) {
            var removeReservations = 6;
            selectedItems = jQuery.grep(selectedItems, function(value) {
                return value != removeReservations;
            });
            $(".merchantTypeId").val(selectedItems).trigger('change');
            typeIndex = typeIndex - 1;
        }
    });
    
    $("#location_postcode").parent().parent().hide();
    $("#location_tables_form").bootstrapValidator({
        excluded: [':disabled'],
        fields: {
            location_name: {
                validators: {
                    notEmpty: {
                        message: 'The Name field is required.'
                    }
                },
                required: true,
                minlength: 3
            },
            location_telephone: {
                validators: {
                    regexp: {
                        //regexp: telephoneregex,
                        regexp: /^[23569][0-9]{7}$/,
                        message: 'The telephone number must contain 8 digits and start with 2,3,5,6,9.'
                    }
                }
            },
            location_email: {
                validators: {
                    /*notEmpty: {
                        message: 'The email address is required'
                    },*/
                    regexp: {
                        regexp: /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/,
                        message: 'Please enter valid email format'
                    }
                }
            },
            live_image_url: {
                validators: {
                    regexp: {
                        regexp: /\.(gif|jpg|jpeg|tiff|png)$/i,
                        message: 'The live image URL must end with .jpg, .jpeg or .png'
                    },
                    uri: {
                        message: 'The live image URL is not valid'
                    }
                }
            },
            /*location_address_1: {
                validators: {
                    notEmpty: {
                        message: 'The Address 1 field is required.'
                    }
                }
            },*/
        }
    });
});

var currentShift = 1;
function addNewShift(currentDay) {
    shiftHtml = '<div class="shift"><label class="col-sm-2 control-label text-right"></label>';
    shiftHtml += '<div class="col-sm-10" style="margin-top: 10px;">';
    shiftHtml += '<div class="control-group control-group-3">';
    shiftHtml += '<div class="input-group" style="width: 30.1%; float: left;">';
    shiftHtml += '<input type="text" name="flexible_hours['+currentDay+']['+currentShift+'][open]" id="flexible_open'+currentShift+'" class="form-control" value="10:00">';
    shiftHtml += '<span class="input-group-addon"><i class="fa fa-clock-o"></i></span></div>';
    shiftHtml += '<div class="input-group" style="width: 30.1%; margin-left: 16px; float: left;">';
    shiftHtml += '<input type="text" name="flexible_hours['+currentDay+']['+currentShift+'][close]" id="flexible_close'+currentShift+'" class="form-control" value="23:00">';
    shiftHtml += '<span class="input-group-addon"><i class="fa fa-clock-o"></i></span></div>';
    shiftHtml += '<div class="btn-group btn-group-switch" data-toggle="buttons">';
    shiftHtml += '<div class="make-switch col-sm-5" data-on="danger" data-off="default">';
    shiftHtml += '<input type="checkbox" name="flexible_hours['+currentDay+']['+currentShift+'][status]" id="flexible_hours_'+currentDay+'_status_'+currentShift+'" value="1" data-on-text="Open" data-off-text="Closed">';
    shiftHtml += '</div></div>';
    shiftHtml += '<a class="btn btn-danger" onclick="confirm(\'This can not be undone! Are you sure you want to do this?\') ? $(this).parent().parent().parent().remove() : false;" style="padding: 4px 6px; margin-left: 5px;"><i class="fa fa-times-circle"></i></a>';
    shiftHtml += '</div></div></div>';

    $('.multipleShift'+currentDay).append(shiftHtml);

    $("#flexible_open"+currentShift).timeDropper({
        primaryColor: "#428bca",
        setCurrentTime: false,
        format: "HH:mm"
    });
    $("#flexible_close"+currentShift).timeDropper({
        primaryColor: "#428bca",
        setCurrentTime: false,
        format: "HH:mm"
    });
    
    var mondayStatusValue = $('#flexible_hours_'+currentDay+'_status_'+currentShift).val();
    if (mondayStatusValue == 1) {
        $('#flexible_hours_'+currentDay+'_status_'+currentShift).bootstrapSwitch('state', true);
    } else {
        $('#flexible_hours_'+currentDay+'_status_'+currentShift).bootstrapSwitch('state', false);
    }
    currentShift++;
}

function holidayChange() {
    $(".holiday_id").on('change',function() {
        $(this).parent().parent().find('.holiday_0_date').val($(this).children(":selected").attr("data-date"));
    });
}
holidayChange();
// holiday hour start
var currentHoliday = 1;
function addNewHoliday() {

    $holidayClone = $('.holiday-clone').clone();
    $holidayClone.find('select').attr('name',"holiday_hours["+currentHoliday+"][0][holiday_id]");
    $holidayClone.find('select').addClass('select21');
    var nextHoliday = currentHoliday + 1;
    holidayHtml = '<div class="form-group">';
    holidayHtml += '<div class="col-sm-2">';
    holidayHtml += $holidayClone.html();
    holidayHtml += '</div>';
    
    holidayHtml += '<div class="holidayShift'+currentHoliday+'">';
    holidayHtml += '<div class="col-sm-10">';
    holidayHtml += '<div class="control-group control-group-3">';
    holidayHtml += '<div class="input-group" style="width:25.3%; float:left;">';
    holidayHtml += '<input type="text" class="holiday_0_date form-control date_disable" name="holiday_hours['+currentHoliday+'][0][date]" placeholder="YYYY-MM-DD"/>';
    holidayHtml += '<div class="input-group-addon"><i class="fa fa-fw fa-calendar"></i></div></div>';
    holidayHtml += '<div class="input-group" style="width:22.1%; float:left; margin-left:12px;">';
    holidayHtml += '<input type="text" name="holiday_hours['+currentHoliday+'][0][open]" class="holiday_0_open form-control" value="10:00">';
    holidayHtml += '<span class="input-group-addon"><i class="fa fa-clock-o"></i></span></div>';
    holidayHtml += '<div class="input-group" style="width:22.1%; margin-left: 12px; float: left;">';
    holidayHtml += '<input type="text" name="holiday_hours['+currentHoliday+'][0][close]" class="holiday_0_close form-control" value="23:00">';
    holidayHtml += '<span class="input-group-addon"><i class="fa fa-clock-o"></i></span></div>';
    holidayHtml += '<div class="btn-group btn-group-switch" data-toggle="buttons">';
    holidayHtml += '<div class="make-switch col-sm-5" data-on="danger" data-off="default">';
    holidayHtml += '<input type="checkbox" name="holiday_hours['+currentHoliday+'][0][status]" id="holiday_'+currentHoliday+'_status" value="1" data-on-text="Open" data-off-text="Closed">';
    holidayHtml += '</div></div>';
    holidayHtml += '<a class="btn btn-primary btn-lg" onclick="addNewHolidayShift('+currentHoliday+');" style="padding: 3px 5px; margin-left: 3px;">';
    holidayHtml += '<i class="fa fa-plus"></i></a>';
    holidayHtml += '</div></div></div></div>';

    $('#holiday-hours').append(holidayHtml);

    $(".select21").select2({
       theme: "bootstrap",
       placeholder: "Please Select Option",
       width: '100%'
    });
    $(".holiday_0_date").dateDropper({
        format: "Y-m-d",
        dropPrimaryColor: "#428bca"
    });
    $(".holiday_0_open").timeDropper({
        primaryColor: "#428bca",
        setCurrentTime: false,
        format: "HH:mm"
    });
    $(".holiday_0_close").timeDropper({
        primaryColor: "#428bca",
        setCurrentTime: false,
        format: "HH:mm"
    });
    var holidayStatusData = $('#holiday_'+currentHoliday+'_status').val();
    if (holidayStatusData == 1) {
        $('#holiday_'+currentHoliday+'_status').bootstrapSwitch('state', true);
    } else {
        $('#holiday_'+currentHoliday+'_status').bootstrapSwitch('state', false);
    }
    currentHoliday++;
    holidayChange();
}

var currentHolidayShift = 1;
function addNewHolidayShift(currentDay) {
    //shiftHtml = '<div class="holiday-shift">';
    shiftHtml = '<div class="col-sm-10" style="margin-top: 1%; margin-left: 37%;">';
    shiftHtml += '<div class="control-group control-group-3">';
    shiftHtml += '<div class="input-group" style="width: 22.1%; float: left; margin-left: 12px;">';
    shiftHtml += '<input type="text" name="holiday_hours['+currentDay+']['+currentHolidayShift+'][open]" class="holiday_0_open form-control" value="10:00">';
    shiftHtml += '<span class="input-group-addon"><i class="fa fa-clock-o"></i></span></div>';
    shiftHtml += '<div class="input-group" style="width: 22.1%; margin-left: 12px; float: left;">';
    shiftHtml += '<input type="text" name="holiday_hours['+currentDay+']['+currentHolidayShift+'][close]" class="holiday_0_close form-control" value="23:00">';
    shiftHtml += '<span class="input-group-addon"><i class="fa fa-clock-o"></i></span></div>';
    shiftHtml += '<div class="btn-group btn-group-switch" data-toggle="buttons">';
    shiftHtml += '<div class="make-switch col-sm-5" data-on="danger" data-off="default">';
    shiftHtml += '<input type="checkbox" name="holiday_hours['+currentDay+']['+currentHolidayShift+'][status]" id="holiday_hours_'+currentDay+'_status_'+currentHolidayShift+'" value="1" data-on-text="Open" data-off-text="Closed">';
    shiftHtml += '</div></div>';
    shiftHtml += '<a class="btn btn-danger" onclick="confirm(\'This can not be undone! Are you sure you want to do this?\') ? $(this).parent().parent().remove() : false;" style="padding: 4px 6px; margin-left: 4px;"><i class="fa fa-times-circle"></i></a>';
    shiftHtml += '</div></div>';

    $('.holidayShift'+currentDay).append(shiftHtml);
    $(".holiday_0_open").timeDropper({
        primaryColor: "#428bca",
        setCurrentTime: false,
        format: "HH:mm"
    });
    $(".holiday_0_close").timeDropper({
        primaryColor: "#428bca",
        setCurrentTime: false,
        format: "HH:mm"
    });
    var holidayStatusValue = $('#holiday_hours_'+currentDay+'_status_'+currentHolidayShift).val();
    if (holidayStatusValue == 1) {
        $('#holiday_hours_'+currentDay+'_status_'+currentHolidayShift).bootstrapSwitch('state', true);
    } else {
        $('#holiday_hours_'+currentDay+'_status_'+currentHolidayShift).bootstrapSwitch('state', false);
    }
    currentHolidayShift++;
}
// holiday hour end

var image_row = 1;
var readNumber;
function addImageToGallery() {
    readNumber = image_row;
    html = '<tr id="gallery-image' + image_row + '">';
    html += '   <td class="action action-one"><a class="btn btn-danger" onclick="confirm(\'This can not be undone! Are you sure you want to do this?\') ? $(this).parent().parent().remove() : false;"><i class="fa fa-times-circle"></i></a></td>';
    html += '<td><input name="gallery[images][' + image_row + '][name]" type="file" class="form-control" onchange="readURL(this,readNumber);"/></td>';
    html += '   <td><img id="galleryImage' + image_row + '" src="#" class="image-thumb img-responsive" />'
        + '<input type="hidden" id="image-thumb' + image_row + '" name="gallery[images][' + image_row + '][path]" value=""></td>';
    html += '   <td><span class="name' + image_row + '"></span><input type="hidden" class="image-name" id="image-name' + image_row + '" name="gallery[images][' + image_row + '][name]" value=""></td>';
    html += '   <td><input type="text" name="gallery[images][' + image_row + '][alt_text]" class="form-control" value="" /></td>';
    html += '   <td class="text-center"><div class="btn-group btn-group-toggle" data-toggle="buttons">';
    html += '       <label class="btn btn-default active"><input type="radio" name="gallery[images][' + image_row + '][status]" checked="checked"value="0">Included</label>';
    html += '       <label class="btn btn-danger"><input type="radio" name="gallery[images][' + image_row + '][status]" value="1">Excluded</label>';
    html += '   </div></td>';
    html += '</tr>';

    $('#gallery-images .table-sortable tbody').append(html);
    image_row++;
}

function readURL(input,imageRow) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
            $('#galleryImage'+imageRow)
                .attr('src', e.target.result)
                .width(50)
                .height(35);
        };
        reader.readAsDataURL(input.files[0]);
    }
    $('span.name'+imageRow).text(input.files[0].name);
}

$(function () {
    var autoLatLngValue = $('#auto_lat_lng').val();
    if (autoLatLngValue == 1) {
        $('#auto_lat_lng').bootstrapSwitch('state', true);
    } else {
        $('#auto_lat_lng').bootstrapSwitch('state', false);
    }

    var locationStatusValue = $('#location_status').val();
    if (locationStatusValue == 1) {
        $('#location_status').bootstrapSwitch('state', true);
    } else {
        $('#location_status').bootstrapSwitch('state', false);
    }

    var offerDeliveryValue = $('#offer_delivery').val();
    if (offerDeliveryValue == 1) {
        $('#offer_delivery').bootstrapSwitch('state', true);
    } else {
        $('#offer_delivery').bootstrapSwitch('state', false);
    }

    var offerCollectionValue = $('#offer_collection').val();
    if (offerCollectionValue == 1) {
        $('#offer_collection').bootstrapSwitch('state', true);
    } else {
        $('#offer_collection').bootstrapSwitch('state', false);
    }

    var futureOrdersValue = $('#future_orders').val();
    if (futureOrdersValue == 1) {
        $('#future_orders').bootstrapSwitch('state', true);
        jQuery('#future-orders-days').css('display', 'block');
    } else {
        $('#future_orders').bootstrapSwitch('state', false);
        jQuery('#future-orders-days').css('display', 'none');
    }

    $('#delivery-hours-daily').hide();
    $('input[type=radio][name=delivery_type]').change(function() {
        if(this.value == 0) {
            $('#delivery-hours-daily').hide();
        }
        else if(this.value == 1) {
            $('#delivery-hours-daily').show();
        }
    });

    $('#collection-hours-daily').hide();
    $('input[type=radio][name=collection_type]').change(function() {
        if(this.value == 0) {
            $('#collection-hours-daily').hide();
        }
        else if(this.value == 1) {
            $('#collection-hours-daily').show();
        }
    });

    $("#opentime").timeDropper({
        primaryColor: "#428bca",
        setCurrentTime: false,
        format: "HH:mm"
    });
    $("#closetime").timeDropper({
        primaryColor: "#428bca",
        setCurrentTime: false,
        format: "HH:mm"
    });

    $("#deliveryopentime").timeDropper({
        primaryColor: "#428bca",
        setCurrentTime: false,
        format: "HH:mm"
    });
    $("#deliveryclosetime").timeDropper({
        primaryColor: "#428bca",
        setCurrentTime: false,
        format: "HH:mm"
    });

    $("#collectionopentime").timeDropper({
        primaryColor: "#428bca",
        setCurrentTime: false,
        format: "HH:mm"
    });
    $("#collectionclosetime").timeDropper({
        primaryColor: "#428bca",
        setCurrentTime: false,
        format: "HH:mm"
    });

    /* flexible opening hours start */
    var flexible_hours_0 = $('#flexible_hours_0_status').val();
    if (flexible_hours_0 == 1) {
        $('#flexible_hours_0_status').bootstrapSwitch('state', true);
    } else {
        $('#flexible_hours_0_status').bootstrapSwitch('state', false);
    }

    var flexible_hours_1 = $('#flexible_hours_1_status').val();
    if (flexible_hours_1 == 1) {
        $('#flexible_hours_1_status').bootstrapSwitch('state', true);
    } else {
        $('#flexible_hours_1_status').bootstrapSwitch('state', false);
    }

    var flexible_hours_2 = $('#flexible_hours_2_status').val();
    if (flexible_hours_2 == 1) {
        $('#flexible_hours_2_status').bootstrapSwitch('state', true);
    } else {
        $('#flexible_hours_2_status').bootstrapSwitch('state', false);
    }

    var flexible_hours_3 = $('#flexible_hours_3_status').val();
    if (flexible_hours_3 == 1) {
        $('#flexible_hours_3_status').bootstrapSwitch('state', true);
    } else {
        $('#flexible_hours_3_status').bootstrapSwitch('state', false);
    }

    var flexible_hours_4 = $('#flexible_hours_4_status').val();
    if (flexible_hours_4 == 1) {
        $('#flexible_hours_4_status').bootstrapSwitch('state', true);
    } else {
        $('#flexible_hours_4_status').bootstrapSwitch('state', false);
    }

    var flexible_hours_5 = $('#flexible_hours_5_status').val();
    if (flexible_hours_5 == 1) {
        $('#flexible_hours_5_status').bootstrapSwitch('state', true);
    } else {
        $('#flexible_hours_5_status').bootstrapSwitch('state', false);
    }

    var flexible_hours_6 = $('#flexible_hours_6_status').val();
    if (flexible_hours_6 == 1) {
        $('#flexible_hours_6_status').bootstrapSwitch('state', true);
    } else {
        $('#flexible_hours_6_status').bootstrapSwitch('state', false);
    }

    $("#flexible_monday_open").timeDropper({
        primaryColor: "#428bca",
        setCurrentTime: false,
        format: "HH:mm"
    });
    $("#flexible_monday_close").timeDropper({
        primaryColor: "#428bca",
        setCurrentTime: false,
        format: "HH:mm"
    });


    $("#flexible_tuesday_open").timeDropper({
        primaryColor: "#428bca",
        setCurrentTime: false,
        format: "HH:mm"
    });
    $("#flexible_tuesday_close").timeDropper({
        primaryColor: "#428bca",
        setCurrentTime: false,
        format: "HH:mm"
    });

    $("#flexible_wednesday_open").timeDropper({
        primaryColor: "#428bca",
        setCurrentTime: false,
        format: "HH:mm"
    });
    $("#flexible_wednesday_close").timeDropper({
        primaryColor: "#428bca",
        setCurrentTime: false,
        format: "HH:mm"
    });

    $("#flexible_thursday_open").timeDropper({
        primaryColor: "#428bca",
        setCurrentTime: false,
        format: "HH:mm"
    });
    $("#flexible_thursday_close").timeDropper({
        primaryColor: "#428bca",
        setCurrentTime: false,
        format: "HH:mm"
    });

    $("#flexible_friday_open").timeDropper({
        primaryColor: "#428bca",
        setCurrentTime: false,
        format: "HH:mm"
    });
    $("#flexible_friday_close").timeDropper({
        primaryColor: "#428bca",
        setCurrentTime: false,
        format: "HH:mm"
    });

    $("#flexible_saturday_open").timeDropper({
        primaryColor: "#428bca",
        setCurrentTime: false,
        format: "HH:mm"
    });
    $("#flexible_saturday_close").timeDropper({
        primaryColor: "#428bca",
        setCurrentTime: false,
        format: "HH:mm"
    });

    $("#flexible_sunday_open").timeDropper({
        primaryColor: "#428bca",
        setCurrentTime: false,
        format: "HH:mm"
    });
    $("#flexible_sunday_close").timeDropper({
        primaryColor: "#428bca",
        setCurrentTime: false,
        format: "HH:mm"
    });
    /* flexible opening hours end */

    /* holiday hours start */
    $("#holiday_0_date").dateDropper({
        format: "Y-m-d",
        dropPrimaryColor: "#428bca"
    });

    $("#holiday_0_open").timeDropper({
        primaryColor: "#428bca",
        setCurrentTime: false,
        format: "HH:mm"
    });
    $("#holiday_0_close").timeDropper({
        primaryColor: "#428bca",
        setCurrentTime: false,
        format: "HH:mm"
    });

    $(".holiday_0_date").dateDropper({
        format: "Y-m-d",
        dropPrimaryColor: "#428bca"
    });
    $(".holiday_0_open").timeDropper({
        primaryColor: "#428bca",
        setCurrentTime: false,
        format: "HH:mm"
    });
    $(".holiday_0_close").timeDropper({
        primaryColor: "#428bca",
        setCurrentTime: false,
        format: "HH:mm"
    });

    var holidayStatus = $('#holiday_0_status').val();
    if (holidayStatus == 1) {
        $('#holiday_0_status').bootstrapSwitch('state', true);
    } else {
        $('#holiday_0_status').bootstrapSwitch('state', false);
    }
    /* holiday hours end */
});

/* flexible opening hours start */
$("input[name='flexible_hours[0][0][status]']").on('switchChange.bootstrapSwitch', function (event, state) {
    if (state) {
        jQuery("#flexible_hours_0_status").val(1);
    } else {
        jQuery("#flexible_hours_0_status").val(0);
    }
    event.preventDefault();
});

$("input[name='flexible_hours[1][status]']").on('switchChange.bootstrapSwitch', function (event, state) {
    if (state) {
        jQuery("#flexible_hours_1_status").val(1);
    } else {
        jQuery("#flexible_hours_1_status").val(0);
    }
    event.preventDefault();
});

$("input[name='flexible_hours[2][status]']").on('switchChange.bootstrapSwitch', function (event, state) {
    if (state) {
        jQuery("#flexible_hours_2_status").val(1);
    } else {
        jQuery("#flexible_hours_2_status").val(0);
    }
    event.preventDefault();
});

$("input[name='flexible_hours[3][status]']").on('switchChange.bootstrapSwitch', function (event, state) {
    if (state) {
        jQuery("#flexible_hours_3_status").val(1);
    } else {
        jQuery("#flexible_hours_3_status").val(0);
    }
    event.preventDefault();
});

$("input[name='flexible_hours[4][status]']").on('switchChange.bootstrapSwitch', function (event, state) {
    if (state) {
        jQuery("#flexible_hours_4_status").val(1);
    } else {
        jQuery("#flexible_hours_4_status").val(0);
    }
    event.preventDefault();
});

$("input[name='flexible_hours[5][status]']").on('switchChange.bootstrapSwitch', function (event, state) {
    if (state) {
        jQuery("#flexible_hours_5_status").val(1);
    } else {
        jQuery("#flexible_hours_5_status").val(0);
    }
    event.preventDefault();
});

$("input[name='flexible_hours[6][status]']").on('switchChange.bootstrapSwitch', function (event, state) {
    if (state) {
        jQuery("#flexible_hours_6_status").val(1);
    } else {
        jQuery("#flexible_hours_6_status").val(0);
    }
    event.preventDefault();
});
/* flexible opening hours end */

$("input[name='auto_lat_lng']").on('switchChange.bootstrapSwitch', function (event, state) {
    if (state) {
        jQuery("#auto_lat_lng").val(1);
        jQuery('#latitudeLongitude').css('display', 'none');
    } else {
        jQuery("#auto_lat_lng").val(0);
        jQuery('#latitudeLongitude').css('display', 'block');
    }
   event.preventDefault();
});

$("input[name='location_status']").on('switchChange.bootstrapSwitch', function (event, state) {
    if (state) {
        jQuery("#location_status").val(1);
    } else {
        jQuery("#location_status").val(0);
    }
   event.preventDefault();
});

$("input[name='offer_delivery']").on('switchChange.bootstrapSwitch', function (event, state) {
    if (state) {
        jQuery("#offer_delivery").val(1);
    } else {
        jQuery("#offer_delivery").val(0);
    }
   event.preventDefault();
});

$("input[name='offer_collection']").on('switchChange.bootstrapSwitch', function (event, state) {
    if (state) {
        jQuery("#offer_collection").val(1);
    } else {
        jQuery("#offer_collection").val(0);
    }
   event.preventDefault();
});

$("input[name='future_orders']").on('switchChange.bootstrapSwitch', function (event, state) {
    if (state) {
        jQuery("#future_orders").val(1);
        jQuery('#future-orders-days').css('display', 'block');
    } else {
        jQuery("#future_orders").val(0);
        jQuery('#future-orders-days').css('display', 'none');
    }
   event.preventDefault();
});

$(document).on('click', '.btn-add-condition', function() {
    var panelRow = $(this).attr('data-panel-row');
    var tableRow = $(this).attr('data-table-row');

    tableRow++;
    addDeliveryAreaCondition(panelRow, tableRow);

    $(this).attr('data-table-row', tableRow);
});

$(document).on('change', '#delivery-areas select.form-control', function() {
    $(this).parent().parent().find('input.total').attr('disabled', false);
    if (this.value == 'all') {
        $(this).parent().parent().find('input.total').val('0');
        $(this).parent().parent().find('input.total').attr('disabled', true);
    }
});

$('#delivery-areas select.form-control').trigger('change');

var panel_row = 1;
function addDeliveryArea() {
    var table_row = '1';

    html  = '<div id="delivery-area' + panel_row + '" class="panel panel-default">';
    html += '   <div class="panel-heading collapsed" data-toggle="collapse" data-target="#delivery-area' + panel_row + ' .collapse">';
    html += '       <div class="area-toggle"><i class="fa fa-angle-double-down up"></i><i class="fa fa-angle-double-up down"></i></div>';
    html += '       <div class="area-name">&nbsp;&nbsp; Area  ' + panel_row + '</div>';
    html += '       <div class="area-buttons pull-right"><a title="Edit"><i class="fa fa-pencil"></i></a> &nbsp;&nbsp; <a class="btn-times area-remove" title="Remove" onClick="confirm(\'This can not be undone! Are you sure you want to do this?\') ? $(this).parent().parent().parent().remove() : false;"><i class="fa fa-times-circle"></i></a></div>';
    html += '   </div>';
    html += '   <div class="collapse">';
    html += '   <div class="panel-body">';
    html += '       <div class="form-group">';
    html += '           <label for="" class="col-sm-5 control-label" style="font-weight: 700;">Area Name</label>';
    html += '           <div class="col-sm-7 wrap-none wrap-right">';
    html += '               <input type="text" name="delivery_areas[' + panel_row + '][name]" id="" class="form-control" value="Area ' + panel_row + '" />';
    html += '           </div>';
    html += '       </div>';
    html += '       <div class="form-group">';
    html += '           <label for="" class="col-sm-12 control-label" style="font-weight: 700;">Delivery Condition';
    html += '               <span class="help-block">Use the table below to set different delivery charge conditions. EX. xx delivery charge on order total above xx. Use 0 delivery charge for free delivery or to apply on all orders.</span>';
    html += '           </label>';
    html += '           <div class="col-sm-12">';
    html += '               <div class="table-responsive wrap-none">';
    html += '                   <table class="table table-striped table-border table-sortable">';
    html += '                       <thead>';
    html += '                       <tr>';
    html += '                           <th class="action action-one"></th>';
    html += '                           <th>Charge</th>';
    html += '                           <th>Condition</th>';
    html += '                           <th>Total</th>';
    html += '                       </tr>';
    html += '                       </thead>';
    html += '                       <tbody>';
    html += '                       <tr id="panel-row-' + panel_row + '-table-row-' + table_row + '">';
    html += '                           <td class="action action-one handle">';
    html += '                               <a class="btn btn-danger btn-xs" onclick="confirm(\'This cannot be undone! Are you sure you want to do this?\') ? $(this).parent().parent().remove() : false;">';
    html += '                                   <i class="fa fa-times-circle"></i>';
    html += '                               </a>';
    html += '                           </td>';
    html += '                           <td>';
    html += '                               <input type="text" name="delivery_areas[' + panel_row + '][charge][' + table_row + '][amount]" class="form-control input-sm charge" value="0" />';
    html += '                           </td>';
    html += '                           <td>';
    html += '                               <select name="delivery_areas[' + panel_row + '][charge][' + table_row + '][condition]" class="form-control input-sm">';
                                                    html += '                                       <option value="all">on all orders</option>';
                                                    html += '                                       <option value="above">on order total above</option>';
                                                    html += '                               </select>';
    html += '                           </td>';
    html += '                           <td>';
    html += '                               <input type="text" name="delivery_areas[' + panel_row + '][charge][' + table_row + '][total]" class="form-control input-sm total" value="0" />';
    html += '                           </td>';
    html += '                       </tr>';
    html += '                       </tbody>';
    html += '                       <tfoot>';
    html += '                       <tr id="tfoot">';
    html += '                           <td class="action action-one text-center"><a class="btn btn-primary btn-xs btn-add-condition" data-panel-row="' + panel_row + '" data-table-row="' + table_row + '"><i class="fa fa-plus"></i></a></td>';
    html += '                           <td></td>';
    html += '                           <td></td>';
    html += '                           <td></td>';
    html += '                       </tr>';
    html += '                       </tfoot>';
    html += '                   </table>';
    html += '               </div>';
    html += '           </div>';
    html += '       </div>';
    html += '   </div>';
    html += '   </div>';
    html += '</div>';

    $('#delivery-areas').append(html);
    panel_row++;
}

function addDeliveryAreaCondition(panelRow, tableRow) {
    html = '<tr id="panel-row-' + panelRow + '-table-row-' + tableRow + '">';
    html += '   <td class="action action-one handle">';
    html += '       <a class="btn btn-danger btn-xs" onclick="confirm(\'This can not be undone! Are you sure you want to do this?\') ? $(this).parent().parent().remove() : false;">';
    html += '           <i class="fa fa-times-circle"></i>';
    html += '       </a>';
    html += '   </td>';
    html += '   <td>';
    html += '       <input type="text" name="delivery_areas[' + panelRow + '][charge][' + tableRow + '][amount]" class="form-control input-sm charge" value="0" />';
    html += '   </td>';
    html += '   <td>';
    html += '       <select name="delivery_areas[' + panelRow + '][charge][' + tableRow + '][condition]" class="form-control input-sm">';
        html += '               <option value="all">on all orders</option>';
        html += '               <option value="above">on order total above</option>';
        html += '       </select>';
    html += '   </td>';
    html += '   <td>';
    html += '       <input type="text" name="delivery_areas[' + panelRow + '][charge][' + tableRow + '][total]" class="form-control input-sm total" disabled="disabled" value="0" />';
    html += '   </td>';
    html += '</tr>';

    $('#delivery-area' + panelRow + ' .table-sortable tbody').append(html);
}

