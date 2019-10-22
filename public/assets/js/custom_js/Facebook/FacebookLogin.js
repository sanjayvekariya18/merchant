var facebookLogin = {
    CONNECT_FACEBOOK: 'a[rel^=connect_Facebook]',
    FACEBOOK_BUTTON_CONNECT: 'button[rel^=connect_Facebook]',
    FACEBOOK_ELEMENT_TARGET: 'connect_Facebook',
    WINDOW_OPEN: 'mywindow',
    WINDOW_WIDTH: 500,
    WINDOW_HEIGHT: 400,
    FACEBOOK_DISCONNECT: 'a[rel^=disconnect_Facebook]',
    FACEBOOK_BUTTON_DISCONNECT: 'button[rel^=disconnect_Facebook]',
    ELEMENT_TARGET_DISCONNECT: 'disconnect_Facebook',
    READ_ATTRIBUTE: 'name',
    ELEMENT_TARGET: 'rel',
    FACEBOOK_WIDTH: 'width=',
    FACEBOOK_HEIGHT: ',height=',
    EMPTY_STRING: '',
    FACEBOOK_DATA: '_social/disconnect',
    FACEBOOK_URL_IDENTITY_VALUE: "#facebookConnectorUrl",
    CLICK_EVENT_VALUE: 'click',
    CONNECTOR_TYPE:'connector',
};

jQuery('body').on('click', 'button', function(){
    var elementTarget = jQuery(this).parent();
    var connectorPath = jQuery(facebookLogin.FACEBOOK_URL_IDENTITY_VALUE).val();
    if (elementTarget && elementTarget.attr(facebookLogin.ELEMENT_TARGET) == facebookLogin.FACEBOOK_ELEMENT_TARGET) {
        var loginWindow = window.open(connectorPath+'/'+facebookLogin.CONNECTOR_TYPE, facebookLogin.WINDOW_OPEN, facebookLogin.FACEBOOK_WIDTH + facebookLogin.WINDOW_WIDTH + facebookLogin.FACEBOOK_HEIGHT + facebookLogin.WINDOW_HEIGHT + facebookLogin.EMPTY_STRING);
        var timer = setInterval(function() {   
            if(loginWindow.closed) {  
                clearInterval(timer);  
                $("#socialConnectorsGrid").data("kendoGrid").dataSource.read(); 
                refreshMessage();
            }  
        }, 1000);        
    }
    if (elementTarget && elementTarget.attr(facebookLogin.ELEMENT_TARGET) == facebookLogin.ELEMENT_TARGET_DISCONNECT) {
        var disconnectPath = connectorPath + facebookLogin.FACEBOOK_DATA;
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
