'use strict';

$(document).ready(function () {
    

    $("#delete_category_form").submit(function(e) {
        var deleteCategoryUrl = $(this).attr("action");
        var token = $('input[name="_token"]').val();
        $.ajax({
            type: "POST",
            url: deleteCategoryUrl,
            data: {_token:token},
            dataType: 'json',
            success: function(data)
            {
                if(data.success)
                {
                    $("#deleteCategory").modal('hide'); // show response from the php script.
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
                    var $toast = toastr["success"]("", "Category Successfully Deleted");
                    var table = $('#categoryTable').DataTable();
                    $('#categoryTable #' +data.category_type_id+ '').fadeOut( function() { $(this).remove(); });
                } 
            },
        });
        e.preventDefault(); // avoid to execute the actual submit of the form.
    });

    $("#create_category_form").bootstrapValidator({
        fields: {
            category_name: {
                validators: {
                    notEmpty: {
                        message: 'The category Name field is required.'
                    }
                },
                required: true,
                minlength: 3
            },
            category_priority: {
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

        /* Insert category Data */
        var createcategoryUrl = $(this).attr("action"); // the script where you handle the form input.
        
        $('#createCategory .preloader img').show();
        $('#createCategory .preloader').show();
        $.ajax({
            type: "POST",
            url: createcategoryUrl,
            data: form_data, // serializes the form's elements.
            async: false,
            dataType: 'json',
            success: function(data)
            {
                if(data.success)
                {

                    console.log("called");
                    $('#create_category_form').bootstrapValidator('resetForm', true);
                    $("#create_category_form .fileinput").fileinput("clear");
                    $("#createCategory").modal('hide'); // show response from the php script.
                    $('#createCategory .preloader').hide();
                    $('#createCategory .preloader img').hide();
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
                    var $toast = toastr["success"]("", "Category Successfully Created");
                    var categoryImage = '<img src="'+data.category_image+'" style="width: 80px; height: 40px;">';
                    var action = '<a href="#" data-url="'+data.editUrl+'" type-id='+data.type_id+' class="editCategory" ><i class="fa fa-fw fa-pencil text-primary actions_icon" title="Edit Category"></i></a><a href="#" data-url="'+data.deleteUrl+'" type-id='+data.style_type_id+' class="deleteCategory" ><i class="fa fa-fw fa-times text-danger actions_icon" title="Delete Category"></i></a>';
                    var table = $('#categoryTable').DataTable();

                    var rowNode = table.row.add( [ action, data.category_type_id, data.category_name,data.parent_name,data.category_priority,categoryImage] ).draw().node();
                    $( rowNode ).find('td').eq(1).addClass('type_id');
                    $( rowNode ).find('td').eq(2).addClass('category_name');
                    $( rowNode ).find('td').eq(3).addClass('parent_id');
                    $( rowNode ).find('td').eq(4).addClass('category_priority');
                    $( rowNode ).find('td').eq(5).addClass('category_image');
                    $( rowNode ).attr("id",data.category_type_id);
                }else{
                    $('#create_category_form').bootstrapValidator('resetForm', true);
                    $("#create_category_form .fileinput").fileinput("clear");
                    $("#createCategory").modal('hide'); // show response from the php script.
                    $('#createCategory .preloader').hide();
                    $('#createCategory .preloader img').hide();
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
                    var $toast = toastr["success"]("", "Category Already Exist");

                }
            },
            cache: false,
            contentType: false,
            processData: false
        });
    });


    $("#edit_category_form").bootstrapValidator({
        fields: {
            category_name: {
                validators: {
                    notEmpty: {
                        message: 'The category Name field is required.'
                    }
                },
                required: true,
                minlength: 3
            },
            category_priority: {
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

        /* Insert category Data */
        var edittyleUrl = $(this).attr("action"); // the script where you handle the form input.
        
        $('#editCategory .preloader img').show();
        $('#editCategory .preloader').show();
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
                    $('#edit_category_form').bootstrapValidator('resetForm', true);
                    $("#edit_category_form .fileinput").fileinput("clear");
                    $("#editCategory").modal('hide'); // show response from the php script.
                    $('#editCategory .preloader').hide();
                    $('#editCategory .preloader img').hide();
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
                    var $toast = toastr["success"]("", "Category Successfully Updated");
                    var categoryImage = '<img src="'+data.category_image+'" style="width: 80px; height: 40px;">';
                    var action = '<a href="#" data-url="'+data.editUrl+'" type-id='+data.category_type_id+' class="editCategory" ><i class="fa fa-fw fa-pencil text-primary actions_icon" title="Edit Categorys"></i></a>';
                    $('#categoryTable #' +data.category_type_id+ ' .category_type_id').html(data.category_type_id);
                    $('#categoryTable #' + data.category_type_id + ' .category_name').html(data.category_name);
                    $('#categoryTable #' + data.category_type_id + ' .parent_id').html(data.parent_name);
                    $('#categoryTable #' + data.category_type_id + ' .category_priority').html(data.category_priority);
                    $('#categoryTable #' + data.category_type_id + ' .category_image').html(categoryImage);
                }else{
                    $('#edit_category_form').bootstrapValidator('resetForm', true);
                    $("#edit_category_form .fileinput").fileinput("clear");
                    $("#editCategory").modal('hide'); // show response from the php script.
                    $('#editCategory .preloader').hide();
                    $('#editCategory .preloader img').hide();
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
                    var $toast = toastr["success"]("", "Updated Category Already Exist");
                }
            },
            cache: false,
            contentType: false,
            processData: false
        });
    });

    $(document).on('click','.deleteCategory',function(ev) {
        ev.preventDefault();
        var target = $(this).attr("data-url");
        $('#delete_category_form').attr("action",target);
        $("#deleteCategory").modal("show"); 
    });

    $(document).on('click', '.editCategory', function(ev){
        ev.preventDefault();
        var target = $(this).attr("data-url");
        $('#edit_category_form').attr("action",target);
        $("#editCategory").modal("show"); 

        var categoryTypeId = $(this).attr("type-id");
        var getRowCategory = $("#getCategoryRow").val();
        var getParentCategory = $("#getParentCategory").val();
        var merchant_type = $("#edit_category_form #merchant_type").val();
        var token = $('input[name="_token"]').val();

        $('#edit_category_form #parent_id').html("<option value='0'>None</option>");
        $.ajax({
            type: "POST",
            url: getParentCategory,
            data: {_token:token,category_type_id:categoryTypeId,merchant_type:merchant_type}, 
            async: false,
            dataType: 'json',
            success: function(categorydata)
            {
                if(categorydata.length)
                {
                    $.each(categorydata, function(i,value) {
                        $('#edit_category_form #parent_id').append("<option value='"+value['category_type_id']+"'>"+value['category_name']+"</option>");
                    });                    
                }
            }
        });

        $.ajax({
            type: "POST",
            url: getRowCategory,
            data: {_token:token,type_id:categoryTypeId}, // serializes the form's elements.
            async: false,
            dataType: 'json',
            success: function(data)
            {

                $('#edit_category_form #category_name').val(data.category_name);
                $('#edit_category_form #parent_id').val(data.category_parent_id);
                $('#edit_category_form #category_priority').val(data.category_priority);
                
                if(data.category_image_url != "")
                {
                    $('#edit_category_form #live_image_url').val(data.category_image_url);
                    $('#edit_category_form #category_image').parent().parent().parent().removeClass("fileinput-new");
                    $('#edit_category_form #category_image').parent().parent().parent().addClass("fileinput-exists");
                    $('#edit_category_form #category_image').parent().parent().parent().find('.fileinput-preview').html('<img src='+data.category_image_url+' alt="profile pic" class="profile_pic">');
                } else {
                    if(data.category_image != "")
                    {
                        $('#edit_category_form #category_image').parent().parent().parent().removeClass("fileinput-new");
                        $('#edit_category_form #category_image').parent().parent().parent().addClass("fileinput-exists");
                        $('#edit_category_form #category_image').parent().parent().parent().find('.fileinput-filename').html(data.category_image);
                        $('#edit_category_form #category_image').parent().parent().parent().find('.fileinput-preview').html('<img src='+data.category_image+' alt="profile pic" class="profile_pic">');
                    }
                }

                if(data.category_image_compact_url != "")
                {
                    $('#edit_category_form #live_image_compact_url').val(data.category_image_compact_url);
                    $('#edit_category_form #category_image_compact').parent().parent().parent().removeClass("fileinput-new");
                    $('#edit_category_form #category_image_compact').parent().parent().parent().addClass("fileinput-exists");
                    $('#edit_category_form #category_image_compact').parent().parent().parent().find('.fileinput-preview').html('<img src='+data.category_image_compact_url+' alt="profile pic" class="profile_pic">');
                } else {
                    if(data.category_image_compact != "")
                    {
                        $('#edit_category_form #category_image_compact').parent().parent().parent().removeClass("fileinput-new");
                        $('#edit_category_form #category_image_compact').parent().parent().parent().addClass("fileinput-exists");
                        $('#edit_category_form #category_image_compact').parent().parent().parent().find('.fileinput-filename').html(data.category_image_compact);
                        $('#edit_category_form #category_image_compact').parent().parent().parent().find('.fileinput-preview').html('<img src='+data.category_image_compact+' alt="profile pic" class="profile_pic">');
                    }
                }
            },
        });
    });
});
