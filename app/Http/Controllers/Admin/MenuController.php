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
use App\Models\Menu;
use App\Models\Company;
use App\Models\Country;
use App\Models\Currency;
use App\Http\Start\Helpers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\DataTables\MenuDataTable;
use Image;

class MenuController extends Controller
{
    /**
     * Load Datatable for Menu
     *
     * @param array $dataTable  Instance of MenuDataTable
     * @return datatable
     */
    public function index(MenuDataTable $dataTable)
    {
        $menus = Menu::all();
        return $dataTable->render('admin.menu.view', compact('menus'));
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
            return view('admin.menu.add');
        }
        
        if($request->submit) {
            $rules = array(
                
                'name'   => 'required',
                'type'     => 'required',
                'url'     => 'required',
            );

            // Add menu Validation Custom Names
            $attributes = array(
                'name' => trans('old.messages.user.name'),
                'type' => trans('old.messages.user.type'),
                'url' => trans('old.messages.user.url'),
            );

            // Edit menu Validation Custom Fields message
            $messages =array(
                'required' => ':attribute '.trans('old.messages.home.field_is_required').'',
            );

            $validator = Validator::make($request->all(), $rules,$messages, $attributes);
           

            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput();
            }

            $menu = new Menu();
            $menu->name = $request->name;
            $menu->type = $request->type;
            $menu->url = $request->url;
            $menu->save();
            $menu->save();
            flashMessage('success', 'Added Successfully');
        }
        return redirect('admin/menu');
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
            $menu = Menu::find($request->id);
            if($menu) {
                return view('admin.menu.edit', compact('menu'));
            }
            flashMessage('danger', 'Invalid ID');
            return redirect('admin/menu');
        }
        if($request->submit) {
            
            $rules = array(
                'name'    => 'required',
                'type'    => 'required',
                'url'    => 'required',
            );
            // Edit menu Validation Custom Fields message
            $messages =array(
                'required' => ':attribute '.trans('old.messages.home.field_is_required').'',
            );
            // Edit menu Validation Custom Fields Name
            $attributes = array(
                'name' => trans('old.messages.user.name'),
                'type' => trans('old.messages.user.type'),
                'url' => trans('old.messages.user.url'),
            );

            $validator = Validator::make($request->all(), $rules,$messages, $attributes);

            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput();
            }

            $menu = Menu::find($request->id);

            $menu->name = $request->name;
            $menu->type = $request->type;
            $menu->url = $request->url;
            $menu->save();

            flashMessage('success', trans('messages.user.update_success'));
        }

        return redirect('admin/menu');
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
            Menu::find($request->id)->delete();
        }
        catch(\Exception $e) {
            flashMessage('error','menu can\'t delete.');
            return back();
        }

        flashMessage('success', 'Deleted Successfully');
        return redirect('admin/menu');
    }

}
