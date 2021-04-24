<?php

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::group(['prefix' => 'admin', 'middleware' =>'admin_auth'], function () {
	Route::get('login', 'AdminController@login')->name('admin_login');
});

Route::match(['get', 'post'],'admin/authenticate', 'AdminController@authenticate');

Route::group(['prefix' => (LOGIN_USER_TYPE=='company')?'company':'admin', 'middleware' =>'admin_guest'], function () {

	Route::redirect('/',LOGIN_USER_TYPE.'/dashboard')->name('dashboard');
	Route::get('dashboard', 'AdminController@indexN');

	if (LOGIN_USER_TYPE == 'admin') {
		Route::get('logout', 'AdminController@logout');
	}

	// Front pages routes
	Route::view('files', 'home.files');

	// Manage Header footer setting
	Route::get('setting', 'SettingController@index')->middleware('admin_can:view_rider');
	Route::match(array('GET', 'POST'), 'add_setting', 'SettingController@add')->middleware('admin_can:create_rider');
	Route::match(array('GET', 'POST'), 'edit_setting/{id}', 'SettingController@update')->middleware('admin_can:update_rider');
	Route::match(array('GET', 'POST'), 'delete_setting/{id}', 'SettingController@delete')->middleware('admin_can:delete_rider');

	// Manage Menu
	Route::get('menu', 'MenuController@index')->middleware('admin_can:view_rider');
	Route::match(array('GET', 'POST'), 'add_menu', 'MenuController@add')->middleware('admin_can:create_rider');
	Route::match(array('GET', 'POST'), 'edit_menu/{id}', 'MenuController@update')->middleware('admin_can:update_rider');
	Route::match(array('GET', 'POST'), 'delete_menu/{id}', 'MenuController@delete')->middleware('admin_can:delete_rider');

	// Manage custom css
	Route::get('customCSS', 'CustomCSSController@index')->middleware('admin_can:view_rider');
	Route::match(array('GET', 'POST'), 'add_customCSS', 'CustomCSSController@add')->middleware('admin_can:create_rider');
	Route::match(array('GET', 'POST'), 'edit_customCSS/{id}', 'CustomCSSController@update')->middleware('admin_can:update_rider');
	Route::match(array('GET', 'POST'), 'delete_customCSS/{id}', 'CustomCSSController@delete')->middleware('admin_can:delete_rider');

	// Manage footer
	Route::get('footer', 'FooterController@index')->middleware('admin_can:view_rider');
	Route::match(array('GET', 'POST'), 'add_footer', 'FooterController@add')->middleware('admin_can:create_rider');
	Route::match(array('GET', 'POST'), 'edit_footer/{id}', 'FooterController@update')->middleware('admin_can:update_rider');
	Route::match(array('GET', 'POST'), 'delete_footer/{id}', 'FooterController@delete')->middleware('admin_can:delete_rider');

	//Front pages routes end

	// Admin Users and permission routes
	Route::group(['middleware' => 'admin_can:manage_admin'], function() {
        Route::get('admin_user', 'AdminController@view');
        Route::match(array('GET', 'POST'),'add_admin_user', 'AdminController@add');
        Route::match(array('GET', 'POST'),'edit_admin_users/{id}', 'AdminController@update');
        Route::match(array('GET', 'POST'),'delete_admin_user/{id}', 'AdminController@delete');

        Route::get('roles', 'RolesController@index');
        Route::match(array('GET', 'POST'), 'add_role', 'RolesController@add');
        Route::match(array('GET', 'POST'), 'edit_role/{id}', 'RolesController@update')->where('id', '[0-9]+');
        Route::get('delete_role/{id}', 'RolesController@delete')->where('id', '[0-9]+');
    });

    // Manage Help Routes
    Route::group(['middleware' => 'admin_can:manage_help'],function () {
        Route::get('help_category', 'HelpCategoryController@index');
        Route::match(array('GET', 'POST'), 'add_help_category', 'HelpCategoryController@add');
        Route::match(array('GET', 'POST'), 'edit_help_category/{id}', 'HelpCategoryController@update')->where('id', '[0-9]+');
        Route::get('delete_help_category/{id}', 'HelpCategoryController@delete')->where('id', '[0-9]+');
        Route::get('help_subcategory', 'HelpSubCategoryController@index');
        Route::match(array('GET', 'POST'), 'add_help_subcategory', 'HelpSubCategoryController@add');
        Route::match(array('GET', 'POST'), 'edit_help_subcategory/{id}', 'HelpSubCategoryController@update')->where('id', '[0-9]+');
        Route::get('delete_help_subcategory/{id}', 'HelpSubCategoryController@delete')->where('id', '[0-9]+');
        Route::get('help', 'HelpController@index');
        Route::match(array('GET', 'POST'), 'add_help', 'HelpController@add');
        Route::match(array('GET', 'POST'), 'edit_help/{id}', 'HelpController@update')->where('id', '[0-9]+');
        Route::get('delete_help/{id}', 'HelpController@delete')->where('id', '[0-9]+');
        Route::post('ajax_help_subcategory/{id}', 'HelpController@ajax_help_subcategory')->where('id', '[0-9]+');
    });

    //Additional Tax
     Route::group(['middleware' => 'admin_can:manage_additional_tax'],function () {
        Route::get('additional_tax', 'AdditionalTaxController@index');
        Route::match(array('GET', 'POST'), 'add_additional_tax', 'AdditionalTaxController@add');
        Route::match(array('GET', 'POST'), 'edit_additional_tax/{id}', 'AdditionalTaxController@update')->where('id', '[0-9]+');
        Route::get('delete_additional_tax/{id}', 'AdditionalTaxController@delete')->where('id', '[0-9]+');
    });

	// Send message
	Route::group(['middleware' => 'admin_can:manage_send_message'], function() {
		Route::match(array('GET', 'POST'), 'send_message', 'SendmessageController@index')->name('admin.send_message');
		Route::post('get_send_users', 'SendmessageController@get_send_users');
	});
	
	// Manage Rider
	Route::get('rider', 'RiderController@index')->middleware('admin_can:view_rider');
	Route::match(array('GET', 'POST'), 'add_rider', 'RiderController@add')->middleware('admin_can:create_rider');
	Route::match(array('GET', 'POST'), 'edit_rider/{id}', 'RiderController@update')->middleware('admin_can:update_rider');
	Route::match(array('GET', 'POST'), 'delete_rider/{id}', 'RiderController@delete')->middleware('admin_can:delete_rider');

	// Manage Driver
	Route::get('driver', 'DriverController@index')->middleware('admin_can:view_driver');
	Route::match(array('GET', 'POST'), 'add_driver', 'DriverController@add')->middleware('admin_can:create_driver');
	Route::match(array('GET', 'POST'), 'edit_driver/{id}', 'DriverController@update')->middleware('admin_can:update_driver');
	Route::match(array('GET', 'POST'), 'delete_driver/{id}', 'DriverController@delete')->middleware('admin_can:delete_driver');
	Route::match(['GET', 'POST'], 'get_documents', 'DriverController@get_documents');

	// Manage Statements
	Route::group(['middleware' =>  'admin_can:manage_statements'], function() {
		Route::post('get_statement_counts', 'StatementController@get_statement_counts');
		Route::get('statements/{type}', 'StatementController@index');
		Route::get('view_driver_statement/{driver_id}', 'StatementController@view_driver_statement');
		Route::post('driver_statement', 'StatementController@driver_statement');
		Route::post('statement_all', 'StatementController@custom_statement');
	});

	// Manage Location
	Route::group(['middleware' => 'admin_can:manage_locations'], function() {
		Route::get('locations', 'LocationsController@index');
	    Route::match(array('GET', 'POST'),'add_location', 'LocationsController@add')->name('admin.add_location');
	    Route::match(array('GET', 'POST'),'edit_location/{id}', 'LocationsController@update')->name('admin.edit_location');
	    Route::get('delete_location/{id}', 'LocationsController@delete');
	});

    // Manage Peak Based Fare Details
	Route::group(['middleware' => 'admin_can:manage_peak_based_fare'], function() {
		Route::get('manage_fare', 'ManageFareController@index');
	    Route::match(array('GET', 'POST'),'add_manage_fare', 'ManageFareController@add')->name('admin.add_manage_fare');
	    Route::match(array('GET', 'POST'),'edit_manage_fare/{id}', 'ManageFareController@update')->name('admin.edit_manage_fare');
	    Route::get('delete_manage_fare/{id}', 'ManageFareController@delete');
	});

	// Map
	Route::group(['middleware' =>  'admin_can:manage_map'], function() {
		Route::match(array('GET', 'POST'), 'map', 'MapController@index');
		Route::match(array('GET', 'POST'), 'mapdata', 'MapController@mapdata');
	});
	Route::group(['middleware' =>  'admin_can:manage_heat_map'], function() {
		Route::match(array('GET', 'POST'), 'heat-map', 'MapController@heat_map');
		Route::match(array('GET', 'POST'), 'heat-map-data', 'MapController@heat_map_data');
	});

	// Manage Vehicle Type
	Route::group(['middleware' =>  'admin_can:manage_vehicle_type'], function() {
		Route::get('vehicle_type', 'VehicleTypeController@index');
		Route::match(array('GET', 'POST'), 'add_vehicle_type', 'VehicleTypeController@add');
		Route::match(array('GET', 'POST'), 'edit_vehicle_type/{id}', 'VehicleTypeController@update');
		Route::match(array('GET', 'POST'), 'delete_vehicle_type/{id}', 'VehicleTypeController@delete');
	});

	// Manage Referrals Routes
	Route::group(['prefix' => 'referrals'], function() {
		Route::get('rider', 'ReferralsController@index')->middleware('admin_can:manage_rider_referrals');
		Route::get('driver', 'ReferralsController@index')->middleware('admin_can:manage_driver_referrals');
		Route::get('{id}', 'ReferralsController@referral_details');
	});

	// Manage Vehicle
	Route::group(['middleware' =>  'admin_can:manage_vehicle'], function() {
		Route::get('vehicle', 'VehicleController@index');
		Route::match(array('GET', 'POST'), 'add_vehicle', 'VehicleController@add');
		Route::post('manage_vehicle/{company_id}/get_driver', 'VehicleController@get_driver');
		Route::match(array('GET', 'POST'), 'edit_vehicle/{id}', 'VehicleController@update');
		Route::match(array('GET', 'POST'), 'delete_vehicle/{id}', 'VehicleController@delete');
		Route::match(array('GET', 'POST'), 'validate_vehicle_number','VehicleController@validate_vehicle_number');
		Route::match(array('GET', 'POST'), 'check_default','VehicleController@check_default');
	});

	// Trips
	Route::group(['middleware' =>  'admin_can:manage_trips'], function() {
		Route::match(array('GET', 'POST'), 'trips', 'TripsController@index');
		Route::get('view_trips/{id}', 'TripsController@view');
		Route::post('trips/payout/{id}', 'TripsController@payout');
		Route::get('trips/export/{from}/{to}', 'TripsController@export');
	});

	// Manage Driver Payout Routes
	Route::group(['middleware' =>  'admin_can:manage_driver_payments'], function() {
		Route::get('payout/overall', 'PayoutController@overall_payout');
		Route::get('weekly_payout/{driver_id}', 'PayoutController@weekly_payout');
		Route::get('per_week_report/{driver_id}/{start_date}/{end_date}', 'PayoutController@payout_per_week_report');
		Route::get('per_day_report/{driver_id}/{date}', 'PayoutController@payout_per_day_report');
		Route::post('make_payout', 'PayoutController@payout_to_driver');
	});

	// Manage Wallet
	Route::group(['prefix' => 'wallet', 'middleware' =>  'admin_can:manage_wallet'], function() {
		Route::get('{user_type}', 'WalletController@index')->name('wallet');
		Route::match(array('GET', 'POST'), 'add/{user_type}', 'WalletController@add')->name('add_wallet');
		Route::match(array('GET', 'POST'), 'edit/{user_type}/{id}', 'WalletController@update')->where('id', '[0-9]+')->name('edit_wallet');
		Route::get('delete/{user_type}/{id}', 'WalletController@delete')->where('id', '[0-9]+')->name('delete_wallet');
	});

	// Manage Promo Code
	Route::group(['middleware' =>  'admin_can:manage_promo_code'], function() {
		Route::get('promo_code', 'PromocodeController@index');
		Route::match(array('GET', 'POST'), 'add_promo_code', 'PromocodeController@add');
		Route::match(array('GET', 'POST'), 'edit_promo_code/{id}', 'PromocodeController@update')->where('id', '[0-9]+');
		Route::get('delete_promo_code/{id}', 'PromocodeController@delete');
	});

	// Payments
	Route::group(['middleware' =>  'admin_can:manage_payments'], function() {
		Route::match(array('GET', 'POST'), 'payments', 'PaymentsController@index');
		Route::get('view_payments/{id}', 'PaymentsController@view');
		Route::get('payments/export/{from}/{to}', 'PaymentsController@export');
	});

	// Cancelled Trips
	Route::group(['middleware' =>  'admin_can:manage_cancel_trips'], function() {
		Route::get('cancel_trips', 'TripsController@cancel_trips');
	});

	// Manage Cancel reasons
	Route::get('cancel-reason', 'CancelReasonController@index')->middleware('admin_can:view_manage_reason');
	Route::match(array('GET', 'POST'), 'add-cancel-reason', 'CancelReasonController@add')->middleware('admin_can:create_manage_reason');
	Route::match(array('GET', 'POST'), 'edit-cancel-reason/{id}', 'CancelReasonController@update')->where('id', '[0-9]+')->middleware('admin_can:update_manage_reason');
	Route::get('delete-cancel-reason/{id}', 'CancelReasonController@delete')->middleware('admin_can:delete_manage_reason');

	// Manage Rating
	Route::group(['middleware' =>  'admin_can:manage_rating'], function() {
		Route::get('rating', 'RatingController@index');
		Route::get('delete_rating/{id}', 'RatingController@delete');
	});

	// Manage fees
	Route::group(['middleware' =>  'admin_can:manage_fees'], function() {
		Route::match(array('GET', 'POST'), 'fees', 'FeesController@index');
	});

	// Manage Referral Settings
	Route::group(['middleware' =>  'admin_can:manage_referral_settings'], function() {
		Route::get('referral_settings', 'ReferralSettingsController@index');
		Route::post('update_referral_settings', 'ReferralSettingsController@update');
	});

	// SiteSetting
	Route::match(array('GET', 'POST'), 'site_setting', 'SiteSettingsController@index')->middleware('admin_can:manage_site_settings');
	
	// Manage Api credentials
	Route::match(array('GET', 'POST'), 'api_credentials', 'ApiCredentialsController@index')->middleware('admin_can:manage_api_credentials');

	// Manage Payment Gateway
	Route::group(['middleware' =>  'admin_can:manage_payment_gateway'], function() {
		Route::match(array('GET', 'POST'), 'payment_gateway', 'PaymentGatewayController@index');
	});

	// Request
	Route::group(['middleware' =>  'admin_can:manage_requests'], function() {
		Route::get('detail_request/{id}', 'RequestController@detail_request');
		Route::match(array('GET', 'POST'), 'request', 'RequestController@index');
	});

	// Join us management
	Route::group(['middleware' =>  'admin_can:manage_join_us'], function() {
		Route::match(array('GET', 'POST'), 'join_us', 'JoinUsController@index');
	});

	// Manage Static pages
	Route::group(['middleware' =>  'admin_can:manage_static_pages'], function() {
		Route::get('pages', 'PagesController@index');
		Route::match(array('GET', 'POST'), 'add_page', 'PagesController@add');
		Route::match(array('GET', 'POST'), 'edit_page/{id}', 'PagesController@update')->where('id', '[0-9]+');
		Route::get('delete_page/{id}', 'PagesController@delete')->where('id', '[0-9]+');
	});

	// Manage Meta
	Route::group(['middleware' =>  'admin_can:manage_metas'], function() {
		Route::match(array('GET', 'POST'), 'metas', 'MetasController@index');
		Route::match(array('GET', 'POST'), 'edit_meta/{id}', 'MetasController@update')->where('id', '[0-9]+');
	});

	// Manage Currency Routes
	Route::group(['middleware' =>  'admin_can:manage_currency'], function() {
		Route::get('currency', 'CurrencyController@index');
		Route::match(array('GET', 'POST'), 'add_currency', 'CurrencyController@add');
		Route::match(array('GET', 'POST'), 'edit_currency/{id}', 'CurrencyController@update')->where('id', '[0-9]+');
		Route::get('delete_currency/{id}', 'CurrencyController@delete')->where('id', '[0-9]+');
	});

	// Manage Document Routes
	Route::get('documents', 'DocumentsController@index')->middleware('admin_can:view_documents');
	Route::match(array('GET', 'POST'), 'add_document', 'DocumentsController@add')->middleware('admin_can:create_documents');
	Route::get('edit_document/{id}', 'DocumentsController@edit')->where('id', '[0-9]+')->middleware('admin_can:update_documents');
	Route::get('delete_document/{id}', 'DocumentsController@delete')->where('id', '[0-9]+')->middleware('admin_can:delete_documents');

	// Manage Language Routes
	Route::group(['middleware' =>  'admin_can:manage_language'], function() {
		Route::get('language', 'LanguageController@index');
		Route::match(array('GET', 'POST'), 'add_language', 'LanguageController@add');
		Route::match(array('GET', 'POST'), 'edit_language/{id}', 'LanguageController@update')->where('id', '[0-9]+');
		Route::get('delete_language/{id}', 'LanguageController@delete')->where('id', '[0-9]+');
	});

	// Manage Country
	Route::group(['middleware' => 'admin_can:manage_country'],function () {
        Route::get('country', 'CountryController@index');
        Route::match(array('GET', 'POST'), 'add_country', 'CountryController@add');
        Route::match(array('GET', 'POST'), 'edit_country/{id}', 'CountryController@update')->where('id', '[0-9]+');
        Route::get('delete_country/{id}', 'CountryController@delete')->where('id', '[0-9]+');
    });

	// Manual Booking
    Route::group(['middleware' => 'admin_can:manage_manual_booking'],function () {
        Route::get('manual_booking/{id?}', 'ManualBookingController@index');
        Route::post('manual_booking/store', 'ManualBookingController@store');
        Route::post('search_phone', 'ManualBookingController@search_phone');
        Route::post('search_cars', 'ManualBookingController@search_cars');
        Route::post('get_driver', 'ManualBookingController@get_driver');
        Route::post('driver_list', 'ManualBookingController@driver_list');
        Route::get('later_booking', 'LaterBookingController@index');
        Route::post('immediate_request', 'LaterBookingController@immediate_request');
        Route::post('manual_booking/cancel', 'LaterBookingController@cancel');
    });
	
	// Manage Email Settings Routes
	Route::match(array('GET', 'POST'), 'email_settings', 'EmailController@index')->middleware(['admin_can:manage_email_settings']);
    Route::match(array('GET', 'POST'), 'send_email', 'EmailController@send_email')->middleware(['admin_can:manage_send_email']);

    // Manage Make  Vehicle reasons
	Route::get('vehicle_make', 'MakeVehicleController@index')->middleware('admin_can:view_vehicle_make');
	Route::match(array('GET', 'POST'), 'add-vehicle-make', 'MakeVehicleController@add')->middleware('admin_can:create_vehicle_make');
	Route::match(array('GET', 'POST'), 'edit-vehicle-make/{id}', 'MakeVehicleController@update')->where('id', '[0-9]+')->middleware('admin_can:update_vehicle_make');
	Route::get('delete-vehicle_make/{id}', 'MakeVehicleController@delete')->middleware('admin_can:delete_vehicle_make');

	Route::get('vehicle_model', 'VehicleModelController@index')->middleware('admin_can:view_vehicle_model');
	Route::match(array('GET', 'POST'), 'add-vehicle_model', 'VehicleModelController@add')->middleware('admin_can:create_vehicle_model');
	Route::match(array('GET', 'POST'), 'edit-vehicle_model/{id}', 'VehicleModelController@update')->where('id', '[0-9]+')->middleware('admin_can:update_vehicle_model');
	Route::get('delete_vehicle_model/{id}', 'VehicleModelController@delete')->middleware('admin_can:delete_vehicle_make');
	Route::post('makelist','VehicleModelController@makeListValue');

});
