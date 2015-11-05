<?php namespace Unisharp\Laravelfilemanager\controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use Lang;

/**
 * Class CropController
 * @package Unisharp\Laravelfilemanager\controllers
 */
class DeleteController extends LfmController {

    /**
     * Delete image and associated thumbnail
     *
     * @return mixed
     */
    public function getDelete()
    {
        $name_to_delete = Input::get('items');

        $file_path = parent::getPath();

        $file_to_delete = $file_path . $name_to_delete;
        $thumb_to_delete = parent::getPath('thumb') . $name_to_delete;

        if (!File::exists($file_to_delete)) {
            return $file_to_delete . ' not found!';
        }

        if (File::isDirectory($file_to_delete)) {
            if (sizeof(File::files($file_to_delete)) != 0) {
                return Lang::get('laravel-filemanager::lfm.error-delete');
            }

            File::deleteDirectory($file_to_delete);

            return 'OK';
        }

        File::delete($file_to_delete);

        if (Session::get('lfm_type') == 'Images') {
            File::delete($thumb_to_delete);
        }

        return 'OK';
    }
    
}
