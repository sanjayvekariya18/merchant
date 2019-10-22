/**
 *
 * File is Display activity tree view.
 *
 *
 * @method activityTreeData()
 
 */
var jQuery = jQuery.noConflict();
var activityRegionTreeView = {
    ACTIVITY_TREE_URL: '../public/assets/kendoui-treeview-categories/js/utree/js/ActivityTree.json',
    ACTIVITY_TREE_AUTO_COMPLETE_URL: '../public/assets/kendoui-treeview-categories/js/utree/js/ActivityAutoComplete.json',
    ACTIVITY_TREE_MODULE_NAME: 'moduleActivity',
    ACTIVITY_TREE_RESULTS: 'result_treeviewActivity',
    ACTIVITY_TREE_AUTO_COMPLETE_NAME: 'selectActivity',
    ACTIVITY_TREE_AUTO_COMPLETE_LABEL: 'Search Activity',
    ACTIVITY_TREE_SELECTED_VALUE: 'selectActivity',
    ACTIVITY_TREE_RESULT_TEXT: "resultTextActivity",
    REGION_TREE_URL: '../public/assets/kendoui-treeview-categories/js/utree/js/RegionTree.json',
    REGION_TREE_MODULE_NAME: 'moduleRegion',
    REGION_TREE_RESULTS: 'result_treeviewRegion',
    REGION_TREE_AUTO_COMPLETE_URL: '../public/assets/kendoui-treeview-categories/js/utree/js/RegionAutoComplete.json',
    REGION_TREE_AUTO_COMPLETE_NAME: 'selectRegion',
    REGION_TREE_AUTO_COMPLETE_LABEL: "Search Region",
    REGION_TREE_SELECTED_VALUE: 'setRegionValue',
    REGION_TREE_RESULT_TEXT: "resultText",
    SOHYPER_TREE_JS: '../public/assets/kendoui-treeview-categories/js/kendoui/tree/SohyperTree.js',
    SOHYPER_AUTOCOMPLATE_OVERRIDE_ORIGINAL: '%252528autoCompleteUrl%25252C%252520function%252520%252528JsonReturnData%252529%252520%25257B',
    SOHYPER_AUTOCOMPLATE_OVERRIDE_VARIABLE: ' autoCompleteItems=JsonReturnData; ',
    REGION_ACTIVITY_BLANK_VALUE: '',
    TREE_NODE_PATH_FORWARD_SLASH: /\//g,
    ACTIVITY_REGION_SPLIT_VALUE:',',
    HTML_SCRIPT_TAG_LANGUAGE: 'javascript',
    HTML_SCRIPT_TAG_TYPE: "text/javascript",
    REGEXP_FOR_NO_CONFLICT: /(.*?)Conflict\(\)\;/,
    TREE_NODE_PATH_SPLICE_END: 2,
    FIRST_NODE_INDEX: 0,
    CHECK_IS_NOT_ZERO: 1,
    GET_JSON_STRING: 'jQuery.getJSON',
    IMAGE_REGION_VALUE:'imageRegionValue',
    IMAGE_ACTIVITY_VALUE:'imageActivityValue',
    REGION_ACTIVITY_TEXT_VALUE:'text',
    SQUARE_BRACKET_OPEN: '[',
    SQUARE_BRACKET_CLOSE: ']',
    HASH_SYMBOL:'#',
    HEAD_VALUE:'HEAD',
    SCRIPT_VALUE:"script",
    KENDO_TREE_VIEW_VALUE:'kendoTreeView',
    KENDO_ITEM_VALUE:".k-item",
    EXPANDED_VALUE:"expanded",
    CHANGE_VALUE:"change",
    KENDO_STATE_SELECTED:"k-state-selected",
    CHECKBOX_NAME_VALUE:"input[type='checkbox'][name='c_",
    CHECKBOX_VALUE_BRACKET_OVER:"']",
    CLICK_VALUE:'click',
    DIV_VALUE:'div',
    SPAN_LAST_VALUE:'span:last',
}

/**
 * Function is get the image region/activity in kendo window. 
 */
var autoCompleteItems;
function imageCategoryView(){
jQuery(document).ready(function () {
    jQuery.ajaxSetup({
        async: false
    });
    var sohyperTreeJs = jQuery.ajax({
        type: activityRegionTreeView.DATA_TYPE,
        url:  activityRegionTreeView.SOHYPER_TREE_JS,
        dataType: activityRegionTreeView.REGION_ACTIVITY_TEXT_VALUE,
        async: false
    }).responseText;
    sohyperTreeJs = sohyperTreeJs.replace(activityRegionTreeView.REGEXP_FOR_NO_CONFLICT, activityRegionTreeView.REGION_ACTIVITY_BLANK_VALUE);
    var overridePrefix = activityRegionTreeView.GET_JSON_STRING + unescape(decodeURIComponent(unescape(activityRegionTreeView.SOHYPER_AUTOCOMPLATE_OVERRIDE_ORIGINAL)));
    sohyperTreeJs = sohyperTreeJs.replace(overridePrefix, overridePrefix + activityRegionTreeView.SOHYPER_AUTOCOMPLATE_OVERRIDE_VARIABLE);
    var htmlHeadTag = document.getElementsByTagName(activityRegionTreeView.HEAD_VALUE).item(activityRegionTreeView.FIRST_NODE_INDEX);
    var htmlScriptTag = document.createElement(activityRegionTreeView.SCRIPT_VALUE);
    htmlScriptTag.language = activityRegionTreeView.HTML_SCRIPT_TAG_LANGUAGE;
    htmlScriptTag.type = activityRegionTreeView.HTML_SCRIPT_TAG_TYPE;
    htmlScriptTag.defer = true;
    htmlScriptTag.text = sohyperTreeJs;
    htmlHeadTag.appendChild(htmlScriptTag);
    var isMapping = true;
    var mappingAutoComplete = true;
    var checkParent = true;
    var isRadio = false;
    
    var regionTreeUrl = activityRegionTreeView.REGION_TREE_URL;
    var regionTreeName = activityRegionTreeView.REGION_TREE_MODULE_NAME;
    var regionResultId = activityRegionTreeView.REGION_TREE_RESULTS;
    var regionAutoCompleteUrl = activityRegionTreeView.REGION_TREE_AUTO_COMPLETE_URL;
    var regionAutoCompleteName = activityRegionTreeView.REGION_TREE_AUTO_COMPLETE_NAME;
    var regionAutoLabel = activityRegionTreeView.REGION_TREE_AUTO_COMPLETE_LABEL;
    var setRegionValueId = activityRegionTreeView.REGION_TREE_SELECTED_VALUE;
    var regionResultText = activityRegionTreeView.REGION_TREE_RESULT_TEXT;
    sohyperTree(checkParent, regionTreeUrl, regionTreeName, regionResultId, regionResultText, isMapping, regionAutoCompleteName, setRegionValueId, isRadio);
    sohyperAutoComplete(mappingAutoComplete, regionAutoCompleteUrl, regionAutoCompleteName, regionAutoLabel, regionTreeName, regionResultId, setRegionValueId);
    var regionValue=localStorage.getItem(activityRegionTreeView.IMAGE_REGION_VALUE);
    if(regionValue != null ){
        expandCategoryTree(regionValue,activityRegionTreeView.REGION_TREE_AUTO_COMPLETE_URL, activityRegionTreeView.REGION_TREE_MODULE_NAME,activityRegionTreeView.REGION_AUTO_COMPLETE_NAME, autoCompleteItems);
    }
    var activityTreeUrl = activityRegionTreeView.ACTIVITY_TREE_URL;
    var activityTreeName = activityRegionTreeView.ACTIVITY_TREE_MODULE_NAME;
    var activityResultId = activityRegionTreeView.ACTIVITY_TREE_RESULTS;
    var activityAutoCompleteUrl = activityRegionTreeView.ACTIVITY_TREE_AUTO_COMPLETE_URL;
    var activityAutoCompleteName = activityRegionTreeView.ACTIVITY_TREE_AUTO_COMPLETE_NAME;
    var activityAutoLabel = activityRegionTreeView.ACTIVITY_TREE_AUTO_COMPLETE_LABEL;
    var setActivityValueId = activityRegionTreeView.ACTIVITY_TREE_SELECTED_VALUE;
    var activityResultText = activityRegionTreeView.ACTIVITY_TREE_RESULT_TEXT;
    sohyperTree(checkParent, activityTreeUrl, activityTreeName, activityResultId, activityResultText, isMapping, activityAutoCompleteName, setActivityValueId, isRadio);
    sohyperAutoComplete(mappingAutoComplete, activityAutoCompleteUrl, activityAutoCompleteName, activityAutoLabel, activityTreeName, activityResultId, setActivityValueId);
    var activityValue=localStorage.getItem(activityRegionTreeView.IMAGE_ACTIVITY_VALUE);
    if(activityValue != null ){
        expandCategoryTree(activityValue,activityRegionTreeView.ACTIVITY_TREE_AUTO_COMPLETE_URL, activityRegionTreeView.ACTIVITY_TREE_MODULE_NAME,activityRegionTreeView.ACTIVITY_AUTO_COMPLETE_NAME, autoCompleteItems);
    }
});
}
/**
 * Function is get the Unique value of selected category.
 * @param integer nodeValue.
 * @param integer nodeIndex.
 * @param integer nodeSelf.
 */
function onlyUniqueItem(nodeValue, nodeIndex, nodeSelf) {
    return nodeSelf.indexOf(nodeValue) === nodeIndex;
}
/**
 * Function is use for expand the image category tree with selected value.
 * @param array nodeIdList.
 * @param string autoCompleteUrl.
 * @param string treeName.
 * @param string autoCompleteName.
 * @param integer autoCompleteJsonItems.
 */
function expandCategoryTree(nodeIdList, autoCompleteUrl, treeName, autoCompleteName, autoCompleteJsonItems) {
    var nodeId = [];
    var nodeId = nodeIdList.split(activityRegionTreeView.ACTIVITY_REGION_SPLIT_VALUE);
    nodeId = nodeId.filter(onlyUniqueItem);
    var nodeLength = (nodeId.length);
    var treeView = jQuery(activityRegionTreeView.HASH_SYMBOL + treeName).data(activityRegionTreeView.KENDO_TREE_VIEW_VALUE);
    treeView.setOptions({
        animation: false
    });
    treeView.collapse(activityRegionTreeView.KENDO_ITEM_VALUE);
    for (nodeCount = activityRegionTreeView.FIRST_NODE_INDEX; nodeCount < nodeLength; nodeCount++) {
        var nodeDetail = getJsonObjects(autoCompleteJsonItems, activityRegionTreeView.REGION_ACTIVITY_TEXT_VALUE, nodeId[nodeCount]);
        if (nodeDetail[activityRegionTreeView.FIRST_NODE_INDEX] == undefined) {
            continue;
        }
        var treeDataSource = treeView.dataSource;
        var treeNodePath = nodeDetail[activityRegionTreeView.FIRST_NODE_INDEX].path;
        treeNodePath = treeNodePath.replace(activityRegionTreeView.TREE_NODE_PATH_FORWARD_SLASH, activityRegionTreeView.ACTIVITY_REGION_SPLIT_VALUE);
        openCategoryTreePath(treeView, treeNodePath);
    }
    treeView.setOptions({
        animation: kendo.ui.TreeView.fn.options.animation
    });
}
/**
 * Function is use for display selected value.
 * @param array treeView.
 * @param string treeNodePath.
 */
function openCategoryTreePath(treeView, treeNodePath) {
    treeNodePath = JSON.parse(activityRegionTreeView.SQUARE_BRACKET_OPEN + treeNodePath + activityRegionTreeView.SQUARE_BRACKET_CLOSE);
    treeNodePath.splice(activityRegionTreeView.FIRST_NODE_INDEX, activityRegionTreeView.TREE_NODE_PATH_SPLICE_END);
    var treeDataSource = treeView.dataSource;
    var treeNode = treeDataSource.get(treeNodePath[activityRegionTreeView.FIRST_NODE_INDEX])
    while (treeNodePath.length > activityRegionTreeView.CHECK_IS_NOT_ZERO && (treeNode.expanded || treeNode.loaded())) {
        treeNode.set(activityRegionTreeView.EXPANDED_VALUE, true);
        treeNodePath.shift();
        treeNode = treeDataSource.get(treeNodePath[activityRegionTreeView.FIRST_NODE_INDEX]);
    }
    if (treeNodePath.length > activityRegionTreeView.CHECK_IS_NOT_ZERO) {
        treeDataSource.bind(activityRegionTreeView.CHANGE_VALUE, function expandLevel(changeEvent) {
            var lastNodeId = changeEvent.treeNode && changeEvent.treeNode.id;
            if (lastNodeId == treeNodePath[activityRegionTreeView.FIRST_NODE_INDEX]) {
                treeNodePath.shift();
                treeDataSource.unbind(activityRegionTreeView.CHANGE_VALUE, expandLevel);
                openTreePath(treeView, treeNodePath);
            }
        });
        treeDataSource.get(treeNodePath[activityRegionTreeView.FIRST_NODE_INDE]).set(activityRegionTreeView.EXPANDED_VALUE, true);
    } else {
        var currentNodeId = treeNodePath[treeNodePath.length - activityRegionTreeView.CHECK_IS_NOT_ZERO];
        treeNode = treeView.findByUid(treeDataSource.get(treeNodePath[activityRegionTreeView.FIRST_NODE_INDEX]).uid);
        jQuery(activityRegionTreeView.CHECKBOX_NAME_VALUE + currentNodeId + activityRegionTreeView.CHECKBOX_VALUE_BRACKET_OVER).trigger(activityRegionTreeView.CLICK_VALUE);
        jQuery(activityRegionTreeView.HASH_SYMBOL + currentNodeId).closest(activityRegionTreeView.DIV_VALUE).find(activityRegionTreeView.SPAN_LAST_VALUE).addClass(activityRegionTreeView.KENDO_STATE_SELECTED);
    }
}