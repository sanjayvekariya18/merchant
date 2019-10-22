'use strict';

$(document).ready(function () {

    $('.option_enable').bootstrapSwitch('state', true);

    $("#delete_option_form").submit(function(e) {
        var deleteOptionUrl = $(this).attr("action");
        var token = $('input[name="_token"]').val();
        $.ajax({
            type: "POST",
            url: deleteOptionUrl,
            data: {_token:token},
            dataType: 'json',
            success: function(data)
            {
                if(data.success)
                {
                    $("#deleteOption").modal('hide'); // show response from the php script.
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
                    var $toast = toastr["success"]("", "option Successfully Deleted");
                    var table = $('#optionTable').DataTable();
                    $('#optionTable #' +data.category_option_type_id+ '').fadeOut( function() { $(this).remove(); });
                } 
            },
        });
        e.preventDefault(); // avoid to execute the actual submit of the form.
    });
    $("#create_option_form").bootstrapValidator({
        fields: {
            option_name: {
                validators: {
                    notEmpty: {
                        message: 'The option Name field is required.'
                    }
                },
                required: true,
                minlength: 3
            }
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

        /* Insert option Data */
        var createoptionUrl = $(this).attr("action"); // the script where you handle the form input.
        
        $('#createOption .preloader img').show();
        $('#createOption .preloader').show();
        $.ajax({
            type: "POST",
            url: createoptionUrl,
            data: form_data, // serializes the form's elements.
            async: false,
            dataType: 'json',
            success: function(data)
            {
                if(data.success)
                {
                    $('#create_option_form').bootstrapValidator('resetForm', true);
                    $("#createOption").modal('hide'); // show response from the php script.
                    $('#createOption .preloader').hide();
                    $('#createOption .preloader img').hide();
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
                    var $toast = toastr["success"]("", "Option Successfully Created");
                    var optionImage = '<img src="'+data.option_image+'" style="width: 80px; height: 40px;">';
                    var action = '<a href="#" data-url="'+data.editUrl+'" type-id='+data.category_option_type_id+' class="editoption" ><i class="fa fa-fw fa-pencil text-primary actions_icon" title="Edit Option"></i></a><a href="#" data-url="'+data.deleteUrl+'" type-id='+data.style_type_id+' class="deleteOption" ><i class="fa fa-fw fa-times text-danger actions_icon" title="Delete Option"></i></a>';
                    var table = $('#optionTable').DataTable();
     
                    
                    var rowNode = table.row.add( [ action, data.category_option_type_id, data.option_name,optionImage] ).draw().node();
                    $( rowNode ).find('td').eq(1).addClass('option_type_id');
                    $( rowNode ).find('td').eq(2).addClass('option_name');
                    $( rowNode ).find('td').eq(3).addClass('option_image');
                    $( rowNode ).attr("id",data.category_option_type_id);
                }else{
                    $('#create_option_form').bootstrapValidator('resetForm', true);
                    $("#createOption").modal('hide'); // show response from the php script.
                    $('#createOption .preloader').hide();
                    $('#createOption .preloader img').hide();
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
                    var $toast = toastr["success"]("", "Option Already Exits");

                }
            },
            cache: false,
            contentType: false,
            processData: false
        });
    });

    $("#edit_option_form").bootstrapValidator({
        fields: {
            option_name: {
                validators: {
                    notEmpty: {
                        message: 'The option Name field is required.'
                    }
                },
                required: true,
                minlength: 3
            }
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

        /* Insert option Data */
        var edittyleUrl = $(this).attr("action"); // the script where you handle the form input.
        
        $('#editoption .preloader img').show();
        $('#editoption .preloader').show();
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
                    $('#edit_option_form').bootstrapValidator('resetForm', true);
                    $("#editOption").modal('hide'); // show response from the php script.
                    $('#editoption .preloader').hide();
                    $('#editoption .preloader img').hide();
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
                    var $toast = toastr["success"]("", "Option Successfully Updated");
                    var optionImage = '<img src="'+data.option_image+'" style="width: 80px; height: 40px;">';
                    var action = '<a href="#" data-url="'+data.editUrl+'" type-id='+data.category_option_type_id+' class="editoption" ><i class="fa fa-fw fa-pencil text-primary actions_icon" title="Edit options"></i></a>';
                    $('#optionTable #' +data.category_option_type_id+ ' .option_type_id').html(data.category_option_type_id);
                    $('#optionTable #' + data.category_option_type_id + ' .option_name').html(data.option_name);
                    $('#optionTable #' + data.category_option_type_id + ' .option_image').html(optionImage);
                }else{
                    $('#edit_option_form').bootstrapValidator('resetForm', true);
                    $("#editOption").modal('hide'); // show response from the php script.
                    $('#editoption .preloader').hide();
                    $('#editoption .preloader img').hide();
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
                    var $toast = toastr["success"]("", "Updated Option Already Exits");
                }
            },
            cache: false,
            contentType: false,
            processData: false
        });
    });

    $(document).on('click', '.deleteOption', function(ev){
        ev.preventDefault();
        var target = $(this).attr("data-url");
        $('#delete_option_form').attr("action",target);
        $("#deleteOption").modal("show"); 
    });


    $(document).on('click', '.editoption', function(ev){
        console.log("hello");
        ev.preventDefault();
        var target = $(this).attr("data-url");
        $('#edit_option_form').attr("action",target);
        $("#editOption").modal("show"); 

        var optionTypeId = $(this).attr("type-id");
        var getRowOption = $("#getOptionRow").val();
        var token = $('input[name="_token"]').val();

        $.ajax({
            type: "POST",
            url: getRowOption,
            data: {_token:token,option_type_id:optionTypeId}, // serializes the form's elements.
            async: false,
            dataType: 'json',
            success: function(data)
            {
                $('#edit_option_form #option_name').val(data.option_name);
                if(data.option_image_url != "")
                {
                    $('#edit_option_form #live_image_url').val(data.option_image_url);
                    $('#edit_option_form #option_image').parent().parent().parent().removeClass("fileinput-new");
                    $('#edit_option_form #option_image').parent().parent().parent().addClass("fileinput-exists");
                    $('#edit_option_form #option_image').parent().parent().parent().find('.fileinput-preview').html('<img src='+data.option_image_url+' alt="profile pic" class="profile_pic">');
                } else {
                    if(data.option_image != "")
                    {
                        $('#edit_option_form #option_image').parent().parent().parent().removeClass("fileinput-new");
                        $('#edit_option_form #option_image').parent().parent().parent().addClass("fileinput-exists");
                        $('#edit_option_form #option_image').parent().parent().parent().find('.fileinput-filename').html(data.option_image);
                        $('#edit_option_form #option_image').parent().parent().parent().find('.fileinput-preview').html('<img src='+data.option_image+' alt="profile pic" class="profile_pic">');
                    }
                }

                if(data.option_image_compact_url != "")
                {
                    $('#edit_option_form #live_image_compact_url').val(data.option_image_compact_url);
                    $('#edit_option_form #option_image_compact').parent().parent().parent().removeClass("fileinput-new");
                    $('#edit_option_form #option_image_compact').parent().parent().parent().addClass("fileinput-exists");
                    $('#edit_option_form #option_image_compact').parent().parent().parent().find('.fileinput-preview').html('<img src='+data.option_image_compact_url+' alt="profile pic" class="profile_pic">');
                } else {
                    if(data.option_image_compact != "")
                    {
                        $('#edit_option_form #option_image_compact').parent().parent().parent().removeClass("fileinput-new");
                        $('#edit_option_form #option_image_compact').parent().parent().parent().addClass("fileinput-exists");
                        $('#edit_option_form #option_image_compact').parent().parent().parent().find('.fileinput-filename').html(data.option_image_compact);
                        $('#edit_option_form #option_image_compact').parent().parent().parent().find('.fileinput-preview').html('<img src='+data.option_image_compact+' alt="profile pic" class="profile_pic">');
                    }
                }
                if(data.option_enable == 1)
                {
                    $('#option_enable').bootstrapSwitch('state', true);
                } else {
                    $('#option_enable').bootstrapSwitch('state', false);
                }
            },
        });
    });
});
