$(document).ready(function () {
    
    var promotionTable = $('#promotionTable').DataTable({
        "responsive": true,
        "iDisplayLength": 25
    });

    $("#merchant_type_id").select2({
        theme: "bootstrap",
        placeholder: "Merchant Type"
    });

    $('#delete').on('show.bs.modal', function(e) {
        $(this).find('.btn-danger').attr('href', $(e.relatedTarget).attr('data-link'));
    });

    var requestUrl = $("#requestUrl").val();
    var token = $('input[name="_token"]').val();

    $("#merchant_type_id").on('change', function (event, state) {
        var merchant_type_id = $(this).val();
        $.ajax({
                type:'POST',
                data:{_token:token,merchant_type:merchant_type_id},
                dataType:"text",
                url : requestUrl+"/getFilter",
                error:function(xhr,status,error) {
                    console.log(error);
                },
                success:function(merchantType,status,xhr) {
                    location.href=requestUrl;
                }
        });

        event.preventDefault();
    });
});