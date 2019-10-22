var expectData = chai.expect;
var assertData = chai.assert;

var assertionConstant = {
    "TIMEOUT_FIFTY": 50,
    "CONSTANT_ONE": 1,
    "NOT_EQUALS_STRING": "not Equals",
    "OBJECT_KEY": "id",
    "WRONG_OBJECT_KEY": "idd",
    "OBJECT_VALUE": 581,
    "WRONG_OBJECT_VALUE": 501,
    "KENDOUI_TREEVIEW": "Treeview",
    "SOHYPER_TREE": "Sohyper Tree",
    "GET_JSON_OBJECTS": "getJsonObjects",
    "PASS_WITH_CORRECT_INPUTS": "should pass with correct input",
    "GET_NODE_CLICK_WITH_EXPAND": "getNodeClickWithExpand",
    "AUTO_EXPAND_TREE": "AutoExpandTree",
    "TREE_EXPAND_NODE": "getTreeExpandWithSelectedNode",
    "KENDOUI_HOVER_AUTO_SCROLLING": "kendoUiHoverAutoScrolling",
    "TREE_URL": "../js/utree/js/RegionTree.json",
    "AUTO_COMPLETE_URL": "../js/utree/js/RegionAutoComplete.json",
    "EMPTY_STRING": "",
    "INDEX_ZERO": 0,
    "TREE_NAME": "treeview",
    "AUTO_COMPLETE_NAME": "select",
    "KENDOUI_HOVER_AUTO_SCROLLING": "kendoUiHoverAutoScrolling",
    "TIMEOUT_EIGHTY": 80,
    "ELEMENT_INPUT": "input",
    "ELEMENT_DIV": "div",
    "INPUT_TYPE_CHECK": 'checkbox',
    "CHECK_NAME": 'c_treeview',
    "AUTO_EXPAND_TREE": "autoExpandTree",
    "NODE_LOOP": "nodeLoop",
    "NODE_COUNT": 0,
    "SOHYPER_TREE_METHOD": "sohyperTree",
    "RESULT_ID": "result",
    "RESULT_TEXT": "resultText",
    "SET_VALUE_ID": "",
    "DOM_TESTS": "DOM Tests",
    "HAS_RIGHT_TITLE": "has the right title",
    "PAGE_TITLE": "Treeview Tests",
    "SINON_TEST": "Sinon Test",
    "MOCK_TEST": "Mock Test",
    "CALLING_METHOD_AJAX": "ajax",
    "DATA_TYPE_JSON": "json",
    "ELEMENT_EXIST": "Element exists in the DOM",
    "RIGHT_TEXT": "element has the right text",
    "HELLO_WORLD": "Hello World"
};
var treeUrl = assertionConstant.TREE_URL;
var autoCompleteUrl = assertionConstant.AUTO_COMPLETE_URL;
var jsonObject = assertionConstant.EMPTY_STRING;
var idElement = assertionConstant.EMPTY_STRING;
var treeName = assertionConstant.TREE_NAME;
var autoCompleteName = assertionConstant.AUTO_COMPLETE_NAME;

jQuery('input[type=checkbox]').click(function (eventData) {
    var liElement = jQuery(eventData.target).closest("li");
    idElement = jQuery("input:hidden", liElement).attr("id");
});

jQuery.getJSON(autoCompleteUrl, function (jsonData) {
    jsonObject = jsonData;
});
describe(assertionConstant.KENDOUI_TREEVIEW, function () {

    /*
     * DOM tests
     */
	describe(assertionConstant.DOM_TESTS, function () {
		it(assertionConstant.HAS_RIGHT_TITLE, function () {
			expect(document.title).to.equal(assertionConstant.PAGE_TITLE);
		});
		
	});
	
	/*
     * test cases for SohyperTree.js
     */
    describe(assertionConstant.SOHYPER_TREE, function () {
        before(function (testTimeout) {
            this.timeout(assertionConstant.TIMEOUT_EIGHTY);
            setTimeout(function () {
                testTimeout();
            }, assertionConstant.TIMEOUT_FIFTY);
        });
        /*
         * test case for getJsonObjects()
         */
        describe(assertionConstant.GET_JSON_OBJECTS, function () {
            it(assertionConstant.PASS_WITH_CORRECT_INPUTS, function () {
                var processJsonObject = getJsonObjects(jsonObject, assertionConstant.OBJECT_KEY, assertionConstant.OBJECT_VALUE);
                var objectId = processJsonObject[assertionConstant.INDEX_ZERO].id;
                assertData.equal(objectId, assertionConstant.OBJECT_VALUE, assertionConstant.NOT_EQUALS_STRING);
            });
        });

        before(function (testTimeout) {
            this.timeout(assertionConstant.TIMEOUT_EIGHTY);
            setTimeout(function () {
                var mockUpCheck = document.createElement(assertionConstant.ELEMENT_INPUT);
                mockUpCheck.type = assertionConstant.INPUT_TYPE_CHECK;
                mockUpCheck.id = assertionConstant.CHECK_NAME;
                var mockUpElement = document.createElement(assertionConstant.ELEMENT_DIV);
                mockUpElement.id = treeName;
                mockUpElement.value = jsonObject;
                testTimeout();
            }, assertionConstant.TIMEOUT_FIFTY);
        });
        /*
         * test cases for getNodeClickWithExpand()
         */
        describe(assertionConstant.GET_NODE_CLICK_WITH_EXPAND, function () {
        	var mockUpIdElement = document.createElement(assertionConstant.ELEMENT_DIV);
            mockUpIdElement.id = idElement;
            mockUpIdElement.innerHTML = assertionConstant.HELLO_WORLD;
            document.body.appendChild(mockUpIdElement);
        	var treeNameElement = document.getElementById(treeName);
        	var idElementDom = document.getElementById(idElement);
    		it(assertionConstant.RIGHT_TEXT, function () {
    			assertData.equal(idElementDom.innerHTML, assertionConstant.HELLO_WORLD, assertionConstant.NOT_EQUALS_STRING);
    		});
        	it(assertionConstant.ELEMENT_EXIST, function () {
    			expect(treeNameElement).to.not.equal(null);
    		});
    		it(assertionConstant.ELEMENT_EXIST, function () {
    			expect(idElementDom).to.not.equal(null);
    		});
        	it(assertionConstant.PASS_WITH_CORRECT_INPUTS, function () {
                var processJsonObject = getNodeClickWithExpand(idElement, treeName, autoCompleteName);
                console.log(processJsonObject);
            });
        });

        /*
         * test cases for getTreeExpandWithSelectedNode()
         */
        describe(assertionConstant.TREE_EXPAND_NODE, function () {
            xit(assertionConstant.PASS_WITH_CORRECT_INPUTS, function () {
                var processJsonObject = getTreeExpandWithSelectedNode(idElement, treePath, treeName, autoCompleteName);
            });
        });

        /*
         * test cases for kendoUiHoverAutoScrolling()
         */
        describe(assertionConstant.KENDOUI_HOVER_AUTO_SCROLLING, function () {
            xit(assertionConstant.PASS_WITH_CORRECT_INPUTS, function () {
                var processJsonObject = kendoUiHoverAutoScrolling(idElement, treeName, autoCompleteName);
            });
        });

        /*
         * Test case for sohyperTree method
         */
        before(function (testTimeout) {
            var checkParent = false;
            var isMapping = false;
            var isRadio = false;
            testTimeout();
        });

        describe(assertionConstant.SOHYPER_TREE_METHOD, function () {
            xit(assertionConstant.PASS_WITH_CORRECT_INPUTS, function () {
                treeResult = sohyperTree(checkParent, assertionConstant.TREE_URL, assertionConstant.TREE_NAME, assertionConstant.RESULT_ID, assertionConstant.RESULT_TEXT, isMapping, assertionConstant.AUTO_COMPLETE_NAME, assertionConstant.SET_VALUE_ID, isRadio);
            });
        });
    });

    /*
     * test cases for AutoExpandTree.js
     */
    describe(assertionConstant.AUTO_EXPAND_TREE, function () {

        before(function (testTimeout) {
            var nodeId = [];
            var nodeCount = assertionConstant.NODE_COUNT;
            var nodeLength = nodeId.length;
            testTimeout();
        });
        /*
         * test case for nodeLoop function
         */
        describe(assertionConstant.NODE_LOOP, function () {
            xit(assertionConstant.PASS_WITH_CORRECT_INPUTS, function () {
                var fetchNodeLoop = nodeLoop();
                console.log(fetchNodeLoop);
            });
        });
    });
    describe(assertionConstant.SINON_TEST, function () {
        before(function(){
             sinon.spy(jQuery, assertionConstant.CALLING_METHOD_AJAX);
        });
        after(function(){
            jQuery.ajax.restore();
        });
        describe(assertionConstant.MOCK_TEST, function () {
            it(assertionConstant.PASS_WITH_CORRECT_INPUTS, function() {
                jQuery.getJSON(assertionConstant.AUTO_COMPLETE_URL);
                assert(jQuery.ajax.calledOnce);
                assertEquals(assertionConstant.AUTO_COMPLETE_URL, jQuery.ajax.getCall(assertionConstant.INDEX_ZERO).args[assertionConstant.INDEX_ZERO].url);
                assertEquals(assertionConstant.DATA_TYPE_JSON, jQuery.ajax.getCall(assertionConstant.INDEX_ZERO).args[assertionConstant.INDEX_ZERO].dataType);
            });
        });
    });
});
