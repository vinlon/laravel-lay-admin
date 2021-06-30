<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateImageResourcesTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('image_resources', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('image_key', 64)->index()->comment('图片查询关键字');
            $table->string('intro', 128)->comment('图片描述');
            $table->string('image_url', 512)->comment('图片地址');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('image_resources');
    }
}
