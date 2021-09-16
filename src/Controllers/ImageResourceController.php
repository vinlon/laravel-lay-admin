<?php

namespace Vinlon\Laravel\LayAdmin\Controllers;

use Illuminate\Routing\Controller;
use Vinlon\Laravel\LayAdmin\Exceptions\AdminException;
use Vinlon\Laravel\LayAdmin\Models\ImageResource;

class ImageResourceController extends Controller
{
    public function index()
    {
        $query = ImageResource::query()
            ->when(request()->key, function ($q) {
                return $q->where('image_key', 'like', '%' . request()->key . '%');
            })->orderByDesc('updated_at');

        return paginate_result(request(), $query);
    }

    public function store()
    {
        $params = request()->validate([
            'image_key' => 'required',
            'intro' => 'required',
            'image_url' => 'required',
        ]);

        $exists = ImageResource::query()->where('image_key', request()->image_key)->first();
        if ($exists && $exists->id != request()->id) {
            throw new AdminException('关键字已存在,请不要重复添加');
        }

        /** @var ImageResource $image */
        $image = get_entity(ImageResource::class);
        $image->fill($params);
        $image->save();
    }

    public function destroy($id)
    {
        ImageResource::destroy($id);
    }
}
