$(".select21").select2({
   theme: "bootstrap",
   placeholder: "Please Select Option",
   width: '100%'
});

$('#holiday_date').datepicker({
	dateFormat: 'yyyy-mm-dd',
});

$(function () {
	$("#holiday_form").bootstrapValidator({
        fields: {
            holiday_name: {
                validators: {
                    notEmpty: {
                        message: 'The Name field is required.'
                    }
                },
                required: true,
                minlength: 3
            },
        }
    });
});
$('#holiday_country_id').change(function(){
    var countryId = $(this).val();
    var token = $('input[name="_token"]').val();
    var requestUrl = $('#requestUrl').val();
    $('#holiday_state_id').html("");
    $.ajax({
        type:'POST',
        data:{_token:token,country_id:countryId},
        dataType:"json",
        url : requestUrl+"/getCountryState",
        error:function(xhr,status,error){
            console.log(error);
        },
        success:function(statusList,status,xhr){
            if(statusList && statusList.length)
            {   
                $.each(statusList, function(i,value) {
                    $('#holiday_state_id').append("<option value='"+value['state_id']+"'>"+value['state_name']+"</option>");
                });
            }
        }

    });
});
