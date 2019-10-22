$(document).ready(function () {
    $('#assetFlowForm').bootstrapValidator({
        fields: {
            merchant_id: {
                validators: {
                    notEmpty: {
                        message: 'The merchant is required and cannot be empty'
                    }
                }
            },
            staff_id: {
                validators: {
                    notEmpty: {
                        message: 'The staff is required and cannot be empty'
                    }
                }
            },
            asset_id: {
                validators: {
                    notEmpty: {
                        message: 'The asset is required and cannot be empty'
                    }
                }
            }
        },
        submitHandler: function (validator, form, submitButton) {
            
        }
    }).on('reset', function (event) {
        $('#assetFlowForm').data('bootstrapValidator').resetForm();
    });
	var requestUrl = $("#requestUrl").val();

	$(".select21").select2({
	   theme: "bootstrap",
	   placeholder: "Please Select Option",
	   width: '100%'
	});

	$('#merchant_id').change(function(){
        var merchantID = $(this).val();
        var token = $('input[name="_token"]').val();
        $('#group_id').html("");
        $('#staff_id').html("");
        
        $.ajax({
            type:'POST',
            data:{_token:token,merchant_id:merchantID},
            dataType:"json",
            url : requestUrl+"/getMerchantStaffs",
            success:function(staffs,status,xhr){
                if(staffs.length)
                {
                    $.each(staffs, function(i,value) {
                        $('#staff_id').append("<option value='"+value['staff_id']+"'>"+value['staff_name']+"</option>");
                    });
                }
            }
        });
    });
});
