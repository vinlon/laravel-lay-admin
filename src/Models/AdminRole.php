<?php

namespace Vinlon\Laravel\LayAdmin\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Role.
 *
 * @property int        $id
 * @property null|mixed $created_at
 * @property null|mixed $updated_at
 * @property string     $name        角色名称
 * @property string     $description 角色描述
 * @property string[]   $menu_ids    角色拥有访问权限的菜单ID列表
 * @property bool       $is_root
 *
 * @method static \Illuminate\Database\Eloquent\Builder|AdminRole newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AdminRole newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AdminRole query()
 */
class AdminRole extends Model
{
    const ROOT_ROLE_NAME = '超级管理员';

    // 除 id, created_at, updated_at 外的其它字段都是 fillable
    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $appends = ['is_root'];

    /**
     * @return bool
     */
    public function getIsRootAttribute()
    {
        return self::ROOT_ROLE_NAME == $this->name;
    }

    /**
     * @return string[]
     */
    public function getMenuIdsAttribute()
    {
        return explode(',', $this->attributes['menu_ids']);
    }

    public function setMenuIdsAttribute(array $menuIds)
    {
        $this->attributes['menu_ids'] = implode(',', $menuIds);
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
