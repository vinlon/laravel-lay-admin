<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

Route::namespace('Vinlon\Laravel\LayAdmin\Controllers')->prefix('admin')->group(function () {
    //Admin后台入口
    Route::get('/', 'AuthController@home');

    //用户名密码登录
    Route::post('password_login', 'AuthController@passwordLogin');

    Route::middleware('auth:lay-admin')->group(function () {
        Route::get('profile', 'AuthController@profile');
        Route::post('profile', 'AuthController@updateProfile');
        Route::post('changePassword', 'AuthController@changePassword');
        Route::get('config', 'AuthController@getConfig');
        Route::post('config', 'AuthController@setConfig');

        Route::get('sidebar', 'MenuController@sidebar');
        Route::get('menuTree', 'MenuController@getMenuTree');
        Route::post('menu', 'MenuController@saveMenu');
        Route::get('menu', 'MenuController@getMenuList');

        Route::get('role/{roleId}', 'RoleController@getRole');
        Route::get('role', 'RoleController@getRoleList');
        Route::post('role', 'RoleController@saveRole');
        Route::delete('role', 'RoleController@deleteRole');

        Route::get('user', 'UserController@getUserList');
        Route::post('user', 'UserController@saveUser');
        Route::delete('user', 'UserController@deleteUser');
        Route::post('user/resetPassword', 'UserController@resetPassword');
    });
});
