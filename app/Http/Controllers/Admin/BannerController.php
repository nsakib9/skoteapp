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
use App\Models\Banner;
use App\Models\Company;
use App\Models\Country;
use App\Models\Currency;
use App\Http\Start\Helpers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\DataTables\BannerDataTable;
use Image;

class BannerController extends Controller
{
    /**
     * Load Datatable for Banner
     *
     * @param array $dataTable  Instance of BannerDataTable
     * @return datatable
     */
    public function index(BannerDataTable $dataTable)
    {
        $banner = Banner::count();
        return $dataTable->render('admin.banner.view', compact('banner'));
    }

    /**
     * Add a New Banner
     *
     * @param array $request  Input values
     * @return redirect     to Banner view
     */
    public function add(Request $request)
    {
        if($request->isMethod('GET')) {
            return view('admin.banner.add');
        }
        
        if($request->submit) {
            $rules = array(
                'line_one'    => 'required',
                'line_two'     => 'required',
                'banner_img'   => 'required',
                'button_one'     => 'required',
                'button_two'      => 'required',
            );

            // Add Banner Validation Custom Names
            $attributes = array(
                'line_one' => trans('old.messages.user.line_one'),
                'line_two' => trans('old.messages.user.line_two'),
                'banner_img' => trans('old.messages.user.bannera_img'),
                'button_one' => trans('old.messages.user.button_one'),
                'button_two' => trans('old.messages.user.button_two'),
            );

            // Edit Banner Validation Custom Fields message
            $messages =array(
                'required' => ':attribute '.trans('old.messages.home.field_is_required').'',
            );

            $validator = Validator::make($request->all(), $rules,$messages, $attributes);
           

            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput();
            }

            $banner = new Banner();
            $banner->line_one   = $request->line_one;
            $banner->line_two    = $request->line_two;
            $banner->button_one = $request->button_one;
            $banner->button_two   = $request->button_two;
            $banner->save();

            if ($request->hasFile('banner_img')) {
                $originalName = $request->banner_img->getClientOriginalName();
                // $uniqueImageName = $request->name.$originalName;
                $image = Image::make($request->banner_img);
                $image->save(public_path().'/banner/'.$originalName);
                $banner->banner_img = $originalName;
            }
            $banner->save();
            flashMessage('success', 'Added Successfully');
        }
        return redirect('admin/banner');
    }

    /**
     * Update Banner Details
     *
     * @param array $request    Input values
     * @return redirect     to Banner View
     */
    public function update(Request $request)
    {
        if($request->isMethod("GET")) {
            $banner = Banner::find($request->id);
            if($banner) {
                return view('admin.banner.edit', compact('banner'));
            }
            flashMessage('danger', 'Invalid ID');
            return redirect('admin/banner');
        }
        if($request->submit) {
            
            $rules = array(
                'line_one'    => 'required',
                'line_two'     => 'required',
                'button_one'    => 'required',
                'button_two'      => 'required',
            );
            // Edit Banner Validation Custom Fields message
            $messages =array(
                'required'           => ':attribute '.trans('old.messages.home.field_is_required').'',
            );
            // Edit Banner Validation Custom Fields Name
            $attributes = array(
                'line_one' => trans('old.messages.user.line_one'),
                'line_two' => trans('old.messages.user.line_two'),
                'banner_img' => trans('old.messages.user.bannera_img'),
                'button_one' => trans('old.messages.user.button_one'),
                'button_two' => trans('old.messages.user.button_two'),
            );

            $validator = Validator::make($request->all(), $rules,$messages, $attributes);

            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput();
            }

            $banner = Banner::find($request->id);

            $banner->line_one   = $request->line_one;
            $banner->line_two    = $request->line_two;
            $banner->button_one = $request->button_one;
            $banner->button_two   = $request->button_two;
            $banner->save();

            flashMessage('success', trans('messages.user.update_success'));
        }

        return redirect('admin/banner');
    }

    /**
     * Delete Banner
     *
     * @param array $request    Input values
     * @return redirect     to Banner View
     */
    public function delete(Request $request)
    {
        try {
            Banner::find($request->id)->delete();
        }
        catch(\Exception $e) {
            flashMessage('error','Banner can\'t delete.');
            return back();
        }

        flashMessage('success', 'Deleted Successfully');
        return redirect('admin/banner');
    }

}
