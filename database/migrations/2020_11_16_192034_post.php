<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Post extends Migration
{
    public function up()
    {
        Schema::create('post', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title_en');
            $table->string('title_fa');
            $table->string('title_fr');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('categories');
    }
}
