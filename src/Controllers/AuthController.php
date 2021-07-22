<?php

namespace Vinlon\Laravel\LayAdmin\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Tymon\JWTAuth\JWTGuard;
use Vinlon\Laravel\LayAdmin\EmailCode;
use Vinlon\Laravel\LayAdmin\Models\AdminRole;
use Vinlon\Laravel\LayAdmin\Models\AdminUser;

class AuthController extends BaseController
{
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

    /** 管理后台入口  */
    public function home()
    {
        $userCount = AdminUser::query()->count();
        $title = config('lay-admin.display_name');

        return view('lay-admin::index', [
            'title' => $title,
            'view_path' => './lay-admin/',
            'debug' => config('app.debug') ? 1 : 0,
            'static_version' => time(), //不缓存表态资源
            'install' => 0 == $userCount,
        ]);
    }

    /** 初始化管理员 */
    public function initUser()
    {
        if (AdminUser::query()->count() > 0) {
            return $this->errorResponse('', '已经存在管理员用户，无法重复初始化');
        }
        request()->validate([
            'username' => 'required',
            'password' => 'required',
            'email' => 'required',
            'real_name' => 'nullable',
        ]);
        $role = AdminRole::query()->where('name', AdminRole::ROOT_ROLE_NAME)->first();
        if (!$role) {
            $role = new AdminRole();
            $role->name = AdminRole::ROOT_ROLE_NAME;
            $role->description = '系统默认创建，不可修改';
            $role->save();
        }
        $user = new AdminUser();
        $user->username = request()->username;
        $user->password = Hash::make(request()->password);
        $user->real_name = request()->real_name ?: '';
        $user->email = request()->email;
        $user->role_id = $role->id;
        $user->save();

        return $this->successResponse();
    }

    /** 用户名密码登录 */
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

    /** 发送邮箱验证码 */
    public function sendEmailCode()
    {
        request()->validate([
            'email' => 'required',
        ]);
        $email = request()->email;
        $user = AdminUser::query()->where('email', $email)->first();
        if (!$user) {
            return $this->errorResponse('', '该邮箱未关联任何账号');
        }
        $code = rand(100000, 999999);
        $minutes = 15;
        //发送邮件验证码
        Mail::send(new EmailCode($code, 15));
        Cache::put('email_code:' . $email, $code, now()->addMinutes($minutes));

        return $this->successResponse();
    }

    /** 通过邮箱重置登录密码 */
    public function resetPasswordByEmail()
    {
        request()->validate([
            'email' => 'required',
            'verify_code' => 'required',
            'new_password' => 'required',
        ]);

        $email = request()->email;
        $user = AdminUser::query()->where('email', $email)->first();
        if (!$user) {
            return $this->errorResponse('', '邮箱未找到');
        }
        if (Cache::get('email_code:' . $email, '') != request()->verify_code) {
            return $this->errorResponse('', '验证码错误');
        }
        $user->password = Hash::make(request()->new_password);
        $user->save();

        return $this->successResponse();
    }

    /** 查询个人信息 */
    public function profile()
    {
        /** @var AdminUser $user */
        $user = $this->auth->user();
        $user->role;

        return $this->successResponse($user->toArray());
    }

    /** 更新个人信息 */
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

    /** 修改密码 */
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
