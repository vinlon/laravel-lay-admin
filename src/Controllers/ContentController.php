<?php

namespace Vinlon\Laravel\LayAdmin\Controllers;

use Illuminate\Routing\Controller;
use Vinlon\Laravel\LayAdmin\Exceptions\AdminException;
use Vinlon\Laravel\LayAdmin\Models\RichContent;

class ContentController extends Controller
{
    public function index()
    {
        $query = RichContent::query()
            ->when(request()->key, function ($q) {
                return $q->where('content_key', 'like', '%' . request()->key . '%');
            })->orderByDesc('updated_at');

        return paginate_result(request(), $query);
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
            throw new AdminException('关键字已存在,请不要重复添加');
        }

        /** @var RichContent $content */
        $content = get_entity(RichContent::class);
        $content->fill($params);
        $content->save();
    }

    public function destroy($id)
    {
        RichContent::destroy($id);
    }
}
