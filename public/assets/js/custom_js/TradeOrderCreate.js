$(document).ready(function () {

	var requestUrl = $("#requestUrl").val();
	var token = $('input[name="_token"]').val();

	$(".select21").select2({
	   theme: "bootstrap",
	   placeholder: "Please Select Option",
	   width: '100%'
	});

	$('#start_date,#expire_date').datepicker({
		dateFormat: 'yyyy-mm-dd',
		autoClose: true
	});

	$("#start_time,#expire_time").timeDropper({
	        primaryColor: "#428bca",
	        setCurrentTime: false,
	        format: "HH:mm"

	});

	$("#location_country").on('change', function() {
        var countryId = $(this).val();

        $('select[name="location_state"]').html("");
        $('select[name="location_county"]').html("");
        $('select[name="location_city_id"]').html("");
        $.ajax({
            type:'POST',
            data:{_token:token,country_id:countryId},
            dataType:"json",
            url : requestUrl+"/getState",
            error:function(xhr,status,error) {
                console.log(error);
            },
            success:function(locationState,status,xhr) {
                $('select[name="location_state"]').append("<option></option>");
                $.each(locationState, function(i,value) {
                    $('select[name="location_state"]').append("<option value='"+value['state_id']+"'>"+value['state_name']+"</option>");
                });
            }
        });

		$('select[name="location_state"]').select2({
            theme: "bootstrap",
            placeholder: "Select State"
        });
        

    });

    $("#location_state").on('change', function() {
        var stateId = $(this).val();

        $('select[name="location_county"]').html("");
        $('select[name="location_city_id"]').html("");
        $.ajax({
            type:'POST',
            data:{_token:token,state_id:stateId},
            dataType:"json",
            url : requestUrl+"/getCounty",
            error:function(xhr,status,error) {
                console.log(error);
            },
            success:function(locationCity,status,xhr) {
                $('select[name="location_county"]').append("<option></option>");
                $.each(locationCity, function(i,value) {
                    $('select[name="location_county"]').append("<option value='"+value['county_id']+"'>"+value['county_name']+"</option>");
            	});
        	}
    	}); 

        $('select[name="location_county"]').select2({
            theme: "bootstrap",
            placeholder: "Select County"
        });        


	});

    $("#location_county").on('change', function() {
        var countyId = $(this).val();

        $('select[name="location_city_id"]').html("");
        $.ajax({
            type:'POST',
            data:{_token:token,county_id:countyId},
            dataType:"json",
            url : requestUrl+"/getCity",
            error:function(xhr,status,error) {
                console.log(error);
            },
            success:function(locationCity,status,xhr) {
                $('select[name="location_city_id"]').append("<option></option>");
                $.each(locationCity, function(i,value) {
                    $('select[name="location_city_id"]').append("<option value='"+value['city_id']+"'>"+value['city_name']+"</option>");
                });
            }
        });

        $('select[name="location_city_id"]').select2({
            theme: "bootstrap",
            placeholder: "Select City"
        });
    }); 

	
});
