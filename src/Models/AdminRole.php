<?php

namespace Vinlon\Laravel\LayAdmin\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Role
 *
 * @property int $id
 * @property mixed|null $created_at
 * @property mixed|null $updated_at
 * @property string $name 角色名称
 * @property string $description 角色描述
 * @property string[] $menu_ids 角色拥有访问权限的菜单ID列表
 * @property-read bool $is_root
 * @method static \Illuminate\Database\Eloquent\Builder|AdminRole newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AdminRole newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AdminRole query()
 */
class AdminRole extends Model
{
    const ROOT_ROLE_NAME = '超级管理员';

    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];

    protected $appends = ['is_root'];

    /**
     * @return bool
     */
    public function getIsRootAttribute()
    {
        return $this->name == self::ROOT_ROLE_NAME;
    }

    /**
     * @return string[]
     */
    public function getMenuIdsAttribute()
    {
        return explode(',', $this->attributes['menu_ids']);
    }

    /**
     * @param array $menuIds
     */
    public function setMenuIdsAttribute(array $menuIds)
    {
        $this->attributes['menu_ids'] = implode(',', $menuIds);
    }
}
