<?php

/**
 * Help Model
 *
 * @package     Gofer
 * @subpackage  Controller
 * @category    Help
 * @author      Trioangle Product Team
 * @version     2.2.1
 * @link        http://trioangle.com
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Session;
use Request;

class AdditionalTax extends Model
{
  use Translatable;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'additional_tax';  

    public $translatedAttributes = ['name'];

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

    public function getUpdatedAtAttribute()
    {
        return date('d-m-Y'.' H:i:s',strtotime($this->attributes['updated_at']));
    }

    // Get all Active status records
    public static function active_all()
    {
        return AdditionalTax::whereStatus('Active')->get();
    }
}
