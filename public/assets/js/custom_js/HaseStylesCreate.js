$(document).ready(function () {
    
    var requestUrl = $("#requestUrl").val();
    var styleLimit = parseInt($('#style_type_id').attr('data-limit'));
    var titleValidators = {  // The title is placed inside a <div class="col-xs-4"> element
        validators: {
            notEmpty: {
                message: 'The priority is required'
            }
        }
    };  
    $('#assignStylesForm').bootstrapValidator({
        fields: {
            location_id: {
                validators: {
                    notEmpty: {
                        message: 'The field is required and cannot be empty'
                    }
                }
            },
            style_type_id: {
                selector:"#style_type_id",
                validators: {
                    notEmpty: {
                        message: 'The field is required and cannot be empty'
                    }
                }
            },
            stylePriorityRequired: {
                selector : '.stylePriorityRequired',
                validators: {
                    notEmpty: {
                        message: 'The priority is required and cannot be empty'
                    },
                    regexp: {
                        regexp: /^[0-9]+$/,
                        message: 'The priority must contain an integer.'
                    }
                }
            },
        }
    });
    $("#style_type_id").select2({
        theme: "bootstrap",
        placeholder: "Retails type List",
        maximumSelectionLength: styleLimit
    });
    $("#merchant_id").select2({
        theme: "bootstrap",
        placeholder: "Merchant Name"
    });
    $("#location_id").select2({
        theme: "bootstrap",
        placeholder: "Select Location"
    });

    $("#enable").bootstrapSwitch('state', true);
    $('#location_id').change(function(){
        $("#table").find('tbody').empty();
        var locationID = $(this).val();
        var token = $('input[name="_token"]').val();

        $('.content .preloader').show();
        $('.content .preloader img').show();

        $.ajax({
            type:'POST',
            data:{_token:token,location_id:locationID},
            dataType:"json",
            async:false,
            url : requestUrl+"/getStyles",
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
                    trObjectFound.find("#style_type_id").attr("name","styles["+table_row+"][style_type_id]").val(styleId);
                    trObjectFound.find("#style_name").attr("name","styles["+table_row+"][style_name]").text(styleName);
                    trObjectFound.find("#priority").attr("name","styles["+table_row+"][priority]").val(stylePriority);

                    var stylePriorityData = trObjectFound.find("[name='styles["+table_row+"][priority]']");
                    $('#assignStylesForm').bootstrapValidator('addField', stylePriorityData);
                    /*var validatorObject = $('#assignStylesForm').data('bootstrapValidator');
                    validatorObject.addField(stylePriorityData);*/

                    $("#table").find('tbody').append(trObjectFound);

                    styleArray.push(styleId);
                });
                $("#style_type_id").val(styleArray).trigger("change");

                $('.content .preloader').hide();
                $('.content .preloader img').hide();
            }

        });
    });
    $('#merchant_id').change(function(){
        var merchantID = $(this).val();
        var token = $('input[name="_token"]').val();


        var styleLimit = parseInt($('#merchant_id option:selected').attr('data-limit'));
        $("#style_type_id").select2({
            theme: "bootstrap",
            placeholder: "Retails type List",
            maximumSelectionLength: styleLimit
        });
        $('#location_id').html("");
        $('#style_type_id').html("");
        
        $.ajax({
            type:'POST',
            data:{_token:token,merchant_id:merchantID},
            dataType:"json",
            async:false,
            url : requestUrl+"/getAllStyles",
            error:function(xhr,status,error){
                console.log(error);
            },
            success:function(styles,status,xhr){
                $.each(styles, function(i,value) {
                    $('#style_type_id').append("<option value='"+value['style_type_id']+"'>"+value['style_name']+"</option>");
                });
            }
        });
        
        $.ajax({
            type:'POST',
            data:{_token:token,merchant_id:merchantID},
            dataType:"json",
            async:false,
            url : requestUrl+"/getLocations",
            error:function(xhr,status,error){
                console.log(error);
            },
            success:function(locations,status,xhr){
                $.each(locations, function(i,value) {
                    $('#location_id').append("<option value='"+value['location_id']+"'>"+value['location_name']+"</option>");
                });
                $('#location_id').trigger('change');
            }

        });
        $("#location_id").select2({
            theme: "bootstrap",
            placeholder: "Select Location"
        });
    });
    
    $('#style_type_id').on("select2:select", function (e) { 

        var table_row = e.params.data.id;
        $styleList = $('#demo').find('tbody').clone();
        $styleList.removeAttr('id');
        $styleList.removeAttr('style');
        $styleList.find('tr:last').attr("id","table-row"+table_row+"");
        var trObjectFound = $styleList.find("#table-row"+table_row+"");
        trObjectFound.find("#style_type_id").attr("name","styles["+table_row+"][style_type_id]").val(e.params.data.id);
        trObjectFound.find("#style_name").attr("name","styles["+table_row+"][style_name]").text(e.params.data.text);
        trObjectFound.find("#priority").attr("name","styles["+table_row+"][priority]");
        var stylePriorityData = trObjectFound.find("[name='styles["+table_row+"][priority]']");
        $('#assignStylesForm').bootstrapValidator('addField', stylePriorityData);
        $("#table").find('tbody').append(trObjectFound);
    });
    $('#style_type_id').on("select2:unselect", function (e) { 
        $("#table").find('tbody').find("#table-row"+e.params.data.id).remove();
    });

    $('#merchant_id').trigger('change');
    //$('#location_id').trigger('change');
    
});
