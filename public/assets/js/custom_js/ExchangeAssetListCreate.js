$(document).ready(function () {
	var token = $('input[name="_token"]').val();

    $(".select21").select2({
        theme: "bootstrap",
        placeholder: "Please Select Option"
    });

    //-------------------------------Exchange Asset List Tagging-------------------------------------

    function initialize(){
        
        $("#panelList").find("select").select2({
            theme: "bootstrap",
            placeholder: "Please Select Option"
        });

        $("#panelList").find("input:checkbox").bootstrapSwitch();
    }

    $("#asset_id").on("select2:select",function(event){
        
        setRow(event.params.data.id,event.params.data.text,event.params.data.element.attributes.asset_code.value)
        initialize();
    });

    $('#asset_id').on("select2:unselect", function (event) {
        assetId =event.params.data.id;
        $("#panelList").find("#asset_id_"+assetId).remove();
    });

    function setRow(assetId,assetName,assetCode){
        $assetList = $('#assetDemo').clone();
        $assetList.removeAttr('id');
        $assetList.removeAttr('style');
        $assetList.css("margin-top", "10px");
        $assetList.attr('id',"asset_id_"+assetId);
        $tabIndex = $('#panelList').children().length - 1;
        
        $assetList.find('#asset_name').text(assetName);
        $assetList.find('#assetId').attr('name',"assets["+$tabIndex+"][asset_id]").val(assetId);
        $assetList.find('#asset_code').attr('name',"assets["+$tabIndex+"][asset_code]").val(assetCode);
        $assetList.find('#priority').attr('name',"assets["+$tabIndex+"][priority]");
        $assetList.find('#status').attr('name',"assets["+$tabIndex+"][status]");

        $('#panelList').append($assetList);
        initialize();
    }

    function setDefaultRow(assetId,assetName,assetCode,priority,status){
        $assetList = $('#assetDemo').clone();
        $assetList.removeAttr('id');
        $assetList.removeAttr('style');
        $assetList.css("margin-top", "10px");
        $assetList.attr('id',"asset_id_"+assetId);
        $tabIndex = $('#panelList').children().length - 1;
        
        $assetList.find('#asset_name').text(assetName);
        $assetList.find('#assetId').attr('name',"assets["+$tabIndex+"][asset_id]").val(assetId);
        $assetList.find('#asset_code').attr('name',"assets["+$tabIndex+"][asset_code]").val(assetCode);
        $assetList.find('#priority').attr('name',"assets["+$tabIndex+"][priority]").val(priority);
        $assetList.find('#status').attr('name',"assets["+$tabIndex+"][status]");
        if(status == 1){
            $assetList.find('#status').prop('checked', true);
        }else{
            $assetList.find('#status').prop('checked', false);
        }

        $('#panelList').append($assetList);
        initialize();
    }

    $("#exchange_id").on("change",function(){

        var exchangeId = $("#exchange_id").val();
        var requestUrl = $("#requestUrl").val();

        $('#panelList').html("<div class='row'>"+
                                "<div class='col-md-12'>"+
                                    "<div class='row'>"+
                                        "<label class='col-md-4'>Asset Name</label>"+
                                        "<label class='col-md-3'>Asset Code</label>"+
                                        "<label class='col-md-3'>Priority</label>"+
                                        "<label class='col-md-2'>Status</label>"+
                                    "</div>"+
                                "</div>"+
                            "</div>");
        $.ajax({
            type:'POST',
            data:{_token:token,exchange_id:exchangeId},
            dataType:"json",
            url : requestUrl+"/getExchangeAsset",
            async: false,
            error:function(xhr,status,error){
                console.log(error);
            },
            success:function(assets,status,xhr){
                var assetArray = [];
                $.each(assets, function(i,value) {
                    var assetId = value['asset_id'];
                    var assetName = value['asset_name'];
                    var assetCode = value['new_asset_code'];
                    var assetPriority = value['priority'];
                    var assetStatus = value['status'];

                    assetArray.push(assetId);
                    setDefaultRow(assetId,assetName,assetCode,assetPriority,assetStatus);
                });
                $("#asset_id").val(assetArray).change();
            }
        });

    });

});