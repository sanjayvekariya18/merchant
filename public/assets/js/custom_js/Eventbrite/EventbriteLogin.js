var eventbriteLogin = {
    CONNECT_EVENTBRITE: 'a[rel^=connect_Eventbrite]',
    EVENTBRITE_BUTTON_CONNECT: 'button[rel^=connect_Eventbrite]',
    EVENTBRITE_ELEMENT_TARGET: 'connect_Eventbrite',
    WINDOW_OPEN: 'mywindow',
    WINDOW_WIDTH: 500,
    WINDOW_HEIGHT: 400,
    EVENTBRITE_DISCONNECT: 'a[rel^=disconnect_Eventbrite]',
    EVENTBRITE_BUTTON_DISCONNECT: 'button[rel^=disconnect_Eventbrite]',
    ELEMENT_TARGET_DISCONNECT: 'disconnect_Eventbrite',
    READ_ATTRIBUTE: 'name',
    ELEMENT_TARGET: 'rel',
    EVENTBRITE_WIDTH: 'width=',
    EVENTBRITE_HEIGHT: ',height=',
    EMPTY_STRING: '',
    EVENTBRITE_DATA: '_social/disconnect',
    EVENTBRITE_URL_IDENTITY_VALUE: "#eventbriteConnectorUrl",
    CLICK_EVENT_VALUE: 'click',
    CONNECTOR_TYPE:'connector',
};
jQuery('body').on('click', 'button', function() {
    var elementTarget = jQuery(this).parent();
    var connectorPath = jQuery(eventbriteLogin.EVENTBRITE_URL_IDENTITY_VALUE).val();
    if (elementTarget && elementTarget.attr(eventbriteLogin.ELEMENT_TARGET) == eventbriteLogin.EVENTBRITE_ELEMENT_TARGET) {
        var loginWindow = window.open(connectorPath + '/' + eventbriteLogin.CONNECTOR_TYPE, eventbriteLogin.WINDOW_OPEN, eventbriteLogin.EVENTBRITE_WIDTH + eventbriteLogin.WINDOW_WIDTH + eventbriteLogin.EVENTBRITE_HEIGHT + eventbriteLogin.WINDOW_HEIGHT + eventbriteLogin.EMPTY_STRING);
        var timer = setInterval(function() {   
            if(loginWindow.closed) {  
                clearInterval(timer); 
                $("#socialConnectorsGrid").data("kendoGrid").dataSource.read();  
                refreshMessage();
            }  
        }, 1000); 
    }
    if (elementTarget && elementTarget.attr(eventbriteLogin.ELEMENT_TARGET) == eventbriteLogin.ELEMENT_TARGET_DISCONNECT) {
        var disconnectPath = connectorPath + eventbriteLogin.EVENTBRITE_DATA;
        $.ajax({
            type: 'GET',
            url: disconnectPath,
            success: function (eventData) {

                $("#socialConnectorsGrid").data("kendoGrid").dataSource.read();                
                
                var data = jQuery.parseJSON(eventData);                
                var type = data.type;
                var message = data.message; 

                localStorage.setItem('type',type);
                localStorage.setItem('msg',message);
                refreshMessage();
            }
        });
    }
});