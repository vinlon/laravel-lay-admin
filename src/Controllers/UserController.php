<?php

namespace Vinlon\Laravel\LayAdmin\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Hash;
use Vinlon\Laravel\LayAdmin\Models\AdminUser;

class UserController extends Controller
{
    /** 查询用户列表 */
    public function getUserList()
    {
        $users = AdminUser::query()->get();

        return $users->toArray();
    }

    /** 保存用户信息 */
    public function saveUser()
    {
        $param = request()->validate([
            'username' => 'required',
            'role_id' => 'required',
            'password' => 'nullable',
        ]);

        /** @var AdminUser $user */
        $user = get_entity(AdminUser::class);
        if (!$user->id) {
            $user->password = Hash::make($param['password']);
        }
        $user->username = $param['username'];
        $user->role_id = $param['role_id'];
        $user->save();
    }

    /** 重置密码 */
    public function resetPassword()
    {
        $param = request()->validate([
            'id' => 'required',
            'password' => 'required',
        ]);
        $user = AdminUser::query()->find($param['id']);
        $user->password = Hash::make($param['password']);
        $user->save();
    }

    /** 删除用户 */
    public function deleteUser($id)
    {
        $user = AdminUser::query()->find($id);
        $user->delete();
    }
}
