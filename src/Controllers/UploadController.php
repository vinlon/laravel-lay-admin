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
        $path = $publicStorage->putFile('resource/editor', $file);

        return [
            'image_url' => $publicStorage->url($path),
        ];
    }

    /** 上传资源图片 */
    public function uploadResourceImage()
    {
        $file = request()->file('file');

        $publicStorage = Storage::disk();
        $path = $publicStorage->putFile('resource/image', $file);

        return [
            'image_url' => $publicStorage->url($path),
        ];
    }
}
