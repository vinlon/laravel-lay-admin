<?php

namespace Vinlon\Laravel\LayAdmin\Controllers;

use Illuminate\Support\Facades\Hash;
use Vinlon\Laravel\LayAdmin\Models\AdminUser;

class UserController extends BaseController
{
    /** 查询用户列表 */
    public function getUserList()
    {
        $users = AdminUser::query()->get();

        return $this->successResponse($users->toArray());
    }

    /** 保存用户信息 */
    public function saveUser()
    {
        $param = request()->validate([
            'username' => 'required',
            'role_id' => 'required',
            'password' => 'nullable',
        ]);
        $user = $this->getEntity(AdminUser::class);
        if (!$user->id) {
            $user->password = Hash::make($param['password']);
        }
        $user->username = $param['username'];
        $user->role_id = $param['role_id'];
        $user->save();

        return $this->successResponse();
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

        return $this->successResponse();
    }

    /** 删除用户 */
    public function deleteUser($id)
    {
        $user = AdminUser::query()->find($id);
        $user->delete();

        return $this->successResponse();
    }
}
