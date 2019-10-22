var token = $('input[name="_token"]').val();
var requestUrl = $("#requestUrl").val();
$(document).ready(function() {
    $("#event_scheduler").kendoScheduler({
        date: new Date(),
        startTime: new Date("2013/6/13 07:00 AM"),
        height: 600,
        views: ["day", "week", "month", "agenda"],
        majorTick: 120,
        eventTemplate: $("#event-template").html(),
        allDayEventTemplate: $("#event-template").html(),
        dataBound: function(eventObject) {
            var eventScheduler = eventObject.sender,
                schedulerHeader = eventScheduler.element.find(".k-scheduler-header table tbody tr:eq(0)"),
                schedulerData = eventScheduler.dataSource.data();
            $(".k-event-delete").remove();
            // add custom column header, do more append for more column
            if (eventScheduler.view().title === "Agenda") {
                /*Remove Comment column for now */
                //schedulerHeader.append("<th>Comment</th>");
                schedulerHeader.append("<th>Categories</th>");
                schedulerHeader.append("<th>Action</th>");
                // add custom column for each data
                schedulerData.forEach(function(data) {
                    var uid = data.uid;
                    var actionTemplate = '<td><a class="k-button eventCategories" eventId=' + data.taskId + ' eventName="' + data.title + '" >Category</a></td>';
                    if (typeof data.matchRoles !== 'undefined' && data.matchRoles == 4 && typeof data.owner_event !== 'undefined' && data.owner_event == true) {
                        if (typeof data.shared_event !== 'undefined' && data.shared_event == true) {
                            var actionTemplate = actionTemplate+ '<td><a class="k-button shareButton" eventId=' + data.taskId + ' eventName="' + data.title + '">Unshare</a>';
                        } else {
                            var actionTemplate = actionTemplate +'<td><a class="k-button shareButton" eventId=' + data.taskId + ' eventName="' + data.title + '" >Share</a>';
                        }
                    } else {
                        var actionTemplate = actionTemplate +'<td>';
                    }
                    if(typeof data.owner_event !== 'undefined' && data.owner_event == true && typeof data.matchRoles !== 'undefined' && data.matchRoles == 4 && typeof data.private_event !== 'undefined' && data.private_event == 1)
                    {
                        actionTemplate = actionTemplate + '<a style="margin-left:3%" class="k-button hideButton" eventId=' + data.taskId + ' eventUid="' + uid + '" >Delete</a></td>';
                    } else {
                        if(data.comment)
                        {
                            var commentTemplate = '<br><spn style="color:red">Reason : '+data.comment+'</span></td>';
                            $(".k-task[data-uid='" + uid + "']").parent().parent().css("background-color", "darkgrey");
                        } else {
                            var commentTemplate = "";
                        }
                        actionTemplate = actionTemplate + '<a style="margin-left:3%" class="k-button hideButton" eventId=' + data.taskId + ' eventUid="' + uid + '" >Hide</a>'+commentTemplate;
                    }
                    $(".k-task[data-uid='" + uid + "']").parent().after(actionTemplate);
                });
            }

            $(".eventCategories").click(function() {
                var eventName = $(this).attr("eventName");
                var eventId = $(this).attr("eventId");
                $('.content .preloader').show();
                $('.content .preloader img').show();
                getEventCategory(eventId,eventName);
            });


            $(".shareButton").click(function() {
                var eventName = $(this).attr("eventName");
                var eventId = $(this).attr("eventId");
                $('.content .preloader').show();
                $('.content .preloader img').show();
                getEventGroup(eventId,eventName);
            });
            $(".hideButton").click(function() {
                var uid = $(this).attr("eventUid");
                eventScheduler.removeEvent(uid);
                $(".k-edit-buttons .k-scheduler-delete").text("hide");
                $("span.k-window-title").text("Hide Event")
            });
        },
        editable: {
            template: jQuery("#editor").html(),
            resize: false,
            move: false,
        },
        edit: event_edit,
        save: event_save,
        dataSource: {
            batch: true,
            transport: {
                read: {
                    url: requestUrl + "/getGraphEvents",
                    dataType: "jsonp"
                },
                update: {
                    url: requestUrl + "/updateGraphEvents",
                    dataType: "jsonp"
                },
                create: {
                    url: requestUrl + "/createGraphEvents",
                    dataType: "jsonp"
                },
                destroy: {
                    url: requestUrl + "/destroyGraphEvents",
                    dataType: "jsonp"
                },
                parameterMap: function(options, operation) {
                    if (operation !== "read" && options.models) {
                        return {
                            models: kendo.stringify(options.models)
                        };
                    }
                }
            },
            requestStart: function(eventObject) {
                if(eventObject.type == "update" || eventObject.type == "create")
                {
                    $('.content .preloader').show();
                    $('.content img').show();
                }
            },
            requestEnd: function(eventObject) {
                if (typeof eventObject.type == 'undefined')
                {
                    jQuery(".errorMessage").show();
                } else {
                    jQuery(".errorMessage").hide();
                    if(eventObject.type == "update" || eventObject.type == "create")
                    {
                        jQuery(".successMessage").show();
                    }
                    if(eventObject.type == "destroy")
                    {
                        jQuery('.successMessage span').text("Event Successfully Deleted");
                        jQuery(".successMessage").show();
                    }                    

                    setTimeout(function(){
                        jQuery(".successMessage").hide();
                    },5000);
                }
                $('.content .preloader').hide();
                $('.content img').hide();
                
            },
            schema: {
                model: {
                    id: "taskId",
                    fields: {
                        taskId: {
                            from: "calendar_event_id",
                            type: "number"
                        },
                        title: {
                            from: "event_name",
                            defaultValue: "No title",
                            validation: {
                                required: true
                            }
                        },
                        parentIdentity: {
                            from: "cal_name"
                        },
                        start: {
                            type: "date",
                            from: "start_date"
                        },
                        end: {
                            type: "date",
                            from: "end_date"
                        },
                        isAllDay: {
                            type: "boolean",
                            from: "all_day"
                        },
                        matchRoles: {
                            type: "int",
                            from: "matchRoles"
                        },
                        owner_event: {
                            type: "boolean",
                            from: "owner_event"
                        },
                        location: {
                            type: "string",
                            from: "location"
                        },
                    }
                }
            }
        }
    });
    
    function event_edit(eventObject) {
        var isValidate = compareWithCurrentTime(eventObject.event.start);
        if(eventObject.event.isNew()){
            if(isValidate === false)
            {
                alert("select slot after the current time");
                eventObject.preventDefault();
            }
            
            
        } else {
        $(".k-window .k-window-title").text(eventObject.event.parentIdentity);
            if (eventObject.event.matchRoles != 4) {
                alert("This event can't be edited");
                eventObject.preventDefault();
            } else {
                if(isValidate === false)
                {
                    alert("Completed Event can not be Edited");
                    eventObject.preventDefault();
                } else {
                    if(typeof eventObject.event.matchRoles !== 'undefined' && eventObject.event.matchRoles == 4 && typeof eventObject.event.private_event !== 'undefined' && eventObject.event.private_event == 1)
                    {
                        $(".k-edit-form-container .k-scheduler-delete").text('Delete');
                    } else {
                        $(".k-edit-form-container .k-scheduler-delete").text('Hide');    
                    }
                    $('.k-edit-form-container .k-scheduler-delete').on('click', handleDeleteClick);
                }
            }
        }
    }

    function event_save(eventObject) {
        var isValidate = compareWithCurrentTime(eventObject.event.start);
        if(isValidate === false)
        {
            alert("Start time should be above current time");
            eventObject.preventDefault();
        }
    }


    $("#event_scheduler").kendoTooltip({
        filter: ".k-event > div",
        position: "top",
        width: 250,
        content: kendo.template($('#eventDetails').html())
    });

    function compareWithCurrentTime(startTime)
    {
        var startEventTime = kendo.toString(new Date(startTime), "g");
        var currentTime = kendo.toString(new Date(), "g");
        if (Date.parse(startEventTime) < Date.parse(currentTime)) {
            return false;
        }
        return true;
    }
});
function handleDeleteClick(eventObject)
{
    var dynamicMessage = eventObject.currentTarget.innerText;
    setTimeout(function() {
        var confirmationMessage = "Are you sure you want to "+dynamicMessage+" this event?";
        var titleMessage = ""+dynamicMessage+" Event";
        var messageElement = $('.k-popup-message');
        var windowTitle = messageElement.parent().parent().parent().find(".k-window-title");
        messageElement.parent().find(".k-scheduler-delete").text(dynamicMessage);
        messageElement.text(confirmationMessage);
        windowTitle.text(titleMessage);
    }, 0);
}