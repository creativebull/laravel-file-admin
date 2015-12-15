<?php namespace Unisharp\Laravelfilemanager\controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use Intervention\Image\Facades\Image;

/**
 * Class LfmController
 * @package Unisharp\Laravelfilemanager\controllers
 */
class LfmController extends Controller {

    /**
     * @var
     */
    public $file_location;
    public $dir_location;
    

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->setPathAndType();
        
        $this->checkMyFolderExists();
        
        $this->checkSharedFolderExists();
    }


    /**
     * Show the filemanager
     *
     * @return mixed
     */
    public function show()
    {
        if (Input::has('working_dir')) {
            $working_dir = Input::get('working_dir');
        } else {
            $working_dir = '/';
        }

        return View::make('laravel-filemanager::index')
            ->with('working_dir', $working_dir);
    }


    /*****************************
     ***   Private Functions   ***
     *****************************/


    private function setPathAndType()
    {
        // dd('type:'.Input::get('type'));
        
        if (Input::has('type') && Input::get('type') === 'Files') {
            Session::put('lfm_type', 'Files');
            Session::put('lfm.file_location', Config::get('lfm.files_dir'));
            Session::put('lfm.dir_location', Config::get('lfm.files_url'));
        } else if (Input::has('type') && Input::get('type') === 'Images') {
            Session::put('lfm_type', 'Images');
            Session::put('lfm.file_location', Config::get('lfm.images_dir'));
            Session::put('lfm.dir_location', Config::get('lfm.images_url'));
        }
    }


    private function checkMyFolderExists()
    {
        if (\Config::get('lfm.allow_multi_user') === true) {
            $path = $this->getPath();

            if (!File::exists($path)) {
                File::makeDirectory($path, $mode = 0777, true, true);
            }
        }
    }


    private function checkSharedFolderExists()
    {
        $path = $this->getPath('share');

        if (!File::exists($path)) {
            File::makeDirectory($path, $mode = 0777, true, true);
        }
    }


    private function formatLocation($location, $type = null)
    {
        if ($type === 'share') {
            return $location . Config::get('lfm.shared_folder_name') . '/';
        }

        $working_dir = Input::get('working_dir');

        if ($working_dir !== '/') {
            $location .= $working_dir . '/';
        }

        if ($type === 'thumb') {
            $location = $location . Config::get('lfm.thumb_folder_name') . '/';
        }

        return $location;
    }


    /****************************
     ***   Shared Functions   ***
     ****************************/


    public function getPath($type = null)
    {
        $path = base_path() . '/' . Session::get('lfm.file_location');

        $path = $this->formatLocation($path, $type);

        return $path;
    }


    public function getUrl($type = null)
    {
        $url = Session::get('lfm.dir_location');

        $url = $this->formatLocation($url, $type);

        return $url;
    }


    public function getDirectories($path)
    {
        $thumb_folder_name = Config::get('lfm.thumb_folder_name');
        $all_directories = File::directories($path);

        $arr_dir = [];

        foreach ($all_directories as $directory) {
            $dir_name = $this->getFileName($directory);

            if ($dir_name !== $thumb_folder_name) {
                $arr_dir[] = $dir_name;
            }
        }

        return $arr_dir;
    }


    public function getFileName($file)
    {
        $path_parts = explode('/', $file);

        $filename = end($path_parts);

        return $filename;
    }
}
