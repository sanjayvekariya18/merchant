var jQuery = jQuery.noConflict();
var constants = {
    TREE_INTERVAL: 10,
    ADD_MULTISELECT: 50,
    HIDE_FIRST_NODE: 6,
    AUTO_SCROLLING: 113,
    RADIO_TREE_CHECK_ID: 0,
    CHECK_LENGTH: 0,
    HIGHLIGHT_NODE: -1,
    PATH_LENGTH_COUNT: 0,
    DIV_SCROLL_TOP: 0,
    SEARCH_NODE: 2,
    ASSIGN_VALUE: 0,
}
/*Tree Function that will return the tree
 * checkBox : that will infor the parents needs checkbox or not
 * treeUrl :  that gives the path of the json file
 * treeName : the id of the kendo tree
 * resultId : it gives you the list of selected id in tree
 * isMapping : if you are mapping the tree and auto complete
 * autoCompleteName : if you are doing mapping then you need to pass the name here
 * setValueId : if you are using mapping then need this for set values of tree
 * isRadio : if you want the radio button in tree then pass the value true else false
 * @package : base-magent-categories-services
 * @Author : Mitesh
 */

    function sohyperTree(checkParent, treeUrl, treeName, resultId,resultText, isMapping, autoCompleteName, setValueId, isRadio) {
        var checkedId = constants.RADIO_TREE_CHECK_ID;
        if (checkParent == true) {
            treeCheckChildren = false;
            if (isRadio == true) {
                treeTemplate = "# if(!item.hasChildren){# <input type='hidden' id='#=item.id#' parent_id='#=item.parent_id#' d_text='#=item.text#'/> <input type='radio' class='radioJsonTree' name_a='#= item.text #'  id_a='#= item.id #' name='jsontree' value='#=item.text#' data-bind='checked:jsontree' />#}else{# <input type='hidden' id='#=item.id#' parent_id='#=item.parent_id#' d_text='#=item.text#'/> <input type='radio' class='radioJsonTree' name_a='#= item.text #' id_a='#= item.id #' name='jsontree' value='#=item.text#' data-bind='checked:jsontree' /> #}#";
            }
            if (isRadio == false) {
                treeTemplate = "# if(!item.hasChildren){# <input type='hidden' id='#=item.id#' parent_id='#=item.parent_id#' d_text='#=item.text#'/> <input type='checkbox' name_a='#= item.text #'  id_a='#= item.id #' name='c_#= item.id #' value='true' />#}else{# <input type='hidden' id='#=item.id#' parent_id='#=item.parent_id#' d_text='#=item.text#'/> <input type='checkbox' name_a='#= item.text #' id_a='#= item.id #' name='c_#= item.id #' value='true' /> #}#";
            }
        }
        if (checkParent == false) {
            treeCheckChildren = false;
            if (isRadio == true) {
                treeTemplate = "# if(!item.hasChildren){# <input type='hidden' id='#=item.id#' parent_id='#=item.parent_id#' d_text='#=item.text#'/> <input type='radio' class='radioJsonTree' name_a='#= item.text #' id_a='#= item.id #' name='jsontree' value='#=item.text#' data-bind='checked:jsontree' />#}else{# <input type='hidden' id='#=item.id#' parent_id='#=item.parent_id#' d_text='#=item.text#'/> #}#";
            }
            if (isRadio == false) {
                treeTemplate = "# if(!item.hasChildren){# <input type='hidden' id='#=item.id#' parent_id='#=item.parent_id#' d_text='#=item.text#'/> <input type='checkbox' name_a='#= item.text #' id_a='#= item.id #' name='c_#= item.id #' value='true' />#}else{# <input type='hidden' id='#=item.id#' parent_id='#=item.parent_id#' d_text='#=item.text#'/> #}#";
            }
        }
        jQuery.getJSON(treeUrl, function (JsonReturnData) {
            jQuery("#" + treeName).kendoTreeView({
                checkboxes: {
                    checkChildren: treeCheckChildren,
                    template: treeTemplate,
                },
                dataSource: {
                    data: JsonReturnData
                },
                dataBound: function () {
                    if (isRadio === false) {
                        onBindData(treeName, autoCompleteName, setValueId, isMapping);
                    }
                },
                dataTextField: "text",
                dataValueField: "id"
            });
            function checkedNodeOptionName(checkBoxNodes, checkedNodes) {    
                for (var checkBoxCount = constants.ASSIGN_VALUE; checkBoxCount < checkBoxNodes.length; checkBoxCount++) {  
                    if (checkBoxNodes[checkBoxCount].checked) {
                        checkedNodes.push(checkBoxNodes[checkBoxCount].id);
                    }

                    if (checkBoxNodes[checkBoxCount].hasChildren) {
                        checkedNodeIds(checkBoxNodes[checkBoxCount].children.view(), checkedNodes);
                    }
                }
            }
            function checkedNodeIds(checkBoxNodes, checkedNodes) {
                for (var checkBoxCount = constants.ASSIGN_VALUE; checkBoxCount < checkBoxNodes.length; checkBoxCount++) {
                    if (checkBoxNodes[checkBoxCount].checked) {
                        checkedNodes.push(checkBoxNodes[checkBoxCount].id);
                    }

                    if (checkBoxNodes[checkBoxCount].hasChildren) {
                        checkedNodeIds(checkBoxNodes[checkBoxCount].children.view(), checkedNodes);
                    }
                }
            }
            function checkedNodeText(checkBoxNodes, checkedNodesText) {
                for (var checkBoxCount = constants.ASSIGN_VALUE; checkBoxCount < checkBoxNodes.length; checkBoxCount++) {
                    if (checkBoxNodes[checkBoxCount].checked) {
                        checkedNodesText.push(checkBoxNodes[checkBoxCount].text);
                    }

                    if (checkBoxNodes[checkBoxCount].hasChildren) {
                        checkedNodeText(checkBoxNodes[checkBoxCount].children.view(), checkedNodesText);
                    }
                }
            }
            if (isRadio == false) {
                jQuery("#" + treeName).data("kendoTreeView").dataSource.bind("change", function () {
                    var checkedNodes = [],
                    	checkedNodesText = [],
                        treeView = jQuery("#" + treeName).data("kendoTreeView"),
                        displayMessage,
                        displayText;

                    checkedNodeIds(treeView.dataSource.view(), checkedNodes);
                    checkedNodeText(treeView.dataSource.view(), checkedNodesText);
                    if (checkedNodes.length > constants.ASSIGN_VALUE) {
                        displayMessage = checkedNodes.join(",");
                    } else {
                        displayMessage = "";
                    }
                    
                    if (checkedNodes.length > constants.ASSIGN_VALUE) {
                    	displayText = checkedNodesText.join(",");
                    } else {
                    	displayText = "";
                    }
                    jQuery("#" + resultId).html(displayMessage);
                    jQuery("#" + resultText).html(displayText);
                });
            }
        });

        (function () {
            jQuery('#' + treeName + ' > ul > li.k-first >div.k-top span.k-checkbox').hide();
            setTimeout(arguments.callee, constants.HIDE_FIRST_NODE);
        })();

        if (isRadio === true) {
            jQuery("#" + treeName).bind("click.radioJsonTree", function () {
                var clickedId = jQuery("#" + treeName + " input[type='radio']:checked").attr('id_a');
                if (clickedId != checkedId && typeof (clickedId) != "undefined") {
                    jQuery("#" + clickedId).closest("div").find("span:last").addClass("k-state-selected");
                    jQuery("#" + resultId).html(clickedId);
                    jQuery("#" + checkedId).closest("div").find("span:last").removeClass("k-state-selected");
                    checkedId = clickedId;
                }
            });
        }
    }

    /*Auto Complete Function that will return multiselect there is an option to mapping with tree 
     * mappingAutoComplete : pass true or false based on if you want maaping with tree
     * autoCompleteUrl :  that gives the path of the json file
     * autoCompleteName : Id of the select list on which you want to disply auto complete
     * resultId : it gives you the list of selected id in tree
     * autoLabel : it displays Label name in auto complete box
     * treeName : if you are doing mapping then it requires tree ID
     * setValueId : if you are using mapping then need this for set values of tree
     * @package : base-magent-categories-services
     * @Author : Mitesh
     */
    function sohyperAutoComplete(mappingAutoComplete, autoCompleteUrl, autoCompleteName, autoLabel, treeName, resultId, setValueId) {

        jQuery.getJSON(autoCompleteUrl, function (JsonReturnData) {
            var multiSelect = jQuery("#" + autoCompleteName).kendoMultiSelect({
                dataTextField: "text",
                dataValueField: "id",
                itemTemplate: "<option a_value='${text}' entity_id ='${id}' path='${path}' >#:text#</option>",
                tagTemplate: "<span class='multiNodeDetail' entity_id ='${data.id}' path ='${data.path}' >" + '#: data.text #' + "</span>",
                placeholder: autoLabel,
                dataSource: JsonReturnData,
                filter: "startswith",
                minLength: constants.SEARCH_NODE,
                select: onSelectChange,
                dataBound: function () {
                    if (mappingAutoComplete === true) {
                        saveCurrent(this);
                    }
                },
                change: function () {
                    if (mappingAutoComplete === true) {
                        var previousNodes = this._savedOld;
                        var currentNodes = this.value();
                        var differanceOfNodes = jQuery(previousNodes).not(currentNodes).get();
                        saveCurrent(this);
                        if (differanceOfNodes != '') {
                            removeFromTree(differanceOfNodes);
                        }
                        setTimeout(function () {
                            kendoUiHoverAutoScrollingNodes(treeName, autoCompleteName);
                        }, constants.AUTO_SCROLLING);
                    }
                },
            }).data("kendoMultiSelect");

            function saveCurrent(multiSelect) {
                multiSelect._savedOld = multiSelect.value().slice(constants.ASSIGN_VALUE);
            }

            if (mappingAutoComplete === true) {
                setValue = function (eventData) {
                    if (eventData.type != "keypress" || kendo.keys.ENTER == eventData.keyCode) {
                        multiSelect.dataSource.filter({}); //clear applied filter before setting value
                        multiSelect.value(jQuery("#" + resultId).html().split(","));
                    }
                };

                jQuery("#" + setValueId).click(setValue);
                jQuery("#" + resultId).keypress(setValue);
            }
        });

        function onSelectChange(eventData) {
            if (mappingAutoComplete == true) {
                var dataItem = this.dataSource.view()[eventData.item.index()];
                getTreeExpandWithSelectedNode(dataItem.id, dataItem.path, treeName, autoCompleteName);
            }
        }

        function removeFromTree(idElement) {
            if (jQuery("input[type='checkbox'][name='c_" + idElement + "']").is(':checked')) {
                jQuery("input[type='checkbox'][name='c_" + idElement + "']").trigger('click');
            }
            jQuery('#' + idElement).closest('div').find('span:last').removeClass("k-state-selected");
        }

    }

    function onBindData(treeName, autoCompleteName, setValueId, isMapping) {
        jQuery('input[type=checkbox]').click(function (eventData) {
            var liElement = jQuery(eventData.target).closest("li");
            var idElement = jQuery("input:hidden", liElement).attr("id");
            if (jQuery(this).is(':checked')) {
                jQuery("input[type='checkbox'][name='c_" + idElement + "']").closest("div").find("span:last").addClass("k-state-selected");
                if (isMapping === true) {
                    setTimeout(function () {
                        addToMultiSelect(idElement, treeName, autoCompleteName);
                    }, constants.ADD_MULTISELECT);
                }
            } else {
                jQuery("input[type='checkbox'][name='c_" + idElement + "']").closest("div").find("span:last").removeClass("k-state-selected");
                if (isMapping === true) {
                    setTimeout(function () {
                        setToMultiSelect(setValueId, treeName, autoCompleteName);
                    }, constants.ADD_MULTISELECT);
                }
            }
        });
    }

    function setToMultiSelect(setValueId, treeName, autoCompleteName) {
        jQuery('#' + setValueId).trigger('click');
        setTimeout(function () {
            kendoUiHoverAutoScrollingNodes(treeName, autoCompleteName);
        }, constants.ADD_MULTISELECT);
    }

    function addToMultiSelect(idElement, treeName, autoCompleteName) {
        idAdditionList = new Array();
        idAdditionList.length = constants.ASSIGN_VALUE;
        subtractNodes = "" + idElement + "";
        idAdditionList.push(subtractNodes);
        var multiSelect = jQuery("#" + autoCompleteName).data("kendoMultiSelect");
        var nodeValues = multiSelect.value().slice();
        var mergeNodes = jQuery.merge(nodeValues, idAdditionList);
        multiSelect.dataSource.filter({});
        multiSelect.value(jQuery.unique(mergeNodes));
        setTimeout(function () {
            kendoUiHoverAutoScrollingNodes(treeName, autoCompleteName);
        }, constants.ADD_MULTISELECT);
    }

    function kendoUiHoverAutoScrollingNodes(treeName, autoCompleteName) {
        jQuery('div.k-multiselect-wrap ul.k-reset li').hover(function () {
            var hoverText = jQuery(this).find('span:first').text();
            var hoverId = jQuery(this).find('.multiNodeDetail').attr('entity_id');
            kendoUiHoverAutoScrolling(hoverId, treeName, autoCompleteName);
        }, function () {});
    }

    function kendoUiHoverAutoScrolling(idElement, treeName, autoCompleteName) {
        if ((jQuery("#" + idElement).length) > (constants.CHECK_LENGTH)) {
            jQuery("#" + treeName).scrollTop(constants.DIV_SCROLL_TOP);
            treePosition = jQuery("div#" + treeName).offset().top;
            nodePosition = jQuery('#' + idElement).closest('div').offset().top;
            finalPosition = parseInt(nodePosition) - parseInt(treePosition) - (constants.HIGHLIGHT_NODE);
            jQuery("div#" + treeName).scrollTop(finalPosition);
        }
    }

    function getTreeExpandWithSelectedNode(idElement, treePath, treeName, autoCompleteName) {
        if ((jQuery("#" + idElement).length) > (constants.CHECK_LENGTH)) {
            setTimeout(function () {
                getNodeClickWithExpand(idElement, treeName, autoCompleteName);
            }, constants.ADD_MULTISELECT);
        } else {
            selectedNodePath = treePath.split('/');
            pathLength = (selectedNodePath.length);
            var pathLengthCount = constants.PATH_LENGTH_COUNT;

            function loopLi() {
                setInterval(function () {
                    if (pathLengthCount < pathLength) {
                        var treeView = jQuery("#" + treeName).data("kendoTreeView");
                        treeView.expand(document.getElementById(selectedNodePath[pathLengthCount]));
                        if (idElement == selectedNodePath[pathLengthCount]) {
                            getNodeClickWithExpand(idElement, treeName, autoCompleteName);
                        }
                    }
                    pathLengthCount++;
                }, constants.TREE_INTERVAL);
            }
            jQuery(loopLi);
        }
    }

    function getNodeClickWithExpand(idElement, treeName, autoCompleteName) {
        jQuery("input[type='checkbox'][name='c_" + idElement + "']").trigger('click');
        var treeView = jQuery("#" + treeName).data("kendoTreeView");
        treeView.expand(document.getElementById(idElement));
        jQuery('#' + idElement).closest('div').find('span:last').addClass("k-state-selected");
        kendoUiHoverAutoScrolling(idElement, treeName, autoCompleteName);
    }

    function getJsonObjects(jsonObject, objectKey, objectValue) {
        var returnObject = [];
        for (var checkNode in jsonObject) {
            if (!jsonObject.hasOwnProperty(checkNode)) continue;
            if (typeof jsonObject[checkNode] == 'object') {
                returnObject = returnObject.concat(getJsonObjects(jsonObject[checkNode], objectKey, objectValue));
            } else if (checkNode == objectKey && jsonObject[objectKey] == objectValue) {
                returnObject.push(jsonObject);
            }
        }
        return returnObject;
    }