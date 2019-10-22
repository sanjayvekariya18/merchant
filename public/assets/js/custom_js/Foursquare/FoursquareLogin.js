var foursquareLogin = {
    CONNECT_FOURSQUARE: 'a[rel^=connect_Foursquare]',
    FOURSQUARE_BUTTON_CONNECT: 'button[rel^=connect_Foursquare]',
    FOURSQUARE_ELEMENT_TARGET: 'connect_Foursquare',
    WINDOW_OPEN: 'mywindow',
    WINDOW_WIDTH: 500,
    WINDOW_HEIGHT: 400,
    FOURSQUARE_DISCONNECT: 'a[rel^=disconnect_Foursquare]',
    FOURSQUARE_BUTTON_DISCONNECT: 'button[rel^=disconnect_Foursquare]',
    ELEMENT_TARGET_DISCONNECT: 'disconnect_Foursquare',
    READ_ATTRIBUTE: 'name',
    ELEMENT_TARGET: 'rel',
    FOURSQUARE_WIDTH: 'width=',
    FOURSQUARE_HEIGHT: ',height=',
    EMPTY_STRING: '',
    FOURSQUARE_DATA: '_social/disconnect',
    FOURSQUARE_URL_IDENTITY_VALUE: "#foursquareConnectorUrl",
    CLICK_EVENT_VALUE: 'click',
    CONNECTOR_TYPE:'connector',
};

jQuery('body').on('click', 'button', function(){
    var elementTarget = jQuery(this).parent();
    var connectorPath = jQuery(foursquareLogin.FOURSQUARE_URL_IDENTITY_VALUE).val();
    if (elementTarget && elementTarget.attr(foursquareLogin.ELEMENT_TARGET) == foursquareLogin.FOURSQUARE_ELEMENT_TARGET) {
        var loginWindow = window.open(connectorPath+'/'+foursquareLogin.CONNECTOR_TYPE, foursquareLogin.WINDOW_OPEN, foursquareLogin.FOURSQUARE_WIDTH + foursquareLogin.WINDOW_WIDTH + foursquareLogin.FOURSQUARE_HEIGHT + foursquareLogin.WINDOW_HEIGHT + foursquareLogin.EMPTY_STRING);
        var timer = setInterval(function() {   
            if(loginWindow.closed) {  
                clearInterval(timer);  
                $("#socialConnectorsGrid").data("kendoGrid").dataSource.read();  
                refreshMessage(); 
            }  
        }, 1000);
    }
    if (elementTarget && elementTarget.attr(foursquareLogin.ELEMENT_TARGET) == foursquareLogin.ELEMENT_TARGET_DISCONNECT) {
        var disconnectPath = connectorPath + foursquareLogin.FOURSQUARE_DATA;
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
