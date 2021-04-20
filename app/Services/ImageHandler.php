<?php

/**
 * Local Image Handler
 *
 * @package     Gofer
 * @subpackage  Services
 * @category    Image Handler
 * @author      Trioangle Product Team
 * @version     2.2.1
 * @link        http://trioangle.com
*/

namespace App\Services;

use App\Contracts\ImageHandlerInterface;
use File;
use Image;
use Illuminate\Http\UploadedFile;


class ImageHandler implements ImageHandlerInterface
{
    const DEFAULT_EXTENSIONS = [
        'png', 'jpg', 'jpeg', 'gif', 'webp',
    ];

    /**
     * Upload image to storage
     *
     * @param UploadedFile $[image]
     * @param Array $[options] [options related to image upload]
     * @return Array Image data
     */
	public function upload(UploadedFile $image, string $path, ?string $prefix = null, ?array $options = [])
	{
		$ext = $image->getClientOriginalExtension();

		if(!$this->validateExtension($ext, $options['extensions'] ?? [])) {
			return [
				'status' => false,
				'status_message' => 'Invalid File Type',
			];
		}

        if ($prefix === null) {
			$prefix = 'image-'.time();
        }

        try {
            $stored_path = $image->storeAs($path, $prefix . '.' . $ext);
        } catch (\Exception $e) {
            return [
                'status' => false,
                'status_message' => 'Unable to Upload. ' . $ex->getMessage(),
            ];
        }

        if (!$stored_path) {
            return [
                'status' => false,
                'status_message' => 'Failed To Upload Image',
            ];
        }

        if(isset($options['sizes'])) {
            foreach ($options['sizes'] as $size) {
                list($width, $height) = $size;
                $resized_path = $this->resize($image, 80, $width, $height);
                $resized = new UploadedFile($resized_path, $image->getClientOriginalName());
                $resized->storeAs($path, $prefix . '_' . $width . 'x' . $height . '.' . $ext);
            }
        }

        return [
            'status' => true,
            'path' => $stored_path,
            'file_name' => $prefix . '.' . $ext,
        ];
	}

     /**
     * Delete image from storage
     *
     * @param String $[image]
     * @return Boolean
     */
	public function delete(string $image)
	{
		try {
            Storage::delete($image);
		} catch(\Exception $e) {
			return false;
		}

        return true;
	}

    /**
     * Validate Extension is valid or not
     *
     * @param String $[ext] [extension]
     * @param Array $[addional_ext] [additional extensions]
     * @return Boolean
     */
	protected function validateExtension($ext, $addional_ext = [])
	{
        $valid_extensions = array_merge(static::DEFAULT_EXTENSIONS, $addional_ext);
		return in_array(strtolower($ext), $valid_extensions);
	}

    /**
     * Compress/Crop Given image to given size
     *
     * @param UploadedFile $image
     * @param Int $[quality] [quality]
     * @param Int $width
     * @param Int $height
     * @return String filepath
     */
	protected function resize(UploadedFile $image, $quality, $width = 225, $height = 225)
	{
        $pathname = $image->getPathname();
        $info = getimagesize($pathname);
        if(!$info) {
            raise \Exception('Could not get image size');
        }

        switch ($info['mime']) {
            case 'image/jpeg':
                $image = imagecreatefromjpeg($pathname);
                $exif = @exif_read_data($pathname);
                break;
            case 'image/gif':
                $image = imagecreatefromgif($pathname);
                break;
            case 'image/png':
                $image = imagecreatefrompng($source_url);
                break;
            case 'image/webp':
                $image = imagecreatefromwebp($source_url);
                break;
            default:
                throw \Exception('Cannot resize image with mime "' . $info['mime'] . '"');
        }

        if (isset($exif) && !empty($exif['Orientation'])) {
            switch ($exif['Orientation']) {
                case 3:
                    $image = imagerotate($image, 180, 0);
                    break;
                case 6:
                    $image = imagerotate($image, -90, 0);
                    break;
                case 8:
                    $image = imagerotate($image, 90, 0);
                    break;
            }
        }

        $tmpfile = stream_get_meta_data(tmpfile())['uri'];
        imagejpeg($image, $tmpfile, $quality);

        ini_set('memory_limit', '-1');
        $img = Image::make($tmpfile);
        $image_width = $img->width();
        $image_height = $img->height();

        if($image_width < $width && $width < $height){
            $img = $img->fit($width, $image_height);
        }if($image_height < $height  && $width > $height){
            $img = $img->fit($width, $height);
        }

        $cropped_image = $img->fit($width, $height);
		$cropped_image->save($tmpfile);
        return $tmpfile;
    }
}
