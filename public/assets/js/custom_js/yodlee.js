$(document).ready(function() {
	var yodleeConstant = {
		GET_YODEE_ACCOUNT_METHOD : "/getYodleeAccount"
	}
	var token = $('input[name="_token"]').val();
    var requestUrl = $("#requestUrl").val();
    function isEmpty(obj) {
	    for(var key in obj) {
	        if(obj.hasOwnProperty(key))
	            return false;
	    }
	    return true;
	}
	$("#syncAccountTransaction").click(function(event) {
		event.preventDefault();
		$('.content .preloader').show();
		$('.content .preloader img').show();
		$.ajax({
			type: "POST",
			url: requestUrl+"/getUserYodleeAccount",
			data:{_token:token},
			error:function(xhr,status,error) {
                $("#yodleeDetailsError span").text("Error Getting Account");
				$("#yodleeDetailsError").css("display","block");
				$('.content .preloader').hide();
				$('.content .preloader img').hide();
            },
			success: function(getYodleeAccountResponse,status,xhr)
			{
				var getYodleeAccountResponse = jQuery.parseJSON(getYodleeAccountResponse);
				var commaSeparatedAccountId = [];
				jQuery.each(getYodleeAccountResponse, function(accountKey, accountValue) {
					commaSeparatedAccountId.push(accountValue.id);
			    });
			    
			    var transactionsUrl = 'https://developer.api.yodlee.com/ysl/transactions?accountId='+commaSeparatedAccountId.join()+'&fromDate=2016-01-01&toDate=2018-03-31';
			    var cobrandSessionValue = $("#cob_session").val();
				var userSessionValue = $("#user_session").val();
				var accessTokenAuthorization = "{cobSession="+cobrandSessionValue+",userSession="+userSessionValue+"}";
			    $.ajax({
					type: "GET",
					url: transactionsUrl,
					beforeSend: function(xhr){
						xhr.setRequestHeader('authorization', accessTokenAuthorization);
						xhr.setRequestHeader('Cobrand-Name', 'restserver');
						xhr.setRequestHeader('Api-Version', '1.1');
					},
					contentType: 'application/json;charset=utf-8',
					success: function(transactionsDetails)
					{
						if(!isEmpty(transactionsDetails))
						{
							var  insertTransactions = transactionsDetails.transaction;
							$.ajax({
								type: "POST",
								url: requestUrl+"/saveYodleeTransactions",
								data:{_token:token,insertTransactions:insertTransactions},
								error:function(xhr,status,error) {
				                    $("#yodleeDetailsError span").text("Error During Data Saved");
									$("#yodleeDetailsError").css("display","block");
									$('.content .preloader').hide();
				    				$('.content .preloader img').hide();
				                },
								success: function(saveYodleeAccountResponse,status,xhr)
								{
									$('.content .preloader').hide();
									$('.content .preloader img').hide();
									$("#yodleeAccountGrid").data("kendoGrid").dataSource.read();
								}
							});
						}

					},
					error: function(XMLHttpRequest, textStatus, errorThrown) { 
						$("#yodleeDetailsError span").text(XMLHttpRequest.responseJSON.errorMessage);
						$("#yodleeDetailsError").css("display","block");
						$('.content .preloader').hide();
						$('.content .preloader img').hide();
				    }
				});
			}
		});
	});
    $("#syncYodleeAccount").click(function(event) {
    	event.preventDefault();
    	$('.content .preloader').show();
        $('.content .preloader img').show();
    	var getAccountUrl = "https://developer.api.yodlee.com/ysl/accounts?container=bank";
    	var cobrandSessionValue = $("#cob_session").val();
		var userSessionValue = $("#user_session").val();
		var accessToken = $("#access_token").val();
		var appId = $("#app_id").val();

		var accessTokenAuthorization = "{cobSession="+cobrandSessionValue+",userSession="+userSessionValue+"}";

		$.ajax({
			type: "GET",
			url: getAccountUrl,
			beforeSend: function(xhr){
				xhr.setRequestHeader('authorization', accessTokenAuthorization);
				xhr.setRequestHeader('Cobrand-Name', 'restserver');
				xhr.setRequestHeader('Api-Version', '1.1');
			},
			contentType: 'application/json;charset=utf-8',
			success: function(AccountDetails)
			{
				if(!isEmpty(AccountDetails))
				{
					var  insertAccounts = AccountDetails.account;
					$.ajax({
						type: "POST",
						url: requestUrl+"/saveYodleeAccount",
						data:{_token:token,insertAccounts:insertAccounts},
						error:function(xhr,status,error) {
		                    $("#yodleeDetailsError span").text("Error During Data Saved");
							$("#yodleeDetailsError").css("display","block");
							$('.content .preloader').hide();
		    				$('.content .preloader img').hide();
		                },
						success: function(saveYodleeAccountResponse,status,xhr)
						{
							$('.content .preloader').hide();
							$('.content .preloader img').hide();
							$("#yodleeAccountGrid").data("kendoGrid").dataSource.read();
						}
					});
				}
			},
			error: function(XMLHttpRequest, textStatus, errorThrown) { 
				$("#yodleeDetailsError span").text(XMLHttpRequest.responseJSON.errorMessage);
				$("#yodleeDetailsError").css("display","block");
				$('.content .preloader').hide();
				$('.content .preloader img').hide();
		    }
		});
    });
	$("#loginWithYodlee").click(function(event) {
		event.preventDefault();
		$('#loginWithYodleeModel').modal('show');
	});

	$("#loginWithYodleeFastLink").click(function(event) {
		event.preventDefault();
		$('#loginWithFastLinkYodleeModel').modal('show');
	});
	$("#yodleeLoginForm").submit(function(event) {
		$('.loginPreloader').show();
        $('.loginPreloader img').show();
		/* stop form from submitting normally */
		event.preventDefault();
		var cobrandUserName = $("#yodleeCobrandUsername").val();
		var cobrandPassword = $("#yodleeCobrandPassword").val();
		var yodleeUserName = $("#yodleeUsername").val();
		var yodleePassword = $("#yodleeUserPassword").val();

		/*var coBrandQueryParameter = '{ "cobrand" : '+'{ "locale":"en_US" , "cobrandLogin":"sbCobd423903612fe16e55cdb0f184bf191af7zZ" , "cobrandPassword":"a514d4ff-8f73-4fc1-994c-b4fd5d86d763" }}';

		var userQueryParameter = '{ "user" : '+'{ "locale":"en_US" , "loginName":"sbMemd423903612fe16e55cdb0f184bf191af7zZ1", "password":"sbMemd423903612fe16e55cdb0f184bf191af7zZ1#123" }}';*/

		var coBrandQueryParameter = '{ "cobrand" : '+'{ "locale":"en_US" , "cobrandLogin":"'+cobrandUserName+'" , "cobrandPassword":"'+cobrandPassword+'" }}';

		var userQueryParameter = '{ "user" : '+'{ "locale":"en_US" , "loginName":"'+yodleeUserName+'", "password":"'+yodleePassword+'" }}';

		var coBrandurl = "https://developer.api.yodlee.com/ysl/cobrand/login";
		var userurl = "https://developer.api.yodlee.com/ysl/user/login";
		var userTokenurl = "https://developer.api.yodlee.com/ysl/user/accessTokens?appIds=10003600";

		$.ajax({
			type: "POST",
			url: coBrandurl,
			contentType: 'application/json; charset=utf-8',
			beforeSend: function(xhr){
				xhr.setRequestHeader('Cobrand-Name', 'restserver');
				xhr.setRequestHeader('Api-Version', '1.1');
			},
			dataType : "json",
			data: coBrandQueryParameter,
			success: function(cobrandLoginResponse)
			{
				var cobrandSessionValue = cobrandLoginResponse.session.cobSession;
				var cobrandApplicationId = cobrandLoginResponse.applicationId;
				var cobrandId = cobrandLoginResponse.cobrandId;
				var cobrandLocale = cobrandLoginResponse.locale;
			    var coBrandSession = "{cobSession="+cobrandSessionValue+"}"; // show response from the php script.
			    $.ajax({
					type: "POST",
					url: userurl,
					beforeSend: function(xhr){
						xhr.setRequestHeader('authorization', coBrandSession);
						xhr.setRequestHeader('Cobrand-Name', 'restserver');
						xhr.setRequestHeader('Api-Version', '1.1');
					},
					contentType: 'application/json;charset=utf-8',
					data:userQueryParameter,
					success: function(userLoginResponse)
					{
						var userSessionValue = userLoginResponse.user.session.userSession;
						var userId = userLoginResponse.user.id;
						var loginName = userLoginResponse.user.loginName;
						var firstName = userLoginResponse.user.name.first;
						var lastName = userLoginResponse.user.name.last;
						var currency = userLoginResponse.user.preferences.currency;
						var userLocale = userLoginResponse.user.preferences.locale;
						var roleType = userLoginResponse.user.roleType;
						var accessTokenAuthorization = "{cobSession="+cobrandSessionValue+",userSession="+userSessionValue+"}";
						$.ajax({
							type: "GET",
							url: userTokenurl,
							beforeSend: function(xhr){
								xhr.setRequestHeader('authorization', accessTokenAuthorization);
								xhr.setRequestHeader('Cobrand-Name', 'restserver');
								xhr.setRequestHeader('Api-Version', '1.1');
							},
							contentType: 'application/json;charset=utf-8',
							success: function(userAccessToken)
							{
								var userAppId = userAccessToken.user.accessTokens[0].appId;
								var applicationLaunchUrl = userAccessToken.user.accessTokens[0].url;
								var appAccessToken = userAccessToken.user.accessTokens[0].value;
								$.ajax({
									type: "POST",
									url: requestUrl+"/saveYadleeSession",
									data:{_token:token,co_brand_session:cobrandSessionValue,co_brand_applicationId:cobrandApplicationId,co_brand_id:cobrandId,co_brand_locale:cobrandLocale,user_session_value:userSessionValue,user_id:userId,login_name:loginName,first_name:firstName,last_name:lastName,currency:currency,user_locale:userLocale,role_type:roleType,user_app_id:userAppId,application_launch_url:applicationLaunchUrl,app_access_token:appAccessToken},
									error:function(xhr,status,error) {
		                                $("#invalidLogin span").text("Error During Data Saved");
										$("#invalidLogin").css("display","block");
										$('.loginPreloader').hide();
        								$('.loginPreloader img').hide();
		                            },
									success: function(saveYadleeSessionResponse,status,xhr)
									{
		        						location.reload();
									}
								});
							},
							error: function(XMLHttpRequest, textStatus, errorThrown) { 
								$("#invalidLogin span").text(XMLHttpRequest.responseJSON.errorMessage);
								$("#invalidLogin").css("display","block");
								$('.loginPreloader').hide();
								$('.loginPreloader img').hide();
						    }
						});						
					},
					error: function(XMLHttpRequest, textStatus, errorThrown) { 
						$("#invalidLogin span").text(XMLHttpRequest.responseJSON.errorMessage);
						$("#invalidLogin").css("display","block");
						$('.loginPreloader').hide();
						$('.loginPreloader img').hide();
				    }
				});
			},
			error: function(XMLHttpRequest, textStatus, errorThrown) { 
				$("#invalidLogin span").text(XMLHttpRequest.responseJSON.errorMessage);
				$("#invalidLogin").css("display","block");
				$('.loginPreloader').hide();
				$('.loginPreloader img').hide();
		    } 
		});
		return false;
    });

	var yodleeAccountGrid = jQuery("#yodleeAccountGrid").kendoGrid({
		dataSource: {
			serverPaging: true,
			pageSize: 20,
			transport : {
				read : {
					data:{_token:token},
					url: requestUrl+yodleeConstant.GET_YODEE_ACCOUNT_METHOD,
					dataType: "json",
					type: "POST"
				}
			},
			schema: {
				total:'total',
				data:'yodlee_account',
				model: {
					account_id:'account_id',
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
		editable: false,
		detailTemplate: kendo.template($("#transactionDetailsTemplate").html()),
		detailInit: detailInit,
		dataBound : function(e) {
			var dataSource = this.dataSource;
            this.element.find('tr.k-master-row').each(function() {
                var row = $(this);
                var data = dataSource.getByUid(row.data('uid'));
                if (data.totalTransaction == 0) {
                    row.find('.k-hierarchy-cell a').remove();
                }
            });
		},
		selectable:'row',
		columns: [
		{
			field: "id",
			title: "Account",
		},{
			field: "staff_id",
			title: "Staff Id",
			hidden: true,
		},{
			field: "staff_fname",
			title: "User",
			width:100
		},{
			field: "account_name",
			title: "account Name",
			width:100
		},{
			field: "current_balance_amount",
			title: "Current Balance",
			width:100
		},{
			field: "current_balance_currency",
			title: "Currency",
			width:100
		},{
			field: "account_type",
			title: "Account Type",
			width:100
		},{
			field: "account_status",
			title: "Account Status",
			width:100
		},{
			field: "provider_name",
			title: "Provider",
			width:100
		}
		],
	});

	function detailInit(e) {
        var detailRow = e.detailRow;
        detailRow.find(".transactionDetails").kendoGrid({
            dataSource: {
                transport: {
                    read: {
                        url: requestUrl+"/getAccountTransaction",
                        data: {
			                accountId: e.data.id,
			                staffId: e.data.staff_id,
			                _token:token
			            },
                        dataType: "json",
                        type: "POST",
                    }
                },
                pageSize: 5,
                serverFiltering: true,
            },
            pageable: {
                refresh: true,
                pageSizes: true
            },
            scrollable: false,
            sortable: true,
            pageable: true,
            columns: [
                { field: "id", title: "Transactions"},
                { field: "container", title: "Container"},
                { field: "transaction_amount", title: "Amount"},
                { field: "transaction_currency", title: "Currency"},
                { field: "base_type", title: "Type"},
                { field: "category", title: "Category"},
                { field: "transaction_date", title: "Transaction Date"},
                { field: "check_number", title: "Check Number"},
            ]
        });
    }
});
