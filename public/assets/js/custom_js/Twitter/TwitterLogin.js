var twitterLogin = {
    CONNECT_TWITTER: 'a[rel^=connect_Twitter]',
    TWITTER_BUTTON_CONNECT: 'button[rel^=connect_Twitter]',
    TWITTER_ELEMENT_TARGET: 'connect_Twitter',
    WINDOW_OPEN: 'mywindow',
    WINDOW_WIDTH: 500,
    WINDOW_HEIGHT: 400,
    TWITTER_DISCONNECT: 'a[rel^=disconnect_Twitter]',
    TWITTER_BUTTON_DISCONNECT: 'button[rel^=disconnect_Twitter]',
    ELEMENT_TARGET_DISCONNECT: 'disconnect_Twitter',
    READ_ATTRIBUTE: 'name',
    ELEMENT_TARGET: 'rel',
    TWITTER_WIDTH: 'width=',
    TWITTER_HEIGHT: ',height=',
    EMPTY_STRING: '',
    TWITTER_DATA: '_social/disconnect',
    TWITTER_URL_IDENTITY_VALUE: "#twitterConnectorUrl",
    CLICK_EVENT_VALUE: 'click',
    CONNECTOR_TYPE:'connector',
};

jQuery('body').on('click', 'button', function(){
    var elementTarget = jQuery(this).parent();
    var connectorPath = jQuery(twitterLogin.TWITTER_URL_IDENTITY_VALUE).val();
    if (elementTarget && elementTarget.attr(twitterLogin.ELEMENT_TARGET) == twitterLogin.TWITTER_ELEMENT_TARGET) {
        var loginWindow = window.open(connectorPath+'/'+twitterLogin.CONNECTOR_TYPE, twitterLogin.WINDOW_OPEN, twitterLogin.TWITTER_WIDTH + twitterLogin.WINDOW_WIDTH + twitterLogin.TWITTER_HEIGHT + twitterLogin.WINDOW_HEIGHT + twitterLogin.EMPTY_STRING);
        var timer = setInterval(function() {   
            if(loginWindow.closed) {  
                clearInterval(timer);  
                $("#socialConnectorsGrid").data("kendoGrid").dataSource.read();
                refreshMessage();  
            }  
        }, 1000);
    }
    if (elementTarget && elementTarget.attr(twitterLogin.ELEMENT_TARGET) == twitterLogin.ELEMENT_TARGET_DISCONNECT) {
        var disconnectPath = connectorPath + twitterLogin.TWITTER_DATA;
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
