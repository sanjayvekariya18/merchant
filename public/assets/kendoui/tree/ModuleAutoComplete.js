var jQuery = jQuery.noConflict();

jQuery(document).ready(function () {
    var treeName = "";
    var resultId = "";
    var mappingAutoComplete = false;
    var autoCompleteUrl = "../js/utree/js/RegionAutoComplete.json";
    var autoCompleteName = "select";
    var autoLabel = "Search Region...";
    var setValueId = "setValue";
    sohyperAutoComplete(mappingAutoComplete, autoCompleteUrl, autoCompleteName, autoLabel, treeName, resultId, setValueId);
});