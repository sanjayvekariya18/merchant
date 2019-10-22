var flickrLogin = {
    CONNECT_FLICKR: 'a[rel^=connect_Flickr]',
    FLICKR_BUTTON_CONNECT: 'button[rel^=connect_Flickr]',
    FLICKR_ELEMENT_TARGET: 'connect_Flickr',
    WINDOW_OPEN: 'mywindow',
    WINDOW_WIDTH: 500,
    WINDOW_HEIGHT: 400,
    FLICKR_DISCONNECT: 'a[rel^=disconnect_Flickr]',
    FLICKR_BUTTON_DISCONNECT: 'button[rel^=disconnect_Flickr]',
    ELEMENT_TARGET_DISCONNECT: 'disconnect_Flickr',
    READ_ATTRIBUTE: 'name',
    ELEMENT_TARGET: 'rel',
    FLICKR_WIDTH: 'width=',
    FLICKR_HEIGHT: ',height=',
    EMPTY_STRING: '',
    FLICKR_DATA: '_social/disconnect',
    FLICKR_URL_IDENTITY_VALUE: "#flickrConnectorUrl",
    CLICK_EVENT_VALUE: 'click',
    CONNECTOR_TYPE:'connector',
};

jQuery('body').on('click', 'button', function(){
    var elementTarget = jQuery(this).parent();
    var connectorPath = jQuery(flickrLogin.FLICKR_URL_IDENTITY_VALUE).val();
    if (elementTarget && elementTarget.attr(flickrLogin.ELEMENT_TARGET) == flickrLogin.FLICKR_ELEMENT_TARGET) {
        var loginWindow = window.open(connectorPath+'/'+flickrLogin.CONNECTOR_TYPE, flickrLogin.WINDOW_OPEN, flickrLogin.FLICKR_WIDTH + flickrLogin.WINDOW_WIDTH + flickrLogin.FLICKR_HEIGHT + flickrLogin.WINDOW_HEIGHT + flickrLogin.EMPTY_STRING);
        var timer = setInterval(function() {   
            if(loginWindow.closed) {  
                clearInterval(timer);  
                $("#socialConnectorsGrid").data("kendoGrid").dataSource.read();  
                refreshMessage(); 
            }  
        }, 1000);
    }
    if (elementTarget && elementTarget.attr(flickrLogin.ELEMENT_TARGET) == flickrLogin.ELEMENT_TARGET_DISCONNECT) {
        var disconnectPath = connectorPath + flickrLogin.FLICKR_DATA;
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
