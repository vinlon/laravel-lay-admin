<?php

namespace Vinlon\Laravel\LayAdmin\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputArgument;
use Vinlon\Laravel\LayAdmin\Models\AdminRole;
use Vinlon\Laravel\LayAdmin\Models\AdminUser;

class CreateAdminUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lay-admin:super';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '添加超级管理员, 自动生成随机密码';

    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        parent::__construct();
        $this->addArgument('username', InputArgument::REQUIRED, '登录用户名');
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $username = $this->input->getArgument('username');
        $user = AdminUser::findByName($username);
        if ($user) {
            $this->output->error("用户名 [{$username}] 已存在");

            return 1;
        }
        $role = AdminRole::query()->where('name', AdminRole::ROOT_ROLE_NAME)->first();
        if (!$role) {
            $role = new AdminRole();
            $role->name = AdminRole::ROOT_ROLE_NAME;
            $role->description = '系统默认创建，不可修改';
            $role->save();
        }
        $password = Str::random(8);
        $user = new AdminUser();
        $user->username = $username;
        $user->password = Hash::make($password);
        $user->role_id = $role->id;
        $user->save();
        $this->output->writeln('=====用户创建成功=====');
        $this->output->writeln('用户名：' . $username);
        $this->output->writeln('密码：' . $password);

        return 0;
    }
}
