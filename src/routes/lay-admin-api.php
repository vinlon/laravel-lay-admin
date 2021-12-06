<?php

use Illuminate\Support\Facades\Route;

//用户初始化
Route::post('init', 'AuthController@initUser');

//获取图片验证码
Route::get('captcha', 'AuthController@captcha');

//用户名密码登录
Route::post('password_login', 'AuthController@passwordLogin');
Route::post('password_login_captcha', 'AuthController@passwordLoginWithCaptcha');

//发送邮箱验证码
Route::post('email_code', 'AuthController@sendEmailCode');
//重置密码
Route::post('reset_password', 'AuthController@resetPasswordByEmail');

Route::middleware('auth:lay-admin')->group(function () {
    Route::get('profile', 'AuthController@profile');
    Route::post('profile', 'AuthController@updateProfile');
    Route::post('changePassword', 'AuthController@changePassword');
    Route::get('config', 'AuthController@getConfig');
    Route::post('config', 'AuthController@setConfig');

    //菜单管理
    Route::get('sidebar', 'MenuController@sidebar');
    Route::get('menuTree', 'MenuController@getMenuTree');
    Route::post('menus', 'MenuController@saveMenu');
    Route::get('menus', 'MenuController@getMenuList');
    Route::delete('menus/{id}', 'MenuController@deleteMenu');

    //角色管理
    Route::get('roles', 'RoleController@getRoleList');

    //用户管理
    Route::get('users', 'UserController@getUserList');
    Route::post('users', 'UserController@saveUser');
    Route::delete('users/{id}', 'UserController@deleteUser');
    Route::post('users/resetPassword', 'UserController@resetPassword');

    //编辑器图片上传
    Route::post('upload/editor/image', 'UploadController@uploadEditorImage');
});
