<?php

namespace Vinlon\Laravel\LayAdmin\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\Console\Input\InputArgument;
use Vinlon\Laravel\LayAdmin\Models\AdminUser;

class ResetPassword extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lay-admin:reset-password';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '重置管理后台用户密码';

    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        parent::__construct();
        $this->addArgument('username', InputArgument::REQUIRED, '用户名');
        $this->addArgument('password', InputArgument::REQUIRED, '密码');
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $name = $this->input->getArgument('username');
        $password = $this->input->getArgument('password');
        $user = AdminUser::findByName($name);
        if (!$user) {
            $this->output->error("用户 [{$name}] 未找到");

            return 1;
        }
        $user->password = Hash::make($password);
        $user->save();
        $this->output->writeln('=====重置成功=====');
        $this->output->writeln('当前密码为： ' . $password);

        return 0;
    }
}
