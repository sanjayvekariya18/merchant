$(document).ready(function () {

    $(".select21").select2({
        theme: "bootstrap",
        placeholder: "Please Select Option",
    });
    
	$("#hase_exhibition_edit").bootstrapValidator({
        fields: {
            exhibition_name: {
                validators: {
                    notEmpty: {
                        message: 'The Exhibition Name is required'
                    }
                },
            },
            exhibition_venue: {
                validators: {
                    notEmpty: {
                        message: 'The Exhibition Venue is required'
                    }
                },
            },
        }
    }).on('focusout', '[name="exhibition_date_start"]', function() {
            var isEmpty = $(this).val() != '';
            $('#hase_exhibition_edit')
                    .bootstrapValidator('enableFieldValidators', 'exhibition_date_start', !isEmpty);

            // Revalidate the field when user start typing in the password field
            if ($(this).val().length == 1) {
                $('#hase_exhibition_edit').bootstrapValidator('validateField', 'exhibition_date_start');
            }
    }).on('focusout', '[name="exhibition_date_end"]', function() {
            var isEmpty = $(this).val() != '';
            $('#hase_exhibition_edit')
                    .bootstrapValidator('enableFieldValidators', 'exhibition_date_end', !isEmpty);

            // Revalidate the field when user start typing in the password field
            if ($(this).val().length == 1) {
                $('#hase_exhibition_edit')
                    .bootstrapValidator('validateField', 'exhibition_date_end');
            }
    });;
	
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
});

var nextHoliday=parseInt(jQuery("#exhibitionDays").val())+1;
function addNewHolidayShift() {
    shiftHtml = '<div class="holidayShift'+nextHoliday+'" style="margin-top:10px">';
    shiftHtml += '<div class="col-sm-1" ><a class="btn btn-danger" onclick="confirm(\'This can not be undone! Are you sure you want to do this?\') ? $(this).parent().parent().remove() : false;" style="padding: 4px 6px;"><i class="fa fa-times-circle"></i></a></div>';
    shiftHtml += '<div class="col-sm-3" ><div class="input-group">';
    shiftHtml += '<input type="text" name="holiday_hours['+nextHoliday+'][date]" class="form-control holiday_0_date" placeholder="YYYY-MM-DD"/>';
    shiftHtml += '<div class="input-group-addon"><i class="fa fa-fw fa-calendar"></i></div></div></div>';
    shiftHtml += '<div class="col-sm-3" ><div class="input-group">';
    shiftHtml += '<input type="text" name="holiday_hours['+nextHoliday+'][open]" class="form-control holiday_0_open" value="10:00">';
    shiftHtml += '<span class="input-group-addon"><i class="fa fa-clock-o"></i></span></div></div>';
    shiftHtml += '<div class="col-sm-3" ><div class="input-group">';
    shiftHtml += '<input type="text" name="holiday_hours['+nextHoliday+'][close]" class="form-control holiday_0_close" value="23:00">';
    shiftHtml += '<span class="input-group-addon"><i class="fa fa-clock-o"></i></span></div></div>';
    shiftHtml += '</div>';
    shiftHtml += '<div class="clearfix"></div>';
    
    $('.holidayShift0').parent().append(shiftHtml);
    $('input[name="holiday_hours['+nextHoliday+'][open]"]').timeDropper({
        primaryColor: "#428bca",
        setCurrentTime: false,
        format: "HH:mm"
    });
    $('input[name="holiday_hours['+nextHoliday+'][close]"]').timeDropper({
        primaryColor: "#428bca",
        setCurrentTime: false,
        format: "HH:mm"
    });
    $('input[name="holiday_hours['+nextHoliday+'][date]"]').dateDropper({
        format: "Y-m-d",
        dropPrimaryColor: "#428bca"
    });
    nextHoliday = nextHoliday + 1;
}