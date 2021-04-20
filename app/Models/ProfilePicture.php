<?php

/**
 * Profile Picture Model
 *
 * @package     Gofer
 * @subpackage  Model
 * @category    Profile Picture
 * @author      Trioangle Product Team
 * @version     2.2.1
 * @link        http://trioangle.com
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProfilePicture extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'profile_picture';

    protected $primaryKey = 'user_id';

    public $timestamps = false;

    public $appends = ['header_src', 'email_src'];

    // Get picture source URL based on photo_source
    public function getSrcAttribute()
    {
        $src = @$this->attributes['src'];

        if ($src == '' || $src === 'images/user.jpeg') {
            return url('images/user.jpeg');
        }

        if ($this->attributes['photo_source'] === 'Local') {
            return storage_url($src);
        }

        return $src;
    }

    // Get header picture source URL based on photo_source
    public function getHeaderSrcAttribute()
    {
        $src = $this->attributes['src'];

        if ($src == '' || $src === 'images/user.jpeg') {
            return url('images/user.jpeg');
        }

        if ($this->attributes['photo_source'] == 'Facebook') {
            $src = str_replace('large', 'small', $src);
        }

        if ($this->attributes['photo_source'] === 'Local') {
            return storage_url($src);
        }

        return $src;
    }

    //mobile hearder picture src
    public function getHeaderSrc510Attribute()
    {
        $src = $this->attributes['src'];

        if ($src == '' || $src === 'images/user.jpeg') {
            return url('images/user.jpeg');
        }

        if ($this->attributes['photo_source'] == 'Facebook') {
            $src = str_replace('large', 'small', $src);
        }

        if ($this->attributes['photo_source'] == 'Local') {
            return storage_url($src);
        }

        return $src;
    }

    /**
     * Get Image Source for Email
     *
     */
    public function getEmailSrcAttribute()
    {
        $src = $this->attributes['src'];

        if ($src == '' || $src === 'images/user.jpeg') {
            return url('images/user.jpeg');
        }

        if ($this->attributes['photo_source'] == 'Facebook') {
            $src = str_replace('large', 'small', $src);
        }

        if ($this->attributes['photo_source'] == 'Local') {
            return storage_url($src);
        }

        return $src;
    }
}
