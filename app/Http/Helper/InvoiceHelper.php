<?php

/**
 * Invoice Helper
 *
 * @package     Gofer
 * @subpackage  Controller
 * @category    Invoice
 * @author      Trioangle Product Team
 * @version     2.2.1
 * @link        http://trioangle.com
 */
namespace App\Http\Helper;

use App\Http\Helper\RequestHelper;
use App\Models\User;
use App\Models\Trips;
use App\Models\Wallet;
use App\Models\DriverOweAmount;
use App\Repositories\DriverOweAmountRepository;
use App\Models\Company;
use App\Models\ScheduleRide;
use App\Models\UsersPromoCode;
use App\Models\ManageFare;
use App\Models\Fees;
use App\Models\ReferralUser;
use App\Models\Currency;
use App\Models\PoolTrip;
use App\Models\AdditionalTax;
use App\Models\AdditionalTaxTranslations;
use App\Models\TripAdditionalTax;
use App\Models\TripAdditionalTaxTranslations;
use App\Models\Request as RideRequest;
use App\Http\Start\Helpers;
use DateTime;
use DB;
use Str;
use Request;

class InvoiceHelper
{
	/**
	 * Constructor
	 *
	 */
	public function __construct(DriverOweAmountRepository $driver_owe_amt_repository)
	{
		$this->request_helper = new RequestHelper();
		$this->helper = new Helpers();
		$this->driver_owe_amt_repository = $driver_owe_amt_repository;
	}

	/**
	 * check given payment mode is Wallet or not
	 *
	 * @param String $payment_mode
	 * @return Boolean
	 */
	protected function checkIsWallet($payment_mode)
	{
		return Str::contains($payment_mode,'Wallet');
	}

	/**
	 * check given payment mode is Cash or not
	 *
	 * @param String $payment_mode
	 * @return Boolean
	 */
	// protected function checkIsCashTrip($payment_mode)
	// {
	// 	return Str::contains($payment_mode,'Cash');
	// }

	/**
	 * Calculate trip fare details
	 *
	 * @param Array $data
	 * @return $trip instanceOf \App\Models\Trips
	 */
	public function calculation($data)
	{
		$save = $data['save_to_trip_table'];

		$trips = Trips::where('id', $data['trip_id'])->first();
		$user = User::where('id', $data['user_id'])->first();

		//Time calculation
		$arrive_time = new DateTime($trips->arrive_time);
		$begin_time = new DateTime($trips->begin_trip);
		$end_time = new DateTime($trips->end_trip);
		$timeDiff = date_diff($begin_time, $end_time);

		$trip_hours = $timeDiff->format("%H");
		$trip_minute = $timeDiff->format("%I");

		$request_details = RideRequest::where('id',$trips->request_id)->first();

		$fare_details = ManageFare::where('location_id',$request_details->location_id)->where('vehicle_id',$trips->car_id)->first();

		// Waiting charge calculation
		$waiting_charge = 0;
		$waitingDiff = date_diff($arrive_time, $begin_time);
		$waitingMin = $waitingDiff->format("%I");
		if($waitingMin > $fare_details->waiting_time) {
			$waitingMin = $waitingDiff->format("%I") - $fare_details->waiting_time;
			$waiting_charge = $waitingMin * $fare_details->waiting_charge;
		}

		//total fare calculation
		$total_minute = ($trip_hours * 60) + $trip_minute;
		$total_km = $trips->total_km;
		if($trips->pool_id > 0 ) {
			$fare_estimation = $trips->fare_estimation;
			if ($fare_estimation['status'] == "success") {
	            if ($fare_estimation['distance'] != '') {
		            $total_minute = round(floor(round($fare_estimation['time'] / 60)));
		            $total_km = round(floor($fare_estimation['distance'] / 1000) . '.' . floor($fare_estimation['distance'] % 1000));
	            }
	        }
		}

		$trip_time_fare = number_format(($fare_details->per_min * $total_minute), 2, '.', '');
		$trip_km_fare = number_format(($fare_details->per_km * $total_km), 2, '.', '');
		$schedule_fare_amount = 0;

		if($request_details->schedule_id != '') {
			if($request_details->schedule_ride->booking_type != "Manual Booking") {
				$schedule_fare_amount = number_format($fare_details->schedule_fare, 2, '.', '');
			}
		}

		/* Standard fare */
		$trip_base_fare = $fare_details->base_fare;
		$driver_total_fare = $trip_total_fare = $subtotal_fare = number_format(($trip_base_fare + $trip_km_fare + $trip_time_fare), 2, '.', '');

		/* minimum fare */
		if($driver_total_fare < $fare_details->min_fare) {
			$trip_base_fare =  $fare_details->min_fare - ($trip_km_fare + $trip_time_fare);
			$driver_total_fare = $trip_total_fare =	$subtotal_fare = number_format(($trip_base_fare + $trip_km_fare + $trip_time_fare), 2, '.', '');
		}

		if($trips->cancellation_fee != 0.00){
			$driver_total_fare = 0.00;
			$trip_base_fare = 0.00;
			$trip_km_fare = 0.00;
			$trip_time_fare = 0.00;
			$subtotal_fare = 0.00;
			$trip_total_fare = 0.00;
			$waiting_charge = 0.00;
		}

		/* Peak fare */
		$peak_amount = 0;
		$driver_peak_amount = 0;

		if($trips->peak_fare != 0 && $trips->cancellation_fee == 0.00) {
			$trip_total_fare = $subtotal_fare * $trips->peak_fare;
			$peak_amount = $trip_total_fare - $subtotal_fare;

			$driver_per = fees('driver_peak_fare');
		    $driver_peak_amount = number_format(($driver_per / 100) *  $peak_amount , 2, '.', '');
		    $driver_total_fare = $subtotal_fare + $driver_peak_amount;
		}

		$additional_rider_amount = 0;
		if($request_details->seats > 1 ) {
			$additional_rider = $trips->additional_rider;
		    $additional_rider_amount = number_format(($additional_rider / 100) *  $trip_total_fare , 2, '.', '');
		    $trip_total_fare += $additional_rider_amount;
		    $driver_total_fare += $additional_rider_amount;
		}

		//for driver payout variable - total_trip_fare_for
		// access fee calculation

		$percentage = fees('access_fee');

		$access_fee = number_format(($percentage / 100) * $trip_total_fare, 2, '.', '');

		$black_car_fund_percentage = fees('black_car_fund');

		$black_car_fund = number_format(($black_car_fund_percentage / 100) * $trip_total_fare, 2, '.', '');

		$sales_tax_percentage = fees('sales_tax');
		$sales_tax = number_format(($sales_tax_percentage / 100) * $trip_total_fare, 2, '.', '');

		$deleteoldtac = TripAdditionalTaxTranslations::where('trip_id',$trips->id)->delete();
		$deleteoldtac = TripAdditionalTax::where('trip_id',$trips->id)->delete();

		$additional_tax = AdditionalTax::where('status','Active')->get();

		if(!empty($additional_tax)){
			foreach($additional_tax as $tax){
				$tax_percentage = $tax->value;
				$other_sales_tax = number_format(($tax_percentage / 100) * $trip_total_fare, 2, '.', '');

				$trip_additional_tax = new TripAdditionalTax;
				$trip_additional_tax->trip_id = $trips->id;
				$trip_additional_tax->tax_name = $tax->name;
				$trip_additional_tax->tax_value = $other_sales_tax;
				$trip_additional_tax->currency_code = $trips->getOriginal('currency_code');
				$trip_additional_tax->save();

				$additionaltaxtranslation = AdditionalTaxTranslations::where('additional_tax_id',$tax->id)->get();
				if(!empty($additionaltaxtranslation)){
					foreach($additionaltaxtranslation as $add_tax){
						$translation = new TripAdditionalTaxTranslations;
						$translation->trip_id = $trips->id;
						$translation->trip_additional_tax_id = $trip_additional_tax->id;
						$translation->name = $add_tax->name;
						$translation->locale = $add_tax->locale;
						$translation->save();
					}
				}
				$total_tax[] = $other_sales_tax;
			}
		}

		$trip_additional_tax = (!empty($total_tax)) ? array_sum($total_tax) : 0.00;

		$owe_amount = 0;
		$remaining_wallet = 0;
		$applied_wallet = 0;
		$promo_amount = 0;

		$trips = Trips::find($data['trip_id']);

		if($trips->is_calculation == 0) {

			$trip_total_fare = $trip_total_fare +  $access_fee + $black_car_fund + $sales_tax + $schedule_fare_amount + $trip_additional_tax;

			$total_fare = $trip_total_fare;

			$driver_payout = $driver_total_fare;

			$company_id = User::find($trips->driver_id);
			$company_id = @$company_id->company_id;

			if ($company_id == null || $company_id == 1) {
				$driver_service_fee_percentage = fees('driver_access_fee');
				$driver_or_company_commission = number_format(($driver_service_fee_percentage / 100) * $driver_total_fare, 2, '.', '');
			}
			else {
				$company_commission_percentage = Company::find($company_id)->company_commission;
				$driver_or_company_commission = number_format(($company_commission_percentage / 100) * $driver_total_fare, 2, '.', '');
			}
			$driver_total_fare = $driver_total_fare + $trips->tips + $trips->toll_fee + $waiting_charge + $trips->surcharge_amount;
			$driver_payout = $driver_total_fare-$driver_or_company_commission;

			$total_fare = $total_fare + $trips->tips + $trips->toll_fee + $waiting_charge + $data['cancellation_fee']  + $trips->surcharge_amount;

			//Apply promo code if promocode is available
			$promo_codes = UsersPromoCode::whereUserId($trips->user_id)->whereTripId(0)->with('promo_code_many')->whereHas('promo_code_many')->orderBy('created_at', 'asc')->first();
			if ($promo_codes) {
				if ($save == 1) {
					UsersPromoCode::whereId($promo_codes->id)->update(['trip_id' => $data['trip_id']]);
				}
				$promo_amount = $promo_codes->promo_code_many[0]->amount;
				if($promo_amount >= $total_fare) {
					$total_fare = '0';
				}
				else {
					$total_fare = $total_fare - $promo_amount;
				}
			}

			// Wallet Amount
			$wallet_amount = 0;
			$wallet = Wallet::whereUserId($trips->user_id)->first();

			if($wallet) {
				$wallet_amount = $wallet->original_amount;
			}

			if($this->checkIsWallet($trips->payment_mode)) {
				if ($total_fare >= $wallet_amount) {
					$amount = $total_fare - $wallet_amount;
					$remaining_wallet = 0;
					$applied_wallet = $wallet_amount;

					if ($trips->payment_mode == 'Cash & Wallet') {
						$owe_amount = $amount;
						if ($owe_amount >=($driver_total_fare-$driver_or_company_commission)) {  // if owe amount is more than driver payout then driver payout is zero
							$owe_amount = $owe_amount-($driver_total_fare-$driver_or_company_commission);
							$driver_payout = 0;
						}
						else { // if owe amount is less than driver payout condition
							$owe_amount = $owe_amount;
							$driver_payout = ($driver_total_fare-$driver_or_company_commission) - $owe_amount;
							$owe_amount = 0;
						}
					}
				}
				else if ($total_fare < $wallet_amount) {
					$remaining_wallet = $wallet_amount - $total_fare;
					$amount = 0;
					$applied_wallet = $total_fare;
				}

				if ($save == 1) {
					$this->referralUpdate($trips->user_id,$applied_wallet,$user->currency->code);
					Wallet::whereUserId($trips->user_id)->update(['amount' => $remaining_wallet, 'currency_code' => $user->currency->code]);
				}
				//owe amount deduction for driver 
			}
			elseif ($trips->payment_mode == 'Cash') {
				if($promo_amount > 0) {
					$trips->payment_mode = 'Cash & Wallet';
				}
				// Check total Fare less than commission for promo applied

				if($total_fare < $driver_payout) {
					$owe_amount = 0;
					$driver_payout = abs($total_fare - $driver_payout);
				}
				else {
					$owe_amount = abs($total_fare - $driver_payout);
					$driver_payout = 0;
				}
				$amount = $total_fare;
			}
			else {
				$amount = $total_fare;
			}

            if($trips->payment_mode != 'Cash' && $trips->payment_mode != 'Cash & Wallet') {
		       $driver_payout_result = $this->oweAmount($driver_payout,$trips->driver_id,$trips->id,$save,$user->currency->code);
		       $driver_payout = $driver_payout_result['driver_payout'];
		    }
		    else {
		    	$converted_owe_amount = $this->helper->currency_convert($user->currency->code,$trips->getOriginal('currency_code'),$owe_amount);
		    	if ($save == 1) {
		    		Trips::where('id', $data['trip_id'])->update(['owe_amount' => $converted_owe_amount]);
		    	}
		    	$driver_payout_result = $this->oweAmount($driver_payout,$trips->driver_id,$trips->id,$save,$user->currency->code);
		    	if($trips->payment_mode == 'Cash & Wallet') {
		       		$driver_payout = ($driver_payout_result['driver_payout'] > 0 ) ? $driver_payout_result['driver_payout'] : 0;
		    	}
		    }

			$trips->total_time = ($trip_hours * 60) + $trip_minute;
			$trips->time_fare = $trip_time_fare;
			$trips->distance_fare = $trip_km_fare;
			$trips->base_fare = $trip_base_fare;
			$trips->subtotal_fare = $subtotal_fare;
			$trips->total_fare = $amount;
			$trips->driver_payout = $driver_payout;
			$trips->access_fee = $access_fee;
			$trips->black_car_fund = $black_car_fund; 
			$trips->additional_tax = $trip_additional_tax;
			$trips->sales_tax =  $sales_tax;
			$trips->owe_amount = $owe_amount;
		    $trips->additional_rider_amount = $additional_rider_amount;
		    $trips->wallet_amount = $applied_wallet;
		    $trips->promo_amount = $promo_amount;
			$trips->surcharge_amount = $trips->surcharge_amount;
		    $trips->tips = $trips->tips;
		    $trips->toll_fee = $trips->toll_fee;
			$trips->cancellation_fee = $trips->cancellation_fee;
			$trips->currency_code = (Request::segment(1) == 'admin') ? DEFAULT_CURRENCY : $user->currency->code;
		    $trips->schedule_fare = $schedule_fare_amount;
		    $trips->peak_amount = $peak_amount;
		    $trips->waiting_charge = $waiting_charge;
		    $trips->driver_peak_amount = $driver_peak_amount;
		    $trips->driver_or_company_commission = $driver_or_company_commission;
		    $trips->applied_owe_amount = $driver_payout_result['applied'];

		    if ($save == 1) {
				$trips->is_calculation = 1;
				if ($amount <= 0 && $trips->cancellation_fee == 0.00) {  //If toatal amount taken from wallet then trip status changed to completed
					$trips->status = 'Completed';
				} else {
					$trips->status = 'Cancelled';
				}

		    	$trips->save();

		    	if($trips->pool_id>0) {
		    		// sum all trips
		    		$this->poolTripsCalculation($trips->pool_id);
		    	}	
			}

			//Send payment detail as SMS to manual booking user
			$schedule_ride = ScheduleRide::find($trips->ride_request->schedule_id);
			if(isset($schedule_ride) && $schedule_ride->booking_type == 'Manual Booking') {
				$push_title = __('messages.sms_payment_detail');
		        $text 		= __('messages.api.trip_total_fare',['total_fare' => $trips->total_fare, 'currency' => $trips->currency_code]);

		        $push_data['push_title'] = $push_title;
		        $push_data['data'] = array(
		            'custom_message' => array(
		                'title' => $push_title,
		                'message_data' => $text,
		            )
		        );

		        $text = $push_title.$text;

		        $this->request_helper->checkAndSendMessage($trips->users,$text,$push_data);
        	}
		}

		return $trips;
	}

	/**
	 * Generate Web Invoice
	 *
	 * @param $trip instanceOf \App\Models\Trips
	 * @return Array $invoice_data
	 */
	public function getWebInvoice($trip)
	{
		$payment_mode = array(
            'key'   => __('messages.dashboard.payment_mode'),
            'value' => $trip->payment_mode,
        );
        $invoice[] = formatInvoiceItem($payment_mode);

        $user_data = [
            'user_id' => @Auth()->user()->id,
            'user_type' => strtolower(@Auth()->user()->user_type),
        ];
        $symbol = Currency::original_symbol(session('currency'));
        $symbol = html_entity_decode($symbol);

        $invoice = array_merge($invoice,$this->formatInvoice($trip,$user_data,true));
        /*if($user_data['user_type'] == 'driver') {
        	$total_fare = array(
				'key' 	=> __('messages.total_trip_fare'),
				'value' => $symbol.$trip->total_trip_fare,
				'bar' 	=> 1,
				'colour'=> 'black',
			);
			$invoice[] = formatInvoiceItem($total_fare);
        }*/
        
        return $invoice;
	}

	/**
	 * Generate Base Invoice
	 *
	 * @param $trips instanceOf \App\Models\Trips
	 * @param Array $data
	 * @param Boolean $is_web
	 * @return Array $invoice_data
	 */
	public static function formatInvoice($trips,$data,$is_web = false)
	{
		$user_type = strtolower($data['user_type']);
		$user = User::where('id', $data['user_id'])->first();
		$symbol = html_entity_decode($user->currency->symbol);
		if($is_web) {
			$symbol = Currency::original_symbol(session('currency'));
		}

		$total_trip_amount = number_format($trips->subtotal_fare + $trips->peak_amount + $trips->access_fee + $trips->surcharge_amount  + $trips->black_car_fund + $trips->sales_tax + $trips->additional_tax + $trips->schedule_fare + $trips->tips + $trips->toll_fee + $trips->waiting_charge + $trips->additional_rider_amount + $trips->cancellation_fee,2,'.','');
		$peak_subtotal_fare = number_format($trips->peak_amount + $trips->subtotal_fare,2,'.','');

		$invoice = array();

		if($trips->driver->company_id != 1 && $user_type != 'rider') {
			$total_amount = $trips->company_driver_earnings;
			if(checkIsCashTrip($trips->payment_mode) && $trips->total_fare > 0) {
				$total_amount = $trips->total_fare;
			}
			$item = array(
				'key' => __('messages.api.total_fare'),
				'value' => $symbol.$total_amount,
			);
			$invoice[] = formatInvoiceItem($item);
			return $invoice;
		}

		if($trips->base_fare != 0) {
			$item = array(
				'key' => __('messages.base_fare'),
				'value' => $symbol . $trips->base_fare,
			);
			$invoice[] = formatInvoiceItem($item);
		}

		if($trips->time_fare !=0) {
	   		$item = array(
				'key' => __('messages.time_fare'),
				'value' => $symbol . $trips->time_fare,
			);
			$invoice[] = formatInvoiceItem($item);
	   	}

		if($trips->distance_fare != 0) {
			$item = array(
				'key' => __('messages.distance_fare'),
				'value' => $symbol . $trips->distance_fare,
			);
			$invoice[] = formatInvoiceItem($item);
		}

	   	if($user_type == 'rider' && $trips->schedule_fare != 0) {
	   		$item = array(
				'key' => __('messages.schedule_fare'),
				'value' => $symbol . $trips->schedule_fare,
			);
			$invoice[] = formatInvoiceItem($item);
	   	}

		if($trips->peak_fare != 0) {
			if($trips->subtotal_fare != 0){
				$item = array(
					'key' => __('messages.normal_fare'),
					'value' => $symbol . $trips->subtotal_fare,
					'bar'	=> 1,
					'colour'=> 'black',
				);
				$invoice[] = formatInvoiceItem($item);
			}

			if($user_type == 'rider') {
				if($trips->peak_amount != 0){
					$item = array(
						'key' => trans('messages.peak_time_fare').'  x'.($trips->peak_fare + 0),
						'value' => $symbol.$trips->peak_amount,
					);

					$invoice[] = formatInvoiceItem($item);
				}

				if($peak_subtotal_fare != 0) {

					$item = array(
						'key' 	=> __('messages.peak_subtotal_fare'),
						'value' => $symbol.$peak_subtotal_fare,
						'bar'	=> 1,
						'colour'=> 'black'
					);
					$invoice[] = formatInvoiceItem($item);

				}
			} else {
				$item = array(
					'key' => __('messages.peak_time_fare'),
					'value' => $symbol.$trips->driver_peak_amount,
				);
				$invoice[] = formatInvoiceItem($item);

				$item = array(
					'key' 	=> __('messages.peak_subtotal_fare'),
					'value' => $symbol.($trips->driver_peak_amount + $trips->subtotal_fare),
					'bar'	=> 1,
					'colour'=> 'black'
				);
				$invoice[] = formatInvoiceItem($item);
			}
		}
		else {
			if($trips->subtotal_fare != 0) {
				$item = array(
					'key' 	=> __('messages.subtotal_fare'),
					'value' => $symbol.$trips->subtotal_fare,
					'bar'	=> 1,
					'colour'=> 'black'
				);
				$invoice[] = formatInvoiceItem($item);
		    }
		}

		if($trips->additional_rider_amount > 0) {
			$item = array(
				'key' 	=> __('messages.additional_rider_amount'),
				'value' => $symbol.$trips->additional_rider_amount,
			);
			$invoice[] = formatInvoiceItem($item);
		}

		if($user_type == 'rider') {
			if($trips->access_fee != 0) {
				$item = array(
					'key' => __('messages.access_fee'),
					'value' => $symbol.$trips->access_fee,
				);
				$invoice[] = formatInvoiceItem($item);
			}

			if($trips->black_car_fund != 0) {
				$item = array(
					'key' => __('messages.black_car_fund'),
					'value' => $symbol.$trips->black_car_fund,
				);
				$invoice[] = formatInvoiceItem($item);
			}

			if($trips->sales_tax != 0) {
				$item = array(
					'key' => __('messages.sales_tax'),
					'value' => $symbol.$trips->sales_tax,
				);
				$invoice[] = formatInvoiceItem($item);
			}

			if($trips->additional_tax != 0) {
				$trips_additional = TripAdditionalTax::where('trip_id',$trips->id)->where('tax_value','!=','0')->get();
				foreach($trips_additional as $additional){
					$item = array(
					'key' => ucfirst($additional->tax_name),
					'value' => $symbol.$additional->tax_value,
					);
					$invoice[] = formatInvoiceItem($item);
				}
			}
		} else {
			if($trips->driver_or_company_commission > 0) {
				$item = array(
					'key' => __('messages.service_fee'),
					'value' => '-'.$symbol.$trips->driver_or_company_commission,
				);
				$invoice[] = formatInvoiceItem($item);
			}
		}

		$is_first = 1;
		if($trips->waiting_charge != 0) {
	 		$item = array(
				'key' => __('messages.waiting_charge'),
				'value' => $symbol.$trips->waiting_charge,
				'bar'	=> $is_first,
			);
			$invoice[] = formatInvoiceItem($item);
			$is_first = 0;
	 	}

	 	if($trips->toll_fee != 0) {
	 		$item = array(
				'key' => 'Toll Fee',
				'value' => $symbol.$trips->toll_fee,
				'bar'	=> $is_first,
			);
			$invoice[] = formatInvoiceItem($item);
			$is_first = 0;
	 	}

	 	if($trips->surcharge_amount != 0) {
	 		if(request()->segment(1) != 'api'){
				$item = array(
					'key' => __('messages.surcharge_location'),
					'value' => $trips->surcharge_location,
				);
				$invoice[] = formatInvoiceItem($item);
			}

			$item = array(
				'key' => __('messages.surcharge_amount'),
				'comment' => $trips->surcharge_location,
				'value' => $symbol.$trips->surcharge_amount,
			);
			$invoice[] = formatInvoiceItem($item);
		}

	 	if($trips->tips != 0) {
			$item = array(
				'key' 	=> __('messages.tips'),
				'value' => $symbol.$trips->tips,
				'bar'	=> $is_first,
			);
			$invoice[] = formatInvoiceItem($item);
			$is_first = 0;
		}

		if($user_type == 'rider') {
			$item = array(
				'key' 	=> ($trips->cancellation_fee > 0.00) ? __('messages.api.cancellation_fee') : __('messages.total_trip_fare'),
				'value' => $symbol.$total_trip_amount,
				'bar' 	=> 1,
				'colour'=> 'black',
			);
			$invoice[] = formatInvoiceItem($item);

			if($trips->promo_amount != 0) {
				$item = array(
					'key' => __('messages.promo_amount'),
					'value' => '-'.$symbol.$trips->promo_amount,
				);
				$invoice[] = formatInvoiceItem($item);
			}

			if($trips->wallet_amount != 0) {
				$item = array(
					'key' => __('messages.wallet_amount'),
					'value' => '-'.$symbol.$trips->wallet_amount,
				);
				$invoice[] = formatInvoiceItem($item);
			}

		    if($trips->promo_amount != 0 || $trips->wallet_amount != 0) {
		    	$item = array(
					'key' 	=> __('messages.payable_amount'),
					'value' => $symbol.$trips->total_fare,
					'color'	=> 'green',
				);
				$invoice[] = formatInvoiceItem($item);
		    }
		} else {
			$is_first = 1;
			if($trips->owe_amount != 0 || in_array($trips->payment_mode,['Cash','Cash & Wallet'])) {
				if($trips->total_fare != 0) {
					$item = array(
						'key' 	=> __('messages.cash_collected'),
						'value' => $symbol.$trips->total_fare,
						'bar'	=> $is_first,
						'colour'=> 'green',
					);
					$invoice[] = formatInvoiceItem($item);
					$is_first = 0;
				}

		       	if($trips->owe_amount > 0) {
		       		$item = array(
						'key' 	=> __('messages.owe_amount'),
						'value' => '-'.$symbol.$trips->owe_amount,
						'bar' 	=> $is_first,
					);
					$invoice[] = formatInvoiceItem($item);
		       	}
			}

			$item = array(
				'key' => __('messages.api.driver_earnings'),
				'value' => $symbol.$trips->company_driver_earnings,
				'bar'	=> '1',
			);
			$invoice[] = formatInvoiceItem($item);

			$is_first = 1;
			if($trips->applied_owe_amount != 0) {
				$item = array(
					'key' 	=> __('messages.applied_owe_amount'),
					'value' => '-'.$symbol.$trips->applied_owe_amount,
					'bar'	=> $is_first,
				);
				$invoice[] = formatInvoiceItem($item);
				$is_first = 0;
			}

			$item = array(
				'key' => __('messages.driver_payout'),
				'value' => $symbol.$trips->driver_payout,
				'bar'	=> $is_first,
			);
		}

		return $invoice;
	}

	/**
	 * Generate Base Invoice with payment and user info
	 *
	 * @param $trip instanceOf \App\Models\Trips
	 * @param Array $data
	 * @return Array $invoice_data
	 */
	public function getInvoice($trips,$data)
	{
		$invoice = $this->formatInvoice($trips,$data);
		$user_promo_details = $this->getUserPromoDetails($trips->user_id);

		$payment_details = [
			'currency_code' 	=> $trips->currency_code ?? '',
			'total_time' 		=> $trips->total_time ?? '0.00',
			'pickup_location' 	=> $trips->pickup_location ?? '',
			'drop_location' 	=> $trips->drop_location ?? '',
			'driver_payout' 	=> $trips->driver_payout ?? '0.00',
			'payment_status' 	=> $trips->payment_status ?? '',
			'payment_mode' 		=> $trips->payment_mode ?? '',
			'owe_amount' 		=> $trips->owe_amount ?? '0.00',
			'applied_owe_amount'=> $trips->applied_owe_amount ?? '0.00',
			'remaining_owe_amount' => $trips->remaining_owe_amount ?? '0.00',
			'trips_status' 		=> $trips->status,
			'driver_paypal_id'  => $trips->driver->payout_id,
	        'total_fare'		=> $trips->total_fare,
		];

		if(Request::segment(1) == 'admin') {
			return $invoice;
		}

		return response()->json([
			'status_code' 		=> '1',
			'status_message' 	=> "Success",
			'total_time' 		=> $trips->total_time,
			'pickup_location' 	=> $trips->pickup_location,
			'drop_location' 	=> $trips->drop_location,
			'payment_mode' 		=> $trips->payment_mode,
			'payment_status' 	=> $trips->payment_status,
			'applied_owe_amount'=> $trips->applied_owe_amount,
			'remaining_owe_amount' => $trips->remaining_owe_amount,
			'is_calculation' 	=> $trips->is_calculation,
			'invoice' 			=> $invoice,
			'payment_details' 	=> $payment_details,
			'currency_code' 	=> $trips->currency_code,
			'total_fare' 		=> $trips->total_fare,
			'driver_payout' 	=> $trips->driver_payout,
			'promo_amount' 		=> $trips->promo_amount,
			'promo_details' 	=> $user_promo_details,
			'trip_status' 		=> $trips->status,
			'trip_id' 			=> $trips->id,
			'driver_image' 		=> $trips->driver_thumb_image,
			'driver_name' 		=> $trips->driver->first_name,
			'rider_image' 		=> @$trips->rider_profile_picture,
			'rider_name' 		=> @$trips->users->first_name,
			'paypal_app_id'		=> PAYPAL_ID,
			'paypal_mode'		=> PAYPAL_MODE,
		]);
	}

	/**
	 * Calculate OWE amount
	 *
	 * @param float $driver_payout
	 * @param integer $driver_id
	 * @param integer $trip_id
	 * @param Boolean $save
	 * @param String $currency
	 * @return Array $owe_data
	 */
	public function oweAmount($driver_payout, $driver_id, $trip_id,$save=0,$currency_code)
	{ 
		$current_trip = Trips::where('id', $trip_id)->first();
	  	// deduction
	  	$driver_owe_amount = DriverOweAmount::where('user_id',$driver_id)->first();
	  	$owe_amount = $remaining_owe_amount = $driver_owe_amount->amount+$current_trip->owe_amount;
	  	$applied_owe_amount = 0;

	   	if($owe_amount != 0) {
			/*
			// Hided Applied Owe amount concept removed
			$remaining_owe_amount = 0;
			if($owe_amount >= $driver_payout) {
				$applied_owe_amount = $driver_payout;
				$driver_payout = 0;
				$remaining_owe_amount  = $owe_amount - $driver_payout;
			}
			else if($owe_amount < $driver_payout) {
				$applied_owe_amount = $driver_payout - ($driver_payout-$owe_amount);
				$driver_payout = $driver_payout - $owe_amount;
				$remaining_owe_amount  = 0;
			}

			if ($save == 1) {
		   		Trips::where('id', $trip_id)->update(['remaining_owe_amount' => $remaining_owe_amount, 'applied_owe_amount' => $applied_owe_amount]);

		   		$this->driver_owe_amt_repository->update($driver_id,$remaining_owe_amount,$currency_code);
		   	}*/

		   	if ($save == 1) {
		   		$this->driver_owe_amt_repository->update($driver_id,$remaining_owe_amount,$currency_code);
		   	}

		   	return array('remaining' => $remaining_owe_amount, 'applied' => $applied_owe_amount, 'driver_payout' => $driver_payout);
	   	}

	    return array('remaining' => 0, 'applied' => 0, 'driver_payout' => $driver_payout);
	}

	/**
	 * Update Referral applied amount
	 *
	 * @param integer $user_id
	 * @param float $applied_amount
	 * @param string $currency_code
	 * @return Array $promo_details
	 */
	public function referralUpdate($user_id,$applied_amount,$from_currency_code)
	{
		$referrel_users = ReferralUser::where('user_id',$user_id)->where('payment_status','Completed')->where('pending_amount','>',0)->get();

		foreach ($referrel_users as $referrel_user) {
			if ($referrel_user->pending_amount <= $applied_amount) {
				$applied_amount = $applied_amount-$referrel_user->pending_amount;
				$referrel_user->pending_amount = 0;
				$referrel_user->save();
			}
			else {
				$referrel_user->pending_amount = $this->helper->currency_convert($from_currency_code,$referrel_user->getOriginal('currency_code'),($referrel_user->pending_amount-$applied_amount));
				$referrel_user->save();

				$applied_amount=0;
			}
		}
	}

	/**
	 * get User Promo Details
	 *
	 * @param integer $user_id
	 * @return Array $promo_details
	 */
	public function getUserPromoDetails($user_id)
	{
		$users_promo_codes = UsersPromoCode::whereUserId($user_id)->whereTripId(0)->with('promo_code')->whereHas('promo_code')->get();

		$promo_details = $users_promo_codes->map(function($users_promo) {
			$promo_code = $users_promo->promo_code;
			return [
				'id' 			=> $promo_code->id,
				'code' 			=> $promo_code->code,
				'amount' 		=> $promo_code->amount,
				'expire_date' 	=> $promo_code->expire_date_dmy,
			];
		});

		return $promo_details;
	}

	/**
	 * Get Overall Price calculation
	 *
	 * @param Int $id
	 * @return void
	 */
    public function poolTripsCalculation($pool_trip_id) {
    	$pool_trip = PoolTrip::with('trips')->find($pool_trip_id);

    	$pending_count = $pool_trip->trips->whereNotIn('status',['Payment','Rating','Completed'])->count();

		if($pending_count == 0) {
			$pool_trip = $pool_trip->toArray();

			$total_time = $total_km = $time_fare = $distance_fare = $base_fare = $additional_rider_amount = $peak_fare = $peak_amount = $driver_peak_amount = $schedule_fare = $access_fee = $surcharge_amount  = $sales_tax = $black_car_fund = $additional_tax = $waiting_charge = $toll_fee = $wallet_amount = $promo_amount = $subtotal_fare = $total_fare = $driver_payout = $driver_or_company_commission = 0;

			$to_currency = $pool_trip['currency_code'];
			$trips = $pool_trip['trips'];

			foreach($trips as $trip) {
				$total_time += $trip['total_time'];
				$total_km 	+= $trip['total_km'];

				$time_fare 		+= $this->helper->currency_convert($trip['currency_code'],$to_currency,$trip['time_fare']);
				$distance_fare 	+= $this->helper->currency_convert($trip['currency_code'],$to_currency,$trip['distance_fare']);
				$base_fare 		+= $this->helper->currency_convert($trip['currency_code'],$to_currency,$trip['base_fare']);
				$peak_fare 		+= $this->helper->currency_convert($trip['currency_code'],$to_currency,$trip['peak_fare']);
				$peak_amount 	+= $this->helper->currency_convert($trip['currency_code'],$to_currency,$trip['peak_amount']);
				$schedule_fare 	+= $this->helper->currency_convert($trip['currency_code'],$to_currency,$trip['schedule_fare']);
				$access_fee 	+= $this->helper->currency_convert($trip['currency_code'],$to_currency,$trip['access_fee']);
				$surcharge_amount += $this->helper->currency_convert($trip['currency_code'],$to_currency,$trip['surcharge_amount']);
				$sales_tax      += $this->helper->currency_convert($trip['currency_code'],$to_currency,$trip['sales_tax']);
				$sales_tax      += $this->helper->currency_convert($trip['currency_code'],$to_currency,$trip['additional_tax']);
				$black_car_fund += $this->helper->currency_convert($trip['currency_code'],$to_currency,$trip['black_car_fund']);
				$waiting_charge += $this->helper->currency_convert($trip['currency_code'],$to_currency,$trip['waiting_charge']);
				$toll_fee 		+= $this->helper->currency_convert($trip['currency_code'],$to_currency,$trip['toll_fee']);
				$wallet_amount 	+= $this->helper->currency_convert($trip['currency_code'],$to_currency,$trip['wallet_amount']);
				$promo_amount 	+= $this->helper->currency_convert($trip['currency_code'],$to_currency,$trip['promo_amount']);
				$subtotal_fare 	+= $this->helper->currency_convert($trip['currency_code'],$to_currency,$trip['subtotal_fare']);
				$total_fare 	+= $this->helper->currency_convert($trip['currency_code'],$to_currency,$trip['total_fare']);
				$driver_payout 	+= $this->helper->currency_convert($trip['currency_code'],$to_currency,$trip['driver_payout']);

				$driver_peak_amount += $this->helper->currency_convert($trip['currency_code'],$to_currency,$trip['driver_peak_amount']);
				$additional_rider_amount += $this->helper->currency_convert($trip['currency_code'],$to_currency,$trip['additional_rider_amount']);
				$driver_or_company_commission += $this->helper->currency_convert($trip['currency_code'],$to_currency,$trip['driver_or_company_commission']);
			}

			$pool_trip = PoolTrip::find($pool_trip_id);
			$pool_trip->total_time 		= $total_time;
			$pool_trip->total_km 		= $total_km;
			$pool_trip->time_fare 		= $time_fare;
			$pool_trip->distance_fare 	= $distance_fare;
			$pool_trip->base_fare 		= $base_fare;
			$pool_trip->peak_fare 		= $peak_fare;
			$pool_trip->peak_amount 	= $peak_amount;
			$pool_trip->schedule_fare 	= $schedule_fare;
			$pool_trip->access_fee 		= $access_fee;
			$pool_trip->surcharge_amount = $surcharge_amount;
			$pool_trip->black_car_fund  = $black_car_fund;
			$pool_trip->sales_tax       = $sales_tax;
			$pool_trip->waiting_charge 	= $waiting_charge;
			$pool_trip->toll_fee 		= $toll_fee;
			$pool_trip->wallet_amount 	= $wallet_amount;
			$pool_trip->promo_amount 	= $promo_amount;
			$pool_trip->subtotal_fare 	= $subtotal_fare;
			$pool_trip->total_fare 		= $total_fare;
			$pool_trip->driver_payout 	= $driver_payout;
			$pool_trip->driver_peak_amount = $driver_peak_amount;
			$pool_trip->additional_rider_amount = $additional_rider_amount;
			$pool_trip->driver_or_company_commission = $driver_or_company_commission;
			$pool_trip->save();
		}
		
    }
}
