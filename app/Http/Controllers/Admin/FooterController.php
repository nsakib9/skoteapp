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
use App\Models\Footer;
use App\Models\Trips;
use App\Models\Menu;
use App\Models\Company;
use App\Models\Country;
use App\Models\Currency;
use App\Http\Start\Helpers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\DataTables\FooterDataTable;
use Image;

class FooterController extends Controller
{
    /**
     * Load Datatable for Menu
     *
     * @param array $dataTable  Instance of MenuDataTable
     * @return datatable
     */
    public function index(FooterDataTable $dataTable)
    {
        $footer = Footer::all();
        return $dataTable->render('admin.footer.view', compact('footer'));
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
            return view('admin.footer.add');
        }
        
        if($request->submit) {

            $rules = array(
                
                'leftColumn'   => 'required',
                'middleColumn'   => 'required',
                'rightColumn'   => 'required',
                'bottomRow'   => 'required',
                
            );


            // Add menu Validation Custom Names
            $attributes = array(
                'leftColumn' => trans('old.messages.footer.leftColumn'),
                'middleColumn' => trans('old.messages.footer.middleColumn'),
                'rightColumn' => trans('old.messages.footer.rightColumn'),
                'bottomRow' => trans('old.messages.footer.bottomRow')
                
            );

            // Edit menu Validation Custom Fields message
            $messages =array(
                'required' => ':attribute '.trans('old.messages.home.field_is_required').'',
            );

            $validator = Validator::make($request->all(), $rules,$messages, $attributes);
           

            // if ($validator->fails()) {
            //     return back()->withErrors($validator)->withInput();
            // }

            $footer = new Footer();

            $footer->leftColumn = $request->leftColumn;
            $footer->middleColumn = $request->middleColumn;
            $footer->rightColumn = $request->rightColumn;
            $footer->bottomRow = $request->bottomRow;
            $footer->save();
            flashMessage('success', 'Added Successfully');
        }
        return redirect('admin/footer');
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
            $footer = Footer::find($request->id);
            if($footer) {
                return view('admin.footer.edit', compact('footer'));
            }
            flashMessage('danger', 'Invalid ID');
            return redirect('admin/footer');
        }
        if($request->submit) {
            
            $rules = array(
                
                'leftColumn'   => 'required',
                'middleColumn'   => 'required',
                'rightColumn'   => 'required',
                'bottomRow'   => 'required',
                
            );

           
           
            // Edit menu Validation Custom Fields message
            $messages =array(
                'required' => ':attribute '.trans('old.messages.home.field_is_required').'',
            );
            // Edit menu Validation Custom Fields Name
           
            $attributes = array(
                'leftColumn' => trans('old.messages.footer.leftColumn'),
                'middleColumn' => trans('old.messages.footer.middleColumn'),
                'rightColumn' => trans('old.messages.footer.rightColumn'),
                'bottomRow' => trans('old.messages.footer.bottomRow'),
                
            );

            $validator = Validator::make($request->all(), $rules,$messages, $attributes);

            // if ($validator->fails()) {
            //     return back()->withErrors($validator)->withInput();
            // }

            $footer = Footer::find($request->id);
            
            $footer->leftColumn = $request->leftColumn;
            $footer->middleColumn = $request->middleColumn;
            $footer->rightColumn = $request->rightColumn;
            $footer->bottomRow = $request->bottomRow;
            $footer->save();

            flashMessage('success', trans('messages.footer.update_success'));
        }

        return redirect('admin/footer');
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
            Footer::find($request->id)->delete();
        }
        catch(\Exception $e) {
            flashMessage('error','footer can\'t delete.');
            return back();
        }

        flashMessage('success', 'Deleted Successfully');
        return redirect('admin/footer');
    }

}
