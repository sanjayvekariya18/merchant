var token = $('input[name="_token"]').val();
var requestUrl = $("#requestUrl").val();
$(document).ready(function() {

     /*get user group list */
    var merchantTypeList = new kendo.data.DataSource({
        transport: {
            read: {
                dataType: "json",
                url: requestUrl + "/getMerchantType",
                type: "GET",
            }
        }
    });

    var treeMerchantTypes = $("#treeMerchantType").kendoMultiSelect({
        dataTextField: "merchant_type_name",
        dataValueField: "merchant_type_id",
        filter: "contains",
        height: 400,
        placeholder: "Select Merchant Type",
        dataSource: merchantTypeList
    }).data("kendoMultiSelect");

    $("#selectAllMerchantType").click(function(eventObject) {
        eventObject.preventDefault();
        var selectedTypes = $.map(treeMerchantTypes.dataSource.data(), function(dataItem) {
            return dataItem.merchant_type_id;
        });
        treeMerchantTypes.value(selectedTypes);
    });

    $("#deSelectAllMerchantType").click(function(eventObject) {
        eventObject.preventDefault();
        treeMerchantTypes.value([]);
    });

    $("#category_tree_create").kendoValidator({
        validateOnBlur: false,
        rules: {
            customRule1: function(input) {
                if (input.is("[id=treeMerchantType]")) {
                    var ms = input.data("kendoMultiSelect");
                    if (ms.value().length === 0) {
                        return false;
                    }
                }
                return true;
            }
        },
        messages: {
            customRule1: "Please Select at least one mechant type",
        },
        validate: function(e) {
            console.log("valid" + e.valid);
        },
        validateInput: function(e) {
            console.log("input " + e.input.attr("keyword") + " changed to valid: " + e.valid);
        }
    });

    $("#category_tree_create").submit(function(event) {
        console.log($('#category_tree_create').serialize());
        event.preventDefault(); // avoid to execute the actual submit of the form.
        var validatable = $("#category_tree_create").data("kendoValidator");
        if (validatable.validate()) {
            $('#categoryTreeCreate').modal('hide');
            var merchantTypes = $("#treeMerchantType").data("kendoMultiSelect").value();
            $.ajax({
                type:'POST',
                data: $('#category_tree_create').serialize(),
                data: {
                    _token: token,
                    merchantTypeList: merchantTypes,
                },
                dataType:"json",
                url : requestUrl+"/selectedCategoryTreeCreate",
                error:function(xhr,status,error) {
                    console.log(error);
                },
                success:function(treeCreateResponse,status,xhr) {
                    if (typeof treeCreateResponse.type == 'undefined')
                    {
                        jQuery(".errorMessage").show();
                        setTimeout(function(){
                            jQuery(".errorMessage").hide();
                        },3000);
                    } else {
                        jQuery(".successMessage").show();
                        jQuery('.successMessage span').text(treeCreateResponse.message);
                        setTimeout(function(){
                            jQuery(".successMessage").hide();
                        },3000);
                        var merchantTypeMultiSelect = $('#treeMerchantType').data("kendoMultiSelect");
                        merchantTypeMultiSelect.value([]);
                    }
                }
            });
        }
    });
});