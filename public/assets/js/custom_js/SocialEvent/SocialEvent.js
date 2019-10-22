var token = $('input[name="_token"]').val();
var requestUrl = $("#requestUrl").val();
var baseUrl = $("#baseUrl").val();
$(document).ready(function() {

    commentWindow = jQuery("#commentsWindow").kendoWindow({
        title: "Comments",
        modal: true,
        visible: false,
        resizable: false,
        width: "auto",
        height: "auto",
        close: function() {
            $('#top_modal').modal("show");
        },
    }).data("kendoWindow");
    var detailsTemplate;
    detailsTemplate = kendo.template(jQuery("#commentTemplate").html());

    function commentShowDetails() {
        commentWindow.content(detailsTemplate(kendo.template(jQuery("#commentTemplate").html())));
        commentWindow.center().open();
    }

    /*get user group list */
    var sharedGroupMultiselect = new kendo.data.DataSource({
        transport: {
            read: {
                dataType: "json",
                url: requestUrl + "/getUserGroupList",
                type: "GET",
            }
        }
    });
    var groupDropDownList = $("#sharedGroupMultiselect").kendoMultiSelect({
        dataTextField: "group_name",
        dataValueField: "group_id",
        filter: "contains",
        height: 400,
        placeholder: "Select Group",
        dataSource: sharedGroupMultiselect
    });

	 /*get user group list */
    var merchantTypeList = new kendo.data.DataSource({
        transport: {
            read: {
                dataType: "json",
                url: requestUrl + "/getMerchantType",
                type: "GET",
            }
        }
    });
    var groupDropDownList = $("#merchantTypeList").kendoComboBox({
        dataTextField: "merchant_type_name",
        dataValueField: "merchant_type_id",
        filter: "contains",
        height: 400,
        change: onChangeMerchantType,
        placeholder: "Select Merchant Type",
        dataSource: merchantTypeList
    });
    $('#saveEventCategories').click(function(eventObject) {
        eventObject.preventDefault();
    });

    $("#event_category_form").kendoValidator({
        validateOnBlur: false,
        rules: {
            customRule: function(input) {
                if (input.is("[id=categoryMultiSelect]")) {
                    var ms = input.data("kendoMultiSelect");
                    if (ms.value().length === 0) {
                        return false;
                    }
                }
                return true;
            },
        },
        messages: {
            customRule: "Please select at least one category",
        },
        validate: function(e) {
            console.log("valid" + e.valid);
        },
        validateInput: function(e) {
            console.log("input " + e.input.attr("id") + " changed to valid: " + e.valid);
        }
    });
    // share to group
    $('#saveEventCategories').click(function() {
        var validatable = $("#event_category_form").data("kendoValidator");
        if (validatable.validate()) {
            $('.modal .preloader').show();
            $('.modal img').show();
            $.ajax({
                type: 'POST',
                data: $('#event_category_form').serialize(),
                url: requestUrl + '/saveEventCategories',
                success: function(categorySaveResponse) {
                    $('.modal .preloader').hide();
                    $('.modal img').hide();
                    $('#eventCategories').modal("hide");
                }
            });
        } else {
            jQuery(".k-invalid-msg").css("display","block");
        }
    });

    // share to group
    $('#shareEventToGroup').click(function() {
        var sharedGroupMultiselect = $("#sharedGroupMultiselect").data("kendoMultiSelect");
        var previouCountObject = $("#previousGroupCount").val();
        var previouCount = JSON.parse(previouCountObject);
        var currentcount = sharedGroupMultiselect.value();
        if (typeof previouCount !== 'undefined' && previouCount.length > 0) {
            var unsharedEventExist = arrayContainsArray(currentcount, previouCount);
            if (!unsharedEventExist) {
                $('#top_modal').modal("hide");
                commentShowDetails({}, function no() {});
            } else {
                shareStatusWithGroup({}, function no() {});
            }
        } else {
            shareStatusWithGroup({}, function no() {});
        }
    });
});

function getEventCategory(calendarEventId)
{
    $('#categoryEventId').val(calendarEventId);
    $.ajax({
        data: {
            _token: token,
            calendar_event_id: calendarEventId,
        },
        type: 'POST',
        dataType: 'json',
        url: requestUrl + "/graphEventCategories",
        success: function(categoriesObject) {
            var treeName = "categoryTreeView";
            var autoCompleteName = "categoryMultiSelect";
            var merchantTypeComboBox = $("#merchantTypeList").data("kendoComboBox");
            merchantTypeComboBox.value(categoriesObject.merchant_type_id);
            if(categoriesObject.merchant_type_id)
            {
                merchantTypeComboBox.trigger("change");
                var nodeId = [];
                var nodeId = categoriesObject.categories;
                var nodeLength = (nodeId.length);
                var nodeCount = 0;
                var autoCompleteUrl = "../storage/utree/"+categoriesObject.merchant_type_name+"AutoComplete.json";
                jQuery.getJSON(autoCompleteUrl, function (jsonObject) {
                    function nodeLoop() {
                        setInterval(function () {
                            if (nodeCount < nodeLength) {
                                var nodeDetail = getJsonObjects(jsonObject, 'id', nodeId[nodeCount]);
                                getTreeExpandWithSelectedNode(nodeDetail[0].id, nodeDetail[0].path, treeName, autoCompleteName);
                                nodeCount++;
                            }
                        }, 500);
                    }
                    nodeLoop();
                });
            } else {
                jQuery("#"+treeName).replaceWith('<div class="demo-section" id="'+treeName+'"></div>');
                jQuery("#categoryMultiSelect_taglist").parent().parent().replaceWith('<select id="'+autoCompleteName+'"></select>');
            }
            $('#eventCategories').modal("show");
            $('.content .preloader').hide();
            $('.content .preloader img').hide();
        }
    });
}
function showCategories(eventGridObject)
{
    $('.content .preloader').show();
    $('.content .preloader img').show();
    eventGridObject.preventDefault();
    var dataItem = this.dataItem($(eventGridObject.currentTarget).closest("tr"));
    getEventCategory(dataItem.calendar_event_id)
}

function onChangeMerchantType(eventObjct) {
    var merchantTypeData = $("#merchantTypeList").data("kendoComboBox");
    var merchantType = merchantTypeData.text();
    // For kendo ui tree
    var checkParent = true;
    var treeUrl = "../storage/utree/"+merchantType+"Category.json";
    var treeName = "categoryTreeView";
    var resultId = "selectedCategory";
    var isMapping = true;
    var isRadio = false;
    // for AutoComplete
    var mappingAutoComplete = true;
    var autoCompleteUrl = "../storage/utree/"+merchantType+"AutoComplete.json";
    var autoCompleteName = "categoryMultiSelect";
    var autoLabel = "Search Categories...";
    var setValueId = "setValue";
    var resultText = "resultText";
    var emptyMessage = "No Categories Found for this merchant type";
    jQuery("#"+treeName).replaceWith('<div class="demo-section" id="'+treeName+'"></div>');
    sohyperTree(checkParent, treeUrl, treeName, resultId, resultText, isMapping, autoCompleteName, setValueId, isRadio,emptyMessage);
    jQuery("#categoryMultiSelect_taglist").parent().parent().replaceWith('<select id="'+autoCompleteName+'"></select>');
    sohyperAutoComplete(mappingAutoComplete, autoCompleteUrl, autoCompleteName, autoLabel, treeName, resultId, setValueId);

};

function getEventGroup(calendarEventId,event_name)
{
    var sharedGroupMultiselect = $("#sharedGroupMultiselect").data("kendoMultiSelect");
    $.ajax({
        data: {
            _token: token,
            calendar_event_id: calendarEventId,
        },
        type: 'POST',
        dataType: 'json',
        url: requestUrl + "/sharedEventGroup",
        success: function(sharedGroupIds) {
            var previousGroupCount = sharedGroupIds;
            $('#previousGroupCount').val(JSON.stringify(previousGroupCount));
            var groupIdValues = sharedGroupIds.map(function(groupId) {
                return groupId;
            }).join(',');
            sharedGroupMultiselect.value(sharedGroupIds);
            $('#sharedEventName').html(event_name);
            $('#sharedEventId').val(calendarEventId);
            $('#sharedEventGroupList').val(groupIdValues);
            $('.content .preloader').hide();
            $('.content .preloader img').hide();
            $('#top_modal').modal("show");
        }
    });
}
function shareToGroup(eventGridObject) {
    $('.content .preloader').show();
    $('.content .preloader img').show();
    eventGridObject.preventDefault();
    var dataItem = this.dataItem($(eventGridObject.currentTarget).closest("tr"));
    getEventGroup(dataItem.calendar_event_id,dataItem.event_name)
}



function arrayContainsArray(superset, subset) {
    if (0 === subset.length) {
        return false;
    }
    return subset.every(function(value) {
        return (superset.indexOf(value) >= 0);
    });
}

function shareStatusWithGroup() {
    var calendarFetchList = $("#sharedGroupMultiselect").data("kendoMultiSelect");
    $('#sharedEventGroupList').val(calendarFetchList.value().toString());
    var unshareComments = $('textarea').val();
    $('.modal .preloader').show();
    $('.modal img').show();
    $.ajax({
        type: 'POST',
        data: $('#shareToGroup').serialize() + "&unshareComment=" + unshareComments,
        url: requestUrl + '/saveSharedEventsToGroup',
        success: function(json) {
            $('.modal .preloader').hide();
            $('.modal img').hide();
            jQuery("#commentsWindow").data("kendoWindow").close();
            $('#top_modal').modal("hide");
        }
    });
}

function closeCommentEvent() {
    jQuery("#commentsWindow").data("kendoWindow").close();
    $('#top_modal').modal("show");
}

function textareHeightWidth() {
    var textarea = document.querySelector('textarea');
    textarea.addEventListener('keydown', autosize);

    function autosize() {
        var el = this;
        setTimeout(function() {
            el.style.cssText = 'height:auto; padding:0';
            el.style.cssText = 'height:' + el.scrollHeight + 'px';
        }, 0);
    }
}