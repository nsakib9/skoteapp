<?php

/**
 * Help Translations Model
 *
 * @package     Gofer
 * @subpackage  Controller
 * @category    Help Translations
 * @author      Trioangle Product Team
 * @version     2.2.1
 * @link        http://trioangle.com
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TripAdditionalTaxTranslations extends Model
{	
	 protected $table = 'trip_additional_tax_translations';  

    public $timestamps = false;
    protected $fillable = ['name'];
    
    public function language() {
    	return $this->belongsTo('App\Models\Language','locale','value');
    }
}
