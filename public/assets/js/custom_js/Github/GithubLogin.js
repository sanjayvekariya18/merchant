var githubLogin = {
    CONNECT_GITHUB: 'a[rel^=connect_Github]',
    GITHUB_BUTTON_CONNECT: 'button[rel^=connect_Github]',
    GITHUB_ELEMENT_TARGET: 'connect_Github',
    WINDOW_OPEN: 'mywindow',
    WINDOW_WIDTH: 500,
    WINDOW_HEIGHT: 400,
    GITHUB_DISCONNECT: 'a[rel^=disconnect_Github]',
    GITHUB_BUTTON_DISCONNECT: 'button[rel^=disconnect_Github]',
    ELEMENT_TARGET_DISCONNECT: 'disconnect_Github',
    READ_ATTRIBUTE: 'name',
    ELEMENT_TARGET: 'rel',
    GITHUB_WIDTH: 'width=',
    GITHUB_HEIGHT: ',height=',
    EMPTY_STRING: '',
    GITHUB_DATA: '_social/disconnect',
    GITHUB_URL_IDENTITY_VALUE: "#githubConnectorUrl",
    CLICK_EVENT_VALUE: 'click',
    CONNECTOR_TYPE:'connector',
};

jQuery('body').on('click', 'button', function(){
    var elementTarget = jQuery(this).parent();
    var connectorPath = jQuery(githubLogin.GITHUB_URL_IDENTITY_VALUE).val();
    if (elementTarget && elementTarget.attr(githubLogin.ELEMENT_TARGET) == githubLogin.GITHUB_ELEMENT_TARGET) {
        var loginWindow = window.open(connectorPath+'/'+githubLogin.CONNECTOR_TYPE, githubLogin.WINDOW_OPEN, githubLogin.GITHUB_WIDTH + githubLogin.WINDOW_WIDTH + githubLogin.GITHUB_HEIGHT + githubLogin.WINDOW_HEIGHT + githubLogin.EMPTY_STRING);
        var timer = setInterval(function() {   
            if(loginWindow.closed) {  
                clearInterval(timer);  
                $("#socialConnectorsGrid").data("kendoGrid").dataSource.read();  
                refreshMessage(); 
            }  
        }, 1000);
    }
    if (elementTarget && elementTarget.attr(githubLogin.ELEMENT_TARGET) == githubLogin.ELEMENT_TARGET_DISCONNECT) {
        var disconnectPath = connectorPath + githubLogin.GITHUB_DATA;
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
