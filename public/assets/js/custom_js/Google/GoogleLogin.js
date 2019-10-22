var googleLogin = {
    CONNECT_GOOGLE: 'a[rel^=connect_Google]',
    GOOGLE_BUTTON_CONNECT: 'button[rel^=connect_Google]',
    GOOGLE_ELEMENT_TARGET: 'connect_Google',
    WINDOW_OPEN: 'mywindow',
    WINDOW_WIDTH: 500,
    WINDOW_HEIGHT: 400,
    GOOGLE_DISCONNECT: 'a[rel^=disconnect_Google]',
    GOOGLE_BUTTON_DISCONNECT: 'button[rel^=disconnect_Google]',
    ELEMENT_TARGET_DISCONNECT: 'disconnect_Google',
    READ_ATTRIBUTE: 'name',
    ELEMENT_TARGET: 'rel',
    GOOGLE_WIDTH: 'width=',
    GOOGLE_HEIGHT: ',height=',
    EMPTY_STRING: '',
    GOOGLE_DATA: '_social/disconnect',
    GOOGLE_URL_IDENTITY_VALUE: "#googleConnectorUrl",
    CLICK_EVENT_VALUE: 'click',
    CONNECTOR_TYPE:'connector',
};

jQuery('body').on('click', 'button', function(){
    var elementTarget = jQuery(this).parent();
    var connectorPath = jQuery(googleLogin.GOOGLE_URL_IDENTITY_VALUE).val();
    if (elementTarget && elementTarget.attr(googleLogin.ELEMENT_TARGET) == googleLogin.GOOGLE_ELEMENT_TARGET) {
        var loginWindow = window.open(connectorPath+'/'+googleLogin.CONNECTOR_TYPE, googleLogin.WINDOW_OPEN, googleLogin.GOOGLE_WIDTH + googleLogin.WINDOW_WIDTH + googleLogin.GOOGLE_HEIGHT + googleLogin.WINDOW_HEIGHT + googleLogin.EMPTY_STRING);
        var timer = setInterval(function() {   
            if(loginWindow.closed) {  
                clearInterval(timer); 
                $("#socialConnectorsGrid").data("kendoGrid").dataSource.read();
                refreshMessage();
            }  
        }, 1000);        
    }
    if (elementTarget && elementTarget.attr(googleLogin.ELEMENT_TARGET) == googleLogin.ELEMENT_TARGET_DISCONNECT) {
        var disconnectPath = connectorPath + googleLogin.GOOGLE_DATA;
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
