<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRichContentsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('rich_contents', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('content_key', 64)->unique()->comment('内容查询关键字');
            $table->string('intro', 128)->comment('内容简介');
            $table->longText('content')->comment('内容正文');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('rich_contents');
    }
}
