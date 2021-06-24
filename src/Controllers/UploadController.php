<?php

namespace Vinlon\Laravel\LayAdmin\Controllers;

use Illuminate\Support\Facades\Storage;

class UploadController extends BaseController
{
    public function uploadEditorImage()
    {
        $file = request()->file('file');
        $publicStorage = Storage::disk('public');
        $path = $publicStorage->putFile('image/editor', $file);

        return $this->successResponse([
            'image_url' => $publicStorage->url($path),
        ]);
    }
}
