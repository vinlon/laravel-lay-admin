<?php

namespace Vinlon\Laravel\LayAdmin\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\JWTGuard;
use Vinlon\Laravel\LayAdmin\Models\AdminConfig;
use Vinlon\Laravel\LayAdmin\Models\AdminUser;

class AuthController extends BaseController
{
    const OPT_ADMIN_PREFIX = 'admin:config:';

    /**
     * @var JWTGuard
     */
    private $auth;

    /**
     * AuthController constructor.
     */
    public function __construct()
    {
        $this->auth = Auth::guard('lay-admin');
    }

    public function home()
    {
        $title = AdminConfig::get(self::OPT_ADMIN_PREFIX . 'title', '后台管理系统');

        return view('lay-admin::index', [
            'title' => $title,
            'view_path' => './lay-admin/',
            'debug' => config('app.debug') ? 1 : 0,
            'static_version' => time(), //不缓存表态资源
        ]);
    }

    public function passwordLogin()
    {
        $param = request()->validate([
            'username' => 'required',
            'password' => 'required',
        ]);
        $user = AdminUser::findByName($param['username']);
        if ($user && Hash::check($param['password'], $user->password)) {
            $token = $this->auth->login($user);

            return $this->successResponse(['access_token' => $token]);
        }

        return $this->errorResponse('login_faied', '用户名或密码错误');
    }

    public function profile()
    {
        /** @var AdminUser $user */
        $user = $this->auth->user();
        $user->role;

        return $this->successResponse($user->toArray());
    }

    public function updateProfile()
    {
        $param = request()->validate([
            'real_name' => 'nullable',
            'mobile' => 'nullable',
            'email' => 'nullable',
        ]);
        /** @var AdminUser $user */
        $user = $this->auth->user();
        $user->real_name = $param['real_name'] ?? '';
        $user->mobile = $param['mobile'] ?? '';
        $user->email = $param['email'] ?? '';
        $user->save();

        return $this->successResponse();
    }

    public function changePassword()
    {
        $param = request()->validate([
            'old_password' => 'required',
            'new_password' => 'required',
        ]);
        /** @var AdminUser $user */
        $user = Auth::user();
        if (!Hash::check($param['old_password'], $user->password)) {
            return $this->errorResponse('password_incorrect', '当前密码错误');
        }
        $user->password = Hash::make($param['new_password']);
        $user->save();

        return $this->successResponse();
    }
}
