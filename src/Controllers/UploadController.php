<?php

namespace Vinlon\Laravel\LayAdmin\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Storage;

class UploadController extends Controller
{
    /** 上传编辑器图片 */
    public function uploadEditorImage()
    {
        $file = request()->file('file');
        $publicStorage = Storage::disk();
        $path = $publicStorage->putFile('tinymce', $file);

        return [
            'image_url' => $publicStorage->url($path),
        ];
    }
}
