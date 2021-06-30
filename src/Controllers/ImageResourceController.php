<?php

namespace Vinlon\Laravel\LayAdmin\Controllers;

use Vinlon\Laravel\LayAdmin\Models\ImageResource;

class ImageResourceController extends BaseController
{
    public function index()
    {
        $query = ImageResource::query()
            ->when(request()->key, function ($q) {
                return $q->where('image_key', 'like', '%' . request()->key . '%');
            })->orderByDesc('updated_at');

        return $this->paginateResponse($query);
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
            return $this->errorResponse('', '关键字已存在,请不要重复添加');
        }

        /** @var ImageResource $image */
        $image = $this->getEntity(ImageResource::class);
        $image->fill($params);
        $image->save();

        return $this->successResponse();
    }

    public function destroy($id)
    {
        ImageResource::destroy($id);

        return $this->successResponse();
    }
}
