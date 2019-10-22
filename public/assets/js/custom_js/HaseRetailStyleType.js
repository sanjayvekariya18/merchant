'use strict';

$(document).ready(function () {   

    $(".select21").select2({
       theme: "bootstrap",
       placeholder: "Please Select Option",
       width: '100%',
       allowClear: true,
       
    }); 


    
    $("#delete_style_form").submit(function(e) {
        var deleteStyleUrl = $(this).attr("action");
        var token = $('input[name="_token"]').val();
        $.ajax({
            type: "POST",
            url: deleteStyleUrl,
            data: {_token:token},
            dataType: 'json',
            success: function(data)
            {
                if(data.success)
                {
                    $("#deleteStyle").modal('hide'); // show response from the php script.
                    toastr.options = {
                        "closeButton": true,
                        "positionClass": "toast-top-right",
                        "showDuration": "1000",
                        "hideDuration": "1000",
                        "timeOut": "5000",
                        "extendedTimeOut": "1000",
                        "showEasing": "swing",
                        "hideEasing": "swing",
                        "showMethod": "show"
                    };
                    var $toast = toastr["success"]("", "Style Successfully Deleted");
                    var table = $('#styleTable').DataTable();
                    $('#styleTable #' +data.style_type_id+ '').fadeOut( function() { $(this).remove(); });
                } 
            },
        });
        e.preventDefault(); // avoid to execute the actual submit of the form.
    });

    $("#create_style_form").bootstrapValidator({
        fields: {
            style_name: {
                validators: {
                    notEmpty: {
                        message: 'The style Name field is required.'
                    }
                },
                required: true,
                minlength: 3
            },
            style_priority: {
                validators: {
                    notEmpty: {
                        message: 'The Select Order field is required.'
                    },
                    regexp: {
                        regexp: /^[0-9]+$/,
                        message: 'The Select Order can only consist of numbers'
                    }
                },
                required: true,
            },
        }
    }).on('success.form.bv', function(e) {
        // Prevent form submission
        e.preventDefault();
        
        // Get the form instance
        var $form = $(e.target);
        
        // Get the BootstrapValidator instance
        var bv = $form.data('bootstrapValidator');
        
        // Use Ajax to submit form data
        var form_data = new FormData($(this)[0]);

        /* Insert style Data */
        var createstyleUrl = $(this).attr("action"); // the script where you handle the form input.
        
        $('#createStyle .preloader img').show();
        $('#createStyle .preloader').show();
        $.ajax({
            type: "POST",
            url: createstyleUrl,
            data: form_data, // serializes the form's elements.
            async: false,
            dataType: 'json',
            success: function(data)
            {
                if(data.success)
                {
                    $('#create_style_form').bootstrapValidator('resetForm', true);
                    $("#create_style_form .fileinput").fileinput("clear");
                    $("#createStyle").modal('hide'); // show response from the php script.
                    $('#createStyle .preloader').hide();
                    $('#createStyle .preloader img').hide();
                    toastr.options = {
                        "closeButton": true,
                        "positionClass": "toast-top-right",
                        "showDuration": "1000",
                        "hideDuration": "1000",
                        "timeOut": "5000",
                        "extendedTimeOut": "1000",
                        "showEasing": "swing",
                        "hideEasing": "swing",
                        "showMethod": "show"
                    };
                    var $toast = toastr["success"]("", "Style Successfully Created");
                    var styleImage = '<img src="'+data.style_image+'" style="width: 80px; height: 40px;">';
                    var styleImagecompact = '<img src="'+data.style_image_compact+'" style="width: 80px; height: 40px;">';
                    var action = '<a href="#" data-url="'+data.editUrl+'" type-id='+data.style_type_id+' class="editStyle" ><i class="fa fa-fw fa-pencil text-primary actions_icon" title="Edit Styles"></i></a><a href="#" data-url="'+data.deleteUrl+'" type-id='+data.style_type_id+' class="deleteStyle"><i class="fa fa-fw fa-times text-danger actions_icon" title="Delete Styles"></i></a>';
                    var table = $('#styleTable').DataTable();
                    if(!data.parent_name)
                    {
                        data.parent_name ='';   
                    }
                    var rowNode = table.row.add( [ action, data.style_type_id, data.style_name,data.parent_name,data.style_priority,styleImage] ).draw().node();
                    $( rowNode ).find('td').eq(1).addClass('style_type_id');
                    $( rowNode ).find('td').eq(2).addClass('style_name');
                    $( rowNode ).find('td').eq(3).addClass('parent_id');
                    $( rowNode ).find('td').eq(4).addClass('style_priority');
                    $( rowNode ).find('td').eq(5).addClass('style_image');
                    $( rowNode ).attr("id",data.style_type_id);
                }else{
                    $('#create_style_form').bootstrapValidator('resetForm', true);
                    $("#create_style_form .fileinput").fileinput("clear");
                    $("#createStyle").modal('hide'); // show response from the php script.
                    $('#createStyle .preloader').hide();
                    $('#createStyle .preloader img').hide();
                 toastr.options = {
                        "closeButton": true,
                        "positionClass": "toast-top-right",
                        "showDuration": "1000",
                        "hideDuration": "1000",
                        "timeOut": "5000",
                        "extendedTimeOut": "1000",
                        "showEasing": "swing",
                        "hideEasing": "swing",
                        "showMethod": "show"
                    };
                    var $toast = toastr["success"]("", "Style Already Exist");
                }


            },
            cache: false,
            contentType: false,
            processData: false
        });
    });

    $("#edit_style_form").bootstrapValidator({
        fields: {
            style_name: {
                validators: {
                    notEmpty: {
                        message: 'The style Name field is required.'
                    }
                },
                required: true,
                minlength: 3
            },
            style_priority: {
                validators: {
                    notEmpty: {
                        message: 'The Select Order field is required.'
                    },
                    regexp: {
                        regexp: /^[0-9]+$/,
                        message: 'The Select Order can only consist of numbers'
                    }
                },
                required: true,
            },
        }
    }).on('success.form.bv', function(e) {
        // Prevent form submission
        e.preventDefault();
        
        // Get the form instance
        var $form = $(e.target);
        
        // Get the BootstrapValidator instance
        var bv = $form.data('bootstrapValidator');
        
        // Use Ajax to submit form data
        var form_data = new FormData($(this)[0]);

        /* Insert style Data */
        var edittyleUrl = $(this).attr("action"); // the script where you handle the form input.
        
        $('#editStyle .preloader img').show();
        $('#editStyle .preloader').show();
        $.ajax({
            type: "POST",
            url: edittyleUrl,
            data: form_data, // serializes the form's elements.
            async: false,
            dataType: 'json',
            success: function(data)
            {
                if(data.success)
                {
                    $('#edit_style_form').bootstrapValidator('resetForm', true);
                    $("#edit_style_form .fileinput").fileinput("clear");
                    $("#editStyle").modal('hide'); // show response from the php script.
                    $('#editStyle .preloader').hide();
                    $('#editStyle .preloader img').hide();
                    toastr.options = {
                        "closeButton": true,
                        "positionClass": "toast-top-right",
                        "showDuration": "1000",
                        "hideDuration": "1000",
                        "timeOut": "5000",
                        "extendedTimeOut": "1000",
                        "showEasing": "swing",
                        "hideEasing": "swing",
                        "showMethod": "show"
                    };
                    var $toast = toastr["success"]("", "Style Successfully Updated");
                    var styleImage = '<img src="'+data.style_image+'" style="width: 80px; height: 40px;">';
                    var action = '<a href="#" data-url="'+data.editUrl+'" type-id='+data.style_type_id+' class="editStyle" ><i class="fa fa-fw fa-pencil text-primary actions_icon" title="Edit Styles"></i></a>';
                    $('#styleTable #' +data.style_type_id+ ' .style_type_id').html(data.style_type_id);
                    $('#styleTable #' + data.style_type_id + ' .style_name').html(data.style_name);
                    $('#styleTable #' + data.style_type_id + ' .parent_id').html(data.parent_name);
                    $('#styleTable #' + data.style_type_id + ' .style_priority').html(data.style_priority);
                    $('#styleTable #' + data.style_type_id + ' .style_image').html(styleImage);
                }else{
                    $('#edit_style_form').bootstrapValidator('resetForm', true);
                    $("#edit_style_form .fileinput").fileinput("clear");
                    $("#editStyle").modal('hide'); // show response from the php script.
                    $('#editStyle .preloader').hide();
                    $('#editStyle .preloader img').hide();
                    toastr.options = {
                        "closeButton": true,
                        "positionClass": "toast-top-right",
                        "showDuration": "1000",
                        "hideDuration": "1000",
                        "timeOut": "5000",
                        "extendedTimeOut": "1000",
                        "showEasing": "swing",
                        "hideEasing": "swing",
                        "showMethod": "show"
                    };
                    var $toast = toastr["success"]("", "Updated Style Already Exist");
                }
            },
            cache: false,
            contentType: false,
            processData: false
        });
    });

    $(document).on('click', '.deleteStyle', function(ev){

        ev.preventDefault();
        var target = $(this).attr("data-url");
        $('#delete_style_form').attr("action",target);
        $("#deleteStyle").modal("show"); 

    });


    $(document).on('click', '.editStyle', function(ev){
        ev.preventDefault();
        var target = $(this).attr("data-url");
        $('#edit_style_form').attr("action",target);
        $("#editStyle").modal("show"); 

        var styleTypeId = $(this).attr("type-id");
        var merchant_type = $("#edit_style_form #merchant_type").val();
        var getRowStyle = $("#getStyleRow").val();
        var getParentStyle = $("#getParentStyle").val();
        var token = $('input[name="_token"]').val();

        $('#edit_style_form #parent_id').html("<option value='0'>None</option>");
        $.ajax({
            type: "POST",
            url: getParentStyle,
            data: {_token:token,style_type_id:styleTypeId,merchant_type:merchant_type}, // serializes the form's elements.
            async: false,
            dataType: 'json',
            success: function(styledata)
            {
                if(styledata.length)
                {
                    $.each(styledata, function(i,value) {
                        $('#edit_style_form #parent_id').append("<option value='"+value['style_type_id']+"'>"+value['style_name']+"</option>");
                    });                    
                }
            }
        });

        $.ajax({
            type: "POST",
            url: getRowStyle,
            data: {_token:token,style_type_id:styleTypeId}, // serializes the form's elements.
            async: false,
            dataType: 'json',
            success: function(data)
            {
                $('#edit_style_form #style_name').val(data.style_name);
                $('#edit_style_form #parent_id').val(data.style_parent_id);
                $('#edit_style_form #style_priority').val(data.style_priority);
                
                if(data.style_image_url != "")
                {
                    $('#edit_style_form #live_image_url').val(data.style_image_url);
                    $('#edit_style_form #style_image').parent().parent().parent().removeClass("fileinput-new");
                    $('#edit_style_form #style_image').parent().parent().parent().addClass("fileinput-exists");
                    $('#edit_style_form #style_image').parent().parent().parent().find('.fileinput-preview').html('<img src='+data.style_image_url+' alt="profile pic" class="profile_pic">');
                } else {
                    if(data.style_image != "")
                    {
                        $('#edit_style_form #style_image').parent().parent().parent().removeClass("fileinput-new");
                        $('#edit_style_form #style_image').parent().parent().parent().addClass("fileinput-exists");
                        $('#edit_style_form #style_image').parent().parent().parent().find('.fileinput-filename').html(data.style_image);
                        $('#edit_style_form #style_image').parent().parent().parent().find('.fileinput-preview').html('<img src='+data.style_image+' alt="profile pic" class="profile_pic">');
                    }
                }

                if(data.style_image_compact_url != "")
                {
                    $('#edit_style_form #live_image_compact_url').val(data.style_image_compact_url);
                    $('#edit_style_form #style_image_compact').parent().parent().parent().removeClass("fileinput-new");
                    $('#edit_style_form #style_image_compact').parent().parent().parent().addClass("fileinput-exists");
                    $('#edit_style_form #style_image_compact').parent().parent().parent().find('.fileinput-preview').html('<img src='+data.style_image_compact_url+' alt="profile pic" class="profile_pic">');
                } else {
                    if(data.style_image_compact != "")
                    {
                        $('#edit_style_form #style_image_compact').parent().parent().parent().removeClass("fileinput-new");
                        $('#edit_style_form #style_image_compact').parent().parent().parent().addClass("fileinput-exists");
                        $('#edit_style_form #style_image_compact').parent().parent().parent().find('.fileinput-filename').html(data.style_image_compact);
                        $('#edit_style_form #style_image_compact').parent().parent().parent().find('.fileinput-preview').html('<img src='+data.style_image_compact+' alt="profile pic" class="profile_pic">');
                    }
                }
            },            
        });
    });
});
