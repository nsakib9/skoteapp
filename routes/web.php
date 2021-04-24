<?php
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware gro up. Now create something great!
|
 */

Route::get('oweAmount', 'Api\RatingController@oweAmount');
Route::get('driver_invoice', 'DriverDashboardController@driver_invoice');
Route::match(array('GET', 'POST'),'apple_callback', 'UserController@apple_callback');
Route::get('app/{type}', 'HomeController@redirect_to_app')->name('redirect_to_app');

Route::group(['middleware' =>'locale'], function () {
	Route::get('/', 'HomeController@newindex');
	// Route::redirect('/', '/home');
    
    // Route::get('/', 'HomeController@index');
    // Route::get('/pricing', 'HomeController@pricing');
	// Route::get('/about', 'HomeController@about');
	Route::get('/contact', 'HomeController@contact');
	// Route::get('/model', 'HomeController@model');
	// Route::get('/front/signin', 'HomeController@signin');
	// Route::get('/front/signup', 'HomeController@signup');

});

Route::group(['middleware' =>'locale'], function () {
	Route::get('help', 'HomeController@help');
	Route::get('help/topic/{id}/{category}', 'HomeController@help');
	Route::get('help/article/{id}/{question}', 'HomeController@help');
	Route::get('ajax_help_search', 'HomeController@ajax_help_search');

	Route::post('set_session', 'HomeController@set_session');
	Route::get('user_disabled', 'UserController@user_disabled');

	Route::match(array('GET', 'POST'), 'signin_driver', 'UserController@signin_driver');
	Route::match(array('GET', 'POST'),'signin_rider', 'UserController@signin_rider')->name('rider.signin');
	Route::get('facebook_login', 'UserController@facebook_login');
	Route::get('forgot_password_driver', 'UserController@forgot_password');
	Route::get('forgot_password_rider', 'UserController@forgot_password');
	Route::post('forgotpassword', 'UserController@forgotpassword');
	Route::match(array('GET', 'POST'), 'reset_password', 'UserController@reset_password');
	Route::get('forgot_password_link/{id}', 'EmailController@forgot_password_link');
	Route::match(array('GET', 'POST'),'signup_rider', 'UserController@signup_rider');
	Route::match(array('GET', 'POST'),'signup_driver', 'UserController@signup_driver');

	Route::get('facebookAuthenticate', 'UserController@facebookAuthenticate');
	Route::get('googleAuthenticate', 'UserController@googleAuthenticate');

	Route::view('signin', 'user.signin');
	Route::view('signup', 'user.signup');

	Route::view('safety', 'ride.safety');
	Route::view('ride', 'ride.ride');


	Route::view('drive', 'drive.drive');
	Route::view('requirements', 'drive.requirements');
	Route::view('driver_app', 'drive.driver_app');
	Route::view('drive_safety', 'drive.drive_safety');

	// signup functionality
	Route::post('rider_register', 'UserController@rider_register');
	Route::post('driver_register', 'UserController@driver_register');
	Route::post('login', 'UserController@login');
	Route::post('login_driver', 'UserController@login_driver');
	Route::post('ajax_trips/{id}', 'DashboardController@ajax_trips');

	Route::post('change_mobile_number', 'DriverDashboardController@change_mobile_number');
	Route::post('profile_upload', 'DriverDashboardController@profile_upload');
	Route::get('download_invoice/{id}', 'DriverDashboardController@download_invoice');
	Route::get('download_rider_invoice/{id}', 'DashboardController@download_rider_invoice');
});

// Rider Routes..
Route::group(['middleware' => ['locale','rider_guest']], function () {
	Route::get('trip', 'DashboardController@trip')->name('rider.trips');
	Route::get('profile', 'DashboardController@profile');
	Route::view('payment', 'dashboard.payment');
	Route::get('trip_detail/{id}', 'DashboardController@trip_detail');
	Route::post('rider_rating/{rating}/{trip_id}', 'DashboardController@rider_rating');
	Route::post('trip_detail/rider_rating/{rating}/{trip_id}', 'DashboardController@rider_rating');
	Route::get('trip_invoice/{id}', 'DashboardController@trip_invoice');
	Route::get('invoice_download/{id}', 'DashboardController@invoice_download');
	Route::post('rider_update_profile/{id}', 'DashboardController@update_profile');
	Route::get('referral', 'DashboardController@referral')->name('referral');
	Route::post('ajax_referral_data/{id}', 'DashboardController@ajax_referral_data');
});

// Driver Routes..
Route::group(['middleware' => ['locale','driver_guest']], function () {
	Route::get('driver_profile', 'DriverDashboardController@driver_profile');
	Route::get('documents/{id}', 'DriverDashboardController@documents')->name('documents');
	Route::get('vehicle/{id}', 'DriverDashboardController@showvehicle')->name('vehicle');
	Route::post('driver_document','DriverDashboardController@driver_document_upload');
	Route::get('add_vehicle', 'DriverDashboardController@add_vehicle')->name('add_vehicle');
	Route::get('edit_vehicle/{id}', 'DriverDashboardController@edit_vehicle')->name('edit_vehicle');
	Route::get('delete_vehicle/{id}', 'DriverDashboardController@delete_vehicle');
	Route::get('default_vehicle/{id}', 'DriverDashboardController@default_vehicle');
	Route::post('makelist','DriverDashboardController@makeListValue');
	Route::post('update_vehicle','DriverDashboardController@update_vehicle');
	Route::get('driver_payment', 'DriverDashboardController@driver_payment');

	Route::get('driver_invoice/{id}', 'DriverDashboardController@driver_invoice');
	Route::view('driver_banking', 'driver_dashboard.driver_banking');
	Route::view('driver_trip', 'driver_dashboard.driver_trip');
	Route::get('driver_trip_detail/{id}', 'DriverDashboardController@driver_trip_detail');

	Route::post('ajax_payment', 'DriverDashboardController@ajax_payment');
	Route::get('driver_referral', 'DashboardController@driver_referral')->name('driver_referral');

	// profile update
	Route::post('driver_update_profile/{id}', 'DriverDashboardController@driver_update_profile');
	Route::get('driver_invoice', 'DriverDashboardController@show_invoice');
	Route::get('print_invoice/{id}', 'DriverDashboardController@print_invoice');

	// Payout Preferences
	Route::get('payout_preferences','UserController@payoutPreferences')->name('driver_payout_preference');
	Route::post('update_payout_preference','UserController@updatePayoutPreference')->name('update_payout_preference');
	Route::get('payout_delete/{id}', 'UserController@payoutDelete')->where('id', '[0-9]+')->name('payout_delete');
	Route::get('payout_default/{id}', 'UserController@payoutDefault')->where('id', '[0-9]+')->name('payout_default');
});

Route::get('sign_out', function () {
	$user_type = @Auth::user()->user_type;
	Auth::logout();
	if (@$user_type == 'Rider') {
		return redirect('signin_rider');
	} else {
		return redirect('signin_driver');
	}

});

// Static page route
Route::get('{name}', 'HomeController@static_pages');
