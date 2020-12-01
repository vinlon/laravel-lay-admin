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
        Route::post('menus', 'MenuController@saveMenu');
        Route::get('menus', 'MenuController@getMenuList');
        Route::delete('menus/{id}', 'MenuController@deleteMenu');

        Route::get('roles/{id}', 'RoleController@getRole');
        Route::get('roles', 'RoleController@getRoleList');
        Route::post('roles', 'RoleController@saveRole');
        Route::delete('roles/{id}', 'RoleController@deleteRole');

        Route::get('users', 'UserController@getUserList');
        Route::post('users', 'UserController@saveUser');
        Route::delete('users/{id}', 'UserController@deleteUser');
        Route::post('users/resetPassword', 'UserController@resetPassword');
    });
});
