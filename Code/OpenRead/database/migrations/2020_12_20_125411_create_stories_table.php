<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stories', function (Blueprint $table) {
            $table->string('story_id', 7);
            $table->string('username', 50);
            $table->string('story_title', 50);
            $table->string('cover', 255);
            $table->string('status', 10);
            $table->text('sinopsis');
            $table->integer('views')->default(0);

            $table->primary('story_id');
            $table->foreign('username')
                  ->constrained('users', 'username')
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
        Schema::dropIfExists('stories');
    }
}
