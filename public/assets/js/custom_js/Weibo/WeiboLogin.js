var weiboLogin = {
    CONNECT_WEIBO: 'a[rel^=connect_Weibo]',
    WEIBO_BUTTON_CONNECT: 'button[rel^=connect_Weibo]',
    WEIBO_ELEMENT_TARGET: 'connect_Weibo',
    WINDOW_OPEN: 'mywindow',
    WINDOW_WIDTH: 500,
    WINDOW_HEIGHT: 400,
    WEIBO_DISCONNECT: 'a[rel^=disconnect_Weibo]',
    WEIBO_BUTTON_DISCONNECT: 'button[rel^=disconnect_Weibo]',
    ELEMENT_TARGET_DISCONNECT: 'disconnect_Weibo',
    READ_ATTRIBUTE: 'name',
    ELEMENT_TARGET: 'rel',
    WEIBO_WIDTH: 'width=',
    WEIBO_HEIGHT: ',height=',
    EMPTY_STRING: '',
    WEIBO_DATA: '/disconnect',
    WEIBO_URL_IDENTITY_VALUE: "#weiboConnectorUrl",
    CLICK_EVENT_VALUE: 'click',
};

jQuery('body').on('click', 'img', function(){
    var elementTarget = jQuery(this).parent();
    var connectorPath = jQuery(weiboLogin.WEIBO_URL_IDENTITY_VALUE).val();
    if (elementTarget && elementTarget.attr(weiboLogin.ELEMENT_TARGET) == weiboLogin.WEIBO_ELEMENT_TARGET) {
        var newwindow = window.open(connectorPath, weiboLogin.WINDOW_OPEN, weiboLogin.WEIBO_WIDTH + weiboLogin.WINDOW_WIDTH + weiboLogin.WEIBO_HEIGHT + weiboLogin.WINDOW_HEIGHT + weiboLogin.EMPTY_STRING);
        
    }
    if (elementTarget && elementTarget.attr(weiboLogin.ELEMENT_TARGET) == weiboLogin.ELEMENT_TARGET_DISCONNECT) {
        window.location.href = connectorPath + weiboLogin.WEIBO_DATA;
    }
});
