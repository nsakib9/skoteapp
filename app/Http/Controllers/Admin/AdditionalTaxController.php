<?php

/**
 * Help Controller
 *
 * @package     Gofer
 * @subpackage  Controller
 * @category    Help
 * @author      Trioangle Product Team
 * @version     2.2.1
 * @link        http://trioangle.com
 */

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\DataTables\AdditionalTaxDataTable;
use App\Models\AdditionalTax;
use App\Models\Language;
use Validator;

class AdditionalTaxController extends Controller
{
    /**
     * Load Datatable for Help
     *
     * @param array $dataTable  Instance of HelpDataTable
     * @return datatable
     */
    public function index(AdditionalTaxDataTable $dataTable)
    {
        return $dataTable->render('admin.additional_tax.view');
    }

    /**
     * Add a New Help
     *
     * @param array $request  Input values
     * @return redirect     to Help view
     */
    public function add(Request $request)
    {
        if($request->isMethod('GET')) {
            $data['languages'] = Language::where('status', '=', 'Active')->pluck('name', 'value');
            return view('admin.additional_tax.add', $data);
        }
        
        if($request->submit) {
            // Add Help Validation Rules
            $rules = array(
                
                'tax_name' => 'required',
                'tax_value'      => 'required|numeric|max:100',
                'status'      => 'required'
            );

            // Add Help Validation Custom Names
            $attributes = array(
               
                'tax_name' => 'Tax Name',
                'tax_value'      => 'Tax Value',
                'status'      => 'Status'
            );

          
            foreach($request->translations ?: array() as $k => $translation) {
                $rules['translations.'.$k.'.locale'] = 'required';
                $rules['translations.'.$k.'.name'] = 'required';              

                $attributes['translations.'.$k.'.locale'] = 'Language';
                $attributes['translations.'.$k.'.name'] = 'Tax Name';
              
            }
            $validator = Validator::make($request->all(), $rules,[], $attributes);

            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput();
            }

            $additional_tax = new AdditionalTax;
            $additional_tax->name    = $request->tax_name;
            $additional_tax->value = $request->tax_value;         
            $additional_tax->status         = $request->status;
            $additional_tax->save();

            foreach($request->translations ?: array() as $translation_data) {  
                $translation = $additional_tax->getTranslationById(@$translation_data['locale'], $additional_tax->id);
                $translation->name = $translation_data['name'];                
                $translation->save();
            }

            flashMessage('success', 'Added Successfully');
        }

        return redirect('admin/additional_tax');
    }

    /**
     * Update Help Details
     *
     * @param array $request    Input values
     * @return redirect     to Help View
     */
    public function update(Request $request)
    {
        if($request->isMethod("GET")) {
            $data['languages'] = Language::where('status', '=', 'Active')->pluck('name', 'value');
            $data['result'] = AdditionalTax::findOrFail($request->id);

            return view('admin.additional_tax.edit', $data);
        }
        else if($request->submit) {
            // Edit Help Validation Rules
            $rules = array(
                'tax_name'    => 'required',
                'tax_value' => 'required|numeric|max:100',                
                'status'      => 'required'
            );

            // Edit Help Validation Custom Fields Name
            $attributes = array(
                'tax_name'    => 'Tax Name',
                'tax_value' => 'Tax Value',                
                'status'      => 'Status'
            );
         
            foreach($request->translations ?: array() as $k => $translation)
            {
                $rules['translations.'.$k.'.locale'] = 'required';
                $rules['translations.'.$k.'.name'] = 'required';
                

                $attributes['translations.'.$k.'.locale'] = 'Language';
                $attributes['translations.'.$k.'.name'] = 'Tax Name';
               
            }
            $validator = Validator::make($request->all(), $rules);
            $validator->setAttributeNames($attributes); 

            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput();
            }

            $additional_tax = AdditionalTax::findOrFail($request->id);
            $additional_tax->name    = $request->tax_name;
            $additional_tax->value = $request->tax_value;    
            $additional_tax->status = $request->status;            
            $additional_tax->save();

            $removed_translations = explode(',', $request->removed_translations);
            foreach(array_values($removed_translations) as $id) {
                $additional_tax->deleteTranslationById($id);
            }

            foreach($request->translations ?: array() as $translation_data) {  
                $translation = $additional_tax->getTranslationById(@$translation_data['locale'], $translation_data['id']);
                $translation->name = $translation_data['name'];               

                $translation->save();
            }

            flashMessage('success', 'Updated Successfully');
        }
        return redirect('admin/additional_tax');
    }

    /**
     * Delete Help
     *
     * @param array $request    Input values
     * @return redirect     to Help View
     */
    public function delete(Request $request)
    {
        $additional_tax = AdditionalTax::findOrFail($request->id);
        $additional_tax->delete();

        flashMessage('success', 'Deleted Successfully');
        return redirect('admin/additional_tax');
    }

    public function ajax_help_subcategory(Request $request)
    {
        $result = HelpSubCategory::where('category_id', $request->id)->get();
        return json_encode($result);
    }
}