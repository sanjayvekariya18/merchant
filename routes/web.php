<?php
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/
use App\Http\Traits\PermissionTrait;
use Illuminate\Support\Facades\Request as Requests;

Auth::routes();

Route::get('/2fa','PasswordSecurityController@show2faForm');
Route::post('/generate2faSecret','PasswordSecurityController@generate2faSecret')->name('generate2faSecret');
Route::post('/2fa','PasswordSecurityController@enable2fa')->name('enable2fa');
Route::post('/disable2fa','PasswordSecurityController@disable2fa')->name('disable2fa');
Route::get('/re-authenticate','PasswordSecurityController@reauthenticate');

Route::post('/2faVerify', function () {
    return redirect('home');
})->name('2faVerify')->middleware('2fa');

Route::get('/register', function() {
  Auth::logout();
  return view('login');
});


Route::get('/login', function() {

  $user = Auth::user();
  if($user){
    return redirect('index');
  }else{
    Auth::logout();
    return view('login');
  }
});


Route::get('logout', 'CorePlusController@logout');

Route::group(['middleware'=> 'web','middleware' => 'prevent-back-history'],function() {
  Route::get('/facebook/{type}','Facebook_connectorController@index');
  Route::get('/facebook_social/connect','Facebook_connectorController@socialConnect');
  Route::get('/facebook_social/disconnect','Facebook_connectorController@socialDisconnect');
  Route::get('/facebook_login/connect', 'Facebook_connectorController@loginConnect');
});

Route::group(['middleware'=> 'web','middleware' => 'prevent-back-history'],function() {
  Route::get('/twitter/{type}','Twitter_connectorController@index');
  Route::get('/twitter_social/connect','Twitter_connectorController@socialConnect');
  Route::get('/twitter_social/disconnect','Twitter_connectorController@socialDisconnect');
  Route::get('/twitter_login/connect', 'Twitter_connectorController@loginConnect');
});

Route::group(['middleware'=> 'web','middleware' => 'prevent-back-history'],function() {
  Route::get('/linkedin/{type}','Linkedin_connectorController@index');
  Route::get('/linkedin_social/connect','Linkedin_connectorController@socialConnect');
  Route::get('/linkedin_social/disconnect','Linkedin_connectorController@socialDisconnect');
  Route::get('/linkedin_login/connect', 'Linkedin_connectorController@loginConnect');
});

Route::group(['middleware'=> 'web','middleware' => 'prevent-back-history'],function() {
  Route::get('/github/{type}','Github_connectorController@index');
  Route::get('/github_social/connect','Github_connectorController@socialConnect');
  Route::get('/github_social/disconnect','Github_connectorController@socialDisconnect');
  Route::get('/github_login/connect', 'Github_connectorController@loginConnect');
});

Route::group(['middleware'=> 'web','middleware' => 'prevent-back-history'],function() {
  Route::get('/meetup/{type}','Meetup_connectorController@index');
  Route::get('/meetup_social/connect','Meetup_connectorController@socialConnect');
  Route::get('/meetup_social/disconnect','Meetup_connectorController@socialDisconnect');
  Route::get('/meetup_login/connect', 'Meetup_connectorController@loginConnect');
});

Route::group(['middleware'=> 'web','middleware' => 'prevent-back-history'],function() {
  Route::get('/eventbrite/{type}','Eventbrite_connectorController@index');
  Route::get('/eventbrite_social/connect','Eventbrite_connectorController@socialConnect');
  Route::get('/eventbrite_social/disconnect','Eventbrite_connectorController@socialDisconnect');
  Route::get('/eventbrite_login/connect', 'Eventbrite_connectorController@loginConnect');
});

Route::group(['middleware'=> 'web','middleware' => 'prevent-back-history'],function() {
  Route::get('/flickr/{type}','Flickr_connectorController@index');
  Route::get('/flickr_social/connect','Flickr_connectorController@socialConnect');
  Route::get('/flickr_social/disconnect','Flickr_connectorController@socialDisconnect');
  Route::get('/flickr_login/connect', 'Flickr_connectorController@loginConnect');
});

Route::group(['middleware'=> 'web','middleware' => 'prevent-back-history'],function() {
  Route::get('/foursquare/{type}','Foursquare_connectorController@index');
  Route::get('/foursquare_social/connect','Foursquare_connectorController@socialConnect');
  Route::get('/foursquare_social/disconnect','Foursquare_connectorController@socialDisconnect');
});

Route::group(['middleware'=> 'web','middleware' => 'prevent-back-history'],function() {
  Route::get('/instagram/{type}','Instagram_connectorController@index');
  Route::get('/instagram_social/connect','Instagram_connectorController@socialConnect');
  Route::get('/instagram_social/disconnect','Instagram_connectorController@socialDisconnect');
});

Route::group(['middleware'=> 'web','middleware' => 'prevent-back-history'],function() {
  Route::get('/strava/{type}','Strava_connectorController@index');
  Route::get('/strava_social/connect','Strava_connectorController@socialConnect');
  Route::get('/strava_social/disconnect','Strava_connectorController@socialDisconnect');
});

Route::group(['middleware'=> 'web','middleware' => 'prevent-back-history'],function() {
  Route::get('/weibo', 'Weibo_connectorController@index');
  Route::get('/weibo/connect', 'Weibo_connectorController@connect');
  Route::get('/weibo/disconnect', 'Weibo_connectorController@disconnect');
});

Route::group(['middleware'=> 'web','middleware' => 'prevent-back-history'],function() {
  Route::get('/freelancer/{type}','Freelancer_connectorController@index');
  Route::get('/freelancer_social/connect','Freelancer_connectorController@socialConnect');
  Route::get('/freelancer_social/disconnect','Freelancer_connectorController@socialDisconnect');
});

Route::group(['middleware'=> 'web','middleware' => 'prevent-back-history'],function() {
  Route::get('/tripit','Tripit_connectorController@index');
  Route::get('/tripit_social/connect','Tripit_connectorController@socialConnect');
  Route::get('/tripit_social/disconnect','Tripit_connectorController@socialDisconnect');
});

Route::group(['middleware' => ['web','prevent-back-history','auth','2fa']], function () {

  // All my routes that needs a logged in user
  Route::get('/', 'HomeController@activity');
  Route::get('/index', 'HomeController@activity');
  Route::get('/home', 'HomeController@activity');
  Route::get('/activity', 'HomeController@activity');
  Route::get('/maps', 'HomeController@maps');
  Route::get('/scaffold', 'scaffoldController@index');
  Route::post('home/getLocationJSON','HomeController@getLocationJSON');
  Route::post('home/getAllStyles','HomeController@getAllStyles');
  Route::post('home/getLocations','HomeController@getLocationDetails');
  Route::post('home/getStyles','HomeController@getStyles');

  Route::post('home/getCategories','HomeController@getCategories');

  Route::post('home/getCategoryOptions','HomeController@getCategoryOptions');

  Route::post('home/getAllCategories','HomeController@getAllCategories');
  Route::resource('password','PasswordController');

});

//modules Routes
Route::group(['middleware'=> 'web','middleware' => 'prevent-back-history'],function() {
  Route::resource('modules','ModuleController');
  Route::resource('module_fields', 'FieldController');
  Route::get('module_generate_migr/{model_id}', 'ModuleController@generate_migr');
  Route::get('module_generate_update/{model_id}/{crud_type_id}', 'ModuleController@generate_update');
  Route::post('module_generate_migr_crud', 'ModuleController@generate_migr_crud');
  Route::get('modules/{model_id}/set_view_col/{column_name}', 'ModuleController@set_view_col');
  Route::post('save_role_module_permissions/{id}', 'ModuleController@save_role_module_permissions');
  Route::get('save_module_field_sort/{model_id}', 'ModuleController@save_module_field_sort');
  Route::post('check_unique_val/{field_id}', 'FieldController@check_unique_val');
  Route::get('module_fields/{id}/delete', 'FieldController@destroy');
  Route::get('module_fields/{id}/set_visibility/{status}', 'FieldController@set_visibility');
  Route::get('get_module_files/{module_id}', 'ModuleController@get_module_files');
  Route::post('modules/getModulesSnippet/{module_id}','ModuleController@getModulesSnippet');
  Route::post('modules/updateModulesSnippet','ModuleController@updateModulesSnippet');
  Route::post('modules/deleteModulesSnippet','ModuleController@deleteModulesSnippet');  
  Route::resource('modules/delete','ModuleController@destroy');
  Route::post('modules/massModuleAction','ModuleController@massModuleAction');
  Route::post('module_fields/massFieldAction', 'FieldController@massFieldAction');
  Route::post('get_schema_fields/','ModuleController@get_schema_fields');
  Route::post('modules/getSchemaTablePopups','ModuleController@getSchemaTablePopups');
  Route::get('get_schema_tables/{provider_id}','ModuleController@get_schema_tables');
  Route::post('modules/newModulestore','ModuleController@newModulestore');
  Route::post('modules/moduleGroupStore','ModuleController@moduleGroupStore');
  Route::post('moduleActionStore/{module_id}','ModuleController@moduleActionStore');
  
  /* ================== Uploads ================== */
  Route::resource('uploads', 'UploadsController');
  Route::post('upload_files', 'UploadsController@upload_files');
  Route::get('uploaded_files', 'UploadsController@uploaded_files');
  Route::post('uploads_update_caption', 'UploadsController@update_caption');
  Route::post('uploads_update_filename', 'UploadsController@update_filename');
  Route::post('uploads_update_public', 'UploadsController@update_public');
  Route::post('uploads_delete_file', 'UploadsController@delete_file');
  
});

//dynamic_menu Routes
Route::group(['middleware'=> 'web','middleware' => 'prevent-back-history'],function() {
  Route::resource('menus','MenuController');
  Route::post('menus/update_hierarchy', 'MenuController@update_hierarchy');
  Route::get('menus/deleteModule/{id}', 'MenuController@delete_module');
  Route::get('menus/delete_menu/{id}', 'MenuController@delete_menu');
});

//payment_ledger Routes
Route::group(['middleware'=> 'web','middleware' => 'prevent-back-history'],function() {
  Route::resource('payment_ledger','Payment_ledgerController');
});

//payment_summary Routes
Route::group(['middleware'=> 'web','middleware' => 'prevent-back-history'],function() {
  Route::get('payment_summary/getPersons', 'Payment_summaryController@getPersons');
  Route::post('payment_summary/getMerchantCityList', 'Payment_summaryController@getMerchantCityList');
  Route::post('payment_summary/getLocationPostalList', 'Payment_summaryController@getLocationPostalList');
  Route::post('payment_summary/getMerchantCustomers', 'Payment_summaryController@getMerchantCustomers');
  Route::post('payment_summary/merchantAccountList', 'Payment_summaryController@merchantAccountList');
  Route::post('payment_summary/customerAccountList', 'Payment_summaryController@customerAccountList');
  Route::get('payment_summary/getPaymentTypes', 'Payment_summaryController@getPaymentTypes');
  Route::get('payment_summary/getAssets', 'Payment_summaryController@getPaymentAssets');
  Route::get('payment_summary/getFeeTypes', 'Payment_summaryController@getFeeTypes');
  Route::post('payment_summary/createPaymentSummary', 'Payment_summaryController@createPaymentSummary');
  Route::post('payment_summary/getPaymentSummaryList', 'Payment_summaryController@getPaymentSummaryList');
  Route::post('payment_summary/getAllPaymentLedger','Payment_summaryController@getAllPaymentLedger');
  Route::post('payment_summary/updatePaymentLedger','Payment_summaryController@updatePaymentLedger');
  Route::resource('payment_summary','Payment_summaryController');
});

//social Routes
Route::group(['middleware'=> 'web'],function(){
  Route::post('/social/getPersons','SocialController@getPersons');
  Route::post('/social/getSocials','SocialController@getSocials');
  Route::post('/social/getAccounts','SocialController@getSocialAccounts');
  Route::post('/social/getWallets','SocialController@getSocialWallets');
  Route::post('/social/createSocial','SocialController@createSocial');
  Route::post('/social/updateSocial','SocialController@updateSocial');
  Route::post('/social/getSocialApiKeys','SocialController@getSocialApiKeys');
  Route::post('/social/deleteSocialApiKeys','SocialController@deleteSocialApiKeys');
  Route::post('/social/getFilterSocialApiKeys','SocialController@getFilterSocialApiKeys');
  Route::post('/social/createSocialApiKeys','SocialController@createSocialApiKeys');
  Route::post('/social/updateSocialApiKeys','SocialController@updateSocialApiKeys');
  Route::post('/social/getAccountWallets','AccountController@getAccountWallets');
  Route::post('/social/updateWalletList', 'AccountController@updateWalletList');
  Route::post('/social/createAccountWallet', 'AccountController@createAccountWallet');
  Route::resource('social','\App\Http\Controllers\SocialController');
});

//bank
Route::group(['middleware'=> 'web'],function(){
  Route::get('/bank/getBankList','BankController@getBankList');
  Route::get('bank/getLocationTree', 'BankController@getLocationTree');
  Route::get('bank/getRegions', 'BankController@getRegions');
  Route::get('bank/getCountries','BankController@getBankCountries');
  Route::post('bank/updateBankLists','BankController@updateBankLists');
  Route::post('bank/deleteBankLists','BankController@deleteBankLists');
  Route::resource('bank','BankController');
});

//yodlee Routes
Route::group(['middleware'=> 'web'],function(){
  Route::post('/yodlee/saveYadleeSession','YodleeController@saveYadleeSession');
  Route::post('/yodlee/saveYodleeAccount','YodleeController@saveYodleeAccount');
  Route::post('/yodlee/getYodleeAccount','YodleeController@getYodleeAccount');
  Route::post('/yodlee/saveYodleeTransactions','YodleeController@saveYodleeTransactions');
  Route::post('/yodlee/getAccountTransaction','YodleeController@getAccountTransaction');
  Route::get('/yodlee/fastLinkLoginFrom','YodleeController@fastLinkLoginForm');
  Route::post('/yodlee/getUserYodleeAccount','YodleeController@getUserYodleeAccount');

  Route::resource('yodlee','YodleeController');
});

//asset_sale_list Routes
Route::group(['middleware'=> 'web'],function(){
  Route::resource('asset_sale_list','Asset_sale_listController');
  Route::post('asset_sale_list/{id}/update','Asset_sale_listController@update');
  Route::get('asset_sale_list/{id}/delete','Asset_sale_listController@destroy');
  Route::get('asset_sale_list/{id}/deleteMsg','Asset_sale_listController@DeleteMsg');
  Route::post('asset_sale_list/getEvents','Asset_sale_listController@getEvents');

});

//exchange_category_list Routes
Route::group(['middleware'=> 'web'],function(){
  Route::resource('exchange_category_list','Exchange_category_listController');
  Route::post('exchange_category_list/{id}/update','Exchange_category_listController@update');
  Route::get('exchange_category_list/{id}/delete','Exchange_category_listController@destroy');
  Route::get('exchange_category_list/{id}/deleteMsg','Exchange_category_listController@DeleteMsg');
  Route::post('exchange_category_list/getCategoryTypes','Exchange_category_listController@getCategoryTypes');

});

//Account Routes
  Route::group(['middleware'=> 'web','middleware' => 'prevent-back-history'],function() {

    Route::get('account', 'AccountController@accounts');

    Route::get('account/getMerchantTypes', 'HomeController@getMerchantTypes');

    Route::post('account/getMerchants', 'AccountController@getAccountMerchants');
    Route::get('account/getAssets/{flag}', 'AccountController@getAccountAssets');
    Route::get('account/getMerchantAssets/{mid}', 'AccountController@getMerchantAssets');
    Route::get('account/getReferrerAccounts/{mid}/{cid}', 'AccountController@getReferrerAccounts');
    Route::post('account/merchant_account_list','AccountController@merchant_account_list');
    Route::post('account/updateList','AccountController@updateList');
    Route::post('account/createMerchantAccount','AccountController@createMerchantAccount');
    Route::get('account/getCustomers/{id}', 'AccountController@getAccountCustomers');
    Route::post('account/customer_account_list','AccountController@customer_account_list');
    Route::post('account/createCustomerAccount','AccountController@createCustomerAccount');
    Route::post('account/updateCustomerList','AccountController@updateCustomerList');
    Route::post('account/filterAssets','AccountController@filterAssets');
    
    Route::post('account/getAccountWallets', 'AccountController@getAccountWallets');
    Route::post('account/createAccountWallet', 'AccountController@createAccountWallet');
    Route::post('account/updateWalletList', 'AccountController@updateWalletList');
    Route::get('account/getWallets', 'AccountController@getAllWallets');

  });

//customer_account_list Routes
Route::group(['middleware'=> 'web'],function(){
  Route::resource('customer_account_list','Customer_account_listController');
  Route::post('customer_account_list/{id}/update','Customer_account_listController@update');
  Route::get('customer_account_list/{id}/delete','Customer_account_listController@destroy');
  Route::get('customer_account_list/{id}/deleteMsg','Customer_account_listController@DeleteMsg');
  Route::post('customer_account_list/getAccounts','Customer_account_listController@getAccounts');
});




//asset_category_list Routes
Route::group(['middleware'=> 'web'],function(){
  Route::resource('asset_category_list','Asset_category_listController');
  Route::post('asset_category_list/{id}/update','Asset_category_listController@update');
  Route::get('asset_category_list/{id}/delete','Asset_category_listController@destroy');
  Route::get('asset_category_list/{id}/deleteMsg','Asset_category_listController@DeleteMsg');
  Route::post('asset_category_list/getCategoryTypes','Asset_category_listController@getCategoryTypes');

});
//website_domain Routes
Route::group(['middleware'=> 'web'],function(){
  Route::resource('website_domain','Website_domainController');
  Route::post('website_domain/{id}/update','Website_domainController@update');
  Route::get('website_domain/{id}/delete','Website_domainController@destroy');
  Route::get('website_domain/{id}/deleteMsg','Website_domainController@DeleteMsg');
});

//identity_website Routes
Route::group(['middleware'=> 'web'],function(){
  Route::resource('identity_website','Identity_websiteController');
  Route::get('/website_list/{website_search}','Identity_websiteController@websiteList');
  Route::get('/website_group_list/{website_search}','Identity_websiteController@websiteGroupList');
  Route::get('/add_website_list','Identity_websiteController@addWebsiteList');
  Route::get('/update_website_list','Identity_websiteController@updateWebsiteList'); 
  Route::get('/update_website_list','Identity_websiteController@updateWebsiteList');
  Route::get('/delete_website_list','Identity_websiteController@deleteWebsiteList'); 
  Route::get('/regex_category_type','Identity_websiteController@categoriesList');
  Route::get('/update_website_categories_list','Identity_websiteController@updateWebsiteCategoriesList');
});
///search_result_queue Routes
Route::group(['middleware'=> 'web'],function(){
  Route::resource('search_result_queue','Search_result_queueController');
  Route::post('search_result_queue/{id}/update','Search_result_queueController@update');
  Route::get('search_result_queue/{id}/delete','Search_result_queueController@destroy');
  Route::get('search_result_queue/{id}/deleteMsg','Search_result_queueController@DeleteMsg');
  
});

//search_result_scrape Routes
Route::group(['middleware'=> 'web'],function(){
  Route::resource('search_result_scrape','Search_result_scrapeController');
  Route::post('search_result_scrape/{id}/update','Search_result_scrapeController@update');
  Route::get('search_result_scrape/{id}/delete','Search_result_scrapeController@destroy');
  Route::get('search_result_scrape/{id}/deleteMsg','Search_result_scrapeController@DeleteMsg');
});
//scrape events detail routs
Route::group(['middleware'=> 'web','middleware' => 'prevent-back-history'],function() {
  Route::get('/validate_regex_target_list', 'Validate_regex_target_listController@validateRegexTargetListView');
  Route::get('/event_queue_status_update','Search_result_queueController@eventQueueStatusUpdate');
  Route::get('/queue_event_details_view/{website_id}','Search_result_queueController@queueEventDetailsView');
  Route::get('/event_url_update','Search_result_queueController@eventUrlUpdate');
  Route::get('/event_details_update','Search_result_queueController@eventDetailsUpdate');
  Route::get('/scrape_event_details_view/{website_id}/{scrape_id}','Search_result_scrapeController@scrapeEventDetailsView');
  Route::get('/grab_scrape_detail','Search_result_scrapeController@grabScrapeDetail');
  Route::get('/update_tuple_list','Search_result_scrapeController@updateTupleList');
  Route::get('/grab_event_scrape_detail','Search_result_scrapeController@grabEventScrapeDetail');
  Route::get('/scrape_event_link_list/{scrape_id}','Search_result_scrapeController@scrapeEventLinkList');
  Route::get('/scrape_event_grab_detail_list/{scrape_id}','Search_result_scrapeController@scrapeEventGrabDetailList');
  Route::get('/scrape_event_list','Search_result_scrapeController@scrapeEventList');
  Route::get('/update_categories_list','Search_result_scrapeController@updateCategoriesList');

});

//event_url_list routes
Route::group(['middleware'=> 'web','middleware' => 'prevent-back-history'],function() {
Route::get('/event_url_list', 'Event_url_listController@eventUrlList');
Route::get('/event_url_details_view/{website_id}','Event_url_listController@eventUrlDetailsView');
Route::get('/event_url_list/{status_id}', 'Event_url_listController@eventUrlList'); 
Route::get('/search_keywords', 'Keywords_listController@keywordsList');
Route::get('search_keywords/getKeywordsLists','Keywords_listController@getKeywordsLists');
Route::get('createKeyword','Keywords_listController@createKeyword');
Route::get('/add_keyword','Keywords_listController@addKeyword');
Route::get('/keyword_list_details/{keyword_id}','Keywords_listController@searchKeywordDetailsList');
Route::get('search_keywords/{keyword_id}/editKeyword','Keywords_listController@editKeyword');
Route::get('/update_keyword','Keywords_listController@updateKeyword');
Route::get('/search_list','Search_listController@searchUrl');
Route::get('search_list/searchUrlLists','Search_listController@searchUrlList');
Route::post('search_list/updateSearchUrlLists','Search_listController@updateSearchUrl');
Route::post('search_list/deleteSearchUrlLists','Search_listController@deleteSearchUrl');
Route::post('search_list/createSearchUrlLists','Search_listController@createSearchUrl');
Route::get('/search_keywords/{id}/delete', 'Keywords_listController@keywordListDelete');
Route::get('search_keywords/updateActiveValue','Keywords_listController@updateActiveValue');
Route::get('/website_domain_list/','Website_domainController@websiteLists');
Route::get('website_domain_list/websiteDomainLists','Website_domainController@websiteDomainLists');
Route::post('website_domain_list/updateWebsiteDomain','Website_domainController@updateWebsiteDomain');
Route::post('website_domain_list/deleteWebsiteDomain','Website_domainController@deleteWebsiteDomain');
Route::post('website_domain_list/createWebsiteDomain','Website_domainController@createWebsiteDomain');

 
});
//keyword_type_regex Routes
Route::group(['middleware'=> 'web'],function(){
  Route::resource('keyword_type_regex','Keyword_type_regexController');
  Route::post('keyword_type_regex/{id}/update','Keyword_type_regexController@update');
  Route::get('keyword_type_regex/{id}/delete','Keyword_type_regexController@destroy');
  Route::get('keyword_type_regex/{id}/deleteMsg','Keyword_type_regexController@DeleteMsg');
});
//block_delimiter Routes
Route::group(['middleware'=> 'web'],function(){
  Route::resource('block_delimiter','Block_delimiterController');
  Route::post('block_delimiter/{id}/update','Block_delimiterController@update');
  Route::get('block_delimiter/{id}/delete','Block_delimiterController@destroy');
  Route::get('block_delimiter/{id}/deleteMsg','Block_delimiterController@DeleteMsg');
});

//regex_example Routes
Route::group(['middleware'=> 'web'],function(){
  Route::resource('regex_example','Regex_exampleController');
  Route::post('regex_example/{id}/update','Regex_exampleController@update');
  Route::get('regex_example/{id}/delete','Regex_exampleController@destroy');
  Route::get('regex_example/{id}/deleteMsg','Regex_exampleController@DeleteMsg');
});

//trade_breach Routes
Route::group(['middleware'=> 'web'],function(){
  Route::resource('trade_breach','Trade_breachController');
  Route::post('trade_breach/{id}/update','Trade_breachController@update');
  Route::get('trade_breach/{id}/delete','Trade_breachController@destroy');
  Route::get('trade_breach/{id}/deleteMsg','Trade_breachController@DeleteMsg');
  Route::post('trade_breach/getMerchantCities','HomeController@getMerchantCityDetails');
  Route::post('trade_breach/getMerchantCityPostals','HomeController@getMerchantCityPostals');
  Route::post('trade_breach/getMerchantAccounts','HomeController@getMerchantAccounts');
  Route::post('trade_breach/getMerchantCustomers','HomeController@getMerchantCustomers');
  Route::post('trade_breach/getCustomerAccounts','HomeController@getCustomerAccounts');  
});

//trade_limits Routes
Route::group(['middleware'=> 'web'],function(){
  Route::resource('trade_limits','Trade_limitsController');
  Route::post('trade_limits/{id}/update','Trade_limitsController@update');
  Route::get('trade_limits/{id}/delete','Trade_limitsController@destroy');
  Route::get('trade_limits/{id}/deleteMsg','Trade_limitsController@DeleteMsg');
  
  Route::post('trade_limits/getMerchantCities','HomeController@getMerchantCityDetails');
  Route::post('trade_limits/getMerchantCityPostals','HomeController@getMerchantCityPostals');
  
  
  Route::post('trade_limits/getMerchantCustomers','HomeController@getMerchantCustomers');
  Route::post('trade_limits/getCustomerAccounts','HomeController@getCustomerAccounts');
  Route::post('trade_limits/getMerchantAccounts','HomeController@getMerchantAccounts');
  Route::post('trade_limits/getMerchantGroups','HomeController@getMerchantGroups');
  Route::post('trade_limits/getMerchantGroupStaffs','HomeController@getMerchantGroupStaffs');
});


//asset_team Routes
Route::group(['middleware'=> 'web'],function(){
  Route::resource('asset_team','Asset_teamController');
  Route::post('asset_team/{id}/update','Asset_teamController@update');
  Route::get('asset_team/{id}/delete','Asset_teamController@destroy');
  Route::get('asset_team/{id}/deleteMsg','Asset_teamController@DeleteMsg');
});

//trade_order Routes
Route::group(['middleware'=> 'web'],function(){
  Route::resource('trade_order','Trade_orderController');
  Route::post('trade_order/{id}/update','Trade_orderController@update');
  Route::get('trade_order/{id}/delete','Trade_orderController@destroy');
  Route::get('trade_order/{id}/deleteMsg','Trade_orderController@DeleteMsg');
  Route::post('trade_order/getCity','Trade_orderController@getCity');
  Route::post('trade_order/getCounty','Trade_orderController@getCounty');
  Route::post('trade_order/getState','Trade_orderController@getState');
});

//asset_rate
Route::group(['middleware'=> 'web'],function(){
  Route::resource('asset_rate','Asset_rateController');
  Route::post('asset_rate/{id}/update','Asset_rateController@update');
  Route::get('asset_rate/{id}/delete','Asset_rateController@destroy');
  Route::get('asset_rate/{id}/deleteMsg','Asset_rateController@DeleteMsg');
});


//asset_risk Routes
Route::group(['middleware'=> 'web'],function(){
  Route::resource('asset_risk','Asset_riskController');
  Route::post('asset_risk/{id}/update','Asset_riskController@update');
  Route::get('asset_risk/{id}/delete','Asset_riskController@destroy');
  Route::get('asset_risk/{id}/deleteMsg','Asset_riskController@DeleteMsg');
});

//asset_pnl Routes
Route::group(['middleware'=> 'web'],function(){
  Route::resource('asset_pnl','Asset_pnlController');
  Route::post('asset_pnl/{id}/update','Asset_pnlController@update');
  Route::get('asset_pnl/{id}/delete','Asset_pnlController@destroy');
  Route::get('asset_pnl/{id}/deleteMsg','Asset_pnlController@DeleteMsg');
});

//asset_deal Routes
Route::group(['middleware'=> 'web'],function(){
  Route::resource('asset_deal','Asset_dealController');
  Route::post('asset_deal/{id}/update','Asset_dealController@update');
  Route::get('asset_deal/{id}/delete','Asset_dealController@destroy');
  Route::get('asset_deal/{id}/deleteMsg','Asset_dealController@DeleteMsg');
});

//status_crypto_type Routes
Route::group(['middleware'=> 'web'],function(){
  Route::resource('status_crypto_type','Status_crypto_typeController');
  Route::post('status_crypto_type/{id}/update','Status_crypto_typeController@update');
  Route::get('status_crypto_type/{id}/delete','Status_crypto_typeController@destroy');
  Route::get('status_crypto_type/{id}/deleteMsg','Status_crypto_typeController@DeleteMsg');
});

//status_fiat_type Routes
Route::group(['middleware'=> 'web'],function(){
  Route::resource('status_fiat_type','Status_fiat_typeController');
  Route::post('status_fiat_type/{id}/update','Status_fiat_typeController@update');
  Route::get('status_fiat_type/{id}/delete','Status_fiat_typeController@destroy');
  Route::get('status_fiat_type/{id}/deleteMsg','Status_fiat_typeController@DeleteMsg');
});

//status_operations_type Routes
Route::group(['middleware'=> 'web'],function(){
  Route::resource('status_operations_type','Status_operations_typeController');
  Route::post('status_operations_type/{id}/update','Status_operations_typeController@update');
  Route::get('status_operations_type/{id}/delete','Status_operations_typeController@destroy');
  Route::get('status_operations_type/{id}/deleteMsg','Status_operations_typeController@DeleteMsg');
});


//trade_fee_schedule Routes
Route::group(['middleware'=> 'web'],function(){
  Route::resource('trade_fee_schedule','Trade_fee_scheduleController');
  Route::post('trade_fee_schedule/{id}/update','Trade_fee_scheduleController@update');
  Route::get('trade_fee_schedule/{id}/delete','Trade_fee_scheduleController@destroy');
  Route::get('trade_fee_schedule/{id}/deleteMsg','Trade_fee_scheduleController@DeleteMsg');
});


//exchange_rate Routes
Route::group(['middleware'=> 'web'],function(){
  Route::resource('exchange_rates','Exchange_ratesController');
  Route::post('exchange_rates/{id}/update','Exchange_ratesController@update');
  Route::get('exchange_rates/{id}/delete','Exchange_ratesController@destroy');
  Route::get('exchange_rates/{id}/deleteMsg','Exchange_ratesController@DeleteMsg');
});

//exchange Routes
Route::group(['middleware'=> 'web','middleware' => 'prevent-back-history'],function(){
  Route::resource('exchange','ExchangeController');
  Route::post('exchange/{id}/update','ExchangeController@update');
  Route::get('exchange/{id}/delete','ExchangeController@destroy');
  Route::get('exchange/{id}/deleteMsg','ExchangeController@DeleteMsg');
});

//asset_type Routes
Route::group(['middleware'=> 'web'],function(){
  Route::resource('asset_type','Asset_typeController');
  Route::post('asset_type/{id}/update','Asset_typeController@update');
  Route::get('asset_type/{id}/delete','Asset_typeController@destroy');
  Route::get('asset_type/{id}/deleteMsg','Asset_typeController@DeleteMsg');
});

//asset Routes
Route::group(['middleware'=> 'web','middleware' => 'prevent-back-history'],function(){
  Route::resource('asset','AssetController');
  Route::post('asset/getAllAssets','AssetController@getAllAssets');
  Route::post('asset/updateAsset','AssetController@updateAsset');
  Route::post('asset/{id}/update','AssetController@update');
  Route::get('asset/{id}/delete','AssetController@destroy');
  Route::get('asset/{id}/deleteMsg','AssetController@DeleteMsg');
});

//hase_scrape_event_details Routes
Route::group(['middleware'=> 'web','middleware' => 'prevent-back-history'],function() {
  Route::get('/hase_scrape_details_list', 'Hase_scrape_details_translation_viewController@scrapeEventDetailsView');
  Route::get('/scrape-event-detail-list/{filterValue}', 'Hase_scrape_details_translation_viewController@scrapeEventDetailsList');
  Route::get('/scrape-event-translation-history/{scrapeDetailsId}', 'Hase_scrape_details_translation_viewController@scrapeEventTranslationHistory');
 Route::get('/hase_scrape_event_approval_list', 'Hase_scrape_details_translation_viewController@scrapeEventApprovalView');
 Route::get('hase_scrape_event_approval_list/getScrapeApprovalLists','Hase_scrape_details_translation_viewController@getScrapeApprovalLists');
 Route::get('/hase_scrape_details_list/update-website-url', 'Hase_scrape_details_translation_viewController@updateWebsiteUrl');
 Route::get('/hase_scrape_details_list/{id}/delete', 'Hase_scrape_details_translation_viewController@websiteUrlDelete');
});

Route::group(['middleware' => 'web','middleware' => 'prevent-back-history'], function () {
  Route::get('/hase_approval', 'ProductApprovalController@index');
   Route::get('/category_view/{filterValue}', 'ProductApprovalController@ApprovalProductsLists');
   Route::get('/rejects_comment_list/{approveId}', 'ProductApprovalController@RejectsCommentList');
    Route::get('/update_approval_status_multiple', 'ProductApprovalController@updateApprovalStatusMultiple');
  Route::get('/update_approval_status', 'ProductApprovalController@ApprovalActionStatusTransitionToAnother');
  Route::get('/update_approval_comments', 'ProductApprovalController@UpdateApprovalComments');
  Route::get('/product_update', 'ProductApprovalController@ProductUpdate');
});


//transaction_summary Routes
Route::group(['middleware'=> 'web','middleware' => 'prevent-back-history'],function(){
  Route::get('/transaction_summary/summary_view', 'Transaction_summaryController@summaryIndex');
  Route::get('/transaction_summary/summary_list', 'Transaction_summaryController@RetrieveTransactionSummaryList');
  Route::get('transaction_summary/ledger_list/{id}', 'Transaction_summaryController@RetrieveTransactionLedgerList');
  Route::resource('transaction_summary','Transaction_summaryController@summaryIndex');
  Route::post('transaction_summary/{id}/update','Transaction_summaryController@update');
  Route::get('transaction_summary/{id}/delete','Transaction_summaryController@destroy');
  Route::get('transaction_summary/{id}/deleteMsg','Transaction_summaryController@DeleteMsg');
});

//transactions_ledger Routes
Route::group(['middleware'=> 'web','middleware' => 'prevent-back-history'],function(){
  Route::resource('transactions_ledger','Transactions_ledgerController');
  Route::post('transactions_ledger/{id}/update','Transactions_ledgerController@update');
  Route::get('transactions_ledger/{id}/delete','Transactions_ledgerController@destroy');
  Route::get('transactions_ledger/{id}/deleteMsg','Transactions_ledgerController@DeleteMsg');
});

//account_type Routes
Route::group(['middleware'=> 'web','middleware' => 'prevent-back-history'],function(){
  Route::resource('account_type','account_typeController');
  Route::post('account_type/{id}/update','account_typeController@update');
  Route::get('account_type/{id}/delete','account_typeController@destroy');
  Route::get('account_type/{id}/deleteMsg','account_typeController@DeleteMsg');
});

//identity_type Routes
Route::group(['middleware'=> 'web'],function(){
  Route::resource('identity_type','Identity_typeController');
  Route::post('identity_type/{id}/update','Identity_typeController@update');
  Route::get('identity_type/{id}/delete','Identity_typeController@destroy');
  Route::get('identity_type/{id}/deleteMsg','Identity_typeController@DeleteMsg');
});

//trade_status_type Routes
Route::group(['middleware'=> 'web','middleware' => 'prevent-back-history'],function(){
  Route::resource('trade_status_type','Trade_status_typeController');
  Route::post('trade_status_type/{id}/update','Trade_status_typeController@update');
  Route::get('trade_status_type/{id}/delete','Trade_status_typeController@destroy');
  Route::get('trade_status_type/{id}/deleteMsg','Trade_status_typeController@DeleteMsg');
});

//trade_order_type Routes
Route::group(['middleware'=> 'web','middleware' => 'prevent-back-history'],function(){
  Route::resource('trade_order_type','Trade_order_typeController');
  Route::post('trade_order_type/{id}/update','Trade_order_typeController@update');
  Route::get('trade_order_type/{id}/delete','Trade_order_typeController@destroy');
  Route::get('trade_order_type/{id}/deleteMsg','Trade_order_typeController@DeleteMsg');
});

//trade_reason_type Routes
Route::group(['middleware'=> 'web','middleware' => 'prevent-back-history'],function(){
  Route::resource('trade_reason_type','Trade_reason_typeController');
  Route::post('trade_reason_type/{id}/update','Trade_reason_typeController@update');
  Route::get('trade_reason_type/{id}/delete','Trade_reason_typeController@destroy');
  Route::get('trade_reason_type/{id}/deleteMsg','Trade_reason_typeController@DeleteMsg');
});

//trade_side_type Routes
Route::group(['middleware'=> 'web','middleware' => 'prevent-back-history'],function(){
  Route::resource('trade_side_type','Trade_side_typeController');
  Route::post('trade_side_type/{id}/update','Trade_side_typeController@update');
  Route::get('trade_side_type/{id}/delete','Trade_side_typeController@destroy');
  Route::get('trade_side_type/{id}/deleteMsg','Trade_side_typeController@DeleteMsg');
});

//trade_transaction_type Routes
Route::group(['middleware'=> 'web','middleware' => 'prevent-back-history'],function(){
  Route::resource('trade_transaction_type','Trade_transaction_typeController');
  Route::post('trade_transaction_type/{id}/update','Trade_transaction_typeController@update');
  Route::get('trade_transaction_type/{id}/delete','Trade_transaction_typeController@destroy');
  Route::get('trade_transaction_type/{id}/deleteMsg','Trade_transaction_typeController@DeleteMsg');
});

//position Routes
Route::group(['middleware'=> 'web','middleware' => 'prevent-back-history'],function(){
  Route::resource('position','PositionController');
  Route::post('position/{id}/update','PositionController@update');
  Route::get('position/{id}/delete','PositionController@destroy');
  Route::get('position/{id}/deleteMsg','PositionController@DeleteMsg');
  Route::get('/position_view', 'PositionController@customerIndex');
  Route::get('/position_list/{filterValue}', 'PositionController@RetrieveCustomerPositionList');
});

//Trade_order_list Routes
Route::group(['middleware'=> 'web','middleware' => 'prevent-back-history'],function() {
Route::get('hase_otc_order_entry/hase_trade_order_view', 'Hase_trade_orders_listController@tradeOrderListView');
Route::get('/otc-entry-detail-list/{accountId}','Hase_trade_orders_listController@otcManagerEntryList');
Route::post('hase_otc_order_entry/customer_order_details','Hase_trade_orders_listController@customerOrderDetailsList');
Route::post('update_trade_order_status','Hase_trade_orders_listController@updateTradeOrderStatus');
Route::get('hase_otc_order_entry/fx_all_rates_list','Hase_trade_orders_listController@fxAllRatesList');
Route::get('/hase_trade_orders_queue','Hase_trade_orders_listController@tradeOrdersQueue');
Route::post('hase_otc_order_entry/account_quantity_Data','Hase_trade_orders_listController@accountQuantityData');
Route::post('/update_queue_status','Hase_trade_orders_listController@updateQueueStatus');
Route::get('/trade_orders_queue_mobile','Hase_trade_orders_listController@tradeOrdersQueueMobile');
});

//hase_otc_order_entry Routes
Route::group(['middleware'=> 'web','middleware' => 'prevent-back-history'],function() {
Route::get('/hase_otc_order_entry', 'Hase_otc_order_entry_listController@otcOrderEntryList');
Route::post('hase_otc_order_entry/customer_account_list','Hase_otc_order_entry_listController@customerAccountList');
Route::post('hase_otc_order_entry/customer_list','Hase_otc_order_entry_listController@customerList');
Route::get('hase_otc_order_entry/customer_id','Hase_otc_order_entry_listController@customerIdList');
Route::get('hase_otc_order_entry/customer_id_list','Hase_otc_order_entry_listController@accountIdList');
Route::get('hase_otc_order_entry/fx_rates_list','Hase_otc_order_entry_listController@fxRatesList');
Route::get('hase_otc_order_entry/customer_list','Hase_otc_order_entry_listController@customerList');

Route::post('otc_entry_details_update','Hase_otc_order_entry_listController@otcEntryDetailsUpdate');
Route::post('hase_otc_order_entry/merchant_account_list','Hase_otc_order_entry_listController@merchantAccountList');
Route::get('hase_otc_order_entry/asset_name_list','Hase_otc_order_entry_listController@assetNameList');


/*nirmal code */
Route::get('hase_otc_order_entry/broker_name_details','Hase_otc_order_entry_listController@brokerNameDetails');
Route::post('hase_otc_order_entry/asset_into_Value','Hase_otc_order_entry_listController@assetIntoValues');
Route::get('hase_otc_order_entry/exchange_name_details','Hase_otc_order_entry_listController@exchangeNameDetails');
Route::post('hase_otc_order_entry/asset_type_list','Hase_otc_order_entry_listController@tradeSideTypeList');
Route::post('hase_otc_order_entry/asset_sell_price','Hase_otc_order_entry_listController@assetSellPrice');
Route::post('hase_otc_order_entry/asset_buy_price','Hase_otc_order_entry_listController@assetBuyPrice');
Route::post('hase_otc_order_entry/fx_rates_list','Hase_otc_order_entry_listController@fxRatesList');
Route::post('hase_otc_order_entry/asset_settlement_details','Hase_otc_order_entry_listController@assetSettlementDetails');
Route::post('hase_otc_order_entry/account_settlement_selected','Hase_otc_order_entry_listController@accountSettlementSelected');
Route::post('hase_otc_order_entry/trade_basket_details','Hase_otc_order_entry_listController@tradeBasketDetails');
Route::post('hase_otc_order_entry/insert_basket_details','Hase_otc_order_entry_listController@insertBasketDetails');


});

//Trade_order_type_list Routes
Route::group(['middleware'=> 'web','middleware' => 'prevent-back-history'],function() {
  
  // Route::get('trade_order_type_list/order_type_list_view', 'Trade_order_type_listController@orderTypeListView');

  Route::get('trade_order_type_list/tradeOrderTypes', 'Trade_order_type_listController@tradeOrderTypes');

  Route::get('trade_order_type_list/getOrderTypeList/{id}', 'Trade_order_type_listController@getOrderTypeList');

  Route::get('trade_order_type_list/getOrderTypeListTree', 'Trade_order_type_listController@getOrderTypeListTree');

  // Route::get('trade_order_type_list/getOrderTypeListByListID/{id}', 'Trade_order_type_listController@getOrderTypeListByListID');

  Route::post('trade_order_type_list/updateList', 'Trade_order_type_listController@updateList');
  Route::get('trade_order_type_list/getMerchants', 'Trade_order_type_listController@getMerchants');
  // Route::get('trade_order_type_list/getAccounts', 'Trade_order_type_listController@getAccounts');
  // Route::get('trade_order_type_list/getAssets', 'Trade_order_type_listController@getAssets');
  Route::resource('trade_order_type_list','Trade_order_type_listController');
});

//Trade_order_side_list Routes
Route::group(['middleware'=> 'web','middleware' => 'prevent-back-history'],function() {
  
  // Route::get('trade_order_side_list/order_side_list_view', 'Trade_order_side_listController@orderSideListView');

  Route::get('trade_order_side_list/tradeSideTypes', 'Trade_order_side_listController@tradeSideTypes');

  Route::get('trade_order_side_list/getOrderSideList/{id}', 'Trade_order_side_listController@getOrderSideList');

  // Route::get('trade_order_side_list/getOrderSideListTree', 'Trade_order_side_listController@getOrderSideListTree');

  // Route::get('trade_order_side_list/getOrderSideListByListID/{id}', 'Trade_order_side_listController@getOrderSideListByListID');

  Route::post('trade_order_side_list/updateList', 'Trade_order_side_listController@updateList');
  Route::get('trade_order_side_list/getMerchants', 'Trade_order_side_listController@getMerchants');
  Route::resource('trade_order_side_list','Trade_order_side_listController');
});

//Merchant_customer_listController Routes
Route::group(['middleware'=> 'web','middleware' => 'prevent-back-history'],function() {
  
  Route::get('merchant_customer_list/merchant_customer_list_view', 'Merchant_customer_listController@merchantCustomerListView');

  Route::get('merchant_customer_list/getCustomers', 'Merchant_customer_listController@getCustomers');

  Route::get('merchant_customer_list/getMerchantCustomerList/{id}', 'Merchant_customer_listController@getMerchantCustomerList');

  Route::get('merchant_customer_list/getMerchantCustomerListTree', 'Merchant_customer_listController@getMerchantCustomerListTree');

  Route::get('merchant_customer_list/getMerchantCustomerListByListID/{id}', 'Merchant_customer_listController@getMerchantCustomerListByListID');

  Route::post('merchant_customer_list/updateList', 'Merchant_customer_listController@updateList');

  Route::resource('merchant_customer_list','Merchant_customer_listController');
});

//Location_listController Routes
Route::group(['middleware'=> 'web','middleware' => 'prevent-back-history'],function() {

  
  Route::get('location/getCountries', 'Location_listController@getLocationCountries');
  Route::get('location/getStates', 'Location_listController@getLocationStates');
  Route::get('location/getCounties', 'Location_listController@getLocationCounties');
  Route::get('location/getCities', 'Location_listController@getLocationCities');
  Route::get('location/getRegions', 'Location_listController@getRegions');
  
  Route::post('location/getIdentities', 'Location_listController@getIdentities');
  Route::post('location/getIdentityCityList', 'Location_listController@getIdentityCityList');
  Route::post('location/getIdentityCityListData', 'Location_listController@getIdentityCityListData');

  Route::post('location/getLocationData', 'Location_listController@getLocationData');

  Route::get('location/getLocationTree', 'Location_listController@getLocationTree');

  Route::post('location/updateList', 'Location_listController@updateList');
  Route::post('location/getPostalAddress', 'Location_listController@getPostalAddress');
  Route::post('location/updateLocation', 'Location_listController@updateLocation');

  Route::resource('location','Location_listController');
});

//trace limit view view Routes
Route::group(['middleware' => 'web','middleware' => 'prevent-back-history'], function () {
  Route::get('/trade_limits_view', 'TradeLimitsController@TradeLimitsIndex');
  Route::get('/trade_limits_list/{filterValue}', 'TradeLimitsController@RetrieveTradeLimitsList');
});


//hase_reservation Routes
Route::group(['middleware'=> 'web','middleware' => 'prevent-back-history'],function() {
  Route::resource('hase_reservation','Hase_reservationController');
  Route::post('hase_reservation/{id}/update','Hase_reservationController@update');
  Route::get('hase_reservation/{id}/delete','Hase_reservationController@destroy');
  Route::get('hase_reservation/{id}/acceptReject/{statusId}','Hase_reservationController@acceptReject');
  Route::post('hase_reservation/accept','Hase_reservationController@multiAccept');
  Route::post('hase_reservation/reject','Hase_reservationController@multiReject');
  Route::post('hase_reservation/getMerchantCities','HomeController@getMerchantCityDetails');
  Route::post('hase_reservation/getMerchantCityPostals','HomeController@getMerchantCityPostals');
  Route::post('hase_reservation/getSeatings','Hase_reservationController@getSeatings');
});

//hase_table Routes
Route::group(['middleware'=> 'web','middleware' => 'prevent-back-history'],function() {
  Route::resource('hase_table','Hase_tableController');
  Route::post('hase_table/{id}/update','Hase_tableController@update');
  Route::get('hase_table/{id}/delete','Hase_tableController@destroy');
});


//hase_customer_group Routes
Route::group(['middleware'=> 'web','middleware' => 'prevent-back-history'],function(){
  Route::resource('hase_customer_group','Hase_customer_groupController');
  Route::post('hase_customer_group/{id}/update','Hase_customer_groupController@update');
  Route::get('hase_customer_group/{id}/delete','Hase_customer_groupController@destroy');
});

//hase_customer Routes
Route::group(['middleware'=> 'web','middleware' => 'prevent-back-history'],function(){
  Route::post('hase_customer/{id}/update','Hase_customerController@update');
  Route::get('hase_customer/{id}/delete','Hase_customerController@destroy');  
  Route::get('hase_customer/getCustomers/{id}','Hase_customerController@getMerchantCustomers');
  Route::post('hase_customer/updateCustomer','Hase_customerController@updateCustomer');  
  Route::post('hase_customer/updateLocationList', 'Hase_customerController@updateLocation');
  Route::post('hase_customer/checkUsername','Hase_customerController@checkUsername');
  Route::post('hase_customer/checkEditUsername','Hase_customerController@checkEditUsername');
  Route::post('hase_customer/getCities', 'HomeController@getCityDetails');
  Route::post('hase_customer/getCounties', 'HomeController@getCountyDetails');
  Route::post('hase_customer/getStates', 'HomeController@getStateDetails');
  Route::get('hase_customer/getRegions','HomeController@getRegions');
  Route::get('hase_customer/getLocationTree','HomeController@getLocationTree');
  Route::post('hase_customer/getIdentityCityList','HomeController@getIdentityCityList');
  Route::post('hase_customer/insertIdentityCityList','HomeController@insertIdentityCityList');
  Route::post('hase_customer/getLocationData', 'Hase_customerController@getLocationsData');
  Route::post('hase_customer/getPostalAddress', 'HomeController@getPostalAddress');
  Route::post('hase_customer/updateLocation', 'Hase_customerController@updateLocationPostal');
  Route::post('hase_customer/getMerchants','Hase_customerController@getCustomerMerchants');
  Route::get('hase_customer/getCustomerGroup','Hase_customerController@getCustomerGroup');
  Route::post('hase_customer/reset','Hase_customerController@reset');

  Route::resource('hase_customer','Hase_customerController');

  
});

//hase_security_question Routes
Route::group(['middleware'=> 'web','middleware' => 'prevent-back-history'],function(){
  Route::resource('hase_security_question','Hase_security_questionController');
  Route::post('hase_security_question/update','Hase_security_questionController@update');
  Route::get('hase_security_question/{id}/delete','Hase_security_questionController@destroy');
});

//hase_menu Routes
Route::group(['middleware'=> 'web','middleware' => 'prevent-back-history'],function(){
  Route::any(Requests::segment(1).'/search','Hase_menuController@search');
  Route::resource('hase_menu','Hase_menuController');
  Route::post('hase_menu/{id}/update','Hase_menuController@update');
  Route::get('hase_menu/{id}/delete','Hase_menuController@destroy');  
  Route::post('hase_menu/getLocations','Hase_staffController@getLocations');

  Route::post('hase_menu/getMerchantCities','HomeController@getMerchantCityDetails');
  Route::post('hase_menu/getMerchantCityPostals','HomeController@getMerchantCityPostals');
  Route::post('hase_menu/getStyles','HomeController@getStyles');
  Route::post('hase_menu/getCategories','HomeController@getCategories');
  Route::post('hase_menu/getAllStyles','HomeController@getAllStyles');
  Route::post('hase_menu/getAllCategories','HomeController@getAllCategories');
  Route::post('hase_menu/getCategoryOptions','HomeController@getCategoryOptions');

  Route::resource('hase_product','Hase_menuController');
  Route::post('hase_product/{id}/update','Hase_menuController@update');
  Route::get('hase_product/{id}/delete','Hase_menuController@destroy');
  Route::post('hase_product/getLocations','Hase_staffController@getLocations');

  Route::post('hase_product/getMerchantCities','HomeController@getMerchantCityDetails');
  Route::post('hase_product/getMerchantCityPostals','HomeController@getMerchantCityPostals');
  Route::post('hase_product/getStyles','HomeController@getStyles');
  Route::post('hase_product/getCategories','HomeController@getCategories');
  Route::post('hase_product/getAllStyles','HomeController@getAllStyles');
  Route::post('hase_product/getAllCategories','HomeController@getAllCategories');
  Route::post('hase_product/getCategoryOptions','HomeController@getCategoryOptions');

});

//hase_option Routes
Route::group(['middleware'=> 'web','middleware' => 'prevent-back-history'],function(){
  Route::resource('hase_option','Hase_optionController');
  Route::post('hase_option/{id}/update','Hase_optionController@update');
  Route::get('hase_option/{id}/delete','Hase_optionController@destroy');

  Route::resource('hase_product_option','Hase_optionController');
  Route::post('hase_product_option/{id}/update','Hase_optionController@update');
  Route::get('hase_product_option/{id}/delete','Hase_optionController@destroy');
});

//hase_category Routes
Route::group(['middleware'=> 'web','middleware' => 'prevent-back-history'],function(){
  Route::resource('hase_category','Hase_categoryController');
  Route::post('hase_category/{id}/update','Hase_categoryController@update');
  Route::get('hase_category/{id}/delete','Hase_categoryController@destroy');
  Route::get('hase_category/{id}','Hase_categoryController@show');

  Route::resource('hase_product_category','Hase_categoryController');
  Route::post('hase_product_category/{id}/update','Hase_categoryController@update');
  Route::get('hase__product_category/{id}/delete','Hase_categoryController@destroy');
  Route::get('hase_product_category/{id}','Hase_categoryController@show');
});

//hase_staff_group Routes
Route::group(['middleware'=> 'web','middleware' => 'prevent-back-history'],function(){
  Route::resource('hase_staff_group','Hase_staff_groupController');
  Route::post('hase_staff_group/{id}/update','Hase_staff_groupController@update');
  Route::get('hase_staff_group/{id}/delete','Hase_staff_groupController@destroy');
  Route::get('hase_staff_group/{id}','Hase_staff_groupController@show');
  Route::get('hase_staff_group/getRolesList/{id}','Hase_staff_groupController@getRolesList');
  Route::post('hase_staff_group/updateRoles','Hase_staff_groupController@updateRoles');
  Route::post('hase_staff_group/cloneRole','Hase_staff_groupController@cloneRole');  
});

//hase_staff Routes
Route::group(['middleware'=> 'web','middleware' => 'prevent-back-history'],function(){
  Route::resource('hase_staff','Hase_staffController');
  Route::post('hase_staff/{id}/update','Hase_staffController@update');
  Route::get('hase_staff/{id}/delete','Hase_staffController@destroy');
  Route::post('hase_staff/checkUsername','Hase_staffController@checkUsername');
  Route::post('hase_staff/reset','Hase_staffController@reset');
  Route::post('hase_staff/cityDetailsList','Hase_staffController@getCityDetails'); 
  Route::post('hase_staff/locationDetailsList','Hase_staffController@getLocationDetails');
  Route::post('hase_staff/checkEmail','Hase_staffController@checkEmail');
  Route::post('hase_staff/getMerchants','Hase_staffController@getMerchantsForStaff');
  Route::post('hase_staff/getLocations','Hase_staffController@getLocations');
  Route::post('hase_staff/getMerchantCities','HomeController@getMerchantCityDetails');
  Route::post('hase_staff/getMerchantCityPostals','HomeController@getMerchantCityPostals');
  Route::get('hase_staff/getStaffList/{id}','Hase_staffController@getStaffList');
  Route::post('hase_staff/updateStaffDetails','Hase_staffController@updateStaffDetails'); 
  Route::post('hase_staff/getStaffGroup','Hase_staffController@staffGroup'); 
});

//hase_permission Routes
Route::group(['middleware'=> 'web','middleware' => 'prevent-back-history'],function(){
  Route::resource('hase_permission','Hase_permissionController');
  Route::post('hase_permission/{id}/update','Hase_permissionController@update');
  Route::get('hase_permission/{id}/delete','Hase_permissionController@destroy');
});



//hase_merchant Routes
Route::group(['middleware'=> 'web','middleware' => 'prevent-back-history'],function(){
  Route::any('hase_merchant/search','Hase_merchantController@search');
  Route::resource('hase_merchant','Hase_merchantController');
  Route::post('hase_merchant/{id}/update','Hase_merchantController@update');
  Route::get('hase_merchant/{id}/delete','Hase_merchantController@destroy');
  Route::post('hase_merchant/getFilter','Hase_merchantController@getFilter');
});

//hase_order Routes
Route::group(['middleware'=> 'web','middleware' => 'prevent-back-history'],function(){
  Route::resource('hase_order','Hase_orderController');
  Route::post('hase_order/{id}/update','Hase_orderController@update');
  Route::get('hase_order/{id}/delete','Hase_orderController@destroy');
});


//hase_import Routes
Route::group(['middleware'=> 'web','middleware' => 'prevent-back-history'],function(){
  Route::resource('hase_import','Hase_importController');
  Route::post('hase_import/{id}/update','Hase_importController@update');
  Route::get('hase_import/{id}/delete','Hase_importController@destroy');
  Route::get('hase_import/{id}/deleteMsg','Hase_importController@DeleteMsg');
});

//hase_activity Routes
Route::group(['middleware'=> 'web','middleware' => 'prevent-back-history'],function(){
  Route::resource('hase_activity','Hase_activityController');
  Route::post('hase_activity/updateActivityStatus','Hase_activityController@updateActivityStatus');
});

//hase_promotion Routes
Route::group(['middleware'=> 'web','middleware' => 'prevent-back-history'],function(){
  Route::any('hase_promotion/search','Hase_promotionController@search');
  Route::resource('hase_promotion','Hase_promotionController');
  Route::post('hase_promotion/{id}/update','Hase_promotionController@update');
  Route::get('hase_promotion/{id}/delete','Hase_promotionController@destroy');
  Route::post('hase_promotion/getMerchants','HomeController@getMerchantDetails');
  Route::post('hase_promotion/getLocations','Hase_staffController@getLocations');
  Route::post('hase_promotion/getFilter','Hase_promotionController@getFilter');
  Route::post('hase_promotion/getMerchantCities','HomeController@getMerchantCityDetails');
  Route::post('hase_promotion/getMerchantCityPostals','HomeController@getMerchantCityPostals');
});

//hase_import Routes
Route::group(['middleware'=> 'web','middleware' => 'prevent-back-history'],function(){
  Route::resource('hase_import','Hase_importController');
  Route::post('hase_import/{id}/update','Hase_importController@update');
  Route::get('hase_import/{id}/delete','Hase_importController@destroy');
  Route::get('hase_import/{id}/deleteMsg','Hase_importController@DeleteMsg');
});

//hase_merchant_retail_style_list Routes
Route::group(['middleware'=> 'web','middleware' => 'prevent-back-history'],function(){
  Route::any('hase_style_list/search','Hase_merchant_retail_style_listController@search');
  Route::resource('hase_style_list','Hase_merchant_retail_style_listController');
  Route::post('hase_style_list/{id}/update','Hase_merchant_retail_style_listController@update');
  Route::get('hase_style_list/{id}/delete','Hase_merchant_retail_style_listController@destroy');
  Route::post('hase_style_list/getLocations','HomeController@getLocationDetails');
  Route::post('hase_style_list/getStyles','HomeController@getStyles');
  Route::post('hase_style_list/getAllStyles','HomeController@getAllStyles');
  Route::post('hase_style_list/getFilter','Hase_merchant_retail_style_listController@getFilter');
});



//hase_merchant_hase_category_list Routes
Route::group(['middleware'=> 'web','middleware' => 'prevent-back-history'],function(){
  Route::any('hase_category_list/search','Hase_merchant_retail_category_listController@search');
  Route::resource('hase_category_list','Hase_merchant_retail_category_listController');
  Route::post('hase_category_list/{id}/update','Hase_merchant_retail_category_listController@update');
  Route::get('hase_category_list/{id}/delete','Hase_merchant_retail_category_listController@destroy');
  Route::post('hase_category_list/getLocations','HomeController@getLocationDetails');
  Route::post('hase_category_list/getCategories','HomeController@getCategories');

  Route::post('hase_category_list/getCategoryOptions','HomeController@getCategoryOptions');

  Route::post('hase_category_list/getAllCategories','HomeController@getAllCategories');
  Route::post('hase_category_list/getFilter','Hase_merchant_retail_category_listController@getFilter');
});

//hase_working_holiday Routes
Route::group(['middleware'=> 'web','middleware' => 'prevent-back-history'],function() {
  Route::resource('hase_working_holiday','Hase_working_holidayController');
  Route::post('hase_working_holiday/{id}/update','Hase_working_holidayController@update');
  Route::get('hase_working_holiday/{id}/delete','Hase_working_holidayController@destroy');
  Route::post('hase_working_holiday/getCountryState','Hase_working_holidayController@getCountryState');
});

//hase_cuisines_type Routes
Route::group(['middleware'=> 'web','middleware' => 'prevent-back-history'],function(){
  /* For restorant type */
  Route::resource('hase_cuisine_types','Hase_cusine_industry_typeController');
  Route::post('hase_cuisine_types/getCategoryType','Hase_cusine_industry_typeController@getCategoryType');
  
  /* For shop type */
  Route::resource('hase_industry_types','Hase_cusine_industry_typeController');
  Route::post('hase_industry_types/getCategoryType','Hase_cusine_industry_typeController@getCategoryType');

  Route::resource('hase_retail_style_type','Hase_merchant_retail_style_typeController');
  Route::post('hase_retail_style_type/{id}/update','Hase_merchant_retail_style_typeController@update');
  Route::post('hase_retail_style_type/{id}/delete','Hase_merchant_retail_style_typeController@destroy');
  Route::post('hase_retail_style_type/getRowStyle','Hase_merchant_retail_style_typeController@getRowStyle');
  Route::post('hase_retail_style_type/getParentStyle','Hase_merchant_retail_style_typeController@getParentStyle');

  Route::resource('hase_retail_category_type','Hase_merchant_retail_category_typeController');
  Route::post('hase_retail_category_type/{id}/update','Hase_merchant_retail_category_typeController@update');
  Route::post('hase_retail_category_type/{id}/delete','Hase_merchant_retail_category_typeController@destroy');
  Route::post('hase_retail_category_type/getRowCategory','Hase_merchant_retail_category_typeController@getRowCategory');
  Route::post('hase_retail_category_type/getParentCategory','Hase_merchant_retail_category_typeController@getParentCategory');


  Route::resource('hase_retail_option_type','Hase_merchant_retail_category_optionController');
  Route::post('hase_retail_option_type/{id}/update','Hase_merchant_retail_category_optionController@update');
  Route::post('hase_retail_option_type/{id}/delete','Hase_merchant_retail_category_optionController@destroy');
  Route::post('hase_retail_option_type/getRowOption','Hase_merchant_retail_category_optionController@getRowOption');
});

//hase_approval_group_list Routes
Route::group(['middleware'=> 'web','middleware' => 'prevent-back-history'],function(){
  Route::get('hase_approval_group_list/getStaffGroup','Hase_approval_group_listController@getStaffGroup');
  Route::get('hase_approval_group_list/getApprovalStatus','Hase_approval_group_listController@getApprovalStatus');
  Route::get('hase_approval_group_list/getApprovalGropLists','Hase_approval_group_listController@getApprovalGropLists');
  Route::get('hase_approval_group_list/getMerchantList','Hase_approval_group_listController@getMerchantList');
  Route::get('hase_approval_group_list/getCategoryList','Hase_approval_group_listController@getCategoryList');
  Route::post('hase_approval_group_list/createApprovalGropLists','Hase_approval_group_listController@createApprovalGropLists');
  Route::post('hase_approval_group_list/updateApprovalGropLists','Hase_approval_group_listController@updateApprovalGropLists');
  Route::post('hase_approval_group_list/deleteApprovalGropLists','Hase_approval_group_listController@deleteApprovalGropLists');
  Route::resource('hase_approval_group_list','Hase_approval_group_listController');
});

//hase_approval_status Routes
Route::group(['middleware'=> 'web','middleware' => 'prevent-back-history'],function(){
  Route::resource('hase_approval_status','Hase_approval_statusController');
  Route::post('hase_approval_status/{id}/update','Hase_approval_statusController@update');
  Route::get('hase_approval_status/{id}/delete','Hase_approval_statusController@destroy');
});

//hase_approval_crud_status Routes
Route::group(['middleware'=> 'web','middleware' => 'prevent-back-history'],function(){
  Route::resource('hase_approval_crud_status','Hase_approval_crud_statusController');
  Route::post('hase_approval_crud_status/{id}/update','Hase_approval_crud_statusController@update');
  Route::get('hase_approval_crud_status/{id}/delete','Hase_approval_crud_statusController@destroy');
});

//hase_chatbot_communication Routes
Route::group(['middleware'=> 'web','middleware' => 'prevent-back-history'],function(){
  Route::resource('hase_chatbot_communication','Hase_chatbot_communicationController');
  Route::post('hase_chatbot_communication/{id}/update','Hase_chatbot_communicationController@update');
  Route::get('hase_chatbot_communication/{id}/delete','Hase_chatbot_communicationController@destroy'); 
});

//hase_country Routes
Route::group(['middleware'=> 'web','middleware' => 'prevent-back-history'],function(){
  Route::resource('hase_country','Hase_countryController');
  Route::post('hase_country/{id}/update','Hase_countryController@update');
  Route::get('hase_country/{id}/delete','Hase_countryController@destroy');
  Route::get('hase_country/{id}/deleteMsg','Hase_countryController@DeleteMsg');
});

//hase_exhibition Routes
Route::group(['middleware'=> 'web','middleware' => 'prevent-back-history'],function(){
  Route::resource('hase_exhibition','Hase_exhibitionController');
  Route::post('hase_exhibition/{id}/update','Hase_exhibitionController@update');
  Route::get('hase_exhibition/{id}/delete','Hase_exhibitionController@destroy');
});

//hase_exhibitor Routes
Route::group(['middleware'=> 'web','middleware' => 'prevent-back-history'],function(){
  Route::resource('hase_exhibitor','Hase_exhibitorController');
  Route::post('hase_exhibitor/{id}/update','Hase_exhibitorController@update');
  Route::get('hase_exhibitor/{id}/delete','Hase_exhibitorController@destroy');
});

//Venue Routes
Route::group(['middleware'=> 'web','middleware' => 'prevent-back-history'],function(){

    Route::get('venue/getCountries', 'VenueController@getCountries');
    Route::get('venue/getStates', 'VenueController@getStates');
    Route::get('venue/getCounties', 'VenueController@getCounties');
    Route::get('venue/getCities', 'VenueController@getCities');
    Route::get('venue/getVenues', 'VenueController@getVenues');
    Route::post('venue/updateVenue', 'VenueController@updateVenue');
    Route::post('venue/getPostalAddress', 'VenueController@getPostalAddress');
    Route::post('venue/updateLocation', 'VenueController@updateLocation');
    Route::post('venue/getVenue', 'VenueController@getVenue');

    Route::resource('venue','VenueController');
});

//hase_status_view_manage Routes
Route::group(['middleware'=> 'web'],function(){
  Route::get('hase_status_view_manage/getApprovalStatus','Hase_translation_status_view_manageController@getApprovalStatus');
  Route::get('hase_status_view_manage/getTranslationManageLists','Hase_translation_status_view_manageController@getTranslationManageLists');
  Route::get('hase_status_view_manage/getUserStatusManageLists','Hase_translation_status_view_manageController@getUserStatusManageLists');
  Route::post('hase_status_view_manage/createUserStatusManageLists','Hase_translation_status_view_manageController@createUserStatusManageLists');
  Route::post('hase_status_view_manage/updateUserStatusManageLists','Hase_translation_status_view_manageController@updateUserStatusManageLists');
  Route::post('hase_status_view_manage/deleteUserStatusManageLists','Hase_translation_status_view_manageController@deleteUserStatusManageLists');
  Route::resource('hase_status_view_manage','Hase_translation_status_view_manageController');
});

//hase_translation_manage Routes
Route::group(['middleware'=> 'web'],function(){

  Route::get('hase_translation_manage/getApprovalStatus','Hase_translation_manageController@getApprovalStatus');
  Route::get('hase_translation_manage/getTranslationManageLists','Hase_translation_manageController@getTranslationManageLists');
  Route::get('hase_translation_manage/getManageTableList','Hase_translation_manageController@getManageTableList');
  Route::get('hase_translation_manage/getStaffGroup','Hase_translation_manageController@getStaffGroup');
  Route::get('hase_translation_manage/usersList','Hase_translation_manageController@usersList');
  Route::post('hase_translation_manage/createTranslationManageLists','Hase_translation_manageController@createTranslationManageLists');
  Route::post('hase_translation_manage/updateTranslationManageLists','Hase_translation_manageController@updateTranslationManageLists');
  Route::post('hase_translation_manage/deleteTranslationManageLists','Hase_translation_manageController@deleteTranslationManageLists');
  Route::resource('hase_translation_manage','Hase_translation_manageController');

});

//hase_word_translation Routes
Route::group(['middleware'=> 'web','middleware' => 'prevent-back-history'],function() {
  Route::get('/hase_translation_view', 'Hase_word_translation_viewController@index');
  Route::get('/hase_translation_list', 'Hase_word_translation_viewController@translation');
  Route::get('/word_translation_list/{languageCode}/{filterValue}', 'Hase_word_translation_viewController@WordTranslationList');
  Route::get('/words_translation_multiple_entries/{languageCode}/{originalWordId}/{approvedStatus}', 'Hase_word_translation_viewController@WordsTranslationMultipleEntries');
  Route::get('/update_translation_sentence_details/{languageCode}', 'Hase_word_translation_viewController@UpdateTranslationSentenceDetails'); 
  Route::get('/update_translation_status', 'Hase_word_translation_viewController@UpdatetranslationStatus');
  Route::get('/update_translation_status_multiple', 'Hase_word_translation_viewController@updateTranslationStatusMultiple');
  Route::get('/update_translation_comments', 'Hase_word_translation_viewController@updateTranslationComments');
  Route::get('/hase_word_approval_list', 'Hase_word_translation_viewController@wordApprovalView');
  Route::get('/hase_language_translation', 'Hase_word_translation_viewController@translationLanguage');
  Route::get('/word_translation_language_list/{filterValue}', 'Hase_word_translation_viewController@wordTranslationLanguageList');
  Route::get('/words_translation_language_details_list/{originalWordId}', 'Hase_word_translation_viewController@wordsTranslationLanguageDetailsList');
  Route::get('hase_word_approval_list/getWordApprovalLists','Hase_word_translation_viewController@getWordApprovalLists');
  Route::get('/hase_translation_list/{id}/delete', 'Hase_word_translation_viewController@wordsDelete');
});

//hase_image_translation Routes
Route::group(['middleware'=> 'web','middleware' => 'prevent-back-history'],function() {
  Route::get('/image_translation','Hase_image_translation_viewController@imageTranslationView');
  Route::get('/image-detail-list/{filterValue}','Hase_image_translation_viewController@imageDetailList');
  Route::get('/update-image-text','Hase_image_translation_viewController@updateImageText');
  Route::get('/image-translation-history-list/{imageId}', 'Hase_image_translation_viewController@imageTranslationHistoryList');
  Route::get('/image-view-status', 'Hase_image_translation_viewController@imageViewStatus');
  Route::get('/activity-region-window','Hase_image_translation_viewController@activityRegionWindow');
  Route::get('/activity-region-tree','Hase_image_translation_viewController@activityRegionWindow');
  Route::get('/hase_image_upload','Hase_image_translation_viewController@imageUploadList');
  Route::post('/image_upload','Hase_image_translation_viewController@imageUpload');
  Route::get('/insert-activity-region-value', 'Hase_image_translation_viewController@insertActivityRegionValue');
  Route::get('/hase_json_query_view', 'Hase_image_translation_viewController@jsonQueryView');
  Route::post('/translation_json_query_list','Hase_image_translation_viewController@translationJsonQueryList');
  Route::get('/hase_image_json_query_list', 'Hase_image_translation_viewController@imageJsonQueryListView');
  Route::get('/hase_image_approval_list', 'Hase_image_translation_viewController@imageApprovalView');
  Route::get('hase_image_approval_list/getImageApprovalLists','Hase_image_translation_viewController@getImageApprovalLists');
  Route::get('image_translation/{id}/delete','Hase_image_translation_viewController@imageDelete');

});

//hase_translation Routes
Route::group(['middleware'=> 'web','middleware' => 'prevent-back-history'],function() {
  Route::get('/hase_translation_approval','Hase_translation_viewController@translationApprovalView');
  Route::get('/translation-approval-detail-list/{filterValue}','Hase_translation_viewController@translationApprovalDetailList');
  Route::get('/update_image_translation_status', 'Hase_translation_viewController@updateImageTranslationStatus');
  Route::get('/update_image_translation_comments', 'Hase_translation_viewController@updateImageTranslationComments');
  Route::get('/user_known_language_list', 'Hase_translation_viewController@UserKnownLanguageList');
  Route::get('/hase_translation_queue', 'Hase_translation_viewController@imageWordQueue');
  Route::get('/image-word-status-update', 'Hase_translation_viewController@imageWordStatusUpdate');
  Route::get('/hase_translation_queue_randomly/{queueRandomValue}', 'Hase_translation_viewController@imageWordQueue');
  Route::post('/update-image-word-text', 'Hase_translation_viewController@updateImageWordText');
  Route::get('/translation-approval-current-detail-list/{filterValue}','Hase_translation_viewController@translationApprovalCurrentDetailList');
});

//hase_communication_translation Routes
Route::group(['middleware'=> 'web','middleware' => 'prevent-back-history'],function() {
  Route::get('/hase_chatbot_communication_view', 'Hase_communication_translation_viewController@chatbotCommunicationView');
  Route::get('/communication-detail-list/{filterValue}', 'Hase_communication_translation_viewController@communicationDetailsList');
  Route::get('/communication-translation-history/{communicationId}', 'Hase_communication_translation_viewController@communicationTranslationHistory');
  Route::get('/translation-language-difference','Hase_communication_translation_viewController@translationLanguageDifference');
  Route::get('/communication-diffrence-detail-list/{oldVersionNumber}/{newVersionNumber}', 'Hase_communication_translation_viewController@communicationDiffrenceDetailList');
  Route::get('/hase_chatbot_communication_language_view', 'Hase_communication_translation_viewController@chatbotCommunicationLanguageView');
  Route::get('/communication-detail-language-list/{filterValue}', 'Hase_communication_translation_viewController@communicationLanguageDetailsList');
  Route::get('/communication-translation-language-history/{communicationId}', 'Hase_communication_translation_viewController@communicationTranslationLanguageHistory');
  Route::get('/hase_communication_approval_list', 'Hase_communication_translation_viewController@communicationApprovalView');
    Route::get('hase_communication_approval_list/getCommunicationApprovalLists','Hase_communication_translation_viewController@getCommunicationApprovalLists');
    Route::get('/update-communication-details', 'Hase_communication_translation_viewController@updateCommunicationDetails');
});


//users_language Routes
Route::group(['middleware'=> 'web','middleware' => 'prevent-back-history'],function(){
  Route::resource('users_language','Hase_users_languageController');
  Route::post('users_language/{id}/update','Hase_users_languageController@update');
  Route::get('users_language/{id}/delete','Hase_users_languageController@destroy');
  Route::get('users_language/{id}/deleteMsg','Hase_users_languageController@DeleteMsg');
  Route::post('users_language/getIdentities', 'Hase_users_languageController@getIdentities');
  Route::post('users_language/updateDetails', 'Hase_users_languageController@updateDetails');
  Route::get('/language_identitites', 'Hase_users_languageController@languageIdentitites');
});

//asset_flow Routes
Route::group(['middleware'=> 'web'],function(){
  Route::resource('asset_flow','Asset_flowController');
  Route::post('asset_flow/{id}/update','Asset_flowController@update');
  Route::get('asset_flow/{id}/delete','Asset_flowController@destroy');
  Route::get('asset_flow/{id}','Asset_flowController@show');
  Route::post('asset_flow/getMerchantStaffs','HomeController@getMerchantStaffs');
});

//exchange_language_list Routes
Route::group(['middleware'=> 'web'],function(){
  //Route::resource('exchange_language_list','Exchange_language_listController');
  Route::get('exchange_language_list','Exchange_language_listController@index');
  Route::get('exchange_language_list/index','Exchange_language_listController@index');
  Route::get('exchange_language_list/create','Exchange_language_listController@create');
  Route::post('exchange_language_list/store','Exchange_language_listController@store');
  Route::get('exchange_language_list/{id}/delete','Exchange_language_listController@destroy');
  Route::post('exchange_language_list/getLanguages','Exchange_language_listController@getLanguages');
});


//exchange_asset_list Routes
Route::group(['middleware'=> 'web'],function() {
  Route::resource('exchange_asset_list','Exchange_asset_listController');
  Route::post('exchange_asset_list/{id}/update','Exchange_asset_listController@update');
  Route::get('exchange_asset_list/{id}/delete','Exchange_asset_listController@destroy');
  Route::get('exchange_asset_list/{id}','Exchange_asset_listController@show');
  Route::post('exchange_asset_list/getExchangeAsset','Exchange_asset_listController@getExchangeAsset');
  Route::post('exchange_asset_list/getExchangeAssetEdit','Exchange_asset_listController@getExchangeAssetEdit');
});

//asset_team_list Routes
Route::group(['middleware'=> 'web'],function(){
  Route::any('asset_team_list/search','Asset_team_listController@search');
  Route::get('/asset_team_list_assets', 'Asset_team_listController@assets');
  Route::get('/asset_team_list/asset_list', 'Asset_team_listController@asset_list');
  Route::get('/asset_team_list/people/{id}', 'Asset_team_listController@asset_people_list');
  Route::resource('asset_team_list','Asset_team_listController');
  Route::post('asset_team_list/{id}/update','Asset_team_listController@update');
  Route::get('asset_team_list/{id}/delete','Asset_team_listController@destroy');
  Route::get('asset_team_list/{id}','Asset_team_listController@show');
  Route::post('asset_team_list/getMembers','Asset_team_listController@getMembers');
  Route::post('asset_team_list/gete','Asset_social_listController@destroy');
});

//asset_social_list Routes
Route::group(['middleware'=> 'web'],function(){
  Route::resource('asset_social_list','Asset_social_listController');
  Route::post('asset_social_list/{id}/update','Asset_social_listController@update');
  Route::get('asset_social_list/{id}/delete','Asset_social_listController@destroy');
  Route::post('asset_social_list/getSocials','Asset_social_listController@getSocials');
});

//exchange_asset_pair Routes
Route::group(['middleware'=> 'web'],function() {
  Route::resource('exchange_asset_pair','Exchange_asset_pairController');
  Route::post('exchange_asset_pair/{id}/update','Exchange_asset_pairController@update');
  Route::get('exchange_asset_pair/{id}/delete','Exchange_asset_pairController@destroy');
  Route::get('exchange_asset_pair/{id}','Exchange_asset_pairController@show');
});

//asset_move Routes
Route::group(['middleware'=> 'web','middleware' => 'prevent-back-history'],function() {
  Route::get('asset_move','Asset_moveController@index');
  Route::get('asset_move/getMerchants', 'Asset_moveController@getAssetMerchants');
  Route::get('asset_move/getAssets/{flag}', 'Asset_moveController@getFilterAssets');
  Route::post('asset_move/getAssetMoveList','Asset_moveController@getAssetMoveList');
  Route::get('asset_move/getAccounts/{id}', 'Asset_moveController@getAssetAccounts');
  Route::post('asset_move/updateAssetMoveEntry','Asset_moveController@updateAssetMoveEntry');
  Route::post('asset_move/getAssetMoveHistoryList','Asset_moveController@getAssetMoveHistoryList');
  Route::post('asset_move/{id}/update','Asset_moveController@update');
  Route::get('asset_move/{id}/delete','Asset_moveController@destroy');
  Route::get('asset_move/{id}','Asset_moveController@show');
  Route::post('asset_move/updateMoveComments', 'Asset_moveController@updateMoveComments');
  Route::post('asset_move/moveCommentList', 'Asset_moveController@moveCommentList');
});

//asset_fund Routes
Route::group(['middleware'=> 'web','middleware' => 'prevent-back-history'],function(){
  Route::resource('asset_fund','Asset_fundController');
  Route::post('asset_fund/getAssetFundList','Asset_fundController@getAssetFundList');
  Route::post('asset_fund/getAssetFundHistoryList','Asset_fundController@getAssetFundHistoryList');
  Route::post('asset_fund/updateAssetFundEntry','Asset_fundController@updateAssetFundEntry');  
  Route::post('asset_fund/{id}/update','Asset_fundController@update');
  Route::get('asset_fund/{id}/delete','Asset_fundController@destroy');
  Route::get('asset_fund/{id}','Asset_fundController@show');
  Route::post('asset_fund/getMerchantAccounts','HomeController@getMerchantAccounts');
  Route::post('asset_fund/getMerchantCustomers','HomeController@getMerchantCustomers');
  Route::post('asset_fund/getCustomerAccounts','HomeController@getCustomerAccounts');
  Route::post('asset_fund/updateFundComments', 'Asset_fundController@updateFundComments');
  Route::post('asset_fund/fundCommentList', 'Asset_fundController@fundCommentList');
});

//payee Routes
Route::group(['middleware'=> 'web','middleware' => 'prevent-back-history'],function(){
    
  
  Route::post('payee/getPayees','PayeeController@getPayeesData');
  Route::post('payee/updatePayee','PayeeController@updatePayee');
  Route::post('payee/{id}/update','PayeeController@update');
  Route::get('payee/{id}/delete','PayeeController@destroy');
  Route::get('payee/getRegions','HomeController@getRegions');
  Route::get('payee/getLocationTree','HomeController@getLocationTree');
  Route::post('payee/getIdentityCityList','HomeController@getIdentityCityList');
  Route::post('payee/insertIdentityCityList','HomeController@insertIdentityCityList');
  Route::post('payee/getLocationData', 'HomeController@getLocationData');
  Route::post('payee/getPostalAddress', 'HomeController@getPostalAddress');
  Route::post('payee/updateLocation', 'HomeController@updateLocation');
  

  Route::get('payee_list','PayeeController@payee_list');
  Route::get('payee_list/getPayeeList','PayeeController@getPayeeList');
  Route::post('payee_list/getIdentities', 'PayeeController@getIdentities');
  Route::post('payee_list/getIdentityPayeeList', 'PayeeController@getIdentityPayeeList');
  Route::post('payee_list/updateList', 'PayeeController@updateList');
  Route::post('payee_list/createPayeeList', 'PayeeController@createPayeeList');
  Route::resource('payee','PayeeController');
  

});

//transaction_code Routes
Route::group(['middleware'=> 'web','middleware' => 'prevent-back-history'],function(){
  Route::resource('transactions_code','Transactions_codeController');
  Route::post('transactions_code/getAllTransactionCode','Transactions_codeController@getAllTransactionCode');
  Route::post('transactions_code/updateTransactionCode','Transactions_codeController@updateTransactionCode');
  Route::post('transactions_code/{id}/update','Transactions_codeController@update');
  Route::get('transactions_code/{id}/delete','Transactions_codeController@destroy');
  Route::get('transactions_code/{id}','Transactions_codeController@show');
});

//tax_type Routes
Route::group(['middleware'=> 'web','middleware' => 'prevent-back-history'],function(){
  Route::resource('tax_type','Tax_typeController');
  Route::post('tax_type/getAllTaxType','Tax_typeController@getAllTaxType');
  Route::post('tax_type/getAllTaxCategory','Tax_typeController@getAllTaxCategory');
  Route::post('tax_type/getTaxPercent','Tax_typeController@getTaxPercent');
  Route::post('tax_type/getAssetSettlementPrice','Tax_typeController@getAssetSettlementPrice');
  Route::post('tax_type/createTaxType','Tax_typeController@createTaxType');
  Route::post('tax_type/updateTaxType','Tax_typeController@updateTaxType');
  Route::get('tax_type/getAssets/{flag}', 'Tax_typeController@getTaxTypeAssets');
  Route::post('tax_type/getTaxCategory', 'Tax_typeController@getTaxCategory');
  Route::post('tax_type/getPersons', 'Tax_typeController@getPersons');
  Route::post('tax_type/createTaxTypeCategory', 'Tax_typeController@createTaxTypeCategory');
  Route::post('tax_type/{id}/update','Tax_typeController@update');
  Route::get('tax_type/{id}/delete','Tax_typeController@destroy');
  Route::get('tax_type/{id}','Tax_typeController@show');
});

//regex_setup Routes
Route::group(['middleware'=> 'web'],function(){
  Route::get('regex/getRegexTypes','RegexController@getRegexTypes');
  Route::get('regex/getRegexFields','RegexController@getRegexFields');
  Route::get('regex/getRegexCategories','RegexController@getRegexCategories');
  Route::get('regex/getLanguages','RegexController@getLanguages');
  Route::get('regex/getRegexPatterns','RegexController@getRegexPatterns');
  Route::get('regex/getRegexPrimitive','RegexController@getRegexPrimitive');
  
  Route::post('regex/createRegexPattern','RegexController@createRegexPattern');
  Route::post('regex/updateRegexPattern','RegexController@updateRegexPattern');
  Route::get('regex/deleteRegexPattern','RegexController@deleteRegexPattern');
  Route::get('regex/deleteRegexPrimitive','RegexController@deleteRegexPrimitive');
  Route::post('regex/updateIncellEditRegexPattern','RegexController@updateIncellRegexPattern');
  Route::post('regex/updateIncellEditRegexPrimitive','RegexController@updateIncellRegexPrimitive');
  
  Route::post('regex/getRegexDetail','RegexController@getRegexDetail');
  Route::post('regex/updateVerifyStatus','RegexController@updateVerifyStatus');

  Route::post('regex/getCategoryList','RegexController@getCategoryList');
  Route::post('regex/createCategory','RegexController@createCategory');
  Route::post('regex/updateCategory','RegexController@updateCategory');
  Route::post('regex/deleteCategory','RegexController@deleteCategory');
  Route::get('regex_field_details','RegexController@regexFieldList');
  Route::get('regex_type_details','RegexController@regexTypeList');
  Route::get('regex_category_field_details','RegexController@regexMapCategoryFieldList');
  Route::get('regex_category_type_details','RegexController@regexMapCategoryTypeList');
  Route::get('regex_map_table_details','RegexController@regexMapTableList');
  Route::post('regex/getRegexTypeList','RegexController@getRegexTypeList');
  Route::post('regex/getRefDetails','RegexController@getRefDetailsList');
  Route::post('regex/createRegexType','RegexController@createRegexType');
  Route::post('regex/updateRegexType','RegexController@updateRegexType');
  Route::post('regex/deleteRegexType','RegexController@deleteRegexType');
  Route::post('regex/getRegexFieldList','RegexController@getRegexFieldList');
  Route::post('regex/createRegexField','RegexController@createRegexField');
  Route::post('regex/updateRegexField','RegexController@updateRegexField');
  Route::post('regex/deleteRegexField','RegexController@deleteRegexField');
  Route::get('regex/getRegexReferenceTable','RegexController@getReferenceTableList');
  Route::get('regex/getRegexUserReferenceTable','RegexController@getReferenceUsersTableList');
   Route::get('regex/getRegexReferenceColumn','RegexController@getReferenceColumn');
  Route::get('regex/getAccessTableList','Regex_table_accessController@getAccessTableList');
  Route::post('regex/createAccessTable','Regex_table_accessController@createAccessTable');
  Route::post('regex/updateAccessTable','Regex_table_accessController@updateAccessTable');
  Route::post('regex/deleteAccessTable','Regex_table_accessController@deleteAccessTable');
  Route::get('regex/getReferenceTable','Regex_table_accessController@getReferenceTable');
  Route::get('regex/getReferenceTableGroupList','Regex_table_accessController@getReferenceTableGroupList');
  Route::post('regex/updateSplitData','RegexController@updateSplitData');
  Route::post('regex/getPrimitiveCode','RegexController@getPrimitiveCode');
  Route::get('regex/getRegexSplitData','RegexController@getRegexSplitData');
  Route::get('regex/getRegexSplitPrimitiveData/{split_id}','RegexController@getRegexSplitPrimitiveData');
  Route::post('regex/getSplitData','RegexController@getSplitData');
  Route::post('regex/deleteSplitData','RegexController@deleteSplitData');
  Route::resource('regex','RegexController');
});


  /* ================== Crosswalk_terrain ================== */
Route::group(['middleware'=> 'web','middleware' => 'prevent-back-history'],function() { 
  Route::get("regex_list/getParentIdList", "RegexListController@getParentIdList");
  Route::get("regex_list/getRootIdList", "RegexListController@getRootIdList");
   Route::resource('regex_list', 'RegexListController');
  Route::post('regex_list/getRegexList','RegexListController@getRegexList');
  Route::post('regex_list/updateRegexList','RegexListController@updateRegexList');
  Route::post('regex_list/deleteRegexList','RegexListController@deleteRegexList');
});

  /* ================== Crosswalk ================== */
Route::group(['middleware'=> 'web','middleware' => 'prevent-back-history'],function() { 
  Route::get("crosswalk_list/getCreatorIdList", "CrosswalkController@getCreatorIdList"); Route::resource('crosswalk_list', 'CrosswalkController');
  Route::post('crosswalk_list/getCrosswalkDetails','CrosswalkController@getCrosswalkDetails');
  Route::post('crosswalk_list/updateCrosswalkDetails','CrosswalkController@updateCrosswalkDetails');
  Route::post('crosswalk_list/deleteCrosswalkDetails','CrosswalkController@deleteCrosswalkDetails');
});

//regex_website Routes
Route::group(['middleware'=> 'web','middleware' => 'prevent-back-history'],function() {
  Route::get('regex_website/getWebsiteList','Regex_websiteController@getWebsiteList');
  Route::post('regex_website/createWebsite','Regex_websiteController@createWebsite');
  Route::post('regex_website/updateWebsite','Regex_websiteController@updateWebsite');
  Route::post('regex_website/deleteWebsite','Regex_websiteController@deleteWebsite');
  Route::get('regex_website/getRegexPatternList','Regex_websiteController@getRegexPatternList');
  Route::get('regex_website/getRegexTypes','Regex_websiteController@getRegexTypes');
  Route::post('regex_website/saveWebsiteRegex','Regex_websiteController@saveWebsiteRegex');
  
  Route::get('regex_website/getBlockLevel','Regex_websiteController@getBlockLevel');
  Route::post('regex_website/createBlockLevel','Regex_websiteController@createBlockLevel');
  Route::post('regex_website/updateBlockLevel','Regex_websiteController@updateBlockLevel');
  Route::post('regex_website/deleteBlockLevel','Regex_websiteController@deleteBlockLevel');
  Route::post('regex_website/assignWebsiteBlockLevel','Regex_websiteController@assignWebsiteBlockLevel');
  
  Route::get('regex_website/getBlockElementList','Regex_websiteController@getBlockElementList');
  Route::post('regex_website/createBlockElement','Regex_websiteController@createBlockElement');
  Route::post('regex_website/updateBlockElement','Regex_websiteController@updateBlockElement');
  Route::post('regex_website/deleteBlockElement','Regex_websiteController@deleteBlockElement');
  Route::post('regex_website/assignWebsiteBlockElement','Regex_websiteController@assignWebsiteBlockElement');

  Route::get('regex_website/getPaginationList','Regex_websiteController@getPaginationList');
  Route::post('regex_website/createPagination','Regex_websiteController@createPagination');
  Route::post('regex_website/updatePagination','Regex_websiteController@updatePagination');
  Route::post('regex_website/deletePagination','Regex_websiteController@deletePagination');
  Route::post('regex_website/assignWebsitePagination','Regex_websiteController@assignWebsitePagination');

  Route::post('regex_website/scrapeWebsiteLinkData','Regex_websiteController@scrapeWebsiteLinkData');
  Route::resource('regex_website','Regex_websiteController');
});

//regex_result
Route::group(['middleware'=> 'web','middleware' => 'prevent-back-history'],function() {
  Route::post('regex_result/scrapeSocialLinks','Regex_resultController@scrapeSocialLinks');
  Route::get('regex_result/getIdentityTables','Regex_resultController@getIdentityTables');
  Route::get('regex_result/getRegexCategories','RegexController@getRegexCategories');
  Route::get('regex_result/getWebsiteUrl','Regex_resultController@getWebsiteUrl');
  Route::get('regex_result/getPatternRegexResult','Regex_resultController@getPatternRegexResult');
  Route::resource('regex_result','Regex_resultController');
});

//proxy details 
Route::group(['middleware'=> 'web','middleware' => 'prevent-back-history'],function() {
  Route::get('proxy_node_source/proxyNodeSourceList','ProxiesController@proxyNodeSourceListData');
  Route::get('proxy_node_source/proxySummeryDetails/{summaryId}','ProxiesController@proxySummeryDetailsList');
  Route::get('proxy_node_source/proxyLocationDetails/{proxyId}','ProxiesController@proxyLocationDetails');
  Route::get('proxy_node_source/updateProxyDetailsStatus','ProxiesController@updateProxyDetailsStatusList');
  Route::get('proxy_node_source/getProxyStatusList','ProxiesController@proxyStatusList');
  Route::get('monitoring/lastStatusChartDetails/{proxy_status_id}','ProxiesController@lastStatusChartDetailsList');
  Route::get('monitoring/prevSpeedChartDetails/{proxy_status_id}','ProxiesController@prevSpeedChartList');
  Route::get('monitoring/initInitialChartDetails/{proxy_status_id}','ProxiesController@initInitialChartDetailsList');
  Route::get('proxy_node_source/getProxyStatusColorList','ProxiesController@proxyStatusList');
  Route::get('proxy_node_source/updateProxyStatusColorCode','ProxiesController@updateProxyStatusColor');
  Route::get('monitoring/lastAllChartDetails','ProxiesController@chartAllLastDetailsList');
  Route::get('monitoring/prevAllChartDetails','ProxiesController@chartAllPrevDetailsList');
  Route::get('monitoring/initAllChartDetails','ProxiesController@chartAllInitDetailsList');
  Route::get('monitoring/proxyAllStatus','ProxiesController@proxyAllStatusDetails');
  Route::get('monitoring','ProxiesController@donutsList');
  Route::resource('proxy_node_source','ProxiesController');
});

//htmldom
Route::group(['middleware'=> 'web','middleware' => 'prevent-back-history'],function() {
 Route::get('htmldom/scrapeHtmlDom','HtmldomController@scrapeHtmlDom');
 Route::get('htmldom/getIdentityTables','Regex_resultController@getIdentityTables');
 Route::get('htmldom/getWebsiteUrl','Regex_resultController@getWebsiteUrl');
 Route::get('htmldom/getRegexCategories','RegexController@getRegexCategories');
 Route::get('htmldom/getGroupPatterns','HtmldomController@getGroupPatterns');
 Route::post('htmldom/saveDomNodeValues','HtmldomController@saveDomNodeValues');
 Route::post('htmldom/updateHtmlDomClass','HtmldomController@updateHtmlDomClassList');
 Route::get('htmldom/getReferenceNodeDetails','HtmldomController@getReferenceNodeDetails');
 Route::post('htmldom/assignReferenceInfo','HtmldomController@assignReferenceInfo');
 Route::post('htmldom/gethtmlDomHistoryListList','HtmldomController@htmlDomHistoryListList');
 Route::get('htmldom/getScrapeUrlStatus','HtmldomController@getScrapeUrlStatus');
 Route::get('htmldom/getUserScrapeLists','HtmldomController@getUserScrapeLists');
 Route::get('htmldom/getTargetTables','HtmldomController@getTargetTables');
 Route::post('htmldom/getTargetFieldsDetails','HtmldomController@getTargetFieldsDetails');
 Route::get('htmldom/getTargetTableColumns','HtmldomController@getTargetTableColumns');
 Route::post('htmldom/saveTargetTableInfo','HtmldomController@saveTargetTableInfo');
 Route::get('htmldom/getMatchReferenceClass','HtmldomController@getMatchReferenceClass');
 Route::post('htmldom/saveTempReferenceData','HtmldomController@saveTempReferenceData');
 Route::post('htmldom/saveCrosswalkData','HtmldomController@saveCrosswalkData');
 Route::post('htmldom/saveLookupData','HtmldomController@saveLookupData');
 Route::get('htmldom/getReferenceData','HtmldomController@getReferenceData');
 Route::post('htmldom/updateReferenceData','HtmldomController@updateReferenceData');
 Route::post('htmldom/deleteReferenceData','HtmldomController@deleteReferenceData');
 Route::post('htmldom/cleanUserReferenceData','HtmldomController@cleanUserReferenceData');
 Route::post('htmldom/saveTargetMappingValues','HtmldomController@saveTargetMappingValues');
 Route::get('htmldom/getAllAsset','HtmldomController@getAllAsset');
 Route::get('htmldom/getAllSocials','HtmldomController@getAllSocials');
 Route::post('htmldom/savePrimitiveValues','HtmldomController@savePrimitiveValues');
 Route::post('htmldom/applySplitOnNodeValue','HtmldomController@applySplitOnNodeValue');
 Route::resource('htmldom','HtmldomController');
});

//scrape url status
Route::group(['middleware'=> 'web','middleware' => 'prevent-back-history'],function() {
  Route::get('scrapestatus/getScrapeStatusHistory','ScrapeStatusController@getScrapeStatusHistory');
  Route::resource('scrapestatus','ScrapeStatusController');
});

//social connector
Route::group(['middleware'=> 'web','middleware' => 'prevent-back-history'],function() {
  Route::post('social_connectors/getSocialConnectors','Social_connectorController@getSocialConnectors');
  Route::resource('social_connectors','Social_connectorController');
});

//google connector
Route::group(['middleware'=> 'web','middleware' => 'prevent-back-history'],function() {
  Route::get('/google/{type}','Google_connectorController@index');
  Route::get('/google_social/connect','Google_connectorController@socialConnect');
  Route::get('/google_social/disconnect','Google_connectorController@socialDisconnect');
  Route::get('/google_login/connect', 'Google_connectorController@loginConnect');
  Route::resource('google','Google_connectorController');
});

//google events
Route::group(['middleware'=> 'web','middleware' => 'prevent-back-history'],function() {
  Route::get('google_events/getUserGroupList', 'HomeController@getUserGroupList');
  Route::get('google_events/getMerchantType', 'HomeController@getAllMerchantTypes');
  Route::get('google_events/getUnsyncedCalendar', 'Google_eventsController@getUnsyncedCalendar');
  Route::resource('google_events','Google_eventsController');
  Route::post('google_events/googleCalendarList','Google_eventsController@googleCalendarList');
  Route::post('google_events/deleteGoogleEvent','Google_eventsController@deleteGoogleEvent');
  Route::post('google_events/deleteGoogleCalendar','Google_eventsController@deleteGoogleCalendar');
  Route::post('google_events/googleEventList','Google_eventsController@googleEventList');
  Route::post('google_events/fetchCalendarList','Google_eventsController@fetchCalendarList');
  Route::post('google_events/syncGoogleEvents','Google_eventsController@syncGoogleEvents');
  Route::post('google_events/saveSharedEventsToGroup','SocialEventsController@shareEventToGroup');
  Route::post('google_events/sharedEventGroup','SocialEventsController@sharedEventGroup');
  Route::post('google_events/graphEventCategories', 'SocialEventsController@graphEventCategories');
    Route::post('google_events/saveEventCategories','SocialEventsController@saveEventCategories');
});

//meetup events
Route::group(['middleware'=> 'web','middleware' => 'prevent-back-history'],function() {
    Route::get('meetup_events/getUserGroupList', 'HomeController@getUserGroupList');
    Route::get('meetup_events/getMerchantType', 'HomeController@getAllMerchantTypes');
    Route::resource('meetup_events','Meetup_eventsController');
    Route::post('meetup_events/fetchGroupList', 'Meetup_eventsController@fetchGroupList');
    Route::post('meetup_events/syncMeetupEvents','Meetup_eventsController@syncMeetupEvents');
    Route::post('meetup_events/meetupCalendarList','Meetup_eventsController@meetupCalendarList');
    Route::post('meetup_events/meetupEventList','Meetup_eventsController@meetupEventList');
    Route::post('meetup_events/sharedEventGroup','SocialEventsController@sharedEventGroup');
    Route::post('meetup_events/saveSharedEventsToGroup','SocialEventsController@shareEventToGroup');
    Route::post('meetup_events/deleteMeetupEvent','Meetup_eventsController@deleteMeetupEvent');
    Route::post('meetup_events/graphEventCategories', 'SocialEventsController@graphEventCategories');
    Route::post('meetup_events/saveEventCategories','SocialEventsController@saveEventCategories');
});

//eventbrite events
Route::group(['middleware'=> 'web','middleware' => 'prevent-back-history'],function() {
    Route::get('eventbrite_events/getUserGroupList', 'HomeController@getUserGroupList');
    Route::get('eventbrite_events/getMerchantType', 'HomeController@getAllMerchantTypes');
    Route::resource('eventbrite_events','Eventbrite_eventsController');
    Route::post('eventbrite_events/fetchMyEventList', 'Eventbrite_eventsController@fetchMyEventList');
    Route::post('eventbrite_events/fetchOtherEventList', 'Eventbrite_eventsController@fetchOtherEventList');
    Route::post('eventbrite_events/syncEventbriteEvents', 'Eventbrite_eventsController@syncEventbriteEvents');
    Route::post('eventbrite_events/eventBriteEventList', 'Eventbrite_eventsController@eventBriteEventList');
    Route::post('eventbrite_events/sharedEventGroup', 'SocialEventsController@sharedEventGroup');
    Route::post('eventbrite_events/saveSharedEventsToGroup','SocialEventsController@shareEventToGroup');
    Route::post('eventbrite_events/saveEventCategories','SocialEventsController@saveEventCategories');
    Route::post('eventbrite_events/deleteEventBriteEvent','Eventbrite_eventsController@deleteEventBriteEvent');
    Route::post('eventbrite_events/graphEventCategories', 'SocialEventsController@graphEventCategories');
});


//facebook events
Route::group(['middleware'=> 'web','middleware' => 'prevent-back-history'],function() {
    Route::get('facebook_events/getUserGroupList', 'HomeController@getUserGroupList');
    Route::resource('facebook_events','Facebook_eventsController');
    Route::post('facebook_events/fetchMyEventList', 'Facebook_eventsController@fetchMyEventList');
    Route::post('facebook_events/syncFacebookEvents', 'Facebook_eventsController@syncfacebookEvents');
    Route::post('facebook_events/facebookEventList', 'Facebook_eventsController@facebookEventList');
    Route::post('facebook_events/sharedEventGroup', 'SocialEventsController@sharedEventGroup');
    Route::post('facebook_events/saveSharedEventsToGroup','SocialEventsController@shareEventToGroup');
    Route::post('eventbrite_events/deleteFacebookEvent','Facebook_eventsController@deleteFacebookEvent');
});

//event schedule
Route::group(['middleware'=> 'web','middleware' => 'prevent-back-history'],function() {
    Route::get('event_scheduler/getMerchantType', 'HomeController@getAllMerchantTypes');
    Route::get('event_scheduler/getUserGroupList', 'HomeController@getUserGroupList');
    Route::get('event_scheduler/getGraphEvents', 'Event_schedulerController@getGraphEvents');
    Route::get('event_scheduler/createGraphEvents', 'Event_schedulerController@createGraphEvents');
    Route::get('event_scheduler/updateGraphEvents', 'Event_schedulerController@updateGraphEvents');
    Route::get('event_scheduler/destroyGraphEvents', 'Event_schedulerController@destroyGraphEvents');
    Route::resource('event_scheduler','Event_schedulerController');
    Route::post('event_scheduler/sharedEventGroup','SocialEventsController@sharedEventGroup');
    Route::post('event_scheduler/saveSharedEventsToGroup','SocialEventsController@shareEventToGroup');
    Route::post('event_scheduler/graphEventCategories', 'SocialEventsController@graphEventCategories');
    Route::post('event_scheduler/saveEventCategories','SocialEventsController@saveEventCategories');
});

//Tickets
Route::group(['middleware'=> 'web','middleware' => 'prevent-back-history'],function() {
    Route::get('ticket_event/getVenueList','Ticket_eventController@getVenueList');
    Route::get('ticket_event/getOpponentList','Ticket_eventController@getOpponentList');
    Route::resource('ticket_event','Ticket_eventController');
    Route::post('ticket_event/ticketEventList','Ticket_eventController@ticketEventList');
    Route::post('ticket_event/updateEventList','Ticket_eventController@updateEventList');
    Route::post('ticket_event/ticketVenueList','Ticket_eventController@ticketVenueList');
    Route::post('ticket_event/ticketOpponentList','Ticket_eventController@ticketOpponentList');
    Route::post('ticket_event/ticketServiceList','Ticket_eventController@ticketServiceList');
    Route::post('ticket_event/productionJsonDetail','Ticket_eventController@productionJsonDetailList');
});


//Tickets List
Route::group(['middleware'=> 'web','middleware' => 'prevent-back-history'],function() {
    Route::get('ticket_list','TicketListController@ticketList'); 
    Route::post('ticket_list/ticketPurchasedEvent','TicketListController@gatherTicketPurchasedWithoutMissed');
    Route::post('ticket_list/ticketListingEvent','TicketListController@ticketListingEventList');
    Route::post('ticket_list/ticketMissedEvent','TicketListController@gatherTicketMissedWithoutPurchase');
    Route::post('ticket_list/proxyPurchasedDetails','TicketListController@proxyPurchasedEventDetails');
    Route::post('ticket_list/ticketPurchasedWithMissed','TicketListController@gatherTicketPurchasedWithMissed');
    Route::post('ticket_list/purchasingDetails','TicketListController@purchasingDetailsList');
    Route::post('ticket_list/ticketMissedWithPurchase','TicketListController@gatherTicketMissedWithPurchase');
    Route::post('ticket_list/ticketPurchasedListingDetails','TicketListController@getTicketPurchasedListingDetails');
    Route::post('ticket_list/ticketMissedListingDetails','TicketListController@getTicketMissedListingDetails');
    Route::post('ticket_list/ticketSalesEvent','TicketListController@ticketSalesEvent');
});

// inventory
Route::group(['middleware'=> 'web','middleware' => 'prevent-back-history'],function() { 
   Route::resource('inventory', 'InventoriesController');
  Route::post('inventory/getInventories','InventoriesController@getInventories');
  Route::post('inventory/updateInventories','InventoriesController@updateInventories');
  Route::post('inventory/getInventoriesHistory','InventoriesController@getInventoriesHistory');
  Route::post('inventory/getCompetitors','InventoriesController@getCompetitors');
  Route::post('inventory/inventoryDetailsList','InventoriesController@getInventoryDetailsList');
  Route::post('inventory/competitorsDetailsList','InventoriesController@getCompetitorsDetailsList');
  Route::post('inventory/competitorsHistoryDetailsList','InventoriesController@getCompetitorsHistoryDetailsList');
  Route::post('inventory/inventoriesHistoryDetailsList','InventoriesController@getInventoriesHistoryDetailsList');
  Route::post('inventory/getCompetitorsHistory','InventoriesController@getCompetitorsHistory');
});

// Tickers
Route::group(['middleware'=> 'web','middleware' => 'prevent-back-history'],function() { 
   Route::resource('ticker', 'TickersController');
  Route::post('ticker/getTickers','TickersController@getTickers');
  Route::post('ticker/tickerDetailsList','TickersController@getTickerDetailsList');
  Route::post('ticker/tickerHistoryDetailsList','TickersController@getTickerHistoryDetailsList');
  Route::post('ticker/getTickersHistory','TickersController@getTickersHistory');
  Route::post('ticker/updateTickers','TickersController@updateTickers');
});

  // Venue_criteria
Route::group(['middleware'=> 'web','middleware' => 'prevent-back-history'],function() { 
  Route::get("venue_criteria/getvenue_idList", "Venue_criteriaController@getvenue_idList"); Route::resource('venue_criteria', 'Venue_criteriaController');
  Route::post('venue_criteria/getVenue_criteria','Venue_criteriaController@getVenue_criteria');
  Route::post('venue_criteria/updateVenue_criteria','Venue_criteriaController@updateVenue_criteria');
  Route::post('venue_criteria/criteria_json_upload','Venue_criteriaController@criteriaJsonUpload');
  Route::post('venue_criteria/deleteVenue_criteria','Venue_criteriaController@deleteVenue_criteria');
  Route::post('venue_criteria/getcriteria_exceptions_details','Venue_criteriaController@criteriaExceptionsDetails');
  Route::post('venue_criteria/getVenueName','Venue_criteriaController@getVenueName');
});

  /* ================== Production_criteria ================== */
Route::group(['middleware'=> 'web','middleware' => 'prevent-back-history'],function() { 
  Route::get("production_criteria/getProductionIdList", "Production_criteriaController@getProductionIdList");Route::get("production_criteria/getBrokerIdsList", "Production_criteriaController@getBrokerIdsList"); Route::resource('production_criteria', 'Production_criteriaController');
  Route::post('production_criteria/venueCriteriaDetails','Production_criteriaController@venueCriteriaDetailsList');
  Route::post('production_criteria/getProductionCriteria','Production_criteriaController@getProductionCriteria');
  Route::post('production_criteria/updateProductionCriteria','Production_criteriaController@updateProductionCriteria');
  Route::post('production_criteria/deleteProductionCriteria','Production_criteriaController@deleteProductionCriteria');
});

/* ================== Crosswalk_exchange ================== */
Route::group(['middleware'=> 'web','middleware' => 'prevent-back-history'],function() { 
  Route::get("crosswalk_section/getVenueIdList", "Crosswalk_exchangeController@getVenueIdList"); 
  Route::get("crosswalk_section/getDataTDList", "Crosswalk_exchangeController@getDataTDList");
  Route::get("crosswalk_section/getDataSHList", "Crosswalk_exchangeController@getDataSHList");
  Route::resource('crosswalk_section', 'Crosswalk_exchangeController');
  Route::post('crosswalk_section/getCrosswalkExchange','Crosswalk_exchangeController@getCrosswalkExchange');
  Route::post('crosswalk_section/updateCrosswalkExchange','Crosswalk_exchangeController@updateCrosswalkExchange');
  Route::post('crosswalk_section/deleteCrosswalkExchange','Crosswalk_exchangeController@deleteCrosswalkExchange');
});

/* ================== Crosswalk_venue ================== */
Route::group(['middleware'=> 'web','middleware' => 'prevent-back-history'],function() {
  Route::get("crosswalk_venue/getVenueTDList", "Crosswalk_venueController@getVenueTDList");
  Route::get("crosswalk_venue/getVenueSHList", "Crosswalk_venueController@getVenueSHList");
  Route::get("crosswalk_venue/getVenueSFList", "Crosswalk_venueController@getVenueSFList"); 
   Route::resource('crosswalk_venue', 'Crosswalk_venueController');
  Route::post('crosswalk_venue/getCrosswalkVenue','Crosswalk_venueController@getCrosswalkVenue');
  Route::post('crosswalk_venue/updateCrosswalkVenue','Crosswalk_venueController@updateCrosswalkVenue');
  Route::post('crosswalk_venue/deleteCrosswalkVenue','Crosswalk_venueController@deleteCrosswalkVenue');
});

  /* ================== Crosswalk_production ================== */
Route::group(['middleware'=> 'web','middleware' => 'prevent-back-history'],function() { 
  Route::get("crosswalk_production/getProductionTDList", "Crosswalk_productionController@getProductionTDList");
  Route::get("crosswalk_production/getProductionSHList", "Crosswalk_productionController@getProductionSHList");
  Route::get("crosswalk_production/getProductionSFList", "Crosswalk_productionController@getProductionSFList"); 
  Route::resource('crosswalk_production', 'Crosswalk_productionController');
  Route::post('crosswalk_production/getCrosswalkProduction','Crosswalk_productionController@getCrosswalkProduction');
  Route::post('crosswalk_production/updateCrosswalkProduction','Crosswalk_productionController@updateCrosswalkProduction');
  Route::post('crosswalk_production/deleteCrosswalkProduction','Crosswalk_productionController@deleteCrosswalkProduction');
});

/* ================== Crosswalk_exchange_ledger ================== */
Route::group(['middleware'=> 'web','middleware' => 'prevent-back-history'],function() { 
   Route::resource('crosswalk_exchange_ledger', 'Crosswalk_exchange_ledgerController');
  Route::post('crosswalk_exchange_ledger/getCrosswalkExchangeLedger','Crosswalk_exchange_ledgerController@getCrosswalkExchangeLedger');
  Route::post('crosswalk_exchange_ledger/updateCrosswalkExchangeLedger','Crosswalk_exchange_ledgerController@updateCrosswalkExchangeLedger');
  Route::post('crosswalk_exchange_ledger/deleteCrosswalkExchangeLedger','Crosswalk_exchange_ledgerController@deleteCrosswalkExchangeLedger');
});

//Instances_events
Route::group(['middleware'=> 'web','middleware' => 'prevent-back-history'],function() { 
   Route::resource('instances_events', 'Instances_eventsController');
  Route::post('instances_events/getInstances_events','Instances_eventsController@getInstances_events');
  Route::post('instances_events/updateInstances_events','Instances_eventsController@updateInstances_events');
  Route::post('instances_events/create_instances_events','Instances_eventsController@createInstancesEvents');
  Route::post('instances_events/getProductionIdList','Instances_eventsController@productionIdList');
});

/* ================== Transfer ================== */
Route::group(['middleware'=> 'web','middleware' => 'prevent-back-history'],function() { 
   Route::resource('transfer', 'TransferController');
  Route::post('transfer/getTransfer','TransferController@getTransfer');
  Route::post('transfer/updateTransfer','TransferController@updateTransfer');
  Route::post('transfer/deleteTransfer','TransferController@deleteTransfer');
});

//proxy_location Routes
Route::group(['middleware'=> 'web','middleware' => 'prevent-back-history'],function(){
  Route::resource('proxy_location','Proxy_locationController');
  Route::post('/proxy_location/getProxyLocationList','Proxy_locationController@getProxyLocationList');
  Route::post('/proxy_location/fetchProxyLocation','Proxy_locationController@fetchProxyLocation');
});

//portal_social_api Routes
Route::group(['middleware'=> 'web'],function(){
  
  Route::resource('portal_social_api','Portal_social_apikeysController');
  Route::post('portal_social_api/getPortalSocialConnector','Portal_social_apikeysController@getPortalSocialConnector');
  Route::post('portal_social_api/updatePortalSocialApi','Portal_social_apikeysController@updatePortalSocialApi');
  Route::post('portal_social_api/updatePortalSocialConnector','Portal_social_apikeysController@updatePortalSocialConnector');
  Route::get('portal_social_api/getPortalSocialApi/{connectorId}','Portal_social_apikeysController@getPortalSocialApi');
});

//database_manager Routes
Route::group(['middleware'=> 'web'],function(){
  Route::post('/database_manager/getDatabaseManager','DatabaseManagerController@getDatabaseManager');
  Route::post('/database_manager/saveDatabaseManager','DatabaseManagerController@saveDatabaseManager');
  Route::post('/database_manager/deleteDatabaseManager','DatabaseManagerController@deleteDatabaseManager');
  Route::post('/database_manager/getMenusHierarchy', 'DatabaseManagerController@getMenusHierarchy');
  Route::post('/database_manager/update_hierarchy', 'DatabaseManagerController@update_hierarchy');
  Route::get('/database_manager/getEnvironment', 'DatabaseManagerController@getEnvironment');
  Route::resource('database_manager','\App\Http\Controllers\DatabaseManagerController');
});

//limits_apikey Routes
Route::group(['middleware'=> 'web'],function(){
  Route::post('/limits_apikey/getLimitsApikey','LimitsApikeyController@getLimitsApikey');
  Route::post('/limits_apikey/saveLimitsApikey','LimitsApikeyController@saveLimitsApikey');
  Route::resource('limits_apikey','\App\Http\Controllers\LimitsApikeyController');
});

//merchant_type Routes
Route::group(['middleware'=> 'web'],function(){
  Route::get('/merchant_type/getAllMerchantTypes','MerchantTypeController@getAllMerchantTypes');
  Route::get('/merchant_type/getAllParentMerchantTypes','MerchantTypeController@getAllParentMerchantTypes');
  Route::post('/merchant_type/getMerchantTypes','MerchantTypeController@getMerchantTypes');
  Route::post('/merchant_type/saveMerchantTypes','MerchantTypeController@saveMerchantTypes');
  Route::resource('merchant_type','\App\Http\Controllers\MerchantTypeController');
});

// DiffContentCron Routes
Route::group(['middleware'=> 'web','middleware' => 'prevent-back-history'],function() {
  Route::resource('diffContentCron','DiffContentCronController');
});


  /* ================== Portal_exception ================== */
Route::group(['middleware'=> 'web','middleware' => 'prevent-back-history'],function() { 
  Route::get("portal_exception/getidentity_table_idList", "Portal_exceptionController@getidentity_table_idList");Route::get("portal_exception/getidentity_idList", "Portal_exceptionController@getidentity_idList"); Route::resource('portal_exception', 'Portal_exceptionController');
  Route::post('portal_exception/getPortal_exception','Portal_exceptionController@getPortal_exception');
  Route::post('portal_exception/updatePortal_exception','Portal_exceptionController@updatePortal_exception');
});

  /* ================== Ctaegory tree creation ================== */
Route::group(['middleware'=> 'web','middleware' => 'prevent-back-history'],function() { 
  Route::get('servicecategory/createCategoryTree/{merchant_type}','CategoryTreeCreationController@createCategoryTree');
  Route::get('servicecategory/createCategoryAutoComplete/{merchant_type}','CategoryTreeCreationController@createCategoryAutoComplete');
  Route::get('servicecategory/allTreeAutoCompleteCategories','CategoryTreeCreationController@allTreeAutoCompleteCategories');
  Route::resource('servicecategory','CategoryTreeCreationController');
});

  /* ================== Activities ================== */
Route::group(['middleware'=> 'web','middleware' => 'prevent-back-history'],function() { 
  Route::get("activities/getMerchantIdList", "ActivitiesController@getMerchantIdList");Route::get("activities/getUserIdList", "ActivitiesController@getUserIdList"); Route::resource('activities', 'ActivitiesController');
  Route::post('activities/getActivities','ActivitiesController@getActivities');
  Route::post('activities/updateActivities','ActivitiesController@updateActivities');
  Route::post('activities/deleteActivities','ActivitiesController@deleteActivities');
});

  /* ================== Category Json Tab ================== */
Route::group(['middleware'=> 'web','middleware' => 'prevent-back-history'],function() { 
  Route::get('categories/getMerchantType', 'HomeController@getAllMerchantTypes');
  Route::resource('categories','CategoriesController');
  Route::post('categories/selectedCategoryTreeCreate','CategoryTreeCreationController@selectedCategoryTreeCreate');
});

/* ================== Identity_dom_setup ================== */
Route::group(['middleware'=> 'web','middleware' => 'prevent-back-history'],function() { 
  Route::get("identity_dom_setup/getTableIdentityIdList", "Identity_dom_setupController@getTableIdentityIdList");
  Route::get("identity_dom_setup/getUrlIdList", "Identity_dom_setupController@getUrlIdList"); 
  Route::resource('identity_dom_setup', 'Identity_dom_setupController');
  Route::post('identity_dom_setup/getIdentityDomSetup','Identity_dom_setupController@getIdentityDomSetup');
  Route::post('identity_dom_setup/createIdentityDomSetup','Identity_dom_setupController@createIdentityDomSetup');
});  

//Entity Relationship Routes
Route::group(['middleware'=> 'web'],function(){
  Route::get('entity_relationship/getAllTableList','Entity_relationshipController@getAllTableList');
  Route::resource('entity_relationship','Entity_relationshipController');
  Route::post('entity_relationship/getTableForeignObject','Entity_relationshipController@getTableForeignObject');
});