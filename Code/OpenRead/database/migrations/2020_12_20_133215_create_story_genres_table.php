<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStoryGenresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('story_genres', function (Blueprint $table) {
            $table->string('story_id', 7);
            $table->string('genre_id', 7);

            $table->primary(['story_id', 'genre_id']);
            $table->foreign('story_id')
                  ->references('story_id')
                  ->on('stories')
                  ->onUpdate('cascade')
                  ->onDelete('cascade');
            $table->foreign('genre_id')
                  ->references('genre_id')
                  ->on('genres')
                  ->onUpdate('cascade')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('story_genres');
    }
}
