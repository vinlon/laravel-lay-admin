<?php

namespace Vinlon\Laravel\LayAdmin\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\RichContent.
 *
 * @property int                             $id
 * @property null|\Illuminate\Support\Carbon $created_at
 * @property null|\Illuminate\Support\Carbon $updated_at
 * @property string                          $content_key 内容查询关键字
 * @property string                          $intro       内容简介
 * @property string                          $content     内容正文
 *
 * @method static \Illuminate\Database\Eloquent\Builder|RichContent newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RichContent newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RichContent query()
 */
class RichContent extends Model
{
    // 除 id, created_at, updated_at 外的其它字段都是 fillable
    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
