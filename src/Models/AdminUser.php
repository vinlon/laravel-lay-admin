<?php

namespace Vinlon\Laravel\LayAdmin\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as AuthUser;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Vinlon\Laravel\LayAdmin\AdminRole;

/**
 * App\Models\AdminUser.
 *
 * @property int                                $id
 * @property null|mixed                         $created_at
 * @property null|mixed                         $updated_at
 * @property string                             $username   登录用户名
 * @property string                             $password   密码
 * @property int                                $role_id    角色对应ID
 * @property null|string                        $real_name  真实姓名
 * @property null|string                        $mobile     手机号
 * @property null|string                        $email      邮箱
 * @property int                                $status     用户状态
 * @property \Vinlon\Laravel\LayAdmin\AdminRole $role
 *
 * @method static \Illuminate\Database\Eloquent\Builder|AdminUser newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AdminUser newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AdminUser query()
 */
class AdminUser extends AuthUser implements JWTSubject
{
    // 除 id, created_at, updated_at 外的其它字段都是 fillable
    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $hidden = ['password'];

    protected $appends = [
        'role',
    ];

    public function getRoleAttribute()
    {
        $roleClass = config('lay-admin.role_class', AdminRole::class);

        return $roleClass::fromKey($this->role_id);
    }

    /**
     * @param mixed $name
     *
     * @return null|AdminUser|\Illuminate\Database\Eloquent\Builder|Model|object
     */
    public static function findByName($name)
    {
        return self::query()->where('username', $name)->first();
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
