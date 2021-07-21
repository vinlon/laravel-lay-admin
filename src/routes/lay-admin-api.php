<?php

use Illuminate\Support\Facades\Route;

//用户初始化
Route::post('init', 'AuthController@initUser');

//用户名密码登录
Route::post('password_login', 'AuthController@passwordLogin');

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
    Route::get('roles/{id}', 'RoleController@getRole');
    Route::get('roles', 'RoleController@getRoleList');
    Route::post('roles', 'RoleController@saveRole');
    Route::delete('roles/{id}', 'RoleController@deleteRole');

    //用户管理
    Route::get('users', 'UserController@getUserList');
    Route::post('users', 'UserController@saveUser');
    Route::delete('users/{id}', 'UserController@deleteUser');
    Route::post('users/resetPassword', 'UserController@resetPassword');

    //内容管理
    Route::resource('contents', 'ContentController')->only(['index', 'store', 'destroy']);

    //图片管理
    Route::resource('images', 'ImageResourceController')->only(['index', 'store', 'destroy']);

    //编辑器图片上传
    Route::post('upload/editor/image', 'UploadController@uploadEditorImage');
    //资源图片上传
    Route::post('upload/resource/image', 'UploadController@uploadResourceImage');
});
