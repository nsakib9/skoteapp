<?php

/**
 * Trips Model
 *
 * @package     Gofer
 * @subpackage  Model
 * @category    Trips
 * @author      Trioangle Product Team
 * @version     2.2.1
 * @link        http://trioangle.com
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DateTime;
use DB;
use Auth;
use Request;
use Session;

class TripAdditionalTax extends Model
{
    // use CurrencyConversion,Translatable;

    // use  CurrencyConversion,Translatable {
    //     CurrencyConversion::method  insteadof Translatable;
    // }
   // use  Translatable,CurrencyConversion {
   //      Translatable::method  insteadof CurrencyConversion;
   //      CurrencyConversion::method  as methodmodify;
   //  }

    use  CurrencyConversion,Translatable {
        Translatable::getAttribute  insteadof CurrencyConversion;
        // Translatable::setAttribute  insteadof CurrencyConversion;
        Translatable::attributesToArray  insteadof CurrencyConversion;
        // CurrencyConversion::setAttribute as setAttribute1;
        CurrencyConversion::getAttribute as getAttribute1;
        CurrencyConversion::attributesToArray as attributesToArray1;

    }

    public $translatedAttributes = ['name'];


    public $convert_fields = ['tax_value'];
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'trip_additional_tax';


       public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        
        if(Request::segment(1) == 'admin') {
            $this->defaultLocale = 'en';
        } else if(Request::segment(1) == 'api') {
            $this->defaultLocale = @$_GET['language'] ?? 'en';
        }
        else {
            $this->defaultLocale = Session::get('language');
        }
    }



    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    // Join with profile_picture table
  
    // Join with payment table
    public function trip()
    {
        return $this->belongsTo('App\Models\Trips','id','trip_id');
    }
    // Join with Currency table
    public function currency()
    {
        return $this->belongsTo('App\Models\Currency','currency_code','code');
    }
    /**
     * get Driver Payout Attribute
     * 
     */
    public function getTaxValueAttribute()
    {
        return number_format(($this->attributes['tax_value']),2, '.', ''); 
    }
    /**
     * Prepare a date for array / JSON serialization.
     *
     * @param  \DateTimeInterface  $date
     * @return string
     */
    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
