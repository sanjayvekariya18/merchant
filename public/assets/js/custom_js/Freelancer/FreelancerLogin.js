var freelancerLogin = {
    CONNECT_FREELANCER: 'a[rel^=connect_Freelancer]',
    FREELANCER_BUTTON_CONNECT: 'button[rel^=connect_Freelancer]',
    FREELANCER_ELEMENT_TARGET: 'connect_Freelancer',
    WINDOW_OPEN: 'mywindow',
    WINDOW_WIDTH: 500,
    WINDOW_HEIGHT: 400,
    FREELANCER_DISCONNECT: 'a[rel^=disconnect_Freelancer]',
    FREELANCER_BUTTON_DISCONNECT: 'button[rel^=disconnect_Freelancer]',
    ELEMENT_TARGET_DISCONNECT: 'disconnect_Freelancer',
    READ_ATTRIBUTE: 'name',
    ELEMENT_TARGET: 'rel',
    FREELANCER_WIDTH: 'width=',
    FREELANCER_HEIGHT: ',height=',
    EMPTY_STRING: '',
    FREELANCER_DATA: '_social/disconnect',
    FREELANCER_URL_IDENTITY_VALUE: "#freelancerConnectorUrl",
    CLICK_EVENT_VALUE: 'click',
    CONNECTOR_TYPE:'connector',
};

jQuery('body').on('click', 'button', function(){
    var elementTarget = jQuery(this).parent();
    var connectorPath = jQuery(freelancerLogin.FREELANCER_URL_IDENTITY_VALUE).val();
    if (elementTarget && elementTarget.attr(freelancerLogin.ELEMENT_TARGET) == freelancerLogin.FREELANCER_ELEMENT_TARGET) {
        var loginWindow = window.open(connectorPath+'/'+freelancerLogin.CONNECTOR_TYPE, freelancerLogin.WINDOW_OPEN, freelancerLogin.FREELANCER_WIDTH + freelancerLogin.WINDOW_WIDTH + freelancerLogin.FREELANCER_HEIGHT + freelancerLogin.WINDOW_HEIGHT + freelancerLogin.EMPTY_STRING);
        var timer = setInterval(function() {   
            if(loginWindow.closed) {  
                clearInterval(timer);  
                $("#socialConnectorsGrid").data("kendoGrid").dataSource.read();
                refreshMessage(); 
            }  
        }, 1000);
    }
    if (elementTarget && elementTarget.attr(freelancerLogin.ELEMENT_TARGET) == freelancerLogin.ELEMENT_TARGET_DISCONNECT) {
        var disconnectPath = connectorPath + freelancerLogin.FREELANCER_DATA;
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
