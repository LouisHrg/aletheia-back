<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArticlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->longText('content')->nullable();
            $table->string('url', 500);
            $table->string('image', 500)->nullable();
            $table->string('lang',4)->nullable();
            $table->string('title', 255);
            $table->integer('trust')->default(0);
            $table->integer('biased')->default(0);
            $table->integer('clickbait')->default(0);
            $table->integer('score')->nullable();
            $table->string('edition')->nullable();
            $table->unsignedBigInteger('idOzae')->unique()->nullable();
            $table->integer('source_id')->unsigned();
            $table->foreign('source_id')->references('id')->on('sources');
            $table->integer('word_id')->nullable()->unsigned();
            $table->foreign('word_id')->references('id')->on('words');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('articles');
    }
}
