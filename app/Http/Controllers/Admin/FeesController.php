<?php

/**
 * Fees Controller
 *
 * @package     Gofer
 * @subpackage  Controller
 * @category    Fees
 * @author      Trioangle Product Team
 * @version     2.2.1
 * @link        http://trioangle.com
 */

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Fees;
use Validator;

class FeesController extends Controller
{
    /**
     * Load View and Update Fees Data
     *
     * @return redirect     to fees
     */
    public function index(Request $request)
    {
        if($request->isMethod("GET")) {
            return view('admin.fees');
        }
        if($request->submit) {
            // Fees Validation Rules
            $rules = array(
                'driver_peak_fare' => 'numeric|max:100',
                'driver_service_fee' => 'numeric',
                'black_car_fund' => 'numeric|max:100',
                'sales_tax' => 'numeric|max:100',
                'cancellation_fee' => 'numeric',
            );

            // Fees Validation Custom Names
            $attributes = array(
                'driver_peak_fare' => 'driver Peak Fare',
                'driver_service_fee' => 'driver Service Fee',
                'black_car_fund' => 'Black Car Fund Fee',
                'sales_tax' => 'Sales Tax',
            );

            $validator = Validator::make($request->all(), $rules, [], $attributes);

            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput();
            }

            Fees::where(['name' => 'driver_peak_fare'])->update(['value' => $request->driver_peak_fare]);
            Fees::where(['name' => 'driver_access_fee'])->update(['value' => $request->driver_service_fee]);
            Fees::where(['name' => 'black_car_fund'])->update(['value' => $request->black_car_fund]);
            Fees::where(['name' => 'sales_tax'])->update(['value' => $request->sales_tax]);
            Fees::where(['name' => 'cancellation_fee'])->update(['value' => $request->cancellation_fee]);

            flashMessage('success', 'Updated Successfully');
        }
        return redirect('admin/fees');
    }
}
