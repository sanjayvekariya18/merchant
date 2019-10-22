var jQuery = jQuery.noConflict();

jQuery(document).ready(function () {
    // For kendo ui tree
    var checkParent = false;
    var treeUrl = "../js/utree/js/RegionTree.json";
    var treeName = "treeview";
    var resultId = "result";
    var isMapping = false;
    var autoCompleteName = "";
    var setValueId = "";
    var isRadio = false;
    var resultText = "resultText";
    sohyperTree(checkParent, treeUrl, treeName, resultId, resultText, isMapping, autoCompleteName, setValueId, isRadio);
});
