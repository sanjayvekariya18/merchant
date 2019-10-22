$(document).ready(function () {
	$(".select21").select2({
	   theme: "bootstrap",
	   placeholder: "Please Select Option",
	   width: '100%'
	});

	$("input[name='status']").bootstrapSwitch();

    $("input[name='status']").on('switchChange.bootstrapSwitch', function (event, state) {
        if (state) {
            $("#status").attr('checked',true);
        } else {
            $("#status").attr('checked',false);
        }
        event.preventDefault();
    });

    $("input[name='status']").on('switchChange.bootstrapSwitch', function (event, state) {
        if (state) {
            jQuery("#status").val(1);
        } else {
            jQuery("#status").val(0);
        }
       event.preventDefault();
    });

    var languageListStatusValue = $('#status').val();
    if (languageListStatusValue == 1) {
        $('#status').bootstrapSwitch('state', true);
    } else {
        $('#status').bootstrapSwitch('state', false);
    }

    $("#asset_id").on('change',function() {
        var assetCode = $(this).children(":selected").attr("asset_code");
        $("#asset_code").val(assetCode);
    });

    var token = $('input[name="_token"]').val();
    var requestUrl = $("#requestUrl").val();
    var selectedAsset = $("#selectedAsset").val();

    $("#exchange_id").on('change',function() {
        var exchangeId = $(this).val();
        $('#asset_id').html("");
        $.ajax({
            type:'POST',
            data:{_token:token,exchange_id:exchangeId,current_asset:selectedAsset},
            dataType:"json",
            url : requestUrl+"/getExchangeAssetEdit",
            error:function(xhr,status,error) {
                console.log(error);
            },
            success:function(exchangeAsset,status,xhr) {
                $('#asset_id').append("<option></option>");
                $.each(exchangeAsset, function(i,value) {
                    if(value['asset_id'] == selectedAsset) {
                        $('#asset_id').append("<option value='"+value['asset_id']+"' asset_code='"+value['asset_code']+"' selected>"+value['asset_name']+"&nbsp;("+value['asset_code']+")"+"</option>");
                    } else {
                        $('#asset_id').append("<option value='"+value['asset_id']+"' asset_code='"+value['asset_code']+"'>"+value['asset_name']+"&nbsp;("+value['asset_code']+")"+"</option>");
                    }
                });
            }
        });
    });
});