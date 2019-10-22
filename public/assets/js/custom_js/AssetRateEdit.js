
$(document).ready(function(){
    $(".select21").select2({
       theme: "bootstrap",
       placeholder: "Please Select Option",
       width: '100%'
    });

    $('#asset_last_date').datepicker({
        autoClose : true
    });

    $("#asset_last_time").timeDropper({
        primaryColor: "#428bca",
        setCurrentTime: false,
        format: "HH:mm"
    });

    $("#asset_rate_edit").bootstrapValidator({
        excluded: [':disabled'],
        fields: {
            asset_from_id: {
                validators: {
                    notEmpty: {
                        message: 'The asset from field is required. Please select.'
                    }
                },
                required: true
            },
            asset_into_id: {
                validators: {
                    notEmpty: {
                        message: 'The asset into field is required. Please select.'
                    }
                },
                required: true
            },
            asset_last_timezone: {
                validators: {
                    notEmpty: {
                        message: 'The asset timezone field is required. Please select.'
                    }
                },
                required: true
            },
            asset_source_id: {
                validators: {
                    notEmpty: {
                        message: 'The asset source id field is required. Please select.'
                    }
                },
                required: true
            },
        }
    });
});