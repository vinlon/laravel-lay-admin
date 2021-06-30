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
 * @property string                          $image_key  图片查询关键字
 * @property string                          $intro      图片简介
 * @property string                          $image_url  图片链接
 *
 * @method static \Illuminate\Database\Eloquent\Builder|RichContent newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RichContent newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RichContent query()
 */
class ImageResource extends Model
{
    // 除 id, created_at, updated_at 外的其它字段都是 fillable
    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
