$(document).ready(function () {
    var requestUrl = $("#activity_url").attr('href');
    var _token = $('input[name="_token"]').val();
    $('#activity_notification').click(function(){
        var token = $('input[name="_token"]').val();
        $('#staff_location_id option:gt(0)').remove().end();
        $.ajax({
            type:'POST',
            data:{_token:token},
            url : requestUrl+"/updateActivityStatus",
            error:function(xhr,status,error){
                console.log(error);
            },
            success:function(locations,status,xhr){
               console.log("Notification status updated");
               $('#activity_notification .label').html("0");
            }
        });
    });

});