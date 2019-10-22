$(document).ready(function () {
	
	var token = $('input[name="_token"]').val();

    $(".select21").select2({
        theme: "bootstrap",
        placeholder: "Please Select Option"
    });

    function initialize(){
        
        $("#panelList").find("select").select2({
            theme: "bootstrap",
            placeholder: "Please Select Option"
        });

        $("#panelList").find("input:checkbox").bootstrapSwitch();

    }

    $("#social_id").on("select2:select",function(event){
        
        setRow(event.params.data.id,event.params.data.text)
        initialize();
    });

    $('#social_id').on("select2:unselect", function (event) {
        socialId =event.params.data.id;
        $("#panelList").find("#social_id"+socialId).remove();
    });

    function setRow(socialId,socialName){
        $socialList = $('#socialDemo').clone();
        $socialList.removeAttr('id');
        $socialList.removeAttr('style');
        $socialList.css("margin-top", "10px");
        $socialList.attr('id',"social_id"+socialId);
        $tabIndex = $('#panelList').children().length - 1;
        
        $socialList.find('#social_name').text(socialName);
        $socialList.find('#socialId').attr('name',"socials["+$tabIndex+"][social_id]").val(socialId);
        $socialList.find('#social_url').attr('name',"socials["+$tabIndex+"][social_url]");
        $socialList.find('#priority').attr('name',"socials["+$tabIndex+"][priority]");
        $socialList.find('#status').attr('name',"socials["+$tabIndex+"][status]");

        $('#panelList').append($socialList);
        initialize();
    }

    function setDefaultRow(socialId,socialName,socialUrl,priority,status){
        $socialList = $('#socialDemo').clone();
        $socialList.removeAttr('id');
        $socialList.removeAttr('style');
        $socialList.css("margin-top", "10px");
        $socialList.attr('id',"social_id"+socialId);
        $tabIndex = $('#panelList').children().length - 1;
        
        $socialList.find('#social_name').text(socialName);
        $socialList.find('#socialId').attr('name',"socials["+$tabIndex+"][social_id]").val(socialId);
        $socialList.find('#social_url').attr('name',"socials["+$tabIndex+"][social_url]").val(socialUrl);
        $socialList.find('#priority').attr('name',"socials["+$tabIndex+"][priority]").val(priority);
        $socialList.find('#status').attr('name',"socials["+$tabIndex+"][status]");
        if(status == 1){
            $socialList.find('#status').prop('checked', true);
        }else{
            $socialList.find('#status').prop('checked', false);
        }

        $('#panelList').append($socialList);
        initialize();
    }

    $("#asset_id").on("change",function(){

        var assetId = $("#asset_id").val();
        var requestUrl = $("#requestUrl").val();

        $('#panelList').html("<div class='row'>"+
                            "<div class='col-md-12'>"+
                                "<div class='row'>"+
                                    "<label class='col-md-3'>Social Name</label>"+
                                    "<label class='col-md-5'>Social Url</label>"+
                                    "<label class='col-md-2'>Priority</label>"+
                                    "<label class='col-md-2'>Status</label>"+
                                "</div>"+
                            "</div>"+
                        "</div>");
        $.ajax({
            type:'POST',
            data:{_token:token,asset_id:assetId},
            dataType:"json",
            url : requestUrl+"/getSocials",
            async: false,
            error:function(xhr,status,error){
                console.log(error);
            },
            success:function(socials,status,xhr){
                var socialArray = [];
                $.each(socials, function(i,value) {
                    var socialId = value['social_id'];
                    var socialName = value['social_name'];
                    var socialUrl = value['social_url'];
                    var priority = value['priority'];
                    var status = value['status'];

                    socialArray.push(socialId);
                    setDefaultRow(socialId,socialName,socialUrl,priority,status);
                });
                $("#social_id").val(socialArray).change();
            }
        });

    });
});