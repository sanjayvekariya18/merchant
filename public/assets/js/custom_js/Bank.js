var bankCreateConstant = {
    TOKEN                       :   $("input[name=_token]").val(),
    ACTION_URL                  :   $('#identityCityListForm').attr('action'),
    COUNTRY_ORIGIN_ID           :   "#country_origin",
    TREE_ID                     :   "#topologyTree",
    TREE_METHOD_NAME            :   "/getLocationTree",
    MULTI_SELECT_DROPDOWN_ID    :   "#region_id",
    MULTI_METHOD_NAME           :   "/getRegions",
    MULTI_DROPDOWN_PRIMARY_ID   :   "region_id",
    MULTI_DROPDOWN_PRIMARY_VAL  :   "region_name",
    COUNTRY_METHOD_NAME         :   "/getCountries",
}
$(document).ready(function() {
    var defaultCountryId = $("#default_country_id").val();
    var validator = $("#identityCityListForm").kendoValidator({
        validateOnBlur: false,
        rules: {
         customRule1: function(input){
              if (input.is("[name=bank_name]")) {
                if(input.val() == ''){
                    return false;    

                }else{
                    return true;
                }
              }
              return true;
          },
          customRule2: function(input){
              if (input.is("[name=clearing_code]")) {
                if(input.val() == '') {
                    return false;    

                } else {
                    return true;
                }
              }
              return true;
          },customRule3: function(input){
              if (input.is("[name=local_name]")) {
                if(input.val() == ''){
                    return false;    

                }else{
                    return true;
                }
              }
              return true;
          },
          customRule4: function(input){
              if (input.is("[name=country_origin]")) {
                if(input.val() == ''){
                    return false;    

                }else{
                    return true;
                }
              }
              return true;
          },
          customRule5: function(input){
              if (input.is("[name=swift_bic]")) {
                if(input.val() == ''){
                    return false;    

                }else{
                    return true;
                }
              }
              return true;
          },
          /*customRule6: function(input){
              if (input.is("[id=region_id]")) {
                if(input.val().length === 0){
                    return false;    

                }else{
                    return true;
                }
              }
              return true;
          },*/
        },
        messages: {
            customRule1: "Please Enter Bank name",
            customRule2: "Please Enter Clearing Code",
            customRule3: "Please Enter Local Name",
            customRule4: "Please Enter Country Origin",
            customRule5: "Please Enter Swift Bic",
            /*customRule6: "Please select at least one City",*/
        },
        validate: function(e) {
            console.log("valid" + e.valid);
        },
        validateInput: function(e) {
            console.log("input " + e.input.attr("customerAccountId") + " changed to valid: " + e.valid);
        }
    }).data("kendoValidator");
    var token = $('input[name="_token"]').val();
    var requestUrl = $("#requestUrl").val();

    /*var kendoTreeUrl = bankCreateConstant.ACTION_URL+bankCreateConstant.TREE_METHOD_NAME;
    jQuery.getJSON(kendoTreeUrl, function (JsonReturnData) {
        treeTemplate = "# if(item.level() > 2){# <input type='hidden' id='#=item.id#' parent_id='#=item.parent_id#' d_text='#=item.text#'/> <input type='checkbox' name_a='#= item.text #' id_a='#= item.id #' name='c_#= item.parent_id #' value='true' />#}else{# <input type='hidden' id='#=item.id#' parent_id='#=item.parent_id#' d_text='#=item.text#'/> #}#";
        $(bankCreateConstant.TREE_ID).kendoTreeView({
            loadOnDemand: false,
            checkboxes: {
                checkChildren: true,
                template: treeTemplate,
            },
            dataSource: { data: JsonReturnData },
            loadOnDemand: false,
            dataTextField: "text",
            dataValueField: "id",
            check: function(e){
                var checkNodes = [];
                $(bankCreateConstant.TREE_ID+" .k-item input[type=checkbox]:checked").each(function(){
                    checkNodes.push($(this).attr('id_a'));
                });
                kendoRegion.value(checkNodes);
            }
        });
        kendoTree           = $(bankCreateConstant.TREE_ID).data("kendoTreeView");
    });
    $(bankCreateConstant.MULTI_SELECT_DROPDOWN_ID).kendoMultiSelect({
        placeholder: "Enter City...",
        dataTextField: bankCreateConstant.MULTI_DROPDOWN_PRIMARY_VAL,
        dataValueField: bankCreateConstant.MULTI_DROPDOWN_PRIMARY_ID,
        filter: "contains",
        height: 400,
        dataSource: {
            transport : {   
                read : {
                    dataType : "json",
                    url : bankCreateConstant.ACTION_URL+bankCreateConstant.MULTI_METHOD_NAME,
                    type : "GET"
                }
            }
        },
        change: function(e) {
            
            kendoTree.dataSource.read();
            var values = this.value();

            if (values.length != 0) {
                $.each(values, function(i, nodeID) {

                    kendoTree.expandTo(nodeID);
                    var getitem = kendoTree.dataSource.get(nodeID);
                    var selectitem = kendoTree.findByUid(getitem.uid);
                    selectitem.find(':checkbox').prop("checked",true);
                });

            }else{

            }
        },
    }).data("kendoMultiSelect");
    var kendoRegion = $(bankCreateConstant.MULTI_SELECT_DROPDOWN_ID).data("kendoMultiSelect");*/


    var countryList = new kendo.data.DataSource({
        transport : {   
            read : {
                dataType : "json",
                url : requestUrl+bankCreateConstant.COUNTRY_METHOD_NAME,
                type : "GET",
            }
        }
    });
    $(bankCreateConstant.COUNTRY_ORIGIN_ID).kendoComboBox({
        placeholder: "Enter country...",
        dataTextField: "country_name",
        dataValueField: "country_id",
        filter: "contains",
        height: 400,
        value: defaultCountryId,
        dataSource: countryList,
    }).data("kendoComboBox");

    var bankGrid = jQuery("#bankGrid").kendoGrid({
        dataSource: {
            serverPaging: true,
            pageSize: 20,
            transport : {   
                read : {
                    url: requestUrl+"/getBankList",
                    dataType: "json",
                    type: "GET"
                },
                update: {
                    data:{_token:token},
                    url: requestUrl + "/updateBankLists",
                    dataType: "json",
                    type: "POST",
                    complete:function(data){
                        bankGrid.dataSource.read();
                    }
                },
                destroy: {
                    data:{_token:token},
                    url: requestUrl + "/deleteBankLists",
                    dataType: "json",
                    type: "POST",
                    complete:function(data){
                        bankGrid.dataSource.read();
                    }
                },
            },
            schema: {
                total:'total',
                data:'bank_list',  
                model: {
                    id:'bank_id',
                    fields: {
                        bank_name: { validation: { required: true }},
                        branch_name: { editable:false},
                        city_name: { validation: { required: true }},
                        clearing_code: { validation: { required: true }},
                        local_name: { validation: { required: true }},
                        swift_bic: { validation: { required: true }},
                    }
                },
            },
            serverFiltering: true,
        },
        noRecords: true,
        /*messages: {
            noRecords: "There is no data on current page"
        },*/
        pageable: {
            refresh: true,
            pageSizes: true
        },
        scrollable: true,
        autoSync: true,
        sortable: true,
        reorderable: true,
        serverFiltering: true,
        groupable: true,
        resizable: true,
        editable: 'inline',
        edit: function(e){
            var columnNotEditableIndex = [3];
            if($.inArray(parseInt(e.container.index()),columnNotEditableIndex) != -1){
                this.closeCell(); 
            }
        },
        columns: [
        
        {
            command: [
                { name: "edit", text: { edit: " ", update: " ", cancel: " " } },
                { name: "destroy", text: " " }
                ],
            title: "&nbsp;",
            width: "90px"
        },{
            field: "bank_id",
            title: "Bank Id",
            hidden:true,
        },{
            field: "bank_name",
            title: "Bank",
        }, {
            field: "swift_bic",
            title: "Swift Code",
        }, {
            field: "branch_name",
            title: "Branch",
            template: "#=(data.branch_name != null)?data.branch_name:'None'#",
        }, {
            field: "country_origin",
            title: "Origin City",
            editor: originCountryId,
            template: "#=data.country_name#"
        }, {
            field: "clearing_code",
            title: "Clearing Code",
        }, {
            field: "local_name",
            title: "Local",
        }],
    });
    var bankGrid = $("#bankGrid").data("kendoGrid");
    function originCountryId(container, options) {

        $('<input data-text-field="country_name" data-value-field="country_id" data-bind="value:' + options.field + '"/>')
            .appendTo(container)
            .kendoDropDownList({
                dataSource: {
                    transport : {   
                        read : {
                            dataType : "json",
                            url : requestUrl+"/getCountries",
                            type : "GET"
                        }
                    }
                },
                dataBound: function() {
                    if (this.select() === -1) { //check whether any item is selected
                        this.select(0);
                        this.trigger("change");
                    }
                },
                filter: "contains",
                dataTextField: "country_name",
                dataValueField: "country_id"
            });
    }
    // Submit Form
    
    $("#submitBtn").click(function(){
        if (validator.validate()) {
            $('.content .preloader').show();
            $('.content .preloader img').show();

            /*var checkNodes = [];
            var identity_id = kendoIdentity.value();
            
            $(constant.TREE_ID+" .k-item input[type=checkbox]:checked").each(function(){
                checkNodes.push($(this).attr('id_a'));
            });*/

            $.ajax({
                type: 'POST',
                data:$("#identityCityListForm").serialize(),
                url: bankCreateConstant.ACTION_URL,
                success: function (bankAddResponse) {
                    $("#bank_name").val("");
                    $("#clearing_code").val("");
                    $("#local_name").val("");
                    $("#swift_bic").val("");
                    $("#country_origin").data("kendoComboBox").value(defaultCountryId);
                    bankGrid.dataSource.read();
                    $('.content .preloader').hide();
                    $('.content .preloader img').hide();
                }
            });
        }
    });
});