<?php namespace Unisharp\Laravelfilemanager\controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;

/**
 * Class FolderController
 * @package Unisharp\Laravelfilemanager\controllers
 */
class FolderController extends LfmController {

    function __construct()
    {
        parent::__construct();

        $this->file_location .= Input::get('base');
    }


    /**
     * Get list of folders as json to populate treeview
     *
     * @return mixed
     */
    public function getFolders()
    {
        $directories = File::directories(base_path($this->file_location));
        $final_array = [];

        foreach ($directories as $directory) {
            if (basename($directory) != "thumbs") {
                $final_array[] = basename($directory);
            }
        }

        return View::make("laravel-filemanager::tree")
            ->with('dirs', $final_array);
    }


    /**
     * Add a new folder
     *
     * @return mixed
     */
    public function getAddfolder()
    {
        $folder_name = Input::get('name');

        $path = base_path($this->file_location) . "/" . $folder_name;

        if (!File::exists($path)) {
            File::makeDirectory($path, $mode = 0777, true, true);
            return "OK";
        } else if (empty($folder_name)) {
            return 'Folder name cannot be empty!';
        } else {
            return "A folder with this name already exists!";
        }

    }

}
