<?php

namespace App\Http\Controllers\Admin;

use DB;
use Auth;
use Session;
use Validator;
use App\Models\Role;
use App\Models\User;
use App\Models\Admin;
use App\Models\CustomCSS;
use App\Models\Trips;
use App\Models\Menu;
use App\Models\Company;
use App\Models\Country;
use App\Models\Currency;
use App\Http\Start\Helpers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\DataTables\CustomCSSDataTable;
use Image;

class CustomCSSController extends Controller
{
    /**
     * Load Datatable for Menu
     *
     * @param array $dataTable  Instance of MenuDataTable
     * @return datatable
     */
    public function index(CustomCSSDataTable $dataTable)
    {
        $customCSS = CustomCSS::all();
        return $dataTable->render('admin.customcss.view', compact('customCSS'));
    }

    /**
     * Add a New Menu
     *
     * @param array $request  Input values
     * @return redirect     to Menu view
     */
    public function add(Request $request)
    {
        if($request->isMethod('GET')) {
            return view('admin.customcss.add');
        }
        
        if($request->submit) {
            $rules = array(
                
                'csscode'   => 'required',
                
            );

            // Add menu Validation Custom Names
            $attributes = array(
                'csscode' => trans('old.messages.customcss.csscode'),
                
            );

            // Edit menu Validation Custom Fields message
            $messages =array(
                'required' => ':attribute '.trans('old.messages.home.field_is_required').'',
            );

            $validator = Validator::make($request->all(), $rules,$messages, $attributes);
           

            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput();
            }

            $customCSS = new CustomCSS();
            $customCSS->csscode = $request->csscode;
            $customCSS->save();
            flashMessage('success', 'Added Successfully');
        }
        return redirect('admin/customCSS');
    }

    /**
     * Update Menu Details
     *
     * @param array $request    Input values
     * @return redirect     to Menu View
     */
    public function update(Request $request)
    {
        if($request->isMethod("GET")) {
            $customCSS = CustomCSS::find($request->id);
            if($customCSS) {
                return view('admin.customcss.edit', compact('customCSS'));
            }
            flashMessage('danger', 'Invalid ID');
            return redirect('admin/customCSS');
        }
        if($request->submit) {
            
            $rules = array(
                'csscode'    => 'required',
               
            );
            // Edit menu Validation Custom Fields message
            $messages =array(
                'required' => ':attribute '.trans('old.messages.home.field_is_required').'',
            );
            // Edit menu Validation Custom Fields Name
            $attributes = array(
                'csscode' => trans('old.messages.customcss.csscode'),
               
            );

            $validator = Validator::make($request->all(), $rules,$messages, $attributes);

            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput();
            }

            $customCSS = CustomCSS::find($request->id);
            
            $customCSS->csscode = $request->csscode;
            
           
            $customCSS->save();

            flashMessage('success', trans('messages.customcss.update_success'));
        }

        return redirect('admin/customCSS');
    }

    /**
     * Delete menu
     *
     * @param array $request    Input values
     * @return redirect     to menu View
     */
    public function delete(Request $request)
    {
        try {
            CustomCSS::find($request->id)->delete();
        }
        catch(\Exception $e) {
            flashMessage('error','menu can\'t delete.');
            return back();
        }

        flashMessage('success', 'Deleted Successfully');
        return redirect('admin/customCSS');
    }

}
