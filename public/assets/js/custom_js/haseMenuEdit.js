$(document).ready(function () {

    var requestUrl = $("#requestUrl").val();
    var styleLimit = parseInt($('#style_type_id').attr('data-limit'));
    var specialStatusValue = $('#menuSpecialStatus').val();
    var token = $('input[name="_token"]').val();
    var locationID = $('#location_id').val();
    var menuID = $('#menu_id').val();

    
    if (specialStatusValue == 1) {
        $('#special_status').bootstrapSwitch('state', true);
    } else {
        $('.specialMenuEnable').hide();
        $('#special_status').bootstrapSwitch('state', false);
    }

    $("#category_type_id").select2({
        theme: "bootstrap",
        placeholder: "Retails type List"
    });

    $("#style_type_id").select2({
        theme: "bootstrap",
        placeholder: "Retails type List",
        maximumSelectionLength: styleLimit
    });

    $(".select21").select2({
        theme: "bootstrap",
        width: '100%'
    });

    $('#start_date').datepicker({
        autoClose : true,
        minDate : new Date
     });
    
    $('#end_date').datepicker({
        autoClose : true,
        minDate : new Date
     });

    $("#location_id").select2({
        theme: "bootstrap",
        placeholder: "Select Location"
    });

    $("#city_id").select2({
        theme: "bootstrap",
        placeholder: "Select City"
    });

    $("#edit_menu_form").bootstrapValidator({
        excluded: [':disabled'],
        fields: {
            location_id: {
                validators: {
                    notEmpty: {
                        message: 'The field is required and cannot be empty'
                    }
                }
            },
            menu_name: {
                validators: {
                    notEmpty: {
                        message: 'The menu name is required'
                    }
                },
                required: true,
            },
            menu_description: {
                validators: {
                    notEmpty: {
                        message: 'The menu description is required'
                    }
                },
                required: true,
                minlength: 3
            },
            menu_price: {
                validators: {
                    notEmpty: {
                        message: 'The menu Price is required'
                    },
                    regexp : {
                        regexp : /^\d+(\.\d{0,2})?$/,
                        message: 'The value is not a number',
                    },
                }
            },
            stock_qty: {
                validators: {
                    regexp: {
                        regexp: /^[0-9]+$/,
                        message: 'The Stock Quantity field must contain an integer.'
                    }
                }
            },
            minimum_qty: {
                validators: {
                    notEmpty: {
                        message: 'The Minimum Quantity field is required.'
                    },
                    regexp: {
                        regexp: /^[1-9]+$/,
                        message: 'The Minimum Quantity field must contain an integer.'
                    }
                }
            },
            special_price : {
                validators: {
                    notEmpty: {
                        message: 'The special price field is required.'
                    },
                    regexp : {
                        regexp : /^\d+(\.\d{0,2})?$/,
                        message: 'The value is not a number',
                    }
                }
            },
            live_image_url: {
                validators: {
                    regexp: {
                        regexp: /\.(gif|jpg|jpeg|tiff|png)$/i,
                        message: 'The live image URL must end with .jpg, .jpeg or .png'
                    },
                    uri: {
                        message: 'The live image URL is not valid'
                    }
                }
            },
            live_image_compact_url: {
                validators: {
                    regexp: {
                        regexp: /\.(gif|jpg|jpeg|tiff|png)$/i,
                        message: 'The live image URL must end with .jpg, .jpeg or .png'
                    },
                    uri: {
                        message: 'The live image Compact URL is not valid'
                    }
                }
            },
            live_special_image_url: {
                validators: {
                    regexp: {
                        regexp: /\.(gif|jpg|jpeg|tiff|png)$/i,
                        message: 'The live image URL must end with .jpg, .jpeg or .png'
                    },
                    uri: {
                        message: 'The special image URL is not valid'
                    }
                }
            },
            live_special_image_compact_url: {
                validators: {
                    regexp: {
                        regexp: /\.(gif|jpg|jpeg|tiff|png)$/i,
                        message: 'The live image URL must end with .jpg, .jpeg or .png'
                    },
                    uri: {
                        message: 'The special image compact URL is not valid'
                    }
                }
            }
        }
    });

    $('#style_type_id').on("select2:select", function (e) { 
        var table_row = e.params.data.id;
        $styleList = $('#demo').find('tbody').clone();
        $styleList.removeAttr('id');
        $styleList.removeAttr('style');
        $styleList.find('tr:last').attr("id","table-row"+table_row+"");
        var trObjectFound = $styleList.find("#table-row"+table_row+"");
        trObjectFound.find("#style_id").attr("name","styles["+table_row+"][style_id]").val(e.params.data.id);
        trObjectFound.find("#style_name").attr("name","styles["+table_row+"][style_name]").text(e.params.data.text);
        trObjectFound.find("#priority").attr("name","styles["+table_row+"][priority]");

        var stylePriorityData = trObjectFound.find("[name='styles["+table_row+"][priority]']");
        $('#editAssignStylesForm').bootstrapValidator('addField', stylePriorityData);

        $("#table").find('tbody').append(trObjectFound);
    });

    $('#style_type_id').on("select2:unselect", function (e) { 
        $("#table").find('tbody').find("#table-row"+e.params.data.id).remove();
    });
    
    $("input[name='special_status']").on('switchChange.bootstrapSwitch', function (event, state) {
        if (state) {
            $( ".specialMenuEnable" ).fadeIn( "slow" );
            jQuery("#special_status").val(1);
        } else {
            $( ".specialMenuEnable" ).fadeOut( "slow" );
            jQuery("#special_status").val(0);
            $('.specialMenuEnable #start_date').val("");
            $('.specialMenuEnable #end_date').val("");
            $('.specialMenuEnable #special_price').val(0);
        }
       event.preventDefault();
    });

    //-----------------------------------------------------------------//
    function initialize(){
        $("#panelList").find('select').select2({
            theme: "bootstrap"
        });
        $("#panelList").find('input:checkbox').bootstrapSwitch();
    }

    function setRow(categoryId,categoryName,locationID=null){
        $categoryList = $('#categoryDemo').clone();
        $categoryList.removeAttr('id');
        $categoryList.removeAttr('style');
        $categoryList.attr('id',"category_id_"+categoryId);
        $tabIndex = $('#panelList').children().length - 1;
        if(!locationID)
        {
            var locationID = $('#location_id').val();
        }
        
        $categoryList.find('#category_type_name').text(categoryName);
        $categoryList.find('#categoryTypeId').attr('name',"options["+$tabIndex+"][category_type_id]").val(categoryId);
        $categoryList.find('#category_option_type_id').attr('name',"options["+$tabIndex+"][category_option_type_id][]");
        $categoryList.find('#priority').attr('name',"options["+$tabIndex+"][priority]");
        $categoryList.find('#enable').attr('name',"options["+$tabIndex+"][enable]");

        setOptionList($categoryList,categoryId,locationID);
        $('#panelList').append($categoryList);
        initialize();
    }

    function setOptionList($categoryList,categoryTypeId,locationID=null){

        $optionList = $categoryList.find('#category_option_type_id');
        $optionPriority = $categoryList.find('#priority');
        $optionEnable = $categoryList.find('#enable');
        var menuID = $('#menu_id').val();

        $('.content .preloader').show();
        $('.content .preloader img').show();

        $.ajax({
            type:'POST',
            data:{_token:token,category_type_id:categoryTypeId,location_id:locationID,product_id:menuID},
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

    $("#category_type_id").on("select2:select",function(event){
        setRow(event.params.data.id,event.params.data.text)
        initialize();
    });

    $('#category_type_id').on("select2:unselect", function (event) { 
        categoryId =event.params.data.id;
        $("#panelList").find("#category_id_"+categoryId).remove();
    });

    $('.content .preloader').show();
    $('.content .preloader img').show();

    $.ajax({
        type:'POST',
        data:{_token:token,location_id:locationID,product_id:menuID},
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
        }
    });

    $('#city_id').change(function(){
        var merchantCityID = $(this).val();
        var merchantID = $('#merchant_id').val();
        var token = $('input[name="_token"]').val();
        $('#location_id').html("<option></option>");
        $.ajax({
            type:'POST',
            data:{_token:token,merchant_id:merchantID,merchant_city_id:merchantCityID},
            dataType:"json",
            url : requestUrl+"/getMerchantCityPostals",
            async: true,
            beforeSend:function(){
                if(merchantID == 0){
                    $('#location_id').html("<option></option>");
                }
            },            
            success:function(merchantCityLocations,status,xhr){
                if(merchantCityLocations.length)
                {
                    $.each(merchantCityLocations, function(i,value) {
                        $('#location_id').append("<option value='"+value['location_id']+"'>"+value['location_name']+"</option>");
                    });
                }

                $('.content .preloader').hide();
                $('.content .preloader img').hide();
            }

        });
    });

    $('#location_id').change(function(){

        $("#table").find('tbody').empty();
        $("#panelList").children().not(':first').remove();
        var menuID = $('#menu_id').val();
        var locationID = $(this).val();
        var token = $('input[name="_token"]').val();

        $('.content .preloader').show();
        $('.content .preloader img').show();
        
        $.ajax({
            type:'POST',
            data:{_token:token,location_id:locationID,product_id:menuID},
            dataType:"json",
            url : requestUrl+"/getStyles",
            async:true,
            error:function(xhr,status,error){
                console.log(error);
            },
            success:function(styles,status,xhr){
                var styleArray = [];
                $.each(styles, function(i,value) {
                    var styleId=value['style_type_id'];
                    var styleName=value['style_name'];
                    var stylePriority=value['priority'];

                    var table_row = styleId;
                    $styleList = $('#demo').find('tbody').clone();
                    $styleList.removeAttr('id');
                    $styleList.removeAttr('style');
                    $styleList.find('tr:last').attr("id","table-row"+table_row+"");
                    var trObjectFound = $styleList.find("#table-row"+table_row+"");
                    trObjectFound.find("#style_id").attr("name","styles["+table_row+"][style_id]").val(styleId);
                    trObjectFound.find("#style_name").attr("name","styles["+table_row+"][style_name]").text(styleName);
                    trObjectFound.find("#priority").attr("name","styles["+table_row+"][priority]").val(stylePriority);

                    var stylePriorityData = trObjectFound.find("[name='styles["+table_row+"][priority]']");
                    //$('#assignStylesForm').bootstrapValidator('addField', stylePriorityData);
                    /*var validatorObject = $('#assignStylesForm').data('bootstrapValidator');
                    validatorObject.addField(stylePriorityData);*/

                    $("#table").find('tbody').append(trObjectFound);

                    styleArray.push(styleId);
                });

                $("#style_type_id").val(styleArray).trigger("change");
            }
        });

        $.ajax({
            type:'POST',
            data:{_token:token,location_id:locationID,product_id:menuID},
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
                    setRow(categoryId,categoryName,locationID);
                });
                $("#category_type_id").val(categoryArray).change();

                $('.content .preloader').hide();
                $('.content .preloader img').hide();
            }
        });
    });
});
