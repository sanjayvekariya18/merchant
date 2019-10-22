$(document).ready(function () {

    var token = $('input[name="_token"]').val();

    $(".select21").select2({
        theme: "bootstrap",
        placeholder: "Please Select Option"
    });

    
    //-------------------------------Account List Tagging-------------------------------------

    function initialize(){
        
        $("#panelList").find("select").select2({
            theme: "bootstrap",
            placeholder: "Please Select Option"
        });

        $("#panelList").find("input:checkbox").bootstrapSwitch();
    }

    $("#category_type_id").on("select2:select",function(event){
        
        setRow(event.params.data.id,event.params.data.text)
        initialize();
    });

    $('#category_type_id').on("select2:unselect", function (event) {
        categoryTypeId =event.params.data.id;
        $("#panelList").find("#category_type_id_"+categoryTypeId).remove();
    });

    function setRow(categoryTypeId,categoryTypeName){
        $categoryTypeList = $('#categoryTypeDemo').clone();
        $categoryTypeList.removeAttr('id');
        $categoryTypeList.removeAttr('style');
        $categoryTypeList.css("margin-top", "10px");
        $categoryTypeList.attr('id',"category_type_id_"+categoryTypeId);
        $tabIndex = $('#panelList').children().length - 1;
        
        $categoryTypeList.find('#category_type_name').text(categoryTypeName);
        $categoryTypeList.find('#categoryTypeId').attr('name',"category_type["+$tabIndex+"][category_type_id]").val(categoryTypeId);
        $categoryTypeList.find('#category_list_priority').attr('name',"category_type["+$tabIndex+"][category_list_priority]");
        $categoryTypeList.find('#category_list_status').attr('name',"category_type["+$tabIndex+"][category_list_status]");

        $('#panelList').append($categoryTypeList);
        initialize();
    }

    function setDefaultRow(categoryTypeId,categoryTypeName,category_list_priority,category_list_status){
        $categoryTypeList = $('#categoryTypeDemo').clone();
        $categoryTypeList.removeAttr('id');
        $categoryTypeList.removeAttr('style');
        $categoryTypeList.css("margin-top", "10px");
        $categoryTypeList.attr('id',"category_type_id_"+categoryTypeId);
        $tabIndex = $('#panelList').children().length - 1;
        
        $categoryTypeList.find('#category_type_name').text(categoryTypeName);
        $categoryTypeList.find('#categoryTypeId').attr('name',"category_type["+$tabIndex+"][category_type_id]").val(categoryTypeId);
        $categoryTypeList.find('#category_list_priority').attr('name',"category_type["+$tabIndex+"][category_list_priority]").val(category_list_priority);
        $categoryTypeList.find('#category_list_status').attr('name',"category_type["+$tabIndex+"][category_list_status]");
        if(category_list_status == 1){
            $categoryTypeList.find('#category_list_status').prop('checked', true);
        }else{
            $categoryTypeList.find('#category_list_status').prop('checked', false);
        }

        $('#panelList').append($categoryTypeList);
        initialize();
    }

    $("#asset_id").on("change",function(){

        var assetId = $("#asset_id").val();
        var requestUrl = $("#requestUrl").val();

        $('#panelList').html("<div class='row'>"+
                                "<div class='col-md-12'>"+
                                    "<div class='row'>"+
                                        "<label class='col-md-6'>Category Type Name</label>"+
                                        "<label class='col-md-3'>Priority</label>"+
                                        "<label class='col-md-3'>Status</label>"+
                                    "</div>"+
                                "</div>"+
                            "</div>");
        $.ajax({
            type:'POST',
            data:{_token:token,asset_id:assetId},
            dataType:"json",
            url : requestUrl+"/getCategoryTypes",
            async: false,
            error:function(xhr,status,error){
                console.log(error);
            },
            success:function(categoryTypes,status,xhr){
                var categoryTypeArray = [];
                $.each(categoryTypes, function(i,value) {
                    var categoryTypeId = value['category_type_id'];
                    var categoryTypeName = value['category_type_name'];
                    var categoryTypePriority = value['category_list_priority'];
                    var categoryTypeStatus = value['category_list_status'];

                    categoryTypeArray.push(categoryTypeId);
                    setDefaultRow(categoryTypeId,categoryTypeName,categoryTypePriority,categoryTypeStatus);
                });
                $("#category_type_id").val(categoryTypeArray).change();
            }
        });

    });
    
});

