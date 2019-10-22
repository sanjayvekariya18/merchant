var regionActivityValue = {
        HTTP_HEADER: 'http://',
        KEYWORD_LIST_PAGE_PATH: '/public/search_keywords',
        ACTIVITY_IFRAME_VALUE:'activityIframe',
        RESULT_TREEVIEW_REGION:'result_treeviewRegion',
        RESULT_TREEVIEW_ACTIVITY:'result_treeviewActivity',
};
function setRegionActivityValue(keyword_url,keywordId) {
    var keyword =$('#keyword').val();
    if(keyword == ''){
        alert("Please Add keyword");
             return false;
    }else{ 
     activityRegionIframe = document.getElementById(regionActivityValue.ACTIVITY_IFRAME_VALUE);
     activityRegionIframeInnerDocument = activityRegionIframe.contentDocument || activityRegionIframe.contentWindow.document;
     regionIframeInput = activityRegionIframeInnerDocument.getElementById(regionActivityValue.RESULT_TREEVIEW_REGION);
     var regionId = regionIframeInput.innerHTML;
     activityIframeInput = activityRegionIframeInnerDocument.getElementById(regionActivityValue.RESULT_TREEVIEW_ACTIVITY);
     var activityId = activityIframeInput.innerHTML;         
        jQuery.ajax({
                type:'GET',
                url: keyword_url,
                data: {
                        keyword: keyword,
                        keywordId:keywordId,
                        regionId: regionId,
                        activityId: activityId
                },
                success: function (imageData) {
                    var getUrl = window.location;
                    var baseUrl = getUrl .protocol + "//" + getUrl.host + "/" + getUrl.pathname.split('/')[1];
                    window.location = baseUrl + regionActivityValue.KEYWORD_LIST_PAGE_PATH;
                }
        });
    }
}
function goBack() {
    var getUrl = window.location;
    var baseUrl = getUrl .protocol + "//" + getUrl.host + "/" + getUrl.pathname.split('/')[1];
    window.location = baseUrl + regionActivityValue.KEYWORD_LIST_PAGE_PATH;

}