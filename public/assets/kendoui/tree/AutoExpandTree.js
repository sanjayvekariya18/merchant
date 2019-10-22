var jQuery = jQuery.noConflict();

jQuery(document).ready(function () {
    // For kendo ui tree
    var constants = {
        NODE_COUNT: 0,
        TREE_INTERVAL: 1500,
    }
    var checkBox = false;
    var treeUrl = "../js/utree/js/RegionTree.json";
    var autoCompleteUrl = "../js/utree/js/RegionAutoComplete.json";
    var treeName = "treeview";
    var autoCompleteName = "select";
    var resultId = "result";
    var isMapping = false;
    var autoCompleteName = "";
    var setValueId = "";
    var isRadio = false;
    var resultText = "resultText";
    sohyperTree(checkBox, treeUrl, treeName, resultId,resultText,isMapping, autoCompleteName, setValueId, isRadio);
    var nodeIdList = jQuery("#treeValue").val();
    var nodeId = [];
    var nodeId = nodeIdList.split(',');
    var nodeLength = (nodeId.length);
    var nodeCount = constants.NODE_COUNT;
    jQuery.getJSON(autoCompleteUrl, function (jsonObject) {
        function nodeLoop() {
            setInterval(function () {
                if (nodeCount < nodeLength) {
                    var nodeDetail = getJsonObjects(jsonObject, 'id', nodeId[nodeCount]);
                    getTreeExpandWithSelectedNode(nodeDetail[0].id, nodeDetail[0].path, treeName, autoCompleteName);
                    nodeCount++;
                }
            }, constants.TREE_INTERVAL);
        }
        nodeLoop();
    });
});
