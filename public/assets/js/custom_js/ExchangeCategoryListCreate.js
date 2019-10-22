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
        $categoryTypeList.find('#priority').attr('name',"category_type["+$tabIndex+"][priority]");
        $categoryTypeList.find('#status').attr('name',"category_type["+$tabIndex+"][status]");

        $('#panelList').append($categoryTypeList);
        initialize();
    }

    function setDefaultRow(categoryTypeId,categoryTypeName,priority,status){
        $categoryTypeList = $('#categoryTypeDemo').clone();
        $categoryTypeList.removeAttr('id');
        $categoryTypeList.removeAttr('style');
        $categoryTypeList.css("margin-top", "10px");
        $categoryTypeList.attr('id',"category_type_id_"+categoryTypeId);
        $tabIndex = $('#panelList').children().length - 1;
        
        $categoryTypeList.find('#category_type_name').text(categoryTypeName);
        $categoryTypeList.find('#categoryTypeId').attr('name',"category_type["+$tabIndex+"][category_type_id]").val(categoryTypeId);
        $categoryTypeList.find('#priority').attr('name',"category_type["+$tabIndex+"][priority]").val(priority);
        $categoryTypeList.find('#status').attr('name',"category_type["+$tabIndex+"][status]");
        if(status == 1){
            $categoryTypeList.find('#status').prop('checked', true);
        }else{
            $categoryTypeList.find('#status').prop('checked', false);
        }

        $('#panelList').append($categoryTypeList);
        initialize();
    }

    $("#exchange_id").on("change",function(){

        var exchangeId = $("#exchange_id").val();
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
            data:{_token:token,exchange_id:exchangeId},
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
                    var categoryTypePriority = value['priority'];
                    var categoryTypeStatus = value['status'];

                    categoryTypeArray.push(categoryTypeId);
                    setDefaultRow(categoryTypeId,categoryTypeName,categoryTypePriority,categoryTypeStatus);
                });
                $("#category_type_id").val(categoryTypeArray).change();
            }
        });

    });
    
});

