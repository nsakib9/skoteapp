<?php

/**
 * Token Auth Controller
 *
 * @package     Gofer
 * @subpackage  Controller
 * @category    Token Auth
 * @author      Trioangle Product Team
 * @version     2.2.1
 * @link        http://trioangle.com
 */

namespace App\Http\Controllers\Api;
 
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\ProfilePicture;
use App\Models\DriverLocation;
use App\Models\DriverAddress;
use App\Models\CarType;
use App\Models\Currency;
use App\Models\Trips;
use App\Models\Language;
use App\Models\Country;
use App\Models\PaymentMethod;
use App\Models\Request as RideRequest;
use Validator;
use Session;
use App;
use JWTAuth;
use Auth;
use Log;

class TokenAuthController extends Controller
{
    /**
     * Constructor
     * 
     */
    public function __construct()
    {
        $this->request_helper = resolve('App\Http\Helper\RequestHelper');
    }

    public function pickup_point(Request $request){
        $rules = array(
            'pickup_latitude'   => 'required',
            'pickup_longitude'  => 'required',
        );

        $validator = Validator::make($request->all(), $rules);

        if($validator->fails()) {
            return response()->json([
                'status_code' => '0',
                'status_message' => $validator->messages()->first()
            ]);
        }

        return response()->json([
            'status_code' => '1',
            'status_message' => 'listed successfully',
            'select_type' => 'house',
            "select_array"=> [
                array("id"=> 11613, "name"=> "Gate 1"),
                array("id"=> 11613, "name"=> "Gate 1")
            ],
        ]);
    }

    /**
     * Get User Details
     * 
     * @param Collection User
     *
     * @return Response Array
     */
    protected function getUserDetails($user)
    {
        $invoice_helper = resolve('App\Http\Helper\InvoiceHelper');
        $promo_details = $invoice_helper->getUserPromoDetails($user->id);

        $user_data = array(
            'user_id'           => $user->id,
            'first_name'        => $user->first_name,
            'last_name'         => $user->last_name,
            'mobile_number'     => $user->mobile_number,
            'country_code'      => $user->country_code,
            'email_id'          => $user->email ?? '',
            'user_status'       => $user->status,
            'user_thumb_image'  => @$user->profile_picture->src ?? url('images/user.jpeg'),
            'currency_symbol'   => $user->currency->symbol,
            'currency_code'     => $user->currency->code,
            'payout_id'         => $user->payout_id ?? '',
            'wallet_amount'     => getUserWalletAmount($user->id),
            'promo_details'     => $promo_details,
        );

        // Also sent for rider because mobile team also handle these parameters in rider

        $rider_details = array();
        if($user->user_type == 'Rider' || true) {
            $user->load('rider_location');
            $rider_location = $user->rider_location;
            $rider_details = array(
                'home'          => optional($rider_location)->home ?? '',
                'work'          => optional($rider_location)->work ?? '',
                'home_latitude' => optional($rider_location)->home_latitude ?? '',
                'home_longitude'=> optional($rider_location)->home_longitude ?? '',
                'work_latitude' => optional($rider_location)->work_latitude ?? '',
                'work_longitude'=> optional($rider_location)->work_longitude ?? '',
            );
        }

        $driver_details = array();
        if($user->user_type == 'Driver' || true) {
            $user->load(['driver_documents','driver_address']);
            $driver_documents = $user->driver_documents;
            $driver_address = $user->driver_address;
            $driver_details = array(
                'car_details'       => CarType::active()->get(),
                'license_front'     => optional($driver_documents)->license_front ?? '',
                'license_back'      => optional($driver_documents)->license_back ?? '',
                'insurance'         => optional($driver_documents)->insurance ?? '',
                'rc'                => optional($driver_documents)->rc ?? '',
                'permit'            => optional($driver_documents)->permit ?? '',
                'vehicle_id'        => optional($driver_documents)->vehicle_id ?? '',
                'vehicle_type'      => optional($driver_documents)->vehicle_type ?? '',
                'vehicle_number'    => optional($driver_documents)->vehicle_number ?? '',
                'address_line1'     => optional($driver_address)->address_line1 ?? '',
                'address_line2'     => optional($driver_address)->address_line2 ?? '',
                'state'             => optional($driver_address)->state ?? '',
                'postal_code'       => optional($driver_address)->postal_code ?? '',
                'company_name'      => $user->company_name,
                'company_id'        => $user->company_id ?? '',
            );
        }

        return array_merge($user_data,$rider_details,$driver_details);
    }
 
    /**
     * User Resister
     *@param  Get method request inputs
     *
     * @return Response Json 
     */
    public function register(Request $request) 
    {
        $language = $request->language ?? 'en';
        App::setLocale($language);

        try {
            $auth_method = "App\Services\Auth\AuthVia".ucfirst($request->auth_type);
            $auth_service = resolve($auth_method);
        }
        catch (\Exception $e) {
            $auth_service = resolve("App\Services\Auth\AuthViaEmail");            
        }

        $validate = $auth_service->validate($request);

        if($validate) {
            return $validate;
        }

        $user = $auth_service->create($request);
        $request['country_id'] = $user->country_id;
        $credentials = $request->only('mobile_number','country_id', 'password','user_type');
        
        $return_data = $auth_service->login($credentials);
        if(!isset($return_data['status_code'])) {
            return $return_data;
        }

        $user_data = $this->getUserDetails($user);

        return response()->json(array_merge($return_data,$user_data));
    }

    /**
     * User Socail media Resister & Login 
     * @param Get method request inputs
     *
     * @return Response Json 
     */
    public function apple_callback(Request $request) 
    {
        $client_id = api_credentials('service_id','Apple');

        $client_secret = getAppleClientSecret();

        $params = array(
            'grant_type' 	=> 'authorization_code',
            'code' 		 	=> $request->code,
            'redirect_uri'  => url('api/apple_callback'),
            'client_id' 	=> $client_id,
            'client_secret' => $client_secret,
        );
        
        $curl_result = curlPost("https://appleid.apple.com/auth/token",$params);

        if(!isset($curl_result['id_token'])) {
            $return_data = array(
                'status_code'       => '0',
                'status_message'    => $curl_result['error'],
            );

            return response()->json($return_data);
        }

        $claims = explode('.', $curl_result['id_token'])[1];
        $user_data = json_decode(base64_decode($claims));

        $user = User::where('apple_id', $user_data->sub)->first();

        if($user == '') {
            $return_data = array(
                'status_code'       => '1',
                'status_message'    => 'New User',
                'email_id'          => optional($user_data)->email ?? '',
                'apple_id'          => $user_data->sub,
            );

            return response()->json($return_data);
        }

        $token = JWTAuth::fromUser($user);

        $user_details = $this->getUserDetails($user);

        $return_data = array(
            'status_code'       => '2',
            'status_message'    => 'Login Successfully',
            'apple_email'       => optional($user_data)->email ?? '',
            'apple_id'          => $user_data->sub,
            'access_token'      => $token,
        );

        return response()->json(array_merge($return_data,$user_details));
    }

    /**
     * User Socail media Resister & Login 
     * @param Get method request inputs
     *
     * @return Response Json 
     */
    public function socialsignup(Request $request) 
    {
        $rules = array(
            'auth_type'   => 'required|in:facebook,google,apple',
            'auth_id'     => 'required',
        );

        $validator = Validator::make($request->all(), $rules);

        if($validator->fails()) {
            return response()->json([
                'status_code' => '0',
                'status_message' => $validator->messages()->first()
            ]);
        }

        if($request->auth_type == 'facebook') {
            $auth_column = 'fb_id';
        }
        else if($request->auth_type == 'google') {
            $auth_column = 'google_id';
        }
        else {
            $auth_column = 'apple_id';
        }

        $user_count = User::where($auth_column,$request->auth_id)->count();

        // Social Login Flow
        if($user_count == 0) {
            return response()->json([
                'status_code'   => '2',
                'status_message'=> 'New User',
            ]);
        }

        $rules =  array(
            'device_type'  =>'required',
            'device_id'    =>'required'
        );

        $messages = array('required'=>':attribute is required.');
        $validator = Validator::make($request->all(), $rules, $messages);
        if($validator->fails()) {
            return response()->json([
                'status_code' => '0',
                'status_message' => $validator->messages()->first()
            ]);
        }

        $user = User::where($auth_column,$request->auth_id)->first();

        $user->device_id    = $request->device_id;
        $user->device_type  = $request->device_type;
        $user->language     = $request->language;

        $user->currency_code= get_currency_from_ip();
        $user->save();

        $token = JWTAuth::fromUser($user);

        $return_data = array(
            'status_code'       => '1',
            'status_message'    => 'Login Success',
            'access_token'      => $token,
        );

        $user_data = $this->getUserDetails($user);

        return response()->json(array_merge($return_data,$user_data));
    }

    /**
     * User Login
     * @param  Get method request inputs
     *
     * @return Response Json 
     */
    public function login(Request $request)
    {
        // NOTE: Added 01/12/2020.
        //
        // Login from the iOS and Android apps has historically happened over
        // GET. This is not ideal because the params (password, in particular)
        // are sent in the URL query string. Instead, login requests should
        // happen over POST with the data in the request body, where it is less
        // likely to be included in logs, intercepted by MITM, etc.
        //
        // In order to transition apps to login over POST, we'll accept both
        // GET and POST, log the GET requests, and remove GET when no one is
        // hitting it anymore for a while.
        //
        // Revisit this in a few months and consider removing GET in
        // `routes/api.php`. Check rollbar for occurrences.
        if ($request->isMethod('get')) {
            Log::warning('Login over GET', [
                'user_agent' => $request->header('user-agent'),
                'mobile_number' => $request->mobile_number,
            ]);
        }

        $user_id = $request->mobile_number;
        $auth_column = 'mobile_number';

        $rules = array(
            'mobile_number'   =>'required|regex:/^[0-9]+$/|min:6',
            'user_type'       =>'required|in:Rider,Driver,rider,driver',
            'password'        =>'required',
            'country_code'    =>'required',
            'device_type'     =>'required',
            'device_id'       =>'required',
        );

        $validator = Validator::make($request->all(), $rules); 

        if($validator->fails()) {
            return response()->json([
                'status_code' => '0',
                'status_message' => $validator->messages()->first()
            ]);
        }

        $language = $request->language ?? 'en';
        App::setLocale($language);

        $country_id = Country::whereShortName($request->country_code)->value('id');
        $attempt = Auth::attempt([$auth_column=>$user_id, 'password'=>$request->password, 'user_type'=>$request->user_type,'country_id'=>$country_id]);

        if(!$attempt) {
            return response()->json([
                'status_code'    => '0',
                'status_message' => __('messages.credentials'),
            ]);
        }

        $credentials = $request->only($auth_column,'password','user_type');
        $credentials['country_id'] = $country_id;

        try {
            $token = JWTAuth::attempt($credentials);
            if(!$token) {
                return response()->json([
                    'status_code'    => '0',
                    'status_message' => __('messages.credentials'),
                ]);
            }

        } catch (JWTException $e) {
            return response()->json([
                'status_code'    => '0',
                'status_message' => 'could_not_create_token',
            ]);
        }

        $user = User::with('company')->where($auth_column, $user_id)->whereUserType($request->user_type)->whereCountryId($country_id)->first();

        if($user->status == 'Inactive') {
            return response()->json([
                'status_code'     => '0',
                'status_message' => __('messages.inactive_admin'),
           ]);
        }

        if(isset($user->company) && $user->company->status == 'Inactive') {
            return response()->json([
                'status_code'     => '0',
                'status_message' => __('messages.inactive_company'),
           ]);
        }

        $currency_code = get_currency_from_ip();
        User::whereId($user->id)->update([
            'device_id'     => $request->device_id,
            'device_type'   => $request->device_type,
            'currency_code' => $currency_code,
            'language' => $request->language
        ]);

        $user = User::where('id', $user->id)->first();
        auth()->setUser($user);

        if(strtolower($request->user_type) != 'rider') {
            $first_car = CarType::active()->first();

            $data['user_id'] = $user->id;
            $data['status'] = 'Offline';

            if(isset(optional($user->driver_documents)->vehicle_id) && optional($user->driver_documents)->vehicle_id) {
                $car_id = optional($user->driver_documents)->vehicle_id;
                $car_id = explode(',', $car_id);
                $car_id = $car_id[0];
            } else {
                $car_id = $first_car->id;
            }
            
            $data['car_id'] = $car_id;

            DriverLocation::updateOrCreate(['user_id' => $user->id], $data);
            RideRequest::where('driver_id',$user->id)->where('status','Pending')->update(['status'=>'Cancelled']);
        }

        $language = $user->language ?? 'en';
        App::setLocale($language);

        $return_data = array(
            'status_code'       => '1',
            'status_message'    => __('messages.login_success'),
            'access_token'      => $token,
        );

        $user = $this->getUserDetails($user);
    
        return response()->json(array_merge($return_data,$user));   
    }

    public function language(Request $request)
    {
        $user_details = JWTAuth::parseToken()->authenticate();

        $user= User::find($user_details->id);

        if($user == '') {
            return response()->json([
                'status_code'    => '0',
                'status_message' => __('messages.invalid_credentials'),
            ]);
        }
        $user->language = $request->language;
        $user->save();

        $language = $user->language ?? 'en';

        App::setLocale($language);

        return response()->json([
            'status_code'       => '1',
            'status_message'    => trans('messages.update_success'),
        ]);
    }
    
     /**
     * User Email Validation
     *
     * @return Response in Json
     */
    public function emailvalidation(Request $request)
    {
        $rules = array('email'=> 'required|max:255|email_id|unique:users');

        // Email signup validation custom messages
        $messages = array('required'=>':attribute is required.');

        $validator = Validator::make($request->all(), $rules, $messages);

        if($validator->fails()) {
            return response()->json([
                'status_code'   => '0',
                'status_message'=> 'Email Already exist',
            ]);
        }

        return response()->json([
            'status_code'   => '1',
            'status_message'=> 'Email validation Success',
        ]);
    }

    /**
     * Forgot Password
     * 
     * @return Response in Json
     */ 
    public function forgotpassword(Request $request)
    {
        $rules = array(
            'mobile_number'   => 'required|regex:/^[0-9]+$/|min:6',
            'user_type'       =>'required|in:Rider,Driver,rider,driver',
            'password'        =>'required|min:6',
            'country_code'    =>'required',
            'device_type'     =>'required',
            'device_id'       =>'required'
        );
        $attributes = array(
            'mobile_number'   => 'Mobile Number',
        );

        $validator = Validator::make($request->all(), $rules, $attributes);

        if($validator->fails()) {
            return response()->json([
                'status_code' => '0',
                'status_message' => $validator->messages()->first()
            ]);
        }

        $country_id = Country::whereShortName($request->country_code)->value('id');
        $user_check = User::where('mobile_number', $request->mobile_number)->where('user_type', $request->user_type)->whereCountryId($country_id)->first();
        
        if($user_check == '') {
            return response()->json([
                'status_code'    => '0',
                'status_message' => __('messages.invalid_credentials'),
            ]);
        }

        $user = User::whereId($user_check->id)->first();
        $user->password = $request->password;
        $user->device_id = $request->device_id;
        $user->device_type = $request->device_type;
        $user->currency_code = $request->currency_code;
        $user->save();

        $user = User::where('mobile_number', $request->mobile_number)->where('user_type', $request->user_type)->first();

        $token = JWTAuth::fromUser($user);

        auth()->setUser($user);

        if(strtolower($request->user_type) != 'rider') {
            $car_id = optional($user->driver_documents)->vehicle_id ?? CarType::active()->first()->id;
            if ($car_id) {
                DriverLocation::updateOrCreate(['user_id' => $user->id], [
                    'status'   => 'Offline',
                    'car_id'   => $car_id,
                ]);
            }
            RideRequest::where('driver_id',$user->id)->where('status','Pending')->update(['status'=>'Cancelled']);
        }

        $return_data = array(
            'status_code'       => '1',
            'status_message'    => __('messages.login_success'),
            'access_token'      => $token,
        );

        $user_data =$this->getUserDetails($user);
        
        return response()->json(array_merge($return_data,$user_data));
    }

    /**
     * Mobile number verification
     * 
     * @return Response in Json
     */ 
    public function numbervalidation(Request $request)
    {
        if(isset($request->language)) {
            $language = $request->language;
        }
        else {
            $language = 'en';
        }
        App::setLocale($language);

        $rules = array(
            'mobile_number'   => 'required|regex:/^[0-9]+$/|min:6',
            'user_type'       =>'required|in:Rider,Driver,rider,driver',
            'country_code'    =>'required',
        );

        if($request->forgotpassword==1) {
            $rules['mobile_number'] = 'required|regex:/^[0-9]+$/|min:6|exists:users,mobile_number';
        }

        $messages = array(
            'mobile_number.required' => trans('messages.mobile_num_required'),
            'mobile_number.exists'   => trans('messages.enter_registered_number'),
        );

        $validator = Validator::make($request->all(), $rules,$messages);
      
        if($validator->fails()) {
            return response()->json([
                'status_code' => '0',
                'status_message' => $validator->messages()->first()
            ]);
        }

        $mobile_number = $request->mobile_number;

        $country_id = Country::whereShortName($request->country_code)->value('id');

        $user = User::where('mobile_number', $mobile_number)->where('user_type', $request->user_type)->whereCountryId($country_id)->get();
        if($user->count() && $request->forgotpassword != 1) {
            return response()->json([
                'status_message'  => trans('messages.mobile_number_exist'),
                'status_code'     => '0',
            ]);
        }

        if($user->count() <= 0 && $request->forgotpassword == 1) {
            return response()->json([
                'status_message'  => trans('messages.number_does_not_exists'),
                'status_code'     => '0',
            ]);
        }

        $otp = rand(1000,9999);
        $text = __('messages.api.your_otp_is').$otp;
        $to = '+'.$request->country_code.$request->mobile_number;
        $sms_gateway = resolve("App\Contracts\SMSInterface");
        $sms_responce = $sms_gateway->send($to,$text);

        if($sms_responce['status_code'] == 0) {
            return response()->json([
                'status_message' => $sms_responce['message'],
                'status_code' => '0',
                'otp' => '',
            ]);
        }

        return response()->json([
            'status_code'    => '1',
            'status_message' => 'Success',
            'otp'           => strval($otp),
        ]);
    }

    /**
     * Updat Device ID and Device Type
     * @param  Get method request inputs
     *
     * @return Response Json 
     */
    public function updateDevice(Request $request)
    {
        $user_details = JWTAuth::parseToken()->authenticate();

        $rules = array(
            'user_type'    =>'required|in:Rider,Driver,rider,driver',
            'device_type'  =>'required',
            'device_id'    =>'required'
        );
        $attributes = array(
            'mobile_number'   => 'Mobile Number',
        );
        $validator = Validator::make($request->all(), $rules, $attributes);

        if($validator->fails()) {
            return response()->json([
                'status_code' => '0',
                'status_message' => $validator->messages()->first()
            ]);
        }

        $user = User::where('id', $user_details->id)->first();

        if($user == '') {
            return response()->json([
                'status_code'       => '0',
                'status_message'    => trans('messages.api.invalid_credentials'),
            ]);
        }

        User::whereId($user_details->id)->update(['device_id'=>$request->device_id,'device_type'=>$request->device_type]);                
        return response()->json([
            'status_code'     => '1',
            'status_message'  => __('messages.api.updated_successfully'),
        ]);
    }

    public function logout(Request $request)
    {
        $user_details = JWTAuth::parseToken()->authenticate();

        $user = User::where('id', $user_details->id)->first();

        if($user == '') {
            return response()->json([
                'status_code'       => '0',
                'status_message'    => __('messages.api.invalid_credentials'),
            ]);
        }

        if($user->user_type == 'Driver') {

            $trips_count = Trips::where('driver_id',$user_details->id)->whereNotIn('status',['Completed','Cancelled'])->count();

            $driver_location = DriverLocation::where('user_id',$user_details->id)->first();

            if(optional($driver_location)->status == 'Trip' || $trips_count > 0) {
                return response()->json([
                    'status_code'    => '0',
                    'status_message' => __('messages.complete_your_trips'),
                ]); 
            }

            DriverLocation::where('user_id',$user_details->id)->update(['status'=>'Offline']);
            JWTAuth::invalidate($request->token);
            Session::flush();

            $user->device_type = Null;
            $user->device_id = '';
            $user->save();
            
            return response()->json([
                'status_code'     => '1',
                'status_message'  => "Logout Successfully",
            ]); 
        }

        $trips_count = Trips::where('user_id',$user_details->id)->whereNotIn('status',['Completed','Cancelled'])->count();
        if($trips_count) {
            return response()->json([
              'status_code'    => '0',
              'status_message' => __('messages.complete_your_trips'),
            ]);
        }
        //Deactive the Access Token
        JWTAuth::invalidate($request->token);

        Session::flush();

        $user->device_type = Null;
        $user->device_id = '';
        $user->save();

        return response()->json([
            'status_code'     => '1',
            'status_message'  => "Logout Successfully",
        ]);
    }

    public function currency_conversion(Request $request)
    {
        $user_details   = JWTAuth::parseToken()->authenticate();

        $payment_methods = collect(PAYMENT_METHODS);
        $payment_methods = $payment_methods->reject(function($value) {
            $is_enabled = payment_gateway('is_enabled',ucfirst($value['key']));
            return ($is_enabled != '1');
        });
        $payment_types = $payment_methods->pluck('key')->implode(',');

        $request['payment_type'] = strtolower($request->payment_type);

        $rules  = [
            'amount' => 'required|numeric|min:0',
            'payment_type'  => 'required|in:'.$payment_types,
        ];

        $validator = Validator::make($request->all(), $rules);
        if($validator->fails()) {
            return response()->json([
                'status_code' => '0',
                'status_message' => $validator->messages()->first()
            ]);
        }

        $currency_code  = $user_details->currency->code;
        $payment_currency = site_settings('payment_currency');

        $price = floatval($request->amount);

        $converted_amount = currencyConvert($currency_code,$payment_currency,$price);

        $gateway = ($request->payment_type == "braintree") ?resolve('braintree') : resolve('braintree_paypal');

        $customer_id = $user_details->id.$user_details->mobile_number;
        try {
            $customer = $gateway->customer()->find($customer_id);
        }
        catch(\Exception $e) {
            try {
                $newCustomer = $gateway->customer()->create([
                    'id'        => $customer_id,
                    'firstName' => $user_details->first_name,
                    'lastName'  => $user_details->last_name,
                    'email'     => $user_details->email,
                    'phone'     => $user_details->phone_number,
                ]);

                if(!$newCustomer->success) {
                    return response()->json([
                        'status_code' => '0',
                        'status_message' => $newCustomer->message,
                    ]);
                }
                $customer = $newCustomer->customer;
            }
            catch(\Exception $e) {
                if($e instanceOf \Braintree\Exception\Authentication) {
                    return response()->json([
                        'status_code' => '0',
                        'status_message' => __('messages.api.authentication_failed'),
                    ]);
                }
                return response()->json([
                    'status_code' => '0',
                    'status_message' => $e->getMessage(),
                ]);
            }
        }

        $bt_clientToken = $gateway->clientToken()->generate([
            "customerId" => $customer->id
        ]);

        return response()->json([
            'status_code'    => '1',
            'status_message' => 'Amount converted successfully',
            'currency_code'  => $payment_currency,
            'amount'         => $converted_amount,
            'braintree_clientToken' => $bt_clientToken,
        ]);
    }

    public function getSessionOrDefaultCode()
    {
        $currency_code = Currency::defaultCurrency()->first()->code;
    }

    public function currency_list() 
    {
        $currency_list = Currency::active()->orderBy('code')->get();
        $curreny_list_keys = ['code', 'symbol'];

        $currency_list = $currency_list->map(function ($item, $key) use($curreny_list_keys) {
            return array_combine($curreny_list_keys, [$item->code, $item->symbol]);
        })->all();

        if(!empty($currency_list)) { 
            return response()->json([
                'status_message' => 'Currency Details Listed Successfully',
                'status_code'     => '1',
                'currency_list'   => $currency_list
            ]);
        }
        return response()->json([
            'status_code'     => '0',
            'status_message' => 'Currency Details Not Found',
        ]);
    }

    public function language_list() 
    {
        $languages = Language::active()->get();

        $languages = $languages->map(function ($item, $key)  {
            return $item->value;
        })->all();

        if(!empty($languages)) { 
            return response()->json([
                'status_code'   => '1',
                'status_message'=> 'Successfully',
                'language_list' => $languages,
            ]);
        }
        return response()->json([
            'status_code'     => '0',
            'status_message' => 'language Details Not Found',
        ]);
    }
}
