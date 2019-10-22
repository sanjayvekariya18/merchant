$(document).ready(function () {

    $("#category_type_id").select2({
        theme: "bootstrap",
        placeholder: "Retails type List"
    });

    var requestUrl = $("#actionUrl").attr('href');
    var merchantID = $("#merchant_id").val();
    var locationID = $("#location_id").val();
    var token = $('input[name="_token"]').val();

    $("#category_status").bootstrapSwitch();

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
            async: true,
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

    $('.content .preloader').show();
    $('.content .preloader img').show();

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