var stravaLogin = {
    CONNECT_STRAVA: 'a[rel^=connect_Strava]',
    STRAVA_BUTTON_CONNECT: 'button[rel^=connect_Strava]',
    STRAVA_ELEMENT_TARGET: 'connect_Strava',
    WINDOW_OPEN: 'mywindow',
    WINDOW_WIDTH: 500,
    WINDOW_HEIGHT: 400,
    STRAVA_DISCONNECT: 'a[rel^=disconnect_Strava]',
    STRAVA_BUTTON_DISCONNECT: 'button[rel^=disconnect_Strava]',
    ELEMENT_TARGET_DISCONNECT: 'disconnect_Strava',
    READ_ATTRIBUTE: 'name',
    ELEMENT_TARGET: 'rel',
    STRAVA_WIDTH: 'width=',
    STRAVA_HEIGHT: ',height=',
    EMPTY_STRING: '',
    STRAVA_DATA: '_social/disconnect',
    STRAVA_URL_IDENTITY_VALUE: "#stravaConnectorUrl",
    CLICK_EVENT_VALUE: 'click',
    CONNECTOR_TYPE:'connector',
};

jQuery('body').on('click', 'button', function(){
    var elementTarget = jQuery(this).parent();
    var connectorPath = jQuery(stravaLogin.STRAVA_URL_IDENTITY_VALUE).val();
    if (elementTarget && elementTarget.attr(stravaLogin.ELEMENT_TARGET) == stravaLogin.STRAVA_ELEMENT_TARGET) {
        var loginWindow = window.open(connectorPath+'/'+stravaLogin.CONNECTOR_TYPE, stravaLogin.WINDOW_OPEN, stravaLogin.STRAVA_WIDTH + stravaLogin.WINDOW_WIDTH + stravaLogin.STRAVA_HEIGHT + stravaLogin.WINDOW_HEIGHT + stravaLogin.EMPTY_STRING);
        var timer = setInterval(function() {   
            if(loginWindow.closed) {  
                clearInterval(timer);  
                $("#socialConnectorsGrid").data("kendoGrid").dataSource.read();
                refreshMessage();  
            }  
        }, 1000);
    }
    if (elementTarget && elementTarget.attr(stravaLogin.ELEMENT_TARGET) == stravaLogin.ELEMENT_TARGET_DISCONNECT) {
        var disconnectPath = connectorPath + stravaLogin.STRAVA_DATA;
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
