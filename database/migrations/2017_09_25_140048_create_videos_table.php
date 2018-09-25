<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVideosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('videos', function (Blueprint $table) {
            $table->uuid('v_id');
            $table->primary('v_id');
            $table->string('resolution', 30)->nullable();
            $table->char('movie_id',36)->nullable();
            $table->char('episode_id',36)->nullable();
            $table->string('video_url');
            $table->string('type')->default('s3');
            $table->timestamps();

            $table->foreign('movie_id')->references('m_id')->on('movies')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('episode_id')->references('id')->on('episodes')->onDelete('cascade')->onUpdate('cascade');

        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('videos');

    }
}
