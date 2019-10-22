"use strict";

$(document).ready(function() {

    var requestUrl = $('#requestUrl').val();
    var _token = $('#_token').val();

    $.ajax({
        type : 'POST',
        url : requestUrl+"/getLocationJSON",
        data : {_token : _token},
        dataType : 'json',
        success:function(json){
            initialize(json);
        }
    });
    
    function initialize(jsonData){
        /*console.log(jsonData[0]);
        console.log(jsonData[0].location_lat);*/
        var map5 = new GMaps({
            div: "#gmap-styled",
            lat: jsonData[0].location_lat,
            lng: jsonData[0].location_lng,
            zoom: 10,
            zoomControl: true,
            zoomControlOpt: {
                style: "SMALL",
                position: "TOP_LEFT"
            },
            panControl: true,
            streetViewControl: false,
            mapTypeControl: false,
            overviewMapControl: false
        });

        var styles = [
            { stylers: [ { hue: "#00ffe6" },{ saturation: -20 }] },
            {
                featureType: "road",
                elementType: "geometry",
                stylers: [
                    { lightness: 100 },
                    { visibility: "simplified" }
                ]
            }, 
            {
                featureType: "road",
                elementType: "labels",
                stylers: [
                    { visibility: "off" }
                ]
            }
        ];

        map5.addStyle({
            styles: styles,
            mapTypeId: "maps_style"
        });

        map5.setStyle("maps_style");

        $.each(jsonData, function(i,value) {

        var address = "";

        if(value['postal_subpremise']!=""){
            address = address+value['postal_subpremise']+" ";
        }

        if(value['postal_subpremise']!=""){
            address = address+value['postal_premise'];
        }

        if(address !=""){
            address = address+"<br>";
        }else{
            if(value['postal_street_number']!=""){
                address = address + value['postal_street_number']+" ";
            }

            if(value['postal_route']!=""){
                address = address + value['postal_route'];
            }

            address = address + "<br>";
            
        }

        address = address + value['city_name']+", "+value['county_name'];


           map5.addMarker({
                lat: value['location_lat'],
                lng: value['location_lng'],
                title: value['merchant_name'],
                infoWindow: {
                    content: String(value['merchant_name'])+"<br>"+String(address)
                }
            });
        });
    }
});
