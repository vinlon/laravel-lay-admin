<?php

namespace Vinlon\Laravel\LayAdmin\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Mews\Captcha\Facades\Captcha;
use Tymon\JWTAuth\JWTGuard;
use Vinlon\Laravel\LayAdmin\AdminRole;
use Vinlon\Laravel\LayAdmin\EmailCode;
use Vinlon\Laravel\LayAdmin\Exceptions\AdminException;
use Vinlon\Laravel\LayAdmin\Models\AdminUser;

class AuthController extends Controller
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
            throw new AdminException('已经存在管理员用户，无法重复初始化');
        }
        request()->validate([
            'username' => 'required',
            'password' => 'required',
            'email' => 'required',
            'real_name' => 'nullable',
        ]);
        $user = new AdminUser();
        $user->username = request()->username;
        $user->password = Hash::make(request()->password);
        $user->real_name = request()->real_name ?: '';
        $user->email = request()->email;
        $user->role_id = AdminRole::ROOT()->key;
        $user->save();
    }

    /** 获取图片验证码 */
    public function captcha()
    {
        return Captcha::create('math', true);
    }

    /** 用户名密码登录（加验证码） */
    public function passwordLoginWithCaptcha()
    {
        $param = request()->validate([
            'captcha' => 'required',
            'key' => 'required',
            'username' => 'required',
            'password' => 'required',
        ]);
        if (!captcha_api_check(request()->captcha, request()->key, 'math')) {
            throw new AdminException('验证码错误');
        }
        $user = AdminUser::findByName($param['username']);
        if ($user && Hash::check($param['password'], $user->password)) {
            $token = $this->auth->login($user);

            return ['access_token' => $token];
        }

        throw new AdminException('用户名或密码错误');
    }

    /** 发送邮箱验证码 */
    public function sendEmailCode()
    {
        request()->validate([
            'captcha' => 'required',
            'key' => 'required',
            'email' => 'required',
        ]);

        if (!captcha_api_check(request()->captcha, request()->key, 'math')) {
            throw new AdminException('验证码错误');
        }

        $email = request()->email;
        $user = AdminUser::query()->where('email', $email)->first();
        if (!$user) {
            throw new AdminException('该邮箱未关联任何账号');
        }
        $code = rand(100000, 999999);
        $minutes = 15;
        //发送邮件验证码
        Mail::to($email)->send(new EmailCode($code, 15));
        Cache::put('email_code:' . $email, $code, now()->addMinutes($minutes));
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
            throw new AdminException('邮箱未找到');
        }
        if (Cache::get('email_code:' . $email, '') != request()->verify_code) {
            throw new AdminException('验证码错误');
        }
        $user->password = Hash::make(request()->new_password);
        $user->save();
    }

    /** 查询个人信息 */
    public function profile()
    {
        /** @var AdminUser $user */
        $user = $this->auth->user();
        $user->role;

        return $user;
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
            throw new AdminException('当前密码错误');
        }
        $user->password = Hash::make($param['new_password']);
        $user->save();
    }
}
