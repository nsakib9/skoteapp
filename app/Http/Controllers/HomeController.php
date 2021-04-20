<?php

namespace App\Http\Controllers;

use DB;
use App;
use Auth;
use Route;
use Session;
use App\Models\Help;
use App\Models\Pages;
use App\Models\Banner;
use App\Models\JoinUs;
use App\Models\Currency;
use Illuminate\Http\Request;
use App\Models\HelpSubCategory;
use App\Models\HelpTranslations;
use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    /** 
    * Display homepage
    **/
	public function index()
    {
    	return view('home.home');
    }
    
	public function newindex()
    {
        $banner = Banner::first();
    	return view('home.index', compact('banner'));
    }

	public function pricing()
    {
      	return view('home.pricing');
    }

	public function about()
    {
      	return view('home.about');
    }

	public function contact()
    {
      	return view('home.contact');
    }

    public function model()
    {
      	return view('home.model');
    }
  
    /**
     * View Static Pages
     *
     * @param array $request  Input values
     * @return Static page view file
     */
    public function static_pages(Request $request)
    {
        if($request->token != '') {
            session(['get_token' => $request->token]);
        }

        $pages = Pages::where(['url'=>$request->name, 'status'=>'Active'])->firstOrFail();

        $data['content'] = str_replace(['SITE_NAME', 'SITE_URL'], [SITE_NAME, url('/')], $pages->content);
        $data['title'] = $pages->name;

        return view('home.static_pages', $data);
    }

    /**
     * Set session for Currency & Language while choosing footer dropdowns
     *
     */
    public function set_session(Request $request)
    {
        if($request->currency) {
            Session::put('currency', $request->currency);
            $symbol = Currency::original_symbol($request->currency);
            Session::put('symbol', $symbol);
        }
        else if ($request->language) {
            Session::put('language', $request->language);
            App::setLocale($request->value);
        }
    }

    /** 
    * Display Help Page
    **/
    public function help(Request $request)
    {
        if ($request->token != '') {
            Session::put('get_token', $request->token);
        }

        if (Route::current()->uri() == 'help') {
            $data['result'] = Help::whereSuggested('yes')->whereStatus('Active')->get();
        }
        else if (Route::current()->uri() == 'help/topic/{id}/{category}') {
            $count_result = HelpSubCategory::find($request->id);
            $data['subcategory_count'] = $count = (str_slug($count_result->name, '-') != $request->category) ? 0 : 1;
            $data['is_subcategory'] = (str_slug($count_result->name, '-') == $request->category) ? 'yes' : 'no';
            if ($count) {
                $data['result'] = Help::whereSubcategoryId($request->id)->whereStatus('Active')->get();
            }
            else {
                $data['result'] = Help::whereCategoryId($request->id)->whereStatus('Active')->get();
            }
        }
        else {
            $data['result'] = Help::whereId($request->id)->whereStatus('Active')->get();
            $data['is_subcategory'] = ($data['result'][0]->subcategory_id) ? 'yes' : 'no';
        }

        $data['category'] = Help::with(['category', 'subcategory'])->whereStatus('Active')->groupBy('category_id')->get(['category_id', 'subcategory_id']);

        return view('home.help', $data);
    }

    /** 
    * Get Help questions using ajax
    **/
    public function ajax_help_search(Request $request)
    {
        $term = $request->term;

        $queries = Help::where('question', 'like', '%' . $term . '%')->get();
        $queries_translate = HelpTranslations::where('name', 'like', '%' . $term . '%')->get();
        if ($queries->isEmpty() && $queries_translate->isEmpty()) {
            $results[] = ['id' => '0', 'value' => trans('messages.help.no_results_found'), 'question' => trans('messages.help.no_results_found')];
        }
        else {
            foreach ($queries as $query) {
                $results[] = ['id' => $query->id, 'value' => str_replace('SITE_NAME', SITE_NAME, $query->question), 'question' => str_slug($query->question, '-')];
            }
            foreach ($queries_translate as $translate) {
                $results[] = ['id' => $translate->help_id, 'value' => str_replace('SITE_NAME', SITE_NAME, $translate->name), 'question' => str_slug($translate->name, '-')];
            }
        }

        return json_encode($results);
    }

    /**
     * Redirect to play store or app store based on OS
     *
     * @param array $request  Input values
     * @return Static page view file
     */
    public function redirect_to_app(Request $request)
    {
        $join_us = JoinUs::get();
        if($request->type == 'driver') {
            $play_store_link = $join_us->where('name','play_store_driver')->first()->value;
            $app_store_link  = $join_us->where('name','app_store_driver')->first()->value;
        }
        else {
            $play_store_link = $join_us->where('name','play_store_rider')->first()->value;
            $app_store_link  = $join_us->where('name','app_store_rider')->first()->value;
        }

        return view('home.apps',compact('play_store_link','app_store_link'));
    }
}
