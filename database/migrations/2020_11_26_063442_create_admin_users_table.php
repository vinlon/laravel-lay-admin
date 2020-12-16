<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdminUsersTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('admin_users', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('username', 64)->comment('登录用户名');
            $table->string('password', 64)->comment('密码');
            $table->bigInteger('role_id')->comment('角色对应ID');
            $table->string('real_name', 64)->nullable()->comment('真实姓名');
            $table->string('mobile', 18)->nullable()->comment('手机号');
            $table->string('email', 64)->nullable()->comment('邮箱');
            $table->tinyInteger('status')->default(0)->comment('用户状态');
            $table->unique(['username']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('admin_users');
    }
}
