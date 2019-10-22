$(document).ready(function () {
    
    var requestUrl = $("#CategoriesForm").attr('action');
    
    function initialize(){
        
        $("#panelList").find('select').select2({
            theme: "bootstrap"
        });
        $("#panelList").find('input:checkbox').bootstrapSwitch();
    }

    $("#category_type_id").on("select2:select",function(event){
        
        setRow(event.params.data.id,event.params.data.text)
        initialize();
    });

    $('#category_type_id').on("select2:unselect", function (event) { 
        categoryId =event.params.data.id;
        $("#panelList").find("#category_id_"+categoryId).remove();
    });

    function setRow(categoryId,categoryName){
        $categoryList = $('#demo').clone();
        $categoryList.removeAttr('id');
        $categoryList.removeAttr('style');
        $categoryList.attr('id',"category_id_"+categoryId);
        $tabIndex = $('#panelList').children().length - 1;

        $categoryList.find('#category_type_name').text(categoryName);
        $categoryList.find('#categoryTypeId').attr('name',"options["+$tabIndex+"][category_type_id]").val(categoryId);
        $categoryList.find('#category_option_type_id').attr('name',"options["+$tabIndex+"][category_option_type_id][]");
        $categoryList.find('#priority').attr('name',"options["+$tabIndex+"][priority]");
        $categoryList.find('#enable').attr('name',"options["+$tabIndex+"][enable]");

        setOptionList($categoryList,categoryId);
        $('#panelList').append($categoryList);
        initialize();
    }

    function setOptionList($categoryList,categoryTypeId){

        $optionList = $categoryList.find('#category_option_type_id');
        $optionPriority = $categoryList.find('#priority');
        $optionEnable = $categoryList.find('#enable');

        var locationID = $('#location_id').val();
        var token = $('input[name="_token"]').val();

        $('.content .preloader').show();
        $('.content .preloader img').show();

        $.ajax({
            type:'POST',
            data:{_token:token,category_type_id:categoryTypeId,location_id:locationID},
            dataType:"json",
            url : requestUrl+"/getCategoryOptions",
            async: false,
            error:function(xhr,status,error){
                console.log(error);
            },
            success:function(optionLists,status,xhr){
                
                var optionArray = [];
                $.each(optionLists, function(i,value) {
                    var optionId=value['category_option_type_id'];
                    optionArray.push(optionId);
                });

                if(optionArray.length){
                    $optionPriority.val(optionLists[0]['priority']);
                    var checked = (optionLists[0]['enable'])?true:false;
                    $optionEnable.prop("checked",checked);
                    $optionEnable.bootstrapSwitch('state', checked);
                    $optionList.val(optionArray).trigger('change');
                }

                $('.content .preloader').hide();
                $('.content .preloader img').hide();
            }
        });

    }

    $("#merchant_id").select2({
        theme: "bootstrap",
        placeholder: "Merchant Name"
    });

    $("#location_id").select2({
        theme: "bootstrap",
        placeholder: "Select Location"
    });

    $("#category_type_id").select2({
        theme: "bootstrap",
        placeholder: "Retails type List"
    });

    $("#category_status").bootstrapSwitch('state', true);

    
    $('#location_id').change(function(){
        var locationID = $(this).val();
        var token = $('input[name="_token"]').val();

        $('.content .preloader').show();
        $('.content .preloader img').show();
        
        $("#panelList").children().not(':first').remove();
        $.ajax({
            type:'POST',
            data:{_token:token,location_id:locationID},
            dataType:"json",
            url : requestUrl+"/getCategories",
            async: true,
            error:function(xhr,status,error){
                console.log(error);
            },
            success:function(categories,status,xhr){
                var categoryArray = [];
                $.each(categories, function(i,value) {

                    var categoryId = value['category_type_id'];
                    var categoryName = value['category_name'];
                    categoryArray.push(categoryId);
                    setRow(categoryId,categoryName);
                });
                $("#category_type_id").val(categoryArray).change();
                
                $('.content .preloader').hide();
                $('.content .preloader img').hide();
            }
        });
    });

    $('#merchant_id').change(function(){
        
        var merchantID = $(this).val();
        var token = $('input[name="_token"]').val();

        $('.content .preloader').show();
        $('.content .preloader img').show();
        
        $('#location_id').html("<option></option>");
        $('#category_type_id').html("<option></option>");

        $.ajax({
            type:'POST',
            data:{_token:token,merchant_id:merchantID},
            dataType:"json",
            url : requestUrl+"/getAllCategories",
            async: true,
            error:function(xhr,status,error){
                console.log(error);
            },
            success:function(styles,status,xhr){
                $.each(styles, function(i,value) {
                    $('#category_type_id').append("<option value='"+value['category_type_id']+"'>"+value['category_name']+"</option>");
                });                
            }
        });

        $.ajax({
            type:'POST',
            data:{_token:token,merchant_id:merchantID},
            dataType:"json",
            url : requestUrl+"/getLocations",
            async: true,
            error:function(xhr,status,error){
                console.log(error);
            },
            success:function(locations,status,xhr){
                $.each(locations, function(i,value) {
                    $('#location_id').append("<option value='"+value['location_id']+"'>"+value['location_name']+"</option>");
                });
                $('#location_id').trigger('change');

                $('.content .preloader').hide();
                $('.content .preloader img').hide();
            }

        });
        $("#location_id").select2({
            theme: "bootstrap",
            placeholder: "Select Location"
        });
    });

    $('#merchant_id').trigger('change');

    $('#location_id').trigger('change');

});