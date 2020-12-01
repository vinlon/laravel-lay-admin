<?php

use Illuminate\Database\Seeder;
use Vinlon\Laravel\LayAdmin\Models\AdminRole;

class InitRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role = new AdminRole();
        $role->name = AdminRole::ROOT_ROLE_NAME;
        $role->description = '系统默认创建，不可修改';
        $role->save();
    }
}
