<?php

/**
 * Trips Controller
 *
 * @package     Gofer
 * @subpackage  Controller
 * @category    Trips
 * @author      Trioangle Product Team
 * @version     2.2.1
 * @link        http://trioangle.com
 */

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Start\Helpers;
use App\Models\User;
use App\Models\Trips;
use App\Models\Payment;
use App\Models\PaymentGateway;
use App\Models\SiteSettings;
use App\Models\Currency;
use App\Models\PayoutCredentials;
use App\Models\DriverOweAmount;
use App\Models\TripAdditionalTax;
use App\DataTables\CancelTripsDataTable;
use App\DataTables\TripsDataTable;
use Excel;
use DB;
use Auth;

class TripsController extends Controller
{
    public function __construct()
    {
        $this->request_helper = resolve('App\Http\Helper\RequestHelper');
        $this->invoice_helper = resolve('App\Http\Helper\InvoiceHelper');
    }

    /**
     * Load Datatable for Trips
     *
     * @return view file
     */
    public function index(TripsDataTable $dataTable)
    {
        return $dataTable->render('admin.trips.index');
    }

    /**
     * Load particular trip data
     *
     * @return view file
     */
    public function view(Request $request)
    {
        $data['result'] = Trips::
                where('id',$request->id)
                ->where(function($query)  {
                    if(LOGIN_USER_TYPE=='company') {
                        $query->whereHas('driver',function($q1){
                            $q1->where('company_id',Auth::guard('company')->user()->id);
                        });
                    }
                })
                ->first();

        $data['code'] = Currency::defaultCurrency()->first()->code;

        if($data['result']) {
            if ($data['result']->status == 'Payment') {
                $dataw = [
                    'trip_id'   => $data['result']->id,
                    'user_type' => 'rider',
                    'user_id'   => $data['result']->user_id,
                    'cancellation_fee'   =>$data['result']->cancellation_fee,
                    'save_to_trip_table' =>  0,
                ];
                $trips = $this->invoice_helper->calculation($dataw);

                $data_web =  $this->getAdminInvoice($trips);
            } else {
                $data_web = $this->getAdminInvoice($data['result']);

            }

            $data['invoice_data'] =  $data_web;
            $data['back_url'] = url(LOGIN_USER_TYPE.'/trips');
            if($request->s == 'overall') {
                $data['back_url'] = url(LOGIN_USER_TYPE.'/statements/overall');
            }
            elseif($request->s == 'driver') {
                $data['back_url'] = url(LOGIN_USER_TYPE.'/view_driver_statement/'.$data['result']->driver_id);
            }

            $driver_owe_amount = DriverOweAmount::where('user_id',$data['result']->driver_id)->first();

            return view('admin.trips.view', $data);
        }
        flashMessage('danger', 'Invalid ID');
        return redirect(LOGIN_USER_TYPE.'/trips');          
    }

    /**
     * Export trip data to excel 
     *
     * @return view file
     */
    public function export(Request $request)
    {
      

           $from = date('Y-m-d H:i:s', strtotime($request->from));
            $to = date('Y-m-d H:i:s', strtotime($request->to));
            $category = $request->category;

           
                $result = Trips::where('trips.created_at', '>=', $from)->where('trips.created_at', '<=', $to) 
                        ->join('users', function($join) {
                                $join->on('users.id', '=', 'trips.user_id');
                            })
                        ->join('currency', function($join) {
                                $join->on('currency.code', '=', 'trips.currency_code');
                            })
                        ->join('car_type', function($join) {
                                $join->on('car_type.id', '=', 'trips.car_id');
                            })
                        ->leftJoin('users as u', function($join) {
                                $join->on('u.id', '=', 'trips.driver_id');
                            })
                        ->select(['trips.id as id','trips.begin_trip as begin_trip','trips.pickup_location as pickup_location','trips.drop_location as drop_location', 'u.first_name as driver_name', 'users.first_name as rider_name',  DB::raw('CONCAT(currency.symbol, trips.total_fare) AS total_amount'), 'trips.status','car_type.car_name as car_name', 'trips.created_at as created_at', 'trips.updated_at as updated_at', 'trips.*'])->get();
        

        Excel::create('Trips-report', function($excel) use($result) {
            $excel->sheet('sheet1', function($sheet) use($result) {

                 $data[0]=['Id','From Location','To Location','Date','Driver Name','Rider Name','Fare','Vehicle Details','Status','Created At'];

                foreach ($result as $key => $value) {
                    $data[]=array($value->id,$value->pickup_location,$value->drop_location,date('d-m-y h:m a',strtotime($value->date)),$value->rider_name,$value->driver_name, html_entity_decode($value->total_amount),$value->car_name,$value->status,$value->created_at);
                }
                $data = array_values($data);
                 $sheet->with($data);
            });
        })->export('csv');
    }

    /**
     * Load Datatable for Cancel trips
     *
     * @param array $dataTable  Instance of Cancel tripsDataTable
     * @return datatable
     */
    public function cancel_trips(CancelTripsDataTable $dataTable)
    {
        return $dataTable->render('admin.trips.cancel');
    }

    public function getAdminInvoice($trips)
    {
        $default_currency = Currency::defaultCurrency()->first();
        if (LOGIN_USER_TYPE=='company' && session()->get('currency') != null) {
            $default_currency = Currency::whereCode(session('currency'))->first();
        }
        $currency_code = $default_currency->code;

        $symbol = html_entity_decode($default_currency->symbol);

        $invoice = array();

        if ($trips->cancellation_fee > 0) {
            $item = array(
                'key' => 'Cancellation Fee',
                'value' => $symbol.$trips->cancellation_fee,
            );

            $invoice[] = formatStatementItem($item);

            if($trips->wallet_amount > 0 && LOGIN_USER_TYPE != 'company') {
                $item = array(
                    'key' => 'Wallet amount',
                    'value' => $symbol.$trips->wallet_amount,
                );

                $invoice[] = formatStatementItem($item);
            }

            if($trips->total_fare > 0){
                $item = array(
                    'key' => 'Total Fare',
                    'value' => $symbol.$trips->total_fare,
                );

                $invoice[] = formatStatementItem($item);
            }

            return $invoice;
        }

        if($trips->total_km > 0) {
            $item = array(
                'key' => 'Distance',
                'value' => $trips->total_km.' Miles',
            );
            $invoice[] = formatStatementItem($item);
        }

        if($trips->base_fare > 0) {
            $item = array(
                'key' => 'Base fare',
                'value' => $symbol.$trips->base_fare,
            );
            $invoice[] = formatStatementItem($item);
        }

        if($trips->time_fare > 0) {
            $item = array(
                'key' => 'Time fare',
                'value' => $symbol.$trips->time_fare,
            );
            $invoice[] = formatStatementItem($item);
        }

        if($trips->distance_fare > 0) {
            $item = array(
                'key' => 'Distance fare',
                'value' => $symbol.$trips->distance_fare,
            );
            $invoice[] = formatStatementItem($item);
        }

        if($trips->schedule_fare > 0) {
            $item = array(
                'key' => 'Schedule fare',
                'value' => $symbol.$trips->schedule_fare,
            );
            $invoice[] = formatStatementItem($item);
        }

        if($trips->peak_fare > 0) {
            $item = array(
                'key' => 'Normal fare',
                'value' => $symbol.$trips->subtotal_fare,
            );
            $invoice[] = formatStatementItem($item);

            $item = array(
                'key' => 'Peak time pricing  x '.$trips->peak_fare,
                'value' => $symbol.$trips->peak_amount,
            );
            $invoice[] = formatStatementItem($item);

            $item = array(
                'key' => 'Subtotal',
                'value' => $symbol.$trips->peak_subtotal_fare,
            );
            $invoice[] = formatStatementItem($item);

            $item = array(
                'key' => 'Driver Peak Amount',
                'value' => $symbol.$trips->driver_peak_amount,
            );
            $invoice[] = formatStatementItem($item);

            $item = array(
                'key' => 'Admin Peak Amount',
                'value' => $symbol.($trips->peak_amount - $trips->driver_peak_amount),
            );
            $invoice[] = formatStatementItem($item);
        }
        else {
            if($trips->subtotal_fare > 0) {
                $item = array(
                    'key'   => 'Sub Total Fare',
                    'value' => $symbol.$trips->subtotal_fare,
                );
                $invoice[] = formatInvoiceItem($item);
            }
        }

        if($trips->additional_rider_amount > 0) {
            $item = array(
                'key' => __('messages.additional_rider_amount'),
                'value' => $symbol.$trips->additional_rider_amount,
            );
            $invoice[] = formatStatementItem($item);
        }

        if($trips->waiting_charge > 0) {
            $item = array(
                'key' => 'Waiting Charge',
                'value' => $symbol.$trips->waiting_charge,
            );
            $invoice[] = formatStatementItem($item);
        }

        if($trips->toll_fee > 0) {
            $item = array(
                'key' => 'Toll Fee',
                'value' => $symbol.$trips->toll_fee,
            );
            $invoice[] = formatStatementItem($item);
        }

        if($trips->tips > 0) {
            $item = array(
                'key' => 'Driver Tips',
                'value' => $symbol.$trips->tips,
            );
            $invoice[] = formatStatementItem($item);
        }

        if(LOGIN_USER_TYPE != 'company') {
            if($trips->admin_total_amount > 0) {
                $item = array(
                    'key' => 'Total fare',
                    // 'value' => $symbol.(number_format($trips->base_fare + $trips->time_fare +  $trips->distance_fare +  $trips->peak_amount + $trips->access_fee + $trips->schedule_fare + $trips->tips+ $trips->waiting_charge,2,'.','')),
                    'value' => $symbol.(number_format($trips->admin_total_amount + $trips->additional_rider_amount,2,'.','')),
                );
                $invoice[] = formatStatementItem($item);
            }

            if($trips->access_fee > 0) {
                $item = array(
                    'key' => 'Admin Commission for Rider',
                    'value' => $symbol.$trips->access_fee,
                );
                $invoice[] = formatStatementItem($item);
            }

            if($trips->sales_tax > 0) {
                $item = array(
                    'key' => 'Sales Tax',
                    'value' => $symbol.$trips->sales_tax,
                );
                $invoice[] = formatStatementItem($item);
            }

             if($trips->black_car_fund > 0) {
                $item = array(
                    'key' => 'Black Car Fund',
                    'value' => $symbol.$trips->black_car_fund,
                );
                $invoice[] = formatStatementItem($item);
            }


            if($trips->additional_tax != 0) {
                $trips_additional = TripAdditionalTax::where('trip_id',$trips->id)->get();
                foreach($trips_additional as $additional){
                    $item = array(
                        'key' => ucfirst($additional->tax_name),
                        'value' => $symbol.$additional->tax_value,
                    );
                    $invoice[] = formatStatementItem($item);
                }
            }
        }

        if($trips->surcharge_amount != 0) {

            $item = array(
                'key' => 'Surcharge Locations',
                'value' => $trips->surcharge_location,
            );
            $invoice[] = formatInvoiceItem($item);

            $item = array(
                'key' => __('messages.surcharge_amount'),
                'value' => $symbol.$trips->surcharge_amount,
            );
            $invoice[] = formatInvoiceItem($item);
        }

        if($trips->driver_or_company_commission > 0) {
            $item = array(
                'key' => 'Admin Commission for Driver',
                'value' => $symbol.$trips->driver_or_company_commission,
            );
            $invoice[] = formatStatementItem($item);
        }

        if($trips->owe_amount > 0) {
            $item['key'] = 'Owe Amount';
            if(LOGIN_USER_TYPE == 'company') {
                $item['key'] .= ' ( Service fee + Admin Commission)';
            }
            $item['value'] = $symbol.$trips->owe_amount;

            $invoice[] = formatStatementItem($item);
        }

        if($trips->applied_owe_amount > 0) {
            $item = array(
                'key' => 'Applied Owe Amount',
                'value' => $symbol.$trips->applied_owe_amount,
            );

            $invoice[] = formatStatementItem($item);
        }

        $item = array(
            'key' => 'Remaining Owe amount',
            'value' => $symbol.$trips->remaining_owe_amount,
        );

        if($trips->wallet_amount > 0 && LOGIN_USER_TYPE != 'company') {
            $item = array(
                'key' => 'Wallet amount',
                'value' => $symbol.$trips->wallet_amount,
            );

            $invoice[] = formatStatementItem($item);
        }

        if($trips->promo_amount > 0) {
            $item = array(
                'key' => 'Promo amount',
                'value' => $symbol.$trips->promo_amount,
            );

            $invoice[] = formatStatementItem($item);
        }

        if($trips->cash_collectable > 0) {
            $item = array(
                'key' => 'Cash Collected by Driver',
                'value' => $symbol.$trips->cash_collectable,
            );

            $invoice[] = formatStatementItem($item);
        }
        if(LOGIN_USER_TYPE != 'company' && $trips->payment_mode != 'Cash' && $trips->status == 'Completed' && $trips->driver_payout > 0) {
            $item = array(
                'key' => 'Driver Payout Amount',
                'value' => $symbol.$trips->driver_payout,
            );

            $invoice[] = formatStatementItem($item);
        }

        $item = array(
                'key' => 'Driver Earnings',
                'value' => $symbol.$trips->company_driver_earnings,
            );

            $invoice[] = formatStatementItem($item);

        $item = array(
            'key' => 'Payment Mode',
            'value' => $trips->payment_mode,
        );

        $invoice[] = formatStatementItem($item);

        return $invoice;
    }
}
