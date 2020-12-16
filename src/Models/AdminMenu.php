<?php

namespace Vinlon\Laravel\LayAdmin\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int        $id
 * @property null|mixed $created_at
 * @property null|mixed $updated_at
 * @property int        $pid        父菜单ID, 为0时代表一级菜单
 * @property string     $title      菜单标题
 * @property string     $icon       菜单图标对应的class, 只有一级菜单需要设置
 * @property string     $path       菜单跳转链接,父菜单不需要设置
 * @property float      $sequence   排序字段
 *
 * @method static \Illuminate\Database\Eloquent\Builder|AdminMenu newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AdminMenu newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AdminMenu query()
 */
class AdminMenu extends Model
{
    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];

    public static function findOrderedListByPid($pid)
    {
        return self::query()->where('pid', $pid)->orderBy('sequence')->get();
    }
}
