$(document).ready(function () {

    
    $(".select21").select2({
        theme: "bootstrap",
        placeholder: "Please Select Option",
    });

    $("input[name='enable']").bootstrapSwitch();

    $("input[name='enable']").on('switchChange.bootstrapSwitch', function (event, state) {
        if (state) {
            $("#enable").attr('checked',true);
        } else {
            $("#enable").attr('checked',false);
        }
        event.preventDefault();
    });

});

