var linkedinLogin = {
    CONNECT_LINKEDIN: 'a[rel^=connect_Linkedin]',
    LINKEDIN_BUTTON_CONNECT: 'button[rel^=connect_Linkedin]',
    LINKEDIN_ELEMENT_TARGET: 'connect_Linkedin',
    WINDOW_OPEN: 'mywindow',
    WINDOW_WIDTH: 500,
    WINDOW_HEIGHT: 400,
    LINKEDIN_DISCONNECT: 'a[rel^=disconnect_Linkedin]',
    LINKEDIN_BUTTON_DISCONNECT: 'button[rel^=disconnect_Linkedin]',
    ELEMENT_TARGET_DISCONNECT: 'disconnect_Linkedin',
    READ_ATTRIBUTE: 'name',
    ELEMENT_TARGET: 'rel',
    LINKEDIN_WIDTH: 'width=',
    LINKEDIN_HEIGHT: ',height=',
    EMPTY_STRING: '',
    LINKEDIN_DATA: '_social/disconnect',
    LINKEDIN_URL_IDENTITY_VALUE: "#linkedinConnectorUrl",
    CLICK_EVENT_VALUE: 'click',
    CONNECTOR_TYPE:'connector',
};

jQuery('body').on('click', 'button', function(){
    var elementTarget = jQuery(this).parent();
    var connectorPath = jQuery(linkedinLogin.LINKEDIN_URL_IDENTITY_VALUE).val();
    if (elementTarget && elementTarget.attr(linkedinLogin.ELEMENT_TARGET) == linkedinLogin.LINKEDIN_ELEMENT_TARGET) {
        var loginWindow = window.open(connectorPath+'/'+linkedinLogin.CONNECTOR_TYPE, linkedinLogin.WINDOW_OPEN, linkedinLogin.LINKEDIN_WIDTH + linkedinLogin.WINDOW_WIDTH + linkedinLogin.LINKEDIN_HEIGHT + linkedinLogin.WINDOW_HEIGHT + linkedinLogin.EMPTY_STRING);
        var timer = setInterval(function() {   
            if(loginWindow.closed) {  
                clearInterval(timer);  
                $("#socialConnectorsGrid").data("kendoGrid").dataSource.read(); 
                refreshMessage();   
            }  
        }, 1000);
    }
    if (elementTarget && elementTarget.attr(linkedinLogin.ELEMENT_TARGET) == linkedinLogin.ELEMENT_TARGET_DISCONNECT) {
        var disconnectPath = connectorPath + linkedinLogin.LINKEDIN_DATA;
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
