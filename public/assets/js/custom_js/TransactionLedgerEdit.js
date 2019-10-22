
$(document).ready(function(){
    $(".select21").select2({
       theme: "bootstrap",
       placeholder: "Please Select Option",
       width: '100%'
    });

    $('#trade_date').datepicker({
        autoClose : true
    });

    $("#trade_time").timeDropper({
        primaryColor: "#428bca",
        setCurrentTime: false,
        format: "HH:mm"
    });
});