$(document).ready(function () {

    var token = $('input[name="_token"]').val();

    $(".select21").select2({
        theme: "bootstrap",
        placeholder: "Please Select Option"
    });

    
    //-------------------------------Account List Tagging-------------------------------------

    function initialize(panelName){
        
        $(panelName).find("select").select2({
            theme: "bootstrap",
            placeholder: "Please Select Option"
        });

        $(panelName).find("input:checkbox").bootstrapSwitch();        
    }

    $("#member_id").on("select2:select",function(event){
        setRow(event.params.data.id,event.params.data.text)
        initialize('#panelList');
    });

    $('#member_id').on("select2:unselect", function (event) {
        memberId =event.params.data.id;
        $("#panelList").find("#member_id"+memberId).remove();
    });

    function socialLinkClick(){
        $(".addSocial").unbind( "click" );
        $('.addSocial').click(function(){
            memberId = $(this).attr('id');
            setSocialRow(memberId)
            initialize("#socialList_"+memberId);
        });
    }

    function socialLinkRemoveClick(){
        $(".deleteSocial").unbind( "click" );
        $('.deleteSocial').click(function(){
            socialId = $(this).attr('id');
            socialField = socialId.split('_');
            $("#socialList_"+socialField[1]).find("#"+socialId).remove();
        });
    }

    function setRow(memberId,memberName){
        $memberList = $('#memberDemo').clone();
        $memberList.removeAttr('id');
        $memberList.removeAttr('style');
        $memberList.css("margin-top", "10px");
        $memberList.attr('id',"member_id"+memberId);
        $tabIndex = $('#panelList').children().length - 1;
        
        $memberList.find('#member_name').text(memberName);
        $memberList.find('#memberId').attr('name',"members["+$tabIndex+"][member_id]").val(memberId);
        $memberList.find('#member_title').attr('name',"members["+$tabIndex+"][member_title]");
        $memberList.find('#priority').attr('name',"members["+$tabIndex+"][priority]");
        $memberList.find('#status').attr('name',"members["+$tabIndex+"][status]");
        $memberList.find('#status_date_begin').attr('name',"members["+$tabIndex+"][status_date_begin]");
        $memberList.find('#status_date_end').attr('name',"members["+$tabIndex+"][status_date_end]");
        $memberList.find('#socialList').attr('id',"socialList_"+memberId);
        $memberList.find('.addSocial').attr('id',memberId);

        $('#panelList').append($memberList);
        initialize('#panelList');
        
        $('input[name="members['+$tabIndex+'][status_date_begin]"'+']').datepicker({
            dateFormat: 'yyyy-mm-dd',
            autoClose: true
        });
        $('input[name="members['+$tabIndex+'][status_date_end]"'+']').datepicker({
            dateFormat: 'yyyy-mm-dd',
            autoClose: true
        });
        
        socialLinkClick();
    }

    function setDefaultRow(memberId,memberName,memberTitle,priority,status,status_date_begin,status_date_end){
        

        $memberList = $('#memberDemo').clone();
        $memberList.removeAttr('id');
        $memberList.removeAttr('style');
        $memberList.css("margin-top", "10px");
        $memberList.attr('id',"member_id"+memberId);
        $tabIndex = $('#panelList').children().length - 1;
        
        $memberList.find('#member_name').text(memberName);
        $memberList.find('#memberId').attr('name',"members["+$tabIndex+"][member_id]").val(memberId);
        $memberList.find('#member_title').attr('name',"members["+$tabIndex+"][member_title]").val(memberTitle);
        $memberList.find('#priority').attr('name',"members["+$tabIndex+"][priority]").val(priority);
        $memberList.find('#status').attr('name',"members["+$tabIndex+"][status]");
        if(status == 1){
            $memberList.find('#status').prop('checked', true);
        }else{
            $memberList.find('#status').prop('checked', false);
        }

        var status_date_begin_convert = status_date_begin.substring(0, 4)+"-"+status_date_begin.substring(4, 6)+"-"+status_date_begin.substring(6, 8);
        var status_date_end_convert = status_date_end.substring(0, 4)+"-"+status_date_end.substring(4, 6)+"-"+status_date_end.substring(6, 8);

        $memberList.find('#status_date_begin').attr('name',"members["+$tabIndex+"][status_date_begin]").val(status_date_begin_convert);
        $memberList.find('#status_date_end').attr('name',"members["+$tabIndex+"][status_date_end]").val(status_date_end_convert);
        $memberList.find('#socialList').attr('id',"socialList_"+memberId);
        $memberList.find('.addSocial').attr('id',memberId);

        $('#panelList').append($memberList);
        initialize('#panelList');

        $('input[name="members['+$tabIndex+'][status_date_begin]"'+']').datepicker({
            dateFormat: 'yyyy-mm-dd',
            autoClose: true
        });
        $('input[name="members['+$tabIndex+'][status_date_end]"'+']').datepicker({
            dateFormat: 'yyyy-mm-dd',
            autoClose: true
        });

        socialLinkClick();
    }

    function setSocialRow(memberId){
        $socialList = $('#socialDemo').clone();
        $socialList.removeAttr('id');
        $socialList.removeAttr('style');
        $socialList.css("margin-top", "10px");        
        $tabIndex = $("#socialList_"+memberId).children().length - 1;

        $socialList.attr('id',"social_"+memberId+"_"+$tabIndex);
        $socialList.find('#social_member_id').val(memberId);
        $socialList.find('#social_id').attr('name',"socials["+memberId+"]["+$tabIndex+"][social_id]");
        $socialList.find('#social_url').attr('name',"socials["+memberId+"]["+$tabIndex+"][social_url]");
        $socialList.find('#priority').attr('name',"socials["+memberId+"]["+$tabIndex+"][priority]");
        $socialList.find('#status').attr('name',"socials["+memberId+"]["+$tabIndex+"][status]");
        $socialList.find('.deleteSocial').attr('id',"social_"+memberId+"_"+$tabIndex);

        $("#socialList_"+memberId).append($socialList);
        initialize("#socialList_"+memberId);
        socialLinkRemoveClick();
    }

    function setSocialDefaultRow(memberId,socialId,socialUrl,priority,status){

        /*console.log("member id : "+memberId);
        console.log("social id : "+socialId);
        console.log("social url : "+socialUrl);
        console.log("priority : "+priority);
        console.log("status : "+status);*/

        $socialList = $('#socialDemo').clone();
        $socialList.removeAttr('id');
        $socialList.removeAttr('style');
        $socialList.css("margin-top", "10px");        
        $tabIndex = $("#socialList_"+memberId).children().length - 1;

        $socialList.attr('id',"social_"+memberId+"_"+$tabIndex);
        $socialList.find('#social_member_id').val(memberId);
        $socialList.find('#social_id').attr('name',"socials["+memberId+"]["+$tabIndex+"][social_id]").val(socialId);
        $socialList.find('#social_url').attr('name',"socials["+memberId+"]["+$tabIndex+"][social_url]").val(socialUrl);
        $socialList.find('#priority').attr('name',"socials["+memberId+"]["+$tabIndex+"][priority]").val(priority);
        $socialList.find('#status').attr('name',"socials["+memberId+"]["+$tabIndex+"][status]").val(status);
        $socialList.find('.deleteSocial').attr('id',"social_"+memberId+"_"+$tabIndex);

        if(status == 1){
            $socialList.find('#status').prop('checked', true);
        }else{
            $socialList.find('#status').prop('checked', false);
        }

        $("#socialList_"+memberId).append($socialList);
        initialize("#socialList_"+memberId);
        socialLinkRemoveClick();
    }



    $("#asset_id,#team_id").on("change",function(){

        var assetId = $("#asset_id").val();
        var teamId = $("#team_id").val();
        var requestUrl = $("#requestUrl").val();

        if(assetId != "" && teamId !=""){
            $('#panelList').html("<div class='row'>"+
                                "<div class='col-md-12'>"+
                                    "<div class='row'>"+
                                        "<label class='col-md-2'>Member Name</label>"+
                                        "<label class='col-md-2'>Member Title</label>"+
                                        "<label class='col-md-2'>Priority</label>"+
                                        "<label class='col-md-2'>Begin Date</label>"+
                                        "<label class='col-md-2'>End Date</label>"+
                                        "<label class='col-md-2'>Status</label>"+
                                    "</div>"+
                                "</div>"+
                            "</div>");
            $.ajax({
                type:'POST',
                data:{_token:token,asset_id:assetId,team_id:teamId},
                dataType:"json",
                url : requestUrl+"/getMembers",
                async: false,
                error:function(xhr,status,error){
                    console.log(error);
                },
                success:function(members,status,xhr){
                    var memberArray = [];
                    $.each(members, function(i,value) {
                        var memberId = value['member_id'];
                        var memberName = value['member_name'];
                        var memberTitle = value['member_title'];
                        var priority = value['priority'];
                        var status = value['status'];
                        var status_date_begin = value['status_date_begin'].toString();
                        var status_date_end = value['status_date_end'].toString();

                        memberArray.push(memberId);
                        setDefaultRow(memberId,memberName,memberTitle,priority,status,status_date_begin,status_date_end);

                        $.ajax({
                            type:'POST',
                            data:{_token:token,people_id:memberId},
                            dataType:"json",
                            url : requestUrl+"/getMemberSocials",
                            async: false,
                            error:function(xhr,status,error){
                                console.log(error);
                            },
                            success:function(socials,status,xhr){

                                $.each(socials, function(i,value) {

                                    var socialId = value['social_id'];
                                    var socialUrl = value['social_url'];
                                    var priority = value['priority'];
                                    var status = value['status'];

                                    setSocialDefaultRow(memberId,socialId,socialUrl,priority,status);
                                });
                            }
                        });

                    });
                    $("#member_id").val(memberArray).change();
                }
            });
        }

    });
    
});

