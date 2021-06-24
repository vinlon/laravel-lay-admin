<?php

namespace Vinlon\Laravel\LayAdmin\Controllers;

use Vinlon\Laravel\LayAdmin\Models\RichContent;

class ContentController extends BaseController
{
    public function index()
    {
        $query = RichContent::query()
            ->when(request()->key, function ($q) {
                return $q->where('content_key', 'like', '%' . request()->key . '%');
            })->orderByDesc('updated_at');

        return $this->paginateResponse($query);
    }

    public function store()
    {
        $params = request()->validate([
            'content_key' => 'required',
            'intro' => 'required',
            'content' => 'required',
        ]);

        $exists = RichContent::query()->where('content_key', request()->content_key)->first();
        if ($exists && $exists->id != request()->id) {
            return $this->errorResponse('', '关键字已存在,请不要重复添加');
        }

        /** @var RichContent $content */
        $content = $this->getEntity(RichContent::class);
        $content->fill($params);
        $content->save();

        return $this->successResponse();
    }

    public function destroy($id)
    {
        RichContent::destroy($id);

        return $this->successResponse();
    }
}
