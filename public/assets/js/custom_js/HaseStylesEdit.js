$(document).ready(function () {

	var styleLimit = parseInt($('#style_type_id').attr('data-limit'));

    $("#style_type_id").select2({
        theme: "bootstrap",
        placeholder: "Retails type List",
        maximumSelectionLength: styleLimit
    });
    $('#editAssignStylesForm').bootstrapValidator({
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
    $("#enable").bootstrapSwitch();
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
});