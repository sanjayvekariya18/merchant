$(document).ready(function () {

    var token = $('input[name="_token"]').val();

    $(".select21").select2({
        theme: "bootstrap",
        placeholder: "Please Select Option"
    });

    
    //-------------------------------Account List Tagging-------------------------------------

    function initialize(){
        
        $("#panelList").find("select").select2({
            theme: "bootstrap",
            placeholder: "Please Select Option"
        });

        $("#panelList").find("input:checkbox").bootstrapSwitch();
    }

    $("#language_id").on("select2:select",function(event){
        
        setRow(event.params.data.id,event.params.data.text)
        initialize();
    });

    $('#language_id').on("select2:unselect", function (event) {
        languageId =event.params.data.id;
        $("#panelList").find("#language_id_"+languageId).remove();
    });

    function setRow(languageId,languageName){
        $languageList = $('#languageDemo').clone();
        $languageList.removeAttr('id');
        $languageList.removeAttr('style');
        $languageList.css("margin-top", "10px");
        $languageList.attr('id',"language_id_"+languageId);
        $tabIndex = $('#panelList').children().length - 1;
        
        $languageList.find('#language_name').text(languageName);
        $languageList.find('#languageId').attr('name',"languages["+$tabIndex+"][language_id]").val(languageId);
        $languageList.find('#priority').attr('name',"languages["+$tabIndex+"][priority]");
        $languageList.find('#status').attr('name',"languages["+$tabIndex+"][status]");

        $('#panelList').append($languageList);
        initialize();
    }

    function setDefaultRow(languageId,languageName,priority,status){
        $languageList = $('#languageDemo').clone();
        $languageList.removeAttr('id');
        $languageList.removeAttr('style');
        $languageList.css("margin-top", "10px");
        $languageList.attr('id',"language_id_"+languageId);
        $tabIndex = $('#panelList').children().length - 1;
        
        $languageList.find('#language_name').text(languageName);
        $languageList.find('#languageId').attr('name',"languages["+$tabIndex+"][language_id]").val(languageId);
        $languageList.find('#priority').attr('name',"languages["+$tabIndex+"][priority]").val(priority);
        $languageList.find('#status').attr('name',"languages["+$tabIndex+"][status]");
        if(status == 1){
            $languageList.find('#status').prop('checked', true);
        }else{
            $languageList.find('#status').prop('checked', false);
        }

        $('#panelList').append($languageList);
        initialize();
    }

    $("#exchange_id").on("change",function(){

        var assetId = $("#exchange_id").val();
        var requestUrl = $("#requestUrl").val();

        $('#panelList').html("<div class='row'>"+
                                "<div class='col-md-12'>"+
                                    "<div class='row'>"+
                                        "<label class='col-md-6'>Language Name</label>"+
                                        "<label class='col-md-3'>Priority</label>"+
                                        "<label class='col-md-3'>Status</label>"+
                                    "</div>"+
                                "</div>"+
                            "</div>");
        $.ajax({
            type:'POST',
            data:{_token:token,exchange_id:assetId},
            dataType:"json",
            url : requestUrl+"/getLanguages",
            async: false,
            error:function(xhr,status,error){
                console.log(error);
            },
            success:function(languages,status,xhr){
                var languageArray = [];
                $.each(languages, function(i,value) {
                    var languageId = value['language_id'];
                    var languageName = value['language_name'];
                    var languagePriority = value['priority'];
                    var languageStatus = value['status'];

                    languageArray.push(languageId);
                    setDefaultRow(languageId,languageName,languagePriority,languageStatus);
                });
                $("#language_id").val(languageArray).change();
            }
        });

    });
    
});

