$(document).ready(function() {
    var requestUrl = $('#requestUrl').val();
    var imageUrl = $('#imageUrl').val();
    var token = $('input[name="_token"]').val();
    var portalSocialApiGrid = jQuery("#portalSocialApiGrid").kendoGrid({
        dataSource: {
            serverPaging: true,
            pageSize: 20,
            transport: {
                read: {
                    data: {
                        _token: token
                    },
                    url: requestUrl + "/getPortalSocialConnector",
                    dataType: "json",
                    type: "POST"
                }
            },
            schema: {
                data: 'portal_social_connector',
                total: 'total',
                model: {
                    id: 'connectorid',
                    fields: {
                        connectorimage: {
                            editable: false
                        },
                        connectorname: {
                            editable: false
                        }
                    }
                },
            },
            serverFiltering: true,
        },
        pageable: {
            refresh: true,
            pageSizes: true
        },
        scrollable: true,
        autoSync: true,
        sortable: true,
        reorderable: true,
        serverFiltering: true,
        groupable: true,
        resizable: true,
        editable: true,
        save:onConnectorSave,
        detailTemplate: '<div class="apikeygrid"></div>',
        detailInit: detailInit,
        dataBound: function(eventData){
            var data = this.dataSource.data();
            $.each(data, function(i, row) {
               if (row.api_active === 'yes'){
                $('tr[data-uid="' + row.uid + '"]').css("background-color", "green")
               }
            });
        },
        columns: [{
            field: "connectorimage",
            title: "Flag",
            template: "<img src='" + imageUrl + "/#=data.connectorimage#' width=30 height=30/>",
            width: 40,
        }, {
            field: "connectorname",
            title: "Connector Name",
        }, {
            field: "api_active",
            title: "Active Status",
            editor: statusDropDownEditor,
            template: "#=(data.api_active == 'yes')?'Show':'Hide' #"
        }, {
            field: "priority",
            title: "Priority",
        }]
    });

    function statusDropDownEditor(container, options) {
        console.log(options);
        var data = [
                    { Description: "Show", ID: "yes" },
                    { Description: "Hide", ID: "no" }
                ];
            $('<input data-text-field="Description" data-value-field="ID" data-bind="value:' + options.field + '"/>')
                .appendTo(container)
                .kendoDropDownList ({
                    dataSource: data,
                    dataTextField: "Description",
                    dataValueField:"ID"
             });
    };

    function onConnectorSave(data) {

        var id = data.model.id;
        var key="";var value=0;var message="";var response="";

        if(data.values.api_active){
            key = "api_active";
            value = data.values.api_active;
        }else if(data.values.priority){
            key = "priority";
            value = data.values.priority;
        }

        $.ajax({
            type: 'POST',
            data:{id:id,key:key,value:value,_token:token},
            url: requestUrl+"/updatePortalSocialConnector",
            success: function (eventData) {

                data.sender.dataSource.read();
                response = eventData.type;

                if(response.localeCompare("success") == 0){
                    message = "Connector Information Updated";
                }else if (response.localeCompare("error") == 0){
                    message = eventData.message;
                }
                toastr.options = {
                    "closeButton": true,
                    "positionClass": "toast-top-right",
                    "showDuration": "1000",
                    "hideDuration": "1000",
                    "timeOut": "5000",
                    "extendedTimeOut": "1000",
                    "showEasing": "swing",
                    "hideEasing": "swing",
                    "showMethod": "show"
                };
                var $toast = toastr[response]("", message);
            }
        });
    }

    function detailInit(portalApiDetails) {
        var ApiKeysGrid = portalApiDetails.detailRow.find(".apikeygrid").kendoGrid({
            dataSource: {
                pageSize: 5,
                transport: {
                    read: {
                        url: requestUrl + "/getPortalSocialApi" + "/" + portalApiDetails.data.connectorid,
                        type: "GET",
                        dataType: "json",
                    }
                },
                schema: {
                    data: 'portal_social_api',
                    total: 'total',
                    model: {
                        id: 'id',
                        fields: {
                            hostname: {
                                editable: false
                            },
                            type_id: {
                                editable: false
                            }
                        }
                    },
                },
            },
            pageable: {
                refresh: true,
                pageSizes: true
            },
            autoSync: true,
            reorderable: true,            
            groupable: true,
            resizable: true,
            editable: 'incell',
            scrollable: true,
            sortable: true,
            save:onApiKeySave,
            columns: [{
                field: "hostname",
                title: "Environment",
            }, {
                field: "api_key",
                title: "Api Key",
            }, {
                field: "api_secret_key",
                title: "Api Secret Key",
                template: "********",
                editor: function (container, options) {
                        $('<input data-text-field="' + options.field + '" ' +
                                'class="k-input k-textbox" ' +
                                'type="password" ' +
                                'data-value-field="' + options.field + '" ' +
                                'data-bind="value:' + options.field + '"/>')
                                .appendTo(container)
                        }
            }, {
                field: "type_id",
                title: "Type Name",                
                template: "#=(data.type_id == 1)?'Login':'Connector'#",
            }]
        }).data("kendoGrid");      
    }

    function onApiKeySave(data) {

        var id = data.model.id;
        var key="";var value=0;var message="";var response="";

        if(data.values.api_key){
            key = "api_key";
            value = data.values.api_key;
        }else if(data.values.api_secret_key){
            key = "api_secret_key";
            value = data.values.api_secret_key;
        }

        $.ajax({
            type: 'POST',
            data:{id:id,key:key,value:value,_token:token},
            url: requestUrl+"/updatePortalSocialApi",
            success: function (eventData) {
                data.sender.dataSource.read();
                response = eventData.type;
                
                if(response.localeCompare("success") == 0){
                    message = "Api Keys Information Updated";
                }else if (response.localeCompare("error") == 0){
                    message = eventData.message;
                }
                toastr.options = {
                    "closeButton": true,
                    "positionClass": "toast-top-right",
                    "showDuration": "1000",
                    "hideDuration": "1000",
                    "timeOut": "5000",
                    "extendedTimeOut": "1000",
                    "showEasing": "swing",
                    "hideEasing": "swing",
                    "showMethod": "show"
                };
                var $toast = toastr[response]("", message);
            }
        });
    }
});