var meetupLogin = {
    CONNECT_MEETUP: 'a[rel^=connect_Meetup]',
    MEETUP_BUTTON_CONNECT: 'button[rel^=connect_Meetup]',
    MEETUP_ELEMENT_TARGET: 'connect_Meetup',
    WINDOW_OPEN: 'mywindow',
    WINDOW_WIDTH: 500,
    WINDOW_HEIGHT: 400,
    MEETUP_DISCONNECT: 'a[rel^=disconnect_Meetup]',
    MEETUP_BUTTON_DISCONNECT: 'button[rel^=disconnect_Meetup]',
    ELEMENT_TARGET_DISCONNECT: 'disconnect_Meetup',
    READ_ATTRIBUTE: 'name',
    ELEMENT_TARGET: 'rel',
    MEETUP_WIDTH: 'width=',
    MEETUP_HEIGHT: ',height=',
    EMPTY_STRING: '',
    MEETUP_DATA: '_social/disconnect',
    MEETUP_URL_IDENTITY_VALUE: "#meetupConnectorUrl",
    CLICK_EVENT_VALUE: 'click',
    CONNECTOR_TYPE:'connector',
};

jQuery('body').on('click', 'button', function(){
    var elementTarget = jQuery(this).parent();
    var connectorPath = jQuery(meetupLogin.MEETUP_URL_IDENTITY_VALUE).val();
    if (elementTarget && elementTarget.attr(meetupLogin.ELEMENT_TARGET) == meetupLogin.MEETUP_ELEMENT_TARGET) {
        var loginWindow = window.open(connectorPath+'/'+meetupLogin.CONNECTOR_TYPE, meetupLogin.WINDOW_OPEN, meetupLogin.MEETUP_WIDTH + meetupLogin.WINDOW_WIDTH + meetupLogin.MEETUP_HEIGHT + meetupLogin.WINDOW_HEIGHT + meetupLogin.EMPTY_STRING);
        var timer = setInterval(function() {   
            if(loginWindow.closed) {  
                clearInterval(timer);  
                $("#socialConnectorsGrid").data("kendoGrid").dataSource.read();  
                refreshMessage();
            }  
        }, 1000);
    }
    if (elementTarget && elementTarget.attr(meetupLogin.ELEMENT_TARGET) == meetupLogin.ELEMENT_TARGET_DISCONNECT) {
        var disconnectPath = connectorPath + meetupLogin.MEETUP_DATA;
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
