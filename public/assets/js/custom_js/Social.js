$(document).ready(function() {

	$(".wallet-box").css('width','80%');
    $(".k-plus").css('padding','8px');
    $(".login").css('margin-top','5px');
    $(".manual,.login").hide();

	var token = $('input[name="_token"]').val();
	var requestUrl = $("#requestUrl").val();
	var selectedPullAccountId = 0;
	var selectedPullWalletId = 0;

	$("#tabstrip").kendoTabStrip().data("kendoTabStrip");

	/*SOCIAL TAB CODEING PART*/

	var socialValidator = $("#socialForm").kendoValidator({
        // code for validate
    }).data("kendoValidator");

    $('#socialForm').on('submit',function(){
		 if (socialValidator.validate()) {
			$.ajax({
				type: 'POST',
				data:$('#socialForm').serialize(),
				url: requestUrl+"/createSocial",
				success: function (eventData) {

					$('#socialForm').trigger("reset");	
					$("#socialGrid").data("kendoGrid").dataSource.read();	
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
		        var $toast = toastr["success"]("", "Social Information Inserted.");		
				}
			});
			return false;
		}
	});

	var socialGrid = jQuery("#socialGrid").kendoGrid({
		dataSource: {
			serverPaging: true,
			pageSize: 20,
			transport : {
                read : {
                    data:{_token:token},
                    url: requestUrl+"/getSocials",
                    dataType: "json",
                    type: "POST"
                }
            },
			schema: {
				total:'total',
				data:'socials',
				model: {
					social_id:'social_id',
					fields: {
						priority: {
							type: "number",
							validation: { required: true,min: 0,max:99 }
						},
						enable: {
							defaultValue: { value: 1, text: "Enable"}
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
		serverFiltering: false,
		groupable: true,
		resizable: true,
		editable: true,
		selectable:'row',
		save:onSocialSave,
		edit: function(e){
			var columnIndex = [0,1];
			if($.inArray(parseInt(e.container.index()),columnIndex) != -1){
				this.closeCell(); 
			}
		},
		columns: [
		{
			field: "social_id",
			title: "Social Id",
		}, {
			field: "social_code",
			title: "Social Code",
		}, {
			field: "social_name",
			title: "Social Name",
		}],
	});

	function onSocialSave(data) {

		var social_id = data.model.social_id;
		var social_name = data.values.social_name;

		$.ajax({
			type: 'POST',
			data:{social_id:social_id,social_name:social_name,_token:token},
			url: requestUrl+"/updateSocial",
			success: function (eventData) {
				$("#socialGrid").data("kendoGrid").dataSource.read();
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
		        var $toast = toastr["success"]("", "Social Information Updated.");
			}
		});
	}

	/*SOCIAL API KEYS TAB CODEING PART*/

	$("#wallet_id").kendoComboBox({
        placeholder: "Select Wallet",
        dataTextField: "wallet_name",
        dataValueField: "wallet_id",
        filter: "contains",
        height: 400,
        
    }).data("kendoComboBox");

	$("#account_id").kendoComboBox({
        placeholder: "Select Account",
        dataTextField: "account_code_long",
        dataValueField: "account_id",
        filter: "contains",
        height: 400,
        change: function (e) {

            if($('#account_id').val() != ""){

            	var account_id = $('#account_id').val();
                var wallet_id = $("#wallet_id").data("kendoComboBox");
                

                /*GET PEOPLE ACCOUNT WALLET NAMES*/

                var walletDataSource = new kendo.data.DataSource({
                    transport : {   
                        read : {
                        		data:{_token:token,account_id:account_id},
                                url: requestUrl+"/getWallets",
                                dataType: "json",
                                type: "POST",
                            }
                        }
                    });

                wallet_id.setDataSource(walletDataSource);
                if(selectedPullWalletId){
                    wallet_id.value(selectedPullWalletId);  
                }else{
                    wallet_id.value("");
                }
                wallet_id.enable(true);
                $(".addwallet").show();

            }else{
                selectedPullWalletId = 0;
                $(".addwallet").hide();
            }
        }
        
    }).data("kendoComboBox");


	$("#person_id").kendoComboBox({
        placeholder: "Select Identity",
        dataTextField: "person_name",
        dataValueField: "person_id",
        filter: "contains",
        height: 400,
        dataSource: {
            transport : {   
                read : {
                	data:{_token:token},
                    dataType : "json",
                    url : requestUrl+"/getPersons",
                    type : "POST",
                }
            }
        },
        change: function (e) {

            if($('#person_id').val() != ""){

            	var person_id = $('#person_id').val();
                var account_id = $("#account_id").data("kendoComboBox");
                var socialApiKeysGrid = $("#socialApiKeysGrid").data("kendoGrid");

                /*GET PEOPLE ACCOUNT NAMES*/

                var accountDataSource = new kendo.data.DataSource({
                    transport : {   
                        read : {
                        		data:{_token:token,person_id:person_id},
                                url: requestUrl+"/getAccounts",
                                dataType: "json",
                                type: "POST",
                            }
                        }
                    });


				var socialApiKeysDataSource = new kendo.data.DataSource({
				serverPaging: true,	
				pageSize: 20,
				transport : {   
					read : {
						data:{_token:token,person_id:person_id},
							url: requestUrl+"/getFilterSocialApiKeys",
							dataType: "json",
							type: "POST"
						}
				},
				schema: {
					total:'total',
					data:'socialApiKeys',
					model: {
						keys_id:'keys_id',
						fields: {
							priority: {
								type: "number",
								validation: { required: true,min: 0,max:99 }
							},
							nonce: {
								defaultValue: { value: 1, text: "Enable"}
							}
						}
					},
				},
				});
				
				socialApiKeysGrid.setDataSource(socialApiKeysDataSource);

                account_id.setDataSource(accountDataSource);
                if(selectedPullAccountId){
                    account_id.value(selectedPullAccountId);  
                }else{
                    account_id.value("");
                }
                account_id.enable(true);
                $(".addwallet").hide();
            }else{
                selectedPullAccountId = 0;
                $(".addwallet").hide();
            }
        }

    }).data("kendoComboBox");

    $("#social_id").kendoComboBox({
        placeholder: "Select Social",
        dataTextField: "social_name",
        dataValueField: "social_id",
        filter: "contains",
        height: 400,
        dataSource: {
            transport : {   
                read : {
                	data:{_token:token},
                    dataType : "json",
                    url : requestUrl+"/getSocials",
                    type : "POST",
                }
            },
            schema: {
				total:'total',
				data:'socials',
				model: {
					social_id:'social_id'
				},
			},
        },
        change: function (e) {
        	var social_id = $("#social_id").val();

        	if(social_id == null){
        		$(".manual").hide();
        		$(".login").hide();

        	}else if(social_id == 0){
        		$(".manual").show();
        		$(".login").hide();

        	}else{
        		$(".manual").hide();
        		$(".login").show();
        	}
        }
        
    }).data("kendoComboBox");

	var socialApiKeysValidator = $("#socialApiKeysForm").kendoValidator({
        
        rules: {
            hasAccount: function (input) {
                if(input.is("[id=account_id]")){
                    var ms = input.data("kendoComboBox");       
                    if(ms.value().length === 0){
                        return false;
                    }
                } 
                return true;
            },
            hasSocial: function (input) {
                if(input.is("[id=social_id]")){
                    var ms = input.data("kendoComboBox");       
                    if(ms.value().length === 0){
                        return false;
                    }
                } 
                return true;
            }
        },
        messages: { 
            hasAccount: "Account Required",
            hasSocial: "Social Required"
        }
    }).data("kendoValidator");

	$('#socialApiKeysForm').on('submit',function(){
		 if (socialApiKeysValidator.validate()) {
			$.ajax({
				type: 'POST',
				data:$('#socialApiKeysForm').serialize(),
				url: requestUrl+"/createSocialApiKeys",
				success: function (eventData) {

					$('#socialApiKeysForm').trigger("reset");	
					$("#socialApiKeysGrid").data("kendoGrid").dataSource.read();	
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
		        var $toast = toastr["success"]("", "Social Api Information Inserted.");		
				}
			});
			return false;
		}
	});

	var socialApiKeysGrid = jQuery("#socialApiKeysGrid").kendoGrid({
		dataSource: {
			serverPaging: true,
			pageSize: 20,				
			transport : {
                read : {
                    data:{_token:token},
                    url: requestUrl+"/getSocialApiKeys",
                    dataType: "json",
                    type: "POST"
                }
            },
			schema: {
				model: {
					keys_id:'keys_id',
					fields: {
						priority: {
							type: "number",
							validation: { required: true,min: 0,max:99 }
						},
						nonce: {
							defaultValue: { value: 1, text: "Enable"}
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
		edit: function(e){
			var columnIndex = [0,1,2,3];
			if($.inArray(parseInt(e.container.index()),columnIndex) != -1){
				this.closeCell(); 
			}
		},
		scrollable: true,
		autoSync: true,
		sortable: true,
		reorderable: true,
		serverFiltering: true,
		groupable: true,
		resizable: true,
		editable: true,
		selectable:'row',
		save:onSocialApiKeysSave,		
		columns: [
		{ 
			command: { text: "Delete", click: deleteSocialApiKeys },
			title: " ", 
			width: "80px"
		},{
			field: "people_name",
			title: "Person Name",
		},{
			field: "account_code_long",
			title: "Account",
		},{
			field: "wallet_name",
			title: "Wallet",
		},{
			field: "social_name",
			title: "Social Name",
			editor: socialDropDownEditor,
			template: "#=data.social_name#",
		},{
			field: "apikey_name",
			title: "Api Key Name",
		}, {
			field: "connector_key",
			title: "Connector Key",
		}, {
			field: "connector_passcode",
			title: "Connector Passcode",
		}, {
			field: "consumer_key",
			title: "Consumer Key",
		}, {
			field: "consumer_secret",
			title: "Consumer Secret",
		}, {
			field: "access_token",
			title: "Access Token",
		}, {
			field: "access_secret",
			title: "Access Secret",
		}, {
			field: "nonce",
			title: "Status",
			editor: statusDropDownEditor,
			template: "#=(data.nonce)?'Enable':'Disable'#",
			width: 100
		}],
	});

	function deleteSocialApiKeys(e) {
		e.preventDefault();		
		kendo.confirm("Are you sure that you want to proceed?").then(function () {            

			var socialApiKeysGridData = $("#socialApiKeysGrid").data("kendoGrid");
            var dataItem = socialApiKeysGridData.dataItem($(e.currentTarget).closest("tr"));
			keys_id = dataItem.keys_id;

			$.ajax({
					type: 'POST',
					data: {_token:token,keys_id:keys_id},
					url: requestUrl+"/deleteSocialApiKeys",
					success: function (eventData) {

						$('#socialApiKeysForm').trigger("reset");	
						$("#socialApiKeysGrid").data("kendoGrid").dataSource.read();	
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
			        var $toast = toastr["success"]("", "Social Api Information Deleted.");		
					}
			});
        });

        setTimeout(function(){
        	$(".k-window-title").text("Confirmation");
        },20);
		
		return false;		
	}

	function statusDropDownEditor(container, options) {
		console.log(options);
		var data = [
					{ Description: "Enable", ID: "1" },
					{ Description: "Disable", ID: "0" }
				];
			$('<input data-text-field="Description" data-value-field="ID" data-bind="value:' + options.field + '"/>')
				.appendTo(container)
				.kendoDropDownList ({
					dataSource: data,
					dataTextField: "Description",
					dataValueField:"ID"
			 });
	};

	function socialDropDownEditor(container, options) {
		$('<input data-text-field="social_name" data-value-field="social_id" data-bind="value:' + options.field + '"/>')
			.appendTo(container)
			.kendoDropDownList({
				dataSource: {
					transport : {   
						read : {
							data:{_token:token},
							dataType : "json",
							url : requestUrl+"/getSocials",
							type : "POST"
						}
					}
				},
				dataTextField: "social_name",
				dataValueField: "social_id"
			});
	}

	function onSocialApiKeysSave(data) {

		var keys_id = data.model.keys_id;
		var key="";var value=0;

		if(data.values.social_name){
			key = "social_id";
			value = data.values.social_name;
		}else if(data.values.apikey_name){
			key = "apikey_name";
			value = data.values.apikey_name;
		}else if(data.values.connector_key){
			key = "connector_key";
			value = data.values.connector_key;
		}else if(data.values.connector_passcode){
			key = "connector_passcode";
			value = data.values.connector_passcode;
		}else if(data.values.consumer_key){
			key = "consumer_key";
			value = data.values.consumer_key;
		}else if(data.values.consumer_secret){
			key = "consumer_secret";
			value = data.values.consumer_secret;
		}else if(data.values.access_token){
			key = "access_token";
			value = data.values.access_token;
		}else if(data.values.access_secret){
			key = "access_secret";
			value = data.values.access_secret;
		}else if(data.values.nonce){
			key = "nonce";
			value = data.values.nonce;
		}else{
			return false;
		}

		$.ajax({
			type: 'POST',
			data:{keys_id:keys_id,key:key,value:value,_token:token},
			url: requestUrl+"/updateSocialApiKeys",
			success: function (eventData) {
				$("#socialApiKeysGrid").data("kendoGrid").dataSource.read();
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
		        var $toast = toastr["success"]("", "Social Api Information Updated.");
			}
		});
	}

	$('.addwallet').on('click', function(e){
		
		$('.content .preloader').show();
		$('.content .preloader img').show();

		var account_id = $('#account_id').val();;

		var cKendoGridWallet = $("#AccountWalletListGrid").data("kendoGrid");
		var accountWalletListDataSource = new kendo.data.DataSource({
				pageSize: 20,
				serverPaging: true,
				transport : {   
					read : {
							data:{_token:token,account_id:account_id},
							url: requestUrl+"/getAccountWallets",
							dataType: "json",
							type: "POST"
						}
				},
				schema: {
					total:'total',
					data:'account_wallet',
					model: {
						account_id:'list_id',
						fields: {
							priority: {
								type: "number",
								validation: { required: true,min: 0,max:99 }
							},
							status: {
								defaultValue: { value: 1, text: "Enable"}
							}
						}
					},
				}	
		});

		cKendoGridWallet.setDataSource(accountWalletListDataSource);

		$('#walletForm').trigger('reset');

		$('#wallet_account_id').val(account_id);
		$('.modal-title').text("Account : ");
		$('.content .preloader').hide();
		$('.content .preloader img').hide();
		$('#top_modal').modal("show");
	});

	$("#top_modal").on("hidden.bs.modal", function () {
		
		var dropdownlist = $("#account_id").data("kendoComboBox"); 
		dropdownlist.trigger("change");
	});


});