<?php

namespace App\Providers;

use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use App\Http\Helper\FacebookHelper;
use App\Models\Admin;
use App\Models\Setting;
use App\Models\Menu;
use App\Models\CustomCSS;
use App\Models\Footer;
use App\Models\CarType;
use App\Models\Currency;
use App\Models\Language;
use App\Models\SiteSettings;
use App\Models\EmailSettings;
use App\Models\ApiCredentials;
use App\Models\PaymentGateway;
use App\Models\Fees;
use App\Models\ReferralSetting;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Config;
use DB;
use Session;
use App;
use View;

class AppServiceProvider extends ServiceProvider
{
	/**
	 * Register any application services.
	 *
	 * @return void
	 */
	public function register()
	{
		foreach(glob(app_path() . '/Helpers/*.php') as $file) {
			require_once $file;
		}

		foreach(glob(app_path() . '/Constants/*.php') as $file) {
			require_once $file;
		}
	}

	/**
	 * Bootstrap any application services.
	 *
	 * @return void
	 */
	public function boot(UrlGenerator $url)
	{


		$setting = Setting::first();
		$menus = Menu::all();
		$customCSS = CustomCSS::all();
		$footer = Footer::first();
		View::share(['setting'=> $setting, 'menus'=> $menus, 'customCSS'=> $customCSS, 'footer'=> $footer]);

		if (\App::environment('production')) {
			$url->forceScheme('https');
		}

		$this->conditionallyDefine('EMAIL_LOGO_URL', url('images/logo.png'));
        $this->conditionallyDefine('LOGO_URL', url('images/logo.png'));
        $this->conditionallyDefine('PAGE_LOGO_URL', url('images/page_logo.png'));
        $this->conditionallyDefine('FAVICON_URL', url('images/favicon.png'));

		$this->conditionallyDefine('LOGIN_USER_TYPE', request()->segment(1));

		Schema::defaultStringLength(191);

        try {
            Schema::hasTable('site_settings');
            $database_exists = TRUE;
        } catch (QueryException $ex) {
            $database_exists = FALSE;
        }

		if ($database_exists) {
			$this->bindModels();
			$this->shareCommonData();

			// Configuration for data table pdf export
			config(['datatables-buttons.pdf_generator' => 'snappy']);

			if(!in_array(request()->segment(1),['admin','company','install']) && !\App::runningInConsole()) {
				Config::set(['cache.default' => 'file']);
			}

			if (Schema::hasTable('site_settings')) {
				$site_settings = DB::table('site_settings')->get();
				View::share('site_name', $site_settings[0]->value);
				$this->conditionallyDefine('SITE_NAME', $site_settings[0]->value);

				$this->conditionallyDefine('MANUAL_BOOK_CONTACT', '+'.$site_settings[9]->value.' '.$site_settings[8]->value);
                $this->conditionallyDefine(
                    'CAN_CONVERT_CURRENCY',
                    request()->segment(2) != 'view_trips' && request()->segment(1) != 'view_trips'
                );

				$this->conditionallyDefine('PAYPAL_CURRENCY_CODE', $site_settings[1]->value);
				$this->conditionallyDefine('PHP_DATE_FORMAT','Y-m-d');
				$this->conditionallyDefine('SITE_URL',$site_settings[10]->value);
				$this->conditionallyDefine('Driver_Km', $site_settings[6]->value);
			}
			if (Schema::hasTable('country')) {
				$country = DB::table('country')->get();
				View::share('country', $country);

			}
			if (Schema::hasTable('car_type')) {
				$car_type = CarType::where('status', 'Active')->get();
				View::share('car_type', $car_type);

			}

            if (Schema::hasTable('currency')) {
                $this->conditionallyDefine('DEFAULT_CURRENCY', Currency::defaultCurrency()->first()->code);
            }

			if (Schema::hasTable('api_credentials')) {

				$api_credentials = resolve('api_credentials');

				// For Google Key
				$google_map_result = $api_credentials->where('site', 'GoogleMap');
				$this->conditionallyDefine('MAP_KEY', $google_map_result->where('name','key')->first()->value);
				$this->conditionallyDefine('MAP_SERVER_KEY', $google_map_result->where('name','server_key')->first()->value);
				View::share('map_key', $google_map_result->where('name','key')->first()->value);

				//For facebook
				$facebook_result = $api_credentials->where('site', 'Facebook');
				$this->conditionallyDefine('FB_CLIENT_ID', $facebook_result->where('name','client_id')->first()->value);

				// Share Google Credentials
				$google_result =  $api_credentials->where('site','Google');
				$this->conditionallyDefine('GOOGLE_CLIENT_ID', $google_result->where('name','client_id')->first()->value);

				// For Twillo Key
				$twillo_result = $api_credentials->where('site', 'Twillo');

				$this->conditionallyDefine('TWILLO_SID', $twillo_result->where('name','sid')->first()->value);
				$this->conditionallyDefine('TWILLO_TOKEN', $twillo_result->where('name','token')->first()->value);
				$this->conditionallyDefine('TWILLO_FROM', $twillo_result->where('name','from')->first()->value);

				// For FCM Key
				$fcm_result = $api_credentials->where('site', 'FCM');
				Config::set(['fcm.http' => [
					'server_key' => $fcm_result->where('name','server_key')->first()->value,
					'sender_id' => $fcm_result->where('name','sender_id')->first()->value,
					'server_send_url' => 'https://fcm.googleapis.com/fcm/send',
					'server_group_url' => 'https://android.googleapis.com/gcm/notification',
					'timeout' => 10,
				],
				]);

				// For Facebook app id and secret
				$fb_result = $api_credentials->where('site', 'Facebook');
				Config::set([
					'facebook' => [
						'client_id' => $fb_result->where('name','client_id')->first()->value,
						'client_secret' => $fb_result->where('name','client_secret')->first()->value,
						'redirect' => url('/facebookAuthenticate'),
					],
				]);

				$fb = new FacebookHelper;
				View::share('fb_url', $fb->getUrlLogin());
				$this->conditionallyDefine('FB_URL', $fb->getUrlLogin());
			}
			if(Schema::hasTable('admin')) {
				$admin_email = Admin::first()->email;
				View::share('admin_email', $admin_email);
			}

			if (Schema::hasTable('payment_gateway')) {
				$this->setPaymentConfig();
			}

			// Configure Email settings from email_settings table
			if(Schema::hasTable('email_settings'))
			{
				$result = DB::table('email_settings')->get();

				Config::set([
					'mail.default' => email_settings('driver'),
					'mail.mailers.smtp.host' 		=> email_settings('host'),
					'mail.mailers.smtp.port'       	=> email_settings('port'),
					'mail.mailers.smtp.encryption' 	=> email_settings('encryption'),
					'mail.mailers.smtp.username'   	=> email_settings('username'),
					'mail.mailers.smtp.password'   	=> email_settings('password'),
					'mail.from' => [
						'address' => email_settings('from_address'),
						'name'    => email_settings('from_name')
					],
				]);

				if(email_settings('driver') == 'mailgun') {
					Config::set([
						'services.mailgun.domain'     => email_settings('domain'),
						'services.mailgun.secret'     => email_settings('secret'),
					]);
				}

				Config::set([
					'laravel-backup.notifications.mail.from' => email_settings('from_address'),
					'laravel-backup.notifications.mail.to'   => email_settings('from_address'),
				]);
			}
		}

		// Enable pagination
		if (!Collection::hasMacro('paginate')) {
			Collection::macro('paginate', function($perPage, $total = null, $page = null, $pageName = 'page') {
				$page = $page ?: LengthAwarePaginator::resolveCurrentPage($pageName);

				return new LengthAwarePaginator($this->forPage($page, $perPage), $total ?: $this->count(), $perPage, $page, [
					'path' => LengthAwarePaginator::resolveCurrentPath(),
					'pageName' => $pageName,
				]);
			});
		}

		// Append Array to laravel Collection through map
		if (!Collection::hasMacro('setAppends')) {
			Collection::macro('setAppends', function ($attributes) {
				return $this->map(function ($item) use ($attributes) {
					return $item->setAppends($attributes);
				});
			});
		}

		// Append Array to laravel Collection through transform
		if (!Collection::hasMacro('transformWithAppends')) {
			Collection::macro('transformWithAppends', function ($attributes) {
				return $this->transform(function ($item) use ($attributes) {
					foreach ($attributes as $attribute) {
						$item[$attribute] = $item->$attribute;
					}
					return $item;
				});
			});
		}

		// Custom Validation for File Extension
		\Validator::extend('valid_extensions', function($attribute, $value, $parameters) 
		{
			if(count($parameters) == 0) {
				return false;
			}
			$ext = strtolower($value->getClientOriginalExtension());

			return in_array($ext,$parameters);
		});
	}

	protected function bindModels()
	{
		if (Schema::hasTable('site_settings')) {
			$this->app->singleton('site_settings', function ($app) {
				$site_settings = SiteSettings::get();
				return $site_settings;
			});
		}

		if (Schema::hasTable('email_settings')) {
			$this->app->singleton('email_settings', function ($app) {
				$email_settings = EmailSettings::get();
				return $email_settings;
			});
		}

		if (Schema::hasTable('api_credentials')) {
			$this->app->singleton('api_credentials', function ($app) {
				$api_credentials = ApiCredentials::get();
				return $api_credentials;
			});
		}

		if (Schema::hasTable('payment_gateway')) {
			$this->app->singleton('payment_gateway', function ($app) {
				$payment_gateway = PaymentGateway::get();
				return $payment_gateway;
			});
		}

		if (Schema::hasTable('referral_settings')) {
			$this->app->singleton('referral_settings', function ($app) {
				$referral_settings = ReferralSetting::get();
				return $referral_settings;
			});
		}

		if (Schema::hasTable('fees')) {
			$this->app->singleton('fees', function ($app) {
				$fees = Fees::get();
				return $fees;
			});
		}

		if (Schema::hasTable('vehicle_type')) {
			$this->app->singleton('vehicle_type', function ($app) {
				$car_types = \App\Models\CarType::get();
				return $car_types;
			});
		}

		$this->app->bind('App\Contracts\ImageHandlerInterface','App\Services\ImageHandler');
		$this->app->bind('App\Contracts\SMSInterface','App\Services\SMS\TwillioSms');
	}

	protected function shareCommonData()
	{
		$acceptable_mimes = array(
			'image/jpeg',
			'image/jpg',
			'image/gif',
			'image/png',
		);

		View::share('acceptable_mimes',$acceptable_mimes);
	}

	protected function setPaymentConfig()
	{
		$paypal_mode  = payment_gateway('mode','Paypal');

		$this->conditionallyDefine('PAYPAL_ID', payment_gateway('mode','paypal_id'));
		$this->conditionallyDefine('PAYPAL_MODE', ($paypal_mode == 'sandbox') ? 0 : 1);
		$this->conditionallyDefine('PAYPAL_CLIENT_ID', payment_gateway('client','Paypal'));

		$this->conditionallyDefine('STRIPE_KEY', payment_gateway('publish','Stripe'));
		$this->conditionallyDefine('STRIPE_SECRET', payment_gateway('secret','Stripe'));

		$site_settings = resolve('site_settings');

		$this->app->bind('braintree', function($app) {
			$bt_env = payment_gateway('mode','Braintree');
			$bt_merchant_id = payment_gateway('merchant_id','Braintree');
			$bt_public_key = payment_gateway('public_key','Braintree');
			$bt_private_key = payment_gateway('private_key','Braintree');
			$config = new \Braintree_Configuration([
				'environment' => $bt_env,
				'merchantId' => $bt_merchant_id,
				'publicKey' => $bt_public_key,
				'privateKey' => $bt_private_key,
			]);

			return new \Braintree_Gateway($config);
		});

		$this->app->bind('braintree_paypal', function($app) {
			$access_token = payment_gateway('access_token','Paypal');
			$config = new \Braintree_Configuration([
				'accessToken' => $access_token,
			]);
			return new \Braintree_Gateway($config);
		});

		$this->app->bind('paypal', function($app) {
			$gateway = \Omnipay\Omnipay::create('PayPal_Rest');

			$gateway->initialize(array(
				'clientId' 	=> payment_gateway('client','Paypal'),
				'secret' 	=> payment_gateway('secret','Paypal'),
				'testMode' 	=> (payment_gateway('mode','Paypal') == 'sandbox'),
			));

			return $gateway;
		});

		$this->app->singleton('google_service', function($app) {
			$google_service = new \App\Services\GoogleAPIService;
			return $google_service;
		});
	}

	protected function conditionallyDefine($const, $value) {
		if (defined($const)) {
			return;
		}
		define($const, $value);
	}
}
