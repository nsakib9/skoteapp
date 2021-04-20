<?php

/**
 * OTP Helper
 *
 * @package     Gofer
 * @subpackage  Controller
 * @category    Helper
 * @author      Trioangle Product Team
 * @version     2.2.1
 * @link        http://trioangle.com
 */
namespace App\Http\Helper;

class OtpHelper
{
	/**
	 * Send OTP
	 *
	 * @param integer $country_code
	 * @param integer $mobile_number
	 * @return Array $response
	 */
	public function sendOtp($mobile_number,$country_code)
	{
		$otp = rand(1000,9999);
        $text = 'Your OTP number is '.$otp;
        $to = '+'.$country_code.$mobile_number;
        $sms_gateway = resolve("App\Contracts\SMSInterface");
        $response = $sms_gateway->send($to,$text);
        if ($response['status_code']==1) {
        	session([
				'signup_mobile' => $mobile_number,
				'signup_country_code' => $country_code,
				'signup_otp' => $otp,
			]);
        }
		return $response;
	}

	/**
	 * Resend OTP
	 *
	 * @return Array $response
	 */
	public function resendOtp()
	{
		$otp = rand(1000,9999);
        $text = 'Your OTP number is '.$otp;
        $to = '+'.session('signup_country_code').session('signup_mobile');
        $sms_gateway = resolve("App\Contracts\SMSInterface");
        $response = $sms_gateway->send($to,$text);

        if ($response['status_code']==1) {
            session(['signup_otp' => $otp]);
            $response['message'] = trans('messages.signup.otp_resended');
        }

		return $response;
	}

	/**
	 * Check Given OTP
	 *
	 * @param integer $otp
	 * @param integer $mobile_number
	 * @return Array $response
	 */
	public function checkOtp($otp,$mobile_number = null)
	{
		$data = ['status_code' => 0,'message'=>trans('messages.signup.wrong_otp')];
		if ($otp == session('signup_otp') && ($mobile_number==null || $mobile_number==session('signup_mobile'))) {
			$data = ['status_code' => 1,'message'=>'success'];
		}
		return $data;
	}
}