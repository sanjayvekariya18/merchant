var jQuery = jQuery.noConflict();

jQuery(document).ready(function () {
    // For kendo ui tree
    var checkParent = false;
    var treeUrl = "../js/utree/js/RegionTree.json";
    var treeName = "treeview";
    var resultId = "result";
    var isMapping = true;
    var isRadio = false;
    // for AutoComplete
    var mappingAutoComplete = true;
    var autoCompleteUrl = "../js/utree/js/RegionAutoComplete.json";
    var autoCompleteName = "select";
    var autoLabel = "Search Region...";
    var setValueId = "setValue";
    var resultText = "resultText";
    sohyperTree(checkParent, treeUrl, treeName, resultId, resultText, isMapping, autoCompleteName, setValueId, isRadio);
    sohyperAutoComplete(mappingAutoComplete, autoCompleteUrl, autoCompleteName, autoLabel, treeName, resultId, setValueId);
});