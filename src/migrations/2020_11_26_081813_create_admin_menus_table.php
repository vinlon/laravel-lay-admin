<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdminMenusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_menus', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->bigInteger('pid')->default(0)->comment('父菜单ID, 为0时代表一级菜单');
            $table->string('title', 32)->comment('菜单标题');
            $table->string('icon', 32)->default('')->comment('菜单图标对应的class, 只有一级菜单需要设置');
            $table->string('path', 32)->default('')->comment('菜单跳转链接,父菜单不需要设置');
            $table->float('sequence')->comment('排序字段');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admin_menus');
    }
}
