<?php

namespace App\Http\Controllers\Admin;

use DB;
use Auth;
use Session;
use Validator;
use App\Models\Role;
use App\Models\User;
use App\Models\Admin;
use App\Models\Trips;
use App\Models\Setting;
use App\Models\Company;
use App\Models\Country;
use App\Models\Currency;
use App\Http\Start\Helpers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\DataTables\SettingDataTable;
use Image;

class SettingController extends Controller
{
    /**
     * Load Datatable for Setting
     *
     * @param array $dataTable  Instance of SettingDataTable
     * @return datatable
     */
    public function index(SettingDataTable $dataTable)
    {
        $setting = Setting::count();
        return $dataTable->render('admin.setting.view', compact('setting'));
    }

    /**
     * Add a New Setting
     *
     * @param array $request  Input values
     * @return redirect     to Setting view
     */
    public function add(Request $request)
    {
        if($request->isMethod('GET')) {
            return view('admin.setting.add');
        }
        
        if($request->submit) {
            $rules = array(
                
                'logo_img'   => 'required',
                'button_text'     => 'required',
            );

            // Add Setting Validation Custom Names
            $attributes = array(
                'logo_img' => trans('old.messages.user.logo_img'),
                'button_text' => trans('old.messages.user.button_text'),
            );

            // Edit Setting Validation Custom Fields message
            $messages =array(
                'required' => ':attribute '.trans('old.messages.home.field_is_required').'',
            );

            $validator = Validator::make($request->all(), $rules,$messages, $attributes);
           

            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput();
            }

            $setting = new Setting();
            $setting->button_text = $request->button_text;
            $setting->save();

            if ($request->hasFile('logo_img')) {
                $originalName = $request->logo_img->getClientOriginalName();
                $image = Image::make($request->logo_img);
                $image->save(public_path().'/setting/'.$originalName);
                $setting->logo_img = $originalName;
            }
            $setting->save();
            flashMessage('success', 'Added Successfully');
        }
        return redirect('admin/setting');
    }

    /**
     * Update Setting Details
     *
     * @param array $request    Input values
     * @return redirect     to Setting View
     */
    public function update(Request $request)
    {
        if($request->isMethod("GET")) {
            $setting = Setting::find($request->id);
            if($setting) {
                return view('admin.setting.edit', compact('setting'));
            }
            flashMessage('danger', 'Invalid ID');
            return redirect('admin/setting');
        }
        if($request->submit) {
            
            $rules = array(
                'button_text'    => 'required',
            );
            // Edit Setting Validation Custom Fields message
            $messages =array(
                'required' => ':attribute '.trans('old.messages.home.field_is_required').'',
            );
            // Edit Setting Validation Custom Fields Name
            $attributes = array(
                'logo_img' => trans('old.messages.user.logo_img'),
                'button_text' => trans('old.messages.user.button_text'),
            );

            $validator = Validator::make($request->all(), $rules,$messages, $attributes);

            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput();
            }

            $setting = Setting::find($request->id);

            $setting->button_text = $request->button_text;
            $setting->save();

            flashMessage('success', trans('messages.user.update_success'));
        }

        return redirect('admin/setting');
    }

    /**
     * Delete Setting
     *
     * @param array $request    Input values
     * @return redirect     to Setting View
     */
    public function delete(Request $request)
    {
        try {
            Setting::find($request->id)->delete();
        }
        catch(\Exception $e) {
            flashMessage('error','Setting can\'t delete.');
            return back();
        }

        flashMessage('success', 'Deleted Successfully');
        return redirect('admin/setting');
    }

}
