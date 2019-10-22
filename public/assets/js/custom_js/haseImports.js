$(function () {

    $("#input-23").fileinput({
        browseClass: "btn btn-default",
        showUpload: false,
        mainTemplate: "{preview}\n" +
            "<div class='input-group {class}'>\n" +
            "   <div class='input-group-btn'>\n" +
            "       {browse}\n" +
            "       {upload}\n" +
            "       {remove}\n" +
            "   </div>\n" +
            "   {caption}\n" +
            "</div>"
    });

    $("#importForm").bootstrapValidator({
    excluded: [':disabled'],
    fields: {
        title: {
            validators: {
                notEmpty: {
                    message: 'Select Import Name'
                }
            },
            required: true,
        },
        upload_file: {
            validators: {
                notEmpty: {
                    message: 'File is required'
                }
            },
            required: true,
        }
    }
    });

});