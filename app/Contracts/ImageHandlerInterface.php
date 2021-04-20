<?php

/**
 * Image Handler Interface
 *
 * @package     Gofer
 * @subpackage  Contracts
 * @category    Image Handler
 * @author      Trioangle Product Team
 * @version     2.2.1
 * @link        http://trioangle.com
*/

namespace App\Contracts;

use Illuminate\Http\UploadedFile;


interface ImageHandlerInterface
{
	public function upload(UploadedFile $image, string $path, ?string $name = null, ?array $options = []);
	public function delete(string $image);
}
