$(function () {
    $("#edit_menu_options_form").bootstrapValidator({
        excluded: [':disabled'],
        fields: {
            option_name: {
                validators: {
                    notEmpty: {
                        message: 'The Option Name field is required.'
                    }
                },
                required: true,
            },
            location_id: {
                validators: {
                    notEmpty: {
                        message: 'The Option Name field is required.'
                    }
                },
                required: true,
            },
            priority: {
                validators: {
                    notEmpty: {
                        message: 'The Priority field is required.'
                    }
                },
                regexp: {
                    regexp: /^[0-9]+$/,
                    message: 'The phone number can only consist of numbers'
                }
            }
        }
    });
    $('#display_type').val($('#displayType').val());
    $("tbody").sortable();
    $("#menuDynamicOptionRow").hide();
    $('#pager_wizard').bootstrapWizard({
        'tabClass': 'nav nav-pills',
        onTabClick: function (tab, navigation, index) {
            var $total = navigation.find('li').length;
            var $current = index + 1;
            var $percent = ($current / $total) * 100;
            var pager_wizard = $('#pager_wizard');
            // If it's the last tab then hide the last button and show the finish instead
            if ($current >= $total) {
                pager_wizard.find('.pager .next').hide();
                pager_wizard.find('.pager .finish').show();
                pager_wizard.find('.pager .finish').removeClass('disabled');
            } else {
                pager_wizard.find('.pager .next').show();
                pager_wizard.find('.pager .finish').hide();
            }
        }
    });

    $(".select21").select2({
        theme: "bootstrap",
        placeholder: "Please Select Option",
        width: '100%'
    });
});
var optionCount = $("#optionCount").val();
function addValue()
{   
    optionCount++;
    $("#table").find('tbody').append($("#menuDynamicOptionRow").find('tbody').html());
    $("#table").find('tbody').find('tr:last').attr("id","table-row"+optionCount+"");
    var trObjectFound = $("#table").find('tbody').find("#table-row"+optionCount+"");
    trObjectFound.find("#value").attr("name","option_values["+optionCount+"][value]");
    trObjectFound.find("#price").attr("name","option_values["+optionCount+"][price]");
    trObjectFound.find("#option_value_id").attr("name","option_values["+optionCount+"][option_value_id]");
    var priceOptionData = trObjectFound.find("[name='option_values["+optionCount+"][price]']");
    var ValueOptionData = trObjectFound.find("[name='option_values["+optionCount+"][value]']");
    var validatorObject = $('#edit_menu_options_form').data('bootstrapValidator');
    validatorObject.addField(priceOptionData);
    validatorObject.addField(ValueOptionData);
    $("#optionCount").val(optionCount);
}
