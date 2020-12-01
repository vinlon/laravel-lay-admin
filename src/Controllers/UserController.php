<?php


namespace Vinlon\Laravel\LayAdmin\Controllers;


use Illuminate\Support\Facades\Hash;
use Vinlon\Laravel\LayAdmin\Models\AdminUser;

class UserController extends BaseController
{
    public function getUserList()
    {
        $users = AdminUser::with('role')->get();
        return $this->successResponse($users->toArray());
    }

    public function saveUser()
    {
        $param = request()->validate([
            'username' => 'required',
            'role_id' => 'required',
            'password' => 'nullable',
        ]);
        $user = $this->getEntity(AdminUser::class);
        if (!$user->id) {
            $user->password = Hash::make($param['username']);
        }
        $user->username = $param['username'];
        $user->role_id = $param['role_id'];
        $user->save();
        return $this->successResponse();
    }

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


    public function deleteUser($id)
    {
        $user = AdminUser::query()->find($id);
        $user->delete();
        return $this->successResponse();
    }
}