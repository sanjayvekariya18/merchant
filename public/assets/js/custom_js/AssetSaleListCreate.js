$(document).ready(function () {

    var token = $('input[name="_token"]').val();

    $(".select21").select2({
        theme: "bootstrap",
        placeholder: "Please Select Option"
    });

    
    //-------------------------------Asset Sale List-------------------------------------

    function initialize(){
        
        $("#panelList").find("select").select2({
            theme: "bootstrap",
            placeholder: "Please Select Option"
        });

        $("#panelList").find("input:checkbox").bootstrapSwitch();
    }

    $("#event_id").on("select2:select",function(event){
        
        setRow(event.params.data.id,event.params.data.text)
        initialize();
    });

    $('#event_id').on("select2:unselect", function (event) {
        eventId =event.params.data.id;
        $("#panelList").find("#event_id_"+eventId).remove();
    });

    function setRow(eventId,eventName){
        $eventList = $('#eventDemo').clone();
        $eventList.removeAttr('id');
        $eventList.removeAttr('style');
        $eventList.css("margin-top", "10px");
        $eventList.attr('id',"event_id_"+eventId);
        $tabIndex = $('#panelList').children().length - 1;
        
        $eventList.find('#event_name').text(eventName);
        $eventList.find('#eventId').attr('name',"events["+$tabIndex+"][event_id]").val(eventId);
        $eventList.find('#priority').attr('name',"events["+$tabIndex+"][priority]");
        $eventList.find('#status').attr('name',"events["+$tabIndex+"][status]");

        $('#panelList').append($eventList);
        initialize();
    }

    function setDefaultRow(eventId,eventName,priority,status){
        $eventList = $('#eventDemo').clone();
        $eventList.removeAttr('id');
        $eventList.removeAttr('style');
        $eventList.css("margin-top", "10px");
        $eventList.attr('id',"event_id_"+eventId);
        $tabIndex = $('#panelList').children().length - 1;
        
        $eventList.find('#event_name').text(eventName);
        $eventList.find('#eventId').attr('name',"events["+$tabIndex+"][event_id]").val(eventId);
        $eventList.find('#priority').attr('name',"events["+$tabIndex+"][priority]").val(priority);
        $eventList.find('#status').attr('name',"events["+$tabIndex+"][status]");
        if(status == 1){
            $eventList.find('#status').prop('checked', true);
        }else{
            $eventList.find('#status').prop('checked', false);
        }

        $('#panelList').append($eventList);
        initialize();
    }

    $("#asset_id").on("change",function(){

        var assetId = $("#asset_id").val();
        var requestUrl = $("#requestUrl").val();

        $('#panelList').html("<div class='row'>"+
                                "<div class='col-md-12'>"+
                                    "<div class='row'>"+
                                        "<label class='col-md-6'>Event Name</label>"+
                                        "<label class='col-md-3'>Priority</label>"+
                                        "<label class='col-md-3'>Status</label>"+
                                    "</div>"+
                                "</div>"+
                            "</div>");
        $.ajax({
            type:'POST',
            data:{_token:token,asset_id:assetId},
            dataType:"json",
            url : requestUrl+"/getEvents",
            async: false,
            error:function(xhr,status,error){
                console.log(error);
            },
            success:function(events,status,xhr){
                var eventArray = [];
                $.each(events, function(i,value) {
                    var eventId = value['event_id'];
                    var eventName = value['event_name'];
                    var eventPriority = value['priority'];
                    var eventStatus = value['status'];

                    eventArray.push(eventId);
                    setDefaultRow(eventId,eventName,eventPriority,eventStatus);
                });
                $("#event_id").val(eventArray).change();
            }
        });

    });
    
});

