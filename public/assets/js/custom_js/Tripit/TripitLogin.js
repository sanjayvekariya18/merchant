var tripitLogin = {
    CONNECT_TRIPIT: 'a[rel^=connect_Tripit]',
    TRIPIT_BUTTON_CONNECT: 'button[rel^=connect_Tripit]',
    TRIPIT_ELEMENT_TARGET: 'connect_Tripit',
    WINDOW_OPEN: 'mywindow',
    WINDOW_WIDTH: 500,
    WINDOW_HEIGHT: 400,
    TRIPIT_DISCONNECT: 'a[rel^=disconnect_Tripit]',
    TRIPIT_BUTTON_DISCONNECT: 'button[rel^=disconnect_Tripit]',
    ELEMENT_TARGET_DISCONNECT: 'disconnect_Tripit',
    READ_ATTRIBUTE: 'name',
    ELEMENT_TARGET: 'rel',
    TRIPIT_WIDTH: 'width=',
    TRIPIT_HEIGHT: ',height=',
    EMPTY_STRING: '',
    TRIPIT_DATA: '_social/disconnect',
    TRIPIT_URL_IDENTITY_VALUE: "#tripitConnectorUrl",
    CLICK_EVENT_VALUE: 'click',
};

jQuery('body').on('click', 'button', function(){
    var elementTarget = jQuery(this).parent();
    var connectorPath = jQuery(tripitLogin.TRIPIT_URL_IDENTITY_VALUE).val();
    if (elementTarget && elementTarget.attr(tripitLogin.ELEMENT_TARGET) == tripitLogin.TRIPIT_ELEMENT_TARGET) {
        var loginWindow = window.open(connectorPath, tripitLogin.WINDOW_OPEN, tripitLogin.TRIPIT_WIDTH + tripitLogin.WINDOW_WIDTH + tripitLogin.TRIPIT_HEIGHT + tripitLogin.WINDOW_HEIGHT + tripitLogin.EMPTY_STRING);
        var timer = setInterval(function() {   
            if(loginWindow.closed) {  
                clearInterval(timer);  
                $("#socialConnectorsGrid").data("kendoGrid").dataSource.read();
                refreshMessage();  
            }  
        }, 1000);
    }
    if (elementTarget && elementTarget.attr(tripitLogin.ELEMENT_TARGET) == tripitLogin.ELEMENT_TARGET_DISCONNECT) {
        var disconnectPath = connectorPath + tripitLogin.TRIPIT_DATA;
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
