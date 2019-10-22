var instagramLogin = {
    CONNECT_INSTAGRAM: 'a[rel^=connect_Instagram]',
    INSTAGRAM_BUTTON_CONNECT: 'button[rel^=connect_Instagram]',
    INSTAGRAM_ELEMENT_TARGET: 'connect_Instagram',
    WINDOW_OPEN: 'mywindow',
    WINDOW_WIDTH: 500,
    WINDOW_HEIGHT: 400,
    INSTAGRAM_DISCONNECT: 'a[rel^=disconnect_Instagram]',
    INSTAGRAM_BUTTON_DISCONNECT: 'button[rel^=disconnect_Instagram]',
    ELEMENT_TARGET_DISCONNECT: 'disconnect_Instagram',
    READ_ATTRIBUTE: 'name',
    ELEMENT_TARGET: 'rel',
    INSTAGRAM_WIDTH: 'width=',
    INSTAGRAM_HEIGHT: ',height=',
    EMPTY_STRING: '',
    INSTAGRAM_DATA: '_social/disconnect',
    INSTAGRAM_URL_IDENTITY_VALUE: "#instagramConnectorUrl",
    CLICK_EVENT_VALUE: 'click',
    CONNECTOR_TYPE:'connector',
};

jQuery('body').on('click', 'button', function(){
    var elementTarget = jQuery(this).parent();
    var connectorPath = jQuery(instagramLogin.INSTAGRAM_URL_IDENTITY_VALUE).val();
    if (elementTarget && elementTarget.attr(instagramLogin.ELEMENT_TARGET) == instagramLogin.INSTAGRAM_ELEMENT_TARGET) {
        var loginWindow = window.open(connectorPath+'/'+instagramLogin.CONNECTOR_TYPE, instagramLogin.WINDOW_OPEN, instagramLogin.INSTAGRAM_WIDTH + instagramLogin.WINDOW_WIDTH + instagramLogin.INSTAGRAM_HEIGHT + instagramLogin.WINDOW_HEIGHT + instagramLogin.EMPTY_STRING);
        var timer = setInterval(function() {   
            if(loginWindow.closed) {  
                clearInterval(timer);  
                $("#socialConnectorsGrid").data("kendoGrid").dataSource.read();
                refreshMessage();   
            }  
        }, 1000);
    }
    if (elementTarget && elementTarget.attr(instagramLogin.ELEMENT_TARGET) == instagramLogin.ELEMENT_TARGET_DISCONNECT) {
        var disconnectPath = connectorPath + instagramLogin.INSTAGRAM_DATA;
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
