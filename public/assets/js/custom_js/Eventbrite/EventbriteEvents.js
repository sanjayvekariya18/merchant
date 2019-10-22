var token = $('input[name="_token"]').val();
var requestUrl = $("#requestUrl").val();
var baseUrl = $("#baseUrl").val();
$(document).ready(function() {
    $("#distance").kendoNumericTextBox();
    var priceFilterData = [{
        text: "Free",
        value: "free"
    }, {
        text: "Paid",
        value: "paid"
    }];
    $("#price").kendoMultiSelect({
        dataTextField: "text",
        dataValueField: "value",
        dataSource: priceFilterData,
        value: [ "free" ]
    });

    var eventbriteSyncEventGrid = jQuery("#unSyncGroupPanel").kendoGrid({
        noRecords: true,
        messages: {
            noRecords: "No event Found"
        },
        pageable: {
            refresh: true,
            pageSizes: true
        },
        autoSync: true,
        sortable: true,
        reorderable: true,
        groupable: true,
        resizable: true,
        toolbar: kendo.template($("#syncEventTemplate").html()),
        dataBound: function(eventObject) {
            var data = this.dataSource.data();
            $.each(data, function(i, row) {
                if(row.disable_sync)
                {
                    $('tr[data-uid="' + row.uid + '"] td:nth-child(9)').find(".k-grid-sync").remove(); 
                }
            });
        },
        columns: [{
           headerTemplate: "<input type='checkbox' class='allSelectRow' />",
           template: "<input type='checkbox' class='selectRow' data-bind='checked: checked' />",
           width: "20px",
           filterable: false
        },{
            field: "event_id",
            title: "Event Id",
            hidden: true,
        }, {
            field: "logo",
            title: "Logo",
            template: "#if(data.logo){#<img width='100' height='50' src='#= data.logo.url #' alt='image' />#}#",
            width: "110px",
            groupable: false
        }, {
            field: "event_name",
            title: "Event Name",
            template: '<a target="_blank" href="#=url#">#=event_name#</a>',
            width: "160px",
        }, {
            field: "start_date",
            title: "Start",
            width: "120px",
            template: "#=data.start_date# #=data.start_time#",
        }, {
            field: "end_date",
            title: "End",
            width: "120px",
            template: "#=data.end_date# #=data.end_time#",
        }, {
            field: "address.city",
            title: "City",
            width: "90",
            template: "#if(data.address.city){# #=data.address.city# #}#",
        }, {
            field: "status",
            title: "status",
            width: "80px",
        }, {
            command: [{
                text: "sync",
                click: syncEventBriteEvent
            }],
            title: "Action",
            width: "80px",
        }],
    }).data("kendoGrid");

    $("#unSyncGroupPanel").on('click', '.allSelectRow', function(eventData) {
        var checkedData = eventData.target.checked;
        $('.selectRow').each(function (idx, item) {
            if (checkedData) {
                if(!$(this).prop('checked') == true){
                    $(this).click();
                }
            } else {
                if($(this).prop('checked') == true){
                    $(this).click();
                }
            }
        });
    });

    $("#event_filter_form").kendoValidator({
        validateOnBlur: false,
        rules: {
            customRule1: function(input) {
                if (input.is("[id=price]")) {
                    var ms = input.data("kendoMultiSelect");
                    if (ms.value().length === 0) {
                        return false;
                    }
                }
                return true;
            },
            customRule2: function(input) {
                if (input.is("[name=keyword]")) {
                    if (input.val() == '') {
                        return false;
                    } else {
                        return true;
                    }
                }
                return true;
            },
            customRule3: function(input) {
                if (input.is("[name=address]")) {
                    if (input.val() == '') {
                        return false;
                    } else {
                        return true;
                    }
                }
                return true;
            }
        },
        messages: {
            customRule1: "Please Select Event Type",
            customRule2: "Please Enter Keyword",
            customRule3: "Please Enter Address",
        },
        validate: function(e) {
            console.log("valid" + e.valid);
        },
        validateInput: function(e) {
            console.log("input " + e.input.attr("keyword") + " changed to valid: " + e.valid);
        }
    });
    var eventbriteEventsGrid = jQuery("#eventbriteEvents").kendoGrid({
        dataSource: {
            serverPaging: true,
            serverFiltering: true,
            serverSorting: false,
            pageSize: 10,
            transport: {
                read: {
                    data: {
                        _token: token
                    },
                    url: requestUrl + "/eventBriteEventList",
                    dataType: "json",
                    type: "POST"
                },
                destroy: {
                    data: {
                        _token: token
                    },
                    url: requestUrl + "/deleteEventBriteEvent",
                    dataType: "jsonp",
                    type: "POST",
                }
            },
            schema: {
                total: 'total',
                data: 'graph_calendar',
                model: {
                    id: 'calendar_event_id',
                },
            },
        },
        pageable: {
            refresh: true,
            pageSizes: true
        },
        filterable: {
            mode: "row"
        },
        batch: true,
        scrollable: false,
        sortable: true,
        toolbar: [{
            template: kendo.template($("#eventBriteGridSearch").html())
        }],
        editable: "inline",
        editable: {
            mode: "inline",
            confirmation: "Are you sure you want to hide this record??"
        },
        columnMenu: true,
        dataBound: function(eventObject) {
            var data = this.dataSource.data();
            $.each(data, function(i, row) {
                if ((typeof row.owner_event !== 'undefined' && row.owner_event == true) && row.matchRoles == 4) {
                    $('tr[data-uid="' + row.uid + '"] td:nth-child(9)').find(".k-grid-delete").remove();
                    if (row.shared_event) $('tr[data-uid="' + row.uid + '"] td:nth-child(9)').find(".k-grid-Share").text('unshare');
                } else {
                    if (row.comment) {
                        $('tr[data-uid="' + row.uid + '"] td:nth-child(9)').find(".k-grid-Share").remove();
                        $('tr[data-uid="' + row.uid + '"]').css("background-color", "darkgrey")
                    } else {
                        $('tr[data-uid="' + row.uid + '"] td:nth-child(9)').find(".k-grid-delete,.k-grid-Share").remove();
                    }
                }
                if ((typeof row.own_share !== 'undefined' && row.own_share == true))
                {
                    var element = $('tr[data-uid="' + row.uid + '"] ');
                    element.css("background-color", "00FFFF");
                }
            });
        },
        columns: [{
            field: "calendar_event_id",
            title: "Event Id",
            hidden: true,
        }, {
            field: "event_name",
            title: "Event",
            filterable: {
                cell: {
                    operator: "contains",
                }
            },
            width: "34%",
            template: "<div>#if(data.avatar_link){#<img class='avatarLink' src='#= data.avatar_link #' alt='image' /># }# #if(data.website_link){#<a class='eventLink' href='#=data.website_link#' target='_blank'>#=data.event_name#</a>#}else{# #=data.event_name# #} #</div>",
        }, {
            field: "start_date",
            title: "Start Date",
            width: "16%",
            template: '#= Unix_timestamp(data.start_date,data.start_time) #'
        }, {
            field: "end_date",
            title: "End Date",
            width: "16%",
            template: '#= Unix_timestamp(data.end_date,data.end_time) #'
        },
        {
            field: "location",
            title: "city",
            width: "15%",
        }, {
            field: "comment",
            title: "Comment",
            hidden: true,
            width: "20%",

        }, {
            field: "status",
            title: "Status",
            width: "10%",
        },
        {
            command: [{
                text: "Categories",
                click: showCategories
            }],
            title: "Category",
            width: "10%"
        },
        {
            command: [{
                text: "Share",
                click: shareToGroup
            },{
                name: "destroy",
                text: "Hide"
            }],
            title: "Action",
            width: "10%"
        }]
    });

    $('#eventBriteSearchBox').keypress(function (eventObject) {
        var keyPress = eventObject.which;
        if(keyPress == 13)  // the enter key code
        {
            eventBriteGridSearch();
        }
    }); 
    function eventBriteGridSearch()
    {
        var eventBriteSearchValue = $('#eventBriteSearchBox').val();
        $("#eventbriteEvents").data("kendoGrid").dataSource.filter({
            logic: "or",
            filters: [{
                field: "event_name",
                operator: "contains",
                value: eventBriteSearchValue
            },
            {
                field   : "start_date",
                operator: "contains",
                value   : eventBriteSearchValue
            },
            {
                field   : "end_date",
                operator: "contains",
                value   : eventBriteSearchValue
            },
            {
                field   : "location",
                operator: "contains",
                value   : eventBriteSearchValue
            }]
        });
    }
    $("#eventBriteBtnSearch").click(function() {
        eventBriteGridSearch();
    });
    //Clearing the filter
    $("#eventBriteBtnReset").click(function() {
        $("#eventbriteEvents").data("kendoGrid").dataSource.filter({});
    });
    // this is the id of the form
    $(".searchOwnEvents").click(function() {
        var eventSearchUrl = $(this).attr("data-url");
        var eventbriteSyncEventGrid = $("#unSyncGroupPanel").data("kendoGrid");
        var eventbriteSyncEventDataSource = new kendo.data.DataSource({
            transport: {
                read: {
                    data: {
                        _token: token,
                        searchUrl : eventSearchUrl
                    },
                    url: requestUrl + "/fetchMyEventList",
                    type: 'POST',
                    dataType: "json"
                },
            },
            serverPaging: true,
            serverSorting: true,
            pageSize: 50,
            batch: true,
            schema: {
                data: "events",
                total: "total",
                model: {
                    id: 'event_id',
                },
            },
        });
        eventbriteSyncEventGrid.setDataSource(eventbriteSyncEventDataSource);
    });
    $('#address').keypress(function (eventObject) {
        var keyPress = eventObject.which;
        if(keyPress == 13)  // the enter key code
        {
            return false;  
        }
    });  
    $("#event_filter_form").submit(function(event) {
        event.preventDefault(); // avoid to execute the actual submit of the form.
        var validatable = $("#event_filter_form").data("kendoValidator");
        if (validatable.validate()) {
            $('#searchOtherEvents').modal('hide');
            var priceValues = $("#price").data("kendoMultiSelect").value().toString();
            var eventDistance = $("#distance").data("kendoNumericTextBox").value()
            var eventKeyword = $("#keyword").val();
            var eventAddress = $("#address").val();
            var eventLatitude = $("#latitude").val();
            var eventLongitude = $("#longitude").val();
            var eventSortBy = $("#sort_by").val();
            var eventbriteSyncEventGrid = $("#unSyncGroupPanel").data("kendoGrid");
            var eventbriteSyncEventDataSource = new kendo.data.DataSource({
                transport: {
                    read: {
                        data: {
                            _token: token,
                            keyword: eventKeyword,
                            address: eventAddress,
                            distance: eventDistance,
                            sort_by: eventSortBy,
                            eventPriceType: priceValues,
                            eventLatitude: eventLatitude,
                            eventLongitude: eventLongitude
                        },
                        url: requestUrl + "/fetchOtherEventList",
                        type: 'POST',
                        dataType: "json"
                    },
                },
                serverPaging: true,
                serverSorting: true,
                pageSize: 50,
                batch: true,
                schema: {
                    data: "events",
                    total: "total",
                    model: {
                        id: 'event_id',
                    },
                },
            });
            eventbriteSyncEventGrid.setDataSource(eventbriteSyncEventDataSource);
        }
    });

    $("#eventBriteLoginRedirect").click(function(eventObject) {
        eventObject.preventDefault();
        localStorage.setItem('highlighterLogin', 'Eventbrite');
        window.location = baseUrl+"/social_connectors";
    });
});



function syncEventBriteEvent(syncGridObject) {
    $('.content .preloader').show();
    $('.content .preloader img').show();
    var dataItem = this.dataItem($(syncGridObject.currentTarget).closest("tr"));
    var eventlist = [dataItem.event_id];
    $.ajax({
        type: "POST",
        url: requestUrl + '/syncEventbriteEvents',
        data: {
            _token: token,
            otherEventList: JSON.stringify(eventlist),
        },
        success: function(syncEventResponse, status, xhr) {
            $('.content .preloader').hide();
            $('.content .preloader img').hide();
            $("#unSyncGroupPanel").data("kendoGrid").dataSource.remove(dataItem);
            $('#eventbriteEvents').data('kendoGrid').dataSource.read();
        }
    });
    syncGridObject.preventDefault();
}

function syncBatchEvent(syncType)
{
    $('.content .preloader').show();
    $('.content .preloader img').show();
    var eventbriteSyncEventGrid = jQuery("#unSyncGroupPanel").data('kendoGrid');
    var syncEventGridData = eventbriteSyncEventGrid.dataSource.view();
    var checkedEventData = [];
    if(syncType == 'checkedEvents')
    {
        for (var initData = 0; initData < syncEventGridData.length; initData++)
        {
            dataItem = eventbriteSyncEventGrid.table.find("tr[data-uid='" + syncEventGridData[initData].uid + "']");
            var checkbox = dataItem.find(".selectRow");
            if (checkbox.is(":checked")) {
                checkedEventData.push(syncEventGridData[initData].event_id);
            }
        }
    } else {
        var checkedEventData = eventbriteSyncEventGrid.dataSource.data().map(function(x){
            return x.event_id;
        });
    }

    if(typeof checkedEventData !== 'undefined' && checkedEventData.length > 0)
    {
        $.ajax({
            type: "POST",
            url: requestUrl + '/syncEventbriteEvents',
            data: {
                _token: token,
                otherEventList: JSON.stringify(checkedEventData),
            },
            success: function(syncEventResponse, status, xhr) {
                $('.content .preloader').hide();
                $('.content .preloader img').hide();
                if(syncType == 'checkedEvents')
                {
                    $("#unSyncGroupPanel").find("input:checked").each(function(){
                        if (!$(this).parents('th').length) {
                            eventbriteSyncEventGrid.removeRow($(this).closest('tr'));
                        }
                    })
                } else {
                    $("#unSyncGroupPanel").data('kendoGrid').dataSource.data([]);
                }
                $('#eventbriteEvents').data('kendoGrid').dataSource.read();
            }
        });
    } else {
        $('.content .preloader').hide();
        $('.content .preloader img').hide();
    }
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

function initAutocomplete() {
    var geocoder = new google.maps.Geocoder();
    var map = new google.maps.Map(document.getElementById('googleMap'), {
        center: {
            lat: 40.730610,
            lng: -73.935242
        },
        zoom: 10,
        mapTypeId: 'roadmap',
        mapTypeControlOptions: {
            style: google.maps.MapTypeControlStyle.HORIZONTAL_BAR,
            position: google.maps.ControlPosition.BOTTOM_CENTER
        },
        streetViewControl: false,
        fullscreenControl: false
    });
    var postalAddress = document.getElementById('address').value;
    if (postalAddress) {
        geocoder.geocode({
            'address': postalAddress
        }, function(results, status) {
            if (status === 'OK') {
                map.setCenter(results[0].geometry.location);
            } else {
                alert('Geocode was not successful for the following reason: ' + status);
            }
        });
    }

    // Create the search box and link it to the UI element.
    var input = document.getElementById('address');
    var searchBox = new google.maps.places.SearchBox(input);
    map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

    // Bias the SearchBox results towards current map's viewport.
    map.addListener('bounds_changed', function() {
        searchBox.setBounds(map.getBounds());
    });

    var markers = [];
    // Listen for the event fired when the user selects a prediction and retrieve
    // more details for that place.
    searchBox.addListener('places_changed', function() {
        var places = searchBox.getPlaces();

        if (places.length == 0) {
            return;
        }

        // Clear out the old markers.
        markers.forEach(function(marker) {
            marker.setMap(null);
        });
        markers = [];

        // For each place, get the icon, name and location.
        var bounds = new google.maps.LatLngBounds();
        places.forEach(function(place) {
            if (!place.geometry) {
                console.log("Returned place contains no geometry");
                return;
            }
            // show in input box
            document.getElementById("latitude").value = place.geometry.location.lat();
            document.getElementById("longitude").value = place.geometry.location.lng();

            var icon = {
                url: place.icon,
                size: new google.maps.Size(71, 71),
                origin: new google.maps.Point(0, 0),
                anchor: new google.maps.Point(17, 34),
                scaledSize: new google.maps.Size(25, 25)
            };

            // Create a marker for each place.
            markers.push(new google.maps.Marker({
                map: map,
                icon: icon,
                title: place.name,
                position: place.geometry.location
            }));

            if (place.geometry.viewport) {
                // Only geocodes have viewport.
                bounds.union(place.geometry.viewport);
            } else {
                bounds.extend(place.geometry.location);
            }
        });
        map.fitBounds(bounds);
    });
}
