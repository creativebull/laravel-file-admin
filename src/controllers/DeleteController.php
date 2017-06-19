<?php

namespace Unisharp\Laravelfilemanager\controllers;

use Unisharp\Laravelfilemanager\Events\ImageIsDeleting;
use Unisharp\Laravelfilemanager\Events\ImageWasDeleted;

class DeleteController extends LfmController
{
    /**
     * Delete image and associated thumbnail
     *
     * @return mixed
     */
    public function getDelete()
    {
        $name_to_delete = request('items');
        $file_to_delete = $this->lfm->path('full', $name_to_delete);
        $thumb_to_delete = $this->lfm->thumb()->path('full', $name_to_delete);

        event(new ImageIsDeleting($file_to_delete));

        if (is_null($name_to_delete)) {
            return parent::error('folder-name');
        }

        if (!parent::exists($file_to_delete)) {
            return parent::error('folder-not-found', ['folder' => $file_to_delete]);
        }

        if (parent::isDirectory($file_to_delete)) {
            if (!parent::directoryIsEmpty($file_to_delete)) {
                return parent::error('delete-folder');
            }

            parent::deleteDirectory($file_to_delete);
        } else {
            if (parent::fileIsImage($file_to_delete)) {
               parent::delete($thumb_to_delete);
            }

            parent::delete($file_to_delete);
        }

        event(new ImageWasDeleted($file_to_delete));

        return parent::$success_response;
    }
}
