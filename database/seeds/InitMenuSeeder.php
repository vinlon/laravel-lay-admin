<?php

use Illuminate\Database\Seeder;
use Vinlon\Laravel\LayAdmin\Models\AdminMenu;

class InitMenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        \Illuminate\Support\Facades\DB::transaction(function () {
            $now = \Carbon\Carbon::now();

            //初始化菜单
            $configId = AdminMenu::query()->insertGetId([
                'title' => '设置',
                'icon' => 'layui-icon-set',
                'sequence' => 100,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
            AdminMenu::query()->insert([
                [
                    'pid' => $configId,
                    'title' => '系统设置',
                    'path' => '_base/system/config',
                    'sequence' => 101,
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                [
                    'pid' => $configId,
                    'title' => '菜单设置',
                    'path' => '_base/system/menu/',
                    'sequence' => 102,
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
            ]);

            $userId = AdminMenu::query()->insertGetId([
                'title' => '用户',
                'icon' => 'layui-icon-user',
                'sequence' => 200,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
            AdminMenu::query()->insert([
                [
                    'pid' => $userId,
                    'title' => '后台管理员',
                    'path' => '_base/user/user/',
                    'sequence' => 201,
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                [
                    'pid' => $userId,
                    'title' => '角色管理',
                    'path' => '_base/user/role/',
                    'sequence' => 202,
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
            ]);

            $myId = AdminMenu::query()->insertGetId([
                'title' => '我的',
                'icon' => 'layui-icon-username',
                'sequence' => 300,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
            AdminMenu::query()->insert([
                [
                    'pid' => $myId,
                    'title' => '修改密码',
                    'path' => '_base/user/user/password',
                    'sequence' => 302,
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                [
                    'pid' => $myId,
                    'title' => '基本资料',
                    'path' => '_base/user/user/info',
                    'sequence' => 301,
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
            ]);
        });
    }
}
