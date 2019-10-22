$(document).ready(function () {
	$(".select21").select2({
	   theme: "bootstrap",
	   placeholder: "Please Select Option",
	   width: '100%'
	});

    var requestUrl = $("#requestUrl").val();
    var token = $('input[name="_token"]').val();
    $("#merchant_id").on('change', function() {
        var merchantId = $(this).val();
        $('select[name="merchant_account_id"]').html("<option></option>");
        $('select[name="customer_account_id"]').html("<option></option>");
        $("#asset_id_hidden").val('');
        $.ajax({
            type:'POST',
            data:{_token:token,merchant_id:merchantId},
            dataType:"json",
            url : requestUrl+"/getMerchantAccounts",
            error:function(xhr,status,error) {
                console.log(error);
            },
            success:function(mercantAccounts,status,xhr) {
                $.each(mercantAccounts, function(i,value) {
                    $('select[name="merchant_account_id"]').append("<option value='"+value['account_id']+"' asset-id='"+value['asset_id']+"'>"+value['account_name']+"</option>");
                });
            }
        });
        $('select[name="customer_id"]').html("<option></option>");
        $.ajax({
            type:'POST',
            data:{_token:token,merchant_id:merchantId},
            dataType:"json",
            url : requestUrl+"/getMerchantCustomers",
            success:function(merchantCustomers,status,xhr) {
                $.each(merchantCustomers, function(i,value) {
                    $('select[name="customer_id"]').append("<option value='"+value['customer_id']+"'>"+value['customer_name']+"</option>");
                });
            }
        });
    });

    $("#customer_id").on('change', function() {
        var merchantId = $('#merchant_id').val();
        var customerId = $(this).val();

        $('select[name="customer_account_id"]').html("<option></option>");
        $.ajax({
            type:'POST',
            data:{_token:token,merchant_id:merchantId,customer_id:customerId},
            dataType:"json",
            url : requestUrl+"/getCustomerAccounts",
            success:function(customers,status,xhr) {
                $.each(customers, function(i,value) {
                    $('select[name="customer_account_id"]').append("<option value='"+value['account_id']+"'>"+value['account_name']+"</option>");
                });
            }
        });
    });

    $("#merchant_account_id").on('change', function() {
        var assetId = $(this).children(":selected").attr("asset-id");
        if(assetId != '') {
            $('#asset_id').val(assetId);
            $("#asset_id").select2({
               theme: "bootstrap",
               placeholder: "Please Select Option",
               width: '100%'
            });
            $("#asset_id").prop('disabled',true);
            $("#asset_id_hidden").val(assetId);
        }
        else {
            $("#asset_id").prop('disabled',false);
            $('#asset_id').val('');
            $("#asset_id").select2({
               theme: "bootstrap",
               placeholder: "Please Select Option",
               width: '100%'
            });
            $("#asset_id_hidden").val('');
        }
    });

    $("#asset_id").on('change',function() {
        var assetId = $(this).val();
        $("#asset_id_hidden").val(assetId);
    });

    $('#fund_date').datepicker({
        dateFormat: 'yyyy-mm-dd',
        autoClose: true
    });

    $("#fund_time").timeDropper({
        primaryColor: "#428bca",
        setCurrentTime: false,
        format: "HH:mm"
    });
});
