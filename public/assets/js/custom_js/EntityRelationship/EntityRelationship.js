var token = $('input[name="_token"]').val();
var requestUrl = $("#requestUrl").val();
$(document).ready(function() {

     /*get user group list */
    var databaseTableList = new kendo.data.DataSource({
        transport: {
            read: {
                dataType: "json",
                url: requestUrl + "/getAllTableList",
                type: "GET",
            }
        }
    });

    var groupDropDownList = $("#treeTableList").kendoDropDownList({
        dataTextField: "table_name",
        dataValueField: "table_id",
        filter: "contains",
        height: 400,
        placeholder: "Select Table",
        dataSource: databaseTableList,
        dataBound: dropDownListAutoWidth
    });


    $("#table_tree_create").kendoValidator({
        validateOnBlur: false,
        rules: {
            customRule1: function(input) {
                if (input.is("[id=treeTableList]")) {
                    var ms = input.data("kendoDropDownList");
                    console.log(ms.value().length);
                    if (ms.value().length === 0) {
                        return false;
                    }
                }
                return true;
            }
        },
        messages: {
            customRule1: "Please Select table",
        },
        validate: function(elementObject) {
            console.log("valid" + elementObject.valid);
        },
        validateInput: function(elementObject) {
            console.log("input " + elementObject.input.attr("keyword") + " changed to valid: " + elementObject.valid);
        }
    });

    $("#table_tree_create").submit(function(event) {
        event.preventDefault(); // avoid to execute the actual submit of the form.
        var validatable = $("#table_tree_create").data("kendoValidator");
        if (validatable.validate()) {
            var selectedTable = $("#treeTableList").data("kendoDropDownList").text();
            $.ajax({
                type:'POST',
                data: {
                    _token: token,
                    selectedTable: selectedTable,
                },
                dataType:"json",
                url : requestUrl+"/getTableForeignObject",
                error:function(xhr,status,error) {
                    console.log(error);
                },
                success:function(treeForeignObject,status,xhr) {
                    console.log(treeForeignObject);
                }
            });
        }
    });
});
function dropDownListAutoWidth(elementObject) {
    var popupElement = elementObject.sender.popup.element;
    var fontSize = $(elementObject.sender.element).css('font-size');
    var cloneElement = popupElement.clone().css({ visibility: 'hidden', 'font-size': fontSize }).appendTo($('body'));
    var cloneElementWidth = cloneElement.outerWidth();
    cloneElement.remove();
    elementObject.sender.list.closest('.k-animation-container').width(cloneElementWidth);
    $(elementObject.sender.element).closest('.k-widget').width(cloneElementWidth);
}