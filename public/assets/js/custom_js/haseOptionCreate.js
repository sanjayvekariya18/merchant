$(function () {
    
    $("#create_menu_options_form").bootstrapValidator({
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
            },
            /*optionValueRequired: {
                selector: '.optionValueRequired',
                validators: {
                    notEmpty: {
                        message: 'The Option Value field is required..'
                    },
                }
            },
            optionPriceRequired: {
                selector: '.optionPriceRequired',
                validators: {
                    regexp: {
                        regexp: /^[0-9]+$/,
                        message: 'The Option Price field must contain only numbers.'
                    }
                }
            }*/
        }
    });
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
var table_row = 1;
function addValue()
{   
    $("#table").find('tbody').append($("#menuDynamicOptionRow").find('tbody').html());
    $("#table").find('tbody').find('tr:last').attr("id","table-row"+table_row+"");
    var trObjectFound = $("#table").find('tbody').find("#table-row"+table_row+"");
    trObjectFound.find("#value").attr("name","option_values["+table_row+"][value]");
    trObjectFound.find("#price").attr("name","option_values["+table_row+"][price]");
    trObjectFound.find("#option_value_id").attr("name","option_values["+table_row+"][option_value_id]");
    var priceOptionData = trObjectFound.find("[name='option_values["+table_row+"][price]']");
    var ValueOptionData = trObjectFound.find("[name='option_values["+table_row+"][value]']");
    var validatorObject = $('#create_menu_options_form').data('bootstrapValidator');
    validatorObject.addField(priceOptionData);
    validatorObject.addField(ValueOptionData);
    table_row++;
    
}
